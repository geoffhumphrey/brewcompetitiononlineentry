<?php

if ($view == "entry") $order = "b.id";
else $order = "b.brewJudgingNumber";

$query_entries_mini = sprintf("SELECT b.id, b.brewStyle, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewInfo, b.brewMead1, b.brewMead2, b.brewMead3, b.brewJudgingNumber, b.brewBoxNum, b.brewComments, b.brewInfoOptional, b.brewPossAllergens, b.brewStaffNotes FROM %s a, %s b WHERE scoreMiniBOS='1' AND a.eid = b.id ORDER BY %s", $judging_scores_db_table, $brewing_db_table, $order);
$entries_mini = mysqli_query($connection,$query_entries_mini) or die (mysqli_error($connection));
$row_entries_mini = mysqli_fetch_assoc($entries_mini);
$totalRows_entries_mini = mysqli_num_rows($entries_mini);

?>