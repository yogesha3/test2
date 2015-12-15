<?php if(count($new_messages)){
foreach ($new_messages as $comment){?>
<div class="media">
	<?php
	if(!empty($comment['BusinessOwners']['profile_image'])) {
		echo $this->Html->image('uploads/profileimage/'.$comment['BusinessOwners']['user_id'].'/resize/'.$comment['BusinessOwners']['profile_image'], array('alt' => 'Sample Image', 'class' => 'media-object pull-left', 'height' => 50, 'width' => 60));
	} else {
		echo $this->Html->image('no_image.png', array('alt' => 'no_image', 'class' => 'media-object pull-left', 'height' => 50, 'width' => 60));
	}        
	?>
	<div class="media-body">
		<div class="media-heading"><?php echo ucFirst($comment['BusinessOwners']['fname']) . " " . ucFirst($comment['BusinessOwners']['lname']); ?>:
			<span class="alex-proto"><?php echo $comment['ReferralComment']['comment']; ?> </span>
		</div>
		<div class="clearfix">&nbsp;</div>
		<div class="send_date">Posted  on <?php echo $this->Functions->dateTime($comment['ReferralComment']['created']);?></div>
	</div> 
</div>
<?php }?>
<?php $new_last_comment = (count($new_messages)) ? $new_messages[count($new_messages)-1]["ReferralComment"]["id"] : $lastComment; ?>
<div id="last-msg" last-database-message="<?php echo $this->Encryption->decode($new_last_comment);?>"></div>
<script>
$(document).ready(function(){
var ajacContainer=$('.ajaxUpdate');
ajacContainer.scrollTop(ajacContainer[0].scrollHeight);
});
</script>
<?php }