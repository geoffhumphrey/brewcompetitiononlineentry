<?php
$rows_bos = array();
$totalRows_bos = 0;

$brewing_db_table = $prefix."brewing";
$brewer_db_table = $prefix."brewer";
$judging_scores_bos_db_table = $prefix."judging_scores_bos";
$style_types_db_table = $prefix."style_types";

if ($filter != "default") {
	$filter_clean = preg_replace("/[^a-zA-Z0-9]+/", "", $filter);
	$brewing_db_table = $prefix."brewing_".$filter_clean;
	$brewer_db_table = $prefix."brewer_".$filter_clean;
	$judging_scores_bos_db_table = $prefix."judging_scores_bos_".$filter_clean;
	$style_types_db_table = $prefix."style_types_".$filter_clean;
}

if ((!empty($type)) && (is_numeric($type)) && (table_exists($style_types_db_table))) {

	$db_conn->where("id", $type);
	$row_style_type_1 = $db_conn->getOne($style_types_db_table);

	if ((isset($row_style_type_1['styleTypeBOS'])) && ($row_style_type_1['styleTypeBOS'] == "Y") && (table_exists($judging_scores_bos_db_table)) && (table_exists($brewing_db_table)) && (table_exists($brewer_db_table))) {

	    if ($type == "4") {
	    	$sql_bos = sprintf("SELECT a.scorePlace, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerCity, c.brewerState, c.brewerCountry, c.brewerBreweryName, c.brewerClubs FROM %s a, %s b, %s c WHERE a.eid = b.id AND a.scorePlace IS NOT NULL AND c.uid = b.brewBrewerID AND (a.scoreType='2' OR a.scoreType='3' OR a.scoreType='4') ORDER BY a.scorePlace", $judging_scores_bos_db_table, $brewing_db_table, $brewer_db_table);
	    	$rows_bos = $db_conn->rawQuery($sql_bos);
	    }

		else {
			$sql_bos = sprintf("SELECT a.scorePlace, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerCity, c.brewerState, c.brewerCountry, c.brewerBreweryName, c.brewerClubs FROM %s a, %s b, %s c WHERE a.eid = b.id AND a.scorePlace IS NOT NULL AND c.uid = b.brewBrewerID AND a.scoreType=? ORDER BY a.scorePlace", $judging_scores_bos_db_table, $brewing_db_table, $brewer_db_table);
			$rows_bos = $db_conn->rawQuery($sql_bos, array($type));
		}

		$totalRows_bos = $db_conn->count;
		$row_bos = ($rows_bos && count($rows_bos) > 0) ? $rows_bos[0] : null;

	}

}

?>