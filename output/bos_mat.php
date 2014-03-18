<?php 
/**
 * Module:      bos_mat.php 
 * Description: Template for custom reports.
 * 
 */

require('../paths.php');
require(CONFIG.'bootstrap.php');
$go = "output";
require(DB.'admin_common.db.php');
require(LIB.'output.lib.php');
if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {
if (NHC) $base_url = "../";

$special_beer = array("6D","16E","17F","20A","21A","21B","22B","22C","23A");
$special_mead = array("24A","24B","24C","25A","25B","25C","26A","26B","26C");
$special_cider = array("27B","27C","27E","28A","28B","28C","28D");

if ($view == "default") {
	do { $a[] = $row_style_types['id']; } while ($row_style_types = mysql_fetch_assoc($style_types));
	sort($a);
}

else $a = $view;

$output = "";

foreach ($a as $type) {
	$style_type_info = style_type_info($type);
	$style_type_info = explode("^",$style_type_info);
	
	if ($style_type_info[0] == "Y") { 
	
		include(DB.'output_bos_mat.db.php');
		//$output .= $query_scores;
		
		$endRow = 0;
		$columns = 3;  // number of columns
		$hloopRow1 = 0; // first row flag
		
		$output .= '<table class="BOS-mat">';
		do {
			
			$style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];
    		if (($endRow == 0) && ($hloopRow1++ != 0)) $output .= "<tr>";
			$output .= '<td>';
			$output .= '<h2>'.$row_scores['brewCategory'].': '.style_convert($row_scores['brewCategorySort'],1).'</h2>';
			$output .= '<h3>'.$style.': '.$row_scores['brewStyle'].'</h3>';
			if ($filter == "entry") $output .= '<p>#'.$row_scores['id'].'</p>';
			else $output .= '<p>#'.readable_judging_number($row_scores['brewCategorySort'],$row_scores['brewJudgingNumber']).'</p>';
			$output .= '<p><strong>'.$row_scores['brewInfo'].'</strong></p>';
			if ($type == 2) $output .= '<p>'.$row_scores['brewMead1'].', '.$row_scores['brewMead2'].'</p>';
			if ($type == 3) $output .= '<p>'.$row_scores['brewMead1'].', '.$row_scores['brewMead2'].', '.$row_scores['brewMead3'].'</p>';
			$output .= '</td>';
			
			$endRow++;
			if ($endRow >= $columns) {
				$output .= '</tr>';
				if ($hloopRow1 % 2 == 0) $output .= '</table><div style="page-break-after:always;"></div><table class="BOS-mat">';
				$endRow = 0;
  			}
			
		} while ($row_scores = mysql_fetch_assoc($scores));
		
		if ($endRow != 0) {
			while ($endRow < $columns) {
			$output .= '<td>&nbsp;</td>';
			$endRow++;
		}
		
		$output .= '</table>';
		
		}
		
	} // end if ($style_type_info[0] == "Y")
	
	$output .= '<div style="page-break-after:always;"></div>';
} // end foreach
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
	<?php if (NHC) { ?>
	background-image: url(<?php echo $base_url; ?>images/nhc_logo.png);
	<?php } ?>
	background-repeat: no-repeat;
	background-position: center;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 1.3em;
}

.BOS-mat h1, .BOS-mat h2, .BOS-mat h3 {
	font-family: Arial, Helvetica, sans-serif;
}

.BOS-mat p {
	font-size: 12px;	
}
-->
</style>
</head>
<body>
<?php 
if (empty($output)) {
	echo '<h1>';
	echo 'No BOS entries are present';
	if ($view != "default") echo ' for this style';
	echo '.';
	echo '</h1>';
}
else echo $output; 
?>
</body>
</html>
<script type="text/javascript">
function selfPrint(){
    self.focus();
    self.print();
}
setTimeout('selfPrint()',2000);
html.push('');
</script>
<?php } else echo "<p>Not available.</p>"; ?>