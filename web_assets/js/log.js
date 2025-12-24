
/**** ================== for sinup ==========================****/

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


$("#signup").submit(function(e){
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
          saveDatas(formData,'account/users/register','.alert-success','',1,'reg')
        }else{
               return false;
            }
    }
   
});
$("#merchant_redibtn").click(function(){
    $('.merchant_detail').show();
    $('.sole_proprietor').attr("required", "true");
    $('.business_name').attr("required", "true");
    $('.registration_number').attr("required", "true");
    $('.kra_pin_number').attr("required", "true");
});
$("#user_redibtn").click(function(){
    
    $('.sole_proprietor').removeAttr('required');
    $('.business_name').removeAttr('required');
    $('.registration_number').removeAttr('required');
    $('.kra_pin_number').removeAttr('required');
    $('.merchant_detail').hide();
});

$(".email").bind("keyup change", function(e){
 if($('input[name=email]').parsley().isValid()){
           if($('.email').val() !==''){
             check_mobile(1);
           }else{
               $(".malerror").hide();
            }
       }
});

$(".email").bind("keydown keyup change ", function(e) {
         if($('.email').val() ==''){
            $(".malerror").hide();
        }            
});

$(".mobile").bind("keyup change", function(e){
 if($('input[name=mobile]').parsley().isValid()){
           if($('.mobile').val() !==''){
             check_mobile(2);
           }else{
               $(".moberror").hide();
            }
       }
});

$(".mobile").bind("keydown keyup change ", function(e) {
         if($('.mobile').val() ==''){
            $(".moberror").hide();
        }      
});
function check_mobile(type)
{
    var email= $('.email').val();
    var mobile= $('.mobile').val();
    $.ajax({
        url: BASEURL+"account/users/check_mobile/"+type,
        type: "POST",
        data:{ email:email,mobile:mobile},
        success: function(response){
            var data=JSON.parse(response);
            if(data.status == true){
              
                 if(type == 1){
                     if(email !== ''){
                      $(".malerror").html(data.message).show();
                     }
                  }else{
                    if(mobile !== ''){
                      $(".moberror").html(data.message).show();
                    }
                  } 
            }else{  
                   $(".malerror").hide(); 
                 $(".moberror").hide();    
            }
       }
    });
}


function regclear(){
   $(".fullname").val('');  
   $(".email").val('');  
   $(".location_country").val('');  
   $(".password").val('');  
   $(".mobile").val('');  
   $(".id_pass_number").val('');  
   $(".sole_proprietor").val('');  
   $(".business_name").val('');  
   $(".registration_number").val('');  
   $(".kra_pin_number").val('');  
   $("#read-checkbox").val('');  
   $(".identification_image").val('');  
   $("#noti1").html('');  
   $("#noti3").html('');  
  
}

/**** ================== End of sinup ==========================****/

/**** ================== signin  ==========================****/
$("#signin").submit(function(e){
  e.preventDefault();
  $(this).parsley().validate();
   if ($(this).parsley().isValid())
    {
      //$("#verification-code-popup").show();

      var formData = new FormData($(this)[0]);
      saveDatas(formData,'account/users/login','.alert-success','.alert-danger',1,'login');
    }else{
           return false;
        }   
});

function loginclear(){
   $("#mob").val('');  
   $("#paass").val('');      
}
/**** ================== End of signin  ==========================****/

/**** ================== forgot password  ==========================****/
$("#forgotpass").submit(function(e){
  e.preventDefault();
  $(this).parsley().validate();
   if ($(this).parsley().isValid())
    {
      $('#Login-Tab').modal("hide");
      var formData = new FormData($(this)[0]);
      saveDatas(formData,'account/users/forgot_password','.forgetsucc','.forgetdng',1,'verification-code-popup');
    }else{
           return false;
        }   
});


