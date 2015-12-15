<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');
App::uses('CakeNumber', 'Utility');
/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller 
{
  /*public $paginate = array(
        'order' => array('Profession.created' => 'desc')
    );*/
    public $helpers = array('Js','Functions');
    public $components = array('Auth', 'Session', 'Encryption', 'Mail', 'Cookie', 'RequestHandler','Common','Businessowner','Functions','Groups');
    public $uses = array('User', 'BusinessOwner','GroupChangeRequest');

    /**
     * To filter url request
     * @author Gaurav
     */
    public function beforeFilter() 
    {
        $headersInformation = getallheaders();
        // admin email        
        $this->set('AdminEmail',AdminEmail);
       
        if (isset($this->params['prefix']) && $this->params['prefix'] == 'admin') {
        	//$this->Auth->loginRedirect = array('plugin' => false, 'controller' => 'dashboard', 'action' => 'index');
        	$this->layout = 'admin';
        	AuthComponent::$sessionKey = 'Auth.User';
        	$this->isAdmin = TRUE;
        	Configure::write('isAdmin', TRUE);
        }  else {
        	//$this->Auth->loginRedirect = array('plugin' => false, 'controller' => 'pages', 'action' => 'home');
        	$this->layout = 'front';
        	// get group change request status
        	$bizOwnerId = $this->Session->read('Auth.Front.BusinessOwners.id');
        	//$crCount = $this->Session->read('Auth.Front.BusinessOwners.group_change');
        	//$this->set(compact('crCount'));
        	Configure::write('isAdmin', FALSE);
        	AuthComponent::$sessionKey = 'Auth.Front';
        }
         
        $roleType = $this->Session->read('Auth.User.user_type');
        $this->set('common', $this->Common);
        
        $frontUserRole = $this->Session->read('Auth.Front.user_type');
        $isUserLogin = ($frontUserRole=="businessOwner") ? true : false;        
        $this->set(compact("isUserLogin"));
        
        $loginUserId = $this->Session->read('Auth.Front.id');        
        $this->set(compact("loginUserId"));
        
        if (empty($headersInformation['HASHKEY']) && $this->params['prefix'] != 'api') {
            // redirect session after login
            $checkUrl = Router::fullbaseUrl().$this->here;
            if (strpos($checkUrl,  Configure::read('SITE_URL') . 'referrals/referralDetails/sent/') !== false) {
                $this->Session->write('BackUrlAfterLogin', $checkUrl);
            } elseif (strpos($checkUrl,  Configure::read('SITE_URL') . 'referrals/referralDetails/received/') !== false) {
                $this->Session->write('BackUrlAfterLogin', $checkUrl);
            } elseif (strpos($checkUrl,  Configure::read('SITE_URL') . 'messages/viewMessage/') !== false) {
                $this->Session->write('BackUrlAfterLogin', $checkUrl);
            } elseif (strpos($checkUrl,  Configure::read('SITE_URL') . 'reviews/index') !== false) {
                $this->Session->write('BackUrlAfterLogin', $checkUrl);
            } elseif (strpos($checkUrl,  Configure::read('SITE_URL') . 'meetings') !== false) {
                $this->Session->write('BackUrlAfterLogin', $checkUrl);
            }
            
            if (empty($roleType)) {
                $this->Auth->allow(array(
                    'admin_login',
                    'admin_forgotPassword',
                    'admin_resetPassword',
                    'home',
                    'login',
                    'subscribe',
                    'aboutUs',
                    'contactUs',
                    'privacyPolicy',
                    'termsOfServices',
                    'careers',
                    'partners',
                    'faq',
                    'faqView',
                    'faqSearch',
                    'getCountryList',
                    'getCountryName',
                    'getStateList',
                    'getStateName',
                    'trainingVideoReminderMail',
                    'recurringTransaction',
                    'rating',
                    'deactivateUser',
                    'getProfessionList'
                  )
                );
            }
        } else if($this->params['prefix'] == 'api') {
            $this->RequestHandler->ext = Configure::read('SERVICEFORMAT');
            if (!isset($headersInformation['HASHKEY']) || $headersInformation['HASHKEY'] != Configure::read('HASHKEY')) {
                echo json_encode(array('code'=>Configure::read('RESPONSE_ERROR'),'message' => 'Invalid Hash Key'));die;
            }
            $this->Auth->allow($this->action);

            //API Post Data in Json
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $this->__getPostContent();
            }
            //fetch the headers data
            $this->__getHeaderInformation();
        }
        
        // Login user get counter for different entity (ex- message, referrals etc.)
        if(empty($this->request->data) && empty($this->request->form))
        {
            $this->Common->clearDropzoneData();
        }
        $messageCounter = 0;
		$referalCounter = 0;
        if($loginUserId!=""){
		    $loginUserId = $this->Encryption->decode($loginUserId);
			$userGroup = $this->Groups->getUserGroupId($loginUserId);

			// logout user after shuffling
			$beforeShuffling = $this->Session->read('Auth.Front.BusinessOwner.group_id');
			$afterShuffling = $userGroup['BusinessOwner']['group_id'];
			if(!empty($beforeShuffling) && $beforeShuffling!=$afterShuffling){
				$this->Auth->logout();
			}

            if($this->action=="viewMessage") {
                if (isset($this->params->pass[0])) {
                    $messageId = $this->Encryption->decode($this->params->pass[0]);
                    $this->loadModel('MessageRecipient');
                     $this->MessageRecipient->updateAll(
                          array('MessageRecipient.is_read' => 1, 'MessageRecipient.is_total_read' => 1),
                          array('MessageRecipient.message_id' => $messageId, 'MessageRecipient.recipient_user_id' => $loginUserId)
                      );
                }
            }
            if($this->action=="referralDetails") {
                if (isset($this->params->pass[0]) && isset($this->params->pass[1])) {
                    if ($this->params->pass[0] == "received") {
                        $referralId = $this->Encryption->decode($this->params->pass[1]);
                        $this->loadModel('ReceivedReferral');
                        $this->ReceivedReferral->updateAll(
                              array('ReceivedReferral.is_read' => 1, 'ReceivedReferral.is_total_read' => 1),
                              array('ReceivedReferral.id' => $referralId, 'ReceivedReferral.to_user_id' => $loginUserId)
                        );    
                    }
                }
            }
        	$messageCounter = $this->Common->unreadCounter('messages',$loginUserId); 
            $referalCounter = $this->Common->unreadCounter('referrals',$loginUserId);  
            $this->set('userGroup',$userGroup['BusinessOwner']['group_id']);
               
            // get profile picture path
            $profileImage = $this->Businessowner->getProfilePicture($loginUserId);
            $this->set(compact("profileImage"));
            
            // get login user info
            $loginUserInfo = $this->BusinessOwner->findByUserId($loginUserId);
            $loginUserName = $loginUserInfo['BusinessOwner']['fname'].' '.$loginUserInfo['BusinessOwner']['lname'];
            $loginUserRole = $loginUserInfo['BusinessOwner']['group_role'];
            $this->set(compact("loginUserName", "loginUserRole", "loginUserInfo"));
        }
        $this->set(compact("messageCounter"));
        $this->set(compact("referalCounter"));   
        if($this->request->is('ajax')) {
            $ajaxRinningUrl = parse_url($this->referer());
            $serverUrl = parse_url(Configure::read('SITE_URL'));
            if($ajaxRinningUrl['host'] != $serverUrl['host']){
                $result = array(
                    'response' => __('Unauthorize Access'),
                    'responsecode' => Configure::read('RESPONSE_ERROR'),
                    );
                echo json_encode($result);die;
            }
        }   
    }
    
    /**
     * Filter apply before render
     * @author Laxmi Saini
     */
    public function beforeRender() 
    {
        $this->response->disableCache();
        if ($this->name == 'CakeError') {
          $this->layout = 'error';
        }
        // show advertisements
        $professionId = $this->Session->read('Auth.Front.BusinessOwners.profession_id'); 
        if($this->params['prefix'] != 'admin'){     
	        $bottomAds = $this->Businessowner->getAdvertisement(0,$professionId);
	        $rightAds  = $this->Businessowner->getAdvertisement(1,$professionId);	        
	        $this->set(compact('bottomAds','rightAds'));
        }
    }

    /*
     * Function to get post data and convert it into json format.
     *@author Priti Kabra
     */
    private function __getPostContent(){
        $data = file_get_contents("php://input");
        $this->jsonDecodedRequestedData = json_decode($data);
    }

    /*
     * Function to get header information.
     *@author Priti Kabra
     */
    private function __getHeaderInformation()
    {
        $headersInformation = getallheaders();
        if (isset($headersInformation['UserId'])) {
            $this->loggedInUserId = $this->Encryption->decode($headersInformation['UserId']);
        }
        if (isset($headersInformation['groupId'])) {
            $this->loggedInUserGroupId = $headersInformation['groupId'];
        }
        if (!empty($headersInformation['DeviceToken']) && !empty($headersInformation['DeviceId']) && !empty($headersInformation['DeviceType'])) {
            $this->DeviceToken = $headersInformation['DeviceToken'];
            $this->DeviceId = $headersInformation['DeviceId'];
            $this->DeviceType = $headersInformation['DeviceType'];
        }
    }
    
    /**
     *Function for web service to get the count of unread messages, unread referrals and total
     *@return totalUnread, messageTotalUnread, referralTotalUnread, referralUnread, messageUnread, userloggedInStatus
     *@author Priti Kabra
     */
    public function api_getUnreadCount()
    {
        $this->loadModel('ReceivedReferral');
        $this->loadModel('MessageRecipient');
        $unread['referralUnread'] = $this->ReceivedReferral->find('count', array('conditions' => array('ReceivedReferral.to_user_id' => $this->loggedInUserId, 'ReceivedReferral.is_read' => 0, 'ReceivedReferral.is_archive' => 0)));
        $unread['messageUnread'] = $this->MessageRecipient->find('count', array('conditions' => array('MessageRecipient.recipient_user_id' => $this->loggedInUserId, 'MessageRecipient.is_read' => 0, 'MessageRecipient.is_archive' => 0)));
        $unread['referralTotalUnread'] = $this->ReceivedReferral->find('count', array('conditions' => array('ReceivedReferral.to_user_id' => $this->loggedInUserId, 'ReceivedReferral.is_total_read' => 0, 'ReceivedReferral.is_archive' => 0)));
        $unread['messageTotalUnread'] = $this->MessageRecipient->find('count', array('conditions' => array('MessageRecipient.recipient_user_id' => $this->loggedInUserId, 'MessageRecipient.is_total_read' => 0, 'MessageRecipient.is_archive' => 0)));
        $unread['total'] = $unread['referralTotalUnread'] + $unread['messageTotalUnread'];
        //to check the user is logged in
        $fields = array('User.id', 'BusinessOwners.group_id', 'BusinessOwner.fname', 'BusinessOwner.lname', 'User.deactivated_by_user', 'BusinessOwner.group_role');
        $conditions = array('User.id' => $this->loggedInUserId, 'User.device_token' => $this->DeviceToken, 'User.deactivated_by_user' => 0);
        $userLogStatus = $this->User->find('first', array('conditions' => $conditions, 'fields' => $fields));
        $unread['is_login'] = !empty($userLogStatus) ? 'yes' : 'no' ;
        $unread['user_name'] = !empty($userLogStatus) ? $userLogStatus['BusinessOwner']['fname'] . " " . $userLogStatus['BusinessOwner']['lname'] : '' ;
        $unread['user_role'] = !empty($userLogStatus) ? $userLogStatus['BusinessOwner']['group_role'] : '' ;
        $this->set(array(
            'code' => Configure::read('RESPONSE_SUCCESS'),
            'result' => $unread,
            'message' => 'Unread counts.',
            '_serialize' => array('code', 'result', 'message')
        ));
    }

    /**
     *Function for delete pop up on referrals, mesasge, contacts and group
     *@author Priti Kabra
     */
    public function popupFunction()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $this->set('id', $this->request->data['id']);           
            $this->set('controller', $this->request->data['controller']);
            $this->set('action', $this->request->data['action']);
            $this->set('UID', $this->request->data['listPage']);
            if ($this->request->data['action'] == "selectGroup" || $this->request->data['action'] == "updateGroup") {
                $this->set('listPage', 'UID');
            } else {
                $this->set('listPage', $this->request->data['listPage']);
            }
            $this->set('perPage', 10);
            $this->render('/Elements/Front/action_popup', 'ajax');
        }
    }

    /**
     *Function for mass action pop up on referrals, mesasge, contacts, teams
     *@author Priti Kabra
     */
    public function massActionFunction()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $this->set('formId', $this->request->data['formId']);
            $this->set('controller', $this->request->data['controller']);
            $this->set('action', $this->request->data['action']);
            $this->set('listPage', $this->request->data['listPage']);
            $this->render('/Elements/Front/mass_action_popup', 'ajax');
        }
    }

    /**
     *Function for web service to mark as read all unread messages and referrals on total counter
     *@author Priti Kabra
     */
    public function api_readTotal()
    {
        $this->loadModel('MessageRecipient');
        $this->loadModel('ReceivedReferral');
        if (!empty($this->loggedInUserId)) {
            $this->MessageRecipient->updateAll(
                array('MessageRecipient.is_total_read' => 1),
                array('MessageRecipient.recipient_user_id' => $this->loggedInUserId)
            );
            $this->ReceivedReferral->updateAll(
                array('ReceivedReferral.is_total_read' => 1),
                array('ReceivedReferral.to_user_id' => $this->loggedInUserId)
            );
            $this->set(array(
                'code' => Configure::read('RESPONSE_SUCCESS'),
                'message' => 'Updated successfully.',
                '_serialize' => array('code', 'message')
            ));
        } else {
            $this->set(array(
                'code' => Configure::read('RESPONSE_ERROR'),
                'message' => 'Please try again.',
                '_serialize' => array('code', 'message')
            ));
        }
        
    }
    /**
     * Function to post Message to Twitter on various actions
     * @param array $userData
     * @param string $statusMessage
     * @author Rohan Julka
     * */
	public function postToTwitter($userData,$statusMessage)
    {
        require_once (ROOT.DS.APP_DIR.DS.'Plugin/twitteroauth/twitteroauth.php');
        $consumer_key=Configure::read('twitter_consumer_key');
        $consumer_secret=Configure::read('twitter_consumer_secret');
        $oauth_token=$userData['User']['twitter_oauth_token'];
        $oauth_token_secret=$userData['User']['twitter_oauth_token_secret'];
        $connection = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token,$oauth_token_secret);
        $sent=$connection->post('statuses/update', array('status' => $statusMessage));
    }
    /**
     * Function to post Message to Facebook on various actions
     * @param array $userData
     * @param string $statusMessage
     * @author Rohan Julka
     * */
    public function postToFacebook($userData,$statusMessage)
    {
        require_once (ROOT.DS.APP_DIR.DS.'Plugin/facebook/facebook.php');
        $facebookData = array();
        $facebookData['appId'] = Configure::read('appId');
        $facebookData['secret'] = Configure::read('appSecret');
        $connection = new Facebook($facebookData);
        $connection->setAccessToken($userData['User']['fb_access_token']);
        $params = array();
        $params["access_token"] = $userData['User']['fb_access_token'];
        $params["message"] = $statusMessage;
        $params["name"] = $statusMessage;
        $params["description"] = $statusMessage;
        try{
            $connection->api('/me/feed', 'POST', $params);
        } catch(Exception $e) {
            $this->Session->setFlash('Unable to post to Facebook','Front/flash_bad');
        }
    }
    /**
     * Function to post Message to Linkedin on various actions
     * @param array $userData
     * @param string $statusMessage
     * @author Rohan Julka
     * */
    public function postToLinkedin($userData,$statusMessage)
    {
        require_once (ROOT.DS.APP_DIR.DS.'Plugin/linkedin/linkedin.php');
        $ln = new LinkedIn(Configure::read('linkedinApiKey'), Configure::read('linkedinApiSecret'));
        $ln->setTokenData($userData['User']['linkedin_access_token']);
        //$user = $ln->fetch('GET', '/v1/people/~:(firstName,lastName)');
        //print "Hello $user->firstName $user->lastName.";
        try{
            $ln->fetch('POST','/v1/people/~/shares', array( 'comment' => $statusMessage, 'visibility' => array('code' => 'anyone' ) ) );
        }
        catch(Exception $e){
            $this->Session->setFlash('Unable to post to Linkedin','Front/flash_bad');
        }
        
    }
    /*
     * Function to get country list
     * @author Gaurav Bhandari
     */
    public function getCountryList()
    { 
        $this->autoRender = false;
        $countryName = $this->request->data('country');
        $result = $this->Common->getCountries($countryName);
        $a_json = array();
        $a_json_row = array();
        foreach($result as $data) {            
          $a_json_row["label"] = $data['Country']['country_name'];
          $a_json_row["value"] = $data['Country']['country_iso_code_2'];
          array_push($a_json, $a_json_row);
        }
        return json_encode($a_json);
    }

    /*
     * Function to get state list
     * @author Gaurav Bhandari
     */
    public function getStateList()
    {
        $this->autoRender = false;
        $countryName = $this->request->data('country');
        $stateName = $this->request->data('state');
        $result = $this->Common->getStates($countryName,$stateName);
        $a_json = array();
        $a_json_row = array();
        foreach($result as $data) {            
          $a_json_row["label"] = $data['State']['state_subdivision_name'];
          $a_json_row["value"] = $data['State']['state_subdivision_id'];
          array_push($a_json, $a_json_row);
        }
        return json_encode($a_json);
    }
    /**
     * Function to setup Popup Variables.
     * @param string $action
     * @param string $info
     * @param string $data
     * @author Rohan Julka
     * */
    public function parsePopupVars($action,$info='',$data='')
    {
        $response = array();
        $response['firstButtonLabel'] = 'Cancel';
        $response['headerMsg'] = 'Delete Confirmation';
        $response['secondButtonDisplay'] ='';
        switch ($action) {
            case "approve":
                $response['headerMsg'] = 'Approve Confirmation';
                $response['actionMessage'] = "Do you want to approve the ".$info."?";
                break;
        
            case "activate":
                $response['headerMsg'] = 'Activate Confirmation';
                $response['actionMessage'] = "Do you want to activate the ".$info."?";
                break;
        
            case "delete":
                $response['actionMessage'] = "Do you want to delete the ".$info."?";
                break;
        
            case "deleteCategory":
                $response['actionMessage'] = " The category may consists of FAQs. Do you want to delete the category?";
                break;
        
            case "cannotDeleteProfession":
                $response['actionMessage'] = "Cannot complete the operation as profession is already in use.";
                $response['firstButtonLabel'] ='Ok';
                $response['secondButtonDisplay'] ="display:none";
                break;
        
            case "cannotDeleteGroup":
                $response['actionMessage'] = "No sufficient groups available for the members";
                $response['firstButtonLabel'] ='Ok';
                $response['secondButtonDisplay'] ="display:none";
                break;
        
            case "moveGroupMembers":
                $response['actionMessage'] = "Group consist of members. Do you still want to delete the group?";
                break;
        
            case "status":
                $response['headerMsg'] = $data.' Confirmation';
                $response['actionMessage'] = "Do you want to ".$data." the ".$info."?";
                break;
            case "cannotActiveStatus":
                $response['headerMsg'] ='Activate Confirmation';
                $response['actionMessage'] = "Cannot activate this coupon as only one public coupon can be active at a time.";
                $response['firstButtonLabel'] ='Ok';
                $response['secondButtonDisplay'] ="display:none";
                break;
            case "subscriptionDelete":
                $response['actionMessage'] = "Do you want to delete the ".$info."?";
                break;
            default:
                $response['headerMsg'] =" confirmation";
                $response['actionMessage'] = "Do you want to perform this action?";
        }
        return $response;
    }
    /**
     * Function to combine all Server side errors into list
     * @param string $model, Specifies name of the model 
    * @author Rohan Julka
    */
    public function compileErrors($model)
    {
        $errors = NULL;
        if (!empty($this->$model->validationErrors)) {
            $errors = '<ul>';
            foreach ($this->$model->validationErrors as $key=>$err) {
                $errors.='<li>'.$err[0].'</li>';
            }
            $errors.='</ul>';            
        }
        return $errors;
    }

	/**
	* check header values exist and logged in user exist and is active
	* @author Priti Kabra
	* @return string $errorMsg
	*/
    public function checkApiHeaderInfo()
    {
        $errorMsg = NULL;
        if (!isset($this->DeviceToken) || !isset($this->DeviceId) || !isset($this->DeviceType)) {
            $errorMsg = "Please provide device type, device token.";
        }
        $this->loadModel('User');
        //check User Exists
        if (!$this->User->exists($this->loggedInUserId) || !$this->User->checkUserIsActive($this->loggedInUserId)) {
            $errorMsg = "Please provide UserId.";
        }
        return $errorMsg;
    }
    
    /**
     * function used to return error message and code in API
     * @return string $errMsg
     * @author Priti Kabra
     */
    public function errorMessageApi($errMsg)
    {
        $this->set(array(
            'code' => Configure::read('RESPONSE_ERROR'),
            'message' => !empty($errMsg) ? $errMsg : '',
            '_serialize' => array('code', 'message')
        ));
    }

    /*
     * Function to get profession list by category
     * @author Gaurav Bhandari
     */
    public function getProfessionList()
    {
    	$categoryId = $this->request->data['catID'];
        $this->autoRender = false;
        $result = $this->Profession->getAllProfessions($categoryId);     
        return json_encode($result);
    }
}