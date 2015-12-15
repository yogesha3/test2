Hi <?php echo $businessowner;?>,<br/><br/>
<?php if($role == "participent") {?>
Welcome <?php echo !empty($reactivated) ? 'back ' : ''; ?> to Foxhopr!   Your account has been <?php echo !empty($reactivated) ? 're-' : ''; ?>activated.  Below are your group details:<br/>
<?php } elseif($role=='leader') {?>
Welcome <?php echo !empty($reactivated) ? 'back ' : ''; ?> to Foxhopr!   Your account has been <?php echo !empty($reactivated) ? 're-' : ''; ?>activated as a Group Leader!  To learn and unlock Group Leader rights, please watch our mandatory training video here:
<br/>
Below are your group details:<br/>
<?php     
}?>
<br/><br/>
Group Name : <b><?php echo $groupname;?></b><br/>
Meeting Date : <b><?php echo $meetingdate;?></b><br/>
Meeting Time : <b><?php echo $meetingtime;?></b><br/><br>

<br/>
<br/>
Get started following these few easy steps:<br/><br/>

Download the mobile app on your iPhone, iPad or Android here: <a href="#" target="_blank">Android App</a>&nbsp;&nbsp;<a href="#" target="_blank">IOS App</a><br/><br/>

Start building your referral network with our custom CRM here:<br/><br/>

Customer Support can always be found here:<a href="<?php echo Router::url(array('controller'=>'pages','action'=>'contactUs'));?>" target="_blank">Contact Us</a><br/>
Thanks for joining, see you at the next meeting and start Foxhopping!
