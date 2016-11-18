<?php
if (SINGLE) $query_substyle_count = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewCategorySort='%s' AND brewSubCategory='%s' AND brewPaid='1' AND brewReceived='1' AND comp_id='%s'",$prefix."brewing",$substyle[0],$substyle[1], $_SESSION['comp_id']);
else $query_substyle_count = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewCategorySort='%s' AND brewSubCategory='%s' AND brewPaid='1' AND brewReceived='1'",$prefix."brewing",$substyle[0],$substyle[1]);
$substyle_count = mysqli_query($connection,$query_substyle_count) or die (mysqli_error($connection));
$row_substyle_count = mysqli_fetch_assoc($substyle_count); 

if (SINGLE) $query_substyle_count_logged = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewCategorySort='%s' AND brewSubCategory='%s' AND comp_id='%s'",$prefix."brewing",$substyle[0],$substyle[1], $_SESSION['comp_id']);
else $query_substyle_count_logged = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewCategorySort='%s' AND brewSubCategory='%s'",$prefix."brewing",$substyle[0],$substyle[1]);
$substyle_count_logged = mysqli_query($connection,$query_substyle_count_logged) or die (mysqli_error($connection));
$row_substyle_count_logged = mysqli_fetch_assoc($substyle_count_logged);

$substyle_cat_num = ltrim($substyle[0],"0");

if ($substyle_cat_num <= $beer_end) {
	$count_beer = TRUE;
	$count_mead = FALSE;
	$count_cider = FALSE;
	$other_count = FALSE;
}

if (in_array($substyle_cat_num,$mead_array)) {
	$count_beer = FALSE;
	$count_mead = TRUE;
	$count_cider = FALSE;
	$other_count = FALSE;
}

if (in_array($substyle_cat_num,$cider_array)) {
	$count_beer = FALSE;
	$count_mead = FALSE;
	$count_cider = TRUE;
	$other_count = FALSE;
}

if ($substyle_cat_num > $category_end) {
	
	$query_style_type = sprintf("SELECT brewStyleType FROM %s WHERE brewStyleGroup='%s'",$styles_db_table,$substyle_cat_num);
	$style_type = mysqli_query($connection,$query_style_type) or die (mysqli_error($connection));
	$row_style_type = mysqli_fetch_assoc($style_type);
	
	$count_beer = FALSE;
	$count_mead = FALSE;
	$count_cider = FALSE;
	$other_count = TRUE;
}

if ($count_beer) { 
	$style_type = "Beer"; 
	$style_beer_count[] .= $row_substyle_count['count']; 
	$style_beer_count_logged[] .= $row_substyle_count_logged['count'];  
	}

if ($count_mead) { 
	$style_type = "Mead"; 
	$style_mead_count[] .= $row_substyle_count['count']; 
	$style_mead_count_logged[] .= $row_substyle_count_logged['count']; 
	}

if ($count_cider)  { 
	$style_type = "Cider";
	$style_cider_count[] .= $row_substyle_count['count'];
	$style_cider_count_logged[] .= $row_substyle_count_logged['count']; 
	}

if ($other_count) {
	
	if ($row_style_type['brewStyleType'] <= 3) $source = "bcoe"; 
	else $source = "custom"; 
	
	$style_type = style_type($row_style_type['brewStyleType'],"2",$source);
	
	if ($style_type == "Beer") {
		$style_beer_count[] .= $row_substyle_count['count']; 
		$style_beer_count_logged[] .= $row_substyle_count_logged['count'];  
	}
	
	elseif ($style_type == "Mead") {
		$style_mead_count[] .= $row_substyle_count['count']; 
		$style_mead_count_logged[] .= $row_substyle_count_logged['count']; 
	}
	
	elseif ($style_type == "Cider") {
		$style_cider_count[] .= $row_substyle_count['count'];
		$style_cider_count_logged[] .= $row_substyle_count_logged['count']; 
	}
	
	else {
		$style_other_count[] .= $row_substyle_count['count'];
		$style_other_count_logged[] .= $row_substyle_count_logged['count']; 
	}
	
}
?>