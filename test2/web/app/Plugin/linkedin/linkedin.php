<?php

class LinkedInException extends Exception {
    private $lastResponse;
    public function __construct($message, $code=null, $lastResponse=null, $previous=null){
        parent::__construct($message, $code, $previous);
        $this->lastResponse = $lastResponse;
    }
    
    /**
     * Returns json decoded response object associated with this exception (Only applies to fetch calls)
     * @return type last HTTP response from linkedin or null if NA.
     */
    public function getLastResponse(){
        return $this->lastResponse; 
    }
}

class LinkedIn {
    private $API_KEY = '';
    private $API_SECRET = '';
    private $REDIRECT_URI = '';
    private $SCOPE = array('r_fullprofile','r_emailaddress');
    
    private $TOKEN_STORAGE;
    
    private $DISABLE_SSL_CHECK = true;
    private $DISABLE_REAUTH = false;
    /**
     * Create a new Linkedin intance.     
     * @param $apiKey
     * @param $apiSecret
     * @param $redirectUrl
     * @param $scope 
     * @param $tokenVar
     */
    public function __construct($apiKey,$apiSecret,$redirectUrl=null,$scope=null,&$tokenVar=null) {
        //Set api / secret.
        $this->API_KEY = $apiKey;
        $this->API_SECRET = $apiSecret;
        //Set redirect uri.
        if($redirectUrl){
            $this->REDIRECT_URI = $redirectUrl;
        } else {
            $this->REDIRECT_URI = (isset($_SERVER['HTTPS']) ? 'https://':'http://') . $_SERVER['SERVER_NAME']
                    . preg_replace('#\?.*#', '', $_SERVER['REQUEST_URI']);
        }
        //set scope.
        if($scope){
            if(is_array($scope)){
                $this->SCOPE = $scope;
            } else {
                $this->SCOPE = explode(' ',$scope);
            }
        }        
        if(!$tokenVar)
            $this->TOKEN_STORAGE = &$_SESSION['simple_linkedin'];
        else 
            $this->TOKEN_STORAGE = &$tokenVar;
    }
    /**
     * Add a scope. Use before authorize()
     * @param string $scope eg. 'rw_nus'
     */
    public function addScope($scope){
        /*
         * If we know that scope needs to be extended, we do so by re-aquiring a token.
        */
        if(isset($this->TOKEN_STORAGE['current_scope']) && !in_array($scope,$this->TOKEN_STORAGE['current_scope'])){
            //Reset if we need to re aquire scope.
            $this->resetToken();
        }
        $this->SCOPE[] = $scope;
    }
    /**
     * Function to reset the token value
     * 
     */
    public function resetToken(){
        $this->TOKEN_STORAGE['access_token'] = null;
    }
    
    /**
     * Function to Start the authorization process. Called after constructor
     * @return bool true if authorized, false if user declined. Throws Exception on error.
     */
    public function authorize(){
        // OAuth 2 Control Flow
        if (isset($_GET['error'])) {
            if($_GET['error'] == 'access_denied'){
                return false; //Report back that authorization was denied.
            }
            // LinkedIn returned an error
            throw new LinkedInException($_GET['error'] . ': ' . $_GET['error_description'],1);
        } elseif (isset($_GET['code'])) {
            // User authorized your application
            if ($this->TOKEN_STORAGE['state'] == $_GET['state']) {
                // Get token to make API calls
                $this->getAccessToken();
            } else {
                // Throw Exception
                throw new LinkedInException('State mismatch error.',2);
            }
        } else {
            if ((empty($this->TOKEN_STORAGE['expires_at'])) || (time() > $this->TOKEN_STORAGE['expires_at'])) {
                // Token has expired, clear the state
                $this->TOKEN_STORAGE = array();
            }
            if (empty($this->TOKEN_STORAGE['access_token'])) {
                // Start authorization process
                if(!$this->DISABLE_REAUTH){
                    $this->getAuthorizationCode();
                } else {
                    return false;
                }
            }
        }
        
        return true;
    }
    
    /**
     * Gets the OAuth2 authorization code. 
     * Redirects the user to authentication page.
     */
    private function getAuthorizationCode() {
        $params = array('response_type' => 'code',
                        'client_id' => $this->API_KEY,
                        'scope' => implode(' ',$this->SCOPE),
                        'state' => uniqid('', true), // unique long string
                        'redirect_uri' => $this->REDIRECT_URI,
                  );
        
        $this->TOKEN_STORAGE['current_scope'] = $this->SCOPE;
        
        // Linkedin Authentication request
        $url = 'https://www.linkedin.com/uas/oauth2/authorization?' . http_build_query($params);

        // Needed to identify request when it returns to us
        $this->TOKEN_STORAGE['state'] = $params['state'];
        
        // Redirect user to authenticate
        header("Location: $url");
        exit;
    }

