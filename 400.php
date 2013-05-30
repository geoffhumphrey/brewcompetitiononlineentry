<?php
require('paths.php');
require(DB.'common.db.php');
require(INCLUDES.'functions.inc.php');
require(INCLUDES.'authentication_nav.inc.php');  session_start(); 
require(INCLUDES.'url_variables.inc.php');
require(INCLUDES.'db_tables.inc.php'); 
require(INCLUDES.'constants.inc.php');
$section = "400";
?>
<html>
<head>
<title><?php echo $_SESSION['contestName']; ?> Organized By <?php echo $_SESSION['contestHost']." &gt; Error 400: Bad Request"; ?></title>
<link href="<?php echo $base_url; ?>/css/<?php echo $_SESSION['prefsTheme']; ?>.css" rel="stylesheet" type="text/css" />

<body>
<div id="container">
<div id="navigation">
	<div id="navigation-inner"><?php include (SECTIONS.'nav.sec.php'); ?></div>
</div>
	<div id="content">
    	<div id="content-inner">
			<div id="header">	
				<div id="header-inner"><h1>400 Error</h1></div>
            </div>
			<p>Unfortunately, an invalid request has been received.</p>
			<p>Please use the main navigation above to get where you want to go.</p>
            <p>Cheers!<br>
          The <?php echo $_SESSION['contestName']; ?> Site Server</p>
        </div>
    </div>
</div>
<div id="footer">
	<div id="footer-inner"><?php include (SECTIONS.'footer.sec.php'); ?></div>
</div>
</body>
</html>
