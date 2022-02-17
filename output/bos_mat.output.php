<?php
/**
 * Module:      bos_mat.php
 * Description: Template for custom reports.
 *
 */

$go = "output";
require(DB.'admin_common.db.php');
require(LIB.'output.lib.php');
if ($_SESSION['prefsStyleSet'] == "BA") include(INCLUDES.'ba_constants.inc.php');

function check_table_name($id,$judging_tables_db_table) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	$query_tables = sprintf("SELECT tableName,tableNumber FROM %s WHERE id='%s'",$judging_tables_db_table,$id);
	$tables = mysqli_query($connection,$query_tables) or die (mysqli_error($connection));
	$row_tables = mysqli_fetch_assoc($tables);
	return "From Table ".$row_tables['tableNumber'].": ".$row_tables['tableName'];
}

$special_beer = array("6D","16E","17F","20A","21A","21B","22B","22C","23A");
$special_mead = array("24A","24B","24C","25A","25B","25C","26A","26B","26C");
$special_cider = array("27B","27C","27E","28A","28B","28C","28D");

$a = array();

if ($view == "default") {

	do { $a[] = $row_style_types['id']; } while ($row_style_types = mysqli_fetch_assoc($style_types));
	sort($a);
}

else $a[] = $view;

$output = "";

foreach ($a as $type) {
	$style_type_info = style_type_info($type);
	$style_type_info = explode("^",$style_type_info);

	// print_r($style_type_info); exit;

	if ($style_type_info[0] == "Y") {

		include (DB.'output_bos_mat.db.php');
		//$output .= $query_scores;

		$endRow = 0;
		$columns = 3;  // number of columns
		$hloopRow1 = 0; // first row flag

		$output .= '<table class="BOS-mat">';
		do {
			
			if (!empty($row_scores['brewJudgingNumber'])) {

				$style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];

				if (($endRow == 0) && ($hloopRow1++ != 0)) $output .= "<tr>";
				$output .= '<td>';
				$output .= '<div style="position:relative;width:100%;height:100%;">';
				if ($_SESSION['prefsStyleSet'] == "BA") {
					$output .= '<h3 style="padding:0; margin: 0;">';
					$output .= $row_scores['brewStyle'];
					$output .= '</h3>';
					$output .= '<h4>';
					$output .= '<em>'.style_convert($row_scores['brewCategorySort'],1).'</em>';
					$output .= '</h4>';
				}
				else {
					$output .= '<h3 style="padding:0 0 5px 0;margin:0;">';
					$output .= ltrim($row_scores['brewCategory'],"0").': '.style_convert($row_scores['brewCategorySort'],1);
					$output .= '</h3>';
					$output .= '<h4 style="padding:0 0 5px 0;margin:0;">';
					$output .= '<em>'.$style.': '.$row_scores['brewStyle'].'</em>';
					$output .= '</h4>';
				}
				//$output .= '</h3>';
				if (!empty($row_scores['brewPossAllergens'])) $output .= '<p style="width:100%;padding:3px;border:1px solid #000;border-radius:5px;"><strong>'.$label_possible_allergens.':</strong> <em>'.$row_scores['brewPossAllergens'].'</em></p>';
				if (!empty($row_scores['brewInfo'])) $output .= '<p><em><small>'.str_replace("^"," | ",$row_scores['brewInfo']).'</small></em></p>';
				if (!empty($row_scores['brewInfoOptional'])) $output .= '<p><em><small>'.str_replace("^"," | ",$row_scores['brewInfoOptional']).'</small></em></p>';
				if (!empty($row_scores['brewComments'])) $output .= '<p><em><small>'.$row_scores['brewComments'].'</small></em></p>';
				
				if ($type == 2) $output .= '<p><em>'.$row_scores['brewMead1'].', '.$row_scores['brewMead2'].'</p>';
				if ($type == 3) $output .= '<p><em>'.$row_scores['brewMead1'].', '.$row_scores['brewMead2'].', '.$row_scores['brewMead3'].'</p>';
				if (pro_am_check($row_scores['brewBrewerID']) == 1) $output .= "<p><em>** NOT ELIGIBLE FOR PRO-AM **</em></p>";

				$output .= '<section style="text-align:right;position:absolute;bottom:0;right:0;">';
				if ($filter == "entry") $output .= '<p style="padding:0;margin:0"><small>#'.sprintf("%06s",$row_scores['id']).'</small></p>';
				else $output .= '<p style="padding:0;margin:0"><small>#'.sprintf("%06s",$row_scores['brewJudgingNumber']).'</small></p>';
				$output .= '<p><small><strong>'.check_table_name($row_scores['scoreTable'],$judging_tables_db_table).'</strong></small></p>';
				$output .= '</section>';

				$output .= '</div>';
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
<?php if ((isset($_SESSION['contestLogo'])) && (file_exists(USER_IMAGES.$_SESSION['contestLogo']))) { ?>
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
<?php } 
if (empty($output)) {
	echo '<h1>';
	echo 'No BOS entries are present';
	if ($view != "default") echo ' for this style';
	echo '.';
	echo '</h1>';
}
else echo $output;
?>
