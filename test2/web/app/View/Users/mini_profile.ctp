<?php
/**
* user mini profile: Personal Information
* @author Laxmi Saini
*/ 
?>
<section class="contact-detail" id="contact">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-header margin0">
                    <div class="about_text">
                        <?php echo $this->Html->tag('h1','Fill your details', array('class'=>'H-Text-Bold'));?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section>
    <div class="content_grey">
        <div class="container">
            <div class="row">
                <div class="col-md-12 margin50">
                    <div class="row">
                        <div class="col-md-2  ">
                            <div class=" border_pro">
                                <?php echo $this->Html->image('avatar1.jpg',array('class'=> 'img-circle img-responsive'));?> 
                            </div>
                            <div class="edit_profile">
                                <?php echo $this->Html->link('<i class="fa fa-file-picture-o"></i> Upload Photo','#', array('class' => 'change_profile profile_font become_btn','escape'=>false));?>
                            </div>
                        </div>
                        <div class="col-xs-12  col-md-9  col-md-offset-1">
                            <div class="clearfix"></div>
                            <?php
                                echo $this->Form->create('BusinessOwner',array('id'=>'miniProfileForm','type'=>'post','url'=>array('controller'=>'users','action'=>'miniProfile'),'class'=>'sky-form','inputDefaults' => array('label' => false,'div' => false,'error'=>true),'novalidate'=>true));
                                echo $this->Form->hidden('step',array("value"=>'personalInfo'));
                                echo $this->Form->hidden('selectedPlan',array('value'=>$selectedPlan));
                            ?>
                            <div class="col-md-6">
                                <header class="Personal_info" style="margin-right: 7px">Personal Information </header>
                            </div>
                            <div class="col-md-6">
                                <header class="Professional-Info" style="margin-left: 7px">Professional Information</header>
                            </div>
                            <div class="clearfix"></div>
                            <fieldset>
                                <div class="row">
                                    <section class="col col-6">
                                        <?php echo $this->Form->label('', 'First Name:', array('class' => 'label_join', 'for' => 'fname'));?>
                                        <label class="input">
                                            <?php echo $this->Form->input('BusinessOwner.fname',array('type'=>'text','id'=>'fname','placeholder'=>"First name"));?>
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <?php echo $this->Form->label('', 'Last Name:', array('class' => 'label_join', 'for' => 'lname'));?>
                                        <label class="input">
                                            <?php echo $this->Form->input('lname',array('type'=>'text','id'=>'lname','placeholder'=>"Last name"));?>
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-6">
                                        <?php echo $this->Form->label('', 'Password:', array('class' => 'label_join', 'for' => 'password'));?>
                                        <label class="input">
                                            <?php echo $this->Form->input('password',array('type'=>'password','id'=>'password','placeholder'=>"Password"));?>
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <?php echo $this->Form->label('', 'Confirm Password:', array('class' => 'label_join', 'for' => 'cpassword'));?>
                                        <label class="input">
                                            <?php echo $this->Form->input('cpassword',array('type'=>'password','id'=>'cpassword','placeholder'=>"Confirm Password"));?>
                                        </label>
                                    </section>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="row">
                                    <section class="col col-6">
                                        <?php echo $this->Form->label('', 'Country:', array('class' => 'label_join', 'for' => 'country'));?>
                                        <label class="select"> 
                                            <?php echo $this->Form->input('country_id', array('id'=>'country','type'=>'select','options'=>$countryList,'empty' => 'Please select country','onchange' => "getStateList(this.value)"));?> <i></i>
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <?php echo $this->Form->label('', 'State:', array('class' => 'label_join', 'for' => 'state'));?>
                                        <label class="select" id="stateD">
                                            <?php echo $this->Form->input('state_id',array('type' => 'select','id' => 'state', 'options' => $stateList, 'default' => !empty($this->Session->read("UserData")['BusinessOwner']['state_id'])? $this->Session->read("UserData")['BusinessOwner']['state_id'] : ''));?>
                                            <i></i>
                                        </label>
                                    </section>
                                    
                                     <section class="col col-6">
                                        <?php echo $this->Form->label('', 'City:', array('class' => 'label_join', 'for' => 'city'));;?>
                                        <label class="input">
                                            <?php echo $this->Form->input('city',array('type'=>'text','id'=>'city','placeholder'=>"City"));?>
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <?php echo $this->Form->label('', 'ZIP Code:', array('class' => 'label_join', 'for' => 'zipcode'));?>
                                        <label class="input">
                                            <?php echo $this->Form->input('zipcode',array('type'=>'text','id'=>'zipcode','placeholder'=>"Zip Code:"))?>
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <?php echo $this->Form->label('', 'Timezone:', array('class' => 'label_join', 'for' => 'timezone'));?>
                                        <label class="select">
                                            <?php echo $this->Form->select('timezone_id', $timezoneList, array('label' => false, 'class' => 'form-control', 'id' => 'timezone', 'required'=>false, 'empty' => 'Select Timezone')); ?>
                                            <i></i>
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <?php echo $this->Form->label('', 'Mobile:', array('class' => 'label_join', 'for' => 'mobile'));?>
                                        <label class="input">
                                            <?php echo $this->Form->input('mobile',array('type'=>'text','id'=>'mobile','placeholder'=>"Mobile:"));?>
                                        </label>
                                    </section>
                                </div>
                            </fieldset>
                            <footer class="form_footer">
                                <?php 
                                //echo $this->Html->link('Back',array('controller'=>'users','action'=>'choosePlan'),array('class'=>'reset_btn button pull-left become_btn', 'escape'=>false));
                                echo $this->Form->button('Save & Next',array('class'=>'button become_btn', 'type'=>'submit'));
                                ?>
                            </footer>
                            <?php echo $this->Form->end();?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<script type="text/javascript">
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
                $('#stateD').html(msg);
            }
        });
    }
    if (countryId == '') {
        $('#stateDiv').html("<select id='state' class='form-control' name='data[BusinessOwner][state_id]'><option value=''>Select State</option></select>");
    }
}



</script>
<script type="text/javascript">
    $(document).ready(function(){
   
   $("#miniProfileForm").validate({
	    rules: {
	        'data[BusinessOwner][fname]': {
	            required: true
	        },
	        'data[BusinessOwner][password]': {
                required: true,
                pwcheck: true,
                minlength: 8
	        },
	        'data[BusinessOwner][cpassword]': {
                required: true,
                minlength: 8,
                equalTo: "#password"
	        },
	        'data[BusinessOwner][country_id]':{
	        	required: true
	        },
	        'data[BusinessOwner][state_id]':{
	        	required: true
	        },
//	        'data[BusinessOwner][zipcode]':{
//	        	required: true,
//	        	minlength: 5,
//	        },
//	        'data[BusinessOwner][timezone_id]':{
//	        	required: true
//	        },
//	        'data[BusinessOwner][mobile]':{
//	        	phoneUS: true,
//                required: true
//	        }
	    },
	    messages: {
	        'data[BusinessOwner][fname]': {
	            required: "This field is required"
	        },
	        'data[BusinessOwner][password]': {
	        	required: "This field is required",
	        	pwcheck: "The password does not meet the criteria!",
                minlength: "The password should be minimum 8 charecter long!"
	        },
	        'data[BusinessOwner][cpassword]': {
	        	required: "This field is required",
                equalTo: "The passwords do not match!"
	        },
	        'data[BusinessOwner][mobile]':{
	        	phoneUS: "Please specify a valid phone number"
	        }
	    },
	    submitHandler: function (form) {
	    	form.submit();
	    }
	});
 });
    </script>