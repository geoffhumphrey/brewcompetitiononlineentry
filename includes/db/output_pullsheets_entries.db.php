<?php
declare(strict_types=1);
/*
if (HOSTED) $styles_db_table = "bcoem_shared_styles";
else
*/
$styles_db_table = $prefix."styles";

/*
if (HOSTED) $query_styles = sprintf("SELECT brewStyleGroup,brewStyleNum FROM %s WHERE id='%s' UNION ALL SELECT brewStyleGroup,brewStyleNum FROM %s WHERE id='%s'", $styles_db_table, $value, $prefix."styles", $value);
else 
*/
$db_conn->where('id', $value);
$row_styles = $db_conn->getOne($styles_db_table, "brewStyleGroup,brewStyleNum");

// Mini-BOS Pullsheets (by Table or Category)
// Unified Mini-BOS query housed in /includes/db/output_pullsheets_mini_bos.db.php
if ($filter == "mini_bos") {

	// $order is restricted to 2 hardcoded literal column names above, never user input - safe to splice as identifier text
	if ($view == "default") $order = "b.brewJudgingNumber";
	else $order = "b.id";

	$query_entries = "SELECT a.scoreMiniBOS, b.id, b.brewStyle, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewInfo, b.brewMead1, b.brewMead2, b.brewMead3, b.brewJudgingNumber, b.brewBoxNum, b.brewComments, b.brewInfoOptional, b.brewPossAllergens, b.brewStaffNotes, b.brewABV, b.brewJuiceSource, b.brewSweetnessLevel, b.brewPouring, b.brewStyleType, b.brewPackaging FROM ".$prefix."judging_scores"." a, ".$prefix."brewing"." b WHERE b.brewCategorySort=? AND b.brewSubCategory=? AND a.eid = b.id AND a.scoreMiniBOS='1' ORDER BY ".$order;
	$params_entries = array($row_styles['brewStyleGroup'], $row_styles['brewStyleNum']);

}

// All Other Pullsheets
else {

	// $order is restricted to 2 hardcoded literal column names above, never user input - safe to splice as identifier text
	if ($view == "default") $order = "brewJudgingNumber";
	else $order = "id";

	$received = TRUE;
	if ($view == "judge_inventory") {
		if ($_SESSION['jPrefsTablePlanning'] == 1) $received = FALSE;
	}

	$query_entries = "SELECT * FROM ".$prefix."brewing"." WHERE brewCategorySort=? AND brewSubCategory=?";
	$params_entries = array($row_styles['brewStyleGroup'], $row_styles['brewStyleNum']);

	if ($received) $query_entries .= " AND brewReceived='1'";
	$query_entries .= " ORDER BY ".$order." ASC";

}

$rows_entries = $db_conn->rawQuery($query_entries, $params_entries);
$row_entries = ($rows_entries && count($rows_entries) > 0) ? $rows_entries[0] : null;
$totalRows_entries = $db_conn->count;
?>