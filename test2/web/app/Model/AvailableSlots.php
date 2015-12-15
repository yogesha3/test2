<?php


/**
 * Adobe Connect Meeting Model
 *
 * PHP version 5
 */

App::import('Component','Adobeconnect');
class AvailableSlots extends AppModel 
{
    public function getSlotData()
    {
    	$Adobeconnect = new AdobeconnectComponent(new ComponentCollection);
    	$todayDate =  date('Y-m-d');
    	$slotArr = $this->find('all',array('conditions' => array('AvailableSlots.date' => $todayDate,'is_active' => 0)));
    	$count = 0;
    	foreach($slotArr as $slot) {
    		$slotArr[$count]['AvailableSlots']['slot_time'] = $Adobeconnect->getSlotTimes($slot['AvailableSlots']['slot_id']);
    		$count++;
    	}
    	return $slotArr;
    }

    public function getSlotDataByGroup($groupId)
    {
    	$Adobeconnect = new AdobeconnectComponent(new ComponentCollection);
    	$slotArr = $this->find('first',array('conditions' => array('group_id' => $groupId)));
    	$slotArr['AvailableSlots']['slot_time'] = $Adobeconnect->getSlotTimes($slotArr['AvailableSlots']['slot_id']);
    	return $slotArr;
    }
}