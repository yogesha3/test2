<?php
/**
 * Header element of admin panel
 */
?>
<div class="navbar navbar-inverse navbar-fixed-top">
    <!-- start: TOP NAVIGATION CONTAINER -->
    <div class="container">
        <div class="navbar-header">
            <!-- start: RESPONSIVE MENU TOGGLER -->
            <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
                <span class="clip-list-2"></span>
            </button>
            <!-- end: RESPONSIVE MENU TOGGLER -->
            <!-- start: LOGO -->
            <?php echo $this->Html->link($this->Html->image('logo.png', array('width'=>'156px')), '#', array('class'=>'navbar-brand admin-logo','escape'=>false));?>
            <!-- end: LOGO -->
        </div>
        <div class="navbar-tools">
            <!-- start: TOP NAVIGATION MENU -->
            <ul class="nav navbar-right">
               <li class="dropdown current-user">
                   <?php 
                   echo $this->Html->link($this->Html->image('no_image.png',array('class'=>'circle-img', 'width' => '35px', 'height' => '35px'))
                           ."&nbsp;".$this->Html->tag('span',ucfirst($this->Session->read('Auth.User.username')),array('class'=>'username'))
                           .'&nbsp;<i class="clip-chevron-down"></i>',
                           '#',
                           array('class'=>'dropdown-toggle','data-toggle'=>'dropdown','escape'=>false));
                   ?>
                    <ul class="dropdown-menu">
                    <li>
                            <?php
                             echo $this->Html->link('<i class="fa fa-key"></i>&nbsp;Change Password', array('controller'=>'users', 'action'=>'changePassword'), array('escape'=>false));
                            ?>
                        </li>
                        <li>
                            <?php echo $this->Html->link('<i class="clip-exit"></i>&nbsp;Log Out', array('controller'=>'users', 'action'=>'logout','admin'=>true), array('escape'=>false));?>
                        </li>
                    </ul>
                </li>
                <!-- end: USER DROPDOWN -->
            </ul>
            <!-- end: TOP NAVIGATION MENU -->
        </div>
    </div>
    <!-- end: TOP NAVIGATION CONTAINER -->
</div>

<div class="main-container">
    <?php echo $this->Session->flash();