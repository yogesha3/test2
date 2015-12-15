<?php echo $this->Html->script('Front/all');?>
<div class="row margin_top_referral_search">
  <div class="col-md-9 col-sm-8">
    <div class="row"> 
      <div class="col-md-12">
        <div class="referrals_reviews">
          <div class="referrals_reviews_head padd-top0">Webcast</div>

          <div class="clearfix"></div>
        </div>
      </div>
    </div>
    <div class="clearfix">&nbsp;</div>
<!--     <div class="webcast_heading">  VIEW OUR LATEST WEBCAST    </div> -->

    <div class="video_here webcast">
    <?php 
    if(!empty($latestWebcast)) {
        $url = str_replace('watch?v=', 'embed/', $latestWebcast['Webcast']['link'].'?autoplay=1&rel=0');
        echo '<iframe width="850" height="424" src="'.$url.'" frameborder="0" allowfullscreen></iframe>';
    } else {
        echo $this->Html->image('no_video.png',array('alt'=> 'no_video'));
    } 
    ?>
    <?php ?>
    <!-- <iframe width="850" height="424" src="<?php echo $url;?>" frameborder="0" allowfullscreen></iframe> -->

    </div>

    <?php if(!empty($latestWebcast)) { ?>
    <div class="foxhopr_network">
        <?php echo $latestWebcast['Webcast']['title']; ?>
        <div class="webcast_description">
        <?php echo $latestWebcast['Webcast']['description']; ?>
        </div>
    </div>

    <div class="upload_video"><span>Uploaded  on:</span>
    <?php echo $this->Functions->dateTime($latestWebcast['Webcast']['created']);?>
     <!-- Tuesday, May 10th, 2015 @ 11:50 pm --></div>

    <div class="blockClass">
      <?php 
      echo $this->Form->textarea('comments',array('id'=>'commentbox','class'=>'form-control write_comments write_comments_bg','rows'=>3,'placeholder'=>'Write a comment',"onkeyup"=>"checkContent();",));
      echo $this->Form->hidden('webcastid',array('value'=>$latestWebcast['Webcast']['id']))

      ?>
      </div>
      <div class="clearfix">&nbsp;</div>
      <button id="addCommentsWebcast" type="button" class="btn btn-sm file_sent_btn pull-right">Post</button>

      <div class="clearfix"></div>

    <div class="sender_msg_box"> 
      <div class="sender_msg_box_inner"> 
        <div class="attachments_head">
		<?php if($commentCount > 0) {
        echo 'Comments';
        }
        ?>
		</div>

        <div class="ajaxUpdate2">
        
            
        </div>
      </div>
      <?php if($commentCount > 5) {
        echo '<div class="view_comment"><a class="viewmorecomment" class="" href="#">Show More</a></div> ';
        }
        ?>
           

      
    </div>
    <?php } ?>
    
  </div>
  <?php echo $this->element("Front/loginSidebar",array('tabpage' => 'webcast'));?>
  </div>
<?php echo $this->element('Front/bottom_ads');
echo $this->Html->script('Front/webcast');