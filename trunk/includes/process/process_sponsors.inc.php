<?php 
/*
 * Module:      process_styles_edit.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "sponsors" table
 */
$sponsorURL = check_http($_POST['sponsorURL']);
$sponsor_name = capitalize($_POST['sponsorName']);

if ($action == "add") {
	$insertSQL = sprintf("INSERT INTO $sponsors_db_table (sponsorName, sponsorURL, sponsorImage, sponsorText, sponsorLocation, sponsorLevel) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString(strtr($sponsor_name,$html_string), "text"),
                       GetSQLValueString($sponsorURL, "text"), 
                       GetSQLValueString($_POST['sponsorImage'], "text"),
                       GetSQLValueString(strtr($_POST['sponsorText'],$html_string), "text"),
					   GetSQLValueString(strtr($_POST['sponsorLocation'],$html_string), "text"),
					   GetSQLValueString($_POST['sponsorLevel'], "int")
					   );

	mysql_select_db($database, $brewing);
  	$Result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());
	$pattern = array('\'', '"');
  	$insertGoTo = str_replace($pattern, "", $insertGoTo); 
  	header(sprintf("Location: %s", stripslashes($insertGoTo)));					   
}

if ($action == "edit") {
	$updateSQL = sprintf("UPDATE $sponsors_db_table SET sponsorName=%s, sponsorURL=%s, sponsorImage=%s, sponsorText=%s, sponsorLocation=%s , sponsorLevel=%s WHERE id=%s",
                       GetSQLValueString(strtr($sponsor_name,$html_string), "text"),
                       GetSQLValueString($sponsorURL, "text"), 
                       GetSQLValueString($_POST['sponsorImage'], "text"),
                       GetSQLValueString(strtr($_POST['sponsorText'],$html_string), "text"),
					   GetSQLValueString(strtr($_POST['sponsorLocation'],$html_string), "text"),
					   GetSQLValueString($_POST['sponsorLevel'], "int"),
					   GetSQLValueString($id, "int"));

	mysql_select_db($database, $brewing);
  	$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
	$pattern = array('\'', '"');
  	$updateGoTo = str_replace($pattern, "", $updateGoTo); 
  	header(sprintf("Location: %s", stripslashes($updateGoTo)));			   
}

?>