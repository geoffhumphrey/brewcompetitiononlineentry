<?php
// -----------------------------------------------------------
// !!!! NOT from 1.3.0.0 Release !!!!
// Programming must be done to change Y/N values to boolean
// -----------------------------------------------------------


// -----------------------------------------------------------
// Data Updates: Brewer Table
// -----------------------------------------------------------

mysqli_select_db($connection,$database);

$query_brewer = "SELECT * FROM $brewer_db_table";
$brewer = mysqli_query($query_brewer, $brewing);
$row_brewer = mysqli_fetch_assoc($brewer);

do { 
	if ($row_brewer['brewerJudge'] == "Y") $brewerJudge = "1"; else $brewerJudge = "0";
	if ($row_brewer['brewerSteward'] == "Y") $brewerSteward = "1"; else $brewerSteward = "0";
	if ($row_brewer['brewerJudgeBOS'] == "Y") $brewerJudgeBOS = "1"; else $brewerJudgeBOS = "0";
	if ($row_brewer['brewerDiscount'] == "Y") $brewerDiscount = "1"; else $brewerDiscount = "0";
	
	$updateSQL = sprintf("UPDATE $brewer_db_table SET 
						 brewerJudge='%s',
						 brewerSteward='%s',
						 brewerJudgeBOS='%s', 
						 brewerDiscount='%s',
						 WHERE id='%s'", 
						 $brewerJudge,
						 $brewerSteward,
						 $brewerJudgeBOS, 
						 $brewerDiscount, 
						 $row_brewer['id']);
	
	
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

}  
while ($row_brewer = mysqli_fetch_assoc($brewer));

// Change rows to tinyint type

$updateSQL = "ALTER TABLE  $brewer_db_table CHANGE  `brewerJudgeBOS`  `brewerJudgeBOS` TINYINT(1) NULL DEFAULT NULL COMMENT '1 for true; 0 for false'";
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$updateSQL = "ALTER TABLE  $brewer_db_table CHANGE  `brewerDiscount`  `brewerDiscount` TINYINT(1) NULL DEFAULT NULL COMMENT '1 for true; 0 for false'";
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$updateSQL = "ALTER TABLE  $brewer_db_table CHANGE `brewerJudge` TINYINT(1) NULL DEFAULT NULL COMMENT '1 for true; 0 for false'";
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$updateSQL = "ALTER TABLE  $brewer_db_table CHANGE  `brewerSteward`  `brewerSteward` TINYINT(1) NULL DEFAULT NULL COMMENT '1 for true; 0 for false'";
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$output .= "<li>Updates to brewer table completed.</li>";

?>