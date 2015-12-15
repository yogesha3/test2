<?php
/**
 * This is a group controller
 */
App::uses('Email', 'Lib');
class GroupsController extends AppController 
{
    //public $paginate = array('order' => array('Group.created' => 'desc'));
    public $helper=array('Timezone');
    public $components=array('Common','Timezone','Paginator','Cookie','Groups','GroupGoals','Adobeconnect');
    public $uses = array('BusinessOwner', 'Profession', 'User', 'Group', 'Country', 'State', 'LiveFeed', 'Transaction', 'GroupChangeRequest', 'PrevGroupRecord','AdobeConnectMeeting','AvailableSlots');
    /**
     * callback function on filter
     * @author Gaurav Bhandari
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(array('globalGroups','localGroups','groupSelection','selectGroup','createGroup', 'popupFunction','getCountryList','getStateList','admin_addGroup','createNewGroup'));
    }

	/**
    * list all the groups in admin panel
    * @author Gaurav Bhandari
    */
    public function admin_index() 
    {
        $condition=array();
        if (!$this->request->is('ajax')) {
            $this->Session->delete('direction');
            $this->Session->delete('sort');
        }
        $this->layout = 'admin';
        $perpage = $this->Functions->get_param('perpage', Configure::read('PER_PAGE'), true);
        $page = $this->Functions->get_param('page', Configure::read('PAGE_NO'), false);
        $counter = (($page - 1) * $perpage) + 1;
        $this->set('counter', $counter);   

        $search = $this->Functions->get_param('search');
        $this->Functions->set_param('direction');        
        $this->Functions->set_param('sort');
        if ($this->Session->read('sort') != '') {
            $order = array($this->Session->read('sort') => $this->Session->read('direction'));
        }else{
            $order=array('Group.created'=>'desc');
        }
        //$this->paginate['Group']['limit'] = $perpage;
        if ($search != '') {
            
            $condition['Group.id LIKE'] =  '%' . $search . '%';
        }
        $condition['Group.is_active']=1;
        $this->Paginator->settings = array(
                'conditions' => $condition,
                'order' => $order,
                'limit' => $perpage
            );
        $resultData = $this->Paginator->paginate('Group');
        //pr($resultData);die;
        $this->set('groups', $resultData);
        if ($this->request->is('ajax')) {
            $this->layout = false;
            $this->set('perpage', $perpage);
            $this->set('search', $search);
            $this->render('admin_group_ajax_list'); // View, Layout
        }
    }

    /**
    * used for add group
    * @author Gaurav Bhandari
    */
    public function admin_addGroup()
    {
        $this->includePageJs = array('admin_validation');
        $this->layout = 'admin';
        if ($this->request->is('ajax')) {
        	$this->autoRender = false;
         	$date = $this->request->data['date'];
         	$availability = $this->__checkAdobeConnectMeetingSlots($date);
			return json_encode($availability);
        }
        $timezones = $this->Timezone->getAllTimezones();
        $this->set('timezones', $timezones);
        $this->set('includePageJs', $this->includePageJs);
    }

    /**
    * list all the group requests in admin panel
    * @author Gaurav Bhandari
    */
    public function admin_requestIndex() 
    {
        if (!$this->request->is('ajax')) {
            $this->Session->delete('direction');
            $this->Session->delete('sort');
        }
        $this->layout = 'admin';
        $perpage = $this->Functions->get_param('perpage', Configure::read('PER_PAGE'), true);
        $page = $this->Functions->get_param('page', Configure::read('PAGE_NO'), false);
        $counter = (($page - 1) * $perpage) + 1;
        $this->set('counter', $counter);
        $search = $this->Functions->get_param('search');
        $this->Functions->set_param('direction');        
        $this->Functions->set_param('sort');
        if ($this->Session->read('sort') != '') {
            $order = array(
                $this->Session->read('sort') => $this->Session->read('direction'),
            );
        } else {
            $order = array('modified' => 'desc');
        }
        if ($search != '') {
            $condition = array(
                'Group.id LIKE' => '%' . $search . '%',
                'Group.is_active' => '0',
            );
        } else {
            $condition = array('Group.is_active' => '0');
        }
        $this->Paginator->settings = array(
                'conditions' => $condition,
                'order' => $order,
                'limit' => $perpage
            );
        $resultData = $this->Paginator->paginate('Group');
        $this->set('groups', $resultData);
        if ($this->request->is('ajax')) {
            $this->layout = false;
            $this->set('perpage', $perpage);
            $this->set('search', $search);
            $this->render('admin_grouprequest_ajax_list'); // View, Layout
        }
    }

