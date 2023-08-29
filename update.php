<?php
require_once ('paths.php');
require_once (INCLUDES.'url_variables.inc.php');
$section = "update";

if (SINGLE) require_once(SSO.'sso.inc.php');
require_once (LIB.'common.lib.php');
require_once (LIB.'update.lib.php');
require_once (INCLUDES.'db_tables.inc.php');
require_once (LIB.'help.lib.php');

// Check if bcoem_sys is there. If not, change.
if (table_exists($prefix."system")) {
	$query_sys = sprintf("RENAME TABLE `%s` TO `%s`",$prefix."system",$prefix."bcoem_sys");
	$sys = mysqli_query($connection,$query_sys) or die (mysqli_error($connection));
	$system_name_change = TRUE;
}

require_once (DB.'common.db.php');
require_once (INCLUDES.'constants.inc.php');
require_once (LANG.'language.lang.php');
require_once (INCLUDES.'headers.inc.php');
require_once (INCLUDES.'scrubber.inc.php');
if (HOSTED) require_once (LIB.'hosted.lib.php');

$query_system = sprintf("SELECT version FROM %s", $prefix."bcoem_sys");
$system_db_table = $prefix."bcoem_sys";
$system = mysqli_query($connection,$query_system) or die (mysqli_error($connection));
$row_system = mysqli_fetch_assoc($system);
$version = $row_system['version'];

// Set timezone globals for the site
$timezone_raw = "0";
$update_running = FALSE;

// Check whether prefs have been established
// If so, get time zone set by admin
if (check_setup($prefix."preferences",$database)) {

	$query_prefs_tz = sprintf("SELECT prefsTimeZone FROM %s WHERE id='1'", $prefix."preferences");
	$prefs_tz = mysqli_query($connection,$query_prefs_tz) or die (mysqli_error($connection));
	$row_prefs_tz = mysqli_fetch_assoc($prefs_tz);
	$totalRows_prefs_tz = mysqli_num_rows($prefs_tz);

	if ($totalRows_prefs_tz > 0) {
		$timezone_raw = $row_prefs_tz['prefsTimeZone'];
	}

}

// Establish time zone for all date-related functions
$timezone_prefs = get_timezone($timezone_raw);
date_default_timezone_set($timezone_prefs);
$tz = date_default_timezone_get();

// Check for Daylight Savings Time (DST) - if true, add one hour to the offset
$bool = date("I");
if ($bool == 1) $timezone_offset = number_format(($timezone_raw + 1.000),0);
else $timezone_offset = number_format($timezone_raw,0);

$filename = INCLUDES."version.inc.php";
$update_alerts = "";
$update_body = "";
$output = "";

