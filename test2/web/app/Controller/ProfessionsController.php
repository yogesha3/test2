<?php

/**
 * Controller for Professions Manager
 */
class ProfessionsController extends AppController 
{
    public $paginate = array(
        'order' => array('Profession.created' => 'desc')
    );
    public $components = array('Csv.Csv');
    public $includePageJs = '';
    public $uses = array('ProfessionCategory', 'Profession');

    /**
    * profession listing 
    * @author Laxmi saini
    */
    public function admin_index() 
    {
        if (!$this->request->is('ajax')) {
            $this->Session->delete('direction');
            $this->Session->delete('sort');
        }
        $this->layout = 'admin';
        $perpage = $this->Functions->get_param('perpage', Configure::read('PER_PAGE'), true);
        $page = $this->Functions->get_param('page', Configure::read('PAGE_NO'), false);
        $counter = (($page - 1) * $perpage) + 1;
        $this->set('counter', $counter);

        $search = $this->Functions->get_param('search');
        $this->Functions->set_param('direction');        
        $this->Functions->set_param('sort');
        if ($this->Session->read('sort') != '') {
            $this->paginate['order'] = array($this->Session->read('sort') => $this->Session->read('direction'));
        } else {
            $this->paginate['Profession']['order'] = array('created' => 'DESC');
        }
        $this->paginate['Profession']['limit'] = $perpage;
        if ($search != '') {
            $condition['OR'] = array(
                "Profession.profession_name LIKE" => "%" . trim($search) . "%",
                "ProfessionCategory.name LIKE" => "%" . trim($search) . "%"
            );
            $this->paginate = array('conditions' => $condition);
        }
        $professions = $this->paginate('Profession');
        $this->set('professions', $professions);
        if ($this->request->is('ajax')) {
            $this->layout = false;
            $this->set('perpage', $perpage);
            $this->set('search', $search);
            $this->render('admin_profession_ajax_list'); // View, Layout
        }
    }

    /**
    * profession category listing 
    * @author Priti Kabra
    */
    public function admin_categoryList() 
    {
        if (!$this->request->is('ajax')) {
            $this->Session->delete('direction');
            $this->Session->delete('sort');
        }
        $this->layout = 'admin';
        $perpage = $this->Functions->get_param('perpage', Configure::read('PER_PAGE'), true);
        $page = $this->Functions->get_param('page', Configure::read('PAGE_NO'), false);
        $counter = (($page - 1) * $perpage) + 1;
        $this->set('counter', $counter);

        $search = $this->Functions->get_param('search');
        $this->Functions->set_param('direction');        
        $this->Functions->set_param('sort');
        if ($this->Session->read('sort') != '') {
            $this->paginate['ProfessionCategory']['order'] = array($this->Session->read('sort') => $this->Session->read('direction'));
        }else{
            $this->paginate['ProfessionCategory']['order']=array('modified'=>'desc');
        }
        $this->paginate['ProfessionCategory']['limit'] = $perpage;
        if ($search != '') {
            $this->paginate['ProfessionCategory']['conditions'] = array(
                'ProfessionCategory.name LIKE' => '%' . $search . '%'
            );
        }
        $categoryList = $this->paginate('ProfessionCategory');
        $this->set(compact('categoryList'));
        if ($this->request->is('ajax')) {
            $this->layout = false;
            $this->set('perpage', $perpage);
            $this->set('search', $search);
            $this->render('admin_category_ajax_list');
        }
    }

    /**
    * add profession category
    * @author Priti Kabra
    */
    public function admin_addCategory() 
    {
        $this->layout = 'admin';
        $this->includePageJs = array('admin_validation');
        if ($this->request->is('post')) {
            $this->request->data['ProfessionCategory']['name'] = trim($this->request->data['ProfessionCategory']['name']);
                $this->ProfessionCategory->create();
                if ($this->ProfessionCategory->save($this->request->data)) {
                    $this->Session->setFlash(__('Your category has been added successfully.'), 'flash_good');
                    $this->redirect(array('controller' => 'professions', 'action' => 'categoryList'));
                } else {
                    $validationErr = $this->compileErrors('ProfessionCategory');
                }
        }
        $this->set('includePageJs',$this->includePageJs);
    }

