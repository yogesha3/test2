<?php

/**
 * This is contact controller
 *
 */
App::uses('Email', 'Lib');
class ContactsController extends AppController 
{
    public $components=array('Paginator','Csv.Csv');
    public $uses = array('User', 'Contact','InvitePartner', 'ReceivedReferral');
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('titleForLayout', 'FoxHopr: Contacts');
        $this->layout = 'front';
    }

    /**
     *Function to add contact by the registered user
     *@author Priti Kabra
     */
    public function addContact() 
    {
        $this->validationJs = array('add.contact.validation');
        $this->set('validationJs', $this->validationJs);
        $countryList  = $this->Common->getAllCountries();
        $this->set(compact('countryList'));
        if ($this->request->is('post')) {
            if (!empty($this->request->data['BusinessOwner']['state_id'])) {
                $this->request->data['Contact']['state_id'] = $this->request->data['BusinessOwner']['state_id'];
            }
            $this->request->data['Contact']['user_id'] = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
            $this->request->data['Contact'] = array_map('trim', $this->request->data['Contact']);
            if ($this->Contact->validates()) {
                $this->Contact->create();
                if(!empty($this->request->data['Contact']['job_title'])) {
                    $this->request->data['Contact']['job_title'] = htmlentities($this->request->data['Contact']['job_title']);
                }
                $groupId = NULL;
                $userId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
                $fields = array('Groups.*');
                $userData=$this->User->find('first',array('conditions'=>array('User.id'=>$userId),'fields'=>$fields));
                $groupId = $this->Encryption->decode( $userData['Groups']['id'] );
                $dataToInsert = $this->request->data;
                if($groupId) {
                   $dataToInsert['Contact']['user_groupid'] = $groupId;
                }
                if ($this->Contact->save( $dataToInsert['Contact'])) {
                    $this->Session->setFlash('Contact has been added successfully.', 'Front/flash_good');
                    $this->redirect(array('controller' => 'contacts', 'action' => 'contactList'));
                } else {
                    //$this->Session->setFlash('Please try again.', 'Front/flash_bad');
                    $this->request->data = $this->request->data;
                }
            } else {
                $validationErrors=$this->compileErrors('Contact');
                if($validationErrors!=NULL) {
                    $this->Session->setFlash($validationErrors, 'Front/flash_bad');
                }
                $this->request->data = $this->request->data;
            }
        }
    }

    /**
     *Function to get the contact list
     *@author Priti Kabra
     */
    public function contactList() 
    {
        $this->layout = 'front';
        $groupId = $this->Session->read('Auth.Front.BusinessOwners.group_id');
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
        $condition1 = array('Contact.user_id' => $userId);
        $fields = array('Contact.id', 'Contact.first_name', 'Contact.last_name', 'Contact.job_title', 'Contact.email', 'Contact.user_id');
         if ($search != '') {
              $condition2['OR'] = array(                 
                  "Contact.first_name LIKE" => "%" . trim($search) . "%",
                  "Contact.last_name LIKE" => "%" . trim($search) . "%",
                  "CONCAT(Contact.first_name ,' ',Contact.last_name) LIKE" => "%" . trim($search) . "%",
                  "Contact.job_title LIKE" => "%" . trim($search) . "%",
                  "Contact.email LIKE" => "%" . trim($search) . "%"
              );
        } else {
            $condition2 = array();
        }
        $condition = array_merge($condition1, $condition2);
        $this->Paginator->settings = array(
                    'conditions' => $condition,
                    'fields' => $fields,
                    'order' => $order,
                    'limit' =>$perpage,
                    'recursive'=>-1
                );
        $contactList = $this->Paginator->paginate('Contact');
        if (!empty($search)) {
            $noDataMsg = "No result found";
            $error = (strpos($search, '%') !== false) ? 1 : 0;
            if ($error == 0) {
                $error = (strpos($search, '_') !== false) ? 1 : 0;
            }
            if ($error == 1) {
                $contactList = '';
            }
            $this->set('noDataMsg', $noDataMsg);
        }
        $this->set(compact('contactList', 'search'));
        if ($this->request->is('ajax')) {
            $this->layout = false;
            $noDataMsg = "No results found";
            $this->set('perpage', $perpage);
            $this->set('search', $search);
            $this->set('noDataMsg', $noDataMsg);
            $sessionCheck = $this->Session->read('sessionDelMsg');
            if (!empty($sessionCheck)) {
                $noDataMsg = "No record found";
                $this->Session->delete('sessionDelMsg');
                $this->set('noDataMsg', $noDataMsg);
            }
            $this->render('contact_list_ajax');
        }
    }

    /**
     * Function to delete the contacts
     * @author Priti Kabra
     * @access public
     */
    public function bulkAction()
    {
        $this->Session->write('sessionDelMsg', 'No record found');
        $action = $this->request->data['mass_action'];
        if (is_array($this->request->data['contactIds'])) {    	
            if ($action=="massdelete") {
                $record = $this->request->data['contactIds'];
                foreach ($record as $deleteId) {
                    $this->Contact->delete($this->Encryption->decode($deleteId));
                }    		
            }
        }
        $this->redirect(array('controller' => 'contacts', 'action' => 'contactList'));
    }

    /**
     * Function to delete a contact
     * @author Priti Kabra
     * @param string $deleteId delete id
     * @access public
     */
    public function deleteContact($deleteId=null)
    {
        $this->Session->write('sessionDelMsg', 'No record found');
        if ($this->Contact->delete($this->Encryption->decode($deleteId))) {
            $contactList = $this->contactList();
        }
    }

    /**
    * Function used for contact Details
    * @param int $contactId contact id
    * @param string $backurl back url
    * @author Priti Kabra
    */
    public function contactDetail($contactId = null, $backurl = null)
    {
          $this->set('referer', $backurl);
          if ($contactId != null) {
           $contactId = $this->Encryption->decode($contactId);
              if (!$this->Contact->exists($contactId)) {
               $this->Session->setFlash(__('This contact does not exist.'), 'Front/flash_bad');
               $this->redirect(array('controller' => 'contacts', 'action' => 'contactList'));
             }
            $contactData = $this->Contact->find('first', array('conditions' => array('Contact.id' => $contactId)));
            $this->set(compact('contactData'));
            $this->set('contactId', $contactId);
        } else {
            $this->Session->setFlash(__('This contact does not exist.'), 'Front/flash_bad');
            $this->redirect(array('controller' => 'contacts', 'action' => 'contactList'));
        }       
    }

    /**
    * Function used to update contact Details
    * @param int $contactId contact id
    * @param string $backurl back url
    * @author Priti Kabra
    */
    public function contactUpdate($contactId = null, $backurl = null)
    {
        $this->validationJs = array('edit.contact.validation');
        $this->set('validationJs', $this->validationJs);
        $countryList  = $this->Common->getAllCountries();
        $this->set('referer', $backurl);
        if ($contactId != null) {
            $contactId = $this->Encryption->decode($contactId);
            if (!$this->Contact->exists($contactId)) {
                $this->Session->setFlash(__('This contact does not exist.'), 'Front/flash_bad');
                $this->redirect(array('controller' => 'contacts', 'action' => 'contactList'));
             }
            $contactData = $this->Contact->find('first', array('conditions' => array('Contact.id' => $contactId)));
            $stateList  = $this->Common->getStatesForCountry($contactData['Contact']['country_id']);
            $this->set(compact('contactData', 'countryList', 'stateList'));
            if ($this->request->is('post')) {
                if (!empty($this->request->data['BusinessOwner']['state_id'])) {
                    $this->request->data['Contact']['state_id'] = $this->request->data['BusinessOwner']['state_id'];
                }
                $this->request->data['Contact']['user_id'] = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
                $this->request->data['Contact'] = array_map('trim', $this->request->data['Contact']);
                if ($this->Contact->validates()) {
                    $this->Contact->id = $contactId;
                    if(!empty($this->request->data['Contact']['job_title'])) {
                        $this->request->data['Contact']['job_title'] = htmlentities($this->request->data['Contact']['job_title']);
                    } 
                    if ($this->Contact->save($this->request->data['Contact'])) {
                        $this->Session->setFlash('Contact has been updated successfully.', 'Front/flash_good');
                        $this->redirect(array('controller' => 'contacts', 'action' => 'contactList'));
                    } else {
                        $this->request->data = $this->request->data;
                    }
                } else {
                    foreach ($this->Contact->validationErrors as $key => $value){
                        $err[] = $value[0];
                    }
                    $this->Session->setFlash($err[0], 'Front/flash_bad');
                    $this->request->data = $this->request->data;
                }
            }
        } else {
            $this->Session->setFlash(__('This contact does not exist.'), 'Front/flash_bad');
            $this->redirect(array('controller' => 'contacts', 'action' => 'contactList'));
        }       
    }
    
    /**
     * Function used to Invite partners
     * @param form data posted
     * @author Rohan Julka
     */
    public function invitePartners()
    {
        $userId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
        $userData = $this->User->find('first',array('conditions'=>array('User.id'=>$userId)));
        $userName = $userData['BusinessOwners']['fname'].' '.$userData['BusinessOwners']['lname'];
        $this->set('userName',$userName);
        if ( $this->request->is('post') ) {
            $dataToInsert = array();            
            $senderFullName = ucfirst($userData['BusinessOwners']['fname']).' '.ucfirst($userData['BusinessOwners']['lname']);
            $senderMail = $userData['User']['user_email'];
            $formatted = array();
            $groupId = NULL;
            if ( $userData['Groups']['id'] != NULL ) {
                $groupId = $this->Encryption->decode($userData['Groups']['id']);
            }
            $i=0;
            foreach ( $this->request->data['InvitePartner']['email_id'] as $address ) {
                $email_id = $this->request->data['InvitePartner']['email_id'][$i];
                $formatted[$email_id] = $this->request->data['InvitePartner']['name'][$i];
                $i++;
            }            
            $sendTo = array_unique($formatted);            
            foreach ( $sendTo as $email=>$name ) {
                //Insert data into DB
                $conditions = array(
                    'InvitePartner.inviter_userid' => $userId,
                    'InvitePartner.invitee_email'=> $email
                );
                $data = $this->InvitePartner->find('first',array('conditions'=>$conditions));
                $inviteHash = $this->Encryption->encode(date("Y-m-d H:i:s"));
                if ( !empty($data) ){
                    $this->InvitePartner->id = $this->Encryption->decode($data['InvitePartner']['id']);
                    $dataToInsert = array();
                    $this->InvitePartner->saveField('invite_hash',$inviteHash);
                } else {
                    $this->InvitePartner->create();
                    $dataToInsert = array('invitee_email'=> $email,
                        'inviter_userid' => $userId,
                        'inviter_groupid' =>$groupId,
                        'invitee_name' => $name,
                        'status' => 'pending',
                        'invite_hash' => $inviteHash);
                    $this->InvitePartner->save($dataToInsert);
                }
                //Send Email
                $insertedRecord = $this->InvitePartner->id;
                $emailLib = new Email();
                $to = $email;
                $subject = 'FoxHopr: You\'ve got a Partner Invite';
                $template = 'invite_partners';
                $format = "both";
                $msgBody = $this->request->data['InvitePartner']['message_body'];
                $signupHash = $insertedRecord.';'.$inviteHash;
                $signupHash = $this->Encryption->encode($signupHash);                   
                $signupUrl = Configure::read('SITE_URL')."users/signUp/referral/".$signupHash;
                $params = array('name' => $senderFullName,
                    'email' => $senderMail,
                    'message_body'=>$msgBody,
                    'referral_link'=>$signupUrl);
                $emailLib->sendEmail($to,$subject,$params,$template,$format);
            }
            $this->Session->setFlash('Invitation has been sent to the partners successfully','Front/flash_good');
            $this->redirect(array('controller'=>'contacts','action'=>'partnersList'));
        }
    }
    
    /**
     *Function to get the contact list
     *@author Rohan Julka
     */
    public function partnersList()
    {
        $this->layout = 'front';
        $this->set('titleForLayout','Foxhopr : Partners');
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
        $condition1 = array('InvitePartner.inviter_userid' => $userId);
        $fields = array('InvitePartner.id','InvitePartner.invitee_email','InvitePartner.invitee_name', 'InvitePartner.created', 'InvitePartner.status', 'InvitePartner.referral_amount');
        if ($search != '') {
            $condition2 ['OR']= array("InvitePartner.invitee_email LIKE" => "%" . trim($search) . "%",
                    "InvitePartner.invitee_name LIKE" => "%" . trim($search) . "%",
                    "InvitePartner.status LIKE" => "%" . trim($search) . "%",);
        } else {
            $condition2 = array();
        }
        $condition = array_merge($condition1, $condition2);
        $this->Paginator->settings = array(
                'conditions' => $condition,
                'fields' => $fields,
                'order' => $order,
                'limit' =>$perpage,
                'recursive'=>-1
        );
        $partnersList = $this->Paginator->paginate('InvitePartner');
        if (isset($search) && !empty($search)) {
            $noDataMsg = "No result found";
            $error = (strpos($search, '%') !== false) ? 1 : 0;
            if ($error == 0) {
                $error = (strpos($search, '_') !== false) ? 1 : 0;
            }
            if ($error == 1) {
                $partnersList = '';
            }
            $this->set('noDataMsg', $noDataMsg);
        }
        $this->set(compact('partnersList', 'search'));
        if ($this->request->is('ajax')) {
            $this->layout = false;
            $noDataMsg = "No results found";
            $this->set('perpage', $perpage);
            $this->set('search', $search);
            $this->set('noDataMsg', $noDataMsg);
            $sessionCheck = $this->Session->read('sessionDelMsg');
            if (isset($sessionCheck) && !empty($sessionCheck)) {
                $noDataMsg = "No record found";
                $this->Session->delete('sessionDelMsg');
                $this->set('noDataMsg', $noDataMsg);
            }
            $this->render('partners_list_ajax');
        }
    }

    /**
     * Action for export list of contact
     * @Jitendra sharma
     */
    function downloadContactList()
    {
    	$this->layout = "ajax";
    	$this->autoRender = false;
    	$filepath = WWW_ROOT . 'files' . DS . 'Contact_exported_' . date('d-m-Y-H:i:s') . '.csv';
    	// fields to be show in exported csv
    	$fields = array('Contact.*','Country.country_name','State.state_subdivision_name');
    	 
    	// condition array
    	$userId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
    	$condition = array('Contact.user_id' => $userId);
    	 
    	// fetch result array
    	$data = $this->Contact->find('all', array(/* 'fields' => $fields, */ 'conditions' => $condition, 'order'=>'created DESC'));
    	//pr($data);die; 
    	if (count($data) > 0) {
    		$data = $this->formatCsvData($data);
    		$result = $this->Csv->export($filepath, $data);
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
    		$this->Session->setFlash(__('No contact(s) to download.'), 'Front/flash_bad');
    		$this->redirect(array('controller' => 'contacts', 'action' => 'contactList'));
    	}
    }
    
    /**
     * Action for format csv data
     * @Jitendra sharma
     */
    public function formatCsvData($data=array()){
    	foreach ($data as $key => $model){
    		$formatData[$key]['Contact']['Contact Name'] 	= $model['Contact']['first_name']." ".$model['Contact']['last_name'];
    		$formatData[$key]['Contact']['Profession'] 		= $model['Contact']['job_title'];
    		$formatData[$key]['Contact']['Company'] 		= $model['Contact']['company'];    		
    		$formatData[$key]['Contact']['Country'] 		= $model['Country']['country_name'];
    		$formatData[$key]['Contact']['State'] 			= $model['State']['state_subdivision_name'];
    		$formatData[$key]['Contact']['Address'] 		= $model['Contact']['address'];
    		$formatData[$key]['Contact']['City'] 			= $model['Contact']['city'];
    		$formatData[$key]['Contact']['Zip Code'] 		= $model['Contact']['zip'];
    		$formatData[$key]['Contact']['Office Phone'] 	= $model['Contact']['office_phone'];
    		$formatData[$key]['Contact']['Mobile'] 			= $model['Contact']['mobile'];
    		$formatData[$key]['Contact']['Email Address'] 	= $model['Contact']['email'];
    		$formatData[$key]['Contact']['Website'] 		= $model['Contact']['website'];
    		$formatData[$key]['Contact']['Added On'] 		= date("d/M/Y",strtotime($model['Contact']['created']));
    	}
    	return $formatData;
    }

    /*
     *Web service to get the Contact List
     *@author Priti Kabra
     */
    public function api_getContactList()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        if (!empty($this->jsonDecodedRequestedData->search_filter)) {
            $this->jsonDecodedRequestedData->search_filter = (strpos($this->jsonDecodedRequestedData->search_filter, '%') !== false) ? str_replace('%', '\%', $this->jsonDecodedRequestedData->search_filter) : $this->jsonDecodedRequestedData->search_filter;
            $this->jsonDecodedRequestedData->search_filter = (strpos($this->jsonDecodedRequestedData->search_filter, '_') !== false) ? str_replace('_', '\_', $this->jsonDecodedRequestedData->search_filter) : $this->jsonDecodedRequestedData->search_filter;
        }
        if ($error == 0) {
            $contactFilter = array();
            $sortData = '';
            $userId = $this->loggedInUserId;
            if (!empty($this->jsonDecodedRequestedData->search_filter)) {
                $contactFilter['OR'] = array(
                    "Contact.first_name LIKE" => "%" . trim($this->jsonDecodedRequestedData->search_filter) . "%",
                    "Contact.last_name LIKE" => "%" . trim($this->jsonDecodedRequestedData->search_filter) . "%",
                    "CONCAT(Contact.first_name ,' ',Contact.last_name) LIKE" => "%" . trim($this->jsonDecodedRequestedData->search_filter) . "%",
                    "Contact.job_title LIKE" => "%" . trim($this->jsonDecodedRequestedData->search_filter) . "%",
                    "Contact.email LIKE" => "%" . trim($this->jsonDecodedRequestedData->search_filter) . "%"
                );
            }
            /** Fields array to be fetched from the database */
            $fields = array("Contact.id", "Contact.first_name", "Contact.last_name", "Contact.job_title", "Contact.email", "Contact.created");
            /** Sort data according to the sort filter */
            if (!empty($this->jsonDecodedRequestedData->sort_data) && !empty($this->jsonDecodedRequestedData->sort_direction)) {
                $sortData = "Contact.".$this->jsonDecodedRequestedData->sort_data." ".$this->jsonDecodedRequestedData->sort_direction;
            } else {
                $sortData = "Contact.created DESC";
            }
            $conditionsRequired = array('Contact.user_id' => $this->loggedInUserId);
            $conditions = array_merge($conditionsRequired, $contactFilter);
            $contactList = $this->Contact->find('all',
                                                array('conditions' => $conditions,
                                                      'fields' => $fields,
                                                      'order' => $sortData,
                                                      'recursive'=>1,
                                                      'limit'=>$this->jsonDecodedRequestedData->record_per_page,
                                                      'page' => $this->jsonDecodedRequestedData->page_no
                                                )
                                              );
            $totalContacts = $this->Contact->find('count',
                                                array('conditions' => $conditions,
                                                      'fields' => $fields,
                                                      'order' => "'Contact.created DESC'",
                                                      'recursive'=>1
                                                )
                                              );
            foreach ($contactList as $contacts) {
                $list[] = $contacts['Contact'];
            }
            if (!empty($contactList)) {
                $this->set(array(
                    'code' => Configure::read('RESPONSE_SUCCESS'),
                    'result' => $list,
                    'message' => 'Contact List.',
                    'page_no' => $this->jsonDecodedRequestedData->page_no,
                    'totalContacts' => $totalContacts,
                    '_serialize' => array('code', 'result', 'message', 'page_no', 'totalContacts')
                ));
            } else {
                $this->errorMessageApi('');
            }
        } else {
            $this->errorMessageApi($errMsg);
        }
    }

    /*
     *Web service to add the Contact
     *@author Priti Kabra
     */
    public function api_addContact()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        if ($error == 0) {
            $this->jsonDecodedRequestedData->user_id = $this->loggedInUserId;
            $conditions = array('Contact.user_id' => $this->loggedInUserId, 'Contact.email' => $this->jsonDecodedRequestedData->email);
			$var = $this->Contact->find('first', array('conditions' => $conditions));
			if (empty($var)) {
		        $this->Contact->create();
		        if ($this->Contact->save($this->jsonDecodedRequestedData)) {
		            $this->set(array(
		                'code' => Configure::read('RESPONSE_SUCCESS'),
		                'message' => 'Contact has been added successfully.',
		                '_serialize' => array('code', 'message')
		            ));
		        } else {
		            foreach ($this->Contact->validationErrors as $key => $value){
		                $err = $value[0];
		            }
		            if (!empty($err)) {
		                $this->set(array(
		                    'code' => Configure::read('RESPONSE_ERROR'),
		                    'message' => $err,
		                    '_serialize' => array('code', 'message')
		                ));
		            }
		        }
			} else {
				$this->errorMessageApi('Contact with same email already exists');
			}
        } else {
            $this->errorMessageApi($errMsg);
        }
    }

    /*
     *Web service to delete the Contact
     *@author Priti Kabra
     */
    public function api_deleteContact()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        $success = 0;
        if (!empty($this->jsonDecodedRequestedData->deleteId)) {
            $deleteID = explode(",", urldecode($this->jsonDecodedRequestedData->deleteId));
            $error = $this->Contact->checkContactExist($deleteID);
            if ($error == 0) {
                foreach ($deleteID as $deleteContactId) {
                    if (!empty($deleteContactId)) {
                        $deleteData['id'] = $this->Encryption->decode($deleteContactId);
                        if ($this->Contact->delete($deleteData['id'])) {
                            $success = 1;
                        }
                    }

                }
                if ($success == 1) {
                    $this->set(array(
                        'code' => Configure::read('RESPONSE_SUCCESS'),
                        'message' => 'Contact(s) has been permanently deleted successfully',
                        '_serialize' => array('code', 'message')
                    ));
                } else {
                    $this->errorMessageApi('Please try again.');
                }
            } else {
                $errMsg = isset($errMsg) ? $errMsg : "Contact does not exist.";
                $this->errorMessageApi($errMsg);
            }
        }
    }

    /*
     *Web service to get the Contact details
     *@author Priti Kabra
     */
    public function api_contactDetail()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        $success = 0;
        if (!empty($this->jsonDecodedRequestedData->contactId) && empty($error)) {
            $contactId = $this->Encryption->decode($this->jsonDecodedRequestedData->contactId);
            $contactData = $this->Contact->find('first', array('conditions' => array('Contact.id' => $contactId)));
            if (!empty($contactData)) {
                $contactData['Contact']['country_name'] = $contactData['Country']['country_name'];
                $contactData['Contact']['state_name'] = $contactData['State']['state_subdivision_name'];
                $this->set(array(
                    'code' => Configure::read('RESPONSE_SUCCESS'),
                    'result' => $contactData['Contact'],
                    'message' => 'Contact Detail',
                    '_serialize' => array('code', 'message', 'result')
                ));
            } else {
                $this->errorMessageApi('Please try again.');
            }
        } else {
            $errMsg = isset($errMsg) ? $errMsg : "Contact does not exist.";
            $this->errorMessageApi($errMsg);
        }
    }

    /*
     *Web service to edit the Contact
     *@author Priti Kabra
     */
    public function api_editContact()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        if ($error == 0) {
            $this->jsonDecodedRequestedData->user_id = $this->loggedInUserId;
            if ($this->Contact->exists($this->Encryption->decode($this->jsonDecodedRequestedData->contactId))) {
				$conditions = array('Contact.user_id' => $this->loggedInUserId, 'Contact.email' => $this->jsonDecodedRequestedData->email, 'Contact.id !=' => $this->Encryption->decode($this->jsonDecodedRequestedData->contactId));
				$var = $this->Contact->find('first', array('conditions' => $conditions));
				if (empty($var)) {
		            $this->Contact->id = $this->Encryption->decode($this->jsonDecodedRequestedData->contactId);
		            if ($this->Contact->save($this->jsonDecodedRequestedData)) {
		                $this->set(array(
		                    'code' => Configure::read('RESPONSE_SUCCESS'),
		                    'message' => 'Contact has been updated successfully',
		                    '_serialize' => array('code', 'message')
		                ));
		            } else {
		              //pr($this->Contact->validationErrors); exit;
		                foreach ($this->Contact->validationErrors as $key => $value){
		                    $err = $value[0];
		                }
		                if (!empty($err)) {
		                    $this->errorMessageApi($err);
		                }
		            }
				} else {
					$this->errorMessageApi('Contact with same email already exists');
				}
            } else {
                $this->errorMessageApi('Contact does not exist');
            }
        } else {
            $this->errorMessageApi($errMsg);
        }
    }

    /*
     *Web service to get the Partners List
     *@author Priti Kabra
     */
    public function api_getPartnersList()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        if (!empty($this->jsonDecodedRequestedData->search_filter)) {
            $this->jsonDecodedRequestedData->search_filter = (strpos($this->jsonDecodedRequestedData->search_filter, '%') !== false) ? str_replace('%', '\%', $this->jsonDecodedRequestedData->search_filter) : $this->jsonDecodedRequestedData->search_filter;
            $this->jsonDecodedRequestedData->search_filter = (strpos($this->jsonDecodedRequestedData->search_filter, '_') !== false) ? str_replace('_', '\_', $this->jsonDecodedRequestedData->search_filter) : $this->jsonDecodedRequestedData->search_filter;
        }
        if ($error == 0) {
            $partnerFilter = array();
            $sortData = '';
            $userId = $this->loggedInUserId;
            if (!empty($this->jsonDecodedRequestedData->search_filter)) {
                $partnerFilter['OR'] = array(
                    "InvitePartner.invitee_name LIKE" => "%" . trim($this->jsonDecodedRequestedData->search_filter) . "%",
                    "InvitePartner.invitee_email LIKE" => "%" . trim($this->jsonDecodedRequestedData->search_filter) . "%",
                    "InvitePartner.status LIKE" => "%" . trim($this->jsonDecodedRequestedData->search_filter) . "%"
                );
            }
            /** Fields array to be fetched from the database */
            $fields = array("InvitePartner.id", "InvitePartner.invitee_name", "InvitePartner.invitee_email", "InvitePartner.status", "InvitePartner.referral_amount", "InvitePartner.created");
            /** Sort data according to the sort filter */
            if (!empty($this->jsonDecodedRequestedData->sort_data) && !empty($this->jsonDecodedRequestedData->sort_direction)) {
                $sortData = "InvitePartner.".$this->jsonDecodedRequestedData->sort_data." ".$this->jsonDecodedRequestedData->sort_direction;
            } else {
                $sortData = "InvitePartner.created DESC";
            }
            $conditionsRequired = array('InvitePartner.inviter_userid' => $this->loggedInUserId);
            $conditions = array_merge($conditionsRequired, $partnerFilter);
            $partnerList = $this->InvitePartner->find('all',
                                                array('conditions' => $conditions,
                                                      'fields' => $fields,
                                                      'order' => $sortData,
                                                      'recursive'=>1,
                                                      'limit'=>$this->jsonDecodedRequestedData->record_per_page,
                                                      'page' => $this->jsonDecodedRequestedData->page_no
                                                )
                                              );
            $totalPartners = $this->InvitePartner->find('count',
                                                array('conditions' => $conditions,
                                                      'fields' => $fields,
                                                      'order' => "'InvitePartner.created DESC'",
                                                      'recursive'=> 1
                                                )
                                              );
            foreach ($partnerList as $partners) {
                $list[] = $partners['InvitePartner'];
            }
            if (!empty($partnerList)) {
                $this->set(array(
                    'code' => Configure::read('RESPONSE_SUCCESS'),
                    'result' => $list,
                    'message' => 'Invite Partner List.',
                    'page_no' => $this->jsonDecodedRequestedData->page_no,
                    'totalPartners' => $totalPartners,
                    '_serialize' => array('code', 'result', 'message', 'page_no', 'totalPartners')
                ));
            } else {
                $this->errorMessageApi('');
            }
        } else {
            $this->errorMessageApi($errMsg);
        }
    }

    /**
     * Web service used to Invite partners
     * @author Priti Kabra
     */
    public function api_invitePartners()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        if ($error == 0) {
            //$dataToInsert = array();
            $userId = $this->loggedInUserId;
            $userData = $this->User->find('first', array('conditions' => array('User.id' => $userId)));
            $senderFullName = ucfirst($userData['BusinessOwners']['fname']) . ' ' . ucfirst($userData['BusinessOwners']['lname']);
            $senderMail = $userData['User']['user_email'];
            $formatted = array();
            $groupId = NULL;
            if ($userData['Groups']['id'] != NULL) {
                $groupId = $this->Encryption->decode($userData['Groups']['id']);
            }
            foreach ($this->jsonDecodedRequestedData->data as $address ) {
                $email_id = $address->email;
                $formatted[$email_id] = $address->name;
            }
            $sendTo = $formatted;
            foreach ($sendTo as $email=>$name) {
                $email = strtolower($email);
                //Insert data into DB
                $conditions = array(
                    'InvitePartner.inviter_userid' => $userId,
                    'InvitePartner.invitee_email'=> $email
                );
                $data = $this->InvitePartner->find('first', array('conditions' => $conditions));
                $inviteHash = $this->Encryption->encode(date("Y-m-d H:i:s"));
                if (!empty($data)){
                    $this->InvitePartner->id = $this->Encryption->decode($data['InvitePartner']['id']);
                    $dataToInsert = array();
                    $this->InvitePartner->saveField('invite_hash',$inviteHash);
                } else {
                    $this->InvitePartner->create();
                    $dataToInsert = array('invitee_email'=> $email,
                        'inviter_userid' => $userId,
                        'inviter_groupid' =>$groupId,
                        'invitee_name' => $name,
                        'status' => 'pending',
                        'invite_hash' => $inviteHash);
                    $this->InvitePartner->save($dataToInsert);
                }
                //Send Email
                $insertedRecord = $this->InvitePartner->id;
                $emailLib = new Email();
                $to = $email;
                $subject = 'FoxHopr: You\'ve got a Partner Invite';
                $template = 'invite_partners';
                $format = "both";
                $msgBody = $this->jsonDecodedRequestedData->message;
                $signupHash = $insertedRecord.';'.$inviteHash;
                $signupHash = $this->Encryption->encode($signupHash);                   
                $signupUrl = Configure::read('SITE_URL')."users/signUp/referral/".$signupHash;
                $params = array('name' => $senderFullName,
                    'email' => $senderMail,
                    'message_body' => $msgBody,
                    'referral_link' => $signupUrl);
                $emailLib->sendEmail($to,$subject,$params,$template,$format);
            }
            $this->set(array(
                'code' => Configure::read('RESPONSE_SUCCESS'),
                'message' => 'Invitation has been sent to the partner(s) successfully',
                '_serialize' => array('code', 'message')
            ));
        } else {
            $this->errorMessageApi($errMsg);
        }
    }
    
    /**
     * Function used to Import Contacts
     * @author Rohan Julka
     */
    public function importContacts()
    {
        $this->layout = 'front';
        $userId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
        $userData = $this->User->find('first', array('conditions' => array('User.id' => $userId)));
        $findConditions = array('Contact.user_id'=>$userId,'Contact.user_groupid'=>$userData['BusinessOwners']['group_id'],'Contact.source'=>'gmail');
        $contactCount = $this->Contact->find('count',array('conditions'=>$findConditions));
        $this->set('contactCount',$contactCount);
        $this->includePageJs = array('admin_validation');
        if ($this->request->is('post')) {
            Configure::write('debug', 0);
            $InvalidHeaderMsg = "Contacts cannot be imported due to invalid header(s)";
            if (!empty($this->request->data['Contact']['csv'])) {
                $file = $this->request->data['Contact']['csv'];
                if ($file['type'] != 'text/csv' && $file['type'] != 'application/vnd.ms-excel' && $file['type'] != 'application/octet-stream') {
                    //$this->Profession->validationErrors['email'] = 'Please upload Valid CSV file';
                    $this->Session->setFlash(__('Please upload Valid CSV file'), 'Front/flash_bad');
                } else {
                    // get professions into array
                    $contact = $this->Csv->import($file['tmp_name']);
                    //array_shift($contact);
                    $errormsg = "";
                    $contact = array_map("unserialize", array_unique(array_map('strtolower', array_map("serialize", $contact))));
                    if (!empty($contact)) {
                        
                        $dataToSave = array();
                        $i=0;
                        $errormsg = '';
                        if(isset($contact[0]['user']['first name']) && isset($contact[0]['user']['last name']) && isset($contact[0]['user']['e-mail address'])) {
                            foreach ($contact as $key => $name) {
                                
                                $dataToSave[$i]['Contact']['user_id'] = $userId;
                                $dataToSave[$i]['Contact']['source'] = 'csv';
                                $dataToSave[$i]['Contact']['user_groupid'] = $userData['BusinessOwners']['group_id'];
                                if(isset($contact[$key]['user']['first name']) ) {
                                    if($contact[$key]['user']['first name'] != '' && (preg_match('/^[a-z\d_ .-]{1,20}$/i', $contact[$key]['user']['first name']))) {
                                        $dataToSave[$i]['Contact']['first_name'] = $contact[$key]['user']['first name'];
                                    } else {
                                        unset($dataToSave[$i]);
                                        continue;
                                    }
                                } else {                            
                                    $errormsg = $InvalidHeaderMsg;
                                }
                                
                                if(isset($contact[$key]['user']['last name'])) {
                                    if($contact[$key]['user']['last name'] != '' &&  (preg_match('/^[a-z\d_ .-]{1,20}$/i', $contact[$key]['user']['last name']))) {
                                        $dataToSave[$i]['Contact']['last_name'] = $contact[$key]['user']['last name'];
                                    } else {
                                        unset($dataToSave[$i]);
                                        continue;
                                    }
                                } else { 
                                    $errormsg = $InvalidHeaderMsg;
                                }
                                
                                if(isset($contact[$key]['user']['e-mail address'])) {
                                    $conditions = array('Contact.user_id'=>$userId,'Contact.user_groupid'=>$userData['BusinessOwners']['group_id'],'Contact.email'=>$contact[$key]['user']['e-mail address']);
                                    if(!$this->Contact->hasAny($conditions)) {
                                        if($contact[$key]['user']['e-mail address'] != '' && filter_var($contact[$key]['user']['e-mail address'], FILTER_VALIDATE_EMAIL)) {
                                            $dataToSave[$i]['Contact']['email'] = $contact[$key]['user']['e-mail address'];
                                        } else {                                    
                                            unset($dataToSave[$i]);
                                            continue;
                                        }
                                    } else {
                                        unset($dataToSave[$i]);
                                        continue;
                                    }
                                } else {
                                    $errormsg = $InvalidHeaderMsg;
                                }
                                if(isset($contact[$key]['user']['company']) && $contact[$key]['user']['company'] != '' && (preg_match('/^[a-z\d_ -]{1,35}$/i', $contact[$key]['user']['company'])) ) {
                                    $dataToSave[$i]['Contact']['company'] = $contact[$key]['user']['company'];
                                }
                                if(isset($contact[$key]['user']['job title']) && $contact[$key]['user']['job title'] != '' && strlen($contact[$key]['user']['job title'])<=35 ) {
                                    $dataToSave[$i]['Contact']['job_title'] = $contact[$key]['user']['job title'];
                                }
                                $i++;
                            }
                            if (strlen($errormsg) == 0) {
                                if(!empty($dataToSave)) {
                                    $this->Contact->create();
                                    if ($this->Contact->saveAll($dataToSave)) {
                                        $this->Session->setFlash(count($dataToSave).' Contact(s) has been imported successfully', 'Front/flash_good');
                                    }
                                } else {
                                    $this->Session->setFlash(__('No valid contacts found in csv file.'), 'Front/flash_bad');
                                }
                                $this->redirect(array('controller' => 'contacts', 'action' => 'contactList'));
                            } else {
                                $this->Session->setFlash($errormsg, 'Front/flash_bad');
                            }
                        } else {
                                    $this->Session->setFlash($InvalidHeaderMsg, 'Front/flash_bad');
                        }
                    }else{
                        $this->Session->setFlash(__("You can't upload an empty CSV file"), 'flash_bad');
                    }
                }
            }
        }
        $this->set('includePageJs',$this->includePageJs);
    }
    
    /**
     * Function used to Sync Gmail Contacts
     * @author Rohan Julka
     */
    public function gmailSync($request_type = "ownSite")
    {
        $loggedInUserId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
        $userData = $this->User->userInfoById($loggedInUserId);
        $client_id = Configure::read('GOOGLE_CLIENT_ID');
        $client_secret = Configure::read('GOOGLE_CLIENT_SECRET');
        $redirect_uri = Configure::read('GOOGLE_REDIRECT_URI');
        $max_results = 100;        
        $this->autoRender = false;
        if($request_type == "ownSite") {
            $googleConnect = "https://accounts.google.com/o/oauth2/auth?client_id=".Configure::read('GOOGLE_CLIENT_ID')."&redirect_uri=".Configure::read('GOOGLE_REDIRECT_URI')."&scope=https://www.google.com/m8/feeds/&response_type=code";
            $this->redirect($googleConnect);
        } else {
            $auth_code = $this->request->query["code"];
            $fields=array(
                'code'=>  urlencode($auth_code),
                'client_id'=>  urlencode($client_id),
                'client_secret'=>  urlencode($client_secret),
                'redirect_uri'=>  urlencode($redirect_uri),
                'grant_type'=>  urlencode('authorization_code')
            );
            $post = '';
            foreach($fields as $key=>$value) {
                $post .= $key.'='.$value.'&';
            }
            $post = rtrim($post,'&');
            
            $curl = curl_init();
            curl_setopt($curl,CURLOPT_URL,'https://accounts.google.com/o/oauth2/token');
            curl_setopt($curl,CURLOPT_POST,5);
            curl_setopt($curl,CURLOPT_POSTFIELDS,$post);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,TRUE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
            $result = curl_exec($curl);
            curl_close($curl);
            
            $response =  json_decode($result);
            $accesstoken = $response->access_token;
            $url = 'https://www.google.com/m8/feeds/contacts/default/full?max-results='.$max_results.'&oauth_token='.$accesstoken;
            $xmlresponse =  $this->curl_file_get_contents($url);
            if((strlen(stristr($xmlresponse,'Authorization required'))>0) && (strlen(stristr($xmlresponse,'Error '))>0))
            {
                $this->Session->setFlash('Error Importing contacts','Front/flash_bad');
            } else {              
                $doc = new DOMDocument;
                $doc->recover = true;
                $doc->loadXML($xmlresponse);                
                $xpath = new DOMXPath($doc);
                $xpath->registerNamespace('gd', 'http://schemas.google.com/g/2005');                
                $emails = $xpath->query('//gd:email');  
                unset($this->Contact->validate['job_title']);
                $i=0;
                foreach ( $emails as $email )
                {   $emailId = $email->getAttribute('address');                
                    $fullName = $email->parentNode->getElementsByTagName('title')->item(0)->textContent;
                    if( $emailId!='' && $fullName!='' && (preg_match('/^[a-z\d_ .-]{1,20}$/i', $fullName)) ) {
                        $fullName = explode(' ', $fullName);
                        if(count($fullName)==2) {
                            $fName = $fullName[0];
                            $lName = $fullName[1];                            
                            $conditions = array('Contact.user_id'=>$loggedInUserId,'Contact.user_groupid'=>$userData['BusinessOwners']['group_id'],'Contact.email'=>$emailId);
                            
                            if(!$this->Contact->hasAny($conditions)) {
                               
                                $dataToSave = array('user_id'=>$loggedInUserId,'user_groupid'=>$userData['BusinessOwners']['group_id'],'email'=>$emailId,'first_name'=>$fName,'last_name'=>$lName);
                                if($email->parentNode->getElementsByTagName('orgTitle')->item(0) != NULL) {
                                    $jobTitle = $email->parentNode->getElementsByTagName('orgTitle')->item(0)->textContent;
                                    $dataToSave['job_title'] = $jobTitle;
                                    
                                }
                                $dataToSave['source'] = 'gmail';
                                $this->Contact->create();
                                $i++;
                                $this->Contact->save(array('Contact'=>$dataToSave));
                            }
                        } 
                    }                    
                }
                $this->Session->setFlash($i.' Contact(s) Imported Successfully','Front/flash_good');
            }
            $this->redirect(array('action'=>'addContact'));           
        }
    }
    /**
     * Function for fetching file contents via CURL
     * @author Rohan Julka
     */
    public function curl_file_get_contents($url)
    {
        $curl = curl_init();
        $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
    
        curl_setopt($curl,CURLOPT_URL,$url);	//The URL to fetch. This can also be set when initializing a session with curl_init().
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE);	//TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
        curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,5);	//The number of seconds to wait while trying to connect.
    
        curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);	//The contents of the "User-Agent: " header to be used in a HTTP request.
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);	//To follow any "Location: " header that the server sends as part of the HTTP header.
        curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);	//To automatically set the Referer: field in requests where it follows a Location: redirect.
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);	//The maximum number of seconds to allow cURL functions to execute.
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);	//To stop cURL from verifying the peer's certificate.
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    
        $contents = curl_exec($curl);
        curl_close($curl);
        return $contents;
    }
    /**
     * Function used to Remove Synced Gmail Contacts
     * @author Rohan Julka
     */
    public function gmailRemove()
    {
       $loggedInUserId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
       $userData = $this->User->userInfoById($loggedInUserId);
       $deleteConditions = array('Contact.user_id' => $loggedInUserId,'Contact.user_groupid'=>$userData['BusinessOwners']['group_id'],'Contact.source'=>'gmail');
       if($this->Contact->deleteAll($deleteConditions, false)) {
           $this->Session->setFlash('Contsct(s) removed successfully');
           $this->redirect(array('action'=>'contactList'));
       }
        
    }
    
    /**
     * Function used to add contact from referrals received
     * param $referralId referral id
     * @author Priti Kabra
     */
    public function addReferralContact($referralId = null)
    {
        if (isset($referralId)) {
            $userId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
            $group = $this->BusinessOwner->find('first', array('conditions' => array('BusinessOwner.user_id' => $userId), 'fields' => array('BusinessOwner.group_id')));
            $referralData = $this->ReceivedReferral->find('first', array(
                                                            'conditions' => array(
                                                                'ReceivedReferral.id' => $this->Encryption->decode($referralId),
                                                                'ReceivedReferral.is_archive' => 0
                                                            ),
                                                            'recursive' => -1
                                                            )
                                                        );
            $referralData['ReceivedReferral']['user_id'] = $userId;
            $referralData['ReceivedReferral']['user_groupid'] = $group['BusinessOwner']['group_id'];
            unset($referralData['ReceivedReferral']['created']);
            unset($referralData['ReceivedReferral']['modified']);
            if (!empty($referralData)) {
                if ($this->Contact->validates()) {
                    $contactData = $this->Contact->find('first', array('conditions' => array('Contact.email' => $referralData['ReceivedReferral']['email'], 'Contact.user_id' => $userId), 'recursive' => -1));
                    $contactId = !empty($contactData) ? $this->Encryption->decode($contactData['Contact']['id']) : '';
                    if (!empty($contactData) && !($this->request->is('post'))) {
                        $this->autoRender = false;
                        $this->Session->write('isReferralExist', 'yes');
                        $this->Session->write('referralContactId', $referralId);
                        $this->redirect(array('controller' => 'referrals', 'action' => 'received'));
                    } else {
                        if ($this->request->is('post')) {
                            if (!empty($contactData)) {
                                $this->Contact->id = $contactId;
                                unset($referralData['ReceivedReferral']['id']);
                                $msg = "Contact has been updated successfully";
                            } else {
                                 $this->Contact->create();
                                $msg = "Referral successfully added to the contacts";
                            }
                        } else {
                            $this->Contact->create();
                            $msg = "Referral successfully added to the contacts";
                        }
                        if ($this->Contact->save($referralData['ReceivedReferral'])) {
                            $this->Session->setFlash($msg, 'Front/flash_good');
                            $this->redirect(array('controller' => 'referrals', 'action' => 'received'));
                        } else {
                            $this->Session->setFlash('Please try again.', 'Front/flash_bad');
                            $this->redirect(array('controller' => 'referrals', 'action' => 'received'));
                        }
                    }
                }  else {
                    $validationErrors=$this->compileErrors('Contact');
                    if($validationErrors!=NULL) {
                        $this->Session->setFlash($validationErrors, 'Front/flash_good');
                        $this->redirect(array('controller' => 'referrals', 'action' => 'received'));
                    }
                }
            } else {
                $this->Session->setFlash('Referral does not exist', 'Front/flash_bad');
                $this->redirect(array('controller' => 'referrals', 'action' => 'received'));
            }
        }
    }

    /**
    * check contact exists
    * @params string $contactId contact id to edit contact
    * @author Priti Kabra
    */
    public function checkContactExist($contactId = null) 
    {
		$userId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
		$this->autoRender = false;
		$contact = $this->request->data['Contact']['email'];
        if (!empty($contactId)) {
            $conditions = array('Contact.user_id' => $userId, 'Contact.email' => $contact, 'Contact.id !=' => $this->Encryption->decode($contactId));
        } else {
            $conditions = array('Contact.user_id' => $userId, 'Contact.email' => $contact);
        }
		$var  = $this->Contact->find('first', array('conditions' => $conditions));
		if ($var) {
			echo 'false';
		} else {
			echo 'true';
		}
	}
}