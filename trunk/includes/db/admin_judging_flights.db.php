<?php 
if (($filter != "default") && ($filter != "rounds"))  { 
	$query_entries = sprintf("SELECT id,brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewInfo,brewJudgingNumber FROM $brewing_db_table WHERE brewStyle='%s' AND brewReceived='1' ORDER BY brewCategorySort,brewSubCategory", $style_name);
	$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
	$row_entries = mysql_fetch_assoc($entries);
}

if (($action == "assign") && ($filter == "rounds")) { 

	$query_flights = sprintf("SELECT * FROM $judging_flights_db_table WHERE flightTable='%s' ORDER BY flightNumber DESC LIMIT 1", $flight_table);
	$flights = mysql_query($query_flights, $brewing) or die(mysql_error());
	$row_flights = mysql_fetch_assoc($flights);
	$totalRows_flights = mysql_num_rows($flights);

	$query_tables = sprintf("SELECT id,tableNumber,tableName,tableLocation FROM $judging_tables_db_table WHERE id='%s'",$flight_table);
	$tables = mysql_query($query_tables, $brewing) or die(mysql_error());
	$row_tables = mysql_fetch_assoc($tables);

	$query_table_location = sprintf("SELECT * FROM $judging_locations_db_table WHERE id='%s'",$row_tables['tableLocation']);
	$table_location = mysql_query($query_table_location, $brewing) or die(mysql_error());
	$row_table_location = mysql_fetch_assoc($table_location);
}

?>