<?php

if (NHC) {

	// Place NHC SQL calls below
	
	
}
// end if (NHC)

else {
	if ($section == "step7") {
		$query_prefs_styleset = sprintf("SELECT prefsStyleSet FROM %s WHERE id='1'",$prefix."preferences");
		$prefs_styleset = mysql_query($query_prefs_styleset, $brewing) or die(mysql_error());
		$row_prefs_styleset = mysql_fetch_assoc($prefs_styleset);
		$styleSet = $row_prefs_styleset['prefsStyleSet'];
	}
	
	else $styleSet = $_SESSION['prefsStyleSet'];
	$query_styles = sprintf("SELECT * FROM $styles_db_table WHERE brewStyleVersion='%s'",$styleSet);
	if ((($section == "entry") || ($section == "brew") || ($action == "word") || ($action == "html")) || ((($section == "admin") && ($filter == "judging")) && ($bid != "default"))) $query_styles .= " AND brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum";
	elseif (($section == "admin") && ($action == "edit") && ($go != "judging_tables")) $query_styles .= " AND id='$id'";
	elseif (($section == "admin") && ($go == "count_by_style")) $query_styles .= " AND brewStyleActive='Y'";
	elseif ((($section == "judge") && ($go == "judge")) || ($action == "add") || ($action == "edit")) $query_styles .= " AND brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum";
	elseif (($section == "beerxml") && ($msg != "default")) $query_styles .= " AND brewStyleActive='Y' AND brewStyleOwn='bcoe'";
	elseif ($section == "sorting") $query_styles .= " AND brewStyleActive='Y'";
	elseif ($section == "list") $query_styles .= sprintf(" AND brewStyleGroup = '%s' AND brewStyleNum = '%s'", $row_log['brewCategorySort'], $row_log['brewSubCategory']);
	elseif ($section == "output_styles") {
		if ($filter == "default") {
			if ($go == "default") $query_styles .= " AND brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum";
			if ($go != "default") {
				$explodies = explode("-",$go);
				$query_styles .= sprintf(" AND brewStyleGroup='%s' AND brewStyleNum='%s'",$explodies[0],$explodies[1]);
			}
			
		}
		else $query_styles .= " AND brewStyleActive='Y' AND brewStyleGroup='$filter' ORDER BY brewStyleGroup,brewStyleNum";
		
		//echo $query_styles;
		
		
		$query_styles_count = sprintf("SELECT brewStyleGroup FROM $styles_db_table WHERE brewStyleVersion='%s' ORDER BY brewStyleGroup DESC LIMIT 1",$_SESSION['prefsStyleSet']);
		$styles_count = mysql_query($query_styles_count, $brewing) or die(mysql_error());
		$row_styles_count = mysql_fetch_assoc($styles_count);
	}
	
	//else $query_styles .= "";
	$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
	$row_styles = mysql_fetch_assoc($styles);
	$totalRows_styles = mysql_num_rows($styles);
	
	if ($section != "list") {
		$query_styles2 = sprintf("SELECT * FROM $styles_db_table WHERE brewStyleVersion='%s'",$styleSet);
		if (($section == "judge") && ($go == "judge")) $query_styles2 .= " AND brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum";
		elseif ($section == "brew") $query_styles2 .= " AND brewStyleActive='Y' AND brewStyleGroup > '28' AND brewStyleReqSpec = '1'";
		else $query_styles2 .= " AND brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum";
		$styles2 = mysql_query($query_styles2, $brewing) or die(mysql_error());
		$row_styles2 = mysql_fetch_assoc($styles2);
		$totalRows_styles2 = mysql_num_rows($styles2);
	}
	
	//echo $query_styles;

}
?>