    /**
    * check category exists
    * @author Priti Kabra
    */
    public function admin_checkCategoryExist($categoryId = null) 
    {
		$this->autoRender = false;
		$category = $this->request->data['ProfessionCategory']['name'];
        if (!empty($categoryId)) {
            $conditions = array('ProfessionCategory.name' => $category, 'ProfessionCategory.id !=' => $this->Encryption->decode($categoryId));
        } else {
            $conditions = array('ProfessionCategory.name' => $category);
        }
		$var  = $this->ProfessionCategory->find('first', array('conditions' => $conditions));
		if ($var) {
			echo 'false';
		} else {
			echo 'true';
		}
	}

    /**
    * edit profession category
    * @param string $categoryId category id
    * @author Priti Kabra
    */
    function admin_categoryEdit($categoryId = null) 
    {
        $this->layout = 'admin';
        $this->includePageJs = array('admin_validation');
        $id = $this->Encryption->decode($categoryId);
        $this->set('id', $categoryId);
        if (!$this->ProfessionCategory->exists($id)) {
            $this->Session->setFlash(__('Invalid category'), 'flash_bad');
            $this->redirect(array('controller' => 'professions', 'action' => 'categoryList', 'admin' => true));
        }
        $categoryData = $this->ProfessionCategory->findById($id);
        if ($this->request->is(array('post', 'put'))) {
            $this->request->data['ProfessionCategory']['name'] = trim($this->request->data['ProfessionCategory']['name']);
            $this->request->data['ProfessionCategory']['id'] = $this->Encryption->decode($this->request->data['ProfessionCategory']['id']);
            $category = $this->ProfessionCategory->findByName($this->request->data['ProfessionCategory']['name']);
            if (!empty($category) && $category['ProfessionCategory']['id'] != $categoryId) {
                $this->Session->setFlash(__('Category already exists.'), 'flash_bad');
            } else {
                if ($this->ProfessionCategory->save($this->request->data)) {
                    $this->Session->setFlash(__('Your category has been updated successfully.'), 'flash_good');
                    return $this->redirect(array('controller' => 'professions', 'action' => 'categoryList', 'admin' => true));
                } else {
                    $validationErr = $this->compileErrors('ProfessionCategory');
                    if ($validationErr != NULL) {
                        $this->Session->setFlash($validationErr,'flash_bad');
                    }
                }
            }
        }
        if (!$this->request->data) {
            $this->request->data = $categoryData;
        }
        $this->set('includePageJs',$this->includePageJs);
    }

    /**
    * add a profession
    * @author Laxmi saini
    */
    public function admin_addProfession() 
    {
        $this->layout = 'admin';
        $this->includePageJs = array('admin_validation');
        $this->loadModel('ProfessionCategory');
        $categoryList = $this->ProfessionCategory->find('all', array('order' => 'ProfessionCategory.name ASC'));
        $this->set(compact('categoryList'));
        if ($this->request->is('post')) {
            $this->request->data['Profession']['profession_name'] = trim($this->request->data['Profession']['profession_name']);
            $this->request->data['Profession']['category_id'] = $this->Encryption->decode($this->request->data['Profession']['category_id']);
            $profession = $this->Profession->find('first', array('conditions' => array('Profession.profession_name' => $this->request->data['Profession']['profession_name'], 'Profession.category_id' => $this->request->data['Profession']['category_id'])));
            //$profession = $this->Profession->findByProfession_name($this->request->data['Profession']['profession_name']);
            if ($profession) {
                $this->Session->setFlash(__('Profession for the selected category already exist'), 'flash_bad');
                $this->redirect(array('controller' => 'professions', 'action' => "addProfession", 'admin' => true));
            } else {
                $this->Profession->create();
                $this->request->data['Profession']['created_user_id'] = $this->Auth->User('id');
                $this->request->data['Profession']['modified_user_id'] = $this->Auth->User('id');
                $this->request->data['Profession']['created'] = date('Y-m-d h:i:s');
                if ($this->Profession->save($this->request->data)) {
                    $this->Session->setFlash(__('Your profession has been added successfully.'), 'flash_good');
                    $this->redirect(array('controller' => 'professions', 'action' => 'index'));
                } else {
                    $validationErr=$this->compileErrors('Profession');
                    if($validationErr!=NULL) {
                        $this->Session->setFlash($validationErr,'flash_bad');
                    }
                }
            }
        }
        $this->set('includePageJs',$this->includePageJs);
    }

