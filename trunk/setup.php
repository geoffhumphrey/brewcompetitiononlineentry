<?php
define('ROOT',$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR);
define('INCLUDES',ROOT.'includes'.DIRECTORY_SEPARATOR);
define('CONFIG',ROOT.'Connections'.DIRECTORY_SEPARATOR);
define('SECTIONS',ROOT.'sections'.DIRECTORY_SEPARATOR);
define('ADMIN',ROOT.'admin'.DIRECTORY_SEPARATOR);
define('TEMPLATES',ROOT.'templates'.DIRECTORY_SEPARATOR);
define('SETUP',ROOT.'setup'.DIRECTORY_SEPARATOR);
define('DB',ROOT.'includes'.DIRECTORY_SEPARATOR.'db'.DIRECTORY_SEPARATOR);

require(CONFIG.'config.php');
require(INCLUDES.'functions.inc.php'); 

// Check to see if initial setup has taken place 
mysql_select_db($database, $brewing);
$query_setup = "SELECT COUNT(*) as 'count' FROM users";
$setup = mysql_query($query_setup, $brewing);
$totalRows_setup = $row_setup['count'];

if ($totalRows_setup > 0) header ('Location: index.php'); 
else
{
require(INCLUDES.'url_variables.inc.php');
require(DB.'common.db.php');
require(INCLUDES.'version.inc.php');
require(INCLUDES.'headers.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Set Up Your Brew Competition Online Entry Site</title>
<link href="css/html_elements.css" rel="stylesheet" type="text/css" />
<link href="css/<?php echo $row_prefs['prefsTheme']; ?>.css" rel="stylesheet" type="text/css" />
<link href="css/thickbox.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="js_includes/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="js_includes/tinymce.init.js"></script>
<script type="text/javascript" src="js_includes/calendar_control.js" ></script>
<script type="text/javascript" src="js_includes/jquery.js"></script>
<script type="text/javascript" src="js_includes/thickbox.js"></script>
<?php include(INCLUDES.'form_check.inc.php'); ?>
</head>
<body>
<div id="container">
<div id="navigation">
	<div id="navigation-inner"><?php include(SECTIONS.'nav.sec.php'); ?></div>
</div>
	<div id="content">
		<div id="content-inner">
        <div id="header">	
			<div id="header-inner"><h1><?php echo $header_output; ?></h1></div>
		</div>
    	<?php
        if ($section == "step1") 	include(SETUP.'admin_user.setup.php');
		if ($section == "step2") 	include(SETUP.'admin_user_info.setup.php');
		if ($section == "step3") 	include(SETUP.'site_preferences.setup.php');
		if ($section == "step4") 	include(SETUP.'competition_info.setup.php');
		if ($section == "step6") 	include(SETUP.'drop-off.setup.php');
		if ($section == "step5") 	include(SETUP.'judging_locations.setup.php');
		if ($section == "step7") 	include(SETUP.'accepted_styles.setup.php');
		if ($section == "step8") 	include(SETUP.'judging_preferences.setup.php');
		?>
    	</div>
	</div>
</div>
<div id="footer">
	<div id="footer-inner"><?php include(SECTIONS.'footer.sec.php'); ?></div>
</div>
</body>
</html>
<?php } ?>
