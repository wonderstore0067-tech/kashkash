<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	public function __construct(){		
        parent::__construct();		
        $this->load->model('dynamic_model');  
    }
	public function index()
	{
		$this->load->view('welcome_message');
	}
	public function privacy_policy(){  
         $where = array('slug' =>'privacy_policy');
         $data['static_data'] = $this->dynamic_model->getdatafromtable('static_page',$where);
         $this->load->view('privacy',$data);
    }
    public function terms_condition(){
         $where = array('slug' =>'term-and-condition');
         $data['static_data'] = $this->dynamic_model->getdatafromtable('static_page',$where);
         $this->load->view('terms_condition',$data);
    }
    public function about_app(){
        $where = array('slug' =>'about-us');
		$data['static_data'] = $this->dynamic_model->getdatafromtable('static_page',$where);
        $this->load->view('aboutapp',$data);
    }
    public function banner_details($advertisement_id=''){
        $where = array('Id' =>decode($advertisement_id));
        $data['banner_data'] = $this->dynamic_model->getdatafromtable('manage_advertisement_images',$where);
        $this->load->view('banner_details',$data);
    }
    
}
