<?php

function random_judging_num_generator(){
	
	srand ((double) microtime() * 10000000);

	$random_generator = "";
	
	for ($i=1;$i<6+1;$i++) { 
		$random_generator .= rand(1,9);
	}

	return $random_generator;

}

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
	$scoresheet_file_name_judging = strtolower($input).".pdf";

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

		// For 2.1.15, change to utilize a dash to separate the category from the number.
		// Allows for use of alpha numeric style categories like M1, C2, PR, etc.

		if (($totalRows_brewing_styles == 0) || ($row_brewing_styles['brewJudgingNumber'] == "")) {
			$output = $style_cat_num."-001";
		}

		else {
			$splitter = explode("-",$row_brewing_styles['brewJudgingNumber']);
			$add_one = $splitter[1] + 1;
			$output = sprintf("%02s",$splitter[0])."-".sprintf("%03s",$add_one);
		}

		return sprintf("%06s",strtolower($output));

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

	$reconstruct = "";

	if (!empty($referer)) {
		
		include (CONFIG."config.php");
		mysqli_select_db($connection,$database);

		if (NHC) $base_url = "../";

		$parts = parse_url($referer);

		if (!empty($parts['query'])) {

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

		}

	}

	return $reconstruct;

}

function generate_judging_numbers($brewing_db_table,$method) {

	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$status = 0;
	
	$data = array('brewJudgingNumber' => NULL);
	$result = $db_conn->update ($brewing_db_table, $data);
	if (!$result) $status += 1;

	$query_judging_numbers = sprintf("SELECT id,brewCategory,brewName FROM %s", $brewing_db_table);
	$judging_numbers = mysqli_query($connection,$query_judging_numbers) or die (mysqli_error($connection));
	$row_judging_numbers = mysqli_fetch_assoc($judging_numbers);

	if ($method == "default") {

		$files = array_slice(scandir(USER_DOCS), 2);

		do {

			$judging_number_looper = TRUE;

			while($judging_number_looper) {

				$j_num = generate_judging_num(1,"default");
				$scoresheet_file_name_judging = $j_num.".pdf";

				if (!in_array($scoresheet_file_name_judging,$files))  {				

					$data = array('brewJudgingNumber' => $j_num);
					$db_conn->where ('id', $row_judging_numbers['id']);
					$result = $db_conn->update ($brewing_db_table, $data);
					if (!$result) $status += 1;

					$judging_number_looper = FALSE;
				
				}

				else $judging_number_looper = TRUE;

			}

		} while ($row_judging_numbers = mysqli_fetch_assoc($judging_numbers));
	
	}

	if ($method == "identical") {
		
		do {
			
			$j_num = sprintf("%06s",$row_judging_numbers['id']);
			
			$data = array('brewJudgingNumber' => $j_num);
			$db_conn->where ('id', $row_judging_numbers['id']);
			$result = $db_conn->update ($brewing_db_table, $data);
			if (!$result) $status += 1;

		} while ($row_judging_numbers = mysqli_fetch_assoc($judging_numbers));
	
	}

	if ($method == "legacy") {
		
		do {

			$j_num = generate_judging_num(2,$row_judging_numbers['brewCategory']);

			$data = array('brewJudgingNumber' => $j_num);
			$db_conn->where ('id', $row_judging_numbers['id']);
			$result = $db_conn->update ($brewing_db_table, $data);
			if (!$result) $status += 1;

		} while ($row_judging_numbers = mysqli_fetch_assoc($judging_numbers));
	
	}

	return $status;

}

