<?php
// BY TABLE

if ($winner_method == 0) {

	$query_scores = "SELECT a.eid, a.scorePlace, b.id, b.brewBrewerID, b.brewCoBrewer, b.brewName, b.brewStyle, b.brewCategorySort, b.brewCategory, b.brewSubCategory, b.brewBrewerFirstName, b.brewBrewerLastName, b.brewJudgingNumber, c.uid, c.brewerFirstName, c.brewerLastName, c.brewerClubs, c.brewerEmail, c.brewerAddress, c.brewerCity, c.brewerState, c.brewerZip, c.brewerCountry, c.brewerPhone1, c.brewerBreweryName, c.brewerBreweryInfo FROM ".$prefix."judging_scores".$archive_suffix." a, ".$prefix."brewing".$archive_suffix." b, ".$prefix."brewer".$archive_suffix." c WHERE a.scoreTable=? AND a.eid = b.id AND b.brewBrewerID = c.uid";
	$params_scores = [$row_sql['id']];

	if (SINGLE) { $query_scores .= " AND comp_id=?"; $params_scores[] = $_SESSION['comp_id']; }
	$query_scores .= " ORDER BY a.scorePlace ASC";
	$rows_scores = $db_conn->rawQuery($query_scores, $params_scores);
	$totalRows_scores = $db_conn->count;

	if ($totalRows_scores > 0) {

		foreach ($rows_scores as $row_scores) {

			if ((isset($row_scores['scorePlace'])) && (!empty($row_scores['scorePlace']))) {

				if ($row_scores['brewerCountry'] == "United States") $phone = format_phone_us($row_scores['brewerPhone1']); else $phone = $row_scores['brewerPhone1'];

				if ($pro_edition == 1) {

					$brewerBreweryTTB = "";
					$brewerBreweryProd = "";

					if (!empty($row_scores['brewerBreweryInfo'])) {
						$ttb = json_decode($row_scores['brewerBreweryInfo'],true);
						if ((isset($ttb['TTB'])) && (!empty($ttb['TTB']))) $brewerBreweryTTB = convert_to_entities($ttb['TTB']);
						if ((isset($ttb['Production'])) && (!empty($ttb['Production']))) $brewerBreweryProd = convert_to_entities($ttb['Production']);
					}

					$a[] = [
						$row_sql['tableNumber'],
						convert_to_entities($row_sql['tableName']),
						$row_scores['brewJudgingNumber'],
						$row_scores['brewCategory'],
						$row_scores['brewSubCategory'],
						convert_to_entities($row_scores['brewStyle']),
						$row_scores['scorePlace'],
						convert_to_entities($row_scores['brewerLastName']),
						convert_to_entities($row_scores['brewerFirstName']),
						convert_to_entities($row_scores['brewerBreweryName']),
						$brewerBreweryTTB,
						$brewerBreweryProd,
						$row_scores['brewerEmail'],
						convert_to_entities($row_scores['brewerAddress']),
						convert_to_entities($row_scores['brewerCity']),
						convert_to_entities($row_scores['brewerState']),
						convert_to_entities($row_scores['brewerZip']),
						convert_to_entities($row_scores['brewerCountry']),
						$phone,
						convert_to_entities($row_scores['brewName']),
						$row_scores['brewerClubs'],
						convert_to_entities($row_scores['brewCoBrewer'])
					];
				}

				else {

				$bos_for_entry = 0;
				$pro_am_for_entry = "";
				$bestbrewer_place = 0;

					if ($tb == "circuit") {

						if (array_key_exists($row_scores['id'],$bos_score_arr)) {
							$bos_for_entry = $bos_score_arr[$row_scores['id']];
						}

						if (array_key_exists($row_scores['id'],$pro_am_arr)) {
							$pro_am_for_entry = $pro_am_arr[$row_scores['id']];
						}

						if (array_key_exists($row_scores['uid'],$bb_circuit_array)) {
							$bestbrewer_place = $bb_circuit_array[$row_scores['uid']];
						}

						$a[] = [
							$row_sql['tableNumber'],
							convert_to_entities($row_sql['tableName']),
							$row_scores['brewJudgingNumber'],
							$row_scores['brewCategory'],
							$row_scores['brewSubCategory'],
							convert_to_entities($row_scores['brewStyle']),
							$row_scores['scorePlace'],
							convert_to_entities($row_scores['brewerLastName']),
							convert_to_entities($row_scores['brewerFirstName']),
							$row_scores['brewerEmail'],
							convert_to_entities($row_scores['brewerAddress']),
							convert_to_entities($row_scores['brewerCity']),
							convert_to_entities($row_scores['brewerState']),
							convert_to_entities($row_scores['brewerZip']),
							convert_to_entities($row_scores['brewerCountry']),
							$phone,
							convert_to_entities($row_scores['brewName']),
							convert_to_entities($row_scores['brewerClubs']),
							convert_to_entities($row_scores['brewCoBrewer']),
							$bos_for_entry,
							$pro_am_for_entry,
							$totalRows_scores,
							$bestbrewer_place
						];

					}

					if ($tb == "winners") {
						$a[] = [
							$row_sql['tableNumber'],
							convert_to_entities($row_sql['tableName']),
							$row_scores['brewCategory'],
							$row_scores['brewSubCategory'],
							convert_to_entities($row_scores['brewStyle']),
							$row_scores['scorePlace'],
							convert_to_entities($row_scores['brewerLastName']),
							convert_to_entities($row_scores['brewerFirstName']),
							$row_scores['brewerEmail'],
							convert_to_entities($row_scores['brewerAddress']),
							convert_to_entities($row_scores['brewerCity']),
							convert_to_entities($row_scores['brewerState']),
							convert_to_entities($row_scores['brewerZip']),
							convert_to_entities($row_scores['brewerCountry']),
							$phone,
							convert_to_entities($row_scores['brewName']),
							convert_to_entities($row_scores['brewerClubs']),
							convert_to_entities($row_scores['brewCoBrewer'])
						];
					}
				}

			}

		}
	}
}

