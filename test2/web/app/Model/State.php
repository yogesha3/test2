<?php
/**
 * State
 *
 * PHP version 5
 *
 * @category Model
 * @author Priti Kabra
*/
class State extends AppModel 
{

    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'State';
    
    /**
     * Model Primary Key
     *
     * @var string
     * @access public
     */
    public $primaryKey = 'country_id';
    
    /**
     *return state list
     *Param countryId country_code
     *@author Priti Kabra
     *4 Aug, 2015
     */
    function getCountryStateList($countryId = null)
    {
        $data = $this->find('all', array(
                              'conditions' => array(
                                  'State.country_code_char2' => $countryId
                              ), 'fields' => array('State.state_subdivision_id', 'State.state_subdivision_name'),
				'order' => array('State.state_subdivision_name' => 'asc')));
        return $data;
    }
}	
