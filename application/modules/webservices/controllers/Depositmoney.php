<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;

require_once('vendor/autoload.php');
class Depositmoney extends MX_Controller {
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct()
	{
		parent::__construct();
		header('Content-Type: application/json');
		$this->load->library('form_validation');
		$this->load->model('dynamic_model');
		$this->load->model('users_model');
		$this->load->library('encryption');
		$this->load->library('sendmail');

		$language = $this->input->get_request_header('language');
		if($language == "en"){
			$this->lang->load("message","english");
		} else if($language == "tk"){
			$this->lang->load("message","taka");
		} else {
			$this->lang->load("message","english");
		}
	}

	public function index() {
	}

	public function notification()
	{
		//Notification functionality
	    //$notification_to = 'fy5n-53ylFw:APA91bGyhYFgZzTxSKquLnN55vOabvD3m10GA-4XcpqDj3sh-oZIzSiEL2vO35Wf8hixmRIBqGrbL4CyiCgN9mmG-JDHUqlb38gtdQ_FTMkeGxukaUCTW30EcMmJeZJOGZA1l_TinUA8';
	    $notification_to = 'eWC_looZ0ZE:APA91bFH_4hTvF2fj8ct3ODNmI1Y1fWWnLSuH9iRZJrRy8UtsyZlGGrtE6sR8zLlSMySZf10S3_A2MqMZacysCEzPJZ9QXsDypgOBb_wE89henZVFu9kjR47jtkJHKKyq9xF21J_fj4K';
	    $myname          = 'tejendra';
	    $amount = '10';
	    $notification_type = 2; // Use For Deposit Money
	    $notification_title = 'Dear '.$myname.',You have successfully added ₹'.number_format((float)$amount, 2, '.', '').' to your wallet on '.date('d/m/Y '). ' at '.date('H:i A').' Ref.No: 5454544 Transaction cost ₹0.00';

	    //sendPushAndroid($notification_to,$notification_title, $notification_type );
	    sendPushIos($notification_to,$notification_title, $notification_type );
	}

