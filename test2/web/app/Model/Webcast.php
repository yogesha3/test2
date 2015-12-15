<?php

/**
 * Webcast Model
 *
 * PHP version 5
 *
 * @category Model
 * @version  1.0
 * @author Gaurav Bhandari
 */
App::uses('AppModel', 'Model');
class Webcast extends AppModel 
{
    public $validate = array(
        'title' => array(            
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'Webcast title is required'
                ),
            'length' => array (
                'rule' => array('maxLength', 70),
                'message' => 'Maximum 70 characters are allowed for Webcast title'
                ),
            'unique' => array(
                    'rule' => 'isUnique',
                    'message' => 'Webcast title already exist'
                )
            ),
        'link' => array(            
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'Webcast link is required'
             ),
        	'validurl' => array(
		        'rule' => 'url',
        		'message' => 'Please enter valid webcast link'
		    )
         ),
        'description' => array(            
            'length' => array (
                'rule' => array('maxLength', 250),
                'message' => 'Maximum 250 characters are allowed for webcast description'
                ),
            )
        );

    public $hasMany = array(
        'WebcastComment' => array(   
            'dependent' => true
        )
    );
    
    
    /**
    * manupulate data before save
    * @param array $options
    * @author Laxmi Saini
    */
    public function beforeSave($options = Array())
    {
        if(!empty($this->data['Webcast']['title'])) {
            $this->data['Webcast']['title'] =  trim($this->data['Webcast']['title']);
        }
        if(!empty($this->data['Webcast']['link'])) {
            $this->data['Webcast']['link'] =  trim($this->data['Webcast']['link']);
        }
        if(!empty($this->data['Webcast']['description]'])) {
            $this->data['Webcast']['description]'] =  trim($this->data['Webcast']['description]']);
        }
        if(!empty($this->data['Webcast']['id'])) {
            unset($this->data['Webcast']['link']);            
        }
    }

    /**
    * Get latest webcast id
    * @author Gaurav Bhandari
    * @return array $latestWebcastId
    */
    public function getWebcastData($webcastId = NULL)
    {
        if($webcastId != NULL) {
            $notDisplayWebcast =  $this->find('first',array('conditions'=>array('id'=>$webcastId)));
        } else {
            $notDisplayWebcast = $this->find('first',array('order'=>array('created'=>'desc')));
        }        
        return $notDisplayWebcast;
    }

    /**
    * Check valid webcast id
    * @param int $webcastId
    * @author Gaurav Bhandari
    * @return bool
    */
    public function checkWebcastValid($webcastId)
    {
        $isValid = $this->find('first',array('conditions'=>array('Webcast.id'=>$webcastId)));
        if($isValid) {
            return true;
        } else {
            return false;
        }
    }
}