
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
            <div class="col-lg-4 col-md-12">
                <div class="card box-shadow profile-header">
                    <div class="body text-center">
                        <div class="profile-image"> <img id="img_tag" src="<?php echo base_url();?>uploads/user/<?php echo $userdata[0]['Profile_Pic'] ?>" alt="" onerror="this.src='<?php echo base_url();?>assets/images/default.jpg';"> </div>
                        <div>
                            <h4 class="m-b-0"><strong><?php echo isset($userdata) ? ucfirst($userdata[0]['FirstName'].' '.$userdata[0]['LastName']) : '' ;?></strong> </h4>
                            <span class=""><?php echo isset($userdata) ? get_role_name($userdata[0]['Id']) : '';?></span>  
                        </div>   
                    </div>                    
                </div>                               
                  
            </div>
            <div class="col-lg-8 col-md-12">
                <div class="card box-shadow">
                    <ul class="nav nav-tabs">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#profile">Profile</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#Account">Change Password</a></li>                        
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane body active" id="profile">
                         <form id="edit_profile"  data-parsley-validate="" >
                          <div class="form-group">
                                <input type="text" name="fname" class="form-control" placeholder="First Name" value="<?php echo isset($userdata[0]['FirstName']) ? $userdata[0]['FirstName'] : '';?>" required="" data-parsley-required-message=" First Name is required">
                            </div>
                            <div class="form-group">
                                <input type="text" name="lname" class="form-control" placeholder="Last Name" value="<?php echo isset($userdata[0]['LastName']) ? $userdata[0]['LastName'] : '';?>" required="" data-parsley-required-message="Last Name is required">
                            </div>
                            <div class="form-group">
                                <input type="text" name="email" class="form-control" placeholder="Email" value="<?php echo isset($userdata[0]['Email']) ? $userdata[0]['Email'] : '';?>"  data-parsley-type="email" required="" data-parsley-required-message=" Email is required" data-parsley-type-message="Email field should be a correct format" readonly>
                            </div>
                            <div class="form-group">
                                <input type="text" name="mobile_no" class="form-control" placeholder="Phone No." value="<?php echo isset($userdata[0]['Mobile_No']) ? $userdata[0]['Mobile_No'] : '';?>" required data-parsley-type="number"  data-parsley-required-message=" Phone number  is required" data-parsley-type-message="Phone number should be a valid number." data-parsley-minlength="10" data-parsley-maxlength="10">
                            </div>
                            <div class="form-group">
                                <input type="file" name="image" class="form-control" id="fileInput" accept="image/*">
                                <input type="hidden" name="profileimg" value="<?php echo isset($userdata[0]['Profile_Pic']) ? $userdata[0]['Profile_Pic'] : '';?>">
                            </div>
                            <button class="btn btn-info btn-round color-purple">Save Changes</button> 
                            </form>   
                            <br> 
                             <div class="m-l-10 alert alert-success hideme"></div>
                              <!-- <div class=" alert alert-danger hideme"></div> -->                                             
                        </div>
                        <div class="tab-pane body" id="Account">
                            <form id="change_pass" data-parsley-validate=""> 
                              <div class="form-group">
                                <input type="password" name="current_password" class="form-control" id="current_password" placeholder="Current Password"  required data-parsley-required-message=" Current password is required" >
                                 <span class="inputerror passerror text text-danger" style="display: none;"> </span>
                            </div>  
                            <div class="form-group">
                                <input type="password" name="new_password" class="form-control"  id="new_password" placeholder="New Password" required data-parsley-required-message=" New password is required" data-parsley-minlength="6" data-parsley-maxlength="32" data-parsley-type="alphanum" data-parsley-minlength-message="New password is too short. It should have 6 characters or more."  data-parsley-maxlength-message=" New password is too long. It should have 32 characters or fewer." >
                                <input type="hidden" name="errchk" id="errchk">
                                 <input type="hidden" name="errchk" id="errchk2">
                                <div id="info" class="hideme">
                                              <span id="noti1" class="fa fa-times" style="color:red !important;">Your Password Must Have One Small letter</span><br>  
                                              <span id="noti3" class="fa fa-times" style="color:red !important;">Your Password Must Have One Number</span><br> 
                                      </div>
                            </div>
                            <div class="form-group">
                                <input type="password" name="confirm_password" class="form-control" placeholder="New Confirm Password" required data-parsley-required-message=" Confirm password is required" data-parsley-equalto="#new_password" data-parsley-equalto-message=" Confirm password should be same"  >
                            </div>
                          
                            <button class="btn btn-info btn-round color-purple passbtn">Save Changes</button>
                             </form><br> 
                             <div class="m-l-10 alert alert-success hideme"></div>
                              <div class=" alert alert-danger hideme"></div>
                        </div>                        
                    </div>
                </div>                               
            </div>
        </div>        
    </div>
