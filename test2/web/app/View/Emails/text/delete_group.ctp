Dear <?php echo $businessowner;?>,
Welcome to FoxHopr, your account has been activated. Following are the Group Details:
Group Name : <?php echo $groupname;?>
Meeting Date : <?php echo $meetingdate;?>
Meeting Time : <?php echo $meetingtime;?>
<?php
if(($role == 2) || ($role == 'leader')) { ?>
You are the Leader of the group. To learn and unlock the group leader rights please watch our training video. 
1. Login with your credentials provided at the time of sign up.
2. Click the 'Account' tab and navigate to 'Training Video' sub tab. 
3. Watch the entire video and unlock the group leader rights.
<?php } else if(($role == 3) || ($role == 'co-leader')){ ?>
You are the Co-Leader of the group. To learn and unlock the group leader rights please watch our training video. 
1. Login with your credentials provided at the time of sign up.
2. Click the 'Account' tab and navigate to 'Training Video' sub tab. 
3. Watch the entire video and unlock the group leader rights.
<?php }?>



Thanks,
Foxhopr Team
