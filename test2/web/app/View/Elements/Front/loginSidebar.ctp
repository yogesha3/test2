<div class="col-md-3 col-sm-4 right_sidebar">	
	<?php if(isset($tabpage) && $tabpage=="messagecompose"){?>
	<!--<a class=" search_newreferral_btn" href="composeMessage"> <span class="newreferral">New Message </span>
		<div class="clearfix"></div> <span class="send_referral">Compose a message</span>
	</a>-->
	<?php }elseif(isset($tabpage) && ($tabpage=="messageinbox" || $tabpage=="sentMessages" || $tabpage=="inboxarchive" || $tabpage=="sentMessagesArchive")){ ?>
	<a class=" search_newreferral_btn" href="<?php echo Router::url(array('controller'=>'messages','action'=>'composeMessage'));?>"> <span class="newreferral">Compose a message </span>
		<div class="clearfix"></div>
	</a>
	<?php }elseif(isset($tabpage) && $tabpage=="referralsSend"){ ?>
	<!--<a class=" search_newreferral_btn" href="<?php echo Router::url(array('controller'=>'referrals','action'=>'sendReferrals'));?>"> <span class="newreferral">New Referral</span>
		<div class="clearfix"></div> <span class="send_referral">Send a referral</span>
	</a>-->
	<?php }elseif(isset($tabpage) && $tabpage=="referralsSent"){ ?>
	<a class=" search_newreferral_btn" href="<?php echo Router::url(array('controller'=>'referrals','action'=>'sendReferrals'));?>"> <span class="newreferral">Send a referral</span>
		<div class="clearfix"></div>
	</a>
	<?php }elseif(isset($tabpage) && $tabpage=="referralsDetail"){ ?>
	<a class=" search_newreferral_btn" href="<?php echo Router::url(array('controller'=>'referrals','action'=>'sendReferrals'));?>"> <span class="newreferral">Send a referral</span>
		<div class="clearfix"></div>
	</a>
	<?php }elseif(isset($tabpage) && ($tabpage=="referralsReceived" || $tabpage=="receivededit" || $tabpage=="sent" || $tabpage=="received")){ ?>
	<a class=" search_newreferral_btn" href="<?php echo Router::url(array('controller'=>'referrals','action'=>'sendReferrals'));?>"> <span class="newreferral">Send a referral</span>
		<div class="clearfix"></div>
	</a>
   
  <?php }elseif(isset($tabpage) && ($tabpage=="team" || $tabpage=="accountprofile")){?>
  
  <?php }elseif(isset($tabpage) && $tabpage=="contactadd"){ ?>
  <?php }elseif(isset($tabpage) && $tabpage=="contactList"){ ?>
    <a class=" search_newreferral_btn" href="<?php echo Router::url(array('controller'=>'contacts','action'=>'addContact'));?>">
        <span class="newreferral">Create a new contact</span>
        <div class="clearfix"></div>
    </a>
	<?php }elseif(isset($tabpage) && $tabpage=="contactDetail"){ ?>
    <a class=" search_newreferral_btn" href="<?php echo Router::url(array('controller'=>'contacts','action'=>'addContact'));?>">
        <span class="newreferral">Create a new contact</span>
        <div class="clearfix"></div>
    </a>
    <?php }elseif(isset($tabpage) && $tabpage=="invitePartners"){ ?>    
        <div class="clearfix">&nbsp;</div>
    <?php }elseif(isset($tabpage) && $tabpage=="memberDetail"){?>
    
    <?php }elseif(isset($tabpage) && $tabpage=="partnersList"){ ?>   
    <a href="<?php echo Router::url(array('controller'=>'contacts','action'=>'invitePartners'),true);?>" class=" search_newreferral_btn">
         <span class="newreferral">Invite a new partner</span>
    <div class="clearfix"></div>
    </a>
	<?php }elseif(isset($tabpage) && $tabpage=="contactUpdate"){ ?>
    <a class=" search_newreferral_btn" href="<?php echo Router::url(array('controller'=>'contacts','action'=>'addContact'));?>">
        <span class="newreferral">Create a new contact</span>
        <div class="clearfix"></div>
    </a>
  <?php }elseif(isset($tabpage) && $tabpage=="changePassword"){ ?>
	<?php }elseif(isset($tabpage) && $tabpage=="notifications"){ ?>
  <?php }elseif(isset($tabpage) && $tabpage=="webcast"){ ?>
	<?php }elseif(isset($tabpage) && ($tabpage=="twitterSidebar" || $tabpage=="facebookSidebar" || $tabpage=="linkedinSidebar")){?>
	<?php }elseif(isset($tabpage) && $tabpage=="videotraining"){?>
	<?php }elseif(isset($tabpage) && $tabpage=="review"){?>
	<?php }elseif(isset($tabpage) && $tabpage=="billing"){ ?>
	<?php }elseif(isset($tabpage) && $tabpage=="previousTeam"){ ?>
	<?php }elseif(isset($tabpage) && $tabpage=="goals"){ ?>
	<?php }elseif(isset($tabpage) && $tabpage=="contactsimport"){ ?>
	
	<?php }else {?>
	<a class=" search_newreferral_btn" href="<?php echo Router::url(array('controller'=>'messages','action'=>'composeMessage'));?>"> <span class="newreferral">Compose a message</span>
		<div class="clearfix"></div>
	</a>
	<div class="clearfix"></div>
	<?php }?>
	<?php if(isset($tabpage) && $tabpage=="review"){?>
	<div class="gold_member_div thumbnail">
		<div class="gold_member_text"><?php echo $level;?> Member</div>
		<div class="star5">
			<span class="star_rating star_rating_color">5 Star</span>
			<div class="progress">
				<div style="width:<?php echo isset($review5StarPercent) ? $review5StarPercent : '0';?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="60" role="progressbar" class="progress-bar progress-bar-warning">
				</div>    
			</div>
			<span class="star_rating_present "><?php echo isset($review5StarPercent) ? $review5StarPercent : '0';?>%</span>          
		</div>       
		<div class="star5">
			<span class="star_rating star_rating_color">4 Star</span>
			<div class="progress">
				<div style="width:<?php echo isset($review4StarPercent) ? $review4StarPercent : '0';?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="60" role="progressbar" class="progress-bar progress-bar-warning">
				</div>
			</div>
			<span class="star_rating_present "><?php echo isset($review4StarPercent) ? $review4StarPercent : '0';?>%</span>          
		</div>         
		<div class="star5">
			<span class="star_rating star_rating_color">3 Star</span>
			<div class="progress">
				<div style="width:<?php echo isset($review3StarPercent) ? $review3StarPercent : '0';?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="60" role="progressbar" class="progress-bar progress-bar-warning">
				</div>
			</div>
			<span class="star_rating_present "><?php echo isset($review3StarPercent) ? $review3StarPercent : '0';?>%</span>
		</div>          
		<div class="star5">
			<span class="star_rating star_rating_color">2 Star</span>
			<div class="progress">
				<div style="width:<?php echo isset($review2StarPercent) ? $review2StarPercent : '0';?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="60" role="progressbar" class="progress-bar progress-bar-warning">
				</div>
			</div>
			<span class="star_rating_present "><?php echo isset($review2StarPercent) ? $review2StarPercent : '0';?>%</span>
		</div>          
		<div class="star5">
			<span class="star_rating star_rating_color">1 Star</span>
			<div class="progress">
				<div style="width:<?php echo isset($review1StarPercent) ? $review1StarPercent : '0';?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="60" role="progressbar" class="progress-bar progress-bar-warning">
				</div>
			</div>
			<span class="star_rating_present "><?php echo isset($review1StarPercent) ? $review1StarPercent : '0';?>%</span>         
		</div>         
	</div>	
	<div class="clearfix"></div>
	
	<?php } ?>
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
	<div class="clearfix"></div>
	<?php if($tabpage=='twitterSidebar') {?>
        <!--  <div class="referral_received margintop0"> Why bother?</div> -->
        <!--  <div class="archive_text">Check out <span class="twitter101">Twitter 101</span> to understand why businesses should take advantage of Twitter.</div> -->
        <!--  <div class="clearfix">&nbsp;</div> -->
        <div class="referral_received margintop0"> How it works</div>
        <div class="archive_text">Allow FoxHopr to automatically tweet on your behalf when you do things like send referrals.</div>
            <div class="clearfix">&nbsp;</div>
<!--         <div class="referral_received margintop0"> How to turn it on</div> -->
<!--         <div class="archive_text">Sign-up for a Twitter account and then click on the Grant Access button to give FoxHopr access to tweet. Then check off the boxes below to enable each type of tweet.</div> -->
            
<!--         <div class="clearfix">&nbsp;</div> -->
        <div class="clearfix">&nbsp;</div>
	<?php } elseif($tabpage=='linkedinSidebar') {?>
	<div class="referral_received margintop0"> How it works</div>
        <div class="archive_text">Allow FoxHopr to automatically post on your behalf when you do things like send referrals.</div>
<!--             <div class="clearfix">&nbsp;</div> -->
<!--         <div class="referral_received margintop0"> How to turn it on</div> -->
<!--         <div class="archive_text">Sign-up for a Linkedin account and then click on the Grant Access button to give FoxHopr access to post. Then check off the boxes below to enable each type of post.</div> -->
            
        <div class="clearfix">&nbsp;</div>
	<?php } elseif($tabpage=='facebookSidebar') {?>
	<div class="referral_received margintop0"> How it works</div>
        <div class="archive_text">Allow FoxHopr to automatically post on your behalf when you do things like send referrals.</div>
<!--             <div class="clearfix">&nbsp;</div> -->
<!--         <div class="referral_received margintop0"> How to turn it on</div> -->
<!--         <div class="archive_text">Sign-up for a Facebook account and then click on the Grant Access button to give FoxHopr access to post. Then check off the boxes below to enable each type of post.</div> -->
            
        <div class="clearfix">&nbsp;</div>
	<?php }?>
	<?php if($this->params['controller'] == 'events' && $this->params['action'] == 'webcast') {?>
	<?php if(!empty($webcastArr)) {?>
	<div class="left_video">
		<div class="up-next"><b>Up Next</b></div><br>
		<?php 
			foreach($webcastArr as $webcast) {
				$videoThumbnailArr = explode('v=', $webcast['Webcast']['link']);
				echo '<div class="left_video_wrapper">';
				echo $this->Html->link(
									$this->Html->image('http://img.youtube.com/vi/'.$videoThumbnailArr[1].'/mqdefault.jpg',array('width' => 265 , 'height'=>112)),
									array('controller' => 'events','action' => 'webcast',$webcast['Webcast']['id']),array('escape'=>false));
				?>
				<div class="left_video_text">
					<?php echo $this->Html->link(
									$webcast['Webcast']['title'],
									array('controller' => 'events','action' => 'webcast',$webcast['Webcast']['id'])); ?>
					<div class="clearfix"></div>
					<span><?php echo $this->Functions->dateTime($webcast['Webcast']['created']);?></span>
				</div>
				</div>	
		<?php } ?>
	</div>
	<?php 
	if($webcastCount > 3) {
		echo $this->Paginator->next('Show More',array('class' => 'btn btn-sm back_btn pull-right text-center add_focus show-mpre-btn show_btn1 file_sent_btn','tag' =>false));
	}	
	?>
	<?php } } else { ?>
	
	<?php if(isset($tabpage) && $tabpage=="messagecompose"){?>
	<div class="inbox">Compose Message</div>
	<div class="archive_text">Use this form to send a message to one or all your team members.</div>
	<?php }elseif(isset($tabpage) && $tabpage=="partnersList"){?>
	<div class="inbox">Partners</div>
	<div class="archive_text">Partners are people whom you invite to join FoxHopr. Typically, they are the ones when signed-up will earn you referral bonus.</div>
	<?php }elseif(isset($tabpage) && $tabpage=="memberDetail"){?>
	
	<?php }elseif(isset($tabpage) && $tabpage=="messageinbox"){ ?>
	<div class="inbox">Inbox</div>
	<div class="archive_text">This page lists all of the messages that you’ve received from other team members.</div>
	<?php }elseif(isset($tabpage) && $tabpage=="referralsSend"){ ?>
	<div class="referral_received margintop0"> Send a Referral</div>
	<div class="archive_text">Use this form to send a referral to a team member</div>
	<?php }elseif(isset($tabpage) && $tabpage=="referralsSent"){ ?>
	<div class="referral_received margintop0"> Referral Sent</div>
	<div class="archive_text">This page lists all of the referrals you’ve sent to group members in your network.</div>
	<?php }elseif(isset($tabpage) && $tabpage=="referralsReceived"){ ?>
	<div class="referral_received margintop0"> Referral Received</div>
	<div class="archive_text">This page lists all of the referrals you’ve received from group members in your network.</div>
	<?php }elseif(isset($tabpage) && $tabpage=="sent"){ ?>
	<div class="referral_received margintop0"> Archive</div>
	<div class="archive_text">This page lists all of the referrals archived.</div>
	<?php }elseif(isset($tabpage) && $tabpage=="received"){ ?>
	<div class="referral_received margintop0"> Archive</div>
	<div class="archive_text">This page lists all of the referrals archived received.</div>
	<?php }elseif(isset($tabpage) && $tabpage=="referralsDetail"){ ?>
<!--		<div class="statuses">Referral Statuses</div>
		<div class="clearfix"></div>
		<div class="received_head"> <strong>1.</strong> Received  </div>
		<div class="received_text">The referral has been received by the recipient</div>	
		<div class="received_head"> <strong>2.</strong>  Contacted    </div>
		<div class="received_text">  The recipient has made one or more attempts to contact the referral</div>	
		<div class="received_head"> <strong>3.</strong> Proposal   </div>
		<div class="received_text">A formal quote or estimate has been provided to the referral</div>	
		<div class="received_head"> <strong>4.</strong> Success  </div>
		<div class="received_text">  The recipient has successfully engaged in business with the referral</div>	
		<div class="received_head"> <strong>5.</strong> Kaput  </div>
		<div class="received_text">The recipient was unable to engage in business with the referral</div>-->
		<?php }elseif(isset($tabpage) && $tabpage=="accountprofile"){ ?>
		<div class="sent_message ">Profile</div>
		<div class="archive_text">This page is your profile. Information entered here will be seen by all team members.</div>
	<?php }elseif(isset($tabpage) && $tabpage=="sentMessages"){ ?>
		<div class="sent_message ">Sent Message</div>
		<div class="archive_text">This page lists all of the messages that you’ve sent to other team members.</div>
	<?php }elseif(isset($tabpage) && $tabpage=="inboxarchive"){ ?>
		<div class="sent_message ">Inbox Archive</div>
		<div class="archive_text">This page lists all of the messages that you’ve archived from inbox .</div>
	<?php }elseif(isset($tabpage) && $tabpage=="sentMessagesArchive"){ ?>
		<div class="sent_message ">Sent Archive</div>
		<div class="archive_text">This page lists all of the messages that you’ve deleted from sent message folder.</div>    
	<?php }elseif(isset($tabpage) && $tabpage=="team"){ ?>
		<div class="referral_received">Team Members</div>
		<div class="archive_text">This page lists all of your current team members. You can send a referral, message or set up an event with anyone you see here.</div>
	<?php }elseif(isset($tabpage) && $tabpage=="previousTeam"){ ?>
	<div class="referral_received">Previous Team Members</div>
	<div class="archive_text">This page lists all of your previous team members in your group. You can send a referral, message or set up an event with anyone you see here.</div>
	<?php }elseif(isset($tabpage) && $tabpage=="goals"){ ?>
	<div class="referral_received">Set Goals</div>
	<div class="archive_text">This page allows you to set goals for Individual team members, teams + personal goals</div>
	<?php }elseif(isset($tabpage) && $tabpage=="contactadd"){ ?>
		<div class="referral_received">Add A Contact</div>
    <div class="archive_text">Use this form to add a new contact.</div>
    <?php }elseif(isset($tabpage) && $tabpage=="contactsimport"){ ?>
		<div class="referral_received">Import Contact</div>
    <div class="archive_text">Use this form to import contacts.</div>
	<?php }elseif(isset($tabpage) && $tabpage=="contactList"){ ?>
		<div class="archive">Contacts</div>
    <div class="archive_text">Contacts are people you do business with .</div>
    <?php }elseif(isset($tabpage) && $tabpage=="contactDetail"){ ?>
		<div class="referral_received">Contact</div>
    <div class="archive_text">Contacts are people you do business with outside of your FoxHopr group. Typically, These are the leads that you would refer to other members of your group. </div>
    <?php }elseif(isset($tabpage) && $tabpage=="contactUpdate"){ ?>
		<div class="referral_received">Update Contact</div>
    <div class="archive_text">Use the form on this page to edit your contact in contact list. Contacts are people you do business with outside of your referral group.</div>
    <?php }elseif(isset($tabpage) && $tabpage=="changePassword"){ ?>
<!--     <div class="referral_received margintop0"> Profile</div> -->
<!--     <div class="archive_text">Your profile contains your contact information. All other group members are able to view your profile.</div> -->
    <?php }elseif(isset($tabpage) && $tabpage=="invitePartners"){ ?>
    <div class="inbox">Invite Partners</div><div class="archive_text">You can send invitations by entering your partners' email addresses. Click "Add" to enter additional email addresses (you can send up to a maximum of 10 invitations at a time).</div>
    <?php }elseif(isset($tabpage) && $tabpage=="videotraining"){ ?>
    <div class="inbox">Training Video</div><div class="archive_text">FoxHopr Team step-by-step interactive video training teaches you about being a team leader.</div>
    <?php }elseif(isset($tabpage) && $tabpage=="billing"){ ?>
    <div class="inbox">Billing</div><div class="archive_text">If you have decided to change your plan in the middle of a billing cycle you’ll be charged the new rate on your next billing cycle.</div>
    <?php }elseif(isset($tabpage) && $tabpage=="notifications"){ ?>
<!-- 		<div class="archive">Notifications</div> -->
<!--     <div class="archive_text">Checking off any of these options will enable email notifications to be sent to you directly. -->
<!-- <div class="clearfix">&nbsp;</div>Make sure to add the address <span class="noreply">noreply@foxhopr.com</span> to your safe sender list to ensure that notifications aren't filtered.</div> -->
	<?php }else{?>
	
	<?php } }?>
</div>	

