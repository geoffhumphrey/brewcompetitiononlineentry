<?php
/*
 * Module:      process_special_best_info.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "special_best_info" table
 */

if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1))) {

	// Instantiate HTMLPurifier
	require (CLASSES.'htmlpurifier/HTMLPurifier.standalone.php');
	$config_html_purifier = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config_html_purifier);

	if ($action == "add") {

			$insertSQL = sprintf("INSERT INTO $special_best_info_db_table (sbi_name, sbi_description, sbi_places, sbi_rank, sbi_display_places) VALUES (%s, %s, %s, %s, %s)",
							   GetSQLValueString($purifier->purify($_POST['sbi_name']), "text"),
							   GetSQLValueString($purifier->purify(strip_newline($_POST['sbi_description'])), "text"),
							   GetSQLValueString(sterilize($_POST['sbi_places']), "int"),
							   GetSQLValueString(sterilize($_POST['sbi_rank']), "int"),
							   GetSQLValueString(sterilize($_POST['sbi_display_places']), "int")
							   );

			mysqli_real_escape_string($connection,$insertSQL);
			$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));

			$pattern = array('\'', '"');
			$insertGoTo = str_replace($pattern, "", $insertGoTo);
			$redirect_go_to = sprintf("Location: %s", stripslashes($insertGoTo));

		}

		if ($action == "edit") {

			$updateSQL = sprintf("UPDATE $special_best_info_db_table SET sbi_name=%s, sbi_description=%s, sbi_places=%s, sbi_rank=%s, sbi_display_places=%s WHERE id=%s",
							   GetSQLValueString($purifier->purify($_POST['sbi_name']), "text"),
							   GetSQLValueString($purifier->purify(strip_newline($_POST['sbi_description'])), "text"),
							   GetSQLValueString(sterilize($_POST['sbi_places']), "int"),
							   GetSQLValueString(sterilize($_POST['sbi_rank']), "int"),
							   GetSQLValueString(sterilize($_POST['sbi_display_places']), "int"),
							   GetSQLValueString($id, "int"));

			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			$pattern = array('\'', '"');
			$updateGoTo = str_replace($pattern, "", $updateGoTo);
			$redirect_go_to = sprintf("Location: %s", stripslashes($updateGoTo));

		}

} else {
	$redirect_go_to = sprintf("Location: %s", $base_url."index.php?msg=98");
}
?>