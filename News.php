<?php

Class News extends CI_Controller{

	public function __construct(){
		parent:: __construct();
		$this->load->helper('url', 'form');
		$this->load->model('News_Model');
	}

	public function index(){
		$data['news'] = $this->News_Model->getnewsdata();
		$this->load->view('vwnews',$data);
	}

	public function add_news(){
		$this->load->view('add_news');
	}

	public function save(){
    $n_id=$this->input->post('n_id');
    $n_image=$this->input->post('n_image');
		$title = $this->input->post('title');
		$description = $this->input->post('description');
    $date = date('M d, Y');

		    $config['upload_path'] = './uploads/News/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 2000;
        $config['max_width'] = 1500;
        $config['max_height'] = 1500;
        $this->load->library('upload', $config);
        if ($n_id) {
          if ($_FILES['userfile']['name']=='') {
                $data = array(
                          'n_title'=>$title,
                          'n_description'=>$description,
                          'n_image'=>$n_image
                          );
                if ($this->News_Model->updatenewsbyid($data,$n_id)) {
                $this->session->set_flashdata("msg","<span><b><p>Successfull...Data Updated</p></b></span>");
                redirect('News');
                }else{
                  $this->session->set_flashdata("msg","<span><b><p>Sorry...Data is not Updated</p></b></span>");
                redirect('News/add_news');
                }
          }else{
                if ($this->upload->do_upload('userfile')) {
                  $image = array('image_metadata' => $this->upload->data());
                  $data = array(
                            'n_title'=>$title,
                            'n_description'=>$description,
                            'n_image'=>$image['image_metadata']['file_name']
                            );
                  if ($this->News_Model->updatenewsbyid1($data,$n_id,$n_image)) {
                  $this->session->set_flashdata("msg","<span><b><p>Successfull...Data Updated</p></b></span>");
                  redirect('News');
                  }else{
                    $this->session->set_flashdata("msg","<span><b><p>Sorry...Data is not Updated</p></b></span>");
                  redirect('News/add_news');
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
                            'n_title'=>$title,
                            'n_description'=>$description,
                            'n_image'=>$image['image_metadata']['file_name'],
                            'n_date'=>$date
                            );
                if ($this->News_Model->insertnewsdata($data)) {
                  $this->session->set_flashdata("msg","<span><b><p>Successfull...Data Inserted</p></b></span>");
                redirect('News');
                }else{
                  $this->session->set_flashdata("msg","<span><b><p>Sorry...Data is not inserted</p></b></span>");
                redirect('News/add_News');
                }
              }
              else {
                $error = array('error' => $this->upload->display_errors());
                echo "hii";
              } 
          }     
	}

  public function edit($id){
    $data['news']=$this->News_Model->getnewsdatabyid($id);
    $this->load->view('add_news',$data);
  }

  public function delete($id){
    $n_image =  $this->uri->segment(4);
     if($this->News_Model->removenewsdata($id,$n_image)){
      $this->session->set_flashdata("msg","<span><b><p>Successfull...Deleted</p></b></span>");
       redirect('News');
     }else{
      $this->session->set_flashdata("msg","<span><b><p>Sorry...Not Deleted</p></b></span>");
       redirect('News');
     }
  }
}


?>