<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Advertisement_model extends CI_Model {
	public function __construct(){
		parent::__construct();
	}

   public function alladvertisementslist($isCount='', $start = 0,$stop = 0, $column_name = '',$order = 'desc'){
      if(!empty($column_name) && $column_name=='Advertisement_Title' ){
        $orderby_name = 'Advertisement_Title';
      }elseif(!empty($column_name) && $column_name=='Advertisement_Subtitle' ){
        $orderby_name = 'Advertisement_Subtitle';
      }elseif(!empty($column_name) && $column_name=='Address' ){
        $orderby_name = 'Address';
      }else{
        $orderby_name='Creation_Date_Time';
       } 
      $search = $this->input->get('search');
      $this->db->select('*');
      $this->db->from('manage_advertisement_images');
      //$this->db->where('Status', '1');    
     
       // search condition
      if(!empty($search['value'])){
        $search_value = trim($search['value']);
        $this->db->where('(`manage_advertisement_images.Advertisement_Title` LIKE "%'.$search_value.'%" OR `manage_advertisement_images.Advertisement_Subtitle` LIKE "%'.$search_value.'%" OR `manage_advertisement_images.Address` LIKE "%'.$search_value.'%" OR `manage_advertisement_images.Advertisement_Type` LIKE "%'.$search_value.'")',NUll);
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