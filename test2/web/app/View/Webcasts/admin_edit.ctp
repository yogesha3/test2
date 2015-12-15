<?php

/**
 * Edit webcast
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
                <?php echo $this->Html->link('Webcasts', array('controller' => 'webcasts', 'action' => 'index', 'admin' => true)); ?>
            </li>
            <li class="active"><?php echo __("Edit Webcast");?></li>
        </ol>
        <div class="page-header">
            <?php echo $this->Html->tag('h1','Edit Webcast'); ?>
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
                <?php 
                    echo $this->Form->create('Webcast', array('url' => array('controller' => 'webcasts', 'action' => 'edit', 'admin' => true), 'class' => 'smart-wizard form-horizontal', 'id' => 'editWebcastsForm','type' => 'file','inputDefaults' => array('error' => false)));
                    echo $this->Form->hidden('id');
                ?>

                <div id="wizard" class="swMain">
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Webcast Link', array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-7">
                            <?php echo $this->Form->input('Webcast.link', array('label' => false,'type' =>'text', 'class' => 'form-control', 'id' =>'link','autocomplete'=>'off','disabled' => true, 'readonly' => true));?>
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
                        <label class="col-sm-3 control-label"></label>                            
                        <div class="col-sm-2">
                            <div class="input text"></div>
                        </div>
                        <label class="col-sm-1 control-label"></label>                            
                        <div class="col-sm-4">
                            <div class="input text pull-right">
                           <?php echo $this->Html->link($this->Form->button('<i class="fa fa-circle-arrow-left"></i>Cancel', array('type' => 'button', 'class' => 'btn btn-light-grey go-back')), array('controller' => 'webcasts', 'action' => 'index', 'admin' => true), array('escape' => false)); ?>
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
<script type="text/javascript">
    $(document).ready(function () {
        Ladda.bind('.ladda-button', {
            timeout: 2000
        });
        // Bind progress buttons and simulate loading progress
        Ladda.bind('.progress-demo button', {
            callback: function (instance) {
                var progress = 0;
                var interval = setInterval(function () {
                    progress = Math.min(progress + Math.random() * 0.1, 1);
                    instance.setProgress(progress);
                    if (progress === 1) {
                        instance.stop();
                        clearInterval(interval);
                    }
                }, 200);
            }
        });        
    });
</script>