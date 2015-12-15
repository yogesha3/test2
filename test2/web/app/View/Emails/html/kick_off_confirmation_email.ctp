Dear <?php echo $name;?>,<br/><br/>
You have been successfully moved into a new group by the Admin.<br/>
Please login and check your new group members.<br/>
Go FoxHopping!!<br/>
Group Details:<br/>
Group ID - Group <?php echo $groupMailData['id']; ?><br/>
Group Meeting Time - <?php echo $groupMailData['time']; ?><br/>
Group Meeting Date - <?php echo $groupMailData['date']; ?><br/><br>
<?php
if($groupMailData['role'] == 'leader') { ?>
You are the Leader of the group. To learn and unlock the group leader rights please watch our training video. <br>
1. Login with your credentials provided at the time of sign up.<br>
2. Click the 'Account' tab and navigate to 'Training Video' sub tab. <br>
3. Watch the entire video and unlock the group leader rights.<br>
<?php } else if($groupMailData['role'] == 'co-leader'){ ?>
You are the Co-Leader of the group. To learn and unlock the group leader rights please watch our training video. <br>
1. Login with your credentials provided at the time of sign up.<br>
2. Click the 'Account' tab and navigate to 'Training Video' sub tab. <br>
3. Watch the entire video and unlock the group leader rights.<br>
<?php }?>

<br/><br/><br/>
Thanks,<br/>
Foxhopr Team<br/><br/>
