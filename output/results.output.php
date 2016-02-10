<?php 
require(DB.'winners.db.php');
require(DB.'output_results.db.php');
if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {
	if (NHC) $base_url = "../";
	
	if (($go == "judging_scores") && ($action == "print"))  {
		if ($row_prefs['prefsWinnerMethod'] == "1") include (SECTIONS.'winners_category.sec.php'); 
		elseif ($row_prefs['prefsWinnerMethod'] == "2") include (SECTIONS.'winners_subcategory.sec.php'); 
		else include (SECTIONS.'winners.sec.php');
	} 
	if (($go == "judging_scores_bos") && ($action == "print")) include (SECTIONS.'bos.sec.php');
}
?>
