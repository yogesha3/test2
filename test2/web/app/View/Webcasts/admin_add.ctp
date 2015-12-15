<?php
/**
 * Add webcast
 * @author Gaurav Bhandari
 */
?>
<!-- start: PAGE HEADER -->
<div class="row">
    <div class="col-sm-12">
        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <i class="clip-file"></i>
                <?php echo $this->Html->link('Webcasts', array('controller' => 'webcasts', 'action' => 'index', 'admin' => true)); ?>
            </li>
            <li class="active"><?php echo __("Add Webcast");?></li>
        </ol>
        <div class="page-header">
            <?php echo $this->Html->tag('h1','Add Webcast'); ?>
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
                <?php echo $this->Form->create('Webcast', array('url' => array('controller' => 'webcasts', 'action' => 'add', 'admin' => true), 'class' => 'smart-wizard form-horizontal', 'id' => 'addWebcastsForm','type' => 'file','inputDefaults' => array('error' => false))); ?>
                <div id="wizard" class="swMain">
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Webcast Link'.$this->Html->tag('span', '', array('class' => 'symbol required')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-7">

                            <?php echo $this->Form->input('Webcast.link', array('label' => false, 'class' => 'form-control', 'id' =>'link','autocomplete'=>'off','placeholder' => 'Webcast Link', 'required'=>false));?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Webcast Title'.$this->Html->tag('span', '', array('class' => 'symbol required')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-7">
                            <?php echo $this->Form->input('Webcast.title', array('label' => false, 'class' => 'form-control', 'id' => 'title', 'placeholder'=>'Webcast Title','autocomplete'=>'off', 'maxlength'=>70,'required'=>false));?>
                        </div>
                    </div>                   

                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Webcast Description', array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-7">
                            <?php echo $this->Form->textarea('Webcast.description', array('label' => false,'placeholder' => 'Webcast Description', 'class' => 'form-control', 'id' => 'description','autocomplete'=>'off', 'maxlength'=>250,'required'=>false,'rows'=>'5' ,'style'=>'resize:none'));?>
                        </div>
                    </div>  

                     


                    <div class="form-group">
                        <div class="col-sm-2 col-sm-offset-8">
                            <?php
                            echo $this->Form->button('Save <i class="fa fa-arrow-circle-right"></i>', array('type' => 'submit', 'class' => 'btn btn-bricky ladda-button pull-right','data-style'=>'slide-left'));
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
