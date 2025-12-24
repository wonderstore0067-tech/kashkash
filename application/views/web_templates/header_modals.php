	
			<!-- Login Popup -->
			<div class="modal fade" id="Login_popup" role="dialog">
				<div class="modal-dialog">

					<!-- Modal content-->
					<div class="modal-content">

						<div class="modal-body">
						  
							<div class="login-form">	
								<ul  class="nav row text-center">
									<li class="active col-md-6 col-sm-6 col-xs-6">
										<a href="#Login-Tab" data-toggle="tab">Login</a>
									</li>
							  		<li class="col-md-6 col-sm-6 col-xs-6">
							  			<a href="#sign-up-Tab" data-toggle="tab">Sign Up</a>
							  		</li>
								</ul>
								<div class="tab-content clearfix">
									<div class="tab-pane active" id="Login-Tab"> 
								        <form  id="signin" class="top_form padding-t20 row clearfix" data-parsley-validate="" >
								        	<div class=" alert alert-success succmsg hideme"></div>
								        	<div class=" alert alert-danger succmsg hideme"></div>

											<div class="form-group form-row margin-b20 clearfix">
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="text"  name="mobile" class="mobile_no form-control" id="mob" placeholder="Email / Mobile Number" required  data-parsley-type="number"  data-parsley-required-message=" Phone number  is required" data-parsley-type-message="Phone number should be a valid number." data-parsley-minlength="10" data-parsley-maxlength="10">
												</div>	
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="password" name= "password" class="form-control" id="paass" placeholder="Password" required data-parsley-required-message="Password is required">
												</div>
											</div>

											<div class="form-group forgot-password clearfix">
												<a href="#" data-toggle="modal" data-target="#forgot-popup">Forgot Password?</a>
											</div>

											<div class="login-formbtn padding-b15 clearfix">
												<div class="padding-tb15 col-md-6 col-sm-6 col-xs-12 formbtn">
													<input type="submit" class="orange-btn">
												</div>
												<div class="padding-tb15 col-md-6 col-sm-6 col-xs-12 formbtn">
													<a href="#" class="grey-btn">Cancel</a>
												</div>
											</div>

											<div class="login-form-footer clearfix text-center padding-b20">
												<p> Please login using your RuPay card registered mobile number and password. </p>
											</div>
										</form>
									</div>
									<div class="tab-pane" id="sign-up-Tab">  
								        <form  id="signup" class="top_form padding-t20 row clearfix " data-parsley-validate="">
								        	<div class=" alert alert-success succmsg hideme"></div>
								        	<!-- User Sign Up Form -->
											<div class="form-row user-signup clearfix">
												<div class="form-group col-md-12 col-sm-12 col-xs-12 padding-b5">
													<div class="rediobtn">
													    <input type="radio" id="user_redibtn" name="roles" value="2" required >
													    <label for="user_redibtn">User</label>
													</div>
													<div class="rediobtn">
													    <input type="radio" id="merchant_redibtn" name="roles" value="3" >
													    <label for="merchant_redibtn">Merchant</label>
													</div>
												</div>
												<div class="form-group col-md-6 col-sm-6 col-xs-12">
													<input type="text" name="fullname" class="form-control fullname" placeholder="Name"  required data-parsley-required-message="Fullname is required">
												</div>
												<div class="form-group col-md-6 col-sm-6 col-xs-12">
													<input type="text" name="email" class="form-control email" placeholder="Email" required data-parsley-type="email"data-parsley-required-message="Email is required" data-parsley-type-message="Email field should be a correct format" >
													 <span class="inputerror malerror text text-danger hideme" > </span>
												</div>	
												<div class="form-group col-md-6 col-sm-6 col-xs-12">
													<select name="location_country" class="form-control location_country" id="select_country"  required data-parsley-required-message="Countrty name is required">
														<option value="">Select a country</option>
														<?php 
                                                         $country_data = getdatafromtable('countries'); 
														if($country_data){
														  foreach($country_data as $value){ ?>
                                                           <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option>
													<?php }
													} ?>	
													</select>
												</div>
												<div class="form-group col-md-6 col-sm-6 col-xs-12">
													<input type="password" name="password" class="form-control password" placeholder="Password"  required data-parsley-required-message="Password is required" id="password" data-parsley-minlength="6" data-parsley-maxlength="18" >
													<input type="hidden" name="errchk" id="errchk">
													<input type="hidden" name="errchk" id="errchk2">
													<div id="info" class="hideme">
							                                <span id="noti1" class="fa fa-times" style="color:red !important;">Your Password Must Have One Small letter</span><br>  
							                                <span id="noti3" class="fa fa-times" style="color:red !important;">Your Password Must Have One Number</span><br> 
							                        </div>
												</div>
												<div class="form-group col-md-6 col-sm-6 col-xs-12">
													<input type="text" name="mobile" class="form-control mobile" placeholder="Enter Mobile Number"  required data-parsley-type="number" data-parsley-required-message="Mobile number is required" data-parsley-type-message="Mobile number should be a valid number." data-parsley-minlength="10" data-parsley-maxlength="10" >
													 <span class="inputerror moberror text text-danger hideme" > </span>
												</div>
												<div class="form-group col-md-6 col-sm-6 col-xs-12">
													<input type="text" name="id_pass_number" class="form-control id_pass_number" placeholder="Enter ID Number / Passport Number"  required data-parsley-required-message="ID Number / Passport Number is required">
												</div>
												<div class="form-group col-md-6 col-sm-6 col-xs-12">
													<input type="file" name="identification_image" class="form-control identification_image" placeholder="Upload a File for Identification"  required data-parsley-required-message="Identification file is required">
												</div>
												<div class="merchant_detail hideme">
												<div class="form-group col-md-6 col-sm-6 col-xs-12">
													<input type="text" name="sole_proprietor" class="form-control sole_proprietor" placeholder="Sole proprietor"  required data-parsley-required-message="Sole proprietor is required">
												</div>
												<div class="form-group col-md-6 col-sm-6 col-xs-12">
													<input type="text" name="business_name" class="form-control business_name" placeholder="Name of Business"  required data-parsley-required-message="Name of business is required">
												</div>
												<div class="form-group col-md-6 col-sm-6 col-xs-12">
													<input type="text" name="registration_number" class="form-control registration_number" placeholder="Registration Number"  required data-parsley-required-message="Registration number is required">
												</div>
												<div class="form-group col-md-6 col-sm-6 col-xs-12">
													<input type="text" name="kra_pin_number" class="form-control kra_pin_number" placeholder="Enter KRA PIN"  required data-parsley-required-message="KRA PIN is required">
												</div>
												</div>
												<div class="col-md-12 col-sm-12 col-xs-12 padding-b5">
													<div class="checkbox">
														<input class="custom-checkbox" id="read-checkbox" type="checkbox" name="terms_condition" value="1" required data-parsley-required-message="Please refer to our Terms & Conditions">
												    	<label for="read-checkbox">I have read and accept the terms & conditions.</label>
													</div>
												</div>
											</div>
								        	<!-- User Sign Up Form -->

								        	<!-- Merchant Sign Up Form -->
											<!-- <div class="form-row merchant-signup clearfix">
												<div class="form-group col-md-12 col-sm-12 col-xs-12 padding-b5">
													<div class="rediobtn">
													    <input type="radio" id="user_redibtn" name="radio-group" checked>
													    <label for="user_redibtn">User</label>
													</div>
													<div class="rediobtn">
													    <input type="radio" id="merchant_redibtn" name="radio-group" checked>
													    <label for="merchant_redibtn">Merchant</label>
													</div>
												</div>
												<div class="form-group col-md-6 col-sm-6 col-xs-12">
													<input type="text" class="form-control" placeholder="Name">
												</div>
												<div class="form-group col-md-6 col-sm-6 col-xs-12">
													<input type="email" class="form-control" placeholder="Email">
												</div>	
												<div class="form-group col-md-6 col-sm-6 col-xs-12">
													<select class="form-control" id="select_country">
														<option>Select a country</option>
														<option>India</option>
														<option>USA</option>
														<option>France</option>
													</select>
												</div>
												<div class="form-group col-md-6 col-sm-6 col-xs-12">
													<input type="text" class="form-control" placeholder="Password">
												</div>
												<div class="form-group col-md-6 col-sm-6 col-xs-12">
													<input type="text" class="form-control" placeholder="Enter Mobile Number">
												</div>
												<div class="form-group col-md-6 col-sm-6 col-xs-12">
													<input type="text" class="form-control" placeholder="Enter ID Number / Passport Number">
												</div>
												<div class="form-group col-md-6 col-sm-6 col-xs-12">
													<input type="text" class="form-control" placeholder="Upload a File for Identification">
												</div>
												<div class="form-group col-md-6 col-sm-6 col-xs-12">
													<input type="text" class="form-control" placeholder="Sole proprietor">
												</div>
												<div class="form-group col-md-6 col-sm-6 col-xs-12">
													<input type="text" class="form-control" placeholder="Name of Business">
												</div>
												<div class="form-group col-md-6 col-sm-6 col-xs-12">
													<input type="text" class="form-control" placeholder="Registration Number">
												</div>
												<div class="form-group col-md-6 col-sm-6 col-xs-12">
													<input type="text" class="form-control" placeholder="Enter KRA PIN">
												</div>
												<div class="col-md-12 col-sm-12 col-xs-12 padding-b5">
													<div class="checkbox">
														<input class="custom-checkbox" id="read-checkbox" type="checkbox" value="value1">
												    	<label for="read-checkbox">I have read and accept the terms & conditions.</label>
													</div>
												</div>
											</div> -->
								        	<!-- Merchant Sign Up Form -->
											<div class="login-formbtn clearfix">
												<div class="padding-tb15 col-md-6 col-sm-6 col-xs-12 formbtn">
													<input type="submit" name="" value="Proceed" class="orange-btn" >
												</div>
												<div class="padding-tb15 col-md-6 col-sm-6 col-xs-12 formbtn">
													<a href="#" class="grey-btn">Cancel</a>

												</div>
												
											</div>
											
										</form>
									</div>
								</div>
							  </div>
						</div>
					</div>
				  
				</div>
			</div>
			<!-- Forgot Popup -->
			<div class="modal fade" id="forgot-popup" role="dialog">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-body">            
					        <form class="top_form padding-t20 row clearfix" id="forgotpass" data-parsley-validate="">
								<div class="login-form-header clearfix text-center padding-b20">
									<div class="forgot-icon"><img src="<?php echo base_url();?>web_assets/img/forgot_password_icon.png"></div>
									<h4 class="padding-t20 padding-b10"> Forgot Password </h4>
									<p class="margin-b20"> Enter Your Email address to reset your password. </p>
								</div>
                                    <div class=" alert alert-success margin-lr90 padding7 hideme"></div>
								    <div class="margin-lr90 padding7 alert alert-danger hideme"></div>
								<div class="form-group form-row margin-b20 clearfix">
									<div class="col-md-offset-2 col-sm-offset-2 col-md-8 col-sm-8 col-xs-12">
										<input type="text" name="mobileEmail" class="form-control" placeholder="Email / Mobile Number" required data-parsley-required-message="Email or mobile number is required">
									</div>	
								</div>
								<div class="login-formbtn padding-b15 clearfix">
									<div class="padding-tb15 col-md-offset-3 col-sm-offset-3 col-md-6 col-sm-6 col-xs-12 text-center">	
									<!-- 	<input type="submit" name="" value="Submit" class="orange-btn" > -->
										 <a href="#" class="orange-btn" data-toggle="modal" data-target="#verification-code-popup">Submit</a>
									</div>
								</div>
							</form>

						</div>
					</div>
				</div>
			</div>
			<!-- Verification Code Popup -->
			<div class="modal fade" id="verification-code-popup" role="dialog">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-body">
							            
					        <form class="top_form padding-t20 row clearfix">

								<div class="login-form-header clearfix text-center padding-b20">
									<div class="forgot-icon padding-t20 padding-b30"><img src="<?php echo base_url();?>web_assets/img/logo.png"></div>
									<h4 class="padding-t20 padding-b10"> Verification Code </h4>
									<p class="margin-b20"> Please Type the Verifacation Code sent to your Email / Phone </p>
								</div>
                                  <div class="forgetsucc alert alert-success margin-lr90 padding7 hideme"></div>
								  <div class="forgetdng margin-lr90 padding7 alert alert-danger hideme"></div>
								<div class="form-group verification-code-input form-row margin-b20 clearfix">
									<div class="col-md-offset-2 col-sm-offset-2 col-md-2 col-sm-2 col-xs-3">
										<input type="text"  class="form-control" id="regfname" placeholder="" maxlength="1">
									</div>
									<div class="col-md-2 col-sm-2 col-xs-3">
										<input type="text" class="form-control" placeholder="" maxlength="1">
									</div>	
									<div class="col-md-2 col-sm-2 col-xs-3">
										<input type="text" class="form-control" placeholder="" maxlength="1">
									</div>	
									<div class="col-md-2 col-sm-2 col-xs-3">
										<input type="text" class="form-control" placeholder="" maxlength="1">
									</div>
								</div>

								<div class="login-formbtn padding-b15 clearfix">
									<div class="padding-tb15 col-md-offset-3 col-sm-offset-3 col-md-6 col-sm-6 col-xs-12 text-center">
										<input type="submit" value="Submit" class="orange-btn" >
										<!-- data-toggle="modal" data-target="#set-new-password" -->
									</div>
								</div>
							</form>

						</div>
					</div>
				</div>
			</div>
			<!-- New Password Popup -->
			<div class="modal fade" id="set-new-password" role="dialog">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-body">
							            
					        <form class="top_form padding-t20 row clearfix">

								<div class="login-form-header clearfix text-center padding-b20">
									<div class="forgot-icon padding-t20 padding-b30"><img src="<?php echo base_url();?>web_assets/img/logo.png"></div>
								</div>

								<div class="form-row clearfix">
									<div class="col-md-offset-2 col-sm-offset-2 col-md-8 col-sm-8 col-xs-12">
										<div class="form-group">
											<input type="text" class="form-control" placeholder="New Password">
										</div>
										<div class="form-group">
											<input type="text" class="form-control" placeholder="Confirm Password">
										</div>
									</div>	
								</div>

								<div class="login-formbtn padding-b15 clearfix">
									<div class="padding-tb15 col-md-offset-3 col-sm-offset-3 col-md-6 col-sm-6 col-xs-12 text-center">
										<a href="#" class="orange-btn">Submit</a>
									</div>
								</div>
							</form>

						</div>
					</div>
				</div>
			</div>
<script type="text/javascript">
	$(":input").bind("keyup change", function(e) {
      $(this).parsley().validate();
      $(this).parsley().isValid(); 
});

$('[data-target="#forgot-popup"]').click(function(){
  //alert('test')
  $('#Login_popup').modal("hide");
});
$("#regfname").one("keypress", function () {
    alert('Handler for .keypress() called.');
});

</script>