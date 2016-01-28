<?php
if (NHC) {
	// Place NHC SQL calls below
	
	
}
// end if (NHC)

else {
	
	$query_contest_info = sprintf("SELECT contestBottles,contestBOSAward,contestAwards,contestCircuit FROM %s WHERE id=1", $prefix."contest_info");
	$contest_info = mysql_query($query_contest_info, $brewing) or die(mysql_error());
	$row_contest_info = mysql_fetch_assoc($contest_info);

}
?>