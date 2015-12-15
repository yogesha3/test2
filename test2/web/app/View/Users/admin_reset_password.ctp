<?php

/**
 * Reset password form page for admin 
 */
?>
<!-- start: LOGIN BOX -->
<div class="box-login">
    <?php
    echo $this->Html->tag('h3', 'Reset your password.');
    echo $this->Html->tag('p', 'Please enter your new password to reset your password.');
    echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' => 'resetPassword',$this->params['pass'][0],$this->params['pass'][1]), 'class' => 'form-login', 'id' => 'UserResetPasswordForm'));
    echo $this->Html->tag('div', '<i class="fa fa-remove-sign"></i> You have some form errors. Please check below.', array('class' => 'errorHandler alert alert-danger no-display'));
    ?>
    <fieldset>
        <div class="form-group">
            <?php echo $this->Html->tag('span', $this->Form->input('password', array('label' => false, 'div' => false, 'class' => 'form-control', 'placeholder' => 'New Password')) . '<i class="fa fa-lock"></i>', array('class' => 'input-icon')); ?>
        </div>
        <div class="form-group form-actions">
            <?php echo $this->Html->tag('span', $this->Form->input('cpassword', array('label' => false, 'div' => false, 'class' => 'form-control password', 'placeholder' => 'Confirm Password', 'type' => 'password')) . '<i class="fa fa-lock"></i>',array('class' => 'input-icon'));?>
        </div>
        <div class="form-actions">
            <?php echo $this->Form->button('Submit <i class="fa fa-arrow-circle-right"></i>', array('type' => 'submit', 'class' => 'btn btn-bricky pull-right'));?>
        </div>
        <div class="new-account">
            <!--      <a href="#" class="register">
            Create an account
            </a>-->
        </div>
    </fieldset>
<?php echo $this->Form->end();?>
</div>

