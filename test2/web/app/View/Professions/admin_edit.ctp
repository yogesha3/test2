<?php
/**
 * edit Profession page
 * @author Laxmi Saini
 * 
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
            <li class="active">
               Edit Profession
            </li>
        </ol>
        <div class="page-header">
            <?php echo $this->Html->tag('h1','Edit Profession');?>
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
                echo $this->Form->create('Profession', array('url' => array('controller' => 'professions', 'action' => 'edit', 'admin' => true, $id), 'class' => 'smart-wizard form-horizontal', 'id' => 'editProfessionForm'));
                echo $this->Form->input('Profession.id', array('type' => 'hidden', 'value' => $id, 'id' => 'professionId'));
                ?>
                <div id="wizard" class="swMain">
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Category' . $this->Html->tag('span', '', array('class' => 'symbol', 'for' => 'categoryName')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-7">
                            <?php
                            echo $this->Form->input('ProfessionCategory.name', array('type' => 'text', 'label' => false, 'class' => 'form-control', 'id' => 'categoryName', 'placeholder' => 'Enter Category Name', 'readonly' => true));
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
                        <label class="col-sm-3 control-label"></label>                            
                        <div class="col-sm-2">
                            <div class="input text"></div>
                        </div>
                        <label class="col-sm-1 control-label"></label>                            
                        <div class="col-sm-4">
                            <div class="input text pull-right">
                           <?php echo $this->Html->link($this->Form->button('<i class="fa fa-circle-arrow-left"></i>Cancel', array('type' => 'button', 'class' => 'btn btn-light-grey go-back')), array('controller' => 'professions', 'action' => 'index', 'admin' => true), array('escape' => false)); ?>
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




