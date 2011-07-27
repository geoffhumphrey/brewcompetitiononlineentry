<?php 
/**
 * Module:      print.php 
 * Description: This module is the gateway for printing information from
 *              the site.
 * 
 */

require('../paths.php'); 
require(INCLUDES.'functions.inc.php');
require(INCLUDES.'authentication_nav.inc.php');  session_start(); 
require(INCLUDES.'url_variables.inc.php');
require(DB.'common.db.php');
require(DB.'brewer.db.php');
require(INCLUDES.'version.inc.php');
require(INCLUDES.'headers.inc.php');

$tb = "default";
if (isset($_GET['tb'])) {
  $tb = (get_magic_quotes_gpc()) ? $_GET['tb'] : addslashes($_GET['tb']);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php if ($tb == "default") { ?><meta http-equiv="refresh" content="0;URL=<?php echo "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."&tb=true"; ?>" /><?php } ?>
<title><?php echo $row_contest_info['contestName']; ?> organized by <?php echo $row_contest_info['contestHost']; ?></title>
<link href="../css/print.css" rel="stylesheet" type="text/css" />
</head>
<body <?php if ($tb == "true") echo "onload=\"javascript:window.print()\""; ?>>
<div id="content">
	<div id="content-inner">
    <?php if ($section != "admin") { ?>
    <div id="header">	
		<div id="header-inner"><h1><?php echo $header_output; ?></h1></div>
	</div>
    <?php 
	} 
	
	//if ($section == "register") 	include (SECTIONS.'register.sec.php');
	//if ($section == "login")		include (SECTIONS.'login.sec.php');
	if ($section == "rules") 		include (SECTIONS.'rules.sec.php');
	if ($section == "entry") 		include (SECTIONS.'entry_info.sec.php');
	if ($section == "default") 		include (SECTIONS.'default.sec.php');
	if ($section == "sponsors") 	include (SECTIONS.'sponsors.sec.php');
	if ($section == "past_winners") include (SECTIONS.'past_winners.sec.php');
	if ($section == "contact") 		include (SECTIONS.'contact.sec.php');
	if (isset($_SESSION['loginUsername'])) {
		if ($row_user['userLevel'] == "1") { if ($section == "admin")	include (ADMIN.'default.admin.php'); }
		if ($section == "brewer") 	include (SECTIONS.'brewer.sec.php');
		if ($section == "brew") 	include (SECTIONS.'brew.sec.php');
		if ($section == "pay") 		include (SECTIONS.'pay.sec.php');
		if ($section == "list") 	include (SECTIONS.'list.sec.php');
		//if ($section == "judge") 	include (SECTIONS.'judge.sec.php');
		//if ($section == "user") 	include (SECTIONS.'user.sec.php');
		//if ($section == "beerxml")	include (SECTIONS.'beerxml.sec.php');
	}
	?>
    </div>
</div>
<div id="footer">
	<div id="footer-inner">Printed <?php echo date_convert(date("Y-m-d"), 2, $row_prefs['prefsDateFormat']) ; ?></div>
</div>
</body>
</html>