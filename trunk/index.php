<?php 
/**
 * Module:      index.php 
 * Description: This module is the delivery vehicle for all modules.
 * 
 */

require('paths.php');
define('MAINT',FALSE);
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', '1');

function check_setup($tablename, $database) {
	require(CONFIG.'config.php');
	$query_log = "SELECT COUNT(*) AS count FROM information_schema.tables WHERE table_schema = '$database' AND table_name = '$tablename'";
	$log = mysql_query($query_log, $brewing) or die(mysql_error());
	$row_log = mysql_fetch_assoc($log);

    if ($row_log['count'] == 0) return FALSE;
	else return TRUE;

}

if (!check_setup($prefix."system",$database)) { header ("Location: setup.php?section=step0");  exit; }
elseif (!check_setup($prefix."mods",$database)) { header ("Location: update.php");  exit; }
elseif (MAINT) { header ("Location: maintenance.php"); exit; }

// If all setup or update has taken place, run normally
else 
{
/*
function version_check($version) {
	// Current version is 1.2.2.0, change version in system table if not
	// There are NO database structure or data updates for version 1.2.1.3
	// USE THIS FUNCTION ONLY IF THERE ARE NOT ANY DB TABLE OR DATA UPDATES
	// OTHERWISE, DEFINE/UPDATE THE VERSION VIA THE UPDATE FUNCTION
	require(CONFIG.'config.php');
	
	if ($version != "1.2.2.0") {
		$updateSQL = sprintf("UPDATE %s SET version='%s', version_date='%s' WHERE id='%s'",$prefix."system","1.2.2.0","2013-01-31","1");
		mysql_select_db($database, $brewing);
		$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
	}
}
	
version_check($version);
*/
require(INCLUDES.'functions.inc.php');
require(INCLUDES.'authentication_nav.inc.php');  session_start(); 
require(INCLUDES.'url_variables.inc.php');
require(INCLUDES.'db_tables.inc.php');
require(DB.'common.db.php');

if ((isset($_SESSION['prefix'])) && ($_SESSION['prefix'] != $prefix)) {
	session_unset();
	session_destroy();
	session_write_close();
	setcookie(session_name(),'',0,'/');
	session_regenerate_id(true);
	header("Location: index.php");
	exit;
}

/*
if (empty($_SESSION['prefs'])) { 
	if (NHC) $location = "index.php";
	else $location = $base_url;
	header(sprintf("Location: %s",$location));
}

if ((isset($_SESSION['loginUsername'])) && (empty($_SESSION['user_info'.$prefix_session]))) { 

	if (($_SESSION['userLevel'] <= 1) && ($section == "list") && ($msg == "2")) $location = "index.php?section=list";
	elseif (($_SESSION['userLevel'] <= 1) && ($msg == "default")) $location = "index.php?section=admin";
	else $location = build_public_url("list","default","default",$sef,$base_url);
	
	header(sprintf("Location: %s",$location));
}
*/
require(DB.'brewer.db.php');
require(DB.'entries.db.php');
require(INCLUDES.'headers.inc.php');
require(INCLUDES.'constants.inc.php');
//require(DB.'winners.db.php');

if ($_SESSION['prefsSEF'] == "Y") $sef = "true";
else $sef = "false";

// Perform data integrity check on users, brewer, and brewing tables at 24 hour intervals
if ((!NHC) && ($today > ($_SESSION['dataCheck'] + 86400))) data_integrity_check();

// check to see if all judging numbers have been generated. If not, generate
if ((!check_judging_numbers()) && (!NHC)) header("Location: includes/process.inc.php?action=generate_judging_numbers&go=hidden");


/*
// Automatically purge all unconfirmed entries
purge_entries("unconfirmed", 1);

// Purge entries without defined special ingredients designated to particular styles that require them
purge_entries("special", 1);
*/


// Set timezone globals for the site
$timezone_prefs = get_timezone($_SESSION['prefsTimeZone']);
date_default_timezone_set($timezone_prefs);
$tz = date_default_timezone_get();

// Check for Daylight Savings Time (DST) - if true, add one hour to the offset
$bool = date("I"); if ($bool == 1) $timezone_offset = number_format(($_SESSION['prefsTimeZone'] + 1.000),0); 
else $timezone_offset = number_format($_SESSION['prefsTimeZone'],0);

$ua_array = explode(' ', $_SERVER['HTTP_USER_AGENT']);
$msie_key = array_search('MSIE', $ua_array);
if($msie_key !== false) { // you found MSIE browser
    $msie_version_key = $msie_key + 1;
    $msie_version = intval($ua_array[$msie_version_key]);
    if ($msie_version <= 7) {
        $ua = "unsupported";
    }
}

if (NHC) {
	if (($registration_open == 1) && (isset($_SESSION['loginUsername']))) {
		// compare region prefix to the actual region that the user is registered to
		// if they do not match, destroy the session - saves confusion and cheating
		
		if ($_SESSION['userLevel'] == 2) {
		$query_check_region = sprintf("SELECT email,regionPrefix FROM nhcentrant WHERE email='%s'", $_SESSION['loginUsername']);
		$check_region = mysql_query($query_check_region, $brewing) or die(mysql_error());
		$row_check_region = mysql_fetch_assoc($check_region);
		
		if (($row_check_region['regionPrefix'] != $prefix) && ($_SESSION['loginUsername'] != "geoff@zkdigital.com") && ($_SESSION['loginUsername'] != "janis@brewersassociation.org")) session_destroy();
		}
		
	}
	// ONLY for NHC application
	// Check to see if SSL is in place and redirect to non SSL instance if not on pay screens
	if ($section != "pay") {
		$https = ((!empty($_SERVER['HTTPS'])) && ($_SERVER['HTTPS'] != 'off')) ? true : false;
		if ($https)  {
			$location = "http://www.brewingcompetition.com".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
			header("Location: $location");
			exit;
		}
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['contestName']; ?> Organized By <?php echo $_SESSION['contestHost']." &gt; ".$header_output; ?></title>
<link href="<?php echo $base_url; ?>css/<?php echo $_SESSION['prefsTheme']; ?>.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?php echo $base_url; ?>css/jquery-ui-1.8.18.custom.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $base_url; ?>css/sorting.css" type="text/css" />
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jquery.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jquery.ui.widget.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jquery.ui.tabs.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jquery.ui.position.min.js"></script>
<link rel="stylesheet" href="<?php echo $base_url; ?>css/jquery.ui.timepicker.css?v=0.3.0" type="text/css" />
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jquery.ui.timepicker.js?v=0.3.0"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/fancybox/jquery.easing-1.3.pack.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/fancybox/jquery.mousewheel-3.0.6.pack.js"></script>
<link rel="stylesheet" href="<?php echo $base_url; ?>js_includes/fancybox/jquery.fancybox.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/fancybox/jquery.fancybox.pack.js"></script>
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
				<?php if ($modal_window == "false") { ?>
				'afterClose': 		function() { parent.location.reload(true); }
				<?php } ?>
			});

		});
	</script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/delete.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jump_menu.js" ></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/smoothscroll.js" ></script>
