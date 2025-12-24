
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
                      <?php if(!empty($staff_data_by_id)){?>
                       <a href="<?php echo base_url('admin/staff');?>" class="btn btn-primary btn-round pull-right color-purple">Back</a> 
                      <?php } ?>
                    </div>
                    
                    <div class="body">
                     <form id="staffid" data-parsley-validate="">
                      <div class="row ">
                       <div class="col-lg-3 col-md-12">
                                 <label class="col-md-12 update_padding"><strong>First Name</strong></label>  
                                <div class="form-group">
                                    <input type="text" name="fname" id="fname" class="form-control" placeholder="First Name" value="<?php echo (!empty($staff_data_by_id)) ? $staff_data_by_id[0]['FirstName'] : '';?>" required="" data-parsley-required-message=" First Name is required">
                                </div>
                            </div>
                             <div class="col-lg-3 col-md-12">
                                 <label class="col-md-12 update_padding"><strong>Last Name</strong></label>  
                                <div class="form-group">
                                   <input type="text" name="lname" id="lname" class="form-control" placeholder="Last Name" value="<?php echo (!empty($staff_data_by_id)) ? $staff_data_by_id[0]['LastName'] : '';?>" required="" data-parsley-required-message=" Last Name is required">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-12">
                                 <label class="col-md-12 update_padding"><strong> Mobile</strong></label>  
                                <div class="form-group">
                                    <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Mobile"  value="<?php echo (!empty($staff_data_by_id)) ? $staff_data_by_id[0]['Mobile_No'] : '';?>"  <?php echo (!empty($staff_data_by_id)) ? 'disabled' : '';?> required="" data-parsley-required-message=" Mobile number is required" onkeypress="javascript:return isNumber(event)" data-parsley-minlength="10" data-parsley-minlength-message=" Minimum 10 digit Mobile number is required" data-parsley-maxlength="12" data-parsley-maxlength-message=" Maximum 12 digit Mobile number is required" >
                                </div>
                            </div>
                          </div>
                            <div class="row ">
                              <div class="col-lg-3 col-md-12">
                                 <label class="col-md-12 update_padding"><strong>E-mail</strong></label>  
                                <div class="form-group">
                                    <input type="text" name="email" id="email" class="form-control" placeholder="E-mail" value="<?php echo (!empty($staff_data_by_id)) ? $staff_data_by_id[0]['Email'] : '';?>" <?php echo (!empty($staff_data_by_id)) ? 'disabled' : '';?> required="" data-parsley-required-message=" Email is required">
                                </div>
                            </div>
                            <?php if(empty($staff_data_by_id[0]['Password'])){ ?>
                               <div class="col-lg-3 col-md-12 pass">
                                 <label class="col-md-12 update_padding"><strong> Password</strong></label>  
                                <div class="form-group">
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required="" data-parsley-required-message="Password is required">
                                </div>
                            </div>

                            <?php }?>
                               <div class="col-lg-3 col-md-12">
                                <div class="form-group ">
                                     <label class="col-md-12 update_padding"><strong>Role</strong></label>   
                                    <select name="role" required  id="role" class="form-control  no_padding show-tick" required="" data-parsley-required-message="  Role name is required">
                                    <option value="">--Select Role --</option>
                                      <?php  
                                        foreach($role_data AS $value){  
                                          ?>
                                        <option value="<?php echo $value['Id'];?>" <?php
                                         $string= isset($staff_data_by_id[0]['Role_Id']) ? $staff_data_by_id[0]['Role_Id'] : '';
                                        if (strpos($string,$value['Id']) !== false){
                                            echo 'selected';
                                        } ?>><?php echo $value['Role_Name'];?></option>
                                       <?php
                                        }
                                      ?>    
                                </select>

                                    </div>
                              </div> 
                           
                            </div>
                            <div class="row ">
                               <div class="col-lg-3 col-md-12">
                                <div class="form-group">
                                     <label class="col-md-12 update_padding"><strong>Status</strong></label>   
                                     <label class="switch">
                                      <input type="checkbox" name="status" value="1" <?php echo ( !empty($staff_data_by_id[0]['Is_Active'])== 1 ? 'checked' : '');?>>
                                      <span class="slider round"></span>
                                    </label>
                                    </div>
                              </div>
                            </div>
                            <div class="col-sm-12 no_padding no-margin">
                                  <input type="hidden" name="user_id" id="user_id" value="<?php echo (!empty($staff_data_by_id)) ? base64_encode($staff_data_by_id[0]['userid']) : '';?>">
                                <button class="btn btn-raised btn-round btn-primary color-purple ">Save</button>
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
                                    <th class="heading_center">Full Name</th>
                                    <th class="heading_center">Email</th>
                                    <th class="heading_center">Mobile</th>
                                    <th class="heading_center">Role</th>
                                    <th class="heading_center">Status</th>
                                    <th class="heading_center">No. Of Logins</th>
                                    <th class="heading_center">Action</th>
                                </tr>
                            </thead>                            
                            <tbody>
                                <?php 
                                   $i=1; 
                                  if(!empty($staff_data)){ 
                                    foreach($staff_data as $value){ ?>
                                    <tr>
                                    <td><?php echo $i;?></td>
                                    <td><?php echo ucwords(strtolower($value['FirstName'].' ' .$value['LastName']));?></td>
                                    <td><?php echo $value['Email']; ?></td>
                                    <td><?php echo $value['Mobile_No']; ?></td>
                                    <td><?php echo get_role_name($value['User_Id']);?></td>
                                    <td><?php echo ($value['Is_Active']==1) ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Deactive</span>';?> </td>
                                    <td> <?php echo user_login_count($value['User_Id']);?></td>
                                    <td>
                                      <a href="<?php echo base_url('admin/staff/'.base64_encode($value['User_Id']));?>" class="btn btn-primary btn-round btn-simple "> <i class="fa fa-edit"></i> EDIT</a>
                                      <a href="<?php echo base_url('admin/set_role_permission/'.base64_encode($value['User_Id']));?>" class="btn btn-info btn-round btn-simple " data-id="2"> <i class="fa fa-gear"></i> SET PERMMISSION </a> 

                                    </td>
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
function isNumber(evt) {
     var iKeyCode = (evt.which) ? evt.which : evt.keyCode
      if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
          return false;

      return true;
} 



$('.adminmgmt').addClass('active');
$('.mngestaff').addClass('active');


$(":input").bind("keyup change", function(e) {
      $(this).parsley().validate();
      $(this).parsley().isValid();   
});

  $("#staffid").submit(function(e){
    e.preventDefault();
     $(this).parsley().validate();
    if ($(this).parsley().isValid())
    { 
      var formData = new FormData($(this)[0]);
      saveDatas(formData,'admin/admin/staff_action','.alert-success','.alert-danger');
     }else{
       return false;
    }
  
});

</script>

