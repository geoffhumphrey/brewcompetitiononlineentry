<?php
require('paths.php');
require(CONFIG.'bootstrap.php');
$section = "400";
$go = "error_page";

$container_main = "container";
$nav_container = "navbar-default";
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $_SESSION['contestName']; ?> Organized By <?php echo $_SESSION['contestHost']." &gt; Error 400"; ?></title>

    <?php

    if (CDN) include (INCLUDES.'load_cdn_libraries.inc.php');
    else include (INCLUDES.'load_local_libraries.inc.php');

    ?>

    <!-- Load BCOE&M Custom Theme CSS - Contains Bootstrap overrides and custom classes -->
    <link rel="stylesheet" type="text/css" href="<?php echo $theme; ?>" />

    <!-- Load BCOE&M Custom JS -->
    <script src="<?php echo $base_url; ?>js_includes/bcoem_custom.min.js"></script>

  </head>
	<body>
    <!-- MAIN NAV -->
	<div class="container hidden-print">
		<?php include (SECTIONS.'nav.sec.php'); ?>
	</div><!-- container -->
    <!-- ./MAIN NAV -->

    <!-- ALERTS -->
    <div class="container bcoem-warning-container">
    	<div class="alert alert-danger"><span class="fa fa-exclamation-circle"></span> <strong>Invalid request.</strong> Don't worry, we still want you around!</div>

    </div><!-- ./container -->
    <!-- ./ALERTS -->

    <!-- Public Pages (Fixed Layout with Sidebar) -->
    <div class="container">
    	<div class="row">
    		<div class="col col-lg-9 col-md-8 col-sm-12 col-xs-12">
            <div class="page-header">
        		<h1>400 Error</h1>
        	</div>
        	<p class="lead">Unfortunately, there was a problem.</p>
            <p class="lead"><small>Please use the main navigation above to get where you want to go.</small></p>
            <p>Cheers!<br>&ndash; The <?php echo $_SESSION['contestName']; ?> Site Server</p>
            </div><!-- ./left column -->
            <div class="sidebar col col-lg-3 col-md-4 col-sm-12 col-xs-12">
            	<?php include (SECTIONS.'sidebar.sec.php'); ?>
            </div><!-- ./sidebar -->
        </div><!-- ./row -->
    	<!-- ./Public Pages -->
    </div><!-- ./container -->
    <!-- ./Public Pages -->

    <!-- Mods Bottom -->
    <div class="container">

    </div>
    <!-- ./Mods Bottom -->

    <!-- Footer -->
    <footer class="footer hidden-xs hidden-sm hidden-md">
    	<nav class="navbar navbar-default navbar-fixed-bottom">
            <div class="container text-center">
                <p class="navbar-text col-md-12 col-sm-12 col-xs-12 text-muted small"><?php include (SECTIONS.'footer.sec.php'); ?></p>
            </div>
    	</nav>
    </footer><!-- ./footer -->
	<!-- ./ Footer -->
</body>
</html>