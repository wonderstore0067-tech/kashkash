<style type="text/css">
.highcharts-button { display: none; }
.highcharts-credits { display: none; }
 .height-427{height: 470px; overflow-y: auto;}
</style>
<!-- Main Content -->
<section class="content home">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-12 heading_pt13">
                <h2>Dashboard 
                </h2>
            </div>            
            <div class="col-lg-7 col-md-7 col-sm-12 text-right">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard');?>"><i class="zmdi zmdi-home"></i> Dashboard</a></li>  
                </ul>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12">
                 <div class="row clearfix">
                      <div class="col-lg-4 col-md-6">
                        <div class="card box-shadow top_counter">
                            <div class="body">
                                <div class="icon gradient_box">
                                  <!-- <i class="icon-add_money dark_b"></i> -->
                                   <img src="<?php echo base_url();?>assets/images/admin managment-1.png">
                                </div>

                                <?php
                                    if(get_role_id($loginuser_id) == 1){ 
                                ?>

                                  <a href=" <?php echo base_url("admin/all_senders");?>">
                                    <div class="content">
                                        <div class="text ">Total senders </div>
                                       <h5 class="number"> <?php echo isset($sender_data[0]['sender_count']) ? $sender_data[0]['sender_count']: '0';?></h5> 
                                    </div>
                                  </a>
                                <?php
                                    }else{
                                ?>
                                <div class="content">
                                    <div class="text ">Total senders </div>
                                    <h5 class="number"> <?php echo isset($sender_data[0]['sender_count']) ? $sender_data[0]['sender_count']: '0';?></h5> 
                                </div>
                                <?php
                                    }
                                ?>
                               
                            </div>
                        </div>
                    </div>
                   <div class="col-lg-4 col-md-6">
                        <div class="card box-shadow top_counter">
                            <div class="body">
                                <div class="icon gradient_box">
                                  <!-- <i class="icon-cashout"></i> -->
                                 <img src="<?php echo base_url();?>assets/images/admin managment-1.png">
                                 </div>

                                 
                                <?php
                                    if(get_role_id($loginuser_id) == 1){ 
                                ?>

                                    <a href=" <?php echo base_url("admin/all_receivers");?>">
                                    <div class="content">
                                        <div class="text ">Total Receivers</div>
                                      <h5 class="number"> <?php echo isset($receiver_data[0]['reciver_count']) ? $receiver_data[0]['reciver_count']: '0';?></h5>   
                                    </div>
                                  </a>

                                <?php
                                    }else{
                                ?>
                                <div class="content">
                                    <div class="text ">Total Receivers</div>
                                    <h5 class="number"> <?php echo isset($receiver_data[0]['reciver_count']) ? $receiver_data[0]['reciver_count']: '0';?></h5>   
                                </div>
                                <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                     <div class="col-lg-4 col-md-6">
                        <div class="card box-shadow top_counter">
                            <div class="body">
                                <div class="icon gradient_box">
                                  <!-- <i class="fa fa-upload"></i> -->
                                  <img src="<?php echo base_url();?>assets/images/total charges.png"> 
                                </div>
                                
                                <?php
                                    if(get_role_id($loginuser_id) == 1){ 
                                ?>
                                  <a href=" <?php echo base_url("admin/all_transactions");?>">
                                    <div class="content">
                                        <div class="text ">Total Transactions</div>
                                      <h5 class="number"> <?php echo isset($transcation_data[0]['v_count']) ? $transcation_data[0]['v_count']: '0';?></h5>   
                                    </div> 
                                  </a>

                                  <?php
                                    }else{
                                ?>
                                <div class="content">
                                    <div class="text ">Total Transactions</div>
                                    <h5 class="number"> <?php echo isset($transcation_data[0]['v_count']) ? $transcation_data[0]['v_count']: '0';?></h5>   
                                </div> 
                                <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </div>  
                </div>
            </div>
           

        </div>        
    </div>
</section>




