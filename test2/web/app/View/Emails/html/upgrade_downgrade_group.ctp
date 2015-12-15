Hi <?php echo $businessowner;?>, 
<br/><br/>

Congratulations!  Your account has been successfully changed from a <?php echo ucfirst($group_type_from);?> to a <?php echo ucfirst($group_type_to);?> Team. <br/><br>

Below are your new Team details:<br/><br/>

Group Details: <br/><br/>
Group ID - <?php echo $groupname;?><br/>
Group Meeting Time - <?php echo $meetingtime;?><br/>
Group Meeting Date - <?php echo $meetingdate;?><br/><br>

<?php
if($role == '2') { ?>
You are the Team Leader!  To learn and unlock the Team Leader rights please watch our training video.<br/><br/>

1. Login with your credentials provided at the time of sign up.  Forgot them?  <a href="#">Click HERE</a>.<br/>
2. Click the 'Account' tab and navigate to 'Training Video' sub tab. <br/>
3. Watch the entire video and unlock the Team leader rights.<br/>
<?php } else if($role == '3'){ ?>
You are the Team Co-Leader. To learn and unlock the Team Co-Leader rights please watch our training video.<br/><br/>

1. Login with your credentials provided at the time of sign up.  Forgot them?  <a href="#">Click HERE</a>.<br/>
2. Click the 'Account' tab and navigate to 'Training Video' sub tab. <br/>
3. Watch the entire video and unlock the Team leader rights.<br/>
<?php }?>
<br/><br/>
Please login <a href="#">HERE</a> to view your new Team members and start Foxhopping!

<br/><br/><br/>
Thanks
FoxHopr Team
