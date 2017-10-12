<?php
// ---------------------------------------------------------
// Entry Data Labels
// ---------------------------------------------------------

if ($go == "entries") {

	if ($action == "bottle-entry") {

		$query_log = "SELECT * FROM $brewing_db_table";
		if ($filter != "default") $query_log .= sprintf(" WHERE brewCategorySort='%s'",$filter);
		$query_log .= " ORDER BY brewCategorySort,brewSubCategory,id ASC";

	}

	if ($action == "bottle-judging") {

		$query_log = sprintf("SELECT * FROM %s",$prefix."brewing");
		if ($filter != "default") $query_log .= sprintf(" WHERE brewCategorySort='%s'",$filter);
		$query_log .= " ORDER BY brewCategorySort,brewSubCategory,brewJudgingNumber ASC";

	}

	if (($action == "bottle-judging-round") && ($view == "default")) {
		$query_log = sprintf("SELECT * FROM %s",$prefix."brewing");
		//if ($filter != "default") $query_log .= sprintf(" WHERE brewCategorySort='%s'",$filter);
		$query_log .= " ORDER BY brewCategorySort,brewSubCategory,brewJudgingNumber ASC";

	}

	if (($action == "bottle-entry-round") && ($view == "default")) {

		$query_log = sprintf("SELECT * FROM %s",$prefix."brewing");
		//if ($filter != "default") $query_log .= sprintf(" WHERE brewCategorySort='%s'",$filter);
		$query_log .= " ORDER BY brewCategorySort,brewSubCategory,id ASC";

	}

	if (($action == "bottle-category-round") && ($view == "default")) {

		$query_log = sprintf("SELECT brewCategorySort,brewSubCategory FROM %s ORDER BY brewCategorySort,brewSubCategory ASC",$prefix."brewing");
		if ($filter != "default") $query_log .= sprintf(" WHERE brewCategorySort='%s'",$filter);

	}

	if (($action == "bottle-entry-round") && ($view == "OL5275WR")) {

		$query_log = sprintf("SELECT * FROM %s",$prefix."brewing");
		if ($filter != "default") $query_log .= sprintf(" WHERE brewCategorySort='%s'",$filter);
		$query_log .= " ORDER BY brewCategorySort,brewSubCategory,id ASC";

	}


	// Execute query
	$log = mysqli_query($connection,$query_log) or die (mysqli_error($connection));
	$row_log = mysqli_fetch_assoc($log);
	$totalRows_log = mysqli_num_rows($log);

}

// ---------------------------------------------------------
// Participant Data Labels
// ---------------------------------------------------------

if ($go == "participants") {

	if ($action == "judging_nametags") {

		$query_brewer = "SELECT a.id,a.brewerFirstName,a.brewerLastName,a.brewerCity,a.brewerState,b.uid,b.staff_judge,b.staff_steward,b.staff_staff,b.staff_organizer FROM $brewer_db_table a, $staff_db_table b WHERE a.uid = b.uid ORDER BY a.brewerLastName ASC";

	}

	if (($action == "judging_labels") && ($id == "default")) {

		$query_brewer = "SELECT a.id,a.brewerFirstName,a.brewerLastName,a.brewerJudgeID,a.brewerEmail,a.brewerJudgeRank,a.brewerJudgeMead,b.uid,b.staff_judge FROM $brewer_db_table a, $staff_db_table b WHERE a.uid = b.uid AND b.staff_judge='1' AND a.brewerJudge = 'Y' ORDER BY a.brewerLastName ASC";

	}

	if ($action == "address_labels") {

		$query_brewer = sprintf("SELECT * FROM %s ORDER BY brewerLastName ASC",$brewer_db_table);

		if ($filter == "with_entries") {
			$query_with_entries = sprintf("SELECT brewBrewerID FROM %s WHERE brewReceived='1'",$brewing_db_table);
			$with_entries = mysqli_query($connection,$query_with_entries) or die (mysqli_error($connection));
			$row_with_entries = mysqli_fetch_assoc($with_entries);
		}
	}

	if (($action == "judging_labels") && ($id != "default")) {

		$query_brewer = sprintf("SELECT id,brewerFirstName,brewerLastName,brewerJudgeID,brewerEmail,brewerJudgeRank,brewerJudgeMead,uid FROM $brewer_db_table WHERE id = %s",$id);

	}

	// Execute the query
	$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
	$row_brewer = mysqli_fetch_assoc($brewer);
	$totalRows_brewer = mysqli_num_rows($brewer);
}

// ---------------------------------------------------------
// Score and Result Data Labels
// ---------------------------------------------------------
?>