if (file_exists($filename)) {

	// check to see if the "bcoem_sys" db table is present, if not, use the legacy hard-coded version
	if (!check_setup($system_db_table,$database)) require (INCLUDES.'version.inc.php');

	if (($action == "default") && ($version != $current_version)) $update_alerts .= "<div class=\"alert alert-info\"><span class=\"fa fa-lg fa-info-circle\"></span> <strong>The BCOE&amp;M ".$current_version_display." Database Update Script must be run to update the database.</strong></div>";

	if (check_setup($prefix."preferences",$database)) {

		if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {

			if (($current_version != $version) || (($current_version == $version) && ($version_date < $current_version_date))) {

				if ($action == "default") {
				$update_alerts .= "<div class=\"alert alert-danger\"><span class=\"fa fa-lg fa-exlamation-circle text-danger\"></span> <strong>Before running this script</strong>, make sure that you have uploaded <strong>ALL</strong> of the necessary version ".$current_version_display." files to your installation's root folder on your webserver and <strong>BACKED UP</strong> your database.</div>";

				$update_body .= "<p class=\"lead\">";
				$update_body .= "This script will update your BCOE&amp;M database from its current version, ";
				$update_body .= $version;
                if (($version == $current_version) && ($version_date < $current_version_date)) $update_body .= " (Build ".$row_version['version_date'].")";
				$update_body .= ", to the latest version, ";
				$update_body .= $current_version;
				if (($version == $current_version) && ($version_date < $current_version_date)) $update_body .= " (Build ".$current_version_date_display.")";
				$update_body .= ".</p>";

				$update_body .= "<p class=\"lead\"><small><strong class=\"text-danger\">Please note!</strong> This update contains a conversion script that affects each table in your database. Therefore, it may take a while to run. Please be patient!</small></p>";

				$update_body .= "<div class=\"bcoem-admin-element-bottom\"><button class=\"btn btn-primary btn-lg btn-block hide-loader update-begin\" data-toggle=\"modal\" data-target=\"#update-begin-confirm\"><span class=\"fa fa-cog fa-spin\"></span> Begin The Update</button></div>";

				$update_body .= "
					<div class=\"modal fade\" id=\"update-begin-confirm\" tabindex=\"-1\" role=\"dialog\">
					  <div class=\"modal-dialog\" role=\"document\">
					    <div class=\"modal-content\">
					      <div class=\"modal-header\">
					        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>
					        <h4 class=\"modal-title\">Please Confirm</h4>
					      </div>
					      <div class=\"modal-body\">
					        <p>Are you sure you want to start the update?</p>
					        <p>Before doing so, make sure that you have <a href=\"https://comodosslstore.com/resources/phpmyadmin-backup-database\" target=\"_blank\">created a backup</a> of up your database.</p>
					        <p>This function will update your current installation and cannot be stopped once begun.</p>
					        <p class=\"text-primary\"><strong>Please note that the update may take some time to complete, so patience is warranted!</strong></p>
					      </div>
					      <div class=\"modal-footer\">
					        <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
					        <a id=\"confirm-start-update\" class=\"btn btn-success\" href=\"update.php?action=update\">Yes, I'm Sure</a>
					      </div>
					    </div><!-- /.modal-content -->
					  </div><!-- /.modal-dialog -->
					</div><!-- /.modal -->
				";

				}

				if ($action == "update") {

					$update_running = TRUE;

					// Perform updates to the db based upon the current version
					$version = str_replace(".","",$version);

					if ($version == "200") $version = "2000";

					if ($version < "113") {
						$update_alerts .= "<div class=\"alert alert-danger\"><span class=\"fa fa-lg fa-exclamation-circle\"></span> Your installed version is incompatible with this update script.</div>";
						$output .= "<p>Please update your database and files manually through version 1.1.2.0 to utilize the update feature.</p>";
					}

					if (($version == "113") || ($version == "1130")) {
						include (UPDATE.'1.1.4.0_update.php');
						include (UPDATE.'1.1.5.0_update.php');
						include (UPDATE.'1.1.6.0_update.php');
						include (UPDATE.'1.2.0.0_update.php');
						include (UPDATE.'1.2.0.3_update.php');
						include (UPDATE.'1.2.1.0_update.php');
						include (UPDATE.'1.3.0.0_update.php');
						include (UPDATE.'1.3.2.0_update.php');
						include (UPDATE.'2.0.0.0_update.php');
						include (UPDATE.'2.1.0.0_update.php');
						include (UPDATE.'2.1.5.0_update.php');
						include (UPDATE.'2.1.8.0_update.php');
						include (UPDATE.'current_update.php');
					}

					if (($version == "114") || ($version == "1140"))  {
						include (UPDATE.'1.1.5.0_update.php');
						include (UPDATE.'1.1.6.0_update.php');
						include (UPDATE.'1.2.0.0_update.php');
						include (UPDATE.'1.2.0.3_update.php');
						include (UPDATE.'1.2.1.0_update.php');
						include (UPDATE.'1.3.0.0_update.php');
						include (UPDATE.'1.3.2.0_update.php');
						include (UPDATE.'2.0.0.0_update.php');
						include (UPDATE.'2.1.0.0_update.php');
						include (UPDATE.'2.1.5.0_update.php');
						include (UPDATE.'2.1.8.0_update.php');
						include (UPDATE.'current_update.php');
					}

					if (($version == "115") || ($version == "1150"))  {
						include (UPDATE.'1.1.6.0_update.php');
						include (UPDATE.'1.2.0.0_update.php');
						include (UPDATE.'1.2.0.3_update.php');
						include (UPDATE.'1.2.1.0_update.php');
						include (UPDATE.'1.3.0.0_update.php');
						include (UPDATE.'1.3.2.0_update.php');
						include (UPDATE.'2.0.0.0_update.php');
						include (UPDATE.'2.1.0.0_update.php');
						include (UPDATE.'2.1.5.0_update.php');
						include (UPDATE.'2.1.8.0_update.php');
						include (UPDATE.'current_update.php');
					}

					if (($version == "116") || ($version == "1160") || ($version == "1161")) {
						include (UPDATE.'1.2.0.0_update.php');
						include (UPDATE.'1.2.0.3_update.php');
						include (UPDATE.'1.2.1.0_update.php');
						include (UPDATE.'1.3.0.0_update.php');
						include (UPDATE.'1.3.2.0_update.php');
						include (UPDATE.'2.0.0.0_update.php');
						include (UPDATE.'2.1.0.0_update.php');
						include (UPDATE.'2.1.5.0_update.php');
						include (UPDATE.'2.1.8.0_update.php');
						include (UPDATE.'current_update.php');
					}

					if (($version >= "1200") && ($version < "1203")) {
						include (UPDATE.'1.2.0.3_update.php');
						include (UPDATE.'1.2.1.0_update.php');
						include (UPDATE.'1.3.0.0_update.php');
						include (UPDATE.'1.3.2.0_update.php');
						include (UPDATE.'2.0.0.0_update.php');
						include (UPDATE.'2.1.0.0_update.php');
						include (UPDATE.'2.1.5.0_update.php');
						include (UPDATE.'2.1.8.0_update.php');
						include (UPDATE.'current_update.php');
					}

					if (($version >= "1203") && ($version < "1210")) {

						include (UPDATE.'1.2.1.0_update.php');
						include (UPDATE.'1.3.0.0_update.php');
						include (UPDATE.'1.3.2.0_update.php');
						include (UPDATE.'2.0.0.0_update.php');
						include (UPDATE.'2.1.0.0_update.php');
						include (UPDATE.'2.1.5.0_update.php');
						include (UPDATE.'2.1.8.0_update.php');
						include (UPDATE.'current_update.php');
					}

					if (($version >= "1210") && ($version < "1300")) {
						include (UPDATE.'1.3.0.0_update.php');
						include (UPDATE.'1.3.2.0_update.php');
						include (UPDATE.'2.0.0.0_update.php');
						include (UPDATE.'2.1.0.0_update.php');
						include (UPDATE.'2.1.5.0_update.php');
						include (UPDATE.'2.1.8.0_update.php');
						include (UPDATE.'current_update.php');
					}

					if (($version >= "1300") && ($version < "1320")) {
						include (UPDATE.'1.3.2.0_update.php');
						include (UPDATE.'2.0.0.0_update.php');
						include (UPDATE.'2.1.0.0_update.php');
						include (UPDATE.'2.1.5.0_update.php');
						include (UPDATE.'2.1.8.0_update.php');
						include (UPDATE.'current_update.php');
					}


					if (($version >= "1320") && ($version < "2000"))  {
						include (UPDATE.'2.0.0.0_update.php');
						include (UPDATE.'2.1.0.0_update.php');
						include (UPDATE.'2.1.5.0_update.php');
						include (UPDATE.'2.1.8.0_update.php');
						include (UPDATE.'current_update.php');
					}


					if (($version >= "2000") && ($version < "2100"))  {
						include (UPDATE.'2.1.0.0_update.php');
						include (UPDATE.'2.1.5.0_update.php');
						include (UPDATE.'2.1.8.0_update.php');
						include (UPDATE.'current_update.php');
					}

					if (($version >= "2100") && ($version < "2150"))  {
						include (UPDATE.'2.1.5.0_update.php');
						include (UPDATE.'2.1.8.0_update.php');
						include (UPDATE.'current_update.php');
					}


					if (($version >= "2150") && ($version < "2180"))  {
						include (UPDATE.'2.1.8.0_update.php');
						include (UPDATE.'current_update.php');
					}

					if (($version >= "2180") && ($version < "21120"))  {
						include (UPDATE.'current_update.php');
					}

					if ($version >= "113") {

						if (session_status() == PHP_SESSION_NONE) {
							$_SESSION['update_complete'] = 1;
							if (isset($output_off_sched_update)) $_SESSION['update_summary'] = $output_errors.$output_off_sched_update;
							if ((isset($error_output)) && ($error_count == 1)) $_SESSION['update_errors'] = 1;
							else $_SESSION['update_errors'] = 0;
						}

						/*

						$redirect = $base_url;
						$redirect = prep_redirect_link($redirect);
						$redirect_go_to = sprintf("Location: %s", $redirect);
						header($redirect_go_to);
						*/

						$update_alerts .= "<div class=\"alert alert-success\"><span class=\"fa fa-lg fa-check-circle\"></span> <strong>Update to ".$current_version_display." Complete!</strong></div>";

						// -----------------------------------------------------------
						//  Finish and Clean Up
						// -----------------------------------------------------------

						$update_body .= "<p>To take advantage of this version's added features, please go to the Admin Dashboard and update the following:</p>";
						$update_body .= "<ul>";
						$update_body .= "<li>Your site preferences.</li>";
						$update_body .= "<li>Your site judging preferences.</li>";
						$update_body .= "<li>Your competition&rsquo;s specific information.</li>";
						$update_body .= "</ul>";
						$update_body .= "<a class=\"btn btn-primary btn-lg hide-loader\" role=\"button\" href='".$base_url."index.php?section=admin'>Admin Dashboard</a>";
						$update_body .= "<h3>Updates Performed Are Detailed Below</h3>";
						$update_body .= $output;

					}

				} // end if ($action == "update")

			} // end compare versions

			// if current version is same as installed version...
			else {
				$update_alerts .= "<div class=\"alert alert-info\"><span class=\"fa fa-lg fa-info-circle\"></span> No database updates are necessary for version ".$current_version_display.".</div>";
				$update_body .= "<p class=\"lead\">Your installation is up to date!</p>";
				$update_body .= "<div class=\"btn-group\" role=\"group\">";
				$update_body .= "<a class=\"btn btn-default btn-lg hide-loader\" href=\"".$base_url."\">Go to the Home Page</a>";
				$update_body .= "<a class=\"btn btn-default btn-lg hide-loader\" href=\"".$base_url."index.php?section=admin\">Go to the Admin Dashboard</a>";
				$update_body .= "</div>";
			}
		
		} // end check of user level

		// if user is not logged in or an admin...
		else {
			$update_alerts .= "<div class=\"alert alert-danger\"><span class=\"fa fa-lg fa-exclamation-circle\"></span> <strong>Only top level administrators are able to access and perform updates.</strong>";
			if ((isset($_SESSION['loginUsername'])) && ($row_user_level['userLevel'] > 0))  $update_alerts .= " You do not have administrative access to this site.";
			$update_alerts .= "</div>";
			if (!isset($_SESSION['loginUsername'])) {
				$update_body .= "<p class=\"lead\">If you are an administrator of this site, log in and try again.</p>";
			}
		}

	} // end if preferences exists check

	// If preferences table does not exist...
	else {
		$update_alerts .= "<div class=\"alert alert-danger\"><span class=\"fa fa-lg fa-exclamation-circle\"></span> <strong>It looks like one or more tables in the database called $database are either missing or not setup correctly.</strong></div>";
		if ($prefix != "") $update_alerts .= "<h3>Prefix Defined</h3>";
		$update_body .= "<p>You have indicated in the config.php file that a prefix of $prefix should be prepended to each table name. Is this correct? If not, make the required changes to the config.php file and try again.</p>";
		$update_body .= "<p>Please run the <a class='hide-loader' href='setup.php'>install and setup utility</a> to install the correct database tables.</p>";
	}

} // end if version.inc.php file exists

