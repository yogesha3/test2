<?php
/**
 * Faqcategorie Model
 *
 * PHP version 5
 *
 * @category Model
 * @version  1.0
 * @author Gaurav Bhandari
 */
App::uses('AppModel', 'Model');
class Faqcategorie extends AppModel
{
    public $validate = array(
        'category_name' => array(
            'R1' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Category name is required',
                  ),
            'R2' => array(
                'rule' => 'isUnique',
                'message' => 'Category name already exist',
                ),
            )
        );
}