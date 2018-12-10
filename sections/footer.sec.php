<?php 
/**
 * Module:      footer.sec.php 
 * Description: This module houses the footer displayed on all pages. 
 * 
 */
$footer = "";

if(!empty($_SESSION['contestName'])) $footer .= $_SESSION['contestName']." &ndash; ";

$footer .= "<a href=\"http://www.brewcompetition.com\" target=\"_blank\">BCOE&amp;M</a> ";

if ((isset($_SESSION['prefsProEdition'])) && ($_SESSION['prefsProEdition'] == 0)) {
	if (HOSTED) $footer .= $current_version_display." ".$label_hosted." ".$label_amateur_comp_edition;
	else $footer .= $current_version_display." ".$label_amateur_comp_edition;
	
}

elseif ((isset($_SESSION['prefsProEdition'])) && ($_SESSION['prefsProEdition'] == 1)) {
	if (HOSTED) $footer .= $current_version_display." ".$label_hosted." ".$label_pro_comp_edition;
	else $footer .= $current_version_display." ".$label_pro_comp_edition;
}

else $footer .= $current_version_display;

$footer .= " <span class=\"fa fa-copyright\"></span>2009-".date('Y');

if (((TESTING) || (DEBUG)) && (isset($starttime))) {
	$mtime = microtime(); 
	$mtime = explode(" ",$mtime); 
	$mtime = $mtime[1] + $mtime[0]; 
	$endtime = $mtime; 
	$totaltime = ($endtime - $starttime); 
	$footer .= " &ndash; Page Created: ".number_format($totaltime, 3)."s"; 
}

echo $footer;
?>