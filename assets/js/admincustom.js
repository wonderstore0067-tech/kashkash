
function deleteData_old(postid, actionurl, returnurl){
  var r = confirm("Do you want to delete this?");
  if (r == true){
       $.ajax({
          type : "POST",
          url : actionurl,
          processData:false,
          contentType: false
      }).done(function(response) { 
        var result = JSON.parse(response);       
        if(result.result == '1'){
          window.location.href = returnurl;
        } 
      });
  } else {
     return false;
  }
}
	// function to delete data in database 
	function deleteAction(){
		if ($('.ischeckedaction').is(":checked")) {
            swal({
                title:  "Are you sure you want to do this?", 
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancel",
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                closeOnConfirm: false
            }, function () {
                swal("Deleted", "Delete successfully", "success");
				$('#delete-form').submit();
            });
		}else{
			showErrorMessage('Please select atleast one record');
		}
	}
	


function deleteData(postid, actionurl, returnurl) {
    swal({
        title: "Do you want to delete this?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false
    },function () {
        swal("Deleted!", "Successful Delete" , "success");
            $.ajax({
                type : "POST",
                url : actionurl,
                processData:false,
                contentType: false
            }).done(function(response) { 
              var result = JSON.parse(response);       
              if(result.result == '1'){
                window.location.href = returnurl;
              } else {
                console.log(response);
              }
            });
    });

}


 // delete agent
function deletealert(ids, status, urls, table, field, del_Id) {
    swal({
        title: "Do you want to delete this user",
        type: "warning",
        showCancelButton: true,
        cancelButtonText: "Cancel",
        customClass: 'swal-wide',
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes",
        closeOnConfirm: false,
    }, function() {
        swal('Removed', "User Removed Successfully", "success");

        var formData = {
            'ids': ids,
            'table': table,
            'del_Id': del_Id,
        };
        $.ajax({
            type: 'POST',
            url: urls,
            dataType: 'json',
            async: false,
            data: formData,
            success: function(data) {
                if (data.isSuccess == true) {
                  refreshPge();
                } else {
                  $("#ErrorStatus").html('');
                }
            },
        });
    });
}



  // update ststus
function sweetalert(ids, status, urls, table, field, del_Id) {
    swal({
        title: "Do you want to change status",
        type: "warning",
        showCancelButton: true,
        cancelButtonText: "Cancel",
        customClass: 'swal-wide',
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "yes change it",
        closeOnConfirm: false,
    }, function() {
        if (status == 0) {
            var status_text = "Enabled";
        } else {
            var status_text = "Disabled";
        }
        swal(status_text, "Status change success", "success");

        var formData = {
            'ids': ids,
            'status': status,
            'table': table,
            'field': field,
            'del_Id': del_Id,
        };
        $.ajax({
            type: 'POST',
            url: urls,
            dataType: 'json',
            async: false,
            data: formData,
            success: function(data) {
                if (data.isSuccess == true) {
                      refreshPge();
                    // if (status == 0){
                    //        $('.statusdng'+ids).addClass('activate_br');
                    //        $('.statusdng'+ids).removeClass('deactivate_br');
                    //         $("td a.statusdng"+ids).text('Active');
                    //   }else {
                    //        $('.statussucc'+ids).addClass('deactivate_br');
                    //        $('.statussucc'+ids).removeClass('activate_br');
                    //        $("td a.statussucc"+ids).text('Deactive');
                    //    } 

                } else {
                    $("#ErrorStatus").html('');
                }
            },
        });
    });
}

  // update status user or merchant and also mobile and email verification
function doc_status(ids, status, urls, table, field, del_Id,doc_verified_status='',is_block='') {
    
       if(doc_verified_status ==1){
          swal({
            title: "Do you want to change status",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: "Cancel",
            customClass: 'swal-wide',
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "yes change it",
            closeOnConfirm: false,
        }, function() {
            if (status == 0) {
                var status_text = "Enabled";
            } else {
                var status_text = "Disabled";
            }
            swal(status_text, "Status change success", "success");

            var formData = {
                'ids': ids,
                'status': status,
                'table': table,
                'field': field,
                'del_Id': del_Id,
            };
            $.ajax({
                type: 'POST',
                url: urls,
                dataType: 'json',
                async: false,
                data: formData,
                success: function(data) {
                    if (data.isSuccess == true) {
                          refreshPge(); 
                    } else {
                        $("#ErrorStatus").html('');
                    }
                },
            });
        });

     }else{
            if(doc_verified_status==2){
                var title_text="Mobile No. not verify for this user";
                var doc_text="Please Verify Mobile No. first ";
            }else if(doc_verified_status==3){
                var title_text="Email not verify for this user";
                var doc_text="Please Verify Email first";
            }else{
                var title_text="Email and Mobile No. not verify for this user";
                 var doc_text="Please Verify Email and Mobile No. first";
            }
       swal({html:true, title:title_text,text:doc_text,type: "warning"});
     }
     
}

