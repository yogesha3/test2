<?php
/**
 * Responsible for frontend layout
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if (!empty($metaDescription)) { ?>
    <meta name="description" content="<?php echo $metaDescription; ?>" />
  	<?php } ?>
  	<?php if (!empty($metaKeywords)) { ?>
    <meta name="keywords" content="<?php echo $metaKeywords; ?>" />
  	<?php } ?>
  	<link rel="icon" type="image/x-icon" href="<?php echo $this->Html->url('/img/favicon.ico');?>">
    <meta name="author" content="">
    
    <title>		
		<?php echo $titleForLayout = (isset($titleForLayout)) ? $titleForLayout : 'Foxhopr'; ?>		
	</title> 
    <?php
		echo $this->Html->meta('icon');
		echo $this->Html->css(array('bootstrap.min','responsive','fonts','sass-compiled','https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css','developer','bootstrap-multiselect','../assets/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch','../assets/plugins/bootstrap-modal/css/bootstrap-modal'));	
		if ($this->params['controller'] != "pages" && $this->params['action'] != "home") {
			echo $this->Html->css('header-footer-inner-pages.css');
	    }
		echo $this->Html->css('custom.css');
	?>
	<?php echo $this->Html->script(array('Front/jquery','/assets/plugins/jquery-validation/dist/jquery.validate.min','Front/jquery.form','Front/front_validation','Front/infinitescroll.min','Front/bootstrap-multiselect', '/assets/plugins/blockUI/jquery.blockUI.js', 'all.js'));?>

  <?php 
  if(!empty($validationJs)){
            foreach ($validationJs as $jsFile){
                echo $this->Html->script('Front/validations/'.$jsFile);
            }
        }
  echo $this->Html->scriptBlock('var BASE_URL = '.$this->Js->object(Configure::read('SITE_URL')).';'); 
  echo $this->Html->scriptBlock('var PAGE = '.$this->Js->object($this->params['controller']).';');
  ?>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
 <!--- For Country State -->
		<?php if($this->params['controller'] != 'dashboard'){	
        echo $this->Html->css('jQueryUI'); }?>
<!--<link href="http://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">-->
<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<!-- End-->
</head>
<body id="page-top" class="index">
<div style="display: none;" id="page-preloader"><span style="display: none;" class="spinner"></span></div>
<?php echo $this->element("Front/navigation");?>
<?php 
$menuPages = array('dashboard','team','referrals','messages','events','contacts','accounts','reviews','teams','businessOwners');
$notinclude = array('rating');
if(isset($isUserLogin) && $isUserLogin && $this->Session->check('UID') == false && in_array($this->params['controller'],$menuPages) && !in_array($this->params['action'],$notinclude)) {
  echo $this->element("Front/loginNavigation");
}
?>
<?php echo $this->fetch('content'); ?>
<?php echo $this->element("Front/footer");?>

<style>
  .logo{ width:197px !important}
.navbar-brand{ padding-bottom:0; padding-top:0}
.navbar-fixed-top.navbar-shrink { padding: 3px 0 2px; !important}
.navbar .navbar-nav { margin-top: -5px;}
.footer_inner1{ background:#f2f2f2 !important; padding-top:30px;padding-bottom:30px; border-top:0px}
.footer_inner2{ background:#f2f2f2 !important}
</style>
