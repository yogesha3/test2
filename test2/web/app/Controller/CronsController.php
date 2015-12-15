<?php

/**
 * This is a Crons controller to handle all the cron jobs
 *
 */
App::uses('Email', 'Lib');
class CronsController extends AppController 
{
    public $uses = array('BusinessOwner', 'Subscription', 'User', 'Group','PrevGroupRecord','SendReferral','Review','Setting','Goal','Membership','AvailableSlots');
    public $components=array('Adobeconnect');
    public function beforeFilter() 
    {
        parent::beforeFilter();
        $this->autoRender = false;
        $this->autoLayout = false;     
        $this->Auth->allow(array('calculateShufflingPercentage','shufflingEmailNotification','shufflingMissedEmailNotification','deactivateUser','trainingVideoReminderMail','meetingReminder'));
    }

    /**
    * function is used to send reminder mail to leader and co-leader
    * to watch the training video
    * @author Gaurav
    */
    public function trainingVideoReminderMail() 
    {
        $data = $this->BusinessOwner->find('all',array(
                                'conditions'=>array('BusinessOwner.group_role' => array('leader', 'co-leader'),'BusinessOwner.is_unlocked'=> 0),
                                'fields' => array('BusinessOwner.email','BusinessOwner.fname','BusinessOwner.lname','BusinessOwner.group_role')
                                ));
        if(!empty($data)) {
            foreach($data as $row) {                
                $emailLib = new Email();
                $subject = "FoxHopr: Watch Training Video";
                $template = "training_video_reminder";
                $format = "both";
                $to=$row['BusinessOwner']['email'];
                $emailLib->sendEmail($to,$subject,array('username'=>$row['BusinessOwner']['fname'].' '.$row['BusinessOwner']['lname']),$template,$format);
            }
        }
    }

    /**
    * function is used to deactivate the user and group who has cancelled membership
    * @author Priti Kabra
    */
    
    public function deactivateUser()
    {
        $subscritpionData = $this->Subscription->find('all', array('conditions' => array('Subscription.is_active' => 0, 'Subscription.next_subscription_date' => date('Y-m-d'))));
        foreach ($subscritpionData as $subscription) {
            $nextDate = $subscription['Subscription']['next_subscription_date'];
            if (date('Y-m-d') == $nextDate) {
                $this->User->id = $subscription['Subscription']['user_id'];
                $this->User->saveField('deactivated_by_user', 1);
                $businessId = $this->BusinessOwner->findByUserId($subscription['Subscription']['user_id']);
                
                //Store previous group members information
                $gdata = $this->BusinessOwner->getMyGroupMemberList($this->Encryption->decode($businessId['Group']['id']),$subscription['Subscription']['user_id']);
                $prevMember = NULL;
                $prevRecord['PrevGroupRecord'] = array();
                foreach($gdata as $key => $val) {
                    $data['user_id'] = $subscription['Subscription']['user_id'];
                    $data['group_id'] = $this->Encryption->decode($businessId['Group']['id']);
                    $data['members_id'] = $key;
                    array_push($prevRecord['PrevGroupRecord'],$data);
                }
                $this->PrevGroupRecord->saveAll($prevRecord['PrevGroupRecord']);       
                $this->BusinessOwner->id = $this->Encryption->decode($businessId['BusinessOwner']['id']);
                $parts = explode(',', $businessId['Group']['group_professions']); 
                    while(($i = array_search($businessId['BusinessOwner']['profession_id'], $parts)) !== false) {
                        unset($parts[$i]);
                    }
                $updateProfessions = implode(',', $parts);
                $updateMember = $businessId['Group']['total_member'] - 1;
				$this->BusinessOwner->saveField('group_id', NULL);
                $this->Group->updateAll(array('Group.group_professions' => "'".$updateProfessions."'",'Group.total_member' =>"'".$updateMember."'"),array( 'Group.id' => $businessId['BusinessOwner']['group_id']));
            }
        }
    }

