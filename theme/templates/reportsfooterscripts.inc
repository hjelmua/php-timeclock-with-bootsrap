
  
<!-- script footer-->


<!-- jQuery 2.2.3 -->
<script src="../theme/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="../theme/AdminLTE/bootstrap/js/bootstrap.min.js"></script>
<!-- Select2 -->
<script src="../theme/AdminLTE/plugins/select2/select2.full.min.js"></script>
<!-- InputMask -->
<script src="../theme/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
<script src="../theme/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="../theme/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- bootstrap datepicker -->
<script src="../theme/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- bootstrap time picker -->
<script src="../theme/AdminLTE/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- SlimScroll -->
<script src="../theme/AdminLTE/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../theme/AdminLTE/plugins/fastclick/fastclick.js"></script>

<!-- AdminLTE App -->
<script src="../theme/AdminLTE/dist/js/app.min.js"></script>



<script>
//Initialize Select2 Elements
    $(".select2").select2();
//Date picker
    $('#datepicker').datepicker({
      autoclose: true,
      format: 'd/m/yyyy',
 weekStart: 1,
	    todayHighlight: true
    });
    $('#datepicker1').datepicker({
      autoclose: true,
      format: 'd/m/yyyy',
       weekStart: 1,
	    todayHighlight: true
    });

	     $("#timepicker1").timepicker({
	              template: false,
	              showInputs: false,
			 showMeridian: false,
	              minuteStep: 5
	          });

	    //Timepicker
	    $(".timepicker").timepicker({
	      showInputs: false,
			showMeridian: false,
				              minuteStep: 5 
	    });	
</script>

</body>
</html>
