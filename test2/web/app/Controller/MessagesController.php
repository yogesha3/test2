<?php
/**
 * Controller for Messages Control in user dashboard
 */
App::uses('Email', 'Lib');
class MessagesController extends AppController 
{
    public $includePageJs = '';
    
    /**
     * Components
     *
     * @var array
     * @access public
     */
    public $components = array(
		'Security','Paginator','Businessowner','Messages','Common'
    );
    
    /**
     * Models used by the Controller
     *
     * @var array
     * @access public
     */
    public $uses = array('Message','MessageRecipient','MessageAttachment','BusinessOwner','User','MessageComment','LiveFeed');
    
    /**
     * function calling before request action
     * @author Jitendra Sharma
     */
    public function beforeFilter()
    {
    	parent::beforeFilter();
    	$this->Security->blackHoleCallback = 'blackhole';
    	$this->Security->unlockedActions = array('composeMessage','addComment','inbox','sentMessages','bulkMessageAction','updateCounter','popupFunction', 'massActionFunction', 'api_composeMessage', 'api_getMessage', 'api_deleteMessage', 'api_messageDetail', 'api_messageComment', 'api_addMessageComment', 'api_changeMessageStatus');
    	$this->layout = "front";
    	$this->set('titleForLayout', 'FoxHopr: Messages');
    }

    /**
     * function to send messages to group members
     * @author Jitendra Sharma
     */
    public function composeMessage()
    {
        $this->loadModel('PrevGroupRecord');
        $this->request->data['Message']['written_by_user'] = $userId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
        $userGroupId = $this->Session->read('Auth.Front.BusinessOwners.group_id');
        $usersList = $this->BusinessOwner->getMyGroupMemberList($userGroupId,$userId);
        $prevGroupMembersList = $this->PrevGroupRecord->getMyPreviousGroupMemberList($userId);
        $allUserList = $usersList;
        if(!empty($prevGroupMembersList) && !empty($usersList)) {
            $allUserList = $allUserList+$prevGroupMembersList;
            $usersList = array(                        
                        'Current' => $usersList,
                        'Previous' => $prevGroupMembersList,
                        );
            
        } else if(!empty($prevGroupMembersList)){
            $allUserList = $allUserList+$prevGroupMembersList;
            $usersList = array(                        
                        'Previous' => $prevGroupMembersList,
                        );
        }else {
            $temp = $usersList;
            unset($usersList);
            $usersList['Current'] = $temp;
        }
        $this->set(compact('usersList'));
        if($this->Session->check('teamMembers') == true) {
            $this->request->data['Message']['sendto'] = 0;
            $this->request->data['Message']['recipient_list'] = $this->Session->read('teamMembers');
        }
        $this->Session->delete('teamMembers');
        if($this->request->is('post') && !$this->request->is('ajax')) {
            $writtenBy = $this->request->data['Message']['written_by_user'];
            $writtenByData = $this->User->find('first',array('conditions'=>array('User.id'=>$writtenBy)));
            $this->Message->set($this->request->data);
            $loginUserId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
            $userData=$this->User->find('first',array('conditions'=>array('User.id'=>$loginUserId)));
            if(isset($userData['BusinessOwners']['notifications_enabled'])) {
                
                $socialConfig=explode(',',$userData['BusinessOwners']['notifications_enabled']);
                
                if(!empty($socialConfig) && (in_array('tweetMessageSend',$socialConfig) ||  in_array('fbMessageSend',$socialConfig) || in_array('linkedinMessageSend',$socialConfig))) {
                    if(!empty($userData)) {
                        if($this->request->data['Message']['sendto']==0) {
                            $this->request->data['Message']['recipient_list'] = array_unique(str_replace('prev_', '', $this->request->data['Message']['recipient_list']));
                            $recipientUsers = $this->request->data['Message']['recipient_list'];
                        } else {
                            $recipientUsers = $usersList;
                            $currentList = array();
                            if(isset($recipientUsers['Current'])) {
                                $currentList = array_keys($recipientUsers['Current']);
                            } 
                            $recipientUsers = array_unique($currentList);
                            //$recipientUsers=array_keys($recipientUsers);
                        }
                        
                        $results=$this->User->find('all',array('conditions'=>array('User.id'=>$recipientUsers)));
                        $userIds=array();
                        foreach($results as $result) {
                            $userIds[]=$result['BusinessOwners']['fname'].' '.$result['BusinessOwners']['lname'];
                        }
                        $userIds=implode(', ', $userIds);
                        // Send Twitter update
                        if(in_array('tweetMessageSend',$socialConfig) && $userData['User']['twitter_connected'] == 1) {
                            $statusMessage="Just sent a message to $userIds via @FoxHopr";
                            $this->postToTwitter($userData, $statusMessage);
                        }
                        // Send Facebook update
                        if(in_array('fbMessageSend',$socialConfig) && $userData['User']['fb_connected'] == 1) {
                            $statusMessage="Just sent a message to $userIds via http://foxhopr.com";
                            $this->postToFacebook($userData, $statusMessage);
                        }
                        // Send Linkedin update
                        if(in_array('linkedinMessageSend',$socialConfig) && $userData['User']['linkedin_connected'] == 1) {
                            $statusMessage="Just sent a message to $userIds via http://foxhopr.com";
                            $this->postToLinkedin($userData, $statusMessage);
                        }
                    }
                }
            }
            if($this->Message->validates()){  
                if($this->request->data['Message']['sendto']==0 && empty($this->request->data['Message']['recipient_list'])){
                    $this->Session->setFlash(__('Please choose recipients!'), 'Front/flash_bad');
                    $this->Session->setFlash(__('Please choose recipients!'), 'Front/flash_bad','', 'error');
                } else{
                    // send message
                    $recipientsName = array();
                    if($this->request->data['Message']['sendto']==0){
                        $this->request->data['Message']['recipient_list'] = $this->request->data['Message']['recipient_list'];
                        $recipientUsers = $this->request->data['Message']['recipient_list'];
                        $recipientUsers = array_unique(str_replace('prev_', '', $this->request->data['Message']['recipient_list']));
                        
                        $usersNewList = array();
                        foreach($allUserList as $key => $user) {
                            $key = str_replace('prev_', '', $key);
                            $usersNewList[$key] = $user;
                        }
                        $allUserList = $usersNewList;
                        foreach ($recipientUsers as $recipientId){
                            $recipientsName[] = $allUserList[$recipientId];
                        }
                        
                    } else {
                        if(empty($usersList['Current'])) {
                            $this->Session->setFlash(__('Currently no member available in your group!'), 'Front/flash_bad');
                            $this->redirect(array('controller'=>'messages','action'=>'inbox'));
                        } else {
                            $recipientUsers = $usersList;
                            $recipientUsers = array_keys($recipientUsers['Current']);
                            $recipientsName = array_values($usersList['Current']);
                        }
                    }
                    $recipientsName = array_unique($recipientsName);
                    $this->request->data['Message']['recipient_users'] = implode(", ",$recipientsName);
                    if ($this->Message->save($this->request->data)) {
                        $messageId = $this->Message->id;
                        $fileName = $this->Session->check('messagesFiles') == true ? $this->Session->read('messagesFiles') : '';
                        if ($fileName != '') {
                            $tempFiles = explode(',',$fileName);
                            foreach ($tempFiles as $temp) {
                                $filepathTempPath = WWW_ROOT . 'files/messages/temp/'. $temp;
                                $fileUploadName = $temp;
                                $filepathMovePath = WWW_ROOT . 'files/messages/'. $fileUploadName;
                                if (file_exists($filepathTempPath)) {
                                    rename($filepathTempPath, $filepathMovePath);
                                    $attachmentData['MessageAttachment']['message_id'] = $messageId;
                                    $attachmentData['MessageAttachment']['filename'] = $fileUploadName;
                                    $this->MessageAttachment->create();
                                    $this->MessageAttachment->save($attachmentData);
                                }
                            }
                          }
                        // sent message to recipients                                               
                        $recipientData['MessageRecipient']['message_id'] = $messageId;
                        foreach ($recipientUsers as $userId){
                            $this->MessageRecipient->create();
                            $recipientData['MessageRecipient']['recipient_user_id'] = $userId;
                            $this->MessageRecipient->save($recipientData);
                            // sent message live feed
                            $this->LiveFeed->create();
                            $liveFeedData['LiveFeed']['to_user_id'] 	= $userId;
                            $liveFeedData['LiveFeed']['from_user_id'] 	= $this->request->data['Message']['written_by_user'];
                            $liveFeedData['LiveFeed']['group_id']   = $userGroupId;
                            $liveFeedData['LiveFeed']['feed_type'] 		= "message";
                            $this->LiveFeed->save($liveFeedData);
                            // sent email
                            $userData = $this->User->findById($userId,'User.user_email,User.device_type,User.device_id,device_token,BusinessOwner.fname,BusinessOwner.lname,BusinessOwner.notifications_enabled');
                            $notificationEnable = explode(',',$userData['BusinessOwner']['notifications_enabled']);
                            $emailLib = new Email();
                            $subject = $this->request->data['Message']['subject'];
                            $content = $this->request->data['Message']['content'];
                            $template = "inbox_message";
                            $format = "both";
                            if(in_array('receiveMessage', $notificationEnable)) {                       
                                $variable = array('username'=>$userData['BusinessOwner']['fname'].' '.$userData['BusinessOwner']['lname'],'subject' => $subject, 'content'=>$content,'writtenByName'=>$writtenByData['BusinessOwner']['fname'].' '.$writtenByData['BusinessOwner']['lname']);
                                $to = $userData['User']['user_email'];
                                $success = $emailLib->sendEmail($to,$subject,$variable,$template,$format);
                            }
                            //send PN
                            if ($userData['User']['device_type'] == "ios") {
                                $this->Common->iospushnotification($userData['User']['device_token'], "You have received a new message", 'service', "You have received a new message");
                            } elseif ($userData['User']['device_type'] == "android") {
                                $this->Common->androidpushnotification($userData['User']['device_token'], "You have received a new message", 'service', "You have received a new message");
                            }
                        }                                                                   
                        $this->Session->setFlash(__('Your message has been sent successfully!'), 'Front/flash_good');
                        $this->redirect(array('controller' => 'messages', 'action' => 'composeMessage'));
                    }
                }
            }else{                  
                if(count($this->Message->validationErrors)>0)                
                $this->Session->setFlash(__('Please fill all mandatory fields!'), 'Front/flash_bad');
            }
        }
        if($this->request->is('ajax')) {
            $this->autoRender = false;
            if(!isset($this->request->data['action'])) {
                $files = $_FILES;          
                $fileUploadName = $this->Common->getFileName($files['files']['name']);
                $filepath = WWW_ROOT . 'files/messages/temp/' . $fileUploadName;
                move_uploaded_file($files['files']['tmp_name'], $filepath);
                if($this->Session->check('messagesFiles') == true) {
                    $messagesFiles = $this->Session->read('messagesFiles').','.$fileUploadName;
                    $this->Session->write('messagesFiles',$messagesFiles);
                } else {
                    $this->Session->write('messagesFiles',$fileUploadName);
                }
                $response = array('filename'=>$fileUploadName,'sessionData'=>$this->Session->read('messagesFiles'));
                return json_encode($response);
            } else {
                if(isset($this->request->data['filename']) && $this->request->data['filename'] != '') {
                    $filepath = WWW_ROOT . 'files/messages/temp/' . $this->request->data['filename'];
                    if(file_exists($filepath))
                    {
                        $parts = explode(',', $this->Session->read('messagesFiles'));
                        while(($i = array_search($this->request->data['filename'], $parts)) !== false) {
                            unset($parts[$i]);
                        }
                        $messagesFiles= implode(',', $parts);
                        $this->Session->write('messagesFiles',$messagesFiles);
                        unlink($filepath);
                    }
                }         
            }        
        }
    }
    
