<?php
/*
 * Module:      process_styles.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "styles" table
 */

// Instantiate HTMLPurifier
require (CLASSES.'htmlpurifier/HTMLPurifier.standalone.php');
$config_html_purifier = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config_html_purifier);

if ((isset($_SERVER['HTTP_REFERER'])) && (((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) || ($section == "setup"))) {

	$ba_styles_accepted = "";
	$brewStyleEntry = "";
	$brewStyleInfo = "";
	$brewStyleLink = "";
	$brewStyleStrength = "";
	if (isset($_POST['brewStyleEntry'])) $brewStyleEntry = $purifier->purify($_POST['brewStyleEntry']);
	if (isset($_POST['brewStyleInfo'])) $brewStyleInfo = $purifier->purify($_POST['brewStyleInfo']);
	if (isset($_POST['brewStyleLink'])) $brewStyleLink = sterilize($_POST['brewStyleLink']);
	if ((isset($_POST['brewStyleType'])) && ($_POST['brewStyleType'] == 2)) $brewStyleStrength = 0; else $brewStyleStrength = sterilize($_POST['brewStyleStrength']);

	if ($action == "update") {

		foreach($_POST['id'] as $id) {

			if (isset($_POST['brewStyleActive'.$id])) $brewStyleActive = "Y";
			else $brewStyleActive = "N";

				if ($filter == "default") {

					$updateSQL = "UPDATE $styles_db_table SET brewStyleActive='".$brewStyleActive."' WHERE id='".$id."'";
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

				 } // end if ($filter == "default")

				if (($filter == "judging") && ($bid == $_POST["brewStyleJudgingLoc".$id])) {

					$updateSQL = "UPDATE $styles_db_table SET brewStyleJudgingLoc='".$_POST["brewStyleJudgingLoc".$id]."' WHERE id='".$id."';";
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

					// Also need to find all records in the "brewing" table (entries) that are null or have either old judging location associated with the style and update them with the new judging location.
					$query_style_name = "SELECT *FROM $styles_db_table WHERE id='".$id."'";
					$style_name = mysqli_query($connection,$query_style_name) or die (mysqli_error($connection));
					$row_style_name = mysqli_fetch_assoc($style_name);

					$query_loc = sprintf("SELECT * FROM $brewing_db_table WHERE brewCategorySort='%s' AND brewSubCategory='%s'", $row_style_name['brewStyleGroup'], $row_style_name['brewStyleNum']);
					$loc = mysqli_query($connection,$query_loc) or die (mysqli_error($connection));
					$row_loc = mysqli_fetch_assoc($loc);
					$totalRows_loc = mysqli_num_rows($loc);

					if ($totalRows_loc > 0) {
						do {

							if ($row_loc['brewJudgingLocation'] != $_POST["brewStyleJudgingLoc".$id]) {

								$updateSQL = sprintf("UPDATE $brewing_db_table SET brewJudgingLocation='%s' WHERE id='%s';", $_POST["brewStyleJudgingLoc".$id], $row_loc['id']);
								mysqli_real_escape_string($connection,$updateSQL);
								$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

							}

						} while($row_loc = mysqli_fetch_assoc($loc));

					} // end if ($totalRows_loc > 0)

				} // end if (($filter == "judging") && ($bid == $_POST["brewStyleJudgingLoc".$id]))

			} // end foreach($_POST['id'] as $id)

		if($result) {

			if ($section == "setup") {

				$sql = sprintf("UPDATE `%s` SET setup_last_step = '7' WHERE id='1';", $system_db_table);
				mysqli_select_db($connection,$database);
				mysqli_real_escape_string($connection,$sql);
				$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));

				$massUpdateGoTo = $base_url."setup.php?section=step8";
				$pattern = array('\'', '"');
				$massUpdateGoTo = str_replace($pattern, "", $massUpdateGoTo);
				$redirect_go_to = sprintf("Location: %s", stripslashes($massUpdateGoTo));

			}

			else {
				$pattern = array('\'', '"');
				$massUpdateGoTo = str_replace($pattern, "", $massUpdateGoTo);
				$redirect_go_to = sprintf("Location: %s", stripslashes($massUpdateGoTo));
			}

		} // end if($result)

	} // end if ($action == "update");

	if ($action == "add") {

		$order_by = "id";

		if ($_SESSION['prefsStyleSet'] == "BJCP2008") $category_end = 28;
		elseif ($_SESSION['prefsStyleSet'] == "BA") {
			$category_end = 14;
			$order_by = "brewStyleNum";
		}
		else $category_end = 34;

		if ($_SESSION['prefsStyleSet'] == "BA") $query_style_name = sprintf("SELECT id,brewStyleGroup,brewStyleNum FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') ORDER BY brewStyleNum DESC LIMIT 1", $styles_db_table, $_SESSION['prefsStyleSet'], $category_end);

		else $query_style_name = sprintf("SELECT id,brewStyleGroup,brewStyleNum FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND brewStyleGroup >= %s ORDER BY id DESC LIMIT 1", $styles_db_table, $_SESSION['prefsStyleSet'], $category_end);

		$style_name = mysqli_query($connection,$query_style_name) or die (mysqli_error($connection));
		$row_style_name = mysqli_fetch_assoc($style_name);

		// Get the difference between the category end and the last number
		// $style_difference = ($row_style_name['brewStyleGroup'] - $category_end);
		$style_add_one = $row_style_name['brewStyleGroup'] + 1;
		if ($_SESSION['prefsStyleSet'] == "BA") $style_num_add_one = $row_style_name['brewStyleNum'] + 1;
		else $style_num_add_one = "A";

		$insertSQL = sprintf("INSERT INTO $styles_db_table (
		brewStyleNum,
		brewStyle,
		brewStyleOG,
		brewStyleOGMax,
		brewStyleFG,

		brewStyleFGMax,
		brewStyleABV,
		brewStyleABVMax,
		brewStyleIBU,
		brewStyleIBUMax,

		brewStyleSRM,
		brewStyleSRMMax,
		brewStyleType,
		brewStyleInfo,
		brewStyleLink,

		brewStyleGroup,
		brewStyleActive,
		brewStyleOwn,
		brewStyleVersion,
		brewStyleReqSpec,

		brewStyleStrength,
		brewStyleCarb,
		brewStyleSweet,
		brewStyleEntry
		)
		VALUES (
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s
		)",
					   GetSQLValueString($style_num_add_one, "text"),
					   GetSQLValueString($purifier->purify($_POST['brewStyle']), "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleOG']), "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleOGMax']), "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleFG']), "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleFGMax']), "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleABV']), "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleABVMax']), "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleIBU']), "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleIBUMax']), "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleSRM']), "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleSRMMax']), "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleType']), "text"),
					   GetSQLValueString($brewStyleInfo, "text"),
					   GetSQLValueString($brewStyleLink, "text"),
					   GetSQLValueString(sterilize($style_add_one), "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleActive']), "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleOwn']), "text"),
					   GetSQLValueString($_SESSION['prefsStyleSet'], "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleReqSpec']), "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleStrength']), "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleCarb']), "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleSweet']), "text"),
					   GetSQLValueString($brewStyleEntry, "text")
					   );

		// echo $insertSQL; exit;
		mysqli_real_escape_string($connection,$insertSQL);
		$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));

		$pattern = array('\'', '"');
		$insertGoTo = str_replace($pattern, "", $insertGoTo);
		$redirect_go_to = sprintf("Location: %s", stripslashes($insertGoTo));


	} // end if ($action == "add");

	if ($action == "edit") {

		$updateSQL = sprintf("UPDATE $styles_db_table SET
		brewStyleNum=%s,
		brewStyle=%s,
		brewStyleOG=%s,
		brewStyleOGMax=%s,
		brewStyleFG=%s,

		brewStyleFGMax=%s,
		brewStyleABV=%s,
		brewStyleABVMax=%s,
		brewStyleIBU=%s,
		brewStyleIBUMax=%s,

		brewStyleSRM=%s,
		brewStyleSRMMax=%s,
		brewStyleType=%s,
		brewStyleInfo=%s,
		brewStyleLink=%s,

		brewStyleGroup=%s,
		brewStyleActive=%s,
		brewStyleOwn=%s,
		brewStyleReqSpec=%s,
		brewStyleStrength=%s,
		brewStyleCarb=%s,
		brewStyleSweet=%s,
		brewStyleEntry=%s

		WHERE id=%s",
					   GetSQLValueString(sterilize($_POST['brewStyleNum']), "text"),
					   GetSQLValueString($purifier->purify($_POST['brewStyle']), "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleOG']), "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleOGMax']), "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleFG']), "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleFGMax']), "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleABV']), "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleABVMax']), "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleIBU']), "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleIBUMax']), "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleSRM']), "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleSRMMax']), "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleType']), "text"),
					   GetSQLValueString($brewStyleInfo, "text"),
					   GetSQLValueString($brewStyleLink, "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleGroup']), "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleActive']), "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleOwn']), "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleReqSpec']), "text"),
					   GetSQLValueString($brewStyleStrength, "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleCarb']), "text"),
					   GetSQLValueString(sterilize($_POST['brewStyleSweet']), "text"),
					   GetSQLValueString($brewStyleEntry, "text"),
					   GetSQLValueString($id, "int"));

	  	mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		$query_log = sprintf("SELECT id FROM $brewing_db_table WHERE brewStyle = '%s'",$_POST['brewStyleOld']);
		$log = mysqli_query($connection,$query_log) or die (mysqli_error($connection));
		$row_log = mysqli_fetch_assoc($log);
		$totalRows_log = mysqli_num_rows($log);

		do {

			$updateSQL = sprintf("UPDATE $brewing_db_table SET brewStyle='%s' WHERE id='%s'", $_POST['brewStyle'],$row_log['id']);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		} while ($row_log = mysqli_fetch_assoc($log));

		$pattern = array('\'', '"');
		$updateGoTo = str_replace($pattern, "", $updateGoTo);
		$redirect_go_to = sprintf("Location: %s", stripslashes($updateGoTo));
	}

} else {
	$redirect_go_to = sprintf("Location: %s", $base_url."index.php?msg=98");
}
?>