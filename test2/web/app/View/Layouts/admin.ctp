<?php

/**
 * this is admin layout page
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
            <?php if(strtolower($this->fetch('title'))=='cms'){ echo "Pages"; }else { echo $this->fetch('title');} ?>
        </title>
        <?php
//      start: MAIN CSS
        echo $this->Html->css('../assets/plugins/bootstrap/css/bootstrap.min');
        echo $this->Html->css('https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css');
        echo $this->Html->css('../assets/fonts/style');
        echo $this->Html->css('../assets/css/main');
        echo $this->Html->css('../assets/css/main-responsive');
        echo $this->Html->css('../assets/plugins/iCheck/skins/all');
        echo $this->Html->css('../assets/plugins/bootstrap-colorpalette/css/bootstrap-colorpalette');
        echo $this->Html->css('../assets/plugins/perfect-scrollbar/src/perfect-scrollbar');
        echo $this->Html->css('../assets/css/theme_light');
//        echo $this->Html->css('../assets/plugins/font-awesome/css/font-awesome-ie7.min');
        echo $this->Html->css('../assets/plugins/select2/select2');
        echo $this->Html->css('../assets/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch');
        echo $this->Html->css('../assets/plugins/bootstrap-modal/css/bootstrap-modal');
        echo $this->Html->css('../css/developer');
        echo $this->Html->css('../assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min');
        echo $this->Html->css('../assets/plugins/ladda-bootstrap/dist/ladda-themeless.min');

        echo $this->Html->script('jquery.min');
        //echo $this->Html->script('../assets/plugins/jquery-ui/jquery-ui-1.10.2.custom.min');
        echo $this->Html->script('http://code.jquery.com/ui/1.11.4/jquery-ui.js');
        echo $this->Html->script('../assets/plugins/bootstrap/js/bootstrap.min');
        echo $this->Html->script('../assets/plugins/blockUI/jquery.blockUI');
        echo $this->Html->script('../assets/plugins/iCheck/jquery.icheck.min');
        echo $this->Html->script('../assets/plugins/perfect-scrollbar/src/jquery.mousewheel');
        echo $this->Html->script('../assets/plugins/perfect-scrollbar/src/perfect-scrollbar');
        echo $this->Html->script('../assets/plugins/less/less-1.5.0.min');
        echo $this->Html->script('../assets/plugins/jquery-cookie/jquery.cookie');
        echo $this->Html->script('../assets/plugins/bootstrap-colorpalette/js/bootstrap-colorpalette');
        echo $this->Html->script('../assets/js/main');

        //button rotator
        echo $this->Html->script('../assets/plugins/ladda-bootstrap/dist/spin.min.js');
        echo $this->Html->script('../assets/plugins/ladda-bootstrap/dist/ladda.min.js');


        echo $this->Html->script('../assets/plugins/jquery-validation/dist/jquery.validate.min.js');
        echo $this->Html->script('../assets/plugins/jquery-validation/dist/additional-methods.js');
        echo $this->Html->script('../assets/plugins/select2/select2.min');
        echo $this->Html->script('../assets/js/table-data');
        
        //ckeditor
        echo $this->Html->script('../assets/plugins/ckeditor/less-1.5.0.min');
        
        //modal js
        echo $this->Html->script('../assets/plugins/bootstrap-modal/js/bootstrap-modal');
        echo $this->html->script('../assets/plugins/bootstrap-modal/js/bootstrap-modalmanager');
        echo $this->Html->script('../assets/js/ui-modals');
        echo $this->Html->script('../assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.js');
        echo $this->Html->script('../assets/js/ellipse.js');

        
        //form-js
        echo $this->Html->script('jquery.form');
        echo $this->Html->script('../developer/js/admin_ajax.js');
        
        //page specific js
        if(!empty($includePageJs)){
            foreach ($includePageJs as $jsFile){
                echo $this->Html->script('../developer/js/'.$jsFile);
            }
        }

        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
        echo $this->Html->scriptBlock('var BASE_URL = '.$this->Js->object(Configure::read('SITE_URL')).';');
        echo $this->Html->scriptBlock('var PAGE = '.$this->Js->object($this->params['controller']).';');
        ?>
         <!--- For Country State -->
        <?php echo $this->Html->css('jQueryUI'); ?>
        <!--<link href="http://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">-->
<!--         <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script> -->
        <?php echo $this->Html->script('all.js');?>
<?php
echo $this->Html->script('Front/selectbox');
echo $this->Html->css('selectbox');
?>
        <!-- End-->
    </head>
    <body class="footer-fixed">
<?php
echo $this->element('header');
echo $this->element('admin_side_menu');
?>
        <!-- start: PAGE -->
        <div class="main-content">

            <div class="container">

                <!-- start: PAGE CONTENT -->
                <?php echo $this->fetch('content');?>
                <!-- end: PAGE CONTENT-->
            </div>
        </div>
        <!-- end: PAGE -->
<?php echo $this->element('footer');?>
<?php echo $this->Js->writeBuffer(); ?>
    </body>
</html>
