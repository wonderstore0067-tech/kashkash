
/**** ================== withdraw for merchant  ==========================****/
$(":input").bind("keydown keyup change ", function(e) {
           
        if($('#merchant_number').val() ==''){
             $(".inputerror").hide();
           }  
});
$('#withdraw_merchant').submit(function(e){
         e.preventDefault();
         if($(this).parsley().isValid()){ 
            swal({
                title: " ",
                text: 'Enter your transaction PIN',
                type: "input",
                inputType: "password",
                inputPlaceholder: "4 digit pin",
                showCancelButton: true,
                confirmButtonColor: "#d21353",
                confirmButtonText: "Ok",
                closeOnConfirm: false
            }, 
            // function(isConfirm){
            //     if (isConfirm) form.submit() ;   //$('#send_money').submit(true);  //form.submit();
            //     else return false;
            // }); 
                function (inputValue){ 
                    if (inputValue === false) return false;
                    if (inputValue === ""){
                        swal.showInputError("Pin is required for the transaction ");
                        return false;
                    }
                    if (inputValue.length < 4) {
                        swal.showInputError("Pin length is 4 digits ");
                        return false;
                    }
                    else{
                         var formData = new FormData($('#withdraw_merchant')[0]);
                         saveDatas(formData,'account/depositmoney/withdrawMoneyAction','.alert-success','',1,'swal')
                    }
            });

        }
        else{
            return false;
        }
});
$(document).on( 'keyup','.show-input input ' , function(){
        this.value = this.value.replace(/[^0-9]/g, '');
        if($(this).val().length > 4 ){ 
            this.value = '';
        }
        $('.transaction_pin').val($(this).val() ) ;
        if($(this).val() != '' && $(this).val().length ==4){
            $('.show-input fieldset').append('<span class="text-danger er"></span>');  
            var pin= $(this).val();
            $.ajax({
                url: BASEURL+"account/depositmoney/checkTransactionPin",
                type: "POST",
                data: {"pin":pin},
                cache: false,
                success: function(response){
                    var data=JSON.parse(response);
                    if(data.status ==false){
                        $('.er').html(data.message);
                        $('.sa-confirm-button-container button').attr('disabled', true);
                    }
                    else{
                        $('.er').html('');  
                        $('.sa-confirm-button-container button').attr('disabled', false);
                    }
                }
            });
        }
        else{
            $('.er').remove();
            $('.sa-confirm-button-container button').attr('disabled', false);
        }
    });

$("#merchant_number").bind("keydown keyup change ", function(e) {
         if($('#merchant_number').val() ==''){
            $("#merchant_number_err").hide();

        }else{
           if($('[id=merchant_number]').parsley().validate()){
            $("#merchant_number_err").hide();
           }
        }
});
$(document).on(' change', '[id^=merchant_number]',function(){
        if($('[id=merchant_number]').parsley().isValid()){
        if($('#merchant_number').val() !==''){
            var curr = $(this); 
            $(curr).next('div').html('');
            var email = curr.val();
            $.ajax({
            url: BASEURL+"account/depositmoney/getMerchantBusiness/",
            type: "POST",
            data:{ sendto:email},
            success: function(response){
                    var data=JSON.parse(response);
                    if (data.status == true){
                    $('#merchant_number_err').html(data.data).hise();
                    $('#merchant_preview').attr('disabled', false);
                    $('#merchant_preview').css('cursor','pointer');
                }else{
                    $('#merchant_number_err').html(data.message).show();
                     $('#merchant_preview').attr('disabled', true);
                    $('#merchant_preview').css('cursor','not-allowed');
                }
               
               }
            });
          }
        }
        
        });
