<!-- <style type="text/css">
 .imgsize{
     height: 170px;
    width: 170px;
    }
</style>
 -->
<section class="content profile-page">
                    <div class="card box-shadow">
                    <form id="update_adv" data-parsley-validate="">
                   <div class="row body">
                    <label class="col-md-12"><h6 class="capitalize"><i class="fa fa-cog"></i> Update Advertisement Details</h6></label> <br><br><br><br>  

                                <div class="col-lg-4 col-md-12">
                                    <label class="col-md-12 update_padding"><strong>Advertisement Name</strong></label>
                                    <div class="form-group">
                                        <input type="text" id="name" value="<?php echo $advData[0]['Advertisement_Title'] ?>" name="Advertisement_Name" class="form-control" placeholder="Enter Advertisement Title" required="" value="" data-parsley-required-message="Advertisement Title is required">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12">
                                    <label class="col-md-12 update_padding"><strong>Add Advertisement Image</strong></label>
                                    <div class="form-group">
	                            	<?php
	              					      $qrcode_url= IMAGE_PATH.'advertisement_img/'.$advData[0]['Advertisement_Image'];
	                            	 ?>
	                            	 	<input type="hidden" name="Id" value="<?php echo $advData[0]['Id']; ?>">
                                        <img id="img_tag" src="<?php echo $qrcode_url ?>" width="250px">
                                        <input type="hidden" id="fileFack" name="fileFack" value="<?php echo $advData[0]['Advertisement_Image']; ?>">
                                        <input type="file" id="fileInput" name="advImg" class="form-control" value="" hidden="">
                                        <a href="javascript:void(0)" id="fileButton" class="btn btn-primary">Change Image</a>
                                    </div>
                                </div>
                            </div> 
                                <div class="col-md-12">
                                    <br>
                                   <input type="hidden" name="userid" value="">
                                    <button class="btn btn-primary btn-round color-purple">Save Changes</button>
                                </div> 
                                <div class="col-md-6 alert alert-success hideme"></div>
                      </div>
                  </form>
                </div>
</section>

<script type="text/javascript">
$(":input").bind("keyup change", function(e) {
      $(this).parsley().validate();
})

$("#fileButton").click(function(){
  $("#fileInput").click();
})

$("#fileInput").change(function(){
	$("#fileFack").val("");
	$("#img_tag").hide();
});


$("#update_adv").submit(function(e){
  e.preventDefault();
  // $(this).parsley().validate();
  //   if ($(this).parsley().isValid() || $("#fileFack").val() != "" && $("#name") != "")
  //   {
      var formData = new FormData($(this)[0]);
      console.log(formData);
      saveDatas(formData,'admin/advertisement/updateAdvertisement','.alert-success','')
    // }else{
    //        return false;
    // }

});
 
 //alert(<?php echo $this->uri->segment(3);?>)
</script>