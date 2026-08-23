<?php

// Reorder view: every entry assigned to one flight, saved manual order first (NULLs last), then judging number
if ((($filter != "default") && ($filter != "rounds")) && (isset($flight)) && ($flight != "default")) {

	if (SINGLE) {
		$query_reorder_entries = "SELECT b.id,b.brewName,b.brewStyle,b.brewCategorySort,b.brewSubCategory,b.brewInfo,b.brewJudgingNumber,f.flightEntryOrder FROM ".$judging_flights_db_table." f JOIN ".$brewing_db_table." b ON f.flightEntryID=b.id WHERE f.flightTable=? AND f.flightNumber=? AND b.comp_id=?";
		$params_reorder_entries = array($flight_table, $flight, $_SESSION['comp_id']);
	}
	else {
		$query_reorder_entries = "SELECT b.id,b.brewName,b.brewStyle,b.brewCategorySort,b.brewSubCategory,b.brewInfo,b.brewJudgingNumber,f.flightEntryOrder FROM ".$judging_flights_db_table." f JOIN ".$brewing_db_table." b ON f.flightEntryID=b.id WHERE f.flightTable=? AND f.flightNumber=?";
		$params_reorder_entries = array($flight_table, $flight);
	}

	if ($_SESSION['jPrefsTablePlanning'] == 0) $query_reorder_entries .= " AND b.brewReceived='1'";
	$query_reorder_entries .= " ORDER BY f.flightEntryOrder IS NULL ASC, f.flightEntryOrder ASC, b.brewCategorySort, b.brewSubCategory, b.brewJudgingNumber ASC";

	$rows_reorder_entries = $db_conn->rawQuery($query_reorder_entries, $params_reorder_entries);

}
elseif (($filter != "default") && ($filter != "rounds"))  {
		$style_name = explode(",",$style_name);
		$params_entries = array();
		if (SINGLE) {
			$query_entries = "SELECT id,brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewInfo,brewJudgingNumber FROM ".$brewing_db_table." WHERE comp_id=? AND brewCategorySort=? AND brewSubcategory=?";
			$params_entries = array($_SESSION['comp_id'], $style_name[0], $style_name[1]);
		}
		else {
			$query_entries = "SELECT id,brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewInfo,brewJudgingNumber FROM ".$brewing_db_table." WHERE brewCategorySort=? AND brewSubcategory=?";
			$params_entries = array($style_name[0], $style_name[1]);
		}

		if ($_SESSION['jPrefsTablePlanning'] == 0) $query_entries .= " AND brewReceived='1'";
		$query_entries .= " ORDER BY brewCategorySort,brewSubCategory";

	$rows_entries = $db_conn->rawQuery($query_entries, $params_entries);
	$row_entries = ($rows_entries && count($rows_entries) > 0) ? $rows_entries[0] : null;

	//echo $query_entries;
}

if (($action == "assign") && ($filter == "rounds")) {

	// Query based upon unique variable (id of record from "judging_tables" table)
	$db_conn->where('flightTable', $flight_table);
	$db_conn->orderBy('flightNumber', 'DESC');
	$rows_flights = $db_conn->get($judging_flights_db_table, 1);
	$row_flights = ($rows_flights && count($rows_flights) > 0) ? $rows_flights[0] : null;
	$totalRows_flights = $db_conn->count;

	// Query based upon unique variable (id of record from "judging_tables" table)
	$db_conn->where('id', $flight_table);
	$row_tables = $db_conn->getOne($judging_tables_db_table, "id,tableNumber,tableName,tableLocation");

	// Query based upon unique variable (id of record from "judging_tables" table)
	$db_conn->where('id', $row_tables['tableLocation']);
	$row_table_location = $db_conn->getOne($judging_locations_db_table);
}

?>