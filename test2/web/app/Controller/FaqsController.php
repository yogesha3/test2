<?php 
class FaqsController extends AppController
{
    public $paginate = array('order' => array('Faq.created' => 'desc'));
    public $includePageJs = '';
    
    /**
     * callback function on filter
     * @author Gaurav Bhandari
     */
    function beforeFilter()
    {
        parent::beforeFilter();
        $this->loadModel('Faqcategorie');
        $this->set('title_for_layout','FAQs');
    }

    /**
     * function to show faqs listing
     * @author Gaurav Bhandari
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
            $this->paginate['Faq']['order'] = array($this->Session->read('sort') => $this->Session->read('direction'));
        } else {
            $this->paginate['Faq']['order'] = array('modified' => 'desc');
        }
        $this->paginate['Faq']['limit'] = $perpage;
        if ($search != '') {
            $this->paginate['Faq']['conditions'] = array(
                'Faq.question LIKE' => '%' . $search . '%'
            );
        }
        $this->set('faqs', $this->paginate());
        if ($this->request->is('ajax')) {
            $this->layout = false;
            $this->set('perpage', $perpage);
            $this->set('search', $search);
            $this->render('admin_faq_ajax_list'); // View, Layout
        }
    }

    /**
    * function to add faq
    * @author Gaurav Bhandari
    */
    function admin_add() 
    {
        $this->layout = 'admin';
        $this->includePageJs = array('admin_validation');
        if ($this->request->is('post')) {
            $this->request->data['Faq']['category_id'] = $this->Encryption->decode($this->request->data['Faq']['category_id']);
            $checkExistingData = $this->Faq->find('first', array(
                'conditions' => array(
                    'Faq.category_id' => $this->request->data['Faq']['category_id'],
                    'Faq.question' => $this->request->data['Faq']['question'],
                )
            ));
            if (!$checkExistingData) {
                $this->request->data['Faq']['slug'] = Inflector::slug($this->request->data['Faq']['question'],"-");
                if ($this->Faq->save($this->request->data)) {
                    $this->Session->setFlash(__('Question has been added successfully'), 'flash_good');
                } else {
                    $validationErrors = $this->compileErrors('Faq');
                    if( $validationErrors != NULL ) {
                        $this->Session->setFlash($validationErrors,'flash_bad');
                    }
                }
            } else {
                $this->Session->setFlash(__('Question for the selected category already exist'), 'flash_bad');
                $this->redirect(array('action' => 'add', 'admin' => true));
            }

            $this->redirect(array('action' => 'index', 'admin' => true));
        }
        $categoryList = $this->Faqcategorie->find('list', array('fields' => array('Faqcategorie.id', 'Faqcategorie.category_name'), 'order' => array("Faqcategorie.category_name" => "asc")));
        $this->set('categoryList', $categoryList);
        $this->set('includePageJs', $this->includePageJs);
    }

    /**
    * Edit FAQ
    * @param string $questionId Question id
    * @author Gaurav Bhandari
    */
    public function admin_edit($questionId = Null)
    {
        $this->layout = 'admin';
        $this->includePageJs = array('admin_validation');
        $categoryList = $this->Faqcategorie->find('list',array('fields' => array('Faqcategorie.id', 'Faqcategorie.category_name')));        
        if(!$questionId){
            $this->Session->setFlash(__('Invalid Question'),'flash_bad');
            $this->redirect(array('controller'=>'faqs','action'=>'index','admin'=>true));
        }
        $questionData = $this->Faq->findById($this->Encryption->decode($questionId));
        if(!$questionData){
            $this->Session->setFlash(__('Invalid Question'),'flash_bad');
            $this->redirect(array('controller'=>'faqs','action'=>'index','admin'=>true));
        }  

        if(isset($this->request->data['Faq']['question']) && $this->request->data['Faq']['question']!=""){
	    $this->request->data['Faq']['id'] =  $this->Encryption->decode($this->request->data['Faq']['id']);
        $this->request->data['Faq']['category_id']	=  $this->Encryption->decode($this->request->data['Faqcategorie']['category_id']);
	    $this->request->data['Faq']['question']	=  trim($this->request->data['Faq']['question']);
	    $this->request->data['Faq']['answers']	=  trim($this->request->data['Faq']['answers']);
        $this->request->data['Faq']['slug'] = Inflector::slug($this->request->data['Faq']['question'],"-");
            if($this->Faq->save($this->request->data)){
                $this->Session->setFlash(__('FAQ has been updated successfully'),'flash_good');
                $this->redirect(array('action' => 'index','admin'=>true));
            } else {
                $validationErrors=$this->compileErrors('Faq');
                if($validationErrors!=NULL) {
                    $this->Session->setFlash($validationErrors,'flash_bad');
                }
            }
        } 
        if (!$this->request->data) {
            $questionData['Faq']['category_id'] = $this->Encryption->encode($questionData['Faq']['category_id']);
            $this->request->data = $questionData;
        }
        $this->set('categoryList',$categoryList);
        $this->set('includePageJs',$this->includePageJs);
        $this->set('categoryId',$questionData['Faq']['category_id']);
    } 

