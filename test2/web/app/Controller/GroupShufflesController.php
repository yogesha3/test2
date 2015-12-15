<?php
/**
* Group Shuffle Controller
*
* Controller used to perform group shuffling.
* 
* Group Sfuffles class to handle all the group suffling action
* It contains all type list needed in process
* It contains the notification email send process
*/
App::uses('Email', 'Lib');

/**
 * Group Shuffle Controller
 *
 * Controller used to perform group shuffling.
 */
class GroupShufflesController extends AppController {
	public $components = array('GroupGoals');
	
	/**
	 * Model to be used in this Class
	 */
	public $uses = array (
			'Group','BusinessOwner','PrevGroupRecord','Setting','UserGroupHistory'
	);
	
	/**
	 * callback function on filter
	 * 
	 * @author Jitendra Sharma
	 *        
	 */
	public function beforeFilter() {
		parent::beforeFilter ();
		$this->set('title_for_layout','Shuffling');
		$this->Auth->allow ( array('autoGroupShuffling') );
	}
	
	/**
	 * list all the groups shuffle dates in step1
	 * 
	 * @author Jitendra Sharma
	 *        
	 */
	public function admin_shufflingStep1() {
		$dateRanges = $this->getDatesRangeFromStartDate();
		$this->set(compact('dateRanges'));
	}
	
	/**
	 * list groups count with timeslot which will be shuffle on shuffling date
	 * @param $shufflingDate shuffling date of groups
	 * @param $isAuto check whether process is manual or automatic
	 * @author Jitendra Sharma
	 *
	 */
	public function admin_shufflingStep2($shufflingDate = null,$isAuto=false) {
		if(!is_numeric($shufflingDate)){
			$shufflingDate = strtotime($shufflingDate);
		}		
		$startTime = strtotime("24:00:00");
		$meetingInterval = 90;
		$meetingGap = 30;
		$timeslots = array();
		$k = 0;
		for($i=0;$i<48;$i++){			
			// create time slots of meeting
			$meetingtime = $meetingGap * $i;
			$nextmeetingtime = "+".$meetingtime." minutes";
			$startTimeSlot = strtotime($nextmeetingtime, $startTime);
			$endmeetingtime = "+".$meetingInterval." minutes";
			$endTimeSlot = strtotime($endmeetingtime, $startTimeSlot);
			
			// get the group count will shuffled on shuffling date			
			$groupCount = $this->getShuffledGroupCount($shufflingDate,$startTimeSlot);
			if($groupCount>0){
				$timeslots[$k]['timeslot']['startTime'] = $startTimeSlot;
				$timeslots[$k]['timeslot']['endTime'] = $endTimeSlot;
				$timeslots[$k]['timeslot']['group_count'] = $groupCount;
				$k++;
			}			
		}
		//pr($timeslots);
		if(!$isAuto){
			$this->set(compact('timeslots','shufflingDate'));
		}else{
			return $timeslots;
		}
	}
	
	/**
	 * list all the groups with group member count
	 * @param $shufflingDate shuffling date of groups
	 * @author Jitendra Sharma
	 *
	 */
	public function admin_shufflingStep3($shufflingDate = null, $startTimeSlot = null) {
		if(!is_numeric($shufflingDate) || !is_numeric($startTimeSlot)){
			$shufflingDate = strtotime($shufflingDate);
			$startTimeSlot = strtotime($startTimeSlot);
		}
		// get the group list will shuffled on shuffling date			
		$localGroupList = $this->getShuffledGroupList($shufflingDate,$startTimeSlot);
		$globalGroupList = $this->getShuffledGroupList($shufflingDate,$startTimeSlot,'global');
		//pr($groupList);
		$this->set(compact('localGroupList','globalGroupList','shufflingDate','startTimeSlot'));
	}
	
	/**
	 * Function to create date range array
	 * @return array $dateList list of dates with status
	 * @author Jitendra Sharma
	 */
	public function getDatesRangeFromStartDate(){
		$dateRange = array('-2','-1','+0','+1','+2','+3','+4','+5','+6','+7');
		foreach($dateRange as $key => $dates){
			$dateDiff = $dates." days";
			if($dates < 0){
				$status = "Pending";
			}else if($dates == "+0"){
				$status = "Shuffle Now";
			}else{
				$status = "Upcoming";
			} 
			$dateList[$key]['date'] = strtotime($dateDiff);
			$dateList[$key]['status'] = $status;
		}		
		return $dateList;
	}
	
