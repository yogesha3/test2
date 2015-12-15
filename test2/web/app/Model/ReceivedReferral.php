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
class ReceivedReferral extends AppModel
{
    public $belongsTo = array(
        'User'=> array(
            'foreignKey' => false,
            'conditions' => 'ReceivedReferral.from_user_id=User.id'
        ),
        'BusinessOwners' => array(
            'foreignKey' => false,
            'conditions'=>'User.id=BusinessOwners.user_id'
        ),
    );

    public $hasOne = array(
        'Country' => array(
            'foreignKey' => false,
            'conditions' => array('Country.country_iso_code_2 = ReceivedReferral.country_id'),
            'fields' => array('Country.country_name')
        ),
        'State' => array(
            'foreignKey' => false,
            'conditions' => array('State.state_subdivision_id = ReceivedReferral.state_id'),
            'fields' => array('State.state_subdivision_name')
        ),
        'Review' => array(
            'foreignKey' => false,
            'conditions' => array('Review.referral_id = ReceivedReferral.id')
           
        )
    );


    /**
     *Function CheckReferralExist to check the referrals exists or not
     *@return boolean false if not exists
     *@author Priti Kabra
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

    /**
     *Function is used to get info by referral id
     *@author Gaurav Bhandari
    */
    public function getInfoByReferralId($refid) {
        $referralData = $this->find('first', array('conditions' => array('ReceivedReferral.id' => $refid)));
        return $referralData;
    }
}
