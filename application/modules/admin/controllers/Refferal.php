<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Referrals extends My_Controller {
   echo "tyrteertgrgr";die;
   private static $admin_id = null;
   public function __construct(){       
        parent::__construct();      
        $this->load->model('dynamic_model');
        $this->load->model('admin_model');
        $this->load->model('users_model');
        $this->load->model('advertisement_model');
        $this->lang->load("message","english");
        if($this->session->userdata('logged_in')){
            $currentuser = $this->session->userdata('logged_in');
            self::$admin_id = $currentuser['session_userid'];
        }else{
            self::$admin_id=null;
            redirect('admin/','refresh');
        }
    }

   public function refferal_earns_list(){
       echo "test";die;
        // $permission_data=get_permission_detail(self::$admin_id);
        // $find_permission= unserialize($permission_data[0]['Permission']);    
        // if($find_permission['user_manage']==1){
         $data['title']='All Earn Referral Users';
         $this->admintemplates('referral/earn_referral_list',$data);
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
    public function allrefferalearnsajaxslist(){  
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
        $totalRecord      = $this->advertisement_model->alladvertisementslist(true);
        $getRecordListing = $this->advertisement_model->alladvertisementslist(false,$start,$length,$column_name, $order[0]['dir']);
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
                $qrcode_url= IMAGE_PATH.'advertisement_img/'.$recordData->Advertisement_Image;
                $recordListing[$i][0]= $srNumber+1;
                $recordListing[$i][1]= ucwords(strtolower($recordData->Advertisement_Title));
                $recordListing[$i][2]=  '<img src="'.$qrcode_url.'" width="60px">';
                $del_Id='Id';
                $table='Manage_Advertisement_Images';
                $field='Status';
                $urls = base_url('admin/home/updateStatus');
               
                if($recordData->Status=='1'){
                    $actionContent .='<a class="btn statussucc activate_br waves-effect" href="javascript:void(0);" onclick="sweetalert('.$recordData->Id.', \''.$recordData->Status.'\', \''.$urls.'\' , \''.$table.'\', \''.$field.'\',\''.$del_Id.'\');" title="Active">Active</a>';
                }else{ 
                    $actionContent .='<a class="btn statussucc deactivate_br waves-effect" href="javascript:void(0);" onclick="doc_status('.$recordData->Id.', \''.$recordData->Status.'\', \''.$urls.'\', \''.$table.'\', \''.$field.'\',\''.$del_Id.'\');" title="Deactive">Deactive</a>';
                }
                $recordListing[$i][3]= $actionContent;
                $recordListing[$i][4]= '<a href="'.base_url('admin/advertisement_details/'.base64_encode($recordData->Id)).'" title="Edit" class="btn btn-success btn-sm_eye_box btn-sm"><i class="material-icons material-icons_eye">remove_red_eye</i></a> ';

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


    public function addAdvertisements(){

     $this->admintemplates('advertisement/add_adv');

    }
    public function addAdvertisement(){

        $this->form_validation->set_rules('Advertisement_Name', 'Advertisement Title', 'required');  

        if ($this->form_validation->run() == FALSE){  
            $error=array(
                          'Advertisement_Name' =>form_error('Advertisement_Name')  
                       );
                $return = array('status'=>false,'message'=>'All fields are mandatory..','data'=>$error);
        }else{
                if(!empty($_FILES['advImg']['name'])){
                       $adv_img  = $this->dynamic_model->fileupload('advImg', '/uploads/advertisement_img');
                }else{
                    $adv_img = '';
                }

               $Advertisement_Name  = $this->input->post('Advertisement_Name');

                   $insertdata=array(
                                    'Advertisement_Title'   => $Advertisement_Name,
                                    'Advertisement_Image'   => $adv_img,
                                    'Status'                => 1,
                                    'Created_By'            => self::$admin_id,
                                    'Creation_Date_Time'    => date('Y-m-d H:i:s')    
                                    );
                $biller_data = $this->dynamic_model->insertdata('Manage_Advertisement_Images',$insertdata);
                
              
                $return=array('status'=>true,'message'=>'Saved Successfully','data'=>'');  
            }
          echo json_encode($return);        
   }
    public function updateAdvertisement(){

        $this->form_validation->set_rules('Advertisement_Name', 'Advertisement Name', 'required');  

        if ($this->form_validation->run() == FALSE){  
            $error=array(
                          'Advertisement_Name' =>form_error('Advertisement_Name')  
                       );
                $return = array('status'=>false,'message'=>'All fields are mandatory..','data'=>$error);
        }else{
                if(!empty($_FILES['advImg']['name'])){
                       $adv_img  = $this->dynamic_model->fileupload('advImg','/uploads/advertisement_img');
                }else if($this->input->post('fileFack') != ""){
                    $adv_img = $this->input->post('fileFack');
                }
                else{
                    $adv_img = '';
                }
                $id_value = $this->input->post('Id');

               $Advertisement_Name = $this->input->post('Advertisement_Name');
                $insertdata=array(
                                   'Advertisement_Title'    => $Advertisement_Name,
                                  'Advertisement_Image'     => $adv_img    
                                 );
                $upWhere = array("Id" =>$id_value);
                $this->dynamic_model->updateRowWhere("Manage_Advertisement_Images", $upWhere , $insertdata);
                $return=array('status'=>true,'message'=>'Saved Successfully','data'=>'');  
            }
          echo json_encode($return);        
   }
    public function advertisement_details($id=''){
       // $permission_data=get_permission_detail(self::$admin_id);
       // $find_permission= unserialize($permission_data[0]['Permission']); 
        // if($find_permission['user_manage']==1)
        // {
            $data['title']='Advertisement Details';
            $id = base64_decode($id);
            $wh=array('Id'=>$id); 
            $data['advData']= getdatafromtable('Manage_Advertisement_Images',$wh); 
            
            // $user_role_wh=array('User_Id'=>$userid); 
            // $data['user_role_data']= getdatafromtable('User_In_Roles',$user_role_wh);  
            
            // $trans_where =array('To_User_Id'=>$userid,'Tran_Status_Id'=>6); 
            // $data['transcation_data']= getdatafromtable('Transactions',$trans_where,'COUNT(*) as v_count,SUM(Amount) as v_amount');
            // $deposit_where=array('To_User_Id'=>$userid,'Tran_Type_Id'=>2,'Tran_Status_Id'=>6); 
            // $data['deposit_data']= getdatafromtable('Transactions',$deposit_where,'COUNT(*) as v_count,SUM(Amount) as v_amount');
            
            // $withdraw_where=array('To_User_Id'=>$userid,'Tran_Type_Id'=>1,'Tran_Status_Id'=>6); 
            // $data['withdraw_data']= getdatafromtable('Transactions',$withdraw_where,'COUNT(*) as v_count,SUM(Amount) as v_amount');
            
            // $login_where=array('User_Id'=>$userid); 
            // $data['userlogins']= getdatafromtable('User_Logins',$login_where,'COUNT(*) as v_count');
           
            // $data['currency']=$this->config->item("currency");
            $this->admintemplates('advertisement/advertisement_details',$data);
        // }else{
        //     redirect('admin');
        // } 
    }








}
