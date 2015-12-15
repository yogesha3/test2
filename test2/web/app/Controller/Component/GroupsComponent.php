<?php
App::uses('Component', 'Controller');
class GroupsComponent extends Component 
{
    /**
    * Get Latitude and Longitude From zip code
    * @param string $lat group latitude
    * @param string $long group longitude
    * @param int $miles miles
    * @return array $result groups based on miles as radius
    * @author Gaurav Bhandari
    */
    public $components = array('Encryption','Session');
    
    public function getGroupByMiles($lat,$long,$miles)
    {
        $model = ClassRegistry::init('Group');
        $result = $model->find('all', array(
            'fields' => array('* , ( 3959 * ACOS( COS( RADIANS(  ' . $lat . ' ) ) * COS( RADIANS(  `lat` ) ) * COS( RADIANS(  `long` ) - RADIANS(  ' . $long . ' ) ) + SIN( RADIANS(  ' . $lat . ' ) ) * SIN( RADIANS(  `lat` ) ) ) ) AS distance'),
            'group' => 'distance HAVING distance <=' . $miles,
        ));
        return $result;
    }
    
    /**
     * Get Group Leader Name via id
     * @param int $leaderId (Group Leader id)
     * @return Group Leader name
     * @author Jitendra
     */
    public function getGroupLeaderNameById($leaderId=null)
    {
        $model = ClassRegistry::init('BusinessOwner');
        $leader = "";
        if ($leaderId) {
            $leader = $model->find('first', array('fields' => array('BusinessOwner.fname,BusinessOwner.lname'), 'conditions' => array('BusinessOwner.user_id' => $leaderId)));
            return $leader['BusinessOwner']['fname'].' '.$leader['BusinessOwner']['lname'];
        } else {
            return $leader;
        }
    }
    
