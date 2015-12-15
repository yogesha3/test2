<?php

/**
 * Admin panel side bar menu view file
 */
?>
<div class="navbar-content">
    <!-- start: SIDEBAR -->
    <div class="main-navigation navbar-collapse collapse">
        <!-- start: MAIN NAVIGATION MENU -->
        <ul class="main-navigation-menu">
            <li <?php if($this->params['controller']=="dashboard"){ ?>class="active"<?php }?>>
                <?php
                echo $this->Html->link('<i class="clip-home-3"></i><span class="title"> Dashboard </span><span class="selected"></span>', array('controller' => 'dashboard', 'action' => 'index','admin'=>true), array('escape' => false));
                ?>       
            </li>
            <!--<li <?php if($this->params['controller']=="plans"){ ?>class="active"<?php }?>>
                <?php
                echo $this->Html->link('<i class="clip-screen"></i><span class="title"> Plans </span><span class="selected"></span>', array('controller'=>'plans','action'=>'index'), array('escape' => false));
                ?>
            </li>-->
          
           <li <?php if($this->params['controller']=="professions"){ ?>class="active"<?php }?>>
    
                <?php
                echo $this->Html->link('<i class="fa fa-briefcase"></i><span class="title"> Professions </span><i class="icon-arrow"></i><span class="selected"></span>', '#', array('escape' => false));
                ?>
                <ul class="sub-menu" >
                    <li <?php if($this->params['controller']=="professions" && $this->params['action']=="admin_categoryList"){ ?>class="active"<?php }?>>
                       <?php echo $this->Html->link('<span class="title"> Category List </span>', array('controller' => 'professions', 'action' => 'categoryList'), array('escape' => false));?>
                    </li>
                    <li <?php if($this->params['controller']=="professions" && $this->params['action']=="admin_index"){ ?>class="active"<?php }?>>
                        <?php echo $this->Html->link('<span class="title"> Profession List </span>', array('controller' => 'professions', 'action' => 'index'), array('escape' => false));?>
                    </li>
                    <!--<li <?php if($this->params['controller']=="professions" && $this->params['action']=="admin_importProfession"){ ?>class="active"<?php }?>>
                        <?php echo $this->Html->link('<span class="title"> Import Profession </span>', array('controller' => 'professions', 'action' => 'importProfession'), array('escape' => false));?>
                    </li>-->
                </ul>
            </li> 

           <li <?php if($this->params['controller']=="cms" || $this->params['controller']=="faqs"){ ?>class="active"<?php }?>>    
                <?php echo $this->Html->link('<i class="clip-file"></i><span class="title"> Pages </span><i class="icon-arrow"></i><span class="selected"></span>', '#', array('escape' => false));?>
                <ul class="sub-menu" >
                    <li <?php if(($this->params['controller']=="cms" || $this->params['controller']=="faqs") && $this->params['action']=="admin_about"){ ?>class="active"<?php }?>>
                       <?php echo $this->Html->link('<span class="title"> About Us </span>', array('controller' => 'cms', 'action' => 'about'), array('escape' => false));?>
                    </li>
                    <li <?php if($this->params['controller']=="faqs"){ ?>class="active"<?php }?>>
                        <?php echo $this->Html->link(' FAQ<i class="icon-arrow"></i> ', 'javascript:void(0);', array('escape' => false));?>
                        <ul class="sub-menu">
                            <li <?php if($this->params['controller']=="faqs" && $this->params['action']=="admin_category"){ ?>class="active"<?php }?>>
                                   <?php echo $this->Html->link('<span class="title"> Category List </span>', array('controller' => 'faqs', 'action' => 'category'), array('escape' => false,'li'=>array('class'=>'open')));?>
                            </li>
                            <li <?php if($this->params['controller']=="faqs" && $this->params['action']=="admin_index"){ ?>class="active"<?php }?>>
                                   <?php echo $this->Html->link('<span class="title"> FAQ List </span>', array('controller' => 'faqs', 'action' => 'index','admin'=>true), array('escape' => false));?>
                            </li>
                        </ul> 
                    </li>
                    <li <?php if($this->params['controller']=="cms" && $this->params['action']=="admin_privacyPolicy"){ ?>class="active"<?php }?>>
                        <?php echo $this->Html->link('<span class="title"> Privacy Policy </span>', array('controller' => 'cms', 'action' => 'privacyPolicy'), array('escape' => false));?>
                    </li>
                    <li <?php if($this->params['controller']=="cms" && $this->params['action']=="admin_termsConditions"){ ?>class="active"<?php }?>>
                       <?php echo $this->Html->link('<span class="title"> Terms & Conditions </span>', array('controller' => 'cms', 'action' => 'termsConditions'), array('escape' => false));?>
                    </li>
                </ul>            
            </li>
            <li <?php if($this->params['controller']=="businessOwners"){ ?>class="active"<?php }?>>
                <?php
                echo $this->Html->link('<i class="fa fa-group"></i><span class="title"> Business Owners </span><i class="icon-arrow"></i><span class="selected"></span>', '#', array('escape' => false));               
                ?>
                <ul class="sub-menu" >
                    <li <?php if($this->params['controller']=="businessOwners" && $this->params['action']=="admin_index"){ ?>class="active"<?php }?>>
                       <?php echo $this->Html->link('<span class="title"> User List </span>', array('controller' => 'businessOwners', 'action' => 'index'), array('escape' => false));?>
                    </li>
                    <li <?php if($this->params['controller']=="businessOwners" && $this->params['action']=="admin_kickedOffUsers"){ ?>class="active"<?php }?>>
                        <?php echo $this->Html->link('<span class="title"> Kicked-Off Users </span>', array('controller'=>'businessOwners','action'=>'kickedOffUsers','admin'=>true), array('escape' => false));?>
                    </li>
                    <!--<li <?php if($this->params['controller']=="businessOwners" && $this->params['action']=="admin_groupChangeRequest"){ ?>class="active"<?php }?>>
                       <?php echo $this->Html->link('<span class="title"> Group Change Requests </span>', array('controller' => 'businessOwners', 'action' => 'groupChangeRequest'), array('escape' => false));?>
                    </li>-->
                    <li <?php if($this->params['controller']=="businessOwners" && $this->params['action']=="admin_membershipLevels"){ ?>class="active"<?php }?>>
                    	<?php echo $this->Html->link('<span class="title"> Membership Levels </span>', array('controller' => 'businessOwners', 'action' => 'membershipLevels','admin'=>true), array('escape' => false));?>
                    </li>
                    <!--<li <?php if($this->params['controller']=="businessOwners" && $this->params['action']=="admin_groupChangeRequest"){ ?>class="active"<?php }?>>
                       <?php echo $this->Html->link('<span class="title"> Leader Change Request </span>', array('controller' => 'businessOwners', 'action' => 'index'), array('escape' => false));?>
                    </li>-->
                </ul>
            </li> 

            <li <?php if($this->params['controller']=="trainingvideos"){ ?>class="active"<?php }?>>
    
                <?php
                echo $this->Html->link('<i class="fa fa-video-camera"></i><span class="title"> Training Videos </span><i class="icon-arrow"></i><span class="selected"></span>', '#', array('escape' => false));
                ?>
                <ul class="sub-menu" >
                    <li <?php if($this->params['controller']=="trainingvideos" && $this->params['action']=="admin_index"){ ?>class="active"<?php }?>>
                       <?php echo $this->Html->link('<span class="title"> Video List </span>', array('controller' => 'trainingvideos', 'action' => 'index'), array('escape' => false));?>
                    </li>
                    <li <?php if($this->params['controller']=="trainingvideos" && $this->params['action']=="admin_add"){ ?>class="active"<?php }?>>
                        <?php echo $this->Html->link('<span class="title"> Add Video </span>', array('controller' => 'trainingvideos', 'action' => 'add'), array('escape' => false));?>
                    </li>
                </ul>
            </li>
			<li <?php if($this->params['controller']=="groups" || $this->params['controller']=="Groups"){ ?>class="active"<?php }?>>
    
                <?php
                echo $this->Html->link('<i class="fa fa-users"></i><span class="title"> Groups </span><i class="icon-arrow"></i><span class="selected"></span>', '#', array('escape' => false));
                ?>
                <ul class="sub-menu" >
                    <li <?php if($this->params['controller']=="groups" && $this->params['action']=="admin_index"){ ?>class="active"<?php }?>>
                       <?php echo $this->Html->link('<span class="title"> Group List </span>', array('controller' => 'groups', 'action' => 'index'), array('escape' => false));?>
                    </li>
                    <li <?php if($this->params['controller']=="groups" && $this->params['action']=="admin_addGroup"){ ?>class="active"<?php }?>>
                        <?php echo $this->Html->link('<span class="title"> Add Group </span>', array('controller' => 'groups', 'action' => 'addGroup','admin'=>true), array('escape' => false));?>
                    </li>
                    <li <?php if($this->params['controller']=="groups" && $this->params['action']=="admin_requestIndex"){ ?>class="active"<?php }?>>
                        <?php echo $this->Html->link('<span class="title"> Group Requests </span>', array('controller' => 'groups', 'action' => 'requestIndex'), array('escape' => false));?>
                    </li>
                </ul>
            </li>
            <li <?php if($this->params['controller']=="coupons"){ ?>class="active"<?php }?>>
    
                <?php
                echo $this->Html->link('<i class="fa fa-list-alt"></i><span class="title"> Coupon Code </span><i class="icon-arrow"></i><span class="selected"></span>', '#', array('escape' => false));
                ?>
                <ul class="sub-menu" >
                    <li <?php if($this->params['controller']=="coupons" && $this->params['action']=="admin_index"){ ?>class="active"<?php }?>>
                       <?php echo $this->Html->link('<span class="title"> Coupon List </span>', array('controller' => 'coupons', 'action' => 'index'), array('escape' => false));?>
                    </li>
                    <li <?php if($this->params['controller']=="coupons" && $this->params['action']=="admin_add"){ ?>class="active"<?php }?>>
                       <?php echo $this->Html->link('<span class="title"> Add Coupon </span>', array('controller' => 'coupons', 'action' => 'add'), array('escape' => false));?>
                    </li>
                </ul>
            </li>
           <li <?php if($this->params['controller']=="webcasts"){ ?>class="active"<?php }?>>
    
                <?php
                echo $this->Html->link('<i class="fa fa-youtube-play"></i><span class="title"> Webcasts </span><i class="icon-arrow"></i><span class="selected"></span>', '#', array('escape' => false));
                ?>
                <ul class="sub-menu" >
                    <li <?php if($this->params['controller']=="webcasts" && $this->params['action']=="admin_index"){ ?>class="active"<?php }?>>
                       <?php echo $this->Html->link('<span class="title"> Webcast List </span>', array('controller' => 'webcasts', 'action' => 'index'), array('escape' => false));?>
                    </li>
                    <li <?php if($this->params['controller']=="webcasts" && $this->params['action']=="admin_add"){ ?>class="active"<?php }?>>
                       <?php echo $this->Html->link('<span class="title"> Add Webcast</span>', array('controller' => 'webcasts', 'action' => 'add','admin'=>true), array('escape' => false));?>
                    </li>
                </ul>
            </li>
            <li <?php if($this->params['controller']=="newsletters"){ ?>class="active"<?php }?>>    
                <?php
                echo $this->Html->link('<i class="clip-pencil"></i><span class="title"> Newsletters </span><i class="icon-arrow"></i><span class="selected"></span>', '#', array('escape' => false));
                ?>
                <ul class="sub-menu" >
                    <li <?php if($this->params['controller']=="newsletters" && $this->params['action']=="admin_index"){ ?>class="active"<?php }?>>
                       <?php echo $this->Html->link('<span class="title"> Template List </span>', array('controller' => 'newsletters', 'action' => 'index'), array('escape' => false));?>
                    </li>
                    <li <?php if($this->params['controller']=="newsletters" && $this->params['action']=="admin_createTemplate"){ ?>class="active"<?php }?>>
                       <?php echo $this->Html->link('<span class="title"> Add Newsletter Template </span>', array('controller' => 'newsletters', 'action' => 'createTemplate'), array('escape' => false));?>
                    </li>
                    <li <?php if($this->params['controller']=="newsletters" && $this->params['action']=="admin_subscribeList"){ ?>class="active"<?php }?>>
                       <?php echo $this->Html->link('<span class="title"> Subscriber List </span>', array('controller' => 'newsletters', 'action' => 'subscribeList'), array('escape' => false));?>
                    </li>
                    <li <?php if($this->params['controller']=="newsletters" && $this->params['action']=="admin_sendNewsletter"){ ?>class="active"<?php }?>>
                       <?php echo $this->Html->link('<span class="title"> Send Newsletter </span>', array('controller' => 'newsletters', 'action' => 'sendNewsletter'), array('escape' => false));?>
                    </li>                    
                </ul>
            </li>
            <li <?php if($this->params['controller']=="advertisements"){ ?>class="active"<?php }?>>    
                <?php
                echo $this->Html->link('<i class="fa fa-adn"></i><span class="title"> Advertisements </span><i class="icon-arrow"></i><span class="selected"></span>', '#', array('escape' => false));
                ?>
                <ul class="sub-menu" >
                    <li <?php if($this->params['controller']=="advertisements" && $this->params['action']=="admin_index"){ ?>class="active"<?php }?>>
                       <?php echo $this->Html->link('<span class="title"> Advertisement List </span>', array('controller' => 'advertisements', 'action' => 'index', 'admin' =>true), array('escape' => false));?>
                    </li>
                    <li <?php if($this->params['controller']=="advertisements" && $this->params['action']=="admin_add"){ ?>class="active"<?php }?>>
                       <?php echo $this->Html->link('<span class="title"> Add Advertisement </span>', array('controller' => 'advertisements', 'action' => 'add','admin' =>true), array('escape' => false));?>
                    </li>
                </ul>
            </li>
            <li <?php if($this->params['controller']=="groupShuffles"){ ?>class="active"<?php }?>>    
                <?php
                echo $this->Html->link('<i class="clip-transfer"></i><span class="title"> Shuffling </span><i class="icon-arrow"></i><span class="selected"></span>', '#', array('escape' => false));
                ?>
                <ul class="sub-menu" >
                    <li <?php if($this->params['controller']=="groupShuffles" && $this->params['action']=="admin_shufflingStep1"){ ?>class="active"<?php }?>>
                       <?php echo $this->Html->link('<span class="title"> Shuffling List</span>', array('controller' => 'groupShuffles', 'action' => 'shufflingStep1', 'admin' =>true), array('escape' => false));?>
                    </li>
                    <li <?php if($this->params['controller']=="groupShuffles" && $this->params['action']=="admin_setShufflingCriteria"){ ?>class="active"<?php }?>>
                       <?php echo $this->Html->link('<span class="title"> Shuffling Percentage </span>', array('controller' => 'groupShuffles', 'action' => 'setShufflingCriteria','admin' =>true), array('escape' => false));?>
                    </li>
                </ul>
            </li>
            <li <?php if($this->params['controller']=="affiliates"){ ?>class="active"<?php }?>>    
                <?php
                echo $this->Html->link('<i class="clip-share"></i><span class="title"> Affiliates </span><i class="icon-arrow"></i><span class="selected"></span>', '#', array('escape' => false));
                ?>
                <ul class="sub-menu" >
                    <li <?php if($this->params['controller']=="affiliates" && $this->params['action']=="admin_index"){ ?>class="active"<?php }?>>
                       <?php echo $this->Html->link('<span class="title"> Affiliate List</span>', array('controller' => 'affiliates', 'action' => 'index', 'admin' =>true), array('escape' => false));?>
                    </li>
                    <li <?php if($this->params['controller']=="affiliates" && $this->params['action']=="admin_addAffiliate"){ ?>class="active"<?php }?>>
                       <?php echo $this->Html->link('<span class="title"> Add Affiliate </span>', array('controller' => 'affiliates', 'action' => 'addAffiliate','admin' =>true), array('escape' => false));?>
                    </li>
                </ul>
            </li>
