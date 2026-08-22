<?php
/*
if (HOSTED) $styles_db_table = "bcoem_shared_styles";
else
*/

$styles_db_table = $prefix."styles";

if ($section == "step7") {

	$db_conn->where("id", 1);
	$row_prefs_styleset = $db_conn->getOne($prefix."preferences", "prefsStyleSet");
	$styleSet = $row_prefs_styleset['prefsStyleSet'];
	$_SESSION['prefsStyleSet'] = $row_prefs_styleset['prefsStyleSet'];

}

elseif (isset($_SESSION['prefsStyleSet'])) $styleSet = $_SESSION['prefsStyleSet'];
else $styleSet = "BJCP2025";

$styles_selected = [];
$styles_selected = json_decode($_SESSION['prefsSelectedStyles'], true);

if ((($section == "admin") && ($go == "preferences")) || ($section == "step3")) {

	// Get custom styles from all style sets
	$db_conn->where("brewStyleOwn", "custom");
	$db_conn->orderBy("brewStyleVersion", "ASC");
	$db_conn->orderBy("brewStyleGroup", "ASC");
	$db_conn->orderBy("brewStyleNum", "ASC");
	$db_conn->orderBy("brewStyle", "ASC");
	$rows_styles_all = $db_conn->get($styles_db_table, null, "id,brewStyleGroup,brewStyleNum,brewStyle,brewStyleVersion,brewStyleOwn");
	$row_styles_all = ($rows_styles_all && count($rows_styles_all) > 0) ? $rows_styles_all[0] : null;
	$totalRows_styles_all = $db_conn->count;

	$custom_styles_arr = [];

	if ($totalRows_styles_all > 0) {

		foreach ($rows_styles_all as $row_styles_all) {

			$custom_styles_arr[] = [
				"id" => $row_styles_all['id'],
				"brewStyleGroup" => $row_styles_all['brewStyleGroup'],
				"brewStyleNum" => $row_styles_all['brewStyleNum'],
				"brewStyle" => $row_styles_all['brewStyle']
			];

		}

	}

}

if ($styleSet == "BJCP2025") { $query_styles = "SELECT * FROM ".$styles_db_table." WHERE ((brewStyleVersion='BJCP2025' AND brewStyleType='2') OR (brewStyleVersion='BJCP2021' AND brewStyleType !='2') OR brewStyleOwn='custom')"; $params_styles = []; }
elseif ($styleSet == "AABC2025") { $query_styles = "SELECT * FROM ".$styles_db_table." WHERE ((brewStyleVersion='AABC2025' AND brewStyleType='2') OR (brewStyleVersion='AABC2022' AND brewStyleType !='2') OR brewStyleOwn='custom')"; $params_styles = []; }
else { $query_styles = "SELECT * FROM ".$styles_db_table." WHERE (brewStyleVersion=? OR brewStyleOwn='custom')"; $params_styles = [$styleSet]; }

if ($section == "admin") {

	if ((($action == "edit") || ($action == "add")) && ($go == "entries")) $styles_query_add = "";
	elseif ((($action == "edit") || ($action == "add")) && ($go == "judging_tables"))  $styles_query_add = "";
	elseif ((($action == "default") || ($action == "add"))&& ($go == "styles")) $styles_query_add = "";
	elseif ((($action == "entries")) && ($go == "preferences")) $styles_query_add = "";
	elseif (($go == "count_by_style") || ($go == "count_by_substyle")) $styles_query_add = "";
	else { $styles_query_add = " AND id=?"; $params_styles[] = $id; }

	$query_styles .= $styles_query_add;

}

if (($view != "default") && ($section == "styles")) {
	$explodies = explode("-",$view);
	$query_styles .= " AND brewStyleGroup=? AND brewStyleNum=?";
	$params_styles[] = $explodies[0];
	$params_styles[] = $explodies[1];
}

if ((($section == "entry") || ($section == "brew") || ($action == "word") || ($action == "html")) || ((($section == "admin") && ($filter == "judging")) && ($bid != "default"))) {
	if ($_SESSION['prefsStyleSet'] == "BA") $query_styles .= " ORDER BY brewStyleType, brewStyleGroup, brewStyle ASC";
	else $query_styles .= " ORDER BY brewStyleType, brewStyleGroup, brewStyleNum ASC";
}

