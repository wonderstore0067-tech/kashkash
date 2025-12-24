<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Referrals_model extends CI_Model {
	public function __construct(){
		parent::__construct();
	}

  // Referral_From  Referral_To Referral_Code Referral_Points  Balance_Referral_Point Status Ref_Num
   public function allreferalsslist($isCount='', $start = 0,$stop = 0, $column_name = '',$order = 'desc'){
       if(!empty($column_name) && $column_name=='Balance_Referral_Point' ){
         $orderby_name = 'Balance_Referral_Point';
       }else{
         $orderby_name='Creation_Date_Time';
       } 
      $search = $this->input->get('search');
     
      // $this->db->select('*');
      // $this->db->from('users_referrals');
     
       // search condition
      // if(!empty($search['value'])){
      //   $search_value = trim($search['value']);
      //   $this->db->where('(`users_referrals.Referral_From` LIKE "%'.$search_value.'%" OR `users_referrals.Referral_To` LIKE "%'.$search_value.'%" OR `users_referrals.Referral_Points` LIKE "%'.$search_value.'%" OR `users_referrals.Balance_Referral_Point` LIKE "%'.$search_value.'%")',NUll);
      // } 

      $this->db->select('a.FirstName,a.LastName,a.FullName,a.Mobile_No,b.FirstName as firstname,b.LastName as lastname,b.FullName as fullname,b.Mobile_No as mobile_no,users_referrals.*');
      $this->db->from('users_referrals');
      $this->db->join('users a','a.Id=users_referrals.Referral_To');
      $this->db->join('users b','b.Id=users_referrals.Referral_From','left');

      // search condition
      if(!empty($search['value'])){
        $search_value = trim($search['value']);
         $this->db->where('(`a.FirstName` LIKE "%'.$search_value.'%" OR `a.LastName` LIKE "%'.$search_value.'%" OR `a.FullName` LIKE "%'.$search_value.'%"  OR `a.Mobile_No` LIKE "%'.$search_value.'%" OR `b.firstname` LIKE "%'.$search_value.'%" OR `b.lastname` LIKE "%'.$search_value.'%" OR `b.fullname` LIKE "%'.$search_value.'%" OR `b.mobile_no` LIKE "%'.$search_value.'%" OR `users_referrals.Referral_Points` LIKE "%'.$search_value.'%" OR `users_referrals.Balance_Referral_Point` LIKE "%'.$search_value.'%"  OR `users_referrals.Referral_Code` LIKE "%'.$search_value.'%")',NUll);
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
   public function allreferalspointslist($isCount='', $start = 0,$stop = 0, $column_name = '',$order = 'desc'){
       if(!empty($column_name) && $column_name=='Give_Refferal_Point' ){
         $orderby_name = 'Give_Refferal_Point';
       }else{
         $orderby_name='Refferal_Points';
       } 
      $search = $this->input->get('search');
      $this->db->select('*');
      $this->db->from('refferal_points_settings');
     
       // search condition
      if(!empty($search['value'])){
        $search_value = trim($search['value']);
        $this->db->where('(`refferal_points_settings.Give_Refferal_Point` LIKE "%'.$search_value.'%"  OR `refferal_points_settings.Refferal_Points` LIKE "%'.$search_value.'%" OR `refferal_points_settings.Refferal_Amount` LIKE "%'.$search_value.'%")',NUll);
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
    public function allredeemajaxlist($isCount=false, $start = 0,$stop = 0, $column_name = '',$order = 'desc'){
      if(!empty($column_name) && $column_name=='FullName' ){
        $orderby_name = 'FullName';
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
      $ignore=array('7','8');
      $this->db->select('users.FirstName,users.LastName,users.Mobile_No,transactions.*');
      $this->db->from('transactions');
      $this->db->join('users','users.Id=transactions.To_User_Id');
      $this->db->where_not_in('transactions.Tran_Type_Id',$ignore);
      $this->db->where('transactions.Tran_Status_Id', '6');
      $this->db->where('transactions.Is_Referral_Point', '1');
      // if(!empty($orderby_name)){
      //    $this->db->order_by($orderby_name, $order);
      // }

        // search condition
      if(!empty($search['value'])){
        $search_value = trim($search['value']);
         $this->db->where('(`users.FullName` LIKE "%'.$search_value.'%" OR `users.FirstName` LIKE "%'.$search_value.'%" OR `users.LastName` LIKE "%'.$search_value.'%" OR `users.Email` LIKE "%'.$search_value.'%" OR `users.Mobile_No` LIKE "%'.$search_value.'%" OR `transactions.Third_Party_Tran_Id` LIKE "%'.$search_value.'%")',NUll);
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
}