    /**
     * function to show list of messages received
     * @param string $listType type of list to show (either inbox or inbox archive)
     * @author Gaurav Bhandari
     */
    public function inbox($listType="inbox")
    {
        $this->layout = 'front';
        $titleForLayout = "Message in inbox";
        $userId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
        if (!$this->request->is('ajax')) {
            $this->Session->delete('direction');
            $this->Session->delete('sort');
        }
        //paginvation starts here
        $perpage = $this->Functions->get_param('perpage',  Configure::read('PER_PAGE'), true);
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
        if($listType=="inbox"){
        	$condition1 = array('MessageRecipient.recipient_user_id'=>$userId,'MessageRecipient.is_archive'=>0);
        }else{
        	$condition1 = array('MessageRecipient.recipient_user_id'=>$userId,'MessageRecipient.is_archive'=>1);
        }
        if ($search != '') {
          $condition2['OR'] = array(                 
              "BusinessOwners.fname LIKE" => "%" . trim($search) . "%",
              "BusinessOwners.lname LIKE" => "%" . trim($search) . "%",
              "Message.subject LIKE" => "%" . trim($search) . "%",
              "CONCAT(BusinessOwners.fname ,' ',BusinessOwners.lname) LIKE" => "%" . trim($search) . "%",
          );
        } else {
            $condition2 = array();
        }
        $condition = array_merge($condition1, $condition2);
        $this->Paginator->settings = array(
                    'conditions' => $condition,
                    'order' => $order,
                    'limit' =>$perpage,
                    'recursive' => 1
                );
        $resultData = $this->Paginator->paginate('MessageRecipient');
        $this->set('inboxData', $resultData);
        $this->set('messageComponent', $this->Messages);
        $this->set('search', $search);
        $this->set('listType', $listType);
        if ($this->request->is('ajax')) {
            $this->layout = false;
            $this->set('perpage', $perpage);
            $this->set('search', $search);
            $this->render('inbox_ajax');
        }
    }
    
