<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Advertisement extends My_Controller {
   
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

   public function all_advertisements(){
        // $permission_data=get_permission_detail(self::$admin_id);
        // $find_permission= unserialize($permission_data[0]['Permission']);    
        // if($find_permission['user_manage']==1){
         $data['title']='All Advertisements';
         $this->admintemplates('advertisement/all_advertisement',$data);
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
    public function alladvertisementajaxslist(){  
        $start         =  $this->input->get('start'); 
        $length        =  $this->input->get('length'); 
        $draw          =  $this->input->get('draw'); 
        $order         =  $this->input->get('order');
        if(!empty($order)){ 
            if($order[0]['column']==1){
                $column_name='Advertisement_Title';
            }elseif($order[0]['column']==2){
                $column_name='Advertisement_Subtitle';
            }elseif($order[0]['column']==3){
                $column_name='Address';
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
                $recordListing[$i][2]= ucwords(strtolower($recordData->Advertisement_Subtitle));
                if(!empty($recordData->Address && $recordData->Advertisement_Type=="location_wise")){
                 $recordListing[$i][3]= $recordData->Address;
                }else{
                  $recordListing[$i][3]= "-";
                }
                $recordListing[$i][4]= (!empty($recordData->Advertisement_Type && ($recordData->Advertisement_Type=="location_wise"))) ? "Location wise" : 'Default' ;
                $recordListing[$i][5]=  '<img src="'.$qrcode_url.'" width="60px">';
                $del_Id='Id';
                $table='manage_advertisement_images';
                $field='Status';
                $urls = base_url('admin/home/updateStatus');
               
                if($recordData->Status=='1'){
                    $actionContent .='<a class="btn statussucc activate_br waves-effect" href="javascript:void(0);" onclick="sweetalert('.$recordData->Id.', \''.$recordData->Status.'\', \''.$urls.'\' , \''.$table.'\', \''.$field.'\',\''.$del_Id.'\');" title="Enabled">Enabled</a>';
                }else{ 
                    $actionContent .='<a class="btn statussucc deactivate_br waves-effect" href="javascript:void(0);" onclick="sweetalert('.$recordData->Id.', \''.$recordData->Status.'\', \''.$urls.'\', \''.$table.'\', \''.$field.'\',\''.$del_Id.'\');" title="Disabled">Disabled</a>';
                }
                $recordListing[$i][6]= $actionContent;
                $recordListing[$i][7]= '<a href="'.base_url('admin/advertisement_details/'.encode($recordData->Id)).'" title="Edit" class="btn btn-success btn-sm_eye_box btn-sm"><i class="material-icons material-icons_eye">remove_red_eye</i></a> ';

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

    public function get_country(){
      // $wh=array('Id'=>$id); 
      $data= getdatafromtable('countries'); 
      echo json_encode($data);
    }

    public function addAdvertisements(){
         $data['title']='Add New Advertisement ';
        $this->admintemplates('advertisement/add_adv',$data);
    } 
    public function addAdvertisement(){
        $is_submit = $this->input->post('is_submit');
        if (isset($is_submit) && $is_submit == 1) {
           $this->form_validation->set_rules('advertisement_title', 'Advertisement Title', 'required');  
           $this->form_validation->set_rules('advertisement_subtitle', 'Advertisement Sub Title', 'required');  
           $this->form_validation->set_rules('description','page description','required');
           //$this->form_validation->set_rules('adv_address','Advertisement Address','required');
            if ($this->form_validation->run() == FALSE){     
                $this->messages->setMessageFront(validation_errors(),'error'); 
                  redirect(base_url('admin/advertisement/addAdvertisements/'));
            }else{
                $time=time();
                $desc= trim(preg_replace('/\s\s+/', ' ', $this->input->post('description')));
                $discription= str_replace(array('&nbsp;','<p>&nbsp;</p>','<p> </p>'),array('','',''), $desc);
                $dec= strip_tags($discription);
                $updatedata=array();
               if(!empty(trim($dec))){
                 $advertisement_title     = $this->input->post('advertisement_title');
                 $advertisement_subtitle  = $this->input->post('advertisement_subtitle');
                 $advertisement_type      = $this->input->post('advertisement_type');
                 $adv_address             = $this->input->post('adv_address');
                 $adv_address_lat         = $this->input->post('adv_address_lat');
                 $adv_address_lang        = $this->input->post('adv_address_lang');
                 //echo "<pre>";print_r($_POST);die;

                  if(!empty($_FILES['advImg']['name'])){
                        $adv_img  = $this->dynamic_model->fileupload('advImg', '/uploads/advertisement_img');
                    }else{
                        $adv_img = '';
                    }
                   $insertdata=array(
                                    'Advertisement_Title'   => $advertisement_title,
                                    'Advertisement_Subtitle'=> $advertisement_subtitle,
                                    'Advertisement_Type'    => (!empty($advertisement_type)) ? $advertisement_type : 'default',
                                    'Advertisement_Image'   => $adv_img,
                                    'description'           => $discription,
                                    'Address'               => $adv_address,
                                    'Lat'                   => $adv_address_lat,
                                    'Lang'                  => $adv_address_lang,
                                    'Status'                => 1,
                                    'Created_By'            => self::$admin_id,
                                    'Creation_Date_Time'    => date('Y-m-d H:i:s')    
                                    );
                 $this->dynamic_model->insertdata('manage_advertisement_images',$insertdata);
                $this->messages->setMessageFront("Data updated successfully",'success'); 
                redirect(base_url('admin/all_advertisements'));
                }else{
                      $this->messages->setMessageFront("Description field is required",'error'); 
                      redirect(base_url('admin/advertisement/addAdvertisements/'));
                }   
            }
        }else{
            $this->messages->setMessageFront("Wrong Method. Please Fill this Form",'error'); 
            redirect(base_url('admin/advertisement/addAdvertisements/'));
        }
  }
public function updateAdvertisement(){
        $id = $this->input->post('Id');
        $is_submit = $this->input->post('is_submit');
        if (isset($is_submit) && $is_submit == 1) {
             $this->form_validation->set_rules('advertisement_title', 'Advertisement Title', 'required');  
             $this->form_validation->set_rules('advertisement_subtitle', 'Advertisement Sub Title', 'required');  
             $this->form_validation->set_rules('description','page description','required');
             //$this->form_validation->set_rules('adv_address','Advertisement Address','required');
            if ($this->form_validation->run() == FALSE) {     
                $this->messages->setMessageFront(validation_errors(),'error'); 
                  redirect(base_url('admin/advertisement/advertisement_details/'.$id));
            }else{
                $time=time();
                $desc= trim(preg_replace('/\s\s+/', ' ', $this->input->post('description')));
                $discription= str_replace(array('&nbsp;','<p>&nbsp;</p>','<p> </p>'),array('','',''), $desc);
                $dec= strip_tags($discription);
                $updatedata=array();
               if(!empty(trim($dec))){
                 $advertisement_title     = $this->input->post('advertisement_title');
                 $advertisement_type      = $this->input->post('advertisement_type');
                 $advertisement_subtitle  = $this->input->post('advertisement_subtitle');
                 $adv_address             = $this->input->post('adv_address');
                 $adv_address_lat         = $this->input->post('adv_address_lat');
                 $adv_address_lang        = $this->input->post('adv_address_lang');
                 //echo  $_FILES['advImg']['name'];die;
                  if(!empty($_FILES['advImg']['name'])){
                        $adv_img  = $this->dynamic_model->fileupload('advImg', '/uploads/advertisement_img');
                    }else{
                        $adv_img = '';
                    }
                   $id_value = decode($id);
                   $updatedata=array();
                   $updatedata['Advertisement_Title']=$advertisement_title;
                   $updatedata['Advertisement_Subtitle']=$advertisement_subtitle;
                   if(!empty($advertisement_type)){ 
                   $updatedata['Advertisement_Type']=$advertisement_type;
                   }
                   $updatedata['description']=$discription;
                   if($advertisement_type== 'location_wise'){
                    $updatedata['Address']=$adv_address;
                    $updatedata['Lat']=$adv_address_lat;
                    $updatedata['Lang']=$adv_address_lang;  
                   }else{
                     $updatedata['Address']='';
                     $updatedata['Lat']='';
                     $updatedata['Lang']='';  
                   }
                   if(!empty($adv_img)){ 
                    $updatedata['Advertisement_Image']=$adv_img;
                    }
                   
                  $upWhere = array("Id" =>$id_value);
                $this->dynamic_model->updateRowWhere("manage_advertisement_images", $upWhere,$updatedata);
                $this->messages->setMessageFront("Data updated successfully",'success'); 
                redirect(base_url('admin/all_advertisements'));
                }else{
                      $this->messages->setMessageFront("Description field is required",'error'); 
                      redirect(base_url('admin/advertisement/advertisement_details/'.$id));
                }   
            }
        }else{
            $this->messages->setMessageFront("Wrong Method. Please Fill this Form",'error'); 
            redirect(base_url('admin/advertisement/advertisement_details/'.$id));
        }
  }
 public function advertisement_details($id=''){
       // $permission_data=get_permission_detail(self::$admin_id);
       // $find_permission= unserialize($permission_data[0]['Permission']); 
        // if($find_permission['user_manage']==1)
        // {
            $data['title']='Edit Advertisement ';
            $id = decode($id);
            $wh=array('Id'=>$id); 
            $data['advData']= getdatafromtable('manage_advertisement_images',$wh); 
            
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
