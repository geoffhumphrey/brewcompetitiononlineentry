<?php 
/*
 * Module:      process_drop_off.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "drop_off" table
 */
if (((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) || ($section == "setup")) {
	
	$dropLocationWebsite = check_http($_POST['dropLocationWebsite']);
	$dropLocationName = strtr($_POST['dropLocationName'],$html_string);
	
	if ($action == "add") {
		$insertSQL = sprintf("INSERT INTO $drop_off_db_table (dropLocationName, dropLocation, dropLocationPhone, dropLocationWebsite, dropLocationNotes) VALUES (%s, %s, %s, %s, %s)",
						   GetSQLValueString(capitalize($dropLocationName), "text"),
						   GetSQLValueString(strtr($_POST['dropLocation'],$html_string), "text"),
						   GetSQLValueString($_POST['dropLocationPhone'], "text"),
						   GetSQLValueString(strtolower($dropLocationWebsite), "text"),
						   GetSQLValueString(strtr($_POST['dropLocationNotes'],$html_string), "text")
						   );
	
		mysqli_real_escape_string($connection,$insertSQL);
		$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));
		
		if ($section == "setup") $insertGoTo = "../setup.php?section=step6&msg=11"; else $insertGoTo = $insertGoTo;
		$pattern = array('\'', '"');
		$insertGoTo = str_replace($pattern, "", $insertGoTo); 
		header(sprintf("Location: %s", stripslashes($insertGoTo)));			   
		
	}
	
	if ($action == "edit") {
		$updateSQL = sprintf("UPDATE $drop_off_db_table SET dropLocationName=%s, dropLocation=%s, dropLocationPhone=%s, dropLocationWebsite=%s, dropLocationNotes=%s WHERE id=%s",
						   GetSQLValueString(capitalize($dropLocationName), "text"),
						   GetSQLValueString(strtr($_POST['dropLocation'],$html_string), "text"),
						   GetSQLValueString($_POST['dropLocationPhone'], "text"),
						   GetSQLValueString(strtolower($dropLocationWebsite), "text"),
						   GetSQLValueString(strtr($_POST['dropLocationNotes'],$html_string), "text"),
						   GetSQLValueString($id, "int"));   
						   
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		$pattern = array('\'', '"');
		$updateGoTo = str_replace($pattern, "", $updateGoTo); 
		header(sprintf("Location: %s", stripslashes($updateGoTo)));			
	}
		
} else echo "<p>Not available.</p>"; 
?>