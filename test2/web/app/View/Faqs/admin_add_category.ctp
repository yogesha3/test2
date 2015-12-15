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
                Add FAQ Category
            </li>
        </ol>
        <div class="page-header">
            <?php echo $this->Html->tag('h1', 'Add FAQ Category');?>
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
                echo $this->Form->create('Faq', array('url' => array('controller' => 'faqs', 'action' => 'addCategory'), 'class' => 'smart-wizard form-horizontal', 'id' => 'addFaqCategoryForm','inputDefaults' => array('error' => false)));
                ?>
                <div id="wizard" class="swMain">
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Category Name<span class="symbol required" for="metaTitle"></span>', array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-7">
                            <?php
                            echo $this->Form->input('Faqcategorie.category_name', array('label' => false, 'class' => 'form-control', 'id' => 'faqcategory','required'=>false,'autocomplete'=>'off'));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"></label>                            
                        <div class="col-sm-2">
                            <div class="input text"></div>
                        </div>
                        <label class="col-sm-2 control-label"></label>                            
                        <div class="col-sm-3">
                            <div class="input text pull-right">
                           <?php echo $this->Html->link($this->Form->button('<i class="fa fa-circle-arrow-left"></i>Cancel', array('type' => 'button', 'class' => 'btn btn-light-grey go-back')), array('controller' => 'faqs', 'action' => 'category', 'admin' => true), array('escape' => false)); ?>
                           <?php echo $this->Form->button('Add <i class="fa fa-arrow-circle-right"></i>', array('type' => 'submit', 'class' => 'btn btn-bricky'));?>
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

