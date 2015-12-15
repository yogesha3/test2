<div class="time_table">
<?php $actionUrl = 'groups/popupFunction'; ?>
<?php if($count > 0) { ?>
              <?php 
              $i = 1;
              foreach($groupData as $group) : 
				$colorCode = $group['Group']['groupType'] == 'local' ? 'icon_yellow' : 'icon_blue';
?>
               <!-- Start Here -->             
                <div class="col-md-3 col-sm-4">
                  <div id="ul1" class="pic time_box ">
                    <div class="city_name"><?php echo $group['Group']['stateName'];?> <br>
                      <span><?php echo $group['Group']['countryName'];?></span></div>
                      <div class="day_name"><?php echo date('l',strtotime($group['Group']['meetingDate']));?></div>
                      <div class="day_time"> <?php echo date('h:i A',strtotime($group['Group']['meetingTime']));?></div>
                      <div class="rating_icon"> 
                        <?php 
                        for($j = 0; $j < 20;$j++) {
                          if($j % 10 == 0){
                            echo '<div class="clearfix"></div>';
                          }
                          if($j < $group['Group']['members']) {
                              echo '<i class="fa fa-user '.$colorCode.'"></i> ';
                          } else {
                              echo '<i class="fa fa-user "></i> ';
                          }
                        }
                        ?>      

                      </div>
                        <div class="group_code"><?php echo Configure::read('GROUP_PREFIX').' '.$this->Encryption->decode($group['Group']['id']);?></div>
                        <?php $groupId = $group['Group']['id']; ?>
                         <a class="pic-caption bottom-to-top" href="javascript:void(0)" data-toggle="modal" data-target="#myModal" onclick="popUp('<?php echo $actionUrl;?>', '<?php echo $groupId; ?>','<?php echo 'groups'; ?>', '<?php echo 'selectGroup'; ?>', '<?php echo $this->Session->read('UID'); ?>')" escape = false>
                          <h1 class="pic-title">Enter</h1>
                        </a> 
                        <?php 
                        //echo $this->Form->postLink('<h1 class="pic-title">Enter</h1>', 
                        //  array('action' => 'selectGroup',$group['Group']['id'] ,$this->Session->read('UID')),
                        //  array('escape'=>false, 'onclick' => "alert('check')",'class' => 'pic-caption bottom-to-top')); 
                        ?>
                        <?php 
                        if($group['Group']['groupType'] == 'local') {
                          echo $this->Html->image('local.png',array('alt'=> '','class'=>'local'));
                        } else {
                          echo $this->Html->image('global.png',array('alt'=> '','class'=>'local'));
                        }
                        ?>
                    </div>
                </div>
                <!-- Start Here -->          
                <?php 
                if ($i % 4 == 0 && $i != $count) {
                  ?>
                  <div class="clearfix clearfix_margin"></div> 
                  </div>
                  <div class="time_table">
                  <?php
                }
                ?>                
              <!--Ends Here -->
              <?php $i++; endforeach; ?>
              <div class="clearfix clearfix_margin"></div>
              </div>
              </div>
              <div class="col-md-10 text-center"><?php  echo $this->Paginator->next();?></div>
              
              <?php } else { ?>
                </div>
                <div class="time_table2">
                    <h1>Doesn't find the suitable group? Click to create a new group! <?php echo $this->Html->link('Create New Group','/groups/createGroup',array('class' => ''));?></h1>
                </div>
             <?php }  ?>
             <?php echo $this->Html->script('Front/all'); ?>
<script>
  $(function(){
    var count = '<?php echo $count;?>';
    var checkClassLength = $('.time_table').length;
    if(checkClassLength == 1 && count == 0) {
      $('.time_table').last().hide();
    }

    var $container = $('.ajaxLoading');

  $container.infinitescroll({
    navSelector  : '.next',    // selector for the paged navigation 
    nextSelector : '.next a',  // selector for the NEXT link (to page 2)
    itemSelector : '.time_table',     // selector for all items you'll retrieve
    debug     : false,
    dataType    : 'html',
    //path : ["group-selection/page:","/milesfilter:"+$('.milesfilter').val()+"/"],
    //path : ["group-selection/page:","/group:global/"],
    loading: {
      finishedMsg: 'Thats all folks !!',
      //img: '<?php echo $this->webroot; ?>img/spinner.gif'
    }
    }
  );
  });
</script>

