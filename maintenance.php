<?php 
/**
 * Module:      maintenance.php 
 * Description: This page displays if the site is in maintenance mode.
 * 
 */
require('paths.php');
require(DB.'common.db.php');
if (NHC) $base_url = "";
else $base_url = "http://".$_SERVER['SERVER_NAME'].$sub_directory."/";
?>


<!DOCTYPE html>
<html lang="en">
  	<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $_SESSION['contestName']; ?> Organized By <?php echo $_SESSION['contestHost']; ?></title>
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <!--[if lt IE 9]>
          	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
        <!-- Load BCOE&M Custom CSS -->
        <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>css/default.css">
                	
        <!-- Load BCOE&M Custom JS -->
    	<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/bcoem_custom.js"></script>
	</head>
<body>
	<!-- MAIN NAV -->
	<div class="container hidden-print">
		<!-- Fixed navbar -->
        <nav class="navbar navbar-default navbar-fixed-top">
        	<div class="container">
            	<div class="navbar-header">
              		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bcoem-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
            	</div>
            	<div class="collapse navbar-collapse" id="bcoem-navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="<?php echo $base_url; ?>">Home</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <?php if (isset($_SESSION['loginUsername'])) { ?>
                         <li role="presentation" class="text-muted"><a href="#">Logged in as <?php echo $_SESSION['loginUsername']; ?></a></li>    
                        <?php } else { ?>
                        <li><a href="<?php echo $base_url; ?>index.php?section=login">Log In</a></li>
                        <?php } ?>
                    </ul>
              	</div><!--/.nav-collapse -->
          	</div>
        </nav>
    </div><!-- container -->   
    <!-- ./MAIN NAV -->
    
    
    
    <!-- Update Pages (Fluid Layout) -->
    <div class="container">
    
    	<div class="page-header">
        	<h1>Maintenance</h1>
        </div>
        
        <p class="lead">The <?php echo $_SESSION['contestName']; ?> site administrator has taken the site offline. It is currently undergoing maintenance.</p>
    	<p>Please check back shortly.</p>
    
    </div><!-- ./container-fluid -->    
    <!-- ./Update Pages -->
    
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