<?php
// BY TABLE

if ($winner_method == 0) {

	$query_scores = sprintf("SELECT a.eid, a.scorePlace, b.id, b.brewBrewerID, b.brewCoBrewer, b.brewName, b.brewStyle, b.brewCategorySort, b.brewCategory, b.brewSubCategory, b.brewBrewerFirstName, b.brewBrewerLastName, b.brewJudgingNumber, c.uid, c.brewerFirstName, c.brewerLastName, c.brewerClubs, c.brewerEmail, c.brewerAddress, c.brewerCity, c.brewerState, c.brewerZip, c.brewerCountry, c.brewerPhone1, c.brewerBreweryName, c.brewerBreweryTTB FROM %s a, %s b, %s c WHERE a.scoreTable='%s' AND a.eid = b.id AND b.brewBrewerID = c.uid", $prefix."judging_scores".$archive_suffix, $prefix."brewing".$archive_suffix, $prefix."brewer".$archive_suffix, $row_sql['id']);

	if (SINGLE) $query_scores .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
	$query_scores .= " ORDER BY a.scorePlace ASC";
	$scores = mysqli_query($connection,$query_scores) or die (mysqli_error($connection));
	$row_scores = mysqli_fetch_assoc($scores);
	$totalRows_scores = mysqli_num_rows($scores);

	if ($totalRows_scores > 0) {
		 
		do {

			if ((isset($row_scores['scorePlace'])) && (!empty($row_scores['scorePlace']))) {

				if ($row_scores['brewerCountry'] == "United States") $phone = format_phone_us($row_scores['brewerPhone1']); else $phone = $row_scores['brewerPhone1'];

				if ($pro_edition == 1) {
					$a[] = array(
						$row_sql['tableNumber'],
						html_entity_decode($row_sql['tableName']),
						$row_scores['brewJudgingNumber'],
						$row_scores['brewCategory'],
						$row_scores['brewSubCategory'],
						iconv("UTF-8", "ISO-8859-1//TRANSLIT",html_entity_decode($row_scores['brewStyle'])),
						$row_scores['scorePlace'],
						html_entity_decode($row_scores['brewerLastName']),
						html_entity_decode($row_scores['brewerFirstName']),
						html_entity_decode($row_scores['brewerBreweryName']),
						$row_scores['brewerBreweryTTB'],
						$row_scores['brewerEmail'],
						html_entity_decode($row_scores['brewerAddress']),
						html_entity_decode($row_scores['brewerCity']),
						html_entity_decode($row_scores['brewerState']),
						html_entity_decode($row_scores['brewerZip']),
						html_entity_decode($row_scores['brewerCountry']),
						$phone,
						html_entity_decode($row_scores['brewName']),
						$row_scores['brewerClubs'],
						html_entity_decode($row_scores['brewCoBrewer'])
					);
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

						$a[] = array(
							$row_sql['tableNumber'],
							html_entity_decode($row_sql['tableName']),
							$row_scores['brewJudgingNumber'],
							$row_scores['brewCategory'],
							$row_scores['brewSubCategory'],
							iconv("UTF-8", "ISO-8859-1//TRANSLIT",html_entity_decode($row_scores['brewStyle'])),
							$row_scores['scorePlace'],
							html_entity_decode($row_scores['brewerLastName']),
							html_entity_decode($row_scores['brewerFirstName']),
							$row_scores['brewerEmail'],
							html_entity_decode($row_scores['brewerAddress']),
							html_entity_decode($row_scores['brewerCity']),
							html_entity_decode($row_scores['brewerState']),
							html_entity_decode($row_scores['brewerZip']),
							html_entity_decode($row_scores['brewerCountry']),
							$phone,
							html_entity_decode($row_scores['brewName']),
							html_entity_decode($row_scores['brewerClubs']),
							html_entity_decode($row_scores['brewCoBrewer']),
							$bos_for_entry,
							$pro_am_for_entry,
							$totalRows_scores,
							$bestbrewer_place
						);
						
					}

					if ($tb == "winners") {
						$a[] = array(
							$row_sql['tableNumber'],
							html_entity_decode($row_sql['tableName']),
							$row_scores['brewCategory'],
							$row_scores['brewSubCategory'],
							iconv("UTF-8", "ISO-8859-1//TRANSLIT",html_entity_decode($row_scores['brewStyle'])),
							$row_scores['scorePlace'],
							html_entity_decode($row_scores['brewerLastName']),
							html_entity_decode($row_scores['brewerFirstName']),
							$row_scores['brewerEmail'],
							html_entity_decode($row_scores['brewerAddress']),
							html_entity_decode($row_scores['brewerCity']),
							html_entity_decode($row_scores['brewerState']),
							html_entity_decode($row_scores['brewerZip']),
							html_entity_decode($row_scores['brewerCountry']),
							$phone,
							html_entity_decode($row_scores['brewName']),
							html_entity_decode($row_scores['brewerClubs']),
							html_entity_decode($row_scores['brewCoBrewer'])
						);
					}
				}

			}		

		} while ($row_scores = mysqli_fetch_assoc($scores));
	}
}

