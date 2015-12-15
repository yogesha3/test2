<?php

/**
* Message Comment
*
* PHP version 5
*
* @ReferralComment Model
* @version 1.0
* @author Priti Kabra
*        
*/
App::uses('AppModel', 'Model');
class MessageComment extends AppModel
{
	public $hasOne = array(
		'User'=> array(
          	'foreignKey' => false,
          	'conditions' => array('MessageComment.commented_by_id = User.id')
        ),
		'BusinessOwner' => array(
            'foreignKey' => false,
            'conditions'=>array('User.id = BusinessOwner.user_id')
        )
    );
}