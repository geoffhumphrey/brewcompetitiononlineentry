<?php
/*
 * Module:      process_style_types.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "style_types" table
 */
if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1))) {

	// Instantiate HTMLPurifier
	require (CLASSES.'htmlpurifier/HTMLPurifier.standalone.php');
	$config_html_purifier = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config_html_purifier);

	if ($action == "add") {

		$insertSQL = sprintf("INSERT INTO $style_types_db_table (
		styleTypeName,
		styleTypeOwn,
		styleTypeBOS,
		styleTypeBOSMethod
		)
		VALUES
		(%s, %s, %s, %s)",
						   GetSQLValueString(capitalize($purifier->purify($_POST['styleTypeName'])), "text"),
						   GetSQLValueString(sterilize($_POST['styleTypeOwn']), "text"),
						   GetSQLValueString(sterilize($_POST['styleTypeBOS']), "text"),
						   GetSQLValueString(sterilize($_POST['styleTypeBOSMethod']), "text"));

		mysqli_real_escape_string($connection,$insertSQL);
		$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));

		$pattern = array('\'', '"');
		$insertGoTo = str_replace($pattern, "", $insertGoTo);
		$redirect_go_to = sprintf("Location: %s", stripslashes($insertGoTo));

	}

	if ($action == "edit") {

		if ($go == "combine") {

			$updateSQL = "UPDATE $style_types_db_table SET styleTypeBOS='Y' WHERE styleTypeName='Mead/Cider'";
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			$updateSQL = "UPDATE $style_types_db_table SET styleTypeBOS='N' WHERE id='2'";
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			$updateSQL = "UPDATE $style_types_db_table SET styleTypeBOS='N' WHERE id='3'";
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			// Delete any BOS score entries with Mead or Cider ids; failsafe
			$updateSQL = "DELETE FROM $judging_scores_bos_db_table WHERE scoreType='2' OR scoreType='3'";
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			$updateGoTo = $base_url."index.php?section=admin&go=style_types&msg=2";

		}

		elseif ($go == "separate") {

			$updateSQL = "UPDATE $style_types_db_table SET styleTypeBOS='N' WHERE styleTypeName='Mead/Cider'";
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			$updateSQL = "UPDATE $style_types_db_table SET styleTypeBOS='Y' WHERE id='2'";
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			$updateSQL = "UPDATE $style_types_db_table SET styleTypeBOS='Y' WHERE id='3'";
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			// Delete any BOS score entries with Mead/Cider id; failsafe
			$query_mead_cider_present = sprintf("SELECT id FROM %s WHERE styleTypeName = 'Mead/Cider'",$prefix."style_types");
			$mead_cider_present = mysqli_query($connection,$query_mead_cider_present) or die (mysqli_error($connection));
			$row_mead_cider_present = mysqli_fetch_assoc($mead_cider_present);

			$updateSQL = sprintf("DELETE FROM $judging_scores_bos_db_table WHERE scoreType='%s'",$row_mead_cider_present['id']);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			$updateGoTo = $base_url."index.php?section=admin&go=style_types&msg=2";

		}

		else {
			$updateSQL = sprintf("UPDATE $style_types_db_table SET
			styleTypeName=%s,
			styleTypeOwn=%s,
			styleTypeBOS=%s,
			styleTypeBOSMethod=%s
			WHERE id=%s",
							   GetSQLValueString(capitalize($purifier->purify($_POST['styleTypeName'])), "text"),
							   GetSQLValueString(sterilize($_POST['styleTypeOwn']), "text"),
							   GetSQLValueString(sterilize($_POST['styleTypeBOS']), "text"),
							   GetSQLValueString(sterilize($_POST['styleTypeBOSMethod']), "text"),
							   GetSQLValueString($id, "int"));

			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		}

		$pattern = array('\'', '"');
		$updateGoTo = str_replace($pattern, "", $updateGoTo);
		$redirect_go_to = sprintf("Location: %s", stripslashes($updateGoTo));

	}

} else {
	$redirect_go_to = sprintf("Location: %s", $base_url."index.php?msg=98");
}
?>