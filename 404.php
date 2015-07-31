<?php
require('paths.php');
require(CONFIG.'bootstrap.php');
$section = "404";
?>
<html>
<head>
<title><?php echo $_SESSION['contestName']; ?> Organized By <?php echo $_SESSION['contestHost']." &gt; Error 404: File Not Found"; ?></title>
<link href="<?php echo $base_url; ?>/css/<?php echo $_SESSION['prefsTheme']; ?>.css" rel="stylesheet" type="text/css" />
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
            <div class="error">Sorry, the page you were looking for was not found. Don't worry, we still want you around!</div>
			<div class="info">If you are a top-level admin and are seeing this error page when accessing the a public page such as the "<a href="<?php echo $base_url."rules"; ?>">Rules</a>" page, your server may not be able to accommodate search engine safe (SEF) URLs. If you are a top-level administrator of the site, <a href="<?php echo $base_url."index.php?section=login"; ?>">log in</a> now and adjust your site preferences.</div>
			<p>Please use the main navigation above to get where you want to go.</p>
            <p>If the links above aren't working, please <a href="<?php echo $base_url."index.php?section=contact"; ?>">contact a site representative</a>!</p>
            <p>Cheers!<br>&ndash; The <?php echo $_SESSION['contestName']; ?> Site Server</p>
        </div>
    </div>
</div>
<div id="footer">
	<div id="footer-inner"><?php include (SECTIONS.'footer.sec.php'); ?></div>
</div>
</body>
</html>