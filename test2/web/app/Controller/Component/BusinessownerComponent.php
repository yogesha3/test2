<?php
App::uses('Component', 'Controller');
class BusinessownerComponent extends Component 
{
    /**
    * Get User Name by id
    * @param $userid   
    * @return string $name user name
    * @author Jitendra Sharma
    */
    public function getBusinessOwnerNameById($userid=null)
    {
    	$model = ClassRegistry::init('BusinessOwner');
        $username = "";
        if($userid!=NULL){
        	$userData = $model->find('first',array('fields'=>'member_name','conditions'=>array('BusinessOwner.user_id'=>$userid)));
        	$username = $userData['BusinessOwner']['member_name'];
        }
        return $username;
    }
    
    /**
     * Get User profile picture
     * @param $userid
     * @param $type Either base image or resize image
     * @return string $profileImage location of profile picture
     * @author Jitendra Sharma
     */
    public function getProfilePicture($userid=null,$type="resize")
    {
    	$model = ClassRegistry::init('BusinessOwner'); 
    	$filename = "";
    	if($userid!=NULL){
    		$userData = $model->find('first',array('fields'=>'profile_image','conditions'=>array('BusinessOwner.user_id'=>$userid)));
    		$filename = $userData['BusinessOwner']['profile_image'];
    	}
    	$filepath = ($type=='resize') ? "uploads/profileimage/".$userid."/resize/" : "uploads/profileimage/".$userid."/";
    	$profileImage = (!empty($filename)) ? $filepath.$filename : "no_image.png";
    	return $profileImage;
    }
    
    /**
     * Get User profile compilation status in percentage
     * @param $userid
     * @author Jitendra Sharma
     */
    public function getProfileStatus($userid=null)
    {
    	$model = ClassRegistry::init('BusinessOwner');
    	$percentage = "0";
    	if($userid!=NULL){
    		$model->recursive = 0;
    		$userData = $model->findByUserId($userid,array('BusinessOwner.*','User.*'));
    	}
    	
    	$weightageValue = $this->getProfileWeightage();
	    foreach ($userData['BusinessOwner'] as $key => $val){
	    	if(isset($weightageValue[$key]) && !empty($val)){
	    		$percentage += $weightageValue[$key];
	    	}
	    }
	    if($userData['User']['twitter_connected']==1){
	    	$percentage += $weightageValue['twitter'];
	    }
	    return $percentage;
    }
    
    /**
     * Set user profile compilation status weightage
     * @author Jitendra Sharma
     */
    public function getProfileWeightage()
    {
    	$weightage = array(
    	    'fname' => '5',
    		'lname' => '5',
    		'email' => '10',
    		'company' => '5',
    		'profession_id' => '5',
    		'state_id' => '5',
    		'country_id' => '5',
    		'timezone_id' => '5',
    		'zipcode' => '5',
    		'profile_image' => '10',
    		'city' => '2',
    		'address' => '3',
    		'office_phone' => '3',
    		'mobile' => '3',
    		'website' => '3',
    		'website1' => '1',
    		'skype_id' => '3',
    		'twitter_profile_id' => '3',
    		'linkedin_profile_id' => '2',
    		'facebook_profile_id' => '3',
    		'business_description' => '3',
    		'aboutme' => '3',
    		'services' => '3',
    		'twitter' => '5'
    	);
    	return $weightage;
    }
    
    /**
     * Get random advetisement of selected profession 
     * @param $advPosition position of advertisement
     * @param $professionId profession id
     * @return $advData advertisement detail array
     * @author Jitendra Sharma
     */
     public function getAdvertisement($advPosition,$professionId=null){
     	$model = ClassRegistry::init('Advertisement');
     	$model->recursive = -1;
     	$advData = $model->find('first',array('conditions'=>array('Advertisement.profession_id'=>$professionId,'Advertisement.position'=>$advPosition), 'order'=>'rand()'));
     	return $advData;
     }
     
     /**
      * Get Business Owner Name and Email id via id
      * @param int $businessOwnerId business owner id
      * @return $userInfo array
      * @author Laxmi Saini
      */
     public function getBusinessOwnersNameAndEmailById($businessOwnerId=null)
     {
         $model = ClassRegistry::init('BusinessOwner');
         $userInfo = $model->find('first', array(
                 'fields' => array('BusinessOwner.fname, BusinessOwner.lname,BusinessOwner.email'),
                 'conditions' => array('BusinessOwner.user_id' => $businessOwnerId)));
         return $userInfo;
     }
}