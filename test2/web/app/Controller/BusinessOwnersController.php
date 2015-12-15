<?php

/**
 * Business Owner controller.
 * PHP 5
 * @author Jitendra Sharma
 * 
 */
App::uses('CakeEmail', 'Network/Email');
App::uses('Email', 'Lib');
App::import('Vendor', 'mpdf/mpdf');
class BusinessOwnersController extends AppController 
{
    public $paginate = array(
        'order' => array('BusinessOwner.created' => 'desc')
    );
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'BusinessOwner';

    /**
     * Components
     *
     * @var array
     * @access public
     */
    public $components = array(
        'Security','Email', 'Csv.Csv', 'Common','Profession','Timezone','ImageResize','Groups','Businessowner'
    );
    var $helpers = array('Html');
    /**
     * Models used by the Controller
     *
     * @var array
     * @access public
     */
    public $uses = array('BusinessOwner','User','GroupChangeRequest','Group','Trainingvideo','Transaction','Subscription', 'LiveFeed','Review', 'CreditCard','Membership', 'ProfessionCategory', 'Profession');

    /**
     * callback function on filter
     * @author Jitendra
     */
    public function beforeFilter() 
    {
        parent::beforeFilter();
        require_once (ROOT.DS.APP_DIR.DS.'Plugin/authorizedotnet/AuthorizeNet.php');
        require_once (ROOT.DS.APP_DIR.DS.'Plugin/facebook/facebook.php');
        $this->Auth->allow('exportBusinessOwners','getCountryList','getStateList', 'renderPDF', 'loginTwitter', 'twitterOauthCallback', 'fbLogin', 'linkedInLogin', 'linkedInOauthCallback', 'linkedin', 'admin_getProfessionList');
        $this->set('titleForLayout', 'FoxHopr: Account');
        $this->Security->unlockedActions=array('admin_index','admin_getData','notifications','twitter','trainingVideo','getCountryList','getStateList','popupFunction','creditCard','admin_groupChangeRequest','admin_kickedOffUserInfo','admin_kickedOffUsers','facebook','linkedIn','social','ajaxUpdateLevelMembership', 'api_changePassword','checkApiHeaderInfo','errorMessageApi', 'api_changeNotifications', 'api_userProfile', 'api_editProfile', 'api_updateCreditCard', 'api_receipts', 'loginTwitter', 'twitterOauthCallback', 'api_social', 'api_socialRevokeAccess', 'api_trainingVideo', 'admin_getProfessionList');
        $this->BusinessOwner->validate=array (        
    	'email' => array (
    		'required' => array (
    			'rule' => 'notEmpty',
    			'message' => 'This field is required'
    		),
    		'unique' => array (
	    		'rule' => 'isUnique',
	    		'message' => 'Email already exists.'
    		)			
		));       
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
     * List Users
     * @author Laxmi Saini
     */
    public function admin_index()
    {
        $this->layout = 'admin';
        $this->set('title_for_layout', 'Business Owners');
        if($this->request->is('POST') && isset($this->request->data['ExportBusinessOwner'])){
            $this->admin_exportBusinessOwners();
        }
        $this->includePageJs = array('admin_validation');
        if (!$this->request->is('ajax')) {
            $this->Session->delete('direction');
            $this->Session->delete('sort');
        }
        $this->BusinessOwner->bindModel(
                array('hasOne' => array(
                        'AvailableSlot' => array(
                            'className' => 'AvailableSlot',
                            'foreignKey' => false,
                            'conditions' => array('BusinessOwner.group_id = AvailableSlot.group_id')
                        )
                    )
                )
            );
        $countries = $this->Common->getAllCountries();
        $professions = $this->Profession->getAllProfessions();
        $categories = $this->ProfessionCategory->find('list', array('fields' => array('ProfessionCategory.id', 'ProfessionCategory.name'), 'order' => array('ProfessionCategory.name ASC')));
        $this->set(compact('countries', 'professions', 'categories'));

        $perpage = $this->Functions->get_param('perpage', Configure::read('PER_PAGE'), true);
        $page = $this->Functions->get_param('page', Configure::read('PAGE_NO'), false);
        $counter = (($page - 1) * $perpage) + 1;
        $this->set('counter', $counter);
        $search = $this->Functions->get_param('search');
        $this->Functions->set_param('direction');        
        $this->Functions->set_param('sort');       
        if ($this->Session->read('sort') != '') {
            $this->paginate['BusinessOwner']['order'] = array($this->Session->read('sort') => $this->Session->read('direction'));
        } else {
            $this->paginate['BusinessOwner']['order'] = array('modified' => 'desc');
        }
        $this->paginate['BusinessOwner']['limit'] = $perpage;
        $condition = array();
        if ($search != '') {
            $nameSearch="concat(BusinessOwner.fname,' ',BusinessOwner.lname) LIKE ";
            $condition['OR']=array($nameSearch=>'%' . $search . '%',
                    'Profession.profession_name LIKE '=>'%' . $search . '%',
                    'Country.country_name LIKE '=>'%' . $search . '%');            
        }
        if (isset($this->params['named']['category'])) {
            $categoryId = $this->params['named']['category'];
        } else if (isset($this->request->data['BusinessOwner']['category_id'])) {
            $categoryId = $this->Encryption->decode($this->request->data['BusinessOwner']['category_id']);
        } else {
            $categoryId = '';
        }
        if ($categoryId != '') {
            $condition['Profession.category_id'] = $categoryId;
        }

        if (isset($this->params['named']['profession'])) {
            $professionId = $this->params['named']['profession'];
        } else if (isset($this->request->data['BusinessOwner']['profession_id'])) {
            $professionId = $this->Encryption->decode($this->request->data['BusinessOwner']['profession_id']);
        } else {
            $professionId = '';
        }
        if ($professionId != '') {
            $condition['BusinessOwner.profession_id'] = $professionId;
        }
        if (isset($this->params['named']['meeting_time'])) {
            $meetingTime = $this->params['named']['meeting_time'];
        } else if (isset($this->request->data['BusinessOwner']['meeting_time'])) {
            $meetingTime = $this->request->data['BusinessOwner']['meeting_time'];
            $this->set('reqMeetingTime',$meetingTime);
        } else {
            $meetingTime = '';
        }
        if ($meetingTime != '') {
            $meetingTime=date("H:i", strtotime($meetingTime));
            $this->loadModel('Group');
            $groupsA = $this->Group->find('list', array(
                'fields' => array('id'),
                'conditions' => array(
                    'Group.meeting_time' => $meetingTime
                ),
                'callbacks' => false
            ));
            if (!empty($groupsA)) {
                $condition['BusinessOwner.group_id'] = $groupsA;
            } else {
                $condition['BusinessOwner.group_id'] = '';
            }
        }
        if (isset($this->params['named']['country'])) {
            $countryId = $this->params['named']['country'];
        } else if (isset($this->request->data['BusinessOwner']['country_id'])) {
            $countryId = $this->request->data['BusinessOwner']['country_id'];
        } else {
            $countryId = '';
        }
        $states='';
        if ($countryId != '') {
           $condition['BusinessOwner.country_id'] = $countryId;
           $states = $this->Common->getStatesForCountry($countryId);
        }        

        if (isset($this->params['named']['state'])) {
            $stateId = $this->params['named']['state'];
        } else if (isset($this->request->data['BusinessOwner']['state_id'])) {
            $stateId = $this->request->data['BusinessOwner']['state_id'];
        } else {
            $stateId = '';
        }
        if ($stateId != '') {
            $condition['BusinessOwner.state_id'] = $stateId;
        }        
        //if (isset($this->params['named']['city'])) {
        //    $cityId = $this->params['named']['city'];
        //} else if (!empty($this->request->data['BusinessOwner']['city_id'])) {
        //    $cityId = $this->request->data['BusinessOwner']['city_id'];
        //} else {
        //    $cityId = '';
        //}
        //if ($cityId != '') {
        //    $condition['BusinessOwner.city LIKE'] = '%' . $cityId. "%"; 
        //}
        $this->paginate['BusinessOwner']['conditions'] = $condition;
        $this->set('businessOwners', $this->paginate());
        $this->set('perpage', $perpage);
        $this->set('search', $search);
        $this->set(compact('categoryId', 'professionId', 'meetingTime', 'stateId', 'countryId', 'states'));
        //$this->set('professionId', $professionId);
        //$this->set('meetingTime', $meetingTime);
        //$this->set('countryId', $countryId);
        //$this->set('stateId', $stateId);
        //$this->set('cityId', $cityId);
        //$this->set('states', $states);
        $this->set('includePageJs', $this->includePageJs);
        if ($this->request->is('ajax')) {
            $this->layout = false;
            $this->render('admin_business_owner_ajax_list'); // View, Layout
        }
    }
   
    /**
     * Action for export list of business owners
     * @Jitendra sharma
     */
    function admin_exportBusinessOwners()
    {    
        $this->layout = "ajax";
        $this->autoRender = false;
        parse_str($this->request->data['ExportBusinessOwner']['filter_params'], $filterParams);
        parse_str($this->request->data['ExportBusinessOwner']['search_params'], $searchParams);
        $filepath = WWW_ROOT . 'files' . DS . 'BusinessOwner_exported_' . date('d-m-Y-H:i:s') . '.csv';
        // fields to be show in exported csv    	
        $fields = array('BusinessOwner.zipcode', 'BusinessOwner.mobile', 'BusinessOwner.office_phone', 'BusinessOwner.timezone_id', 'BusinessOwner.skype_id');
        if (isset($this->request->data['heading'])) {
            
            $fields = array_merge($this->request->data['heading'],$fields );
            
        }
        // condition array
        $condition = array();
        if (isset($filterParams['profession']) && $filterParams['profession'] != "") {
            $condition['BusinessOwner.profession_id'] = $this->Encryption->decode($filterParams['profession']);
        }
        if (isset($filterParams['country']) && $filterParams['country'] != "") {
            $condition['BusinessOwner.country_id'] = $filterParams['country'];
        }
        if (isset($filterParams['state']) && $filterParams['state'] != "") {
            $condition['BusinessOwner.state_id'] = $filterParams['state'];
        }
        //if (isset($filterParams['city']) && $filterParams['city'] != "") {
        //    $condition['BusinessOwner.city'] = $filterParams['city'];
        //}
        if (isset($filterParams['meeting_time']) && $filterParams['meeting_time'] != "") {
            $condition['Group.meeting_time'] = $filterParams['meeting_time'];
        }
        if (isset($searchParams['search']) && $searchParams['search'] != "") {
            $condition['OR'] = array(array('BusinessOwner.fname LIKE' => '%' . $searchParams['search'] . '%'), array('BusinessOwner.lname LIKE' => '%' . $search_params['search'] . '%'));
        }        
        // fetch result array
        $data = $this->BusinessOwner->find('all', array('fields' => $fields, 'conditions' => $condition));
        if(in_array('Profession.profession_name', $fields)) {
            $fields=str_replace('Profession.profession_name', 'Profession.profession', $fields);
        }
        if (count($data) > 0) {            
            $data=$this->formatData($data);
            $exportHeaders=array_keys($data[0]);
            foreach($exportHeaders as $key=>$header){
                $exportHeaders[$key]=ucwords(str_replace('_', ' ', $header));
            }
            $result = $this->Csv->bizOwnersExport($filepath, $data,array(),$exportHeaders);
            $fsize = filesize($filepath);
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: private", false);
            header("Content-type: application/octet-stream");
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=" . basename($filepath) . ";");
            header("Content-Length: " . $fsize);
            readfile($filepath) or die("Errors");
            unlink($filepath);
            exit(0);
        } else {
            $this->Session->setFlash(__('No business owner(s) to download.'), 'flash_bad');
            $this->redirect(array('controller' => 'business_owners', 'action' => 'index'));
        }
    }

    /**
     * to fetch state and city list on country selection
     * @author Laxmi Saini
     */
    public function admin_getData() 
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            if ($this->request->data['countryId'] != '') {
                $condition = $this->request->data['countryId'];
                $data = $this->Common->getStatesForCountry($condition);
                $this->set('data', $data);
            } else {
                $this->set('data', '');
            }
            $this->set('modelName', $this->modelClass);
            $this->render('/Elements/stateCityDropdown', 'ajax');
        }
    }

	/**
     * to fetch profession list on category selection
     * @author Priti Kabra
     */
    public function admin_getProfessionList()
    {
        $this->layout = false;
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            if ($this->request->data['categoryId'] != '') {
                $categoryId = $this->request->data['categoryId'];
                $this->loadModel('Profession');
                if ($this->request->data['professionId'] != '') {
                    $this->set('professionId',$this->Encryption->encode($this->request->data['professionId']));
                }
                $professions = $this->Profession->find('list', array(
                    'conditions' => array('Profession.category_id' => $this->Encryption->decode($categoryId)),
                    'fields' => array('Profession.id', 'Profession.profession_name'),
                    'order' => 'Profession.profession_name ASC'
                    )
                );
                $this->set(compact('professions'));
            } else {
                $this->set('professions', '');
            }
            $this->set('modelName', $this->modelClass);
            $this->render('/Elements/professionDropDown', 'ajax');
        }
    }
    /**
     * view business owner's detail
     * @param string $id encrypted business owner id
     * @author Laxmi Saini
     */
    public function admin_view($bid = null) 
    {
        $this->layout = 'admin';
        $this->set('title_for_layout', 'Business Owners');
        if (!$bid) {
            $this->Session->setFlash(__('Invalid business Owner'), 'flash_bad');
            $this->redirect(array('controller' => 'businessOwners', 'action' => 'index', 'admin' => true));
        }
        $this->set('id', $bid);
        $id = $this->Encryption->decode($bid);
        if (!is_numeric($id)) {
            $this->Session->setFlash(__('Invalid business Owner'), 'flash_bad');
            $this->redirect(array('controller' => 'businessOwners', 'action' => 'index', 'admin' => true));
        }
        $businessOwnerData = $this->BusinessOwner->findById($id);
        $this->set('businessOwnerData', $businessOwnerData);
    }
    
    /**
     * Show admin member group change request(s) list
     * @author Jitendra Sharma
     */
    public function admin_groupChangeRequest() 
    {
    	$this->layout = 'admin';
    	$this->set('title_for_layout', 'Business Owners');
    	$this->includePageJs = array('admin_validation');
    	if (!$this->request->is('ajax')) {
    		$this->Session->delete('direction');
    		$this->Session->delete('sort');
    	}
    	$perpage = $this->Functions->get_param('perpage', Configure::read('PER_PAGE'), true);
    	$page = $this->Functions->get_param('page', Configure::read('PAGE_NO'), false);
    	$counter = (($page - 1) * $perpage) + 1;    	
    	$this->set('counter', $counter);    	
    	
    	$search = $this->Functions->get_param('search');
    	$this->Functions->set_param('direction');
    	$this->Functions->set_param('sort');
    	$condition = array();
    	if ($search != '') {
    		$nameSearch="concat(BusinessOwner.fname,' ',BusinessOwner.lname) LIKE ";
            $condition['OR']=array($nameSearch=>'%' . $search . '%',
                    'Profession.profession_name LIKE '=>'%' . $search . '%',
                    'Group.group_type LIKE '=>'%' . $search . '%',
                    'Group.id LIKE '=>'%' . $search . '%');    		
    	}
    	$condition['GroupChangeRequest.is_moved'] = 0;
    	$condition['GroupChangeRequest.request_type'] = 'cr';
    	$search = "";
    	$fields = array('GroupChangeRequest.*', 'BusinessOwner.fname','BusinessOwner.id','BusinessOwner.lname','Group.*','Profession.profession_name');
    	$this->paginate = array('fields' => $fields,'conditions'=>$condition,'limit'=>$perpage);
    	$this->set('businessOwners', $this->paginate('GroupChangeRequest'));
    	$this->set('perpage', $perpage);
    	$this->set('search', $search);
    	$this->set('includePageJs',$this->includePageJs);
    	
    	if ($this->request->is('ajax')) {
    		$this->layout = false;
    		$this->render('admin_groupChangeRequestAjaxList');
    	}    	  	
    }

    /**
     * view group change request in detail
     * @param string $gid encrypted change request id
     * @author Jitendra Sharma
     */
    public function admin_viewGroupChangeRequest($gid = null) 
    {
        $this->layout = 'admin';
        $this->set('title_for_layout', 'Business Owners');
        
        if (!$gid) {
            $this->Session->setFlash(__('Invalid group change request'), 'flash_bad');
            $this->redirect(array('controller' => 'businessOwners', 'action' => 'groupChangeRequest', 'admin' => true));
        }       
        $this->set('id', $gid);
        $id = $this->Encryption->decode($gid);
        if (!is_numeric($id)) {
            $this->Session->setFlash(__('Invalid group change request'), 'flash_bad');
            $this->redirect(array('controller' => 'businessOwners', 'action' => 'groupChangeRequest', 'admin' => true));
        }
        $changeRequestData = $this->GroupChangeRequest->findById($id);
        $curGroupType = $changeRequestData['Group']['group_type'];
        $professionId = $changeRequestData['BusinessOwner']['profession_id'];
        $groupMeetingDate = $changeRequestData['Group']['second_meeting_date'];
        $proposedMeetingTime = $changeRequestData['GroupChangeRequest']['proposed_meeting_time'];
        $currentGroupId = $changeRequestData['GroupChangeRequest']['group_id'];

        // Get eligible group list with same profession vacant position
        $group_suggestion = array();
        $eligibleGroups = $this->Group->find('list',array('fields'=>'Group.id','conditions'=>array('Group.meeting_time'=>$proposedMeetingTime,'Group.second_meeting_date'=>$groupMeetingDate,'Group.id != '=>$currentGroupId,'Group.group_type'=>$curGroupType,'Group.total_member <'=>20,'FIND_IN_SET(\''. $professionId .'\',Group.group_professions) '=>0)));
        if($eligibleGroups){
            foreach ($eligibleGroups as $key=>$val) {
                $group_suggestion[$key] = 'Group '.$this->Encryption->decode($val);
            }
        }
        $this->set('groupsComponent',$this->Groups);
        $this->set('change_request_data',$changeRequestData);
        $this->set(compact('group_suggestion'));
    }
    
    /**
     * move user from previous group to new group by admin
     * @author Jitendra Sharma
     */
    public function admin_moveUserGroup() 
    {
        $this->layout = 'admin';
        $this->set('title_for_layout', 'Business Owners');
        
        if ($this->request->is('post')) {
            //Save Previous Group Data
            $userID = $this->request->data['Group']['userId'];
            $groupID = $this->Encryption->decode($this->request->data['Group']['group_id']);
            $groupChangeRequestID = $this->Encryption->decode($this->request->data['Group']['groupChangeRequestId']);
            $previousGroupData = $this->GroupChangeRequest->find('first',array('conditions'=>array('GroupChangeRequest.id'=>$groupChangeRequestID),'fields'=>array('GroupChangeRequest.group_id')));
            $userInfo = $this->User->userInfoById($userID);
            $userProfessionId = $userInfo['BusinessOwners']['profession_id'];
            if ($this->Group->isProfessionOccupiedInGroup($this->Encryption->decode($this->request->data['Group']['group_id']), $userProfessionId)) {
                $this->Session->setFlash(__('The group member cannot be moved, due to group unavailability.'), 'flash_bad');
                $this->redirect(array('controller' => 'businessOwners', 'action' => 'groupChangeRequest', 'admin' => true, $this->request->data['Group']['groupChangeRequestId']));
            } else {
                ///Save previous Group data
                $this->Groups->savePrevGroupData($previousGroupData['GroupChangeRequest']['group_id'],$userID);
                if ($groupID != NULL) {                                        
                    $postEmails = $this->Groups->updateGroupInfo($previousGroupData['GroupChangeRequest']['group_id'],$groupID,$userID);
                    //Post emails is necessary
                    if (!empty($postEmails)) {
                        foreach ($postEmails as $row) {
                            $emailLib = new Email();
                            $emailLib->sendEmail($row['to'],$row['subject'],$row['variable'],$row['template'],'both');
                        }
                    }                          
                    // update group change request status
                    $groupChangeData['GroupChangeRequest']['id'] = $groupChangeRequestID;
                    $groupChangeData['GroupChangeRequest']['is_moved'] = 1;
                    $this->GroupChangeRequest->save($groupChangeData);
    
                    // update user group in business owner model
                    $businessOwnerData['BusinessOwner']['is_kicked'] = 0;
                    $businessOwnerData['BusinessOwner']['group_id'] = $groupID;
                    $newGroupData = $this->Group->find('first',array('conditions'=>array('Group.id'=>$groupID),'fields'=>array('Group.*')));
                    $this->BusinessOwner->id = $this->Encryption->decode($userInfo['BusinessOwners']['id']);
                    if ($this->BusinessOwner->save($businessOwnerData)) {
                        $newGroupRole =  $this->BusinessOwner->find('first',array(
                                'conditions' => array('BusinessOwner.user_id' => $userID),
                                'fields' => array('BusinessOwner.group_role')));
                        $emailLib = new Email();
                        $to = $userInfo['BusinessOwners']['email'];
                        $subject = 'FoxHopr: Group change request proposed successfully.';                    
                        $template ='group_change_response';
                        if(strtotime($newGroupData['Group']['first_meeting_date']) > strtotime(date('Y-m-d'))) {
                            $meetingDate = $newGroupData['Group']['first_meeting_date'];
                        } else {
                            $meetingDate = $newGroupData['Group']['second_meeting_date'];
                        }
                        $variable = array('name'=>$userInfo['BusinessOwners']['fname'] . " " . $userInfo['BusinessOwners']['lname'],
                            'secondMeeting' => $meetingDate,
                            'meetingTime' => $newGroupData['Group']['meeting_time'],
                            'groupId' => $groupID,
                            'role' => $newGroupRole['BusinessOwner']['group_role']
                            );
                        $success = $emailLib->sendEmail($to,$subject,$variable,$template,'both');
                       
                        if ($success) {
                            $this->Session->setFlash(__("Your group has been changed successfully."), "flash_good");
                            $this->redirect(array('controller' => 'businessOwners', 'action' => 'groupChangeRequest', 'admin' => true));
                        } else {
                            $this->Session->setFlash(__("Your group has been changed successfully. Email has not sent successfully."), "flash_bad");
                        }
                    } else {
                        $this->Session->setFlash(__('Unable to change group.'), 'flash_bad');
                    }
                } else {
                    
                    $emailLib = new Email();
                    $to = $userInfo['BusinessOwners']['email'];
                    $subject = 'FoxHopr: Group change request status';
                    $template ='group_change_notify';
                    $variable = array('name'=>$userInfo['BusinessOwners']['fname'] . " " . $userInfo['BusinessOwners']['lname'],'message'=> "No group available for change group request. Once the group avialable admin will move to another group.");
                    $success = $emailLib->sendEmail($to,$subject,$variable,$template,'both');               
                    if ($success) {
                        $this->Session->setFlash(__("No group available for change group request."), "flash_bad");
                        $this->redirect(array('controller' => 'businessOwners', 'action' => 'groupChangeRequest', 'admin' => true));
                    } else {
                        $this->Session->setFlash(__("No group available for change group request. Email has not sent successfully."), "flash_bad");
                    }
                }
            }
        }
    }

    /**
     * Show admin kicked off user(s) list
     * @author Priti Kabra
     */
    public function admin_kickedOffUsers()
    {
        $this->layout = 'admin';
        $this->set('title_for_layout', 'Business Owners');
    	$this->includePageJs = array('admin_validation');
    	if (!$this->request->is('ajax')) {
    		$this->Session->delete('direction');
    		$this->Session->delete('sort');
    	}
    	
    	$perpage = $this->Functions->get_param('perpage', Configure::read('PER_PAGE'), true);
    	$page = $this->Functions->get_param('page', Configure::read('PAGE_NO'), false);
    	$counter = (($page - 1) * $perpage) + 1;    	
    	$this->set('counter', $counter);    	
    	$search = $this->Functions->get_param('search');    	
    	$this->Functions->set_param('direction');
    	$this->Functions->set_param('sort'); 
    	$this->paginate['GroupChangeRequest']['limit'] = $perpage;
    	$condition = array();  
    	
    	if ($search != '') {
    		//$condition['BusinessOwner.fname LIKE'] = '%' . $search . '%';
            $condition['OR'] = array(
                  "BusinessOwner.fname LIKE" => "%" . trim($search) . "%",
                  "BusinessOwner.lname LIKE" => "%" . trim($search) . "%",
                  "CONCAT(BusinessOwner.fname ,' ',BusinessOwner.lname) LIKE" => "%" . trim($search) . "%",
                  "Group.id LIKE" => "%" . trim($search) . "%",
                  "Profession.profession_name LIKE" => "%" . trim($search) . "%"
              );
    	}
    	$condition['BusinessOwner.is_kicked'] = 1;
    	$search = "";
    	$this->paginate = array('conditions'=>$condition);
    	$this->set('businessOwners', $this->paginate('BusinessOwner'));
    	$this->set('perpage', $perpage);
    	$this->set('search', $search);    	    	
    	$this->set('includePageJs',$this->includePageJs);         
        if ($this->request->is('ajax')) {
            $this->layout = false;
            $this->render('admin_kicked_off_users_list'); // View, Layout
        }
    }
    
    /**
     * view kicked off user information in detail
     * @param $bid : encrypted kicked off user id
     * @author Priti Kabra
     */
    public function admin_kickedOffUserInfo($bid = null)
    {
        $this->layout = 'admin';
        $this->set('title_for_layout', 'Business Owners');
        if(!$bid) {
            $this->Session->setFlash(__('Invalid business owner'),'flash_bad');
            $this->redirect(array('controller'=>'businessOwners','action'=>'kickedOffUsers','admin'=>true));
        }
        $this->set('id',$bid);
        $id = $this->Encryption->decode($bid);
        if (!is_numeric($id)) {
            $this->Session->setFlash(__('Invalid business Owner'), 'flash_bad');
            $this->redirect(array('controller' => 'businessOwners', 'action' => 'index', 'admin' => true));
        }
        $businessOwnerData = $this->BusinessOwner->findById($id);
        if (empty($businessOwnerData['BusinessOwner']['is_kicked'])) {
            $this->Session->setFlash(__('Kick Off request does not exist.'), 'flash_bad');
            $this->redirect(array('controller' => 'businessOwners', 'action' => 'kickedOffUsers', 'admin' => true));
        }
        $groupSuggestion = array();
        $leaderName = $this->BusinessOwner->find('first', array('conditions' => array('BusinessOwner.user_id' => $businessOwnerData['Group']['group_leader_id']), 'fields' => array('BusinessOwner.fname', 'BusinessOwner.lname')));
        $firstMeetingDate = $businessOwnerData['Group']['first_meeting_date'];
        $secondMeetingDate = $businessOwnerData['Group']['second_meeting_date'];
        $meetingTime = $businessOwnerData['Group']['meeting_time'];
        $sameTimeAvailableGroups = $this->Group->find('list', array(
            'fields' => array('Group.id'),
            'conditions' => array('meeting_time' => $meetingTime,
                'OR' => array('second_meeting_date' => array($firstMeetingDate, $secondMeetingDate), 'first_meeting_date' => array($firstMeetingDate, $secondMeetingDate)),
                'Group.id !=' => $this->Encryption->decode($businessOwnerData['Group']['id']),
                'Group.group_type' => $businessOwnerData['Group']['group_type'])
        ));
        if (!empty($sameTimeAvailableGroups)) {
            $userProfessionId = $businessOwnerData['BusinessOwner']['profession_id'];
            foreach ($sameTimeAvailableGroups as $key => $availableGroup) {
                if ($this->Group->isProfessionOccupiedInGroup($this->Encryption->decode($key), $userProfessionId)) {
                    $cannotDelete = 'not be deleted';
                } else {
                    $businessOwnerData['BusinessOwner']['Group'][$this->Encryption->decode($key)] = Configure::read('GROUP_PREFIX').' '.$this->Encryption->decode($availableGroup);
                    $groupSuggestion[$key] = 'Group '.$this->Encryption->decode($key);
                }
            }
        }
        $this->set(compact('businessOwnerData', 'groupSuggestion', 'leaderName'));
        if ($this->request->is('post')) {
            $checkUserIsKicked = $this->BusinessOwner->find('first', array('conditions' => array('BusinessOwner.id' => $this->Encryption->decode($businessOwnerData['BusinessOwner']['id']), 'BusinessOwner.is_kicked' => 1)));
            if (empty($checkUserIsKicked)) {
                $this->Session->setFlash('Message for shuffled user?', 'flash_bad');
                $this->redirect(array('controller' => 'businessOwners', 'action' => 'kickedOffUsers', 'admin' => true));
            }
            $groupId = $this->Encryption->decode($this->request->data['Group']['group_id']);
            $businessOwnerId = $this->Encryption->decode($bid);
            if ($businessOwnerData['BusinessOwner']['group_id'] == $groupId) {
                $this->Session->setFlash(__('Please try again'),'flash_bad');
                $this->redirect(array('controller' => 'businessOwners', 'action' => 'kickedOffUsers', 'admin' => true));
            }
            $userProfessionId = $businessOwnerData['BusinessOwner']['profession_id'];
            if ($this->Group->isProfessionOccupiedInGroup($this->Encryption->decode($this->request->data['Group']['group_id']), $userProfessionId)) {
                $this->Session->setFlash(__('The group member cannot be moved, due to group unavailability.'), 'flash_bad');
                $this->redirect(array('controller' => 'businessOwners', 'action' => 'kickedOffUserInfo', 'admin' => true, $businessOwnerData['BusinessOwner']['id']));
            } else {
                $this->Groups->savePrevGroupData($businessOwnerData['BusinessOwner']['group_id'], $businessOwnerData['BusinessOwner']['user_id']);
                $postEmails = $this->Groups->updateGroupInfo($businessOwnerData['BusinessOwner']['group_id'], $groupId, $businessOwnerData['BusinessOwner']['user_id']);
                //Post emails is necessary
                if (!empty($postEmails)) {
                    foreach ($postEmails as $row) {
                        $emailLib = new Email();
                        $emailLib->sendEmail($row['to'],$row['subject'],$row['variable'],$row['template'],'both');
                    }
                }
                $newGroupRole =  $this->BusinessOwner->find('first',array(
                                                            'conditions' => array('BusinessOwner.user_id' => $businessOwnerData['BusinessOwner']['user_id']), 
                                                            'fields' => array('BusinessOwner.group_role')));
                $groupMailData['role'] = $newGroupRole['BusinessOwner']['group_role'];
                $groupMailData['id'] = $groupId;
                if(strtotime($businessOwnerData['Group']['first_meeting_date']) > strtotime(date('Y-m-d'))) {
                    $meetingDate = $businessOwnerData['Group']['first_meeting_date'];
                } else {
                    $meetingDate = $businessOwnerData['Group']['second_meeting_date'];
                }
                $groupMailData['date'] = date('m-d-Y', strtotime($meetingDate));
                $groupMailData['time'] = date('h:i A', strtotime($businessOwnerData['Group']['meeting_time']));
                $name = $businessOwnerData['BusinessOwner']['fname']." ".$businessOwnerData['BusinessOwner']['lname'];
                $emailLib = new Email();
                $to = $businessOwnerData['BusinessOwner']['email'];
                $subject = 'FoxHopr: Group replaced successfully';
                $template = 'kick_off_confirmation_email';
                $format = 'both';
                $variable = array('name' => $name, 'groupMailData' => $groupMailData);
                $emailLib->sendEmail($to,$subject,$variable,$template,$format);
                $this->Session->setFlash(__('Group Member has been moved successfully.'), 'flash_good');
                $this->redirect(array('controller' => 'businessOwners', 'action' => 'kickedOffUsers', 'admin' => true));
            }
        }
    }

    /**
     * View user(BusinessOwner) Profile with edit option
     * @author Jitendra Sharma
     * @access public
     */
    public function profile($mode=null)
    {
        $this->layout = 'front';
        $titleForLayout = "FoxHopr: Account";
        $loginUserId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
        $filepath = WWW_ROOT . 'img/uploads/profileimage/' . $loginUserId.'/';
        
        if ($this->request->is('PUT') || $this->request->is('POST')){
        	if (!file_exists($filepath)) {
        		mkdir($filepath, 0777, true);
        		mkdir($filepath. 'resize', 0777, true);
        	}        	
        	$imageInfo['file']	= 	$this->request->data['BusinessOwner']['profile_image'];
        	$attachmentName 	= 	$imageInfo['file']['name'];
        	$imageInfo['path'] 	= 	$filepath;
        	$profileImage 		= 	$this->ImageResize->copy($imageInfo);        
        	$profileImageInfo['file']			= $filepath.$profileImage;
        	$profileImageInfo['width'] 			= 105;	
        	$profileImageInfo['height'] 		= 105;	
        	$profileImageInfo['output'] 		= $filepath."/resize/";
        	$resizeProfileImage = $this->ImageResize->resize($profileImageInfo);        	
        	$this->request->data['BusinessOwner']['profile_image'] = $this->request->data['BusinessOwner']['old_profile_img'];
        	if(!empty($resizeProfileImage)){
        		$this->request->data['BusinessOwner']['profile_image'] = $profileImage;
        		// remove old profile image
        		$oldfile = $this->request->data['BusinessOwner']['old_profile_img'];
        		if(!empty($oldfile)){
        			unlink($filepath.$oldfile);
        			unlink($filepath. 'resize/'.$oldfile);
        		}
        	}        	
        	$this->request->data['BusinessOwner']['id'] = $this->Encryption->decode($this->request->data['BusinessOwner']['id']);
            $this->request->data['BusinessOwner'] = array_map('trim', $this->request->data['BusinessOwner']);
            unset($this->request->data['BusinessOwner']['fname']);
            unset($this->request->data['BusinessOwner']['lname']);
            unset($this->request->data['BusinessOwner']['category']);
        	$this->BusinessOwner->save($this->request->data);
        	$this->Session->setFlash(__('Profile has been updated successfully.'), 'Front/flash_good');
        	$this->redirect(array('action' => 'profile'));        	
        }else{
        	$this->request->data = $this->BusinessOwner->findByUserId($loginUserId);
        	$this->request->data['BusinessOwner']['old_profile_img'] = $this->request->data['BusinessOwner']['profile_image'];
        	$country_id   = $this->request->data['BusinessOwner']['country_id'];
        	$stateList	  = (!empty($country_id)) ? $this->Common->getStatesForCountry($country_id) : array();
        	$this->set(compact('stateList'));
        }
        
        $countryList  = $this->Common->getAllCountries();
        $timezoneList	= $this->Timezone->getAllTimezones();
        $this->set(compact('titleForLayout','countryList','mode','timezoneList','stateList','filepath'));
        $professionCategory = $this->Profession->getProfessionNameById($this->request->data['BusinessOwner']['profession_id']);
        $this->request->data['BusinessOwner']['job_title'] =  $professionCategory['profession'];
        $this->request->data['BusinessOwner']['category'] =  $professionCategory['category_name'];
        $this->set('userID',$loginUserId);
    }
    
    /**
     * Function used for profile Details
     * @author Jitendra Sharma
     */
    public function profileDetail()
    {
    	$this->layout = 'front';
        $titleForLayout = "FoxHopr: Account";
        $loginUserId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
    
    	if ($loginUserId != null) {
    		$memberData = $this->BusinessOwner->find('first', array('conditions' => array('BusinessOwner.user_id' => $loginUserId)));
    		$totalReview = $this->Review->getTotalReviewByUserId($memberData['BusinessOwner']['user_id']);
    		$totalReviewAverage = $totalReview*3;
    		$totalAvgRatingArr = $this->Review->getAverage($memberData['BusinessOwner']['user_id']);
    		if (!empty($totalAvgRatingArr)) {
    			$totalAvgRating = round($totalAvgRatingArr/$totalReviewAverage);
    		} else {
    			$totalAvgRating = 0;
    		}
    		$this->set(compact('memberData','totalAvgRating','totalReview'));
    		$this->set('memberId', $loginUserId);
    		$this->set('sideTab','accountprofile');
    	}
    	$this->render('../Teams/member_detail');
    }

	/**
    * function to change password for web
    * @author Gaurav
    */
    public function changePassword()
    {
        $this->set('titleForLayout', 'FoxHopr: Account');
        $this->validationJs = array('change.password.validation');
        if($this->request->is('post')){
            $this->User->set($this->request->data);
            $this->User->validator()->remove('user_email');
            if($this->User->validates()){
                $userID = $this->Session->read('Auth.Front.id');
                $userInfo = $this->User->userInfoById($this->Encryption->decode($userID));
                $newPassword = AuthComponent::password($this->request->data['User']['new_password']);
                $password = AuthComponent::password($this->request->data['User']['password']);
                if($password != $userInfo['User']['password']){
                    $this->Session->setFlash(__('Current password is incorrect'),"Front/flash_bad");
                    $this->Session->setFlash(__('Current password is incorrect'),"Front/flash_bad","","error");
		    $this->redirect(array('controller' => 'businessOwners', 'action' => 'changePassword'));
                } else if($newPassword == $userInfo['User']['password']) {
                    $this->Session->setFlash(__('Current and New password cannot be same'),"Front/flash_bad","","error2");
                    $this->Session->setFlash(__('Current and New password cannot be same'),"Front/flash_bad");
                    $this->redirect(array('controller' => 'businessOwners', 'action' => 'changePassword'));
                } else {
                    $this->User->id = $this->Encryption->decode($userID);
                    if($this->User->saveField('password',$newPassword)) {
                        $this->Session->setFlash(__('Your password has been changed successfully'),"Front/flash_good");
                        $this->redirect(array('controller' => 'businessOwners', 'action' => 'changePassword'));
                    } else {
                        $this->Session->setFlash(__('Password not update successfully'),"Front/flash_bad");
                    }
                }
            } else {
                $errmsg = '';
                foreach ($this->User->validationErrors as $key => $value) {
                    $errmsg = ($errmsg == '') ? $value[0] : $errmsg.'<br>'.$value[0];
                }
                $this->Session->setFlash(__($errmsg),"Front/flash_bad");
                $this->redirect(array('controller' => 'businessOwners', 'action' => 'changePassword'));
            }            
        }       
        $this->set('validationJs', $this->validationJs);        
    }

    /**
    * function to change notification
    * @author Gaurav
    */
    public function notifications()
    {
        $userId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
        $userData = $this->User->userInfoById($userId);
        if($this->request->is('post')) {            
            if(!empty($this->request->data)) {                
                $notificationEnable = '';
                foreach($this->request->data['noticheck'] as $data) {
                    $notificationEnable = empty($notificationEnable) ? $data : $notificationEnable.','.$data;
                }
                $this->BusinessOwner->id = $this->Encryption->decode($userData['BusinessOwners']['id']);
                if($this->BusinessOwner->saveField('notifications_enabled',$notificationEnable)) {
                    $this->Session->setFlash(__('Your changes have been saved successfully'),"Front/flash_good");
                    $this->redirect(array('controller' => 'businessOwners', 'action' => 'notifications'));
                } else {
                    $this->Session->setFlash(__('Notifications Not Update Successfully'),"Front/flash_bad");
                }
            } else {
                $this->BusinessOwner->id = $this->Encryption->decode($userData['BusinessOwners']['id']);
                if($this->BusinessOwner->saveField('notifications_enabled',NULL)) {
                    $this->Session->setFlash(__('Your changes have been saved successfully'),"Front/flash_good");
                    $this->redirect(array('controller' => 'businessOwners', 'action' => 'notifications'));
                } else {
                    $this->Session->setFlash(__('Notifications Not Update Successfully'),"Front/flash_bad");
                }
            }
        }      
        $notificationEnable = explode(',',$userData['BusinessOwners']['notifications_enabled']);
        $this->set(compact('notificationEnable'));        
    }
    /**
    * function is used to change the export headers fields
    * @author Rohan Julka
    */
    public function formatData($data)
    {
        $newData = array();
        
        foreach ($data as $key=>$row) {
            
            foreach ($row as $subrow) {
                
                foreach ($subrow as $subkey => $subval) {
                    $newData[$key][$subkey] = $subval;
                }
            }            
        }
        $orderedData=array();        
        foreach ($newData as $key=>$row) {            
            $tempData = array();
            if (array_key_exists('member_name',$row)) {
                $tempData['member_name'] = $row['member_name'];
                if ($tempData['member_name'] == NULL || $tempData['member_name'] == '') { $tempData['member_name']="-"; } else { $tempData['member_name'] = ucwords($tempData['member_name']); }
            }            
            if ( array_key_exists('profession_name',$row) ) {
                $tempData['profession'] = $row['profession_name'];
                if ( $tempData['profession'] == NULL || $tempData['profession'] == '' ) { $tempData['profession'] = "-"; }
            }            
            if ( array_key_exists('group_name',$row) ) {
                $tempData['group_name'] = $row['group_name'];
                if($tempData['group_name'] == NULL || $tempData['group_name'] == '' ) { $tempData['group_name']="-"; }
            }            
            if ( array_key_exists('meeting_time',$row) ) {
                $tempData['meeting_time'] = $row['meeting_time'];
                if ($tempData['meeting_time'] == NULL || $tempData['meeting_time'] ) { $tempData['meeting_time'] = "-"; }
            }            
            if ( array_key_exists('first_meeting_date',$row) ) {
                $tempData['meeting_date'] = $row['first_meeting_date'];
                if ( $tempData['meeting_date'] == NULL || $tempData['meeting_date'] == '' ) { $tempData['meeting_date'] = "-"; }
            }            
            if ( array_key_exists('country_name',$row) ) {
                $tempData['country'] = $row['country_name'];
                if ( $tempData['country'] == NULL || $tempData['country'] == '') { $tempData['country'] = "-"; }
            }            
            if ( array_key_exists('state_subdivision_name',$row) ) {
                $tempData['state'] = $row['state_subdivision_name'];
                if ($tempData['state'] == NULL || $tempData['state'] == '') { $tempData['state'] = "-"; }
            }            
            if( array_key_exists('city',$row) ) {
                $tempData['city'] = $row['city'];
                if ($tempData['city'] == NULL || $tempData['city'] == '') { $tempData['city'] = "-";} else { $tempData['city'] = ucfirst($tempData['city']); }
            }
            
            if ( array_key_exists('zipcode',$row) ) {
                $tempData['zipcode'] = $row['zipcode'];
                if( $tempData['zipcode'] == NULL || $tempData['zipcode'] == '' ) { $tempData['zipcode']="-"; }
            }            
            if ( array_key_exists('mobile',$row) ) {
                $tempData['mobile'] = $row['mobile'];
                if ( $tempData['mobile'] == NULL || $tempData['mobile'] == '') { $tempData['mobile'] = "-"; }
            }            
            if ( array_key_exists('office_phone', $row) ) {
                $tempData['office_phone'] = $row['office_phone'];
                if ( $tempData['office_phone'] == NULL || $tempData['office_phone'] == '' ) { $tempData['office_phone'] = "-";}
            }            
            if ( array_key_exists('timezone_id',$row) ) {
                $tempData['timezone'] = $row['timezone_id'];
                if ( $tempData['timezone'] == NULL || $tempData['timezone'] == '') { $tempData['timezone'] = "-"; }
            }            
            if ( array_key_exists('skype_id',$row) ) {
                $tempData['skype_id'] = $row['skype_id'];
                if ( $tempData['skype_id'] == NULL || $tempData['skype_id'] == '') { $tempData['skype_id'] = "-"; }
            }            
            $orderedData[$key] = $tempData;
        }
        return $orderedData;
    }
	/**
     * Twitter action to manage twitter connectivity
     * @author Rohan Julka
     * @access public
     */
	public function twitter()
    {
        //require_once (ROOT.DS.APP_DIR.DS.'Plugin/twitteroauth/twitteroauth.php');
        $this->layout = 'front';
        $titleForLayout = "FoxHopr: Account";
        $loginUserId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
		$checkAccessSession = $this->Session->read('AccessedBy');
        if (!empty($checkAccessSession)) {
            $this->redirect('foxhoprapplication://cancel');
		}
        $data = $this->BusinessOwner->find('first',array('conditions'=>array('User.id'=>$loginUserId)));
        $this->BusinessOwner->id = $this->Encryption->decode($data['BusinessOwner']['id']);
        $oldConfig = $data['BusinessOwner']['notifications_enabled'];
        $oldConfig = explode(',', $oldConfig);

        if ( isset($data['User']['twitter_connected']) && $data['User']['twitter_connected']==1 ) {
            $this->set( 'userData',$data['User'] );
            $this->set( 'twitterConnected',true );
        }
        else {
            $this->set( 'twitterConnected',false );
        }
        
        $this->set( 'twitterData',$oldConfig );
        $this->set( 'userID',$loginUserId );
        $this->render( 'twitter_bkp' );
    }
    /**
    * function to show Training Video for leader and co-leader and update the status if complete video viewed
    * @author Gaurav
    */
    public function trainingVideo()
    {
        $this->set('titleForLayout', 'FoxHopr: Training Video');
        $video = $this->Trainingvideo->find('first',array('conditions'=>array('is_active'=>1))); 
        if($this->request->is('ajax')) {
            $this->autoRender=false;
            $userId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
            $this->BusinessOwner->updateAll(
                array('BusinessOwner.is_unlocked' => 1),
                array( 'BusinessOwner.user_id' => $userId));
            $data = array('code'=>Configure::read('RESPONSE_SUCCESS'), 
                           'response' => 'Thanks for watching the training video.', 
                        );
            echo json_encode($data);
        }
        $this->set(compact('video'));      
    }
    /**
     * Twitter Revoke access via accounts section
     * @author Rohan Julka
     * @access public
     */
    public function twitterLogout()
    {
        $loginUserId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
        $this->User->updateAll( array('twitter_connected'=>0,
            'twitter_oauth_token'=>"''",
            'twitter_oauth_token_secret'=>"''"
            ),array('User.id'=>$loginUserId) );
        $buisnessOwndata = $this->BusinessOwner->find('first',array('conditions'=>array('User.id'=>$loginUserId)));
        if ( !empty($buisnessOwndata) ) {
            $twitterData = explode(',',$buisnessOwndata['BusinessOwner']['notifications_enabled']);
            $pos = array_search('tweetReferralSend', $twitterData);
            if ($pos !== FALSE) {
                unset($twitterData[$pos]);
            }
            $pos = array_search( 'tweetMessageSend', $twitterData );
            if ($pos !== FALSE) {
                unset($twitterData[$pos]);
            }
            $pos = array_search( 'tweetInviteSend', $twitterData );
            if ($pos !== FALSE) {
                unset($twitterData[$pos]);
            }
            $twitterData = implode( ',',array_values($twitterData) );
            $this->BusinessOwner->id = $this->Encryption->decode($buisnessOwndata['BusinessOwner']['id']);
            $this->BusinessOwner->saveField('notifications_enabled',$twitterData);
        }
        $this->Session->setFlash( 'Twitter account has been disconnected','Front/flash_good' );
        $this->redirect( array('action'=>'social','twitter') );
    }
    
    /**
     * Twitter Revoke access via accounts section
     * @author Rohan Julka
     * @access public
     */
    public function loginTwitter($userIdApp = null)
    {
		$userId = !empty($userIdApp) ? $userIdApp : $this->Session->read('Auth.Front.id');
        $userIsConnected = $this->User->find('first', array('conditions' => array('User.id' => array($this->Encryption->decode($userId))), 'fields' => array('User.twitter_connected')));
        if (!empty($userIsConnected['User']['twitter_connected'])) {
            $this->Session->setFlash('Your Twitter account has already been linked','Front/flash_good');
            if (!empty($userIdApp)) {
                $this->redirect('foxhoprapplication://alreadytwitter');
            } else {
                return $this->redirect(array('controller'=>'businessOwners','action'=>'social','twitter'));
            }
        }
        require_once (ROOT.DS.APP_DIR.DS.'Plugin/twitteroauth/twitteroauth.php');       
        $consumer_key = Configure::read('twitter_consumer_key');
        $consumer_secret = Configure::read('twitter_consumer_secret');
        $oauth_callback = Configure::read('twitter_oauth_callback');
        //echo $consumer_key.'<br/>'.$consumer_secret.'<br/>'.$oauth_callback;exit;

        $connection = new TwitterOAuth($consumer_key, $consumer_secret);
        $request_token = $connection->getRequestToken($oauth_callback); //get Request Token
        if ($request_token) {
            $token = $request_token['oauth_token'];
            $this->Session->write('request_token',$token);
            $this->Session->write('request_token_secret',$request_token['oauth_token_secret']);
			if (!empty($userIdApp)) {
                $this->Session->write('Auth.Front.id', $userIdApp);
                $this->Session->write('AccessedBy', 'WebService');
            }
            switch ($connection->http_code) {
                case 200:
                    $url = $connection->getAuthorizeURL($token);
                    //redirect to Twitter .
                    $this->redirect($url);
                    break;
                default:
                    $this->Session->setFlash("Connection with twitter Failed",'Front/flash_bad'); 
                    break;
            }
        } else {
            $this->Session->setFlash("Error Receiving Request Token",'Front/flash_bad');
        }
        $this->autoRender=false;
    }    
    /**
     * Twitter Oauth callback to process the response from twitter
     * @author Rohan Julka
     * @access public
     */
    public function twitterOauthCallback()
    {
        if ($this->request->is('get')) {
            require_once (ROOT.DS.APP_DIR.DS.'Plugin/twitteroauth/twitteroauth.php');
            $consumer_key = Configure::read('twitter_consumer_key');
            $consumer_secret = Configure::read('twitter_consumer_secret');
            $oauth_callback = Configure::read('twitter_oauth_callback');
            $authToken = $this->Session->read('request_token');
            $authTokenSecret = $this->Session->read('request_token_secret');
            $twitterReq = !empty($this->request->data) ? $this->request->data : $this->request->query;
            if(!isset($twitterReq['denied'])) {
                $connection = new TwitterOAuth($consumer_key, $consumer_secret, $authToken, $authTokenSecret);
            
                $access_token = $connection->getAccessToken($this->request->query['oauth_verifier']);
                if ($access_token) {
                    $connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token['oauth_token'], $access_token['oauth_token_secret']);
                    $params = array();
                    $params['include_entities'] = 'false';
                    $content = $connection->get('account/verify_credentials',$params);
                    if ($content && isset($content->screen_name) && isset($content->name)) {
                        $loginUserId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
                        $token = $access_token['oauth_token'];
                        $tokenSecret = $access_token['oauth_token_secret'];
                        $this->User->updateAll(array('twitter_connected'=>1,'twitter_oauth_token'=>"'$token'",'twitter_oauth_token_secret'=>"'$tokenSecret'"),array('User.id'=>$loginUserId));
                        // Update twitterData
                        $buisnessOwndata=$this->BusinessOwner->find('first',array('conditions'=>array('User.id'=>$loginUserId)));
                        if(!empty($buisnessOwndata)) {
                            $twitterData = $buisnessOwndata['BusinessOwner']['notifications_enabled'];
                            $twitterData.= ',tweetReferralSend,tweetMessageSend,tweetInviteSend';
                            $this->BusinessOwner->id = $this->Encryption->decode($buisnessOwndata['BusinessOwner']['id']);
                            $this->BusinessOwner->saveField('notifications_enabled',$twitterData);
                        }
                        $this->Session->setFlash('Your twitter account has been successfully linked','Front/flash_good');
						$checkAccessSession = $this->Session->read('AccessedBy');
                        if (!empty($checkAccessSession)) {
                            $this->Session->delete('AccessedBy');
                            $this->Session->delete('Auth.Front.id');
                            $this->redirect('foxhoprapplication://twitter');
                        }
                    } else {
                        $this->Session->setFlash('Connection failed','Front/flash_bad');
                    }
                } else {
                    if (!empty($checkAccessSession)) {
                        $this->redirect('foxhoprapplication://cancel');
                    }
                    $this->Session->setFlash('Connection failed','Front/flash_bad');
                }
            } else {
                if (!empty($checkAccessSession)) {
                    $this->redirect('foxhoprapplication://cancel');
                }
                $this->Session->setFlash('Connection failed','Front/flash_bad');
            }
        }
        return $this->redirect(array('controller'=>'businessOwners','action'=>'social','twitter'));
    }

    /**
     * function is used for billing section
     * @author Gaurav
     */
    public function billing()
    {
        $loginUserId = $this->Encryption->decode($this->Session->read('Auth.Front.id')); 
        $userData = $this->User->userInfoById($loginUserId);
		$subscriptionData = $this->Subscription->find('first',array('conditions'=>array('Subscription.user_id'=>$loginUserId,'Subscription.is_active'=>1)));
		//pr($subscriptionData);die;
        $this->set(compact('userData','subscriptionData'));
    }
    /**
     * function is used for credit card information under the billing section
     * @author Rohan Julka
     */
    public function creditCard()
    {
        $loginUserId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
        $userData = $this->User->userInfoById($loginUserId);
        if ( $this->request->is('post') ) {
            $conditions = array(
                'Subscription.user_id' => $loginUserId,
            );
            $subscripData = $this->Subscription->find( 'first',array('conditions'=>$conditions) );
            if ( $this->Subscription->hasAny($conditions) ) {
                $subscription = new AuthorizeNet_Subscription;
                $subscription->creditCardCardNumber = $this->request->data['BusinessOwner']['CC_Number'];
                $subscription->creditCardExpirationDate = $this->request->data['BusinessOwner']['CC_year']['year'].'-'.$this->request->data['BusinessOwner']['CC_month']['month'];
                $subscription->creditCardCardCode = $this->request->data['BusinessOwner']['CC_cvv'];
                $subscription->billToFirstName = $this->request->data['BusinessOwner']['CC_Name'];
                $subscription->billToLastName = $this->request->data['BusinessOwner']['CC_Name'];
                $request = new AuthorizeNetARB;
                $response = $request->updateSubscription($subscripData['Subscription']['subscription_id'], $subscription);
                if ( $response->xml->messages->resultCode == 'Ok' ) {
                    $this->BusinessOwner->id = $this->Encryption->decode($userData['BusinessOwners']['id']);
                    $this->BusinessOwner->save(array('BusinessOwner'=>array('credit_card_number'=>$this->Encryption->encode(substr($this->request->data['BusinessOwner']['CC_Number'],-4,4)))));
                    $this->Session->setFlash('The credit card Information has been updated successfully','Front/flash_good');
                } elseif($response->xml->messages->resultCode == 'Error' && $response->xml->messages->message->code == 'E00037'){
                    $this->Session->setFlash('Credit card cannot be updated for a cancelled membership.','Front/flash_bad');
                } elseif($response->xml->messages->resultCode == 'Error' && $response->xml->messages->message->code == 'E00027') {
                    $this->Session->setFlash('Credit card cannot be updated as the merchant does not accept this type of credit card.','Front/flash_bad');
                } else {
                    $this->Session->setFlash('An error occured while processing your request.','Front/flash_bad');
                }
            } else {
                    $this->Session->setFlash('An error occured while processing your request.','Front/flash_bad');
            }
            $this->redirect( array('action'=>'billing') );
        }
        $savedCards = $userData;
        $this->set(compact('savedCards'));
    }
    /**
     * function is used for Purchase Receipts
     * @author Rohan Julka
     */
    public function purchaseReceipts()
    {
        $loginUserId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
        $txData = $this->Transaction->find('all',array('conditions'=>array('Transaction.user_id'=>$loginUserId)));
        $this->set(compact('txData'));
    }
    /**
     * function is used for Purchase Receipts
     * @author Rohan Julka
     */
    public function renderPDF($transactionID = NULL)
    {
        $logoUrl = $this->webroot.'img/logo_black.png';
        $paidLogo = $this->webroot.'img/canstock12610992.jpg';
        if ($transactionID) {
            $txData = $this->Transaction->find('first',array('conditions'=>array('Transaction.id'=>$this->Encryption->decode($transactionID))));
            $amountPaid = $txData['Transaction']['amount_paid'];
            $discounts = 49.99 - $amountPaid;
            $discounts = number_format((float)$discounts, 2, '.', '');
            if (!empty($txData)) {
                $html = '
                <!DOCTYPE html>
                <html lang="en">
                <head>
                <meta charset="utf-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <meta name="description" content="">
                <meta name="author" content="">
                <title>FoxHopr</title>
                <!--[if lt IE 9]>
                <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
                <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
                <![endif]-->
                </head>
                <body id="page-top" class="index">
            
                <style>
                .table-responsive {
                min-height: 0.01%;
                overflow-x: auto;
                }
                
                table.table > tbody > tr > td, .table > tbody > tr > th, table.table > tfoot > tr > td, table.table > tfoot > tr > th, table.table > thead > tr > td, table.table > thead > tr > th {
                
                border: 0.1mm solid #ddd;
                line-height: 1.42857;
                padding: 8px;
                vertical-align: top; text-align:left;font-family: "Times New Roman", Times, serif; fo
                }
                .items tbody,.items thead { border-top: 0.1mm solid #000000;}
                td { vertical-align: top; }
                .items td {
                border-top: 0.1mm solid #ddd;
                
                }
                table thead td {
                text-align: center;
                font-variant: small-caps;
                }
                
                .items td.totals {
                text-align: right;
                border: 0.1mm solid #000000;
                }
                .items td.cost {
                text-align: "." center;
                }                
                </style>
            
                <htmlpageheader name="myheader"> </htmlpageheader>
                
                <htmlpagefooter name="myfooter">
                
                <div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
                Page {PAGENO} of {nb}
                </div>
                </htmlpagefooter>
                
                <sethtmlpageheader name="myheader" value="on" show-this-page="1" />
                <sethtmlpagefooter name="myfooter" value="on" />
                
                mpdf-->
                <table class="table items">
                <tr>
                <th scope="row" style="border-top:0"><a href="'.Router::url('/').'"><img src="'.$logoUrl.'" style="float:left;display:inline-block;text-align:left;width:30%;"></a></th>
                 
                </tr>
                </table>
                <table class="table table-bordered items" width="100%" style="width:700px; margin:0 auto;font-size: 13pt; border-collapse: collapse; ">
                <thead>
                <tr>
                <th style=" border-top:0"></th>
                <td  style=" border-top:0"></td>
                
                <th  style=" text-align:right; border-top:0;padding-bottom:10px;padding-right:5px;">Receipt</th>
                </tr>
                
                
                </thead>
                <tbody>
                <tr >
             
                <td style="padding-top:30px;padding-left:5px;"><strong>Billed to:</strong></td>
                <td style="padding-top:30px;"></td>
                <td style="text-align:right;padding-top:30px;line-height:25px;padding-right:5px;"><strong>Receipt Date:</strong> '.date('M d, Y',strtotime($txData['Transaction']['created'])).'<br>
                </td>
                </tr>
                <tr style="border:none;">
                 
                <td style="padding-top:2px;"></td>
                <td style="padding-top:2px;"></td>
                <td style="padding-top:2px;"></td>
                </tr>
                
                <tr style="border:none;">
                <td class="bt" style="padding-left:5px;">'.$txData['BusinessOwner']['email'].'<br>
                '.$txData['BusinessOwner']['fname'].' '.$txData['BusinessOwner']['lname'].'<br>';
                if (!empty($txData['BusinessOwner']['address'])) {
                    $html.=ucfirst($txData['BusinessOwner']['address']);
                }
                if($txData['BusinessOwner']['address']!='' && $txData['BusinessOwner']['city']!=''){
                    $html.=', ';
                }
                if (!empty($txData['BusinessOwner']['city'])) {
                    $html.=ucfirst($txData['BusinessOwner']['city']);
                }
                $html.='
                </td>
                <td class="bt"></td>
                <td class="bt"></td>
                
                </tr>
            
                <tr style="border:none;border-bottom:1px solid #ddd;">
                <td class="bt" style="padding-bottom:10px;padding-left:5px;">'.$txData['State']['state_subdivision_name'].' '.$txData['BusinessOwner']['zipcode'].'<br>
                '.$txData['Country']['country_name'].'
                </td>
                <td class="bt"></td>
                <td class="bt"></td>
                
                </tr>
                <tr>
                <th style="text-align:left;padding-bottom:20px;padding-left:5px;width:40%;">Membership Plan</th>
                <th style="text-align:left;padding-bottom:20px;width:20%;">Discount</th>
                <th style="text-align:right;padding-bottom:20px;width:40%;padding-right:5px;">Price</th>
                </tr>
                
                
                <tr>
                <td style="padding-bottom:40px;padding-left:5px;padding-left:5px;padding-right:5px;">'.ucfirst($txData['Transaction']['group_type']).'</td>
                <td style="padding-bottom:40px;padding-right:5px;">$'.$discounts.'</td>
                <td style="text-align:right;padding-bottom:40px;padding-right:5px;">$49.99</td>
                
                </tr>
                
                <tr>
            
                <td></td>
                <td></td>
                <td style="text-align:right;line-height:25px;"><b>Total: $'.$amountPaid.'</b> </td>                
                </tr>
                
                </tbody>
                </table>
                </body>
                </html>';
                $mpdf=new mPDF('c','A4','','',15,15,30,25,10,10);
                $mpdf->SetProtection(array('print'));
                $mpdf->SetTitle("FOXHOPR - Invoice");
                $mpdf->SetAuthor("FOXHOPR");
                $mpdf->SetDisplayMode('fullpage');
                /*$mpdf->SetWatermarkText('PAID');
                $mpdf->watermark_font = 'DejaVuSansCondensed';*/
                $mpdf->showWatermarkImage = true;
                $mpdf->SetWatermarkImage('http://10.10.12.69/foxhopr_testing/img/watermark.jpg', 0.15, 'F');
                
                //$mpdf->showWatermarkText = true;
                
                $mpdf->WriteHTML($html);
                $mpdf->Output(); exit;
            } else {
                $this->Session->setFlash('Invalid Transaction ID','Front/flash_bad');
                $this->redirect(array('action'=>'purchaseReceipts'));
            }
        } else {
            $this->Session->setFlash('Invalid Transaction ID','Front/flash_bad');
            $this->redirect(array('action'=>'purchaseReceipts'));
        }
    
    }
    /**
     * Function to connect with Facebook
     * @author Rohan Julka
     * */
    public function facebook()
    {
        $this->layout = 'front';
        $titleForLayout = "FoxHopr: Account";
        $loginUserId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
        $data = $this->BusinessOwner->find('first',array('conditions'=>array('User.id'=>$loginUserId)));
		$checkAccessSession = $this->Session->read('AccessedBy');
        if (!empty($checkAccessSession)) {
            $this->redirect('foxhoprapplication://cancel');
		}
        $this->BusinessOwner->id = $this->Encryption->decode($data['BusinessOwner']['id']);
        $oldConfig = $data['BusinessOwner']['notifications_enabled'];
        $oldConfig = explode(',', $oldConfig);
        if ( isset($data['User']['fb_connected']) && $data['User']['fb_connected']==1 ) {
            $this->set( 'userData',$data['User'] );
            $this->set( 'fbConnected',true );
        }
        else {
            $this->set( 'fbConnected',false );
        }
        
        $this->set( 'fbData',$oldConfig );
        $this->set( 'userID',$loginUserId );
    }
    /**
     * Function to login with Facebook and store Oauth tokens
     * @author Rohan Julka 
     * */
   public function fbLogin($userIdApp = null)
   {
        $userId = !empty($userIdApp) ? $userIdApp : $this->Session->read('Auth.Front.id');
        $userIsConnected = $this->User->find('first', array('conditions' => array('User.id' => array($this->Encryption->decode($userId))), 'fields' => array('User.fb_connected')));
        if (!empty($userIsConnected['User']['fb_connected'])) {
            $this->Session->setFlash('Your Facebook account has already been linked','Front/flash_good');
            if (!empty($userIdApp)) {
                $this->redirect('foxhoprapplication://alreadyfacebook');
            } else {
                return $this->redirect(array('controller'=>'businessOwners','action'=>'social','facebook'));
            }
        }
        if (!empty($userIdApp)) {
            $this->Session->write('Auth.Front.id', $userIdApp);
            $this->Session->write('AccessedBy', 'WebService');
        }
       //Call Facebook API
       $facebook = new Facebook(array(
               'appId'  => Configure::read('appId'),
               'secret' => Configure::read('appSecret')
       ));       
       $fbuser = $facebook->getUser();
       if ($fbuser) {
           try {
               $accessToken = $facebook->getAccessToken();
               $loginUserId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
               $this->User->id = $loginUserId;           
               $this->User->save(array('User'=>array('fb_connected'=>1,'fb_access_token'=>"$accessToken")));
               $buisnessOwndata=$this->BusinessOwner->find('first',array('conditions'=>array('User.id'=>$loginUserId)));
               if(!empty($buisnessOwndata)) {
                   $fbData = $buisnessOwndata['BusinessOwner']['notifications_enabled'];
                   $fbData.= ',fbReferralSend,fbMessageSend,fbInviteSend';
                   $this->BusinessOwner->id = $this->Encryption->decode($buisnessOwndata['BusinessOwner']['id']);
                   $this->BusinessOwner->saveField('notifications_enabled',$fbData);
               }
               $this->Session->setFlash('Your Facebook account has been successfully linked' ,'Front/flash_good');
                $facebook->destroySession();
                $checkAccessSession = $this->Session->read('AccessedBy');
                if (!empty($checkAccessSession)) {
                    $this->Session->delete('AccessedBy');
                    $this->Session->delete('Auth.Front.id');
                    $this->redirect('foxhoprapplication://facebook');
                }
               $this->redirect(array('controller'=>'businessOwners','action'=>'social','facebook'));
           } catch (FacebookApiException $e) {
               echo $e->getMessage();
               $fbuser = null;
           }
       }else{
           //Show login button for guest users
           $loginUrl = $facebook->getLoginUrl(array('redirect_uri'=>Configure::read('SITE_URL').'businessOwners/fbLogin','scope'=>Configure::read('fbPermissions')));
           $fbuser = null;
           // Reditrect to facebook to login
           $this->redirect($loginUrl);
       }
       exit;
   }
   /**
    * Facebook Revoke access via accounts section
    * @author Rohan Julka
    * @access public
    */
   public function fbRevoke()
   {
       $loginUserId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
       $this->User->id = $loginUserId;
       $this->User->save(array('User'=>array('fb_connected'=>0,'fb_access_token'=>"")));
       $buisnessOwndata = $this->BusinessOwner->find('first',array('conditions'=>array('User.id'=>$loginUserId)));
       if ( !empty($buisnessOwndata) ) {
           $twitterData = explode(',',$buisnessOwndata['BusinessOwner']['notifications_enabled']);
           $pos = array_search('fbReferralSend', $twitterData);
           if ($pos !== FALSE) {
               unset($twitterData[$pos]);
           }
           $pos = array_search( 'fbMessageSend', $twitterData );
           if ($pos !== FALSE) {
               unset($twitterData[$pos]);
           }
           $pos = array_search( 'fbInviteSend', $twitterData );
           if ($pos !== FALSE) {
               unset($twitterData[$pos]);
           }
           $twitterData = implode( ',',array_values($twitterData) );
           $this->BusinessOwner->id = $this->Encryption->decode($buisnessOwndata['BusinessOwner']['id']);
           $this->BusinessOwner->saveField('notifications_enabled',$twitterData);
       }
       $this->Session->setFlash( 'Facebook account has been disconnected','Front/flash_good' );
       $this->redirect( array('action'=>'social','facebook') );
   }
   /**
    * Function to post to facebook
    * @author Rohan Julka
    * */
   public function linkedIn()
   {
       $this->layout = 'front';
       $titleForLayout = "FoxHopr: Account";
       $loginUserId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
		$checkAccessSession = $this->Session->read('AccessedBy');
        if (!empty($checkAccessSession)) {
            $this->redirect('foxhoprapplication://cancel');
		}
       $data = $this->BusinessOwner->find('first',array('conditions'=>array('User.id'=>$loginUserId)));
       $this->BusinessOwner->id = $this->Encryption->decode($data['BusinessOwner']['id']);
       $oldConfig = $data['BusinessOwner']['notifications_enabled'];
       $oldConfig = explode(',', $oldConfig);
      
       if ( isset($data['User']['linkedin_connected']) && $data['User']['linkedin_connected']==1 ) {
           $this->set( 'userData',$data['User'] );
           $this->set( 'linkedinConnected',true );
       }
       else {
           $this->set( 'linkedinConnected',false );
       }
       
       $this->set( 'linkedinData',$oldConfig );
       $this->set( 'userID',$loginUserId );
   }
   /**
    * Function to Grant Access to Linkedin
    * @author Rohan Julka
    * */
   public function linkedInLogin($userIdApp = null)
   {
        $userId = !empty($userIdApp) ? $userIdApp : $this->Session->read('Auth.Front.id');
        $userIsConnected = $this->User->find('first', array('conditions' => array('User.id' => array($this->Encryption->decode($userId))), 'fields' => array('User.linkedin_connected')));
        if (!empty($userIsConnected['User']['linkedin_connected'])) {
            $this->Session->setFlash('Your LinkedIn account has already been linked','Front/flash_good');
            if (!empty($userIdApp)) {
                $this->redirect('foxhoprapplication://alreadylinkedin');
            } else {
                return $this->redirect(array('controller'=>'businessOwners','action'=>'social','linkedIn'));
            }
        }
        if (!empty($userIdApp)) {
            $this->Session->write('Auth.Front.id', $userIdApp);
            $this->Session->write('AccessedBy', 'WebService');
        }
       require_once (ROOT.DS.APP_DIR.DS.'Plugin/linkedin/linkedin.php');
       $ln = new LinkedIn(Configure::read('linkedinApiKey'), Configure::read('linkedinApiSecret'), Configure::read('SITE_URL').'businessOwners/linkedInOauthCallback', array('w_share', 'r_basicprofile'));
       if($ln->authorize()) {
           $ln->resetToken();
           $ln->authorize();
           echo 'authorized';
       } else {
           
           echo 'not authorized';
       }
       $this->autoRender = false;
   }
   /**
    * Function to process Linkedin connection data
    * @author Rohan Julka
    * */
   public function linkedInOauthCallback()
   {
       $response = $this->request->query;
       require_once (ROOT.DS.APP_DIR.DS.'Plugin/linkedin/linkedin.php');
       $ln = new LinkedIn(Configure::read('linkedinApiKey'), Configure::read('linkedinApiSecret'));
       $ln->getAccessToken();
       $accessToken = $ln->getTokenData();
       $checkAccessSession = $this->Session->read('AccessedBy');
       if(isset($response['code']) && !isset($response['error'])) {
           $loginUserId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
           $this->User->id = $loginUserId;
           if( $this->User->save(array('User'=>array('linkedin_connected'=>1,'linkedin_access_token'=>$accessToken['access_token'])))) {
               $buisnessOwndata=$this->BusinessOwner->find('first',array('conditions'=>array('User.id'=>$loginUserId)));
               if(!empty($buisnessOwndata)) {
                   $linkedinData = $buisnessOwndata['BusinessOwner']['notifications_enabled'];
                   $linkedinData.= ',linkedinReferralSend,linkedinMessageSend,linkedinInviteSend';
                   $this->BusinessOwner->id = $this->Encryption->decode($buisnessOwndata['BusinessOwner']['id']);
                   $this->BusinessOwner->saveField('notifications_enabled',$linkedinData);
               }
               $this->Session->setFlash('Your LinkedIn account has been successfully linked', 'Front/flash_good');
				if (!empty($checkAccessSession)) {
				    $this->Session->delete('AccessedBy');
				    $this->Session->delete('Auth.Front.id');
				    $this->redirect('foxhoprapplication://linkedin');
				}
           } else {
				if (!empty($checkAccessSession)) {
				    $this->Session->delete('AccessedBy');
				    $this->Session->delete('Auth.Front.id');
				    $this->redirect('foxhoprapplication://cancel');
				}
               $this->Session->setFlash('Unable to authorize. ','Front/flash_bad');
           }
       } else {
           $this->Session->setFlash('Connection to Linkedin failed ', 'Front/flash_bad');
			if (!empty($checkAccessSession)) {
			    $this->Session->delete('AccessedBy');
			    $this->Session->delete('Auth.Front.id');
			    $this->redirect('foxhoprapplication://cancel');
			}
       }
       $this->redirect(array('action'=>'social','linkedin'));
   }
   /**
    * Function to Revoke Linkedin Access
    * @author Rohan Julka
    * */
   public function revokeLinkedIn()
   {
       $loginUserId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
       $this->User->id = $loginUserId;
       if( $this->User->save(array('User'=>array('linkedin_connected'=>0,'linkedin_access_token'=>'')))) {
           $buisnessOwndata = $this->BusinessOwner->find('first',array('conditions'=>array('User.id'=>$loginUserId)));
           if ( !empty($buisnessOwndata) ) {
               $linkedinData = explode(',',$buisnessOwndata['BusinessOwner']['notifications_enabled']);
               $pos = array_search('linkedinReferralSend', $linkedinData);
               if ($pos !== FALSE) {
                   unset($linkedinData[$pos]);
               }
               $pos = array_search( 'linkedinMessageSend', $linkedinData );
               if ($pos !== FALSE) {
                   unset($linkedinData[$pos]);
               }
               $pos = array_search( 'linkedinInviteSend', $linkedinData );
               if ($pos !== FALSE) {
                   unset($linkedinData[$pos]);
               }
               $linkedinData = implode( ',',array_values($linkedinData) );
               $this->BusinessOwner->id = $this->Encryption->decode($buisnessOwndata['BusinessOwner']['id']);
               $this->BusinessOwner->saveField('notifications_enabled',$linkedinData);
           }
           $this->Session->setFlash( 'Linkedin account has been disconnected','Front/flash_good' );
       } else {
           $this->Session->setFlash('Unable to Process. ','Front/flash_bad');
       }
       $this->redirect(array('action'=>'social','linkedin'));
    }
    /**
     * Function to linkedinPost
     * @author Rohan Julka
     * */
    public function linkedinPost()
    {
        require_once (ROOT.DS.APP_DIR.DS.'Plugin/linkedin/linkedin.php');
        $loginUserId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
        $userData = $this->User->userInfoById($loginUserId);
        $ln = new LinkedIn(Configure::read('linkedinApiKey'), Configure::read('linkedinApiSecret'));
        //$ln->addScope('rw_nus');
        //pr($ln->getTokenData());exit;
        $ln->setTokenData($userData['User']['linkedin_access_token']);
        $user = $ln->fetch('GET', '/v1/people/~:(firstName,lastName)');
        
        print "Hello $user->firstName $user->lastName.";
        print_r ($ln->fetch('POST','/v1/people/~/shares',
                array(
                        'comment' => 'Hello Linkedin',
                        'visibility' => array('code' => 'anyone' )
                )
        ));
        
        //Update stored token.
        $tokenData = $ln->getTokenData();
        pr($tokenData);exit;
    }
    /**
     * Function to display social page under Accounts
     * @author Rohan Julka
     * */
    public function social($profile = NULL)
    {
        if($this->request->is('post')) {
            $loginUserId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
            $data = $this->BusinessOwner->find('first',array('conditions'=>array('User.id'=>$loginUserId)));
            $this->BusinessOwner->id = $this->Encryption->decode($data['BusinessOwner']['id']);
            $oldConfig = $data['BusinessOwner']['notifications_enabled'];
            $oldConfig = explode(',', $oldConfig);
            $notifications = array();
            // For saving Facebook Config
            if(isset($this->request->data['BusinessOwner']['config_type']) &&  $this->request->data['BusinessOwner']['config_type'] == 'facebook') {
                $notifications = array('fbReferralSend','fbMessageSend','fbInviteSend');
                $requestKey = 'fb_config';
            } elseif(isset($this->request->data['BusinessOwner']['config_type']) &&  $this->request->data['BusinessOwner']['config_type'] == 'twitter') {
                $notifications = array('tweetReferralSend','tweetMessageSend','tweetInviteSend');
                $requestKey = 'twitter_config';
            } elseif(isset($this->request->data['BusinessOwner']['config_type']) &&  $this->request->data['BusinessOwner']['config_type'] == 'linkedin') {
                $notifications = array('linkedinReferralSend','linkedinMessageSend','linkedinInviteSend');
                $requestKey = 'linkedin_config';
            }
            
            $newConfig = $oldConfig;
            foreach($notifications as $notification) {
                $pos = array_search($notification, $newConfig);
                if ( $pos!==FALSE ) {
                    unset( $newConfig[$pos] );
                }
            }
            if ( $newConfig!='' || $newConfig!=NULL ) {
                if ( !empty($this->request->data[$requestKey]) ) {
                    $requestConfig = $this->request->data[$requestKey];
                    $newConfig = implode( ',',array_merge($newConfig,$requestConfig) );
                } else {
                    $newConfig = implode( ',',$newConfig );
                }
                if ($this->BusinessOwner->saveField('notifications_enabled',$newConfig) ) {
                    $this->Session->setFlash(__('Your changes have been saved successfully'),"Front/flash_good");
                    $oldConfig = $newConfig;
                    $oldConfig = explode(',', $oldConfig);
                } else {
                    $this->Session->setFlash(__('Notifications Not Update Successfully'),"Front/flash_bad");
                }
            }
                
        }
        switch($profile) {
            case 'facebook': $this->facebook(); 
            $this->render('facebook');
                break;
            
            case 'linkedin': $this->linkedIn();
            $this->render('linked_in');
            break; 

            default: $this->twitter();
            break;
        }
    }
    
    /**
     * Function for Membership Levels in admin panel
     * @access Admin
     * @author Rohan Julka
     * */
    function admin_membershipLevels()
    {
        $this->set('title_for_layout','Business Owners');
        if($this->request->is('post')) {
            $flag = 0;
            $error = "";
            $flash = "";
            //pr($this->request->data);
            if($this->request->data['Membership']['Bronze']['upper_limit']>0 && $this->request->data['Membership']['Bronze']['upper_limit']+1 == $this->request->data['Membership']['Silver']['lower_limit']) {
                if($this->request->data['Membership']['Silver']['lower_limit']>0 && $this->request->data['Membership']['Silver']['lower_limit']-1 == $this->request->data['Membership']['Bronze']['upper_limit']) {
                    if($this->request->data['Membership']['Silver']['upper_limit'] > $this->request->data['Membership']['Silver']['lower_limit']) {
                    if($this->request->data['Membership']['Silver']['upper_limit']>0 && $this->request->data['Membership']['Silver']['upper_limit']+1 == $this->request->data['Membership']['Gold']['lower_limit'] ) {
                        if($this->request->data['Membership']['Silver']['upper_limit'] > $this->request->data['Membership']['Silver']['lower_limit']) {
                         
                            if($this->request->data['Membership']['Gold']['lower_limit']>0 && $this->request->data['Membership']['Gold']['lower_limit']-1 == $this->request->data['Membership']['Silver']['upper_limit']) {
                                if($this->request->data['Membership']['Gold']['upper_limit']>0 && $this->request->data['Membership']['Gold']['upper_limit'] > $this->request->data['Membership']['Gold']['lower_limit']) {                               
                                    $data = $this->request->data['Membership'];
                                    $data['Platinum'] = array('lower_limit'=>$data['Gold']['upper_limit']+1, 'upper_limit'=>9999999);
                                    $levelsCount = $this->Membership->find('count');                                
                                    $timestamp = date('Y-m-d H:i:s');
                                    if($levelsCount>0) {
                                        foreach($data as $level=>$value) {
                                            $this->Membership->updateAll(array('Membership.lower_limit' => $value['lower_limit'], 'Membership.upper_limit' => $value['upper_limit'],'Membership.modified'=>"'$timestamp'"), array('Membership.membership_type' => strtolower($level)));
                                        }
                                        $flash = "Membership Levels have been updated Successfully";                              
                                    } else {
                                        foreach($data as $level=>$value) {
                                            $this->Membership->create();
                                            $this->Membership->save(array('Membership'=>array('lower_limit' => $value['lower_limit'], 'upper_limit' => $value['upper_limit'],'membership_type'=>strtolower($level))));
                                        }
                                        $flash = "Membership Levels have been saved Successfully";
                                    }
                                    $this->BusinessOwner->unbindModel(array('belongsTo' => array('Country','State','Group','Profession','User')));
                                    $this->BusinessOwner->updateAll(array('BusinessOwner.is_level_message_viewed'=>0),array('BusinessOwner.group_id !='=>NULL));
                                } else {
                                    $flag = 1;
                                    $error = "The upper limit of gold membership must be greater than lower limit of gold membership";
                                }                            
                            } else {
                                $flag = 1;
                                $error = "The lower limit of gold membership must be in continuity with lower limit of gold membership";
                            }
                        } else {
                            $flag = 1;
                            $error = "The upper limit of gold membership must be greater than the lower limit of gold membership";
                        }
                    } else {
                        $flag = 1;
                        $error = "The upper limit of silver membership must be in continuity with lower limit of gold membership";
                    }
                } else {
                    $flag = 1;
                    $error = "The upper limit of silver membership must be greater than the lower limit of silver membership";
                }
                } else {
                    $flag = 1;
                    $error = "The lower limit of silver membership must be in continuity with upper limit of bronze membership";
                }
            } else {
                $flag = 1;
                $error = "The upper limit of bronze membership must be in continuity with lower limit of silver membership";
            }
            if($flag) {
                $this->Session->setFlash($error,'flash_bad');
            } else {
                $this->Session->setFlash('Membership Levels have been updated.','flash_good');
            }
        }
        $membershipLevels = $this->Membership->find('all');
        $this->set(compact('membershipLevels'));
    }
    
    /**
     * Function to update membership level popup Vars
     * @author Rohan julka
     * */
    public function ajaxUpdateLevelMembership()
    {
        $this->autoRender = false;
        $this->layout = false;
        
        if($this->request->is('ajax')) {
            $loginUserId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
            $userData = $this->User->userInfoById($loginUserId);
            if(!empty($userData) && $userData['BusinessOwners']['is_level_message_viewed'] == 0) {
                $this->BusinessOwner->id = $this->Encryption->decode($userData['BusinessOwners']['id']);
                if($this->BusinessOwner->save(array('is_level_message_viewed'=>1))) {
                    echo 'updated';
                }
               
            }
        }
    }

    /**
     * Web service to change password
     * @author Priti Kabra
     */
    public function api_changePassword()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        if ($error == 0) {
            $this->User->validator()->remove('user_email');
            $userData['password'] = $this->jsonDecodedRequestedData->old_password;
            $userData['new_password'] = $this->jsonDecodedRequestedData->new_password;
            $userData['confirm_password'] = $this->jsonDecodedRequestedData->confirm_password;
            if ($userData['password'] != $userData['new_password']) {
                if ($this->User->validates()) {
                    $condition = array('User.id' => $this->loggedInUserId, 'User.password' => $this->Auth->password($this->jsonDecodedRequestedData->old_password));
                    $userInfo = $this->User->find('first', array('conditions' => $condition));
                    if (!empty($userInfo)) {
                        $this->User->id = $this->loggedInUserId;
                        $this->User->saveField('password', $this->Auth->password($this->jsonDecodedRequestedData->new_password));
                        $this->set(array(
                            'code' => Configure::read('RESPONSE_SUCCESS'),
                            'message' => 'Your password has been changed successfully.',
                            '_serialize' => array('code', 'message')
                        ));
                    } else {
                        $this->errorMessageApi('Current password is incorrect');
                    }
                } else {
                    foreach ($this->User->validationErrors as $key => $value) {
                        $err[] = $value[0];
                    }
                    $this->errorMessageApi($err[0]);
                }
            } else {
                $this->errorMessageApi('Current and New password cannot be same');
            }
        } else {
            $this->errorMessageApi($errMsg);
        }
    }

    /**
    * web service to change notifications
    * @author Priti Kabra
    */
    public function api_changeNotifications()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        if ($error == 0) {
            $conditions = array('BusinessOwner.user_id' => $this->loggedInUserId);
            $fields = array('BusinessOwner.id', 'BusinessOwner.user_id', 'BusinessOwner.notifications_enabled');
            $userData = $this->BusinessOwner->find('first', array('conditions' => $conditions, 'fields' => $fields));
            if ($this->jsonDecodedRequestedData->notifPage == "getNotif") {
                $notificationArr = explode(",", $userData['BusinessOwner']['notifications_enabled']);
                $notifList['weeklySummery'] = 0;
                $notifList['receiveReferral'] = 0;
                $notifList['commentMadeOnReferral'] = 0;
                $notifList['receiveMessage'] = 0;
                $notifList['commentMadeOnMessage'] = 0;
                $notifList['receiveEventInvitation'] = 0;
                $notifList['commentMadeOnEvent'] = 0;
                foreach ($notificationArr as $notification) {
                    switch ($notification) {
                        case "weeklySummery":
                            $notifList[$notification] = 1;
                            break;
                        case "receiveReferral":
                            $notifList[$notification] = 1;
                            break;
                        case "commentMadeOnReferral":
                            $notifList[$notification] = 1;
                            break;
                        case "receiveMessage":
                            $notifList[$notification] = 1;
                            break;
                        case "commentMadeOnMessage":
                            $notifList[$notification] = 1;
                            break;
                        case "receiveEventInvitation":
                            $notifList[$notification] = 1;
                            break;
                        case "commentMadeOnEvent":
                            $notifList[$notification] = 1;
                            break;
                    }
                }
                $this->set(array(
                    'code' => Configure::read('RESPONSE_SUCCESS'),
                    'message' => 'Notification List.',
                    'result' => $notifList,
                    '_serialize' => array('code', 'message', 'result')
                ));
            } elseif ($this->jsonDecodedRequestedData->notifPage == "editNotif") {
                $notificationEnable = '';
                foreach ($this->jsonDecodedRequestedData->notifArr as $notifications_enabled) {
                    $notificationEnable = empty($notificationEnable) ? $notifications_enabled : $notificationEnable.','.$notifications_enabled;
                }
                $this->BusinessOwner->id = $this->Encryption->decode($userData['BusinessOwner']['id']);
                if ($this->BusinessOwner->saveField('notifications_enabled', $notificationEnable)) {
                    $this->set(array(
                        'code' => Configure::read('RESPONSE_SUCCESS'),
                        'message' => 'Your changes have been saved successfully',
                        '_serialize' => array('code', 'message')
                    ));
                } else {
                    $this->errorMessageApi('Please try again');
                }
            }
        } else {
            $this->errorMessageApi($errMsg);
        }
    }

    /**
    * web service to edit profile
    * @author Priti Kabra
    */
    
    public function api_editProfile()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        if ($error == 0) {
            $userData = $this->BusinessOwner->find('first', array('conditions' => array('BusinessOwner.user_id' => $this->loggedInUserId), 'fields' => array('BusinessOwner.id', 'BusinessOwner.profile_image')));
        	if (!empty($_FILES['fileUpload0'])) {
				$filepath = WWW_ROOT . 'img/uploads/profileimage/' . $this->loggedInUserId.'/';
		    	if (!file_exists($filepath)) {
		    		mkdir($filepath, 0777, true);
		    		mkdir($filepath. 'resize', 0777, true);
		    	}
		    	$imageInfo['file'] = $_FILES['fileUpload0'];
		    	$attachmentName = $imageInfo['file']['name'];
		    	$imageInfo['path'] = $filepath;
		    	$profileImage = $this->ImageResize->copy($imageInfo);
		    	$profileImageInfo['file'] = $filepath.$profileImage;
		    	$profileImageInfo['width'] = 105;
		    	$profileImageInfo['height'] = 105;
		    	$profileImageInfo['output'] = $filepath."/resize/";
		    	$resizeProfileImage = $this->ImageResize->resize($profileImageInfo);
		    	$_POST['profile_image'] = !empty($userData['BusinessOwner']['profile_image']) ? (string)$userData['BusinessOwner']['profile_image'] : NULL;
		    	if (!empty($resizeProfileImage)) {
		    		$_POST['profile_image'] = $profileImage;
		    		// remove old profile image
		    		$oldfile = $userData['BusinessOwner']['profile_image'];
		    		if (!empty($oldfile)) {
		    			unlink($filepath.$oldfile);
		    			unlink($filepath. 'resize/'.$oldfile);
		    		}
		    	}
			}
            $this->BusinessOwner->id = $this->Encryption->decode($userData['BusinessOwner']['id']);
            if ($this->BusinessOwner->save($_POST)) {
                $this->set(array(
                    'code' => Configure::read('RESPONSE_SUCCESS'),
                    'message' => 'Profile has been updated successfully',
                    '_serialize' => array('code', 'message')
                ));
            }
        } else {
            $this->errorMessageApi($errMsg);
        }
    }

    /**
     * web service used to update the credit card information of the logged in user
     * @author Priti Kabra
     */
    public function api_updateCreditCard()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        if ($error == 0) {
            $conditions = array(
                'Subscription.user_id' => $this->loggedInUserId,
                'Subscription.is_active' => 1
            );
            $this->Subscription->bindModel(
                array('hasOne' => array(
                        'BusinessOwner' => array(
                            'className' => 'BusinessOwner',
                            'foreignKey' => false,
                            'conditions' => array('BusinessOwner.user_id = Subscription.user_id')
                        )
                    )
                )
            );
            $subscripData = $this->Subscription->find('first',array('conditions'=>$conditions));
            if ($this->Subscription->hasAny($conditions)) {
                $subscription = new AuthorizeNet_Subscription;
                $subscription->creditCardCardNumber = $this->jsonDecodedRequestedData->CC_Number;
                $subscription->creditCardExpirationDate = $this->jsonDecodedRequestedData->CC_year . '-' . $this->jsonDecodedRequestedData->CC_month;
                $subscription->creditCardCardCode = $this->jsonDecodedRequestedData->CC_cvv;
                $subscription->billToFirstName = $this->jsonDecodedRequestedData->CC_Name;
                $subscription->billToLastName = $this->jsonDecodedRequestedData->CC_Name;
                $request = new AuthorizeNetARB;
                $response = $request->updateSubscription($subscripData['Subscription']['subscription_id'], $subscription);
                if ($response->xml->messages->resultCode == 'Ok') {
                    $info['credit_card_number'] = substr($this->jsonDecodedRequestedData->CC_Number,-4,4);
                    $this->BusinessOwner->id = $this->Encryption->decode($subscripData['BusinessOwner']['id']);
                    $this->BusinessOwner->saveField('credit_card_number', $this->Encryption->encode(substr($this->jsonDecodedRequestedData->CC_Number,-4,4)));
                    $this->set(array(
                        'code' => Configure::read('RESPONSE_SUCCESS'),
                        'result' => $info,
                        'message' => 'The credit card Information has been updated successfully',
                        '_serialize' => array('code', 'result', 'message')
                    ));
                } else {
                    $this->errorMessageApi('Credit card cannot be updated for cancelled membership');
                }
            } else {
                $this->set(array(
                    'code' => Configure::read('RESPONSE_SUCCESS'),
                    'message' => 'Credit card cannot be updated for cancelled membership',
                    '_serialize' => array('code', 'message')
                ));
            }
        } else {
            $this->errorMessageApi($errMsg);
        }
    }

    /**
     * Web service to get the list of transactions done till date
     * @author Priti Kabra
     */
    public function api_receipts()
    {
        $this->Transaction->unbindModel(array('hasOne' => array('Country', 'State', 'Subscription')));
        $txData = $this->Transaction->find('all', array('conditions' => array('Transaction.user_id' => $this->loggedInUserId)));
        if (!empty($txData)) {
            $i = 0;
            foreach ($txData as $transaction) {
                $transationData[$i]['group_type'] = $transaction['Transaction']['group_type'];
                $transationData[$i]['invoice_date'] = $transaction['Transaction']['created'];
                $transationData[$i]['purchase'] = $transaction['Transaction']['purchase_date'];
                $transationData[$i]['pdfUrl'] = Configure::read('SITE_URL').'businessOwners/renderPDF/'.$transaction['Transaction']['id'];
                $i++;
            }
            $this->set(array(
                'code' => Configure::read('RESPONSE_SUCCESS'),
                'result' => $transationData,
                'message' => 'Receipt List',
                '_serialize' => array('code', 'result', 'message')
            ));
        } else {
            $this->errorMessageApi('No Record found');
        }
    }

	/**
     * check Social connectivity
     * @author Priti Kabra
     * @access public
     */
	public function api_social()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        if ($error == 0) {
            $message = 'Social Connect Information';
            $userData = $this->User->find('first', array('conditions' => array('User.id' => $this->loggedInUserId)));
            $socialConnect['twitter'] = $userData['User']['twitter_connected'];
            $socialConnect['facebook'] = $userData['User']['fb_connected'];
            $socialConnect['linkedin'] = $userData['User']['linkedin_connected'];
            if (!empty($this->jsonDecodedRequestedData->mode)) {
                $notifData = $userData['BusinessOwners']['notifications_enabled'];
                if (!empty($this->jsonDecodedRequestedData->updateSocial)) {
                    switch ($this->jsonDecodedRequestedData->updateSocial) {
                        case "twitter":
                            $isConnected = $userData['User']['twitter_connected'];
                            break;
                        case "facebook":
                            $isConnected = $userData['User']['fb_connected'];
                            break;
                        case "linkedin":
                            $isConnected = $userData['User']['linkedin_connected'];
                            break;
                        default:
                            break;
                    }
                    if (!empty($isConnected)) {
                        $updateInfo = $this->updateSocialMedia($this->loggedInUserId, $this->jsonDecodedRequestedData);
                        if (!empty($updateInfo)) {
                            $message = 'Your preferences are saved';
                            $notifData = $updateInfo;
                        } else {
                            $errMessage = 'Notifications Not Update Successfully';
                        }
                    } else {
                        $errMessage = 'Changes cannot be saved as the access has been revoked';
                    }
                }
                switch ($this->jsonDecodedRequestedData->list) {
                    case "twitter":
                        $socialConnect['tweetReferralSend'] = (strpos($notifData, 'tweetReferralSend')) ? true : false;
                        $socialConnect['tweetMessageSend'] = (strpos($notifData, 'tweetMessageSend')) ? true : false;
                        $socialConnect['tweetInviteSend'] = (strpos($notifData, 'tweetInviteSend')) ? true : false;
                        break;
                    case "facebook":
                        $socialConnect['fbReferralSend'] = (strpos($notifData, 'fbReferralSend')) ? true : false;
                        $socialConnect['fbMessageSend'] = (strpos($notifData, 'fbMessageSend')) ? true : false;
                        $socialConnect['fbInviteSend'] = (strpos($notifData, 'fbInviteSend')) ? true : false;
                        break;
                    case "linkedin":
                        $socialConnect['linkedinReferralSend'] = (strpos($notifData, 'linkedinReferralSend')) ? true : false;
                        $socialConnect['linkedinMessageSend'] = (strpos($notifData, 'linkedinMessageSend')) ? true : false;
                        $socialConnect['linkedinInviteSend'] = (strpos($notifData, 'linkedinInviteSend')) ? true : false;
                        break;
                    default:
                        break;
                }
            }
            if (!empty($errMessage)) {
                $this->errorMessageApi($errMessage);
            } else {
                $this->set(array(
                    'code' => Configure::read('RESPONSE_SUCCESS'),
                    'result' => $socialConnect,
                    'message' => $message,
                    '_serialize' => array('code', 'result', 'message')
                ));
            }
        } else {
            $this->errorMessageApi($errMsg);
        }
    }

    /**
     * update Social media connection notifications
     * @author Priti Kabra
     * @access public
     */
    public function updateSocialMedia($loginUserId, $requestData)
    {
        $data = $this->BusinessOwner->find('first', array('conditions' => array('User.id' => $loginUserId)));
        $this->BusinessOwner->id = $this->Encryption->decode($data['BusinessOwner']['id']);
        $oldConfig = $data['BusinessOwner']['notifications_enabled'];
        $oldConfig = explode(',', $oldConfig);
        $notifications = array();
        if($requestData->updateSocial == 'facebook') {
            $notifications = array('fbReferralSend', 'fbMessageSend', 'fbInviteSend');
            $requestKey = 'fb_config';
        } elseif($requestData->updateSocial == 'twitter') {
            $notifications = array('tweetReferralSend','tweetMessageSend','tweetInviteSend');
            $requestKey = 'twitter_config';
        } elseif($requestData->updateSocial == 'linkedin') {
            $notifications = array('linkedinReferralSend','linkedinMessageSend','linkedinInviteSend');
            $requestKey = 'linkedin_config';
        }
        $newConfig = $oldConfig;
        foreach ($notifications as $notification) {
            $pos = array_search($notification, $newConfig);
            if ($pos !== FALSE) {
                unset( $newConfig[$pos] );
            }
        }
        if (!empty($newConfig)) {
            if (!empty($requestData->updateFields)) {
                $requestConfig = $requestData->updateFields;
                $newConfig = implode(',', array_merge($newConfig, $requestData->updateFields));
            } else {
                $newConfig = implode(',', $newConfig);
            }
            if ($this->BusinessOwner->saveField('notifications_enabled', $newConfig)) {
                return $newConfig;
            } else {
                return 0;
            }
        }
    }

    /**
     * Revoke access from social media via accounts section
     * @author Priti Kabra
     * @access public
     */
    public function api_socialRevokeAccess()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        if ($error == 0) {
            $userData = $this->BusinessOwner->find('first', array('conditions' => array('User.id' => $this->loggedInUserId), 'fields' => array('BusinessOwner.id', 'BusinessOwner.notifications_enabled')));
            if ($this->jsonDecodedRequestedData->revokeList) {
                switch ($this->jsonDecodedRequestedData->revokeList) {
                    case "twitter":
                        $updateField['twitter_connected'] = 0;
                        $updateField['twitter_oauth_token'] = '';
                        $updateField['twitter_oauth_token_secret'] = '';
                        //unset the notifications
                        $notifData = explode(',', $userData['BusinessOwner']['notifications_enabled']);
                        $pos = array_search('tweetReferralSend', $notifData);
                        if ($pos !== FALSE) {
                            unset($notifData[$pos]);
                        }
                        $pos = array_search('tweetMessageSend', $notifData);
                        if ($pos !== FALSE) {
                            unset($notifData[$pos]);
                        }
                        $pos = array_search('tweetInviteSend', $notifData);
                        if ($pos !== FALSE) {
                            unset($notifData[$pos]);
                        }
                        $message = "Twitter account has been disconnected";
                        break;
                    case "facebook":
                        $updateField['fb_connected'] = 0;
                        $updateField['fb_access_token'] = '';
                        //unset the notifications
                        $notifData = explode(',', $userData['BusinessOwner']['notifications_enabled']);
                        $pos = array_search('fbReferralSend', $notifData);
                        if ($pos !== FALSE) {
                            unset($notifData[$pos]);
                        }
                        $pos = array_search('fbMessageSend', $notifData);
                        if ($pos !== FALSE) {
                            unset($notifData[$pos]);
                        }
                        $pos = array_search('fbInviteSend', $notifData);
                        if ($pos !== FALSE) {
                            unset($notifData[$pos]);
                        }
                        $message = "Facebook account has been disconnected";
                        break;
                    case "linkedin":
                        $updateField['linkedin_connected'] = 0;
                        $updateField['linkedin_access_token'] = '';
                        //unset the notifications
                        $notifData = explode(',', $userData['BusinessOwner']['notifications_enabled']);
                        $pos = array_search('linkedinReferralSend', $notifData);
                        if ($pos !== FALSE) {
                            unset($notifData[$pos]);
                        }
                        $pos = array_search('linkedinMessageSend', $notifData);
                        if ($pos !== FALSE) {
                            unset($notifData[$pos]);
                        }
                        $pos = array_search('linkedinInviteSend', $notifData);
                        if ($pos !== FALSE) {
                            unset($notifData[$pos]);
                        }
                        $message = "LinkedIn account has been disconnected";
                        break;
                    default:
                        break;
                }
            }
            $this->User->id = $this->loggedInUserId;
            $this->User->save($updateField);
            if (!empty($notifData)) {
                $notifData = implode(',', array_values($notifData));
                $this->BusinessOwner->id = $this->Encryption->decode($userData['BusinessOwner']['id']);
                if ($this->BusinessOwner->saveField('notifications_enabled', $notifData)) {
                    $this->set(array(
                        'code' => Configure::read('RESPONSE_SUCCESS'),
                        'message' => $message,
                        '_serialize' => array('code', 'message')
                    ));
                } else {
                    $this->errorMessageApi('Try again later');
                }
            } else {
                $this->set(array(
                    'code' => Configure::read('RESPONSE_SUCCESS'),
                    'message' => $message,
                    '_serialize' => array('code', 'message')
                ));
            }
        } else {
            $this->errorMessageApi($errMsg);
        }
    }

    /**
    * function to show Training Video for leader and co-leader and update the status if viewed
    * @author Priti Kabra
    */
    public function api_trainingVideo()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        if ($error == 0) {
            $video = $this->Trainingvideo->find('first', array('conditions' => array('is_active' => 1)));
            if (!empty($video['Trainingvideo']['video_name'])) {
                $video['url'] = Configure::read('SITE_URL') . 'trainingvideo/'.$video['Trainingvideo']['video_name'];
                $this->set(array(
                    'code' => Configure::read('RESPONSE_SUCCESS'),
                    'result' => $video['url'],
                    'message' => 'Training video',
                    '_serialize' => array('code', 'result', 'message')
                ));
            }
            if (!empty($this->jsonDecodedRequestedData->trainingVideo)) {
                $userData = $this->BusinessOwner->find('first', array('conditions' => array('BusinessOwner.user_id' => $this->loggedInUserId), 'fields' => array('BusinessOwner.group_role', 'BusinessOwner.id')));
                if (in_array($userData['BusinessOwner']['group_role'], array('leader', 'co-leader'))) {
                    $this->BusinessOwner->id = $this->Encryption->decode($userData['BusinessOwner']['id']);
                    if ($this->BusinessOwner->saveField('is_unlocked', 1)) {
                        $this->set(array(
                            'code' => Configure::read('RESPONSE_SUCCESS'),
                            'message' => 'Thanks for watching the training video.',
                            '_serialize' => array('code', 'message')
                        ));
                    } else {
                        $this->errorMessageApi('Please try again later');
                    }
                }
            }
        } else {
            $this->errorMessageApi($errMsg);
        }
    }
}
