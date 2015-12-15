<?php

/**
 * WebCastComments Model
 *
 */
App::uses('AppModel', 'Model');
class WebcastComment extends AppModel 
{	
	public $hasOne = array(
		'User'=> array(
          	'foreignKey' => false,
          	'conditions' => 'WebcastComment.user_id = User.id'
        ),
		'BusinessOwners' => array(
            'foreignKey' => false,
            'conditions'=>'User.id = BusinessOwners.user_id'
        )
    );	
}