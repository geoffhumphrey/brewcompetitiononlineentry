<?php 
ob_start();
require('../paths.php');
require(INCLUDES.'url_variables.inc.php');
$address = rtrim($id,"&amp;KeepThis=true");
$address1 = str_replace(' ', '+', $address);
$driving = "http://maps.google.com/maps?f=q&source=s_q&hl=en&q=".$address1;
header(sprintf("Location: %s", $driving));
?>
