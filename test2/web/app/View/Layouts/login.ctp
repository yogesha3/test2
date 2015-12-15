<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$siteDescription = __d('B2B', 'Welcome to FoxHopr Admin Panel');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html lang="en" class="no-js">

    <!-- start: HEAD -->
    <head>
<link rel="icon" type="image/x-icon" href="<?php echo $this->Html->url('/img/favicon.ico');?>">
        <?php echo $this->Html->charset(); ?>
        <title>
            <?php echo $siteDescription ; ?>
        </title>

        <!-- start: MAIN CSS -->
        <?php
        echo $this->Html->charset('utf-8');
        echo $this->Html->meta('viewport', 'width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0');
        echo $this->Html->css('../assets/plugins/bootstrap/css/bootstrap.min');
        echo $this->Html->css('../assets/plugins/font-awesome/css/font-awesome.min');
        echo $this->Html->css('../assets/fonts/style');
        echo $this->Html->css('../assets/css/main');
        echo $this->Html->css('../css/developer');
        echo $this->Html->css('../assets/css/main-responsive');
        echo $this->Html->css('../assets/plugins/iCheck/skins/all');

        echo $this->Html->script('jquery.min');
        echo $this->Html->script('../assets/plugins/jquery-ui/jquery-ui-1.10.2.custom.min');
        echo $this->Html->script('../assets/plugins/bootstrap/js/bootstrap.min');
        echo $this->Html->script('../assets/plugins/jquery-validation/dist/jquery.validate.min.js');
        echo $this->Html->script('../assets/js/login');

        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
        if(!empty($includePageJs)){
            foreach ($includePageJs as $jsFile){
                echo $this->Html->script('../developer/js/'.$jsFile);
            }
        }
        
        ?>
        <!--<link rel="stylesheet" href="assets/plugins/font-awesome/css/font-awesome-ie7.min.css">-->
    </head>
    <!-- end: HEAD -->
    <!-- start: BODY -->
    <body class="login example2">
        <div class="main-login col-sm-4 col-sm-offset-4">


            <div class="logo">FOXHOPR ADMINISTRATOR</div>
           
            <?php echo $this->Session->flash(); ?>
            <?php
              echo $this->fetch('content');
            ?>
        <div class="copyright">
                <?php echo date('Y')?> &copy; FoxHopr
            </div>
            <!-- end: COPYRIGHT -->
        </div>
        <!-- start: MAIN JAVASCRIPTS -->
     

        <!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
        <script>
            jQuery(document).ready(function() {
//                Main.init();
                Login.init();
            });
        </script>
    </body>
    <!-- end: BODY -->
</html>
