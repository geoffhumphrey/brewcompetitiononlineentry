<?php 
require('paths.php');
require(INCLUDES.'url_variables.inc.php');
require(INCLUDES.'db_tables.inc.php');
require(INCLUDES.'authentication_nav.inc.php');  session_start(); 
require(INCLUDES.'version.inc.php'); // used only for version 1.2.1.0; subsequent versions will utilize a db query.
require(INCLUDES.'headers.inc.php');
$section = "update";
$current_version = "1.2.1.0"; // Change to db query variable after v1.2.1.0.

require(INCLUDES.'functions.inc.php'); 
require(DB.'common.db.php');
require(DB.'archive.db.php'); 

$query_log = "SELECT * FROM $brewing_db_table";
$log = mysql_query($query_log, $brewing) or die(mysql_error());
$row_log = mysql_fetch_assoc($log);
$totalRows_log = mysql_num_rows($log); 

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
	<div id="navigation-inner"></div>
</div>
<div id="content">
 	 <div id="content-inner">
     <div id="header">	
		<div id="header-inner"><h1>BCOE&amp;M <?php echo $current_version; ?> Database Update Script</h1></div>
  	</div>
     <?php if ($action == "default") { ?><div class="error">BCOE&amp;M <?php echo $current_version; ?> Database Update Script must be run to update the database.</div><?php } ?>
<?php 
function table_exists($table_name) {
	require(CONFIG.'config.php');
	// taken from http://snippets.dzone.com/posts/show/3369
	if (mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$table_name."'"))) return TRUE;
	else return FALSE;
}

$table_name = $prefix."preferences";

if (table_exists($table_name)) {

	if ((isset($_SESSION["loginUsername"])) && ($row_user['userLevel'] == "1")) { ?>
		<?php if (($action == "default") && ($msg == "default")) { ?>
		<h2>This script will update your BCOE&amp;M database from its current version, <?php echo $version; ?>, to the latest version, <?php echo $current_version; ?>.</h2>
		<p><span class="icon"><img src="images/exclamation.png" /></span>Before running this script, make sure that you have uploaded the necessary version <?php echo $current_version; ?> files to your installation's root folder on your webserver.</p>
		<p><span class="icon"><img src="images/cog.png" /></span><a href="update.php?action=update">Begin The Update Script</a></p>
		<?php } ?>
		<?php if ($action == "update") {
			// Check the installed version against the current version
			
			if ($version != $current_version) {
			// Perform updates to the db based upon the current version
				if ($version < "1.1.3") echo "<div class='error'>Your installed version is incompatible with this update script.</div><p>Please update your database and files manually through version 1.1.2 to utilize the update feature.</p>";
				if ($version == "1.1.3") {
					include (UPDATE.'1.1.4.0_update.php');
					include (UPDATE.'1.1.5.0_update.php');
					include (UPDATE.'1.1.6.0_update.php');
					include (UPDATE.'1.2.0.0_update.php');
					include (UPDATE.'current_update.php');
				}
				
				if ($version == "1.1.4") {
					include (UPDATE.'1.1.5.0_update.php');
					include (UPDATE.'1.1.6.0_update.php');
					include (UPDATE.'1.2.0.0_update.php');
					include (UPDATE.'current_update.php');
				}
				
				if ($version == "1.1.5") {
					include (UPDATE.'1.1.6.0_update.php');
					include (UPDATE.'1.2.0.0_update.php');
					include (UPDATE.'current_update.php');
				}
				
				if (($version == "1.1.6") || ($version == "1.1.6.1")) {
					include (UPDATE.'1.2.0.0_update.php');
					include (UPDATE.'current_update.php');
				}
				
				if (($version == "1.2.0.0") || ($version == "1.2.0.1") || ($version == "1.2.0.2")) {
					include (UPDATE.'current_update.php');
				}
	
				if (($version == "1.2.0.3") || ($version == "1.2.0.4"))  {
					include (UPDATE.'current_update.php');
					//exit;
					//echo "CURRENT!!";
				} 
			
			/// Perform structure check ///
			
			
			echo "<p class='error' style='width:230px; margin-top:20px;'>Update to ".$current_version." Complete!</p>";	
			
			} else echo "<div class='info'>Your database is already up to date. There is no need to run the update script. Nice!</div>"; 
			
			// -----------------------------------------------------------
			//  Finish and Clean Up
			// -----------------------------------------------------------
			
			
			echo "<ul>";
			echo "<li>Go to the <a href='index.php'>Home Page</a>.</li>";
			echo "<li>Go to the <a href='index.php?section=admin'>Admin Main Menu</a>.</li>";
			echo "</ul>";
			
		}
	}
	
	else { 
		
		if ($go == "db_update_required") echo "<div class='error'>A database update is required for this site to function properly.</div>";
		
		echo "<div class='info'>Only website administrators are able to access and run the update script.</div><p>If you are an administrator of this site, log in and try again.</p>";
		if (!isset($_SESSION["loginUsername"])) include (SECTIONS.'login.sec.php');
	}

} // end check if preferences exist. If not,

else {
	echo "<div class='error'>It looks like one or more tables in the database called $datbase are either missing or not setup correctly.</div>";
	if ($prefix != "") echo "
	<h3>Prefix Defined</h3>
	<p>You have indicated in the config.php file that a prefix of $prefix should be prepended to each table name. Is this correct? If not, make the required changes to the config.php file and try again.</p>
	<p>If the prefix is correct, you might want to run the <a href='update.php?action=repair'>database repair utility</a> to make sure the correct prefix is being used.</p>
	";
	echo "<p>Please run the <a href='setup.php'>install and setup utility</a> to install the correct database tables.</p>";
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