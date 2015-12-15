<?php
/**
 * Import profession csv 
 */
?>
<!-- start: PAGE HEADER -->
<div class="row">
    <div class="col-sm-12">
        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <i class="clip-file"></i>
                <?php
                echo $this->Html->link('Professions', array('controller' => 'professions', 'action' => 'index', 'admin' => true));
                ?>
            </li>
            <li class="active">Import Profession</li>
            <li class="search-box">
            </li>
        </ol>
        <div class="page-header">
            <?php echo $this->Html->tag('h1','Import Profession'); ?>
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
                <?php echo $this->Form->create('Profession', array('url' => array('controller' => 'professions', 'action' => 'importProfession', 'admin' => true), 'class' => 'smart-wizard form-horizontal', 'id' => 'importProfessionForm','type' => 'file')); ?>
                <div id="wizard" class="swMain">
                    <div class="col-sm-3 control-label"></div>
                    <div class="col-sm-7" style="padding-left: 6px;">
                       <p style="color: #8c8c8c;">(Please add "Profession-Name" in the CSV as column header)</p>
                    </div>
                   
                    <div class="form-group">
                        <?php echo $this->Form->label('', 'Upload a CSV File' . $this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'professionName')), array('class' => 'col-sm-3 control-label')); ?>
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
                                                <input type="file" class="file-input" name="data[Profession][csv]" id="profession_csv">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <label class="error" for='deshdash' style="display: none">Please select a CSV file</label>
                            <span class="error_msg" style="color: red"><?php echo $error = isset($errormsg) ? $errormsg : ""; ?></span>
                        </div>
                    </div>                    
                    <div class="form-group">
                        <div class="col-sm-2 col-sm-offset-8">
                            <?php
                            echo $this->Form->button('Import <i class="fa fa-arrow-circle-right"></i>', array('type' => 'submit', 'class' => 'btn btn-bricky pull-right'));
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
            $('#profession_csv').change(function(){
               $('.uneditable-input').removeClass('error1'); 
            });
	});

</script>




