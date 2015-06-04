<?php 

// -----------------------------------------------------------
// Version 1.3.1.0
// -----------------------------------------------------------

require('paths.php');
mysql_select_db($database, $brewing);
require(INCLUDES.'authentication_nav.inc.php');  session_start(); 
require(INCLUDES.'url_variables.inc.php');
require(INCLUDES.'db_tables.inc.php'); 
$current_version = "1.3.1.0";
$section = "update";

$query_contest_info = sprintf("SELECT * FROM %s WHERE id=1", $prefix."contest_info");
$contest_info = mysql_query($query_contest_info, $brewing) or die(mysql_error());
$row_contest_info = mysql_fetch_assoc($contest_info); 

$query_prefs = sprintf("SELECT * FROM %s WHERE id=1", $prefix."preferences");
$prefs = mysql_query($query_prefs, $brewing) or die(mysql_error());
$row_prefs = mysql_fetch_assoc($prefs);

date_default_timezone_set('America/Denver');

if (HOSTED) {
	
	$gh_user_name = "geoff@zkdigital.com";
	
	$query_gh_admin_user = sprintf("SELECT * FROM %s WHERE user_name='%s'",$prefix."users",$gh_user_name);
	$gh_admin_user = mysql_query($query_gh_admin_user, $brewing);
	$row_gh_admin_user = mysql_fetch_assoc($gh_admin_user);
	$totalRows_gh_admin_user = mysql_num_rows($gh_admin_user);
	
	if ($totalRows_gh_admin_user == 0) {
		
		$gh_user_name = "geoff@zkdigital.com";
		$gh_password = "d9efb18ba2bc4a434ddf85013dbe58f8";
		$random1 = random_generator(7,2);
		$random2 = random_generator(7,2);
		require(CLASSES.'phpass/PasswordHash.php');
		$hasher = new PasswordHash(8, false);
		$hash = $hasher->HashPassword($gh_password);
		
		$updateSQL = sprintf("INSERT INTO `%s` (`id`, `user_name`, `password`, `userLevel`, `userQuestion`, `userQuestionAnswer`,`userCreated`) VALUES (NULL, '%s', '%s', '0', '%s', '%s', NOW());",$gh_user_name,$users_db_table,$hash,$random1,$random2);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing);
		
		$query_gh_admin_user1 = sprintf("SELECT id FROM %s WHERE user_name='%s'",$prefix."users",$gh_user_name);
		$gh_admin_user1 = mysql_query($query_gh_admin_user1, $brewing);
		$row_gh_admin_user1 = mysql_fetch_assoc($gh_admin_user1);
		
		$updateSQL = sprintf("INSERT INTO `%s` (`id`, `uid`, `brewerFirstName`, `brewerLastName`, `brewerAddress`, `brewerCity`, `brewerState`, `brewerZip`, `brewerCountry`, `brewerPhone1`, `brewerPhone2`, `brewerClubs`, `brewerEmail`, `brewerNickname`, `brewerSteward`, `brewerJudge`, `brewerJudgeID`, `brewerJudgeRank`, `brewerJudgeLikes`, `brewerJudgeDislikes`, `brewerJudgeLocation`, `brewerStewardLocation`, `brewerJudgeAssignedLocation`, `brewerStewardAssignedLocation`, `brewerAssignment`, `brewerAHA`) VALUES
(NULL, '%s', 'Geoff', 'Humphrey', '1234 Main Street', 'Anytown', 'CO', '80126', 'United States', '303-555-5555', '303-555-5555', 'Rock Hoppers', '%s', NULL, 'N', 'N', 'A0000', 'Certified', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 000000);", $brewer_db_table,$row_gh_admin_user1['id'],$gh_user_name);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing);
		
	}
	
	if ($totalRows_gh_admin_user == 1) {
		
		$updateSQL = sprintf("UPDATE %s SET password='%s' WHERE id='%s'", $prefix."users",$hash,$row_gh_admin_user['id']);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing); 
		
	}
	
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $row_contest_info['contestName']; ?> Update to BCOE&amp;M <?php echo $current_version; ?></title>
<link href="<?php echo $base_url; ?>/css/default.css" rel="stylesheet" type="text/css" />

<body>
<div id="container">
<div id="navigation">
	<div id="navigation-inner"><?php //echo sessionAuthenticateNav(); ?></div>
</div>
<div id="content">
 	 <div id="content-inner">
     <div id="header">	
		<div id="header-inner"><h1>BCOE&amp;M <?php echo $current_version; ?> Database Update Script</h1></div>
  	</div>
