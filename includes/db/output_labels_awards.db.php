<?php
$query_tables = sprintf("SELECT * FROM %s ORDER BY tableNumber",$prefix."judging_tables");
$tables = mysqli_query($connection,$query_tables) or die (mysqli_error($connection));
$row_tables = mysqli_fetch_assoc($tables);
$totalRows_tables = mysqli_num_rows($tables);

$query_bos = sprintf("SELECT * FROM %s ORDER BY scoreType,scorePlace ASC",$prefix."judging_scores_bos");
$bos = mysqli_query($connection,$query_bos) or die (mysqli_error($connection));
$row_bos = mysqli_fetch_assoc($bos);
$totalRows_bos = mysqli_num_rows($bos);

if ($filter == "round") $character_limit = 18;
else $character_limit = 31;

$styles_selected = json_decode($_SESSION['prefsSelectedStyles'], true);

do {

	$query_entries = sprintf("SELECT id,brewBrewerFirstName,brewBrewerLastName,brewName,brewStyle,brewCategory,brewSubCategory FROM %s WHERE id='%s'", $prefix."brewing", $row_bos['eid']);
	$entries = mysqli_query($connection,$query_entries) or die (mysqli_error($connection));
	$row_entries = mysqli_fetch_assoc($entries);
	
	if ($row_bos['scorePlace'] != "") {
		$text = sprintf("\n%s - %s (%s)\n%s\n'%s' %s",
		display_place($row_bos['scorePlace'],1),
		"Best of Show",
		style_type($row_bos['scoreType'],"3","default"),
		html_entity_decode($row_entries['brewBrewerFirstName'])." ".html_entity_decode($row_entries['brewBrewerLastName']),
		html_entity_decode(trim($row_entries['brewName'])),
		$row_entries['brewStyle']
		);
		$text = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $text)));
		$pdf->Add_Label($text);
	}

} while ($row_bos = mysqli_fetch_assoc($bos));

if ($_SESSION['prefsWinnerMethod'] == "1") { // Output by Category

	$query_styles = sprintf("SELECT id,brewStyleGroup FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') ORDER BY brewStyleGroup ASC", $prefix."styles", $_SESSION['prefsStyleSet']);
	$styles = mysqli_query($connection,$query_styles) or die (mysqli_error($connection));
	$row_styles = mysqli_fetch_assoc($styles);
	$totalRows_styles = mysqli_num_rows($styles);

	do { 
		if (array_key_exists($row_styles['id'], $styles_selected)) $style[] = $row_styles['brewStyleGroup']; 
	} while ($row_styles = mysqli_fetch_assoc($styles));

	foreach (array_unique($style) as $style) {
		$query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewCategorySort='%s' AND brewReceived='1'", $prefix."brewing",  $style);
		$entry_count = mysqli_query($connection,$query_entry_count) or die (mysqli_error($connection));
		$row_entry_count = mysqli_fetch_assoc($entry_count);

		$query_score_count = sprintf("SELECT  COUNT(*) as 'count' FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND a.eid = b.id AND c.uid = b.brewBrewerID AND (a.scorePlace IS NOT NULL OR a.scorePlace='')", $prefix."judging_scores", $prefix."brewing", $prefix."brewer", $style);
		$score_count = mysqli_query($connection,$query_score_count) or die (mysqli_error($connection));
		$row_score_count = mysqli_fetch_assoc($score_count);


		if (($row_entry_count['count'] > 0) && ($row_score_count['count'] > 0)) {

			$query_scores = sprintf("SELECT a.scorePlace, a.scoreEntry, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerClubs FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND a.eid = b.id AND c.uid = b.brewBrewerID AND (a.scorePlace IS NOT NULL OR a.scorePlace='') ORDER BY a.scorePlace", $prefix."judging_scores", $prefix."brewing", $prefix."brewer", $style);
			$scores = mysqli_query($connection,$query_scores) or die (mysqli_error($connection));
			$row_scores = mysqli_fetch_assoc($scores);
			$totalRows_scores = mysqli_num_rows($scores);

			do {

			$display_place = display_place($row_scores['scorePlace'],1);
			$brewer_name = truncate($row_scores['brewerFirstName']." ".$row_scores['brewerLastName'], $character_limit,"...");
			$entry_name = truncate(trim($row_scores['brewName']), $character_limit,"...");
			$style = style_convert($row_scores['brewCategorySort'],1);
			$style = truncate($style,$character_limit,"...");
			$style_name = truncate($row_scores['brewStyle'],$character_limit);

			if ($filter == "round") {

				$text = sprintf("\n%s\n%s\n%s\n'%s'\n%s",
					$display_place,
					$style,
					$brewer_name,
					$entry_name,
					$style_name
				);

			}

			else {

				$text = sprintf("\n%s\n%s\n%s\n'%s'\n%s",
					$display_place,
					$style,
					$brewer_name,
					$entry_name,
					$style_name
				);

			}

			$text = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$text);
			$pdf->Add_Label($text);

			} while ($row_scores = mysqli_fetch_assoc($scores));

		}
	}
}

