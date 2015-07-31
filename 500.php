<?php
require('paths.php');
require(CONFIG.'bootstrap.php');
$section = "500";
?>
<html>
<head>
<title><?php echo $_SESSION['contestName']; ?> Organized By <?php echo $_SESSION['contestHost']." &gt; Error 500: Internal Server Error"; ?></title>
<link href="<?php echo $base_url; ?>/css/<?php echo $_SESSION['prefsTheme']; ?>.css" rel="stylesheet" type="text/css" />
<?php if ($action == "default") { ?>
<meta http-equiv="refresh" content="0;URL=<?php echo $base_url; ?>500.php?action=error" />
<?php } ?>

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
			<div class="error">Oh, hey! Apparently, there was some sort of error...</div>
			<p>Please use the main navigation above to get where you want to go.</p>
            <p>Cheers!<br>The <?php echo $_SESSION['contestName']; ?> Site Server</p>
        </div>
    </div>
</div>
<div id="footer">
	<div id="footer-inner"><?php include (SECTIONS.'footer.sec.php'); ?></div>
</div>
<?php } ?>
</body>
</html>