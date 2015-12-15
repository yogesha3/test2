<?php

/**
* ProfessionCategory
*
* PHP version 5
*
* @Contact Model
* @version 1.0
* @author Priti Kabra
*        
*/
App::uses('AppModel', 'Model');
class ProfessionCategory extends AppModel
{
    public $validate = array (        
        'name' => array (
            'required' => array (
                'rule' => 'notEmpty',
                'message' => 'This field is required'
            ),
            'maxLength' => array(
                'rule' => array('between', 3, 35),
                'message' => 'Category name can have maximum 35 characters',
            ),
            'checkUniqueCategory' => array(
                'rule' => 'isUnique',
                'message' => 'Category name already exist'
            )
        )
    );

    /**
    * Encrypted Id after find and upper case first letter of profession name
    * @param array $results data
    * @param bool  $primary
    * @return array $result encrypted id
    * @author Priti Kabra
    */
    public function afterFind($results, $primary = false) 
    {
        $results = parent::afterFind($results);        
        foreach ($results as $key => $val) {
           if (isset($val['ProfessionCategory']['name'])) {
                $results[$key]['ProfessionCategory']['name'] = ucfirst($val['ProfessionCategory']['name']);
            }
        }
        return $results;
    }
}
