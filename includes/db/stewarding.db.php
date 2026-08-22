<?php	
if ($section == "list") $db_conn->where('id', $row_brewer['brewerStewardLocation']);
if (in_array($section, ["brewer", "admin", "register"])) {
	$db_conn->orderBy('judgingDate', 'ASC');
	$db_conn->orderBy('judgingLocName', 'ASC');
}
$rows_stewarding = $db_conn->get($judging_locations_db_table);
$row_stewarding = ($rows_stewarding && count($rows_stewarding) > 0) ? $rows_stewarding[0] : null;
$totalRows_stewarding = $db_conn->count;

if ($section == "list") $db_conn->where('id', $row_brewer['brewerStewardLocation2']);
if (in_array($section, ["brewer", "admin", "register"])) {
	$db_conn->orderBy('judgingDate', 'ASC');
	$db_conn->orderBy('judgingLocName', 'ASC');
}
$rows_stewarding2 = $db_conn->get($judging_locations_db_table);
$row_stewarding2 = ($rows_stewarding2 && count($rows_stewarding2) > 0) ? $rows_stewarding2[0] : null;
$totalRows_stewarding2 = $db_conn->count;

?>