<?php

/**
 * Component using for message related information
 */
App::uses('Component', 'Controller');
class MessagesComponent extends Component 
{   
	public $components = array('Encryption');
	/**
     * to get the attachment list of messages
     * @param int $messageId message id
     * @return array $attachments attachements list 
     * @author Jitendra Sharma
     */
    public function messageAttachment($messageId=NULL)
    {
    	$attachmentData = array();    	
    	if($messageId!=NULL){
    		$messageId = $this->Encryption->decode($messageId);
	    	$model = ClassRegistry::init('MessageAttachment');
	        $attachmentData = $model->find("all",array('conditions'=>array('MessageAttachment.message_id'=>$messageId)));
        }
        return $attachmentData;
    }
    
    /**
     * to get the recipientsID
     * @param int $messageId message id
     * @return array $recipients Recipients list 
     * @author Priti Kabra
     */
    public function messageRecipient($messageId=NULL)
    {
    	$attachmentData = array();
    	if($messageId!=NULL){
	    	$model = ClassRegistry::init('MessageRecipient');
	        $recipientsData = $model->find("all",array('conditions'=>array('MessageRecipient.message_id'=>$messageId)));
        }
        return $recipientsData;
    }
   
}