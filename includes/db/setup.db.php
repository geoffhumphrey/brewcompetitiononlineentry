<?php
mysql_select_db($database, $brewing);

// Check to see if initial setup has taken place
if (table_exists($prefix."system")) {
	
	$query_system = sprintf("SELECT setup FROM %s", $prefix."system");
	$system = mysql_query($query_system, $brewing) or die(mysql_error());
	$row_system = mysql_fetch_assoc($system);
	if ($row_system['setup'] == 1) header (sprintf("Location: %s",$base_url."index.php"));
	
}

if ($section == "step4") { 
	$query_prefs = sprintf("SELECT * FROM %s WHERE id=1", $prefix."preferences");
	$prefs = mysql_query($query_prefs, $brewing) or die(mysql_error());
	$row_prefs = mysql_fetch_assoc($prefs);
}
?>