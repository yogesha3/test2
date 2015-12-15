<?php

/**
* Referral
*
* PHP version 5
*
* @category Model
* @version 1.0
* @author Priti Kabra
*        
*/
App::uses('AppModel', 'Model');
class SendReferral extends AppModel
{
    public $belongsTo = array(
      'User'=> array(
            'foreignKey' => false,
            'conditions' => 'SendReferral.to_user_id=User.id'
            ),
      'BusinessOwners' => array(
              'foreignKey' => false,
              'conditions'=>'User.id=BusinessOwners.user_id'
              ),
    );
    public $hasOne = array(
        'Country' => array(
            'foreignKey' => false,
            'conditions' => array('Country.country_iso_code_2 = SendReferral.country_id'),
            'fields' => array('Country.country_name')
        ),
        'State' => array(
            'foreignKey' => false,
            'conditions' => array('State.state_subdivision_id = SendReferral.state_id'),
            'fields' => array('State.state_subdivision_name')
        )
    );
    
    /*
     *Web service function CheckReferralExist to check the referrals exists or not
     *@return boolean false if not exists
     *@author Priti Kabra
     *11 August, 2015
    */
    public function CheckReferralExist($deleteIdArr) {
        $return = 0;
        $collection = new ComponentCollection ();
        $Encryption = new EncryptionComponent($collection);
        foreach ($deleteIdArr as $deleteId) {
            if (!$this->exists($Encryption->decode($deleteId))) {
                $return = 1;
            }
        }
        return $return;
    }
}
