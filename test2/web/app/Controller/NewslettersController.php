<?php
/**
 * Controller for Professions Manager
 */
App::uses('Email', 'Lib');
class NewslettersController extends AppController 
{
    public $paginate = array(
        'order' => array('Newsletter.id' => 'desc')
    );
    public $includePageJs = '';
    
    /**
     * Components
     *
     * @var array
     * @access public
     */
    public $components = array(
    	'Csv.Csv','Profession'
    );
    
    /**
     * Models used by the Controller
     *
     * @var array
     * @access public
     */
    public $uses = array('NewsletterSubscribe','Newsletter');

    /**
     * function to save subscribers email address
     * @author Gaurav Bhandari
     */
    public function subscribe()
    {
        $this->layout = "ajax";
        $this->autoRender = false; 
        if(!$this->NewsletterSubscribe->hasAny(array('NewsletterSubscribe.subscribe_email_id'=>$this->request->data['NewsletterSubscribe']['subscribe_email_id']))){            
            if ($this->NewsletterSubscribe->save($this->request->data)) {
                echo "success";
            } else {
                echo "failed";
            }
        }
        else {
            echo 'already_exists';    
        }
    }  
    
    /**
     * function to show newsletter templates list
     * @author Jitendra Sharma
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
    		$this->paginate['Newsletter']['order'] = array($this->Session->read('sort') => $this->Session->read('direction'));
    	}else{
    		$this->paginate['Newsletter']['order']=array('modified'=>'desc');
    	}
    	//$this->paginate['Newsletter']['order']=array('Newsletter.id'=>'desc');
    	$this->paginate['Newsletter']['limit'] = $perpage;
    	if ($search != '') {
    		$this->paginate['Newsletter']['conditions'] = array(
    				'Newsletter.template_name LIKE' => '%' . $search . '%'
    		);
    	}
    	$this->set('templates', $this->paginate('Newsletter'));
    	if ($this->request->is('ajax')) {
    		$this->layout = false;
    		$this->set('perpage', $perpage);
    		$this->set('search', $search);
    		$this->render('admin_newsletter_ajax_list'); // View, Layout
    	}
    }
    
    /**
     * Delete newsletter templates
     * @param int $templateId Newsletter Template Id
     * @author Jitendra Sharma
     */
    public function admin_delete($templateId = null)
    {
    	$this->autoRender = false;
    	if ($this->request->is('ajax')) {
    		$this->set('id', $this->request->data['id']);
    		$this->set('action', 'delete');
    		$this->set('info', 'Template');
    		$popupData = $this->parsePopupVars('delete','Template');
    		$this->set('popupData',$popupData);
    		$this->render('/Elements/activate_delete_popup', 'ajax');
    	} else if ($this->request->is('post')) {
    		$template = $this->Newsletter->findById($this->Encryption->decode($templateId));
    		if (!$template) {
    			$this->Session->setFlash(__('Invalid template id'), 'flash_bad');
    			$this->redirect(array('action' => 'index'));
    		} else {
    			$this->Newsletter->delete($this->Encryption->decode($templateId));
    			$this->Session->setFlash(__('Template has been deleted successfully'), 'flash_good');
    			$this->redirect(array('action' => 'index'));
    		}
    	}
    }
    
    /**
     * Create newsletter templates
     * @author Jitendra Sharma
     */
     public function admin_createTemplate(){
     	$this->layout = 'admin';
     	$this->includePageJs = array('admin_validation');
     	
     	if($this->request->is('post')){
     		$savetemp = $this->Newsletter->save($this->request->data);
	     	if($savetemp){
	     		$this->Session->setFlash(__('Template has been added successfully.'), 'flash_good');
	     		$this->redirect(array('action' => 'index'));
	     		exit;
	     	}else{
	     		$this->Session->setFlash(__('Template not added.'), 'flash_bad');
	     	}
     	}
     	$this->set('includePageJs',$this->includePageJs);
     }

