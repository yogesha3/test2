<?php
/**
 * Group Change Request Model
*
* PHP version 5
*
* @category Model
* @version  1.0
* @author   Rohan Julka
*/

class InvitePartner extends AppModel
{
    
    public $validate = array('user_email' => array(
                'required' => true,
                'message' => 'Email is Required',
        ),);
}