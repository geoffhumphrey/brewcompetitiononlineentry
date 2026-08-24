<?php
/**
 * Code is executed when rendering a scoresheet.
 * Not used for any other purposes.
 */

$styles_db_table = $prefix."styles";
$db_conn->where("id", $id);
$row_eval = $db_conn->getOne($dbTable);

$db_conn->where("uid", $row_eval['evalJudgeInfo']);
$row_judge = $db_conn->getOne($prefix."brewer".$archive_suffix);

if (empty($row_eval['evalStyle'])) {

	$db_conn->where("id", $row_eval['eid']);
	$row_brewing = $db_conn->getOne($prefix."brewing".$archive_suffix);

	// NOTE: the BJCP2025 branch's OR-group must stay explicitly parenthesized in raw SQL text -
	// MysqliDb's where()/orWhere() builder has no parenthesization support, so chaining where()
	// calls here would silently produce "... AND brewStyleNum=? AND brewStyleVersion=? OR brewStyleVersion=?"
	// (wrong precedence) instead of the intended "... AND (brewStyleVersion=? OR brewStyleVersion=?)".
	if ($_SESSION['prefsStyleSet'] == "BJCP2025") {
		$query_style = "SELECT id,brewStyle,brewStyleGroup,brewStyleNum,brewStyleType FROM ".$styles_db_table." WHERE brewStyle=? AND brewStyleGroup=? AND brewStyleNum=? AND (brewStyleVersion='BJCP2025' OR brewStyleVersion='BJCP2021')";
		$params_style = array($row_brewing['brewStyle'], $row_brewing['brewCategorySort'], $row_brewing['brewSubCategory']);
	}

	elseif ($_SESSION['prefsStyleSet'] == "AABC2025") {
		$query_style = "SELECT id,brewStyle,brewStyleGroup,brewStyleNum,brewStyleType FROM ".$styles_db_table." WHERE brewStyle=? AND brewStyleGroup=? AND brewStyleNum=? AND (brewStyleVersion='AABC2025' OR brewStyleVersion='AABC2022')";
		$params_style = array($row_brewing['brewStyle'], $row_brewing['brewCategorySort'], $row_brewing['brewSubCategory']);
	}

	else {
		$query_style = "SELECT id,brewStyle,brewStyleGroup,brewStyleNum,brewStyleType FROM ".$styles_db_table." WHERE brewStyle=? AND brewStyleGroup=? AND brewStyleNum=? AND brewStyleVersion=?";
		$params_style = array($row_brewing['brewStyle'], $row_brewing['brewCategorySort'], $row_brewing['brewSubCategory'], $_SESSION['prefsStyleSet']);
	}

	$row_style = $db_conn->rawQueryOne($query_style, $params_style);

}

else {

	$db_conn->where("id", $row_eval['evalStyle']);
	$row_style = $db_conn->getOne($styles_db_table, "brewStyle,brewStyleGroup,brewStyleNum,brewStyleType");

}

$db_conn->where("id", $row_eval['eid']);
$row_entry_info = $db_conn->getOne($prefix."brewing".$archive_suffix);
?>