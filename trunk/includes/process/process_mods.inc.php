<?php 
/*
 * Module:      process_mods.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "mods" table
 */

if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {

if (($_POST['mod_extend_function_admin'] == "") && ($_POST['mod_extend_function'] == 9)) $mod_extend_function_admin = "default"; 
else $mod_extend_function_admin = $_POST['mod_extend_function_admin'];

	if (NHC) {
		// Place NHC SQL calls below
		
		
	}
	
	else {

		if ($action == "update") {
			foreach($_POST['id'] as $id) {  
				if ($_POST['mod_enable'.$id] == 1) $enable = 1; else $enable = 0;
				$updateSQL = sprintf("UPDATE %s SET mod_enable='%s' WHERE id='%s'",$mods_db_table,$enable,$id);
				//echo $updateSQL."<br>";
				mysql_real_escape_string($updateSQL);
				$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
			}
			$massUpdateGoTo = $base_url."index.php?section=admin&go=mods&msg=9"; 
			$pattern = array('\'', '"');
			$massUpdateGoTo = str_replace($pattern, "", $massUpdateGoTo); 
			header(sprintf("Location: %s", stripslashes($massUpdateGoTo))); 
		}
		
		if ($action == "add") {
			$insertSQL = sprintf("
						INSERT INTO $mods_db_table 
						(
						mod_name,
						mod_type, 
						mod_extend_function, 
						mod_extend_function_admin, 
						mod_filename, 
						mod_description, 
						mod_permission, 
						mod_rank, 
						mod_display_rank,
						mod_enable
						) 
						VALUES 
						(
						%s, %s, %s, %s, %s,
						%s, %s, %s, %s, %s
						)",
						GetSQLValueString(strtr($_POST['mod_name'],$html_string), "text"),
						GetSQLValueString($_POST['mod_type'], "int"),
						GetSQLValueString($_POST['mod_extend_function'], "int"),
						GetSQLValueString($mod_extend_function_admin, "text"),
						GetSQLValueString($_POST['mod_filename'], "text"),			   
						GetSQLValueString(strip_newline($_POST['mod_description']), "text"),
						GetSQLValueString($_POST['mod_permission'], "int"),
						GetSQLValueString($_POST['mod_rank'], "int"),
						GetSQLValueString($_POST['mod_display_rank'], "int"),
						GetSQLValueString($_POST['mod_enable'], "int")
						);
		
			mysql_select_db($database, $brewing);
			mysql_real_escape_string($insertSQL);
			$result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());
			$pattern = array('\'', '"');
			$insertGoTo = str_replace($pattern, "", $insertGoTo); 
			header(sprintf("Location: %s", stripslashes($insertGoTo)));					   
		}
		
		if ($action == "edit") {
			$updateSQL = sprintf("
					UPDATE $mods_db_table SET 
					mod_name=%s,
					mod_type=%s, 
					mod_extend_function=%s, 
					mod_extend_function_admin=%s, 
					mod_filename=%s, 
					mod_description=%s, 
					mod_permission=%s, 
					mod_rank=%s, 
					mod_display_rank=%s,
					mod_enable=%s
					WHERE id=%s",
					GetSQLValueString(strtr($_POST['mod_name'],$html_string), "text"),
					GetSQLValueString($_POST['mod_type'], "int"),
					GetSQLValueString($_POST['mod_extend_function'], "int"),
					GetSQLValueString($mod_extend_function_admin, "text"),
					GetSQLValueString($_POST['mod_filename'], "text"),			   
					GetSQLValueString(strip_newline($_POST['mod_description']), "text"),
					GetSQLValueString($_POST['mod_permission'], "int"),
					GetSQLValueString($_POST['mod_rank'], "int"),
					GetSQLValueString($_POST['mod_display_rank'], "int"),
					GetSQLValueString($_POST['mod_enable'], "int"),
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