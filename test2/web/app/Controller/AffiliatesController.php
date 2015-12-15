<?php
/**
* Affliates Controller
*
* Controller managing affiliate related actions.
* 
* Affiliates class to handle all the affiliate related process
* It contains list of all the affiliates
* It showing the status of all affiliates
*/
App::uses('Email', 'Lib');

class AffiliatesController extends AppController {
	//public $components = array('GroupGoals');
	
	public $paginate = array(
			'order' => array('Affiliate.id' => 'desc')
	);
	
	/**
	 * Model to be used in this Class
	 */
	/* public $uses = array (
			'Group','BusinessOwner','PrevGroupRecord','Setting' 
	); */
	
	/**
	 * callback function on filter
	 * 
	 * @author Jitendra Sharma
	 *        
	 */
	public function beforeFilter() {
		parent::beforeFilter ();
		$this->set('title_for_layout','Affiliates');
		$this->Auth->allow ( array('index') );
	}
	
	/**
	 * list all the affliates
	 * @author Jitendra Sharma
	 */
    public function admin_index()
    {
    	if (!$this->request->is('ajax')) {
            $this->Session->delete('direction');
            $this->Session->delete('sort');
        }
        $this->layout = 'admin';
        $perpage = $this->Functions->get_param('perpage',10,true);
        $page = $this->Functions->get_param('page',1,false);
        $counter = (($page - 1) * $perpage) + 1;
        $this->set('counter', $counter);

        $search = $this->Functions->get_param('search');
        $this->Functions->set_param('direction');        
        $this->Functions->set_param('sort');
        if ($this->Session->read('sort') != '') {
            $this->paginate['Affiliate']['order'] = array($this->Session->read('sort') => $this->Session->read('direction'));
        }else{
            $this->paginate['Affiliate']['order']=array('modified'=>'desc');
        }
        $this->paginate['Affiliate']['limit'] = $perpage;
        $this->paginate['Affiliate']['order']=array('Affiliate.id'=>'desc');
        if ($search != '') {
            $this->paginate['Affiliate']['conditions'] = array('OR' => array(
            		'Affiliate.name LIKE' => '%' . $search . '%',
            		'Affiliate.email LIKE' => '%' . $search . '%'
            	)                
            );
        }
        $this->set('affiliates', $this->paginate('Affiliate'));
        if ($this->request->is('ajax')) {
            $this->layout = false;
            $this->set('perpage', $perpage);
            $this->set('search', $search);
            $this->render('admin_affiliate_ajax_list'); // View, Layout
        }
    }
	
	/**
	 * add new affliate
	 * @author Jitendra Sharma
	 */
	public function admin_addAffiliate() {
		$this->includePageJs = array('admin_validation');		
		if($this->request->is('post')){
			if($this->Affiliate->save($this->request->data)){
				$id = $this->Affiliate->id;
				$affiliateLink = Configure::read('SITE_URL')."affiliate/".$this->Encryption->encode('foxhopr')."-fx-hr-".$this->Encryption->encode($id);
				$this->Affiliate->savefield('link',$affiliateLink);
				 if($this->request->data['Affiliate']['isemail']){
				 	// send email to affiliate user
				 	$emailLib = new Email();
				 	$subject = "FoxHopr - Affiliate Created";
				 	$template = "affiliate_create_user_notify";
				 	$format = "both";
				 	$to = $this->request->data['Affiliate']['email'];
				 	$username = $this->request->data['Affiliate']['name'];
				 	$emailLib->sendEmail($to,$subject,array('username' => $username, 'link' => $affiliateLink),$template,$format);
				 }
				 $this->Session->setFlash(__('Affiliate has been added successfully.'), 'flash_good');
				 $this->redirect(array('action' => 'addAffiliate'));
			}
			$affiliateLink = $this->request->data['Affiliate']['linkhidden'];
		}else{
			$affiliate = $this->Affiliate->find('first', array('fields'=>'id,name','order' => array('id' => 'DESC'), 'limit' => 1));
			$id = (!empty($affiliate)) ? $this->Encryption->encode($this->Encryption->decode($affiliate['Affiliate']['id']) + 1) : $this->Encryption->encode(1);
			$affiliateLink = Configure::read('SITE_URL')."affiliate/".$this->Encryption->encode('foxhopr')."-fx-hr-".$id;
		}
		
		$this->set(compact('affiliateLink'));
		$this->set('includePageJs', $this->includePageJs);
	}
	
	/**
	 * Delete Affiliate
	 * @param int $affiliateId affiliate id
	 * @author Jitendra Sharma
	 */
	public function admin_affiliateDelete($affiliateId = null)
	{
		$this->autoRender = false;
		if ($this->request->is('ajax')) {
			$this->set('id', $this->request->data['id']);
			$this->set('action', 'affiliateDelete');
			$this->set('info', 'Affiliate');
			$popupData=$this->parsePopupVars('subscriptionDelete','affiliate entry');
			$this->set('popupData',$popupData);
			$this->render('/Elements/activate_delete_popup', 'ajax');
		} else if ($this->request->is('post')) {
			$affiliate = $this->Affiliate->findById($this->Encryption->decode($affiliateId));
			if (!$affiliate) {
				$this->Session->setFlash(__('Invalid Affiliate id'), 'flash_bad');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Affiliate->delete($this->Encryption->decode($affiliateId));
				$this->Session->setFlash(__('Affiliate entry has been deleted successfully.'), 'flash_good');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
	
	/**
	 * Function to view the detail of affiliate in popup
	 * @author Jitendra Sharma
	 */
	public function admin_affiliateDetail(){
		$affiliateId = $this->Encryption->decode($this->request->data['affiliateId']);
		$affiliateDetail = $this->Affiliate->find('first',array('fields'=>'Affiliate.*','conditions'=>array('Affiliate.id'=>$affiliateId)));
		$this->set('affiliateDetail', $affiliateDetail);
	}
	
	/**
	 * Handle click of affliate link from email
	 * @author Jitendra Sharma
	 * @params $affiliateId affiliate id
	 */
	public function index($affiliateId=null){
		if(!$this->Session->check('current_affiliate_url')){
			$this->Session->write('current_affiliate_url',Router::url( $this->here, true ));
			$this->updateTraffic(Router::url( $this->here, true ));
		}else{
			$currentUrl = $this->Session->read('current_affiliate_url');
			$clickUrl   = Router::url( $this->here, true );
			if($clickUrl!=$currentUrl){
				$this->Session->write('current_affiliate_url',$clickUrl);
				$this->updateTraffic($clickUrl);
			}
		}
		$this->redirect(array('controller' => 'pages', 'action' => 'home'));
	}
	
	public function updateTraffic($url){
		$urlInfo = explode("-fx-hr-",$url);		
		$affiliateId = $this->Encryption->decode($urlInfo['1']);
		$this->Affiliate->updateAll(
				array('Affiliate.traffic_generated' => 'Affiliate.traffic_generated + 1'),
				array('Affiliate.id' => $affiliateId)
		);		
	}
	
	
	
	
}