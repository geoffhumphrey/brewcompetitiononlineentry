<?php

$count_beer = FALSE;
$count_mead = FALSE;
$count_mead_cider = FALSE;
$count_cider = FALSE;
$other_count = FALSE;

$cat_convert = $cat;
$cat_name = style_convert($cat_convert,1);

// Perform query in appropriate db table rows
if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false) {
	if (SINGLE) $query_style_count = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewCategory='%s' AND brewPaid='1' AND brewReceived='1' AND brewConfirmed='1' AND comp_id='%s'", $prefix."brewing", $cat, $_SESSION['comp_id']);
	else $query_style_count = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewCategory='%s' AND brewPaid='1' AND brewReceived='1' AND brewConfirmed='1'", $prefix."brewing", $cat);
}

else {
	if (SINGLE) $query_style_count = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewCategorySort='%s' AND brewPaid='1' AND brewReceived='1' AND brewConfirmed='1' AND comp_id='%s'",$prefix."brewing",$cat, $_SESSION['comp_id']);
	else $query_style_count = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewCategorySort='%s' AND brewPaid='1' AND brewReceived='1' AND brewConfirmed='1'",$prefix."brewing",$cat);
}

$style_count = mysqli_query($connection,$query_style_count) or die (mysqli_error($connection));
$row_style_count = mysqli_fetch_assoc($style_count);

if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false) {
	if (SINGLE) $query_style_count_logged = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewCategory='%s' AND brewConfirmed='1' AND comp_id='%s'",$prefix."brewing",$cat, $_SESSION['comp_id']);
	else $query_style_count_logged = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewCategory='%s' AND brewConfirmed='1'",$prefix."brewing",$cat);
}

else {
	if (SINGLE) $query_style_count_logged = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewCategorySort='%s' AND brewConfirmed='1' AND comp_id='%s'",$prefix."brewing",$cat, $_SESSION['comp_id']);
	else $query_style_count_logged = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewCategorySort='%s' AND brewConfirmed='1'",$prefix."brewing",$cat);
}

$style_count_logged = mysqli_query($connection,$query_style_count_logged) or die (mysqli_error($connection));
$row_style_count_logged = mysqli_fetch_assoc($style_count_logged);

if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false) {

	if (($cat < 12) || (($cat > 12) && ($cat <= $ba_category_end))) {
		$count_beer = TRUE;
		$count_mead = FALSE;
		$count_mead_cider = FALSE;
		$count_cider = FALSE;
		$other_count = FALSE;
	}

	if ($cat == 12) {
		$count_beer = FALSE;
		$count_mead = FALSE;
		$count_mead_cider = TRUE;
		$count_cider = FALSE;
		$other_count = FALSE;
	}

	if ($cat > $ba_category_end) {
		$count_beer = FALSE;
		$count_mead = FALSE;
		$count_mead_cider = FALSE;
		$count_cider = FALSE;
		$other_count = TRUE;
	}
	
}

else {

	// Perform query in appropriate db table rows
	if (SINGLE) $query_style_count = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewCategorySort='%s' AND brewPaid='1' AND brewReceived='1' AND comp_id='%s'",$prefix."brewing",$cat, $_SESSION['comp_id']);
	else $query_style_count = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewCategorySort='%s' AND brewPaid='1' AND brewReceived='1'",$prefix."brewing",$cat);
	$style_count = mysqli_query($connection,$query_style_count) or die (mysqli_error($connection));
	$row_style_count = mysqli_fetch_assoc($style_count);

	if (SINGLE) $query_style_count_logged = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewCategorySort='%s' AND comp_id='%s'",$prefix."brewing",$cat, $_SESSION['comp_id']);
	else $query_style_count_logged = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewCategorySort='%s'",$prefix."brewing",$cat);
	$style_count_logged = mysqli_query($connection,$query_style_count_logged) or die (mysqli_error($connection));
	$row_style_count_logged = mysqli_fetch_assoc($style_count_logged);


	if ($cat > $category_end) {

		if (SINGLE) $query_style_type = sprintf("SELECT brewStyleType FROM %s WHERE brewStyleGroup='%s' AND comp_id='%s'",$styles_db_table,$cat, $_SESSION['comp_id']);
		else $query_style_type = sprintf("SELECT brewStyleType FROM %s WHERE brewStyleGroup='%s'",$styles_db_table,$cat);
		$style_type = mysqli_query($connection,$query_style_type) or die (mysqli_error($connection));
		$row_style_type = mysqli_fetch_assoc($style_type);

	}

	if ($cat <= $beer_end) {
		$count_beer = TRUE;
		$count_mead = FALSE;
		$count_cider = FALSE;
		$other_count = FALSE;
	}
	
	if (in_array($cat,$mead_array)) {
		$count_beer = FALSE;
		$count_mead = TRUE;
		$count_cider = FALSE;
		$other_count = FALSE;
	}

	if (in_array($cat,$cider_array)) {
		$count_beer = FALSE;
		$count_mead = FALSE;
		$count_cider = TRUE;
		$other_count = FALSE;
	}

	if ($cat > $category_end) {
		$count_beer = FALSE;
		$count_mead = FALSE;
		$count_cider = FALSE;
		$other_count = TRUE;
	}
	
}

if ($count_beer) { 
	$style_type = "Beer"; 
	$style_beer_count[] .= $row_style_count['count']; 
	$style_beer_count_logged[] .= $row_style_count_logged['count'];
}


if ($count_mead) { 
	$style_type = "Mead";
	$style_mead_count[] .= $row_style_count['count']; 
	$style_mead_count_logged[] .= $row_style_count_logged['count'];
}

if ($count_cider)  { 
	$style_type = "Cider";
	$style_cider_count[] .= $row_style_count['count'];
	$style_cider_count_logged[] .= $row_style_count_logged['count'];
}

if ($count_mead_cider)  { 
	$style_type = "Mead/Cider";
	$style_mead_cider_count[] .= $row_style_count['count'];
	$style_mead_cider_count_logged[] .= $row_style_count_logged['count']; 
}

if ($other_count) {

	if ($row_style_type['brewStyleType'] <= 3) $source = "bcoe"; 
	else  $source = "custom"; 

	$style_type = style_type($row_style_type['brewStyleType'],"2",$source);

	if ($style_type == "Beer") {
		$style_beer_count[] .= $row_style_count['count']; 
		$style_beer_count_logged[] .= $row_style_count_logged['count'];  
	}

	elseif ($style_type == "Mead") {
		$style_mead_count[] .= $row_style_count['count']; 
		$style_mead_count_logged[] .= $row_style_count_logged['count'];
		$style_mead_cider_count[] .= $row_style_count['count'];
		$style_mead_cider_count_logged[] .= $row_style_count_logged['count']; 
	}

	elseif ($style_type == "Cider") {
		$style_cider_count[] .= $row_style_count['count'];
		$style_cider_count_logged[] .= $row_style_count_logged['count'];
		$style_mead_cider_count[] .= $row_style_count['count'];
		$style_mead_cider_count_logged[] .= $row_style_count_logged['count']; 
	}

	else {
		$style_other_count[] .= $row_style_count['count'];
		$style_other_count_logged[] .= $row_style_count_logged['count']; 
	}

}
?>