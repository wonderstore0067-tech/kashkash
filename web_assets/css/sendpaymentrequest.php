
 
 <!--*****************main section starts here*****************-->
<main class="main_data relative">
	<div class="absolute top-20">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					
					<div class="col-md-7 col-sm-7 col-xs-12">
						<div class="leftform">
							<div class="form-row margin-b20 clearfix bg_white border-radius-tl border-radius-tr boxshadow">
							  <div class="col-md-12 col-sm-12 col-xs-12">
							  	<h3 class="col-md-12 col-sm-12 col-xs-12 padding-b10">Payment Request</h3>
							 	</div>
							 	<div class="banktab_section paymentrequest_tab">
								  <ul class="nav nav-tabs">
								    <li class="active"><a data-toggle="tab" href="#section_new">New</a></li>
								    <li><a data-toggle="tab" href="#section_request">Requests</a></li>
								    <li><a data-toggle="tab" href="#section_his">History</a></li>
								  </ul>
								  <div class="tab-content">
							      <div id="section_new" class="tab-pane fade in active">
							        <form class="top_form clearfix bg_white border-radius-tl border-radius-tr" data-parsley-validate="" id="sendreq">
												<div class=" form-row margin-b20 clearfix">
												  <div class="col-md-12 col-sm-12 col-xs-12 clearfix">
												  	 <h3 class="col-md-6 col-sm-6 col-xs-12 padding-b12 ">Receiver Details</h3>
												  	<h4 class="col-md-6 col-sm-6 col-xs-12 padding-b12 pull-right"><a href="javascript:void(0);" class="add_more_button " style="margin-left: 170px" title="Add More"><i class="fa fa-plus"></i><span>Add More</span></a></h4>
												  </div>
												  <div class="col-md-12 col-sm-12 col-xs-12 clearfix">
												  	 <div class=" send_request"><div>
												  	 <div class="row">
													  <div class="col-md-6 col-sm-6 col-xs-12 ">
													    <div class="form-group">
													     	<input type="text" name="mobile[]" required class="form-control" placeholder="Enter Mobile Number"  maxlength="10" data-parsley-type="number" data-parsley-required-message="Mobile number is required" data-parsley-type-message="Mobile number should be a valid number.">
													    </div>
													  </div>
													  <div class="col-md-6 col-sm-6 col-xs-12 ">
													    <div class="form-group">
													     	<input type="number" name="amount[]" required class="form-control" placeholder="Enter Amount" data-parsley-required-message="Amount is required" min="1">
													    </div>
													  </div>
													</div>
													 <div class="row">
												    <div class="col-md-6 col-sm-6 col-xs-12 ">
												      <div class="form-group">
												      	<input type="text" name="comment[]" class="form-control" placeholder="Comment (Optional)">
												      </div>
												    </div>
												    </div>
												   </div>
												  </div>
												    <div class="col-md-12 col-sm-6 col-xs-12">
													    <div class="padding-tb25 col-md-6 col-sm-6 col-xs-12 formbtn">
													    	<input type="submit" name="" value="Send Money" class="btnboxshadow border-rad22 white bg_orange padding-tb10">
													    </div>
						    						</div>
													</div>
												</div>
											</form>
							      </div>
							      <div id="section_request" class="tab-pane fade">
						          <form class="top_form clearfix bg_white border-radius-tl border-radius-tr scroolheight overflow_y">
												<div class="col-md-12 col-sm-6 col-xs-12 border-b1">
										    	<div class="addmoney_block row clearfix padding-tb25">
										    		<div class="col-md-8 col-sm-6 col-xs-12">
										    			<div class="topblock row">
										    				<div class="col-md-9 col-sm-6 col-xs-6">
										    					<h5 class="bold">Add Money</h5>
										    					<h6 class="bold ">11 Oct 2017 10:41 AM</h6>
										    					<H6 class="lightsubheading">Order Number-123456789</H6>
										    				</div>
										    			</div>
										    		</div>
										    		<div class="col-md-4 col-sm-6 col-xs-12 text-right">
										    			<h4 class="amount"><b>K160.</b>95</h4>
										    		</div>
										     	</div>
										     	<div class="login-formbtn padding-b15 clearfix">
														<div class="padding-tb15 col-md-6 col-sm-6 col-xs-12 formbtn">
															<a href="#" class="grey-btn capitalize">Decline</a>
														</div>
														<div class="padding-tb15 col-md-6 col-sm-6 col-xs-12 formbtn">
															<input type="submit" name="" value="Confirm" class="capitalize orange-btn">
														</div>
													</div>
							    			</div>
							    			<div class="col-md-12 col-sm-6 col-xs-12 border-b1">
										    	<div class="addmoney_block row clearfix padding-tb25">
										    		<div class="col-md-8 col-sm-6 col-xs-12">
										    			<div class="topblock row">
										    				<div class="col-md-9 col-sm-6 col-xs-6">
										    					<h5 class="bold">Add Money</h5>
										    					<h6 class="bold ">11 Oct 2017 10:41 AM</h6>
										    					<H6 class="lightsubheading">Order Number-123456789</H6>
										    				</div>
										    			</div>
										    		</div>
										    		<div class="col-md-4 col-sm-6 col-xs-12 text-right">
										    			<h4 class="amount"><b>K59.</b>00</h4>
										    		</div>
										     	</div>
										     	<div class="login-formbtn padding-b15 clearfix">
														<div class="padding-tb15 col-md-6 col-sm-6 col-xs-12 formbtn">
															<a href="#" class="grey-btn capitalize">Decline</a>
														</div>
														<div class="padding-tb15 col-md-6 col-sm-6 col-xs-12 formbtn">
															<input type="submit" name="" value="Confirm" class="orange-btn capitalize">
														</div>
													</div>
							    			</div>
											</form>
							      </div>
							      <div id="section_his" class="tab-pane fade">
						          <form class="top_form clearfix bg_white border-radius-tl border-radius-tr scroolheight overflow_y">
												<div class="col-md-12 col-sm-6 col-xs-12 border-b1">
										    	<div class="addmoney_block row clearfix padding-tb25">
										    		<div class="col-md-8 col-sm-6 col-xs-12">
										    			<div class="topblock row">
										    				<div class="col-md-9 col-sm-6 col-xs-6">
										    					<h5 class="bold">Add Money</h5>
										    					<h6 class="bold ">Bill for: 2 Hot Coffee</h6>
										    					<h6 class="bold green">Paid</h6>
										    				</div>
										    			</div>
										    		</div>
										    		<div class="col-md-4 col-sm-6 col-xs-12 text-right">
										    			<h6 class="lightsubheading">11 Oct 2017 10:41 AM</h6>
										    			<h4 class="amount"><b>K160.</b>95</h4>
										    		</div>
										     	</div>
							    			</div>
							    			<div class="col-md-12 col-sm-6 col-xs-12 border-b1">
										    	<div class="addmoney_block row clearfix padding-tb25">
										    		<div class="col-md-8 col-sm-6 col-xs-12">
										    			<div class="topblock row">
										    				<div class="col-md-9 col-sm-6 col-xs-6">
										    					<h5 class="bold">E Shop request to pay</h5>
										    					<h6 class="bold ">Bill for: 2 Hot Coffee</h6>
										    					<h6 class="bold red">Declined</h6>
										    				</div>
										    			</div>
										    		</div>
										    		<div class="col-md-4 col-sm-6 col-xs-12 text-right">
										    			<h6 class="lightsubheading">11 Oct 2017 10:41 AM</h6>
										    			<h4 class="amount"><b>K59.</b>00</h4>
										    			<h6 class="bold red">Declined</h6>
										    		</div>
										     	</div>
							    			</div>
											</form>
							      </div>
							    </div>
							  </div>
							</div>
						</div>
								<!--**************slider***********-->
						<div class="slider_wrap">
							<?php echo promo_slidertemp();?> 	
						</div>
					</div>			

					<div class="col-md-5 col-sm-5 col-xs-12">
						<?php echo rightsidebartemp();?> 
					</div>

				</div>
			</div>

			
		</div>


		

 <!--*****************main section ends here*****************-->

    	


