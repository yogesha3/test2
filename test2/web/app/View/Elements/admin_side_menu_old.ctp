<?php
/**
 * Admin panel side bar menu view file
 * @author Laxmi Saini 
 */
?>
<div class="navbar-content">
    <!-- start: SIDEBAR -->
    <div class="main-navigation navbar-collapse collapse">
        <!-- start: MAIN NAVIGATION MENU -->
        <ul class="main-navigation-menu">
            <li <?php if($this->params['controller']=="Dashboard"){ ?>class="active"<?php }?>>
                <?php
                //echo $this->Html->link('<i class="clip-home-3"></i><span class="title"> Dashboard </span><span class="selected"></span>', array('controller' => 'admin', 'action' => 'dashboard'), array('escape' => false));
                ?>       
            </li>
            <li <?php if($this->params['controller']=="plans"){ ?>class="active"<?php }?>> <!-- class=" open" -->
                <?php
                echo $this->Html->link('<i class="clip-screen"></i><span class="title"> Plans </span><span class="selected"></span>', array('controller'=>'plans','action'=>'index'), array('escape' => false));
                ?>
            </li>
          
           <li <?php if($this->params['controller']=="professions"){ ?>class="active"<?php }?>>
    
                <?php
                echo $this->Html->link('<i class="fa fa-briefcase"></i><span class="title"> Professions </span><i class="icon-arrow"></i><span class="selected"></span>', '#', array('escape' => false));
               
                $list = array(
                    $this->Html->link('<span class="title"> Profession List </span>', array('controller' => 'professions', 'action' => 'index'), array('escape' => false)),
                    $this->Html->link('<span class="title"> Add Profession </span>', array('controller' => 'professions', 'action' => 'addProfession'), array('escape' => false))
                );
                echo $this->Html->nestedList($list, array('class' => 'sub-menu'));
                ?>
            </li> 
           <li <?php if($this->params['controller']=="cms"){ ?>class="active"<?php }?>>
    
                <?php
                echo $this->Html->link('<i class="clip-file"></i><span class="title"> Pages </span><i class="icon-arrow"></i><span class="selected"></span>', '#', array('escape' => false));
               
                $list = array(
                    $this->Html->link('<span class="title"> About Us </span>', array('controller' => 'cms', 'action' => 'about'), array('escape' => false)),
                    $this->Html->link('<span class="title"> FAQ </span>', array('controller' => 'cms', 'action' => 'faq'), array('escape' => false)),
                    $this->Html->link('<span class="title"> Privacy Policy </span>', array('controller' => 'cms', 'action' => 'privacyPolicy'), array('escape' => false)),
                    $this->Html->link('<span class="title"> Terms & Conditions </span>', array('controller' => 'cms', 'action' => 'termsConditions'), array('escape' => false)),
                );
                echo $this->Html->nestedList($list, array('class' => 'sub-menu'));
                ?>
            </li> 

        </ul>
        <!-- end: MAIN NAVIGATION MENU -->
    </div>
    <!-- end: SIDEBAR -->
</div>


