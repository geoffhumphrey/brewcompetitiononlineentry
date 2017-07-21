<?php

// function to generate random number
function random_judging_num_generator(){
	srand ((double) microtime() * 10000000);

	$random_generator = "";// Initialize the string to store random numbers
	for ($i=1;$i<6+1;$i++) { // Loop the number of times of required digits
		$random_generator .=rand(1,9); // one number is added
	} // end of for loop 

	return $random_generator;
} // end of function

function check_http($input) {
	if ($input != "") {
			if (strstr($input,"http://")) return $input;
			if (strstr($input,"https://")) return $input;
			if ((!strstr($input, "http://")) || (!strstr($input, "https://"))) return "http://".$input;		   
		}
}

function check_judging_num($input) {
	
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	
	$query_brewing_styles = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewJudgingNumber='%s'", $prefix."brewing", $input);
	$brewing_styles = mysqli_query($connection,$query_brewing_styles) or die (mysqli_error($connection));
	$row_brewing_styles = mysqli_fetch_assoc($brewing_styles);
	
	$files = array_slice(scandir(USER_DOCS), 2);
	$scoresheet_file_name_judging = $input.".pdf";
	
	if (($row_brewing_styles['count'] == 0) && (!in_array($scoresheet_file_name_judging,$files))) return TRUE;
	else return FALSE;
	
}
 
function generate_judging_num($method,$style_cat_num) {
	
	if ($method == 1) {
		// Generate the Judging Number each entry 
		
		// For 2.1.0 converting from style-based judging numbers to completely random judging numbers (if not using barcode checking numbers)
		// Standardizing to six digit numbers	
		
		require(CONFIG.'config.php');
		mysqli_select_db($connection,$database);
		
		$unique_num_found = FALSE;
		
		// Check to see if that number has already been assigned to an entry
		
		while (!$unique_num_found) {
			
			$random = "";
			$random = random_judging_num_generator();
		
			if (check_judging_num($random)) {
				$unique_num_found = TRUE;
			}
			
			else {
				$unique_num_found = FALSE;
			}
		} 
		
		return $random;
	
	}
	
	if ($method == 2) {
	
		// Generate the Judging Number each entry 
		require(CONFIG.'config.php');	
		mysqli_select_db($connection,$database);
		
		$query_brewing_styles = sprintf("SELECT brewJudgingNumber FROM %s WHERE brewCategory='%s' ORDER BY brewJudgingNumber DESC LIMIT 1", $prefix."brewing", $style_cat_num);
		$brewing_styles = mysqli_query($connection,$query_brewing_styles) or die (mysqli_error($connection));
		$row_brewing_styles = mysqli_fetch_assoc($brewing_styles);
		$totalRows_brewing_styles = mysqli_num_rows($brewing_styles);
			
		// Need to convert mead and cider categories for BJCP2015 to numerals (all contain alphas, which break the script)
		switch ($style_cat_num) {
			case "C1": $style_cat_num = "38"; break;
			case "C2": $style_cat_num = "39"; break;
			case "M1": $style_cat_num = "40"; break;
			case "M2": $style_cat_num = "41"; break;
			case "M3": $style_cat_num = "42"; break;
			case "M4": $style_cat_num = "43"; break;
			default: $style_cat_num = $style_cat_num;
		}
		
		if (($totalRows_brewing_styles == 0) || ($row_brewing_styles['brewJudgingNumber'] == "")) $output = $style_cat_num."001";
		else $output = $row_brewing_styles['brewJudgingNumber'] + 1;
		return sprintf("%06s",$output);	
		
	}
	
}

function ucwordspecific($str,$delimiter) {
	$delimiter_space = $delimiter." ";
	$output = str_replace($delimiter_space,$delimiter,ucwords(str_replace($delimiter,$delimiter_space,$str)));
	return $output;
}

function capitalize($string1) {
	require(INCLUDES.'scrubber.inc.php');
	$output = strtr($string1,$html_remove);
	$output = ucwords($output);
	$output = ucwordspecific($output,"-");
	$output = ucwordspecific($output,".");
	$output = ucwordspecific($output,"(");
	$output = ucwordspecific($output,")");
	$output = strtr($output,$html_string);
	return $output;
}

function strip_newline($input) {
	$output = preg_replace("/[\n\r]/"," ",$input);
	return $output;
}


