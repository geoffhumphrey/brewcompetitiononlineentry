<?php
require('paths.php');
require(DB.'common.db.php');
require(INCLUDES.'functions.inc.php');
require(INCLUDES.'authentication_nav.inc.php');  session_start(); 
require(INCLUDES.'url_variables.inc.php');
require(INCLUDES.'db_tables.inc.php'); 
require(INCLUDES.'constants.inc.php');
$section = "500";
?>
<html>
<head>
<title><?php echo $row_contest_info['contestName']; ?> Organized By <?php echo $row_contest_info['contestHost']." &gt; Error 500: Internal Server Error"; ?></title>
<link href="<?php echo $base_url; ?>/css/<?php echo $row_prefs['prefsTheme']; ?>.css" rel="stylesheet" type="text/css" />
<?php if ($action == "default") { ?>
<meta http-equiv="refresh" content="0;URL=<?php echo $base_url; ?>/500.php?action=error" />
<?php } ?>
</head>
<body>
<?php if ($action == "error") { ?>
<div id="container">
<div id="navigation">
	<div id="navigation-inner"><?php include (SECTIONS.'nav.sec.php'); ?></div>
</div>
	<div id="content">
    	<div id="content-inner">
			<div id="header">	
				<div id="header-inner"><h1>500 Error</h1></div>
            </div>
			<p>Oh, hey! Apparently, there was some sort of error...</p>
			<p>Please use the main navigation above to get where you want to go.</p>
            <p>Cheers!<br>The <?php echo $row_contest_info['contestName']; ?> Site Server</p>
        </div>
    </div>
</div>
<div id="footer">
	<div id="footer-inner"><?php include (SECTIONS.'footer.sec.php'); ?></div>
</div>
<?php } ?>
</body>
</html>