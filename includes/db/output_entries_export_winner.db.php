<?php
// BY TABLE
if ($_SESSION['prefsWinnerMethod'] == 0) {
	$query_scores = sprintf("SELECT eid,scorePlace FROM %s WHERE scoreTable='%s' AND scorePlace IS NOT NULL", $judging_scores_db_table, $row_sql['id']);
	if (SINGLE) $query_scores .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
	$query_scores .= " ORDER BY scorePlace ASC";
	$scores = mysqli_query($connection,$query_scores) or die (mysqli_error($connection));
	$row_scores = mysqli_fetch_assoc($scores);
	$totalRows_scores = mysqli_num_rows($scores);

	//echo $query_scores."<br>";
	if ($totalRows_scores > 0) {
		do {

			$query_entries = sprintf("SELECT id, brewBrewerID, brewCoBrewer, brewName, brewStyle, brewCategorySort, brewCategory, brewSubCategory, brewBrewerFirstName, brewBrewerLastName, brewJudgingNumber FROM %s WHERE id='%s'", $brewing_db_table, $row_scores['eid']);
			$entries = mysqli_query($connection,$query_entries) or die (mysqli_error($connection));
			$row_entries = mysqli_fetch_assoc($entries);

			$query_brewer = sprintf("SELECT id, brewerFirstName, brewerLastName, brewerClubs, brewerEmail, brewerAddress, brewerCity, brewerState, brewerZip, brewerCountry, brewerPhone1, brewerBreweryName, brewerBreweryTTB FROM $brewer_db_table WHERE uid='%s'", $row_entries['brewBrewerID']);
			$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
			$row_brewer = mysqli_fetch_assoc($brewer);

			if ($row_brewer['brewerCountry'] == "United States") $phone = format_phone_us($row_brewer['brewerPhone1']); else $phone = $row_brewer['brewerPhone1'];

			if ($_SESSION['prefsProEdition'] == 1) $a[] = array($row_sql['tableNumber'],$row_sql['tableName'],$row_entries['brewCategory'],$row_entries['brewSubCategory'],iconv("UTF-8", "ISO-8859-1//TRANSLIT",$row_entries['brewStyle']),$row_scores['scorePlace'],strtr($row_brewer['brewerLastName'],$html_remove),strtr($row_brewer['brewerFirstName'],$html_remove),$row_brewer['brewerBreweryName'],$row_brewer['brewerBreweryTTB'],$row_brewer['brewerEmail'],$row_brewer['brewerAddress'],$row_brewer['brewerCity'],$row_brewer['brewerState'],$row_brewer['brewerZip'],$row_brewer['brewerCountry'],$phone,strtr($row_entries['brewName'],$html_remove),$row_brewer['brewerClubs'],$row_entries['brewCoBrewer']);

			else $a[] = array($row_sql['tableNumber'],$row_sql['tableName'],$row_entries['brewCategory'],$row_entries['brewSubCategory'],iconv("UTF-8", "ISO-8859-1//TRANSLIT",$row_entries['brewStyle']),$row_scores['scorePlace'],strtr($row_brewer['brewerLastName'],$html_remove),strtr($row_brewer['brewerFirstName'],$html_remove),$row_brewer['brewerEmail'],$row_brewer['brewerAddress'],$row_brewer['brewerCity'],$row_brewer['brewerState'],$row_brewer['brewerZip'],$row_brewer['brewerCountry'],$phone,strtr($row_entries['brewName'],$html_remove),$row_brewer['brewerClubs'],$row_entries['brewCoBrewer']);

		} while ($row_scores = mysqli_fetch_assoc($scores));
	}
}

