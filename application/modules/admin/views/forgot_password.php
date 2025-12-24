<!doctype html>
<html class="no-js " lang="en">

<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">
   <?php $general_data = get_generalsettings();?>
   <title> <?php echo isset($general_data[0]['Sitetitle']) ? $general_data[0]['Sitetitle'] : 'Donmac Admin';?></title>
   <?php $favicon =  isset($general_data[0]['sfavicon']) ? $general_data[0]['sfavicon'] : '';?>
   <link rel="icon" href="<?php echo base_url('uploads/'.$favicon);?>" type="image/x-icon">

    <!-- Custom Css -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/custom.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/main.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/authentication.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/color_skins.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/parsley.css">
</head>

<body class="theme-blush authentication sidebar-collapse">
<!-- Navbar -->
<style type="text/css">
  .inputerror{
    font-size: 0.9em !important;
    color: red !important;
    /* margin-right: 120px;*/
}
 .emailgrp ul li {
     margin-right: 132px
    }
.passgrp ul li {
     margin-right: 100px
    } 
   .parsley-type{
     margin-right: 16px
    } 
.passerror{
   font-size: 0.9em !important;
   color: red !important;
   margin-right: 62px;  
}
</style>

<!-- End Navbar -->
<div class="page-header">
    <div class="page-header-image" style="background-image:url(<?php echo base_url();?>assets/images/dapple_admin_bg.jpg) !important"></div>
    <div class="container">
  <div class="col-md-12 content-center">
      <div class=""><div class="loader" style="display: none;"><div class="m-t-30"><img class="zmdi-hc-spin" src="<?php echo base_url();?>/assets/images/logo.svg" width="48" height="48" alt="Oreo"></div> <p style="color: #000 !important;">Please wait...</p></div></div>
            <div class="col-md-5 message_center">
                <div class=" alert alert-success " style="display:none;"></div>
                <div class=" alert alert-danger " style="display:none;"></div>
             </div>


                <?php 
                 $userid= $this->input->get('userid');
                if(empty($userid)){
                ?>
                <div class="card-plain bg_white boxshadow-login">
                   <div class="succmsg text text-success display-none">  </div>
                  <div class="dangererror  text text-danger display-none" style="color:red">  </div>
                  <form  class="form" id="forgotpass" data-parsley-validate="" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate="">
                    <input type="hidden" name="is_submit" value="1">
                    <div class="header border_white">
                        <div class="logo-containers">
                            <?php $logo = isset($general_data[0]['slogo']) ? $general_data[0]['slogo'] : '';?>
                         <img src="<?php echo base_url('uploads/'.$logo );?>" alt="" height="60"> 
                            
                        </div>
                       <!--  <h5 class="black f-18 f-600 ">Welcome to eTippers!</h5> -->
                    </div>
                    <div class="content loginDiv">                                           
                        <div class="form-group emailgrp">
                            <input type="text" name="email" style="margin-bottom: 15px;" class="form-control mail input_border_login" placeholder="Enter Email" required="" data-parsley-required-message=" Email is required"  data-parsley-type="email" data-parsley-type-message="Email is not valid" >
                            <span class="inputerror malerror  text text-danger" style="display: none;"> </span>
                        </div>
                      </div>
                       <div class="footer text-center">
                        <button type="submit" class="btn btn-round bg_p_login btn-lg btn-block f-700">Forgot Password</button>
                        <h5><a href="JavaScript:Void(0);" class="link hideme" id="ForgotPass">Login Again</a></h5> 
                    </div>
                </form>  
            </div>
              <?php  
                $userid= $this->input->get('userid');
                }elseif(!empty($userid) && $is_verify_email==0){
              ?>
               <div class="card-plain bg_white boxshadow-login"> 
                 <div class="succmsg text text-success hideme" style="font-size: smaller;">  </div>
                   <div class="dangererror  text text-danger hideme" style="color:red font-size: smaller;">  </div>
                  <form  class="form" id="resetpassword" data-parsley-validate="" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate="">
                    <input type="hidden" name="is_submit" value="1">
                    <div class="header border_white">
                        <div class="logo-containers">
                            <?php $logo = isset($general_data[0]['slogo']) ? $general_data[0]['slogo'] : '';?>
                         <img src="<?php echo base_url('uploads/'.$logo );?>" alt="" height="60"> 
                            
                        </div>
                       <!--  <h5 class="black f-18 f-600 ">Welcome to eTippers!</h5> -->
                    </div>
                    <div class="content loginDiv">                                           
                        <div class="form-group passgrp">
                            <input type="password" name="password" placeholder="Password" id="password" class="input_border_login form-control pass" required="" data-parsley-required-message="Password is required"/>
                             <span class="passerror text text-danger" style="display: none;"> </span>
                              <input type="hidden" name="errchk" id="errchk">
                              <input type="hidden" name="errchk" id="errchk2">
                              <div id="info" class="hideme">
                                  <span id="noti1" class="fa fa-times" style="color:red !important; font-size: smaller;">Your Password Must Have One Small letter</span><br>  
                                  <span id="noti3" class="fa fa-times" style="color:red !important;font-size: smaller;">Your Password Must Have One Number</span><br> 
                              </div>
                        </div>
                        <div class="form-group passgrp">
                            <input type="password" name="confirm_password" placeholder="Confirm Password" class="input_border_login form-control pass" required="" data-parsley-required-message="Confirm Password is required" data-parsley-equalto="#password" data-parsley-equalto-message="Confirm Password should be the same." />
                        </div>
                         <input type="hidden" name="user_id" value="<?php echo $userid;?>">

                    </div>
                       <div class="footer text-center">
                        <button type="submit" class="btn btn-round bg_p_login btn-lg btn-block f-700">Reset Password</button>
                        <h5><a href="JavaScript:Void(0);" class="link hideme" id="ForgotPass">login?</a></h5> 
                    </div>
                </form>  
            </div>
              <?php }else{?>
                <script> alert('Your link has been expired..');
                setTimeout(function(){window.location.href='<?php echo site_url('admin');?>';},500 );</script>
              <?php  } ?>



        </div><!--- end of content-center --->
    </div>
    <footer class="footer">
        <div class="container">
            <div class="copyright black">
                &copy;
                <script>
                    document.write(new Date().getFullYear())
                </script>,
                <span style="color: #000; ">Developed by <a href="#" style="color: #21005f;">eTippers</a></span>
            </div>
        </div>
    </footer>
