<?php 
/*
 * Module:      process_styles.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "styles" table
 */

if ($action == "update") {
foreach($_POST['id'] as $id)	{ 

		if ($filter == "default") {
		 $updateSQL = "UPDATE $styles_db_table SET brewStyleActive='".$_POST["brewStyleActive".$id]."' WHERE id='".$id."'";
		 mysql_select_db($database, $brewing);
		 $result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());	
		 }
		 
		if (($filter == "judging") && ($bid == $_POST["brewStyleJudgingLoc".$id])) { 
		 $updateSQL = "UPDATE $styles_db_table SET brewStyleJudgingLoc='".$_POST["brewStyleJudgingLoc".$id]."' WHERE id='".$id."';";
		 $result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		 //echo $updateSQL."<br>"; 
		 
		 // Also need to find all records in the "brewing" table (entries) that are null or have either old judging location associated with the style and update them with the new judging location.		 
		 mysql_select_db($database, $brewing);
		 $query_style_name = "SELECT *FROM $styles_db_table WHERE id='".$id."'";
		 $style_name = mysql_query($query_style_name, $brewing) or die(mysql_error());
		 $row_style_name = mysql_fetch_assoc($style_name);
		 
		 $query_loc = sprintf("SELECT * FROM $brewing_db_table WHERE brewCategorySort='%s' AND brewSubCategory='%s'", $row_style_name['brewStyleGroup'], $row_style_name['brewStyleNum']);
		 $loc = mysql_query($query_loc, $brewing) or die(mysql_error());
		 $row_loc = mysql_fetch_assoc($loc);
		 $totalRows_loc = mysql_num_rows($loc);
		 //echo $query_loc."<br>";
		 	if ($totalRows_loc > 0) {
		 		do { 
				if ($row_loc['brewJudgingLocation'] != $_POST["brewStyleJudgingLoc".$id]) {
				$updateSQL2 = sprintf("UPDATE $brewing_db_table SET brewJudgingLocation='%s' WHERE id='%s';", $_POST["brewStyleJudgingLoc".$id], $row_loc['id']); 
				$result2 = mysql_query($updateSQL2, $brewing) or die(mysql_error());
				//echo $updateSQL2."<br>"; 
				}
				}
				 
				while($row_loc = mysql_fetch_assoc($loc));
		 	}
		 }
	}
		 
if($result1){ 
	if ($section == "step7") header("location:../setup.php?section=step8"); 
	else {
		$pattern = array('\'', '"');
  		$massUpdateGoTo = str_replace($pattern, "", $massUpdateGoTo);
		header(sprintf("Location: %s", stripslashes($massUpdateGoTo)));
	}
	}
}

if ($action == "add") {
	
mysql_select_db($database, $brewing);
$query_style_name = "SELECT brewStyleGroup FROM `".$prefix."styles` ORDER BY id DESC LIMIT 1";
$style_name = mysql_query($query_style_name, $brewing) or die(mysql_error());
$row_style_name = mysql_fetch_assoc($style_name);
$style_add_one = $row_style_name['brewStyleGroup'] + 1;

  $insertSQL = sprintf("INSERT INTO $styles_db_table (
  brewStyleNum, 
  brewStyle, 
  brewStyleOG, 
  brewStyleOGMax, 
  brewStyleFG, 
  
  brewStyleFGMax, 
  brewStyleABV, 
  brewStyleABVMax, 
  brewStyleIBU, 
  brewStyleIBUMax, 
  
  brewStyleSRM, 
  brewStyleSRMMax, 
  brewStyleType, 
  brewStyleInfo, 
  brewStyleLink, 
  
  brewStyleGroup, 
  brewStyleActive, 
  brewStyleOwn
  ) 
  VALUES (
  %s, %s, %s, %s, %s, 
  %s, %s, %s, %s, %s, 
  %s, %s, %s, %s, %s, 
  %s, %s, %s)",
                       GetSQLValueString("A", "text"),
                       GetSQLValueString($_POST['brewStyle'], "scrubbed"),
                       GetSQLValueString($_POST['brewStyleOG'], "text"),
                       GetSQLValueString($_POST['brewStyleOGMax'], "text"),
                       GetSQLValueString($_POST['brewStyleFG'], "text"),
                       GetSQLValueString($_POST['brewStyleFGMax'], "text"),
                       GetSQLValueString($_POST['brewStyleABV'], "text"),
                       GetSQLValueString($_POST['brewStyleABVMax'], "text"),
                       GetSQLValueString($_POST['brewStyleIBU'], "text"),
                       GetSQLValueString($_POST['brewStyleIBUMax'], "text"),
                       GetSQLValueString($_POST['brewStyleSRM'], "text"),
                       GetSQLValueString($_POST['brewStyleSRMMax'], "text"),
                       GetSQLValueString($_POST['brewStyleType'], "text"),
                       GetSQLValueString($_POST['brewStyleInfo'], "text"),
                       GetSQLValueString($_POST['brewStyleLink'], "text"),
                       GetSQLValueString($style_add_one, "text"),
					   GetSQLValueString($_POST['brewStyleActive'], "text"),
					   GetSQLValueString($_POST['brewStyleOwn'], "text")
					   );


  	mysql_select_db($database, $brewing);
  	$Result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());
  	$pattern = array('\'', '"');
  	$insertGoTo = str_replace($pattern, "", $insertGoTo); 
  	header(sprintf("Location: %s", stripslashes($insertGoTo)));
	
}

