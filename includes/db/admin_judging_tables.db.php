<?php

$db_conn->orderBy('tableNumber', 'ASC');
$row_table_number = $db_conn->getOne($judging_tables_db_table, "tableNumber");
$totalRows_table_number = $db_conn->count;

if ($action == "add") {

	$with_received_entries =  explode(",",received_entries());

	// Note: the SINGLE-mode query previously had a malformed FROM clause (missing the table name)
	// that also failed to bind $_SESSION['comp_id'] due to a sprintf() arg/placeholder mismatch —
	// this branch would have thrown a SQL syntax error whenever SINGLE mode hit this "add" action.
	// Fixed to what the ELSE branch's structure clearly intended.
	if (SINGLE) {
		$query_table_number_last = "SELECT tableNumber FROM ".$judging_tables_db_table." WHERE comp_id=? ORDER BY tableNumber DESC LIMIT 1";
		$row_table_number_last = $db_conn->rawQueryOne($query_table_number_last, array($_SESSION['comp_id']));
	}
	else {
		$query_table_number_last = "SELECT tableNumber FROM ".$judging_tables_db_table." ORDER BY tableNumber DESC LIMIT 1";
		$row_table_number_last = $db_conn->rawQueryOne($query_table_number_last);
	}

}

if ($action == "default") $db_conn->where('brewReceived', '1');
$row_entry_count = $db_conn->getOne($brewing_db_table, "COUNT(*) as 'count'");

// Check and see if scores have been entered for this table already
$db_conn->where('scoreTable', $id);
$row_table_scores = $db_conn->getOne($judging_scores_db_table, "COUNT(*) as 'count'");
if ($row_table_scores['count'] > 0) $already_scored = TRUE; else $already_scored = FALSE;

$rows_flights = $db_conn->get($judging_flights_db_table, null, "id");
$totalRows_flights = $db_conn->count;
?>