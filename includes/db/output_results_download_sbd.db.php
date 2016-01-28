<?php

if (NHC) {
	// Place NHC SQL calls below
	
	
}
// end if (NHC)

else {
	
	$query_sbd = sprintf("SELECT * FROM %s WHERE sid='%s' ORDER BY sbd_place ASC",$prefix."special_best_data",$row_sbi['id']);
	$sbd = mysql_query($query_sbd, $brewing) or die(mysql_error());
	$row_sbd = mysql_fetch_assoc($sbd);
	$totalRows_sbd = mysql_num_rows($sbd);
	
}

?>