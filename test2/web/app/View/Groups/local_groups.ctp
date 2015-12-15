<?php 

?>
<section class=" contact-detail" id="contact">
  <div class=" container">
    <div class="row">
      <div class="col-md-12">
        <div class="section-header margin0">
          <div class="about_text">
            <h1 class="H-Text-Bold"> Choose a group locally </h1>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!--insert contact detailsection-->
<section>
  <div class="container">
    <div class="row">
      <div class="col-md-12 margin_top50"> <a href="#" class="Create_group pull-right">Create your own group</a> </div>
      <div class="clearfix"> &nbsp; </div>
      <div class="col-md-12 ">
        <div class="content_grey">
          <div class="row">
            <div class="grey_box_bottom col-md-9">
              <div class="search_faq">
                <?php echo $this->Form->create('Group', array('url' => array('controller' => 'groups', 'action' => 'localGroups')));?>
                <?php
                if(isset($ispost)) {?>
                <input type="hidden" class="post" name="post" value="<?php echo $ispost;?>">
                <?php }
                ?>
                
                  <div class="search_faq">
                    <div class="col-xs-3 selectContainer padding_clear">
                    <?php $filter = (!isset($filter2)?'':$filter2);?>
                      <select onchange="checkField(this.value);" name="filter2" class="form-control group_select_whitin filter2">
                        <option value="all">All</option>
                        <option value="Group.zipcode" <?php if($filter == 'Group.zipcode'){echo 'selected';}?>>Zip Code</option>
                        <option  value="Group.location" <?php if($filter == 'Group.location'){echo 'selected';}?>>Location</option>
                        <option value="Group.group_name" <?php if($filter == 'Group.group_name'){echo 'selected';}?>>Group Name</option>
                        <option value="groupownername" <?php if($filter == 'groupownername'){echo 'selected';}?>>Group Owner Name</option>
                      </select>
                    </div>                    
                    <?php $queryValue = (!isset($query)?'':$query);?>
                    <div class="col-md-9">
                    <?php if(isset($filter3)) {
                      $css = 'style = "display : none"';
                      $css2 = 'style = "display : block"';
                      $filter3 = $filter3;
                    } else {
                      $css = 'style = "display : block"';
                      $css2 = 'style = "display : none"';
                      $filter3 = '';
                    }
                      ?>
                      <div id="field" <?php echo $css;?>> 
                      <input type="text" value="<?php echo $queryValue;?>" autocomplete="off" class="title query" id="query" name="query">
                      <i class="fa fa-search search_icon2"></i>
                      </div>
                      <div class="col-xs-3 selectContainer" id="dropdown" <?php echo $css2;?>> 
                        <select name="filter3" class="form-control group_select_whitin filter3">
                          <option value="5" <?php if($filter3 == '5'){echo 'selected';}?>>5 Miles</option>
                          <option value="10" <?php if($filter3 == '10'){echo 'selected';}?>>10 Miles</option>  
                          <option value="25" <?php if($filter3 == '25'){echo 'selected';}?>>25 Miles</option>
                          <option value="50" <?php if($filter3 == '50'){echo 'selected';}?>>50 Miles</option>       
                        </select>
                      </div>
                      
                      <input type="submit" class="button btn_faq search primary" id="buttonsubmit" value="Search">
                    </div>
                  </div>
                <?php echo $this->Form->end(); ?>
              </div>
            </div>
            <div class="grey_box_bottom col-md-3">
              <!-- <form method="get"> -->
                <div class="search_faq">
                  <div class="form-group margin_clear">
                    <label class="col-sm-5 control-label Create_group short_by" for="inputEmail3">Sort By</label>
                    <div class="col-sm-7 padding_clear">
                      <select name="filter" class="form-control group_select filter">
                        <option value="Group.created"> Newest </option>
                        <option value="Group.total_members"> Most Members</option>
                        <option value="Group.meeting_time"> Meeting Time</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div style="display: none;" class="autocomplete" id="forum_search_autocomplete"></div>
              <!-- </form> -->
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-2 margin50">
        <div class="Narrow_search"> Narrow Your Search</div>
        <div class="zipcod">By Meeting Days</div>
        <div class="meeting_option">
           <div class="checkbox"><label><input name="day[]" class="day" type="checkbox" value="Monday"> Monday</label></div>
          <div class="checkbox"><label><input name="day[]" class="day" type="checkbox" value="Tuesday">Tuesday</label></div>
          <div class="checkbox"><label><input name="day[]" class="day" type="checkbox" value="Wednesday"> Wednesday</label></div>
          <div class="checkbox"><label><input name="day[]" class="day" type="checkbox" value="Thursday"> Thursday</label></div>
          <div class="checkbox"><label><input name="day[]" class="day" type="checkbox" value="Friday"> Friday </label></div>
          <div class="checkbox"><label><input name="day[]" class="day" type="checkbox" value="Saturday"> Saturday</label></div>
          <div class="checkbox"><label><input name="day[]" class="day" type="checkbox" value="Sunday"> Sunday</label></div>
      </div>
      <div class="zipcod">By Meeting Days</div>
      <div class="meeting_option">        
        <div class="checkbox"><label><input name="time[]" class="time" value="00:00 - 4:00" type="checkbox"> 00:00 - 4:00</label></div>
        <div class="checkbox"><label><input name="time[]" class="time" value="04:00 - 08:00" type="checkbox"> 04:00 - 08:00</label></div>
        <div class="checkbox"><label><input name="time[]" class="time" value="08:00 - 12:00" type="checkbox"> 08:00 - 12:00</label></div>
        <div class="checkbox"><label><input name="time[]" class="time" value="12:00 - 16:00" type="checkbox"> 12:00 - 16:00</label></div>
        <div class="checkbox"><label><input name="time[]" class="time" value="16:00 - 20:00" type="checkbox"> 16:00 - 20:00</label></div>
        <div class="checkbox"><label><input name="time[]" class="time" value="20:00 - 00:00" type="checkbox"> 20:00 - 00:00</label></div>
      </div>
      <?php
      $this->Js->get('.day,.time,.filter');
      $this->Js->event('change',
        $this->Js->request(array(
          'controller'=>'Groups',
          'action'=>'localGroups'),
        array('async'=>true,
          'update'=>'.ajaxData',
          'dataExpression'=>true,
          'data' => '$(\'.day,.time,.filter,.filter2,.filter3,.query,.post\').serializeArray()',
          'method'=>'post',
          )
        )
        );
      ?>

  </div>
  <div class="col-md-10">
    <div class="row">
      <div class="row  margin50 ajaxData">
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
                <!--<a class="Join_Us_btn" href="#">Details </a>-->
            </div>
          </ul>
        </div>
      <?php
      echo $this->Form->end();
        }
       } else { ?>
       <h1>No Group Found</h1>
    <?php }
      ?>
      </div>
    </div>
  </div>

</div>

</div>
</section>
<script>
function checkField(val) {
  if(val == 'Group.location') {
    $('#field').css('display','none');
    $('#dropdown').css('display','block');
  } else {
    $('#field').css('display','block');
    $('#dropdown').css('display','none');
  }
}
</script>