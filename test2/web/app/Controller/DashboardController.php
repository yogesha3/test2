<?php

/**
 * Dashborad controller creating to manage all dashborad actions.
 * PHP 5
 * @author Jitendra Sharma
 * 
 */
class DashboardController extends AppController 
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Dashboard';

    /**
     * Components
     *
     * @var array
     * @access public
     */
    public $components = array(
        'Security','Csv.Csv'
    );
    
    var $helpers = array('Html');
    
    /**
     * Models used by the Controller
     *
     * @var array
     * @access public
     */
    public $uses = array('ReceivedReferral','Webcast','LiveFeed','Review','PrevGroupRecord','Membership','Goal','ReferralStat','UserGroupHistory', 'SendReferral');

    /**
     * callback function on filter
     * @author Jitendra
     */
    public function beforeFilter() 
    {
        parent::beforeFilter();
        $this->Auth->allow('dashboard');
        $this->set('titleForLayout', 'FoxHopr: Dashboard');
        $this->Security->unlockedActions = array('referralStatusChartByGroup','referralStatusChartByTimeFrame','referralProfessionChartByGroup','referralProfessionChartByTimeFrame','currentIndividualReferralChart','liveFeedUpdates','currentGroupReferralChart','dashboard', 'referralActivityChart');        
    }

    /**
     * Filter apply before render
     * @author Jitendra
     */
    public function beforeRender() 
    {
        parent::beforeRender();
        $this->set('modelName', $this->modelClass);
    }
    /**
     * Admin index
     * @author Rohan Julka
     */
    public function admin_index()
    {
        $this->layout = 'admin';
        $this->set('regData',$this->getUserRegHistory());
        $this->set('professionsCount',$this->getProfessionsCount());
        $refStat = array();
        $refStat['received'] = $this->ReceivedReferral->find('count');
        $refStat['sent'] = $this->ReferralStat->find('count');
        if($refStat['received']==0 && $refStat['sent']==0) {
            $refStat = array();
        }
        $this->set(compact('refStat'));
    }
    /**
     * Admin index
     * @author Rohan Julka
     */
    public function getUserRegHistory()
    {
        $curMonth = date("M y");
        $prevMonth = date("M y", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-1 month" ) );
        $firstPrevMonth = date("M y", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-2 month" ) );
        $secPrevMonth = date("M y", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-3 month" ) );
        $thirdPrevMonth = date("M y", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-4 month" ) );
        $forthPrevMonth = date("M y", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-5 month" ) );
        $months = array($forthPrevMonth=>$this->User->find('count',array('conditions'=>array('MONTH(User.created)'=>date('n',strtotime($forthPrevMonth)),'YEAR(User.created)'=>date('Y',strtotime($forthPrevMonth))))),
                $thirdPrevMonth=>$this->User->find('count',array('conditions'=>array('MONTH(User.created)'=>date('n',strtotime($thirdPrevMonth)),'YEAR(User.created)'=>date('Y',strtotime($thirdPrevMonth))))),
                $secPrevMonth=>$this->User->find('count',array('conditions'=>array('MONTH(User.created)'=>date('n',strtotime($secPrevMonth)),'YEAR(User.created)'=>date('Y',strtotime($secPrevMonth))))),
                $firstPrevMonth=>$this->User->find('count',array('conditions'=>array('MONTH(User.created)'=>date('n',strtotime($firstPrevMonth)),'YEAR(User.created)'=>date('Y',strtotime($firstPrevMonth))))),
                $prevMonth=>$this->User->find('count',array('conditions'=>array('MONTH(User.created)'=>date('n',strtotime($prevMonth)),'YEAR(User.created)'=>date('Y',strtotime($prevMonth))))),
                $curMonth=>$this->User->find('count',array('conditions'=>array('MONTH(User.created)'=>date('n',strtotime($curMonth)),'YEAR(User.created)'=>date('Y',strtotime($curMonth))))),
               
        );
        return $months;
    }
    /**
     * Admin index
     * @author Rohan Julka
     */
    public function getProfessionsCount()
    {
        $this->BusinessOwner->virtualFields['professions_count'] = "COUNT('BusinessOwner.*')";
        $this->BusinessOwner->unbindModel(array('belongsTo'=>array('Profession','User','Group','State','Country')));
        $professionsData = $this->BusinessOwner->find('all', array(
                'joins' => array(
                        array(
                                'table' => 'professions',
                                'alias' => 'ProfJoin',
                                'type' => 'INNER',
                                'conditions' => array(
                                        'ProfJoin.id = BusinessOwner.profession_id'
                                )
                        )
                ),
                'conditions' => array('BusinessOwner.profession_id !='=>NULL ),
                'fields' => array('ProfJoin.profession_name', 'BusinessOwner.professions_count'),
                'order' => "BusinessOwner.professions_count DESC",
                'group' => 'BusinessOwner.profession_id',
                'limit' => 5
        ));
        if(!empty($professionsData)) {
            return $professionsData;
        } else {
            return false;
        }
    }
    /**
     * Show user(BusinessOwner) dashboard
     * @author Jitendra Sharma
     * @access public
     */
    public function dashboard()
    {
    	$this->layout = 'front';
    	$titleForLayout = "Profile: Customize Your Information";
    	$loginUserId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
    	$loginGroupId = $this->Session->read('Auth.Front.BusinessOwners.group_id');
        $checkSession = $this->Session->read('UID');
        if (!empty($checkSession) && empty($loginUserId)) {
        	$userSessionInfo = $this->User->userInfoById($this->Encryption->decode($checkSession));
            if (empty($userSessionInfo['BusinessOwners']['group_id'])) {
                $this->Session->setFlash(__('This group is no longer available. Please select another group.'),'Front/flash_bad');
                $this->redirect(array('controller' => 'groups', 'action' => 'group-selection'));
            } else {
                $this->Session->setFlash(__('You have already joined a group.'),'Front/flash_bad');
                $this->redirect(array('controller' => 'users', 'action' => 'login'));
            }
        } elseif (!empty($loginUserId) && empty($loginGroupId)) {
            $this->Session->setFlash(__('This group is no longer available. Please select another group.'),'Front/flash_bad');
            $this->redirect(array('controller' => 'groups', 'action' => 'group-selection'));
        } elseif (!empty($loginUserId) && !empty($loginGroupId)) {
			$this->Session->delete('UID');
			// get all group list of login user (current and previous groups)
			$userData = $this->User->userInfoById($loginUserId);
			$groupId = $this->Session->read('Auth.Front.BusinessOwners.group_id');
			$groupList[$groupId] = "Group ".$groupId;
			$previousGroupList = $this->PrevGroupRecord->getUserPreviousGroup($loginUserId);
			// concat previous group 
			$groupList = $groupList + $previousGroupList;
			$this->set(compact('groupList','groupId'));
			
			// get profile complition status
			$percentage = $this->Businessowner->getProfileStatus($loginUserId);
			$this->set(compact('percentage'));
			
			// get latest webcast
			$latestWebcast = $this->Webcast->getWebcastData();
			$this->set(compact('latestWebcast'));

		    // get average review
		    $totalReview = $this->Review->getTotalReviewByUserId($loginUserId);
		    $totalReviewAverage = $totalReview*3;
		    $totalAvgRatingArr = $this->Review->getAverage($loginUserId);
		    if(!empty($totalAvgRatingArr)) {
		        $totalAvgRating = round($totalAvgRatingArr/$totalReviewAverage);            
		    } else {
		        $totalAvgRating = 0;
		    }
		    //echo $totalAvgRating;die;
		    $membershipData = $this->Membership->find('all');
		    $daylen = 60*60*24;
		    $diff = round((time()-strtotime($membershipData[0]['Membership']['modified']))/$daylen);
		    $membershipUpdated = false;
		    if($diff >=0){
		        $membershipUpdated = true;
		    }
		    $levelMessageViewed = false;
		    if($userData['BusinessOwners']['is_level_message_viewed'] == 1 || $userData['BusinessOwners']['is_level_message_viewed'] == NULL) {
		        $levelMessageViewed = true;
		    }
		    $level = "";
		    $referralCount = $this->ReferralStat->find('count',array('conditions'=>array('ReferralStat.sent_from_id'=>$loginUserId)));
		    if ( $referralCount>=$membershipData[0]['Membership']['lower_limit'] && $referralCount<=$membershipData[0]['Membership']['upper_limit']) {
		        $level = 'Bronze';
		    } elseif( $referralCount>=$membershipData[1]['Membership']['lower_limit'] && $referralCount<=$membershipData[1]['Membership']['upper_limit']) {
		        $level = 'Silver';
		    } elseif( $referralCount>=$membershipData[2]['Membership']['lower_limit'] && $referralCount<=$membershipData[2]['Membership']['upper_limit']) {
		        $level = 'Gold';
		    } elseif( $referralCount>=$membershipData[2]['Membership']['lower_limit'] ) {
		        $level = 'Platinum';
		    }
		    $this->set(compact('totalAvgRating','totalReview','membershipUpdated','membershipData','level','levelMessageViewed'));
		} else {
			$this->Session->setFlash('Thanks for being the part of FoxHopr community.','Front/flash_good');
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
		}
    }
    
    /**
     * Show Referral Status Graph of Groups
     * @author Jitendra Sharma
     * @access public
     */
    public function referralStatusChartByGroup()
    {
    	$this->layout = 'ajax';
    	$referrar = explode("/",$this->referer());
    	$referrar = end($referrar);
    	if($this->request->is('post') && $referrar=="dashboard"){    		
    		$searchEntity 	= $this->request->data['search_entity'];
    		$entityValue 	= $this->request->data['entity_val'];
    		
    		if($entityValue = '') {
    		    $graphInfo 		= $this->getGraph3Data($searchEntity,$entityValue);
    		} else {
    		    $graphInfo = array();
    		}	
    		$this->set(compact('graphInfo','searchEntity')); 
    	}else{
    		echo "Invalid request!";die;
    	}    	  	
    }
    
    /**
     * Show Referral Status Graph according Time Frame
     * @author Jitendra Sharma
     * @access public
     */
    public function referralStatusChartByTimeFrame()
    {
    	$this->layout = 'ajax';
    	$referrar = explode("/",$this->referer());
    	$referrar = end($referrar);
    	if($this->request->is('post') && $referrar=="dashboard"){
    		$searchEntity 	= $this->request->data['search_entity'];
    		$fromTimeValue 	= $this->request->data['from_time_val'];
    		$toTimeValue 	= $this->request->data['to_time_val'];    
    		$graphInfo 		= $this->getGraph3Data($searchEntity,null,$fromTimeValue,$toTimeValue);
    		$this->set(compact('graphInfo','searchEntity'));
    		$this->render('referral_status_chart_by_group');
    	}else{
    		echo "Invalid request!";die;
    	}    
    }
    
    /**
     * Show Referral By Profession Graph of Groups
     * @author Jitendra Sharma
     * @access public
     */
    public function referralProfessionChartByGroup()
    {
    	$this->layout = 'ajax';
    	$referrar = explode("/",$this->referer());
    	$referrar = end($referrar);
    	if($this->request->is('post') && $referrar=="dashboard"){
    		$searchEntity 	= $this->request->data['search_entity'];
    		$entityValue 	= $this->request->data['entity_val'];
    		if($entityValue!='') {
    		    $graphInfo = $this->getGraph4Data($searchEntity,$entityValue);
    		} else {
    		    $graphInfo = array();
    		} 		
    		$this->set(compact('graphInfo','searchEntity'));    		    		 
    	}else{
    		echo "Invalid request!";die;
    	}    
    }
    
    /**
     * Show Referral By Profession Graph of selected Time Frame
     * @author Jitendra Sharma
     * @access public
     */
    public function referralProfessionChartByTimeFrame()
    {
    	$this->layout = 'ajax';    	
    	$referrar = explode("/",$this->referer());
    	$referrar = end($referrar);
    	if($this->request->is('post') && $referrar=="dashboard"){
    		$searchEntity 	= $this->request->data['search_entity'];
    		$fromTimeValue 	= $this->request->data['from_time_val'];
    		$toTimeValue 	= $this->request->data['to_time_val'];    
    		$graphInfo 		= $this->getGraph4Data($searchEntity,null,$fromTimeValue,$toTimeValue);    		
    		$this->set(compact('graphInfo','searchEntity'));
    		$this->render('referral_profession_chart_by_group'); 
    	}else{
    		echo "Invalid request!";die;
    	}
    }
    
    /**
     * SHOW CURRENT INDIVIDUAL REFERRAL GOALS VS ACTUAL ACTIVITY FOR CURRENT GROUP
     * @author Jitendra Sharma
     * @access public
     */
    public function currentIndividualReferralChart()
    {
    	$this->layout = 'ajax';
    	$referrar = explode("/",$this->referer());
    	$referrar = end($referrar);
    	if($this->request->is('post') && $referrar=="dashboard"){
    		$entityValue 		= $this->request->data['entity_val'];
    		$targetIndividualGraphInfo 	= $this->getGoalsGraph($entityValue,'individual','targetData');
    		$actualIndividualGraphInfo 	= $this->getGoalsGraph($entityValue,'individual','actualData');
    		$this->set(compact('targetIndividualGraphInfo','actualIndividualGraphInfo'));
    	}else{
    		echo "Invalid request!";die;
    	}
    }
    
    /**
     * SHOW CURRENT GROUP REFERRAL GOALS VS ACTUAL ACTIVITY FOR CURRENT GROUP
     * @author Rohan Julka
     * @access public
     */
    public function currentGroupReferralChart()
    {
        $this->layout = 'ajax';
        $referrar = explode("/",$this->referer());
        $referrar = end($referrar);
        if($this->request->is('post') && $referrar=="dashboard"){
            $entityValue 		= $this->request->data['entity_val'];
            $targetGroupGraphInfo 	= $this->getGroupGoalsGraph($entityValue,'group','targetData');
            $actualGroupGraphInfo 	= $this->getGroupGoalsGraph($entityValue,'group','actualData');
            $this->set(compact('targetGroupGraphInfo','actualGroupGraphInfo'));
        }else{
            echo "Invalid request!";die;
        }
    }
    
    
    /**
     * To get the graph3 (REFERRALS BY STATUS) data
     * @params string $searchEntity entiry for which graph draw ( i.e. 1- Number of received referrals, 2- value of received referrals)
     * @params array $entityValue group ids ( if graph show for GROUPS)
     * @params string $fromTimeValue starting month in date range selected( if graph show for Time Frame)
     * @params string $toTimeValue Last month in date range selected( if graph show for Time Frame)
     * @return array graph3 data
     * @author Jitendra Sharma
     * @access private
     */
    function getGraph3Data($searchEntity=null,$entityValue=null,$fromTimeValue=null,$toTimeValue=null){
    	$loginUserId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
    	if($searchEntity==1){
    		$fields = array('ReceivedReferral.referral_status','COUNT(ReceivedReferral.id) AS referralCount');
    	}elseif($searchEntity==2){
    		$fields = array('ReceivedReferral.referral_status','SUM(ReceivedReferral.monetary_value) AS referralCount');
    	}
    	
    	if($entityValue!=null){
    		$conditions = array('ReceivedReferral.to_user_id'=>$loginUserId, 'ReceivedReferral.group_id'=>$entityValue, 'ReceivedReferral.is_archive' => 0);
    	}else{
    		$fromDateStr = explode(",",$fromTimeValue);
    		$toDateStr 	 = explode(",",$toTimeValue);
    		$fromDate 	 = $fromDateStr[1]."-".$fromDateStr[0]."-"."01 00:00:00";
    		$toDate 	 = $toDateStr[1]."-".$toDateStr[0]."-"."31 23:59:59";
    		$conditions = array('ReceivedReferral.to_user_id'=>$loginUserId, 'ReceivedReferral.created >='=>$fromDate, 'ReceivedReferral.created <='=>$toDate, 'ReceivedReferral.is_archive' => 0);
    	}
    	
    	$this->ReceivedReferral->recursive = 0;
    	$graphInfo = $this->ReceivedReferral->find('all', array('fields' => $fields, 'conditions' => $conditions,'group' => 'ReceivedReferral.referral_status' ,'order' => 'ReceivedReferral.referral_status ASC'));
    	//pr($graphInfo);
    	$graphInfo = Set::combine($graphInfo, '{n}.ReceivedReferral.referral_status', '{n}.0.referralCount');
    	return $graphInfo;
    }
    
    /**
     * To get the graph4 (REFERRALS BY PROFESSION) data
     * @params string $searchEntity entiry for which graph draw ( i.e. 1- Number of received referrals, 2- value of received referrals)
     * @params array $entityValue group ids ( if graph show for GROUPS)
     * @params string $fromTimeValue starting month in date range selected( if graph show for Time Frame)
     * @params string $toTimeValue Last month in date range selected( if graph show for Time Frame)
     * @return array graph4 data
     * @author Jitendra Sharma
     * @access private
     */
    function getGraph4Data($searchEntity=null,$entityValue=null,$fromTimeValue=null,$toTimeValue=null){
    	$loginUserId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
    	if($searchEntity==1){
    		$fields = array('ReceivedReferral.job_title','COUNT(ReceivedReferral.id) AS referralCount');
    		$orderByValue = array('COUNT(ReceivedReferral.id) DESC','ReceivedReferral.job_title ASC');
    	}elseif($searchEntity==2){
    		$fields = array('ReceivedReferral.job_title','SUM(ReceivedReferral.monetary_value) AS referralCount');
    		$orderByValue = array('SUM(ReceivedReferral.monetary_value) DESC','ReceivedReferral.job_title ASC');
    	}
    	
    	if($entityValue!=null){
    		$conditions = array('ReceivedReferral.to_user_id'=>$loginUserId, 'ReceivedReferral.group_id'=>$entityValue, 'ReceivedReferral.is_archive' => 0, 'ReceivedReferral.job_title !=' => "");
    	}else{
    		$fromDateStr = explode(",",$fromTimeValue);
    		$toDateStr 	 = explode(",",$toTimeValue);
    		$fromDate 	 = $fromDateStr[1]."-".$fromDateStr[0]."-"."01 00:00:00";
    		$toDate 	 = $toDateStr[1]."-".$toDateStr[0]."-"."31 23:59:59";    		 
    		$conditions = array('ReceivedReferral.to_user_id'=>$loginUserId, 'ReceivedReferral.created >='=>$fromDate, 'ReceivedReferral.created <='=>$toDate, 'ReceivedReferral.is_archive' => 0, 'ReceivedReferral.job_title !=' => "");
    	}
    	
    	$this->ReceivedReferral->recursive = 0;
    	$graphInfo = $this->ReceivedReferral->find('all', array('fields' => $fields, 'conditions' => $conditions,'group' => 'ReceivedReferral.job_title' ,'order' => $orderByValue, 'limit' => 5));
    	$graphInfo = Set::combine($graphInfo, '{n}.ReceivedReferral.job_title', '{n}.0.referralCount');
    	return $graphInfo;
    }
    
    /**
     * Return Goals graph data with sent referral
     * @author Jitendra Sharma
     * @access public
     * @param int $groupId current group id of user
     * @param string $graphType (individual or group goals)
     * @param string $dataType (target data or actual data)
     * @return array $goalsData
     */
    public function getGoalsGraph($groupId,$graphType,$dataType){
    	$loginUserId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
    	$currentGroupJoin 	= $this->UserGroupHistory->findByUserIdAndGroupId($loginUserId,$groupId,NULL,array('id desc'));
    	$groupJoinDate =  (!empty($currentGroupJoin)) ? $currentGroupJoin['UserGroupHistory']['group_join_date'] : date("Y-m-d 00:00:00");
    	$todayDate     =  date("Y-m-d 23:59:59");
    	
    	$monthList = $this->getMonths($groupJoinDate,$todayDate);
    	$this->Goal->recursive = -1;
    	$this->ReferralStat->recursive = -1;
    	if($dataType=="targetData" && $graphType=="individual"){
    		$conditions = array('Goal.user_id'=>$loginUserId, 'Goal.group_id'=>$groupId, 'Goal.goal_type' => 'individual_goals', 'Goal.created >=' => $groupJoinDate, 'Goal.created <=' => $todayDate);
    		$graphData = $this->Goal->find('all',array('Goal.*','conditions'=>$conditions,'order'=>array('created DESC')));
    		foreach ($graphData as $goal){
    			$monthname = date("M y",strtotime($goal['Goal']['created']));
    			$resultData[$monthname] = $goal['Goal']['goal_value'];
    		}
    	}else if($dataType=="actualData" && $graphType=="individual"){
    		$conditions = array('ReferralStat.sent_from_id'=>$loginUserId, 'ReferralStat.group_id'=>$groupId, 'ReferralStat.created >=' => $groupJoinDate, 'ReferralStat.created <=' => $todayDate);
    		$graphData = $this->ReferralStat->find('all',array('fields'=>array('created , COUNT(id) as referralSent'),'conditions'=>$conditions,'order'=>array('created DESC'),'group'=>'MONTH(created)'));
    		foreach ($graphData as $goal){
    			$monthname = date("M y",strtotime($goal['ReferralStat']['created']));
    			$resultData[$monthname] = $goal['0']['referralSent'];
    		}
    	}
    	foreach ($monthList as $month){
    		$result[$month] = (!empty($resultData[$month])) ? $resultData[$month] : 0;
    	}    	
    	return $result;
    	
    }
    
    /**
     * Return Group Goals graph data with sent referral
     * @author Rohan Julka
     * @access public
     * @param int $groupId current group id of user
     * @param string $graphType (individual or group goals)
     * @param string $dataType (target data or actual data)
     * @return array $goalsData
     */
    public function getGroupGoalsGraph($groupId,$graphType,$dataType) {
        $loginUserId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
        $userData = $this->User->userInfoById($loginUserId);
        $groupLeader = $userData['Groups']['group_leader_id'];
        $groupCoLeader = $userData['Groups']['group_coleader_id'];
        // Calciulation based on group goals
        
        $groupCreated = $userData['Groups']['created'];
        
        $now = time(); // or your date as well
        $your_date = strtotime($groupCreated);
        $datediff = $now - $your_date;
        $shufflingDate = date('Y-m-d 23:59:59',strtotime($userData['Groups']['shuffling_date']));
        $datediff = floor($datediff/(60*60*24));
        if($datediff<90) {
            $groupGoalStartDate = $groupCreated;
        } else {
            $groupGoalStartDate = $shufflingDate;
        }
        $groupGoalEndDate = date('Y-m-d H:i:s',strtotime("+3 months", strtotime($groupGoalStartDate)));
        // Calciulation based on group goals Ends
        $first_day_of_current_month = date('Y-m-01 00:00:00');        
        $currentGroupJoin 	= $this->UserGroupHistory->findByUserIdAndGroupId($loginUserId,$groupId,NULL,array('id desc'));
        //echo $this->Encryption->decode($currentGroupJoin['UserGroupHistory']['id']);
        $groupJoinDate =  (!empty($currentGroupJoin)) ? date("Y-m-d 00:00:00",strtotime($currentGroupJoin['UserGroupHistory']['group_join_date'])) : date("Y-m-d 00:00:00");
        $todayDate     =  date("Y-m-d 23:59:59");
        $date_diff=strtotime($todayDate)-strtotime($groupJoinDate);
        $monthsDiff = floor(($date_diff)/2628000);
        if($monthsDiff>=2) {
            $monthList = array(date('M y',strtotime('-2 months')),date('M Y',strtotime('-1 months')),date('M Y',strtotime($first_day_of_current_month)));
        } elseif($monthsDiff==1) {
            $monthList = array(date('M y',strtotime('-1 months')),date('M Y',strtotime($first_day_of_current_month)));
        } elseif($monthsDiff==0) {
            $monthList = array(date('M y',strtotime($first_day_of_current_month)));
        }
        foreach($monthList as $month) {
            $first_day_of_month = date('Y-m-01 00:00:00',strtotime($month));
            $last_day_of_month = date('Y-m-t 23:59:59',strtotime($month));
            if($dataType=="targetData" && $graphType=="group") {
                $conditions = array('Goal.user_id'=>$groupLeader, 'Goal.group_id'=>$groupId, 'Goal.goal_type' => 'group_goals','Goal.created BETWEEN ? AND ?'=>array($groupGoalStartDate,$groupGoalEndDate));
                $targetGoals = $this->Goal->find('first',array('conditions'=>$conditions));
                if(!empty($targetGoals)) {
                    $resultData[$month] = $targetGoals['Goal']['goal_value'];
                } else {
                    $resultData[$month] = 0;
                }
                
            } else if($dataType=="actualData" && $graphType=="group") {
                //Actual Group Goals
                $groupData = $this->BusinessOwner->find('all',array('conditions'=>array('BusinessOwner.group_id'=>$groupId)));
                $count = 0;
                foreach ($groupData as $row) {
                    $count+= $this->ReferralStat->find('count',array('conditions'=>array('sent_from_id'=>$row['BusinessOwner']['user_id'],'ReferralStat.group_id'=>$groupId,'ReferralStat.created BETWEEN ? AND ?' => array($first_day_of_month,$last_day_of_month))));
                }
                $resultData[$month] = $count;
            } 
            $result = array();   
            foreach ($monthList as $month){
                $result[$month] = (!empty($resultData[$month])) ? $resultData[$month] : 0;
            }            
        }
        return $result;
         
    }
    
    /**
     * function to print month names between to two date range
     * @param $startdate, $enddate
     * @author Jitendra Sharma
     */
    function getMonths($date1, $date2) {
    	$time1 = strtotime ( $date1 );
    	$time2 = strtotime ( $date2 );
    	$my = date ( 'mY', $time2 );
    	$months = array (
    			date ( 'M y', $time1 )
    	);    
    	while ( $time1 < $time2 ) {
    		$time1 = strtotime ( date ( 'Y-m-d', $time1 ) . ' +1 month' );
    		if (date ( 'mY', $time1 ) != $my && ($time1 < $time2))
    			$months [] = date ( 'M y', $time1 );
    	}    
    	$months [] = date ( 'M y', $time2 );
    	return $months;
    }
    
    /**
     * Show live feed updates on right bar in dashboard
     * @author Jitendra Sharma
     * @access public
     */
    public function liveFeedUpdates()
    {
    	$this->layout = 'ajax';
    	$loginUserId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
        $loginUserGroupId = $this->Encryption->decode($this->Session->read('Auth.Front.Groups.id'));
    	$referrar = explode("/",$this->referer());
    	$referrar = end($referrar);
    	if($this->request->is('post') && $referrar=="dashboard"){
    		$this->LiveFeed->recursive = 0;
    		$startDate = date('Y-m-d 00:00:00');
    		$endDate = date('Y-m-d 23:59:59');
    		$liveFeeds = $this->LiveFeed->find('all', array('conditions' => array('LiveFeed.to_user_id'=>$loginUserId,'LiveFeed.created >=' => $startDate,'LiveFeed.created <=' => $endDate,'LiveFeed.group_id'=>$loginUserGroupId),'order' => 'LiveFeed.created DESC'));
    		if(!empty($liveFeeds)){
	    		$liveUpdates = "";
	    		foreach($liveFeeds as $feed){ 
                    if($feed['LiveFeed']['feed_type'] == 'review') {
                        $referralData = $this->ReceivedReferral->getInfoByReferralId($feed['LiveFeed']['from_user_id']);
                        $member_name = $referralData['ReceivedReferral']['first_name'].' '. $referralData['ReceivedReferral']['last_name'];
                    } else {
                        $member_name = $this->Businessowner->getBusinessOwnerNameById($feed['LiveFeed']['from_user_id']);
                    }	    			  		
	    			switch($feed['LiveFeed']['feed_type']){
	    				case 'event' :
	    					$feed_msg = EVENTFEED;
	    					break;
	    				case 'message' :
	    					$feed_msg = MSGFEED;
	    					break;
	    				case 'newmember' :
	    					$feed_msg = NEWMEMBERFEED;
	    					break;
	    				case 'referral' :
	    					$feed_msg = REFERRARFEED;
	    					break;
	    				case 'review' :
	    					$feed_msg = REVIEWFEED;
	    					break;
	    			}
	    			$liveUpdates .= '<div class="event_text">'.$member_name.' '.$feed_msg.'</div>
	    			<div class="border_head">
	    				<div class="text_head">&nbsp;</div>
	    			</div>
	    			<div class="clearfix"></div>';    			
	    		}
	    		echo $liveUpdates;die;
    		}else{
    			echo "No recent updates.";die;
    		}
    	}else{
    		echo "Invalid request!";die;
    	}
    
    }
    /**
     * Referral Activity chart on dashboard
     * @author Rohan Julka
     * @access public
     */
    public function referralActivityChart()
    {
        
        $loginUserId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
        $donut = array();
        $searchCases = array('sent', 'received', 'both');
        if(in_array($this->request->data['search_entity'], $searchCases)) { 
            if($this->request->data['from_time_val1'] !='' && $this->request->data['to_time_val1']!='' && $this->request->data['from_time_val2']!='' && $this->request->data['to_time_val2']!='') {
                //First Time frame
                $fromDateStr1 = explode(",", $this->request->data['from_time_val1']);
                $toDateStr1 	 = explode(",", $this->request->data['to_time_val1']);
                $fromDate1 	 = $fromDateStr1[1]."-".$fromDateStr1[0]."-"."01 00:00:00";
                $toDate1	 = $toDateStr1[1]."-".$toDateStr1[0]."-"."31 23:59:59";        
                // Second Timeframe
                $fromDateStr2 = explode(",",$this->request->data['from_time_val2']);
                $toDateStr2 	 = explode(",",$this->request->data['to_time_val2']);
                $fromDate2 	 = $fromDateStr2[1]."-".$fromDateStr2[0]."-"."01 00:00:00";
                $toDate2	 = $toDateStr2[1]."-".$toDateStr2[0]."-"."31 23:59:59";
                
                $donut['timeframe1']['label'] =  date("M y", strtotime($fromDate1) ).'-'.date("M y", strtotime($toDateStr1[1]."-".$toDateStr1[0]." 23:59:59") );
                $donut['timeframe1']['color'] = "#46BFBD";
                $donut['timeframe1']['highlight'] = "#5AD3D1";
                
                $donut['timeframe2']['label'] =  date("M y", strtotime($fromDate2) ).'-'.date("M y", strtotime($toDateStr2[1]."-".$toDateStr2[0]." 23:59:59") );
                $donut['timeframe2']['color'] = "#FDB45C";
                $donut['timeframe2']['highlight'] = "#FFC870";
                switch($this->request->data['search_entity']) {                    
                    case 'received':
                        $this->ReceivedReferral->recursive = 0;
                        $conditions1 = array('ReceivedReferral.to_user_id'=>$loginUserId, 'ReceivedReferral.created >='=>$fromDate1, 'ReceivedReferral.created <='=>$toDate1, 'ReceivedReferral.is_archive' => 0);
                        $conditions2 = array('ReceivedReferral.to_user_id'=>$loginUserId, 'ReceivedReferral.created >='=>$fromDate2, 'ReceivedReferral.created <='=>$toDate2, 'ReceivedReferral.is_archive' => 0);
                        $donut['timeframe1']['value'] = $this->ReceivedReferral->find('count',array('conditions'=>$conditions1));
                        $donut['timeframe2']['value'] = $this->ReceivedReferral->find('count',array('conditions'=>$conditions2));
                        break;
                    case 'sent':
                        $this->ReferralStat->recursive = 0;
                        $conditions1 = array('ReferralStat.sent_from_id'=>$loginUserId, 'ReferralStat.created >='=>$fromDate1, 'ReferralStat.created <='=>$toDate1);
                        $conditions2 = array('ReferralStat.sent_from_id'=>$loginUserId, 'ReferralStat.created >='=>$fromDate2, 'ReferralStat.created <='=>$toDate2);
                        $donut['timeframe1']['value'] = $this->ReferralStat->find('count',array('conditions'=>$conditions1));
                        $donut['timeframe2']['value'] = $this->ReferralStat->find('count',array('conditions'=>$conditions2));
                        break;
                    case 'both':
                        $this->ReceivedReferral->recursive = 0;
                        $this->ReferralStat->recursive = 0;
                        //Received
                        $conditions1 = array('ReceivedReferral.to_user_id'=>$loginUserId, 'ReceivedReferral.created >='=>$fromDate1, 'ReceivedReferral.created <='=>$toDate1, 'ReceivedReferral.is_archive' => 0,);
                        $conditions2 = array('ReceivedReferral.to_user_id'=>$loginUserId, 'ReceivedReferral.created >='=>$fromDate2, 'ReceivedReferral.created <='=>$toDate2, 'ReceivedReferral.is_archive' => 0,);
                        $donut['timeframe1']['value'] = $this->ReceivedReferral->find('count',array('conditions'=>$conditions1));
                        $donut['timeframe2']['value'] = $this->ReceivedReferral->find('count',array('conditions'=>$conditions2));
                        //Sent
                        $conditions1 = array('ReferralStat.sent_from_id'=>$loginUserId, 'ReferralStat.created >='=>$fromDate1, 'ReferralStat.created <='=>$toDate1);
                        $conditions2 = array('ReferralStat.sent_from_id'=>$loginUserId, 'ReferralStat.created >='=>$fromDate2, 'ReferralStat.created <='=>$toDate2);
                        $donut['timeframe1']['value']+= $this->ReferralStat->find('count',array('conditions'=>$conditions1));
                        $donut['timeframe2']['value']+= $this->ReferralStat->find('count',array('conditions'=>$conditions2));
                        break;
                }
                if($donut['timeframe1']['value'] == 0 && $donut['timeframe2']['value'] == 0) {
                    $donut = array();
                }
            }
        }
        
        $this->set(compact('donut'));        
        //pr($donut);exit;
    }
}