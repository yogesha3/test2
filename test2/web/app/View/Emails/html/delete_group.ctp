Dear <?php echo $businessowner;?>,<br/><br/>
You and your respective group members have been moved to the new group by the FoxHopr Admin.<br>
Please login to check you new group members.<br/><br/>
Group Name : <b><?php echo $groupname;?></b><br/>
Meeting Date : <b><?php echo $meetingdate;?></b><br/>
Meeting Time : <b><?php echo $meetingtime;?></b><br/><br>
<?php
if($role == 'leader') { ?>
You are the Leader of the group. To learn and unlock the group leader rights please watch our training video. <br>
1. Login with your credentials provided at the time of sign up.<br>
2. Click the 'Account' tab and navigate to 'Training Video' sub tab. <br>
3. Watch the entire video and unlock the group leader rights.<br>
<?php } else if($role == 'co-leader'){ ?>
You are the Co-Leader of the group. To learn and unlock the group leader rights please watch our training video. <br>
1. Login with your credentials provided at the time of sign up.<br>
2. Click the 'Account' tab and navigate to 'Training Video' sub tab. <br>
3. Watch the entire video and unlock the group leader rights.<br>
<?php }?>

<br/>
<br/>
Thanks,<br/>
Foxhopr Team
