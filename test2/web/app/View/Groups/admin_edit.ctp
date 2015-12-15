<div class="row">
    <div class="col-sm-12">
        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <i class="clip-file"></i>
                <?php echo $this->Html->link('Groups', array('controller' => 'groups', 'action' => 'index', 'admin' => true));?>
            </li>
            <li class="active">
                Edit Group
            </li>
        </ol>
        <div class="page-header">
            <?php echo $this->Html->tag('h1', ucfirst($groupData['Group']['group_name']));?>
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
                    <?php  
                        echo $this->Form->create('groups', array('url' => array('controller' => 'groups', 'action' => 'edit', 'admin'=>true, $groupData['Group']['id']), 'class' => 'smart-wizard form-horizontal', 'id' => 'editGroup','inputDefaults' => array('error' => false))); 
                        echo $this->Form->input('Group.id', array('type' => 'hidden', 'value' => $groupData['Group']['id'], 'id' => 'groupId'));
                    ?>
                    <div class="col-sm-6">                                      
                            <div class="form-group">
                                <?php echo $this->Form->label('', 'Group Name'.$this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'groupName')), array('class' => 'col-sm-4 control-label')); ?>
                                <div class="col-sm-7">
                                <?php echo $this->Form->input('Group.group_name', array('label' => false,'value'=>ucfirst($groupData['Group']['group_name']), 'class' => 'form-control', 'id' => 'groupName',  'placeholder'=>'Group Name','autocomplete'=>'off', 'maxlength'=>25,'required'=>false));?>
                                </div>
                            </div>                        

                            <div class="form-group">
                                <?php echo $this->Form->label('', 'First Meeting Date', array('class' => 'col-sm-4 control-label')); ?>
                                <div class="col-sm-7">
                                <?php
                                $date = date("m-d-Y", strtotime($groupData['Group']['first_meeting_date']));
                                echo $this->Form->input('uneditable',array('type'=>'text','value'=>$date,'label'=>false,'div'=>false,'class'=>'form-control','readonly'=>true,'disabled'=>true));
                                ?>
                                </div>
                            </div>

                            <div class="form-group">
                            <?php echo $this->Form->label('', 'Group Meeting Time ' , array('class' => 'col-sm-4 control-label'));?>
                                <div class="col-sm-7">
                            <?php echo $this->Form->input('uneditable',array('type'=>'text','value'=>$groupData['Group']['meeting_time'],'label'=>false,'div'=>false,'class'=>'form-control','readonly'=>true,'disabled'=>true)); ?>
                                </div>
                            </div>
                            <div class="form-group">
                            <?php echo $this->Form->label('', 'State ' , array('class' => 'col-sm-4 control-label'));?>
                                <div class="col-sm-7">
                                <?php echo $this->Form->input('uneditable', array('label' => false,'value'=>ucfirst($groupData['State']['state_subdivision_name']), 'class' => 'form-control', 'id' => 'state', 'disabled'=>true,'readonly'=>true)); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <?php echo $this->Form->label('', 'Zip Code' , array('class' => 'col-sm-4 control-label '));?>
                                <div class="col-sm-7">
                                    <?php echo $this->Form->input('uneditable', array('label' => false,'value'=>ucfirst($groupData['Group']['zipcode']), 'class' => 'form-control', 'id' => 'zipcode', 'disabled'=>true,'readonly'=>true)); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <?php echo $this->Form->label('', 'Number of Member(s) ' , array('class' => 'col-sm-4 control-label'));?>
                                <div class="col-sm-7">
                                    <?php echo $this->Form->input('uneditable', array('type'=>'text','label' => false,'value'=>($groupData['Group']['total_member']!=NULL)?$groupData['Group']['total_member']:"-", 'class' => 'form-control', 'id' => 'totalMember', 'disabled'=>true,'readonly'=>true)); ?>
                                </div>
                             
                            </div>
                            
                            <div class="form-group">
                                <?php echo $this->Form->label('', 'Group Co-Leader ' , array('class' => 'col-sm-4 control-label'));?>
                                <div class="col-sm-7">
                                    <?php echo $this->Form->input('uneditable', array('label' => false,'value'=>($groupData['Group']['group_coleader_id']!=NULL)?'Yes':'No', 'class' => 'form-control', 'id' => 'isCoLeader', 'disabled'=>true,'readonly'=>true)); ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?php echo $this->Form->label('', 'Type of Group', array('class' => 'col-sm-4 control-label')); ?>
                                <div class="col-sm-7">
                                    <?php echo $this->Form->input('uneditable', array('label' => false,'value'=>ucfirst($groupData['Group']['group_type']), 'class' => 'form-control', 'id' => 'groupType', 'disabled'=>true,'readonly'=>true));?>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <?php echo $this->Form->label('', 'Second Meeting Date', array('class' => 'col-sm-4 control-label')); ?>
                                <div class="col-sm-7">
                                <?php
                                    $date = date("m-d-Y", strtotime($groupData['Group']['second_meeting_date']));
                                    echo $this->Form->input('uneditable',array('type'=>'text','value'=>$date,'label'=>false,'div'=>false,'class'=>'form-control','readonly'=>true));
                                ?>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <?php echo $this->Form->label('', 'Country ' , array('class' => 'col-sm-4 control-label'));?>
                                <div class="col-sm-7">
                                    <?php echo $this->Form->input('uneditable', array('label' => false,'value'=>ucfirst($groupData['Country']['country_name']), 'class' => 'form-control', 'id' => 'country', 'disabled'=>true,'readonly'=>true)); ?>
                                </div>
                            </div>


                            <div class="form-group">
                                <?php echo $this->Form->label('', 'City ' , array('class' => 'col-sm-4 control-label'));?>
                                <div class="col-sm-7">
                                    <?php echo $this->Form->input('uneditable', array('label' => false,'value'=>ucfirst($groupData['Group']['city']), 'class' => 'form-control', 'id' => 'city', 'disabled'=>true,'readonly'=>true));?>
                                </div>
                            </div>


                            <div class="form-group">
                                <?php echo $this->Form->label('', 'Timezone ' , array('class' => 'col-sm-4 control-label'));?>
                                <div class="col-sm-7">
                                    <?php echo $this->Form->input('uneditable', array('label' => false,'value'=>(isset($groupData['Group']['timezone_id']) && ($groupData['Group']['timezone_id']!=''))?ucfirst($groupData['Group']['timezone_id'])." (".$this->Timezone->getTimezoneOffset($groupData['Group']['timezone_id']).")":"-", 'class' => 'form-control', 'id' => 'timezone', 'required'=>false,'disabled'=>true,'readonly'=>true)); ?>
                                </div>
                            </div>


                            <div class="form-group">
                                <?php echo $this->Form->label('', 'Group Leader ' , array('class' => 'col-sm-4 control-label'));?>
                                <div class="col-sm-7">
                                    <?php echo $this->Form->input('uneditable', array('label' => false,'value'=>($groupData['Group']['group_leader_id']!=NULL)?'Yes':'No', 'class' => 'form-control', 'id' => 'isLeader', 'required'=>false,'disabled'=>true,'readonly'=>true)); ?>
                                </div>
                            </div>
                        </div>
                  
                     <div class="form-group">

                        <label class="col-sm-8 control-label"></label>                            
                        <div class="col-sm-4">
                            <div class="input text pull-right">
                           <?php 
                                echo $this->Html->link($this->Form->button('<i class="fa fa-circle-arrow-left"></i> Cancel', array('type' => 'button', 'class' => 'btn btn-light-grey go-back')), array('controller' => 'groups', 'action' => 'index', 'admin' => true), array('escape' => false)); 
                                echo '&nbsp';
                                echo $this->Form->button('Update <i class="fa fa-arrow-circle-right"></i>', array('type' => 'submit', 'class' => 'btn btn-bricky pull-right','style'=>'margin-right:15px'));
                            ?>
                            </div>
                        </div>
                    </div>

                </div>
            
        </div>

            </div>
        </div>
        <!-- end: FORM WIZARD PANEL -->
    </div>
</div>