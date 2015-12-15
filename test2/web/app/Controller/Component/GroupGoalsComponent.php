<?php
/**
 * Timezone component to manage timezone functions
*/
App::uses('Component', "Controller");
App::uses('CakeTime', 'Utility');

class GroupGoalsComponent extends Component
{

    /**
     * to reset user Goals
     * @param (int) $userID
     * @author Rohan Julka
     */
    function resetUserGoals($userID)
    {
        $model = ClassRegistry::init('ReferralStat');
        $first_day_this_month = date('Y-m-01 H:i:s');
        $last_day_this_month  = date('Y-m-t H:i:s');
        $conditions = array('ReferralStat.sent_from_id' => $userID, 'ReferralStat.created BETWEEN ? AND ?' => array($first_day_this_month,$last_day_this_month));
        $model->deleteAll($conditions,false);
        
        $goalModel = ClassRegistry::init('Goal');
        $deleteConditions = array('Goal.user_id'=>$userID,'Goal.goal_type'=>'individual_goals');
        $goalModel->deleteAll($deleteConditions,false);
    }
    
    /**
     * to reset user Goals while shuffling
     * @param (int) $userID
     * @author Rohan Julka
     */
    function resetUserGoalsShuffling($userID)
    {
        $model = ClassRegistry::init('ReferralStat');
        $conditions = array('ReferralStat.sent_from_id' => $userID);
        $model->deleteAll($conditions,false);
    
        $goalModel = ClassRegistry::init('Goal');
        $deleteConditions = array('Goal.user_id'=>$userID);
        $goalModel->deleteAll($deleteConditions,false);
    }
}