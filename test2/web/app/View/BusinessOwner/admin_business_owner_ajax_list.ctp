<?php

/**
 * professions listing landing page
 * @author Jitendra
 */
?>

<?php echo $this->Paginator->options(array(
    'url' => array(
        "perpage"=>$perpage,
        "search"=>$search,
        'sort'=> $this->Session->read('sort'),
        'direction'=> $this->Session->read('direction'),
        'profession'=>$professionId,
        'meeting_time'=>$meetingTime,
        'country'=>$countryId,
        'state'=>$stateId
        ),
    'update' => '.panel-body',
    'evalScripts' => true
    ));
?>

<table id="sample-table-1" class="table table-hover">
    <thead>
        <tr>
            <th class="center">S.No.</th>
            <th><?php echo $this->Paginator->sort('fname', 'Member Name'); ?></th>
            <th><?php echo $this->Paginator->sort('Profession.profession_name', 'Profession'); ?></th>
            <th><?php echo $this->Paginator->sort('email', 'Email'); ?></th>
            <th><?php echo $this->Paginator->sort('Country.country_name', 'Country'); ?></th>
            <th><?php echo $this->Paginator->sort('AvailableSlot.slot_id','Meeting Time');?></th>
            <th><?php echo $this->Paginator->sort('Group.first_meeting_date','Meeting Date1');?></th>
            <th><?php echo $this->Paginator->sort('Group.id','Group Name');?></th>
            <th style="text-align: center">Action</th>
        </tr>
    </thead>
    <tbody id="professionContent">
        <?php
        if (!empty($businessOwners)) {
            foreach ($businessOwners as $businessOwner) {
                $businessOwnerId = $businessOwner['BusinessOwner']['id'];
                $createdDate      = date('m-d-Y' , strtotime($businessOwner['BusinessOwner']['created']));
                $modifiedDate      = date('m-d-Y' , strtotime($businessOwner['BusinessOwner']['modified']));
                $groupId=$this->Encryption->decode($businessOwner['Group']['id']);
                ?>
        <tr>
            <td class="center"><?php echo $counter;?></td>
            <td class="hidden-xs"><?php echo ucfirst($businessOwner['BusinessOwner']['fname'])." ".ucfirst($businessOwner['BusinessOwner']['lname']); ?></td>
            <td><?php echo ucfirst($businessOwner['Profession']['profession_name']);?></td>
            <td><?php echo $businessOwner['BusinessOwner']['email'];?></td>                      
            <td><?php echo ucfirst($businessOwner['Country']['country_name']);?></td>
            <td><?php if($groupId) { echo $this->Adobeconnect->getFirstMeetingTime($businessOwner['AvailableSlot']['slot_id']) ; } else { echo '-';}?></td>
            <td><?php if($groupId) { echo date('m-d-Y',strtotime($businessOwner['Group']['first_meeting_date']));} else { echo '-';}?></td>
            <td><?php if($groupId) { echo Configure::read('GROUP_PREFIX').' '.$groupId;} else { echo '-';}?></td>

            <td class="center">
                <div class="visible-md visible-lg hidden-sm hidden-xs">
                                            <?php
                                            echo $this->Html->link('<i class="clip-search"></i>', array('controller' => 'businessOwners', 'action' => 'view', $businessOwnerId,'admin'=>true), array('class' => 'btn btn-xs btn-teal tooltips', 'data-original-title' => 'View', 'data-placement' => 'top', 'escape' => false));
                                            echo '&nbsp;';
                                            /*echo $this->Html->link($this->Html->tag('i', '', array('class' => 'clip-checkmark')), '#', array(
                                                'class' => 'btn btn-xs btn-bricky tooltips deleteProfession',
                                                'data-original-title' => 'Check',
                                                'data-toggle' => 'modal',
                                                'id'=>$businessOwnerId,
                                                'data-target' => '#deleteConfirmation',
                                                'data-id'=>$businessOwnerId,
                                                'data-action' => Router::url(
                                                        array('action' => 'admin_delete', $businessOwnerId)
                                                ),
                                                'escape' => false), false);*/
                                            ?>
                </div>
            </td>
        </tr>

                                <?php
                                $counter++;
                            }
                        }else{
                            echo "<tr><td colspan='8' style='text-align:center'>No record found</td></tr>";
//                          echo "<tr><td colspan='5' style='text-align:center'>No profession has been added yet. Please add a profession</td></tr>";
                        }
                        ?>
    </tbody>
</table>
                <?php

     if($this->Paginator->numbers()){
    ?>

<div class="paging" style="float:right;">
    <ul class="pagination" style="margin:0px;">
        <li>
              <?php echo $this->Paginator->prev(__('Previous',true)); ?>      
        </li>
        <li>
              <?php echo $this->Paginator->numbers(array('separator'=>false)); ?>      
        </li>
        <li>
             <?php echo $this->Paginator->next(__('Next',true)); ?>
        </li>
    </ul>
</div>
                <?php } 
                echo $this->Js->writeBuffer();