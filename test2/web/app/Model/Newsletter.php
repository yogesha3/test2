<?php

/**
 * Newsletter Model
 *
 * PHP version 5
 *
 * @category Model
 * @version  1.0
 * @author Jitendra Sharma
 */
App::uses('AppModel', 'Model');
class Newsletter extends AppModel 
{	
	/**
	 * Model validations
	 * @access Public
	 */
	public $validate = array (
		'template_name' => array (
		    'rule' => 'notEmpty',
		    'message' => 'This field is required'
		),
		'subject' => array (
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'content' => array (
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		)
	);	
}