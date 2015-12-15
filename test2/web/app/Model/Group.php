<?php

/**
 * Group Model
 *
 * PHP version 5
 *
 * @category Model
 * @version  1.0
 * @author Laxmi Saini
 */
App::uses('AppModel', 'Model');
App::uses('EncryptionComponent', 'Controller/Component');
class Group extends AppModel 
{
    public $belongsTo = array(
            'Country' => array(
                    'foreignKey' => false,
                    'conditions'=>'Group.country_id=Country.country_iso_code_2'
                ),
            'State' => array(            
                        'foreignKey' => false,
                        'conditions'=>'Group.state_id=State.state_subdivision_id'
            ),
            'User' => array(            
                        'foreignKey' => false,
                        'conditions'=>'Group.group_created_by=User.id'
            ),
            'AvailableSlots' => array(
            			'foreignKey' => false,
            			'conditions' => 'Group.id = AvailableSlots.group_id'
            )
    );
    public $validate = array(
    	'group_leader_id' => array(			
			'R2' => array(
				'rule' => 'isUnique',
				'message' => 'You have already assign a group.',
			)
		),
	); 

    /**
    * Encrypted Id and convert time format to 24 hour time format after find
    * @param array $result data
    * @param bool  $primary
    * @return array $result encrypted id
    * @author Gaurav Bhandari
    */
    public function afterFind($results, $primary = false) 
    {
        $results=parent::afterFind($results);        
        foreach ($results as $key => $val) {
            if (isset($val['Group']['meeting_time'])) {
                $results[$key]['Group']['meeting_time'] = date("H:i", strtotime($val['Group']['meeting_time']));
            }
        }
        return $results;
    }

    /**
    * to manipulate data before save
    * @param array $options
    * @author Laxmi Saini
    */
    public function beforeSave($options = array()) 
    {
        if (isset($this->data['Group']['id'])) {
            unset($this->data['Group']['uneditable']);
        }
        if (isset($this->data['Group']['meeting_time'])) {
            $this->data['Group']['meeting_time'] = date("H:i:s", strtotime($this->data['Group']['meeting_time']));
        }
    }
    
    /**
    * to check profession occupied in group
    * @param string $groupId
    * @author Gaurav Bhandari
    */
    public function isProfessionOccupiedInGroup($groupId = Null , $professionId = Null)
    {
        $groupData = $this->findById($groupId);
        $parts = explode(',', $groupData['Group']['group_professions']);
        $result = in_array($professionId, $parts);
        return $result;
    }
}