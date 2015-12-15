<?php $this->Paginator->options(array('update' => '.panel-body','evalScripts' => true)); ?>
 <?php echo $this->Paginator->options(array('url' => array("perpage"=>$perpage,"search"=>$search,'sort'=> $this->Session->read('sort'),'direction'=> $this->Session->read('direction'))));
?>
<table id="sample-table-1" class="table table-hover">
<thead>
    <tr>
        <th class="center">S.No.</th>
        <th><?php echo $this->Paginator->sort('id', 'Group Name'); ?></th>
        <th><?php echo $this->Paginator->sort('group_type', 'Group Type'); ?></th>
        <th><?php echo $this->Paginator->sort('total_member','No. of Member(s)');?></th>
        <th><?php echo $this->Paginator->sort('User.username', 'Created By'); ?></th>
        <th><?php echo $this->Paginator->sort('meeting_time', 'Meeting Time'); ?></th>
        <th>Leader</th>
        <th>Co-Leader</th>
        <th style="text-align: center">Action</th>
    </tr>
</thead>
<tbody id="GroupContent">
<?php
    if (!empty($groups)) {
    $deleteUrl = 'admin/groups/delete/';
    foreach ($groups as $group) {
    $groupId = $group['Group']['id'];
    $createdDate=date("m-d-Y", strtotime($group['Group']['created']));
    //$modifiedDate=date("d-m-Y", strtotime($group['Group']['modified']));
?>
    <tr>
        <td class="center"><?php echo $counter;?></td>
        <td><?php echo Configure::read('GROUP_PREFIX').' '.$this->Encryption->decode($group['Group']['id']); ?></td>
        <td class="hidden-xs"><?php echo ucfirst($group['Group']['group_type']); ?></td>
        <td><?php echo ($group['Group']['total_member']!='')?ucfirst($group['Group']['total_member']):'-'; ?></td>
        <td><?php echo (!empty($group['User']['username'])) ? ucfirst($group['User']['username']) : "-";?></td>
        <td>
        <?php 
	        $timezone = explode(' ',$group['Group']['timezone_id']);
	        $slot = $this->Adobeconnect->getSlotTimes($group['AvailableSlots']['slot_id']);
	        if(!empty($group['Group']['timezone_id'])) { 
	            echo $slot.' '.$timezone[0]; 
	        } else {
				echo $slot; 
	        }
        ?>
        </td>
        <td><?php echo ($group['Group']['group_leader_id']!=NULL)?'Yes':'No';?></td>
        <td><?php echo ($group['Group']['group_coleader_id']!=NULL)?'Yes':'No';?></td>
        <td class="center">
            <div class="visible-md visible-lg hidden-sm hidden-xs">
                <?php
                echo $this->Html->link('<i class="clip-search-2"></i>', array('controller'=>'groups','action'=>'admin_view',$group['Group']['id']), array('class' => 'btn btn-xs btn-teal tooltips', 'data-original-title' => 'View', 'data-placement' => 'top', 'escape' => false));
                //echo '&nbsp;';
                //echo $this->Html->link('<i class="fa fa-edit"></i>', array('controller' => 'groups', 'action' => 'admin_edit', $groupId), array('class' => 'btn btn-xs btn-teal tooltips', 'data-original-title' => 'Edit', 'data-placement' => 'top', 'escape' => false));
                echo '&nbsp;';
                echo $this->Html->link('<i class="fa fa-times fa fa-white"></i>', 'javascript:void(0)', 
                  array(
                    'class' => 'btn btn-xs btn-bricky tooltips delete',
                    'data-original-title' => 'Delete',
                    'data-toggle' => 'modal',
                    'data-backdrop'=>'static',
                    'data-placement' => 'top',
                    'data-target' => '#popup',
                    'onclick'=>"popUp('".$deleteUrl."','".$groupId."')",'escape' => false
                    ));
                    ?>
            </div>
            <div class="visible-xs visible-sm hidden-md hidden-lg">
                    <div class="btn-group">
                        <?php
                        echo $this->Html->link('<i class="fa fa-cog"></i> <span class="caret"></span>', '#', array('data-toggle' => 'dropdown', 'class' => 'btn btn-primary dropdown-toggle btn-sm', 'escape' => false));

                        $list = array(
                            $this->Html->link('<i class="clip-search-2"></i> View', array('controller'=>'groups','action'=>'admin_view',$group['Group']['id']), array('class' => 'btn btn-xs', 'data-placement' => 'top', 'escape' => false,'style'=>'text-align:left')),
                            //$this->Html->link('<i class="fa fa-edit"></i> Edit', array('controller' => 'groups', 'action' => 'admin_edit', $groupId), array('class' => 'btn btn-xs', 'data-placement' => 'top', 'escape' => false,'style'=>'text-align:left')),
                            $this->Html->link('<i class="fa fa-times fa fa-white"></i> Delete', 'javascript:void(0)', 
                                    array(
                                        'class' => 'btn btn-xs delete',
                                        'data-toggle' => 'modal',
                                        'data-backdrop'=>'static',
                                        'data-placement' => 'top',
                                        'data-target' => '#popup',
                                        'onclick'=>"popUp('".$deleteUrl."','".$groupId."')",'escape' => false,
                                        'style'=>'text-align:left'
                                        )));
                        echo $this->Html->nestedList($list, array('class' => 'dropdown-menu pull-right', 'role' => 'menu'));
                        ?>
                    </div>
        </div>
        </td>
    </tr>

<?php
$counter++;
}
}else{
    echo "<tr><td colspan='9' style='text-align:center'>No record found.</td></tr>";
}
?>
</tbody>
</table>
<?php 
if($this->Paginator->numbers()){ ?>
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
<?php }?>
<?php echo $this->Js->writeBuffer(); ?>
<script>
$(document).ready(function(){
    $('.delete').hover(function(){
        $('.delete').tooltip('enable');
    });
    $('.activeInactive').hover(function(){
        $('.activeInactive').tooltip('enable');
    });
});
</script>
