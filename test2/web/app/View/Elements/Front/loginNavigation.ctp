<?php
$currentController = $this->params['controller'];
$currentAction = $this->params['action'];
$firstParam = isset($this->params['pass']['0']) ? $this->params['pass']['0'] : "";
//referral pages
$refReceActive = ($currentAction=="received")? "active" : "";
$refSentActive = ($currentAction=="sent")? "active" : "";
$sendRefActive = ($currentAction=="sendReferrals" || $currentAction=="send-referrals")? "active" : "";
$recArchActive = ($currentAction=="archive" && $firstParam=="received")? "active" : "";
$sentArchActive = ($currentAction=="archive" && $firstParam=="sent")? "active" : "";
//message pages
$msgReceActive = ($currentAction=="inbox" && $firstParam!="archive")? "active" : "";
$msgSentActive = ($currentAction=="sentMessages" && $firstParam!="archive")? "active" : "";
$msgCompActive = ($currentAction=="composeMessage")? "active" : "";
$msgArchActive = ($currentAction=="inbox" && $firstParam=="archive")? "active" : "";
$msgSentArchActive = ($currentAction=="sentMessages" && $firstParam=="archive")? "active" : "";
//events pages
$webcastActive = ($currentAction=="webcast")? "active" : "";
//contact pages
$addcontactActive = ($currentAction=="addContact" || $currentAction=="add-contact")? "active" : "";
$invitepartnersActive = ($currentAction=="invitePartners" || $currentAction=="invite-partners")? "active" : "";
$listpartnersActive= ($currentAction=="partnersList" || $currentAction=="partners-list")? "active" : "";
$contactListActive = ($currentAction=="contactList" || $currentAction=="contact-list")? "active" : "";
//Account pages
$accountActive = ($currentAction=="changePassword")? "active" : "";
$accProfileActive = ($currentAction=="profile" || $currentAction=="profileDetail")? "active" : "";
$accountNotifications = ($currentAction=="notifications")? "active" : "";
$socialActive = ($currentAction=="social")? "active" : "";
$accountTrainingVideo = ($currentAction=="trainingVideo")? "active" : "";
$accountBilling = ($currentAction=="billing" || $currentAction=="creditCard" || $currentAction=="purchaseReceipts")? "active" : "";
//dashboard
$dashboardActive = ($currentAction=="dashboard")? "active" : "";
//reviews
$reviewsActive = ($currentController=="reviews" && $currentAction=="index")? "active" : "";
//Team Pages
$currentTeamActive = ($currentController=="teams" && $currentAction=="teamList")? "active" : "";
$prevTeamActive = ($currentController=="teams" && $currentAction=="previousTeamList")? "active" : "";
$goalsActive = ($currentController=="teams" && $currentAction=="goals")? "active" : "";
?>
<div style="background:#fff; border:0" class="inner_pages_heading">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="intro-text"> </div>
			</div>
		</div>
	</div>
