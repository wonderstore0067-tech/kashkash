
<!-- Top Bar -->
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
            <div class="col-md-12">
                <div class="card box-shadow parents-list">                    
                    <div class="header">   
                    </div>
                    <div class="body tablewidth">
                        <div class="table-responsive">
                            <?php //echo $product_id = $this->uri->segment(4);?>
                            <table id="wd_details" class="table table-bordered table-striped table-hover m-b-0 js-basic-example dataTabl ">
                              
                             <tbody>
                                        <tr class="bold">
                                                <td>First Name </td>
                                                <td>
                                                <?php echo isset($feedback_data) ? ucwords(strtolower($feedback_data['First_Name'])) : '';?>
                                                
                                                </td>
                                            </tr>
                                            <tr class="bold">
                                                <td>Last Name </td>
                                                <td>
                                                <?php echo isset($feedback_data) ? ucwords(strtolower($feedback_data['Last_Name'])) : '';?>
                                                
                                                </td>
                                            </tr>
                                             <tr class="bold">
                                                <td>Email </td>
                                                <td>
                                                <?php echo isset($feedback_data) ? ucwords(strtolower($feedback_data['Email'])) : '';?>
                                                
                                                </td>
                                            </tr>
                                             <tr class="bold">
                                                <td>Subject </td>
                                                <td>
                                                <?php echo isset($feedback_data) ? ucwords(strtolower($feedback_data['Subject'])) : '';?>
                                                
                                                </td>
                                            </tr>
                                             <tr class="bold">
                                                <td>Message </td>
                                                <td>
                                                <?php echo isset($feedback_data) ? ucwords(strtolower($feedback_data['Message'])) : '';?>
                                                
                                                </td>
                                            </tr>
                                              <tr class="bold">
                                                <td>Last Name </td>
                                                <td><?php echo isset($feedback_data) ? dateFormat($feedback_data['Creation_Date_Time']) : '';?> </td>
                                            </tr>
                                                 
                                 </tbody>
                            </table>
                        </div>

                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</section>
