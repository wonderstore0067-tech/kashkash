<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends My_Controller {
   
   private static $admin_id = null;
   public function __construct(){		
        parent::__construct();		
        $this->load->model('dynamic_model');
        $this->load->model('admin_model');
        $this->load->model('users_model');
        $this->lang->load("message","english");
        if($this->session->userdata('logged_in')){
	        $currentuser = $this->session->userdata('logged_in');
			self::$admin_id = $currentuser['session_userid'];
		}else{
            self::$admin_id=null;
            redirect('admin/','refresh');
        }
    }    
    public function searchSender(){ 
        $unique_id=uniqid();
        $user_status   =  $this->input->post('user_status');       
        $store_data= array('user_status'=>$user_status);
        $this->session->set_userdata('senderdata', $store_data);
        redirect('/admin/all_senders/'.$unique_id);   
    }
    /**
    * all senders
    *
    * ALL Senders Data.
    *
    * @param int usertype means customer or merchant
    * @param int userverified means user verifed, user banned or mobile unverified users
    */
   public function all_senders($uniqid=''){
        if(empty($uniqid)){
           $this->session->unset_userdata('senderdata');
        }
        // $permission_data=get_permission_detail(self::$admin_id);
        // $find_permission= unserialize($permission_data[0]['Permission']);    
        // if($find_permission['user_manage']==1){
         $data['title']='All Senders';
         $this->admintemplates('sender/all_sender',$data);
        // }else{
        //     redirect('admin');
        // }  
    }
    /**
    * all allsendersajaxlist
    *
    * Fetch ALL Senders Data Uning ajax method.
    *
    * @param int usertype means customer or merchant
    * @param int userverified means user verifed, user banned or mobile unverified users
    */
	public function allsendersajaxlist(){
        $start         =  $this->input->get('start'); 
        $length        =  $this->input->get('length'); 
        $draw          =  $this->input->get('draw'); 
        $order         =  $this->input->get('order');
        if(!empty($order)){ 
            if($order[0]['column']==1){
                $column_name='FullName';
            }else if($order[0]['column']==2){
                $column_name='Email';
            }else if($order[0]['column']==3){
                $column_name='Mobile_No';
            }else if($order[0]['column']==4){
                $column_name='Current_Wallet_Balance';
            }else if($order[0]['column']==5){
                $column_name='Total_Referral_Points';
            }else if($order[0]['column']==6){
                $column_name='etippers_id';
            }else if($order[0]['column']==7){
                $column_name='Creation_Date_Time';
            }else{
                $column_name='Creation_Date_Time';
            }

        }   
        $totalRecord      = $this->users_model->allsendersajaxlist(true,'','','', '');
        $getRecordListing = $this->users_model->allsendersajaxlist(false,$start,$length,$column_name, $order[0]['dir']);
        //$totalRecord= count($getRecordListing);
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
                $recordListing[$i][1]= ucwords(strtolower($recordData->FullName));
                $recordListing[$i][2]= $recordData->Email;
                $recordListing[$i][3]= $recordData->Mobile_No;
                $recordListing[$i][4]= $this->config->item("currency").''.$recordData->Current_Wallet_Balance;   
                $recordListing[$i][5]= $recordData->Total_Referral_Points;   
                $recordListing[$i][6]= (!empty($recordData->etippers_id)) ? $recordData->etippers_id : '-' ;   
                $recordListing[$i][7]= dateFormat($recordData->Creation_Date_Time);
                $recordListing[$i][8]= ($recordData->Is_LoggedIn == 1) ? '<span class="badge badge-success">Online</span>' : '<span class="badge badge-danger">Offline</span>';

                $wh=array('User_Id'=>$recordData->User_Id); 
                $user_document_status= getdatafromtable('users_documents',$wh);
                if(!empty($user_document_status)){
                    if(@$user_document_status[0]['Is_Verified']==1){
                       $doc_status ='<span class="badge badge-success">Verified</span>';
                    }else{
                        $doc_status ='<span class="badge badge-danger">Unverified</span>';
                    }
                }else{
                 $doc_status = '<span class="badge badge-danger">Unverified</span>';
                }

                $recordListing[$i][9]= $doc_status;
                $del_Id='Id';
                $table='users';
				$field='Is_Active';
			 	$urls = base_url('admin/home/updateStatus');
                if($recordData->Is_Mobile_Verified ==1 && $recordData->Is_Email_Verified ==1)
                {
                    $doc_verified_status='1';
                }elseif($recordData->Is_Mobile_Verified ==0){
                    $doc_verified_status='2';
                }elseif($recordData->Is_Email_Verified ==0){
                    $doc_verified_status='3';
                }else{
                    $doc_verified_status='0';
                }
                if($recordData->Is_Active==1){
                    $statuschng ='statussucc'.$recordData->User_Id;
					$actionContent .='<a class="btn '.$statuschng.' activate_br waves-effect" href="javascript:void(0);" onclick="doc_status('.$recordData->User_Id.', \''.$recordData->Is_Active.'\', \''.$urls.'\' , \''.$table.'\', \''.$field.'\',\''.$del_Id.'\',\''.$doc_verified_status.'\');" title="Enabled">Enabled</a>';
				}
                else{ 
                    $statuschng ='statusdng'.$recordData->User_Id;
					$actionContent .='<a class="btn '.$statuschng.' deactivate_br waves-effect" href="javascript:void(0);" onclick="doc_status('.$recordData->User_Id.', \''.$recordData->Is_Active.'\', \''.$urls.'\', \''.$table.'\', \''.$field.'\',\''.$del_Id.'\',\''.$doc_verified_status.'\');" title="Disabled">Disabled</a>';
				}
                   
                $recordListing[$i][10]= $actionContent;

                //blank for edit button
                $actionContent = '';
                 $actionContent .='<a href="'.base_url('admin/sender_details/'.base64_encode($recordData->User_Id)).'" title="Edit" class="btn btn-success btn-sm_eye_box btn-sm"><i class="material-icons material-icons_eye">remove_red_eye</i></a> ';

                $recordListing[$i][11]= $actionContent;
                
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
    public function searchReceiver(){ 
        $unique_id=uniqid();
        $user_status   =  $this->input->post('user_status');       
        $store_data= array('user_status'=>$user_status);
        $this->session->set_userdata('receiverdata', $store_data);
        redirect('/admin/all_receivers/'.$unique_id);   
    }
    /**
    * all receivers
    *
    * ALL Receivers
    *
    * @param int usertype means customer or merchant
    * @param int userverified means user verifed, user banned or mobile unverified users
    */
   public function all_receivers($uniqid=''){
       if(empty($uniqid)){
           $this->session->unset_userdata('receiverdata');
        }
       // $permission_data=get_permission_detail(self::$admin_id);
       // $find_permission= unserialize($permission_data[0]['Permission']);
       //      if($find_permission['user_manage']==1){
             $data['title']='All Receivers';
             $this->admintemplates('receiver/all_receiver',$data);
            // }else{
            //     redirect('admin');
            // } 
    }
    /**
    * all receiversajaxlist
    *
    * Fetch ALL Receivers Data Uning ajax method.
    *
    * @param int usertype means customer or merchant
    * @param int userverified means user verifed, user banned or mobile unverified users
    */
    public function allreceiversajaxlist($userverified=''){  
        $start         =  $this->input->get('start'); 
        $length        =  $this->input->get('length'); 
        $draw          =  $this->input->get('draw'); 
        $order         =  $this->input->get('order');
        if(!empty($order)){ 
              if($order[0]['column']==1){
                $column_name='FullName';
            }else if($order[0]['column']==2){
                $column_name='Email';
            }else if($order[0]['column']==3){
                $column_name='Mobile_No';
            }else if($order[0]['column']==4){
                $column_name='Current_Wallet_Balance';
            }else if($order[0]['column']==5){
                $column_name='Total_Referral_Points';
            }else if($order[0]['column']==6){
                $column_name='etippers_id';
            }else if($order[0]['column']==7){
                $column_name='Creation_Date_Time';
            }else{
                $column_name='Creation_Date_Time';
            }
        }     
       $totalRecord      = $this->users_model->allreceiversajaxlist(true,'','','', '');
       $getRecordListing = $this->users_model->allreceiversajaxlist(false,$start,$length,$column_name, $order[0]['dir']);
        //$totalRecord= count($getRecordListing);
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
                $recordListing[$i][1]= ucwords(strtolower($recordData->FullName));
                $recordListing[$i][2]= $recordData->Email;
                $recordListing[$i][3]= $recordData->Mobile_No;
                $recordListing[$i][4]= $this->config->item("currency").''.$recordData->Current_Wallet_Balance;   
                $recordListing[$i][5]= $recordData->Total_Referral_Points; 
                $recordListing[$i][6]= (!empty($recordData->etippers_id)) ? $recordData->etippers_id : '-' ;  
                $recordListing[$i][7]= dateFormat($recordData->Creation_Date_Time);
                $recordListing[$i][8]= ($recordData->Is_LoggedIn == 1) ? '<span class="badge badge-success">Online</span>' : '<span class="badge badge-danger">Offline</span>';

                $wh=array('User_Id'=>$recordData->User_Id); 
                $user_document_status= getdatafromtable('users_documents',$wh);
                if(!empty($user_document_status)){
                    if(@$user_document_status[0]['Is_Verified']==1){
                       $doc_status ='<span class="badge badge-success">Verified</span>';
                    }else{
                        $doc_status ='<span class="badge badge-danger">Unverified</span>';
                    }
                }else{
                 $doc_status = '<span class="badge badge-danger">Unverified</span>';
                }

                $recordListing[$i][9]= $doc_status;
                $del_Id='Id';
                $table='users';
                $field='Is_Active';
                $urls = base_url('admin/home/updateStatus');
                if($recordData->Is_Mobile_Verified ==1 && $recordData->Is_Email_Verified ==1)
                {
                    $doc_verified_status='1';
                }elseif($recordData->Is_Mobile_Verified ==0){
                    $doc_verified_status='2';
                }elseif($recordData->Is_Email_Verified ==0){
                    $doc_verified_status='3';
                }else{
                    $doc_verified_status='0';
                }
                if($recordData->Is_Active==1){
                    $statuschng ='statussucc'.$recordData->User_Id;
                    $actionContent .='<a class="btn '.$statuschng.' activate_br waves-effect" href="javascript:void(0);" onclick="doc_status('.$recordData->User_Id.', \''.$recordData->Is_Active.'\', \''.$urls.'\' , \''.$table.'\', \''.$field.'\',\''.$del_Id.'\',\''.$doc_verified_status.'\');" title="Enabled">Enabled</a>';
                }
                else{ 
                    $statuschng ='statusdng'.$recordData->User_Id;
                    $actionContent .='<a class="btn '.$statuschng.' deactivate_br waves-effect" href="javascript:void(0);" onclick="doc_status('.$recordData->User_Id.', \''.$recordData->Is_Active.'\', \''.$urls.'\', \''.$table.'\', \''.$field.'\',\''.$del_Id.'\',\''.$doc_verified_status.'\');" title="Disabled">Disabled</a>';
                }
                   
                $recordListing[$i][10]= $actionContent;
                //blank for edit button
                $actionContent = '';
                 $actionContent .='<a href="'.base_url('admin/receiver_details/'.base64_encode($recordData->User_Id)).'" title="Edit" class="btn btn-success btn-sm_eye_box btn-sm"><i class="material-icons material-icons_eye">remove_red_eye</i></a> ';

                $recordListing[$i][11]= $actionContent;
                
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
    /**
    * updateStatus
    *
    * this dynamic function used for update status
    *
    * @return true or false
    */
	public function updateStatus(){
		$returnData=false;
		$userId=$this->input->post('ids');
		$del_Id = $this->input->post('del_Id') ? $this->input->post('del_Id') : "id";
		$userStatus=$this->input->post('status');
		$table=$this->input->post('table');
		$field=$this->input->post('field');

		if((!empty($userId)) && (!empty($table))){

			if($userStatus==1){
				$status=0;
			}else{
				$status=1;
			}
	
			$upWhere = array($del_Id =>$userId);
			$updateData = array($field=>$status);
			$this->dynamic_model->updateRowWhere($table, $upWhere , $updateData);
            //$resultdata = getdatafromtable($table,$upWhere); 
			$returnData = array('isSuccess'=>true);
		}else{
			$returnData = array('isSuccess'=>false);
		}
		echo json_encode($returnData); 
	}

    public function deleteagent(){
		$returnData=false;
		$userId=$this->input->post('ids');
		// $del_Id = $this->input->post('del_Id') ? $this->input->post('del_Id') : "Id";
		$table=$this->input->post('table');

		if((!empty($userId)) && (!empty($table))){

            $this->db->where('Id', $userId);
            $this->db->delete('users');

			$returnData = array('isSuccess'=>true);
		}else{
			$returnData = array('isSuccess'=>false);
		}
		echo json_encode($returnData); 
	}

    /**
    * Sender Details
    *
    * view of user_details .
    *
    * @param int 
    * @param int 
    */
	public function sender_details($user_id=''){
       $permission_data=get_permission_detail(self::$admin_id);
       $find_permission= unserialize($permission_data[0]['Permission']); 
        if($find_permission['user_manage']==1)
        {
            $data['title']='Sender Details';
            $userid = base64_decode($user_id);
            $wh=array('Id'=>$userid); 
            $data['userdata']= getdatafromtable('users',$wh); 
            $user_role_wh=array('User_Id'=>$userid); 
            $data['user_role_data']= getdatafromtable('user_in_roles',$user_role_wh); 
            $wh1=array('User_Id'=>$userid);
            $data['document_data']= getdatafromtable('users_documents',$wh1); 
            $this->admintemplates('sender/sender_details',$data);
        }else{
            redirect('admin');
        } 
    }
    /**
    * Sender profile Update
    *
    * profile update sender
    *
    */
    public function sender_profile_update(){
        $user_type = $this->input->post('user_type'); 
        $age  = $this->input->post('age');
        $this->form_validation->set_error_delimiters("<p class='inputerror text text-danger error'>", "</p>");
        $this->form_validation->set_rules('fname', 'First Name', 'required');
        $this->form_validation->set_rules('lname', 'Last Name', 'required');
        if(!empty($age)){
          $this->form_validation->set_rules('age', 'age', 'required|numeric');
         }
            //$this->form_validation->set_rules('mobile', 'mobile', 'required');
           // $this->form_validation->set_rules('gender', 'gender','required'); 
            if ($this->form_validation->run() == FALSE){  
                        $error= array(
                              'fname'           =>form_error('fname'),
                              'lname'           =>form_error('lname'),
                              'age'            =>form_error('age')    
                           );
                    $return = array('status'=>false,'message'=>'All fields are mandatory..','data'=>$error);
            }else{
                    $updatedata =  array(); 
                    $updateprofile =array();
                    $userid                                 = $this->input->post('userid');  
                    $dob                                    = date('Y-m-d',strtotime($this->input->post('dob')));
                    $email_verified                         = ($this->input->post('email_verified')) ? 1: 0;
                    $mobile_verified                        = ($this->input->post('mobile_verified')) ? 1: 0;
                    $address                                = $this->input->post('address');
                    $age                                    = $this->input->post('age');
                    $gender                                 = $this->input->post('gender');
                    $is_allowed_transaction                 = ($this->input->post('is_allowed_transaction')) ? 1: 0;
                    
                    $updatedata['FirstName']                = $this->input->post('fname');
                    $updatedata['LastName']                 = $this->input->post('lname');
                    $updatedata['FullName']                 = $this->input->post('fname').' '.$this->input->post('lname');
                    if(!empty($_FILES['userImg']['name'])){
                    $user_img  = $this->dynamic_model->fileupload('userImg','/uploads/user');
                    }else{
                    $user_img = '';
                    }


                    if($email_verified ==1){
                        $updatedata['Is_Email_Verified']    = 1;
                    }
                   if($mobile_verified ==1){
                     $updatedata['Is_Mobile_Verified']      = 1;
                    }
                    $updatedata['Is_Allowed_Transaction']  = $is_allowed_transaction;
                    if(!empty($address)){ 
                      $updatedata['Address']                = $address;
                    }
                    if(!empty($age)){ 
                          $updatedata['Age']                = $age;
                     }
                     if(!empty($gender)){ 
                          $updatedata['Gender']             = $gender;
                     } 
                    if(!empty($user_img)){ 
                      $updatedata['Profile_Pic']=$user_img;
                    }

                    $where=array('Id'=>$userid );
                    $profileupdate= $this->dynamic_model->updateRowWhere('users',$where,$updatedata);
                    //echo $this->db->last_query();die;
                    $wh=array('Id'=>$userid); 
                    $userdetails= getdatafromtable('users',$where); 
                    
                     if(!empty($userdetails)){
                         if(@$userdetails[0]['Is_Email_Verified'] ==1 && $userdetails[0]['Is_Mobile_Verified'] ==1){
                        $updateprofile['Is_Profile_Complete']      = 1;
                         $profileupdate= $this->dynamic_model->updateRowWhere('users',$where,$updateprofile);
                        }
                     }
                    $return=array('status'=>true,'message'=>'Updated Successfully','data'=>'');  
                }
              echo json_encode($return);        
    }
   /**
    * Sender documents_verified
    *
    * View documents verified
    *
    * @param int user_id 
    * @param int usertype means customer or merchant
    */
    public function sender_documents_verified($user_id=''){  
           $data['title']='User Documents';     
            $userid = base64_decode($user_id);
            $wh=array('Id'=>$userid); 
            $data['userdata']= getdatafromtable('users',$wh); 
            $wh1=array('User_Id'=>$userid);
            $data['document_data']= getdatafromtable('users_documents',$wh1); 
            $this->admintemplates('sender/sender_document',$data);       
    }
   public function sender_documents_verified_action($user_id=''){
            $updatedata = array();  
            $userid                     = $this->input->post('userid');    
            $documents_status  = ($this->input->post('documents_status')) ? 1 : 0;
            $updatedata['Is_Verified'] = $documents_status;
            
            $where=array('User_Id'=>$userid );
            $document_data= getdatafromtable('users_documents',$where);  
            if(!empty($document_data))
            {
               $Dl_img=(!empty($document_data[0]['Document_Image_Name'])) ? $document_data[0]['Document_Image_Name'] :'' ;
               $url=IMAGE_PATH.'identification/';
               $unique_img =  doc_image_check($Dl_img,$url,$flag=2);                           
               if(!empty($unique_img ))
               {
	               $profileupdate= $this->dynamic_model->updateRowWhere('users_documents',$where,$updatedata);
	               if($documents_status ==1){
                        /*$where=array('Id'=>$userid );
                        $user_data= getdatafromtable('users_documents',$where);  
                        $where1 = array('slug' =>'document_status'); 
                        $template_data = $this->dynamic_model->getdatafromtable('manage_notification_mail', $where1);
                            
                        $emaildata['subject']     = $template_data[0]['subject']; 
                        $desc_data = str_replace('{USERNAME}',strtoupper($user_data[0]['FullName']),$template_data[0]['description']);
                        $desc_data = str_replace('{STATUS}','Verified',$desc_data);
                       
                        $emaildata['description'] = $desc_data;  
                        
                        $emaildata['body']        = '';

                        $msg = $this->load->view('emailtemplate',$emaildata,TRUE);
                        //--------------load email template----------------
                        $mail = sendEmailCI($user_data[0]['Email'],'' ,$emaildata['subject'],$msg);
                        if($mail){
                            echo "string";
                        }
                        else  {
                            echo "s no tring";
                        }*/


	                   $return=array('status'=>true,'message'=>'Document Verified Successfully'); 
	               }else{
	                 $return=array('status'=>false,'message'=>'Document Unverified Successfully'); 
	               }  
                }else{
                 $return=array('status'=>false,'message'=>'identification image not found');  
                }
            }else{
             $return=array('status'=>false,'message'=>'Not upload identification image!');  
            }
            echo json_encode($return);        
    }

    /**
    * Reciver Details
    *
    * view of user_details .
    *
    * @param int 
    * @param int 
    */
    public function receiver_details($user_id=''){
        $data['title']='Receiver Details';
        $userid = base64_decode($user_id);
        $wh=array('Id'=>$userid); 
        $data['userdata']= getdatafromtable('users',$wh); 
        
        $user_role_wh=array('User_Id'=>$userid); 
        $data['user_role_data']= getdatafromtable('user_in_roles',$user_role_wh);  
        $wh1=array('User_Id'=>$userid);
        $data['document_data']= getdatafromtable('users_documents',$wh1); 
        $data['currency']=$this->config->item("currency");
        $this->admintemplates('receiver/receiver_details',$data);    
    }
    /**
    * Receiver profile Update
    *
    * profile update sender
    *
    */
    public function receiver_profile_update(){
        $user_type = $this->input->post('user_type'); 
        $age  = $this->input->post('age');
        $this->form_validation->set_error_delimiters("<p class='inputerror text text-danger error'>", "</p>");
        $this->form_validation->set_rules('fname', 'First Name', 'required');
        $this->form_validation->set_rules('lname', 'Last Name', 'required');
        if(!empty($age)){
          $this->form_validation->set_rules('age', 'age', 'required|numeric');
         }
        $this->form_validation->set_rules('gender', 'gender','required'); 
        if ($this->form_validation->run() == FALSE){  
                    $error= array(
                          'fname'           =>form_error('fname'),
                          'lname'           =>form_error('lname'),
                          'age'          =>form_error('age')    
                       );
                $return = array('status'=>false,'message'=>'All fields are mandatory..','data'=>$error);
        }else{
                $updatedata =  array(); 
                $updateprofile =array();
                $userid                                 = $this->input->post('userid');  
                $dob                                    = date('Y-m-d',strtotime($this->input->post('dob')));
                $email_verified                         = ($this->input->post('email_verified')) ? 1: 0;
                $mobile_verified                        = ($this->input->post('mobile_verified')) ? 1: 0;
                $address                                = $this->input->post('address');
                $age                                    = $this->input->post('age');
                $gender                                 = $this->input->post('gender');
                $is_allowed_transaction                 = ($this->input->post('is_allowed_transaction')) ? 1: 0;
                if(!empty($_FILES['userImg']['name'])){
                $user_img  = $this->dynamic_model->fileupload('userImg','/uploads/user');
                }else{
                $user_img = '';
                }

                if($email_verified ==1){
                    $updatedata['Is_Email_Verified']    = 1;
                }
               if($mobile_verified ==1){
                 $updatedata['Is_Mobile_Verified']      = 1;
                }
                $updatedata['Is_Allowed_Transaction']   = $is_allowed_transaction;
                $updatedata['FirstName']                = $this->input->post('fname');
                $updatedata['LastName']                 = $this->input->post('lname');
                $updatedata['FullName']                 = $this->input->post('fname').' '.$this->input->post('lname');   
                if(!empty($address)){	
                      $updatedata['Address']            = $address;
                    }
                if(!empty($age)){ 
                      $updatedata['Age']                = $age;
                 }
                 if(!empty($gender)){ 
                      $updatedata['Gender']             = $gender;
                 }
                 if(!empty($user_img)){ 
                      $updatedata['Profile_Pic']=$user_img;
                }
                $where=array('Id'=>$userid );
                $profileupdate= $this->dynamic_model->updateRowWhere('users',$where,$updatedata);

                $wh=array('Id'=>$userid); 
                $userdetails= getdatafromtable('users',$where); 
                
                 if(!empty($userdetails)){
                     if(@$userdetails[0]['Is_Email_Verified'] ==1 && $userdetails[0]['Is_Mobile_Verified'] ==1){
                    $updateprofile['Is_Profile_Complete']      = 1;
                     $profileupdate= $this->dynamic_model->updateRowWhere('users',$where,$updateprofile);
                    }
                 }
                $return=array('status'=>true,'message'=>'Updated Successfully','data'=>'');  
            }
          echo json_encode($return);        
    }





    public function agent_profile_update(){
        $user_type = $this->input->post('user_type');
        $this->form_validation->set_error_delimiters("<p class='inputerror text text-danger error'>", "</p>");
        $this->form_validation->set_rules('fname', 'First Name', 'required');
        $this->form_validation->set_rules('lname', 'Last Name', 'required');
        
        // $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.Email]' , array('required' => $this->lang->line('email_required'),'valid_email' => $this->lang->line('email_valid'),'is_unique' => $this->lang->line('email_unique')
			// ));
			// $this->form_validation->set_rules('mobile', 'Mobile', 'required|min_length[10]|max_length[12]|numeric|is_unique[users.Mobile_No]', array(
			// 		'required' => $this->lang->line('mobile_required'),
			// 		'min_length' => $this->lang->line('mobile_min_length'),
			// 		'max_length' => $this->lang->line('mobile_max_length'),
			// 		'numeric' => $this->lang->line('mobile_numeric'),
			// 		'is_unique' => $this->lang->line('mobile_unique')
			// 	));

            $this->form_validation->set_rules('email', 'Email', 'required');
            $this->form_validation->set_rules('mobile', 'Mobile', 'required');
        //    $this->form_validation->set_rules('gender', 'gender','required'); 
            if ($this->form_validation->run() == FALSE){  
                        $error= array(
                              'fname'           =>form_error('fname'),
                              'lname'           =>form_error('lname'),
                            //   'age'            =>form_error('age')    
                           );
                    $return = array('status'=>false,'message'=>'All fields are mandatory..','data'=>$error);
            }else{
                    $updatedata =  array(); 
                    $updateprofile =array();
                    $userid                                 = $this->input->post('userid');
                    $mobile                                 = $this->input->post('mobile');  
                    $email                                  = $this->input->post('email');  
                    $address                                = $this->input->post('address');
                    $updatedata['FirstName']                = $this->input->post('fname');
                    $updatedata['LastName']                 = $this->input->post('lname');
                    $updatedata['FullName']                 = $this->input->post('fname').' '.$this->input->post('lname');
                
                    
                    if(!empty($email)){ 
                        // $wh=array('Email'=>$email); 
                        // $userdetails= getdatafromtable('users',$where);
                        // if($userdetails){
                        //     $return=array('status'=>false,'message'=>'Updated Successfully','data'=>'');
                        // }

                          $updatedata['Email']                = $email;
                     }

                    if(!empty($mobile)){ 
                          $updatedata['Mobile_No']                = $mobile;
                     }
                    if(!empty($address)){ 
                      $updatedata['Address']                = $address;
                    }
                    
                    $where=array('Id'=>$userid );
                    $profileupdate= $this->dynamic_model->updateRowWhere('users',$where,$updatedata);
                    // //echo $this->db->last_query();die;
                    // $wh=array('Id'=>$userid); 
                    // $userdetails= getdatafromtable('users',$where); 
                    
                    //  if(!empty($userdetails)){
                    //      if(@$userdetails[0]['Is_Email_Verified'] ==1 && $userdetails[0]['Is_Mobile_Verified'] ==1){
                    //     $updateprofile['Is_Profile_Complete']      = 1;
                    //      $profileupdate= $this->dynamic_model->updateRowWhere('users',$where,$updateprofile);
                    //     }
                    //  }
                    $return=array('status'=>true,'message'=>'Updated Successfully','data'=>'');  
                }
              echo json_encode($return);        
    }

    /**
    * Receiver documents_verified
    *
    * View documents verified
    *
    * @param int user_id 
    * @param int usertype means customer or merchant
    */
    public function receiver_documents_verified($user_id=''){  
       $permission_data=get_permission_detail(self::$admin_id);
       $find_permission= unserialize($permission_data[0]['Permission']);       
        if($find_permission['user_manage']==1)
        {
            $data['title']='User Documents';     
            $userid = base64_decode($user_id);
            $wh=array('Id'=>$userid); 
            $data['userdata']= getdatafromtable('users',$wh); 
            $wh1=array('User_Id'=>$userid);
            $data['document_data']= getdatafromtable('users_documents',$wh1); 
            $this->admintemplates('receiver/receiver_document',$data);
        }else{
            redirect('admin');
        }    
    }

   public function receiver_documents_verified_action($user_id=''){
            $updatedata = array();  
            $userid                     = $this->input->post('userid');    
            $documents_status  = ($this->input->post('documents_status')) ? 1 : 0;
            $updatedata['Is_Verified'] = $documents_status;
            
            $where=array('User_Id'=>$userid );
            $document_data= getdatafromtable('users_documents',$where);  
            if(!empty($document_data))
            {
               $Dl_img=(!empty($document_data[0]['Document_Image_Name'])) ? $document_data[0]['Document_Image_Name'] :'' ;
               $url=IMAGE_PATH.'identification/';
               $unique_img =  doc_image_check($Dl_img,$url,$flag=2);                           
               if(!empty($unique_img ))
               {
	               $profileupdate= $this->dynamic_model->updateRowWhere('users_documents',$where,$updatedata);
	               if($documents_status ==1){
	                   $return=array('status'=>true,'message'=>'Document Verified Successfully'); 
	               }else{
	                 $return=array('status'=>false,'message'=>'Document Unverified Successfully'); 
	               }  
                }else{
                 $return=array('status'=>false,'message'=>'identification image not found');  
                }
            }else{
             $return=array('status'=>false,'message'=>'Not upload identification image!');  
            }
            echo json_encode($return);        
    }
    public function searchTransaction(){ 
        $unique_id=uniqid();
        $trxDate   =  $this->input->post('trxDate');       
        $store_data= array('trxDate'=>$trxDate);
        $this->session->set_userdata('searchdata', $store_data);
        redirect('/admin/all_transactions/'.$unique_id);   
    }
    public function all_transactions($uniqid=''){ 
        if(empty($uniqid)){
           $this->session->unset_userdata('searchdata');
        }
        $data['title']='All Transactions';
        $this->admintemplates('transactions/alltransaction',$data);   
    }
    public function alltransactionsajaxlist(){       
        $start         =  $this->input->get('start'); 
        $length        =  $this->input->get('length'); 
        $draw          =  $this->input->get('draw'); 
        $order         =  $this->input->get('order');
        if(!empty($order)){ 
            if($order[0]['column']==1){
                $column_name='FirstName';
            }else if($order[0]['column']==2){
                $column_name='Mobile_No';
            }else if($order[0]['column']==3){
                $column_name='amount';
            }else if($order[0]['column']==4){
                $column_name='transaction_id';
            }else if($order[0]['column']==5){
                $column_name='created_at';
            }else if($order[0]['column']==6){
                $column_name='status';
            }
            else{
                $column_name='created_at';
            }
        }
        $totalRecord      = $this->users_model->transactionajaxlist(true);
        $getRecordListing = $this->users_model->transactionajaxlist(false,$start,$length,$column_name, $order[0]['dir']);
        $recordListing = array();
        $content='[';
        $i=0;       
        $srNumber=$start;       
        $currency= $this->config->item("currency");
    //    echo "<pre>";print_r($getRecordListing);die;
        if(!empty($getRecordListing)) {
            $actionContent = '';
            foreach($getRecordListing as $recordData) {
                $userListData  = array(); //set default array
                $actionContent = ''; // set default empty
                $content .='['; 
                $recordListing[$i][0]= $srNumber+1;
                $recordListing[$i][1]= name_format($recordData->sender_first_name.' '.$recordData->sender_last_name);
                $recordListing[$i][2]= name_format($recordData->receiver_name);
                $recordListing[$i][3]= $currency.''.$recordData->amount;   
                $recordListing[$i][4]= dateFormat($recordData->created_at);
                $recordListing[$i][5]= $recordData->transaction_id;
                $recordListing[$i][6]= $recordData->receiver_mobile;
                $recordListing[$i][7]= $recordData->receiver_email;
                $recordListing[$i][8]= $recordData->receiver_account_number;
                $recordListing[$i][9]= $recordData->receiver_city;
                $recordListing[$i][10]= $recordData->receiver_country;
                $recordListing[$i][11]= $recordData->receiver_address;
                // $recordListing[$i][6]= getPaymentTypeText($recordData->Tran_Type_Id);
                $recordListing[$i][12]= getPaymentStatusText($recordData->status);
                //$recordListing[$i][8]= $recordData->Refund_Reason;  


                // $actionContent = '';
                // $actionContent .='<a href="'.base_url('admin/sender_details/'.base64_encode($recordData->User_Id)).'" title="Edit" class="btn btn-success btn-sm_eye_box btn-sm"><i class="material-icons material-icons_eye">remove_red_eye</i></a> ';

                $actionContent = '';
                
                $actionContent .= '
                <form class="paidToRecipientForm d-inline" method="POST" action="' . base_url('admin/transaction_status_change') . '">
                    <input type="hidden" name="transaction_id" value="' . $recordData->transaction_id . '">
                    <input type="hidden" name="status" value="PAID TO AGENT">
                    <button type="submit"
                        class="btn btn-primary btn-round color-purple"
                        ' . ($recordData->status == "PAID TO RECEIVER" ? 'disabled' : '') . 
                        ($recordData->status == "PAID TO AGENT" ? 'disabled' : '') . '>
                        Paid to Agent
                    </button>
                </form>';

                // $actionContent .= '<a href="javascript:void(0);"
                //     class="btn btn-primary btn-round btn-lg color-purple paidToRecipientBtn ' . 
                //     ($recordData->status == "PAID TO RECEIVER" ? 'disabled' : '') . '"
                //     data-transaction-id="' . $recordData->transaction_id . '">
                //     Paid to Agent
                // </a>';

                $recordListing[$i][13]= $actionContent;

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

    public function withdraw_history(){
      $permission_data=get_permission_detail(self::$admin_id);
      $find_permission= unserialize($permission_data[0]['Permission']);  
        if($find_permission['withdraw']==1)
        {
            $data['title']='Withdrawal History';
            $this->admintemplates('withdraw_history',$data);
        }else{
            redirect('admin');
        }
    }
   public function withdrawhistoryajaxlist(){
        $start         =  $this->input->get('start'); 
        $length        =  $this->input->get('length'); 
        $draw          =  $this->input->get('draw'); 
        $order         =  $this->input->get('order');
        if(!empty($order)){ 
            if($order[0]['column']==1){
                $column_name='FirstName';
            }else if($order[0]['column']==2){
                $column_name='Mobile_No';
            }else if($order[0]['column']==3){
                $column_name='Amount';
            }else if($order[0]['column']==4){
            $column_name='Charge';
            }else if($order[0]['column']==5){
            $column_name='Creation_Date_Time';
            }else if($order[0]['column']==6){
            $column_name='Third_Party_Tran_Id';
            }
            else{
                $column_name='Creation_Date_Time';
            }
        }
        $totalRecord      = $this->users_model->withdraw_history_ajaxlist(true);
        $getRecordListing = $this->users_model->withdraw_history_ajaxlist(false,$start,$length,$column_name, $order[0]['dir']);
        $recordListing = array();
        $content='[';
        $i=0;       
        $srNumber=$start;       
        $currency= $this->config->item("currency");
        if(!empty($getRecordListing)) {
            $actionContent = '';
            foreach($getRecordListing as $recordData) {
                $userListData  = array(); //set default array
                $actionContent = ''; // set default empty
                $content .='[';
                
                $recordListing[$i][0]= $srNumber+1;
                $recordListing[$i][1]= ucwords(strtolower($recordData->FirstName.' '.$recordData->LastName));
                $recordListing[$i][2]= $recordData->Mobile_No;
                $recordListing[$i][3]= $currency.''.$recordData->Amount;
                $recordListing[$i][4]= $currency.''.$recordData->Charge;   
                $recordListing[$i][5]= dateFormat($recordData->Creation_Date_Time);
                $recordListing[$i][6]= $recordData->Third_Party_Tran_Id;
                $recordListing[$i][7]= getPaymentTypeText($recordData->Tran_Type_Id);
                $recordListing[$i][8]= getPaymentStatusText($recordData->Tran_Status_Id);  
                 
                $actionContent = '';
                $actionContent .='<a href="'.base_url('admin/home/withdraw_details/'.base64_encode($recordData->Id)).'" title="Edit" class="btn btn-primary btn-round btn-simple "><i class="fa fa-desktop"></i> Details</a> ';

                $recordListing[$i][9]= $actionContent;
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
    public function withdraw_details($trans_id=''){
        $permission_data=get_permission_detail(self::$admin_id);
        $find_permission= unserialize($permission_data[0]['Permission']);  
        if($find_permission['deposit']==1)
        {
            $data['title']='Withdraw Request ';
            $condition    = array('Id'=> base64_decode($trans_id));
            $transdata    = getdatafromtable('Transactions',$condition);
            $data['transdata']=$transdata;
            $data['currency']=$this->config->item("currency");
            if(!empty($transdata)){
                if($transdata[0]['From_User_Id']!=0 ){
                     $user_where  = array('Id'=>$transdata[0]['From_User_Id']);
                     $withdrawdata = getdatafromtable('users',$user_where,'FirstName,LastName,Id');
                      $data['withdrawdata'] = isset($withdrawdata) ? $withdrawdata[0]['FirstName'].' '.$withdrawdata[0]['LastName'] : '';
                }else{
                     $withdraw_where  = array('Id'=>$transdata[0]['To_Payment_Method_Id'],'User_Id'=>$transdata[0]['To_User_Id']);
                     $withdrawdata = getdatafromtable('User_Payment_Methods',$withdraw_where); 
                      $data['withdrawdata'] = isset($withdrawdata[0]['Wallet_Bank_Name']) ? $withdrawdata[0]['Wallet_Bank_Name'] : ''; 
                }
            }
            $user_where  = array('Id'=>$transdata[0]['To_User_Id']);
            $data['userdata'] = getdatafromtable('users',$user_where,'FirstName,LastName,Id');
            $this->admintemplates('withdraw_details',$data);
        }else{
            redirect('admin');
        }
    }
   public function deposit_history(){
        $permission_data=get_permission_detail(self::$admin_id);
        $find_permission= unserialize($permission_data[0]['Permission']);  
        if($find_permission['deposit']==1)
        {
            $data['title']='Deposit History ';
            $this->admintemplates('deposit_history',$data);
        }else{
            redirect('admin');
        }
    }
    public function deposit_history_ajaxlist(){  
        $start         =  $this->input->get('start'); 
        $length        =  $this->input->get('length'); 
        $draw          =  $this->input->get('draw'); 
        $order         =  $this->input->get('order');
        if(!empty($order)){ 
            if($order[0]['column']==1){
                $column_name='FirstName';
            }else if($order[0]['column']==2){
                $column_name='Mobile_No';
            }else if($order[0]['column']==3){
                $column_name='Amount';
            }else if($order[0]['column']==4){
            $column_name='Charge';
            }else if($order[0]['column']==5){
            $column_name='Creation_Date_Time';
            }else if($order[0]['column']==6){
            $column_name='Third_Party_Tran_Id';
            }
            else{
             $column_name='Creation_Date_Time';
            }
        }
        $totalRecord      = $this->users_model->deposit_history_ajaxlist(true);
        $getRecordListing = $this->users_model->deposit_history_ajaxlist(false,$start,$length,$column_name, $order[0]['dir']);
        $recordListing = array();
        $content='[';
        $i=0;       
        $srNumber=$start;       
        $currency= $this->config->item("currency");
        if(!empty($getRecordListing)) {
            $actionContent = '';
            foreach($getRecordListing as $recordData) {
                $userListData  = array(); //set default array
                $actionContent = ''; // set default empty
                $content .='[';
                
                $recordListing[$i][0]= $srNumber+1;
                $recordListing[$i][1]= ucwords(strtolower($recordData->FirstName.' '.$recordData->LastName));
                $recordListing[$i][2]= $recordData->Mobile_No;
                $recordListing[$i][3]= $currency.''.$recordData->Amount;
                $recordListing[$i][4]= $currency.''.$recordData->Charge;   
                $recordListing[$i][5]= dateFormat($recordData->Creation_Date_Time);
                $recordListing[$i][6]= $recordData->Third_Party_Tran_Id;
                $recordListing[$i][7]= getPaymentTypeText($recordData->Tran_Type_Id);
                $recordListing[$i][8]= getPaymentStatusText($recordData->Tran_Status_Id);  
                
                $actionContent = '';
                $actionContent .='<a href="'.base_url('admin/home/deposit_details/'.base64_encode($recordData->Id)).'" title="Edit" class="btn btn-primary btn-round btn-simple "><i class="fa fa-desktop"></i> Details</a> ';

                $recordListing[$i][9]= $actionContent;

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
    public function deposit_details($trans_id=''){
        $permission_data=get_permission_detail(self::$admin_id);
        $find_permission= unserialize($permission_data[0]['Permission']);  
        if($find_permission['deposit']==1)
        {
            $data['title']='Deposit Details ';
            $condition    = array('Id'=> base64_decode($trans_id));
            $transdata    = getdatafromtable('Transactions',$condition);
            $data['transdata']=$transdata;
           // echo "<pre>";print_r($data['transdata']);
            $data['currency']=$this->config->item("currency");
            $deposit_where  = array('Id'=>$transdata[0]['To_Payment_Method_Id'],'User_Id'=>$transdata[0]['To_User_Id']);
            $data['depositdata'] = getdatafromtable('User_Payment_Methods',$deposit_where);  
            // echo "<pre>";print_r($data['depositdata']);die;
            $user_where  = array('Id'=>$transdata[0]['To_User_Id']);
            $data['userdata'] = getdatafromtable('users',$user_where,'FirstName,LastName,Id'); 
            $this->admintemplates('deposit_details',$data);        
        }else{
            redirect('admin');
        }
    }
    public function send_money(){
        $data['title']='Tips To/From';
        $this->admintemplates('transactions/sendmoney',$data);   
    }
    public function send_money_ajaxlist(){  
        $start         =  $this->input->get('start'); 
        $length        =  $this->input->get('length'); 
        $draw          =  $this->input->get('draw'); 
        $order         =  $this->input->get('order');
        if(!empty($order)){ 
            if($order[0]['column']==1){
                $column_name='FullName';
            }else if($order[0]['column']==2){
                $column_name='fullname';
            }else if($order[0]['column']==3){
                $column_name='Amount';
            }else if($order[0]['column']==4){
            $column_name='Charge';
            }else if($order[0]['column']==5){
            $column_name='Third_Party_Tran_Id';
            }
            else{
                $column_name='Creation_Date_Time';
            }
        }
        $totalRecord      = $this->users_model->send_money_ajaxlist(true);
        $getRecordListing = $this->users_model->send_money_ajaxlist(false,$start,$length,$column_name, $order[0]['dir']);
        $recordListing = array();
        $content='[';
        $i=0;       
        $srNumber=$start;       
        $currency =$this->config->item("currency");
        if(!empty($getRecordListing)) {
            $actionContent = '';
            foreach($getRecordListing as $recordData) {
                $userListData  = array(); //set default array
                $actionContent = ''; // set default empty
                $content .='['; 
                $recordListing[$i][0]= $srNumber+1;
                $recordListing[$i][1]=  name_format($recordData->FullName).'<br>('.$recordData->Mobile_No.')';
                $recordListing[$i][2]=  name_format($recordData->fullname).'<br>('.$recordData->mobile_no.')'; 
                $recordListing[$i][3]= $currency.''.$recordData->Amount;
                $recordListing[$i][4]= $recordData->Third_Party_Tran_Id;  
                $recordListing[$i][5]= dateFormat($recordData->Creation_Date_Time);
                $recordListing[$i][6]= getPaymentStatusText($recordData->Tran_Status_Id);  
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
   
   /**
    * user_transactions
    *
    * View user transactions
    *
    * @param int user_id 
    * @param int usertype means customer or merchant
    */
    public function user_transactions($userid='',$usertype=''){
        $permission_data=get_permission_detail(self::$admin_id);
        $find_permission= unserialize($permission_data[0]['Permission']);  
        if($usertype ==1)
        {
            if($find_permission['transaction']==1)
            {
                $data['title']='All Transactions';
                $data['userid']=$userid;
                $data['usertype']=$usertype;
                $data['trxtype']='all_transacxtion';
                $this->admintemplates('user_trxs',$data);
            }else{
                redirect('admin');
            }
        }elseif($usertype==2){
            if($find_permission['transaction']==1)
            {
                $data['title']='All Transactions';
                $data['userid']=$userid;
                $data['usertype']=$usertype;
                $data['trxtype']='all_transacxtion';
                $this->admintemplates('user_trxs',$data);
            }else{
                redirect('admin');
            }
        }else{
                redirect('admin');
            }
    }
    /**
    * user_deposits
    *
    * View user deposits 
    *
    * @param int user_id 
    * @param int usertype means customer or merchant
    */
    public function user_deposits($userid='',$usertype=''){
        $permission_data=get_permission_detail(self::$admin_id);
        $find_permission= unserialize($permission_data[0]['Permission']); 
        if($usertype==1)
        {
            if($find_permission['deposit']==1)
            {
                $data['title']='Deposit Log';
                $data['userid']=$userid;
                $data['usertype']=$usertype;
                $data['trxtype']='deposit';
                $this->admintemplates('user_trxs',$data);
            }else{
                redirect('admin');
            }
        }elseif($usertype==2){
            if($find_permission['deposit']==1)
            {
                $data['title']='Deposit Log';
                $data['userid']=$userid;
                $data['usertype']=$usertype;
                $data['trxtype']='deposit';
                $this->admintemplates('user_trxs',$data);
            }else{
                redirect('admin');
            }
        }else{
                redirect('admin');
        }
    }
    /**
    * user_withdraw
    *
    * View user withdraw  
    *
    * @param int user_id 
    * @param int usertype means customer or merchant
    */
    public function user_withdraw($userid='',$usertype=''){
        $permission_data=get_permission_detail(self::$admin_id);
        $find_permission= unserialize($permission_data[0]['Permission']); 
        if($usertype==1)
        {
            if($find_permission['withdraw']==1)
            {
                $data['title']='Withdraw Log';
                $data['userid']=$userid;
                $data['usertype']=$usertype;
                $data['trxtype']='withdraw';
                $this->admintemplates('user_trxs',$data);
            }else{
                redirect('admin');
            }
        }elseif($usertype==2){
            if($find_permission['withdraw']==1)
            {
                $data['title']='Withdraw Log';
                $data['userid']=$userid;
                $data['usertype']=$usertype;
                $data['trxtype']='withdraw';
                $this->admintemplates('user_trxs',$data);
            }else{
                redirect('admin');
            }
        }else{
                redirect('admin');
        }
    }
    public function usertransactionsajaxlist($userid='',$trxtype=''){   
        $userid        =  base64_decode($userid);  
        $start         =  $this->input->get('start'); 
        $length        =  $this->input->get('length'); 
        $draw          =  $this->input->get('draw'); 
        $order         =  $this->input->get('order');
        if(!empty($order)){ 
            if($order[0]['column']==1){
                $column_name='FirstName';
            }else if($order[0]['column']==2){
                $column_name='Mobile_No';
            }else if($order[0]['column']==3){
                $column_name='Amount';
            }else if($order[0]['column']==4){
            $column_name='Charge';
            }else if($order[0]['column']==5){
            $column_name='Creation_Date_Time';
            }else if($order[0]['column']==6){
            $column_name='Third_Party_Tran_Id';
            }
            else{
                $column_name='Transactions.Creation_Date_Time';
            }
        }
        $totalRecord      = $this->users_model->usertransactionajaxlist(true,'','','','',$userid,$trxtype);
        $getRecordListing = $this->users_model->usertransactionajaxlist(false,$start,$length,$column_name, $order[0]['dir'],$userid,$trxtype);
        //$totalRecord= count($getRecordListing);
        $recordListing = array();
        $content='[';
        $i=0;       
        $srNumber=$start;       
        $currency =$this->config->item("currency");
        if(!empty($getRecordListing)){
            $actionContent = '';
            foreach($getRecordListing as $recordData){
                $userListData  = array(); //set default array
                $actionContent = ''; // set default empty
                $content .='['; 
                $recordListing[$i][0]= $srNumber+1;
                //$where= array('Id'=>$recordData->From_User_Id);
                //$userdata     = getdatafromtable('users',$where,'FirstName,LastName,Mobile_No');
                 $recordListing[$i][1]= name_format($recordData->FirstName.' '.$recordData->LastName);
                 $recordListing[$i][2]= $recordData->Mobile_No;
                 $recordListing[$i][3]= $currency.''.$recordData->Amount;
                 $recordListing[$i][4]= $currency.''.$recordData->Charge;   
                 $recordListing[$i][5]= dateFormat($recordData->Creation_Date_Time);
                 $recordListing[$i][6]= $recordData->Third_Party_Tran_Id;
                 $recordListing[$i][7]= getPaymentTypeText($recordData->Tran_Type_Id);    
                //$recordListing[$i][8]= $recordData->Refund_Reason;  
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
    /**
    * user_logins
    *
    * View user logins 
    *
    * @param int user_id 
    * @param int usertype means customer or merchant
    */
    public function user_logins($userid='',$usertype=''){
        $permission_data=get_permission_detail(self::$admin_id);
        $find_permission= unserialize($permission_data[0]['Permission']); 
        if($usertype ==1){
            if($find_permission['user_manage']==1)
            {
              $data['title']   = 'Login Information';
              $data['userid']  = $userid;
              $data['usertype']= $usertype;
              $this->admintemplates('user_logins',$data);
            }else{
                redirect('admin');
            }
        }elseif($usertype==2){
            if($find_permission['merchant_manage']==1)
            {
              $data['title']    = 'Login Information';
              $data['userid']   = $userid;
              $data['usertype'] = $usertype;
              $this->admintemplates('user_logins',$data);
            }else{
                redirect('admin');
            }
        }else{
            redirect('admin');
        }
    }
    public function userloginajaxlist($userid=''){
         //usertype means customer or merchant
         //userverified means user verifed, user banned or mobile unverified users

        $start         =  $this->input->get('start'); 
        $length        =  $this->input->get('length'); 
        $draw          =  $this->input->get('draw'); 
        $order         =  $this->input->get('order');
        if(!empty($order)){ 
            if($order[0]['column']==1){
                $column_name='FirstName';
            }else if($order[0]['column']==2){
                $column_name='Ip_Address';
            }else if($order[0]['column']==3){
                $column_name='Location';
            }else{
                $column_name='Creation_Date_Time';
            }

        }
        $userid= base64_decode($userid);
        $totalRecord      = $this->users_model->userloginajaxlist(true,'','','','',$userid);
        $getRecordListing = $this->users_model->userloginajaxlist(false,$start,$length,$column_name, $order[0]['dir'],$userid);
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
                $recordListing[$i][1]= name_format($recordData->FirstName.' '.$recordData->LastName);
                $recordListing[$i][2]= $recordData->Ip_Address;
                $recordListing[$i][3]= $recordData->Location;
                $recordListing[$i][4]= $recordData->User_Os_Platform;   
                $recordListing[$i][5]= dateFormat($recordData->Creation_Date_Time);  
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
    public function all_qrcodes(){
        // $permission_data=get_permission_detail(self::$admin_id);
        // $find_permission= unserialize($permission_data[0]['Permission']);  
        // if($find_permission['qrcode']==1)
        // {
            $data['title']='All Qr Codes';
            $this->admintemplates('all_qrcodes',$data); 
        // }else{
        //     redirect('admin');
        // }
    }
    public function allqrcodesajaxlist(){  

        $start         =  $this->input->get('start'); 
        $length        =  $this->input->get('length'); 
        $draw          =  $this->input->get('draw'); 
        $order         =  $this->input->get('order');
        if(!empty($order)){ 
            if($order[0]['column']==1){
                $column_name='FirstName';
            }else if($order[0]['column']==2){
                $column_name='Mobile_No';
            }else if($order[0]['column']==3){
                $column_name='Role_Id';
            }else if($order[0]['column']==4){
            $column_name='Creation_Date_Time';
            }else if($order[0]['column']==5){
            $column_name='QR_Code';
            }
            else{
                $column_name='Creation_Date_Time';
            }
        }
        $totalRecord      = $this->users_model->allqrcodesajaxlist(true);
        $getRecordListing = $this->users_model->allqrcodesajaxlist(false,$start,$length,$column_name, $order[0]['dir']);
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
                $recordListing[$i][1]= name_format($recordData->FirstName.' '.$recordData->LastName);
                $recordListing[$i][2]= $recordData->Mobile_No; 
                // $recordListing[$i][3]= get_role_name($recordData->User_Id); 
                $recordListing[$i][3]= dateFormat($recordData->Creation_Date_Time);
                if(!empty($recordData->QR_Code)){
                   $recordListing[$i][4]= $recordData->QR_Code;
                }else{
                   $recordListing[$i][4]= '-';
                }
                
                if($recordData->QR_Code_Img_Path){
                    $qrcode_url= IMAGE_PATH.'qrcodes/'.$recordData->QR_Code_Img_Path;
                     $filename = "$qrcode_url";
                      if(@getimagesize($filename)){
                       $recordListing[$i][5]= '<img src="'.$qrcode_url.'" width="60px">';
                       }else{
                        $recordListing[$i][5]= '-';
                       }
                }else{ 
                     $recordListing[$i][5]= '-';
                }

                // $del_Id='User_Id';
                // $table='User_In_Roles';
                // $field='QR_Status';
                // $urls = base_url('admin/home/updateStatus');
                // if($recordData->QR_Status==1){
                //     $actionContent .='<a class="activate_br btn  waves-effect" href="javascript:void(0);" onclick="sweetalert('.$recordData->User_Id.', \''.$recordData->QR_Status.'\', \''.$urls.'\' , \''.$table.'\', \''.$field.'\',\''.$del_Id.'\');" title="Active">Active</a>';
                // }else{ 
                //     $actionContent .='<a class="deactivate_br btn waves-effect" href="javascript:void(0);" onclick="sweetalert('.$recordData->User_Id.', \''.$recordData->QR_Status.'\', \''.$urls.'\', \''.$table.'\', \''.$field.'\',\''.$del_Id.'\');" title="Deactive">Deactive</a>';
                // }
                // $recordListing[$i][7]= $actionContent;
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
    public function feedback(){
        $permission_data=get_permission_detail(self::$admin_id);
        $find_permission= unserialize($permission_data[0]['Permission']);  
        if($find_permission['feedback']==1)
        {
            $data['title']='All Feedback';
            $this->admintemplates('feedback_list',$data); 
        }else{
            redirect('admin');
        }
    }
    public function allfeedbackajaxlist(){  

        $start         =  $this->input->get('start'); 
        $length        =  $this->input->get('length'); 
        $draw          =  $this->input->get('draw'); 
        $order         =  $this->input->get('order');
        if(!empty($order)){ 
            if($order[0]['column']==1){
                $column_name='FirstName';
            }else if($order[0]['column']==2){
                $column_name='Email';
            }else if($order[0]['column']==3){
                $column_name='Subject';
            }else{
                $column_name='Creation_Date_Time';
            }
        }
        $totalRecord      = $this->users_model->allfeedbackajaxlist(true);
        $getRecordListing = $this->users_model->allfeedbackajaxlist(false,$start,$length,$column_name, $order[0]['dir']);
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
                $recordListing[$i][1]= ucwords(strtolower($recordData->First_Name.' '.$recordData->Last_Name));
                $recordListing[$i][2]= $recordData->Email; 
                $recordListing[$i][3]= $recordData->Subject; 
                // $recordListing[$i][4]= $recordData->Message; 
                // $recordListing[$i][5]= $recordData->Type; 
                $recordListing[$i][4]= dateFormat($recordData->Creation_Date_Time);
                $actionContent = '';
                $actionContent .='<a href="'.base_url('admin/home/feedback_details/'.base64_encode($recordData->Id)).'" title="Edit" class="btn btn-primary btn-round btn-simple "><i class="fa fa-desktop"></i> Details</a> ';

                $recordListing[$i][5]= $actionContent;

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
    public function feedback_details($id=''){
        $permission_data=get_permission_detail(self::$admin_id);
        $find_permission= unserialize($permission_data[0]['Permission']);  
        if($find_permission['feedback']==1)
        {
            $data['title']='Feedback details';
            $where =array('Id'=>base64_decode($id));
            $data['feedback_data']=$this->dynamic_model->get_row('User_Feedback',$where);
            $this->admintemplates('feedback_details',$data); 
        }else{
            redirect('admin');
        }
    }
    public function set_transaction_limit(){
        $permission_data=get_permission_detail(self::$admin_id);
        $find_permission= unserialize($permission_data[0]['Permission']);  
        if($find_permission['trx_limit']==1)
        {
            $data['title']='Transactions Limit';
            $where_add_money =array('tran_type_id'=>2);
            $data['deposit_limit_data']=$this->dynamic_model->get_row('Transactions_Limit',$where_add_money);
            $where_send_money =array('tran_type_id'=>3);
            $data['send_limit_data']=$this->dynamic_model->get_row('Transactions_Limit',$where_send_money);
            
            $where_wihdrawal_money =array('tran_type_id'=>1);
            $data['withdraw_limit_data']=$this->dynamic_model->get_row('Transactions_Limit',$where_wihdrawal_money);
            $this->admintemplates('set_transaction_limit',$data); 
        }else{
            redirect('admin');
        }
    }
    public function set_transaction_limit_action(){
        $this->form_validation->set_error_delimiters("<p class='inputerror text text-danger error'>", "</p>");
        $this->form_validation->set_rules('daily_limit', 'Daily Limit', 'required|is_numeric');  
        $this->form_validation->set_rules('monthly_limit','Monthly Limit ','required|is_numeric');   
        $this->form_validation->set_rules('daily_count_limit','Daily Count Limit','required|is_numeric');  
        $this->form_validation->set_rules('monthly_count_limit','Monthly Count Limit','required|is_numeric');  
          
        if($this->form_validation->run() == FALSE){  
            $error=array(
                          'daily_limit'         =>form_error('daily_limit'),  
                          'monthly_limit'       =>form_error('monthly_limit'),  
                          'daily_count_limit'   =>form_error('daily_count_limit'),  
                          'monthly_count_limit' =>form_error('monthly_count_limit')  
                       );
                $return = array('status'=>false,'message'=>'All fields are mandatory..','data'=>$error);
        }else{
               $trx_type            = $this->input->post('trx_type');
               $daily_limit         = $this->input->post('daily_limit');
               $monthly_limit       = $this->input->post('monthly_limit');
               $daily_count_limit   = $this->input->post('daily_count_limit');
               $monthly_count_limit = $this->input->post('monthly_count_limit');
                if($trx_type ==2)
                {
                   $updatedata=array(
                                'tran_type_id'       => $trx_type,
                                'count_limit'        => $daily_count_limit,
                                'daily_limit'        => $daily_limit,
                                'monthly_trans_limit'=> $monthly_count_limit,
                                'monthly_limit'      => $monthly_limit,
                                'created_on'         => date('Y-m-d H:i:s')  
                             );
                  $where       = array('tran_type_id'=>$trx_type);
                  $this->dynamic_model->updateRowWhere('Transactions_Limit',$where,$updatedata);
                  $return=array('status'=>true,'message'=>'Saved Successfully','data'=>'');  
                }elseif($trx_type ==3){
                      $updatedata=array(
                                'tran_type_id'       => $trx_type,
                                'count_limit'        => $daily_count_limit,
                                'daily_limit'        => $daily_limit,
                                'monthly_trans_limit'=> $monthly_count_limit,
                                'monthly_limit'      => $monthly_limit,
                                'created_on'         => date('Y-m-d H:i:s')  
                             );
                    $where = array('tran_type_id'=>$trx_type);
                    $this->dynamic_model->updateRowWhere('Transactions_Limit',$where,$updatedata);
                    $return=array('status'=>true,'message'=>'Saved Successfully','data'=>'');  
                }elseif($trx_type ==1){
                       $updatedata=array(
                                'tran_type_id'       => $trx_type,
                                'count_limit'        => $daily_count_limit,
                                'daily_limit'        => $daily_limit,
                                'monthly_trans_limit'=> $monthly_count_limit,
                                'monthly_limit'      => $monthly_limit,
                                'created_on'         => date('Y-m-d H:i:s')  
                             );
                     $where = array('tran_type_id'=>$trx_type);
                     $this->dynamic_model->updateRowWhere('Transactions_Limit',$where,$updatedata);
                     $return=array('status'=>true,'message'=>'Saved Successfully','data'=>'');  
                }else{
                    $return=array('status'=>flase,'message'=>'Saved Successfully');  
                }     
               
            }
             echo json_encode($return);        
       }
       public function exportTransactioncsv($file_type=''){
            $currency =$this->config->item("currency");
            $csvOutput = "";
            $file= 'Users-Transaction-List';
            $getRecordListing = $this->users_model->trx_details();
            $getRecordListing = json_decode(json_encode($getRecordListing),true);
            //echo "<pre>";print_r($getRecordListing);die;
            //Code for CSV output
            $header  = array(
            'User',
            'Mobile',
            'Amount',
            'Date',
            'Transaction No',
            'Transaction Type',
            'Transaction Status',
        );    
        
        //Code for make header of CSV file
        for($head=0; $head<count($header); $head++)
        {
            $csvOutput .= $header[$head].",";
        }
        
        $csvOutput .= "\n";
        //Code for make rows of CSV file
        foreach($getRecordListing as $key => $recordData){

            $csvOutput .= name_format($recordData['FullName']).",";
            $csvOutput .= $recordData['Mobile_No'].",";     
            $csvOutput .= $currency.''.number_format((float)$recordData['Amount'], 2, '.', '').",";  
            $csvOutput .= dateFormat($recordData['Creation_Date_Time']).",";
            $csvOutput .= $recordData['Third_Party_Tran_Id'].",";  
            $csvOutput .= getPaymentTypeName($recordData['Tran_Type_Id']).",";  
            $csvOutput .= $recordData['Status_Name'].",";   
            $csvOutput .= "\n";
        }
        
       // echo "<pre>"; print_r($getRecordListing);die();
        $filename = $file."-".date("Y-m-d",time());

        // header('Content-Type: application/csv');
        // header('Content-Disposition: attachment; filename="filename.csv"');

        header('Content-Type: text/csv; charset=utf-8');
        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: csv" . date("Y-m-d") . ".".$file_type);
        header("Content-disposition: filename=".$filename.".".$file_type);
        print $csvOutput;
        exit;
    }
    public function exportUsercsv($type='',$status=''){
            $currency =$this->config->item("currency");
            $csvOutput = "";
            $file= (!empty($type==1)) ? 'Senders-List' : 'Receivers-List';
            $getRecordListing = $this->users_model->users_details($type,$status);
            $getRecordListing = json_decode(json_encode($getRecordListing),true);

            //Code for CSV output
            $header  = array(
            'Name',
            'Email',
            'Mobile',
            'Wallet Balance',
            'Referral Points',
            'Etippers Id',
            'Member Since',
            'Online',
            'Document Status',
            'Status',
          );    
        //Code for make header of CSV file
        for($head=0; $head<count($header); $head++)
        {
            $csvOutput .= $header[$head].",";
        }
        
        $csvOutput .= "\n";
        //Code for make rows of CSV file
        foreach($getRecordListing as $key => $recordData){
                $wh=array('User_Id'=>$recordData['User_Id']); 
                $user_document_status= getdatafromtable('users_documents',$wh);
                if(!empty($user_document_status)){
                    if(@$user_document_status[0]['Is_Verified']==1){
                       $doc_status ='Verified';
                    }else{
                        $doc_status ='Unverified';
                    }
                }else{
                 $doc_status = 'Unverified';
                }

            $status=($recordData['Is_Active']==1) ? 'Enabled' : 'Disabled';
            $login_status=($recordData['Is_LoggedIn'] == 1) ? 'Online' : 'Offline';
            $etippers_id=(!empty($recordData['etippers_id'])) ? $recordData['etippers_id'] : '-';
            $csvOutput .= name_format($recordData['FullName']).",";
            $csvOutput .= $recordData['Email'].",";  
            $csvOutput .= $recordData['Mobile_No'].",";     
            $csvOutput .= $currency.''.number_format((float)$recordData['Current_Wallet_Balance'], 2, '.', '').",";    
            $csvOutput .= $recordData['Total_Referral_Points'].","; 
            $csvOutput .= $etippers_id.","; 
            $csvOutput .= dateFormat($recordData['Creation_Date_Time']).",";
            $csvOutput .= $login_status.",";   
            $csvOutput .= $doc_status.",";   
            $csvOutput .= $status.",";   
            $csvOutput .= "\n";
        }
       // echo "<pre>"; print_r($getRecordListing);die();
        $filename = $file."-".date("Y-m-d",time());
        // header('Content-Type: application/csv');
        // header('Content-Disposition: attachment; filename="filename.csv"');
        header('Content-Type: text/csv; charset=utf-8');
        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: csv" . date("Y-m-d").".csv");
        header("Content-disposition: filename=".$filename.".csv");
        print $csvOutput;
        exit;
    }


    public function allagentsajaxlist(){       
        $start         =  $this->input->get('start'); 
        $length        =  $this->input->get('length'); 
        $draw          =  $this->input->get('draw'); 
        $order         =  $this->input->get('order');
        if(!empty($order)){ 
            if($order[0]['column']==1){
                $column_name='FirstName';
            }else if($order[0]['column']==2){
                $column_name='LastName';
            }else if($order[0]['column']==3){
                $column_name='Mobile_No';
            }else if($order[0]['column']==4){
                $column_name='Email';
            }
            else{
                $column_name='Creation_Date_Time';
            }
        }
        $totalRecord      = $this->users_model->agentajaxlist(true);
        $getRecordListing = $this->users_model->agentajaxlist(false,$start,$length,$column_name, $order[0]['dir']);
        $recordListing = array();
        $content='[';
        $i=0;       
        $srNumber=$start;       
        $currency= $this->config->item("currency");
    //    echo "<pre>";print_r($getRecordListing);die;
        if(!empty($getRecordListing)) {
            $actionContent = '';
            foreach($getRecordListing as $recordData) {
                $userListData  = array(); //set default array
                $actionContent = ''; // set default empty
                $content .='['; 
                $recordListing[$i][0]= $srNumber+1;
                $recordListing[$i][1]= name_format($recordData->FirstName.' '.$recordData->LastName);
                $recordListing[$i][2]= $recordData->Email; 
                $recordListing[$i][3]= $recordData->Mobile_No;
                $recordListing[$i][4]= $recordData->Address; 
                $recordListing[$i][5]= dateFormat($recordData->Creation_Date_Time);

                $del_Id='Id';
                $table='users';
				$field='Is_Active';
			 	$urls = base_url('admin/home/updateStatus');
                if($recordData->Is_Mobile_Verified ==1 && $recordData->Is_Email_Verified ==1)
                {
                    $doc_verified_status='1';
                }elseif($recordData->Is_Mobile_Verified ==0){
                    $doc_verified_status='2';
                }elseif($recordData->Is_Email_Verified ==0){
                    $doc_verified_status='3';
                }else{
                    $doc_verified_status='0';
                }
                if($recordData->Is_Active==1){
                    $statuschng ='statussucc'.$recordData->Id;
					$actionContent .='<a class="btn '.$statuschng.' activate_br waves-effect" href="javascript:void(0);" onclick="sweetalert('.$recordData->Id.', \''.$recordData->Is_Active.'\', \''.$urls.'\' , \''.$table.'\', \''.$field.'\',\''.$del_Id.'\');" title="Enabled">Enabled</a>';
				}
                else{ 
                    $statuschng ='statusdng'.$recordData->Id;
					$actionContent .='<a class="btn '.$statuschng.' deactivate_br waves-effect" href="javascript:void(0);" onclick="sweetalert('.$recordData->Id.', \''.$recordData->Is_Active.'\', \''.$urls.'\', \''.$table.'\', \''.$field.'\',\''.$del_Id.'\');" title="Disabled">Disabled</a>';
				}

                $recordListing[$i][6]= $actionContent;

                 //blank for edit button
                $actionContent = '';
                 $actionContent .='<a href="'.base_url('admin/agent_details/'.base64_encode($recordData->Id)).'" title="Edit" class="btn btn-success btn-sm_eye_box btn-sm"><i class="material-icons material-icons_eye">remove_red_eye</i></a> ';
                 
                    // $actionContent .='<a href="'.base_url('admin/agent_delete/'.base64_encode($recordData->Id)).'" title="Delete" class="btn btn-danger btn-sm">Remove</a> ';


                    $del_Id='Id';
                    $table='users';
                    $field='Is_Active';
                    $urls = base_url('admin/home/deleteagent');
                    //  $statuschng ='statusdng'.$recordData->Id;
					$actionContent .='<a class="btn statusdng0 btn-sm_eye_box btn-sm" href="javascript:void(0);" onclick="deletealert('.$recordData->Id.', \''.$recordData->Is_Active.'\', \''.$urls.'\', \''.$table.'\', \''.$field.'\',\''.$del_Id.'\');" title="Remove"><i class="material-icons">delete_forever</i></a>';


                $recordListing[$i][7]= $actionContent;
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
   

}
