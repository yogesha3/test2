<?php

/**
 * Plan Model
 *
 * PHP version 5
 *
 * @category Model
 * @version  1.0
 * @author Laxmi Saini
 */
App::uses('AppModel', 'Model');
App::uses('EncryptionComponent', 'Controller/Component');

class Plan extends AppModel 
{
    /**
     * to make plan name uneditable before save
     * @param array $options
     * @author Laxmi Saini
     */
    public function beforeSave($options = array()) 
    {
        unset($this->data['Plan']['plan_name']);
    }
}