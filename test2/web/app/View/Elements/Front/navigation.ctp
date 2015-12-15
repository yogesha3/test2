<?php
/**
 * Navigation element of Front panel
 */
?>
<!-- Collect the nav links, forms, and other content for toggling -->
		<div id="header" class="header-section">      
            <div class="navbar navbar-inverse navbar-fixed-top animated drop-nav" role="banner" style="margin-bottom:0;border-radius: 0; background:rgba(0,0,0,0.0)">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <?php echo $this->Html->link($this->Html->image('logo.png', array('class'=>'logo','alt'=>'Foxhopr')),'/',array('class'=>'navbar-brand','escape' => false));?>
                    </div>
                    <!-- NAVIGATION LINKS -->
                    <nav class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">
				<li class="hidden"><a href="#page-top"></a></li>
				<li class="page-scroll"><?php echo $this->Html->link('FAQ',array('controller'=>'pages','action'=>'faq'))?></li>
				<li class="page-scroll"><a href="#">Tour</a></li>
				<li class="page-scroll"><a href="#">Benefits</a></li>
				<li class="page-scroll"><a href="#">Blog</a></li>
				<?php if(isset($isUserLogin) && !$isUserLogin){?>
				<li class="page-scroll login_index login_btn"><?php echo $this->Html->link('Login',array('controller'=>'users','action'=>'login'))?></li>
				<li class="page-scroll login_index signup_btn"><?php echo $this->Html->link('Sign Up',array('controller'=>'users','action'=>'signUp'), array('class' => 'selected'))?></li>
				<?php }else{?>
				<li class="page-scroll"><?php if (isset($userGroup)) {
					echo $this->Html->link('My Account',array('controller'=>'dashboard','action'=>'dashboard'));
				}
				?></li>
				<!--<li class="page-scroll"><?php echo $this->Html->link('MyAccount',array('controller'=>'referrals','action'=>'received'))?></li>-->
				<li class="page-scroll"><?php echo $this->Html->link('Logout',array('controller'=>'users','action'=>'logout'))?></li>
				<?php }?>
			</ul>
                    </nav>
                </div>
            </div>
        </div>

<?php if ($this->params['controller']=="pages" && $this->params['action']=='home') { 
	echo $this->Session->flash();
?>
<!-- Header -->
<header>
	<div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="intro-text">
                    <span class="name"> In-person business networking; online.</span>
                    <span class="skills">Meet. Share. Grow.</span>
                </div>
            </div>
            <div class="col-md-6"> 
                <div class="video_div">
                    <?php echo $this->Html->image('profile.png', array('class'=>'img-responsive','alt'=>'Foxhopr'));?>
                </div>
            </div>
            <div class="clearfix"></div>
			<div class="col-md-12">
				<div class="joinus">
					<?php echo $this->Html->link('Join us', array('controller'=>'users', 'action'=>'signUp'),array('class'=>'join_btn'));?>
				</div>
				<div class="down_arrow">
					<i class="fa fa-angle-down"></i>
				</div>
			</div>
        </div>
	</div>
</header>
<?php } else {
	echo $this->Session->flash();
}

