<?php 
?>
<section class=" contact-detail" id="contact">
  <div class=" container">
    <div class="row">
      <div class="col-md-12">
        <div class="section-header margin0">
          <div class="about_text">
            <h1 class="H-Text-Bold"> Choose a group globally </h1>
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
                <?php echo $this->Form->create('Group', array('url' => array('controller' => 'groups', 'action' => 'globalGroups')));?>
                <?php
                if(isset($ispost)) {?>
                <input type="hidden" class="post" name="post" value="<?php echo $ispost;?>">
                <?php }
                ?>
                
                  <div class="search_faq">
                    <div class="col-xs-3 selectContainer padding_clear">
                    <?php $filter = (!isset($filter2)?'':$filter2);?>
                      <select name="filter2" class="form-control group_select_whitin filter2">
                        <option value="all">All</option>
                        <option value="Group.zipcode" <?php if($filter == 'Group.zipcode'){echo 'selected';}?>>Zip Code</option>
                        <option value="Group.city_id" <?php if($filter == 'Group.city_id'){echo 'selected';}?>>City</option>
                        <option value="Group.state_id" <?php if($filter == 'Group.state_id'){echo 'selected';}?>>State</option>
                        <option value="Group.country_id" <?php if($filter == 'Group.country_id'){echo 'selected';}?>>Country</option>
                        <option value="Group.group_name" <?php if($filter == 'Group.group_name'){echo 'selected';}?>>Group Name</option>
                        <option value="groupownername" <?php if($filter == 'groupownername'){echo 'selected';}?>>Group Owner Name</option>
                      </select>
                    </div>
                    <div class="col-md-9">
                    <?php $queryValue = (!isset($query)?'':$query);?>
                      <input type="text" value="<?php echo $queryValue;?>" autocomplete="off" class="title query" id="query" name="query">
                      <i class="fa fa-search search_icon2"></i>
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
          'action'=>'globalGroups'),
        array('async'=>true,
          'update'=>'.ajaxData',
          'dataExpression'=>true,
          'data' => '$(\'.day,.time,.filter,.filter2,.query,.post\').serializeArray()',
          'method'=>'post',
          )
        )
        );
      ?>
      <?php
      /*$this->Js->get('#buttonsubmit');
      $this->Js->event('click',
        $this->Js->request(array(
          'controller'=>'Groups',
          'action'=>'index'),
        array('async'=>true,
          'update'=>'.ajaxData',
          'dataExpression'=>true,
          'data' => '$(\'.day,.time,.filter,.query,.filter2\').serializeArray()',
          'method'=>'post',
          )
        )
        );*/
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