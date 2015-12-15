<?php
App::uses ( 'AuthComponent', 'Controller/Component' );
App::uses ( 'EncryptionComponent', 'Controller/Component' );



/**
* BusinessOwner
*
* PHP version 5
*
* @category Model
* @version 1.0
* @author Jitendra Shrama
*        
*/
class BusinessOwner extends AppModel
{

    /**
    * Model name
    *
    * @var string
    * @access public
    *        
    */
    public $name = 'BusinessOwner';

    public $components = array('Encryption');


    /**
    * Model Virtual Field
    *
    * @var string
    * @access public
    *        
    */
    public $virtualFields = array (
        'member_name' => 'CONCAT(BusinessOwner.fname, " ", BusinessOwner.lname)',
        
    );

    /**
    * Order
    *
    * @var string
    * @access public
    *        
    */
    public $order = 'BusinessOwner.fname ASC';

    /**
    * Model validations
    *
    * @access Public
    *        
    */
    public $validate = array (        
    	'email' => array (
    		'required' => array (
    			'rule' => 'notEmpty',
    			'message' => 'This field is required'
    		),
    		'unique' => array (
	    		'rule' => 'isUnique',
	    		'message' => 'Email already exists.'
    		)			
		),
        'fname' => array(
            'required' => array (
    			'rule' => 'notEmpty',
    			'message' => 'This field is required'
    		),
        ),
        'lname' => array(
            'required' => array (
                'rule' => 'notEmpty',
                'message' => 'This field is required'
            ),
        ),
        'profession_id' => array(
            'rule' => 'notEmpty',
            'message' => 'This field is required',
        ),
        'country' => array(
            'rule' => 'notEmpty',
            'message' => 'This field is required',
        ),
        'country_id' => array(
            'rule' => 'notEmpty',
            'message' => 'This field is required',
        ),
        'timezone_id' => array(
            'rule' => 'notEmpty',
            'message' => 'This field is required',
        ),
        'state' => array(
            'rule' => 'notEmpty',
            'message' => 'This field is required',
        ),
        'zipcode' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'This field is required',
            ),
            'maxLength'=>array(
                'rule' => array('minLength', 3),
                'message' => 'ZIP code should be minimum 3 and maximum 12 characters.'
            ),
            'minLength'=>array(
                'rule' => array('maxLength', 12),
                'message' => 'ZIP code should be minimum 3 and maximum 12 characters.'
            )
        ),
        'confirm_email_address'=> array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'This field is required',
            ),
            'email'=>array(
                'rule' => 'email',
                'message' => 'Please enter valid email address.'
            ),
        ),
        'password'=> array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'This field is required',
            ),
            'whitespaces'=>array(
                'rule' => array('custom', '/^[a-z0-9 ]*$/i'),
                'message'  => 'Space is not allowed in password.'
            ),
            'minLength'=>array(
                'rule' => array('minLength', 6),
                'message' => 'Password should be minimum 6 characters and maximum 20 characters.'
            ),
            'maxLength'=>array(
                'rule' => array('maxLength', 20),
                'message' => 'Password should be minimum 6 characters and maximum 20 characters.'
            ),
            
        ),
        'cpassword'=>array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'This field is required',
            ),
            'equaltofield' => array(
                'rule' => array('equaltofield','password'),
                'message' => 'Require the same value to password.',
            ),
            'minLength'=>array(
                'rule' => array('minLength', 6),
                'message' => 'Password should be minimum 6 characters and maximum 20 characters.'
            ),
            'maxLength'=>array(
                'rule' => array('maxLength', 20),
                'message' => 'Password should be minimum 6 characters and maximum 20 characters.'
            ),
        ),
        'CC_Number' => array(
            'required' => array (
                'rule' => 'notEmpty',
                'message' => 'This field is required'
            )
        ),
        'CC_Number' => array(
            'required' => array (
                'rule' => 'notEmpty',
                'message' => 'This field is required'
            )
        ),
        'cvv' => array(
            'required' => array (
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'This field is required'
            )
        )
    );

    /**
    * Model associations: belongsTo
    *
    * @var array
    * @access public
    *        
    */
    public $belongsTo = array (
        'Country' => array (
            'foreignKey' => false,
            'conditions' => array('BusinessOwner.country_id = Country.country_iso_code_2')
        ),
        'State' => array (
            'foreignKey' => false,
            'conditions' => array('BusinessOwner.state_id = State.state_subdivision_id')
        ),
        'Group' => array (
            /*'counterCache' => 'total_member' */
        ),
        'Profession',
        'User'
    );

    /**
    * Model associations: hasMany
    *
    * @var array
    * @access public
    *        
    */
    // public $hasMany = array('Users');
     /**
     * function to get the list of member from a group
     * @author Jitendra Sharma
     * @param int $groupId id of group
     * @param int $userId Login user id
     * @return array $memberList List of members 
     */
    public function getMyGroupMemberList($groupId=null,$userId=null){
	   if($groupId!=NULL){
			$usersList = $this->find('all',array('fields'=>'User.id,BusinessOwner.fname,BusinessOwner.lname','conditions'=>array('BusinessOwner.group_id'=>$groupId,'BusinessOwner.user_id !='=>$userId,'User.is_active'=>1), 'order' => 'BusinessOwner.lname ASC'));
	        $memberList = array();
	        $collection = new ComponentCollection ();
	        $Encryption = new EncryptionComponent($collection);
	        foreach ($usersList as $user) {
	            $id = $Encryption->decode($user['User']['id']);
	            $name = $user['BusinessOwner']['lname'] . " " . $user['BusinessOwner']['fname'];
	            $memberList[$id] = $name;
	        }
	        return $memberList;
	    }
	}
    /**
     * function for custom validation
     * @author Rohan Julka
     * @param array $check  currentfield data
     * @param int $userId Login user id
     * @return array $memberList List of members
     */
    public function equaltofield($check,$otherfield)
    {
        //get name of field
        $fname = '';
        foreach ($check as $key => $value){
            $fname = $key;
            break;
        }
        return $this->data[$this->name][$otherfield] === $this->data[$this->name][$fname];
    }

    /**
     * get the members list of a group
     * @param int groupId
     * @author Priti Kabra
     */
    public function getGroupMembers($groupId = NULL)
    {
        $collection = new ComponentCollection ();
        $this->Encryption = new EncryptionComponent($collection);
        $groupMembers = $this->find('all', array('conditions' => array('BusinessOwner.group_id' => $this->Encryption->decode($groupId)), 'fields' => array('BusinessOwner.member_name', 'BusinessOwner.user_id', 'BusinessOwner.fname', 'BusinessOwner.group_role', 'BusinessOwner.email', 'Profession.profession_name','User.principal_id'), 'order' => 'BusinessOwner.member_name ASC'));
        return $groupMembers;
    }
}
