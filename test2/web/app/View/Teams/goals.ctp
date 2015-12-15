<?php 
echo $this->Html->script('Front/all');
$disabled=array();
if($bizData['BusinessOwner']['group_role'] == 'participant') {
    $disabled[] = 'group_goals';
    $disabled[] = 'group_member_goals';    
}
$groupGoals = $groupMemberGoals = NULL;
$flag = 0;
?>
<div class="row margin_top_referral_search">
<div class="col-md-9 col-sm-8">
  
               <div class="row"> 
         <div class="col-md-12">
      <div class="referrals_reviews">
            <div class="referrals_reviews_head padd-top0">Goals</div>
            
            <div class="clearfix"></div>
           
            </div>
            </div>
            </div>
         <div class="clearfix">&nbsp;</div>
  
      <div class="row"> 
      <?php
        echo $this->Form->create('Goal',array('url'=>array('controller'=>'teams','action'=>'goals'),'class'=>'goals_form','id'=>'goalsForm','inputDefaults' => array('label' => false,'div' => false,'errorMessage'=>true),'novalidate'=>true));
        ?>
      <div class="col-md-8">
          <div class="Goal_form_head ">Set Goals</div>
        </div>
            
            <div class="col-md-2">
      <div class="Goal_form_head text-center">Target</div>
            </div>
            
            <div class="col-md-2">
      <div class="Goal_form_head text-center">Actual</div>
            </div>
      <div class="col-md-12" style="border-bottom: 1px solid #f2f2f2;margin: 15px 0;"></div>
      <div class="clearfix">&nbsp;</div>
      
            <div class="col-md-8">
      <div class="set_goals">
            
        <span class="Goal_form_head">  Group Goals</span> <br>
<span class="goal_info">Set referral sharing goals for 90 Days</span> 
            </div>
            </div>
            <div class="col-md-2">
            <?php $params = array('placeholder'=>"",'class'=>'form-control','autocomplete'=>false,'type'=>'text');
            if(in_array('group_goals',$disabled)) {
                $params['disabled'] = true;
                $params['value'] = '';
            } else {
                $params['autofocus']=true;
            }
            if (!empty($goupGoalData)) {
                $flag = 1;
                $params['value'] = $goupGoalData['Goal']['goal_value'];
            } else {
                $params['value'] = 0;
            }
            echo $this->Form->input('group_goals',$params);?>
            </div>
            <div class="col-md-2">
            <?php if($actualGoals['group_goals'] == '' || $actualGoals['group_goals'] == NULL) {$atcualGroupGoals = 0;} else {$atcualGroupGoals = $actualGoals['group_goals'];$flag = 1;}?>
            <?php echo $this->Form->input('group_goals_actual',array('placeholder'=>"",'class'=>'form-control','autocomplete'=>false,'disabled'=>true,'value'=>$atcualGroupGoals));?>
            </div>
            
            <div class="clearfix">&nbsp;</div>
           <div class="col-md-8">
      <div class="set_goals">
            
        <span class="Goal_form_head"> Team Member Goals</span> <br>

<span class="goal_info">Set referral sharing goals for each team member for 30 days</span>
           
            </div>
            </div> 
            
            <div class="col-md-2">
            <?php $params = array('placeholder'=>"",'class'=>'form-control','autocomplete'=>false,'type'=>'text');
            if(in_array('group_member_goals',$disabled)) {
                $params['disabled'] = true;
            }
            if (!empty($goupMemberGoalData)) {
                $flag = 1;
                $params['value'] = $goupMemberGoalData['Goal']['goal_value'];
            } else {
                $params['value'] = 0;
            }
            echo $this->Form->input('group_member_goals',$params);?>
            </div>
            <div class="col-md-2">
            <?php if($actualGoals['individual'] == '' || $actualGoals['individual'] == NULL) {$atcualIndividualGoals = 0;} else {$atcualIndividualGoals = $actualGoals['individual'];$flag = 1;}?>
            <?php echo $this->Form->input('group_member_goals_actual',array('placeholder'=>"",'class'=>'form-control','autocomplete'=>false,'disabled'=>true,'value'=>$atcualIndividualGoals));?>
            </div>
            
             <div class="clearfix">&nbsp;</div>
            <div class="col-md-8">
      <div class="set_goals">
            
        <span class="Goal_form_head">Individual Goals </span> <br>

<span class="goal_info">Set your sharing referral target to individual team members for 30 days</span>
           
            </div>
            </div>
               
          <div class="col-md-2">
          <?php $params = array('placeholder'=>"",'class'=>'form-control','autocomplete'=>false,'type'=>'text');
          if (!empty($individualMemberGoalData)) {
              $flag = 1;
              $params['value'] = $individualMemberGoalData['Goal']['goal_value'];
          } else {
                $params['value'] = 0;
            }
        if($bizData['BusinessOwner']['group_role'] == 'participant') {
            $params['autofocus'] = true;
        }
          echo $this->Form->input('individual_goals',$params);?>
    
            </div>
            <div class="col-md-2">
            <?php echo $this->Form->input('individual_goals_actual',array('placeholder'=>"",'class'=>'form-control','autocomplete'=>false,'disabled'=>true,'value'=>$atcualIndividualGoals));?>
   
            </div>
            
            <div class="col-md-12">
            <?php if($flag ==0) {$label = 'Set Goals';} else {$label = 'Update Goals';}?>
            <?php echo $this->Form->button($label, array('type' => 'submit','class'=>'btn btn-sm file_sent_btn pull-right','style'=>'margin-top: 15px;'));?>
            </div>
           <?php echo $this->Form->end();?>
            </div>
        
      </div>
      <?php echo $this->element("Front/loginSidebar",array('tabpage' => 'goals'));?>
    </div>
    <?php echo $this->element('Front/bottom_ads');?>