function clean_up_url($referer) {
	
	include (CONFIG."config.php");
	mysqli_select_db($connection,$database);
	
	if (NHC) $base_url = "../";
	
	// Break URL into an array
	$parts = parse_url($referer);
	$referer = $parts['query'];	
	
	// Remove $msg=X from query string
	$pattern = array("/[0-9]/", "/&msg=/");
	$referer = preg_replace($pattern, "", $referer);
	// Remove $id=X from query string
	$pattern = array("/[0-9]/", "/&id=/");
	$referer = preg_replace($pattern, "", $referer);
	
	// Remove $pg=X from query string and add back in
	$pattern = array("/[0-9]/", "/&pg=/");
	$referer = str_replace($pattern,"",$referer);
	
	$pattern = array('\'', '"');
	$referer = str_replace($pattern,"",$referer);
	$referer = stripslashes($referer);	
	
	// Reconstruct the URL
	$reconstruct = $base_url."index.php?".$referer;
	return $reconstruct;
	
}
/*
function purge_entries($type, $interval) {
	
	require(CONFIG.'config.php');	
	mysqli_select_db($connection,$database);
	
	if ($type == "unconfirmed") {
		$query_check = sprintf("SELECT id FROM %s WHERE brewConfirmed='0'", $prefix."brewing");
		if ($interval > 0) $query_check .= " AND brewUpdated < DATE_SUB( NOW(), INTERVAL 1 DAY)";
		$check = mysqli_query($connection,$query_check) or die (mysqli_error($connection));
		$row_check = mysqli_fetch_assoc($check);
		
		do { $a[] = $row_check['id']; } while ($row_check = mysqli_fetch_assoc($check));
		
		foreach ($a as $id) {
			$deleteSQL = sprintf("DELETE FROM %s WHERE id='%s'", $prefix."brewing", $id);
			mysqli_real_escape_string($connection,$deleteSQL);
			$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));
		}
	}
	
	if ($type == "special") {
		$query_check = sprintf("SELECT a.id, a.brewUpdated, a.brewInfo, a.brewCategorySort, a.brewSubCategory FROM %s as a, %s as b WHERE a.brewCategorySort=b.brewStyleGroup AND a.brewSubCategory=b.brewStyleNum AND b.brewStyleReqSpec=1 AND (a.brewInfo IS NULL OR a.brewInfo='') AND b.brewStyleVersion = '%s'", $prefix."brewing",$prefix."styles",$_SESSION['prefsStyleSet']);
		if ($interval > 0) $query_check .=" AND a.brewUpdated < DATE_SUB( NOW(), INTERVAL 1 DAY)";
		
		$check = mysqli_query($connection,$query_check) or die (mysqli_error($connection));
		$row_check = mysqli_fetch_assoc($check);
		
		do { 
				
			$deleteSQL = sprintf("DELETE FROM %s WHERE id='%s'", $prefix."brewing", $row_check['id']);
			mysqli_real_escape_string($connection,$deleteSQL);
			$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));
			
		} while ($row_check = mysqli_fetch_assoc($check));
	}
}
*/

