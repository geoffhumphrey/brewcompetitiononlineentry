<?php 
/**
 * Module:      bos_mat.php 
 * Description: Template for custom reports.
 * 
 */

$go = "output";
require(DB.'admin_common.db.php');
require(LIB.'output.lib.php');

$special_beer = array("6D","16E","17F","20A","21A","21B","22B","22C","23A");
$special_mead = array("24A","24B","24C","25A","25B","25C","26A","26B","26C");
$special_cider = array("27B","27C","27E","28A","28B","28C","28D");

if ($view == "default") {
	do { $a[] = $row_style_types['id']; } while ($row_style_types = mysqli_fetch_assoc($style_types));
	sort($a);
}

else $a = $view;

$output = "";

foreach ($a as $type) {
	$style_type_info = style_type_info($type);
	$style_type_info = explode("^",$style_type_info);
	
	if ($style_type_info[0] == "Y") { 
	
		include (DB.'output_bos_mat.db.php');
		//$output .= $query_scores;
		
		$endRow = 0;
		$columns = 3;  // number of columns
		$hloopRow1 = 0; // first row flag
		
		$output .= '<table class="BOS-mat">';
		do {
			if ($row_scores['brewJudgingNumber'] > 0) {
				if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) $style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];
				else {
					if (is_numeric($row_scores['brewSubCategory'])) {
					$style = $_SESSION['styles']['data'][$row_scores['brewSubCategory'] - 1]['category']['name'];
					if ($style == "Hybrid/mixed Beer") $style = "Hybrid/Mixed Beer"; 
					elseif ($style == "European-germanic Lager") $style = "European-Germanic Lager";
					else $style = ucwords($style);	
					}
					else $style = "Custom Style";
				}
				if (($endRow == 0) && ($hloopRow1++ != 0)) $output .= "<tr>";
				$output .= '<td>';
				if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) {
					$output .= '<h3>'.$row_scores['brewCategory'].': '.style_convert($row_scores['brewCategorySort'],1).'</h3>';
					$output .= '<p class="lead">'.$style.': '.$row_scores['brewStyle'].'</p>';
				}
				else $output .= '<h3>'.$style.": ".$row_scores['brewStyle'].'</h3>';
				
				if ($filter == "entry") $output .= '<p>#'.sprintf("%06s",$row_scores['id']).'</p>';
				else $output .= '<p>#'.sprintf("%06s",$row_scores['brewJudgingNumber']).'</p>';
				if (!empty($row_scores['brewInfo'])) $output .= '<p><small><em>'.str_replace("^","; ",$row_scores['brewInfo']).'</em></small></p>';
				if (!empty($row_scores['brewInfoOptional'])) $output .= '<p><small><em>'.str_replace("^","; ",$row_scores['brewInfoOptional']).'</em></small></p>';
				if (!empty($row_scores['brewComments'])) $output .= '<p><small><em>'.$row_scores['brewComments'].'</em></small></p>';
				if ($type == 2) $output .= '<p><small><em>'.$row_scores['brewMead1'].', '.$row_scores['brewMead2'].'</small></p>';
				if ($type == 3) $output .= '<p><small><em>'.$row_scores['brewMead1'].', '.$row_scores['brewMead2'].', '.$row_scores['brewMead3'].'</small></p>';
				$output .= '</td>';
				
				$endRow++;
				if ($endRow >= $columns) {
					$output .= '</tr>';
					if ($hloopRow1 % 2 == 0) $output .= '</table><div style="page-break-after:always;"></div><table class="BOS-mat">';
					$endRow = 0;
				}
			}
			
		} while ($row_scores = mysqli_fetch_assoc($scores));
		
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
<?php if ($_SESSION['contestLogo'] != "") { ?>
<style>
<!--
.BOS-mat td:before {
	background-image: url(<?php echo $base_url."user_images/".$_SESSION['contestLogo']; ?>);
	background-repeat: no-repeat;
	background-position: center;
	opacity: 0.25;
    filter: alpha(opacity=25); /* For IE8 and earlier */
}
-->
</style>
<?php } ?>

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
