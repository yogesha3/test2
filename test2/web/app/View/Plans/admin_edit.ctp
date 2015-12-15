<?php

/**
 * Edit plan view page
 */
?>
<!-- start: PAGE HEADER -->
<div class="row">
    <div class="col-sm-12">
        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <i class="clip-file"></i>
                <?php echo $this->Html->link('Plans', array('controller' => 'plans', 'action' => 'index', 'admin' => true)); ?>
            </li>
            <li class="active">
                Edit Plan
            </li>
        </ol>
        <div class="page-header">
            <?php echo $this->Html->tag('h1', 'Edit Plan');?>
        </div>
        <!-- end: PAGE TITLE & BREADCRUMB -->
    </div>
</div>

<!-- end: PAGE HEADER -->
<div class="row">
    <div class="col-sm-12">
        <!-- start: FORM WIZARD PANEL -->
        <div class="panel panel-default">

            <div class="panel-body">
                <?php
                echo $this->Form->create('Plan', array('url' => array('controller' => 'plans', 'action' => 'edit', $id), 'class' => 'smart-wizard form-horizontal', 'id' => 'editPlanForm'));
                echo $this->Form->input('Plan.id', array('type' => 'hidden', 'value' => $id, 'id' => 'planId'));
                ?>
                <div id="wizard" class="swMain">
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Plan Title ', array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-7">
                            <?php
                            echo $this->Form->input('Plan.plan_name', array('label' => false, 'class' => 'form-control', 'id' => 'planTitle', 'disabled'=>true, 'readonly' => true));
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Plan Content ', array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-7">
                            <?php echo $this->Form->textarea('Plan.plan_details', array('rows' => 5, 'label' => false, 'class' => 'form-control', 'id' => 'planContent', 'placeholder' => 'Plan Content','style'=>"resize: none")); ?>
                        </div>
                    </div>
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Membership Fee ($)' . $this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'membership_price')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('Plan.membership_price', array('type' => 'text', 'label' => false, 'class' => 'form-control', 'id' => 'membershipPrice', 'placeholder' => 'Membership Fee','maxlength'=>7,'autocomplete'=>'off')); ?>
                        </div>
                    </div>
                    <?php if ($id != $this->Encryption->encode(3)) { ?>
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'No. of Discounted Members ' . $this->Html->tag('span', '', array('for' => 'membership_price')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-2">
                                <?php echo $this->Form->input('Plan.discounted_members', array('label' => false, 'class' => 'form-control', 'id' => 'discountedMember', 'type' => 'text', 'maxlength' => 2,'autocomplete'=>'off')); ?>
                        </div>
                            <?php echo $this->Form->label('', 'Discounted Price ($)' . $this->Html->tag('span', '', array('for' => 'membership_price')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-2">
                                <?php echo $this->Form->input('Plan.discounted_amount', array('label' => false, 'class' => 'form-control', 'id' => 'discountedPrice', 'type' => 'text' ,'maxlength'=>7,'autocomplete'=>'off')); ?>
                        </div>
                    </div>
                    <?php } ?>

                    <div class="form-group">
                        <label class="col-sm-3 control-label"></label>                            
                        <div class="col-sm-2">
                            <div class="input text"></div>
                        </div>
                        <label class="col-sm-1 control-label"></label>                            
                        <div class="col-sm-4">
                            <div class="input text pull-right">
                           <?php echo $this->Html->link($this->Form->button('<i class="fa fa-circle-arrow-left"></i>Cancel', array('type' => 'button', 'class' => 'btn btn-light-grey go-back')), array('controller' => 'plans', 'action' => 'index', 'admin' => true), array('escape' => false)); ?>
                           <?php echo $this->Form->button('Update <i class="fa fa-arrow-circle-right"></i>', array('type' => 'submit', 'class' => 'btn btn-bricky'));?>
                            </div>
                        </div>
                    </div>

                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
        <!-- end: FORM WIZARD PANEL -->
    </div>
</div>