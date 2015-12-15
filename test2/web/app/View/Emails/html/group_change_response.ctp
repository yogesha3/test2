Hi <?php echo ucfirst($name)?>, 
<br/><br/>
<?php if($role == 'leader' || $role == 'co-leader') {?>
Congratulations!  Your Team has successfully changed and you are now a <?php echo ucwords($role);?>! <br/><br/>
<?php } else {?>
Congratulations!  Your Team has been successfully changed!
<?php }?>
Below are your new Team details: <br/><br/>
Group ID - Group <?php echo $groupId;?><br/>
Group Meeting Time - <?php echo date('h:i A',strtotime($meetingTime));?><br/>
Group meeting date - <?php echo date("d/m/Y", strtotime($secondMeeting));?><br/><br>

<?php
if($role == 'leader') { ?>
You are the Team Leader!  To learn and unlock the Team Leader rights please watch our training video.<br/><br/>

1. Login with your credentials provided at the time of sign up.  Forgot them?  <a href="#">Click HERE</a>.<br/>
2. Click the 'Account' tab and navigate to 'Training Video' sub tab. <br/>
3. Watch the entire video and unlock the Team leader rights.<br/>
<?php } else if($role == 'co-leader'){ ?>
You are the Team Co-Leader. To learn and unlock the Team Co-Leader rights please watch our training video.<br/><br/>

1. Login with your credentials provided at the time of sign up.  Forgot them?  <a href="#">Click HERE</a>.<br/>
2. Click the 'Account' tab and navigate to 'Training Video' sub tab. <br/>
3. Watch the entire video and unlock the Team leader rights.<br/>
<?php }?>
<br/><br/>
Please <a href="#">login HERE</a> to view your new Team members and start Foxhopping!
<br/><br/><br/>
Thanks
FoxHopr Team