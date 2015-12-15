<?php 
class AdobeconnectHelper extends AppHelper
{
	public function getSlotTimes($slots)
    {
    	$slotTimes = array(
            't1' => '00:00 AM - 01:30 AM',
            't2' => '00:30 AM - 02:00 AM',
            't3' => '01:00 AM - 02:30 AM',
            't4' => '01:30 AM - 03:00 AM',
            't5' => '02:00 AM - 03:30 AM',
            't6' => '02:30 AM - 04:00 AM',
            't7' => '03:00 AM - 04:30 AM',
            't8' => '03:30 AM - 05:00 AM',
            't9' => '04:00 AM - 05:30 AM',
            't10' => '04:30 AM - 06:00 AM',
            't11' => '05:00 AM - 06:30 AM',
            't12' => '05:30 AM - 07:00 AM',
            't13' => '06:00 AM - 07:30 AM',
            't14' => '06:30 AM - 08:00 AM',
            't15' => '07:00 AM - 08:30 AM',
            't16' => '07:30 AM - 09:00 AM',
            't17' => '08:00 AM - 09:30 AM',
            't18' => '08:30 AM - 10:00 AM',
            't19' => '09:00 AM - 10:30 AM',
            't20' => '09:30 AM - 11:00 AM',
            't21' => '10:00 AM - 11:30 AM',
            't22' => '10:30 AM - 12:00 AM',
            't23' => '11:00 AM - 12:30 PM',
            't24' => '11:30 AM - 01:00 PM',
            't25' => '12:00 AM - 01:30 PM',
            't26' => '12:30 PM - 02:00 PM',
            't27' => '01:00 PM - 02:30 PM',
            't28' => '01:30 PM - 03:00 PM',
            't29' => '02:00 PM - 03:30 PM',
            't30' => '02:30 PM - 04:00 PM',
            't31' => '03:00 PM - 04:30 PM',
            't32' => '03:30 PM - 05:00 PM',
            't33' => '04:00 PM - 05:30 PM',
            't34' => '04:30 PM - 06:00 PM',
            't35' => '05:00 PM - 06:30 PM',
            't36' => '05:30 PM - 07:00 PM',
            't37' => '06:00 PM - 07:30 PM',
            't38' => '06:30 PM - 08:00 PM',
            't39' => '07:00 PM - 08:30 PM',
            't40' => '07:30 PM - 09:00 PM',
            't41' => '08:00 PM - 09:30 PM',
            't42' => '08:30 PM - 10:00 PM',
            't43' => '09:00 PM - 10:30 PM',
            't44' => '09:30 PM - 11:00 PM',
            't45' => '10:00 PM - 11:30 PM',
            't46' => '10:30 PM - 12:00 PM',
            't47' => '11:00 PM - 12:30 PM',
            't48' => '11:30 PM - 01:00 AM',
            );
		return $slotTimes[$slots];
    }
    
    public function getFirstMeetingTime($slotId)
    {
    	$value = $this->getSlotTimes($slotId);
    	$startTime = explode(' ',$value);
    	return $startTime[0].' '. $startTime[1];
    }
}
?>