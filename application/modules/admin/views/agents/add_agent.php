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
            <form id="add_the_adv"  method="POST" action="<?php echo base_url('admin/addAgent');?>" data-parsley-validate="" enctype="multipart/form-data">
                <div class="row body mt-3">
                    <div class="col-lg-12 col-md-12">
                        <a href="<?php echo base_url('admin/all_agents');?>" class="btn btn-primary btn-round pull-right color-purple">Back</a>
                    </div>
                </div>
                     <div class="row body">
                      <div class="col-lg-6 col-md-12">
                          <label class="col-md-12 update_padding"><strong>First Name</strong></label>
                          <div class="form-group">
                              <input type="text" name="first_name" class="form-control" placeholder="Enter First Name" required="" value="" data-parsley-required-message="First Name is required">
                          </div>
                      </div>
                    
                      <div class="col-lg-6 col-md-12">
                          <label class="col-md-12 update_padding"><strong>Last Name</strong></label>
                          <div class="form-group">
                              <input type="text" name="last_name" class="form-control" placeholder="Enter Last Name" required="" value="" data-parsley-required-message="Last Name is required">
                          </div>
                      </div>
                    </div>
                     <div class="row body">
                      <div class="col-lg-6 col-md-12">
                          <label class="col-md-12 update_padding"><strong>Email</strong></label>
                          <div class="form-group">
                              <input type="email" name="email" class="form-control" placeholder="Enter Email" required="" value="" data-parsley-required-message="Email is required">
                          </div>
                      </div>
                   
                      <div class="col-lg-6 col-md-12">
                        <label class="col-md-12 update_padding"><strong>Phone Number</strong></label>
                          <div class="form-group">
                              <input type="text" name="mobile" class="form-control" id="mobile" placeholder="Enter Phone Number" required="" value="" data-parsley-required-message="Phone Number is required">
                          </div>
                      </div>
                    </div>
                    
                    <div class="row body password">
                      <div class="col-lg-6 col-md-12">
                          <label class="col-md-12 update_padding"><strong>Password</strong></label>
                          <div class="form-group">
                              <input type="password" name="password" class="form-control" placeholder="Enter Password" required="" value="" data-parsley-required-message="Password is required">
                          </div>
                      </div>

                      <div class="col-lg-6 col-md-12">
                          <label class="col-md-12 update_padding"><strong>Address</strong></label>
                          <div class="form-group">
                              <input type="text" name="address" class="form-control" placeholder="Enter Address" required="" value="" data-parsley-required-message="Address is required">
                          </div>
                      </div>

                      <!-- <div class="col-lg-6 col-md-12">
                          <label class="col-md-12 update_padding"><strong> Image</strong></label>
                          <div class="form-group">
                              <input type="file" id="fileInput" name="varification_image" class="form-control" required="" value="" data-parsley-required-message="Image field is required" accept="image/*">
                          </div>
                      </div> -->
                    </div>
                      <div class="col-md-12">
                          <br>
                          <input type="hidden" name="is_submit" value="1">
                          <button class="btn btn-primary btn-round color-purple">Save Changes</button>
                      </div> 
                      <div class="col-md-6 alert alert-success hideme rounded ml-3"></div>
              </div>
          </form>
        </div>
 </section>

<script src="https://cdn.ckeditor.com/4.7.3/standard/ckeditor.js"></script>

 <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD-HGyHx6qMaVd20nFSKMjt3pRBOexJLRY&sensor=false&libraries=places"></script> 

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
$(document).ready(function () {

    $('#add_the_adv').on('submit', function (e) {
        e.preventDefault(); // stop normal form submit

        // validate parsley first
        if (!$(this).parsley().isValid()) {
            return false;
        }

        // update CKEditor textarea before submit
        if (typeof CKEDITOR !== "undefined") {
            for (var instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
        }

        var form = $(this);
        var formData = new FormData(this);

        $.ajax({
            url: form.attr('action'),
            type: "POST",
            data: formData,
            contentType: false, // required for file upload
            processData: false, // required for file upload
            dataType: "json",
            beforeSend: function () {
                form.find('button[type="submit"]').prop('disabled', true).text('Saving...');
            },
            success: function (response) {

                form.find('button[type="submit"]').prop('disabled', false).text('Save Changes');

                if (response.status === 1 || response.success === true) {
                    $('.hideme').hide();

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        confirmButtonColor: '#6f42c1'
                    });

                    setTimeout(function () {
                        window.location.href = "<?php echo base_url('admin/all_agents');?>";
                    }, 3000);

                } 

                if (response.status === 0) {
                    $('.hideme')
                    .removeClass('alert-success')
                    .addClass('alert-danger')
                    .html(response.message)
                    .show();

                } 

            },
            error: function (xhr, response) {
                form.find('button[type="submit"]').prop('disabled', false).text('Save Changes');
                $('.hideme')
                    .removeClass('alert-success')
                    .addClass('alert-danger')
                    .html(response.message)
                    .show();
            }
        });
    });

});
</script>