// BY CATEGORY
// @single
if ($winner_method == 1) {
	
	$a = array();
	
	if ($tb == "circuit") $a[] = array($label_table,$label_name,$label_judging_number,$label_category,$label_subcategory,$label_style,$label_place,$label_last_name,$label_first_name,$label_email,$label_address,$label_city,$label_state_province,$label_zip,$label_country,$label_phone,$label_entry_name,$label_club,$label_cobrewer,$label_bos,$label_pro_am,$label_medal_count,$label_best_brewer_place);
	
	else {
		
		if ($pro_edition == 1) $a[] = array($label_table,$label_name,$label_category,$label_style,$label_name,$label_place,$label_last_name,$label_first_name,$label_organization,$label_ttb,$label_email,$label_address,$label_city,$label_state_province,$label_zip,$label_country,$label_phone,$label_entry_name,$label_club,$label_cobrewer);

		else $a[] = array($label_table,$label_name,$label_category,$label_subcategory,$label_style,$label_place,$label_last_name,$label_first_name,$label_email,$label_address,$label_city,$label_state_province,$label_zip,$label_country,$label_phone,$label_entry_name,$label_club,$label_cobrewer);
	}	

	$z = styles_active(0,$archive_suffix);
	$z = array_unique($z);

	foreach ($z as $style) {

		include (DB.'winners_category.db.php');

		if ($row_score_count['count'] > 0) {

			$style_pad = sprintf("%02d", $style);

			if ($winner_style_set == "BA") $query_scores = sprintf("SELECT a.scoreTable, a.scorePlace, a.scoreEntry, b.id, b.brewJudgingNumber, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.uid, c.brewerLastName, c.brewerFirstName, c.brewerEmail, c.brewerClubs, c.brewerAddress, c.brewerState, c.brewerCity, c.brewerZip, c.brewerPhone1, c.brewerCountry, c.brewerBreweryTTB, c.brewerBreweryName FROM %s a, %s b, %s c WHERE b.brewCategory='%s' AND a.eid = b.id AND c.uid = b.brewBrewerID", $prefix."judging_scores".$archive_suffix, $prefix."brewing".$archive_suffix, $prefix."brewer".$archive_suffix, $style);

			else $query_scores = sprintf("SELECT a.scoreTable, a.scorePlace, a.scoreEntry, b.id, b.brewJudgingNumber, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.uid, c.brewerLastName, c.brewerFirstName, c.brewerEmail, c.brewerClubs, c.brewerAddress, c.brewerState, c.brewerCity, c.brewerZip, c.brewerPhone1, c.brewerCountry, c.brewerBreweryTTB, c.brewerBreweryName FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND a.eid = b.id AND c.uid = b.brewBrewerID", $prefix."judging_scores".$archive_suffix, $prefix."brewing".$archive_suffix, $prefix."brewer".$archive_suffix, $style_pad);

			$query_scores .= " AND a.scorePlace IS NOT NULL";
			$query_scores .= " ORDER BY b.brewCategory,a.scorePlace ASC";

			$scores = mysqli_query($connection,$query_scores) or die (mysqli_error($connection));
			$row_scores = mysqli_fetch_assoc($scores);
			$totalRows_scores = mysqli_num_rows($scores);

			do {

				$query_table_name = sprintf("SELECT tableName,tableNumber from %s WHERE id = '%s'", $prefix."judging_tables".$archive_suffix, $row_scores['scoreTable']);
				$table_name = mysqli_query($connection,$query_table_name) or die (mysqli_error($connection));
				$row_table_name = mysqli_fetch_assoc($table_name);

				if ($row_scores['brewerCountry'] == "United States") $phone = format_phone_us($row_scores['brewerPhone1']); else $phone = $row_scores['brewerPhone1'];

				if ($pro_edition == 1) $a[] = array(
					$row_table_name['tableNumber'],
					html_entity_decode($row_table_name['tableName']),
					$row_scores['brewCategory'],
					$row_scores['brewSubCategory'],
					iconv("UTF-8", "ISO-8859-1//TRANSLIT",$row_scores['brewStyle']),
					$row_scores['scorePlace'],
					html_entity_decode($row_scores['brewerLastName']),
					html_entity_decode($row_scores['brewerFirstName']),
					html_entity_decode($row_scores['brewerBreweryName']),
					html_entity_decode($row_scores['brewerBreweryTTB']),
					html_entity_decode($row_scores['brewerEmail']),
					html_entity_decode($row_scores['brewerAddress']),
					html_entity_decode($row_scores['brewerCity']),
					html_entity_decode($row_scores['brewerState']),
					html_entity_decode($row_scores['brewerZip']),
					html_entity_decode($row_scores['brewerCountry']),
					$phone,
					html_entity_decode($row_scores['brewName']),
					html_entity_decode($row_scores['brewerClubs']),
					html_entity_decode($row_scores['brewCoBrewer'])
				);

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

						$a[] = array(
							$row_sql['tableNumber'],
							html_entity_decode($row_table_name['tableName']),
							$row_scores['brewJudgingNumber'],
							$row_scores['brewCategory'],
							$row_scores['brewSubCategory'],
							iconv("UTF-8", "ISO-8859-1//TRANSLIT",html_entity_decode($row_scores['brewStyle'])),
							$row_scores['scorePlace'],
							html_entity_decode($row_scores['brewerLastName']),
							html_entity_decode($row_scores['brewerFirstName']),
							$row_scores['brewerEmail'],
							html_entity_decode($row_scores['brewerAddress']),
							html_entity_decode($row_scores['brewerCity']),
							html_entity_decode($row_scores['brewerState']),
							html_entity_decode($row_scores['brewerZip']),
							html_entity_decode($row_scores['brewerCountry']),
							$phone,
							html_entity_decode($row_scores['brewName']),
							html_entity_decode($row_scores['brewerClubs']),
							html_entity_decode($row_scores['brewCoBrewer']),
							$bos_for_entry,
							$pro_am_for_entry,
							$totalRows_scores,
							$bestbrewer_place
						);
						
					}

					if ($tb == "winners") {

						$a[] = array(
							$row_table_name['tableNumber'],
							html_entity_decode($row_table_name['tableName']),
							$row_scores['brewCategory'],
							$row_scores['brewSubCategory'],
							iconv("UTF-8", "ISO-8859-1//TRANSLIT",$row_scores['brewStyle']),
							$row_scores['scorePlace'],
							html_entity_decode($row_scores['brewerLastName']),
							html_entity_decode($row_scores['brewerFirstName']),
							html_entity_decode($row_scores['brewerEmail']),
							html_entity_decode($row_scores['brewerAddress']),
							html_entity_decode($row_scores['brewerCity']),
							html_entity_decode($row_scores['brewerState']),
							html_entity_decode($row_scores['brewerZip']),
							html_entity_decode($row_scores['brewerCountry']),
							$phone,
							html_entity_decode($row_scores['brewName']),
							html_entity_decode($row_scores['brewerClubs']),
							html_entity_decode($row_scores['brewCoBrewer'])
						);

					}

				}				

			} while ($row_scores = mysqli_fetch_assoc($scores));			
		
		}
	
	}

} // end if ($winner_method == 1)

