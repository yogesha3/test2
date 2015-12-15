<?php
/**
 * This is a Adobe Connect controller
 */
App::uses('Email', 'Lib');
class MeetingsController extends AppController 
{
    public $uses = array('User','AvailableSlots');
    public $components=array('Common','Timezone','Paginator','Cookie','Encryption','Adobeconnect');

	public function beforeFilter() 
    {
        parent::beforeFilter();
    }
	
	public function index()
	{
		$sessionUrl = $this->Session->read('BackUrlAfterLogin');
        if (!empty($sessionUrl)) {
            $this->Session->delete('BackUrlAfterLogin');
        }
		$breezSessionData = '';
		$userData = $this->User->find('first',array('conditions'=>array(
														'User.id' => $this->Encryption->decode($this->Session->read('Auth.Front.id'))),
														'fields'=>array('password','user_email')));
        $adobeMail = $userData['User']['user_email'];
        $adobePass = substr($userData['User']['password'],0,20);
		$slotData =  $this->AvailableSlots->getSlotDataByGroup($this->Encryption->decode($this->Session->read('Auth.Front.Groups.id')));
		$this->set(compact('userData','slotData'));
		$timeArr = explode(' ', $slotData['AvailableSlots']['slot_time']);
		$currentDate =  date('Y-m-d');
		$startTime = strtotime($timeArr[0].' '.$timeArr[1]);
		$endTime = strtotime($timeArr[3].' '.$timeArr[4]);
		$currentTime = strtotime(date('h:i A'));
		$currentDate =  date('Y-m-d');
		if($currentDate == $slotData['AvailableSlots']['date']){
			if($currentTime > $startTime && $currentTime < $endTime) {
				$breezSessionData = $this->Adobeconnect->adobeConnectLogin($adobeMail,$adobePass);
				$breezsession = explode('=',$breezSessionData);
				$breezsessionValue =  $breezsession[1];
			}
		}
		
		$this->set(compact('slotData','breezsessionValue'));
	}
}