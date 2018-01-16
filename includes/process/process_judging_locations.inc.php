<?php
/*
 * Module:      process_judging_location.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "judging_locations" table
 */
if ((isset($_SERVER['HTTP_REFERER'])) && (((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) || ($section == "setup"))) {

	// Instantiate HTMLPurifier
	require (CLASSES.'htmlpurifier/HTMLPurifier.standalone.php');
	$config_html_purifier = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config_html_purifier);

	$judgingDate = strtotime(sterilize($_POST['judgingDate']));
	$judgingLocName = $purifier->purify($_POST['judgingLocName']);

	if ($action == "add") {
		$insertSQL = sprintf("INSERT INTO $judging_locations_db_table (judgingDate, judgingLocation, judgingLocName, judgingRounds) VALUES (%s, %s, %s, %s)",
						   GetSQLValueString($judgingDate, "text"),
						   GetSQLValueString($purifier->purify($_POST['judgingLocation']), "text"),
						   GetSQLValueString($judgingLocName, "text"),
						   GetSQLValueString(sterilize($_POST['judgingRounds']), "text")
						   );

		//echo $insertSQL;
		mysqli_real_escape_string($connection,$insertSQL);
		$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));

		if ($section == "setup") {

			$sql = sprintf("UPDATE `%s` SET setup_last_step = '5' WHERE id='1';", $system_db_table);
			mysqli_select_db($connection,$database);
			mysqli_real_escape_string($connection,$sql);
			$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));

			$insertGoTo = $base_url."setup.php?section=step5&msg=9";

		}

		else $insertGoTo = $insertGoTo;

		$pattern = array('\'', '"');
		$insertGoTo = str_replace($pattern, "", $insertGoTo);
		$redirect_go_to = sprintf("Location: %s", stripslashes($insertGoTo));

	}

	if ($action == "edit") {
		$updateSQL = sprintf("UPDATE $judging_locations_db_table SET judgingDate=%s, judgingLocation=%s, judgingLocName=%s, judgingRounds=%s WHERE id=%s",
						   GetSQLValueString($judgingDate, "text"),
						   GetSQLValueString($purifier->purify($_POST['judgingLocation']), "text"),
						   GetSQLValueString($judgingLocName, "text"),
						   GetSQLValueString(sterilize($_POST['judgingRounds']), "text"),
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