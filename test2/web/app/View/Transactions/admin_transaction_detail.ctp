<?php

/** 
 * View Transaction Detail
 * @author Priti Kabra
 */
?>
<div class="row">
    <div class="col-sm-12">
        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <i class="clip-file"></i>
                <?php echo $this->Html->link('Transactions', array('controller' => 'transactions', 'action' => 'index', 'admin' => true));?>
            </li>
            <li class="active"> View Transaction Detail</li>
        </ol>
        <div class="page-header">
            <?php echo $this->Html->tag('h1', ucfirst($transactionData['BusinessOwner']['fname']." ".$transactionData['BusinessOwner']['lname']));?>
        </div>
        <!-- end: PAGE TITLE & BREADCRUMB -->
    </div>
</div>
<style >.text_left{ text-align: left !important;}</style>
<!-- end: PAGE HEADER -->
<div class="row">
    <div class="col-sm-12">
        <!-- start: FORM WIZARD PANEL -->
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="smart-wizard form-horizontal">
                    <fieldset>
                        <legend>Account Information</legend>
                    </fieldset>
                    <div id="wizard" class="swMain">
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Transaction ID ' , array('class' => 'col-sm-3 control-label col-sm-offset-2')); ?>
                            <div class="col-sm-7">
                           <?php echo $this->Form->label('', $transactionData['Transaction']['transaction_id'], array('class' => 'col-sm-3 control-label text_left col-sm-offset-1')); ?>
                            </div>
                        </div>
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Transaction Status ' , array('class' => 'col-sm-3 control-label col-sm-offset-2')); ?>
                            <div class="col-sm-7">
                           <?php echo $this->Form->label('', ($transactionData['Transaction']['status'] == 'settled') ? 'Success' : 'Failed', array('class' => 'col-sm-3 control-label text_left col-sm-offset-1')); ?>
                            </div>
                        </div>
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'User Name ' , array('class' => 'col-sm-3 control-label col-sm-offset-2')); ?>
                            <div class="col-sm-7">
                           <?php echo $this->Form->label('',ucfirst($transactionData['BusinessOwner']['fname'])." ".ucfirst($transactionData['BusinessOwner']['lname']), array('class' => 'col-sm-3 control-label text_left col-sm-offset-1')); ?>
                            </div>
                        </div>
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'User Email ' , array('class' => 'col-sm-3 control-label col-sm-offset-2')); ?>
                            <div class="col-sm-7">
                           <?php echo $this->Form->label('', $transactionData['BusinessOwner']['email'], array('class' => 'col-sm-3 control-label text_left col-sm-offset-1')); ?>
                            </div>
                        </div>
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Membership Plan ' , array('class' => 'col-sm-3 control-label col-sm-offset-2')); ?>
                            <div class="col-sm-7">
                           <?php echo $this->Form->label('', !empty($transactionData['Group']['group_type']) ? ucfirst($transactionData['Group']['group_type']) : '-', array('class' => 'col-sm-3 control-label text_left col-sm-offset-1')); ?>
                            </div>
                        </div>
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Payment Type ' , array('class' => 'col-sm-3 control-label col-sm-offset-2')); ?>
                            <div class="col-sm-7">
                           <?php echo $this->Form->label('', Configure::read('PAYMENT_TYPE'), array('class' => 'col-sm-3 control-label text_left col-sm-offset-1')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="smart-wizard form-horizontal">
                    <fieldset>
                        <legend>Billing Information</legend>
                    </fieldset>
                    <div id="wizard" class="swMain">
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'User Name ' , array('class' => 'col-sm-3 control-label col-sm-offset-2')); ?>
                            <div class="col-sm-7">
                           <?php echo $this->Form->label('', ucfirst($transactionData['BusinessOwner']['fname'])." ".ucfirst($transactionData['BusinessOwner']['lname']), array('class' => 'col-sm-3 control-label text_left col-sm-offset-1')); ?>
                            </div>
                        </div>
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'State ' , array('class' => 'col-sm-3 control-label col-sm-offset-2')); ?>
                            <div class="col-sm-7">
                           <?php echo $this->Form->label('', $transactionData['State']['state_subdivision_name'], array('class' => 'col-sm-3 control-label text_left col-sm-offset-1')); ?>
                            </div>
                        </div>
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Country ' , array('class' => 'col-sm-3 control-label col-sm-offset-2')); ?>
                            <div class="col-sm-7">
                           <?php echo $this->Form->label('', $transactionData['Country']['country_name'], array('class' => 'col-sm-3 control-label text_left col-sm-offset-1')); ?>
                            </div>
                        </div>
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'ZIP ' , array('class' => 'col-sm-3 control-label col-sm-offset-2')); ?>
                            <div class="col-sm-7">
                           <?php echo $this->Form->label('', $transactionData['BusinessOwner']['zipcode'], array('class' => 'col-sm-3 control-label text_left col-sm-offset-1')); ?>
                            </div>
                        </div>
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Address ' , array('class' => 'col-sm-3 control-label col-sm-offset-2')); ?>
                            <div class="col-sm-7">
                           <?php echo $this->Form->label('', !empty($transactionData['BusinessOwner']['address']) ? ucfirst($transactionData['BusinessOwner']['address']) : '-' , array('class' => 'col-sm-3 control-label text_left col-sm-offset-1')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="smart-wizard form-horizontal">
                    <fieldset>
                        <legend>Payment Details</legend>
                    </fieldset>
                    <div id="wizard" class="swMain">
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Credit Card Number ' , array('class' => 'col-sm-3 control-label col-sm-offset-2')); ?>
                            <div class="col-sm-7">
                           <?php echo $this->Form->label('', "XXXX-XXXX-XXXX-".$this->Encryption->decode($transactionData['Transaction']['credit_card_number']), array('class' => 'col-sm-5 control-label text_left col-sm-offset-1')); ?>
                            </div>
                        </div>
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Status ' , array('class' => 'col-sm-3 control-label col-sm-offset-2')); ?>
                            <div class="col-sm-7">
                           <?php echo $this->Form->label('', ($transactionData['Transaction']['status'] == 'settled') ? 'Success' : 'Failed', array('class' => 'col-sm-3 control-label text_left col-sm-offset-1')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="smart-wizard form-horizontal">
                    <fieldset>
                        <legend>Invoice</legend>
                    </fieldset>
                    <div id="wizard" class="swMain">
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Membership Plan ' , array('class' => 'col-sm-3 control-label col-sm-offset-2')); ?>
                            <div class="col-sm-7">
                           <?php echo $this->Form->label('', ucfirst($transactionData['Transaction']['group_type']), array('class' => 'col-sm-3 control-label text_left col-sm-offset-1')); ?>
                            </div>
                        </div>
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Membership Plan Fees ' , array('class' => 'col-sm-3 control-label col-sm-offset-2')); ?>
                            <div class="col-sm-7">
                           <?php echo $this->Form->label('', '$'.Configure::read('PLANPRICE'), array('class' => 'col-sm-3 control-label text_left col-sm-offset-1')); ?>
                            </div>
                        </div>
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Discount Applied ' , array('class' => 'col-sm-3 control-label col-sm-offset-2')); ?>
                            <div class="col-sm-7">
                           <?php
                                $discount = Configure::read('PLANPRICE') - $transactionData['Transaction']['amount_paid'];
                                echo $this->Form->label('', '$'.number_format($discount,2), array('class' => 'col-sm-3 control-label text_left col-sm-offset-1'));
                            ?>
                            </div>
                        </div>
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Final Fees ' , array('class' => 'col-sm-3 control-label col-sm-offset-2')); ?>
                            <div class="col-sm-7">
                           <?php echo $this->Form->label('', '$'.number_format($transactionData['Transaction']['amount_paid'],2), array('class' => 'col-sm-3 control-label text_left col-sm-offset-1')); ?>
                            </div>
                            <div class="col-sm-2 col-sm-offset-8">                            
							<?php echo $this->Html->link('<button class="btn btn-light-grey go-back pull-right" type="button">Back</button>',array('controller'=>'transactions','action'=>'index','admin'=>true),array('escape'=>false));?>
                            </div>							       
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- end: FORM WIZARD PANEL -->
    </div>
</div>
