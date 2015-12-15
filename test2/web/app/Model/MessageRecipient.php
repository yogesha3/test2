<?php

/**
 * MessageRecipient Model
 *
 * PHP version 5
 *
 * @category Model
 * @version  1.0
 * @author Jitendra Sharma
 */
App::uses('AppModel', 'Model');
class MessageRecipient extends AppModel 
{	
	/**
	 * Model name
	 *
	 * @var string
	 * @access public
	 *
	 */
	public $name = 'MessageRecipient';
	
	
    /**
     * Model associations: belongsTo
     *
     * @var array
     * @access public
     *
     */
    public $belongsTo = array (

        'Message' => array(
            'foreignKey' => false,
            'conditions' => 'Message.id=MessageRecipient.message_id'
	    	),
        'User' => array(
            //'foreignKey' => 'recipient_user_id',
            'foreignKey' => false,
            'conditions' => 'Message.written_by_user=User.id',
            'fields' => array('User.id','User.username')
        ),
    	'Recipient' => array(
    		'className' => 'User',
    		'foreignKey' => false,
    		'conditions'=>'Recipient.id=MessageRecipient.recipient_user_id',
    		'fields' => array('Recipient.id','Recipient.user_email')
    	),
        'BusinessOwners' => array(
            'foreignKey' => false,
            'conditions'=>'User.id=BusinessOwners.user_id',
            'fields' => array('BusinessOwners.fname,BusinessOwners.lname,BusinessOwners.user_id')
        )
    );

    /*
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