    /**
    * edit profession
    * @param string $professionId profession id
    * @author Laxmi saini
    */
    function admin_edit($professionId = null) 
    {
        $this->layout = 'admin';
        $this->includePageJs = array('admin_validation');
        $this->set('id', $professionId);
        $id = $this->Encryption->decode($professionId);
        if (!$this->Profession->exists($id)) {
            $this->Session->setFlash(__('Invalid profession'), 'flash_bad');
            $this->redirect(array('controller' => 'professions', 'action' => 'index', 'admin' => true));
        }
        $profession = $this->Profession->findById($id);
        if (!$profession) {
            throw new NotFoundException(__('Invalid Profession'));
        }
        if ($this->request->is(array('post', 'put'))) {
            $this->request->data['Profession']['profession_name'] = trim($this->request->data['Profession']['profession_name']);
            $this->request->data['Profession']['id'] = $this->Encryption->decode($this->request->data['Profession']['id']);
            $profesion = $this->Profession->find('first', array(
                                                    'conditions' => array(
                                                        'Profession.profession_name' => $this->request->data['Profession']['profession_name'],
                                                        'Profession.category_id' => $profession['Profession']['category_id']
                                                        )
                                                    )
                                                 );
            if (!empty($profesion) && $this->request->data['Profession']['id'] != $this->Encryption->decode($profesion['Profession']['id'])) {
                $this->Session->setFlash(__('Profession for the selected category already exist'), 'flash_bad');
            } else {
                if ($this->Profession->save($this->request->data)) {
                    $this->Session->setFlash(__('Your profession has been updated successfully.'), 'flash_good');
                    return $this->redirect(array('action' => 'index', 'admin' => true));
                } else {
                    $validationErr=$this->compileErrors('Profession');
                    if($validationErr!=NULL) {
                        $this->Session->setFlash($validationErr,'flash_bad');
                    }
                }
            }
        }
        if (!$this->request->data) {
            $this->request->data = $profession;
        }
        $this->set('includePageJs',$this->includePageJs);
    }

  
    /**
    * Delete Profession
    * @param string $professionId profession Id
    * @author Laxmi Saini
    */
    public function admin_delete($professionId = null)
    {
        $this->autoRender = false;
        $action='';
        $info='';
        if($this->request->is('ajax')){
            $this->set('id',$this->request->data['id']);  
            $info='Profession';
            $this->loadModel('BusinessOwner');
            $professionHolders=$this->BusinessOwner->find('count',
                    array('conditions'=>array('Profession_id'=>$this->Encryption->decode($this->request->data['id']))));
            if($professionHolders!=0){
                $action='cannotDeleteProfession';
            }else{
                $action = 'delete';
            }
            $this->set('action',$action);
            $this->set('info',$info);
            $popupData=$this->parsePopupVars($action,$info);
            $this->set('popupData',$popupData);
            $this->render('/Elements/activate_delete_popup', 'ajax');
        } else if($this->request->is('post')){
            $profession = $this->Profession->findById($this->Encryption->decode($professionId));
            if (!$profession) {
                $this->Session->setFlash(__('Invalid Profession'),'flash_bad');
                $this->redirect(array('action' => 'index'));
            } else{
                $this->Profession->delete($this->Encryption->decode($professionId));
                $this->Session->setFlash(__('Profession has been deleted successfully'),'flash_good');
                $this->redirect(array('action' => 'index'));                
            }
        }       
    }
    
