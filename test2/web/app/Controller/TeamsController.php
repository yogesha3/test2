<?php 
App::uses('Email', 'Lib');
class TeamsController extends AppController 
{
    public $components=array('Common','Timezone','Paginator','Cookie','Encryption');
    public $uses = array('BusinessOwner','Profession','User','Group','Country','State','Review','PrevGroupRecord','SendReferral','Goal','ReferralStat');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('titleForLayout', 'FoxHopr: Team');
        //$this->Auth->allow(array('teamList'));
        $userId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
        $condition1 = array('PrevGroupRecord.user_id' => $userId, 'User.deactivated_by_user' => 0, 'User.is_active' => 1);
        $groupBy = array('PrevGroupRecord.members_id');
        $previousRecordCount = $this->PrevGroupRecord->find('count',array('conditions'=>$condition1,'group'=>$groupBy));
        $this->set('previousRecordCount',$previousRecordCount);
    }
    /**
     * function to show list of team members
     * @author Priti Kabra
     */
    public function teamList()
    {
        $this->layout = 'front';
        $titleForLayout = "Team Members";
        $groupId = $this->Session->read('Auth.Front.BusinessOwners.group_id');
        $userId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
        $grpLeaderId = $this->User->find('first', array('conditions' => array('User.id' => $userId), 'fields' => array('Groups.group_leader_id')));
        $kickOffPermission = ($grpLeaderId['Groups']['group_leader_id'] == $userId) ? 1 : 0 ;
        if (!empty($kickOffPermission)) {
            $videoStatus = $this->BusinessOwner->find('first', array('conditions' => array('BusinessOwner.is_unlocked' => 1, 'BusinessOwner.user_id' => $userId), 'fields' => array('BusinessOwner.is_unlocked')));
            $checkVideoStatus = (!empty($videoStatus)) ? 1 : 0 ;
            $this->set(compact('checkVideoStatus'));
        }
        if (!$this->request->is('ajax')) {
            $this->Session->delete('direction');
            $this->Session->delete('sort');
        }
        //pagination starts here
        $perpage = $this->Functions->get_param('perpage', Configure::read('PER_PAGE'), true);
        $page = $this->Functions->get_param('page', Configure::read('PAGE_NO'), false);
       	$counter = (($page - 1) * $perpage) + 1;

        $this->set('counter', $counter);

        $search = $this->Functions->get_param('search');
        $this->Functions->set_param('direction');        
        $this->Functions->set_param('sort');
        if ($this->Session->read('sort') != '') {
            $order = array($this->Session->read('sort') => $this->Session->read('direction'));
        } else {
            $order = array('created' => 'desc');
        }
        $condition1 = array('BusinessOwner.group_id' => $groupId, 'BusinessOwner.user_id !=' => $userId ,'User.is_active' => 1);
         if ($search != '') {
              $condition2['OR'] = array(                 
                  "BusinessOwner.company LIKE" => "%" . trim($search) . "%",
                  "BusinessOwner.fname LIKE" => "%" . trim($search) . "%",
                  "BusinessOwner.lname LIKE" => "%" . trim($search) . "%",
                  "CONCAT(BusinessOwner.fname ,' ',BusinessOwner.lname) LIKE" => "%" . trim($search) . "%",
                  "Country.country_name LIKE" => "%" . trim($search) . "%",
                  "State.state_subdivision_name LIKE" => "%" . trim($search) . "%",
                  "Profession.profession_name LIKE" => "%" . trim($search) . "%",
              );
        } else {
          $condition2 = array();
        }
        $condition = array_merge($condition1, $condition2);
        $this->Paginator->settings = array(
                    'conditions' => $condition,
                    'order' => $order,
                    'limit' =>$perpage,
                    'recursive'=>1
                );
        $groupData = $this->Paginator->paginate('BusinessOwner');
        $this->set('groupData',$groupData);
        $this->set('search', $search);
        if ($this->request->is('ajax')) {
            $this->layout = false;
            $this->set('perpage', $perpage);
            $this->set('search', $search);
            $this->render('team_list_ajax');
        }
    }

     /**
     * function to redirect the user to respected page on more action
     * @author Jitendra Sharma
     * @param string $action redirect to page
     * @param int $id user id
     */
    public function action($action = NULL , $id = NULL)
    {
        if(isset($this->request->data['bulkaction'])) {
            $action = $this->request->data['bulkaction'];
            $this->Session->write('teamMembers',$this->request->data['teamMembers']);            
            if($action == 'referral') {            
                $this->redirect(array('controller' => 'referrals', 'action' => 'sendReferrals'));
            } else if($action == 'message') {
                $this->redirect(array('controller' => 'messages', 'action' => 'composeMessage'));
            }             
        } else if($action != NULL && $id != NULL) {
            $action = $action;
            $this->Session->write('teamMembers',$this->Encryption->decode($id));            
            if($action == 'referral') {            
                $this->redirect(array('controller' => 'referrals', 'action' => 'sendReferrals'));
            } else if($action == 'message') {
                $this->redirect(array('controller' => 'messages', 'action' => 'composeMessage'));
            }    
        } else {
            $this->redirect(array('controller' => 'teams', 'action' => 'teamList'));
        }
    }
    
    /**
     * Function used for member Details
     * @param int $contactId contact id
     * @param string $backurl back url
     * @author Rohan Julka
     */
    public function memberDetail($contactId = null, $backurl = null)
    {
        $this->set('referer', $backurl);
        
        if ($contactId != null) {
            $contactId = $this->Encryption->decode($contactId);
            $query=$this->BusinessOwner->findByUserId($contactId);
            if (empty($query)) {
                $this->Session->setFlash(__('This team member does not exist.'), 'Front/flash_bad');
                $this->redirect(array('controller' => 'teams', 'action' => 'teamList'));
            }
            $memberData = $this->BusinessOwner->find('first', array('conditions' => array('BusinessOwner.user_id' => $contactId)));
            $totalReview = $this->Review->getTotalReviewByUserId($memberData['BusinessOwner']['user_id']);
            $totalReviewAverage = $totalReview*Configure::read('RATING_TYPE_NO');
            $totalAvgRatingArr = $this->Review->getAverage($memberData['BusinessOwner']['user_id']);
            if (!empty($totalAvgRatingArr)) {
                $totalAvgRating = round($totalAvgRatingArr/$totalReviewAverage);            
            } else {
                $totalAvgRating = 0;
            }
            $this->set(compact('memberData','totalAvgRating','totalReview'));
            $this->set('memberId', $contactId);
        } else {
            $this->Session->setFlash(__('This team member does not exist.'), 'Front/flash_bad');
            $this->redirect(array('controller' => 'teams', 'action' => 'teamList'));
        }
    }
    /**
     * function to show list of Previous team members
     * @author Rohan julka
     */
    public function previousTeamList()
    {
        $this->layout = 'front';
        $titleForLayout = "Previous Team Members";
        $userId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
        if (!$this->request->is('ajax')) {
            $this->Session->delete('direction');
            $this->Session->delete('sort');
        }
        //pagination starts here
        $perpage = $this->Functions->get_param('perpage', Configure::read('PER_PAGE'), true);
        $page = $this->Functions->get_param('page', Configure::read('PAGE_NO'), false);
        $counter = (($page - 1) * $perpage) + 1;
    
        $this->set('counter', $counter);
    
        $search = $this->Functions->get_param('search');
        $this->Functions->set_param('direction');
        $this->Functions->set_param('sort');
        if ($this->Session->read('sort') != '') {
            $order = array($this->Session->read('sort') => $this->Session->read('direction'));
        } else {
            $order = array('created' => 'desc');
        }
        $condition1 = array('PrevGroupRecord.user_id' => $userId, 'User.deactivated_by_user' => 0, 'User.is_active' => 1);
        
        $groupBy = array('PrevGroupRecord.members_id');
        if ($search != '') {
            $condition2['OR'] = array(
                "BusinessOwner.company LIKE" => "%" . trim($search) . "%",
                "BusinessOwner.fname LIKE" => "%" . trim($search) . "%",
                "BusinessOwner.lname LIKE" => "%" . trim($search) . "%",
                "CONCAT(BusinessOwner.fname ,' ',BusinessOwner.lname) LIKE" => "%" . trim($search) . "%",
                "Country.country_name LIKE" => "%" . trim($search) . "%",
                "State.state_subdivision_name LIKE" => "%" . trim($search) . "%",
                "Profession.profession_name LIKE" => "%" . trim($search) . "%",
            );
        } else {
            $condition2 = array();
        }
        $condition = array_merge($condition1, $condition2);
        $this->Paginator->settings = array(
                'conditions' => $condition,
                'order' => $order,
                'limit' =>$perpage,
                'recursive'=>1,
                'group'=>$groupBy
        );
        $groupData = $this->Paginator->paginate('PrevGroupRecord');
        $this->set('groupData',$groupData);
        $this->set('search', $search);
        if ($this->request->is('ajax')) {
            $this->layout = false;
            $this->set('perpage', $perpage);
            $this->set('search', $search);
            $this->render('previous_team_list_ajax');
        }
    }

    /**
     * Function to kick off the team member by the Group leader
     * @author Priti Kabra
     * @access public
     */
    public function kickOff()
    {
        //$this->Session->write('sessionDelMsg', 'No record found');
        $action = $this->request->data['mass_action'];
        //pr($this->request->data); exit;
        if (is_array($this->request->data['teamMembers'])) {
            if ($action=="massdelete") {
                $record = $this->request->data['teamMembers'];
                foreach ($record as $kickOffUser) {
                    $userData = $this->BusinessOwner->find('first', array('conditions' => array('BusinessOwner.user_id' => $kickOffUser)));
                    $this->BusinessOwner->id = $this->Encryption->decode($userData['BusinessOwner']['id']);
                    if ($this->BusinessOwner->saveField('is_kicked', 1)) {
                        $name = $userData['BusinessOwner']['fname']." ".$userData['BusinessOwner']['lname'];
                        $emailLib = new Email();
                        $to = $userData['BusinessOwner']['email'];
                        $subject = 'FoxHopr: Kick Off Request';
                        $template = 'kick_off_email';
                        $format = 'both';
                        $variable = array('name' => $name);
                        $emailLib->sendEmail($to,$subject,$variable,$template,$format);
                    }
                }
                //send email to the leader
                $leaderName = $this->Session->read('Auth.Front.BusinessOwners.fname')." ".$this->Session->read('Auth.Front.BusinessOwners.lname');
                $emailToLeader = new Email();
                $toLeader = $this->Session->read('Auth.Front.BusinessOwners.email');
                $subjectToLeader = 'FoxHopr: Kick Off Request';
                $templateToLeader = 'leader_kick_off_request_email';
                $formatToLeader = 'both';
                $variableToLeader = array('name' => $leaderName);
                $emailToLeader->sendEmail($toLeader, $subjectToLeader, $variableToLeader, $templateToLeader, $formatToLeader);
                //send email to the admin
                $emailToAdmin = new Email();
                $toAdmin = AdminEmail;
                $subjectToAdmin = 'FoxHopr: Kick Off Request';
                $templateToAdmin = 'admin_kick_off_request_email';
                $formatToAdmin = 'both';
                $variableToAdmin = array('name' => AdminName);
                $emailToAdmin->sendEmail($toAdmin, $subjectToAdmin, $variableToAdmin, $templateToAdmin, $formatToAdmin);
            }
        }
        $this->redirect(array('controller' => 'teams', 'action' => 'teamList'));
    }

    /*
     *Web service to get the Current and Previous Team Members List
     *@author Priti Kabra
     */
    public function api_getTeamList()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        if (!empty($this->jsonDecodedRequestedData->search_filter)) {
            $this->jsonDecodedRequestedData->search_filter = (strpos($this->jsonDecodedRequestedData->search_filter, '%') !== false) ? str_replace('%', '\%', $this->jsonDecodedRequestedData->search_filter) : $this->jsonDecodedRequestedData->search_filter;
            $this->jsonDecodedRequestedData->search_filter = (strpos($this->jsonDecodedRequestedData->search_filter, '_') !== false) ? str_replace('_', '\_', $this->jsonDecodedRequestedData->search_filter) : $this->jsonDecodedRequestedData->search_filter;
        }
        if ($error == 0) {
            $teamFilter = array();
            $sortData = '';
            $userId = $this->loggedInUserId;
            if (!empty($this->jsonDecodedRequestedData->search_filter)) {
                $teamFilter['OR'] = array(
                  "BusinessOwner.company LIKE" => "%" . trim($this->jsonDecodedRequestedData->search_filter) . "%",
                  "BusinessOwner.fname LIKE" => "%" . trim($this->jsonDecodedRequestedData->search_filter) . "%",
                  "BusinessOwner.lname LIKE" => "%" . trim($this->jsonDecodedRequestedData->search_filter) . "%",
                  "CONCAT(BusinessOwner.fname,' ',BusinessOwner.lname) LIKE" => "%" . trim($this->jsonDecodedRequestedData->search_filter) . "%",
                  "Country.country_name LIKE" => "%" . trim($this->jsonDecodedRequestedData->search_filter) . "%",
                  "State.state_subdivision_name LIKE" => "%" . trim($this->jsonDecodedRequestedData->search_filter) . "%",
                  "Profession.profession_name LIKE" => "%" . trim($this->jsonDecodedRequestedData->search_filter) . "%",
              );
            }
            /** Fields array to be fetched from the database */
            $fields = array("BusinessOwner.id", "BusinessOwner.user_id", "BusinessOwner.fname", "BusinessOwner.lname", "BusinessOwner.company", "BusinessOwner.email", "BusinessOwner.created", "Profession.profession_name", "State.state_subdivision_name", "Country.country_name", "User.id");
            /** Sort data according to the sort filter */
            if (!empty($this->jsonDecodedRequestedData->sort_data) && !empty($this->jsonDecodedRequestedData->sort_direction)) {
                $sortData = "BusinessOwner.".$this->jsonDecodedRequestedData->sort_data." ".$this->jsonDecodedRequestedData->sort_direction;
            } else {
                $sortData = "BusinessOwner.created DESC";
            }
            if ($this->jsonDecodedRequestedData->listPage == 'current') {
                $conditionsRequired = array('BusinessOwner.group_id' => $this->loggedInUserGroupId, 'BusinessOwner.user_id !=' => $this->loggedInUserId, 'User.deactivated_by_user' => 0, 'User.is_active' => 1);
                $model = "BusinessOwner";
                $groupBy = array();
                $message = "Current Members List";
            } elseif ($this->jsonDecodedRequestedData->listPage == 'previous') {
                $conditionsRequired = array('PrevGroupRecord.user_id' => $this->loggedInUserId, 'User.deactivated_by_user' => 0, 'User.is_active' => 1); //header will return grp id
                $model = "PrevGroupRecord";
                $groupBy = array('PrevGroupRecord.members_id');
                $message = "Previous Members List";
            } else {
                $error = 1;
                $errMsg = "Please provide list type'.";
            }
            if (empty($error)) {
                $conditions = array_merge($conditionsRequired, $teamFilter);
                $teamList = $this->$model->find('all',
                                                array('conditions' => $conditions,
                                                      'fields' => $fields,
                                                      'order' => $sortData,
                                                      'recursive' => 1,
                                                      'limit' => $this->jsonDecodedRequestedData->record_per_page,
                                                      'page' => $this->jsonDecodedRequestedData->page_no,
                                                      'group' => $groupBy
                                                )
                                            );
                $totalMembers = $this->$model->find('count',
                                                      array('conditions' => $conditions, 'group'=>$groupBy)
                                                  );
                foreach ($teamList as $key => $value) {
                    $list[] = $value['BusinessOwner'];
                    $list[$key]['member_id'] = $value['User']['id'];
                    $list[$key]['list_user_id'] = ($this->jsonDecodedRequestedData->listPage == 'current') ? 'cur_'.$value['BusinessOwner']['user_id'] : 'prev_'.$value['BusinessOwner']['user_id'];
                    $list[$key]['country_name'] = $value['Country']['country_name'];
                    $list[$key]['state_name'] = $value['State']['state_subdivision_name'];
                    $list[$key]['profession_name'] = $value['Profession']['profession_name'];
                }
                if (!empty($teamList)) {
                    $this->set(array(
                        'code' => Configure::read('RESPONSE_SUCCESS'),
                        'result' => $list,
                        'message' => $message,
                        'page_no' => $this->jsonDecodedRequestedData->page_no,
                        'totalMembers' => $totalMembers,
                        '_serialize' => array('code', 'result', 'message', 'page_no', 'totalMembers')
                    ));
                } else {
                    $this->errorMessageApi('');
                }
            } else {
                $this->errorMessageApi($errMsg);
            }
        } else {
            $this->errorMessageApi($errMsg);
        }
    }

    /*
     *Web service to get the team member detail
     *@author Priti Kabra
     */
    public function api_memberDetail()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        $success = 0;
        if (!empty($this->jsonDecodedRequestedData->memberId) && empty($error)) {
            $memberId = $this->Encryption->decode($this->jsonDecodedRequestedData->memberId);
            $memberData = $this->BusinessOwner->find('first', array('conditions' => array('BusinessOwner.user_id' => $memberId)));
            if (!empty($memberData)) {
                $totalReview = $this->Review->getTotalReviewByUserId($memberData['BusinessOwner']['user_id']);
                $totalReviewAverage = $totalReview*Configure::read('RATING_TYPE_NO');
                $totalAvgRatingArr = $this->Review->getAverage($memberData['BusinessOwner']['user_id']);
                if (!empty($totalAvgRatingArr)) {
                    $memberData['BusinessOwner']['rating'] = round($totalAvgRatingArr/$totalReviewAverage);
                } else {
                    $memberData['BusinessOwner']['rating'] = 0;
                }
                $memberData['BusinessOwner']['totalReview'] = $totalReview;
                $memberData['BusinessOwner']['profession_name'] = $memberData['Profession']['profession_name'];
                $memberData['BusinessOwner']['country_name'] = $memberData['Country']['country_name'];
                $memberData['BusinessOwner']['state_name'] = $memberData['State']['state_subdivision_name'];
                $profile_image = !empty($memberData['BusinessOwner']['profile_image']) ? 'uploads/profileimage/'.$memberData['BusinessOwner']['user_id'].'/'.$memberData['BusinessOwner']['profile_image'] : 'no_image.png';
                $memberData['BusinessOwner']['member_profile_image'] = Configure::read('SITE_URL') . 'img/' . $profile_image;
                $this->set(array(
                    'code' => Configure::read('RESPONSE_SUCCESS'),
                    'result' => $memberData['BusinessOwner'],
                    'message' => 'Team Member Detail',
                    '_serialize' => array('code', 'message', 'result')
                ));
            } else {
                $this->set(array(
                    'code' => Configure::read('RESPONSE_ERROR'),
                    'message' => 'Please try again',
                    '_serialize' => array('code', 'message')
                ));
            }
        } else {
            $errMsg = isset($errMsg) ? $errMsg : "Contact does not exist.";
            $this->errorMessageApi($errMsg);
        }
    }
    
    /**
     * function for the Goals page under Team section
     * @author Rohan julka
     */
    public function goals()
    {
        $userId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
        $bizData = $this->BusinessOwner->find('first',array('conditions'=>array('BusinessOwner.user_id'=>$userId)));
        $groupCreated = $bizData['Group']['created'];
        $groupShufflingDate = $bizData['Group']['shuffling_date'];
        $groupLeader = $bizData['Group']['group_leader_id'];
        $groupCoLeader = $bizData['Group']['group_coleader_id'];
        $groupId = $bizData['BusinessOwner']['group_id'];
        $first_day_this_month = date('Y-m-01 00:00:00');
        $last_day_this_month  = date('Y-m-t 23:59:59');
        $now = time(); // or your date as well
        $your_date = strtotime($groupCreated);
        $datediff = $now - $your_date;
        $groupShufflingDate = date('Y-m-d H:i:s',strtotime($groupShufflingDate));
        $datediff = floor($datediff/(60*60*24));
        $groupID = $this->Encryption->decode($bizData['Group']['id']);
        if($datediff<90) {
            $groupGoalStartDate = $groupCreated;
        } else {
            $groupGoalStartDate = $groupShufflingDate;
        }
        $groupGoalEndDate = date('Y-m-d H:i:s',strtotime("+3 months", strtotime($groupGoalStartDate)));
        if($this->request->is('post')) {
            unset ($this->BusinessOwner->validate['cvv']);
            $this->BusinessOwner->id = $this->Encryption->decode($bizData['BusinessOwner']['id']); 
            $postOperations = array();
            if($bizData['BusinessOwner']['group_role'] == 'leader' || $bizData['BusinessOwner']['group_role'] == 'co-leader') {
                
                // Check if group_goals exists                
                $conditions = array('Goal.user_id' => $groupLeader,'Goal.goal_type' => "group_goals",'Goal.created BETWEEN ? AND ?' => array($groupGoalStartDate,$groupGoalEndDate));
                $dataToSave = array();
                $dataToSave['Goal'] = array('goal_type' => 'group_goals','user_id' => $groupLeader,'group_id' => $groupId,'goal_value'=>$this->request->data['Goal']['group_goals']);                
                $postOperations[] = $this->createUpdateGoals($conditions, $dataToSave,$this->request->data['Goal']['group_goals']);
                // Check if group_member_goals exists
                $conditions = array('Goal.user_id' => $groupLeader,'Goal.goal_type' => 'group_member_goals','Goal.created BETWEEN ? AND ?' => array($first_day_this_month,$last_day_this_month));
                $dataToSave = array();
                $dataToSave['Goal'] = array('goal_type' => 'group_member_goals','user_id' => $groupLeader,'group_id' => $groupId,'goal_value'=>$this->request->data['Goal']['group_member_goals']);
                $postOperations[] = $this->createUpdateGoals($conditions, $dataToSave,$this->request->data['Goal']['group_member_goals']);
                // Check if individual_goals exists                
                $memberID = $userId;
                $conditions = array('Goal.user_id' => $memberID,'Goal.goal_type' => 'individual_goals','Goal.created BETWEEN ? AND ?' => array($first_day_this_month,$last_day_this_month));
                $dataToSave = array();
                $dataToSave['Goal'] = array('goal_type' => 'individual_goals','user_id' => $memberID,'group_id' => $groupId,'goal_value'=>$this->request->data['Goal']['individual_goals']);
                $postOperations[] = $this->createUpdateGoals($conditions, $dataToSave,$this->request->data['Goal']['individual_goals']);
                
                $this->Session->setFlash('Goals have been saved successfully','Front/flash_good');
                
            } else {
                // Check if individual_goals exists                
                $conditions = array('Goal.user_id' => $userId,'Goal.goal_type' => 'individual_goals','Goal.created BETWEEN ? AND ?' => array($first_day_this_month,$last_day_this_month));
                $dataToSave = array();
                $dataToSave['Goal'] = array('goal_type' => 'individual_goals','user_id' => $userId,'group_id' => $groupId,'goal_value'=>$this->request->data['Goal']['individual_goals']);
                $postOperations[] = $this->createUpdateGoals($conditions, $dataToSave,$this->request->data['Goal']['individual_goals']);
                
            }
            if(in_array('updated', $postOperations)) {
                $this->Session->setFlash('Goals have been updated successfully.','Front/flash_good');
            } else {
                $this->Session->setFlash('Goals have been saved successfully.','Front/flash_good');
            }      
        }
        //Group Goal Data
        $conditions = array('Goal.user_id'=>$groupLeader,'Goal.goal_type'=>"group_goals",'Goal.created BETWEEN ? AND ?'=>array($groupGoalStartDate,$groupGoalEndDate),'Goal.group_id'=>$bizData['BusinessOwner']['group_id']);
        $goupGoalData = $this->Goal->find('first',array('conditions'=>$conditions,'fields'=>array('Goal.*')));
        //Group Member Goal Data
        $condition2 = array('Goal.user_id'=>$groupLeader,'Goal.goal_type'=>"group_member_goals",'Goal.created BETWEEN ? AND ?'=>array($first_day_this_month,$last_day_this_month),'Goal.group_id'=>$bizData['BusinessOwner']['group_id']);
        $goupMemberGoalData = $this->Goal->find('first',array('conditions'=>$condition2,'fields'=>array('Goal.*')));
        //Individual Member Goal Data
        $condition2['Goal.goal_type'] = 'individual_goals';
        $condition2['Goal.user_id'] = $userId;
        $individualMemberGoalData = $this->Goal->find('first',array('conditions'=>$condition2,'fields'=>array('Goal.*')));
        $this->set(compact('goupGoalData','goupMemberGoalData','individualMemberGoalData'));
        $actualGoals = $this->calcActualGoals($bizData,$groupId);
        $this->set('actualGoals',$actualGoals);
        $this->set('bizData',$bizData);
    }
    /**
     * function for Fetching Actual Goals
     * @author Rohan julka
     */
    public function calcActualGoals($allData, $groupId)
    {
        $actualGoals = array();
        $userId = $allData['BusinessOwner']['user_id'];
        //Actual Individual goals per month
        $bizData = $this->User->find('first',array('conditions'=>array('User.id'=>$userId,'Subscription.is_active'=>1)));
        $first_day_this_month = date('Y-m-01 00:00:00');
        $last_day_this_month  = date('Y-m-t 23:59:59');
        $groupCreated = $allData['Group']['created'];
        $groupShufflingDate = $allData['Group']['shuffling_date'];
        $now = time(); // or your date as well
        $your_date = strtotime($groupCreated);
        $datediff = $now - $your_date;
        $groupShufflingDate = date('Y-m-d H:i:s',strtotime($groupShufflingDate));
        $datediff = floor($datediff/(60*60*24)).'Days';
        $groupID = $this->Encryption->decode($allData['Group']['id']);
        if($datediff<90) {
            $groupGoalStartDate = $groupCreated;
        } else {
            $groupGoalStartDate = $groupShufflingDate;
        }
        $groupGoalEndDate = date('Y-m-d H:i:s',strtotime("+3 months", strtotime($groupGoalStartDate)));
        $actualGoals['individual'] = $this->ReferralStat->find('count',array('conditions'=>array('sent_from_id'=>$userId,'ReferralStat.group_id'=>$allData['BusinessOwner']['group_id'],'ReferralStat.created BETWEEN ? AND ?' => array($first_day_this_month,$last_day_this_month))));
        //Actual Group Goals        
        $groupData = $this->BusinessOwner->find('all',array('conditions'=>array('BusinessOwner.group_id'=>$groupId)));
        $count = 0;
        foreach ($groupData as $row) {
            $count+= $this->ReferralStat->find('count',array('conditions'=>array('sent_from_id'=>$row['BusinessOwner']['user_id'],'ReferralStat.group_id'=>$allData['BusinessOwner']['group_id'],'ReferralStat.created BETWEEN ? AND ?' => array($groupGoalStartDate,$groupGoalEndDate))));
        }        
        $actualGoals['group_goals'] = $count;
        return $actualGoals;
    }
    /**
     * function to Create or Update Goal Values
     * @author Rohan julka
     */
    public function createUpdateGoals($conditions,$dataToSave,$goalValue)
    {
        $data = $this->Goal->find('first',array('conditions'=>$conditions));
        if (!empty($data)){
            $updateConditions = array('Goal.id' => $this->Encryption->decode($data['Goal']['id']));
            $this->Goal->updateAll(array('Goal.goal_value'=>$goalValue),$updateConditions);
            $response = "updated";
        } else {
            $response = "created";
            $this->Goal->create();
            $this->Goal->save($dataToSave);
        }
        return $response;
    }

	/**
     * function for the Goals data and update goal data
     * @author Priti Kabra
     */
    public function api_goals()
    {
        $userData = $this->BusinessOwner->find('first', array('conditions'=>array('BusinessOwner.user_id' => $this->loggedInUserId)));
        $groupCreated = $userData['Group']['created'];
        $groupShufflingDate = $userData['Group']['shuffling_date'];
        $groupLeader = $userData['Group']['group_leader_id'];
        $groupCoLeader = $userData['Group']['group_coleader_id'];
        $now = time(); // or your date as well
        $your_date = strtotime($groupCreated);
        $datediff = $now - $your_date;
        $groupShufflingDate = date('Y-m-d H:i:s', strtotime($groupShufflingDate));
        $datediff = floor($datediff/(60*60*24)).'Days';
        $groupID = $this->Encryption->decode($userData['Group']['id']);
        if ($datediff < 90) {
            $groupGoalStartDate = $groupCreated;
        } else {
            $groupGoalStartDate = $groupShufflingDate;
        }
        $groupGoalEndDate = date('Y-m-d H:i:s',strtotime("+3 months", strtotime($groupGoalStartDate)));
        //update Goals
        if(isset($this->jsonDecodedRequestedData->mode) && ($this->jsonDecodedRequestedData->mode == "edit")) {
            $bizData = $this->BusinessOwner->find('first', array('conditions'=>array('BusinessOwner.user_id' => $this->loggedInUserId)));
            $groupId = $bizData['BusinessOwner']['group_id'];
            $this->BusinessOwner->id = $this->Encryption->decode($bizData['BusinessOwner']['id']); 
            $postOperations = array();
            if ($bizData['BusinessOwner']['group_role'] == 'leader' || $bizData['BusinessOwner']['group_role'] == 'co-leader') {
                // Check if group_goals exists                
                $conditions = array('Goal.user_id' => $bizData['Group']['group_leader_id'],'Goal.goal_type' => "group_goals", 'Goal.created BETWEEN ? AND ?' => array($groupGoalStartDate,$groupGoalEndDate));
                $dataToSave = array();
                $dataToSave['Goal'] = array('goal_type' => 'group_goals', 'user_id' => $bizData['Group']['group_leader_id'], 'group_id' => $groupId, 'goal_value' => $this->jsonDecodedRequestedData->group_goals);                
                $postOperations[] = $this->createUpdateGoals($conditions, $dataToSave, $this->jsonDecodedRequestedData->group_goals);
                // Check if group_member_goals exists
                $conditions = array('Goal.user_id' => $bizData['Group']['group_leader_id'], 'Goal.goal_type' => 'group_member_goals', 'Goal.created BETWEEN ? AND ?' => array(Configure::read('FIRSTDAY_CURRENT_MONTH'), Configure::read('LASTDAY_CURRENT_MONTH')));
                $dataToSave = array();
                $dataToSave['Goal'] = array('goal_type' => 'group_member_goals','user_id' => $bizData['Group']['group_leader_id'], 'group_id' => $groupId, 'goal_value' => $this->jsonDecodedRequestedData->group_member_goals);
                $postOperations[] = $this->createUpdateGoals($conditions, $dataToSave, $this->jsonDecodedRequestedData->group_member_goals);
                // Check if individual_goals exists                
                $memberID = $this->loggedInUserId;
                $conditions = array('Goal.user_id' => $memberID, 'Goal.goal_type' => 'individual_goals', 'Goal.created BETWEEN ? AND ?' => array(Configure::read('FIRSTDAY_CURRENT_MONTH'), Configure::read('LASTDAY_CURRENT_MONTH')));
                $dataToSave = array();
                $dataToSave['Goal'] = array('goal_type' => 'individual_goals','user_id' => $memberID, 'group_id' => $groupId, 'goal_value'=> $this->jsonDecodedRequestedData->individual_goals);
                $postOperations[] = $this->createUpdateGoals($conditions, $dataToSave, $this->jsonDecodedRequestedData->individual_goals);
                $successMsg = "Goals have been saved successfully.";
            } else {
                // Check if individual_goals exists                
                $conditions = array('Goal.user_id' => $this->loggedInUserId,'Goal.goal_type' => 'individual_goals','Goal.created BETWEEN ? AND ?' => array(Configure::read('FIRSTDAY_CURRENT_MONTH'), Configure::read('LASTDAY_CURRENT_MONTH')));
                $dataToSave = array();
                $dataToSave['Goal'] = array('goal_type' => 'individual_goals', 'user_id' => $this->loggedInUserId, 'group_id' => $groupId, 'goal_value' => $this->jsonDecodedRequestedData->individual_goals);
                $postOperations[] = $this->createUpdateGoals($conditions, $dataToSave, $this->jsonDecodedRequestedData->individual_goals);
            }
            if(in_array('updated', $postOperations)) {
                $successMsg = "Goals have been updated successfully.";
            } else {
                $successMsg = "Goals have been saved successfully.";
            }      
        }
        $conditions = array('Goal.user_id' => $groupLeader, 'Goal.goal_type' => "group_goals",'Goal.created BETWEEN ? AND ?' => array($groupGoalStartDate, $groupGoalEndDate), 'Goal.group_id' => $userData['BusinessOwner']['group_id']);
        $groupGoal = $this->Goal->find('first', array('conditions' => $conditions, 'fields' => array('Goal.*')));
        //Group Member Goal Data
        $condition2 = array('Goal.user_id' => $groupLeader, 'Goal.goal_type' => 'group_member_goals', 'Goal.created BETWEEN ? AND ?' => array(Configure::read('FIRSTDAY_CURRENT_MONTH'), Configure::read('LASTDAY_CURRENT_MONTH')), 'Goal.group_id' => $userData['BusinessOwner']['group_id']);
        $goupMemberGoalData = $this->Goal->find('first',array('conditions'=>$condition2,'fields'=>array('Goal.*')));
        //Individual Member Goal Data
        $condition2['Goal.goal_type'] = 'individual_goals';
        $condition2['Goal.user_id'] = $this->loggedInUserId;
        $individualMemberGoalData = $this->Goal->find('first', array('conditions' => $condition2, 'fields' => array('Goal.*')));
        $actualGoals = $this->calcActualGoals($userData, $this->loggedInUserGroupId);
        $data['group_goals'] = !empty($groupGoal) ? $groupGoal['Goal']['goal_value'] : '';
        $data['group_member_goals'] = !empty($goupMemberGoalData) ? $goupMemberGoalData['Goal']['goal_value'] : '';
        $data['individual_goals'] = !empty($individualMemberGoalData) ? $individualMemberGoalData['Goal']['goal_value'] : '';
        $data['set_edit_key'] = isset($individualMemberGoalData['Goal']['goal_value']) || isset($goupMemberGoalData['Goal']['goal_value']) || isset($groupGoal['Goal']['goal_value']) ? 'update' : 'set';
        $data['actual_group_goals'] = $actualGoals['group_goals'];
        $data['actual_individual_goals'] = $actualGoals['individual'];
        $data['member_type'] = $userData['BusinessOwner']['group_role'];
        //result array
        if (isset($successMsg)) {
            $this->set(array(
                'code' => Configure::read('RESPONSE_SUCCESS'),
                'result' => $data,
                'message' => $successMsg,
                '_serialize' => array('code', 'result', 'message')
            ));
        } else {
            $this->set(array(
                'code' => Configure::read('RESPONSE_SUCCESS'),
                'result' => $data,
                'message' => "Goal Data",
                '_serialize' => array('code', 'result', 'message')
            ));
        }
    }

}
