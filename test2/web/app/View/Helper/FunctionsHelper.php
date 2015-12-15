<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
App::uses('Functions', 'Controller/Component');
class FunctionsHelper extends AppHelper 
{
    
    public function encrypt($string)
    {
        $collection = new ComponentCollection();
        $obj=new FunctionsComponent($collection);
        return $obj->encrypt($string);
    }
    
    public function formatURL($url=null) 
    {
        $return = $url;
        if ((!(substr($url, 0, 7) == 'http://')) && (!(substr($url, 0, 8) == 'https://'))) { $return = 'http://' . $url; }
        return $return;
    }

    public function dateTime($string)
    {
        return date("M d, Y @ h:i A", strtotime($string));
    }
}