elseif (($section == "admin") && ($go == "preferences")) $query_styles .= "  ORDER BY brewStyleOwn,brewStyleVersion,brewStyleType,brewStyleGroup,brewStyleNum,brewStyle ASC";
elseif (($section == "admin") && ($go == "count_by_style")) $query_styles .= "";
elseif (($section == "admin") && ($go == "styles")) $query_styles .= " ORDER BY brewStyleGroup, brewStyleNum ASC";
elseif ((($section == "judge") && ($go == "judge")) || ($go == "judging_tables") || ($action == "add") || ($action == "edit")) {
	if ($_SESSION['prefsStyleSet'] == "BA") $query_styles .= " ORDER BY brewStyleGroup, brewStyle ASC";
	else $query_styles .= " ORDER BY brewStyleType, brewStyleGroup, brewStyleNum ASC";
}

elseif ($section == "sorting") $query_styles .= "";
elseif ($section == "list") {
	if ((isset($row_log['brewCategorySort'])) && (isset($row_log['brewCategorySort']))) { $query_styles .= " AND brewStyleGroup = ? AND brewStyleNum = ?"; $params_styles[] = $row_log['brewCategorySort']; $params_styles[] = $row_log['brewSubCategory']; }
}

elseif ($section == "styles") {
	if ($filter == "default") $query_styles .= " ORDER BY brewStyleType, brewStyleGroup, brewStyleNum ASC";
	else { $query_styles .= " AND brewStyleGroup=? ORDER BY brewStyleGroup, brewStyleNum ASC"; $params_styles[] = $filter; }
}

elseif (($section == "default") || ($section == "step7")) {
	$query_styles .= " ORDER BY brewStyleType, brewStyleGroup, brewStyleNum ASC";
}

$rows_styles = ($params_styles !== []) ? $db_conn->rawQuery($query_styles, $params_styles) : $db_conn->rawQuery($query_styles);
$row_styles = ($rows_styles && count($rows_styles) > 0) ? $rows_styles[0] : null;
$totalRows_styles = $db_conn->count;

if ($section != "list") {

	/*
	if (HOSTED) $query_styles2 = ...
	else
	*/
	if ($styleSet == "BJCP2025") { $query_styles2 = "SELECT * FROM ".$styles_db_table." WHERE ((brewStyleVersion='BJCP2025' AND brewStyleType='2') OR (brewStyleVersion='BJCP2021' AND brewStyleType !='2') OR brewStyleOwn='custom')"; $params_styles2 = []; }
	elseif ($styleSet == "AABC2025") { $query_styles2 = "SELECT * FROM ".$styles_db_table." WHERE ((brewStyleVersion='AABC2025' AND brewStyleType='2') OR (brewStyleVersion='AABC2022' AND brewStyleType !='2') OR brewStyleOwn='custom')"; $params_styles2 = []; }
	else { $query_styles2 = "SELECT * FROM ".$styles_db_table." WHERE (brewStyleVersion=? OR brewStyleOwn='custom')"; $params_styles2 = [$styleSet]; }
	if (($section == "judge") && ($go == "judge")) $query_styles2 .= " ORDER BY brewStyleType, brewStyleGroup, brewStyleNum ASC";
	elseif ($section == "brew") $query_styles2 .= " AND brewStyleGroup > '28' AND brewStyleReqSpec = '1'";
	else {
		if ($styleSet == "BA") $query_styles2 .= " ORDER BY brewStyleGroup, brewStyleNum ASC";
		else $query_styles2 .= " ORDER BY brewStyleType, brewStyleGroup, brewStyleNum ASC";
	}
	$rows_styles2 = ($params_styles2 !== []) ? $db_conn->rawQuery($query_styles2, $params_styles2) : $db_conn->rawQuery($query_styles2);
	$row_styles2 = ($rows_styles2 && count($rows_styles2) > 0) ? $rows_styles2[0] : null;
	$totalRows_styles2 = $db_conn->count;

}

?>