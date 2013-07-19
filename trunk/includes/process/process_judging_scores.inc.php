<?php 
/*
 * Module:      process_judging_scores.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "judging_scores" table
 */

if ($action == "add") {
	foreach($_POST['score_id'] as $score_id)	{
	if (($_POST['scoreEntry'.$score_id] != "") || ($_POST['scorePlace'.$score_id] != "")) {
	$insertSQL = sprintf("INSERT INTO $judging_scores_db_table (
	eid, 
	bid, 
	scoreTable,
	scoreEntry,
	scorePlace,
	scoreType,
	scoreMiniBOS
  	) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['eid'.$score_id], "text"),
					   GetSQLValueString($_POST['bid'.$score_id], "text"),
					   GetSQLValueString($_POST['scoreTable'.$score_id], "text"),
					   GetSQLValueString($_POST['scoreEntry'.$score_id], "text"),
					   GetSQLValueString($_POST['scorePlace'.$score_id], "text"),
					   GetSQLValueString($_POST['scoreType'.$score_id], "text"),
					   GetSQLValueString($_POST['scoreMiniBOS'.$score_id], "int")
					   );

	//echo $insertSQL."<br>";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($insertSQL);
  	$result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());
		}
	}
	$pattern = array('\'', '"');
  	$insertGoTo = str_replace($pattern, "", $insertGoTo); 
  	header(sprintf("Location: %s", stripslashes($insertGoTo)));
}

if ($action == "edit") {
	foreach($_POST['score_id'] as $score_id)	{
	if ((($_POST['scoreEntry'.$score_id] != "") || ($_POST['scorePlace'.$score_id] != "")) && ($_POST['scorePrevious'.$score_id] == "Y")) {
	$updateSQL = sprintf("UPDATE $judging_scores_db_table SET
	eid=%s,
	bid=%s,
	scoreTable=%s,
	scoreEntry=%s,
	scorePlace=%s,
	scoreType=%s,
	scoreMiniBOS=%s
	WHERE id=%s",
                       GetSQLValueString($_POST['eid'.$score_id], "text"),
					   GetSQLValueString($_POST['bid'.$score_id], "text"),
					   GetSQLValueString($_POST['scoreTable'.$score_id], "text"),
					   GetSQLValueString($_POST['scoreEntry'.$score_id], "text"),
					   GetSQLValueString($_POST['scorePlace'.$score_id], "text"),
					   GetSQLValueString($_POST['scoreType'.$score_id], "text"),
					   GetSQLValueString($_POST['scoreMiniBOS'.$score_id], "int"),
					   GetSQLValueString($score_id, "text")
					   );

	//echo $updateSQL."<br>";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
  	$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
	}
	if ((($_POST['scoreEntry'.$score_id] != "") || ($_POST['scorePlace'.$score_id] != "")) && ($_POST['scorePrevious'.$score_id] == "N")) {
	$insertSQL = sprintf("INSERT INTO $judging_scores_db_table (
	eid, 
	bid, 
	scoreTable,
	scoreEntry,
	scorePlace,
	scoreType,
	scoreMiniBOS
  	) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['eid'.$score_id], "text"),
					   GetSQLValueString($_POST['bid'.$score_id], "text"),
					   GetSQLValueString($_POST['scoreTable'.$score_id], "text"),
					   GetSQLValueString($_POST['scoreEntry'.$score_id], "text"),
					   GetSQLValueString($_POST['scorePlace'.$score_id], "text"),
					   GetSQLValueString($_POST['scoreType'.$score_id], "text"),
					   GetSQLValueString($_POST['scoreMiniBOS'.$score_id], "int")
					   );

	//echo $insertSQL."<br>";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($insertSQL);
  	$result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());		
		}
	
	if ((($_POST['scoreEntry'.$score_id] == "") && ($_POST['scorePlace'.$score_id] == "")) && ($_POST['scorePrevious'.$score_id] == "Y")) {
		$deleteScore = sprintf("DELETE FROM $judging_scores_db_table WHERE id='%s'", $score_id);
		mysql_real_escape_string($deleteScore);
		$result1 = mysql_query($deleteScore, $brewing) or die(mysql_error());
	}
	
	}
	
	$pattern = array('\'', '"');
  	$updateGoTo = str_replace($pattern, "", $updateGoTo); 
  	header(sprintf("Location: %s", stripslashes($updateGoTo)));
}

?>