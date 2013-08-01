<?php

// -----------------------------------------------------------
// Data Updates
// -----------------------------------------------------------

// Update all current admins to Uber Admins

$query_admin_users = "SELECT * FROM $users_db_table WHERE userLevel='1'";
$admin_users = mysql_query($query_admin_users, $brewing) or die(mysql_error());
$row_admin_users = mysql_fetch_assoc($admin_users);

do { 
	
	$updateSQL = sprintf("UPDATE $users_db_table SET 
						 userLevel='%s'
						 WHERE id='%s'", 
						 "0",
						 $row_admin_users['id']);
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
	
}  
while ($row_admin_users = mysql_fetch_assoc($admin_users));

$output .=  "<li>Updates to user's table completed.</li>";

// Underscores in the archiveSuffix were tripping up the archive functions
// Replacing all underscores with dashes "-"
$query_archive_name = sprintf("SELECT id,archiveSuffix FROM %s",$prefix."archive");
$archive_name = mysql_query($query_archive_name, $brewing) or die(mysql_error());
$row_archive_name = mysql_fetch_assoc($archive_name);

do {
	
	$archive_name = str_replace("_","",$row_archive_name['archiveSuffix']);
	
	
	// First, replicate the current archive db tables with the new name
	
	if (HOSTED) $tables_array = array($prefix."users_".$row_archive_name['archiveSuffix'], $prefix."brewer_".$row_archive_name['archiveSuffix'], $prefix."brewing_".$row_archive_name['archiveSuffix'], $prefix."judging_assignments_".$row_archive_name['archiveSuffix'], $prefix."judging_scores_".$row_archive_name['archiveSuffix'],$prefix."judging_tables_".$row_archive_name['archiveSuffix'], $prefix."judging_scores_bos_".$row_archive_name['archiveSuffix'],$prefix."style_types_".$row_archive_name['archiveSuffix']);
	else $tables_array = array($prefix."users_".$row_archive_name['archiveSuffix'], $prefix."brewer_".$row_archive_name['archiveSuffix'], $prefix."brewing_".$row_archive_name['archiveSuffix'], $prefix."judging_assignments_".$row_archive_name['archiveSuffix'], $prefix."judging_scores_".$row_archive_name['archiveSuffix'],$prefix."judging_tables_".$row_archive_name['archiveSuffix'], $prefix."judging_scores_bos_".$row_archive_name['archiveSuffix'],$prefix."style_types_".$row_archive_name['archiveSuffix'],$prefix."judging_flights_".$row_archive_name['archiveSuffix'], $prefix."special_best_data_".$row_archive_name['archiveSuffix'], $prefix."special_best_info_".$row_archive_name['archiveSuffix']);
	
	foreach ($tables_array as $table) {
		
		$updateSQL = "RENAME TABLE `".$table."` TO `". str_replace($row_archive_name['archiveSuffix'],"",$table).$archive_name."`;";
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
		
		//echo $updateSQL."<br>";
	}

	
	
	
	// Second, update the record in the "archive" table
	
	$updateSQL = sprintf("UPDATE ".$prefix."archive SET 
						 archiveSuffix='%s'
						 WHERE id='%s'", 
						 $archive_name,
						 $row_archive_name['id']);
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
	
	//echo $updateSQL."<br>";
	
} while ($row_archive_name = mysql_fetch_assoc($archive_name));

$output .= "<li>Updates to archive table completed.</li>";

$updateSQL = sprintf("UPDATE %s SET version='%s', version_date='%s' WHERE id='%s'",$prefix."system","1.3.0.0","2013-08-01","1");
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 

$output .= "<li>Updates to system table completed.</li>";

?>