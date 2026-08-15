<?php

if ($action == "edit") {
	$db_conn->where("id", $id);
} else {
	if ($dbTable == "default") $sponsors_db_table = $prefix."sponsors";
	if ($dbTable != "default") {
		$dbTable_clean = preg_replace("/[^a-zA-Z0-9_]+/", "", $dbTable);
		$sponsors_db_table = $dbTable_clean;
	}
	$db_conn->orderBy("sponsorLevel", "ASC");
	$db_conn->orderBy("sponsorName", "ASC");
}
$rows_sponsors = $db_conn->get($sponsors_db_table);
$row_sponsors = ($rows_sponsors && count($rows_sponsors) > 0) ? $rows_sponsors[0] : null;
$totalRows_sponsors = $db_conn->count;

?>