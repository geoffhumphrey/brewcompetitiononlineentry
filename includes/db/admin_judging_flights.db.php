<?php
if (($filter != "default") && ($filter != "rounds"))  {


		$style_name = explode(",",$style_name);
		if (SINGLE) $query_entries = sprintf("SELECT id,brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewInfo,brewJudgingNumber FROM %s WHERE comp_id='%s' AND brewCategorySort='%s' AND brewSubcategory='%s' AND brewReceived='1' ORDER BY brewCategorySort,brewSubCategory", $brewing_db_table, $_SESSION['comp_id'], $style_name[0],$style_name[1]);
		else $query_entries = sprintf("SELECT id,brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewInfo,brewJudgingNumber FROM %s WHERE brewCategorySort='%s' AND brewSubcategory='%s' AND brewReceived='1' ORDER BY brewCategorySort,brewSubCategory", $brewing_db_table, $style_name[0],$style_name[1]);

	$entries = mysqli_query($connection,$query_entries) or die (mysqli_error($connection));
	$row_entries = mysqli_fetch_assoc($entries);

	//echo $query_entries;
}

if (($action == "assign") && ($filter == "rounds")) {

	// Query based upon unique variable (id of record from "judging_tables" table)
	$query_flights = sprintf("SELECT * FROM $judging_flights_db_table WHERE flightTable='%s' ORDER BY flightNumber DESC LIMIT 1", $flight_table);
	$flights = mysqli_query($connection,$query_flights) or die (mysqli_error($connection));
	$row_flights = mysqli_fetch_assoc($flights);
	$totalRows_flights = mysqli_num_rows($flights);

	// Query based upon unique variable (id of record from "judging_tables" table)
	$query_tables = sprintf("SELECT id,tableNumber,tableName,tableLocation FROM $judging_tables_db_table WHERE id='%s'",$flight_table);
	$tables = mysqli_query($connection,$query_tables) or die (mysqli_error($connection));
	$row_tables = mysqli_fetch_assoc($tables);

	// Query based upon unique variable (id of record from "judging_tables" table)
	$query_table_location = sprintf("SELECT * FROM $judging_locations_db_table WHERE id='%s'",$row_tables['tableLocation']);
	$table_location = mysqli_query($connection,$query_table_location) or die (mysqli_error($connection));
	$row_table_location = mysqli_fetch_assoc($table_location);
}

?>