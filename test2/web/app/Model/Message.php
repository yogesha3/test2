<?php

/**
 * Message Model
 *
 * PHP version 5
 *
 * @category Model
 * @version  1.0
 * @author Jitendra Sharma
 */
App::uses('AppModel', 'Model');
class Message extends AppModel 
{	
	/**
	 * Model name
	 *
	 * @var string
	 * @access public
	 *
	 */
	public $name = 'Message';
	
	/**
	 * Model validations
	 *
	 * @access Public
	 *
	 */
	public $validate = array (
		'subject' => array (
			'required' => array (
				'rule' => 'notEmpty',
				'message' => 'This field is required'
			)
		),
		'content' => array (
			'required' => array (
				'rule' => 'notEmpty',
				'message' => 'This field is required'
			)
		)
	);
	
    /**
     * Model associations: belongsTo
     *
     * @var array
     * @access public
     *
     */
    /*public $belongsTo = array (
	    'BusinessOwner' => array (
		    'foreignKey' => false,
		    'conditions'=>'BusinessOwner.email=NewsletterSubscribe.subscribe_email_id'
		)
	);*/
	public $hasOne = array(
		'User'=> array(
        	'foreignKey' => false,
        	'conditions' => array('Message.written_by_user=User.id'),
          'fields' => array('User.username,User.id')
        	),
		'BusinessOwner' => array(
            'foreignKey' => false,
            'conditions'=>array('User.id=BusinessOwner.user_id')
            //'fields' => array('BusinessOwner.fname,BusinessOwner.lname,BusinessOwner.member_name')
            ),

    );

    /**
     *Web service function checkMessageExist to check the message exists or not
     *@return boolean false if does not exist
     *@author Priti Kabra
    */
    public function checkMessageExist($deleteIdArr) {
        $return = 0;
        $collection = new ComponentCollection ();
        $Encryption = new EncryptionComponent($collection);
        foreach ($deleteIdArr as $deleteId) {
            if (!$this->exists($Encryption->decode($deleteId))) {
                $return = 1;
            }
        }
        return $return;
    }
}