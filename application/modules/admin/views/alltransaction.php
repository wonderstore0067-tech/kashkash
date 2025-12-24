
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
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card box-shadow parents-list">
                    <div class="header">
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <?php //echo $product_id = $this->uri->segment(4);?>
                            <table id="usersList" class="table table-bordered table-striped table-hover m-b-0 js-basic-example dataTabl ">
                                <thead>
                                    <tr>                                       
                                        <th class="heading_center">S.No.</th>
                                        <th class="heading_center">User</th>
                                        <th class="heading_center">Mobile</th>
                                        <th class="heading_center"> Amount</th>
                                        <th class="heading_center">Charge</th>
                                        <th class="heading_center"> Time</th>
                                        <th class="heading_center">Transaction No</th>
                                        <th class="heading_center">Transactions Type</th>
                                        <th class="heading_center">Transactions Status</th>
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
$('.trxs').addClass('active');
$('.alltrx').addClass('active');
  var userListingUrl =  BASEURL+"admin/home/alltransactionsajaxlist/";

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
          'aoColumnDefs': [{'bSortable': false,'aTargets': [0,7,8]}],
      });
</script>