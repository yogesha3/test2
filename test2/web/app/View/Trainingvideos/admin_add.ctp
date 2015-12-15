<!-- start: PAGE HEADER -->
<div class="row">
    <div class="col-sm-12">
        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <i class="clip-file"></i>
                <?php
                echo $this->Html->link('Training Videos', array('controller' => 'trainingvideos', 'action' => 'index', 'admin' => true));
                ?>
            </li>
            <li class="active">Add Video</li>
        </ol>
        <div class="page-header">
            <?php echo $this->Html->tag('h1','Add Video'); ?>
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
                <?php echo $this->Form->create('Trainingvideo', array('url' => array('controller' => 'trainingvideos', 'action' => 'add', 'admin' => true), 'class' => 'smart-wizard form-horizontal', 'id' => 'trainingvideosForm','type' => 'file','inputDefaults' => array('error' => false))); ?>
                <div id="wizard" class="swMain">
                    <div class="form-group">
                        <?php echo $this->Form->label('', 'Upload a Video File' . $this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'Trainingvideo')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-7">                            
                            <div class="col-sm-13" id='csvDiv'>								
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="input-group">
                                        <div class="form-control uneditable-input">
                                            <i class="fa fa-file fileupload-exists"></i>
                                            <span class="fileupload-preview"></span>
                                        </div>
                                        <div class="input-group-btn">
                                            <div class="btn btn-light-grey btn-file">
                                                <span class=""><i class="fa fa-folder-open-o"></i> Select file</span>
                                                <!-- <input type="file" class="file-input" name="data[trainingvideos][video_name]" id="video_name"> -->
                                                <?php 
                                                echo $this->Form->input('video_name', array('type' => 'file','id'=>'video_name','class'=>"file-input",'label' => false,'div'=>false,'required'=>false)); 
                                                ?>
                                            </div>

                                        </div>

                                    </div>
                                    <span class="help-block pull-right"><i class="fa fa-info-circle"></i> Only MP4 video format of maximum 10 MB is allowed</span>
                                </div>
                            </div>
                            <label class="error" for='deshdash' style="display: none">Please select a valid video file</label>
                            <span class="error_msg" style="color: red"><?php echo $error = isset($errormsg) ? $errormsg : ""; ?></span>
                        </div>
                    </div>                    
                    <div class="form-group">
                        <div class="col-sm-2 col-sm-offset-8">
                            <?php
                            echo $this->Form->button('Save <i class="fa fa-arrow-circle-right"></i>', array('type' => 'submit', 'class' => 'btn btn-bricky pull-right'));
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
<script type="text/javascript">
	$(document).ready(function(){
            $('#video_name').change(function(){
               //$('.uneditable-input').removeClass('error1'); 
            });
	});
</script>