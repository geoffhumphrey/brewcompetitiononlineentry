<?php

	$query_style_count = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewCategorySort='%s' AND brewPaid='1' AND brewReceived='1'",$prefix."brewing",$cat_convert);
	$style_count = mysql_query($query_style_count, $brewing) or die(mysql_error());
	$row_style_count = mysql_fetch_assoc($style_count); 
	
	$query_style_count_logged = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewCategorySort='%s'",$prefix."brewing",$cat_convert);
	$style_count_logged = mysql_query($query_style_count_logged, $brewing) or die(mysql_error());
	$row_style_count_logged = mysql_fetch_assoc($style_count_logged);

if ($cat > 28) {

	$query_style_type = sprintf("SELECT brewStyleType FROM %s WHERE brewStyleGroup='%s'",$prefix."styles",$cat_convert);
	$style_type = mysql_query($query_style_type, $brewing) or die(mysql_error());
	$row_style_type = mysql_fetch_assoc($style_type);

}

?>