// BY CATEGORY
// @single
if ($winner_method == 1) {

	$a = [];

	if ($tb == "circuit") $a[] = [$label_table,$label_name,$label_judging_number,$label_category,$label_subcategory,$label_style,$label_place,$label_last_name,$label_first_name,$label_email,$label_address,$label_city,$label_state_province,$label_zip,$label_country,$label_phone,$label_entry_name,$label_club,$label_cobrewer,$label_bos,$label_pro_am,$label_medal_count,$label_best_brewer_place];

	else {

		if ($pro_edition == 1) $a[] = [$label_table,$label_name,$label_category,$label_style,$label_name,$label_place,$label_last_name,$label_first_name,$label_organization,$label_ttb,$label_yearly_volume,$label_email,$label_address,$label_city,$label_state_province,$label_zip,$label_country,$label_phone,$label_entry_name];

		else $a[] = [$label_table,$label_name,$label_category,$label_subcategory,$label_style,$label_place,$label_last_name,$label_first_name,$label_email,$label_address,$label_city,$label_state_province,$label_zip,$label_country,$label_phone,$label_entry_name,$label_club,$label_cobrewer];
	}	

	$z = styles_active(0,$archive_suffix);
	$z = array_unique($z);

	foreach ($z as $style) {

		include (DB.'winners_category.db.php');

		if ($row_score_count['count'] > 0) {

			$style_pad = sprintf("%02d", $style);

			if ($winner_style_set == "BA") {
				$query_scores = "SELECT a.scoreTable, a.scorePlace, a.scoreEntry, b.id, b.brewJudgingNumber, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.uid, c.brewerLastName, c.brewerFirstName, c.brewerEmail, c.brewerClubs, c.brewerAddress, c.brewerState, c.brewerCity, c.brewerZip, c.brewerPhone1, c.brewerCountry, c.brewerBreweryInfo, c.brewerBreweryName FROM ".$prefix."judging_scores".$archive_suffix." a, ".$prefix."brewing".$archive_suffix." b, ".$prefix."brewer".$archive_suffix." c WHERE b.brewCategory=? AND a.eid = b.id AND c.uid = b.brewBrewerID";
				$params_scores = [$style];
			}

			else {
				$query_scores = "SELECT a.scoreTable, a.scorePlace, a.scoreEntry, b.id, b.brewJudgingNumber, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.uid, c.brewerLastName, c.brewerFirstName, c.brewerEmail, c.brewerClubs, c.brewerAddress, c.brewerState, c.brewerCity, c.brewerZip, c.brewerPhone1, c.brewerCountry, c.brewerBreweryInfo, c.brewerBreweryName FROM ".$prefix."judging_scores".$archive_suffix." a, ".$prefix."brewing".$archive_suffix." b, ".$prefix."brewer".$archive_suffix." c WHERE b.brewCategorySort=? AND a.eid = b.id AND c.uid = b.brewBrewerID";
				$params_scores = [$style_pad];
			}

			$query_scores .= " AND a.scorePlace IS NOT NULL";
			$query_scores .= " ORDER BY b.brewCategory,a.scorePlace ASC";

			$rows_scores = $db_conn->rawQuery($query_scores, $params_scores);
			$totalRows_scores = $db_conn->count;

			foreach ($rows_scores as $row_scores) {

				$db_conn->where('id', $row_scores['scoreTable']);
				$row_table_name = $db_conn->getOne($prefix."judging_tables".$archive_suffix, "tableName,tableNumber");

				if ($row_scores['brewerCountry'] == "United States") $phone = format_phone_us($row_scores['brewerPhone1']); else $phone = $row_scores['brewerPhone1'];

				if ($pro_edition == 1) {

					$brewerBreweryTTB = "";
					$brewerBreweryProd = "";

					if (!empty($row_scores['brewerBreweryInfo'])) {
						$ttb = json_decode($row_scores['brewerBreweryInfo'],true);
						if ((isset($ttb['TTB'])) && (!empty($ttb['TTB']))) $brewerBreweryTTB = convert_to_entities($ttb['TTB']);
						if ((isset($ttb['Production'])) && (!empty($ttb['Production']))) $brewerBreweryProd = convert_to_entities($ttb['Production']);
					}

					$a[] = [

						$row_table_name['tableNumber'],
						convert_to_entities($row_table_name['tableName']),
						$row_scores['brewCategory'],
						$row_scores['brewSubCategory'],
						convert_to_entities($row_scores['brewStyle']),
						$row_scores['scorePlace'],
						convert_to_entities($row_scores['brewerLastName']),
						convert_to_entities($row_scores['brewerFirstName']),
						convert_to_entities($row_scores['brewerBreweryName']),
						$brewerBreweryTTB,
						$brewerBreweryProd,
						convert_to_entities($row_scores['brewerEmail']),
						convert_to_entities($row_scores['brewerAddress']),
						convert_to_entities($row_scores['brewerCity']),
						convert_to_entities($row_scores['brewerState']),
						convert_to_entities($row_scores['brewerZip']),
						convert_to_entities($row_scores['brewerCountry']),
						$phone,
						convert_to_entities($row_scores['brewName']),
						convert_to_entities($row_scores['brewerClubs']),
						convert_to_entities($row_scores['brewCoBrewer'])
					];
				}

				else {

					$bos_for_entry = 0;
					$pro_am_for_entry = "";
					$bestbrewer_place = 0;

					if ($tb == "circuit") {

						if (array_key_exists($row_scores['id'],$bos_score_arr)) {
							$bos_for_entry = $bos_score_arr[$row_scores['id']];
						}

						if (array_key_exists($row_scores['id'],$pro_am_arr)) {
							$pro_am_for_entry = $pro_am_arr[$row_scores['id']];
						}

						if (array_key_exists($row_scores['uid'],$bb_circuit_array)) {
							$bestbrewer_place = $bb_circuit_array[$row_scores['uid']];
						}

						$a[] = [
							$row_sql['tableNumber'],
							convert_to_entities($row_table_name['tableName']),
							$row_scores['brewJudgingNumber'],
							$row_scores['brewCategory'],
							$row_scores['brewSubCategory'],
							convert_to_entities($row_scores['brewStyle']),
							$row_scores['scorePlace'],
							convert_to_entities($row_scores['brewerLastName']),
							convert_to_entities($row_scores['brewerFirstName']),
							$row_scores['brewerEmail'],
							convert_to_entities($row_scores['brewerAddress']),
							convert_to_entities($row_scores['brewerCity']),
							convert_to_entities($row_scores['brewerState']),
							convert_to_entities($row_scores['brewerZip']),
							convert_to_entities($row_scores['brewerCountry']),
							$phone,
							convert_to_entities($row_scores['brewName']),
							convert_to_entities($row_scores['brewerClubs']),
							convert_to_entities($row_scores['brewCoBrewer']),
							$bos_for_entry,
							$pro_am_for_entry,
							$totalRows_scores,
							$bestbrewer_place
						];

					}

					if ($tb == "winners") {

						$a[] = [
							$row_table_name['tableNumber'],
							convert_to_entities($row_table_name['tableName']),
							$row_scores['brewCategory'],
							$row_scores['brewSubCategory'],
							convert_to_entities($row_scores['brewStyle']),
							$row_scores['scorePlace'],
							convert_to_entities($row_scores['brewerLastName']),
							convert_to_entities($row_scores['brewerFirstName']),
							convert_to_entities($row_scores['brewerEmail']),
							convert_to_entities($row_scores['brewerAddress']),
							convert_to_entities($row_scores['brewerCity']),
							convert_to_entities($row_scores['brewerState']),
							convert_to_entities($row_scores['brewerZip']),
							convert_to_entities($row_scores['brewerCountry']),
							$phone,
							convert_to_entities($row_scores['brewName']),
							convert_to_entities($row_scores['brewerClubs']),
							convert_to_entities($row_scores['brewCoBrewer'])
						];

					}

				}				

			}

		}

	}

} // end if ($winner_method == 1)

