<?php

/**
* Referral Comment
*
* PHP version 5
*
* @ReferralComment Model
* @version 1.0
* @author Priti Kabra
*        
*/
App::uses('AppModel', 'Model');
class ReferralComment extends AppModel
{
	public $hasOne = array(
		'User'=> array(
          	'foreignKey' => false,
          	'conditions' => 'ReferralComment.commented_by_id = User.id'
        ),
		'BusinessOwners' => array(
            'foreignKey' => false,
            'conditions'=>'User.id = BusinessOwners.user_id'
        )
    );
}