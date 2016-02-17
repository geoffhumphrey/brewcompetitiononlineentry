<?php

$query_post_inventory_entry = sprintf("SELECT id,scoreEntry,scorePlace FROM %s WHERE eid='%s'",$prefix."judging_scores",$row_post_inventory['id']);
$post_inventory_entry = mysql_query($query_post_inventory_entry, $brewing) or die(mysql_error());
$row_post_inventory_entry = mysql_fetch_assoc($post_inventory_entry);
$totalRows_post_inventory_entry = mysql_num_rows($post_inventory_entry);

?>