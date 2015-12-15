<div class="row margin_top_referral_search">
  <div class="col-md-9 col-sm-8">
   <div class="row"> 
     <div class="col-md-8">
      <div class="referrals_reviews">
      <?php if($listType=="received" && $userData[$modal]['is_archive']==0){?>
        	<div class="referrals_reviews_head padd-top0">Referral Details <?php echo $this->Html->link('',array('controller'=>'referrals','action'=>'referralUpdate',$referredId),array('class'=>'fa fa-edit referral_edit','title'=>'Edit'))?></div>
        <?php }else{ ?>
			<div class="referrals_reviews_head padd-top0">Referral Details</div>
        <?php } ?>
        <div class="clearfix"></div>
      </div>
    </div>
    <div class="col-md-4 text-right">
        <?php if (!empty($referer)) {?>
        <a href="<?php echo base64_decode($referer);?>" class="back_btn_new pull-right text-center padauto " ><i class="fa fa-arrow-circle-left"></i> Back</a>
        <?php } ?>
    </div>
  </div>
  <div class="clearfix">&nbsp;</div>
  <div class="col-md-6 col-sm-6 padd-left0 icon_awesome">
    <div class="kevin_martin"><?php echo ucFirst($userData[$modal]['first_name']) . " ". ucFirst($userData[$modal]['last_name']); ?></div>
    <?php $jobinfo = array();    
	    if(trim($userData[$modal]['job_title'])!="")
	    	$jobinfo[] = strtoupper($userData[$modal]['job_title']);
	    if(trim($userData[$modal]['company'])!="")
    		$jobinfo[] = $userData[$modal]['company'];
    ?>
    <div class="tech_recu"><?php echo implode(",&nbsp;",array_filter( $jobinfo )); ?></div>
    <div class="web_url">
    <?php echo '<i class="fa fa-envelope"></i> : <a href="mailto:'.$userData[$modal]['email'].'">'.$userData[$modal]['email'].'</a>'?>
    </div>
    <div class="our_contact"><i class="fa fa-map-marker"></i> : <?php echo $addresses = (trim($userData[$modal]['address'])!="") ? $userData[$modal]['address']."<br/>" : ""; ?>
	  <?php $addressinfo1 = array();
		  if (trim($userData[$modal]['city']) != "")
		  	$addressinfo1[] = $userData[$modal]['city'];
		  if (trim($userData[$modal]['zip']) != "")
		  	$addressinfo1[] = $userData[$modal]['zip'];
		  if (trim($userData['State']['state_subdivision_name']) != "")
    		  $addressinfo1[] = $userData['State']['state_subdivision_name'];
		  if (trim($userData['Country']['country_name'])!="")
    		  $addressinfo1[] = $userData['Country']['country_name'];
	  ?>	  
      <?php if(!empty($addressinfo1) && $userData[$modal]['address']!='') {echo implode(",&nbsp;",array_filter( $addressinfo1 ));} else {echo 'NA';} echo '<br/>'; ?> 
	  <?php $website_link = (strlen($userData[$modal]['website']) > 50 ) ? substr($userData[$modal]['website'], 0 , 50)."..." : $userData[$modal]['website']; ?>
	  <?php $formatURL = $this->Functions->formatURL($userData[$modal]['website']);?>
      <strong><i class="fa fa-chrome"></i> :</strong> <?php echo $website = ($userData[$modal]['website']!="") ? '<a href="'.$formatURL.'" target="_blank" title="'.$userData[$modal]['website'].'">'.$website_link.'</a>': "NA"; ?><br>

      <strong><i class="fa fa-mobile"></i> :</strong> <?php echo $mobile = ($userData[$modal]['mobile']!="") ? $userData[$modal]['mobile']: "NA"; ?><br>

      <strong><i class="fa fa-phone"></i> :</strong> <?php echo $office_phone = ($userData[$modal]['office_phone']!="") ? $userData[$modal]['office_phone']: "NA"; ?></div>

      <div class="our_contact received_date">
<strong>DATE RECEIVED:</strong>  <?php //echo $userData[$modal]['created'];
echo $this->Functions->dateTime($userData[$modal]['created']);?><br>
<?php if ($modal == "ReceivedReferral") { ?>
<strong>REFERRAL STATUS:</strong> <?php
    echo ($userData[$modal]['is_archive'] == 1) ? ucfirst($userData[$modal]['referral_status']) : $this->Html->link(ucfirst($userData[$modal]['referral_status']), array('controller' => 'referrals', 'action' => 'referralUpdate', $referredId));
    ?><br>
<strong>MONETARY VALUE:</strong> 
<?php
    $value = !empty($userData[$modal]['monetary_value']) ? '$'.CakeNumber::format($userData[$modal]['monetary_value']): '$0';
    echo ($userData[$modal]['is_archive'] == 1) ? $value : $this->Html->link($value, array('controller' => 'referrals', 'action' => 'referralUpdate', $referredId));
?><br>
<?php
  }
?>
</div>

</div>

