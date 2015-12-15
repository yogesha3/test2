<!-- start: PAGE HEADER -->
<style>.seperator{padding: 32px 0 0;text-align: center;width: 10px;} .paddTop{padding: 32px 0 0!important;}</style>
<div class="row">
    <div class="col-sm-12">
        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <i class="clip-file"></i>
                <?php echo $this->Html->link('Shuffling', array('controller' => 'groupShuffles', 'action' => 'shufflingStep1', 'admin' => true));?>
            </li>
            <li class="active">
                Shuffling Percentage
            </li>
           
        </ol>
        <div class="page-header">
            <?php echo $this->Html->tag('h1', 'Shuffling Percentage');?>
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
                echo $this->Form->create('groupShuffles', array('url' => array('controller' => 'groupShuffles', 'action' => 'setShufflingCriteria', 'admin'=>true), 'class' => 'smart-wizard form-horizontal', 'id' => 'updateShuffleCriteria'));                
                ?>
                <div id="wizard" class="swMain">
                    <div class="form-group">
                        <?php echo $this->Form->label('', 'Set Shuffle Percent'.$this->Html->tag('span', '', array('class' => 'symbol required')), array('class' => 'paddTop col-sm-3 control-label')); ?>
                        <div class="col-sm-3">
                        <?php echo $this->Form->label('', '% of Total Referral'.$this->Html->tag('span', '', array('class' => '')), array('class' => 'control-label')); ?>
                        <?php echo $this->Form->input('Setting.shuffling_criteria_1', array('label' => false, 'class' => 'form-control', 'id' => 'shuffling_criteria_1' ,'placeholder'=>''));?>
                        </div>
                        <div class="col-sm-1 seperator">:</div>
                        <div class="col-sm-3">
                        <?php echo $this->Form->label('', '% of Rating'.$this->Html->tag('span', '', array('class' => '')), array('class' => 'control-label')); ?>
                        <?php echo $this->Form->input('Setting.shuffling_criteria_2', array('label' => false, 'class' => 'form-control', 'id' => 'shuffling_criteria_2' ,'placeholder'=>''));?>
                        </div>
                    </div>                 
                    <div class="form-group">
                        <div class="col-sm-1 col-sm-offset-8" style="padding-right: 6px;">
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