</div>
<div class="clearfix"></div>
<section id="inner_pages_top_gap">
	<div class="container">
		<div class="row">
			<div class=" col-md-9 col-sm-4 col-xs-12 ">
          <div class="logged-in">
            Logged in as <?php echo $loginUserName;?>
          </div>
          <div class="product_thumbnail ">
            <a href="<?php echo Router::url(array('controller'=>'businessOwners','action'=>'profile'),true);?>" title="View Profile"><?php echo $this->Html->image($profileImage,array('alt'=> '','width'=>'103','height'=>'103'));?></a>
          </div>
          <div class="thumbnail_text">
            <span class="porduct_name">
            	<?php 
            	switch ($loginUserInfo['BusinessOwner']['group_role']) {
            		case 'leader':
            			echo 'Group Leader';
            			break;
            		case 'co-leader':
            			echo 'Group Co-Leader';
            			break;
            		case 'participant':
            			echo 'Participant';
            			break;
            		
            		default:
            			echo '';
            			break;
            	}
            	?>
            </span>
            <div class="group1 groupFont"><?php echo Configure::read('GROUP_PREFIX').' '.$userGroup;?> 
            &nbsp;&nbsp;&nbsp;
            <?php if(date('Y-m-d') > date('Y-m-d', strtotime($loginUserInfo['BusinessOwner']['group_change']. ' + 30 days'))){ ?>
            <a class="btn btn-sm text-center add_focus change_group" href="<?php echo Router::url(array('controller'=>'groups','action'=>'group-change',$this->Encryption->encode('change')))?>" title="Change Group"><i class="fa fa-exchange"></i></a>
            <?php } else { ?>
            <a class="btn btn-sm text-center add_focus change_group disabled" href="javascript:void(0);" title="Change Group"><i class="fa fa-exchange"></i>
            </a>
            <?php }	?>
            
            <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="Group can be changed only once in 30 days."></i>
            </div>
          </div>
			</div>
			<div class=" col-md-3 col-sm-8 col-xs-12">
				<div class="logged_lixia" style="text-align: left"><?php echo $this->Html->image('video-img.png',array('alt'=> '','class'=>'center-block'));?></div>
			</div>
		</div>
		<div class="row">
	    <div class="megamenu">
	        <div class=" col-md-9 col-sm-12  padd-left-nav0">
	          <nav class="navbar navbar-default">
	            <div class="navbar-header">
	              <button data-target=".js-navbar-collapse" data-toggle="collapse" type="button" class="navbar-toggle"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
	            </div>
	            <div class="collapse navbar-collapse1 js-navbar-collapse">
	              <ul class="nav navbar-nav">
	                <li class="dropdown mega-dropdown">
	                <?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action' => 'dashboard'),array('class' =>'dropdown-toggle '.$dashboardActive));?>
	                </li>
	                <li class="dropdown mega-dropdown <?php if($this->params['controller']=="teams") { echo 'open'; }?>"> 
	                <?php $teamActive = $this->params['controller']=="teams" ? 'active' : '' ?>
	                <a class="dropdown-toggle <?php echo $teamActive;?>" href="<?php echo Router::url(array('controller' => 'teams', 'action' => 'teamList'));?>" aria-expanded="false">Team <i class="fa fa-angle-down nav_i"></i></a>
	                <?php //echo $this->Html->link('TEAM', array('controller' => 'teams', 'action' => 'teamList'),array('class' =>$active));?>
	                <ul class="dropdown-menu mega-dropdown-menu row">
	                    <li class="col-sm-311">
	                      <ul>
	                        <li><?php echo $this->Html->link('Current Team', array('controller' => 'teams', 'action' => 'teamList'),array('class'=>$currentTeamActive)); ?></li>
	                        <?php if(!empty($previousRecordCount)) {?>
							<li><?php echo $this->Html->link('Previous Team', array('controller' => 'teams', 'action' => 'previousTeamList'),array('class'=>$prevTeamActive)); ?></li>
							<?php }?>
							<li><?php echo $this->Html->link('Goals', array('controller' => 'teams', 'action' => 'goals'),array('class'=>$goalsActive)); ?></li>
	                      </ul>
	                    </li>
	                  </ul>
	                </li>
	                <li class="dropdown mega-dropdown <?php if($this->params['controller']=="referrals") { echo 'open'; }?>">	                
	                 <a class="dropdown-toggle <?php if($this->params['controller']=="referrals") { echo 'active'; }?>" href="<?php echo Router::url(array('controller'=>'referrals','action'=>'received'));?>" aria-expanded="false">Referrals <i class="fa fa-angle-down nav_i"></i></a>
                    <?php if($referalCounter>0){?>
	        		<div class="msg_counter_div">
					<?php echo $this->Html->link('<div class="msg_counter">'.$referalCounter.'</div>', array('controller' => 'referrals', 'action' => 'received'),array('escape' =>false)); ?>
	                </div>
	                <?php }?>
	                  <ul class="dropdown-menu mega-dropdown-menu row">
	                    <li class="col-sm-311">
	                      <ul>
	                        <li><?php echo $this->Html->link('Received', array('controller' => 'referrals', 'action' => 'received'),array('class'=>$refReceActive)); ?></li>
							<li><?php echo $this->Html->link('Sent', array('controller' => 'referrals', 'action' => 'sent'),array('class'=>$refSentActive)); ?></li>
							<li><?php echo $this->Html->link('Send Referral', array('controller' => 'referrals', 'action' => 'sendReferrals'),array('class'=>$sendRefActive)); ?></li>
							<li><?php echo $this->Html->link('Sent Archive', array('controller' => 'referrals', 'action' => 'archive','sent'),array('class'=>$sentArchActive)); ?></li>
							<li><?php echo $this->Html->link('Received Archive', array('controller' => 'referrals', 'action' => 'archive','received'),array('class'=>$recArchActive)); ?></li>
	                      </ul>
	                    </li>
	                  </ul>
	                </li>
	                <li class="dropdown mega-dropdown <?php if($this->params['controller']=="messages") { echo 'open'; }?>"> <a class="dropdown-toggle <?php if($this->params['controller']=="messages") { echo 'active'; }?>" href="<?php echo Router::url(array('controller'=>'messages','action'=>'inbox'));?>" aria-expanded="false">Messages <i class="fa fa-angle-down nav_i"></i></a>
	        		<?php if($messageCounter>0){?>
	        		<div class="msg_counter_div">
					<?php echo $this->Html->link('<div class="msg_counter" id="message_counter_element">'.$messageCounter.'</div>', array('controller' => 'messages', 'action' => 'inbox'),array('escape' =>false)); ?>
	                </div>
	                <?php }?>	                
	                  <ul class="dropdown-menu mega-dropdown-menu row">
	                    <li class="col-sm-311">
	                      <ul>
							<li><?php echo $this->Html->link('Inbox', array('controller' => 'messages', 'action' => 'inbox'),array('class'=>$msgReceActive)); ?></li>
							<li><?php echo $this->Html->link('Compose Message', array('controller' => 'messages', 'action' => 'composeMessage'),array('class'=>$msgCompActive)); ?></li>
							<li><?php echo $this->Html->link('Sent Messages', array('controller' => 'messages', 'action' => 'sentMessages'),array('class'=>$msgSentActive)); ?></li>							
							<li><?php echo $this->Html->link('Inbox Archive', array('controller' => 'messages', 'action' => 'inbox','archive'),array('class'=>$msgArchActive)); ?></li>
							<li><?php echo $this->Html->link('Sent Archive', array('controller' => 'messages', 'action' => 'sentMessages','archive'),array('class'=>$msgSentArchActive)); ?></li>
							<li class="divider"></li>
						  </ul>
	                    </li>
	                  </ul>
	                </li>
	                <li class="dropdown mega-dropdown <?php if($this->params['controller']=="events") { echo 'open'; }?>"> <a data-toggle="dropdown <?php if($this->params['controller']=="events") { echo 'active'; }?>" class="dropdown-toggle" href="<?php echo Router::url(array('controller'=>'events','action'=>'webcast'));?>" aria-expanded="false">Events <i class="fa fa-angle-down nav_i"></i></a>
	                <ul class="dropdown-menu mega-dropdown-menu row">
	                 <li class="col-sm-311">
	                    <ul>	                     
	                      <li><a href="javascript:void(0);">Upcoming Events</a></li>
	                      <li><a href="javascript:void(0);">Past Events </a></li>
	                      <li><a href="javascript:void(0);">Create An Event</a></li>
	                      <li><?php echo $this->Html->link('Webcast', array('controller' => 'events', 'action' => 'webcast'),array('class'=>$webcastActive)); ?></li>	                   
	                      <li class="divider"></li>	  
	                    </ul>
	                  </li>
	                </ul>
	                </li>
                  <li class="dropdown mega-dropdown <?php if($this->params['controller']=="contacts") { echo 'open'; }?>"> <a class="dropdown-toggle <?php if($this->params['controller']=="contacts") { echo 'active'; }?>" href="<?php echo Router::url(array('controller'=>'contacts','action'=>'contactList'));?>" aria-expanded="false">Contacts <i class="fa fa-angle-down nav_i"></i></a>
                    <ul class="dropdown-menu mega-dropdown-menu row">
                      <li class="col-sm-311">
                        <ul>
	                        <li><?php echo $this->Html->link('Contacts', array('controller' => 'contacts', 'action' => 'contactList'),array('class'=>$contactListActive)); ?></li>
                          <li><?php echo $this->Html->link('Add A Contact', array('controller' => 'contacts', 'action' => 'addContact'),array('class'=>$addcontactActive)); ?></li>
	                        <li><?php echo $this->Html->link('Partners', array('controller' => 'contacts', 'action' => 'partnersList'),array('class'=>$listpartnersActive)); ?></li>
	                        <li><?php echo $this->Html->link('Invite Partners', array('controller' => 'contacts', 'action' => 'invitePartners'),array('class'=>$invitepartnersActive)); ?></li>
                        <li class="divider"></li>
                        </ul>
	                    </li>
	                  </ul>         
	                 </li>
	                <li class="dropdown mega-dropdown <?php if($this->params['controller']=="businessOwners") { echo 'open'; }?>"> <a class="dropdown-toggle <?php if($this->params['controller']=="businessOwners") { echo 'active'; }?>" href="<?php echo Router::url(array('controller'=>'businessOwners','action'=>'profile'));?>" aria-expanded="false">Account <i class="fa fa-angle-down nav_i"></i></a>
	                  <ul class="dropdown-menu mega-dropdown-menu row">
	                    <li class="col-sm-311">
	                      <ul>
	                        <li><?php echo $this->Html->link('Profile', array('controller' => 'businessOwners', 'action' => 'profile'),array('class'=>$accProfileActive)); ?></li>
	                        <li><?php echo $this->Html->link('Change Password', array('controller' => 'businessOwners', 'action' => 'changePassword'),array('class'=>$accountActive)); ?></li>
	                        <li><?php echo $this->Html->link('Notifications', array('controller' => 'businessOwners', 'action' => 'notifications'),array('class'=>$accountNotifications)); ?></li>
	                        <li><?php echo $this->Html->link('Billing', array('controller' => 'businessOwners', 'action' => 'billing'),array('class'=>$accountBilling)); ?></li>
	                        <li><?php echo $this->Html->link('Social Media', array('controller'=>'businessOwners','action'=>'social'),array('class'=>$socialActive)); ?></li>
	                        
	                        <?php if($loginUserRole == 'leader' || $loginUserRole == 'co-leader'):?>
	                        <li><?php echo $this->Html->link('Training Video', array('controller' => 'businessOwners', 'action' => 'trainingVideo'),array('class'=>$accountTrainingVideo)); ?></li>
	                    	<?php endif;?>
	                        <li class="divider"></li>
	                      </ul>
	                    </li>
	                  </ul>
	                </li>
	                <li class="dropdown mega-dropdown"> 
	                <?php $active = $this->params['controller']=="reviews" ? 'active' : '' ?>
	                <?php echo $this->Html->link('Reviews', array('controller' => 'reviews', 'action' => 'index'),array('class' =>$active));?>
	                </li>
	              </ul>
	            </div>
	            <!-- /.nav-collapse -->
	          </nav>
	        </div>
	      </div>
	      <div class="col-md-3 col-sm-12 group_shuffling ">
	      <div class="media ">
	          <div class="media-left">
	           <?php echo $this->Html->image('upload_icon.png',array('alt'=> ''));?>
	          </div>
	          <div class="media-body">
	            <h4 class="media-heading"> Group Shuffling Date:</h4>	
	            <?php echo $shuffling_date = ($this->Session->read('Auth.Front.Groups.shuffling_date')!=NULL) ? date("M d, Y",strtotime($this->Session->read('Auth.Front.Groups.shuffling_date'))) : "";?>            
	           </div>
	        </div>
	      </div>
	    </div>		
<div class="clearfix"></div>
<div class="row margin_top_referral_search">  </div>
<div class="clearfix"></div>
