<?php
/**
 * Module:      bestbrewer.sec.php
 * Description: This module displays the best brewers, ordered by the sum of points obtained for his entries
 *
 *
 */

$header1_1 = "";
$table_head1 = "";
$table_body1 = "";
$page_info_1 = "";

$bestbrewer = array();

include(DB.'scores_bestbrewer.db.php');

// Loop through brewing table for preliminary round scores
do {

	if (array_key_exists($bb_row_scores['uid'], $bestbrewer)) {
		$place = floor($bb_row_scores['scorePlace']);
		if (($place == $bb_row_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer[$bb_row_scores['uid']]['Places'][$place-1] += 1;
		$bestbrewer[$bb_row_scores['uid']]['Scores'][] = $bb_row_scores['scoreEntry'];
	}

	else {
		if ($_SESSION['prefsProEdition'] == 1) $bestbrewer[$bb_row_scores['uid']]['Name'] = $bb_row_scores['brewerBreweryName'];
		if ($_SESSION['prefsProEdition'] == 0)  {
			$bestbrewer[$bb_row_scores['uid']]['Name'] = $bb_row_scores['brewerFirstName']." ".$bb_row_scores['brewerLastName'];
			if ($bb_row_scores['brewCoBrewer'] != "") $bestbrewer[$bb_row_scores['uid']]['Name'] .= "<br>".$label_cobrewer.": ".$bb_row_scores['brewCoBrewer'];
		}

		if ($_SESSION['prefsProEdition'] == 0) $bestbrewer[$bb_row_scores['uid']]['Clubs'] = $bb_row_scores['brewerClubs'];
		$bestbrewer[$bb_row_scores['uid']]['Places'] = array(0,0,0,0,0);
		$bestbrewer[$bb_row_scores['uid']]['Scores'] = array();
		$bestbrewer[$bb_row_scores['uid']]['TypeBOS'] = array();

		$place = floor($bb_row_scores['scorePlace']);
		if (($place == $bb_row_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer[$bb_row_scores['uid']]['Places'][$place-1] = 1;
		$bestbrewer[$bb_row_scores['uid']]['Scores'][0] = $bb_row_scores['scoreEntry'];

	}

} while ($bb_row_scores = mysqli_fetch_assoc($bb_scores));

// BOS Round
do {

	if (array_key_exists($bb_row_bos_scores['uid'], $bestbrewer)) {
		$place = floor($bb_row_bos_scores['scorePlace']);
		if (($place == $bb_row_bos_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer[$bb_row_bos_scores['uid']]['Places'][$place-1] += 1;
		$bestbrewer[$bb_row_bos_scores['uid']]['Scores'][] = $bb_row_bos_scores['scoreEntry'];
		$bestbrewer[$bb_row_bos_scores['uid']]['TypeBOS'][] += 1;
	}

	else {
		$bestbrewer[$bb_row_bos_scores['uid']]['Places'] = array(0,0,0,0,0);
		$bestbrewer[$bb_row_bos_scores['uid']]['TypeBOS'] = array();
		$bestbrewer[$bb_row_bos_scores['uid']]['Scores'] = array();

		$place = floor($bb_row_bos_scores['scorePlace']);
		if (($place == $bb_row_bos_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer[$bb_row_bos_scores['uid']]['Places'][$place-1] = 1;
		$bestbrewer[$bb_row_bos_scores['uid']]['Scores'][0] = $bb_row_bos_scores['scoreEntry'];
		$bestbrewer[$bb_row_bos_scores['uid']]['TypeBOS'][0] = 1;
	}

} while ($bb_row_bos_scores = mysqli_fetch_assoc($bb_bos_scores));

foreach (array_keys($bestbrewer) as $key) {
	$points = best_brewer_points($key,$bestbrewer[$key]['Places'],$bestbrewer[$key]['Scores'],$bb_points_prefs,$bb_tiebreaker_prefs);
	$bestbrewer[$key]['Points'] = $points;
	$bb_sorter[$key] = $points;
}

arsort($bb_sorter);

$show_4th = FALSE;
$show_HM = FALSE;
$bb_count = 0;
$bb_position = 0;
$bb_previouspoints = 0;
if ($row_limits['prefsShowBestBrewer'] == -1) $bb_max_position = count(array_keys($bb_sorter));
else $bb_max_position = $row_limits['prefsShowBestBrewer'];

foreach (array_keys($bb_sorter) as $key) {
	$bb_count += 1;
	$points = $bestbrewer[$key]['Points'];
	if ($points != $bb_previouspoints) {
		$bb_position = $bb_count;
		$bb_previouspoints = $points;
	}
	if ($bb_position <= $bb_max_position) {
		if ($bestbrewer[$key]['Places'][3] > 0) $show_4th = TRUE;
		if ($bestbrewer[$key]['Places'][4] > 0) $show_HM = TRUE;
	}
	else break;
}

// Build page headers
$header1_1 .= "<h3>".$row_bb_prefs['prefsBestBrewerTitle'];
$header1_1 .= sprintf(" (".get_participant_count('received-entrant')." %s)", ucwords($best_brewer_text_000));
$header1_1 .= "</h3>";

// Build table headers
$table_head1 .= "<tr>";
$table_head1 .= sprintf("<th width=\"1%%\" nowrap>%s</th>",$label_place);
$table_head1 .= sprintf("<th width=\"24%%\">%s</th>",$label_brewer);
$table_head1 .= "<th width=\"10%%\">".addOrdinalNumberSuffix(1)."</th>";
$table_head1 .= "<th width=\"10%%\">".addOrdinalNumberSuffix(2)."</th>";
$table_head1 .= "<th width=\"10%%\">".addOrdinalNumberSuffix(3)."</th>";
if ($show_4th) $table_head1 .= "<th width=\"10%%\">".addOrdinalNumberSuffix(4)."</th>";
if ($show_HM) $table_head1 .= sprintf("<th width=\"10%%\">%s</th>",$best_brewer_text_001);
$table_head1 .= sprintf("<th width=\"1%%\" nowrap>%s</th>",$label_score);
if ($_SESSION['prefsProEdition'] == 0) $table_head1 .= sprintf("<th width=\"24%%\" class=\"hidden-xs hidden-sm hidden-md\">%s</th>",$label_club);

$bb_count = 0;
$bb_position = 0;
$bb_previouspoints = 0;

foreach (array_keys($bb_sorter) as $key) {
	$bb_count += 1;
	$points = $bestbrewer[$key]['Points'];
	if ($points != $bb_previouspoints) {
		$bb_position = $bb_count;
		$bb_previouspoints = $points;
		$bb_display_position = display_place($bb_position,3);
	}
	else $bb_display_position = "";
	if ($bb_position <= $bb_max_position) {
		$table_body1 .= "<tr>";
		$table_body1 .= "<td nowrap>".$bb_display_position."</td>";
		$table_body1 .= "<td>".$bestbrewer[$key]['Name'];
		if (array_sum($bestbrewer[$key]['TypeBOS']) > 0) $table_body1 .= sprintf("<small><em><br>** %s %s **</em></small>",$label_bos,$label_participant);
		$table_body1 .= "</td>";
		$table_body1 .= "<td>".$bestbrewer[$key]['Places'][0]."</td>";
		$table_body1 .= "<td>".$bestbrewer[$key]['Places'][1]."</td>";
		$table_body1 .= "<td>".$bestbrewer[$key]['Places'][2]."</td>";
		if ($show_4th) $table_body1 .= "<td>".$bestbrewer[$key]['Places'][3]."</td>";
		if ($show_HM) $table_body1 .= "<td>".$bestbrewer[$key]['Places'][4]."</td>";
		// $table_body1 .= "<td>".$points."</td>";
		if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= "1")) $table_body1 .= "<td>".$points."</td>";
		else $table_body1 .= "<td>".floor($points)."</td>";
		if ($_SESSION['prefsProEdition'] == 0) $table_body1 .= "<td class=\"hidden-xs hidden-sm hidden-md\">".$bestbrewer[$key]['Clubs']."</td>";
		$table_body1 .= "</tr>";
	}
	else break;
}

$page_info_1 .= "<p>".$best_brewer_text_002."</p>";

// --------------------------------------------------------------
// Display
// --------------------------------------------------------------

?>
<div class="bcoem-winner-table">
	<?php echo $header1_1; ?>
    <table class="table table-responsive table-striped table-bordered"  id="sortable">
    <thead>
        <?php echo $table_head1; ?>
    </thead>
    <tbody>
        <?php echo $table_body1; ?>
    </tbody>
    </table>
    <?php echo $page_info_1; ?>
</div>
<div class="modal fade" id="scoreMethod" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><?php echo $row_bb_prefs['prefsBestBrewerTitle']." ".$best_brewer_text_003; ?></h4>
			</div>
			<div class="modal-body">
				<p><?php echo $best_brewer_text_004; ?></p>
				<ul>
					<li><?php echo addOrdinalNumberSuffix(1)." ".$label_place.": ".$row_bb_prefs['prefsFirstPlacePts']; ?></li>
					<li><?php echo addOrdinalNumberSuffix(2)." ".$label_place.": ".$row_bb_prefs['prefsSecondPlacePts']; ?></li>
					<li><?php echo addOrdinalNumberSuffix(3)." ".$label_place.": ".$row_bb_prefs['prefsThirdPlacePts']; ?></li>
					<?php if ($row_bb_prefs['prefsFourthPlacePts'] > 0) { ?><li><?php echo addOrdinalNumberSuffix(4)." ".$label_place.": ".$row_bb_prefs['prefsFourthPlacePts']; ?></li><?php } ?>
					<?php if ($row_bb_prefs['prefsHMPts'] > 0) { ?><li><?php echo $best_brewer_text_001.": ".$row_bb_prefs['prefsHMPts']; ?></li><?php } ?>
				</ul>
				<?php if (!empty($row_bb_prefs['prefsTieBreakRule1'])) { ?>
				<p><?php echo $best_brewer_text_005; ?></p>
				<ol>
					<li><?php echo tiebreak_rule($row_bb_prefs['prefsTieBreakRule1']); ?></li>
					<?php if (!empty($row_bb_prefs['prefsTieBreakRule2'])) { ?><li><?php echo tiebreak_rule($row_bb_prefs['prefsTieBreakRule2']); ?></li><?php } ?>
					<?php if (!empty($row_bb_prefs['prefsTieBreakRule3'])) { ?><li><?php echo tiebreak_rule($row_bb_prefs['prefsTieBreakRule3']); ?></li><?php } ?>
					<?php if (!empty($row_bb_prefs['prefsTieBreakRule4'])) { ?><li><?php echo tiebreak_rule($row_bb_prefs['prefsTieBreakRule4']); ?></li><?php } ?>
					<?php if (!empty($row_bb_prefs['prefsTieBreakRule5'])) { ?><li><?php echo tiebreak_rule($row_bb_prefs['prefsTieBreakRule5']); ?></li><?php } ?>
					<?php if (!empty($row_bb_prefs['prefsTieBreakRule6'])) { ?><li><?php echo tiebreak_rule($row_bb_prefs['prefsTieBreakRule6']); ?></li><?php } ?>
				</ol>
				<?php } ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Public Page Rebuild completed 08.26.15 -->