<?php

/**
 * forgot password Form page
 */
?>
<!-- start: FORGOT BOX -->
<div class="box-forgot" style="display: block">
    <?php 
        echo $this->Html->tag('h3','Forget Password?');
        echo $this->Html->tag('p','Enter your e-mail address below to reset your password.');
       
        echo $this->Form->create('User', array('action' => 'forgotPassword'));
    ?>
    <div class="errorHandler alert alert-danger no-display">
        <i class="fa fa-remove-sign"></i> You have some form errors. Please check below.
    </div>
    <fieldset>
        <div class="form-group">
            <?php
                echo $this->Html->tag('span', $this->Form->input('email', array('div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => 'E-mail', 'type' => 'email')) . '<i class="fa fa-envelope"></i> </span>', array('class' => 'input-icon'));
            ?>

        </div>
        <div class="form-actions">
            <?php
                echo $this->Html->link($this->Form->button('<i class="fa fa-circle-arrow-left"></i> Back', array('type' => 'button', 'class' => 'btn btn-light-grey go-back')),array('controller'=>'users','action'=>'login','admin'=>true), array('escape'=>false));
                echo $this->Form->button('Submit <i class="fa fa-arrow-circle-right"></i>', array('type' => 'submit', 'class' => 'btn btn-bricky pull-right'));
            ?>
        </div>
    </fieldset>

    <?php
    echo $this->Form->end();
    ?>
</div>
<!-- end: FORGOT BOX -->
<script type="text/javascript">
    $(document).ready(function () {

        

    });

</script>

