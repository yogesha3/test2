<?php 
echo $this->Html->script('Front/dropzone');
echo $this->Html->css('dropzone');
echo $this->Html->script('Front/all');
?>
<div class="row margin_top_referral_search">

	<div class="col-md-9 col-sm-8">
		<div class="row">
			<div class="col-md-12">
				<div class="referrals_reviews">
					<div class="referrals_reviews_head padd-top0">Send Referral</div>

					<div class="clearfix"></div>
				</div>
			</div>
		</div>
		<div class="clearfix">&nbsp;</div>
		<?php
	      echo $this->Form->create('Contact',array('id'=>'ContactForm','type'=>'file','url'=>array('controller'=>'referrals','action'=>'sendReferrals'),'class'=>'Choose-a-contact','inputDefaults' => array('label' => false,'div' => false,'errorMessage'=>true),'novalidate'=>true));
		?>
		<div class="row ">
		<div class="col-md-12 col-sm-6 col-xs-7 width_at_mob">
			<div class="send-referral">Send to<span class="star">*</span> &nbsp;&nbsp;</div>
			<div class=" action_bulk validClass">
            <?php $select = isset($selected) ? $selected : '';?>
				<?php echo $this->Form->input('group_id', array('id'=>'multiselect','type'=>'select','options'=>$groupMembersList,'empty' => false,'name'=>'multiselect','class'=>"form-control seclect_value seclect_bulk",'multiple'=>'multiple','label'=>false,'selected' => $select,'tabindex'=>1));?>
			</div>
		</div>
		</div>
		<div class="clearfix">&nbsp;</div>
		<div class="row">
			<div class="col-md-12">
				<div class="referral_profile_head"> <span>Details</span></div>
			</div>
				<div class="form-group col-md-6">
					<label for="exampleInputPassword1"> Choose a contact  or add a new contact below</label>
            <?php
            if (!empty($contactData)) {
                foreach ($contactData as $contact) {
                    $contactList[$contact['id']] = ucfirst($contact['first_name']) . " " . ucfirst($contact['last_name']) . ", " . ucfirst($contact['job_title']);
                }
                if (!isset($contactListId)) {
                    $contactListId = false;
                }
                echo $this->Form->input('contact', array(
                                            'id' => 'multiselect2',
                                            'type' => 'select',
                                            'options' => $contactList,
                                            'empty' => 'Select an existing contact',
                                            'class' => 'form-control seclect_value seclect_bulk multiselectContact',
                                            'onChange' => 'getUserDetail(this.value)',
                                            'label' => false,
                                            'tabindex' => 1,
                                            'selected' => $contactListId
                                            )
                                        );
            } else {
                $contactList = '';
                echo $this->Form->input('contact', array(
                                            'type' => 'select',
                                            'options' => $contactList,
                                            'empty' => 'Select an existing contact',
                                            'class' => 'form-control seclect_value seclect_bulk multiselectContact',
                                            'onChange' => 'getUserDetail(this.value)',
                                            'label' => false,
                                            'tabindex' => 1
                                            )
                                        );
            }
			?>
				</div>

				<div class="form-group col-md-6">
					<label for="exampleInputPassword1">Country</label>
            <?php echo $this->Form->input('country',array('type'=>'text','id'=>'country','placeholder'=>"Country",'class'=>'form-control','required' => false,'tabindex'=>8));?>
            <?php echo $this->Form->input('country_id',array('type'=>'hidden','id'=>'country_id','class'=>'form-control'));?>
				</div>
        <div class="clearfix">&nbsp;</div>

				<div class="form-group col-md-6">
					<label for="exampleInputPassword1">First Name<span class="star">*</span></label>
					<?php echo $this->Form->input('first_name',array('type'=>'text','id'=>'first_name','placeholder'=>"First Name",'class'=>'form-control','tabindex'=>1,'maxlength'=>false, 'autofocus'=>true));?>
				</div>
				<div class="form-group col-md-6">
					<label for="exampleInputPassword1">State</label>
					<div id="stateDiv">
            <?php echo $this->Form->input('state',array('type'=>'text','id'=>'state','placeholder'=>'State','class'=>'form-control', 'required' => false,'tabindex'=>9));?>
            <?php echo $this->Form->input('state_id',array('type'=>'hidden','id'=>'state_id','class'=>'form-control'));?>
					</div>
				</div>
                <div class="clearfix">&nbsp;</div>

				<div class="form-group col-md-6">
					<label for="exampleInputPassword1">Last Name<span class="star">*</span></label>
					<?php echo $this->Form->input('last_name',array('type'=>'text','id'=>'last_name','placeholder'=>"Last Name",'class'=>'form-control','tabindex'=>2,'maxlength'=>false));?>
				</div>
				<div class="form-group col-md-4">
					<label for="exampleInputPassword1">City</label>
					<?php echo $this->Form->input('city',array('type'=>'text','id'=>'city','placeholder'=>"City",'class'=>'form-control','tabindex'=>10,'maxlength'=>false));?>
				</div>
				<div class="form-group col-md-2">
					<label for="exampleInputPassword1">Zip Code</label>
					<?php echo $this->Form->input('zip',array('type'=>'text','id'=>'zip','placeholder'=>"Zip Code",'class'=>'form-control','tabindex'=>11,'maxlength'=>false));?>
				</div>
        <div class="clearfix">&nbsp;</div>

				<div class="form-group col-md-6">
					<label for="exampleInputPassword1">Company</label>
					<?php echo $this->Form->input('company',array('type'=>'text','id'=>'company','placeholder'=>"Company",'class'=>'form-control','tabindex'=>3,'maxlength'=>false));?>
				</div>
				<div class="form-group col-md-6">
					<label for="exampleInputPassword1">Address</label>
					<?php echo $this->Form->input('address',array('type'=>'text','id'=>'address','placeholder'=>"Address",'class'=>'form-control','tabindex'=>12));?>
				</div>
        <div class="clearfix">&nbsp;</div>

				<div class="form-group col-md-6">
					<label for="exampleInputPassword1">Job Title<span class="star">*</span></label>
					<?php echo $this->Form->input('job_title',array('type'=>'text','id'=>'job_title','placeholder'=>"Job Title",'class'=>'form-control','tabindex'=>4,'maxlength'=>false));?>
				</div>

				<div class="form-group col-md-6">
					<label for="exampleInputPassword1">Website</label>
					<?php echo $this->Form->input('website',array('type'=>'text','id'=>'website','placeholder'=>"Website",'class'=>'form-control','tabindex'=>13,'maxlength'=>false));?>
				</div>
        <div class="clearfix">&nbsp;</div>

				<div class="form-group col-md-6">
					<label for="exampleInputPassword1">Office Phone</label>
					<?php echo $this->Form->input('office_phone',array('type'=>'text','id'=>'office_phone','placeholder'=>"Office Phone",'class'=>'form-control','tabindex'=>5,'maxlength'=>false));?>
				</div>
				<div class="form-group col-md-6">
					<label for="exampleInputPassword1">Email Address<span class="star">*</span></label>
					<?php echo $this->Form->input('email',array('type'=>'text','id'=>'email','placeholder'=>"Email Address",'class'=>'form-control','tabindex'=>14));?>
				</div>
        <div class="clearfix">&nbsp;</div>

				<div class="form-group col-md-6">
					<label for="exampleInputPassword1">Mobile</label>
					<?php echo $this->Form->input('mobile',array('type'=>'text','id'=>'mobile','placeholder'=>"Mobile",'class'=>'form-control','tabindex'=>6,'maxlength'=>false));?>
				</div>
        <div class="clearfix"></div>

        <div class="padd-left0 padd-right0">
          <!-- <div class="col-md-6">
              <label for="exampleInputPassword1">Message from Sender</label>
              <?php //echo $this->Form->textarea('note',array('id'=>'fname','class'=>'form-control','tabindex'=>20));?>
          </div> -->
          <div class="col-md-12">
            <div class="sender_msg">   Message from Sender</div>
            <div class="sender_msg_box"> 
               <?php echo $this->Form->textarea('note',array('id'=>'fname','class'=>'form-control border','tabindex'=>20,'rows'=>5, 'placeholder' => 'Enter Message'));?>
               <div class="clearfix">&nbsp;</div> 
               <div id="dropZoneArea" class="file_attatch">
                <i class="fa fa-paperclip"></i> To attach files, drag &amp; drop here or  <a id="clickable" href="javascript:void(0);"> Select files from your computer.</a> 
                <div class="clearfix">&nbsp;</div>  
            </div>
            <div class="clearfix"></div> 
            <br>
            <div class="informationnote">NOTE: Maximum 5 attachments are allowed.</div>
            <div class="clearfix"> </div>
            <button type="submit" id="submit" tabindex="100" class="btn btn-sm  file_sent_btn pull-right">Send</button>
            <div class="clearfix"></div> 
        </div>
    </div>