else {

    $update_alerts .= "<div class=\"alert alert-danger\"><span class=\"fa fa-lg fa-exclamation-circle\"></span> <strong>The update script cannot run.</strong> The version.inc.php file does not exist in the /includes/ directory of your BCOE&amp;M installation.</div>";
	$update_body .= "<p>Currently your installation's base URL is <strong><?php echo $base_url; ?></strong>. If this is incorrect, you will need to <strong>edit the &#36base_url variable in the config.php file</strong>, located in your installation's /sites/ directory.</p>";
	$update_body .= "<p><strong>Make sure the version.inc.php file from your <em>previous</em> installation is in your current installation's /includes/ directory.</strong> The update script utilizes this file to determine which database tables to update and install.</p>";
	$update_body .= "<p>If you do not have the version.inc.php file from your previous version, create a new document locally, name it <em>version.inc.php</em> and copy/paste the following single line of code into the new document. <strong> Don't forget to change the version number!</strong></p>";
	$update_body .= "<blockquote><pre>&#60;&#63;php &#36;version = \"1.2.0.4\"; &#63;&#62;</pre></blockquote>";
	$update_body .= "<p>Save the file, upload to your installation's /includes/ directory, and run this script again.</p>";
}

?>
<!DOCTYPE html>
<html lang="en">
  	<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Update to BCOE&amp;M <?php echo $current_version_display; ?></title>

        <!-- Load jQuery / http://jquery.com/ -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

        <!-- Load Bootstrap / http://www.getbootsrap.com -->
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- Load Fancybox / http://www.fancyapps.com -->
	    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen" />
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
	    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js"></script>
	    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.pack.js"></script>

	    <!-- Load TinyMCE / https://www.tinymce.com/ -->
		<script src="https://cdn.tinymce.com/4/tinymce.min.js"></script>
		<script src="<?php echo $base_url;?>js_includes/tinymce-init.min.js"></script>

		<!-- Load Bootstrap DateTime Picker / http://eonasdan.github.io/bootstrap-datetimepicker/ -->
	    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" />
	    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.1/moment-with-locales.min.js"></script>
	    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
	    <script src="<?php echo $base_url;?>js_includes/date-time.min.js"></script>

        <!-- Load Font Awesome / https://fortawesome.github.io/Font-Awesome -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

        <!-- Load BCOE&M Custom Theme CSS - Contains Bootstrap overrides and custom classes -->
        <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>css/common.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>css/default.min.css" />

        <!-- Load BCOE&M Custom JS -->
    	<script src="<?php echo $base_url; ?>js_includes/bcoem_custom.min.js"></script>

    	<script type="text/javascript">
    		$(document).ready(function() {
    			$("#confirm-start-update").click(function() {
    			    $('#loader-submit').show(0).delay(120000).hide(0);
    			});
    		});
    	</script>

	</head>
