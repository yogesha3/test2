<?php
App::uses('AuthComponent', 'Controller/Component');
class Suggestion extends AppModel 
{
	public $name = 'Suggestion';
	public $useTable = 'suggestions';
    public $belongsTo = array(                     
        'User' => array(            
            'foreignKey' => false,
            'conditions' => array('Suggestion.user_id=User.id')
        ),
        'BusinessOwner' => array(
            'foreignKey' => false,
            'conditions'=>'Suggestion.user_id=BusinessOwner.user_id'
        ),
    );	
	
}