<?php
$query_bos = "";
$totalRows_bos = 0;

$brewing_db_table = $prefix."brewing";
$brewer_db_table = $prefix."brewer";
$judging_scores_bos_db_table = $prefix."judging_scores_bos";
$style_types_db_table = $prefix."style_types";

if ($filter != "default") {
	$brewing_db_table = $prefix."brewing_".$filter;
	$brewer_db_table = $prefix."brewer_".$filter;
	$judging_scores_bos_db_table = $prefix."judging_scores_bos_".$filter;
	$style_types_db_table = $prefix."style_types_".$filter;
}

if (!empty($type)) {

	$query_style_type_1 = sprintf("SELECT * FROM %s WHERE id='%s'",$style_types_db_table,$type);
	$style_type_1 = mysqli_query($connection,$query_style_type_1) or die (mysqli_error($connection));
	$row_style_type_1 = mysqli_fetch_assoc($style_type_1);

	if ($row_style_type_1['styleTypeBOS'] == "Y") {
	    
	    if ($type == "4") $query_bos = sprintf("SELECT a.scorePlace, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerCity, c.brewerState, c.brewerCountry, c.brewerBreweryName, c.brewerClubs FROM %s a, %s b, %s c WHERE a.eid = b.id AND a.scorePlace IS NOT NULL AND c.uid = b.brewBrewerID AND (a.scoreType='2' OR a.scoreType='3' OR a.scoreType='4') ORDER BY a.scorePlace", $judging_scores_bos_db_table, $brewing_db_table, $brewer_db_table);

		else $query_bos = sprintf("SELECT a.scorePlace, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerCity, c.brewerState, c.brewerCountry, c.brewerBreweryName, c.brewerClubs FROM %s a, %s b, %s c WHERE a.eid = b.id AND a.scorePlace IS NOT NULL AND c.uid = b.brewBrewerID AND a.scoreType='%s' ORDER BY a.scorePlace", $judging_scores_bos_db_table, $brewing_db_table, $brewer_db_table, $type);
		$bos = mysqli_query($connection,$query_bos) or die (mysqli_error($connection));
		$row_bos = mysqli_fetch_assoc($bos);
		$totalRows_bos = mysqli_num_rows($bos);

	}

}

?>