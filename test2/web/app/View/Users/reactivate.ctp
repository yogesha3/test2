<!-- Header -->
<div class="inner_pages_heading" style="background:#fff; border:0">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="intro-text"> </div>
      </div>
    </div>
  </div>
</div>
<div class="clearfix"></div>
<section id="inner_pages_top_gap">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 ">
        <div class="become_head"> <span>WELCOME BACK TO</span> FOXHOPR</div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4 col-md-offset-4">
        <div class="select_group">
          <div class="tab_active pull-left">1</div>
          <div class="tab_border"></div>
          <div class=" select_tab pull-right">2</div>
          <div class="clearfix"></div>
          <div class="sign_up_text">SIGN UP</div>
          <div class="group_select_text">GROUP SELECTION</div>
        </div>
      </div>
    </div>
    <?php 
      echo $this->Form->create('BusinessOwner',array('id'=>'miniProfileForm','type'=>'post','class'=>'sign_up_form','inputDefaults' => array('label' => false,'div' => false,'errorMessage'=>true),'novalidate'=>true));
	  echo $this->Form->hidden('step',array("value"=>'step1'));				
	?>
    <div class="row location_search_margin_top">
      <div class="col-md-6 ">
        <div class="detail_below text-center">
          <div class="detail_head">Enter Your Details Below</div>
        </div>	      
          <div class="form-group">
            <label for="exampleInputEmail1">First Name</label>
            <?php echo $this->Form->input('fname',array('type'=>'text','id'=>'fname','placeholder'=>"First Name",'class'=>'form-control','autocomplete'=>'off', 'readonly' => true, 'value' => $userData['BusinessOwners']['fname']));?>
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Last Name</label>
            <?php echo $this->Form->input('lname',array('type'=>'text','id'=>'lname','placeholder'=>"Last Name",'class'=>'form-control','autocomplete'=>'off', 'readonly' => true, 'value' => $userData['BusinessOwners']['lname']));?>
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Company</label>
            <?php echo $this->Form->input('company',array('type'=>'text','id'=>'company','placeholder'=>"Company",'class'=>'form-control','autocomplete'=>'off', 'readonly' => true, 'value' => $userData['BusinessOwners']['company']));?>
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1"> Profession</label>
            <?php //echo $this->Form->input('profession_id',array('type'=>'text','id'=>'professionlist','placeholder'=>"Enter Profession Name",'class'=>'form-control','maxlength'=>30));?>
            <?php echo $this->Form->input('profession_name', array('id'=>'profession_name','value'=>$userData['Profession']['profession_name'],'class'=>"form-control", 'readonly' => true));?>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="form-group col-md-6">
                  <label for="exampleInputPassword1">Country<span class="star">*</span></label>
                      <?php echo $this->Form->input('country',array('type'=>'text','id'=>'country','placeholder'=>"Country",'class'=>'form-control','required' => false, 'value' => $userData['Country']['country_name'], 'tabindex' => 7, 'readonly' => true));?>
                      <?php echo $this->Form->input('country_id',array('type'=>'hidden','id'=>'country_id','class'=>'form-control', 'value' => $userData['BusinessOwners']['country_id']));?>
              </div>
              <div class="col-xs-6 col-md-6">
                <label for="exampleInputPassword1">TimeZone</label>
                <?php echo $this->Form->input('profession_name', array('id'=>'profession_name','value'=>$userData['BusinessOwners']['timezone_id'],'class'=>"form-control", 'readonly' => true, 'readonly' => true));?>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-xs-6 col-md-5">
                <label for="exampleInputPassword1">City</label>
                <?php echo $this->Form->input('city',array('type'=>'text','id'=>'city','placeholder'=>"City",'class'=>'form-control','autocomplete'=>'off', 'readonly' => true, 'value' => $userData['BusinessOwners']['city']));?>                
              </div>
              <div class="col-xs-6 col-md-4">
                <label for="exampleInputPassword1">State</label>
                <div id="stateDiv">
                     <?php echo $this->Form->input('state',array('type'=>'text','id'=>'state','placeholder'=>'State','class'=>'form-control', 'required' => false, 'value' => $userData['State']['state_subdivision_name'], 'readonly' => true));?>
                     <?php echo $this->Form->input('state_id',array('type'=>'hidden','id'=>'state_id','class'=>'form-control', 'readonly' => true, 'value' => $userData['BusinessOwners']['state_id']));?>
                </div>
              </div>
              <div class="col-xs-6 col-md-3">
                <label for="exampleInputPassword1">Zip Code</label>
                <?php echo $this->Form->input('zipcode',array('type'=>'text','id'=>'zipcode','placeholder'=>"Zip Code",'class'=>'form-control','maxlength'=>12,'autocomplete'=>'off', 'readonly' => true, 'value' => $userData['BusinessOwners']['zipcode']));?>
              </div>
            </div>
          </div>
          
          <div class="form-group">
            <label for="exampleInputEmail1">Email Address </label>
            <?php echo $this->Form->input('User.user_email',array('type'=>'text','id'=>'email_address','placeholder'=>"Email Address",'class'=>'form-control','autocomplete'=>'off', 'readonly' => true, 'value' => $userData['BusinessOwners']['email']));?>
          </div>
          <div  class="creating-an-account">By creating an account, you agree to Foxhopr <span>“terms of service.”</span></div>
      </div>
      <div class="col-md-6 ">
        <div class="sign_up_divider">
          <div class="detail_below text-center">
            <div class="pricing">Pricing</div>
          </div>
          <div  class="monthly-fee">Flat Monthly Fee: $49.99, Never More, Sometimes Less.</div>
          <ul class="about_fee">
            <li>Global and Local Teams</li>
            <li>100% Virtual </li>
            <li>20+ Professionals Per Group </li>
            <li>No Contract</li>
            <li>Two Meetings Each Month </li>
          </ul>
          <div class="clearfix"></div>
          <div class="clearfix" style="height:40px"></div>
          <div class="detail_below text-center">
            <div class="payment-info">Payment Info</div>
          </div>
          <div class="clearfix" style="height:10px"></div>
          <div  class="monthly-fee ">Please Enter Payment Details Below</div>
          <div class="clearfix">&nbsp;</div>          
            <div class="form-group">
              <label for="exampleInputPassword1">Name on Card*</label>
              <?php echo $this->Form->input('CC_Name',array('id'=>'card_holder_name','autocomplete'=>"off",'placeholder'=>"Name on Card",'class'=>'form-control'));?>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-xs-6">
                  <label for="exampleInputPassword1">Card Number*</label>
                  <?php echo $this->Form->input('CC_Number',array('id'=>'card_number','autocomplete'=>"off",'placeholder'=>"Card Number",'class'=>'form-control','maxlength'=>'16'));?>
                </div>
                <div class="col-xs-6 payment_info_div">
                  <label for="exampleInputPassword1"></label>
                  <div class="clearfix"></div>
                  <a href="#"> <?php echo $this->Html->image('payment1.jpg',array('alt'=> ''));?> </a> <a href="#"> <?php echo $this->Html->image('payment2.jpg',array('alt'=> ''));?></a><a href="#"> <?php echo $this->Html->image('payment3.jpg',array('alt'=> ''));?></a> </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-xs-6 col-md-4">
                  <label for="exampleInputPassword1">Expiration Date*</label>
                  <?php echo $this->Form->month('expiration_month', array('id'=>'expiration_month','empty' => 'Month','class'=>"form-control"));?>
                </div>
                <div class="col-xs-6 col-md-4">
                  <label for="exampleInputPassword1">&nbsp;</label>
                  <?php echo $this->Form->year('expiration_year', 2030, date('Y'),array('class'=>'form-control','orderYear'=>'asc','empty'=>'Year'));?>
                </div>
                <div class="col-xs-6 col-md-3 clear_fix">
                  <label for="exampleInputPassword1">CVC*</label>
                  <?php echo $this->Form->password('cvv',array('id'=>'cvvNumber','autocomplete'=>"off",'placeholder'=>"CVC",'class'=>"form-control",'maxlength'=>3));?>
                </div>
                <!-- <div class="col-xs-6 col-md-3">
                  <label for="exampleInputPassword1">CC Zip Code</label>
                  <input type="text" placeholder="CC Zip Code" id="exampleInputEmail1" class="form-control">
                </div> -->
              </div>
            </div>
            <div class="clearfix" style="height:25px"></div>
            <div class="detail_below text-center">
              <div class="detail_head">Subscription Summary</div>
            </div>
            <div class="clearfix" style="height:10px"></div>
            <div class="form-group">
              <div class="row">
                <div class="col-xs-8 ">
                  <div class="monthly-fee">Monthly Subscription</div>
                </div>
                <div class="col-xs-4 payment_info_div">
                  <div class="monthly-fee"> $49.99</div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-xs-8">
                  <?php echo $this->Form->input('code',array('type'=>'text','class'=>'form-control promocod_input','autocomplete'=>"off",'id'=>'code','placeholder'=>"Promo code" , 'maxlength'=>9 ));?>
                  <a href="#" id="apply" class="apply_promocode pull-right"><i class="codecheck"></i> Apply</a>
                  <div id="couponerror" class="error"></div>
                </div>
                <div class="col-xs-4 payment_info_div">
                  <div class="monthly-fee fee-color discount"> -$00.00</div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-xs-8">
                  <div class="monthly-fee">Total Monthly Subscription Fee: </div>
                </div>
                <div class="col-xs-4 payment_info_div">
                  <div class="monthly-fee fee-color-black totalamount"> $49.99</div>
                </div>
              </div>
            </div>
            <?php
            $this->Js->get('#apply');
            $this->Js->event('click',
              $this->Js->request(array(
                'controller'=>'Users',
                'action'=>'checkCoupon'),
              array('async'=>true,
                'dataExpression'=>true,
                'data' => '$(\'#code,#email_address\').serializeArray()',
                'method'=>'post',
                'success' => 'updateData(data);'
                )
              )
              );
              ?>
            <div class="col-xs-12 "> 
            	<?php echo $this->Form->button('Next Step',array('class'=>'next-step-btn pull-right','id'=>'next', 'type'=>'submit'));?>
            </div>
            <div class="clearfix"></div>          
        </div>
      </div>
      <div class="clearfix"></div>
    </div>
    <?php echo $this->Form->end();?>
    <div class="clearfix"></div>
  </div>
</section>
<div class="clearfix"></div>
<div class="clearfix"></div>
<script type="text/javascript">


function updateData(data) {
  var jsonData = JSON.parse(data);
  if(jsonData.response == 'success') {
    if(jsonData.data.afterDiscount == 0) {
      jsonData.data.afterDiscount = '00.00';
    }
    $('.discount').html('-$'+jsonData.data.discountValue);
    $('.totalamount').html('$'+jsonData.data.afterDiscount);
    $('.codecheck').addClass('fa fa-check ');
    $('#code').removeClass('error');
    $('#couponerror').html('');
  } else if(jsonData.response == 'fail') {
    $('.discount').html('-$00.00');
    $('.totalamount').html('$49.99');
    $('#code').addClass('error');
    $('.codecheck').removeClass('fa fa-check ');
    $('#couponerror').html(jsonData.message);
  }
}
 </script>