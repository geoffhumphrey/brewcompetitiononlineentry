<?php 

/**
 * Module:      print.php 
 * Description: This module is the gateway for printing information from
 *              the site.
 * 
 */

session_start(); 
require('../paths.php'); 
require(CONFIG.'bootstrap.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['contestName']; ?> organized by <?php echo $_SESSION['contestHost']; ?></title>
<link href="<?php echo $base_url; ?>css/html_elements.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $base_url; ?>css/common.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $base_url; ?>css/messages.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $base_url; ?>css/print.css" rel="stylesheet" type="text/css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> 
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jquery.dataTables.js"></script>
</head>
<body>
<div id="printable">
<div id="content">
	<div id="content-inner">
    <!-- <button class="print no-print" style="cursor:pointer; vertical-align:middle;"><?php echo "<img src=\"".$base_url."images/printer.png\">"; ?> Print </button> -->
    <?php if ($section != "admin") { ?>
    <div id="header">	
		<div id="header-inner"><h1><?php echo $header_output; ?></h1></div>
	</div>
    <?php 
	} 

	if ($section == "rules") 		include (SECTIONS.'rules.sec.php');
	if ($section == "entry") 		include (SECTIONS.'entry_info.sec.php');
	if ($section == "default") 		include (SECTIONS.'default.sec.php');
	if ($section == "sponsors") 	include (SECTIONS.'sponsors.sec.php');
	if ($section == "past_winners") include (SECTIONS.'past_winners.sec.php');
	if ($section == "contact") 		include (SECTIONS.'contact.sec.php');
	if ($section == "volunteers") 	include (SECTIONS.'volunteers.sec.php');	
	if (isset($_SESSION['loginUsername'])) {
		if ($section == "admin")	include (ADMIN.'default.admin.php'); 
		if ($section == "brewer") 	include (SECTIONS.'brewer.sec.php');
		if ($section == "brew") 	include (SECTIONS.'brew.sec.php');
		if ($section == "pay") 		include (SECTIONS.'pay.sec.php');
		if ($section == "list") 	include (SECTIONS.'list.sec.php');
	}
	?>
    </div>
</div>
<div id="footer">
	<div id="footer-inner">Printed <?php echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], time(), $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "long", "date-time"); ?>.</div>
</div>
</div>
<!--
<script src="<?php echo $base_url; ?>js_includes/jQuery.print.js"></script> 
<script type='text/javascript'>
	$(function() {
		$("#printable").find('.print').on('click', function() {
			$.print("#printable");
		}); 
	});
</script>
-->
<?php if (!$fx) { ?>
<script type="text/javascript">
function selfPrint(){
    self.focus();
    self.print();
}
setTimeout('selfPrint()',3000);
html.push(''); 
</script>
<?php } ?>
</body>
</html>