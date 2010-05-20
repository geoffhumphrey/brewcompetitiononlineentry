<?php 
require ('Connections/config.php');

// Check to see if initial setup has taken place 
mysql_select_db($database, $brewing);
$query_setup = "SELECT * FROM users";
$setup = mysql_query($query_setup, $brewing);
$totalRows_setup = mysql_num_rows($setup);

$query_setup1 = "SELECT * FROM contest_info";
$setup1 = mysql_query($query_setup1, $brewing);
$totalRows_setup1 = mysql_num_rows($setup1);

$query_setup2 = "SELECT * FROM preferences";
$setup2 = mysql_query($query_setup2, $brewing);
$totalRows_setup2 = mysql_num_rows($setup2);

if (($totalRows_setup == 0) && ($totalRows_setup1 == 0) && ($totalRows_setup2 == 0)) { 
header ("Location: setup.php?section=step1");
} 
// If all setup has taken place, run normally
else 
{
require ('includes/authentication_nav.inc.php');  session_start(); 
require ('includes/url_variables.inc.php');
require ('includes/db_connect.inc.php');
include ('includes/plug-ins.inc.php');
include ('includes/version.inc.php');
include ('includes/headers.inc.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $row_contest_info['contestName']; ?> Organized By <?php echo $row_contest_info['contestHost']." | ".$header_output; ?></title>
<link href="css/default.css" rel="stylesheet" type="text/css" />
<link href="css/thickbox.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="js_includes/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js_includes/thickbox.js"></script>
<script type="text/javascript" src="js_includes/delete.js"></script>
<script type="text/javascript" src="js_includes/CalendarControl.js" ></script>
<script type="text/javascript" src="js_includes/jump_menu.js" ></script>
<script type="text/javascript" src="js_includes/smoothscroll.js" ></script>
<?php if ((isset($_SESSION["loginUsername"])) && ($row_user['userLevel'] == "1")) { ?>
<script type="text/javascript" src="js_includes/menu.js"></script>
<?php } 
if ($section == "admin") { ?>
<script type="text/javascript" src="js_includes/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="js_includes/tinymce.init.js"></script>
<?php } 
if (($section == "admin") || ($section == "brew") || ($section == "brewer") || ($section == "user")  || ($section == "register")) include ('includes/form_check.inc.php'); ?>
</head>
<body>
<a name="top"></a>
<div id="container">
<div id="navigation">
	<div id="navigation-inner"><?php include ('sections/nav.sec.php'); ?></div>
</div>
<div id="content">
  <div id="content-inner">
  <?php if ($section != "admin") { ?>
  <div id="header">	
	<div id="header-inner"><h1><?php echo $header_output; ?></h1></div>
  </div>
  <?php }
  
  // Check if registration open date has passed. If so, display "registration not open yet" message.
  if (lesserDate($today,$reg_open)) { 
  	if ($section != "admin") {
  	?>
    <div id="closed">Registration will open <?php echo dateconvert($row_contest_info['contestRegistrationOpen'], 2); ?>.</div>
	<?php }
	if ($section == "default") 	include ('sections/default.sec.php');
	if ($section == "login")	include ('sections/login.sec.php');
	if ($section == "rules") 	include ('sections/rules.sec.php');
	if ($section == "entry") 	include ('sections/entry_info.sec.php');
	if ($section == "sponsors") include ('sections/sponsors.sec.php');
	if ($section == "past_winners") include ('sections/past_winners.sec.php');
	if (isset($_SESSION['loginUsername'])) {
		if ($row_user['userLevel'] == "1") {
			if ($section == "list") 	include ('sections/list.sec.php');
			if ($section == "pay") 		include ('sections/pay.sec.php');
			if ($section == "admin")	include ('admin/default.admin.php');
			if ($section == "brewer") 	include ('sections/brewer.sec.php');
			if ($section == "brew") 	include ('sections/brew.sec.php');
			if ($section == "judge") 	include ('sections/judge.sec.php');
			if ($section == "user") 	include ('sections/user.sec.php');
			if ($section == "beerxml")	include ('sections/beerxml.sec.php');
			}
		}
  }
  // Check if registration close date has passed. If so, display "registration end" message.
  elseif (greaterDate($today,$reg_deadline)) {
	if ((($section != "admin") || ($row_user['userLevel'] != "1")) && ($judgingDateReturn == "false")) { ?>
    <div id="closed">Registration has closed.</div>
	<?php }  
	if ($section == "default") 	include ('sections/default.sec.php');
	if ($section == "login")	include ('sections/login.sec.php');
	if ($section == "rules") 	include ('sections/rules.sec.php');
	if ($section == "entry") 	include ('sections/entry_info.sec.php');
	if ($section == "sponsors") include ('sections/sponsors.sec.php');
	if ($section == "past_winners") include ('sections/past_winners.sec.php');
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
  } else { // If registration is not closed
	if ($section == "register") include ('sections/register.sec.php');
	if ($section == "login")	include ('sections/login.sec.php');
	if ($section == "rules") 	include ('sections/rules.sec.php');
	if ($section == "entry") 	include ('sections/entry_info.sec.php');
	if ($section == "default") 	include ('sections/default.sec.php');
	if ($section == "sponsors") include ('sections/sponsors.sec.php');
	if ($section == "past_winners") include ('sections/past_winners.sec.php');
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
  } // End registration date check.
  if ((!isset($_SESSION['loginUsername'])) && (($section == "admin") || ($section == "brewer") || ($section == "brew") || ($section == "user") || ($section == "judge") || ($section == "list") || ($section == "pay") || ($section == "beerXML")))  
  echo "<div class=\"error\">Please register or log in to access this area.</div>";
  if ($action != "print") { ?>
  <p><a href="#top">Top</a></p>
  <?php } ?>
  </div>
</div>
</div>
<a name="bottom"></a>
<div id="footer">
	<div id="footer-inner"><?php include ('sections/footer.sec.php'); ?></div>
</div>
</body>
</html>
<?php } ?>