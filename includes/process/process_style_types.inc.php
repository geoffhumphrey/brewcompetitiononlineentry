<?php 
/*
 * Module:      process_style_types.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "style_types" table
 */
if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {
	
	if ($action == "add") {
		
		$insertSQL = sprintf("INSERT INTO $style_types_db_table (
		styleTypeName, 
		styleTypeOwn, 
		styleTypeBOS, 
		styleTypeBOSMethod
		) 
		VALUES 
		(%s, %s, %s, %s)",
						   GetSQLValueString(capitalize($_POST['styleTypeName']), "text"),
						   GetSQLValueString($_POST['styleTypeOwn'], "text"),
						   GetSQLValueString($_POST['styleTypeBOS'], "text"),
						   GetSQLValueString($_POST['styleTypeBOSMethod'], "text"));
		
		mysqli_real_escape_string($connection,$insertSQL);
		$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));
		
		$pattern = array('\'', '"');
		$insertGoTo = str_replace($pattern, "", $insertGoTo); 
		header(sprintf("Location: %s", stripslashes($insertGoTo)));				   
		
	}
	
	if ($action == "edit") {
		
		$updateSQL = sprintf("UPDATE $style_types_db_table SET
		styleTypeName=%s, 
		styleTypeOwn=%s, 
		styleTypeBOS=%s, 
		styleTypeBOSMethod=%s
		WHERE id=%s",
						   GetSQLValueString($_POST['styleTypeName'], "text"),
						   GetSQLValueString($_POST['styleTypeOwn'], "text"),
						   GetSQLValueString($_POST['styleTypeBOS'], "text"),
						   GetSQLValueString($_POST['styleTypeBOSMethod'], "text"),
						   GetSQLValueString($id, "int"));
		
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		$pattern = array('\'', '"');
		$updateGoTo = str_replace($pattern, "", $updateGoTo); 
		header(sprintf("Location: %s", stripslashes($updateGoTo)));	
				
	}
		
} else echo "<p>Not available.</p>";?>