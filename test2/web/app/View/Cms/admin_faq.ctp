<?php
/**
 * FAQ content manager page
 */
echo $this->Html->script('../assets/plugins/ckeditor/ckeditor');
?>
<!-- start: PAGE HEADER -->
<div class="row">
    <div class="col-sm-12">
        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <i class="clip-file"></i>
                <?php echo $this->Html->link('Pages', array('controller' => 'cms', 'action' => 'about', 'admin' => true));?>
            </li>
            <li class="active">
                FAQ
            </li>
        </ol>
        <div class="page-header">
            <?php echo $this->Html->tag('h1', 'FAQ');?>
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
                echo $this->Form->create('pages', array('url' => array('controller' => 'cms', 'action' => 'faq', 'admin'=>true), 'class' => 'smart-wizard form-horizontal', 'id' => 'editFaqForm'));
                echo $this->Form->input('Page.id', array('type' => 'hidden'));
                ?>
                <div id="wizard" class="swMain">
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Meta Title'.$this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'metaTitle')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-7">
                            <?php echo $this->Form->input('Page.meta_title', array('label' => false, 'class' => 'form-control', 'id' => 'metaTitle', 'placeholder'=>'Meta Title', 'maxlength'=>60));?>
                        </div>
                    </div>
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Meta Keywords'.$this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'metaKeywords')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-7">
                            <?php echo $this->Form->input('Page.meta_keywords', array('label' => false, 'class' => 'form-control', 'id' => 'metaKeywords', 'placeholder'=>'Meta Keywords'));?>
                        </div>
                    </div>
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Meta Description'.$this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'metaDesc')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-7">
                            <?php echo $this->Form->input('Page.meta_desc', array('label' => false, 'class' => 'form-control', 'id' => 'metaDesc','placeholder'=>'Meta Description', 'maxlength'=>160));?>
                        </div>
                    </div>

                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Page Content ', array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-7">
                            <?php echo $this->Form->textarea('Page.page_content', array('rows' => 5, 'label' => false, 'class' => 'form-control', 'id' => 'pageContent', 'placeholder' => 'Page Content','style'=>"resize: none")); ?>
                        </div>
                    </div>
                   
                    <div class="form-group">
                        <div class="col-sm-2 col-sm-offset-8">
                            <?php echo $this->Form->button('Update <i class="fa fa-arrow-circle-right"></i>', array('type' => 'submit', 'class' => 'btn btn-bricky pull-right')); ?>
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
    $(document).ready(function() {
     CKEDITOR.replace('pageContent', {
            filebrowserUploadUrl : '<?php echo $this->webroot?>app/webroot/ckeditorupload/upload.php',
            filebrowserWindowWidth  : 800,
            filebrowserWindowHeight : 500
        });
        CKEDITOR.disableAutoInline = true;
   });

</script>
