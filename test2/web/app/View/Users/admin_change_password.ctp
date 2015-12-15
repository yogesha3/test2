<?php
/**
 * Admin change password form page 
 */
echo $this->Html->script('../developer/js/admin_validation');
?>
<!-- start: PAGE HEADER -->
<div class="row">
    <div class="col-sm-12">
        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <i class="clip-file"></i>
                <a href="<?php echo Router::url(array('controller'=>'dashboard','action'=>'index','admin'=>true));?>">
                    Home
                </a>
            </li>
            <li class="active">
                Change Password
            </li>
            <li class="search-box">
                <form class="sidebar-search">
                    <div class="form-group">
                        <input type="text" placeholder="Start Searching...">
                        <button class="submit">
                            <i class="clip-search-3"></i>
                        </button>
                    </div>
                </form>
            </li>
        </ol>
        <div class="page-header">
            <h1>Change Password<small></small></h1>
        </div>
        <!-- end: PAGE TITLE & BREADCRUMB -->
    </div>
</div>
<!-- end: PAGE HEADER -->
<div class="row">
    <div class="col-sm-12">
        <!-- start: FORM WIZARD PANEL -->
        <div class="panel panel-default">

            <div class="panel-body">
                <?php echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' => 'changePassword'), 'class' => 'smart-wizard form-horizontal', 'id' => 'userChangePasswordForm')); ?>
                <div id="wizard" class="swMain">
                    <div class="form-group">
                        <?php echo $this->Form->label('', 'Old  Password ' . $this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'oldPassword')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-7">
                            <?php 
                            echo $this->Form->password('User.current_password', array('label' => false, 'class' => 'form-control', 'id' => 'oldPassword', 'placeholder' => 'Old Password'));
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?php echo $this->Form->label('', 'New Password ' . $this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'newPassword')), array('class' => 'col-sm-3 control-label'));?>
                        <div class="col-sm-7">
                            <?php echo $this->Form->password('User.new_password', array('label' => false, 'class' => 'form-control', 'id' => 'newPassword', 'placeholder' => 'New Password')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $this->Form->label('', 'Confirm Password ' . $this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'cPassword')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-7">
                            <?php echo $this->Form->password('User.cpassword', array('label' => false, 'class' => 'form-control', 'id' => 'cPassword', 'placeholder' => 'Confirm Password')); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-2 col-sm-offset-8">
                        <?php echo $this->Form->button('Submit <i class="fa fa-arrow-circle-right"></i>', array('type' => 'submit', 'class' => 'btn btn-bricky pull-right')); ?>
                        
                        </div>
                    </div>

                </div>
                <?php echo $this->Form->end(); ?>
       

            </div>
        </div>
        <!-- end: FORM WIZARD PANEL -->
    </div>
</div>