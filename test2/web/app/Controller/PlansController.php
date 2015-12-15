<?php

/**
 * PlansController for plan manager
 */
class PlansController extends AppController 
{

    public $helper = array('Encryption');
    public $includePageJs='';

    /**
     * callback function
     * @author Laxmi Saini
     */
    public function beforeFilter() 
    {
        parent::beforeFilter();
    }

    /**
    * List Plans 
    * @author Laxmi saini
    */
    public function admin_index() 
    {
        $this->layout = 'admin';
        $plans = $this->Plan->find('all');
        $this->set('plans', $plans);
    }

    /**
    * view plans details 
    * @param string $planId plan id
    * @author Laxmi saini
    */
    public function admin_view($planId = null) 
    {
        $this->layout = 'admin';
        if (!$planId) {
            $this->Session->setFlash(__('Invalid plan'), 'flash_bad');
            $this->redirect(array('controller' => 'plans', 'action' => 'index'));
        }
        $this->set('id', $planId);
        $id = $this->Encryption->decode($planId);
        if (!is_numeric($id)) {
            $this->Session->setFlash(__('Invalid plan'), 'flash_bad');
            $this->redirect(array('controller' => 'plans', 'action' => 'index'));
        }
        $plan = $this->Plan->findById($id);
        if (!$plan) {
            $this->Session->setFlash(__('Invalid plan'), 'flash_bad');
            $this->redirect(array('controller' => 'plans', 'action' => 'index'));
        }
        $this->set('plan', $plan);
    }
    
    /**
    * edit plan
    * @param string $planId plan id
    * @author Laxmi saini
    */
    public function admin_edit($planId = null) 
    {
        $this->layout = 'admin';
        $this->includePageJs=array('admin_validation');
        if (!$planId) {
            $this->Session->setFlash(__('Invalid plan'),'flash_bad');
            $this->redirect(array('controller'=>'plans','action'=>'index'));
        }
        $this->set('id', $planId);
        $id = $this->Encryption->decode($planId);
        if (!is_numeric($id)) {
            $this->Session->setFlash(__('Invalid plan'),'flash_bad');
            $this->redirect(array('controller'=>'plans','action'=>'index'));
        }
        $plan = $this->Plan->findById($id);
        if (!$plan) {
            $this->Session->setFlash(__('Invalid plan'),'flash_bad');
            $this->redirect(array('controller'=>'plans','action'=>'index'));
        }
        if ($this->request->is(array('post', 'put'))) {
	    if($this->request->data['Plan']['discounted_members'] == '' || $this->request->data['Plan']['discounted_members'] == 0){
                $this->request->data['Plan']['discounted_amount'] = NULL;
            }
            $this->request->data['Plan']['id'] = $this->Encryption->decode($this->request->data['Plan']['id']);
            if ($this->Plan->save($this->request->data)) {
                 $this->Session->setFlash(__('Your plan has been updated successfully.'),'flash_good');
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('Unable to update plan.'),'flash_bad');
        }
        if (!$this->request->data) {
            $this->request->data = $plan;
        }
        $this->set('includePageJs',$this->includePageJs);
    }
}