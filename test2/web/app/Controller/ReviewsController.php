<?php 
App::uses('Email', 'Lib');
class ReviewsController extends AppController 
{
    public $components=array('Common','Timezone','Paginator','Encryption');
    public $uses = array('ReceivedReferral','Review','LiveFeed','User','ReferralStat','Membership');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('titleForLayout', 'FoxHopr: Reviews');
    }

    public function index()
    {
        $sessionUrl = $this->Session->read('BackUrlAfterLogin');
        if (!empty($sessionUrl)) {
            $this->Session->delete('BackUrlAfterLogin');
        }
        $this->loadModel('Review');
        $userId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
        $this->Review->virtualFields['reviewReferral'] = 'Review.services + Review.knowledge + Review.communication';
        $reviews = $this->Review->find('all', array('conditions' => array('Review.user_id' => $userId), 'fields' => array('Review.id', 'Review.comments', 'Review.services', 'Review.knowledge', 'Review.communication', 'Review.reviewReferral')));
        $totalReview = sizeof($reviews);
        $total = sizeOf($reviews)*Configure::read('RATING_TYPE_NO');
        $totalAvgRatingArr = $this->Review->getAverage($userId);
        $review5Star = 0;
        $review4Star = 0;
        $review3Star = 0;
        $review2Star = 0;
        $review1Star = 0;
        if(!empty($totalAvgRatingArr)) {
            $totalAvgRating = round($totalAvgRatingArr/$total);
            foreach ($reviews as $review) {
                $reviewAvg = round($review['Review']['reviewReferral']/Configure::read('RATING_TYPE_NO'));
                switch ($reviewAvg) {
                    case "5":
                        $review5Star++;
                        break;
                    case "4":
                        $review4Star++;
                        break;
                    case "3":
                        $review3Star++;
                        break;
                    case "2":
                        $review2Star++;
                        break;
                    case "1":
                        $review1Star++;
                        break;
                    default:
                        break;
                }
            }
        } else {
            $totalAvgRating = 0;
        }
        
        if ($total != 0) {
            $review5StarPercent = round(($review5Star/sizeOf($reviews))*100);
            $review4StarPercent = round(($review4Star/sizeOf($reviews))*100);
            $review3StarPercent = round(($review3Star/sizeOf($reviews))*100);
            $review2StarPercent = round(($review2Star/sizeOf($reviews))*100);
            $review1StarPercent = round(($review1Star/sizeOf($reviews))*100);
            $a = array($review5StarPercent, $review4StarPercent, $review3StarPercent, $review2StarPercent, $review1StarPercent);
            $arrRating = array('review1StarPercent'=>$review1StarPercent,'review2StarPercent'=>$review2StarPercent,'review3StarPercent'=>$review3StarPercent,'review4StarPercent'=>$review4StarPercent,'review5StarPercent'=>$review5StarPercent);
            if(array_sum($a) > 100){
                $keyVal = array_keys($arrRating, max($arrRating));
                switch ($keyVal[0]) {
                    case "review5StarPercent":
                    $review5StarPercent--;
                    break;
                    case "review4StarPercent":
                    $review4StarPercent--;
                    break;
                    case "review3StarPercent":
                    $review3StarPercent--;
                    break;
                    case "review2StarPercent":
                    $review2StarPercent--;
                    break;
                    case "review1StarPercent":
                    $review1StarPercent--;
                    break;
                    default:
                    break;
                }
            }            
            $this->set(compact('review5StarPercent','review4StarPercent','review3StarPercent','review2StarPercent','review1StarPercent'));
        }
        
        // Calculating Membership Level
        $level = "";
        $membershipData = $this->Membership->find('all');
        $referralCount = $this->ReferralStat->find('count',array('conditions'=>array('ReferralStat.sent_from_id'=>$userId)));
        if ( $referralCount>=$membershipData[0]['Membership']['lower_limit'] && $referralCount<=$membershipData[0]['Membership']['upper_limit']) {
            $level = 'Bronze';
        } elseif( $referralCount>=$membershipData[1]['Membership']['lower_limit'] && $referralCount<=$membershipData[1]['Membership']['upper_limit']) {
            $level = 'Silver';
        } elseif( $referralCount>=$membershipData[2]['Membership']['lower_limit'] && $referralCount<=$membershipData[2]['Membership']['upper_limit']) {
            $level = 'Gold';
        } elseif( $referralCount>=$membershipData[2]['Membership']['lower_limit'] ) {
            $level = 'Platinum';
        }
        $this->set(compact('totalAvgRating', 'reviews', 'reviewsListing','totalReview','level'));
    }

    /**
    * Function used for get reviews on page load
    * @author Gaurav Bhandari
    */
    public function reviewsListing()
    {
        $sessionUrl = $this->Session->read('BackUrlAfterLogin');
        if (!empty($sessionUrl)) {
            $this->Session->delete('BackUrlAfterLogin');
        }
        $this->loadModel('Review');
        $this->autoLayout=false;
        $this->autoRender=false;
        if($this->request->is('ajax')) {   
            $this->Review->virtualFields['reviewReferral'] = 'Review.services + Review.knowledge + Review.communication';            
            $userId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
            $this->Paginator->settings = array(                    
                'conditions' => array('Review.user_id' => $userId), 
                'fields' => array(
                    'Review.id',
                    'Review.referral_id', 
                    'Review.comments', 
                    'Review.created', 
                    'Review.reviewReferral',
                    'ReceivedReferral.first_name',
                    'ReceivedReferral.last_name'
                    ),
                'limit' => Configure::read('REVIEW_PER_PAGE'),
                'order' => 'Review.created desc'
            );
            $reviewArr = $this->Paginator->paginate('Review');
            $view = new View($this, false);
            $view->set('reviewsListing',$reviewArr);
            $html_content = $view->render('/Reviews/listing');
            $result = array(
                'response' => $html_content,
                'responsecode' => Configure::read('RESPONSE_SUCCESS'),
                );                
            echo json_encode($result);            
        }
    }  

    /**
    * Function used for get reviews on page load
    * @param string $refid referral id , string $userid user id
    * @author Gaurav Bhandari
    */
    public function rating($refid = NULL,$userid = NULL)
    {
        if(empty($refid) || empty($userid)) {
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }
        $referralId = $this->Encryption->decode($refid);
        $userId = $this->Encryption->decode($userid);
        $userInfo = $this->User->userInfoById($userId);
        $checkValid = $this->ReceivedReferral->find('first', array('conditions' => array('ReceivedReferral.id'=>$referralId,'ReceivedReferral.to_user_id'=>$userId)));
        if($checkValid){
            if(!empty($checkValid['ReceivedReferral']['rating_status'])) {
                if (date('Y-m-d H:i:s') > date('Y-m-d H:i:s', strtotime($checkValid['Review']['created']. ' + 15 minutes'))) {
                    $this->Session->setFlash(__('You have already submitted your review for this user'),'Front/flash_bad');
                } else {
					$data['Review']['services'] = $checkValid['Review']['services'];
                 	$data['Review']['knowledge'] = $checkValid['Review']['knowledge'];
                 	$data['Review']['communication'] = $checkValid['Review']['communication'];
                 	$data['Review']['comments'] = $checkValid['Review']['comments'];
                 	$timeleft = strtotime(date("Y-m-d H:i:s")) - strtotime($checkValid["Review"]["created"]);
                	$this->set(compact('data'));
                    $timeValid = 1;
                }
            }   
        } else {
            $this->Session->setFlash(__('Invalid link'),'Front/flash_bad');
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }
        if($this->request->is('post')) {
            $checkValid = $this->ReceivedReferral->find('first', array('conditions' => array('ReceivedReferral.id'=>$referralId,'ReceivedReferral.to_user_id'=>$userId)));
            if($checkValid){
                if(empty($checkValid['ReceivedReferral']['rating_status']) || isset($timeValid)) {
                    $this->request->data['Review']['services'] = !empty($this->request->data['Review']['services']) ? $this->request->data['Review']['services'] : 0;
                    $this->request->data['Review']['knowledge'] = !empty($this->request->data['Review']['knowledge']) ? $this->request->data['Review']['knowledge'] : 0;
                    $this->request->data['Review']['communication'] = !empty($this->request->data['Review']['communication']) ? $this->request->data['Review']['communication'] : 0;                    
                    $this->request->data['Review']['referral_id'] = $this->Encryption->decode($this->request->data['Review']['referral_id']);
                    $this->request->data['Review']['user_id'] = $this->Encryption->decode($this->request->data['Review']['user_id']);
                    $this->request->data['Review']['group_id'] = $this->Encryption->decode($userInfo['Groups']['id']);
                    $total = $this->request->data['Review']['services'] + $this->request->data['Review']['knowledge'] + $this->request->data['Review']['communication'];
                    $this->request->data['Review']['rating'] = round($total/Configure::read('RATING_TYPE_NO'));
                    if (empty($checkValid['ReceivedReferral']['rating_status'])) {
                        $this->Review->create();
                        if($this->Review->save($this->request->data)) {
                            $this->request->data['LiveFeed']['to_user_id'] = $userId;
                            $this->request->data['LiveFeed']['from_user_id'] = $referralId;
                            $this->request->data['LiveFeed']['group_id'] = $this->Encryption->decode($userInfo['Groups']['id']);
                            $this->request->data['LiveFeed']['feed_type'] = 'review';
                            $this->LiveFeed->save($this->request->data['LiveFeed']);
                            $this->ReceivedReferral->id = $referralId;
                            $this->ReceivedReferral->saveField('rating_status',1);
                            $userData = $this->Review->findByReferralId($referralId);          
                            $emailLib = new Email();
                            $subject = "FoxHopr: You have received a review";
                            $template = "referral_rating_success";
                            $format = "both";
                            $business_owner_name = $userData['BusinessOwner']['fname']." ".$userData['BusinessOwner']['lname'];
                            $url = Configure::read('SITE_URL') . 'reviews/index';
                            $variable = array('businessowner'=>$business_owner_name, 'url' => $url);
                            $to = $userData['User']['user_email'];
                            $success = $emailLib->sendEmail($to,$subject,$variable,$template,$format);
                            $this->Session->setFlash(__('Thank You for submitting your review.'),'Front/flash_good');
                            $this->redirect(array('controller' => 'reviews', 'action' => 'rating', $refid, $userid));
                        } else {
                            $this->Session->setFlash(__('Review not saved this time. Please try again later.'),'Front/flash_bad');
                        }
                    } elseif (isset($timeValid)) {
                        $this->Review->id = $this->Encryption->decode($checkValid['Review']['id']);
                        $this->Review->save($this->request->data['Review']);
                        $this->Session->setFlash(__('Thank You for submitting your review.'),'Front/flash_good');
                        $this->redirect(array('controller' => 'reviews', 'action' => 'rating', $refid, $userid));
                    }                    
                } else {
                    $this->Session->setFlash(__('You have already submitted your review for this user.'),'Front/flash_bad');
                    $this->redirect(array('controller' => 'reviews', 'action' => 'rating', $refid, $userid));
                }  
            }
        }
        $this->set('action','rating');
        if(isset($timeleft) && $timeleft >= 0){
			$this->set('timeleft',$timeleft);
        } else {
        	$this->set('timeleft','0');
        }        
        $this->set(compact('refid','userid','checkValid','userInfo'));
    }  
}
