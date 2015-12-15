<?php
/**
 * this is a add profession category form page
 * @author Priti Kabra
 */
?>
<!-- start: PAGE HEADER -->
<div class="row">
    <div class="col-sm-12">
        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <i class="clip-file"></i>
                <?php echo $this->Html->link('Professions', array('controller' => 'professions', 'action' => 'index', 'admin' => true));?>
            </li>
            <li class="active">Add Profession Category</li>
        </ol>
        <div class="page-header">
            <h1>Add Profession Category</h1>
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
                <?php echo $this->Form->create('ProfessionCategory', array('url' => array('controller' => 'professions', 'action' => 'addCategory', 'admin' => true), 'class' => 'smart-wizard form-horizontal', 'id' => 'addProfessionCategoryForm')); ?>
                <div id="wizard" class="swMain">
                    <div class="form-group">
                        <?php echo $this->Form->label('', 'Category Name' . $this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'categoryName')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-7">
                            <?php
                            echo $this->Form->input('ProfessionCategory.name', array('type' => 'text', 'label' => false, 'class' => 'form-control', 'id' => 'categoryName', 'placeholder' => 'Enter Category Name', 'maxLength' => 35));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2 col-sm-offset-8">
							<?php echo $this->Html->link($this->Form->button('<i class="fa fa-circle-arrow-left"></i>Cancel', array('type' => 'button', 'class' => 'btn btn-light-grey go-back')), array('controller' => 'professions', 'action' => 'categoryList', 'admin' => true), array('escape' => false)); ?>
                            <?php
                            echo $this->Form->button('Add <i class="fa fa-arrow-circle-right"></i>', array('type' => 'submit', 'class' => 'btn btn-bricky pull-right'));
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


