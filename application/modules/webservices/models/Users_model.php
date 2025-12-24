<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model {
	public function __construct(){
		parent::__construct();
	}

	public function delete_auth($userid)
	{
		$this->db->delete('auth_user', array('user_id' => $userid));
		return true;
	}

    //function for check payment request history 
	function check_request_history($id, $type, $limit = '', $offset = '', $orderby = "")
	{
		$this->db->select('*');
		$this->db->from('request_share');
		$this->db->where("(From_User_Id = $id OR To_User_Id = $id)", NULL, FALSE);
		//$this->db->where('From_User_Id', $id);
		//$this->db->or_where('To_User_Id', $id);
		$this->db->where('Type', $type);
		$this->db->where('Tran_Status_Id !=', 1);
        if($limit != '')
        {
		  	$this->db->limit($limit, $offset);
	    }
		$this->db->order_by("Id", "DESC");
		$query=$this->db->get(); 
		return $query->result_array();
	}

	public function get_country()
	{
		$ignore=array(101,102,104,121,207,239);
		$this->db->select('*');
		$this->db->from('countries');
		$this->db->where_not_in('id',$ignore);
		$this->db->order_by('nicename',"ASC");
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_services($service_name)
	{
		$this->db->like('Service_Name', $service_name);
		$this->db->where('Status',1);
		$query = $this->db->get('Services');
		return $query->result_array();
	}

	function search_user_list($user_id, $search_value)
	{
		//query for get filtered result
		$query = "SELECT id ,FirstName,LastName,Email,Mobile_No,Profile_Pic as profile_image,Mongo_Chat_Object as chat_id,Chat_Sync_Status";

		$query .= "	FROM Users 
					WHERE Chat_Sync_Status = 0 ";
		if($search_value != '')
			$query .= " AND (FirstName  LIKE '$search_value%' OR LastName  LIKE '$search_value%' OR Mobile_No  LIKE '$search_value%' OR Email  LIKE '$search_value%' OR CONCAT( FirstName,  ' ', LastName) LIKE '$search_value%') ";

		$query .= " AND id != $user_id ORDER BY id DESC";

		$results = $this->db->query($query)->result_array();
		return $results;
	}

	function user_friend_detail($id='',$limit = '',$offset = '', $orderby = "")
	{
		$this->db->from('chatRoom');
		//$this->db->where("(sender_id = $id OR receiver_id = $id)");
		$this->db->where("chatRoom.sender_id",$id );
		$this->db->or_where("chatRoom.receiver_id",$id );
		//$this->db->where("chatRoom.group_status","Active");
		if($limit != '')
        {
		  	$this->db->limit($limit, $offset);
	    }
		$this->db->order_by('id',"DESC");
		$query=$this->db->get(); 
		 //echo $this->db->last_query();exit;
		return $query->result_array();
	}
	function getUserProfile($userId='') {
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('Id',$userId);
		$query=$this->db->get(); 
		return $query->result_array();
  }
  function user_check_friend($sender_id='',$receiver_id=''){
		$this->db->select('*');
		$this->db->from('chatRoom');
		$this->db->where("sender_id",$sender_id );
		$this->db->where("receiver_id",$receiver_id );
		$this->db->where("group_status","Active");
		$result_data=$this->db->get()->result_array(); 
		if(!empty($result_data)){
			return $result_data;

		}elseif(empty($result_data)){
	        $this->db->select('*');
			$this->db->from('chatRoom');
			$this->db->where("sender_id",$receiver_id );
			$this->db->where("receiver_id",$sender_id );
			$this->db->where("group_status","Active");
			$result_datas=$this->db->get()->result_array(); 
			return $result_datas;

		}else{
            return false;
		}	
  }
  public function referral_points_update($userid=''){
  	 if(!empty($get_referrals_data)){
	 $referrals_point_info = $this->dynamic_model->get_row('Refferal_Points_Settings',array('Id'=>1));
	 $give_refferal_point=(!empty($referrals_point_info['Give_Refferal_Point'])) ? $referrals_point_info['Give_Refferal_Point'] : '0';
     $referral_from=@$get_referrals_data[0]['Id'];
     if($get_referrals_data[0]['Is_Profile_Complete']==1){
                   $referrals_data =   array( 
                  'Referral_From'   => $referral_from,
	              'Referral_To'     => $userid,
	              'Referral_Code'   =>$referral_code,
	              'Referral_Points' =>$give_refferal_point,
	              'Status'          =>1
                );
		     }else{    
                    $referrals_data = array(
                   'Referral_From'   => $referral_from,
	               'Referral_To'     => $userid,
	               'Referral_Code'   =>$referral_code,
	               'Referral_Points' =>$give_refferal_point,
	               'Status'          =>0
	              );     
		        }
   	    $this->dynamic_model->insertdata('Users_Referrals',$referrals_data);
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
  }
 public function get_advertisements_data($lat='',$lang=''){
 	if(empty($lat && $lang)){
            $condition=array('status'=>'1','Advertisement_Type'=>'default');
	        $advertisement_data = $this->dynamic_model->getdatafromtable('manage_advertisement_images',$condition);
 	}else{
 	        $advertisement_data_location_wise=$this->db->query("SELECT Id, (
										    3959 * acos (
										      cos ( radians($lat) )
										      * cos( radians( Lat ) )
										      * cos( radians( Lang ) - radians($lang) )
										      + sin ( radians($lat) )
										      * sin( radians( Lat ) )
										    )
										  ) AS distance,Advertisement_Title,Advertisement_Subtitle,Advertisement_Image,Description,Status,Advertisement_Type
										 FROM manage_advertisement_images
										 Where status=1 AND Advertisement_Type='location_wise'
										 HAVING distance < 30
										 ORDER BY distance
										 LIMIT 0,20")->result_array();
 	        if(!empty($advertisement_data_location_wise)){
                  $advertisement_data=$advertisement_data_location_wise;
 	        }else{
               $condition=array('status'=>'1','Advertisement_Type'=>'default');
	           $advertisement_data = $this->dynamic_model->getdatafromtable('manage_advertisement_images',$condition);
 	        }
 	
   }
   return $advertisement_data;
 }
  
  
}