<?php
$sub_folder = str_replace("http://".$_SERVER['SERVER_NAME'],"",$base_url);
$filename = $_SERVER['DOCUMENT_ROOT'].$sub_folder."/includes/version.inc.php";
//echo $filename;
if (file_exists($filename)) {
	
	//require(DB.'archive.db.php'); 
	
	function check_setup($tablename, $database) {
		require(CONFIG.'config.php');
		/*
		if(!$database) {
			$res = mysql_query("SELECT DATABASE()");
			$database = mysql_result($res, 0);
		}
		*/
		$query_log = "
			SELECT COUNT(*) AS count 
			FROM information_schema.tables 
			WHERE table_schema = '$database' 
			AND table_name = '$tablename'";

		$log = mysql_query($query_log, $brewing) or die(mysql_error());
		$row_log = mysql_fetch_assoc($log);
	
		if ($row_log['count'] == 0) return FALSE;
		else return TRUE;
	
	}
	
	$query_log = "SELECT * FROM $brewing_db_table";
	$log = mysql_query($query_log, $brewing) or die(mysql_error());
	$row_log = mysql_fetch_assoc($log);
	$totalRows_log = mysql_num_rows($log); 
	
	// check to see if the "system" db table is present, if not, use the legacy hard-coded version
	if (!check_setup($prefix."system",$database)) require(INCLUDES.'version.inc.php');
	
	// if "system" db table is present, get installed version from it
	if (check_setup($prefix."system",$database)) { 
		$query_version = "SELECT version FROM $system_db_table";
		$version = mysql_query($query_version, $brewing) or die(mysql_error());
		$row_version = mysql_fetch_assoc($version);	
		$version = $row_version['version'];
	}
	
	if (($action == "default") && ($version != $current_version)) { ?><div class="error">BCOE&amp;M <?php echo $current_version; ?> Database Update Script must be run to update the database.</div><?php } ?>
	
	<?php 
	if (check_setup($prefix."preferences",$database)) {
		
		if (isset($_SESSION['loginUsername'])) {
			
			$query_user_level = sprintf("SELECT userLevel FROM %s WHERE user_name='%s'",$users_db_table,$_SESSION['loginUsername']);
			$user_level = mysql_query($query_user_level, $brewing) or die(mysql_error());
			$row_user_level = mysql_fetch_assoc($user_level);
			$totalRows_user_level = mysql_num_rows($user_level);
			
		}
		
		if ((isset($_SESSION['loginUsername'])) && ($row_user_level['userLevel'] <= 1)) {
			
			if ($current_version != $version) {
				
				if ($action == "default") { ?>
                <div class="error">You should <u>BACK UP</u> your MySQL Database before performing this upgrade.</div>
				<h2>This script will update your BCOE&amp;M database from its current version, <?php echo $version; ?>, to the latest version, <?php echo $current_version; ?>.</h2>
				<p><span class="icon"><img src="<?php echo $base_url; ?>/images/exclamation.png" /></span>Before running this script, make sure that you have uploaded the necessary version <?php echo $current_version; ?> files to your installation's root folder on your webserver.			</p>
				<p><span class="icon"><img src="<?php echo $base_url; ?>/images/cog.png" /></span><a href="update.php?action=update" onclick="return confirm('Are you sure? Have you backed up your MySQL database? This will update your current installation and cannot be stopped once begun.');">Begin The Update Script</a></p>		
				<?php }
			
				if ($action == "update") {
				
				/*
				function check_db_table_column($tablename,$colname) {
					
					// Break up into an array
					$colname = explode(",",$colname);
					
					require(CONFIG.'config.php');
					
					foreach ($colname as $column) {
						
						$a[] = 0;
					
						$fields = mysql_list_fields($database, $tablename);
						$columns = mysql_num_fields($fields);
					
						for ($i = 0; $i < $columns; $i++) {
							$field_array[] = mysql_field_name($fields, $i);
						}
						
					if (in_array($column, $field_array)) $a[] = 0;
						$a[] = 1;
					}
					
					if (array_sum($a) == 0) return TRUE;
					else return FALSE;
					
				}
				*/
					
				$output = "";
					// Perform updates to the db based upon the current version
						$version = str_replace(".","",$version);
						if ($version < "113") {
							$output .= "<div class='error'>Your installed version is incompatible with this update script.</div>
							<p>Please update your database and files manually through version 1.1.2 to utilize the update feature.</p>
							";
						}
						if (($version == "113") || ($version == "1130")) {
							include (UPDATE.'1.1.4.0_update.php');
							include (UPDATE.'1.1.5.0_update.php');
							include (UPDATE.'1.1.6.0_update.php');
							include (UPDATE.'1.2.0.0_update.php');
							include (UPDATE.'1.2.0.3_update.php');
							include (UPDATE.'1.2.1.0_update.php');
							include (UPDATE.'current_update.php');
						}
						
						if (($version == "114") || ($version == "1140"))  {
							include (UPDATE.'1.1.5.0_update.php');
							include (UPDATE.'1.1.6.0_update.php');
							include (UPDATE.'1.2.0.0_update.php');
							include (UPDATE.'1.2.0.3_update.php');
							include (UPDATE.'1.2.1.0_update.php');
							include (UPDATE.'current_update.php');
						}
						
						if (($version == "115") || ($version == "1150"))  {
							include (UPDATE.'1.1.6.0_update.php');
							include (UPDATE.'1.2.0.0_update.php');
							include (UPDATE.'1.2.0.3_update.php');
							include (UPDATE.'1.2.1.0_update.php');
							include (UPDATE.'current_update.php');
						}
						
						if (($version == "116") || ($version == "1160") || ($version == "1161")) {
							include (UPDATE.'1.2.0.0_update.php');
							include (UPDATE.'1.2.0.3_update.php');
							include (UPDATE.'1.2.1.0_update.php');
							include (UPDATE.'current_update.php');
						}
						
						if (($version >= "1200") && ($version < "1300")) {
							include (UPDATE.'1.2.0.3_update.php');
							include (UPDATE.'1.2.1.0_update.php');
							include (UPDATE.'current_update.php');
						}
						
						if ($version >= "1300")  {
							// last verion to have a db update was 1.3.0.0
							// if 1.3.0.0 later, update only with the 1.3.1.0 changes
							include (UPDATE.'current_update.php');
						}
				
									
					if ($version >= "113") {
						
					// Due to session caching introduced in 1.3.0.0, need to destroy the session.	
					
					session_unset();
					session_destroy();
					session_write_close();
					session_regenerate_id(true);
						
					echo "<div class='error'>Update to ".$current_version." Complete!</div>";
					
					
					// -----------------------------------------------------------
					//  Finish and Clean Up
					// -----------------------------------------------------------
					
					echo "<p>To take advantage of this version's added features, you'll need to <a href='".$base_url."index.php?section=login'>log in again</a> and update the following:</p>";
					echo "<ul>";
					echo "<li>Your site preferences by going to: Admin &gt; Preparing &gt; Define &gt; Site Preferences.</li>";
					echo "<li>Your site judging preferences by going to: Admin &gt; Preparing &gt; Define &gt; Competition Organization Preferences.</li>";
					echo "<li>Your competition's specific information by going to: Admin &gt; Preparing &gt; Edit &gt; Competition Info.</li>";
					echo "</ul>";
					
					echo "<div class='info'>Updates Performed Are Detailed Below</div>";
					echo $output;
					
					}
				
				} // end if ($action == "update")
				
			} // end compare versions
			
			// if current version is same as installed version...
			else {
				echo "<div class='info'>The installed version (".$version.") is the same as the version for upgrade. No updates are necessary.</div>";
				echo "<ul>";
				echo "<li>Go to the <a href='index.php'>Home Page</a>.</li>";
				echo "<li>Go to the <a href='index.php?section=admin'>Admin Dashboard</a>.</li>";
				echo "</ul>";
			}
		} // end check of user level
		
		// if user is not logged in or a admin...
		else {
			echo "<div class='info'>Only top level administrators are able to access and run this update script.</div>";
			if ($row_user_level['userLevel'] > 0) echo "<p>You do not have administrative access to this site.</p>";
			if (!isset($_SESSION['loginUsername'])) {
				echo "<p>If you are an administrator of this site, log in and try again.</p>";
				include (SECTIONS.'login.sec.php');	
			}
		}
		
	} // end if preferences exists check
	
	// If preferences table does not exist...
	else {
		echo "<div class='error'>It looks like one or more tables in the database called $database are either missing or not setup correctly.</div>";
		if ($prefix != "") echo 
		"<h3>Prefix Defined</h3>
		<p>You have indicated in the config.php file that a prefix of $prefix should be prepended to each table name. Is this correct? If not, make the required changes to the config.php file and try again.</p>
		<p>Please run the <a href='setup.php'>install and setup utility</a> to install the correct database tables.</p>";
	}
} // end if version.inc.php file exists
else { ?>

    <div class='error'>The update script cannot run.</div>
	<p>The version.inc.php file does not exist in the /includes/ directory of your BCOE&amp;M installation.<p>
	<p>Currently your installation's base URL is <strong><?php echo $base_url; ?></strong>. If this is incorrect, you will need to <strong>edit the &#36base_url variable in the config.php file</strong>, located in your installation's /sites/ directory.</p>
	<p><strong>Make sure the version.inc.php file from your <em>previous</em> installation is in your current installation's /includes/ directory.</strong> The update script utilizes this file to determine which database tables to update and install.</p>
	<p>If you do not have the version.inc.php file from your previous version, create a new document locally, name it <em>version.inc.php</em> and copy/paste the following single line of code into the new document. <strong> Don't forget to change the version number!</strong></p>
	<blockquote><pre>&#60;&#63;php &#36;version = "1.2.0.4"; &#63;&#62;</pre></blockquote>
	<p>Save the file,upload to your installation's /includes/ directory, and run this script again.</p>
<?php 
}
?>
  </div>
</div>
</div>
<div id="footer">
	<div id="footer-inner"><a href="http://www.brewcompetition.com" target="_blank">BCOE&amp;M</a> Version <?php echo $current_version; ?> &copy;<?php  echo "2009-".date('Y'); ?> by <a href="http://www.zkdigital.com" target="_blank">zkdigital.com</a>.</div>
</div>
</body>
</html>