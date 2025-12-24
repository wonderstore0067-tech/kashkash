
<!-- Top Bar -->
<section class="content profile-page">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-6 col-sm-12 heading_pt13">
                <h2><?php echo $title;?>
                </h2>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-12">
               <!--  <button class="btn btn-white btn-icon btn-round hidden-sm-down float-right m-l-10" type="button">
                    <i class="zmdi zmdi-plus"></i>
                </button> -->
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin/');?>"><i class="zmdi zmdi-home"></i> Dashboard</a></li>                    
                    <li class="breadcrumb-item active"><?php echo $title;?></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Referral-Points-Reward For Sign-Up  Referral-Points Per Referal-Amount  Referal-Amount   -->
                    <div class="card box-shadow  referralDiv">
                    <form id="add_the_ref" data-parsley-validate="">
                   <div class="row body">  
                                <div class="col-lg-4 col-md-12">
                                    <label class="col-md-12 update_padding"><strong>  Referral-Points-Reward For Sign-Up</strong></label>
                                    <div class="form-group">
                                        <input type="text" id="Give_Refferal_Point" name="Give_Refferal_Point" class="form-control" placeholder="Enter Referral-Points-Reward For Sign-Up" required="" value="<?php  echo (!empty($referral_data[0]['Give_Refferal_Point'])) ?  $referral_data[0]['Give_Refferal_Point'] : "" ?> " data-parsley-required-message="Referral-Points-Reward For Sign-Up is required" onkeypress="javascript:return isNumber(event)">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12">
                                    <label class="col-md-12 update_padding"><strong>Referral-Points Per Referral-Amount</strong></label>
                                    <div class="form-group">
                                        <input type="text" id="Refferal_Points" name="Refferal_Points" class="form-control" placeholder="Enter Referral-Points Per Referal-Amount" required="" value="<?php echo (!empty($referral_data[0]['Refferal_Points'])) ?  $referral_data[0]['Refferal_Points'] : "" ?> " data-parsley-required-message="Referral-Points Per Referal-Amount is required" onkeypress="javascript:return isNumber(event)">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12">
                                    <label class="col-md-12 update_padding"><strong>Referral-Amount</strong></label>
                                    <div class="form-group">
                                        <input type="text" id="Refferal_Amount" step="any" name="Refferal_Amount" class="form-control" placeholder="Enter Referal-Amount" required="" value="<?php echo (!empty($referral_data[0]['Refferal_Amount'])) ? $referral_data[0]['Refferal_Amount'] : "" ?> " data-parsley-required-message="Referal-Amount is required" onkeypress="javascript:return isNumber(event)">
                                    </div>
                                </div>  
                             
                                <div class="col-md-12">
                                    <br>
                                   <input type="hidden" name="Id" id="Id" value="<?php echo (!empty($referral_data[0]['Id'])) ? $referral_data[0]['Id'] : "" ?>">
                                    <button class="btn btn-primary btn-round color-purple">Save Changes</button>
                                </div> 
                                <div class="col-md-6 alert alert-success hideme"></div>  
                                </div>  
                           </form>
                       </div>

    <div class="container-fluid hideme">
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card box-shadow parents-list">
                     <div class="header">
                          <!-- <strong><i class="fa fa-desktop"></i> View All Users</strong>  -->
                     </div>
                
                    <div class="body">
                        <div class="table-responsive">

                        <!-- ///////////////////// Add Advertisement ////////////////////////////-->

                           <!--  <a class="btn statussucc53 activate_br waves-effect" href="javascript:void(0);" onclick="doc_status(53, '1', 'http://localhost/etippers/admin/advertisement/addAdvTempGet' , 'Users', 'Is_Active','Id','1');" title="Active">Add Advertisement</a> -->
                          <!-- ///////////////////// Add Advertisement ////////////////////////////-->

                            <table id="usersList" class="usersList table table-bordered table-striped table-hover m-b-0 js-basic-example dataTabl search_box_right">
                                <thead>
                                    <tr>
                                        <th class="heading_center">S.No.</th>
                                        <th class="heading_center">Referral-Points-Reward For Sign-Up</th>
                                        <th class="heading_center">Referral-Points Per Referal-Amount</th>
                                        <th class="heading_center">Referal-Amount</th>
                                        <th class="heading_center">Action</th>
                                        <!-- <th class="heading_center">Status</th>
                                        <th class="heading_center">Ref. Num</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
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

$('.referral_mgmt').addClass('active');
$('.add_referrals').addClass('active');
$(":input").bind("keyup change", function(e) {
      $(this).parsley().validate();
})

$("#add_the_ref").submit(function(e){
  e.preventDefault();
  $(this).parsley().validate();
    if ($(this).parsley().isValid())
    {
      var formData = new FormData($(this)[0]);
      saveDatas(formData,'admin/referrals/addReferrals','.alert-success','')
    }else{
           return false;
    }

});

  var userListingUrl =  BASEURL+"admin/referrals/allreferralsmanagementajaxslist";
  $('#usersList').dataTable({
    "bPaginate": true,
    "bLengthChange": true,
    "bFilter": true,
    "bSort": true,
    "bInfo": true, 
    "bAutoWidth": false,
    "processing": true,
    "serverSide": true,
    "stateSave": false,
    "order": [ 0, "desc" ],
    "ajax": userListingUrl,
    "columnDefs": [ { "targets": 0, "bSortable": true,"orderable": true, "visible": true } ],
          'aoColumnDefs': [{'bSortable': false,'aTargets': [1,4]}],
      });
</script>