    /**
    * function to delete category
    * @param string $questionId Question id
    * @author Gaurav Bhandari
    */
    public function admin_delete($questionId = null) 
    {
        $this->autoRender = false;
        if($this->request->is('ajax')){
            $this->set('id',$this->request->data['id']);
            $this->set('action','delete');
            $this->set('info','Question');
            $popupData=$this->parsePopupVars('delete','Question');
            $this->set('popupData',$popupData);
            $this->render('/Elements/activate_delete_popup', 'ajax');
        } else if($this->request->is('post')){
            $questionData = $this->Faq->findById($this->Encryption->decode($questionId));
            if (!$questionData) {
                $this->Session->setFlash(__('Invalid Category'),'flash_bad');
                $this->redirect(array('action' => 'index','admin'=>true));
            }
            else{
                $this->Faq->delete($this->Encryption->decode($questionId));
                $this->Session->setFlash(__('Question has been deleted successfully'),'flash_good');
                $this->redirect(array('action' => 'index','admin'=>true));                     
            }
        }       
    }

    /**
    * function to show faqs category listing
    * @author Gaurav Bhandari
    */
    public function admin_category()
    {
        $this->loadModel('Faqcategorie');
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
            $this->paginate['Faqcategorie']['order'] = array($this->Session->read('sort') => $this->Session->read('direction'));
        }else{
            $this->paginate['Faqcategorie']['order']=array('modified'=>'desc');
        }
        $this->paginate['Faqcategorie']['limit'] = $perpage;
        if ($search != '') {
            $this->paginate['Faqcategorie']['conditions'] = array(
                'Faqcategorie.category_name LIKE' => '%' . $search . '%'
            );
        }           
        $this->set('categories', $this->paginate('Faqcategorie'));
        if ($this->request->is('ajax')) {
            $this->layout = false;
            $this->set('perpage', $perpage);
            $this->set('search', $search);
            $this->render('admin_category_ajax_list'); // View, Layout
        }
    }

    /**
    * function to add category
    * @author Gaurav Bhandari
    */
    public function admin_addCategory() 
    {
        $this->layout = 'admin';
        $this->includePageJs = array('admin_validation');
        if($this->request->is('post')) {
            $this->loadModel('Faqcategorie');
            if($this->Faqcategorie->save($this->request->data)) {
                $this->Session->setFlash(__('FAQ Category has been added successfully'),'flash_good'); 
                $this->redirect(array('action' => 'category','admin'=>true));
            } else {
                $validationErrors=$this->compileErrors('Faqcategorie');
                if($validationErrors!=NULL) {
                    $this->Session->setFlash($validationErrors,'flash_bad');
                }
            }
            $this->redirect(array('action' => 'addCategory','admin'=>true));
        }
        $this->set('includePageJs',$this->includePageJs);
    }

    /**
    * function to edit category
    * @param string $catId category id
    * @author Gaurav Bhandari
    */
    function admin_editCategory($catId = null) 
    {
        $this->loadModel('Faqcategorie');
        $this->layout = 'admin';
        $this->includePageJs = array('admin_validation');
        if (!$catId) {
            $this->Session->setFlash(__('Invalid Faq Category'), 'flash_bad');
            $this->redirect(array('controller' => 'faqs', 'action' => 'category', 'admin' => true));
        }
        $this->set('id', $catId);
        $id = $this->Encryption->decode($catId);
        $category = $this->Faqcategorie->findById($id);
        if (!$category) {
            $this->Session->setFlash(__('Invalid Category'), 'flash_bad');
            $this->redirect(array('action' => 'category', 'admin' => true));
        }
        if ($this->request->is('post')) {
            $this->request->data['Faqcategorie']['category_name'] = trim($this->request->data['Faqcategorie']['category_name']);
            $this->request->data['Faqcategorie']['id'] = $this->Encryption->decode($this->request->data['Faqcategorie']['id']);
            $category = $this->Faqcategorie->findByCategoryName($this->request->data['Faqcategorie']['category_name']);
            if (!empty($category) && $this->request->data['Faqcategorie']['id'] != $this->Encryption->decode($category['Faqcategorie']['id'])) {
                $this->Session->setFlash(__('Category name already exist.'), 'flash_bad');
            } else {
                if ($this->Faqcategorie->save($this->request->data)) {
                    $this->Session->setFlash(__('Category has been updated successfully.'), 'flash_good');
                    $this->redirect(array('action' => 'category', 'admin' => true));
                }
            }
        }
        if (!$this->request->data) {
            $this->request->data = $category;
        }
        $this->set('includePageJs',$this->includePageJs);
    }

    /**
    * function to delete category
    * @param string $catId categogy id
    * @author Gaurav Bhandari
    */
    public function admin_deleteCategory($catId = null) 
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $this->set('id', $this->request->data['id']);
            $this->set('action', 'deleteCategory');
            $this->set('info', 'Category');
            $popupData=$this->parsePopupVars('deleteCategory','Category');
            $this->set('popupData',$popupData);
            $this->render('/Elements/activate_delete_popup', 'ajax');
        } else if ($this->request->is('post')) {
            $categoryData = $this->Faqcategorie->findById($this->Encryption->decode($catId));
            if (!$categoryData) {
                $this->Session->setFlash(__('Invalid Category'), 'flash_bad');
                $this->redirect(array('action' => 'category', 'admin' => true));
            } else {
                $this->Faqcategorie->delete($this->Encryption->decode($catId));
                $this->Session->setFlash(__('Category deleted successfully'), 'flash_good');
                $this->redirect(array('action' => 'category', 'admin' => true));
            }
        }
    }
}