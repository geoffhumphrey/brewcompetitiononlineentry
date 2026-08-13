<?php
declare(strict_types=1);
if ($dbTable == "default") $dbTable = $prefix."brewing";
else $dbTable = $dbTable;

// This query is ONLY used in the judging_scores function when entering scores for a particular table
$select_cols = "id,brewBrewerID,brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewInfo,brewJudgingNumber,brewName";

if ($_SESSION['prefsStyleSet'] == "BA") {

	if ($value > 500) {

		$query_entries = "SELECT ".$select_cols." FROM ".$dbTable." WHERE (brewCategorySort=? AND brewSubCategory=?) AND brewReceived='1'";
		$params_entries = array($score_style_data[0], $score_style_data[1]);

		if (SINGLE) {
			$query_entries .= " AND comp_id=?";
			$params_entries[] = $_SESSION['comp_id'];
		}

	}

	else {

		$query_entries = "SELECT ".$select_cols." FROM ".$dbTable." WHERE brewSubCategory=? AND brewReceived='1'";
		$params_entries = array($score_style_data[1]);

		if (SINGLE) {
			$query_entries .= " AND comp_id=?";
			$params_entries[] = $_SESSION['comp_id'];
		}

	}

}

else {

	if (SINGLE) {
		$query_entries = "SELECT ".$select_cols." FROM ".$dbTable." WHERE comp_id = ? AND (brewCategorySort=? AND brewSubCategory=?) AND brewReceived='1'";
		$params_entries = array($_SESSION['comp_id'], $score_style_data[0], $score_style_data[1]);
	}

	else {
		$query_entries = "SELECT ".$select_cols." FROM ".$dbTable." WHERE (brewCategorySort=? AND brewSubCategory=?) AND brewReceived='1'";
		$params_entries = array($score_style_data[0], $score_style_data[1]);
	}

}

$rows_entries = $db_conn->rawQuery($query_entries, $params_entries);
$row_entries = ($rows_entries && count($rows_entries) > 0) ? $rows_entries[0] : null;
$totalRows_entries = $db_conn->count;

//echo  $query_entries." ".$totalRows_entries."<br>";
?>