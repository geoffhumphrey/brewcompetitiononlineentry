<?php 
/**
 * Module:      index.php 
 * Description: This module is the delivery vehicle for all functions.
 * 
 */

require('paths.php');
require(INCLUDES.'functions.inc.php');
$php_version = phpversion();
$current_page = "http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?".$_SERVER['QUERY_STRING'];
$images_dir = dirname( __FILE__ );
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', '1');
// Check to see if initial setup has taken place 
if (check_setup()) header ("Location: setup.php?section=step1"); 


// If all setup has taken place, run normally
else 
{
// check to see if all judging numbers have been generated. If not, generate
if (!check_judging_numbers()) header("Location: includes/process.inc.php?action=generate_judging_numbers&go=hidden");
require(INCLUDES.'authentication_nav.inc.php');  session_start(); 
require(INCLUDES.'url_variables.inc.php');
require(DB.'common.db.php');
require(DB.'brewer.db.php');
include(DB.'entries.db.php');
require(INCLUDES.'version.inc.php');
require(INCLUDES.'headers.inc.php');
require(INCLUDES.'constants.inc.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $row_contest_info['contestName']; ?> Organized By <?php echo $row_contest_info['contestHost']." &gt; ".$header_output; ?></title>
<link href="css/<?php echo $row_prefs['prefsTheme']; ?>.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/jquery-ui-1.8.18.custom.css" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<script type="text/javascript" src="js_includes/jquery-ui-1.8.18.custom.min.js"></script>

<script type="text/javascript" src="js_includes/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="js_includes/jquery.ui.widget.min.js"></script>
<script type="text/javascript" src="js_includes/jquery.ui.tabs.min.js"></script>
<script type="text/javascript" src="js_includes/jquery.ui.position.min.js"></script>
<script type="text/javascript" src="js_includes/fancybox/jquery.easing-1.3.pack.js"></script>
<script type="text/javascript" src="js_includes/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<link rel="stylesheet" href="js_includes/fancybox/jquery.fancybox.css?v=2.0.2" type="text/css" media="screen" />
<script type="text/javascript" src="js_includes/fancybox/jquery.fancybox.pack.js?v=2.0.2"></script>
	<script type="text/javascript">
		$(document).ready(function() {

			$("#modal_window_link").fancybox({
				'width'				: '75%',
				'height'			: '75%',
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
				<?php if ($modal_window == "false") { ?>
				'afterClose': 		function() { parent.location.reload(true); }
				<?php } ?>
			});

		});
	</script>
<script type="text/javascript" src="js_includes/jquery.dataTables.js"></script>
<script type="text/javascript" src="js_includes/thickbox.js"></script>
<script type="text/javascript" src="js_includes/delete.js"></script>
<script type="text/javascript" src="js_includes/calendar_control.js" ></script>
<script type="text/javascript" src="js_includes/jump_menu.js" ></script>
<script type="text/javascript" src="js_includes/smoothscroll.js" ></script>
<?php if ((isset($_SESSION["loginUsername"])) && ($row_user['userLevel'] == "1")) { ?>
<script type="text/javascript" src="js_includes/menu.js"></script>
<?php } 
if ($section == "admin") { ?>
<script type="text/javascript" src="js_includes/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="js_includes/tinymce.init.js"></script>
<?php } 
if (($section == "admin") || ($section == "brew") || ($section == "brewer") || ($section == "user")  || ($section == "register") || ($section == "contact")) include(INCLUDES.'form_check.inc.php'); ?>
</head>
<body>
<a name="top"></a>
<div id="container">
<div id="navigation">
	<div id="navigation-inner"><?php include (SECTIONS.'nav.sec.php'); ?></div>
</div>
<div id="content">
 	 <div id="content-inner"> 
  <?php if ($section != "admin") { ?>
 	<div id="header">	
		<div id="header-inner"><h1><?php echo $header_output; ?></h1></div>
  	</div>
  <?php }
  
  // Check if registration open date has not passed. If so, display "registration not open yet" message.
  if ($registration_open == "0") { 
  	if ($section != "admin") {
  	?>
    <div class="closed">Registration will open <?php echo $reg_open; ?>.</div>
	<?php }
	if ($section == "default") 		include (SECTIONS.'default.sec.php');
	if ($section == "login")		include (SECTIONS.'login.sec.php');
	if ($section == "rules") 		include (SECTIONS.'rules.sec.php');
	if ($section == "entry") 		include (SECTIONS.'entry_info.sec.php');
	if ($section == "sponsors") 	include (SECTIONS.'sponsors.sec.php');
	if ($section == "past_winners") include (SECTIONS.'past_winners.sec.php');
	if ($section == "contact") 		include (SECTIONS.'contact.sec.php');
	if (isset($_SESSION['loginUsername'])) {
		if ($row_user['userLevel'] == "1") {
			if ($section == "list") 	include (SECTIONS.'list.sec.php');
			if ($section == "pay") 		include (SECTIONS.'pay.sec.php');
			if ($section == "admin")	include (ADMIN.'default.admin.php');
			if ($section == "brewer") 	include (SECTIONS.'brewer.sec.php');
			if ($section == "brew") 	include (SECTIONS.'brew.sec.php');
			if ($section == "judge") 	include (SECTIONS.'judge.sec.php');
			if ($section == "user") 	include (SECTIONS.'user.sec.php');
			if ($section == "beerxml")	include (SECTIONS.'beerxml.sec.php');
			}
		}
  }
  // Check if registration close date has passed. If so, display "registration end" message.
  if ($registration_open == "2") {
	if ((($section != "admin") || ($row_user['userLevel'] != "1")) && (judging_date_return() > 0)) { ?>
    <div class="closed">Entry registration closed <?php echo $reg_closed; ?>.</div>
    <?php if ((!isset($_SESSION['loginUsername'])) && ($section != "register")) { ?><div class="error">If you are willing to be a judge or steward, please <a href="index.php?section=register&amp;go=judge">register here</a>.</div><?php } ?>
	<?php }  
	if ($section == "default") 		include (SECTIONS.'default.sec.php');
	if ($section == "register") 	include (SECTIONS.'register.sec.php');
	if ($section == "login")		include (SECTIONS.'login.sec.php');
	if ($section == "rules") 		include (SECTIONS.'rules.sec.php');
	if ($section == "entry") 		include (SECTIONS.'entry_info.sec.php');
	if ($section == "sponsors") 	include (SECTIONS.'sponsors.sec.php');
	if ($section == "past_winners") include (SECTIONS.'past_winners.sec.php');
	if ($section == "contact") 		include (SECTIONS.'contact.sec.php');
	if (isset($_SESSION['loginUsername'])) {
		if ($section == "list") 	include (SECTIONS.'list.sec.php');
		if ($section == "pay") 		include (SECTIONS.'pay.sec.php');
		if ($section == "brewer") 	include (SECTIONS.'brewer.sec.php');
			
		if ($row_user['userLevel'] == "1") {
			if ($section == "admin")	include (ADMIN.'default.admin.php');
			if ($section == "brew") 	include (SECTIONS.'brew.sec.php');
			if ($section == "judge") 	include (SECTIONS.'judge.sec.php');
			if ($section == "user") 	include (SECTIONS.'user.sec.php');
			if ($section == "beerxml")	include (SECTIONS.'beerxml.sec.php');
			}
		}
  } 
  if ($registration_open == "1") { // If registration is currently open
  	if ((!isset($_SESSION['loginUsername'])) && ($action != "print") && ($section != "register") && (open_limit($totalRows_log,$row_prefs['prefsEntryLimit'],$registration_open))) echo "<div class='closed'>The limit of ".$row_prefs['prefsEntryLimit']." entries has been reached. Judges and stewards may still <a href='index.php?section=register&amp;go=judge'>register</a>, but entries will no longer be accepted..</div>";
	if ($section == "register") 	include (SECTIONS.'register.sec.php');
	if ($section == "login")		include (SECTIONS.'login.sec.php');
	if ($section == "rules") 		include (SECTIONS.'rules.sec.php');
	if ($section == "entry") 		include (SECTIONS.'entry_info.sec.php');
	if ($section == "default") 		include (SECTIONS.'default.sec.php');
	if ($section == "sponsors") 	include (SECTIONS.'sponsors.sec.php');
	if ($section == "past_winners") include (SECTIONS.'past_winners.sec.php');
	if ($section == "contact") 		include (SECTIONS.'contact.sec.php');
	// if ($section == "brewer") 		include (SECTIONS.'brewer.sec.php');
	if (isset($_SESSION['loginUsername'])) {
		if ($row_user['userLevel'] == "1") { if ($section == "admin")	include (ADMIN.'default.admin.php'); }
		if ($section == "brewer") 	include (SECTIONS.'brewer.sec.php');
		if ($section == "brew") 	include (SECTIONS.'brew.sec.php');
		if ($section == "pay") 		include (SECTIONS.'pay.sec.php');
		if ($section == "list") 	include (SECTIONS.'list.sec.php');
		if ($section == "judge") 	include (SECTIONS.'judge.sec.php');
		if ($section == "user") 	include (SECTIONS.'user.sec.php');
		if ($section == "beerxml")	include (SECTIONS.'beerxml.sec.php');
	}
  } // End registration date check.
  if ((!isset($_SESSION['loginUsername'])) && (($section == "admin") || ($section == "brew") || ($section == "user") || ($section == "judge") || ($section == "list") || ($section == "pay") || ($section == "beerXML"))) { ?>  
  <?php if ($section == "admin") { ?>
  <div id="header">	
	<div id="header-inner"><h1><?php echo $header_output; ?></h1></div>
  </div>
  <?php } ?>
  <div class="error">Please register or log in to access this area.</div>
  <?php } ?>
  </div>
</div>
</div>
<a name="bottom"></a>
<div id="footer">
	<div id="footer-inner"><?php include (SECTIONS.'footer.sec.php'); ?></div>
</div>
</body>
</html>
<?php } ?>