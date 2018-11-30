<?php
/*
 * Module:      process_drop_off.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "drop_off" table
 */
if ((isset($_SERVER['HTTP_REFERER'])) && (((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) || ($section == "setup"))) {

	$dropLocationWebsite = check_http(sterilize($_POST['dropLocationWebsite']));
	$dropLocationName = sterilize($_POST['dropLocationName']);

	if ($action == "add") {

		if ($go != "skip") {

			$insertSQL = sprintf("INSERT INTO $drop_off_db_table (dropLocationName, dropLocation, dropLocationPhone, dropLocationWebsite, dropLocationNotes) VALUES (%s, %s, %s, %s, %s)",
							   GetSQLValueString(capitalize($dropLocationName), "text"),
							   GetSQLValueString(sterilize($_POST['dropLocation']), "text"),
							   GetSQLValueString(sterilize($_POST['dropLocationPhone']), "text"),
							   GetSQLValueString(strtolower($dropLocationWebsite), "text"),
							   GetSQLValueString(sterilize($_POST['dropLocationNotes']), "text")
							   );

			mysqli_real_escape_string($connection,$insertSQL);
			$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));

		}

		if ($section == "setup") {

			$sql = sprintf("UPDATE `%s` SET setup_last_step = '6' WHERE id='1';", $system_db_table);
			mysqli_select_db($connection,$database);
			mysqli_real_escape_string($connection,$sql);
			$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));

			if ($go == "skip") $insertGoTo = $base_url."setup.php?section=step7";
			else $insertGoTo = $base_url."setup.php?section=step6&msg=11";

		}

		else $insertGoTo = $base_url."index.php?section=admin&go=dropoff&msg=1";
		$pattern = array('\'', '"');
		$insertGoTo = str_replace($pattern, "", $insertGoTo);
		$redirect_go_to = sprintf("Location: %s", stripslashes($insertGoTo));

	}

	if ($action == "edit") {
		$updateSQL = sprintf("UPDATE $drop_off_db_table SET dropLocationName=%s, dropLocation=%s, dropLocationPhone=%s, dropLocationWebsite=%s, dropLocationNotes=%s WHERE id=%s",
						   GetSQLValueString(capitalize($dropLocationName), "text"),
						   GetSQLValueString(sterilize($_POST['dropLocation']), "text"),
						   GetSQLValueString(sterilize($_POST['dropLocationPhone']), "text"),
						   GetSQLValueString(strtolower($dropLocationWebsite), "text"),
						   GetSQLValueString(sterilize($_POST['dropLocationNotes']), "text"),
						   GetSQLValueString($id, "int"));

		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		$updateGoTo = $base_url."index.php?section=admin&go=dropoff&msg=2";

		$pattern = array('\'', '"');
		$updateGoTo = str_replace($pattern, "", $updateGoTo);
		$redirect_go_to = sprintf("Location: %s", stripslashes($updateGoTo));
	}

} else {
	$redirect_go_to = sprintf("Location: %s", $base_url."index.php?msg=98");
}
?>