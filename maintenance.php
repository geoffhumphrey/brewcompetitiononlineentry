<?php 
/**
 * Module:      maintenance.php 
 * Description: This page displays if the site is in maintenance mode.
 * 
 */
require('paths.php');
require(DB.'common.db.php');
if (NHC) $base_url = "";
else $base_url = "http://".$_SERVER['SERVER_NAME'].$sub_directory."/";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['contestName']; ?> Organized By <?php echo $_SESSION['contestHost']; ?></title>
<link href="<?php echo $base_url; ?>css/<?php echo $_SESSION['prefsTheme']; ?>.css" rel="stylesheet" type="text/css" />
</head>
<body>
<a name="top"></a>
<div id="container">
<div id="navigation">
	<div id="navigation-inner">Offline Mode</div>
</div>
<div id="content">
	<div id="content-inner">  
	<div id="header">	
		<div id="header-inner"><h1>Maintenance</h1></div>
  	</div>
  	<p>The <?php echo $_SESSION['contestName']; ?> site administrator has taken the site offline. It is currently undergoing maintenance.</p>
    <p>Please <a href="<?php echo $base_url; ?>">check back</a> shortly.</p>
  </div>
</div>
</div>
<a name="bottom"></a>
<div id="footer">
	<div id="footer-inner"><?php include (SECTIONS.'footer.sec.php'); ?></div>
</div>
</body>
</html>