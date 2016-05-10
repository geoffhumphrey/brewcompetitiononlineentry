<?php
$query_table_round = sprintf("SELECT COUNT(*) as count from $judging_flights_db_table WHERE flightTable='%s' AND flightRound='%s'",$row_tables['id'],$round);
$table_round  = mysqli_query($connection,$query_table_round) or die (mysqli_error($connection));
$row_table_round = mysqli_fetch_assoc($table_round);

//echo $query_table_round;
?>