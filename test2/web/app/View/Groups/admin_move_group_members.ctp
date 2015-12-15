<?php $this->Paginator->options(array('update' => '.panel-body','evalScripts' => true )); ?>
<!-- start: PAGE HEADER -->
<div class="row">
    <div class="col-sm-12">
        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <i class="clip-file"></i>
                <?php echo $this->Html->link('Groups', array('controller' => 'groups', 'action' => 'index', 'admin' => true));?>
            </li>
            <li class="active">Group Member List</li>
           
        </ol>
        <div class="page-header">
            <h1> Group Member List</h1>
        </div>
       
        <!-- end: PAGE TITLE & BREADCRUMB -->
    </div>
</div>
<div class="row">
	
    <div class="col-md-12">
        <!-- start: BASIC TABLE PANEL -->
        <div class="panel panel-default">

            <div class="panel-body" >
                <table id="sample-table-1" class="table table-hover">
                    <thead>
                        <tr>
                            <th class="center">S.No.</th>
                            <th>Member Name</th>
                            <th>Group Type</th>
                            <th>Member Profession</th>
                            <th>Meeting Time</th>
                            <th>Group Available for Re- grouping</th>
                        </tr>
                    </thead>
                    <tbody id="GroupContent">
                        <?php
                        echo $this->Form->create('BusinessOwner',array('url'=>array('controller'=>'groups','action'=>'moveGroupMembers','admin'=>true,$currentGroup['Group']['id'],'id'=>'deleteGroup')));
                        if (!empty($groupMembersData)) {                            
                           echo $this->Form->input('GroupChangeRequest.id', array('value'=>$currentGroup['Group']['id'],'type'=>'hidden'));
                           $deleteUrl = 'admin/groups/delete/';
                           $counter=1;
                           foreach ($groupMembersData as $groupMember) {
                            //pr($groupMember);die;
                               $businessOwnerId=$this->Encryption->encode($groupMember['BusinessOwner']['user_id']);
                               
                               echo $this->Form->input('BusinessOwner.'.$counter.'.id',array('value'=>$businessOwnerId));
                               echo $this->Form->input('BusinessOwner.'.$counter.'.profession_id', array('value'=>$groupMember['BusinessOwner']['profession_id'],'type'=>'hidden'));
                               ?><tr>
                                <td class="center"><?php echo $counter;?></td>
                                <td><?php echo ucfirst($groupMember['BusinessOwner']['fname']." ".$groupMember['BusinessOwner']['lname']); ?></td>
                                <td><?php echo ucfirst($currentGroup['Group']['group_type']); ?></td>
                                <td><?php echo $groupMember['Profession']['profession_name']; ?></td>
                                <td><?php echo date('h:i A',strtotime($currentGroup['Group']['meeting_time']));?></td>
                                <td><?php
                                    $error = '';
                                    if (isset($validate)) {
                                        $error = empty($this->request->data['BusinessOwner'][$counter]['group_id']) ? 'error' : '';
                                    }
                                    echo $this->Form->select('BusinessOwner.'.$counter.'.group_id', $groupMember['BusinessOwner']['Group'],
                                    array(
                                        'label' => false,
                                        'class' => 'form-control '.$error, 
                                        'id' => 'group'.$counter,
                                        'required'=>false,
                                        'onchange' => 'validate("'.$counter.'")',
                                        'empty' => 'Select Group'));
                                    ?>
                               </td>
                                </tr>

                        <?php
                                $counter++;
                            }
                        }else{
                            echo "<tr><td colspan='6' style='text-align:center'>No record found</td></tr>";
                        }
                       
                        ?>
                    </tbody>
                </table>
                <div class="form-group">
                    <div class="col-sm-2 col-sm-offset-10" style="padding-right: 10px; ">
                            <?php echo $this->Form->button('Move <i class="fa fa-arrow-circle-right"></i>', array('type' => 'submit', 'class' => 'btn btn-bricky pull-right submitclick')); ?>
                        </div>
                    </div>
                <?php  echo $this->Form->end();?>
    
            </div>
        </div>
        <!-- end: BASIC TABLE PANEL -->
    </div>
</div>
<script>
   function validate(id)
   {
        var data=$('#group'+id).val();
        if(data != '') {
            $('#group'+id).removeClass('error');
        } else {

            $('#group'+id).addClass('error');
        }
   }
   $("#BusinessOwnerAdminMoveGroupMembersForm").submit(function() {
    var selectEls = document.querySelectorAll('select'),
    numSelects = selectEls.length;
    $('select').removeClass("error");//added this to clear formatting when fixed after alert
    var anyInvalid = false;
    for(var x=0;x<numSelects;x++) {
        if (selectEls[x].value === '') {
            $(selectEls[x]).addClass("error");
            anyInvalid = true;
        }}
        if (anyInvalid) {
            //alert('One or more required fields does not have a choice selected... please check your form');
            return false;
        }
   });
</script>