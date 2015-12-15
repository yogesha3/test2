<?php
/**
 * This is a Adobe Connect controller
 */
App::uses('Email', 'Lib');
class AdobeConnectController extends AppController 
{
    public $uses = array('AdobeConnectMeeting');
	public function admin_index()
	{
		$this->includePageJs = array('admin_validation');
		$count = $this->AdobeConnectMeeting->find('count');
		$this->set('count',$count);
		if($this->request->is('post')){
			$postCount = $this->request->data['adobeConnect']['hostedCount'];
			if($count < $postCount){
				$remainingAccount = $postCount - $count;
				for($i=1; $i<=$remainingAccount; $i++){
					$checkRowCount = $this->AdobeConnectMeeting->find('count') + 1;
    				$this->request->data['AdobeConnectMeeting']['nmh'] = $checkRowCount;
    				$this->AdobeConnectMeeting->create();
    				$this->AdobeConnectMeeting->save($this->request->data);
    				$lastInsertId = $this->AdobeConnectMeeting->id;
					$rowCount = $checkRowCount % 4;
					switch ($rowCount) {
		    			case 1:
		    				$slots = explode(',',Configure::read('SLOT_POSITION_FIRST'));		    				
		    			break;
		    			case 2:
		    				$slots = explode(',',Configure::read('SLOT_POSITION_SECOND'));
		    			break;
		    			case 3:
		    				$slots = explode(',',Configure::read('SLOT_POSITION_THIRD'));
		    			break;
		    			case 0:
		    				$slots = explode(',',Configure::read('SLOT_POSITION_FOURTH'));
		    			break;
		    		}
		    		$this->AdobeConnectMeeting->id = $lastInsertId;
    				foreach($slots as $positions) {
    					$this->AdobeConnectMeeting->id = $lastInsertId;
    					$this->AdobeConnectMeeting->saveField($positions, 1);
    				}
				}
				$this->Session->setFlash(__('NMH Accounts has been updated successfully'), 'flash_good');
                $this->redirect(array('action' => 'index', 'admin' => true));
			} else {
				$this->Session->setFlash(__('NMH count cannot be less than or equal to '.$count), 'flash_bad');
                $this->redirect(array('action' => 'index', 'admin' => true));
			}
		}
		$this->set('includePageJs', $this->includePageJs);
	}
}