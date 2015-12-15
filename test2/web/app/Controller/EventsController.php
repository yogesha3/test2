<?php
class EventsController extends AppController {
	/**
     * Models used by the Controller
     *
     * @var array
     * @access public
     */
    public $uses = array('Webcast','WebcastComment','User');
    public $components=array('Common','Timezone','Paginator','Cookie','Encryption');

	public function beforeFilter() 
    {
        parent::beforeFilter();
    }

    /**
    * Function used for displaying webcasts uploaded by admin
    * @param integer $webcastId Webcast id
    * @author Gaurav Bhandari
    */
    public function webcast($webcastId = NULL)
    {
		$this->set('titleForLayout', 'FoxHopr: Webcast');
        if($webcastId != NULL) {
            $checkValid = $this->Webcast->checkWebcastValid($this->Encryption->decode($webcastId));
            if($checkValid) {
                $notShowingWebcastId = $this->Encryption->decode($webcastId);
            } else {
                $this->redirect(array('action'=>'webcast'));
            }
        } else {
            $notShowingWebcastId = NULL;
        }
    	$latestWebcast = $this->Webcast->getWebcastData($notShowingWebcastId);
        if(!empty($latestWebcast)) {
            $this->Paginator->settings = array(                    
                'conditions' => array('Webcast.id !='=> $this->Encryption->decode($latestWebcast['Webcast']['id'])),
                'order' => array('Webcast.created' => 'desc'),
                'limit' =>3
                );
            $webcastArr = $this->Paginator->paginate('Webcast');
            $commentCount = $this->WebcastComment->find('count', array('conditions' => array('WebcastComment.webcast_id' => $this->Encryption->decode($latestWebcast['Webcast']['id']))));
            $webcastCount = $this->Webcast->find('count', array('conditions' => array('Webcast.id !='=> $this->Encryption->decode($latestWebcast['Webcast']['id']))));
            $this->set(compact('commentCount'));
            $this->set(compact('webcastCount'));
            $this->set(compact('latestWebcast'));
            $this->set(compact('webcastArr'));
        }    	
    }

    /**
    * Function used for adding webcast Comments
    * @author Gaurav Bhandari
    */
    public function webcastAddComment()
    {
        $this->autoRender = false;
        if($this->request->is('ajax')) {
            $webcastId = $this->request->data['webcastid'];
            $isExist = $this->Webcast->checkWebcastValid($this->Encryption->decode($webcastId));
            if($isExist){
                $userId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
                $this->request->data['WebcastComments']['webcast_id'] = $this->Encryption->decode($webcastId);
                $this->request->data['WebcastComments']['user_id'] =  $userId;
                $this->request->data['WebcastComments']['comments'] = htmlentities($this->request->data['comment']);
                $currentDate = date('Y-m-d H:i:s');
                if($this->WebcastComment->save($this->request->data['WebcastComments'])) {
                    $referralComment = $this->User->userInfoById($userId);
                    $data = array(
							'user_id'	=>	$referralComment['BusinessOwners']['user_id'],
                            'profile_image' => $referralComment['BusinessOwners']['profile_image'] != null ? $referralComment['BusinessOwners']['profile_image'] : '',
                            'fname' => $referralComment['BusinessOwners']['fname'],
                            'lname' => $referralComment['BusinessOwners']['lname'],
                            'comment' => htmlentities(htmlspecialchars($this->request->data['comment'])),
                            'created' => $currentDate
                            );
                    $view = new View($this, false);
                    $view->set('referralData',$data);
                    $html_content = $view->render('/Elements/Front/commentBoxView');
                    $result = array(
                        'response' => __($html_content),
                        'responsecode' => Configure::read('RESPONSE_SUCCESS'),
                        );
                    return json_encode($result);
                } else {
                    $result = array(
                        'response' => __('Comment not posted at this time.'),
                        'responsecode' => Configure::read('RESPONSE_ERROR'),
                        );
                    return json_encode($result);
                }

            } else {
                $result = array(
                        'response' => __('Webcast not exist'),
                        'responsecode' => Configure::read('RESPONSE_ERROR'),
                        );
                return json_encode($result);
            }
        } else {
            $result = array(
                        'response' => __('Unauthorize Access'),
                        'responsecode' => Configure::read('RESPONSE_ERROR'),
                        );
            return json_encode($result);
        }
    }

    /**
    * Function used for get webcast comments on page load
    * @author Gaurav Bhandari
    */
    public function webcastGetComments()
    {
        $this->autoLayout=false;
        $this->autoRender=false;
        if($this->request->is('ajax')) {
            if (!empty($this->request->data['webcastid']) || !empty($this->params['named'])) {
                if(!empty($this->params['named'])) {
                    $webcastId = $this->Encryption->decode($this->params['named']['id']);
                } else {
                    $this->WebcastComment->updateAll(array('WebcastComment.is_checked'=>1));
                    $webcastId = $this->Encryption->decode($this->request->data['webcastid']);                    
                }             
                $this->Paginator->settings = array(                    
                    'conditions' => array('WebcastComment.webcast_id' => $webcastId,'is_checked'=>1),
                    'fields' => array('WebcastComment.id,WebcastComment.created,WebcastComment.comments,BusinessOwners.user_id,BusinessOwners.fname,BusinessOwners.lname,BusinessOwners.profile_image'),
                    'limit' => 5,
                    'order' => 'created desc'
                );
                $webcastCommentArr = $this->Paginator->paginate('WebcastComment');
                $view = new View($this, false);
                $view->set('webcastComment',$webcastCommentArr);
                $html_content = $view->render('/Events/webcastcommentlist');
                $result = array(
                    'response' => __($html_content),
                    'responsecode' => Configure::read('RESPONSE_SUCCESS'),
                    );                
                return json_encode($result);
            }
        }
    }

