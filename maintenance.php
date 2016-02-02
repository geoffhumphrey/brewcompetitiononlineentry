<?php 
/**
 * Module:      maintenance.php 
 * Description: This page displays if the site is in maintenance mode.
 * 
 */
require('paths.php');
require(CONFIG.'bootstrap.php');
if (NHC) $base_url = "";
else $base_url = "http://".$_SERVER['SERVER_NAME'].$sub_directory."/";
?>


<!DOCTYPE html>
<html lang="en">
  	<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $_SESSION['contestName']; ?> Organized By <?php echo $_SESSION['contestHost']." &gt; Error 400"; ?></title>
        
    <!-- Load jQuery / http://jquery.com/ -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	
    <!-- Load Bootstrap / http://www.getbootsrap.com -->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <!-- Load Font Awesome / https://fortawesome.github.io/Font-Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    
    <!-- Load BCOE&M Custom Theme CSS - Contains Bootstrap overrides and custom classes -->
    <link rel="stylesheet" type="text/css" href="<?php echo $theme; ?>" />
	
	<!-- Load BCOE&M Custom JS -->
    <script src="<?php echo $base_url; ?>js_includes/bcoem_custom.min.js"></script>
  </head>
<body>

	<!-- MAIN NAV -->
	<div class="container hidden-print">    
        <!-- Fixed navbar -->
        <div class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#"><?php echo $_SESSION['contestName']; ?></a>
                </div>
            <div class="collapse navbar-collapse" id="bcoem-navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <?php if (isset($_SESSION['loginUsername'])) { ?>
                    <li role="presentation" class="text-muted"><a href="#" class="disabled">Logged in as <?php echo $_SESSION['loginUsername']; ?></a></li>    
                    <?php } else { ?>
                    <li><a class="disabled">Site Offline</a></li>
                    <?php } ?>
                </ul>
            </div>
            </div><!--/.nav-collapse -->
        </div>
    </div><!-- container -->   
    <!-- ./MAIN NAV -->
    
    <!-- Container -->
    <div class="jumbotron">
    	<div class="container">
        	<h1>Maintenance</h1>
            <p class="lead">The <?php echo $_SESSION['contestName']; ?> site administrator has taken the site offline. It is currently undergoing maintenance.</p>
        </div>
    </div>
    <div class="container">
    	<p>Please check back shortly.</p>
    </div><!-- ./container-fluid -->    
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