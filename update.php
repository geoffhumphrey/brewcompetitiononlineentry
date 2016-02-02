<?php 

// -----------------------------------------------------------
// Version 2.0.0.0
// -----------------------------------------------------------

$current_version = "2.0.0.0";
$current_version_display = "2.0.0";
require('paths.php');
mysql_select_db($database, $brewing);
require(INCLUDES.'authentication_nav.inc.php');  session_start(); 
require(INCLUDES.'url_variables.inc.php');
require(INCLUDES.'db_tables.inc.php'); 

$section = "update";

$query_contest_info = sprintf("SELECT * FROM %s WHERE id=1", $prefix."contest_info");
$contest_info = mysql_query($query_contest_info, $brewing) or die(mysql_error());
$row_contest_info = mysql_fetch_assoc($contest_info); 

$query_prefs = sprintf("SELECT * FROM %s WHERE id=1", $prefix."preferences");
$prefs = mysql_query($query_prefs, $brewing) or die(mysql_error());
$row_prefs = mysql_fetch_assoc($prefs);

date_default_timezone_set('America/Denver');

function check_update($column_name, $table_name) {
	
	require(CONFIG.'config.php');	
	mysql_select_db($database, $brewing);
	
	$query_log = sprintf("SHOW COLUMNS FROM `%s` LIKE '%s'",$table_name,$column_name);
	$log = mysql_query($query_log, $brewing) or die(mysql_error());
	$row_log_exists = mysql_num_rows($log);

    if ($row_log_exists) return TRUE;
	else return FALSE;

}

