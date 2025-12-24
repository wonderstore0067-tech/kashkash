<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  <title>eTIPPERS Banner Details</title>
      <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel='stylesheet prefetch' href='http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css'>
  <!-- <link rel="stylesheet" href="<?php echo base_url().'/assets/css/style.css'?>"> -->
  
</head>
<body style="margin: 0px !important; ">
<div class="content">
	

 <?php 
	      if(!empty(@$banner_data[0]['Advertisement_Image'])){
	      echo '<p>
		  <a href="MY WEBSITE LINK" target="_blank">
		    <img src="'.base_url('uploads/advertisement_img/'.$banner_data[0]['Advertisement_Image']).'" style="height:100;width:100;" border="0" alt="Null">
		  </a></p>';
		}
	      echo (!empty($banner_data[0]['Advertisement_Title'])) ? '<h3>'.ucwords($banner_data[0]['Advertisement_Title']).'</h3>':'';
         echo (!empty($banner_data[0]['Description'])) ? $banner_data[0]['Description'] :'';

?>
	
</div>
</body>

</html>
