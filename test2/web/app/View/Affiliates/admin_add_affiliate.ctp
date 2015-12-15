<style>
.isemailCheckbox{float: left;height: auto;margin-top: 2px !important;width: auto; outline:none;}
.isemailCheckbox:focus{outline:none!important}
</style>
<div class="row">
    <div class="col-sm-12">
        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <i class="clip-file"></i>
                <?php echo $this->Html->link('Affiliates', array('controller' => 'affiliates', 'action' => 'index', 'admin' => true));?>
            </li>
            <li class="active">
                Add Affiliate
            </li>
        </ol>
        <div class="page-header">
            <?php echo $this->Html->tag('h1', 'Add Affiliate');?>
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
                echo $this->Form->create('Affiliate', array('url' => array('controller' => 'affiliates', 'action' => 'addAffiliate', 'admin'=>true), 'class' => 'smart-wizard form-horizontal', 'id' => 'addAffiliateFrm'));
                ?>
                <div id="wizard" class="swMain">                   
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Affiliate Name'.$this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'affiliateName')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-6">
                            <?php echo $this->Form->input('name', array('label' => false, 'class' => 'form-control', 'id' => 'affiliateName', 'autocomplete'=>"off",'maxlength'=>false));?>
                        </div>
                    </div>
                   
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Affiliate Email'.$this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'affiliateEmail')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-6">
                            <?php echo $this->Form->input('email', array('type'=>'text','label' => false, 'class' => 'form-control', 'id' => 'affiliateEmail', 'autocomplete'=>"off"));?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Affiliate Link'.$this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'affiliateLink')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-6">
                            <?php echo $this->Form->input('link', array('type'=>'text','label' => false, 'class' => 'form-control', 'id' => 'affiliateLink', 'autocomplete'=>"off", 'value' =>$affiliateLink, 'disabled'=>true));?>
                            <?php echo $this->Form->input('linkhidden', array('type'=>'hidden','label' => false, 'id' => 'affiliateLinkHidden', 'value' =>$affiliateLink));?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label"></label>                            
                        <div class="col-sm-3"> 
                        	<span class="pull-left">Send email&nbsp;&nbsp;</span> 
                        	<?php echo $this->Form->checkbox('isemail', array('label' => false, 'class' => 'form-control isemailCheckbox', 'id' => 'isemail', 'checked' => 'checked'));?>
                        </div>            
                        <div class="col-sm-3">
                            <div class="input text pull-right">
                           <?php echo $this->Form->button('Save <i class="fa fa-arrow-circle-right"></i>', array('type' => 'submit', 'class' => 'btn btn-bricky ladda-button','data-style'=>'slide-left'));?>
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