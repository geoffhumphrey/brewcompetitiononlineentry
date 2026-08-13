<?php
if ($filter == "default") {
	$special_best_data_db_table = $prefix."special_best_data";
	$brewing_db_table = $prefix."brewing";
	$brewer_db_table = $prefix."brewer";
	$judging_scores_bos_db_table = $prefix."judging_scores_bos";
	$style_types_db_table = $prefix."style_types";
}

else {
	$filter_clean = preg_replace("/[^a-zA-Z0-9]+/", "", $filter);
	$special_best_data_db_table = $prefix."special_best_data_".$filter_clean;
	$brewing_db_table = $prefix."brewing_".$filter_clean;
	$brewer_db_table = $prefix."brewer_".$filter_clean;
	$judging_scores_bos_db_table = $prefix."judging_scores_bos_".$filter_clean;
	$style_types_db_table = $prefix."style_types_".$filter_clean;
}

$sql_sbd = sprintf("SELECT a.eid, a.bid, a.sbd_place, a.sbd_comments, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerCity, c.brewerState, c.brewerCountry, c.brewerBreweryName, c.brewerClubs FROM %s a, %s b, %s c WHERE a.eid = b.id AND c.uid = b.brewBrewerID AND a.sid=? ORDER BY a.sbd_place ASC", $special_best_data_db_table, $brewing_db_table, $brewer_db_table);

$rows_sbd = $db_conn->rawQuery($sql_sbd, array($row_sbi['id']));
$totalRows_sbd = $db_conn->count;
$row_sbd = ($rows_sbd && count($rows_sbd) > 0) ? $rows_sbd[0] : null;
?>