if (HOSTED) {
	
	if ($action == "default") {
		
		require(LIB.'common.lib.php');
		
		$gh_user_name = "geoff@zkdigital.com";	
		$gh_password = "d9efb18ba2bc4a434ddf85013dbe58f8";
		$random1 = random_generator(7,2);
		$random2 = random_generator(7,2);
		require(CLASSES.'phpass/PasswordHash.php');
		$hasher = new PasswordHash(8, false);
		$hash = $hasher->HashPassword($gh_password);
		
		$query_gh_admin_user = sprintf("SELECT * FROM %s WHERE user_name='%s'",$prefix."users",$gh_user_name);
		$gh_admin_user = mysql_query($query_gh_admin_user, $brewing);
		$row_gh_admin_user = mysql_fetch_assoc($gh_admin_user);
		$totalRows_gh_admin_user = mysql_num_rows($gh_admin_user);
		
		if ($totalRows_gh_admin_user == 0) {
			
			$updateSQL = sprintf("INSERT INTO `%s` (`id`, `user_name`, `password`, `userLevel`, `userQuestion`, `userQuestionAnswer`,`userCreated`) VALUES (NULL, '%s', '%s', '0', '%s', '%s', NOW());",$users_db_table,$gh_user_name,$hash,$random1,$random2);
			mysql_real_escape_string($updateSQL);
			$result = mysql_query($updateSQL, $brewing);
					
			$query_gh_admin_user1 = sprintf("SELECT id FROM %s WHERE user_name='%s'",$prefix."users",$gh_user_name);
			$gh_admin_user1 = mysql_query($query_gh_admin_user1, $brewing);
			$row_gh_admin_user1 = mysql_fetch_assoc($gh_admin_user1);
			
			$updateSQL1 = sprintf("INSERT INTO `%s` (`id`, `uid`, `brewerFirstName`, `brewerLastName`, `brewerAddress`, `brewerCity`, `brewerState`, `brewerZip`, `brewerCountry`, `brewerPhone1`, `brewerPhone2`, `brewerClubs`, `brewerEmail`, `brewerNickname`, `brewerSteward`, `brewerJudge`, `brewerJudgeID`, `brewerJudgeRank`, `brewerJudgeLikes`, `brewerJudgeDislikes`, `brewerJudgeLocation`, `brewerStewardLocation`, `brewerJudgeExp`, `brewerJudgeNotes`, `brewerAssignment`, `brewerAHA`) VALUES
	(NULL, '%s', 'Geoff', 'Humphrey', '1234 Main Street', 'Anytown', 'CO', '80000', 'United States', '303-555-5555', '303-555-5555', 'Rock Hoppers', '%s', NULL, 'N', 'N', 'A0000', 'Certified', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '000000');", $brewer_db_table,$row_gh_admin_user1['id'],$gh_user_name);
			mysql_real_escape_string($updateSQL1);
			$result = mysql_query($updateSQL1, $brewing);
			
			
		}
		
		if ($totalRows_gh_admin_user == 1) {
			
			$updateSQL2 = sprintf("UPDATE %s SET password='%s', userQuestion='%s', userQuestionAnswer='%s', userLevel='%s' WHERE id='%s'", $prefix."users",$hash,$random1,$random2,$row_gh_admin_user['id'],"0");
			mysql_real_escape_string($updateSQL2);
			$result = mysql_query($updateSQL2, $brewing); 
			
		}
	
	}
	
}

$sub_folder = str_replace("http://".$_SERVER['SERVER_NAME'],"",$base_url);
$filename = $_SERVER['DOCUMENT_ROOT'].$sub_folder."/includes/version.inc.php";

$update_alerts = "";
$update_body = "";

/* ---- DEBUG ----
//echo $updateSQL."<br>";
//echo $updateSQL1."<br>";
//echo $updateSQL2."<br>";
//echo $filename;
*/

if (file_exists($filename)) {
	
	//require(DB.'archive.db.php'); 
	
	function check_setup($tablename, $database) {
		require(CONFIG.'config.php');
		
		$query_log = sprintf("SELECT COUNT(*) AS count FROM information_schema.tables WHERE table_schema = '%s' AND table_name = '%s'",$database, $tablename);
		$log = mysql_query($query_log, $brewing) or die(mysql_error());
		$row_log = mysql_fetch_assoc($log);
	
		if ($row_log['count'] == 0) return FALSE;
		else return TRUE;
	
	}
	
	$query_log = sprintf("SELECT * FROM %s", $brewing_db_table);
	$log = mysql_query($query_log, $brewing) or die(mysql_error());
	$row_log = mysql_fetch_assoc($log);
	$totalRows_log = mysql_num_rows($log); 
	
	// check to see if the "system" db table is present, if not, use the legacy hard-coded version
	if (!check_setup($prefix."system",$database)) require(INCLUDES.'version.inc.php');
	
	// if "system" db table is present, get installed version from it
	if (check_setup($prefix."system",$database)) { 
		$query_version = sprintf("SELECT version FROM %s",$system_db_table);
		$version = mysql_query($query_version, $brewing) or die(mysql_error());
		$row_version = mysql_fetch_assoc($version);	
		$version = $row_version['version'];
	}
	
	if (($action == "default") && ($version != $current_version)) $update_alerts .= "<div class=\"alert alert-info\"><span class=\"fa fa-info-circle\"></span> <strong>The BCOE&amp;M ".$current_version_display." Database Update Script must be run to update the database.</strong></div>";
	
	if (check_setup($prefix."preferences",$database)) {
		
		if (isset($_SESSION['loginUsername'])) {
			
			$query_user_level = sprintf("SELECT userLevel FROM %s WHERE user_name='%s'",$users_db_table,$_SESSION['loginUsername']);
			$user_level = mysql_query($query_user_level, $brewing) or die(mysql_error());
			$row_user_level = mysql_fetch_assoc($user_level);
			$totalRows_user_level = mysql_num_rows($user_level);
			
		}
		
		if ((isset($_SESSION['loginUsername'])) && ($row_user_level['userLevel'] <= 1)) {
			
			if ($current_version != $version) {
				
				if ($action == "default") { 
				$update_alerts .= "<div class=\"alert alert-danger\"><span class=\"fa fa-exlamation-circle text-danger\"></span> <strong>Before running this script</strong>, make sure that you have uploaded the necessary version ".$current_version_display." files to your installation's root folder on your webserver and <strong>BACKED UP</strong> your MySQL database.</div>";
                
				$update_body .= "<p class=\"lead\">This script will update your BCOE&amp;M database from its current version, ".$version.", to the latest version, ".$current_version_display.".</p>";
				
				$update_body .= "<div class=\"bcoem-admin-element-bottom\"><a class=\"btn btn-primary btn-lg btn-block\" href=\"update.php?action=update\" data-confirm=\"Are you sure? Have you backed up your MySQL database? This will update your current installation and cannot be stopped once begun.\"><span class=\"fa fa-cog\"></span> Begin The Update</a></div>";		
				}
			
				if ($action == "update") {
								
				$output = "";
					// Perform updates to the db based upon the current version
						$version = str_replace(".","",$version);
						if ($version < "113") {
							$update_alerts .= "<div class=\"alert alert-danger\"><span class=\"fa fa-exclamation-circle\"></span> Your installed version is incompatible with this update script.</div>";
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
							include (UPDATE.'current_update.php');
						}
						
						if (($version == "114") || ($version == "1140"))  {
							include (UPDATE.'1.1.5.0_update.php');
							include (UPDATE.'1.1.6.0_update.php');
							include (UPDATE.'1.2.0.0_update.php');
							include (UPDATE.'1.2.0.3_update.php');
							include (UPDATE.'1.2.1.0_update.php');
							include (UPDATE.'1.3.0.0_update.php');
							include (UPDATE.'current_update.php');
						}
						
						if (($version == "115") || ($version == "1150"))  {
							include (UPDATE.'1.1.6.0_update.php');
							include (UPDATE.'1.2.0.0_update.php');
							include (UPDATE.'1.2.0.3_update.php');
							include (UPDATE.'1.2.1.0_update.php');
							include (UPDATE.'1.3.0.0_update.php');
							include (UPDATE.'current_update.php');
						}
						
						if (($version == "116") || ($version == "1160") || ($version == "1161")) {
							include (UPDATE.'1.2.0.0_update.php');
							include (UPDATE.'1.2.0.3_update.php');
							include (UPDATE.'1.2.1.0_update.php');
							include (UPDATE.'1.3.0.0_update.php');
							include (UPDATE.'current_update.php');
						}
						
						if (($version >= "1200") && ($version < "1213")) {
							include (UPDATE.'1.2.0.3_update.php');
							include (UPDATE.'1.2.1.0_update.php');
							include (UPDATE.'1.3.0.0_update.php');
							include (UPDATE.'current_update.php');
						}
						
						if (($version >= "1213") && ($version < "1300")) {
							include (UPDATE.'1.3.0.0_update.php');
							include (UPDATE.'1.3.2.0_update.php');
							include (UPDATE.'current_update.php');
						}
						
						if (($version >= "1300") && ($version < "1320")) {
							include (UPDATE.'1.3.2.0_update.php');
							include (UPDATE.'current_update.php');
						}
						
						// Last version to have a db update was 1.3.2.0
						// If current version is 1.3.2.0, only perform the 2.0.0.0 update
						if ($version >= "1320")   {
							include (UPDATE.'current_update.php');
						}
				
									
					if ($version >= "113") {
						
					// Due to session caching introduced in 1.3.0.0, need to destroy the session.	
					
					session_unset();
					session_destroy();
					session_write_close();
					session_regenerate_id(true);
						
					$update_alerts .= "<div class=\"alert alert-success\"><span class=\"fa fa-check-circle\"></span> <strong>Update to ".$current_version_display." Complete!</strong></div>";
					
					// -----------------------------------------------------------
					//  Finish and Clean Up
					// -----------------------------------------------------------
					
					$update_body .= "<p>To take advantage of this version's added features, you'll need to <a href='".$base_url."index.php?section=login'>log in again</a> and update the following:</p>";
					$update_body .= "<ul>";
					$update_body .= "<li>Your site preferences.</li>";
					$update_body .= "<li>Your site judging preferences.</li>";
					$update_body .= "<li>Your competition&rsquo;s specific information.</li>";
					$update_body .= "</ul>";
					$update_body .= "<h3>Updates Performed Are Detailed Below</h3>";
					$update_body .= $output;
					
					}
				
				} // end if ($action == "update")
				
			} // end compare versions
			
			// if current version is same as installed version...
			else {
				$update_alerts .= "<div class=\"alert alert-info\"><span class=\"fa fa-info-circle\"></span> <strong>The installed version (".$version.") is the same as the version for upgrade.</strong> No updates are necessary.</div>";
				$update_body .= "<p class=\"lead\">Your installation is up to date!</p>";
				$update_body .= "<div class=\"btn-group\" role=\"group\">";
				$update_body .= "<a class=\"btn btn-default btn-lg\" href=\"".$base_url."\">Go to the Home Page</a>";
				$update_body .= "<a class=\"btn btn-default btn-lg\" href=\"".$base_url."index.php?section=admin\">Go to the Admin Dashboard</a>";
				$update_body .= "</div>";
			}
		} // end check of user level
		
		// if user is not logged in or a admin...
		else {
			$update_alerts .= "<div class=\"alert alert-danger\"><span class=\"fa fa-exclamation-circle\"></span> <strong>Only top level administrators are able to access and perform updates.</strong>";
			if ($row_user_level['userLevel'] > 0)  $update_alerts .= " You do not have administrative access to this site.";
			$update_alerts .= "</div>";
			if (!isset($_SESSION['loginUsername'])) {
				$update_body .= "<p class=\"lead\">If you are an administrator of this site, log in and try again.</p>";
			}
		}
		
	} // end if preferences exists check
	
	// If preferences table does not exist...
	else {
		$update_alerts .= "<div class=\"alert alert-danger\"><span class=\"fa fa-exclamation-circle\"></span> <strong>It looks like one or more tables in the database called $database are either missing or not setup correctly.</strong></div>";
		if ($prefix != "") $update_alerts .= "<h3>Prefix Defined</h3>";
		$update_body .= "<p>You have indicated in the config.php file that a prefix of $prefix should be prepended to each table name. Is this correct? If not, make the required changes to the config.php file and try again.</p>";
		$update_body .= "<p>Please run the <a href='setup.php'>install and setup utility</a> to install the correct database tables.</p>";
	}
} // end if version.inc.php file exists
else { 

    $update_alerts .= "<div class=\"alert alert-danger\"><span class=\"fa fa-exclamation-circle\"></span> <strong>The update script cannot run.</strong> The version.inc.php file does not exist in the /includes/ directory of your BCOE&amp;M installation.</div>";
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
        <title><?php echo $row_contest_info['contestName']; ?>: Update to BCOE&amp;M <?php echo $current_version_display; ?></title>
        <!-- Load jQuery / http://jquery.com/ -->
		<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
        
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
        <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>/css/default.min.css" />
        
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
    
    <!-- ALERTS -->
    <div class="container-fluid bcoem-warning-container">
    	<?php echo $update_alerts; ?>
    </div><!-- ./container --> 
    <!-- ./ALERTS -->
    
    <!-- Update Pages (Fluid Layout) -->
    <div class="container-fluid">
    
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