    /**
     * function to view the detail of the message
     * @param int $mId message id
     * @param string $backurl url of back button
     * @author Priti Kabra
     */
    public function viewMessage($mId = null,$backurl = null)
    {
        $this->set('titleForLayout','Foxhopr: Message Details');
        $sessionUrl = $this->Session->read('BackUrlAfterLogin');
        if (!empty($sessionUrl)) {
            $this->Session->delete('BackUrlAfterLogin');
        }
        $this->set('referer', $backurl);
        $titleForLayout = "Message Detail";
        $userId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
        $userData=$this->User->findById($userId);
        $this->set(compact('userData'));
        $messageId = $this->Encryption->decode($mId);
        if (!$this->Message->exists($messageId)) {
            $this->Session->setFlash(__('This message does not exist.'), 'Front/flash_bad');
            $this->redirect(array('controller' => 'messages', 'action' => 'inbox'));
        }
        $messageData = $this->Message->find('first', array('conditions' => array('Message.id' => $messageId)));
        $this->Message->id = $messageId;
        $this->Message->saveField('is_read', 1);
        $messageRecipients = $this->Messages->messageRecipient($messageId);
        $sendMailTo = array();
        $receiversName = array();
        $loginUser = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
        if ($loginUser == $messageData['Message']['written_by_user']) {
            foreach ($messageRecipients as $mR) {
                $sendMailTo[] = $mR['Recipient']['user_email'];
            }
            $type = "sent";
        } else {
            $sendMailTo[] = $messageData['User']['username'];
            $type = "received";
        }
        $sendMailTo = implode(",",$sendMailTo);
        foreach ($messageRecipients as $mR) {
            $rcvrData = $this->BusinessOwner->find('first', array('conditions' => array('BusinessOwner.user_id' => $mR['MessageRecipient']['recipient_user_id'])));
            $receiversName[]=ucfirst($rcvrData['BusinessOwner']['fname']). " ".ucfirst($rcvrData['BusinessOwner']['lname']);
        }
        $messageAttachments = $this->Messages->messageAttachment($mId);
        if (!empty($messageData['Message']['id'])) {
            $messageComment = $this->MessageComment->find('all', array('conditions' => array('MessageComment.message_id' => $this->Encryption->decode($messageData['Message']['id']))));
            $this->set(compact('messageComment'));
        }
        $this->set(compact('messageData','messageRecipients','messageAttachments','sendMailTo','type','receiversName'));
    }
    
    /**
     * function to download the message attachment files
     * @param string $fileName filename
     * @author Priti Kabra
     */
    public function downloadFiles($fileName = null) 
    {
        $file = $this->Encryption->decode($fileName);
        if (!empty($fileName)) {
            $this->viewClass = 'Media';
            $fileType = explode('.',$file);
            foreach ($fileType as $fileExt) {
                $fileExt = $fileExt;
            }
            $fileBase = basename($file);
            $fileBase = basename($file, ".".$fileExt);
            $downloadFileName = substr($fileBase, 19);
            $params = array(
                'id'        => $file,
                'name'      => $downloadFileName,
                'download'  => true,
                'mimeType'  => array(
                                    'docx' => 'application/vnd.openxmlformats-officedocument' .'.wordprocessingml.document'
                                ),
                'path' => 'files' . DS . 'messages' . DS
            );
            $this->set($params);
        }
    }
    
