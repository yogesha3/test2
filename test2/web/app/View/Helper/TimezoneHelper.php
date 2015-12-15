<?php

/**
 * to use  ready to use TimezoneComponent functions in view files  
 */

App::uses('Timezone', 'Controller/Component');
class TimezoneHelper extends AppHelper 
{
    
     /**
     * used for GMT offset for a timezone
     * @param (string) $timezone: timezone name
     * @return (string) GMT offset for the given timezone
     * @added by Laxmi Saini
     */
    public function getTimezoneOffset($string)
    {
        $collection = new ComponentCollection();
        $timezoneObj= new TimezoneComponent($collection);
        return $timezoneObj->getTimezoneOffset($string);
    }
}