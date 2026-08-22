<?php
/*
if (HOSTED) $styles_db_table = "bcoem_shared_styles";
else
*/
$styles_db_table = $prefix."styles";

$db_conn->orderBy('tableNumber', 'ASC');
$rows_tables = $db_conn->get($prefix."judging_tables");
$totalRows_tables = $db_conn->count;

$db_conn->orderBy('scoreType', 'ASC');
$db_conn->orderBy('scorePlace', 'ASC');
$rows_bos = $db_conn->get($prefix."judging_scores_bos");
$totalRows_bos = $db_conn->count;

if ($filter == "round") $character_limit = 18;
else $character_limit = 31;

$styles_selected = json_decode($_SESSION['prefsSelectedStyles'], true);

foreach ($rows_bos as $row_bos) {

	$db_conn->where('id', $row_bos['eid']);
	$row_entries = $db_conn->getOne($prefix."brewing", "id,brewBrewerFirstName,brewBrewerLastName,brewName,brewStyle,brewCategory,brewSubCategory");

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

}

if ($_SESSION['prefsWinnerMethod'] == "1") { // Output by Category

	if ($styleSet == "BJCP2025") {
		$query_styles = "SELECT * FROM ".$styles_db_table." WHERE (brewStyleVersion='BJCP2025' AND brewStyleType='2') OR (brewStyleVersion='BJCP2021' AND brewStyleType !='2') OR brewStyleOwn='custom' ORDER BY brewStyleGroup ASC";
		$rows_styles = $db_conn->rawQuery($query_styles);
	}
	else {
		$query_styles = "SELECT id,brewStyleGroup FROM ".$styles_db_table." WHERE (brewStyleVersion=? OR brewStyleOwn='custom') ORDER BY brewStyleGroup ASC";
		$rows_styles = $db_conn->rawQuery($query_styles, [$_SESSION['prefsStyleSet']]);
	}
	$totalRows_styles = $db_conn->count;

	foreach ($rows_styles as $row_styles) {
		if (array_key_exists($row_styles['id'], $styles_selected)) $style[] = $row_styles['brewStyleGroup'];
	}

	foreach (array_unique($style) as $style) {
		$db_conn->where('brewCategorySort', $style);
		$db_conn->where('brewReceived', '1');
		$row_entry_count = $db_conn->getOne($prefix."brewing", "COUNT(*) as 'count'");

		$query_score_count = "SELECT  COUNT(*) as 'count' FROM ".$prefix."judging_scores"." a, ".$prefix."brewing"." b, ".$prefix."brewer"." c WHERE b.brewCategorySort=? AND a.eid = b.id AND c.uid = b.brewBrewerID AND (a.scorePlace IS NOT NULL OR a.scorePlace='')";
		$row_score_count = $db_conn->rawQueryOne($query_score_count, [$style]);


		if (($row_entry_count['count'] > 0) && ($row_score_count['count'] > 0)) {

			$query_scores = "SELECT a.scorePlace, a.scoreEntry, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerClubs FROM ".$prefix."judging_scores"." a, ".$prefix."brewing"." b, ".$prefix."brewer"." c WHERE b.brewCategorySort=? AND a.eid = b.id AND c.uid = b.brewBrewerID AND (a.scorePlace IS NOT NULL OR a.scorePlace='') ORDER BY a.scorePlace";
			$rows_scores = $db_conn->rawQuery($query_scores, [$style]);
			$totalRows_scores = $db_conn->count;

			foreach ($rows_scores as $row_scores) {

			$display_place = display_place($row_scores['scorePlace'],1);
			$brewer_name = truncate($row_scores['brewerFirstName']." ".$row_scores['brewerLastName'], $character_limit,"...");
			$entry_name = truncate(trim($row_scores['brewName']), $character_limit,"...");
			$style = style_convert($row_scores['brewCategorySort'],1,$base_url);
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

			$text = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $text)));
			$pdf->Add_Label($text);

			}

		}
	}
}

