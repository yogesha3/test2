<!-- start: PAGE HEADER -->
<style>.seperator{padding: 32px 0 0;text-align: center;width: 10px;} .paddTop{padding: 32px 0 0!important;}</style>
<?php echo $this->Html->script('../developer/js/admin_validation');
//    pr($membershipLevels);
?>
<div class="row">
    <div class="col-sm-12">
        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <i class="clip-file"></i>
                <?php echo $this->Html->link('Business Owners', array('controller' => 'businessOwners', 'action' => 'index', 'admin' => true));?>
            </li>
            <li class="active">
                Membership Levels
            </li>
           
        </ol>
        <div class="page-header">
            <?php echo $this->Html->tag('h1', 'Membership Levels');?>
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
                echo $this->Form->create('Membership', array('url' => array('controller' => 'businessOwners', 'action' => 'membershipLevels', 'admin'=>true), 'class' => 'smart-wizard form-horizontal', 'id' => 'membershipLevels'));                
                ?>
                <div id="wizard" class="swMain">
                    <div class="form-group">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-3" style="text-align: center;text-decoration: underline;">Referral Sent (Lower)</div>
                    <div class="col-sm-3" style="text-align: center;text-decoration: underline;">Referral Sent (Upper)</div>
                    </div>
                    <div class="form-group">
                        <?php echo $this->Form->label('', '<strong>Bronze Membership</strong>'.$this->Html->tag('span', '', array('class' => 'symbol required')), array('class' => 'paddTop col-sm-3 control-label')); ?>
                        <div class="col-sm-3">
                        
                        <?php echo $this->Form->input('Membership.Bronze.lower_limit', array('label' => false, 'class' => 'form-control', 'id' => 'bronze_lower_limit' ,'placeholder'=>'','readonly'=>true,'value'=>0,'maxlength'=>4));?>
                        </div>
                        <div class="col-sm-1 seperator"></div>
                        <div class="col-sm-3">
                        
                        <?php $params = array('label' => false, 'class' => 'form-control', 'id' => 'bronze_upper_limit' ,'placeholder'=>'','maxlength'=>4);
                        if(!empty($membershipLevels)) {
                            $params['value']=$membershipLevels[0]['Membership']['upper_limit'];
                        }
                        echo $this->Form->input('Membership.Bronze.upper_limit', $params);?>
                        </div>
                    </div>                 
                   
                </div>
                
                <div id="wizard" class="swMain">
                    <div class="form-group">
                        <?php echo $this->Form->label('', '<strong>Silver Membership</strong>'.$this->Html->tag('span', '', array('class' => 'symbol required')), array('class' => 'paddTop col-sm-3 control-label')); ?>
                        <div class="col-sm-3">
                        <?php $params = array('label' => false, 'class' => 'form-control', 'id' => 'silver_lower_limit' ,'placeholder'=>'','maxlength'=>4);
                        if(!empty($membershipLevels)) {
                            $params['value']=$membershipLevels[1]['Membership']['lower_limit'];
                        }?>
                        <?php echo $this->Form->input('Membership.Silver.lower_limit', $params);?>
                        </div>
                        <div class="col-sm-1 seperator"></div>
                        <div class="col-sm-3">
                        <?php $params = array('label' => false, 'class' => 'form-control', 'id' => 'silver_upper_limit' ,'placeholder'=>'','maxlength'=>4);
                        if(!empty($membershipLevels)) {
                            $params['value']=$membershipLevels[1]['Membership']['upper_limit'];
                        }?>
                        <?php echo $this->Form->input('Membership.Silver.upper_limit', $params);?>
                        </div>
                    </div>                 
                    
                </div>
                
                <div id="wizard" class="swMain">
                    <div class="form-group">
                        <?php echo $this->Form->label('', '<strong>Gold Membership</strong>'.$this->Html->tag('span', '', array('class' => 'symbol required')), array('class' => 'paddTop col-sm-3 control-label')); ?>
                        <div class="col-sm-3">
                        <?php $params = array('label' => false, 'class' => 'form-control', 'id' => 'gold_lower_limit' ,'placeholder'=>'','maxlength'=>4);
                        if(!empty($membershipLevels)) {
                            $params['value']=$membershipLevels[2]['Membership']['lower_limit'];
                        }?>
                        <?php echo $this->Form->input('Membership.Gold.lower_limit', $params);?>
                        </div>
                        <div class="col-sm-1 seperator"></div>
                        <div class="col-sm-3">
                        <?php $params = array('label' => false, 'class' => 'form-control', 'id' => 'gold_upper_limit' ,'placeholder'=>'','maxlength'=>4);
                        if(!empty($membershipLevels)) {
                            $params['value']=$membershipLevels[2]['Membership']['upper_limit'];
                        }?>
                        <?php echo $this->Form->input('Membership.Gold.upper_limit', $params);?>
                        </div>
                    </div>                 
                   
                </div>
                
                               
                <div id="wizard" class="swMain">
                    <div class="form-group">
                        <?php echo $this->Form->label('', '<strong>Platinum Membership</strong>'.$this->Html->tag('span', '', array('class' => 'symbol required')), array('class' => 'paddTop col-sm-3 control-label')); ?>
                        <div class="col-sm-6">
                        <p style="margin: 5px 0 10px;"><i>This membership will automatically start after achieving the upper limit of Gold Membership</i></p>
                        </div>
                        
                    </div>            
                    <div class="form-group">
                        <div class="col-sm-1 col-sm-offset-8" style="padding-right: 6px;">
                            <?php $saveButtonText = 'Save';
                            if(!empty($membershipLevels)) {
                                $saveButtonText = 'Update';
                            }?>
                            <?php echo $this->Form->button($saveButtonText.' <i class="fa fa-arrow-circle-right"></i>', array('type' => 'submit', 'class' => 'btn btn-bricky pull-right')); ?>
                        </div>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
        <!-- end: FORM WIZARD PANEL -->
    </div>
</div>