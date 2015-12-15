<?php
/**
 * this is a add profession form page
 * @author Laxmi Saini 
 */
?>
<!-- start: PAGE HEADER -->
<div class="row">
    <div class="col-sm-12">
        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <i class="clip-file"></i>
                <?php echo $this->Html->link('Adobe Connect', array('controller' => 'adobeConnect', 'action' => 'index', 'admin' => true));?>
            </li>
            <li class="active">Add Hosted Account</li>
        </ol>
        <div class="page-header">
            <h1>Add Hosted Account</h1>
        </div>
        <!-- end: PAGE TITLE & BREADCRUMB -->
    </div>
</div>
<!-- end: PAGE HEADER -->
<div class="row">
    <div class="col-sm-12">
        <div id="responseMessage"></div>
        <!-- start: FORM WIZARD PANEL -->
        <div class="panel panel-default">

            <div class="panel-body">
                <?php echo $this->Form->create('adobeConnect', array('url' => array('controller' => 'adobeConnect', 'action' => 'index', 'admin' => true), 'class' => 'smart-wizard form-horizontal', 'id' => 'addHostedForm')); ?>
                <div id="wizard" class="swMain">
                    <div class="form-group">
                        <?php echo $this->Form->label('', 'Number of account' . $this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'addHost')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-7">
                            <?php
                            echo $this->Form->input('hostedCount', array('type' => 'text', 'label' => false, 'class' => 'form-control', 'id' => 'addHost', 'placeholder' => 'Enter Number of Hosted Account','value'=>$count));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2 col-sm-offset-8">
                            <?php
                            echo $this->Form->button('Save <i class="fa fa-arrow-circle-right"></i>', array('type' => 'submit', 'class' => 'btn btn-bricky pull-right ladda-button','data-style'=>'slide-left'));
                            ?>
                        </div>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
        <!-- end: FORM WIZARD PANEL -->
    </div>
</div>