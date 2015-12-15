Dear <?php echo ucfirst($name)?>, 


Your group change request has been proposed successfully.

You have been successfully moved into a new group by the Admin.

Please login and check your new group members.

Go FoxHopping!!


Group Details: 
Group ID - Group <?php echo $groupId;?>
Group Meeting Time - <?php echo date('h:i A',strtotime($meetingTime));?>
Group meeting date - <?php echo date("d/m/Y", strtotime($secondMeeting));?> 

<?php
if($role == 'leader') { ?>
You are the Leader of the group. To learn and unlock the group leader rights please watch our training video. 
1. Login with your credentials provided at the time of sign up.
2. Click the 'Account' tab and navigate to 'Training Video' sub tab. 
3. Watch the entire video and unlock the group leader rights.
<?php } else if($role == 'co-leader'){ ?>
You are the Co-Leader of the group. To learn and unlock the group leader rights please watch our training video. 
1. Login with your credentials provided at the time of sign up.
2. Click the 'Account' tab and navigate to 'Training Video' sub tab. 
3. Watch the entire video and unlock the group leader rights.
<?php }?>

Thanks
FoxHopr Team