<?php
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require('../paths.php');
require(CONFIG.'bootstrap.php');

/**
 * The action variable cooresponds to a table in the DB.
 *
 * The id varable will be used as an identifier - either as the 
 * record id in the table or a relational component (bid, eid).
 * 
 * $ridX variables are for other relational vars
 */

// For use in ajax URLS
$rid1 = "default";
if (isset($_GET['rid1'])) {
  $rid1 = (get_magic_quotes_gpc()) ? $_GET['rid1'] : addslashes($_GET['rid1']);
}

$rid2 = "default";
if (isset($_GET['rid2'])) {
  $rid2 = (get_magic_quotes_gpc()) ? $_GET['rid2'] : addslashes($_GET['rid2']);
}

$rid3 = "default";
if (isset($_GET['rid3'])) {
  $rid3 = (get_magic_quotes_gpc()) ? $_GET['rid3'] : addslashes($_GET['rid3']);
}

$rid4 = "default";
if (isset($_GET['rid4'])) {
  $rid4 = (get_magic_quotes_gpc()) ? $_GET['rid4'] : addslashes($_GET['rid4']);
}

if ((isset($_SESSION['session_set_'.$prefix_session])) && (isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {

	$return_json = array();
	$status = 0;
	$process = FALSE;
	$sql = "";
	$input = "";
	$post = 0;
	$error_type = 0;

	// brewing (entries) DB table
	if ($action == "brewing") {
		
		$eid = $id;
		if ($rid1 != "default") $brewBrewerID = $rid1;

		if ($go == "brewAdminNotes") {
			$input = filter_var($_POST['brewAdminNotes'],FILTER_SANITIZE_STRING);
		}

		if ($go == "brewStaffNotes") {
			$input = filter_var($_POST['brewStaffNotes'],FILTER_SANITIZE_STRING);
		}

		if ($go == "brewBoxNum") {
			$input = filter_var($_POST['brewBoxNum'],FILTER_SANITIZE_STRING);
		}

		if ($go == "brewJudgingNumber") {
			$post = str_replace("^","-",$_POST['brewJudgingNumber']);
			$input = filter_var($post,FILTER_SANITIZE_STRING);
			$input = strtolower($input);
		}

		if ($go == "brewPaid") {
			$input = filter_var($_POST['brewPaid'],FILTER_SANITIZE_NUMBER_FLOAT);
		}

		if ($go == "brewReceived") {
			$input = filter_var($_POST['brewReceived'],FILTER_SANITIZE_NUMBER_FLOAT);
		}

		if (empty($input)) {
			if ($rid2 == "text-col") $sql = sprintf("UPDATE `%s` SET %s='' WHERE id=%s", $prefix.$action, $go, $id);
			else $sql = sprintf("UPDATE `%s` SET %s=NULL WHERE id=%s", $prefix."brewing", $go, $id);
		}

		else {
			if ($input == "0") $sql = sprintf("UPDATE `%s` SET %s=NULL WHERE id=%s", $prefix.$action, $go, $id);
			else $sql = sprintf("UPDATE `%s` SET %s='%s' WHERE id=%s", $prefix."brewing", $go, $input, $id);
		}

		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
		
		// If successful, change $status from fail (0) to success (1)
		if ($result) $status = 1;
		else $error_type = 3; // SQL error

	}

	if ($action == "sponsors") {

		if ($go == "sponsorEnable") {
			$input = filter_var($_POST['sponsorEnable'],FILTER_SANITIZE_NUMBER_FLOAT);
		}

		if ($go == "sponsorLevel") {
			$input = filter_var($_POST['sponsorEnable'],FILTER_SANITIZE_NUMBER_FLOAT);
		}

		if ($go == "sponsorText") {
			$input = filter_var($_POST['sponsorText'],FILTER_SANITIZE_STRING);
		}

		if ($go == "sponsorLogo") {
			$input = filter_var($_POST['sponsorText'],FILTER_SANITIZE_STRING);
		}

		if (empty($input)) {
			if ($rid2 == "text-col") $sql = sprintf("UPDATE `%s` SET %s='' WHERE id=%s", $prefix.$action, $go, $id);
			else $sql = sprintf("UPDATE `%s` SET %s=NULL WHERE id=%s", $prefix."brewing", $go, $id);
		}

		else {
			if ($input == "0") $sql = sprintf("UPDATE `%s` SET %s=NULL WHERE id=%s", $prefix.$action, $go, $id);
			else $sql = sprintf("UPDATE `%s` SET %s='%s' WHERE id=%s", $prefix."brewing", $go, $input, $id);
		}

		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
		
		// If successful, change $status from fail (0) to success (1)
		if ($result) $status = 1;
		else $error_type = 3; // SQL error
		
	}
	
	// judging_scores DB Table
	if (($action == "judging_scores") || ($action == "judging_scores_bos")) {

		$eid = $id;
		$bid = "";
		$scoreTable = "";
		$scoreType = "";
		$scoreEntry = "NULL";
		$scorePlace = "NULL";
		$scoreMiniBOS = "NULL";
		
		if ($rid1 != "default") $bid = $rid1;
		if ($rid2 != "default") $scoreTable = $rid2;
		if ($rid3 != "default") $scoreType = $rid3;

		if ($go == "scoreEntry") $post = $_POST['scoreEntry'];
		if ($go == "scorePlace") $post = $_POST['scorePlace'];
		if (($go == "scoreMiniBOS") && (!empty($_POST['scoreMiniBOS']))) $post = $_POST['scoreMiniBOS'];

		if ((empty($post)) || ($post == "null")) $post = 0;

		if (is_numeric($post)) {

			// For scores, all ajax input will be an integer - filter as such
			$input = filter_var($post,FILTER_SANITIZE_NUMBER_FLOAT);

			// However, if that number is actually zero, make the value null instead for storage in DB
			if ($input == 0) $input = "NULL";
			
			// First, query if there is a record with the eid
			$query_already_scored = sprintf("SELECT * FROM %s WHERE eid=%s", $prefix.$action, $eid);
			$already_scored = mysqli_query($connection,$query_already_scored) or die (mysqli_error($connection));
			$row_already_scored = mysqli_fetch_assoc($already_scored);
			$totalRows_already_scored = mysqli_num_rows($already_scored);

			// If so, and only one is present, create update query (only update the column specified).
			if ($totalRows_already_scored == 1) {
				$process = TRUE;
				$sql = sprintf("UPDATE %s SET %s=%s WHERE id=%s", $prefix.$action, $go, $input, $row_already_scored['id']);
			}

			// If no record, create an insert query
			else if ($totalRows_already_scored == 0) {

				if (($action == "judging_scores") && ($rid1 != "default") && ($rid2 != "default") && ($rid3 != "default")) $process = TRUE;
				if (($action == "judging_scores_bos") && ($rid1 != "default") && ($rid3 != "default")) $process = TRUE;
				if ($go == "scoreEntry") $scoreEntry = $input;	
				if ($go == "scorePlace") $scorePlace = $input;		
				if ($go == "scoreMiniBOS") $scoreMiniBOS = $input;
				
				if ($action == "judging_scores") {
					$sql = sprintf("INSERT INTO %s (eid, bid, scoreTable, scoreEntry, scorePlace, scoreType, scoreMiniBOS)", $prefix.$action);
					$sql .= sprintf(" VALUES (%s, %s, %s, %s, %s, %s, %s)", $eid, $bid, $scoreTable, $scoreEntry, $scorePlace, $scoreType, $scoreMiniBOS);
				}

				if ($action == "judging_scores_bos") {
					$sql = sprintf("INSERT INTO %s (eid, bid, scoreEntry, scorePlace, scoreType)", $prefix.$action);
					$sql .= sprintf(" VALUES (%s, %s, %s, %s, %s)", $eid, $bid, $scoreEntry, $scorePlace, $scoreType);
				}
			
			}
			
			// If more than one in the DB, perform some functions
			else {

				if (($rid1 != "default") && ($rid2 != "default") && ($rid3 != "default")) $process = TRUE;

				/*
				$retain_arr = array();

				do {
					
					$retain_arr[] = array(
						"eid" => $row_already_scored['eid'],
						"bid" => $row_already_scored['bid'],
						"scoreTable" => $row_already_scored['scoreTable'],
						"scoreEntry" => $row_already_scored['scoreEntry'],
						"scorePlace" => $row_already_scored['scorePlace'],
						"scoreMiniBOS" => $row_already_scored['scoreMiniBOS']
					);

					$deleteSQL = sprintf("DELETE FROM %s WHERE id='%s'", $prefix."judging_scores", $row_delete_assign['id']);
					mysqli_real_escape_string($connection,$deleteSQL);
					$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));
				
				} 

				while ($row_already_scored = mysqli_fetch_assoc($already_scored));

				*/

			}

			// Finally, submit the query if all conditions to process are met
			if ($process) {
				mysqli_real_escape_string($connection,$sql);
				$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
				// If successful, change $status from fail (0) to success (1)
				if ($result) $status = 1;
			}

			else {
				$error_type = 3; // SQL error
			}

		} // end if (is_numeric($post))

		else {
			$error_type = 1;
		}

	}

	$return_json = array(
		"status" => "$status",
		"query" => "$sql",
		"post" => "$post",
		"input" => "$input",
		"error_type" => "$error_type"
	);

	// Return the json
	echo json_encode($return_json);

} else echo "<p>Not allowed.</p>"; // end if session is set

	
?>