</section>

<script type="text/javascript">
$(":input").bind("keyup change", function(e){
      $(this).parsley().validate();
      $(this).parsley().isValid();
    
});
$(":input").bind("keydown keyup change ", function(e) {
           
        if($('#current_password').val() ==''){
             $(".passerror").hide();
           }  
});
$("#current_password").bind("keyup change", function(e){
           if($('#current_password').val() !==''){
               if($('#current_password').val().length >=6){
                 email_password_exists();
               }
            }else{
                $(".passerror").hide();
             }
       });

 $("#edit_profile").submit(function(e){
        e.preventDefault();
        $(this).parsley().validate();
        if ($(this).parsley().isValid())
        {
            var formData = new FormData($(this)[0]);
            saveDatas(formData,'admin/admin/profile_setting_action','.alert-success');
        }
        else{
           return false;
        }          
});

$("#new_password").focusin(function(){
    $('#new_password').addClass('error');
      $("#info").show();
    });

$('#new_password').on('input',function(e){ 
var pswd = $('#new_password').val();
if ( pswd.match(/[a-z]/) ) {
  $('#noti1').removeClass('fa fa-times').addClass('fa fa-check');
      $('#noti1').css('color','green');
      $('#errchk').val(0); 
      $('#new_password').removeClass('error').addClass('valid');
} else {
  $('#noti1').removeClass('fa fa-check').addClass('fa fa-times');
  $('#noti1').css('color','red');
   $('#errchk').val(1);
  $('#new_password').removeClass('valid').addClass('error');
}
if ( pswd.match(/\d/) ) {
  $('#noti3').removeClass('fa fa-times').addClass('fa fa-check');
      $('#noti3').css('color','green');
      $('#errchk2').val(0);
      $('#new_password').removeClass('error').addClass('valid');
} else {
  
  $('#noti3').removeClass('fa fa-check').addClass('fa fa-times');
  $('#noti3').css('color','red');
  $('#errchk2').val(1);
  $('#new_password').removeClass('valid').addClass('error');
}
});

 $("#change_pass").submit(function(e){
   err1=  $('#errchk').val();
    err2=  $('#errchk2').val();
    if(err1 == 1 || err2 ==1){
         return false;
    }
    else
    {
        e.preventDefault();
        $(this).parsley().validate();
        if ($(this).parsley().isValid())
        {
             var formData = new FormData($(this)[0]);
            saveDatas(formData,'admin/admin/change_password','.alert-success','.alert-danger')
        }
        else{
               return false;
            } 
    }
});

function email_password_exists()
{
    var pass= $('#current_password').val();
   
    $.ajax({
       url: "<?php echo site_url();?>admin/admin/email_password_exists/",
       type : "POST",
       data:{pass:pass},
       success: function(response){
            var data=JSON.parse(response);
            if(data.status == true){
                 $(".passerror").hide(); 
                 $(".passbtn").removeAttr("disabled", "disabled");
            }else{            
              $(".passerror").html(data.message).show(); 
              $(".passbtn").attr("disabled", "disabled");

            }
       }
    });
}

function readURLs(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $('#img_tag').attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]);
  }
}

$("#fileInput").change(function() {
   //alert("test");
  readURLs(this);
});


</script>
