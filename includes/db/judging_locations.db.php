<?php

$params_judging = [];

if (SINGLE) {

	$query_judging = "SELECT * FROM ".$judging_locations_db_table." WHERE comp_id=?";
	$params_judging[] = $_SESSION['comp_id'];

	if ($go != "default") {
		if (($go == "styles") && ($bid != "default")) { $query_judging .= " AND id=?"; $params_judging[] = $bid; }
		elseif (($go == "judging") && ($action == "update") && ($bid != "default")) { $query_judging .= " AND id=?"; $params_judging[] = $bid; }
		elseif (($go == "judging") && (($action == "add") || ($action == "edit")))  { $query_judging .= " AND id=?"; $params_judging[] = $id; }
		elseif (($go == "non-judging") && ($action == "default")) $query_judging .= " AND judgingLocType='2' ORDER BY judgingDate,judgingLocName ASC";
		elseif (($go == "non-judging") && (($action == "add") || ($action == "edit"))) { $query_judging .= " AND id=?"; $params_judging[] = $id; }
	}
	else $query_judging .= " WHERE judgingLocType < 2 ORDER BY judgingDate,judgingLocName ASC";

}

else {

	$query_judging = "SELECT * FROM ".$judging_locations_db_table;

	if ($go != "default") {
		if (($go == "styles") && ($bid != "default")) { $query_judging .= " WHERE id=?"; $params_judging[] = $bid; }
		elseif (($go == "judging") && ($action == "default")) $query_judging .= " WHERE judgingLocType < 2 ORDER BY judgingDate,judgingLocName ASC";
		elseif (($go == "judging") && ($action == "update") && ($bid != "default")) { $query_judging .= " WHERE id=?"; $params_judging[] = $bid; }
		elseif (($go == "judging") && (($action == "add") || ($action == "edit"))) { $query_judging .= " WHERE id=?"; $params_judging[] = $id; }
		elseif (($go == "non-judging") && ($action == "default")) $query_judging .= " WHERE judgingLocType='2' ORDER BY judgingDate,judgingLocName ASC";
		elseif (($go == "non-judging") && (($action == "add") || ($action == "edit"))) { $query_judging .= " WHERE id=?"; $params_judging[] = $id; }
		elseif (($section == "admin") && ($go == "judging_tables")) $query_judging .= " ORDER BY judgingDate,judgingLocName ASC";
	}

	else $query_judging .= " ORDER BY judgingDate,judgingLocName ASC";

}

$rows_judging = ($params_judging !== []) ? $db_conn->rawQuery($query_judging, $params_judging) : $db_conn->rawQuery($query_judging);
$row_judging = ($rows_judging && count($rows_judging) > 0) ? $rows_judging[0] : null;
$totalRows_judging = $db_conn->count;

// Separate connections for selected queries that are housed on the same page.
// ********************* Should be replaced with function *********************

// Apparently Unused - v 2.5.0
$query_judging1 = "SELECT * FROM ".$judging_locations_db_table;
$params_judging1 = [];
if (SINGLE) { $query_judging1 .= " WHERE comp_id=?"; $params_judging1[] = $_SESSION['comp_id']; }
$query_judging1 .= "  WHERE judgingLocType = 2 ORDER BY judgingDate,judgingLocName ASC";
$rows_judging1 = ($params_judging1 !== []) ? $db_conn->rawQuery($query_judging1, $params_judging1) : $db_conn->rawQuery($query_judging1);
$row_judging1 = ($rows_judging1 && count($rows_judging1) > 0) ? $rows_judging1[0] : null;
$totalRows_judging1 = $db_conn->count;


if (($section == "admin") && ($go == "default")) {
	$query_judging2 = "SELECT * FROM ".$judging_locations_db_table;
	$params_judging2 = [];
	if (SINGLE) { $query_judging2 .= " WHERE comp_id=?"; $params_judging2[] = $_SESSION['comp_id']; }
	if (in_array($section, ["brewer", "admin", "register"])) $query_judging2 .= " WHERE judgingLocType < 2 ORDER BY judgingDate,judgingLocName ASC";
	$rows_judging2 = ($params_judging2 !== []) ? $db_conn->rawQuery($query_judging2, $params_judging2) : $db_conn->rawQuery($query_judging2);
	$row_judging2 = ($rows_judging2 && count($rows_judging2) > 0) ? $rows_judging2[0] : null;
	$totalRows_judging2 = $db_conn->count;
}

$query_judging3 = "SELECT * FROM ".$judging_locations_db_table;
$params_judging3 = [];
if (SINGLE) { $query_judging3 .= " WHERE comp_id=?"; $params_judging3[] = $_SESSION['comp_id']; }
if ((($section == "brewer") && ($action == "edit")) || ($section == "admin") || ($section == "register")) $query_judging3 .= " ORDER BY judgingDate,judgingLocName ASC";
$rows_judging3 = ($params_judging3 !== []) ? $db_conn->rawQuery($query_judging3, $params_judging3) : $db_conn->rawQuery($query_judging3);
$row_judging3 = ($rows_judging3 && count($rows_judging3) > 0) ? $rows_judging3[0] : null;
$totalRows_judging3 = $db_conn->count;

// Make DB Connections

if ((($action == "default") || ($action == "assign")) && ($section != "step5")) {

	// Get Judging Locations Info
	$query_judging_locs = "SELECT * FROM ".$judging_locations_db_table;
	$params_judging_locs = [];
	if (SINGLE) { $query_judging_locs .= " WHERE comp_id=?"; $params_judging_locs[] = $_SESSION['comp_id']; }
	if (($go == "judging") && ($action == "default")) $query_judging_locs .= " WHERE judgingLocType < 2";
	if (($go == "non-judging") && ($action == "default")) $query_judging_locs .= " WHERE judgingLocType='2'";
	$query_judging_locs .= " ORDER by judgingDate ASC";
	$rows_judging_locs = ($params_judging_locs !== []) ? $db_conn->rawQuery($query_judging_locs, $params_judging_locs) : $db_conn->rawQuery($query_judging_locs);
	$row_judging_locs = ($rows_judging_locs && count($rows_judging_locs) > 0) ? $rows_judging_locs[0] : null;
	$totalRows_judging_locs = $db_conn->count;

}

if ($filter == "staff") {

	$db_conn->where("staff_organizer", "1");
	if (SINGLE) $db_conn->where("comp_id", $_SESSION['comp_id']);
	$rows_organizer = $db_conn->get($prefix."staff", null, "uid");
	$row_organizer = ($rows_organizer && count($rows_organizer) > 0) ? $rows_organizer[0] : null;
	$totalRows_organizer = $db_conn->count;

	// @single
	$db_conn->orderBy("brewerLastName", "ASC");
	$rows_brewers = $db_conn->get($brewer_db_table);
	$row_brewers = ($rows_brewers && count($rows_brewers) > 0) ? $rows_brewers[0] : null;

}

?>