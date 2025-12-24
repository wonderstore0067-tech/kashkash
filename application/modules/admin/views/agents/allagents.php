<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<!-- Top Bar -->
<section class="content profile-page">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-6 col-sm-12 heading_pt13">
                 <h2><?php echo $title;?>
                </h2>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-12"> 
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin/');?>"><i class="zmdi zmdi-home"></i> Dashboard</a></li>                    
                    <li class="breadcrumb-item active"><?php echo $title;?></li>
                </ul>
            </div>
        </div>
    </div>
      <!-- <div class="card box-shadow">
        <div class="row">
            <div class="col-md-10  m-t-10">
                <form id="profile_update" method="POST" action="<?php echo base_url('admin/searchTransaction');?>" data-parsley-validate="" novalidate="">
                    <div class="row body">
                        <div class="col-lg-4 col-md-12">
                            <label class="col-md-12 update_padding"><strong>Search Transaction By Date</strong></label>
                            <div class="form-group">
                                <input type="text" name="trxDate" class="form-control" required id="dateRange" placeholder="Search Transaction By Date"  value="<?php $searchTrx = $this->session->userdata('searchdata');echo isset($searchTrx) ? $searchTrx['trxDate'] : '';?>">
                            </div>
                            </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                            <button class="btn btn-primary btn-round color-purple">Search </button>
                            <a class="btn btn-primary btn-round color-purple" href="<?php echo base_url('admin/all_transactions');?>">Reset </a>
                    </div>
                </form>
            </div>
            <div class="col-md-2 text-right ">
                <a  href="<?php echo base_url('admin/home/exportTransactioncsv/csv');?>" class="btn btn-primary btn-round text-right m-r-30">Export CSV</a>
            </div>
        </div>
    </div>   -->
     <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card box-shadow parents-list">
                    <div class="header">

                        <div class="col-md-2 text-right float-right">
                            <a  href="<?php echo base_url('admin/addAgents');?>" class="btn btn-primary btn-round text-right m-r-30">Add Agent</a>
                        </div>

                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table id="usersList" class="table table-bordered table-striped table-hover m-b-0 js-basic-example dataTabl ">
                                <thead>
                                    <tr>                                       
                                        <th class="heading_center">Line #</th>
                                        <th class="heading_center">Name</th>
                                        <th class="heading_center">Email</th>
                                        <th class="heading_center">Mobile</th>
                                        <th class="heading_center">Address</th>
                                        <th class="heading_center">Date</th>
                                        <th class="heading_center">Status</th>
                                        <th class="heading_center">Action</th>
                                        <!-- <th class="heading_center">Transaction No</th>
                                        <th class="heading_center">Transactions Status</th> -->
                                         <!-- <th>Details</th>   -->
                                    </tr>
                                </thead> 
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
$( document ).ready(function() {
  var searchtrx="<?php echo $this->uri->segment(3);?>";
  if(searchtrx ==''){
    $('#dateRange').val('  ');
   };
});

$('#dateRange').daterangepicker();
$('.trxs').addClass('active');
$('.alltrx').addClass('active');
  var userListingUrl =  BASEURL+"admin/home/allagentsajaxlist/";

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
          'aoColumnDefs': [{'bSortable': false,'aTargets': [0,5]}],
      });
</script>