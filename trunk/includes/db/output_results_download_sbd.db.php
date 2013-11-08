<?php

if (NHC) {
	// Place NHC SQL calls below
	
	
}
// end if (NHC)

else {

	$query_sbd = sprintf("SELECT * FROM $special_best_data_db_table WHERE sid='%s' ORDER BY sbd_place ASC",$row_sbi['id']);
	$sbd = mysql_query($query_sbd, $brewing) or die(mysql_error());
	$row_sbd = mysql_fetch_assoc($sbd);
	$totalRows_sbd = mysql_num_rows($sbd);
	
}

?>