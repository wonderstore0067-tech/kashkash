<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dynamic_model extends CI_Model {

	public function __construct(){
		parent::__construct();
	}

	public function insertdata($tbnm = null,$var = array())
	{
		$this->db->insert($tbnm, $var);
		return $this->db->insert_id(); 
	}

	function deletedata($tbnm=null,$where = array())
	{
	   $this->db->where($where);
	   $this->db->delete($tbnm);
	   return $tbnm; 
    }

	public function updatedata($tbnm = null,$var = array(), $postid = null)
	{
		$this->db->where('id', $postid);
		$this->db->update($tbnm, $var);
		return $this->db->insert_id(); 
	}

	function updateRowWhere($table, $where = array(), $data = array())
	{
	    $this->db->where($where);
	    $this->db->update($table, $data);
	    $updated_status = $this->db->affected_rows();
 		return $updated_status;
	}

	public function add_user_meta($usid = 0,$key = null,$val = null)
	{
		$arg = array(
		    'user_id' => $usid,
		    'meta_key' => $key,
		    'meta_value' => $val
		);
		$this->db->insert('user_meta', $arg);
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
            $config['allowed_types'] = 'mp4|3gp|mpeg|jpg|jpeg|png|gif';
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
            	 //$res=$this->upload->display_errors();
            	//echo "<pre>";print_r($res);die;
                $picture = false;
            }
		}
		else
		{
		}
		return $picture;
	}

	public function multiple_fileupload($filenm, $foldername)
	{
    	$number_of_files = sizeof($_FILES["$filenm"]['tmp_name']);
    	$files = $_FILES["$filenm"];
    	$errors = array();
    	$upload_result = array();
 
	    for($i=0;$i<$number_of_files;$i++) {
	      if($_FILES["$filenm"]['error'][$i] != 0) $errors[$i][] = 'Couldn\'t upload file '.$_FILES["$filenm"]['name'][$i];
	    }
	    if(sizeof($errors)== 0) {
	      $this->load->library('upload');

	      $config['upload_path'] = './'.$foldername.'/';
	      $config['allowed_types'] = 'mp4|3gp|mpeg|jpg|jpeg|png|gif';
	      for ($i = 0; $i < $number_of_files; $i++) {
	        $_FILES['uploadedimage']['name'] = time().str_replace(str_split(' ()\\/,:*?"<>|'), '', 

    		$_FILES[$filenm]['name'])[$i];
	        $_FILES['uploadedimage']['type'] = $files['type'][$i];
	        $_FILES['uploadedimage']['tmp_name'] = $files['tmp_name'][$i];
	        $_FILES['uploadedimage']['error'] = $files['error'][$i];
	        $_FILES['uploadedimage']['size'] = $files['size'][$i];
	        $this->upload->initialize($config);
	        if ($this->upload->do_upload('uploadedimage'))  {
	          $data = $this->upload->data();
	          $upload_result[$i] = $data['file_name'];
	        } else {
	          $data['upload_errors'][$i] = $this->upload->display_errors();
	        }
	      }
	    } else {
	      $upload_result['error'] = $errors;
	    }
	    return $upload_result;
	}
    public function pdfFileUpload($filenm, $foldername)
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
            $config['allowed_types'] = 'pdf';
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
                $picture = false;
            }
		}
		else
		{
		}
		return $picture;
	}
	public function checkEmail($key)
	{
		$arg = array(
		    'Email' => $key
		);
		$query = $this->db->get_where("users", $arg);
		if($query->num_rows() != 0){
			return $query->result_array();
		} else {
			return false;
		}
	}

	// public function checkMobile($key)
	// {
	// 	$arg = array(
	// 	    'Mobile_No' => $key
	// 	);
	// 	$query = $this->db->get_where("Users", $arg);
	// 	if($query->num_rows() != 0){
	// 		return $query->result_array();
	// 	} else {
	// 		return false;
	// 	}
	// }

	public function get_user_by_id($usid = 0)
	{
		$key = array(
			'Id' => $usid
		);
		$query = $this->db->get_where('users', $key);
		return $query->row_array();
	}
	public function get_role_id($usid = 0)
	{
		$key = array(
			'User_Id' => $usid
		);
		$query = $this->db->get_where('user_in_roles',$key,'User_Id,Role_Id');
		return $query->row_array();
	}

	public function get_user($usid)
	{
		$data = $this->get_user_by_id($usid);
		return $data;
	}

	/*************** Get Table Data *******************/

	public function getdatafromtable($tbnm, $condition = array(), $data = "*", $limit="", $offset="", $orderby ='')
	{
		$this->db->select($data);
		$this->db->from($tbnm);
		$this->db->where($condition);
        $this->db->order_by("$orderby", "DESC");
		if($limit != '') {
			$this->db->limit($limit, $offset);
		}

		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	public function getdatafromtable_new($tbnm, $condition = array(), $data = "*", $limit="", $offset="", $orderby ='',$search_false ='' )
	{
		$this->db->select($data);
		$this->db->from($tbnm);
		if(!empty($condition)){
			foreach ($condition as $key => $value) {
				$this->db->where($key,$value, $search_false);
			}
		}
        $this->db->order_by("$orderby", "DESC");
		if($limit != '') {
			$this->db->limit($limit, $offset);
		}

		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}


	public function getdatafromtable_test($tbnm, $condition = array(), $data = "*", $limit="", $offset="", $orderby ='')
	{
		$this->db->select($data);
		$this->db->from($tbnm);
		$this->db->where($condition);
        $this->db->order_by("$orderby", "DESC");
		if($limit != '') {
			$this->db->limit($limit, $offset);
		}

		$query = $this->db->get();
		//echo $this->db->last_query();die;
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}


	


	public function getdatafromtable2($tbnm, $condition = array(), $data = "*", $limit="", $offset="",$groupBy="")
	{
		$this->db->select($data);
		$this->db->from($tbnm);
		$this->db->where($condition);
		If($limit != ''){
			$this->db->limit($limit, $offset);
		}
		If($groupBy != ''){
			$this->db->group_by($groupBy);
		}
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	/*************** Count *******************/	

	public function countdata($tablename,$condition)
	{
		$this->db->select('COUNT(*) as counting');
		$query=$this->db->from($tablename);
		$query=$this->db->where($condition);
		$query=$this->db->get();
		return $query->result_array();
	}

	/*************** Option Table data *******************/	

	public function getoptions($condition)
	{
		$this->db->select('option_value');
		$query=$this->db->from('options');
		$query=$this->db->where($condition);
		$query=$this->db->get();
		return $query->result_array();
	}

	/*************** update options *******************/	

	public function updateoptions($condition, $data)
	{
		$this->db->where($condition);
		$this->db->update('options', $data);
		return $this->db->insert_id(); 
	}

	/* GET THREE TABLE DATA  */

	/****************************************/

	public function getTwoTableData($data,$table1,$table2,$on,$condition = '')
	{
        $this->db->select($data);
        $this->db->from($table1);
        $this->db->join($table2,$on);
        if(!empty($condition))
        {
            $this->db->where($condition);
        }
	    $query=$this->db->get();
        return $query->result_array();
	}

	public function getJoinWithPaging($data,$table1,$table2,$on,$condition = '',$limit="", $offset="",$orderby='')
	{
        $this->db->select($data);
        $this->db->from($table1);
        $this->db->join($table2,$on);
        if(!empty($condition))
        {
            $this->db->where($condition);
        }
        if($orderby !=''){
           $this->db->order_by($orderby, "DESC");
        }
        if($limit != ''){
		  $this->db->limit($limit, $offset);
	    }
        $query=$this->db->get();
        return $query->result_array();
	}

	public function getThreeTableData($data,$table1,$table2,$table3,$on,$on2,$condition)
	{
        $this->db->select($data);
        $this->db->from($table1);
        $this->db->join($table2,$on);
        $this->db->join($table3,$on2);
        $this->db->where($condition);
        $query=$this->db->get();
        return $query->result_array();
	}

	/* Get search Query */

	function get_search($tbnm, $match) 
	{
		$this->db->like('company_nm','jain');
		$this->db->or_like('author','test');
		/*
		$this->db->or_like('author',$match);
		$this->db->or_like('characters',$match);
		$this->db->or_like('synopsis',$match);
		*/
		$query = $this->db->get($tbnm);
		return $query->result();
	}

	/* Get search Query */

	function filter($tbnm, $match, $column)
	{
		$this->db->like($column,$match);
		//$this->db->or_like('author','test');
		/*
		$this->db->or_like('author',$match);
		$this->db->or_like('characters',$match);
		$this->db->or_like('synopsis',$match);
		*/
		$query = $this->db->get($tbnm);
		return $query->result_array();
	}

	/**
	* Function for get row array from table
	* Parameters : 
	*	@table :
	*	@where :
	*	@select : Optional
	*/
	function get_row($table, $where, $select='')
	{
		if($select == '')
			$this->db->select('*');
		else
			$this->db->select($select);

		$this->db->from($table);
		$this->db->where($where);
		$query = $this->db->get();
		return $query->row_array();
	}

	/**
	* Function for get result array from table
	* Parameters : 
	*	@table :
	*	@where :
	*	@select : Optional
	*/
	function get_rows($table, $where, $select='', $order_by='', $order='')
	{
		if($select == '')
			$this->db->select('*');
		else
			$this->db->select($select);

		$this->db->from($table);
		$this->db->where($where);

		if($order_by != '' && $order != '')
			$this->db->order_by($order_by, $order);

		$query = $this->db->get();
		return $query->result_array();
	}

	//function for check Phone or Email credentials
	function check_userdetails($email)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('Email', $email);
		$this->db->or_where('Mobile_No', $email);
		$query=$this->db->get(); 
		return $query->row_array();
	}

	//function for check Phone or Email credentials
	function check_nonreguserdetails($email)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('Mobile_No', $email);
		$this->db->where('Is_Virtual', 1);
		$query=$this->db->get(); 
		return $query->row_array();
	}

	//function for check card details 
	function check_card_details($id)
	{
		$this->db->select('*');
		$this->db->from('user_payment_methods');
		//$this->db->where('Is_Debit_Card', 1);
		//$this->db->or_where('Is_Credit_Card', 1);
		$this->db->where("(Is_Debit_Card = '1' OR Is_Credit_Card = '1')", NULL, FALSE);
		$this->db->where('User_Id',$id);
		$this->db->where('Is_Deleted',0);
		$this->db->order_by("Id", "DESC");
		$query=$this->db->get(); 
		return $query->result_array();
	}

	//function for check payment request type
	function check_request_type($id,$status_id)
	{
		$this->db->select('*');
		$this->db->from('Request_Share');
		$this->db->where("(Type = 'Req_money' OR Type = 'Sharebill_req')", NULL, FALSE);
		$this->db->where('To_User_Id', $id);
		$this->db->where('Tran_Status_Id', $status_id);
		$this->db->order_by("Id", "DESC");
		$query=$this->db->get(); 
		return $query->result_array();
	}

	//function for check Phone or Email credentials
	function checkMobile($key)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('Email', $key);
		$this->db->or_where('Mobile_No', $key);
		$query=$this->db->get(); 
		if($query->num_rows() != 0){
			return $query->result_array();
		} else {
			return false;
		}
	}
	//function for check Transaction Pin
	function checkTransactionPin($userid='',$pin=''){
	  $this->db->select('Id');
      $this->db->from('users');     
      $this->db->where('Id',$userid); 
      $this->db->where('Transaction_Password',encrypt_password($pin)); 
      $query= $this->db->get();
      
      if($query->num_rows()> 0){
			return true;
		}else{
			return false;
		}
    }

    	/* select query */
    function select($table, $where ='',$coloumn = '*'){
        $sql = "SELECT $coloumn FROM $table";
		if(!empty($where)){
		   $sql .= " $where";
		}
		//echo $sql; die;
		$query = $this->db->query($sql);
        if($query->num_rows()>0){
            return $query->result_array();
        }else{
            return false;
        }
    }

    //function for check Phone or Email credentials
	function checkForgotMobile($id,$key)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('Email', $key);
		$this->db->or_where('Mobile_No', $key);
		$this->db->where('Country_ID', $id);
		$query=$this->db->get(); 
		if($query->num_rows() != 0){
			return $query->result_array();
		} else {
			return false;
		}
	}

	public function get_chat($sender_id, $receiver_id)
	{
		$this->db->select('*');
		$this->db->from('chat');
		$this->db->where("(sender_id = $sender_id AND receiver_id = $receiver_id) OR (sender_id = $receiver_id AND receiver_id = $sender_id)");
		$this->db->limit(1);
		$query = $this->db->get();
        return $query->result();
	}

	// public function get_chats($user_id)
	// {
	// 	$user_id = (int)$user_id;  // Sanitize input
		
	// 	$subquery = $this->db->select('chat_id, MAX(id) as last_message_id')
	// 						->from('messages')
	// 						->group_by('chat_id')
	// 						->get_compiled_select();
		
	// 	$this->db->select('c.id, c.sender_id, c.receiver_id, c.created_at, m.message as last_message, m.created_at as last_message_time, u_receiver.FullName as receiver_name');
	// 	$this->db->from('chat c');
	// 	$this->db->join("($subquery) lm", 'c.id = lm.chat_id', 'left');
	// 	$this->db->join('messages m', 'm.id = lm.last_message_id', 'left');
	// 	$this->db->join('users u_receiver', 'u_receiver.id = c.receiver_id', 'left'); // Join with users table for receiver name
	// 	$this->db->where("(c.sender_id = $user_id OR c.receiver_id = $user_id)");
	// 	$query = $this->db->get();
		
	// 	return $query->result();
	// }



	public function get_chats($user_id)
	{
		$user_id = (int)$user_id;

		// Subquery to get the last message in each chat
		$subquery_last_message = $this->db->select('chat_id, MAX(id) as last_message_id')
										->from('messages')
										->group_by('chat_id')
										->get_compiled_select();

		// Subquery to count unread messages in each chat for the given user
		$subquery_unread_count = $this->db->select('chat_id, COUNT(id) as unread_count')
										->from('messages')
										->where('is_read', 0)
										->where('receiver_id', $user_id)
										->group_by('chat_id')
										->get_compiled_select();

		// Main query to get chat information
		$this->db->select('c.id, c.sender_id, c.receiver_id, c.created_at, m.message as last_message, m.created_at as last_message_time, u_receiver.FullName as receiver_name, u_sender.FullName as sender_name, COALESCE(uc.unread_count, 0) as unread_count');
		$this->db->from('chat c');
		$this->db->join("($subquery_last_message) lm", 'c.id = lm.chat_id', 'left');
		$this->db->join('messages m', 'm.id = lm.last_message_id', 'left');
		$this->db->join('users u_receiver', 'u_receiver.id = c.receiver_id', 'left'); // Join with users table for receiver name
		$this->db->join('users u_sender', 'u_sender.id = c.sender_id', 'left'); // Join with users table for receiver name
		$this->db->join("($subquery_unread_count) uc", 'c.id = uc.chat_id', 'left'); // Join with unread messages count subquery
		$this->db->where("(c.sender_id = $user_id OR c.receiver_id = $user_id)");
		$query = $this->db->get();
		$result = $query->result_array();

		// Update unread messages to read
		$this->db->where('is_read', 0)
				->where('receiver_id', $user_id)
				->update('messages', ['is_read' => 1]);

		return $result;
	}

	public function get_messages($chat_id)
    {
        $this->db->select('*');
        $this->db->from('messages');
        $this->db->where('chat_id', $chat_id);
        $query = $this->db->get();
        return $query->result();
    }

	// function get_all_users($table, $where ='',$coloumn = '*', $excluded_columns = []){
	// 	$fields = $this->db->list_fields('users');
	// 	$selected_fields = array_diff($fields, $excluded_columns);
	// 	$select_columns = implode(',', $selected_fields);

	// 	$this->db->select($select_columns);
	// 	$this->db->from('users');
	// 	$query = $this->db->get();
	// 	return $query->result();
    // }


	function get_all_users($table, $where ='',$coloumn = '*', $excluded_columns = [])
	{
		$fields = $this->db->list_fields('users');
		$selected_fields = array_diff($fields, $excluded_columns);
		$select_columns = [];
		foreach ($selected_fields as $field) {
			$select_columns[] = 'users.' . $field;
		}

		$this->db->select(implode(',', $select_columns));
		$this->db->from('users');
		$this->db->join(
			'user_in_roles',
			'user_in_roles.User_Id = users.Id',
			'left'
		);

		$this->db->where_not_in('user_in_roles.Role_Id', [1, 5]);
		$this->db->where('users.Id !=', $where);
		$this->db->group_by('users.Id');
		$query = $this->db->get();
		return $query->result();
	}


	public function get_payment_method()
    {
        $this->db->select('*');
        $this->db->from('payout_method');
        // $this->db->where('chat_id', $chat_id);
        $query = $this->db->get();
        return $query->result();
    }

	public function get_recipients()
    {
        $this->db->select('*');
        $this->db->from('recipients');
        // $this->db->where('chat_id', $chat_id);
        $query = $this->db->get();
        return $query->result();
    }
	public function get_receiver_by_id($usid = 0)
	{
		$key = array(
			'Id' => $usid
		);
		$query = $this->db->get_where('recipients', $key);
		return $query->row_array();
	}
}