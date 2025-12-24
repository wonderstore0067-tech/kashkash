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
            <form id="add_the_adv"  method="POST" action="<?php echo base_url('admin/advertisement/addAdvertisement');?>" data-parsley-validate="" enctype="multipart/form-data">
           <div class="col-md-12">
                <a href="<?php echo base_url('admin/all_advertisements');?>" class="btn btn-primary btn-round pull-right color-purple">Back</a>
          </div>
                     <div class="row body">
                      <div class="col-lg-6 col-md-12">
                          <label class="col-md-12 update_padding"><strong>Advertisement Title</strong></label>
                          <div class="form-group">
                              <input type="text" name="advertisement_title" class="form-control" placeholder="Enter Advertisement Name" required="" value="" data-parsley-required-message="Advertisement Name is required">
                          </div>
                      </div>
                    </div>
                     <div class="row body">
                      <div class="col-lg-6 col-md-12">
                          <label class="col-md-12 update_padding"><strong>Advertisement Sub Title</strong></label>
                          <div class="form-group">
                              <input type="text" name="advertisement_subtitle" class="form-control" placeholder="Enter Advertisement Name" required="" value="" data-parsley-required-message="Advertisement Name is required">
                          </div>
                      </div>
                    </div>
                     <div class="row body">
                        <div class="col-lg-6 col-md-12">
                          <label class="col-md-12 update_padding"><strong>Advertisement Description</strong></label>
                          <div class="form-group">
                               <textarea id="elm" name="description" class="form-control" placeholder="Enter Description" required="" data-parsley-required-message="Description is required"></textarea>
                          </div>
                      </div>
                    </div>
                    <div class="row body">
                    <div class="col-lg-6 col-md-12">
                      <label class="col-md-12 update_padding"><strong>Advertisement Type</strong></label>
                      <div class="form-group">
                           <select  name="advertisement_type" id="advertisement_type" class="form-control show-tick" required >
                             <option value="">-Please select-</option>
                             <option value="location_wise" > Location Wise</option>
                             <option value="default"   >Default</option>
                           </select>
                      </div>
                  </div>
                </div>
                    <div class="row body advAddress">
                      <div class="col-lg-6 col-md-12">
                          <label class="col-md-12 update_padding"><strong>Advertisement Address</strong></label>
                          <div class="form-group">
                              <input type="text" name="adv_address" class="form-control" id="adv_address" placeholder="Enter Advertisement Address" required="" value="" data-parsley-required-message="Advertisement Address is required">
                              <input  type="hidden" name="adv_address_lat" id="adv_address_lat"   />
                             <input  type="hidden" name="adv_address_lang" id="adv_address_lang"/>
                          </div>
                      </div>
                    </div>
                     <div class="row body">
                      <div class="col-lg-6 col-md-12">
                          <label class="col-md-12 update_padding"><strong> Advertisement Image</strong></label>
                          <div class="form-group">
                              <input type="file" id="fileInput" name="advImg" class="form-control" required="" value="" data-parsley-required-message="Image field is required" accept="image/*">
                             <!--  <a href="javascript:void(0)" id="fileButton" class="btn btn-primary">Add Image</a> -->
                          </div>
                      </div>
                    </div>
                      <div class="col-md-12">
                          <br>
                          <input type="hidden" name="is_submit" value="1">
                          <button class="btn btn-primary btn-round color-purple">Save Changes</button>
                      </div> 
                      <div class="col-md-6 alert alert-success hideme"></div>
              </div>
          </form>
        </div>
 </section>

<!-- <script type="text/javascript">
  $.get("http://192.168.1.160/etippers/admin/advertisement/get_country", function(data, status){
    console.log("/////////////....................................///////////kirtisagar")
    // console.log("Data: " + data + "\nStatus: " + status);
    var response = JSON.parse(data);
    console.log(response)
    $("#advertisement_country").append('<option>thisisit</option>');
  });
</script> -->
<script src="https://cdn.ckeditor.com/4.7.3/standard/ckeditor.js"></script>

 <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD-HGyHx6qMaVd20nFSKMjt3pRBOexJLRY&sensor=false&libraries=places"></script> 

<script type="text/javascript">
  // when advertisement type default logic
  $('#advertisement_type').on('change', function() {
    var advertisement_type=  $(this).val();
   if(advertisement_type =='default'){
    //$('#adv_address').removeAttr('required');
      $(".advAddress").hide();
   }else{
     $(".advAddress").show();
   }
});

CKEDITOR.replace( 'elm' );
$('#elm').attr('required', '');
  //deal with copying the ckeditor text into the actual textarea
CKEDITOR.on('instanceReady',function (){
    $.each(CKEDITOR.instances, function (instance) {
        CKEDITOR.instances[instance].document.on("keyup", CK_jQ);
        CKEDITOR.instances[instance].document.on("paste", CK_jQ);
        CKEDITOR.instances[instance].document.on("keypress", CK_jQ);
        CKEDITOR.instances[instance].document.on("blur", CK_jQ);
        CKEDITOR.instances[instance].document.on("change", CK_jQ);
    });
});

function CK_jQ(){
    for (instance in CKEDITOR.instances){
        CKEDITOR.instances[instance].updateElement();
    }
}
$(":input").bind("keyup change", function(e) {
      $(this).parsley().validate();
})

$("#fileButton").click(function(){
  $("#fileInput").click();
})

//Google palce api
google.maps.event.addDomListener(window,'load',function(){
  // var defaultBounds = new google.maps.LatLngBounds(
  // new google.maps.LatLng(22.7196,75.8577));
  // var options = {
  //   bounds: defaultBounds,
  //   //types: ['establishment'],
  //   componentRestrictions: {country: "in"}
  // }; 
    var autocomplete="adv_address";
    var lat ="adv_address_lat";
    var lang="adv_address_lang";
      var places = new google.maps.places.Autocomplete(document.getElementById(autocomplete));
        google.maps.event.addListener(places,'place_changed',function(){
            var place = places.getPlace();
            var address = place.formatted_address;
            // var latitude = place.geometry.location.lat();
            // var longitude = place.geometry.location.lng();
            //document.getElementById(autocompleteid).value = address;
            document.getElementById(lat).value = place.geometry.location.lat();
            document.getElementById(lang).value = place.geometry.location.lng();
            //var mesg = "Address: " + address;
            // mesg += "\nLatitude: " + latitude;
            // mesg += "\nLongitude: " + longitude;
            //alert(mesg);
        });
   });

</script>