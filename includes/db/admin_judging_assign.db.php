<?php
// @single
// Query based upon unique variable (id of record from "judging_locations" table)
$db_conn->where('id', $location);
$row_table_location = $db_conn->getOne($prefix."judging_flights");

// Query based upon unique variable (id of record from "judging_tables" table)
$db_conn->where('flightTable', $row_tables_edit['id']);
$db_conn->orderBy('flightRound', 'DESC');
$row_rounds = $db_conn->getOne($prefix."judging_flights", "flightRound");

// Query based upon unique variable (id of record from "judging_tables" table)
$db_conn->where('flightTable', $row_tables_edit['id']);
$db_conn->orderBy('flightNumber', 'DESC');
$row_flights = $db_conn->getOne($prefix."judging_flights");
$total_flights = $row_flights['flightNumber'];

// Query based upon unique variable (id of record from "judging_tables" table)
$db_conn->where('assignTable', $row_tables_edit['id']);
$row_assignments = $db_conn->getOne($judging_assignments_db_table);
$totalRows_assignments = $db_conn->count;

?>