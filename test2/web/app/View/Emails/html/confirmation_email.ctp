Hi <?php echo $businessowner;?> ,<br/><br/><br/>
Congratulations on becoming a Foxhopr!  You're on your way to building success!  <br/>

You can login to Foxhopr after activating your account by clicking the following link:<br/>

<a href="<?php echo $activate_link;?>" target="_blank">Activate Your Account</a><br/>
Username : <b><?php echo $username;?></b><br/>
Password : <b><?php echo $password;?></b><br/><br/>
Customer Support can always be found here:<a href="<?php echo Router::url(array('controller'=>'pages','action'=>'contactUs'));?>" target="_blank">Contact Us</a><br/><br/>

Thanks for joining, see you at the next meeting and start Foxhopping!<br/>