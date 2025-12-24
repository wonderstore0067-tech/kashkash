<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">
  <?php $general_data = get_generalsettings();?>
<title> <?php echo isset($general_data[0]['Sitetitle']) ? $general_data[0]['Sitetitle'] : 'Kash Kash';?></title>
 <?php $favicon =  isset($general_data[0]['sfavicon']) ? $general_data[0]['sfavicon'] : '';?>
<link rel="icon" href="<?php echo base_url('assets/img/'.$favicon);?>" type="image/x-icon"> 
<!--  <link rel="icon" href="<?php echo base_url('assets/images/dapplepay_favicon.png');?>" type="image/x-icon"> -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/jvectormap/jquery-jvectormap-2.0.3.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/morrisjs/morris.min.css" />
<!-- Custom Css -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/main.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/custom.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/icomoon Fonts Icons/style.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/color_skins.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/sweetalert.css">
<link rel="stylesheet" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/themes/redmond/jquery-ui.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<link rel="stylesheet" href="<?php echo base_url();?>/assets/css/datatables.min.css"> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/parsley.css">

<!-- Fav-ivon Link -->
<!-- <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon">
<link rel="icon" href="assets/images/favicon.ico" type="image/x-icon"> -->


<!-- JQuery DataTable Css -->
<!--  <link href="<?php //echo base_url();?>assets/plugins/jquery-datatable/dataTables.bootstrap4.min.css" rel="stylesheet">   -->

<!-- Bootstrap Material Datetime Picker Css -->
 <link href="<?php echo base_url();?>assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" /> 
<!-- 
<link href="<?php //echo base_url();?>assets/plugins/datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" /> -->
<!-- Dropzone Css -->
<link href="<?php echo base_url();?>assets/plugins/dropzone/dropzone.css" rel="stylesheet">
<!-- Bootstrap Select Css -->
<link href="<?php echo base_url();?>assets/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />


<!-- Custom Css -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/main.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/color_skins.css">

<script src="<?php echo base_url();?>assets/bundles/libscripts.bundle.js"></script>
<!-- Lib Scripts Plugin Js ( jquery.v3.2.1, Bootstrap4 js) --> 
<script src="<?php echo base_url();?>assets/bundles/vendorscripts.bundle.js"></script> 

<script src="<?php echo base_url();?>assets/js/datatables.js"></script> 

<!-- <script src="<?php //echo base_url();?>assets/bundles/datatablescripts.bundle.js"></script>
<script src="<?php //echo base_url();?>assets/js/pages/tables/jquery-datatable.js"></script> -->

</head>
<body class="theme-blush authentication sidebar-collapse">
<!-- Page Loader -->
<style type="text/css">
    .zmdi-search{
        cursor: pointer;
    }
</style>
 <div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img class="zmdi-hc-spin" src="<?php echo base_url();?>assets/images/logo.svg" width="58" height="58" alt="Oreo"></div>
        <p>Please wait...</p>        
    </div>
</div> 

<!-- Overlay For Sidebars -->
<div class="overlay"></div>
 <script type="text/javascript">
    var  BASEURL = "<?php echo base_url(); ?>" ;
  </script>
<!-- Top Bar -->
<nav class="navbar p-l-5 p-r-5">
    <ul class="nav navbar-nav navbar-left">
        <li>
            <div class="navbar-header">
                <a href="javascript:void(0);" class="bars"></a>
                <?php $logo = isset($general_data[0]['slogo']) ? $general_data[0]['slogo'] : '';?>
                 <!-- <a class="navbar-brand" href="#" style="cursor:default;margin-top: 10px;"><img src="<?php echo base_url('uploads/'.$logo );?>" height="50" alt=""><span class="m-l-10"></span></a>  -->
                 <a class="navbar-brand" href="#" style="cursor:default;margin-top: 10px;"><img src="<?php echo base_url('assets/img/fav-icon.png');?>" height="50" alt=""><span class="m-l-10"></span></a> 

            </div>
        </li>
        <!-- <li><a href="javascript:void(0);" class="ls-toggle-btn" data-close="true"><i class="zmdi zmdi-swap" style="margin-top: 9px;"></i></a></li> -->
  
        <li class="float-right">
            <!-- <a href="javascript:void(0);" class="fullscreen hidden-sm-down" data-provide="fullscreen" data-close="true"><i class="zmdi zmdi-fullscreen"></i></a> -->
            <a href="<?php echo base_url('admin/admin/logout');?>" class="mega-menu" data-close="true" data-toggle="tooltip" title="Logout"><i class="zmdi zmdi-power" style="margin-top: 21px;"></i></a>
            <!-- <a href="javascript:void(0);" class="js-right-sidebar" data-close="true"><i class="zmdi zmdi-settings zmdi-hc-spin"></i></a> -->
        </li>
        <li class="hidden-sm-down float-right search_margin">
            
        </li>        
    </ul>
