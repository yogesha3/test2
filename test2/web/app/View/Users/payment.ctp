<?php

/**
 * Payment Detail View page
 * @author Laxmi Saini
 */
?>

<section class="contact-detail" id="contact">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-header margin0">
                    <div class="about_text">
                        <h1 class="H-Text-Bold">Fill your details</h1>
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
                                <?php //echo $this->Html->link('<i class="fa fa-file-picture-o"></i> Upload Photo','#', array('class' => 'change_profile profile_font become_btn','escape'=>false));?>
                            </div>
                        </div>
                        <div class="col-xs-12  col-md-9  col-md-offset-1">
                            <?php //echo $selectedPlan; ?>
                            <div class="clearfix"></div>
                            <?php echo $this->Form->create('BusinessOwner',array('id'=>'PaymentDetailForm','type'=>'post','url'=>array('controller'=>'payments','action'=>'index'),'class'=>'sky-form','inputDefaults' => array('label' => false,'div' => false,'error'=>true),'novalidate'=>true));?>
                          
                            <div class="col-md-12">
                                <header class="Personal_info" style="margin-right: 7px">Payment Information </header>
                            </div>
                            
                            <div class="clearfix"></div>
                            <fieldset>
                                <div class="row">
                                    <section class="col col-6">
                                        <?php echo $this->Form->label('', 'CC No:', array('class' => 'label_join', 'for' => 'ccNumber')); ?>
                                        <label class="input">
                                                <?php echo $this->Form->input('CC_Number',array('type'=>'text','id'=>'ccNumber','placeholder'=>"Credit Card Number"));?>
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <?php echo $this->Form->label('', 'CC Name:', array('class' => 'label_join', 'for' => 'ccName')); ?>
                                        <label class="input">
                                            <?php echo $this->Form->input('CC_Name',array('type'=>'text','id'=>'ccName','placeholder'=>"Credit Card Name"));?>
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-6">
                                        <?php echo $this->Form->label('', 'CVV:', array('class' => 'label_join', 'for' => 'cvvNumber'));?>
                                        <label class="input">
                                            <?php echo $this->Form->input('cvv',array('type'=>'text','id'=>'cvvNumber','placeholder'=>"CVV"));?>
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <?php echo $this->Form->label('', 'Expiration:', array('class' => 'label_join', 'for' => 'expiration'));?>
                                        <label class="input">
                                            <?php echo $this->Form->input('expiration',array('type'=>'text','id'=>'expiration','placeholder'=>"Expiration"));?>
                                        </label>
                                    </section>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="row"></div>
                            </fieldset>
                            <footer class="form_footer">
                                <?php 
//                                echo $this->Html->link('Back',array('controller'=>'users','action'=>'professionalInfo'),array('class'=>'reset_btn button pull-left become_btn', 'escape'=>false));
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
    $(document).ready(function(){
        $("#PaymentDetailForm").validate({
	    rules: {
	        'data[BusinessOwner][CC_Number]': {
	            required: true
	        },
	        'data[BusinessOwner][CC_Name]': {
                    required: true,
	        },
	        'data[BusinessOwner][cvv]': {
                    required: true,
	        },
	        'data[BusinessOwner][expiration]':{
                    required: true
	        },
	    },
	    messages: {
	        'data[BusinessOwner][CC_Number]': {
                    required: "This field is required"
	        },
	        'data[BusinessOwner][CC_Name]': {
                    required: "This field is required",
	        },
	        'data[BusinessOwner][cvv]': {
                    required: "This field is required",
	        },
	        'data[BusinessOwner][expiration]':{
                    required: "This field is required",
	        }
	    },
	    submitHandler: function (form) {
	    	form.submit();
	    }
	});
    });
</script>