function acceptwithdraw(id)
{         
    var pin           = $('#pin'+id).val();
    var charge        = $('#charge'+id).val();
    var reqwithdrawId = $('#reqwithdrawId'+id).val();
     // if($(this).parsley().isValid()){ 
            swal({
                title: " ",
                text: 'Enter your transaction PIN',
                type: "input",
                inputType: "password",
                inputPlaceholder: "4 digit pin",
                showCancelButton: true,
                confirmButtonColor: "#d21353",
                confirmButtonText: "Ok",
                closeOnConfirm: false
            }, 
            function (inputValue){ 
                if (inputValue === false) return false;
                if (inputValue === ""){
                    swal.showInputError("Pin is required for the transaction ");
                    return false;
                }
                if (inputValue.length < 4) {
                    swal.showInputError("Pin length is 4 digits ");
                    return false;
                }
                else{
                        $.ajax({
                        type:'POST', 
                        cache:false,
                        url:  BASEURL+'account/depositmoney/acceptWithdrawRequest', 
                        data:{reqwithdrawId:reqwithdrawId,pin:pin,charge:charge},
                        success:function(response)
                           {
                              var data =jQuery.parseJSON(response );
                              if(data.status == true){
                              swal({html:true, title:data.message, text:data.data,type: "success"});
                                 // setTimeout(function(){ location.reload(); },3000); 
                             }else{
                                swal({html:true, title:data.message, text:data.data,type: "warning"});
                             }     
                          } 
                       });

                    }
          });
        // }
        // else{
        //     return false;
        // }
      return false; //stop the form from initially submitting
  }
function declinewithdraw(id)
{         
    var reason = $('#reason'+id).val();
    var msg    = $('#msg'+id).val();
    var reject = $('#reject'+id).val();
    if($('#decline'+id).parsley().isValid()){ 
    $.ajax({
        type:'POST', 
        cache:false,
        url:  BASEURL+'account/depositmoney/declineWithdrawRequest', 
        data:{reqwithdrawId:reject,select_reason:reason,actionMessage:msg},
        success:function(response)
           {
               $('#decline_request'+id).modal("hide");
              var data =jQuery.parseJSON(response );
              if(data.status == true){
              swal({html:true, title:data.message, text:data.data,icon: "success"});
                  setTimeout(function(){ location.reload(); },3000);  
                }else{
                       $('#decline_request'+id).modal("hide");
                       swal({html:true, title:data.message, text:data.data,type: "warning"});
                     }      
            } 
       });
     } 
      return false; //stop the form from initially submitting
}
$('#withdrawbank').submit(function(e){

         e.preventDefault();
         if($(this).parsley().isValid()){ 
            swal({
                title: " ",
                text: 'Enter your transaction PIN',
                type: "input",
                inputType: "password",
                inputPlaceholder: "4 digit pin",
                showCancelButton: true,
                confirmButtonColor: "#d21353",
                confirmButtonText: "Ok",
                closeOnConfirm: false
            }, 
             function (inputValue){ 
                if (inputValue === false) return false;
                if (inputValue === ""){
                    swal.showInputError("Pin is required for the transaction ");
                    return false;
                }
                if (inputValue.length < 4) {
                    swal.showInputError("Pin length is 4 digits ");
                    return false;
                }
                else{
                     var formData = new FormData($('#withdrawbank')[0]);
                     saveDatas(formData,'account/depositmoney/withdrawMoneyAction','','',1,'swal')
                }
            });
        }
        else{
            return false;
        }
});


$('#sendreq').submit(function(e){
         e.preventDefault();
         if($(this).parsley().isValid()){ 
            swal({
                title: " ",
                text: 'Enter your transaction PIN',
                type: "input",
                inputType: "password",
                inputPlaceholder: "4 digit pin",
                showCancelButton: true,
                confirmButtonColor: "#d21353",
                confirmButtonText: "Ok",
                closeOnConfirm: false
            }, 
            // function(isConfirm){
            //     if (isConfirm) form.submit() ;   //$('#send_money').submit(true);  //form.submit();
            //     else return false;
            // }); 
                function (inputValue){ 
                    if (inputValue === false) return false;
                    if (inputValue === ""){
                        swal.showInputError("Pin is required for the transaction ");
                        return false;
                    }
                    if (inputValue.length < 4) {
                        swal.showInputError("Pin length is 4 digits ");
                        return false;
                    }
                    else{
                         var formData = new FormData($('#sendreq')[0]);
                         saveDatas(formData,'account/Paymentrequest/sendPaymentRequestAction','','',1,'swal');
                    }
            });

        }
        else{
            return false;
        }
});

      