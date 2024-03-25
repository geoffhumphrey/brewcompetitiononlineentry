<?php
/**
 * Module:      bos_mat.php
 * Description: Template for custom reports.
 *
 */

// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] > 1))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

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
	return "Table ".$row_tables['tableNumber'].": ".$row_tables['tableName'];
}

$a = array();
$output = "";

if ($action == "blank") {

	$output .= '<table class="BOS-mat">';

	for ($i=1; $i<=2; $i++) {
		$output .= '<tr>';
		for ($j=1; $j<=3; $j++) {
			$output .= '<td>';
			$output .= '<div style="position:relative;width:100%;height:100%;">';
			if ($view == "mini-bos") $output .= '<h4 style="text-align:center"><strong>*** Mini-BOS ***</strong></h4>';
			elseif ($view == "pro-am") $output .= '<h4 style="text-align:center"><strong>*** Pro-Am/Scale-Up ***</strong></h4>';
			else $output .= '<h4 style="text-align:center"><strong>*** Best of Show ***</strong></h4>';
			$output .= '<p style="text-align:center">'.$label_style.' ___________________________<p>';
			$output .= '<section style="text-align:right;position:absolute;bottom:0;right:0;">';
			$output .= '<p style="padding:0;margin:0"><small>'.$label_entry.' # ____________</small></p>';
			$output .= '</section>';
			$output .= '</div>';
			$output .= '</td>';
		}
		$output .= '</tr>';
	}

	$output .= '</table>';
	$output .= '<div style="break-after: always;"></div>';
	
}

// Group by Table
elseif ($action == "mini-bos") {

	$query_tables = sprintf("SELECT id,tableNumber FROM %s",$judging_tables_db_table);
	$tables = mysqli_query($connection,$query_tables) or die (mysqli_error($connection));
	$row_tables = mysqli_fetch_assoc($tables);

	if ($row_tables) {

		if ($view == "default") {
		
			do { 
				$a[] = $row_tables['id']; 
			} while ($row_tables = mysqli_fetch_assoc($tables));
			
			sort($a);
		}

		else $a[] = $view;

	}

} 

// Group by style type
else {

	if ($view == "default") {

		do { 
			$a[] = $row_style_types['id']; 
		} while ($row_style_types = mysqli_fetch_assoc($style_types));
		
		sort($a);

	}

	else $a[] = $view;

}