    /**
    * Import profession by csv
    * @author Jitendra 
    */
    function admin_importProfession() 
    {
        $this->layout = 'admin';
        $this->includePageJs = array('admin_validation');
        if ($this->request->is('post')) {
            if (!empty($this->request->data['Profession']['csv'])) {
                $file = $this->request->data['Profession']['csv'];
                if ($file['type'] != 'text/csv' && $file['type'] != 'application/vnd.ms-excel') {
                    //$this->Profession->validationErrors['email'] = 'Please upload Valid CSV file';
                    $this->Session->setFlash(__('Please upload Valid CSV file'), 'flash_bad');
                } else {
                    // get professions into array    			
                    $profession = $this->Csv->import($file['tmp_name'], array('Profession.profession_name'));
                    array_shift($profession);
                    $errormsg = "";
                    $profession = array_map("unserialize", array_unique(array_map('strtolower', array_map("serialize", $profession))));
                    if (!empty($profession)) {
                        foreach ($profession as $key => $name) {
                            $profname = $name['profession']['profession_name'];
                            $count = $this->Profession->findByProfession_name($profname);
                            if (strlen($profname) < 5 || strlen($profname) > 30) {
                                $errormsg .= "Line " . ($key + 2) . " : Profession name should be 5 to 30 character long.<br/>";
                            } elseif ($count) {
                                $errormsg .= "Line " . ($key + 2) . " : Profession name already exist.<br/>";
                            } elseif (!(preg_match('/^[a-z . \- ]+$/i', trim($profname)))) {
                                $errormsg .= "Line " . ($key + 2) . " : Profession name can contain period, space and hyphen only including alphabets.<br/>";
                            }
                            $profession[$key]['Profession']['profession_name'] = $profname;
                            $profession[$key]['Profession']['created_user_id'] = $this->Auth->User('id');
                            $profession[$key]['Profession']['modified_user_id'] = $this->Auth->User('id');
                        }
                        if (strlen($errormsg) == 0) {
                            $this->Profession->create();
                            if ($this->Profession->saveAll($profession)) {
                                $this->Session->setFlash(__('Profession(s) has been imported successfully'), 'flash_good');
                                $this->redirect(array('controller' => 'professions', 'action' => 'index'));
                            }
                        } else {
                            $this->set('errormsg', $errormsg);
                        }
                    }else{
                       $this->Session->setFlash(__("You can't upload an empty CSV file"), 'flash_bad'); 
                    }
                }
            }
        }
        $this->set('includePageJs',$this->includePageJs);
    }

    /**
    * Export profession by csv
    * @author Jitendra 
    */
    function admin_exportProfession() 
    {
    	$this->layout = 'admin';
    	$this->autoRender = false;
    	if ($this->request->is('get')) {
            $options = array("ProfessionCategory.name AS category_name", "Profession.profession_name");
    		$data = $this->Profession->find('all', array('fields' => $options, 'recursive' => 0,             'order' => array('Profession.created' => 'desc')));
    		if(count($data)>0){
	    		$filepath = WWW_ROOT.'files'.DS.'Profession.csv';    		
	    		$result = $this->Csv->export($filepath, $data);
	    		$fsize = filesize($filepath);
	    		header("Pragma: public");
	    		header("Expires: 0");
	    		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	    		header("Cache-Control: private", false);
	    		header("Content-type: application/octet-stream");
	    		header("Content-Description: File Transfer");
	    		header("Content-Disposition: attachment; filename=" . basename($filepath) . ";");
	    		header("Content-Length: " . $fsize);
	    		readfile($filepath) or die("Errors");
	    		exit(0);	    		
    		}else{
    			$this->Session->setFlash(__('No profession(s) to download.'), 'flash_bad');
    			$this->redirect(array('controller' => 'professions', 'action' => 'index'));
    		}
    	}
    }
}