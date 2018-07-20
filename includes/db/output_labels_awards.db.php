<?php
$query_tables = "SELECT * FROM $judging_tables_db_table ORDER BY tableNumber";
$tables = mysqli_query($connection,$query_tables) or die (mysqli_error($connection));
$row_tables = mysqli_fetch_assoc($tables);
$totalRows_tables = mysqli_num_rows($tables);

$query_bos = "SELECT * FROM $judging_scores_bos_db_table ORDER BY scoreType,scorePlace ASC";
$bos = mysqli_query($connection,$query_bos) or die (mysqli_error($connection));
$row_bos = mysqli_fetch_assoc($bos);
$totalRows_bos = mysqli_num_rows($bos);

do {

	$query_entries = sprintf("SELECT id,brewBrewerFirstName,brewBrewerLastName,brewName,brewStyle,brewCategory,brewSubCategory FROM $brewing_db_table WHERE id='%s'", $row_bos['eid']);
	$entries = mysqli_query($connection,$query_entries) or die (mysqli_error($connection));
	$row_entries = mysqli_fetch_assoc($entries);
	if ($row_bos['scorePlace'] != "") {
		$text = sprintf("\n%s - %s (%s)\n%s\n'%s' %s",
		display_place($row_bos['scorePlace'],1),
		"Best of Show",
		style_type($row_bos['scoreType'],"3","default"),
		strtr($row_entries['brewBrewerFirstName'],$html_remove)." ".strtr($row_entries['brewBrewerLastName'],$html_remove),
		strtr($row_entries['brewName'],$html_remove),
		$row_entries['brewStyle']
		);
		$text = iconv('UTF-8', 'windows-1252', $text);
		$pdf->Add_Label($text);
	}

} while ($row_bos = mysqli_fetch_assoc($bos));

if ($_SESSION['prefsWinnerMethod'] == "1") { // Output by Category

	$query_styles = sprintf("SELECT brewStyleGroup FROM %s WHERE brewStyleActive='Y' AND (brewStyleVersion='%s' OR brewStyleOwn='custom') ORDER BY brewStyleGroup ASC", $styles_db_table, $_SESSION['prefsStyleSet']);
	$styles = mysqli_query($connection,$query_styles) or die (mysqli_error($connection));
	$row_styles = mysqli_fetch_assoc($styles);
	$totalRows_styles = mysqli_num_rows($styles);

	do { $style[] = $row_styles['brewStyleGroup']; } while ($row_styles = mysqli_fetch_assoc($styles));


	foreach (array_unique($style) as $style) {
		$query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewCategorySort='%s' AND brewReceived='1'", $brewing_db_table,  $style);
		$entry_count = mysqli_query($connection,$query_entry_count) or die (mysqli_error($connection));
		$row_entry_count = mysqli_fetch_assoc($entry_count);

		$query_score_count = sprintf("SELECT  COUNT(*) as 'count' FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND a.eid = b.id AND c.uid = b.brewBrewerID AND (a.scorePlace IS NOT NULL OR a.scorePlace='')", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style);
		$score_count = mysqli_query($connection,$query_score_count) or die (mysqli_error($connection));
		$row_score_count = mysqli_fetch_assoc($score_count);


		if (($row_entry_count['count'] > 0) && ($row_score_count['count'] > 0)) {

			$query_scores = sprintf("SELECT a.scorePlace, a.scoreEntry, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerClubs FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND a.eid = b.id AND c.uid = b.brewBrewerID AND (a.scorePlace IS NOT NULL OR a.scorePlace='') ORDER BY a.scorePlace", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style);
			$scores = mysqli_query($connection,$query_scores) or die (mysqli_error($connection));
			$row_scores = mysqli_fetch_assoc($scores);
			$totalRows_scores = mysqli_num_rows($scores);

			do {

			$style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];

				$text = sprintf("\n%s - %s\n%s\n'%s' %s",
				display_place($row_scores['scorePlace'],1),
				style_convert($row_scores['brewCategorySort'],1),
				strtr($row_scores['brewerFirstName'],$html_remove)." ".strtr($row_scores['brewerLastName'],$html_remove),
				strtr($row_scores['brewName'],$html_remove),
				$row_scores['brewerStyle']
				);
				$text = iconv('UTF-8', 'windows-1252', $text);
				$pdf->Add_Label($text);

			} while ($row_scores = mysqli_fetch_assoc($scores));

		}
	}
}

