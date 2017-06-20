<?php

if ($bid != "") {
	$query_judging = sprintf("SELECT judgingLocName FROM %s WHERE id='%s'",$judging_locations_db_table, $bid);
	if (SINGLE) $query_judging .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
	$judging = mysqli_query($connection,$query_judging) or die (mysqli_error($connection));
	$row_judging = mysqli_fetch_assoc($judging);
}

// Note: the order of the columns is set to the specifications set by HCCP for import
if ($filter != "winners") {
	
	if ($filter == "all") 	$query_sql = "SELECT * FROM $brewing_db_table";
	else $query_sql = "SELECT DISTINCT id, brewBrewerFirstName, brewBrewerLastName, brewCategory, brewSubCategory, brewName, brewInfo, brewComments, brewMead2, brewMead1, brewMead3, brewBrewerID, brewJudgingNumber, brewStyle FROM $brewing_db_table";
	
	if (SINGLE) {
		
		if (($filter == "paid") && ($bid == "default") && ($view == "default"))  $query_sql .= sprintf(" WHERE brewPaid = '1' AND brewReceived = '1' AND comp_id='%s'",$_SESSION['comp_id']); 
		if (($filter == "paid") && ($bid == "default") && ($view == "all"))  $query_sql .= sprintf(" WHERE brewPaid = '1' AND comp_id='%s'",$_SESSION['comp_id']); 
		if (($filter == "paid") && ($bid == "default") && ($view == "not_received"))  $query_sql .= sprintf(" WHERE brewPaid = '1' AND (brewReceived <> 1 OR brewReceived IS NULL) AND comp_id='%s'",$_SESSION['comp_id']);
		if (($filter == "paid") && ($bid != "default"))  $query_sql .= sprintf(" WHERE brewPaid = '1' AND brewReceived = '1' AND brewJudgingLocation = '$bid' AND comp_id='%s'",$_SESSION['comp_id']); 
		if (($filter == "nopay") && ($bid == "default") && ($view == "default")) $query_sql .= sprintf(" WHERE (brewPaid <> 1 OR brewPaid IS NULL) AND brewReceived = '1' AND comp_id='%s'",$_SESSION['comp_id']);
		if (($filter == "nopay") && ($bid == "default") && ($view == "all")) $query_sql .= sprintf(" WHERE (brewPaid <> 1 OR brewPaid IS NULL) AND comp_id='%s'",$_SESSION['comp_id']);
		
	}
	
	else {
		
		if (($filter == "paid") && ($bid == "default") && ($view == "default"))  $query_sql .= " WHERE brewPaid = '1' AND brewReceived = '1'"; 
		if (($filter == "paid") && ($bid == "default") && ($view == "all"))  $query_sql .= " WHERE brewPaid = '1'"; 
		if (($filter == "paid") && ($bid == "default") && ($view == "not_received"))  $query_sql .= " WHERE brewPaid = '1' AND (brewReceived <> 1 OR brewReceived IS NULL)"; 
		if (($filter == "paid") && ($bid != "default"))  $query_sql .= " WHERE brewPaid = '1' AND brewReceived = '1' AND brewJudgingLocation = '$bid'"; 
		if (($filter == "nopay") && ($bid == "default") && ($view == "default")) $query_sql .= " WHERE (brewPaid <> 1 OR brewPaid IS NULL) AND brewReceived = '1'";
		if (($filter == "nopay") && ($bid == "default") && ($view == "all")) $query_sql .= " WHERE (brewPaid <> 1 OR brewPaid IS NULL)";
		
	}
	
}

if (($go == "csv") && ($action == "email")) $query_sql .= " ORDER BY brewBrewerLastName,brewBrewerFirstName,id ASC";
if (($go == "csv") && ($action == "all") && ($filter == "all")) $query_sql .= " ORDER BY id ASC";

if ($filter == "winners") {
	
	$query_sql = "SELECT id,tableNumber,tableName FROM $judging_tables_db_table";
	if (SINGLE) $query_sql .= sprintf(" AND comp_id='%s'",$_SESSION['comp_id']);
	$query_sql .= " ORDER BY tableNumber ASC";
	
}

$sql = mysqli_query($connection,$query_sql) or die (mysqli_error($connection));
$row_sql = mysqli_fetch_assoc($sql);
$num_fields = mysqli_num_fields($sql);
?>