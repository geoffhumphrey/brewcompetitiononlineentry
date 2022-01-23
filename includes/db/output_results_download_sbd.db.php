<?php
if ($filter == "default") {
	$special_best_data_db_table = $prefix."special_best_data";
	$brewing_db_table = $prefix."brewing";
	$brewer_db_table = $prefix."brewer";
	$judging_scores_bos_db_table = $prefix."judging_scores_bos";
	$style_types_db_table = $prefix."style_types";
}

else {
	$special_best_data_db_table = $prefix."special_best_data_".$filter;
	$brewing_db_table = $prefix."brewing_".$filter;
	$brewer_db_table = $prefix."brewer_".$filter;
	$judging_scores_bos_db_table = $prefix."judging_scores_bos_".$filter;
	$style_types_db_table = $prefix."style_types_".$filter;
}

$query_sbd = sprintf("SELECT a.eid, a.bid, a.sbd_place, a.sbd_comments, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerCity, c.brewerState, c.brewerCountry, c.brewerBreweryName, c.brewerClubs FROM %s a, %s b, %s c WHERE a.eid = b.id AND c.uid = b.brewBrewerID AND a.sid='%s' ORDER BY a.sbd_place ASC", $special_best_data_db_table, $brewing_db_table, $brewer_db_table, $row_sbi['id']);

//$query_sbd = sprintf("SELECT * FROM %s WHERE sid='%s' ORDER BY sbd_place ASC",$special_best_data_db_table,$row_sbi['id']);
$sbd = mysqli_query($connection,$query_sbd) or die (mysqli_error($connection));
$row_sbd = mysqli_fetch_assoc($sbd);
$totalRows_sbd = mysqli_num_rows($sbd);
?>