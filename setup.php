<?php
ob_start();
require_once ('paths.php');
require_once (INCLUDES.'url_variables.inc.php');
require_once (INCLUDES.'styles.inc.php');
if (SINGLE) require_once(SSO.'sso.inc.php');
require_once (LIB.'common.lib.php');
require_once (LIB.'update.lib.php');
require_once (DB.'setup.db.php');
require_once (INCLUDES.'db_tables.inc.php');
require_once (LIB.'help.lib.php');

$ajax_url = $base_url."ajax/";
$js_url = $base_url."js_includes/";
$images_url = $base_url."images/";
$css_url = $base_url."css/";

$prefs_set = FALSE;
$jprefs_set = FALSE;

if ($section == "default") $section = "setup";

if (($section == "step4") || ($section == "step5") || ($section == "step6") || ($section == "step7")) {
	
	unset($_SESSION['prefs'.$prefix_session]);
	$query_prefs = sprintf("SELECT * FROM %s WHERE id='1'", $prefix."preferences");
	$prefs = mysqli_query($connection,$query_prefs) or die (mysqli_error($connection));
	$row_prefs = mysqli_fetch_assoc($prefs);
	$totalRows_prefs = mysqli_num_rows($prefs);

	if ($totalRows_prefs == 1) {
		$prefs_set = TRUE;
		foreach ($row_prefs as $key => $value) {
			if ($key != "id") $_SESSION[$key] = $value;
		}
	}

	$query_judging_prefs = sprintf("SELECT * FROM %s WHERE id='1'", $prefix."judging_preferences");
	$judging_prefs = mysqli_query($connection,$query_judging_prefs) or die (mysqli_error($connection));
	$row_judging_prefs = mysqli_fetch_assoc($judging_prefs);
	$totalRows_judging_prefs = mysqli_num_rows($judging_prefs);

	if ($totalRows_judging_prefs == 1) {
		$jprefs_set = TRUE;
		foreach ($row_judging_prefs as $key => $value) {
			if ($key != "id") $_SESSION[$key] = $value;
		}
	}

	// If preferences aren't set, redirect to preferences set up screen
	if (!$prefs_set) {
		unset($_SESSION['prefs'.$prefix_session]);
		header(sprintf("Location: %s", $base_url."setup.php?section=step3&msg=999"));
		exit();
	}

}

// Set language preferences in session variables
if (empty($_SESSION['prefsLang'.$prefix_session])) {

    // Default is US English. Users will choose language when defining preferences.
    $_SESSION['prefsLanguage'] = "en-US";

    // Check if variation used (demarked with a dash)
    if (strpos($_SESSION['prefsLanguage'], '-') !== FALSE) {
        $lang_folder = explode("-",$_SESSION['prefsLanguage']);
        $_SESSION['prefsLanguageFolder'] = strtolower($lang_folder[0]);
    }

    else $_SESSION['prefsLanguageFolder'] = strtolower($_SESSION['prefsLanguage']);

    $_SESSION['prefsLang'.$prefix_session] = "1";

}

//require_once (DB.'common.db.php');
require_once (INCLUDES.'constants.inc.php');
require_once (LANG.'language.lang.php');
require_once (INCLUDES.'headers.inc.php');
require_once (INCLUDES.'scrubber.inc.php');

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

if ($msg == 999) $setup_alerts .= "<div class=\"alert alert-danger\"><span class=\"fa fa-lg fa-exclamation-circle\"></span> <strong>Preferences Were Not Saved.</strong> There was an error is saving your preferences. Please check below and re-enter your installation preferences as necessary.</div>";

if ($setup_free_access == FALSE) {

	$setup_alerts .= "<div class=\"alert alert-danger\"><span class=\"fa fa-lg fa-exclamation-circle\"></span> <strong>Setup Cannot Run.</strong> The variable called &#36;setup_free_access is set to FALSE.</div>";
	$setup_body .= "<p>The &#36;setup_free_access variable is in the config.php file, which is located in the &ldquo;site&rdquo; folder on your server.</p>";
	$setup_body .= "<p>For the install and setup scripts to run, <strong>the &#36;setup_free_access variable must be set to TRUE</strong>. Server access is required to change the config.php file.</p>";
	$setup_body .= "<p>Once the installation has finished, you should change the &#36;setup_free_access variable back to FALSE for security reasons.</p>";
}


