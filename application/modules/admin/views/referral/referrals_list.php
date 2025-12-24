
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
    <div class="container-fluid">
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
                                        <th class="heading_center">Line #</th>
                                        <th class="heading_center">Referral From</th>
                                        <th class="heading_center">Referral To</th>
                                        <th class="heading_center"> Referral Code</th>
                                        <th class="heading_center">Referral Points</th>
                                        <th class="heading_center">Balance Referral Point</th>
                                        <th class="heading_center">Time</th>
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
  $('.referral_mgmt').addClass('active');
  $('.referrals_listing').addClass('active');
  var userListingUrl =  BASEURL+"admin/referrals/allreferralsajaxslist";
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
          'aoColumnDefs': [{'bSortable': false,'aTargets': [3,6]}],
      });
</script>