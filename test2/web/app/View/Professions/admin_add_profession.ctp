<?php
/**
 * this is a add profession form page
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
                <?php echo $this->Html->link('Professions', array('controller' => 'professions', 'action' => 'index', 'admin' => true));?>
            </li>
            <li class="active">Add Profession</li>
        </ol>
        <div class="page-header">
            <h1>Add Profession</h1>
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
                <?php echo $this->Form->create('Profession', array('url' => array('controller' => 'professions', 'action' => 'addProfession', 'admin' => true), 'class' => 'smart-wizard form-horizontal', 'id' => 'addProfessionForm')); ?>
                <div id="wizard" class="swMain">
                    <div class="form-group">
                        <?php echo $this->Form->label('', 'Select Category' . $this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'category')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-7">
                            <?php
                            foreach ($categoryList as $category) {
                                $categoryArr[$category['ProfessionCategory']['id']] = ucfirst($category['ProfessionCategory']['name']);
                            }
                            echo $this->Form->select('Profession.category_id', $categoryArr,
                                array(
                                        'label' => false, 
                                        'class' => 'form-control', 
                                        'id' => 'category',
                                        'required' => false,
                                        'empty' => 'Select Category'));

                           
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $this->Form->label('', 'Profession Name' . $this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'professionName')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-7">
                            <?php
                            echo $this->Form->input('Profession.profession_name', array('type' => 'text', 'label' => false, 'class' => 'form-control', 'id' => 'professionName', 'placeholder' => 'Enter Profession Name'));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2 col-sm-offset-8">
							<?php echo $this->Html->link($this->Form->button('<i class="fa fa-circle-arrow-left"></i>Cancel', array('type' => 'button', 'class' => 'btn btn-light-grey go-back')), array('controller' => 'professions', 'action' => 'index', 'admin' => true), array('escape' => false)); ?>
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


