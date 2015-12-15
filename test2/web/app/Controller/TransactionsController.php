<?php

/**
 * This is a Transaction controller to save all the recurring data
 *
 */
class TransactionsController extends AppController 
{
    /**
     * Models used by the Controller
     *
     * @var array
     * @access public
     */
    public $paginate = array(
        'order' => array('Transaction.created' => 'desc')
    );
    public $uses = array('Subscription', 'Transaction', 'User', 'BusinessOwner');
    public $components = array('Common','Csv.Csv');
    
    public function beforeFilter() 
    {
        parent::beforeFilter();
    }

    /**
    * function recurringTransaction
    * to save the recurring data in log file
    * @author Priti Kabra
    */
    public function recurringTransaction() 
    {
    	$this->autoRender = false;
        $this->autoLayout = false;
    	if (isset($_POST) && !empty($_POST)) {
            $output = $_REQUEST;
    		$date = array();
    		$date['x_response_date'] = date("F j, Y, g:i a");
    		$output = $date+$output;
    		$file = 'files/log.txt';
    		$current = file_get_contents($file);
    		$current .= print_r($output, TRUE);
    		file_put_contents($file, $current);
    		$userData = $this->Subscription->find('first', array('conditions' => array('Subscription.subscription_id' => $output['x_subscription_id']), 'fields' => array('Subscription.user_id', 'Subscription.id', 'Subscription.created')));
    		if (!empty($userData)) {
    			$month = date_create($userData['Subscription']['created']);
    			$monthChange = date_format($month, 'm') + 1;
    			$saveData['id'] = $this->Encryption->decode($userData['Subscription']['id']);
    			$saveTransaction['user_id'] = $saveData['user_id'] = $userData['Subscription']['user_id'];
    			$userInfo = $this->User->userInfoById($saveTransaction['user_id']);
    			if(!empty($userData) && ($userInfo['Groups']['group_type']!=NULL || $userInfo['Groups']['group_type']!='') ) {
    				$saveTransaction['group_type'] = $userInfo['Groups']['group_type'];
    			}
    			$saveData['subscription_id'] = $output['x_subscription_id'];
    			$saveTransaction['transaction_id'] = $output['x_trans_id'];
				//$saveTransaction['credit_card_number'] = $userInfo['BusinessOwners']['credit_card_number'];
				$saveTransaction['amount_paid'] = $output['x_amount'];
    			$saveTransaction['status'] = 'settled';
    			$saveTransaction['transaction_type'] = 'subscription';
    			$saveTransaction['purchase_date'] = $this->Common->getCurrentActiveDate($userData['Subscription']['user_id']);
    			if ($monthChange <= 12) {
    				$saveData['next_subscription_date'] = date_format($month, 'Y-'.$monthChange.'-d');
    			} else {
    				$monthChange = 1;
    				$year = date_format($month, 'Y') + 1;
    				$saveData['next_subscription_date'] = date_format($month, $year.'-'.$monthChange.'-d');
    			}
    			$this->Transaction->create();
    			$this->Transaction->save($saveTransaction);
    			$this->Subscription->id = $saveData['id'];
    			$this->Subscription->save($saveData);
    		}
    		echo 'Data written to Log.txt file';
    	} else {
    		echo 'invalid method';
    	}
    }

