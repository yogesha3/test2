<?php echo $this->Html->script('Front/all');?>
<div class="row margin_top_referral_search">
	<div class="col-md-9 col-sm-8">
		<div class="row">
			<div class="col-md-12">
				<div class="referrals_reviews">
					<div class="referrals_reviews_head">Referral Reviews</div>
					<div class="col-md-3 pull-right padd-left0 padd-right0 ">
						<div class="rating-star margin_clear"> 
							<div id="stars-existing" class="starrr stars_rat star_color" data-rating=<?php echo $totalAvgRating;?>></div> 
							<span class="rating_23"><?php //echo $totalReview;?></span> 
						</div>
						<div class="stars5"> <?php /*echo $totalAvgRating;*/ echo $totalReview ?> review(s)</div>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="reviews_div">
					<div class=" graph">
						<div class="col-md-12">
							<div class="col-md-5 graph_img pull-right">
								<div class="clearfix">&nbsp;</div>
							</div>

							<div class="ajaxUpdate2">																
							</div>  
						</div>
						<?php if($totalReview > 5) {
							echo '<div class="clearfix"></div>';
				        	echo '<div class="view_comment view_comment2"><a class="viewmorecomment" class="" href="#">Show More</a></div> ';
				        }
				        ?>          
						<div class="clearfix"></div>
					</div>        
				</div>
			</div>
		</div>
	</div>
	<?php echo $this->element("Front/loginSidebar",array('tabpage' => 'review'));?>
</div>	
<script>var action = 'listing'; </script>
<?php echo $this->element('Front/bottom_ads');?>
<?php echo $this->Html->script('Front/reviews');?>
