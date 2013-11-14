<?php
// Display by Table
if ($_SESSION['prefsWinnerMethod'] == 0) $query_scores = sprintf("SELECT a.scorePlace, a.scoreEntry, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerClubs FROM %s a, %s b, %s c WHERE scoreTable='%s' AND a.eid = b.id AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table,  $row_tables['id']);

// Display by Category
if ($_SESSION['prefsWinnerMethod'] == 1) $query_scores = sprintf("SELECT a.scorePlace, a.scoreEntry, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerClubs FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND a.eid = b.id AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style);

// Display by Subcategory
if ($_SESSION['prefsWinnerMethod'] == 2) $query_scores = sprintf("SELECT a.scorePlace, a.scoreEntry, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerClubs FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND b.brewSubCategory='%s' AND a.eid = b.id  AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style[0], $style[1]);

if ((($action == "print") && ($view == "winners")) || ($action == "default") || ($section == "default")) $query_scores .= " AND a.scorePlace IS NOT NULL";
$query_scores .= " ORDER BY a.scorePlace ASC";
$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
$row_scores = mysql_fetch_assoc($scores);
$totalRows_scores = mysql_num_rows($scores);

?>