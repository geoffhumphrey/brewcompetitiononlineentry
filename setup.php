<?php
ob_start();
require('paths.php');
require(INCLUDES.'url_variables.inc.php');
require(LANG.'language.lang.php');
require(LIB.'common.lib.php');
require(LIB.'help.lib.php');
require(LIB.'update.lib.php');
require(DB.'setup.db.php');
require(INCLUDES.'db_tables.inc.php');
require(LANG.'language.lang.php');
require(INCLUDES.'constants.inc.php');
require(INCLUDES.'headers.inc.php');

if ($section == "step0") {

	$query_character_check = "SHOW VARIABLES LIKE 'character_set_database'";
	$character_check = mysqli_query($connection,$query_character_check) or die (mysqli_error($connection));
	$row_character_check = mysqli_fetch_assoc($character_check);

	// If not usf8mb4, convert DB and all tables
	if ($row_character_check['Value'] != "utf8mb4") {

		$sql = sprintf("ALTER DATABASE `%s` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;",$database);
		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));

	}

}

if (($section == "step2") || ($section == "step3")) require(INCLUDES.'constants.inc.php');

// Set default timezone for setup steps before configuration of installation default timezone
$timezone_raw = "0";

if (check_setup($prefix."preferences",$database)) {

	$query_prefs_tz = sprintf("SELECT prefsTimeZone FROM %s WHERE id='1'", $prefix."preferences");
	$prefs_tz = mysqli_query($connection,$query_prefs_tz) or die (mysqli_error($connection));
	$row_prefs_tz = mysqli_fetch_assoc($prefs_tz);
	$totalRows_prefs_tz = mysqli_num_rows($prefs_tz);

	if ($totalRows_prefs_tz > 0) {
		$timezone_raw = $row_prefs_tz['prefsTimeZone'];
	}

}

$timezone_prefs = get_timezone($timezone_raw);
date_default_timezone_set($timezone_prefs);
$tz = date_default_timezone_get();

// Check for Daylight Savings Time (DST) - if true, add one hour to the offset
$bool = date("I");
if ($bool == 1) $timezone_offset = number_format(($timezone_raw + 1.000),0);
else $timezone_offset = number_format($timezone_raw,0);

$setup_alerts = "";
$setup_body = "";

if ($msg == 1) $setup_alerts .= "<div class=\"alert alert-danger\"><span class=\"fa fa-lg fa-exclamation-circle\"></span> <strong>Setup Has Not Been Completed.</strong> Continue setting up your BCOE&amp;M installation.</div>";

if ($setup_free_access == FALSE) {

	$setup_alerts .= "<div class=\"alert alert-danger\"><span class=\"fa fa-lg fa-exclamation-circle\"></span> <strong>Setup Cannot Run.</strong> The variable called &#36;setup_free_access is set to FALSE.</div>";
	$setup_body .= "<p>The &#36;setup_free_access variable is in the config.php file, which is located in the &ldquo;site&rdquo; folder on your server.</p>";
	$setup_body .= "<p>For the install and setup scripts to run, <strong>the &#36;setup_free_access variable must be set to TRUE</strong>. Server access is required to change the config.php file.</p>";
	$setup_body .= "<p>Once the installation has finished, you should change the &#36;setup_free_access variable back to FALSE for security reasons.</p>";
}

else {
	if ($section != "step0") require(DB.'common.db.php');
	require(INCLUDES.'version.inc.php');
	if ((!table_exists($prefix."system")) && ($section == "step0"))	include (SETUP.'install_db.setup.php');
	$setup_body .= $output;
}

