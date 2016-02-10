<?php

if (NHC) {
	// Place NHC SQL calls below
	
	
}
// end if (NHC)

else {

	$query_style_type = sprintf("SELECT * FROM %s WHERE id='%s'",$prefix."style_types",$type);
	$style_type = mysql_query($query_style_type, $brewing) or die(mysql_error());
	$row_style_type = mysql_fetch_assoc($style_type);

	$totalRows_bos = 0;
	
	if ($row_style_type['styleTypeBOS'] == "Y") { 
		$query_bos = sprintf("SELECT a.scorePlace, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerClubs FROM %s a, %s b, %s c WHERE a.eid = b.id AND a.scorePlace IS NOT NULL AND c.uid = b.brewBrewerID AND scoreType='%s' ORDER BY a.scorePlace", $prefix."judging_scores_bos", $prefix."brewing", $prefix."brewer", $type);
		$bos = mysql_query($query_bos, $brewing) or die(mysql_error());
		$row_bos = mysql_fetch_assoc($bos);
		$totalRows_bos = mysql_num_rows($bos);
	}
	
	

}
	
?>