// BY SUB-CATEGORY
if ($winner_method == 2) {

	$a = array();
	
	if ($tb == "circuit") $a[] = array($label_table,$label_name,$label_judging_number,$label_category,$label_subcategory,$label_style,$label_place,$label_last_name,$label_first_name,$label_email,$label_address,$label_city,$label_state_province,$label_zip,$label_country,$label_phone,$label_entry_name,$label_club,$label_cobrewer,$label_bos,$label_pro_am,$label_medal_count,$label_best_brewer_place);
	
	else {
		
		if ($pro_edition == 1) $a[] = array($label_table,$label_name,$label_category,$label_style,$label_name,$label_place,$label_last_name,$label_first_name,$label_organization,$label_ttb,$label_email,$label_address,$label_city,$label_state_province,$label_zip,$label_country,$label_phone,$label_entry_name,$label_club,$label_cobrewer);

		else $a[] = array($label_table,$label_name,$label_category,$label_subcategory,$label_style,$label_place,$label_last_name,$label_first_name,$label_email,$label_address,$label_city,$label_state_province,$label_zip,$label_country,$label_phone,$label_entry_name,$label_club,$label_cobrewer);
	}

	$b = styles_active(2,$archive_suffix);
	// echo $archive_suffix;
	// print_r($b);

	foreach (array_unique($b) as $style) {

		$style = explode("^",$style);

		include (DB.'winners_subcategory.db.php');

		if ($row_entry_count['count'] > 0) {

			if ($_SESSION['prefsStyleSet'] != "BA") $query_scores = sprintf("SELECT a.scoreTable, a.scorePlace, a.scoreEntry, b.id, b.brewJudgingNumber, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.uid, c.brewerLastName, c.brewerFirstName, c.brewerEmail, c.brewerClubs, c.brewerAddress, c.brewerState, c.brewerCity, c.brewerZip, c.brewerPhone1, c.brewerCountry, c.brewerBreweryTTB, c.brewerBreweryName FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND b.brewSubCategory='%s' AND a.eid = b.id  AND c.uid = b.brewBrewerID", $prefix."judging_scores".$archive_suffix, $prefix."brewing".$archive_suffix, $prefix."brewer".$archive_suffix, $style[0], $style[1]);

			else $query_scores = sprintf("SELECT a.scoreTable, a.scorePlace, a.scoreEntry, b.id, b.brewJudgingNumber, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.uid, c.brewerLastName, c.brewerFirstName, c.brewerEmail, c.brewerClubs, c.brewerAddress, c.brewerState, c.brewerCity, c.brewerZip, c.brewerPhone1, c.brewerCountry, c.brewerBreweryTTB, c.brewerBreweryName FROM %s a, %s b, %s c WHERE b.brewSubCategory='%s' AND a.eid = b.id  AND c.uid = b.brewBrewerID", $prefix."judging_scores".$archive_suffix, $prefix."brewing".$archive_suffix, $prefix."brewer".$archive_suffix.$archive_suffix, $style[1]);

			/*
			if ($_SESSION['prefsStyleSet'] != "BA") $query_scores = sprintf("SELECT a.scoreTable, a.scorePlace, a.scoreEntry, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerClubs, c.brewerAddress, c.brewerState, c.brewerCity, c.brewerZip, c.brewerPhone1, c.brewerCountry, c.brewerEmail FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND b.brewSubCategory='%s' AND a.eid = b.id  AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style[0], $style[1]);
			else $query_scores = sprintf("SELECT a.scoreTable, a.scorePlace, a.scoreEntry, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerClubs, c.brewerAddress, c.brewerState, c.brewerCity, c.brewerZip, c.brewerPhone1, c.brewerCountry, c.brewerEmail FROM %s a, %s b, %s c WHERE b.brewSubCategory='%s' AND a.eid = b.id  AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style[1]);
			*/

			$query_scores .= " AND a.scorePlace IS NOT NULL";
			if ($_SESSION['prefsStyleSet'] == "BA") $query_scores .= " ORDER BY b.brewStyle,a.scorePlace ASC";
			else $query_scores .= " ORDER BY b.brewCategory,b.brewSubCategory,a.scorePlace";

			// echo $query_scores."<br><br>";
			$scores = mysqli_query($connection,$query_scores) or die (mysqli_error($connection));
			$row_scores = mysqli_fetch_assoc($scores);
			$totalRows_scores = mysqli_num_rows($scores);

			do {

				if ((isset($row_scores['scoreTable'])) && (!empty($row_scores['scoreTable']))) {
					$query_table_name = sprintf("SELECT tableName,tableNumber from %s WHERE id = '%s'",$prefix."judging_tables".$archive_suffix,$row_scores['scoreTable']);
					$table_name = mysqli_query($connection,$query_table_name) or die (mysqli_error($connection));
					$row_table_name = mysqli_fetch_assoc($table_name);
				}

				if (!empty($row_scores['scorePlace'])) {

					if ($row_scores['brewerCountry'] == "United States") $phone = format_phone_us($row_scores['brewerPhone1']); 
					else $phone = $row_scores['brewerPhone1'];

					if ($pro_edition == 1)  {
						
						$a[] = array(
							$row_table_name['tableNumber'],
							html_entity_decode($row_table_name['tableName']),
							$row_scores['brewCategory'],
							$row_scores['brewSubCategory'],
							iconv("UTF-8", "ISO-8859-1//TRANSLIT",$row_scores['brewStyle']),
							$row_scores['scorePlace'],
							html_entity_decode($row_scores['brewerLastName']),
							html_entity_decode($row_scores['brewerFirstName']),
							html_entity_decode($row_scores['brewerBreweryName']),
							html_entity_decode($row_scores['brewerBreweryTTB']),
							html_entity_decode($row_scores['brewerEmail']),
							html_entity_decode($row_scores['brewerAddress']),
							html_entity_decode($row_scores['brewerCity']),
							html_entity_decode($row_scores['brewerState']),
							html_entity_decode($row_scores['brewerZip']),
							html_entity_decode($row_scores['brewerCountry']),
							$phone,
							html_entity_decode($row_scores['brewName']),
							html_entity_decode($row_scores['brewerClubs']),
							html_entity_decode($row_scores['brewCoBrewer'])
						);

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

							$a[] = array(
								$row_sql['tableNumber'],
								html_entity_decode($row_table_name['tableName']),
								$row_scores['brewJudgingNumber'],
								$row_scores['brewCategory'],
								$row_scores['brewSubCategory'],
								iconv("UTF-8", "ISO-8859-1//TRANSLIT",html_entity_decode($row_scores['brewStyle'])),
								$row_scores['scorePlace'],
								html_entity_decode($row_scores['brewerLastName']),
								html_entity_decode($row_scores['brewerFirstName']),
								$row_scores['brewerEmail'],
								html_entity_decode($row_scores['brewerAddress']),
								html_entity_decode($row_scores['brewerCity']),
								html_entity_decode($row_scores['brewerState']),
								html_entity_decode($row_scores['brewerZip']),
								html_entity_decode($row_scores['brewerCountry']),
								$phone,
								html_entity_decode($row_scores['brewName']),
								html_entity_decode($row_scores['brewerClubs']),
								html_entity_decode($row_scores['brewCoBrewer']),
								$bos_for_entry,
								$pro_am_for_entry,
								$totalRows_scores,
								$bestbrewer_place
							);
							
						}

						if ($tb == "winners") {
							$a[] = array(
								$row_table_name['tableNumber'],
								html_entity_decode($row_table_name['tableName']),
								$row_scores['brewCategory'],
								$row_scores['brewSubCategory'],
								iconv("UTF-8", "ISO-8859-1//TRANSLIT",$row_scores['brewStyle']),
								$row_scores['scorePlace'],
								html_entity_decode($row_scores['brewerLastName']),
								html_entity_decode($row_scores['brewerFirstName']),
								html_entity_decode($row_scores['brewerEmail']),
								html_entity_decode($row_scores['brewerAddress']),
								html_entity_decode($row_scores['brewerCity']),
								html_entity_decode($row_scores['brewerState']),
								html_entity_decode($row_scores['brewerZip']),
								html_entity_decode($row_scores['brewerCountry']),
								$phone,
								html_entity_decode($row_scores['brewName']),
								html_entity_decode($row_scores['brewerClubs']),
								html_entity_decode($row_scores['brewCoBrewer'])
							);
						}
					}
				}

			} while ($row_scores = mysqli_fetch_assoc($scores));
		}
	}
} // end if ($winner_method == 2)

?>