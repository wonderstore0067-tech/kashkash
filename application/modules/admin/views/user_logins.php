
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
                     <a href="<?php echo base_url('/admin/user_details/'.$userid.'/'.$usertype);?>" class="btn btn-primary btn-round pull-right color-purple">Back</a> 
                    </div>
                    <div class="body"></div>
                    <div class="body">
                        <div class="table-responsive">
                            <?php //echo $product_id = $this->uri->segment(4);?>
                            <table id="usersList" class="table table-bordered table-striped table-hover m-b-0 js-basic-example dataTabl ">
                                <thead>
                                    <tr>                                       
                                        <th>S.No.</th>
                                        <th>User</th>
                                        <th>Ip</th>
                                        <th>Location</th>
                                        <th >Using</th>
                                        <th>Time</th>
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
  //usertype means customer or merchant
 //userverified means user verifed, user banned or mobile unverified users
  var  id='<?php echo $userid;?>';
  var userListingUrl =  BASEURL+"admin/home/userloginajaxlist/"+id;

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