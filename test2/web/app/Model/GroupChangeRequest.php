<?php


/**
 * Group Change Request Model
 *
 * PHP version 5
 *
 * @category Model
 * @version  1.0
 * @author   Jitendra Shrama
 */

App::uses('AuthComponent', 'Controller/Component');
class GroupChangeRequest extends AppModel 
{

    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'GroupChangeRequest';
    
    /**
     * Order
     *
     * @var string
     * @access public
     */
    public $order = 'GroupChangeRequest.id DESC';

    /**
     * Model associations: belongsTo
     *
     * @var array
     * @access public
     */
   public $belongsTo = array(
   		'BusinessOwner','Group','Profession'
   );
   
    /**
     * Model associations: hasMany
     *
     * @var array
     * @access public
     */
    //public $hasMany = array('Users');
    
}