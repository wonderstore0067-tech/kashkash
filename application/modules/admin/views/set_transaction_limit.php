
<!-- <style type="text/css">
    .inputerror{
    font-size: 0.9em !important;
    color: red !important;
}
</style> -->
<section class="content profile-page">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-6 col-sm-12 heading_pt13">
                <h2> <?php echo $title;?>  </h2>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-12">                 
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard');?>"><i class="zmdi zmdi-home"></i> Dashboard</a></li>
                    <li class="breadcrumb-item subheading_black"> <?php echo $title;?></li>
                </ul>                
            </div>
        </div>
    </div>    
    <div class="container-fluid">
        <div class="row clearfix">
         
            <div class="col-lg-12 col-md-12">
                <div class="card box-shadow">
                    <ul class="nav nav-tabs">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#addmoney">Add Money</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#sendmoney">Send Money</a></li> 
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#withdraw">Withdraw Money</a></li>                        
                    </ul>
                    <div class="tab-content">
                      <div class="tab-pane body active" id="addmoney">
                        <div class="row clearfix">
                        <div class="col-lg-6 col-md-6">
                         <form class="trx_mthd"  data-parsley-validate="" >
                          <div class="form-group">
                              <label class="col-md-12 update_padding"><strong>Add Money</strong></label>
                              <input type="text" name="add_money" class="form-control" placeholder="Add Money" value="<?php echo isset($deposit_limit_data['name']) ? $deposit_limit_data['name'] : '';?>" disabled >
                            </div>
                            <div class="form-group">
                              <label class="col-md-12 update_padding"><strong>Daily Limit</strong></label>
                                <input type="text" name="daily_limit" class="form-control" placeholder="Daily Limit" value="<?php echo isset($deposit_limit_data['daily_limit']) ? $deposit_limit_data['daily_limit'] : '';?>" required="" data-parsley-required-message="Daily Limit is required" onkeypress="javascript:return isNumber(event)">
                            </div>
                            <div class="form-group">
                               <label class="col-md-12 update_padding"><strong>Monthly Limit</strong></label>
                                <input type="text" name="monthly_limit" class="form-control" placeholder="Monthly Limit" value="<?php echo isset($deposit_limit_data['monthly_limit']) ? $deposit_limit_data['monthly_limit'] : '';?>" required="" data-parsley-required-message=" Monthly Limit is required" onkeypress="javascript:return isNumber(event)">
                            </div>
                            <div class="form-group">
                                <label class="col-md-12 update_padding"><strong>Daily Count Limit</strong></label>
                                <input type="text" name="daily_count_limit" class="form-control" placeholder="Daily Count Limit" value="<?php echo isset($deposit_limit_data['count_limit']) ? $deposit_limit_data['count_limit'] : '';?>" required data-parsley-required-message="Daily Count Limit is required" onkeypress="javascript:return isNumber(event)">
                            </div>
                           <div class="form-group">
                             <label class="col-md-12 update_padding"><strong>Monthly Count Limit</strong></label>
                                <input type="text" name="monthly_count_limit" class="form-control" placeholder="Monthly Count Limit" value="<?php echo isset($deposit_limit_data['monthly_trans_limit']) ? $deposit_limit_data['monthly_trans_limit'] : '';?>" required data-parsley-type="number"  data-parsley-required-message="Monthly Count Limit is required" onkeypress="javascript:return isNumber(event)">
                            </div>
                            <input type="hidden" name="trx_type" class="form-control" value="2">
                            <button class="btn btn-info btn-round color-purple">Save Changes</button> 
                            </form> 
                            </div></div>  
                            <br> 
                             <div class="col-md-6 m-l-10 alert alert-success hideme"></div>
                              <!-- <div class=" alert alert-danger hideme"></div> -->                                             
                        </div>
                        
                        <div class="tab-pane body" id="sendmoney">
                          <div class="row clearfix">
                          <div class="col-lg-6 col-md-6">
                         <form class="trx_mthd" data-parsley-validate="" >
                          <div class="form-group">
                                 <label class="col-md-12 update_padding"><strong>Send Money</strong></label>
                                <input type="text" name="send_money" class="form-control" placeholder="Send Money" value="<?php echo isset($send_limit_data['name']) ? $send_limit_data['name'] : '';?>" disabled >
                            </div>
                            <div class="form-group">
                              <label class="col-md-12 update_padding"><strong>Daily Limit</strong></label>
                                <input type="text" name="daily_limit" class="form-control" placeholder="Daily Limit" value="<?php echo isset($send_limit_data['daily_limit']) ? $send_limit_data['daily_limit'] : '';?>" required="" data-parsley-required-message="Daily Limit is required" onkeypress="javascript:return isNumber(event)">
                            </div>
                            <div class="form-group">
                               <label class="col-md-12 update_padding"><strong>Monthly Limit</strong></label>
                                <input type="text" name="monthly_limit" class="form-control" placeholder="Monthly Limit" value="<?php echo isset($send_limit_data['monthly_limit']) ? $send_limit_data['monthly_limit'] : '';?>" required="" data-parsley-required-message=" Monthly Limit is required" onkeypress="javascript:return isNumber(event)">
                            </div>
                            <div class="form-group">
                                <label class="col-md-12 update_padding"><strong>Daily Count Limit</strong></label>
                                <input type="text" name="daily_count_limit" class="form-control" placeholder="Daily Count Limit" value="<?php echo isset($send_limit_data['count_limit']) ? $send_limit_data['count_limit'] : '';?>" required data-parsley-required-message="Daily Count Limit is required" onkeypress="javascript:return isNumber(event)">
                            </div>
                           <div class="form-group">
                             <label class="col-md-12 update_padding"><strong>Monthly Count Limit</strong></label>
                                <input type="text" name="monthly_count_limit" class="form-control" placeholder="Monthly Count Limit" value="<?php echo isset($send_limit_data['monthly_trans_limit']) ? $send_limit_data['monthly_trans_limit'] : '';?>" required data-parsley-type="number"  data-parsley-required-message="Monthly Count Limit is required" onkeypress="javascript:return isNumber(event)">
                            </div>
                             <input type="hidden" name="trx_type" class="form-control" value="3">
                            <button class="btn btn-info btn-round color-purple">Save Changes</button> 
                            </form> 
                            </div></div>  

                             <br> 
                             <div class="col-md-6 m-l-10 alert alert-success hideme"></div>
                              <div class=" alert alert-danger hideme"></div>
                        </div>  
                        <div class="tab-pane body " id="withdraw">
                          <div class="row clearfix">
                          <div class="col-lg-6 col-md-6">
                         <form class="trx_mthd"  data-parsley-validate="" >
                          <div class="form-group">
                                 <label class="col-md-12 update_padding"><strong>Withdraw Money</strong></label>
                                <input type="text" name="add_money" class="form-control" placeholder="Add Money" value="<?php echo isset($withdraw_limit_data['name']) ? $withdraw_limit_data['name'] : '';?>" disabled >
                            </div>
                            <div class="form-group">
                              <label class="col-md-12 update_padding"><strong>Daily Limit</strong></label>
                                <input type="text" name="daily_limit" class="form-control" placeholder="Daily Limit" value="<?php echo isset($withdraw_limit_data['daily_limit']) ? $withdraw_limit_data['daily_limit'] : '';?>" required="" data-parsley-required-message="Daily Limit is required" onkeypress="javascript:return isNumber(event)">
                            </div>
                            <div class="form-group">
                               <label class="col-md-12 update_padding"><strong>Monthly Limit</strong></label>
                                <input type="text" name="monthly_limit" class="form-control" placeholder="Monthly Limit" value="<?php echo isset($withdraw_limit_data['monthly_limit']) ? $withdraw_limit_data['monthly_limit'] : '';?>" required="" data-parsley-required-message=" Monthly Limit is required" onkeypress="javascript:return isNumber(event)">
                            </div>
                            <div class="form-group">
                                <label class="col-md-12 update_padding"><strong>Daily Count Limit</strong></label>
                                <input type="text" name="daily_count_limit" class="form-control" placeholder="Daily Count Limit" value="<?php echo isset($withdraw_limit_data['count_limit']) ? $withdraw_limit_data['count_limit'] : '';?>" required data-parsley-required-message="Daily Count Limit is required" onkeypress="javascript:return isNumber(event)">
                            </div>
                           <div class="form-group">
                             <label class="col-md-12 update_padding"><strong>Monthly Count Limit</strong></label>
                                <input type="text" name="monthly_count_limit" class="form-control" placeholder="Monthly Count Limit" value="<?php echo isset($withdraw_limit_data['monthly_trans_limit']) ? $withdraw_limit_data['monthly_trans_limit'] : '';?>" required data-parsley-type="number"  data-parsley-required-message="Monthly Count Limit is required" onkeypress="javascript:return isNumber(event)">
                            </div>
                             <input type="hidden" name="trx_type" class="form-control" value="1">
                            <button class="btn btn-info btn-round color-purple">Save Changes</button> 
                            </form> 
                            </div></div>   
                            <br> 
                             <div class="col-md-6 m-l-10 alert alert-success hideme"></div>
                              <!-- <div class=" alert alert-danger hideme"></div> -->                                             
                        </div>                      
                    </div>
                </div>                               
            </div>
        </div>        
    </div>
</section>

<script type="text/javascript">
$('.trxlimit').addClass('active');
function isNumber(evt) {
     var iKeyCode = (evt.which) ? evt.which : evt.keyCode
      if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
          return false;

      return true;
} 
$(":input").bind("keyup change", function(e){
      $(this).parsley().validate();
      $(this).parsley().isValid();
    
});

$(".trx_mthd").submit(function(e){
e.preventDefault();
$(this).parsley().validate();
if ($(this).parsley().isValid())
{
    var formData = new FormData($(this)[0]);
    saveDatas(formData,'admin/home/set_transaction_limit_action','.alert-success');
}
else{
   return false;
}          
});


</script>
