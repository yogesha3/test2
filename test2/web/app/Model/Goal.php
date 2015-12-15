<?php
App::uses('AuthComponent', 'Controller/Component');
class Goal extends AppModel 
{
	public $name = 'Goal';
    public $belongsTo = array(
            'BusinessOwner' => array(            
                    'foreignKey' => false,
                    'conditions'=>'Goal.user_id=BusinessOwner.user_id'
            ),            
            'User' => array(            
                    'foreignKey' => false,
                    'conditions' => array('BusinessOwner.user_id=User.id')
            )
    );	
	
}