<div class="col-md-6 col-sm-6 right0">
 <div class="sender_msg">   Message from Sender</div>
 <div class="sender_msg_box"> 
   <span><?php echo $userData[$modal]['message'] != '' ? $userData[$modal]['message'] : 'No message from sender' ; ?></span>
 </div>

 <div class="attachments_head"><i class="fa fa-paperclip"></i>&nbsp;&nbsp; Attachments</div>
 <div class="attachments_text">
 <div class="clearfix">&nbsp;</div>
  <?php
  if (isset($userData[$modal]['files']) && !empty($userData[$modal]['files'])) {
    $docs = explode(',', $userData[$modal]['files']);
    foreach ($docs as $files) {
      $fileName = $this->Encryption->encode($files);
      $fileArray = explode('.', $files);
      $fileIcon = '';
      foreach($fileArray as $fileExt) {
        $fileExt = $fileExt;
        
      }
      $extension = pathinfo($files);
      switch($extension['extension']) {
        case 'jpg':
        case 'jpeg':
        case 'gif':
        case 'png':
           $fileIcon = 'fa-file-image-o';
          break;
      case 'xls':
      case 'xlsx':
          $fileIcon = 'fa-file-excel-o';
          break;
      case 'doc':
      case 'docx':
          $fileIcon = 'fa-file-word-o';
          break;
      case 'pdf':
          $fileIcon = 'fa-file-pdf-o';
          break;
      case 'txt':
          $fileIcon = 'fa-file-text';
          break;
      default:
          $fileIcon = 'fa-file';
      }
      $files = substr($files,19);
      ?>
      <div class="attachment_row">
      <i class="fa  <?php echo $fileIcon;?> file_icon"></i> &nbsp;&nbsp;
      <?php 
      echo $this->Html->link($files, array('controller' => 'referrals', 'action' => 'downloadFiles', $fileName));
      ?>
      </div>
      <?php 
    }
  } else {
    echo "No attachments are available.";
  }
  ?>
</div>

<div class="comments">Chat With Member</div>
<div class="ajaxUpdate">
<?php
  if (isset($referralComment) && !empty($referralComment)) {
      foreach ($referralComment as $referralComments):  ?>
      <div class="media">
          <?php
          if(!empty($referralComments['BusinessOwners']['profile_image'])) {
            echo $this->Html->image('uploads/profileimage/'.$referralComments['BusinessOwners']['user_id'].'/resize/'.$referralComments['BusinessOwners']['profile_image'], array('alt' => 'Sample Image', 'class' => 'media-object pull-left', 'height' => 50, 'width' => 60));
          } else {
            echo $this->Html->image('no_image.png', array('alt' => 'no_image', 'class' => 'media-object pull-left', 'height' => 50, 'width' => 60));
          }        
          ?>
        <div class="media-body">
          <div class="media-heading"><?php echo ucFirst($referralComments['BusinessOwners']['fname']) . " " . ucFirst($referralComments['BusinessOwners']['lname']); ?>:
            <span class="alex-proto"><?php echo $referralComments['ReferralComment']['comment']; ?> </span>
          </div>
          <div class="clearfix">&nbsp;</div>
          <div class="send_date">Posted on <?php echo $this->Functions->dateTime($referralComments['ReferralComment']['created']);?></div>
        </div> 
      </div>    

    <?php endforeach; ?>	
    <?php } else { ?>
    <div class="media-body ttt" id="no_comments">No Comments</div>        
    <?php  }?>
    <?php $new_last_comment = (count($referralComment)) ? $this->Encryption->decode($referralComment[count($referralComment)-1]["ReferralComment"]["id"]) : 0; ?>
	<div id="last-msg" last-database-message="<?php echo $new_last_comment;?>"></div>
    </div>
    <div class="blockClass blockClass_comment " style="position: relative">
        <input type="hidden" name="rid" id="rid" value="<?php echo $userData[$modal]['id']?>">
        <input type="hidden" name="sendMailTo" id="sendMailTo" value="<?php echo $sendMailTo; ?>">
        <input type="hidden" name="type" id="type" value="<?php echo $modal;?>">
        <textarea onkeyup="checkContent();" name="comment" id="commentbox" rows="1" placeholder="" class="form-control write_comments"></textarea>
    </div>
    <div class="clearfix">&nbsp;</div>
    <button class="btn btn-sm file_sent_btn pull-right ML_btn padauto" onclick="addComment();" id="addbutton" type="button">Post</button>
</div>
</div>
<?php echo $this->element("Front/loginSidebar",array('tabpage' => 'referralsDetail'));?>
</div>
<?php echo $this->element('Front/bottom_ads');?>
<script>
var liveCommentUrl = "<?php echo Router::url(array('controller'=>'referrals','action'=>'getLatestComment'));?>";
var msgLoader = '<div class="blockClass_comment2"><div id="rays"><?php echo $this->Html->image('loding-logo.png',array('id'=>'referralStatusWait','class'=>'center-block img-responsive'));?></div></div>';
var ajacContainer =	$('.ajaxUpdate');
var entity = 'referral';
</script>
<?php echo $this->Html->script ( 'Front/LiveComment' );