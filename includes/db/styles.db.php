<?php

// --- Until 1.3.1.1 Release, keep this code --- //

if ($version == "1.3.1.0") {
	
	$query_wild = sprintf("SELECT brewStyle FROM %s WHERE brewStyle='Soured Fruit Beer'",$styles_db_table);
	$wild = mysql_query($query_wild, $brewing) or die(mysql_error());
	$row_wild = mysql_fetch_assoc($wild);
	$totalRows_wild = mysql_num_rows($wild);
	
	if ($totalRows_wild > 0) {

		$updateSQL = sprintf("UPDATE %s SET brewStyle='Wild Specialty Beer', brewStyleInfo='A sour and/or funky version of a fruit, herb, or spice beer, or a wild beer aged in wood. If wood-aged, the wood should not be the primary or dominant character. Entry Instructions: Entrant must specify the type of fruit, spice, herb, or wood used. Entrant must specify a description of the beer, identifying the yeast/bacteria used and either a base style or the ingredients/specs/target character of the beer. A general description of the special nature of the beer can cover all the required items. Commercial Examples: Cascade Bourbonic Plague, Jester King Atrial Rubicite, New Belgium Eric’s Ale, New Glarus Belgian Red, Russian River Supplication, The Lost Abbey 
	Cuvee de Tomme.' WHERE id=187",$styles_db_table);
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		
		$updateSQL = sprintf("UPDATE %s SET brewStyle='Wild Specialty Beer' WHERE brewStyle='Soured Fruit Beer'",$brewing_db_table);
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		
	}

}

// ---

if ($section == "step7") {
	$query_prefs_styleset = sprintf("SELECT prefsStyleSet FROM %s WHERE id='1'",$prefix."preferences");
	$prefs_styleset = mysql_query($query_prefs_styleset, $brewing) or die(mysql_error());
	$row_prefs_styleset = mysql_fetch_assoc($prefs_styleset);
	$styleSet = $row_prefs_styleset['prefsStyleSet'];
}

else $styleSet = $_SESSION['prefsStyleSet'];
$query_styles = sprintf("SELECT * FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom')",$styles_db_table,$styleSet);

if (($view != "default") && ($section == "output_styles")) {
		$explodies = explode("-",$view);
		$query_styles .= sprintf(" AND brewStyleGroup='%s' AND brewStyleNum='%s'",$explodies[0],$explodies[1]);
	}

if ((($section == "entry") || ($section == "brew") || ($action == "word") || ($action == "html")) || ((($section == "admin") && ($filter == "judging")) && ($bid != "default"))) $query_styles .= " AND brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum";
elseif (($section == "admin") && ($action == "edit") && ($go != "judging_tables")) $query_styles .= " AND id='$id'";
elseif (($section == "admin") && ($go == "count_by_style")) $query_styles .= " AND brewStyleActive='Y'";
elseif ((($section == "judge") && ($go == "judge")) || ($action == "add") || ($action == "edit")) $query_styles .= " AND brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum";
elseif (($section == "beerxml") && ($msg != "default")) $query_styles .= " AND brewStyleActive='Y' AND brewStyleOwn='bcoe'";
elseif ($section == "sorting") $query_styles .= " AND brewStyleActive='Y'";
elseif ($section == "list") $query_styles .= sprintf(" AND brewStyleGroup = '%s' AND brewStyleNum = '%s'", $row_log['brewCategorySort'], $row_log['brewSubCategory']);
elseif ($section == "output_styles") {
	if ($filter == "default") $query_styles .= " AND brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum";
	else $query_styles .= " AND brewStyleActive='Y' AND brewStyleGroup='$filter' ORDER BY brewStyleGroup,brewStyleNum";
}

else $query_styles .= "";
$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
$row_styles = mysql_fetch_assoc($styles);
$totalRows_styles = mysql_num_rows($styles);

if ($section != "list") {
	$query_styles2 = sprintf("SELECT * FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom')",$styles_db_table,$_SESSION['prefsStyleSet']);
	if (($section == "judge") && ($go == "judge")) $query_styles2 .= " AND brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum";
	elseif ($section == "brew") $query_styles2 .= " AND brewStyleActive='Y' AND brewStyleGroup > '28' AND brewStyleReqSpec = '1'";
	else $query_styles2 .= " AND brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum";
	$styles2 = mysql_query($query_styles2, $brewing) or die(mysql_error());
	$row_styles2 = mysql_fetch_assoc($styles2);
	$totalRows_styles2 = mysql_num_rows($styles2);
}

// echo $query_styles;

?>