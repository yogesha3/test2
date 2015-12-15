<?php

/**
 * Page Model
 *
 * PHP version 5
 *
 * @category Model
 * @version  1.0
 * @author Gaurav Bhandari
 */
App::uses('AppModel', 'Model');
App::uses('EncryptionComponent', 'Controller/Component');
class Page extends AppModel 
{

    /**
     * to unset page name before save
     * @param array $options
     * @author Laxmi Saini
     */
    public function beforeSave($options = array()) 
    {
        unset($this->data['Page']['page_name']);
    }
    
}