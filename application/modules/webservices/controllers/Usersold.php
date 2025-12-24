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



class Users extends MX_Controller {
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
		//$this->load->library('phpmailer');
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
	//Check Auth for customer or merchant 
	function check_authorization($logout = NULL)
	{
	    $auth_token = $this->input->get_request_header('Authorization');
	    $user_token = json_decode(base64_decode($auth_token));
	    if(!empty($user_token)){
	    	$usid     =  $user_token->userid;
			$auth_key =  $user_token->token;
			if($usid != '' && $auth_key != '') {
				$condition = array(
					'Id' => $usid,
					'Login_Token' => $auth_key
				);
				$loguser = $this->dynamic_model->getdatafromtable('users', $condition);
				if($loguser) {
					if($usid == $loguser[0]['Id'] && $auth_key == $loguser[0]['Login_Token']) {

						if(!empty($logout)) {
							$data2 = array(
								'Login_Token' => ''
							);
			                $wheres = array("Id" => $usid);
			                $result = $this->dynamic_model->updateRowWhere("users", $wheres, $data2);

			                $data2 = array(
								'Device_Id'   => '',
								'Device_Type' => ''
							);
			                $wheress = array("User_Id" => $usid);
			                $result = $this->dynamic_model->updateRowWhere("user_in_roles", $wheress, $data2);

							return $this->lang->line('logout_success');
						} else {
							return true;
						}

					} else {
						return $this->lang->line('session_expire');
					}


				} else {
					return $this->lang->line('varify_token_userid');
				}
			} else {
				return $this->lang->line('header_required');
			}
	    } else {
	    	return $this->lang->line('header_required');
	    }
	}

