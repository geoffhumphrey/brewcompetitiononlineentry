<?php 
require ('Connections/config.php');

// Check to see if initial setup has taken place 
mysql_select_db($database, $brewing);
$query_setup = "SELECT * FROM contest_info";
$setup = mysql_query($query_setup, $brewing);
$totalRows_setup = mysql_num_rows($setup);

if ($totalRows_setup == 0) { 
header ("Location: setup.php?section=step1");
} 
else 
{
require ('includes/authentication_nav.inc.php');  session_start(); 
require ('includes/url_variables.inc.php');
require ('includes/db_connect.inc.php');
include ('includes/plug-ins.inc.php');
$today = date('Y-m-d');
$deadline = $row_contest_info['contestRegistrationDeadline'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $row_contest_info['contestName']; ?> Organized By <?php echo $row_contest_info['contestHost']; ?></title>
<link href="css/default.css" rel="stylesheet" type="text/css" />
<link href="css/thickbox.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="js_includes/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js_includes/thickbox.js"></script>
<script type="text/javascript" src="js_includes/delete.js"></script>
<script type="text/javascript" src="js_includes/CalendarControl.js" ></script>
<script type="text/javascript" src="js_includes/jump_menu.js" ></script>
<?php if ($section == "admin") { ?>
<script type="text/javascript" src="js_includes/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="js_includes/tinymce.init.js"></script>
<?php } ?>
<?php include ('includes/form_check.inc.php'); ?>
</head>
<body>
<div id="container">
<div id="navigation">
	<div id="navigation-inner"><?php include ('sections/nav.sec.php'); ?></div>
</div>
<div id="content">
	<div id="content-inner">   
    <?php 
	// Check if registration date has passed. If so, display "registration end" message.
	if (greaterDate($today,$deadline)) {
	if ($section != "admin") { ?>
    <div id="closed">Registration has closed. Thanks to everyone who participated.</div>
	<?php }  
	if ($section == "default") 	include ('sections/default.sec.php');
	if ($section == "login")	include ('sections/login.sec.php');
	if ($section == "rules") 	include ('sections/rules.sec.php');
	if ($section == "entry") 	include ('sections/entry_info.sec.php');
	if ($section == "sponsors") include ('sections/sponsors.sec.php');
	if (isset($_SESSION['loginUsername'])) {
		if ($section == "list") 	include ('sections/list.sec.php');
		if ($section == "pay") 		include ('sections/pay.sec.php');
		if ($row_user['userLevel'] == "1") {
			if ($section == "admin")	include ('admin/default.admin.php');
			if ($section == "brewer") 	include ('sections/brewer.sec.php');
			if ($section == "brew") 	include ('sections/brew.sec.php');
			if ($section == "judge") 	include ('sections/judge.sec.php');
			if ($section == "user") 	include ('sections/user.sec.php');
			if ($section == "beerxml")	include ('sections/beerxml.sec.php');
			}
		}
	} else 
	{
	if ($section == "register") include ('sections/register.sec.php');
	if ($section == "login")	include ('sections/login.sec.php');
	if ($section == "rules") 	include ('sections/rules.sec.php');
	if ($section == "entry") 	include ('sections/entry_info.sec.php');
	if ($section == "default") 	include ('sections/default.sec.php');
	if ($section == "sponsors") include ('sections/sponsors.sec.php');
	if (isset($_SESSION['loginUsername'])) {
		if ($row_user['userLevel'] == "1") { if ($section == "admin")	include ('admin/default.admin.php'); }
		if ($section == "brewer") 	include ('sections/brewer.sec.php');
		if ($section == "brew") 	include ('sections/brew.sec.php');
		if ($section == "pay") 		include ('sections/pay.sec.php');
		if ($section == "list") 	include ('sections/list.sec.php');
		if ($section == "judge") 	include ('sections/judge.sec.php');
		if ($section == "user") 	include ('sections/user.sec.php');
		if ($section == "beerxml")	include ('sections/beerxml.sec.php');
	}
	if ((!isset($_SESSION['loginUsername'])) && (($section == "admin") || ($section == "brewer") || ($section == "brew") || ($section == "user") || ($section == "judge") || ($section == "list") || ($section == "pay") || ($section == "beerXML")))  
	echo "<div id=\"header\"><div id=\"header-inner\"><h1>Restricted Area</h1></div></div>
	<div class=\"error\">Please register or log in to access this area.</div>";
	} // End registration date check.
	?>
	</div>
</div>
</div>
<div id="footer">
	<div id="footer-inner"><a href="http://competition.brewblogger.net" target="_blank">Brew Competition Online Entry</a> (BCOE) <?php include ('includes/version.inc.php'); ?> &copy;<?php if (date ('Y') == "2009") echo date('Y'); else echo "2009-".date('Y'); ?> Geoff Humphrey for <a href="http://www.zkdigital.com" target="_blank">zkdigital.com</a>.</div>
</div>
</body>
</html>
<?php } ?>