function generate_judging_numbers($brewing_db_table,$method) {
	
	require(CONFIG.'config.php');	
	mysqli_select_db($connection,$database);
	
	// Clear out all current judging numbers
	$updateSQL = sprintf("UPDATE %s SET brewJudgingNumber=NULL", $brewing_db_table);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

	$query_judging_numbers = sprintf("SELECT id,brewCategory,brewName FROM %s", $brewing_db_table);
	$judging_numbers = mysqli_query($connection,$query_judging_numbers) or die (mysqli_error($connection));
	$row_judging_numbers = mysqli_fetch_assoc($judging_numbers);
	
	if ($method == "default") {
		
		$files = array_slice(scandir(USER_DOCS), 2);
		
		do { 	
			
			$judging_number_looper = TRUE;
			
			while($judging_number_looper) {
				
				$generated_judging_number = generate_judging_num(1,"default");
				$scoresheet_file_name_judging = $generated_judging_number.".pdf";
			
				if (!in_array($scoresheet_file_name_judging,$files))  { 
					$brewJudgingNumber = $generated_judging_number;
					$updateSQL = sprintf("UPDATE %s SET brewJudgingNumber=%s WHERE id=%s",
						$brewing_db_table,
						GetSQLValueString($brewJudgingNumber, "text"),
						GetSQLValueString($row_judging_numbers['id'], "text"));
						
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
					
					$judging_number_looper = FALSE;
				}
				else {
					$judging_number_looper = TRUE;
				}
			}
		} while ($row_judging_numbers = mysqli_fetch_assoc($judging_numbers));
	}
	
	if ($method == "identical") {
		do {
			$j_num = sprintf("%06s",$row_judging_numbers['id']);
			$updateSQL = sprintf("UPDATE %s SET brewJudgingNumber=%s WHERE id=%s",
						$brewing_db_table,
						GetSQLValueString($j_num, "text"),
						GetSQLValueString($row_judging_numbers['id'], "text"));	
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
		} while ($row_judging_numbers = mysqli_fetch_assoc($judging_numbers));
	}
	
	if ($method == "legacy") {
		do { 	
			$updateSQL = sprintf("UPDATE %s SET brewJudgingNumber=%s WHERE id=%s",
						$brewing_db_table,
						GetSQLValueString(generate_judging_num(2,$row_judging_numbers['brewCategory']), "text"),
						GetSQLValueString($row_judging_numbers['id'], "text"));	
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
		} while ($row_judging_numbers = mysqli_fetch_assoc($judging_numbers));
	}
}

