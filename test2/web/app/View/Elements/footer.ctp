<?php

?>
</div>
<!-- end: MAIN CONTAINER -->
<div id="popup" class="modal fade modal-sm" tabindex="-1" data-width='auto' style="display: none;">
</div>

<!-- start: FOOTER -->
<div class="footer clearfix">
    <div class="footer-inner">
        <?php echo date('Y')?> &copy;FoxHopr
    </div>
    <div class="footer-items">
        <span class="go-top"><i class="clip-chevron-up"></i></span>
    </div>
</div>
<div class="loader">
		<?php echo $this->Html->image('fancybox_loading@2x.gif',array('id'=>'loading'));?>
		
    </div>
<script>
    jQuery(document).ready(function() {
        Main.init();
UIModals.init();
    });
</script>
<script type="text/javascript">
$(window).load(function() {
	$(".loader").fadeOut("slow");
});
/*$(document).ready(function(){
    $('input[type="submit"]').click(function() {
        this.disabled = true;
    });
});*/
/*
$(document).ready(function(){
$(document).ajaxStart(function(){
	   $(".loader").show();
	 });

$(document).ajaxComplete(function(){
	$(".loader").hide();
	 });
});
*/
</script>


