<?php 
/**
 * Module:      footer.sec.php 
 * Description: This module houses the footer displayed on all pages. 
 * 
 */
$footer = "";
if(!empty($_SESSION['contestName'])) $footer .= $_SESSION['contestName']." &ndash; ";
$footer .= "<a href=\"http://www.brewcompetition.com\" target=\"_blank\">BCOE&amp;M</a> ";
if ((HOSTED) && ($_SESSION['prefsProEdition'] == 0)) $footer .= $current_version_display." ".$label_hosted." ".$label_edition;
elseif ((HOSTED) && ($_SESSION['prefsProEdition'] == 1)) $footer .= $current_version_display." ".$label_hosted." ".$label_pro." ".$label_edition;
elseif ((!HOSTED) && ($_SESSION['prefsProEdition'] == 1)) $footer .= $current_version_display." ".$label_pro." ".$label_edition;
else $footer .= $current_version_display;
$footer .= " <span class=\"fa fa-copyright\"></span>2009-".date('Y');
if ((TESTING) || (DEBUG)) {
		$mtime = microtime(); 
		$mtime = explode(" ",$mtime); 
		$mtime = $mtime[1] + $mtime[0]; 
		$endtime = $mtime; 
		$totaltime = ($endtime - $starttime); 
		$footer .= " &ndash; This page was created in ".number_format($totaltime, 3)." seconds."; 
	}
echo $footer;
?>