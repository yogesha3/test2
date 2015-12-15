<?php

/**
* @author Gaurav Bhandari
*        
*/

App::uses('AppModel', 'Model');
class Review extends AppModel
{
    public $belongsTo = array(
        'ReceivedReferral'=> array(
            'foreignKey' => false,
            'conditions' => array('ReceivedReferral.id = Review.referral_id')
        ),
        'User'=> array(
            'foreignKey' => false,
            'fields' => array('User.user_email'),
            'conditions' => array('User.id = Review.user_id')
        ),
        'BusinessOwner' => array (
        	'foreignKey' => false,
        	'fields' => array('BusinessOwner.fname','BusinessOwner.lname'),
        	'conditions' => array('User.id = BusinessOwner.user_id')
        )
    );

    public function getAverage($userId)
    {
        $this->virtualFields = array('totalStar' => 'SUM(Review.services + Review.knowledge + Review.communication)');
        $totalAvgRatingArr = $this->find('all', array('conditions' => array('Review.user_id' => $userId), 'fields' => array('Review.totalStar')));
        return $totalAvgRatingArr[0]['Review']['totalStar'];
    }
    public function getTotalReviewByUserId($userId)
    {
        $reviews = $this->find('count', array('conditions' => array('Review.user_id' => $userId)));
        return $reviews;
    }
}
