<?php
mysqli_select_db($connection,$database);
// Check to see if initial setup has taken place
if (table_exists($prefix."system")) {
	
	$query_system = sprintf("SELECT setup FROM %s", $prefix."system");
	$system = mysqli_query($connection,$query_system) or die (mysqli_error($connection));
	$row_system = mysql_fetch_assoc($system);
	if ($row_system['setup'] == 1) header (sprintf("Location: %s",$base_url."index.php"));
	
}

if ($section == "step4") { 
	$query_prefs = sprintf("SELECT * FROM %s WHERE id=1", $prefix."preferences");
	$prefs = mysqli_query($connection,$query_prefs) or die (mysqli_error($connection));
	$row_prefs = mysqli_fetch_assoc($prefs);
}
?>