	/**
	 * Function to get shufling group count in specific time slot
	 * @param $shufflingDate group shuffling date
	 * @param $startTimeSlot time slot starting time
	 * @return int $shuffledGroupCount group count
	 * @author Jitendra Sharma
	 */
	public function getShuffledGroupCount($shufflingDate=null,$startTimeSlot=null){
		$shufflingDate = date("Y-m-d",$shufflingDate);
		$startTimeSlot = date("H:i:s",$startTimeSlot);
		$currentDay = date("d");
		
		$this->Group->recursive = -1;
		$shuffledGroupCount = $this->Group->find('count', array(
			'conditions' => array('Group.shuffling_date' => $shufflingDate,'DAY(Group.first_meeting_date) !='=> $currentDay,'Group.meeting_time' => $startTimeSlot,'Group.total_member >' => 0)
		));
		return $shuffledGroupCount;
	}
	
	/**
	 * Function to get shufling group list in specific time slot
	 * @param $shufflingDate group shuffling date
	 * @param $startTimeSlot time slot starting time
	 * @return array $shuffledGroup group array
	 * @author Jitendra Sharma
	 */
	public function getShuffledGroupList($shufflingDate=null,$startTimeSlot=null,$groupType="local"){
		$shufflingDate = date("Y-m-d",$shufflingDate);
		$startTimeSlot = date("H:i:s",$startTimeSlot);
		$currentDay = date("d");
	
		$this->Group->recursive = -1;
		$shuffledGroup = $this->Group->find('all', array(
			'conditions' => array('Group.shuffling_date' => $shufflingDate,'DAY(Group.first_meeting_date) !='=> $currentDay,'Group.meeting_time' => $startTimeSlot,'Group.total_member >' => 0,'Group.group_type'=> $groupType )
		));
		return $shuffledGroup;
	}
	
	/**
	 * Function to show the group member list in popup
	 * @author Jitendra Sharma
	 */
	public function admin_showGroupMemberList(){		
		$groupId = $this->Encryption->decode($this->request->data['groupId']);
		$usersList = $this->BusinessOwner->find('all',array('fields'=>'User.id,BusinessOwner.member_name,BusinessOwner.shuffling_percent,Profession.profession_name','conditions'=>array('BusinessOwner.group_id'=>$groupId),'order' => array('BusinessOwner.shuffling_percent DESC')));
		$this->set('usersList', $usersList);
	}
	
