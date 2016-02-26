<?php
// !!!!!! NOT NEEDED for 2.0.0.0 setup or upgrade !!!!!!

// -----------------------------------------------------------
// Data Updates: Archive Tables
//   Convert the data in archived brewer tables to be compatible
//   with 1.3.0.0+ scripting
// -----------------------------------------------------------


$query_archive = "SELECT archiveSuffix FROM $archive_db_table";
$archive = mysqli_query($connection,$query_archive) or die (mysqli_error($connection));
$row_archive = mysqli_fetch_assoc($archive);
$totalRows_archive = mysqli_num_rows($archive);

if ($totalRows_archive > 0) {
	
	do { $a[] = $row_archive['archiveSuffix']; } while ($row_archive = mysqli_fetch_assoc($archive));
	
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
			mysqli_select_db($connection,$database);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			
	} // end foreach

} // end if ($totalRows_archive > 0) 
?>