<body>
	<!-- LOADER -->
	<div id="loader-submit">
	    <div class="center">
	        <span class="fa fa-cog fa-spin fa-5x fa-fw"></span>
	        <p><strong>Working.<br>Please wait, update in progress.<br>Press ESC to hide this loader (update scripts will still be running in the background).</strong></p>
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
            	</div>
            	<div class="collapse navbar-collapse" id="bcoem-navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li><a class="hide-loader" href="<?php echo $base_url; ?>">Home</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <?php if (isset($_SESSION['loginUsername'])) { ?>
                         <li role="presentation" class="text-muted"><a href="#">Logged in as <?php echo $_SESSION['loginUsername']; ?></a></li>
                        <?php } else { ?>
                        <li><a class="hide-loader" href="<?php echo $base_url; ?>index.php?section=login">Log In</a></li>
                        <?php } ?>
                    </ul>
              	</div><!--/.nav-collapse -->
          	</div>
        </nav>
    </div><!-- container -->
    <!-- ./MAIN NAV -->

    <!-- ALERTS -->
    <div class="container-fluid bcoem-warning-container" style="margin-top: 50px;">
    	<?php echo $update_alerts; ?>
    </div><!-- ./container -->
    <!-- ./ALERTS -->

    <!-- Update Pages (Fluid Layout) -->
    <div class="container-fluid">

    	<?php if (DEBUG_SESSION_VARS) include (DEBUGGING.'session_vars.debug.php'); ?>
    	<div class="page-header">
        	<h1>BCOE&amp;M <?php echo $current_version_display; ?> Update</h1>
        </div>

        <?php echo $update_body; ?>

		<?php if (!isset($_SESSION['loginUsername'])) { ?>
        	<div class="bcoem-admin-element-bottom">
        	<?php include (SECTIONS.'login.sec.php'); ?>
			</div>
		<?php }	?>

    </div><!-- ./container-fluid -->
    <!-- ./Update Pages -->

    <!-- Footer -->
    <footer class="footer">
    	<nav class="navbar navbar-inverse navbar-fixed-bottom">
            <div class="container-fluid text-center">
                <p class="navbar-text col-md-12 col-sm-12 col-xs-12 text-muted small"><?php include (SECTIONS.'footer.sec.php'); ?></p>
            </div>
    	</nav>
    </footer><!-- ./footer -->
	<!-- ./ Footer -->

</body>
</html>