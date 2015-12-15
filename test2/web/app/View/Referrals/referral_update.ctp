<!--<section id="inner_pages_top_gap">-->
  <!--<div class="container">-->
<?php echo $this->Form->create('ReceivedReferral', array('id' => 'referralUpdate','url' => array('controller' => 'referrals','action' => 'referralUpdate',$referralData['ReceivedReferral']['id'])));?>
<div class="Choose-a-contact">
    <div class="row margin_top_referral_search">
      <div class="col-md-9 col-sm-8">
               <div class="row"> 
					 <div class="col-md-8">
				  		<div class="referrals_reviews">
						<div class="referrals_reviews_head padd-top0">Update Referral</div>            
						<div class="clearfix"></div>
					</div>
					
            	</div>
				<div class="col-md-4 text-right rightslide">
						<?php if($referer!=NULL){?>
						  <a href="<?php echo base64_decode($referer);?>" class="btn-sm  back_btn_new pull-right" ><i class="fa fa-arrow-circle-left"></i> Back</a>
						  <?php }else{?>
						  <a href="<?php echo $this->request->referer();?>" class="btn-sm  back_btn_new pull-right" ><i class="fa fa-arrow-circle-left"></i> Back</a>
						  <?php }?>
					</div>

            </div>
         <div class="clearfix">&nbsp;</div>

      
        <div class="row">
        <div class="col-md-12">
        <div class="referral_profile_head"> <span>Referral Profile</span></div>
        </div>
        <?php
            echo $this->Form->input('referral_status', array('type' => 'hidden', 'value' => $referralData['ReceivedReferral']['referral_status']));
        ?>
  
  <div class="form-group col-md-6">
    <label for="exampleInputPassword1">First Name</label>
    <?php
        echo $this->Form->input('first_name', array('type' => 'text', 'placeholder' => 'First Name', 'class' => 'form-control', 'label' => false, 'div' => false, 'value' => $referralData['ReceivedReferral']['first_name'],'readonly'=>'readonly'));
    ?>
  </div>
  <div class="form-group col-md-6">
    <label for="exampleInputPassword1">Country</label>
    <?php
        echo $this->Form->input('country', array('type' => 'text', 'placeholder' => 'Country', 'class' => 'form-control', 'label' => false, 'div' => false,'readonly' =>true, 'value' => $referralData['Country']['country_name']));
    ?>
  </div>
  <div class="clearfix">&nbsp;</div>
  <div class="form-group col-md-6">
    <label for="exampleInputPassword1">Last Name</label>
    <?php
        echo $this->Form->input('last_name', array('type' => 'text', 'placeholder' => 'Last Name', 'class' => 'form-control', 'label' => false, 'div' => false, 'value' => $referralData['ReceivedReferral']['last_name'],'readonly'=>'readonly'));
    ?>
  </div>
  <div class="form-group col-md-6">
    <label for="exampleInputPassword1">State</label>
    <?php
    echo $this->Form->input('state', array('type' => 'text', 'placeholder' => 'State', 'class' => 'form-control', 'label' => false, 'div' => false,'readonly' =>true, 'value' => $referralData['State']['state_subdivision_name']));
    ?>
  </div>
  <div class="clearfix">&nbsp;</div>
  <div class="form-group col-md-6">
    <label for="exampleInputPassword1">Company</label>
    <?php
        echo $this->Form->input('company', array('type' => 'text', 'placeholder' => 'Company', 'class' => 'form-control', 'label' => false, 'div' => false, 'value' => $referralData['ReceivedReferral']['company'], 'autofocus'=>true));
    ?>
  </div>
  <div class="form-group col-md-3">
    <label for="exampleInputPassword1">City</label>
    <?php
        echo $this->Form->input('city', array('type' => 'text', 'placeholder' => 'City', 'class' => 'form-control', 'label' => false, 'div' => false, 'value' => $referralData['ReceivedReferral']['city'],'readonly'=>'readonly'));
    ?>
  </div>

  <div class="form-group col-md-3">
    <label for="exampleInputPassword1">Zip Code</label>
    <?php
        echo $this->Form->input('zip', array('type' => 'text', 'placeholder' => 'Zip Code', 'class' => 'form-control', 'label' => false, 'div' => false, 'value' => $referralData['ReceivedReferral']['zip'],'readonly'=>'readonly'));
    ?>
  </div>
  <div class="clearfix">&nbsp;</div>
  <div class="form-group col-md-6">
    <label for="exampleInputPassword1">Job Title<span class="star">*</span></label>
    <?php
        echo $this->Form->input('job_title', array('type' => 'text', 'placeholder' => 'Job Title', 'class' => 'form-control', 'label' => false, 'div' => false, 'value' => $referralData['ReceivedReferral']['job_title']));
    ?>
  </div>
  <div class="form-group col-md-6">
    <label for="exampleInputPassword1">Address</label>
    <?php
        echo $this->Form->input('address', array('type' => 'text', 'placeholder' => 'Address', 'class' => 'form-control', 'label' => false, 'div' => false, 'value' => $referralData['ReceivedReferral']['address']));
    ?>
  </div>
  <div class="clearfix">&nbsp;</div>

  <div class="form-group col-md-6">
    <label for="exampleInputPassword1">Office Phone</label>
    <?php
        echo $this->Form->input('office_phone', array('type' => 'text', 'placeholder' => 'Office Phone', 'class' => 'form-control', 'label' => false, 'div' => false, 'value' => $referralData['ReceivedReferral']['office_phone']));
    ?>
  </div>
  <div class="form-group col-md-6">
    <label for="exampleInputPassword1">Website</label>
    <?php
        echo $this->Form->input('website', array('type' => 'text', 'placeholder' => 'Website', 'class' => 'form-control', 'label' => false, 'div' => false, 'value' => $referralData['ReceivedReferral']['website']));
    ?>
  </div>
  <div class="clearfix">&nbsp;</div>

  <div class="form-group col-md-6">
    <label for="exampleInputPassword1">Mobile</label>
    <?php
        echo $this->Form->input('mobile', array('type' => 'text', 'placeholder' => 'Mobile', 'class' => 'form-control', 'label' => false, 'div' => false, 'value' => $referralData['ReceivedReferral']['mobile']));
    ?>
  </div>
  <div class="form-group col-md-6">
    <label for="exampleInputPassword1">Email Address</label>
    <?php
        echo $this->Form->input('email', array('type' => 'email', 'readonly' =>true, 'placeholder' => 'Email Address', 'class' => 'form-control', 'label' => false, 'div' => false, 'value' => $referralData['ReceivedReferral']['email']));
    ?>
  </div>
  <div class="clearfix">&nbsp;</div>

  <div class=" col-md-12">
              <div class="clearfix">&nbsp;</div>
              <div class="price_text font14px">Monetary value of received referral &nbsp;&nbsp;&nbsp;&nbsp;</div>
                <div class="price">$</div>
				<?php echo $this->Form->input('monetary_value', array('type' => 'text', 'placeholder' => '0', 'class' => 'btn-sm  price_btn rs_input pull-left ' ,'style' => 'text-align:left;padding-left:18px;width:115px;', 'label' => false, 'div' => false, 'value' => $referralData['ReceivedReferral']['monetary_value'])); ?>
              
            </div>

      <div class="clearfix">&nbsp;</div>
      <div class="clearfix">&nbsp;</div>
            <div class="col-md-12">
        <div class="referral_profile_head"> <span>REFERRAL STATUS</span></div>
        <div class="row">
        <div class="col-md-12">
         <div class="referral_status border-L0"> 
        <div class="radio_btn">  <input type="radio" name="optionsRadios" id="received" value="received" <?php echo ($referralData['ReceivedReferral']['referral_status'] == 'received') ? 'checked=checked' : '';?> onClick="changeReferralStatus(this.value);">   </div>
         <div class="status_head">Received</div>
         <div class="status_description"> The referral has been received by the recipient  </div>
           </div>
           <div class="referral_status"> 
        <div class="radio_btn">  <input type="radio" name="optionsRadios" id="contacted" value="contacted" <?php echo ($referralData['ReceivedReferral']['referral_status'] == 'contacted') ? 'checked=checked' : '';?> onClick="changeReferralStatus(this.value);">   </div>
         <div class="status_head">Contacted   </div>
         <div class="status_description"> The recipient has made one or more attempts to contact the referral </div>
           </div>
           <div class="referral_status"> 
        <div class="radio_btn">  <input type="radio" name="optionsRadios" id="proposal" value="proposal" <?php echo ($referralData['ReceivedReferral']['referral_status'] == 'proposal') ? 'checked=checked' : '';?> onClick="changeReferralStatus(this.value);">   </div>
         <div class="status_head">Proposal    </div>
         <div class="status_description"> A formal quote or estimate has been provided to the <br/>referral   </div>
           </div>
           <div class="referral_status"> 
        <div class="radio_btn">  <input type="radio" name="optionsRadios" id="success" value="success" <?php echo ($referralData['ReceivedReferral']['referral_status'] == 'success') ? 'checked=checked' : '';?> onClick="changeReferralStatus(this.value);"></div>
         <div class="status_head">Success  </div>
         <div class="status_description"> The recipient has successfully engaged in business with the referral </div>
           </div>
           <div class="referral_status"> 
        <div class="radio_btn">  <input type="radio" name="optionsRadios" id="kaput" value="kaput"<?php echo ($referralData['ReceivedReferral']['referral_status'] == 'kaput') ? 'checked=checked' : '';?>  onClick="changeReferralStatus(this.value);">   </div>
         <div class="status_head">Kaput  </div>
         <div class="status_description"> The recipient was unable to engage in business with the referral   </div>
           </div>
           </div>
     <div class="clearfix">&nbsp;</div>
      <div class="clearfix">&nbsp;</div>
        <div class="col-md-3 pull-right">
          <button class="btn add_focus btn-sm  back_btn pull-right" type="submit" id="referralUpdateButton">Save</button>
          
        </div>
      </div>
    </div>
        </div>
      </div>
         <?php echo $this->element("Front/loginSidebar",array('tabpage'=>'receivededit'));?>
    </div>
    <div class="row mt20px">
    
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->element('Front/bottom_ads');?>

          <!--<div class="col-md-3">-->
          <!--<div class="clearfix">&nbsp;</div>-->
  <?php
        //echo $this->Form->input('monetary_value', array('type' => 'text', 'placeholder' => '$0', 'class' => 'form-control', 'label' => false, 'div' => false, 'value' => '$'.$referralData['ReceivedReferral']['monetary_value']));
    ?>
         <!--<button type="button" class="btn btn-sm  price_btn ">$900</button>-->
<!--          <div class="clearfix">&nbsp;</div>-->
<!--        <div class="archive_text font14px">What is the monetary value-->
<!--of the received referrals</div>-->
        <!--</div>-->
    
    </div>
  <!--</div>-->
<!--</section>-->
<script>
  /**
 * ajaxChange() to fetch State /City list on country selection
 * @param url
 * @param location_id: country id
 * @param location_type: type of list to be fetched 1: state list, 2:city list
 */
function getStateList(countryId) {
    var ajaxUrl = "<?php echo Router::url(array('controller'=>'users','action'=>'getStateCity'));?>";
    if (countryId!= '') {
        $.ajax({
            'type': 'post',
            'data': {'countryId': countryId},
            'url': ajaxUrl,
            success: function (msg) {
                $('#stateDiv').html(msg);
            }
        });
    }
    if (countryId == '') {
        $('#stateDiv').html("<select id='state' class='form-control' name='data[ReceivedReferral][state_id]'><option value=''>Select State</option></select>");
    }
}

function changeReferralStatus(status) {
  $('#ReceivedReferralReferralStatus').val(status);
  $('#'+status).attr('checked','checked');
}
</script>