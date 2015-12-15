<?php

/**
 * This is a common component
 */
App::uses('Component', 'Controller');
class CommonComponent extends Component 
{
    /**
    * fetch list of all the countries
    * @return array of countries
    * @author Gaurav
    */
    public function getAllCountries()
    {
        $model = ClassRegistry::init('Country');
        return $model->find('list', array(
                'fields' => array('Country.country_iso_code_2', 'Country.country_name'),
                'order' => array('Country.country_name = "United States"' => 'desc','Country.country_name' => 'asc')));
    }
    
    /**
     * To get list of states for a country
     * @param int $countryId country Code
     * @return Array of States
     * @author Gaurav Bhandari
     */      
    public function getStatesForCountry($countryId)
    {
        $model = ClassRegistry::init('State');
        return $model->find('list', array(
                'conditions' => array('State.country_code_char2' => $countryId),
                'fields' => array('State.state_subdivision_id', 'State.state_subdivision_name'),
                'order' => array('State.state_subdivision_name' => 'asc')));
    }

    /**
     * getCountryStateCity() function is used to fetch single state OR city name
     * @param int $condition (state id OR city id)
     * @return Array of States OR City OR Country Data
     * @author Gaurav Bhandari
     */      
    public function getCountryStateCity($conditions)
    {
        $model = ClassRegistry::init('Country');
        return $model->find('first', array(
                'conditions' => array('Country.location_id' => $conditions),
                'fields' => array('Country.location_id', 'Country.name')));
    }
    
    /**
     * List Professions
     * @return Array of all Profession
     * @author Laxmi Saini
     */
     
    public function getAllProfessions()
    {
        $model = ClassRegistry::init('Profession');
        return $model->find('list', array(
                'fields' => array('Profession.id', 'Profession.profession_name')));
    }
    
        
    /**
     * Get Latitude and Longitude From zip code
     * @param int $zipcode group zipcode
     * @return array $result3
     * @author Gaurav Bhandari
     */
    public function getLatLong($zipcode)
    {
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($zipcode) . "&sensor=false&key=AIzaSyBPm51tXSwRnO8056xeBNY-ysZwKyGJuL0";
        $result_string = file_get_contents($url);
        $result = json_decode($result_string, true);
        $result1[] = $result['results'][0];
        $result2[] = $result1[0]['geometry'];
        $result3[] = $result2[0]['location'];
        return $result3[0];
    }

        
	/**
     * to get the counter of unread $entity
     * @param string $entity entity for which counter find
     * @param int $userId user id for which counter find
     * @return int $unreadCounter enitity unread counter
     * @author Jitendra Sharma
     */
    public function unreadCounter($entity=NULL,$userId=NULL)
    {
    	if($entity=='messages')
    		$model = ClassRegistry::init('MessageRecipient');
        if($entity=='referrals')
            $model = ClassRegistry::init('ReceivedReferral');
    	
    	if($userId!=NULL){	    	
	    	// get the count of unread message
    		$model->recursive = 0;
            if($entity == 'referrals') {
                $unreadCounter = $model->find('count',array('conditions' => array('ReceivedReferral.to_user_id'=>$userId,'ReceivedReferral.is_archive'=>0,'ReceivedReferral.is_read'=>0)));
            } else {
                $unreadCounter = $model->find('count',array('conditions' => array('MessageRecipient.recipient_user_id'=>$userId,'MessageRecipient.is_archive'=>0,'MessageRecipient.is_read'=>0)));
            }
	    	
        }
        return $unreadCounter;
    }
    /**
     * To remove the session files uploded by dropzone
     * @author Gaurav Bhandari
     */
    public function clearDropzoneData()
    {
        if(CakeSession::check('referralsFiles') == true && CakeSession::read('referralsFiles')!='') {
            $sessionName = 'referralsFiles';
            $folderName = 'referrals';
        } else if(CakeSession::check('messagesFiles') == true && CakeSession::read('messagesFiles')!=''){
            $sessionName = 'messagesFiles';
            $folderName = 'messages';
        }
        if(!empty($sessionName)) {
            $tempFiles = explode(',',CakeSession::read($sessionName));
                foreach($tempFiles as $temp) {
                    if(!empty($temp)) {
                        $filepath = WWW_ROOT . 'files/'.$folderName.'/temp/' . $temp;
                        if(file_exists($filepath))
                        {
                          unlink($filepath);
                      }
                  }                    
              }            
        CakeSession::delete($sessionName);
      }        
    }
	
