<?php 
/**
 * Module:      footer.sec.php 
 * Description: This module houses the footer displayed on all pages. 
 * 
 */
 
$footer = "";
if (NHC) $footer .= "<a href='http://www.brewcompetition.com' target='_blank'>BCOE&amp;M</a> - NHC Edition*  &copy;".date('Y');
else $footer .= "<a href='http://www.brewcompetition.com' target='_blank'>BCOE&amp;M</a> ".$version." &copy;2009-".date('Y')." by <a href='http://www.zkdigital.com' target='_blank'>zkdigital.com</a>.";
if (TESTING) {
		$mtime = microtime(); 
		$mtime = explode(" ",$mtime); 
		$mtime = $mtime[1] + $mtime[0]; 
		$endtime = $mtime; 
		$totaltime = ($endtime - $starttime); 
		$footer .= "&nbsp;This page was created in ".number_format($totaltime, 3)." seconds."; 
	}

echo $footer;
?>