<?php if (isset($_SESSION['loginUsername'])) { ?>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/menu.js"></script>
<?php } 
if ($section == "admin") { ?>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/tinymce.init.js"></script>
<?php } 
if (($section == "admin") || ($section == "brew") || ($section == "brewer") || ($section == "user")  || ($section == "register") || ($section == "contact")) include(INCLUDES.'form_check.inc.php'); 
?>

<script type="text/javascript">
var _gaq = _gaq || [];
  //_gaq.push(['_setAccount', '<?php // echo $google_analytics; ?>']);
  _gaq.push(['_setAccount', 'UA-7085721-23']);
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>

</head>
<body>
<a name="top"></a>
<div id="container">
<div id="navigation">
	<div id="navigation-inner"><?php include (SECTIONS.'nav.sec.php'); ?></div>
</div>
<div id="content">
	<div id="content-inner">  
  <?php
  //print_r($_SESSION);
  // echo $tz; echo "<br>".$timezone_offset; echo "<br>".$_SESSION['prefsTimeZone']; echo "<br>".date('T');
  /*
  echo "User: ".$query_user."<br>";
  echo "Brewer: ".$query_brewer."<br>";
  echo "Name: ".$query_name."<br>";
  echo "Prefs: ".$query_prefs."<br>";
  echo "Comp Info: ".$row_contest_info."<br>";
  echo "Tables: ".$query_tables."<br>";
  */
  
  
  if ($section != "admin") { ?>
	<div id="header">	
		<div id="header-inner"><h1><?php echo $header_output; ?></h1></div>
  	</div>
  <?php  } 
 // Check if registration open date has passed
  if (($registration_open == "0") && ($ua != "unsupported")) { 
  	if ($section != "admin") {
  	?>
    <?php if (!isset($_SESSION['loginUsername'])) { ?><div class="closed">Entry registration will open <?php echo $reg_open; ?>.</div><?php } ?>
    <?php if ((!isset($_SESSION['loginUsername'])) && ($judge_window_open == "0")) { ?><div class="info">Judge/steward registration will open <?php echo $judge_open; ?>.</div><?php } ?>
    <?php if ((!isset($_SESSION['loginUsername'])) && ($section != "register") && ($judge_window_open == "1")) { ?><div class="info">If you are willing to be a judge or steward, please <a href="<?php echo build_public_url("register","judge","default",$sef,$base_url); ?>">register here</a>.</div><?php } ?> 
	<?php }
	if ($section == "default") 		include (SECTIONS.'default.sec.php');
	if ($section == "login")		include (SECTIONS.'login.sec.php');
	if ($section == "rules") 		include (SECTIONS.'rules.sec.php');
	if ($section == "entry") 		include (SECTIONS.'entry_info.sec.php');
	if ($section == "sponsors") 	include (SECTIONS.'sponsors.sec.php');
	if ($section == "past_winners") include (SECTIONS.'past_winners.sec.php');
	if ($section == "contact") 		include (SECTIONS.'contact.sec.php');
	if ($section == "volunteers")	include (SECTIONS.'volunteers.sec.php');
	if ($section == "register")		include (SECTIONS.'register.sec.php');
	if ($section == "brew") 		include (SECTIONS.'brew.sec.php');
	
	if (isset($_SESSION['loginUsername'])) {
		if ($section == "list") 	include (SECTIONS.'list.sec.php');
		if ($section == "user") 	include (SECTIONS.'user.sec.php');
		if ($section == "pay") {
				if (NHC) include (SECTIONS.'nhc_pay.sec.php');
				else include (SECTIONS.'pay.sec.php');
			}
		if ($section == "brewer") 	include (SECTIONS.'brewer.sec.php');
			
		if ($_SESSION['userLevel'] <= "1") {
			if ($section == "admin")	include (ADMIN.'default.admin.php');
			if ($section == "judge") 	include (SECTIONS.'judge.sec.php');
			if ($section == "beerxml")	include (SECTIONS.'beerxml.sec.php');
			}
		}
  }
  
  // Check if registration close date has passed. If so, display "registration end" message.
  if (($registration_open == "2") && ($ua != "unsupported")) {
	if ((($section != "admin") || ($_SESSION['userLevel'] > "1")) && (judging_date_return() > 0)) { ?>
    <div class="closed">Entry registration closed <?php echo $reg_closed; ?>.</div>
    <?php if ((!isset($_SESSION['loginUsername'])) && ($section != "register") && ($judge_window_open == "1")) { ?><div class="info">If you are willing to be a judge or steward, please <a href="<?php echo build_public_url("register","judge","default",$sef,$base_url); ?>">register here</a>.</div><?php } ?>
	<?php }  
	if ($section == "default") 		include (SECTIONS.'default.sec.php');
	if ($section == "login")		include (SECTIONS.'login.sec.php');
	if ($section == "rules") 		include (SECTIONS.'rules.sec.php');
	if ($section == "entry") 		include (SECTIONS.'entry_info.sec.php');
	if ($section == "sponsors") 	include (SECTIONS.'sponsors.sec.php'); 
	if ($section == "past_winners") include (SECTIONS.'past_winners.sec.php');
	if ($section == "contact") 		include (SECTIONS.'contact.sec.php');
	if ($section == "volunteers")	include (SECTIONS.'volunteers.sec.php');
	if ($section == "register") 	include (SECTIONS.'register.sec.php');
	if ($section == "brewer") 		include (SECTIONS.'brewer.sec.php');
	if (isset($_SESSION['loginUsername'])) {
		//echo $registration_open;
		if ($section == "list") 	include (SECTIONS.'list.sec.php');
		if ($section == "pay") {
				if (NHC) include (SECTIONS.'nhc_pay.sec.php');
				else include (SECTIONS.'pay.sec.php');
			}
		
		if ($section == "user") 	include (SECTIONS.'user.sec.php');
		if ($entry_window_open < 2) {
			if ($section == "brew") 	include (SECTIONS.'brew.sec.php');	
		}
		if ($_SESSION['userLevel'] <= "1") {
			if ($section == "admin")	include (ADMIN.'default.admin.php');
			if ($section == "judge") 	include (SECTIONS.'judge.sec.php');
			if ($section == "beerxml")	include (SECTIONS.'beerxml.sec.php');
			}
		}
  }
  
  // If registration is currently open
  if (($registration_open == "1") && ($ua != "unsupported")) {
  	//if ((NHC) && ($section == "default")) echo "<div class='error'>".$totalRows_entry_count." of ".$row_limits['prefsEntryLimit']." entries have been logged for this region.</div>";
  	if (open_limit($totalRows_entry_count,$row_limits['prefsEntryLimit'],$registration_open)) { 
		if ($section != "admin") { 
			echo "<div class='closed'>The limit of ".readable_number($row_limits['prefsEntryLimit'])." (".$row_limits['prefsEntryLimit'].") entries has been reached. No further entries will be accepted."; if (!isset($_SESSION['loginUsername'])) echo " However, judges and stewards may still <a href='".build_public_url("register","judge","default",$sef,$base_url)."'>register here</a>."; echo "</div>"; 
		}
	}
	if ($section == "register") 	include (SECTIONS.'register.sec.php');
	if ($section == "login")		include (SECTIONS.'login.sec.php');
	if ($section == "rules") 		include (SECTIONS.'rules.sec.php');
	if ($section == "entry") 		include (SECTIONS.'entry_info.sec.php');
	if ($section == "default") 		include (SECTIONS.'default.sec.php');
	if ($section == "sponsors") 	include (SECTIONS.'sponsors.sec.php');
	if ($section == "past_winners") include (SECTIONS.'past_winners.sec.php');
	if ($section == "contact") 		include (SECTIONS.'contact.sec.php');
	if ($section == "volunteers")	include (SECTIONS.'volunteers.sec.php');
	if (isset($_SESSION['loginUsername'])) {
		if ($_SESSION['userLevel'] <= "1") { if ($section == "admin")	include (ADMIN.'default.admin.php'); }
		if ($section == "brewer") 	include (SECTIONS.'brewer.sec.php');
		if ($section == "brew") 	include (SECTIONS.'brew.sec.php');
		if ($section == "pay") {
				if (NHC) include (SECTIONS.'nhc_pay.sec.php');
				else include (SECTIONS.'pay.sec.php');
			}
		if ($section == "list") 	include (SECTIONS.'list.sec.php');
		if ($section == "judge") 	include (SECTIONS.'judge.sec.php');
		if ($section == "user") 	include (SECTIONS.'user.sec.php');
		if ($section == "beerxml")	include (SECTIONS.'beerxml.sec.php');
	}
  } // End registration date check.

  if ($ua == "unsupported") { 
  	echo "<div class='error'>Unsupported browser.</div><p>Your version of Internet Explorer, as detected by our scripting, is not supported by "; if (NHC) 	echo "the NHC online registration system."; else echo "BCOE&amp;M.</p>"; echo "<p>Please <a href='http://windows.microsoft.com/en-US/internet-explorer/download-ie'>download and install the latest version</a> for your operating system. Alternatively, you can use the latest version of another browser (<a href='http://www.google.com/chrome'>Chrome</a>, <a href='http://www.mozilla.org/en-US/firefox/new/'>Firefox</a>, <a href='http://www.apple.com/safari/'>Safari</a>, etc.).</p>"; 
  	echo "<p>The information provided by your browser and used by our script is: ".$_SERVER['HTTP_USER_AGENT']."</p>";
  }
  
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