function check_sweetness($style,$styleSet) {
	
	include (CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	
	$style_explodies = explode("-",$style);
		
	if (($style_explodies[0]< 34) && (strpos($styleSet,"BABDB") !== false)) {
		
		include (INCLUDES.'ba_constants.inc.php');
		
		if (in_array($style,$ba_sweetness)) return TRUE;
		else return FALSE;
		
	}
	
	else {
		
		if (preg_match("/^[[:digit:]]+$/",$style_explodies[0])) $style_0 = sprintf('%02d',$style_explodies[0]);
		else $style_0 = $style_explodies[0];
	
		if (strpos($styleSet,"BABDB") !== false) $query_brews = sprintf("SELECT brewStyleSweet FROM %s WHERE brewStyleOwn='custom' AND brewStyleGroup='%s'", $prefix."styles", $style_explodies[0]);
		else $query_brews = sprintf("SELECT brewStyleSweet FROM %s WHERE brewStyleGroup='%s' AND brewStyleNum='%s' AND (brewStyleVersion='%s' OR brewStyleOwn='custom')", $prefix."styles", $style_0, $style_explodies[1], $_SESSION['prefsStyleSet']);
		$brews = mysqli_query($connection,$query_brews) or die (mysqli_error($connection));
		$row_brews = mysqli_fetch_assoc($brews);
		
		if ($row_brews['brewStyleSweet'] == 1) return TRUE;
		else return FALSE;
	
	}
}


function check_carb($style,$styleSet) {
	
	include (CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	
	$style_explodies = explode("-",$style);
		
	if (($style_explodies[0] < 34) && (strpos($styleSet,"BABDB") !== false)) {
		
		include (INCLUDES.'ba_constants.inc.php');
		
		if (in_array($style,$ba_carb)) return TRUE;
		else return FALSE;
		
	}
	
	else {
		
		if (preg_match("/^[[:digit:]]+$/",$style[0])) $style_0 = sprintf('%02d',$style_explodies[0]);
		else $style_0 = $style_explodies[0];
	
		if (strpos($styleSet,"BABDB") !== false) $query_brews = sprintf("SELECT brewStyleCarb FROM %s WHERE brewStyleOwn='custom' AND brewStyleGroup='%s'", $prefix."styles", $style_explodies[0]);
		else $query_brews = sprintf("SELECT brewStyleCarb FROM %s WHERE brewStyleGroup='%s' AND brewStyleNum='%s' AND (brewStyleVersion='%s' OR brewStyleOwn='custom')", $prefix."styles", $style_0, $style_explodies[1], $_SESSION['prefsStyleSet']);
		$brews = mysqli_query($connection,$query_brews) or die (mysqli_error($connection));
		$row_brews = mysqli_fetch_assoc($brews);
		
		if ($row_brews['brewStyleCarb'] == 1) return TRUE;
		else return FALSE;
		
	}
}
	
function check_mead_strength($style,$styleSet) {
	
	include (CONFIG.'config.php');
	mysqli_select_db($connection,$database);
		
	$style_explodies = explode("-",$style);
	
	if (($style_explodies[0] < 34) && (strpos($styleSet,"BABDB") !== false)) {
		
		include (INCLUDES.'ba_constants.inc.php');
		
		if (in_array($style,$ba_strength)) return TRUE;
		else return FALSE;
		
	}
	
	else {
	
		if (preg_match("/^[[:digit:]]+$/",$style_explodies[0])) $style_0 = sprintf('%02d',$style_explodies[0]);
		else $style_0 = $style_explodies[0];
	
		if (strpos($styleSet,"BABDB") !== false) $query_brews = sprintf("SELECT brewStyleStrength FROM %s WHERE brewStyleOwn='custom' AND brewStyleGroup='%s'", $prefix."styles", $style_explodies[0]);
		else $query_brews = sprintf("SELECT brewStyleStrength FROM %s WHERE brewStyleGroup = '%s' AND brewStyleNum = '%s' AND (brewStyleVersion='%s' OR brewStyleOwn='custom')", $prefix."styles", $style_0, $style_explodies[1], $styleSet);
		$brews = mysqli_query($connection,$query_brews) or die (mysqli_error($connection));
		$row_brews = mysqli_fetch_assoc($brews);
		
		if ($row_brews['brewStyleStrength'] == 1) return TRUE;
		else return FALSE;
	
	}
	
}


// Map BJCP2008 Styles to BJCP2015 Styles
function bjcp_convert() {
	
	include (CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	
	$query_brews = sprintf("SELECT id,brewName,brewCategory,brewCategorySort,brewSubCategory,brewStyle FROM %s ORDER BY brewCategorySort,brewSubCategory", $prefix."brewing");
	$brews = mysqli_query($connection,$query_brews) or die (mysqli_error($connection));
	$row_brews = mysqli_fetch_assoc($brews);
		
	// Loop through entries and convert to 2015 styles
	do {
		
		$style = $row_brews['brewCategorySort'].$row_brews['brewSubCategory'];
		
		switch($style) {
			
			// 1
			case "01A": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","1","01","A","American Light Lager",$row_brews['id']);
			break;
			
			case "01B": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","1","01","B","American Lager",$row_brews['id']);
			break;
			
			case "01C": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","2","02","A","International Pale Lager",$row_brews['id']);
			break;
			
			case "01D": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","4","04","A","Munich Helles",$row_brews['id']);
			break;
			
			case "01E": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","5","05","C","Helles Exportbier",$row_brews['id']);
			break;
			
			// 2
			case "02A": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","5","05","D","German Pils",$row_brews['id']);
			break;
			
			case "02B": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","3","03","B","Czech Premium Pale Lager",$row_brews['id']);
			break;
			
			case "02C": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s', brewInfo='%s' WHERE id='%s'",$prefix."brewing","27","27","A","Historical Beer","Pre-Phohibition Lager",$row_brews['id']);
			break;
			
			// 3
			case "03A": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","7","07","A","Vienna Lager",$row_brews['id']);
			break;
			
			case "03B": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","6","06","A","Marzen",$row_brews['id']);
			break;
			
			// 4
			case "04A": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","2","02","C","International Dark Lager",$row_brews['id']);
			break;
			
			case "04B": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","8","08","A","Munich Dunkel",$row_brews['id']);
			break;
			
			case "04C": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","8","08","B","Schwarzbier",$row_brews['id']);
			break;
			
			// 5
			case "05A": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","4","04","C","Helles Bock",$row_brews['id']);
			break;
			
			case "05B": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","6","06","C","Dunkels Bock",$row_brews['id']);
			break;
			
			case "05C": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","9","09","A","Doppelbock",$row_brews['id']);
			break;
			
			case "05D": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","9","09","B","Doppelbock",$row_brews['id']);
			break;
			
			// 6
			case "06A": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","1","01","C","Cream Ale",$row_brews['id']);
			break;
			
			case "06B": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","18","18","A","Blonde Ale",$row_brews['id']);
			break;
			
			case "06C": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","5","05","B","Kolsch",$row_brews['id']);
			break;
			
			case "06D": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","1","01","D","American Wheat Beer",$row_brews['id']);
			break;
			
			// 7
			case "07A": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","2","02","B","International Amber Lager",$row_brews['id']);
			break;
			
			case "07B": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","19","19","B","California Common",$row_brews['id']);
			break;
			
			case "07C": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","7","07","B","Altbier",$row_brews['id']);
			break;
			
			// 8
			case "08A": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","A","Ordinary Bitter",$row_brews['id']);
			break;
			
			case "08B": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","B","Best Bitter",$row_brews['id']);
			break;
			
			case "08C": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","11","11","C","Strong Bitter",$row_brews['id']);
			break;
			
			// 9
			case "09A": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","14","14","A","Scottish Light",$row_brews['id']);
			break;
			
			case "09B": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","14","14","B","Scottish Heavy",$row_brews['id']);
			break;
			
			case "09C": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","14","14","C","Scottish Export",$row_brews['id']);
			break;
			
			case "09D": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","15","15","A","Irish Red Ale",$row_brews['id']);
			break;
			
			case "09E": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","17","17","C","Wee Heavy",$row_brews['id']);
			break;
			
			// 10
			case "10A": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","18","18","B","American Pale Ale",$row_brews['id']);
			break;
			
			case "10B": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","19","19","A","American Amber Ale",$row_brews['id']);
			break;
			
			case "10C": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","19","19","B","American Brown Ale",$row_brews['id']);
			break;
			
			// 11
			case "11A": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","13","13","A","Dark Mild",$row_brews['id']);
			break;
			
			case "11B": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s', brewInfo='%s' WHERE id='%s'",$prefix."brewing","27","27","B","Historical Beer","London Brown Ale",$row_brews['id']);
			break;
			
			case "11C": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","13","13","B","British Brown Ale",$row_brews['id']);
			break;
			
			// 12
			case "12A": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","13","13","C","English Porter",$row_brews['id']);
			break;
			
			case "12B": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","20","20","B","American Porter",$row_brews['id']);
			break;
			
			case "12C": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","9","09","C","Baltic Porter",$row_brews['id']);
			break;
			
			// 13
			case "13A": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","15","15","B","Irish Stout",$row_brews['id']);
			break;
			
			case "13B": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","16","16","A","Sweet Stout",$row_brews['id']);
			break;
			
			case "13C": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","16","16","B","Oatmeal Stout",$row_brews['id']);
			break;
			
			case "13D": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","16","16","D","Foreign Export Stout",$row_brews['id']);
			break;
			
			case "13E": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","20","20","B","American Stout",$row_brews['id']);
			break;
			
			case "13F": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","20","20","C","Imperial Stout",$row_brews['id']);
			break;
			
			// 14
			case "14A": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","12","12","C","English IPA",$row_brews['id']);
			break;
			
			case "14B": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","21","21","A","American IPA",$row_brews['id']);
			break;
			
			case "14C": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","22","22","A","American IPA",$row_brews['id']);
			break;
			
			// 15
			case "15A": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","10","10","A","Weissbier",$row_brews['id']);
			break;
			
			case "15B": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","10","10","B","Dunkles Weissbier",$row_brews['id']);
			break;
			
			case "15C": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","10","10","C","Weizenbock",$row_brews['id']);
			break;
			
			case "15D": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s', brewInfo='%s' WHERE id='%s'",$prefix."brewing","27","27","A","Historical Beer","Roggenbier",$row_brews['id']);
			break;
			
			// 16
			case "16A": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","24","24","A","Witbier",$row_brews['id']);
			break;
			
			case "16B": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","24","24","B","Belgian Pale Ale",$row_brews['id']);
			break;
			
			case "16C": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","25","25","B","Saison",$row_brews['id']);
			break;
			
			case "16D": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","24","24","C","Biere de Garde",$row_brews['id']);
			break;
			
			case "16E": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","34","34","A","Clone Beer",$row_brews['id']);
			break;
			
			// 17
			case "17A": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","23","23","A","Berliner Weisse",$row_brews['id']);
			break;
			
			case "17B": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","23","23","B","Flanders Red Ale",$row_brews['id']);
			break;
			
			case "17C": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","23","23","C","Oud Bruin",$row_brews['id']);
			break;
			
			case "17D": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","23","23","D","Lambic",$row_brews['id']);
			break;
			
			case "17E": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","23","23","E","Gueuze",$row_brews['id']);
			break;
			
			case "17F": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","23","23","F","Fruit Lambic",$row_brews['id']);
			break;
			
			// 18
			case "18A": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","25","25","A","Belgian Blonde Ale",$row_brews['id']);
			break;
			
			case "18B": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","26","26","B","Belgian Dubbel",$row_brews['id']);
			break;
			
			case "18C": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","26","26","C","Belgian Tripel",$row_brews['id']);
			break;
			
			case "18D": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","25","25","C","Belgian Golden Strong Ale",$row_brews['id']);
			break;
			
			case "18E": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","26","26","D","Belgian Dark Strong Ale",$row_brews['id']);
			break;
			
			// 19
			case "19A": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","17","17","B","Old Ale",$row_brews['id']);
			break;
			
			case "19B": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","17","17","D","English Barleywine",$row_brews['id']);
			break;
			
			case "19C": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","22","22","C","American Barleywine",$row_brews['id']);
			break;
			
			// 20
			case "20A": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","29","29","A","Fruit Beer",$row_brews['id']);
			break;
			
			// 21
			case "21A": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","30","30","A","Spice, Herb, or Vegetable Beer",$row_brews['id']);
			break;
			
			case "21B": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","30","30","C","Winter Seasonal Beer",$row_brews['id']);
			break;
			
			// 22
			case "22A": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","6","06","B","Rauchbier",$row_brews['id']);
			break;
			
			case "22B": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","32","32","A","Classic Style Smoked Beer",$row_brews['id']);
			break;
			
			case "22C": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","33","33","A","Wood-Aged Beer",$row_brews['id']);
			break;
			
			// 23
			case "23A": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","34","34","C","Specialty Beer",$row_brews['id']);
			break;
			
			// 24
			case "24A": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","M1","M1","A","Dry Mead",$row_brews['id']);
			break;
			
			case "24B": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","M1","M1","B","Semi-Sweet Mead",$row_brews['id']);
			break;
			
			case "24C": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","M1","M1","C","Sweet Mead",$row_brews['id']);
			break;
			
			// 25
			case "25A": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","M2","M2","A","Cyser",$row_brews['id']);
			break;
			
			case "25B": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","M2","M2","B","Pyment",$row_brews['id']);
			break;
			
			case "25C": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","M2","M2","E","Melomel",$row_brews['id']);
			break;
			
			// 26
			case "26A": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","M3","M3","A","Spice, Herb or Vegetable Mead",$row_brews['id']);
			break;
			
			case "26B": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","M4","M4","A","Braggot",$row_brews['id']);
			break;
			
			case "26C": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","M4","M4","C","Experimental Mead",$row_brews['id']);
			break;
			
			// 27
			case "27A": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","C1","C1","A","New World Cider",$row_brews['id']);
			break;
			
			case "27B": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","C1","C1","B","English Cider",$row_brews['id']);
			break;
			
			case "27C": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","C1","C1","C","French Cider",$row_brews['id']);
			break;
			
			case "27D": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","C1","C1","C","New World Perry",$row_brews['id']);
			break;
			
			case "27E": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","C1","C1","D","Traditional Perry",$row_brews['id']);
			break;
			
			// 28
			case "28A": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","C2","C2","A","New England Cider",$row_brews['id']);
			break;
			
			case "28B": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","C2","C2","B","Cider with Other Fruit",$row_brews['id']);
			break;
			
			case "28C": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","C2","C2","C","Applewine",$row_brews['id']);
			break;
			
			case "28D": 
				$updateSQL = sprintf("UPDATE %s SET brewCategory='%s', brewCategorySort='%s', brewSubCategory='%s', brewStyle='%s' WHERE id='%s'",$prefix."brewing","C2","C2","F","Specialty Cider/Perry",$row_brews['id']);
			break;
			
		}
		
		mysqli_select_db($connection,$database);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection)); 
				
		/* --- DEBUG ---
		echo "<p>".$updateSQL."<br>";
		echo $row_brews['brewName']." (".$row_brews['brewCategorySort'].$row_brews['brewSubCategory'].": ".$row_brews['brewStyle'].")</p>";
		*/
		
	} while ($row_brews = mysqli_fetch_assoc($brews));
			
}
 ?>