<?php
if (NHC) {
	// Place NHC SQL calls below
	
	
}
// end if (NHC)

else {
	
	$query_styles = "SELECT * FROM $styles_db_table";
	if ((($section == "entry") || ($section == "brew") || ($action == "word") || ($action == "html")) || ((($section == "admin") && ($filter == "judging")) && ($bid != "default"))) $query_styles .= " WHERE brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum";
	elseif (($section == "admin") && ($action == "edit") && ($go != "judging_tables")) $query_styles .= " WHERE id='$id'";
	elseif (($section == "admin") && ($go == "count_by_style")) $query_styles .= " WHERE brewStyleActive='Y'";
	elseif ((($section == "judge") && ($go == "judge")) || ($action == "add") || ($action == "edit")) $query_styles .= " WHERE brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum";
	elseif (($section == "beerxml") && ($msg != "default")) $query_styles .= " WHERE brewStyleActive='Y' AND brewStyleOwn='bcoe'";
	elseif ($section == "sorting") $query_styles .= " WHERE brewStyleActive='Y'";
	elseif ($section == "list") $query_styles .= sprintf(" WHERE brewStyleGroup = '%s' AND brewStyleNum = '%s'", $row_log['brewCategorySort'], $row_log['brewSubCategory']);
	elseif ($section == "output_styles") {
		if ($filter == "default") $query_styles .= " WHERE brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum";
		else $query_styles .= " WHERE brewStyleActive='Y' AND brewStyleGroup='$filter' ORDER BY brewStyleGroup,brewStyleNum";
		
		$query_styles_count = "SELECT brewStyleGroup FROM $styles_db_table ORDER BY brewStyleGroup DESC LIMIT 1";
		$styles_count = mysql_query($query_styles_count, $brewing) or die(mysql_error());
		$row_styles_count = mysql_fetch_assoc($styles_count);
	}
	
	else $query_styles .= "";
	$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
	$row_styles = mysql_fetch_assoc($styles);
	$totalRows_styles = mysql_num_rows($styles);
	
	if ($section != "list") {
		$query_styles2 = "SELECT * FROM $styles_db_table";
		if (($section == "judge") && ($go == "judge")) $query_styles2 .= " WHERE brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum";
		elseif ($section == "brew") $query_styles2 .= " WHERE brewStyleActive='Y' AND brewStyleGroup > '28' AND brewStyleReqSpec = '1'";
		else $query_styles2 .= " WHERE brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum";
		$styles2 = mysql_query($query_styles2, $brewing) or die(mysql_error());
		$row_styles2 = mysql_fetch_assoc($styles2);
		$totalRows_styles2 = mysql_num_rows($styles2);
	}

}
?>