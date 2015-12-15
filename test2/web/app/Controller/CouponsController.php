<?php 
/**
* Coupons Controller Class
* @author Rohan Julka
*
*/
App::uses('AppController', 'Controller');
App::uses('Email', 'Lib');

class CouponsController extends AppController
{	
    public $paginate = array(
        'order' => array('Coupon.created' => 'desc')
    );	
    public $includePageJs="";
    
    /**
     * callback function on filter
     * @author Rohan Julka
     */
    function beforeFilter()
    {
        parent::beforeFilter();
    }

    /**
     * List of all coupons
     * @author Rohan
     */  
    public function admin_index() 
    {    	
    	$this->layout = 'admin';
        if (!$this->request->is('ajax')) {
            $this->Session->delete('direction');
            $this->Session->delete('sort');
        }
        $perpage = $this->Functions->get_param('perpage', Configure::read('PER_PAGE'), true);
        $page = $this->Functions->get_param('page', Configure::read('PAGE_NO'), false);
        $counter = (($page - 1) * $perpage) + 1;
        $this->set('counter', $counter);
        $search = $this->Functions->get_param('search');
        $this->Functions->set_param('direction');        
        $this->Functions->set_param('sort');
        if ($this->Session->read('sort') != '') {
            $this->paginate['Coupon']['order'] = array($this->Session->read('sort') => $this->Session->read('direction'));
        } else {
            $this->paginate['Coupon']['order'] = array('created' => 'desc');
        }
        $this->paginate['Coupon']['limit'] = $perpage;
        if ($search != '') {
            $this->paginate['Coupon']['conditions'] = array(
                'Coupon.coupon_code LIKE' => '%' . $search . '%'
            );
        }
        $this->set('coupons', $this->paginate());
        if ($this->request->is('ajax')) {
            $this->layout = false;
            $this->set('perpage', $perpage);
            $this->set('search', $search);
            $this->render('admin_coupon_ajax_list'); // View, Layout
        }
        $this->set('titleForLayout', 'Coupon Code');
    }

    /**
    * Function used for making Coupon status changes
    * @param integer $id Coupon id
    * @author Gaurav
    */
    public function admin_status($id = NULL) 
    {
        $this->autoRender = false;
        $data='';
        $action='';
        if ($this->request->is('ajax')) {
            $coupon = $this->Coupon->findById($this->Encryption->decode($this->request->data['id']));
            $isActive = $coupon['Coupon']['is_active'];
            $action='status';
            if (!$isActive) {
                if (strtolower($coupon['Coupon']['coupon_type']) == 'public') {
                    $isAnyOtherCouponActive = $this->Coupon->find('count', array(
                        'conditions' => array('coupon_type' => 'public', 'is_active' => true, 'expiry_date >=' => date('Y-m-d'))));
                    if ($isAnyOtherCouponActive) {
                        $action = 'cannotActiveStatus';
                    } else {
                        $data = 'Activate';
                    }
                } else {
                    $data = 'Activate';
                }
            } else {
                $data = 'De-activate';
            }
            $this->set('action',$action);
            $this->set('data', $data);
            $this->set('info', 'Coupon');
            $this->set('id', $this->request->data['id']);
            $popupData=$this->parsePopupVars($action,'Coupon',$data);
            $this->set('popupData',$popupData);
            $this->render('/Elements/activate_delete_popup', 'ajax');
        }else if ($this->request->is('post')) {
            $coupon = $this->Coupon->findById($this->Encryption->decode($id));
            if (!$coupon) {
                $this->Session->setFlash(__('Invalid Coupon'), 'flash_bad');
                $this->redirect(array('action' => 'index'));
            } else {
                if (!$coupon['Coupon']['is_active']) {
                    $this->Coupon->updateAll(array('Coupon.is_active' => 1), array('Coupon.id ' => $this->Encryption->decode($id)));
                    $this->Session->setFlash(__('Coupon has been activated successfully'), 'flash_good');
                } else if ($coupon['Coupon']['is_active']) {
                    $this->Coupon->updateAll(array('Coupon.is_active' => 0), array('Coupon.id ' => $this->Encryption->decode($id)));
                    $this->Session->setFlash(__('Coupon has been de-activated successfully'), 'flash_good');
                }
                $this->redirect(array('action' => 'index'));
            }
        }
    }

    /**
     * Add Coupons for the admin
     * @author Gaurav
     */
    public function admin_add()
    {
        $this->set('titleForLayout', 'Coupon Code');
        $this->includePageJs = array('admin_validation');
        $this->layout = 'admin';
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $reqData = $this->request->data['Coupon'];
            //Check for coupons with current date as start date 
            $checkCouponCode = $this->Coupon->find('first', array(
                'conditions' => array('Coupon.coupon_code' => $reqData['coupon_code'],
                    'Coupon.start_date >=' => date('Y-m-d'),
            )));
            if (!empty($checkCouponCode)) {
                return 'false';
            } else {
                return 'true';
            }
        }
        if ($this->request->is('post')) {
            $this->Coupon->create();
            if ($this->Coupon->save($this->request->data)) {
                if ($this->request->data['Coupon']['coupon_types'] == 'email') {
                    $this->Coupon->updateAll(array("email"=>"'".$this->request->data['Coupon']['user_email']."'"),array("coupon_code"=> $this->request->data['Coupon']['coupon_code']));
                    $emailData = explode(',', $this->request->data['Coupon']['user_email']);
                    foreach ($emailData as $data) {
                        $emailLib = new Email();
                        $variable = array(
                                    'couponCode' => $this->request->data['Coupon']['coupon_code'], 
                                    'startDate' => $this->request->data['Coupon']['start_date'],
                                    'expiryDate'=> $this->request->data['Coupon']['expiry_date'],
                                    'discount'=> $this->request->data['Coupon']['discount_amount']);
                        $emailLib->sendEmail($data, 'Coupon Code : Foxhopr', $variable, 'coupons','both');
                    }
                }
                $this->Session->setFlash(__('Coupon has been added successfully'), 'flash_good');
                $this->redirect(array('action' => 'index', 'admin' => true));
            }
        }
        $this->set('includePageJs', $this->includePageJs);
    }

    /**
    * Delete coupons
    * @param integer $couponId Coupon id
    * @author Rohan
    */
    public function admin_delete($couponId = null) 
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $this->set('id', $this->request->data['id']);
            $this->set('action', 'delete');
            $this->set('info', 'Coupon');
            $popupData=$this->parsePopupVars('delete','Coupon');
            $this->set('popupData',$popupData);
            $this->render('/Elements/activate_delete_popup', 'ajax');
        } else if ($this->request->is('post')) {
            $coupon = $this->Coupon->findById($this->Encryption->decode($couponId));
            if (!$coupon) {
                $this->Session->setFlash(__('Invalid Coupon'), 'flash_bad');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Coupon->delete($this->Encryption->decode($couponId));
                $this->Session->setFlash(__('Coupon deleted successfully'), 'flash_good');
                $this->redirect(array('action' => 'index'));
            }
        }
    }
}