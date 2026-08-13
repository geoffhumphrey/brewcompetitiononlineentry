<?php
/**
 * Module:      brewer.db.php
 * Description: This module houses all participant (brewer) related queries
 */

if (isset($_SESSION['user_id'])) {

	include (DB.'admin_participants.db.php');

	if (($section == "admin") && (($go == "brewer") || ($go == "user"))) {

		$db_conn->where("id", $id);
		$rows_brewer = $db_conn->get($brewer_db_table);
		$row_brewer = ($rows_brewer && count($rows_brewer) > 0) ? $rows_brewer[0] : null;
		$totalRows_brewer = $db_conn->count;

	}

	if (NHC) {
		// Custom code for AHA - possiblity of inclusion in a future version
		$db_conn->orderBy("IDClub", "ASC");
		$rows_clubs = $db_conn->get("nhcclubs");
		$row_clubs = ($rows_clubs && count($rows_clubs) > 0) ? $rows_clubs[0] : null;
	}

	// Editing a single participant query
	if (($section == "brewer") && ($action == "edit") && ($id == "default")) {
		$db_conn->where("uid", $_SESSION['user_id']);
		$rows_brewer = $db_conn->get($brewer_db_table);
		$row_brewer = ($rows_brewer && count($rows_brewer) > 0) ? $rows_brewer[0] : null;
		$totalRows_brewer = $db_conn->count;
	}

	elseif ($section == "notes") {

		// @single
		$db_conn->where("brewerJudgeNotes IS NOT NULL");
		$rows_brewer = $db_conn->get($brewer_db_table);
		$row_brewer = ($rows_brewer && count($rows_brewer) > 0) ? $rows_brewer[0] : null;
		$totalRows_brewer = $db_conn->count;
	}

	elseif (($section == "brewer") && ($action == "edit") && ($id != "default")) {
		$db_conn->where("id", $id);
		$rows_brewer = $db_conn->get($brewer_db_table);
		$row_brewer = ($rows_brewer && count($rows_brewer) > 0) ? $rows_brewer[0] : null;
		$totalRows_brewer = $db_conn->count;
	}

	elseif ($section == "pay") {
		$db_conn->where("uid", $_SESSION['user_id']);
		$rows_brewer = $db_conn->get($brewer_db_table);
		$row_brewer = ($rows_brewer && count($rows_brewer) > 0) ? $rows_brewer[0] : null;
		$totalRows_brewer = $db_conn->count;
	}

	// Viewing all participants in current comp DB query
	elseif ((($section == "admin") && ($go == "participants") && ($filter == "default")  && ($dbTable == "default")) || ($section == "participant_summary") || ($section == "particpant-entries")) {
		$sql = sprintf("SELECT a.*, b.id AS user_id, b.user_name, b.userLevel, b.userAdminObfuscate FROM %s a, %s b WHERE a.brewerEmail = b.user_name ORDER BY brewerLastName ASC", $brewer_db_table, $users_db_table);
		$rows_brewer = $db_conn->rawQuery($sql);
		$row_brewer = ($rows_brewer && count($rows_brewer) > 0) ? $rows_brewer[0] : null;
		$totalRows_brewer = $db_conn->count;
	}

	elseif ((($section == "admin") && ($go == "participants") && ($filter == "with_entries")  && ($dbTable == "default"))) {
		$sql = sprintf("SELECT a.id AS user_id, a.user_name, a.userLevel, a.userAdminObfuscate, b.id, b.uid, b.brewerEmail, b.brewerLastName, b.brewerFirstName, b.brewerPhone1, b.brewerBreweryName, cb.* FROM (SELECT brewBrewerLastName, brewBrewerFirstName, brewBrewerID, GROUP_CONCAT(id ORDER BY id) AS 'Entries', GROUP_CONCAT(brewJudgingNumber ORDER BY brewJudgingNumber) AS 'JudgingNums' FROM %s GROUP BY brewBrewerLastName, brewBrewerFirstName, brewBrewerID) cb, %s a, %s b WHERE cb.brewBrewerID = b.uid AND a.id = b.uid;", $prefix."brewing", $prefix."users", $prefix."brewer");
		$rows_brewer = $db_conn->rawQuery($sql);
		$row_brewer = ($rows_brewer && count($rows_brewer) > 0) ? $rows_brewer[0] : null;
		$totalRows_brewer = $db_conn->count;
	}

	// Viewing available judges query (not assigned)
	elseif (($section == "admin") && ($go == "participants") && ($filter == "judges") && ($dbTable == "default")) {

		// @single
		if (SINGLE) include (SSO.'available_judges.db.php');

		else {

			$sql = sprintf("SELECT a.*, b.id AS user_id, b.user_name, b.userLevel, b.userAdminObfuscate FROM %s a, %s b WHERE a.brewerEmail = b.user_name", $brewer_db_table, $users_db_table);
			$params = array();
			if ($id == "default") $sql .= " AND brewerJudge='Y'";
			if ($id != "default") { $sql .= " AND a.id=?"; $params[] = $id; }
			$rows_brewer = $db_conn->rawQuery($sql, $params);
			$row_brewer = ($rows_brewer && count($rows_brewer) > 0) ? $rows_brewer[0] : null;
			$totalRows_brewer = $db_conn->count;

		}
	}

	// Viewing available stewards query (not assigned)
	elseif (($section == "admin") && ($go == "participants") && ($filter == "stewards") && ($dbTable == "default")) {


		// @single
		if (SINGLE) include (SSO.'available_stewards.db.php');

		else {

			$sql = sprintf("SELECT a.*, b.id AS user_id, b.user_name, b.userLevel, b.userAdminObfuscate FROM %s a, %s b WHERE a.brewerEmail = b.user_name", $brewer_db_table, $users_db_table);
			$params = array();
			if ($id == "default") $sql .= " AND brewerSteward='Y'";
			if ($id != "default") { $sql .= " AND a.id=?"; $params[] = $id; }
			$rows_brewer = $db_conn->rawQuery($sql, $params);
			$row_brewer = ($rows_brewer && count($rows_brewer) > 0) ? $rows_brewer[0] : null;
			$totalRows_brewer = $db_conn->count;

		}

	}

	// Viewing all participants from archive query
	elseif (($section == "admin") && ($go == "participants") && ($filter == "default")  && ($dbTable != "default")) {
		$dbTable_clean = preg_replace("/[^a-zA-Z0-9_]+/", "", $dbTable);
		$db_conn->orderBy("brewerLastName", "ASC");
		$rows_brewer = $db_conn->get($dbTable_clean);
		$row_brewer = ($rows_brewer && count($rows_brewer) > 0) ? $rows_brewer[0] : null;
		$totalRows_brewer = $db_conn->count;
	}

	// Updating assigned judges query
	elseif (($section == "admin") && ($go == "judging") && ($filter == "judges")  && ($dbTable == "default") && ($action == "update")) {

		// @single
		if (SINGLE) include (SSO.'assigned_judges.db.php');

		else {

			$db_conn->where("brewerAssignment", "J");
			$rows_brewer = $db_conn->get($brewer_db_table);
			$row_brewer = ($rows_brewer && count($rows_brewer) > 0) ? $rows_brewer[0] : null;
			$totalRows_brewer = $db_conn->count;

		}

	}

	// Updating assigned stewards query
	elseif (($section == "admin") && ($go == "judging") && ($filter == "stewards")  && ($dbTable == "default") && ($action == "update")) {

		// @single
		if (SINGLE) include (SSO.'assigned_stewards.db.php');

		else {

			$db_conn->where("brewerAssignment", "S");
			if (($row_participant_count['count'] > $_SESSION['prefsRecordLimit']) && ($view == "default")) $rows_brewer = $db_conn->get($brewer_db_table, array((int) $start, (int) $display));
			else $rows_brewer = $db_conn->get($brewer_db_table);
			$row_brewer = ($rows_brewer && count($rows_brewer) > 0) ? $rows_brewer[0] : null;
			$totalRows_brewer = $db_conn->count;

		}

	}

	// Assign Judge query
	elseif (($section == "admin") && ($go == "judging") && ($filter == "judges")  && ($dbTable == "default") && ($action == "assign")) {

		if (SINGLE) include (SSO.'available_judges.db.php');

		else {

			$db_conn->where("brewerJudge", "Y");
			$rows_brewer = $db_conn->get($brewer_db_table);
			$row_brewer = ($rows_brewer && count($rows_brewer) > 0) ? $rows_brewer[0] : null;
			$totalRows_brewer = $db_conn->count;

		}

	}

	// Assign Steward query
	elseif (($section == "admin") && ($go == "judging") && ($filter == "stewards")  && ($dbTable == "default") && ($action == "assign")) {

		if (SINGLE) include (SSO.'available_stewards.db.php');

		else {

			$db_conn->where("brewerSteward", "Y");
			$rows_brewer = $db_conn->get($brewer_db_table);
			$row_brewer = ($rows_brewer && count($rows_brewer) > 0) ? $rows_brewer[0] : null;
			$totalRows_brewer = $db_conn->count;

		}

	}

	// Assign staff query
	elseif (($section == "admin") && ($go == "judging") && ($filter == "staff")  && ($dbTable == "default") && ($action == "assign")) {
		$sql = sprintf("SELECT * FROM %s", $brewer_db_table);
		$params = array();
		if (SINGLE) { $sql .= " WHERE FIND_IN_SET(?,brewerCompParticipant) > 0"; $params[] = $_SESSION['comp_id']; }
		if ((!SINGLE) && ($view == "yes")) $sql .= " WHERE brewerStaff='Y'";
		if ((SINGLE) && ($view == "yes")) $sql .= " AND brewerStaff='Y'";
		$rows_brewer = $db_conn->rawQuery($sql, $params);
		$row_brewer = ($rows_brewer && count($rows_brewer) > 0) ? $rows_brewer[0] : null;
		$totalRows_brewer = $db_conn->count;
	}

	// Assign BOS judges query
	elseif (($section == "admin") && ($go == "judging") && ($filter == "bos") && ($dbTable == "default") && ($action == "assign")) {

		if ($view == "ranked") $sql = "SELECT * FROM ".$brewer_db_table." WHERE (brewerJudgeRank LIKE 'Recognized%' OR brewerJudgeRank LIKE 'Certified%' OR brewerJudgeRank LIKE 'National%' OR brewerJudgeRank LIKE 'Master%' OR brewerJudgeRank LIKE '%Cicerone' OR brewerJudgeRank LIKE 'Grand%' OR brewerJudgeMead='Y' OR brewerJudgeCider='Y') AND brewerJudge='Y'";
		else $sql = "SELECT * FROM ".$brewer_db_table." WHERE brewerJudge='Y'";

		$params = array();
		if (SINGLE) { $sql .= " AND brewerJudge=? AND FIND_IN_SET(?,brewerCompParticipant) > 0"; $params[] = "Y-".$_SESSION['comp_id']; $params[] = $_SESSION['comp_id']; }
		$rows_brewer = $db_conn->rawQuery($sql, $params);
		$row_brewer = ($rows_brewer && count($rows_brewer) > 0) ? $rows_brewer[0] : null;
		$totalRows_brewer = $db_conn->count;

	}

	// Assigned judges at table query
	elseif (($section == "admin") && ($go == "judging_tables") && ($filter == "judges") && ($dbTable == "default")) {
		$sql = "SELECT a.brewerFirstName, a.brewerLastName, a.uid, a.brewerJudgeRank, a.brewerJudgeID, b.uid FROM ".$brewer_db_table." a, ".$staff_db_table." b WHERE b.staff_judge='1' AND a.uid=b.uid";
		$params = array();
		if (SINGLE) { $sql .= " AND comp_id=?"; $params[] = $_SESSION['comp_id']; }
		$sql .= " ORDER BY a.brewerLastName ASC";
		$rows_brewer = $db_conn->rawQuery($sql, $params);
		$row_brewer = ($rows_brewer && count($rows_brewer) > 0) ? $rows_brewer[0] : null;
		$totalRows_brewer = $db_conn->count;
	}

	// Assigned staff query
	elseif (($section == "admin") && ($go == "judging_tables") && ($filter == "staff")  && ($dbTable == "default")) {
		$sql = "SELECT a.brewerFirstName, a.brewerLastName, a.uid, a.brewerJudgeRank, a.brewerJudgeID, b.uid FROM ".$brewer_db_table." a, ".$staff_db_table." b WHERE b.staff_staff='1' AND a.uid=b.uid";
		$params = array();
		if (SINGLE) { $sql .= " AND comp_id=?"; $params[] = $_SESSION['comp_id']; }
		$rows_brewer = $db_conn->rawQuery($sql, $params);
		$row_brewer = ($rows_brewer && count($rows_brewer) > 0) ? $rows_brewer[0] : null;
		$totalRows_brewer = $db_conn->count;
	}

	// Assigned stewards at table query
	elseif (($section == "admin") && ($go == "judging_tables") && ($filter == "stewards")  && ($dbTable == "default")) {
		$sql = "SELECT a.brewerFirstName, a.brewerLastName, a.uid, a.brewerJudgeRank, a.brewerJudgeID, b.uid FROM ".$brewer_db_table." a, ".$staff_db_table." b WHERE b.staff_steward='1' AND a.uid=b.uid";
		$params = array();
		if (SINGLE) { $sql .= " AND comp_id=?"; $params[] = $_SESSION['comp_id']; }
		$rows_brewer = $db_conn->rawQuery($sql, $params);
		$row_brewer = ($rows_brewer && count($rows_brewer) > 0) ? $rows_brewer[0] : null;
		$totalRows_brewer = $db_conn->count;
	}

	// Make a participant an admin or change password query
	elseif ((($section == "admin") && ($go == "make_admin")) || (($section == "admin") && ($go == "change_user_password")) || (($section == "user") && ($filter == "admin") && ($action == "username"))){
		$db_conn->where("uid", $id);
		$rows_brewer = $db_conn->get($brewer_db_table);
		$row_brewer = ($rows_brewer && count($rows_brewer) > 0) ? $rows_brewer[0] : null;
		$totalRows_brewer = $db_conn->count;
	}

	elseif (($section == "user") && ($filter == "default")) {

		$db_conn->where("uid", $_SESSION['user_id']);
		$rows_brewer = $db_conn->get($brewer_db_table, null, "id,uid");
		$row_brewer = ($rows_brewer && count($rows_brewer) > 0) ? $rows_brewer[0] : null;

	}

	elseif (($section == "list") || ($section == "judge") || ($section == "steward")) {
		$db_conn->where("uid", $_SESSION['user_id']);
		$rows_brewer = $db_conn->get($brewer_db_table);
		$row_brewer = ($rows_brewer && count($rows_brewer) > 0) ? $rows_brewer[0] : null;
		$totalRows_brewer = $db_conn->count;
	}

	if ($section != "step2") {
		$db_conn->where("uid", $_SESSION['user_id']);
		$rows_brewerID = $db_conn->get($brewer_db_table, null, "id,brewerEmail");
		$row_brewerID = ($rows_brewerID && count($rows_brewerID) > 0) ? $rows_brewerID[0] : null;
		$totalRows_brewerID = $db_conn->count;
	}

} // end if (isset($_SESSION['user_id'])

if ($section == "step2")  {
	$db_conn->where("user_name", $go);
	$rows_brewerID = $db_conn->get($users_db_table, null, "id,user_name");
	$row_brewerID = ($rows_brewerID && count($rows_brewerID) > 0) ? $rows_brewerID[0] : null;
	$totalRows_brewerID = $db_conn->count;
}

?>