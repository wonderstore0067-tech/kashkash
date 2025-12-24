

<section class="content profile-page">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-6 col-sm-12 heading_pt13">
                <h2><?php echo $title;?></h2>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-12">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard');?>"><i class="zmdi zmdi-home"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);" style="color: black;"><?php echo $title;?></a></li>
                 
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card box-shadow">
                    <div class="header"> 
                    </div>
                    <div class="body  ">
                     <form id="roleid"  data-parsley-validate="">
                    
                        <div class="row ">
                            <div class="col-lg-3 col-md-12">
                                 <label class="col-md-12 update_padding"><strong>Role Name</strong></label>  
                                <div class="form-group">
                                    <input type="text" name="role_name" id="role_name" class="form-control" placeholder="Role name" required="" data-parsley-required-message=" Role name is required">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12">
                                <div class="form-group">
                                     <label class="col-md-12 update_padding"><strong>Status</strong></label>   
                                       <label class="switch">
                                      <input type="checkbox" name="status" id="status" value="1">
                                      <span class="slider round"></span>
                                    </label>
                                    </div>
                              </div>
                        </div> 
                            <div class="col-sm-12 no_padding">
                                  <input type="hidden" name="role_id" id="role_id" value="">
                                <button class="btn btn-raised btn-round btn-primary  color-purple">Save</button>
                                <button type="reset" class="btn btn-raised btn-round cancel_br">Cancel</button>
                            </div>     
                        </form>
                            <br>
                                   <div class="m-l-25 col-md-6 alert alert-success hideme"></div>
                                   <div class="m-l-25 col-md-6 alert alert-danger hideme"></div>
                      </div>  
                       <div class="body ">
                        <div class="table-responsive">
                              <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            <thead>
                                <tr>
                                    <th class="heading_center">S.No.</th>
                                    <th class="heading_center">Role Name</th>
                                    <th class="heading_center">Status</th>
                                    <th class="heading_center">Created Time</th>
                                    <th class="heading_center">Action</th>
                                </tr>
                            </thead>                            
                            <tbody>
                                <?php 
                                   $i=1; 
                                  if(!empty($roles_data)){ 
                                    foreach($roles_data as $value){ ?>
                                    <tr>
                                    <td><?php echo $i;?></td>
                                    <td><?php echo $value['Role_Name']?></td>
                                    <td><?php echo ($value['Status']==1) ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Deactive</span>';?></td>
                                    <td><?php echo $value['Creation_Date_Time'];?></td>
                                    <td> 
                                      <?php if($value['Id'] == '1' || $value['Id'] == '2' ||$value['Id'] == '3'){ echo '-';
                                      }else{
                                    ?>
                                      <a href="#" class="btn btn-primary btn-round btn-simple " onclick="editform('<?php echo $value['Id'];?>','<?php echo $value['Role_Name'];?>','<?php echo  $value['Status'];?>')"> <i class="fa fa-edit"></i> EDIT</a>
                                  
                                </td>
                                <?php } ?>
                                    
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
  $('.adminmgmt').addClass('active');
  $('.allroles').addClass('active');

   $(":input").bind("keyup change", function(e) {
      $(this).parsley().validate();
      $(this).parsley().isValid();   
});
  
  $("#roleid").submit(function(e){
    e.preventDefault();
     $(this).parsley().validate();
    if ($(this).parsley().isValid())
    {
      var formData = new FormData($(this)[0]);
        saveDatas(formData,'admin/admin/role_action','.alert-success')
     }else{
       return false;
    }
  
});
function editform(roleid,role_nm,status){
    
    $('#role_id').val(roleid);
    $('#role_name').val(role_nm); 
   if(status == 1){
    $( '#status' ).attr( 'checked', true )
  }else{
      $( '#status' ).attr( 'checked',false )
  }
};

</script>