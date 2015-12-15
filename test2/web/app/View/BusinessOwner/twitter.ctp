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
		<div class="row">
			<div class="col-md-12">
				<div class="referral_profile_head"></div>
			</div>		
			<div class="form-group col-md-12">
			<?php if(!$twitterConnected) {?>
				<div class="twitter_btn">
				
                <a href="<?php echo Router::url(array('controller'=>'BusinessOwners','action'=>'loginTwitter'),true);?>"><img src="../LoginTwitter.png"/></a>
				</div>
				<?php } else {?>
				<div class="twitter_data">
				<strong>Twitter account is connected</strong><br/><br/><br/>
				
				<p>
				OauthToken: <?php echo $userData['twitter_oauth_token']?>
				</p>
				<p>
				OauthToken Secret: <?php echo $userData['twitter_oauth_token_secret']?>
				</p>
				<?php 
                    echo $this->Html->link($this->Form->button('<i class="fa fa-circle-arrow-left"></i> Disconnect', array('type' => 'button', 'class' => 'btn btn-red go-back pull-left')),array('controller'=>'businessOwners','action'=>'twitterLogout'), array('escape'=>false));
                ?>
				</div>
				 <?php }?>
			</div>
			
	</div>
</div>
<?php echo $this->element("Front/loginSidebar",array('tabpage' => 'twittersidebar'));?>
</div>
</section>
<script>
var ajaxUrl = "<?php echo Router::url(array('controller'=>'users','action'=>'getStateCity'));?>";
var imgPath = "<?php echo $this->webroot; ?>img/icons/error.png";
</script>
<?php echo $this->Html->script ( 'Front/profile' );