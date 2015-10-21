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
	$query_version_check = sprintf("SELECT version FROM %s WHERE id='1'",$prefix."system");
	$version_check = mysql_query($query_version_check, $brewing) or die(mysql_error());
	$row_version_check = mysql_fetch_assoc($version_check);
		
	//echo $row_version['version'];
	
	if ($row_version_check['version'] != $current_version) { 
		$setup_success = FALSE;
		
		// Change version number in DB ONLY if there is no need to run the update scripts 
		if (!$db_update) {
			
			$updateSQL = sprintf("UPDATE %s SET version='%s', version_date='%s' WHERE id='%s'",$prefix."system","1.3.2.0","2015-10-31","1");
			mysql_select_db($database, $brewing);
			$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
			
			$setup_relocate = "Location: ".$base_url;
			
		}
		
		else $setup_relocate = "Location: ".$base_url."update.php";
	}

}
	

if (!$setup_success) {
	
	header ($setup_relocate);
	exit;
	
}

?>