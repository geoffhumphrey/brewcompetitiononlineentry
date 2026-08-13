<?php
if (($section == "admin") && ($action == "edit") && ($go == "dropoff")) {
	$db_conn->where("id", $id);
} else {
	$db_conn->orderBy("dropLocationName", "ASC");
}
$rows_dropoff = $db_conn->get($drop_off_db_table);
$row_dropoff = ($rows_dropoff && count($rows_dropoff) > 0) ? $rows_dropoff[0] : null;
$totalRows_dropoff = $db_conn->count;
?>