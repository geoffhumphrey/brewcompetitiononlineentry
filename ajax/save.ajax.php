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

	if ($action == "styles") {

		$styles_db_table = $prefix."styles";

		if ($go == "brewStyleAtLimit") {

			$input = sterilize($_POST['brewStyleAtLimit']);
			$data = array('brewStyleAtLimit' => (!empty($input)) ? 1 : NULL);

			$db_conn->where ('id', $id);
			if ($db_conn->update ($styles_db_table, $data)) $status = 1;
			else $error_type = 3; // SQL error

		} // end if ($go == "brewStyleAtLimit")

		if ($go == "brewStyleActive") {

			$input = sterilize($_POST['brewStyleActive']);

			// "Accepted" styles aren't a column on the styles table itself -
			// they're a JSON map (id => style summary) on
			// {prefix}preferences.prefsSelectedStyles, the same one
			// includes/process/process_styles.inc.php's batch "update"
			// action (the Update Accepted Styles fallback form) maintains.
			//
			// A "select all" click fires this save for every visible style
			// at once, so a PHP-side read-decode-modify-encode-write here
			// (read the JSON, change one key, write the whole thing back)
			// would lose updates: many concurrent requests each read the
			// same starting snapshot, and whichever finishes last overwrites
			// the DB with a version that only reflects its own change, not
			// the others' - the exact "each row said Saved, but nothing
			// stuck" symptom. JSON_MERGE_PATCH (RFC 7396: a key set to null
			// in the patch removes that key; any other key is added/
			// replaced) pushes the single-key add/remove into one atomic
			// UPDATE that MySQL/MariaDB itself row-locks, so concurrent
			// saves for different styles can never clobber each other.
			// (JSON_SET/JSON_REMOVE would work too, but JSON_SET's non-JSON
			// argument needs CAST(... AS JSON) to be treated as a document
			// rather than a scalar string - syntax MariaDB doesn't support -
			// so JSON_MERGE_PATCH's whole-document patch argument, which
			// needs no such cast, is used for both directions instead.)
			if (!empty($input)) {

				$db_conn->where("id", $id);
				$row_style = $db_conn->getOne($styles_db_table, "id, brewStyle, brewStyleGroup, brewStyleNum, brewStyleVersion, brewStyleType");

				if ($row_style) {
					$patch = json_encode(array((string)$id => array(
						'id' => $row_style['id'],
						'brewStyle' => sterilize($row_style['brewStyle']),
						'brewStyleGroup' => sterilize($row_style['brewStyleGroup']),
						'brewStyleNum' => sterilize($row_style['brewStyleNum']),
						'brewStyleVersion' => sterilize($row_style['brewStyleVersion']),
						'brewStyleType' => $row_style['brewStyleType']
					)));
				}

			}

			else {
				$patch = json_encode(array((string)$id => null));
			}

			if (!empty($patch)) {
				$sql = "UPDATE ".$prefix."preferences SET prefsSelectedStyles = JSON_MERGE_PATCH(COALESCE(NULLIF(prefsSelectedStyles, ''), '{}'), ?) WHERE id = 1";
				$db_conn->rawQuery($sql, array($patch));
				if ($db_conn->getLastErrno() === 0) {
					$status = 1;
					// $_SESSION['prefsSelectedStyles'] (and every other
					// prefs* session var) is only ever reloaded from the DB
					// when $_SESSION['prefs'.$prefix_session] is unset -
					// includes/db/common.db.php's cache guard - so this
					// direct DB write would otherwise leave the admin's own
					// session showing stale (pre-save) checkbox state on
					// every page view until they log out, no matter how
					// many successful saves happen. Unsetting it here forces
					// exactly one fresh reload on the next page load, same
					// as the batch fallback in process_styles.inc.php
					// already does after its own save.
					unset($_SESSION['prefs'.$prefix_session]);
				}
				else $error_type = 3; // SQL error
			}

		} // end if ($go == "brewStyleActive")

		// Batched counterparts of the two handlers above, used by a
		// "select all" checkbox on index.php?section=admin&go=styles:
		// applying the SAME new value to every affected style as one SQL
		// statement instead of one request (and one MyISAM table-lock
		// acquisition - {prefix}styles and {prefix}preferences are both
		// MyISAM, table-level-locked on every write) per style. Confirmed
		// live: one row at a time, "select all" on a ~195-style set took
		// ~25 seconds and, sent concurrently instead of sequentially,
		// occasionally dropped a handful of saves outright once the DB's
		// max_connections was exceeded. Folding the whole batch into a
		// single statement removes both problems - there's only ever one
		// request and one lock acquisition, no matter how many rows.
		if (($go == "brewStyleAtLimitAll") || ($go == "brewStyleActiveAll")) {

			$ids = json_decode($_POST['ids'] ?? '[]', true);
			$ids = is_array($ids) ? array_values(array_filter(array_map('intval', $ids))) : array();

			if (!empty($ids)) {

				if ($go == "brewStyleAtLimitAll") {

					$input = sterilize($_POST['brewStyleAtLimit']);
					$data = array('brewStyleAtLimit' => (!empty($input)) ? 1 : NULL);

					$db_conn->where('id', $ids, 'IN');
					if ($db_conn->update($styles_db_table, $data)) $status = 1;
					else $error_type = 3; // SQL error

				}

				if ($go == "brewStyleActiveAll") {

					$input = sterilize($_POST['brewStyleActive']);

					// Confirmed live (2026-09-15): MariaDB 10.4.32's
					// JSON_MERGE_PATCH() intermittently drops one key
					// (observed at different positions in different runs,
					// not consistently "the first key") whenever the
					// removals it's asked to apply bring the resulting
					// document close to/exactly empty - reproduced
					// deterministically outside this app via a plain
					// SELECT JSON_MERGE_PATCH(doc, patch) against the real
					// prefsSelectedStyles document, including when chunked
					// into smaller per-call patches (the LAST chunk still
					// drains the document to near-empty, since the earlier
					// chunks already removed most of it) - an engine
					// limitation, not an application bug, and not one
					// avoidable by staying under some patch-size threshold.
					//
					// The single-style handler above still uses
					// JSON_MERGE_PATCH, because it genuinely needs
					// concurrency-safety against OTHER simultaneous single-
					// style saves (e.g. a second browser tab). This batch
					// endpoint doesn't have that problem to begin with -
					// it's already exactly one request for the whole
					// "select all" click, so there's nothing else to race
					// against - so it reads, modifies in PHP, and writes
					// back the whole document in one UPDATE instead,
					// sidestepping the buggy function entirely.
					$db_conn->where('id', 1);
					$row_prefs_selected_styles = $db_conn->getOne($prefix."preferences", "prefsSelectedStyles");
					$selected_styles = json_decode($row_prefs_selected_styles['prefsSelectedStyles'] ?? '', true);
					if (!is_array($selected_styles)) $selected_styles = array();

					if (!empty($input)) {

						$db_conn->where('id', $ids, 'IN');
						$rows_style_batch = $db_conn->get($styles_db_table, null, "id, brewStyle, brewStyleGroup, brewStyleNum, brewStyleVersion, brewStyleType");

						if ($rows_style_batch) {
							foreach ($rows_style_batch as $row_style_batch) {
								$selected_styles[$row_style_batch['id']] = array(
									'id' => $row_style_batch['id'],
									'brewStyle' => sterilize($row_style_batch['brewStyle']),
									'brewStyleGroup' => sterilize($row_style_batch['brewStyleGroup']),
									'brewStyleNum' => sterilize($row_style_batch['brewStyleNum']),
									'brewStyleVersion' => sterilize($row_style_batch['brewStyleVersion']),
									'brewStyleType' => $row_style_batch['brewStyleType']
								);
							}
						}

					}

					else {
						foreach ($ids as $batch_id) unset($selected_styles[$batch_id]);
					}

					$data = array('prefsSelectedStyles' => json_encode($selected_styles));
					$db_conn->where('id', 1);
					if ($db_conn->update($prefix."preferences", $data)) {
						$status = 1;
						// See the single-style brewStyleActive handler
						// above for why this is needed.
						unset($_SESSION['prefs'.$prefix_session]);
					}
					else $error_type = 3; // SQL error

				}

			}

		} // end if brewStyleAtLimitAll / brewStyleActiveAll

	} // END if ($action == "styles")

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