	public function valid_password($password = '')
    {
        $password = trim($password);
        $regex_lowercase = '/[a-z]/';
        $regex_uppercase = '/[A-Z]/';
        $regex_number = '/[0-9]/';
        $regex_special = '/[!@#$%^&*()\-_=+{};:,<.>§~]/';
        if (empty($password))
        {
            return 'The password field is required.';
            //return FALSE;
        }
        if (preg_match_all($regex_lowercase, $password) < 1)
        {
            return 'The password field must be at least one lowercase letter.';
            //return FALSE;
        }
        if (preg_match_all($regex_uppercase, $password) < 1)
        {
            return 'The password field must be at least one uppercase letter.';
            //return FALSE;
        }
        if (preg_match_all($regex_number, $password) < 1)
        {
            return 'The password  field must have at least one number.';
            //return FALSE;
        }
        // if (preg_match_all($regex_special, $password) < 1)
        // {
        //     return 'The password field must have at least one special character.' . ' ' . '!@#$%^&*()\-_=+{};:,<.>§~';
        //     //return FALSE;
        // }
        if (strlen($password) < 8)
        {
            return 'The password field must be at least 8 characters in length.';
            //return FALSE;
        }
        if (strlen($password) > 20)
        {
            return 'The password field cannot exceed 20 characters in length.';
           // return FALSE;
        }
        return TRUE;
    }
	//Registration for sender or receiver(Both)
	public function register()
	{		
		$arg = array();
		$version_result = version_check_helper();
		if($version_result['status'] != 1 )
		{
			$arg = $version_result;
		} 
		else
		{
			$this->form_validation->set_rules('first_name', 'Name', 'required|trim', array( 'required' => $this->lang->line('firstname')));
			$this->form_validation->set_rules('last_name', 'Last Name', 'required|trim', array( 'required' => $this->lang->line('lastname')));		
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.Email]' , array('required' => $this->lang->line('email_required'),'valid_email' => $this->lang->line('email_valid'),'is_unique' => $this->lang->line('email_unique')
			));
			$this->form_validation->set_rules('mobile', 'Mobile', 'required|min_length[10]|max_length[12]|numeric|is_unique[users.Mobile_No]', array(
					'required' => $this->lang->line('mobile_required'),
					'min_length' => $this->lang->line('mobile_min_length'),
					'max_length' => $this->lang->line('mobile_max_length'),
					'numeric' => $this->lang->line('mobile_numeric'),
					'is_unique' => $this->lang->line('mobile_unique')
				));
			
            $password= $this->input->post('password');
			$pass_msg= $this->valid_password($password);
			if($pass_msg !== TRUE){
			$this->form_validation->set_rules('password','Password','trim|required|callback_valid_password');
			$this->form_validation->set_message('valid_password', $pass_msg);
		    }
			$this->form_validation->set_rules('roles','Role', 'required', array( 'required' => $this->lang->line('role_required')));
			if(empty($_FILES['varification_image']['name']))
			{
				$this->form_validation->set_rules('varification_image','IMAGE','required', array( 'required' => $this->lang->line('verify_front')));
			}
			if ($this->form_validation->run() == FALSE)
			{
			  	$arg['status']  = 0;
			  	$arg['error_code']   = ERROR_FAILED_CODE;
			    $arg['error_line']= __line__;
			 	$arg['message'] = get_form_error($this->form_validation->error_array());
			}
			else
			{
				$full_name              = $this->input->post('first_name').' '.$this->input->post('last_name');
				$firstname              = $this->input->post('first_name');
				$lastname               = $this->input->post('last_name');
				$email                  = $this->input->post('email');
				$mobile                 = $this->input->post('mobile');

				$account_id            = $this->input->post('account_id');
				$private_key            = $this->input->post('private_key');
				$secret_key             = $this->input->post('secret_key');

				//$id_pass_number         = $this->input->post('id_pass_number');
				$referral_code          = $this->input->post('referral_code');
				$hashed_password        = encrypt_password($this->input->post('password'));
				$varification_image = isset($_FILES['varification_image']['name']); 
				$roles =$this->input->post('roles');
				$doc_name = true;
	        	if(!($roles ==2 || $roles ==3))
			    {
					$arg['status']  = 0;
					$arg['error_code']   = ERROR_FAILED_CODE;
					$arg['error_line']= __line__;
					$arg['message'] = 'Please send valid role';
					echo json_encode($arg);exit;
			    }
	        	if($varification_image != '')
	        	{
					$doc_name = $this->dynamic_model->fileupload('varification_image','uploads/identification');
					if($doc_name)
					{
			        	$docPic = site_url().'uploads/identification/'.$doc_name;
					}
					else
					{
						$arg['status']  = 0;
						$arg['error_code']   = ERROR_FAILED_CODE;
						$arg['error_line']= __line__;
						$arg['message'] = 'File Type Is Not Supported';
						echo json_encode($arg);exit;
					}
				}			   
				if($doc_name)
				{
					$otpnumber = '1234';
					$my_referral_code= generateRandomString(10);
					$otpnumber=generate_Pin();
					$ref_num=getuniquenumber();
					//Users Register from Referral Code
			        if(!empty($referral_code)){	
			        	$ref_where = array('Referral_Code' =>$referral_code);	
						$get_referrals_data = $this->dynamic_model->getdatafromtable('users',$ref_where,'Id,Referral_Code,Is_Profile_Complete,Total_Referral_Points');
			             if(empty($get_referrals_data)){
			                $arg['status']  = 0;
						    $arg['error_code']   = ERROR_FAILED_CODE;
							$arg['error_line']= __line__;
							$arg['message'] = 'Please enter correct refferal code.';
							echo json_encode($arg);exit;
		           	    } 	
		            } 
		            $generate_etip_id = strtolower(substr($firstname, 0, 2)).rand (10,99).substr($mobile, -3).'@'.strtolower(SITE_TITLE);
		            $lat= $this->input->get_request_header('lat');
		            $long= $this->input->get_request_header('long');
					$userdata = array(
								       	'FullName'    => $full_name,
								       	'FirstName'   => $firstname,
								       	'lastName'    => $lastname,
								        'Password'    => $hashed_password,
								        'Email'       => $email,
								        'Mobile_No'   => $mobile,
								        'Profile_Pic' => 'default.jpg',
								        'Mobile_OTP'  => $otpnumber,
								        'Email_OTP'   => $otpnumber,
								        'Notification_Status'=> 1,
								        'Fingerprint_Status'   => 1,
								        'Referral_Code'=> $my_referral_code,
								        'Lat'=> (!empty($lat)) ? $lat : 0,
								        'Lang'=> (!empty($long)) ? $long : 0,
								        'etippers_id'=> $generate_etip_id,
										'account_id'=> $account_id,
										'private_key'=> $private_key,
										'secret_key'=> $secret_key,
				    				);
					$userid = $this->dynamic_model->insertdata('users', $userdata); 				
			        $users_documents = array('User_Id'            =>$userid,
						                     'Document_Type_Id'   =>2,
						                     //'Pin_Number'         =>$id_pass_number,
						                     //'Is_Verified'         =>1,
						                     'Document_Image_Name'=>$doc_name,
						                     'Created_By'         =>$userid
					                    );
		           	$users_documents_id = $this->dynamic_model->insertdata('users_documents', $users_documents); 

			        //Insert in referral table
			        if(!empty($get_referrals_data)){
					 $referrals_point_info = $this->dynamic_model->get_row('refferal_points_settings',array('Id'=>1));
					 $give_refferal_point=(!empty($referrals_point_info['Give_Refferal_Point'])) ? $referrals_point_info['Give_Refferal_Point'] : '0';
			         $referral_from  =@$get_referrals_data[0]['Id'];
			         $referral_points=@(!empty($get_referrals_data[0]['Total_Referral_Points'])) ? $get_referrals_data[0]['Total_Referral_Points'] : 0 ;
			         if($get_referrals_data[0]['Is_Profile_Complete']==1){
                             $balance_points = $referral_points+$give_refferal_point;
                             $referrals_data = array( 'Referral_From'   => $referral_from,
								                      'Referral_To'     => $userid,
								                      'Referral_Code'   =>$referral_code,
								                      'Referral_Points' =>$give_refferal_point,
								                      'Balance_Referral_Point' =>$balance_points,
								                      'Ref_Num'          =>$ref_num,
								                      'Status'           =>1
					                                );
			         }else{
                               $referrals_data = array('Referral_From'   => $referral_from,
								                       'Referral_To'     => $userid,
								                       'Referral_Code'   =>$referral_code,
								                       'Referral_Points' =>$give_refferal_point,
								                       'Balance_Referral_Point' =>$referral_points,
								                       'Ref_Num'          =>$ref_num,
								                       'Status'          =>0
					                                  );     
			            }
		           	    $this->dynamic_model->insertdata('users_referrals',$referrals_data);
		                $wheres        = array("Id" => $referral_from);	
					    $get_user_data = $this->dynamic_model->getdatafromtable('users',$wheres,'Id,Total_Referral_Points');
					   if($get_referrals_data[0]['Is_Profile_Complete']==1){
						   if(!empty($get_user_data)){
						   	$total_points=@$get_user_data[0]['Total_Referral_Points']+$give_refferal_point;
						   }else{
						   	$total_points=$give_refferal_point;
						   }
			           	    $updadata2 = array('Total_Referral_Points'=>$total_points);
			                $updated_token = $this->dynamic_model->updateRowWhere("users",$wheres,$updadata2);
		               }
		           	 }
			        //function used for QRCode
					 $qr_number = generateQrcode($mobile);
                        $users_roles = array('User_Id'=>$userid,
		                                 'Role_Id'=>$roles,
		                                 'Device_Id'=>$this->input->get_request_header('device_id'),
		                                 'Device_Type'=>$this->input->get_request_header('device_type'),
		                                 'QR_Code'=>$qr_number,
		                                 'QR_Code_Img_Path'=>$qr_number.'.png'
		                                );

		           	$users_roles_id = $this->dynamic_model->insertdata('user_in_roles', $users_roles);

			        if($userid && $users_roles_id && $users_documents_id)
			        {
		                //Send Email Code
						
		                $where1 = array('slug' =>'send_otp_code');	
						$template_data = $this->dynamic_model->getdatafromtable('manage_notification_mail', $where1);
	                    $desc_data= str_replace('{OTP}',$otpnumber,$template_data[0]['description']);
							
						$emaildata['subject']     = $template_data[0]['subject']; 
						$emaildata['description'] = $desc_data;  
			        	//Send Email Code
						//$emaildata['subject']     = 'Registration';
						//$emaildata['description'] = 'Your OTP is :'.$otpnumber;
						$emaildata['body']        = '';

						$msg = $this->load->view('emailtemplate',$emaildata,TRUE);
						//--------------load email template----------------
						//$this->sendmail->sendmailto($email,'Verify mobile OTP Code',$msg);
						//sendEmailCI($email,'' ,'Verify mobile OTP Code',$msg);

						$enc_user = encode($userid);
						$enc_role = encode($users_roles_id);
						$url = site_url().'webservices/users/verify_user?userid='.$enc_user.'&role='.$enc_role;
		                $where2 = array('slug' => 'sucessfully_registration_user');	
						$template_data = $this->dynamic_model->getdatafromtable('manage_notification_mail', $where2);
	                    $desc= str_replace('{NAME}',$full_name,$template_data[0]['description']);
	                    $desc_data= str_replace('{URL}',$url, $desc);
							
						$data['subject']     = $template_data[0]['subject']; 
						$data['description'] = $desc_data;  
						$data['body']        = '';
						$msg = $this->load->view('emailtemplate',$data,true);
						//$this->sendmail->sendmailto($email,'Verify Email From Etippers', "$msg");
						
						if(sendEmailCI($email,'' ,'Verify Email From '.SITE_TITLE,$msg))
						{
							$arg['status']  = 1;
							$arg['error_code']   = SUCCESS_CODE;
							$arg['error_line']= __line__;
							$arg['message'] = $this->lang->line('thank_msg');
							//$arg['url']     = $url;
							$arg['user_id']  = "$userid";
							$arg['data']['user_id']  = "$userid";
						}
						else{
							$where2    = array('Id' => $userid);
							$response  = $this->dynamic_model->deletedata('users',$where2);

							$arg['status']  = 0;
							$arg['error_code']   = ERROR_FAILED_CODE;
							$arg['error_line']= __line__;
							$arg['message'] = $this->lang->line('server_problem');
						}
			        }
			        else
			        {
			        	$arg['status']  = 0;
			        	$arg['error_code']   = ERROR_FAILED_CODE;
			            $arg['error_line']= __line__;
						$arg['message'] = $this->lang->line('server_problem');
			        }
			    }
			    else
			    {
			    	$arg['status']  = 0;
			    	$arg['error_code']   = ERROR_FAILED_CODE;
			        $arg['error_line']= __line__;
					$arg['message'] = $this->lang->line('invalid_image');
			    }
			}
	    }
		
		echo json_encode($arg);
	}

	//Used function for verify email
	public function verify_user()
	{
		header("Content-Type: text/html");
		$enc = $_GET['userid'];
		$userid = decode($enc);
		if(!$userid)
		{
			$userid = $_GET['userid'];
			var_dump($enc);
		}
		//$userid = 2;
		$where = array(
			'Id' => $userid
		);
		$findresult = $this->dynamic_model->getdatafromtable('users', $where);
		
		if($findresult)
		{
			$where1 = array(
				'Email' => $findresult[0]['Email']
			);
			$data = array(
				'Is_Email_Verified' => "1"
			);
			$varify = $this->dynamic_model->updateRowWhere('users', $where1, $data);

			$wheres = array(
						'Id' => $userid
					);
			$fetchresult = $this->dynamic_model->getdatafromtable('users', $wheres);

			if($fetchresult[0]['Is_Email_Verified'] == 1 && $fetchresult[0]['Is_Mobile_Verified'] == 1)
			{
				$data2 = array(
								'Is_Profile_Complete' => 1,
								'Is_Active'           =>1,
								'Is_LoggedIn'         =>1,
								'Last_Updated_By'     =>$userid,
								'Last_Updated_Date_Time'=>date('Y-m-d H:i:s')
								);
            	$where3   = array("Id" => $userid);   
            	$updatedt = update_data("users", $data2, $where3);

            	//update referral points status and poins in users tables
            	$wh= array('Referral_From' => $userid,'Status'=>0);
			    $referral_result = $this->dynamic_model->getdatafromtable('users_referrals',$wh);
			    if(!empty($referral_result)){
		    	   foreach($referral_result as $value){
		    	   	  $referrals_data = array('Status'=>1);                
	                  update_data('users_referrals',$referrals_data,$wh); 
                      $points+=$value['Referral_Points'];
		    	   }       
		        }
                $total_points=$fetchresult[0]['Total_Referral_Points']+$points;
                $data3 = array('Total_Referral_Points' => $total_points);
		        $updatedt = update_data("users", $data3,$where3); 	
			  
        	}
            echo '<p style="text-align: center !important;padding-top: 30px"><b>Verify successfully.</b> </p>';
        	 
		}
		else
		{
			 echo '<p style="text-align: center !important;padding-top: 30px"><b>Not Verify Please Try again Later.</b> </p>';
		}
	}
	//Used function for varify otp 
	public function verify_otp()
	{
        $arg = array();
		$version_result = version_check_helper();
		if($version_result['status'] != 1 )
		{
			$arg = $version_result;
		} 
		else
		{
			$_POST = $this->input->post();

			if($_POST == [])
			{
				$_POST = json_decode(file_get_contents("php://input"), true);
			}
			if($_POST)
			{
				$this->form_validation->set_rules('user_id', 'User ID', 'required');
				$this->form_validation->set_rules('otp', 'OTP','required|max_length[4]');

				if ($this->form_validation->run() == FALSE)
				{
				  	$arg['status']  = 0;
				  	$arg['error_code']   = ERROR_FAILED_CODE;
				  	$arg['error_line']= __line__;
				  	$arg['message'] =  get_form_error($this->form_validation->error_array());
				}
				else
				{
					$points=0;
					$usid     = $this->input->post('user_id');
					$user_otp = $this->input->post('otp');

					$condition = array('Id' => $usid,'Mobile_OTP' => $user_otp);
					$result    = getdatafromtable('users', $condition);
					if($result)
					{
						$condition1 = array('Id' => $usid,'Is_Mobile_Verified' => 1);
						$result1    = getdatafromtable('users', $condition1);
						if(empty($result1))
						{
							$data1 = array(
											'Is_Mobile_Verified'   => 1,
											'Last_Updated_By'     =>$usid,
											'Last_Updated_Date_Time'=>date('Y-m-d H:i:s')
											);
			                $where    = array("Id" => $usid);   
			                $updatedt = update_data("users", $data1, $where);


							$wheres = array(
										'Id' => $usid
									);
							$fetchresult = $this->dynamic_model->getdatafromtable('users', $wheres);
							
							if($fetchresult[0]['Is_Email_Verified'] == 1 && $fetchresult[0]['Is_Mobile_Verified'] == 1)
							{
								$data2 = array(
												'Is_Profile_Complete' => 1,
												'Is_Active'           =>1,
												'Is_LoggedIn'         =>1,
												'Last_Updated_By'     =>$usid,
												'Last_Updated_Date_Time'=>date('Y-m-d H:i:s')
												);
				            	$where3   = array("Id" => $usid);
				            	$updatedt = update_data("users", $data2, $where3);

				            	//update referral points status and poins in users tables
				            	$wh= array('Referral_From' => $usid,'Status'=>0);
							    $referral_result = $this->dynamic_model->getdatafromtable('users_referrals',$wh);
							    if(!empty($referral_result)){
						    	   foreach($referral_result as $value){
						    	   	  $referrals_data = array('Status'=>1);                
					                  update_data('users_referrals',$referrals_data,$wh); 
                                      $points+=$value['Referral_Points'];
						    	   }       
						        }
                                $total_points=$fetchresult[0]['Total_Referral_Points']+$points;
                                $data3 = array('Total_Referral_Points' => $total_points);
						        $updatedt = update_data("users",$data3,$where3);
				            }
				            //condition manage for social login (Docuement verified status)
                            $where1      = array('User_Id' => $usid);
							$useroles = $this->dynamic_model->getdatafromtable('user_in_roles',$where1,'Id, User_Id,Role_Id');
							$docdetails = $this->dynamic_model->getdatafromtable('users_documents',$where1,'Id, Is_Verified');
							
                            //doc_verified one means document verified & 0 means unverified
							if($docdetails[0]['Is_Verified'] == 1 && $useroles[0]['Role_Id']== 3){
					    		$doc_verified='1';
					    	}elseif($useroles[0]['Role_Id']== 2){
					    		$doc_verified='1';

					    	}else{
					    		$doc_verified='0';
					    	}

			                $arg['status']  = 1;
			                $arg['error_code']   = SUCCESS_CODE;
			                $arg['error_line']= __line__;
					  		$arg['message'] = $this->lang->line('otp_varify');
					  		$arg['data']['document_verify_status'] =$doc_verified;
			            }
			            else
			            {
			            	$arg['status']  = 0;
			            	$arg['error_code']   = ERROR_FAILED_CODE;
			            	$arg['error_line']= __line__;
				  			$arg['message'] = $this->lang->line('otp_already_varify');
			            }
					}
					else
					{
						$arg['status']  = 0;
						$arg['error_code']   = ERROR_FAILED_CODE;
						$arg['error_line']= __line__;
				  		$arg['message'] = $this->lang->line('otp_not_match');
					}
				}
				
			}

        }
       echo json_encode($arg);
	}

	//Login for sernder or receiver
	public function login()
	{
		$arg = array();
		$version_result = version_check_helper();
		if($version_result['status'] != 1 )
		{
			$arg = $version_result;
		} 
		else
		{
			$_POST = $this->input->post();

			if($_POST == [])
			{
				$_POST = json_decode(file_get_contents("php://input"), true);
			}
			
			// $this->form_validation->set_rules('password','Password','trim|required|callback_valid_password');
			if($_POST)
			{
				$arg = array();
				$social_auth_id= $this->input->post('social_auth_id');
				//$this->form_validation->set_rules('mobile', 'Mobile', 'required|min_length[8]');
				if(empty($social_auth_id)){
				 $this->form_validation->set_rules('password', 'Password', 'required');
				}else{
					 $this->form_validation->set_rules('social_auth_id', 'social auth id', 'required');
				}
				if ($this->form_validation->run() == FALSE)
				{
				  	$arg['status']  = 0;
				  	$arg['error_code']   = ERROR_FAILED_CODE;
				  	$arg['error_line']= __line__;
				  	$arg['message'] =  get_form_error($this->form_validation->error_array());
				}
				else
				{
					$varify = 0;
					$data   = $this->dynamic_model->checkMobile($this->input->post('mobile'));
					if($data)
					{
						$hashed_password = encrypt_password($this->input->post('password'));
						if($hashed_password == $data[0]['Password'])
						{
							$varify = 1;
						}elseif($social_auth_id== $data[0]['social_auth_id']){
							
							 $varify = 1;
						}
						else
						{
							$varify         = 0;
							$arg['status']  = 0;
							$arg['error_code']   = ERROR_FAILED_CODE;
							$arg['error_line']= __line__;
			  		 		$arg['message'] = $this->lang->line('password_notmatch');
						}
        
						if($varify == 1)
						{
						    $fname       = name_format($data[0]['FirstName']);
						    $lname       = name_format($data[0]['LastName']);
						    $fullname    = name_format($data[0]['FullName']);
						    $gender      = (!empty($data[0]['Gender'])) ? $data[0]['Gender'] :'';
							$Age         = (!empty($data[0]['Age'])) ? $data[0]['Age'] :'';
			                $address     = (!empty($data[0]['Address'])) ? $data[0]['Address'] :'';
			                $etippers_id = (!empty($data[0]['etippers_id'])) ? $data[0]['etippers_id'] :'';

							$userid      = $data[0]['Id'];
					      	$where1      = " where User_Id = '".$userid."'";
					    	$userdetails = $this->dynamic_model->select('users_documents',$where1,'Id, Is_Verified');
					    	$useroles = $this->dynamic_model->select('user_in_roles',$where1,'Id, Role_Id');
	                        $mob_verified = (!empty($data[0]['Is_Mobile_Verified']))? $data[0]['Is_Mobile_Verified'] : 0;
							$email_verified=  (!empty($data[0]['Is_Email_Verified']))? $data[0]['Is_Email_Verified'] : 0;      
					    	$is_profile_complete= (!empty($data[0]['Email'] && $data[0]['Mobile_No'] )) ? '1': '0'; 
                            $loguser_documents = $this->dynamic_model->get_row('users_documents',array('User_Id'=> $data[0]['Id'],'Document_Type_Id'=>2));
                            $verification_image = site_url().'uploads/identification/'.$loguser_documents['Document_Image_Name'];
					    	//Document verification check remove for client request(Receiver case)
					    	// if($userdetails[0]['Is_Verified'] == 1 && $useroles[0]['Role_Id']== 3){
					    	// 	$doc_verified=1;
					    	// }elseif($useroles[0]['Role_Id']== 2){
					    	// 	$doc_verified=1;

					    	// }else{
					    	// 	$doc_verified=0;
					    	// }
					    	$doc_verified = 1;
					    	if($doc_verified == 1 && $data[0]['Is_Profile_Complete']== 1 )
					    	{
	                               if($data[0]['Is_Active'] == 0)
							        {
							        
					  					$arg['status']  = 0;
					  					$arg['error_code']   = ERROR_FAILED_CODE;
					  					$arg['error_line']= __line__;
						  				$arg['data']    = array('user_id' => $data[0]['Id']);
						  				$arg['message'] = $this->lang->line('user_block');
						  				echo json_encode($arg);exit;
					  				}
				                	$redirect_to_verify = 0;
									$user_id  = $data[0]['Id'];
						            $token    = uniqid();
									$device_token = $this->input->post('device_token');

						            $data2 = array(
												'Login_Token' => $token,
												'Device_Token' => $device_token,
												'Is_LoggedIn' =>'1',
												'Last_Updated_By'      =>$user_id,
												'Last_Updated_Date_Time'=>date('Y-m-d H:i:s')
												);
					                $wheres        = array("Id" => $user_id);
					                $updated_token = $this->dynamic_model->updateRowWhere("users",$wheres,$data2);

						            $updatedata['Device_Type'] = $this->input->get_request_header('device_type');
			                        $updatedata['Device_Id']   = $this->input->get_request_header('device_id');

			                        $wheres1 = array("User_Id" => $user_id);
			                        $updated_token1 = $this->dynamic_model->updateRowWhere("user_in_roles",$wheres1,$updatedata);
			                        // Function for add login data in table
				                	$user_os        = getOS();
									$user_browser   = getBrowser();

									$device_details = "".$user_browser." on ".$user_os."";

									$co = ip_info("Visitor", "Country"); // India
									$cc = ip_info("Visitor", "Country Code"); // IN
									$ca = ip_info("Visitor", "Address"); // Proddatur, Andhra Pradesh, India
									//$ip = $_SERVER['REMOTE_ADDR'];
									$ip = $_SERVER['HTTP_HOST'];
									$ua = $_SERVER['HTTP_USER_AGENT'];
									$loc = "$ca ($cc)";
									$logindata = array('User_Id'     => $user_id,
				                                    'Ip_Address'   => $ip,
				                                    'Location' =>$loc,
				                                    'User_Os_Platform'=>$device_details
				                                   );
									$loginid = $this->dynamic_model->insertdata('user_logins', $logindata);

									$condition = array(
													'User_Id' => $user_id
												);
									$loguser = getdatafromtable('user_in_roles', $condition);

						            if(!empty($loguser[0]['Role_Id']==3)){
							            if(!empty($loguser[0]['QR_Code_Img_Path'])){
							            $qrcode_image  = site_url().'uploads/qrcodes/'.$loguser[0]['QR_Code_Img_Path'];
							            }else{
							        	 $qrcode_image  ='';
							            }
						            }else{
						        	 $qrcode_image  ='';
						            }
						            $profile_image = site_url().'uploads/user/'.$data[0]['Profile_Pic'];

						            $tokendata = array(
										    	'userid' => $user_id,
										    	'token' => $token
										    );

									// Generate QR Code
									$data = [
										'user_id'       => $userid,
										'phone_number'  => $data[0]['Mobile_No'],
										'name'          => $fullname
									];
									$qrCodeData = $this->generate_qrcode($data);
									$qrCodeFilePath = $qrCodeData['file'];


									$user_token = base64_encode( json_encode($tokendata));
						            $userinfo = array('user_id' => $userid,'Authorization' => $user_token,'roles'=>$loguser[0]['Role_Id'],'first_name' => $fname, 'last_name'=>$lname,'full_name' =>$fullname,'wallet_amount'=>$data[0]['Current_Wallet_Balance'],'mobile'=>$data[0]['Mobile_No'],'email'=>$data[0]['Email'],'qrcode_image'=>$qrcode_image,'redirect_to_verify'=>"$redirect_to_verify",'notification_status'=>$data[0]['Notification_Status'],'profile_image'=>$profile_image,'fingerprint_status'=>$data[0]['Fingerprint_Status'],'auth_provider'=>$data[0]['Auth_Provider'],'subscription_plan_status' =>'1','referral_code' =>$data[0]['Referral_Code'],'total_referral_points' =>$data[0]['Total_Referral_Points'],'verification_image' =>$verification_image,'gender' =>$gender,'age' =>$Age,'address' =>$address, 'etippers_id' =>$etippers_id,"is_profile_completed"=>$is_profile_complete , 'qrcode_image' => site_url() . $qrCodeFilePath);
						                
									$arg['status']  = 1;
									$arg['error_code']   = SUCCESS_CODE;
			                        $arg['error_line']= __line__;
					  				$arg['message'] = $this->lang->line('login_success');
					  				$arg['data']    = $userinfo;
				  			}
				  			else
				  			{
				  				
				  				if($data[0]['Is_Profile_Complete'] == 1)
			                	{
			                		$redirect_to_verify = 0; // here redirect_to verify = 0 means user is verified

			                		$userinfo = array('user_id' => $userid,'Authorization' =>"",'roles'=>"",'first_name' => "", 'last_name' =>"",'full_name' =>"",'wallet_amount'=>"",'mobile'=>"",'email'=>"",'qrcode_image'=>"",'redirect_to_verify'=>"",'notification_status'=>"",'profile_image'=>"",'fingerprint_status'=>"",'auth_provider'=>"",'subscription_plan_status' =>"",'referral_code' =>"",'total_referral_points' =>"",'verification_image' =>"",'gender' =>"",'age' =>"",'address' =>"",'etippers_id' =>"","is_profile_completed"=>"");
			                		$arg['status']  = 0;
			                		$arg['error_code']   = ERROR_FAILED_CODE;
			                		$arg['error_line']= __line__;
						  			$arg['data']    = $userinfo;
						  			$arg['message'] = 'Document is not Verified.Please contact to Administration';
			                	}
			                	else
			                	{  
	                                if($email_verified  == 0 ){
	                                    $redirect_to_verify = 2; // here redirect_to verify = 2 means email is not verified
	                                   }elseif($mob_verified == 0){
	                                   $redirect_to_verify = 1;// here redirect_to verify = 1 means mobile is not verified
	                                   }else{
	                                   $redirect_to_verify =''; 
	                                 } 
			                		 if($useroles[0]['Role_Id']== 3){
						            $userinfo = array('user_id' => $userid,'Authorization' =>"",'roles'=>$useroles[0]['Role_Id'],'first_name' => $fname, 'last_name' =>$lname,'full_name' =>$fullname,'wallet_amount'=>$data[0]['Current_Wallet_Balance'],'mobile'=>$data[0]['Mobile_No'],'email'=>$data[0]['Email'],'qrcode_image'=>"",'redirect_to_verify'=>"$redirect_to_verify",'notification_status'=>$data[0]['Notification_Status'],'profile_image'=>"",'fingerprint_status'=>$data[0]['Fingerprint_Status'],'auth_provider'=>$data[0]['Auth_Provider'],'subscription_plan_status' =>'1','referral_code' =>$data[0]['Referral_Code'],'total_referral_points' =>$data[0]['Total_Referral_Points'],'verification_image' =>$verification_image,'gender' =>$gender,'age' =>$Age,'address' =>$address,'etippers_id' =>$etippers_id ,"is_profile_completed"=>$is_profile_complete);
						            }else{
						            $userinfo = array('user_id' => $userid,'Authorization' => "",'roles'=>$useroles[0]['Role_Id'],'first_name' => $fname, 'last_name' => $lname, 'full_name' =>$fullname,'wallet_amount'=>$data[0]['Current_Wallet_Balance'],'mobile'=>$data[0]['Mobile_No'],'email'=>$data[0]['Email'],'qrcode_image'=>"",'redirect_to_verify'=>"$redirect_to_verify",'notification_status'=>$data[0]['Notification_Status'],'profile_image'=>"",'fingerprint_status'=>$data[0]['Fingerprint_Status'],'auth_provider'=>$data[0]['Auth_Provider'],'subscription_plan_status' =>'1','referral_code' =>$data[0]['Referral_Code'],'total_referral_points' =>$data[0]['Total_Referral_Points'],'verification_image' =>$verification_image,'gender' =>$gender,'age' =>$Age,'address' =>$address,'etippers_id' =>$etippers_id,"is_profile_completed"=>$is_profile_complete);
						            } 
	                    
									if($email_verified  == 0){
	                                   $account_verify_msg = 'Please verify your email'; 
	                                 }elseif($mob_verified == 0){
	                                  $account_verify_msg = 'Please verify your Mobile No.';
	                                 }else{
	                                   $account_verify_msg = 'Your profile is not complete'; 
	                                 }
									$arg['status']  = 1;
									$arg['error_code']   = SUCCESS_CODE;
			                        $arg['error_line']= __line__;
					  				$arg['message'] = $account_verify_msg;
					  				$arg['data']    = $userinfo;
			                	}
				  			}
						}
					}
					else
					{
						$arg['status']  = 0;
						$arg['error_code']   = ERROR_FAILED_CODE;
			            $arg['error_line']= __line__;
						$arg['message'] = $this->lang->line('register_first');
					}
				}
				
			}
	    }
	    echo json_encode($arg);
	}







	// Social Login: Kirtisagar Prajapat


	public function social_login()
	{
		$arg = array();
		$version_result = version_check_helper();
		if($version_result['status'] != 1 )
		{
			$arg = $version_result;
		} 
		else
		{
			$_POST = $this->input->post();

			if($_POST == [])
			{
				$_POST = json_decode(file_get_contents("php://input"), true);
			}
			
			if($_POST)
			{
				$arg = array();
				//$this->form_validation->set_rules('mobile', 'Mobile', 'required|min_length[8]');
				$this->form_validation->set_rules('social_auth_id', 'Social Auth Id', 'required');
				$this->form_validation->set_rules('Auth_Provider', 'Auth Provider', 'required');
				// $this->form_validation->set_rules('Role_Id', 'Role Id', 'required');
				if ($this->form_validation->run() == FALSE)
				{
				  	$arg['status']  = 0;
				  	$arg['error_code']   = ERROR_FAILED_CODE;
				  	$arg['error_line']= __line__;
				  	$arg['message'] =  get_form_error($this->form_validation->error_array());
				}
				else
				{
					$condition = array(
						'social_auth_id' => $this->input->post('social_auth_id')
					);
					$data = getdatafromtable('users', $condition);

					if(empty($data)){

							//$this->form_validation->set_rules('first_name', 'Name', 'required|trim', array( 'required' => $this->lang->line('firstname')));
							// $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim', array( 'required' => $this->lang->line('lastname')));		
							if ($this->form_validation->run() == FALSE)
							{
							  	$arg['status']  = 0;
							  	$arg['error_code']   = ERROR_FAILED_CODE;
							    $arg['error_line']= __line__;
							 	$arg['message'] = get_form_error($this->form_validation->error_array());
								echo json_encode($arg);exit;
							}
							else
							{

								$firstname = $this->input->post('first_name');
								if($this->input->post('last_name') && $this->input->post('last_name')!=""){
									$lastname = $this->input->post('last_name');
									$full_name = $this->input->post('first_name').' '.$lastname;
								}else{
									$lastname = "";
									$full_name  = $firstname;
								}
								$social_auth_id               = $this->input->post('social_auth_id');
								$Auth_Provider               = $this->input->post('Auth_Provider');

								///////////// Lastname Include code ////////////////

								// $full_name              = $this->input->post('first_name').' '.$this->input->post('last_name');
								// $firstname              = $this->input->post('first_name');
								// $lastname               = $this->input->post('last_name');
								// $social_auth_id               = $this->input->post('social_auth_id');
								// $Auth_Provider               = $this->input->post('Auth_Provider');

								///////////// Lastname Include code ////////////////

								///////////// role id code ////////////////


								// $roles =$this->input->post('Role_Id');
								// $doc_name = true;
					   //      	if(!($roles ==2 || $roles ==3))
							 //    {
								// 	$arg['status']  = 0;
								// 	$arg['error_code']   = ERROR_FAILED_CODE;
								// 	$arg['error_line']= __line__;
								// 	$arg['message'] = 'Please send valid role';
								// 	echo json_encode($arg);exit;
							 //    }


									//Users Register from Referral Code    Is_Active

						            $lat= $this->input->get_request_header('lat');
						            $long= $this->input->get_request_header('long');
									$userdata = array(
												       	'FullName'    => $full_name ? $full_name : '',
												       	'FirstName'   => $firstname ? $firstname : '',
												       	'lastName'    => $lastname ? $lastname : '',
												       	'social_auth_id'    => $social_auth_id,
												       	'Auth_Provider'    => $Auth_Provider,
												        'Lat'=> (!empty($lat)) ? $lat : 0,
												        'Lang'=> (!empty($long)) ? $long : 0,
												       	'Is_Active'    => 1,
												       	'Is_LoggedIn'    => 1

								    				);
									$userid = $this->dynamic_model->insertdata('users', $userdata); 				
							}
							$condition = array(
								'social_auth_id' => $this->input->post('social_auth_id')
							);
							$data = getdatafromtable('users', $condition);
					}
					if(!empty($data)){
						
						    $fname       = (!empty($data[0]['FirstName'])) ? name_format($data[0]['FirstName']) :'';
						    $lname       = (!empty($data[0]['LastName'])) ? name_format($data[0]['LastName']) :'';
						    $fullname    = (!empty($data[0]['FullName'])) ? name_format($data[0]['FullName']) :'';	   
						    $gender      = (!empty($data[0]['Gender'])) ? $data[0]['Gender'] :'';
							$Age         = (!empty($data[0]['Age'])) ? $data[0]['Age'] :'';
			                $address     = (!empty($data[0]['Address'])) ? $data[0]['Address'] :'';
			                $etippers_id = (!empty($data[0]['etippers_id'])) ? $data[0]['etippers_id'] :'';

							 $userid      = $data[0]['Id'];
					      	
					      	$where1      = array("User_Id"=>$userid);
					    	$userdetails = $this->dynamic_model->getdatafromtable('users_documents',$where1,'Id,User_Id,Is_Verified');
                          // echo $this->db->last_query();die;
					    	//echo "<Pre>";print_r($userdetails);die;
					    	$useroles = $this->dynamic_model->getdatafromtable('user_in_roles',$where1,'Id, Role_Id');
	                        $role_id= (!empty($useroles[0]['Role_Id'])) ?  $useroles[0]['Role_Id'] : "";

	                        $mob_verified = (!empty($data[0]['Is_Mobile_Verified']))? $data[0]['Is_Mobile_Verified'] : 0;
							$email_verified=  (!empty($data[0]['Is_Email_Verified']))? $data[0]['Is_Email_Verified'] : 0;      
							

							$is_profile_complete= (!empty($data[0]['Email'] && $data[0]['Mobile_No'] )) ? '1': '0';      
					    	
                          // echo $email_verified;die;
	                        if($email_verified == 0)
							{
							  $redirect_to_verify = 2; // here redirect_to verify = 2 means email is not verified
							}elseif($mob_verified == 0){
							 $redirect_to_verify = 1;// here redirect_to verify = 1 means mobile is not verified
							}else{
							  $redirect_to_verify ='0'; 
						    } 
						    if($email_verified  == 0){
                               $account_verify_msg = 'Please verify your email'; 
                             }elseif($mob_verified == 0){
                              $account_verify_msg = 'Please verify your Mobile No.';
                             }else{
                               $account_verify_msg = $this->lang->line('login_success'); 
                             }


                           // echo $redirect_to_verify;die;
                            $loguser_documents = $this->dynamic_model->get_row('users_documents',array('User_Id'=> $data[0]['Id'],'Document_Type_Id'=>2));
                            $verification_image = site_url().'uploads/identification/'.$loguser_documents['Document_Image_Name'];
					    	
                                //echo $userdetails[0]['Is_Verified'];die;
					    
                           
                            if((!empty($userdetails)) && $data[0]['Is_Profile_Complete'] ==1){
						    	if($userdetails[0]['Is_Verified'] == 1 && $useroles[0]['Role_Id']== 3){
						    		 $doc_verified='1';
						    		 $doc_detail_empty=1;
						    	}elseif($useroles[0]['Role_Id']== 2){
						    		$doc_verified='1';
						    		 $doc_detail_empty=1;
						    	}else{
						    		$doc_verified='0';
						    		$doc_detail_empty=1;
						    	}
					        }else{
                                $doc_detail_empty=0;
					        }
					        

									$user_id  = $data[0]['Id'];
						            $token    = uniqid();

						            $data2 = array(
												'Login_Token' => $token,
												'Is_LoggedIn' =>'1',
												'Last_Updated_By'      =>$user_id,
												'Last_Updated_Date_Time'=>date('Y-m-d H:i:s')
												);
					                $wheres        = array("Id" => $user_id);
					                $updated_token = $this->dynamic_model->updateRowWhere("users",$wheres,$data2);

						            $updatedata['Device_Type'] = $this->input->get_request_header('device_type');
			                        $updatedata['Device_Id']   = $this->input->get_request_header('device_id');

			                        $wheres1 = array("User_Id" => $user_id);
			                        $updated_token1 = $this->dynamic_model->updateRowWhere("user_in_roles",$wheres1,$updatedata);
			                        // Function for add login data in table
				                	$user_os        = getOS();
									$user_browser   = getBrowser();

									$device_details = "".$user_browser." on ".$user_os."";

									$co = ip_info("Visitor", "Country"); // India
									$cc = ip_info("Visitor", "Country Code"); // IN
									$ca = ip_info("Visitor", "Address"); // Proddatur, Andhra Pradesh, India
									//$ip = $_SERVER['REMOTE_ADDR'];
									$ip = $_SERVER['HTTP_HOST'];
									$ua = $_SERVER['HTTP_USER_AGENT'];
									$loc = "$ca ($cc)";
									$logindata = array('User_Id'     => $user_id,
				                                    'Ip_Address'   => $ip,
				                                    'Location' =>$loc,
				                                    'User_Os_Platform'=>$device_details
				                                   );
									$loginid = $this->dynamic_model->insertdata('user_logins', $logindata);

									$condition = array(
													'User_Id' => $user_id
												);
									$loguser = getdatafromtable('user_in_roles', $condition);

						            if(!empty($loguser[0]['Role_Id']==3)){
							            if(!empty($loguser[0]['QR_Code_Img_Path'])){
							            $qrcode_image  = site_url().'uploads/qrcodes/'.$loguser[0]['QR_Code_Img_Path'];
							            }else{
							        	 $qrcode_image  ='';
							            }
						            }else{
						        	 $qrcode_image  ='';
						            }
						            $profile_image = site_url().'uploads/user/'.$data[0]['Profile_Pic'];

						            $tokendata = array(
										    	'userid' => $user_id,
										    	'token' => $token
										    );
									$user_token = base64_encode( json_encode($tokendata));
                                  if($doc_verified ==0 && $doc_detail_empty== 1){
								  $userinfo = array('user_id' => $userid,'Authorization' =>"",'roles'=>"",'first_name' => "", 'last_name' =>"",'full_name' =>"",'wallet_amount'=>"",'mobile'=>"",'email'=>"",'qrcode_image'=>"",'redirect_to_verify'=>"",'notification_status'=>"",'profile_image'=>"",'fingerprint_status'=>"",'auth_provider'=>"",'subscription_plan_status' =>"",'referral_code' =>"",'total_referral_points' =>"",'verification_image' =>"",'gender' =>"",'age' =>"",'address' =>"",'etippers_id' =>"");
			                		$arg['status']  = 0;
			                		$arg['error_code']   = ERROR_FAILED_CODE;
			                		$arg['error_line']= __line__;
						  			$arg['data']    = $userinfo;
						  			$arg['message'] = 'Document is not Verified.Please contact to Administration';
						  		    }else{
						            $userinfo = array('user_id' => $userid,'Authorization' => $user_token,'roles'=>$role_id,'first_name' => $fname, 'last_name'=>$lname,'full_name' =>$fullname,'wallet_amount'=>$data[0]['Current_Wallet_Balance'],'mobile'=>$data[0]['Mobile_No'],'email'=>$data[0]['Email'],'qrcode_image'=>$qrcode_image,'redirect_to_verify'=>"$redirect_to_verify",'notification_status'=>$data[0]['Notification_Status'],'profile_image'=>$profile_image,'fingerprint_status'=>$data[0]['Fingerprint_Status'],'auth_provider'=>$data[0]['Auth_Provider'],'subscription_plan_status' =>'1','referral_code' =>$data[0]['Referral_Code'],'total_referral_points' =>$data[0]['Total_Referral_Points'],'verification_image' =>$verification_image,'gender' =>$gender,'age' =>$Age,'address' =>$address, 'etippers_id' =>$etippers_id,"is_profile_completed"=>$is_profile_complete);
						                
									$arg['status']  = 1;
									$arg['error_code']   = SUCCESS_CODE;
			                        $arg['error_line']= __line__;
					  				$arg['message'] = $account_verify_msg;
					  				$arg['data']    = $userinfo;
					  			}
				  			
					}
					else
					{
						$arg['status']  = 0;
						$arg['error_code']   = ERROR_FAILED_CODE;
			            $arg['error_line']= __line__;
						$arg['message'] = 'Unable To Register User Using Social Login';
					}
				}
				
			}
	    }
	    echo json_encode($arg);
	}




	// Update After Social Login




		//Function used for update profile
	public function social_user_update_profile()
	{
		$arg = array();
		//check user is active or not
        /*$user_status = checkUserStatus(); 
		// print_r($user_status); exit;
		echo json_encode($user_status);exit;
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {*/
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error_code']   = 461;
					$arg['error_line']= __line__;
					$arg['message'] = $result;
				}
				else
				{
					$auth_token = $this->input->get_request_header('Authorization');
				    $user_token = json_decode(base64_decode($auth_token));
					$usid = $user_token->userid;
					$loguser = $this->dynamic_model->get_user_by_id($usid);
                    $email                  = $this->input->post('email');
					$mobile                 = $this->input->post('mobile');
					
					////////////////////////////////////////// CODE /////////////////////////////////////

					$this->form_validation->set_rules('first_name', 'Name', 'required|trim', array( 'required' => $this->lang->line('firstname')));
					$this->form_validation->set_rules('last_name', 'Last Name', 'required|trim', array( 'required' => $this->lang->line('lastname')));		
					
					if($loguser['Email'] !==$email){
					$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.Email]' , array('required' => $this->lang->line('email_required'),'valid_email' => $this->lang->line('email_valid'),'is_unique' => $this->lang->line('email_unique')
					));
				    }
				    if($loguser['Mobile_No'] !==$mobile){
					$this->form_validation->set_rules('mobile', 'Mobile', 'required|min_length[10]|max_length[12]|numeric|is_unique[users.Mobile_No]', array(
							'required' => $this->lang->line('mobile_required'),
							'min_length' => $this->lang->line('mobile_min_length'),
							'max_length' => $this->lang->line('mobile_max_length'),
							'numeric' => $this->lang->line('mobile_numeric'),
							'is_unique' => $this->lang->line('mobile_unique')
						));
			     	}
					$this->form_validation->set_rules('roles','Role', 'required', array( 'required' => $this->lang->line('role_required')));
					if(empty($_FILES['varification_image']['name']))
					{
						$this->form_validation->set_rules('varification_image','IMAGE','required', array( 'required' => $this->lang->line('verify_front')));
					}
					if ($this->form_validation->run() == FALSE)
					{
					  	$arg['status']  = 0;
					  	$arg['error_code']   = ERROR_FAILED_CODE;
					    $arg['error_line']= __line__;
					 	$arg['message'] = get_form_error($this->form_validation->error_array());
					}
					else
					{
						$firstname              = $this->input->post('first_name');
						$referral_code          = $this->input->post('referral_code');
						$varification_image = isset($_FILES['varification_image']['name']); 
						$roles =$this->input->post('roles');
						$doc_name = true;
			        	if(!($roles ==2 || $roles ==3))
					    {
							$arg['status']  = 0;
							$arg['error_code']   = ERROR_FAILED_CODE;
							$arg['error_line']= __line__;
							$arg['message'] = 'Please send valid role';
							echo json_encode($arg);exit;
					    }
			        	if($varification_image != '')
			        	{
							$doc_name = $this->dynamic_model->fileupload('varification_image','uploads/identification');
							if($doc_name)
							{
					        	$docPic = site_url().'uploads/identification/'.$doc_name;
							}
							else
							{
								$arg['status']  = 0;
								$arg['error_code']   = ERROR_FAILED_CODE;
								$arg['error_line']= __line__;
								$arg['message'] = 'File Type Is Not Supported';
								echo json_encode($arg);exit;
							}
						}			   
						if($doc_name)
						{
							$otpnumber = '1234';
							$my_referral_code= generateRandomString(10);
							$otpnumber=generate_Pin();
							$ref_num=getuniquenumber();
							//Users Register from Referral Code
					        if(!empty($referral_code)){	
					        	$ref_where = array('Referral_Code' =>$referral_code);	
								$get_referrals_data = $this->dynamic_model->getdatafromtable('users',$ref_where,'Id,Referral_Code,Is_Profile_Complete,Total_Referral_Points');
					             if(empty($get_referrals_data)){
					                $arg['status']  = 0;
								    $arg['error_code']   = ERROR_FAILED_CODE;
									$arg['error_line']= __line__;
									$arg['message'] = 'Please enter correct refferal code.';
									echo json_encode($arg);exit;
				           	    } 	
				            } 
				            $generate_etip_id = strtolower(substr($firstname, 0, 2)).rand (10,99).substr($mobile, -3).'@'.strtolower(SITE_TITLE);
				            $lat= $this->input->get_request_header('lat');
				            $long= $this->input->get_request_header('long');
							$userdata = array(
										       	// 'FullName'    => $full_name,
										       	'FirstName'   => $firstname,
										       	// 'lastName'    => $lastname,
										        'Password'    => $hashed_password,
										        'Email'       => $email,
										        'Mobile_No'   => $mobile,
										        'Profile_Pic' => 'default.jpg',
										        'Mobile_OTP'  => $otpnumber,
										        'Email_OTP'   => $otpnumber,
										        'Notification_Status'=> 1,
										        'Fingerprint_Status'   => 1,
										        'Referral_Code'=> $my_referral_code,
										        'Lat'=> (!empty($lat)) ? $lat : 0,
										        'Lang'=> (!empty($long)) ? $long : 0,
										        'etippers_id'=> $generate_etip_id,
						    				);
							if($this->input->post('last_name') && $this->input->post('last_name')!=""){
								$userdata["LastName"]=$this->input->post('last_name');
								$userdata["FullName"]= $this->input->post('first_name').' '.$this->input->post('last_name');
							}else{
								$userdata["FullName"]= $this->input->post('first_name');
							}


							$where = array(
						    	'Id' => $usid
						    );					    
							$updatedata = $this->dynamic_model->updateRowWhere("users",$where,$userdata);

							$userid = $usid;

							$where11 = array('User_Id' =>$userid);	
							$doc_data = $this->dynamic_model->getdatafromtable('users_documents',$where11);
                             if(empty($doc_data)){			
					        $users_documents = array('User_Id'            =>$userid,
								                     'Document_Type_Id'   =>2,
								                     //'Is_Verified'         =>1,
								                     'Document_Image_Name'=>$doc_name,
								                     'Created_By'         =>$userid
							                    );
				           	$users_documents_id = $this->dynamic_model->insertdata('users_documents', $users_documents); 
				            }

					        //Insert in referral table
					        if(!empty(@$get_referrals_data)){
							 $referrals_point_info = $this->dynamic_model->get_row('refferal_points_settings',array('Id'=>1));
							 $give_refferal_point=(!empty($referrals_point_info['Give_Refferal_Point'])) ? $referrals_point_info['Give_Refferal_Point'] : '0';
					         $referral_from  =@$get_referrals_data[0]['Id'];
					         $referral_points=@(!empty($get_referrals_data[0]['Total_Referral_Points'])) ? $get_referrals_data[0]['Total_Referral_Points'] : 0 ;
					         if($get_referrals_data[0]['Is_Profile_Complete']==1){
		                             $balance_points = $referral_points+$give_refferal_point;
		                             $referrals_data = array( 'Referral_From'   => $referral_from,
										                      'Referral_To'     => $userid,
										                      'Referral_Code'   =>$referral_code,
										                      'Referral_Points' =>$give_refferal_point,
										                      'Balance_Referral_Point' =>$balance_points,
										                      'Ref_Num'          =>$ref_num,
										                      'Status'           =>1
							                                );
					         }else{
		                               $referrals_data = array('Referral_From'   => $referral_from,
										                       'Referral_To'     => $userid,
										                       'Referral_Code'   =>$referral_code,
										                       'Referral_Points' =>$give_refferal_point,
										                       'Balance_Referral_Point' =>$referral_points,
										                       'Ref_Num'          =>$ref_num,
										                       'Status'          =>0
							                                  );     
					            }
				           	    $this->dynamic_model->insertdata('users_referrals',$referrals_data);
				                $wheres        = array("Id" => $referral_from);	
							    $get_user_data = $this->dynamic_model->getdatafromtable('users',$wheres,'Id,Total_Referral_Points');
							   if($get_referrals_data[0]['Is_Profile_Complete']==1){
								   if(!empty($get_user_data)){
								   	$total_points=@$get_user_data[0]['Total_Referral_Points']+$give_refferal_point;
								   }else{
								   	$total_points=$give_refferal_point;
								   }
					           	    $updadata2 = array('Total_Referral_Points'=>$total_points);
					                $updated_token = $this->dynamic_model->updateRowWhere("users",$wheres,$updadata2);
				               }
				           	 }
					        //function used for QRCode
							 $qr_number = generateQrcode($mobile);
	
							$role_data = $this->dynamic_model->getdatafromtable('user_in_roles',$where11);
                             if(empty($role_data)){	
		                       $users_roles = array('User_Id'=>$userid,
				                                 'Role_Id'=>$roles,
				                                 'Device_Id'=>$this->input->get_request_header('device_id'),
				                                 'Device_Type'=>$this->input->get_request_header('device_type'),
				                                 'QR_Code'=>$qr_number,
				                                 'QR_Code_Img_Path'=>$qr_number.'.png'
				                                );

				           	$users_roles_id = $this->dynamic_model->insertdata('user_in_roles', $users_roles);
				           }else{
				           	 $users_roles_id= @$role_data[0]['Role_Id'];
				           }
                           
					        if($userid)
					        {
				                //Send Email Code
								
				    //             $wh = array('Id' =>'userid');	
								// $user_data = $this->dynamic_model->getdatafromtable('users',$wh);
				                $where1 = array('slug' =>'send_otp_code');	
								$template_data = $this->dynamic_model->getdatafromtable('manage_notification_mail',$where1);
			                    $desc_data= str_replace('{OTP}',$otpnumber,$template_data[0]['description']);
									
								$emaildata['subject']     = $template_data[0]['subject']; 
								$emaildata['description'] = $desc_data;  
					        	//Send Email Code
								//$emaildata['subject']     = 'Registration';
								//$emaildata['description'] = 'Your OTP is :'.$otpnumber;
								$emaildata['body']        = '';

								$msg = $this->load->view('emailtemplate',$emaildata,TRUE);
								//--------------load email template----------------
								//$this->sendmail->sendmailto($email,'Verify mobile OTP Code',$msg);
								sendEmailCI($email,'' ,'Verify mobile OTP Code',$msg);

								$enc_user = encode($userid);
								$enc_role = encode($users_roles_id);
								$url = site_url().'webservices/users/verify_user?userid='.$enc_user.'&role='.$enc_role;
				                $where2 = array('slug' => 'sucessfully_registration_user');	
								$template_data = $this->dynamic_model->getdatafromtable('manage_notification_mail', $where2);
			                    $desc= str_replace('{NAME}',$full_name,$template_data[0]['description']);
			                    $desc_data= str_replace('{URL}',$url, $desc);
									
								$data['subject']     = $template_data[0]['subject']; 
								$data['description'] = $desc_data;  
								$data['body']        = '';
								$msg = $this->load->view('emailtemplate',$data,true);
								//$this->sendmail->sendmailto($email,'Verify Email From Etippers', "$msg");
								sendEmailCI($email,'','Verify Email From '.SITE_TITLE,$msg);
							   
                                 //document chk
                                 $where11 = array('User_Id' =>$userid);	
							     $doc_data = $this->dynamic_model->getdatafromtable('users_documents',$where11);
						    	
						    	if($doc_data[0]['Is_Verified'] == 1 && $users_roles_id== 3){
						    		 $doc_verified='1';
						    		 $msg=$this->lang->line('thank_msg');
						    	}elseif($users_roles_id== 2){
						    		$doc_verified='1';
						    		$msg=$this->lang->line('thank_msg');

						    	}else{
						    		$msg="Please verify document to continue";
						    		$doc_verified='0';
						    	}

								$result_data=array("user_id"=> "$userid","document_verify"=> "$doc_verified");
								$arg['status']  = 1;
							 	$arg['error_code']   = SUCCESS_CODE;
							 	$arg['error_line']= __line__;
							 	$arg['message'] = $msg;
							 	//$arg['url']     = $url;
							 	$arg['user_id']  = "$userid";
							 	$arg['data']  = $result_data;

					        }
					        else
					        {
					        	$arg['status']  = 0;
					        	$arg['error_code']   = ERROR_FAILED_CODE;
					            $arg['error_line']= __line__;
								$arg['message'] = $this->lang->line('server_problem');
					        }
					    }
					    else
					    {
					    	$arg['status']  = 0;
					    	$arg['error_code']   = ERROR_FAILED_CODE;
					        $arg['error_line']= __line__;
							$arg['message'] = $this->lang->line('invalid_image');
					    }
					}


					////////////////////////////////////////// CODE /////////////////////////////////////
				}
			//}
		}
		echo json_encode($arg);
	}    




	public function social_user_update_profile_old()
	{		
		$arg = array();
		$version_result = version_check_helper();
		if($version_result['status'] != 1 )
		{
			$arg = $version_result;
		} 
		else
		{
			$this->form_validation->set_rules('first_name', 'Name', 'required|trim', array( 'required' => $this->lang->line('firstname')));
			// $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim', array( 'required' => $this->lang->line('lastname')));		
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.Email]' , array('required' => $this->lang->line('email_required'),'valid_email' => $this->lang->line('email_valid'),'is_unique' => $this->lang->line('email_unique')
			));
			$this->form_validation->set_rules('mobile', 'Mobile', 'required|min_length[10]|max_length[12]|numeric|is_unique[users.Mobile_No]', array(
					'required' => $this->lang->line('mobile_required'),
					'min_length' => $this->lang->line('mobile_min_length'),
					'max_length' => $this->lang->line('mobile_max_length'),
					'numeric' => $this->lang->line('mobile_numeric'),
					'is_unique' => $this->lang->line('mobile_unique')
				));
			$this->form_validation->set_rules('roles','Role', 'required', array( 'required' => $this->lang->line('role_required')));
			if(empty($_FILES['varification_image']['name']))
			{
				$this->form_validation->set_rules('varification_image','IMAGE','required', array( 'required' => $this->lang->line('verify_front')));
			}
			if ($this->form_validation->run() == FALSE)
			{
			  	$arg['status']  = 0;
			  	$arg['error_code']   = ERROR_FAILED_CODE;
			    $arg['error_line']= __line__;
			 	$arg['message'] = get_form_error($this->form_validation->error_array());
			}
			else
			{


					$cond = array(
							'User_Id' => $userDetail['Id']
						);
					$userrole = getdatafromtable('user_in_roles', $cond);


				
				$firstname              = $this->input->post('first_name');
				$email                  = $this->input->post('email');
				$mobile                 = $this->input->post('mobile');
				//$id_pass_number         = $this->input->post('id_pass_number');
				$referral_code          = $this->input->post('referral_code');
				// $hashed_password        = encrypt_password($this->input->post('password'));
				$varification_image = isset($_FILES['varification_image']['name']); 
				$roles =$this->input->post('roles');
				$doc_name = true;
	        	if(!($roles ==2 || $roles ==3))
			    {
					$arg['status']  = 0;
					$arg['error_code']   = ERROR_FAILED_CODE;
					$arg['error_line']= __line__;
					$arg['message'] = 'Please send valid role';
					echo json_encode($arg);exit;
			    }
	        	if($varification_image != '')
	        	{
					$doc_name = $this->dynamic_model->fileupload('varification_image','uploads/identification');
					if($doc_name)
					{
			        	$docPic = site_url().'uploads/identification/'.$doc_name;
					}
					else
					{
						$arg['status']  = 0;
						$arg['error_code']   = ERROR_FAILED_CODE;
						$arg['error_line']= __line__;
						$arg['message'] = 'File Type Is Not Supported';
						echo json_encode($arg);exit;
					}
				}			   
				if($doc_name)
				{
					$otpnumber = '1234';
					$my_referral_code= generateRandomString(10);
					$otpnumber=generate_Pin();
					$ref_num=getuniquenumber();
					//Users Register from Referral Code
			        if(!empty($referral_code)){	
			        	$ref_where = array('Referral_Code' =>$referral_code);	
						$get_referrals_data = $this->dynamic_model->getdatafromtable('users',$ref_where,'Id,Referral_Code,Is_Profile_Complete,Total_Referral_Points');
			             if(empty($get_referrals_data)){
			                $arg['status']  = 0;
						    $arg['error_code']   = ERROR_FAILED_CODE;
							$arg['error_line']= __line__;
							$arg['message'] = 'Please enter correct refferal code.';
							echo json_encode($arg);exit;
		           	    } 	
		            } 
		            $generate_etip_id = strtolower(substr($firstname, 0, 2)).rand (10,99).substr($mobile, -3).'@'.strtolower(SITE_TITLE);
		            $lat= $this->input->get_request_header('lat');
		            $long= $this->input->get_request_header('long');
					$userdata = array(
								       	// 'FullName'    => $full_name,
								       	'FirstName'   => $firstname,
								       	// 'lastName'    => $lastname,
								        'Password'    => $hashed_password,
								        'Email'       => $email,
								        'Mobile_No'   => $mobile,
								        'Profile_Pic' => 'default.jpg',
								        'Mobile_OTP'  => $otpnumber,
								        'Email_OTP'   => $otpnumber,
								        'Notification_Status'=> 1,
								        'Fingerprint_Status'   => 1,
								        'Referral_Code'=> $my_referral_code,
								        'Lat'=> (!empty($lat)) ? $lat : 0,
								        'Lang'=> (!empty($long)) ? $long : 0,
								        'etippers_id'=> $generate_etip_id,
				    				);
					if($this->input->post('last_name') && $this->input->post('last_name')!=""){
						$userdata["LastName"]=$this->input->post('last_name');
						$userdata["FullName"]= $this->input->post('first_name').' '.$this->input->post('last_name');
					}else{
						$userdata["FullName"]= $this->input->post('first_name');
					}


					$where = array(
				    	'Id' => $usid
				    );					    
					$updatedata = $this->dynamic_model->updateRowWhere("users",$where,$userdata);

					$userid = $usid;

					// $userid = $this->dynamic_model->insertdata('users', $userdata); 				
			        $users_documents = array('User_Id'            =>$userid,
						                     'Document_Type_Id'   =>2,
						                     //'Pin_Number'         =>$id_pass_number,
						                     //'Is_Verified'         =>1,
						                     'Document_Image_Name'=>$doc_name,
						                     'Created_By'         =>$userid
					                    );
		           	$users_documents_id = $this->dynamic_model->insertdata('users_documents', $users_documents); 

			        //Insert in referral table
			        if(!empty($get_referrals_data)){
					 $referrals_point_info = $this->dynamic_model->get_row('refferal_points_settings',array('Id'=>1));
					 $give_refferal_point=(!empty($referrals_point_info['Give_Refferal_Point'])) ? $referrals_point_info['Give_Refferal_Point'] : '0';
			         $referral_from  =@$get_referrals_data[0]['Id'];
			         $referral_points=@(!empty($get_referrals_data[0]['Total_Referral_Points'])) ? $get_referrals_data[0]['Total_Referral_Points'] : 0 ;
			         if($get_referrals_data[0]['Is_Profile_Complete']==1){
                             $balance_points = $referral_points+$give_refferal_point;
                             $referrals_data = array( 'Referral_From'   => $referral_from,
								                      'Referral_To'     => $userid,
								                      'Referral_Code'   =>$referral_code,
								                      'Referral_Points' =>$give_refferal_point,
								                      'Balance_Referral_Point' =>$balance_points,
								                      'Ref_Num'          =>$ref_num,
								                      'Status'           =>1
					                                );
			         }else{
                               $referrals_data = array('Referral_From'   => $referral_from,
								                       'Referral_To'     => $userid,
								                       'Referral_Code'   =>$referral_code,
								                       'Referral_Points' =>$give_refferal_point,
								                       'Balance_Referral_Point' =>$referral_points,
								                       'Ref_Num'          =>$ref_num,
								                       'Status'          =>0
					                                  );     
			            }
		           	    $this->dynamic_model->insertdata('users_referrals',$referrals_data);
		                $wheres        = array("Id" => $referral_from);	
					    $get_user_data = $this->dynamic_model->getdatafromtable('users',$wheres,'Id,Total_Referral_Points');
					   if($get_referrals_data[0]['Is_Profile_Complete']==1){
						   if(!empty($get_user_data)){
						   	$total_points=@$get_user_data[0]['Total_Referral_Points']+$give_refferal_point;
						   }else{
						   	$total_points=$give_refferal_point;
						   }
			           	    $updadata2 = array('Total_Referral_Points'=>$total_points);
			                $updated_token = $this->dynamic_model->updateRowWhere("users",$wheres,$updadata2);
		               }
		           	 }
			        //function used for QRCode
					 $qr_number = generateQrcode($mobile);
                        $users_roles = array('User_Id'=>$userid,
		                                 'Role_Id'=>$roles,
		                                 'Device_Id'=>$this->input->get_request_header('device_id'),
		                                 'Device_Type'=>$this->input->get_request_header('device_type'),
		                                 'QR_Code'=>$qr_number,
		                                 'QR_Code_Img_Path'=>$qr_number.'.png'
		                                );

		           	$users_roles_id = $this->dynamic_model->insertdata('user_in_roles', $users_roles);

			        if($userid && $users_roles_id && $users_documents_id)
			        {
		                //Send Email Code
						
		                $where1 = array('slug' =>'send_otp_code');	
						$template_data = $this->dynamic_model->getdatafromtable('manage_notification_mail', $where1);
	                    $desc_data= str_replace('{OTP}',$otpnumber,$template_data[0]['description']);
							
						$emaildata['subject']     = $template_data[0]['subject']; 
						$emaildata['description'] = $desc_data;  
			        	//Send Email Code
						//$emaildata['subject']     = 'Registration';
						//$emaildata['description'] = 'Your OTP is :'.$otpnumber;
						$emaildata['body']        = '';

						$msg = $this->load->view('emailtemplate',$emaildata,TRUE);
						//--------------load email template----------------
						//$this->sendmail->sendmailto($email,'Verify mobile OTP Code',$msg);
						sendEmailCI($email,'' ,'Verify mobile OTP Code',$msg);

						$enc_user = encode($userid);
						$enc_role = encode($users_roles_id);
						$url = site_url().'webservices/users/verify_user?userid='.$enc_user.'&role='.$enc_role;
		                $where2 = array('slug' => 'sucessfully_registration_user');	
						$template_data = $this->dynamic_model->getdatafromtable('manage_notification_mail', $where2);
	                    $desc= str_replace('{NAME}',$full_name,$template_data[0]['description']);
	                    $desc_data= str_replace('{URL}',$url, $desc);
							
						$data['subject']     = $template_data[0]['subject']; 
						$data['description'] = $desc_data;  
						$data['body']        = '';
						$msg = $this->load->view('emailtemplate',$data,true);
						//$this->sendmail->sendmailto($email,'Verify Email From Etippers', "$msg");
						sendEmailCI($email,'' ,'Verify Email From '.SITE_TITLE,$msg);

						$arg['status']  = 1;
					 	$arg['error_code']   = SUCCESS_CODE;
					 	$arg['error_line']= __line__;
					 	$arg['message'] = "Your Profile Successfully Updated";
					 	//$arg['url']     = $url;
					 	$arg['user_id']  = "$userid";
					 	$arg['data']['user_id']  = "$userid";

			        }
			        else
			        {
			        	$arg['status']  = 0;
			        	$arg['error_code']   = ERROR_FAILED_CODE;
			            $arg['error_line']= __line__;
						$arg['message'] = $this->lang->line('server_problem');
			        }
			    }
			    else
			    {
			    	$arg['status']  = 0;
			    	$arg['error_code']   = ERROR_FAILED_CODE;
			        $arg['error_line']= __line__;
					$arg['message'] = $this->lang->line('invalid_image');
			    }
			}
	    }
		
		echo json_encode($arg);
	}

 











	//Logout for customer or merchant
	public function logout()
	{
		$arg            = array();
		$arg['status']  = 1;
		$arg['error_code']   = SUCCESS_CODE;
		$arg['error_line']= __line__;
		$arg['message'] = check_authorization('logout');
		echo json_encode($arg);
	}

	//Used function for resend otp
	public function resend_otp()
	{
		$arg = array();
		$version_result = version_check_helper();
		if($version_result['status'] != 1 )
		{
			$arg = $version_result;
		} 
		else
		{
			$_POST = $this->input->post();

			if($_POST == [])
			{
				$_POST = json_decode(file_get_contents("php://input"), true);
			}
			if($_POST)
			{
				$this->form_validation->set_rules('user_id', 'User ID', 'required|numeric');
				//$this->form_validation->set_rules('mobile', '', 'required', array('required' => 'Mobile number required'));
				if ($this->form_validation->run() == FALSE)
				{
				  	$arg['status']  = 0;
				  	$arg['error_code']   = ERROR_FAILED_CODE;
			        $arg['error_line']= __line__;
				  	$arg['message'] = get_form_error($this->form_validation->error_array());
				}
				else
				{
	                //$mobile_no = $this->input->post('mobile');
	                $user_id = $this->input->post('user_id');
	                $condition = array('Id' => $user_id);
					$us_info   = getdatafromtable('users',$condition);
					if(!empty($us_info))
					{
						// $otpnumber = generatePIN();
						// $otpmsg = "Use $otpnumber as one time password (OTP) for mobile varification to your yugpay account. Do not share this OTP to  anyone for security reasons. ";
						// sendSms($mobile, $otpmsg);
				       	//$otpnumber = '1234';
				       	$otpnumber=generate_Pin();
				       	$data   = array("Mobile_OTP"=>$otpnumber,"Last_Updated_Date_Time"=>date('Y-m-d H:i:s'));
				       	$where  = array('Id'=>$user_id);
				       	$update_user = $this->dynamic_model->updateRowWhere("users",$where,$data);
				       	$new_otp = array(
				                 'otp'=>$otpnumber
				             );
						//Send Email Code
		                $where1 = array('slug' => 'resend_otp_code');	
						$template_data = $this->dynamic_model->getdatafromtable('manage_notification_mail', $where1);
	                    $desc_data= str_replace('{OTP}',$otpnumber,$template_data[0]['description']);
							
						$emaildata['subject']     = $template_data[0]['subject']; 
						$emaildata['description'] = $desc_data;  
						$emaildata['body']        = '';

						$msg = $this->load->view('emailtemplate',$emaildata,TRUE);
						// $result = email_function($email, 'Registration', $msg);
						//--------------load email template----------------
						//$this->sendmail->sendmailto($us_info[0]['Email'],'Thank You For Resend OTP Code',$msg);
						sendEmailCI($us_info[0]['Email'],'' ,'Your OTP code',$msg);

					   	$arg['status']  = 1;
					   	$arg['error_code']   = SUCCESS_CODE;
			            $arg['error_line']= __line__;
			  		   	$arg['message'] = $this->lang->line('otp_send');
			  		   	$arg['data']    = $new_otp;
					}
					else
					{
	                    $arg['status']  = 0;
	                    $arg['error_code']   = ERROR_FAILED_CODE;  
			            $arg['error_line']= __line__;
			  		    $arg['message'] = $this->lang->line('mobile_not_register');
					}
				}
				
			}
	    }
	     echo json_encode($arg);
	}

	//Used function for change password
	public function changepassword()
	{
		$arg    = array();
		//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0 && !empty($user_status))
        {
        	$arg = $user_status;
        }
        else
        {
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = $this->check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error_code']   = 461;
					$arg['error_line']= __line__;
					$arg['message'] = $result;
				}
				else
				{
					$_POST = $this->input->post();

					if($_POST == [])
					{
						$_POST = json_decode(file_get_contents("php://input"), true);
					}
					if($_POST)
					{
						$this->form_validation->set_rules('old_password', 'Old Password', 'trim|required');
						//$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[6]');
						$new_password= $this->input->post('new_password');
						$pass_msg= $this->valid_password($new_password);
						if($pass_msg !== TRUE){
						$this->form_validation->set_rules('new_password','Password','trim|required|callback_valid_password');
						$this->form_validation->set_message('valid_password', $pass_msg);
					    }
						if ($this->form_validation->run() == FALSE)
						{
							$arg['status']  = 0;
							$arg['error_code']   = ERROR_FAILED_CODE;
							$arg['error_line']= __line__;
							$arg['message'] = get_form_error($this->form_validation->error_array());
						}
						else
						{
							$auth_token = $this->input->get_request_header('Authorization');
					    	$user_token = json_decode(base64_decode($auth_token));

							$usid    = $user_token->userid;
							$loguser = $this->dynamic_model->get_user_by_id($usid);
							$hashed_password = encrypt_password($this->input->post('old_password'));
							if($hashed_password == $loguser['Password'])
							{
								$data1 = array(
												'Password' => encrypt_password($new_password),
												'Last_Updated_By'     =>$usid,
												'Last_Updated_Date_Time'=>date('Y-m-d H:i:s')
											);
				                $where     = array("Id" => $usid);
				                $keyUpdate = $this->dynamic_model->updateRowWhere("users",$where,$data1);
				                if($keyUpdate)
				                {
				                	$arg['status']  = 1;
				                	$arg['error_code']   = SUCCESS_CODE;
			                        $arg['error_line']= __line__;
									$arg['message'] = $this->lang->line('password_change_success');
				                }
				                else
				                {
				                	$arg['status '] = 0;
				                	$arg['error_code']   = ERROR_FAILED_CODE;
			                        $arg['error_line']= __line__;
				                	$arg['message'] = $this->lang->line('password_not_update');
				                }
							}
							else
							{
								$arg['status']  = 0;
								$arg['error_code']   = ERROR_FAILED_CODE;
								$arg['error_line']= __line__;
								$arg['message'] = $this->lang->line('old_password_not');
							}
						}
					}
				}
			}
		}
		echo json_encode($arg);
	}

	//Used function for forget password
	public function forgot_password()
	{
		$arg = array();
		$version_result = version_check_helper();
		if($version_result['status'] != 1 )
		{
			$arg = $version_result;
		} 
		else
		{
			$_POST = $this->input->post();

			if($_POST == [])
			{
				$_POST = json_decode(file_get_contents("php://input"), true);
			}
			if($_POST)
			{
				$arg = array();
				$this->form_validation->set_rules('mobileEmail', '', 'required');
				if ($this->form_validation->run() == FALSE)
				{
					$arg['status']  = 0;
					$arg['error_code']   = ERROR_FAILED_CODE;
					$arg['error_line']= __line__;
					$arg['message'] = get_form_error($this->form_validation->error_array());
				}
				else
				{
					$data = $this->dynamic_model->checkMobile($this->input->post('mobileEmail'));
					if(!empty($data))
					{
						if($data[0]['Is_Email_Verified']==1){
							$where2    = array('User_Id' => $data[0]['Id'],'Otp_For'=>'forget');
							$response  = $this->dynamic_model->deletedata('user_otp',$where2);
							$condition = array(
											'User_Id' => $data[0]['Id'],
											'Otp_For' => 'forget'
										);
							$otp_user = getdatafromtable('user_otp', $condition);
							if(empty($otp_user))
							{
								$otpnumber = '1234';
								$otpnumber=generate_Pin();
								// $otpnumber = generatePIN();
								// $otpmsg = "Use $otpnumber as one time password (OTP) for mobile varification to your yugpay account. Do not share this OTP to  anyone for security reasons. ";
								// sendSms($mobile, $otpmsg);
								$forget_data = array('User_Id'     => $data[0]['Id'],
					                                    'Send_Otp' => $otpnumber,
					                                    'Otp_For'  =>'forget'
					                                );
								$otpid  = $this->dynamic_model->insertdata('user_otp', $forget_data);
								$full_name=ucwords($data[0]['FullName']);
								//Send Email Code
				                $where1 = array('slug' => 'forget_password');	
								$template_data = $this->dynamic_model->getdatafromtable('manage_notification_mail', $where1);
			                    $desc_data= str_replace('{OTP}',$otpnumber,$template_data[0]['description']);
			                    $desc= str_replace('{USERNAME}',$full_name,$desc_data);
									
								$emaildata['subject']     = $template_data[0]['subject']; 
								$emaildata['description'] = $desc;  
								$emaildata['body']        = '';

								$msg    = $this->load->view('emailtemplate', $emaildata, TRUE);
								// $result = email_function($email, 'Registration', $msg);
								//--------------load email template----------------
								$this->sendmail->sendmailto($data[0]['Email'],'Forgot password',$msg);
								//sendEmailCI($data[0]['Email'],'' ,'Forgot password',$msg);

								if($otpid)
								{
									$arg['status']         = 1;
									$arg['error_code']     = SUCCESS_CODE;
				                    $arg['error_line']     = __line__;
									$arg['message']        = $this->lang->line('forgot_otp_send_to_email');
									$arg['data']['otp']    = $otpnumber;
									$arg['data']['user_id'] = $data[0]['Id'];
								}
								else
								{
									$arg['status']  = 0;
									$arg['error_code']   = ERROR_FAILED_CODE;
									$arg['error_line']= __line__;
									$arg['message'] = $this->lang->line('forgot_otp_not_send');
								}
							}
							else
							{
								$arg['status']  = 0;
								$arg['error_code']   = ERROR_FAILED_CODE;
								$arg['error_line']= __line__;
								$arg['message'] = $this->lang->line('otp_already_send');
							}
					    }
                        else
						{
							$arg['status']  = 0;
							$arg['error_code']   = ERROR_FAILED_CODE;
							$arg['error_line']= __line__;
					  		$arg['message'] = 'Please verify your email';
						}
					}
					else
					{
						$arg['status']  = 0;
						$arg['error_code']   = ERROR_FAILED_CODE;
						$arg['error_line']= __line__;
						$arg['message'] = $this->lang->line('email_not_exist');
					}
				}
				
			}
	    }
	   echo json_encode($arg);
	}

	public function forgot_password_temp()
	{
		$arg = array();
		$version_result = version_check_helper();
		if($version_result['status'] != 1 )
		{
			$arg = $version_result;
		} 
		else
		{
			$_POST = $this->input->post();

			if($_POST == [])
			{
				$_POST = json_decode(file_get_contents("php://input"), true);
			}
			if($_POST)
			{
				$arg = array();
				$this->form_validation->set_rules('mobileEmail', '', 'required');
				if ($this->form_validation->run() == FALSE)
				{
					$arg['status']  = 0;
					$arg['error_code']   = ERROR_FAILED_CODE;
					$arg['error_line']= __line__;
					$arg['message'] = get_form_error($this->form_validation->error_array());
				}
				else
				{
					$data = $this->dynamic_model->checkMobile($this->input->post('mobileEmail'));
					if(!empty($data))
					{
						if($data[0]['Is_Email_Verified']==1){
							$where2    = array('User_Id' => $data[0]['Id'],'Otp_For'=>'forget');
							$response  = $this->dynamic_model->deletedata('user_otp',$where2);
							$condition = array(
											'User_Id' => $data[0]['Id'],
											'Otp_For' => 'forget'
										);
							$otp_user = getdatafromtable('user_otp', $condition);
							if(empty($otp_user))
							{
								$otpnumber = '1234';
								$otpnumber=generate_Pin();
								// $otpnumber = generatePIN();
								// $otpmsg = "Use $otpnumber as one time password (OTP) for mobile varification to your yugpay account. Do not share this OTP to  anyone for security reasons. ";
								// sendSms($mobile, $otpmsg);
								$forget_data = array('User_Id'     => $data[0]['Id'],
					                                    'Send_Otp' => $otpnumber,
					                                    'Otp_For'  =>'forget'
					                                );
								$otpid  = $this->dynamic_model->insertdata('user_otp', $forget_data);
								$full_name=ucwords($data[0]['FullName']);
								//Send Email Code
				                $where1 = array('slug' => 'forget_password');	
								$template_data = $this->dynamic_model->getdatafromtable('manage_notification_mail', $where1);
			                    $desc_data= str_replace('{OTP}',$otpnumber,$template_data[0]['description']);
			                    $desc= str_replace('{USERNAME}',$full_name,$desc_data);
									
								$emaildata['subject']     = $template_data[0]['subject']; 
								$emaildata['description'] = $desc;  
								$emaildata['body']        = '';

								$msg    = $this->load->view('emailtemplate', $emaildata, TRUE);
								// $result = email_function($email, 'Registration', $msg);
								//--------------load email template----------------
								//$result = $this->sendmail->sendmailto($data[0]['Email'],'Forgot password',$msg);
								$result = $this->phpmailer->SendTo($data[0]['Email'],'Forgot password',$msg);


								//$result = sendEmailCI($data[0]['Email'],'' ,'Forgot password',$msg);

								$arg['status']  = 0;
								$arg['error_code']   = ERROR_FAILED_CODE;
								$arg['error_line']= __line__;
								$arg['message'] = $result;

								

								
							}
							else
							{
								$arg['status']  = 0;
								$arg['error_code']   = ERROR_FAILED_CODE;
								$arg['error_line']= __line__;
								$arg['message'] = $this->lang->line('otp_already_send');
							}
					    }
                        else
						{
							$arg['status']  = 0;
							$arg['error_code']   = ERROR_FAILED_CODE;
							$arg['error_line']= __line__;
					  		$arg['message'] = 'Please verify your email';
						}
					}
					else
					{
						$arg['status']  = 0;
						$arg['error_code']   = ERROR_FAILED_CODE;
						$arg['error_line']= __line__;
						$arg['message'] = $this->lang->line('email_not_exist');
					}
				}
				
			}
	    }
	   echo json_encode($arg);
	}

	//Used function for varify forgot password otp 
	public function verify_forgot_otp()
	{
		$arg = array();
		$version_result = version_check_helper();
		if($version_result['status'] != 1 )
		{
			$arg = $version_result;
		} 
		else
		{
			$_POST = $this->input->post();

			if($_POST == [])
			{
				$_POST = json_decode(file_get_contents("php://input"), true);
			}
			if($_POST)
			{
				$arg = array();
				$this->form_validation->set_rules('user_id', 'User ID', 'required');
				$this->form_validation->set_rules('otp', 'OTP','required|max_length[4]');

				if ($this->form_validation->run() == FALSE)
				{
				  	$arg['status']  = 0;
				  	$arg['error_code']   = ERROR_FAILED_CODE;
				  	$arg['error_line']= __line__;
				  	$arg['message'] =  get_form_error($this->form_validation->error_array());
				} 
				else
				{
					$usid     = $this->input->post('user_id');
					$user_otp = $this->input->post('otp');

					$condition = array('User_Id' => $usid,'Send_Otp' => $user_otp);
					$result    = getdatafromtable('user_otp', $condition);
					if($result)
					{
		                $arg['status']         = 1;
		                $arg['error_code']   = SUCCESS_CODE;
			            $arg['error_line']= __line__;
				  		$arg['message']        = $this->lang->line('otp_verify');
				  		$arg['data']['user_id'] = $usid;
					}
					else
					{
						$arg['status']  = 0;
						$arg['error_code']   = ERROR_FAILED_CODE;
						$arg['error_line']= __line__;
				  		$arg['message'] = $this->lang->line('otp_not_match');
					}
				}
				
			}
	    }
	   echo json_encode($arg);
	}

	//Used function for change forgot password
	public function change_forgot_password()
	{
		$arg = array();
		$version_result = version_check_helper();
		if($version_result['status'] != 1 )
		{
			$arg = $version_result;
		} 
		else
		{
			$_POST = $this->input->post();

			if($_POST == [])
			{
				$_POST = json_decode(file_get_contents("php://input"), true);
			}
			if($_POST)
			{
				$this->form_validation->set_rules('user_id', 'User ID', 'required');
				$this->form_validation->set_rules('otp', 'OTP','required|max_length[4]');
				
				$new_password= $this->input->post('new_password');
				$pass_msg= $this->valid_password($new_password);
				if($pass_msg !== TRUE){
				$this->form_validation->set_rules('new_password','Password','trim|required|callback_valid_password');
				$this->form_validation->set_message('valid_password', $pass_msg);
			    }

				if ($this->form_validation->run() == FALSE)
				{
					$arg['status']  = 0;
					$arg['error_code']   = ERROR_FAILED_CODE;
					$arg['error_line']= __line__;
					$arg['message'] = get_form_error($this->form_validation->error_array());
				}
				else
				{
					$userid  = $this->input->post('user_id');
					$loguser = $this->dynamic_model->get_user_by_id($userid);
					$user_otp = $this->input->post('otp');
                    if(!empty($loguser )){
                    	if($loguser['Is_Email_Verified']==1){
							$condition = array('User_Id' => $userid ,'Send_Otp' => $user_otp,'Otp_For'  =>'forget');
							$result    = getdatafromtable('user_otp', $condition);
							if($result)
							{
								$data1   = array(
												'Password' => encrypt_password($new_password),
												'Last_Updated_By'     =>$userid,
												'Last_Updated_Date_Time'=>date('Y-m-d H:i:s')
											);
				                $where     = array("Id" => $userid);
				                $keyUpdate = $this->dynamic_model->updateRowWhere("users",$where,$data1);

				                $where2   = array('User_Id' => $userid);
								$response = $this->dynamic_model->deletedata('user_otp',$where2);
				                if($keyUpdate)
				                {
				                	$arg['status']  = 1;
				                	$arg['error_code']   = SUCCESS_CODE;
			                        $arg['error_line']= __line__;
									$arg['message'] = $this->lang->line('password_change_success');
				                }
				                else
				                {
				                	$arg['status']  = 0;
				                	$arg['error_code']   = ERROR_FAILED_CODE;
				                	$arg['error_line']= __line__;
									$arg['message'] = $this->lang->line('password_not_change');
				               }
				            }
			                else
							{
								$arg['status']  = 0;
								$arg['error_code']   = ERROR_FAILED_CODE;
								$arg['error_line']= __line__;
						  		$arg['message'] = $this->lang->line('otp_not_match');
							}
						}
		                else
						{
							$arg['status']  = 0;
							$arg['error_code']   = ERROR_FAILED_CODE;
							$arg['error_line']= __line__;
					  		$arg['message'] = 'Please verify your email';
						}
				    }
					else
					{
						$arg['status']  = 0;
						$arg['error_code']   = ERROR_FAILED_CODE;
						$arg['error_line']= __line__;
				  		$arg['message'] = "Invalid user";
					}
				}
			}
	    }
	  echo json_encode($arg);
	}

	//Used function to get data on dashboard(sender or receiver)
	public function get_dashboard_data()
	{
		$arg    = array();
		//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error_code']   = 461;
					$arg['error_line']= __line__;
					$arg['message'] = $result;
				}
				else
				{
					$auth_token = $this->input->get_request_header('Authorization');
					$lat = $this->input->get_request_header('lat');
					$lang = $this->input->get_request_header('long');
			    	$user_token = json_decode(base64_decode($auth_token));
					$usid    = $user_token->userid;
					$loguser = $this->dynamic_model->get_user_by_id($usid);
					if($loguser)
					{
						$loguser_roles = $this->dynamic_model->get_row('user_in_roles',array('User_Id'=> $loguser['Id']));
						$user_role_id = $loguser_roles['Role_Id'];
						// Paid transaction 
						$paidTransaction = $this->dynamic_model->get_rows('transactions',array('To_User_Id'=>$usid,'Tran_Status_Id'=>6));
						if($paidTransaction)
							$paid_transaction  = count($paidTransaction);
						else
							$paid_transaction  = "0";

						// Unread Notifications Count
						$cond = "Recepient_Id = '".$usid."' AND Is_Read = '0' AND Is_Deleted = '0' AND `Read_Date_Time` >= ( CURDATE() - INTERVAL 15 DAY )";
						$notification_count = $this->dynamic_model->getdatafromtable('user_notifications', $cond, '*');
						if($notification_count)
							$unread_count  = count($notification_count);
						else
							$unread_count  = "0";

						$cur_date  = strtotime(date('Y-m-d H:i:s'));
						$advdata=array();  
						$condition=array('status'=>'1');
						//$advertisement_data = $this->dynamic_model->getdatafromtable("manage_advertisement_images",$condition);
						$advertisement_data = $this->users_model->get_advertisements_data($lat,$lang);
						if(!empty($advertisement_data))
						{
						    foreach($advertisement_data as $value) 
						    {
						    	$advdata['advertisement_id']   = $value['Id'];
						    	$advdata['advertisement_title']= $value['Advertisement_Title'];
						    	$advdata['advertisement_subtitle']= $value['Advertisement_Subtitle'];
						    	$advdata['advertisement_image']=  site_url().'uploads/advertisement_img/'.$value['Advertisement_Image'];
						    	$finaldata[]	          = $advdata;
						    }
						      
						}else{
							$finaldata=[];
						    }
							$transaction_data = $this->dynamic_model->getdatafromtable('transactions', array('To_User_Id'=>$usid) , '*','5','0','Id');

							//echo $this->db->last_query();die;
							//echo "<pre>";print_r($transaction_data);die;	
							$transaction_array=array();
							if(!empty($transaction_data))
							{
								$user_data = array();
								foreach($transaction_data as $details)
			            		{
			            			$transaction_status = $this->dynamic_model->get_row('tran_status',array('Id'=> $details['Tran_Status_Id']));

			            			if($details['From_User_Id'])
			            				$Requested_user = $this->dynamic_model->get_user_by_id($details['From_User_Id']);

			            			if(!empty($details['organisation_id'])){
			            				$org_data = $this->dynamic_model->getdatafromtable('organization_details',array('Id'=>$details['organisation_id']),'Organization_Name');
			            				$org_name=(!empty($org_data[0]['Organization_Name'])) ? $org_data[0]['Organization_Name'] : "";
			            			}else{
			            				$org_name='';
			            			}

			            			if($details['Tran_Type_Id'] == "1") //withdraw
			            			{
			            				$user_data["Id"]                 = $details['Id'];
			            				$user_data["title"]              = "Withdraw Money";
			            				$user_data["fullname"]           = "";
			            				$user_data["user_image"]         = "";
			            				$user_data["mobile"]             = "";
			            				$user_data["order_number"]       = $details['Third_Party_Tran_Id'];
			            				$user_data["amount"]             = number_format($details['Amount'],2);
			            				$user_data["charge"]             = number_format($details['Charge'],2);
			            				$user_data['created_at']         = date('d M Y', strtotime($details['Creation_Date_Time']));
			            				if($transaction_status['Status_Name'] == "Reject")
			            					$user_data['transaction_status'] = "Failed";
			            				else
			            					$user_data['transaction_status'] = $transaction_status['Status_Name'];
			            				$user_data['trx_type']           = "Withdraw";
			            				$user_data['tran_type_id']       = $details['Tran_Type_Id'];
			            				$user_data['tran_status_id']       = $details['Tran_Status_Id'];
			            				$user_data['time']               = date('g:i A',strtotime($details['Creation_Date_Time']));
			            				$user_data['msg']                = (!empty($details['Msg'])) ? $details['Msg'] : "";
			            				$user_data['organization_title']  = $org_name;
			            				$transaction_array[]             = $user_data;
			            			}
			            			if($details['Tran_Type_Id'] == "2" && ($user_role_id == '2' || $details['Is_Referral_Point']==1)) //add
			            			{
			            				$user_data["Id"]                 = $details['Id'];
			            				$user_data["title"]              = "Money added";
			            				$user_data["fullname"]           = "";
			            				$user_data["user_image"]         = "";
			            				$user_data["mobile"]             = "";
			            				$user_data["order_number"]       = $details['Third_Party_Tran_Id'];
			            				$user_data["amount"]             = number_format($details['Amount'],2);
			            				$user_data["charge"]             = number_format($details['Charge'],2);
			            				$user_data['created_at']         = date('d M Y', strtotime($details['Creation_Date_Time']));
			            				if($transaction_status['Status_Name'] == "Reject")
			            					$user_data['transaction_status'] = "Failed";
			            				else
			            					$user_data['transaction_status'] = $transaction_status['Status_Name'];
			            				$user_data['trx_type']           = "Deposit";
			            				$user_data['tran_type_id']       = $details['Tran_Type_Id'];
			            				$user_data['tran_status_id']       = $details['Tran_Status_Id'];
			            				$user_data['time']               = date('g:i A',strtotime($details['Creation_Date_Time']));
			            				$user_data['msg']                = (!empty($details['Msg'])) ? $details['Msg'] : "";
			            				$user_data['organization_title']  = $org_name;
			            				$transaction_array[]             = $user_data;
			            			}
			            			if($details['Tran_Type_Id'] == "3" && $user_role_id == '2') //send tip
			            			{
			            				$user_data["Id"]                 = $details['Id'];
			            				if($Requested_user['FullName'] != "")
			            				{
			            					$user_data["title"]              = "Paid to";
			            					$user_data["fullname"]           = $Requested_user['FullName'];
			            					$user_data["user_image"]         = site_url().'uploads/user/'.$Requested_user['Profile_Pic'];
			            					$user_data["mobile"]             = $Requested_user['Mobile_No'];
			            				}
			            				$user_data["order_number"]       = $details['Third_Party_Tran_Id'];
			            				$user_data["amount"]             = number_format($details['Amount'],2);
			            				$user_data["charge"]             = number_format($details['Charge'],2);
			            				$user_data['created_at']         = date('d M Y', strtotime($details['Creation_Date_Time']));
			            				if($transaction_status['Status_Name'] == "Reject")
			            					$user_data['transaction_status'] = "Failed";
			            				else
			            					$user_data['transaction_status'] = $transaction_status['Status_Name'];
			            				$user_data['trx_type']           = "Sent Money";
			            				$user_data['tran_type_id']       = $details['Tran_Type_Id'];
			            				$user_data['tran_status_id']       = $details['Tran_Status_Id'];
			            				$user_data['time']               = date('g:i A',strtotime($details['Creation_Date_Time']));
			            				$user_data['msg']                = (!empty($details['Msg'])) ? $details['Msg'] : "";
			            				$user_data['organization_title']  = $org_name;
			            				$transaction_array[]             = $user_data;
			            			} 
			            			if($details['Tran_Type_Id'] == "4") //receive
			            			{
			            				$user_data["Id"]                 = $details['Id'];
			            				if($Requested_user['FullName'] != "")
			            				{
			            					$user_data["title"]          = "Received From ";
			            				}
			            				$user_data["fullname"]           = $Requested_user['FullName'];
			            				$user_data["user_image"]         = site_url().'uploads/user/'.$Requested_user['Profile_Pic'];
			            				$user_data["mobile"]             = "";
			            				$user_data["order_number"]       = $details['Third_Party_Tran_Id'];
			            				$user_data["amount"]             = number_format($details['Amount'],2);
			            				$user_data["charge"]             = number_format($details['Charge'],2);
			            				$user_data['created_at']         = date('d M Y', strtotime($details['Creation_Date_Time']));
			            				if($transaction_status['Status_Name'] == "Reject")
			            					$user_data['transaction_status'] = "Failed";
			            				else
			            					$user_data['transaction_status'] = $transaction_status['Status_Name'];
			            				$user_data['trx_type']           = "Money Received";
			            				$user_data['tran_type_id']       = $details['Tran_Type_Id'];
			            				$user_data['tran_status_id']      = $details['Tran_Status_Id'];
			            				$user_data['time']               = date('g:i A',strtotime($details['Creation_Date_Time']));
			            				$user_data['msg']                = (!empty($details['Msg'])) ? $details['Msg'] : "";
			            				$user_data['organization_title']  = $org_name;
			            				$transaction_array[]             = $user_data;
			            			}	
			            		}	
							}
						$org_detail = $this->dynamic_model->getdatafromtable('organization_details',array('User_Id'=>$loguser['Id'],'is_deleted'=>0));
            			if(!empty($user_role_id== 3)){
	            			  if(!empty($org_detail)){	
	            				$is_organization='0';
	            			}else{
	            				$is_organization='1';
	            			}
            			}else{
            				$is_organization='0';
            			}
						$arg['status'] = 1;
						$arg['error_code']   = SUCCESS_CODE;
			            $arg['error_line']= __line__;
					 	$arg['data']['user_id']             = $loguser['Id'];
					    //$arg['data']['first_name']        = $loguser['FirstName'];
					    //$arg['data']['last_name']         = $loguser['LastName'];
					    $arg['data']['full_name']           = $loguser['FullName'];
					    $arg['data']['email']               = $loguser['Email'];
					    $arg['data']['notification_status'] = $loguser['Notification_Status'];
					    $arg['data']['roles']            	= $loguser_roles['Role_Id'];
					    $arg['data']['profile_image']    	= site_url().'uploads/user/'.$loguser['Profile_Pic'];
					    $arg['data']['wallet_amount']    	= number_format($loguser['Current_Wallet_Balance'],2);
					    //$arg['data']['paid_transaction'] 	= (string)($paid_transaction);
					    $arg['data']['unread_count']     	= (string)($unread_count);
					    $arg['data']['advertisement_details'] = $finaldata;
					    $arg['data']['recent_transactions'] = $transaction_array;
					     $arg['data']['is_organization'] = $is_organization;
					}
					else
					{
						$arg['status']  = 0;
						$arg['error_code']   = ERROR_FAILED_CODE;
						$arg['error_line']= __line__;
						$arg['message'] = $this->lang->line('record_not_found');
					}
				}
			}
		}
		echo json_encode($arg);
	}
	//Used function to get profile details(sender or receiver)
	public function generate_qrcode($data)
{
    // Convert the array to a JSON string
    $json_data = json_encode($data);
    
    // Set file path using a shorter hash function
    $hash_data = md5($json_data);
    $save_name = $hash_data . '.png';

    // QR Code File Directory Initialize
    $dir = 'assets/media/qrcode/';
    if (!file_exists($dir)) {
        mkdir($dir, 0775, true);
    }

    $file_path = $dir . $save_name;

    // Create QR code
    $writer = new PngWriter();
    $qrCode = QrCode::create($json_data)
        ->setEncoding(new Encoding('UTF-8'))
        ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
        ->setSize(300)
        ->setMargin(10)
        ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
        ->setForegroundColor(new Color(0, 0, 0))
        ->setBackgroundColor(new Color(255, 255, 255));

    $result = $writer->write($qrCode);

    // Save QR code to file
    $result->saveToFile(FCPATH . $file_path);

    // Return Data
    $return = array(
        'content' => $data,
        'file'    => $file_path
    );
    return $return;
}

