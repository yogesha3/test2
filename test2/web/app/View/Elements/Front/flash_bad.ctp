<!-- <div id="notification_bar" class="alert alert-danger" style="margin-top:40px;"><?php echo $message; ?></div> -->
<div class="container topspace col-md-12 ">
<div class="alert alert-danger" id="flashMessage"><button data-dismiss="alert" class="close"> × </button><?php echo $message; ?></div>
</div>
 <script type="text/javascript">
	jQuery(document).ready(function () {
		$('#flashMessage').slideDown();
		setTimeout(function(){
			$("#flashMessage").html("");
			$('#flashMessage').slideUp();
		}, 5000);
	});
</script> 