// page refresh
function refreshPge() {
    window.location.href = window.location.href;
}



function saveData(frmid, frmaction, redirection, message = ''){
     $('#'+message).html('');
      var formData1 = new FormData($('#'+ frmid)[0]);
      $.ajax({
          type : "POST",
          url : frmaction,
          data : formData1,
          processData:false,
          contentType: false
      }).done(function(response) { 
        var result = JSON.parse(response);
        if(result.result == '1'){
          window.location.href = redirection;
        } else {
          $('#'+message).html(result.msg);
        }
      });
}

function saveDatas(frmdata='', frmaction='',classnm1='',classnm2=''){
      //var form = $('form')[0]; 
      //var formData = new FormData(form);   
      var formData = frmdata;
      $.ajax({
          type : "POST",
          url : BASEURL+frmaction,
          data : formData,
          processData:false,
          contentType: false,     
       beforeSend: function() {
        $('.error').remove();
       },
      success: function(response){ 
        var data=JSON.parse(response);
        if(data.status==true){
         // swal("", "Updated Successfully", "success");
             $(classnm2).hide();
             $(classnm1).html(data.message ).show();
            setTimeout(function(){ location.reload(); },2000);
        }else{            
                $(classnm1).hide();
                $(classnm2).html(data.message ).show();
                $.each(data.data, function(key, value) {    
                  $('input[name='+key+']').closest('div').append(value);
                });
        }
     }
});
};


//alert message 
function showErrorMessage(ErrorMsg) {
    swal("",ErrorMsg);
}


function srchfunction(frmid, frmaction, responce){
      $('#'+responce).html('');
      var formData1 = new FormData($('#'+ frmid)[0]);
      $.ajax({
          type : "POST",
          url : frmaction,
          data : formData1,
          processData:false,
          contentType: false
      }).done(function(response) {
        var result = JSON.parse(response);
        $('#'+responce).html(result.data);
      });
}

function getemployeedtl(compid, frmaction, responce, obj){
  _this = $(obj);
  $('#srchcompany').find('tr').removeClass('active');
  _this.parents('tr').addClass('active');
    $('#'+responce).html('');
    $.ajax({
        type : "POST",
        url : frmaction,
        data : { compid:compid },
    }).done(function(resp) {
      console.log(resp);
      var result = JSON.parse(resp);
      $('#'+responce).html(result.data);
    });
}

$('body').on('focus',".form_date", function(){
    $('.form_date').datetimepicker({
      language:  'en',
      weekStart: 1,
      todayBtn:  1,
      autoclose: 1,
      todayHighlight: 1,
      startView: 2,
      minView: 2,
      forceParse: 0
    });
  });

  $('body').on('focus',".form_time", function(){
    $('.form_time').datetimepicker({
      language:  'en',
      weekStart: 1,
      todayBtn:  1,
      autoclose: 1,
      todayHighlight: 1,
      startView: 1,
      minView: 0,
      maxView: 1,
      forceParse: 0
    });
  });

  function getajaxList(postListingUrl, ids){
      $('#'+ids).dataTable({
        "bPaginate": true,
        "bLengthChange": true,
        "bFilter": true,
        "bSort": true,
        "bInfo": true,
        "bAutoWidth": false,
        "processing": true,
        "serverSide": true,
        "stateSave": false,
        "ajax": postListingUrl,
        "columnDefs": [ { "targets": 0, "bSortable": true,"orderable": true, "visible": true } ],
              'aoColumnDefs': [{'bSortable': false,'aTargets': [0,6]}],
          });
  }

  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        $('#blah').attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
  }

  $("#imgInp").change(function() {
    readURL(this);
  });
 $('#search').keyup(function(){
      var q= $(this).val();
    
      $.ajax({
         type: 'post', 
         url: BASEURL+'/admin/admin/search_user', 
         data:{ q:q
            },  
         success:function(response)
         {
            var data =jQuery.parseJSON(response );
            if(data.status ==false)
            {
             $("#suggesstion-box").hide();
             $(".user-searchbox").hide();
             $(".search_hd").removeAttr("action");
            }
            else
            {
             $("#suggesstion-box").show();
             $(".user-searchbox").show();
             $("#suggesstion-box").html(data.data);
             $(".search_hd").attr("action",BASEURL+'/admin/admin/search_user_submit');
             //$("#search").css("background","#FFF");
            }
             
         }
      });
    });

  //Datetimepicker plugin
  $('.datetimepicker').bootstrapMaterialDatePicker({
      format: 'MM/DD/YYYY',
      clearButton: true,
      weekStart: 1
     
  });
