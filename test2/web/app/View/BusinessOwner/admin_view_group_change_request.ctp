<div class="row">
    <div class="col-sm-12">
        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <i class="clip-file"></i>
                <?php echo $this->Html->link('Business Owners', array('controller' => 'businessOwners', 'action' => 'index', 'admin' => true));?>
            </li>
            <li class="active">
                View Group Change Request
            </li>
        </ol>
        <div class="page-header">
            <?php echo $this->Html->tag('h1', ucfirst($change_request_data['BusinessOwner']['fname']." ".$change_request_data['BusinessOwner']['lname']));?>
        </div>
        <!-- end: PAGE TITLE & BREADCRUMB -->
    </div>
</div>
<style >.text_left{ text-align: left !important;}</style>
<!-- end: PAGE HEADER -->
<div class="row">
    <div class="col-sm-12">
        <!-- start: FORM WIZARD PANEL -->
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="smart-wizard form-horizontal">
                <fieldset>
                        <legend>Member Information</legend>
                    </fieldset>
                    <div id="wizard" class="swMain">
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Member Name' , array('class' => 'col-sm-3 control-label col-sm-offset-2')); ?>
                            <div class="col-sm-7">
                           <?php echo $this->Form->label('',$change_request_data['BusinessOwner']['member_name'], array('class' => 'col-sm-3 control-label text_left col-sm-offset-1')); ?>   
                            </div>
                        </div>
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Member Profession' , array('class' => 'col-sm-3 control-label col-sm-offset-2')); ?>
                            <div class="col-sm-7">
                           <?php echo $this->Form->label('',ucfirst($change_request_data['Profession']['profession_name']), array('class' => 'col-sm-3 control-label text_left col-sm-offset-1')); ?>   
                            </div>
                        </div>
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Group Name' , array('class' => 'col-sm-3 control-label col-sm-offset-2')); ?>
                            <div class="col-sm-7">
                           <?php echo $this->Form->label('',ucfirst('group '. $this->Encryption->decode($change_request_data['Group']['id'])), array('class' => 'col-sm-3 control-label text_left col-sm-offset-1')); ?>   
                            </div>
                        </div>
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Group Type' , array('class' => 'col-sm-3 control-label col-sm-offset-2')); ?>
                            <div class="col-sm-7">
                           <?php echo $this->Form->label('',ucfirst($change_request_data['Group']['group_type']), array('class' => 'col-sm-3 control-label text_left col-sm-offset-1')); ?>   
                            </div>
                        </div>
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Group Meeting Time' , array('class' => 'col-sm-3 control-label col-sm-offset-2'));?>
                            <div class="col-sm-7">
                       		<?php echo $this->Form->label('',date('h:i A',strtotime($change_request_data['Group']['meeting_time'])).' EST', array('class' => 'col-sm-3 control-label text_left col-sm-offset-1')); ?>
                            </div>
                        </div>
                        <!--<div class="form-group">
                        <?php //echo $this->Form->label('', 'Proposed Meeting Time' , array('class' => 'col-sm-3 control-label col-sm-offset-2'));?>
                            <div class="col-sm-7">
                       		<?php //echo $this->Form->label('',ucfirst($change_request_data['GroupChangeRequest']['proposed_meeting_time']), array('class' => 'col-sm-3 control-label text_left col-sm-offset-1')); ?>
                            </div>
                        </div>-->
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Current Meeting Date 1' , array('class' => 'col-sm-3 control-label col-sm-offset-2'));?>
                            <div class="col-sm-7">
                       		<?php echo $this->Form->label('',date("d M Y",strtotime($change_request_data['Group']['first_meeting_date'])), array('class' => 'col-sm-3 control-label text_left col-sm-offset-1')); ?>
                            </div>
                        </div>
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Current Meeting Date 2' , array('class' => 'col-sm-3 control-label col-sm-offset-2'));?>
                            <div class="col-sm-7">
                       		<?php echo $this->Form->label('',date("d M Y",strtotime($change_request_data['Group']['second_meeting_date'])), array('class' => 'col-sm-3 control-label text_left col-sm-offset-1')); ?>
                            </div>
                        </div>                      
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Group Leader' , array('class' => 'col-sm-3 control-label col-sm-offset-2'));?>
                            <div class="col-sm-7">
                            <?php if($change_request_data['Group']['group_leader_id'] !='' ||$change_request_data['Group']['group_leader_id']!=NULL){$leader_name = $groupsComponent->getGroupLeaderNameById($change_request_data['Group']['group_leader_id']);?>
                       		<?php echo $this->Form->label('',$leader_name, array('class' => 'col-sm-3 control-label text_left col-sm-offset-1'));} ?>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
        <!-- end: FORM WIZARD PANEL -->
        <div class="panel panel-default">
            <div class="panel-body">
            <?php echo $this->Form->create('Group',array('url'=>array('controller'=>'businessOwners','action'=>'moveUserGroup','admin'=>true),'class'=>'smart-wizard form-horizontal'));?>
                <div class="swMain" id="wizard">
                <fieldset>
                        <legend>Re-Group</legend>
                    </fieldset>
                    <div class="col-sm-3 control-label col-sm-offset-2">
                        <div class="form-group"><label for="form-field-23">Groups available for re-grouping</label></div>
                    </div>
                	
                   <div class="form-group">
                        <div class="col-sm-5">
                            <?php
                            $disable=true;
                            if(!empty($group_suggestion)){
                                $disable=false;
                            }
                            ?>
                            <?php echo $this->Form->select('Group.group_id',$group_suggestion, array(
                                'label' => false, 
                                'class' => 'form-control', 
                                'id' => 'group_names','empty'=>false,
                                'disabled'=>$disable,
                              ));?>
                              <?php echo $this->Form->hidden('userId',array('value'=>$change_request_data['BusinessOwner']['user_id']));?>
                              <?php echo $this->Form->hidden('groupChangeRequestId',array('value'=>$change_request_data['GroupChangeRequest']['id']));?>
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
                           <?php echo $this->Html->link($this->Form->button('<i class="fa fa-circle-arrow-left"></i>Back', array('type' => 'button', 'class' => 'btn btn-light-grey go-back')), array('controller' => 'businessOwners', 'action' => 'groupChangeRequest', 'admin' => true), array('escape' => false)); ?>
                           
                           	<?php echo $this->Form->button('Move <i class="fa"></i>', array('type' => 'submit', 'class' => 'btn btn-bricky ','disabled'=>$disable));?>
                           
                        </div>
                    </div>
                </div>
              <?php echo $this->Form->end();?>
            </div>
        </div>
    </div>
</div>
