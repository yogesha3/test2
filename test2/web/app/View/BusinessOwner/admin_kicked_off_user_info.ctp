<?php

/** 
 * View Kicked off user details
 * @author Laxmi Saini
 */
?>
<div class="row">
    <div class="col-sm-12">
        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <i class="clip-file"></i>
                <?php echo $this->Html->link('Business Owners', array('controller' => 'businessOwners', 'action' => 'index', 'admin' => true));?>
            </li>
            <li class="active"> View Kicked Off User</li>
        </ol>
        <div class="page-header">
            <?php echo $this->Html->tag('h1', ucfirst($businessOwnerData['BusinessOwner']['fname']." ".$businessOwnerData['BusinessOwner']['lname']));?>
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
            <?php //pr($businessOwnerData);?>
            <div class="panel-body">
                <div class="smart-wizard form-horizontal">
                    <fieldset>
                        <legend>Member Information</legend>
                    </fieldset>
                    <div id="wizard" class="swMain">
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Member Name ' , array('class' => 'col-sm-3 control-label col-sm-offset-2')); ?>
                            <div class="col-sm-7">
                           <?php echo $this->Form->label('',ucfirst($businessOwnerData['BusinessOwner']['fname'])." ".ucfirst($businessOwnerData['BusinessOwner']['lname']), array('class' => 'col-sm-3 control-label text_left col-sm-offset-1')); ?>   
                            </div>
                        </div>
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Member Profession ' , array('class' => 'col-sm-3 control-label col-sm-offset-2')); ?>
                            <div class="col-sm-7">
                           <?php echo $this->Form->label('',ucfirst($businessOwnerData['Profession']['profession_name']), array('class' => 'col-sm-3 control-label text_left col-sm-offset-1')); ?>   
                            </div>
                        </div>
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Group Name ' , array('class' => 'col-sm-3 control-label col-sm-offset-2')); ?>
                            <div class="col-sm-7">
                           <?php echo $this->Form->label('','Group '.$this->Encryption->decode($businessOwnerData['Group']['id']), array('class' => 'col-sm-3 control-label text_left col-sm-offset-1')); ?>   
                            </div>
                        </div>
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Group Type ' , array('class' => 'col-sm-3 control-label col-sm-offset-2')); ?>
                            <div class="col-sm-7">
                           <?php echo $this->Form->label('',ucfirst($businessOwnerData['Group']['group_type']), array('class' => 'col-sm-3 control-label text_left col-sm-offset-1')); ?>   
                            </div>
                        </div>
                        <div class="form-group">
                            <?php echo $this->Form->label('', 'Group Meeting Time ' , array('class' => 'col-sm-3 control-label col-sm-offset-2'));?>
                            <div class="col-sm-7">
                            <?php 
                                echo $this->Form->label('', date('h:i A',strtotime($businessOwnerData['Group']['meeting_time'])). ' EST', array('class' => 'col-sm-3 control-label col-sm-offset-1 text_left')); 
                            ?>
                            </div>
                        </div>
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Group Meeting Date 1' , array('class' => 'col-sm-3 control-label col-sm-offset-2'));?>
                            <div class="col-sm-7">
                            <?php 
                                $date = date("m-d-Y", strtotime($businessOwnerData['Group']['first_meeting_date']));
                                echo $this->Form->label('', $date, array('class' => 'col-sm-3 control-label col-sm-offset-1 text_left')); 
                            ?>
                            </div>
                        </div>
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Group Meeting Date 2' , array('class' => 'col-sm-3 control-label col-sm-offset-2'));?>
                            <div class="col-sm-7">
                            <?php 
                                $date = date("m-d-Y", strtotime($businessOwnerData['Group']['second_meeting_date']));
                                echo $this->Form->label('', $date, array('class' => 'col-sm-3 control-label col-sm-offset-1 text_left')); 
                            ?>
                            </div>
                        </div>
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Leader Name' , array('class' => 'col-sm-3 control-label col-sm-offset-2'));?>
                            <div class="col-sm-7">
                            <?php 
                                echo $this->Form->label('', $leaderName['BusinessOwner']['fname'] . " " . $leaderName['BusinessOwner']['lname'], array('class' => 'col-sm-3 control-label col-sm-offset-1 text_left')); 
                            ?>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-sm-2 col-sm-offset-8">
                            <?php 
                            //echo $this->Html->link($this->Form->button('<i class="fa fa-circle-arrow-left"></i> Back', array('type' => 'button', 'class' => 'btn btn-light-grey go-back pull-right')),array('controller'=>'businessOwners','action'=>'index','admin'=>true), array('escape'=>false));
                            ?>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
            <?php echo $this->Form->create('Group',array('url'=>array('controller'=>'businessOwners','action'=>'kickedOffUserInfo','admin'=>true, $businessOwnerData['BusinessOwner']['id']),'class'=>'smart-wizard form-horizontal'));?>
                <div class="swMain" id="wizard">
					<fieldset>
                        <legend>Re-Group</legend>
                    </fieldset>
                  
                	
                   <div class="form-group">
                        <label class="col-sm-3 control-label col-sm-offset-2" for="ProfessionId">Groups available for re-grouping</label>
                        <div class="col-sm-5">
                            
                            <?php
                            $disable=true;
                            if(!empty($groupSuggestion)){
                                $disable=false;
								$empty = false;
                            } else {
								$empty = 'No group available';							}
                            ?>
                            <?php echo $this->Form->select('Group.group_id',$groupSuggestion, array(
                                'label' => false, 
                                'class' => 'form-control', 
                                'id' => 'group_names',
                                'empty'=> $empty,
                                'disabled'=>$disable
                              ));
                            //echo $this->Form->input('group_id', array(
                            //    'type' => 'select',
                            //    'options' => $groupSuggestion,
                            //    'empty' => false,
                            //    'class' => 'form-control',
                            //    'empty' => false,
                            //    'id' => 'group_names',
                            //    'disabled' => $disable,
                            //    'label' => false
                            //));
                            ?>
                              <?php //echo $this->Form->hidden('userId',array('value'=>$change_request_data['BusinessOwner']['id']));?>
                              <?php //echo $this->Form->hidden('groupChangeRequestId',array('value'=>$change_request_data['GroupChangeRequest']['id']));?>
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
                                <?php echo $this->Html->link($this->Form->button('<i class="fa fa-circle-arrow-left"></i>Back', array('type' => 'button', 'class' => 'btn btn-light-grey go-back')), array('controller' => 'businessOwners', 'action' => 'kickedOffUsers', 'admin' => true), array('escape' => false)); ?>
                                <?php if ($groupSuggestion) { ?>
                                <?php echo $this->Form->button('Move <i class="fa"></i>', array('type' => 'submit', 'class' => 'btn btn-bricky'));?>
                                <?php } else { ?>
                                <?php echo $this->Form->button('Move <i class="fa"></i>', array('type' => 'submit', 'class' => 'btn btn-bricky','disabled'=>true));?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php echo $this->Form->end();?>
            </div>
        </div>
    </div>
        <!-- end: FORM WIZARD PANEL -->
    </div>
</div>
<script>
    $('form#GroupAdminKickedOffUserInfoForm').submit(function(){
        $(this).find('button').prop('disabled', true);
    });
</script>
