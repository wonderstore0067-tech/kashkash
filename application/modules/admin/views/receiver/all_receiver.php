
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
    <div class="card box-shadow">
              <div class="row">
                <div class="col-md-10  m-t-10">
                    <form id="profile_update" method="POST" action="<?php echo base_url('admin/searchReceiver');?>" data-parsley-validate="" novalidate="">
                           <div class="row body">
                            <?php $searchData= $this->session->userdata('receiverdata');?>
                             <div class="col-md-4 col-sm-12">  
                                 <label class="col-md-12 update_padding"><strong>Users Status</strong></label>
                                <select name="user_status" class="form-control show-tick" required data-parsley-required-message="Users Status is required">
                                    <option value="">-- Status --</option>
                                    <option value="1" <?php echo $searchData ? ($searchData['user_status']=='1' ? 'selected': '') : '';?>>Enabled</option>
                                    <option value="0" <?php echo $searchData ? ($searchData['user_status']=='0' ? 'selected': '') : '';?>>Disabled</option> 
                                </select>
                               </div>
                          </div>
                        <div class="col-lg-4 col-md-12">
                                <button class="btn btn-primary btn-round color-purple">Search </button>
                                <a class="btn btn-primary btn-round color-purple" href="<?php echo base_url('admin/all_receivers');?>">Reset </a>
                         </div>
                    </form>
                </div>
               <div class="col-md-2 text-right ">
                <?php if(isset($searchData)){ ?>
                    <a href="<?php echo base_url('admin/home/exportUsercsv/2/'.$searchData['user_status']);?>" class="btn btn-primary btn-round text-right m-r-30">Export CSV</a>
                  <!-- <a href="<?php echo base_url('admin/home/exportUsercsv/2/'.$searchData['user_status']);?>" class="btn btn-primary btn-round text-right m-r-30">Export CSV</a> -->
                <?php } else{ ?>
                    <a href="<?php echo base_url('admin/home/exportUsercsv/2/'.$searchData);?>" class="btn btn-primary btn-round text-right m-r-30">Export CSV</a>
                <?php } ?>
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
                                        <th class="heading_center">Line #</th>
                                        <th class="heading_center">Name</th>
                                        <th class="heading_center">Email</th>
                                        <th class="heading_center"> Mobile</th>
                                        <th class="heading_center">Wallet Balance</th>
                                        <th class="heading_center"> Referral Points</th>
                                          <th class="heading_center">Etippers Id</th>
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
$('.receivermgmt').addClass('active');

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
          'aoColumnDefs': [{'bSortable': false,'aTargets': [0,8,9,10,11]}],
      });

</script>