<li <?php if($this->params['controller']=="suggestions"){ ?>class="active"<?php }?>>    
                <?php
                echo $this->Html->link('<i class="fa fa-comments"></i><span class="title"> Suggestions </span><span class="selected"></span>', array('controller' => 'suggestions', 'action' => 'index', 'admin' =>true), array('escape' => false));
               
                ?>
            </li> 
			<li <?php if($this->params['controller']=="transactions"){ ?>class="active"<?php }?>>
                <?php
                echo $this->Html->link('<i class="fa fa-random"></i><span class="title"> Transactions </span><span class="selected"></span>', array('controller'=>'transactions','action'=>'index','admin'=>true), array('escape' => false));
                ?>
            </li>

            <li <?php if($this->params['controller']=="adobeConnect"){ ?>class="active"<?php }?>>    
                <?php
                echo $this->Html->link('<i class="clip-share"></i><span class="title"> Adobe Connect </span><i class="icon-arrow"></i><span class="selected"></span>', '#', array('escape' => false));
                ?>
                <ul class="sub-menu" >
                    <li <?php if($this->params['controller']=="adobeConnect" && $this->params['action']=="admin_index"){ ?>class="active"<?php }?>>
                       <?php echo $this->Html->link('<span class="title"> Hosted Accounts</span>', array('controller' => 'adobeConnect', 'action' => 'index', 'admin' =>true), array('escape' => false));?>
                    </li>
                    <!-- <li <?php if($this->params['controller']=="affiliates" && $this->params['action']=="admin_addAffiliate"){ ?>class="active"<?php }?>>
                       <?php echo $this->Html->link('<span class="title"> Add Affiliate </span>', array('controller' => 'affiliates', 'action' => 'addAffiliate','admin' =>true), array('escape' => false));?>
                    </li> -->
                </ul>
            </li>
            

        </ul>
        <!-- end: MAIN NAVIGATION MENU -->
    </div>
    <!-- end: SIDEBAR -->
</div>
