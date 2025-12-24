<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends My_Controller {

  private static $admin_id = null;

	public function __construct(){		
        parent::__construct();		
        $this->load->model('dynamic_model');
        $this->load->model('admin_model');
        $this->load->library('sendmail');
         if($this->session->userdata('logged_in')){
            $currentuser = getuserdetails();
           self::$admin_id = $currentuser['Id'];
        }

        
		$language = $this->input->get_request_header('language');
		if($language == "en"){
			$this->lang->load("message","english");
		}else {
			$this->lang->load("message","english");
		}
        
    }   	
	public function index(){
        if($this->session->userdata('logged_in')){
             $this->Dashboard();
        } else{
            $this->admintemplates('login');
        }
	}
	public function login(){
            $this->form_validation->set_error_delimiters("<p class='inputerror text text-danger error'>", "</p>");
            $this->form_validation->set_rules('usertype', 'Username', 'required');
            $this->form_validation->set_rules('useremail', 'Username', 'required|valid_email');
            $this->form_validation->set_rules('userpass', 'Password', 'required');
            if($this->form_validation->run() == FALSE)
            {
                    $error = array(
                              'usertype' =>form_error('usertype'),
                              'useremail' =>form_error('useremail'),
                              'userpass' =>form_error('userpass')
                     );
                    $return=array('status'=>false,'message'=>'All fields are mandatory..','data'=>$error);  
            }
            else
            {
                $checkdata = $this->dynamic_model->checkEmail($this->input->post('useremail'));	
                $usertype = $this->input->post('usertype');	
                if($checkdata){		
                     $hashed_password = encrypt_password($this->input->post('userpass'));
                     if($hashed_password == $checkdata[0]['Password']){
                            $wh= array('User_Id'=>$checkdata[0]['Id']);	
                            $role_id= getdatafromtable('user_in_roles',$wh,'User_Id,Role_Id');
                            $user_role = $role_id[0]['Role_Id'];
                            $session_array['session_userid'] = $checkdata[0]['Id'];		
                            $session_array['session_userrole'] = $user_role;
                            if($usertype == 1 || $usertype == 5){
                              // echo "<pre>";print_r($usertype . ' and ' . $user_role);die;
                              if($usertype == $user_role){

                                if($checkdata[0]['Is_Active'] == 1)
                                {
                                  $this->session->set_userdata('logged_in', $session_array);           
                                  $return=array('status'=> true,'message'=>'Login Successfully');
                                }
                                else{
                                  $return=array('status'=> false,'message'=>'User is inactive. Please contact the admin.');
                                }

                              //    if($usertype == 5){
                              //    // Function for add login data in table
                              //   $user_os        = getOS();
                              //   $user_browser   = getBrowser();
                              //   $device_details = "".$user_browser." on ".$user_os."";
                              //   $co = ip_info("Visitor", "Country"); // India
                              //   $cc = ip_info("Visitor", "Country Code"); // IN
                              //   $ca = ip_info("Visitor", "Address"); // Proddatur, Andhra Pradesh, India
                              //   //$ip = $_SERVER['REMOTE_ADDR'];
                              //   $ip = $_SERVER['HTTP_HOST'];
                              //   $ua = $_SERVER['HTTP_USER_AGENT'];
                              //   $loc = "$ca ($cc)";
                              //   $logindata = array('User_Id'          => $checkdata[0]['Id'],
                              //                      'Ip_Address'       => $ip,
                              //                       'Location'        => $loc,
                              //                       'User_Os_Platform'=> $device_details
                              //                      );
                              //   $loginid = $this->dynamic_model->insertdata('user_logins', $logindata);
                              //  }
                                
                              }
                              else{
                                $return=array('status'=> false,'message'=>'User role and credential do not matched.');

                              }
                            }else{        
                            $return=array('status'=> false,'message'=>'You cannot authorized for this admin panel');

                            }
                            

                            //  if($user_role == 1){
                            //    if($user_role !== 1){
                            //    // Function for add login data in table
                            //   $user_os        = getOS();
                            //   $user_browser   = getBrowser();
                            //   $device_details = "".$user_browser." on ".$user_os."";
                            //   $co = ip_info("Visitor", "Country"); // India
                            //   $cc = ip_info("Visitor", "Country Code"); // IN
                            //   $ca = ip_info("Visitor", "Address"); // Proddatur, Andhra Pradesh, India
                            //   //$ip = $_SERVER['REMOTE_ADDR'];
                            //   $ip = $_SERVER['HTTP_HOST'];
                            //   $ua = $_SERVER['HTTP_USER_AGENT'];
                            //   $loc = "$ca ($cc)";
                            //   $logindata = array('User_Id'          => $checkdata[0]['Id'],
                            //                      'Ip_Address'       => $ip,
                            //                       'Location'        => $loc,
                            //                       'User_Os_Platform'=> $device_details
                            //                      );
                            //   $loginid = $this->dynamic_model->insertdata('user_logins', $logindata);
                            //  }
                            //   $this->session->set_userdata('logged_in', $session_array);           
                            //   $return=array('status'=> true,'message'=>'Login Successfully');
                            // }else{        
                            //   $return=array('status'=> false,'message'=>'You cannot authorized for this admin panel');

                            // }
                            
                            
                        }else{	  
                            $return=array('status'=> false,'message'=>'Password Does Not Match');
                        }		
                }else{	
                      $return=array('status'=>false,'message'=>'Invalid Credentials');      
                }
           }	
         echo json_encode($return);   
	}
  public function Dashboard(){
        if($this->session->userdata('logged_in')){
            $adminid =  self::$admin_id; 
            $trans_where=array('Tran_Status_Id'=>6); 
            $data['transcation_data']= getdatafromtable('transactions',$trans_where,'COUNT(*) as v_count,SUM(Amount) as v_amount,SUM(Charge) as v_charge');

           //Sender count
            $condition=array('Role_Id ='=>2);
            $on='users.Id = user_in_roles.User_Id';
            $data['sender_data']=$this->dynamic_model->getTwoTableData('User_Id,Role_Id,COUNT(*) as sender_count','users','user_in_roles',$on,$condition);

            //Receiver count
            $condition=array('Role_Id ='=>3);
            $on='users.Id = user_in_roles.User_Id';
            $data['receiver_data']=$this->dynamic_model->getTwoTableData('User_Id,Role_Id,COUNT(*) as reciver_count','users','user_in_roles',$on,$condition);
            $data['loginuser_id']=$adminid;
            $this->admintemplates('dashboard' ,$data);
        }else{
            redirect('admin/');
        }
  }
  public function profile_setting(){
        if($this->session->userdata('logged_in')){
            $data['title']='Profile Settings';
            $userid =  self::$admin_id;  
            $wh     =  array('Id'=>$userid); 
            $data['userdata']= getdatafromtable('users',$wh,'Id,FirstName,LastName,Mobile_No,Email,Profile_Pic'); 
            $this->admintemplates('profile_admin',$data);
        }else{
           redirect('admin/');
        }
  }
  public function profile_setting_action(){
            $where = array('Id'=> self::$admin_id);
            $userdata = getdatafromtable('users',$where,'Id,Email,Mobile_No');

            $this->form_validation->set_error_delimiters("<p class='inputerror text text-danger error'>","</p>");
            $this->form_validation->set_rules('fname', 'First Name', 'required');
            $this->form_validation->set_rules('lname', 'Last Name', 'required');
            if($this->input->post('mobile_no')!== $userdata[0]['Mobile_No']){
             $this->form_validation->set_rules('mobile_no', 'Phone No.', 'required|numeric|min_length[10]|max_length[10]|is_unique[Users.Mobile_No]');
            }
            if($this->input->post('email')!== $userdata[0]['Email']){
             $this->form_validation->set_rules('email', 'Email ', 'required|valid_email|is_unique[Users.Email]');
            }
            if($this->form_validation->run() == FALSE){  
                $error=array(
                              'fname'      =>form_error('fname'),
                              'lname'      =>form_error('lname'),
                              'email'     =>form_error('email'),
                              'mobile_no' =>form_error('mobile_no')  
                           );
                    $return = array('status'=>false,'message'=>'All fields are mandatory..','data'=>$error);
            }else{  
                    $updatedata = array();  
                   if(!empty($_FILES['image']['name'])){
                      $profile_img  = $this->dynamic_model->fileupload('image', './uploads/user');
                      $updatedata['Profile_Pic']  = $profile_img;
                    }
                    $userid                     = self::$admin_id;  
                    $updatedata['FirstName']    = $this->input->post('fname');
                    $updatedata['LastName']     = $this->input->post('lname');
                    $updatedata['FullName']     = $this->input->post('fname').' '.$this->input->post('lname');
                    $updatedata['Email']        = $this->input->post('email');
                    $updatedata['Mobile_No']    = $this->input->post('mobile_no');
                  
                     
                    $where=array('Id'=>$userid );
                    $profileupdate= $this->dynamic_model->updateRowWhere('users',$where,$updatedata);
                
                    $return=array('status'=>true,'message'=>'Updated Successfully','data'=>'');  
                }
              echo json_encode($return);        
  }
    // public function alphanumeric_password($str){
           
    //     return (! preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $str)) ? FALSE : TRUE;
         
    // }
  public function change_password(){

            $this->form_validation->set_error_delimiters("<p class=' inputerror text text-danger  error'>", "</p>");
            $this->form_validation->set_rules('current_password', 'Current Password', 'required');
            $this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[6]|max_length[18]|matches[confirm_password]');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required');
            if ($this->form_validation->run() == FALSE){  
                $error=array( 
                              'new_password'     =>form_error('new_password'),
                              'confirm_password' =>form_error('confirm_password'),
                              'current_password' =>form_error('current_password')    
                           );
                    $return = array('status'=>false,'message'=>'All fields are mandatory..','data'=>$error);
            }else{  
                    $updatedata = array();  
                    $userid                = self::$admin_id;  
                    $current_pass          = encrypt_password($this->input->post('current_password'));
                    $updatedata['Password']= encrypt_password($this->input->post('new_password')); 
                    $where=array('Id'=>$userid );
                    $userdata =getdatafromtable('users',$where,'Id,Password');
                    if($current_pass == $userdata[0]['Password'] ){
                         $profileupdate= $this->dynamic_model->updateRowWhere('users',$where,$updatedata);
                         $return=array('status'=>true,'message'=>'Change Password Successfully','data'=>'');  

                    }else{

                         $return=array('status'=>false,'message'=>'Current Password Does Not Match..','data'=>'');  
                    }      
                }
              echo json_encode($return);        
  }
  public function page_settings($type=''){
    	 if($this->session->userdata('logged_in'))
       {
          $permission_data=get_permission_detail(self::$admin_id);
          $find_permission= unserialize($permission_data[0]['Permission']);  
          if($find_permission['setting']==1)
          {  
            if($type == 1){
              $data['title']='Terms & Conditions ';
            }elseif($type == 2){
              $data['title']='Privacy & Policy';
            }else{
              $data['title']='About Us';
            }  
            $where = array('Id'=>$type);
            $data['option_data'] = getdatafromtable('options',$where);
            $this->admintemplates('page_settings',$data); 
          }else{
              redirect('admin');
          } 
	      }else{
            redirect('admin');
        }
  }
  public function page_settings_action(){
            $type= $this->input->post('option_type');
            if (empty($_FILES['file_settings']['name'])){
                  $return=array('status'=>false,'message'=>'Please Select Valid PDF File'); 
              }else{
                      $optiondata  = $this->dynamic_model->pdfFileUpload('file_settings', '/uploads/static_contents');
                      if($type == 1){
                           $where = array('Option_Name'=>'terms_condition');  
                      }elseif($type == 2){
                         $where = array('Option_Name'=>'privacy_policy'); 

                      }else{
                            $where = array('Option_Name'=>'about_us'); 
                      }
                      $updatedata=array('Option_Value' => $optiondata);       
                      $setting_data = $this->dynamic_model->updateRowWhere('Options',$where,$updatedata);
                    $return=array('status'=>true,'message'=>'Upload Successfully'); 
                  }
	         echo json_encode($return);          
  }
  public function transaction_fee(){
    	 if($this->session->userdata('logged_in'))
       {
          $permission_data=get_permission_detail(self::$admin_id);
          $find_permission= unserialize($permission_data[0]['Permission']);  
          if($find_permission['setting']==1)
          {  
            $data['title']          ='Transactions Fee'; 
            $where =array('Type !='=>'point_earns');
            $data['transaction_fee']= getdatafromtable('fee',$where); 
            $this->admintemplates('transaction_fees',$data);
          }else{
             redirect('admin');
          }
        }else{
           redirect('admin');
        }
  }
  public function transaction_fee_action(){
        $this->form_validation->set_error_delimiters("<p class='inputerror text text-danger error'>", "</p>");
        $this->form_validation->set_rules('service_name', 'Service Name', 'required');  
        $this->form_validation->set_rules('review_fee','Amount','required');  
        $this->form_validation->set_rules('charge_fee',' Fee','required');  
        if ($this->form_validation->run() == FALSE){  
            $error=array(
                          'service_name'=>form_error('service_name') , 
                          'review_fee' =>form_error('review_fee') , 
                          'charge_fee'  =>form_error('charge_fee')        
                       );
                $return = array('status'=>false,'message'=>'All fields are mandatory..','data'=>$error);
        }else{
               $transactionFeeId	= $this->input->post('transactionFeeId');
               $service_name    	= $this->input->post('service_name');
               $review_fee     		= $this->input->post('review_fee');
               $charge_fee      	= $this->input->post('charge_fee'); 

                if(empty($transactionFeeId))
                {
                   $insertdata=array(

                                'Service_Name'=> $service_name,
                                'Review_Fee'  => $review_fee,
                                'Fee' 				=> $charge_fee,
                                'Status'      => ($this->input->post('trans_status')) ? 1 : 0,
                                'Type'        => 'all',
                                'Created_at'  => date('Y-m-d H:i:s'),
                                'Created_By'  => self::$admin_id    
                             );
                $tranfee_data = $this->dynamic_model->insertdata('fee',$insertdata);
                }else{
                     $updatedata=array(

                              
                                'Service_Name'=> $service_name,
                                'Review_Fee'  => $review_fee,
                                'Fee' 				=> $charge_fee,
                                'Status'      => ($this->input->post('trans_status')) ? 1 : 0,
                                'Type'       	=> 'all',
                                'Modified_at' => date('Y-m-d H:i:s'),
                                'Modified_by' => self::$admin_id 
                                
                             );
                $where        = array('Id'=>$transactionFeeId);
                $tranfee_data = $this->dynamic_model->updateRowWhere('Fee',$where,$updatedata);
                }
                $return=array('status'=>true,'message'=>'Saved Successfully','data'=>'');  
            }
          echo json_encode($return);        
  }
  public function points_earn(){
       if($this->session->userdata('logged_in')){
            $data['title']          ='Points Earn'; 
            $where =array('Type !='=>'all');
            $data['transaction_fee']= getdatafromtable('fee',$where); 
            $this->admintemplates('points_earn',$data);
        }else{
           redirect('admin/');
        }
  }
  public function points_earn_action(){
        $this->form_validation->set_error_delimiters("<p class='inputerror text text-danger error'>", "</p>");
        $this->form_validation->set_rules('service_name', 'Service Name', 'required');  
        $this->form_validation->set_rules('review_fee','Review Fee','required');  
        $this->form_validation->set_rules('charge_fee','Charge Fee','required');  
        if ($this->form_validation->run() == FALSE){  
            $error=array(
                          'service_name'=>form_error('service_name') , 
                          'review_fee' =>form_error('review_fee') , 
                          'charge_fee'  =>form_error('charge_fee')        
                       );
                $return = array('status'=>false,'message'=>'All fields are mandatory..','data'=>$error);
        }else{
               $transactionFeeId  = $this->input->post('transactionFeeId');
               $service_name      = $this->input->post('service_name');
               $review_fee        = $this->input->post('review_fee');
               $charge_fee        = $this->input->post('charge_fee'); 

                if(empty($transactionFeeId))
                {
                   $insertdata=array(

                                'Service_Name'=> $service_name,
                                'Review_Fee'  => $review_fee,
                                'Fee'         => $charge_fee,
                                'Status'      => ($this->input->post('trans_status')) ? 1 : 0,
                                'Type'        => 'point_earns',
                                'Created_at'  => date('Y-m-d H:i:s'),
                                'Created_By'  => self::$admin_id    
                             );
                $tranfee_data = $this->dynamic_model->insertdata('fee',$insertdata);
                }else{
                     $updatedata=array(

                                'Service_Name'=> $service_name,
                                'Review_Fee'  => $review_fee,
                                'Fee'         => $charge_fee,
                                'Status'      => ($this->input->post('trans_status')) ? 1 : 0,
                                'Type'        => 'point_earns',
                                'Modified_at' => date('Y-m-d H:i:s'),
                                'Modified_by' => self::$admin_id         
                             );
                $where        = array('Id'=>$transactionFeeId);
                $tranfee_data = $this->dynamic_model->updateRowWhere('Fee',$where,$updatedata);
                }
                $return=array('status'=>true,'message'=>'Saved Successfully','data'=>'');  
            }
          echo json_encode($return);        
  }
  public function logo_setting(){   
       if($this->session->userdata('logged_in'))
       {
          $permission_data=get_permission_detail(self::$admin_id);
          $find_permission= unserialize($permission_data[0]['Permission']);  
          if($find_permission['setting']==1)
          {  
              $data['title']          ='Logo And Icon Setting'; 
              $data['general_data']= getdatafromtable('general_setting'); 
              $this->admintemplates('logo_setting',$data);
          }else{
             redirect('admin');
          }
        }else{
           redirect('admin');
        }
  } 
  public function logo_setting_action(){
        if(empty($_FILES['logo_image']['name'] || $_FILES['favicon_image']['name'])){
              $return=array('status'=>false,'message'=>'Please Select Valid Image'); 
          }else{
                  if(!empty($_FILES['logo_image']['name'])){
                        $logo_image  = $this->admin_model->fileupload('logo_image','assets/images');
                        if($logo_image){
                        $where        = array('Id'=>1 );
                        $updatedata   = array('slogo' => $logo_image);       
                        $setting_data = $this->dynamic_model->updateRowWhere('general_setting',$where,$updatedata);
                      }
                  }   
                  if(!empty($_FILES['favicon_image']['name'])){
                      $favicon_image  = $this->admin_model->fileupload('favicon_image', 'assets/images/');
                      if($favicon_image){
                      $where        = array('Id'=>1);
                      $updatedata   = array('sfavicon' => $favicon_image);       
                      $setting_data = $this->dynamic_model->updateRowWhere('general_setting',$where,$updatedata);
                    }
                  }
                   
                $return=array('status'=>true,'message'=>'Upload Successfully'); 
              }
         echo json_encode($return);          
  }
  public function roles(){
       if($this->session->userdata('logged_in'))
       {
          $permission_data=get_permission_detail(self::$admin_id);
          $find_permission= unserialize($permission_data[0]['Permission']);  
          if($find_permission['admin_management']==1)
          {  
              $data['title']     ='Add Role';  
              $data['roles_data']= getdatafromtable('roles'); 
              $this->admintemplates('roles',$data);
          }else{
             redirect('admin');
          }
        }else{
           redirect('admin');
        }
  } 
  public function role_action(){
            $this->form_validation->set_error_delimiters("<p class='inputerror text text-danger error'>", "</p>");
            $this->form_validation->set_rules('role_name', 'Role Name ', 'required');
            if ($this->form_validation->run() == FALSE){  
                  $error=array('role_name'=>form_error('role_name'));
                  $return = array('status'=>false,'message'=>'All fields are mandatory..','data'=>$error);
            }else{    
                   $roleid    = $this->input->post('role_id');
                   $role_name = $this->input->post('role_name');
                   $status    = $this->input->post('status');
                    if(empty($roleid))
                    {
                       $insertdata=array(

                                    'Role_Name'           => $role_name,
                                    'Status'              => ($status) ? 1 : 0,
                                    'Created_By'          => self::$admin_id,   
                                    'Creation_Date_Time'  => date('Y-m-d H:i:s')
                                 );
                    $roledata = $this->dynamic_model->insertdata('roles',$insertdata);
                    }else{
                         $updatedata=array(

                                    'Role_Name'              => $role_name,
                                    'Status'                 =>($status) ? 1 : 0,
                                    'Last_Updated_By'        => self::$admin_id,   
                                    'Last_Updated_Date_Time' => date('Y-m-d H:i:s')
                                    
                                 );
                    $where        = array('Id'=>$roleid);
                    $roledata = $this->dynamic_model->updateRowWhere('roles',$where,$updatedata);
                    } 
                    $return=array('status'=>true,'message'=>'Saved Successfully');            
               }
              echo json_encode($return);        
  }
  public function set_role_permission($user_id=''){
         if($this->session->userdata('logged_in'))
         {           
              $permission_data=get_permission_detail(self::$admin_id);
              $find_permission= unserialize($permission_data[0]['Permission']);  
              if($find_permission['admin_management']==1)
              {  
                $data['title']          ='Set Permission'; 
                $where                  = array('User_Id'=>base64_decode($user_id));
                $data['permission_data']= getdatafromtable('admin_roles_permission',$where); 
                $this->admintemplates('set_role_permission',$data);
              }else{
                 redirect('admin');
              }
          }else{
             redirect('admin');
          }
  } 
  public function set_role_permission_action(){

                $userid           = base64_decode($this->input->post('userid'));
                $dashboard        =  1;
                $user_manage      = ($this->input->post('user_manage')) ? 1 : 0 ;
                $merchant_manage  = ($this->input->post('merchant_manage')) ? 1 : 0 ;
                $withdraw         = ($this->input->post('withdraw')) ? 1 : 0 ;
                $deposit          = ($this->input->post('deposit')) ? 1 : 0 ;
                $request          = ($this->input->post('request')) ? 1 : 0 ;
                $transaction      = ($this->input->post('transaction')) ? 1 : 0 ;
                $send_money       = ($this->input->post('send_money')) ? 1 : 0 ;
                $sharebill_req    = ($this->input->post('sharebill_request')) ? 1 : 0 ;
                $qrcode           = ($this->input->post('qrcode')) ? 1 : 0 ;
                $pay_bill         = ($this->input->post('pay_bill')) ? 1 : 0 ;
                $manage_promocode = ($this->input->post('manage_promocode')) ? 1 : 0 ;
                $biller_manage    = ($this->input->post('biller_manage')) ? 1 : 0 ;
                $feedback         = ($this->input->post('feedback')) ? 1 : 0 ;
                $trx_limit        = ($this->input->post('trx_limit')) ? 1 : 0 ;
                $setting          = ($this->input->post('setting')) ? 1 : 0 ;
                //$website          = ($this->input->post('website')) ? 1 : 0 ;
                $admin_management = 0;
                
                $where            = array('User_Id'=> $userid);
                $permission_data  = getdatafromtable('admin_roles_permission',$where); 
                if(empty($permission_data))
                {  
                   $permission=array(

                                'dashboard'        => $dashboard,
                                'user_manage'      => $user_manage,
                                'merchant_manage'  => $merchant_manage,   
                                'withdraw'         => $withdraw,
                                'deposit'          => $deposit,
                                'request'          => $request,
                                'transaction'      => $transaction,
                                'send_money'       => $send_money,
                                'sharebill_request' => $sharebill_req,
                                'qrcode'           => $qrcode,
                                'pay_bill'         => $pay_bill,
                                'manage_promocode' => $manage_promocode,
                                'biller_manage'    => $biller_manage,
                                'feedback'         => $feedback,
                                'trx_limit'        => $trx_limit,
                                'setting'          => $setting,
                                //'website'          => $website,
                                'admin_management' => $admin_management
                                
                             );
                $insertdata= array('Permission'=>serialize($permission));
                $roledata = $this->dynamic_model->insertdata('admin_roles_permission',$insertdata);
                   $return=array('status'=>true,'message'=>'Saved Successfully');  
                }else{
                     $permission=array(

                                'dashboard'        => $dashboard,
                                'user_manage'      => $user_manage,
                                'merchant_manage'  => $merchant_manage,   
                                'withdraw'         => $withdraw,
                                'deposit'          => $deposit,
                                'request'          => $request,
                                'transaction'      => $transaction,
                                'send_money'       => $send_money,
                                'sharebill_request'=> $sharebill_req,
                                'qrcode'           => $qrcode,
                                'pay_bill'         => $pay_bill,
                                'manage_promocode' => $manage_promocode,
                                'biller_manage'    => $biller_manage,
                                'feedback'         => $feedback,
                                'trx_limit'        => $trx_limit,
                                'setting'          => $setting,
                                //'website'          => $website,
                                'admin_management' => $admin_management
                                
                             ); 
                $updatedata= array('Permission'=> serialize($permission));
                $where        = array('User_Id'=>$userid); 
                $roledata = $this->dynamic_model->updateRowWhere('Admin_Roles_Permission',$where,$updatedata);
                   $return=array('status'=>true,'message'=>'Saved Successfully');  
                } 
         echo json_encode($return);        
  }
  public function staff($satff_id=''){
         $satff_id         = base64_decode($satff_id);
         if($this->session->userdata('logged_in'))
         {           
              $permission_data=get_permission_detail(self::$admin_id);
              $find_permission= unserialize($permission_data[0]['Permission']);  
              if($find_permission['admin_management']==1)
              {  
                $data['title']           = 'Manage Staff'; 
                $admin_id                = self::$admin_id;
                $data['staff_data']      = $this->admin_model->add_staff($admin_id);  
                $data['staff_data_by_id']= $this->admin_model->add_staff_by_id($admin_id,$satff_id); 
                $data['role_data']       = $this->admin_model->get_role_data(); 
                $this->admintemplates('staff',$data);
              }else{
                 redirect('admin');
              }
          }else{
             redirect('admin');
          }  
  } 
  public function staff_action(){
         $user_id         = base64_decode($this->input->post('user_id'));
        $this->form_validation->set_error_delimiters("<p class='inputerror text text-danger error'>", "</p>");
        $this->form_validation->set_rules('fname', 'First Name', 'required');
        $this->form_validation->set_rules('lname', 'Last Name', 'required');
       if(empty($user_id)){ 
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[Users.Email]');
         $this->form_validation->set_rules('password', 'Password', 'required');
         $this->form_validation->set_rules('mobile', 'Mobile number', 'required|numeric|greater_than[0]|is_unique[Users.Mobile_No]',array('greater_than'=>'Mobile number should be greater than zero '));
       } 
        if ($this->form_validation->run() == FALSE){   
              $error=   array(
                             'fname'      =>form_error('fname'),
                             'lname'      =>form_error('lname'),
                             'email'      =>form_error('email'),
                             'mobile'     =>form_error('mobile'),
                             'password'   =>form_error('password'),
                             'status'     =>form_error('status')
                           ); 
              $return = array('status'=>false,'message'=>'All fields are mandatory..','data'=>$error);
        }else{ 
                $admin_id         = self::$admin_id; 
                $fname            = $this->input->post('fname');
                $lname            = $this->input->post('lname');
                $email            = $this->input->post('email') ;
                $mobile           = $this->input->post('mobile');
                $password         = $this->input->post('password');
                $status           = ($this->input->post('status')) ? 1 : 0;
                $role             = $this->input->post('role');     
                $where            = array('Id'=> $user_id);
                $staff_data       = getdatafromtable('users',$where); 
                if(empty($staff_data))
                {  
                   $insertdata=array(

                                'FirstName'  => $fname,
                                'LastName'   => $lname,
                                'Email'      => $email,
                                'Mobile_No'  => $mobile,   
                                'Password'   => encrypt_password($password),
                                'Is_Active'  => $status,
                                'Created_By' => $admin_id      
                             );
                           
                            $userid         = $this->dynamic_model->insertdata('users',$insertdata);
                            $role_data= array(
                                                'User_Id'    => $userid,
                                                'Role_Id'    => $role,
                                                'Created_By' => $admin_id   
                                                );
                            $users_roles_id = $this->dynamic_model->insertdata('user_in_roles', $role_data);

                            //insert also Admin_Roles_Permission table
                           $insertarray= array('Role_Id'=>$role,'User_Id'=>$userid);
                           $this->dynamic_model->insertdata('admin_roles_permission',$insertarray);
                           $return=array('status'=>true,'message'=>'Saved Successfully');  
                }else{
                    $updatedata=array(

                                'FirstName'  => $fname,
                                'LastName'   => $lname,
                                //'Email'      => $email,
                                //'Mobile_No'  => $mobile,     
                                'Is_Active'  => $status,
                                'Last_Updated_By' => $admin_id      
                             );
                          $where    = array('Id'=>$user_id); 
                          $userdata = $this->dynamic_model->updateRowWhere('users',$where,$updatedata);      
                          $role_data =array( 
                                        'Role_Id'    => $role,
                                        'Last_Updated_By' => $admin_id   
                                        );
                          $role_where    = array('User_Id'=>$user_id); 
                          $roledata = $this->dynamic_model->updateRowWhere('user_in_roles',$role_where,$role_data);
                          $return=array('status'=>true,'message'=>'Saved Successfully');  
                } 
             }
            echo json_encode($return);        
  }
  public function search_user()
  {
        $user_search= $this->input->post('q');
        $search_item = $this->admin_model->search_users($user_search); 
        
        if(!empty($user_search))
        {
          if(!empty($search_item))
          {                    
            foreach($search_item as $key => $value)
            {   
                if ($value['Role_Id']==2) {
                  $final_data[]='<a href="'.base_url().'admin/user_details/'.base64_encode($value['User_Id']).'/1"><li class="listitem list-group-item" >'.ucwords(strtolower($value['FirstName'].' '.$value['LastName'])).'</li></a>'; 

                }else{
                  $final_data[]='<a href="'.base_url().'admin/user_details/'.base64_encode($value['User_Id']).'/2"><li class="listitem list-group-item" >'.ucwords(strtolower($value['FirstName'].' '.$value['LastName'])).'</li></a>';    
              }
            } 
             $return= array('status' => true, 'data'=>$final_data);                   
          }
          else
          {
              $final_data='<li class="listitem list-group-item" >No Data Found</li>';
              $return= array('status' => false, 'data'=>$final_data);  
          }
        }else
        {
            $final_data='';
            $return= array('status' => false, 'data'=>$final_data);  
        }
        echo json_encode($return);
  }
  public function search_user_submit()
  {
        $user_search= $this->input->post('q');
        $search_item = $this->admin_model->search_users($user_search); 
        if(!empty($user_search))
        {
          if(!empty($search_item))
          {                    
            foreach($search_item as $key => $value)
            {   
                if ($value['Role_Id']==2) { 
                   $url= base_url().'admin/user_details/'.base64_encode($value['User_Id']).'/1';
                  redirect($url, 'refresh'); 
                }else{
                  $url= base_url().'admin/user_details/'.base64_encode($value['User_Id']).'/2';
                 redirect($url, 'refresh'); 
              }
            }                    
          }
          else
          {
            
            redirect('/admin/dashboard', 'refresh');
          }
        }else
        {
           redirect('/admin/dashboard', 'refresh');
        } 
  }
  public function email_password_exists(){
     $admin_id         = self::$admin_id;   
     $pass = encrypt_password($this->input->post('pass'));
     $where =array('Id'=>$admin_id);
   
    $final_data = getdatafromtable('users',$where,'Id,Email,Password'); 
    if($final_data[0]['Password'] == $pass){
         $return=array('status'=>true,'message'=>'Saved Successfully','data'=>0);  
    }else{
          $message = 'Password does not match'; 
          $return=array('status'=>false,'message'=> $message,'data'=>1);  
    }
    echo json_encode($return);
  }
   public function forgot_password(){
       $this->load->view('admin/forgot_password');
    }
    public function forgot_password_action()
    {
      $this->form_validation->set_error_delimiters("<p class='error text text-danger ' style='margin-right:240px;'>","</p>");
      $this->form_validation->set_rules('email','email','required|valid_email',array('required'=>'Email field is required','valid_email'=>'Please enter valid email')); 
        if($this->form_validation->run() == FALSE){  
          $error=array(
                        'user_email'=>form_error('user_email')        
                       );                
                $return = array('status'=>false,'message'=>'','data'=>$error);
        }else{ 
                $email = $this->input->post('email');
                $check_email= $this->dynamic_model->getdatafromtable('users',array('Email'=>$email));
                if(!empty($check_email)){
                    $role_check =$this->db->where_not_in('Role_Id',array(2,3))->get('User_In_Roles')->result();
                    if($role_check){
                      $fullname= name_format($check_email[0]['FullName']);
                      $password =substr(md5(microtime()),rand(0,26),6);
                      $enc_user = encode($check_email[0]['Id']);
                      $url = site_url().'admin/reset_password?userid='.$enc_user;
                      $where1 = array(
                              'slug' => 'forgot_password_admin'
                          );
                      $mailtemplate = $this->dynamic_model->getdatafromtable('manage_notification_mail', $where1);
                      if(!empty($mailtemplate)){
                          $desc_data= str_replace(array("{USERNAME}","{URL}"),array($fullname,$url),$mailtemplate[0]['description']);
                          $data['subject']     = ucwords($mailtemplate[0]['subject']); 
                          $data['description'] = $desc_data;
                          $data['body'] = "";
                          $msg = $this->load->view('emailtemplate',$data, true);
                          $mailsent=$this->sendmail->sendmailto($email,$mailtemplate[0]['subject'],"$msg");
                          if($mailsent == 1){
                              $updatedata=array('Is_Email_Verified'=>0);
                              $where = array('Email' =>$email);
                              $update = $this->dynamic_model->updateRowWhere('users',$where,$updatedata);
                              $return = array('status'=>true,'message'=>'Email send succesfully ..Please check your mail for forgot password');    
                          }else{
                               $return = array('status'=>false,'message'=>'Server problem! Email Not send');
                          }
                             
                      }else{
                            $return = array('status'=>false,'message'=>'Something went wrong..');
                      } 
                    }else{
                          $return = array('status'=>false,'message'=>'You have not authorized for this panel!');
                    }
                   
                }else {
                    $return = array('status'=>false,'message'=>'Invalid Email ID!');
                    
                }       
      } 
      echo json_encode($return);
  }
  public function reset_password(){
    $user_data = getdatafromtable('users',[],'Id,Email,Is_Email_Verified'); 
    $data['is_verify_email']=$user_data[0]['Is_Email_Verified'];
   $this->load->view('admin/forgot_password',$data);
  }
  public function reset_password_action(){

    $this->form_validation->set_error_delimiters("<p class=' inputerror text text-danger  error'>", "</p>");
    $this->form_validation->set_rules('password','Password','required');
    $this->form_validation->set_rules('confirm_password','Confirm Password','required|min_length[6]|max_length[18]|matches[confirm_password]');

    if($this->form_validation->run() == FALSE){  
        $error=array( 
                      'password'     =>form_error('password'),
                      'confirm_password' =>form_error('confirm_password')    
                    );
            $return = array('status'=>false,'message'=>'All fields are mandatory..','data'=>$error);
    }else{  
            $updatedata = array();  
            $userid                = $this->input->post("user_id");  
            $password              = $this->input->post('password');
            $confirm_password      = $this->input->post('confirm_password'); 
            $where=array('Id'=>decode($userid));
            $userdata =getdatafromtable('users',$where,'Id,Password');
            if(!empty($userdata))
            {
                if($password ==$confirm_password)
                {
                   $updatedata['Password']= encrypt_password($password); 
                   $updatedata['Is_Email_Verified']=1; 
                   $profileupdate= $this->dynamic_model->updateRowWhere('users',$where,$updatedata);
                   $return=array('status'=>true,'message'=>'Change Password Successfully','data'=>'');  
                }else{
                    $return=array('status'=>false,'message'=>'Password Does Not Match..','data'=>'');  
                } 
            }else{
                   $return=array('status'=>false,'message'=>'Something went wrong','data'=>'');  
            }      
        }
     echo json_encode($return);        
  }
   public function all_notification_mails(){
        // $permission_data=get_permission_detail(self::$admin_id);
        // $find_permission= unserialize($permission_data[0]['Permission']);    
        // if($find_permission['user_manage']==1){
         $data['title']='All Email Templates';
         $this->admintemplates('settings/all_notification_mails',$data);
        // }else{
        //     redirect('admin');
        // }  
    }
    public function allNotificationMailajaxslist(){  
        $start         =  $this->input->get('start'); 
        $length        =  $this->input->get('length'); 
        $draw          =  $this->input->get('draw'); 
        $order         =  $this->input->get('order');
        if(!empty($order)){ 
            if($order[0]['column']==1){
                $column_name='Advertisement_Title';
            }else{
                $column_name='Creation_Date_Time';
            }
        }   
        $totalRecord      = $this->admin_model->allNotificationMails(true);
        $getRecordListing = $this->admin_model->allNotificationMails(false,$start,$length,$column_name, $order[0]['dir']);
        $recordListing = array();
        $content='[';
        $i=0;       
        $srNumber=$start;       
        if(!empty($getRecordListing)) {
            $actionContent = '';
            foreach($getRecordListing as $recordData) {
                $userListData  = array(); //set default array
                $actionContent = ''; // set default empty
                $content .='[';
                $recordListing[$i][0]= $srNumber+1;
                $recordListing[$i][1]= $recordData->subject;
                $recordListing[$i][2]= $recordData->description;
                // $del_Id='id';
                // $table='manage_notification_mail';
                // $field='Status';
                // $urls = base_url('admin/home/updateStatus');
               
                // if($recordData->status=='Active'){
                //     $actionContent .='<a class="btn statussucc activate_br waves-effect" href="javascript:void(0);" onclick="sweetalert('.$recordData->id.', \''.$recordData->status.'\', \''.$urls.'\' , \''.$table.'\', \''.$field.'\',\''.$del_Id.'\');" title="Active">Active</a>';
                // }else{ 
                //     $actionContent .='<a class="btn statussucc deactivate_br waves-effect" href="javascript:void(0);" onclick="doc_status('.$recordData->id.', \''.$recordData->status.'\', \''.$urls.'\', \''.$table.'\', \''.$field.'\',\''.$del_Id.'\');" title="Deactive">Deactive</a>';
                // }
                // $recordListing[$i][3]= $actionContent;
                $recordListing[$i][3]= '<a href="'.base_url('admin/notification_mail_details/'.encode($recordData->id)).'" title="Edit" class="btn btn-success btn-sm_eye_box btn-sm"><i class="material-icons material-icons_eye">remove_red_eye</i></a> ';

                $i++;
                $srNumber++;
            }
            $content .= ']';
            $final_data = json_encode($recordListing);
        } else {
            $final_data = '[]';
        }              
        echo '{"draw":'.$draw.',"recordsTotal":'.$totalRecord.',"recordsFiltered":'.$totalRecord.',"data":'.$final_data.'}';      
    }
    public function notification_mail_details($id=''){
            $data['title']='Update Email Templates';
            $id =decode($id);
            $wh=array('id'=>$id); 
            $data['notifMailData']= getdatafromtable('manage_notification_mail',$wh); 
            $this->admintemplates('settings/all_notification_mails_details',$data); 
  }
  public function notificationMailDetailsAction(){
        $is_submit = $this->input->post('is_submit');
        $mailid = $this->input->post('mailid');
        if (isset($is_submit) && $is_submit == 1) {
            $this->form_validation->set_rules('subject','subject ','trim|required');
            $this->form_validation->set_rules('description',' description','required');
            if ($this->form_validation->run() == FALSE) {     
                $this->messages->setMessageFront(validation_errors(),'error'); 
                  redirect(base_url('admin/notification_mail_details/'.$mailid));
            }else{
                $upWhere = array('id' =>decode($mailid));
                $time=time();
                $desc= trim(preg_replace('/\s\s+/', ' ', $this->input->post('description')));
                $discription= str_replace(array('&nbsp;','<p>&nbsp;</p>','<p> </p>'),array('','',''), $desc);
                $dec= strip_tags($discription);
                $updatedata=array();
               if(!empty(trim($dec))){
                $updatedata['subject']          = $this->input->post('subject');
                $updatedata['description']      = $discription; 
                $updatedata['status']           = 'Active';
                $this->dynamic_model->updateRowWhere('manage_notification_mail',$upWhere,$updatedata);
                $this->messages->setMessageFront("Data updated successfully",'success'); 
                redirect(base_url('admin/notification_mail_details/'.$mailid));
                }else{
                      $this->messages->setMessageFront("Description field is required",'error'); 
                      redirect(base_url('admin/notification_mail_details/'.$mailid));
                }   
            }
        }else{
            $this->messages->setMessageFront("Wrong Method. Please Fill this Form",'error'); 
            redirect(base_url('admin/notification_mail_details/'.$mailid));
        }

  }
  public function static_pages_list(){
        // $permission_data=get_permission_detail(self::$admin_id);
        // $find_permission= unserialize($permission_data[0]['Permission']);    
        // if($find_permission['user_manage']==1){
          $data['title']='All Static pages';
          $data['static_data']= getdatafromtable('static_page'); 
         $this->admintemplates('settings/static_pages',$data);
        // }else{
        //     redirect('admin');
        // }  
  }
  public function editpage($pageid=''){
        // $logged= $this->session->userdata('logged_in');
        // $permit_data = get_permission_detail($logged['session_userid']);
        // $role_permission=unserialize($permit_data[0]['permission']);
        // if($logged['session_userrole']==1 || $role_permission['seetings']==1){
            $data['title']='Edit Static page';
            $wherepage = array('id' => decode($pageid));
            $data['page_data'] = getdatafromtable('static_page',$wherepage);
            $this->admintemplates('settings/edit_static_pages',$data);
        //  }else{
        //     redirect('admin');
        // }  

    }
    public function alpha_dash_space($str)
    {
       return ( ! preg_match("/^([-a-z0-9_-\s])+$/i", $str)) ? FALSE : TRUE;
    } 
    public function editPageAction(){
        $is_submit = $this->input->post('is_submit');
        $pageid = $this->input->post('pageid');
        if (isset($is_submit) && $is_submit == 1) {
            $this->form_validation->set_rules('title','page title','trim|required');
            $this->form_validation->set_rules('description','page description','required');
            if ($this->form_validation->run() == FALSE) {     
                $this->messages->setMessageFront(validation_errors(),'error'); 
                  redirect(base_url('admin/editpage/'.$pageid));
            }else{
                $upWhere = array('id' =>decode($pageid));
                $time=time();
                $desc= trim(preg_replace('/\s\s+/', ' ', $this->input->post('description')));
                $discription= str_replace(array('&nbsp;','<p>&nbsp;</p>','<p> </p>'),array('','',''), $desc);
                $dec= strip_tags($discription);
                $updatedata=array();
               if(!empty(trim($dec))){
                $updatedata['title']            = $this->input->post('title');
                $updatedata['discription']      = $discription; 
                $updatedata['status']           = 'Active';
                $this->dynamic_model->updateRowWhere('static_page',$upWhere,$updatedata);
                $this->messages->setMessageFront("Updated successfully",'success'); 
                redirect(base_url('admin/editpage/'.$pageid));
                }else{
                      $this->messages->setMessageFront("Description field is required",'error'); 
                      redirect(base_url('admin/editpage/'.$pageid));
                }   
            }
        }else{
            $this->messages->setMessageFront("Wrong Method. Please Fill this Form",'error'); 
            redirect(base_url('admin/editpage/'.$pageid));
        }

  }
  public function logout(){
    $this->session->unset_userdata('logged_in');
    //$this->session->sess_destroy();         
    redirect(base_url('admin'));
  }
  public function page_error() {;
      $this->load->view('admin/page-error');

  }

  public function all_agents(){
    $data['title']='All Agents';
    $this->admintemplates('agents/allagents',$data);
  }

  public function agent_details($user_id=''){
       $permission_data=get_permission_detail(self::$admin_id);
       $find_permission= unserialize($permission_data[0]['Permission']); 
        if($find_permission['user_manage']==1)
        {
            $data['title']='Agent Details';
            $userid = base64_decode($user_id);
            $wh=array('Id'=>$userid); 
            $data['userdata']= getdatafromtable('users',$wh); 
            $user_role_wh=array('User_Id'=>$userid); 
            $data['user_role_data']= getdatafromtable('user_in_roles',$user_role_wh); 
            $wh1=array('User_Id'=>$userid);
            $data['document_data']= getdatafromtable('users_documents',$wh1); 
            $this->admintemplates('agents/agent_details',$data);
        }else{
            redirect('admin');
        } 
    }


    // public function agent_delete($user_id = '')
    // {
    //     $permission_data = get_permission_detail(self::$admin_id);
    //     $find_permission = unserialize($permission_data[0]['Permission']); 

    //     if (!isset($find_permission['user_manage']) || $find_permission['user_manage'] != 1) {
    //         redirect('admin');
    //         return;
    //     }

    //     if (empty($user_id)) {
    //         redirect('admin/agents');
    //         return;
    //     }

    //     $userid = base64_decode($user_id);

    //     if (!$userid) {
    //         redirect('admin/all_agents');
    //         return;
    //     }

    //     // Start transaction for safety
    //     $this->db->trans_start();

    //     // Delete from user_in_roles
    //     $this->db->where('User_Id', $userid);
    //     $this->db->delete('user_in_roles');

    //     // Delete from users table
    //     $this->db->where('Id', $userid);
    //     $this->db->delete('users');

    //     $this->db->trans_complete();

    //     // Optional: flash message
    //     $this->session->set_flashdata('success', 'Agent deleted successfully.');

    //     redirect('admin/all_agents');
    // }



  public function agent_search_transaction()
  {
    $trx = $this->input->post('transaction_id');
    if(isset($trx)){

      $this->db->select('
          receiver.account_holder_name AS receiver_name,
          receiver.phone_number AS receiver_mobile,
          receiver.email AS receiver_email,
          receiver.account_number AS receiver_account_number,
          receiver.country AS receiver_country,
          receiver.city AS receiver_city,
          receiver.full_address AS receiver_address,

          sender.FirstName AS sender_first_name,
          sender.LastName AS sender_last_name,
          sender.Mobile_No AS sender_mobile,

          hedera_transactions.*
      ');

      $this->db->from('hedera_transactions');
      $this->db->join('recipients as receiver', 'receiver.id = hedera_transactions.receiver_id', 'left');
      $this->db->join('users as sender', 'sender.Id = hedera_transactions.sender_id', 'left');
      $this->db->where('hedera_transactions.transaction_id', $trx);
      // $this->db->where('hedera_transactions.status', 'SUCCESS');

      $data['transaction'] = $this->db->get()->row();
      // var_dump($data);
      // die;
    }

    $data['title'] = "Search Transaction";
    $this->admintemplates('transactions/agenttransaction',$data);
  }



  
  public function transaction_status_change()
  {
    $trx = $this->input->post('transaction_id');
    $status = $this->input->post('status');
    if(isset($trx)){

      $updatedata=array(
        'status' => $status
      );

      $where       = array('transaction_id'=>$trx);
      $update = $this->dynamic_model->updateRowWhere('hedera_transactions',$where,$updatedata);

      if ($update) {

        if($status == "PAID TO AGENT"){
          redirect(base_url('admin/all_transactions'));
        }
        echo json_encode([
            'status' => true,
            'message' => 'Transaction marked as Paid successfully'
        ]);
      } else {
          echo json_encode([
              'status' => false,
              'message' => 'Failed to update transaction'
          ]);
      }

    }
   
  }

  public function addAgents(){
    $data['title']='Add New Agent ';
    $this->admintemplates('agents/add_agent',$data);
  }

 public function addAgent()
  {
      if ($this->input->method() !== 'post') {
          echo json_encode([
              'status' => 0,
              'message' => 'Invalid request method'
          ]);
          exit;
      }

      // var_dump($this->input->post('password'));
      // die;

      $this->form_validation->set_rules('first_name', 'First Name', 'required');
      $this->form_validation->set_rules('last_name', 'Last Name', 'required');
      
      $this->form_validation->set_rules('address', 'Address', 'required');

      $this->form_validation->set_rules(
          'email',
          'Email',
          'required|valid_email|is_unique[users.Email]',
          [
              'required'    => $this->lang->line('email_required'),
              'valid_email' => $this->lang->line('email_valid'),
              'is_unique'   => $this->lang->line('email_unique')
          ]
      );

      $this->form_validation->set_rules(
          'mobile',
          'Mobile',
          'required|min_length[10]|max_length[12]|numeric|is_unique[users.Mobile_No]',
          [
              'required'    => $this->lang->line('mobile_required'),
              'min_length'  => $this->lang->line('mobile_min_length'),
              'max_length'  => $this->lang->line('mobile_max_length'),
              'numeric'     => $this->lang->line('mobile_numeric'),
              'is_unique'   => $this->lang->line('mobile_unique')
          ]
      );

      // Password validation
      $password = $this->input->post('password');
      $pass_msg = $this->valid_password($password);
      if ($pass_msg !== TRUE) {
          $this->form_validation->set_rules(
              'password',
              'Password',
              'required|callback_valid_password'
          );
          $this->form_validation->set_message('valid_password', $pass_msg);
      }

      if ($this->form_validation->run() === FALSE) {
        $errors = $this->form_validation->error_array();

        // Get first error message only
        $first_error = reset($errors);

        echo json_encode([
            'status'  => 0,
            'message' => $first_error
        ]);
        exit;
      }

      $firstname   = $this->input->post('first_name');
      $lastname    = $this->input->post('last_name');
      $full_name   = $firstname . ' ' . $lastname;
      $email       = $this->input->post('email');
      $mobile      = $this->input->post('mobile');
      $password    = $this->input->post('password');
      $address    = $this->input->post('address');
      $referral_code = $this->input->post('referral_code');

      $hashed_password = encrypt_password($password);
      $roles = 5;

      $doc_name = '';

      
      // // var_dump("fsdfsd");
      // // die;

      // if (!empty($_FILES['varification_image']['name'])) {
      //     $doc_name = $this->dynamic_model->fileupload(
      //         'varification_image',
      //         'uploads/identification'
      //     );

      //     if (!$doc_name) {
      //         echo json_encode([
      //             'status' => 0,
      //             'message' => 'Invalid image format'
      //         ]);
      //         exit;
      //     }
      // } else {
      //     echo json_encode([
      //         'status' => 0,
      //         'message' => 'Image is required'
      //     ]);
      //     exit;
      // }


      $get_referrals_data = [];
      if (!empty($referral_code)) {
          $get_referrals_data = $this->dynamic_model
              ->getdatafromtable('users', ['Referral_Code' => $referral_code]);

          if (empty($get_referrals_data)) {
              echo json_encode([
                  'status' => 0,
                  'message' => 'Invalid referral code'
              ]);
              exit;
          }
      }

      $otpnumber = generate_Pin();
      $my_referral_code = generateRandomString(10);
      $generate_etip_id = strtolower(substr($firstname, 0, 2)) .
                          rand(10, 99) .
                          substr($mobile, -3) . '@' .
                          strtolower(SITE_TITLE);

      $lat  = $this->input->get_request_header('lat') ?? 0;
      $long = $this->input->get_request_header('long') ?? 0;

      $userdata = [
          'FullName'              => $full_name,
          'FirstName'             => $firstname,
          'lastName'              => $lastname,
          'Password'              => $hashed_password,
          'Email'                 => $email,
          'Mobile_No'             => $mobile,
          'Profile_Pic'           => 'default.jpg',
          'Address'               => $address,
          'Mobile_OTP'            => $otpnumber,
          'Email_OTP'             => $otpnumber,
          'Notification_Status'   => 1,
          'Fingerprint_Status'    => 1,
          'Referral_Code'         => $my_referral_code,
          'Lat'                   => $lat,
          'Lang'                  => $long,
          'etippers_id'           => $generate_etip_id,
          'Is_Mobile_Verified'    => 1,
          'Is_Email_Verified'     => 1,
          'Is_Profile_Complete'   => 1,
          'Is_Active'             => 1
      ];

      $userid = $this->dynamic_model->insertdata('users', $userdata);

      if (!$userid) {
          echo json_encode([
              'status' => 0,
              'message' => 'User creation failed'
          ]);
          exit;
      }

      $this->dynamic_model->insertdata('users_documents', [
          'User_Id'              => $userid,
          'Document_Type_Id'     => 2,
          'Document_Image_Name'  => $doc_name,
          'Created_By'           => $userid
      ]);

      // $qr_number = generateQrcode($mobile);

      $this->dynamic_model->insertdata('user_in_roles', [
          'User_Id'        => $userid,
          'Role_Id'        => $roles,
          'Device_Id'      => 1234,
          'Device_Type'    => 'mobile',
          'QR_Code'        => 0,
          'QR_Code_Img_Path' => '.png'
      ]);

      echo json_encode([
          'status'  => 1,
          'message' => 'Agent added successfully',
          'user_id' => $userid
      ]);
      exit;
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


}
