<?php

if ($bid != "") {
	$query_judging = sprintf("SELECT judgingLocName FROM %s WHERE id='%s'",$prefix."judging_locations", $bid);
	if (SINGLE) $query_judging .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
	$judging = mysqli_query($connection,$query_judging) or die (mysqli_error($connection));
	$row_judging = mysqli_fetch_assoc($judging);
}

// Note: the order of the columns is set to the specifications set by HCCP for import
if (($filter != "winners") || ($tb != "winners")) {

	if ($tb == "all") $query_sql = sprintf($query_sql = "SELECT * FROM %s", $prefix."brewing");
	else $query_sql = sprintf("SELECT DISTINCT id, brewBrewerFirstName, brewBrewerLastName, brewCategory, brewSubCategory, brewName, brewInfo, brewInfoOptional, brewComments, brewMead2, brewMead1, brewMead3, brewBrewerID, brewJudgingNumber, brewStyle FROM %s", $prefix."brewing");

	if (SINGLE) {

		if ((($filter == "paid") || ($tb == "paid")) && ($bid == "default") && ($view == "default"))  $query_sql .= sprintf(" WHERE brewPaid = '1' AND brewReceived = '1' AND comp_id='%s'",$_SESSION['comp_id']);
		if ((($filter == "paid") || ($tb == "paid")) && ($bid == "default") && ($view == "all"))  $query_sql .= sprintf(" WHERE brewPaid = '1' AND comp_id='%s'",$_SESSION['comp_id']);
		if ((($filter == "paid") || ($tb == "paid")) && ($bid == "default") && ($view == "not_received"))  $query_sql .= sprintf(" WHERE brewPaid = '1' AND (brewReceived <> 1 OR brewReceived IS NULL) AND comp_id='%s'",$_SESSION['comp_id']);
		if ((($filter == "paid") || ($tb == "paid")) && ($bid != "default"))  $query_sql .= sprintf(" WHERE brewPaid = '1' AND brewReceived = '1' AND brewJudgingLocation='%s' AND comp_id='%s'", $bid, $_SESSION['comp_id']);
		if ((($filter == "nopay") || ($tb == "nopay")) && ($bid == "default") && ($view == "default")) $query_sql .= sprintf(" WHERE (brewPaid <> 1 OR brewPaid IS NULL) AND brewReceived = '1' AND comp_id='%s'",$_SESSION['comp_id']);
		if ((($filter == "nopay") || ($tb == "nopay")) && ($bid == "default") && ($view == "all")) $query_sql .= sprintf(" WHERE (brewPaid <> 1 OR brewPaid IS NULL) AND comp_id='%s'",$_SESSION['comp_id']);
		if ((($filter == "required") || ($tb == "required")) && ($bid == "default") && ($view == "default")) $query_sql .= sprintf(" WHERE (brewInfo IS NOT NULL) OR (brewComments IS NOT NULL) OR (brewInfoOptional IS NOT NULL) AND comp_id='%s'",$_SESSION['comp_id']);

	}

	else {

		if ((($filter == "paid") || ($tb == "paid")) && ($bid == "default") && ($view == "default"))  $query_sql .= " WHERE brewPaid = '1' AND brewReceived = '1'";
		if ((($filter == "paid") || ($tb == "paid")) && ($bid == "default") && ($view == "all"))  $query_sql .= " WHERE brewPaid = '1'";
		if ((($filter == "paid") || ($tb == "paid")) && ($bid == "default") && ($view == "not_received"))  $query_sql .= " WHERE brewPaid = '1' AND (brewReceived <> 1 OR brewReceived IS NULL)";
		if ((($filter == "paid") || ($tb == "paid")) && ($bid != "default"))  $query_sql .= sprintf(" WHERE brewPaid = '1' AND brewReceived = '1' AND brewJudgingLocation = '%s'",$bid);
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

	$query_sql = sprintf("SELECT id,tableNumber,tableName FROM %s", $judging_tables_db_table.$archive_suffix);
	if (SINGLE) $query_sql .= sprintf(" AND comp_id='%s'",$_SESSION['comp_id']);
	$query_sql .= " ORDER BY tableNumber ASC";

}

$sql = mysqli_query($connection,$query_sql) or die (mysqli_error($connection));
$row_sql = mysqli_fetch_assoc($sql);
$num_fields = mysqli_num_fields($sql);
$totalRows_sql = mysqli_num_rows($sql);
?>