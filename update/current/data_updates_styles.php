<?php
// -----------------------------------------------------------
// Data Updates: Styles Tables
//   Convert style_info row in the styles_bjcp_2008 table to be 
//   relational to the style_types table.
//   Convert style_active row in the styles_bjcp_2008 table to be 
//   a boolean value (0 = false; 1 = true).
// -----------------------------------------------------------
$query_styles = "SELECT * FROM styles_bjcp_2008";
$styles = mysql_query($query_styles, $brewing);
$row_styles = mysql_fetch_assoc($styles);
do { 
	if ($row_styles['style_type'] == "Cider") $style_type = "2";
	elseif ($row_styles['style_type'] == "Mead") $style_type = "3";
	else $style_type = "1";
	
	if ($row_styles['style_active'] == "N") $style_active = "0"; 
	else $style_active = "1";
	
	$updateSQL = sprintf("UPDATE styles_bjcp_2008 SET style_type='%s', style_active='%s' WHERE id='%s';", $style_type, $style_active, $row_styles['id']);
	
	mysql_select_db($database, $brewing);
	$result = mysql_query($updateSQL, $brewing);
	
	//$output .= $updateSQL."<br>";
	
	// Move all custom styles from the old styles table to the 
	// 'styles_custom' table.
	if ($row_styles['style_cat'] > 28) {
		$updateSQL = sprintf("INSERT INTO styles_custom (
									style_name, 
									style_cat, 
									style_subcat, 
									style_og_min, 
									style_og_max, 
									style_fg_min, 
									style_fg_max , 
									style_abv_min, 
									style_abv_max, 
									style_ibu_min, 
									style_ibu_max, 
									style_srm_min, 
									style_srm_max, 
									style_type, 
									style_info, 
									style_active, 
									style_location,
									style_spec_ingred
									 ) 
									VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",
									$row_styles['style_name'],
									$row_styles['style_cat'],
									$row_styles['style_subcat'],
									$row_styles['style_og_min'],
									$row_styles['style_og_max'],
									$row_styles['style_fg_min'],
									$row_styles['style_fg_max'],
									$row_styles['style_abv_min'],
									$row_styles['style_abv_max'],
									$row_styles['style_ibu_min'],
									$row_styles['style_ibu_max'],
									$row_styles['style_srm_min'],
									$row_styles['style_srm_max'],
									$row_styles['style_type'],
									$row_styles['style_info'],
									'1',
									$row_styles['style_location'],
									'1'
									);
		//$output .= $updateSQL."<br>";
		
		mysql_select_db($database, $brewing);
		$result = mysql_query($updateSQL, $brewing);
		
		$output .= "<li>Custom style ".$row_styles['style_name']." moved to styles_custom table successfully.</li>";
		
		$updateSQL = sprintf("DELETE FROM styles_bjcp_2008 WHERE id='%s'",$row_styles['id']);
		//$output .= $updateSQL."<br>";
		mysql_select_db($database, $brewing);
		$result = mysql_query($updateSQL, $brewing);
	}
	
	$haystack = array(21,59,65,74,75,76,78,79,80,86,87,89,94,96,97,98);
	if (in_array($row_styles['id'],$haystack)) $spec_ingred = "1"; else $spec_ingred = "0"; 
	$updateSQL = sprintf("UPDATE styles_bjcp_2008 SET style_spec_ingred='%s' WHERE id='%s';",$spec_ingred,$row_styles['id']);
	//$output .= $updateSQL."<br>";
	mysql_select_db($database, $brewing);
	$result = mysql_query($updateSQL, $brewing);
		
	
} while ($row_styles = mysql_fetch_assoc($styles));
// Change data type of both rows to tinyint(1)
$updateSQL = "ALTER TABLE  styles_bjcp_2008 CHANGE  style_type  style_type TINYINT(1) NULL DEFAULT NULL COMMENT 'relational to the style_types table', CHANGE  style_active  style_active TINYINT(1) NULL DEFAULT NULL COMMENT '1 for true; 0 for false';";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing);
$output .= "<li>Conversion of styles and associated tables completed.</li>";
?>