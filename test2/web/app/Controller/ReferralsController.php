<?php 
App::uses('Email', 'Lib');
class ReferralsController extends AppController 
{
	public $components = array('Email', 'Common','Groups', 'Profession', 'Timezone','Paginator','Csv.Csv','Encryption');
	public $helpers = array('Functions');
	public $uses = array('User','Group','Contact','Referral','BusinessOwner','SendReferral','ReceivedReferral','ReferralComment','Message','MessageRecipient','LiveFeed','ReferralStat');
	public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('titleForLayout', 'FoxHopr: Referrals');
    }
    /**
    * Function used to get list of sent referrals
    * @author Gaurav
    */
	public function sent()
	{
        $this->layout = 'front';
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
        $condition1 = array('SendReferral.from_user_id' => $userId,'SendReferral.is_archive' => 0);
         if ($search != '') {
              $condition2['OR'] = array(
                  "SendReferral.first_name LIKE" => "%" . trim($search) . "%",
                  "SendReferral.last_name LIKE" => "%" . trim($search). "%",
                  "CONCAT(SendReferral.first_name ,' ',SendReferral.last_name) LIKE" => "%" . trim($search) . "%",
                  "BusinessOwners.fname LIKE" => "%" . trim($search) . "%",
                  "BusinessOwners.lname LIKE" => "%" . trim($search) . "%",
                  "CONCAT(BusinessOwners.fname ,' ',BusinessOwners.lname) LIKE" => "%" . trim($search) . "%",
              );
        } else {
          $condition2 = array();
        }
        $condition = array_merge($condition1, $condition2);
        $this->Paginator->settings = array(
                    'conditions' => $condition,
                    'order' => $order,
                    'limit' =>$perpage
                );
        $resultData = $this->Paginator->paginate('SendReferral');
        $this->set('referralData', $resultData);
        $this->set('search', $search);
        
        if ($this->request->is('ajax')) {
            $this->layout = false;
            $this->set('perpage', $perpage);
            $this->set('search', $search);
            $this->render('sent_ajax');
        }      
	}

    /**
    * Function used for sending referrals
    * @author Gaurav
    */
    public function sendReferrals($contactListId = null)
    {
        $this->loadModel('PrevGroupRecord');
        $this->validationJs = array('send.referral.validation');
        $userId = $this->Session->read('Auth.Front.id');
        $this->User->bindModel(
          array('hasMany' => array(
                  'Contact' => array(
                      'className' => 'Contact',
                      'foreignKey' => 'user_id',
                      'order' => 'Contact.first_name ASC'
                  )
              )
          )
        );
        $userInfo = $this->User->userInfoById($this->Encryption->decode($userId));
        $this->set('contactData', $userInfo['Contact']);
        $this->layout = 'front';
        $titleForLayout = 'Send Referral';
        $countryList  = $this->Common->getAllCountries();
        $groupMembersList     = $this->BusinessOwner->getMyGroupMemberList($this->Encryption->decode($userInfo['Groups']['id']), $this->Encryption->decode($userId));
        $prevGroupMembersList     = $this->PrevGroupRecord->getMyPreviousGroupMemberList($this->Encryption->decode($userId));
        if(!empty($prevGroupMembersList) && !empty($groupMembersList)) {
            $groupMembersList = array(                        
                        'Current' => $groupMembersList,
                        'Previous' => $prevGroupMembersList,
                        );
        } else if(!empty($prevGroupMembersList)){
            $groupMembersList = array(
                        'Previous' => $prevGroupMembersList,
                        );
        }

        if($this->request->is('post') && !$this->request->is('ajax')) {
            if(!isset($this->request->data['Contact']['country_id']) && !empty($this->request->data['country_id2'])) {
              $this->request->data['Contact']['country_id'] =  $this->request->data['country_id2'];
            }
            if(!isset($this->request->data['BusinessOwner']['state_id']) && !empty($this->request->data['state_id2'])) {
              $this->request->data['BusinessOwner']['state_id'] =  $this->request->data['state_id2'];
            }
            $this->request->data['Contact']['user_id'] = $this->Encryption->decode($userId);
            if (isset($this->request->data['BusinessOwner']['state_id'])) {
                $this->request->data['Contact']['state_id'] = $this->request->data['BusinessOwner']['state_id'];
            }
            if (!empty($this->request->data['Contact']['contact'])) {
                $contactUpdate = array_map('trim', $this->request->data['Contact']);
                $contactCheck = $this->checkContact($this->request->data['Contact']['email'], $this->request->data['Contact']['contact']);
                if ($contactCheck === true) {
                    $this->Contact->id = $this->Encryption->decode($this->request->data['Contact']['contact']);
                    $this->Contact->save($contactUpdate);
                }
            } else {
                $contactSave = array_map('trim', $this->request->data['Contact']);
                $contactCheck = $this->checkContact($this->request->data['Contact']['email']);
                if ($contactCheck === true) {
                    $this->Contact->create();
                    $this->Contact->save($contactSave);
                }
            }
            $i = 0;
            if (isset($this->request->data['multiselect']) && !empty($this->request->data['multiselect'])) {
                $this->request->data['multiselect'] = array_unique(str_replace('prev_', '', $this->request->data['multiselect']));
                $messageTo=array();
                $refTo = implode(',', $this->request->data['multiselect']);
                $refStatData = $this->ReferralStat->find('first',array('conditions'=>array('ReferralStat.sent_from_id'=>$this->Encryption->decode($userId),'ReferralStat.sent_to'=>$refTo,'email_id'=>$this->request->data['Contact']['email'] )));
                if(empty($refStatData)) {
                    
                    $this->ReferralStat->create();
                	$statData = array('ReferralStat'=>array('sent_from_id'=>$this->Encryption->decode($userId), 'group_id' => $this->Encryption->decode($userInfo['Groups']['id']),'sent_to'=>$refTo,'email_id'=>$this->request->data['Contact']['email']));
                	$this->ReferralStat->save($statData);
                }
                    foreach ($this->request->data['multiselect'] as $sendTo) {
                        $sendUser = $this->User->userInfoById($sendTo);
                        $messageTo[]=$sendUser['BusinessOwners']['fname'].' '.$sendUser['BusinessOwners']['lname'];
                        $this->request->data['Referral'] = $this->request->data['Contact'];
                        $this->request->data['Referral']['to_user_id'] = $sendTo;
                        $this->request->data['Referral']['from_user_id'] = $this->Encryption->decode($userId);
                        $this->request->data['Referral']['group_id'] = $this->Encryption->decode($sendUser['Groups']['id']);
                        $this->request->data['Referral']['message'] = $this->request->data['Contact']['note'];
                        $fileName = $this->Session->check('referralsFiles') == true ? $this->Session->read('referralsFiles') : '';
                        $this->request->data['Referral']['files'] = $fileName;
                        $notificationEnable = explode(',',$sendUser['BusinessOwners']['notifications_enabled']);
                        if(in_array('receiveReferral', $notificationEnable)) {
                            $emailLib = new Email();
                            $to = $sendUser['User']['user_email'];
                            $subject = 'Your Referral';
                            $template = 'send_referral_mail';
                            $format = "both";
                            $user_name = $sendUser['BusinessOwners']['fname']." ".$sendUser['BusinessOwners']['lname'];
                            $referral_name = $this->request->data['Contact']['first_name']." ".$this->request->data['Contact']['last_name'];
                            $sender_name = $userInfo['BusinessOwners']['fname']." ".$userInfo['BusinessOwners']['lname'];
                            $params = array('name' => $user_name, 'referral_name' => $referral_name, 'sender_name' => $sender_name);
                            $success = $emailLib->sendEmail($to,$subject,$params,$template,$format);
                        }
                        $i++;
                        $this->SendReferral->create();
                        $this->request->data['Referral'] = array_map('trim', $this->request->data['Referral']);
                        if ($this->SendReferral->save($this->request->data['Referral'])) {
                            $this->ReceivedReferral->create();
                            $this->request->data['Referral']['id'] = $this->SendReferral->getLastInsertID();
                            if ($this->ReceivedReferral->save($this->request->data['Referral'])) {
                              if($fileName != '') {
                                $tempFiles = explode(',',$fileName);
                                foreach($tempFiles as $temp) {
                                  $filepathTempPath = WWW_ROOT . 'files/referrals/temp/'. $temp;
                                  $filepathMovePath = WWW_ROOT . 'files/referrals/'. $temp;
                                  if(file_exists($filepathTempPath))
                                  {
                                    rename($filepathTempPath, $filepathMovePath);
                                  }
                                }
                              }
                              // sent message live feed
                              $this->LiveFeed->create();
                              $liveFeedData['LiveFeed']['to_user_id'] 	= $sendTo;
                              $liveFeedData['LiveFeed']['from_user_id'] = $this->Encryption->decode($userId);
                              $liveFeedData['LiveFeed']['group_id']   = $this->Encryption->decode($userInfo['Groups']['id']);
                              $liveFeedData['LiveFeed']['feed_type'] 	= "referral";
                              $this->LiveFeed->save($liveFeedData);
                              
                              $this->Session->setFlash(__('Referral has been sent successfully.'), 'Front/flash_good');
                            }
                            //send Pn
                            if ($sendUser['User']['device_type'] == "ios") {
                                $this->Common->iospushnotification($sendUser['User']['device_token'], 'You have received a new referral', 'service_test', 'You have received a new referral');
                            } elseif ($sendUser['User']['device_type'] == "android") {
                                $this->Common->androidpushnotification($sendUser['User']['device_token'], 'You have received a new referral', 'service_test', 'You have received a new referral');
                            }
                        } else {
                            $err[] = 'Please Try Again.';
                        }
                    }
                    //Send TwitterPost.
                    $socialConfig=explode(',',$userInfo['BusinessOwners']['notifications_enabled']);
                    $recievers=implode(', ', $messageTo);
                    // Send Twitter Update
                    if(in_array('tweetReferralSend', $socialConfig) && $userInfo['User']['twitter_connected'] == 1) {                    
                        $statusMessage="Just sent a referral to $recievers via @Foxhopr";
                        $this->postToTwitter($userInfo, $statusMessage);
                    }
                    // Send Facebook Update
                    if(in_array('fbReferralSend', $socialConfig) && $userInfo['User']['fb_connected'] == 1) {
                        $statusMessage="Just sent a referral to $recievers via http://foxhopr.com";
                        $this->postToFacebook($userInfo, $statusMessage);
                    }
                    // Send Linkedin Update
                    if(in_array('linkedinReferralSend', $socialConfig) && $userInfo['User']['linkedin_connected'] == 1) {
                        $statusMessage="Just sent a referral to $recievers via http://foxhopr.com";
                        $this->postToLinkedin($userInfo, $statusMessage);
                    }
                    
            } else {
                $err[] = 'Please select a member.';
                $country_id = $this->request->data['Contact']['country_id'];
                $getStateList  = $this->Common->getStatesForCountry($country_id);
                $this->set('stateList', $getStateList);
            }
            if (empty($err)) {
                $this->redirect(array('controller' => 'referrals', 'action' => 'sent'));
            } else {
                $this->Session->setFlash(__($err[0]), 'Front/flash_bad');
                $this->request->data = $this->request->data;
                $this->set('selected',$this->request->data['multiselect']);
            }
            $country_id = $this->request->data['Contact']['country_id'];
            $getStateList  = $this->Common->getStatesForCountry($country_id);
            $this->set('stateList', $getStateList);
            $this->set('selected',$this->request->data['multiselect']);
        }
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            if(!isset($this->request->data['action']) && !empty($_FILES)) {
                $files = $_FILES;          
                $fileUploadName = $this->Common->getFileName($files['files']['name']);
                $filepath = WWW_ROOT . 'files/referrals/temp/' . $fileUploadName;
                move_uploaded_file($files['files']['tmp_name'], $filepath);
                if($this->Session->check('referralsFiles') == true) {
                    $referralsFiles = $this->Session->read('referralsFiles').','.$fileUploadName;
                    $this->Session->write('referralsFiles',$referralsFiles);
                } else {
                    $this->Session->write('referralsFiles',$fileUploadName);
                }
                $response = array('filename'=>$fileUploadName,'sessionData'=>$this->Session->read('referralsFiles'));
                return json_encode($response);
            } else {
                if (isset($this->request->data['filename']) && $this->request->data['filename'] != '') {
                    $filepath = WWW_ROOT . 'files/referrals/temp/' . $this->request->data['filename'];
                    if(file_exists($filepath))
                    {
                        $parts = explode(',', $this->Session->read('referralsFiles'));
                        while(($i = array_search($this->request->data['filename'], $parts)) !== false) {
                          unset($parts[$i]);
                        }
                        $referralsFiles= implode(',', $parts);
                        $this->Session->write('referralsFiles',$referralsFiles);
                        unlink($filepath);
                    }
                }
            }        
        }
        if($this->Session->check('teamMembers') == true) {
            $this->set('selected',$this->Session->read('teamMembers'));
        }
        if (isset($contactListId)) {
            $this->set(compact('contactListId'));
        }
        $this->Session->delete('teamMembers');
        $this->set(compact('countryList'));
        $this->set(compact('groupMembersList'));
        $this->set('validationJs', $this->validationJs);
    }

    /**
    * Function used to get contact details
    * @param integer $id Contact id
    * @return Json $contactInfo
    * @author Priti Kabra
    */
    function getContactDetails($id=null)
    {
        $this->loadModel('Contact');
        $this->loadModel('Country');
        $this->loadModel('State');
        $this->layout = false;
        $this->render(false);
        if (!$id) {
          //return false;
          //$this->Session->setFlash(__('Invalid Contact.'), 'Front/flash_bad');
        }
        $conditions=array(
          'Contact.id' => $this->Encryption->decode($id),
        );
        $contactInfo = $this->Contact->find('first',array('conditions' => $conditions));
        echo json_encode($contactInfo);
    }

    /**
    * Function used for referrals Details
    * @param String $listType List Type (Default = received)
    * @param int $referredId referral id
    * @param string $backurl back url
    * @author Gaurav Bhandari
    */
    public function referralDetails($listType = "received", $referredId = null,$backurl = null)
    {
        $sessionUrl = $this->Session->read('BackUrlAfterLogin');
        if (!empty($sessionUrl)) {
            $this->Session->delete('BackUrlAfterLogin');
        }
        if (!empty($referredId)) {
            $userId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
            if ($listType == "received") {
                if (!$this->ReceivedReferral->find('first', array('conditions' => array('ReceivedReferral.to_user_id' => $userId, 'ReceivedReferral.id' => $this->Encryption->decode($referredId)), 'fields' => array('ReceivedReferral.id')))) {
                    $this->Session->setFlash(__('This referral does not exist.'), 'Front/flash_bad');
                    $this->redirect(array('controller' => 'referrals', 'action' => 'received'));
                }
            } else {
                if (!$this->SendReferral->find('first', array('conditions' => array('SendReferral.from_user_id' => $userId, 'SendReferral.id' => $this->Encryption->decode($referredId)), 'fields' => array('SendReferral.id')))) {
                    $this->Session->setFlash(__('This referral does not exist.'), 'Front/flash_bad');
                    $this->redirect(array('controller' => 'referrals', 'action' => 'sent'));
                }
            }
        }
        $this->set('referer', $backurl);
        if($referredId!=null){
            $referralid = $this->Encryption->decode($referredId);
            if($listType=='sent') {
                if (!$this->SendReferral->exists($referralid)) {
                    $this->Session->setFlash(__('This referral does not exist.'), 'Front/flash_bad');
                    $this->redirect(array('controller' => 'referrals', 'action' => 'sent'));
                }
                $this->SendReferral->bindModel(
                    array('hasOne' => array(
                        'State' => array(
                            'className' => 'State',
                            'foreignKey' => false,
                            'conditions' => array('State.state_subdivision_id = SendReferral.state_id'),
                            'fields' => array('State.state_subdivision_name')
                            ),
                        'Country' => array(
                            'className' => 'Country',
                            'foreignKey' => false,
                            'conditions' => array('Country.country_iso_code_2 = SendReferral.country_id'),
                            'fields' => array('Country.country_name')
                            )
                        )
                    )
                    );                
                $userData = $this->SendReferral->find('first', array('conditions' => array('SendReferral.id' => $referralid)));
                $this->set(compact('userData'));
                $getReceiverMailId = $this->User->find('first', array('conditions' => array('User.id' => $userData['SendReferral']['to_user_id']), 'fields' => array('User.user_email')));
                $this->set('sendMailTo',$getReceiverMailId['User']['user_email']);

                if (!empty($userData['SendReferral']['id'])) {
                    $referralComment = $this->ReferralComment->find('all', array('conditions' => array('ReferralComment.referral_id' => $this->Encryption->decode($userData['SendReferral']['id']))));
                    $this->set(compact('referralComment'));
                    $this->set('modal','SendReferral');
                }

            } else if($listType=='received') {
                if (!$this->ReceivedReferral->exists($referralid)) {
                    $this->Session->setFlash(__('This referral does not exist.'), 'Front/flash_bad');
                    $this->redirect(array('controller' => 'referrals', 'action' => 'received'));
                }
                $this->ReceivedReferral->bindModel(
                    array('hasOne' => array(
                        'State' => array(
                            'className' => 'State',
                            'foreignKey' => false,
                            'conditions' => array('State.state_subdivision_id = ReceivedReferral.state_id'),
                            'fields' => array('State.state_subdivision_name')
                            ),
                        'Country' => array(
                            'className' => 'Country',
                            'foreignKey' => false,
                            'conditions' => array('Country.country_iso_code_2 = ReceivedReferral.country_id'),
                            'fields' => array('Country.country_name')
                            )
                        )
                    )
                    );                
                $userData = $this->ReceivedReferral->find('first', array('conditions' => array('ReceivedReferral.id' => $referralid)));
                $this->set(compact('userData'));
                $getSenderMailId = $this->User->find('first', array('conditions' => array('User.id' => $userData['ReceivedReferral']['from_user_id']), 'fields' => array('User.user_email')));
                $this->set('sendMailTo',$getSenderMailId['User']['user_email']);

                if (!empty($userData['ReceivedReferral']['id'])) {
                    $referralComment = $this->ReferralComment->find('all', array('conditions' => array('ReferralComment.referral_id' => $this->Encryption->decode($userData['ReceivedReferral']['id']))));
                    $this->set(compact('referralComment'));
                    $this->set('modal','ReceivedReferral');
                }
            }else{
                $this->Session->setFlash(__('This referral does not exist'), 'Front/flash_bad');
                $this->redirect(array('controller' => 'referrals', 'action' => 'sent'));
            }
            $this->set('referredId', $referredId);
            $this->set('listType', $listType);
        }else{
            $this->Session->setFlash(__('This referral does not exist.'), 'Front/flash_bad');
            $this->redirect(array('controller' => 'referrals', 'action' => 'received'));
        }       
    }

    /**
    * Function used to download files of referrals
    * @param integer $fileName Filename
    * @author Priti Kabra
    */
    public function downloadFiles($fileName = null) 
    {
        $file = $this->Encryption->decode($fileName);
        if (!empty($fileName)) {
            $this->viewClass = 'Media';
            // Render app/webroot/files/example.docx
            $fileExt = pathinfo($file, PATHINFO_EXTENSION);
            $fileBase = basename($file);         // $file is set to "index.php"
            $fileBase = basename($file, ".".$fileExt);
            $downloadFileName = substr($fileBase, 19); //19 is used as the name stored in database is adding 19 chars(unique id) before the filename
            $params = array(
                'id' => $file,
                'name'      => $downloadFileName,
                'download' => true,
                'mimeType'  => array(
                    'docx' => 'application/vnd.openxmlformats-officedocument' .
                        '.wordprocessingml.document'
                ),
                'path' => 'files' . DS . 'referrals' . DS
            );
            $this->set($params);
        }
    }
    
    /**
    * Function used for adding comment
    * @return Json $result
    * @author Gaurav Bhandari
    */
    public function addComment()
    {
        $this->layout = false;
        $this->autoRender = false;
        if ($this->request->is('post')) {
          $this->layout = false;
          $this->autoRender = false;
          $referralId = $this->Encryption->decode($this->request->data['rid']);
            if (!empty($this->request->data['comment']) && is_numeric($referralId)) {
                $this->request->data['ReferralComment']['commented_by_id'] = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
                $this->request->data['ReferralComment']['referral_id'] = $referralId ;
                $this->request->data['ReferralComment']['comment'] = htmlentities($this->request->data['comment']) ;
                $type = $this->request->data['type'] == 'SendReferral' ? 'sent' : 'received';
                $this->request->data['ReferralComment']['type'] = $type;
                $this->ReferralComment->create();
                if ($this->ReferralComment->save($this->request->data['ReferralComment'])) {
                    $commentLastId =  $this->ReferralComment->getInsertID();
                    $condition = array(
                              'ReferralComment.referral_id' => $referralId,
                              'ReferralComment.commented_by_id' => $this->Encryption->decode($this->Session->read('Auth.Front.id')),
                              'ReferralComment.id' => $commentLastId,
                              );
                    $referralComment = $this->ReferralComment->find('first', array('conditions' => $condition));
                    // Create Message entry
                    $senderName=$referralComment['BusinessOwners']['fname']." ".$referralComment['BusinessOwners']['lname'];
                    $this->addMessageEntry($this->request->data,$senderName);
                    $data = array(
                            'user_id'   =>  $referralComment['BusinessOwners']['user_id'],
                            'profile_image' => $referralComment['BusinessOwners']['profile_image'] != null ? $referralComment['BusinessOwners']['profile_image'] : '',
                            'fname' => $referralComment['BusinessOwners']['fname'],
                            'lname' => $referralComment['BusinessOwners']['lname'],
                            'comment' => htmlentities($this->request->data['comment']),
                            'created' => $referralComment['ReferralComment']['created']
                            ); 
                    $tableName = $this->request->data['type'];
                    $userData = $this->$tableName->findById($referralComment['ReferralComment']['referral_id']);
                    $notificationEnable = explode(',',$userData['BusinessOwners']['notifications_enabled']); 
                    if(in_array('commentMadeOnReferral', $notificationEnable)) {                                         
                        //sent mail to receiver or sender
                        $sendType = $type == 'sent' ? 'received' : 'sent';
                        $emailLib = new Email();
                        $subject = "Referral comment ". $sendType.".";
                        $template = "referral_comment";
                        $format = "both";
                        $business_owner_name = $referralComment['BusinessOwners']['fname']." ".$referralComment['BusinessOwners']['lname'];
                        $url = Configure::read('SITE_URL') . 'referrals/referralDetails/' . $sendType . '/' . $this->request->data['rid'];
                        $variable = array('businessowner'=>$userData['BusinessOwners']['fname'].' '.$userData['BusinessOwners']['lname'],'username' => $business_owner_name, 'type' => $sendType, 'comment' => htmlentities($this->request->data['comment']), 'url' => $url);
                        $to = $this->request->data['sendMailTo'];
                        $success = $emailLib->sendEmail($to,$subject,$variable,$template,$format);
                    }
                    $view = new View($this, false);
                    $view->set('referralData',$data);
                    $html_content = $view->render('/Elements/Front/commentBoxView');
                    $result = array(
                            'response' => __($html_content),
                            'responsecode' => Configure::read('Response_code_Success'),
                    		'commentId' => $commentLastId,
                          );
                    echo json_encode($result);exit;
                } else {
                    $result = array(
                              'response' => __('Error'),
                              'responsecode' => Configure::read('Response_code_Error'),
                    		  'commentId' => 'NULL',
                            );
                    echo json_encode($result);exit;
                }
            }
        }
    }

    /**
    * Function used for received referral list
    * @author Gaurav Bhandari
    */
    public function received()
    {
        $this->layout = 'front';
        $titleForLayout = "Received Referrals";
        $userId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
        if (!$this->request->is('ajax')) {
            $this->Session->delete('direction');
            $this->Session->delete('sort');
        }
        //paginvation starts here
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
            $order = array('id' => 'desc');
        }
        $condition1 = array('ReceivedReferral.to_user_id' => $userId , 'ReceivedReferral.is_archive' => 0 );
        $totalCount = $this->ReceivedReferral->find('all',array('fields'=>'SUM(ReceivedReferral.monetary_value) AS total_count','conditions'=>$condition1));
        if ($search != '') {
            $condition['OR'] = array(
                "ReceivedReferral.first_name LIKE" => "%" . trim($search) . "%",
                "ReceivedReferral.last_name LIKE" => "%" . trim($search) . "%",
                "CONCAT(ReceivedReferral.first_name ,' ',ReceivedReferral.last_name) LIKE" => "%" . trim($search) . "%",
                "BusinessOwners.fname LIKE" => "%" . trim($search) . "%",
                "BusinessOwners.lname LIKE" => "%" . trim($search) . "%",
                "CONCAT(BusinessOwners.fname ,' ',BusinessOwners.lname) LIKE" => "%" . trim($search) . "%"
                );
        } else {
            $condition = array();
        }
        $condition1 = array_merge($condition1, $condition);
        $this->Paginator->settings = array(
            'conditions' => $condition1,
            'order' => $order,
            'limit' =>$perpage
            );
        $resultData = $this->Paginator->paginate('ReceivedReferral');
        $this->set('referralData', $resultData);
        $this->set('value',$totalCount[0][0]['total_count']);
        $this->set('search', $search);
        $isReferralExist = $this->Session->read('isReferralExist');
        $referralContactId = $this->Session->read('referralContactId');
        if (isset($isReferralExist) && isset($referralContactId)) {
            $this->set(compact('isReferralExist', 'referralContactId'));
            $this->Session->delete('isReferralExist');
            $this->Session->delete('referralContactId');
        }
        if ($this->request->is('ajax')) {
            $this->layout = false;
            $this->set('perpage', $perpage);
            $this->set('search', $search);
            $this->render('received_ajax');
        }
    }

    /**
    * Function used for delete referrals
    * @param int $referralId referral id 
    * @param string $action action for delete
    * @author 
    */
    public function delete($referralId = NULL,$action = NULL)
    {
    	if ($this->request->is('ajax')) {
    		$this->set('referralId', $this->request->data['id']);
    		$this->render('/Elements/Front/deletepopup');
    	}
    	if($this->request->is('post') && !$this->request->is('ajax')) {
    		if($this->referer() == Router::url(array('controller'=>'referrals','action'=>'sent'),true))
    		{
    			$this->SendReferral->delete($this->Encryption->decode($referralId));
    			$this->redirect(array('action' => 'sent'));
    		}elseif($this->referer() == Router::url(array('controller'=>'referrals','action'=>'archive','sent'),true))
    		{
    			$this->SendReferral->delete($this->Encryption->decode($referralId));
    			$this->redirect(array('action' => 'archive/sent'));
    		}elseif($this->referer() == Router::url(array('controller'=>'referrals','action'=>'archive','received'),true))
    		{
    			$this->ReceivedReferral->delete($this->Encryption->decode($referralId));
    			$this->redirect(array('action' => 'archive/received'));
    		} else {
    			$this->ReceivedReferral->delete($this->Encryption->decode($referralId));
    			$this->redirect(array('action' => 'received'));
    		}
    	}
    }

    /**
     * Function to show the list of archive sent messages
     * @author Jitendra Sharma
     * @param string $archiveType type of archive (By default it is sent)
     * @access public
     */
	public function archive($archiveType="sent")
    {
    	$model = ($archiveType=='sent') ? "SendReferral" : "ReceivedReferral";
    	$this->layout = 'front';
    	$titleForLayout = "Sent Referrals";
    	$userId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
    	if (!$this->request->is('ajax')) {
    		$this->Session->delete('direction');
    		$this->Session->delete('sort');
    	}
    	//paginvation starts here
    	$perpage = $this->Functions->get_param('perpage',  Configure::read('PER_PAGE'), true);
        $page = $this->Functions->get_param('page',  Configure::read('PAGE_NO'), false);
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
    
    	if($archiveType=='sent'){
    		$condition1 = array('from_user_id' => $userId);
    	}else{
    		$condition1 = array('to_user_id' => $userId);
    	}
    	
    	if ($search != '') {
    		$condition2 = array(
    			'is_archive'=>1,
    			'OR' => array(
    			    "first_name LIKE" => "%" . trim($search) . "%",
                    "last_name LIKE" => "%" . trim($search) . "%",
                    "CONCAT(first_name ,' ',last_name) LIKE" => "%" . trim($search) . "%",
                    "BusinessOwners.fname LIKE" => "%" . trim($search) . "%",
                    "BusinessOwners.lname LIKE" => "%" . trim($search) . "%",
                    "CONCAT(BusinessOwners.fname ,' ',BusinessOwners.lname) LIKE" => "%" . trim($search) . "%"    				
    			)
    		);
    	}else{
    		$condition2 = array('is_archive'=>1);
    	}
    	$condition = array_merge($condition1, $condition2);
    	$this->Paginator->settings = array(
    		'conditions' => $condition,
    		'order' => $order,
    		'limit' =>$perpage
    	);
    	$resultData = $this->Paginator->paginate($model);
    	$this->set('archiveData', $resultData);
    	$this->set(compact('model'));
    	$this->set(compact('archiveType'));
    	$this->set('search', $search);
    	
    	if ($this->request->is('ajax')) {
    		$this->layout = false;
    		$this->set('perpage', $perpage);
    		$this->set('search', $search);
    		$this->render('archive_ajax');    	  
    	}
    }
    
    /**
     * Function to delete the archive sent messages
     * @author Jitendra Sharma
     * @param string $archiveId archive id
     * @access public
     */
    public function removeArchive($archiveType="sent",$archiveId=null)
    {
    	$model = ($archiveType=='sent') ? "SendReferral" : "ReceivedReferral";
    	if ($this->$model->delete($this->Encryption->decode($archiveId))) {
    		$archiveData = $this->archive($archiveType);
    	}
    }
    
    /**
     * Function to delete the archive sent messages
     * @author Jitendra Sharma
     * @param string $archiveId archive id
     * @access public
     */
	public function bulkAction($archiveType="sent")
    {
    	$model = ($archiveType=='sent') ? "SendReferral" : "ReceivedReferral";
    	$action = $this->request->data['mass_action'];
    	if(is_array($this->request->data['referralIds'])){
	    	 if($action=="massdelete"){
	    		$record = $this->request->data['referralIds'];
	    		foreach ($record as $archiveId){
		    		$this->$model->delete($this->Encryption->decode($archiveId));
	    		}    		
	    	}
    	}
    	$this->redirect(array('controller' => 'referrals', 'action' => 'archive',$archiveType));
    }
    
    /**
     * Function to delete the referrals
     * @author Jitendra Sharma
     * @param string $referralId referrals id
     * @access public
     */
    public function removeReferral($archiveType="sent",$archiveId=null)
    {
    	$model = ($archiveType=='sent') ? "SendReferral" : "ReceivedReferral";
    	$this->$model->id = $this->Encryption->decode($archiveId);
    	if (!$this->$model->exists()) {
    		$this->Session->setFlash(__('invalid Referral'), 'default', array ('class' => 'primary alert'));
    		 
    		throw new NotFoundException(__('invalid referral'));
    	}    	 
    	if ($this->$model->saveField('is_archive', 1)) {
    		if($archiveType=="sent"){
    			$archiveData = $this->sent();
    		}else{
    			$archiveData = $this->received();
    		}
    	}
    }
    
    /**
     * Function to delete the referrals in bulk
     * @author Jitendra Sharma
     * @param string $referralType type of referral
     * @access public
     */
    public function bulkReferralAction($referralType="sent",$bulkAction = "archive")
    {
    	$model = ($referralType=='sent') ? "SendReferral" : "ReceivedReferral";
    	$action = $this->request->data['mass_action'];
    	//pr($this->request->data);
    	if(is_array($this->request->data['referralIds'])){
    		if($action=="massdelete" || $action=="massunarchive"){
    			$record = $this->request->data['referralIds'];
    			foreach ($record as $referralId){
    				$this->$model->id = $this->Encryption->decode($referralId);
		    		if (!$this->$model->exists()) {
		    			$this->Session->setFlash(__('invalid Referral'), 'default', array ('class' => 'primary alert'));		    			 
		    			throw new NotFoundException(__('invalid referral'));
		    		}	
		    		if($bulkAction == 'archive') {	    		
		    		    $this->$model->saveField('is_archive', 1);
		    		} elseif($bulkAction == 'unarchive') {
		    		    $this->$model->saveField('is_archive', 0);
		    		}
    			}
    		}
    	}
    	if($referralType=="sent"){
    		$this->redirect(array('controller' => 'referrals', 'action' => 'sent'));
    	}else{
    		$this->redirect(array('controller' => 'referrals', 'action' => 'received'));
    	}
    }

     /**
     * Function to update the referral
     * @author Priti Kabra
     * @param int $referralid referral id
     * @param string $backurl back url
     * @access public
     */
    public function referralUpdate($referralid = null,$backurl = null)
    {
        $this->validationJs = array('send.referral.validation');
        $this->set('referer', $backurl);
        $rId = $this->Encryption->decode($referralid);
        if (!$this->ReceivedReferral->exists($rId)) {
            $this->Session->setFlash(__('This referral does not exist.'), 'Front/flash_bad');
            $this->redirect(array('controller' => 'referrals', 'action' => 'received'));
        }
        $this->ReceivedReferral->bindModel(
            array('hasOne' => array(
                'State' => array(
                    'className' => 'State',
                    'foreignKey' => false,
                    'conditions' => array('State.state_subdivision_id = ReceivedReferral.state_id'),
                    'fields' => array('State.state_subdivision_name')
                    ),
                'Country' => array(
                    'className' => 'Country',
                    'foreignKey' => false,
                    'conditions' => array('Country.country_iso_code_2 = ReceivedReferral.country_id'),
                    'fields' => array('Country.country_name')
                    )
                )
            )
            );

        $referralData = $this->ReceivedReferral->find('first', array('conditions' => array('ReceivedReferral.id' => $rId)));        
        $this->set(compact('referralData'));
        if ($this->request->is('post')) {
            $this->request->data['ReceivedReferral']['monetary_value'] = trim($this->request->data['ReceivedReferral']['monetary_value'], '$');      
            $this->ReceivedReferral->id = $rId;
            unset($this->request->data['ReceivedReferral']['first_name']);
            unset($this->request->data['ReceivedReferral']['last_name']);
            unset($this->request->data['ReceivedReferral']['email']);
            unset($this->request->data['ReceivedReferral']['city']);
            unset($this->request->data['ReceivedReferral']['zip']);
            $this->request->data['ReceivedReferral'] = array_map('trim', $this->request->data['ReceivedReferral']);
            if ($this->ReceivedReferral->save($this->request->data['ReceivedReferral'])) {
                $this->Session->setFlash(__('Referral has been updated successfully.'), 'Front/flash_good');
                $this->redirect(array('controller' => 'referrals', 'action' => 'received'));
            }
        }
        $this->set('validationJs', $this->validationJs);
    }
    
    /**
     * Action for export list of received referral
     * @Jitendra sharma
     */
    function downloadReferralList()
    {
    	$this->layout = "ajax";
    	$this->autoRender = false;    	
    	$filepath = WWW_ROOT . 'files' . DS . 'Referral_exported_' . date('d-m-Y-H:i:s') . '.csv';
    	// fields to be show in exported csv
    	$fields = array('ReceivedReferral.first_name','ReceivedReferral.last_name','ReceivedReferral.company','ReceivedReferral.job_title','ReceivedReferral.address','ReceivedReferral.city','ReceivedReferral.zip','ReceivedReferral.office_phone','ReceivedReferral.mobile','ReceivedReferral.email','ReceivedReferral.website', 'ReceivedReferral.referral_status', 'ReceivedReferral.monetary_value', 'ReceivedReferral.created','BusinessOwners.fname', 'BusinessOwners.lname','Country.country_name','State.state_subdivision_name');
    	
    	// condition array
    	$userId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
    	$condition = array('ReceivedReferral.to_user_id' => $userId , 'ReceivedReferral.is_archive' => 0 );
    	
    	// fetch result array
    	$data = $this->ReceivedReferral->find('all', array('fields' => $fields, 'conditions' => $condition, 'order'=>'created DESC'));
    	
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
    		$this->Session->setFlash(__('No referral(s) to download.'), 'Front/flash_bad');
    		$this->redirect(array('controller' => 'referrals', 'action' => 'received'));
    	}
    }
    
    /**
     * Action for format csv data
     * @Jitendra sharma
     */
    public function formatCsvData($data=array()){
    	foreach ($data as $key => $model){
    		$formatData[$key]['Referral']['Referral'] 		= $model['ReceivedReferral']['first_name']." ".$model['ReceivedReferral']['last_name'];
    		$formatData[$key]['Referral']['From'] 			= $model['BusinessOwners']['fname']." ".$model['BusinessOwners']['lname'];
    		$formatData[$key]['Referral']['Company'] 		= (!empty($model['ReceivedReferral']['company'])) ? $model['ReceivedReferral']['company'] : "NA";
    		$formatData[$key]['Referral']['Job Title'] 		= (!empty($model['ReceivedReferral']['job_title'])) ? $model['ReceivedReferral']['job_title']: "NA";
    		$formatData[$key]['Referral']['Country'] 		= $model['Country']['country_name'];
    		$formatData[$key]['Referral']['State'] 			= $model['State']['state_subdivision_name'];
    		$formatData[$key]['Referral']['Address'] 		= (!empty($model['ReceivedReferral']['address'])) ? $model['ReceivedReferral']['address']: "NA";
    		$formatData[$key]['Referral']['City'] 			= (!empty($model['ReceivedReferral']['city'])) ? $model['ReceivedReferral']['city'] : "NA";
    		$formatData[$key]['Referral']['Zip Code'] 		= (!empty($model['ReceivedReferral']['zip'])) ? $model['ReceivedReferral']['zip'] : "NA";
    		$formatData[$key]['Referral']['Office Phone'] 	= $model['ReceivedReferral']['office_phone'];
    		$formatData[$key]['Referral']['Mobile'] 		= (!empty($model['ReceivedReferral']['mobile'])) ? $model['ReceivedReferral']['mobile'] : "NA";
    		$formatData[$key]['Referral']['Email Address'] 	= (!empty($model['ReceivedReferral']['email'])) ? $model['ReceivedReferral']['email'] : "NA";
    		$formatData[$key]['Referral']['Website'] 		= (!empty($model['ReceivedReferral']['website'])) ? $model['ReceivedReferral']['website'] : "NA";
    		$formatData[$key]['Referral']['Referral Amount']= (!empty($model['ReceivedReferral']['monetary_value'])) ? "$".$model['ReceivedReferral']['monetary_value'] : "$0";
    		$formatData[$key]['Referral']['Status'] 		= ucfirst($model['ReceivedReferral']['referral_status']);
    		$formatData[$key]['Referral']['Received On'] 	= date("d/M/Y",strtotime($model['ReceivedReferral']['created']));
    	}
    	return $formatData;
    }
    
    /**
     * Action for get latest comment live
     * @params $referralId referral id 
     * @params $lastComment last commment id
     * @return void
     * @Jitendra sharma
     */
    public function getLatestComment($referralId=null,$lastComment=null){
    	$new_last_comment = $lastComment;
    	//$this->autoRender = false;
    	$this->layout = 'ajax';
    	$referralId = $this->Encryption->decode($referralId);
    	$new_messages = $this->ReferralComment->find('all',
    			array(
    					'fields' => array('ReferralComment.*,BusinessOwners.user_id,BusinessOwners.fname,BusinessOwners.lname,BusinessOwners.profile_image'),
    					'conditions' => array('ReferralComment.id >' => $lastComment, 'ReferralComment.referral_id' => $referralId)
    			)
    	);
    	//pr($new_messages);die;
    	$this->set(compact('new_messages','lastComment'));
    	
    	
    	//print json_encode(array("last_message" => $new_last_message, "messages" => $new_messages));
    }
    
    /*
     *Web service to get the Referral List
     *@author Priti Kabra
     */
    public function api_getReferral()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        if (!empty($this->jsonDecodedRequestedData->referral_name)) {
            $this->jsonDecodedRequestedData->referral_name = (strpos($this->jsonDecodedRequestedData->referral_name, '%') !== false) ? str_replace('%', '\%', $this->jsonDecodedRequestedData->referral_name) : $this->jsonDecodedRequestedData->referral_name;
            $this->jsonDecodedRequestedData->referral_name = (strpos($this->jsonDecodedRequestedData->referral_name, '_') !== false) ? str_replace('_', '\_', $this->jsonDecodedRequestedData->referral_name) : $this->jsonDecodedRequestedData->referral_name;
        }
        if ($error == 0) {
            $refererFilter = array();
            $fieldReferral = array();
            $referralData = array();
            $sortData = '';
            $userId = $this->loggedInUserId;
            /* Listing conditions for sent, receive, archive sent and archive received*/
            if (isset($this->jsonDecodedRequestedData->list_page)) {
                switch ($this->jsonDecodedRequestedData->list_page) {
                    case "received":
                        $model = 'ReceivedReferral';
                        $fieldReferral = array('ReceivedReferral.referral_status', 'ReceivedReferral.monetary_value', 'ReceivedReferral.is_read');
                        $conditionsRequired = array('ReceivedReferral.to_user_id' => $userId , 'ReceivedReferral.is_archive' => 0 );
                        break;
                    case "archiveReceived":
                        $model = 'ReceivedReferral';
                        $fieldReferral = array('ReceivedReferral.referral_status', 'ReceivedReferral.monetary_value', 'ReceivedReferral.is_read');
                        $conditionsRequired = array('ReceivedReferral.to_user_id' => $userId , 'ReceivedReferral.is_archive' => 1 );
                        break;
                    case "sent":
                        $model = 'SendReferral';
                        $conditionsRequired = array('SendReferral.from_user_id' => $userId , 'SendReferral.is_archive' => 0 );
                        break;
                    case "archiveSent":
                        $model = 'SendReferral';
                        $conditionsRequired = array('SendReferral.from_user_id' => $userId , 'SendReferral.is_archive' => 1 );
                    default:
                        break;
                }
            }
            /** Name filter for referrer name, sender's name and both*/
            if (!empty($this->jsonDecodedRequestedData->referral_name)) {
                $refererFilter['OR'] = array(
                    "$model.first_name LIKE" => "%" . trim($this->jsonDecodedRequestedData->referral_name) . "%",
                    "$model.last_name LIKE" => "%" . trim($this->jsonDecodedRequestedData->referral_name) . "%",
                    "CONCAT($model.first_name, ' ',$model.last_name) LIKE" => "%" . trim($this->jsonDecodedRequestedData->referral_name) . "%",
                    "BusinessOwners.fname LIKE" => "%" . trim($this->jsonDecodedRequestedData->referral_name) . "%",
                    "BusinessOwners.lname LIKE" => "%" . trim($this->jsonDecodedRequestedData->referral_name) . "%",
                    "CONCAT(BusinessOwners.fname, ' ',BusinessOwners.lname) LIKE" => "%" . trim($this->jsonDecodedRequestedData->referral_name) . "%"
                );
            }
            /** Fields array to be fetched from the database */
            $fields = array("$model.id", "$model.from_user_id", "$model.to_user_id", "$model.first_name", "$model.last_name", "$model.created", "BusinessOwners.fname", "BusinessOwners.lname");
            $fields = array_merge($fields, $fieldReferral);
            /** Sort data according to the sort filter */
            if (!empty($this->jsonDecodedRequestedData->sort_data) && !empty($this->jsonDecodedRequestedData->sort_direction)) {
                if ($this->jsonDecodedRequestedData->sort_data == "fname") {
                    $sortData = "BusinessOwners.".$this->jsonDecodedRequestedData->sort_data." ".$this->jsonDecodedRequestedData->sort_direction;
                } else {
                    if (($this->jsonDecodedRequestedData->sort_data == "monetary_value") || ($this->jsonDecodedRequestedData->sort_data == "referral_status")) {
                        if ($model != "ReceivedReferral") {
                            $sortData = "$model.created DESC";
                        } else {
                            $sortData = "$model.".$this->jsonDecodedRequestedData->sort_data." ".$this->jsonDecodedRequestedData->sort_direction;
                        }
                    } else {
                        $sortData = "$model.".$this->jsonDecodedRequestedData->sort_data." ".$this->jsonDecodedRequestedData->sort_direction;
                    }
                }
            } else {
                $sortData = "$model.created DESC";
            }
            $conditions = array_merge($conditionsRequired, $refererFilter);
            $referralList = $this->$model->find('all',
                                                array('conditions' => $conditions,
                                                      'fields' => $fields,
                                                      'order' => $sortData,
                                                      'recursive'=>1,
                                                      'limit'=>$this->jsonDecodedRequestedData->record_per_page,
                                                      'page' => $this->jsonDecodedRequestedData->page_no
                                                )
                                              );
            $totalReferrals = $this->$model->find('count',
                                                array('conditions' => $conditions,
                                                      'fields' => $fields,
                                                      'order' => "'$model.created DESC'",
                                                      'recursive'=>1
                                                )
                                              );
            
            foreach ($referralList as $key => $value) {
                $referralData[$key][$model]['id'] = $referralList[$key][$model]['id'];
                $referralData[$key][$model]['from_user_id'] = $referralList[$key][$model]['from_user_id'];
                $referralData[$key][$model]['to_user_id'] = $referralList[$key][$model]['to_user_id'];
                $referralData[$key][$model]['first_name'] = $referralList[$key][$model]['first_name'];
                $referralData[$key][$model]['last_name'] = $referralList[$key][$model]['last_name'];
                if ($model == "ReceivedReferral") {
                    $referralData[$key][$model]['referral_status'] = $referralList[$key][$model]['referral_status'];
                    $referralData[$key][$model]['monetary_value'] = $referralList[$key][$model]['monetary_value'];
                    $referralData[$key][$model]['is_read'] = $referralList[$key][$model]['is_read'];
                }
                $referralData[$key][$model]['created'] = $referralList[$key][$model]['created'];
                $referralData[$key][$model]['fname'] = $referralList[$key]['BusinessOwners']['fname'];
                $referralData[$key][$model]['lname'] = $referralList[$key]['BusinessOwners']['lname'];
            }
            foreach ($referralData as $key => $value) {
                $list[] = $value[$model];
            }
            if (!empty($referralData)) {
                $this->set(array(
                    'code' => Configure::read('RESPONSE_SUCCESS'),
                    'result' => $list,
                    'message' => 'Referral List.',
                    'page_no' => $this->jsonDecodedRequestedData->page_no,
                    'totalReferrals' => "$totalReferrals",
                    '_serialize' => array('code', 'result', 'message', 'page_no', 'totalReferrals')
                ));
            } else {
                $this->errorMessageApi('');
            }
        } else {
            $this->errorMessageApi($errMsg);
        }
    }

    /*
     *Web service to send referral
     *@author Priti Kabra
     */
    public function api_sendReferral()
    {
        $size = 0;
        $fileUploaded = array();
        $fileName = '';
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        if (empty($this->request->data['teamMembers'])) {
            $error = 1;
            $errMsg = "Please select a member.";
        }
        if (isset($_FILES) && !empty($_FILES)) {
            foreach ($_FILES as $filesize) {
                $size += $filesize['size']/Configure::read('MAXFILESIZE');
            }
        }
        if ($size != Configure::read('MIN_IMG_ARRAY_SIZE') && $size > Configure::read('MAX_IMG_ARRAY_SIZE')) {
            $error = 1;
            $errMsg = "Attachments cannot exceed 10 MB.";
        }
        if ($error == 0) {
            $userId = $this->loggedInUserId;
            $fields = array('User.*', 'BusinessOwners.fname', 'BusinessOwners.lname', 'BusinessOwners.group_id', 'BusinessOwners.notifications_enabled');
            $userInfo = $this->User->userInfo($userId, $fields);
            $teamMember = array_unique(explode(",",urldecode($this->request->data['teamMembers'])));
            $i = 0;
            if (!empty($_FILES)) {
                foreach ($_FILES as $file) {
                    if (!empty($file['name'])) {
                        $ext = substr(strtolower(strrchr($file['name'], '.')), 1);
                        $fileUploadName = $this->Common->getFileName($file['name']);
                        $filepath = WWW_ROOT . 'files/referrals/' . $fileUploadName;
                        if (in_array($ext, Configure::read('ARRAYEXT'))) {
                            move_uploaded_file($file['tmp_name'], $filepath);
                            if (!empty($fileName)) {
                                $fileUploaded[$i] = $fileUploadName;
                                $fileName = $fileName . "," . $fileUploadName;
                            } else {
                                $fileUploaded[$i] = $fileUploadName;
                                $fileName = $fileUploadName;
                            }
                            $i++;
                        } else {
                            $error = 1;
                            $errMsg = 'Please enter a file with a valid extension.';
                        }
                    }
                }
            }
            if ($error == 0) {
				$refStatData = $this->ReferralStat->find('first',array('conditions'=>array('ReferralStat.sent_from_id' => $userId, 'ReferralStat.sent_to' => $this->request->data['teamMembers'], 'email_id' => $this->request->data['email'] )));
		            if (empty($refStatData)) {
		                $this->ReferralStat->create();
		                $statData = array('ReferralStat' => array('sent_from_id' => $userId, 'group_id' => $userInfo['BusinessOwners']['group_id'], 'sent_to' => $this->request->data['teamMembers'], 'email_id' => $this->request->data['email']));
		                $this->ReferralStat->save($statData);
	            }
                $this->request->data['user_groupid'] = $userInfo['BusinessOwners']['group_id'];
				$this->request->data['user_id'] = $userId;
                if (!empty($this->request->data['contact_id'])) {
                    $contactUpdate = array_map('trim', $this->request->data);
                    $contactCheck  = $this->Contact->find('first', array('conditions' => array('Contact.user_id' => $userId, 'Contact.email' => $this->request->data['email'], 'Contact.id !=' => $this->Encryption->decode($this->request->data['contact_id']))));
                    if (empty($contactCheck)) {
                        $this->Contact->id = $this->Encryption->decode($this->request->data['contact_id']);
                        $this->Contact->save($contactUpdate);
                    }
                } else {
                    $contactSave = array_map('trim', $this->request->data);
                    $contactCheck  = $this->Contact->find('first', array('conditions' => array('Contact.user_id' => $userId, 'Contact.email' => $this->request->data['email'])));
                    if (empty($contactCheck)) {
                        $this->Contact->create();
                        $this->Contact->save($contactSave);
                    }
                }
                foreach ($teamMember as $sendTo) {
                    if (!empty($sendTo)) {
                        $this->request->data['files'] = $fileName;
                        $this->request->data['from_user_id'] = $userId;
                        $this->request->data['to_user_id'] = $sendTo;
                        $this->request->data['group_id'] = $userInfo['BusinessOwners']['group_id'];
                        $sendUser = $this->User->userInfoById($sendTo);
                        $messageTo[] = $sendUser['BusinessOwners']['fname'].' '.$sendUser['BusinessOwners']['lname'];
                        $emailLib = new Email();
                        $to = $sendUser['User']['user_email'];
                        $subject = 'Your Referral';
                        $template = 'send_referral_mail';
                        $format = 'both';
                        $user_name = $sendUser['BusinessOwners']['fname']." ".$sendUser['BusinessOwners']['lname'];
                        $referral_name = $this->request->data['first_name']." ".$this->request->data['last_name'];
                        $sender_name = $userInfo['BusinessOwners']['fname']." ".$userInfo['BusinessOwners']['lname'];
                        $params = array('name' => $user_name, 'referral_name' => $referral_name, 'sender_name' => $sender_name);
                        $this->SendReferral->create();
                        if ($this->SendReferral->save($this->request->data)) {
                            $this->ReceivedReferral->create();
                            if ($this->ReceivedReferral->save($this->request->data)) {
                                $notificationEnable = explode(',', $sendUser['BusinessOwners']['notifications_enabled']);
                                if(in_array('receiveReferral', $notificationEnable)) {
                                    $success = $emailLib->sendEmail($to, $subject, $params, $template, $format);
                                }
                                // sent message live feed
                                $userGroupId = $this->BusinessOwner->find('first', array('conditions' => array('BusinessOwner.user_id' => $this->loggedInUserId), 'fields' => array('BusinessOwner.group_id')));
                                $this->LiveFeed->create();
                                $liveFeedData['LiveFeed']['to_user_id'] = $sendTo;
                                $liveFeedData['LiveFeed']['from_user_id'] = $userId;
                                $liveFeedData['LiveFeed']['group_id'] = $userGroupId['BusinessOwner']['group_id'];
                                $liveFeedData['LiveFeed']['feed_type'] = "referral";
                                $this->LiveFeed->save($liveFeedData);
                                //send PN
                                if ($sendUser['User']['device_type'] == "ios") {
                                    $this->Common->iospushnotification($sendUser['User']['device_token'], 'You have received a new referral', 'service_test', 'You have received a new referral');
                                } elseif ($sendUser['User']['device_type'] == "android") {
                                    $this->Common->androidpushnotification($sendUser['User']['device_token'], 'You have received a new referral', 'service_test', 'You have received a new referral');
                                }
                                $this->set(array(
                                    'code' => Configure::read('RESPONSE_SUCCESS'),
                                    'message' => 'Referral has been sent successfully.',
                                    '_serialize' => array('code', 'message')
                                ));
                            }
                        } else {
                            foreach ($fileUploaded as $unlinkFile) {
                                unlink(WWW_ROOT . 'files/referrals/' . $file_name);
                            }
                            $this->errorMessageApi('Please Try Again.');
                        }
                    }
                }
                //Post on social media
                $socialConfig = explode(',', $userInfo['BusinessOwners']['notifications_enabled']);
                $recievers = implode(', ', $messageTo);
                // Send Twitter Update
                if (in_array('tweetReferralSend', $socialConfig) && $userInfo['User']['twitter_connected'] == 1) {                    
                    $statusMessage="Just sent a referral to $recievers via @Foxhopr";
                    $this->postToTwitter($userInfo, $statusMessage);
                }
                // Send Facebook Update
                if (in_array('fbReferralSend', $socialConfig) && $userInfo['User']['fb_connected'] == 1) {
                    $statusMessage="Just sent a referral to $recievers via http://foxhopr.com";
                    $this->postToFacebook($userInfo, $statusMessage);
                }
                // Send Linkedin Update
                if(in_array('linkedinReferralSend', $socialConfig) && $userInfo['User']['linkedin_connected'] == 1) {
                    $statusMessage="Just sent a referral to $recievers via http://foxhopr.com";
                    $this->postToLinkedin($userInfo, $statusMessage);
                }
            } else {
                foreach ($fileUploaded as $unlinkFile) {
                    if (unlink(WWW_ROOT . 'files/referrals/' . $file_name)) {
                        $this->errorMessageApi($errMsg);
                    }
                }
                $this->errorMessageApi($errMsg);
            }
        } else {
            $this->errorMessageApi($errMsg);
        }
    }

    /*
     *Web service to delete the referral
     *@author Priti Kabra
     */
    public function api_deleteReferral()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        $success = 0;
        if (!empty($this->jsonDecodedRequestedData->deleteId) && !empty($this->jsonDecodedRequestedData->listPage)) {
            if (in_array($this->jsonDecodedRequestedData->listPage, array("sent", "archiveSent"))) {
                $model = 'SendReferral';
            } elseif (in_array($this->jsonDecodedRequestedData->listPage, array("received", "archiveReceived"))) {
                $model = 'ReceivedReferral';
            }
            $deleteID = explode(",", urldecode($this->jsonDecodedRequestedData->deleteId));
            $error = $this->$model->CheckReferralExist($deleteID);
            if ($error == 0) {
                if (in_array($this->jsonDecodedRequestedData->listPage, array("sent", "received"))) {
                    foreach ($deleteID as $deleteReferralId) {
                        $this->$model->id = $this->Encryption->decode($deleteReferralId);
                        $updateData['is_archive'] = 1;
                        $updateData['is_read'] = 1;
                        $updateData['is_total_read'] = 1;
                        if ($this->$model->save($updateData)) {
                            $success = 1;
                            $successMsg = " Referral(s) has been moved to " . $this->jsonDecodedRequestedData->listPage . " archive successfully.";
                        }
                    }
                } elseif (in_array($this->jsonDecodedRequestedData->listPage, array("archiveSent", "archiveReceived"))) {
                    foreach ($deleteID as $deleteReferralId) {
                        $deleteData['id'] = $this->Encryption->decode($deleteReferralId);
                        if ($this->$model->delete($deleteData['id'])) {
                            $success = 1;
                            $successMsg = "Referral(s) has been permanently deleted successfully";
                        }
                    }
                }
                if ($success == 1) {
                    $this->set(array(
                        'code' => Configure::read('RESPONSE_SUCCESS'),
                        'message' => $successMsg,
                        '_serialize' => array('code', 'message')
                    ));
                } else {
                    $this->errorMessageApi('Please try again.');
                }
            } else {
                $errMsg = isset($errMsg) ? $errMsg : "Referral does not exist.";
                $this->errorMessageApi($errMsg);
            }
        }
    }

    /*
     *Web service to get the details of the referral
     *@author Priti Kabra
     */
    public function api_referralDetail()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        if ( empty($this->jsonDecodedRequestedData->referralId) || empty($this->jsonDecodedRequestedData->detailPage)) {
            $error = 1;
            $errMsg = "Please provide all parameters.";
        }
        if ($error == 0) {
            $rId = $this->Encryption->decode($this->jsonDecodedRequestedData->referralId);
            $model = in_array($this->jsonDecodedRequestedData->detailPage, array("sent", "archiveSent")) ? 'SendReferral' : 'ReceivedReferral';
            if ($model == "ReceivedReferral") {
                $this->ReceivedReferral->id = $rId;
                $updateData['is_read'] = 1;
                $updateData['is_total_read'] = 1;
                $this->ReceivedReferral->save($updateData);
            }
            $condition = array("$model.id" => $rId);
            $conditionsArr = in_array($this->jsonDecodedRequestedData->detailPage, array("archiveSent", "archiveReceived")) ? array("$model.is_archive" => 1) : array("$model.is_archive" => 0);
            $condition = array_merge($condition, $conditionsArr);
            $fields = array("$model.*", 'BusinessOwners.fname', 'BusinessOwners.lname', 'Country.country_name', 'State.state_subdivision_name');
            $referralData = $this->$model->find('first', array(
                                                    'conditions' => $condition,
                                                    'fields' => $fields
                                                    )
                                                );
            if (!empty($referralData)) {
                if (!empty($referralData[$model]['files'])) {
                    $files = explode(',', $referralData[$model]['files']);
                    foreach ($files as $key => $value) {
                        $fileList[$key]['url'] = Configure::read('SITE_URL') . 'files/referrals/' . $value;
                        $fileList[$key]['name'] = substr($value,19);
						if (!empty($fileList[$key]['name'])) {
                            $fileList[$key]['name'] = substr($value,19);
                        } else {
                            $fileList[$key]['name'] = "";
						}
                    }
                    $referralData[$model]['files'] = $fileList;
                } else {
                    $referralData[$model]['files'] = array();
                    $referralData[$model]['file_name'] = array();
                }
                $referralData[$model]['user_name'] = $referralData['BusinessOwners']['fname'] . " ". $referralData['BusinessOwners']['lname'];
                $referralData[$model]['country_name'] = $referralData['Country']['country_name'];
                $referralData[$model]['state_name'] = $referralData['State']['state_subdivision_name'];
                $this->set(array(
                    'code' => Configure::read('RESPONSE_SUCCESS'),
                    'result' => $referralData[$model],
                    'message' => 'Referral Detail.',
                    '_serialize' => array('code', 'result', 'message')
                ));
            } else {
                $this->errorMessageApi('Referral does not exist.');
            }
        } else {
            $this->errorMessageApi($errMsg);
        }
    }

    /*
     *Web service to get the list of the comments
     *@author Priti Kabra
     */
    public function api_referralComment()
    {
        $rId = $this->Encryption->decode($this->jsonDecodedRequestedData->referralId);
        $commentField = array('ReferralComment.*', 'BusinessOwners.user_id', 'BusinessOwners.fname', 'BusinessOwners.lname', 'BusinessOwners.profile_image');
        $referralComment = $this->ReferralComment->find('all', array('conditions' => array('ReferralComment.referral_id' => $rId), 'fields' => $commentField));
        if (!empty($referralComment)) {
            foreach ($referralComment as $key => $value) {
                $referralComment[$key]['ReferralComment']['commented_by'] = $referralComment[$key]['BusinessOwners']['fname'] . " ". $referralComment[$key]['BusinessOwners']['lname'];
                $profile_image = !empty($referralComment[$key]['BusinessOwners']['profile_image']) ? 'uploads/profileimage/'.$referralComment[$key]['ReferralComment']['commented_by_id'].'/'.$referralComment[$key]['BusinessOwners']['profile_image'] : 'no_image.png';
                $referralComment[$key]['ReferralComment']['commented_by_profile_image'] = Configure::read('SITE_URL') . 'img/' . $profile_image;
            }
            //pr($referralComment); exit;
            foreach ($referralComment as $key => $value) {
                $referralCommentList[] = $value['ReferralComment'];
            }
            $this->set(array(
                    'code' => Configure::read('RESPONSE_SUCCESS'),
                    'result' => $referralCommentList,
                    'message' => 'Referral Detail.',
                    '_serialize' => array('code', 'result', 'message')
                ));
        } else {
            $this->errorMessageApi('No comment.');
        }
    }

    /*
     *Web service to add comment on the referral detail
     *@author Priti Kabra
     */
    public function api_addReferralComment()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        if (empty($this->jsonDecodedRequestedData->referralId) || empty($this->jsonDecodedRequestedData->detailPage)) {
            $error = 1;
            $errMsg = "Please provide all parameters.";
        }
        if ($error == 0) {
            $rId = $this->Encryption->decode($this->jsonDecodedRequestedData->referralId);
            $saveData['referral_id'] = $rId;
            $saveData['type'] = $this->jsonDecodedRequestedData->type;
            $saveData['commented_by_id'] = $this->loggedInUserId;
            $saveData['comment'] = $this->jsonDecodedRequestedData->comment;
            $this->ReferralComment->create();
            if ($this->ReferralComment->save($saveData)) {
                $commentLastId =  $this->ReferralComment->getInsertID();
                $condition = array(
                              'ReferralComment.referral_id' => $rId,
                              'ReferralComment.commented_by_id' => $this->loggedInUserId,
                              'ReferralComment.id' => $commentLastId,
                              );
                $referralComment = $this->ReferralComment->find('first', array('conditions' => $condition));
                $senderName=$referralComment['BusinessOwners']['fname']." ".$referralComment['BusinessOwners']['lname'];
                $commentSaveData['rid'] = $this->jsonDecodedRequestedData->referralId;
                $commentSaveData['type'] = ($this->jsonDecodedRequestedData->type == 'sent') ? 'SendReferral' : 'ReceivedReferral';
                $commentSaveData['ReferralComment']['commented_by_id'] = $this->loggedInUserId;
                $commentSaveData['ReferralComment']['comment'] = $this->jsonDecodedRequestedData->comment;
                $this->addMessageEntry($commentSaveData, $senderName);
                $getMailModel = ($saveData['type'] == "received") ? 'ReceivedReferral' : 'SendReferral' ;
                //it is for received only
                $refferalMailId = $this->$getMailModel->find('first', array(
                                      'conditions' => array("$getMailModel.id" => $rId),
                                      )
                                  );
                $notificationEnable = explode(',', $refferalMailId['BusinessOwners']['notifications_enabled']); 
                if(in_array('commentMadeOnReferral', $notificationEnable)) {
                    //send mail to receiver or sender
                    $sendType = $saveData['type'] == 'sent' ? 'received' : 'sent';
                    $emailLib = new Email();
                    $subject = "Referral comment ". $sendType.".";
                    $template = "referral_comment";
                    $format = "both";
                    $business_owner_name = $referralComment['BusinessOwners']['fname']." ".$referralComment['BusinessOwners']['lname'];
                    $url = Configure::read('SITE_URL') . 'referrals/referralDetails/' . $sendType . '/' . $this->jsonDecodedRequestedData->referralId;
                    $variable = array('businessowner'=>$refferalMailId['BusinessOwners']['fname'].' '.$refferalMailId['BusinessOwners']['lname'], 'username' => $business_owner_name, 'type' => $sendType, 'comment' => $this->jsonDecodedRequestedData->comment, 'url' => $url);
                    $to = $refferalMailId['User']['username'];
                    if ($emailLib->sendEmail($to,$subject,$variable,$template,$format)) {
                        $this->set(array(
                            'code' => Configure::read('RESPONSE_SUCCESS'),
                            'message' => 'Your comment has been posted successfully.',
                            '_serialize' => array('code', 'message')
                        ));
                    } else {
                        $this->errorMessageApi('Mail not sent, but commented successfully.');
                    }
                }
                $this->set(array(
                    'code' => Configure::read('RESPONSE_SUCCESS'),
                    'message' => 'Your comment has been posted successfully.',
                    '_serialize' => array('code', 'message')
                ));
            } else {
                $this->errorMessageApi('Please try again.');
            }
        } else {
            $this->errorMessageApi($errMsg);
        }
    }
    /**
     * Function to add messages table entry as the comment is posted on referrals
     * @author Rohan Julka
     * @param array $data, string $senderName
     * @access public
     */
    public function addMessageEntry($data,$senderName)
    {
        $refid = $this->Encryption->decode($data['rid']);
        $type = $data['type'] == 'SendReferral' ? 'sent' : 'received';
        $sendType = ($type == 'sent') ? 'received' : 'sent';
        if ( $data['type']=='SendReferral' ) {
            $refData = $this->SendReferral->findById($refid);
        } else {
            $refData = $this->ReceivedReferral->findById($refid);
        }
        $recipientUser = $refData['BusinessOwners']['fname'].' '.$refData['BusinessOwners']['lname'];
        $content = $data['ReferralComment']['comment'];
        $contentInMessage = "A comment is posted by $senderName on referral you $sendType.
        <br/>
        <br/>
        $content        
        <br/>
        <br/>
        Thanks,<br/>
        Foxhopr Team";
        $contentInMessage = htmlentities($contentInMessage);
        $dataToInsert = array('Message'=>array('subject'=>"You have received new comment",
            'written_by_user'=>$data['ReferralComment']['commented_by_id'],
            'content'=>"$contentInMessage",
            'message_type'=>'referral_comment',
            'recipient_users'=>"$recipientUser",
            'is_read'=>0,
            'is_archive'=>0
             ));
        $this->Message->create();
        if ($this->Message->save($dataToInsert)){
            $msgId=$this->Message->id;
            $this->MessageRecipient->save(array('message_id'=>$msgId,
                'recipient_user_id'=>$this->Encryption->decode($refData['User']['id']),
                'is_read'=>0,
                'is_total_read'=>0,
                'is_archive'=>0
                ));
            
            $pnDeviceType = $refData['User']['device_type'];
            $pn['device_token'] = $refData['User']['device_token'];
            $pn['notification_message'] = "You have received a new comment";
            $pn['pushData'] = "You have received a new comment";
            if ($refData['User']['device_type'] == "ios") {
               $this->Common->iospushnotification($refData['User']['device_token'], 'You have received a new comment', 'service_test', 'You have received a new comment');
            } elseif ($refData['User']['device_type'] == "android") {
                $this->Common->androidpushnotification($refData['User']['device_token'], 'You have received a new comment', 'service_test', 'You have received a new comment');
            }
        }
    }

    /*
     *Web service to edit referral
     *@author Priti Kabra
     */
    public function api_editReferral()
    {
        $fileUploaded = array();
        $fileName = '';
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        if ($error == 0) {
            $userId = $this->loggedInUserId;
            $referralId = $this->Encryption->decode($this->jsonDecodedRequestedData->referralId);
            $this->ReceivedReferral->id = $referralId;
            if ($this->ReceivedReferral->save($this->jsonDecodedRequestedData)) {
                $this->set(array(
                    'code' => Configure::read('RESPONSE_SUCCESS'),
                    'message' => 'Referral has been updated successfully',
                    '_serialize' => array('code', 'message')
                ));
            }
        } else {
            $this->errorMessageApi($errMsg);
        }
    }

	/**
    * Function used for received referral list
    * @author Gaurav Bhandari
    */
    function requestRating() 
    {
        $this->autoRender = false;
        $referralId = $this->Encryption->decode($this->request->data['referral']);
        $checkId = $this->ReceivedReferral->find('first', array('conditions' => array('ReceivedReferral.id'=>$referralId/*,'ReceivedReferral.rating_status' => 0*/)));
        if(!empty($checkId)) {
            if(empty($checkId['ReceivedReferral']['rating_status'])) {
                $toId = $this->Encryption->encode($checkId['ReceivedReferral']['to_user_id']);           
                $emailLib = new Email();
                $subject = "FoxHopr: Please rate and share your reviews";
                $template = "referral_rating";
                $format = "both";
                $business_owner_name = $checkId['ReceivedReferral']['first_name']." ".$checkId['ReceivedReferral']['last_name'];
                $url = Configure::read('SITE_URL') . 'reviews/rating/' . $checkId['ReceivedReferral']['id'] . '/' . $toId;
                $variable = array('businessowner'=>$business_owner_name, 'url' => $url);
                $to = $checkId['ReceivedReferral']['email'];
                $success = $emailLib->sendEmail($to,$subject,$variable,$template,$format);
                $data = array('response' => 'success','code'=>Configure::read('RESPONSE_SUCCESS'));
                return json_encode($data);
            } else {
                $data = array('response' => 'warning','code'=>Configure::read('RESPONSE_WARNING'));
                return json_encode($data);
            }
            
        } else {
            $data = array('response' => 'error','code'=>Configure::read('RESPONSE_ERROR'));
            return json_encode($data);
        }
    }

    /**
    * check contact exists
    * @params string $contactEmail contact email
    * @params string $contactId contact id
    * @author Priti Kabra
    */
    public function checkContact($contactEmail = null, $contactId = null) 
    {
		$userId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
		$this->autoRender = false;
        if (!empty($contactId)) {
            $conditions = array('Contact.user_id' => $userId, 'Contact.email' => $contactEmail, 'Contact.id !=' => $this->Encryption->decode($contactId));
        } else {
            $conditions = array('Contact.user_id' => $userId, 'Contact.email' => $contactEmail);
        }
		$var  = $this->Contact->find('first', array('conditions' => $conditions));
		if ($var) {
			return 'false';
		} else {
			return 'true';
		}
	}
}
