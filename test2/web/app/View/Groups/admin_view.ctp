<div class="row">
    <div class="col-sm-12">
        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <i class="clip-file"></i>
                <?php echo $this->Html->link('Groups', array('controller' => 'groups', 'action' => 'index', 'admin' => true));?>
            </li>
            <li class="active"> View Group </li>
        </ol>
        <div class="page-header">
            <?php echo $this->Html->tag('h1', Configure::read('GROUP_PREFIX').' '.$this->Encryption->decode($groupData['Group']['id']));?>
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

                    <div id="wizard" class="swMain">
                        <div class="col-sm-6">                                      
                            <div class="form-group">
                                <?php echo $this->Form->label('', 'Group Name', array('class' => 'col-sm-4 control-label')); ?>
                                <div class="col-sm-7">
                                <?php echo $this->Form->input('', array('label' => false,'name'=>'','value'=> Configure::read('GROUP_PREFIX').' '.$this->Encryption->decode($groupData['Group']['id']), 'class' => 'form-control', 'id' => 'groupName', 'disabled'=>true,'readonly'=>true));?>
                                </div>
                            </div>                        

                            <div class="form-group">
                                <?php echo $this->Form->label('', 'First Meeting Date', array('class' => 'col-sm-4 control-label')); ?>
                                <div class="col-sm-7">
                                <?php
                                $date = date("m-d-Y", strtotime($groupData['Group']['first_meeting_date']));
                                echo $this->Form->input('',array('type'=>'text','name'=>'','value'=>$date,'label'=>false,'div'=>false,'class'=>'form-control','readonly'=>true));
                                ?>
                                </div>
                            </div>

                            <div class="form-group">
                            <?php echo $this->Form->label('', 'Group Meeting Time ' , array('class' => 'col-sm-4 control-label'));?>
                                <div class="col-sm-7">
                            <?php echo $this->Form->input('',array('type'=>'text','name'=>'','value'=>$this->Adobeconnect->getSlotTimes($groupData['AvailableSlots']['slot_id']),'label'=>false,'div'=>false,'class'=>'form-control','readonly'=>true)); ?>
                                </div>
                            </div>
                            <div class="form-group">
                            <?php echo $this->Form->label('', 'State ' , array('class' => 'col-sm-4 control-label'));?>
                                <div class="col-sm-7">
                                <?php echo $this->Form->input('', array('label' => false,'name'=>'','value'=>ucfirst($groupData['State']['state_subdivision_name']), 'class' => 'form-control', 'id' => 'state', 'disabled'=>true,'readonly'=>true)); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <?php echo $this->Form->label('', 'Zip Code' , array('class' => 'col-sm-4 control-label '));?>
                                <div class="col-sm-7">
                                    <?php echo $this->Form->input('', array('label' => false,'name'=>'','value'=>ucfirst($groupData['Group']['zipcode']), 'class' => 'form-control', 'id' => 'zipcode', 'disabled'=>true,'readonly'=>true)); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <?php echo $this->Form->label('', 'Number of Member(s) ' , array('class' => 'col-sm-4 control-label'));?>
                                <div class='<?php echo ($groupData["Group"]["total_member"]!=NULL && $groupData["Group"]["total_member"]!='0')?"col-sm-6":"col-sm-7";?>'>
                                    <?php echo $this->Form->input('', array('type'=>'text','label' => false,'value'=>($groupData['Group']['total_member']!=NULL)?$groupData['Group']['total_member']:"-", 'class' => 'form-control', 'id' => 'totalMember', 'disabled'=>true,'readonly'=>true)); ?>
                                </div>
                                <?php 
                                if (!empty($groupData["Group"]["total_member"])) {
                                ?>
                                <div class="col-sm-1 control-label" style="padding-left:0px;text-align: left;">
                                  <?php
                                  $groupId = $groupData['Group']['id'];
                                  $dataUrl = 'admin/groups/getGroupMembersList/'.$groupData['Group']['id'];
                                  echo $this->Html->link('View', 'javascript:void(0)', 
                                                            array(
                                                                  'class' => '',
                                                                  'data-toggle' => 'modal',
                                                                  'data-backdrop'=>'static',
                                                                  'data-placement' => 'top',
                                                                  'data-target' => '#popup',
                                                                  'onclick'=>"popUp('".$dataUrl."','".$groupId."')",
                                                                  'escape' => false
                                                                  ));
                                  ?>
                                </div>
                                <?php }?>
                            </div>
                            <div class="form-group">
                                <?php echo $this->Form->label('', 'Group Co-Leader ' , array('class' => 'col-sm-4 control-label'));?>
                                <div class="col-sm-7">
                                    <?php echo $this->Form->input('', array('label' => false,'value'=>($groupData['Group']['group_coleader_id']!=NULL)?'Yes':'No', 'class' => 'form-control', 'id' => 'isCoLeader', 'disabled'=>true,'readonly'=>true)); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?php echo $this->Form->label('', 'Type of Group', array('class' => 'col-sm-4 control-label')); ?>
                                <div class="col-sm-7">
                                    <?php echo $this->Form->input('', array('label' => false,'name'=>'','value'=>ucfirst($groupData['Group']['group_type']), 'class' => 'form-control', 'id' => 'groupType', 'disabled'=>true,'readonly'=>true));?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?php echo $this->Form->label('', 'Second Meeting Date', array('class' => 'col-sm-4 control-label')); ?>
                                <div class="col-sm-7">
                                <?php
                                    $date = date("m-d-Y", strtotime($groupData['Group']['second_meeting_date']));
                                    echo $this->Form->input('',array('type'=>'text','name'=>'','value'=>$date,'label'=>false,'div'=>false,'class'=>'form-control','readonly'=>true));
                                ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?php echo $this->Form->label('', 'Country ' , array('class' => 'col-sm-4 control-label'));?>
                                <div class="col-sm-7">
                                    <?php echo $this->Form->input('', array('label' => false,'name'=>'','value'=>ucfirst($groupData['Country']['country_name']), 'class' => 'form-control', 'id' => 'country', 'disabled'=>true,'readonly'=>true)); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?php echo $this->Form->label('', 'City ' , array('class' => 'col-sm-4 control-label'));?>
                                <div class="col-sm-7">
                                    <?php echo $this->Form->input('', array('label' => false,'name'=>'','value'=>ucfirst($groupData['Group']['city']), 'class' => 'form-control', 'id' => 'city', 'disabled'=>true,'readonly'=>true));?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?php echo $this->Form->label('', 'Timezone ' , array('class' => 'col-sm-4 control-label'));?>
                                <div class="col-sm-7">
                                    <?php echo $this->Form->input('', array('label' => false,'name'=>'','value'=>$groupData['Group']['timezone_id'], 'class' => 'form-control', 'id' => 'timezone', 'required'=>false,'disabled'=>true,'readonly'=>true)); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?php echo $this->Form->label('', 'Group Leader ' , array('class' => 'col-sm-4 control-label'));?>
                                <div class="col-sm-7">
                                    <?php echo $this->Form->input('', array('label' => false,'name'=>'','value'=>($groupData['Group']['group_leader_id']!=NULL)?'Yes':'No', 'class' => 'form-control', 'id' => 'isLeader', 'required'=>false,'disabled'=>true,'readonly'=>true)); ?>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group">
                            <div class="col-sm-2 col-sm-offset-10">
                            <?php 
                            echo $this->Html->link($this->Form->button('<i class="fa fa-circle-arrow-left"></i> Back', array('type' => 'button', 'class' => 'btn btn-light-grey go-back pull-right','style'=>'margin-right:15px')),array('controller'=>'Groups','action'=>'index','admin'=>true), array('escape'=>false));
                            ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end: FORM WIZARD PANEL -->
    </div>
</div>