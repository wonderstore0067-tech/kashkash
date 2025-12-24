<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">
  <?php $general_data = get_generalsettings();?>
<title> <?php echo isset($general_data[0]['Sitetitle']) ? $general_data[0]['Sitetitle'] : 'Donmac Admin';?></title>
 <?php $favicon =  isset($general_data[0]['sfavicon']) ? $general_data[0]['sfavicon'] : '';?>
<link rel="icon" href="<?php echo base_url('assets/images/'.$favicon);?>" type="image/x-icon"> 
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
        <div class="m-t-30"><img class="zmdi-hc-spin" src="<?php echo base_url();?>assets/images/logo.svg" width="48" height="48" alt="Oreo"></div>
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
                <a class="navbar-brand" href="#" style="cursor:default;"><img src="<?php echo base_url('assets/images/'.$logo);?>" height="50" alt=""><span class="m-l-10"></span></a>

            </div>
        </li>
        <li><a href="javascript:void(0);" class="ls-toggle-btn" data-close="true"><i class="zmdi zmdi-swap" style="margin-top: 9px;"></i></a></li>
  
        <li class="float-right">
            <!-- <a href="javascript:void(0);" class="fullscreen hidden-sm-down" data-provide="fullscreen" data-close="true"><i class="zmdi zmdi-fullscreen"></i></a> -->
            <a href="<?php echo base_url('admin/admin/logout');?>" class="mega-menu" data-close="true"><i class="zmdi zmdi-power" style="margin-top: 21px;"></i></a>
            <!-- <a href="javascript:void(0);" class="js-right-sidebar" data-close="true"><i class="zmdi zmdi-settings zmdi-hc-spin"></i></a> -->
        </li>
        <li class="hidden-sm-down float-right search_margin">
            <form class="search_hd" method="POST" action="<?php echo base_url('/admin/admin/search_user_submit');?>">
            <div class="input-group ">                
                <input type="text" name="q" id="search" class="form-control black border-c search_height" placeholder="Search...">
                <div class="user-searchbox hideme height-250"><ul id="suggesstion-box" class="scroll_property"></ul></div>
                <span class="input-group-addon search_height">
                    <i class="zmdi zmdi-search" style="padding-right: 25px; "></i>
                </span>
            </div>
        </form>
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
                                echo get_role_name($loginuser_data['Id']);?></small>
                            </div>
                        </div>
                    </li>
                        <?php  
                        $reqactive = $_SERVER['REQUEST_URI']; 

                        $getAdminPermissions =admin_permission($loginuser_data['Id']);
                       // print_r($getAdminPermissions);die;
                        $permissions = unserialize($getAdminPermissions[0]['Permission']);
                        $dashboard          = isset($permissions['dashboard'] ) ? $permissions['dashboard'] : '' ;
                        $user_manage        = isset($permissions['user_manage']) ? $permissions['user_manage'] : '' ;
                        $merchant_manage    = isset($permissions['merchant_manage']) ? $permissions['merchant_manage'] : '' ;
                        $withdraw           = isset($permissions['withdraw']) ? $permissions['withdraw'] : '' ;
                        $deposit            = isset($permissions['deposit']) ? $permissions['deposit'] : '' ;
                        $request            = isset($permissions['request']) ? $permissions['request'] : '' ;
                        $send_money         = isset($permissions['send_money']) ? $permissions['send_money'] : '' ;
                        $transaction        = isset($permissions['transaction']) ? $permissions['transaction'] : '' ;
                        $sharebill_request  = isset($permissions['sharebill_request']) ? $permissions['sharebill_request'] : '' ;
                        $qrcode             = isset($permissions['qrcode']) ? $permissions['qrcode'] : '' ;
                        $pay_bill           = isset($permissions['pay_bill']) ? $permissions['pay_bill'] : '' ;
                        $manage_promocode   = isset($permissions['manage_promocode']) ? $permissions['manage_promocode'] : 0 ;
                        $biller             = isset($permissions['biller_manage']) ? $permissions['biller_manage'] : '' ;
                        $feedback           = isset($permissions['feedback']) ? $permissions['feedback'] : 0 ;
                        $trx_limit          = isset($permissions['trx_limit']) ? $permissions['trx_limit'] : 0 ;
                        $setting            = isset($permissions['setting']) ? $permissions['setting'] : '' ;
                        $website            = isset($permissions['website']) ? $permissions['website'] : '' ;
                        $admin_management   = isset($permissions['admin_management']) ? $permissions['admin_management'] : '' ;
                      if($dashboard == 1){      
                    ?>
                     
                  <li class="<?php echo strpos($reqactive, 'dashboard') !== false ? 'active' : '';?>">
                    <a href="<?php echo site_url('admin/dashboard');?>">
                      <!-- <i class="zmdi zmdi-home"></i> -->
                      <img src="<?php echo base_url();?>assets/images/Dashboard.png">
                      <span>Dashboard</span>
                    </a>
                  </li>
                    <?php } 

                    if($user_manage == 1){ 
                    ?>

                    <li class="usermgmt">
                      <a href="#" class="menu-toggle">
                        <!-- <i class="icon-user  "></i> -->
                        <img src="<?php echo base_url();?>assets/images/user mang.png">
                        <span>Users Management</span>
                      </a>
                        <ul class="ml-menu usermgmt1">
                            <li  class="alluser"><a href="<?php echo site_url('admin/all_users/1');?>">All Users</a></li>
                            <li class="bnuser" ><a href="<?php echo site_url('admin/all_users/1/banned_user');?>">Banned Users</a></li>
                            <li class="verifyuser" ><a href="<?php echo site_url('admin/all_users/1/verified_user');?>">Verified Users</a></li>
                             <li class="user_mob" ><a href="<?php echo site_url('admin/all_users/1/mobile_unverified');?>">Mobile Unverified</a></li>
                           
                        </ul>
                    </li>  
                <?php } 
                 if($merchant_manage == 1){    
                    ?>

                    <li class="merchant_mgmt">
                      <a href="#" class="menu-toggle">
                        <!-- <i class="icon-add_user_phonebook"></i> -->
                        <img src="<?php echo base_url();?>assets/images/merchant mang.png">
                        <span>Merchant Management</span> 
                      </a>
                        <ul class="ml-menu">
                            <li class="allmgmt"><a href="<?php echo site_url('admin/all_merchants/2');?>">All Merchants</a></li>
                            <li class="bn_merchant" ><a href="<?php echo site_url('admin/all_merchants/2/banned_user');?>">Banned Merchants</a></li>
                            <li class="verify_merchant" ><a href="<?php echo site_url('admin/all_merchants/2/verified_user');?>">Verified Merchants</a></li>
                         <li class="mob_merchant"><a href="<?php echo site_url('admin/all_merchants/2/mobile_unverified');?>">Mobile Unverified</a></li>
                        </ul>
                    </li> 
                <?php } if($transaction == 1){ 
                    ?>

                    <li class="trxs ">
                      <a href="#" class="menu-toggle">
                        <!-- <i class="fa fa-money"></i> -->
                        <img src="<?php echo base_url();?>assets/images/transactions.png">
                        <span> Transactions</span> 
                      </a>
                        <ul class="ml-menu">
                            <li class="alltrx"><a href="<?php echo site_url('admin/all_transactions');?>"> Transactions List</a></li>
                             <li class="trx_wallet"><a href="<?php echo site_url('admin/transactions_wallet_list');?>"> Transactions Wallet List</a></li>
                        </ul>
                    </li> 
                    <?php } if($withdraw == 1){ ?>
                     
                    <li class="withdraw">
                      <a href="#" class="menu-toggle">
                        <!-- <i class="icon-cashout-h"></i> -->
                        <img src="<?php echo base_url();?>assets/images/withdraw money.png">
                        <span> Withdraw Money</span> 
                      </a>
                        <ul class="ml-menu">
                           <!--  <li><a href="<?php //echo site_url('admin/home/withdraw_method');?>"><i class="fa fa-cog"></i> Withdrawal Charges</a></li> -->
                            <li class="withdraw_history"><a href="<?php echo site_url('admin/withdraw_history');?>"> Withdrawal History</a></li>
                        </ul>
                    </li>
                <?php } if($deposit == 1){ ?>

                    <li class="deposit">
                      <a href="#" class="menu-toggle">
                        <!-- <i class="icon-add_money-h"></i> -->
                        <img src="<?php echo base_url();?>assets/images/deposit money.png">
                        <span> Deposit Money</span> 
                      </a>

                        <ul class="ml-menu">
                           <!--  <li><a href="<?php //echo site_url('admin/home/withdraw_method');?>"><i class="fa fa-cog"></i> Withdrawal Charges</a></li> -->
                            <li class="deposit_history"><a href="<?php echo site_url('admin/deposit_history');?>"> Deposit History</a></li>
                        </ul>
                    </li> 
                <?php } if($send_money == 1){
                 ?>

                  <li class="sendmoney">
                    <a href="<?php echo site_url('admin/send_money');?>" class="">
                      <!-- <i class="icon-chat_send_money"></i> -->
                      <img src="<?php echo base_url();?>assets/images/send money.png">
                      <span> Send Money</span>
                    </a>     
                  </li> 

                   <?php } if($request == 1){ ?>
                    <li class="reqmoney">
                      <a href="<?php echo site_url('admin/request_money');?>" class="">
                        <!-- <i class="icon-payment_request"></i> -->
                        <img src="<?php echo base_url();?>assets/images/request money.png">
                        <span>Request Money</span> 
                      </a>   
                    </li> 

                <?php } if($sharebill_request == 1){?>
                    <li class="sharebill">
                      <a href="<?php echo site_url('admin/sharebill_request');?>" class="">
                        <!-- <i class="icon-payment_request"></i> -->
                        <img src="<?php echo base_url();?>assets/images/split bill.png">
                        <span> Split Bill Request</span> 
                      </a>      
                    </li> 

                     <?php } if($pay_bill == 1){ ?>
                    <li class="paybill">
                      <a href="<?php echo site_url('admin/paybills');?>" class="">
                        <!-- <i class="icon-pay_bill"></i> -->
                        <img src="<?php echo base_url();?>assets/images/pay bills.png">
                        <span> Pay Bills</span> 
                      </a>      
                    </li> 

                    <?php } if($qrcode == 1){ ?>
                    <li class="qrcode">
                      <a href="<?php echo site_url('admin/all_qrcodes');?>" class="">
                        <!-- <i class="fa fa-qrcode"></i> -->
                        <img src="<?php echo base_url();?>assets/images/qr code.png">
                        <span> QR Codes</span> 
                      </a>      
                    </li> 

                    <?php } 
                    if($biller == 1){ ?>
                    <li class="billmg">
                      <a href="<?php echo site_url('admin/billers_list');?>" class="">
                        <!-- <i class="fa fa-money"></i> -->
                        <img src="<?php echo base_url();?>assets/images/bill management.png">
                        <span>Biller Mangement</span> 
                      </a>      
                    </li>  

                <?php } if($manage_promocode == 1){?>
               <!--  <li class="mngprcode">
                  <a href="<?php echo site_url('admin/promocode_list');?>" class="">
                  
                    <img src="<?php echo base_url();?>assets/images/manage promo.png">
                    <span>Manage Promocode </span> 
                  </a>      
                </li>  -->
                 <?php } if($feedback == 1){?>
                    <li class="feedback">
                      <a href="<?php echo site_url('admin/feedback');?>">
                        <!-- <i class="fa fa-commenting"></i> -->
                        <img src="<?php echo base_url();?>assets/images/feedback.png">
                        <span> Feedback</span> 
                      </a>    
                </li>
                 <?php } if($trx_limit == 1){?>
                 <li class="trxlimit">
                      <a href="<?php echo site_url('admin/set_transaction_limit');?>" class="">
                         <!-- <i class="fa fa-money" style="color: #365aad !important"></i>  -->
                        <img src="<?php echo base_url();?>assets/images/transactions.png">
                        <span>Transaction Limit</span> 
                      </a>      
                    </li>

                <?php }if($setting == 1){ ?>
                    <li class="setings">
                      <a href="#" class="menu-toggle">
                        <!-- <i class="fa fa-gear"></i> -->
                        <img src="<?php echo base_url();?>assets/images/settings.png">
                        <span>Settings</span> 
                      </a>
                    <ul class="ml-menu">
                        <li class="transfee">
                          <a href="<?php echo site_url('admin/transaction_fee');?>"> Transaction Fees</a>
                        </li>
                        <li class="aboutus">
                          <a href="<?php echo site_url('admin/about_us/3');?>"> About Us</a>
                        </li> 
                        <li class="privcypolicy">
                          <a href="<?php echo site_url('admin/privacy_policy/2');?>"> Privacy & Policy</a>
                        </li>
                        <li class="trms_setting">
                          <a href="<?php echo site_url('admin/terms_setting/1');?>"> Terms & Conditions</a>
                        </li>
                    </ul>
                    </li>
                <?php } if($website == 1){ ?>
                   <!-- <li class="webcontent">
                    <a href="#" class="menu-toggle">
                      <img src="<?php echo base_url();?>assets/images/website content.png">
                      <span>Websites Content</span> 
                    </a>
                     <ul class="ml-menu">
                        <li class="iconsetting"><a href="<?php echo site_url('admin/logo_setting');?>"> Logo + Icon Settings</a></li>   
                    </ul>
                </li> -->
                <?php } if($admin_management == 1){ ?>
                 <li class="adminmgmt">
                  <a href="#" class="menu-toggle">
                    <!-- <i class="fa fa-user"></i> -->
                    <img src="<?php echo base_url();?>assets/images/admin managment.png">
                    <span>Admin Management</span> 
                  </a>
                     <ul class="ml-menu">
                        <li class="allroles"><a href="<?php  echo site_url('admin/roles');?>"> All Role</a></li> 
                        <li class="mngestaff"><a href="<?php echo site_url('admin/staff');?>">Manage Staff</a></li>   
                    </ul>
                 </li> 
                 
                 <?php }  ?>

                </ul>
            </div>
        </div>
       
    </div>    
</aside>



 