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
			$input = sterilize($_POST['evalPlace']);
			if (empty($input)) $data = array($go => NULL);
			else {
				if ($input == "0") $data = array($go => NULL);			
				else $data = array($go => $input);
			}
		}

		if ($go == "evalMiniBOS") {
			$input = sterilize($rid1);
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
			$input = sterilize($_POST['brewAdminNotes']);
		}

		if ($go == "brewStaffNotes") {
			$input = sterilize($_POST['brewStaffNotes']);
		}

		if ($go == "brewBoxNum") {
			$input = sterilize($_POST['brewBoxNum']);
		}

		if ($go == "brewJudgingNumber") {
			$post = str_replace("^","-",$_POST['brewJudgingNumber']);
			$input = sterilize($post);
			$input = strtolower($input);
		}

		if ($go == "brewPaid") {
			$input = sterilize($_POST['brewPaid']);
		}

		if ($go == "brewReceived") {
			$input = sterilize($_POST['brewReceived']);
		}

		$update_table = $prefix."brewing";

		if (empty($input)) {

			if ($rid2 == "text-col") {
				$data = array($go => '', 'brewUpdated' => date('Y-m-d H:i:s', time()));
			}

			else {
				$data = array($go => NULL, 'brewUpdated' => date('Y-m-d H:i:s', time()));
			}

		}

		else {

			if ($input == "0") {
				$data = array($go => NULL, 'brewUpdated' => date('Y-m-d H:i:s', time()));
			}

			else {
				$data = array($go => $input, 'brewUpdated' => date('Y-m-d H:i:s', time()));
			}

		}

		$db_conn->where ('id', $id);
		if ($db_conn->update ($update_table, $data)) $status = 1;
		else $error_type = 3; // SQL error

	} // END if ($action == "brewing")

	if ($action == "sponsors") {

		if ($go == "sponsorEnable") {
			$input = sterilize($_POST['sponsorEnable']);
		}

		if ($go == "sponsorLevel") {
			$input = sterilize($_POST['sponsorLevel']);
		}

		if ($go == "sponsorText") {
			$input = sterilize($_POST['sponsorText']);
		}

		if ($go == "sponsorImage") {
			$input = sterilize($_POST['sponsorImage']);
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

	if ($action == "judging_staff") {

		$input = "";
		$update_table = $prefix."staff";

		if ($go == "staff_judge") $input = sterilize($_POST['staff_judge']);
		if ($go == "staff_steward") $input = sterilize($_POST['staff_steward']);
		if ($go == "staff_staff") $input = sterilize($_POST['staff_staff']);
		if ($go == "staff_judge_bos") $input = sterilize($_POST['staff_judge_bos']);
		
		if ($go == "staff_organizer") {

			$input = sterilize($_POST['staff_organizer']);
			$uid = $input;

			if (!empty($input)) {

				$uid = $input;

				// Clear organizer from the staff table
				$data = array('staff_organizer' => 0);
				$result = $db_conn->update ($update_table, $data);

				$query_org = sprintf("SELECT uid FROM %s WHERE uid='%s'", $prefix."staff", $uid);
				$org = mysqli_query($connection,$query_org) or die (mysqli_error($connection));
				$row_org = mysqli_fetch_assoc($org);
				$totalRows_org = mysqli_num_rows($org);
				
				if ($totalRows_org == 0) {
					$data = array(
						'staff_organizer' => 1,
						'staff_staff' => 0,
						'staff_judge' => 0,
						'staff_judge_bos' => 0,
						'uid' => $uid
					);
					if ($db_conn->insert ($update_table, $data)) $status = 1;
					else $error_type = 3; // SQL error
				}

				else {

					if ($uid == $row_org['uid']) {

						$data = array(
							'staff_organizer' => 1,
							'staff_staff' => 0,
							'staff_judge' => 0,
							'staff_judge_bos' => 0
						);
						$db_conn->where ('uid', $uid);
						if ($db_conn->update ($update_table, $data)) $status = 1;
						else $error_type = 3; // SQL error

					}

					else $error_type = 3; // SQL error
					
				}
				

			}

			else $error_type = 3;
			
		}

		else {

			if ((empty($input)) || ($input == 0)) $data = array($go => 0);
			else $data = array($go => $input);
			$db_conn->where ('uid', $id);
			if ($db_conn->update ($update_table, $data)) $status = 1;
			else $error_type = 3; // SQL error
			
			if (($go == "staff_judge") || ($go == "staff_steward")) {

				// Unassign from any tables
				if ((empty($input)) || ($input == 0)) {

					if ($go == "staff_judge") $query_table_assign = sprintf("SELECT id FROM %s WHERE bid='%s' AND assignment='J'",$prefix."judging_assignments",$id);
					if ($go == "staff_steward") $query_table_assign = sprintf("SELECT id FROM %s WHERE bid='%s' AND assignment='S'",$prefix."judging_assignments",$id);
					$table_assign = mysqli_query($connection,$query_table_assign) or die (mysqli_error($connection));
					$row_table_assign = mysqli_fetch_assoc($table_assign);
					$totalRows_table_assign = mysqli_num_rows($table_assign);

					if ($totalRows_table_assign > 0) {

						do {

							$update_table = $prefix."judging_assignments";
							$db_conn->where ('id', $row_table_assign['id']);
							$result = $db_conn->delete($update_table);

						} while ($row_table_assign = mysqli_fetch_assoc($table_assign));
						
					}

				}
			
			}

		}		

	}
	
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
			$input = sterilize($post);

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
		
		$input = sterilize($rid1);
		
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