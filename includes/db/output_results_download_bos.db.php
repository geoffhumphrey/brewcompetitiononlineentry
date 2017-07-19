<?php
$query_style_type = sprintf("SELECT * FROM %s WHERE id='%s'",$prefix."style_types",$type);
$style_type = mysqli_query($connection,$query_style_type) or die (mysqli_error($connection));
$row_style_type = mysqli_fetch_assoc($style_type);

$totalRows_bos = 0;

if ($row_style_type['styleTypeBOS'] == "Y") { 
	$query_bos = sprintf("SELECT a.scorePlace, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerBreweryName, c.brewerClubs FROM %s a, %s b, %s c WHERE a.eid = b.id AND a.scorePlace IS NOT NULL AND c.uid = b.brewBrewerID AND scoreType='%s' ORDER BY a.scorePlace", $prefix."judging_scores_bos", $prefix."brewing", $prefix."brewer", $type);
	$bos = mysqli_query($connection,$query_bos) or die (mysqli_error($connection));
	$row_bos = mysqli_fetch_assoc($bos);
	$totalRows_bos = mysqli_num_rows($bos);
}	
?>