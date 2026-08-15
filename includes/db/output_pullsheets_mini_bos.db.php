<?php

// $order is restricted to 2 hardcoded literal column names below, never user input - safe to splice as identifier text
if ($view == "entry") $order = "b.id";
else $order = "b.brewJudgingNumber";

$query_entries_mini = "SELECT b.id, b.brewStyle, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewInfo, b.brewMead1, b.brewMead2, b.brewMead3, b.brewJudgingNumber, b.brewBoxNum, b.brewComments, b.brewInfoOptional, b.brewPossAllergens, b.brewStaffNotes, b.brewJuiceSource, b.brewABV, b.brewPouring, b.brewStyleType, b.brewPackaging FROM ".$prefix."judging_scores"." a, ".$prefix."brewing"." b WHERE scoreMiniBOS='1' AND a.eid = b.id ORDER BY ".$order;
$rows_entries_mini = $db_conn->rawQuery($query_entries_mini);
$row_entries_mini = ($rows_entries_mini && count($rows_entries_mini) > 0) ? $rows_entries_mini[0] : null;
$totalRows_entries_mini = $db_conn->count;

?>