	/**
	 * function to use shuffled the group members
	 * @param $isAuto if process is automatic or manual
	 * @author Jitendra Sharma
	 */
	public function admin_groupShuffling($isAuto=false){
		// set memory and max timeout for shuffling
		ini_set('memory_limit', '256M');
		ini_set('max_execution_time', 0);
		
		$shuffleDate = $this->request->data['shuffledate'];
		$shuffleTime = $this->request->data['shuffletime'];
		$shuffleGroup = $this->request->data['shuffleGroupType'];
		$groupList = $this->getShuffledGroupList($shuffleDate,$shuffleTime,$shuffleGroup);
		
		// get the shuffling members
		if(count($groupList)>1){
			$leader = $coleader = $members = $leaderProfession = array();
			foreach($groupList as $group){
				$groupId = $this->Encryption->decode($group['Group']['id']);
				$usersList = $this->BusinessOwner->find('all',array('fields'=>'BusinessOwner.user_id,BusinessOwner.group_id,BusinessOwner.profession_id,BusinessOwner.group_role,BusinessOwner.shuffling_percent','conditions'=>array('BusinessOwner.group_id'=>$groupId),'order' => array('BusinessOwner.shuffling_percent DESC')));
				foreach($usersList as $user){
					$userid = $user['BusinessOwner']['user_id'];
					switch($user['BusinessOwner']['group_role']){
						case 'leader':
							$leader[$userid] = $user['BusinessOwner']['shuffling_percent'];
							$leaderProfession[$userid] = $user['BusinessOwner']['profession_id'];
							$currentGroup[$userid] = $user['BusinessOwner']['group_id'];
							$previousGroup[$user['BusinessOwner']['group_id']][] = $userid;
							break;
						case 'co-leader':
							$coleader[$userid] = $user['BusinessOwner']['shuffling_percent'];
							$coleaderProfession[$userid] = $user['BusinessOwner']['profession_id'];
							$currentGroup[$userid] = $user['BusinessOwner']['group_id'];
							$previousGroup[$user['BusinessOwner']['group_id']][] = $userid;
							break;
						default:
							$members[$user['BusinessOwner']['profession_id']][$userid] = $user['BusinessOwner']['shuffling_percent'];
							$currentGroup[$userid] = $user['BusinessOwner']['group_id'];
							$previousGroup[$user['BusinessOwner']['group_id']][] = $userid;
							break;
					}
				}
			}
			
			// sort array by percentage
			arsort($leader);
			$leader = array_keys($leader);
			arsort($coleader);		
			$coleader = array_keys($coleader);
			foreach($members as $profession => $member){
				arsort($member);
				$member = array_keys($member);
				$participant[$profession] = $member;
			}			
			
			$dataSource = ConnectionManager::getDataSource('default');
			$dataSource->begin();
			
			try {
				// shuffling start here
				$i = 0;
				foreach($groupList as $group){
					$groupId = $this->Encryption->decode($group['Group']['id']);
					$newGroupMember[$groupId][] = $leader[$i];
					$groupProfession[$groupId][] = $leaderProfession[$leader[$i]];
					$coleaderProfessionId = (!empty($coleader[$i])) ? $coleaderProfession[$coleader[$i]] : "";
					if(array_key_exists($i, $coleader) && !in_array($coleaderProfessionId,$groupProfession[$groupId])){
						$newGroupMember[$groupId][] = $coleader[$i];
						$groupProfession[$groupId][] = $coleaderProfessionId;
					}
					foreach($participant as $professionid => $member){
						if(!in_array($professionid,$groupProfession[$groupId]) && !(empty($member))){
							$newGroupMember[$groupId][] = $member[0];
							$groupProfession[$groupId][] = $professionid;
							array_splice($participant[$professionid],0,1);
						}
					}
				
					$myGroupMemberList = array();
					// shuffled into new group $currentGroup[$userId]
					foreach($newGroupMember[$groupId] as $userId){
				
						// save previous group team member details
						$myGroupMemberList = array_diff($previousGroup[$currentGroup[$userId]], array($userId));
						$prevGroupRecord['PrevGroupRecord']['user_id'] = $userId;
						$prevGroupRecord['PrevGroupRecord']['group_id'] = $currentGroup[$userId];
							
						foreach ($myGroupMemberList as $membersId){
							$this->PrevGroupRecord->create();
							$prevGroupRecord['PrevGroupRecord']['members_id'] = $membersId;
							$this->PrevGroupRecord->save($prevGroupRecord);
						}
							
						// shuffled into new group
						$this->BusinessOwner->updateAll(
								array('BusinessOwner.group_id' => $groupId,'BusinessOwner.is_kicked' => '0','BusinessOwner.shuffling_percent'=>'0.00'),
								array('BusinessOwner.user_id' => $userId)
						);
						
						// save user group history
						if($currentGroup[$userId]==$groupId){
							$this->UserGroupHistory->updateAll(
									array('UserGroupHistory.group_leave_date' => date('Y-m-d')),
									array('UserGroupHistory.user_id' => $userId,'UserGroupHistory.group_id' => $groupId)
							);
							
							$this->UserGroupHistory->create();
							$groupHistory['UserGroupHistory']['user_id'] = $userId;
							$groupHistory['UserGroupHistory']['group_id'] = $groupId;
							$groupHistory['UserGroupHistory']['group_join_date'] = date('Y-m-d');
							$this->UserGroupHistory->save($groupHistory);
						}
						
						// reset user goals
						$this->GroupGoals->resetUserGoalsShuffling($userId);
					}
				
					// group updates accordingly
					$totalMember = count($newGroupMember[$groupId]);
					$groupProfessions = "'".implode(",",$groupProfession[$groupId])."'";
					$groupLeader = array_values(array_intersect($newGroupMember[$groupId], $leader));
					$groupLeaderId = $groupLeader[0];
					$groupCoLeader = array_values(array_intersect($newGroupMember[$groupId], $coleader));
					$groupCoLeaderId = (!empty($groupCoLeader)) ? $groupCoLeader[0] : NULL;
					$shufflingDate = "'".date("Y-m-d",strtotime("+91 days",$shuffleDate))."'";
				
					$this->Group->unbindModel(
							array('belongsTo' => array('Country','State','User'))
					);
				
					$this->Group->updateAll(
							array('total_member'=>$totalMember,'group_professions'=>$groupProfessions,'group_leader_id'=>$groupLeaderId,'group_coleader_id'=>$groupCoLeaderId,'shuffling_date'=>$shufflingDate),
							array('Group.id' => $groupId)
					);
					$i++;
				}
				
				if ($dataSource->commit()) {
					// notification email settings after shuffling
					$emailLib = new Email();
					$subjectMembers = "FoxHopr: Group Shuffling";
					$templateMembers = "group_after_shuffling_member_notify";
					$format = "both";
					
					// send notification email after shuffling
					$this->BusinessOwner->recursive = -1;
					foreach($currentGroup as $userId => $userGroupId){
						$userData = $this->BusinessOwner->find('first',array('fields'=>'BusinessOwner.email,BusinessOwner.member_name','conditions'=>array('BusinessOwner.user_id'=>$userId)));
						$to = $userData['BusinessOwner']['email'];
						$username = $userData['BusinessOwner']['member_name'];
						$emailLib->sendEmail($to,$subjectMembers,array('username' => $username),$templateMembers,$format);
					}
				}
				
			} catch (Exception $e) {
				$dataSource->rollback();
			}
		}
		
		// shuffle single group
		if(count($groupList)==1){
			$groupId = $this->Encryption->decode(Set::classicExtract($groupList,'0.Group.id'));
			$this->Group->id = $groupId;
			$shufflingDate = date("Y-m-d",strtotime("+91 days",$shuffleDate));
			$this->Group->saveField('shuffling_date', $shufflingDate);
		}
		
		$this->Session->setFlash(__('Groups has been shuffled successfully.'), 'flash_good');		
		if(!$isAuto){
			echo "Success";die;
		}
	}
	
