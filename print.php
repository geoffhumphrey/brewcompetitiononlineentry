<?php 
require ('Connections/config.php');
require ('includes/authentication_nav.inc.php');  session_start(); 
require ('includes/url_variables.inc.php');
require ('includes/db_connect.inc.php');
include ('includes/plug-ins.inc.php');
include ('includes/headers.inc.php');
$today = date('Y-m-d');
$deadline = $row_contest_info['contestRegistrationDeadline'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $row_contest_info['contestName']; ?> organized by <?php echo $row_contest_info['contestHost']; ?></title>
<link href="css/print.css" rel="stylesheet" type="text/css" />
</head>
<body onload="javascript:window.print()">
<div id="content">
	<div id="content-inner">
    <?php if ($section != "admin") { ?>
    <div id="header">	
		<div id="header-inner"><h1><?php echo $header_output; ?></h1></div>
	</div>
    <?php 
	} 
	// Check if registration date has passed. If so, display "registration end" message.
	if (greaterDate($today,$deadline)) {
	if ($section != "admin") { ?><div id="closed">Registration has closed. Thanks to all the brewers who registered and participated in our competition.</div><?php }  
	if ($section == "default") 	include ('sections/default.sec.php');
	if ($section == "rules") 	include ('sections/rules.sec.php');
	if ($section == "entry") 	include ('sections/entry_info.sec.php');
	if ($section == "sponsors") include ('sections/sponsors.sec.php');
	if ($section == "past_winners") include ('sections/past_winners.sec.php');
	if (isset($_SESSION['loginUsername'])) {
		if ($section == "admin")	include ('admin/default.admin.php');
		if ($section == "list") 	include ('sections/list.sec.php');
		}
	} else {
	if ($section == "rules") 	include ('sections/rules.sec.php');
	if ($section == "entry") 	include ('sections/entry_info.sec.php');
	if ($section == "default") 	include ('sections/default.sec.php');
	if ($section == "sponsors") include ('sections/sponsors.sec.php');
	if ($section == "past_winners") include ('sections/past_winners.sec.php');
	if (isset($_SESSION['loginUsername'])) {
		if ($section == "admin")	include ('admin/default.admin.php');
		if ($section == "brew") 	include ('sections/brew.sec.php');
		if ($section == "pay") 		include ('sections/pay.sec.php');
		if ($section == "list") 	include ('sections/list.sec.php');
	if ((!isset($_SESSION['loginUsername'])) && (($section == "admin") || ($section == "brewer") || ($section == "brew") || ($section == "user") || ($section == "judge") || ($section == "list") || ($section == "pay") || ($section == "beerXML")))  
	echo "<div id=\"header\"><div id=\"header-inner\"><h1>Restricted Area</h1></div></div>
	<div class=\"error\">Please register or log in to access this area.</div>";
	}
	} // End registration date check.
	?>    
    </div>
</div>
</body>
</html>
