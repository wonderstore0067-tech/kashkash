<!-- Jquery Core Js --> 
 <!-- slimscroll, waves Scripts Plugin Js -->

<script src="<?php echo base_url();?>assets/bundles/morrisscripts.bundle.js"></script><!-- Morris Plugin Js -->
<script src="<?php echo base_url();?>assets/bundles/jvectormap.bundle.js"></script> <!-- JVectorMap Plugin Js -->
<script src="<?php echo base_url();?>assets/plugins/jvectormap/jquery-jvectormap-us-aea-en.js"></script><!-- USA Map Js -->
<script src="<?php echo base_url();?>assets/bundles/knob.bundle.js"></script> <!-- Jquery Knob, Count To, Sparkline Js -->
<script src="<?php echo base_url();?>assets/plugins/autosize/autosize.js"></script> <!-- Autosize Plugin Js --> 
<script src="<?php echo base_url();?>assets/plugins/momentjs/moment.js"></script> <!-- Moment Plugin Js --> 
<script src="<?php echo base_url();?>assets/plugins/dropzone/dropzone.js"></script> <!-- Dropzone Plugin Js -->
<!-- Bootstrap Material Datetime Picker Plugin Js --> 

 <script src="<?php echo base_url();?>assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>

<!-- <script src="<?php //echo base_url();?>assets/plugins/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>  -->

<!-- <script src="<?php //echo base_url();?>assets/js/pages/forms/basic-form-elements.js"></script> -->
<script src="<?php echo base_url();?>assets/bundles/mainscripts.bundle.js"></script>
<script src="<?php echo base_url();?>assets/js/pages/index.js"></script>
<script src="<?php echo base_url();?>assets/js/sweetalert.min.js"></script>
<script src="<?php echo base_url();?>assets/js/admincustom.js"></script>
<script src="<?php echo base_url();?>assets/js/parsley.js"></script>
<script src="<?php echo base_url();?>/assets/js/highcharts.js"></script>

</body>

<!-- Mirrored from thememakker.com/templates/oreo/university/html/ by HTTrack Website Copier/3.x [XR&CO'2010], Mon, 10 Sep 2018 11:34:08 GMT -->
</html>
<script type="text/javascript">
	 $('.zmdi-search').click(function(){
	 	if($('#search').val() !==''){
          $('.search_hd').submit();   
      }else{
      	$('.user-searchbox').show();
      	$('#suggesstion-box').html('<li class=\"list-group-item\" >Search field is required<li>').show();
      }
});
	 
  $(".table-responsive, .usersList_wrapper, .dataTables_length, .bootstrap-select").on('click', function(){
 $('.bootstrap-select').addClass("show-tick"); 
  
});


</script>