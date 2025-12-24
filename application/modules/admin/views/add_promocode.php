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
     <div class="card box-shadow">
          <br>
                <div class="col-lg-12  col-md-12">
                    <a href="<?php echo base_url('/admin/promocode_list');?>" class="btn btn-primary btn-round pull-right color-purple deactivate_br">Back</a>
                </div> 
               
                <div class="row">
                 <div class="col-lg-10 col-md-12 col-sm-12">
                     <form id="promo_add"  data-parsley-validate="">
                       <div class="row m-l-10">
                        <div class="col-lg-6 col-md-8">
                            <label class="col-md-12 no_padding"><strong>Promocode Name</strong></label>
                            <div class="form-group ">
                                <input type="text" name="promo_name" class="form-control"  value="<?php echo isset($promo_data[0]['Promo_Name']) ? $promo_data[0]['Promo_Name'] : '';?>" required="" data-parsley-required-message=" Promocode name is required">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-8">
                            <label class="col-md-12 no_padding"><strong>Promocode Description</strong></label>
                            <div class="form-group ">
                                <!-- <input type="text" name="promo_desc" class="form-control" data-parsley-required placeholder="Promocode Description" value="<?php //echo isset($promo_data[0]['Promo_Description']) ? $promo_data[0]['Promo_Description'] : '';?>"> -->

                                <textarea name="promo_desc" rows="2" class="form-control no-resize" placeholder="Please type what you want..."><?php echo isset($promo_data[0]['Promo_Description']) ? $promo_data[0]['Promo_Description'] : '';?></textarea>
                            </div>
                        </div>
                      </div>
                      <div class="row m-l-10">
                         <div class=" col-lg-6 col-md-8 ">  
                          <label class="col-md-12 no_padding"><strong>Transaction Type</strong></label> 
                           <div class="form-group ">
                           <select name="trx_type" class="form-control show-tick no_padding" > 
                                   
                                   <?php foreach($tran_types as $value){ ?>      
                                      <option value="<?php echo $value['Id'];?>"  <?php
                                      $string= isset($promo_data[0]['Tran_Type_Id']) ? $promo_data[0]['Tran_Type_Id'] : '';
                                        if (strpos($string,$value['Id']) !== false){
                                            echo 'selected';
                                        } ?> > <?php echo $value['Tran_Name'];?></option>
                                    
                                    <?php  } ?>
                                </select>
                              </div>
                            </div>
                            <div class="col-lg-6 col-md-8">
                            <label class="col-md-12 no_padding"><strong>Promo Code</strong></label>
                            <div class="form-group">
                                <input type="text" name="promo_code" class="form-control" placeholder=" Promo Code" value="<?php echo isset($promo_data[0]['Promo_Code']) ? $promo_data[0]['Promo_Code'] : '';?>" required="" data-parsley-required-message=" Promo code is required">
                              </div>
                             </div>
                            </div>
                         <div class="row m-l-10">
                            <div class="col-lg-6 col-md-8">
                              <label class="col-md-12 no_padding"><strong>Start Date</strong></label>
                              <div class="form-group ">
                                  <input type="text" name="start_date" class="startdate form-control"  id="StartDate" value="<?php echo isset($promo_data[0]['Promo_Start_Date']) ? date('m/d/Y',$promo_data[0]['Promo_Start_Date']) : '';?>" required="" data-parsley-required-message=" Start date is required">
                              </div>
                          </div>
                            <div class="col-lg-6 col-md-8">
                              <label class="col-md-12 no_padding"><strong>End Date</strong></label>
                              <div class="form-group ">
                                  <input type="text" name="end_date" class="form-control enddate"  id="EndDate" value="<?php echo isset($promo_data[0]['Promo_End_Date']) ? date('m/d/Y ',$promo_data[0]['Promo_End_Date']) : '';?>" required="" data-parsley-required-message=" End date is required">
                              </div>
                          </div>
                      </div>
                        <div class="row m-l-10">
                             <div class="col-lg-6 col-md-8">
                            <label class="col-md-6 no_padding"><strong>Status</strong></label>
                               <div class="form-group">
                                      <label class="switch">
                                      <input type="checkbox" name="promo_status" value="1" <?php echo ($promo_data[0]['Promo_Status']== 1 ? 'checked' : '');?>>
                                      <span class="slider round"></span>
                                    </label>  
                                </div>
                              </div>
                               <div class="col-lg-6 col-md-8">
                                  <label class="col-md-6 no_padding"><strong>Promo Limit</strong></label>
                                 <div class="form-group">  
                                       <input type="number" name="promo_limit" class="form-control" min="0"  value="<?php echo isset($promo_data[0]['Promo_Limit']) ? $promo_data[0]['Promo_Limit'] : '';?>" required="" data-parsley-required-message=" Promo limit is required">    
                                  </div>
                               </div>
                                 
                           </div>
                      <div class="row m-l-10"> 
                         <div class="col-lg-6 col-md-8">
                            <label class="col-md-12 no_padding"><strong>Promocode Percentage</strong></label>
                            <div class="form-group ">
                                <input type="number" name="promo_percentage" class="form-control" min="0"  value="<?php echo isset($promo_data[0]['Promo_Percentage']) ? $promo_data[0]['Promo_Percentage'] : '';?>" required="" data-parsley-required-message=" Promo percentage is required">
                            </div>
                        </div>
                          <div class="col-lg-6 col-md-8">
                              <label class="col-md-12 no_padding"><strong>Update Promo Image</strong></label>
                             <div class="form-group">
                                  <input type="file" name="promo_image" accept="image/*" class="form-control">   
                                  <input type="hidden" name="updatepromo" class="form-control" value="<?php echo isset($promo_data[0]['Promo_Image']) ? $promo_data[0]['Promo_Image'] : '';?>">   
                              </div>
                            </div>
                      </div>
                          <div class="col-lg-6 col-md-8 ">
                                <?php if(!empty($promo_data[0]['Promo_Image'])){ ?>
                                <label class="col-md-12 no_padding"><strong>Current Promo Image</strong></label>
                               <div class="form-group m-l-20">
                              
                              <img src="<?php echo base_url('/uploads/promo/'.$promo_data[0]['Promo_Image']);?>" width="50px;" height="50px;">
                             
                                </div>
                                  <?php } ?>
                            </div>
                        <div class="col-md-12">
                            <input type="hidden" name="promo_id" value="<?php echo isset($promo_data[0]['Id']) ? $promo_data[0]['Id'] : '';?>"> 
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
      $(this).parsley().isValid();   
});

  $("#promo_add").submit(function(e){
    e.preventDefault();
     $(this).parsley().validate();
    if ($(this).parsley().isValid())
    { 
      var formData = new FormData($(this)[0]);
      saveDatas(formData,'admin/home/add_promocode_action','.alert-success','');
    }else{
       return false;
    }
  
});

$(function () {
  //Datetimepicker plugin
  $('.startdate').bootstrapMaterialDatePicker({
      format: 'MM/DD/YYYY',
      clearButton: true,
      weekStart: 1,
  });
    //Datetimepicker plugin
  $('.enddate').bootstrapMaterialDatePicker({
      format: 'MM/DD/YYYY',
      clearButton: true,
      weekStart: 1,
      minDate:new Date()
  });

  $("#EndDate").change(function (){
    var startDate = document.getElementById("StartDate").value;
    var endDate = document.getElementById("EndDate").value;
      if ((Date.parse(endDate) < Date.parse(startDate))) {
          alert("End date should be greater than Start date");
          document.getElementById("EndDate").value = "";
      }  
});

});


</script>
