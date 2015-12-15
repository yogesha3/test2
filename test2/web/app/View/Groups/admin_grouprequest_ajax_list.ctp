<?php $this->Paginator->options(array('update' => '.panel-body','evalScripts' => true)); ?>
<?php echo $this->Paginator->options(array('url' => array("perpage"=>$perpage,"search"=>$search,'sort'=> $this->Session->read('sort'),'direction'=> $this->Session->read('direction'))));
?>
<table id="sample-table-1" class="table table-hover">
  <thead>
    <tr>
      <th class="center">S.No.</th>
      <th><?php echo $this->Paginator->sort('id', 'Group Name'); ?></th>
      <th><?php echo $this->Paginator->sort('group_type', 'Group Type'); ?></th>
      <th><?php echo $this->Paginator->sort('total_members','No. of Member(s)');?></th>
      <th><?php echo $this->Paginator->sort('created', 'Created Date'); ?></th>
      <th>Leader</th>
      <th>Co-Leader</th>
      <th style="text-align: center">Action</th>
    </tr>
  </thead>
  <tbody id="GroupContent">
    <?php
    if (!empty($groups)) {
      $approveUrl = 'admin/groups/groupApprove/';
      foreach ($groups as $group) {
        $groupId = $group['Group']['id'];
        $createdDate=date("m-d-Y", strtotime($group['Group']['created']));
        $modifiedDate=date("m-d-Y", strtotime($group['Group']['modified']));
        ?>
        <tr>
          <td class="center"><?php echo $counter;?></td>
          <td><?php echo Configure::read('GROUP_PREFIX').' '.$this->Encryption->decode($group['Group']['id']); ?></td>
          <td><?php echo ucfirst($group['Group']['group_type']); ?></td>
          <td><?php echo ($group['Group']['total_member']!='')?ucfirst($group['Group']['total_member']):'-';; ?></td>
          <td><?php echo $createdDate;?></td>
          <td><?php echo ($group['Group']['group_leader_id']!=NULL)?'Yes':'No';?></td>
          <td><?php echo ($group['Group']['group_coleader_id']!=NULL)?'Yes':'No';?></td>
          <td class="center">
			<div class="visible-md visible-lg hidden-sm hidden-xs">
            <?php                                             
            echo $this->Html->link('<i class="fa fa-square-o"></i>', 'javascript:void(0)', 
              array(
                'class' => 'btn btn-xs btn-teal tooltips activeInactive',
                'data-original-title' => 'Approve',
                'data-toggle' => 'modal',
                'data-backdrop'=>'static',
                'data-placement' => 'top',
                'data-id'=>$groupId,
                'data-target' => '#popup',
                'onclick'=>"popUp('".$approveUrl."','".$groupId."')",'escape' => false
                ));
                ?>
				</div>
              </td>
            </tr>

            <?php
            $counter++;
          }
        }else{
          echo "<tr><td colspan='8' style='text-align:center'>No record found.</td></tr>";
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
    $('.activeInactive').hover(function(){
        $('.activeInactive').tooltip('enable');
    });
});
</script>

