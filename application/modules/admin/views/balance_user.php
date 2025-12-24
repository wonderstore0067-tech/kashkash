
<section class="content profile-page">
   
   <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-6 col-sm-12 heading_pt13">
                <h2><?php echo $title;?>
                </h2>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-12">                
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin/');?>"><i class="zmdi zmdi-home"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active"><?php echo $title;?></li>
                </ul>                
            </div>
        </div>
    </div>   
    <div class="container-fluid"> 
      <div class="row clearfix"> 
     <div class="card box-shadow">
          <br>
                <div class="col-lg-12  col-md-12">
                   <!-- <h5 class="m-l-5">ADD / SUBSTRACT BALANCE</h5> -->
                    <a href="<?php echo base_url('admin/user_details/'.base64_encode($userdata[0]['Id']).'/'.$usertype);?>" class="btn btn-primary btn-round pull-right color-purple">Back</a>
                </div> 
               
                <div class="row">
                 <div class="col-lg-7 col-md-12 col-sm-12">
                     <form id="user_balance" data-parsley-validate="">
                     <div class="col-lg-8 col-md-12">
                            <label class="col-md-12 update_padding"><strong>Operation</strong></label>
                               <div class="form-group">
                                       <label class="switch">
                                      <input type="checkbox"  name="operation" >
                                      <span class="slider round"></span>
                                    </label>    
                                </div>
                       </div>
                        <div class="col-lg-8 col-md-12">
                            <label class="col-md-12 update_padding"><strong>Amount</strong></label>
                            <div class="form-group">
                              <input type="text" name="amount" class="form-control" placeholder="Amount"  value="" required="" id="amtchk" data-parsley-required-message="  Amount is required" onkeypress="javascript:return isNumber(event)">
                            </div>
                        </div>
                         <div class="col-lg-8 col-md-12">
                            <label class="col-md-12 update_padding"><strong> Message</strong></label>
                            <div class="form-group">
                               <textarea name="message" rows="4" class="form-control no-resize" placeholder="Please type what you want..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <input type="hidden" name="userid" value="<?php echo isset($userdata) ? $userdata[0]['Id']: '';?>"> 
                            <button type="submit" class="btn btn-primary btn-round color-purple btnSubmit">Save Changes</button>
                        </div> 
                         </form> <br>
                          <div class="m-l-25 col-md-10 alert alert-success hideme"></div>
                          <div class="m-l-25 col-md-10 alert alert-danger hideme"></div>
                       </div>
                        <div class="col-lg-5 col-md-12 col-sm-12">
                          <strong>Current Balance</strong><br><br>
                           <?php $logo_img= (!empty($logo[0]['slogo'])) ? $logo[0]['slogo'] : '';?>
                           <img src="<?php echo base_url('assets/images/'.$logo_img);?>" height="" width="150" alt="" onerror="this.src='<?php echo base_url();?>assets/images/wallet.png';"> <span><h4> <?php echo $currency.''. number_format((float)$userdata[0]['Current_Wallet_Balance'], 2, '.', '');?></h4></span>

                        </div>
                      </div>  
                </div>
               </div>
</section>

<script type="text/javascript">
function isNumber(evt) {
     var iKeyCode = (evt.which) ? evt.which : evt.keyCode
      if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
          return false;

      return true;
} 

  $(":input").bind("keyup change", function(e) {
      $(this).parsley().validate();
})
    $("#user_balance").submit(function(e){
    e.preventDefault();
    $(this).parsley().validate();
    if ($(this).parsley().isValid())
    {
         var formData = new FormData($(this)[0]);
         saveDatas(formData,'admin/home/balance_user_action','.alert-success','.alert-danger')  
     }else{
         return false;
      }
});

//var current_balance='<?php echo number_format((float)$userdata[0]['Current_Wallet_Balance'], 2, '.', '');?>';
var current_balance='<?php echo $userdata[0]['Current_Wallet_Balance'];?>';

// $('#amtchk').keyup(function() { 
//  // alert($(this).val());
//   if($(this).val() <= current_balance ){
//     $(".btnSubmit").attr("disabled",false);
   
//   }else{
//     $(".btnSubmit").attr("disabled",true);
//   }
//  }); 
</script>
