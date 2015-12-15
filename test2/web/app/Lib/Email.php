<?php 
App::uses('CakeEmail', 'Network/Email');
class Email 
{
   public function sendEmail($to=NULL,$subject=NULL,$data=NULL,$template=NULL,$format='text',$cc=NULL,$bcc=NULL) {
    $Email = new CakeEmail();
    $Email->from(array(Configure::read('Email_From_Email') => Configure::read('Email_From_Name')));
    //$Email->config(Configure::read('TRANSPORT'));
    $Email->config('default');
    $Email->template($template);
    if($to!=NULL){
    	$Email->to($to);
    }
    if($cc!=NULL){
    	$Email->cc($cc);
    }
    if($bcc!=NULL){
    	$Email->bcc($bcc);
    }
    $Email->subject($subject);
    $Email->viewVars($data);
    $Email->emailFormat($format);
    if($Email->send()) {
    	return true;
    } else {
    	return false;
    }
   }
}
?>
