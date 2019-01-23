<?php

if ((($section == "admin") && ($go == "preferences")) || ($section == "step3")) {

	// Get all three sets of styles
	$query_styles_all = sprintf("SELECT id,brewStyleGroup,brewStyleNum,brewStyle,brewStyleVersion,brewStyleOwn FROM %s ORDER BY brewStyleVersion,brewStyleGroup,brewStyleNum,brewStyle ASC ",$styles_db_table);
	$styles_all = mysqli_query($connection,$query_styles_all) or die (mysqli_error($connection));
	$row_styles_all = mysqli_fetch_assoc($styles_all);

	$ba_styles_arr[] = array();
	$bjcp_2008_styles_arr[] = array();
	$bjcp_2015_styles_arr[] = array();
	$custom_styles_arr[] = array();

	do {

		if (($row_styles_all['brewStyleVersion'] == "BA") && ($row_styles_all['brewStyleOwn'] == "bcoe")) {
			$ba_styles_arr[] = array(
				"id" => $row_styles_all['id'],
				"brewStyleGroup" => $row_styles_all['brewStyleGroup'],
				"brewStyleNum" => $row_styles_all['brewStyleNum'],
				"brewStyle" => $row_styles_all['brewStyle']
			);
		}

		if (($row_styles_all['brewStyleVersion'] == "BJCP2008") && ($row_styles_all['brewStyleOwn'] == "bcoe")) {
			$bjcp_2008_styles_arr[] = array(
				"id" => $row_styles_all['id'],
				"brewStyleGroup" => $row_styles_all['brewStyleGroup'],
				"brewStyleNum" => $row_styles_all['brewStyleNum'],
				"brewStyle" => $row_styles_all['brewStyle']
			);
		}

		if (($row_styles_all['brewStyleVersion'] == "BJCP2015") && ($row_styles_all['brewStyleOwn'] == "bcoe")) {
			$bjcp_2015_styles_arr[] = array(
				"id" => $row_styles_all['id'],
				"brewStyleGroup" => $row_styles_all['brewStyleGroup'],
				"brewStyleNum" => $row_styles_all['brewStyleNum'],
				"brewStyle" => $row_styles_all['brewStyle']
			);
		}

		if ($row_styles_all['brewStyleOwn'] == "custom") {
			$custom_styles_arr[] = array(
				"id" => $row_styles_all['id'],
				"brewStyleGroup" => $row_styles_all['brewStyleGroup'],
				"brewStyleNum" => $row_styles_all['brewStyleNum'],
				"brewStyle" => $row_styles_all['brewStyle']
			);
		}

	} while ($row_styles_all = mysqli_fetch_assoc($styles_all));

}

if ($section == "step7") {
	$query_prefs_styleset = sprintf("SELECT prefsStyleSet FROM %s WHERE id='1'",$prefix."preferences");
	$prefs_styleset = mysqli_query($connection,$query_prefs_styleset) or die (mysqli_error($connection));
	$row_prefs_styleset = mysqli_fetch_assoc($prefs_styleset);
	$styleSet = $row_prefs_styleset['prefsStyleSet'];
	$_SESSION['prefsStyleSet'] = $row_prefs_styleset['prefsStyleSet'];
}

elseif (isset($_SESSION['prefsStyleSet'])) $styleSet = $_SESSION['prefsStyleSet'];
else $styleSet = "BJCP2015";

$query_styles = sprintf("SELECT * FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom')",$styles_db_table,$styleSet);
if (($view != "default") && ($section == "styles")) {
	$explodies = explode("-",$view);
	$query_styles .= sprintf(" AND brewStyleGroup='%s' AND brewStyleNum='%s'",$explodies[0],$explodies[1]);
}
if ((($section == "entry") || ($section == "brew") || ($action == "word") || ($action == "html")) || ((($section == "admin") && ($filter == "judging")) && ($bid != "default"))) $query_styles .= " AND brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum ASC";
elseif (($section == "admin") && ($go == "preferences")) $query_styles .= "  ORDER BY brewStyleOwn,brewStyleVersion,brewStyleGroup,brewStyleNum,brewStyle ASC";
elseif (($section == "admin") && ($action == "edit") && ($go != "judging_tables")) $query_styles .= " AND id='$id'";
elseif (($section == "admin") && ($go == "count_by_style")) $query_styles .= " AND brewStyleActive='Y'";
elseif (($section == "admin") && ($go == "styles")) $query_styles .= " ORDER BY brewStyleGroup,brewStyleNum ASC";
elseif ((($section == "judge") && ($go == "judge")) || ($go == "judging_tables") || ($action == "add") || ($action == "edit")) {
	if ($_SESSION['prefsStyleSet'] == "BA") $query_styles .= " AND brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyle ASC";
	else $query_styles .= " AND brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum ASC";
}
elseif (($section == "beerxml") && ($msg != "default")) $query_styles .= " AND brewStyleActive='Y' AND brewStyleOwn='bcoe'";
elseif ($section == "sorting") $query_styles .= " AND brewStyleActive='Y'";
elseif ($section == "list") $query_styles .= sprintf(" AND brewStyleGroup = '%s' AND brewStyleNum = '%s'", $row_log['brewCategorySort'], $row_log['brewSubCategory']);
elseif ($section == "styles") {
	if ($filter == "default") $query_styles .= " AND brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum ASC";
	else $query_styles .= " AND brewStyleActive='Y' AND brewStyleGroup='$filter' ORDER BY brewStyleGroup,brewStyleNum ASC";
}
$styles = mysqli_query($connection,$query_styles) or die (mysqli_error($connection));
$row_styles = mysqli_fetch_assoc($styles);
$totalRows_styles = mysqli_num_rows($styles);

if ($section != "list") {
	$query_styles2 = sprintf("SELECT * FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom')",$styles_db_table,$styleSet);
	if (($section == "judge") && ($go == "judge")) $query_styles2 .= " AND brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum ASC";
	elseif ($section == "brew") $query_styles2 .= " AND brewStyleActive='Y' AND brewStyleGroup > '28' AND brewStyleReqSpec = '1'";
	else {
		if ($styleSet == "BA") $query_styles2 .= " AND brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyle ASC";
		else $query_styles2 .= " AND brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum ASC";
	}
	$styles2 = mysqli_query($connection,$query_styles2) or die (mysqli_error($connection));
	$row_styles2 = mysqli_fetch_assoc($styles2);
	$totalRows_styles2 = mysqli_num_rows($styles2);
}
?>