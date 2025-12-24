<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;

require_once('vendor/autoload.php');


use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;



class Node extends MX_Controller {
	public function __construct()
	{
		parent::__construct();
		header('Content-Type: application/json');
		//$this->load->library('form_validation');
		$this->load->model('dynamic_model');
		$this->load->model('users_model');
		// $this->load->library('encrypt');
		$this->load->library('encryption');
		$this->load->library('sendmail');
		$this->load->library('email');
		$this->load->library('form_validation');
		$this->load->helper(array('form', 'url'));
		error_reporting(0);

		$this->load->config('stripe');
        // Load the Stripe PHP library
        // require_once(APPPATH.'third_party/stripe-php/init.php');

		$language = $this->input->get_request_header('language');
		if($language == "en"){
			$this->lang->load("message","english");
		}else {
			$this->lang->load("message","english");
		}
	}
	// App Version Check
	public function version_check()
	{
		$arg            = array();
		$version_result = version_check_helper();
		echo json_encode($version_result);
	}

	public function transferToken(){
		echo 'sfdadfsad sdfdsaf dsfa';
		//var_dump();
			die();
	}
		//Function used for transferToken
		public function transferTokenTest()
		{
			$result  = array();

		// 	echo 'sfdadfsad sdfdsaf dsfa';
		// //var_dump();
		// 	die();
			
			// Check if the user is active
			$_POST = $this->input->post();
		
						if ($_POST == []) {
							$_POST = json_decode(file_get_contents("php://input"), true);
						}
						if ($_POST) {
						
		
							$this->form_validation->set_rules('myAccountId', 'My Account Id', 'required|trim', array('required' => 'My Account Id is required'));
							$this->form_validation->set_rules('myPrivateKey', 'My Private Key', 'required|trim', array('required' => 'My Private Key is required'));
							$this->form_validation->set_rules('newAccountId', 'New Account Id', 'required|trim', array('required' => 'New Account Id is required'));
							$this->form_validation->set_rules('newAccountPrivateKey', 'New Private Key', 'required|trim', array('required' => 'New Private Key is required'));
							$this->form_validation->set_rules('amount', 'Amount', 'required|trim', array('required' => 'Amount is required'));
							$this->form_validation->set_rules('tokenId', 'Token Id', 'required|trim', array('required' => 'Token Id is required'));
							$this->form_validation->set_rules('bearerToken', 'Bearer Token', 'required|trim', array('required' => 'Bearer Token is required'));
		
							if ($this->form_validation->run() == FALSE) {
								$arg['status']  = 0;
								$arg['error']   = ERROR_FAILED_CODE;
								$arg['message'] = get_form_error($this->form_validation->error_array());
							} else {
								$myAccountId              = $this->input->post('myAccountId');
								$myPrivateKey             = $this->input->post('myPrivateKey');
								$newAccountId             = $this->input->post('newAccountId');
								$newAccountPrivateKey     = $this->input->post('newAccountPrivateKey');
								$amount                   = $this->input->post('amount');
								$tokenId                  = $this->input->post('tokenId');
								$bearerToken              = $this->input->post('bearerToken');
		
								$fields = array(
									'myAccountId'              => $myAccountId,
									'myPrivateKey'             => $myPrivateKey,
									'newAccountId'             => $newAccountId,
									'newAccountPrivateKey'     => $newAccountPrivateKey,
									'amount'                   => $amount,
									'tokenId'                  => $tokenId
								);
		
								$headers = array(
									'Authorization: Bearer ' . $bearerToken,
									'Content-Type: application/json',
									'version: 1'
								);
	
								//var_dump(json_encode($headers));
								//die();
								$url = 'http://192.64.81.86/api/v1/sendAmount';
								//$url = 'http://192.64.81.86:5000/api/v1/getalluserstesting';
		
								// $ch = curl_init($url);
								// //curl_setopt($ch, CURLOPT_URL, 'http://192.64.81.86:5000/api/v1/sendAmount');
								// curl_setopt($ch, CURLOPT_POST, true);
								// curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
								// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
								// //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
								// curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
								// $response = curl_exec($ch);

								$jsonData = json_encode($fields);
	
								$curl = curl_init();
								//curl_setopt($curl, CURLOPT_PROXY, 'http://192.64.81.86:5000');
	
								curl_setopt_array($curl, array(
								CURLOPT_URL => $url,
								CURLOPT_RETURNTRANSFER => true,
								//CURLOPT_ENCODING => '',
								CURLOPT_MAXREDIRS => 10,
								CURLOPT_TIMEOUT => 0,
								CURLOPT_FOLLOWLOCATION => true,
								CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
								CURLOPT_CUSTOMREQUEST => 'POST',
								CURLOPT_POSTFIELDS => $jsonData,
								CURLOPT_HTTPHEADER => $headers,
								));


								
								$response = curl_exec($curl);
								// //var_dump($response);
								// //die();
								// curl_close($curl);
								// echo $response;
	
								
								if (curl_errno($curl)) {
									$error_msg = curl_error($curl);
									echo 'Curl error: ' . $error_msg;
								}
		
								curl_close($curl);
								//var_dump($response);
								//die();
		
								$result = json_decode($response, true);
								//echo json_encode($result);
								
	
								// var_dump($result);
								$arg['result'] = $result;
								
							}
						}
			echo json_encode($arg);

		}

		public function sendAmount() {
			$url = "http://192.64.81.86:5000/api/v1/sendAmount";
			
			$data = array(
				"myAccountId" => "0.0.4294006",
				"myPrivateKey" => "302e020100300506032b657004220420ec29e6c205e5380d3906ca44ab87348157ea73b6a1a572931fab7513f2c425d6",
				"newAccountId" => "0.0.4338316",
				"newAccountPrivateKey" => "e04ef685fcc74280c5402324ffa0f2660cb781a2755b3a380e6b9363321490ca",
				"amount" => 10,
				"tokenId" => "0.0.4363597"
			);
	
			$bearerToken = "your_bearer_token"; // Replace with the actual token
	
			$ch = curl_init($url);
	
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Authorization: Bearer ' . $bearerToken
			));
			curl_setopt($ch, CURLOPT_TIMEOUT, 60); // Increase timeout if necessary
	
			$response = curl_exec($ch);
	
			// Check for errors
			if (curl_errno($ch)) {
				echo 'cURL error: ' . curl_error($ch);
			} else {
				// Debug output
				echo 'HTTP Response: ' . $response;
			}
	
			curl_close($ch);
		}
}