	/**
     * PUSH NOTIFICATION FOR IOS DEVICE
     * @param type $regId is device_token
     * @param type $message is messge to be sent
     * @param type $pnType is type of service
     * @param type $requestedData id data to be sent
     * @author Priti Kabra
     */
    function iospushnotification ($regId, $message, $pnType, $requestedData) {
        $pempath = WWW_ROOT.'files/ck.pem';
        // Put your private key's passphrase here:
        $passphrase = 'pass1';
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $pempath);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
        // Open a connection to the APNS server
        $fp = stream_socket_client(
            'ssl://gateway.sandbox.push.apple.com:2195', $err,
            $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
        // Create the payload body
        $body['aps'] = array(
            'alert' => $message,
            'sound' => 'default'
        );
        $body['pn_type'] = $pnType;
        $body['requested_data'] = $requestedData;
        // Encode the payload as JSON
        $payload = json_encode($body);
        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $regId) . pack('n', strlen($payload)) . $payload;
        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));
        if (!$result) {
            return 0 ; //Message not delivered;
        } else {
            return 1 ; //Message successfully delivered
        }
        // Close the connection to the server
        fclose($fp);
    }

    /**
     * PUSH NOTIFICATION FOR ANDROID DEVICE
     * @param type $regId is device_token
     * @param type $message is messge to be sent
     * @param type $pnType is type of service
     * @param type $requestedData id data to be sent
     * @author Priti Kabra
     */
    function androidpushnotification ($regId, $message, $pnType, $requestedData) {
        $fields = array(
            'registration_ids' => array($regId),
            'data' => array("message" => $message, "pn_type" => $pnType, "requested_data" => $requestedData),
        );
        $headers = array(
            'Authorization: key=AIzaSyB3Y_nDVk1isAv94miFvThkMXLrZsQpmG4',
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        } else {
            return 1;
        }
        curl_close($ch);
    }

    /**
     * To get the the file name
     * @param string $fileName File Name
     * @author Priti Kabra
     */
    public function getFileName($fileName)
    {
        $fileUploadName = date('his').uniqid().str_replace(' ','_',$fileName);
        return $fileUploadName;
    }
     /**
    * fetch list of all the countries
    * @return array of countries
    * @author Gaurav
    */
    public function getCountries($name)
    {
        $model = ClassRegistry::init('Country');
        return $model->find('all', array(
                'conditions' => array('Country.country_name LIKE' => trim($name).'%'),
                'fields' => array('Country.country_iso_code_2', 'Country.country_name'),
                'order' => array('Country.country_name' => 'asc')));

    }

    /**
    * fetch list of all the countries
    * @return array of countries
    * @author Gaurav
    */
    public function getStates($country,$state)
    {
        $model = ClassRegistry::init('Country');
        $countryId = $model->find('first', array('conditions' => array('Country.country_name' => trim($country)),
                                                'fields' => array('Country.country_iso_code_2')));
        if(!empty($countryId)) {
            $cid = $countryId['Country']['country_iso_code_2'];
        } else {
            $cid = 'null';
        }
        $model = ClassRegistry::init('State');
        return $model->find('all', array(
                'conditions' => array('State.country_code_char2' => $cid,'State.state_subdivision_name LIKE' => ($state).'%'),
                'fields' => array('State.state_subdivision_id', 'State.state_subdivision_name'),
                'order' => array('State.state_subdivision_name' => 'asc')));
    }

    /**
     * Function to check twitter connected and post tweets
     * @return bool 
     * @author Rohan Julka
     * 
     */
    public function twitterCheckNPost($userData,$notification,$message)
    {
        if( isset($userData['BusinessOwners']['notifications_enabled']) ) {
            $twitterConfig = explode(',',$userData['BusinessOwners']['notifications_enabled']);
            if(!empty($twitterConfig) && in_array($notification,$twitterConfig)) {
                require_once (ROOT.DS.APP_DIR.DS.'Plugin/twitteroauth/twitteroauth.php');
                $consumer_key = Configure::read('twitter_consumer_key');
                $consumer_secret = Configure::read('twitter_consumer_secret');
                $oauth_token = $userData['User']['twitter_oauth_token'];
                $oauth_token_secret = $userData['User']['twitter_oauth_token_secret'];
                $connection = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token,$oauth_token_secret);
                $sent = $connection->post('statuses/update', array('status' => $message));
            }
        }
    }
    /**
     * Function to get Current account active date
     * @return datetime
     * @author Rohan Julka
     *
     */
    
    public function getCurrentActiveDate($user_id)
    {
        $userModel = ClassRegistry::init('User');
        $subscrip = ClassRegistry::init('Subscription');
        $conditions = array(
            'User.id' => $user_id,
            'User.reactivate' => 1,
        );
        $return=NULL;
        if ( $userModel->hasAny($conditions) ) {
            $date = $subscrip->find('first',array('conditions'=>array('Subscription.is_active'=>1,'Subscription.user_id'=>$user_id)));
            $return = $date['Subscription']['created'];
        } else {
           $userData = $userModel->findById($user_id);
           $return = $userData['User']['created'];           
        }
        return $return;
    }
}