<?php

$query_post_inventory_entry = sprintf("SELECT id,scoreEntry,scorePlace FROM %s WHERE eid='%s'",$prefix."judging_scores",$row_post_inventory['id']);
$post_inventory_entry = mysqli_query($connection,$query_post_inventory_entry) or die (mysqli_error($connection));
$row_post_inventory_entry = mysqli_fetch_assoc($post_inventory_entry);
$totalRows_post_inventory_entry = mysqli_num_rows($post_inventory_entry);

?>