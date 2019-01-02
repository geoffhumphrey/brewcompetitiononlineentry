<?php
/**
 * Module:      maintenance.php
 * Description: This page displays if the site is in maintenance mode.
 *
 */
$section = "maintenance";
require_once ('paths.php');
require_once (CONFIG.'bootstrap.php');
require_once (INCLUDES.'url_variables.inc.php');
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);
require_once (LIB.'common.lib.php');
require_once (DB.'common.db.php');
require_once (INCLUDES.'constants.inc.php');
require_once (LANG.'language.lang.php');
?>


<!DOCTYPE html>
<html lang="en">
  	<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $_SESSION['contestName']; ?> Organized By <?php echo $_SESSION['contestHost']." &gt; Maintenance"; ?></title>

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
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

    <!-- Load BCOE&M Custom Theme CSS - Contains Bootstrap overrides and custom classes -->
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>css/common.min.css">
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
                    <p class="navbar-text"><?php echo $_SESSION['contestName']; ?></p>
                </div>
            <div class="collapse navbar-collapse" id="bcoem-navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <p class="navbar-text">
                    <?php if (isset($_SESSION['loginUsername'])) { ?>
                    <?php echo $label_logged_in." <span class=\"fa fa-caret-right\"></span> <span class=\"small\"><em>".$_SESSION['loginUsername']."</em></span>"; ?>
                    <?php } else { ?>
                    Site Offline</a></li>
                    <?php } ?>
                    </p>
                </ul>
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