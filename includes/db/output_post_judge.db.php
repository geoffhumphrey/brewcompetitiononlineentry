<?php
declare(strict_types=1);

$db_conn->where('eid', $row_post_inventory['id']);
$rows_post_inventory_entry = $db_conn->get($prefix."judging_scores", null, "id,scoreEntry,scorePlace");
$row_post_inventory_entry = ($rows_post_inventory_entry && count($rows_post_inventory_entry) > 0) ? $rows_post_inventory_entry[0] : null;
$totalRows_post_inventory_entry = $db_conn->count;

?>