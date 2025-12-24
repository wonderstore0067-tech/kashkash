<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
 <?php $general_data = get_generalsettings();?>
<title> <?php echo isset($general_data[0]['Sitetitle']) ? $general_data[0]['Sitetitle'] : 'Donmac Admin';?></title>
 <?php $favicon =  isset($general_data[0]['sfavicon']) ? $general_data[0]['sfavicon'] : '';?>
<link rel="icon" href="<?php echo base_url('assets/images/'.$favicon);?>" type="image/x-icon"> <!-- Favicon-->
	<link rel="stylesheet" href="<?php echo base_url();?>web_assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url();?>web_assets/css/style.css">
	<link rel="stylesheet" href="<?php echo base_url();?>web_assets/css/default.css">
	<link rel="stylesheet" href="<?php echo base_url();?>web_assets/css/font_style.css">
	<link rel="stylesheet" href="<?php echo base_url();?>web_assets/css/type-font.css">
	<link rel="stylesheet" href="<?php echo base_url();?>web_assets/css/login.css">
	<link rel="stylesheet" href="<?php echo base_url();?>web_assets/css/custom.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/sweetalert.css">
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/parsley.css">
   <script src="<?php echo base_url();?>web_assets/js/jquery.min.js"></script>
   <script src="<?php echo base_url();?>web_assets/js/bootstrap.min.js"></script>
