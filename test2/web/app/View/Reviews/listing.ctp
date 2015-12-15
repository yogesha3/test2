<?php if(!empty($reviewsListing)) { 
	foreach($reviewsListing as $listing):
		?>
	<div class="review_box col-md-12">      
		<div class="rating-star pull-left"> 
			<div id="stars-existing" class="starrr stars_rat star_color" data-rating=<?php echo round($listing['Review']['reviewReferral']/3)?>></div>
			<span class="reviews_date"> &nbsp;
				By <?php echo $listing['ReceivedReferral']['first_name'] .' '.$listing['ReceivedReferral']['last_name']?> 
				<span>on <?php echo date('M d, Y ',strtotime($listing['Review']['created']))?></span>
			</span>        
		</div>
		<div class="clearfix"></div>
		<div class="reviews_description"><?php echo $listing['Review']['comments'];?> 
		</div>
	</div>
<?php endforeach; } else { ?>
<center id="ncp"><h4 class="view_comment2">No reviews posted</h4>
<?php }?>
<?php echo $this->Html->script('Front/rating');?>