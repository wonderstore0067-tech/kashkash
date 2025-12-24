
<section class="content profile-page">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-6 col-sm-12 heading_pt13">
                <h2><?php echo $title;?></h2>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-12">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard');?>"><i class="zmdi zmdi-home"></i> Dashboard</a></li>
                    <li class="breadcrumb-item "><a href="javascript:void(0);" class="color-black"><?php echo $title;?></a></li>
               
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card">
                    <div class="header"> 
                    </div>
                    <div class="body fee_form hideme">
                     <form id="trans_fee" data-parsley-validate="">
                    
                        <div class="row ">
                            <div class="col-lg-6 col-md-12">
                                 <label class="col-md-12 update_padding"><strong>Service name</strong></label>  
                                <div class="form-group">
                                    <input type="text" name="service_name" id="service_name" class="form-control" placeholder="Service name" required data-parsley-required-message="Service name is required">
                                </div>
                            </div>
                        </div>
                         <div class="row ">
                            <div class="col-lg-6 col-md-12">
                                 <label class="col-md-12 update_padding"><strong>Review Fee</strong></label>  
                                <div class="form-group">
                                    <input type="text" name="review_fee" id="review_fee" class="form-control" placeholder="Review Fee" required data-parsley-required-message="Service name is required">
                                </div>
                            </div>
                        </div>
                         <div class="row ">
                            <div class="col-lg-6 col-md-12">
                                <label class="col-md-12 update_padding"><strong>Points Earn(%)</strong></label>  
                                <div class="form-group">
                                    <input type="text" name="charge_fee" id="charge_fee" class="form-control" placeholder="Charge Fee" required data-parsley-required-message="Points earn is required">
                                </div>
                            </div>
                        </div>  
                         <div class="row ">
                            <div class="col-lg-6 col-md-12">

                                <div class="form-group">
                                     <label class="col-md-12 update_padding"><strong>Status</strong></label>   
                                       <label class="switch">
                                      <input type="checkbox" name="trans_status" id="trans_status" value="1">
                                      <span class="slider round"></span>
                                    </label>
                                    </div>
                            </div>
                        </div> 
                            <div class="col-sm-12">
                                  <input type="hidden" name="transactionFeeId" id="fee_id" value="">
                                <button class="btn btn-raised btn-round btn-primary color-purple">Submit</button>
                                <button type="reset" class="btn btn-raised btn-round">Cancel</button>
                            </div>
                            
                        </form>
                            <br>
                                   <div class="m-l-25 col-md-10 alert alert-success hideme"></div>
                                   <div class="m-l-25 col-md-10 alert alert-danger hideme"></div>
                      </div>  
                       <div class="body ">
                        <div class="table-responsive">
                              <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Amount</th>
                                    <th>Point Earn</th>
                                    <th> Transaction For</th>
                                    <th >Added On</th>
                                     <th>Action</th>
                                </tr>
                            </thead>                            
                            <tbody>
                                <?php 
                                   $i=1; 
                                  if(!empty($transaction_fee)){ 
                                    foreach ($transaction_fee as $key => $value) { ?>
                                    <tr>
                                    <td><?php echo $i;?></td>
                                    <td><?php echo 'Any';?></td>
                                    <td><?php echo $value['Fee'];?></td>
                                    <td><?php echo $value['Service_Name'];?></td>
                                    <td><?php echo $value['Created_at'];?></td>
                                    <td><a href="javascript:void(0)" title="Edit" class="btn btn-primary btn-round btn-simple editform" onclick="editform('<?php echo $value['Id'];?>','<?php echo $value['Service_Name'];?>','<?php echo  $value['Review_Fee'];?>','<?php echo  $value['Fee'];?>','<?php echo  $value['Type'];?>','<?php echo  $value['Status'];?>')"><i class="fa fa-edit"></i> Edit</a></td>
                                </tr>

                               <?php  
                                $i++;
                             } 
                            }else{

                           }?>   
                            </tbody>
                        </table>
                        </div>
                      </div>                       
                    </div>
                </div>
            </div>
        </div>
   
</section>
<script src="<?php echo base_url();?>assets/js/pages/tables/jquery-datatable.js"></script>

<script type="text/javascript">
 $('.setings').addClass('active');
  $('.pt_earn').addClass('active');

 $(":input").bind("keyup change", function(e) {
      $(this).parsley().validate();
})
  $("#trans_fee").submit(function(e){
    e.preventDefault();
     $(this).parsley().validate();
    if ($(this).parsley().isValid())
    {
      var formData = new FormData($(this)[0]);
      saveDatas(formData,'admin/admin/points_earn_action','.alert-success')
    }else{
           return false;
        }
  
});
function editform(fee_id,service_nm,review_fee,chrge_fee,type,status){
    $('.fee_form').show();
    $('#fee_id').val(fee_id);
    $('#service_name').val(service_nm);
    $('#review_fee').val(review_fee);
    $('#charge_fee').val(chrge_fee);
   
   if(status == 1){
    $( '#trans_status' ).attr( 'checked', true )
}else{
    $( '#trans_status' ).attr( 'checked',false )
}

};

 
</script>