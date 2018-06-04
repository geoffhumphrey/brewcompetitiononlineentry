<?php
$query_post_inventory = sprintf("SELECT id, brewJudgingNumber, brewName, brewCategory, brewCategorySort, brewSubCategory, brewStyle, brewInfo, brewMead1, brewMead2, brewMead3 FROM %s ORDER BY brewCategory,brewSubCategory ASC",$prefix."brewing");
$post_inventory = mysqli_query($connection,$query_post_inventory) or die (mysqli_error($connection));
$row_post_inventory = mysqli_fetch_assoc($post_inventory);
$totalRows_post_inventory = mysqli_num_rows($post_inventory);
?>