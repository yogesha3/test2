<div class="row margin_top_referral_search">
      <div class="col-md-9 col-sm-8">
               <div class="row"> 
         <div class="col-md-12">
      <div class="referrals_reviews">
            <div class="referrals_reviews_head padd-top0">REFERRAL DETAILS</div>
            
            <div class="clearfix"></div>
            </div>
            </div>
            </div>
         <div class="clearfix">&nbsp;</div>
        <div class="col-md-6 col-sm-6 padd-left0">
        <div class="kevin_martin"><?php echo ucFirst($userData['ReceivedReferral']['first_name']) . " ". ucFirst($userData['ReceivedReferral']['last_name']); ?></div>
        <div class="tech_recu"><?php echo $userData['ReceivedReferral']['job_title'] . ", " . $userData['ReceivedReferral']['company']; ?></div>
        <div class="web_url"><?php echo $userData['ReceivedReferral']['website']; ?></div>
        <div class="our_contact"><?php echo $userData['ReceivedReferral']['address']; ?><br>

<?php echo $userData['ReceivedReferral']['city'] . " " . $userData['ReceivedReferral']['zip'] . ", " . $userData['State']['state_subdivision_name'] . ", " .$userData['Country']['country_name'] . "<br>"; ?> 

<strong>Website :</strong> <?php echo $userData['ReceivedReferral']['website']; ?><br>

<strong>M :</strong> <?php echo $userData['ReceivedReferral']['mobile']; ?><br>

<strong>F :</strong> <?php echo $userData['ReceivedReferral']['office_phone']; ?></div>

<div class="our_contact received_date">
<strong>DATE RECEIVED:</strong>  <?php //echo $userData['SendReferral']['created'];
echo date("M d, Y @ H:i A", strtotime($userData['ReceivedReferral']['created']));?><br>
<strong>REFERRAL STATUS:</strong> Received<br>
<strong>WANTS CONTACT:</strong> Yes?
</div>
        
        </div>
        
        <div class="col-md-6 col-sm-6">
     <div class="sender_msg">   Message from Sender</div>
       <div class="sender_msg_box"> 
       <span><?php echo $userData['ReceivedReferral']['message']; ?></span>
       </div>
       
      <div class="attachments_head">Attachments</div>
      <div class="attachments_text">
        <?php
            if (!empty($userData['ReceivedReferral']['files'])) {
                $docs = explode(',', $userData['ReceivedReferral']['files']);
                foreach ($docs as $files) {
                  $fileName = $this->Encryption->encode($files);
                    $fileArray = explode('.', $files);
                    foreach($fileArray as $fileExt) {
                        $fileExt = $fileExt;
                    }
                    echo $this->Html->link($files, array('controller' => 'referrals', 'action' => 'downloadFiles', $fileName))."<br/>";
                }
            } else {
                echo "No attachments are available.";
            }
        ?>
      </div>

<div class="comments">Comments</div>
<div class="ajaxUpdate">
<?php
  if (!empty($referralComment)) {
      foreach ($referralComment as $referralComments):  ?>
      <div class="media">
        <a class="pull-left" href="#">
          <?php
          if(!empty($referralComments['BusinessOwners']['profile_image'])) {
            echo $this->Html->image('uploads/profileimage/'.$referralComments['BusinessOwners']['profile_image'], array('alt' => 'Sample Image', 'class' => 'media-object', 'height' => 50, 'width' => 50));
          } else {
            echo $this->Html->image('profil-img.jpg', array('alt' => 'Sample Image', 'class' => 'media-object', 'height' => 50, 'width' => 50));
          }        
          ?>
        </a>
        <div class="media-body">
          <div class="media-heading"><?php echo ucFirst($referralComments['BusinessOwners']['fname']) . " " . ucFirst($referralComments['BusinessOwners']['lname']); ?>:
            <span class="alex-proto"><?php echo trim(ucFirst($referralComments['ReferralComment']['comment'])); ?> </span>
          </div>
          <div class="clearfix">&nbsp;</div>
          <div class="send_date">sent  on <?php echo date("M d, Y @ g:i A", strtotime($referralComments['ReferralComment']['created']));?></div>
        </div> 
      </div>    

    <?php endforeach; ?>
</div>
    <?php } else { ?>
    <div class="media-body">
      No Comments
    </div>        
    <?php  }?>
    <input type="hidden" name="rid" id="rid" value="<?php echo $userData['ReceivedReferral']['id']?>">
    <textarea name="comment" id="commentbox" rows="1" placeholder="Write a comments..." class="form-control write_comments"></textarea>
    <div class="clearfix">&nbsp;</div>
    <button class="btn btn-sm file_sent_btn pull-right ML_btn padauto" id="addbutton" type="button">Add</button>
    <button class="btn btn-sm back_btn pull-right text-center padauto" type="button">Back</button>

</div>
</div>
<?php echo $this->element("Front/loginSidebar",array('tabpage' => 'referralsDetail'));?>
</div>
<?php
  $this->Js->get('#addbutton');
  $this->Js->event('click',
  $this->Js->request(array(
          'controller'=>'referrals',
          'action'=>'addComment'),
          array('async'=>true,
                //'update'=>'#media',
                'dataExpression'=>true,
                'data' => '$(\'#commentbox,#rid\').serializeArray()',
                'method'=>'post',
                'success' => 'updateData(data);')
       )
  );           
?>
<script>
$(document).ready(function(){
var ajacContainer=$('.ajaxUpdate');
ajacContainer.scrollTop(ajacContainer[0].scrollHeight);
});
function updateData(data) {
  $('#commentbox').val('');
    var jsonData = JSON.parse(data);       
    $( ".ajaxUpdate" ).append( jsonData.response );
    //alert(jsonData.response.fname);
}
</script>