<!-- <div id="notification_bar" class="alert alert-danger" style="margin-top:40px;"><?php echo $message; ?></div> -->
<div class="alert alert-danger" id="flashMessage"><button data-dismiss="alert" class="close"> × </button><?php echo $message; ?></div>
<script type="text/javascript">
	jQuery(document).ready(function () {
		var timeout = 5000;
		<?php if($this->request->params['action'] == 'admin_membershipLevels') {?>
		timeout = 10000;
		<?php }?>
		$('#flashMessage').slideDown();
		setTimeout(function(){
			$("#flashMessage").html("");
			$('#flashMessage').slideUp();
		}, timeout);
	});
</script> 
