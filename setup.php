<?php
require('paths.php');
require(INCLUDES.'url_variables.inc.php');
$current_version = "1.2.2.0"; 
require(INCLUDES.'functions.inc.php'); 
require(INCLUDES.'url_variables.inc.php');
require(INCLUDES.'db_tables.inc.php');

// Check to see if initial setup has taken place 

if (table_exists($prefix."system")) {
mysql_select_db($database, $brewing);
$query_system = sprintf("SELECT setup FROM %s", $prefix."system");
$system = mysql_query($query_system, $brewing) or die(mysql_error());
$row_system = mysql_fetch_assoc($system);

if ($row_system['setup'] == 1) header ('Location: index.php');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Set Up Your Brew Competition Online Entry Site</title>
<link href="<?php echo $base_url; ?>/css/html_elements.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $base_url; ?>/css/default.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $base_url; ?>/css/jquery-ui-1.8.18.custom.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/jquery.ui.widget.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/jquery.ui.tabs.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/jquery.ui.position.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/fancybox/jquery.easing-1.3.pack.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/fancybox/jquery.mousewheel-3.0.6.pack.js"></script>
<link href="<?php echo $base_url; ?>/css/jquery.ui.timepicker.css?v=0.3.0" rel="stylesheet"  type="text/css" />
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/jquery.ui.timepicker.js?v=0.3.0"></script>
<link href="<?php echo $base_url; ?>/js_includes/fancybox/jquery.fancybox.css?v=2.0.2" rel="stylesheet"  type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/fancybox/jquery.fancybox.pack.js?v=2.0.2"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#modal_window_link").fancybox({
				'width'				: '85%',
				'maxHeight'			: '85%',
				'fitToView'			: false,
				'scrolling'         : 'auto',
				'openEffect'		: 'elastic',
				'closeEffect'		: 'elastic',
				'openEasing'     	: 'easeOutBack',
				'closeEasing'   	: 'easeInBack',
				'openSpeed'         : 'normal',
				'closeSpeed'        : 'normal',
				'type'				: 'iframe',
				'helpers' 			: {	title : { type : 'inside' } },
			});

		});
	</script>
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/delete.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/jump_menu.js" ></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/smoothscroll.js" ></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/tinymce.init.js"></script>
<?php include(INCLUDES.'form_check.inc.php'); ?>
</head>
<body>
<div id="container">
<div id="navigation">
	<div id="navigation-inner"><?php // include(SECTIONS.'nav.sec.php'); ?></div>
</div>
	<div id="content">
		<div id="content-inner">
        <div id="header">	
			<div id="header-inner"><h1>BCOE&amp;M <?php echo $current_version; ?> Setup</h1></div>
		</div>
<?php 
if ($setup_free_access == TRUE) {
	if ($section != "step0") require(DB.'common.db.php');
	require(INCLUDES.'version.inc.php');
	if ((!table_exists($prefix."system")) && ($section == "step0"))	include(SETUP.'install_db.setup.php');
	if (table_exists($prefix."system")) {
		mysql_select_db($database, $brewing);
		$query_system = sprintf("SELECT setup FROM %s", $prefix."system");
		$system = mysql_query($query_system, $brewing) or die(mysql_error());
		$row_system = mysql_fetch_assoc($system);
		
		if ($row_system['setup'] == 0) {
			if ($section == "step1") 	include(SETUP.'admin_user.setup.php');
			if ($section == "step2") 	include(SETUP.'admin_user_info.setup.php');
			if ($section == "step3") 	include(SETUP.'site_preferences.setup.php');
			if ($section == "step4") 	include(SETUP.'competition_info.setup.php');
			if ($section == "step6") 	include(SETUP.'drop-off.setup.php');
			if ($section == "step5") 	include(SETUP.'judging_locations.setup.php');
			if ($section == "step7") 	include(SETUP.'accepted_styles.setup.php');
			if ($section == "step8") 	include(SETUP.'judging_preferences.setup.php');		
		}
		
	} // end if (table_exists($prefix."system"))
	
}

if ($setup_free_access == FALSE) {
	echo "
	<div class='error'>Setup Cannot Run</div>
	<p>The variable called &#36;setup_free_access is set to FALSE in the config.php file. The config.php file is located in the &ldquo;site&rdquo; folder on your server.</p>
	<p><strong>For the install and setup scripts to run, it must be set to TRUE. Server access is required to change the config.php file.</strong></p>
	<p>Once the installation has finished, you should change the &#36;setup_free_access variable back to FALSE for security reasons.</p>
	";
}

?>
    	</div>
	</div>
</div>
<div id="footer">
	<div id="footer-inner"><a href="http://www.brewcompetition.com" target="_blank">BCOE&amp;M</a> Version <?php echo $current_version; ?> &copy;<?php  echo "2009-".date('Y'); ?> by <a href="http://www.zkdigital.com" target="_blank">zkdigital.com</a>.</div>
</div>
</body>
</html>