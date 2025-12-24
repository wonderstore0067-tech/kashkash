<section class="content profile-page">
   
   <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-6 col-sm-12 heading_pt13">
                <h2><?php echo $title;?></h2>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-12">                
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard');?>"><i class="zmdi zmdi-home"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active"><?php echo $title;?></li>
                </ul>                
            </div>
        </div>
    </div>   
    <div class="container-fluid"> 
      <div class="row clearfix"> 
     <div class="card box-shadow parents-list">
          <br>
                <div class="col-lg-12  col-md-12">
                  
                    <a href="<?php echo base_url('/admin/billers_list');?>" class="btn btn-primary btn-round pull-right color-purple deactivate_br">Back</a>
                </div> 
               
                <div class="row">
                 <div class="col-lg-10 col-md-12 col-sm-12">
                     <form id="biller_add" data-parsley-validate="">
                    
                        <div class="col-lg-10 col-md-12">
                            <label class="col-md-12 no_padding"><strong>Biller Name</strong></label>
                            <div class="form-group ">
                                <input type="text" name="biller_name" class="form-control"  placeholder="Biller Name" value="<?php echo isset($biller_data[0]['Biller_Name']) ? $biller_data[0]['Biller_Name'] : '';?>" required data-parsley-required-message="Biller name is required">
                            </div>
                        </div>                       
                         <div class=" col-lg-10 col-md-12 ">  
                          <label class="col-md-12 no_padding"><strong>Category</strong></label> 
                           <div class="form-group ">
                           <select name="category" class="form-control show-tick no_padding" required data-parsley-required-message="Biller category is required">
                                  
                                   <?php foreach($biller_category as $value){ ?> 
                                      
                                      <option value="<?php echo $value['Id'];?>"  <?php
                                      $string= isset($biller_data[0]['Biller_Category_Id']) ? $biller_data[0]['Biller_Category_Id'] : '';
                                        if (strpos($string,$value['Id']) !== false){
                                            echo 'selected';
                                        } ?> > <?php echo $value['Category_Name'];?></option>
                                    
                                    <?php  } ?>
                                </select>
                              </div>
                            </div>
                            <div class="col-lg-10 col-md-12 ">
                                <?php if(!empty($biller_data[0]['Logo_Image_Name'])){ ?>
                                <label class="col-md-12 no_padding"><strong>Current Logo</strong></label>
                               <div class="form-group m-l-20">
                              
                              <img src="<?php echo base_url('/uploads/billers/'.$biller_data[0]['Logo_Image_Name']);?>" width="50px;" height="50px;">
                             
                                </div>
                                  <?php } ?>
                            </div>
                         <div class="col-lg-10 col-md-12">
                            <label class="col-md-12 no_padding"><strong>Update Logo</strong></label>
                           <div class="form-group">
                                <input type="file" name="logo_image" accept="image/*" class="form-control">   
                                <input type="hidden" name="updatelogo" class="form-control" value="<?php echo isset($biller_data[0]['Logo_Image_Name']) ? $biller_data[0]['Logo_Image_Name'] : '';?>">   
                            </div>
                        </div>
                         <div class="col-lg-10 col-md-12">
                            <label class="col-md-12 no_padding"><strong>Telephone No.</strong></label>
                            <div class="form-group">
                                <input type="text" name="telephone_no" class="form-control" placeholder="telephone no" value="<?php echo isset($biller_data[0]['Biller_Contact_Details']) ? $biller_data[0]['Biller_Contact_Details'] : '';?>">
                            </div>
                        </div>
                        <!--  <div class="col-lg-10 col-md-12">
                            <label class="col-md-12"><strong>Billing Circle</strong></label>
                            <div class="form-group">
                                <input type="text" name="billing_circle" class="form-control" placeholder="Amount" value="">
                            </div>
                        </div> -->
                         <div class="col-lg-10 col-md-12">
                             <div class="row">
                             <div class="col-lg-5 col-md-5">
                            <label class="col-md-6 no_padding"><strong>Status</strong></label>
                               <div class="form-group">
                                      <label class="switch">
                                      <input type="checkbox" name="status" value="<?php echo isset($biller_data) ? $biller_data[0]['Is_Active'] : '0';?> " <?php echo ($biller_data[0]['Is_Active']== 1 ? 'checked' : '');?>>
                                      <span class="slider round"></span>
                                    </label>  
                                </div>
                              </div>
                               <div class="col-lg-7 col-md-7">
                                  <label class="col-md-6 no_padding"><strong>Billing Notifications</strong></label>
                                 <div class="form-group">  
                                       <label class="switch">
                                      <input type="checkbox" name="billing_noti" value="<?php echo isset($biller_data) ? $biller_data[0]['Biller_Notification'] : '0';?> " <?php echo ($biller_data[0]['Biller_Notification']== 1 ? 'checked' : '');?>>
                                      <span class="slider round"></span>
                                    </label>    
                                  </div>
                               </div>
                                 </div>
                              </div>
                       
                        <div class="col-md-12">
                            <input type="hidden" name="biller_id" value="<?php echo isset($biller_data[0]['Id']) ? $biller_data[0]['Id'] : '';?>"> 
                            <button type="submit" class="btn btn-primary btn-round color-purple deactivate_br">Save Changes</button>
                        </div> 
                         </form> <br>
                          <div class="m-l-25 col-md-10 alert alert-success hideme"></div>
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

  $("#biller_add").submit(function(e){
    e.preventDefault();
     $(this).parsley().validate();
    if ($(this).parsley().isValid())
    {
      var formData = new FormData($(this)[0]);
      saveDatas(formData,'admin/home/add_biller_action','.alert-success','');
    }else{
           return false;
        }
  
});
 
</script>