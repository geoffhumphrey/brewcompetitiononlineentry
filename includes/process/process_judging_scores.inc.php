<?php 
/*
 * Module:      process_judging_scores.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "judging_scores" table
 */

if ($action == "add") {
	foreach($_POST['score_id'] as $score_id)	{
	if ($_POST['scoreEntry'.$score_id] != "") {
	$insertSQL = sprintf("INSERT INTO judging_scores (
	eid, 
	bid, 
	scoreTable,
	scoreEntry,
	scorePlace,
	scoreType
  	) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['eid'.$score_id], "text"),
					   GetSQLValueString($_POST['bid'.$score_id], "text"),
					   GetSQLValueString($_POST['scoreTable'.$score_id], "text"),
					   GetSQLValueString($_POST['scoreEntry'.$score_id], "text"),
					   GetSQLValueString($_POST['scorePlace'.$score_id], "text"),
					   GetSQLValueString($_POST['scoreType'.$score_id], "text")
					   );

	//echo $insertSQL."<br>";
	mysql_select_db($database, $brewing);
  	$Result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());
		}
	}
	
	header(sprintf("Location: %s", $insertGoTo));	
}

if ($action == "edit") {
	foreach($_POST['score_id'] as $score_id)	{
	if (($_POST['scoreEntry'.$score_id] != "") && ($_POST['scorePrevious'.$score_id] == "Y")) {
	$updateSQL = sprintf("UPDATE judging_scores SET
	eid=%s,
	bid=%s,
	scoreTable=%s,
	scoreEntry=%s,
	scorePlace=%s,
	scoreType=%s
	WHERE id=%s",
                       GetSQLValueString($_POST['eid'.$score_id], "text"),
					   GetSQLValueString($_POST['bid'.$score_id], "text"),
					   GetSQLValueString($_POST['scoreTable'.$score_id], "text"),
					   GetSQLValueString($_POST['scoreEntry'.$score_id], "text"),
					   GetSQLValueString($_POST['scorePlace'.$score_id], "text"),
					   GetSQLValueString($_POST['scoreType'.$score_id], "text"),
					   GetSQLValueString($score_id, "text")
					   );

	//echo $updateSQL."<br>";
	mysql_select_db($database, $brewing);
  	$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
	}
	if (($_POST['scoreEntry'.$score_id] != "") && ($_POST['scorePrevious'.$score_id] == "N")) {
	$insertSQL = sprintf("INSERT INTO judging_scores (
	eid, 
	bid, 
	scoreTable,
	scoreEntry,
	scorePlace,
	scoreType
  	) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['eid'.$score_id], "text"),
					   GetSQLValueString($_POST['bid'.$score_id], "text"),
					   GetSQLValueString($_POST['scoreTable'.$score_id], "text"),
					   GetSQLValueString($_POST['scoreEntry'.$score_id], "text"),
					   GetSQLValueString($_POST['scorePlace'.$score_id], "text"),
					   GetSQLValueString($_POST['scoreType'.$score_id], "text")
					   );

	//echo $insertSQL."<br>";
	mysql_select_db($database, $brewing);
  	$Result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());		
		}
	}
	header(sprintf("Location: %s", $updateGoTo));
}

?>