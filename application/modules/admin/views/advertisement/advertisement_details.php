<!-- <style type="text/css">
 .imgsize{
     height: 170px;
    width: 170px;
    }
</style>
 -->
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
    <div class="card box-shadow">
    <form id="add_the_adv"  method="POST" action="<?php echo base_url('admin/advertisement/updateAdvertisement');?>" data-parsley-validate="" enctype="multipart/form-data">
    <div class="col-md-12">
        <a href="<?php echo base_url('admin/all_advertisements');?>" class="btn btn-primary btn-round pull-right color-purple">Back</a>
  </div>
   <div class="row body">
                <div class="col-lg-6 col-md-12">
                    <label class="col-md-12 update_padding"><strong>Advertisement Title</strong></label>
                    <div class="form-group">
                        <input type="text" name="advertisement_title" class="form-control" placeholder="Enter Advertisement Name" required="" value="<?php echo (!empty($advData[0]['Advertisement_Title'])) ? $advData[0]['Advertisement_Title'] : '';?>" name="Advertisement_Name" data-parsley-required-message="Advertisement Name is required">
                    </div>
                </div>
              </div>
               <div class="row body">
                <div class="col-lg-6 col-md-12">
                    <label class="col-md-12 update_padding"><strong>Advertisement Sub Title</strong></label>
                    <div class="form-group">
                        <input type="text" name="advertisement_subtitle" class="form-control" placeholder="Enter Advertisement Name" required="" value="<?php echo $advData[0]['Advertisement_Subtitle'] ?>" name="Advertisement_Name" data-parsley-required-message="Advertisement Sub Title is required">
                    </div>
                </div>
              </div>
               <div class="row body">
                  <div class="col-lg-6 col-md-12">
                    <label class="col-md-12 update_padding"><strong>Advertisement Description</strong></label>
                    <div class="form-group">
                         <textarea id="elm" name="description" class="form-control" placeholder="Enter Description" required="" data-parsley-required-message="Description is required"><?php echo (!empty($advData[0]['Description'])) ? $advData[0]['Description'] : '';?></textarea>
                    </div>
                </div>
              </div>
                 <div class="row body">
                  <div class="col-lg-6 col-md-12">
                    <label class="col-md-12 update_padding"><strong>Advertisement Type</strong></label>
                    <div class="form-group">
                         <select  name="advertisement_type"  id="advertisement_type"  class="form-control show-tick" required >
                           <option value="">-Please select-</option>
                           <option value="location_wise" <?php echo (!empty($advData[0]['Advertisement_Type'] && ($advData[0]['Advertisement_Type']=="location_wise"))) ? 'selected' : '';?>> Location Wise</option>
                           <option value="default"  <?php echo (!empty($advData[0]['Advertisement_Type'] && ($advData[0]['Advertisement_Type']=="default"))) ? 'selected' : '';?> >Default</option>
                         </select>
                    </div>
                </div>
              </div>
              <div class="row body advAddress">
                <div class="col-lg-6 col-md-12">
                    <label class="col-md-12 update_padding"><strong>Advertisement Address</strong></label>
                    <div class="form-group">
                        <input type="text" name="adv_address" class="form-control" id="adv_address" placeholder="Enter Advertisement Address" value="<?php echo (!empty($advData[0]['Address'])) ? $advData[0]['Address'] : '';?>" data-parsley-required-message="Advertisement Address is required">
                        <input  type="hidden" name="adv_address_lat" id="adv_address_lat"  value="<?php echo (!empty($advData[0]['Lat'])) ? $advData[0]['Lat'] : '';?>" />
                       <input  type="hidden" name="adv_address_lang" id="adv_address_lang" value="<?php echo (!empty($advData[0]['Lang'])) ? $advData[0]['Lang'] : '';?>"/>
                    </div>
                </div>
              </div> 
            
               <div class="row body">
                <div class="col-lg-6 col-md-12">
                    <label class="col-md-12 update_padding"><strong> Advertisement Image</strong></label>
                   <div class="form-group">
                    <?php
                    $adv_url= IMAGE_PATH.'advertisement_img/'.$advData[0]['Advertisement_Image'];
                     ?>
                           <p> <img id="img_tag" src="<?php echo $adv_url ?>" width="250px"></p>
                            <input type="hidden" id="fileFack" name="fileFack" value="<?php echo (!empty($advData[0]['Advertisement_Image'])) ? $advData[0]['Advertisement_Image'] : '';?>">
                            <input type="file" id="fileInput" class="fileInput" name="advImg" class="form-control" accept="image/*">
                            <!-- <a href="javascript:void(0)" id="fileButton" class="btn btn-primary">Change Image</a> -->
                      </div>
                </div>
              </div>

                <div class="col-md-12">
                    <br>
                    <input type="hidden" name="is_submit" value="1">
                     <input type="hidden" name="Id" value="<?php echo (!empty($advData[0]['Id'])) ? encode($advData[0]['Id']) : '';?>">
                    <button class="btn btn-primary btn-round color-purple">Save Changes</button>
                </div> 
                <div class="col-md-6 alert alert-success hideme"></div>
      </div>
  </form>
