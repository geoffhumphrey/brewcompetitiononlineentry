<?php
if ((strpos($styleSet,"BABDB") !== false) && ($style < 35)) $category_sort = "brewCategory";
else $category_sort = "brewCategorySort";

$query_entries = sprintf("SELECT id,brewName,brewStyle,brewCategory,brewCategorySort,brewSubCategory,brewBrewerLastName,brewBrewerFirstName,brewBrewerID,brewJudgingNumber,brewPaid,brewReceived FROM $brewing_db_table WHERE %s='%s'", $category_sort,$style);
$entries = mysqli_query($connection,$query_entries) or die (mysqli_error($connection));
$row_entries = mysqli_fetch_assoc($entries);
$totalRows_entries = mysqli_num_rows($entries);
?>