if (!empty($a)) {

	foreach ($a as $type) {

		$display = FALSE;
		if ($action == "mini-bos") $display = TRUE;
		
		else {

			$style_type_info = style_type_info($type);
			$style_type_info = explode("^",$style_type_info);

			if ($style_type_info[0] == "Y") $display = TRUE;

		}

		if ($display) {

			include (DB.'output_bos_mat.db.php');

			$endRow = 0;
			$rows = 2;
			$columns = 3;
			$hloopRow1 = 0;
			$cells = $columns * $rows;
			$tile_count = 0;

			$output .= '<table class="BOS-mat">';

			do {
				
				if (!empty($row_scores['brewJudgingNumber'])) {

					$tile_count++;

					$style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];

					if ($tile_count == 1) $output .= '<tr>';
					if ($tile_count == 4) $output .= '<tr>';
					$output .= '<td>';
					$output .= '<div style="position:relative;width:100%;height:100%;">';
					
					if ($action == "mini-bos") $output .= '<p style="text-align:center"><strong>*** '.$label_mini_bos.' ***</strong></p>';
					elseif ($action == "pro-am") $output .= '<p style="text-align:center"><strong>*** '.$label_pro_am.': '.$style_type_info[2].' * ***</strong></p>';
					else $output .= '<p style="text-align:center"><strong>*** '.$label_bos.': '.$style_type_info[2].' ***</strong></p>';
					
					if ($_SESSION['prefsStyleSet'] == "BA") {

						$output .= '<h3 style="padding:0; margin: 0;">';
						$output .= $row_scores['brewStyle'];
						$output .= '</h3>';
						$output .= '<h4>';
						$output .= '<em>'.style_convert($row_scores['brewCategorySort'],1,$base_url).'</em>';
						$output .= '</h4>';

					}
					
					else {

						$output .= '<h3 style="padding:0 0 5px 0; margin:0;">';
						$output .= $style.': '.$row_scores['brewStyle'];
						$output .= '</h3>';
						$output .= '<h4 style="padding:0 0 5px 0; margin:0;">';
						if ($_SESSION['prefsWinnerMethod'] == 0) $output .= check_table_name($row_scores['scoreTable'],$judging_tables_db_table);
						else $output .= ltrim($row_scores['brewCategory'],"0").': '.style_convert($row_scores['brewCategorySort'],1,$base_url);
						$output .= '</h4>';

					}
					
					if (!empty($row_scores['brewInfo'])) $output .= '<p><em><small>'.str_replace("^"," | ",$row_scores['brewInfo']).'</small></em></p>';
					if (!empty($row_scores['brewInfoOptional'])) $output .= '<p><em><small>'.str_replace("^"," | ",$row_scores['brewInfoOptional']).'</small></em></p>';
					if (!empty($row_scores['brewComments'])) $output .= '<p><em><small>'.$row_scores['brewComments'].'</small></em></p>';
					
					if (($action == "default") && ($type == 2)) {
						$output .= '<p><em>';
						if (!empty($row_scores['brewMead1'])) $output .= $row_scores['brewMead1'];
						if (!empty($row_scores['brewMead2'])) $output .= ', '.$row_scores['brewMead2'];
						$output .= '</p>';
					}

					if (($action == "default") && ($type == 3)) {
						$output .= '<p><em>';
						if (!empty($row_scores['brewMead1'])) $output .= $row_scores['brewMead1'];
						if (!empty($row_scores['brewMead2'])) $output .= ', '.$row_scores['brewMead2'];
						if (!empty($row_scores['brewMead3'])) $output .= ', '.$row_scores['brewMead3'];
						$output .= '</p>';
					}

					if (!empty($row_scores['brewPossAllergens'])) $output .= '<p style="width:100%; padding:5px; border:1px solid #ccc; border-radius:5px;"><small><strong>'.$label_possible_allergens.':</strong> <em>'.$row_scores['brewPossAllergens'].'</em></small></p>';
					

					if (($action == "default") && (pro_am_check($row_scores['brewBrewerID']) == 1)) $output .= "<p><em>** NOT ELIGIBLE FOR PRO-AM **</em></p>";
					
					$output .= '<section style="text-align:right;position:absolute;bottom:0;right:0;">';

					if ($filter == "entry") $output .= '<p style="padding:0;margin:0"><small>#'.sprintf("%06s",$row_scores['id']).'</small></p>';
					else $output .= '<p style="padding:0;margin:0"><small>#'.sprintf("%06s",$row_scores['brewJudgingNumber']).'</small></p>';

					if ($_SESSION['prefsWinnerMethod'] > 0) {
						$output .= '<p><small><strong>'.check_table_name($row_scores['scoreTable'],$judging_tables_db_table).'</strong></small></p>';
						$output .= '</section>';
					}
					
					/*
					$output .= $tile_count;
					$output .= "<br>";
					$output .= $endRow;
					$output .= "<br>";
					$output .= $tile_count;
					$output .= '</div>';
					$output .= '</td>';
					*/

					$endRow++;
					$hloopRow1++;

					if ($endRow >= $columns) {
						$output .= '</tr>';
						if ($tile_count == $cells) {
							$output .= '</table><div style="break-after: always;"></div><table class="BOS-mat">';
							$tile_count = 0;
						}
						$endRow = 0;
					}

				}

			} while ($row_scores = mysqli_fetch_assoc($scores));

			if ($tile_count < $cells) {

				$this_tile_count = 0;
				$output_extra = "";
				
				while ($tile_count < $cells) {
					
					$tile_count++;
					$endRow++;
					$this_tile_count++;
					
					if ($tile_count == 4) $output_extra .= '<tr>';
					$output_extra .= '<td>&nbsp;</td>';
					
					if ($tile_count == 6) $output_extra .= '</tr>';

				}

				if ($this_tile_count < 6) $output .= $output_extra;

			$output .= '</table><div style="break-after: always;"></div>';

			}

		} // end if ($display)

	} // end foreach

	if ($tile_count == 0) {
		$output .= '<h1>';
		if ($action == "mini-bos") $output .= 'No Mini-BOS entries are present';
		else $output .= 'No BOS entries are present';
		if ($view != "default") $output .= ' for this style type';
		$output .= '.';
		$output .= '</h1>';
	}

} // end if (!empty($a))

$unformatted = array("</table>","</tr>","</td>","</p>","</h3>","</h4>","</div>","</section>","<tr>","<td>",);
$formatted = array("</table>\n\n","</tr>\n","</td>\n","</p>\n","</h3>\n","</h4>\n","</div>\n","</section>\n","<tr>\n","<td>\n",);
$output = str_replace($unformatted,$formatted,$output);
echo $output;

?>
