<?php

/**
 * Module:      admin_common.db.php
 * Description: This module houses common admin related queries
 */

if (table_exists($style_types_db_table)) {

	if (in_array($go, ["default", "style_types", "styles", "judging_scores", "judging_scores_bos", "judging_tables", "judging", "staff", "preferences", "csv"])) {

		$params_style_type = [];

		if (SINGLE) {
			$sql = "SELECT * FROM ".$style_types_db_table." WHERE comp_id='0' OR comp_id=?";
			$params_style_type[] = $_SESSION['comp_id'];
			if (($action == "edit") && ($filter != "default")) { $sql .= " AND id=?"; $params_style_type[] = $filter; }
			if (($action == "enter") && ($filter != "default")) { $sql .= " AND id=?"; $params_style_type[] = $filter; }
			if (($go != "styles") && ($id !="default")) { $sql .= " AND id=?"; $params_style_type[] = $id; }
			if ($go == "styles") $sql .= " ORDER BY id ASC";
			if ((($go == "judging_tables") || ($go == "judging_scores_bos")) && ($action == "default") && ($id == "default")) $sql .= " AND styleTypeBOS='Y'";
		}

		else {
			$sql = "SELECT * FROM ".$style_types_db_table;
			if ($go == "preferences") $sql .= " WHERE styleTypeBOS='Y' ORDER BY id ASC";
			if (($action == "edit") && ($filter != "default")) { $sql .= " WHERE id=?"; $params_style_type[] = $filter; }
			if (($action == "enter") && ($filter != "default")) { $sql .= " WHERE id=?"; $params_style_type[] = $filter; }
			if (($go != "styles") && ($id !="default")) { $sql .= " WHERE id=?"; $params_style_type[] = $id; }
			if ($go == "styles") $sql .= " ORDER BY id ASC";
			if ((($go == "judging_tables") || ($go == "judging_scores_bos")) && ($action == "default") && ($id == "default")) $sql .= " WHERE styleTypeBOS='Y'";
		}

		$rows_style_type = ($params_style_type !== []) ? $db_conn->rawQuery($sql, $params_style_type) : $db_conn->rawQuery($sql);
		$row_style_type = ($rows_style_type && count($rows_style_type) > 0) ? $rows_style_type[0] : null;
		$totalRows_style_type = $db_conn->count;

	}
}

if (table_exists($judging_tables_db_table)) {

	if (in_array($go, ["default", "participants", "judging_scores", "judging_tables", "judging_flights", "judging_tables", "judging_locations"])) {

		$sql = "SELECT * FROM ".$judging_tables_db_table;
		$params_tables = [];
		if (($go == "judging_tables") || ($go == "judging_scores") || (($section == "table_cards") && ($go == "judging_tables"))) $sql .= " ORDER BY tableNumber ASC";
		if (($section == "table_cards") && ($go == "judging_locations")) {
			$sql = "SELECT a.*, b.assignRound FROM ".$judging_tables_db_table." a, ".$judging_assignments_db_table." b WHERE a.id = b.assignTable AND a.tableLocation = ? AND b.assignRound = ? GROUP BY b.assignTable ORDER BY tableNumber";
			$params_tables = [$location, $round];
		}

		$sql_edit = "SELECT * FROM ".$judging_tables_db_table;
		$params_tables_edit = [];
		if ($id != "default") { $sql_edit .= " WHERE id=?"; $params_tables_edit[] = $id; }
		if (($id == "default") || ($go == "judging_scores") || ($go == "judging_scores_bos") || ($go == "judging_flights"))  $sql_edit .= " ORDER BY tableNumber ASC";

		$rows_tables = ($params_tables !== []) ? $db_conn->rawQuery($sql, $params_tables) : $db_conn->rawQuery($sql);
		$row_tables = ($rows_tables && count($rows_tables) > 0) ? $rows_tables[0] : null;
		$totalRows_tables = $db_conn->count;

		$rows_tables_edit = ($params_tables_edit !== []) ? $db_conn->rawQuery($sql_edit, $params_tables_edit) : $db_conn->rawQuery($sql_edit);
		$row_tables_edit = ($rows_tables_edit && count($rows_tables_edit) > 0) ? $rows_tables_edit[0] : null;
		$totalRows_tables_edit = $db_conn->count;

		$rows_tables_edit_2 = ($params_tables_edit !== []) ? $db_conn->rawQuery($sql_edit, $params_tables_edit) : $db_conn->rawQuery($sql_edit);
		$row_tables_edit_2 = ($rows_tables_edit_2 && count($rows_tables_edit_2) > 0) ? $rows_tables_edit_2[0] : null;
		$totalRows_tables_edit_2 = $db_conn->count;

	}

}

if (check_setup($judging_scores_db_table,$database)) {
	$sql = "SELECT * FROM ".$judging_scores_db_table;
	$params_scores = [];
	if (SINGLE) { $sql .= " WHERE comp_id=?"; $params_scores[] = $_SESSION['comp_id']; }
	$sql .= " ORDER BY eid ASC";
	$rows_scores = ($params_scores !== []) ? $db_conn->rawQuery($sql, $params_scores) : $db_conn->rawQuery($sql);
	$row_scores = ($rows_scores && count($rows_scores) > 0) ? $rows_scores[0] : null;
	$totalRows_scores = $db_conn->count;
}

