	<!--********Footer********-->
				<footer class="main_footer">
					<div class="footer bg_lightblack padding-tb35">
						<div class="container">
							<div class="row">
								<nav class="col-md-3 col-sm-6 col-xs-12">
									<h3>Our Services</h3>
									<ul>
										<li>
											<a href="#">Recharge & PayBills</a>
										</li>
										<li>
											<a href="#">Send Money to Wallet</a>
										</li>
										<li>
											<a href="#">Send Money to Bank</a>
										</li>
										<li>
											<a href="#">Shop</a>
										</li>
										<li>
											<a href="#">Request Money</a>
										</li>
										<!-- <li>
											<a href="#">PAYBACK Points</a>
										</li> -->
									</ul>
								</nav>
								<nav class="col-md-3 col-sm-6 col-xs-12">
									<h3>YugPay Wallet</h3>
									<ul>
										<li>
											<a href="#">About Us</a>
										</li>
										<li>
											<a href="#">FAQs</a>
										</li>
										<li>
											<a href="#">Terms & Conditions</a>
										</li>
										<li>
											<a href="#">Contact Us</a>
										</li>
									</ul>
								</nav>
								<nav class="col-md-3 col-sm-6 col-xs-12">
									<h3>More Links</h3>
									<ul>
										<li>
											<a href="#">Mobile APP</a>
										</li>
										<li>
											<a href="#">Refer a Friend</a>
										</li>
										<li>
											<a href="#">Customer Grievance Policy</a>
										</li>
										<li>
											<a href="#">Privacy Policy</a>
										</li>
									</ul>
								</nav>

								<nav class="col-md-3 col-sm-6 col-xs-12">
									<h3>About Us</h3>
									<p class="content f-16 white padding-b10">
										Send Money to your Friends & Other users as well with Money Transfer App
									</p>
									<h3>Connect & Share on</h3>
									<ul class="social">
										<li><a href="#"><i class="fa fa-facebook"></i></a></li>
										<li><a href="#"><i class="fa fa-twitter"></i></a></li>
										<li><a href="#"><i class="fa fa-linkedin"></i></a></li>
										<li><a href="#"><i class="fa fa-instagram"></i></a></li>
									</ul>
								</nav>

							</div> <!--/.row--> 
						</div> <!--/.container--> 
					</div> <!--/.footer-->

					<div class="footer-bottom">
						<div class="container">
							<div class="bg_darkblack white padding-tb20 text-center f-16"> © 2019 All rights reserved YugPay.</div>
						</div>
					</div> <!--/.footer-bottom--> 
				</footer>

	   </div>

		</main>
        <script src="<?php echo base_url();?>assets/js/parsley.js"></script>
        <script src="<?php echo base_url();?>assets/js/sweetalert.min.js"></script>
		<script src="<?php echo base_url();?>web_assets/js/common.js"></script>
         <?php  if(isset($this->session->userdata['logged_user'])){ ?>
        <script src="<?php echo base_url();?>web_assets/js/custom.js"></script>
        <?php }?>
		<script src="<?php echo base_url();?>web_assets/js/log.js"></script>
	<?php //} ?>
	</body>
	</html>