	/**
	 * function to use set the shuffling criteria which is used to calculate shuffling percent
	 * @author Jitendra Sharma
	 */
	public function admin_setShufflingCriteria(){
		$this->layout = 'admin';
		$this->includePageJs = array('admin_validation');
		$this->set('includePageJs',$this->includePageJs);
		
		$criteria = $this->Setting->find('first',array('conditions'=>array('Setting.id'=>1)));
		if($this->request->is('post')){
			$total_precent = array_sum($this->request->data['Setting']);
			if($total_precent==100){
				$this->Setting->id = 1;
				$settingdata['Setting']['key_value'] = implode(":",$this->request->data['Setting']);
				$this->Setting->save($settingdata);
				$this->Session->setFlash(__('Percentage criteria has been updated successfully.'), 'flash_good');
			}else{
				$this->Session->setFlash(__('Total criteria percentage must be equal to 100'), 'flash_bad');
			}
		}else{
			$criteria = explode(":",$criteria['Setting']['key_value']);
			$this->request->data['Setting']['shuffling_criteria_1'] = $criteria[0];
			$this->request->data['Setting']['shuffling_criteria_2'] = $criteria[1];
		}
	}
	
	/**
	 * function to handle automatic shuffling of groups after 48hrs of shuffling date
	 * @author Jitendra Sharma
	 */
	public function autoGroupShuffling(){		
		$autoShuffledDate = strtotime("-3 days",strtotime(date('Y-m-d')));
		//$autoShuffledDate = "1443707291";
		$availableTimeSlots = $this->admin_shufflingStep2($autoShuffledDate,true);
		// process each timeslot for which group available for shuffling  
		foreach($availableTimeSlots as $timeslot){
			$startTimeSlot = $timeslot['timeslot']['startTime'];
			//$availableGroupTypes = $this->admin_shufflingStep3($autoShuffledDate, $startTimeSlot, true);
			$availableGroupTypes = array('local','global');
			foreach($availableGroupTypes as $groupType){
				$this->request->data['shuffledate'] = $autoShuffledDate;
				$this->request->data['shuffletime'] = $startTimeSlot;
				$this->request->data['shuffleGroupType'] = $groupType;
				$this->admin_groupShuffling(true);
			}
		}
	}
	
}