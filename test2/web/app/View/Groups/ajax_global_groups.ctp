<?php 
      if(!empty($groupData)) { 
        foreach($groupData as $data) { 
            echo $this->Form->create('BusinessOwner',array('id'=>'chooseGroupForm_'.$data['Group']['id'],'type'=>'post','url'=>array('controller'=>'users','action'=>'payment'),'inputDefaults' => array('label' => false,'div' => false,'error'=>true),'novalidate'=>true));
            echo $this->Form->input('group_id',array('type'=>'hidden','value'=>$data['Group']['id']));
    ?>
        <div class="col-sm-4">
          <ul class="list-group Group_Name">
            <li class="list-group-item">Group Name 
              <span class="pull-right Group_head">
                <?php echo $data['Group']['groupName']?>
              </span>
            </li>
            <li class="list-group-item">Group meeting date 
              <span class="pull-right "> 
                <?php echo date('m-d-Y',strtotime($data['Group']['meetingDate']))?>
              </span>
            </li>
            <li class="list-group-item">group Location  
              <span class="pull-right ">
                <?php echo $data['Group']['countryName']?>
              </span>
            </li>
            <li class="list-group-item">Group meeting time  
              <span class="pull-right ">
                <?php echo $data['Group']['meetingTime']?>
              </span>
            </li>
            <li class="list-group-item">No of member in group  
              <span class="pull-right ">
              <?php echo $member = ($data['Group']['members'] == NULL) ? '-' : $data['Group']['members']?> 
              </span>
            </li>
            <div class="Group_Name_hover"> 

                <?php
                 echo $this->Html->link('Join', 'javascript:document.forms["chooseGroupForm_'.$data['Group']['id'].'"].submit();',array('class'=>'Join_Us_btn','escape'=>false));
              
                ?>
            </div>
          </ul>
        </div>
      <?php }
       } else { ?>
       <h1>Doesn't find the suitable group? Click to create a new group! <?php echo $this->Html->link('Create New Group','/groups/createGroup',array('class' => ''));?></h1>
    <?php }