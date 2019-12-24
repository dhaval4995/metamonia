<?php

Class Gallery extends CI_Controller{

	public function __construct(){
		parent:: __construct();
		$this->load->helper('url', 'form');
		$this->load->model('Gallery_Model');
	}

	public function index(){
		$data['gallery'] = $this->Gallery_Model->getgallerydata();
		$this->load->view('vwgallery',$data);
	}

	public function add_gallery(){
		$this->load->view('add_gallery');
	}

	public function save(){
    $g_id=$this->input->post('g_id');
    $g_image=$this->input->post('g_image');
		$title = $this->input->post('title');
		$subtitle = $this->input->post('subtitle');
    $date = date('M d, Y');

		    $config['upload_path'] = './uploads/gallery/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 2000;
        $config['max_width'] = 1500;
        $config['max_height'] = 1500;
        $this->load->library('upload', $config);
        if ($g_id) {
          if ($_FILES['userfile']['name']=='') {
                $data = array(
                          'g_title'=>$title,
                          'g_subtitle'=>$subtitle,
                          'g_image'=>$g_image
                          );
                if ($this->Gallery_Model->updategallerybyid($data,$g_id)) {
                $this->session->set_flashdata("msg","<span><b><p>Successfull...Data Updated</p></b></span>");
                redirect('Gallery');
                }else{
                  $this->session->set_flashdata("msg","<span><b><p>Sorry...Data is not Updated</p></b></span>");
                redirect('Gallery/add_gallery');
                }
          }else{
                if ($this->upload->do_upload('userfile')) {
                  $image = array('image_metadata' => $this->upload->data());
                  $data = array(
                            'g_title'=>$title,
                            'g_subtitle'=>$subtitle,
                            'g_image'=>$image['image_metadata']['file_name']
                            );
                  if ($this->Gallery_Model->updategallerybyid1($data,$g_id,$g_image)) {
                  $this->session->set_flashdata("msg","<span><b><p>Successfull...Data Updated</p></b></span>");
                  redirect('Gallery');
                  }else{
                    $this->session->set_flashdata("msg","<span><b><p>Sorry...Data is not Updated</p></b></span>");
                  redirect('Gallery/add_gallery');
                  }
                }
                else {
                $error = array('error' => $this->upload->display_errors());
                echo "hii";
                }   
          }     
      }else{
              if ($this->upload->do_upload('userfile')) {
                $image = array('image_metadata' => $this->upload->data());
                $data = array(
                            'g_title'=>$title,
                            'g_subtitle'=>$subtitle,
                            'g_image'=>$image['image_metadata']['file_name'],
                            'g_date'=>$date
                            );
                if ($this->Gallery_Model->insertgallerydata($data)) {
                  $this->session->set_flashdata("msg","<span><b><p>Successfull...Data Inserted</p></b></span>");
                redirect('Gallery');
                }else{
                  $this->session->set_flashdata("msg","<span><b><p>Sorry...Data is not inserted</p></b></span>");
                redirect('Gallery/add_gallery');
                }
              }
              else {
                $error = array('error' => $this->upload->display_errors());
                echo "hii";
              } 
          }     
	}

  public function edit($id){
    $data['gallery']=$this->Gallery_Model->getgallerydatabyid($id);
    $this->load->view('add_gallery',$data);
  }

  public function delete($id){
    $g_image =  $this->uri->segment(4);
     if($this->Gallery_Model->removegallerydata($id,$g_image)){
      $this->session->set_flashdata("msg","<span><b><p>Successfull...Deleted</p></b></span>");
       redirect('Gallery');
     }else{
      $this->session->set_flashdata("msg","<span><b><p>Sorry...Not Deleted</p></b></span>");
       redirect('Gallery');
     }
  }
}


?>