<?php


App::uses('AuthComponent', 'Controller/Component');
class PrevGroupRecord extends AppModel 
{
	public $name = 'PrevGroupRecord';
    public $belongsTo = array(
            'BusinessOwner' => array(            
                        'foreignKey' => false,
                        'conditions'=>'PrevGroupRecord.members_id=BusinessOwner.user_id'
            ),
            'Profession' => array(
                    'foreignKey' => false,
                    'conditions'=>'Profession.id=BusinessOwner.profession_id'
            ),
            'State' => array (
                    'foreignKey' => false,
                    'conditions'=>'BusinessOwner.state_id=State.state_subdivision_id'
            ),
            'Country' => array (
                    'foreignKey' => false,
                    'conditions'=>'BusinessOwner.country_id=Country.country_iso_code_2'
            ),
            'User' => array(            
                        'foreignKey' => false,
                        'conditions' => array('PrevGroupRecord.members_id=User.id')
            )
    );
	
	/**
     * function to get the list of member from a group
     * @author Jitendra Sharma
     * @param int $groupId id of group
     * @param int $userId Login user id
     * @return array $memberList List of members 
     */
    function getMyPreviousGroupMemberList($userId=null){    	    	
    	if($userId!=NULL){
    		$userData = $this->find('all',array('conditions' => array('PrevGroupRecord.user_id' => $userId, 'User.deactivated_by_user' => 0, 'User.is_active' => 1),'group'=>'PrevGroupRecord.members_id','order' => 'BusinessOwner.lname ASC'));
            $prevMemberList = array();
    		$fields = array('BusinessOwner.fname','BusinessOwner.lname','BusinessOwner.user_id');
    		foreach ($userData as $user) {
                $prevMemberList['prev_'.$user['BusinessOwner']['user_id']] = $user['BusinessOwner']['lname'].' '.$user['BusinessOwner']['fname'];
    			//$list = explode(',',$user['PrevGroupRecord']['members_id']);
                //pr($list);die;
    			//foreach($list as $memberList) {	
    				//$user = ClassRegistry::init('User');			
    				//$userInfo = $user->userInfo($user['PrevGroupRecord']['members_id'],$fields);
    				//$prevMemberList[$user['BusinessOwners']['user_id']] = $user['BusinessOwners']['fname'].' '.$user['BusinessOwners']['lname'];
    			//}
    		}
    		return $prevMemberList;            
    	}
    }
    
    /**
      * Get Business Owner previous group list
      * @param int $userId user id of business owner
      * @return $previousGroupInfo array
      * @author Jitendra Sharma
      */
     public function getUserPreviousGroup($userId=null)
     {	
     	$previousGroupList = array();
     	$previousGroupInfo = $this->find('list', array('fields' => array('PrevGroupRecord.group_id'),'conditions' => array('PrevGroupRecord.user_id' => $userId), 'group' => 'PrevGroupRecord.group_id'));
     	foreach($previousGroupInfo as $group){
     		$previousGroupList[$group] = "Group ".$group;
     	}
     	return $previousGroupList;
     }
}
