<div class="row margin_top_referral_search">
    <div class="col-md-9 col-sm-8">
        <div class="row"> 
            <div class="col-md-8">
                <div class="referrals_reviews">
                    <div class="referrals_reviews_head padd-top0">Add Contact</div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="col-md-4 text-right"><a href="<?php echo Router::url(array('controller'=>'contacts','action'=>'importContacts'),true);?>" class="back_btn_new pull-right text-center " id="backButton"><i class="fa fa-upload"></i> Import Contacts</a></div>
        </div>
        <div class="clearfix">&nbsp;</div>
        <div class="row">
            <div class="col-md-12">
                <div class="referral_profile_head"></div>
            </div>
            <?php echo $this->Form->create('Contact', array('id' => 'addContactForm', 'url' => array('controller' => 'contacts', 'action' => 'addContact'), 'class' => 'Choose-a-contact', 'inputDefaults' => array('label' => false,'div' => false, 'errorMessage' => true), 'novalidate' => true)); ?>
            <!--<form class="Choose-a-contact">-->
                <div class="form-group col-md-6">
                    <label for="exampleInputPassword1">First Name<span class="star">*</span></label>
                    <?php echo $this->Form->input('first_name', array('id' => 'first_name', 'placeholder' => "First Name", 'class' => 'form-control', 'tabindex' => 1, 'maxlength' => false, 'autofocus'=>true));?>
                </div>
                <div class="form-group col-md-6">
                    <label for="exampleInputPassword1">Country</label>
                        <?php echo $this->Form->input('country',array('type'=>'text','id'=>'country','placeholder'=>"Country",'class'=>'form-control','required' => false, 'tabindex' => 7));?>
                        <?php echo $this->Form->input('country_id',array('type'=>'hidden','id'=>'country_id','class'=>'form-control'));?>
                </div>
                <div class="clearfix">&nbsp;</div>
                <div class="form-group col-md-6">
                    <label for="exampleInputPassword1">Last Name<span class="star">*</span></label>
                    <?php echo $this->Form->input('last_name', array('id' => 'last_name', 'placeholder' => "Last Name", 'class' => 'form-control', 'tabindex' => 2, 'maxlength' => false));?>
                </div>
                <div class="form-group col-md-6">
                    <label for="exampleInputPassword1">State</label>
                    <div id="stateDiv">
                        <?php echo $this->Form->input('state',array('type'=>'text','id'=>'state','placeholder'=>'State','class'=>'form-control', 'required' => false, 'tabindex' => 8));?>
                        <?php echo $this->Form->input('state_id',array('type'=>'hidden','id'=>'state_id','class'=>'form-control'));?>
                    </div>
                </div>
                <div class="clearfix">&nbsp;</div>
                <div class="form-group col-md-6">
                    <label for="exampleInputPassword1">Company</label>
                    <?php echo $this->Form->input('company', array('id' => 'company', 'placeholder' => "Company", 'class' => 'form-control', 'tabindex' => 3, 'maxlength' => false));?>
                </div>
                <div class="form-group col-md-3">
                    <label for="exampleInputPassword1">City</label>
                    <?php echo $this->Form->input('city', array('id' => 'city', 'placeholder' => "City", 'class' => 'form-control', 'tabindex' => 9, 'maxlength' => false));?>
                </div>
                <div class="form-group col-md-3">
                    <label for="exampleInputPassword1">ZIP Code</label>
                    <?php echo $this->Form->input('zip', array('id' => 'zip', 'placeholder' => "ZIP Code", 'class' => 'form-control', 'tabindex' => 10, 'maxlength' => false));?>
                </div>
                <div class="clearfix">&nbsp;</div>
                <div class="form-group col-md-6">
                    <label for="exampleInputPassword1">Job Title<span class="star">*</span></label>
                    <?php echo $this->Form->input('job_title', array('id' => 'job_title', 'placeholder' => "Job Title", 'class' => 'form-control', 'tabindex' => 4, 'maxlength' => false));?>
                </div>
                <div class="form-group col-md-6">
                    <label for="exampleInputPassword1">Address</label>
                    <?php echo $this->Form->input('address', array('id' => 'address', 'placeholder' => "Address", 'class' => 'form-control', 'tabindex' => 11, 'maxlength' => false));?>
                </div>
                <div class="clearfix">&nbsp;</div>
                <div class="form-group col-md-6">
                    <label for="exampleInputPassword1">Office Phone</label>
                    <?php echo $this->Form->input('office_phone', array('id' => 'office_phone', 'placeholder' => "Office Phone", 'class' => 'form-control', 'tabindex' => 5, 'maxlength' => false));?>
                </div>
                <div class="form-group col-md-6">
                    <label for="exampleInputPassword1">Website</label>
                    <?php echo $this->Form->input('website', array('id' => 'website', 'placeholder' => "Website", 'class' => 'form-control', 'tabindex' => 12, 'maxlength' => false));?>
                </div>
                <div class="clearfix">&nbsp;</div>
                <div class="form-group col-md-6">
                    <label for="exampleInputPassword1">Mobile</label>
                    <?php echo $this->Form->input('mobile', array('id' => 'mobile', 'placeholder' => "Mobile", 'class' => 'form-control', 'tabindex' => 6, 'maxlength' => false));?>
                </div>
                <div class="form-group col-md-6">
                    <label for="exampleInputPassword1">Email Address<span class="star">*</span></label>
                    <?php echo $this->Form->input('email', array('id' => 'email', 'placeholder' => "Email Address", 'class' => 'form-control', 'tabindex' => 13, 'maxlength' => false));?>
                </div>
                <div class="clearfix">&nbsp;</div>
                <div class="clearfix">&nbsp;</div>
                <div class="form-group">
                    <div class="col-md-12 col-sm-10">
                        <!--<button class="btn btn-sm file_sent_btn pull-right" type="button">Add Contact</button>-->
                        <button type="submit" id="submit" tabindex="100" class="btn btn-sm file_sent_btn pull-right" name="btnSubmit">Add Contact</button>

                    </div>
                </div>
            <?php echo $this->Form->end();?>
            <!--</form>-->
        </div>
    </div>
    <?php echo $this->element("Front/loginSidebar",array('tabpage' => 'contactadd'));?>
</div>
<?php echo $this->element('Front/bottom_ads');?>
<script>
    var ajaxUrl = "<?php echo Router::url(array('controller'=>'users','action'=>'getStateCity'));?>";
</script>
<?php echo $this->Html->script('Front/all');