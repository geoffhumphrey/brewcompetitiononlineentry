<?php
$query_post_inventory = sprintf("SELECT b.id, b.brewJudgingNumber, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewInfo, a.scoreEntry FROM %s a, %s b WHERE a.scorePLACE IS NULL AND a.eid = b.id ORDER BY b.brewCategory,b.brewSubCategory,a.scoreEntry ASC",$prefix."judging_scores",$prefix."brewing");
$post_inventory = mysql_query($query_post_inventory, $brewing) or die(mysql_error());
$row_post_inventory = mysql_fetch_assoc($post_inventory);
$totalRows_post_inventory = mysql_num_rows($post_inventory);
?>