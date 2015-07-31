<?php

function check_setup($tablename, $database) {
	
	require(CONFIG.'config.php');	
	mysql_select_db($database, $brewing);
	
	$query_log = "SELECT COUNT(*) AS count FROM information_schema.tables WHERE table_schema = '$database' AND table_name = '$tablename'";
	$log = mysql_query($query_log, $brewing) or die(mysql_error());
	$row_log = mysql_fetch_assoc($log);

    if ($row_log['count'] == 0) return FALSE;
	else return TRUE;

}

$setup_success = TRUE;

// The following line will need to change with future conversions
if ((!check_setup($prefix."mods",$database)) && (!check_setup($prefix."preferences",$database))) { 

	$setup_success = FALSE;
	$setup_relocate = "Location: ".$base_url."setup.php?section=step0";
	 
}
	
if ((!NHC) && (!check_setup($prefix."mods",$database)) && (check_setup($prefix."preferences",$database))) {
	
	$setup_success = FALSE;
	$setup_relocate = "Location: ".$base_url."update.php";
	
}
	
elseif (MAINT) { 

	$setup_success = FALSE;
	$setup_relocate = "Location: ".$base_url."maintenance.php";
	
}

if (check_setup($prefix."system",$database)) {
	
	mysql_select_db($database, $brewing);
	$query_version = sprintf("SELECT version FROM %s WHERE id='1'",$prefix."system");
	$version = mysql_query($query_version, $brewing) or die(mysql_error());
	$row_version = mysql_fetch_assoc($version);
		
	//echo $row_version['version'];
		
	if ($row_version['version'] != "1.3.1.0") { 
		$setup_success = FALSE;
		$setup_relocate = "Location: ".$base_url."update.php";
	}

}
	

if (!$setup_success) {
	
	header ($setup_relocate);
	exit;
	
}

?>