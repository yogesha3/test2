<div class="media">
		<?php
		if(!empty($referralData['profile_image'])) {
			echo $this->Html->image('uploads/profileimage/'.$referralData['user_id'].'/resize/'.$referralData['profile_image'], array('alt' => 'Sample Image', 'class' => 'media-object pull-left', 'height' => 50, 'width' => 60));
		} else {
			echo $this->Html->image('no_image.png', array('alt' => 'no_image', 'class' => 'media-object pull-left', 'height' => 50, 'width' => 60));
		}        
		?>
	<div class="media-body">
		<div class="media-heading"><?php echo ucFirst($referralData['fname']) . " " . ucFirst($referralData['lname']); ?>:
			<span class="alex-proto"><?php echo html_entity_decode($referralData['comment']); ?> </span>
		</div>
		<div class="clearfix">&nbsp;</div>
		<div class="send_date">Posted  on <?php echo $this->Functions->dateTime($referralData['created']);?></div>
	</div> 
</div>
<script>
$(document).ready(function(){
var ajacContainer=$('.ajaxUpdate');
ajacContainer.scrollTop(ajacContainer[0].scrollHeight);
});
</script>
