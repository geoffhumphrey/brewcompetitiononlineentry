<?php 
require('paths.php');
require(INCLUDES.'url_variables.inc.php');
require(INCLUDES.'db_tables.inc.php');
require(INCLUDES.'authentication_nav.inc.php');  session_start(); 
require(INCLUDES.'headers.inc.php');
require(INCLUDES.'functions.inc.php'); 
mysql_select_db($database, $brewing);
//require(DB.'archive.db.php'); 


$query_contest_info = sprintf("SELECT * FROM %s WHERE id=1", $prefix."contest_info");
$contest_info = mysql_query($query_contest_info, $brewing) or die(mysql_error());
$row_contest_info = mysql_fetch_assoc($contest_info);
$totalRows_contest_info = mysql_num_rows($contest_info); 

$query_prefs = sprintf("SELECT * FROM %s WHERE id=1", $prefix."preferences");
$prefs = mysql_query($query_prefs, $brewing) or die(mysql_error());
$row_prefs = mysql_fetch_assoc($prefs);
$totalRows_prefs = mysql_num_rows($prefs);

// Session specific queries
if (isset($_SESSION["loginUsername"]))  {
	$query_user = sprintf("SELECT * FROM %s WHERE user_name = '%s'", $prefix."users", $_SESSION["loginUsername"]);
	$user = mysql_query($query_user, $brewing) or die(mysql_error());
	$row_user = mysql_fetch_assoc($user);
	$totalRows_user = mysql_num_rows($user);

	$query_name = sprintf("SELECT * FROM %s WHERE uid='%s'", $prefix."brewer", $row_user['id']);
	$name = mysql_query($query_name, $brewing) or die(mysql_error());
	$row_name = mysql_fetch_assoc($name);
	$totalRows_name = mysql_num_rows($name);

	if (($go == "make_admin") || (($go == "participants") && ($action == "add"))) {
		$query_user_level = sprintf("SELECT * FROM %s WHERE user_name = '%s'", $prefix."users", $username);
		}
	elseif (($section == "brewer") && ($action == "edit")) { 
		$query_user_level = sprintf("SELECT * FROM %s WHERE user_name = '%s'", $prefix."users", $row_brewer['brewerEmail']);
		}
	else $query_user_level = sprintf("SELECT id from %s",$prefix."users");
	$user_level = mysql_query($query_user_level, $brewing) or die(mysql_error());
	$row_user_level = mysql_fetch_assoc($user_level);
	$totalRows_user_level = mysql_num_rows($user_level);
	
}

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

$current_version = "1.2.1.0"; // Change to db query variable after v1.2.1.0.
$section = "update";
$table_name = $prefix."system";
if (!check_setup($prefix."system",$database)) require(INCLUDES.'version.inc.php'); // used only for version 1.2.1.0; subsequent versions will utilize a db query.
else $version = $current_version;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $row_contest_info['contestName']; ?> Update to BCOE&amp;M <?php echo $current_version; ?></title>
<link href="css/default.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="container">
<div id="navigation">
	<div id="navigation-inner"><?php echo sessionAuthenticateNav(); ?></div>
</div>
<div id="content">
 	 <div id="content-inner">
     <div id="header">	
		<div id="header-inner"><h1>BCOE&amp;M <?php echo $current_version; ?> Database Update Script</h1></div>
  	</div>
     <?php
	 
	 if (($action == "default") && ($version != $current_version)) { ?><div class="error">BCOE&amp;M <?php echo $current_version; ?> Database Update Script must be run to update the database.</div><?php } ?>
<?php 
if (check_setup($prefix."preferences",$database)) {
	
	if ((isset($_SESSION["loginUsername"])) && ($row_user['userLevel'] == "1")) {
		
		if ($current_version != $version) {
			
			if ($action == "default") { ?>
			<h2>This script will update your BCOE&amp;M database from its current version, <?php echo $version; ?>, to the latest version, <?php echo $current_version; ?>.</h2>
			<p><span class="icon"><img src="images/exclamation.png" /></span>Before running this script, make sure that you have uploaded the necessary version <?php echo $current_version; ?> files to your installation's root folder on your webserver.</p>
			<p><span class="icon"><img src="images/cog.png" /></span><a href="update.php?action=update">Begin The Update Script</a></p>		
			<?php }
		
			if ($action == "update") {
				
				// Perform updates to the db based upon the current version
					$version = str_replace(".","",$version);
					if ($version < "113") {
						echo "
						<div class='error'>Your installed version is incompatible with this update script.</div>
						<p>Please update your database and files manually through version 1.1.2 to utilize the update feature.</p>
						";
					}
					if ($version == "113") {
						include (UPDATE.'1.1.4.0_update.php');
						include (UPDATE.'1.1.5.0_update.php');
						include (UPDATE.'1.1.6.0_update.php');
						include (UPDATE.'1.2.0.0_update.php');
						include (UPDATE.'1.2.0.3_update.php');
						include (UPDATE.'current_update.php');
					}
					
					if ($version == "114") {
						include (UPDATE.'1.1.5.0_update.php');
						include (UPDATE.'1.1.6.0_update.php');
						include (UPDATE.'1.2.0.0_update.php');
						include (UPDATE.'1.2.0.3_update.php');
						include (UPDATE.'current_update.php');
					}
					
					if ($version == "115") {
						include (UPDATE.'1.1.6.0_update.php');
						include (UPDATE.'1.2.0.0_update.php');
						include (UPDATE.'1.2.0.3_update.php');
						include (UPDATE.'current_update.php');
					}
					
					if (($version == "116") || ($version == "1161")) {
						include (UPDATE.'1.2.0.0_update.php');
						include (UPDATE.'1.2.0.3_update.php');
						include (UPDATE.'current_update.php');
					}
					
					if ($version >= "1200") {
						include (UPDATE.'1.2.0.3_update.php');
						include (UPDATE.'current_update.php');
					}
					
				if ($version >= "113") {
					
				echo "<p class='error' style='width:230px; margin-top:20px;'>Update to ".$current_version." Complete!</p>";	
				
				// -----------------------------------------------------------
				//  Finish and Clean Up
				// -----------------------------------------------------------
				
				echo "<ul>";
				echo "<li>Go to the <a href='index.php'>Home Page</a>.</li>";
				echo "<li>Go to the <a href='index.php?section=admin'>Admin Main Menu</a>.</li>";
				echo "</ul>";
				
				}
				
			} // end if ($action == "update")
			
		} // end compare versions
		
		// if current version is same as installed version...
		else {
			echo "<div class='info'>The installed version (".$version.") is the same as the version for upgrade. No updates are necessary.</div>";
			echo "<ul>";
			echo "<li>Go to the <a href='index.php'>Home Page</a>.</li>";
			echo "<li>Go to the <a href='index.php?section=admin'>Admin Main Menu</a>.</li>";
			echo "</ul>";
		}
	} // end check of user level
	
	// if user is not logged in or a admin...
	else {
		echo "<div class='info'>Only website administrators are able to access and run this update script.</div>";
		if (!isset($_SESSION["loginUsername"])) {
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

?>
  </div>
</div>
</div>
<div id="footer">
	<div id="footer-inner"><a href="http://www.brewcompetition.com" target="_blank">BCOE&amp;M</a> Version <?php echo $current_version; ?> &copy;<?php  echo "2009-".date('Y'); ?> by <a href="http://www.zkdigital.com" target="_blank">zkdigital.com</a>.</div>
</div>
</body>
</html>
