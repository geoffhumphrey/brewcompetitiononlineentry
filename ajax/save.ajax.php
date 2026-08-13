<?php
declare(strict_types=1);

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

// CSRF: require a same-origin Referer for these session-authenticated write actions.
$referrer_ok = (isset($_SERVER['HTTP_REFERER'])) && (parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) === $_SERVER['SERVER_NAME']);

if (($session_active) && ($_SESSION['userLevel'] <= 2) && ($referrer_ok)) {

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

if (($session_active) && ($_SESSION['userLevel'] <= 1) && ($referrer_ok)) {

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

		$update_table = $prefix."staff";

		if ($go == "staff_judge") $post = sterilize($_POST['staff_judge']);
		if ($go == "staff_steward") $post = sterilize($_POST['staff_steward']);
		if ($go == "staff_staff") $post = sterilize($_POST['staff_staff']);
		if ($go == "staff_judge_bos") $post = sterilize($_POST['staff_judge_bos']);
		
		if ($go == "staff_organizer") {

			$uid = sterilize($_POST['staff_organizer']);

			if (!empty($uid)) {

				// Clear organizer from the staff table
				$data = array('staff_organizer' => 0);
				$result = $db_conn->update ($update_table, $data);

				$db_conn->where ("uid", $uid);
				$row_org = $db_conn->getOne ($update_table, null, "uid");
				$totalRows_org = $db_conn->count;
				
				if ($totalRows_org == 0) {
					
					$data = array(
						'staff_organizer' => 1,
						'staff_staff' => 0,
						'staff_judge' => 0,
						'staff_judge_bos' => 0,
						'staff_steward' => 0,
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
							'staff_judge_bos' => 0,
							'staff_steward' => 0
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

			if ((empty($post)) || ($post == 0)) $post = 0;
			else $post = 1;

			$staff_organizer = 0;
			$staff_staff = 0;
			$staff_judge = 0;
			$staff_judge_bos = 0;
			$staff_steward = 0;

			if ($go == "staff_staff") $staff_staff = $post;
			if ($go == "staff_judge") $staff_judge = $post;
			if ($go == "staff_steward") $staff_steward = $post;

			$db_conn->where ("uid", $id);
			$row_staff_assign = $db_conn->getOne ($update_table, null, "uid");
			$totalRows_staff_assign = $db_conn->count;

			if ($totalRows_staff_assign == 0) {

				$data = array(
					'staff_organizer' => $staff_organizer,
					'staff_staff' => $staff_staff,
					'staff_judge' => $staff_judge,
					'staff_judge_bos' => $staff_judge_bos,
					'staff_steward' => $staff_steward,
					'uid' => $id
				);
				if ($db_conn->insert ($update_table, $data)) $status = 1;
				else $error_type = 3; // SQL error

			}

			else {

				$data = array($go => $post);
				$db_conn->where ('uid', $id);
				if ($db_conn->update ($update_table, $data)) $status = 1;
				else $error_type = 3; // SQL error

			}
			
			if (($go == "staff_judge") || ($go == "staff_steward")) {

				// Unassign from any tables
				if ((empty($post)) || ($post == 0)) {

					$db_conn->where ("bid", $id);
					if ($go == "staff_judge") $db_conn->where ("assignment", "J");
					if ($go == "staff_steward") $db_conn->where ("assignment", "S");
					$rows_table_assign = $db_conn->get ($prefix."judging_assignments", null, "id");
					$totalRows_table_assign = $db_conn->count;

					if ($totalRows_table_assign > 0) {

						foreach ($rows_table_assign as $row_table_assign) {

							$update_table = $prefix."judging_assignments";
							$db_conn->where ('id', $row_table_assign['id']);
							$result = $db_conn->delete($update_table);

						}

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
			$db_conn->where ("eid", $eid);
			$row_already_scored = $db_conn->getOne ($prefix.$action);
			$totalRows_already_scored = $db_conn->count;

			// If so, update the row
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

			// If not, add a row
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
	"id" => $id,
	"error_type" => "$error_type"
);

// Return the json
echo json_encode($return_json);

?>