     /** 
     * Edit newsletter templates
     * @param string $templateId Newsletter Template Id
     * @author Jitendra Sharma
     */
	public function admin_editTemplate($templateId=null){
		$this->layout = 'admin';
	    $this->includePageJs = array('admin_validation');
	    
	    if($this->request->is('get')){
	    	if($templateId==null){
	    		$this->Session->setFlash(__('Invalid Template'),'flash_bad');
	    		$this->redirect(array('controller'=>'newsletters','action'=>'index','admin'=>true));
	    	}
		    $this->request->data = $templateData = $this->Newsletter->findById($this->Encryption->decode($templateId));
		    if(!$templateData){
		        $this->Session->setFlash(__('Invalid Template'),'flash_bad');
		        $this->redirect(array('controller'=>'newsletters','action'=>'index','admin'=>true));
		    }		   
	    }
	    if($this->request->is('post') && $this->request->data['Newsletter']['id']!=""){
	    	$this->request->data['Newsletter']['id'] =  $this->Encryption->decode($this->request->data['Newsletter']['id']);
	        if($this->Newsletter->save($this->request->data)){
		        $this->Session->setFlash(__('Template has been updated successfully'),'flash_good');
		        $this->redirect(array('action' => 'index','admin'=>true));
		    } else {
		        /* foreach ($this->Newsletter->validationErrors as $key => $value){
			        $err[] = $value[0];
			    } */
		        $this->Session->setFlash(__('Template not updated.'), 'flash_bad');
	    	}
	    }
	    $this->set('includePageJs',$this->includePageJs);
	}     
    /**
     * function to show subscribers list
     * @author Jitendra Sharma
     */
    public function admin_subscribeList()
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
            $this->paginate['NewsletterSubscribe']['order'] = array($this->Session->read('sort') => $this->Session->read('direction'));
        }else{
            $this->paginate['NewsletterSubscribe']['order']=array('modified'=>'desc');
        }
        $this->paginate['NewsletterSubscribe']['limit'] = $perpage;
        $this->paginate['NewsletterSubscribe']['order']=array('NewsletterSubscribe.id'=>'desc');
        if ($search != '') {
            $this->paginate['NewsletterSubscribe']['conditions'] = array(
                'NewsletterSubscribe.subscribe_email_id LIKE' => '%' . $search . '%'
            );
        }
        $this->set('subscribers', $this->paginate('NewsletterSubscribe'));
        if ($this->request->is('ajax')) {
            $this->layout = false;
            $this->set('perpage', $perpage);
            $this->set('search', $search);
            $this->render('admin_subscriber_ajax_list'); // View, Layout
        }
    }
    
    /**
     * Export email address of subscribe users
     * @author Jitendra Sharma
     */
    function admin_exportSubscribeEmails()
    {
    	$this->layout = 'admin';
    	$this->autoRender = false;
    	if ($this->request->is('get')) {
    		$filepath = WWW_ROOT . 'files' . DS . 'Subscriber_emails_exported_' . date('d-m-Y-H:i:s') . '.csv';
    		$fields = array('NewsletterSubscribe.subscribe_email_id', 'NewsletterSubscribe.is_registered','NewsletterSubscribe.is_active', 'NewsletterSubscribe.created');
    		// fetch result array
        	$data = $this->NewsletterSubscribe->find('all', array('fields' => $fields,'order'=>array('NewsletterSubscribe.id'=>'desc')));        	
	    	if (count($data) > 0) {
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
	            unlink($filepath);
	            exit(0);
	        } else {
	            $this->Session->setFlash(__('No subscriber email(s) to download.'), 'flash_bad');
	            $this->redirect(array('controller' => 'newsletters', 'action' => 'subscribeList'));
	        }
    	}
    }
    
    /**
     * Delete newsletter subscription users
     * @param int $subscribeId Subscribe user id
     * @author Jitendra Sharma
     */
    public function admin_subscriptionDelete($subscribeId = null)
    {
    	$this->autoRender = false;
    	if ($this->request->is('ajax')) {
    		$this->set('id', $this->request->data['id']);
    		$this->set('action', 'subscriptionDelete');
    		$this->set('info', 'Subscription');
    		$popupData=$this->parsePopupVars('subscriptionDelete','Subscription');
    		$this->set('popupData',$popupData);
    		$this->render('/Elements/activate_delete_popup', 'ajax');
    	} else if ($this->request->is('post')) {
    		$subscribe = $this->NewsletterSubscribe->findById($this->Encryption->decode($subscribeId));
    		if (!$subscribe) {
    			$this->Session->setFlash(__('Invalid subscription id'), 'flash_bad');
    			$this->redirect(array('action' => 'index'));
    		} else {
    			$this->NewsletterSubscribe->delete($this->Encryption->decode($subscribeId));
    			$this->Session->setFlash(__('Subscription has been deleted successfully'), 'flash_good');
    			$this->redirect(array('action' => 'subscribeList'));
    		}
    	}
    }
    
    /**
     * Send newsletters to subscribe users
     * @param encrypted $selectedTemplateId Template id
     * @author Jitendra Sharma
     */
    public function admin_sendNewsletter($selectedTemplateId=null)
    {
    	$this->layout = 'admin';
    	$this->includePageJs = array('admin_validation');
    	
    	if($this->request->is('post')) {
    		$templateId 		= $this->Encryption->decode($this->request->data['Newsletter']['template_id']);
    		$subscribeUsers 	= $this->request->data['Newsletter']['subscriber_list'];
    		$selectedProfession = array();	
    		 
    		if( $subscribeUsers == 'register_user' ) {
    			$selectedProfession = $this->request->data['Newsletter']['profession_list']; 
    		}
    		
    		if( $subscribeUsers == 'all' ) {
    		    $getSubscriberList = $this->NewsletterSubscribe->find('all',array('fields'=>'NewsletterSubscribe.subscribe_email_id','conditions'=>array('NewsletterSubscribe.is_active'=>1)));
    		}elseif( $subscribeUsers == 'not_register_user' ) {
    			$getSubscriberList = $this->NewsletterSubscribe->find('all',array('fields'=>'NewsletterSubscribe.subscribe_email_id','conditions'=>array('NewsletterSubscribe.is_registered'=>0,'NewsletterSubscribe.is_active'=>1)));
    		}else{
    			if($selectedProfession){
    				$getSubscriberList = $this->NewsletterSubscribe->find('all',array('fields'=>'NewsletterSubscribe.subscribe_email_id','conditions'=>array('NewsletterSubscribe.is_registered'=>1,'NewsletterSubscribe.is_active'=>1,'BusinessOwner.profession_id'=>$selectedProfession)));
    			}else{
    				$getSubscriberList = $this->NewsletterSubscribe->find('all',array('fields'=>'NewsletterSubscribe.subscribe_email_id','conditions'=>array('NewsletterSubscribe.is_registered'=>1,'NewsletterSubscribe.is_active'=>1)));
    			}    			
    		}    		
    		// get the newsletter template
    		$newsletter_template = $this->Newsletter->findById($templateId);    		
    		// send newsletter email to subscribers
    		$emailLib = new Email();    		
    		$subject = $newsletter_template['Newsletter']['subject'];
    		$template = "newsletter";
    		$format = "both";
    		$variable = array('content' => $newsletter_template['Newsletter']['content']);   
    		if($getSubscriberList){		
	    		foreach($getSubscriberList as $user_email){
		    		$to = $user_email['NewsletterSubscribe']['subscribe_email_id'];
		    		$success = $emailLib->sendEmail($to,$subject,$variable,$template,$format);
	    		}
	    		$this->Session->setFlash(__('Newsletter has been sent successfully'), 'flash_good');
	    		$this->redirect(array('action' => 'sendNewsletter'));
    		}else{
    			$this->Session->setFlash(__('No user find for this criteria.'), 'flash_bad');
    		}
    	}
    	
    	$templateList = $this->Newsletter->find('list',array('fields'=>array('id','template_name')));
    	$this->set(compact('templateList'));
    	$this->Professsion = $this->Components->load('Profession');
    	$profesionList = $this->Professsion->getAllProfessions(false);
    	$this->set(compact('profesionList'));
    	$this->set('includePageJs',$this->includePageJs);
    	if($selectedTemplateId!=null){
    		$this->request->data['Newsletter']['template_id'] = $selectedTemplateId;
    	}
    }
}