    /**
     * Web service to get the list of webcasts
     * @author Priti Kabra
     */
    public function api_webcast()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        if ($error == 0) {
            $webCastList = $this->Webcast->find('all',
                                                array(
                                                    'order' => 'Webcast.created DESC',
                                                    'recursive' => -1,
                                                    'limit' => $this->jsonDecodedRequestedData->record_per_page,
                                                    'page' => $this->jsonDecodedRequestedData->page_no
                                                )
                                            );
            $webCastCount = $this->Webcast->find('count',
                                                    array(
                                                        'order' => 'Webcast.created DESC',
                                                    )
                                                );
            foreach ($webCastList as $key => $value) {
                $videoThumbnailArr = explode('v=', $value['Webcast']['link']);
                $value['Webcast']['thumbnail'] = 'http://img.youtube.com/vi/' . $videoThumbnailArr[1] . '/mqdefault.jpg';
                $list[] = $value['Webcast'];
            }
            if (!empty($webCastList)) {
                $this->set(array(
                    'code' => Configure::read('RESPONSE_SUCCESS'),
                    'result' => $list,
                    'message' => 'Webcast List.',
                    'page_no' => $this->jsonDecodedRequestedData->page_no,
                    'totalWebcast' => $webCastCount,
                    '_serialize' => array('code', 'result', 'message', 'page_no', 'totalWebcast')
                ));
            } else {
                $this->errorMessageApi('');
            }
        } else {
            $this->errorMessageApi($errMsg);
        }
    }

    /**
     * Web service to get the comments of webcast
     * @author Priti Kabra
     */
    public function api_webcastCommentDetail()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        if ($error == 0) {
            $webcastId = $this->Encryption->decode($this->jsonDecodedRequestedData->webcastId);
            if (!$this->Webcast->exists($webcastId)) {
                $this->errorMessageApi('Webcast does not exist');
            } else {
                $fields = array('WebcastComment.*', 'BusinessOwners.fname', 'BusinessOwners.lname', 'BusinessOwners.profile_image');
                $webcastComment = $this->WebcastComment->find('all', array('conditions' => array('WebcastComment.webcast_id' => $webcastId), 'fields' => $fields, 'order' => 'WebcastComment.created DESC'));
                if (!empty($webcastComment)) {
                    foreach ($webcastComment as $key => $value) {
                        $webcastComment[$key]['WebcastComment']['commented_by'] = $webcastComment[$key]['BusinessOwners']['fname'] . " ". $webcastComment[$key]['BusinessOwners']['lname'];
                        $webcastComment[$key]['WebcastComment']['comments'] = html_entity_decode($webcastComment[$key]['WebcastComment']['comments']);
                        $profile_image = !empty($webcastComment[$key]['BusinessOwners']['profile_image']) ? 'uploads/profileimage/'.$webcastComment[$key]['WebcastComment']['user_id'].'/'.$webcastComment[$key]['BusinessOwners']['profile_image'] : 'no_image.png';
                        $webcastComment[$key]['WebcastComment']['commented_by_profile_image'] = Configure::read('SITE_URL') . 'img/' . $profile_image;
                    }
                    foreach ($webcastComment as $key => $value) {
                        $webcastCommentList[] = $value['WebcastComment'];
                    }
                    $this->set(array(
                            'code' => Configure::read('RESPONSE_SUCCESS'),
                            'result' => $webcastCommentList,
                            'message' => 'Webcast Comment List',
                            '_serialize' => array('code', 'result', 'message')
                        ));
                } else {
                    $this->errorMessageApi('No comment.');
                }
            }
        } else {
            $this->errorMessageApi($errMsg);
        }
    }

    /**
    * Function used to add comment on webcast
    * @author Priti Kabra
    */
    public function api_webcastAddComment()
    {
        $errMsg = $this->checkApiHeaderInfo();
		$error = !empty($errMsg) ? 1 : 0;
        if ($error == 0) {
            $webcastId = $this->Encryption->decode($this->jsonDecodedRequestedData->webcastId);
            if (!$this->Webcast->exists($webcastId)) {
                $this->set(array(
                    'code' => Configure::read('RESPONSE_ERROR'),
                    'message' => 'Webcast video does not exist',
                    'webcastExist' => false,
                    '_serialize' => array('code', 'message', 'webcastExist')
                ));
            } else {
                $data['webcast_id'] =  $webcastId;
                $data['user_id'] =  $this->loggedInUserId;
                $data['comments'] = htmlentities($this->jsonDecodedRequestedData->comment);
                $this->WebcastComment->create();
                if ($this->WebcastComment->save($data)) {
                    $this->set(array(
                        'code' => Configure::read('RESPONSE_SUCCESS'),
                        'message' => 'Your comment has been posted successfully',
                        '_serialize' => array('code', 'message')
                    ));
                } else {
                    $this->errorMessageApi('Please try again later');
                }
            }
        } else {
            $this->errorMessageApi($errMsg);
        }
    }
}