<?php
App::uses('CakeEmail', 'Network/Email');
App::uses('Email', 'Lib');
class SuggestionsController extends AppController
{
    public $paginate = array(
            'order' => array('Feedback.created' => 'desc')
    );
    public $user = array('User');
    /**
     * List Feedbacks
     * @author Rohan Julka
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
            $this->paginate['Suggestion']['order'] = array($this->Session->read('sort') => $this->Session->read('direction'));
        } else {
            $this->paginate['Suggestion']['order'] = array('Suggestion.created' => 'desc');
        }
        $this->paginate['Suggestion']['limit'] = $perpage;
        if ($search != '') {
            $nameSearch="concat(BusinessOwner.fname,' ',BusinessOwner.lname) LIKE ";
            $this->paginate['Suggestion']['conditions']['OR']=array($nameSearch=>'%' . $search . '%',
                'BusinessOwner.group_id LIKE '=>'%' . $search . '%',
                'BusinessOwner.email LIKE '=>'%' . $search . '%');
        }
        $this->paginate['Suggestion']['fields'] = array('BusinessOwner.fname','BusinessOwner.lname','Suggestion.*','BusinessOwner.group_id','BusinessOwner.email');
        //pr($this->paginate());exit;
        $this->set('feedbacks', $this->paginate('Suggestion'));
        if ($this->request->is('ajax')) {
            $this->layout = false;
            $this->set('perpage', $perpage);
            $this->set('search', $search);
            $this->render('admin_suggestion_ajax_list'); // View, Layout
        }
    }
    
    /**
     * add a Feedback
     * @author Rohan Julka
     */
    public function add()
    {
        $this->layout= false;
        $this->autoRender = false;
        $loggedinUser = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
        $this->includePageJs = array('admin_validation');        
        if ($this->request->is('post')) {
           $adminData = $this->User->find('first',array('conditions'=>array('User.user_type'=>'admin')));
           $dataToInsert = array('Suggestion'=>array('user_id'=>$loggedinUser,'message'=>$this->request->data['Suggestion']['message']));
           if($this->Suggestion->save($dataToInsert)) {
               $emailLib = new Email();
               $to = $adminData['User']['user_email'];
               $subject = 'FoxHopr - New Suggestion Received';
               $template ='user_suggestion_posted';
               $variable = array( );
               $success = $emailLib->sendEmail($to,$subject,$variable,$template,'both');
              $this->Session->setFlash('Thank you for providing your suggestions.','Front/flash_good');
              $this->redirect(array('controller'=>'dashboard','action'=>'index'));
           } else {
               $this->Session->setFlash('Internal Error Occured','Front/flash_bad');
           }           
        }
        $this->set('includePageJs', $this->includePageJs);
    }
    
    public function admin_view()
    {
        $this->layout = false;
        if($this->request->is('ajax')) {
            $data = $this->Suggestion->find('first',array('conditions'=>array('Suggestion.id'=>$this->Encryption->decode($this->request->data['id']))));
            $this->set('suggestion',$data);
            $this->render('admin_view');
        }
    }
    
    /**
     * Delete Suggestion
     * @param string $feedbackId Feedback Id
     * @author Rohan
     */
    public function admin_delete($feedbackId = null)
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $this->set('id', $this->request->data['id']);
            $this->set('action', 'delete');
            $this->set('info', 'Video');
            $this->set('popupData',$this->parsePopupVars('delete','Suggestion'));
            $this->render('/Elements/activate_delete_popup', 'ajax');
        } else if ($this->request->is('post')) {
            if($feedbackId) {
                if($this->Suggestion->delete($this->Encryption->decode($feedbackId))) {
                    $this->Session->setFlash('Suggestion has been deleted successfully.','flash_good');
                } else {
                    $this->Session->setFlash('Error occured while deleting the Suggestion','flash_bad');
                }                
            } else {
                $this->Session->setFlash('Invalid Suggestion ID','flash_bad');
            }
            $this->redirect(array('controller'=>'suggestions','action'=>'index','admin'=>true));
        }
    }
    
    /**
     * add a Feedback
     * @author Priti Kabra
     */
    public function api_addSuggestion()
    {
        $error = 0;
        if (!isset($this->DeviceToken) || !isset($this->DeviceId) || !isset($this->DeviceType)) {
            $error = 1;
            $errMsg = "Please provide device type, device token.";
        }
        if ($error == 0) {
            if (!empty($this->jsonDecodedRequestedData->suggestion)) {
                $saveData['user_id'] = $this->loggedInUserId;
                $saveData['message'] = $this->jsonDecodedRequestedData->suggestion;
                $this->Suggestion->create();
                if($this->Suggestion->save($saveData)) {
                    $emailLib = new Email();
                    $to = AdminEmail;
                    $subject = 'FoxHopr - New Suggestion Received';
                    $template ='user_suggestion_posted';
                    $variable = array( );
                    $success = $emailLib->sendEmail($to, $subject, $variable, $template, 'both');
                    $this->set(array(
                        'code' => Configure::read('RESPONSE_SUCCESS'),
                        'message' => 'Thank you for providing your suggestion',
                        '_serialize' => array('code', 'message')
                    ));
                } else {
                    $this->errorMessageApi('Internal Error Occured');
                }
            } else {
                $this->errorMessageApi('Please enter suggestion');
            }
        } else {
            $this->errorMessageApi($errMsg);
        }
    }
}