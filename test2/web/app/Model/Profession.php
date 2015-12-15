<?php

/**
 * Profession Model
 *
 * PHP version 5
 *
 * @category Model
 * @version  1.0
 * @author Laxmi Saini
 */
App::uses('AppModel', 'Model');
App::uses('EncryptionComponent', 'Controller/Component');

class Profession extends AppModel 
{
    public $actsAs = array('Csv.Csv');	
    public $validate = array(
        'profession_name' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'Profession name should not be empty.'
            ),
            'size' => array(
                'rule' => array('lengthBetween', 5, 30),
                'message' => 'Profession should be at least 5 chars long'
            )
        ),
        'category_id' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'Select a category.'
            )
        )
    );
    
    public $hasOne = array(
        'ProfessionCategory' => array(
            'foreignKey' => false,
            'conditions' => array('ProfessionCategory.id = Profession.category_id'),
            'fields' => array('ProfessionCategory.name')
        ),
    );
    /**
     * callback function to save method
     * @param type $options
     * @author Laxmi Saini
     */
    public function beforeSave($options = array()) 
    {
        
    }

    /**
    * Encrypted Id after find and upper case first letter of profession name
    * @param array $result data
    * @param bool  $primary
    * @return array $result encrypted id
    * @author Gaurav Bhandari
    */
    public function afterFind($results, $primary = false) 
    {
        $results=parent::afterFind($results);        
        foreach ($results as $key => $val) {
           if (isset($val['Profession']['profession_name'])) {
                $results[$key]['Profession']['profession_name'] = ucfirst($val['Profession']['profession_name']);
            }
        }
        return $results;
    }

}