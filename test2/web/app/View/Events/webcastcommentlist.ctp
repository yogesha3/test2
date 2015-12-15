<div class="clearfix"></div>
<?php 
if(!empty($webcastComment)) { 
    foreach($webcastComment as $data) : 
        ?>
    <div class="media">
        <div class="pull-left">
            <?php
            if(!empty($data['BusinessOwners']['profile_image'])) {
                echo $this->Html->image('uploads/profileimage/'.$data['BusinessOwners']['user_id'].'/resize/'.$data['BusinessOwners']['profile_image'], array('alt' => 'Sample Image', 'class' => 'media-object', 'height' => 50, 'width' => 50));
            } else {
                echo $this->Html->image('no_image.png', array('alt' => 'no_image', 'class' => 'media-object', 'height' => 50, 'width' => 50));
            }        
            ?>
        </div>
        <div class="media-body">
            <div class="media-heading"><?php echo ucFirst($data['BusinessOwners']['fname']) . " " . ucFirst($data['BusinessOwners']['lname']); ?>:
                <span class="alex-proto"><?php echo html_entity_decode(htmlspecialchars($data['WebcastComment']['comments'])); ?> </span>
            </div>
            <div class="clearfix">&nbsp;</div>
            <div class="send_date">Posted  on <?php echo $this->Functions->dateTime($data['WebcastComment']['created']);?></div>
        </div> 
    </div>
<?php endforeach; } else {?>
<center id="ncp"><h4>No comments posted</h4>
</center>
<?php }