<?php 
echo "<h2>Performing Database Updates for Version ".$current_version.". Please stand by.</h2>";
			
// Perform updates for the current version
// Current version: current

// Only for the current version (1.2.1.0): check if 'system' db table exists
// If so, check to see what what version is there.
// If current version, do not perform updates

$query_exists = "SELECT id FROM system";
$result	= mysql_query($query_exists);
if (!$result) {
	
	// THE INCLUDES MUST BE IN THIS ORDER! DO NOT CHANGE!!
	
	include ('current/create_tables.php');
	include ('current/alter_tables.php');
	// include ('current/data_updates_brewer.php');
	// include ('current/data_updates_styles.php');
	include ('current/data_updates_brewing.php');
	include ('current/data_updates_archives.php');
	
}
else echo "<ul><li>No updates necessary.</li></ul>";

?>