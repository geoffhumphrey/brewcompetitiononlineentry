<?php 

session_start(); 
require('../paths.php'); 
require(INCLUDES.'functions.inc.php');
require(INCLUDES.'url_variables.inc.php');
require(INCLUDES.'db_tables.inc.php');
require(DB.'common.db.php');
require(DB.'admin_common.db.php');
require(INCLUDES.'version.inc.php');
require(INCLUDES.'headers.inc.php');
require(INCLUDES.'constants.inc.php');
if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {
if (NHC) $base_url = "../";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Brew Competition Online Entry and Management - brewcompetition.com</title>
<link href="<?php echo $base_url; ?>css/print.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jquery.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jquery.dataTables.js"></script>
</head>
<body>
<div id="content">
	<div id="content-inner">
    <div id="header">	
		<div id="header-inner">
			<h1><?php echo $_SESSION['contestName']." Results"; ?></h1>
        </div>
	</div>
<?php if (($go == "judging_scores") && ($action == "print"))  {
	if ($_SESSION['prefsWinnerMethod'] == "1") include (SECTIONS.'winners_category.sec.php'); 
	elseif ($_SESSION['prefsWinnerMethod'] == "2") include (SECTIONS.'winners_subcategory.sec.php'); 
	else include (SECTIONS.'winners.sec.php');
} 
if (($go == "judging_scores_bos") && ($action == "print")) include (SECTIONS.'bos.sec.php'); 
?>
</div>
</div>
<script type="text/javascript">
function selfPrint(){
    self.focus();
    self.print();
}
setTimeout('selfPrint()',2000);
html.push('');
</script> 
</body>
</html>
<?php } 
else echo "<p>Not available.</p>";
?>