// BY CATEGORY
// @single
if ($_SESSION['prefsWinnerMethod'] == 1) {

	$z = styles_active(0);

	foreach (array_unique($z) as $style) {

		include (DB.'winners_category.db.php');

		//echo $row_score_count['count']."<br>";

		if ($row_score_count['count'] > 0) {

			$style_pad = sprintf("%02d", $style);

			if ($_SESSION['prefsStyleSet'] == "BA") $query_scores = sprintf("SELECT a.scoreTable, a.scorePlace, a.scoreEntry, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerEmail, c.brewerClubs, c.brewerAddress, c.brewerState, c.brewerCity, c.brewerZip, c.brewerPhone1, c.brewerCountry, c.brewerBreweryTTB, c.brewerBreweryName FROM %s a, %s b, %s c WHERE b.brewCategory='%s' AND a.eid = b.id AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style);

			else $query_scores = sprintf("SELECT a.scoreTable, a.scorePlace, a.scoreEntry, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerEmail, c.brewerClubs, c.brewerAddress, c.brewerState, c.brewerCity, c.brewerZip, c.brewerPhone1, c.brewerCountry, c.brewerBreweryTTB, c.brewerBreweryName FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND a.eid = b.id AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style_pad);

			$query_scores .= " AND a.scorePlace IS NOT NULL";
			$query_scores .= " ORDER BY b.brewCategory,a.scorePlace ASC";

			$scores = mysqli_query($connection,$query_scores) or die (mysqli_error($connection));
			$row_scores = mysqli_fetch_assoc($scores);
			$totalRows_scores = mysqli_num_rows($scores);

			if ($row_scores['brewerCountry'] == "United States") $phone = format_phone_us($row_scores['brewerPhone1']); else $phone = $row_scores['brewerPhone1'];

			do {
				$query_table_name = sprintf("SELECT tableName,tableNumber from %s WHERE id = '%s'",$judging_tables_db_table,$row_scores['scoreTable']);
				$table_name = mysqli_query($connection,$query_table_name) or die (mysqli_error($connection));
				$row_table_name = mysqli_fetch_assoc($table_name);

				if ($_SESSION['prefsProEdition'] == 1) $a[] = array($row_table_name['tableNumber'],strtr($row_table_name['tableName'],$html_remove),$row_scores['brewCategory'],$row_scores['brewSubCategory'],iconv("UTF-8", "ISO-8859-1//TRANSLIT",$row_scores['brewStyle']),$row_scores['scorePlace'],strtr($row_scores['brewerLastName'],$html_remove),strtr($row_scores['brewerFirstName'],$html_remove),$row_scores['brewerBreweryName'],$row_scores['brewerBreweryTTB'],$row_scores['brewerEmail'],$row_scores['brewerAddress'],$row_scores['brewerCity'],$row_scores['brewerState'],$row_scores['brewerZip'],$row_scores['brewerCountry'],$phone,strtr($row_scores['brewName'],$html_remove),$row_scores['brewerClubs'],$row_scores['brewCoBrewer']);

				else $a[] = array($row_table_name['tableNumber'],strtr($row_table_name['tableName'],$html_remove),$row_scores['brewCategory'],$row_scores['brewSubCategory'],iconv("UTF-8", "ISO-8859-1//TRANSLIT",$row_scores['brewStyle']),$row_scores['scorePlace'],strtr($row_scores['brewerLastName'],$html_remove),strtr($row_scores['brewerFirstName'],$html_remove),$row_scores['brewerEmail'],$row_scores['brewerAddress'],$row_scores['brewerCity'],$row_scores['brewerState'],$row_scores['brewerZip'],$row_scores['brewerCountry'],$phone,strtr($row_scores['brewName'],$html_remove),$row_scores['brewerClubs'],$row_scores['brewCoBrewer']);

			} while ($row_scores = mysqli_fetch_assoc($scores));
		}
	}
} // end if ($_SESSION['prefsWinnerMethod'] == 1)

// print_r($a); exit;

// BY SUB-CATEGORY
if ($_SESSION['prefsWinnerMethod'] == 2) {

	$b = styles_active(2);

	foreach (array_unique($b) as $style) {

		$style = explode("^",$style);

		include (DB.'winners_subcategory.db.php');

		if ($row_entry_count['count'] > 0) {

			if ($_SESSION['prefsStyleSet'] != "BA") $query_scores = sprintf("SELECT a.scoreTable, a.scorePlace, a.scoreEntry, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerClubs, c.brewerAddress, c.brewerState, c.brewerCity, c.brewerZip, c.brewerPhone1, c.brewerCountry, c.brewerEmail, c.brewerBreweryTTB, c.brewerBreweryName FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND b.brewSubCategory='%s' AND a.eid = b.id  AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style[0], $style[1]);

			else $query_scores = sprintf("SELECT a.scoreTable, a.scorePlace, a.scoreEntry, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerClubs, c.brewerAddress, c.brewerState, c.brewerCity, c.brewerZip, c.brewerPhone1, c.brewerCountry, c.brewerEmail, c.brewerBreweryTTB, c.brewerBreweryName FROM %s a, %s b, %s c WHERE b.brewSubCategory='%s' AND a.eid = b.id  AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style[1]);

			/*
			if ($_SESSION['prefsStyleSet'] != "BA") $query_scores = sprintf("SELECT a.scoreTable, a.scorePlace, a.scoreEntry, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerClubs, c.brewerAddress, c.brewerState, c.brewerCity, c.brewerZip, c.brewerPhone1, c.brewerCountry, c.brewerEmail FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND b.brewSubCategory='%s' AND a.eid = b.id  AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style[0], $style[1]);
			else $query_scores = sprintf("SELECT a.scoreTable, a.scorePlace, a.scoreEntry, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerClubs, c.brewerAddress, c.brewerState, c.brewerCity, c.brewerZip, c.brewerPhone1, c.brewerCountry, c.brewerEmail FROM %s a, %s b, %s c WHERE b.brewSubCategory='%s' AND a.eid = b.id  AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style[1]);
			*/

			$query_scores .= " AND a.scorePlace IS NOT NULL";
			if ($_SESSION['prefsStyleSet'] == "BA") $query_scores .= " ORDER BY b.brewStyle,a.scorePlace ASC";
			else $query_scores .= " ORDER BY b.brewCategory,b.brewSubCategory,a.scorePlace";

			//echo $query_scores;
			$scores = mysqli_query($connection,$query_scores) or die (mysqli_error($connection));
			$row_scores = mysqli_fetch_assoc($scores);
			$totalRows_scores = mysqli_num_rows($scores);

			if ($row_scores['brewerCountry'] == "United States") $phone = format_phone_us($row_scores['brewerPhone1']); else $phone = $row_scores['brewerPhone1'];

			do {

				$query_table_name = sprintf("SELECT tableName,tableNumber from %s WHERE id = '%s'",$judging_tables_db_table,$row_scores['scoreTable']);
				$table_name = mysqli_query($connection,$query_table_name) or die (mysqli_error($connection));
				$row_table_name = mysqli_fetch_assoc($table_name);

				if (!empty($row_scores['scorePlace'])) {

					if ($_SESSION['prefsProEdition'] == 1)  $a[] = array($row_table_name['tableNumber'],strtr($row_table_name['tableName'],$html_remove),$row_scores['brewCategory'],$row_scores['brewSubCategory'],iconv("UTF-8", "ISO-8859-1//TRANSLIT",$row_scores['brewStyle']),$row_scores['scorePlace'],strtr($row_scores['brewerLastName'],$html_remove),strtr($row_scores['brewerFirstName'],$html_remove),$row_scores['brewerBreweryName'],$row_scores['brewerBreweryTTB'],$row_scores['brewerEmail'],$row_scores['brewerAddress'],$row_scores['brewerCity'],$row_scores['brewerState'],$row_scores['brewerZip'],$row_scores['brewerCountry'],$row_scores['brewerPhone1'],strtr($row_scores['brewName'],$html_remove),$row_scores['brewerClubs'],$row_scores['brewCoBrewer']);

					else $a[] = array($row_table_name['tableNumber'],strtr($row_table_name['tableName'],$html_remove),$row_scores['brewCategory'],$row_scores['brewSubCategory'],iconv("UTF-8", "ISO-8859-1//TRANSLIT",$row_scores['brewStyle']),$row_scores['scorePlace'],strtr($row_scores['brewerLastName'],$html_remove),strtr($row_scores['brewerFirstName'],$html_remove),$row_scores['brewerEmail'],$row_scores['brewerAddress'],$row_scores['brewerCity'],$row_scores['brewerState'],$row_scores['brewerZip'],$row_scores['brewerCountry'],$row_scores['brewerPhone1'],strtr($row_scores['brewName'],$html_remove),$row_scores['brewerClubs'],$row_scores['brewCoBrewer']);
				}

			} while ($row_scores = mysqli_fetch_assoc($scores));
		}
	}
} // end if ($_SESSION['prefsWinnerMethod'] == 2)
?>