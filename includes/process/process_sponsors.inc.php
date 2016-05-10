<?php 
/*
 * Module:      process_styles_edit.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "sponsors" table
 */
if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {
	$sponsorURL = check_http($_POST['sponsorURL']);
	$sponsor_name = capitalize($_POST['sponsorName']);
	
	if ($action == "update") {
		
		foreach($_POST['id'] as $id) {  
		
			if ($_POST['sponsorEnable'.$id] == 1) $enable = 1; else $enable = 0;
			$updateSQL = sprintf("UPDATE %s SET sponsorEnable='%s' WHERE id='%s'",$sponsors_db_table,$enable,$id);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
		}
		
		$massUpdateGoTo = $base_url."index.php?section=admin&go=sponsors&msg=9"; 
		$pattern = array('\'', '"');
		$massUpdateGoTo = str_replace($pattern, "", $massUpdateGoTo); 
		header(sprintf("Location: %s", stripslashes($massUpdateGoTo))); 
	}
	
	if ($action == "add") {
		
		$insertSQL = sprintf("INSERT INTO $sponsors_db_table (sponsorName, sponsorURL, sponsorImage, sponsorText, sponsorLocation, sponsorLevel, sponsorEnable) VALUES (%s, %s, %s, %s, %s, %s, %s)",
						   GetSQLValueString($sponsor_name, "text"),
						   GetSQLValueString($sponsorURL, "text"), 
						   GetSQLValueString($_POST['sponsorImage'], "text"),
						   GetSQLValueString($_POST['sponsorText'], "text"),
						   GetSQLValueString($_POST['sponsorLocation'], "text"),
						   GetSQLValueString($_POST['sponsorLevel'], "int"),
						   GetSQLValueString($_POST['sponsorEnable'], "int")
						   );
	
		
		mysqli_real_escape_string($connection,$insertSQL);
		$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));
		
		$pattern = array('\'', '"');
		$insertGoTo = str_replace($pattern, "", $insertGoTo); 
		header(sprintf("Location: %s", stripslashes($insertGoTo)));
						   
	}
	
	if ($action == "edit") {
		
		$updateSQL = sprintf("UPDATE $sponsors_db_table SET sponsorName=%s, sponsorURL=%s, sponsorImage=%s, sponsorText=%s, sponsorLocation=%s , sponsorLevel=%s, sponsorEnable=%s WHERE id=%s",
						   GetSQLValueString($sponsor_name, "text"),
						   GetSQLValueString($sponsorURL, "text"), 
						   GetSQLValueString($_POST['sponsorImage'], "text"),
						   GetSQLValueString($_POST['sponsorText'], "text"),
						   GetSQLValueString($_POST['sponsorLocation'], "text"),
						   GetSQLValueString($_POST['sponsorLevel'], "int"),
						   GetSQLValueString($_POST['sponsorEnable'], "int"),
						   GetSQLValueString($id, "int"));
	
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
		
		$pattern = array('\'', '"');
		$updateGoTo = str_replace($pattern, "", $updateGoTo); 
		header(sprintf("Location: %s", stripslashes($updateGoTo)));	
				   
	}

} else echo "<p>Not available.</p>";
?>