</div>

<!-- Jquery Core Js -->
<script src="<?php echo base_url();?>/assets/bundles/libscripts.bundle.js"></script>
<script src="<?php echo base_url();?>/assets/bundles/vendorscripts.bundle.js"></script> <!-- Lib Scripts Plugin Js -->
<script src="<?php echo base_url();?>assets/js/parsley.js"></script>
<script>
   $(".navbar-toggler").on('click',function() {
    $("html").toggleClass("nav-open");
});

$(":input").bind("keyup change", function(e) {
      $(this).parsley().validate();
      $(this).parsley().isValid()
});
$(":input").bind("keydown keyup change ", function(e) {
         if($('.mail').val() ==''){
            $(".malerror").hide();
        }   
        if($('.pass').val() ==''){
             $(".passerror").hide();
           }  
});
    $("#forgotpass").submit(function(e){
        e.preventDefault(); 
        var form = $('form')[0]; // You need to use standard javascript object here
        var formdata = new FormData(form);
       //var formdata = new Formdata($(this)[0]);
            $.ajax({
                url: "<?php echo site_url('admin/forgot_password_action');?>",
                type : "POST",
                data:formdata,
                contentType:false,
                processData:false,
                beforeSend: function() {
                $('.error').remove();
               },
              success:function(response){ 
                var data=JSON.parse(response);
                if(data.status==true){
                     $(".succmsg").html(data.message ).show();
                     $(".dangererror").hide(); 
                }else{  
                      $(".dangererror").html(data.message ).show(); 
                       $(".succmsg").hide();
                       
                }
            }
          });
    
});


$("#password").focusin(function(){
    $('#password').addClass('error');
      $("#info").show();
    });

$('#password').on('input',function(e){ 
var pswd = $('#password').val();
if ( pswd.match(/[a-z]/) ) {
  $('#noti1').removeClass('fa fa-times').addClass('fa fa-check');
      $('#noti1').css('color','green');
      $('#errchk').val(0); 
      $('#password').removeClass('error').addClass('valid');
} else {
  $('#noti1').removeClass('fa fa-check').addClass('fa fa-times');
  $('#noti1').css('color','red');
   $('#errchk').val(1);
  $('#password').removeClass('valid').addClass('error');
}
if ( pswd.match(/\d/) ) {
  $('#noti3').removeClass('fa fa-times').addClass('fa fa-check');
      $('#noti3').css('color','green');
      $('#errchk2').val(0);
      $('#password').removeClass('error').addClass('valid');
} else {
  
  $('#noti3').removeClass('fa fa-check').addClass('fa fa-times');
  $('#noti3').css('color','red');
  $('#errchk2').val(1);
  $('#password').removeClass('valid').addClass('error');
}
});

$("#resetpassword").submit(function(e){
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
        var form = $('form')[0]; // You need to use standard javascript object here
        var formdata = new FormData(form);
       //var formdata = new Formdata($(this)[0]);
            $.ajax({
                url: "<?php echo site_url('admin/reset_password_action');?>",
                type : "POST",
                data:formdata,
                contentType:false,
                processData:false,
                beforeSend: function() {
                $('.error').remove();
               },
              success:function(response){ 
                var data=JSON.parse(response);
                if(data.status==true){
                     $(".succmsg").html(data.message ).show();
                     $(".dangererror").hide(); 
                    setTimeout(function(){
                      window.location.href='<?php echo site_url('admin');?>';
                      },3000 );
                }else{  
                      $(".dangererror").html(data.message ).show(); 
                       $(".succmsg").hide();
                       
                }
             }
          });
        }else{
          return false;
        }
      }
});


// //   Forgot Password
//   $("#ForgotPass").click(function(){
//      $(".bg_p_login").text("Forgot Password"); 
//     $(".forgotDiv").show();
//     $(".loginDiv").hide();
//     $(this).hide();
//     $("#loginFormShowAgain").show();

//   });    

// //loginFormShowAgain
//   $("#loginFormShowAgain").click(function(){
//     $(".bg_p_login").text("LOG IN"); 
//     $(".forgotDiv").hide();
//     $(".loginDiv").show();
//     $("#loginFormShowAgain").hide();
//     $("#ForgotPass").show();

//   }); 



</script>
</body>

</html>