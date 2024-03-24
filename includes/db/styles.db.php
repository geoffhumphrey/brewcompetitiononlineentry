<?php
/*
if (HOSTED) $styles_db_table = "bcoem_shared_styles";
else
*/
$styles_db_table = $prefix."styles";

if ($section == "step7") {
	$query_prefs_styleset = sprintf("SELECT prefsStyleSet FROM %s WHERE id='1'",$prefix."preferences");
	$prefs_styleset = mysqli_query($connection,$query_prefs_styleset) or die (mysqli_error($connection));
	$row_prefs_styleset = mysqli_fetch_assoc($prefs_styleset);
	$styleSet = $row_prefs_styleset['prefsStyleSet'];
	$_SESSION['prefsStyleSet'] = $row_prefs_styleset['prefsStyleSet'];
}

elseif (isset($_SESSION['prefsStyleSet'])) $styleSet = $_SESSION['prefsStyleSet'];
else $styleSet = "BJCP2021";

$styles_selected = array();
$styles_selected = json_decode($_SESSION['prefsSelectedStyles'], true);

if ((($section == "admin") && ($go == "preferences")) || ($section == "step3")) {

	// Get custom styles from all style sets
	/*
	if (HOSTED) $query_styles_all = sprintf("SELECT id,brewStyleGroup,brewStyleNum,brewStyle,brewStyleVersion,brewStyleOwn FROM %s WHERE brewStyleOwn='custom' ORDER BY brewStyleVersion,brewStyleGroup,brewStyleNum,brewStyle ASC;",$prefix."styles");
	else
	*/
	$query_styles_all = sprintf("SELECT id,brewStyleGroup,brewStyleNum,brewStyle,brewStyleVersion,brewStyleOwn FROM %s WHERE brewStyleOwn='custom' ORDER BY brewStyleVersion,brewStyleGroup,brewStyleNum,brewStyle ASC;",$styles_db_table);
	$styles_all = mysqli_query($connection,$query_styles_all) or die (mysqli_error($connection));
	$row_styles_all = mysqli_fetch_assoc($styles_all);
	$totalRows_styles_all = mysqli_num_rows($styles_all);
	
	$custom_styles_arr = array();
	
	if ($totalRows_styles_all > 0) {

		do {
				
			$custom_styles_arr[] = array(
				"id" => $row_styles_all['id'],
				"brewStyleGroup" => $row_styles_all['brewStyleGroup'],
				"brewStyleNum" => $row_styles_all['brewStyleNum'],
				"brewStyle" => $row_styles_all['brewStyle']
			);

		} while ($row_styles_all = mysqli_fetch_assoc($styles_all));
		
	}

}

/*
if (HOSTED) {
	
	$query_styles = sprintf("SELECT * FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') UNION ALL SELECT * FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom')", $styles_db_table, $styleSet, $prefix."styles", $styleSet);

	// Exceptions
	if (($section == "admin") && ($action == "edit") && ($go != "judging_tables")) {
		$query_styles = sprintf("SELECT * FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom')", $prefix."styles", $styleSet);
		$query_styles .= " AND id='$id'";
	}

}
*/

$query_styles = sprintf("SELECT * FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom')", $styles_db_table, $styleSet);
if (($section == "admin") && ($action == "edit") && ($go != "judging_tables")) $query_styles .= " AND id='$id'";

if (($view != "default") && ($section == "styles")) {
	$explodies = explode("-",$view);
	$query_styles .= sprintf(" AND brewStyleGroup='%s' AND brewStyleNum='%s'",$explodies[0],$explodies[1]);
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
	if ((isset($row_log['brewCategorySort'])) && (isset($row_log['brewCategorySort']))) $query_styles .= sprintf(" AND brewStyleGroup = '%s' AND brewStyleNum = '%s'", $row_log['brewCategorySort'], $row_log['brewSubCategory']);
}

elseif ($section == "styles") {
	if ($filter == "default") $query_styles .= " ORDER BY brewStyleType, brewStyleGroup, brewStyleNum ASC";
	else $query_styles .= " AND brewStyleGroup='$filter' ORDER BY brewStyleGroup, brewStyleNum ASC";
}

$styles = mysqli_query($connection,$query_styles) or die (mysqli_error($connection));
$row_styles = mysqli_fetch_assoc($styles);
$totalRows_styles = mysqli_num_rows($styles);

if ($section != "list") {
	
	/*
	if (HOSTED) $query_styles2 = sprintf("SELECT * FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') UNION ALL SELECT * FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom')", $styles_db_table, $styleSet, $prefix."styles", $styleSet);
	else
	*/
	$query_styles2 = sprintf("SELECT * FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom')", $styles_db_table, $styleSet);
	if (($section == "judge") && ($go == "judge")) $query_styles2 .= " ORDER BY brewStyleType, brewStyleGroup, brewStyleNum ASC";
	elseif ($section == "brew") $query_styles2 .= " AND brewStyleGroup > '28' AND brewStyleReqSpec = '1'";
	else {
		if ($styleSet == "BA") $query_styles2 .= " ORDER BY brewStyleGroup, brewStyleNum ASC";
		else $query_styles2 .= " ORDER BY brewStyleType, brewStyleGroup, brewStyleNum ASC";
	}
	$styles2 = mysqli_query($connection,$query_styles2) or die (mysqli_error($connection));
	$row_styles2 = mysqli_fetch_assoc($styles2);
	$totalRows_styles2 = mysqli_num_rows($styles2);
	
}

?>