<?php
$query_table_location = sprintf("SELECT * FROM %s WHERE id='%s'",$prefix."judging_flights", $location);
$table_location = mysqli_query($connection,$query_table_location) or die (mysqli_error($connection));
$row_table_location = mysqli_fetch_assoc($table_location);

$query_rounds = sprintf("SELECT flightRound FROM %s WHERE flightTable='%s' ORDER BY flightRound DESC LIMIT 1", $prefix."judging_flights", $row_tables_edit['id']);
$rounds = mysqli_query($connection,$query_rounds) or die (mysqli_error($connection));
$row_rounds = mysqli_fetch_assoc($rounds);

$query_flights = sprintf("SELECT * FROM %s WHERE flightTable='%s' ORDER BY flightNumber DESC LIMIT 1", $prefix."judging_flights", $row_tables_edit['id']);
$flights = mysqli_query($connection,$query_flights) or die (mysqli_error($connection));
$row_flights = mysqli_fetch_assoc($flights);
$total_flights = $row_flights['flightNumber'];

$query_assignments = sprintf("SELECT * FROM $judging_assignments_db_table WHERE assignTable='%s'", $row_tables_edit['id']);
$assignments = mysqli_query($connection,$query_assignments) or die (mysqli_error($connection));
$row_assignments = mysqli_fetch_assoc($assignments);
$totalRows_assignments = mysqli_num_rows($assignments);
?>