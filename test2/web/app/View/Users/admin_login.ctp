<?php

/**
 * Admin Login Form
 */
?>
<!-- start: LOGIN BOX -->
<div class="box-login">
    <?php 
        echo $this->Html->tag('h3','Login to your account');
        $errorClass = ($this->Session->flash('error')  != '') ? 'error' : '';
        echo $this->Html->tag('p', 'Please enter your e-mail and password to log in.');
        echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' => 'login','admin'=>true), 'class' => 'form-login', 'id' => 'UserLoginForm'));
        echo $this->Html->tag('div', '<i class="fa fa-remove-sign"></i> You have some form errors. Please check below.', array('class' => 'errorHandler alert alert-danger no-display'));
    ?>

    <fieldset>
        <div class="form-group">
            <?php echo $this->Html->tag('span', $this->Form->input('user_email', array('label' => false, 'div' => false,'autofocus'=>true, 'class' => 'form-control '.$errorClass, 'placeholder' => 'E-mail')) . '<i class="fa fa-user"></i>', array('class' => 'input-icon')); ?>
        </div>
        <div class="form-group form-actions">
            <?php
                echo $this->Html->tag('span', $this->Form->input('password', array('label' => false, 'div' => false, 'class' => 'form-control password '.$errorClass, 'placeholder' => 'Password', 'type' => 'password' ,'id' => 'password')) . '<i class="fa fa-lock"></i>' .
                        $this->Html->link('I forgot my password', array('action'=>'forgotPassword'), array('class' => 'forgot')), array('class' => 'input-icon')
                );
            ?>
        </div>
        <div class="form-actions">
            <?php
            /*echo $this->Form->input('remember', array('div' => false, 'class' => 'grey remember', 'id' => 'remember', 'type' => 'checkbox', 'label' => array('for' => 'remember', 'class' => 'checkbox-inline', 'text' => 'Keep me signed in', 'style' => 'padding-left: 5px;')));*/
            echo $this->Form->button('Login <i class="fa fa-arrow-circle-right"></i>', array('type' => 'submit', 'class' => 'btn btn-bricky pull-right'));
            ?>
        </div>
    </fieldset>
        <?php echo $this->Form->end();?>
</div>
<!-- end: LOGIN BOX -->

              