elseif ($_SESSION['prefsWinnerMethod'] == "2") { // Output by sub-category

	if ($styleSet == "BJCP2025") {
		$query_styles = "SELECT * FROM ".$styles_db_table." WHERE (brewStyleVersion='BJCP2025' AND brewStyleType='2') OR (brewStyleVersion='BJCP2021' AND brewStyleType !='2') OR brewStyleOwn='custom' ORDER BY brewStyleGroup ASC";
		$rows_styles = $db_conn->rawQuery($query_styles);
	}
	else {
		$query_styles = "SELECT id,brewStyleGroup,brewStyleNum,brewStyle FROM ".$styles_db_table." WHERE (brewStyleVersion=? OR brewStyleOwn='custom') ORDER BY brewStyleGroup,brewStyleNum ASC";
		$rows_styles = $db_conn->rawQuery($query_styles, [$_SESSION['prefsStyleSet']]);
	}
	$totalRows_styles = $db_conn->count;

	foreach ($rows_styles as $row_styles) {
		if (array_key_exists($row_styles['id'], $styles_selected)) $style[] = $row_styles['brewStyleGroup']."-".$row_styles['brewStyleNum']."-".$row_styles['brewStyle'];
	}

	foreach (array_unique($style) as $style) {
		$style = explode("-",$style);
		$db_conn->where('brewCategorySort', $style[0]);
		$db_conn->where('brewSubCategory', $style[1]);
		$db_conn->where('brewReceived', '1');
		$row_entry_count = $db_conn->getOne($prefix."brewing", "COUNT(*) as 'count'");

		$query_score_count = "SELECT  COUNT(*) as 'count' FROM ".$prefix."judging_scores"." a, ".$prefix."brewing"." b, ".$prefix."brewer"." c WHERE b.brewCategorySort=? AND b.brewSubCategory=? AND a.eid = b.id AND a.scorePlace IS NOT NULL AND c.uid = b.brewBrewerID";
		$row_score_count = $db_conn->rawQueryOne($query_score_count, [$style[0], $style[1]]);

		if (($row_entry_count['count'] > 0) && ($row_score_count['count'] > 0)) {

			$query_scores = "SELECT a.scorePlace, b.brewName, b.brewCategory, b.brewSubCategory, b.brewStyle, c.brewerLastName, c.brewerFirstName, c.brewerClubs FROM ".$prefix."judging_scores"." a, ".$prefix."brewing"." b, ".$prefix."brewer"." c WHERE b.brewCategorySort=? AND b.brewSubCategory=? AND a.eid = b.id  AND c.uid = b.brewBrewerID  AND (a.scorePlace IS NOT NULL OR a.scorePlace='') ORDER BY a.scorePlace";
			$rows_scores = $db_conn->rawQuery($query_scores, [$style[0], $style[1]]);
			$totalRows_scores = $db_conn->count;

			foreach ($rows_scores as $row_scores) {

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

				$text = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $text)));
				$pdf->Add_Label($text);

			}
		}
	}
} // end elseif ($_SESSION['prefsWinnerMethod'] == "2")

else { // Output by Table.

	foreach ($rows_tables as $row_tables) {

	$query_scores = "SELECT * FROM ".$prefix."judging_scores WHERE scoreTable=?";
	$params_scores = [$row_tables['id']];
	$query_scores .= " AND (scorePlace='1' OR scorePlace='2' OR scorePlace='3' OR scorePlace='4' OR scorePlace='5') ORDER BY scorePlace ASC";
	$rows_scores = $db_conn->rawQuery($query_scores, $params_scores);
	$totalRows_scores = $db_conn->count;

		foreach ($rows_scores as $row_scores) {
			$db_conn->where('id', $row_scores['eid']);
			$row_entries = $db_conn->getOne($prefix."brewing", "id,brewBrewerFirstName,brewBrewerLastName,brewName,brewStyle,brewCategorySort,brewSubCategory");

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

			$text = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $text)));
			if ($display_place !== "N/A") $pdf->Add_Label($text);

		}

	}

}

?>