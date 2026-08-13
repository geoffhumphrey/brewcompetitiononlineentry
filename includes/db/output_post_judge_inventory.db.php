<?php
declare(strict_types=1);
$db_conn->orderBy('brewCategory', 'ASC');
$db_conn->orderBy('brewSubCategory', 'ASC');
$rows_post_inventory = $db_conn->get($prefix."brewing", null, "id, brewJudgingNumber, brewName, brewCategory, brewCategorySort, brewSubCategory, brewStyle, brewInfo, brewMead1, brewMead2, brewMead3");
$row_post_inventory = ($rows_post_inventory && count($rows_post_inventory) > 0) ? $rows_post_inventory[0] : null;
$totalRows_post_inventory = $db_conn->count;
?>