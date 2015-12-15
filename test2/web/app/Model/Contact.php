<?php

/**
* Contact
*
* PHP version 5
*
* @Contact Model
* @version 1.0
* @author Priti Kabra
*        
*/
App::uses('AppModel', 'Model');
class Contact extends AppModel
{
    public $hasOne = array(
        'State' => array(
                      'className' => 'State',
                      'foreignKey' => false,
                      'conditions' => array('State.state_subdivision_id = Contact.state_id'),
                      'fields' => array('State.state_subdivision_name')
                    ),
        'Country' => array(
                      'className' => 'Country',
                      'foreignKey' => false,
                      'conditions' => array('Country.country_iso_code_2 = Contact.country_id'),
                      'fields' => array('Country.country_name')
                    )
      );
  
    public $validate = array (        
        'first_name' => array (
            'required' => array (
                'rule' => 'notEmpty',
                'message' => 'This field is required'
            ),
            'maxLength' => array(
                'rule' => array('between', 1,20),
                'message' => 'First name can have maximum 20 characters',
            ),
        ),
        'last_name' => array (
            'required' => array (
                'rule' => 'notEmpty',
                'message' => 'This field is required'
            ),
            'maxLength' => array(
                'rule' => array('between', 1,20),
                'message' => 'Last name can have maximum 20 characters',
            ),
        ),
        'job_title' => array (
            'required' => array (
                'rule' => 'notEmpty',
                'message' => 'This field is required'
            )
        ),
        'email' => array (
            'required' => array (
              'rule' => 'notEmpty',
              'message' => 'This field is required'
            ),
            'email' => array (
              'rule' => 'email',
              'message' => 'Please enter valid email address'
            )			
        )
    );
    
    /*
     *Web service function checkContactExist to check the contact exists or not for multiple delete
     *@return boolean false if does not exist
     *@author Priti Kabra
    */
    public function checkContactExist($deleteIdArr) {
        $return = 0;
        $collection = new ComponentCollection ();
        $Encryption = new EncryptionComponent($collection);
        foreach ($deleteIdArr as $deleteId) {
            if (!empty($deleteId)) {
                if (!$this->exists($Encryption->decode($deleteId))) {
                    $return = 1;
                }
            }
        }
        return $return;
    }
}
