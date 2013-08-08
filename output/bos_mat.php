<?php 
/**
 * Module:      bos_mat.php 
 * Description: Template for custom reports.
 * 
 */

require('../paths.php');
require(CONFIG.'bootstrap.php');
if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {
if (NHC) $base_url = "../";

$special_beer = array("6D","16E","17F","20A","21A","21B","22B","22C","23A");
$special_mead = array("24A","24B","24C","25A","25B","25C","26A","26B","26C");
$special_cider = array("27B","27C","27E","28A","28B","28C","28D");
/*
$section = "brew";
require(DB.'styles.db.php');

// Custom styles where special ingredients are required
if ($totalRows_styles2 > 0) {
	do { 
		$style_special = ltrim($row_styles2['brewStyleGroup'],"0");
		$special_ingredients[] .= $style_special.$row_styles2['brewStyleNum']; 
	}  while ($row_styles2 = mysql_fetch_assoc($styles2));
}
*/

// Beer Styles
$query_scores = sprintf("SELECT a.scorePlace, b.brewJudgingNumber, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewInfo FROM %s a, %s b, %s c WHERE a.eid = b.id AND c.uid = b.brewBrewerID AND scorePlace='1' AND brewCategory <=23", $judging_scores_db_table, $brewing_db_table, $brewer_db_table);
$query_scores .= " ORDER BY b.brewCategorySort ASC";
$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
$row_scores = mysql_fetch_assoc($scores);
$totalRows_scores = mysql_num_rows($scores);

// Mead Styles
$query_scores_mead = sprintf("SELECT a.scorePlace, b.brewJudgingNumber, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewInfo, b.brewMead1, b.brewMead2, b.brewMead3 FROM %s a, %s b, %s c WHERE a.eid = b.id AND c.uid = b.brewBrewerID AND scorePlace='1' AND (brewCategory = 24 OR brewCategory = 25 OR brewCategory = 26)", $judging_scores_db_table, $brewing_db_table, $brewer_db_table);
$query_scores_mead .= " ORDER BY b.brewCategorySort ASC";
$scores_mead = mysql_query($query_scores_mead, $brewing) or die(mysql_error());
$row_scores_mead = mysql_fetch_assoc($scores_mead);
$totalRows_scores_mead = mysql_num_rows($scores_mead);


// Cider Styles

$query_scores_cider = sprintf("SELECT a.scorePlace, b.brewJudgingNumber, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewInfo, b.brewMead1, b.brewMead2, b.brewMead3 FROM %s a, %s b, %s c WHERE a.eid = b.id AND c.uid = b.brewBrewerID AND scorePlace='1' AND (brewCategory = 27 OR brewCategory = 28)", $judging_scores_db_table, $brewing_db_table, $brewer_db_table);
$query_scores_cider .= " ORDER BY b.brewCategorySort ASC";
$scores_cider = mysql_query($query_scores_cider, $brewing) or die(mysql_error());
$row_scores_cider = mysql_fetch_assoc($scores_cider);
$totalRows_scores_cider = mysql_num_rows($scores_cider);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['contestName']; ?> organized by <?php echo $_SESSION['contestHost']; ?></title>
<link href="<?php echo $base_url; ?>css/print.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.BOS-mat {
	border-collapse:collapse;
	margin: 0 0 10px 0;
}

.BOS-mat td {
	margin: 10px;
	padding: 10px;
	height: 300px;
	width: 300px;
	border: 4px solid #000;
	background-image: url(<?php echo $base_url; ?>mages/nhc_logo.png);
	background-repeat: no-repeat;
	background-position: center;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 1.3em;
}

.BOS-mat h1, .BOS-mat h2, .BOS-mat h3 {
	font-family: Arial, Helvetica, sans-serif;
}
-->
</style>
</head>
<body>

<?php
if ($totalRows_scores > 0) {
	echo "<table class='BOS-mat'>";
	$endRow = 0;
	$columns = 3;  // number of columns
	$hloopRow1 = 0; // first row flag
	do {
		$style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];
    	if (($endRow == 0) && ($hloopRow1++ != 0)) echo "<tr>";
    ?>
    <td>
    <h2><?php echo $row_scores['brewCategory'].": ".style_convert($row_scores['brewCategorySort'],1); ?></h2>
    <h3><?php echo $style.": ".$row_scores['brewStyle']; ?></h3>
    <p>#<?php echo $row_scores['brewJudgingNumber']; ?></p>
    <?php if (in_array($style,$special_beer)) { ?>
    <p><strong><?php echo $row_scores['brewInfo']; ?></strong></p>
    <?php } ?>
    </td>
	<?php  $endRow++;
	if ($endRow >= $columns) {
		echo "</tr>";
		if ($hloopRow1 % 2 == 0) echo "</table>\n<div style='page-break-after:always;'></div>\n<table class='BOS-mat'>";
 		$endRow = 0;
  		}
	} while ($row_scores = mysql_fetch_assoc($scores));
	if ($endRow != 0) {
		while ($endRow < $columns) {
    	echo("<td>&nbsp;</td>");
    	$endRow++;
	}
	echo("</tr></table>");
	}
}
	?>
    