</nav>
<!-- Left Sidebar -->
<aside id="leftsidebar" class="sidebar">
    <!-- <ul class="nav nav-tabs">
        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#dashboard"><i class="zmdi zmdi-home"></i></a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#user">Professors</a></li>
    </ul> -->
    <div class="tab-content">
        <div class="tab-pane stretchRight active" id="dashboard">
            <div class="menu">
                <ul class="list">
                    <li>
                        <?php  $loginuser_data= getuserdetails();?>
                        <div class="user-info">
                            <div class="image"><a href="<?php echo base_url('admin/profile_setting');?>"> <img src="<?php echo base_url();?>uploads/user/<?php echo $loginuser_data['Profile_Pic'] ?>" alt="" onerror="this.src='<?php echo base_url();?>assets/images/default.jpg';"> </a></div>
                            <div class="detail">  
                                <h4><?php echo ucwords(strtolower($loginuser_data['FullName']));?></h4>
                                <small><?php 
                                // echo get_role_id($loginuser_data['Id']);
                                echo get_role_name($loginuser_data['Id']);?></small>
                            </div>
                        </div>
                    </li>
                        <?php  
                        $reqactive = $_SERVER['REQUEST_URI']; 
                        ?>     

                    <li class="<?php echo strpos($reqactive, 'dashboard') !== false ? 'active' : '';?>">
                      <a href="<?php echo site_url('admin/dashboard');?>">
                        <!-- <i class="zmdi zmdi-home"></i> -->
                        <img src="<?php echo base_url();?>assets/images/Dashboard.png">
                        <span>Dashboard</span>
                      </a>
                    </li> 
                        
                    <?php

                      if(get_role_id($loginuser_data['Id']) == 1){ 
                    ?>
                  
                    <li class="sendermgmt">
                      <a href="<?php echo site_url('admin/all_senders');?>" >
                        <!-- <i class="icon-user  "></i> -->
                        <img src="<?php echo base_url();?>assets/images/user mang.png">
                        <span>Sender Management</span>
                      </a>
                       
                    </li>  
              
                    <li class="receivermgmt">
                      <a href="<?php echo site_url('admin/all_receivers');?>">
                        <!-- <i class="icon-add_user_phonebook"></i> -->
                        <img src="<?php echo base_url();?>assets/images/merchant mang.png">
                        <span>Receiver Management</span> 
                      </a> 
                    </li> 

                    
                    <li class="agentmgmt ">
                      <a href="<?php echo site_url('admin/all_agents');?>">
                        <!-- <i class="icon-add_user_phonebook"></i> -->
                        <img src="<?php echo base_url();?>assets/images/merchant mang.png">
                        <span>Agent Management</span> 
                      </a> 
                    </li> 
               
                    <li class="advertisement_mgmt">
                      <a href="<?php echo site_url('admin/all_advertisements');?>">
                        <!-- <i class="icon-add_user_phonebook"></i> -->
                        <img src="<?php echo base_url();?>assets/images/merchant mang.png">
                        <span>Advertisement Management</span> 
                      </a> 
                    </li> 

                    <?php

                      }
                    ?>

                    <?php

                      if(get_role_id($loginuser_data['Id']) == 5){ 
                    ?>
                     <li class="trxns ">
                      <a href="<?php echo site_url('admin/agent_search_transaction');?>" >
                        <!-- <i class="fa fa-money"></i> -->
                        <img src="<?php echo base_url();?>assets/images/transactions.png">
                        <span> Transactions</span> 
                      </a> 
                    </li> 

                    <?php

                      }
                    ?>

                    <?php

                      if(get_role_id($loginuser_data['Id']) == 1){ 
                    ?>

                     <li class="trxs ">
                      <a href="<?php echo site_url('admin/all_transactions');?>" >
                        <!-- <i class="fa fa-money"></i> -->
                        <img src="<?php echo base_url();?>assets/images/transactions.png">
                        <span> Transactions</span> 
                      </a> 
                    </li> 

                     <li class="sendmoney">
                    <a href="<?php echo site_url('admin/send_money');?>" class="">
                      <!-- <i class="icon-chat_send_money"></i> -->
                      <img src="<?php echo base_url();?>assets/images/send money.png">
                      <span> Tips To/From</span>
                    </a>     
                  </li> 
                    <li class="referral_mgmt">

                        <a href="#" class="menu-toggle waves-effect waves-block">
                            <span> <i class="fa fa-share" aria-hidden="true"></i> User Referrals Management</span> 
                        </a>                         
                        <ul class="ml-menu">
                            <li class="referrals_listing">
                              <a href="<?php echo site_url('admin/referrals_listing');?>">
                                <!-- <i class="icon-add_user_phonebook"></i> -->
                                <span>Earned Referral Points History</span> 
                              </a> 
                            </li>
                            <li class="redeem_referrals">
                              <a href="<?php echo site_url('admin/redeem_referrals_list');?>">
                                <!-- <i class="icon-add_user_phonebook"></i> -->
                                <span>Redeemed Referral Points History</span> 
                              </a> 
                            </li>
                             <li class="add_referrals">
                              <a href="<?php echo site_url('admin/referrals_management');?>">
                                <!-- <i class="icon-add_user_phonebook"></i> -->
                                <span>Referral Points Settings</span> 
                              </a> 
                            </li>
                        </ul>
                    </li> 
                    <li class="qrcode">
                      <a href="<?php echo site_url('admin/all_qrcodes');?>" class="">
                        <!-- <i class="fa fa-qrcode"></i> -->
                        <img src="<?php echo base_url();?>assets/images/qr code.png">
                        <span> QR Codes</span> 
                      </a>      
                    </li> 
                       <li class="settings">
                        <a href="#" class="menu-toggle waves-effect waves-block">
                       <!--      <img src="<?php echo base_url();?>assets/images/merchant mang.png"> -->
                            <span><i class="fa fa-cog" aria-hidden="true"></i>  Settings</span> 
                        </a>                         
                        <ul class="ml-menu">
                            <li class="email_list">
                              <a href="<?php echo site_url('admin/all_notification_mails');?>">
                                <!-- <i class="icon-add_user_phonebook"></i> -->
                                <span>Email Templates</span> 
                              </a> 
                            </li>  
                             <li class="static_pages_list">
                              <a href="<?php echo site_url('admin/static_pages_list');?>">
                                <!-- <i class="icon-add_user_phonebook"></i> -->
                                <span>Manage Static Pages </span> 
                              </a> 
                            </li> 
                        </ul>
                    </li>

                    <?php

                      }
                    ?>

                </ul>
            </div>
        </div>
       
    </div>    
</aside>



 