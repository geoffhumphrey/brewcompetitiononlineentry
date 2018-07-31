<?php
if (SINGLE) $query_style_count = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE comp_id='%s' AND brewCategorySort='%s' AND brewPaid='1' AND brewReceived='1'",$prefix."brewing", $_SESSION['comp_id'], $cat_convert);
	else $query_style_count = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewCategorySort='%s' AND brewPaid='1' AND brewReceived='1'", $prefix."brewing", $cat_convert);

$style_count = mysqli_query($connection,$query_style_count) or die (mysqli_error($connection));
$row_style_count = mysqli_fetch_assoc($style_count);

	if (SINGLE) $query_style_count_logged = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE comp_id='%s' AND brewCategorySort='%s'", $prefix."brewing", $_SESSION['comp_id'], $cat_convert);
	else $query_style_count_logged = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewCategorySort='%s'", $prefix."brewing", $cat_convert);

$style_count_logged = mysqli_query($connection,$query_style_count_logged) or die (mysqli_error($connection));
$row_style_count_logged = mysqli_fetch_assoc($style_count_logged);

if ($_SESSION['prefsStyleSet'] != "BA") {

	if ($cat > $category_end) {

		$query_style_type = sprintf("SELECT brewStyleType FROM %s WHERE comp_id='0' OR comp_id='%s' AND brewStyleGroup='%s'", $styles_db_table, $_SESSION['comp_id'], $cat_convert);
		$style_type = mysqli_query($connection,$query_style_type) or die (mysqli_error($connection));
		$row_style_type = mysqli_fetch_assoc($style_type);

	}

}
?>