    /**
    * Function is used to view transaction listing in admin panel
    * @author Gaurav Bhandari
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
        $condition = array();
        if ($this->Session->read('sort') != '') {
            $this->paginate['Transaction']['order'] = array($this->Session->read('sort') => $this->Session->read('direction'));
        } else {
            $this->paginate['Transaction']['order'] = array('Transaction.created' => 'desc');
        }
        $this->paginate['Transaction']['limit'] = $perpage;

        if (!empty($this->params['named']['start'])) {
            $startDate = date('Y-m-d' , strtotime(str_replace('-', '/', $this->params['named']['start'])));
        } else if (!empty($this->request->data['Transaction']['start'])) {
           $startDate = date('Y-m-d' , strtotime(str_replace('-', '/', $this->request->data['Transaction']['start'])));
        } else {
            $startDate = '';
        }

        if (!empty($this->params['named']['end'])) {
            $endDate = date('Y-m-d' , strtotime(str_replace('-', '/', $this->params['named']['end'])));
        } else if (!empty($this->request->data['Transaction']['end'])) {
           $endDate = date('Y-m-d' , strtotime(str_replace('-', '/', $this->request->data['Transaction']['end'])));
        } else {
            $endDate = '';
        }       

        if ($startDate != '' || $endDate != '') {
        	if(!empty($startDate) && !empty($endDate)) {
        		$condition["DATE(Transaction.purchase_date) >="] = $startDate;
        		$condition["DATE(Transaction.purchase_date) <="] = $endDate;
        	} else if(!empty($startDate)) {
        		$condition["DATE(Transaction.purchase_date) >= "] = $startDate;
        	} else if(!empty($endDate)){
        		$condition["DATE(Transaction.purchase_date) <= "] = $endDate;
        	}
        }
        if ($search != '') {
            $nameSearch="concat(BusinessOwner.fname,' ',BusinessOwner.lname) LIKE ";
            $condition['OR']=array($nameSearch=>'%' . $search . '%');
        }
        if (!empty($this->params['named']['plan'])) {
            $plan = $this->params['named']['plan'];
        } else if (!empty($this->request->data['Transaction']['plan'])) {
           $plan = $this->request->data['Transaction']['plan'];
        } else {
            $plan = '';
        }

        if ($plan != '') {
    		$condition["Transaction.group_type"] =$plan;
        }
        $this->paginate['Transaction']['conditions'] = $condition;
        $this->set('transaction', $this->paginate('Transaction'));
        $this->set('perpage', $perpage);
        $this->set('search', $search);
        $this->set('startDate', $startDate);
        $this->set('endDate', $endDate);
        $this->set('plan', $plan);
        if ($this->request->is('ajax')) {
            $this->layout = false;
            $this->set('perpage', $perpage);
            $this->set('search', $search);
            $this->render('admin_ajax_list'); // View, Layout
        }
        $this->set('titleForLayout', 'Transaction Code');
    }

/**
    * Function is used to view transaction detail of a transaction
    * @author Priti Kabra
    */
    public function admin_transactionDetail($transactionId = null)
    {
    	$this->layout = 'admin';
        if (!$this->Transaction->exists($this->Encryption->decode($transactionId))) {
            $this->Session->setFlash('Transaction does not exist', 'flash_bad');
            $this->redirect(array('controller' => 'transactions', 'action' => 'transactionDetail', 'admin' => true));
        }
        $transactionData = $this->Transaction->find('first', array(
                                                        'conditions' => array(
                                                            'Transaction.id' => $this->Encryption->decode($transactionId)
                                                            )
                                                        )
                                                    );
        $this->set(compact('transactionData'));
    }

    /**
    * Action for export list of Transactions list
    * @author Gaurav Bhandari
    */
    function admin_exportTransaction()
    {
    	$this->layout = "ajax";
    	$this->autoRender = false;    	
    	$filepath = WWW_ROOT . 'files' . DS . 'Transaction_exported_' . date('d-m-Y-H:i:s') . '.xls';
    	// fields to be show in exported csv
    	$fields = array(
    				'Transaction.transaction_id',
    				'Transaction.purchase_date',
    				'Transaction.status',
    				'Transaction.amount_paid',
    				'Transaction.group_type',
    				'Subscription.next_subscription_date',
    				'BusinessOwner.fname',
    				'BusinessOwner.lname',
    				);
    	
    	// condition array
    	//$userId = $this->Encryption->decode($this->Session->read('Auth.Front.id'));
    	//$condition = array('ReceivedReferral.to_user_id' => $userId , 'ReceivedReferral.is_archive' => 0 );
    	
    	// fetch result array
    	$data = $this->Transaction->find('all', array('fields' => $fields, 'order'=>'Transaction.created DESC'));
    	
    	if (count($data) > 0) {
    		$data = $this->formatCsvData($data);    		
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
    		$this->Session->setFlash(__('No Transactions(s) to download.'), 'Front/flash_bad');
    		$this->redirect(array('controller' => 'transactions', 'action' => 'index' ,'admin' =>true));
    	}
    }

    /**
     * Action for format xls data
     * @author Gaurav 
     */
    public function formatCsvData($data=array()){
    	foreach ($data as $key => $model){
    		$formatData[$key]['Transaction']['Id'] 		= $model['Transaction']['transaction_id']; 
    		$formatData[$key]['Transaction']['Date'] 		= $model['Transaction']['purchase_date'];  
    		$formatData[$key]['Transaction']['Recurring On'] 		= date('m-d-Y',strtotime($model['Subscription']['next_subscription_date'])); 
    		$formatData[$key]['Transaction']['Billed To'] 		= $model['BusinessOwner']['fname'] .' '. $model['BusinessOwner']['lname']; 		
    		$formatData[$key]['Transaction']['Status'] 		= ($model['Transaction']['status'] == 'settled') ? 'Success' : 'Failed';
    		$formatData[$key]['Transaction']['Plan'] 		= ucfirst($model['Transaction']['group_type']);
    		$formatData[$key]['Transaction']['Amount'] 		= '$'.$model['Transaction']['amount_paid'];
    	}
    	return $formatData;
    }
}