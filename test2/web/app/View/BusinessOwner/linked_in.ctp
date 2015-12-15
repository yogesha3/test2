<style>
#profile-img { display: none;}
.form_padd_clear{ padding:0}
.form_padd_L0{ padding-left:0 !important}
.form_padd_R0{ padding-right:0 !important}
.form_padd_right{padding-right:30px !important}
@media (max-width: 767px) {
.form_padd_right{padding-right:0px !important}
}
.profile_edit {color: #000000;font-size: 16px;margin-left: 10px;}
.profile_edit:hover {text-decoration:none;}
</style>
<div class="row margin_top_referral_search">
	<div class="col-md-9 col-sm-8">
               <?php echo $this->Element('Front/social_profiles');?>
         <div class="clearfix">&nbsp;</div>
        <div class="row">
        <div class="col-md-12">
        <div class="referral_profile_head"></div>
        </div>
          <div class="col-md-4 text-center-twitter">
         
        <i class="fa fa-linkedin twitter_icon"></i>

        </div>
        <div class="col-md-8">
        <div class=" twitter-Page-head">
        <?php if(!$linkedinConnected) {
            echo 'Authorize FoxHopr to post on your behalf';
        } else {
            echo 'FoxHopr is authorized to post on your behalf';
        }?>
        
        </div>

      <div class="media-body nf_media_body">
        <h4 class="media-heading">   
        <?php if(!$linkedinConnected) {?>
        <a href="<?php echo Router::url(array('controller'=>'BusinessOwners','action'=>'linkedInLogin'),true);?>" class="btn btn-sm file_sent_btn " style="padding: 10px 40px;">Allow Access</a>   </h4>
        <?php } else {
                    echo $this->Html->link($this->Form->button('<i class="fa fa-circle-arrow-left"></i> Revoke Access', array('type' => 'button', 'class' => 'btn btn-sm file_sent_btn revoke_access')),array('controller'=>'businessOwners','action'=>'revokeLinkedIn'), array('escape'=>false));
        }
                ?>
      </div>
      <div class="twitter_text">FoxHopr does not store or have access to your LinkedIn <br>
            login credentials at any time. </div>
        </div>
     <div class="clearfix"></div>
        <div class="col-md-12 ">
            <div class="referral_profile_head"> <span>CONFIGURE POSTS</span></div>
        </div>
        <?php echo $this->Form->create('BusinessOwner');?>
        <div class="notification_box col-md-12">
          <div class="media  border-top00">
          <div class="media-left">
          <input type="checkbox" class="mt0_checkbox" name="linkedin_config[]" value="linkedinReferralSend" <?php if(in_array('linkedinReferralSend', $linkedinData)) { echo 'checked="checked"';}?>> 
          <?php echo $this->Form->hidden('config_type',array('value'=>'linkedin'));?>
          </div>
      <div class="media-body nf_media_body">
        <h4 class="media-heading">When you send a referral    </h4>
        <div class="clearfix"></div>
          Automatically posts: "Just sent a referral to username via FoxHopr"
      </div>
      
    </div>
    
    <div class="media  ">
      <div class="media-left">
          <input type="checkbox" class="mt0_checkbox" name="linkedin_config[]" value="linkedinMessageSend" <?php if(in_array('linkedinMessageSend', $linkedinData)) { echo 'checked="checked"';}?>>
      </div>
      
      <div class="media-body nf_media_body">
        <h4 class="media-heading">When you send a message </h4>
        <div class="clearfix"></div>
          Automatically posts: "Just sent a message to username via FoxHopr"  
          </div>
      
        </div>
    
    <div class="media  ">
      <div class="media-left">
          <input type="checkbox" class="mt0_checkbox" name="linkedin_config[]" value="linkedinInviteSend" <?php if(in_array('linkedinInviteSend', $linkedinData)) { echo 'checked="checked"';}?>>
  
      </div>
      <div class="media-body nf_media_body">
        <h4 class="media-heading">When you send an event invitation
       </h4>
        <div class="clearfix"></div>
        Automatically posts: "Just set up a calendar event with username via FoxHopr" 
          </div>
      
    </div>
      
      <div class="clearfix">&nbsp;</div>
       <div class="clearfix">&nbsp;</div> 
       <?php $saveClass=(!$linkedinConnected)?'disabled':''; ?>
      <button class="btn btn-sm file_sent_btn pull-right <?php echo $saveClass;?>" type="submit">Save</button>
      </div>        
        </div>
      </div>
    <?php echo $this->element("Front/loginSidebar",array('tabpage' => 'linkedinSidebar'));?>
    </div>
    <?php echo $this->element('Front/bottom_ads');?>
    </section>
    <script>
    var ajaxUrl = "<?php echo Router::url(array('controller'=>'users','action'=>'getStateCity'));?>";
    var imgPath = "<?php echo $this->webroot; ?>img/icons/error.png";
    </script>
    <?php echo $this->Html->script ( 'Front/profile' );