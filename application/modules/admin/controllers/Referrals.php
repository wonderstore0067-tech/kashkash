<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Referrals extends My_Controller {

   private static $admin_id = null;
   public function __construct(){       
        parent::__construct();      
        $this->load->model('dynamic_model');
        $this->load->model('admin_model');
        $this->load->model('admin_model');
        $this->load->model('referrals_model');
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

   public function referrals_listing(){
         $data['title']='Earned Referral Points History';
         $this->admintemplates('referral/referrals_list',$data);  
    }
   public function referrals_management(){
         $data['title']='Referral Points Settings';
        $data['referral_data']= getdatafromtable('refferal_points_settings'); 
         $this->admintemplates('referral/referrals_management',$data);
  
    }
    /**
    * all allsendersajaxlist
    *
    * Fetch ALL Senders Data Uning ajax method.
    *
    * @param int usertype means customer or merchant
    * @param int userverified means user verifed, user banned or mobile unverified users
    */
    public function allreferralsmanagementajaxslist(){  
        $start         =  $this->input->get('start'); 
        $length        =  $this->input->get('length'); 
        $draw          =  $this->input->get('draw'); 
        $order         =  $this->input->get('order');
        if(!empty($order)){ 
            if($order[0]['column']==1){
                $column_name='Give_Refferal_Point';
            }else{
                $column_name='Refferal_Points';
            }
        }   
        $totalRecord      = $this->referrals_model->allreferalspointslist(true);
        $getRecordListing = $this->referrals_model->allreferalspointslist(false,$start,$length,$column_name, $order[0]['dir']);
        $recordListing = array();
        $content='[';
        $i=0;     
        $k=0;  
        $srNumber=$start;       
        if(!empty($getRecordListing)) {
            $actionContent = '';
           // Id    Give_Refferal_Point    Refferal_Points     Refferal_Amount
            foreach($getRecordListing as $recordData) {
                $userListData  = array(); //set default array
                $actionContent = ''; // set default empty
                $content .='[';
                $recordListing[$i][0]= $srNumber+1;
                $recordListing[$i][1]= $recordData->Give_Refferal_Point;
                $del_Id='Id';
                $table='refferal_points_settings';

                $recordListing[$i][2]= $recordData->Refferal_Points;
                $recordListing[$i][3]= $recordData->Refferal_Amount;
                $newData = array($recordData->Id,$recordData->Give_Refferal_Point,$recordData->Refferal_Points,$recordData->Refferal_Amount);
                $newData2 = implode(",", $newData); 
                $somedata = $recordData->Id."_userdata_".$recordData->Give_Refferal_Point."_userdata_".$recordData->Refferal_Points."_userdata_".$recordData->Refferal_Amount;
                if($k !=0){
                    $recordListing[$i][4]= '<a href="javascript:void(0)" id="'.$recordData->Id.'" title="Edit" class="btn btn-success btn-sm_eye_box btn-sm btnEditRef" data-user="'.$somedata.'" onclick="dataTake('.$newData2.')"><i class="material-icons material-icons_eye">remove_red_eye</i></a> ';    
                }else{
                    $recordListing[$i][4]= '<a href="javascript:void(0)" id="'.$recordData->Id.'" title="Edit" class="btn btn-success btn-sm_eye_box btn-sm btnEditRef"  data-user="'.$somedata.'"><i class="material-icons material-icons_eye">remove_red_eye</i></a> 
                            <script>
                            $(".btnEditRef").click(function(){
                                $(".referralDiv").show();
                                var useris = $(this).attr("data-user");
                                $("#Id").val(useris.split("_userdata_")[0]);
                                $("#Give_Refferal_Point").val(useris.split("_userdata_")[1]);
                                $("#Refferal_Points").val(useris.split("_userdata_")[2]);
                                $("#Refferal_Amount").val(useris.split("_userdata_")[3]);
                                
                                
                            })
                            </script>';
                }
                $k++;
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
    public function allreferralsajaxslist(){  
        $start         =  $this->input->get('start'); 
        $length        =  $this->input->get('length'); 
        $draw          =  $this->input->get('draw'); 
        $order         =  $this->input->get('order');
        if(!empty($order)){ 
            if($order[0]['column']==1){
                $column_name='Balance_Referral_Point';
            }else{
                $column_name='Creation_Date_Time';
            }
        }   
        $totalRecord      = $this->referrals_model->allreferalsslist(true);
        $getRecordListing = $this->referrals_model->allreferalsslist(false,$start,$length,$column_name, $order[0]['dir']);


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
                $recordListing[$i][1]= name_format($recordData->FullName);
                $recordListing[$i][2]=  name_format($recordData->fullname);
                $recordListing[$i][3]= $recordData->Referral_Code;
                $recordListing[$i][4]= $recordData->Referral_Points;
                $recordListing[$i][5]= $recordData->Balance_Referral_Point;
                 $recordListing[$i][6]= dateFormat($recordData->Creation_Date_Time);
                // $recordListing[$i][6]= $recordData->Status;

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
    // Id    Give_Refferal_Point    Refferal_Points     Refferal_Amount
    public function addReferrals(){

        $this->form_validation->set_rules('Give_Refferal_Point', 'Referral-Points-Reward For Sign-Up', 'required');  
        $this->form_validation->set_rules('Refferal_Points', 'Referral-Points Per Referal-Amount', 'required');  
        $this->form_validation->set_rules('Refferal_Amount', 'Referal-Amount', 'required');  

        if ($this->form_validation->run() == FALSE){  
            $error=array(
                          'Give_Refferal_Point' =>form_error('Give_Refferal_Point') , 
                          'Refferal_Points' =>form_error('Refferal_Points') , 
                          'Refferal_Amount' =>form_error('Refferal_Amount') 
                       );
                $return = array('status'=>false,'message'=>'All fields are mandatory..','data'=>$error);
        }else{

               $Give_Refferal_Point  = $this->input->post('Give_Refferal_Point');
               $Refferal_Points  = $this->input->post('Refferal_Points');
               $Refferal_Amount  = $this->input->post('Refferal_Amount');

                   $insertdata=array(
                                    'Give_Refferal_Point'   => $Give_Refferal_Point,
                                    'Refferal_Points'   => $Refferal_Points,
                                    'Refferal_Amount'   => $Refferal_Amount
                                    );

                if($this->input->post('Id')){
                    $upWhere = array("Id" => $this->input->post('Id'));
                    $updateData = $insertdata;
                    $this->dynamic_model->updateRowWhere('refferal_points_settings', $upWhere , $updateData);
                }else{
                    $biller_data = $this->dynamic_model->insertdata('refferal_points_settings',$insertdata);
                }
                $return=array('status'=>true,'message'=>'Saved Successfully','data'=>'');  
            }
          echo json_encode($return);        
   }
   public function redeem_referrals_list(){
         $data['title']='Redeemed Referral Points History';
         $this->admintemplates('referral/redeem_referral_list',$data);  
    }
     public function allredeemajaxlist(){       
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
        $totalRecord      = $this->referrals_model->allredeemajaxlist(true);
        $getRecordListing = $this->referrals_model->allredeemajaxlist(false,$start,$length,$column_name, $order[0]['dir']);
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
                $recordListing[$i][4]= $recordData->Redeem_Referral_Point;
                $recordListing[$i][5]= $recordData->Balance_Referral_Point;
                $recordListing[$i][6]= $recordData->Third_Party_Tran_Id;
                $recordListing[$i][7]= getPaymentTypeText($recordData->Tran_Type_Id);
                $recordListing[$i][8]= getPaymentStatusText($recordData->Tran_Status_Id);
                $recordListing[$i][9]= dateFormat($recordData->Creation_Date_Time);
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
