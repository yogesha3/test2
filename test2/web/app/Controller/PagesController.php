<?php
App::uses('AppController', 'Controller');
App::uses('Email', 'Lib');
class PagesController extends AppController 
{
    /**
     * callback function on filter
     * @author Gaurav Bhandari
     */
    function beforeFilter() 
    {
        parent::beforeFilter();
        $filterActions=array('aboutUs','contactUs','privacyPolicy','termsOfServices','faq');
        if(in_array($this->request->params['action'], $filterActions)) {
            if(count($this->request->params['pass'])>0) {
                $this->redirect(Router::url(array('controller'=>'pages','action'=>'404'),true));
            }
        }
    }
    
    /**
     * This controller use a model
     * @var array
     */
    public $uses = array('Page');

    /**
    * @author Gaurav Bhandari
    */
    public function home()
    {
        $titleForLayout = "FoxHopr : Home";
        $this->set(compact('titleForLayout'));
		$checkAccessSession = $this->Session->read('AccessedBy');
        if (!empty($checkAccessSession)) {
            $this->Session->delete('AccessedBy');
            $this->Session->delete('Auth.Front.id');
            $this->redirect('foxhoprapplication://cancel');
        }
    }

    /**
    * Function used view about us page 
    * @author Gaurav Bhandari
    */
    public function aboutUs() 
    {
        $titleForLayout = "FoxHopr : About Us";
        $this->set(compact('titleForLayout'));
        $aboutUsData = $this->Page->findByPageTitle('about-us');
        $this->set('aboutUs',$aboutUsData);
        $this->set("titleForLayout", $aboutUsData['Page']['meta_title']);
        $this->set("metaDescription", $aboutUsData['Page']['meta_desc']);
        $this->set("metaKeywords", $aboutUsData['Page']['meta_keywords']);
    }

    /**
    * Function used for contact us page 
    * @author Gaurav Bhandari
    */
    public function contactUs()
    {
        $titleForLayout = "FoxHopr : Contact";
        $this->set(compact('titleForLayout'));
        if ($this->request->is('post')) {
             $userData = $this->request->data['Page'];
             $emailLib = new Email();
             $to = 'saras@mailinator.com';
             $subject = 'User Feedback';
             $template = 'userFeedback';
             $variable = array('name' => 'Saraswati','data'=>$userData);
             $success = $emailLib->sendEmail($to,$subject,$variable,$template);
             if ($success) {
                $this->Session->setFlash(__('Your feedback has been submitted successfully'), 'flash_good');
                $this->redirect(array('controller' => 'pages', 'action' => 'contactUs'));
            } else {
                $this->Session->setFlash(__('Some error, please try again.'), 'flash_bad');
            }
        }
    }

    /**
    * Function used for Privacy Policy page 
    * @author Gaurav Bhandari
    */
    public function privacyPolicy() 
    {
        $titleForLayout = "FoxHopr : Privacy";
        $this->set(compact('titleForLayout'));
        $privacyPolicyData = $this->Page->findByPageTitle('privacy-policy');
        $this->set('privacyPolicy',$privacyPolicyData);	
        $this->set("titleForLayout", $privacyPolicyData['Page']['meta_title']);
        $this->set("metaDescription", $privacyPolicyData['Page']['meta_desc']);
        $this->set("metaKeywords", $privacyPolicyData['Page']['meta_keywords']);
    }

    /**
    * Function used for Terms of services page 
    * @author Gaurav Bhandari
    */
    public function termsOfServices() 
    {
        $titleForLayout = "FoxHopr : Terms Of Use";
        $this->set(compact('titleForLayout'));
        $termsOfServicesData = $this->Page->findByPageTitle('terms-and-conditions');
        $this->set('termsOfServices',$termsOfServicesData);	
        $this->set("metaDescription", $termsOfServicesData['Page']['meta_desc']);
        $this->set("metaKeywords", $termsOfServicesData['Page']['meta_keywords']);
    }

    /**
    * Function used for careers page 
    * @author Gaurav Bhandari
    */
    /*public function careers() 
    {

    }*/

    /**
    * Function used for Partner page 
    * @author Gaurav Bhandari
    */
    /*public function partners() 
    {

    }*/

    /**
    * Function used for Faq page
    * @author Gaurav Bhandari
    */
    public function faq() 
    {	
        $this->loadModel('Faqcategorie');
        $titleForLayout = "FoxHopr : FAQ";
        $this->Faqcategorie->bindModel(array('hasMany'=>array('Faq'=>array('className'=>'Faq','foreignKey'=>'category_id'))));
        $faqdata = $this->Faqcategorie->find('all');
        $this->set(compact('faqdata', 'titleForLayout'));
    }

    /**
    * Function used for View Faq Detail page 
    * @param string $faqTitle faq slug
    * @author Gaurav Bhandari
    */
    public function faqView($faqTitle = NULL) 
    {
        $titleForLayout = "FoxHopr : FAQ";
        $this->set(compact('titleForLayout'));
        $this->loadModel('Faq');
        $data = $this->Faq->findBySlug($faqTitle);
        if(!$data) {
                $this->redirect(array('action' => 'faq'));
        }
        $this->set('faqData',$data);		
    }

    /**
    * function to ajax search faq list
    * @param string $keywords search keywords
    * @author Gaurav Bhandari
    * @return json encoded $jsonValue
    */
    public function faqSearch($keyword = null)
    {
        $titleForLayout = "FoxHopr : FAQ";
        $this->set(compact('titleForLayout'));
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $count = 0;
            $this->loadModel('Faq');
            if (!empty($this->request->data['query'])) {
                $query = $this->request->data['query'];
                $data = $this->Faq->find('all', array('conditions' => array("Faq.question LIKE" => "%" . $query . "%")));
                if (!empty($data)) {
                    foreach ($data as $result) {
                        $allVal[$count]['encodeName'] = urlencode($result['Faq']['id']);
                        $allVal[$count]['faqName'] = urlencode($result['Faq']['slug']);
                        $allVal[$count]['name'] = $result['Faq']['question'];
                        $count++;
                    }
                    $jsonValue = array('data' => 'true', 'response' => $allVal);
                } else {
                    $jsonValue = array('data' => 'false', 'response' => '0');
                }
            } else {
                $jsonValue = array('data' => 'false', 'response' => NULL);
            }
            return json_encode($jsonValue);
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