	/**
     * function is used to calculate the percentage of group member 
     * based on referral and ratings
     * @author Jitendra Sharma
     */
    public function calculateShufflingPercentage()
    {	
   		$currentDate = date("Y-m-d");
   		$endDate = date("Y-m-d",strtotime("+7 days"));
   		//$currentDate."==".$endDate;
   		$this->Group->unbindModel(
		    array('belongsTo' => array('Country','State','User'))
		);
   		$this->Group->bindModel(array(
   				'hasMany' => array( 
   					'BusinessOwner' => array(
   						'className' => 'BusinessOwner',
   						'foreignKey' => 'group_id',
   						'fields' => array('BusinessOwner.user_id','BusinessOwner.group_id'),
   					 )
   				)
   			)
   		);
   		
   		$groupInfo = $this->Group->find('all', array(   				
				'conditions' => array('Group.shuffling_date >=' => $currentDate,'Group.shuffling_date <=' => $endDate,'Group.total_member >' => 0)
		));
   		if(!empty($groupInfo)){
	   		foreach($groupInfo as $groups){
	   			$totalGroupReferral = "";
	   			$shufflingParams = array();
	   			foreach ($groups['BusinessOwner'] as $groupMember){
	   				//get referral send by group member
	   				$totalGroupReferral += $shufflingParams[$groupMember['user_id']]['ReferralSend'] = $this->SendReferral->find('count', array(
	   					'conditions' => array('SendReferral.from_user_id' => $groupMember['user_id'],'SendReferral.group_id' => $groupMember['group_id'])
	   				));
	   				
	   				//get rating of group member
	   				$this->Review->recursive = -1;
	   				$ratingInfo = $this->Review->find("first", array(
					    "fields"     => array("ROUND(AVG(Review.rating)) AS AverageRating"),
					    'conditions' => array('Review.user_id' => $groupMember['user_id'],'Review.group_id' => $groupMember['group_id'])
					));
	   				$shufflingParams[$groupMember['user_id']]['rating'] = ($ratingInfo['0']['AverageRating']) ? $ratingInfo['0']['AverageRating'] : 0;
	   			}
	   			
	   			// calculate shuffling percentage
	   			foreach($shufflingParams as $userId => $params){
	   				// get the % of Ttl (ReferralSend by user / totalReferralSend in group by all users)
	   				$ttlPercent = ($totalGroupReferral>0) ? $params['ReferralSend']/$totalGroupReferral : 0.00;
	   				// get the rating %
	   				$ratingPercent = $params['rating']/Configure::read('MAX_RATING');
	   				
	   				// update shuffling percentage of respective user
	   				$criteria = $this->Setting->find('first',array('conditions'=>array('Setting.id'=>1)));
	   				$criteria = explode(":",$criteria['Setting']['key_value']);
	   				$referralConfig = $criteria[0];
	   				$ratingConfig = $criteria[1];
	   					   				
	   				$shufflingPercent = ($ttlPercent*$referralConfig/100) + ($ratingPercent*$ratingConfig/100);
	
	   				// update user shuffling percent
	   				$this->BusinessOwner->updateAll(
	   					array('BusinessOwner.shuffling_percent' => $shufflingPercent),
	   					array('BusinessOwner.user_id' => $userId)
	   				);
	   			}   			
	   		}
   		}
    }
    
