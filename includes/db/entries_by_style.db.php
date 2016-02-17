<?php

$cat_convert = $cat;
	$cat_name = style_convert($cat_convert,1);
	
	// Perform query in appropriate db table rows
	$query_style_count = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewCategorySort='%s' AND brewPaid='1' AND brewReceived='1'",$prefix."brewing",$cat);
	$style_count = mysql_query($query_style_count, $brewing) or die(mysql_error());
	$row_style_count = mysql_fetch_assoc($style_count);
	
	$query_style_count_logged = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewCategorySort='%s'",$prefix."brewing",$cat);
	$style_count_logged = mysql_query($query_style_count_logged, $brewing) or die(mysql_error());
	$row_style_count_logged = mysql_fetch_assoc($style_count_logged);
	
	
	if ($cat > $category_end) {
	
		$query_style_type = sprintf("SELECT brewStyleType FROM %s WHERE brewStyleGroup='%s'",$styles_db_table,$cat);
		$style_type = mysql_query($query_style_type, $brewing) or die(mysql_error());
		$row_style_type = mysql_fetch_assoc($style_type);
	
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
	
	if ($count_beer) { 
		$style_type = "Beer"; 
		$style_beer_count[] .= $row_style_count['count']; 
		$style_beer_count_logged[] .= $row_style_count_logged['count'];
		$style_mead_count[] .= 0; 
		$style_mead_count_logged[] .= 0;
		$style_cider_count[] .= 0;
		$style_cider_count_logged[] .= 0; 
		}
		
		
	if ($count_mead) { 
		$style_type = "Mead";
		$style_beer_count[] .= 0; 
		$style_beer_count_logged[] .= 0;
		$style_mead_count[] .= $row_style_count['count']; 
		$style_mead_count_logged[] .= $row_style_count_logged['count'];
		$style_cider_count[] .= 0;
		$style_cider_count_logged[] .= 0; 
	}
		
	if ($count_cider)  { 
		$style_type = "Cider";
		$style_beer_count[] .= 0; 
		$style_beer_count_logged[] .= 0;
		$style_mead_count[] .= 0; 
		$style_mead_count_logged[] .= 0;
		$style_cider_count[] .= $row_style_count['count'];
		$style_cider_count_logged[] .= $row_style_count_logged['count']; 
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
		}
		
		elseif ($style_type == "Cider") {
			$style_cider_count[] .= $row_style_count['count'];
			$style_cider_count_logged[] .= $row_style_count_logged['count']; 
		}
		
		else {
			$style_other_count[] .= $row_style_count['count'];
			$style_other_count_logged[] .= $row_style_count_logged['count']; 
		}
		
		//$style_type_array[] = $style_type;
	}

?>