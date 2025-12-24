
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
  <form id="update_adv" method="POST" action="<?php echo base_url('admin/notificationMailDetailsAction');?>" data-parsley-validate="">
       <div class="col-md-12">
                    <a href="<?php echo base_url('admin/all_notification_mails');?>" class="btn btn-primary btn-round pull-right color-purple">Back</a>
              </div>
       <div class="row body">
              <div class="col-lg-6 col-md-12">
                  <label class="col-md-12 update_padding"><strong>Subject</strong></label>
                  <div class="form-group">
                      <input type="text" id="subject" value="<?php echo $notifMailData[0]['subject'] ?>" name="subject" class="form-control" placeholder="Enter Subject" required="" data-parsley-required-message="Subject is required">
                  </div>
              </div>
            </div>
              <div class="row body">
             <div class="col-lg-6 col-md-12">
                  <label class="col-md-12 update_padding"><strong>Description</strong>(please do not remove curly braces and entire text & also link)</label>
                  <div class="form-group">
                      <textarea id="elm"  name="description" class="form-control" placeholder="Enter Description" required="" data-parsley-required-message="Description is required"><?php echo $notifMailData[0]['description'] ?></textarea>
                  </div>
              </div>
          </div> 
            <div class="col-md-12">
                <br>
               <input type="hidden" name="is_submit" value="1">
                <input type="hidden" name="mailid" value="<?php echo (!empty($notifMailData[0]['id'])) ? encode($notifMailData[0]['id']) : "" ?>">
                <button class="btn btn-primary btn-round color-purple">Save Changes</button>
            </div> 
                  <div class="succMsg"> <?php  echo $this->messages->getMessageFront();?> </div>
    </div>
</form>
</div>
</section>
<script src="https://cdn.ckeditor.com/4.7.3/standard/ckeditor.js"></script>
<script type="text/javascript">
  CKEDITOR.replace( 'elm' );
  $('#elm').attr('required', '');
$(":input").bind("keyup change", function(e) {
      $(this).parsley().validate();
});

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
 //setTimeout(function(){$(".succMsg").hide()}, 3000);

 
</script>