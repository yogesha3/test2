<?php $disable = ($mode==null) ? "disabled" : false;?>
<?php $display = ($mode==null) ? 'style="display:none"' : "";?>
<?php $profileImage = (!empty($this->request->data['BusinessOwner']['profile_image'])) ? "uploads/profileimage/".$userID."/resize/".$this->request->data['BusinessOwner']['profile_image'] : "no_image.png"; ?>
<?php echo $this->Html->script('Front/all');?>
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
			<div class="col-md-8">
				<div class="referrals_reviews">
					<div class="referrals_reviews_head padd-top0"><?php if(!$disable){?>Edit Profile <?php }else{ echo "Profile&nbsp;&nbsp;".$this->Html->link('',array('action' => 'profile','edit'),array('class'=>'fa fa-edit profile_edit','title'=>'Edit')); }?></div>
					<div class="clearfix"></div>
				</div>
			</div>
			<div class="col-md-4 text-right">
				<?php if(!$disable) 
				{
					echo $this->Html->link ( '<i class="fa fa-arrow-circle-left"></i> Back', array ('action' => 'profile'), array ('class' => 'btn btn-sm  back_btn_new pull-right','escape'=>false) );
				}
				?>
    		</div>
		</div>
		<div class="clearfix">&nbsp;</div>
		<div class="row">
			<div class="col-md-12">
				<div class="referral_profile_head"></div>
			</div>
			<?php echo $this->Form->create('BusinessOwner',array('id'=>'EditProfileForm','type'=>'file','class'=>'Choose-a-contact','inputDefaults' => array('label' => false,'div' => false,'errorMessage'=>true),'novalidate'=>true));?>
            <div class="form-group col-md-2">
				<?php if(!$disable){?>					
			    <div id="imgContainer">
				    <?php echo $this->Html->image($profileImage, array('alt' => 'Edit Image','height'=>"103",'width'=>"103",'class'=>'edit_profile_img text-center','id'=>'newProfile','data-flag'=>"0"));?>
				    <div class="text-center" style="position:absolute; top:40px; width:110px; cursor: pointer" id="edit-image">
				    	<center><font COLOR="#709cd2">Edit Image</font></center>
				    </div>
				    <?php echo $this->Form->input('profile_image', array('type' => 'file','id'=>'profile-img','onchange'=>"readURL(this);"));?>
			    </div>
			    <?php }else{?>
			    <div class="edit_profile_img text-center" style="line-height:0px;"><?php echo $this->Html->image($profileImage,array('width'=>'103','height'=>'103'));?></div>
			    <?php }?>
			</div>
            <span class="pull-right col-md-10 col-xs-12">
				<div class="form-group col-md-5 form_padd_L0 form_padd_right">
					<label for="exampleInputPassword1">First Name</label> 
					<?php echo $this->Form->input('fname',array('type'=>'text','id'=>'first_name','placeholder'=>"First Name",'class'=>'form-control','maxlength'=>false,'disabled'=>$disable, 'autofocus'=>true, 'readonly' => true));?>
				</div>
				<div class="form-group col-md-7 form_padd_clear">
					<label for="exampleInputPassword1">Email Address</label>
					<?php echo $this->Form->input('email',array('type'=>'text','id'=>'email','placeholder'=>"Kevin.Martin@wesco.com",'class'=>'form-control','disabled'=>'disabled'));?>
				</div>
				<div class="clearfix"></div>
				<div class="form-group col-md-5 form_padd_L0 form_padd_right ">
					<label for="exampleInputPassword1">Last Name</label>
					<?php echo $this->Form->input('lname',array('type'=>'text','id'=>'last_name','placeholder'=>"Last Name",'class'=>'form-control','maxlength'=>false,'disabled'=>$disable, 'readonly' => true));?>
				</div>
                <div class="form-group col-md-7 form_padd_clear">
					<label for="exampleInputPassword1">Job Category</label>
					<?php echo $this->Form->input('category',array('type'=>'text','id'=>'category','placeholder'=>"Category",'class'=>'form-control','maxlength'=>false,'disabled'=>'disabled', 'readonly' => true));?>
				</div>
				
			</span>
            <div class="clearfix"></div>
             <div class="form-group col-md-6">
				<label for="exampleInputPassword1">Company</label>
				<?php echo $this->Form->input('company',array('type'=>'text','id'=>'company','placeholder'=>"WESCO",'class'=>'form-control','maxlength'=>false,'disabled'=>'disabled'));?>
			 </div>
             <div class="form-group col-md-6">
					<label for="exampleInputPassword1">Job Title</label>
					<?php echo $this->Form->input('job_title',array('type'=>'text','id'=>'job_title','placeholder'=>"Technical Recruiter",'class'=>'form-control','maxlength'=>false,'disabled'=>'disabled'));?>
			</div>
			<div class="clearfix"></div>
			<div class="form-group col-md-3">
				<label for="exampleInputPassword1">Country</label>
				<?php echo $this->Form->input('country_id', array('id'=>'country','type'=>'select','options'=>$countryList,'empty' => 'Select Country','class'=>"form-control",'label'=>false,'disabled'=>'disabled'));?>
			</div>
			<div class="form-group col-md-3">
				<label for="exampleInputPassword1">State</label>
				<div id="stateDiv">
				<?php echo $this->Form->input('state_id',array('type' => 'select','id' => 'state', 'options' => $stateList, 'empty' => 'Select State','class'=>'form-control','label'=>false,'disabled'=>'disabled'));?>                    
				</div>
			</div>
            <div class="form-group col-md-6">
				<label for="exampleInputPassword1">Time Zone</label>
				<?php echo $this->Form->input('timezone_id', array('id'=>'timezone','type'=>'select','options'=>$timezoneList,'empty' => 'Select TimeZone','class'=>"form-control",'label'=>false,'disabled'=>'disabled'));?>
			</div>
			<div class="clearfix"></div>			
			<div class="form-group col-md-3">
				<label for="exampleInputPassword1">City</label> 
				<?php echo $this->Form->input('city',array('type'=>'text','id'=>'city','placeholder'=>"City",'class'=>'form-control','maxlength'=>false,'disabled'=>$disable));?>
			</div>
			<div class="form-group col-md-3">
				<label for="exampleInputPassword1">Zip Code</label>
				<?php echo $this->Form->input('zipcode',array('type'=>'text','id'=>'zip','placeholder'=>"Zip Code",'class'=>'form-control','maxlength'=>false,'disabled'=>'disabled'));?>
			</div>
			<div class="form-group col-md-6">
				<label for="exampleInputPassword1">Address</label>
				<?php echo $this->Form->input('address',array('type'=>'text','id'=>'address','placeholder'=>"Address",'class'=>'form-control','disabled'=>$disable));?>
			</div>
            <div class="clearfix"></div>
			<div class="form-group col-md-6">
				<label for="exampleInputPassword1">Mobile</label>
				<?php echo $this->Form->input('mobile',array('type'=>'text','id'=>'mobile','placeholder'=>"Mobile",'class'=>'form-control','maxlength'=>false,'disabled'=>$disable));?>
			</div>
			<div class="form-group col-md-6">
				<label for="exampleInputPassword1">Office Phone<span class="star" <?php echo $display?>>*</span></label>
				<?php echo $this->Form->input('office_phone',array('type'=>'text','id'=>'office_phone','placeholder'=>"Office Phone",'class'=>'form-control','maxlength'=>false,'disabled'=>$disable));?>
			</div>			
			<div class="clearfix"></div>			
			<div class="form-group col-md-6">
				<label for="exampleInputPassword1">Website1</label>
				<?php echo $this->Form->input('website',array('type'=>'text','id'=>'website','placeholder'=>"Website1",'class'=>'form-control','maxlength'=>false,'disabled'=>$disable));?>
			</div>
			<div class="form-group col-md-6">
				<label for="exampleInputPassword1">Website2</label> 
				<?php echo $this->Form->input('website1',array('type'=>'text','id'=>'website1','placeholder'=>"Website2",'class'=>'form-control','maxlength'=>false,'disabled'=>$disable));?>
			</div>			
			<div class="clearfix"></div>
			<div class="form-group col-md-6">
				<label for="exampleInputPassword1">Twitter Profile</label>
				<?php echo $this->Form->input('twitter_profile_id',array('type'=>'text','id'=>'twitter_profile_id','placeholder'=>"Twitter Profile",'class'=>'form-control','maxlength'=>false,'disabled'=>$disable));?>
			</div>			
			<div class="form-group col-md-6">
				<label for="exampleInputPassword1">Facebook Profile</label>
				<?php echo $this->Form->input('facebook_profile_id',array('type'=>'text','id'=>'facebook_profile_id','placeholder'=>"Facebook Profile",'class'=>'form-control','maxlength'=>false,'disabled'=>$disable));?>
			</div>			
			<div class="clearfix"></div>			
			<div class="form-group col-md-6">
				<label for="exampleInputPassword1">LinkedIn Profile</label>
				<?php echo $this->Form->input('linkedin_profile_id',array('type'=>'text','id'=>'linkedin','placeholder'=>"LinkedIn Profile",'class'=>'form-control','maxlength'=>false,'disabled'=>$disable));?>
			</div>  
			<div class="form-group col-md-6">
				<label for="exampleInputPassword1">Skype</label>
				<?php echo $this->Form->input('skype_id',array('type'=>'text','id'=>'skype_id','placeholder'=>"Skype",'class'=>'form-control','maxlength'=>false,'disabled'=>$disable));?>
			</div>
			<div class="clearfix"></div>			
			<div class="form-group col-md-6">
				<label for="exampleInputPassword1">About Me</label>
				<?php echo $this->Form->input('aboutme',array('type'=>'textarea','id'=>'aboutme','class'=>'form-control','rows'=>"3",'maxlength'=>false,'disabled'=>$disable));?>
			</div>			
			<div class="form-group col-md-6">
				<label for="exampleInputPassword1">Services</label>
				<?php echo $this->Form->input('services',array('type'=>'textarea','id'=>'services','class'=>'form-control','rows'=>"3",'maxlength'=>false,'disabled'=>$disable));?>
			</div>
			<div class="clearfix"></div>			
			<div class="form-group col-md-12">
				<label for="exampleInputPassword1">Company Description</label>
				<?php echo $this->Form->input('business_description',array('type'=>'textarea','id'=>'businessDescription','class'=>'form-control','rows'=>"2",'maxlength'=>false,'disabled'=>$disable));?>
			<div class="clearfix">&nbsp;</div>
			<div class="clearfix">&nbsp;</div>
			<?php
			if ($disable) {
			/* echo $this->Html->link ( 'Edit', array (
			'action' => 'profile',
			'edit' 
			), array (
			'class' => 'btn btn-sm  file_sent_btn pull-right' 
			) ); */
			} else {
			echo $this->Form->hidden ( 'id' );
			echo $this->Form->hidden ( 'old_profile_img' );
			echo $this->Form->button ( 'Save', array (
			'type' => 'submit',
			'id' => 'updatebutton',
			'class' => 'btn btn-sm  file_sent_btn pull-right ML_btn' 
			) );
			
			}
			?>
			</div>
			<?php echo $this->Form->end();?> 
	</div>
</div>
<?php echo $this->element("Front/loginSidebar",array('tabpage' => 'accountprofile'));?>
</div>
<?php echo $this->element('Front/bottom_ads');?>
<script>
var ajaxUrl = "<?php echo Router::url(array('controller'=>'users','action'=>'getStateCity'));?>";
var imgPath = "<?php echo $this->webroot; ?>img/icons/error.png";
</script>
<?php
echo $this->Html->script ( 'Front/profile' );