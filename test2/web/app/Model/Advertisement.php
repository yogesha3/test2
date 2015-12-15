<?php

/** 
 * Advertisement model
 */
App::uses('AppModel', 'Model');
class Advertisement extends AppModel
{
    public $belongsTo = array ('Profession');

    /**
    * Model Validation Array
    */
    public $validate = array(
        'title' => array(
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'Advertisement title should be unique',
                ),
            )
        );
}