?>
<!DOCTYPE html>
<html lang="en">
  	<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Setup BCOE&amp;M <?php echo $current_version; ?></title>

		<!-- Load jQuery / http://jquery.com/ -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

        <!-- Load Bootstrap / http://www.getbootsrap.com -->
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <!-- Load DataTables / https://www.datatables.net -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.10/css/dataTables.bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/plug-ins/1.10.10/integration/font-awesome/dataTables.fontAwesome.css" />
        <script type="text/javascript" src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/1.10.10/js/dataTables.bootstrap.min.js"></script>

        <!-- Load Fancybox / http://www.fancyapps.com -->
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen" />
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.pack.js"></script>

        <!-- Load TinyMCE -->
        <script src="https://cdn.tinymce.com/4/tinymce.min.js"></script>

         <!-- Load Bootstrap DateTime Picker / http://eonasdan.github.io/bootstrap-datetimepicker/ -->
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" />
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.1/moment-with-locales.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>

        <!-- Load Bootstrap Form Validator / http://1000hz.github.io/bootstrap-validator -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.9.0/validator.min.js"></script>

        <!-- Load Bootstrap-Select / http://silviomoreto.github.io/bootstrap-select -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.9.3/css/bootstrap-select.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.9.3/js/bootstrap-select.min.js"></script>
        
        <!-- Load jQuery Password Strength Meter for Twitter Bootstrap / https://github.com/ablanco/jquery.pwstrength.bootstrap -->
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pwstrength-bootstrap/2.1.0/pwstrength-bootstrap.min.js"></script>

        <!-- Load Font Awesome / https://fortawesome.github.io/Font-Awesome -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

        <!-- Load BCOE&M Custom CSS -->
        <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>css/default.min.css">

        <!-- Load BCOE&M Custom JS -->
        <script src="<?php echo $base_url; ?>js_includes/bcoem_custom.min.js"></script>

	</head>
	<body>
	<!-- MAIN NAV -->
	<div class="container-fluid hidden-print">
		<!-- Fixed navbar -->
        <nav class="navbar navbar-inverse navbar-fixed-top">
        	<div class="container-fluid">
            	<div class="navbar-header">
              		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bcoem-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
					<a class="navbar-brand" href="http://www.brewcompetition.com">BCOE&amp;M</a>
            	</div>
          	</div>
        </nav>
    </div><!-- container -->
    <!-- ./MAIN NAV -->
    <!-- ALERTS -->
    <div class="container-fluid bcoem-warning-container">
    	<?php echo $setup_alerts; ?>
    </div><!-- ./container -->

    <!-- Setup Pages (Fluid Layout) -->
    <div class="container-fluid">
        <!-- ./ALERTS -->
    	<?php if ($msg == 99) { ?>
   		<div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
        	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        	<span class="fa fa-lg fa-exclamation-circle"></span> <strong>There was an error in processing your input.</strong> Please re-enter the information.
		</div>
    	<?php } ?>
    	<?php if ($msg == 100) { ?>
   		<div class="alert alert-danger alert-dismissible hidden-print fade in" role="alert">
        	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        	<span class="fa fa-lg fa-exclamation-circle"></span> <strong>Set Up has not been completed.</strong> For your installation to function properly, set up must be completed.
        </div>
    	<?php } ?>
    	<div class="row">
        	<div class="col col-lg-9 col-md-12 col-sm-12 col-xs-12">
            <div class="page-header">
				<h1>BCOE&amp;M <?php echo $current_version." ".$header_output; ?></h1>
			</div>
			<?php

            echo $setup_body;

            if ($setup_free_access == TRUE) {

				if (table_exists($prefix."system")) {

					$query_system = sprintf("SELECT setup FROM %s", $prefix."system");
					$system = mysqli_query($connection,$query_system) or die (mysqli_error($connection));
					$row_system = mysqli_fetch_assoc($system);

					if (($row_system['setup'] == 0) && ($section != "step0")) {
						if ($section == "step1") 	include (SETUP.'admin_user.setup.php');
						if ($section == "step2") 	include (SETUP.'admin_user_info.setup.php');
						if ($section == "step3") 	include (SETUP.'site_preferences.setup.php');
						if ($section == "step4") 	include (SETUP.'competition_info.setup.php');
						if ($section == "step5") 	include (SETUP.'judging_locations.setup.php');
						if ($section == "step6") 	include (SETUP.'drop-off.setup.php');
						if ($section == "step7") 	include (SETUP.'accepted_styles.setup.php');
						if ($section == "step8") 	include (SETUP.'judging_preferences.setup.php');
					}

				} // end if (table_exists($prefix."system"))

			}

			?>
            </div><!-- ./left column -->
            <div class="sidebar col col-lg-3 col-md-12 col-sm-12 col-xs-12">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h4 class="panel-title">Setup Status<span class="fa fa-lg fa-bar-chart text-primary pull-right"></span></h4>
					</div>
					<div class="panel-body">
						<div class="bcoem-sidebar-panel <?php echo $sidebar_status_0; ?>">
							<strong>Install DB Tables and Data</strong>
							<span class="<?php echo $sidebar_status_icon_0; ?> pull-right"></span>
						</div>
						<div class="bcoem-sidebar-panel <?php echo $sidebar_status_1; ?>">
							<strong>Create Admin User</strong>
							<span class="<?php echo $sidebar_status_icon_1; ?> pull-right"></span>
						</div>
						<div class="bcoem-sidebar-panel <?php echo $sidebar_status_2; ?>">
							<strong>Add Admin User Info</strong>
							<span class="<?php echo $sidebar_status_icon_2; ?> pull-right"></span>
						</div>
						<div class="bcoem-sidebar-panel <?php echo $sidebar_status_3; ?>">
							<strong>Set Website Preferences</strong>
							<span class="<?php echo $sidebar_status_icon_3; ?> pull-right"></span>
						</div>
						<div class="bcoem-sidebar-panel <?php echo $sidebar_status_4; ?>">
							<strong>Add Competition Info</strong>
							<span class="<?php echo $sidebar_status_icon_4; ?> pull-right"></span>
						</div>
						<div class="bcoem-sidebar-panel <?php echo $sidebar_status_5; ?>">
							<strong>Add Judging Locations</strong>
							<span class="<?php echo $sidebar_status_icon_5; ?> pull-right"></span>
						</div>
						<div class="bcoem-sidebar-panel <?php echo $sidebar_status_6; ?>">
							<strong>Add Drop-off Locations</strong>
							<span class="<?php echo $sidebar_status_icon_6; ?> pull-right"></span>
						</div>
						<div class="bcoem-sidebar-panel <?php echo $sidebar_status_7; ?>">
							<strong>Designate Accepted Styles</strong>
							<span class="<?php echo $sidebar_status_icon_7; ?> pull-right"></span>
						</div>
						<div class="bcoem-sidebar-panel <?php echo $sidebar_status_8; ?>">
							<strong>Set Judging Preferences</strong>
							<span class="<?php echo $sidebar_status_icon_8; ?> pull-right"></span>
						</div>
					</div><!-- ./sidebar -->
			</div><!-- ./row -->
	</div><!-- ./container-fluid -->
	<!-- ./Container -->
	<!-- Footer -->
    <footer class="footer hidden-xs hidden-sm hidden-md">
    	<nav class="navbar navbar-inverse navbar-fixed-bottom">
            <div class="container-fluid text-center">
                <p class="navbar-text col-md-12 col-sm-12 col-xs-12 text-muted small"><?php include (SECTIONS.'footer.sec.php'); ?></p>
            </div>
    	</nav>
    </footer><!-- ./footer -->
	<!-- ./ Footer -->
	</body>
</html>