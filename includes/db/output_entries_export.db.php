<?php
declare(strict_types=1);

if ($bid != "") {
	$query_judging = "SELECT judgingLocName FROM ".$prefix."judging_locations WHERE id=?";
	$params_judging = array($bid);
	if (SINGLE) { $query_judging .= " AND comp_id=?"; $params_judging[] = $_SESSION['comp_id']; }
	$row_judging = $db_conn->rawQueryOne($query_judging, $params_judging);
}

$params_sql = array();

// Note: the order of the columns is set to the specifications set by HCCP for import
if (($filter != "winners") || ($tb != "winners")) {

	if ($tb == "all") $query_sql = "SELECT * FROM ".$prefix."brewing";
	else $query_sql = "SELECT DISTINCT id, brewBrewerFirstName, brewBrewerLastName, brewCategory, brewSubCategory, brewName, brewInfo, brewInfoOptional, brewComments, brewMead2, brewMead1, brewMead3, brewBrewerID, brewJudgingNumber, brewStyle FROM ".$prefix."brewing";

	if (SINGLE) {

		if ((($filter == "paid") || ($tb == "paid")) && ($bid == "default") && ($view == "default"))  { $query_sql .= " WHERE brewPaid = '1' AND brewReceived = '1' AND comp_id=?"; $params_sql[] = $_SESSION['comp_id']; }
		if ((($filter == "paid") || ($tb == "paid")) && ($bid == "default") && ($view == "all"))  { $query_sql .= " WHERE brewPaid = '1' AND comp_id=?"; $params_sql[] = $_SESSION['comp_id']; }
		if ((($filter == "paid") || ($tb == "paid")) && ($bid == "default") && ($view == "not_received"))  { $query_sql .= " WHERE brewPaid = '1' AND (brewReceived <> 1 OR brewReceived IS NULL) AND comp_id=?"; $params_sql[] = $_SESSION['comp_id']; }
		// if ((($filter == "paid") || ($tb == "paid")) && ($bid != "default"))  $query_sql .= sprintf(" WHERE brewPaid = '1' AND brewReceived = '1' AND brewJudgingLocation='%s' AND comp_id='%s'", $bid, $_SESSION['comp_id']);
		if ((($filter == "nopay") || ($tb == "nopay")) && ($bid == "default") && ($view == "default")) { $query_sql .= " WHERE (brewPaid <> 1 OR brewPaid IS NULL) AND brewReceived = '1' AND comp_id=?"; $params_sql[] = $_SESSION['comp_id']; }
		if ((($filter == "nopay") || ($tb == "nopay")) && ($bid == "default") && ($view == "all")) { $query_sql .= " WHERE (brewPaid <> 1 OR brewPaid IS NULL) AND comp_id=?"; $params_sql[] = $_SESSION['comp_id']; }
		if ((($filter == "required") || ($tb == "required")) && ($bid == "default") && ($view == "default")) { $query_sql .= " WHERE (brewInfo IS NOT NULL) OR (brewComments IS NOT NULL) OR (brewInfoOptional IS NOT NULL) AND comp_id=?"; $params_sql[] = $_SESSION['comp_id']; }

	}

	else {

		if ((($filter == "paid") || ($tb == "paid")) && ($bid == "default") && ($view == "default"))  $query_sql .= " WHERE brewPaid = '1' AND brewReceived = '1'";
		if ((($filter == "paid") || ($tb == "paid")) && ($bid == "default") && ($view == "all"))  $query_sql .= " WHERE brewPaid = '1'";
		if ((($filter == "paid") || ($tb == "paid")) && ($bid == "default") && ($view == "not_received"))  $query_sql .= " WHERE brewPaid = '1' AND (brewReceived <> 1 OR brewReceived IS NULL)";
		// if ((($filter == "paid") || ($tb == "paid")) && ($bid != "default"))  $query_sql .= sprintf(" WHERE brewPaid = '1' AND brewReceived = '1' AND brewJudgingLocation = '%s'",$bid);
		if ((($filter == "nopay") || ($tb == "nopay")) && ($bid == "default") && ($view == "default")) $query_sql .= " WHERE (brewPaid <> 1 OR brewPaid IS NULL) AND brewReceived = '1'";
		if ((($filter == "nopay") || ($tb == "nopay")) && ($bid == "default") && ($view == "all")) $query_sql .= " WHERE (brewPaid <> 1 OR brewPaid IS NULL)";
		if ((($filter == "required") || ($tb == "required")) && ($bid == "default") && ($view == "default")) $query_sql .= " WHERE (brewInfo IS NOT NULL) OR (brewComments IS NOT NULL) OR (brewInfoOptional IS NOT NULL) ORDER BY id ASC";
	}

}

if (($go == "csv") && ($action == "email")) $query_sql .= " ORDER BY brewBrewerLastName,brewBrewerFirstName,id ASC";
if (($go == "csv") && ($action == "all") && ($filter == "all")) $query_sql .= " ORDER BY id ASC";

if (($filter == "winners") || ($tb == "winners") || ($tb == "circuit")) {

	$archive_suffix = "";
	if ($sort != "default") $archive_suffix = "_".$sort;

	$params_sql = array();
	$query_sql = "SELECT id,tableNumber,tableName FROM ".$judging_tables_db_table.$archive_suffix;
	if (SINGLE) { $query_sql .= " AND comp_id=?"; $params_sql[] = $_SESSION['comp_id']; }
	$query_sql .= " ORDER BY tableNumber ASC";

}

$rows_sql = (!empty($params_sql)) ? $db_conn->rawQuery($query_sql, $params_sql) : $db_conn->rawQuery($query_sql);
$row_sql = ($rows_sql && count($rows_sql) > 0) ? $rows_sql[0] : null;
$num_fields = ($row_sql) ? count($row_sql) : 0;
$totalRows_sql = $db_conn->count;
?>