<?php echo $this->Html->script('Front/all');?>
<div class="row margin_top_referral_search">
  <div class="col-md-9 col-sm-8">
    <div class="row"> 
      <div class="col-md-12">
        <div class="referrals_reviews">
          <div class="referrals_reviews_head padd-top0">Email Notification</div>
          <div class="clearfix"></div>
      </div>
  </div>
</div>
<div class="clearfix">&nbsp;</div>
<div class="nf_heading">Notifications are sent instantly to your registered email address</div>
<?php
echo $this->Form->create('Notifications',array('id'=>'notifications','url'=>array('controller'=>'businessOwners','action'=>'notifications'),'class'=>'form-horizontal','inputDefaults' => array('label' => false,'div' => false,'errorMessage'=>true),'novalidate'=>true));
?>
<div class="notification_box">
  <div class="media">
    <div class="media-left">
      <input name="noticheck[]" <?php if(in_array('weeklySummery', $notificationEnable)) {echo 'checked';}?> value="weeklySummery" type="checkbox" class="mt0_checkbox"> 
  </div>
  <div class="media-body nf_media_body">
      <h4 class="media-heading">Weekly summary</h4>
      <div class="clearfix"></div>
      Weekly news with member spotlights, events + more !
  </div>
</div>
</div>
<div class="notification_box">
  <div class="media">
    <div class="media-left">
      <input name="noticheck[]" <?php if(in_array('receiveReferral', $notificationEnable)) {echo 'checked';}?> value="receiveReferral" type="checkbox" class="mt0_checkbox"> 
  </div>
  <div class="media-body nf_media_body">
      <h4 class="media-heading">Received referral  </h4>
      <div class="clearfix"></div>
      Notifies you that you've received a referral
  </div>
</div>
</div>
<div class="notification_box">
  <div class="media">
    <div class="media-left">
      <input name="noticheck[]" <?php if(in_array('commentMadeOnReferral', $notificationEnable)) {echo 'checked';}?> value="commentMadeOnReferral" type="checkbox" class="mt0_checkbox"> 
  </div>
  <div class="media-body nf_media_body">
      <h4 class="media-heading">Referral comment</h4>
      <div class="clearfix"></div>
      Notifies you when you've received a comment on a referral
  </div>
</div>
</div>
<div class="notification_box">
  <div class="media">
    <div class="media-left">
      <input name="noticheck[]" <?php if(in_array('receiveMessage', $notificationEnable)) {echo 'checked';}?> value="receiveMessage" type="checkbox" class="mt0_checkbox"> 
  </div>
  <div class="media-body nf_media_body">
      <h4 class="media-heading">Message</h4>
      <div class="clearfix"></div>
      Notifies you that another user has sent you a message 
  </div>
</div>
</div>
<div class="notification_box">
  <div class="media">
    <div class="media-left">
      <input name="noticheck[]" <?php if(in_array('commentMadeOnMessage', $notificationEnable)) {echo 'checked';}?> value="commentMadeOnMessage" type="checkbox" class="mt0_checkbox"> 
  </div>
  <div class="media-body nf_media_body">
      <h4 class="media-heading">Message comment</h4>
      <div class="clearfix"></div>
      Notifies you when you've received a comment on a message
  </div>
</div>
</div>
<div class="notification_box">
  <div class="media">
    <div class="media-left">
      <input name="noticheck[]" <?php if(in_array('receiveEventInvitation', $notificationEnable)) {echo 'checked';}?> value="receiveEventInvitation" type="checkbox" class="mt0_checkbox"> 
  </div>
  <div class="media-body nf_media_body">
      <h4 class="media-heading">Event invitation</h4>
      <div class="clearfix"></div>
      Notifies you that another user has sent you an event invitation
  </div>
</div>
</div>
<div class="notification_box">
  <div class="media">
    <div class="media-left">
      <input name="noticheck[]" <?php if(in_array('commentMadeOnEvent', $notificationEnable)) {echo 'checked';}?> value="commentMadeOnEvent" type="checkbox" class="mt0_checkbox"> 
  </div>
  <div class="media-body nf_media_body">
      <h4 class="media-heading">When a comment is made on a event  </h4>
      <div class="clearfix"></div>
      Notifies you that the other party has commented on a calendar event you sent or received 
  </div>
</div>
</div>
<div class="notification_box">
  <div class="media text-right">
  <button class="btn btn-sm  back_btn " type="submit">Save</button>
</div>
</div>
<?php echo $this->Form->end();?>
</div>
<?php echo $this->element("Front/loginSidebar",array('tabpage' => 'notifications'));?> 
</div>
<?php echo $this->element('Front/bottom_ads');