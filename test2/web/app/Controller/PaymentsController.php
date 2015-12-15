<?php

/**
 * this is Payments Controller
 * 
 */
class PaymentsController extends AppController 
{
    /**
     * Models used by the Controller
     * @var array
     * @access public
     */
    public $uses = array('BusinessOwner','Plan');
    
    /**
     * callback function to filter
     * @author Gaurav
     */
    public function beforeFilter()
    {
        $this->Auth->allow('index','process');
        require_once (ROOT.DS.APP_DIR.DS.'Plugin/authorizedotnet/AuthorizeNet.php');
    }
    /**
    * Payment form page
    * @author Gaurav
    */
    public function index()
    {
        $this->layout = false;
        if (!empty($this->Session->read('UserData.BusinessOwner.selectedPlan'))) {
            $planName = $this->Session->read('UserData.BusinessOwner.selectedPlan');
            $planDetail = $this->Plan->find('first', array(
                'conditions' => array('plan_name like ' => '%' . $planName . '%'),
                'callbacks' => false,
            ));
            $userPlanId = $planDetail['Plan']['id'];
            $memberShipPrice = $planDetail['Plan']['membership_price'];
            $discountedAmount = $planDetail['Plan']['discounted_amount'];
            $discountedMembers = $planDetail['Plan']['discounted_members'];
            $planHolders = $this->BusinessOwner->find('count', array(
                'conditions' => array('user_plan_id' => $userPlanId)
            ));
            if ($discountedMembers - $planHolders > 0) {
                $memberShipPrice = $memberShipPrice - $discountedAmount;
            }
            $this->request->data['BusinessOwner']['memberShipPrice'] = $memberShipPrice;
        } else {
            $this->Session->setFlash(__('Session time out, please select a plan'), 'flash_bad');
            return $this->redirect(array('controller' => 'users', 'action' => 'choosePlan'));
        }
        $this->request->data = $this->request->data;
    }
    
    /**
    * Payment process and create subscription
    * @author Gaurav
    */
    public function process()
    {
    	$this->layout = false;
    	/*$request = new AuthorizeNetTD;
		$transactionId = "2234120548";
		$response = $request->getTransactionDetails($transactionId);
		pr($response);
		exit;
		echo $response->xml->transaction->transactionStatus;

		exit;*/
    	$transaction = new AuthorizeNetAIM;
    	$transaction->setSandbox(AUTHORIZENET_SANDBOX);
    	$transaction->setFields(
    		array(
    			'amount' => $this->request->data['BusinessOwner']['memberShipPrice'], 
    			'card_num' => $this->request->data['BusinessOwner']['CC_Number'],
    			'exp_date' => $this->request->data['BusinessOwner']['expiration'],
    			'card_code' => $this->request->data['BusinessOwner']['cvv'],
    			)
    		);
    	$response = $transaction->authorizeAndCapture();
    	//pr($response);exit;
    	if (isset($response->declined) && $response->declined == "1") {
            $errMsg = $response->response_reason_text;
            $errMsg .= "Please try again later.";
            $this->Session->setFlash(__($errMsg), 'flash_bad');
            $this->redirect(array('controller' => 'users', 'action' => 'payment'));
        }
        if (isset($response->error) && $response->error == "1") {
            $errMsg = $response->response_reason_text;
            $errMsg .= "Please try again later.";
            $this->Session->setFlash(__($errMsg), 'flash_bad');
            $this->redirect(array('controller' => 'users', 'action' => 'payment'));
        }
        if (isset($response->approved) && $response->approved == "1") {
            /*             * ***********Create Subscription****************** */
            /* $subscription = new AuthorizeNet_Subscription;
              $subscription->name = 'Api Subscription';
              $subscription->intervalLength = "1";
              $subscription->intervalUnit = "months";
              $subscription->startDate = date('Y-m-d',time());
              $subscription->totalOccurrences = "999";
              $subscription->amount = '50';
              $subscription->creditCardCardNumber = $this->request->data['BusinessOwner']['CC_Number'];
              $subscription->creditCardExpirationDate = $this->request->data['BusinessOwner']['expiration'];
              $subscription->creditCardCardCode = $this->request->data['BusinessOwner']['cvv'];
              $subscription->billToFirstName = 'A3';
              $subscription->billToLastName = 'Logics';

              $request = new AuthorizeNetARB;
              $response = $request->createSubscription($subscription);
              $subscription_id = $response->getSubscriptionId(); */
            $errMsg = "Payment Successful";
            $this->Session->setFlash(__($errMsg), 'flash_good');
            /*             * ***********Create Subscripton******************* */

            $this->redirect(array('controller' => 'users', 'action' => 'payment'));
      
        }
    }
}