<?php

/**
 * This is UsersController used for user activities
 * 
 */
App::uses('Email', 'Lib');
class UsersController extends AppController 
{
    public $includePageJs='';
    public $breezSessionData = '';
    
    /**
     * Components
     *
     * @var array
     * @access public
     */
    public $components = array(
    		'Email', 'Common', 'Profession', 'Timezone','GroupGoals','Adobeconnect'
    );
    
    /**
     * Models used by the Controller
     *
     * @var array
     * @access public
     */
    public $uses = array('BusinessOwner','Profession','User','NewsletterSubscribe','InvitePartner','CreditCard','Affiliate', 'Review', 'Membership', 'ReferralStat', 'Subscription', 'Group');
    
    /**
     * callback function
     * @author Gaurav Bhandari
     */
    public function beforeFilter() 
    {
        parent::beforeFilter();
        if($this->request->params['action'] == 'signUp'){
        	$adobeData = $this->__checkAdobeConnectValidLogin();
        	if($adobeData != 'invalid'){
        		$this->breezSessionData = $adobeData;
        	} else {
        		$this->Session->setFlash(__('Please enter correct e-mail/password'), 'flash_bad');
        		$this->redirect(array('controller' => 'users', 'action' => 'login'));
        	}
        }
        // Auth Settings
        $scope = array('User.is_active' => '1');
        if (isset($this->params['prefix']) && $this->params['prefix'] == 'admin') {
        	$scope[] = array("User.user_type" => 'admin');
        } else {
        	$scope[] = array("User.user_type" => 'businessOwner');
        }        
        $this->Auth->authenticate = array(
        		AuthComponent::ALL => array('userModel' => 'User'/*, 'scope' => $scope*/),
        		'Form' => array('fields' => array('username' => 'user_email'))
        );
        $this->Auth->allow('signUp','getStateCity','professionalInfo','payment','checkCoupon','activateAccount','resetPassword','forgotpassword','api_login','api_forgotPassword');
        $this->set('includePageJs',$this->includePageJs);
        require_once (ROOT.DS.APP_DIR.DS.'Plugin/authorizedotnet/AuthorizeNet.php');
    }

