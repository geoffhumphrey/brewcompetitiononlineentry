<?php
$query_scores = sprintf("SELECT scoreEntry,scorePlace,scoreType FROM $judging_scores_db_table WHERE eid='%s'",$row_sql['id']);
if (SINGLE) $query_scores .= sprintf(" AND comp_id='%s'",$_SESSION['comp_id']);
$scores = mysqli_query($connection,$query_scores) or die (mysqli_error($connection));
$row_scores = mysqli_fetch_assoc($scores);

$query_flight = sprintf("SELECT * FROM $judging_flights_db_table WHERE flightEntryID='%s'",$row_sql['id']);
if (SINGLE) $query_flight .= sprintf(" AND comp_id='%s'",$_SESSION['comp_id']);
$flight = mysqli_query($connection,$query_flight) or die (mysqli_error($connection));
$row_flight = mysqli_fetch_assoc($flight);

$query_bos = sprintf("SELECT scorePlace FROM $judging_scores_bos_db_table WHERE eid='%s'",$row_sql['id']);
if (SINGLE) $query_bos .= sprintf(" AND comp_id='%s'",$_SESSION['comp_id']);
$bos = mysqli_query($connection,$query_bos) or die (mysqli_error($connection));
$row_bos = mysqli_fetch_assoc($bos);
$totalRows_bos = mysqli_num_rows($bos);

if ($totalRows_bos > 0) $bos_place = $row_bos['scorePlace']; else $bos_place = "";

$style_type_entry = style_type($row_scores['scoreType'],2,"bcoe");

if (isset($row_flight['flightTable'])) {
	$table_info = explode("^",get_table_info(1,"basic",$row_flight['flightTable'],"default","default"));
	$table_name = sprintf("%02s",$table_info[0]).": ".$table_info[1];
	$location = explode("^",get_table_info($table_info[2],"location",$row_flight['flightTable'],"default","default"));
} else {
	$table_info = "";
	$table_name = "00: Not Assigned to a Table";
	$location = " ^ ";
}
?>