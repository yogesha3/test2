<?php

/**
 * user professional information
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

<!--insert professonal detail section-->
<section>
    <div class="content_grey">
        <div class="container">
            <div class="row">
                <div class="col-md-12 margin50">
                    <div class="row">
                        <div class="col-md-2">
                            <div class=" border_pro" >
                                <?php echo $this->Html->image('avatar1.jpg',array('class'=> 'img-circle img-responsive'));?> 
                            </div>
<!--                            <div class="edit_profile">
                                <a class="change_profile profile_font become_btn" href="#"><i class="fa fa-file-picture-o"></i> Change Photo</a>
                            </div>-->
                        </div>
                        <div class="col-xs-12  col-md-9  col-md-offset-1">
                            <div class="clearfix"></div>
                            <?php echo $this->Form->create('BusinessOwner',array('id'=>'miniProfileProfessionalInfoForm','type'=>'post','url'=>array('controller'=>'users','action'=>'professionalInfo'),'class'=>'sky-form','inputDefaults' => array('label' => false,'div' => false,'error'=>true),'novalidate'=>true));?>
                            <div class="col-md-6">
                                <header class="Professional-Info" style="margin-right:7px">Personal Information </header>
                            </div>
                            <div class="col-md-6">
                                <header class="Personal_info" style="margin-left:7px">Professional Information</header>
                            </div>

                            <div style="margin-right:7px" class="clearfix"></div>
                            <fieldset>					
                                <div class="row">
                                    <section class="col col-md-12">
                                        <?php echo $this->Form->label('','Choose Your Profession:',array('class'=>'label_join','for'=>'userProfession')); ?>
                                        <label class="select">
                                            <?php echo $this->Form->select('Profession_id', $profesionList, array( 'empty' => 'Choose Profession', 'id' => 'userProfession'));?>
                                            <i></i>
                                        </label>
                                    </section>

                                    <section class="col col-md-12">
                                        <?php echo $this->Form->label('','Business Description:',array('class' => 'label_join','for' => 'businessDesc')); ?>
                                        <label class="input">
                                            <section>
                                                <label class="textarea">
                                                    <?php echo $this->Form->textarea('business_description', array('rows' => 3, 'label' => false,'id' => 'businessDesc', 'placeholder' => 'Business Description','style' => "resize: none;max-width:100%",'maxlength' => 200)); ?>
                                                </label>
                                            </section>
                                        </label>
                                    </section>
                                </div>
                                </fieldset>
                                <fieldset>
                                    <div class="row">
                                        <div class="row">
                                            <section class="col col-6">
                                                <?php echo $this->Form->label('','Web Link:', array('class' => 'label_join','for' => 'weblink1'));?>
                                                <label class="input">
                                                    <?php echo $this->Form->input('website', array('placeholder' => 'Website Url','type' => 'url'));?>
                                                </label>
                                            </section>
                                            <section class="col col-6">
                                                <?php echo $this->Form->label('','Blog Link:', array('class' => 'label_join', 'for' => 'blogLink'));?>
                                                <label class="input">
                                                    <?php echo $this->Form->input('blog', array('id' => 'blogLink','placeholder' => 'Blog link'));?>
                                                </label>
                                            </section>

                                            <section class="col col-6">
                                                <?php echo $this->Form->label('','Skype ID:', array('class' => 'label_join', 'for' => 'skypeId'));?>
                                                <label class="input">
                                                    <?php echo $this->Form->input('skype_id', array('placeholder' => 'Skype ID','id' => 'skypeId','type' => 'text'));?>
                                                </label>
                                            </section>

                                            <section class="col col-6">
                                                 <?php echo $this->Form->label('','Video Profile:', array('class' => 'label_join', 'for' => 'videoProfile'));?>
                                                <label class="input">
                                                    <?php echo $this->Form->input('profile_video_link', array('placeholder' => 'Video Profile','id' => 'videoProfile'));?>
                                                </label>
                                            </section>
                                        </div>
                                    </div>
                                    <section>
                                        <div class="row">
                                            <div class="col col-8">
                                                <label class="checkbox ">
                                                    <?php echo $this->Form->checkbox('is_leadership_interest', array()); ?>
                                                    <i></i>Are you interested  in being a group leader
                                                </label>
                                            </div>
                                            <div class="col col-4">
                                                <label class="checkbox">
                                                    <?php echo $this->Form->checkbox('is_newsletter_subscriber', array());?>
                                                    <i></i>Subscribe to our newsletter
                                                </label>
                                            </div>
                                            <div class="col col-6">
                                                <label class="checkbox">
                                                    <?php echo $this->Form->checkbox('is_agree',array('id'=>'isAgree','onclick'=>'readyToSubmit();','hiddenField' => false));?>
                                                    <i></i>I agree to the terms of use of FoxHopr
                                                </label>
                                            </div>
                                        </div>
                                    </section>
                                </fieldset>
                                <footer>
                                    <?php 
                                    echo $this->Html->link('Back',array('controller'=>'users','action'=>'miniProfile'),array('class'=>'reset_btn button pull-left become_btn', 'escape'=>false));
                                    echo $this->Form->button('Save & Next',array('class'=>'button become_btn', 'type'=>'submit','id'=>'submitProfessionaInfo','disabled'));
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

<!--end contact detail-->
<script type="text/javascript">
    $(document).ready(function(){
       readyToSubmit();
    });
    function readyToSubmit()
    {
        if($('#isAgree').is(":checked")){ //if($(this).is(":not(:checked)"))
            $('#submitProfessionaInfo').attr('disabled',false);
        }else{
           $('#submitProfessionaInfo').attr('disabled',true); 
        }
    }
    </script>
    <script type="text/javascript">
    $(document).ready(function(){
   
   $("#miniProfileProfessionalInfoForm").validate({
	    rules: {
	        'data[BusinessOwner][profession_id]': {
	            required: true
	        },
	        'data[BusinessOwner][business_description]': {
	          maxlength: 200,
	        }
	    },
	    messages: {
	        'data[BusinessOwner][fname]': {
	            required: "This field is required"
	        },
	        'data[BusinessOwner][business_description]': {
	             maxlength: "<?php echo __('Maximum 200 characters are allowed'); ?>",
	        }
	      
	    },
	    submitHandler: function (form) {
	    	form.submit();
	    }
	});
    });

    </script>