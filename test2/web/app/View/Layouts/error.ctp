<?php

/**
 * this is error layout page
 */
$siteDescription = __d('B2B', 'FoxHopr');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" type="image/x-icon" href="<?php echo $this->Html->url('/img/favicon.ico');?>">
        <?php echo $this->Html->charset(); ?>
        <title>
            <?php echo $siteDescription ?>:
            <?php echo $this->fetch('title'); ?>
        </title>
        <?php
//      start: MAIN CSS
        echo $this->Html->css('../assets/plugins/bootstrap/css/bootstrap.min');        
        echo $this->Html->css('../assets/plugins/font-awesome/css/font-awesome.min');        
        echo $this->Html->css('../assets/fonts/style');        
        echo $this->Html->css('../assets/css/main');
        echo $this->Html->css('../assets/css/main-responsive');        
        echo $this->Html->css('../assets/plugins/iCheck/skins/all');        
        echo $this->Html->css('../assets/plugins/bootstrap-colorpalette/css/bootstrap-colorpalette');        
        echo $this->Html->css('../assets/plugins/perfect-scrollbar/src/perfect-scrollbar');
        echo $this->Html->css('../assets/css/theme_light');
        
//        echo $this->Html->script('https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min');
        echo $this->Html->script('jquery.min');
        echo $this->Html->script('../assets/plugins/jquery-ui/jquery-ui-1.10.2.custom.min');
        echo $this->Html->script('../assets/plugins/bootstrap/js/bootstrap.min');
        echo $this->Html->script('../assets/plugins/blockUI/jquery.blockUI');
        echo $this->Html->script('../assets/plugins/iCheck/jquery.icheck.min');
        echo $this->Html->script('../assets/plugins/perfect-scrollbar/src/jquery.mousewheel');
        echo $this->Html->script('../assets/plugins/perfect-scrollbar/src/perfect-scrollbar');
        echo $this->Html->script('../assets/plugins/less/less-1.5.0.min');
        echo $this->Html->script('../assets/plugins/jquery-cookie/jquery.cookie');
        echo $this->Html->script('../assets/plugins/bootstrap-colorpalette/js/bootstrap-colorpalette');
        echo $this->Html->script('../assets/js/main');
        
        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
        ?>
    </head>
    <body class="error-full-page">
	<?php //echo $this->Session->flash(); ?>
	<?php echo $this->fetch('content'); ?>
        
 <script>
    jQuery(document).ready(function () {
        Main.init();
    });
</script>
    </body>
</html>
