<style type="text/css">
 .imgsize{
     height: 170px;
    width: 170px;
    }
</style>

<section class="content profile-page">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-6 col-sm-12">
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
            <div class="col-lg-4 col-md-12">
                <div class="card box-shadow profile-header">
                    <div class="body text-center">
                        <div class="profile-image "> <img class="imgsize" src="<?php echo base_url();?>uploads/user/<?php echo $userdata[0]['Profile_Pic'] ?>" id="img_tag" alt="" onerror="this.src='<?php echo base_url();?>assets/images/default.jpg';"> </div>
                        <div>
                            <h4 class="m-b-0"><strong><?php echo isset($userdata) ? ucwords(strtolower($userdata[0]['FirstName'].' '.$userdata[0]['LastName'])) : '';?></strong> </h4>
                            <span class=""><?php echo isset($userdata[0]['Email']) ? $userdata[0]['Email'] : '';?></span><br>
                            <span class=""><strong>Wallet Blance:</strong> <?php echo isset($userdata[0]['Current_Wallet_Balance']) ? CURRENCY_SYMBOLE.''.$userdata[0]['Current_Wallet_Balance'] : '';?></span><br>
                        </div>
                        <div>
                           <!--  <a href="<?php echo base_url('admin/receiver_documents_verified/'.base64_encode($userdata[0]['Id']));?>" class="btn btn-primary btn-round color-purple">View Documents </a>  -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-12">
                <div class="row clearfix">                 
                </div>
                <div class="card box-shadow">
                    <form id="profile_update" data-parsley-validate="">
                   <div class="row body">
                    <label class="col-md-12"><h6 class="capitalize"><i class="fa fa-cog"></i> Update Profile</h6></label>   
                                <div class="col-lg-4 col-md-12">
                                    <label class="col-md-12 update_padding"><strong>First Name</strong></label>
                                    <div class="form-group">
                                        <input type="text" name="fname" class="form-control" placeholder="First Name" required="" value="<?php echo isset($userdata) ? $userdata[0]['FirstName']: '';?>" data-parsley-required-message="First Name is required">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12">
                                    <label class="col-md-12 update_padding"><strong>Last Name</strong></label>
                                    <div class="form-group">
                                        <input type="text" name="lname" class="form-control" placeholder="Last Name" required="" value="<?php echo isset($userdata) ? $userdata[0]['LastName']: '';?>" data-parsley-required-message="Last Name is required">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12">
                                    <label class="col-md-12 update_padding"><strong>Email</strong></label>
                                    <div class="form-group">
                                        <input type="text" name="email" class="form-control" placeholder="Mobile" disabled value="<?php echo isset($userdata) ? $userdata[0]['Email']: '';?>">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12">
                                    <label class="col-md-12 update_padding"><strong>Mobile</strong></label>
                                    <div class="form-group">
                                        <input type="text" name="mobile" class="form-control" placeholder="Mobile" disabled value="<?php echo isset($userdata) ? $userdata[0]['Mobile_No']: '';?>">
                                    </div>
                                </div>
                                 <div class="col-lg-4 col-md-12">
                                    <label class="col-md-12 update_padding"><strong>Etippers Id</strong></label>
                                    <div class="form-group">
                                        <input type="text" name="mobile" class="form-control" placeholder="Mobile" disabled value="<?php echo isset($userdata) ? $userdata[0]['etippers_id']: '';?>">
                                    </div>
                                </div>
                                <?php //if(!empty(@$userdata[0]['Address'])){?>
                                 <div class="col-lg-4 col-md-12">
                                    <label class="col-md-12 update_padding"><strong>Address</strong></label>
                                    <div class="form-group">
                                        <input type="text" name="address" class="form-control" placeholder="Address" value="<?php echo isset($userdata) ? $userdata[0]['Address']: '';?>">
                                    </div>
                                </div>
                              <?php //} ?>
                               <?php //if(!empty(@$userdata[0]['Age'])){?>
                                 <div class="col-lg-4 col-md-12">
                                    <label class="col-md-12 update_padding"><strong>Age</strong></label>
                                    <div class="form-group">
                                        <input type="text" name="age" class="form-control" placeholder="Age" value="<?php echo (!empty($userdata[0]['Age'])) ? $userdata[0]['Age']: '';?>" >
                                    </div>
                                </div>
                              <?php //} ?>
                                <div class="col-md-4 col-sm-12">  
                                 <label class="col-md-12 update_padding"><strong>Gender</strong></label>
                                <select name="gender" class="form-control show-tick" required data-parsley-required-message="Gender is required">
                                    <option value="">-- Gender --</option>
                                    <option value="M"  <?php
                                     $string= isset($userdata) ? $userdata[0]['Gender'] : '';
                                        if (strpos($string,'M') !== false){
                                            echo 'selected';
                                        } ?>
                                   >Male</option>
                                    <option value="F" <?php
                                     $string= isset($userdata) ? $userdata[0]['Gender'] : '';
                                        if (strpos($string,'F') !== false){
                                            echo 'selected';
                                        } ?>>Female</option>
                                      <option value="O" <?php
                                          $string= isset($userdata) ? $userdata[0]['Gender'] : '';
                                        if (strpos($string,'O') !== false){
                                            echo 'selected';
                                       } ?>>Other</option>
                                </select>
                             </div>
                              <div class="col-lg-4 col-md-12">
                                    <label class="col-md-12 update_padding"><strong>Is Allowed Transaction</strong></label>
                                     <div class="form-group">
                                    <label class="switch"> 
                                      <input type="checkbox" name="is_allowed_transaction" value="<?php echo isset($userdata) ? $userdata[0]['Is_Allowed_Transaction'] : '0';?> " <?php echo ($userdata[0]['Is_Allowed_Transaction']== 1 ? 'checked' : '');?>>
                                      <span class="slider round"></span>
                                    </label>
                                    </div> 
                                </div>
                          
                                <div class="col-md-4 col-sm-12">
                                <label class="col-md-12 update_padding"><strong>Email Verification</strong></label>
                                    <?php if($userdata[0]['Is_Email_Verified']==1){?> 
                                    <span class="badge badge-success"><i class="fa fa-check" aria-hidden="true"></i> Verified</span>  
                                    <?php }else{ ?> 
                                     <div class="form-group">
                                       <label class="switch">
                                      <input type="checkbox" name="email_verified" value="<?php echo isset($userdata) ? $userdata[0]['Is_Email_Verified'] : '0';?> " <?php echo ($userdata[0]['Is_Email_Verified']== 1 ? 'checked' : '');?>>
                                      <span class="slider round"></span>
                                    </label>
                                    </div>
                                <?php } ?>
                                </div>
                                 <div class="col-md-4 col-sm-12">
                                <label class="col-md-12 update_padding"><strong>Mobile Verification</strong></label>
                                 <?php if($userdata[0]['Is_Mobile_Verified']==1){?> 
                                    <span class="badge badge-success"><i class="fa fa-check" aria-hidden="true"></i> Verified</span>  
                                    <?php }else{ ?> 
                                     <div class="form-group">
                                       <label class="switch">
                                      <input type="checkbox" name="mobile_verified" value="<?php echo isset($userdata) ? $userdata[0]['Is_Mobile_Verified'] : '0';?> " <?php echo ($userdata[0]['Is_Mobile_Verified']== 1 ? 'checked' : '');?> >
                                      <span class="slider round"></span>
                                    </label>
                                    </div>
                                <?php } ?>
                                </div>
                                 <div class="col-lg-4 col-md-12">
                                    <label class="col-md-12 update_padding"><strong>Profile Image</strong></label>
                                   <div class="form-group">
                                       
                                            <input type="file" id="fileInput" class="fileInput" name="userImg" class="form-control" accept="image/*">
                                            <!-- <a href="javascript:void(0)" id="fileButton" class="btn btn-primary">Change Image</a> -->
                                      </div>
                                </div>
                                <div class="col-md-12">
                                    <br>
                                   <input type="hidden" name="userid" value="<?php echo isset($userdata) ? $userdata[0]['Id']: '';?>">
                                    <button class="btn btn-primary btn-round color-purple">Save Changes</button>
                                </div> 
                      </div>
                  </form>
                   <div class="m-l-20 alert alert-success hideme"></div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
            </div>
            <div class="col-lg-8 col-md-12">
             <div class="row clearfix"> 
               <div class="card box-shadow ">
               <form id="documents">
                       <div class="body ">
                                <div class="row no-margin"> 
                                <!--   <h5 class="col-md-12"><i class="fa fa-cog"></i>Document Details</h5> -->
                                    <div class="col-md-8">
                                       <label class="col-md-12 update_padding"><strong>Unique Identificaiton Image </strong></label>
                                          <?php   $front_image=(!empty($document_data[0]['Document_Image_Name'])) ? $document_data[0]['Document_Image_Name'] :'' ; $url=IMAGE_PATH.'identification/';?>
                                       <div class="profile-image "><?php echo doc_image_check($front_image,$url,$flag=1);?> </div>
                                      </div>
                                    </div>
                                    <div class="row no-margin ">
                                        <div class="col-md-12 col-sm-12 m-t-30">
                                         <div class="row">
                                         <div class="col-lg-2 col-md-12">
                                          <label class="col-md-12 update_padding"><strong>Verified Status</strong></label>     
                                           <div class="form-group">
                                              <label class="switch">
                                                 <input type="hidden" name="userid" value="<?php echo isset($userdata) ? $userdata[0]['Id']: '';?>">  
                                                <input type="checkbox" name="documents_status" value="<?php echo isset($document_data) ? $document_data[0]['Is_Verified'] : '0';?> " <?php echo ($document_data[0]['Is_Verified']== 1 ? 'checked' : '');?>>
                                                <span class="slider round"></span>
                                              </label>
                                              </div>  
                                            </div>
                                             
                                        </div>
                                              <button class="btn btn-primary btn-round color-purple">Save Changes</button>
                                          </div> 
                                  </div>
                                  
                              </div>
                            </form>
                              <div class="m-l-25 col-md-6 alert alert-success succ hideme"></div>
                              <div class="m-l-25 col-md-6 alert alert-danger errmsg1 hideme"></div>
                          </div>
                       </div>
                      </div>
        </div>
    </div>
</section>

<script type="text/javascript">
$(":input").bind("keyup change", function(e) {
      $(this).parsley().validate();
})

$("#profile_update").submit(function(e){
  e.preventDefault();
  $(this).parsley().validate();
    if ($(this).parsley().isValid())
    {
      var formData = new FormData($(this)[0]);
      saveDatas(formData,'admin/home/receiver_profile_update','.alert-success','')
    }else{
           return false;
        }

});
 
function readURLs(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $('#img_tag').attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]);
  }
}

$("#fileInput").change(function() {
   //alert("test");
  readURLs(this);
});

    //Document update
    $("#documents").submit(function(e){
    e.preventDefault();
    var formData = new FormData($(this)[0]);
    saveDatas(formData, 'admin/home/receiver_documents_verified_action','.succ','.succ') 
});

</script>