if (in_array($go, ["judging_scores_bos", "judging_tables", "output", "default"])) {

	$sql = "SELECT * FROM ".$style_types_db_table;
	$params_style_types = [];
	if (SINGLE) { $sql .= " WHERE comp_id='0' OR comp_id=?"; $params_style_types[] = $_SESSION['comp_id']; }
	$rows_style_types = ($params_style_types !== []) ? $db_conn->rawQuery($sql, $params_style_types) : $db_conn->rawQuery($sql);
	$row_style_types = ($rows_style_types && count($rows_style_types) > 0) ? $rows_style_types[0] : null;
}

$total_fees = total_fees($_SESSION['contestEntryFee'], $_SESSION['contestEntryFee2'], $_SESSION['contestEntryFeeDiscount'], $_SESSION['contestEntryFeeDiscountNum'], $_SESSION['contestEntryCap'], $_SESSION['contestEntryFeePasswordNum'], $bid, $filter, $_SESSION['comp_id']);
$total_fees_paid = total_fees_paid($_SESSION['contestEntryFee'], $_SESSION['contestEntryFee2'], $_SESSION['contestEntryFeeDiscount'], $_SESSION['contestEntryFeeDiscountNum'], $_SESSION['contestEntryCap'], $_SESSION['contestEntryFeePasswordNum'], $bid, $filter, $_SESSION['comp_id']);
$total_fees_unpaid = ($total_fees - $total_fees_paid);
$total_nopay_received = total_nopay_received($go, "default", $_SESSION['comp_id']);

if (($go == "default") || ($go == "participants")) {
	$sql = "SELECT COUNT(DISTINCT brewBrewerId) as 'count' FROM ".$prefix."brewing";
	$params_with_entries = [];
	if (SINGLE) { $sql .= " WHERE comp_id = ?"; $params_with_entries[] = $_SESSION['comp_id']; }
	$rows_with_entries = ($params_with_entries !== []) ? $db_conn->rawQuery($sql, $params_with_entries) : $db_conn->rawQuery($sql);
	$row_with_entries = ($rows_with_entries && count($rows_with_entries) > 0) ? $rows_with_entries[0] : null;
}

if (($go == "special_best_data") || ($go == "special_best")) {

	if (SINGLE) {

		$sql_sbi = "SELECT * FROM ".$special_best_info_db_table." WHERE comp_id=?";
		$params_sbi = [$_SESSION['comp_id']];
		if (($action == "add") || ($action == "edit")) { $sql_sbi .= " AND id=?"; $params_sbi[] = $id; }
		$rows_sbi = $db_conn->rawQuery($sql_sbi, $params_sbi);
		$row_sbi = ($rows_sbi && count($rows_sbi) > 0) ? $rows_sbi[0] : null;
		$totalRows_sbi = $db_conn->count;

		if ($action == "add") { $sql_sbd = "SELECT * FROM ".$special_best_data_db_table." WHERE comp_id=? AND id=?"; $params_sbd = [$_SESSION['comp_id'], $id]; }
		elseif ($action == "edit") { $sql_sbd = "SELECT * FROM ".$special_best_data_db_table." WHERE comp_id=? AND sid=? ORDER BY sbd_place ASC"; $params_sbd = [$_SESSION['comp_id'], $id]; }
		else { $sql_sbd = "SELECT * FROM ".$special_best_data_db_table." WHERE comp_id=? ORDER BY sid,sbd_place ASC"; $params_sbd = [$_SESSION['comp_id']]; }
		$rows_sbd = $db_conn->rawQuery($sql_sbd, $params_sbd);
		$row_sbd = ($rows_sbd && count($rows_sbd) > 0) ? $rows_sbd[0] : null;
		$totalRows_sbd = $db_conn->count;

	}

	else {

		$sql_sbi = "SELECT * FROM ".$special_best_info_db_table;
		$params_sbi = [];
		if (($action == "add") || ($action == "edit")) { $sql_sbi .= " WHERE id=?"; $params_sbi[] = $id; }
		$rows_sbi = ($params_sbi !== []) ? $db_conn->rawQuery($sql_sbi, $params_sbi) : $db_conn->rawQuery($sql_sbi);
		$row_sbi = ($rows_sbi && count($rows_sbi) > 0) ? $rows_sbi[0] : null;
		$totalRows_sbi = $db_conn->count;

		if ($action == "add") { $sql_sbd = "SELECT * FROM ".$special_best_data_db_table." WHERE id=?"; $params_sbd = [$id]; }
		elseif ($action == "edit") { $sql_sbd = "SELECT * FROM ".$special_best_data_db_table." WHERE sid=? ORDER BY sbd_place ASC"; $params_sbd = [$id]; }
		else { $sql_sbd = "SELECT * FROM ".$special_best_data_db_table." ORDER BY sid,sbd_place ASC"; $params_sbd = []; }
		$rows_sbd = ($params_sbd !== []) ? $db_conn->rawQuery($sql_sbd, $params_sbd) : $db_conn->rawQuery($sql_sbd);
		$row_sbd = ($rows_sbd && count($rows_sbd) > 0) ? $rows_sbd[0] : null;
		$totalRows_sbd = $db_conn->count;

	}
}

?>