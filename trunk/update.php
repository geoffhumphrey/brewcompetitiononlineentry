<?php 
require('paths.php');
require(INCLUDES.'functions.inc.php');
//if (!check_judging_numbers()) header("Location: includes/process.inc.php?action=generate_judging_numbers&go=hidden");
require(INCLUDES.'authentication_nav.inc.php');  session_start(); 
require(INCLUDES.'url_variables.inc.php');
require(DB.'common.db.php');
require(DB.'brewer.db.php');
require(INCLUDES.'version.inc.php');
require(INCLUDES.'headers.inc.php');
$current_version = "1.2.0.4";
$section = "update";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $row_contest_info['contestName']; ?> Update</title>
<link href="css/<?php echo $row_prefs['prefsTheme']; ?>.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="container">
<div id="navigation">
	<div id="navigation-inner"><?php include (SECTIONS.'nav.sec.php'); ?></div>
</div>
<div id="content">
 	 <div id="content-inner">
     <div id="header">	
		<div id="header-inner"><h1>BCOE&amp;M Update Script</h1></div>
  	</div>
<?php if ((isset($_SESSION["loginUsername"])) && ($row_user['userLevel'] == "1")) { ?>
	<?php if (($action == "default") && ($msg == "default")) { ?>
    <p>This function will update your BCOE&amp;M from its current version (<?php echo $version; ?>) to the latest version, 
	<p><span class="icon"><img src="images/cog.png" /></span><a href="update.php?action=update">Begin The Update Script</a></p>
    <?php } ?>
    <?php if ($action == "update") { 
		// Check the installed version against the current version
		
		if ($version != $current_version) {
		// Perform updates to the db based upon the current version
		
		
		} else echo 
	}
	?>
<?php } else echo "<div class='error'>Only admins are able to access the update script.</div>"; ?>
  </div>
</div>
</div>
<div id="footer">
	<div id="footer-inner"><?php include (SECTIONS.'footer.sec.php'); ?></div>
</div>
</body>
</html>