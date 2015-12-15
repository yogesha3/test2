<?php

/**
 * Add Advertisement
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
                <?php echo $this->Html->link('Advertisements', array('controller' => 'advertisements', 'action' => 'index', 'admin' => true)); ?>
            </li>
            <li class="active"><?php echo __("Add Advertisement");?></li>
        </ol>
        <div class="page-header">
            <?php echo $this->Html->tag('h1','Add Advertisement'); ?>
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
                <?php echo $this->Form->create('Advertisement', array('url' => array('controller' => 'advertisements', 'action' => 'add', 'admin' => true), 'class' => 'smart-wizard form-horizontal', 'id' => 'addAdvertisementsForm','type' => 'file','inputDefaults' => array('errorMessage' => true))); ?>
                <div id="wizard" class="swMain">
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Select Profession'.$this->Html->tag('span', '', array('class' => 'symbol required')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-7">
                            <?php echo $this->Form->select('Advertisement.profession_id', $professionList, array('label' => false, 'class' => 'form-control search-select ', 'id' => 'profession', 'empty' => 'Select Profession'));?>
                        </div>
                    </div>
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Advertisement Position'.$this->Html->tag('span', '', array('class' => 'symbol required')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-7">
                            <?php echo $this->Form->select('Advertisement.position', array(0 => 'Bottom', 1 => 'Right'), array('label' => false, 'class' => 'form-control', 'id' => 'position','empty' => 'Select Position'));?>
                        </div>
                    </div>
                                        
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Advertisement Title'.$this->Html->tag('span', '', array('class' => 'symbol required')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-7">
                            <?php echo $this->Form->input('Advertisement.title', array('label' => false, 'class' => 'form-control', 'id' => 'title', 'placeholder'=>'Advertisement Title', 'maxlength'=>25));?>
                        </div>
                    </div> 
                    
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Target URL'.$this->Html->tag('span', '', array('class' => 'symbol required')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-7">
                            <?php echo $this->Form->input('Advertisement.target_url', array('label' => false, 'class' => 'form-control', 'id' => 'targetUrl', 'placeholder'=>'Advertisement Target URL'));?>
                        </div>
                    </div> 
                     
                    <div class="form-group">
                     <?php echo $this->Form->label('', 'Advertisement Image'.$this->Html->tag('span', '', array('class' => 'symbol required')), array('class' => 'col-sm-3 control-label')); ?>
                        
                        <div class="col-sm-7">   
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="user-edit-image-buttons" id="uploadButton">
                                            <div class="btn btn-light-grey btn-file">
                                                <span class="">
                                                    <i class="fa fa-picture"></i> Select image
                                                </span>                                                   
                                                <input type="file" name="data[Advertisement][ad_image]" class="file-input">
                                            </div>
                                    </div> <br><br>
                                    <div class="fileupload-new thumbnail col-sm-12" style="height: 150px;"></div> 
                                    <div class="fileupload-preview fileupload-exists thumbnail col-sm-12" style="max-height: 150px; line-height: 20px;"></div>
                                    
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2 col-sm-offset-8">
                            <?php echo $this->Form->button('Save <i class="fa fa-arrow-circle-right"></i>', array('type' => 'submit', 'class' => 'btn btn-bricky ladda-button pull-right','data-style'=>'slide-left')); ?>
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
    $(document).ready(function(){
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