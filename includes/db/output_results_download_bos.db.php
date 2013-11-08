<?php

if (NHC) {
	// Place NHC SQL calls below
	
	
}
// end if (NHC)

else {

	$query_style_type = "SELECT * FROM $style_types_db_table WHERE id='$type'";
	$style_type = mysql_query($query_style_type, $brewing) or die(mysql_error());
	$row_style_type = mysql_fetch_assoc($style_type);

	if ($row_style_type['styleTypeBOS'] == "Y") { 
		$query_bos = sprintf("SELECT a.scorePlace, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerClubs FROM %s a, %s b, %s c WHERE a.eid = b.id AND a.scorePlace IS NOT NULL AND c.uid = b.brewBrewerID AND scoreType='%s' ORDER BY a.scorePlace", $prefix."judging_scores_bos", $prefix."brewing", $prefix."brewer", $type);
		$bos = mysql_query($query_bos, $brewing) or die(mysql_error());
		$row_bos = mysql_fetch_assoc($bos);
		$totalRows_bos = mysql_num_rows($bos);
	}
	
	else $totalRows_bos = 0;

}
	
?>