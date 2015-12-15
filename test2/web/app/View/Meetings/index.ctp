<div class="inner_pages_heading" style="background:#fff; border:0">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="intro-text"> </div>
      </div>
    </div>
  </div>
</div>
<div class="container">    
<div class="clearfix"></div>
<div class="row margin_top_referral_search">
    
       
	<div class="col-md-12 col-sm-8">
	<?php if(!empty($breezsessionValue)) { ?>
	<iframe target="_top" src="https://foxhopr.adobeconnect.com<?php echo $slotData['AvailableSlots']['url_path'];?>?session=<?php echo $breezsessionValue;?>" height="600" width="1140" frameborder="0"> </iframe>
	<?php } else {
		echo '<h1>The Meeting you are looking for has already been ended.</h1>';
		}	?>

	</div>
</div>
</div>