    /**
     * Executes a HTTP Request with the CURL module.
     * @param type $method POST|GET|PUT|DELETE
     * @param type $url HTTP URI for the request
     * @param type $postData Any POST data for the request (RAW)
     * $param string $type set content type: JSON | XML
     * @return string raw response body. 
     */
    private function requestCURL($method,$url,$postData='',$type='json'){
        $ch = curl_init($url);
        
        if( $method != 'GET' ){
            // set correct HTTP Request type
            switch($method){
                case 'POST': curl_setopt($ch, CURLOPT_POST, 1);
                break;
                case 'PUT' : curl_setopt($ch, CURLOPT_PUT, 1);
                break;
                case 'DELETE' : curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            }
            
            //Add request data
            if($method=='POST' || $method=='PUT'){
                $contentTypes = array(
                    'json' => array('application/json','json'),
                    'xml' => array('application/xml','xml'),
                );

                $type = $contentTypes[$type];

                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "Content-type: application/$type[0]",
                    "x-li-format: $type[1]",
                    'Connection: close')
                );
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData );
            }            
        }
        
        //Useful for debugging, do not disable ssl in production!
        if($this->DISABLE_SSL_CHECK){
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        }        
        //Basic CURL settings
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
        curl_setopt($ch, CURLOPT_HEADER          ,0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);
        $result = curl_exec($ch);
        
        return $result;
    }
    
    /**
     * Gets OAuth2 accesstoken.
     * @return bool true
     */
    public function getAccessToken() {
        $params = array('grant_type' => 'authorization_code',
            'client_id' => $this->API_KEY,
            'client_secret' => $this->API_SECRET,
            'code' => $_GET['code'],
            'redirect_uri' => $this->REDIRECT_URI,
        );

        // Linkedin Access Token request
        $url = 'https://www.linkedin.com/uas/oauth2/accessToken?' . http_build_query($params);

        $response = $this->requestCURL('POST',$url);
        
        // Decode the json recieved in Response
        $token = json_decode($response);
        if(isset($token->access_token)){
            // Store access token and expiration time
            $this->TOKEN_STORAGE['access_token'] = $token->access_token;
            $this->TOKEN_STORAGE['expires_in']   = $token->expires_in;
            $this->TOKEN_STORAGE['expires_at']   = time() + $this->TOKEN_STORAGE['expires_in'];
        }
        return true;
    }
    
    /**
     * Use this function to make calls to the LinkedIn OAuth2 API.
     * See https://developer.linkedin.com/apis for availible calls.
     * @param type $method POST|GET|PUT|DELETE
     * @param type $resource Resource to make a call to. (eg. v1/people/~/connections)
     * @param type $body POST body data (Will be send as is if string is supplied, json_encoded if object or assoc array.)
     * @return type response object. Throws Exception on error.
     */
    public function fetch($method, $resource, $body = '',$format='json') {
        //Query parameters needed to make a basic OAuth transaction
        $params = array(
            'oauth2_access_token' => $this->TOKEN_STORAGE['access_token'],
            'format'              => $format,
        );
        $urlInfo = parse_url('https://api.linkedin.com'.$resource);
        if(isset($urlInfo['query'])){
            $query = parse_str($urlInfo['query']);
            $params = array_merge($params,$query);
        }
        
        //Build resource URI
        $url = 'https://api.linkedin.com' . $urlInfo['path'] . '?' . http_build_query($params, '', '&');
        
        //Some basic encoding to json if an object or array type is send as body
        if(!is_string($body)){
            if($format=='json'){
                $body = json_encode($body);
            }
            if($format=='xml')
                throw new LinkedInException('Please use a String in XML calls to LinkedIn::fetch()',3);
        }
        $response = $this->requestCURL($method,$url,$body,$format);        
        if($format=='json'){            
            // Decode the json recieved in Response
            $response = json_decode($response);
            if(isset($response->errorCode)){
                //Reset token if expired.
                if($response->status == 401) $this->resetToken ();
                
                throw new LinkedInException(
                    $response->message .
                      ' (Request ID: # '.$response->requestId.')', 
                    4+$response->errorCode ,
                    $response
                );
            }
        }
        return $response;
    }
    /**
     * Returns an assoc array containing the current access token data in a format similar to:
     * @return array [access_token, expires_in, expires_at, scope[]] 
     */
    public function getTokenData(){
        return $this->TOKEN_STORAGE;
    }
    /**
     * Sets the users token data (Token and expire time.)
     * @param type $accessToken Token string or assoc array with token info (see getTokenData)
     * @param type $expiresAt 
     * @param type @scope Scope is not requred, but usefull if you need to aquire more privileges as the reauthorization process will be done automatically. else be prepared for exceptions.
     * @param type @reAuth If running as cron, or you dont want this class to redirect the user to linkedin login on token expiration. 
     */
    public function setTokenData($accessToken, $expiresAt=null,$scope=null,$reAuth=true){
        if(is_array($accessToken)){
            if(!$accessToken['expires_at']){
                $accessToken['expires_at'] = time() + 240;
            }
            $accessToken['expires_in'] = $accessToken['expires_in'] - time();
            
            $this->TOKEN_STORAGE = $accessToken;
            return;
        }
        $this->TOKEN_STORAGE['access_token'] = $accessToken;
        if(!$expiresAt){
            $expiresAt = time() + 240;
        }
        $this->DISABLE_REAUTH = !$reAuth;
        $this->TOKEN_STORAGE['current_scope'] = is_string($scope) ? explode(' ',$scope) : $scope;
        $this->TOKEN_STORAGE['expires_in']   = $expiresAt - time(); // relative time (in seconds)
        $this->TOKEN_STORAGE['expires_at']   = $expiresAt; // absolute time
    }
    
}

?>