<?php
/**
 * Module:      maintenance.php
 * Description: This page displays if the site is in maintenance mode.
 *
 */
require_once ('paths.php');
require_once (LANG.'language.lang.php');
?>

<!DOCTYPE html>
<html lang="en">
  	<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $maintenance_text_001; ?></title>

    <!-- Load jQuery / http://jquery.com/ -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

    <!-- Load Bootstrap / http://www.getbootsrap.com -->
    <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Load Font Awesome / https://fortawesome.github.io/Font-Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/v4-shims.min.css" integrity="sha512-NfhLGuxy6G12XHj7/bvm0RC3jmR25RdpImn8P19aIMmN5pndO0fvIg78ihN2WIJtVRs+AYVrnYF4AipVikGPLg==" crossorigin="anonymous" />

    <!-- Load BCOE&M Custom Theme CSS - Contains Bootstrap overrides and custom classes -->
    <link rel="stylesheet" type="text/css" href="css/common.min.css">
    <link rel="stylesheet" type="text/css" href="css/bruxellensis.min.css" />
    
  </head>
<body>
	<!-- MAIN NAV -->
	<div class="container hidden-print">
        <!-- Fixed navbar -->
        <div class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <p class="navbar-text">Site Offline</p>
                </div>
            </div><!--/.nav-collapse -->
        </div>
    </div><!-- container -->
    <!-- ./MAIN NAV -->
    <!-- Container -->
    <div class="jumbotron">
    	<div class="container">
        	<h1><?php echo $label_maintenance; ?></h1>
            <p class="lead"><?php echo $maintenance_text_000; ?></p>
        </div>
    </div>
    <div class="container">
    	<p><?php echo $maintenance_text_001; ?></p>
    </div><!-- ./container -->
    <!-- ./Container -->
    <!-- Footer -->
    <footer class="footer">
    	<nav class="navbar navbar-default navbar-fixed-bottom">
            <div class="container text-center">
                <p class="navbar-text col-md-12 col-sm-12 col-xs-12 text-muted small"><?php include (SECTIONS.'footer.sec.php'); ?></p>
            </div>
    	</nav>
    </footer><!-- ./footer -->
	<!-- ./ Footer -->
</body>
</html>