    /**
     * function to add comments on message Detail Page
     * @author Priti Kabra
     */
    public function addComment()
    {
        $this->layout = false;
        $this->autoRender = false;
        if ($this->request->is('post')) {
          $this->layout = false;
          $this->autoRender = false;
          $messageId = $this->Encryption->decode($this->request->data['mid']);
            if (!empty($this->request->data['comment']) && is_numeric($messageId)) {
                $this->request->data['MessageComment']['commented_by_id'] = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
                $this->request->data['MessageComment']['message_id'] = $messageId ;
                $this->request->data['MessageComment']['comment'] = htmlentities($this->request->data['comment']) ;
                $type = $this->request->data['type'] == 'sent' ? 'sent' : 'received';
                $this->request->data['MessageComment']['type'] = $type;
                $this->MessageComment->create();
                if ($this->MessageComment->save($this->request->data['MessageComment'])) {
                    $commentLastId =  $this->MessageComment->getInsertID();
                    $condition = array(
                        'MessageComment.message_id' => $messageId,
                        'MessageComment.commented_by_id' => $this->Encryption->decode($this->Session->read('Auth.Front.id')),
                        'MessageComment.id' => $commentLastId,
                        );
                    $messageComment = $this->MessageComment->find('first', array('conditions' => $condition));
                    // Creating entry in Messages 
                    $username=$messageComment['BusinessOwner']['fname']." ".$messageComment['BusinessOwner']['lname'];
                    $this->addMessageData($this->request->data,$username);
                    $data = array(
                        'fname' => $messageComment['BusinessOwner']['fname'],
                        'lname' => $messageComment['BusinessOwner']['lname'],
                        'profile_image' => $messageComment['BusinessOwner']['profile_image'],
                        'user_id' => $messageComment['BusinessOwner']['user_id'],
                        'comment' => htmlentities($this->request->data['comment']),
                        'created' => $messageComment['MessageComment']['created']
                        );
                    $sendMailToUsers = explode(',',$this->request->data['sendMailTo']);
                    foreach ($sendMailToUsers as $sendMailToAB) {
                    $userData = $this->BusinessOwner->findByEmail($sendMailToAB);
                    $notificationEnable = explode(',',$userData['BusinessOwner']['notifications_enabled']); 
                    if(in_array('commentMadeOnMessage', $notificationEnable)) {
                        $sendType = $type == 'sent' ? 'received' : 'sent';
                        $emailLib = new Email();
                        $subject = "Message comment ". $sendType.".";
                        $template = "message_comment";
                        $format = "both";
                        $business_owner_name = $messageComment['BusinessOwner']['fname']." ".$messageComment['BusinessOwner']['lname'];
                        $url = Configure::read('SITE_URL') . 'messages/viewMessage/' . $this->request->data['mid'];
                        $variable = array('businessowner'=>$userData['BusinessOwner']['fname'].' '.$userData['BusinessOwner']['lname'],'username' => $business_owner_name, 'type' => $sendType, 'comment' => htmlentities($this->request->data['comment']), 'url' => $url);
                        if (!empty($sendMailToAB)) {                          
                          $to = $sendMailToAB;
                          $success = $emailLib->sendEmail($to,$subject,$variable,$template,$format);
                        }
                    }
                  }                  
                  $view = new View($this, false);
                  $view->set('messageComment',$data);
                  $html_content = $view->render('/Elements/Front/messageCommentBoxView');
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
                    		  'commentId' => $commentLastId,
                            );
                    echo json_encode($result);exit;
                }
            }
        }
    }

	/**
     * function to show list of messages sent
     * @param string $listType type of list (either sent or sent archive)
     * @author Jitendra Sharma
     */
    public function sentMessages($listType="sentmessage")
    {
    	$titleForLayout = "Sent Message";
    	$userId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
    	
    	if (!$this->request->is('ajax')) {
    		$this->Session->delete('direction');
    		$this->Session->delete('sort');
    	}
    	//pagination starts here
    	$perpage = $this->Functions->get_param('perpage',  Configure::read('PER_PAGE'), true);
        $page = $this->Functions->get_param('page', Configure::read('PAGE_NO'), false);    	
    	$search = $this->Functions->get_param('search');
        $this->Functions->set_param('direction');        
        $this->Functions->set_param('sort');    	
    	if ($this->Session->read('sort') != '') {
    		$order = array($this->Session->read('sort') => $this->Session->read('direction'));
    	} else {
    		$order = array('id' => 'desc');
    	}    	
    	if($listType=="sentmessage"){
    		$condition1 = array('Message.written_by_user'=>$userId,'Message.is_archive'=>0);
    	}else{
    		$condition1 = array('Message.written_by_user'=>$userId,'Message.is_archive'=>1);
    	}    	
    	if ($search != '') {
    		$condition2['OR'] = array(
    				"Message.recipient_users LIKE" => "%" . trim($search) . "%",
    				"Message.subject LIKE" => "%" . trim($search) . "%",
    		);
    	} else {
    		$condition2 = array();
    	}
    	$condition3=array('Message.message_type'=>'message');
    	$condition = array_merge($condition1, $condition2,$condition3);
    	$this->Paginator->settings = array(
    			'conditions' => $condition,
    			'order' => $order,
    			'limit' =>$perpage,
    			'recursive'=>-1
    	);

    	$resultData = $this->Paginator->paginate('Message');
    	$this->set('sentData', $resultData);
    	$this->set('messageComponent', $this->Messages);
    	$this->set('search', $search);
    	$this->set('businessowner', $this->Businessowner);
    	$this->set('messageComponent', $this->Messages);
    	$this->set('listType', $listType);    	
    	if ($this->request->is('ajax')) {
    		$this->layout = false;
    		$this->set('perpage', $perpage);
    		$this->set('search', $search);
    		$this->render('sent_messages_ajax');    		
    	}    	
    }

	/**
     * Function to delete the messages indivisually
     * @author Jitendra Sharma
     * @param string $messageType message type (either from sent or inbox)
     * @param string $isArchive either from archive or not
     * @param string $messageId message id
     * @access public
     */
    public function removeMessage($messageType="sent",$isArchive=false,$messageId=null)
    {
    	$model = ($messageType=='sent') ? "Message" : "MessageRecipient";    	
    	$this->$model->id = $this->Encryption->decode($messageId);
    	if (!$this->$model->exists()) {
    		$this->Session->setFlash(__('invalid Message'), 'default', array ('class' => 'primary alert'));    		 
    		throw new NotFoundException(__('invalid Message'));
    	}     	
    	if($isArchive && $model=="MessageRecipient"){
    		$this->$model->delete();
    	}elseif($isArchive && $model=="Message"){    		
    		$ok = $this->$model->saveField('is_archive',2);
    	}else{
    		$this->$model->saveField('is_archive', 1);
    	}    	  	 
    	if($messageType=="sent"){
    		$listType = (!$isArchive) ? "sentmessage" : "archive";
    		$messageData = $this->sentMessages($listType);
    	}else{
    		$listType = (!$isArchive) ? "inbox" : "archive";
    		$messageData = $this->inbox($listType);
    	}
    }
    
    /**
     * Function to delete the messages in bulk
     * @author Jitendra Sharma
     * @param string $messageType message type (either from sent or inbox)
     * @param string $isArchive either from archive or not
     * @access public
     */
    public function bulkMessageAction($messageType="sent",$isArchive=false)
    {
    	$model = ($messageType=='sent') ? "Message" : "MessageRecipient";
    	$action = $this->request->data['mass_action'];
    	if(is_array($this->request->data['messageIds'])){
    		$record = $this->request->data['messageIds'];
    		foreach ($record as $messageId){
    			$this->$model->id = $this->Encryption->decode($messageId);
    			if (!$this->$model->exists()) {
    				$this->Session->setFlash(__('invalid message'), 'default', array ('class' => 'primary alert'));
    				throw new NotFoundException(__('invalid message'));
    			}
    			// apply mass action
    			if($action=="massdelete"){
    				if($isArchive && $model=="MessageRecipient"){
    					$this->$model->delete();
    				}elseif($isArchive && $model=="Message"){
    					$this->$model->saveField('is_archive', 2);
    				}else{
    					$this->$model->saveField('is_archive', 1);
    				}
    			}elseif($action=="massread"){
    				$this->$model->saveField('is_read', 1);
    			}elseif($action=="massunread"){
    				$this->$model->saveField('is_read',0);
    			}elseif($action=="massunarchive"){
    				$this->$model->saveField('is_archive',0);
    			}     			
    		}    		
    	}
    	if($messageType=="sent"){
    		$listType = (!$isArchive) ? "sentmessage" : "archive";
    		$this->redirect(array('controller' => 'messages', 'action' => 'sentMessages',$listType));
    	}else{
    		$listType = (!$isArchive) ? "inbox" : "archive";
    		$this->redirect(array('controller' => 'messages', 'action' => 'inbox',$listType));
    	}
    }
    
    /**
     * Function to get the updated counter
     * @author Jitendra Sharma
     * @param string $messageType type of messages
     * @access public
     * @return unread message counter
     */
    public function updateCounter($messageType="inbox")
    {
    	$loginUserId = $this->Encryption->decode($this->request->data['loginUserId']);
    	$messageCounter = $this->Common->unreadCounter('messages',$loginUserId);
    	echo $messageCounter;die;
    }
    
    /**
     * Action for get latest comment live
     * @params $messageId message id
     * @params $lastComment last commment id
     * @return void
     * @Jitendra sharma
     */
    public function getLatestComment($messageId=null,$lastComment=null){
    	$new_last_comment = $lastComment;
    	//$this->autoRender = false;
    	$this->layout = 'ajax';
    	$messageId = $this->Encryption->decode($messageId);
    	$new_messages = $this->MessageComment->find('all',
    			array(
    					'fields' => array('MessageComment.*,BusinessOwner.user_id,BusinessOwner.fname,BusinessOwner.lname,BusinessOwner.profile_image'),
    					'conditions' => array('MessageComment.id >' => $lastComment, 'MessageComment.message_id' => $messageId)
    			)
    	);
    	//pr($new_messages);die;
    	$this->set(compact('new_messages','lastComment'));
    }
    
    /**
     * Function to blackhole action from security components
     * @author Jitendra Sharma
     * @param string $type blackhole reason
     * @access public
     */
    public function blackhole($type) {
    	echo $type;die;
    }
    /**
     * Function to add Message entry whenever new comment on an email is posted
     * @author Rohan Julka
     * @param array $data, string $username
     * @access public
     */
    public function addMessageData($data,$username)
    {
        $receiviers=explode(',',$data['sendMailTo']);
        $type = ($data['type'] == 'sent') ? 'sent' : 'received';
        $sendType = ($type == 'sent') ? 'received' : 'sent';
        $messageTo = NULL;
        $recipientUsers = array();
        foreach ( $receiviers as $receiver ) {
            $response = $this->User->findByUserEmail($receiver);
            $messageTo = $this->Encryption->decode($response['User']['id']);
            $recipientUsers[] = ucfirst($response['BusinessOwners']['fname']).' '.ucfirst($response['BusinessOwners']['lname']);
        }        
        $recipientUser = implode(',', $recipientUsers);
        $content = $data['MessageComment']['comment'];
        $contentInMessage = "A comment is posted by $username on message you $sendType.
        <br/>
        <br/>
        $content
        <br/>
        <br/>
        Thanks,<br/>
        Foxhopr Team";
        $contentInMessage = htmlentities($contentInMessage);
        $dataToInsert = array('Message'=>array('subject'=>"You have received new comment",
                'written_by_user'=>$data['MessageComment']['commented_by_id'],
                'content'=>"$contentInMessage",
                'message_type'=>'message_comment',
                'recipient_users'=>"$recipientUser",
                'is_read'=>0,
                'is_archive'=>0
        ));
        $this->Message->create();
        if ( $this->Message->save($dataToInsert) ){
            $msgId = $this->Message->getLastInsertID();
            foreach ( $receiviers as $receiver ) {
                $response = $this->User->findByUserEmail($receiver);
                $messageTo = $this->Encryption->decode($response['User']['id']);
                $this->MessageRecipient->create();
                $this->MessageRecipient->save(array('MessageRecipient'=>array('message_id'=>$msgId,
                    'recipient_user_id'=>$messageTo,
                    'is_read'=>0,
                    'is_total_read'=>0,
                    'is_archive'=>0
                )));
                $pnDeviceType = $response['User']['device_type'];
                $pn['device_token'] = $response['User']['device_token'];
                $pn['notification_message'] = "You have received a new comment";
                $pn['pushData'] = "You have received a new comment";
                if ($response['User']['device_type'] == "ios") {
                    $this->Common->iospushnotification($response['User']['device_token'], 'You have received a new comment', 'service_test', 'You have received a new comment');
                } elseif ($response['User']['device_type'] == "android") {
                    $this->Common->androidpushnotification($response['User']['device_token'], 'You have received a new comment', 'service_test', 'You have received a new comment');
                }
            }
        }
    }

    /*
     *Web service to compose message
     *@author Priti Kabra
     */
    public function api_composeMessage()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        $size = 0;
        $fileUploaded = array();
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
            $this->request->data['written_by_user'] = $this->loggedInUserId;
            $teamMember = array_unique(explode(",",urldecode($this->request->data['teamMembers'])));
            $recipientTotal = 0;
            foreach ($teamMember as $sendTo) {
                $recipientDetailArr[] = $this->BusinessOwner->find('first', array('conditions' => array('BusinessOwner.user_id' => $sendTo), 'fields' => array('BusinessOwner.fname', 'BusinessOwner.lname', 'BusinessOwner.email', 'User.device_id', 'User.device_token', 'User.device_type', 'BusinessOwner.notifications_enabled')));
                $recipientName[] = $recipientDetailArr[$recipientTotal]['BusinessOwner']['fname'] . " " . $recipientDetailArr[$recipientTotal]['BusinessOwner']['lname'];
                $recipientTotal++;
                $this->request->data['recipient_users'] = implode(', ',$recipientName);
            }
            $this->Message->create();
            if ($this->Message->save($this->request->data)) {
                $saveData['message_id'] = $this->Message->getLastInsertID();
                $notifId = 0;
                foreach ($teamMember as $sendTo) {
                    if (!empty($sendTo)) {
                        $saveData['recipient_user_id'] = $sendTo;
                        $this->MessageRecipient->create();
                        if ($this->MessageRecipient->save($saveData)) {
                            if ($recipientDetailArr[$notifId]['User']['device_type'] == "ios") {
                                $this->Common->iospushnotification($recipientDetailArr[$notifId]['User']['device_token'], "You have received a new message", 'service', "You have received a new message");
                            } elseif ($recipientDetailArr[$notifId]['User']['device_type'] == "android") {
                                $this->Common->androidpushnotification($recipientDetailArr[$notifId]['User']['device_token'], "You have received a new message", 'service', "You have received a new message");
                            }
                            // sent message live feed
                            $userGroupId = $this->BusinessOwner->find('first', array('conditions' => array('BusinessOwner.user_id' => $this->loggedInUserId), 'fields' => array('BusinessOwner.group_id')));
                            $this->LiveFeed->create();
                            $liveFeedData['LiveFeed']['to_user_id'] 	= $sendTo;
                            $liveFeedData['LiveFeed']['from_user_id'] 	= $this->loggedInUserId;
                            $liveFeedData['LiveFeed']['group_id']   = $userGroupId['BusinessOwner']['group_id'];
                            $liveFeedData['LiveFeed']['feed_type'] 		= "message";
                            $this->LiveFeed->save($liveFeedData);
                            $notificationEnable = explode(',', $recipientDetailArr[$notifId]['BusinessOwner']['notifications_enabled']);
                            $emailLib = new Email();
                            $template = "inbox_message";
                            $format = "html";
                            if (in_array('receiveMessage', $notificationEnable)) {
                                $variable = array('username' => $recipientName[$notifId], 'subject' => $this->request->data['subject'], 'content' => $this->request->data['content']);
                                $to = $recipientDetailArr[$notifId]['BusinessOwner']['email'];
                                $success = $emailLib->sendEmail($to, $this->request->data['subject'], $variable, $template, $format);
                                $notifId++;
                            }
                        }
                    }
                }
                $userInfo = $this->User->find('first', array('conditions' => array('User.id' => $this->loggedInUserId), 'fields' => array('BusinessOwners.notifications_enabled', 'User.*')));
                //Post on social media
                $socialConfig = explode(',', $userInfo['BusinessOwners']['notifications_enabled']);
                if (in_array('tweetMessageSend', $socialConfig) && $userInfo['User']['twitter_connected'] == 1) {                    
                    $statusMessage = "Just sent a message to " . $this->request->data['recipient_users'] . " via @Foxhopr";
                    $this->postToTwitter($userInfo, $statusMessage);
                }
                // Send Facebook Update
                if (in_array('fbMessageSend', $socialConfig) && $userInfo['User']['fb_connected'] == 1) {
                    $statusMessage = "Just sent a message to " . $this->request->data['recipient_users'] . " via http://foxhopr.com";
                    $this->postToFacebook($userInfo, $statusMessage);
                }
                // Send Linkedin Update
                if(in_array('linkedinMessageSend', $socialConfig) && $userInfo['User']['linkedin_connected'] == 1) {
                    $statusMessage = "Just sent a message to " . $this->request->data['recipient_users'] . " via http://foxhopr.com";
                    $this->postToLinkedin($userInfo, $statusMessage);
                }
                if (!empty($_FILES)) {
                    foreach ($_FILES as $file) {
                        if (!empty($file['name'])) {
                            $ext = substr(strtolower(strrchr($file['name'], '.')), 1);
                            $saveData['filename'] = $this->Common->getFileName($file['name']);
                            $filepath = WWW_ROOT . 'files/messages/' . $saveData['filename'];
                            if (in_array($ext, Configure::read('ARRAYEXT'))) {
                                move_uploaded_file($file['tmp_name'], $filepath);
                                $this->MessageAttachment->create();
                                $this->MessageAttachment->save($saveData);
                            } else {
                                $error = 1;
                                $errMsg = 'Please enter a file with a valid extension.';
                            }
                        }
                    }
                }
                $this->set(array(
                    'code' => Configure::read('RESPONSE_SUCCESS'),
                    'message' => 'Message has been sent successfully',
                    '_serialize' => array('code', 'message')
                ));
            } else {
                $this->errorMessageApi('Please try again.');
            }
        } else {
            $this->errorMessageApi($errMsg);
        }
    }

    /*
     *Web service to get the Message List
     *@author Priti Kabra
     */
    public function api_getMessage()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        if (!empty($this->jsonDecodedRequestedData->search_filter)) {
            $this->jsonDecodedRequestedData->search_filter = (strpos($this->jsonDecodedRequestedData->search_filter, '%') !== false) ? str_replace('%', '\%', $this->jsonDecodedRequestedData->search_filter) : $this->jsonDecodedRequestedData->search_filter;
            $this->jsonDecodedRequestedData->search_filter = (strpos($this->jsonDecodedRequestedData->search_filter, '_') !== false) ? str_replace('_', '\_', $this->jsonDecodedRequestedData->search_filter) : $this->jsonDecodedRequestedData->search_filter;
        }
        if ($error == 0) {
            $messageFilter = array();
            $fieldMessage = array();
            $sortData = '';
            $userId = $this->loggedInUserId;
            /* Listing conditions for inbox, sent, inbox archive and sent archive */
            $inboxArr = array('inbox', 'inboxArchive');
            $sentArr = array('sent', 'sentArchive');
            if (isset($this->jsonDecodedRequestedData->list_page) && in_array($this->jsonDecodedRequestedData->list_page, $inboxArr)) {
                $model = 'MessageRecipient';
                $fieldMessage = array('MessageRecipient.is_read', 'MessageRecipient.message_id', 'MessageRecipient.id', 'MessageRecipient.created', 'Message.id', 'Message.subject', 'Message.written_by_user', 'Message.is_read', 'BusinessOwners.fname', 'BusinessOwners.lname', 'Message.message_type');
                if ($this->jsonDecodedRequestedData->list_page == "inbox") {
                    $conditionsRequired = array('MessageRecipient.recipient_user_id' => $userId , 'MessageRecipient.is_archive' => 0);
                } elseif ($this->jsonDecodedRequestedData->list_page == "inboxArchive") {
                    $conditionsRequired = array('MessageRecipient.recipient_user_id' => $userId , 'MessageRecipient.is_archive' => 1);
                }
                /** Name filter for referrer name, sender's name and both*/
                if (!empty($this->jsonDecodedRequestedData->search_filter)) {
                    $messageFilter['OR'] = array(
                        "Message.subject LIKE" => "%" . trim($this->jsonDecodedRequestedData->search_filter) . "%",
                        "CONCAT(BusinessOwners.fname , ' ',BusinessOwners.lname) LIKE" => "%" . trim($this->jsonDecodedRequestedData->search_filter) . "%"
                    );
                }
            } elseif (isset($this->jsonDecodedRequestedData->list_page) && in_array($this->jsonDecodedRequestedData->list_page, $sentArr)) {
                $model = 'Message';
                $fieldMessage = array('Message.is_read', 'Message.subject', 'Message.recipient_users', 'Message.created', 'Message.message_type');
                if ($this->jsonDecodedRequestedData->list_page == "sent") {
                    $conditionsRequired = array('Message.written_by_user' => $userId , 'Message.is_archive' => 0, 'Message.message_type' => 'message');
                } elseif ($this->jsonDecodedRequestedData->list_page == "sentArchive") {
                    $conditionsRequired = array('Message.written_by_user' => $userId , 'Message.is_archive' => 1, 'Message.message_type' => 'message');
                }
                /** Name filter for referrer name, sender's name and both*/
                if (!empty($this->jsonDecodedRequestedData->search_filter)) {
                    $messageFilter['OR'] = array(
                        "Message.subject LIKE" => "%" . trim($this->jsonDecodedRequestedData->search_filter) . "%",
                        "Message.recipient_users LIKE" => "%" . trim($this->jsonDecodedRequestedData->search_filter) . "%"
                    );
                }
            }
            /** Fields array to be fetched from the database */
            $fields = array("$model.id");
            $fields = array_merge($fields, $fieldMessage);
            /** Sort data according to the sort filter */
            if (!empty($this->jsonDecodedRequestedData->sort_data) && !empty($this->jsonDecodedRequestedData->sort_direction)) {
                switch ($this->jsonDecodedRequestedData->sort_data) {
                    case "subject":
                        $sortData = "Message.".$this->jsonDecodedRequestedData->sort_data." ".$this->jsonDecodedRequestedData->sort_direction;
                        break;
                    case "recipient_users":
                        $sortData = "Message.".$this->jsonDecodedRequestedData->sort_data." ".$this->jsonDecodedRequestedData->sort_direction;
                        break;
                    case "fname":
                        $sortData = "BusinessOwners.".$this->jsonDecodedRequestedData->sort_data." ".$this->jsonDecodedRequestedData->sort_direction;
                        break;
                    case "created":
                        $sortData = "$model.created ".$this->jsonDecodedRequestedData->sort_direction;
                        break;
                    default:
                        $sortData = "$model.created DESC";
                        break;
                }
            } else {
                $sortData = "$model.created DESC";
            }
            $conditions = array_merge($conditionsRequired, $messageFilter);
            $messageList = $this->$model->find('all',
                                                array('conditions' => $conditions,
                                                      'fields' => $fields,
                                                      'order' => $sortData,
                                                      'recursive' => 1,
                                                      'limit' => $this->jsonDecodedRequestedData->record_per_page,
                                                      'page' => $this->jsonDecodedRequestedData->page_no
                                                )
                                              );
            $totalMessages = $this->$model->find('count',
                                                array('conditions' => $conditions,
                                                      'fields' => $fields,
                                                      'order' => "'$model.created DESC'",
                                                      'recursive' => 1
                                                )
                                              );
            //load Component
            $this->MessagesComponent = $this->Components->load('Messages');
            foreach ($messageList as $key => $value) {
                $attachmentCheck = $this->MessagesComponent->messageAttachment($messageList[$key]['Message']['id']);
				if (!empty($attachmentCheck)) {
					$messageData[$key][$model]['attachment'] = true;
				} else {
					$messageData[$key][$model]['attachment'] = false;
				}
                $messageData[$key][$model]['id'] = $messageList[$key][$model]['id'];
                $messageData[$key][$model]['subject'] = $messageList[$key]['Message']['subject'];
                $messageData[$key][$model]['message_type'] = $messageList[$key]['Message']['message_type'];
                $messageData[$key][$model]['is_read'] = $messageList[$key][$model]['is_read'];
                $messageData[$key][$model]['created'] = $messageList[$key][$model]['created'];
                if ($model == "MessageRecipient") {
                    $messageData[$key][$model]['written_by_user'] = $messageList[$key]['Message']['written_by_user'];
                    $messageData[$key][$model]['fname'] = $messageList[$key]['BusinessOwners']['fname'];
                    $messageData[$key][$model]['lname'] = $messageList[$key]['BusinessOwners']['lname'];
                } else {
                    $messageData[$key][$model]['recipient_users'] = $messageList[$key]['Message']['recipient_users'];
                }
            }
            if (!empty($messageData)) {
                foreach ($messageData as $key => $value) {
                    $list[] = $value[$model];
                }
                $this->set(array(
                    'code' => Configure::read('RESPONSE_SUCCESS'),
                    'result' => $list,
                    'message' => 'Message List.',
                    'page_no' => $this->jsonDecodedRequestedData->page_no,
                    'totalMessages' => "$totalMessages",
                    '_serialize' => array('code', 'result', 'message', 'page_no', 'totalMessages')
                ));
            } else {
                $this->errorMessageApi('');
            }
        } else {
            $this->errorMessageApi($errMsg);
        }
    }

    /*
     *Web service to delete the Message
     *@author Priti Kabra
     */
    public function api_deleteMessage()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        $success = 0;
        if (!empty($this->jsonDecodedRequestedData->deleteId) && !empty($this->jsonDecodedRequestedData->listPage)) {
            if (in_array($this->jsonDecodedRequestedData->listPage, array("sent", "sentArchive"))) {
                $model = 'Message';
            } elseif (in_array($this->jsonDecodedRequestedData->listPage, array("inbox", "inboxArchive"))) {
                $model = 'MessageRecipient';
            }
            $deleteID = explode(",",urldecode($this->jsonDecodedRequestedData->deleteId));
            $error = $this->$model->checkMessageExist($deleteID);
            if ($error == 0) {
                if (in_array($this->jsonDecodedRequestedData->listPage, array("sent", "inbox"))) {
                    foreach ($deleteID as $deleteMessageId) {
                        $this->$model->id = $this->Encryption->decode($deleteMessageId);
                        $updateData['is_archive'] = 1;
                        if ($this->$model->save($updateData)) {
                            $success = 1;
                            $successMsg = " Message(s) has been moved to " . $this->jsonDecodedRequestedData->listPage . " archive successfully.";
                        }
                    }
                } elseif (in_array($this->jsonDecodedRequestedData->listPage, array("inboxArchive", "sentArchive"))) {
                    foreach ($deleteID as $deleteMessageId) {
                        $deleteData['id'] = $this->Encryption->decode($deleteMessageId);
                        if ($this->$model->delete($deleteData['id'])) {
                            $success = 1;
                            $successMsg = "Message(s) has been permanently deleted successfully";
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
                $errMsg = isset($errMsg) ? $errMsg : "Message does not exist.";
                $this->errorMessageApi($errMsg);
            }
        }
    }

    /*
     *Web service to get the details of the message
     *@author Priti Kabra
     */
    public function api_messageDetail()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        if (empty($this->jsonDecodedRequestedData->messageId) || empty($this->jsonDecodedRequestedData->detailPage)) {
            $error = 1;
            $errMsg = "Please provide all parameters.";
        }
        $mId = $this->Encryption->decode($this->jsonDecodedRequestedData->messageId);
        $model = in_array($this->jsonDecodedRequestedData->detailPage, array("inbox", "inboxArchive")) ? 'MessageRecipient' : 'Message';
        $this->$model->id = $mId;
        if (!$this->$model->exists()) {
            $error = 1;
            $errMsg = "Message does not exist.";
        }
        if ($error == 0) {
            if ($model == "MessageRecipient") {
                $this->MessageRecipient->id = $mId;
                $updateData['is_read'] = 1;
                $updateData['is_total_read'] = 1;
                $this->MessageRecipient->save($updateData);
                $messageId = $this->MessageRecipient->find('first', array('conditions' => array('MessageRecipient.id' => $mId), 'fields' => array('MessageRecipient.message_id')));
                $mId = $messageId['MessageRecipient']['message_id'];
            } else {
                $this->Message->id = $mId;
                $updateData['is_read'] = 1;
                $updateData['is_total_read'] = 1;
                $this->Message->save($updateData);
            }
            $messageData = $this->Message->find('first', array(
                                                    'conditions' => array('Message.id' => $mId)
                                                    )
                                                );
            $this->MessageRecipient->bindModel(
                array('hasOne' => array(
                    'BusinessOwner' => array(
                        'className' => 'BusinessOwner',
                        'foreignKey' => false,
                        'conditions' => array('BusinessOwner.user_id = MessageRecipient.recipient_user_id')/*,
                        'fields' => array('BusinessOwner.fname', 'BusinessOwner.lname', 'BusinessOwner.user_id')*/
                    )
                ))
            );
            $messageRecipient = $this->MessageRecipient->find('all', array('conditions' => array('MessageRecipient.message_id' => $mId), 'fields' => array('MessageRecipient.recipient_user_id', 'BusinessOwner.fname', 'BusinessOwner.lname', 'BusinessOwner.user_id', 'BusinessOwner.email', 'Message.Created')));
            foreach ($messageRecipient as $mR) {
                $receiversName[]=ucfirst($mR['BusinessOwner']['fname']). " ".ucfirst($mR['BusinessOwner']['lname']);
            }
            $msgRcvr = 0;
            $recipeints = array();
            foreach ($messageRecipient as $mRecipients) {
                if ($mRecipients['MessageRecipient']['recipient_user_id'] == $this->loggedInUserId) {
                    $messageDetail['Receivers'][] = "me";
                }
            }
            if (count($receiversName)>0) {
                foreach ($messageRecipient as $mRecipients) {
                    if ($mRecipients['MessageRecipient']['recipient_user_id'] != $this->loggedInUserId) {
                        $messageDetail['Receivers'][] = $receiversName[$msgRcvr];	              		
                    }
                    $msgRcvr++;
                }
            }
            $messageDetail['ReceiversName'] = implode(", ",$messageDetail['Receivers']);
            if ($this->loggedInUserId == $messageData['Message']['written_by_user']) {
                foreach ($messageRecipient as $msgRecipient) {
                    $messageDetail['sendMailTo'][] = $msgRecipient['BusinessOwner']['email'];
                }
            } else {
                $messageDetail['sendMailTo'][] = $messageData['User']['username'];
            }
            $messageDetail['sendMailTo'] = implode(",",$messageDetail['sendMailTo']);
            $messageAttachments = $this->MessageAttachment->find('all', array('conditions' => array('MessageAttachment.message_id' => $mId)));
            if (!empty($messageData)) {
                $messageDetail['subject'] = $messageData['Message']['subject'];
                $messageDetail['content'] = $messageData['Message']['content'];
                $messageDetail['created'] = $messageData['Message']['created'];
                $messageDetail['message_type'] = $messageData['Message']['message_type'];
                if (!empty($messageAttachments)) {
                    $i = 0;
                    foreach ($messageAttachments as $files) {
                        $messageDetail['files'][$i]['url'] = Configure::read('SITE_URL') . 'files/messages/' .$files['MessageAttachment']['filename'];
                        $messageDetail['files'][$i]['name'] = substr($files['MessageAttachment']['filename'], 19);
                        $i++;
                    }
                } else {
                    $messageDetail['files'] = array();
                }
                $messageDetail['user_name'] = $messageData['BusinessOwner']['fname'] . " ". $messageData['BusinessOwner']['lname'];
                $this->set(array(
                    'code' => Configure::read('RESPONSE_SUCCESS'),
                    'result' => $messageDetail,
                    'message' => 'Message Detail.',
                    '_serialize' => array('code', 'result', 'message')
                ));
            } else {
                $this->errorMessageApi('Message does not exist.');
            }
        } else {
            $this->errorMessageApi($errMsg);
        }
    }

    /*
     *Web service to get the list of the comments
     *@author Priti Kabra
     */
    public function api_messageComment()
    {
        $mId = $this->Encryption->decode($this->jsonDecodedRequestedData->messageId);
        $model = in_array($this->jsonDecodedRequestedData->detailPage, array("inbox", "inboxArchive")) ? 'MessageRecipient' : 'Message';
        if ($model == "MessageRecipient") {
            $messageId = $this->MessageRecipient->find('first', array('conditions' => array('MessageRecipient.id' => $mId), 'fields' => array('MessageRecipient.message_id')));
            $mId = $messageId['MessageRecipient']['message_id'];
        }
        $commentField = array('MessageComment.*', 'BusinessOwner.user_id', 'BusinessOwner.fname', 'BusinessOwner.lname', 'BusinessOwner.profile_image');
        $messageComment = $this->MessageComment->find('all', array('conditions' => array('MessageComment.message_id' => $mId), 'fields' => $commentField));
        if (!empty($messageComment)) {
            foreach ($messageComment as $key => $value) {
                $messageComment[$key]['MessageComment']['commented_by'] = $messageComment[$key]['BusinessOwner']['fname'] . " ". $messageComment[$key]['BusinessOwner']['lname'];
                $profile_image = !empty($messageComment[$key]['BusinessOwner']['profile_image']) ? 'uploads/profileimage/'.$messageComment[$key]['MessageComment']['commented_by_id'].'/'.$messageComment[$key]['BusinessOwner']['profile_image'] : 'no_image.png';
                $messageComment[$key]['MessageComment']['commented_by_profile_image'] = Configure::read('SITE_URL') . 'img/' . $profile_image;
            }
            foreach ($messageComment as $key => $value) {
                $messageCommentList[] = $value['MessageComment'];
            }
            $this->set(array(
                    'code' => Configure::read('RESPONSE_SUCCESS'),
                    'result' => $messageCommentList,
                    'message' => 'Message Comment Detail.',
                    '_serialize' => array('code', 'result', 'message')
                ));
        } else {
            $this->errorMessageApi('No comment.');
        }
    }

    /*
     *Web service to add comment on the message detail
     *@author Priti Kabra
     */
    public function api_addMessageComment()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        if ( empty($this->jsonDecodedRequestedData->messageId) || empty($this->jsonDecodedRequestedData->detailPage)) {
            $error = 1;
            $errMsg = "Please provide all parameters.";
        }
        if ($error == 0) {
            $mId = $this->Encryption->decode($this->jsonDecodedRequestedData->messageId);
            $model = in_array($this->jsonDecodedRequestedData->detailPage, array("inbox", "inboxArchive")) ? 'MessageRecipient' : 'Message';
            if ($model == "MessageRecipient") {
                $messageId = $this->MessageRecipient->find('first', array('conditions' => array('MessageRecipient.id' => $mId), 'fields' => array('MessageRecipient.message_id')));
                $mId = $messageId['MessageRecipient']['message_id'];
                $this->jsonDecodedRequestedData->messageId = $this->Encryption->encode($mId);
            }
            $saveData['message_id'] = $mId;
            $saveData['type'] = in_array($this->jsonDecodedRequestedData->detailPage, array("inbox", "inboxArchive")) ? 'received' : 'sent';
            $saveData['commented_by_id'] = $this->loggedInUserId;
            $saveData['comment'] = $this->jsonDecodedRequestedData->comment;
            $this->MessageComment->create();
            if ($this->MessageComment->save($saveData)) {
                $commentLastId =  $this->MessageComment->getInsertID();
                $condition = array(
                              'MessageComment.message_id' => $mId,
                              'MessageComment.commented_by_id' => $this->loggedInUserId,
                              'MessageComment.id' => $commentLastId,
                              );
                $messageComment = $this->MessageComment->find('first', array('conditions' => $condition));
                $senderName=$messageComment['BusinessOwner']['fname']." ".$messageComment['BusinessOwner']['lname'];
                $commentSaveData['mid'] = $this->jsonDecodedRequestedData->messageId;
                $commentSaveData['type'] = ($saveData['type'] == 'sent') ? 'Message' : 'MessageRecipient';
                $commentSaveData['MessageComment']['commented_by_id'] = $this->loggedInUserId;
                $commentSaveData['MessageComment']['comment'] = $this->jsonDecodedRequestedData->comment;
                $commentSaveData['sendMailTo'] = $this->jsonDecodedRequestedData->sendMailTo;
                $this->addMessageData($commentSaveData, $senderName);
                $sendMailToUsers = explode(',', $this->jsonDecodedRequestedData->sendMailTo);
                foreach ($sendMailToUsers as $sendMailToAB) {
                    if (!empty($sendMailToAB)) {
                        $userData = $this->BusinessOwner->findByEmail($sendMailToAB);
                        $notificationEnable = explode(',', $userData['BusinessOwner']['notifications_enabled']); 
                        if (in_array('commentMadeOnMessage', $notificationEnable)) {
                            $sendType = $saveData['type'] == 'sent' ? 'received' : 'sent';
                            $emailLib = new Email();
                            $subject = "Message comment received";
                            $template = "message_comment";
                            $format = "both";
                            $business_owner_name = $senderName;
                            $url = Configure::read('SITE_URL') . 'messages/viewMessage/' . $this->jsonDecodedRequestedData->messageId;
                            $variable = array('businessowner'=>$userData['BusinessOwner']['fname'].' '.$userData['BusinessOwner']['lname'], 'username' => $business_owner_name, 'type' => $sendType, 'comment' => htmlentities($this->jsonDecodedRequestedData->comment), 'url' => $url);
                            if (!empty($sendMailToAB)) {                          
                                $to = $sendMailToAB;
                                $success = $emailLib->sendEmail($to,$subject,$variable,$template,$format);
                            }
                        }
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

    /*
     *Web service to delete the Message
     *@author Priti Kabra
     */
    public function api_changeMessageStatus()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        $success = 0;
        if (!empty($this->jsonDecodedRequestedData->messageId) && !empty($this->jsonDecodedRequestedData->listPage) && !empty($this->jsonDecodedRequestedData->messageStatus)) {
            if (in_array($this->jsonDecodedRequestedData->listPage, array("sent", "sentArchive"))) {
                $model = 'Message';
            } elseif (in_array($this->jsonDecodedRequestedData->listPage, array("inbox", "inboxArchive"))) {
                $model = 'MessageRecipient';
            }
            $messageID = explode(",",urldecode($this->jsonDecodedRequestedData->messageId));
            $error = $this->$model->checkMessageExist($messageID);
            if ($error == 0) {
                foreach ($messageID as $messageIdStatus) {
                    $this->$model->id = $this->Encryption->decode($messageIdStatus);
                    $updateStatus = ($this->jsonDecodedRequestedData->messageStatus == "read") ? 1 : 0;
                    $updateStatusMsg = ($this->jsonDecodedRequestedData->messageStatus == "read") ? "Selected message(s) have been marked as read." : "Selected message(s) have been marked as unread.";
                    if ($this->$model->saveField('is_read', $updateStatus)) {
                        $this->set(array(
                            'code' => Configure::read('RESPONSE_SUCCESS'),
                            'message' => $updateStatusMsg,
                            '_serialize' => array('code', 'message')
                        ));
                    }
                }
            } else {
                $errMsg = isset($errMsg) ? $errMsg : "Message does not exist.";
                $this->errorMessageApi($errMsg);
            }
        }
    }
}