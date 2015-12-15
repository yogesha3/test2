<?php

/**
 * This is a webcasts manager controller
 *
 */
App::uses('Email', 'Lib');
class WebcastsController extends AppController 
{
    public $paginate = array(
        'order' => array('Webcast.created' => 'desc')
    );
    public $helpers = array(
    'Youtube.Youtube' => array(
      'iframeOpts' => array(
        'width' => 640,
        'height' => 390,
        'frameborder' => 0
      ),
      'playerVars' => array(
        'autohide'    => 2,
        'autoplay'    => 0,
        'controls'    => 1,
        'enablejsapi' => 0,
        'loop'        => 0, 
        'origin'      => null,
        'rel'         => 0,
        'showinfo'    => 0,
        'start'       => null,
        'theme'       => 'dark'
      ),
    ),
  );
    /**
     * List webcasts
     * @author Laxmi Saini
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
            $this->paginate['Webcast']['order'] = array($this->Session->read('sort') => $this->Session->read('direction'));
        }else{
            $this->paginate['Webcast']['order']=array('modified'=>'desc');
        }
        $this->paginate['Webcast']['limit'] = $perpage;
        if ($search != '') {
            $this->paginate['Webcast']['conditions'] = array(
                'Webcast.title LIKE' => '%' . $search . '%'
            );
        }
        $this->set('webcasts', $this->paginate());
        if ($this->request->is('ajax')) {
            $this->layout = false;
            $this->set('perpage', $perpage);
            $this->set('search', $search);
            $this->render('admin_webcast_ajax_list'); // View, Layout
        }
    }

    /**
    * webcast add for admin panel
    * @author Gaurav
    */
    public function admin_add()
    {
        $this->layout = 'admin';
        $this->includePageJs = array('admin_validation');
        if($this->request->is('post')) {
            $this->Webcast->set($this->request->data);
            $this->request->data['Webcast']['slug'] = Inflector::slug($this->request->data['Webcast']['title'],"-");
            if ($this->Webcast->save($this->request->data)) {
                $this->Session->setFlash(__('Webcast has been added successfully'), 'flash_good');
                //$this->postEmails();
                $this->redirect(array('action' => 'index', 'admin' => true));
            } else {
                $validationErrors=$this->compileErrors('Webcast');
                if($validationErrors!=NULL) {
                    $this->Session->setFlash($validationErrors,'flash_bad');
                }
            }
        }
        $this->set('includePageJs',$this->includePageJs);
    }
    
    /**
     * Edit webcast
     * @param type $webcastId
     * @author Laxmi Saini
     */
    public function admin_edit($webcastId = NULL)
    {
        $this->layout = 'admin';
        $this->includePageJs = array('admin_validation');
        if(!$webcastId) {
            $this->Session->setFlash(__('Invalid Webcast'));
            $this->redirect(array('controller' => 'webcasts', 'action' => 'index', 'admin' =>true));
        }
        $this->set('id',$webcastId);       
        
        $id = $this->Encryption->decode($webcastId);
        if (!is_numeric($id)) {
            $this->Session->setFlash(__('Invalid webcast'), 'flash_bad');
            $this->redirect(array('controller' => 'webcasts', 'action' => 'index', 'admin' => true));
        }
        $webcast = $this->Webcast->findById($id);
        if (!$webcast) {
            throw new NotFoundException(__('Invalid webcast'));
        }
        if ($this->request->is(array('post', 'put'))) {
            $this->request->data['Webcast']['id'] = $this->Encryption->decode($this->request->data['Webcast']['id']);
            $this->Webcast->set($this->request->data);
            $this->request->data['Webcast']['slug'] = Inflector::slug($this->request->data['Webcast']['title'],"-");
            if ($this->Webcast->save($this->request->data)) {
                $this->Session->setFlash(__('Webcast has been updated successfully'), 'flash_good');
                $this->redirect(array('action' => 'index', 'admin' => true));
            } else {
                $validationErrors=$this->compileErrors('Webcast');
                if($validationErrors!=NULL) {
                    $this->Session->setFlash($validationErrors,'flash_bad');
                }
            }            
        }
        if (!$this->request->data) {
            $this->request->data = $webcast;
        }
        $this->set('includePageJs',$this->includePageJs);
    }
        
    /**
    * Delete Webcast
    * @param string $webcastId profession Id
    * @author Laxmi Saini
    */
    public function admin_delete($webcastId = null)
    {
        $this->autoRender = false;
        if($this->request->is('ajax')){
            $this->set('id',$this->request->data['id']);           
            $this->set('info','Webcast');
            $this->set('action','delete');
            $this->set('popupData',$this->parsePopupVars('delete','Webcast'));
            $this->render('/Elements/activate_delete_popup', 'ajax');
        } else if($this->request->is('post')){
            $webcast = $this->Webcast->findById($this->Encryption->decode($webcastId));
            if (!$webcast) {
                $this->Session->setFlash(__('Invalid Webcast'),'flash_bad');
                $this->redirect(array('action' => 'index'));
            } else{
                $this->Webcast->delete($this->Encryption->decode($webcastId));
                $this->Session->setFlash(__('Webcast has been deleted successfully'),'flash_good');
                $this->redirect(array('action' => 'index'));                
            }
        }       
    }
    
    /**
     * to preview webcast youtube link
     * @author Laxmi Saini
     */
    public function admin_play()
    {
        $this->layout='ajax';
        $webcast='';
        if($this->request->is('ajax')) {
            $id= $this->Encryption->decode($this->request->data['id']);
            $webcast=$this->Webcast->findById($id);
           
        }
        $this->set('webcast',$webcast);
    }
    /**
     * Function is used to mail new webcast posted to users
     * @author Rohan Julka
     */
    function postEmails()
    {
        $this->loadModel('User');
        $data = $this->User->find('all',array('conditions' => array('User.user_type' => 'businessOwner'),
                'fields' => array('User.user_email','User.username','BusinessOwner.fname','BusinessOwner.lname')));
        if(!empty($data)) {
            $emailLib = new Email();
            foreach ($data as $row) {
                $subject = "New Webcast Posted";
                $template = "message_new_webcast";
                $format = "both";
                $to = $row['User']['user_email'];
                $emailLib->sendEmail($to,$subject,array('username'=>ucfirst($row['BusinessOwner']['fname']).' '.ucfirst($row['BusinessOwner']['lname'])),$template,$format);
            }
        }
    }
}