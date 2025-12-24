<!DOCTYPE html>
<html>
<head>
	<!-- <title> PDF HTML / Stac </title> -->
<!-- 	<meta name="viewport" content="width=device-width, initial-scale=1"> -->
</head>
<!-- <style type="text/css">
	@media (max-width: 767px) {
		.table-content-wrap {
			width: 100% !important;
		}
	}
</style> -->
<body style="font-family: 'Open Sans', sans-serif;margin: 0;padding: 0;">

	<div style="background-color:#fff;width: 100%;">
		<div style="height:5px;background-color:#DD1155;width: 100%;"></div>
		<div style="background-color: #f5f5f5;width: 100%;height:75px;padding-top:5px;">
				<div style="width:49.5%;display:block;float:left;">
					<img src="uploads/logo.png">
				</div>
				<div style="width:49.5%;display:block;float:left;text-align:right;">
					<h3 style="font-size:16px;margin: 0;padding: 0 0 5px;"> <?php echo $fullname; ?> </h3>
					<p style="font-size:14px;margin: 0 0 5px;"> <?php echo $mobile_no; ?> </p>
					<p style="font-size:14px;margin: 0 0 5px;"> <?php echo $email; ?> </p>
				</div>
		</div>
		<div style="background-color:#fff;width: 100%;">
			<div style="background-color:#fff;margin: 0 auto;width: 100%;">
				<h1 style="font-size:18px;margin:0;padding:15px;"> <span style="color:#A8A9B2;"> Account Statement for : </span> <?php echo $end; ?> to <?php echo $start; ?> </h1>
			</div>
		</div>
		<div style="height:10px;background-color:#DD1155;width: 100%;"></div>
		<div style="height:10px;background-color:#950233;width: 100%;"></div>
		<div style="background-color:#fff;margin: 0 auto;width: 100%;">
			<table cellpadding="0" cellspacing="0" border="0" class="table-content-wrap" style="margin: 0 auto;width: 100%;font-size:13px;">
				<thead style="text-transform:uppercase;font-weight: bold;">
					<tr>
						<td style="border-bottom:1px solid #4a4a4a;padding:8px 5px;"> Date & Time </td>
						<td style="border-bottom:1px solid #4a4a4a;padding:8px 5px;"> Transaction Details </td>
						<td style="border-bottom:1px solid #4a4a4a;padding:8px 5px;"> Amount </td>
						<td style="border-bottom:1px solid #4a4a4a;padding:8px 5px;text-align:right;"> Transaction Status </td>
					</tr>
				</thead>
				<tbody>
					<?php 
					foreach ($transaction_array as $key => $value) {
					?>
					<tr>
						<td style="border-bottom:1px solid #999999;padding:15px 5px;">
							<?php echo $value['created_at'];?>
							<span style="color:#A8A9B2;display:block;"> <?php echo $value['time']; ?> </span>
						</td>
						<td style="border-bottom:1px solid #999999;padding:15px 5px;">
							<h4 style="margin:0;"><?php echo $value['title']; ?> </h4>
							<h4 style="margin:0;font-weight:normal;"> Order #<?php echo $value['order_number']; ?> </h4>
							 <span style="color:#A8A9B2;display:block;"> &nbsp; </span>
						</td>
						<td style="border-bottom:1px solid #999999;padding:15px 5px;font-weight:bold;"> <?php echo $value['sig']; ?> Rs. <?php echo $value['amount']; ?> </td>
						<td style="border-bottom:1px solid #999999;padding:15px 5px;text-align:right;text-transform:uppercase;color:#3EDA8C;"> <?php echo $value['transaction_status']; ?> </td>
					</tr>
				<?php } 
				?>
				<tr>
					<td colspan="4">
						<h5 style="font-size:13px;margin:0;font-weight: normal;padding:10px 0px;">This is system generated transaction history.</h5>
					</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>

</body>
</html>