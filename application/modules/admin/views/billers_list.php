
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
                
                    <div class="body">                      
                         <a href="<?php echo base_url('/admin/add_biller');?>" class="btn btn-primary btn-round pull-right color-purple" > <i class="fa fa-plus"></i> Add Biller</a>
                        <div class="table-responsive">
                              <br><br>
                            <?php //echo $product_id = $this->uri->segment(4);?>
                            <table id="usersList" class="usersList table table-bordered table-striped table-hover m-b-0 js-basic-example dataTabl ">
                                <thead>
                                    <tr>                                       
                                        <th class="heading_center">S.No.</th>
                                        <th class="heading_center">Biller Name</th>
                                        <th class="heading_center">Mobile</th>
                                        <th>Category</th>
                                        <!-- <th>Sub Category</th>   -->
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
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
 $('.billmg').addClass('active');

  var userListingUrl =  BASEURL+"admin/home/allbillersajaxlist/";

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
          'aoColumnDefs': [{'bSortable': false,'aTargets': [0,4,5]}],
      });


</script>