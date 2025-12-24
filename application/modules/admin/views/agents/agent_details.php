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
                        <div class="profile-image "> <img class="imgsize" id="img_tag" src="<?php echo base_url();?>uploads/user/<?php echo $userdata[0]['Profile_Pic'] ?>" alt="" onerror="this.src='<?php echo base_url();?>assets/images/default.jpg';"> </div>
                        <div>
                            <h4 class="m-b-0"><strong><?php echo isset($userdata) ? ucwords(strtolower($userdata[0]['FirstName'].' '.$userdata[0]['LastName'])) : '';?></strong> </h4>
                            <span class=""><?php echo isset($userdata[0]['Email']) ? $userdata[0]['Email'] : '';?></span><br>
                        </div>
                        <div>
                           <!--  <a href="<?php echo base_url('admin/sender_documents_verified/'.base64_encode($userdata[0]['Id']));?>" class="btn btn-primary btn-round color-purple">View Documents </a>  -->
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
                        <div class="col-lg-6 col-md-12">
                            <label class="col-md-12 update_padding"><strong>First Name</strong></label>
                            <div class="form-group">
                                <input type="text" name="fname" class="form-control" placeholder="First Name" required="" value="<?php echo isset($userdata) ? $userdata[0]['FirstName']: '';?>" data-parsley-required-message="First Name is required">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <label class="col-md-12 update_padding"><strong>Last Name</strong></label>
                            <div class="form-group">
                                <input type="text" name="lname" class="form-control" placeholder="Last Name" required="" value="<?php echo isset($userdata) ? $userdata[0]['LastName']: '';?>" data-parsley-required-message="Last Name is required">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <label class="col-md-12 update_padding"><strong>Email</strong></label>
                            <div class="form-group">
                                <input type="text" name="email" class="form-control" placeholder="Email" required="" value="<?php echo isset($userdata) ? $userdata[0]['Email']: '';?>">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <label class="col-md-12 update_padding"><strong>Mobile</strong></label>
                            <div class="form-group">
                                <input type="text" name="mobile" class="form-control" placeholder="Mobile" required="" value="<?php echo isset($userdata) ? $userdata[0]['Mobile_No']: '';?>">
                            </div>
                        </div>
                            <div class="col-lg-6 col-md-12">
                            <label class="col-md-12 update_padding"><strong>Address</strong></label>
                            <div class="form-group">
                                <input type="text" name="address" class="form-control" placeholder="Address" value="<?php echo isset($userdata) ? $userdata[0]['Address']: '';?>">
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
           
            <div class="m-l-25 col-md-6 alert alert-success succ hideme"></div>
            <div class="m-l-25 col-md-6 alert alert-danger errmsg1 hideme"></div>
        
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
      saveDatas(formData,'admin/home/agent_profile_update','.alert-success','')
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
    saveDatas(formData, 'admin/home/sender_documents_verified_action','.succ','.succ') 
});
</script>