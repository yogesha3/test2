<?php
App::uses('AuthComponent', 'Controller/Component');
class CreditCard extends AppModel 
{
	public $name = 'CreditCard';
    public $belongsTo = array(
            'BusinessOwner' => array(            
                'foreignKey' => false,
                'conditions'=>'CreditCard.user_id=BusinessOwner.user_id'
            ),
            'User' => array(            
                'foreignKey' => false,
                'conditions' => array('CreditCard.user_id=User.id')
            )
    );
	
}