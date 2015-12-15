<?php
/**
* Affiliate
*
* PHP version 5
*
* @category Model
* @version 1.0
* @author Jitendra Shrama
*        
*/
class Affiliate extends AppModel
{

    /**
    * Model name
    *
    * @var string
    * @access public
    *        
    */
    public $name = 'Affiliate';

    public $components = array('Encryption');
    
    /**
    * Model validations
    *
    * @access Public
    *        
    */
    public $validate = array (        
    	'email' => array (
    		'required' => array (
    			'rule' => 'notEmpty',
    			'message' => 'This field is required'
    		),
    		'unique' => array (
	    		'rule' => 'isUnique',
	    		'message' => 'User e-mail already exists.'
    		)			
		)    		
    );    
}
