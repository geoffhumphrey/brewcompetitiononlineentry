<?php
require('paths.php');
require(INCLUDES.'functions.inc.php');
require(INCLUDES.'authentication_nav.inc.php');  session_start(); 
require(DB.'common.db.php');
?>
<html>
<head>
<title><?php echo $row_contest_info['contestName']; ?> Organized By <?php echo $row_contest_info['contestHost']." &gt; Error 404: File Not Found"; ?></title>
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
				<div id="header-inner"><h1>404 Error</h1></div>
            </div>
			<p>Sorry, the page you were looking for was not found. Don't worry, we still want you around!</p>
			<p>Please use the main navigation above to get where you want to go.</p>
            <p>Cheers!<br>
          The <?php echo $row_contest_info['contestName']; ?> Site Server</p>
        </div>
    </div>
</div>
<div id="footer">
	<div id="footer-inner"><?php include (SECTIONS.'footer.sec.php'); ?></div>
</div>
</body>
</html>