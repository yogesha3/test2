<?php 
$fbActive = $linkedinActive = $twitterActive = '';
$passed = $this->params['pass'];
if(empty($passed)) {
    $passed = array('twitter');
}
switch($passed[0]) {
    case 'facebook': $fbActive = 'active';break;
    case 'linkedin': $linkedinActive = 'active';break;
    default: $twitterActive = 'active';
}
?>
<div class="row"> 
         <div class="col-md-12">
<div class="referrals_reviews">
          <ul class="social_profiles">
          <li><div class="referrals_reviews_head padd-top0"><a href="<?php echo Router::url(array('twitter'),true);?>" class="<?php echo $twitterActive;?>">Twitter</a></div></li>
          <li><div class="referrals_reviews_head padd-top0"><a href="<?php echo Router::url(array('facebook'),true);?>" class="<?php echo $fbActive;?>">Facebook</a></div></li>
          <li><div class="referrals_reviews_head padd-top0"><a href="<?php echo Router::url(array('linkedin'),true);?>" class="<?php echo $linkedinActive;?>">LinkedIn</a></div></li>
          
          </ul>
            <div class="clearfix"></div>
            </div>
        </div>
    </div>