<div style='page-break-after:always;'></div>
<table class='BOS-mat'>
<?php
if ($totalRows_scores_mead > 0) { 
	$endRow_mead = 0;
	$columns_mead = 3;  // number of columns
	$hloopRow1_mead = 0; // first row flag
	do {
		$style_mead = $row_scores_mead['brewCategory'].$row_scores_mead['brewSubCategory'];
    	if (($endRow_mead == 0) && ($hloopRow1_mead++ != 0)) echo "<tr>";
    ?>
    <td>
    <h2><?php echo $row_scores_mead['brewCategory'].": ".style_convert($row_scores_mead['brewCategorySort'],1); ?></h2>
    <h3><?php echo $style_mead.": ".$row_scores_mead['brewStyle']; ?></h3>
    <p>#<?php echo $row_scores_mead['brewJudgingNumber']; ?></p>
    <?php 
	$text = "";
	if ($row_scores_mead['brewMead1'] != "") $text .= sprintf("%s",$row_scores_mead['brewMead1']);
	if ($row_scores_mead['brewMead2'] != "") $text .= sprintf(" / %s",$row_scores_mead['brewMead2']);
	if ($row_scores_mead['brewMead3'] != "") $text .= sprintf(" / %s",$row_scores_mead['brewMead3']);
	?>
    <p><?php echo $text; ?></p>
    <?php if (in_array($style_mead,$special_mead)) { ?>
    <p><strong><?php echo $row_scores_mead['brewInfo']; ?></strong></p>
    <?php } ?>
    </td>
	<?php  $endRow_mead++;
	if ($endRow_mead >= $columns_mead) {
		echo "</tr>";
		if (($totalRows_scores_mead > 6) && ($hloopRow1_mead % 2 == 0)) echo "</table>\n<div style='page-break-after:always;'></div>\n<table class='BOS-mat'>";
 		$endRow_mead = 0;
  		}
	} while ($row_scores_mead = mysql_fetch_assoc($scores_mead));
	if ($endRow_mead != 0) {
		while ($endRow_mead < $columns_mead) {
    	echo("<td>&nbsp;</td>");
    	$endRow_mead++;
	}
	echo("</tr>");
	}
}
?>
</table>

<div style='page-break-after:always;'></div>
<table class='BOS-mat'>
<?php
if ($totalRows_scores_cider > 0) { 
	$endRow_cider = 0;
	$columns_cider = 3;  // number of columns
	$hloopRow1_cider = 0; // first row flag
	do {
		$style_cider = $row_scores_cider['brewCategory'].$row_scores_cider['brewSubCategory'];
    	if (($endRow_cider == 0) && ($hloopRow1_cider++ != 0)) echo "<tr>";
    ?>
    <td>
    <h2><?php echo $row_scores_cider['brewCategory'].": ".style_convert($row_scores_cider['brewCategorySort'],1); ?></h2>
    <h3><?php echo $style_cider.": ".$row_scores_cider['brewStyle']; ?></h3>
    <p> #<?php echo $row_scores_cider['brewJudgingNumber']; ?></p>
    <?php 
	$text = "";
	if ($row_scores_cider['brewMead1'] != "") $text .= sprintf("%s",$row_scores_cider['brewMead1']);
	if ($row_scores_cider['brewMead2'] != "") $text .= sprintf(" / %s",$row_scores_cider['brewMead2']);
	if ($row_scores_cider['brewMead3'] != "") $text .= sprintf(" / %s",$row_scores_cider['brewMead3']);
	?>
    <p><?php echo $text; ?></p>
	<?php if (in_array($style_cider,$special_cider)) { ?>
    <p><strong><?php echo $row_scores_cider['brewInfo']; ?></strong></p>
    <?php } ?>
    </td>
	<?php  $endRow_cider++;
	if ($endRow_cider >= $columns_cider) {
		echo "</tr>";
		if (($totalRows_scores_cider > 6) && ($hloopRow1_cider % 2 == 0)) echo "</table>\n<div style='page-break-after:always;'></div>\n<table class='BOS-mat'>";
 		$endRow_cider = 0;
  		}
	} while ($row_scores_cider = mysql_fetch_assoc($scores_cider));
	if ($endRow_cider != 0) {
		while ($endRow_cider < $columns_cider) {
    	echo("<td>&nbsp;</td>");
    	$endRow_cider++;
	}
	echo("</tr>");
	}
}
?> 
</table>  
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
<?php } else echo "<p>Not available.</p>"; ?>