    /**
    * Admin login
    * @author Laxmi Saini
    */
    public function admin_login() 
    {
        $this->layout = "login";
        $this->includePageJs = array('admin_validation');
        if ($this->Session->read('Auth.User.user_type') == 'admin') {
            $this->redirect(array('controller' => 'professions', 'action' => 'index', 'admin' => true));
        }
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                return $this->redirect(array('controller' => 'dashboard', 'action' => 'index', 'admin' => true));
            } else {
                $this->Session->setFlash(__('Please enter correct e-mail/password'), 'flash_bad');
                $this->Session->setFlash('error', 'default', array(), 'error');
                return $this->redirect(array('controller' => 'users', 'action' => 'login', 'admin' => true));
            }
        }
        $this->set('includePageJs', $this->includePageJs);
    }

    /**
    * admin logout
    * @author Laxmi Saini
    */
    public function admin_logout()
    {
        if ($this->Auth->logout()) {
            $this->redirect(array('controller'=>'users','action' => 'login','admin'=>true));
        }
    }
    
    /**
    * function is used to send a forgot password request
    * @author Gaurav
    */
    public function admin_forgotPassword() 
    {
        $this->layout = "login";
        $this->includePageJs = array('admin_validation');
        if ($this->request->is('post')) {
            $user = $this->User->find('first', array('conditions' => array('user_email' => $this->request->data['User']['email'])));
            if (!empty($user)) {
                $userEmail = $this->Encryption->encode($user['User']['user_email']);
                $time = $this->Encryption->encode(date("Y-m-d H:i:s"));
                $this->User->id = $user['User']['id'];
                $this->User->saveField('pass_reset_hash', $time);
                $url = Configure::read('SITE_URL') . 'admin/users/resetPassword/' . $userEmail . '/' . $time;                
                $emailLib = new Email();
                $to = $user['User']['user_email'];
                $subject = 'Your Foxhopr password reset request';
                $template = 'reset_forgot_password';
                $variable = array('name' => $user['User']['username'],'url'=>$url);
                $success = $emailLib->sendEmail($to,$subject,$variable,$template,'both');

                if ($success) {
                    $this->Session->setFlash(__('Please check your inbox or spam folder to access the reset password link'), 'flash_good');
                    $this->redirect(array('controller' => 'users', 'action' => 'forgotPassword', 'admin' => true));
                } else {
                    $this->Session->setFlash(__('Some error, please try again.'), 'flash_bad');
                }
            } else {
                $this->Session->setFlash(__('This e-mail does not match our records, please enter correct e-mail.'), 'flash_bad');
                $this->redirect(array('controller' => 'users', 'action' => 'forgotPassword', 'admin' => true));
            }
        }
        $this->set('includePageJs',$this->includePageJs);
    }

    /**
     * password reset
     * @param string $userEmail user email
     * @param string $time time
     * @author by Gaurav Bhandari
     */
    public function admin_resetPassword($userEmail = null,$time = null) 
    {  
        $this->layout = "login";
        $this->includePageJs=array('admin_validation');
        $this->set('includePageJs',$this->includePageJs);
        $userEmailDecode = preg_replace('/[^(\x20-\x7F)]*/','', $this->Encryption->decode($userEmail));
        $timeDecode = preg_replace('/[^(\x20-\x7F)]*/','', $this->Encryption->decode($time));
        $userInfo = $this->User->find('first',array('conditions'=>array('user_email'=>$userEmailDecode,'pass_reset_hash'=>$time)));
        if(empty($userInfo)){
            $this->Session->setFlash(__('The link seems to be expired. Please use the latest password reset link or try again.'),'flash_bad');
            $this->redirect(array('controller' => 'users', 'action' => 'forgotPassword', 'admin' => true));
        } else {
            $timediff =  round((strtotime(date("Y-m-d H:i:s")) - strtotime($timeDecode))/(60*60));           
            if($timediff > 24){
                $this->Session->setFlash(__('The link seems to be expired. Please use the latest password reset link or try again'),'flash_bad');
                $this->redirect(array('controller' => 'users', 'action' => 'forgotPassword', 'admin' => true));
            }
            else{
                $this->set('useremail',$userEmailDecode);
            }
        }
        if($this->request->is('post')){
            $this->User->id = $userInfo['User']['id'];
            if($this->User->saveField('password',$this->Auth->password($this->request->data['User']['password']))){                
                $emailLib = new Email();
                $to = $userInfo['User']['user_email'];
                $subject = 'Your Foxhopr password reset successfully';
                $template = 'reset_password';
                $params = array('name' => $userInfo['User']['username']);
                $success = $emailLib->sendEmail($to,$subject,$params,$template,'both');
                if($success){
                    $this->Session->setFlash(__("Your password has been changed successfully."),"flash_good");
                    $this->User->id = $userInfo['User']['id'];
                    $this->User->saveField('pass_reset_hash',NULL);
                    $this->redirect(array('controller' => 'users', 'action' => 'login', 'admin' => true)); 
                }else{
                    $this->Session->setFlash(__("Your password has been changed successfully. Email has not sent successfully."),"flash_bad");
                    $this->User->id = $userInfo['User']['id'];
                    $this->User->saveField('pass_reset_hash',NULL);
                    $this->redirect(array('controller' => 'users', 'action' => 'login', 'admin' => true));                    
                }                
            }else{
                $this->Session->setFlash(__("Your password has not been changed, please try again."),"flash_bad");
            }
        }
    }

    /**
     * Reset password for admin user
     * @author Rohan Julka
     */
    function admin_changePassword() 
    {
        $this->layout = 'admin';
        $this->set('title_for_layout','FoxHopr: Change Password');
        if ($this->request->is('post')) {
            $user = $this->User->findById($this->Encryption->decode($this->Auth->User('id')));
            if (!$user) {
                $this->Session->setFlash(__("Unknown error.  Please try again"),"flash_bad");
                return $this->redirect(array('action' => 'changePassword'));
            }
            if ($this->request->data['User']['new_password'] != $this->request->data['User']['cpassword']) {
                $this->Session->setFlash(__("New password and confirm password should have same value"),"flash_bad");
                return $this->redirect(array('action' => 'changePassword'));
            }
            if ($this->request->data['User']['current_password'] == $this->request->data['User']['new_password']) {
                $this->Session->setFlash(__("Current and New password cannot be same."),"flash_bad");
                return $this->redirect(array('action' => 'changePassword'));
            }
            $currentHash = $user['User']['password'];
            $checkHash = Security::hash($this->request->data['User']['current_password'], null, true);
            if ($currentHash != $checkHash) {
                $this->Session->setFlash(__("Current password is incorrect"),"flash_bad");
                return $this->redirect(array('action' => 'changePassword'));
            }
            
            //$user['User']['confirm_password'] = $this->request->data['User']['cpassword'];
            $newHash = Security::hash($this->request->data['User']['new_password'], null, true);
            $user['User']['password'] = $newHash;
            $user['User']['id'] = $this->Encryption->decode($user['User']['id']);
            unset($this->User->validate['user_email']);
            unset($this->User->validate['password']['length']);
            if ($this->User->save($user)) {
                $this->Session->setFlash(__("Your password has been updated successfully"),"flash_good");
                $this->Session->write('Auth', $this->User->read(null, $user['User']['id']));
                return $this->redirect(array('controller' => 'professions', 'action' => 'index', 'admin' => true));
            } else {
                $this->Session->setFlash(__("Password updation failed.  Please try again"),"flash_bad");
            }
            
        }
    }

    /**
    * Function used for Login Front End 
    * @author Jitendra
    */
    public function login() 
    {
        $this->set('titleForLayout', 'FoxHopr: Log In');
        if ($this->Session->read('Auth.Front.user_type') == 'businessOwner') {
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }
        if ($this->request->is('post')) {
            $this->Session->delete('UID');
            //$this->Session->delete('BackUrlAfterLogin');
            $this->Session->delete('countryInfo');
            $this->Session->delete('zipInfo');
            if ($this->Auth->login()) {
                if($this->Auth->user('is_active')) {
                    $userGroupId = $this->Session->read('Auth.Front.BusinessOwner.group_id');
                    $userId = $this->Session->read('Auth.Front.id');
    				$groupIdCheck = $this->Session->read('Auth.Front.deactivated_by_user');
    				if (!empty($groupIdCheck)) {
                        $this->redirect(array('controller' => 'users', 'action' => 'reactivate'));
                    }
                    if($userGroupId==NULL){
                        $this->Session->write('UID',$userId);
                        return  $this->redirect(array('controller' => 'groups', 'action' => 'group-selection'));
                    }else{
                    $sessionUrl = $this->Session->read('BackUrlAfterLogin');
                    if (!empty($sessionUrl)) {
                            return  $this->redirect($sessionUrl);
                    }
                        if(($this->Session->read('Auth.Front.BusinessOwners.is_unlocked') == 0 ) && ($this->Session->read('Auth.Front.BusinessOwners.group_role') == 'leader' || $this->Session->read('Auth.Front.BusinessOwners.group_role') == 'co-leader')){
                            $this->Session->setFlash(__('Please watch the training video to unlock the group leader rights.<a style="color:white" href="businessOwners/trainingVideo"> <b>Click Here</b></a> to watch the video.'), 'Front/flash_warning');
                        }
                        return  $this->redirect(array('controller' => 'dashboard', 'action' => 'dashboard'));
                    }
                } else {
                    // Logout user as Account Activation is needed first
                    $this->Auth->logout();
                    $this->Session->setFlash(__('Please activate your account.'), 'Front/flash_bad');
                    return $this->redirect(array('controller' => 'users', 'action' => 'login'));
                }
            } else {
                $this->Session->setFlash(__('Please enter correct Email/Password.'), 'Front/flash_bad');
                $this->Session->setFlash(__('Please enter correct Email/Password.'), 'Front/flash_bad','', 'error');
                return $this->redirect(array('controller' => 'users', 'action' => 'login'));
            }
        }        
    }

    /**
     * user logout
     * @author Jitendra
     */
    public function logout()
    {
    	if ($this->Auth->logout()) {
    		$this->Session->delete('UID');
    		$this->redirect(array('controller'=>'users','action' => 'login'));
    	}
    }
    
    /**
     * User information(Registration Step 1)
     * @author Jitendra
     */
    public function signUp($regType=NULL,$refId=NULL)
    {
    	if ($this->Session->check('Auth.Front.id') == true) {
    		$this->redirect(array('controller' => 'dashboard', 'action' => 'dashboard'));
    	}
    	$titleForLayout = "FoxHopr: Become A Foxhopr";
        $this->set(compact('titleForLayout'));        
        $timezoneList   = $this->Timezone->getAllTimezones();
        $profesionCategoryList  = $this->Profession->getAllProfessionsCategory();
        $countryList    = $this->Common->getAllCountries();        
        $this->set("timezoneList",$timezoneList);       
        $this->set('profesionCategoryList', $profesionCategoryList);        
        $this->set(compact('countryList'));   
        // check plan type and move to next step
        if ($this->request->is('post')) {
            $this->BusinessOwner->set($this->request->data);
            $this->loadModel('User');
            $this->User->set($this->request->data);
            if (!$this->User->validates()) {
                foreach ($this->User->validationErrors as $key => $value) {
                    $err[] = $value[0];
                }
                $this->Session->setFlash(__($err[0]), 'Front/flash_bad');
                $this->__unsetData();
                $this->request->data = $this->request->data;
            } else if ($this->BusinessOwner->validates()) {
                if(!empty($this->request->data['User']['user_email']) && !empty($this->request->data['BusinessOwner']['confirm_email_address'])) { 
                    if($this->request->data['User']['user_email']!=$this->request->data['BusinessOwner']['confirm_email_address']) {
                        $this->BusinessOwner->validationErrors['confirm_email_address'] = "Require the same value to Email. ";
                        $this->Session->setFlash(__($this->BusinessOwner->validationErrors['confirm_email_address']), 'Front/flash_bad');
                        $this->__unsetData();
                        $this->request->data = $this->request->data;
                    } 
                }
                $this->loadModel('Coupon');
                $this->loadModel('Transaction');
                $this->loadModel('Subscription');
                //Check Coupon Code
				if(!empty($this->request->data['BusinessOwner']['code'])){
					$couponCheck = $this->checkCouponCode($this->request->data['BusinessOwner']['code']);
					if (isset($couponCheck['error'])) {
	    				$checkCouponError = 1;
	    				$this->User->validationErrors['couponcheck'] = $couponCheck['error'];
	    				$this->request->data = $this->request->data;
					} else {
						$this->request->data['BusinessOwner']['memberShipPrice'] = $couponCheck['newMembershipPrice'];
					}
				} else {
					$this->request->data['BusinessOwner']['memberShipPrice'] = Configure::read('PLANPRICE');
				}
                $this->request->data['BusinessOwner']['expiration'] = $this->request->data['BusinessOwner']['expiration_month']['month'].'/'.$this->request->data['BusinessOwner']['expiration_year']['year'];
                if (!isset($checkCouponError)) {
                	//PAYMENT 
	                $transaction = new AuthorizeNetAIM;
	                $transaction->setSandbox(AUTHORIZENET_SANDBOX);
	                $transaction->setFields(
	                    array(
	                        'amount' => $this->request->data['BusinessOwner']['memberShipPrice'], 
	                        'card_num' => $this->request->data['BusinessOwner']['CC_Number'],
	                        'exp_date' => $this->request->data['BusinessOwner']['expiration'],
	                        'card_code' => $this->request->data['BusinessOwner']['cvv'],
	                        )
	                    );
	                $response = $transaction->authorizeAndCapture();
	                if (isset($response->declined) && $response->declined == "1") {
	                    $errMsg = $response->response_reason_text;
	                    $this->Session->setFlash(__($errMsg), 'Front/flash_bad');
	                    $this->__unsetData();
	                    $this->request->data = $this->request->data;
	                }
	                else if (isset($response->error) && $response->error == "1") {
	                    $errMsg = $response->response_reason_text;
	                    $this->Session->setFlash(__($errMsg), 'Front/flash_bad');
	                    $this->__unsetData();
	                    $this->request->data = $this->request->data;
	                }
	                else if (isset($response->approved) && $response->approved == "1") {
                        $this->request->data['User']['user_email'] = strtolower($this->request->data['User']['user_email']);
	                    $userdata['User']['user_email'] = $this->request->data['User']['user_email'];
	                    $userdata['User']['username']   = $this->request->data['User']['user_email'];
	                    $userdata['User']['new_password']   = $this->request->data['BusinessOwner']['password'];
	                    $userdata['User']['user_type']  = "businessOwner";
	                    if($this->User->save($userdata)) {
			                //Change Refferal Info
			                if($regType!=NULL && $refId !=NULL) {
			                    $decrypted=$this->Encryption->decode($refId);
			                    $inviteData=$this->InvitePartner->find('first',array('conditions'=>array('id'=>$decrypted)));
			                    if($this->request->data['User']['user_email']==$inviteData['InvitePartner']['invitee_email']) {
			                        $data=array('InvitePartner.referral_amount'=>'InvitePartner.referral_amount + 5','InvitePartner.status'=>"'active'",'invitee_userid'=>$this->User->id);
			                        $this->InvitePartner->updateAll($data,array('id'=>$decrypted));  
			                    }                    
			                }
	                        $BusinessOwner['BusinessOwner']['user_id'] = $this->User->id;
	                        $BusinessOwner['BusinessOwner']['email']   = $this->request->data['User']['user_email'];
	                        $BusinessOwner['BusinessOwner']['profession_id'] = $this->Encryption->decode($this->request->data['BusinessOwner']['profession_id']);
	                        $BusinessOwner['BusinessOwner']['fname']  = ucwords(strtolower($this->request->data['BusinessOwner']['fname']));
	                        $BusinessOwner['BusinessOwner']['lname']  = ucwords(strtolower($this->request->data['BusinessOwner']['lname']));
	                        $BusinessOwner['BusinessOwner']['company']  = $this->request->data['BusinessOwner']['company'];
	                        $BusinessOwner['BusinessOwner']['country_id']  = $this->request->data['BusinessOwner']['country_id'];
	                        $BusinessOwner['BusinessOwner']['timezone_id']  = $this->request->data['BusinessOwner']['timezone_id'];
	                        $BusinessOwner['BusinessOwner']['state_id']  = $this->request->data['BusinessOwner']['state_id'];
	                        $BusinessOwner['BusinessOwner']['zipcode']  = $this->request->data['BusinessOwner']['zipcode'];
	                        $BusinessOwner['BusinessOwner']['city']  = $this->request->data['BusinessOwner']['city'];
	                        $BusinessOwner['BusinessOwner']['notifications_enabled'] = 'weeklySummery,receiveReferral,commentMadeOnReferral,receiveMessage,commentMadeOnMessage,receiveEventInvitation,commentMadeOnEvent';
                            $BusinessOwner['BusinessOwner']['credit_card_number'] = $this->Encryption->encode(substr($this->request->data['BusinessOwner']['CC_Number'],-4,4));
	                        $this->BusinessOwner->save($BusinessOwner);

	                        // Add to newsletter subscribe list
	                        $this->NewsletterSubscribe->create();
	                        $newsletterData=array('NewsletterSubscribe'=>array('subscribe_email_id'=>$BusinessOwner['BusinessOwner']['email'],'is_registered'=>1));
	                        $this->NewsletterSubscribe->save($newsletterData);
	                        
	                        // Assign to affiliate account if coming from email link
	                    	if($this->Session->check('current_affiliate_url')){
								$urlInfo = explode("-fx-hr-",$this->Session->read('current_affiliate_url'));		
								$affiliateId = $this->Encryption->decode($urlInfo['1']);
								$this->Affiliate->updateAll(
										array('Affiliate.total_conversion' => 'Affiliate.total_conversion + 1'),
										array('Affiliate.id' => $affiliateId)
								);
							}
							
	                        // sent account verification email
	                        $emailLib = new Email();
	                        $subject = "Foxhopr account created successfully.";
	                        $template = "confirmation_email";
	                        $format = "both";
	                        $encrypt_user_id = $this->Encryption->encode($this->User->id);
	                        $activate_link = Configure::read('SITE_URL')."users/activateAccount/".$encrypt_user_id;
	                        $business_owner_name = ucwords(strtolower($this->request->data['BusinessOwner']['fname']))." ".ucwords(strtolower($this->request->data['BusinessOwner']['lname']));
	                        $variable = array('businessowner'=>$business_owner_name,'username' => $this->request->data['User']['user_email'],'password'=>$this->request->data['BusinessOwner']['password'],'activate_link'=>$activate_link);
	                        $to = $this->request->data['User']['user_email'];
	                        $success = $emailLib->sendEmail($to,$subject,$variable,$template,$format);                                      
	                    } else {
	                        foreach ($this->User->validationErrors as $key => $value) {
	                            $err[] = $value[0];
	                        }
	                        $this->Session->setFlash(__($err), 'Front/flash_bad');
	                        $this->__unsetData();
	                        $this->request->data = $this->request->data;
	                    }
	                    $lastInsertId = $this->User->id;
	                    $transactions['user_id'] = $lastInsertId;
	                    $transactions['transaction_id'] = $response->transaction_id;
	                    $transactions['status'] = 1;
	                    $transactions['purchase_date'] = $this->Common->getCurrentActiveDate($lastInsertId);
						$transactions['amount_paid'] = $this->request->data['BusinessOwner']['memberShipPrice'];
						$transactions['credit_card_number'] = $this->Encryption->encode(substr($this->request->data['BusinessOwner']['CC_Number'],-4,4));						
	                    $this->Transaction->save($transactions);
	                    //Create Subscription
	                    $this->request->data['Subscription']['transaction_id'] = $response->transaction_id;
	                    $this->createSubscription($this->request->data,$lastInsertId);

	                    //Create User in Adobe Connect panel
	                    $adobePass = substr($this->Auth->password($this->request->data['BusinessOwner']['password']),0,20);
	                    $params = array(
	                    			"action" => "principal-update",
									"first-name" => ucwords(strtolower($this->request->data['BusinessOwner']['fname'])),
									"last-name" => ucwords(strtolower($this->request->data['BusinessOwner']['lname'])),
									"login" => $this->request->data['User']['user_email'],
									"password" => $adobePass,
									"type" => "user",
									"has-children" => "0",
									"email" => $this->request->data['User']['user_email']
								);
	                    $curlResponse = $this->Adobeconnect->createUser($params,$this->breezSessionData);
	                    $this->User->id = $lastInsertId;
	                    $this->User->saveField('principal_id',$curlResponse['principal']['@attributes']['principal-id']);
	                    //Create User in Adobe Connect panel ends

	                    $this->Session->write('UID', $this->Encryption->encode($this->User->id));
	                    $this->Session->write('countryInfo',$this->request->data['BusinessOwner']['country_id']);
	                    $this->Session->write('zipInfo',$this->request->data['BusinessOwner']['zipcode']);
	                    //Create Subscripton ends
	                    $this->redirect(array('controller' => 'groups', 'action' => 'group-selection'));          
	                } else {
		                foreach ($this->BusinessOwner->validationErrors as $key => $value) {
		                    $err[] = $value[0];
		                }
		                $this->Session->setFlash(__($err[0]), 'Front/flash_bad');
		                $this->__unsetData();
		                $this->request->data = $this->request->data;
	                }
                } else {
	                $this->Session->setFlash(__($this->User->validationErrors['couponcheck']), 'Front/flash_bad');
	                $this->__unsetData();
	                $this->request->data = $this->request->data;
                }                 		    
            }
            $country_id     = $this->request->data['BusinessOwner']['country_id'];
            $getStateList   = $this->Common->getStatesForCountry($country_id);
            $this->set('stateList', $getStateList);
        }
    }
    
    /**
     * function is used to reset password
     * @author Jitendra
     * @param string $userID encrypted user id
     * @param string $time time
     */
    public function resetPassword($userID=null,$time=null)
    {
    	if ($this->request->is('post')) {
    		$uid = $this->Encryption->decode($this->request->data['User']['uid']);
    		$user = $this->User->find('first', array('conditions' => array('User.id' =>$uid)));    		
    		if (!empty($user)) {
    			$this->User->id = $uid;
	    		if($this->User->saveField('password',$this->Auth->password($this->request->data['User']['password']))){
	    			$emailLib = new Email();
	                $to = $user['User']['user_email'];
	                $subject = 'Your Foxhopr password reset successfully';
	                $template = 'reset_password';
	                $format = "both";
	                $user_name = $user['BusinessOwner']['fname']." ".$user['BusinessOwner']['lname'];
	                $params = array('name' => $user_name);
	                $success = $emailLib->sendEmail($to,$subject,$params,$template,$format);
	                if($success){
	                    $this->Session->setFlash(__("Your password has been changed successfully."),"Front/flash_good");	                    
	                    $this->User->saveField('pass_reset_hash',NULL);
	                    $this->redirect(array('controller' => 'users', 'action' => 'login')); 
	                }else{
	                    $this->Session->setFlash(__("Your password has been changed successfully. Email has not sent successfully."),"Front/flash_bad");	                   
	                    $this->User->saveField('pass_reset_hash',NULL);
	                    $this->redirect(array('controller' => 'users', 'action' => 'login'));                    
	                }                
	            }else{
	                $this->Session->setFlash(__("Your password has not been changed, please try again."),"Front/flash_bad");
	            }
    		} else {
    			$this->Session->setFlash(__('This is an invalid user.'), 'Front/flash_bad');
    			$this->redirect(array('controller' => 'users', 'action' => 'forgotPassword'));
    		}
    	}else{
    		if($userID==null){
    			$this->Session->setFlash(__('This is an invalid link.'), 'Front/flash_bad');
    			$this->redirect(array('controller' => 'users', 'action' => 'forgotPassword'));
    			exit;
    		}
    		$uid = $this->Encryption->decode($userID);
    		$islatestlink = $this->User->find('count', array('conditions' => array('User.id' =>$uid,'User.pass_reset_hash' =>$time)));
    		$time = $this->Encryption->decode($time);
    		$timediff =  round((strtotime(date("Y-m-d H:i:s")) - strtotime($time))/(60*60));
    		if($timediff > 24 || $islatestlink==0){
    			$this->Session->setFlash(__('The link seems to be expired. Please use the latest password reset link or try again.'),'Front/flash_bad');
    			$this->redirect(array('controller' => 'users', 'action' => 'forgotPassword'));
    		}
    		else{
    			$this->set('userid',$userID);
    	    	$this->set('time',$time);
    		}
    		$this->set('userid',$userID);
    		$this->set('time',$time);
    	}
    }
    
    /**
     * function is used to send a reset password link
     * @author Jitendra
     */
    public function forgotpassword()
    {
        $this->set('titleForLayout', 'FoxHopr: Forgot Password');
    	if ($this->request->is('post')) {
    		$user = $this->User->find('first', array('conditions' => array('user_email' => $this->request->data['User']['email'])));
    		if (!empty($user)) {
    			$userid = $user['User']['id'];
    			$time = $this->Encryption->encode(date("Y-m-d H:i:s"));
    			$this->User->id = $this->Encryption->decode($userid);
    			$this->User->saveField('pass_reset_hash', $time);
    			$url = Configure::read('SITE_URL') . 'users/resetPassword/' . $userid . '/' . $time;
    
    			$emailLib = new Email();
    			$to = $user['User']['user_email'];
    			$subject = 'Your Foxhopr password reset request';
    			$template = 'reset_forgot_password';
    			$user_name = $user['BusinessOwner']['fname']." ".$user['BusinessOwner']['lname'];
    			$variable = array('name' => $user_name,'url'=>$url);
    			$success = $emailLib->sendEmail($to,$subject,$variable,$template,'both');
    
    			if ($success) {
    				$this->Session->setFlash(__('Please check your inbox to access the reset password link'), 'Front/flash_good');
    				$this->redirect(array('controller' => 'users', 'action' => 'forgotPassword'));
    			} else {
    				$this->Session->setFlash(__('Email not sent, please try again.'), 'Front/flash_bad');
    			}
    		} else {
    			$this->Session->setFlash(__('The email entered does not match our database.'), 'Front/flash_bad');
    			$this->Session->setFlash('', 'Front/flash_bad','','error');
    			$this->redirect(array('controller' => 'users', 'action' => 'forgotPassword'));
    		}
    	}
    }
    
    /**
     * Lists states or cities
     * @author Laxmi Saini
     */
    public function getStateCity()
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
     * user's Professional informations (Mini profile step 2)
     * @author Laxmi Saini
     */
    public function professionalInfo()
    {
        $userInfo = $this->Session->read("UserData");
        if (empty($userInfo)) {
            $this->Session->setFlash(__("Please fill personal information."), "flash_bad");
            $this->redirect(array('controller' => 'users', 'action' => 'miniProfile'));
        }
        $this->Professsion = $this->Components->load('Profession');
        $titleForLayout = "Submit your professional information";
        $profesionList = $this->Professsion->getAllProfessions();

        $this->set(compact('titleForLayout'));
        $this->set('profesionList', $profesionList);

        if ($this->request->is('post')) {
            $userData['BusinessOwner'] = array_merge($userInfo['BusinessOwner'], $this->request->data['BusinessOwner']);
            $this->Session->write('UserData', $userData);
            if (!empty($userData['BusinessOwner']['selectedPlan'])) {
                if ($userData['BusinessOwner']['selectedPlan'] == 'local') {
                    $this->redirect(array('controller' => 'groups', 'action' => 'localGroups'));
                } elseif ($userData['BusinessOwner']['selectedPlan'] == 'global') {
                    $this->redirect(array('controller' => 'groups', 'action' => 'globalGroups'));
                } elseif ($userData['BusinessOwner']['selectedPlan'] == 'listing') {
                    $this->redirect(array('controller' => 'users', 'action' => 'payment'));
                }
            } else {
                $this->Session->setFlash(__("Please choose local or global plan."), "flash_bad");
                $this->redirect(array('controller' => 'users', 'action' => 'choosePlan'));
            }
        }
    }
    
    /**
     * Payment page
     * @author Laxmi Saini 
     */
    public function payment()
    {
        $titleForLayout = "Make Payments";
        $this->set(compact('titleForLayout'));
        $groupId = '';
        if ($this->request->is('post')) {
            if (!empty($this->request->data['BusinessOwner']['group_id']) && $this->Encryption->decode($this->request->data['BusinessOwner']['group_id'])) {
                $groupId = $this->Encryption->decode($this->request->data['BusinessOwner']['group_id']);
            }
        }
        if (strtolower($this->Session->read("User.selected_plan")) != 'listing') {
            if (!empty($groupId)) {
                $this->Session->write('UserData.BusinessOwner.group_id', $groupId);
            } else {
                $this->Session->setFlash(__("Please choose a group."), "flash_bad");
            }
        }
    }

    /**
    *   Check coupon in valid or not
    *   @author Gaurav Bhandari
    */
    public function checkCoupon()
    {
        $this->loadModel('Coupon');
        $this->autoRender = false;
        $check = $this->Coupon->findByCouponCode(trim($this->request->data['BusinessOwner']['code']));
        if(!empty($check)) {
            if($check['Coupon']['is_active'] == 1) {
                if($check['Coupon']['coupon_type'] == 'email') {
                    $allEmail = explode(',',$check['Coupon']['email']);
                    $checkVal = in_array($this->request->data['User']['user_email'], $allEmail);
                    if($checkVal){
                        if(strtotime(date("Y-m-d")) <= strtotime($check['Coupon']['expiry_date'])) {
                        $data['discountValue'] = number_format(($check['Coupon']['discount_amount'] / 100) * Configure::read('PLANPRICE'),2);
                        $this->Session->write('SESS_ID', $this->Encryption->encode($data['discountValue']));
                        $data['afterDiscount'] = Configure::read('PLANPRICE') - $data['discountValue'];
                        $jsonValue = array('response' => 'success','message'=>'Coupon applied successfully' , 'data' => $data);
                        } else {
                        $this->Session->destroy();
                        $jsonValue = array('response' => 'fail','message'=>'Coupon has been expired' , 'data' => 'null');
                        } 
                    } else {
                        $jsonValue = array('response' => 'fail','message'=>'Coupon does not match with provided email' , 'data' => 'null');
                    }
                } elseif($check['Coupon']['usage_limit'] == 0) {
                    if(strtotime(date("Y-m-d")) <= strtotime($check['Coupon']['expiry_date'])) {
                        $data['discountValue'] = number_format(($check['Coupon']['discount_amount'] / 100) * Configure::read('PLANPRICE'),2);
                        $this->Session->write('SESS_ID', $this->Encryption->encode($data['discountValue']));
                        $data['afterDiscount'] = Configure::read('PLANPRICE') - $data['discountValue'];
                        $jsonValue = array('response' => 'success','message'=>'Coupon applied successfully' , 'data' => $data);
                    } else {
                        $this->Session->destroy();
                        $jsonValue = array('response' => 'fail','message'=>'Coupon has been expired' , 'data' => 'null');
                    } 
                }elseif($check['Coupon']['used_count'] < $check['Coupon']['usage_limit']) {
                    if(strtotime(date("Y-m-d")) <= strtotime($check['Coupon']['expiry_date'])) {
                        $data['discountValue'] = number_format(($check['Coupon']['discount_amount'] / 100) * Configure::read('PLANPRICE'),2);
                        $this->Session->write('SESS_ID', $this->Encryption->encode($data['discountValue']));
                        $data['afterDiscount'] = Configure::read('PLANPRICE') - $data['discountValue'];
                        $jsonValue = array('response' => 'success','message'=>'Coupon applied successfully' , 'data' => $data);
                    } else {
                        $this->Session->destroy();
                        $jsonValue = array('response' => 'fail','message'=>'Coupon has been expired' , 'data' => 'null');
                    } 
                } else {
                    $this->Session->destroy();
                    $jsonValue = array('response' => 'fail','message'=>'Coupon has reached maximum redemption limit' , 'data' => 'null');
                }
                              
            } else {
                $this->Session->destroy();
                if($check['Coupon']['coupon_type'] == 'public') {
                    $jsonValue = array('response' => 'fail','message'=>'There was a problem applying the coupon, please contact Admin' , 'data' => 'null');
                } else {
                    $jsonValue = array('response' => 'fail','message'=>'Coupon not activated' , 'data' => 'null');
                }                
            }           
        } else {
            $this->Session->destroy();
            $jsonValue = array('response' => 'fail','message'=>'Coupon is invalid','data'=>'null');
        }
        return json_encode($jsonValue);
    }
    
    /**
     *   User account activation from email
     *   @author Jitendra
     *   @param string $userID encrypted user id  
     */
    public function activateAccount($userID=null)
    {
    	$this->autoRender = false;
        $userId = $this->Encryption->decode($userID);
        $userData = $this->User->userInfoById($this->Encryption->decode($userID));
    	$is_confirm = $this->User->find('count',array('fields'=>array('User.is_confirm'),'conditions'=>array('User.is_confirm'=>1,'User.id'=>$userId)));
    	if($is_confirm >= 1){
    		$this->Session->setFlash(__("Your profile is already active."), "Front/flash_bad");
    		$this->redirect(array('controller' => 'users', 'action' => 'login'));
    	}    	
    	if($userID!=null){    		
    		$userdata['User']['id'] = $userId;
    		$userdata['User']['is_active'] = 1;
    		$userdata['User']['is_confirm'] = 1;    		
    		if($this->User->save($userdata)){                
                if($userData['BusinessOwners']['group_id'] != '') {
                    $emailLib = new Email();
                    $subject = "Welcome to FoxHopr";
                    $template = "activation_email";
                    $format = "both";  
                    $group_role = $userData['BusinessOwners']['group_role'];     
                    $variable = array(
                                'role' => $group_role,
                                'businessowner'=>$userData['BusinessOwners']['fname'].' '.$userData['BusinessOwners']['lname'],
                                'groupname' => Configure::read('GROUP_PREFIX').' '.$this->Encryption->decode($userData['Groups']['id']),
                                'meetingdate'=> date('m-d-Y',strtotime($userData['Groups']['first_meeting_date'])),
                                'meetingtime'=>date('h:i A',strtotime($userData['Groups']['meeting_time'])));
                    $to = $userData['User']['user_email'];
                    $success = $emailLib->sendEmail($to,$subject,$variable,$template,$format); 
                }
    			$this->Session->setFlash(__("Your profile has been activated successfully."), "Front/flash_good");
    			$this->redirect(array('controller' => 'users', 'action' => 'login'));
    		}else{
    			$this->redirect(array('controller' => 'users', 'action' => 'signUp'));
    		}    		
    	}else{
    		$this->Session->setFlash(__("Your account activation key expired."), "Front/flash_bad");
    		$this->redirect(array('controller' => 'users', 'action' => 'signUp'));
    	}
    }

    /**
     * Web service for login
     * @author Priti Kabra
     */
    public function api_login()
    {
        //$headersInformation = getallheaders();
        //CakeLog::write('debug', 'myArray'.print_r($headersInformation, true) ); exit;
        $error = 0;
        if (!isset($this->DeviceToken) || !isset($this->DeviceId) || !isset($this->DeviceType)) {
            $error = 1;
            $errMsg = "Please provide device type, device token.";
        }
        if ($error == 0) {
            if (!empty($this->jsonDecodedRequestedData->user_email) && !empty($this->jsonDecodedRequestedData->password)) {
                $user_email = $this->jsonDecodedRequestedData->user_email;
                $password = $this->Auth->password($this->jsonDecodedRequestedData->password);
                $userData = $this->User->find('first', array('conditions' => array('User.user_email' => $user_email, 'User.password' => $password, 'User.is_active' => 1), 'fields' => array('User.id', 'User.user_email', 'User.password', 'User.deactivated_by_user', 'BusinessOwner.group_id')));
                if (!empty($userData)) {
                    if (empty($userData['User']['deactivated_by_user'])) {
                        $userUpdate['is_logged_in'] = 1;
                        $userUpdate['device_type'] = $this->DeviceType;
                        $userUpdate['device_token'] = $this->DeviceToken;
                        $userUpdate['device_id'] = $this->DeviceId;
                        $this->User->updateAll(
                            array('User.device_type' => NULL, 'User.is_logged_in' => 0, 'User.device_token' => NULL, 'User.device_id' => NULL), 
                            array('User.device_token' => $this->DeviceToken)
                        );
                        $this->User->id = $this->Encryption->decode($userData['User']['id']);
                        $this->User->save($userUpdate);
                        $conditions = array('User.user_email' => $user_email, 'User.password' => $password);
                        $fields = array('User.id', 'User.username', 'User.user_email', 'is_active', 'User.is_confirm', 'BusinessOwner.user_id', 'BusinessOwner.fname', 'BusinessOwner.lname', 'BusinessOwner.profile_image', 'BusinessOwner.profile_completion_status', 'BusinessOwner.group_id', 'BusinessOwner.group_role', 'BusinessOwner.city', 'BusinessOwner.zipcode', 'Groups.group_type');
                        $userDataUpdated = $this->User->find('first', array('conditions' => $conditions, 'fields' => $fields));
                        $userDataUpdated['User']['fname'] = $userDataUpdated['BusinessOwner']['fname'];
                        $userDataUpdated['User']['lname'] = $userDataUpdated['BusinessOwner']['lname'];
                        $profile_image = !empty($userDataUpdated['BusinessOwner']['profile_image']) ? 'uploads/profileimage/'.$userDataUpdated['BusinessOwner']['user_id'].'/'.$userDataUpdated['BusinessOwner']['profile_image'] : 'no_image.png';
                        $userDataUpdated['User']['profile_image'] = Configure::read('SITE_URL') . 'img/' . $profile_image;
                        $userDataUpdated['User']['profile_completion_status'] = $userDataUpdated['BusinessOwner']['profile_completion_status'];
                        $userDataUpdated['User']['group_type'] = $userDataUpdated['Groups']['group_type'];
                        $userDataUpdated['User']['group_id'] = $userDataUpdated['BusinessOwner']['group_id'];
                        $userDataUpdated['User']['group_role'] = $userDataUpdated['BusinessOwner']['group_role'];
                        $userDataUpdated['User']['city'] = $userDataUpdated['BusinessOwner']['city'];
                        $userDataUpdated['User']['zipcode'] = $userDataUpdated['BusinessOwner']['zipcode'];
                        $userDataUpdated['User']['rating'] = '';
                        $userDataUpdated['User']['next_shuffle'] = '';
                        $this->set(array(
                            'code' => Configure::read('RESPONSE_SUCCESS'),
                            'result' => $userDataUpdated['User'],
                            'message' => 'Successfully logged in.',
                            '_serialize' => array('code', 'result', 'message')
                        ));
                    } else {
                        $this->errorMessageApi('Please reactivate your account through our website.');
                    }
                } else {
                    $this->errorMessageApi('Please enter correct Email/Password');
                }
            } else {
                $this->errorMessageApi('Please provide Email and Password');
            }
        } else {
            $this->errorMessageApi($errMsg);
        }
    }

    /**
     * Web service for logout
     * @author Priti Kabra
     */
    public function api_logout()
    {
        $error = 0;
        if (!isset($this->DeviceToken) || !isset($this->DeviceId) || !isset($this->DeviceType)) {
            $error = 1;
            $errMsg = "Please provide device type, device token.";
        }
        if ($error == 0) {
            $userId = $this->loggedInUserId;
            $userUpdate['is_logged_in'] = 0;
            $userUpdate['device_type'] = '';
            $userUpdate['device_token'] = '';
            $userUpdate['device_id'] = '';
            $this->User->id = $userId;
            $this->User->save($userUpdate);
            $this->set(array(
                'code' => Configure::read('RESPONSE_SUCCESS'),
                'message' => 'Logged out successfully',
                '_serialize' => array('code', 'message')
            ));
        } else {
            $this->errorMessageApi($errMsg);
        }
    }

    /**
     * Web service for forgot password
     * @author Priti Kabra
     */
    public function api_forgotPassword()
    {
        $error = 0;
        if (!isset($this->DeviceToken) || !isset($this->DeviceId) || !isset($this->DeviceType)) {
            $error = 1;
            $errMsg = "Please provide device type, device token.";
        }
        if ($error == 0) {
            if (!empty($this->jsonDecodedRequestedData->user_email)) {
                $user_email = $this->jsonDecodedRequestedData->user_email;
                $userData = $this->User->find('first', array('conditions' => array('User.user_email' => $user_email)));
                if (!empty($userData)) {
                    $userid = $userData['User']['id'];
                    $time = $this->Encryption->encode(date("Y-m-d H:i:s"));
                    $url = Configure::read('SITE_URL') . 'users/resetPassword/' . $userid . '/' . $time;
                    $name = $userData['BusinessOwner']['fname']." ".$userData['BusinessOwner']['lname'];
                    $emailLib = new Email();
                    $to = $userData['User']['user_email'];
                    $subject = 'Your Foxhopr password reset request';
                    $template = 'reset_forgot_password';
                    $format = 'both';
                    $variable = array('name' => $name, 'url' => $url);
                    if ($emailLib->sendEmail($to,$subject,$variable,$template,$format)) {
                        $this->User->id = $this->Encryption->decode($userid);
                        $this->User->saveField('pass_reset_hash', $time);
                        $this->set(array(
                            'code' => Configure::read('RESPONSE_SUCCESS'),
                            'message' => 'Please check your inbox to access the reset password link',
                            '_serialize' => array('code', 'message')
                        ));
                    } else {
                        $this->errorMessageApi('Your request cannot be processed. Please try again.');
                    }
                } else {
                    $this->errorMessageApi('The email entered does not match our database.');
                }
            } else {
                $this->errorMessageApi('Please provide Email');
            }
         } else {
            $this->errorMessageApi($errMsg);
        }
    }

    /** 
     * Web service to get the profile details
     * @author Priti Kabra
     */
    public function api_profileDetail()
    {
        $error = 0;
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        if ($error == 0) {
            $userId = $this->loggedInUserId;
            $profileData = $this->User->find('first', array(
                                                'conditions' => array('User.id' => $userId),
                                                'fields' => array(
                                                    'BusinessOwners.fname',
                                                    'BusinessOwners.lname',
                                                    'BusinessOwners.group_id',
                                                    'BusinessOwners.group_role',
                                                    'BusinessOwners.profile_image',
                                                    'BusinessOwners.is_level_message_viewed',
                                                    'Groups.shuffling_date'
                                                    )
                                                )
                                            );
             $profile_image = !empty($profileData['BusinessOwners']['profile_image']) ? 'uploads/profileimage/'.$userId.'/'.$profileData['BusinessOwners']['profile_image'] : 'no_image.png';
                    $profileData['BusinessOwners']['profile_image'] = Configure::read('SITE_URL') . 'img/' . $profile_image;
            if (!empty($profileData)) {
                $this->BusinessOwner = $this->Components->load('BusinessOwner');
                $profileCompletionStatus = $this->BusinessOwner->getProfileStatus($userId);
                $profileData['BusinessOwners']['profile_completion_status'] = $profileCompletionStatus;
                $profileData['BusinessOwners']['shuffling_date'] = $profileData['Groups']['shuffling_date'];
				$totalReview = $this->Review->getTotalReviewByUserId($this->loggedInUserId);
                $totalReviewAverage = $totalReview*Configure::read('RATING_TYPE_NO');
                $totalAvgRatingArr = $this->Review->getAverage($this->loggedInUserId);
                if (!empty($totalAvgRatingArr)) {
                    $profileData['BusinessOwners']['rating'] = round($totalAvgRatingArr/$totalReviewAverage);            
                } else {
                    $profileData['BusinessOwners']['rating'] = 0;
                }
				//membership type
                $membershipData = $this->Membership->find('all');
                $daylen = 60*60*24;
                $diff = round((time()-strtotime($membershipData[0]['Membership']['modified']))/$daylen);
                $membershipUpdated = false;
                if ($diff >=0) {
                    $membershipUpdated = true;
                }
                $levelMessageViewed = false;
                if ($profileData['BusinessOwners']['is_level_message_viewed'] == 1 || $profileData['BusinessOwners']['is_level_message_viewed'] == NULL) {
                    $levelMessageViewed = true;
                }
                $profileData['BusinessOwners']['membership_type'] = "";
                $referralCount = $this->ReferralStat->find('count',array('conditions'=>array('ReferralStat.sent_from_id' => $userId)));
                if ($referralCount >= $membershipData[0]['Membership']['lower_limit'] && $referralCount <= $membershipData[0]['Membership']['upper_limit']) {
                    $profileData['BusinessOwners']['membership_type'] = 'Bronze';
                } elseif( $referralCount>=$membershipData[1]['Membership']['lower_limit'] && $referralCount<=$membershipData[1]['Membership']['upper_limit']) {
                    $profileData['BusinessOwners']['membership_type'] = 'Silver';
                } elseif( $referralCount>=$membershipData[2]['Membership']['lower_limit'] && $referralCount<=$membershipData[2]['Membership']['upper_limit']) {
                    $profileData['BusinessOwners']['membership_type'] = 'Gold';
                } elseif( $referralCount>=$membershipData[2]['Membership']['lower_limit'] ) {
                    $profileData['BusinessOwners']['membership_type'] = 'Platinum';
                }
                $profileData['BusinessOwners']['totalReview'] = $totalReview;
                $this->set(array(
                    'code' => Configure::read('RESPONSE_SUCCESS'),
                    'result' => $profileData['BusinessOwners'],
                    'message' => 'Profile Data.',
                    '_serialize' => array('code', 'result', 'message')
                ));
            } else {
                $this->errorMessageApi('No member is in the team.');
            }
        } else {
            $this->errorMessageApi($errMsg);
        }
    }

    /** 
     * Web service to get the list of the team members
     * @author Priti Kabra
     */
    public function api_teamMemberList()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        if ($error == 0) {
            $userId = $this->loggedInUserId;
            $userGroup = $this->User->find('first', array('conditions' => array('User.id' => $userId), 'fields' => array('BusinessOwners.group_id')));
            $memberList = $this->BusinessOwner->find('all', array('conditions' => array('BusinessOwner.user_id !=' => $userId, 'BusinessOwner.group_id' => $userGroup['BusinessOwners']['group_id'], 'User.is_active' => 1), 'fields' => array('BusinessOwner.id', 'BusinessOwner.user_id', 'BusinessOwner.fname', 'BusinessOwner.lname'), 'order' => 'BusinessOwner.fname ASC'));
            $list = array();
            foreach ($memberList as $key=>$value) {
              $list[] = $value['BusinessOwner'];
              $list[$key]['member_type'] = "current";
              $list[$key]['list_user_id'] = "cur_".$value['BusinessOwner']['user_id'];
            }
            if (!empty($memberList)) {
                $this->set(array(
                    'code' => Configure::read('RESPONSE_SUCCESS'),
                    'result' => $list,
                    'message' => 'Team Member List.',
                    '_serialize' => array('code', 'result', 'message')
                ));
            } else {
                $this->errorMessageApi('');
            }
        } else {
            $this->errorMessageApi($errMsg);
        }
    }

    /** 
     * Web service to get the list of the previous team members
     * @author Priti Kabra
     */
    public function api_previousMemberList()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        if ($error == 0) {
            $userId = $this->loggedInUserId;
            $this->loadModel('PrevGroupRecord');
            $teamMembers = $this->PrevGroupRecord->find('all', array('conditions' => array('PrevGroupRecord.user_id' => $userId, 'User.deactivated_by_user' => 0, 'User.is_active' => 1), 'group'=>'PrevGroupRecord.members_id', 'fields' => array('BusinessOwner.id', 'BusinessOwner.user_id', 'BusinessOwner.fname', 'BusinessOwner.lname'), 'order' => 'BusinessOwner.fname ASC'));
            $list = array();
            foreach ($teamMembers as $key=>$value) {
              $list[] = $value['BusinessOwner'];
              $list[$key]['member_type'] = "previous";
              $list[$key]['list_user_id'] = "prev_".$value['BusinessOwner']['user_id'];
            }
            if (!empty($teamMembers)) {
                $this->set(array(
                    'code' => Configure::read('RESPONSE_SUCCESS'),
                    'result' => $list,
                    'message' => 'Previous Team Member List.',
                    '_serialize' => array('code', 'result', 'message')
                ));
            } else {
                $this->errorMessageApi('');
            }
        } else {
            $this->errorMessageApi($errMsg);
        }
    }

    
    /**
     * Web service to get the contact list of the user
     * @author Priti Kabra
     */
    public function api_contactList()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        if ($error == 0) {
            $userId = $this->loggedInUserId;
            $this->loadModel('Contact');
            $contactList = $this->Contact->find('all', array('conditions' => array('Contact.user_id' => $userId)));
            $list = array();
            $listIndex = 0;
            foreach ($contactList as $key=>$value) {
                $list[$listIndex] = $value['Contact'];
                $list[$listIndex]['state_name'] = $value['State']['state_subdivision_name'];
                $list[$listIndex]['country_name'] = $value['Country']['country_name'];
                $listIndex++;
            }
            if (!empty($contactList)) {
                $this->set(array(
                    'code' => Configure::read('RESPONSE_SUCCESS'),
                    'result' => $list,
                    'message' => 'Contact List.',
                    '_serialize' => array('code', 'result', 'message')
                ));
            } else {
                $this->errorMessageApi('');
            }
        } else {
            $this->errorMessageApi($errMsg);
        }
    }

    /**
     * Web service to get the list of all countries
     * @author Priti Kabra
     */    
    public function api_listAllCountries()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        if ($error == 0) {
            $this->loadModel('Country');
            $countryList = $this->Country->getAllCountries();
            foreach ($countryList as $key=>$value) {
                $list[] = $value['Country'];
            }
            if (!empty($countryList)) {
                $this->set(array(
                    'code' => Configure::read('RESPONSE_SUCCESS'),
                    'result' => $list,
                    'message' => 'Country List.',
                    '_serialize' => array('code', 'result', 'message')
                ));
            } else {
                $this->errorMessageApi('No country.');
            }
        } else {
            $this->errorMessageApi($errMsg);
        }
    }

    /**
     * Web service to get the list of all states for a country
     * @author Priti Kabra
     */
    public function api_listStateList()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        if ($error == 0) {
            $countryCode = $this->jsonDecodedRequestedData->countryCode;
            $this->loadModel('State');
            $listIndex = 0;
            $stateList = $this->State->getCountryStateList($countryCode);
            foreach ($stateList as $key=>$value) {
                $list[$listIndex]['state_subdivision_id'] = $value['State']['state_subdivision_id'];
                $list[$listIndex]['state_subdivision_name'] = utf8_encode($value['State']['state_subdivision_name']);
                $listIndex++;
            }
            if (!empty($list)) {
                $this->set(array(
                    'code' => Configure::read('RESPONSE_SUCCESS'),
                    'result' => $list,
                    'message' => 'State List.',
                    '_serialize' => array('code', 'result', 'message')
                ));
            } else {
                $this->errorMessageApi('No state.');
            }
        } else {
            $this->errorMessageApi($errMsg);
        }
    }

    /**
     *function to unset payment information 
     *@author Gaurav
     */
    private function __unsetData()
    {
        unset($this->request->data['BusinessOwner']['CC_Name']);
        unset($this->request->data['BusinessOwner']['CC_Number']);
        unset($this->request->data['BusinessOwner']['expiration_month']['month']);
        unset($this->request->data['BusinessOwner']['expiration_year']['year']);
        unset($this->request->data['BusinessOwner']['cvv']);
        unset($this->request->data['BusinessOwner']['code']);  
        unset($this->request->data['BusinessOwner']['password']);
        unset($this->request->data['BusinessOwner']['cpassword']);
    }

    /**
     *function to cancel the subscription of the recurring 
     *@author Gaurav
     */
    public function cancelMembership()
    {
        $this->autoRender=false;
        $this->autoLayout = false;
        if($this->request->is('ajax')) {
            $this->loadModel('Subscription');
            $this->loadModel('Group');
            $loginUserId = $this->Encryption->decode($this->Session->read('Auth.Front.id')); 
            //$userData = $this->User->userInfoById($loginUserId);            
	        $userData = $this->User->find('first', array('conditions' => array('User.id' => $loginUserId, 'Subscription.is_active' => 1)));
	        if (!empty($userData)) {
		        $userGroups = $this->BusinessOwner->find('all',array(
		                            'fields' => array('BusinessOwner.id','BusinessOwner.user_id,BusinessOwner.group_id,BusinessOwner.email,BusinessOwner.fname,BusinessOwner.lname,BusinessOwner.group_role', 'Group.group_leader_id', 'Group.id', 'Group.total_member'),
		                            'conditions'=>array('BusinessOwner.group_id' => $this->Encryption->decode($userData['Groups']['id']))));
		        $request = new AuthorizeNetARB;
		        $response = $request->cancelSubscription($userData['Subscription']['subscription_id']);
		        if($response->xml->messages->message->text == 'Successful.') {
		            $this->Subscription->updateAll(array('Subscription.is_active' => 0),array( 'Subscription.subscription_id' => $userData['Subscription']['subscription_id']));
		            $emailLib = new Email();
		            $format = "both";
		            if($userData['BusinessOwners']['group_role'] == 'participant') {
		                $subject = "FoxHopr: Membership Cancellation";
		                $template = "cancel_membership";
		                $business_owner_name = $userData['BusinessOwners']['fname']." ".$userData['BusinessOwners']['lname'];
		                $variable = array('businessowner'=>$business_owner_name,'case'=>'participant');
		                $to = $userData['BusinessOwners']['email'];
		                $success = $emailLib->sendEmail($to,$subject,$variable,$template,$format);
		            } else if($userData['BusinessOwners']['group_role'] == 'co-leader') {
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
		                            $this->BusinessOwner->updateAll(array('BusinessOwner.group_role' => '"participant"'),array( 'BusinessOwner.user_id' => $group['BusinessOwner']['user_id']));
		                            $this->Group->updateAll(array('Group.group_coleader_id' => NULL),array( 'Group.id' => $group['BusinessOwner']['group_id']));
		                            $subject = "FoxHopr: Membership Cancellation";
		                            $template = "cancel_membership";
		                            $business_owner_name = $group['BusinessOwner']['fname']." ".$group['BusinessOwner']['lname'];
		                            $variable = array('businessowner'=>$business_owner_name,'case'=>'co-leader');
		                            $to = $group['BusinessOwner']['email'];
		                            $success = $emailLib->sendEmail($to,$subject,$variable,$template,$format); 
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
		                        break;
		                        $this->BusinessOwner->id = $this->Encryption->decode($group['BusinessOwner']['id']);
		                        $this->BusinessOwner->saveField('is_kicked', 0);
		                    case 'leader':
		                        $this->BusinessOwner->updateAll(array('BusinessOwner.group_role' => '"participant"'),array( 'BusinessOwner.user_id' => $group['BusinessOwner']['user_id']));
		                        if (($group['Group']['total_member'] == 1) && ($group['Group']['group_leader_id'] == $loginUserId)) {
						            $this->Group->id = $this->Encryption->decode($group['Group']['id']);
		                    		$this->Group->saveField('Group.group_leader_id', NULL);
						        }
		                        $subject = "FoxHopr: Membership Cancellation";
		                        $template = "cancel_membership";
		                        $business_owner_name = $group['BusinessOwner']['fname']." ".$group['BusinessOwner']['lname'];
		                        $variable = array('businessowner'=>$business_owner_name,'case'=>'leader');
		                        $to = $group['BusinessOwner']['email'];
		                        $success = $emailLib->sendEmail($to,$subject,$variable,$template,$format); 
		                        break;
		                    default:
		                        break;
		                    }
		                }

		            }
		        }
		        $this->Session->setFlash('Your request have been registered with FoxHopr','Front/flash_good');
		    } else {
        		$this->Session->setFlash('Your membership plan has already been cancelled','Front/flash_bad');
        	}
        } 
    }

    /**
     *function to reactivate the account
     *@author Priti Kabra
     */
    public function reactivate($regType=NULL,$refId=NULL)
    {
    	$userId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
    	$userData = $this->User->find('first', array('conditions' => array('User.id' => $userId)));
    	$this->set(compact('userData'));
    	if ($this->request->is('post')) {
    		$this->loadModel('Coupon');
    		$this->loadModel('Transaction');
    		$this->loadModel('Subscription');
			//Check Coupon Code
			if(!empty($this->request->data['BusinessOwner']['code'])){
				$couponCheck = $this->checkCouponCode($this->request->data['BusinessOwner']['code']);
				if (isset($couponCheck['error'])) {
    				$checkCouponError = 1;
    				$this->User->validationErrors['couponcheck'] = $couponCheck['error'];
    				$this->request->data = $this->request->data;
				} else {
					$this->request->data['BusinessOwner']['memberShipPrice'] = $couponCheck['newMembershipPrice'];
				}
			} else {
				$this->request->data['BusinessOwner']['memberShipPrice'] = Configure::read('PLANPRICE');
			}
    		$this->request->data['BusinessOwner']['expiration'] = $this->request->data['BusinessOwner']['expiration_month']['month'].'/'.$this->request->data['BusinessOwner']['expiration_year']['year'];
			if (!isset($checkCouponError)) {
				//PAYMENT 
	    		$transaction = new AuthorizeNetAIM;
	    		$transaction->setSandbox(AUTHORIZENET_SANDBOX);
	    		$transaction->setFields(
	    			array(
	    				'amount' => $this->request->data['BusinessOwner']['memberShipPrice'], 
	    				'card_num' => $this->request->data['BusinessOwner']['CC_Number'],
	    				'exp_date' => $this->request->data['BusinessOwner']['expiration'],
	    				'card_code' => $this->request->data['BusinessOwner']['cvv'],
	    				)
	    			);
	    		$response = $transaction->authorizeAndCapture();
	    		if (isset($response->declined) && $response->declined == "1") {
	    			$errMsg = $response->response_reason_text;
	    			$this->Session->setFlash(__($errMsg), 'Front/flash_bad');
	    			$this->__unsetData();
	    		} else if (isset($response->error) && $response->error == "1") {
	    			$errMsg = $response->response_reason_text;
	    			$this->Session->setFlash(__($errMsg), 'Front/flash_bad');
	    			$this->__unsetData();
	    		} else if (isset($response->approved) && $response->approved == "1") {
	    			$userDataUpdate['deactivated_by_user'] = 0;
	    			$userDataUpdate['reactivate'] = 1;
	    			$this->User->id = $userId;
	    			if ($this->User->save($userDataUpdate)) {
	    				if ($regType!=NULL && $refId !=NULL) {
	    					$decrypted=$this->Encryption->decode($refId);
	    					$inviteData=$this->InvitePartner->find('first',array('conditions'=>array('id'=>$decrypted)));
	    					if ($this->request->data['User']['user_email']==$inviteData['InvitePartner']['invitee_email']) {
	    						$data=array('InvitePartner.referral_amount'=>'InvitePartner.referral_amount + 5','InvitePartner.status'=>"'active'",'invitee_userid'=>$this->User->id);
	    						$this->InvitePartner->updateAll($data,array('id'=>$decrypted));  
	    					}                    
	    				}
	    				$transactions['user_id'] = $userId;
	    				$transactions['transaction_id'] = $response->transaction_id;
	    				$transactions['status'] = 1;
	    				$transactions['amount_paid'] = $this->request->data['BusinessOwner']['memberShipPrice'];
	    				$transactions['credit_card_number'] = $this->Encryption->encode(substr($this->request->data['BusinessOwner']['CC_Number'],-4,4));
	    				$this->Transaction->save($transactions);
	    				$txId=$this->Transaction->getLastInsertID();
						//Create Subscription
						$this->request->data['Subscription']['transaction_id'] = $response->transaction_id;
	    				$this->createSubscription($this->request->data, $userId);
						//Update Purchase date
	    				$this->Transaction->id=$txId;
	    				$this->Transaction->save(array('purchase_date'=>$this->Common->getCurrentActiveDate($userId)));
	    				//delete goals
	    				//$this->GroupGoals->resetUserGoals($userId);
	    				$this->Session->write('UID', $this->Encryption->encode($this->User->id));
	    				$this->Session->write('countryInfo',$this->request->data['BusinessOwner']['country_id']);
	    				$this->Session->write('zipInfo',$this->request->data['BusinessOwner']['zipcode']);
						//Create Subscripton ends
	    				$this->redirect(array('controller' => 'groups', 'action' => 'group-selection'));
	    			} else {
	    				foreach ($this->User->validationErrors as $key => $value) {
	    					$err[] = $value[0];
	    				}
	    				$this->Session->setFlash(__($err), 'Front/flash_bad');
	    				$this->__unsetData();
	    			}
	    		} else {
	    			foreach ($this->BusinessOwner->validationErrors as $key => $value) {
	    				$err[] = $value[0];
	    			}
	    			$this->Session->setFlash(__($err[0]), 'Front/flash_bad');
	    			$this->__unsetData();
	    		}
			} else {
                $this->Session->setFlash(__($this->User->validationErrors['couponcheck']), 'Front/flash_bad');
                $this->__unsetData();
                $this->request->data = $this->request->data;
            }
    	}
    }

    /**
     * function to check coupon code after form submit
     * @param string $couponCode coupon code
     * @author Gaurav Bhandari
     * @return array $returnData
     */
    public function checkCouponCode($couponCode)
    {
    	$this->loadModel('Coupon');
    	$returnData = array();
    	//Check Coupon Code
    	$check = $this->Coupon->findByCouponCode(trim($couponCode));
    	if (!empty($check)) {
    		if ($check['Coupon']['is_active'] == 1) {
    			if ($check['Coupon']['coupon_type'] == 'email') {
    				$allEmail = explode(',',$check['Coupon']['email']);
    				$checkVal = in_array($this->request->data['User']['user_email'], $allEmail);
    				if ($checkVal){
    					if (strtotime(date("Y-m-d")) <= strtotime($check['Coupon']['expiry_date'])) {
    						$data['discountValue'] = number_format(($check['Coupon']['discount_amount'] / 100) * Configure::read('PLANPRICE'),2);
    						$this->request->data['BusinessOwner']['memberShipPrice'] = Configure::read('PLANPRICE') - $data['discountValue'];
    						$this->Coupon->updateAll(array("used_count"=>'used_count + 1'),array("coupon_code"=> $couponCode));
    						$returnData = array('newMembershipPrice' => $this->request->data['BusinessOwner']['memberShipPrice']);
    					} else {
    						$returnData = array('error' => 'Coupon has been expired');
    					}
    				} else {
    					$returnData = array('error' => 'Coupon does not match with provided email');
    				}
    			} elseif ($check['Coupon']['usage_limit'] == 0) {
    				if (strtotime(date("Y-m-d")) <= strtotime($check['Coupon']['expiry_date'])) {
    					$data['discountValue'] = number_format(($check['Coupon']['discount_amount'] / 100) * Configure::read('PLANPRICE'),2);
    					$this->Session->write('SESS_ID', $this->Encryption->encode($data['discountValue']));
    					$this->request->data['BusinessOwner']['memberShipPrice'] = Configure::read('PLANPRICE') - $data['discountValue'];
    					$this->Coupon->updateAll(array("used_count"=>'used_count + 1'),array("coupon_code"=> $couponCode));
    					$returnData = array('newMembershipPrice' => $this->request->data['BusinessOwner']['memberShipPrice']);
    				} else {
    					$returnData = array('error' => 'Coupon has been expired');
    				} 
    			} else if ($check['Coupon']['used_count'] < $check['Coupon']['usage_limit']) {
    				if (strtotime(date("Y-m-d")) <= strtotime($check['Coupon']['expiry_date'])) {
    					$data['discountValue'] = number_format(($check['Coupon']['discount_amount'] / 100) * Configure::read('PLANPRICE'),2);
    					$this->request->data['BusinessOwner']['memberShipPrice'] = Configure::read('PLANPRICE') - $data['discountValue'];
    					$this->Coupon->updateAll(array("used_count"=>'used_count + 1'),array("coupon_code"=> $couponCode));
    					$returnData = array('newMembershipPrice' => $this->request->data['BusinessOwner']['memberShipPrice']);
    				} else {
    					$returnData = array('error' => 'Coupon has been expired');
    				} 
    			} else {
    				$returnData = array('error' => 'Coupon has reached maximum redemption limit');
    			}

    		} else {
    			$returnData = array('error' => 'There was a problem applying the coupon, please contact Admin');
    		}           
    	} else {
    		$returnData = array('error' => 'Coupon is invalid');
    	}
    	return $returnData;
    }

    /**
     * function to create subscription
     * @param array $requestData form data
     * @param int $userId user id
     * @author Gaurav Bhandari
     */
    public function createSubscription($requestData,$userId)
    {
    	//Create Subscription
		$subscription = new AuthorizeNet_Subscription;
		$subscription->name = $requestData['User']['user_email'];
		$subscription->intervalLength = "1";
		$subscription->intervalUnit = "months";
		$subscription->startDate = date('Y-m-d',time());
		$subscription->totalOccurrences = "999";
		$subscription->amount = $requestData['BusinessOwner']['memberShipPrice'];
		$subscription->creditCardCardNumber = $requestData['BusinessOwner']['CC_Number'];
		$subscription->creditCardExpirationDate = $requestData['BusinessOwner']['expiration'];
		$subscription->creditCardCardCode = $requestData['BusinessOwner']['cvv'];
		$subscription->billToFirstName = $requestData['BusinessOwner']['fname'];
		$subscription->billToLastName = $requestData['BusinessOwner']['lname'];
		$request = new AuthorizeNetARB; 
		$response = $request->createSubscription($subscription);
		$subscriptionData['subscription_id'] = $response->getSubscriptionId();
		$subscriptionData['user_id'] = $userId;
		$monthChange = date('m') + 1;
		if ($monthChange <= 12) {
			$subscriptionData['next_subscription_date'] = date('Y-'.$monthChange.'-d');
		} else {
			$monthChange = 1;
			$year = date('Y') + 1;
			$subscriptionData['next_subscription_date'] = date($year.'-'.$monthChange.'-d');
		}
		$subscriptionData['transaction_id'] = $requestData['Subscription']['transaction_id'];
		$this->Subscription->create();
		$this->Subscription->save($subscriptionData);
    }

    /**
     *function to get the billing information of the user
     *@author Priti Kabra
     */
    public function api_billing()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        if ($error == 0) {
            $fields = array('Groups.group_type', 'Subscription.is_active', 'BusinessOwners.credit_card_number', 'BusinessOwners.group_update', 'Groups.first_meeting_date', 'Groups.second_meeting_date', 'Groups.meeting_time');
            $userData = $this->User->find('first', array(
                                                'conditions' => array('User.id' => $this->loggedInUserId),
                                                'order' => 'Subscription.created DESC',
                                                'fields' => $fields
                                            )
                                        );
            if (!empty($userData)) {
                $info['group_type'] = $userData['Groups']['group_type'];
                $info['subscription_status'] = $userData['Subscription']['is_active'];
                $info['credit_card_number'] = "XXXX-XXXX-XXXX-".$this->Encryption->decode($userData['BusinessOwners']['credit_card_number']);
                $info['amount_local'] = '$'.Configure::read('PLANPRICE');
                $info['amount_global'] = '$'.Configure::read('PLANPRICE');
				$info['last_updated'] = (date('Y-m-d') > date('Y-m-d', strtotime($userData['BusinessOwners']['group_update']. ' + 30 days'))) ? true : false;
                $this->set(array(
                    'code' => Configure::read('RESPONSE_SUCCESS'),
                    'result' => $info,
                    'message' => 'Billing Information',
                    '_serialize' => array('code', 'result', 'message')
                ));
            } else {
                $this->errorMessageApi('No information');
            }
        } else {
            $this->errorMessageApi($errMsg);
        }
    }
    
    /**
     *function to cancel the subscription of the recurring 
     *@author Priti Kabra
     */
    public function api_cancelMembership()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        if ($error == 0) {
            $userData = $this->User->find('first', array('conditions' => array('User.id' => $this->loggedInUserId, 'Subscription.is_active' => 1)));
            if (!empty($userData)) {
                $userGroups = $this->BusinessOwner->find('all', array(
                                    'fields' => array('BusinessOwner.id', 'BusinessOwner.user_id, BusinessOwner.group_id, BusinessOwner.email, BusinessOwner.fname, BusinessOwner.lname, BusinessOwner.group_role', 'Group.id', 'Group.total_member', 'Group.group_leader_id'),
                                    'conditions' => array('BusinessOwner.group_id' => $userData['BusinessOwners']['group_id'])
                                    )
                                );
                $request = new AuthorizeNetARB;
                $response = $request->cancelSubscription($userData['Subscription']['subscription_id']);
                if ($response->xml->messages->message->text == 'Successful.') {
                    $this->Subscription->updateAll(array('Subscription.is_active' => 0),
                                                   array('Subscription.subscription_id' => $userData['Subscription']['subscription_id'])
                                                   );
                    $format = "both";
                    if($userData['BusinessOwners']['group_role'] == 'participant') {
                        $check = $this->participantCancelPlan($userData, $format);
                    } else if($userData['BusinessOwners']['group_role'] == 'co-leader') {
                        $check = $this->coLeaderCancelPlan($userGroups, $format);
                    } else {
                        $check = $this->leaderCancelPlan($userGroups, $format);
                    }
                    $this->set(array(
                        'code' => Configure::read('RESPONSE_SUCCESS'),
                        'message' => 'Your request have been registered with FoxHopr',
                        '_serialize' => array('code', 'message')
                    ));
                } else {
                    $this->set(array(
                        'code' => Configure::read('RESPONSE_SUCCESS'),
                        'message' => 'Your membership plan has already been cancelled',
                        '_serialize' => array('code', 'message')
                    ));
                }
            } else {
                $this->set(array(
                    'code' => Configure::read('RESPONSE_SUCCESS'),
                    'message' => 'Your membership plan has already been cancelled',
                    '_serialize' => array('code', 'message')
                ));
            }
        } else {
            $this->errorMessageApi($errMsg);
        }
    }
    
    public function participantCancelPlan($userData, $format)
    {
        $emailLib = new Email();
        $subject = "FoxHopr: Membership Cancellation";
        $template = "cancel_membership";
        $business_owner_name = $userData['BusinessOwners']['fname']." ".$userData['BusinessOwners']['lname'];
        $variable = array('businessowner' => $business_owner_name, 'case' => 'participant');
        $to = $userData['BusinessOwners']['email'];
        $success = $emailLib->sendEmail($to, $subject, $variable, $template, $format);
    }

    public function coLeaderCancelPlan($userGroups, $format)
    {
        $emailLib = new Email();
        foreach ($userGroups as $group) {
            $role = $group['BusinessOwner']['group_role'];
            $format = "both";
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
                    $this->BusinessOwner->updateAll(array('BusinessOwner.group_role' => '"participant"'),array( 'BusinessOwner.user_id' => $group['BusinessOwner']['user_id']));
                    $this->Group->updateAll(array('Group.group_coleader_id' => NULL),array( 'Group.id' => $group['BusinessOwner']['group_id']));
                    $subject = "FoxHopr: Membership Cancellation";
                    $template = "cancel_membership";
                    $business_owner_name = $group['BusinessOwner']['fname']." ".$group['BusinessOwner']['lname'];
                    $variable = array('businessowner'=>$business_owner_name,'case'=>'co-leader');
                    $to = $group['BusinessOwner']['email'];
                    $success = $emailLib->sendEmail($to, $subject, $variable, $template, $format); 
                    break;                      
                default:
                    break;
            }
        }
    }
    
    public function leaderCancelPlan($userGroups, $format)
    {
        $emailLib = new Email();
        foreach ($userGroups as $group) {
            $role = $group['BusinessOwner']['group_role'];
            switch ($role) {
                case 'participant':                 
                    $subject = "FoxHopr: Chance to be Group Co-Leader";
                    $template = "upgrade_membership_participant";
                    $business_owner_name = $group['BusinessOwner']['fname']." ".$group['BusinessOwner']['lname'];
                    $variable = array('businessowner'=>$business_owner_name,'case'=>'participant');
                    $to = $group['BusinessOwner']['email'];
                    $success = $emailLib->sendEmail($to, $subject, $variable, $template, $format);
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
                    $success = $emailLib->sendEmail($to, $subject, $variable, $template, $format); 
                    break;
                    $this->BusinessOwner->id = $this->Encryption->decode($group['BusinessOwner']['id']);
                    $this->BusinessOwner->saveField('is_kicked', 0);
                case 'leader':
                    $this->BusinessOwner->updateAll(array('BusinessOwner.group_role' => '"participant"'),array( 'BusinessOwner.user_id' => $group['BusinessOwner']['user_id']));
                    if (($group['Group']['total_member'] == 1) && ($group['Group']['group_leader_id'] == $this->loggedInUserId)) {
                        $this->Group->id = $this->Encryption->decode($group['Group']['id']);
                        $this->Group->saveField('Group.group_leader_id', '');
                    }
                    $subject = "FoxHopr: Membership Cancellation";
                    $template = "cancel_membership";
                    $business_owner_name = $group['BusinessOwner']['fname']." ".$group['BusinessOwner']['lname'];
                    $variable = array('businessowner'=>$business_owner_name,'case'=>'leader');
                    $to = $group['BusinessOwner']['email'];
                    $success = $emailLib->sendEmail($to, $subject, $variable, $template, $format); 
                    break;
                default:
                    break;
            }
        }
    }

	/**
     *function to check adobe connect credentials is valid 
     *@author Gaurav
     */
    private function __checkAdobeConnectValidLogin()
    {
        $returnData = $this->Adobeconnect->adobeConnectLogin();
        return $returnData;
    }
}
