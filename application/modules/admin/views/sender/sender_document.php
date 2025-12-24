<style type="text/css">
  .img{
    height: 220px;
    width: 220px;
  }
</style>
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
     <div class="card box-shadow ">
     <form id="documents">
             <div class="body ">
                <div class="col-md-12">
                    <a href="<?php echo base_url('admin/receiver_details/'.base64_encode($userdata[0]['Id']));?>" class="btn btn-primary btn-round pull-right color-purple">Back</a>
                </div>
                      <div class="row no-margin"> 
                      <!--   <h5 class="col-md-12"><i class="fa fa-cog"></i>Document Details</h5> -->
                          <div class="col-md-2">
                             <label class="col-md-12 update_padding"><strong>Unique Identificaiton Image </strong></label>
                                <?php   $front_image=(!empty($document_data[0]['Document_Image_Name'])) ? $document_data[0]['Document_Image_Name'] :'' ; $url=IMAGE_PATH.'identification/';?>
                             <div class="profile-image "><?php echo doc_image_check($front_image,$url,$flag=1);?> </div>
                            </div>
                           
                          </div>
                          <div class="row no-margin ">
                              <div class="col-md-8 col-sm-12 m-t-30">
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
                    <div class="m-l-25 col-md-6 alert alert-success hideme"></div>
                    <div class="m-l-25 col-md-6 alert alert-danger errmsg hideme"></div>
                </div>
               </div>
            </div>
</section>

<script type="text/javascript">
    $("#documents").submit(function(e){
    e.preventDefault();
    var formData = new FormData($(this)[0]);
    saveDatas(formData, 'admin/home/sender_documents_verified_action','.alert-success','.errmsg') 
});


</script>
