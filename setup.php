<?php
require ('Connections/config.php'); 

// Check to see if initial setup has taken place 
mysql_select_db($database, $brewing);
$query_setup = "SELECT * FROM users";
$setup = mysql_query($query_setup, $brewing);
$totalRows_setup = mysql_num_rows($setup);

if ($totalRows_setup > 0) header ('Location: index.php'); else

{
require ('includes/url_variables.inc.php');
require ('includes/db_connect.inc.php');
include ('includes/plug-ins.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Set Up Your Brew Competition Online Signup Site</title>
<link href="css/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js_includes/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="js_includes/tinymce.init.js"></script>
<script type="text/javascript" src="js_includes/CalendarControl.js" ></script>
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
        if ($section == "step1") 	include ('setup/step1.setup.php');
		if ($section == "step2") 	include ('setup/step2.setup.php');
		if ($section == "step3") 	include ('setup/step3.setup.php');
		if ($section == "step4") 	include ('setup/step4.setup.php');
		?>
    	</div>
	</div>
</div>
<div id="footer">
	<div id="footer-inner"><?php include ('sections/footer.sec.php'); ?></div>
</div>
</body>
</html>
<?php } ?>
