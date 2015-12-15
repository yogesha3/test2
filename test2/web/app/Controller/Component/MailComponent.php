<?php
/** 
 * Mail Component
 * Component for sending email.
 * Developer - Ashish Agarwal
 * Date - 18-12-2014
 */

App::import('Component', 'Email');
class MailComponent extends EmailComponent	{
    function send($content = NULL, $template = NULL, $layout = NULL) {
        $this->smtpOptions = array();
        if($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '10.10.11.38'){
            $this->smtpOptions = array(
                'port'=>'465',
                'timeout'=>'30',
                'host' => 'ssl://smtp.gmail.com',
                'username'=>'shivana369@gmail.com',
                'password'=>'shivani$369?',
            );
            $this->delivery = 'smtp';
        }
        $this->to = $this->to['email'];
        $this->from = $this->from['name'] . ' <' .$this->from['email'] . '>';
        if(parent::send()) {
            return true;
        }
        return false;
    }
}