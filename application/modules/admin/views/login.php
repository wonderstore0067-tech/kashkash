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
            <div class="card-plain bg_white boxshadow-login">

                     <?php $attributes = array('class' => 'form', 'id' => 'loginform','data-parsley-validate'=>"");$hidden = array('is_submit' => 1);echo form_open_multipart('admin/admin/login', $attributes, $hidden); ?>  
                    <div class="header border_white">
                        <div class="logo-containers">
                            <?php $logo = isset($general_data[0]['slogo']) ? $general_data[0]['slogo'] : '';?>
                         <img src="<?php echo base_url('uploads/'.$logo );?>" alt="" height="60"> 
                            
                        </div>
                       <!--  <h5 class="black f-18 f-600 ">Welcome to eTippers!</h5> -->
                    </div>
                    <div class="content"> 
                        <div class="form-group typegrp">
                           <select name="usertype" id="" style="margin-bottom: 15px;" class="form-control mail input_border_login" placeholder="Enter Email" required="" data-parsley-required-message=" User type is required" data-parsley-type-message="User type is not valid" >
                                <option value="" default>Select User Type</option>
                                <option value="1">Admin</option>
                                <option value="5">Agent</option>
                            </select>
                            <input type="hidden" id="errchk2">
                          
                            <span class="inputerror malerror  text text-danger" style="display: none;"> </span>
                        </div>
                                                                 
                        <div class="form-group emailgrp">
                            <input type="text" name="useremail" style="margin-bottom: 15px;" class="form-control mail input_border_login" placeholder="Enter Email" required="" data-parsley-required-message=" Email is required"  data-parsley-type="email" data-parsley-type-message="Email is not valid" >
                            <input type="hidden" id="errchk">
                          
                            <span class="inputerror malerror  text text-danger" style="display: none;"> </span>
                        </div>
                       
                        <div class="form-group passgrp">
                            <input type="password" name="userpass" placeholder="Password" class="input_border_login form-control pass" required="" data-parsley-required-message="Password is required"/>
                            <input type="hidden" id="errchk2">
                             <span class="passerror text text-danger" style="display: none;"> </span>
                            <!-- <span class="input-group-addon">
                                <i class="zmdi zmdi-lock"></i>
                            </span> -->
                        </div>
                    </div>
                    
                    <div class="footer text-center">
                        <button type="submit" class="btn btn-round bg_p_login btn-lg btn-block f-700">LOG IN</button>
                        <h5 id="forgotPasswordBox" class="d-none">
                            <a href="<?php echo base_url("admin/forgot_password");?>" class="link">
                                Forgot Password?
                            </a>
                        </h5>
                    </div>
                </form>
                
            </div>
        </div><!--- end of content-center --->
    </div>
    <footer class="footer">
        <div class="container">
            <div class="copyright black">
                &copy;
                <script>
                    document.write(new Date().getFullYear())
                </script>,
                <span style="color: #000; ">Developed by <a href="#" style="color: #21005f;">SilentSol</a></span>
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

$('select[name="usertype"]').on('change', function () {
    var userType = $(this).val();

    if (userType === '5') {
        $('#forgotPasswordBox').removeClass('d-none');
    } else {
        $('#forgotPasswordBox').addClass('d-none');
    }
});


$("#loginform").submit(function(e){
         e.preventDefault();
         $(this).parsley().validate();
        if ($(this).parsley().isValid())
        { 
            var form = $('form')[0]; // You need to use standard javascript object here
            var formData = new FormData(form);
           // var formdata = new Formdata($(this)[0]);
                $.ajax({
                    url: "<?php echo site_url('admin/login');?>",
                    type : "POST",
                    data:formData,
                    contentType:false,
                    processData:false,
                    beforeSend: function(){
                    $('.loader').show();
                    $('.error').remove();
                   },
                  success: function(response){ 
                    var data=JSON.parse(response);
                    if(data.status==true){
                         $(".alert-success").html(data.message ).show();
                         $(".alert-danger").hide(); 
                         $('.loader').hide();
                        window.location.replace('<?php echo base_url();?>admin/dashboard');
                    }else{  
                          $(".alert-danger").html(data.message ).show(); 
                          setTimeout(function(){ $(".alert-danger").hide(); },3000);
                           $(".alert-success").hide();
                           $('.loader').hide();
                            $.each(data.data, function(key, value){    
                              $('input[name='+key+']').closest('div').append(value);
                            });    
                    }
                }
              });
        }else{
           return false;
        }
});



</script>
</body>

</html>