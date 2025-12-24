

<section class="content profile-page">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-6 col-sm-12 heading_pt13">
                <h2><?php echo $title;?></h2>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-12">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard');?>"><i class="zmdi zmdi-home"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);" style="color: #000;"><?php echo $title;?></a></li>
                 
                </ul> 
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card box-shadow">
                    <div class="header"> 
                       <a href="<?php echo base_url('/admin/staff');?>" class="btn btn-primary btn-round pull-right color-purple">Back</a>
                    </div>
                    <div class="body"></div>
                    <div class="body">

                    <form id="set_permission">
                        <?php  
                            $permissions      = unserialize($permission_data[0]['Permission']);
                            //echo "<pre>";print_r($permissions);
                            $dashboard        = isset($permissions['dashboard'] ) ? $permissions['dashboard'] : 0 ;
                            $user_manage      = isset($permissions['user_manage']) ? $permissions['user_manage'] : 0 ;
                            $merchant_manage  = isset($permissions['merchant_manage']) ? $permissions['merchant_manage'] : 0 ;
                            $withdraw         = isset($permissions['withdraw']) ? $permissions['withdraw'] : 0 ;
                            $deposit          = isset($permissions['deposit']) ? $permissions['deposit'] : 0 ;
                            $request          = isset($permissions['request']) ? $permissions['request'] : 0 ;
                            $transaction      = isset($permissions['transaction']) ? $permissions['transaction'] : 0 ;
                            $send_money       = isset($permissions['send_money']) ? $permissions['send_money'] : 0 ;
                            $sharebill_request  = isset($permissions['sharebill_request']) ? $permissions['sharebill_request'] : 0 ;
                            $qrcode           = isset($permissions['qrcode']) ? $permissions['qrcode'] : 0 ;
                            $pay_bill         = isset($permissions['pay_bill']) ? $permissions['pay_bill'] : 0 ;
                            $biller_manage    = isset($permissions['biller_manage']) ? $permissions['biller_manage'] : 0 ;
                           
                            $manage_promocode = isset($permissions['manage_promocode']) ? $permissions['manage_promocode'] : 0 ;
                            $setting          = isset($permissions['setting']) ? $permissions['setting'] : 0 ;
                            $feedback         = isset($permissions['feedback']) ? $permissions['feedback'] : 0 ;
                            $trx_limit        = isset($permissions['trx_limit']) ? $permissions['trx_limit'] : 0 ;
                            $website          = isset($permissions['website']) ? $permissions['website'] : 0 ;
                            $admin_management = isset($permissions['admin_management']) ? $permissions['admin_management'] : 0 ;

                          ?>
                      <div class="row">
                   <!-- <div class="col-md-4 col-sm-4">    
                    <div class="form-group">
                  <label class=""><input type="checkbox" name="dashboard" value="1"  <?php echo ($dashboard==1) ? 'checked' : ''; ?> class=""> Dashboard
                  </label>
                  </div>
                </div> -->
                <div class="col-md-4 col-sm-4">
                <div class="form-group">
                  <label class=""><input type="checkbox" name="user_manage" value="1" <?php echo ($user_manage==1) ? 'checked' : ''; ?> class=""> Users Management
                  </label>
                </div>
              </div>
                <div class="col-md-4 col-sm-4">
                <div class="form-group">
                  <label class=""><input type="checkbox" name="merchant_manage" value="1" <?php echo ($merchant_manage==1) ? 'checked' : ''; ?> class=""> Merchant Management
                  </label>
                </div>
              </div>
               <div class="col-md-4 col-sm-4">
                <div class="form-group">
                  <label class=""><input type="checkbox" name="withdraw" value="1" <?php echo ($withdraw==1) ? 'checked' : ''; ?> class=""> Withdrawal Money
                  </label>
                </div>
              </div>
                
              </div>
              <div class="row">
                
                <div class="col-md-4 col-sm-4">
                <div class="form-group">
                  <label class=""><input type="checkbox" name="deposit" value="1" <?php echo ($deposit==1) ? 'checked' : ''; ?> class=""> Deposit Money
                  </label>
                </div>
              </div>
                <div class="col-md-4 col-sm-4">
                <div class="form-group">
                  <label class=""><input type="checkbox" name="request" value="1" <?php echo ($request== 1) ? 'checked' : ''; ?> class=""> Request Money
                  </label>
                </div>
              </div> 
                <div class="col-md-4 col-sm-4">
                <div class="form-group">
                  <label class=""><input type="checkbox" name="transaction" value="1" <?php echo ($transaction==1) ? 'checked' : ''; ?> class=""> Transaction 
                  </label>
                </div>
              </div>  
              </div>
                <div class="row">
                <div class="col-md-4 col-sm-4">
                <div class="form-group">
                  <label class=""><input type="checkbox" name="send_money" value="1" <?php echo ($send_money==1) ? 'checked' : ''; ?> class=""> Send Money
                  </label>
                </div>
              </div>
                <div class="col-md-4 col-sm-4">
                <div class="form-group">
                  <label class=""><input type="checkbox" name="sharebill_request" value="1" <?php echo ($sharebill_request== 1) ? 'checked' : ''; ?> class=""> ShareBill Request 
                  </label>
                </div>
              </div> 
                <div class="col-md-4 col-sm-4">
                <div class="form-group">
                  <label class=""><input type="checkbox" name="qrcode"  value="1" <?php echo ($qrcode==1) ? 'checked' : ''; ?> class=""> Qrcode Management
                  </label>
                </div>
              </div>  
              </div>
              <div class="row">
                <div class="col-md-4 col-sm-4">
                <div class="form-group">
                  <label class=""><input type="checkbox" name="pay_bill"  value="1" <?php echo ($pay_bill==1) ? 'checked' : ''; ?> class=""> Pay Bill
                  </label>
                </div>
              </div>
                <div class="col-md-4 col-sm-4">
                <div class="form-group">
                  <label class=""><input type="checkbox" name="biller_manage" value="1" <?php echo ($biller_manage==1) ? 'checked' : ''; ?> class=""> Biller Management
                  </label>
                </div>
              </div>
               <div class="col-md-4 col-sm-4">
                <div class="form-group">
                  <label class=""><input type="checkbox" name="manage_promocode"  value="1" <?php echo ($manage_promocode==1) ? 'checked' : ''; ?> class="">Manage Promocode
                  </label>
                </div>
              </div>
                
              </div>
              <div class="row">
                <div class="col-md-4 col-sm-4">
                <div class="form-group">
                  <label class=""><input type="checkbox" name="feedback" value="1" <?php echo ($feedback==1) ? 'checked' : ''; ?> class=""> Feedback
                  </label>
                </div>
              </div>
               <div class="col-md-4 col-sm-4">
                <div class="form-group">
                  <label class=""><input type="checkbox" name="trx_limit" value="1" <?php echo ($trx_limit==1) ? 'checked' : ''; ?> class=""> Transactions Limit
                  </label>
                </div>
              </div>
                 <div class="col-md-4 col-sm-4">
                <div class="form-group">
                  <label class=""><input type="checkbox" name="setting" value="1" <?php echo ($setting==1) ? 'checked' : ''; ?> class=""> Settings
                  </label>
                </div>
              </div>
               
               <!--  <div class="col-md-4 col-sm-4">
                <div class="form-group">
                  <label class=""><input type="checkbox" name="admin_management" value="1" <?php echo ($admin_management==1) ? 'checked' : ''; ?> class=""> Admin Management
                  </label>
                </div>
                </div> -->
              </div>
             <!--  <div class="row">
                 <div class="col-md-4 col-sm-4">
                <div class="form-group">
                  <label class=""><input type="checkbox" name="website" value="1" <?php echo ($website==1) ? 'checked' : ''; ?> class=""> Website Contents
                  </label>
                </div>
              </div>
              </div> -->
              <br>
               <div class="col-sm-12">
                      <input type="hidden" name="userid" id="userid" value="<?php echo isset($permission_data[0]['User_Id'])? base64_encode($permission_data[0]['User_Id']) : ''?>">
                    <button class="btn btn-raised btn-round btn-primary color-purple">Save</button>
                   <!--  <button type="reset" class="btn btn-raised btn-round cancel_br">Cancel</button> -->
                </div> 
                    </form>
                         <br>
                         <div class="m-l-25 col-md-6 alert alert-success hideme"></div>
                         <div class="m-l-25 col-md-6 alert alert-danger hideme"></div>
                      </div>  
                                         
                    </div>
                </div>
            </div>
        </div>
   
</section>
<script src="<?php echo base_url();?>assets/js/pages/tables/jquery-datatable.js"></script>

<script type="text/javascript">
  $("#set_permission").submit(function(e){
    e.preventDefault();
    var formData = new FormData($(this)[0]);
    saveDatas(formData,'admin/admin/set_role_permission_action','.alert-success','.alert-danger')
  
});
function editform(roleid,role_nm,status){
    
    $('#role_id').val(roleid);
    $('#role_name').val(role_nm); 
   if(status == 1){
    $( '#status' ).attr( 'checked', true )
  }else{
      $( '#status' ).attr( 'checked',false )
  }
};

</script>