</div>
</section>
<script src="https://cdn.ckeditor.com/4.7.3/standard/ckeditor.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD-HGyHx6qMaVd20nFSKMjt3pRBOexJLRY&sensor=false&libraries=places"></script> 

<script type="text/javascript">
  // when advertisement type default logic

  $('#advertisement_type').on('change', function() {
 var advertisement_type=  $(this).val();
 
   if(advertisement_type =='default'){
    $('#adv_address').removeAttr('required');
      $(".advAddress").hide();
   }else{
    $('#adv_address').addAttr('required');
     $(".advAddress").show();
   }
});

 var advertisement_type=  $('#advertisement_type').val();
 if(advertisement_type =='default'){
   $(".advAddress").hide();
 }else{
$(".advAddress").show();
 }

CKEDITOR.replace( 'elm' );
$('#elm').attr('required', '');

  //deal with copying the ckeditor text into the actual textarea
CKEDITOR.on('instanceReady', function (){
    $.each(CKEDITOR.instances, function (instance) {
        CKEDITOR.instances[instance].document.on("keyup", CK_jQ);
        CKEDITOR.instances[instance].document.on("paste", CK_jQ);
        CKEDITOR.instances[instance].document.on("keypress", CK_jQ);
        CKEDITOR.instances[instance].document.on("blur", CK_jQ);
        CKEDITOR.instances[instance].document.on("change", CK_jQ);
    });
});

function CK_jQ() {
    for (instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    }
}
$(":input").bind("keyup change", function(e) {
      $(this).parsley().validate();
})

$("#fileButton").click(function(){
  $("#fileInput").click();
})

// $("#add_the_adv").submit(function(e){
//   e.preventDefault();
//   $(this).parsley().validate();
//     if ($(this).parsley().isValid())
//     {
//       var formData = new FormData($(this)[0]);
//       saveDatas(formData,'admin/advertisement/updateAdvertisement','.alert-success','')
//     }else{
//            return false;
//     }

// });
 
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

//Google palce api
google.maps.event.addDomListener(window,'load',function(){
  // var defaultBounds = new google.maps.LatLngBounds(
  // new google.maps.LatLng(22.7196,75.8577));
  // var options = {
  //   bounds: defaultBounds,
  //   //types: ['establishment'],
  //   componentRestrictions: {country: "in"}
  // }; 
    autocomplete="adv_address";
    lat ="adv_address_lat";
    lang="adv_address_lang";
      var places = new google.maps.places.Autocomplete(document.getElementById(autocomplete));
        google.maps.event.addListener(places,'place_changed',function(){
            var place = places.getPlace();
            //console.log(place);
            var address = place.formatted_address;
           
            //document.getElementById(autocompleteid).value = address;
            document.getElementById(lat).value = place.geometry.location.lat();
            document.getElementById(lang).value = place.geometry.location.lng();
            var mesg = "Address: " + address;
           // mesg += "\nLatitude: " + latitude;
            //mesg += "\nLongitude: " + longitude;
             //alert(mesg);
            
        });
   });

</script>