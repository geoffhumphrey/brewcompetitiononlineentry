<?php
$query_scores = "SELECT scoreEntry,scorePlace,scoreType FROM ".$prefix."judging_scores WHERE eid=?";
$params_scores = [$row_sql['id']];
if (SINGLE) { $query_scores .= " AND comp_id=?"; $params_scores[] = $_SESSION['comp_id']; }
$row_scores = $db_conn->rawQueryOne($query_scores, $params_scores);

$query_flight = "SELECT * FROM ".$prefix."judging_flights WHERE flightEntryID=?";
$params_flight = [$row_sql['id']];
if (SINGLE) { $query_flight .= " AND comp_id=?"; $params_flight[] = $_SESSION['comp_id']; }
$row_flight = $db_conn->rawQueryOne($query_flight, $params_flight);

$query_bos = "SELECT scorePlace FROM ".$prefix."judging_scores_bos WHERE eid=?";
$params_bos = [$row_sql['id']];
if (SINGLE) { $query_bos .= " AND comp_id=?"; $params_bos[] = $_SESSION['comp_id']; }
$row_bos = $db_conn->rawQueryOne($query_bos, $params_bos);
$totalRows_bos = $db_conn->count;

if ($totalRows_bos > 0) $bos_place = $row_bos['scorePlace']; else $bos_place = "";

if ($row_scores) $style_type_entry = style_type($row_scores['scoreType'],2,"bcoe");

if (isset($row_flight['flightTable'])) {
	$table_info = explode("^",get_table_info(1,"basic",$row_flight['flightTable'],"default","default"));
	$table_name = sprintf("%02s",$table_info[0]).": ".html_entity_decode($table_info[1]);
	$location = explode("^",get_table_info($table_info[2],"location",$row_flight['flightTable'],"default","default"));
} else {
	$table_info = "";
	$table_name = "00: Not Assigned to a Table";
	$location = " ^ ";
}
?>