<?php
require('paths.php');
require(DB.'common.db.php');
require(INCLUDES.'functions.inc.php');
require(INCLUDES.'authentication_nav.inc.php');  session_start(); 
require(INCLUDES.'url_variables.inc.php');
require(INCLUDES.'db_tables.inc.php'); 
require(INCLUDES.'constants.inc.php');
$section = "403";
?>
<html>
<head>
<title><?php echo $row_contest_info['contestName']; ?> Organized By <?php echo $row_contest_info['contestHost']." &gt; Error 403: Permission Denied"; ?></title>
<link href="<?php echo $base_url; ?>css/<?php echo $row_prefs['prefsTheme']; ?>.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="container">
<div id="navigation">
	<div id="navigation-inner"><?php include (SECTIONS.'nav.sec.php'); ?></div>
</div>
	<div id="content">
    	<div id="content-inner">
			<div id="header">	
				<div id="header-inner"><h1>403 Error</h1></div>
            </div>
			<p>Denied! You do not have permission for this request: <?php echo $_SERVER['REQUEST_URI']; ?>.</p>
            <p>Please use the main navigation above to get where you want to go.</p>
            <p>Cheers!<br>The <?php echo $row_contest_info['contestName']; ?> Site Server</p>
        </div>
    </div>
</div>
<div id="footer">
	<div id="footer-inner"><?php include (SECTIONS.'footer.sec.php'); ?></div>
</div>
</body>
</html>