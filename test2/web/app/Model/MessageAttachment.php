<?php

/**
 * MessageAttachment Model
 *
 * PHP version 5
 *
 * @category Model
 * @version  1.0
 * @author Jitendra Sharma
 */
App::uses('AppModel', 'Model');
class MessageAttachment extends AppModel 
{	
	/**
	 * Model name
	 *
	 * @var string
	 * @access public
	 *
	 */
	public $name = 'MessageAttachment';
	
	
	/**
	 * Delete attachments afrer message delete
	 * @var string
	 * @access public
	 */
	/* public function afterDelete() {
	    $file = new File(WWW_ROOT ."/img/uploads/message_attachments/".$this->data['MessageAttachment']['filename']);
	    $file->delete();
	} */

    
}