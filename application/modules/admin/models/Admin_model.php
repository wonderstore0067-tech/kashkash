<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Admin_model extends CI_Model {
	public function __construct(){
		parent::__construct();
	}

	public function login($logdata){
		$condition = "Email =" . "'" . $logdata['username'] . "'";
		$this->db->select('*');
		$this->db->from('Users');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			return $query->row_array();
		} else {
			return false;
		}
	}

	public function deletesubadmin($id){
      	$this->db->delete('Users', array('id' => $id));
      	return true;
	}

	public function add_staff($adminid=''){
	 $this->db->select('Users.FirstName,Users.LastName,Users.Mobile_No,Users.Email,Users.Creation_Date_Time,Users.Is_Active,Users.Current_Wallet_Balance,Users.Created_By,User_In_Roles.User_Id,User_In_Roles.Role_Id');
      $this->db->from('Users');
      $this->db->join('User_In_Roles','Users.Id=User_In_Roles.User_Id');
      $this->db->where('Users.Created_By', $adminid); 
      
      $query= $this->db->get();
      return $query->result_array(); 
	}
	public function add_staff_by_id($adminid='',$satff_id=''){
	 $this->db->select('Users.Id as userid,Users.FirstName,Users.LastName,Users.Mobile_No,Users.Password,Users.Email,Users.Creation_Date_Time,Users.Is_Active,Users.Current_Wallet_Balance,Users.Created_By,User_In_Roles.User_Id,User_In_Roles.Role_Id');
      $this->db->from('Users');
      $this->db->join('User_In_Roles','Users.Id=User_In_Roles.User_Id');
      $this->db->where('Users.Id', $satff_id); 
      $this->db->where('Users.Created_By', $adminid); 
      $query= $this->db->get();

      return $query->result_array(); 
	}

	public function get_role_data(){
            $this->db->where_not_in('Id',array(1,2,3));
			$query = $this->db->get('Roles')->result_array();
			return $query;
	}
    public function fileupload($filenm, $foldername)
	{
		if(!empty($_FILES[$filenm]['name']))
		{
			$new_image_name = time().str_replace(str_split(' ()\\/,:*?"<>|'), '', 

    		$_FILES[$filenm]['name']);
		 	$config['upload_path'] = './'.$foldername.'/';
            $config['file_name'] = $new_image_name;
		    $config['overwrite'] = TRUE;
		    $config['max_width']  = '0';
		    $config['max_height']  = '0';
            $config['allowed_types'] = 'ico|jpg|jpeg|png|gif';
            $this->load->library('upload',$config);
            $this->upload->initialize($config);
            if($this->upload->do_upload($filenm)){
                
                $uploadData = $this->upload->data();
                $config['image_library'] = 'gd2'; 
                $config['source_image'] = $uploadData['full_path'];
				$config['create_thumb'] = TRUE;
				$config['maintain_ratio'] = TRUE;
				$config['width']         = 300;
				$config['height']       = 300;
                $this->load->library('image_lib', $config);
                if (!$this->image_lib->resize()) {  
                }
                $picture = $uploadData['file_name'];
            }else{
                //$error = array('error' => $this->upload->display_errors());
                //print_r($error);die;
                $picture = false;
            }
		}
		else
		{
		}
		return $picture;
	}
	public function search_users($search_item=''){
     
	  // $this->db->select('Users.FullName,Users.Mobile_No,Users.Creation_Date_Time,Users.Is_Active,Users.Current_Wallet_Balance,User_In_Roles.User_Id,User_In_Roles.Role_Id');
   //    $this->db->from('Users');
   //    $this->db->join('User_In_Roles','Users.Id=User_In_Roles.User_Id');
   //     $this->db->where('User_In_Roles.Role_Id ',2);
   //    $this->db->or_where('User_In_Roles.Role_Id ', 3);
   //    $this->db->like('FullName', "%".$search_item."%");
     
   //    $this->db->order_by('Users.Id DESC');
   //    $query= $this->db->get();

      $result = $this->db->query("SELECT `Users`.`FirstName`,`Users`.`LastName`, `Users`.`Mobile_No`, `Users`.`Creation_Date_Time`, `Users`.`Is_Active`, `Users`.`Current_Wallet_Balance`, `User_In_Roles`.`User_Id`, `User_In_Roles`.`Role_Id` FROM `Users` JOIN `User_In_Roles` ON `Users`.`Id`=`User_In_Roles`.`User_Id` WHERE  (FirstName LIKE '%$search_item%' AND `User_In_Roles`.`Role_Id` = 2) OR  (LastName LIKE '%$search_item%' AND `User_In_Roles`.`Role_Id` = 3) ORDER BY `Users`.`Id` DESC ")->result_array();

      //echo $this->db->last_query();die;
      return $result; 

	}

    public function allNotificationMails($isCount='', $start = 0,$stop = 0, $column_name = '',$order = 'desc'){
      if(!empty($column_name) && $column_name=='Advertisement_Title' ){
        $orderby_name = 'subject';
      }else{
        $orderby_name='created_dt';
       } 
      $search = $this->input->get('search');
      $this->db->select('*');
      $this->db->from('manage_notification_mail');
     
       // search condition
      if(!empty($search['value'])){
        $search_value = trim($search['value']);
        $this->db->where('(`manage_notification_mail.subject` LIKE "%'.$search_value.'%" OR  `manage_notification_mail.description` LIKE "%'.$search_value.'%")',NUll);
      }        
      if($stop!=0){ 
        $this->db->limit($stop,$start);
      }
      $this->db->order_by($orderby_name,$order);
      $query=$this->db->get(); 
      
      if($isCount){
            $returnData = $query->num_rows();
      }else{
          $returnData = $query->result();
          //echo $this->db->last_query();die;
      }
      return $returnData;
    } 

}