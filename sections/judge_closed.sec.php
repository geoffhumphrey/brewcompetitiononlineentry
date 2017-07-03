<?php 
/**
 * Module:      judge_closed.sec.php 
 * Description: This module houses the information that will be displayded
 *              once judging dates have passed. 
 * 
 */
 
/* ---------------- PUBLIC Pages Rebuild Info ---------------------

Beginning with the 1.3.0 release, an effort was begun to separate the programming
layer from the presentation layer for all scripts with this header.

All Public pages have certain variables in common that build the page:

	$warningX = any warnings
  
	$primary_page_info = any information related to the page
	
	$header1_X = an <h2> header on the page
	$header2_X = an <h3> subheader on the page
	
	$page_infoX = the bulk of the information on the page.
	$help_page_link = link to the appropriate page on help.brewcompetition.com
	$print_page_link = the "Print This Page" link
	$competition_logo = display of the competition's logo
	
	$labelX = the various labels in a table or on a form
	$messageX = various messages to display
	
	$print_page_link = "<p><span class='icon'><img src='".$base_url."images/printer.png' border='0' alt='Print' title='Print' /></span><a id='modal_window_link' class='data' href='".$base_url."output/print.php?section=".$section."&amp;action=print' title='Print'>Print This Page</a></p>";
	$competition_logo = "<img src='".$base_url."user_images/".$_SESSION['contestLogo']."' width='".$_SESSION['prefsCompLogoSize']."' style='float:right; padding: 5px 0 5px 5px' alt='Competition Logo' title='Competition Logo' />";
	
Declare all variables empty at the top of the script. Add on later...
	$warning1 = "";
	$primary_page_info = "";
	$header1_1 = "";
	$page_info1 = "";
	$header1_2 = "";
	$page_info2 = "";
	
	etc., etc., etc.

 * ---------------- END Rebuild Info --------------------- */

$header_jc_1 = "";
$page_info_jc_1 = "";

$header_jc_1 .= sprintf("<p class=\"lead\">%s %s.</p>",$judge_closed_000, $_SESSION['contestName']);
if ($_SESSION['prefsProEdition'] == 1) $page_info_jc_1 .= sprintf("<p class=\"lead\"><small>%s <strong class=\"text-success\">%s</strong> %s</small></p>",$judge_closed_001,get_participant_count('default'),$judge_closed_003);

else $page_info_jc_1 .= sprintf("<p class=\"lead\"><small>%s <strong class=\"text-success\">%s</strong> %s <strong class=\"text-success\">%s</strong> %s</small></p>",$judge_closed_001,get_entry_count('received'),$judge_closed_002,get_participant_count('default'),$judge_closed_003);

echo $header_jc_1;
echo $page_info_jc_1;
?>
