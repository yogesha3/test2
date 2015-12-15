<?php

/** 
 * View business Owner details
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
            <li class="active">
                View Business Owner
            </li>
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

            <div class="panel-body">
                <div class="smart-wizard form-horizontal">

                    <div id="wizard" class="swMain">
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Email Id ' , array('class' => 'col-sm-3 control-label col-sm-offset-2')); ?>
                            <div class="col-sm-7">
                           <?php echo $this->Form->label('',($businessOwnerData['BusinessOwner']['email']=='' || $businessOwnerData['BusinessOwner']['email']==NULL)?'-':$businessOwnerData['BusinessOwner']['email'], array('class' => 'col-sm-3 control-label text_left col-sm-offset-1')); ?>   
                            </div>
                        </div>
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Profession ' , array('class' => 'col-sm-3 control-label col-sm-offset-2')); ?>
                            <div class="col-sm-7">
                           <?php echo $this->Form->label('',($businessOwnerData['Profession']['profession_name']=='' || $businessOwnerData['Profession']['profession_name']==NULL)?'-':ucfirst($businessOwnerData['Profession']['profession_name']), array('class' => 'col-sm-3 control-label text_left col-sm-offset-1')); ?>   
                            </div>
                        </div>
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Group Name ' , array('class' => 'col-sm-3 control-label col-sm-offset-2')); ?>
                            <div class="col-sm-7">
                            <?php $groupId=$this->Encryption->decode($businessOwnerData['Group']['id']);?>
                           <?php if($groupId) { echo $this->Form->label('',Configure::read('GROUP_PREFIX').' '.$this->Encryption->decode($businessOwnerData['Group']['id']), array('class' => 'col-sm-3 control-label text_left col-sm-offset-1'));}
                           else{ echo $this->Form->label('','-', array('class' => 'col-sm-3 control-label text_left col-sm-offset-1'));} ?>   
                            </div>
                        </div>

                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Group Meeting Date 1' , array('class' => 'col-sm-3 control-label col-sm-offset-2'));?>
                            <div class="col-sm-7">
                            <?php 
                            if($groupId) { $date = date("m-d-Y", strtotime($businessOwnerData['Group']['first_meeting_date']));
                                echo $this->Form->label('', $date, array('class' => 'col-sm-3 control-label col-sm-offset-1 text_left'));
                            }else {
                                echo $this->Form->label('','-', array('class' => 'col-sm-3 control-label col-sm-offset-1 text_left'));
                            } 
                            ?>
                            </div>
                        </div>

                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Group Meeting Date 2' , array('class' => 'col-sm-3 control-label col-sm-offset-2'));?>
                            <div class="col-sm-7">
                            <?php 
                            if($groupId) {$date = date("m-d-Y", strtotime($businessOwnerData['Group']['second_meeting_date']));
                                echo $this->Form->label('', $date, array('class' => 'col-sm-3 control-label col-sm-offset-1 text_left'));
                            }else {
                                echo $this->Form->label('','-', array('class' => 'col-sm-3 control-label col-sm-offset-1 text_left'));
                            } 
                            ?>
                            </div>
                        </div>

                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Group Meeting Time ' , array('class' => 'col-sm-3 control-label col-sm-offset-2'));?>
                            <div class="col-sm-7">
                        <?php 
                        if($groupId) {$timezone = explode(' ',$businessOwnerData['Group']['timezone_id']);
	                      echo $this->Form->label('', date('h:i A',strtotime($businessOwnerData['Group']['meeting_time'])).' '.$timezone[0], array('class' => 'col-sm-3 control-label col-sm-offset-1 text_left'));
                        }else {
                            echo $this->Form->label('', "-", array('class' => 'col-sm-3 control-label col-sm-offset-1 text_left'));
                        } 
                        ?>
                            </div>
                        </div>

                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Country ' , array('class' => 'col-sm-3 control-label col-sm-offset-2'));?>
                            <div class="col-sm-7">
                        <?php 
                        echo $this->Form->label('', ($businessOwnerData['Country']['country_name']=='' || $businessOwnerData['Country']['country_name']==NULL)?'-':$businessOwnerData['Country']['country_name'], array('class' => 'col-sm-3 control-label col-sm-offset-1 text_left'));
                        
                        ?>
                            </div>
                        </div>

                        <div class="form-group">
                        <?php echo $this->Form->label('', 'State ' , array('class' => 'col-sm-3 control-label col-sm-offset-2'));?>
                            <div class="col-sm-7">
                        <?php 
                        echo $this->Form->label('', ($businessOwnerData['State']['state_subdivision_name']=='' || $businessOwnerData['State']['state_subdivision_name']==NULL)?'-':$businessOwnerData['State']['state_subdivision_name'], array('class' => 'col-sm-3 control-label col-sm-offset-1 text_left')); 
                        ?>
                            </div>
                        </div>

                        <div class="form-group">
                        <?php echo $this->Form->label('', 'City ' , array('class' => 'col-sm-3 control-label col-sm-offset-2'));?>
                            <div class="col-sm-7">
                        <?php 
                        echo $this->Form->label('', ($businessOwnerData['BusinessOwner']['city']=='' || $businessOwnerData['BusinessOwner']['city']==NULL)?'-':ucfirst($businessOwnerData['BusinessOwner']['city']), array('class' => 'col-sm-3 control-label col-sm-offset-1 text_left')); 
                        ?>
                            </div>
                        </div>

                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Zip Code' , array('class' => 'col-sm-3 control-label col-sm-offset-2'));?>
                            <div class="col-sm-7">
                        <?php 
                        echo $this->Form->label('', $businessOwnerData['BusinessOwner']['zipcode'], array('class' => 'col-sm-3 control-label col-sm-offset-1 text_left')); 
                        ?>
                            </div>
                        </div>


                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Web Link(s)' , array('class' => 'col-sm-3 control-label col-sm-offset-2'));?>
                        
                            <div class="col-sm-7">
                        <?php 
                        
                        echo $this->Form->label('', ($businessOwnerData['BusinessOwner']['website']=='' || $businessOwnerData['BusinessOwner']['website']==NULL)?'-':$businessOwnerData['BusinessOwner']['website'], array('class' => 'col-sm-3 control-label col-sm-offset-1 text_left')); 
                        ?>
                            </div>
                        </div>
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Mobile' , array('class' => 'col-sm-3 control-label col-sm-offset-2'));?>
                            <div class="col-sm-7">
                        <?php 
                        echo $this->Form->label('', ($businessOwnerData['BusinessOwner']['mobile']=='' || $businessOwnerData['BusinessOwner']['mobile']==NULL)?'-':$businessOwnerData['BusinessOwner']['mobile'], array('class' => 'col-sm-3 control-label col-sm-offset-1 text_left')); 
                        ?>
                            </div>
                        </div>
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Phone' , array('class' => 'col-sm-3 control-label col-sm-offset-2'));?>
                            <div class="col-sm-7">
                        <?php 
                        echo $this->Form->label('', ($businessOwnerData['BusinessOwner']['office_phone']!='' || $businessOwnerData['BusinessOwner']['office_phone']!=NULL)?$businessOwnerData['BusinessOwner']['office_phone']:'-', array('class' => 'col-sm-3 control-label col-sm-offset-1 text_left')); 
                        ?>
                            </div>
                        </div>

                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Timezone ' , array('class' => 'col-sm-3 control-label col-sm-offset-2'));?>
                            <div class="col-sm-7">
                        <?php 
                        //pr($businessOwnerData);exit;
                        echo $this->Form->label('', ($businessOwnerData['BusinessOwner']['timezone_id']==''||$businessOwnerData['BusinessOwner']['timezone_id']==NULL)?'-':$businessOwnerData['BusinessOwner']['timezone_id'], array('class' => 'col-sm-3 control-label col-sm-offset-1 text_left')); 
                        ?>
                            </div>
                        </div>
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Skype ID' , array('class' => 'col-sm-3 control-label col-sm-offset-2'));?>
                            <div class="col-sm-7">
                        <?php 
                        echo $this->Form->label('', ($businessOwnerData['BusinessOwner']['skype_id']=='' || $businessOwnerData['BusinessOwner']['skype_id']==NULL)?"-":$businessOwnerData['BusinessOwner']['skype_id'], array('class' => 'col-sm-3 control-label col-sm-offset-1 text_left')); 
                        ?>
                            </div>
                        </div>
                        <div class="form-group">
                        <?php echo $this->Form->label('', 'Is group Leader or Co-Leader ' , array('class' => 'col-sm-3 control-label col-sm-offset-2'));?>
                            <div class="col-sm-7">
                        <?php 
                        $leadership='No';
                        if ($businessOwnerData['BusinessOwner']['group_role'] == 'leader') {
                            $leadership = 'Group Leader';
                        } elseif ($businessOwnerData['BusinessOwner']['group_role'] == 'co-leader'){
                            $leadership = 'Group Co-leader';
                        }
                        echo $this->Form->label('',  $leadership, array('class' => 'col-sm-3 control-label col-sm-offset-1 text_left')); 
                        ?>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-2 col-sm-offset-8">
                            <?php 
                            echo $this->Html->link($this->Form->button('<i class="fa fa-circle-arrow-left"></i> Back', array('type' => 'button', 'class' => 'btn btn-light-grey go-back pull-right')),array('controller'=>'businessOwners','action'=>'index','admin'=>true), array('escape'=>false));
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