function check_sweetness($style,$styleSet) {

	include (CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	$style_explodies = explode("-",$style);

	if (preg_match("/^[[:digit:]]+$/",$style_explodies[0])) $style_0 = sprintf('%02d',$style_explodies[0]);
	else $style_0 = $style_explodies[0];

	$query_brews = sprintf("SELECT brewStyleSweet FROM %s WHERE brewStyleGroup='%s' AND brewStyleNum='%s' AND (brewStyleVersion='%s' OR brewStyleOwn='custom')", $prefix."styles", $style_0, $style_explodies[1], $_SESSION['prefsStyleSet']);
	$brews = mysqli_query($connection,$query_brews) or die (mysqli_error($connection));
	$row_brews = mysqli_fetch_assoc($brews);

	if ($row_brews['brewStyleSweet'] == 1) return TRUE;
	else return FALSE;

}


function check_carb($style,$styleSet) {

	include (CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	$style_explodies = explode("-",$style);

	if (preg_match("/^[[:digit:]]+$/",$style[0])) $style_0 = sprintf('%02d',$style_explodies[0]);
	else $style_0 = $style_explodies[0];

	$query_brews = sprintf("SELECT brewStyleCarb FROM %s WHERE brewStyleGroup='%s' AND brewStyleNum='%s' AND (brewStyleVersion='%s' OR brewStyleOwn='custom')", $prefix."styles", $style_0, $style_explodies[1], $_SESSION['prefsStyleSet']);
	$brews = mysqli_query($connection,$query_brews) or die (mysqli_error($connection));
	$row_brews = mysqli_fetch_assoc($brews);

	if ($row_brews['brewStyleCarb'] == 1) return TRUE;
	else return FALSE;

}

function check_mead_strength($style,$styleSet) {

	include (CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	$style_explodies = explode("-",$style);

	if (preg_match("/^[[:digit:]]+$/",$style_explodies[0])) $style_0 = sprintf('%02d',$style_explodies[0]);
	else $style_0 = $style_explodies[0];

	$query_brews = sprintf("SELECT brewStyleStrength FROM %s WHERE brewStyleGroup = '%s' AND brewStyleNum = '%s' AND (brewStyleVersion='%s' OR brewStyleOwn='custom')", $prefix."styles", $style_0, $style_explodies[1], $styleSet);
	$brews = mysqli_query($connection,$query_brews) or die (mysqli_error($connection));
	$row_brews = mysqli_fetch_assoc($brews);

	if ($row_brews['brewStyleStrength'] == 1) return TRUE;
	else return FALSE;

}

function standardize_name($string) {

	// Modified version of Armand Niculescu's function
	// See http://www.media-division.com/correct-name-capitalization-in-php/
	// Only applies to latin characters

	// Major latin-character languages that will apply the standardization
	$name_check_langs = array("en", "fr", "es", "pt", "it", "de");

	if ((isset($_SESSION['prefsLanguageFolder'])) && (in_array($_SESSION['prefsLanguageFolder'], $name_check_langs))) {

		$word_splitters = array(" ", "-", "O'", "L'", "D'", "St.", "Mc", "Mac", ".", "\"");
		$lowercase_exceptions = array("the", "van", "den", "ter", "von", "und", "des", "der", "de", "da", "of", "and", "l'", "d'", "la", "vit", "dos", "das", "do");
		$uppercase_exceptions = array("II", "III", "IV", "VI", "VII", "VIII", "IX", "IPA", "DIPA", "NE", "SHV", "NEIPA", "RIS");

		$string = strtolower($string);

		foreach ($word_splitters as $delimiter) {

			$words = explode($delimiter, $string);
			$newwords = array();

			foreach ($words as $word) {
				if (in_array(strtoupper($word), $uppercase_exceptions)) $word = strtoupper($word);
				elseif (!in_array($word, $lowercase_exceptions)) $word = ucfirst($word);
				$newwords[] = $word;
			}

			if (in_array(strtolower($delimiter), $lowercase_exceptions)) $delimiter = strtolower($delimiter);
			$string = join($delimiter, $newwords);

		}

	}

	return $string;
}

function rmove($src, $dest) {

    // If source is not a directory stop processing
    if (!is_dir($src)) return false;

    // If the destination directory does not exist create it
    // If the destination directory could not be created stop processing
    if (!is_dir($dest)) {
    	// mkdir($dest);
        if (!mkdir($dest)) {
            return false;
        }
    }

    // Open the source directory to read in files
    $i = new DirectoryIterator($src);
    foreach($i as $f) {
        if ($f->isFile()) {
            rename($f->getRealPath(), "$dest/".$f->getFilename());
        }
    }

    return true;

}

function rdelete($src,$file_mimes) {

	if (empty($file_mimes)) $file_mimes = array('image/jpeg','image/jpg','image/gif','image/png','application/pdf','image/bmp','image/tiff','image/svg+xml');
	else $file_mimes = array('application/pdf');

    // If source is not a directory stop processing
    if(!is_dir($src)) return false;

	$files = new FilesystemIterator($src);

	foreach($files as $file) {
		$mime = mime_content_type($file->getPathname());
		if (in_array($mime, $file_mimes)) unlink($file);
	}

	return true;
}

function blank_to_null($var) {
	if ((!isset($var)) || (empty($var))) $var = NULL;
	return $var;
}

// Standardize name languages
$name_check_langs = array("en", "fr", "es", "pt", "it", "de", "nl");
$last_name_exception_langs = array("nl", "es", "de");

?>