    /**
     * function is used to send email notification before 24 hrs of Shuffling (every 24 hrs)
     * @author Jitendra Sharma
     */
    public function shufflingEmailNotification()
    {
    	$currentDate = date("Y-m-d",strtotime("+1 days"));
   		
   		$this->Group->unbindModel(
   				array('belongsTo' => array('Country','State','User'))
   		);
   		$this->Group->bindModel(array(
   			'hasMany' => array(
   				'BusinessOwner' => array(
   					'className' => 'BusinessOwner',
   					'foreignKey' => 'group_id',
   					'fields' => array('BusinessOwner.user_id','BusinessOwner.group_id','BusinessOwner.email','BusinessOwner.member_name'),
   					)
   				)
   			)
   		);
   		 
   		$groupInfo = $this->Group->find('all', array(
   				'conditions' => array('Group.shuffling_date' => $currentDate,'Group.total_member >' => 0)
   		));
   		$groupCount = count($groupInfo);
   		
   		if(!empty($groupInfo)){
	   		$emailLib = new Email();
	   		$subjectMembers = "FoxHopr: Group Shuffling";
	   		$subjectAdmin = "FoxHopr: Group Shuffling";
	   		$templateMembers = "group_shuffling_member_notify";
	   		$templateAdmin = "group_shuffling_admin_notify";
	   		$format = "both";
	   		
	   		// send email to admin
	   		$adminEmail = AdminEmail;
	   		$emailLib->sendEmail($adminEmail,$subjectAdmin,array('groupCount'=>$groupCount),$templateAdmin,$format);
	   		
	    	foreach($groupInfo as $groups){   			
	   			foreach ($groups['BusinessOwner'] as $groupMember){
	   				// send email to all group member
	                $to = $groupMember['email'];
	                $emailLib->sendEmail($to,$subjectMembers,array('username' => $groupMember['member_name']),$templateMembers,$format);
	   			}   			  			
	   		}
   		}
    }
    
    /**
     * function is used to send email notification to admin if he misses the shuffling (every 24 hrs)
     * @author Jitendra Sharma
     */
    public function shufflingMissedEmailNotification()
    {
    	$initialDate = date("Y-m-d",strtotime("-1 days"));
    	$endDate = date("Y-m-d",strtotime("-3 days"));
    	 
    	$this->Group->unbindModel(
    			array('belongsTo' => array('Country','State','User'))
    	);    	
    	
    	$groupInfo = $this->Group->find('all', array(
    		'conditions' => array('Group.shuffling_date >=' => $endDate,'Group.shuffling_date <=' => $initialDate,'Group.total_member >' => 0)
    	));
    	
    	$groupCount = count($groupInfo);
    	//pr($groupInfo);
    	if(!empty($groupInfo)){
    		$emailLib = new Email();
    		$subjectAdmin = "FoxHopr: Group Shuffling";
    		$templateAdmin = "shuffling_missed_admin_notify";
    		$format = "both";
    	
    		// send email to admin
    		$adminEmail = AdminEmail;
    		$ok = $emailLib->sendEmail($adminEmail,$subjectAdmin,array('groupCount'=>$groupCount),$templateAdmin,$format);
    		//var_dump($ok);	
    	}
    }
    /**
     * Function to send email notification to User if the group has net been changed after being registered for more than 48 hours 
     * @author Rohan Julka
     */
    public function groupNotChangedMails()
    {
        $conditions = array('GroupChangeRequest.request_type'=>'cr','GroupChangeRequest.is_moved'=>0);
        $pendingRequests = $this->GroupChangeRequest->find('all',array('conditions'=>$conditions));
        $cuttentTimeStamp = strtotime(date('Y-m-d'));
        if(!empty($pendingRequests)) {
            $count = 0;
            foreach($pendingRequests as $row){
                $timeDiff = round(abs($cuttentTimeStamp - strtotime($row['GroupChangeRequest']['created'])) / (60*60),0);
                if($timeDiff < 48) {
                    //Send Mails
                    $count++;
                    $emailLib = new Email();
                    $to = $userInfo['BusinessOwner']['email'];
                    //$to = 'rohan.julka@a3logics.in';
                    $subject = 'FoxHopr: Group change request status';
                    $template ='group_change_pending';
                    $variable = array('name'=>$row['BusinessOwner']['fname'] . " " . $row['BusinessOwner']['lname']);
                    $success = $emailLib->sendEmail($to,$subject,$variable,$template,'both');
                }
            }
        }
    } 
    /**
     * Function for sending resetting goals
     * @author Rohan Julka
     * */
    public function goalsResetCron()
    {
        $groups = $this->Group->find('all');
        $emailPost = array();
        foreach($groups as $group) {
            $groupID = $this->Encryption->decode($group['Group']['id']);
            $conditions = array('BusinessOwner.group_id'=>$groupID);
            $fields = array('BusinessOwner.fname','BusinessOwner.lname','BusinessOwner.email');
            $bizData = $this->BusinessOwner->find('all',array('conditions'=>$conditions,'fields'=>$fields));
            // Send mail to all group members
            if(!empty($bizData)) {
                foreach($bizData as $row) {
                    $emailLib = new Email();
                    $to = $row['BusinessOwner']['email'];
                    $subject = 'FoxHopr: Your Goals have been reset';
                    $template ='group_goals_reset';
                    $message = "Your individual goals have been reset.<br/>Please login and click Team tab to open the Goals subtab to set the new goals for the present month. ";
                    $variable = array('name'=>$row['BusinessOwner']['fname'] . " " . $row['BusinessOwner']['lname'],'message'=>$message);
                    $success = $emailLib->sendEmail($to, $subject, $variable, $template, 'both');
                }
            }       
            $leaderColeaderData = $this->BusinessOwner->find('all',array('conditions'=>array('BusinessOwner.group_id'=>$groupID,'BusinessOwner.group_role'=>array('leader','co-leader')),'fields'=>$fields));
            // Send mail to all leaders/Co-leaders
            if(!empty($leaderColeaderData)) {
                foreach($leaderColeaderData as $row) {
                    $emailLib = new Email();
                    $to = $row['BusinessOwner']['email'];
                    $subject = 'FoxHopr: Your Goals have been reset';
                    $template ='group_goals_reset';
                    $message = "The group members goal have been reset.<br/>Please login and click Team tab to open the Goals sub tab and set the group member goals for the present month. ";
                    $fullName = $row['BusinessOwner']['fname'] . " " . $row['BusinessOwner']['lname'];
                    $variable = array('name'=>$fullName, 'message'=>$message);
                    $success = $emailLib->sendEmail($to, $subject, $variable, $template, 'both');
                }
            } 
        }        
    }

