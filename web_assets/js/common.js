
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
            var status_text = "Activated";
        } else {
            var status_text = "Inactivated";
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
}


// page refresh
function refreshPge() {
    window.location.href = window.location.href;
}

function saveDatas(frmdata='', frmaction='',classnm1='',classnm2='',type='',arg=''){
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
             if(type == 1){ 
                 if(arg == 'login'){
                  $(classnm2).hide();
                 $(classnm1).html(data.message ).show();
                 loginclear();
                 setTimeout(function(){ $(classnm1).hide(); },2000);
                }else if(arg == 'reg'){
                  $(classnm2).hide();
                  $(classnm1).html(data.message ).show();
                regclear();
                 setTimeout(function(){ $(classnm1).hide(); },2000);
                }else if(arg == 'swal'){
                  swal({html:true, title:data.message, text:data.data,type: "success"});
                  setTimeout(function(){ location.reload(); },3000);
                }else if(arg == 'verification-code-popup'){ 
                 $(classnm2).hide();
                 $(classnm1).html(data.message ).show();
                 $('#forgot-popup').modal("hide");
                 $('#'+arg).modal({ show: 'flase'});
                 //$(classnm1).hide();         
               }
             }else{
                $(classnm2).hide();
                $(classnm1).html(data.message ).show();
                setTimeout(function(){ location.reload(); },2000);
             } 
        }else{            
                $(classnm1).hide();
                $(classnm2).html(data.message ).show();
                $.each(data.data, function(key, value) {    
                  $('input[name='+key+']').closest('div').append(value);
                });
                if(arg == 'swal'){
                  swal({html:true, title:data.message, text:data.data,type: "error"});
                  //setTimeout(function(){ location.reload(); },3000);
                }
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
            if(data =="")
            {
             $("#suggesstion-box").hide();
             $(".user-searchbox").hide();
            }
            else
            {
             $("#suggesstion-box").show();
             $(".user-searchbox").show();
             $("#suggesstion-box").html(data);
             //$("#search").css("background","#FFF");
            }
             
         }
      });
    });

  //Datetimepicker plugin
  // $('.datetimepicker').bootstrapMaterialDatePicker({
  //     format: 'MM/DD/YYYY',
  //     clearButton: true,
  //     weekStart: 1
     
  // });