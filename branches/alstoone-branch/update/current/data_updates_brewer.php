<?php

// -----------------------------------------------------------
// Data Updates: Brewer Table
// -----------------------------------------------------------



$query_brewer = "SELECT * FROM $brewer_db_table";
$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
$row_brewer = mysql_fetch_assoc($brewer);

do { 

	if ($row_brewer['brewerAssignmentJudge'] == "J") { 
		$brewerAssignmentJudge = "1";
		$brewerAssignmentStaff = "0";
		$brewerAssignmentSteward = "0"; 
		$brewerAssignmentOrganizer = "0";
		}
		
	if ($row_brewer['brewerAssignmentJudge'] == "S") { 
		$brewerAssignmentJudge = "0";
		$brewerAssignmentStaff = "0";
		$brewerAssignmentSteward = "1"; 
		$brewerAssignmentOrganizer = "0";
		}
		
	if ($row_brewer['brewerAssignmentJudge'] == "X") { 
		$brewerAssignmentJudge = "0";
		$brewerAssignmentStaff = "1";
		$brewerAssignmentSteward = "0"; 
		$brewerAssignmentOrganizer = "0";
		}
	
	if ($row_brewer['brewerAssignmentJudge'] == "O") { 
		$brewerAssignmentJudge = "0";
		$brewerAssignmentStaff = "0";
		$brewerAssignmentSteward = "0"; 
		$brewerAssignmentOrganizer = "1";
		}
	
	if ($row_brewer['brewerAssignmentJudge'] == "") { 
		$brewerAssignmentJudge = "0";
		$brewerAssignmentStaff = "0";
		$brewerAssignmentSteward = "0"; 
		$brewerAssignmentOrganizer = "0";
		}
	if ($row_brewer['brewerJudge'] == "Y") $brewerJudge = "1"; else $brewerJudge = "0";
	if ($row_brewer['brewerSteward'] == "Y") $brewerSteward = "1"; else $brewerSteward = "0";
	if ($row_brewer['brewerJudgeBOS'] == "Y") $brewerJudgeBOS = "1"; else $brewerJudgeBOS = "0";
	if ($row_brewer['brewerDiscount'] == "Y") $brewerDiscount = "1"; else $brewerDiscount = "0";
	
	$updateSQL = sprintf("UPDATE $brewer_db_table SET 
						 brewerJudge='%s',
						 brewerSteward='%s',
						 brewerAssignmentJudge='%s', 
						 brewerAssignmentStaff='%s', 
						 brewerAssignmentSteward='%s', 
						 brewerAssignmentOrganizer='%s', 
						 brewerJudgeBOS='%s', 
						 brewerDiscount='%s',
						 WHERE id='%s'", 
						 $brewerJudge,
						 $brewerSteward,
						 $brewerAssignmentJudge, 
						 $brewerAssignmentStaff,
						 $brewerAssignmentSteward, 
						 $brewerAssignmentOrganizer, 
						 $brewerJudgeBOS, 
						 $brewerDiscount, 
						 $row_brewer['id']);
	
	mysql_select_db($database, $brewing);
	//$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
echo $updateSQL."<br>";

	//echo "<ul><li>Record for ".$row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']." updated.</li></ul>";

}  
while ($row_brewer = mysql_fetch_assoc($brewer));

// Change rows to tinyint type
$updateSQL = "ALTER TABLE  `brewer` CHANGE  `brewerAssignmentJudge`  `brewerAssignmentJudge` TINYINT(1) NULL DEFAULT NULL COMMENT '1 for true; 0 for false', CHANGE  `brewerJudgeBOS`  `brewerJudgeBOS` TINYINT(1) NULL DEFAULT NULL COMMENT '1 for true; 0 for false', CHANGE  `brewerDiscount`  `brewerDiscount` TINYINT(1) NULL DEFAULT NULL COMMENT '1 for true; 0 for false', CHANGE  `brewerJudge`  `brewerJudge` TINYINT(1) NULL DEFAULT NULL COMMENT '1 for true; 0 for false', CHANGE  `brewerSteward`  `brewerSteward` TINYINT(1) NULL DEFAULT NULL COMMENT '1 for true; 0 for false'";
//echo $updateSQL."<br>";
mysql_select_db($database, $brewing);
//$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
echo $updateSQL."<br>";

echo "<ul><li>Updates to brewer table completed.</li></ul>";

?>