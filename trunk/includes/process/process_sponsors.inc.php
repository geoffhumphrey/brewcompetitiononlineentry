<?php 
/*
 * Module:      process_styles_edit.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "sponsors" table
 */

if ($action == "add") {
	$insertSQL = sprintf("INSERT INTO sponsors (sponsorName, sponsorURL, sponsorImage, sponsorText, sponsorLocation, sponsorLevel) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString(capitalize($_POST['sponsorName']), "text"),
                       GetSQLValueString($_POST['sponsorURL'], "text"),
                       GetSQLValueString($_POST['sponsorImage'], "text"),
                       GetSQLValueString($_POST['sponsorText'], "text"),
					   GetSQLValueString($_POST['sponsorLocation'], "text"),
					   GetSQLValueString($_POST['sponsorLevel'], "int")
					   );

	mysql_select_db($database, $brewing);
  	$Result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());
	header(sprintf("Location: %s", $insertGoTo));					   
}

if ($action == "edit") {
	$updateSQL = sprintf("UPDATE sponsors SET sponsorName=%s, sponsorURL=%s, sponsorImage=%s, sponsorText=%s, sponsorLocation=%s , sponsorLevel=%s WHERE id=%s",
                       GetSQLValueString(capitalize($_POST['sponsorName']), "text"),
                       GetSQLValueString($_POST['sponsorURL'], "text"),
                       GetSQLValueString($_POST['sponsorImage'], "text"),
                       GetSQLValueString($_POST['sponsorText'], "text"),
					   GetSQLValueString($_POST['sponsorLocation'], "text"),
					   GetSQLValueString($_POST['sponsorLevel'], "int"),
					   GetSQLValueString($id, "int"));

	mysql_select_db($database, $brewing);
  	$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
	header(sprintf("Location: %s", $updateGoTo));					   
}

?>