    public function meetingReminder()
    {
    	//echo date('h:i:s A');
		$emailLib = new Email();    	
    	$breezSessionData = $this->Adobeconnect->adobeConnectLogin();
    	if($breezSessionData != 'invalid') {
    		$slotData = $this->AvailableSlots->getSlotData();
	    	if(!empty($slotData)) {
	    		foreach($slotData as $sData) {
	    			$timeArr = explode(' ', $sData['AvailableSlots']['slot_time']);
	    			$actialMeetingTime = strtotime($timeArr[0].' '.$timeArr[1]);
	    			$timeBefore = strtotime(date('h:i A',strtotime('-2 hours',strtotime($timeArr[0].' '.$timeArr[1]))));
	    			$currentTime = strtotime(date('h:i A'));
	    			if($currentTime > $timeBefore && $currentTime < $actialMeetingTime){
	    				$adobeMeetingId = $sData['AvailableSlots']['adobe_group_id'];
	    				$groupMembers = $this->BusinessOwner->getGroupMembers($this->Encryption->encode($sData['AvailableSlots']['group_id']));
	    				if(!empty($groupMembers)) {
	    					foreach($groupMembers as $info){
	    						$callBackData = $this->Adobeconnect->addRemoveUserToMeeting($info,$adobeMeetingId,$breezSessionData);
			                    $to = $info['BusinessOwner']['email'];
			                    $subject = 'FoxHopr: Meeting Link';
			                    $template ='meeting';
			                    $fullName = $info['BusinessOwner']['member_name'];
                    			$url = Configure::read('SITE_URL') . 'meetings';
			                    $variable = array('name'=>$fullName, 'url'=>$url);
			                    $success = $emailLib->sendEmail($to, $subject, $variable, $template, 'both');
	    					}
	    					$this->AvailableSlots->id = $this->Encryption->decode($sData['AvailableSlots']['id']);
	    					$this->AvailableSlots->saveField('is_active', 1);
	    				}
	    			}
	    		}
	    	}
    	}
    }
}