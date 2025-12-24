<style type="text/css">
  /*.logoimg{
    width: 100px;
    height: 50px;
  }*/
</style>

<section class="content invoice">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-6 col-sm-12 heading_pt13">
                <h2> <?php echo $title;?> </h2>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-12">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard');?>"><i class="zmdi zmdi-home"></i> Dashobard</a></li>
                    <li class="breadcrumb-item"><?php echo $title;?></li>  
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card box-shadow">
                    <div class="header">
                      <h6"><i class="fa fa-edit"></i> Update <?php echo $title;?></h6>
                    </div>
                </div>
                <div class="tab-content">  
                    <div role="tabpanel" class="tab-pane active" id="notes" aria-expanded="true">
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-12">
                                <div class="card box-shadow">  
                                    <div class="body">     
                                    <form class="form-horizontal" id="logosetting"  role="form">
                                        <div class="form-group">
                                          <label class="col-md-12"><strong>Logo</strong></label>
                                          <div class="col-md-12">                 
                                              <input type="file" name="logo_image" accept="image/*">
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-md-12"><strong> Favicon</strong></label>
                                          <div class="col-md-12">              
                                              <input type="file" name="favicon_image" accept="image/*">
                                          </div>
                                        </div>
                                         <div class="col-lg-6 col-md-12">
                                            <button class="btn btn-primary btn-round color-purple">Save</button>
                                        </div>
                                      </form>
                                      <br>
                                   <div class="m-l-25 col-md-10 alert alert-success hideme"></div>
                                   <div class="m-l-25 col-md-10 alert alert-danger hideme"></div>
                                    
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="card box-shadow"> 
                                    <div class="body">
                                      <div class="row">
                                        <div class="col-md-6">
                                           <h6 class="uppercase"><i class="fa fa-image"></i> Current Logo</h6>
                                            <img class="logoimg responsive" src="<?php echo base_url('assets/images/'.$general_data[0]['slogo']);?>" >
                                        </div>
                                        <div class="col-md-6">
                                           <h6 class="uppercase"><i class="fa fa-image"></i> Current Favicon</h6>
                                         <img class="logoimg responsive" src="<?php echo base_url('assets/images/'.$general_data[0]['sfavicon']);?>" >
                                          
                                        </div>
                                      </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>                  
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
  $('.webcontent').addClass('active');
  $('.iconsetting').addClass('active');

  $("#logosetting").submit(function(e){
    e.preventDefault();
    var formData = new FormData($(this)[0]);
    saveDatas(formData,'admin/admin/logo_setting_action','.alert-success','.alert-danger')
  
});
 
</script>