</head>
<body>
<script type="text/javascript">
    var  BASEURL = "<?php echo base_url(); ?>" ;
 </script>

	<!--************** Start Header ***********-->
	<header>
		<div class="topbar bg_gray">
				<div class="container">
					<div class="row">
						<div class="col-md-3 col-sm-4 col-xs-10">
							<div class="header-search">
								<i class="glyphicon glyphicon-search"></i>
								<input type="text" class="form-control border-rad22" placeholder="Search" />
							</div>
						</div>
						<div class="topbar-nav col-md-9 col-sm-8 hidden-xs">
							<ul class="nav navbar-nav navbar-right">
								<!-- <li><a href="#"><span><i class="fa fa-bell-o" aria-hidden="true"></i></span> Notification</a></li> -->
								<li><a href="#"><span class="icon-notification_dark"></span> Notification</a></li>
								<li><a href="#"><span class="glyphicon glyphicon-flag"></span> Language</a></li>
								<li>
									<a href="" data-toggle="modal" data-target="#Login_popup">
										<span> <img src="<?php echo base_url();?>web_assets/img/testimo_1.png" class="headerimg h-w-30 border-50"> </span>
										Account
									</a>
								</li>
							</ul>
						</div>

						<div class="col-xs-2 hidden-md hidden-lg">
							<div class="navbar-header pull-right">
								<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#myNavbar">
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>                        
								</button>
							</div>
						</div>
					</div>
				</div>
		</div>

		<div class="midnavbar">
			<nav class="navbar">
				<div class="container">
					<div class="navbar-header padding-t10">
						<a class="navbar-brand" href="javascript:void(0);">
							<img src="<?php echo base_url();?>web_assets/img/logo.png">
						</a>
					</div>
					<div class="pull-right header-wallet padding-tb15 text-right">
						<div class="wallet-top">
							<img src="<?php echo base_url();?>web_assets/img/wallet.png">
							<span>My Wallet</span>
						</div>
						  <?php $user_detail= getdetailsofuser();
						  ?>
						<div class="wallet-balance f-20 padding-t10">
							<span class="orangecolor"><b> <?php echo (!empty($user_detail)) ? $general_data[0]['currency'].'.'.$user_detail['Current_Wallet_Balance'] : $general_data[0]['currency'].'.'.'0.00';?></b></span>
						</div>
						</li>
					</div>
				</div>
			</nav>
		</div>
		<div class="lastnavbar bg_orange padding-b40 text-center">		
			<div id="myNavbar" class="navbar-collapse collapse">
				<nav class="navbar">
					<div class="container">
							<ul class="nav navbar-nav">
								<li class="active">
									<a href="<?php echo base_url();?>">
										<div class="nav_icontxt">
											<img src="<?php echo base_url();?>web_assets/img/home.png">
											<p class="padding-t10">Dashboard</p>
										</div>
									</a>
								</li>
								<li>
									<a href="#">
										<div class="nav_icontxt">
											<img src="<?php echo base_url();?>web_assets/img/pay.png">
											<p class="padding-t10">Pay</p>
										</div>	
									</a>
								</li>
								<li>
									<?php  if(isset($this->session->userdata['logged_user'])){ ?>
									<a href="<?php echo base_url('account/addmoney');?>">
										<div class="nav_icontxt">
											<img src="<?php echo base_url();?>web_assets/img/dashboard_add_money.png">
											<p  class="padding-t10">Add Money</p>
										</div>	
									</a>
									<?php }else{ ?>
									<a href="" data-toggle="modal" data-target="#Login_popup">
										<div class="nav_icontxt">
											<img src="<?php echo base_url();?>web_assets/img/dashboard_add_money.png">
											<p class="padding-t10">Add Money</p>
										</div>	
									</a>
								<?php } ?>
								</li>
								<li>
									<?php  if(isset($this->session->userdata['logged_user'])){ ?>
									<a href="<?php echo base_url('account/transaction_history/all');?>">
										<div class="nav_icontxt">
											<img src="<?php echo base_url();?>web_assets/img/agenda.png">
											<p class="padding-t10">MY Transactions</p>
										</div>	
									</a>
								<?php }else{ ?>
									<a href="" data-toggle="modal" data-target="#Login_popup">
										<div class="nav_icontxt">
											<img src="<?php echo base_url();?>web_assets/img/agenda.png">
											<p class="padding-t10">MY Transactions</p>
										</div>	
									</a>
								<?php } ?>
								</li>
								<li>
									<?php  if(isset($this->session->userdata['logged_user'])){ ?>
									<a href="<?php echo base_url('account/send_payment_request');?>">
										<div class="nav_icontxt">
											<img src="<?php echo base_url();?>web_assets/img/payment-method.png">
											<p class="padding-t10">Payment Request</p>
										</div>	
									</a>
									<?php }else{ ?>
									<a href="" data-toggle="modal" data-target="#Login_popup">
										<div class="nav_icontxt">
											<img src="<?php echo base_url();?>web_assets/img/payment-method.png">
											<p class="padding-t10">Payment Request</p>
										</div>	
									</a>
								<?php } ?>
								</li>
								<li>
									<?php  if(isset($this->session->userdata['logged_user'])){ ?>
									<a href="<?php echo base_url('account/withdrawmoney');?>">
										<div class="nav_icontxt">
											<img src="<?php echo base_url();?>web_assets/img/withdraw.png">
											<p class="padding-t10">Cashout</p>
										</div>	
									</a>
									<?php }else{ ?>
									<a href="" data-toggle="modal" data-target="#Login_popup">
										<div class="nav_icontxt">
											<img src="<?php echo base_url();?>web_assets/img/withdraw.png">
											<p class="padding-t10">Cashout </p>
										</div>	
									</a>
								 <?php } ?>
								</li>
							</ul>

							<ul class="nav navbar-nav hidden-sm hidden-md hidden-lg mobile-top-rightnav">
								<li><a href="#"><span class="icon-notification_dark"></span> Notification</a></li>
								<li><a href="#"><span class="	glyphicon glyphicon-flag"></span> Language</a></li>
								<li>
									<a href="" data-toggle="modal" data-target="#Login_popup">
										<span class="header-account-img"><img src="<?php echo base_url();?>web_assets/img/testimo_1.png" class="headerimg h-w-30 border-50"></span> Account
									</a>
								</li>
							</ul>

						</div>	
					</div>
				</nav>
			</div>	
		</div>
	</header>
	<!--************** END Header ***********-->
<script type="text/javascript" language="javascript">
    // $(document).ready(function(){
    //     $("#verification-code-popup").dialog({modal: true});
    // });
</script>