// BY SUB-CATEGORY
if ($winner_method == 2) {

	$a = [];

	if ($tb == "circuit") $a[] = [$label_table,$label_name,$label_judging_number,$label_category,$label_subcategory,$label_style,$label_place,$label_last_name,$label_first_name,$label_email,$label_address,$label_city,$label_state_province,$label_zip,$label_country,$label_phone,$label_entry_name,$label_club,$label_cobrewer,$label_bos,$label_pro_am,$label_medal_count,$label_best_brewer_place];

	else {

		if ($pro_edition == 1) $a[] = [$label_table,$label_name,$label_category,$label_style,$label_name,$label_place,$label_last_name,$label_first_name,$label_organization,$label_ttb,$label_yearly_volume,$label_email,$label_address,$label_city,$label_state_province,$label_zip,$label_country,$label_phone,$label_entry_name];

		else $a[] = [$label_table,$label_name,$label_category,$label_subcategory,$label_style,$label_place,$label_last_name,$label_first_name,$label_email,$label_address,$label_city,$label_state_province,$label_zip,$label_country,$label_phone,$label_entry_name,$label_club,$label_cobrewer];
	}

	$b = styles_active(2,$archive_suffix);

	foreach (array_unique($b) as $style) {

		$style = explode("^",$style);

		include (DB.'winners_subcategory.db.php');

		if ($row_entry_count['count'] > 0) {

			// Note: the non-BA branch below concatenates $archive_suffix onto the brewer table name
			// TWICE (pre-existing bug, e.g. "..._brewer_2024_2024" instead of "..._brewer_2024") —
			// preserved exactly since this pass only parameterizes queries, it doesn't fix logic.
			if ($_SESSION['prefsStyleSet'] != "BA") {
				$query_scores = "SELECT a.scoreTable, a.scorePlace, a.scoreEntry, b.id, b.brewJudgingNumber, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.uid, c.brewerLastName, c.brewerFirstName, c.brewerEmail, c.brewerClubs, c.brewerAddress, c.brewerState, c.brewerCity, c.brewerZip, c.brewerPhone1, c.brewerCountry, c.brewerBreweryInfo, c.brewerBreweryName FROM ".$prefix."judging_scores".$archive_suffix." a, ".$prefix."brewing".$archive_suffix." b, ".$prefix."brewer".$archive_suffix." c WHERE b.brewCategorySort=? AND b.brewSubCategory=? AND a.eid = b.id  AND c.uid = b.brewBrewerID";
				$params_scores = [$style[0], $style[1]];
			}

			else {
				$query_scores = "SELECT a.scoreTable, a.scorePlace, a.scoreEntry, b.id, b.brewJudgingNumber, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.uid, c.brewerLastName, c.brewerFirstName, c.brewerEmail, c.brewerClubs, c.brewerAddress, c.brewerState, c.brewerCity, c.brewerZip, c.brewerPhone1, c.brewerCountry, c.brewerBreweryInfo, c.brewerBreweryName FROM ".$prefix."judging_scores".$archive_suffix." a, ".$prefix."brewing".$archive_suffix." b, ".$prefix."brewer".$archive_suffix.$archive_suffix." c WHERE b.brewSubCategory=? AND a.eid = b.id  AND c.uid = b.brewBrewerID";
				$params_scores = [$style[1]];
			}

			$query_scores .= " AND a.scorePlace IS NOT NULL";
			if ($_SESSION['prefsStyleSet'] == "BA") $query_scores .= " ORDER BY b.brewStyle,a.scorePlace ASC";
			else $query_scores .= " ORDER BY b.brewCategory,b.brewSubCategory,a.scorePlace";

			// echo $query_scores."<br><br>";
			$rows_scores = $db_conn->rawQuery($query_scores, $params_scores);
			$totalRows_scores = $db_conn->count;

			foreach ($rows_scores as $row_scores) {

				if ((isset($row_scores['scoreTable'])) && (!empty($row_scores['scoreTable']))) {
					$db_conn->where('id', $row_scores['scoreTable']);
					$row_table_name = $db_conn->getOne($prefix."judging_tables".$archive_suffix, "tableName,tableNumber");
				}

				if (!empty($row_scores['scorePlace'])) {

					if ($row_scores['brewerCountry'] == "United States") $phone = format_phone_us($row_scores['brewerPhone1']); 
					else $phone = $row_scores['brewerPhone1'];

					if ($pro_edition == 1)  {

						$brewerBreweryTTB = "";
						$brewerBreweryProd = "";

						if (!empty($row_scores['brewerBreweryInfo'])) {
							$ttb = json_decode($row_scores['brewerBreweryInfo'],true);
							if ((isset($ttb['TTB'])) && (!empty($ttb['TTB']))) $brewerBreweryTTB = convert_to_entities($ttb['TTB']);
							if ((isset($ttb['Production'])) && (!empty($ttb['Production']))) $brewerBreweryProd = convert_to_entities($ttb['Production']);
						}

						$a[] = [
							$row_table_name['tableNumber'],
							convert_to_entities($row_table_name['tableName']),
							$row_scores['brewCategory'],
							$row_scores['brewSubCategory'],
							convert_to_entities($row_scores['brewStyle']),
							$row_scores['scorePlace'],
							convert_to_entities($row_scores['brewerLastName']),
							convert_to_entities($row_scores['brewerFirstName']),
							convert_to_entities($row_scores['brewerBreweryName']),
							$brewerBreweryTTB,
							$brewerBreweryProd,
							convert_to_entities($row_scores['brewerEmail']),
							convert_to_entities($row_scores['brewerAddress']),
							convert_to_entities($row_scores['brewerCity']),
							convert_to_entities($row_scores['brewerState']),
							convert_to_entities($row_scores['brewerZip']),
							convert_to_entities($row_scores['brewerCountry']),
							$phone,
							convert_to_entities($row_scores['brewName']),
							convert_to_entities($row_scores['brewerClubs']),
							convert_to_entities($row_scores['brewCoBrewer'])
						];

					}

					else {

						if ($tb == "circuit") {

							$bos_for_entry = 0;
							$pro_am_for_entry = "";
							$bestbrewer_place = 0;

							if (array_key_exists($row_scores['id'],$bos_score_arr)) {
								$bos_for_entry = $bos_score_arr[$row_scores['id']];
							}

							if (array_key_exists($row_scores['id'],$pro_am_arr)) {
								$pro_am_for_entry = $pro_am_arr[$row_scores['id']];
							}

							if (array_key_exists($row_scores['uid'],$bb_circuit_array)) {
								$bestbrewer_place = $bb_circuit_array[$row_scores['uid']];
							}

							$a[] = [
								$row_sql['tableNumber'],
								convert_to_entities($row_table_name['tableName']),
								$row_scores['brewJudgingNumber'],
								$row_scores['brewCategory'],
								$row_scores['brewSubCategory'],
								convert_to_entities($row_scores['brewStyle']),
								$row_scores['scorePlace'],
								convert_to_entities($row_scores['brewerLastName']),
								convert_to_entities($row_scores['brewerFirstName']),
								$row_scores['brewerEmail'],
								convert_to_entities($row_scores['brewerAddress']),
								convert_to_entities($row_scores['brewerCity']),
								convert_to_entities($row_scores['brewerState']),
								convert_to_entities($row_scores['brewerZip']),
								convert_to_entities($row_scores['brewerCountry']),
								$phone,
								convert_to_entities($row_scores['brewName']),
								convert_to_entities($row_scores['brewerClubs']),
								convert_to_entities($row_scores['brewCoBrewer']),
								$bos_for_entry,
								$pro_am_for_entry,
								$totalRows_scores,
								$bestbrewer_place
							];

						}

						if ($tb == "winners") {
							$a[] = [
								$row_table_name['tableNumber'],
								convert_to_entities($row_table_name['tableName']),
								$row_scores['brewCategory'],
								$row_scores['brewSubCategory'],
								convert_to_entities($row_scores['brewStyle']),
								$row_scores['scorePlace'],
								convert_to_entities($row_scores['brewerLastName']),
								convert_to_entities($row_scores['brewerFirstName']),
								convert_to_entities($row_scores['brewerEmail']),
								convert_to_entities($row_scores['brewerAddress']),
								convert_to_entities($row_scores['brewerCity']),
								convert_to_entities($row_scores['brewerState']),
								convert_to_entities($row_scores['brewerZip']),
								convert_to_entities($row_scores['brewerCountry']),
								$phone,
								convert_to_entities($row_scores['brewName']),
								convert_to_entities($row_scores['brewerClubs']),
								convert_to_entities($row_scores['brewCoBrewer'])
							];
						}
					}
				}

			}
		}
	}
} // end if ($winner_method == 2)

?>