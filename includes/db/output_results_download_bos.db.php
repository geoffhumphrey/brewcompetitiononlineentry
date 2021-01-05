<?php
$query_bos = "";
$totalRows_bos = 0;

$query_style_type_1 = sprintf("SELECT * FROM %s WHERE id='%s'",$prefix."style_types",$type);
$style_type_1 = mysqli_query($connection,$query_style_type_1) or die (mysqli_error($connection));
$row_style_type_1 = mysqli_fetch_assoc($style_type_1);

if ($row_style_type_1['styleTypeBOS'] == "Y") {
    
    if ($type == "4") $query_bos = sprintf("SELECT a.scorePlace, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerCity, c.brewerState, c.brewerCountry, c.brewerBreweryName, c.brewerClubs FROM %s a, %s b, %s c WHERE a.eid = b.id AND a.scorePlace IS NOT NULL AND c.uid = b.brewBrewerID AND (a.scoreType='2' OR a.scoreType='3' OR a.scoreType='4') ORDER BY a.scorePlace", $prefix."judging_scores_bos", $prefix."brewing", $prefix."brewer");

	else $query_bos = sprintf("SELECT a.scorePlace, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerCity, c.brewerState, c.brewerCountry, c.brewerBreweryName, c.brewerClubs FROM %s a, %s b, %s c WHERE a.eid = b.id AND a.scorePlace IS NOT NULL AND c.uid = b.brewBrewerID AND a.scoreType='%s' ORDER BY a.scorePlace", $prefix."judging_scores_bos", $prefix."brewing", $prefix."brewer", $type);
	$bos = mysqli_query($connection,$query_bos) or die (mysqli_error($connection));
	$row_bos = mysqli_fetch_assoc($bos);
	$totalRows_bos = mysqli_num_rows($bos);

}
?>