if ($action == "edit") {
	$updateSQL = sprintf("UPDATE $styles_db_table SET 
	  brewStyleNum=%s, 
	  brewStyle=%s, 
	  brewStyleOG=%s, 
	  brewStyleOGMax=%s, 
	  brewStyleFG=%s, 
	  
	  brewStyleFGMax=%s, 
	  brewStyleABV=%s, 
	  brewStyleABVMax=%s, 
	  brewStyleIBU=%s, 
	  brewStyleIBUMax=%s, 
	  
	  brewStyleSRM=%s, 
	  brewStyleSRMMax=%s, 
	  brewStyleType=%s, 
	  brewStyleInfo=%s, 
	  brewStyleLink=%s, 
	  
	  brewStyleGroup=%s,
	  brewStyleActive=%s, 
	  brewStyleOwn=%s
	  
	  WHERE id=%s",
                       GetSQLValueString($_POST['brewStyleNum'], "text"),
                       GetSQLValueString($_POST['brewStyle'], "scrubbed"),
                       GetSQLValueString($_POST['brewStyleOG'], "text"),
                       GetSQLValueString($_POST['brewStyleOGMax'], "text"),
                       GetSQLValueString($_POST['brewStyleFG'], "text"),
                       GetSQLValueString($_POST['brewStyleFGMax'], "text"),
                       GetSQLValueString($_POST['brewStyleABV'], "text"),
                       GetSQLValueString($_POST['brewStyleABVMax'], "text"),
                       GetSQLValueString($_POST['brewStyleIBU'], "text"),
                       GetSQLValueString($_POST['brewStyleIBUMax'], "text"),
                       GetSQLValueString($_POST['brewStyleSRM'], "text"),
                       GetSQLValueString($_POST['brewStyleSRMMax'], "text"),
                       GetSQLValueString($_POST['brewStyleType'], "text"),
                       GetSQLValueString($_POST['brewStyleInfo'], "text"),
                       GetSQLValueString($_POST['brewStyleLink'], "text"),
                       GetSQLValueString($_POST['brewStyleGroup'], "text"),
					   GetSQLValueString($_POST['brewStyleActive'], "text"),
					   GetSQLValueString($_POST['brewStyleOwn'], "text"),
                       GetSQLValueString($id, "int"));

  mysql_select_db($database, $brewing);
  $Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
  
  	$query_log = sprintf("SELECT id FROM $brewing_db_table WHERE brewStyle = '%s'",$_POST['brewStyleOld']); 
	$log = mysql_query($query_log, $brewing) or die(mysql_error());
	$row_log = mysql_fetch_assoc($log);
	$totalRows_log = mysql_num_rows($log);
	  
  do {
	 $updateSQL = sprintf("UPDATE $brewing_db_table SET brewStyle='%s' WHERE id='%s'", $_POST['brewStyle'],$row_log['id']);
	 $Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
  } while ($row_log = mysql_fetch_assoc($log));
  
  	$pattern = array('\'', '"');
  	$updateGoTo = str_replace($pattern, "", $updateGoTo); 
  	header(sprintf("Location: %s", stripslashes($updateGoTo)));
	
}
?>