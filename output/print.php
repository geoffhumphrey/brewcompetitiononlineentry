<?php 
/**
 * Module:      print.php 
 * Description: This module is the gateway for printing information from
 *              the site.
 * 
 */

require('../paths.php');
error_reporting(0);
require(INCLUDES.'functions.inc.php');
require(INCLUDES.'authentication_nav.inc.php');  session_start(); 
require(INCLUDES.'url_variables.inc.php');
require(INCLUDES.'db_tables.inc.php'); 
require(DB.'common.db.php');
require(DB.'brewer.db.php');
require(DB.'entries.db.php');
require(INCLUDES.'headers.inc.php');
require(INCLUDES.'constants.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $row_contest_info['contestName']; ?> organized by <?php echo $row_contest_info['contestHost']; ?></title>
<link href="<?php echo $base_url; ?>/css/html_elements.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $base_url; ?>/css/common.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $base_url; ?>/css/messages.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $base_url; ?>/css/print.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/jquery.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/jquery.dataTables.js"></script>
</head>
<body>
<script type="text/javascript">
function selfPrint(){
    self.focus();
    self.print();
}
setTimeout('selfPrint()',200);
</script>
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
	if ($section == "volunteers") 	include (SECTIONS.'volunteers.sec.php');	
	if (isset($_SESSION['loginUsername'])) {
		//if ($row_user['userLevel'] <= "1") { 
		if ($section == "admin")	include (ADMIN.'default.admin.php'); 
		//}
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
	<div id="footer-inner">Printed <?php echo getTimeZoneDateTime($row_prefs['prefsTimeZone'], time(), $row_prefs['prefsDateFormat'], $row_prefs['prefsTimeFormat'], "long", "date-time"); ?>.</div>
</div>
</body>
</html>