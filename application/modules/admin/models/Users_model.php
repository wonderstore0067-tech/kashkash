<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class users_model extends CI_Model {
	public function __construct(){
		parent::__construct();
	}

  public function allsendersajaxlist($isCount='', $start = 0,$stop = 0, $column_name = '',$order = 'desc') {
      if(!empty($column_name) && $column_name=='FullName' ){
        $orderby_name = 'FullName';
      }else if(!empty($column_name) && $column_name=='Email' ){
        $orderby_name = 'Email';
      }else if(!empty($column_name) && $column_name=='Mobile_No' ){
        $orderby_name = 'Mobile_No';
      }else if(!empty($column_name) && $column_name=='Current_Wallet_Balance' ){
        $orderby_name = 'Current_Wallet_Balance';
      }else if(!empty($column_name) && $column_name=='Total_Referral_Points' ){
        $orderby_name = 'Total_Referral_Points';
      }else if(!empty($column_name) && $column_name=='etippers_id' ){
        $orderby_name = 'etippers_id';
      }else if(!empty($column_name) && $column_name=='Creation_Date_Time' ){
        $orderby_name = 'Creation_Date_Time';
      }else{
        $orderby_name='Creation_Date_Time';
       } 
       $search = $this->input->get('search');
       $this->db->select('*');
       $this->db->from('users');
       $this->db->join('user_in_roles','users.Id=user_in_roles.User_Id');
       $this->db->where('user_in_roles.Role_Id', '2');
       //search data
        $searchdata = $this->session->userdata('senderdata');
        if(isset($searchdata) && $searchdata['user_status']=='1'){
          $this->db->where('users.Is_Active','1');
        }elseif(isset($searchdata) && $searchdata['user_status']=='0'){
          $this->db->where('users.Is_Active','0');
        }
      // search condition
      if(!empty($search['value'])){
        $search_value = trim($search['value']);
        $this->db->where('(`users.FullName` LIKE "%'.$search_value.'%" OR `users.Email` LIKE "%'.$search_value.'%" OR `users.Mobile_No` LIKE "%'.$search_value.'%" OR `users.etippers_id` LIKE "%'.$search_value.'%")',NUll);
      }        
      if($stop!=0){ 
        $this->db->limit($stop,$start);
      }
      $this->db->order_by('users.'.$orderby_name,$order);
      //$this->db->order_by('users.Total_Referral_Points',$order);
      $query=$this->db->get(); 
      
      if($isCount){
            $returnData = $query->num_rows();
      }else{
          $returnData = $query->result();
          //echo $this->db->last_query();die;
      }
      return $returnData;
    }
   public function allreceiversajaxlist($isCount='', $start = 0,$stop = 0, $column_name = '',$order = 'desc'){
       if(!empty($column_name) && $column_name=='FullName' ){
        $orderby_name = 'FullName';
      }else if(!empty($column_name) && $column_name=='Email' ){
        $orderby_name = 'Email';
      }else if(!empty($column_name) && $column_name=='Mobile_No' ){
        $orderby_name = 'Mobile_No';
      }else if(!empty($column_name) && $column_name=='Current_Wallet_Balance' ){
        $orderby_name = 'Current_Wallet_Balance';
      }else if(!empty($column_name) && $column_name=='Total_Referral_Points' ){
        $orderby_name = 'Total_Referral_Points';
      }else if(!empty($column_name) && $column_name=='etippers_id' ){
        $orderby_name = 'etippers_id';
      }else if(!empty($column_name) && $column_name=='Creation_Date_Time' ){
        $orderby_name = 'Creation_Date_Time';
      }else{
        $orderby_name='Creation_Date_Time';
       } 
      $search = $this->input->get('search');
      $this->db->select('*');
      $this->db->from('users');
      $this->db->join('user_in_roles','users.Id=user_in_roles.User_Id');
      $this->db->where('user_in_roles.Role_Id','3');  
       //search data
        $searchdata = $this->session->userdata('receiverdata');
        if(isset($searchdata) && $searchdata['user_status']=='1'){
          $this->db->where('users.Is_Active','1');
        }elseif(isset($searchdata) && $searchdata['user_status']=='0'){
          $this->db->where('users.Is_Active','0');
        }     
      // search condition
      if(!empty($search['value'])){
        $search_value = trim($search['value']);
        $this->db->where('(`users.FullName` LIKE "%'.$search_value.'%" OR `users.Email` LIKE "%'.$search_value.'%" OR `users.Mobile_No` LIKE "%'.$search_value.'%" OR `users.etippers_id` LIKE "%'.$search_value.'%" )',NUll);
      }        
      if($stop!=0){ 
        $this->db->limit($stop,$start);
      }
      $this->db->order_by('users.'.$orderby_name,$order);
      $query=$this->db->get(); 
      
      if($isCount){
            $returnData = $query->num_rows();
      }else{
          $returnData = $query->result();
          //echo $this->db->last_query();die;
      }
      return $returnData;
    }
  // public function transactionajaxlist($isCount=false, $start = 0,$stop = 0, $column_name = '',$order = 'desc'){
  //     if(!empty($column_name) && $column_name=='FullName' ){
  //       $orderby_name = 'FullName';
  //     }else if(!empty($column_name) && $column_name=='Mobile_No' ){
  //       $orderby_name = 'Mobile_No';
  //     }else if(!empty($column_name) && $column_name=='amount' ){
  //       $orderby_name = 'Amount';
  //     }else if(!empty($column_name) && $column_name=='transaction_id' ){
  //       $orderby_name = 'transaction_id';
  //     }else if(!empty($column_name) && $column_name=='created_at' ){
  //       $orderby_name = 'created_at';
  //     }else if(!empty($column_name) && $column_name=='status' ){
  //       $orderby_name = 'status';
  //     }else{
  //       $orderby_name='created_at';
  //     }
  //     $search = $this->input->get('search');

  //     $this->db->select('
  //         receiver.FirstName AS receiver_first_name,
  //         receiver.LastName AS receiver_last_name,
  //         receiver.Mobile_No AS receiver_mobile,
  //         receiver.Email AS receiver_email,
  //         receiver.account_id AS receiver_account_id,
  //         receiver.Address AS receiver_address,
  //         sender.FirstName AS sender_first_name,
  //         sender.LastName AS sender_last_name,
  //         sender.Mobile_No AS Mobile_No,
  //         hedera_transactions.*
  //     ');
  //     $this->db->from('hedera_transactions');
  //     $this->db->join('users as receiver', 'receiver.Id = hedera_transactions.receiver_id', 'left');
  //     $this->db->join('users as sender', 'sender.Id = hedera_transactions.sender_id', 'left');
  //     $this->db->where('hedera_transactions.status', 'SUCCESS');
  //     // $this->db->select('users.FirstName,users.LastName,users.Mobile_No,hedera_transactions.*');
  //     // $this->db->from('hedera_transactions');
  //     // $this->db->join('users','users.Id=hedera_transactions.receiver_id');
  //     // $this->db->where('hedera_transactions.status', 'SUCCESS');
    
  //     //search data
  //     $searchTrx = $this->session->userdata('searchdata');;
  //     if(!empty($searchTrx)){ 
  //     $fdate    =  explode('-',trim($searchTrx['trxDate'])); 
  //     @$fromdate = date('Y-m-d',strtotime($fdate[0]));
  //     @$todate   = date('Y-m-d',strtotime($fdate[1]));
  //     $this->db->where('(DATE_FORMAT(hedera_transactions.created_at,"%Y-%m-%d") >= "'.$fromdate.'" AND DATE_FORMAT(hedera_transactions.created_at,"%Y-%m-%d") <= "'.$todate.'")');
  //     }      
  //     // search condition
  //     if(!empty($search['value'])){
  //       $search_value = trim($search['value']);
  //        $this->db->where('(`users.FullName` LIKE "%'.$search_value.'%" OR `users.FirstName` LIKE "%'.$search_value.'%" OR `users.LastName` LIKE "%'.$search_value.'%" OR `users.Email` LIKE "%'.$search_value.'%" OR `users.Mobile_No` LIKE "%'.$search_value.'%" OR `hedera_transactions.transaction_id` LIKE "%'.$search_value.'%")',NUll);
  //     }        
  //     if($stop!=0) { 
  //       $this->db->limit($stop,$start);
  //     }
  //     $this->db->order_by($orderby_name,$order);
  //     $query=$this->db->get(); 
      
  //     if($isCount){
  //           $returnData = $query->num_rows();
  //     }else{
  //         $returnData = $query->result();
  //     }
  //     return $returnData;

  //   }


  public function transactionajaxlist($isCount=false, $start = 0, $stop = 0, $column_name = '', $order = 'desc')
  {
      switch ($column_name) {
          case 'FullName':        $orderby_name = 'sender.FirstName'; break;
          case 'Mobile_No':       $orderby_name = 'sender.Mobile_No'; break;
          case 'amount':          $orderby_name = 'hedera_transactions.amount'; break;
          case 'transaction_id':  $orderby_name = 'hedera_transactions.transaction_id'; break;
          case 'created_at':      $orderby_name = 'hedera_transactions.created_at'; break;
          case 'status':          $orderby_name = 'hedera_transactions.status'; break;
          default:                $orderby_name = 'hedera_transactions.created_at';
      }

      $search = $this->input->get('search');

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
          sender.Mobile_No AS Mobile_No,
          hedera_transactions.*
      ');
      $this->db->from('hedera_transactions');
      $this->db->join('recipients as receiver', 'receiver.id = hedera_transactions.receiver_id', 'left');
      $this->db->join('users as sender',   'sender.Id = hedera_transactions.sender_id', 'left');
      // $this->db->where('hedera_transactions.status', 'SUCCESS');

      // SEARCH FIX
      if (!empty($search['value'])) {
          $search_value = trim($search['value']);
          
          $this->db->group_start();
          $this->db->like('sender.FirstName', $search_value);
          $this->db->or_like('sender.LastName', $search_value);
          // $this->db->or_like('sender.Mobile_No', $search_value);


          $this->db->or_like('receiver.account_holder_name', $search_value);
          $this->db->or_like('receiver.phone_number', $search_value);
          $this->db->or_like('receiver.email', $search_value);
          // $this->db->or_like('receiver.account_id', $search_value);
          $this->db->or_like('receiver.city', $search_value);
          $this->db->or_like('receiver.country', $search_value);
          $this->db->or_like('receiver.full_address', $search_value);

          $this->db->or_like('hedera_transactions.transaction_id', $search_value);
          $this->db->or_like('hedera_transactions.amount', $search_value);
          $this->db->or_like('hedera_transactions.status', $search_value);
          $this->db->or_like('hedera_transactions.created_at', $search_value);
          $this->db->group_end();
      }

      // LIMIT
      if($stop != 0) {
          $this->db->limit($stop, $start);
      }

      // ORDER BY
      $this->db->order_by($orderby_name, $order);

      $query = $this->db->get();

      return $isCount ? $query->num_rows() : $query->result();
  }

   
    public function withdraw_history_ajaxlist($isCount=false, $start = 0,$stop = 0, $column_name = '',$order = 'desc') {
      if(!empty($column_name) && $column_name=='FirstName' ){
        $orderby_name = 'FirstName';
      }else if(!empty($column_name) && $column_name=='Mobile_No' ){
        $orderby_name = 'Mobile_No';
      }else if(!empty($column_name) && $column_name=='Amount' ){
        $orderby_name = 'Amount';
      }else if(!empty($column_name) && $column_name=='Charge' ){
        $orderby_name = 'Charge';
      }else if(!empty($column_name) && $column_name=='Creation_Date_Time' ){
        $orderby_name = 'Creation_Date_Time';
      }else if(!empty($column_name) && $column_name=='Third_Party_Tran_Id' ){
        $orderby_name = 'Third_Party_Tran_Id';
      }else{
                $orderby_name='Creation_Date_Time';
            }
      $search = $this->input->get('search');

      $this->db->select('users.FirstName,users.LastName,users.Mobile_No,transactions.*');
      $this->db->from('transactions');
      $this->db->join('users','users.Id=transactions.To_User_Id');
      $this->db->where('transactions.Tran_Type_Id', '1');
      
        // search condition
      if(!empty($search['value'])){
        $search_value = trim($search['value']);
        $this->db->where('(`users.FirstName` LIKE "%'.$search_value.'%" OR `users.LastName` LIKE "%'.$search_value.'%" OR `users.Email` LIKE "%'.$search_value.'%" OR `users.Mobile_No` LIKE "%'.$search_value.'%" OR `transactions.Third_Party_Tran_Id` LIKE "%'.$search_value.'%")',NUll);
      }        
      if($stop!=0) { 
        $this->db->limit($stop,$start);
      }
      $this->db->order_by($orderby_name,$order);
      $query=$this->db->get(); 
      
      if($isCount){
            $returnData = $query->num_rows();
      }else{
          $returnData = $query->result(); 
      }  
      return $returnData;
        
    }
    public function deposit_history_ajaxlist($isCount=false, $start = 0,$stop = 0, $column_name = '',$order = 'desc') {
      if(!empty($column_name) && $column_name=='FirstName' ){
        $orderby_name = 'FirstName';
      }else if(!empty($column_name) && $column_name=='Mobile_No' ){
        $orderby_name = 'Mobile_No';
      }else if(!empty($column_name) && $column_name=='Amount' ){
        $orderby_name = 'Amount';
      }else if(!empty($column_name) && $column_name=='Charge' ){
        $orderby_name = 'Charge';
      }else if(!empty($column_name) && $column_name=='Creation_Date_Time' ){
        $orderby_name = 'Creation_Date_Time';
      }else if(!empty($column_name) && $column_name=='Third_Party_Tran_Id' ){
        $orderby_name = 'Third_Party_Tran_Id';
      }else{
                $orderby_name='Creation_Date_Time';
      }
      $search = $this->input->get('search');

      $this->db->select('users.FirstName,users.LastName,users.Mobile_No,transactions.*');
      $this->db->from('transactions');
      $this->db->join('users','users.Id=transactions.To_User_Id');
      $this->db->where('transactions.Tran_Type_Id', '2');

      // search condition
      if(!empty($search['value'])){
        $search_value = trim($search['value']);
          $this->db->where('(`users.FirstName` LIKE "%'.$search_value.'%" OR `users.LastName` LIKE "%'.$search_value.'%" OR `users.Email` LIKE "%'.$search_value.'%" OR `users.Mobile_No` LIKE "%'.$search_value.'%" OR `transactions.Third_Party_Tran_Id` LIKE "%'.$search_value.'%")',NUll);
      }        
      if($stop!=0) { 
        $this->db->limit($stop,$start);
      }
      $this->db->order_by($orderby_name,$order);
      $query=$this->db->get(); 
      
      if($isCount){
            $returnData = $query->num_rows();
      }else{
          $returnData = $query->result();
      }

      return $returnData;
        
    }
    public function send_money_ajaxlist($isCount=false, $start = 0,$stop = 0, $column_name = '',$order = 'desc') {
      if(!empty($column_name) && $column_name=='FullName' ){
        $orderby_name = 'a.FullName';
      }else if(!empty($column_name) && $column_name=='fullname' ){
        $orderby_name = 'b.fullname';
      }else if(!empty($column_name) && $column_name=='Amount' ){
        $orderby_name = 'Amount';
      }else if(!empty($column_name) && $column_name=='Charge' ){
        $orderby_name = 'Charge';
      }else if(!empty($column_name) && $column_name=='Third_Party_Tran_Id' ){
        $orderby_name = 'Third_Party_Tran_Id';
      }else{
        $orderby_name='Creation_Date_Time';
       }
      $search = $this->input->get('search');

      //$this->db->select('users.FirstName,users.LastName,users.FullName,users.Mobile_No,transactions.*');
      $this->db->select('a.FirstName,a.LastName,a.FullName,a.Mobile_No,b.FirstName as firstname,b.LastName as lastname,b.FullName as fullname,b.Mobile_No as mobile_no,transactions.*');
      $this->db->from('transactions');
      $this->db->join('users a','a.Id=transactions.To_User_Id');
      $this->db->join('users b','b.Id=transactions.From_User_Id','left');
      $this->db->where('transactions.Tran_Type_Id', '3');
      // search condition
      if(!empty($search['value'])){
        $search_value = trim($search['value']);
         $this->db->where('(`a.FirstName` LIKE "%'.$search_value.'%" OR `a.LastName` LIKE "%'.$search_value.'%" OR `a.FullName` LIKE "%'.$search_value.'%"  OR `a.Mobile_No` LIKE "%'.$search_value.'%" OR `b.firstname` LIKE "%'.$search_value.'%" OR `b.lastname` LIKE "%'.$search_value.'%" OR `b.fullname` LIKE "%'.$search_value.'%" OR `b.mobile_no` LIKE "%'.$search_value.'%" OR `transactions.Third_Party_Tran_Id` LIKE "%'.$search_value.'%")',NUll);
      }        
      if($stop!=0) { 
        $this->db->limit($stop,$start);
      }
       $this->db->order_by($orderby_name,$order);
      $query=$this->db->get(); 
      if($isCount){
            $returnData = $query->num_rows();
      }else{
          $returnData = $query->result();
      }
      return $returnData;   
    }
     public function usertransactionajaxlist($isCount=false, $start = 0,$stop = 0, $column_name = '',$order = 'desc',$userid ='',$trxtype =''){
      if(!empty($column_name) && $column_name=='FirstName' ){
        $orderby_name = 'FirstName';
      }else if(!empty($column_name) && $column_name=='Mobile_No' ){
        $orderby_name = 'Mobile_No';
      }else if(!empty($column_name) && $column_name=='Amount' ){
        $orderby_name = 'Amount';
      }else if(!empty($column_name) && $column_name=='Charge' ){
        $orderby_name = 'Charge';
      }else if(!empty($column_name) && $column_name=='Creation_Date_Time' ){
        $orderby_name = 'Creation_Date_Time';
      }else if(!empty($column_name) && $column_name=='Third_Party_Tran_Id' ){
        $orderby_name = 'Third_Party_Tran_Id';
      }else{
        $orderby_name='transactions.Creation_Date_Time';
      }
      $search = $this->input->get('search');

      $this->db->select('users.FirstName,users.LastName,users.Mobile_No,transactions.*');
      $this->db->from('transactions');
      $this->db->join('users','users.Id=transactions.To_User_Id');
      
     if($trxtype == 'deposit'){
         $this->db->where('transactions.Tran_Type_Id', 2);
      }elseif($trxtype == 'withdraw'){
          $this->db->where('transactions.Tran_Type_Id',1);
      }else{ 
       }
         $this->db->where('transactions.To_User_Id', $userid);
         $this->db->where('transactions.Tran_Status_Id', '6');
     
        // search condition
      if(!empty($search['value'])){
        $search_value = trim($search['value']);
        $this->db->where('(`users.FirstName` LIKE "%'.$search_value.'%" OR `users.LastName` LIKE "%'.$search_value.'%" OR `users.Mobile_No` LIKE "%'.$search_value.'%" OR `transactions.Third_Party_Tran_Id` LIKE "%'.$search_value.'%")',NUll);
      }        
      if($stop!=0) { 
        $this->db->limit($stop,$start);
      }
      //$this->db->order_by("transactions.Creation_Date_Time", "desc");
      if(!empty($order)){   
      $this->db->order_by($orderby_name, $order);
      }
      $query=$this->db->get(); 
      
      if($isCount){
            $returnData = $query->num_rows();
      }else{
          $returnData = $query->result();
         // echo $this->db->last_query();die;
      }
      return $returnData;  
    }
    public function userloginajaxlist($isCount=false, $start = 0,$stop = 0, $column_name = '',$order = 'desc',$userid='') {
      if(!empty($column_name) && $column_name=='FirstName' ){
        $orderby_name = 'FirstName';
      } else if(!empty($column_name) && $column_name=='Ip_Address' ){
        $orderby_name = 'Ip_Address';
      }else if(!empty($column_name) && $column_name=='Location' ){
        $orderby_name = 'Location';
      }else{
                $orderby_name='User_Logins.Creation_Date_Time';
            }
      
      $search = $this->input->get('search');
      $this->db->select('users.FirstName,users.LastName,users.Mobile_No,User_Logins.*');
      $this->db->from('users');
      $this->db->join('User_Logins','users.Id=User_Logins.User_Id');
      $this->db->where('User_Logins.User_Id',$userid);
    
        // search condition
      if(!empty($search['value'])){
        $search_value = trim($search['value']);
        $this->db->where('(`users.FirstName` LIKE "%'.$search_value.'%" OR `users.LastName` LIKE "%'.$search_value.'%")',NUll);
      }        
      if($stop!=0) { 
        $this->db->limit($stop,$start);
      }
      //$this->db->order_by("User_Logins.Creation_Date_Time", "desc"); 
      $this->db->order_by($orderby_name, $order);
      $query=$this->db->get(); 
      
      if($isCount){
            $returnData = $query->num_rows();
      }else{
          $returnData = $query->result();
         // echo $this->db->last_query();die;
      }
      return $returnData;
    }

    public function allqrcodesajaxlist($isCount=false, $start = 0,$stop = 0, $column_name = '',$order = 'desc'){
      if(!empty($column_name) && $column_name=='FirstName' ){
        $orderby_name = 'FirstName';
      }else if(!empty($column_name) && $column_name=='Mobile_No' ){
        $orderby_name = 'Mobile_No';
      }else if(!empty($column_name) && $column_name=='Role_Id' ){
        $orderby_name = 'Role_Id';
      }else if(!empty($column_name) && $column_name=='Creation_Date_Time' ){
        $orderby_name = 'Creation_Date_Time';
      }
      else if(!empty($column_name) && $column_name=='QR_Code' ){
        $orderby_name = 'QR_Code';
      }else{
        $orderby_name='Creation_Date_Time';
      }
      $search = $this->input->get('search');

      $this->db->select('users.FirstName,users.LastName,users.Mobile_No,user_in_roles.User_Id,user_in_roles.QR_Code,user_in_roles.QR_Code_Img_Path,user_in_roles.Role_Id,user_in_roles.QR_Status,user_in_roles.Creation_Date_Time');
      $this->db->from('user_in_roles');
      $this->db->join('users','users.Id=user_in_roles.User_Id');
      //$this->db->where_in('user_in_roles.Role_Id',3);
      $this->db->where('user_in_roles.Role_Id !=',1);
      
        // search condition
      if(!empty($search['value'])){
        $search_value = trim($search['value']);
         $this->db->where('(`users.FirstName` LIKE "%'.$search_value.'%" OR `users.LastName` LIKE "%'.$search_value.'%" OR `users.FullName` LIKE "%'.$search_value.'%"  OR  `users.Email` LIKE "%'.$search_value.'%" OR `users.Mobile_No` LIKE "%'.$search_value.'%"  OR `user_in_roles.QR_Code` LIKE "%'.$search_value.'%")',NUll);
      }        
      if($stop!=0) { 
        $this->db->limit($stop,$start);
      }
      
      //$this->db->order_by("users.Creation_Date_Time", "desc"); 
      $this->db->order_by($orderby_name,$order);
      $query=$this->db->get(); 
      //echo $this->db->last_query();die;
      if($isCount){
            $returnData = $query->num_rows();
      }else{
          $returnData = $query->result();
        
      }
      
      return $returnData;
        
    }
    public function allfeedbackajaxlist($isCount=false, $start = 0,$stop = 0, $column_name = '',$order = 'desc'){
      if(!empty($column_name) && $column_name=='FirstName' ){
        $orderby_name = 'FirstName';
      }else if(!empty($column_name) && $column_name=='Email' ){
        $orderby_name = 'Email';
      }else if(!empty($column_name) && $column_name=='Subject' ){
        $orderby_name = 'Subject';
      }
      else{
        $orderby_name='Creation_Date_Time';
      }
      $search = $this->input->get('search');

      $this->db->select('users.Id as User_Id,users.Mobile_No,User_Feedback.*');
      $this->db->from('User_Feedback');
      $this->db->join('users','users.Id=User_Feedback.User_Id');
    
      
        // search condition
      if(!empty($search['value'])){
        $search_value = trim($search['value']);
         $this->db->where('(`User_Feedback.First_Name` LIKE "%'.$search_value.'%" OR `User_Feedback.Last_Name` LIKE "%'.$search_value.'%" OR  `User_Feedback.Email` LIKE "%'.$search_value.'%")',NUll);
      }        
      if($stop!=0) { 
        $this->db->limit($stop,$start);
      }
      
      //$this->db->order_by("users.Creation_Date_Time", "desc"); 
      $this->db->order_by($orderby_name,$order);
      $query=$this->db->get(); 
      
      if($isCount){
            $returnData = $query->num_rows();
      }else{
          $returnData = $query->result();
        
      }
      return $returnData;    
    }
    public function trx_details(){
        $this->db->select('users.FullName,users.FirstName,users.LastName,users.Mobile_No,users.Is_LoggedIn,users.Total_Referral_Points,users.etippers_id,transactions.*,Tran_Status.Status_Name,Tran_Types.Tran_Name');
        $this->db->from('transactions');
        $this->db->join('users','users.Id=transactions.To_User_Id');
        $this->db->join('Tran_Types','Tran_Types.Id=transactions.Tran_Type_Id','left');
        $this->db->join('Tran_Status','Tran_Status.Id=transactions.Tran_Status_Id','left');
        $this->db->where('transactions.Tran_Status_Id', '6');
        $this->db->order_by("transactions.Creation_Date_Time","Desc");
        $query=$this->db->get(); 
        return $returnData = $query->result_array();
    }
     public function users_details($type='',$status=''){
       $this->db->select('users.*,user_in_roles.User_Id,user_in_roles.Role_Id,user_in_roles.QR_Code');
       $this->db->from('users');
       $this->db->join('user_in_roles','users.Id=user_in_roles.User_Id');
       if($type==1){
        $this->db->where('user_in_roles.Role_Id', '2');        
       }else{
        $this->db->where('user_in_roles.Role_Id', '3'); 
       }
       if($status==1){
          $this->db->where('users.Is_Active', '1'); 
        }elseif($status==''){     
        }else{
           $this->db->where('users.Is_Active', '0'); 
        }
        $this->db->order_by("users.Creation_Date_Time","Desc");
       $query=$this->db->get(); 
       return $returnData = $query->result_array();
    }
    

   public function agentajaxlist($isCount = false, $start = 0, $stop = 0, $column_name = '', $order = 'desc')
   {
      switch ($column_name) {
          case 'FullName':  $orderby_name = 'users.FirstName'; break;
          case 'Mobile_No': $orderby_name = 'users.Mobile_No'; break;
          case 'Email':     $orderby_name = 'users.Email'; break;
          default:          $orderby_name = 'users.Creation_Date_Time';
      }

      $search = $this->input->get('search');

      $this->db->select('users.*');
      $this->db->from('users');
      $this->db->join('user_in_roles', 'user_in_roles.User_Id = users.Id');
      $this->db->where('user_in_roles.Role_Id', 5);

      if (!empty($search['value'])) {
          $search_value = trim($search['value']);

          $this->db->group_start();
          $this->db->like('users.FirstName', $search_value);
          $this->db->or_like('users.LastName', $search_value);
          $this->db->or_like('users.Mobile_No', $search_value);
          $this->db->or_like('users.Email', $search_value);
          $this->db->or_like('users.Address', $search_value);
          $this->db->group_end();
      }

      if (!$isCount && $stop != 0) {
          $this->db->limit($stop, $start);
      }

      $this->db->order_by($orderby_name, $order);

      $query = $this->db->get();
      //  echo "<pre>";print_r($query->result());die;

      return $isCount ? $query->num_rows() : $query->result();
  }

}