<?php

/**
 * ProfessionsComponent contains all common functions related to preofessions used in all module
 * @author Laxmi Saini 
 */

App::uses('Component', 'Controller');
class ProfessionComponent extends Component
{
	public $components = array('Encryption');
	/**
     * List all professions Category
     * @return Array of all Profession Category
     * @author Laxmi Saini
     */
    public function getAllProfessionsCategory($callback=true)
    {
        $model = ClassRegistry::init('ProfessionCategory');
        return $model->find('list',array('fields' => array('ProfessionCategory.id', 'ProfessionCategory.name'),'order'=>array('name ASC'),'callbacks'=>$callback));
    }

    /**
     * List all professions
     * @return Array of all Profession
     * @author Gaurav
     */
    public function getAllProfessions($catId = NULL,$callback=true)
    {
        $model = ClassRegistry::init('Profession');
        $data =  $model->find('list',array('fields' => array(
        									'Profession.id', 'Profession.profession_name'),
        									'conditions' => array('Profession.category_id'=> $this->Encryption->decode($catId)),
        									'order'=>array('profession_name ASC'),
											'callbacks'=>$callback));
        $dataArray = array();
        if(!empty($data)) {
        	foreach($data as $x => $x_value) {
		     $dataArray[][$x] = $x_value;
			}			
        } 
        return $dataArray;        
    }
    
    /**
     * Name of professions from profession id
     * @param int $professionId profession id
     * @return string $professionName name of profession
     * @author Jitendra Sharma
     */
    public function getProfessionNameById($professionId=null)
    {
    	if($professionId!=NULL){
    		$model = ClassRegistry::init('Profession');	    	
	    	$professionInfo = $model->findById($professionId);
	    	$professionName['profession'] = $professionInfo['Profession']['profession_name'];
	    	$professionName['category_name'] = $professionInfo['ProfessionCategory']['name'];
            return $professionName;
    	}
    	return $professionName="";
    }
}