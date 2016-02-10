<?php
$query_table_round = sprintf("SELECT COUNT(*) as count from $judging_flights_db_table WHERE flightTable='%s' AND flightRound='%s'",$row_tables['id'],$round);
$table_round  = mysql_query($query_table_round, $brewing) or die(mysql_error());
$row_table_round = mysql_fetch_assoc($table_round);

//echo $query_table_round;
?>