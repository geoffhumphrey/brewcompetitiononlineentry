<?php 
/**
 * Module:      footer.sec.php 
 * Description: This module houses the footer displayed on all pages. 
 * 
 */
$footer = "";
if(!empty($_SESSION['contestName'])) $footer .= $_SESSION['contestName']." &ndash; ";
$footer .= "<a href='http://www.brewcompetition.com' target='_blank'>BCOE&amp;M</a> ";
if (HOSTED) $footer .= $current_version." Hosted Edition";
else $footer .= $current_version;
$footer .= " &copy;2009-".date('Y');
if (TESTING) {
		$mtime = microtime(); 
		$mtime = explode(" ",$mtime); 
		$mtime = $mtime[1] + $mtime[0]; 
		$endtime = $mtime; 
		$totaltime = ($endtime - $starttime); 
		$footer .= "&nbsp;Page created in ".number_format($totaltime, 3)." seconds."; 
	}
echo $footer;
?>