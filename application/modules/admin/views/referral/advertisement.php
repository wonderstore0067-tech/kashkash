
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
                            <?php //echo $product_id = $this->uri->segment(4);?>
                            <table id="usersList" class="usersList table table-bordered table-striped table-hover m-b-0 js-basic-example dataTabl search_box_right">
                                <thead>
                                    <tr>
                                        <th class="heading_center">S.No.</th>
                                        <th class="heading_center">Name</th>
                                        <th class="heading_center">Email</th>
                                        <th class="heading_center"> Mobile</th>
                                        <th class="heading_center">Wallet Balance</th>
                                        <th class="heading_center">Member Since</th>
                                        <th class="heading_center">Online</th>
                                        <th class="heading_center">Document Status</th>
                                        <th class="heading_center">Status</th>
                                        <th class="heading_center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
               <!--  <div class="card box-shadow">
                    <div class="body">                            
                        <ul class="pagination pagination-primary m-b-0">
                            <li class="page-item"><a class="page-link" href="javascript:void(0);"><i class="zmdi zmdi-arrow-left"></i></a></li>
                            <li class="page-item active"><a class="page-link" href="javascript:void(0);">1</a></li>
                            <li class="page-item"><a class="page-link" href="javascript:void(0);">2</a></li>
                            <li class="page-item"><a class="page-link" href="javascript:void(0);">3</a></li>
                            <li class="page-item"><a class="page-link" href="javascript:void(0);">4</a></li>
                            <li class="page-item"><a class="page-link" href="javascript:void(0);"><i class="zmdi zmdi-arrow-right"></i></a></li>
                        </ul>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">

  var userListingUrl =  BASEURL+"admin/home/allreceiversajaxlist";
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
          'aoColumnDefs': [{'bSortable': false,'aTargets': [0,6,7,8,9]}],
      });


 //  if(userverified =='verified_user' ){
 //   $('.verifyuser').addClass('active');
 //   $('.usermgmt').addClass('active');
 //   $('.merchant_mgmt').removeClass('active');
 // }else if(userverified =='banned_user' ){
 //   $('.bnuser').addClass('active');
 //   $('.usermgmt').addClass('active');
 //   $('.merchant_mgmt').removeClass('active');
 // }else if(userverified =='mobile_unverified' ){
 //   $('.user_mob').addClass('active');
 //   $('.usermgmt').addClass('active');
 //   $('.merchant_mgmt').removeClass('active');
 // }else{
 //      $('.alluser').addClass('active');
 //      $('.usermgmt').addClass('active');
 //      $('.merchant_mgmt').removeClass('active');
 //   }

 //  if(userverified =='verified_user'){
 //   $('.verify_merchant').addClass('active');
 //   $('.merchant_mgmt').addClass('active');
 //   $('.usermgmt').removeClass('active');
 // }else if(userverified =='banned_user'){
 //   $('.bn_merchant').addClass('active');
 //   $('.merchant_mgmt').addClass('active');
 //    $('.usermgmt').removeClass('active');
 // }else if(userverified =='mobile_unverified'){
 //   $('.mob_merchant').addClass('active');
 //   $('.merchant_mgmt').addClass('active');
 //    $('.usermgmt').removeClass('active');
 // }else{
 //      $('.allmgmt').addClass('active');
 //      $('.merchant_mgmt').addClass('active');
 //      $('.usermgmt').removeClass('active');
 //   }



</script>