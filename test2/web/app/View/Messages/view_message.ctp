<div class="row margin_top_referral_search">
      <div class="col-md-9 col-sm-8">
          <div class="row"> 
                <div class="col-md-8">
                    <div class="referrals_reviews">
                        <div class="referrals_reviews_head padd-top0">Message Details</div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="col-md-4 text-right">
                    <?php if (!empty($referer)) {?>
                    <a href="<?php echo base64_decode($referer);?>" class="back_btn_new pull-right text-center padauto "><i class="fa fa-arrow-circle-left"></i> Back</a>
                    <?php } ?>
                </div>
          </div>
          <div class="clearfix">&nbsp;</div>

      <div class="panel panel-default mypanel">
      <!-- Default panel contents -->
      <div class="panel-heading message_subject"><?php echo ucfirst($messageData['Message']['subject']); ?><span class="panel_inbox pull-right"></span>
      
      
      <div class="panel_body_head inbox_users" >From: <span class="color_black"><?php
      echo isset($messageData['BusinessOwner']['member_name']) ? $messageData['BusinessOwner']['member_name']: ''; ?></span>
      <div class="panel_body_subhead">To:  
           <?php
            $i=0;
            $recipeints = array();
            //pr($receiversName);
            //echo $this->Encryption->decode($this->Session->read('Auth.Front.id'));
              foreach ($messageRecipients as $mRecipients) {
                  if ($this->Encryption->encode($mRecipients['MessageRecipient']['recipient_user_id']) == $this->Session->read('Auth.Front.id')) {
                      $recipeints[] = "me";
                  }
              }
              if(count($receiversName)>0){
	              foreach ($messageRecipients as $mRecipients) {
	              	if($this->Encryption->encode($mRecipients['MessageRecipient']['recipient_user_id']) != $this->Session->read('Auth.Front.id')){
	              		$recipeints[] = $receiversName[$i];	              		
	              	}
	              	$i++;
	              }
              }
              echo ' <span class="color_black">'.implode(", ",$recipeints).'</span>';
            ?>
          </div>
      </div>
      </div>
      <div class="panel-body padd-top0">
      
      
      
      <!--<div id="place_of_loading_image" style="display:none"><?php echo $this->Html->image('spinner.gif'); ?></div>-->
        <div class="panel-body-text inbox_message_text">
        <?php if($mRecipients['Message']['message_type']=='referral_comment' || $mRecipients['Message']['message_type']=='message_comment') {?>
        Dear <?php echo $userData['BusinessOwner']['fname'].' '.$userData['BusinessOwner']['lname'];?><br/><br/>
        <?php }?>
         <?php
            echo html_entity_decode($messageData['Message']['content']);
        ?>        
      </div>
      
      
        <div class="attachments">
            <div class="col-md-3 padd-left0">Attachments &nbsp;&nbsp;<i class="fa fa-paperclip"></i></div>
            <div class="col-md-9 padd-left0"> <span class="attachments_text">
                <?php
                 if (isset($messageAttachments) && !empty($messageAttachments)) {
                   //$docs = explode(',', $userData[$modal]['files']);
                   foreach ($messageAttachments as $files) {
                     $fileName = $this->Encryption->encode($files['MessageAttachment']['filename']);
                     $fileArray = explode('.', $files['MessageAttachment']['filename']);
                     foreach($fileArray as $fileExt) {
                       $fileExt = $fileExt;
                     }
                     $extension = pathinfo($files['MessageAttachment']['filename']);
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
                     $filesnameval = substr($files['MessageAttachment']['filename'],19);
                     ?>
                     <div class="attachment_row">
                      <i class="file_icon fa  <?php echo $fileIcon;?>"></i> &nbsp;&nbsp;
                      <?php 
                     echo $this->Html->link($filesnameval, array('controller' => 'messages', 'action' => 'downloadFiles', $fileName));
                     ?>
                     </div>
                     <?php 
                   }
                 } else {
                   echo "No attachments found.";
                 }
                ?>
                </span>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="attachments">
            <div class="col-md-3 padd-left0 ">Chat With Member </div>
            
            <div class="ajaxUpdatemsg messageImage">

            <?php
              if (isset($messageComment) && !empty($messageComment)) {
                foreach ($messageComment as $messageComments):
            ?>
              <div class="media-left">
                <?php
                  if(!empty($messageComments['BusinessOwner']['profile_image'])) {
                    echo $this->Html->image('uploads/profileimage/'.$messageComments['BusinessOwner']['user_id'].'/resize/'.$messageComments['BusinessOwner']['profile_image'], array('alt' => 'Sample Image', 'class' => 'media-object', 'height' => 50, 'width' => 50));
                  } else {
                    echo $this->Html->image('no_image.png', array('alt' => 'no_image', 'class' => 'media-object', 'height' => 50, 'width' => 50));
                  }        
                ?>
              </div>
              <div class=" col-md-9 padd-left0 padd-right0 alex-proto ttt"><b><?php echo ucFirst($messageComments['BusinessOwner']['fname']) . " " . ucFirst($messageComments['BusinessOwner']['lname']); ?>: </b> <?php echo $messageComments['MessageComment']['comment']; ?> 
              
                  <div class="send_time"> 
                      Posted  on <?php echo $this->Functions->dateTime($messageComments['MessageComment']['created']);?>
                  </div><br>
              </div>
              <?php endforeach; ?>

		    <?php } else { ?>
		      <div class="ttt" id="no_comments">No Comments</div>
		    <?php } ?>
		    <?php $new_last_comment = (count($messageComment)) ? $this->Encryption->decode($messageComment[count($messageComment)-1]["MessageComment"]["id"]) : 0; ?>
			<div id="last-msg" last-database-message="<?php echo $new_last_comment;?>"></div>
		    </div>
    <div class="col-md-3"></div>
    <div class="col-md-9 padd-left0 blockClass">
        <input type="hidden" name="mid" id="mid" value="<?php echo $messageData['Message']['id']?>">
        <input type="hidden" name="sendMailTo" id="sendMailTo" value="<?php echo $sendMailTo; ?>">
        <input type="hidden" name="type" id="type" value="<?php echo $type;/* $modal;*/?>">
        <div class="customTextarea"  style="width:613px;position: relative">
        <textarea onkeyup="checkContent();" name="comment" id="commentbox" rows="1" placeholder="" class="form-control write_comments" style="margin: 0;"></textarea>
        </div>
    </div>
    <div class="clearfix">&nbsp;</div>
    <button class="btn btn-sm file_sent_btn pull-right ML_btn padauto" onclick="addComment();" id="addbutton" type="button">Post</button>
              <!--<button class="btn btn-sm file_sent_btn pull-right " type="button">Back</button>-->
        </div>
    </div>
     </div>
         
    </div>
    <?php echo $this->element("Front/loginSidebar",array('tabpage' => 'messagesDetail'));?>

    </div>
<?php echo $this->element('Front/bottom_ads');?>
<script>
var liveCommentUrl = "<?php echo Router::url(array('controller'=>'messages','action'=>'getLatestComment'));?>";
var msgLoader = '<div class="blockClass_comment2"><div id="rays"><?php echo $this->Html->image('loding-logo.png',array('id'=>'referralStatusWait','class'=>'center-block img-responsive'));?></div></div>';
var ajacContainer =	$('.ajaxUpdatemsg');
var entity = 'message';
</script>
<?php echo $this->Html->script ( 'Front/LiveComment' );