public function get_profile()
{
    $arg  = array();
    // Check user is active or not
    $user_status = checkUserStatus();
    if (@$user_status['status'] != 0) {
        $arg = $user_status;
    } else {
        $version_result = version_check_helper();
        if ($version_result['status'] != 1) {
            $arg = $version_result;
        } else {
            $result = check_authorization();
            if ($result != 'true') {
                $arg['status']  = 101;
                $arg['error_code']   = 461;
                $arg['error_line']   = __LINE__;
                $arg['message'] = $result;
            } else {
                $auth_token = $this->input->get_request_header('Authorization');
                $user_token = json_decode(base64_decode($auth_token));

                $usid    = $user_token->userid;
                $loguser = $this->dynamic_model->get_user_by_id($usid);
                if ($loguser) {
                    $loguser_roles = $this->dynamic_model->get_row('user_in_roles', array('User_Id'=> $loguser['Id']));
                    $loguser_documents = $this->dynamic_model->get_row('users_documents', array('User_Id'=> $loguser['Id'], 'Document_Type_Id'=>2));
                    $mob_verified = (!empty($loguser['Is_Mobile_Verified'])) ? $loguser['Is_Mobile_Verified'] : 0;
                    $email_verified = (!empty($loguser['Is_Email_Verified'])) ? $loguser['Is_Email_Verified'] : 0;

                    if ($email_verified == 0) {
                        $redirect_to_verify = 2; // here redirect_to verify = 2 means email is not verified
                    } elseif ($mob_verified == 0) {
                        $redirect_to_verify = 1; // here redirect_to verify = 1 means mobile is not verified
                    } else {
                        $redirect_to_verify ='0';
                    }

                    // Generate QR Code
                    $data = [
                        'user_id'       => $loguser['Id'],
                        'phone_number'  => $loguser['Mobile_No'],
                        'name'          => name_format($loguser['FullName'])
                    ];
                    $qrCodeData = $this->generate_qrcode($data);
                    $qrCodeFilePath = $qrCodeData['file'];

                    $arg['status'] = 1;
                    $arg['error_code']   = SUCCESS_CODE;
                    $arg['error_line']   = __LINE__;
                    $arg['data']['user_id']                = $loguser['Id'];
                    $arg['data']['Authorization']          = $auth_token;
                    $arg['data']['full_name']              = name_format($loguser['FullName']);
                    $arg['data']['first_name']             = name_format($loguser['FirstName']);
                    $arg['data']['last_name']              = name_format($loguser['LastName']);
                    $arg['data']['email']                  = $loguser['Email'];
                    $arg['data']['mobile']                 = $loguser['Mobile_No'];
                    $arg['data']['roles']                  = $loguser_roles['Role_Id'];
                    $arg['data']['gender']                 = (!empty($loguser['Gender'])) ? $loguser['Gender'] : "";
                    $arg['data']['age']                    = (!empty($loguser['Age'])) ? $loguser['Age'] : "";
                    $arg['data']['address']                = (!empty($loguser['Address'])) ? $loguser['Address'] : "";
                    $arg['data']['subscription_plan_status']  = '1';
                    $arg['data']['referral_code']          = $loguser['Referral_Code'];
                    $arg['data']['total_referral_points']  = $loguser['Total_Referral_Points'];
                    $arg['data']['auth_provider']          = $loguser['Auth_Provider'];
                    $arg['data']['notification_status']    = $loguser['Notification_Status'];
                    $arg['data']['fingerprint_status']     = $loguser['Fingerprint_Status'];
                    $arg['data']['redirect_to_verify']     = "$redirect_to_verify";
                    $arg['data']['wallet_amount']          = $loguser['Current_Wallet_Balance'];
                    $etippers_id = (!empty($loguser['etippers_id'])) ? $loguser['etippers_id'] : '';

                    $arg['data']['etippers_id']            = $etippers_id;

                    $arg['data']['qrcode_image']        = site_url() . $qrCodeFilePath;

                    $arg['data']['profile_image']      = site_url() . 'uploads/user/' . $loguser['Profile_Pic'];
                    $arg['data']['verification_image'] = site_url() . 'uploads/identification/' . $loguser_documents['Document_Image_Name'];
                } else {
                    $arg['status']  = 0;
                    $arg['error_code']   = ERROR_FAILED_CODE;
                    $arg['error_line']   = __LINE__;
                    $arg['message'] = $this->lang->line('record_not_found');
                }
            }
        }
    }
    echo json_encode($arg);
}

	//Function used for update profile
	public function profile_update()
	{
		$arg = array();
		//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error_code']   = 461;
					$arg['error_line']= __line__;
					$arg['message'] = $result;
				}
				else
				{
					$auth_token = $this->input->get_request_header('Authorization');
				    $user_token = json_decode(base64_decode($auth_token));
					$usid = $user_token->userid;
					$loguser = $this->dynamic_model->get_user_by_id($usid);
					$email  = $this->input->post('email');
					$mobile = $this->input->post('mobile');

					$this->form_validation->set_rules('first_name', 'Name', 'required|trim', array( 'required' => $this->lang->line('firstname')));
		            $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim', array( 'required' => $this->lang->line('lastname')));	
		            if(!empty($email && $email != $loguser['Email'])){
					$this->form_validation->set_rules('email','Email','required|valid_email|is_unique[users.Email]' , array('required' => $this->lang->line('email_required'),'valid_email' => $this->lang->line('email_valid'),'is_unique' => $this->lang->line('email_unique')
		              ));
				    }
					if(!empty($mobile && $mobile != $loguser['Mobile_No'])){
		           $this->form_validation->set_rules('mobile', 'Mobile','required|min_length[8]|max_length[12]|numeric|is_unique[users.Mobile_No]', array(
					'required' => $this->lang->line('mobile_required'),
					'min_length' => $this->lang->line('mobile_min_length'),
					'max_length' => $this->lang->line('mobile_max_length'),
					'numeric' => $this->lang->line('mobile_numeric'),
					'is_unique' => $this->lang->line('mobile_unique')
			        ));
		            }
					$this->form_validation->set_rules('age', 'Age', 'required', array( 'required' => $this->lang->line('age_required')));
					// $this->form_validation->set_rules('gender', 'Gender', 'required', array( 'required' => $this->lang->line('gender_required')));
					$this->form_validation->set_rules('address', 'Address', 'required', array( 'required' => $this->lang->line('address')));
				
					if ($this->form_validation->run() == FALSE)
					{
					  	$arg['status']  = 0;
					  	$arg['error_code']   = ERROR_FAILED_CODE;
					  	$arg['error_line']= __line__;
					 	$arg['message'] = get_form_error($this->form_validation->error_array());
					}
					else
					{
						$userdata = array();
						$userroledata = array();
						if(!empty($_FILES['profile_image']['name']))
						{
							$profile_image = $this->dynamic_model->fileupload('profile_image','uploads/user');
							$userdata['Profile_Pic'] = $profile_image;
						}
						$location = $this->input->post('location');
						$age = $this->input->post('age');
						$userdata['FirstName']      	= $this->input->post('first_name');
						$userdata['LastName']           = $this->input->post('last_name');
						$userdata['FullName']           = $this->input->post('first_name').' '.$this->input->post('last_name');
						if(!empty($email)){
						 $userdata['Email']             = $email;
						}
						if(!empty($mobile)){
						 $userdata['Mobile_No']         = $mobile;
						}
						$userdata['Address']            = $this->input->post('address');
						if(!empty($age)){
						 $userdata['Age']               = $age;
						}
						$userdata['Gender']             = $this->input->post('gender');
						$where = array(
					    	'Id' => $usid
					    );					    
						$updatedata = $this->dynamic_model->updateRowWhere("users",$where,$userdata);
						// for user documents
						$wheres = array(
					    	'User_Id' => $usid
					    ); 
						// if(!empty($_FILES['varification_image']['name']))
						// {
						// 	$id_image_front = $this->dynamic_model->fileupload('varification_image', 'uploads/identification');
						// 	$userroledata['Document_Image_Name'] = $id_image_front;
						// 	$userroledata['Is_Verified'] =0;
						// 	$updateroledata = $this->dynamic_model->updateRowWhere("Users_Documents",$wheres,$userroledata);
						// }
						$user_data = $this->dynamic_model->getdatafromtable('users', array('Id'=>$usid));
						//for user roles
					    $loguser_roles = $this->dynamic_model->get_row('user_in_roles',array('User_Id'=> $usid));

						$loguser_documents = $this->dynamic_model->get_row('users_documents',array('User_Id'=> $usid,'Document_Type_Id'=>2));				
						$mob_verified = (!empty($user_data[0]['Is_Mobile_Verified']))? $user_data[0]['Is_Mobile_Verified'] : 0;
						$email_verified=  (!empty($user_data[0]['Is_Email_Verified']))? $user_data[0]['Is_Email_Verified'] : 0;
   
						if(!empty($loguser_roles['Role_Id']==3)){
						if(!empty($loguser_roles['QR_Code_Img_Path'])){
							$qrcode_image  = site_url().'uploads/qrcodes/'.$loguser_roles['QR_Code_Img_Path'];
						}else{
							$qrcode_image  ='';
						 }
						 }else{
						 	$qrcode_image  ='';
						 }
						 $profile_image      = site_url().'uploads/user/'.$user_data[0]['Profile_Pic'];
					     $verification_image = site_url().'uploads/identification/'.$loguser_documents['Document_Image_Name'];
						 if($email_verified == 0)
						 {
						  $redirect_to_verify = 2; // here redirect_to verify = 2 means email is not verified
						}elseif($mob_verified == 0){
						 $redirect_to_verify = 1;// here redirect_to verify = 1 means mobile is not verified
						}else{
						  $redirect_to_verify ='0'; 
					    } 
						$fname       = name_format($user_data[0]['FirstName']);
						$lname       = name_format($user_data[0]['LastName']);
						$fullname    = name_format($user_data[0]['FullName']);
						$gender      = (!empty($user_data[0]['Gender'])) ? $user_data[0]['Gender'] :'';
						$Age         = (!empty($user_data[0]['Age'])) ? $user_data[0]['Age'] :'';
						$address     = (!empty($user_data[0]['Address'])) ? $user_data[0]['Address'] :'';
						$etippers_id = (!empty($user_data[0]['etippers_id'])) ? $user_data[0]['etippers_id'] :'';
						if($updatedata)
						{
							$arg['status']  = 1;
							$arg['error_code']   = SUCCESS_CODE;
			                $arg['error_line']= __line__;
			 		  		$arg['message'] = $this->lang->line('profile_update');

			 		  		$userinfo = array('user_id' => $usid,'Authorization' => $auth_token,'roles'=>$loguser_roles['Role_Id'],'first_name' => $fname, 'last_name' =>$lname, 'full_name' =>$fullname,'wallet_amount'=>$user_data[0]['Current_Wallet_Balance'],'mobile'=>$user_data[0]['Mobile_No'],'email'=>$user_data[0]['Email'],'qrcode_image'=>$qrcode_image,'redirect_to_verify'=>"$redirect_to_verify",'notification_status'=>$user_data[0]['Notification_Status'],'profile_image'=>$profile_image,'fingerprint_status'=>$user_data[0]['Fingerprint_Status'],'auth_provider'=>$user_data[0]['Auth_Provider'],'subscription_plan_status' =>'1','referral_code' =>$user_data[0]['Referral_Code'],'total_referral_points' =>$user_data[0]['Total_Referral_Points'],'verification_image' =>$verification_image,'gender' =>$gender,'age' =>$Age,'address' =>$address,'etippers_id' =>$etippers_id);
			 		  		$arg['data'] = $userinfo; 
			 		  	}
			 		  	else
			 		  	{
			 		  		$arg['status']  = 0;
			 		  		$arg['error_code']   = ERROR_FAILED_CODE;
			 		  		$arg['error_line']= __line__;
			 		  		$arg['message'] = $this->lang->line('profile_notupdate');
			 		  	}
					}
				}
			}
		}
		echo json_encode($arg);
	}    




    //Function used for add new organization
	public function add_new_organization()
	{
		$arg = array();
		//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error_code']   = 461;
					$arg['error_line']= __line__;
					$arg['message'] = $result;
				}
				else
				{
					$_POST = $this->input->post();

					if($_POST == [])
					{
						$_POST = json_decode(file_get_contents("php://input"), true);
					}
					// $_POST = json_decode(file_get_contents("php://input"),true);
					if($_POST)
					{
						$oid = $this->input->post('organisation_id');

						$this->form_validation->set_rules('organization_name', 'Organization Name', 'required|trim', array( 'required' => $this->lang->line('organization_name')));
			            $this->form_validation->set_rules('organization_type', ' Organization Type', 'required|trim', array( 'required' => $this->lang->line('organization_type')));	
			            $this->form_validation->set_rules('address', ' Address', 'required|trim', array( 'required' => $this->lang->line('organization_address')));	
			            //$this->form_validation->set_rules('organization_phone', 'Organization Phone','required|trim', array( 'required' => $this->lang->line('organization_phone')));	
			            if(empty($oid)){
							$this->form_validation->set_rules('phone', 'Mobile','required|min_length[8]|max_length[12]|numeric|is_unique[organization_details.Organization_Phone]', array(
							'required' => $this->lang->line('organization_phone'),
							'min_length' => $this->lang->line('mobile_min_length'),
							'max_length' => $this->lang->line('mobile_max_length'),
							'numeric' => $this->lang->line('mobile_numeric'),
							'is_unique' => $this->lang->line('mobile_unique')
					        ));
					    }
					    else{
					    	$this->form_validation->set_rules('phone', 'Mobile','required|min_length[8]|max_length[12]|numeric', array(
							'required' => $this->lang->line('organization_phone'),
							'min_length' => $this->lang->line('mobile_min_length'),
							'max_length' => $this->lang->line('mobile_max_length'),
							'numeric' => $this->lang->line('mobile_numeric'),
					        ));
					    }

						if ($this->form_validation->run() == FALSE)
						{
						  	$arg['status']  = 0;
						  	$arg['error_code']   = ERROR_FAILED_CODE;
						  	$arg['error_line']= __line__;
						 	$arg['message'] = get_form_error($this->form_validation->error_array());
						}
						else
						{
							$auth_token = $this->input->get_request_header('Authorization');
						    $user_token = json_decode(base64_decode($auth_token));
							$usid = $user_token->userid;
							$loguser = $this->dynamic_model->get_user_by_id($usid);
							$roles = $this->dynamic_model->get_role_id($usid);
							if($roles['Role_Id'] ==3){
								$userdata = array();
								
								$wheres = array(
							    	'User_Id' => $usid
							    ); 
								
								$org_data = $this->dynamic_model->getdatafromtable('organization_details', array('User_Id'=>$usid,'is_deleted'=>0));
								$organization_name=$this->input->post('organization_name');
								$organization_type=$this->input->post('organization_type');
								$organization_phone=$this->input->post('phone');
								$organization_address=$this->input->post('address');
								 //echo count($org_data);die;
								 if($org_data==null || count($org_data)< 3){

								 	
								 	$check_dublication_org_name = $this->dynamic_model->getdatafromtable('organization_details', array('User_Id'=>$usid,'Organization_Name'=>$organization_name));

								 	//if(empty($check_dublication_org_name )){

										$userdata['User_Id']               = $usid;
										$userdata['Organization_Name']     = $organization_name;
										$userdata['Organization_Type']     = $organization_type;
										$userdata['Organization_Phone']    = $organization_phone;
										$userdata['Organization_Address']  = $organization_address;

										if(empty($oid)){
											$insertdata = $this->dynamic_model->insertdata("organization_details",$userdata);
											$message = $this->lang->line('organization_add');
										}	    
										else{
											$where     = array("Id" => $oid);
				                			$insertdata = update_data("organization_details", $userdata, $where);
				                			$message = $this->lang->line('organization_update');
										}
										if($insertdata)
										{
											$arg['status']  = 1;
											$arg['error_code']   = SUCCESS_CODE;
			                                $arg['error_line']= __line__;
							 		  		$arg['message'] = $message;

							 		  		//$userinfo     = array('Organization_Name'=>$loguser[0]['Organization_Name'],'organization_type' => $user_data[0]['Organization_Type'], 'organization_phone' => $user_data[0]['Organization_Phone'], 'organization_address' => $user_data[0]['Organization_Address']);
							 		  		$arg['data'] = [];
							 		  	}
							 		  	else
							 		  	{
							 		  		$arg['status']  = 0;
							 		  		$arg['error_code']   = ERROR_FAILED_CODE;
							 		  		$arg['error_line']= __line__;
							 		  		$arg['message'] = $this->lang->line('organization_not_update');
							 		  	}
							 		// }
						 		 //  	else
						 		 //  	{
						 		 //  		$arg['status']  = 0;
						 		 //  		$arg['error']   = ERROR_FAILED_CODE;
						 		 //  		$arg['message'] = "Organization already exists";
						 		 //  	}
						 		}
					 		  	else
					 		  	{
					 		  		$arg['status']  = 0;
					 		  		$arg['error_code']   = ERROR_FAILED_CODE;
					 		  		$arg['error_line']= __line__;
					 		  		$arg['message'] = "You Cannot add more than 3 organization";
					 		  	}



				 		    }else{
				 		  	    $arg['status']  = 0;
				 		  		$arg['error_code']   = ERROR_FAILED_CODE;
				 		  		$arg['error_line']= __line__;
				 		  		$arg['message'] = "Not Authorized for add organization";
				 		    }
						}
				    }
				}
			}
		}
		echo json_encode($arg);
	}

	public function deleteOrganisation()
    {
    	$arg = array();
    	//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {
        	//check version is updated or not
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error']   = 461;
					$arg['message'] = $result;
				}
				else
				{
			    	$_POST = json_decode(file_get_contents("php://input"), true);
					if($_POST)
					{
						$arg = array();
						$this->form_validation->set_rules('organisation_id', 'Organisation Id', 'required|numeric');
						if ($this->form_validation->run() == FALSE)
						{
						  	$arg['status']  = 0;
						  	$arg['error']   = ERROR_FAILED_CODE;
						  	$arg['message'] = get_form_error($this->form_validation->error_array());
						}
						else
						{
							$userid   = getuserid();
							$loguser  = $this->dynamic_model->get_user_by_id($userid);
							$organisation_id   = $this->input->post('organisation_id');

							$organisation_exist = $this->dynamic_model->get_row('organization_details', array('Id'=>$organisation_id,'User_Id' =>$userid));

							if($organisation_exist)
							{
								$update['is_deleted'] = 1 ;
				                $where  = "Id =". $organisation_id;
								$delete = $this->dynamic_model->updateRowWhere('organization_details', $where, $update );
								//echo $this->db->last_query();
				                //$delete = $this->dynamic_model->deletedata('organization_details', $where);

				                if($delete)
				                {
					                $arg['status']  = 1;
					                $arg['error_code']   = SUCCESS_CODE;
			            			$arg['error_line']= __line__;
							  		$arg['message'] = $this->lang->line('delete_success');
							  	}
							  	else
							  	{
							  		$arg['status']  = 0;
							  		$arg['error_code']   = ERROR_FAILED_CODE;
			            			$arg['error_line']= __line__;
							  		$arg['message'] = $this->lang->line('delete_error');
							  	}
						  	}
						  	else
						  	{
						  		$arg['status']  = 0;
						  		$arg['error_code']   = ERROR_FAILED_CODE;
						  		$arg['error_line']   = __LINE__;
						  		$arg['message'] = $this->lang->line('record_not_found');
						  	}
						}
			    	}
			    }
			}
		}
	    echo json_encode($arg);
	}


	//Used function to get get_organization_details
	public function get_organization_list()
	{
		$arg    = array();
		//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error_code']   = 461;
					$arg['error_line']= __line__;
					$arg['message'] = $result;
				}
				else
				{
					$_POST = json_decode(file_get_contents("php://input"), true);
					if($_POST){
						$receiver_id = $this->input->post('receiver_id') ;
						//$for_receiver = $this->input->post('for_receiver') ;
					}
					$organization_arr=array();
					$auth_token = $this->input->get_request_header('Authorization');
			    	$user_token = json_decode(base64_decode($auth_token));
					$usid    = $user_token->userid;
					$loguser = $this->dynamic_model->get_user_by_id($usid);
					//$organization_data = $this->dynamic_model->getdatafromtable('organization_details', array('User_Id'=> $loguser['Id']));
					if(!empty($receiver_id)  ){
						$search_id = $receiver_id;
					}
					else{
						$search_id = $loguser['Id'];
					}
					
					
					$organization_data = $this->dynamic_model->getTwoTableData('od.*, ot.name as Organization_Type_Name','organization_details as od', 'organisation_types as ot', 'od.Organization_Type = ot.id' , array('User_Id'=> $search_id, 'is_deleted'=> '0'  ));
					
					if(!empty($organization_data))
					{
					 	foreach ($organization_data as $value) {
						 	$organization['id']    = $value['Id'];
						 	$organization['organization_name']    = $value['Organization_Name'];
						    //$organization['organization_type']  = $value['Organization_Type'];
						    $organization['phone']   = $value['Organization_Phone'];
						    $organization['organization_type'] = $value['Organization_Type'];
						    $organization['address'] = $value['Organization_Address'];
						    $organization['address'] = $value['Organization_Address'];
						    $organization['organization_type_name'] = $value['Organization_Type_Name'];
						    $organization['creation_date_time'] = $value['Creation_Date_Time'];
						    $organization_arr[]=$organization;
					  
					   }
					    $arg['status'] = 1;
					    $arg['error_code']   = SUCCESS_CODE;
			            $arg['error_line']= __line__;
					    $arg['message']="Organization Details";
					    $arg['data']=$organization_arr;
					}
					else
					{
						$arg['status']  = 0;
						$arg['error_code']   = ERROR_FAILED_CODE;
						$arg['error_line']= __line__;
						$arg['message'] = $this->lang->line('record_not_found');
					}
				}
			}
		}
		echo json_encode($arg);
	}

	public function get_organization_types()
	{
		$arg    = array();
		//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				/*if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error_code']   = 461;
					$arg['error_line']= __line__;
					$arg['message'] = $result;
				}
				else
				{*/
					$organization_arr=array();
					$auth_token = $this->input->get_request_header('Authorization');
			    	$user_token = json_decode(base64_decode($auth_token));
					$usid    = $user_token->userid;
					$loguser = $this->dynamic_model->get_user_by_id($usid);
					$organization_data = $this->dynamic_model->getdatafromtable('organisation_types' );
					if(!empty($organization_data))
					{
					 	/*foreach ($organization_data as $value) {
						 	$organization['organization_name']    = $value['Organization_Name'];
						    //$organization['organization_type']  = $value['Organization_Type'];
						    $organization['phone']   = $value['Organization_Phone'];
						    $organization['address'] = $value['Organization_Address'];
						    $organization_arr[]=$organization;
					  
					   }*/
					    $arg['status'] = 1;
					    $arg['error_code']   = SUCCESS_CODE;
			            $arg['error_line']= __line__;
					    $arg['message']="Organization Types";
					    $arg['data']=$organization_data;
					}
					else
					{
						$arg['status']  = 0;
						$arg['error_code']   = ERROR_FAILED_CODE;
						$arg['error_line']= __line__;
						$arg['message'] = $this->lang->line('record_not_found');
					}
				//}
			}
		}
		echo json_encode($arg);
	}

	//Function used for user is exist or not
	public function userExist()
	{
		$arg    = array();
		//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {
        	//check version is updated or not
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error_code']   = 461;
					$arg['error_line']= __line__;
					$arg['message'] = $result;
				}
				else
				{
					$_POST = $this->input->post();

					if($_POST == [])
					{
						$_POST = json_decode(file_get_contents("php://input"), true);
					}
					if($_POST)
					{
						$this->form_validation->set_rules('emailMobile', '', 'required');
						if ($this->form_validation->run() == FALSE)
						{
						  	$arg['status']  = 0;
						  	$arg['error_code']   = ERROR_FAILED_CODE;
						  	$arg['error_line']= __line__;
						  	$arg['message'] =  get_form_error($this->form_validation->error_array());
						}
						else
						{
							$mobileEmail = $this->input->post('emailMobile');
							$auth_token  = $this->input->get_request_header('Authorization');
					    	$user_token  = json_decode(base64_decode($auth_token));

							$usid        = $user_token->userid;
							$loguser     = $this->dynamic_model->get_user_by_id($usid);
					        $usermail    = isset($loguser['Email']) ? $loguser['Email'] : '';
					        $usermobile  = $loguser['Mobile_No'];

					        if($usermobile == $mobileEmail OR $usermail == $mobileEmail )
					        {
					        	$arg['status']  = 0;
					        	$arg['error_code']   = ERROR_FAILED_CODE;
					        	$arg['error_line']= __line__;
							  	$arg['message'] = $this->lang->line('not_send_same_mobile');
					        }
					        else
					        {
						        if($mobileEmail != "")
						        {
									$userDetail = $this->dynamic_model->check_userdetails($mobileEmail);

									if($userDetail)
									{
										$cond = array(
												'User_Id' => $userDetail['Id']
											);
										$userrole = getdatafromtable('user_in_roles', $cond);
										$profilepic       = isset($userDetail['Profile_Pic']) ? $userDetail['Profile_Pic'] : 'default.png';
						            	$profile_image    = site_url().'uploads/user/'. $profilepic;
						            	
						            	$userinfo = array('user_id'=>$userDetail['Id'],'firstname' => $userDetail['FirstName'], 'lastname' => $userDetail['LastName'],'fullname'=>$userDetail['FullName'],'profile_image'=>$profile_image,'roles'=>$userrole[0]['Role_Id'],'mobile'=>$userDetail['Mobile_No'],'email'=>$userDetail['Email'],'gender'=>$userDetail['Gender']);

					            		$arg['status']  = 1;
					            		$arg['error_code']   = SUCCESS_CODE;
			                            $arg['error_line']= __line__;
						  				$arg['data']    = $userinfo;
									}
									else
									{
										$arg['status']  = 0;
										$arg['error_code']   = ERROR_FAILED_CODE;
										$arg['error_line']= __line__;
								  		$arg['message'] = $this->lang->line('no_user_found');
									}
						        }
						        else
						        {
						        	$arg['status']  = 0;
						        	$arg['error_code']   = ERROR_FAILED_CODE;
						        	$arg['error_line']= __line__;
								  	$arg['message'] = $this->lang->line('invalid_detail');
						        }
						    }
					    }
				    }
				}
			}
		}
		echo json_encode($arg);
    }

	//Function used for Get Qrcode Info 
    public function get_qrcode_info()
    {
    	$arg = array();
    	//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {
        	//check version is updated or not
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error_code']   = 461;
					$arg['error_line']= __line__;
					$arg['message'] = $result;
				}
				else
				{
			    	$_POST = $this->input->post();

					if($_POST == [])
					{
						$_POST = json_decode(file_get_contents("php://input"), true);
					}
					if($_POST)
					{
						$arg     = array();
						$this->form_validation->set_rules('qrcode', 'qrcode', 'required');
						if ($this->form_validation->run() == FALSE)
						{
						  	$arg['status']  = 0;
						  	$arg['error_code']   = ERROR_FAILED_CODE;
						  	$arg['error_line']= __line__;
						  	$arg['message'] = get_form_error($this->form_validation->error_array());
						}
						else
						{
							$auth_token = $this->input->get_request_header('Authorization');
					    	$user_token = json_decode(base64_decode($auth_token));
							$usid    = $user_token->userid;
							$loguser = $this->dynamic_model->get_user_by_id($usid);
							$qrcode  = $this->input->post('qrcode');
							$user_qrcode  = $this->dynamic_model->get_row('user_in_roles',array('QR_Code'=> $qrcode));
							if(!empty($user_qrcode))	
							{
								
								$user_details = $this->dynamic_model->get_row('users',array('Id'=> $user_qrcode['User_Id']));
								//print_r($user_details); exit();
								$user_role = $this->dynamic_model->get_role_id($user_qrcode['User_Id']);
								$profile_image    = site_url().'uploads/user/'. $user_details['Profile_Pic'];
								$qr_image = '' ;
								if($user_qrcode['QR_Code_Img_Path'] != ''){
									$qr_image = site_url().'uploads/qrcodes/'.$user_qrcode['QR_Code_Img_Path'] ;
								}
                                $org_data = $this->dynamic_model->getdatafromtable('organization_details',array('User_Id'=>$user_qrcode['User_Id'],'is_deleted'=>0));
                                if(!empty($org_data)){
                                	 $org_status='1';
                                }else{
                                     $org_status='0';
                                }
								/*$userdata = array(
										    	'qrcode_image' => site_url().'uploads/qrcodes/'.$user_qrcode['QR_Code_Img_Path'],
										    	'user_id' => $user_qrcode['User_Id'],
										    	'fullname' => $user_details['FullName'],
										    	'email' => $user_details['Email'],
										    	'mobile' => $user_details['Mobile_No'] ,
										    	'role_id' => $user_role['Role_Id'] ,
										    );*/
								$userdata = array('user_id'=>$user_qrcode['User_Id'],
									'firstname' => $user_details['FirstName'],
									'lastname' => $user_details['LastName'],
									'fullname'=>$user_details['FullName'],
									'profile_image'=>$profile_image,
									'role_id'=>$user_qrcode['Role_Id'],
									'mobile'=>$user_details['Mobile_No'],
									'email'=>$user_details['Email'],
									'gender'=>$user_details['Gender'],
									'qrcode_image' => $qr_image ,
									'etippers_id'=> $user_details['etippers_id'],
									'org_status'=> $org_status
								);

								$arg['status'] = 1;
								$arg['error_code']   = SUCCESS_CODE;
			                    $arg['error_line']= __line__;
								$arg['data']   = $userdata;
					        }
					        else
					        {
					        	$arg['status']  = 0;
					        	$arg['error_code']   = ERROR_FAILED_CODE;
					        	$arg['error_line']= __line__;
								$arg['message'] = $this->lang->line('record_not_found');
					        }
					    }
			    	}
			    }
			}
		}
	    echo json_encode($arg);
	}

	//Function used for Get Card Details List 
    public function getCardDetails()
    {
    	$arg = array();
    	//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {
        	//check version is updated or not
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error']   = 461;
					$arg['message'] = $result;
				}
				else
				{
					$auth_token = $this->input->get_request_header('Authorization');
			    	$user_token = json_decode(base64_decode($auth_token));

					$userid      = $user_token->userid;
					$loguser     = $this->dynamic_model->get_user_by_id($userid);
					$card_detail = $this->dynamic_model->check_card_details($userid);
					if($card_detail)
					{
						$imagePath   = site_url()."assets/images/card.jpeg";
						$user_data   = array();
						$card_array  = array();
						foreach ($card_detail as $card)
						{
							$user_data["id"]             = $card['Id'];
							$user_data["userid"]         = $card['User_Id'];
							$user_data["card_number"]    = substr_replace($card['Card_Bank_No'], 'XXXX', 0,4);
							$card_expiry                 = explode('-', $card['Expiry_Month_Year']);
							$user_data["expiry_month"]   = $card_expiry[0];
							$user_data["expiry_year"]    = $card_expiry[1];
							$user_data["bank_image"]     = $imagePath;
							$user_data["bank_name"]      = "Test Bank";
							if($card['Is_Debit_Card'] == 1)
								$user_data["card_type"] = "Debit Card";
							if($card['Is_Credit_Card'] == 1)
								$user_data["card_type"] = "Credit Card";
							$card_array[]                = $user_data;
						}
						$arg['status']  = 1;
			            $arg['data']    = $card_array;
					}
					else
					{
						$arg['status']  = 0;
						$arg['error']   = ERROR_FAILED_CODE;
			            $arg['message'] = $this->lang->line('record_not_found');
					}
			    }
			}
		}
	    echo json_encode($arg);
	}

	//Function used for Delete Bank and Card Detail
    public function deleteBankCardDetail()
    {
    	$arg = array();
    	//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {
        	//check version is updated or not
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error']   = 461;
					$arg['message'] = $result;
				}
				else
				{
			    	$_POST = $this->input->post();

					if($_POST == [])
					{
						$_POST = json_decode(file_get_contents("php://input"), true);
					}
					if($_POST)
					{
						$arg = array();
						$this->form_validation->set_rules('bc_id', 'Bank or Card id required', 'required|numeric');
						if ($this->form_validation->run() == FALSE)
						{
						  	$arg['status']  = 0;
						  	$arg['error']   = ERROR_FAILED_CODE;
						  	$arg['message'] = get_form_error($this->form_validation->error_array());
						}
						else
						{
							$userid   = getuserid();
							$loguser  = $this->dynamic_model->get_user_by_id($userid);
							$bc_id   = $this->input->post('bc_id');
							$type     = $this->input->post('type');

							$card_Exist = $this->dynamic_model->get_row('user_payment_methods',array('User_Id'=> $userid,'Id'=>$bc_id,'Is_Deleted'=>0));
							if($card_Exist)
							{
								$data1 = array(
											'Is_Deleted' => 1,
											'Last_Updated_By'     =>$userid,
											'Last_Updated_Date_Time' =>date('Y-m-d H:i:s')
										);
				                $where     = array("Id" => $bc_id,"User_Id"=>$userid);
				                $cardUpdate = update_data("user_payment_methods", $data1, $where);

				                if($type == "card")
				                {
					                $arg['status']  = 1;
					                $arg['error_code']   = SUCCESS_CODE;
						  			$arg['error_line']   = __LINE__;
							  		$arg['message'] = $this->lang->line('card_delete_success');
							  	}
							  	else
							  	{
							  		$arg['status']  = 1;
							  		$arg['error_code']   = SUCCESS_CODE;
						  			$arg['error_line']   = __LINE__;
							  		$arg['message'] = $this->lang->line('bank_delete_success');
							  	}
						  	}
						  	else
						  	{
						  		$arg['status']  = 0;
						  		$arg['error_code']   = ERROR_FAILED_CODE;
						  		$arg['error_line']   = __LINE__;
						  		$arg['message'] = $this->lang->line('record_not_found');
						  	}
						}
			    	}
			    }
			}
		}
	    echo json_encode($arg);
	}

	//Function used for Get Bank Details
    public function getBankDetails()
    {
    	$arg = array();
    	//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {
        	//check version is updated or not
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error']   = 461;
					$arg['message'] = $result;
				}
				else
				{
			    	$userid      = getuserid();
					$loguser     = $this->dynamic_model->get_user_by_id($userid);
					$bank_detail = $this->dynamic_model->get_rows('user_payment_methods',array('Is_Bank'=>1,'User_Id'=>$userid,'Is_Deleted'=>0),'*','Id','DESC');
					if($bank_detail)
					{
						$imagePath   = site_url()."assets/images/banking.jpg";
						$user_data   = array();
						$bank_array  = array();
						foreach ($bank_detail as $bank)
						{
							$user_data["id"]          = $bank['Id'];
							$user_data["userid"]      = $bank['User_Id'];
							//$user_data["bank_name"]   = $bank['Wallet_Bank_Name'];
							$user_data["bank_name"]   = $bank['Bank_Name'];
							$user_data["acc_number"]  = 'xxxxxxxxxx'.substr(decode_id($bank['Account_No']),-4);
							$user_data["acc_holder_name"] = $bank['Acc_Holder_Name'];
							$user_data["branch_name"] = $bank['Branch_Name'];
							//$user_data["bank_image"]  = $imagePath;
							$bank_array[]             = $user_data;
						}
						$arg['status']  = 1;
						$arg['error_code']   = SUCCESS_CODE;
						$arg['error_line']   = __LINE__;
			            $arg['data']    = $bank_array;
			            $arg['message'] ='';
					}
					else
					{
						$arg['status']  = 0;
						$arg['error_code']   = ERROR_FAILED_CODE;
						$arg['error_line']   = __LINE__;
						$arg['data']    = array();
			            $arg['message'] = $this->lang->line('record_not_found');
					}
			    }
			}
		}
	    echo json_encode($arg);
	}
	//Function used for Paid Transaction List 
    public function paidTransactionList()
    {
    	//check user is active or not
        $user_status = checkUserStatus();
        if(!empty($user_status))
        {
            echo json_encode($user_status);die;
        }
    	$arg    = array();
		// $result = $this->check_auth();
		$result = check_authorization();
		if($result != 'true')
		{
			$arg['status']  = 101;
			$arg['error']   = 461;
			$arg['message'] = $result;
		}
		else
		{
	    	$usid            = $this->input->get_request_header('userid', true);
			$loguser         = $this->dynamic_model->get_user_by_id($usid);
			$paidTransaction = $this->dynamic_model->get_rows('transactions',array('To_User_Id'=>$usid,'Tran_Status_Id'=>6),'*','Id','DESC');
			if($paidTransaction)
			{
				$user_data     = array();
				$requestArray  = array();
				$total_request = count($paidTransaction);
				foreach ($paidTransaction as $transaction)
				{
					$Requested_user = $this->dynamic_model->get_user_by_id($transaction['From_User_Id']);

					$newDate                     = date('d M Y', strtotime($transaction['Creation_Date_Time']));
					$newTime                     = date('g:i A',strtotime($transaction['Creation_Date_Time']));
					$user_data["id"]             = $transaction['Id'];
					$user_data["amount"]         = $transaction['Amount'];
					$user_data["charge"]         = $transaction['Charge'];
					$user_data["date"]           = $newDate;
					$user_data["time"]           = $newTime;
					$user_data["msg"]            = $transaction['Msg'];
					$user_data["ref_num"]        = $transaction['Third_Party_Tran_Id'];
					if($Requested_user['FullName'])
					{
						$user_data["action_message"] = 'Paid To '.$Requested_user['FullName'];
						$user_data["name"]           = $Requested_user['FirstName'].' '.$Requested_user['LastName'];
					}
					else
					{
						$user_data["action_message"] = 'Paid';
						$user_data["name"]           = '';
					}
					$total_amount                = $total_amount + $transaction['Amount'];
					$requestArray[]              = $user_data;
				}
				$arg['status']        = 1;
	            $arg['data']          = $requestArray;
	            $arg['total_request'] = "$total_request";
	            $arg['total_amount']  = number_format($total_amount, 2);
	            $arg['wallet_amount'] = $loguser['Current_Wallet_Balance'];
			}
			else
			{
				$arg['status']  = 0;
				$arg['error']   = ERROR_FAILED_CODE;
	            $arg['message'] = 'No record found';
			}
	    }
	    echo json_encode($arg);
	}

	//Function used for Get Transaction History 
    public function get_transaction_history()
    {
    	$arg = array();
    	//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {
        	//check version is updated or not
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error_code']   = 461;
					$arg['message'] = $result;
				}
				else
				{
			    	$_POST = $this->input->post();

					if($_POST == [])
					{
						$_POST = json_decode(file_get_contents("php://input"), true);
					}
					if($_POST)
					{
						$this->form_validation->set_rules('page_no', 'Page No', 'required|numeric');
						if ($this->form_validation->run() == FALSE)
						{
						  $arg['status']  = 0;
						  $arg['error_code']   = ERROR_FAILED_CODE;
						  $arg['message'] = get_form_error($this->form_validation->error_array());
						} 
						else
						{
							$userid           = getuserid();
							//$userid           = 68;
							$loguser          = $this->dynamic_model->get_user_by_id($userid);
							$user_role 		= $this->dynamic_model->getdatafromtable('user_in_roles', array('User_Id'=>$userid) , 'Role_Id');
							$user_role_id = $user_role[0]['Role_Id'];
							

							$transaction_type = $this->input->post('transaction_type');
							$filter_type = $this->input->post('filter_type');
							$search_text = array();
							$search_text["To_User_Id"] = $userid ;
							if(!empty($filter_type)){
								if($filter_type == '1'){ // this month
									$search_text["MONTH(Creation_Date_Time)"] = "MONTH(CURRENT_DATE())"; 
									$search_false = false;
								}
								if($filter_type == '2'){ // last month
									$search_text["MONTH(Creation_Date_Time)"] = "MONTH(CURRENT_DATE() - INTERVAL 1 MONTH)";
									$search_false = false;
								}
								if($filter_type == '3'){ // last 6 month
									$search_text["Creation_Date_Time >"] = " DATE_SUB(now(), INTERVAL 6 MONTH)";
									$search_false = false;
								}
								
								if($filter_type == '4'){ // date range
									$start_date = $this->input->post('start_date');
									$end_date 	= $this->input->post('end_date');
									$search_text["Creation_Date_Time >="] = $start_date;
									$search_text["Creation_Date_Time <="] = $end_date;
									$search_false = true;
								}
							}

							$page = $this->input->post('page_no');
							$page = ($page) ? $page : 1;
							if ($page < 1) {
								$page = 1;
							}

							$limit            = config_item('page_data_limit'); 
							$offset           = $limit * ($page - 1);
							if($transaction_type == "")
							{
								//$transaction_data = $this->dynamic_model->get_rows('transactions',array('To_User_Id'=>$userid),'*','Id','DESC');
								$transaction_data = $this->dynamic_model->getdatafromtable_new('transactions', $search_text , '*', $limit, $offset,'Id',$search_false);
								//echo $this->db->last_query();die;
							}
							else
							{
								//$transaction_data = $this->dynamic_model->get_rows('transactions',array('To_User_Id'=>$userid,'Tran_Type_Id'=>$transaction_type),'*','Id','DESC');
								$transaction_data = $this->dynamic_model->getdatafromtable('transactions', array('To_User_Id'=>$userid,'Tran_Type_Id'=>$transaction_type) , '*', $limit, $offset,'Id');
							}
							$transaction_array=array();
							if(!empty($transaction_data))
							{
								$user_data = array();
								foreach($transaction_data as $details)
			            		{
			            			$transaction_status = $this->dynamic_model->get_row('tran_status',array('Id'=> $details['Tran_Status_Id']));

			            			if($details['From_User_Id'])
			            				$Requested_user = $this->dynamic_model->get_user_by_id($details['From_User_Id']);

			            			if(!empty($details['organisation_id'])){
			            				$org_data = $this->dynamic_model->getdatafromtable('organization_details',array('Id'=>$details['organisation_id']),'Organization_Name');
			            				$org_name=(!empty($org_data[0]['Organization_Name'])) ? $org_data[0]['Organization_Name'] : "";
			            			}else{
			            				$org_name='';
			            			}

			            			if($details['Tran_Type_Id'] == "1") //withdraw
			            			{
			            				$user_data["Id"]                 = $details['Id'];
			            				$user_data["title"]              = "Withdraw Money";
			            				$user_data["fullname"]           = "";
			            				$user_data["user_image"]         = "";
			            				$user_data["mobile"]             = "";
			            				$user_data["order_number"]       = $details['Third_Party_Tran_Id'];
			            				$user_data["amount"]             = number_format($details['Amount'],2);
			            				$user_data["charge"]             = number_format($details['Charge'],2);
			            				$user_data['created_at']         = date('d M Y', strtotime($details['Creation_Date_Time']));
			            				if($transaction_status['Status_Name'] == "Reject")
			            					$user_data['transaction_status'] = "Failed";
			            				else
			            					$user_data['transaction_status'] = $transaction_status['Status_Name'];
			            				$user_data['trx_type']           = "Withdraw";
			            				$user_data['tran_type_id']       = $details['Tran_Type_Id'];
			            				$user_data['tran_status_id']       = $details['Tran_Status_Id'];
			            				$user_data['time']               = date('g:i A',strtotime($details['Creation_Date_Time']));
			            				$user_data['msg']                = (!empty($details['Msg'])) ? $details['Msg'] : "";
			            				$user_data['organization_title']  = $org_name;
			            				$transaction_array[]             = $user_data;
			            			}
			            			if($details['Tran_Type_Id'] == "2" && ($user_role_id == '2' || $details['Is_Referral_Point']==1)) //add
			            			{
			            				$user_data["Id"]                 = $details['Id'];
			            				$user_data["title"]              = "Money added";
			            				$user_data["fullname"]           = "";
			            				$user_data["user_image"]         = "";
			            				$user_data["mobile"]             = "";
			            				$user_data["order_number"]       = $details['Third_Party_Tran_Id'];
			            				$user_data["amount"]             = number_format($details['Amount'],2);
			            				$user_data["charge"]             = number_format($details['Charge'],2);
			            				$user_data['created_at']         = date('d M Y', strtotime($details['Creation_Date_Time']));
			            				if($transaction_status['Status_Name'] == "Reject")
			            					$user_data['transaction_status'] = "Failed";
			            				else
			            					$user_data['transaction_status'] = $transaction_status['Status_Name'];
			            				$user_data['trx_type']           = "Deposit";
			            				$user_data['tran_type_id']       = $details['Tran_Type_Id'];
			            				$user_data['tran_status_id']       = $details['Tran_Status_Id'];
			            				$user_data['time']               = date('g:i A',strtotime($details['Creation_Date_Time']));
			            				$user_data['msg']                = (!empty($details['Msg'])) ? $details['Msg'] : "";
			            				$user_data['organization_title']  = $org_name;
			            				$transaction_array[]             = $user_data;
			            			}
			            			if($details['Tran_Type_Id'] == "3" && $user_role_id == '2') //send tip
			            			{
			            				$user_data["Id"]                 = $details['Id'];
			            				if($Requested_user['FullName'] != "")
			            				{
			            					$user_data["title"]              = "Paid to";
			            					$user_data["fullname"]           = $Requested_user['FullName'];
			            					$user_data["user_image"]         = site_url().'uploads/user/'.$Requested_user['Profile_Pic'];
			            					$user_data["mobile"]             = $Requested_user['Mobile_No'];
			            				}
			            				$user_data["order_number"]       = $details['Third_Party_Tran_Id'];
			            				$user_data["amount"]             = number_format($details['Amount'],2);
			            				$user_data["charge"]             = number_format($details['Charge'],2);
			            				$user_data['created_at']         = date('d M Y', strtotime($details['Creation_Date_Time']));
			            				if($transaction_status['Status_Name'] == "Reject")
			            					$user_data['transaction_status'] = "Failed";
			            				else
			            					$user_data['transaction_status'] = $transaction_status['Status_Name'];
			            				$user_data['trx_type']           = "Sent Money";
			            				$user_data['tran_type_id']       = $details['Tran_Type_Id'];
			            				$user_data['tran_status_id']       = $details['Tran_Status_Id'];
			            				$user_data['time']               = date('g:i A',strtotime($details['Creation_Date_Time']));
			            				$user_data['msg']                = (!empty($details['Msg'])) ? $details['Msg'] : "";
			            				$user_data['organization_title']  = $org_name;
			            				$transaction_array[]             = $user_data;
			            			} 
			            			if($details['Tran_Type_Id'] == "4") //receive
			            			{
			            				$user_data["Id"]                 = $details['Id'];
			            				if($Requested_user['FullName'] != "")
			            				{
			            					$user_data["title"]          = "Received From ";
			            				}
			            				$user_data["fullname"]           = $Requested_user['FullName'];
			            				$user_data["user_image"]         = site_url().'uploads/user/'.$Requested_user['Profile_Pic'];
			            				$user_data["mobile"]             = "";
			            				$user_data["order_number"]       = $details['Third_Party_Tran_Id'];
			            				$user_data["amount"]             = number_format($details['Amount'],2);
			            				$user_data["charge"]             = number_format($details['Charge'],2);
			            				$user_data['created_at']         = date('d M Y', strtotime($details['Creation_Date_Time']));
			            				if($transaction_status['Status_Name'] == "Reject")
			            					$user_data['transaction_status'] = "Failed";
			            				else
			            					$user_data['transaction_status'] = $transaction_status['Status_Name'];
			            				$user_data['trx_type']           = "Money Received";
			            				$user_data['tran_type_id']       = $details['Tran_Type_Id'];
			            				$user_data['tran_status_id']      = $details['Tran_Status_Id'];
			            				$user_data['time']               = date('g:i A',strtotime($details['Creation_Date_Time']));
			            				$user_data['msg']                = (!empty($details['Msg'])) ? $details['Msg'] : "";
			            				$user_data['organization_title']  = $org_name;
			            				$transaction_array[]             = $user_data;
			            			}
			            			
			            		}
                                // get referral redeem data
							
							    $total_redeem_points= $this->dynamic_model->getdatafromtable('transactions', array('To_User_Id'=>$userid,'Tran_Type_Id'=>2,'Is_Referral_Point'=>1),'SUM(Redeem_Referral_Point) as total_redeem_points');
							    $total_redeem_point=(!empty($total_redeem_points[0]['total_redeem_points']))? $total_redeem_points[0]['total_redeem_points'] : "0" ;
								$referral_data = $this->dynamic_model->get_row('refferal_points_settings',[]);
								$referral_points_per_referral_amount=$referral_data['Refferal_Points'];
								$refferal_Amount=$referral_data['Refferal_Amount'];

								$arg['status']         = 1;
								$arg['error_code']   = ERROR_FAILED_CODE;
								$arg['error_line']= __line__;
								$arg['data']           = $transaction_array;
								$arg['wallet_amount']  = number_format($loguser['Current_Wallet_Balance'],2);
								$arg['total_referral_points']= $loguser['Total_Referral_Points'];
								$arg['total_redeem_points']  = "$total_redeem_point";
								$arg['referral_points_per_referral_amount']  = "$referral_points_per_referral_amount";
								$arg['refferal_Amount']  = "$refferal_Amount";
								$arg['message']        = $this->lang->line('transaction_history_list');
							}
							else
							{
								$arg['status']  = 0;
								$arg['error_code']   = ERROR_FAILED_CODE;
								$arg['error_line']= __line__;
				            	$arg['message'] = $this->lang->line('record_not_found');
							}
						}
			    	}
			    	else{
			    		$arg['status']  = 0;
						$arg['error_code']   = ERROR_FAILED_CODE;
						$arg['error_line']= __line__;
		            	$arg['message'] = $this->lang->line('invalid_request');
			    	}
			    }
			}
		}
	    echo json_encode($arg);
	}
	//Function used for change notification status
	public function change_notification_status()
	{
		$arg  = array();
		//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {
        	//check version is updated or not
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error_code']   = 461;
					$arg['error_line']= __line__;
					$arg['message'] = $result;
				}
				else
				{
					$_POST = $this->input->post();

					if($_POST == [])
					{
						$_POST = json_decode(file_get_contents("php://input"), true);
					}
					if($_POST)
					{
						$this->form_validation->set_rules('notification_status', 'Notification Status', 'required|is_natural|less_than[2]', array('less_than' => 'Notification Status should 0 or 1'));
						if ($this->form_validation->run() == FALSE)
						{
							$arg['status']  = 0;
							$arg['error_code']   = ERROR_FAILED_CODE;
							$arg['error_line']= __line__;
							$arg['message'] = get_form_error($this->form_validation->error_array());
						}
						else
						{
							$auth_token = $this->input->get_request_header('Authorization');
					    	$user_token = json_decode(base64_decode($auth_token));

							$usid    = $user_token->userid;
							$loguser = $this->dynamic_model->get_user_by_id($usid);
							if($loguser)
							{
								$data1 = array(
									'Notification_Status' => $this->input->post('notification_status')
								);
				                $where     = array("Id" => $usid);
				                $keyUpdate = $this->dynamic_model->updateRowWhere("users",$where,$data1);   
				                if($keyUpdate)
				                {
				                	$notification_status= $this->input->post('notification_status');
				                	if($notification_status==1){
				                		$msg=$this->lang->line('notification_off_success');
				                	}else{
				                		$msg=$this->lang->line('notification_on_success');
				                	}

				                	$arg['status']  = 1;
				                	$arg['error_code']   = SUCCESS_CODE;
			                        $arg['error_line']= __line__;
				                	$arg['data']['notification_status'] = $this->input->post('notification_status');
									$arg['message'] = $msg;
				                }
				                else
				                {
				                	$arg['status']  = 0;
				                	$arg['error_code']   = ERROR_FAILED_CODE;
				                	$arg['error_line']= __line__;
									$arg['message'] = $this->lang->line('not_updated');
				                }
							} 
							else
							{
								$arg['status']  = 0;
								$arg['error_code']   = ERROR_FAILED_CODE;
								$arg['error_line']= __line__;
								$arg['message'] = $this->lang->line('record_not_found');
							}
						}
					}
				}
			}
		}
		echo json_encode($arg);
	}

    // About the App Function
    public function get_about_us()
    {
		$arg = array(); 
		 //check version is updated or not
		$version_result = version_check_helper();
		if($version_result['status'] != 1 )
		{
			$arg = $version_result;
		} 
		else
		{
			$arg['status'] = 1;
			$arg['error_code']   = SUCCESS_CODE;
			$arg['error_line']= __line__;
			$arg['message'] = "";
	        $arg['data']    = array('url' => site_url().'Welcome/about_app');
	    }
		echo json_encode($arg);
	}
	// Function name(privacy_policy)
    public function get_privacy_policy()
    {
    	$arg = array();
         //check version is updated or not
		$version_result = version_check_helper();
		if($version_result['status'] != 1 )
		{
			$arg = $version_result;
		} 
		else
		{
	        $arg['status'] = 1;
	        $arg['error_code']   = SUCCESS_CODE;
			$arg['error_line']= __line__;
			$arg['message'] = "";
	        $arg['data']    = array('url' => site_url().'Welcome/privacy_policy');
	    }
        echo json_encode($arg);
    }
    // Function name(terms and condition)
    public function get_terms_condition()
    {
	   $arg = array();
        //check version is updated or not
		$version_result = version_check_helper();
		if($version_result['status'] != 1 )
		{
			$arg = $version_result;
		} 
		else
		{
	        $arg['status'] = 1;
	        $arg['error_code']   = SUCCESS_CODE;
			$arg['error_line']= __line__;
			$arg['message'] = "";
	        $arg['data']    = array('url' => site_url().'Welcome/terms_condition');   
	    }
	    echo json_encode($arg);
    } 
    //Function used for Get Contact Details
    public function get_contact_detail()
    {
    	$arg  = array();
		//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {
        	//check version is updated or not
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error_code']   = 461;
					$arg['error_line']= __line__;
					$arg['message'] = $result;
				}
				else
				{
					$contact_detail = $this->dynamic_model->getdatafromtable('general_setting',array());
					if($contact_detail)
					{
						$user_data["mobile"]   = $contact_detail[0]['Cmobile'];
						$user_data["email"]    = $contact_detail[0]['Cemail'];
						$user_data["address"]  = $contact_detail[0]['Caddress'];
						$user_data["site_url"] = $contact_detail[0]['Clink'];
						$user_data["image"]    = site_url().'uploads/static_contents/'.$contact_detail[0]['Contact_Image'];
						$arg['status']  = 1;
						$arg['error_code']   = SUCCESS_CODE;
			            $arg['error_line']= __line__;
			            $arg['data']    = $user_data;
					}
					else
					{
						$arg['status']  = 0;
						$arg['error_code']   = ERROR_FAILED_CODE;
						$arg['error_line']= __line__;
			            $arg['message'] = $this->lang->line('record_not_found');
					}
				}
			}
		}
	    echo json_encode($arg);
	}
    //Function used for Get Notification details 
    public function get_advertisement_details()
    {
    	$arg = array();
    	//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {
        	//check version is updated or not
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error_code']   = 461;
					$arg['error_line']= __line__;
					$arg['message'] = $result;
				}
				else
				{
			    	$_POST = $this->input->post();

					if($_POST == [])
					{
						$_POST = json_decode(file_get_contents("php://input"), true);
					}
					if($_POST)
					{
						$this->form_validation->set_rules('advertisement_id', 'advertisement Id', 'required|numeric');
						if ($this->form_validation->run() == FALSE)
						{
						  	$arg['status']  = 0;
						  	$arg['error_code']   = ERROR_FAILED_CODE;
						  	$arg['error_line']= __line__;
						  	$arg['message'] = get_form_error($this->form_validation->error_array());
						} 
						else
						{
							$auth_token = $this->input->get_request_header('Authorization');
					    	$user_token = json_decode(base64_decode($auth_token));

							$userid   = $user_token->userid;
							$loguser  = $this->dynamic_model->get_user_by_id($userid);
							$advertisement_id   = $this->input->post('advertisement_id');							
							$banner_data = $this->dynamic_model->getdatafromtable('manage_advertisement_images',array('Id' =>$advertisement_id));
							if(!empty($banner_data))
							{
								$user_data = array();
								 
								$arg['status']       = 1;
								$arg['error_code']   = SUCCESS_CODE;
			                    $arg['error_line']= __line__;
								$arg['data']    = array('url' => site_url().'Welcome/banner_details/'.encode($advertisement_id)); 
								$arg['message']      = "Banner Details";
							}
							else
							{
								$arg['status']  = 0;
								$arg['error_code']   = ERROR_FAILED_CODE;
								$arg['error_line']= __line__;
				            	$arg['message'] = $this->lang->line('record_not_found');
							}
						}
			    	}
			    }
			}
		}
	    echo json_encode($arg);
	}

	//Function used for Get Notification List 
    public function get_notification_list()
    {
    	$arg = array();
    	//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {
        	//check version is updated or not
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error_code']   = 461;
					$arg['error_line']= __line__;
					$arg['message'] = $result;
				}
				else
				{
			    	$_POST = $this->input->post();

					if($_POST == [])
					{
						$_POST = json_decode(file_get_contents("php://input"), true);
					}
					if($_POST)
					{
						$this->form_validation->set_rules('page_no', 'Page No', 'required|numeric');
						if ($this->form_validation->run() == FALSE)
						{
						  	$arg['status']  = 0;
						  	$arg['error_code']   = ERROR_FAILED_CODE;
						  	$arg['error_line']= __line__;
						  	$arg['message'] = get_form_error($this->form_validation->error_array());
						} 
						else
						{
							$auth_token = $this->input->get_request_header('Authorization');
					    	$user_token = json_decode(base64_decode($auth_token));

							$userid   = $user_token->userid;
							$loguser  = $this->dynamic_model->get_user_by_id($userid);
							$limit    = config_item('page_data_limit'); 
							$offset   = $limit * $this->input->post('page_no');
							$condition = "Recepient_Id = '".$userid."' AND Is_Deleted = '0' AND `Read_Date_Time` >= ( CURDATE() - INTERVAL 15 DAY )";

							$notification_data = $this->dynamic_model->getdatafromtable('user_notifications', $condition, '*', $limit, $offset,'Id');

							//echo "<pre>";print_r($notification_data);die;
							$cond = "Recepient_Id = '".$userid."' AND Is_Deleted = '0' AND Is_Read = '0' AND `Read_Date_Time` >= ( CURDATE() - INTERVAL 15 DAY )";
							$notification_count = $this->dynamic_model->getdatafromtable('user_notifications', $cond, '*');
							if(!empty($notification_data))
							{
								$user_data = array();
								$request_data=array();
								foreach($notification_data as $details)
			            		{
		            				$user_data["id"]                 = $details['Id'];
		            				$user_data["notification_text"]  = $details['Notification_Text'];
		            				$user_data["tran_type_id"]       = $details['Tran_Type_Id'];
		            				$user_data["read"]               = $details['Is_Read'];
		            				$user_data['created_at']         = date('d M Y', strtotime($details['Read_Date_Time']));
		            				$user_data['time']               = date('g:i A',strtotime($details['Read_Date_Time']));
		            				$notification_array[]            = $user_data;
			            		}
			            		//echo "<pre>";print_r($notification_array);die;
			            		if($notification_count)
									$tot_count  = count($notification_count);
								else
									$tot_count  = "0";
								$arg['status']       = 1;
								$arg['error_code']   = SUCCESS_CODE;
			                    $arg['error_line']= __line__;
								$arg['data']         = $notification_array;
								$arg['unread_count'] = "$tot_count";
								$arg['message']      = "Notification List";
							}
							else
							{
								$arg['status']  = 0;
								$arg['error_code']   = ERROR_FAILED_CODE;
								$arg['error_line']= __line__;
				            	$arg['message'] = $this->lang->line('record_not_found');
							}
						}
			    	}
			    }
			}
		}
	    echo json_encode($arg);
	}

	//Function used for Read Notification Status
	public function read_notification()
	{
    	$arg  = array();
		//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {
        	//check version is updated or not
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error_code']   = 461;
					$arg['error_line']= __line__;
					$arg['message'] = $result;
				}
				else
				{
					$_POST = $this->input->post();

					if($_POST == [])
					{
						$_POST = json_decode(file_get_contents("php://input"), true);
					}
					if($_POST)
					{
						$this->form_validation->set_rules('notification_id', 'Notification Id', 'required');
						if ($this->form_validation->run() == FALSE)
						{
							$arg['status']  = 0;
							$arg['error_code']   = ERROR_FAILED_CODE;
							$arg['message'] = get_form_error($this->form_validation->error_array());
						}
						else
						{
							$auth_token = $this->input->get_request_header('Authorization');
					    	$user_token = json_decode(base64_decode($auth_token));

							$usid    = $user_token->userid;
							$loguser = $this->dynamic_model->get_user_by_id($usid);
							if($loguser)
							{
								$notification_id = $this->input->post('notification_id');
								$data1  = array(
											'Is_Read' => 1
											);
				                $where  = array("Id" => $notification_id,"Recepient_Id" =>$usid);
				                $keyUpdate = update_data("user_notifications", $data1, $where); 
				                if($keyUpdate)
				                {
				                	$cond = "Recepient_Id = '".$usid."' AND Is_Read = '0' AND Is_Deleted = '0' AND `Read_Date_Time` >= ( CURDATE() - INTERVAL 15 DAY )";
									$notification_count = $this->dynamic_model->getdatafromtable('user_notifications', $cond, '*');
									if($notification_count)
										$unread_count  = count($notification_count);
									else
										$unread_count  = "0";

				                	$notification_data = array("id" =>$notification_id,"is_read"=>1);
				                	$arg['status']       = 1;
				                	$arg['error_code']   = SUCCESS_CODE;
			                        $arg['error_line']= __line__;
									$arg['data']         = $notification_data;
									$arg['unread_count'] = (string)$unread_count;
				                }
				                else
				                {
				                	$arg['status']  = 0;
				                	$arg['error_code']   = ERROR_FAILED_CODE;
				                	$arg['error_line']= __line__;
									$arg['message'] = $this->lang->line('not_updated');
				                }
							} 
							else
							{
								$arg['status']  = 0;
								$arg['error_code']   = ERROR_FAILED_CODE;
								$arg['error_line']= __line__;
								$arg['message'] = $this->lang->line('record_not_found');
							}
						}
					}
				}
			}
		}
		echo json_encode($arg);
	}

	//Function used for Read Notification Status
	public function clear_notification()
	{ 
    	$arg  = array();
		//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        { 
        	$arg = $user_status; 
        }
        else
        { 
        	//check version is updated or not
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{  
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error_code']   = 461;
					$arg['error_line']= __line__;
					$arg['message'] = $result;
				}
				else
				{
					// $_POST = json_decode(file_get_contents("php://input"), true);
					// if($_POST)
					// {
						/*$this->form_validation->set_rules('notification_id', 'Notification Id', 'required');
						if ($this->form_validation->run() == FALSE)
						{
							$arg['status']  = 0;
							$arg['error_code']   = ERROR_FAILED_CODE;
							$arg['message'] = get_form_error($this->form_validation->error_array());
						}
						else
						{*/
							$auth_token = $this->input->get_request_header('Authorization');
					    	$user_token = json_decode(base64_decode($auth_token));

							$usid    = $user_token->userid;
							$loguser = $this->dynamic_model->get_user_by_id($usid);
							if($loguser)
							{
								$data1  = array(
											'Is_Deleted' => 1
											);
				                $where  = array("Recepient_Id" =>$usid);
				                $keyUpdate = update_data("user_notifications", $data1, $where); 
				                if($keyUpdate)
				                {
				                	$cond = "Recepient_Id = '".$usid."' AND Is_Deleted = '0' AND `Read_Date_Time` >= ( CURDATE() - INTERVAL 15 DAY )";
									$notification_count = $this->dynamic_model->getdatafromtable('user_notifications', $cond, '*');
									if($notification_count)
										$unread_count  = count($notification_count);
									else
										$unread_count  = "0";

				                	//$notification_data = array("id" =>$notification_id,"is_read"=>1);
				                	$arg['status']       = 1;
				                	$arg['error_code']   = SUCCESS_CODE;
			                        $arg['error_line']= __line__;
									//$arg['data']         = '';//$notification_data;
									//$arg['unread_count'] = (string)$unread_count;
									$arg['message'] = 'Notification cleared successfully';
				                }
				                else
				                {
				                	$arg['status']  = 0;
				                	$arg['error_code']   = ERROR_FAILED_CODE;
				                	$arg['error_line']= __line__;
									$arg['message'] = $this->lang->line('not_updated');
				                }
							} 
							else
							{
								$arg['status']  = 0;
								$arg['error_code']   = ERROR_FAILED_CODE;
								$arg['error_line']= __line__;
								$arg['message'] = $this->lang->line('record_not_found');
							}
						//}
					//}
				}
			}
		}
		echo json_encode($arg);
	}


	//Function used for Add/Save bank details 
    public function saveBankDetail()
    {
    	$arg  = array();
		//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {
        	//check version is updated or not
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error']   = 461;
					$arg['message'] = $result;
				}
				else
				{
			    	$_POST = $this->input->post();

					if($_POST == [])
					{
						$_POST = json_decode(file_get_contents("php://input"), true);
					}
					if($_POST)
					{
						$arg = array();
			        	$this->form_validation->set_rules('bank_name', 'Bank Name', 'required');
			        	$this->form_validation->set_rules('acc_number', 'Account Number', 'required|numeric|min_length[5]|max_length[14]');
			        	$this->form_validation->set_rules('branch_name', 'Branch Name', 'required');
			        	$this->form_validation->set_rules('acc_holder_name', 'Holder Name', 'required');
						if ($this->form_validation->run() == FALSE)
						{
						  	$arg['status']  = 0;
						  	$arg['error']   = ERROR_FAILED_CODE;
						  	$arg['message'] = get_form_error($this->form_validation->error_array());
						}
						else
						{
			        		$bank_name       = $this->input->post('bank_name');
				            $acc_number      = $this->input->post('acc_number');
				            $acc_holder_name = $this->input->post('acc_holder_name');
				            //$branch_name     = $this->input->post('branch_name');

							$userid  = getuserid();
							$loguser = $this->dynamic_model->get_user_by_id($userid);

							$bank_Exist = $this->dynamic_model->get_row('user_payment_methods',array('User_Id'=> $userid,'Account_No'=>$acc_number,'Is_Bank'=>1));
							if(empty($bank_Exist))
						 	{
						 		// insert data into user_payment_methods table
		                        $bankDetailArr = array(
				                                    'User_Id'          	=>$userid,
				                                    'Bank_Name' 		=>$bank_name,
				                                    'Account_No'       	=>encode_id($acc_number),
				                                    'Acc_Holder_Name'  	=>$acc_holder_name,
				                                    'Is_Bank'          	=>1,
				                                    'Is_Deleted'       	=>0,
				                                    'Created_By'       	=>$userid,
				                                    'Last_Updated_By'  	=>$userid
					                            );
					            $payment_id = $this->dynamic_model->insertdata('user_payment_methods', $bankDetailArr);
		                        $arg['status']  = 1;
		                        $arg['error_code']   = SUCCESS_CODE;
								$arg['error_line']= __line__;
		                        $arg['message'] = "Bank details saved successfully";
			                    
						 	}
						 	else
						 	{
						 		if($bank_Exist['Is_Deleted'] == 0)
								{
									$arg['status']  = 0;
									$arg['error_code']   = ERROR_FAILED_CODE;
									$arg['error_line']= __line__;
		                        	$arg['message'] = "Bank details Already Exist";
								}
								else
								{
									$data1 	= array(
												'Is_Deleted' => 0,
												'Last_Updated_By'     =>$userid,
												'Last_Updated_Date_Time'=>date('Y-m-d H:i:s')
											);
					                $where      = array("Id" => $bank_Exist['Id']);
					                $bankUpdate = update_data("user_payment_methods", $data1, $where); 
					                if($bankUpdate)
					                {
					                	$arg['status']  = 1;
					                	$arg['error_code']   = SUCCESS_CODE;
										$arg['error_line']= __line__;
										$arg['message'] = 'Bank Details Updated successfully';
					                }
								}
						 	}
						}
			    	}
			    }
			}
		}
	    echo json_encode($arg);
	}
	//Used function for change fingerprint status
	public function fingerprint_status()
	{
    	$arg  = array();
		//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {
        	//check version is updated or not
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error_code']   = 461;
					$arg['error_line']= __line__;
					$arg['message'] = $result;
				}
				else
				{
					$_POST = $this->input->post();

					if($_POST == [])
					{
						$_POST = json_decode(file_get_contents("php://input"), true);
					}
					if($_POST)
					{
						$this->form_validation->set_rules('finger_status', 'Finger Status' ,'required|numeric', array(
							'required' => $this->lang->line('fingerstatus_required')));
						if ($this->form_validation->run() == FALSE)
						{
							$arg['status']  = 0;
							$arg['error_line']= __line__;
							$arg['message'] = get_form_error($this->form_validation->error_array());
						}
						else
						{
							$userid   = getuserid();
							$loguser  = $this->dynamic_model->get_user_by_id($userid);

							$data2  = array('Fingerprint_Status' => $this->input->post('finger_status'));
			                $wheres = array("Id" => $userid);
			                $result = update_data("users", $data2,$wheres);

			                $arg['status']  = 1;
			                $arg['error_code']   = SUCCESS_CODE;
			                $arg['error_line']= __line__;

			                if($this->input->post('finger_status') == "0")
								$arg['message']  = $this->lang->line('finger_status_disable');
							else
								$arg['message']  = $this->lang->line('finger_status_enable');
								

							$arg['data']['finger_status'] = $this->input->post('finger_status');
						}
					}
				}
			}
		}
		echo json_encode($arg);
	}
    public function get_advertisement_images()
	{
	    $arg = array();
		$version_result = version_check_helper();
		if($version_result['status'] != 1 )
		{
			$arg = $version_result;
		}
		else
		{
			$result = check_authorization();
			if($result != 'true')
			{
				$arg['status']  = 101;
				$arg['error_code']   = 461;
				$arg['error_line']= __line__;
				$arg['message'] = $result;
			}
			else
			{   
		        $auth_token = $this->input->get_request_header('Authorization');
		    	$user_token = json_decode(base64_decode($auth_token));
				$usid      = $user_token->userid;
	            $advdata=array();  
	            $condition=array('status'=>'1');
				$advertisement_data = $this->dynamic_model->getdatafromtable("manage_advertisement_images",$condition);
		       if(!empty($advertisement_data))
		       {
		            foreach($advertisement_data as $value) 
		            {
		            	$advdata['advertisement_id']   = $value['Id'];
		            	$advdata['advertisement_title']= $value['Advertisement_Name'];
		            	$advdata['advertisement_image']=  site_url().'uploads/advertisement_img/'.$value['Advertisement_Image'];
		            	$finaldata[]	          = $advdata;
		            }
		            $arg['status']     = 1;
					$arg['error_code']  =SUCCESS_CODE;
					$arg['error_line']= __line__;
					$arg['data']       = $finaldata;
					$arg['message']    = $this->lang->line('adv_details');    
		       }
		       else
		       {   
				$arg['status']     = 0;
	            $arg['error_code'] = ERROR_FAILED_CODE;
				$arg['error_line' ]= __line__;
				$arg['data']       = array();
				$arg['message']    = $this->lang->line('record_not_found');
	              
		       }
	        }
        }
       echo json_encode($arg);
    }

	//Switch Accounts (sender to receiver OR vice versa)
	public function users_switch_accounts()
	{
    	$arg  = array();
		//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {
        	//check version is updated or not
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error_code']   = 461;
					$arg['error_line']= __line__;
					$arg['message'] = $result;
				}
				else
				{
					$_POST = $this->input->post();

					if($_POST == [])
					{
						$_POST = json_decode(file_get_contents("php://input"), true);
					}
					if($_POST)
					{
						$this->form_validation->set_rules('roles','Role','required', array('required' =>$this->lang->line('role_required')));
						if($this->form_validation->run() == FALSE)
						{
							$arg['status']     = 0;
							$arg['error_line'] = __line__;
							$arg['error_code'] = ERROR_FAILED_CODE;
							$arg['message']    = get_form_error($this->form_validation->error_array());
						}
						else
						{
							$auth_token = $this->input->get_request_header('Authorization');
					    	$user_token = json_decode(base64_decode($auth_token));
							$userid     = $user_token->userid;
							//$userid   = getuserid();
							$loguser    = $this->dynamic_model->get_user_by_id($userid);
							$roleData   = $this->dynamic_model->get_role_id($userid);
							$roles      = $this->input->post('roles');
                            $qrcode_image  = site_url().'uploads/qrcodes/'.$roleData['QR_Code_Img_Path'];
						    $profile_image = site_url().'uploads/user/'.$loguser['Profile_Pic'];
                            $fname       = name_format($loguser['FirstName']);
						    $lname       = name_format($loguser['LastName']);
						    $fullname    = name_format($loguser['FullName']);
					    	
					    	$wheres = array("User_Id" => $userid);
					    	$userdetails = $this->dynamic_model->select('users_documents',$wheres,'Id, Is_Verified');
	                        $mob_verified = (!empty($loguser['Is_Mobile_Verified']))? $loguser['Is_Mobile_Verified'] : 0;
							$email_verified=  (!empty($loguser['Is_Email_Verified']))? $loguser['Is_Email_Verified'] : 0;      
					    	$gender      = (!empty($loguser['Gender'])) ? $loguser['Gender'] :'';
							$Age         = (!empty($loguser['Age'])) ? $loguser['Age'] :'';
							$address     = (!empty($loguser['Address'])) ? $loguser['Address'] :'';
							$etippers_id = (!empty($loguser['etippers_id'])) ? $loguser['etippers_id'] :'';
					    	if($email_verified  == 0 ){
                                $redirect_to_verify = 2; // here redirect_to verify = 2 means email is not verified
                               }elseif($mob_verified == 0){
                               $redirect_to_verify = 1;// here redirect_to verify = 1 means mobile is not verified
                               }else{
                               $redirect_to_verify =0; 
                             } 
                            $loguser_documents = $this->dynamic_model->get_row('users_documents',array('User_Id'=> $loguser['Id'],'Document_Type_Id'=>2));
                            $verification_image = site_url().'uploads/identification/'.$loguser_documents['Document_Image_Name'];
						            
                            if(!($roles ==2 || $roles ==3))
							{
								$arg['status']  = 0;
								$arg['error_code']   = ERROR_FAILED_CODE;
								$arg['error_line']= __line__;
								$arg['message'] = 'Please send valid role';
								echo json_encode($arg);exit;
							}
							//echo $roleData['Role_Id'];die;
							if($roleData['Role_Id']!== $roles )
							{
							//echo $roleData['Role_Id'];die;
									
								if($roles ==3){
									  $document_data = $this->dynamic_model->get_row('users_documents',$wheres);
									  if(!empty($document_data && $document_data['Is_Verified']==1)){
						                    $updadata2 = array(
									                  'Role_Id'   =>3,
					                                   'Last_Updated_By'      =>$userid,
                                                       'Last_Updated_Date_Time'=>date('Y-m-d H:i:s')
							                         );
						                    $updated_token = $this->dynamic_model->updateRowWhere("user_in_roles",$wheres,$updadata2);
								            
								            $userinfo = array('user_id' => $userid,'Authorization' => $auth_token,'roles'=>'3','first_name' => $fname, 'last_name'=>$lname,'full_name' =>$fullname,'wallet_amount'=>$loguser['Current_Wallet_Balance'],'mobile'=>$loguser['Mobile_No'],'email'=>$loguser['Email'],'qrcode_image'=>$qrcode_image,'redirect_to_verify'=>"$redirect_to_verify",'notification_status'=>$loguser['Notification_Status'],'profile_image'=>$profile_image,'fingerprint_status'=>$loguser['Fingerprint_Status'],'auth_provider'=>$loguser['Auth_Provider'],'subscription_plan_status' =>'1','referral_code' =>$loguser['Referral_Code'],'total_referral_points' =>$loguser['Total_Referral_Points'],'verification_image' =>$verification_image,'gender' =>$gender,'age' =>$Age,'address' =>$address,'etippers_id'=>$etippers_id);

								            $where1 = array('slug' =>'profile_switch');	
											$template_data = $this->dynamic_model->getdatafromtable('manage_notification_mail', $where1);
												
											$emaildata['subject']     = $template_data[0]['subject']; 
						                    $desc_data = str_replace('{USERNAME}',strtoupper($loguser['FullName']),$template_data[0]['description']);
						                    $desc_data = str_replace('{PROFILE}','Sender',$desc_data);
						                   
											$emaildata['description'] = $desc_data;  
								        	
											$emaildata['body']        = '';

											$msg = $this->load->view('emailtemplate',$emaildata,TRUE);
											//--------------load email template----------------
											$mail = sendEmailCI($loguser['Email'],'' ,$emaildata['subject'],$msg);

								            $arg['status']  = 1;
							                $arg['error_code']   = SUCCESS_CODE;
							                $arg['error_line']= __line__;
							                $arg['data']    = $userinfo;
				                            $arg['message']  = "Successfully! You have switch profile as a sender";

									  }else{
										  	$where1 = array('slug' =>'profile_receiver_new');	
											$template_data = $this->dynamic_model->getdatafromtable('manage_notification_mail', $where1);
												
											$emaildata['subject']     = $template_data[0]['subject']; 
						                    $desc_data = str_replace('{FULLNAME}',strtoupper($loguser['FullName']),$template_data[0]['description']);
						                    $desc_data = str_replace('{EMAIL}',$loguser['Email'],$desc_data);
						                    $desc_data = str_replace('{MOBILE}',$loguser['Mobile_No'],$desc_data);
											$emaildata['description'] = $desc_data;  
								        	
											$emaildata['body']        = '';

											$msg = $this->load->view('emailtemplate',$emaildata,TRUE);
											//--------------load email template----------------
											//$this->sendmail->sendmailto($email,$emaildata['subject'],$msg);
											$mail = sendEmailCI(ADMIN_MAIL,'' ,$emaildata['subject'],$msg);

									  	    $arg['status']  = 0;
					                		$arg['error_code']   = ERROR_FAILED_CODE;
					                		$arg['error_line']= __line__;
								  			$arg['message'] = 'Document is not Verified.Please contact to Administration';
									      }
                                   }else{
                                   	//echo "testtstt";die;
                                       
									$updadata =  array(
								                  'Role_Id'   =>2,
				                                  'Last_Updated_By'      =>$userid,
                                                  'Last_Updated_Date_Time'=>date('Y-m-d H:i:s')
						                         );
				                    $updated_token = $this->dynamic_model->updateRowWhere("user_in_roles",$wheres,$updadata);
					               
					                $userinfo = array('user_id' => $userid,'Authorization' => $auth_token,'roles'=>'2','first_name' => $fname, 'last_name'=>$lname,'full_name' =>$fullname,'wallet_amount'=>$loguser['Current_Wallet_Balance'],'mobile'=>$loguser['Mobile_No'],'email'=>$loguser['Email'],'qrcode_image'=>'','redirect_to_verify'=>"$redirect_to_verify",'notification_status'=>$loguser['Notification_Status'],'profile_image'=>$profile_image,'fingerprint_status'=>$loguser['Fingerprint_Status'],'auth_provider'=>$loguser['Auth_Provider'],'subscription_plan_status' =>'1','referral_code' =>$loguser['Referral_Code'],'total_referral_points' =>$loguser['Total_Referral_Points'],'verification_image' =>$verification_image,'gender' =>$gender,'age' =>$Age,'address' =>$address,'etippers_id'=>$etippers_id);

					                	$where1 = array('slug' =>'profile_switch');	
										$template_data = $this->dynamic_model->getdatafromtable('manage_notification_mail', $where1);
											
										$emaildata['subject']     = $template_data[0]['subject']; 
					                    $desc_data = str_replace('{USERNAME}',strtoupper($loguser['FullName']),$template_data[0]['description']);
					                    $desc_data = str_replace('{PROFILE}','Receiver',$desc_data);
					                   
										$emaildata['description'] = $desc_data;  
							        	
										$emaildata['body']        = '';

										$msg = $this->load->view('emailtemplate',$emaildata,TRUE);
										//--------------load email template----------------
										$mail = sendEmailCI($loguser['Email'],'' ,$emaildata['subject'],$msg);

					                $arg['status']  = 1;
					                $arg['error_code']   = SUCCESS_CODE;
					                $arg['error_line']= __line__;
					                $arg['data']    = $userinfo;
		                            $arg['message']  =  "Successfully! You have switch profile as a receiver";

								   } 
							}else
							{
							  	    $arg['status']  = 0;
			                		$arg['error_code']   = ERROR_FAILED_CODE;
			                		$arg['error_line']= __line__;
						  			$arg['message'] = 'Please dont send same role';
						   }
						}
					}
				}
			}
		}
		echo json_encode($arg);
	}
	public function removeElementWithValue($array, $key, $value)
	{
	    foreach($array as $subKey => $subArray)
	    {
	        if($subArray[$key] == $value)
	        {
	            unset($array[$subKey]);
	        }
	    }
	    return $array;
	}

	public function get_receiver_list()
	{
    	$arg  = array();
		//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {
        	//check version is updated or not
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error_code']   = 461;
					$arg['error_line']= __line__;
					$arg['message'] = $result;
				}
				else
				{
					// $_POST = json_decode(file_get_contents("php://input"), true);
					// if($_POST)
					// {
						/*$this->form_validation->set_rules('search_keyword', 'Finger Status' ,'required', array(
							'required' => $this->lang->line('search_keyword_required')));
						if ($this->form_validation->run() == FALSE)
						{
							$arg['status']  = 0;
							$arg['error_line']= __line__;
							$arg['message'] = get_form_error($this->form_validation->error_array());
						}*/
						// else
						// {
							$lat = $this->input->get_request_header('lat');
							$long = $this->input->get_request_header('long');
							//$search_keyword = $_POST['search_keyword'];

							$userid   = getuserid();
							$loguser  = $this->dynamic_model->get_user_by_id($userid);

							// 3959 as miles , 6371 as km
							/*$query = $this->db->query(" SELECT  Users.*,uir.Role_Id,  
									( 6371 * acos(cos(radians(".$lat.")) * cos(radians(Lat)) * cos(radians(Lang) - radians(".$long.")) + sin(radians(".$lat.")) * sin(radians(Lat ))) ) AS distance  FROM Users  INNER JOIN 
										user_in_roles as uir ON Users.Id = uir.User_Id
													WHERE uir.Role_Id = 3 HAVING distance < 50 
													ORDER BY distance  ")->result_array();*/
							$query = $this->db->query(" SELECT  users.*,uir.Role_Id FROM users  INNER JOIN
										user_in_roles as uir ON users.Id = uir.User_Id
													WHERE uir.Role_Id = 3 ORDER BY users.FullName  ")->result_array();
							$userinfo = array();
							if(!empty($query)){
								foreach ($query as $userDetail) {
									$profile_image    = site_url().'uploads/user/'. $userDetail['Profile_Pic'];
                                    $org_data = $this->dynamic_model->getdatafromtable('organization_details',array('User_Id'=>$userDetail['Id'],'is_deleted'=>0));
									if(!empty($org_data)){
									  $org_status='1';
									 }else{
									 $org_status='0';
									}
						            	
					            	$userinfo[] = array('user_id'=>$userDetail['Id'],'firstname' => $userDetail['FirstName'], 'lastname' => $userDetail['LastName'],'fullname'=>$userDetail['FullName'],'profile_image'=>$profile_image,'role_id'=>$userDetail['Role_Id'],'mobile'=>$userDetail['Mobile_No'],'email'=>$userDetail['Email'],'gender'=>$userDetail['Gender'],'etippers_id' =>$userDetail['etippers_id'],'org_status'=>$org_status);
								}
								$arg['status']  = 1;
				                $arg['error_code']   = SUCCESS_CODE;
				                $arg['error_line']= __line__;
				                $arg['data']= $userinfo;
				                $arg['message']= '';
							}
							else{
								$arg['status']  = 0;
				                $arg['error_code']   = ERROR_FAILED_CODE;
				                $arg['error_line']= __line__;
				                $arg['data']= array();
				                $arg['message']= $this->lang->line('record_not_found');
							}
						//}
					//}
					/*else{
						$arg['status']  = 0;
                		$arg['error_code']   = ERROR_FAILED_CODE;
                		$arg['error_line']= __line__;
			  			$arg['message'] = 'Please send search keyword';
					}*/
				}
			}
		}
		echo json_encode($arg);
	}
	//Function Refferal_Points_Settings 
    public function get_refferal_points_value()
    {
    	$arg  = array();
		//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {
        	//check version is updated or not
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error_code']   = 461;
					$arg['error_line']= __line__;
					$arg['message'] = $result;
				}
				else
				{
					$userid   = getuserid();
					$loguser  = $this->dynamic_model->get_user_by_id($userid);
					$referral_data = $this->dynamic_model->get_row('refferal_points_settings',[]);
					$referral_points_per_referral_amount=$referral_data['Refferal_Points'];
					$refferal_Amount=$referral_data['Refferal_Amount'];
					if($referral_data)
					{
						 
                       $data=array("referral_points_per_referral_amount"=>"$referral_points_per_referral_amount",
                         	         "refferal_Amount"=>"$refferal_Amount","total_referral_points"=>$loguser['Total_Referral_Points']);
						
						$arg['status']      = 1;
						$arg['error_code']  = ERROR_FAILED_CODE;
						$arg['error_line']  = __line__;
						$arg['data']        = $data;
						$arg['message']     = "Rerferral points value";
					}
					else
					{
						$arg['status']  = 0;
						$arg['error_code']   = ERROR_FAILED_CODE;
						$arg['error_line']= __line__;
			            $arg['message'] = $this->lang->line('record_not_found');
					}
				}
			}
		}
	    echo json_encode($arg);
	}
  


	public function get_countries()
	{
	    $arg = array();
		$version_result = version_check_helper();
		if($version_result['status'] != 1 )
		{
			$arg = $version_result;
		}
		else
		{
			$result = check_authorization();
			if($result != 'true')
			{
				$arg['status']  = 101;
				$arg['error_code']   = 461;
				$arg['error_line']= __line__;
				$arg['message'] = $result;
			}
			else
			{   
		        $auth_token = $this->input->get_request_header('Authorization');
		    	$user_token = json_decode(base64_decode($auth_token));
				$usid      = $user_token->userid;
				$countries = $this->users_model->get_country();;
		       if(!empty($countries))
		       {
		            $arg['status']     = 1;
					$arg['error_code']  =SUCCESS_CODE;
					$arg['error_line']= __line__;
					$arg['data']       = $countries;
					$arg['message']    = 'Countries';    
		       }
		       else
		       {   
				$arg['status']     = 0;
	            $arg['error_code'] = ERROR_FAILED_CODE;
				$arg['error_line' ]= __line__;
				$arg['data']       = array();
				$arg['message']    = $this->lang->line('record_not_found');
	              
		       }
	        }
        }
       echo json_encode($arg);
    }


	//Used function for change Transaction Password
	public function changeTransactionPassword()
	{
		$arg    = array();
		//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0 && !empty($user_status))
        {
        	$arg = $user_status;
        }
        else
        {
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = $this->check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error_code']   = 461;
					$arg['error_line']= __line__;
					$arg['message'] = $result;
				}
				else
				{
					$_POST = $this->input->post();

					if($_POST == [])
					{
						$_POST = json_decode(file_get_contents("php://input"), true);
					}
					if($_POST)
					{
						$this->form_validation->set_rules('old_pin', 'Old Pin', 'trim|required');
						$this->form_validation->set_rules('new_pin', 'Transaction pin', 'required|min_length[4]|max_length[4]|numeric');
						//$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[6]');
						// $new_password= $this->input->post('new_password');

						$old_pin = $this->input->post('old_pin');
						$new_pin = $this->input->post('pin');
						// $pass_msg= $this->valid_password($new_password);
						// if($pass_msg !== TRUE){
						// $this->form_validation->set_rules('new_password','Password','trim|required|callback_valid_password');
						// $this->form_validation->set_message('valid_password', $pass_msg);
					    // }
						if ($this->form_validation->run() == FALSE)
						{
							$arg['status']  = 0;
							$arg['error_code']   = ERROR_FAILED_CODE;
							$arg['error_line']= __line__;
							$arg['message'] = get_form_error($this->form_validation->error_array());
						}
						else
						{
							$auth_token = $this->input->get_request_header('Authorization');
					    	$user_token = json_decode(base64_decode($auth_token));

							$usid    = $user_token->userid;
							$loguser = $this->dynamic_model->get_user_by_id($usid);
							$hashed_password = encrypt_password($this->input->post('old_pin'));
							if($hashed_password == $loguser['Transaction_Password'])
							{
								$data1 = array(
												'Transaction_Password' => encrypt_password($new_pin),
												'Last_Updated_By'     =>$usid,
												'Last_Updated_Date_Time'=>date('Y-m-d H:i:s')
											);
				                $where     = array("Id" => $usid);
				                $keyUpdate = $this->dynamic_model->updateRowWhere("users",$where,$data1);
				                if($keyUpdate)
				                {
				                	$arg['status']  = 1;
				                	$arg['error_code']   = SUCCESS_CODE;
			                        $arg['error_line']= __line__;
									$arg['message'] = $this->lang->line('password_change_success');
				                }
				                else
				                {
				                	$arg['status '] = 0;
				                	$arg['error_code']   = ERROR_FAILED_CODE;
			                        $arg['error_line']= __line__;
				                	$arg['message'] = $this->lang->line('password_not_update');
				                }
							}
							else
							{
								$arg['status']  = 0;
								$arg['error_code']   = ERROR_FAILED_CODE;
								$arg['error_line']= __line__;
								$arg['message'] = $this->lang->line('old_password_not');
							}
						}
					}
				}
			}
		}
		echo json_encode($arg);
	}


	public function getUserDocument()
	{
		$arg    = array();
		//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {
        	//check version is updated or not
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error_code']   = 461;
					$arg['error_line']= __line__;
					$arg['message'] = $result;
				}
				else
				{
					$_POST = $this->input->post();

					if($_POST == [])
					{
						$_POST = json_decode(file_get_contents("php://input"), true);
					}
					if($_POST)
					{
						$this->form_validation->set_rules('user_id', '', 'required');
						if ($this->form_validation->run() == FALSE)
						{
						  	$arg['status']  = 0;
						  	$arg['error_code']   = ERROR_FAILED_CODE;
						  	$arg['error_line']= __line__;
						  	$arg['message'] =  get_form_error($this->form_validation->error_array());
						}
						else
						{
							$user_id = $this->input->post('user_id');
							$auth_token  = $this->input->get_request_header('Authorization');
					    	$user_token  = json_decode(base64_decode($auth_token));

							$usid        = $user_token->userid;
							$user_detail     = $this->dynamic_model->get_user_by_id($user_id);
					        
							if($user_detail){
							$user_roles = $this->dynamic_model->get_row('user_in_roles',array('User_Id'=> $user_detail['Id']));

							$user_documents = $this->dynamic_model->get_row('users_documents',array('User_Id'=> $user_detail['Id']));				
						
							$arg['status'] = 1;
							$arg['error_code']   = SUCCESS_CODE;
							$arg['error_line']= __line__;
							$arg['data']['user_id']                = $user_detail['Id'];
							// $arg['data']['Authorization']          =$auth_token; 
							// $arg['data']['full_name']              = name_format($user_detail['FullName']);
							// $arg['data']['first_name']             = name_format($user_detail['FirstName']);
							// $arg['data']['last_name']              = name_format($user_detail['LastName']);
							// $arg['data']['email']                  = $user_detail['Email'];
							// $arg['data']['mobile']                 = $user_detail['Mobile_No'];
							// $arg['data']['roles']                  = $user_roles['Role_Id'];
							// $arg['data']['gender']                 = (!empty($user_detail['Gender'])) ? $user_detail['Gender'] : "" ;
							// $arg['data']['age']                    = (!empty($user_detail['Age'])) ? $user_detail['Age'] : "" ;
							// $arg['data']['address']                = (!empty($user_detail['Address'])) ? $user_detail['Address'] : "" ;
							// $arg['data']['subscription_plan_status']  = '1';
							// $arg['data']['referral_code']         = $user_detail['Referral_Code'];
							// $arg['data']['total_referral_points'] = $user_detail['Total_Referral_Points'];
							// $arg['data']['auth_provider']         = $user_detail['Auth_Provider'];
							// $arg['data']['notification_status']   = $user_detail['Notification_Status'];
							// $arg['data']['fingerprint_status']    = $user_detail['Fingerprint_Status'];
							// $arg['data']['redirect_to_verify']    = "$redirect_to_verify";
							// $arg['data']['wallet_amount']         = $user_detail['Current_Wallet_Balance'];
							// $etippers_id = (!empty($user_detail['etippers_id'])) ? $user_detail['etippers_id'] :'';

							// $arg['data']['etippers_id']           = $etippers_id;
							if($user_roles['Role_Id'] == "3")
							{
							
								$arg['data']['qrcode_image']        = site_url().'uploads/qrcodes/'.$user_roles['QR_Code_Img_Path'];
							}
							else
							{

								$arg['data']['qrcode_image']   = "";
							}
							
							$arg['data']['profile_image']      = site_url().'uploads/user/'.$user_detail['Profile_Pic'];

							$arg['data']['verification_image'] = site_url().'uploads/identification/'.$user_documents['Document_Image_Name'];
							
							// $userinfo = array('user_id' => $userid,'Authorization' => $user_token,'roles'=>$user_detail[0]['Role_Id'],'first_name' => $fname, 'last_name'=>$lname,'full_name' =>$fullname,'wallet_amount'=>$data[0]['Current_Wallet_Balance'],'mobile'=>$data[0]['Mobile_No'],'email'=>$data[0]['Email'],'qrcode_image'=>$qrcode_image,'redirect_to_verify'=>"$redirect_to_verify",'notification_status'=>$data[0]['Notification_Status'],'profile_image'=>$profile_image,'fingerprint_status'=>$data[0]['Fingerprint_Status'],'auth_provider'=>$data[0]['Auth_Provider'],'subscription_plan_status' =>'1','referral_code' =>$data[0]['Referral_Code']);
						
						}	
						else
						{
							$arg['status']  = 0;
							$arg['error_code']   = ERROR_FAILED_CODE;
							$arg['error_line']= __line__;
							$arg['message'] = $this->lang->line('record_not_found');
						}
					}
				}
				}
			}
		}
		echo json_encode($arg);
	}

	public function sendNotification()
	{
		$arg    = array();
		//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error_code']   = 461;
					$arg['error_line']= __line__;
					$arg['message'] = $result;
				}
				else
				{
					$_POST = $this->input->post();

					if($_POST == [])
					{
						$_POST = json_decode(file_get_contents("php://input"), true);
					}
					if($_POST)
					{
						$this->form_validation->set_rules('device_token', '', 'required');
						if ($this->form_validation->run() == FALSE)
						{
						  	$arg['status']  = 0;
						  	$arg['error_code']   = ERROR_FAILED_CODE;
						  	$arg['error_line']= __line__;
						  	$arg['message'] =  get_form_error($this->form_validation->error_array());
						}
						else
						{
							$device_token = $this->input->post('device_token');
							$auth_token  = $this->input->get_request_header('Authorization');
					    	$user_token  = json_decode(base64_decode($auth_token));

							// echo $device_token;

							$usid        = $user_token->userid;
							$user_detail     = $this->dynamic_model->get_user_by_id($usid);
					        
							if($user_detail){
								$user_roles = $this->dynamic_model->get_row('user_in_roles',array('User_Id'=> $user_detail['Id']));

								
								$arg['status'] = 1;
								$arg['error_code']   = SUCCESS_CODE;
								$arg['error_line']= __line__;
								$arg['data']['user_id']                = $user_detail['Id'];
								
								$subject = "Kashkash";
								$description = "You received a notification from Kashkash";
								$notification_setting = '';
								$type = 'android';

								$send_data = array('title' => $subject, 'message' => $description, 'token' => $device_token, 'notification_setting' => $notification_setting, 'user_id'=>$usid);
									// //print_r($send_data); exit();
									// //echo $notification_setting_on_off;
									// if (!empty($notification_setting_on_off))  { 
									// 	if (strtolower($type) == 'android') {
											// $st = patientAndroidPush($send_data);
											$st = self::androidPush($send_data);
											$arg['message'] = $st;
									// 	} else if (strtolower($type) == 'ios') {
									// 		// $st = patientIosPush($send_data);
									// 	}
									// }
							}	
							else
							{
								$arg['status']  = 0;
								$arg['error_code']   = ERROR_FAILED_CODE;
								$arg['error_line']= __line__;
								$arg['message'] = $this->lang->line('record_not_found');
							}
						}
					}
				}
			}
		}
		echo json_encode($arg);
	}

	//Function used for Add Receiver
    public function addReceiver()
    {
    	$arg  = array();
		//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {
        	//check version is updated or not
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error']   = 461;
					$arg['message'] = $result;
				}
				else
				{
			    	$_POST = $this->input->post();

					if($_POST == [])
					{
						$_POST = json_decode(file_get_contents("php://input"), true);
					}
					if($_POST)
					{
						$arg = array();
            
					   $this->form_validation->set_rules('first_name', 'Name', 'required|trim', array( 'required' => $this->lang->line('firstname')));
					   $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim', array( 'required' => $this->lang->line('lastname')));		
					   $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.Email]' , array('required' => $this->lang->line('email_required'),'valid_email' => $this->lang->line('email_valid'),'is_unique' => $this->lang->line('email_unique')
					   ));
					   $this->form_validation->set_rules('mobile', 'Mobile', 'required|min_length[10]|max_length[12]|numeric|is_unique[users.Mobile_No]', array(
							   'required' => $this->lang->line('mobile_required'),
							   'min_length' => $this->lang->line('mobile_min_length'),
							   'max_length' => $this->lang->line('mobile_max_length'),
							   'numeric' => $this->lang->line('mobile_numeric'),
							   'is_unique' => $this->lang->line('mobile_unique')
						   ));

			        	$this->form_validation->set_rules('bank_name', 'Bank Name', 'required');
			        	$this->form_validation->set_rules('acc_number', 'Account Number', 'required|numeric|min_length[5]|max_length[14]');
			        	$this->form_validation->set_rules('branch_name', 'Branch Name', 'required');
			        	// $this->form_validation->set_rules('acc_holder_name', 'Holder Name', 'required');
						if ($this->form_validation->run() == FALSE)
						{
						  	$arg['status']  = 0;
						  	$arg['error']   = ERROR_FAILED_CODE;
						  	$arg['message'] = get_form_error($this->form_validation->error_array());
						}
						else
						{

							$full_name              = $this->input->post('first_name').' '.$this->input->post('last_name');
							$firstname              = $this->input->post('first_name');
							$lastname               = $this->input->post('last_name');
							$email                  = $this->input->post('email');
							$mobile                 = $this->input->post('mobile');
							//$id_pass_number         = $this->input->post('id_pass_number');
							$referral_code          = $this->input->post('referral_code');

							$roles = 3;
							$doc_name = true;
									
								$otpnumber = '1234';
								$my_referral_code= generateRandomString(10);
								$otpnumber=generate_Pin();
								$ref_num=getuniquenumber();
								$generate_etip_id = strtolower(substr($firstname, 0, 2)).rand (10,99).substr($mobile, -3).'@'.strtolower(SITE_TITLE);
								$lat= $this->input->get_request_header('lat');
								$long= $this->input->get_request_header('long');
								$userdata = array(
													'FullName'    => $full_name,
													'FirstName'   => $firstname,
													'lastName'    => $lastname,
													// 'Password'    => $hashed_password,
													'Email'       => $email,
													'Mobile_No'   => $mobile,
													'Profile_Pic' => 'default.jpg',
													'Mobile_OTP'  => $otpnumber,
													'Email_OTP'   => $otpnumber,
													'Notification_Status'=> 1,
													'Fingerprint_Status'   => 1,
													'Referral_Code'=> $my_referral_code,
													'Lat'=> (!empty($lat)) ? $lat : 0,
													'Lang'=> (!empty($long)) ? $long : 0,
													'etippers_id'=> $generate_etip_id,
												);
								$userid = $this->dynamic_model->insertdata('users', $userdata); 				



								$qr_number = generateQrcode($mobile);
									$users_roles = array('User_Id'=>$userid,
													'Role_Id'=>$roles,
													'Device_Id'=>$this->input->get_request_header('device_id'),
													'Device_Type'=>$this->input->get_request_header('device_type'),
													'QR_Code'=>$qr_number,
													'QR_Code_Img_Path'=>$qr_number.'.png'
													);

								$users_roles_id = $this->dynamic_model->insertdata('user_in_roles', $users_roles);



			        		$bank_name       = $this->input->post('bank_name');
				            $acc_number      = $this->input->post('acc_number');
				            $acc_holder_name = $this->input->post('acc_holder_name');
				            //$branch_name     = $this->input->post('branch_name');

							$userid  = getuserid();
							$loguser = $this->dynamic_model->get_user_by_id($userid);

							$bank_Exist = $this->dynamic_model->get_row('user_payment_methods',array('User_Id'=> $userid,'Account_No'=>$acc_number,'Is_Bank'=>1));
							if(empty($bank_Exist))
						 	{
						 		// insert data into user_payment_methods table
		                        $bankDetailArr = array(
				                                    'User_Id'          	=>$userid,
				                                    'Bank_Name' 		=>$bank_name,
				                                    'Account_No'       	=>encode_id($acc_number),
				                                    'Acc_Holder_Name'  	=>$firstname. ' '. $lastname,
				                                    'Is_Bank'          	=>1,
				                                    'Is_Deleted'       	=>0,
				                                    'Created_By'       	=>$userid,
				                                    'Last_Updated_By'  	=>$userid
					                            );
					            $payment_id = $this->dynamic_model->insertdata('user_payment_methods', $bankDetailArr);
		                        $arg['status']  = 1;
		                        $arg['error_code']   = SUCCESS_CODE;
								$arg['error_line']= __line__;
		                        $arg['message'] = "Receiver added successfully";
			                    
						 	}
						 	else
						 	{
						 		if($bank_Exist['Is_Deleted'] == 0)
								{
									$arg['status']  = 0;
									$arg['error_code']   = ERROR_FAILED_CODE;
									$arg['error_line']= __line__;
		                        	$arg['message'] = "Bank details Already Exist";
								}
								else
								{
									$data1 	= array(
												'Is_Deleted' => 0,
												'Last_Updated_By'     =>$userid,
												'Last_Updated_Date_Time'=>date('Y-m-d H:i:s')
											);
					                $where      = array("Id" => $bank_Exist['Id']);
					                $bankUpdate = update_data("user_payment_methods", $data1, $where); 
					                if($bankUpdate)
					                {
					                	$arg['status']  = 1;
					                	$arg['error_code']   = SUCCESS_CODE;
										$arg['error_line']= __line__;
										$arg['message'] = 'Bank Details Updated successfully';
					                }
								}
						 	}
						}
			    	}
			    }
			}
		}
	    echo json_encode($arg);
	}


	//Used function for Delete Account
	public function deleteAccount()
	{
		$arg    = array();
		//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0 && !empty($user_status))
        {
        	$arg = $user_status;
        }
        else
        {
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = $this->check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error_code']   = 461;
					$arg['error_line']= __line__;
					$arg['message'] = $result;
				}
				else
				{
					$_POST = $this->input->post();

					if($_POST == [])
					{
						$_POST = json_decode(file_get_contents("php://input"), true);
					}
					if($_POST)
					{
						$this->form_validation->set_rules('user_id', 'User Id', 'required');

						if ($this->form_validation->run() == FALSE)
						{
							$arg['status']  = 0;
							$arg['error_code']   = ERROR_FAILED_CODE;
							$arg['error_line']= __line__;
							$arg['message'] = get_form_error($this->form_validation->error_array());
						}
						else
						{
							$auth_token = $this->input->get_request_header('Authorization');
					    	$user_token = json_decode(base64_decode($auth_token));

							$usid    = $user_token->userid;
							// $loguser = $this->dynamic_model->get_user_by_id($usid);

							$userid                 = $this->input->post('user_id');
							$wheres = array(
								'Id' => $userid
							);
					$fetchresult = $this->dynamic_model->getdatafromtable('users', $wheres);
		
					// if($fetchresult[0]['Is_Email_Verified'] == 1 && $fetchresult[0]['Is_Mobile_Verified'] == 1)
					if($fetchresult[0])
					{
						$data2 = array(
										'Is_Active'           =>0,
										'Last_Updated_By'     =>$usid,
										'Last_Updated_Date_Time'=>date('Y-m-d H:i:s')
										);
						$where3   = array("Id" => $userid);   
						$updatedt = update_data("users", $data2, $where3);


				                	$arg['status']  = 1;
				                	$arg['error_code']   = SUCCESS_CODE;
			                        $arg['error_line']= __line__;
									$arg['message'] = 'Account deleted successfully';
				                }
				                else
				                {
				                	$arg['status '] = 0;
				                	$arg['error_code']   = ERROR_FAILED_CODE;
			                        $arg['error_line']= __line__;
				                	$arg['message'] = 'Account not deleted';
				                }
							}
						}
					}
				}
			}
		echo json_encode($arg);
	}

	//Function used send message
    public function sendMessage()
    {
    	$arg  = array();
		//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {
        	//check version is updated or not
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error']   = 461;
					$arg['message'] = $result;
				}
				else
				{
			    	$_POST = $this->input->post();

					if($_POST == [])
					{
						$_POST = json_decode(file_get_contents("php://input"), true);
					}
					if($_POST)
					{
						$arg = array();
            
					   $this->form_validation->set_rules('receiver_id', 'Id', 'required|trim', array( 'required' => $this->lang->line('receiver_id')));
					   $this->form_validation->set_rules('message', 'Message', 'required|trim', array( 'required' => $this->lang->line('lastname')));		
					
						if ($this->form_validation->run() == FALSE)
						{
						  	$arg['status']  = 0;
						  	$arg['error']   = ERROR_FAILED_CODE;
						  	$arg['message'] = get_form_error($this->form_validation->error_array());
						}
						else
						{
							$sender_id = getuserid();
							$receiver_id = $this->input->post('receiver_id');
							$message = $this->input->post('message');

							$sender = $this->dynamic_model->get_user_by_id($sender_id);

							$receiver = $this->dynamic_model->get_user_by_id($receiver_id);
							// echo $receiver['Device_Token'];
							if($sender_id == $receiver_id)
							{
								$arg['status']  = 0;
								$arg['error_code']   = ERROR_FAILED_CODE;
								$arg['error_line']= __line__;
								$arg['message'] = 'You cannot send message to yourself';
							}
							else{
								if($receiver)
								{
									$chatdata = array(
										'sender_id'    => $sender_id,
										'receiver_id'   => $receiver_id,
										// 'message'    => $message	
										);
							
									$chat = $this->dynamic_model->get_chat($sender_id, $receiver_id);
									$chat_id = null;

									if($chat){
										$chat_id = $chat[0]->id;
										$msgdata = array(
											'chat_id' => $chat[0]->id,
											'sender_id'    => $sender_id,
											'receiver_id'   => $receiver_id,
											'message'    => $message	
											);
										$addMsg = $this->dynamic_model->insertdata('messages', $msgdata);
									}
									else{
										$addChat = $this->dynamic_model->insertdata('chat', $chatdata);
										$chat_id = $addChat;
										$msgdata = array(
											'chat_id' => $addChat,
											'sender_id'    => $sender_id,
											'receiver_id'   => $receiver_id,
											'message'    => $message	
											);
										$addMsg = $this->dynamic_model->insertdata('messages', $msgdata);
									}
									
									$subject = $sender['FirstName'] . ' sent you a message ';
									$token = $receiver['Device_Token'];
									$notification_setting = null;
									// $type = $receiver['Device_Type'];

									$type = 'android';
									$send_data = array('title' => $subject, 'message' => $message, 'token' => $token, 'notification_setting' => $notification_setting,'user_id' => $sender_id, 'chat_id'=> $chat_id);
									// if(!empty($patient_info['notification_setting_on_off'])) {
										// if (strtolower($type) == 'android') {
											$st = self::androidPush($send_data);
										// } else if (strtolower($type) == 'ios') {
										// 	$st = self::iosPush($send_data);
										// }
									// }

									$arg['status']  = 1;
									$arg['error_code']   = SUCCESS_CODE;
									$arg['error_line']= __line__;
									$arg['message'] = 'Message sent successfully';
									
								}
								else{
									$arg['status']  = 0;
									$arg['error_code']   = ERROR_FAILED_CODE;
									$arg['error_line']= __line__;
									$arg['message'] = 'User Not Found';
								}
							}
						}		
			    	}
			    }
			}
		}
	    echo json_encode($arg);
	}
	
	public function getAllChats()
	{
    	$arg  = array();
		//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {
        	//check version is updated or not
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error_code']   = 461;
					$arg['error_line']= __line__;
					$arg['message'] = $result;
				}
				else
				{
					$user_id   = getuserid();
					
					$chat = $this->dynamic_model->get_chats($user_id);
					
					// echo $this->db->last_query();
					$arg['status']  = 1;
					$arg['error_code']   = SUCCESS_CODE;
					$arg['error_line']= __line__;
					$arg['data']= $chat;
					$arg['message']= '';
				}
			}
		}
		echo json_encode($arg);
	}

	public function getAllMessages()
	{
		header("Content-Type: text/html");
		$chatid = (int) $_GET['chatid'];
		$findresult = $this->dynamic_model->get_messages($chatid);
		
		if($findresult)
		{
			$arg['status']  = 1;
			$arg['error_code']   = SUCCESS_CODE;
			$arg['error_line']= __line__;
			$arg['data']= $findresult;
			$arg['message']= '';
        }
		else
		{
			$arg['status']  = 0;
			$arg['error_code']   = ERROR_FAILED_CODE;
			$arg['error_line']= __line__;
			$arg['message'] = 'Not Found';
		}
		echo json_encode($arg);
	}


	public function getAllUsers()
	{
    	$arg  = array();
		//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {
        	//check version is updated or not
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error_code']   = 461;
					$arg['error_line']= __line__;
					$arg['message'] = $result;
				}
				else
				{
					$user_id   = getuserid();
					
					$users = $this->dynamic_model->get_all_users('users', '', '', ['Password', 'Transaction_Password']);
					
					// echo $this->db->last_query();
					$arg['status']  = 1;
					$arg['error_code']   = SUCCESS_CODE;
					$arg['error_line']= __line__;
					$arg['data']= $users;
					$arg['message']= '';
				}
			}
		}
		echo json_encode($arg);
	}

    public function createPaymentIntent() {

		$arg = array();
		$version_result = version_check_helper();
		if($version_result['status'] != 1 )
		{
			$arg = $version_result;
		} 
		else
		{
			$_POST = $this->input->post();
			
			if($_POST == [])
			{
				
				$_POST = json_decode(file_get_contents("php://input"), true);
			}
			// if($_POST)
			// {
				$arg = array();
				$this->form_validation->set_rules('receiver_id', 'Receiver Id', 'required');
				$this->form_validation->set_rules('amount', 'Amount','required');

				if ($this->form_validation->run() == FALSE)
				{
				  	$arg['status']  = 0;
				  	$arg['error_code']   = ERROR_FAILED_CODE;
				  	$arg['error_line']= __line__;
				  	$arg['message'] =  get_form_error($this->form_validation->error_array());
				} 
				else
				{
					$sender_id = getuserid();
					$sender = $this->dynamic_model->get_user_by_id($sender_id);
					$current_wallet_balance = $sender['Current_Wallet_Balance'];
					if($this->input->post('amount') < $current_wallet_balance)
					{
						\Stripe\Stripe::setApiKey($this->config->item('stripe')['secret_key']);
						try {
							$paymentIntent = \Stripe\PaymentIntent::create([
								'amount' => $this->input->post('amount'),
								'currency' => 'usd',
								'automatic_payment_methods' => [
									'enabled' => true,
								],
							]);

							$output = [
								'clientSecret' => $paymentIntent->client_secret,
							];

							$receiver_id = $this->input->post('receiver_id');

							$paymentdata = array(
								'sender_id' => $sender_id,
								'receiver_id' => $receiver_id,
								'amount' => $this->input->post('amount'),
								'currency' => 'usd',
								'payment_intent' => $paymentIntent->id,
								'status' => 'initiated',
							);
							$new_sender_balance = $current_wallet_balance - $paymentIntent->amount;
							
							$sender_data = array(
								'Current_Wallet_Balance' => $new_sender_balance,
								'Last_Updated_By'     	 => $sender_id,
								'Last_Updated_Date_Time' =>date('Y-m-d H:i:s')
							);
							$where     = array("Id" => $sender_id);
							$senderUpdate = $this->dynamic_model->updateRowWhere("users",$where,$sender_data);

							$receiver = $this->dynamic_model->get_user_by_id($receiver_id);
							$current_wallet_balance = $receiver['Current_Wallet_Balance'];
							$new_receiver_balance = $current_wallet_balance + $paymentIntent->amount;
							
							$receiver_data = array(
								'Current_Wallet_Balance' => $new_receiver_balance,
								'Last_Updated_By'     	 => $receiver_id,
								'Last_Updated_Date_Time' =>date('Y-m-d H:i:s')
							);
							$where     = array("Id" => $receiver_id);
							$receiverUpdate = $this->dynamic_model->updateRowWhere("users",$where,$receiver_data);

							$addPayment = $this->dynamic_model->insertdata('payments', $paymentdata);

							$arg['status']  = 1;
							$arg['error_code']  = SUCCESS_CODE;
							$arg['error_line'] = __line__;
							$arg['clientKey']  = $paymentIntent->id;
							$arg['clientSecret']  = $paymentIntent->client_secret;
							$arg['message'] = '';
							
						} catch (Exception $e) {
							$arg['error_code']   = ERROR_FAILED_CODE;
							$arg['error_line']= __line__;
							$arg['message'] = $e->getMessage();
							// echo json_encode(['error' => $e->getMessage()]);
						}
					}
					else{
						$arg['error_code']   = ERROR_FAILED_CODE;
						$arg['error_line']= __line__;
						$arg['message'] = "You don't have enough balance.";
					}
				}
			// }
		}
		echo json_encode($arg);
    }

	public function confirmPaymentIntent() {
		\Stripe\Stripe::setApiKey($this->config->item('stripe')['secret_key']);
		try {
			// Retrieve the PaymentIntent ID and payment method from the request
			$paymentIntentId = $this->input->post('payment_intent_id');
			$paymentMethodId = $this->input->post('payment_method_id');
	
			$user_id = getuserid();
			$amount = $this->input->post('amount'); // Amount in smallest unit of currency (e.g., cents)
			$currency = $this->input->post('currency');
			$returnUrl = 'https://kashkash.net/webservices/Users/success'; // Replace with your actual success URL
	
			// Confirm the PaymentIntent
			$paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntentId);
			if($paymentIntent->status == 'requires_confirmation' || $paymentIntent->status == 'requires_payment_method'){
				$paymentIntent->confirm([
					'payment_method' => $paymentMethodId,
					'return_url' => $returnUrl, // Include the return URL here
				]);

				$payment_data = array(
					'status'  => $paymentIntent->status
				);
				$where     = array("payment_intent" => $paymentIntent->id);
				$keyUpdate = $this->dynamic_model->updateRowWhere("payments",$where,$payment_data);
			}
			//echo json_encode($paymentIntent);
			//die;
			// Check the status of the PaymentIntent
			if ($paymentIntent->status == 'succeeded') {

				$payment_data = array(
					'status'  => $paymentIntent->status
				);
				$where     = array("payment_intent" => $paymentIntent->id);
				$keyUpdate = $this->dynamic_model->updateRowWhere("payments",$where,$payment_data);

				// $addPayment = $this->dynamic_model->insertdata('payments', $paymentdata);
	
				$response = [
					'status' => 1,
					'error_code' => SUCCESS_CODE,
					'error_line' => __line__,
					'message' => '',
					'paymentIntent' => 'Payment success',
				];
	
			} elseif ($paymentIntent->status == 'requires_action' && $paymentIntent->next_action->type == 'redirect_to_url') {
				$response = [
					'status' => 'requires_action',
					'paymentIntent' => $paymentIntent,
					'redirect_to_url' => $paymentIntent->next_action->redirect_to_url->url,
				];
			} elseif($paymentIntent->status == 'incomplete') {
				$response = [
					'status' => 1,
					'error_code' => SUCCESS_CODE,
					'error_line' => __line__,
					'message' => '',
					'paymentIntent' => $paymentIntent,
				];
			}else {
				$response = [
					'status' => 0,
					'error_code' => ERROR_FAILED_CODE,
					'error_line' => __line__,
					'message' => 'No status found. Please try with different payment intent',
					'paymentIntent' => null,
				];
			}

	
		} catch (Exception $e) {
			// Handle other errors
			
			$response = [
				'status' => 0,
				'error_code' => ERROR_FAILED_CODE,
				'error_line' => __line__,
				'message' => $e->getMessage(),
				'paymentIntent' => null,
			];
		}
	
		echo json_encode($response);
	}

	public function listenToStripeEvent() {
		\Stripe\Stripe::setApiKey($this->config->item('stripe')['secret_key']);
		// This is your Stripe CLI webhook secret for testing your endpoint locally.
		$endpoint_secret = 'whsec_ddc5ff1d8fc3549a084ec5b1ee83f337ad47d35283bf9414f4f7c3aecba12da8';

		$payload = @file_get_contents('php://input');
		$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
		$sig_header_list =  explode(",",$sig_header);
		$sig_header_time =  explode("=",$sig_header_list[0])[1];
		$sig_header_v1 =  explode("=",$sig_header_list[1])[1];
		$event = null;

		try {
		$event = \Stripe\Webhook::constructEvent(
			$payload, $sig_header_v1, $endpoint_secret
		);
		} catch(\UnexpectedValueException $e) {
		// Invalid payload
		http_response_code(400);
		$response = [
			'status_line' => __line__,
			'message' => $e->getMessage(),
		];
		echo json_encode($response);
		exit();
		} catch(\Stripe\Exception\SignatureVerificationException $e) {
		// Invalid signature
		http_response_code(400);
		$response = [
			'status_line' => __line__,
			'message' => $e->getMessage(),
			'sig_header' => $sig_header,
			'payload' => $payload,
			'sig_header_v1' => $sig_header_v1,
			'sig_header_time' => $sig_header_time,
		];
		echo json_encode($response);
		exit();
		}

		// Handle the event
		switch ($event->type) {
			case 'checkout.session.async_payment_failed':
			$session = $event->data->object;
			case 'checkout.session.async_payment_succeeded':
			$session = $event->data->object;
			case 'payment_intent.succeeded':
			$paymentIntent = $event->data->object;
			case 'payout.canceled':
			$payout = $event->data->object;
			case 'payout.created':
			$payout = $event->data->object;
			case 'payout.failed':
			$payout = $event->data->object;
			case 'payout.paid':
			$payout = $event->data->object;
			// ... handle other event types
			default:
			echo 'Received unknown event type ' . $event->type;
		}

		http_response_code(200);
    }

	function androidPush($post) {
		$message = $post['message'];
		$device_token = $post['token'];
		$title = $post['title'];
		$user_id = @$post['user_id'];
		$chat_id = $post['chat_id'];
		$notification_setting = $post['notification_setting'];
	
		// $headers = array(
		// 	'Authorization: key=' . $apiKey,
		// 	'Content-Type: application/json',
		// );

		try {
			$serviceAccountFilePath = "pvKeylive.json";
			if (!file_exists($serviceAccountFilePath)) {
				throw new Exception("Service account file not found.");
			}
	
			$credential = new ServiceAccountCredentials(
				"https://www.googleapis.com/auth/firebase.messaging",
				json_decode(file_get_contents($serviceAccountFilePath), true)
			);
	
			$httpHandler = HttpHandlerFactory::build();
			$token = $credential->fetchAuthToken($httpHandler);
	
			if (!isset($token['access_token'])) {
				throw new Exception("Failed to fetch access token.");
			}
	
			// echo $device_token;
			// Prepare the cURL request
			$ch = curl_init("https://fcm.googleapis.com/v1/projects/kashkash-deaa2/messages:send");
	
			$headers = [
				'Content-Type: application/json',
				'Authorization: Bearer ' . $token['access_token']
			];
	
			$postData = [
				'message' => [
					'token' => $device_token,
					'notification' => [
						'title' => $title,
						'body' => $message
						// 'image' => 'https://cdn.shopify.com/s/files/1/1061/1924/files/Sunglasses_Emoji.png?2976903553660223024'
					],
					'webpush' => [
						'fcm_options' => [
							'link' => 'https://kashkash.net'
						]
					],
					'data' => [
						'user_id' => $user_id,
						'chat_id' => $chat_id
					],
				]
			];
	
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
	
			if ($response === false) {
				throw new Exception('Curl error: ' . curl_error($ch));
			}
	
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			// echo $response;
			
			$responseDecoded = json_decode($response, true);
			// echo $responseDecoded;
	
			if ($httpCode !== 200) {
				throw new Exception('Error response from FCM: ' . json_encode($responseDecoded));
			}
	
			return $responseDecoded;
		} catch (Exception $e) {
			return ['error' => $e->getMessage()];
		}
	}



	public function addUserDocument()
	{
		$arg = array();
		//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {
        	//check version is updated or not
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error_code']   = 461;
					$arg['error_line']= __line__;
					$arg['message'] = $result;
				}
				else
				{
					$_POST = $this->input->post();

					if($_POST == [])
					{
						$_POST = json_decode(file_get_contents("php://input"), true);
					}
					if($_POST)
					{
						$this->form_validation->set_rules('user_id', '', 'required');
						if(empty($_FILES['document']['name']))
						{
							$this->form_validation->set_rules('document','Document','required', array( 'required' => $this->lang->line('document_file')));
						}
						if ($this->form_validation->run() == FALSE)
						{
						  	$arg['status']  = 0;
						  	$arg['error_code']   = ERROR_FAILED_CODE;
						  	$arg['error_line']= __line__;
						  	$arg['message'] =  get_form_error($this->form_validation->error_array());
						}
						else
						{
							$user_id = $this->input->post('user_id');
							$auth_token  = $this->input->get_request_header('Authorization');
					    	$user_token  = json_decode(base64_decode($auth_token));

							$usid        = $user_token->userid;
							$userid   = getuserid();
							$doc_name = $this->dynamic_model->fileupload('document','uploads/identification');
							if($doc_name)
							{
								$users_documents = array('User_Id'      => $user_id,
								'Document_Type_Id'   	=> 2,
								'Document_Image_Name'	=> $doc_name,
								'Created_By'         	=> $userid
						   		);
								$users_documents_id = $this->dynamic_model->insertdata('users_documents', $users_documents);

								$arg['status'] = 1;
								$arg['error_code'] = SUCCESS_CODE;
								$arg['error_line'] = __line__;
								$arg['message'] =  'Document added successfully.';

							}
							else{
								$arg['status']  = 0;
								$arg['error_code']   = ERROR_FAILED_CODE;
								$arg['error_line']= __line__;
								$arg['message'] =  'Please select a file';
							}
							
						}
					}
				}
			}
		}
		echo json_encode($arg);
	}


	public function updateUserDocument()
	{
		$arg = array();
		//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {
        	//check version is updated or not
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error_code']   = 461;
					$arg['error_line']= __line__;
					$arg['message'] = $result;
				}
				else
				{
					$_POST = $this->input->post();

					if($_POST == [])
					{
						$_POST = json_decode(file_get_contents("php://input"), true);
					}
					if($_POST)
					{
						$this->form_validation->set_rules('user_id', '', 'required');
						$this->form_validation->set_rules('document_id', '', 'required');
						if(empty($_FILES['document']['name']))
						{
							$this->form_validation->set_rules('document','Document','required', array( 'required' => $this->lang->line('document_file')));
						}
						if ($this->form_validation->run() == FALSE)
						{
						  	$arg['status']  = 0;
						  	$arg['error_code']   = ERROR_FAILED_CODE;
						  	$arg['error_line']= __line__;
						  	$arg['message'] =  get_form_error($this->form_validation->error_array());
						}
						else
						{
							$user_id = $this->input->post('user_id');
							$document_id = $this->input->post('document_id');
							$auth_token  = $this->input->get_request_header('Authorization');
					    	$user_token  = json_decode(base64_decode($auth_token));

							$usid        = $user_token->userid;
							$userid   = getuserid();
							$doc_name = $this->dynamic_model->fileupload('document','uploads/identification');
							if($doc_name)
							{
								$users_documents = array('User_Id'      => $user_id,
												'Document_Type_Id'   	=> 2,
												'Document_Image_Name'	=> $doc_name,
												'Last_Updated_By'      => $userid
											);
								
								$where = array(
									'Id' => $document_id
								);	
								$updatedata = $this->dynamic_model->updateRowWhere("users_documents",$where, $users_documents);

								$arg['status'] = 1;
								$arg['error_code'] = SUCCESS_CODE;
								$arg['error_line'] = __line__;
								$arg['message'] =  'Document updated successfully.';
							}
							else{
								$arg['status']  = 0;
								$arg['error_code']   = ERROR_FAILED_CODE;
								$arg['error_line']= __line__;
								$arg['message'] =  'Please select a file';
							}
						}
					}
				}
			}
		}
		echo json_encode($arg);
	}

	public function createBankAccountToken()
	{
		$arg = array();
		$user_status = checkUserStatus();
		if (@$user_status['status'] != 0) {
			$arg = $user_status;
		} else {
			$version_result = version_check_helper();
			if ($version_result['status'] != 1) {
				$arg = $version_result;
			} else {
				$result = check_authorization();
				if ($result != 'true') {
					$arg['status']  = 101;
					$arg['error_code']   = 461;
					$arg['error_line'] = __line__;
					$arg['message'] = $result;
				} else {

					$_POST = $this->input->post();

					if($_POST == [])
					{
						$_POST = json_decode(file_get_contents("php://input"), true);
					}
					if($_POST)
					{
						$this->form_validation->set_rules('country', '', 'required');
						$this->form_validation->set_rules('currency', '', 'required');
						$this->form_validation->set_rules('account_holder_name', '', 'required');
						$this->form_validation->set_rules('account_holder_type', '', 'required');
						$this->form_validation->set_rules('routing_number', '', 'required');
						$this->form_validation->set_rules('account_number', '', 'required');
						if ($this->form_validation->run() == FALSE)
						{
						  	$arg['status']  = 0;
						  	$arg['error_code']   = ERROR_FAILED_CODE;
						  	$arg['error_line']= __line__;
						  	$arg['message'] =  get_form_error($this->form_validation->error_array());
						}
						else
						{
							// $bank_account = $this->input->post('bank_account');
							$country  = $this->input->post('country');
							$currency  = $this->input->post('currency');
							$account_holder_name  = $this->input->post('account_holder_name');
							$account_holder_type  = $this->input->post('account_holder_type');
							$routing_number  = $this->input->post('routing_number');
							$account_number  = $this->input->post('account_number');
								
							// if (!$bank_account) {
							// 	$this->output->set_content_type('application/json')
							// 				->set_output(json_encode(['error' => 'Bank account information is required.']));
							// 	return;
							// }
			
							// if (empty($bank_account['country']) ||
							// 	empty($bank_account['currency']) ||
							// 	empty($bank_account['account_holder_name']) ||
							// 	empty($bank_account['account_holder_type']) ||
							// 	empty($bank_account['routing_number']) ||
							// 	empty($bank_account['account_number'])) {
							// 	$this->output->set_content_type('application/json')
							// 				->set_output(json_encode(['error' => 'All bank account fields are required and must not be empty.']));
							// 	return;
							// }
			
							$user_id = getuserid();
							try {
								$stripe = new \Stripe\StripeClient($this->config->item('stripe')['secret_key']);
			
								$account = $stripe->accounts->create([
									'type' => 'custom',
									'country' => $country,
									'email' => 'user@example.com',
									'capabilities' => [
										'transfers' => ['requested' => true],
									],
									'business_type' => 'individual',
									'individual' => [
										'first_name' => $account_holder_name, 
										'last_name' => $account_holder_name,
									],
									'metadata' => [
										'user_id' => $user_id,
									],
								]);
			
								$bank_account_token = $stripe->tokens->create([
									'bank_account' => [
										'country' => $country,
										'currency' => $currency,
										'account_holder_name' => $account_holder_name,
										'account_holder_type' => $account_holder_type,
										'routing_number' => $routing_number,
										'account_number' => $account_number,
									],
								]);
			
								$bank_account = $stripe->accounts->createExternalAccount(
									$account->id,
									['external_account' => $bank_account_token->id]
								);
			
								$stripe->accounts->update(
									$account->id,
									[
										'business_profile' => [
											'mcc' => '5045',
											'url' => 'https://bestcookieco.com',
										],
										'company' => [
											'address' => [
												'city' => 'Schenectady',
												'line1' => '123 State St',
												'postal_code' => '12345',
												'state' => 'NY',
											],
											'tax_id' => '000000000',
											'name' => $account_holder_name,
											'phone' => '8888675309',
										],
										'individual' => [
											'first_name' => $account_holder_name,
											'last_name' => $account_holder_name,
											'dob' => [
												'day' => 1,
												'month' => 1,
												'year' => 1990,
											],
											'address' => [
												'city' => 'Schenectady',
												'line1' => '123 State St',
												'postal_code' => '12345',
												'state' => 'NY',
											],
											'email' => 'user@example.com',
											'phone' => '8888675309',
										],
										'tos_acceptance' => [
											'date' => time(),
											'ip' => $this->input->ip_address(),
										],
									]
								);
			
								$arg['status'] = 1;
								$arg['error_code'] = SUCCESS_CODE;
								$arg['error_line'] = __line__;
								$arg['account_id'] = $account->id;
								$arg['bank_account_id'] = $bank_account->id;
							} catch (Exception $e) {
								$arg['status']  = 0;
								$arg['error_code']   = ERROR_FAILED_CODE;
								$arg['error_line'] = __line__;
								$arg['message'] =  $e->getMessage();
							}
						}
					}
				}
			}
		}
		echo json_encode($arg);
	}

	public function createBankTransfer()
	{
		$arg = array();
		$user_status = checkUserStatus();
		if (@$user_status['status'] != 0) {
			$arg = $user_status;
		} else {
			$version_result = version_check_helper();
			if ($version_result['status'] != 1) {
				$arg = $version_result;
			} else {
				$result = check_authorization();
				if ($result != 'true') {
					$arg['status']  = 101;
					$arg['error_code']   = 461;
					$arg['error_line'] = __line__;
					$arg['message'] = $result;
				} else {
					$amount = $this->input->post('amount');
					if (!$amount) {
						$this->output->set_content_type('application/json')
									->set_output(json_encode(['error' => 'Transfer amount is required.']));
						return;
					}
					$account_id = $this->input->post('account_id');
					// $external_account_id = $this->input->post('external_account_id');

					if (empty($account_id)) {
						$this->output->set_content_type('application/json')
									->set_output(json_encode(['error' => 'Account ID and External Account ID are required.']));
						return;
					}
					try {
						$stripe = new \Stripe\StripeClient($this->config->item('stripe')['secret_key']);
						$transfer = $stripe->transfers->create([
							'amount' => $amount * 100,
							'currency' => 'usd',
							'destination' => $account_id, 
							'transfer_group' => 'YOUR_TRANSFER_GROUP',
						]);

						$arg['status'] = 1;
						$arg['error_code'] = SUCCESS_CODE;
						$arg['error_line'] = __line__;
						$arg['transfer_id'] = $transfer->id;
					} catch (Exception $e) {
						$arg['status']  = 0;
						$arg['error_code']   = ERROR_FAILED_CODE;
						$arg['error_line'] = __line__;
						$arg['message'] =  $e->getMessage();
					}
				}
			}
		}
		echo json_encode($arg);
	}

	public function getPaymentMethod()
	{
    	$arg  = array();
		//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {
        	//check version is updated or not
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error_code']   = 461;
					$arg['error_line']= __line__;
					$arg['message'] = $result;
				}
				else
				{
					$user_id   = getuserid();
					
					$payment_methods = $this->dynamic_model->get_payment_method();
					
					// echo $this->db->last_query();
					$arg['status']  = 1;
					$arg['error_code']   = SUCCESS_CODE;
					$arg['error_line']= __line__;
					$arg['data']= $payment_methods;
					$arg['message']= '';
				}
			}
		}
		echo json_encode($arg);
	}

	public function addRecipient()
    {
    	$arg  = array();
		//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {
        	//check version is updated or not
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error']   = 461;
					$arg['message'] = $result;
				}
				else
				{
			    	$_POST = $this->input->post();

					if($_POST == [])
					{
						$_POST = json_decode(file_get_contents("php://input"), true);
					}
					if($_POST)
					{
						$arg = array();
			        	$this->form_validation->set_rules('account_holder_name', 'Account Holder Name', 'required');
			        	$this->form_validation->set_rules('account_holder_type', 'Account Holder Type', 'required');
			        	$this->form_validation->set_rules('routing_number', 'Routing Number', 'required');
			        	$this->form_validation->set_rules('account_number', 'Account Number', 'required');
			        	$this->form_validation->set_rules('phone_number', 'Phone Number', 'required');
						if ($this->form_validation->run() == FALSE)
						{
						  	$arg['status']  = 0;
						  	$arg['error']   = ERROR_FAILED_CODE;
						  	$arg['message'] = get_form_error($this->form_validation->error_array());
						}
						else
						{
			        		$account_holder_name = $this->input->post('account_holder_name');
				            $account_holder_type = $this->input->post('account_holder_type');
				            $routing_number 	 = $this->input->post('routing_number');
				            $account_number      = $this->input->post('account_number');
				            $phone_number     	 = $this->input->post('phone_number');

							$recipientArr = array(
								'account_holder_name' => $account_holder_name,
								'account_holder_type' =>$account_holder_type,
								'routing_number'      =>$routing_number,
								'account_number'  	  =>$account_number,
								'phone_number'        =>$phone_number
							);
							$payment_id = $this->dynamic_model->insertdata('recipients', $recipientArr);
							$arg['status']  = 1;
							$arg['error_code']   = SUCCESS_CODE;
							$arg['error_line']= __line__;
							$arg['message'] = "Recipient saved successfully";
						}
			    	}
			    }
			}
		}
	    echo json_encode($arg);
	}

	public function recipientList()
	{
    	$arg  = array();
		//check user is active or not
        $user_status = checkUserStatus();
        if(@$user_status['status'] != 0)
        {
        	$arg = $user_status;
        }
        else
        {
        	//check version is updated or not
			$version_result = version_check_helper();
			if($version_result['status'] != 1 )
			{
				$arg = $version_result;
			} 
			else
			{
				$result = check_authorization();
				if($result != 'true')
				{
					$arg['status']  = 101;
					$arg['error_code']   = 461;
					$arg['error_line']= __line__;
					$arg['message'] = $result;
				}
				else
				{
					$user_id   = getuserid();
					$recipients = $this->dynamic_model->get_recipients();
					
					$arg['status']  = 1;
					$arg['error_code']   = SUCCESS_CODE;
					$arg['error_line']= __line__;
					$arg['data']= $recipients;
					$arg['message']= 'Recipient List';
				}
			}
		}
		echo json_encode($arg);
	}
}