<!-- DROPZONE -->
<div class="table table-striped" class="files" id="previewContainer">
  <div id="template" class="file-row row">
    <!-- This is used as the file preview template -->
    <div class="col-md-1 first_row">
        <span class="preview"><img data-dz-thumbnail /></span>
    </div>
    <div class=" col-md-7">
        <p class="name img_name" data-dz-name></p>
                       
        <div>
            <p class="size size2" data-dz-size></p>
      <strong style="font-size:12px;" class="error text-danger" data-dz-errormessage></strong>
            <div class="uplod_bar">
                <div style="background-color: #fefefe" class="progress progress-striped progress2" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                  <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress> </div>
              </div>
              <a href="javascript:void(0);"><i class="fa fa-close close_icon" data-dz-remove=""></i></a> 
          </div>                    
      </div>
  </div>
  <div class=" col-md-2">
  </div>
</div>
</div>
 <div class="clearfix">&nbsp;</div>

<!-- DROPZONE -->
<div class="clearfix">&nbsp;</div>
    <div class="clearfix">&nbsp;</div>
    

</div>
</div>
<?php echo $this->Form->end();?>
</div>
  <?php echo $this->element("Front/loginSidebar",array('tabpage' => 'referralsSend'));?>
</div>
<?php echo $this->element('Front/bottom_ads');?>
<script>
var path = '<?php echo $this->webroot; ?>';
var ajaxUrl = "<?php echo Router::url(array('controller'=>'users','action'=>'getStateCity'));?>";
var getContactDetailsUrl = '<?php echo Configure::read('SITE_URL'); ?>'+'referrals/getContactDetails/';
$(document).ready(function(){
    $("#ContactForm #first_name").focus();
    var contactId = $('#multiselect2').val();
    if (contactId != '') {
        getUserDetail(contactId);
    }
    
});
</script>
<?php 
echo $this->Html->script('Front/sendreferral');
