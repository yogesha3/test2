<?php
/**
 * Faq Model
 *
 * PHP version 5
 *
 * @category Model
 * @version  1.0
 * @author Gaurav Bhandari
 */
App::uses('AppModel', 'Model');
class Faq extends AppModel
{
	public $belongsTo = array(
            'Faqcategorie' => array(
                    'foreignKey' => false,
                    'conditions'=>'Faq.category_id=Faqcategorie.id'
            ),
    );
}