<?php 
App::uses('AppModel', 'Model');

class Transaction extends AppModel 
{
	public $hasOne = array(
		'BusinessOwner' => array(
            'foreignKey' => false,
            'conditions' => array('Transaction.user_id = BusinessOwner.user_id'),
			'fields' => array('fname', 'lname', 'email', 'zipcode', 'country_id', 'state_id', 'address', 'city')
            ),
		'Subscription' => array (
            'foreignKey' => false,
            'conditions' => array('Transaction.transaction_id = Subscription.transaction_id')
            ),
        'Country' => array (
            'foreignKey' => false,
            'conditions' => array('Country.country_iso_code_2 = BusinessOwner.country_id'),
			'fields' => array('country_name')
        ),
        'State' => array (
            'foreignKey' => false,
            'conditions' => array('State.state_subdivision_id = BusinessOwner.state_id'),
			'fields' => array('state_subdivision_name')
        )
	);
}