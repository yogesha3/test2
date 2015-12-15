<?php

/**
 * Edit Advertisement
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
            <li class="active"><?php echo __("Edit Advertisement");?></li>
        </ol>
        <div class="page-header">
            <?php echo $this->Html->tag('h1','Edit Advertisement'); ?>
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
                <?php echo $this->Form->create('Advertisement', array('url' => array('controller' => 'advertisements', 'action' => 'edit', 'admin' => true), 'class' => 'smart-wizard form-horizontal', 'id' => 'editAdvertisementsForm','type' => 'file','inputDefaults' => array('errorMessage' => true)));
                       echo $this->Form->input('id',array('type' => 'hidden', 'value' =>$id));
                ?>
                <div id="wizard" class="swMain">
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Select Profession'.$this->Html->tag('span', '', array('class' => 'symbol required')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-7">
                            <?php echo $this->Form->select('Advertisement.profession_id', $professionList, array( 'label' => false, 'class' => 'form-control search-select', 'id' => 'profession', 'empty' => 'Select Profession', 'value'=> $advertisement['Profession']['id'])); ?>
                        </div>
                    </div>
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Advertisement Position'.$this->Html->tag('span', '', array('class' => 'symbol required')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-7">
                            <?php echo $this->Form->select('Advertisement.position', array('0' => 'Bottom', '1' => 'Right'), array('label' => false, 'class' => 'form-control', 'id' =>'position', 'empty' => 'Select Position', 'value' => $advertisement['Advertisement']['position']));?>
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
                            <?php
                            $imageName='';
                            if(!empty($advertisement['Advertisement']['ad_image'])) {
                                $imageName= $advertisement['Advertisement']['ad_image'];
                                $imagePath= Configure::read('SITE_URL').'/img/uploads/ads/'.$advertisement['Advertisement']['ad_image'];
                            } else {
                                $imagePath='';
                            }
                             echo $this->Form->input('ad_image',array('type'=>'hidden', 'value' => $imageName,'id' => 'adImage', 'name' => ''));
                            ?>
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="user-edit-image-buttons" id="uploadButton">
                                    <div class="btn btn-light-grey btn-file">
                                        <span class="">
                                            <i class="fa fa-picture"></i> Select image
                                        </span>
                                        <input type="file" name="data[Advertisement][upload]" class="file-input">
                                    </div>
                                </div><br><br>
                                <div class="fileupload-new thumbnail col-sm-12" style="width: 630px; height: 150px;">
                                    <img src="<?php echo $imagePath;?>" alt="Ad image preview">
                                </div> 
                                <div class="fileupload-preview fileupload-exists thumbnail col-sm-12" style="max-width: 630px;max-height: 150px; line-height: 20px;"></div>
                            </div>
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
                                <?php echo $this->Html->link($this->Form->button('<i class="fa fa-circle-arrow-left"></i>Cancel', array('type' => 'button', 'class' => 'btn btn-light-grey go-back')), array('controller' => 'advertisements', 'action' => 'index', 'admin' => true), array('escape' => false)); ?>
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