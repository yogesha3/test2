<?php

/**
 * Newsletter Model
 *
 * PHP version 5
 *
 * @category Model
 * @version  1.0
 * @author Laxmi Saini
 */
App::uses('AppModel', 'Model');
class NewsletterSubscribe extends AppModel 
{	
    public $useTable = 'newsletter_subscriptions';
    
    /**
     * Model associations: belongsTo
     *
     * @var array
     * @access public
     *
     */
    public $belongsTo = array (
	    'BusinessOwner' => array (
		    'foreignKey' => false,
		    'conditions'=> array('BusinessOwner.email=NewsletterSubscribe.subscribe_email_id')
		)
	);
}