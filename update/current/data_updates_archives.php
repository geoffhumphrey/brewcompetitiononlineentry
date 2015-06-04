<?php
// !!!!!! NOT NEEDED for 1.3.0.0 setup or upgrade !!!!!!

// -----------------------------------------------------------
// Data Updates: Archive Tables
//   Convert the data in archived brewer tables to be compatible
//   with 1.3.0.0 scripting
// -----------------------------------------------------------


$query_archive = "SELECT archiveSuffix FROM $archive_db_table";
$archive = mysql_query($query_archive, $brewing);
$row_archive = mysql_fetch_assoc($archive);
$totalRows_archive = mysql_num_rows($archive);

if ($totalRows_archive > 0) {
	
	do { $a[] = $row_archive['archiveSuffix']; } while ($row_archive = mysql_fetch_assoc($archive));
	
	foreach ($a as $suffix) {
	
		$updateSQL = sprintf("UPDATE ".$prefix."brewing_".$suffix." SET 
								 brewPaid='%s',
								 brewWinner='%s',
								 brewReceived='%s',
								 brewConfirmed='%s',
								 brewUpdated=%s
								 WHERE id='%s';",
								 $brewPaid,
								 $brewWinner,
								 $brewReceived,
								 "1",
								 "NOW( )",
								 $row_log['id']);
			mysql_select_db($database, $brewing);
			mysql_real_escape_string($updateSQL);
			$result = mysql_query($updateSQL, $brewing); 
			
	} // end foreach

} // end if ($totalRows_archive > 0) 



?>