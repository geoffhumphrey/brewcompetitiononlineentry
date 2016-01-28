<?php 
/*
 * Module:      process_drop_off.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "drop_off" table
 */
if (((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) || ($section == "setup")) {
	if (NHC) {
		// Place NHC SQL calls below
		
		
	}
	// end if (NHC)
	
	else {
	
	
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
		
			mysql_select_db($database, $brewing);
			mysql_real_escape_string($insertSQL);
			$result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());
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
							   
			mysql_select_db($database, $brewing);
			mysql_real_escape_string($updateSQL);
			$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
			$pattern = array('\'', '"');
			$updateGoTo = str_replace($pattern, "", $updateGoTo); 
			header(sprintf("Location: %s", stripslashes($updateGoTo)));			
		}
		
		
	
	} // end else NHC
} else echo "<p>Not available.</p>"; 
?>