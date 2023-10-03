<?php
ob_start();

require('../paths.php');
require(CONFIG.'bootstrap.php');

ini_set('display_errors', 0); // Change to 0 for prod; change to 1 for testing.
ini_set('display_startup_errors', 0); // Change to 0 for prod; change to 1 for testing.
error_reporting(0); // Change to error_reporting(0) for prod; change to E_ALL for testing.


/**
 * The action variable cooresponds to a table in the DB.
 *
 * @param $id varable will be used as an identifier - either as the 
 * record id in the table or a relational component (bid, eid).
 * 
 * @param $ridX variables are for other relational vars.
 */

$rid1 = "default";
$rid2 = "default";
$rid3 = "default";
$rid4 = "default";

if (isset($_GET['rid1'])) $rid1 = sterilize($_GET['rid1']);
if (isset($_GET['rid2'])) $rid2 = sterilize($_GET['rid2']);
if (isset($_GET['rid3'])) $rid3 = sterilize($_GET['rid3']);
if (isset($_GET['rid4'])) $rid4 = sterilize($_GET['rid4']);

$return_json = array();
$status = 0;
$process = FALSE;
$sql = "";
$input = "";
$post = 0;
$error_type = 0;

$session_active = FALSE;
if ((isset($_SESSION['session_set_'.$prefix_session])) && (isset($_SESSION['loginUsername']))) $session_active = TRUE;

