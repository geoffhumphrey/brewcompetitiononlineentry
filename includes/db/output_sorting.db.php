<?php
if ((strpos($styleSet,"BABDB") !== false) && ($style < 35)) $category_sort = "brewCategory";
else $category_sort = "brewCategorySort";

// Note: $category_sort is restricted above to one of two hardcoded literal column names
// (never derived from request input), so splicing it here does not introduce an injection risk.
$query_entries = "SELECT id,brewName,brewStyle,brewCategory,brewCategorySort,brewSubCategory,brewBrewerLastName,brewBrewerFirstName,brewBrewerID,brewJudgingNumber,brewPaid,brewReceived FROM ".$prefix."brewing WHERE ".$category_sort."=?";
$rows_entries = $db_conn->rawQuery($query_entries, array($style));
$totalRows_entries = $db_conn->count;
?>