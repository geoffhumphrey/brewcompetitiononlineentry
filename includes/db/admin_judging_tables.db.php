<?php

if (SINGLE) $query_table_number = sprintf("SELECT tableNumber FROM %s ORDER BY tableNumber WHERE comp_id='%s'", $judging_tables_db_table, $_SESSION['comp_id']);
else $query_table_number = sprintf("SELECT tableNumber FROM %s ORDER BY tableNumber",$judging_tables_db_table);
$table_number = mysqli_query($connection,$query_table_number) or die (mysqli_error($connection));
$row_table_number = mysqli_fetch_assoc($table_number);
$totalRows_table_number = mysqli_num_rows($table_number);

if ($action == "add") {

	$with_received_entries =  explode(",",received_entries());

	if (SINGLE) $query_table_number_last = sprintf("SELECT tableNumber FROM  WHERE comp_id='%s' ORDER BY tableNumber DESC LIMIT 1", $judging_tables_db_table, $_SESSION['comp_id']);
	else $query_table_number_last = sprintf("SELECT tableNumber FROM %s ORDER BY tableNumber DESC LIMIT 1",$judging_tables_db_table);
	$table_number_last = mysqli_query($connection,$query_table_number_last) or die (mysqli_error($connection));
	$row_table_number_last = mysqli_fetch_assoc($table_number_last);

	$query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s",$brewing_db_table);
	$result = mysqli_query($connection,$query_entry_count) or die (mysqli_error($connection));
	$row = mysqli_fetch_array($result);

}

if (SINGLE) {
	$query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE comp_id='%s'",$brewing_db_table, $_SESSION['comp_id']);
	if ($action == "default") $query_entry_count .= " AND brewReceived='1'";
}

else {
	$query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s",$brewing_db_table);
	if ($action == "default") $query_entry_count .= " WHERE brewReceived='1'";
}

$result = mysqli_query($connection,$query_entry_count) or die (mysqli_error($connection));
$row_entry_count = mysqli_fetch_array($result);

// Check and see if scores have been entered for this table already
$query_table_scores = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE scoreTable='%s'",$judging_scores_db_table, $id);
$table_scores = mysqli_query($connection,$query_table_scores) or die (mysqli_error($connection));
$row_table_scores = mysqli_fetch_assoc($table_scores);
if ($row_table_scores['count'] > 0) $already_scored = TRUE; else $already_scored = FALSE;

if (SINGLE) $query_flights = sprintf("SELECT id FROM %s WHERE comp_id='%s'", $judging_flights_db_table, $_SESSION['comp_id']);
else $query_flights = sprintf("SELECT id FROM %s", $judging_flights_db_table);
$flights = mysqli_query($connection,$query_flights) or die (mysqli_error($connection));
$totalRows_flights = mysqli_num_rows($flights);
?>