if (($session_active) && ($_SESSION['userLevel'] <= 2)) {

	if ($action == "evaluation") {

		if ($go == "evalPlace") {
			$input = filter_var($_POST['evalPlace'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			if (empty($input)) $data = array($go => NULL);
			else {
				if ($input == "0") $data = array($go => NULL);			
				else $data = array($go => $input);
			}
		}

		if ($go == "evalMiniBOS") {
			$input = filter_var($rid1,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			if (empty($input)) $data = array($go => 0);
			else $data = array($go => $input);
		}

		$update_table = $prefix.$action;

		$db_conn->where ('eid', $id);
		if ($db_conn->update ($update_table, $data)) $status = 1;
		else $error_type = 3; // SQL error

	} // end if ($action == "evaluation")

}

if (($session_active) && ($_SESSION['userLevel'] <= 1)) {

	if ($action == "brewing") {
		
		$eid = $id;
		if ($rid1 != "default") $brewBrewerID = $rid1;

		if ($go == "brewAdminNotes") {
			$input = filter_var($_POST['brewAdminNotes'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}

		if ($go == "brewStaffNotes") {
			$input = filter_var($_POST['brewStaffNotes'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}

		if ($go == "brewBoxNum") {
			$input = filter_var($_POST['brewBoxNum'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}

		if ($go == "brewJudgingNumber") {
			$post = str_replace("^","-",$_POST['brewJudgingNumber']);
			$input = filter_var($post,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$input = strtolower($input);
		}

		if ($go == "brewPaid") {
			$input = filter_var($_POST['brewPaid'],FILTER_SANITIZE_NUMBER_FLOAT);
		}

		if ($go == "brewReceived") {
			$input = filter_var($_POST['brewReceived'],FILTER_SANITIZE_NUMBER_FLOAT);
		}

		$update_table = $prefix."brewing";

		if (empty($input)) {

			if ($rid2 == "text-col") {
				$data = array($go => '', 'brewUpdated' => $db_conn->now());
			}

			else {
				$data = array($go => NULL, 'brewUpdated' => $db_conn->now()); 
			}

		}

		else {

			if ($input == "0") {
				$data = array($go => NULL, 'brewUpdated' => $db_conn->now());
			}

			else {
				$data = array($go => $input, 'brewUpdated' => $db_conn->now());
			}

		}

		$db_conn->where ('id', $id);
		if ($db_conn->update ($update_table, $data)) $status = 1;
		else $error_type = 3; // SQL error

	} // END if ($action == "brewing")

	if ($action == "sponsors") {

		if ($go == "sponsorEnable") {
			$input = filter_var($_POST['sponsorEnable'],FILTER_SANITIZE_NUMBER_FLOAT);
		}

		if ($go == "sponsorLevel") {
			$input = filter_var($_POST['sponsorLevel'],FILTER_SANITIZE_NUMBER_FLOAT);
		}

		if ($go == "sponsorText") {
			$input = filter_var($_POST['sponsorText'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}

		if ($go == "sponsorImage") {
			$input = filter_var($_POST['sponsorImage'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}

		$update_table = $prefix."sponsors";

		if (empty($input)) {
			if ($rid2 == "text-col")  $data = array($go => '');
			else $data = array($go => NULL);
		}

		else {
			if ($input == "0") $data = array($go => NULL); 
			else $data = array($go => $input);
		}

		$db_conn->where ('id', $id);
		if ($db_conn->update ($update_table, $data)) $status = 1;
		else $error_type = 3; // SQL error
		
	} // END if ($action == "sponsors")
	
	// judging_scores DB Table
	if (($action == "judging_scores") || ($action == "judging_scores_bos")) {

		$eid = $id;
		$bid = "";
		$scoreTable = "";
		$scoreType = "";
		$scoreEntry = NULL;
		$scorePlace = NULL;
		$scoreMiniBOS = NULL;
		
		if ($rid1 != "default") $bid = $rid1;
		if ($rid2 != "default") $scoreTable = $rid2;
		if ($rid3 != "default") $scoreType = $rid3;

		if ($go == "scoreEntry") $post = $_POST['scoreEntry'];
		if ($go == "scorePlace") $post = $_POST['scorePlace'];
		if (($go == "scoreMiniBOS") && (!empty($_POST['scoreMiniBOS']))) $post = $_POST['scoreMiniBOS'];

		if ((empty($post)) || ($post == "null")) $post = 0;

		if (is_numeric($post)) {

			// For scores, all ajax input will be an integer - filter as such
			$input = filter_var($post,FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);

			// However, if that number is actually zero, make the value null instead for storage in DB
			if ($input == 0) $input = NULL;
			
			// First, query if there is a record with the eid
			$query_already_scored = sprintf("SELECT * FROM %s WHERE eid=%s", $prefix.$action, $eid);
			$already_scored = mysqli_query($connection,$query_already_scored) or die (mysqli_error($connection));
			$row_already_scored = mysqli_fetch_assoc($already_scored);
			$totalRows_already_scored = mysqli_num_rows($already_scored);

			if ($totalRows_already_scored == 1) {				
				
				$process = TRUE;

				$update_table = $prefix.$action;
				$data = array($go => $input);

				if ($process) {
					$db_conn->where ('id', $row_already_scored['id']);
					if ($db_conn->update ($update_table, $data)) $status = 1;
				}
				else $error_type = 3; // SQL error

			}

			else if ($totalRows_already_scored == 0) {

				if (($action == "judging_scores") && ($rid1 != "default") && ($rid2 != "default") && ($rid3 != "default")) $process = TRUE;
				if (($action == "judging_scores_bos") && ($rid1 != "default") && ($rid3 != "default")) $process = TRUE;
				if ($go == "scoreEntry") $scoreEntry = $input;	
				if ($go == "scorePlace") $scorePlace = $input;		
				if ($go == "scoreMiniBOS") $scoreMiniBOS = $input;

				$update_table = $prefix.$action;

				if ($action == "judging_scores") {

					$data = array(
						'eid' => $eid,
						'bid' => $bid,
						'scoreTable' => $scoreTable,
						'scoreEntry' => $scoreEntry,
						'scorePlace' => $scorePlace,
						'scoreType' => $scoreType,
						'scoreMiniBOS' => $scoreMiniBOS
					);

					// $sql = sprintf("INSERT INTO %s (eid, bid, scoreTable, scoreEntry, scorePlace, scoreType, scoreMiniBOS)", $prefix.$action);

					if ($process) {
						if ($db_conn->insert ($update_table, $data)) $status = 1;
					}

					else $error_type = 3; // SQL error

				}

				if ($action == "judging_scores_bos") {

					$data = array(
						'eid' => $eid,
						'bid' => $bid,
						'scoreEntry' => $scoreEntry,
						'scorePlace' => $scorePlace,
						'scoreType' => $scoreType
					);

					if ($process) {
						if ($db_conn->insert ($update_table, $data)) $status = 1;
					}

					else $error_type = 3; // SQL error

				}

			}

			// If more than one in the DB, perform some functions
			else {
				if (($rid1 != "default") && ($rid2 != "default") && ($rid3 != "default")) $process = TRUE;
			}

		} // END if (is_numeric($post))

		else {
			$error_type = 1;
		}

	} // END if ($action == "scores")

}

if (!$session_active) $status = 9; // Session expired, not enabled, etc.

$return_json = array(
	"status" => "$status",
	"query" => "$sql",
	"post" => "$post",
	"input" => "$input",
	"error_type" => "$error_type"
);

// Return the json
echo json_encode($return_json);

/**  
 * The following is unfinished. Need more
 * thought into the various scenarios
 * associated with assigning judges and 
 * stewards to tables/flights/rounds.
 */

/*
if ($action == "judging_assignments") {

	if ($go == "assignFlight") {

		// https://test.brewcomp.com/ajax/save.ajax.php?action=judging_assignments&go=assignTable&id=212&rid1=1&rid2=J&rid3=1
		// $id = judge's uid
		// $rid1 = table id
		// $rid2 = judge or steward
		// $rid3 = round
		// $rid4 = assigned location
		// $_POST['assignFlight']

		// Do query if judge is already assigned in their specified role
		$query_already_assigned = sprintf("SELECT * FROM %s WHERE bid='%s' AND assignment='%s'", $prefix.$action, $id, $rid2);
		$already_assigned = mysqli_query($connection,$query_already_assigned) or die (mysqli_error($connection));
		$row_already_assigned = mysqli_fetch_assoc($already_assigned);
		$totalRows_already_assigned = mysqli_num_rows($already_assigned);

		// If no record of the user assigned to any table as either a judge or steward,
		// simply add a record.
		if ($totalRows_already_assigned == 0) {
			if ($_POST['assignFlight'] > 0) $sql .= sprintf("INSERT INTO `%s` (bid, assignment, assignTable, assignFlight, assignRound, assignLocation) VALUES (%s, %s, %s, %s, %s, %s)", $prefix.$action, $id, $rid2, $rid1, $_POST['assignFlight'], $rid3, $rid4);
		}

		// Otherwise, loop through user's assignments for the role
		
		else {

			do {

				// If assigned to the same round, check to see if the user chose to assign to current table. 
				// If the choice is to assign to current table, delete the previous record and add a new one with this assignment.
				// If the choice is NOT assign to current table, clear any records that may be there

				// Check if assigned to this round and table
				if (($row_already_assigned['assignRound'] == $rid3) && ($row_already_assigned['assignTable'] == $rid1)) {

					// Check to see if the user chose to assign. If so, update the record.
					if ($_POST['assignFlight'] > 0) {

						$sql .= sprintf("UPDATE `%s` SET assignFlight='%s' WHERE bid='%s' AND assignTable='%s' AND assignRound='%s'", $prefix.$action, $_POST['assignFlight'], $id, $rid1, $rid3);

						echo $sql."<br>";

					}

					// If the choice is NOT assign to current table, clear any records that may be there
					if ($_POST['assignFlight'] == 0) {
						$sql = sprintf("DELETE FROM `%s` WHERE bid='%s' AND assignTable='%s' AND assignRound='%s' AND assignFlight='%s'", $prefix.$action, $rid2, $id, $rid1, $rid3, $_POST['assignFlight']);

						echo $sql."<br>";
					}
				
				}

				// Check if assigned to this round at another table, but at same location
				if (($row_already_assigned['assignRound'] == $rid3) && ($row_already_assigned['assignTable'] != $rid1) && ($row_already_assigned['assignLocation'] == $rid4)) {
					
					// if ($_POST['assignFlight'] > 0) $sql .= sprintf("INSERT INTO `%s` (bid, assignment, assignTable, assignFlight, assignRound) VALUES (%s, %s, %s, %s, %s)", $prefix.$action, $id, $rid2, $rid1, $_POST['assignFlight'], $rid3);
				}

			} while ($row_already_assigned = mysqli_fetch_assoc($already_assigned));

		}

	}

	if ($go == "assignRoles") {
		
		$input = filter_var($rid1,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		
		if (empty($input)) {
			$sql = sprintf("UPDATE `%s` SET %s=NULL WHERE assignTable='%s'", $prefix.$action, $go, $id);
		}

		else {

			// Check if ID is assigned to the table, if so, change
			// If not, flag
			$sql = sprintf("UPDATE `%s` SET %s='HJ' WHERE bid='%s' AND assignTable='%s'", $prefix.$action, $go, $input, $id);

		}

		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));

	} // end if ($go == "assignRoles")

	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));

	// If successful, change $status from fail (0) to success (1)
	if ($result) $status = 1;
	else $error_type = 3; // SQL error

}

*/

?>