    /**
    * used to approv group
    * @param string $groupId group id 
    * @author Gaurav Bhandari
    */
    public function admin_groupApprove($groupId = null) 
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $this->set('id', $this->request->data['id']);
            $this->set('action', 'groupApprove');
            $this->set('info','Group');
            $popupData = $this->parsePopupVars('groupApprove','Group');
            $this->set('popupData',$popupData);
            $this->render('/Elements/activate_delete_popup', 'ajax');
        } else if ($this->request->is('post')) {
            $this->Group->id = $groupId = $this->Encryption->decode($groupId);
            if ($this->Group->saveField('is_active', 1)) {
            	$groupDetail = $this->Group->findById($groupId,'Group.*');
            	$groupName 	 = $groupDetail['Group']['group_name'];
            	$groupUserId = $groupDetail['Group']['group_created_by'];
            	$userDetail = $this->User->findById($groupUserId,'User.*,BusinessOwner.*');
            	
            	// send user group approval email
            	$emailLib = new Email();
            	$to = $userDetail['User']['user_email'];
            	$subject = 'Group approved by admin';
            	$format = "both";
            	$template = 'group_approved_admin';
            	$user_name = $userDetail['BusinessOwner']['fname']." ".$userDetail['BusinessOwner']['lname'];
            	$variable = array('name' => $user_name,'groupname'=>$groupName);
            	$success1 = $emailLib->sendEmail($to,$subject,$variable,$template,$format);
            	
                $this->Session->setFlash('Group has been approved and added successfully', 'flash_good');
                return $this->redirect(array('action' => 'admin_requestIndex'));
            } else {
                $this->Session->setFlash('Please try again', 'flash_bad');
                return $this->redirect(array('action' => 'admin_requestIndex'));
            }
        }
    }

    /**
    * View Group detail
    * @param string $groupId group id
    * @author Gaurav Bhandari
    */
    public function admin_view($groupId = NULL)
    {
        $this->layout = 'admin';
        if (!$groupId) {
            $this->Session->setFlash(__('Invalid Group'), 'flash_bad');
            $this->redirect(array('controller' => 'groups', 'action' => 'index', 'admin' => true));
        }
        $groupData = $this->Group->findById($this->Encryption->decode($groupId));
        if (!$groupData) {
            $this->Session->setFlash(__('Invalid Group'), 'flash_bad');
            $this->redirect(array('controller' => 'groups', 'action' => 'index', 'admin' => true));
        }
        $this->set('groupData', $groupData);
    }

    /**
    * Delete Group
    * @param string $groupId group id
    * @author Gaurav Bhandari
    */
    public function admin_delete($groupId = null)
    {
        $this->autoRender = false;
        $action='';
        $info='';
        if ($this->request->is('ajax')) {
            $this->set('id', $this->request->data['id']);
            $action='delete';
            $info='Group';
            $groupHolders = $this->BusinessOwner->find('all', array(
                'fields' => array('BusinessOwner.fname, BusinessOwner.lname, BusinessOwner.profession_id'),
                'conditions' => array('group_id' => $this->Encryption->decode($this->request->data['id']))
            ));

            if (!empty($groupHolders)) {
                $group = $this->Group->findById($this->Encryption->decode($this->request->data['id']));
                $currentGroupId = $this->request->data['id'];
                $meetingTime = $group['Group']['meeting_time'];
                $firstMeetingDate = $group['Group']['first_meeting_date'];
                $secondMeetingDate = $group['Group']['second_meeting_date'];
                $sameTimeAvailableGroups = $this->Group->find('all', array(
                    'fields' => array('Group.id','Group.zipcode'),
                    'conditions' => array('meeting_time' => $meetingTime,
                        'OR' => array('second_meeting_date' => array($firstMeetingDate, $secondMeetingDate), 'first_meeting_date' => array($firstMeetingDate, $secondMeetingDate)),
                        'Group.id !=' => $this->Encryption->decode($this->request->data['id']),
                        'Group.group_type' => $group['Group']['group_type']),
                ));
                $businessOwnerData = array();
                if (!empty($sameTimeAvailableGroups)) {
                    foreach ($groupHolders as $groupUser) {
                        $userProfessionId = $groupUser['BusinessOwner']['profession_id'];
                        foreach ($sameTimeAvailableGroups as $availableGroup) {
                            $returnData = $this->Group->isProfessionOccupiedInGroup($this->Encryption->decode($availableGroup['Group']['id']), $userProfessionId);
                            if ($returnData) {
                                $cannotDelete = 'not be deleted';
                            } else if(!$returnData) {
                                $groupUser['BusinessOwner']['available'][] = $availableGroup;
                            }
                        }
                        $businessOwnerData[] = $groupUser;
                    }
                }
                $groupAvailbleForAllProfession = 1;
                foreach($businessOwnerData as $checkAllAvailable) {
                    if(!array_key_exists("available", $checkAllAvailable['BusinessOwner'])) {
                        $groupAvailbleForAllProfession = 0;
                    }
                }
                if (!empty($businessOwnerData) && $groupAvailbleForAllProfession == 1 ) {
                    $action='moveGroupMembers';
                    $message = 'Group consist of members. Do you still want to delete the group?';
                } else {
                    $action = 'cannotDeleteGroup';
                }
            } else {
                $action = 'delete';
            }
            $this->set('action',$action);
            $this->set('info',$info);
            $popupData=$this->parsePopupVars($action,$info);
            $this->set('popupData',$popupData);
            $this->render('/Elements/activate_delete_popup', 'ajax');
        } else if ($this->request->is('post')) {
            $group = $this->Group->findById($this->Encryption->decode($groupId));
            if (!$group) {
                $this->Session->setFlash(__('Invalid Group'), 'flash_bad');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Group->delete($this->Encryption->decode($groupId));
                $this->Session->setFlash(__('Group has been deleted successfully'), 'flash_good');
                $this->redirect(array('action' => 'index'));
            }
        }
    } 

    /**
     * Edit Group
     * @param string $groupId group id
     * @author Laxmi Saini
     */
    public function admin_edit($groupId = Null)
    {
        $this->layout = 'admin';
        $this->includePageJs = array('admin_validation');
        if(!$groupId){
            $this->Session->setFlash(__('Invalid Group'),'flash_bad');
            $this->redirect(array('controller'=>'groups','action'=>'index','admin'=>true));
        }
        $groupData = $this->Group->findById($this->Encryption->decode($groupId));
        if(!$groupData){
            $this->Session->setFlash(__('Invalid Group'),'flash_bad');
            $this->redirect(array('controller'=>'groups','action'=>'index','admin'=>true));
        }
        $this->set('groupData',$groupData);
        if ($this->request->is('ajax')) {            
            if(isset($this->request->data['Group']['group_name'])){
                $this->autoRender = false;
                $checkGrpName = $this->Group->findByGroupName(trim($this->request->data['Group']['group_name']));                  
                if($checkGrpName && $checkGrpName['Group']['id'] != $groupId){
                    return 'false';
                }else{
                    return 'true';
                }
            }
        }elseif ($this->request->is('post')) {
            $this->request->data['Group']['id']=  $this->Encryption->decode($this->request->data['Group']['id']);
            $this->request->data['Group']['group_name']=trim($this->request->data['Group']['group_name']);
            $this->request->data['Group']['group_modified_by'] = $this->Encryption->decode($this->Session->read('Auth.User.id'));
            if($this->Group->save($this->request->data)){
                $this->Session->setFlash(__('Group has been updated successfully'),'flash_good');
                $this->redirect(array('action' => 'index','admin'=>true));
            } else {                   
                $validationErrors=$this->compileErrors('Group');
                if($validationErrors != NULL) {
                    $this->Session->setFlash($validationErrors, 'flash_bad');
                }
            }
        } 
        $this->set('includePageJs',$this->includePageJs);
    }    

    /**
    * used to list group members with possible groups in which they can be moved and move them
    * @param string $groupId group id
    * @author Gaurav Bhandari
    */
    public function admin_moveGroupMembers($groupId = NULL) 
    {
        $this->layout = 'admin';
        $currentGroup = $this->Group->findById($this->Encryption->decode($groupId));            
        $groupMembersData = $this->getAvailableGroupData($groupId);
        if(!empty($groupMembersData)) {
            foreach($groupMembersData as $checkAllAvailable) {
                if(!array_key_exists("Group", $checkAllAvailable['BusinessOwner'])) {
                    $this->Session->setFlash(__('This group cannot be deleted as highlighted member(s) cannot be moved.'), 'flash_bad');
                    $this->redirect(array('controller'=>'groups','action'=>'index','admin'=>true));
                }
            }
        }
        $this->set('groupMembersData', $groupMembersData);
        $this->set('currentGroup', $currentGroup);

        if ($this->request->is('post')) {
            $isempty = 0;
            foreach ($this->request->data['BusinessOwner'] as $businessOwner) {
                if(empty($businessOwner['group_id'])) {
                    $isempty = 1;
                }
            }                
            $previousGroupId =  $this->Encryption->decode($this->request->data['GroupChangeRequest']['id']);
            $datasource = $this->BusinessOwner->getDataSource();
            try {
                if($isempty == 1){
                    throw new Exception('Please select group for all members.');
                }
                $datasource->begin();
                foreach($this->request->data['BusinessOwner'] as $businessOwner) {
                    //Store previous group members information
                    $gdata = $this->BusinessOwner->getMyGroupMemberList($previousGroupId,$this->Encryption->decode($businessOwner['id']));
                    $prevMember = NULL;
                    $prevRecord['PrevGroupRecord'] = array();
                    foreach($gdata as $key => $val) {
                        $data['user_id'] = $this->Encryption->decode($businessOwner['id']);
                        $data['group_id'] = $previousGroupId;
                        $data['members_id'] = $key;
                        array_push($prevRecord['PrevGroupRecord'],$data);
                    }
                    $this->PrevGroupRecord->saveAll($prevRecord['PrevGroupRecord']);
                }
                $userIdCounter = 1;
                foreach ($this->request->data['BusinessOwner'] as $businessOwner) {
                    $data['BusinessOwner']['id'] = $this->Encryption->decode($businessOwner['id']);
                    $data['BusinessOwner']['group_id'] = $businessOwner['group_id'];
                    $data['BusinessOwner']['profession_id'] = $businessOwner['profession_id'];
                    $result = $this->Group->isProfessionOccupiedInGroup($data['BusinessOwner']['group_id'], $data['BusinessOwner']['profession_id']);
                    if (!$result) {
                        $this->updateGroupInfo($previousGroupId,$data['BusinessOwner']['group_id'], $data['BusinessOwner']['id']);
                    } else {
                        //throw new Exception();
                        $this->request->data['BusinessOwner'][$userIdCounter]['group_id'] = '';
                        throw new Exception('This group cannot be deleted as some user(s) cannot be moved.');
                    }
                    $userIdCounter++;
                }                    
                if ($datasource->commit()) {                        
                    $fields = 'User.id,BusinessOwners.fname,BusinessOwners.lname,User.user_email,BusinessOwners.group_role,Groups.first_meeting_date,Groups.second_meeting_date,Groups.meeting_time,Groups.id';
                    $emailLib = new Email();
                    $subject = 'FoxHopr: Group Deleted';
                    $template = "delete_group";
                    $format = "both";
                    foreach($groupMembersData as $allMembers) {
                        $userInfo = $this->User->userInfo($allMembers['BusinessOwner']['user_id'],$fields);
                        if(strtotime($userInfo['Groups']['first_meeting_date']) > strtotime(date('Y-m-d'))) {
                            $meetingDate = $userInfo['Groups']['first_meeting_date'];
                        } else {
                            $meetingDate = $userInfo['Groups']['second_meeting_date'];
                        }
                        $variable = array(
                        'role' => $userInfo['BusinessOwners']['group_role'],
                        'businessowner'=>$userInfo['BusinessOwners']['fname'] . ' ' . $userInfo['BusinessOwners']['lname'],
                        'groupname' => Configure::read('GROUP_PREFIX').' '.$this->Encryption->decode($userInfo['Groups']['id']),
                        'meetingdate'=> date('m-d-Y',strtotime($meetingDate)),
                        'meetingtime'=>date('g:i A',strtotime($userInfo['Groups']['meeting_time'])),
                        );
                    $to = $userInfo['User']['user_email'];
                    $success = $emailLib->sendEmail($to,$subject,$variable,$template,$format);
                    }
                    $group = $this->Group->findById($this->Encryption->decode($this->request->data['GroupChangeRequest']['id']));
                    if (!$group) {
                        $this->Session->setFlash(__('Invalid Group'), 'flash_bad');
                        $this->redirect(array('action' => 'index'));
                    } else {
                        $this->Group->delete($this->Encryption->decode($this->request->data['GroupChangeRequest']['id']));
                        $this->Session->setFlash(__('Group has been deleted successfully'), 'flash_good');
                        $this->redirect(array('action' => 'index'));
                    }
                }
            } catch (Exception $e) {
                $datasource->rollback();
                $this->request->data = $this->request->data;
                $this->set('validate','1');
                $this->Session->setFlash(__($e->getMessage()), 'flash_bad');
            }
        }
    }

    /**
    * Front End - Group Selection page
    * @author Gaurav Bhandari
    */    
    public function groupSelection() 
    {
    	if(!$this->request->is('ajax')) {
    		$this->Session->delete('mycookie');
    	}
    	$titleForLayout = "FoxHopr: Group Selection";
    	$this->set(compact('titleForLayout'));
    	$this->layout = 'front';
    	$userId = $this->Session->read('UID');
        $userInfo = $this->User->userInfoById($this->Encryption->decode($userId));
    	if(!empty($userId)) {
    		$countryName = $userInfo['Country']['country_name'];
    		$zipcode = $userInfo['BusinessOwners']['zipcode'];
    		$address = $countryName .','.$zipcode;
    		$latLongVal = $this->Common->getLatLong($address);
    		/*------------------for ajax------------------*/
    		if($this->request->is('ajax') &&  $this->request->is('post')) {
    			$this->Session->write('mycookie',$this->request->data);
    			$this->layout = false;
    			$groupData = $this->request->data['group'];
    			$milesData = $this->request->data['milesfilter'];
    			$storeId = $userInfo['BusinessOwners']['profession_id'];

    			if($groupData == 'global'){
    				$resultData = $this->getGlobalData($this->request->data,$storeId);
    			}
    			$count = 0;
    			if(!empty($resultData)) {
    				foreach($resultData as $data) {
    					$data2[$count]['Group']['groupType'] = $data['Group']['group_type'];
    					$data2[$count]['Group']['id'] = $data['Group']['id'];
    					$data2[$count]['Group']['slot_id'] = $data['AvailableSlots']['slot_id'];
    					$data2[$count]['Group']['meetingDate'] = $data['Group']['first_meeting_date'];
    					$data2[$count]['Group']['meetingTime'] = $data['Group']['meeting_time'];
    					$data2[$count]['Group']['countryName'] = $data['Country']['country_name'];
    					$data2[$count]['Group']['stateName'] = $data['State']['state_subdivision_name'];
    					$data2[$count]['Group']['members'] = $data['Group']['total_member'];
    					$data2[$count]['Group']['vacant'] = Configure::read('MAX_USER_IN_GROUP') - $data['Group']['total_member'];
    					$count++;
    				}
    			} else {
    				$data2 = null;
    			} 
    			$userInfo = $this->User->userInfoById($this->Encryption->decode($userId));
    			$this->set('userdata',$userInfo);
    			$this->set('groupData',$data2);
    			$this->set('count',count($data2));
    			$this->render('ajax_localGroups');
    		}
    		if ($this->request->is('ajax') && ($this->Session->check('mycookie') == true)) {
    			$sessData = $this->Session->read('mycookie');
    			$this->layout = false;
    			$storeId = $userInfo['BusinessOwners']['profession_id'];
    			if ($sessData['group'] == 'local') {
    				$resultData = $this->getLocalData($sessData, $storeId, $latLongVal);
    			}

    			if ($sessData['group'] == 'global'){
    				$resultData = $this->getGlobalData($sessData,$storeId);
    			}
    			$count = 0;
    			if (!empty($resultData)) {
    				foreach ($resultData as $data) {
    					$data2[$count]['Group']['groupType'] = $data['Group']['group_type'];
    					$data2[$count]['Group']['id'] = $data['Group']['id'];
    					$data2[$count]['Group']['slot_id'] = $data['AvailableSlots']['slot_id'];
    					$data2[$count]['Group']['meetingDate'] = $data['Group']['first_meeting_date'];
    					$data2[$count]['Group']['meetingTime'] = $data['Group']['meeting_time'];
    					$data2[$count]['Group']['countryName'] = $data['Country']['country_name'];
    					$data2[$count]['Group']['stateName'] = $data['State']['state_subdivision_name'];
    					$data2[$count]['Group']['members'] = $data['Group']['total_member'];
    					$data2[$count]['Group']['vacant'] = Configure::read('MAX_USER_IN_GROUP') - $data['Group']['total_member'];
    					$count++;
    				}
    			} else {
    				$data2 = null;
    			} 
    			$userInfo = $this->User->userInfoById($this->Encryption->decode($userId));
    			$this->set('userdata',$userInfo);
    			$this->set('groupData',$data2);
    			$this->set('count',count($data2));
    			$this->render('ajax_localGroups');
    		}
    		/*------------------for ajax------------------*/
    		else {
    			$lat = $latLongVal['lat'];
    			$long = $latLongVal['lng'];
    			$mileCal = Configure::read('MILESTOKM') * Configure::read('DEFAULT_MILES');
				$latLongValue = $mileCal / Configure::read('LATLONGDEGREE');
    			$storeId = $userInfo['BusinessOwners']['profession_id'];
    			$conditions = array(
    							'Group.is_active'=> 1, 
    							'total_member <' => Configure::read('MAX_USER_IN_GROUP'),
    							'group_type'=>'local',
    							'NOT FIND_IN_SET(\''. $storeId .'\',Group.group_professions)',
    							'Group.lat BETWEEN '.($lat - $latLongValue).' AND '.($lat + $latLongValue),
								'Group.long BETWEEN '.($long - $latLongValue).' AND '.($long + $latLongValue)
    							);
    			
    			$this->Paginator->settings = array(
    				'conditions' => $conditions,
    				'order' => 'Group.id DESC',
    				'limit' => Configure::read('AJAX_LOAD')
    				);
    			$resultData = $this->Paginator->paginate('Group');
    			$count = 0;
    			if(!empty($resultData)) {
    				foreach($resultData as $data) {
    					$data2[$count]['Group']['groupType'] = $data['Group']['group_type'];
    					$data2[$count]['Group']['id'] = $data['Group']['id'];
    					$data2[$count]['Group']['slot_id'] = $data['AvailableSlots']['slot_id'];
    					$data2[$count]['Group']['meetingDate'] = $data['Group']['first_meeting_date'];
    					$data2[$count]['Group']['meetingTime'] = $data['Group']['meeting_time'];
    					$data2[$count]['Group']['countryName'] = $data['Country']['country_name'];
    					$data2[$count]['Group']['stateName'] = $data['State']['state_subdivision_name'];
    					$data2[$count]['Group']['members'] = $data['Group']['total_member'];
    					$data2[$count]['Group']['vacant'] = Configure::read('MAX_USER_IN_GROUP') - $data['Group']['total_member'];
    					$count++;
    				}
    			} else {
    				$data2 = null;
    			}                 
    		}
    		$userInfo = $this->User->userInfoById($this->Encryption->decode($userId));
    		$this->set('userdata',$userInfo);
    		$this->set('groupData',$data2);
    		$this->set('count',count($data2));
    	} else {
    		$this->redirect(array('controller' => 'pages', 'action' => 'home'));  
    	}        
    }

    /**
    * Select Group
    * @param string $userId user id
    * @param string $groupId group id
    * @author Gaurav Bhandari
    */
    public function selectGroup($groupId, $userId)
    {
        $this->layout = false;
        $this->autoRender = false;
        $group_leader_id = NULL;
        $userData = $this->User->userInfoById($this->Encryption->decode($userId));
        $professionId = $userData['BusinessOwners']['profession_id'];
        $condition = array('Group.id' => $this->Encryption->decode($groupId), 'Group.is_active' => 1, 'Group.total_member <' => Configure::read('MAX_USER_IN_GROUP'), 'NOT FIND_IN_SET(\''. $professionId .'\',Group.group_professions)');
        $groupInfo = $this->Group->find('first', array('conditions' => $condition));
        if (empty($userData['BusinessOwners']['group_id'])) {
            if (!empty($groupInfo)) {
                $trxData=$this->Transaction->find('first',array('conditions'=>array('Transaction.user_id'=>$this->Encryption->decode($userId),'Transaction.group_type'=>NULL)));
                if(!empty($trxData)) {
                    $grpType = $groupInfo['Group']['group_type'];
                    $this->Transaction->updateAll(array('Transaction.group_type' => "'$grpType'"),
                        array('Transaction.user_id' => $this->Encryption->decode($userId),'Transaction.group_type'=>NULL) );
                }
                if($groupInfo['Group']['total_member'] == 0) {
                    $group_role = 2;
                    $column = 'Group.group_leader_id';
                    $group_leader_id = $this->Encryption->decode($userId);
                } else if($groupInfo['Group']['total_member'] == 1) {
                    $group_role = 3;
                    $column = 'Group.group_coleader_id';
                    $group_leader_id = $this->Encryption->decode($userId);
                } else {
                    $group_role = 4;
                    $group_leader_id = $groupInfo['Group']['group_coleader_id'];
                    $column = 'Group.group_coleader_id';
                }
                $data = array(
                    'BusinessOwner.group_id' => $this->Encryption->decode($groupInfo['Group']['id']),
                    'BusinessOwner.group_role' => $group_role
                    );
                //$userData = $this->User->userInfoById($this->Encryption->decode($userId));
                if($userData['User']['is_active'] == 1) {
                    $emailLib = new Email();
                    $subject = empty($userData['User']['reactivate']) ? "Welcome to FoxHopr" : "Welcome back to FoxHopr";
                    $template = "activation_email";
                    $format = "both";            
                    $variable = array(
                        'role' => $group_role,
                        'businessowner'=>$userData['BusinessOwners']['fname'].' '.$userData['BusinessOwners']['lname'],
                        'groupname' => Configure::read('GROUP_PREFIX').' '.$this->Encryption->decode($groupInfo['Group']['id']),
                        'meetingdate'=> date('m-d-Y',strtotime($groupInfo['Group']['first_meeting_date'])),
                        'meetingtime'=>date('g:i A',strtotime($groupInfo['Group']['meeting_time'])),
                        'reactivated'=>$userData['User']['reactivate']
                        );
                    $to = $userData['User']['user_email'];
                    $success = $emailLib->sendEmail($to,$subject,$variable,$template,$format); 
                }
                if($groupInfo['Group']['group_professions'] != '') {
                    $groupProfession = $groupInfo['Group']['group_professions'] . ',' . $userData['BusinessOwners']['profession_id'];
                } else {
                    $groupProfession = $userData['BusinessOwners']['profession_id'];
                }
                $this->Group->updateAll(
                    array('Group.total_member' => 'total_member + 1','Group.group_professions' =>  "'". $groupProfession."'",$column =>  "'". $group_leader_id."'"),
                    array( 'Group.id' => $this->Encryption->decode($groupInfo['Group']['id'])));
                $this->BusinessOwner->updateAll($data,array( 'BusinessOwner.user_id' => $this->Encryption->decode($userId)));
                
                // sent message live feed            
                $groupMembers = $this->BusinessOwner->getMyGroupMemberList($this->Encryption->decode($groupId),$this->Encryption->decode($userId));
                foreach ($groupMembers as $groupMemberId => $groupMemberList){
                    $this->LiveFeed->create();
                    $liveFeedData['LiveFeed']['to_user_id'] 	= $groupMemberId;
                    $liveFeedData['LiveFeed']['from_user_id'] 	= $this->Encryption->decode($userId);
                    $liveFeedData['LiveFeed']['group_id'] = $this->Encryption->decode($groupId);
                    $liveFeedData['LiveFeed']['feed_type'] 		= "newmember";
                    $this->LiveFeed->save($liveFeedData);
                }
                $this->Session->delete('UID');
                $sessionCheck = $this->Session->read('Auth.Front.id');
                if (!empty($userData['User']['reactivate']) || !empty($sessionCheck)) {
                    $this->Session->write('Auth.Front.BusinessOwners.group_id', $this->Encryption->decode($groupInfo['Group']['id']));
                    $this->Session->write('Auth.Front.Groups', $groupInfo['Group']);
                }
                $this->Session->setFlash(__('Thanks for being the part of FoxHopr community.'),'Front/flash_good');
                $this->Session->setFlash(__('Thanks for being the part of FoxHopr community.'),'Front/flash_good');
                //$this->redirect(array('controller' => 'referrals', 'action' => 'send-referrals'));
            } else {
                $this->Session->setFlash(__('This group is no longer available. Please select another group.'),'Front/flash_bad');
                //$this->redirect(array('controller' => 'groups', 'action' => 'group-selection'));
            }
        } else {
			$userSessionCheck = $this->Session->read('Auth.Front.id');
			if (!empty($userSessionCheck)) {
	            $this->Session->write('Auth.Front.BusinessOwners.group_id', $userData['BusinessOwners']['group_id']);
			}
            $this->Session->setFlash(__('You have already joined a group.'),'Front/flash_bad');
            //$this->redirect(array('controller' => 'dashboard', 'action' => 'dashboard'));
        }
    }
    
    /**
     * User can create group from frontend
     * @author Jitendra Sharma
     */
    public function createGroup()
    {
    	$this->layout = 'front';
    	$this->includePageJs = array('userpanel_validation');
    	//$getStateList = "";
    	/*if ($this->request->is('post')) {
    		$countryData = $this->Country->find('first',array('conditions'=>array('Country.country_iso_code_2' => $this->request->data['Group']['country_id'])));
    		$this->request->data['Group']['state_id'] = $this->request->data['BusinessOwner']['state_id'];
    		$this->request->data['Group']['first_meeting_date'] = $meetingDate = date("Y-m-d", strtotime(str_replace('-', '/', $this->request->data['Group']['first_meeting_date'])));
    		$this->request->data['Group']['second_meeting_date'] = date("Y-m-d", strtotime(str_replace('-', '/', $this->request->data['Group']['second_meeting_date'])));
    		$meeting_time = $this->request->data['Group']['meeting_time'].":00";
    		// check for available time slot
    		$slotAvailable = $this->Group->find('count',array('conditions'=>array('Group.first_meeting_date'=>$meetingDate,'Group.meeting_time'=>$meeting_time)));
    		if($slotAvailable >=1 ){
    			$this->Session->setFlash(__('A group has already created for this meeting schedule.'), 'Front/flash_bad');
    		}else{    			
    			$userId = $this->Encryption->decode($this->Session->read('UID'));
                $businessOwner = $this->BusinessOwner->findByUserId($userId,'BusinessOwner.id,BusinessOwner.fname,BusinessOwner.lname,BusinessOwner.email,BusinessOwner.profession_id');
                $latLong = $this->Common->getLatLong($countryData['Country']['country_name'].','.$this->request->data['Group']['zipcode']);
    			$this->request->data['Group']['lat'] = $latLong['lat'];
    			$this->request->data['Group']['long'] = $latLong['lng'];
    			$this->request->data['Group']['group_created_by'] = $userId;
    			$this->request->data['Group']['group_modified_by'] = $userId;
    			$this->request->data['Group']['group_leader_id'] = $userId;
    			$this->request->data['Group']['total_member'] = 1;
                $this->request->data['Group']['group_professions'] = $businessOwner['BusinessOwner']['profession_id'];

    			unset($this->request->data['BusinessOwner']);
    			$this->Group->set($this->request->data);
    			if ($this->Group->save($this->request->data)) {
                    $trxData=$this->Transaction->find('first',array('conditions'=>array('Transaction.user_id'=>$userId,'Transaction.group_type'=>NULL)));
                    if(!empty($trxData)) {
                        $grpType = $this->request->data['Group']['group_type'];
                        $this->Transaction->updateAll(array('Transaction.group_type' => "'$grpType'"),
                            array('Transaction.user_id' => $userId,'Transaction.group_type'=>NULL));
                    }
                    $this->request->data['Group']['group_name'] = Configure::read('GROUP_PREFIX').' '.$this->Group->id;
                    $role = 'leader';
                    $this->BusinessOwner->updateAll(
                                    array('BusinessOwner.group_id' => $this->Group->id,'BusinessOwner.group_role'=> "'$role'"),
                                    array('BusinessOwner.id' => $this->Encryption->decode($businessOwner['BusinessOwner']['id']))
                                );
    				// send email to user
    				$emailLib = new Email();
    				$to = $businessOwner['BusinessOwner']['email'];
    				$subject = 'Group created successfully.';
    				$format = "both";
    				$template = 'group_create_byuser';
    				$user_name = $businessOwner['BusinessOwner']['fname']." ".$businessOwner['BusinessOwner']['lname'];
    				$variable = array('name' => $user_name,'groupname'=>$this->request->data['Group']['group_name']);
    				$success = $emailLib->sendEmail($to,$subject,$variable,$template,$format);
    				 
    				// send email to admin
    				$to = "bhanu.bhati@a3logics.in";
    				$subject = 'New group created by user';
    				$format = "html";
    				$template = 'group_create_notify';
    				$user_name = $businessOwner['BusinessOwner']['fname']." ".$businessOwner['BusinessOwner']['lname'];
    				$variable = array('name' => $user_name,'groupname'=>$this->request->data['Group']['group_name']);
    				$success1 = $emailLib->sendEmail($to,$subject,$variable,$template,$format);
    				$user = $this->Session->read('Auth.Front.id');
    				$this->Session->setFlash(__('Thank you for creating the group. Check your email for more details.'), 'Front/flash_good');
    				if(isset($user)){
                        $this->Session->delete('UID');
    					$this->redirect(array('controller' => 'pages', 'action' => 'home'));
    				}else{
    					$this->redirect(array('controller' => 'users', 'action' => 'login'));
    				}
    			} else {
    			    $validationErrors=$this->compileErrors('Group');
    			    if($validationErrors != NULL) {
    			        $this->Session->setFlash($validationErrors, 'flash_bad');
    			    }
    				//unset($this->request->data['Group']['first_meeting_date']);
    			}
    		}
    		
    		$country_id 	= $this->request->data['Group']['country_id'];
    		$getStateList 	= $this->Common->getStatesForCountry($country_id); 
    		$this->request->data['BusinessOwner']['state_id'] = $this->request->data['Group']['state_id'];
    		
    	}else{*/
    		$userId = $this->Session->read('UID');
    		$this->request->data['Group']['group_type'] = 'local';    		
    		if(empty($userId)) {
    			$this->redirect(array('controller' => 'users', 'action' => 'login'));
    		}
    	//}
    	
    	//$countries = $this->Common->getAllCountries();
    	$timezones = $this->Timezone->getAllTimezones();
    	//$this->set('countries', $countries);
    	$this->set('timezones', $timezones);
    	$this->set('includePageJs', $this->includePageJs);
    	//$this->set('stateList', $getStateList);
    }

    /**
    * Front End - Group Change after upgrade/downgrade
    * @author Gaurav Bhandari
    */    
    public function groupChange($checkRequest = NULL) 
    {
        $this->set('referer', $this->referer());
    	$checkRequest = $this->Encryption->decode($checkRequest);
        if(!$this->request->is('ajax')) {
           $this->Session->delete('mycookie');
           if(empty($checkRequest) || !in_array($checkRequest, array("local", "global", "change"))) {
    			$this->redirect(array('controller' => 'dashboard', 'action' => 'dashboard'));
    		}
        }
        $titleForLayout = "FoxHopr: Group Selection";
        $this->set(compact('titleForLayout'));
        $this->loadModel('Country');
        $this->loadModel('State');
        $this->loadModel('User');
        $this->layout = 'front';
        $userId = $this->Session->read('Auth.Front.id');
        if(!empty($userId)) {
            $userInfo = $this->User->userInfoById($this->Encryption->decode($userId));
            $countryInfo = $this->Country->find('first',array('conditions'=>array('Country.country_iso_code_2' => $userInfo['BusinessOwners']['country_id'])));
            $countryName = $countryInfo['Country']['country_name'];
            $zipcode = $userInfo['BusinessOwners']['zipcode'];
            $address = $countryName .','.$zipcode;
            $latLongVal = $this->Common->getLatLong($address);
            $userInfo = $this->User->userInfoById($this->Encryption->decode($userId));
            /*------------------for ajax------------------*/
            if($this->request->is('ajax') &&  $this->request->is('post')) {
                $this->Session->write('mycookie',$this->request->data);
                $this->layout = false;
                $weekArr = array();
                $countryArr = array();
                $stateArr = array();
                $groupData = $this->request->data['group'];
                $milesData = isset($this->request->data['milesfilter']) ? $this->request->data['milesfilter'] : '';
                $storeId = $userInfo['BusinessOwners']['profession_id'];
                if ($groupData == 'local') {
    				$resultData = $this->getLocalData($this->request->data, $storeId, $latLongVal);
    			}
                if ($groupData == 'global') {
    				$resultData = $this->getGlobalData($this->request->data, $storeId);
    			}
    			$count = 0;
                if(!empty($resultData)) {
                    foreach($resultData as $data) {
                    $data2[$count]['Group']['groupType'] = $data['Group']['group_type'];
                    $data2[$count]['Group']['id'] = $data['Group']['id'];
                    $data2[$count]['Group']['slot_id'] = $data['AvailableSlots']['slot_id'];
                    $data2[$count]['Group']['meetingDate'] = $data['Group']['first_meeting_date'];
                    $data2[$count]['Group']['meetingTime'] = $data['Group']['meeting_time'];
                    $data2[$count]['Group']['countryName'] = $data['Country']['country_name'];
                    $data2[$count]['Group']['stateName'] = $data['State']['state_subdivision_name'];
                    $data2[$count]['Group']['members'] = $data['Group']['total_member'];
                    $data2[$count]['Group']['vacant'] = Configure::read('MAX_USER_IN_GROUP') - $data['Group']['total_member'];
                    $count++;
                    }
                } else {
                    $data2 = null;
                } 
                $userInfo = $this->User->userInfoById($this->Encryption->decode($userId));
                $this->set('userdata',$userInfo);
                $this->set('groupData',$data2);
                $this->set('count',count($data2));
                $this->render('ajax_groupUpdate');             
            }
            if($this->request->is('ajax') && ($this->Session->check('mycookie') == true) && $this->request->is('get')) {
                $sessData = $this->Session->read('mycookie');
                $this->layout = false;
                $weekArr = array();
                $countryArr = array();
                $stateArr = array();
                $storeId = $userInfo['BusinessOwners']['profession_id'];
                if ($sessData['group'] == 'local') {
    				$resultData = $this->getLocalData($sessData, $storeId, $latLongVal);
    			}
                if ($sessData['group'] == 'global') {
    				$resultData = $this->getGlobalData($sessData, $storeId);
    			}
    			$count = 0;
                if(!empty($resultData)) {
                    foreach($resultData as $data) {
                    $data2[$count]['Group']['groupType'] = $data['Group']['group_type'];
                    $data2[$count]['Group']['id'] = $data['Group']['id'];
                    $data2[$count]['Group']['slot_id'] = $data['AvailableSlots']['slot_id'];
                    $data2[$count]['Group']['meetingDate'] = $data['Group']['first_meeting_date'];
                    $data2[$count]['Group']['meetingTime'] = $data['Group']['meeting_time'];
                    $data2[$count]['Group']['countryName'] = $data['Country']['country_name'];
                    $data2[$count]['Group']['stateName'] = $data['State']['state_subdivision_name'];
                    $data2[$count]['Group']['members'] = $data['Group']['total_member'];
                    $data2[$count]['Group']['vacant'] = Configure::read('MAX_USER_IN_GROUP') - $data['Group']['total_member'];
                    $count++;
                    }
                } else {
                    $data2 = null;
                } 
                $userInfo = $this->User->userInfoById($this->Encryption->decode($userId));
                $this->set('userdata',$userInfo);
                $this->set('groupData',$data2);
                $this->set('count',count($data2));
                $this->render('ajax_groupUpdate');
            }
            /*------------------for ajax------------------*/
            else {
                $currentGroupType = $userInfo['Groups']['group_type'];
                $lat = $latLongVal['lat'];
                $long = $latLongVal['lng'];
                $storeId = $userInfo['BusinessOwners']['profession_id'];
                switch ($checkRequest) {
                	case 'local':
                	$condition = array('Group.is_active' => 1,'total_member <' => Configure::read('MAX_USER_IN_GROUP'),'group_type'=>'global','NOT FIND_IN_SET(\''. $storeId .'\',Group.group_professions)') ;
                	$this->Paginator->settings = array(
                		'conditions' => $condition,
                		'order' => 'Group.id DESC',
                		'limit' => Configure::read('AJAX_LOAD')
                		);
                		break;
                	case 'global':
                        $mileCal = Configure::read('MILESTOKM') * Configure::read('DEFAULT_MILES');
                        $latLongValue = $mileCal / Configure::read('LATLONGDEGREE');
                        $condition = array(
                                        'Group.is_active'=> 1, 
                                        'total_member <' => Configure::read('MAX_USER_IN_GROUP'),
                                        'group_type'=>'local',
                                        'NOT FIND_IN_SET(\''. $storeId .'\',Group.group_professions)',
                                        'Group.lat BETWEEN '.($lat - $latLongValue).' AND '.($lat + $latLongValue),
                                        'Group.long BETWEEN '.($long - $latLongValue).' AND '.($long + $latLongValue)
                                        );
                        $this->Paginator->settings = array(
                            'conditions' => $condition,
                            'order' => 'Group.id DESC',
                            'limit' => Configure::read('AJAX_LOAD')
                        );
                		break;
                	case 'change':
                	if ($currentGroupType == 'local') {
                        $mileCal = Configure::read('MILESTOKM') * Configure::read('DEFAULT_MILES');
                        $latLongValue = $mileCal / Configure::read('LATLONGDEGREE');
                        $condition = array(
                                        'Group.is_active'=> 1, 
                                        'total_member <' => Configure::read('MAX_USER_IN_GROUP'),
                                        'group_type'=>'local',
                                        'NOT FIND_IN_SET(\''. $storeId .'\',Group.group_professions)',
                                        'Group.lat BETWEEN '.($lat - $latLongValue).' AND '.($lat + $latLongValue),
                                        'Group.long BETWEEN '.($long - $latLongValue).' AND '.($long + $latLongValue)
                                        );
                        $this->Paginator->settings = array(
                            'conditions' => $condition,
                            'order' => 'Group.id DESC',
                            'limit' => Configure::read('AJAX_LOAD')
                        );
                	} else if ($currentGroupType == 'global') {
                		$condition = array('Group.is_active' => 1,'total_member <' => Configure::read('MAX_USER_IN_GROUP'),'group_type'=>'global','NOT FIND_IN_SET(\''. $storeId .'\',Group.group_professions)') ;
                		$this->Paginator->settings = array(
                			'conditions' => $condition,
                			'order' => 'Group.id DESC',
                			'limit' => Configure::read('AJAX_LOAD')
                			);  
                	}
                		break;
                }
                $resultData = $this->Paginator->paginate('Group');
                $count = 0;
                if(!empty($resultData)) {
                    foreach($resultData as $data) {
	                    $data2[$count]['Group']['groupType'] = $data['Group']['group_type'];
	                    $data2[$count]['Group']['id'] = $data['Group']['id'];
	                    $data2[$count]['Group']['slot_id'] = $data['AvailableSlots']['slot_id'];
	                    $data2[$count]['Group']['meetingDate'] = $data['Group']['first_meeting_date'];
	                    $data2[$count]['Group']['meetingTime'] = $data['Group']['meeting_time'];
	                    $data2[$count]['Group']['countryName'] = $data['Country']['country_name'];
	                    $data2[$count]['Group']['stateName'] = $data['State']['state_subdivision_name'];
	                    $data2[$count]['Group']['members'] = $data['Group']['total_member'];
	                    $data2[$count]['Group']['vacant'] = Configure::read('MAX_USER_IN_GROUP') - $data['Group']['total_member'];
	                    $count++;
                    }
                } else {
                    $data2 = null;
                }
            }
            $this->set('userdata',$userInfo);
            $this->set('checkRequest',$checkRequest);
            $this->set('groupData',$data2);
            $this->set('count',count($data2));
        } else {
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));  
        }        
    }

    /**
    * Select Group
    * @param string $userId user id
    * @param string $groupId group id
    * @author Gaurav Bhandari
    */
    public function updateGroup($groupId,$requestType) 
    {
    	$requestType = $this->Encryption->decode($requestType);
        $userId = $this->Session->read('Auth.Front.id');
        $this->layout = false;
        $this->autoRender = false;
        $group_leader_id = NULL;
        $groupInfo = $this->Group->findById($this->Encryption->decode($groupId));
        $userData = $this->User->userInfoById($this->Encryption->decode($userId));
        switch ($requestType) {
        	case 'local':
        		$updateType = 'upgrade';
            	$subject2 = 'FoxHopr : Your Group has been upgraded';
            	$updateColumn = 'BusinessOwner.group_update';
        		break;
        	case 'global':
        		$updateType = 'downgrade';
        		$subject2 = 'FoxHopr : Your Group has been downgraded';
        		$updateColumn = 'BusinessOwner.group_update';
        		break;
        	case 'change':
        		$updateType = 'change';
        		$subject2 = 'FoxHopr : Your Group has been changed';
        		$updateColumn = 'BusinessOwner.group_change';
        		break;
        }
        //$updateType = ($userData['Groups']['group_type'] == 'global') ? 'downgrade' : 'upgrade';
        if(!empty($groupInfo)) {

        //Update Previous Information
        $userGroups = $this->BusinessOwner->find('all',array(
                        'fields' => array('BusinessOwner.id','BusinessOwner.user_id,BusinessOwner.group_id,BusinessOwner.email,BusinessOwner.fname,BusinessOwner.lname,BusinessOwner.group_role'),
                        'conditions'=>array('BusinessOwner.group_id' => $this->Encryption->decode($userData['Groups']['id']))));
        $emailLib = new Email();
        $format = "both";
        if($userData['BusinessOwners']['group_role'] == 'participant') {
            $this->BusinessOwner->id = $this->Encryption->decode($userData['BusinessOwners']['id']);
            $this->BusinessOwner->saveField('is_kicked', 0);
            $parts = explode(',', $userData['Groups']['group_professions']); 
            while(($i = array_search($userData['BusinessOwners']['profession_id'], $parts)) !== false) {
                unset($parts[$i]);
            }
            $updateProfessions = implode(',', $parts);
            $updateMember = $userData['Groups']['total_member'] - 1;
            $this->BusinessOwner->updateAll(array('Group.group_professions' => "'".$updateProfessions."'",'Group.total_member' =>"'".$updateMember."'"),array( 'Group.id' => $this->Encryption->decode($userData['Groups']['id'])));
        } else if($userData['BusinessOwners']['group_role'] == 'co-leader') {
            $this->BusinessOwner->id = $this->Encryption->decode($userData['BusinessOwners']['id']);
            $this->BusinessOwner->saveField('is_kicked', 0);
            foreach ($userGroups as $group) {
            $role = $group['BusinessOwner']['group_role'];
            switch ($role) {
                case 'participant':                 
                    $subject = "FoxHopr: Chance to be Group Co-Leader";
                    $template = "upgrade_membership_participant";
                    $business_owner_name = $group['BusinessOwner']['fname']." ".$group['BusinessOwner']['lname'];
                    $variable = array('businessowner'=>$business_owner_name,'case'=>'participant');
                    $to = $group['BusinessOwner']['email'];
                    $success = $emailLib->sendEmail($to,$subject,$variable,$template,$format); 
                    break;
                case 'co-leader':
                    $this->Group->updateAll(array('Group.group_coleader_id' => NULL),array( 'Group.id' => $group['BusinessOwner']['group_id']));
                    $parts = explode(',', $userData['Groups']['group_professions']); 
                    while(($i = array_search($userData['BusinessOwners']['profession_id'], $parts)) !== false) {
                        unset($parts[$i]);
                    }
                    $updateProfessions = implode(',', $parts);
                    $updateMember = $userData['Groups']['total_member'] - 1;
                    $this->BusinessOwner->updateAll(array('Group.group_professions' => "'".$updateProfessions."'",'Group.total_member' =>"'".$updateMember."'"),array( 'Group.id' => $group['BusinessOwner']['group_id']));
                    break;
                default:
                    break;
                }
            }
        } else {
            foreach ($userGroups as $group) {
            $role = $group['BusinessOwner']['group_role'];
            switch ($role) {
                case 'participant':
                    $subject = "FoxHopr: Chance to be Group Co-Leader";
                    $template = "upgrade_membership_participant";
                    $business_owner_name = $group['BusinessOwner']['fname']." ".$group['BusinessOwner']['lname'];
                    $variable = array('businessowner'=>$business_owner_name,'case'=>'participant');
                    $to = $group['BusinessOwner']['email'];
                    $success = $emailLib->sendEmail($to,$subject,$variable,$template,$format);
                    $this->BusinessOwner->id = $this->Encryption->decode($group['BusinessOwner']['id']);
                    $this->BusinessOwner->saveField('is_kicked', 0);
                    break;
                case 'co-leader':
                    $this->BusinessOwner->updateAll(array('BusinessOwner.group_role' => '"leader"'),array( 'BusinessOwner.user_id' => $group['BusinessOwner']['user_id']));
                    $this->Group->updateAll(array('Group.group_leader_id' => $group['BusinessOwner']['user_id'],'Group.group_coleader_id' => NULL),array( 'Group.id' => $group['BusinessOwner']['group_id']));
                    $subject = "FoxHopr: Promoted as Group Leader";
                    $template = "upgrade_membership_coleader";
                    $business_owner_name = $group['BusinessOwner']['fname']." ".$group['BusinessOwner']['lname'];
                    $variable = array('businessowner'=>$business_owner_name,'case'=>'co-leader');
                    $to = $group['BusinessOwner']['email'];
                    $success = $emailLib->sendEmail($to,$subject,$variable,$template,$format);
                    $this->BusinessOwner->id = $this->Encryption->decode($group['BusinessOwner']['id']);
                    $this->BusinessOwner->saveField('is_kicked', 0);
                    break;
                case 'leader':
                    $parts = explode(',', $userData['Groups']['group_professions']); 
                    while(($i = array_search($userData['BusinessOwners']['profession_id'], $parts)) !== false) {
                        unset($parts[$i]);
                    }
                    $updateProfessions = implode(',', $parts);
                    $updateMember = $userData['Groups']['total_member'] - 1;
                    $this->BusinessOwner->updateAll(array('Group.group_professions' => "'".$updateProfessions."'",'Group.total_member' =>"'".$updateMember."'"),array( 'Group.id' => $group['BusinessOwner']['group_id']));
                    break;
                
                default:
                    break;
                }
            }
        }

        //Store previous group members information

        $gdata = $this->BusinessOwner->getMyGroupMemberList($this->Encryption->decode($userData['Groups']['id']),$this->Encryption->decode($userId));
        $prevMember = NULL;
        $prevRecord['PrevGroupRecord'] = array();
        foreach($gdata as $key => $val) {
            $data['user_id'] = $this->Encryption->decode($userId);
            $data['group_id'] = $this->Encryption->decode($userData['Groups']['id']);
            $data['members_id'] = $key;
            array_push($prevRecord['PrevGroupRecord'],$data);
        }
        $this->PrevGroupRecord->saveAll($prevRecord['PrevGroupRecord']);

        //Assign New Group
            if($groupInfo['Group']['total_member'] == 0) {
                $group_role = 2;
                $column = 'Group.group_leader_id';
                $group_leader_id = $this->Encryption->decode($userId);
            } else if($groupInfo['Group']['total_member'] == 1) {
                $group_role = 3;
                $column = 'Group.group_coleader_id';
                $group_leader_id = $this->Encryption->decode($userId);
            } else {
                $group_role = 4;
                $group_leader_id = $groupInfo['Group']['group_coleader_id'];
                $column = 'Group.group_coleader_id';
            }
            $data = array(
                'BusinessOwner.group_id' => $this->Encryption->decode($groupInfo['Group']['id']),
                'BusinessOwner.group_role' => $group_role,
                $updateColumn => '"'.date("Y-m-d").'"',
                'BusinessOwner.is_kicked' => 0
                );

            //$subject = ($updateType == 'upgrade') ? 'FoxHopr : Your Group has been upgraded' : 'FoxHopr : Your Group has been downgraded';
            $template = "upgrade_downgrade_group";
            $format = "both";            
            $variable = array(
                'updatetype' => $updateType,
                'group_type_from'=>$userData['Groups']['group_type'],
                'group_type_to'=>$groupInfo['Group']['group_type'],
                'role' => $group_role,
                'businessowner'=>$userData['BusinessOwners']['fname'].' '.$userData['BusinessOwners']['lname'],
                'groupname' => Configure::read('GROUP_PREFIX').' '.$this->Encryption->decode($groupInfo['Group']['id']),
                'meetingdate'=> date('m-d-Y',strtotime($groupInfo['Group']['first_meeting_date'])),
                'meetingtime'=>date('g:i A',strtotime($groupInfo['Group']['meeting_time'])),
                );
            $to = $userData['User']['user_email'];
            $success = $emailLib->sendEmail($to,$subject2,$variable,$template,$format); 
            
            if($groupInfo['Group']['group_professions'] != '') {
                $groupProfession = $groupInfo['Group']['group_professions'] . ',' . $userData['BusinessOwners']['profession_id'];
            } else {
                $groupProfession = $userData['BusinessOwners']['profession_id'];
            }
            $this->Group->updateAll(
               array('Group.total_member' => 'total_member + 1','Group.group_professions' =>  "'". $groupProfession."'",$column =>  "'". $group_leader_id."'"),
               array( 'Group.id' => $this->Encryption->decode($groupInfo['Group']['id'])));
            $this->BusinessOwner->updateAll($data,array( 'BusinessOwner.user_id' => $this->Encryption->decode($userId)));
            // sent message live feed            
            $groupMembers = $this->BusinessOwner->getMyGroupMemberList($this->Encryption->decode($groupId),$this->Encryption->decode($userId));
            foreach ($groupMembers as $groupMemberId => $groupMemberList){
                $this->LiveFeed->create();
                $liveFeedData['LiveFeed']['to_user_id']     = $groupMemberId;
                $liveFeedData['LiveFeed']['from_user_id']   = $this->Encryption->decode($userId);
                $liveFeedData['LiveFeed']['feed_type']      = "newmember";
                $this->LiveFeed->save($liveFeedData);
            }
            //$this->GroupGoals->resetUserGoals($this->Encryption->decode($userId));
            $this->Session->setFlash(__('Your Group has been updated successfully!'),'Front/flash_good');
        } else {
            $this->Session->setFlash(__('Group does not exist!'),'Front/flash_bad');
            $this->redirect(array('controller' => 'groups', 'action' => 'group-selection'));
        }
    }
    
    /**
    * Group Change Request
    * @author Rohan Julka
    */    
    public function changeRequest($currentGroup = NULL)
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {            
            $userId = $this->Session->read('Auth.Front.id');            
            $userData = $this->User->userInfoById($this->Encryption->decode($userId));
            $this->GroupChangeRequest->create();
            $dataToInsert = array('business_owner_id'=>$this->Encryption->decode($userData['BusinessOwners']['id']),
                    'profession_id'=>$userData['BusinessOwners']['profession_id'],
                    'group_id' => $currentGroup,
                    'request_type'=>'cr',
                    'proposed_meeting_time' =>$userData['Groups']['meeting_time'],
                    'is_moved'=>0);
            if ($this->GroupChangeRequest->save($dataToInsert)) {
                // send user group change request email                
                $emailLib = new Email();
                $to = $userData['User']['user_email'];
                $subject = 'FoxHopr: Group Change Request';
                $format = "both";
                $template = 'user_group_change_request';
                $user_name = $userData['BusinessOwners']['fname']." ".$userData['BusinessOwners']['lname'];
                $variable = array('name' => $user_name);
                $userSuccess = $emailLib->sendEmail($to,$subject,$variable,$template,$format);
                
                // send Admin group change request email
                $adminUser = $this->User->find('first',array('conditions'=>array('User.user_type'=>'admin')));
                if (!empty($adminUser)) {
                    $emailLib = new Email();
                    $to = $adminUser['User']['user_email'];
                    $subject = 'FoxHopr: Group Change Request';
                    $format = "both";
                    $template = 'admin_group_change_request';                
                    $variable = array();
                    $adminSuccess = $emailLib->sendEmail($to,$subject,$variable,$template,$format);
                }                
            }
        }      
    }

    /**
    * used to check group available for all users
    * @param string $groupId group id string $userId user id
    * @author Gaurav Bhandari
    * @return array $groupMembersData
    */

    public function getAvailableGroupData($groupId = NULL , $userId = NULL)
    {
        $currentGroup = $this->Group->findById($this->Encryption->decode($groupId));
        /** to list group holders*/
        if (!empty($groupId) && empty($userId)) {
            $groupHolders = $this->BusinessOwner->find('all', array(
                'fields' => array('BusinessOwner.id,BusinessOwner.user_id,BusinessOwner.fname, BusinessOwner.lname, BusinessOwner.profession_id','Profession.profession_name,CASE BusinessOwner.group_role WHEN "leader" THEN 1 WHEN "co-leader" THEN 2 ELSE 3 END AS role'),
                'conditions' => array('group_id' => $this->Encryption->decode($groupId)),
                'order' => 'role'
            ));
        } else {
            $groupHolders = $this->BusinessOwner->find('all', array(
                'fields' => array('BusinessOwner.id,BusinessOwner.group_role,BusinessOwner.user_id,BusinessOwner.fname, BusinessOwner.lname, BusinessOwner.profession_id','Profession.profession_name'),
                'conditions' => array('user_id' => $this->Encryption->decode($userId))
            ));
        }
        
        $groupMembersData = array();
        if (!empty($groupHolders)) {
            $currentGroupId = $groupId;
            $firstMeetingDate = $currentGroup['Group']['first_meeting_date'];
            $secondMeetingDate = $currentGroup['Group']['second_meeting_date'];
            $meetingTime = $currentGroup['Group']['meeting_time'];
            $sameTimeAvailableGroups = $this->Group->find('list', array(
                'fields' => array('Group.id'),
                'conditions' => array('meeting_time' => $meetingTime,
                    'OR' => array('second_meeting_date' => array($firstMeetingDate, $secondMeetingDate), 'first_meeting_date' => array($firstMeetingDate, $secondMeetingDate)),
                    'Group.id !=' => $this->Encryption->decode($groupId),
                    'Group.group_type' => $currentGroup['Group']['group_type'])
            ));
            
            if (!empty($sameTimeAvailableGroups)) {
                foreach ($groupHolders as $groupUser) {
                    $userProfessionId = $groupUser['BusinessOwner']['profession_id'];
                    foreach ($sameTimeAvailableGroups as $key => $availableGroup) {
                        if ($this->Group->isProfessionOccupiedInGroup($this->Encryption->decode($key), $userProfessionId)) {
                            $cannotDelete = 'not be deleted';
                        } else {
                            $groupUser['BusinessOwner']['Group'][$this->Encryption->decode($key)] = Configure::read('GROUP_PREFIX').' '.$this->Encryption->decode($availableGroup);
                        }
                    }
                    $groupMembersData[] = $groupUser;
                }
                return $groupMembersData;
            }
        }
    }

    /**
    * used to update group info for the users
    * @param string $previousGroupId previous group id , string $groupId current group id, string $userId user id
    * @author Gaurav Bhandari
    */

    public function updateGroupInfo($previousGroupId = Null , $groupId = NULL , $userId = NULL)
    {
        $groupInfo = $this->Group->findById($groupId);
        $userData = $this->User->userInfoById($userId);        

        //Assign New Group
        if($groupInfo['Group']['total_member'] == 0) {
            $group_role = 2;
            $column = 'Group.group_leader_id';
            $group_leader_id = $userId;
        } else if($groupInfo['Group']['total_member'] == 1) {
            $group_role = 3;
            $column = 'Group.group_coleader_id';
            $group_leader_id = $userId;
        } else {
            $group_role = 4;
            $group_leader_id = $groupInfo['Group']['group_coleader_id'];
            $column = 'Group.group_coleader_id';
        }
        $data['group_id'] = $groupId;
        $data['group_role'] = $group_role;
        $data['is_kicked'] = 0;        

        if($groupInfo['Group']['group_professions'] != '') {
            $groupProfession = $groupInfo['Group']['group_professions'] . ',' . $userData['BusinessOwners']['profession_id'];
        } else {
            $groupProfession = $userData['BusinessOwners']['profession_id'];
        }
        $this->Group->updateAll(
           array('Group.total_member' => 'total_member + 1','Group.group_professions' =>  "'". $groupProfession."'",$column =>  "'". $group_leader_id."'"),
           array( 'Group.id' => $this->Encryption->decode($groupInfo['Group']['id'])));
        $this->loadModel('BusinessOwners');
        $this->BusinessOwners->id = $this->Encryption->decode($userData['BusinessOwners']['id']);
        $this->BusinessOwners->save($data);
        //$this->GroupGoals->resetUserGoals($userId);

        // sent message live feed            
        $groupMembers = $this->BusinessOwner->getMyGroupMemberList($groupId,$userId);
        foreach ($groupMembers as $groupMemberId => $groupMemberList){
            $this->LiveFeed->create();
            $liveFeedData['LiveFeed']['to_user_id']     = $groupMemberId;
            $liveFeedData['LiveFeed']['from_user_id']   = $userId;
            $liveFeedData['LiveFeed']['feed_type']      = "newmember";
            $this->LiveFeed->save($liveFeedData);
        }
    }

	/**
    * used to get the members list of a group
    * @param string $groupId group id
    * @author Priti Kabra
    */
    public function admin_getGroupMembersList($groupId = NULL)
    {
        if ($this->Group->exists($this->Encryption->decode($groupId))) {
            $groupMembers = $this->BusinessOwner->getGroupMembers($groupId);
            $this->set(compact('groupMembers'));
        } else {
            $this->Session->setFlash('Group does not exist', 'flash_bad');
            return $this->redirect(array('action' => 'index', 'controller' => 'groups', 'admin' => true));
        }
    }

    /**
    * used to get global group listing 
    * @param array $data request data
    * @param int $professionId profession id
    * @author Gaurav
    */
    public function getGlobalData($data = NULL , $professionId = NULL)
    {
    	$weekArr = array();
		$countryArr = array();
		$stateArr = array();
    	// $condition = array('Group.is_active' => 1,'total_member <' => 20,'group_type'=>'global','NOT FIND_IN_SET(\''. $professionId .'\',Group.group_professions)') ;
    	// if(isset($data['searchbylocation']) && $data['searchbylocation'] != '') {
    	// 	$searchByLocation = $data['searchbylocation'];
    	// 	$countryData = $this->Country->find('all',array('conditions'=>array('Country.country_name LIKE' => '%'.trim($searchByLocation).'%')));
    	// 	if(!empty($countryData)) {                    
    	// 		foreach($countryData as $country) {
    	// 			array_push($countryArr,$country['Country']['country_iso_code_2']);                                
    	// 		}
    	// 		$orConditions1 = array('Group.country_id' => $countryArr);
    	// 	}                     
    	// 	$stateData = $this->State->find('all',array('conditions'=>array('State.state_subdivision_name LIKE' => '%'.trim($searchByLocation).'%')));
    	// 	if(!empty($stateData)) {                    
    	// 		foreach($stateData as $state) {
    	// 			array_push($stateArr,$state['State']['state_subdivision_id']);
    	// 		}
    	// 		$orConditions2 = array('Group.state_id' => $stateArr);
    	// 	}
    	// 	$orConditions3 = array('Group.city LIKE' => '%'.trim($searchByLocation).'%');
    	// 	if(isset($orConditions1)) {
    	// 		$condition['or'] = $orConditions1 + $orConditions3;
    	// 	}
    	// 	else if(isset($orConditions2)) {
    	// 		$condition['or'] = $orConditions2 + $orConditions3;
    	// 	}
    	// 	else if(isset($orConditions1) && isset($orConditions2)) {
    	// 		$condition['or'] = $orConditions1 + $orConditions2 + $orConditions3;
    	// 	}
    	// 	else {
    	// 		$condition['or'] = $orConditions3;
    	// 	}
    	// }
    	// if(isset($data['day']) && isset($data['time'])) {
    	// 	foreach($data['day'] as $day) {
    	// 		array_push($weekArr,$day);
    	// 	}
    	// 	$timeConditions['OR'] = array();
    	// 	foreach($data['time'] as $time){
    	// 		$timeArray = explode('-',$time);
    	// 		$timeConditions['OR'][] = 'Group.meeting_time BETWEEN "'.trim($timeArray[0]).'" AND "'.trim($timeArray[1]).'"';
    	// 	}
    	// 	$condition['DAYNAME(first_meeting_date)'] = $weekArr;
    	// 	$condition['AND'] = $timeConditions;
    	// 	if(isset($data['searchbylocation']) && $data['searchbylocation'] != '') {
    	// 		$orConditions4 = array('Group.city LIKE' => '%'.trim($searchByLocation).'%'); 
    	// 	} else {
    	// 		$orConditions4 = array();
    	// 	}
    	// 	if(isset($orConditions)) {
    	// 		$condition['or'] = $orConditions + $orConditions4;
    	// 	}
    	// } else if(isset($data['day']) || isset($data['time'])) {
    	// 	if(isset($data['searchbylocation']) && $data['searchbylocation'] != '') {
    	// 		$orConditions4 = array('Group.city LIKE' => '%'.trim($searchByLocation).'%'); 
    	// 	} else {
    	// 		$orConditions4 = array();
    	// 	}
    	// 	if(isset($data['day'])) {
    	// 		foreach($data['day'] as $day) {
    	// 			array_push($weekArr,$day);
    	// 		}
    	// 		$condition['DAYNAME(first_meeting_date)'] = $weekArr;
    	// 		if(isset($orConditions)) {
    	// 			$condition['or'] = $orConditions + $orConditions4;
    	// 		}
    	// 	}
    	// 	if(isset($data['time'])) {
    	// 		$timeConditions['OR'] = array();
    	// 		foreach($data['time'] as $time){
    	// 			$timeArray = explode('-',$time);
    	// 			$timeConditions['OR'][] = 'Group.meeting_time BETWEEN "'.trim($timeArray[0]).'" AND "'.trim($timeArray[1]).'"';
    	// 		}
    	// 		if(isset($orConditions)) {
    	// 			$condition['or'] = $orConditions + $orConditions4;
    	// 		}
    	// 		$condition['AND'] = $timeConditions;
    	// 	}
    	// }
    	// if (isset($data['sorting'])) {
    	// 	$order = 'Group.'.$data['sorting'] . ' DESC';
    	// } else {
    	// 	$order = 'Group.id DESC';
    	// }
    	// $this->Paginator->settings = array(
    	// 	'conditions' => $condition,
    	// 	'order' => $order,
    	// 	'limit' => 20
    	// 	);
    	// $resultData = $this->Paginator->paginate('Group');
    	$condition = array('Group.is_active' => 1,'total_member <' => Configure::read('MAX_USER_IN_GROUP'),'group_type'=>'global','NOT FIND_IN_SET(\''. $professionId .'\',Group.group_professions)') ;
        if(isset($data['searchbylocation']) && $data['searchbylocation'] != '') {
            $searchByLocation = $data['searchbylocation'];
            $countryData = $this->Country->find('all',array('conditions'=>array('Country.country_name LIKE' => '%'.trim($searchByLocation).'%')));
            if(!empty($countryData)) {                    
                foreach($countryData as $country) {
                    array_push($countryArr,$country['Country']['country_iso_code_2']);                                
                }
                $orConditions1 = array('Group.country_id' => $countryArr);
            }                        
            $stateData = $this->State->find('all',array('conditions'=>array('State.state_subdivision_name LIKE' => '%'.trim($searchByLocation).'%')));
            if(!empty($stateData)) {                    
                foreach($stateData as $state) {
                    array_push($stateArr,$state['State']['state_subdivision_id']);
                }
                $orConditions2 = array('Group.state_id' => $stateArr);
            }
            $orConditions3 = array('Group.city LIKE' => '%'.trim($searchByLocation).'%');
            $condition['or'] = $orConditions3;
            if(isset($orConditions1)) {
                $condition['or'] = $orConditions1 + $orConditions3;
            }
            if(isset($orConditions2)) {
                $condition['or'] = $orConditions2 + $orConditions3;
            }
            if(isset($orConditions1) && isset($orConditions2)) {
                 $condition['or'] = $orConditions1 + $orConditions2 + $orConditions3;
            }
        }
        if(isset($data['day']) && isset($data['time'])) {
            foreach($data['day'] as $day) {
                array_push($weekArr,$day);
            }
            $timeConditions['OR'] = array();
            foreach($data['time'] as $time){
                $timeArray = explode('-',$time);
                $timeConditions['OR'][] = 'Group.meeting_time BETWEEN "'.trim($timeArray[0]).'" AND "'.trim($timeArray[1]).'"';
            }
            $condition['DAYNAME(first_meeting_date)'] = $weekArr;
            $condition['AND'] = $timeConditions;
            if(isset($data['searchbylocation']) && $data['searchbylocation'] != '') {
                $orConditions4 = array('Group.city LIKE' => '%'.trim($searchByLocation).'%'); 
            } else {
                $orConditions4 = array();
            }
            if(isset($orConditions)) {
                $condition['or'] = $orConditions + $orConditions4;
            }
        } else if(isset($data['day']) || isset($data['time'])) {
            if(isset($data['searchbylocation']) && $data['searchbylocation'] != '') {
                $orConditions4 = array('Group.city LIKE' => '%'.trim($searchByLocation).'%'); 
            } else {
                $orConditions4 = array();
            }
            if(isset($data['day'])) {
                foreach($data['day'] as $day) {
                    array_push($weekArr,$day);
                }
                $condition['DAYNAME(first_meeting_date)'] = $weekArr;
                if(isset($orConditions)) {
                    $condition['or'] = $orConditions + $orConditions4;
                }
            }
            if(isset($data['time'])) {
                $timeConditions['OR'] = array();
                foreach($data['time'] as $time){
                    $timeArray = explode('-',$time);
                    $timeConditions['OR'][] = 'Group.meeting_time BETWEEN "'.trim($timeArray[0]).'" AND "'.trim($timeArray[1]).'"';
                }
                 if(isset($orConditions)) {
                    $condition['or'] = $orConditions + $orConditions4;
                }
                $condition['AND'] = $timeConditions;
            }
        }
        if (isset($data['sorting'])) {
            $order = 'Group.'.$data['sorting'] . ' DESC';
        } else {
            $order = 'Group.id DESC';
        }

        $this->Paginator->settings = array(
            'conditions' => $condition,
            'order' => $order,
            'limit' => Configure::read('AJAX_LOAD')
        );
        $resultData = $this->Paginator->paginate('Group');
    	return $resultData;
    }

    /**
    * used to get local group listing 
    * @param array $data request data
    * @param int $professionId profession id
    * @param array $latLongVal latitude longitude value
    * @author Gaurav
    */
    public function getLocalData($data, $professionId, $latLongVal)
    {
    	$weekArr = array();
		$countryArr = array();
		$stateArr = array();
		$miles = $data['milesfilter'];
		$lat = $latLongVal['lat'];
		$long = $latLongVal['lng'];
		$mileCal = Configure::read('MILESTOKM') * $miles;
		$latLongValue = $mileCal / Configure::read('LATLONGDEGREE');
        $condition = array(
						'Group.is_active'=> 1, 
						'total_member <' => Configure::read('MAX_USER_IN_GROUP'),
						'group_type'=>'local',
						'NOT FIND_IN_SET(\''. $professionId .'\',Group.group_professions)',
						'Group.lat BETWEEN '.($lat - $latLongValue).' AND '.($lat + $latLongValue),
						'Group.long BETWEEN '.($long - $latLongValue).' AND '.($long + $latLongValue)
						);
        if(isset($data['day']) && isset($data['time'])) {
            foreach($data['day'] as $day) {
                array_push($weekArr,$day);
            }
            $timeConditions['OR'] = array();
            foreach($data['time'] as $time){
                $timeArray = explode('-',$time);
                $timeConditions['OR'][] = 'Group.meeting_time BETWEEN "'.trim($timeArray[0]).'" AND "'.trim($timeArray[1]).'"';
            }
            $condition['DAYNAME(first_meeting_date)'] = $weekArr;
            $condition['AND'] = $timeConditions;
            if(isset($orConditions)) {
                $condition['or'] = $orConditions;
            }
        } else if(isset($data['day']) || isset($data['time'])) {
            if(isset($data['day'])) {
                foreach($data['day'] as $day) {
                    array_push($weekArr,$day);
                }
                $condition['DAYNAME(first_meeting_date)'] = $weekArr;
                if(isset($orConditions)) {
                    $condition['or'] = $orConditions;
                }
            }
            if(isset($data['time'])) {
                $timeConditions['OR'] = array();
                foreach($data['time'] as $time){
                    $timeArray = explode('-',$time);
                    $timeConditions['OR'][] = 'Group.meeting_time BETWEEN "'.trim($timeArray[0]).'" AND "'.trim($timeArray[1]).'"';
                }
                 if(isset($orConditions)) {
                    $condition['or'] = $orConditions;
                }
                $condition['AND'] = $timeConditions;
            }
        }
        if (isset($data['sorting'])) {
            $order = 'Group.'.$data['sorting'] . ' DESC';
        } else {
            $order = 'Group.id DESC';
        }
		$this->Paginator->settings = array(
			'conditions' => $condition,
			'order' => $order,
			'limit' => Configure::read('AJAX_LOAD')
			);
        $resultData = $this->Paginator->paginate('Group');
		return $resultData;
    }

    /**
    * used to get group listing for web services
    * @author Priti Kabra
    */
    public function api_getGroups()
    {
        $errMsg = $this->checkApiHeaderInfo();       
		$error = !empty($errMsg) ? 1 : 0;
        if ($error == 0) {
            $userInfo = $this->User->userInfoById($this->loggedInUserId);
            $countryName = $userInfo['Country']['country_name'];
            $zipcode = $userInfo['BusinessOwners']['zipcode'];
            $latLongVal = $this->Common->getLatLong($countryName .','.$zipcode);
            $filterData = $this->jsonDecodedRequestedData;
            $weekArr = array();
            $countryArr = array();
            $stateArr = array();
            $professionId = $userInfo['BusinessOwners']['profession_id'];
            if ($filterData->group == 'local') {
                $resultData = $this->getLocalApiData($filterData, $professionId, $latLongVal);
            }

            if ($filterData->group == 'global') {
                $resultData = $this->getGlobalApiData($filterData, $professionId);
            }
            $count = 0;
            if (!empty($resultData['Group'])) {
                foreach ($resultData['Group'] as $data) {
                    $result[$count]['groupType'] = $data['Group']['group_type'];
                    $result[$count]['id'] = $data['Group']['id'];
                    $result[$count]['groupName'] = 'Group '. $this->Encryption->decode($data['Group']['id']);
                    $result[$count]['meetingDate'] = $data['Group']['first_meeting_date'];
                    $result[$count]['meetingTime'] = $data['Group']['meeting_time'];
                    $result[$count]['countryName'] = $data['Country']['country_name'];
                    $result[$count]['stateName'] = $data['State']['state_subdivision_name'];
                    $result[$count]['members'] = $data['Group']['total_member'];
                    $result[$count]['vacant'] = Configure::read('MAX_USER_IN_GROUP') - $data['Group']['total_member'];
                    $count++;
                }
                $this->set(array(
                    'code' => Configure::read('RESPONSE_SUCCESS'),
                    'result' => $result,
                    'message' => '',
                    'page_no' => $this->jsonDecodedRequestedData->page_no,
                    'totalGroups' => $resultData['totalGroups'],
                    '_serialize' => array('code', 'result', 'message', 'page_no', 'totalGroups')
                ));
            } else {
                $this->errorMessageApi('');
            }
        } else {
            $this->errorMessageApi($errMsg);
        }
    }

    /**
    * used to get global group listing 
    * @param array $data request data
    * @param int $professionId profession id
    * @author Priti
    */
    public function getGlobalApiData($data = NULL, $professionId = NULL)
    {
    	$weekArr = array();
		$countryArr = array();
		$stateArr = array();
        if (isset($data->list) && $data->list != "groupSelect") {
            $condition = array('Group.id !=' =>  $this->loggedInUserGroupId, 'Group.is_active' => 1, 'total_member <' => Configure::read('MAX_USER_IN_GROUP'), 'group_type' => 'global','NOT FIND_IN_SET(\''. $professionId .'\',Group.group_professions)') ;
        } else {
            $condition = array('Group.is_active' => 1, 'total_member <' => Configure::read('MAX_USER_IN_GROUP'), 'group_type' => 'global','NOT FIND_IN_SET(\''. $professionId .'\',Group.group_professions)') ;
        }
        if (isset($data->searchbylocation) && $data->searchbylocation != '') {
            $searchByLocation = $data->searchbylocation;
            $countryData = $this->Country->find('all',array('conditions'=>array('Country.country_name LIKE' => '%'.trim($searchByLocation).'%')));
            if (!empty($countryData)) {
                foreach ($countryData as $country) {
                    array_push($countryArr, $country['Country']['country_iso_code_2']);
                }
                $orConditions1 = array('Group.country_id' => $countryArr);
            }                        
            $stateData = $this->State->find('all',array('conditions' => array('State.state_subdivision_name LIKE' => '%'.trim($searchByLocation).'%')));
            if (!empty($stateData)) {                    
                foreach ($stateData as $state) {
                    array_push($stateArr, $state['State']['state_subdivision_id']);
                }
                $orConditions2 = array('Group.state_id' => $stateArr);
            }
            $orConditions3 = array('Group.city LIKE' => '%'.trim($searchByLocation).'%');
            $condition['or'] = $orConditions3;
            if (isset($orConditions1)) {
                $condition['or'] = $orConditions1 + $orConditions3;
            }
            if (isset($orConditions2)) {
                $condition['or'] = $orConditions2 + $orConditions3;
            }
            if (isset($orConditions1) && isset($orConditions2)) {
                 $condition['or'] = $orConditions1 + $orConditions2 + $orConditions3;
            }
        }
        if (!empty($data->day) && !empty($data->time)) {
            foreach ($data->day as $day) {
                array_push($weekArr, $day);
            }
            $timeConditions['OR'] = array();
            foreach ($data->time as $time) {
                $timeArray = explode('-', $time);
                $timeConditions['OR'][] = 'Group.meeting_time BETWEEN "'.trim($timeArray[0]).'" AND "'.trim($timeArray[1]).'"';
            }
            $condition['DAYNAME(first_meeting_date)'] = $weekArr;
            $condition['AND'] = $timeConditions;
            if (isset($data->searchbylocation) && $data->searchbylocation != '') {
                $orConditions4 = array('Group.city LIKE' => '%'.trim($searchByLocation).'%'); 
            } else {
                $orConditions4 = array();
            }
            if (isset($orConditions)) {
                $condition['or'] = $orConditions + $orConditions4;
            }
        } else if (!empty($data->day) || !empty($data->time)) {
            if (isset($data->searchbylocation) && $data->searchbylocation != '') {
                $orConditions4 = array('Group.city LIKE' => '%'.trim($searchByLocation).'%'); 
            } else {
                $orConditions4 = array();
            }
            if (!empty($data->day)) {
                foreach ($data->day as $day) {
                    array_push($weekArr, $day);
                }
                $condition['DAYNAME(first_meeting_date)'] = $weekArr;
                if (isset($orConditions)) {
                    $condition['or'] = $orConditions + $orConditions4;
                }
            }
            if (!empty($data->time)) {
                $timeConditions['OR'] = array();
                foreach ($data->time as $time) {
                    $timeArray = explode('-', $time);
                    $timeConditions['OR'][] = 'Group.meeting_time BETWEEN "'.trim($timeArray[0]).'" AND "'.trim($timeArray[1]).'"';
                }
                 if (isset($orConditions)) {
                    $condition['or'] = $orConditions + $orConditions4;
                }
                $condition['AND'] = $timeConditions;
            }
        }
        if (!empty($data->sorting)) {
            $order = 'Group.'.$data->sorting . ' DESC';
        } else {
            $order = 'Group.id DESC';
        }
        $resultData['Group'] = $this->Group->find('all',
                            array('conditions' => $condition,
                                //'fields' => $fields,
                                'order' => $order,
                                'recursive' => 1,
                                'limit'=>$this->jsonDecodedRequestedData->record_per_page,
                                'page' => $this->jsonDecodedRequestedData->page_no
                            )
                        );
        $resultData['totalGroups'] = $this->Group->find('count',
                                                array('conditions' => $condition,
                                                    'recursive' => 1
                                                )
                                            );
    	return $resultData;
    }

    /**
    * used to get local group listing 
    * @param array $data request data
    * @param int $professionId profession id
    * @param array $latLongVal latitude longitude value
    * @author Priti
    */
    public function getLocalApiData($data, $professionId, $latLongVal)
    {
    	$weekArr = array();
		$countryArr = array();
		$stateArr = array();
		$miles = !empty($data->milesfilter) ? $data->milesfilter : Configure::read('DEFAULT_MILES');
		$lat = $latLongVal['lat'];
		$long = $latLongVal['lng'];
        //$condition = array('Group.is_active'=> 1, 'total_member <' => 20, 'group_type'=>'local', 'NOT FIND_IN_SET(\''. $professionId .'\',Group.group_professions)') ;
        $mileCal = Configure::read('MILESTOKM') * $miles;
		$latLongValue = $mileCal / Configure::read('LATLONGDEGREE');
        if (isset($data->list) && $data->list != "groupSelect") {
            $condition = array(
                'Group.id !=' =>  $this->loggedInUserGroupId,
                'Group.is_active'=> 1, 
                'total_member <' => Configure::read('MAX_USER_IN_GROUP'),
                'group_type'=>'local',
                'NOT FIND_IN_SET(\''. $professionId .'\',Group.group_professions)',
                'Group.lat BETWEEN '.($lat - $latLongValue).' AND '.($lat + $latLongValue),
                'Group.long BETWEEN '.($long - $latLongValue).' AND '.($long + $latLongValue)
			);
        } else {
            $condition = array(
                'Group.is_active'=> 1,
                'total_member <' => Configure::read('MAX_USER_IN_GROUP'),
                'group_type'=>'local',
                'NOT FIND_IN_SET(\''. $professionId .'\',Group.group_professions)',
                'Group.lat BETWEEN '.($lat - $latLongValue).' AND '.($lat + $latLongValue),
                'Group.long BETWEEN '.($long - $latLongValue).' AND '.($long + $latLongValue)
            );
        }
		
        if (!empty($data->day) && !empty($data->time)) {
            foreach ($data->day as $day) {
                array_push($weekArr, $day);
            }
            $timeConditions['OR'] = array();
            foreach ($data->time as $time) {
                $timeArray = explode('-', $time);
                $timeConditions['OR'][] = 'Group.meeting_time BETWEEN "'.trim($timeArray[0]).'" AND "'.trim($timeArray[1]).'"';
            }
            $condition['DAYNAME(first_meeting_date)'] = $weekArr;
            $condition['AND'] = $timeConditions;
            if (isset($orConditions)) {
                $condition['or'] = $orConditions;
            }
        } else if (!empty($data->day) || !empty($data->time)) {
            if (!empty($data->day)) {
                foreach ($data->day as $day) {
                    array_push($weekArr, $day);
                }
                $condition['DAYNAME(first_meeting_date)'] = $weekArr;
                if (isset($orConditions)) {
                    $condition['or'] = $orConditions;
                }
            }
            if (!empty($data->time)) {
                $timeConditions['OR'] = array();
                foreach ($data->time as $time) {
                    $timeArray = explode('-', $time);
                    $timeConditions['OR'][] = 'Group.meeting_time BETWEEN "'.trim($timeArray[0]).'" AND "'.trim($timeArray[1]).'"';
                }
                 if (isset($orConditions)) {
                    $condition['or'] = $orConditions;
                }
                $condition['AND'] = $timeConditions;
            }
        }
        if (!empty($data->sorting)) {
            $order = 'Group.'.$data->sorting . ' DESC';
        } else {
            $order = 'Group.id DESC';
        }
        $resultData['Group'] = $this->Group->find('all',
                            array('conditions' => $condition,
                                'order' => $order,
                                'recursive' => 1,
                                'limit' => $this->jsonDecodedRequestedData->record_per_page,
                                'page' => $this->jsonDecodedRequestedData->page_no
                            )
                        );
	$countGroup = $this->Group->find('all',
                            array('conditions' => $condition,
                                'order' => $order,
                                'recursive' => 1
                            )
                        );
	$resultData['totalGroups'] = count($countGroup);
		return $resultData;
    }
    
    /**
    * Select Group
    * @author Priti
    */
    public function api_selectGroup() 
    {
        $groupId = $this->Encryption->decode($this->jsonDecodedRequestedData->groupId);
        $group_leader_id = NULL;
        $userData = $this->User->userInfoById($this->loggedInUserId);
        $professionId = $userData['BusinessOwners']['profession_id'];
        $condition = array('Group.id' => $groupId, 'Group.is_active' => 1, 'Group.total_member <' => Configure::read('MAX_USER_IN_GROUP'), 'NOT FIND_IN_SET(\''. $professionId .'\',Group.group_professions)');
        $groupInfo = $this->Group->find('first', array('conditions' => $condition));
        if ($this->jsonDecodedRequestedData->listPage == "groupSelect") {
            if (empty($userData['BusinessOwners']['group_id'])) {
                if (!empty($groupInfo)) {
                    $trxData = $this->Transaction->find('first', array('conditions' => array('Transaction.user_id' => $this->loggedInUserId, 'Transaction.group_type' => NULL)));
                    if (!empty($trxData)) {
                        $grpType = $groupInfo['Group']['group_type'];
                        $this->Transaction->updateAll(array('Transaction.group_type' => "'$grpType'"),
                            array('Transaction.user_id' => $this->loggedInUserId,'Transaction.group_type' => NULL) );
                    }
                    if ($groupInfo['Group']['total_member'] == 0) {
                        $group_role = 2;
                        $column = 'Group.group_leader_id';
                        $group_leader_id = $this->loggedInUserId;
                    } else if ($groupInfo['Group']['total_member'] == 1) {
                        $group_role = 3;
                        $column = 'Group.group_coleader_id';
                        $group_leader_id = $this->loggedInUserId;
                    } else {
                        $group_role = 4;
                        $group_leader_id = $groupInfo['Group']['group_coleader_id'];
                        $column = 'Group.group_coleader_id';
                    }
                    $data = array(
                        'BusinessOwner.group_id' => $this->Encryption->decode($groupInfo['Group']['id']),
                        'BusinessOwner.group_role' => $group_role
                    );
                    if ($userData['User']['is_active'] == 1) {
                        $emailLib = new Email();
                        $subject = empty($userData['User']['reactivate']) ? "Welcome to FoxHopr" : "Welcome back to FoxHopr";
                        $template = "activation_email";
                        $format = "both";            
                        $variable = array(
                            'role' => $group_role,
                            'businessowner'=>$userData['BusinessOwners']['fname'].' '.$userData['BusinessOwners']['lname'],
                            'groupname' => Configure::read('GROUP_PREFIX').' '.$this->Encryption->decode($groupInfo['Group']['id']),
                            'meetingdate'=> date('m-d-Y',strtotime($groupInfo['Group']['first_meeting_date'])),
                            'meetingtime'=>date('g:i A',strtotime($groupInfo['Group']['meeting_time'])),
                            'reactivated'=>$userData['User']['reactivate']
                            );
                        $to = $userData['User']['user_email'];
                        $success = $emailLib->sendEmail($to,$subject,$variable,$template,$format); 
                    }
                    if ($groupInfo['Group']['group_professions'] != '') {
                        $groupProfession = $groupInfo['Group']['group_professions'] . ',' . $userData['BusinessOwners']['profession_id'];
                    } else {
                        $groupProfession = $userData['BusinessOwners']['profession_id'];
                    }
                    $this->Group->updateAll(array(
                                                'Group.total_member' => 'total_member + 1',
                                                'Group.group_professions' =>  "'". $groupProfession."'",
                                                $column =>  "'". $group_leader_id."'"
                                                ),
                                            array(
                                                'Group.id' => $this->Encryption->decode($groupInfo['Group']['id']))
                                            );
                    $this->BusinessOwner->updateAll($data, array('BusinessOwner.user_id' => $this->loggedInUserId));
                    // sent message live feed            
                    $groupMembers = $this->BusinessOwner->getMyGroupMemberList($groupId, $this->loggedInUserId);
                    foreach ($groupMembers as $groupMemberId => $groupMemberList) {
                        $this->LiveFeed->create();
                        $liveFeedData['LiveFeed']['to_user_id'] = $groupMemberId;
                        $liveFeedData['LiveFeed']['from_user_id'] = $this->loggedInUserId;
                        $liveFeedData['LiveFeed']['group_id'] = $groupId;
                        $liveFeedData['LiveFeed']['feed_type'] = "newmember";
                        $this->LiveFeed->save($liveFeedData);
                    }
                    $groupResult['group_id'] = $groupId;
                    $this->set(array(
                        'code' => Configure::read('RESPONSE_SUCCESS'),
                        'result' => $groupResult,
                        'message' => 'Thanks for being the part of FoxHopr community',
                        '_serialize' => array('code', 'result', 'message')
                    ));
                } else {
                    $this->set(array(
                        'code' => Configure::read('RESPONSE_ERROR'),
                        'message' => 'This group is no longer available. Please select another group',
                        'groupSelected' => true,
                        '_serialize' => array('code', 'message', 'groupSelected')
                    ));
                }
            } else {
                $groupResult['group_id'] = $groupId;
                $this->set(array(
                    'code' => Configure::read('RESPONSE_SUCCESS'),
                    'result' => $groupResult,
                    'message' => 'You have already joined a group',
                    '_serialize' => array('code', 'result', 'message')
                ));
            }
        } else {
            if (in_array($this->jsonDecodedRequestedData->listPage, array('global', 'local'))) {
                $lastUpdatedDate = $userData['BusinessOwners']['group_update'];
                switch($this->jsonDecodedRequestedData->listPage) {
                    case 'global' :
                        $message = "You have already downgraded your group";
                        $successmessage = "Your Group has been updated successfully";
                        break;
                    case 'local' :
                        $message = "You have already upgraded your group";
						$successmessage = "Your Group has been updated successfully";
						break;
                }
            } elseif ($this->jsonDecodedRequestedData->listPage == "change") {
                $lastUpdatedDate = $userData['BusinessOwners']['group_change'];
                $message = "You have already changed your group";
				$successmessage = "Your Group has been changed successfully";
            }
            if (date('Y-m-d') > date('Y-m-d', strtotime($lastUpdatedDate. ' + 30 days'))) {
                $resultData = $this->updatechangeGroup($userData, $groupInfo, $this->jsonDecodedRequestedData->listPage);
                if ($resultData) {
                    $groupResult['group_id'] = $groupId;
                    $this->set(array(
                        'code' => Configure::read('RESPONSE_SUCCESS'),
                        'result' => $groupResult,
                        'message' => $successmessage,
                        '_serialize' => array('code', 'result', 'message')
                    ));
                } else {
                    $groupResult['group_id'] = $userData['BusinessOwners']['group_id'];
                    $this->set(array(
                        'code' => Configure::read('RESPONSE_ERROR'),
                        'message' => 'This group is no longer available. Please select another group',
                        'groupSelected' => true,
                        'result' => $groupResult,
                        '_serialize' => array('code', 'message', 'groupSelected', 'result')
                    ));
                }
            } else {
                $groupResult['group_id'] = $userData['BusinessOwners']['group_id'];
                $this->set(array(
                    'code' => Configure::read('RESPONSE_SUCCESS'),
                    'message' => $message,
                    'result' => $groupResult,
                    '_serialize' => array('code', 'message', 'groupSelected', 'result')
                ));
            }
        }
    }

    /**
    * upgrade and change group function
    * @param array $userData user Information
    * @param array $groupInfo group information
    * @param string $requestType type of the request
    * @author Priti
    */
    public function updatechangeGroup($userData, $groupInfo, $requestType)
    {
        switch ($requestType) {
        	case 'local':
        		$updateType = 'upgrade';
            	$subject2 = 'FoxHopr : Your Group has been upgraded';
            	$updateColumn = 'BusinessOwner.group_update';
        		break;
        	case 'global':
        		$updateType = 'downgrade';
        		$subject2 = 'FoxHopr : Your Group has been downgraded';
        		$updateColumn = 'BusinessOwner.group_update';
        		break;
        	case 'change':
        		$updateType = 'change';
        		$subject2 = 'FoxHopr : Your Group has been changed';
        		$updateColumn = 'BusinessOwner.group_change';
        		break;
        }
        if (!empty($groupInfo)) {
            $fields = array('BusinessOwner.id', 'BusinessOwner.user_id, BusinessOwner.group_id, BusinessOwner.email, BusinessOwner.fname, BusinessOwner.lname, BusinessOwner.group_role');
            $userGroups = $this->BusinessOwner->find('all',array(
                                'fields' => $fields,
                                'conditions'=>array('BusinessOwner.group_id' => $this->Encryption->decode($userData['Groups']['id']))));
            $emailLib = new Email();
            $format = "both";
            if ($userData['BusinessOwners']['group_role'] == 'participant') {
                $this->BusinessOwner->id = $this->Encryption->decode($userData['BusinessOwners']['id']);
                $this->BusinessOwner->saveField('is_kicked', 0);
                $parts = explode(',', $userData['Groups']['group_professions']); 
                while (($i = array_search($userData['BusinessOwners']['profession_id'], $parts)) !== false) {
                    unset($parts[$i]);
                }
                $updateProfessions = implode(',', $parts);
                $updateMember = $userData['Groups']['total_member'] - 1;
                $this->BusinessOwner->updateAll(array(
                                                    'Group.group_professions' => "'".$updateProfessions."'",
                                                    'Group.total_member' =>"'".$updateMember."'"
                                                    ),
                                                array('Group.id' => $this->Encryption->decode($userData['Groups']['id']))
                                                );
            } else if ($userData['BusinessOwners']['group_role'] == 'co-leader') {
                $this->BusinessOwner->id = $this->Encryption->decode($userData['BusinessOwners']['id']);
                $this->BusinessOwner->saveField('is_kicked', 0);
                foreach ($userGroups as $group) {
                $role = $group['BusinessOwner']['group_role'];
                switch ($role) {
                    case 'participant':                 
                        $subject = "FoxHopr: Chance to be Group Co-Leader";
                        $template = "upgrade_membership_participant";
                        $business_owner_name = $group['BusinessOwner']['fname']." ".$group['BusinessOwner']['lname'];
                        $variable = array('businessowner' => $business_owner_name, 'case' => 'participant');
                        $to = $group['BusinessOwner']['email'];
                        $success = $emailLib->sendEmail($to, $subject, $variable, $template, $format);
                        break;
                    case 'co-leader':
                        $this->Group->updateAll(array('Group.group_coleader_id' => NULL),array( 'Group.id' => $group['BusinessOwner']['group_id']));
                        $parts = explode(',', $userData['Groups']['group_professions']); 
                        while(($i = array_search($userData['BusinessOwners']['profession_id'], $parts)) !== false) {
                            unset($parts[$i]);
                        }
                        $updateProfessions = implode(',', $parts);
                        $updateMember = $userData['Groups']['total_member'] - 1;
                        $this->BusinessOwner->updateAll(array(
                                                            'Group.group_professions' => "'".$updateProfessions."'",
                                                            'Group.total_member' => "'".$updateMember."'"),
                                                        array( 'Group.id' => $group['BusinessOwner']['group_id'])
                                                        );
                        break;
                    default:
                        break;
                    }
                }
            } else {
                foreach ($userGroups as $group) {
                $role = $group['BusinessOwner']['group_role'];
                switch ($role) {
                    case 'participant':
                        $subject = "FoxHopr: Chance to be Group Co-Leader";
                        $template = "upgrade_membership_participant";
                        $business_owner_name = $group['BusinessOwner']['fname']." ".$group['BusinessOwner']['lname'];
                        $variable = array('businessowner' => $business_owner_name, 'case' => 'participant');
                        $to = $group['BusinessOwner']['email'];
                        $success = $emailLib->sendEmail($to, $subject, $variable, $template, $format);
                        $this->BusinessOwner->id = $this->Encryption->decode($group['BusinessOwner']['id']);
                        $this->BusinessOwner->saveField('is_kicked', 0);
                        break;
                    case 'co-leader':
                        $this->BusinessOwner->updateAll(array('BusinessOwner.group_role' => '"leader"'),array('BusinessOwner.user_id' => $group['BusinessOwner']['user_id']));
                        $this->Group->updateAll(array(
                                                    'Group.group_leader_id' => $group['BusinessOwner']['user_id'],
                                                    'Group.group_coleader_id' => NULL
                                                    ),
                                                array( 'Group.id' => $group['BusinessOwner']['group_id'])
                                                );
                        $subject = "FoxHopr: Promoted as Group Leader";
                        $template = "upgrade_membership_coleader";
                        $business_owner_name = $group['BusinessOwner']['fname']." ".$group['BusinessOwner']['lname'];
                        $variable = array('businessowner' => $business_owner_name, 'case' => 'co-leader');
                        $to = $group['BusinessOwner']['email'];
                        $success = $emailLib->sendEmail($to, $subject, $variable, $template, $format);
                        $this->BusinessOwner->id = $this->Encryption->decode($group['BusinessOwner']['id']);
                        $this->BusinessOwner->saveField('is_kicked', 0);
                        break;
                    case 'leader':
                        $parts = explode(',', $userData['Groups']['group_professions']); 
                        while(($i = array_search($userData['BusinessOwners']['profession_id'], $parts)) !== false) {
                            unset($parts[$i]);
                        }
                        $updateProfessions = implode(',', $parts);
                        $updateMember = $userData['Groups']['total_member'] - 1;
                        $this->BusinessOwner->updateAll(array('Group.group_professions' => "'".$updateProfessions."'",'Group.total_member' =>"'".$updateMember."'"),array( 'Group.id' => $group['BusinessOwner']['group_id']));
                        break;
                    default:
                        break;
                    }
                }
            }
            //Store previous group members information
            $userId = $userData['User']['id'];
            $gdata = $this->BusinessOwner->getMyGroupMemberList($this->Encryption->decode($userData['Groups']['id']),$this->Encryption->decode($userId));
            $prevMember = NULL;
            $prevRecord['PrevGroupRecord'] = array();
            foreach($gdata as $key => $val) {
                $data['user_id'] = $this->Encryption->decode($userId);
                $data['group_id'] = $this->Encryption->decode($userData['Groups']['id']);
                $data['members_id'] = $key;
                array_push($prevRecord['PrevGroupRecord'],$data);
            }
            $this->PrevGroupRecord->saveAll($prevRecord['PrevGroupRecord']);
    
            //Assign New Group
            if ($groupInfo['Group']['total_member'] == 0) {
                $group_role = 2;
                $column = 'Group.group_leader_id';
                $group_leader_id = $this->Encryption->decode($userId);
            } else if($groupInfo['Group']['total_member'] == 1) {
                $group_role = 3;
                $column = 'Group.group_coleader_id';
                $group_leader_id = $this->Encryption->decode($userId);
            } else {
                $group_role = 4;
                $group_leader_id = $groupInfo['Group']['group_coleader_id'];
                $column = 'Group.group_coleader_id';
            }
            $data = array(
                'BusinessOwner.group_id' => $this->Encryption->decode($groupInfo['Group']['id']),
                'BusinessOwner.group_role' => $group_role,
                $updateColumn => '"'.date("Y-m-d").'"',
                'BusinessOwner.is_kicked' => 0
            );
            $template = "upgrade_downgrade_group";
            $format = "both";            
            $variable = array(
                'updatetype' => $updateType,
                'role' => $group_role,
                'businessowner' => $userData['BusinessOwners']['fname'].' '.$userData['BusinessOwners']['lname'],
                'groupname' => Configure::read('GROUP_PREFIX').' '.$this->Encryption->decode($groupInfo['Group']['id']),
                'meetingdate'=> date('m-d-Y', strtotime($groupInfo['Group']['first_meeting_date'])),
                'meetingtime'=>date('g:i A', strtotime($groupInfo['Group']['meeting_time'])),
                );
            $to = $userData['User']['user_email'];
            $success = $emailLib->sendEmail($to, $subject2, $variable, $template, $format);
            if ($groupInfo['Group']['group_professions'] != '') {
                $groupProfession = $groupInfo['Group']['group_professions'] . ',' . $userData['BusinessOwners']['profession_id'];
            } else {
                $groupProfession = $userData['BusinessOwners']['profession_id'];
            }
            $this->Group->updateAll(
               array('Group.total_member' => 'total_member + 1','Group.group_professions' =>  "'". $groupProfession."'",$column =>  "'". $group_leader_id."'"),
               array( 'Group.id' => $this->Encryption->decode($groupInfo['Group']['id'])));
            $this->BusinessOwner->updateAll($data,array( 'BusinessOwner.user_id' => $this->Encryption->decode($userId)));
            // sent message live feed            
            $groupMembers = $this->BusinessOwner->getMyGroupMemberList($this->Encryption->decode($groupInfo['Group']['id']), $this->Encryption->decode($userId));
            foreach ($groupMembers as $groupMemberId => $groupMemberList){
                $this->LiveFeed->create();
                $liveFeedData['LiveFeed']['to_user_id']     = $groupMemberId;
                $liveFeedData['LiveFeed']['from_user_id']   = $this->Encryption->decode($userId);
                $liveFeedData['LiveFeed']['group_id'] = $this->Encryption->decode($groupInfo['Group']['id']);
                $liveFeedData['LiveFeed']['feed_type']      = "newmember";
                $this->LiveFeed->save($liveFeedData);
            }
            //$this->GroupGoals->resetUserGoals($this->Encryption->decode($userId));
            return 1;
        } else {
            return 0;
        }
    }

	/**
     *function to check adobe connect Meeting time slot availability according to given date 
     * @param string $date selected date 
     *@author Gaurav
     */
    private function __checkAdobeConnectMeetingSlots($date)
    {
        $range1  = strtotime(date('Y-m-d'));
		$range2  = strtotime(date('Y-m-d'). '+30days');

		$timeT1  = strtotime(date($date));
		$timeT2  = strtotime(date($date));
		$timeArray = array();
		$timeArray[] = date('Y-m-d', $timeT1);
		while($timeT1 > strtotime(date('Y-m-d'). '-30days')) {
			$timeT1 = strtotime((date('Y-m-d', $timeT1).' -14days'));
			if($timeT1 >= $range1){
				$timeArray[] = date('Y-m-d',$timeT1);
			}
		}
		while(strtotime(date('Y-m-d'). '+30days') > $timeT2) {
			$timeT2 = strtotime((date('Y-m-d', $timeT2).' +14days'));
			if($timeT2 <= $range2){
				$timeArray[] = date('Y-m-d',$timeT2);
			}
		}
		$data = $this->AdobeConnectMeeting->find('all');		
		$availableSlots = array();
		foreach($data as $d) {
			$AdobeId = $d['AdobeConnectMeeting']['nmh'] % 4;
			switch ($AdobeId) {
				case 1:
				$slots = explode(',',Configure::read('SLOT_POSITION_FIRST'));
				foreach($slots as $position) {
					$usedSlots = $this->AvailableSlots->find('all',array('conditions'=>array(
																			'AvailableSlots.nmh'=>$d['AdobeConnectMeeting']['nmh'],
																			'AvailableSlots.slot_id'=>$position,
																			'AvailableSlots.date'=>$timeArray
																	)
																)
															);
					if(empty($usedSlots)){
						$availableSlots[][$d['AdobeConnectMeeting']['nmh']] = str_replace('t', '', $position);
					}
				}
				break;
				case 2:
				$slots = explode(',',Configure::read('SLOT_POSITION_SECOND'));
				foreach($slots as $position) {
					$usedSlots = $this->AvailableSlots->find('all',array('conditions'=>array(
																			'AvailableSlots.nmh'=>$d['AdobeConnectMeeting']['nmh'],
																			'AvailableSlots.slot_id'=>$position,
																			'AvailableSlots.date'=>$timeArray
																	)
																)
															);
					if(empty($usedSlots)){
						$availableSlots[][$d['AdobeConnectMeeting']['nmh']] = str_replace('t', '', $position);
					}
				}
				break;
				case 3:
				$slots = explode(',',Configure::read('SLOT_POSITION_THIRD'));
				foreach($slots as $position) {
					$usedSlots = $this->AvailableSlots->find('all',array('conditions'=>array(
																			'AvailableSlots.nmh'=>$d['AdobeConnectMeeting']['nmh'],
																			'AvailableSlots.slot_id'=>$position,
																			'AvailableSlots.date'=>$timeArray
																	)
																)
															);
					if(empty($usedSlots)){
						$availableSlots[][$d['AdobeConnectMeeting']['nmh']] = str_replace('t', '', $position);
					}
					
				}
				break;
				case 0:
				$slots = explode(',',Configure::read('SLOT_POSITION_FOURTH'));
				foreach($slots as $position) {
					$usedSlots = $this->AvailableSlots->find('all',array('conditions'=>array(
																			'AvailableSlots.nmh'=>$d['AdobeConnectMeeting']['nmh'],
																			'AvailableSlots.slot_id'=>$position,
																			'AvailableSlots.date'=>$timeArray
																	)
																)
															);
					if(empty($usedSlots)){
						$availableSlots[][$d['AdobeConnectMeeting']['nmh']] = str_replace('t', '', $position);
					}		    				
				}
				break;
			}
		}
		$slotArray = array();
     	$finalArray = array();
		foreach($availableSlots as $xVal) {
			foreach($xVal as $key => $value){					
				if(!in_array($value,$finalArray)){
					array_push($finalArray,$value);
					$timing = $this->Adobeconnect->getSlotTimes('t'.$value);
					$slotArray[$value] = $key.';'.$timing;
				}			     		
			}					
		}
		ksort($slotArray);
		$positionArray = array();
		foreach($slotArray as $x => $x_value) {
			$explodeData = explode(';',$x_value);
			$availSlot = $this->Encryption->encode($explodeData[0].':t'.$x);
	     	$positionArray[][$availSlot] = $explodeData[1];
		}
		if(!empty($positionArray)){
			return $positionArray;
		}
    }

    public function createNewGroup()
    {
    	if ($this->request->is('post')) {
    		$flashBox = ($this->Session->check('UID') == true) ? 'Front/' : '';
        	$breezSessionData = $this->Adobeconnect->adobeConnectLogin();

        	// Check login to adobe connect successfully
        	if($breezSessionData != 'invalid') {
				
        		$countryData = $this->Country->find('first',array('conditions'=>array('Country.country_iso_code_2' => $this->request->data['Group']['country_id'])));
	            $this->request->data['Group']['first_meeting_date'] = date("Y-m-d", strtotime(str_replace('-', '/', $this->request->data['Group']['first_meeting_date'])));
	            $this->request->data['Group']['second_meeting_date'] = date("Y-m-d", strtotime(str_replace('-', '/', $this->request->data['Group']['second_meeting_date'])));

	            $latLong = $this->Common->getLatLong($countryData['Country']['country_name'].','.$this->request->data['Group']['zipcode']);
	            $this->request->data['Group']['lat'] = $latLong['lat'];
	            $this->request->data['Group']['long'] = $latLong['lng'];
	            if(!empty($this->request->data['BusinessOwner']['state_id'])) {
	                $this->request->data['Group']['state_id'] = $this->request->data['BusinessOwner']['state_id'];
	            } else {
	                $this->request->data['Group']['state_id'] = $this->request->data['groups']['state_id'];
	            }
	            $this->request->data['Group']['is_active'] = 1;
	            $userId = ($this->Session->check('UID') == true) ? $this->Encryption->decode($this->Session->read('UID')) : $this->Encryption->decode($this->Session->read('Auth.User.id'));
	            $this->request->data['Group']['group_created_by'] = $userId;
	            $this->request->data['Group']['group_modified_by'] = $userId;

	            if($this->Session->check('UID') == true) {
	            	$businessOwner = $this->BusinessOwner->findByUserId($userId,'BusinessOwner.id,BusinessOwner.fname,BusinessOwner.lname,BusinessOwner.email,BusinessOwner.profession_id');
					$this->request->data['Group']['group_leader_id'] = $userId;
    				$this->request->data['Group']['total_member'] = 1;
                	$this->request->data['Group']['group_professions'] = $businessOwner['BusinessOwner']['profession_id'];
	            }

	            // check Slot availability after submit
	            $date = date('Y-m-d',strtotime($this->request->data['Group']['first_meeting_date']));
	            $slotId = $this->Encryption->decode($this->request->data['Group']['slot']);
	            $meetingSlotArray = explode(':',$slotId);
	            $availability = $this->__checkAdobeConnectMeetingSlots($date);
	            $emptySlots = array();
	            foreach($availability as $availArray) {
	            	foreach($availArray as $key => $val){
	            		$slotTime = explode(':',$this->Encryption->decode($key));
	            		$emptySlots[$slotTime[1]] = $val;
	            	}
	            }	            
	         	if(array_key_exists($meetingSlotArray[1], $emptySlots)) {
	         		$meetingTime = $this->Adobeconnect->getFirstMeetingTime($meetingSlotArray[1]);
	         		$this->request->data['Group']['meeting_time'] = date('h:i:s',strtotime($meetingTime));
	         		$datasource = $this->Group->getDataSource();
	         		$datasource->begin();
	         		$this->Group->set($this->request->data);
	         		if ($this->Group->save($this->request->data)) {
	         			$groupId = $this->Group->id;
	         			$meetingTimingArr = $this->Adobeconnect->getSlotTimes($meetingSlotArray[1]);
	         			$meetTime = explode('-', $meetingTimingArr);
	         			$timeFirst = explode(' ',$meetTime[0]);
	         			$timeSecond = explode(' ',$meetTime[1]);

	         			//Create meeting in adobe connect with group name
	                    $meetingName = 'Group-'.$groupId;
	                    $dateBegin = $date.'T'.$timeFirst[0];
	                    $dataEnd = $date.'T'.$timeSecond[1];	                    
			            $createMeetingSuccess = $this->Adobeconnect->createMeeting($meetingName,$dateBegin,$dataEnd,$breezSessionData);
			            //pr($createMeetingSuccess);die;
	         			if($createMeetingSuccess['status']['@attributes']['code'] == 'ok'){
	         				//Save meeting data to DB
				            $slotDataArray['nmh'] = $meetingSlotArray[0];
		         			$slotDataArray['slot_id'] = $meetingSlotArray[1];
		         			$slotDataArray['group_id'] = $groupId;
		         			$slotDataArray['adobe_group_id'] = $createMeetingSuccess['sco']['@attributes']['sco-id'];
		         			$slotDataArray['url_path'] = $createMeetingSuccess['sco']['url-path'];
		         			$slotDataArray['date'] = $date;
		         			$this->AvailableSlots->save($slotDataArray);
	         				$datasource->commit();
	         				if($this->Session->check('UID') == true) {
	         					//For Front End Group Creation
			         			$trxData=$this->Transaction->find('first',array('conditions'=>array('Transaction.user_id'=>$userId,'Transaction.group_type'=>NULL)));
			                    if(!empty($trxData)) {
			                        $grpType = $this->request->data['Group']['group_type'];
			                        $this->Transaction->updateAll(array('Transaction.group_type' => "'$grpType'"),
			                            array('Transaction.user_id' => $userId,'Transaction.group_type'=>NULL));
			                    }
			                    $this->request->data['Group']['group_name'] = Configure::read('GROUP_PREFIX').' '.$this->Group->id;
			                    $role = 'leader';
			                    $this->BusinessOwner->updateAll(
			                                    array('BusinessOwner.group_id' => $this->Group->id,'BusinessOwner.group_role'=> "'$role'"),
			                                    array('BusinessOwner.id' => $this->Encryption->decode($businessOwner['BusinessOwner']['id']))
			                                );
			    				// send email to user
			    				$emailLib = new Email();
			    				$to = $businessOwner['BusinessOwner']['email'];
			    				$subject = 'Group created successfully.';
			    				$format = "both";
			    				$template = 'group_create_byuser';
			    				$user_name = $businessOwner['BusinessOwner']['fname']." ".$businessOwner['BusinessOwner']['lname'];
			    				$variable = array('name' => $user_name,'groupname'=>$this->request->data['Group']['group_name']);
			    				$success = $emailLib->sendEmail($to,$subject,$variable,$template,$format);
			    				 
			    				// send email to admin
			    				$to = "bhanu.bhati@a3logics.in";
			    				$subject = 'New group created by user';
			    				$format = "html";
			    				$template = 'group_create_notify';
			    				$user_name = $businessOwner['BusinessOwner']['fname']." ".$businessOwner['BusinessOwner']['lname'];
			    				$variable = array('name' => $user_name,'groupname'=>$this->request->data['Group']['group_name']);
			    				$success1 = $emailLib->sendEmail($to,$subject,$variable,$template,$format);
			    				$user = $this->Session->read('Auth.Front.id');
			    				if(isset($user)){
			    					$this->Session->delete('UID');
			    					$condition = array('Group.id' => $groupId);
	        						$groupInfo = $this->Group->find('first', array('conditions' => $condition));
	        						$this->Session->write('Auth.Front.BusinessOwners.group_id', $groupId);
	                    			$this->Session->write('Auth.Front.Groups', $groupInfo['Group']);
	                    			$this->Session->setFlash(__('Thank you for creating the group. Check your email for more details.'),'Front/flash_good');		                        
			    					$this->redirect(array('controller' => 'pages', 'action' => 'home'));
			    				}else{
			    					$this->Session->setFlash(__('Thank you for creating the group. Check your email for more details.'), 'Front/flash_good');
			    					$this->redirect(array('controller' => 'users', 'action' => 'login'));
			    				}
	         				} else {
	         					$this->Session->setFlash(__('Group has been added successfully'), 'flash_good');
	         					$this->redirect(array('action' => 'index', 'admin' => true));
	         				}
	         			} else {
	         				$datasource->rollback();
         					$this->Session->setFlash(__('Some problem for creating the group! Try again.'), !empty($flashBox)?$flashBox.'flash_bad':"flash_bad");
			         		$redirect = ($this->Session->check('UID') == true) ? array('controller' => 'groups', 'action' => 'createGroup') : array('action' => 'index', 'admin' => true);
			     			$this->redirect($redirect);
	         			}
	         		} else {
	         			$validationErrors=$this->compileErrors('Group');
	         			if($validationErrors != NULL) {
	         				$this->Session->setFlash($validationErrors, !empty($flashBox)?$flashBox.'flash_bad':"flash_bad");
	         			}
	         			unset($this->request->data['Group']['first_meeting_date']);
	         		}
	         	} else {
	         		$this->Session->setFlash(__('Slot Not Available to create meeting on given date.'), !empty($flashBox)?$flashBox.'flash_bad':"flash_bad");
	         		$redirect = ($this->Session->check('UID') == true) ? array('controller' => 'groups', 'action' => 'createGroup') : array('action' => 'index', 'admin' => true);
	     			$this->redirect($redirect);
	         	}
        	} else {
        		$this->Session->setFlash(__('Adobe Connect Login Failed'), !empty($flashBox)?$flashBox.'flash_bad':"flash_bad");
        		$redirect = ($this->Session->check('UID') == true) ? array('controller' => 'groups', 'action' => 'createGroup') : array('action' => 'addGroup', 'admin' => true);
     			$this->redirect($redirect);
        	}
        }
    }
}
