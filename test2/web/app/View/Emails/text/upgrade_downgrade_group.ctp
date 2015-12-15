Dear <?php echo $businessowner;?>, 

Your group change request has been proposed successfully. 

Please login and check your new group members.

Go FoxHopping!!

Group Details: 
Group ID - Group <?php echo $groupname;?>
Group Meeting Time - <?php echo $meetingtime;?>
Group meeting date - <?php echo $meetingdate;?>

<?php
if($role == '2') { ?>
You are the Leader of the group. To learn and unlock the group leader rights please watch our training video. 
1. Login with your credentials provided at the time of sign up.
2. Click the 'Account' tab and navigate to 'Training Video' sub tab. 
3. Watch the entire video and unlock the group leader rights.
<?php } else if($role == '3'){ ?>
You are the Co-Leader of the group. To learn and unlock the group leader rights please watch our training video.
1. Login with your credentials provided at the time of sign up.
2. Click the 'Account' tab and navigate to 'Training Video' sub tab.
3. Watch the entire video and unlock the group leader rights.
<?php }?>


Thanks
FoxHopr Team

