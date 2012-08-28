<?php

// -----------------------------------------------------------
// Data Updates: Brewer Table
// -----------------------------------------------------------



$query_brewer = "SELECT * FROM $brewer_db_table";
$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
$row_brewer = mysql_fetch_assoc($brewer);

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
	
	mysql_select_db($database, $brewing);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 

}  
while ($row_brewer = mysql_fetch_assoc($brewer));

// Change rows to tinyint type

$updateSQL = "ALTER TABLE  $brewer_db_table CHANGE  `brewerJudgeBOS`  `brewerJudgeBOS` TINYINT(1) NULL DEFAULT NULL COMMENT '1 for true; 0 for false'";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "ALTER TABLE  $brewer_db_table CHANGE  `brewerDiscount`  `brewerDiscount` TINYINT(1) NULL DEFAULT NULL COMMENT '1 for true; 0 for false'";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "ALTER TABLE  $brewer_db_table CHANGE `brewerJudge` TINYINT(1) NULL DEFAULT NULL COMMENT '1 for true; 0 for false'";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "ALTER TABLE  $brewer_db_table CHANGE  `brewerSteward`  `brewerSteward` TINYINT(1) NULL DEFAULT NULL COMMENT '1 for true; 0 for false'";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

echo "<ul><li>Updates to brewer table completed.</li></ul>";

?>