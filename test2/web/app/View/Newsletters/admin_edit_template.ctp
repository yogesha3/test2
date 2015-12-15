<?php echo $this->Html->script('../assets/plugins/ckeditor/ckeditor');?>
<div class="row">
    <div class="col-sm-12">
        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <i class="clip-file"></i>
                <?php echo $this->Html->link('Newsletter', array('controller' => 'newsletters', 'action' => 'index', 'admin' => true));?>
            </li>
            <li class="active">
                Edit Newsletter Template
            </li>
        </ol>
        <div class="page-header">
            <?php echo $this->Html->tag('h1', 'Edit Newsletter Template');?>
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
                echo $this->Form->create('newsletters', array('url' => array('controller' => 'newsletters', 'action' => 'editTemplate', 'admin'=>true), 'class' => 'smart-wizard form-horizontal', 'id' => 'editNewsletterForm','inputDefaults' => array('errorMessage' => true)));
                ?>
                <div id="wizard" class="swMain">
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Template Name'.$this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'templateName')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-8">
                            <?php echo $this->Form->input('Newsletter.template_name', array('label' => false, 'class' => 'form-control', 'id' => 'template_name', 'placeholder'=>'Template Name','autocomplete'=>'off', 'maxlength'=>25,'required'=>false));?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Subject'.$this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'templateSubject')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-8">
                            <?php echo $this->Form->input('Newsletter.subject', array('label' => false, 'class' => 'form-control', 'id' => 'template_subject', 'placeholder'=>'Subject','autocomplete'=>'off', 'maxlength'=>60,'required'=>false));?>
                        </div>
                    </div>
                   
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Template Content'.$this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'templateContent')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-8">
                            <?php echo $this->Form->input('Newsletter.content', array('label' => false, 'class' => 'form-control required', 'id' => 'template_content','autocomplete'=>'off', 'required'=>false));?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                    <?php echo $this->Form->label('', '&nbsp;', array('class' => 'col-sm-3')); ?>
                        <div class="col-sm-8">
                        	<div class="input text pull-right">
		                        <?php echo $this->Html->link($this->Form->button('<i class="fa fa-circle-arrow-left"></i>Cancel', array('type' => 'button', 'class' => 'btn btn-light-grey go-back')), array('controller' => 'newsletters', 'action' => 'index', 'admin' => true), array('escape' => false)); ?>&nbsp;
		                        <?php echo $this->Form->button('<i class="fa fa-circle-arrow-left"></i>Preview', array('type' => 'button', 'id'=>'preview_template' ,'class' => 'btn btn-light-grey go-back')); ?>&nbsp;
	                            <?php echo $this->Form->button('Update <i class="fa fa-arrow-circle-right"></i>', array('type' => 'submit', 'class' => 'btn btn-bricky pull-right')); ?>
	                        </div>    
                        </div>
                    </div>
                </div>
                <?php echo $this->Form->hidden('Newsletter.id')?>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
        <!-- end: FORM WIZARD PANEL -->
    </div>
</div>
<script type="text/javascript">                
$(document).ready(function() {
    CKEDITOR.replace('template_content', {  
    	filebrowserUploadUrl : '<?php echo $this->webroot?>app/webroot/ckeditorupload/upload.php',          
        filebrowserWindowWidth  : 800,
        filebrowserWindowHeight : 500
    });
    CKEDITOR.disableAutoInline = true;      
});
$("#preview_template").click(function(){
	template_content = CKEDITOR.instances['template_content'].getData();
	newwindow=window.open();
	newdocument=newwindow.document;
	newdocument.write(template_content);
	newdocument.close();
});    

$(function(){
	CKEDITOR.on('instanceReady', function () {
	    $.each(CKEDITOR.instances, function (instance) {
	        CKEDITOR.instances[instance].document.on("keyup", CK_jQ);
	        CKEDITOR.instances[instance].document.on("paste", CK_jQ);
	        CKEDITOR.instances[instance].document.on("keypress", CK_jQ);
	        CKEDITOR.instances[instance].document.on("blur", CK_jQ);
	        CKEDITOR.instances[instance].document.on("change", CK_jQ);
	    });
	});
	function CK_jQ() {
	    for (instance in CKEDITOR.instances) {
	        CKEDITOR.instances[instance].updateElement();
	    }
	}
});
</script>
