<?php
$query_style_type_1 = sprintf("SELECT * FROM %s WHERE id='%s'",$prefix."style_types",$type);
$style_type_1 = mysqli_query($connection,$query_style_type_1) or die (mysqli_error($connection));
$row_style_type_1 = mysqli_fetch_assoc($style_type_1);

$totalRows_bos = 0;

if ($row_style_type_1['styleTypeBOS'] == "Y") {
    if ($row_style_type_1['styleTypeBOSMethod'] == 1) $scorePlace = "AND a.scorePlace = '1'";
    elseif ($row_style_type_1['styleTypeBOSMethod'] == 2) $scorePlace = "AND (a.scorePlace = '1' OR a.scorePlace='2')";
    elseif ($row_style_type_1['styleTypeBOSMethod'] == 3) $scorePlace = "AND (a.scorePlace = '1' OR a.scorePlace='2' OR a.scorePlace='3')";
    elseif ($row_style_type_1['styleTypeBOSMethod'] == 4) $scorePlace = "AND (a.scorePlace = '1' OR a.scorePlace='2' OR a.scorePlace='3' OR a.scorePlace='HM')";
    else $scorePlace = "";
	$query_bos = sprintf("SELECT a.scorePlace, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerCity, c.brewerState, c.brewerCountry, c.brewerBreweryName, c.brewerClubs FROM %s a, %s b, %s c WHERE a.eid = b.id %s AND c.uid = b.brewBrewerID AND scoreType='%s' ORDER BY a.scorePlace", $prefix."judging_scores_bos", $prefix."brewing", $prefix."brewer", $scorePlace, $type);
	$bos = mysqli_query($connection,$query_bos) or die (mysqli_error($connection));
	$row_bos = mysqli_fetch_assoc($bos);
	$totalRows_bos = mysqli_num_rows($bos);
}
?>