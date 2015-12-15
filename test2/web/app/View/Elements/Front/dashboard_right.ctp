<style>
.live_feeds{position:relative;min-height:30px;max-height:300px;_height:expression(this.scrollHeight>899?"300px":"auto");overflow:auto;overflow-x:hidden;}
.live_feeds > div#rays {left: 30% !important;top: 30% !important;}
</style>
<div class="col-md-3 col-sm-4 myleft_panel">
	<div class="clearfix">&nbsp;</div>
	<div class="clearfix">&nbsp;</div>
	<div class="panel panel-default">
	<?php $tooltipText = '<ul><li>Bronze Member - Upto '.$membershipData[0]['Membership']['upper_limit'].' sent referrals </li>';
$tooltipText.= '<li>Silver Member - Between '.$membershipData[1]['Membership']['lower_limit'].'-'.$membershipData[1]['Membership']['upper_limit'].' sent referrals </li>'; 
$tooltipText.= '<li>Gold Member - Between '.$membershipData[2]['Membership']['lower_limit'].'-'.$membershipData[2]['Membership']['upper_limit'].' sent referrals </li>';
$tooltipText.= '<li>Platinum Member - Above '.$membershipData[2]['Membership']['upper_limit'].' sent referrals</li></ul>';?>
		<div class="panel-heading"><span><?php echo $level;?> Member</span> <?php if($membershipUpdated==true && !$levelMessageViewed) {echo '<span class="new_badge">New</span>&nbsp; ';}?><i class="fa fa-info-circle custom_tooltip" data-placement="top" title="" data-original-title="<?php echo $tooltipText;?>"></i></div>
		<div class="rating-star2 text-center">
			<div id="stars-existing" class="starrr stars_rat star_color" data-rating=<?php echo $totalAvgRating;?>></div>  &nbsp;<br>
				<span><?php echo $totalReview;?> review(s)</span>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">Updates</div>
		<div class="panel-body text-center panel_text live_feeds">
			<div id="rays" style="position:inherit;"><?php echo $this->Html->image('loding-logo.png',array('id'=>'liveFeedWait','class'=>'center-block img-responsive'));?></div>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">Upcoming Events</div>
		<div class="panel-body text-center panel_text">
			<div class="border_head">
				<div class="text_head">1.</div>
			</div>
			<div class="clearfix"></div>
			<div class="event_text">Uptown Happy Hour Uptown in the Harlem You
				have 3 new reviews. Other message can go here!</div>
		</div>
		<div class="panel-body text-center panel_text">
			<div class="border_head">
				<div class="text_head">2.</div>
			</div>
			<div class="clearfix"></div>
			<div class="event_text">Uptown Happy Hour Uptown in the Harlem You
				have 3 new reviews. Other message can go here!</div>
		</div>
		<div class="panel-body text-center panel_text">
			<div class="border_head">
				<div class="text_head">3.</div>
			</div>
			<div class="clearfix"></div>
			<div class="event_text">Uptown Happy Hour Uptown in the Harlem You
				have 3 new reviews. Other message can go here!</div>
		</div>
	</div>
	<div class="panel panel-default webcast_dash">
		<div class="panel-heading">Newest Webcast</div>
		<div class="panel-body panel_text">
			<div class="media">
				<div class="media-left">
				<?php if(!empty($webcast)) {
					$videoThumbnailArr = explode('v=', $webcast['Webcast']['link']);							
					echo $this->Html->link(
							$this->Html->image('http://img.youtube.com/vi/'.$videoThumbnailArr[1].'/mqdefault.jpg',array('width' => 99 , 'height'=>56)),
							array('controller' => 'events','action' => 'webcast',$webcast['Webcast']['id']),array('escape'=>false)
						);
				}else{
					echo $this->Html->image('no_video.png',array('alt'=> 'no_video'));
				}?>
				</div>
				<div class="media-body">
					<h4 class="media-heading"> <?php echo $webcast['Webcast']['title']; ?></h4>
					<div class="clearfix"></div>
					<?php echo date('M d, Y',strtotime($webcast['Webcast']['created']));?>
				</div>
			</div>
		</div>
	</div>
	<div class="panel panel-default">	
		<div class="panel-heading">Profile Completion</div>
		<div class="panel-body text-center" style="background: #fff">
			<div class="progress My_progress">
				<div class="progress-bar progress-bar-success progress-bar-striped " role="progressbar"
					aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
					style="width: <?php echo $percentage;?>%"><?php echo $percentage;?>%</div>
			</div>
			<div class="progresbar_text">
				<?php echo $this->Html->link ( 'Edit Profile', array (
						'controller' => 'businessOwners',
						'action' => 'profile',
						'edit' 
					), array (
						'style' => 'color:#F05A28' 
					) );
				?>
				<div class="pull-right persent"><?php echo $percentage;?>% Complete</div>
			</div>
		</div>
	</div>
	
	<!-- Fedback Form Starts -->
	<div class="panel panel-default">	
		<div class="panel-heading">Suggestions</div>
		<div class="panel-body " style="background: #fff">
		<div class="row">
		<div class="col-md-12">
		<?php echo $this->Form->create('Suggestion', array('url' => array('controller' => 'suggestions', 'action' => 'add'),'id'=>'suggestion_form'));
		echo $this->Form->textarea('message',array('class'=>'suggestion_box form-control','placeholder'=>'Post your suggestions here...'));
		echo $this->Form->button('Submit', array('type' => 'submit','class'=>"btn btn-sm file_sent_btn pull-right suggestions_btn"));
		echo $this->Form->end(); ?>
		</div>
		</div>
		</div>
	</div>
	<!-- Feedback Form Ends -->
	
	<div class="advertisement">
		<?php
			$adImage = (!empty($rightAds['Advertisement']['ad_image'])) ? "uploads/ads/".$rightAds['Advertisement']['ad_image'] : "uploads/ads/right-ads.jpg";
			$adTitle = (!empty($rightAds['Advertisement']['title'])) ? $rightAds['Advertisement']['title'] : "";
			$adsurl = (!empty($rightAds['Advertisement']['target_url'])) ? $rightAds['Advertisement']['target_url'] : "";
			if(!empty($adsurl)){
			    echo $this->Html->link($this->Html->image($adImage,array('title'=>$adTitle,'width'=>'300','class'=>'thumbnail img-responsive')), $adsurl, array('target'=>'blank','escape' => false));
		    }else{
		      	echo $this->Html->image($adImage,array('title'=>$adTitle,'width'=>'300','class'=>'thumbnail img-responsive'));
		    }
		?>		
	</div>
</div>
<script> var action = 'listing';</script>
<?php echo $this->Html->script('Front/rating');?>
