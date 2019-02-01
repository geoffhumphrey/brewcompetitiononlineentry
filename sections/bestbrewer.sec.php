<?php
/**
 * Module:      bestbrewer.sec.php
 * Description: This module displays the best brewers, ordered by the sum of points obtained for his entries
 *
 *
 */

$header1_1 = "";
$header1_2 = "";
$table_head1 = "";
$table_head2 = "";
$table_body1 = "";
$table_body2 = "";
$page_info_1 = "";

$bestbrewer = array();
$bestbrewer_clubs = array();

include(DB.'scores_bestbrewer.db.php');

function normalizeClubs($string) {
	$club = strtolower($string);
	$club = preg_replace( "/[^a-z0-9]/i", "", $club );
	$club = preg_replace( '/  +/', ' ', $club );
	return $club;
}

// Loop through brewing table for preliminary round scores
do {

	$place = floor($bb_row_scores['scorePlace']);
	$club_name = normalizeClubs($bb_row_scores['brewerClubs']);

	if (array_key_exists($bb_row_scores['uid'], $bestbrewer)) {
		if (($place == $bb_row_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer[$bb_row_scores['uid']]['Places'][$place-1] += 1;
		$bestbrewer[$bb_row_scores['uid']]['Scores'][] = $bb_row_scores['scoreEntry'];

		// -- Compile separate vars for clubs --
		if (!empty($bb_row_scores['brewerClubs'])) {

			if (array_key_exists($club_name, $bestbrewer_clubs)) {
				if (($place == $bb_row_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer_clubs[$club_name]['Places'][$place-1] += 1;
				$bestbrewer_clubs[$club_name]['Scores'][] = $bb_row_scores['scoreEntry'];
			}

			else {
				$bestbrewer_clubs[$club_name]['Places'] = array(0,0,0,0,0);
				if (($place == $bb_row_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer_clubs[$club_name]['Places'][$place-1] = 1;
				$bestbrewer_clubs[$club_name]['Scores'] = array();
				$bestbrewer_clubs[$club_name]['Scores'][] = $bb_row_scores['scoreEntry'];
				$bestbrewer_clubs[$club_name]['Clubs'] = $bb_row_scores['brewerClubs'];
			}

		}
		// -- end clubs --
	}

	else {
		if ($_SESSION['prefsProEdition'] == 1) $bestbrewer[$bb_row_scores['uid']]['Name'] = $bb_row_scores['brewerBreweryName'];
		if ($_SESSION['prefsProEdition'] == 0) {
			$bestbrewer[$bb_row_scores['uid']]['Name'] = $bb_row_scores['brewerFirstName']." ".$bb_row_scores['brewerLastName'];
			if ($bb_row_scores['brewCoBrewer'] != "") $bestbrewer[$bb_row_scores['uid']]['Name'] .= "<br>".$label_cobrewer.": ".$bb_row_scores['brewCoBrewer'];
		}

		if ($_SESSION['prefsProEdition'] == 0) $bestbrewer[$bb_row_scores['uid']]['Clubs'] = $bb_row_scores['brewerClubs'];
		$bestbrewer[$bb_row_scores['uid']]['Places'] = array(0,0,0,0,0);
		$bestbrewer[$bb_row_scores['uid']]['Scores'] = array();
		$bestbrewer[$bb_row_scores['uid']]['TypeBOS'] = array();

		if (($place == $bb_row_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer[$bb_row_scores['uid']]['Places'][$place-1] = 1;
		$bestbrewer[$bb_row_scores['uid']]['Scores'][0] = $bb_row_scores['scoreEntry'];

		// -- Compile separate vars for clubs --
		if (!empty($bb_row_scores['brewerClubs'])) {

			if (array_key_exists($club_name, $bestbrewer_clubs)) {
				if (($place == $bb_row_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer_clubs[$club_name]['Places'][$place-1] += 1;
				$bestbrewer_clubs[$club_name]['Scores'][] = $bb_row_scores['scoreEntry'];
			}

			else {
				$bestbrewer_clubs[$club_name]['Places'] = array(0,0,0,0,0);
				if (($place == $bb_row_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer_clubs[$club_name]['Places'][$place-1] = 1;
				$bestbrewer_clubs[$club_name]['Scores'] = array();
				$bestbrewer_clubs[$club_name]['Scores'][] = $bb_row_scores['scoreEntry'];
				$bestbrewer_clubs[$club_name]['Clubs'] = $bb_row_scores['brewerClubs'];
			}

		}
		// -- end clubs --

	}

} while ($bb_row_scores = mysqli_fetch_assoc($bb_scores));


// BOS - do calcs only if pref is true
if ($row_bb_prefs['prefsBestUseBOS'] == 1) {

	do {

		$club_name = normalizeClubs($bb_row_bos_scores['brewerClubs']);

		if (array_key_exists($bb_row_bos_scores['uid'], $bestbrewer)) {
			$place = floor($bb_row_bos_scores['scorePlace']);
			if (($place == $bb_row_bos_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer[$bb_row_bos_scores['uid']]['Places'][$place-1] += 1;
			$bestbrewer[$bb_row_bos_scores['uid']]['Scores'][] = $bb_row_bos_scores['scoreEntry'];
			$bestbrewer[$bb_row_bos_scores['uid']]['TypeBOS'][] += 1;

			// -- Compile separate vars for clubs --
			if (!empty($bb_row_bos_scores['brewerClubs'])) {

				if (array_key_exists($club_name, $bestbrewer_clubs)) {
					if (($place == $bb_row_bos_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer_clubs[$club_name]['Places'][$place-1] += 1;
					$bestbrewer_clubs[$club_name]['Scores'][] = $bb_row_bos_scores['scoreEntry'];
				}

				else {
					$bestbrewer_clubs[$club_name]['Places'] = array(0,0,0,0,0);
					if (($place == $bb_row_bos_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer_clubs[$club_name]['Places'][$place-1] = 1;
					$bestbrewer_clubs[$club_name]['Scores'] = array();
					$bestbrewer_clubs[$club_name]['Scores'][] = $bb_row_bos_scores['scoreEntry'];
					$bestbrewer_clubs[$club_name]['Clubs'] = $bb_row_bos_scores['brewerClubs'];
				}

			}
			// -- end clubs --
		}

		else {
			$bestbrewer[$bb_row_bos_scores['uid']]['Places'] = array(0,0,0,0,0);
			$bestbrewer[$bb_row_bos_scores['uid']]['TypeBOS'] = array();
			$bestbrewer[$bb_row_bos_scores['uid']]['Scores'] = array();

			$place = floor($bb_row_bos_scores['scorePlace']);
			if (($place == $bb_row_bos_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer[$bb_row_bos_scores['uid']]['Places'][$place-1] = 1;
			$bestbrewer[$bb_row_bos_scores['uid']]['Scores'][0] = $bb_row_bos_scores['scoreEntry'];
			$bestbrewer[$bb_row_bos_scores['uid']]['TypeBOS'][0] = 1;

			// -- Compile separate vars for clubs --
			if (!empty($bb_row_bos_scores['brewerClubs'])) {

				if (array_key_exists($club_name, $bestbrewer_clubs)) {
					if (($place == $bb_row_bos_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer_clubs[$club_name]['Places'][$place-1] += 1;
					$bestbrewer_clubs[$club_name]['Scores'][] = $bb_row_bos_scores['scoreEntry'];
				}

				else {
					$bestbrewer_clubs[$club_name]['Places'] = array(0,0,0,0,0);
					if (($place == $bb_row_bos_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer_clubs[$club_name]['Places'][$place-1] = 1;
					$bestbrewer_clubs[$club_name]['Scores'] = array();
					$bestbrewer_clubs[$club_name]['Scores'][] = $bb_row_bos_scores['scoreEntry'];
					$bestbrewer_clubs[$club_name]['Clubs'] = $bb_row_bos_scores['brewerClubs'];
				}

			}
			// -- end clubs --

		}

	} while ($bb_row_bos_scores = mysqli_fetch_assoc($bb_bos_scores));

}



if ($row_limits['prefsShowBestBrewer'] != 0) {

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

}

if (($_SESSION['prefsProEdition'] == 0) && ($row_limits['prefsShowBestClub'] != 0)) {

	// Compile the Best Club points
	foreach (array_keys($bestbrewer_clubs) as $key) {
		$points_clubs = best_brewer_points($key,$bestbrewer_clubs[$key]['Places'],$bestbrewer_clubs[$key]['Scores'],$bb_points_prefs,$bb_tiebreaker_prefs);
		$bestbrewer_clubs[$key]['Points'] = $points_clubs;
		$bb_sorter_clubs[$key] = $points_clubs;
	}

	arsort($bb_sorter_clubs);

	$show_4th_clubs = FALSE;
	$show_HM_clubs = FALSE;
	$bb_count_clubs = 0;
	$bb_position_clubs = 0;
	$bb_previouspoints_clubs = 0;
	if ($row_limits['prefsShowBestClub'] == -1) $bb_max_position_clubs = count(array_keys($bb_sorter_clubs));
	else $bb_max_position_clubs = $row_limits['prefsShowBestClub'];

	foreach (array_keys($bb_sorter_clubs) as $key) {
		$bb_count_clubs += 1;
		$points_clubs = $bestbrewer_clubs[$key]['Points'];
		if ($points_clubs != $bb_previouspoints_clubs) {
			$bb_position_clubs = $bb_count_clubs;
			$bb_previouspoints_clubs = $points_clubs;
		}
		if ($bb_position_clubs <= $bb_max_position_clubs) {
			if ($bestbrewer_clubs[$key]['Places'][3] > 0) $show_4th_clubs = TRUE;
			if ($bestbrewer_clubs[$key]['Places'][4] > 0) $show_HM_clubs = TRUE;
		}

		else break;
	}

	// Build clubs table body
	$bb_count_clubs = 0;
	$bb_position_clubs = 0;
	$bb_previouspoints_clubs = 0;

	foreach (array_keys($bb_sorter_clubs) as $key) {

		$points_clubs = $bestbrewer_clubs[$key]['Points'];
		$output .= $bestbrewer_clubs[$key]['Clubs']." - ".$points_clubs. " - Places... ";
		$output .= "1: ".$bestbrewer_clubs[$key]['Places'][0]." ";
		$output .= "2: ".$bestbrewer_clubs[$key]['Places'][1]." ";
		$output .= "3: ".$bestbrewer_clubs[$key]['Places'][2]." ";
		if ($show_4th_clubs) $output .= "4: ".$bestbrewer_clubs[$key]['Places'][3]." ";
		if ($show_HM_clubs) $output .= "HM: ".$bestbrewer_clubs[$key]['Places'][4]." ";
		$output .= "<br>";

		$bb_count_clubs += 1;
		$points_clubs = $bestbrewer_clubs[$key]['Points'];

		if ($points_clubs != $bb_previouspoints_clubs) {
			$bb_position_clubs = $bb_count_clubs;
			$bb_previouspoints_clubs = $points_clubs;
			$bb_display_position_clubs = display_place($bb_position_clubs,3);
		}

		else $bb_display_position_clubs = "";

		if ($bb_position_clubs <= $bb_max_position_clubs) {

			// Build club points table body
			$table_body2 .= "<tr>";
			$table_body2 .= "<td width=\"1%\" nowrap><a name=\"club-".$points_clubs."\"></a>".$bb_display_position_clubs."</td>";
			$table_body2 .= "<td>".$bestbrewer_clubs[$key]['Clubs']."</td>";
			$table_body2 .= "<td width=\"10%\" nowrap>".$bestbrewer_clubs[$key]['Places'][0]."</td>";
			$table_body2 .= "<td width=\"10%\" nowrap>".$bestbrewer_clubs[$key]['Places'][1]."</td>";
			$table_body2 .= "<td width=\"10%\" nowrap>".$bestbrewer_clubs[$key]['Places'][2]."</td>";
			if ($show_4th_clubs) $table_body2 .= "<td width=\"10%\" nowrap>".$bestbrewer_clubs[$key]['Places'][3]."</td>";
			if ($show_HM_clubs) $table_body2 .= "<td width=\"10%\" nowrap>".$bestbrewer_clubs[$key]['Places'][4]."</td>";
			$table_body2 .= "<td width=\"1%\" nowrap>";
			if ($section == "results") $table_body2 .= $points_clubs;
			else $table_body2 .= number_format($points_clubs,2);
			if ($section != "results") $table_body2 .= " <a href=\"#club-".$points_clubs."\"  tabindex=\"0\" role=\"button\" data-toggle=\"popover\" data-trigger=\"hover\" data-placement=\"auto top\" data-container=\"body\" title=\"Actual Calculated Value\" data-content=\"".$points_clubs."\"><span class=\"hidden-xs hidden-sm hidden-md hidden-print fa fa-question-circle\"></span></a>";
			$table_body2 .= "</td>";
			$table_body2 .= "</tr>";

		}

		else break;
	}

}

// Build page headers
if ($row_limits['prefsShowBestBrewer'] != 0) {
	$header1_1 .= "<h3>".$row_bb_prefs['prefsBestBrewerTitle'];
	$header1_1 .= sprintf(" (%s %s)", get_participant_count('received-entrant'), ucwords($best_brewer_text_000));
	$header1_1 .= "</h3>";
}

if (($_SESSION['prefsProEdition'] == 0) && ($row_limits['prefsShowBestClub'] != 0)) {
	$header1_2 .= "<h3>".$row_bb_prefs['prefsBestClubTitle'];
	$header1_2 .= sprintf(" (%s %s)", get_participant_count('received-club'), ucwords($best_brewer_text_014));
	$header1_2 .= "</h3>";
}

if ($row_limits['prefsShowBestBrewer'] != 0) {
	// Build best brewer table headers
	$table_head1 .= "<tr>";
	$table_head1 .= sprintf("<th nowrap>%s</th>",$label_place);
	$table_head1 .= sprintf("<th>%s</th>",$label_brewer);
	$table_head1 .= "<th>".addOrdinalNumberSuffix(1)."</th>";
	$table_head1 .= "<th>".addOrdinalNumberSuffix(2)."</th>";
	$table_head1 .= "<th>".addOrdinalNumberSuffix(3)."</th>";
	if ($show_4th) $table_head1 .= "<th>".addOrdinalNumberSuffix(4)."</th>";
	if ($show_HM) $table_head1 .= sprintf("<th>%s</th>",$best_brewer_text_001);
	$table_head1 .= sprintf("<th nowrap>%s</th>",$label_score);
	if ($_SESSION['prefsProEdition'] == 0) $table_head1 .= sprintf("<th>%s</th>",$label_club);
}

if (($_SESSION['prefsProEdition'] == 0) && ($row_limits['prefsShowBestClub'] != 0))  {
	// Clubs table headers
	$table_head2 .= "<tr>";
	$table_head2 .= sprintf("<th nowrap>%s</th>",$label_place);
	$table_head2 .= sprintf("<th>%s</th>",$label_club);
	$table_head2 .= "<th>".addOrdinalNumberSuffix(1)."</th>";
	$table_head2 .= "<th>".addOrdinalNumberSuffix(2)."</th>";
	$table_head2 .= "<th>".addOrdinalNumberSuffix(3)."</th>";
	if ($show_4th_clubs) $table_head2 .= "<th>".addOrdinalNumberSuffix(4)."</th>";
	if ($show_HM_clubs) $table_head2 .= sprintf("<th>%s</th>",$best_brewer_text_001);
	$table_head2 .= sprintf("<th nowrap>%s</th>",$label_score);
	$table_head2 .= "</tr>";
}

if ($row_limits['prefsShowBestBrewer'] != 0) {
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
			$table_body1 .= "<td width=\"1%\" nowrap><a name=\"".$points."\"></a>".$bb_display_position."</td>";
			$table_body1 .= "<td width=\"20%\">".$bestbrewer[$key]['Name'];
			if (array_sum($bestbrewer[$key]['TypeBOS']) > 0) $table_body1 .= sprintf("<small><em><br>** %s %s **</em></small>",$label_bos,$label_participant);
			$table_body1 .= "</td>";
			$table_body1 .= "<td width=\"10%\" nowrap>".$bestbrewer[$key]['Places'][0]."</td>";
			$table_body1 .= "<td width=\"10%\" nowrap>".$bestbrewer[$key]['Places'][1]."</td>";
			$table_body1 .= "<td width=\"10%\" nowrap>".$bestbrewer[$key]['Places'][2]."</td>";
			if ($show_4th) $table_body1 .= "<td width=\"10%\" nowrap>".$bestbrewer[$key]['Places'][3]."</td>";
			if ($show_HM) $table_body1 .= "<td width=\"10%\" nowrap>".$bestbrewer[$key]['Places'][4]."</td>";
			$table_body1 .= "<td width=\"1%\" nowrap>";
			if ($section == "results") $table_body1 .= $points;
			else $table_body1 .= number_format($points,2);
			if ($section != "results") $table_body1 .= " <a href=\"#".$points."\" tabindex=\"0\" role=\"button\" data-toggle=\"popover\" data-trigger=\"hover\" data-placement=\"auto top\" data-container=\"body\" title=\"Actual Calculated Value\" data-content=\"".$points."\"><span class=\"hidden-xs hidden-sm hidden-md hidden-print fa fa-question-circle\"></span></a>";
			$table_body1 .= "</td>";
			if ($_SESSION['prefsProEdition'] == 0) $table_body1 .= "<td>".$bestbrewer[$key]['Clubs']."</td>";
			$table_body1 .= "</tr>";
		}
		else break;
	}
}

if ($section == "default") $page_info_1 .= "<p>".$best_brewer_text_002."</p>";

// --------------------------------------------------------------
// Display
// --------------------------------------------------------------

?>

<?php if ($section == "admin") { ?>

	<div class="row">
		<div class="col col-md-4 col-sm-12">
			<div class="bcoem-admin-element">
			<a class="btn btn-primary btn-block" role="button" data-toggle="collapse" href="#scoreMethodCollapse" aria-expanded="false" aria-controls="scoreMethodCollapse">View Scoring Methodology</a>
			</div>
		</div>
		<div class="col col-md-4 col-sm-12">
			<div class="bcoem-admin-element">
			<a class="btn btn-info btn-block" role="button" href="<?php echo $base_url."index.php?section=admin&go=preferences"; ?>">Edit Settings</a>
		</div>
		</div>
		<div class="col col-md-4 col-sm-12">
			<div class="bcoem-admin-element">
			<a class="btn btn-success btn-block" role="button" id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=results&amp;go=best&amp;action=print&amp;filter=bos&amp;view=default" title="Best Brewer and/or Club Results Report">Print Results</a>
		</div>
		</div>
	</div><!-- ./row -->
<div class="collapse" id="scoreMethodCollapse">
  <div class="well">
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
	</ul>
  </div>
</div>
<?php } if ($row_limits['prefsShowBestBrewer'] != 0) { ?>
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
<?php } ?>
<?php if (($_SESSION['prefsProEdition'] == 0) && ($row_limits['prefsShowBestClub'] != 0)) { ?>
<div class="bcoem-winner-table">
	<?php echo $header1_2; ?>
    <table class="table table-responsive table-striped table-bordered"  id="sortable">
    <thead>
        <?php echo $table_head2; ?>
    </thead>
    <tbody>
        <?php echo $table_body2; ?>
    </tbody>
    </table>
    <?php echo $page_info_1; ?>
</div>
<?php } if ($section == "results")  { ?>
<h4>
	<?php
				if ($row_limits['prefsShowBestBrewer'] != 0) echo $row_bb_prefs['prefsBestBrewerTitle'];
				if (($row_limits['prefsShowBestClub'] != 0) && ($row_limits['prefsShowBestBrewer'] != 0)) echo " / ";
				if ($row_limits['prefsShowBestClub'] != 0) echo $row_bb_prefs['prefsBestClubTitle'];
				echo " ".$best_brewer_text_003;
				?>
</h4>
<div class="small">
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
<?php } if ($section == "default") { ?>
<div class="modal fade" id="scoreMethod" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">
				<?php
				if ($row_limits['prefsShowBestBrewer'] != 0) echo $row_bb_prefs['prefsBestBrewerTitle'];
				if (($row_limits['prefsShowBestClub'] != 0) && ($row_limits['prefsShowBestBrewer'] != 0)) echo " / ";
				if ($row_limits['prefsShowBestClub'] != 0) echo $row_bb_prefs['prefsBestClubTitle'];
				echo " ".$best_brewer_text_003;
				?>
				</h4>
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
		</div>
	</div>
</div>
<?php } ?>
<!-- Public Page Rebuild completed 08.26.15 -->