    /**
     * to check whether the profession is occupied in the group or not
     * @param int $groupId
     * @param int $professionId
     * @return boolean
     * @author Laxmi Saini
     */
    public function isProfessionOccupiedInGroup($groupId=NULL, $professionId=NULL)
    {
        $model = ClassRegistry::init('BusinessOwner');
        $holdersCount = $model->find('count', array(
                'conditions' => array('BusinessOwner.group_id' => $groupId,
                        'BusinessOwner.profession_id' => $professionId, 'Group.total_member <' => 20)
        ));
        if ($holdersCount != 0) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * to check whether the profession is occupied in the group or not
     * @param int $groupId group id
     * @return boolean
     * @author Laxmi Saini
     */
    public function isGroupFull($groupId=NULL)
    {
        $model = ClassRegistry::init('Group');
        $holdersCount = $model->findById($groupId);
        if (!empty($holdersCount) && $holdersCount['Group']['total_member'] >= 20) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * To get the Group Id of the user
     * @param integer $userId User id
     * @author Priti Kabra
     */
    public function getUserGroupId($userId)
    {
        $model = ClassRegistry::init('BusinessOwner');
        return $model->find('first', array(
                'conditions' => array('BusinessOwner.user_id' => $userId),
                'fields' => array('BusinessOwner.group_id')));
    }
    /**
     * To update User group Info
     * @param integer $userId User id
     * @author Rohan Julka
     */
    public function updateGroupInfo($previousGroupId = NULL , $groupId = NULL , $userId = NULL)
    {
        $prevRecordModel = ClassRegistry::init('PrevGroupRecord');
        $businessOwnerModel = ClassRegistry::init('BusinessOwner');
        $groupModel = ClassRegistry::init('Group');
        $userModel = ClassRegistry::init('User');
        $liveFeedModel = ClassRegistry::init('LiveFeed');
        $groupInfo = $groupModel->findById($groupId);
        $userData = $userModel->userInfoById($userId);
        //Assign New Group
        if($groupInfo['Group']['total_member'] == 0) {
            $group_role = 2;
            $column = 'Group.group_leader_id';
            $group_leader_id = $userId;
        } else if($groupInfo['Group']['total_member'] == 1) {
            $group_role = 3;
            $column = 'Group.group_coleader_id';
            $group_leader_id = $userId;
        } else {
            $group_role = 4;
            $group_leader_id = $groupInfo['Group']['group_coleader_id'];
            $column = 'Group.group_coleader_id';
            $group_leader_id = $groupInfo['Group']['group_coleader_id'];
        }
        
        /*$data['group_id'] = $groupId;
        $data['group_role'] = $group_role;
        $data['is_kicked'] = 0;*/
        
        if($groupInfo['Group']['group_professions'] != '') {
            $groupProfession = $groupInfo['Group']['group_professions'] . ',' . $userData['BusinessOwners']['profession_id'];
        } else {
            $groupProfession = $userData['BusinessOwners']['profession_id'];
        }
        // Update the data in previous group
        $emailInfo = $this->updatePreviousGroupInfo($previousGroupId,$userId);
        // Update data in the destination group
        $groupModel->updateAll(
                array('Group.total_member' => 'total_member + 1','Group.group_professions' =>  "'". $groupProfession."'",$column =>  "'". $group_leader_id."'"),
                array( 'Group.id' => $this->Encryption->decode($groupInfo['Group']['id'])));
        
        /*$businessOwnerModel->id = $this->Encryption->decode($userData['BusinessOwners']['id']);
        $businessOwnerModel->save($data);*/
        $businessOwnerModel->updateAll(
                array('BusinessOwner.group_id' => $groupId,'BusinessOwner.is_kicked' => '0','BusinessOwner.group_role'=>$group_role),
                array('BusinessOwner.user_id' => $userId)
        );
        // sent message live feed
        $groupMembers = $businessOwnerModel->getMyGroupMemberList($groupId,$userId);
        foreach ($groupMembers as $groupMemberId => $groupMemberList){
            $liveFeedModel->create();
            $liveFeedData['LiveFeed']['to_user_id']     = $groupMemberId;
            $liveFeedData['LiveFeed']['from_user_id']   = $userId;
            $liveFeedData['LiveFeed']['feed_type']      = "newmember";
            $liveFeedModel->save($liveFeedData);
        }
        return $emailInfo;
    }
    /**
     * To save Previous User group Info
     * @param int previousGroupId, int userId
     * @author Rohan Julka
     */
    public function savePrevGroupData($previousGroupId,$userId)
    {
        //Store previous group members information
        $buisnessOwnerModel = ClassRegistry::init('BusinessOwner');
        $prevGroupModel = ClassRegistry::init('PrevGroupRecord');
        $gdata = $buisnessOwnerModel->getMyGroupMemberList($previousGroupId,$userId);
        $prevMember = NULL;
        $prevRecord['PrevGroupRecord'] = array();
        foreach($gdata as $key => $val) {
            $data['user_id'] = $userId;
            $data['group_id'] = $previousGroupId;
            $data['members_id'] = $key;
            array_push($prevRecord['PrevGroupRecord'],$data);
        }
        $prevGroupModel->saveAll($prevRecord['PrevGroupRecord']);
    }
    /**
     * To update previous gruop info
     * @param int groupId, int userId
     * @author Rohan Julka
     */
    public function updatePreviousGroupInfo($groupId, $userId)
    {
        //Update Previous Information
        $buisnessOwnerModel = ClassRegistry::init('BusinessOwner');
        $groupModel = ClassRegistry::init('Group');
        $userModel = ClassRegistry::init('User');
        $groupInfo = $groupModel->findById($groupId);
        $userData = $userModel->userInfoById($userId);
        $group_leader_id = NULL;
        $userGroups = $buisnessOwnerModel->find('all',array(
                'fields' => array('BusinessOwner.user_id,BusinessOwner.group_id,BusinessOwner.email,BusinessOwner.fname,BusinessOwner.lname,BusinessOwner.group_role'),
                'conditions'=>array('BusinessOwner.group_id' => $this->Encryption->decode($userData['Groups']['id']))));
        //pr($userGroups);die;
        $emailData = array();
        if($userData['BusinessOwners']['group_role'] == 'participant') {
            $parts = explode(',', $userData['Groups']['group_professions']);
            while(($i = array_search($userData['BusinessOwners']['profession_id'], $parts)) !== false) {
                unset($parts[$i]);
            }
            $updateProfessions = implode(',', $parts);
            $updateMember = $userData['Groups']['total_member'] - 1;
            $buisnessOwnerModel->updateAll(array('Group.group_professions' => "'".$updateProfessions."'",'Group.total_member' =>"'".$updateMember."'"),array( 'Group.id' => $this->Encryption->decode($userData['Groups']['id'])));
        } else if($userData['BusinessOwners']['group_role'] == 'co-leader') {
            foreach ($userGroups as $group) {
                $role = $group['BusinessOwner']['group_role'];
                switch ($role) {
                    case 'participant':
                        $business_owner_name = $group['BusinessOwner']['fname']." ".$group['BusinessOwner']['lname'];
                        $emailData[] = array('subject' => "FoxHopr: Chance to be Group Co-Leader",
                            'template' => "upgrade_membership_participant",
                            'variable' => array('businessowner'=>$business_owner_name,'case'=>'participant'),
                            'to' => $group['BusinessOwner']['email']);
                        break;
                    case 'co-leader':
                        $groupModel->updateAll(array('Group.group_coleader_id' => NULL),array( 'Group.id' => $group['BusinessOwner']['group_id']));
                        $parts = explode(',', $userData['Groups']['group_professions']);
                        while(($i = array_search($userData['BusinessOwners']['profession_id'], $parts)) !== false) {
                           unset($parts[$i]);
                        }
                        $updateProfessions = implode(',', $parts);
                        $updateMember = $userData['Groups']['total_member'] - 1;
                        $groupModel->updateAll(array('Group.group_professions' => "'".$updateProfessions."'",'Group.total_member' =>"'".$updateMember."'"),array( 'Group.id' => $group['BusinessOwner']['group_id']));
                        break;
        
                    default:
                        break;
                }
            }
        } else {
            foreach ($userGroups as $group) {
                $role = $group['BusinessOwner']['group_role'];
                switch ($role) {
                    case 'participant':
                        $business_owner_name = $group['BusinessOwner']['fname']." ".$group['BusinessOwner']['lname'];
                        $emailData[] = array('subject' => "FoxHopr: Chance to be Group Co-Leader",
                            'template' => "upgrade_membership_participant",
                            'variable' => array('businessowner'=>$business_owner_name,'case'=>'participant'),
                            'to' => $group['BusinessOwner']['email']);
                        break;
                    case 'co-leader':
                        $buisnessOwnerModel->updateAll(array('BusinessOwner.group_role' => '"leader"'),array( 'BusinessOwner.user_id' => $group['BusinessOwner']['user_id']));
                        $groupModel->updateAll(array('Group.group_leader_id' => $group['BusinessOwner']['user_id'],'Group.group_coleader_id' => NULL),array( 'Group.id' => $group['BusinessOwner']['group_id']));
                        $business_owner_name = $group['BusinessOwner']['fname']." ".$group['BusinessOwner']['lname'];
                        $emailData[] = array('subject' => "FoxHopr: Promoted as Group Leader",
                            'template' => "upgrade_membership_coleader",
                            'variable' => array('businessowner'=>$business_owner_name,'case'=>'co-leader'),
                            'to' => $group['BusinessOwner']['email']);
                        break;
                    case 'leader':
                        $parts = explode(',', $userData['Groups']['group_professions']);
                        while(($i = array_search($userData['BusinessOwners']['profession_id'], $parts)) !== false) {
                            unset($parts[$i]);
                        }
                        $updateProfessions = implode(',', $parts);
                        $updateMember = $userData['Groups']['total_member'] - 1;
                        $buisnessOwnerModel->updateAll(array('Group.group_professions' => "'".$updateProfessions."'",'Group.total_member' =>"'".$updateMember."'"),array( 'Group.id' => $group['BusinessOwner']['group_id']));
                        break;
        
                    default:
                        break;
                }
            }
        }
        return $emailData;
    }
    /**
     * To revoke change requests from db After shuffling, upgrade, downgrade, reactivate 
     * @param int userId
     * @author Rohan Julka
     */
    public function revokeGroupChangeRequest($businessOwnerID)
    {
        $groupChangeModel = ClassRegistry::init('GroupChangeRequest');
        $groupChangeModel->deleteAll(array('GroupChangeRequest.business_owner_id' => $businessOwnerID,'GroupChangeRequest.is_moved'=>0), false);
    }
    
}