<?php if(count($new_messages)){
foreach ($new_messages as $comment){?>
<div class="media-left">
    <?php
      if(!empty($comment['BusinessOwner']['profile_image'])) {
        echo $this->Html->image('uploads/profileimage/'.$comment['BusinessOwner']['user_id'].'/resize/'.$comment['BusinessOwner']['profile_image'], array('alt' => 'Sample Image', 'class' => 'media-object', 'height' => 50, 'width' => 50));
      } else {
        echo $this->Html->image('no_image.png', array('alt' => 'no_image', 'class' => 'media-object', 'height' => 50, 'width' => 50));
      }        
    ?>
  </div>
 <div class=" col-md-9 padd-left0 padd-right0 alex-proto"><b><?php echo ucFirst($comment['BusinessOwner']['fname']) . " " . ucFirst($comment['BusinessOwner']['lname']); ?>: </b> <?php echo $comment['MessageComment']['comment']; ?> 
    <div class="send_time"> Posted  on <?php echo $this->Functions->dateTime($comment['MessageComment']['created']);?></div><br/>
</div><br/>
<?php }?>
<?php $new_last_comment = (count($new_messages)) ? $new_messages[count($new_messages)-1]["MessageComment"]["id"] : $lastComment; ?>
<div id="last-msg" last-database-message="<?php echo $this->Encryption->decode($new_last_comment);?>"></div>
<script>
$(document).ready(function(){
var ajacContainer=$('.ajaxUpdatemsg');
ajacContainer.scrollTop(ajacContainer[0].scrollHeight);
});
</script>
<?php }