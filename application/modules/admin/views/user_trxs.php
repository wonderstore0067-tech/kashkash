
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
                    <div class="body">
                      <div class="row">
                        <div class="col-lg-5">
                         <h6 class=" pull-left capitalize"> Current Wallet Balance- <?php echo CURRENCY_SYMBOLE .get_current_wallet_balance($userid);?></h6>   
                        </div>
                        <div class="col-lg-7">
                           <a   href="<?php echo base_url('/admin/user_details/'.$userid.'/'.$usertype);?>" class="btn btn-primary btn-round pull-right color-purple">Back</a> 
                        </div>

                      </div>
                      
                    </div>
                    <div class="body">

                        <div class="table-responsive">
                            <?php //echo $product_id = $this->uri->segment(4);?>

                            <table id="usersList" class="table table-bordered table-striped table-hover m-b-0 js-basic-example dataTabl ">
                                <thead>
                                    <tr>                                       
                                        <th>S.No.</th>
                                        <th>User</th>
                                        <th>Mobile</th>
                                        <th> Amount</th>
                                        <th >Charge</th>
                                        <th> Time</th>
                                         <th>Transaction No</th>
                                         <th>Transaction Type</th>
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
 var  id='<?php echo $userid;?>';
 var  trxtype='<?php echo $trxtype;?>'
 var userListingUrl =  BASEURL+"admin/home/usertransactionsajaxlist/"+id+'/'+trxtype;

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
          'aoColumnDefs': [{'bSortable': false,'aTargets': [0,7]}],
      });
</script>