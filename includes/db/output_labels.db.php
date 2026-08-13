<?php
// ---------------------------------------------------------
// Entry Data Labels
// ---------------------------------------------------------

if ($go == "entries") {

	$params_log = array();

	if ($action == "bottle-entry") {

		$query_log = "SELECT * FROM ".$prefix."brewing";
		if ($filter == "default") {
			if ($tb == "received") $query_log .= " WHERE brewReceived='1'";
		}
		else {
			$query_log .= " WHERE brewCategorySort=?";
			$params_log[] = $filter;
			if ($tb == "received") $query_log .= " AND brewReceived='1'";
		}
		$query_log .= " ORDER BY brewCategorySort,brewSubCategory,id ASC";

	}

	if ($action == "bottle-judging") {

		$query_log = "SELECT * FROM ".$prefix."brewing";
		if ($filter == "default") {
			if ($tb == "received") $query_log .= " WHERE brewReceived='1'";
		}
		else {
			$query_log .= " WHERE brewCategorySort=?";
			$params_log[] = $filter;
			if ($tb == "received") $query_log .= " AND brewReceived='1'";
		}
		$query_log .= " ORDER BY brewCategorySort,brewSubCategory,brewJudgingNumber ASC";

	}

	if (($action == "bottle-judging-round") && ($view == "default")) {
		$query_log = "SELECT * FROM ".$prefix."brewing";
		//if ($filter != "default") $query_log .= sprintf(" WHERE brewCategorySort='%s'",$filter);
		$query_log .= " ORDER BY brewCategorySort,brewSubCategory,brewJudgingNumber ASC";

	}

	if (($action == "bottle-entry-round") && ($view == "default")) {

		$query_log = "SELECT * FROM ".$prefix."brewing";
		//if ($filter != "default") $query_log .= sprintf(" WHERE brewCategorySort='%s'",$filter);
		$query_log .= " ORDER BY brewCategorySort,brewSubCategory,id ASC";

	}

	if (($action == "bottle-category-round") && ($view == "default")) {

		$query_log = "SELECT brewCategorySort,brewSubCategory FROM ".$prefix."brewing"." ORDER BY brewCategorySort,brewSubCategory ASC";
		if ($filter != "default") { $query_log .= " WHERE brewCategorySort=?"; $params_log[] = $filter; }

	}

	if (($action == "bottle-entry-round") && ($view == "OL5275WR")) {

		$query_log = "SELECT * FROM ".$prefix."brewing";
		if ($filter != "default") { $query_log .= " WHERE brewCategorySort=?"; $params_log[] = $filter; }
		$query_log .= " ORDER BY brewCategorySort,brewSubCategory,id ASC";

	}


	// Execute query
	$rows_log = (!empty($params_log)) ? $db_conn->rawQuery($query_log, $params_log) : $db_conn->rawQuery($query_log);
	$row_log = ($rows_log && count($rows_log) > 0) ? $rows_log[0] : null;
	$totalRows_log = $db_conn->count;

}

// ---------------------------------------------------------
// Participant Data Labels
// ---------------------------------------------------------

if ($go == "participants") {

	$params_brewer = array();

	if ($action == "judging_nametags") {

		$query_brewer = "SELECT a.id,a.brewerFirstName,a.brewerLastName,a.brewerCity,a.brewerState,b.uid,b.staff_judge,b.staff_steward,b.staff_staff,b.staff_organizer FROM ".$prefix."brewer"." a, ".$prefix."staff"." b WHERE a.uid = b.uid ORDER BY a.brewerLastName ASC";

	}

	if (($action == "judging_labels") && ($id == "default")) {

		$query_brewer = "SELECT a.id,a.brewerFirstName,a.brewerLastName,a.brewerJudgeID,a.brewerEmail,a.brewerJudgeRank,a.brewerJudgeMead,b.uid,b.staff_judge FROM ".$prefix."brewer"." a, ".$prefix."staff"." b WHERE a.uid = b.uid AND b.staff_judge='1' AND a.brewerJudge = 'Y' ORDER BY a.brewerLastName ASC";

	}

	if (($action == "judging_labels") && ($id != "default")) {

		$query_brewer = "SELECT id,brewerFirstName,brewerLastName,brewerJudgeID,brewerEmail,brewerJudgeRank,brewerJudgeMead,uid FROM ".$prefix."brewer"." WHERE id = ?";
		$params_brewer[] = $id;

	}

	if ($action == "address_labels") {

		$query_brewer = "SELECT * FROM ".$prefix."brewer"." ORDER BY brewerLastName ASC";

		if ($filter == "with_entries") {
			$db_conn->where('brewReceived', '1');
			$rows_with_entries = $db_conn->get($brewing_db_table, null, "brewBrewerID");
			$row_with_entries = ($rows_with_entries && count($rows_with_entries) > 0) ? $rows_with_entries[0] : null;
		}
	}

	// Execute the query
	$rows_brewer = (!empty($params_brewer)) ? $db_conn->rawQuery($query_brewer, $params_brewer) : $db_conn->rawQuery($query_brewer);
	$row_brewer = ($rows_brewer && count($rows_brewer) > 0) ? $rows_brewer[0] : null;
	$totalRows_brewer = $db_conn->count;
}

// ---------------------------------------------------------
// Score and Result Data Labels
// ---------------------------------------------------------
?>