elseif ($_SESSION['prefsWinnerMethod'] == "2") { // Output by sub-category

	$query_styles = sprintf("SELECT brewStyleGroup,brewStyleNum,brewStyle FROM %s WHERE brewStyleActive='Y' AND (brewStyleVersion='%s' OR brewStyleOwn='custom') ORDER BY brewStyleGroup,brewStyleNum ASC", $styles_db_table, $_SESSION['prefsStyleSet']);
	$styles = mysqli_query($connection,$query_styles) or die (mysqli_error($connection));
	$row_styles = mysqli_fetch_assoc($styles);
	$totalRows_styles = mysqli_num_rows($styles);
	do { $style[] = $row_styles['brewStyleGroup']."-".$row_styles['brewStyleNum']."-".$row_styles['brewStyle']; } while ($row_styles = mysqli_fetch_assoc($styles));

	foreach (array_unique($style) as $style) {
		$style = explode("-",$style);
		$query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewCategorySort='%s' AND brewSubCategory='%s' AND brewReceived='1'", $brewing_db_table,  $style[0], $style[1]);
		$entry_count = mysqli_query($connection,$query_entry_count) or die (mysqli_error($connection));
		$row_entry_count = mysqli_fetch_assoc($entry_count);

		$query_score_count = sprintf("SELECT  COUNT(*) as 'count' FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND b.brewSubCategory='%s' AND a.eid = b.id AND a.scorePlace IS NOT NULL AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style[0], $style[1]);
		$score_count = mysqli_query($connection,$query_score_count) or die (mysqli_error($connection));
		$row_score_count = mysqli_fetch_assoc($score_count);


		if (($row_entry_count['count'] > 0) && ($row_score_count['count'] > 0)) {

			$query_scores = sprintf("SELECT a.scorePlace, b.brewName, b.brewCategory, b.brewSubCategory, b.brewStyle, c.brewerLastName, c.brewerFirstName, c.brewerClubs FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND b.brewSubCategory='%s' AND a.eid = b.id  AND c.uid = b.brewBrewerID  AND (a.scorePlace IS NOT NULL OR a.scorePlace='') ORDER BY a.scorePlace", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style[0],$style[1]);
			$scores = mysqli_query($connection,$query_scores) or die (mysqli_error($connection));
			$row_scores = mysqli_fetch_assoc($scores);
			$totalRows_scores = mysqli_num_rows($scores);

			do {

				if ($filter == "round") {

					$text = sprintf("\n%s - %s\n%s\n'%s'",
					display_place($row_scores['scorePlace'],1),
					$row_scores['brewCategory'].$row_scores['brewSubCategory'].": ".$row_scores['brewStyle'],
					strtr($row_scores['brewerFirstName'],$html_remove)." ".strtr($row_scores['brewerLastName'],$html_remove),
					strtr($row_scores['brewName'],$html_remove)
					);

				}

				else {

					$text = sprintf("\n%s - %s\n%s\n'%s'",
					display_place($row_scores['scorePlace'],1),
					$row_scores['brewCategory'].$row_scores['brewSubCategory'].": ".$row_scores['brewStyle'],
					strtr($row_scores['brewerFirstName'],$html_remove)." ".strtr($row_scores['brewerLastName'],$html_remove),
					strtr($row_scores['brewName'],$html_remove)
					);

				}

				$text = iconv('UTF-8', 'windows-1252', $text);
				$pdf->Add_Label($text);

			} while ($row_scores = mysqli_fetch_assoc($scores));
		}
	}
} // end elseif ($_SESSION['prefsWinnerMethod'] == "2")

else { // Output by Table.

	do {

	$query_scores = sprintf("SELECT * FROM %s WHERE scoreTable='%s'", $judging_scores_db_table, $row_tables['id']);
	$query_scores .= " AND (scorePlace='1' OR scorePlace='2' OR scorePlace='3' OR scorePlace='4' OR scorePlace='5') ORDER BY scorePlace ASC";
	$scores = mysqli_query($connection,$query_scores) or die (mysqli_error($connection));
	$row_scores = mysqli_fetch_assoc($scores);
	$totalRows_scores = mysqli_num_rows($scores);

		do {
			$query_entries = sprintf("SELECT id,brewBrewerFirstName,brewBrewerLastName,brewName,brewStyle,brewCategory,brewSubCategory FROM $brewing_db_table WHERE id='%s'", $row_scores['eid']);
			$entries = mysqli_query($connection,$query_entries) or die (mysqli_error($connection));
			$row_entries = mysqli_fetch_assoc($entries);

			$display_place = display_place($row_scores['scorePlace'],1);

			$text = sprintf("\n%s - %s\n%s\n'%s' %s",
			$display_place,
			truncate($row_tables['tableName'], 35),
			strtr($row_entries['brewBrewerFirstName'],$html_remove)." ".strtr($row_entries['brewBrewerLastName'],$html_remove),
			strtr($row_entries['brewName'],$html_remove),
			$row_entries['brewStyle']
			);

			$text = iconv('UTF-8', 'windows-1252', $text);
			if ($display_place != "N/A") $pdf->Add_Label($text);

		} while ($row_scores = mysqli_fetch_assoc($scores));

	} while ($row_tables = mysqli_fetch_assoc($tables));

}

?>