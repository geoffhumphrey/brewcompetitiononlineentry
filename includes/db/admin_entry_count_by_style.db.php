<?php
$query_style_count = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewCategorySort='%s' AND brewPaid='1' AND brewReceived='1'",$prefix."brewing",$cat_convert);
$style_count = mysqli_query($connection,$query_style_count) or die (mysqli_error($connection));
$row_style_count = mysqli_fetch_assoc($style_count); 

$query_style_count_logged = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewCategorySort='%s'",$prefix."brewing",$cat_convert);
$style_count_logged = mysqli_query($connection,$query_style_count_logged) or die (mysqli_error($connection));
$row_style_count_logged = mysqli_fetch_assoc($style_count_logged);

if ($cat > $category_end) {

	$query_style_type = sprintf("SELECT brewStyleType FROM %s WHERE brewStyleGroup='%s'",$styles_db_table,$cat_convert);
	$style_type = mysqli_query($connection,$query_style_type) or die (mysqli_error($connection));
	$row_style_type = mysqli_fetch_assoc($style_type);

}
?>