else {

	if ($section != "step0") {
		if (table_exists($prefix."bcoem_sys")) require (DB.'common.db.php');
	}
	
	require (INCLUDES.'version.inc.php');
	
	if ((!table_exists($prefix."bcoem_sys")) && ($section == "step0"))	include (SETUP.'install_db.setup.php');
	$setup_body .= $output;
	
}

$security_question = array($label_secret_01, $label_secret_05, $label_secret_06, $label_secret_07, $label_secret_08, $label_secret_09, $label_secret_10, $label_secret_11, $label_secret_12, $label_secret_13, $label_secret_14, $label_secret_15, $label_secret_16, $label_secret_17, $label_secret_18, $label_secret_19, $label_secret_20, $label_secret_21, $label_secret_22, $label_secret_23, $label_secret_25, $label_secret_26, $label_secret_27);

/**
 * Generate a CSRF token on every page load.
 * This will be used to prevent cross-site request forgeries
 * when processing form data.
 * First check for php 7 compatible random_bytes.
 * If not, use mcrypt_create_iv (deprecated in php 7.1 removed in 7.2)
 * If that's not available, default to openssl_random_pseudo_bytes.
 */

if (function_exists('random_bytes')) $_SESSION['token'] = bin2hex(random_bytes(32));
elseif (function_exists('mcrypt_create_iv')) $_SESSION['token'] = bin2hex(mcrypt_create_iv(32,MCRYPT_DEV_URANDOM));
else $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));

?>
<!DOCTYPE html>
<html lang="en">
  	<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Setup BCOE&amp;M <?php echo $current_version; ?></title>

		<?php
	    if (CDN) include (INCLUDES.'load_cdn_libraries.inc.php');
	    else include (INCLUDES.'load_local_libraries.inc.php');
	    ?>

        <!-- Load BCOE&M Custom CSS -->
        <link rel="stylesheet" type="text/css" href="<?php echo $css_url; ?>common.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $css_url; ?>default.min.css">

	</head>
	<body>
	<!-- LOADER -->
	<div id="loader-submit">
	    <div class="center">
	        <span class="fa fa-cog fa-spin fa-5x fa-fw"></span>
	        <p><strong><?php echo $label_working; ?>.<br><?php echo $output_text_030." ".$output_text_031; ?></strong></p>
	    </div>
	</div>
	<!-- ./LOADER -->
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
					<a class="navbar-brand" href="http://www.brewingcompetitions.com">BCOE&amp;M</a>
            	</div>
          	</div>
        </nav>
    </div><!-- container -->
    <!-- ./MAIN NAV -->
    <!-- ALERTS -->
    <div class="container-fluid bcoem-warning-container">
    	<?php echo $setup_alerts; ?>
        <?php if (DEBUG_SESSION_VARS) include (DEBUGGING.'session_vars.debug.php'); ?>
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

				if (table_exists($prefix."bcoem_sys")) {

					$query_system = sprintf("SELECT setup FROM %s", $prefix."bcoem_sys");
					$system = mysqli_query($connection,$query_system) or die (mysqli_error($connection));
					$row_system = mysqli_fetch_assoc($system);

					if (ENABLE_MARKDOWN) {
	                    include (CLASSES.'parsedown/Parsedown.php');
	                    $Parsedown = new Parsedown();
	                }

					if (($row_system['setup'] == 0) && ($section != "step0")) {
						if ($section == "step1") include (SETUP.'admin_user.setup.php');
						if ($section == "step2") include (SETUP.'admin_user_info.setup.php');
						if ($section == "step3") include (SETUP.'site_preferences.setup.php');
						if ($section == "step4") include (SETUP.'competition_info.setup.php');
						if ($section == "step5") include (SETUP.'judging_locations.setup.php');
						if ($section == "step6") include (SETUP.'drop-off.setup.php');
						if ($section == "step7") include (SETUP.'accepted_styles.setup.php');
						if ($section == "step8") include (SETUP.'judging_preferences.setup.php');
					}

				} // end if (table_exists($prefix."bcoem_sys"))

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
	<!-- Load BCOE&M Custom JS -->
	<script src="<?php echo $js_app_url; ?>"></script>
	</body>
</html>