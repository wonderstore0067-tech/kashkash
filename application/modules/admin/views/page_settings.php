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
                                    <h6 class="capitalize"> <?php echo $title;?></h6>        
                                    <form class="form-horizontal" action="" id="settings" method="post"  role="form">
                                        <div class="form-group">
                                          <div class="col-md-12 no_padding">
                                           <div class="">                    
                                              <input type="file" id="exampleInputFile" name="file_settings" accept="application/pdf">                             
                                            </div>

                                          </div>
                                        </div>
                                         <div class="col-lg-6 col-md-12 no_padding">
                                           <input type="hidden" name="option_type" value="<?php echo $this->uri->segment(3);?>">  
                                            <button class="btn btn-primary deactivate_br btn-round color-purple">Save</button>
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
                                        <h6 class="capitalize">Current <?php echo $title;?></h6>
                                        <div class="col-md-12 no_padding">
                                          <a href="<?php echo base_url('uploads/static_contents/'.$option_data[0]['Option_Value']);?>" target="_blank"><img src="<?php echo base_url('assets/images/pdf.png');?>" alt="icon_pdf">
                                          </a>
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
  var urlsegment='<?php echo $this->uri->segment(3);?>';
 
  if(urlsegment==1){
  $('.setings').addClass('active');
  $('.trms_setting').addClass('active');
  }else if(urlsegment==2){
  $('.setings').addClass('active');
  $('.privcypolicy').addClass('active');
  }else if(urlsegment==3){
  $('.setings').addClass('active');
  $('.aboutus').addClass('active');
  }
  

  $("#settings").submit(function(e){
    e.preventDefault();
    var formData = new FormData($(this)[0]);
    saveDatas(formData,'admin/admin/page_settings_action','.alert-success','.alert-danger')
  
});
 
</script>