elseif ($_SESSION['prefsWinnerMethod'] == "2") { // Output by sub-category

	$query_styles = sprintf("SELECT id,brewStyleGroup,brewStyleNum,brewStyle FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') ORDER BY brewStyleGroup,brewStyleNum ASC", $prefix."styles", $_SESSION['prefsStyleSet']);
	$styles = mysqli_query($connection,$query_styles) or die (mysqli_error($connection));
	$row_styles = mysqli_fetch_assoc($styles);
	$totalRows_styles = mysqli_num_rows($styles);
	
	do { 
		if (array_key_exists($row_styles['id'], $styles_selected)) $style[] = $row_styles['brewStyleGroup']."-".$row_styles['brewStyleNum']."-".$row_styles['brewStyle']; 
	} while ($row_styles = mysqli_fetch_assoc($styles));

	foreach (array_unique($style) as $style) {
		$style = explode("-",$style);
		$query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewCategorySort='%s' AND brewSubCategory='%s' AND brewReceived='1'", $prefix."brewing",  $style[0], $style[1]);
		$entry_count = mysqli_query($connection,$query_entry_count) or die (mysqli_error($connection));
		$row_entry_count = mysqli_fetch_assoc($entry_count);

		$query_score_count = sprintf("SELECT  COUNT(*) as 'count' FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND b.brewSubCategory='%s' AND a.eid = b.id AND a.scorePlace IS NOT NULL AND c.uid = b.brewBrewerID", $prefix."judging_scores", $prefix."brewing", $prefix."brewer", $style[0], $style[1]);
		$score_count = mysqli_query($connection,$query_score_count) or die (mysqli_error($connection));
		$row_score_count = mysqli_fetch_assoc($score_count);

		if (($row_entry_count['count'] > 0) && ($row_score_count['count'] > 0)) {

			$query_scores = sprintf("SELECT a.scorePlace, b.brewName, b.brewCategory, b.brewSubCategory, b.brewStyle, c.brewerLastName, c.brewerFirstName, c.brewerClubs FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND b.brewSubCategory='%s' AND a.eid = b.id  AND c.uid = b.brewBrewerID  AND (a.scorePlace IS NOT NULL OR a.scorePlace='') ORDER BY a.scorePlace", $prefix."judging_scores", $prefix."brewing", $prefix."brewer", $style[0], $style[1]);
			$scores = mysqli_query($connection,$query_scores) or die (mysqli_error($connection));
			$row_scores = mysqli_fetch_assoc($scores);
			$totalRows_scores = mysqli_num_rows($scores);

			do {

				$display_place = display_place($row_scores['scorePlace'],1);
				$brewer_name = truncate($row_scores['brewerFirstName']." ".$row_scores['brewerLastName'], $character_limit,"...");
				$entry_name = truncate(trim($row_scores['brewName']), $character_limit,"...");
				$subcategory = preg_replace('/[0-9]+/', '', $row_scores['brewSubCategory']);
				$style = strtoupper($row_scores['brewCategory']).$subcategory;
				$style_name = truncate($row_scores['brewStyle'],$character_limit,"...");

				if ($filter == "round") {

					$text = sprintf("\n%s\n%s\n%s\n'%s'",
					$display_place,
					$style_name,
					$brewer_name,
					$entry_name
					);

				}

				else {

					$text = sprintf("\n%s\n%s: %s\n%s\n'%s'",
					$display_place,
					$style,
					$style_name,
					$brewer_name,
					$entry_name
					);

				}

				$text = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$text);
				$pdf->Add_Label($text);

			} while ($row_scores = mysqli_fetch_assoc($scores));
		}
	}
} // end elseif ($_SESSION['prefsWinnerMethod'] == "2")

else { // Output by Table.

	do {

	$query_scores = sprintf("SELECT * FROM %s WHERE scoreTable='%s'", $prefix."judging_scores", $row_tables['id']);
	$query_scores .= " AND (scorePlace='1' OR scorePlace='2' OR scorePlace='3' OR scorePlace='4' OR scorePlace='5') ORDER BY scorePlace ASC";
	$scores = mysqli_query($connection,$query_scores) or die (mysqli_error($connection));
	$row_scores = mysqli_fetch_assoc($scores);
	$totalRows_scores = mysqli_num_rows($scores);

		do {
			$query_entries = sprintf("SELECT id,brewBrewerFirstName,brewBrewerLastName,brewName,brewStyle,brewCategorySort,brewSubCategory FROM %s WHERE id='%s'", $prefix."brewing", $row_scores['eid']);
			$entries = mysqli_query($connection,$query_entries) or die (mysqli_error($connection));
			$row_entries = mysqli_fetch_assoc($entries);

			$display_place = display_place($row_scores['scorePlace'],1);
			$table_name = truncate($row_tables['tableName'], ($character_limit - 3));
			$brewer_name = truncate($row_entries['brewBrewerFirstName']." ".$row_entries['brewBrewerLastName'],$character_limit,"...");
			$entry_name = truncate(trim($row_entries['brewName']), $character_limit,"...");
			$subcategory = preg_replace('/[0-9]+/', '', $row_entries['brewSubCategory']);
			$style_name = truncate($row_entries['brewStyle'],$character_limit,"...");

			if ($filter == "round") {

				$text = sprintf("\n%s\n%s\n%s\n'%s'\n%s",
					$display_place,
					$table_name,
					html_entity_decode($brewer_name),
					html_entity_decode($entry_name),
					$style_name
				);

			}

			else {

				$text = sprintf("\n%s\n%s\n%s\n'%s'\n%s",
					$display_place,
					$table_name,
					html_entity_decode($brewer_name),
					html_entity_decode($entry_name),
					$style_name
				);

			}

			$text = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$text);
			if ($display_place != "N/A") $pdf->Add_Label($text);

		} while ($row_scores = mysqli_fetch_assoc($scores));

	} while ($row_tables = mysqli_fetch_assoc($tables));

}

?>