<?php

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

//$cakeDescription = 'CakePHP: the rapid development php framework';

$cakeDescription = '';
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $title;?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap Docs -->
        <link href="http://getbootstrap.com/docs-assets/css/docs.css" rel="stylesheet" media="screen">
        <!-- Included CSS files-->
        <?php echo $this->Html->css('bootstrap.min.css');?>
        <?php echo $this->Html->css('bootstrap-theme.min.css');?>
        <?php echo $this->Html->css('font-awesome.min.css');?>
        <?php echo $this->Html->css('cake-style.css') ;?>                  
        <?php echo $this->Html->css('bootstrap-admin-theme.css') ;?>
        <?php echo $this->Html->css('bootstrap-admin-theme-change-size.css');?>       
        <?php echo $this->Html->css('bootstrap-datetimepicker.min.css');?>
        <?php echo $this->Html->css('highcharts.css');?>
        <!-- Included JS files -->        
        <?php echo $this->Html->script('jquery');?>
        <?php echo $this->Html->script('bootstrap.min');?>   
        <?php echo $this->Html->script('moment.min');?> 
        <?php echo $this->Html->script('moment-timezone');?>
        <?php echo $this->Html->script('bootstrap-datetimepicker.min');?>
        <?php echo $this->Html->script('highcharts');?>
        <script src="http://code.highcharts.com/highcharts-more.js"></script>

    </head>
    <body>
       <?php echo $this->element('header');?>
        <div class="container">
        <div class="row">         
       <?php echo $this->fetch('content');?>
        </div>
        </div>
        <?php echo $this->element('footer');?>      
    </body>
</html>
