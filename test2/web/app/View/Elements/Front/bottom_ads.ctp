<?php
$adImage = (!empty($bottomAds['Advertisement']['ad_image'])) ? "uploads/ads/".$bottomAds['Advertisement']['ad_image'] : "uploads/ads/bottom-ads.jpg";
$adTitle = (!empty($bottomAds['Advertisement']['title'])) ? $bottomAds['Advertisement']['title'] : "";
$url = (!empty($bottomAds['Advertisement']['target_url'])) ? $bottomAds['Advertisement']['target_url'] : "";
?>
<div class="clearfix"></div>
<div class="clearfix"></div>
<div class="container">
	<div class="row">
		<div class="col-md-12">
		<?php if(!empty($url)){
			      echo $this->Html->link($this->Html->image($adImage,array('title'=>$adTitle,'class'=>'thumbnail img-responsive img-centered advirtise_img')), $url, array('target'=>'blank','escape' => false));
		      }else{
		      	  echo $this->Html->image($adImage,array('title'=>$adTitle,'class'=>'thumbnail img-responsive img-centered advirtise_img'));
		      }
		?>
		</div>
	</div>
</div>
