<?php

if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) {
	$query_styles = sprintf("SELECT brewStyleGroup,brewStyleNum FROM %s WHERE id='%s'", $styles_db_table, $value);
	$styles = mysqli_query($connection,$query_styles) or die (mysqli_error($connection));
	$row_styles = mysqli_fetch_assoc($styles);
}

// Mini-BOS Pullsheets
if ($filter == "mini_bos") { 

	if ($view == "default") $order = "b.brewJudgingNumber";
	else $order = "b.id";
	
	if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false) $query_entries = sprintf("SELECT a.scoreMiniBOS, b.id, b.brewStyle, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewInfo, b.brewMead1, b.brewMead2, b.brewMead3, b.brewJudgingNumber, b.brewBoxNum, b.brewComments, b.brewInfoOptional FROM %s a, %s b WHERE b.brewSubCategory='%s' AND a.eid = b.id AND a.scoreMiniBOS='1' ORDER BY %s", $judging_scores_db_table, $brewing_db_table, $value, $order);
	
	else $query_entries = sprintf("SELECT a.scoreMiniBOS, b.id, b.brewStyle, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewInfo, b.brewMead1, b.brewMead2, b.brewMead3, b.brewJudgingNumber, b.brewBoxNum, b.brewComments, b.brewInfoOptional FROM %s a, %s b WHERE b.brewCategorySort='%s' AND b.brewSubCategory='%s' AND a.eid = b.id AND a.scoreMiniBOS='1' ORDER BY %s", $judging_scores_db_table, $brewing_db_table, $row_styles['brewStyleGroup'], $row_styles['brewStyleNum'], $order);


}

// All Other Pullsheets
else { 
	

	if ($view == "default") $order = "brewJudgingNumber";
	else $order = "id";
	
	if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false) $query_entries = sprintf("SELECT id,brewStyle, brewCategory, brewCategorySort, brewSubCategory, brewInfo, brewMead1, brewMead2, brewMead3, brewJudgingNumber, brewBoxNum, brewComments, brewInfoOptional FROM %s WHERE brewSubCategory='%s' AND brewReceived='1' ORDER BY %s ASC", $prefix."brewing", $value, $order);
	else $query_entries = sprintf("SELECT id,brewStyle, brewCategory, brewCategorySort, brewSubCategory, brewInfo, brewMead1, brewMead2, brewMead3, brewJudgingNumber, brewBoxNum, brewComments, brewInfoOptional FROM %s WHERE brewCategorySort='%s' AND brewSubCategory='%s' AND brewReceived='1' ORDER BY %s ASC", $prefix."brewing", $row_styles['brewStyleGroup'], $row_styles['brewStyleNum'], $order);

}

$entries = mysqli_query($connection,$query_entries) or die (mysqli_error($connection));
$row_entries = mysqli_fetch_assoc($entries);
$totalRows_entries = mysqli_num_rows($entries);
?>