<?php
if ($dbTable == "default") $dbTable = $prefix."brewing";
else $dbTable = $dbTable;

// This query is ONLY used in the judging_scores function when entering scores for a particular table
if ($_SESSION['prefsStyleSet'] == "BA") {

	if ($value > 500) {

		$query_entries = $query_entries = sprintf("SELECT id,brewBrewerID,brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewInfo,brewJudgingNumber,brewName FROM %s WHERE (brewCategorySort='%s' AND brewSubCategory='%s') AND brewReceived='1'", $dbTable, $score_style_data[0], $score_style_data[1]);

		if (SINGLE) $query_entries .= sprintf(" AND comp_id='%s'",$_SESSION['comp_id']);

	}

	else {

		$query_entries = sprintf("SELECT id,brewBrewerID,brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewInfo,brewJudgingNumber,brewName FROM %s WHERE brewSubCategory='%s' AND brewReceived='1'", $dbTable, $score_style_data[1]);

		if (SINGLE) $query_entries .= sprintf(" AND comp_id='%s'",$_SESSION['comp_id']);

	}

}

else {

	if (SINGLE) $query_entries = sprintf("SELECT id,brewBrewerID,brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewInfo,brewJudgingNumber,brewName FROM %s WHERE comp_id = '%s' AND (brewCategorySort='%s' AND brewSubCategory='%s') AND brewReceived='1'", $dbTable, $_SESSION['comp_id'], $score_style_data[0], $score_style_data[1]);

	else $query_entries = sprintf("SELECT id,brewBrewerID,brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewInfo,brewJudgingNumber,brewName FROM %s WHERE (brewCategorySort='%s' AND brewSubCategory='%s') AND brewReceived='1'", $dbTable, $score_style_data[0], $score_style_data[1]);

}

$entries = mysqli_query($connection,$query_entries) or die (mysqli_error($connection));
$row_entries = mysqli_fetch_assoc($entries);
$totalRows_entries = mysqli_num_rows($entries);

//echo  $query_entries." ".$totalRows_entries."<br>";
?>