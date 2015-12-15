<?php
/**
 * Country
 *
 * PHP version 5
 *
 * @category Model
 * @author Jitendra Shrama
*/
class Country extends AppModel 
{

	/**
	 * Model name
	 *
	 * @var string
	 * @access public
	 */
	public $name = 'Country';
	
	/**
	 * Model Primary Key
	 *
	 * @var string
	 * @access public
	 */
	public $primaryKey = 'country_id';

  /**
   *return countries array
   *@author Priti Kabra
   */
  function getAllCountries()
  {
      $data = $this->find('all', array('fields' => array('Country.country_name', 'Country.country_iso_code_2'),'order' => array('Country.country_name = "United States"' => 'desc','Country.country_name' => 'asc')));
      return $data;
  }
}	
