<?php
$query_scores = sprintf("SELECT scoreEntry,scorePlace,scoreType FROM $judging_scores_db_table WHERE eid='%s'",$row_sql['id']);
$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
$row_scores = mysql_fetch_assoc($scores);

$query_flight = sprintf("SELECT * FROM $judging_flights_db_table WHERE flightEntryID='%s'",$row_sql['id']);
$flight = mysql_query($query_flight, $brewing) or die(mysql_error());
$row_flight = mysql_fetch_assoc($flight);

$query_bos = sprintf("SELECT scorePlace FROM $judging_scores_bos_db_table WHERE eid='%s'",$row_sql['id']);
$bos = mysql_query($query_bos, $brewing) or die(mysql_error());
$row_bos = mysql_fetch_assoc($bos);
$totalRows_bos = mysql_num_rows($bos);
if ($totalRows_bos > 0) $bos_place = $row_bos['scorePlace']; else $bos_place = "";

$style_type = style_type($row_scores['scoreType'],2,"bcoe");
$location = explode("^",get_table_info(1,"location",$row_flight['flightTable'],"default","default"));
$table_info = explode("^",get_table_info(1,"basic",$row_flight['flightTable'],"default","default"));
$table_name = sprintf("%02s",$table_info[0]).": ".$table_info[1];
?>