<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_Controller extends MX_Controller {
	public function __construct(){
		parent::__construct();
	}
	public function index(){
		
	}
	public function frontendtemplates($view, $data = array(), $headerdata = array()){
			$this->load->view('web_templates/header', $headerdata);
			$this->load->view('web_templates/header_modals');
			$this->load->view('account/'.$view,$data);
			$this->load->view('web_templates/footer');

	}
	
	public function admintemplates($view, $data = array(), $headerdata = array()){
		if($this->session->userdata('logged_in')){
		  	$this->load->view('admin_templates/header', $headerdata);
			$this->load->view('admin/'.$view,$data);
			$this->load->view('admin_templates/footer');
		 } else {
		   $this->load->view('login');
		 }	
	}

	// public function merchanttemplates($view, $data = array(), $headerdata = array()){
	// 	if($this->session->userdata('logged_in')){
	// 		//$this->load->view('templates/header', $headerdata);
	// 		$this->load->view('merchant/'.$view,$data);
	// 		//$this->load->view('templates/footer');
	// 	} else {
	// 	  $this->load->view('login');
	// 	}	
	// }


}