	//Function used for add money
    public function addMoney()
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
						$this->form_validation->set_rules('amount', 'Amount', 'required|greater_than[0]');
						$this->form_validation->set_rules('pin', 'Transaction pin', 'required|min_length[4]|max_length[4]|numeric');
						if ($this->form_validation->run() == FALSE)
						{
						  	$arg['status']  = 0;
						  	$arg['error']   = ERROR_FAILED_CODE;
						  	$arg['message'] = get_form_error($this->form_validation->error_array());
						}
						else
						{
							/*  add_money_method = 1 means Debit card
					        	add_money_method = 2 means Credit card 
					        	add_money_method = 3 means Bank
					        	add_money_method = 4 means Saved card details
					     	*/
					        $add_money_method = $this->input->post('add_money_method');
					        $amount           = $this->input->post('amount');
					        $charge           = $this->input->post('charge');
					        $pin              = $this->input->post('pin');
					        $promo_amount     = $this->input->post('promo_amount');
					        $ref_num          = getuniquenumber();

					        //Check Transaction Password 
			        		$userid  = getuserid();
							$loguser = $this->dynamic_model->get_user_by_id($userid);
							// if($loguser['Transaction_Password'] != encrypt_password($pin))
							if($loguser['Transaction_Password'] != "")
			                {
			                    $arg['status']  = 0;
			                    $arg['error']   = ERROR_PIN_CODE;
			                    $arg['message'] = $this->lang->line('invalid_pin');
			                }
			                else
			                {
			                	//Check Daily limit or count
								$limit = check_limit($amount,$userid,"daily",2);
								if($limit == false)
								{
									$arg['status']  = 0;
									$arg['error']   = ERROR_FAILED_CODE;
									$arg['message'] = $this->lang->line('daily_limit_exceed');
									echo json_encode($arg);die;
								}

								//Check Monthly limit or count
								$monthly_limit = check_limit($amount,$userid,"monthly",2);
								if($monthly_limit == false)
								{
									$arg['status']  = 0;
									$arg['error']   = ERROR_FAILED_CODE;
									$arg['message'] = $this->lang->line('monthly_limit_exceed');
									echo json_encode($arg);die;
								}

						        // Use these function for Debit or Credit card method
						        if($add_money_method == 1 || $add_money_method == 2)
						        {
						        	$this->form_validation->set_rules('security_code', 'Security Code', 'required|numeric|min_length[3]|max_length[3]');
						        	$this->form_validation->set_rules('card_number', 'Card Number', 'required|numeric|min_length[16]|max_length[16]');
						        	$this->form_validation->set_rules('expiry_month', 'Expiry Month', 'required|numeric|less_than_equal_to[12]|greater_than[0]|min_length[2]');
			        				$this->form_validation->set_rules('expiry_year', 'Expiry Year', 'required|numeric|min_length[4]|max_length[4]');
						        }
						        // Use these function for Bank method
						        if($add_money_method == 3)
						        {
						        	$this->form_validation->set_rules('bank_name', 'Bank Name', 'required');
						        	$this->form_validation->set_rules('acc_number', 'Account Number', 'required|numeric|min_length[5]|max_length[14]');
						        	$this->form_validation->set_rules('branch_name', 'Branch Name', 'required');
						        }
						        // Use these function for Saved card details method
						        if($add_money_method == 4)
						        {
						        	$this->form_validation->set_rules('security_code', 'Security Code', 'required|numeric|min_length[3]|max_length[3]');
						        	$this->form_validation->set_rules('save_card_id', 'Saved Card Id', 'required|numeric');
						        }
								if ($this->form_validation->run() == FALSE)
								{
								  	$arg['status']  = 0;
								  	$arg['error']   = ERROR_FAILED_CODE;
								  	$arg['message'] = get_form_error($this->form_validation->error_array());
								}
								else
								{
									if($add_money_method == 1 || $add_money_method == 2)
						        	{
										$security_code    = $this->input->post('security_code');
							            $card_number      = $this->input->post('card_number');
							            $expiry_month     = $this->input->post('expiry_month');
							            $expiry_year      = $this->input->post('expiry_year');
							            $save_card_check  = $this->input->post('save_card_check');

					                	$FirstFourNumber = substr($card_number, 0, 4); // get first 4
					                    $LastFourNumber  = substr($card_number, 12, 4); // get last 4
					                    $newCardNumber   = $FirstFourNumber.' XXXX XXXX '.$LastFourNumber;

					                    // check year is valid
								        if(check_expiry_year($expiry_year) == false)
								        {
								        	$arg['status']  = 0;
								        	$arg['error']   = ERROR_FAILED_CODE;
					                        $arg['message'] = $this->lang->line('invalid_expiry_year');
								            echo json_encode($arg);die;
								        }

								        // check year is valid
								        if(check_expiry_month_year($expiry_month,$expiry_year) == false)
								        {
								        	$arg['status']  = 0;
								        	$arg['error']   = ERROR_FAILED_CODE;
					                        $arg['message'] = $this->lang->line('invalid_expiry_year_month');
								            echo json_encode($arg);die;
								        }
					                }
					                if($add_money_method == 3)
						        	{
						        		$bank_name       = $this->input->post('bank_name');
							            $acc_number      = $this->input->post('acc_number');
							            $branch_name     = $this->input->post('branch_name');
							            $save_card_check = $this->input->post('save_card_check');
						        	}
						        	if($add_money_method == 4)
						        	{
						        		$commMsg       = $this->input->post('commMsg');
							            $security_code = $this->input->post('security_code');
							            $save_card_id  = $this->input->post('save_card_id');
						        	}
				                    $t = time();

				                    if($add_money_method == 1)
				                    {
				                    	$card_Exist = $this->dynamic_model->get_row('user_payment_methods',array('User_Id'=> $userid,'Card_Bank_No'=>$newCardNumber,'Is_Debit_Card'=>1));
				                    }
				                    if($add_money_method == 2)
				                    {
				                    	$card_Exist = $this->dynamic_model->get_row('user_payment_methods',array('User_Id'=> $userid,'Card_Bank_No'=>$newCardNumber,'Is_Credit_Card'=>1));
				                    }
				                    if($add_money_method == 3)
				                    {
				                    	$card_Exist = $this->dynamic_model->get_row('user_payment_methods',array('User_Id'=> $userid,'Bank_Name'=>$bank_name,'Account_No'=>$acc_number,'Is_Bank'=>1));
				                    }
				                    if($add_money_method == 4)
				                    {
				                    	$card_Exist = $this->dynamic_model->get_row('user_payment_methods',array('User_Id'=> $userid,'Id'=>$save_card_id));
				                    }
				                    if(empty($card_Exist))
				                    {
				                        // Use for faster checkout
				                        if(@$save_card_check == 1)
				                            $del_status = 0;
				                        else
				                            $del_status = 1;

				                        if($add_money_method == 1)
				                        {
					                        // Firstly insert data into user_payment_methods table
					                        $debitcardDetailArr = array(
							                                    'User_Id'          =>$userid,
							                                    'Card_Bank_No'     =>$newCardNumber,
							                                    'Expiry_Month_Year'=>$expiry_month.'-'.$expiry_year,
							                                    'Is_Debit_Card'    =>1,
							                                    'Is_Deleted'       =>$del_status,
							                                    'Created_By'       =>$userid,
							                                    'Last_Updated_By'  =>$userid
					                            			);
					                        $payment_id = $this->dynamic_model->insertdata('user_payment_methods', $debitcardDetailArr);
					                    }
					                    if($add_money_method == 2)
				                        {
					                        // Firstly insert data into user_payment_methods table
					                        $creditcardDetailArr = array(
							                                    'User_Id'          =>$userid,
							                                    'Card_Bank_No'     =>$newCardNumber,
							                                    'Expiry_Month_Year'=>$expiry_month.'-'.$expiry_year,
							                                    'Is_Credit_Card'   =>1,
							                                    'Is_Deleted'       =>$del_status,
							                                    'Created_By'       =>$userid,
							                                    'Last_Updated_By'  =>$userid
					                            			);
					                        $payment_id = $this->dynamic_model->insertdata('user_payment_methods', $creditcardDetailArr);
					                    }
					                    if($add_money_method == 3)
				                        {
					                        // Firstly insert data into user_payment_methods table
					                        $bankDetailArr = array(
							                                    'User_Id'          =>$userid,
							                                    'Bank_Name' =>$bank_name,
							                                    'Account_No'       =>$acc_number,
							                                    'Branch_Name'      =>$branch_name,
							                                    'Is_Bank'          =>1,
							                                    'Is_Deleted'       =>$del_status,
							                                    'Created_By'       =>$userid,
							                                    'Last_Updated_By'  =>$userid
					                            			);
					                        $payment_id = $this->dynamic_model->insertdata('user_payment_methods', $bankDetailArr);
					                    }
					                    if($add_money_method == 4)
					                    {
					                    	$arg['status']  = 0;
					                    	$arg['error']   = ERROR_FAILED_CODE;
					                        $arg['message'] = $this->lang->line('invalid_card');
								            echo json_encode($arg);die;
					                    }
				                    }
				                    else
				                    {
				                        $payment_id = $card_Exist['Id'];
				                    }

				                    //Calculate Earn Points
				                    $earn_point = ($amount*0.5)/100;

				                    //Then after insert into Transactions table
			                        $paymentaddArr = array(
			                                                'Tran_Type_Id'         =>2, //deposit money
			                                                'To_Payment_Method_Id' =>$payment_id,
			                                                'Amount'               =>$amount,
			                                                'Tran_Points'          =>$earn_point,
			                                                'To_User_Id'           =>$userid,
			                                                'Tran_Status_Id'       =>6,
			                                                'Sig'                  =>'+',
			                                                'Amount_Received'      =>$amount,
			                                                'Third_Party_Tran_Id'  =>$ref_num,
			                                                'Created_By'           =>$userid,
			                                                'Last_Updated_By'      =>$userid
			                                        	);
			                        $Transaction_id = $this->dynamic_model->insertdata('transactions', $paymentaddArr);

			                        //Then after insert into Tran_Charges table
			                        $chargeaddArr = array(
			                                                'Transaction_Id'  =>$Transaction_id,
			                                                'Charge_Type_Id'  =>0,
			                                                'Charge_Amt'      =>0,
			                                                'Created_By'      =>$userid,
			                                                'Last_Updated_By' =>$userid
			                                        	);
			                        $Transaction_charge_id = $this->dynamic_model->insertdata('tran_charges', $chargeaddArr);

			                        //Update User current wallet balance
			                        $total_amount = $loguser['Current_Wallet_Balance'] + $amount;
			                        $total_points = $loguser['Total_Points'] + $earn_point;
			                        $userbalancedata['Current_Wallet_Balance'] = $total_amount;
			                        $userbalancedata['Total_Points']           = $total_points;
		        					$updatebalancedata = $this->dynamic_model->updatedata('users', $userbalancedata, $userid);

		        					$update_user = $this->dynamic_model->get_user_by_id($userid);

		        					//Notification functionality
		        					$User_Role = $this->dynamic_model->get_row('user_in_roles',array('User_Id'=> $userid));

		                            $notification_to = $User_Role['Device_Id'];
		                            $myname          = ucfirst($loguser['FullName']);

		                            $notification_title = 'Dear '.$myname.',You have successfully added '.CURRENCY_SYMBOLE.''.number_format($amount,2).' to your wallet on '.date('d/m/Y '). ' at '.date('H:i A').' Ref.No: '.$ref_num.' Transaction cost '.CURRENCY_SYMBOLE.'0.00';

		                            $notification_type = 2; // Use For Deposit Money
		                            if(!empty($User_Role['Device_Id']) && $User_Role['Device_Type'] == 'android')
		                            { 
		                                sendPushAndroid($notification_to,$notification_title, $notification_type );
		                            }
		                            if(!empty($User_Role['Device_Id']) && $User_Role['Device_Type'] == 'ios' )
		                            {
		                               sendPushIos($notification_to,$notification_title, $notification_type );
		                            }

			                        //Insert Notification
			                        $notiDataArr = array('Recepient_Id'=>$userid,'Notification_Text' =>$notification_title, 'Tran_Type_Id' =>$notification_type) ;
			                        $insert_notification = $this->dynamic_model->insertdata('user_notifications', $notiDataArr);

		        					$response    = array('amount' => number_format($amount,2),'transaction_date'=>date('d M Y'),'transaction_id'=>$ref_num,'wallet_amount'=>$update_user['Current_Wallet_Balance']);

		        					$arg['status']  = 1;
					  				$arg['message'] = $this->lang->line('addmoney_success');
					  				$arg['data']    = $response;
								}
						    }
						}
			    	}
			    }
			}
		}
	    echo json_encode($arg);
	}

	//Function used for send money
    public function sendMoney()
    {
		$result = check_authorization();
		if($result != 'true')
		{
			$arg['status']  = 101;
			$arg['error']   = 461;
			$arg['message'] = $result;
		}
		else{

    	$arg  = array();
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
				
				// else
				// {
			    	$_POST = $this->input->post();

					if($_POST == [])
					{
						$_POST = json_decode(file_get_contents("php://input"), true);
					}
					if($_POST)
					{
						$arg = array();
						//$this->form_validation->set_rules('amount[]', 'Amount', 'required|numeric|greater_than[0]');
						//$this->form_validation->set_rules('mobileEmail[]','Email or Mobile','required');
						$this->form_validation->set_rules('pin', 'Transaction pin', 'required|min_length[4]|max_length[4]|numeric');
						if ($this->form_validation->run() == FALSE)
						{
						  	$arg['status']  = 0;
						  	$arg['error']   = ERROR_FAILED_CODE;
						  	$arg['message'] = get_form_error($this->form_validation->error_array());
						}
						else
						{
							/*  
								send_money_method = 1 means Debit card
					        	send_money_method = 2 means Credit card 
					        	send_money_method = 3 means Net banking
					        	send_money_method = 4 means Saved card details
					        	send_money_method = 5 means Wallet
					     	*/
					        $send_money_method = $this->input->post('send_money_method');
					        $use_my_wallet     = $this->input->post('use_my_wallet');
					        $request_info      = $this->input->post('request_info');
					        //$amount            = $this->input->post('amount');
					        //$mobileEmail       = $this->input->post('mobileEmail');
					        //$commMsg           = $this->input->post('commMsg');
					        //$charge            = $this->input->post('charge');
					        $pin               = $this->input->post('pin');
					        $ref_num           = getuniquenumber();
					        //$mobileEmailCount  = count($mobileEmail);
							// $request_info = explode(',', $request_info);
							// var_dump($request_info);
							// die;
					        //$request_info_count = count($request_info);
					        $i=1;
			                $j=0;
			                $k=0;
			                $flag=1;

					        //Check Transaction Password 
			        		$userid  = getuserid();
							$loguser = $this->dynamic_model->get_user_by_id($userid);
							$mobile  = array_column($request_info, 'mobileEmail');
							if ((in_array($loguser['Mobile_No'], $mobile)) || (in_array($loguser['Email'], $mobile)))
							{
								$arg['status']  = 0;
								$arg['error']   = ERROR_FAILED_CODE;
			                    $arg['message'] = $this->lang->line('not_sendmoney_youself');
			                    echo json_encode($arg);die();
							}
			                else
			                {
								/*if($loguser['Transaction_Password'] != encrypt_password($pin))
				                {
				                    $arg['status']  = 0;
				                    $arg['error']   = ERROR_PIN_CODE;
				                    $arg['message'] = $this->lang->line('invalid_pin');
				                }
				                else
				                {*/
			                		$amount         = array_column($request_info, 'amount');
				                    $charge         = array_column($request_info, 'charge');
	                                $total_req_amt  = array_sum($amount) + array_sum($charge);

				                	//Check Daily limit or count
									$limit = check_limit($total_req_amt,$userid,"daily",3);
									if($limit == false)
									{
										$arg['status']  = 0;
										$arg['error']   = ERROR_FAILED_CODE;
										$arg['message'] = $this->lang->line('daily_limit_exceed');
										echo json_encode($arg);die;
									}

									//Check Monthly limit or count
									$monthly_limit = check_limit($total_req_amt,$userid,"monthly",3);
									if($monthly_limit == false)
									{
										$arg['status']  = 0;
										$arg['error']   = ERROR_FAILED_CODE;
										$arg['message'] = $this->lang->line('monthly_limit_exceed');
										echo json_encode($arg);die;
									}

							        // Use these function for Debit or Credit card method
							        if($send_money_method == 1 || $send_money_method == 2)
							        {
							        	$this->form_validation->set_rules('security_code', 'Security Code', 'required|numeric|min_length[3]|max_length[3]');
							        	$this->form_validation->set_rules('card_number', 'Card Number', 'required|numeric|min_length[16]|max_length[16]');
							        	$this->form_validation->set_rules('expiry_month', 'Expiry Month', 'required|numeric|less_than_equal_to[12]|greater_than[0]|min_length[2]');
			        					$this->form_validation->set_rules('expiry_year', 'Expiry Year', 'required|numeric|min_length[4]|max_length[4]');
							        }
							        // Use these function for Bank method
							        // if($add_money_method == 3)
							        // {
							        // 	$this->form_validation->set_rules('bank_name', 'Bank Name', 'required');
							        // 	$this->form_validation->set_rules('acc_number', 'Account Number', 'required|numeric|min_length[5]|max_length[14]');
							        // 	$this->form_validation->set_rules('branch_name', 'Branch Name', 'required');
							        // }
							        // Use these function for Saved card details method
							        if($send_money_method == 4)
							        {
							        	$this->form_validation->set_rules('security_code', 'Security Code', 'required|numeric|min_length[3]|max_length[3]');
							        	$this->form_validation->set_rules('save_card_id', 'Saved Card Id', 'required|numeric');
							        }
									if($this->form_validation->run() == FALSE)
									{
									  	$arg['status']  = 0;
									  	$arg['error']   = ERROR_FAILED_CODE;
									  	$arg['message'] = get_form_error($this->form_validation->error_array());
									}
									else
									{
										if($send_money_method == 1 || $send_money_method == 2)
							        	{
											$security_code    = $this->input->post('security_code');
								            $card_number      = $this->input->post('card_number');
								            $expiry_month     = $this->input->post('expiry_month');
								            $expiry_year      = $this->input->post('expiry_year');
								            $save_card_check  = $this->input->post('save_card_check');

						                	$FirstFourNumber = substr($card_number, 0, 4); // get first 4
						                    $LastFourNumber  = substr($card_number, 12, 4); // get last 4
						                    $newCardNumber   = $FirstFourNumber.' XXXX XXXX '.$LastFourNumber;

						                    // check year is valid
									        if(check_expiry_year($expiry_year) == false)
									        {
									        	$arg['status']  = 0;
									        	$arg['error']   = ERROR_FAILED_CODE;
						                        $arg['message'] = $this->lang->line('invalid_expiry_year');
									            echo json_encode($arg);die;
									        }

									        // check year is valid
									        if(check_expiry_month_year($expiry_month,$expiry_year) == false)
									        {
									        	$arg['status']  = 0;
									        	$arg['error']   = ERROR_FAILED_CODE;
						                        $arg['message'] = $this->lang->line('invalid_expiry_year_month');
									            echo json_encode($arg);die;
									        }
						                }

							        	if($send_money_method == 4)
							        	{
								            $security_code = $this->input->post('security_code');
								            $save_card_id  = $this->input->post('save_card_id');
							        	}
					                    $t = time();

					                    if($send_money_method == 1)
					                    {
					                    	$card_Exist = $this->dynamic_model->get_row('user_payment_methods',array('User_Id'=> $userid,'Card_Bank_No'=>$newCardNumber,'Is_Debit_Card'=>1));
					                    }
					                    if($send_money_method == 2)
					                    {
					                    	$card_Exist = $this->dynamic_model->get_row('user_payment_methods',array('User_Id'=> $userid,'Card_Bank_No'=>$newCardNumber,'Is_Credit_Card'=>1));
					                    }

					                    if($send_money_method == 4)
					                    {
					                    	$card_Exist = $this->dynamic_model->get_row('user_payment_methods',array('User_Id'=> $userid,'Id'=>$save_card_id));
					                    }
					                    if(empty($card_Exist))
					                    {
					                        // Use for faster checkout
					                        if(@$save_card_check == 1)
					                            $del_status = 0;
					                        else
					                            $del_status = 1;

					                        if($send_money_method == 1)
					                        {
						                        // Firstly insert data into user_payment_methods table
						                        $debitcardDetailArr = array(
								                                    'User_Id'          =>$userid,
								                                    'Card_Bank_No'     =>$newCardNumber,
								                                    'Expiry_Month_Year'=>$expiry_month.'-'.$expiry_year,
								                                    'Is_Debit_Card'    =>1,
								                                    'Is_Deleted'       =>$del_status,
								                                    'Created_By'       =>$userid,
								                                    'Last_Updated_By'  =>$userid
						                            			);
						                        $payment_id = $this->dynamic_model->insertdata('user_payment_methods', $debitcardDetailArr);
						                    }
						                    if($send_money_method == 2)
					                        {
						                        // Firstly insert data into user_payment_methods table
						                        $creditcardDetailArr = array(
								                                    'User_Id'          =>$userid,
								                                    'Card_Bank_No'     =>$newCardNumber,
								                                    'Expiry_Month_Year'=>$expiry_month.'-'.$expiry_year,
								                                    'Is_Credit_Card'   =>1,
								                                    'Is_Deleted'       =>$del_status,
								                                    'Created_By'       =>$userid,
								                                    'Last_Updated_By'  =>$userid
						                            			);
						                        $payment_id = $this->dynamic_model->insertdata('user_payment_methods', $creditcardDetailArr);
						                    }
					                    }
					                    else
					                    {
					                        $payment_id = $card_Exist['Id'];
					                    }

					                    // $amount         = array_column($request_info, 'amount');
					                    // $charge         = array_column($request_info, 'charge');
		                       			// $total_req_amt  = array_sum($amount) + array_sum($charge);

		                                if($send_money_method == 4 || $send_money_method == 1 || $send_money_method == 2)
			                            {
			                            	if($use_my_wallet == "1")
								        	{
								       			//Get remaining balance
								            	$remaining_amount = $total_req_amt - $loguser['Current_Wallet_Balance'];
								        	}
								        	else
								        	{
								        		$remaining_amount = $total_req_amt;
								        	}
						                    //Then after insert into Transactions table
					                        $paymentaddArr = array(
					                                                'Tran_Type_Id'         =>2, //deposit money
					                                                'To_Payment_Method_Id' =>$payment_id,
					                                                'Amount'               =>$remaining_amount,
					                                                'To_User_Id'           =>$userid,
					                                                'Tran_Status_Id'       =>6,
					                                                'Sig'                  =>'+',
					                                                'Amount_Received'      =>$remaining_amount,
					                                                'Third_Party_Tran_Id'  =>$ref_num,
					                                                'Created_By'           =>$userid,
					                                                'Last_Updated_By'      =>$userid
					                                        	);
					                        $Transaction_id = $this->dynamic_model->insertdata('transactions', $paymentaddArr);

					                        //Then after insert into Tran_Charges table
					                        $chargeaddArr = array(
					                                                'Transaction_Id'  =>$Transaction_id,
					                                                'Charge_Type_Id'  =>0,
					                                                'Charge_Amt'      =>0,
					                                                'Created_By'      =>$userid,
					                                                'Last_Updated_By' =>$userid
					                                        	);
					                        $Transaction_charge_id = $this->dynamic_model->insertdata('tran_charges', $chargeaddArr);

					                        //Update User current wallet balance
					                        $total_amount = $loguser['Current_Wallet_Balance'] + $remaining_amount;
					                        $userbalancedata['Current_Wallet_Balance'] = $total_amount;
				        					$updatebalancedata = $this->dynamic_model->updatedata('users', $userbalancedata, $userid);
				        				}

			        					// NOTE :-start code for Send Money
										$i=0;
			                            // for( $i=0; $i<=$request_info_count; $i++ )
			                            // {
			                            	$update_user = $this->dynamic_model->get_user_by_id($userid);
			                            	$received_amount = (array_key_exists("amount",$request_info[$j]) ? $request_info[$j]['amount'] : 0) + (array_key_exists("charge",$request_info[$j]) ? $request_info[$j]['charge'] : 0);
			                            	// Check send amount limit exceed
											
			                            	if($received_amount <= $update_user['Current_Wallet_Balance'])
		                        			{
				                                $t           = time();
				                                $mobilemail  = array_key_exists("mobileEmail",$request_info[$j]) ? $request_info[$j]['mobileEmail'] : '';
				                                $country_id =  array_key_exists("country_id",$request_info[$j]) ?  @$request_info[$j]['country_id'] : '';
												$userDetail = $this->dynamic_model->check_userdetails($mobilemail);
						
				                                if(!empty($userDetail))
			                                	{
			                                		/* Send money from one user to another user  */

													// print_r($request_info[0]["mobileEmail"]);
													// die;
			                                		//Then after insert intoTransactions table
							                        $paymentaddArr = array(
							                                                'Tran_Type_Id'         =>3, //send money
							                                                'To_Payment_Method_Id' =>0,
							                                                'Amount'               => array_sum($amount),
							                                                'Charge'               => array_sum($charge),
							                                                'Msg'                  => array_key_exists("commMsg",$request_info) ? $request_info[$j]['commMsg'] : '',
							                                                'To_User_Id'           =>$userid,
							                                                'From_User_Id'         =>$userDetail['Id'],
							                                                'Tran_Status_Id'       =>6,
							                                                'Sig'                  =>'-',
							                                                'Amount_Received'      => array_key_exists("amount",$request_info[$j]) ? $request_info[$j]['amount'] : 0,
							                                                'Third_Party_Tran_Id'  =>$ref_num,
							                                                'Created_By'           =>$userid,
							                                                'Last_Updated_By'      =>$userid
							                                        	);
							                        $Transaction_id = $this->dynamic_model->insertdata('transactions', $paymentaddArr);

							                        //Then after insert into Tran_Charges table
							                        $chargeaddArr = array(
							                                                'Transaction_Id'  =>$Transaction_id,
							                                                'Charge_Type_Id'  =>0,
							                                                'Charge_Amt'      => array_key_exists("charge",$request_info) ? $request_info[$j]['charge'] : 0,
							                                                'Created_By'      =>$userid,
							                                                'Last_Updated_By' =>$userid
							                                        	);
							                        $Transaction_charge_id = $this->dynamic_model->insertdata('tran_charges', $chargeaddArr);

							                        /* Receive money to another user from sended user  */

							                        //Then after insert into Transactions table
							                        $paymentaddArr = array(
							                                                'Tran_Type_Id'         =>4, //receive money
							                                                'To_Payment_Method_Id' =>0,
							                                                'Amount'               => array_sum($amount),
							                                                'To_User_Id'           =>$userDetail['Id'],
							                                                'From_User_Id'         =>$userid,
							                                                'Tran_Status_Id'       =>6,
							                                                'Sig'                  =>'+',
							                                                'Amount_Received'      => array_sum($amount),
							                                                'Third_Party_Tran_Id'  =>$ref_num,
							                                                'Created_By'           =>$userid,
							                                                'Last_Updated_By'      =>$userid
							                                        	);
							                        $Transaction_id = $this->dynamic_model->insertdata('transactions', $paymentaddArr);

							                        //Then after insert into Tran_Charges table
							                        $chargeaddArr = array(
							                                                'Transaction_Id'  =>$Transaction_id,
							                                                'Charge_Type_Id'  =>0,
							                                                'Charge_Amt'      =>0,
							                                                'Created_By'      =>$userid,
							                                                'Last_Updated_By' =>$userid
							                                        	);
							                        $Transaction_charge_id = $this->dynamic_model->insertdata('tran_charges', $chargeaddArr);

			                                        //update amount into users table (deduct amount from sender and update wallet amount)
							                        
													// $total_wallet_amount = $update_user['Current_Wallet_Balance'] - $received_amount;
													$userwalletdata['Current_Wallet_Balance'] = $update_user['Current_Wallet_Balance'] - $received_amount;
						        					$updatewalletdata = $this->dynamic_model->updatedata('users', $userwalletdata, $userid);
													// var_dump($userwalletdata);
													// die;
			                                        //update amount into users table (add amount to receiver and update wallet amount)
			                                        $total_receiver_wallet = $userDetail['Current_Wallet_Balance'] + $received_amount;
							                        $receiverwalletdata['Current_Wallet_Balance'] = $total_receiver_wallet;
						        					$updatereceiverwallet = $this->dynamic_model->updatedata('users', $receiverwalletdata, $userDetail['Id']);
													
						        					$updated_user_wallet = $this->dynamic_model->get_user_by_id($userid);
													// var_dump($updated_user_wallet);
													// die;
			                                	}
				                                else
				                                {
				                                	//For Unregistered User Case
				                                	$flag = 1;
				                                	$virtual_exist = $this->dynamic_model->getdatafromtable('users', array('Mobile_No'=>$mobilemail));
				                                	if(empty($virtual_exist))
				                                	{
					                                	$userdata = array(
																       	    'Mobile_No' => $mobilemail,
																	        // 'Country_Id'  => $country_id,
																	        'Profile_Pic' => 'default.jpg',
																	        'Is_Virtual'  => 1
																	    );
											        	$id = $this->dynamic_model->insertdata('users', $userdata);
											        }

										        	$userDetail = $this->dynamic_model->check_nonreguserdetails($mobilemail);

				                                	/* Send money from one user to another user  */

			                                		//Then after insert into Transactions table
							                        $paymentaddArr = array(
							                                                'Tran_Type_Id'         =>3, //send money
							                                                'To_Payment_Method_Id' =>0,
							                                                'Amount'               =>$request_info[$j]['amount'],
							                                                'Charge'               =>$request_info[$j]['charge'],
							                                                'Msg'                  =>array_key_exists("commMsg",$request_info) ? $request_info[$j]['commMsg'] : '',
							                                                'To_User_Id'           =>$userid,
							                                                'From_User_Id'         =>$userDetail['Id'],
							                                                'Tran_Status_Id'       =>6,
							                                                'Sig'                  =>'-',
							                                                'Amount_Received'      =>$request_info[$j]['amount'],
							                                                'Third_Party_Tran_Id'  =>$ref_num,
							                                                'Created_By'           =>$userid,
							                                                'Last_Updated_By'      =>$userid
							                                        	);
							                        $Transaction_id = $this->dynamic_model->insertdata('transactions', $paymentaddArr);

							                        //Then after insert into Tran_Charges table
							                        $chargeaddArr = array(
							                                                'Transaction_Id'  =>$Transaction_id,
							                                                'Charge_Type_Id'  =>0,
							                                                'Charge_Amt'      =>$request_info[$j]['charge'],
							                                                'Created_By'      =>$userid,
							                                                'Last_Updated_By' =>$userid
							                                        	);
							                        $Transaction_charge_id = $this->dynamic_model->insertdata('tran_charges', $chargeaddArr);

							                        /* Receive money to another user from sended user  */

							                        //Then after insert into Transactions table
							                        $paymentaddArr = array(
							                                                'Tran_Type_Id'         =>4, //receive money
							                                                'To_Payment_Method_Id' =>0,
							                                                'Amount'               =>$request_info[$j]['amount'],
							                                                'To_User_Id'           =>$userDetail['Id'],
							                                                'From_User_Id'         =>$userid,
							                                                'Tran_Status_Id'       =>6,
							                                                'Sig'                  =>'+',
							                                                'Amount_Received'      =>$request_info[$j]['amount'],
							                                                'Third_Party_Tran_Id'  =>$ref_num,
							                                                'Created_By'           =>$userid,
							                                                'Last_Updated_By'      =>$userid
							                                        	);
							                        $Transaction_id = $this->dynamic_model->insertdata('transactions', $paymentaddArr);

							                        //Then after insert into Tran_Charges table
							                        $chargeaddArr = array(
							                                                'Transaction_Id'  =>$Transaction_id,
							                                                'Charge_Type_Id'  =>0,
							                                                'Charge_Amt'      =>0,
							                                                'Created_By'      =>$userid,
							                                                'Last_Updated_By' =>$userid
							                                        	);
							                        $Transaction_charge_id = $this->dynamic_model->insertdata('tran_charges', $chargeaddArr);

			                                        //update amount into users table (deduct amount from sender and update wallet amount)
							                        // $total_wallet_amount = $update_user['Current_Wallet_Balance'] - $received_amount;
							                        $userwalletdata['Current_Wallet_Balance'] = $update_user['Current_Wallet_Balance'] - $received_amount;
						        					$updatewalletdata = $this->dynamic_model->updatedata('users', $userwalletdata, $userid);

													// var_dump($userwalletdata);
													// die;
			                                        //update amount into users table (add amount to receiver and update wallet amount)
			                                        $total_receiver_wallet = $userDetail['Current_Wallet_Balance'] + $request_info[$j]['amount'];
							                        $receiverwalletdata['Current_Wallet_Balance'] = $total_receiver_wallet;
						        					$updatereceiverwallet = $this->dynamic_model->updatedata('users', $receiverwalletdata, $userDetail['Id']);

						        					$updated_user_wallet = $this->dynamic_model->get_user_by_id($userid);
				                                	//$arg['status']  = 0;
				                                	//$arg['error']   = ERROR_FAILED_CODE;
				                    				//$arg['message'] = $this->lang->line('mobile_invalid');
				                                }

				                                //Notification functionality
				                                //Send Notification For Sender
			        							$User_Role = $this->dynamic_model->get_row('user_in_roles',array('User_Id'=> $userid));
				                                $myname                 = ucfirst($loguser['FullName']);
				                                $notification_to_sender = $User_Role['Device_Id'];
				                                $sender_name            = ucfirst(strtolower($userDetail['FullName']));

			                                   	$notification_type_sender  = 3; //Send Money

												// var_dump($j);
			                                   	$notification_title_sender = 'Dear '.$myname.',You have successfully sent '.CURRENCY_SYMBOLE.''.number_format($request_info[$j]['amount'], 2).' to '.$sender_name.' '.$userDetail['Mobile_No'].' on '.date('d/m/Y '). ' at '.date('H:i A').' Ref.No: '.$ref_num.' Transaction cost '.CURRENCY_SYMBOLE.'0.00';

												
												$subject = 'You have received cash successfully';
												$token = $loguser['Device_Token'];
												$notification_setting = null;
												$message = 'Dear '.$myname.',You have successfully sent '.CURRENCY_SYMBOLE.''.number_format($request_info[$j]['amount'], 2).' to '.$sender_name.' '.$userDetail['Mobile_No'].' on '.date('d/m/Y '). ' at '.date('H:i A').' Ref.No: '.$ref_num.' Transaction cost '.CURRENCY_SYMBOLE.'0.00';

												$type = 'android';
												$send_data = array('title' => $subject, 'message' => $message, 'token' => $token, 'notification_setting' => $notification_setting,'user_id' => $userid);
											
												// $st = self::androidPush($send_data);
												if (!empty($loguser['Device_Token']) && strtolower($loguser['Device_Type']) == 'android') {
													$st = androidPush($send_data);
												}
												if (!empty($loguser['Device_Token']) && strtolower($loguser['Device_Type']) == 'ios') {
													$st = iosPush($send_data);
												}

			                                    // if(!empty($User_Role['Device_Id']) && $User_Role['Device_Type'] == 'android' )
			                                    // {
			                                    //     sendPushAndroid($notification_to_sender,$notification_title_sender, $notification_type_sender );
			                                    // }
			                                    // if(!empty($User_Role['Device_Id']) && $User_Role['Device_Type'] == 'ios' )
			                                    // {
			                                    //     sendPushIos($notification_to_sender,$notification_title_sender, $notification_type_sender );
			                                    // }

			                                    //Insert Notification
						                        $notiDataArr = array('Recepient_Id'=>$userid,'Notification_Text' =>$notification_title_sender, 'Tran_Type_Id' =>$notification_type_sender);
						                        $insert_notification = $this->dynamic_model->insertdata('user_notifications', $notiDataArr);

				                                //Send Notification For Receiver
												$Rec_User = $this->dynamic_model->get_row('users',array('Id'=> $userDetail['Id']));
				                                
				                                $Rec_User_Role = $this->dynamic_model->get_row('user_in_roles',array('User_Id'=> $userDetail['Id']));
				                                $receiver_name = ucfirst(strtolower($loguser['FirstName'].' '.$loguser['LastName'])).' '.$loguser['Mobile_No'];
				                                $notification_to_receiver = $Rec_User_Role ? $Rec_User_Role['Device_Id'] : '';

				                                $notification_title_receiver = 'Dear '.$sender_name.',You have successfully received '.CURRENCY_SYMBOLE.''.number_format($request_info[$j]['amount'], 2).' from '.$receiver_name.' on '.date('d-m-Y '). ' at '.date('H:i A').' Ref.No: '.$ref_num.' Your new balance is '.CURRENCY_SYMBOLE.''.number_format((float)@$total_receiver_wallet, 2, '.', '');

				                                $notification_type_receiver = 4; //Receive Money


												$subject = 'You have received cash successfully';
												$token = $Rec_User['Device_Token'];
												$notification_setting = null;
												$message = 'Dear '.$sender_name.',You have successfully received '.CURRENCY_SYMBOLE.''.number_format($request_info[$j]['amount'], 2).' from '.$receiver_name.' on '.date('d-m-Y '). ' at '.date('H:i A').' Ref.No: '.$ref_num.' Your new balance is '.CURRENCY_SYMBOLE.''.number_format((float)@$total_receiver_wallet, 2, '.', '');

												$type = 'android';
												$send_data = array('title' => $subject, 'message' => $message, 'token' => $token, 'notification_setting' => $notification_setting,'user_id' => $userDetail['Id']);
												// var_dump($Rec_User['Device_Token']);
												// die;
												// $st = self::androidPush($send_data);
												if (!empty($Rec_User['Device_Token']) && strtolower($Rec_User['Device_Type']) == 'android') {
													$st = androidPush($send_data);
												}
												if (!empty($Rec_User['Device_Token']) && strtolower($Rec_User['Device_Type']) == 'ios') {
													$st = iosPush($send_data);
												}
			                                    // if(!empty($Rec_User_Role['Device_Id']) && $Rec_User_Role['Device_Type'] == 'android' )
			                                    // {
			                                    //     sendPushAndroid($notification_to_receiver,$notification_title_receiver, $notification_type_receiver );
			                                    // }
			                                    // if(!empty($Rec_User_Role['Device_Id']) && $Rec_User_Role['Device_Type'] == 'ios' )
			                                    // {
			                                    //     sendPushIos($notification_to_receiver,$notification_title_receiver, $notification_type_receiver );
			                                    // }

			                                   	//Insert Notification
						                        $notiDataArrRec = array('Recepient_Id'=>$userDetail['Id'],'Notification_Text' =>$notification_title_receiver, 'Tran_Type_Id' =>$notification_type_receiver);
						                        $insert_notification = $this->dynamic_model->insertdata('user_notifications', $notiDataArrRec);

				                                // $j++;
				                                if($flag)
				                                {
					                                $response  = array('amount' => number_format($total_req_amt,2),'transaction_date'=>date('d M Y'),'transaction_id'=>$ref_num,'wallet_amount'=>number_format($updated_user_wallet['Current_Wallet_Balance'],2));

					        						$arg['status']  = 1;
								  					$arg['message'] = $this->lang->line('send_money_success');
								  					$arg['data']    = $response;
								  				}
							  				}
							  				else
							  				{
							  					$arg['status']  = 0;
							  					$arg['error']   = ERROR_FAILED_CODE;
				                    			$arg['message'] = $this->lang->line('send_money_limit_exceed');
							  				}
			                            // }
									}
							    //}
							}
						}
			    	}
			    // }
			}
		}
		}
	    echo json_encode($arg);
	}

	//Function used for withdraw money
    public function withdrawMoney()
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
						$this->form_validation->set_rules('amount', 'Amount', 'required|greater_than[0]');
						$this->form_validation->set_rules('pin', 'Transaction pin', 'required|min_length[4]|max_length[4]|numeric');
						if ($this->form_validation->run() == FALSE)
						{
						  	$arg['status']  = 0;
						  	$arg['error']   = ERROR_FAILED_CODE;
						  	$arg['message'] = get_form_error($this->form_validation->error_array());
						}
						else
						{
							/*  withdraw_money_method = 2 means merchant 
					        	withdraw_money_method = 3 means Bank
					     	*/
					        $withdraw_money_method = $this->input->post('withdraw_money_method');
					        $amount                = $this->input->post('amount');
					        $charge                = $this->input->post('charge');
					        $pin                   = $this->input->post('pin');
					        $ref_num               = getuniquenumber();

					        //Check Transaction Password 
			        		$userid  = getuserid();
							$loguser = $this->dynamic_model->get_user_by_id($userid);
							if($loguser['Transaction_Password'] != encrypt_password($pin))
			                {
			                    $arg['status']  = 0;
			                    $arg['error']   = ERROR_PIN_CODE;
			                    $arg['message'] = $this->lang->line('invalid_pin');
			                }
			                else
			                {
						        // Use these function for Bank method
						        if($withdraw_money_method == 3)
						        {
						        	$this->form_validation->set_rules('bank_name', 'Bank Name', 'required');
						        	//$this->form_validation->set_rules('acc_number', 'Account Number', 'required|numeric|min_length[5]|max_length[14]');
						        	$this->form_validation->set_rules('branch_name', 'Branch Name', 'required');
						        }
								if ($this->form_validation->run() == FALSE)
								{
								  	$arg['status']  = 0;
								  	$arg['error']   = ERROR_FAILED_CODE;
								  	$arg['message'] = get_form_error($this->form_validation->error_array());
								}
								else
								{
									// Use these function for Bank method
					                if($withdraw_money_method == 3)
						        	{
						    //     		//Check Daily limit or count
										// $limit = check_limit($amount,$userid,"daily",1);
										// if($limit == false)
										// {
										// 	$arg['status']  = 0;
										// 	$arg['error']   = ERROR_FAILED_CODE;
										// 	$arg['message'] = $this->lang->line('daily_limit_exceed');
										// 	echo json_encode($arg);die;
										// }

										// //Check Monthly limit or count
										// $monthly_limit = check_limit($amount,$userid,"monthly",1);
										// if($monthly_limit == false)
										// {
										// 	$arg['status']  = 0;
										// 	$arg['error']   = ERROR_FAILED_CODE;
										// 	$arg['message'] = $this->lang->line('monthly_limit_exceed');
										// 	echo json_encode($arg);die;
										// }
						        		$bank_name       = $this->input->post('bank_name');
							            $acc_number      = $this->input->post('acc_number');
							            $acc_holder_name = $this->input->post('acc_holder_name');
							            $branch_name     = $this->input->post('branch_name');
							            $deposit_method  = $this->input->post('deposit_method');
							            $save_card_check = $this->input->post('save_card_check');
					                    $t               = time();

					                    if($withdraw_money_method == 3)
					                    {
					                    	$card_Exist = $this->dynamic_model->get_row('user_payment_methods',array('User_Id'=> $userid,'Bank_Name'=>$bank_name,'Account_No'=>$acc_number,'Is_Bank'=>1));
					                    }
					                    if(empty($card_Exist))
					                    {
					                        // Use for faster checkout
					                        if(@$save_card_check == 1)
					                            $del_status = 0;
					                        else
					                            $del_status = 1;

						                    if($withdraw_money_method == 3)
					                        {
						                        // Firstly insert data into user_payment_methods table
						                        $bankDetailArr = array(
								                                    'User_Id'             =>$userid,
								                                    'Bank_Name'    =>$bank_name,
								                                    'Account_No'          =>$acc_number,
								                                    'Acc_Holder_Name'     =>$acc_holder_name,
								                                    'Branch_Name'         =>$branch_name,
								                                    'Bank_Deposit_Method' =>$deposit_method,
								                                    'Is_Bank'             =>1,
								                                    'Is_Deleted'          =>$del_status,
								                                    'Created_By'          =>$userid,
								                                    'Last_Updated_By'     =>$userid
						                            			);
						                        $payment_id = $this->dynamic_model->insertdata('user_payment_methods', $bankDetailArr);
						                    }
					                    }
					                    else
					                    {
					                        $payment_id = $card_Exist['Id'];
					                    }

					                    $received_amount = $amount + $charge;

			                    	    // Check send amount limit exceed
			                        	if($received_amount <= $loguser['Current_Wallet_Balance'])
			                			{
						                    //Then after insert into Transactions table
					                        $paymentaddArr = array(
					                                                'Tran_Type_Id'         =>1, //withdraw money
					                                                'To_Payment_Method_Id' =>$payment_id,
					                                                'Amount'               =>$amount,
					                                                'Charge'               =>$charge,
					                                                'To_User_Id'           =>$userid,
					                                                'Tran_Status_Id'       =>6,
					                                                'Sig'                  =>'-',
					                                                'Amount_Received'      =>$amount,
					                                                'Third_Party_Tran_Id'  =>$ref_num,
					                                                'Created_By'           =>$userid,
					                                                'Last_Updated_By'      =>$userid
					                                        	);
					                        $Transaction_id = $this->dynamic_model->insertdata('transactions', $paymentaddArr);

					                        //Then after insert into Tran_Charges table
					                        $chargeaddArr = array(
					                                                'Transaction_Id'  =>$Transaction_id,
					                                                'Charge_Type_Id'  =>0,
					                                                'Charge_Amt'      =>$charge,
					                                                'Created_By'      =>$userid,
					                                                'Last_Updated_By' =>$userid
					                                        	);
					                        $Transaction_charge_id = $this->dynamic_model->insertdata('tran_charges', $chargeaddArr);

					                        //Update User current wallet balance
					                        $total_amount = $loguser['Current_Wallet_Balance'] - $received_amount;
					                        $userbalancedata['Current_Wallet_Balance'] = $total_amount;
				        					$updatebalancedata = $this->dynamic_model->updatedata('users', $userbalancedata, $userid);

				        					$update_user = $this->dynamic_model->get_user_by_id($userid);

				        					//Notification functionality
				        					$User_Role = $this->dynamic_model->get_row('user_in_roles',array('User_Id'=> $userid));

				        					$User_Bank = $this->dynamic_model->get_row('user_payment_methods',array('Id'=> $payment_id));

				                            $notification_to = $User_Role['Device_Id'];
				                            $sender_name     = strtoupper($loguser['FullName']);

				                            $notification_title = 'Dear '.$sender_name.',You have successfully withdrawn '.CURRENCY_SYMBOLE.''.number_format($amount, 2).' from '.$User_Bank['Bank_Name'].' on '.date('d-m-Y '). 'at '.date('H:i A').' Ref No.'.$ref_num;

				                            $notification_type = 1; // Use For Withdraw Money
				                            if(!empty($User_Role['Device_Id']) && $User_Role['Device_Type'] == 'android')
				                            { 
				                                sendPushAndroid($notification_to,$notification_title, $notification_type );
				                            }
				                            if(!empty($User_Role['Device_Id']) && $User_Role['Device_Type'] == 'ios' )
				                            {
				                               sendPushIos($notification_to,$notification_title, $notification_type );
				                            }

					                        //Insert Notification
					                        $notiDataArr = array('Recepient_Id'=>$userid,'Notification_Text' =>$notification_title, 'Tran_Type_Id' =>$notification_type) ;
					                        $insert_notification = $this->dynamic_model->insertdata('user_notifications', $notiDataArr);

				        					$response    = array('amount' => number_format($amount, 2),'transaction_date'=>date('d M Y'),'transaction_id'=>$ref_num,'wallet_amount'=>$update_user['Current_Wallet_Balance']);

				        					$arg['status']  = 1;
							  				$arg['message'] = $this->lang->line('withdraw_success');
							  				$arg['data']    = $response;
							  			}
							  			else
							  			{
							  				$arg['status']  = 0;
							  				$arg['error']   = ERROR_FAILED_CODE;
				                    		$arg['message'] = $this->lang->line('withdraw_limit_exceed');
							  			}
							  		}

							  		// Use these function for Merchant method
							  		if($withdraw_money_method == 2)
							  		{
							  			$merchant_number = $this->input->post('merchant_number');
							  			$comment         = $this->input->post('comment');

							  			$condition = array('Users.Mobile_No' => $merchant_number,'user_in_roles.Role_Id'=>3);
							  			$on = 'Users.Id = user_in_roles.User_Id';
							  			$user_info = $this->dynamic_model->getTwoTableData('*','users','user_in_roles',$on,$condition);
							  			if(!empty($user_info))
							  			{
							  				// Check send amount limit exceed
				                        	if($amount <= $loguser['Current_Wallet_Balance'])
				                			{
				                				$paymentRequestArr = array(
													       	'To_User_Id'   => $user_info[0]['User_Id'],
													       	'From_User_Id' => $userid,
													       	'Amount'       => $amount,
													        'Type'         => 'Withdraw_money_req',
													        'Ref_Num'      => $ref_num,
													        'Message'      => $comment,
													        'Tran_Status_Id'=>1,
													        'Created_By'    =>$userid
						    							);
					        					$req_id = $this->dynamic_model->insertdata('request_share', $paymentRequestArr);

					        					//Notification functionality
						                        //Send Notification For Sender
						    					$User_Role = $this->dynamic_model->get_row('user_in_roles',array('User_Id'=> $userid));
							                    $notification_to_sender = $User_Role['Device_Id'];
							                    $receivername           = ucfirst(strtolower($user_info[0]['FullName'])).' '.$user_info[0]['Mobile_No'];
							                    $sender_name            = ucfirst(strtolower($loguser['FullName']));

							                  	$notification_title_sender='Dear '.$sender_name.', You have successfully requested payment of '.CURRENCY_SYMBOLE.''.number_format($amount, 2).' to your wallet on '.date('d-m-Y '). 'at '.date('H:i A').' Ref.No: '.$ref_num.' Transaction cost '.CURRENCY_SYMBOLE.'0.00';

							                    $notification_type_sender = 5; //Request Money
						                        if(!empty($User_Role['Device_Id']) && $User_Role['Device_Type'] == 'android' )
						                        {
							                        sendPushAndroid($notification_to_sender,$notification_title_sender, $notification_type_sender );
							                    }
							                    if(!empty($User_Role['Device_Id']) && $User_Role['Device_Type'] == 'ios' )
							                    {
							                        sendPushIos($notification_to_sender,$notification_title_sender, $notification_type_sender );
							                    }

						                	    //Insert Notification
						                        $notiDataArr = array('Recepient_Id'=>$userid,'Notification_Text' =>$notification_title_sender, 'Tran_Type_Id' =>$notification_type_sender);
						                        //$insert_notification = $this->dynamic_model->insertdata('user_notifications', $notiDataArr);

							                    //Send Notification For Receiver
							                    $Rec_User_Role = $this->dynamic_model->get_row('user_in_roles',array('User_Id'=> $user_info[0]['Id']));
							                    $receiver_name = ucfirst(strtolower($user_info[0]['FullName']));
							                    $sender_name   = ucfirst($loguser['FullName']).' '.$loguser['Mobile_No'];
							                    $notification_to_receiver = $Rec_User_Role['Device_Id'];

							                    $notification_title_receiver = 'Dear '.$receiver_name.', You have received a payment request of '.CURRENCY_SYMBOLE.''.number_format($amount, 2).' from '.$sender_name.' on '.date('d-m-Y '). ' at '.date('H:i A').' Ref.No: '.$ref_num.' Transaction cost '.CURRENCY_SYMBOLE.'0.00';

							                    $notification_type_receiver = 5; // Request Money

						                        if(!empty($Rec_User_Role['Device_Id']) && $Rec_User_Role['Device_Type'] == 'android' )
						                        {
						                            sendPushAndroid($notification_to_receiver,$notification_title_receiver, $notification_type_receiver );
						                        }
						                        if(!empty($Rec_User_Role['Device_Id']) && $Rec_User_Role['Device_Type'] == 'ios' )
						                        {
						                            sendPushIos($notification_to_receiver,$notification_title_receiver, $notification_type_receiver );
						                        }

							                  	//Insert Notification
						                        $notiDataArrRec = array('Recepient_Id'=>$user_info[0]['Id'],'Notification_Text' =>$notification_title_receiver, 'Tran_Type_Id' =>$notification_type_receiver);
												//$insert_notification = $this->dynamic_model->insertdata('user_notifications', $notiDataArrRec);
												$response = array('wallet_amount'=>number_format($loguser['Current_Wallet_Balance'],2),'request_id'=>$req_id,'charge'=>"0.00");

												$arg['status']  = 1;
							  					$arg['message'] = 'Withdraw Payment request send successfully';
							  					$arg['data']    = $response;
								  			}
								  			else
								  			{
								  				$arg['status']  = 0;
								  				$arg['error']   = ERROR_FAILED_CODE;
					                    		$arg['message'] = $this->lang->line('withdraw_limit_exceed');
								  			}
							  			}
							  			else
							  			{
							  				$arg['status']  = 0;
							  				$arg['error']   = ERROR_FAILED_CODE;
								  			$arg['message'] = 'Invalid Merchant Number';
							  			}
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

	//Function used for withdraw request list 
    public function withdrawRequestList()
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
						$this->form_validation->set_rules('page_no', 'Page No', 'required|numeric');
						if ($this->form_validation->run() == FALSE)
						{
						  $arg['status'] = 0;
						  $arg['message'] =  get_form_error($this->form_validation->error_array());
						} 
						else
						{
					    	$usid    = getuserid();
							$loguser = $this->dynamic_model->get_user_by_id($usid);
							$limit   = config_item('page_data_limit'); 
							$offset  = $limit * $this->input->post('page_no');
							$withdrawRequest = $this->dynamic_model->getdatafromtable('request_share', array('Type'=>'Withdraw_money_req','Tran_Status_Id'=>1,'To_User_Id'=>$usid) , '*', $limit, $offset,'Id');
							$total_amount  = "0";
							//$withdrawRequest = $this->dynamic_model->get_rows('request_share',array('Type'=>'Withdraw_money_req','Tran_Status_Id'=>1,'To_User_Id'=>$usid),'*','Id','DESC');
							if($withdrawRequest)
							{
								$user_data     = array();
								$requestArray  = array();
								//$total_request = count($withdrawRequest);
								$total_request = count($this->dynamic_model->getdatafromtable('request_share', array('Type'=>'Withdraw_money_req','Tran_Status_Id'=>1,'To_User_Id'=>$usid)));
								foreach ($withdrawRequest as $request)
								{
									$Requested_user = $this->dynamic_model->get_user_by_id($request['From_User_Id']);

									$newDate                     = date('d M Y', strtotime($request['Creation_Date_Time']));
									$newTime                     = date('g:i A',strtotime($request['Creation_Date_Time']));
									$user_data["id"]             = $request['Id'];
									$user_data["amount"]         = $request['Amount'];
									$user_data["charge"]         = $request['Charge'];
									$user_data["date"]           = $newDate;
									$user_data["time"]           = $newTime;
									$user_data["msg"]            = $request['Message'];
									$user_data["ref_num"]        = $request['Ref_Num'];
									$profilepic                  = isset($Requested_user['Profile_Pic']) ? $Requested_user['Profile_Pic'] : '';
				                    $profile_image               = site_url().'uploads/user/'. $profilepic;
				                    $user_data["user_image"]     = $profile_image;
									$user_data["status"]         = "Pending";
									$user_data["action_message"] = 'Withdraw Request Sent From '.$Requested_user['FullName'];
									$user_data["name"]           = $Requested_user['FirstName'].' '.$Requested_user['LastName'];
									$total_amount                = $total_amount + $request['Amount'];
									$requestArray[]              = $user_data;
								}
								$arg['status']        = 1;
					            $arg['data']          = $requestArray;
					            $arg['total_request'] = "$total_request";
					            $arg['total_amount']  = number_format($total_amount,2);
					            $arg['wallet_amount'] = number_format($loguser['Current_Wallet_Balance'],2);
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
			}
		}
	    echo json_encode($arg);
	}

	//Function used for decline withdraw request
    public function declineWithdrawRequest()
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
						//$this->form_validation->set_rules('pin', 'Transaction pin', 'required|min_length[4]|max_length[4]|numeric');
						$this->form_validation->set_rules('reqwithdrawId', 'Withdraw request money id', 'required|numeric');
						if ($this->form_validation->run() == FALSE)
						{
						  	$arg['status']  = 0;
						  	$arg['error']   = ERROR_FAILED_CODE;
						  	$arg['message'] = get_form_error($this->form_validation->error_array());
						}
						else
						{
							$usid    = getuserid();
							$loguser = $this->dynamic_model->get_user_by_id($usid);
							//$pin     = $this->input->post('pin');

							// if($loguser['Transaction_Password'] != encrypt_password($pin))
			    //             {
			    //                 $arg['status']  = 0;
			    //                 $arg['message'] = $this->lang->line('invalid_pin');
			    //             }
			    //             else
			    //             {
								$reqwithdrawId = $this->input->post('reqwithdrawId');
								$select_reason = $this->input->post('select_reason');
								$actionMessage = $this->input->post('actionMessage');
						        $is_chat       = $this->input->post('is_chat');
						        $type          = $this->input->post('type');
					        	/*  type = 0 means request sent tab
		           			  		type = 1 means request received tab */

								//$paymentRequest = $this->dynamic_model->get_row('request_share',array('Type'=>'Withdraw_money_req','To_User_Id'=>$usid,'Id'=>$reqwithdrawId));
								$paymentRequest = $this->dynamic_model->get_row('request_share',array('Type'=>'Withdraw_money_req','Id'=>$reqwithdrawId));
								if(!empty($paymentRequest))
								{
									if($paymentRequest['Tran_Status_Id'] != "4")
									{
										if($type == "0")
										{
											$updatedata['Tran_Status_Id']  = "7";
										}
										else
										{
											$updatedata['Tran_Status_Id']  = "4";
										}
										//$updatedata['Tran_Status_Id']         = "4";
					                    $updatedata['Message']                = $actionMessage;
					                    $updatedata['Rejection_Reason']       = $select_reason;
					                    $updatedata['Last_Updated_By']        = $usid;
					                    $updatedata['Last_Updated_Date_Time'] = date('Y-m-d H:i:s');

					                    $decline_req = $this->dynamic_model->updatedata('request_share', $updatedata, $reqwithdrawId);
					                    $arg['status']        = 1;
					                    $arg['message']       = $this->lang->line('decline_request_success');
					                    $arg['wallet_amount'] = number_format($loguser['Current_Wallet_Balance'],2);
									}
									else
									{
										$arg['status']  = 0;
										$arg['error']   = ERROR_FAILED_CODE;
										$arg['message'] = $this->lang->line('already_decline_request');
									}
								}
								else
								{
									$arg['status']  = 0;
									$arg['error']   = ERROR_FAILED_CODE;
									$arg['message'] = $this->lang->line('invalid_detail');
								}
							//}
						}
			    	}
			    }
			}
		}
	    echo json_encode($arg);
	}

	//Function used for accept withdraw payment request 
    public function acceptWithdrawRequest()
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
						$this->form_validation->set_rules('pin', 'Transaction pin', 'required|min_length[4]|max_length[4]|numeric');
						$this->form_validation->set_rules('reqwithdrawId', 'Withdraw request money id', 'required|numeric');
						if ($this->form_validation->run() == FALSE)
						{
						  	$arg['status']  = 0;
						  	$arg['error']   = ERROR_FAILED_CODE;
						  	$arg['message'] = get_form_error($this->form_validation->error_array());
						}
						else
						{
							$usid    = getuserid();
							$loguser = $this->dynamic_model->get_user_by_id($usid);
							$pin     = $this->input->post('pin');

							if($loguser['Transaction_Password'] != encrypt_password($pin))
			                {
			                    $arg['status']  = 0;
			                    $arg['error']   = ERROR_PIN_CODE;
			                    $arg['message'] = $this->lang->line('invalid_pin');;
			                }
			                else
			                {
								$reqwithdrawId = $this->input->post('reqwithdrawId');
								$charge        = $this->input->post('charge');
								$actionMessage = $this->input->post('actionMessage');
						        //$is_chat       = $this->input->post('is_chat');
						        $ref_num       = getuniquenumber();
						        $flag = 1;

								$paymentRequest = $this->dynamic_model->get_row('request_share',array('Type'=>'Withdraw_money_req','To_User_Id'=>$usid,'Id'=>$reqwithdrawId));
								if(!empty($paymentRequest))
								{
									if($paymentRequest['Tran_Status_Id'] == "1")
									{
										$otheruser = $this->dynamic_model->get_user_by_id($paymentRequest['From_User_Id']);
										$received_amount = $paymentRequest['Amount'] + $charge;
										if($otheruser['Current_Wallet_Balance']>=$received_amount)
										{
									       	/* Send money from one user to another user  */

			                        		//Then after insert into Transactions table
					                        $paymentaddArr = array(
					                                                'Tran_Type_Id'         =>3, //send money
					                                                'To_Payment_Method_Id' =>0,
					                                                'Amount'               =>$paymentRequest['Amount'],
					                                                'Charge'               =>$charge,
					                                                'To_User_Id'           =>$paymentRequest['From_User_Id'],
					                                                'From_User_Id'         =>$usid,
					                                                'Tran_Status_Id'       =>6,
					                                                'Sig'                  =>'-',
					                                                'Amount_Received'      =>$paymentRequest['Amount'],
					                                                'Third_Party_Tran_Id'  =>$ref_num,
					                                                'Created_By'           =>$usid,
					                                                'Last_Updated_By'      =>$usid
					                                        	);
					                        $Transaction_id = $this->dynamic_model->insertdata('transactions', $paymentaddArr);

					                        //Then after insert into Tran_Charges table
					                        $chargeaddArr = array(
					                                                'Transaction_Id'  =>$Transaction_id,
					                                                'Charge_Type_Id'  =>0,
					                                                'Charge_Amt'      =>0,
					                                                'Created_By'      =>$usid,
					                                                'Last_Updated_By' =>$usid
					                                        	);
					                        $Transaction_charge_id = $this->dynamic_model->insertdata('tran_charges', $chargeaddArr);

					                        /* Receive money to another user from sended user  */

					                        //Then after insert into Transactions table
					                        $paymentaddArr = array(
					                                                'Tran_Type_Id'         =>4, //receive money
					                                                'To_Payment_Method_Id' =>0,
					                                                'Amount'               =>$paymentRequest['Amount'],
					                                                'To_User_Id'           =>$usid,
					                                                'From_User_Id'         =>$paymentRequest['From_User_Id'],
					                                                'Tran_Status_Id'       =>6,
					                                                'Sig'                  =>'+',
					                                                'Amount_Received'      =>$paymentRequest['Amount'],
					                                                'Third_Party_Tran_Id'  =>$ref_num,
					                                                'Created_By'           =>$usid,
					                                                'Last_Updated_By'      =>$usid
					                                        	);
					                        $Transaction_id = $this->dynamic_model->insertdata('transactions', $paymentaddArr);

					                        //Then after insert into Tran_Charges table
					                        $chargeaddArr = array(
					                                                'Transaction_Id'  =>$Transaction_id,
					                                                'Charge_Type_Id'  =>0,
					                                                'Charge_Amt'      =>0,
					                                                'Created_By'      =>$usid,
					                                                'Last_Updated_By' =>$usid
					                                        	);
					                        $Transaction_charge_id = $this->dynamic_model->insertdata('tran_charges', $chargeaddArr);

			                                //update amount into users table (deduct amount from sender and update wallet amount)
					                        $total_wallet_amount = $loguser['Current_Wallet_Balance'] + $paymentRequest['Amount'];
					                        $userwalletdata['Current_Wallet_Balance'] = $total_wallet_amount;
				        					$updatewalletdata = $this->dynamic_model->updatedata('users', $userwalletdata, $usid);

			                                //update amount into users table (add amount to receiver and update wallet amount)
			                                $total_receiver_wallet = $otheruser['Current_Wallet_Balance'] - $received_amount;
					                        $receiverwalletdata['Current_Wallet_Balance'] = $total_receiver_wallet;
				        					$updatereceiverwallet = $this->dynamic_model->updatedata('users', $receiverwalletdata, $otheruser['Id']);

				        					$updated_user_wallet = $this->dynamic_model->get_user_by_id($usid);

				        					//Used function to update request_share table
											$updatedata['Tran_Status_Id']         = "6";
						                    $updatedata['Message']                = $actionMessage;
						                    $updatedata['Tran_Id']                = $Transaction_id;
						                    $updatedata['Last_Updated_By']        = $usid;
						                    $updatedata['Last_Updated_Date_Time'] = date('Y-m-d H:i:s');

						                    $accept_req = $this->dynamic_model->updatedata('request_share', $updatedata, $reqwithdrawId);
						                    $response  = array('amount' => number_format($paymentRequest['Amount'],2),'transaction_date'=>date('d M Y'),'transaction_id'=>$ref_num,'wallet_amount'=>$updated_user_wallet['Current_Wallet_Balance']);

						                    $arg['status']        = 1;
						                    $arg['message']       = $this->lang->line('withdraw_request_accept');
						                    $arg['data']          = $response;
						                    //$arg['wallet_amount'] = $updated_user_wallet['Current_Wallet_Balance'];
						                }
						                else
						                {
						                	$flag = 0;
						                	$arg['status']  = 0;
						                	$arg['error']   = ERROR_FAILED_CODE;
						                	$arg['message'] = $this->lang->line('withdraw_other_user');
						                }
									}
									else
									{
										$arg['status']  = 0;
										$arg['error']   = ERROR_FAILED_CODE;
										$arg['message'] = $this->lang->line('already_withdraw_money');
									}
								}
								else
								{
									$arg['status']  = 0;
									$arg['error']   = ERROR_FAILED_CODE;
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

	//Function used for withdraw request history list 
    public function withdrawRequestHistoryList()
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
						$this->form_validation->set_rules('page_no', 'Page No', 'required|numeric');
						if ($this->form_validation->run() == FALSE)
						{
						  	$arg['status']  = 0;
						  	$arg['error']   = ERROR_FAILED_CODE;
						  	$arg['message'] = get_form_error($this->form_validation->error_array());
						}
						else
						{
							$limit   = config_item('page_data_limit'); 
							$offset  = $limit * $this->input->post('page_no');
					    	$usid    = getuserid();
							$loguser = $this->dynamic_model->get_user_by_id($usid);
							$paymentRequest = $this->users_model->check_request_history($usid,'Withdraw_money_req', $limit, $offset);
							$total_amount = "0";
							//$paymentRequest = $this->dynamic_model->check_request_history($usid,'Withdraw_money_req');
							if($paymentRequest)
							{
								$user_data     = array();
								$requestArray  = array();
								//$total_request = count($paymentRequest);
								$total_request = count($this->users_model->check_request_history($usid,'Withdraw_money_req'));
								foreach ($paymentRequest as $request)
								{
									if($request['To_User_Id'] == $usid)
									{
										$Requested_user = $this->dynamic_model->get_user_by_id($request['From_User_Id']);
										$type           = "1"; //received
									}
									else
									{
										$Requested_user = $this->dynamic_model->get_user_by_id($request['To_User_Id']);
										$type           = "0"; //sent
									}

									$newDate                     = date('d M Y', strtotime($request['Creation_Date_Time']));
									$newTime                     = date('g:i A',strtotime($request['Creation_Date_Time']));
									$user_data["id"]             = $request['Id'];
									$user_data["type"]           = $type;
									$user_data["amount"]         = $request['Amount'];
									$user_data["charge"]         = $request['Charge'];
									$user_data["date"]           = $newDate;
									$user_data["time"]           = $newTime;
									$user_data["msg"]            = $request['Message'];
									$user_data["ref_num"]        = $request['Ref_Num'];
									$user_data["action_message"] = 'Request Sent From '.$Requested_user['FullName'];
									$user_data["name"]           = $Requested_user['FirstName'].' '.$Requested_user['LastName'];
									$user_data['status_id']      = $request['Tran_Status_Id'];
									if($request['Tran_Status_Id'] == "1")
										$user_data["status"]    = "Pending";
									if($request['Tran_Status_Id'] == "2")
										$user_data["status"]    = "Processed";
									if($request['Tran_Status_Id'] == "3")
										$user_data["status"]    = "Hold";
									if($request['Tran_Status_Id'] == "4")
										$user_data["status"]    = "Reject";
									if($request['Tran_Status_Id'] == "5")
										$user_data["status"]    = "Refund";
									if($request['Tran_Status_Id'] == "6")
										$user_data["status"]    = "success";
									if($request['Tran_Status_Id'] == "7")
										$user_data["status"]    = "Cancel";

									$total_amount                = $total_amount + $request['Amount'];
									$requestArray[]              = $user_data;
								}
								$arg['status']        = 1;
					            $arg['data']          = $requestArray;
					            $arg['total_request'] = "$total_request";
					            $arg['total_amount']  = number_format($total_amount, 2);
					            $arg['wallet_amount'] = number_format($loguser['Current_Wallet_Balance'],2);
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
			}
		}
	    echo json_encode($arg);
	}
    //Function used for redeem reffral points
    public function users_redeem_referral_points()
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
					$arg['status']  = 101;;
					$arg['error_code']  = 461;
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
						$arg = array();
						$this->form_validation->set_rules('redeem_point','Redeem point','required|greater_than[0]');
						if ($this->form_validation->run() == FALSE)
						{
						  	$arg['status']  = 0;
						  	$arg['error_code']  = ERROR_FAILED_CODE;
					 	    $arg['error_line']= __line__;
						  	$arg['message'] = get_form_error($this->form_validation->error_array());
						}
						else
						{
							/*  
							  add_money_method using redeem referral point	
					     	*/
					        $ref_num          = getuniquenumber();

					        //Check Transaction Password 
			        		$userid  = getuserid();
							$loguser = $this->dynamic_model->get_user_by_id($userid);
							$redeem_point=$this->input->post('redeem_point');
							if($redeem_point <= $loguser['Total_Referral_Points'])
		                    {
			                    //get referral value
			                    $referral_data = $this->dynamic_model->get_row('refferal_points_settings',[]);
								$referral_points_per_referral_amount=$referral_data['Refferal_Points'];
								$refferal_Amount=$referral_data['Refferal_Amount'];
			                    //Calculate Earn Points
			                    //$earn_money = $redeem_point/10*0.5;
			                    $earn_money = $redeem_point/$referral_points_per_referral_amount*$refferal_Amount;
                                $balance_points = $loguser['Total_Referral_Points']-$redeem_point;
			                    //Then after insert into Transactions table
		                        $paymentaddArr = array(
		                                                'Tran_Type_Id'         =>2, //deposit money
		                                                'To_Payment_Method_Id' =>0,
		                                                'Amount'               =>$earn_money,
		                                                'To_User_Id'           =>$userid,
		                                                'Tran_Status_Id'       =>6,
		                                                'Sig'                  =>'+',
		                                                'Amount_Received'      =>$earn_money,
		                                                'Third_Party_Tran_Id'  =>$ref_num,
		                                                'Redeem_Referral_Point'=>$redeem_point,
		                                                'Balance_Referral_Point'=>$balance_points,
		                                                'Is_Referral_Point'    =>1,
		                                                'Created_By'           =>$userid,
		                                                'Last_Updated_By'      =>$userid
		                                        	);
		                        $Transaction_id = $this->dynamic_model->insertdata('transactions',$paymentaddArr);

		                        //Then after insert into Tran_Charges table
		                        $chargeaddArr = array(
		                                                'Transaction_Id'  =>$Transaction_id,
		                                                'Charge_Type_Id'  =>0,
		                                                'Charge_Amt'      =>0,
		                                                'Created_By'      =>$userid,
		                                                'Last_Updated_By' =>$userid
		                                        	);
		                        $Transaction_charge_id = $this->dynamic_model->insertdata('tran_charges',$chargeaddArr);

		                        //Update User current wallet balance
		                        $total_amount = $loguser['Current_Wallet_Balance'] + $earn_money; 
		                        $userbalancedata['Current_Wallet_Balance'] = $total_amount;
		                        $userbalancedata['Total_Referral_Points'] = $balance_points;

	        					$updatebalancedata = $this->dynamic_model->updatedata('users',$userbalancedata,$userid);
	        					$update_user = $this->dynamic_model->get_user_by_id($userid);
		                        //Insert Notification
                                $amount=number_format((float)$earn_money, 2, '.', '');
                                $full_name=name_format($update_user['FullName']);
		                        $notification_title = 'Dear '.$full_name.',You have successfully added '.CURRENCY_SYMBOLE.''.number_format($amount, 2).' in your wallet on '.date('d-m-Y '). 'at '.date('H:i A').' Ref No.'.$ref_num;
		                       
		                        $notification_type=2;
		                        $notiDataArr = array('Recepient_Id'=>$userid,'Notification_Text' =>$notification_title, 'Tran_Type_Id' =>$notification_type) ;
		                        $insert_notification = $this->dynamic_model->insertdata('user_notifications', $notiDataArr);

	        					$response    = array('amount' => $amount,'transaction_date'=>date('d M Y'),'transaction_id'=>$ref_num,'wallet_amount'=>$update_user['Current_Wallet_Balance'],"total_referral_points"=>$balance_points);

	        					$arg['status']  = 1;
	        					$arg['error_code']   = SUCCESS_CODE;
					 	        $arg['error_line']= __line__;
				  				$arg['message'] = $this->lang->line('addmoney_success');
				  				$arg['data']    = $response;
				  			}else{
								$arg['status']  = 0;
								$arg['error_code']   = ERROR_FAILED_CODE;
					 	        $arg['error_line']= __line__;
				  				$arg['message'] = $this->lang->line('insufficient_referral_point');
							}
						}	

					}

			    }
			}
		}

	    echo json_encode($arg);
	}

   //Function used for Get Points Redeem History 
    public function get_redeem_refferal_points_history(){
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
					$arg['error_code']  = 461;
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
						$this->form_validation->set_rules('page_no','Page No','required|numeric');
						if ($this->form_validation->run() == FALSE)
						{
						  	$arg['status']  = 0;
						  	$arg['error_code']   = ERROR_FAILED_CODE;
						  	$arg['error_line']= __line__;
						  	$arg['message'] = get_form_error($this->form_validation->error_array());
						} 
						else
						{
							$Redeem_Referral_Point=0;
							$transaction_array = array();
							$userid   = getuserid();
							$loguser  = $this->dynamic_model->get_user_by_id($userid);
							$limit    = config_item('page_data_limit'); 
							$offset   = $limit * $this->input->post('page_no');

							$transaction_data = $this->dynamic_model->getdatafromtable('transactions', array('To_User_Id'=>$userid,'Tran_Type_Id'=>2,'Is_Referral_Point'=>1),'*',$limit,$offset,'Id');
							
							 $total_redeem_points= $this->dynamic_model->getdatafromtable('transactions', array('To_User_Id'=>$userid,'Tran_Type_Id'=>2,'Is_Referral_Point'=>1),'SUM(Redeem_Referral_Point) as total_redeem_points');
							 $total_redeem_point=(!empty($total_redeem_points[0]['total_redeem_points']))? $total_redeem_points[0]['total_redeem_points'] : "0" ;
							if(!empty($transaction_data))
							{
								$user_data = array();
								foreach($transaction_data as $details)
			            		{
			            			$transaction_status = $this->dynamic_model->get_row('tran_status',array('Id'=> $details['Tran_Status_Id']));

			            			if($details['From_User_Id'])
			            				$Requested_user = $this->dynamic_model->get_user_by_id($details['From_User_Id']);
			            			if($details['Tran_Type_Id'] == "2")
			            			{
			            				//$user_data["Id"]               = $details['Id'];
			            				$user_data["points_redeem"]      = $details['Redeem_Referral_Point'];
			            				$user_data["points_balance"]     =  $details['Balance_Referral_Point'];
			            				$user_data["order_number"]       = $details['Third_Party_Tran_Id'];
			            				$user_data['created_at']         = date('d M Y', strtotime($details['Creation_Date_Time']));
			            				//$user_data['transaction_status'] = $transaction_status['Status_Name'];
			            				//$user_data['trx_type']           = "Deposit";;
			            				$user_data['time']               = date('g:i A',strtotime($details['Creation_Date_Time']));
			            				$Redeem_Referral_Point+=$details['Redeem_Referral_Point'];
			            				$transaction_array[]             = $user_data;
			            			}
			            		}
			            	}
								$referral_data = $this->dynamic_model->get_row('refferal_points_settings',[]);
								$referral_points_per_referral_amount=$referral_data['Refferal_Points'];
								$refferal_Amount=$referral_data['Refferal_Amount'];

								$arg['status']               = 1;
								$arg['error_code']           = SUCCESS_CODE;
								$arg['error_line']           = __line__;
								$arg['data']                 = $transaction_array;
								$arg['total_referral_points']= $loguser['Total_Referral_Points'];
								$arg['total_redeem_points']  = "$total_redeem_point";
								$arg['referral_points_per_referral_amount']  = "$referral_points_per_referral_amount";
								$arg['refferal_Amount']  = "$refferal_Amount";
								$arg['message']              = $this->lang->line('redeem_point_history_list');
							
							// }else
							// {
							// 	$arg['status']  = 0;
							// 	$arg['error_code']   = ERROR_FAILED_CODE;
							// 	$arg['error_line']= __line__;
				   //          	$arg['message'] = $this->lang->line('record_not_found');
							// }
						}
			    	}
			    }
			}
		}
	    echo json_encode($arg);
	}
	//Function used for Get Points Earn History 
    public function get_earn_refferal_points_history()
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
					$arg['error_code']  = 461;
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
						$this->form_validation->set_rules('page_no','Page No','required|numeric');
						if ($this->form_validation->run() == FALSE)
						{
						  	$arg['status']  = 0;
						  	$arg['error_code']   = ERROR_FAILED_CODE;
						  	$arg['error_line']= __line__;
						  	$arg['message'] = get_form_error($this->form_validation->error_array());
						} 
						else
						{
							$trans_array = array();
							$userid   = getuserid();
							$loguser  = $this->dynamic_model->get_user_by_id($userid);
							$limit    = config_item('page_data_limit'); 
							$offset   = $limit * $this->input->post('page_no');
                            $referral_earn_data = $this->dynamic_model->getdatafromtable('users_referrals', array('Referral_From'=>$userid,'Status'=>1),'*',$limit,$offset,'Id');
							if(!empty($referral_earn_data))
							{
								$user_datas = array();
								foreach($referral_earn_data as $value)
			            		{
                                    $Requested_user = $this->dynamic_model->get_user_by_id($value['Referral_To']);
		            				//$user_data["Id"]                = $details['Id'];
		            				$user_datas["earn_from"]          = $Requested_user['FullName'];
		            				$user_datas["points_earns"]       = $value['Referral_Points'];
		            				$user_datas["points_balance"]     =  $value['Balance_Referral_Point'];
		            				$user_datas["order_number"]        =    @$value['Ref_Num'];
		            				$user_datas['created_at']         = date('d M Y', strtotime($value['Creation_Date_Time']));
		            				$user_datas['time']               = date('g:i A',strtotime($value['Creation_Date_Time']));
		            				$trans_array[]                    = $user_datas;
			            			
			            		}
								$arg['status']     = 1;
								$arg['error_code'] = SUCCESS_CODE;
								$arg['error_line'] = __line__;
								$arg['data']       =  $trans_array;
								$arg['message']    = $this->lang->line('earn_point_history_list');
						    }
							else
							{
								$arg['status']     = 0;
								$arg['error_code'] = ERROR_FAILED_CODE;
						  	    $arg['error_line'] = __line__;
				            	$arg['message']    = $this->lang->line('record_not_found');
							}
						}
			    	}
			    }
			}
		}
	    echo json_encode($arg);
	}
	//Used function to get bank list
	public function get_bank_list()
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
					$arg['error']   = 461;
					$arg['message'] = $result;
				}
				else
				{
					$usid    = getuserid();
					$loguser = $this->dynamic_model->get_user_by_id($usid);
					if($loguser)
					{
						$cur_date  = strtotime(date('Y-m-d H:i:s'));
						//$condition = array('Promo_Start_Date <'=>$cur_date,'Promo_End_Date >'=>$cur_date,'Promo_Status'=>1);
						//$promo_data = $this->dynamic_model->getdatafromtable('Promo_Code', $condition);

						$arg['status'] = 1;
					    // if(!empty($promo_data))
					    // {
					    // 	$promo = array();
					    // 	foreach ($promo_data as $key => $value)
					    // 	{
					    		$promo['id']         = "1";
					    		$promo['Bank_Name']  = "ICICI Bank";
					    		$arg['data'][] = $promo;
					  //   	}
					  //   }
					  //   else
					  //   {
					  //   	$arg['status']  = 0;
							// $arg['message'] = 'No Records Found';
					  //   }
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

	//Function used for Get Transaction History download
    public function download_pdf_transaction_history()
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
					$arg['status']  = STATUS_AUTHORIZATION_CODE;
					$arg['error']   = ERROR_AUTHORIZATION_CODE;
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
						$this->form_validation->set_rules('page_no', 'Page No', 'required|numeric',array(
							'required'   => $this->lang->line('page_no'),
							'numeric'    => $this->lang->line('page_no_numeric')
						));
						$this->form_validation->set_rules('is_download', 'Is Download', 'required|numeric',array(
							'required'   => $this->lang->line('is_download_req'),
							'numeric'    => $this->lang->line('is_download_numeric')
						));
						if ($this->form_validation->run() == FALSE)
						{
						  	$arg['status']  = 0;
						  	$arg['error']   = ERROR_FAILED_CODE;
						  	$arg['message'] = get_form_error($this->form_validation->error_array());
						} 
						else
						{
							$userid  = getuserid();
							$loguser = $this->dynamic_model->get_user_by_id($userid);

							$limit            = config_item('page_data_limit'); 
							$offset           = $limit * $this->input->post('page_no');
							$request_type     = $this->input->post('request_type');
							$is_download      = $this->input->post('is_download');
							$transaction_type = $this->input->post('transaction_type');
							$cur_month        = date('m');

							// if request_type = 0 means this month
							// if request_type = 1 means last month
							// if request_type = 6 means last 6 month
							// if request_type = 12 means custom date
							if($request_type == 12)
							{
								$start = $this->input->post('start_date');
								$end   = $this->input->post('end_date');
							}
							else
							{
								$start = date('Y-m-d', strtotime("first day of -".$request_type." month"));
								if($request_type == 0)
									$end = date('Y-m-d');
								else
									$end = date('Y-m-d', strtotime('last day of last month'));
								// $start = date('Y-m-d');
								// $end   = date('Y-m-d', strtotime("-".$request_type." month"));
							}

							//If is_download = 0 means show transaction as per selected request type 
							//If is_download = 1 means download transaction pdf file as per selected request type
							if($is_download == 0)
							{
								if($transaction_type == "")
								{
									$condition = "cast(`Creation_Date_Time` as DATE) BETWEEN '".$start."' AND '".$end."' and To_User_Id = '".$userid."'";
									$transaction_data = $this->dynamic_model->getdatafromtable('
										Transactions',$condition, '*', $limit, $offset,'Id');
								}
								else
								{
									$condition = "cast(`Creation_Date_Time` as DATE) BETWEEN '".$start."' AND '".$end."' and To_User_Id = '".$userid."' and Tran_Type_Id ='".$transaction_type."'";
									$transaction_data = $this->dynamic_model->getdatafromtable('
										Transactions',$condition, '*', $limit, $offset,'Id');
								}
							}
							else
							{
								if($transaction_type == "")
								{
									$condition = "cast(`Creation_Date_Time` as DATE) BETWEEN '".$start."' AND '".$end."' and To_User_Id = '".$userid."'";
									$transaction_data = $this->dynamic_model->getdatafromtable('transactions',$condition,'*','','','Id');
								}
								else
								{
									$condition = "cast(`Creation_Date_Time` as DATE) BETWEEN '".$start."' AND '".$end."' and To_User_Id = '".$userid."' and Tran_Type_Id ='".$transaction_type."'";
									$transaction_data = $this->dynamic_model->getdatafromtable('transactions',$condition,'*','','','Id');
								}
							}

							if(!empty($transaction_data))
							{
								$user_data = array();
								foreach($transaction_data as $details)
			            		{
			            			$transaction_status = $this->dynamic_model->get_row('tran_status',array('Id'=> $details['Tran_Status_Id']));

			            			if($details['From_User_Id'])
			            				$Requested_user = $this->dynamic_model->get_user_by_id($details['From_User_Id']);

			            			if($details['Tran_Type_Id'] == "1")
			            			{
			            				$user_data["Id"]                 = $details['Id'];
			            				$user_data["title"]              = "Withdraw Money";
			            				$user_data["fullname"]           = "";
			            				$user_data["mobile"]             = "";
			            				$user_data["order_number"]       = $details['Third_Party_Tran_Id'];
			            				$user_data["amount"]             = $details['Amount'];
			            				$user_data["sig"]                = $details['Sig'];
			            				$user_data["charge"]             = $details['Charge'];
			            				$user_data['created_at']         = date('d M Y', strtotime($details['Creation_Date_Time']));
			            				if($transaction_status['Status_Name'] == "Reject")
			            					$user_data['transaction_status'] = "Failed";
			            				else
			            					$user_data['transaction_status'] = $transaction_status['Status_Name'];
			            				$user_data['trx_type']           = "Withdraw";
			            				$user_data['tran_type_id']       = $details['Tran_Type_Id'];
			            				$user_data['time']               = date('g:i A',strtotime($details['Creation_Date_Time']));
			            				$transaction_array[]             = $user_data;
			            			}
			            			if($details['Tran_Type_Id'] == "2")
			            			{
			            				$user_data["Id"]                 = $details['Id'];
			            				$user_data["title"]              = "Money added";
			            				$user_data["fullname"]           = "";
			            				$user_data["mobile"]             = "";
			            				$user_data["order_number"]       = $details['Third_Party_Tran_Id'];
			            				$user_data["amount"]             = $details['Amount'];
			            				$user_data["sig"]                = $details['Sig'];
			            				$user_data["charge"]             = $details['Charge'];
			            				$user_data['created_at']         = date('d M Y', strtotime($details['Creation_Date_Time']));
			            				if($transaction_status['Status_Name'] == "Reject")
			            					$user_data['transaction_status'] = "Failed";
			            				else
			            					$user_data['transaction_status'] = $transaction_status['Status_Name'];
			            				$user_data['trx_type']           = "Deposit";
			            				$user_data['tran_type_id']       = $details['Tran_Type_Id'];
			            				$user_data['time']               = date('g:i A',strtotime($details['Creation_Date_Time']));
			            				$transaction_array[]             = $user_data;
			            			}
			            			if($details['Tran_Type_Id'] == "3")
			            			{
			            				$user_data["Id"]                 = $details['Id'];
			            				$user_data["title"]              = "Money Sent to ".$Requested_user['FullName'];
			            				$user_data["fullname"]           = $Requested_user['FullName'];
			            				$user_data["mobile"]             = $Requested_user['Mobile_No'];
			            				$user_data["order_number"]       = $details['Third_Party_Tran_Id'];
			            				$user_data["amount"]             = $details['Amount'];
			            				$user_data["sig"]                = $details['Sig'];
			            				$user_data["charge"]             = $details['Charge'];
			            				$user_data['created_at']         = date('d M Y', strtotime($details['Creation_Date_Time']));
			            				if($transaction_status['Status_Name'] == "Reject")
			            					$user_data['transaction_status'] = "Failed";
			            				else
			            					$user_data['transaction_status'] = $transaction_status['Status_Name'];
			            				$user_data['trx_type']           = "Sent Money";
			            				$user_data['tran_type_id']       = $details['Tran_Type_Id'];
			            				$user_data['time']               = date('g:i A',strtotime($details['Creation_Date_Time']));
			            				$transaction_array[]             = $user_data;
			            			}
			            			if($details['Tran_Type_Id'] == "4")
			            			{
			            				$user_data["Id"]                 = $details['Id'];
			            				$user_data["title"]              = "Money Received From ".$Requested_user['FullName'];
			            				$user_data["fullname"]           = "";
			            				$user_data["mobile"]             = "";
			            				$user_data["order_number"]       = $details['Third_Party_Tran_Id'];
			            				$user_data["amount"]             = $details['Amount'];
			            				$user_data["sig"]                = $details['Sig'];
			            				$user_data["charge"]             = $details['Charge'];
			            				$user_data['created_at']         = date('d M Y', strtotime($details['Creation_Date_Time']));
			            				if($transaction_status['Status_Name'] == "Reject")
			            					$user_data['transaction_status'] = "Failed";
			            				else
			            					$user_data['transaction_status'] = $transaction_status['Status_Name'];
			            				$user_data['trx_type']           = "Money Received";
			            				$user_data['tran_type_id']       = $details['Tran_Type_Id'];
			            				$user_data['time']               = date('g:i A',strtotime($details['Creation_Date_Time']));
			            				$transaction_array[]             = $user_data;
			            			}
			            		}

								$arg['status']   = 1;
								$arg['message']  = "Success";
								if($is_download == 0)
								{
									$arg['data']           = $transaction_array;
									$arg['wallet_balance'] = number_format($loguser['Current_Wallet_Balance'],2);
								}
								else
								{
									$time = time();
				            		$data['fullname']  = $loguser['FirstName'].' '.$loguser['LastName'];
				            		$data['email']     = $loguser['Email'];
				            		$data['mobile_no'] = $loguser['Mobile_No'];
				            		$data['start']     = $start;
				            		$data['end']       = $end;
				            		$data['transaction_array'] = $transaction_array;
				            		$html = $this->load->view('webservices/transaction_history',$data, true);
								    //$html = $this->output->get_output();

								    // Load pdf library
								    $this->load->library('Pdf');
								    // Load HTML content
								    $this->dompdf->loadHtml($html);
								    // Render the HTML as PDF
								    $this->dompdf->render();
								    $output=$this->dompdf->output();
								    //$this->dompdf->stream("invoice.pdf", array("Attachment"=>1));
								    $temp_file ='uploads/pdf/'.$time.'invoice.pdf';
								    file_put_contents($temp_file,$output);

									//$arg['data']['url']           = site_url().'uploads/pdf/'.$time.'invoice.pdf';
									//$arg['wallet_balance']        = $loguser['Current_Wallet_Balance'];

									//Send Email Code
									$emaildata['subject']     = 'pdf';
									$emaildata['description'] = 'PFA';
									$emaildata['body']        = '';

									$msg    = $this->load->view('emailtemplate', $emaildata, TRUE);
									$this->sendmail->sendmailto($loguser['Email'],'Transaction History',$msg,$temp_file);

									$arg['status']  = 1;
					             	$arg['message'] = 'email will sent on your registered email id.';
					             	$arg['data']['url']            = site_url().'uploads/pdf/'.$time.'invoice.pdf';
									$arg['data']['wallet_balance'] =number_format($loguser['Current_Wallet_Balance'],2);
								}
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
			}
		}
	    echo json_encode($arg);
	}

	//Function used for Get Withdraw Repeat Details
    public function get_withdraw_repeat_details()
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
						$this->form_validation->set_rules('trx_id', 'Transaction id', 'required');
						if ($this->form_validation->run() == FALSE)
						{
						  	$arg['status']  = 0;
						  	$arg['error']   = ERROR_FAILED_CODE;
						  	$arg['message'] = get_form_error($this->form_validation->error_array());
						} 
						else
						{
							$arg      = array();
							$userid   = getuserid();
							$loguser  = $this->dynamic_model->get_user_by_id($userid);
							$trx_id   = $this->input->post('trx_id');
							$transaction_data = $this->dynamic_model->getdatafromtable('transactions', array('Third_Party_Tran_Id'=>$trx_id,'To_User_Id'=>$userid));
							if(!empty($transaction_data))
							{
								$user_data    = array();
		            			$user_payment = $this->dynamic_model->get_row('user_payment_methods',array('Id'=> $transaction_data[0]['To_Payment_Method_Id']));

	            				$user_data["id"]                 = $user_payment['Id'];
	            				$user_data["bank_name"]   = $user_payment['Bank_Name'];
	            				$user_data["acc_holder_name"]    = $user_payment['Acc_Holder_Name'];
	            				$user_data["branch_name"]        = $user_payment['Branch_Name'];
	            				$user_data['account_no']         = $user_payment['Account_No'];
	            				$user_data['is_deleted']         = $user_payment['Is_Deleted'];
	            				$transaction_array[]             = $user_data;

								$arg['status']   = 1;
								$arg['data']     = $transaction_array;
								$arg['message']  = "Success";
							}
							else
							{
								$arg['status']  = 0;
								$arg['error']   = ERROR_FAILED_CODE;
				            	$arg['message'] = 'No record found';
							}
						}
			    	}
			    }
			}
		}
	    echo json_encode($arg);
	}

	//Function used for Refund Virtual Amount to user after 48 hous check(CRON)
	public function refund_virtual_amount()
	{
		$arg = array();
		$ref_num  = getuniquenumber();
		$virtual    = array('Is_Virtual' => 1);
		$userdetail = $this->dynamic_model->getdatafromtable('users',$virtual);

		foreach ($userdetail as $key => $value)
		{
			$timefromdatabase = strtotime($value['Creation_Date_Time']);

			$dif = time() - $timefromdatabase;

			//more than 48 hours
			if($dif > 172800)
			{
				$cond       = array('To_User_Id' => $value['Id']);
				$user_trans = $this->dynamic_model->getdatafromtable('transactions',$cond);

                /* Send money from one user to another user  */

        		//Then after insert into Transactions table
                $paymentaddArr = array(
                                        'Tran_Type_Id'         =>3, //send money
                                        'To_Payment_Method_Id' =>0,
                                        'Amount'               =>$user_trans[0]['Amount'],
                                        'Charge'               =>0,
                                        'Msg'                  =>'',
                                        'To_User_Id'           =>$value['Id'],
                                        'From_User_Id'         =>$user_trans[0]['From_User_Id'],
                                        'Tran_Status_Id'       =>6,
                                        'Sig'                  =>'-',
                                        'Amount_Received'      =>0,
                                        'Third_Party_Tran_Id'  =>$ref_num,
                                        'Created_By'           =>$value['Id'],
                                        'Last_Updated_By'      =>$value['Id']
                                	);
                $Transaction_id = $this->dynamic_model->insertdata('transactions', $paymentaddArr);

                //Then after insert into Tran_Charges table
                $chargeaddArr = array(
                                        'Transaction_Id'  =>$Transaction_id,
                                        'Charge_Type_Id'  =>0,
                                        'Charge_Amt'      =>0,
                                        'Created_By'      =>$value['Id'],
                                        'Last_Updated_By' =>$value['Id']
                                	);
                $Transaction_charge_id = $this->dynamic_model->insertdata('tran_charges', $chargeaddArr);

                /* Receive money to another user from sended user  */

                //Then after insert into Transactions table
                $paymentaddArr = array(
                                        'Tran_Type_Id'         =>4, //receive money
                                        'To_Payment_Method_Id' =>0,
                                        'Amount'               =>$user_trans[0]['Amount'],
                                        'To_User_Id'           =>$user_trans[0]['From_User_Id'],
                                        'From_User_Id'         =>$value['Id'],
                                        'Tran_Status_Id'       =>6,
                                        'Sig'                  =>'+',
                                        'Amount_Received'      =>$user_trans[0]['Amount'],
                                        'Third_Party_Tran_Id'  =>$ref_num,
                                        'Created_By'           =>$value['Id'],
                                        'Last_Updated_By'      =>$value['Id']
                                	);
                $Transaction_id = $this->dynamic_model->insertdata('transactions', $paymentaddArr);

                //Then after insert into Tran_Charges table
                $chargeaddArr = array(
                                        'Transaction_Id'  =>$Transaction_id,
                                        'Charge_Type_Id'  =>0,
                                        'Charge_Amt'      =>0,
                                        'Created_By'      =>$value['Id'],
                                        'Last_Updated_By' =>$value['Id']
                                	);
                $Transaction_charge_id = $this->dynamic_model->insertdata('tran_charges', $chargeaddArr);

                $con    = array('Id' => $user_trans[0]['From_User_Id']);
				$users  = $this->dynamic_model->getdatafromtable('users',$con);

                //update amount into users table (add amount to receiver and update wallet amount)
                $total_receiver_wallet = $users[0]['Current_Wallet_Balance'] + $value['Current_Wallet_Balance'];
                $receiverwalletdata['Current_Wallet_Balance'] = $total_receiver_wallet;
				$updatereceiverwallet = $this->dynamic_model->updatedata('users', $receiverwalletdata, $user_trans[0]['From_User_Id']);

				$where2    = array('Id' => $value['Id']);
				$response  = $this->dynamic_model->deletedata('users',$where2);
			}
		}
	}
	//Function used for send money chat
    public function sendMoneyChat()
    {
    	$arg  = array();
    	$_POST = $this->input->post();

		if($_POST == [])
		{
			$_POST = json_decode(file_get_contents("php://input"), true);
		}
		if($_POST)
		{
			$arg = array();
			$this->form_validation->set_rules('sender_id','Sender Id','required');
			$this->form_validation->set_rules('receiver_id',' Receiver Id','required');
			$this->form_validation->set_rules('amount', 'Amount', 'required|numeric|greater_than[0]');
			//$this->form_validation->set_rules('pin', 'Transaction pin', 'required|min_length[4]|max_length[4]|numeric');
			if ($this->form_validation->run() == FALSE)
			{
			  	$arg['status']  = 0;
			  	$arg['error']   = ERROR_FAILED_CODE;
			  	$arg['message'] = get_form_error($this->form_validation->error_array());
			}
			else
			{ 
		        $usid    = $this->input->post('sender_id');
				$user_status    = checkUserStatusById($usid);
		        if(@$user_status['status'] != 0)
		        {
		        	$arg = $user_status;
		        }
		        else
		        { 

			        //$mobileEmail       = $this->input->post('mobileEmail');
			        $receiver_id       = $this->input->post('receiver_id');
			        $amount            = $this->input->post('amount');
			        $charge            = '0.00';
			        //$commMsg         = $this->input->post('commMsg');
			        $commMsg           = 'Send MOney';
			       // $pin               = $this->input->post('pin');

			        $ref_num           = getuniquenumber();
	                $flag=1;

			        //Check Transaction Password 
	        		
					$loguser = $this->dynamic_model->get_user_by_id($usid);
					$receiver_user = $this->dynamic_model->get_user_by_id($receiver_id);
					$mobileEmail   = $receiver_user['Mobile_No'];
					$userid = $loguser['Id'];
					if($loguser['Mobile_No']== $receiver_user['Mobile_No'] || $loguser['Email']== $receiver_user['Email'])
					{
						$arg['status']  = 0;
						$arg['error']   = ERROR_FAILED_CODE;
	                    $arg['message'] = $this->lang->line('not_sendmoney_youself');
	                    echo json_encode($arg);exit();
					}
	                else
	                {
						// if($loguser['Transaction_Password'] != encrypt_password($pin))
		    //             {
		    //                 $arg['status']  = 0;
		    //                 $arg['error']   = ERROR_PIN_CODE;
		    //                 $arg['message'] = $this->lang->line('invalid_pin');
		    //             }
		    //             else
		    //             {
	                        $total_req_amt  = $amount + $charge;
		                	
		                	//Check Daily limit or count
							$limit = check_limit($total_req_amt,$userid,"daily",3);
							if($limit == false)
							{
								$arg['status']  = 0;
								$arg['error']   = ERROR_FAILED_CODE;
								$arg['message'] = $this->lang->line('daily_limit_exceed');
								echo json_encode($arg);exit;
							}

							//Check Monthly limit or count
							$monthly_limit = check_limit($total_req_amt,$userid,"monthly",3);
							if($monthly_limit == false)
							{
								$arg['status']  = 0;
								$arg['error']   = ERROR_FAILED_CODE;
								$arg['message'] = $this->lang->line('monthly_limit_exceed');
								echo json_encode($arg);exit;
							}
	        					// NOTE :-start code for Send Money
	                        	$update_user = $this->dynamic_model->get_user_by_id($userid);
	                        	$received_amount = $amount + $charge;
	                        	// Check send amount limit exceed
	                        	if($received_amount <= $update_user['Current_Wallet_Balance'])
	                			{
	                                $t           = time();
									$userDetail = $this->dynamic_model->check_userdetails($mobileEmail);

	                                if(!empty($userDetail))
	                            	{
	                            		/* Send money from one user to another user */

	                            		//Then after insert into Transactions table
				                        $paymentaddArr = array(
				                                                'Tran_Type_Id'         =>3, //send money
				                                                'To_Payment_Method_Id' =>0,
				                                                'Amount'               =>$amount,
				                                                'Charge'               =>$charge,
				                                                'Msg'                  =>$commMsg,
				                                                'To_User_Id'           =>$userid,
				                                                'From_User_Id'         =>$userDetail['Id'],
				                                                'Tran_Status_Id'       =>6,
				                                                'Sig'                  =>'-',
				                                                'Amount_Received'      =>$amount,
				                                                'Third_Party_Tran_Id'  =>$ref_num,
				                                                'Created_By'           =>$userid,
				                                                'Last_Updated_By'      =>$userid,
				                                                'Chat_Id'              =>'0'
				                                        	);
				                        $Transaction_id = $this->dynamic_model->insertdata('transactions', $paymentaddArr);

				                        //Then after insert into Tran_Charges table
				                        $chargeaddArr = array(
				                                                'Transaction_Id'  =>$Transaction_id,
				                                                'Charge_Type_Id'  =>0,
				                                                'Charge_Amt'      =>$charge,
				                                                'Created_By'      =>$userid,
				                                                'Last_Updated_By' =>$userid
				                                        	);
				                        $Transaction_charge_id = $this->dynamic_model->insertdata('tran_charges', $chargeaddArr);

				                        /* Receive money to another user from sended user  */

				                        //Then after insert into Transactions table
				                        $paymentaddArr = array(
				                                                'Tran_Type_Id'         =>4, //receive money
				                                                'To_Payment_Method_Id' =>0,
				                                                'Amount'               =>$amount,
				                                                'To_User_Id'           =>$userDetail['Id'],
				                                                'From_User_Id'         =>$userid,
				                                                'Tran_Status_Id'       =>6,
				                                                'Sig'                  =>'+',
				                                                'Amount_Received'      =>$amount,
				                                                'Third_Party_Tran_Id'  =>$ref_num,
				                                                'Created_By'           =>$userid,
				                                                'Last_Updated_By'      =>$userid,
				                                                'Chat_Id'              =>'0'
				                                        	);
				                        $Transaction_id = $this->dynamic_model->insertdata('transactions', $paymentaddArr);

				                        //Then after insert into Tran_Charges table
				                        $chargeaddArr = array(
				                                                'Transaction_Id'  =>$Transaction_id,
				                                                'Charge_Type_Id'  =>0,
				                                                'Charge_Amt'      =>0,
				                                                'Created_By'      =>$userid,
				                                                'Last_Updated_By' =>$userid
				                                        	);
				                        $Transaction_charge_id = $this->dynamic_model->insertdata('tran_charges', $chargeaddArr);

	                                    //update amount into users table (deduct amount from sender and update wallet amount)
				                        $total_wallet_amount = $update_user['Current_Wallet_Balance'] - $received_amount;
				                        $userwalletdata['Current_Wallet_Balance'] = $total_wallet_amount;
			        					$updatewalletdata = $this->dynamic_model->updatedata('users', $userwalletdata, $userid);

	                                    //update amount into users table (add amount to receiver and update wallet amount)
	                                    $total_receiver_wallet = $userDetail['Current_Wallet_Balance'] + $amount;
				                        $receiverwalletdata['Current_Wallet_Balance'] = $total_receiver_wallet;
			        					$updatereceiverwallet = $this->dynamic_model->updatedata('users', $receiverwalletdata, $userDetail['Id']);

			        					$updated_user_wallet = $this->dynamic_model->get_user_by_id($userid);
	                            	}

	                                //Notification functionality
	                                //Send Notification For Sender
	    							$User_Role = $this->dynamic_model->get_row('user_in_roles',array('User_Id'=> $userid));
	                                $myname                 = ucfirst($loguser['FullName']);
	                                $notification_to_sender = $User_Role['Device_Id'];
	                                $sender_name            = ucfirst(strtolower($userDetail['FullName']));

	                               	$notification_type_sender  = 3; //Send Money
	                               	$notification_title_sender = 'Dear '.$myname.',You have successfully sent '.CURRENCY_SYMBOLE.''.number_format($amount, 2).' to '.$sender_name.' '.$userDetail['Mobile_No'].' on '.date('d/m/Y '). ' at '.date('H:i A').' Ref.No: '.$ref_num.' Transaction cost '.CURRENCY_SYMBOLE.'0.00';

	                                if(!empty($User_Role['Device_Id']) && $User_Role['Device_Type'] == 'android' )
	                                {
	                                    sendPushAndroid($notification_to_sender,$notification_title_sender, $notification_type_sender );
	                                }
	                                if(!empty($User_Role['Device_Id']) && $User_Role['Device_Type'] == 'ios' )
	                                {
	                                    sendPushIos($notification_to_sender,$notification_title_sender, $notification_type_sender );
	                                }

	                                //Insert Notification
			                        $notiDataArr = array('Recepient_Id'=>$userid,'Notification_Text' =>$notification_title_sender, 'Tran_Type_Id' =>$notification_type_sender);
			                        $insert_notification = $this->dynamic_model->insertdata('user_notifications', $notiDataArr);

	                                //Send Notification For Receiver
	                                $Rec_User_Role = $this->dynamic_model->get_row('user_in_roles',array('User_Id'=> $userDetail['Id']));
	                                $receiver_name = ucfirst(strtolower($loguser['FirstName'].' '.$loguser['LastName'])).' '.$loguser['Mobile_No'];
	                                $notification_to_receiver = $Rec_User_Role['Device_Id'];

	                                $notification_title_receiver = 'Dear '.$sender_name.',You have successfully received '.CURRENCY_SYMBOLE.''.number_format($amount, 2).' from '.$receiver_name.' on '.date('d-m-Y '). ' at '.date('H:i A').' Ref.No: '.$ref_num.' Your new balance is '.CURRENCY_SYMBOLE.''.number_format(@$total_receiver_wallet, 2);

	                                $notification_type_receiver = 4; //Receive Money

	                                if(!empty($Rec_User_Role['Device_Id']) && $Rec_User_Role['Device_Type'] == 'android' )
	                                {
	                                    sendPushAndroid($notification_to_receiver,$notification_title_receiver, $notification_type_receiver );
	                                }
	                                if(!empty($Rec_User_Role['Device_Id']) && $Rec_User_Role['Device_Type'] == 'ios' )
	                                {
	                                    sendPushIos($notification_to_receiver,$notification_title_receiver, $notification_type_receiver );
	                                }

	                               	//Insert Notification
			                        $notiDataArrRec = array('Recepient_Id'=>$userDetail['Id'],'Notification_Text' =>$notification_title_receiver, 'Tran_Type_Id' =>$notification_type_receiver);
			                        $insert_notification = $this->dynamic_model->insertdata('user_notifications', $notiDataArrRec);
	                                if($flag)
	                                {
		                                $response  = array('amount' => number_format($total_req_amt, 2),'transaction_date'=>date('d M Y'),'transaction_id'=>$ref_num,'wallet_amount'=>$updated_user_wallet['Current_Wallet_Balance']);

		        						$arg['status']  = 1;
					  					$arg['message'] = $this->lang->line('send_money_success');
					  					$arg['data']    = $response;
					  				}
				  				}
				  				else
				  				{
				  					$arg['status']  = 0;
				  					$arg['error']   = ERROR_FAILED_CODE;
	                    			$arg['message'] = $this->lang->line('send_money_limit_exceed');
				  				}     
							
					    //}
					}
				}
			}

    	}
	    echo json_encode($arg);
	}
	//Function used for scan Paysend Money 
    public function scanPaysendMoney(){
		$arg = array();
		//check user is active or not
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
					$arg['status'] = 101;
					$arg['error'] = 461;
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
						$this->form_validation->set_rules('amount', 'Amount', 'required|greater_than[0]');
						$this->form_validation->set_rules('mobile', 'Mobile', 'required|numeric');
						$this->form_validation->set_rules('charge', 'Mobile', 'required|numeric');
						if($this->form_validation->run() == FALSE) 
						{
							$arg['status'] = 0;
							$arg['error'] = ERROR_FAILED_CODE;
							$arg['message'] = get_form_error($this->form_validation->error_array());
						}
						else
						{
							$is_chat = $this->input->post('is_chat');
							$mobile = $this->input->post('mobile');
							$amount = $this->input->post('amount');
							$charge = $this->input->post('charge');
							$commMsg = $this->input->post('commMsg');
							$pin = $this->input->post('pin');
							$ref_num = getuniquenumber();
							$flag = 1;
						
							$userid = getuserid();
							$loguser = $this->dynamic_model->get_user_by_id($userid);
							if($loguser['Transaction_Password'] != encrypt_password($pin)){
								$arg['status'] = 0;
								$arg['error'] = ERROR_PIN_CODE;
								$arg['message'] = $this->lang->line('invalid_pin');
								echo json_encode($arg);exit();
						    }
							if($loguser['Mobile_No'] == $mobile){
								$arg['status'] = 0;
								$arg['error'] = ERROR_FAILED_CODE;
								$arg['message'] = $this->lang->line('send_request_yourself_error');
								echo json_encode($arg);exit();
							}
							$userDetail = $this->dynamic_model->check_userdetails($mobile);
							if($userDetail)
							{
                            	$received_amount = $amount + $charge;
                            	//Check Daily limit or count
								$limit = check_limit($received_amount, $userid, "daily", 3);
								if ($limit == false){
									$arg['status'] = 0;
									$arg['error'] = ERROR_FAILED_CODE;
									$arg['message'] = $this->lang->line('daily_limit_exceed');
									echo json_encode($arg);exit;
								}

								//Check Monthly limit or count
								$monthly_limit = check_limit($received_amount, $userid, "monthly", 3);
								if ($monthly_limit == false) {
									$arg['status'] = 0;
									$arg['error'] = ERROR_FAILED_CODE;
									$arg['message'] = $this->lang->line('monthly_limit_exceed');
									echo json_encode($arg);exit;
								}
                                if($loguser['Current_Wallet_Balance'] >= $received_amount)
                                {
                                    $time=time();
									$paymentRequestArr = array(
										'To_User_Id' => $userDetail['Id'],
										'From_User_Id' => $userid,
										'Amount' => $amount,
										'Charge' => $charge,
										'Type' => 'Sendmoney_req',
										'Ref_Num' => $ref_num,
										'Message' => (!empty($commMsg)) ? $commMsg : '',
										'Tran_Status_Id' => 1,
										'Created_By' => $userid,
										'UnixTimestamp' =>$time
									);
									$req_id = $this->dynamic_model->insertdata('request_share',$paymentRequestArr);
										//Notification functionality
									//Send Notification For Sender
									$User_Role = $this->dynamic_model->get_row('user_in_roles', array('User_Id' => $userid));
									$notification_to_sender = $User_Role['Device_Id'];
									$receivername = ucfirst(strtolower($userDetail['FullName'])) . ' ' . $userDetail['Mobile_No'];
									$sender_name = ucfirst(strtolower($loguser['FullName']));

									// $notification_title_sender = 'Dear ' . $sender_name . ', You have successfully requested payment of ' . CURRENCY_SYMBOLE . '' . number_format((float) $amount, 2, '.', '') . ' to your wallet on ' . date('d-m-Y ') . 'at ' . date('H:i A') . ' Ref.No: ' . $ref_num . ' Transaction cost ' . CURRENCY_SYMBOLE . '0.00';

									// $notification_type_sender = 9; //Request Money
									// if (!empty($User_Role['Device_Id']) && $User_Role['Device_Type'] == 'android') {
									// 	sendPushAndroid($notification_to_sender, $notification_title_sender, $notification_type_sender);
									// }
									// if(!empty($User_Role['Device_Id']) && $User_Role['Device_Type'] == 'ios') {
									// 	sendPushIos($notification_to_sender, $notification_title_sender, $notification_type_sender);
									// }
									// //Insert Notification
									// $notiDataArr = array('Recepient_Id' => $userid, 'Notification_Text' => $notification_title_sender, 'Tran_Type_Id' => $notification_type_sender);
									// $insert_notification = $this->dynamic_model->insertdata('user_notifications', $notiDataArr);

									//Send Notification For Receiver
									$Rec_User_Role = $this->dynamic_model->get_row('user_in_roles', array('User_Id' => $userDetail['Id']));
									$receiver_name = ucfirst(strtolower($userDetail['FullName']));
									$sender_name = ucfirst($loguser['FullName']) . ' ' . $loguser['Mobile_No'];
									$notification_to_receiver = $Rec_User_Role['Device_Id'];

									$notification_title_receiver = 'Dear ' . $receiver_name . ', You have received a payment request of ' . CURRENCY_SYMBOLE . '' . number_format($amount, 2) . ' from ' . $sender_name . ' on ' . date('d-m-Y ') . ' at ' . date('H:i A') . ' Ref.No: ' . $ref_num . ' Transaction cost ' . CURRENCY_SYMBOLE . '0.00';

									$notification_type_receiver = 12; // send money Request Money

									if (!empty($Rec_User_Role['Device_Id']) && $Rec_User_Role['Device_Type'] == 'android') {
										sendPushAndroid($notification_to_receiver, $notification_title_receiver, $notification_type_receiver);
									}
									if (!empty($Rec_User_Role['Device_Id']) && $Rec_User_Role['Device_Type'] == 'ios') {
										sendPushIos($notification_to_receiver, $notification_title_receiver, $notification_type_receiver);
									}

									//Insert Notification
									$notiDataArrRec = array('Req_Id' => $req_id, 'Recepient_Id' => $userDetail['Id'], 'Notification_Text' => $notification_title_receiver, 'Tran_Type_Id' => $notification_type_receiver);
									$insert_notification = $this->dynamic_model->insertdata('user_notifications', $notiDataArrRec);
	                                
	                                //$response = array('wallet_amount' => $loguser['Current_Wallet_Balance'], 'charge' => "0.00");
									$response = array('amount' => number_format($amount, 2), 'transaction_date' => date('d M Y'), 'transaction_id' => $ref_num, 'wallet_amount' => number_format($loguser['Current_Wallet_Balance'],2),'charge' => "0.00");

									$arg['status'] = 1;
									$arg['message'] = $this->lang->line('request_sendmoney_success');
									$arg['data'] = $response;

                                }else{
									$flag = 0;
									$arg['status'] = 0;
									$arg['error'] = ERROR_FAILED_CODE;
									$arg['message'] = $this->lang->line('insufficient_balances');
								}
							}else{
								$flag = 0;
								$arg['status'] = 0;
								$arg['error'] = ERROR_FAILED_CODE;
								$arg['message'] = $this->lang->line('mobile_invalid');
							}

					}
				  }
				}
			}
		}
		echo json_encode($arg);
	}
	//Function used for accept send money
	public function acceptSendMoneyRequest(){
		$arg = array();
		//check user is active or not
		$user_status = checkUserStatus();
		if(@$user_status['status'] != 0){
			$arg = $user_status;
		}else{
			$version_result = version_check_helper();
			if($version_result['status'] != 1){
				$arg = $version_result;
			}else{
				$result = check_authorization();
				if ($result != 'true') {
					$arg['status'] = 101;
					$arg['error'] = 461;
					$arg['message'] = $result;
				} else {
					$_POST = $this->input->post();

					if($_POST == [])
					{
						$_POST = json_decode(file_get_contents("php://input"), true);
					}
					if($_POST){
						$arg = array();
						//$this->form_validation->set_rules('pin', 'Transaction pin', 'required|min_length[4]|max_length[4]|numeric');
						$this->form_validation->set_rules('reqmoneyId', 'Request money id', 'required|numeric');
						if ($this->form_validation->run() == FALSE) {
							$arg['status'] = 0;
							$arg['error'] = ERROR_FAILED_CODE;
							$arg['message'] = get_form_error($this->form_validation->error_array());
						} else {
							$usid = getuserid();
							$loguser = $this->dynamic_model->get_user_by_id($usid);
							$pin = $this->input->post('pin');

							// if ($loguser['Transaction_Password'] != encrypt_password($pin)) {
							// 	$arg['status'] = 0;
							// 	$arg['error'] = ERROR_PIN_CODE;
							// 	$arg['message'] = $this->lang->line('invalid_pin');
							// } else {
								$reqmoneyId = $this->input->post('reqmoneyId');
								$is_chat = $this->input->post('is_chat');
								$ref_num = getuniquenumber();
								$flag = 1;

								$paymentRequest = $this->dynamic_model->get_row('request_share', array('Type' => 'Sendmoney_req', 'To_User_Id' => $usid, 'Id' => $reqmoneyId));
								if($paymentRequest['Tran_Status_Id'] == "1"){
									$otheruser = $this->dynamic_model->get_user_by_id($paymentRequest['From_User_Id']);
									$received_amount = $paymentRequest['Amount'] + $paymentRequest['Charge'];

									/* Send money from one user to another user  */
                                       $charge= $paymentRequest['Charge'];
										//Then after insert into Transactions table
										$paymentaddArr = array(
											'Tran_Type_Id' => 3, //send money
											'To_Payment_Method_Id' => 0,
											'Amount' => $paymentRequest['Amount'],
											'Charge' => $charge,
											'To_User_Id' => $paymentRequest['From_User_Id'] ,
											'From_User_Id' => $usid,
											'Tran_Status_Id' => 6,
											'Sig' => '-',
											'Amount_Received' => $paymentRequest['Amount'],
											'Third_Party_Tran_Id' => $ref_num,
											'Created_By' => $usid,
											'Last_Updated_By' => $usid,
										);
										$Transaction_id = $this->dynamic_model->insertdata('transactions', $paymentaddArr);

										//Then after insert into Tran_Charges table
										$chargeaddArr = array(
											'Transaction_Id' => $Transaction_id,
											'Charge_Type_Id' => 0,
											'Charge_Amt' => $charge,
											'Created_By' => $usid,
											'Last_Updated_By' => $usid,
										);
										$Transaction_charge_id = $this->dynamic_model->insertdata('tran_charges', $chargeaddArr);

										/* Receive money to another user from sended user  */

										//Then after insert into Transactions table
										$paymentaddArr = array(
											'Tran_Type_Id' => 4, //receive money
											'To_Payment_Method_Id' => 0,
											'Amount' => $paymentRequest['Amount'],
											'To_User_Id' => $usid,
											'From_User_Id' => $paymentRequest['From_User_Id'],
											'Tran_Status_Id' => 6,
											'Sig' => '+',
											'Amount_Received' => $paymentRequest['Amount'],
											'Third_Party_Tran_Id' => $ref_num,
											'Created_By' => $usid,
											'Last_Updated_By' => $usid,
										);
										$Transaction_id = $this->dynamic_model->insertdata('transactions', $paymentaddArr);

										//Then after insert into Tran_Charges table
										$chargeaddArr = array(
											'Transaction_Id' => $Transaction_id,
											'Charge_Type_Id' => 0,
											'Charge_Amt' => 0,
											'Created_By' => $usid,
											'Last_Updated_By' => $usid,
										);
										$Transaction_charge_id = $this->dynamic_model->insertdata('tran_charges', $chargeaddArr);

										//update amount into users table (add amount from sender and update wallet amount)
										$total_wallet_amount = $loguser['Current_Wallet_Balance'] + $received_amount;
										$userwalletdata['Current_Wallet_Balance'] = $total_wallet_amount;
										$updatewalletdata = $this->dynamic_model->updatedata('users', $userwalletdata, $usid);

										//update amount into users table (deduct amount to receiver and update wallet amount)
										$total_receiver_wallet = $otheruser['Current_Wallet_Balance'] - $paymentRequest['Amount'];
										$receiverwalletdata['Current_Wallet_Balance'] = $total_receiver_wallet;
										$updatereceiverwallet = $this->dynamic_model->updatedata('users', $receiverwalletdata, $otheruser['Id']);

										$updated_user_wallet = $this->dynamic_model->get_user_by_id($usid);

										//Used function to update request_share table
										$updatedata['Tran_Status_Id'] = "6";
										//$updatedata['Message']                = $actionMessage;
										$updatedata['Tran_Id'] = $Transaction_id;
										$updatedata['Last_Updated_By'] = $usid;
										$updatedata['Last_Updated_Date_Time'] = date('Y-m-d H:i:s');

										$decline_req = $this->dynamic_model->updatedata('request_share', $updatedata, $reqmoneyId);
										

										 //Notification functionality
				                         //Send Notification For Sender
										 $sender_name          = ucfirst($loguser['FullName']);
										$receiver_name            = ucfirst(strtolower($otheruser['FullName']));
		                                $User_Role = $this->dynamic_model->get_row('user_in_roles',array('User_Id'=> $loguser['Id']));
		                                $notification_to_sender = $User_Role['Device_Id'];

		                                $notification_title_sender = 'Dear '.$sender_name.',You have successfully received '.CURRENCY_SYMBOLE.''.number_format($paymentRequest['Amount'], 2).' from '.$receiver_name.' on '.date('d-m-Y '). ' at '.date('H:i A').' Ref.No: '.$ref_num.' Your new balance is '.CURRENCY_SYMBOLE.''.number_format(@$total_wallet_amount, 2);

		                                $notification_type_sender = 4; //Receive Money

	                                    if(!empty($User_Role['Device_Id']) && $User_Role['Device_Type'] == 'android' )
	                                    {
	                                        sendPushAndroid($notification_to_sender,$notification_title_sender, $notification_type_sender );
	                                    }
	                                    if(!empty($User_Role['Device_Id']) && $User_Role['Device_Type'] == 'ios' )
	                                    {
	                                        sendPushIos($notification_to_sender,$notification_title_sender, $notification_type_sender );
	                                    }
	                                   	//update Notification
				                        $notiDataArrRec = array('Req_Id' => '','Recepient_Id'=>$usid,'Notification_Text' =>$notification_title_sender, 'Tran_Type_Id' =>$notification_type_sender);
				                        //$insert_notification = $this->dynamic_model->insertdata('user_notifications', $notiDataArrRec);  									
										$where= array('Req_Id' => $reqmoneyId);
										$this->dynamic_model->updateRowWhere('user_notifications',$where,$notiDataArrRec);
				                         
				                         //Send Notification For Reciver

	        							$Rec_User_Role = $this->dynamic_model->get_row('user_in_roles', array('User_Id' => $otheruser['Id']));
		                              
		                                $notification_to_receiver = $Rec_User_Role['Device_Id']; 

	                                   	$notification_type_receiver  = 3; //Send Money
	                                   	$notification_title_receiver = 'Dear '.$receiver_name.',You have successfully sent '.CURRENCY_SYMBOLE.''.number_format($paymentRequest['Amount'], 2).' to '.$sender_name.' '.$loguser['Mobile_No'].' on '.date('d/m/Y '). ' at '.date('H:i A').' Ref.No: '.$ref_num.' Transaction cost '.CURRENCY_SYMBOLE.'0.00';

	                                    if (!empty($Rec_User_Role['Device_Id']) && $Rec_User_Role['Device_Type'] == 'android'){
									   sendPushAndroid($notification_to_receiver, $notification_title_receiver, $notification_type_receiver);
										
										}else if (!empty($Rec_User_Role['Device_Id']) && $Rec_User_Role['Device_Type'] == 'ios'){
											sendPushIos($notification_to_receiver, $notification_title_receiver, $notification_type_receiver);
										}
	                                    //Insert Notification
				                        $notiDataArr = array('Recepient_Id'=>$otheruser['Id'],'Notification_Text' =>$notification_title_receiver, 'Tran_Type_Id' =>$notification_type_receiver);
				                        $insert_notification = $this->dynamic_model->insertdata('user_notifications', $notiDataArr);

                                        $response = array('title_meassge'=>$notification_title_sender,'amount' => number_format($paymentRequest['Amount'], 2), 'transaction_date' => date('d M Y'), 'transaction_id' => $ref_num, 'wallet_amount' => $updated_user_wallet['Current_Wallet_Balance']);
										$arg['status'] = 1;
										$arg['message'] = $this->lang->line('sendmoney_accept_success');
										$arg['data'] = $response;
										//$arg['wallet_amount'] = $updated_user_wallet['Current_Wallet_Balance'];
									
								} else {
									$arg['status'] = 0;
									$arg['error'] = ERROR_FAILED_CODE;
									$arg['message'] = $this->lang->line('request_accept_already');
								}
							//}
						}
					}
				}
			}
		}
		echo json_encode($arg);
	}
	//Function used for decline send money request
	public function declineSendMoneyRequest() {
		$arg = array();
		//check user is active or not
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
					$arg['status'] = 101;
					$arg['error'] = 461;
					$arg['message'] = $result;
				} else {
					$_POST = $this->input->post();

					if($_POST == [])
					{
						$_POST = json_decode(file_get_contents("php://input"), true);
					}
					if ($_POST) {
						$this->form_validation->set_rules('reqmoneyId', 'Request money id', 'required|numeric');
						if ($this->form_validation->run() == FALSE) {
							$arg['status'] = 0;
							$arg['error'] = ERROR_FAILED_CODE;
							$arg['message'] = get_form_error($this->form_validation->error_array());
						} else {
							$usid = getuserid();
							$loguser = $this->dynamic_model->get_user_by_id($usid);

							$reqmoneyId = $this->input->post('reqmoneyId');
							$select_reason = $this->input->post('select_reason');
							$is_chat = $this->input->post('is_chat');
							$type = $this->input->post('type');
							$updatedataChat = array();
					
							$paymentRequest = $this->dynamic_model->get_row('request_share', array('Type' => 'Sendmoney_req', 'Id' => $reqmoneyId,'Tran_Status_Id'=>1));

							if ($paymentRequest){
								$updatedata['Tran_Status_Id'] = "4";
								//$updatedata['Message']                = $actionMessage;
								$updatedata['Rejection_Reason'] = $select_reason;
								$updatedata['Last_Updated_By'] = $usid;
								$updatedata['Last_Updated_Date_Time'] = date('Y-m-d H:i:s');

								$decline_req = $this->dynamic_model->updatedata('request_share', $updatedata, $reqmoneyId);

							
								//Receiving notification
								$total_amt = $paymentRequest['Amount'];
								$ref_num = $paymentRequest['Ref_Num'];

								$loganotheruser = $this->dynamic_model->get_user_by_id($paymentRequest['From_User_Id']);

								$User_Role = $this->dynamic_model->get_row('user_in_roles', array('User_Id' => $paymentRequest['From_User_Id']));

								$notification_to_receiver = $User_Role['Device_Id'];
								$sendername = strtoupper($loguser['FullName']);
								$receivername = strtoupper($loganotheruser['FullName']);

								$notification_title_receiver = 'Dear ' . $receivername . ', Your money sent of ' . CURRENCY_SYMBOLE . '' . number_format($total_amt,2) . ' to ' . $sendername . ' on ' . date('d-m-Y ') . 'at ' . date('H:i A') . ' Ref.No: ' . $ref_num . ' has been rejected.';

								$notification_type_receiver = 8; // Receive Money

								if (!empty($User_Role['Device_Id']) && $User_Role['Device_Type'] == 'android') {
									sendPushAndroid($notification_to_receiver, $notification_title_receiver, $notification_type_receiver);
								}
								if (!empty($User_Role['Device_Id']) && $User_Role['Device_Type'] == 'ios') {
									sendPushIos($notification_to_receiver, $notification_title_receiver, $notification_type_receiver);
								}

								//Insert Notification
								$notiDataArr = array('Recepient_Id' => $loganotheruser['Id'], 'Notification_Text' => $notification_title_receiver, 'Tran_Type_Id' => $notification_type_receiver);
								$insert_notification = $this->dynamic_model->insertdata('user_notifications', $notiDataArr);

								//Send Notification For Sender
								$Another_User_Role = $this->dynamic_model->get_row('user_in_roles', array('User_Id' => $paymentRequest['To_User_Id']));

								$notification_to_sender = $Another_User_Role['Device_Id'];
								$sender_name = strtoupper($loguser['FullName']);
                              
                                $notification_title_sender = 'Dear ' . $sender_name . ',You have declined ' . CURRENCY_SYMBOLE . '' . number_format($total_amt, 2) . ' from ' . strtoupper($loganotheruser['FullName']) . ' Account No.' . $loganotheruser['Mobile_No'] . '  on ' . date('d-m-Y ') . 'at ' . date('H:i A') . ' Ref.No: ' . $ref_num . '. Transaction Cost ' . CURRENCY_SYMBOLE . '0.00';

								$notification_type_sender = 8; // Receive Money

								if (!empty($Another_User_Role['Device_Id']) && $Another_User_Role['Device_Type'] == 'android') {
									sendPushAndroid($notification_to_sender, $notification_title_sender, $notification_type_sender);
								}
								if (!empty($Another_User_Role['Device_Id']) && $Another_User_Role['Device_Type'] == 'ios') {
									sendPushIos($notification_to_sender, $notification_title_sender, $notification_type_sender);
								}

								//Update Notification
								$notiDataArrSender = array('Req_Id' => '', 'Notification_Text' => $notification_title_sender, 'Tran_Type_Id' => $notification_type_sender);
								 $where= array('Req_Id' => $reqmoneyId);
								 $this->dynamic_model->updateRowWhere('user_notifications',$where,$notiDataArrSender); 
								
								$response=array('title_meassge'=>$notification_title_sender,'wallet_amount'=>number_format($loguser['Current_Wallet_Balance'],2)); 
								$arg['status'] = 1;
								$arg['message'] = $this->lang->line('request_decline_success');
								$arg['data'] = $response;
							} else {
								
								$arg['status'] = 0;
								$arg['error'] = ERROR_FAILED_CODE;
								$arg['message'] = $this->lang->line('request_decline_already');
							}
						}
					}
				}
			}
		}
		echo json_encode($arg);
	}
   //cron job for decline request every 5 minutes
   public function cancel_request_for_sendmoney(){
    	$paymentRequest = $this->dynamic_model->getdatafromtable('request_share', array('Type' =>'Sendmoney_req','Tran_Status_Id'=>1));
		//print_r($paymentRequest );die;
		if(!empty($paymentRequest))
		{
			foreach($paymentRequest as $value)
			{
                if(!empty($value['UnixTimestamp'])){
				$to_time=$value['UnixTimestamp']+(5*60);
				// echo $t_time =date('d m y H:i:s', $value['UnixTimestamp']).'--------------';
				// echo $to_time =date('d m y H:i:s', $createtime).'--------------';
				// echo $from_time =date('d m y H:i:s', time());die;
			   //echo $res=  round(abs($to_time - $from_time) / 60,2). " minute";
			    $from_time = time();
			    if($from_time > $to_time)
			    {
					$updatedata['Tran_Status_Id'] = "4";
					//$updatedata['Message']                = $actionMessage;
					//$updatedata['Rejection_Reason'] = $select_reason;
					$updatedata['Last_Updated_By'] = $value['To_User_Id'];
					$updatedata['Last_Updated_Date_Time'] = date('Y-m-d H:i:s');

					$decline_req = $this->dynamic_model->updatedata('request_share', $updatedata,$value['Id']);

					//Receiving notification
					$total_amt = $value['Amount'];
					$ref_num = $value['Ref_Num'];

					$loganotheruser = $this->dynamic_model->get_user_by_id($value['From_User_Id']);
					$loguser = $this->dynamic_model->get_user_by_id($value['To_User_Id']);

					$User_Role = $this->dynamic_model->get_row('user_in_roles', array('User_Id' => $value['From_User_Id']));

					$notification_to_receiver = $User_Role['Device_Id'];
					$sendername = strtoupper($loguser['FullName']);
					$receivername = strtoupper($loganotheruser['FullName']);

					$notification_title_receiver = 'Dear ' . $receivername . ', Your money sent of ' . CURRENCY_SYMBOLE . '' . number_format((float) $total_amt, 2, '.', '') . ' to ' . $sendername . ' on ' . date('d-m-Y ') . 'at ' . date('H:i A') . ' Ref.No: ' . $ref_num . ' has been rejected.';

					$notification_type_receiver = 8; // cancel

					if (!empty($User_Role['Device_Id']) && $User_Role['Device_Type'] == 'android') {
						sendPushAndroid($notification_to_receiver, $notification_title_receiver, $notification_type_receiver);
					}
					if (!empty($User_Role['Device_Id']) && $User_Role['Device_Type'] == 'ios') {
						sendPushIos($notification_to_receiver, $notification_title_receiver, $notification_type_receiver);
					}

					//Insert Notification
					$notiDataArr = array('Recepient_Id' => $loganotheruser['Id'], 'Notification_Text' => $notification_title_receiver, 'Tran_Type_Id' => $notification_type_receiver);
					$insert_notification = $this->dynamic_model->insertdata('user_notifications', $notiDataArr);

					//Send Notification For Sender
					$Another_User_Role = $this->dynamic_model->get_row('user_in_roles', array('User_Id' => $value['To_User_Id']));

					$notification_to_sender = $Another_User_Role['Device_Id'];
					$sender_name = strtoupper($loguser['FullName']);
		          
		            $notification_title_sender = 'Dear ' . $sender_name . ',You have declined ' . CURRENCY_SYMBOLE . '' . number_format($total_amt,2) . ' from ' . strtoupper($loganotheruser['FullName']) . ' Account No.' . $loganotheruser['Mobile_No'] . '  on ' . date('d-m-Y ') . 'at ' . date('H:i A') . ' Ref.No: ' . $ref_num . '. Transaction Cost ' . CURRENCY_SYMBOLE . '0.00';

					$notification_type_sender = 8; // Receive Money

					if (!empty($Another_User_Role['Device_Id']) && $Another_User_Role['Device_Type'] == 'android') {
						sendPushAndroid($notification_to_sender, $notification_title_sender, $notification_type_sender);
					}
					if (!empty($Another_User_Role['Device_Id']) && $Another_User_Role['Device_Type'] == 'ios') {
						sendPushIos($notification_to_sender, $notification_title_sender, $notification_type_sender);
					}
					//Update Notification
						$notiDataArrSender = array('Req_Id' => '', 'Notification_Text' => $notification_title_sender, 'Tran_Type_Id' => $notification_type_sender);
						 $where= array('Req_Id' => $reqmoneyId);
						 $this->dynamic_model->updateRowWhere('user_notifications',$where,$notiDataArrSender); 
		          //echo $res=  round(abs($to_time - $from_time) / 60,2). " 9minute";
	            }
	         }

            }
        }
    }
}