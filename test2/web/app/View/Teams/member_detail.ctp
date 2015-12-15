<?php 
$loginUserId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
$profileDetail = ($memberId==$loginUserId) ? true : false;
?>
<style>
.profile_edit {
    color: #000000;
    font-size: 16px;
    margin-left: 10px;
}</style>
<div class="row margin_top_referral_search">
    <div class="col-md-9 col-sm-8">
               <div class="row"> 
         <div class="col-md-8">
      <div class="referrals_reviews">
      <?php if($profileDetail){?>
      		<div class="referrals_reviews_head padd-top0">Profile <?php echo $this->Html->link('',array('action' => 'profile','edit'),array('class'=>'fa fa-edit profile_edit','title'=>'Edit'));?></div>
      <?php }else{?>
            <div class="referrals_reviews_head padd-top0">Team Member Details </div>
      <?php }?>
            <div class="clearfix"></div>
            </div>
            </div>
            <div class="col-md-4 text-right">
                <?php if (!empty($referer)) {?>
        <a href="<?php echo base64_decode($referer);?>" class="back_btn_new pull-right" ><i class="fa fa-arrow-circle-left"></i> Back</a>
        <?php } ?>
            </div>
            </div>
         <div class="clearfix">&nbsp;</div>
        <div class="row">
          <div class="col-md-12">
            <div class="referral_profile_head"></div>
          </div>
          <div class="row margin_clear">
            <div class="col-md-9">
              <div class="media">
                <div class="media-left media_fullwidth"> 
                <?php if($memberData['BusinessOwner']['profile_image']!='') {?>
                <img src="<?php echo Configure::read('SITE_URL').'img/uploads/profileimage/'.$memberData['BusinessOwner']['user_id'].'/resize/'.$memberData['BusinessOwner']['profile_image'];?>" alt="" width="103" height="103">
                <?php } else {?>
                <img src="<?php echo Configure::read('SITE_URL').'img/no_image.png';?>" alt="">
                <?php }?> 
                </div>
                <div class="media-body media-body_paddL icon_awesome">
                  <div class="media-heading kevin-martin"><?php echo ucfirst($memberData['BusinessOwner']['fname']).' '.ucfirst($memberData['BusinessOwner']['lname']);?></div>
                  <div class="wesco_llc"><?php echo strtoupper($memberData['Profession']['profession_name'])?>, <?php echo strtoupper($memberData['BusinessOwner']['company'])?></div>
                  <div class="kevin_mail"><i class="fa fa-envelope"></i> : <a href="mailto:<?php echo $memberData['BusinessOwner']['email'];?>"><?php echo $memberData['BusinessOwner']['email'];?></a></div>
                  <div class="wesco_address"><i class="fa fa-map-marker"></i> : <?php if($memberData['BusinessOwner']['address']!=''){echo $memberData['BusinessOwner']['address'].'<br>';} else {echo '';}?>
                  <?php if(trim($memberData['BusinessOwner']['city'])!="")
                		  	$addressinfo1[] = $memberData['BusinessOwner']['city'];
                		  if(trim($memberData['BusinessOwner']['zipcode'])!="")
                		  	$addressinfo1[] = $memberData['BusinessOwner']['zipcode'];
                
                		  $addressinfo1[] = $memberData['State']['state_subdivision_name'];
                		  $addressinfo1[] = $memberData['Country']['country_name'];?>
                    <?php if(!empty($addressinfo1) && $memberData['BusinessOwner']['address']!='') {echo implode(",&nbsp;",array_filter( $addressinfo1 ));} else {echo 'NA';} ?>  <br>
                    <?php $website1=$memberData['BusinessOwner']['website'];
                          $website2Link=$memberData['BusinessOwner']['website1'];
                    ?>
                    <?php 
                    if($website1!='' || $website2Link!='') {    
                    ?>
                    <b><i class="fa fa-chrome"></i></b> : <?php if($website1!='') {?>
                    <a href="<?php if (!preg_match("~^(?:f|ht)tps?://~i", $website1)) { echo "http://" .$website1;} else {echo $website1;}?>" target="_blank" title="<?php echo $website1;?>">
                    <?php if(strlen($website1)>35){echo substr($website1, 0, 33).'..'; } else {echo $website1;}?>
                    </a><?php }?>
                    <?php if($website1!='' && $website2Link!='') {echo '|'; }
                    if($website2Link!='') {?>  
                    <a href="<?php if (!preg_match("~^(?:f|ht)tps?://~i", $website2Link)) { echo "http://" .$website2Link;} else {echo $website2Link;}?>" target="_blank" title="<?php echo $website2Link;?>">
                    <?php if(strlen($website2Link)>35){ echo substr($website2Link, 0, 33).'..'; } else {echo $website2Link;}?>
                    </a><?php }?>
                    <?php } else {echo '<b><i class="fa fa-chrome"></i> </b>: NA';}?>
                    <br>
                    <b><i class="fa fa-mobile"></i> </b>: <?php if($memberData['BusinessOwner']['mobile']!='') { echo $memberData['BusinessOwner']['mobile']; } else { echo 'NA';}?> | <b><i class="fa fa-phone"></i> :</b> <?php if($memberData['BusinessOwner']['office_phone']!='') { echo $memberData['BusinessOwner']['office_phone'];} else { echo 'NA';}?><br>
                    <?php if(isset($sideTab) && $sideTab=='accountprofile') {?>
                    <b><i class="fa fa-globe"></i> </b>: <?php if($memberData['BusinessOwner']['timezone_id']!='') {echo $memberData['BusinessOwner']['timezone_id'];} else {echo 'NA';}?>
                    <?php }?>
                    </div>
                </div>
              </div>
            </div>
            <div class="col-md-3">
            <div class="rating-star margin_clear"> 
                            <div id="stars-existing" class="starrr stars_rat star_color" data-rating=<?php echo $totalAvgRating;?>></div> 
                            <span class="rating_23"><?php //echo $totalReview;?></span> 
                        </div>
              <div class="stars5"> <?php /*echo $totalAvgRating;*/ echo $totalReview?> review(s)</div>
              <?php 
                  $facebookLink=$memberData['BusinessOwner']['facebook_profile_id'];
                  $twitterLink=$memberData['BusinessOwner']['twitter_profile_id'];
                  $linkedinLink=$memberData['BusinessOwner']['linkedin_profile_id'];
                  $skypeLink=$memberData['BusinessOwner']['skype_id'];
              ?>
              <div class="media_link">SOCIAL MEDIA LINK</div>
              <div class="media_link2 social_profiles">
              <a href="<?php if(isset($facebookLink) && $facebookLink!='') { if (!preg_match("~^(?:f|ht)tps?://~i", $facebookLink)) { echo "http://" . $facebookLink;} else {echo $facebookLink;} } else {echo 'javascript:void(0);';}?>" <?php if(isset($facebookLink) && $facebookLink!='') {echo 'target="_blank"';}?> class="<?php if(!isset($facebookLink) || $facebookLink=='') {echo 'social_disabled';}?>"><i class="fa fa-facebook-square"></i> </a>
              <a href="<?php if(isset($twitterLink) && $twitterLink!='') { if (!preg_match("~^(?:f|ht)tps?://~i", $twitterLink)) { echo "http://" .$twitterLink;} else { echo $twitterLink;} } else {echo 'javascript:void(0);';}?>" <?php if(isset($twitterLink) && $twitterLink!='') {echo 'target="_blank"';}?> class="<?php if(!isset($twitterLink) || $twitterLink=='') {echo 'social_disabled';}?>"><i class="fa fa-twitter-square"></i> </a>
              <a href="<?php if(isset($linkedinLink) && $linkedinLink!='') { if (!preg_match("~^(?:f|ht)tps?://~i", $linkedinLink)) {echo "http://" . $linkedinLink;} else { echo $linkedinLink;} } else {echo 'javascript:void(0);';}?>" <?php if(isset($linkedinLink) && $linkedinLink!='') {echo 'target="_blank"';}?> class="<?php if(!isset($linkedinLink) || $linkedinLink=='') {echo 'social_disabled';}?>"><i class="fa fa-linkedin-square"></i> </a><br>
               <div class="clearfix">&nbsp;</div>
               <?php if(isset($skypeLink) && $skypeLink!='') {?> <i class="fa fa-skype"></i> : <span class="Skype_id"><?php echo $skypeLink;?></span><?php }?> </div>
            </div>
          </div>
          <div class="col-md-12">          
          <?php $aboutMe=htmlspecialchars($memberData['BusinessOwner']['aboutme']);
          if($aboutMe!='' || $aboutMe!=NULL){?>
            <div class="about_me ">
              <h2>ABOUT ME</h2>
              <?php ?>
              <div class="about_me_text"><?php if(strlen($aboutMe)>300) {echo '<span class="visible">'.substr($aboutMe,0,300).'</span><span class="collapsed">'.substr($aboutMe,300,strlen($aboutMe)).'</span>';?>&nbsp;&nbsp;<a href="#" class="read_more">Read More</a><?php } else { echo $aboutMe ;}?></div>
            </div>
            <?php }?>
            <?php $bizDescrip=htmlspecialchars($memberData['BusinessOwner']['business_description']);
            if($bizDescrip!='' || $bizDescrip!=NULL){?>
            <div class="about_me">
              <h2>COMPANY DESCRIPTION</h2>
              <div class="about_me_text"><?php if(strlen($bizDescrip)>300) {echo '<span class="visible">'.substr($bizDescrip,0,300).'</span><span class="collapsed">'.substr($bizDescrip,300,strlen($bizDescrip)).'</span>';?>&nbsp;&nbsp;<a href="#" class="read_more">Read More</a><?php } else { echo $bizDescrip ;}?></div>
            </div>
            <?php }?>
            <?php $services=htmlspecialchars($memberData['BusinessOwner']['services']);
            if($services!='' || $services!=NULL){?>
            <div class="about_me">
              <h2>SERVICES</h2>
              <div class="about_me_text"><?php if(strlen($services)>300) {echo '<span class="visible">'.substr($services,0,300).'</span><span class="collapsed">'.substr($services,300,strlen($services)).'</span>';?>&nbsp;&nbsp;<a href="#" class="read_more">Read More</a><?php } else { echo $services ;}?></div>
            </div>
            <?php }?>
            <div class="clearfix">&nbsp;</div>
                        
          </div>
          <div class="clearfix"></div>
        </div>
      </div>
      <?php if(isset($sideTab) && $sideTab!='') {
          $tabPage = $sideTab;
      } else {
          $tabPage = 'memberDetail';
      }?>
    <?php echo $this->element("Front/loginSidebar",array('tabpage' => $tabPage));?>
</div>
<?php echo $this->element('Front/bottom_ads');?>
<script>
$(document).ready(function(){
	$('body').on('click','.read_more',function(e){
		e.preventDefault();
		$(this).closest('.about_me').find('span.collapsed').fadeIn().removeClass('collapsed').addClass('expanded');
		$(this).remove();
		
	});
});
</script>
<script> var action = 'listing';</script>
<?php echo $this->Html->script('Front/rating');?>