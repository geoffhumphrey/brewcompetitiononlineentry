<?php

function check_http($input) {
	if ($input != "") {
			if (strstr($input,"http://")) return $input;
			if (strstr($input,"https://")) return $input;
			if ((!strstr($input, "http://")) || (!strstr($input, "https://"))) return "http://".$input;		   
		}
}

 
function generate_judging_num($style_cat_num) {
	// Generate the Judging Number each entry 
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	$query_brewing_styles = sprintf("SELECT brewJudgingNumber FROM %s WHERE brewCategory='%s' ORDER BY brewJudgingNumber DESC LIMIT 1", $prefix."brewing", $style_cat_num);
	$brewing_styles = mysql_query($query_brewing_styles, $brewing) or die(mysql_error());
	$row_brewing_styles = mysql_fetch_assoc($brewing_styles);
	$totalRows_brewing_styles = mysql_num_rows($brewing_styles);
	
	if (($totalRows_brewing_styles == 0) || ($row_brewing_styles['brewJudgingNumber'] == "")) $output = $style_cat_num."001";
	else $output = $row_brewing_styles['brewJudgingNumber'] + 1;
	return sprintf("%05s",$output) ;
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

function table_exists($table_name) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	// taken from http://snippets.dzone.com/posts/show/3369
	$query_exists = "SHOW TABLES LIKE '".$table_name."'";
	$exists = mysql_query($query_exists, $brewing) or die(mysql_error());
	$totalRows_exists = mysql_num_rows($exists);
	if ($totalRows_exists > 0) return TRUE;
	else return FALSE;
}

function clean_up_url($referer) {
	
	include(CONFIG."config.php");
	
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

function purge_entries($type, $interval) {
		
	require(CONFIG.'config.php');	
	mysql_select_db($database, $brewing);
	
	if ($type == "unconfirmed") {
		$query_check = sprintf("SELECT id FROM %s WHERE brewConfirmed='0'", $prefix."brewing");
		if ($interval > 0) $query_check .= " AND brewUpdated < DATE_SUB( NOW(), INTERVAL 1 DAY)";
		$check = mysql_query($query_check, $brewing) or die(mysql_error());
		$row_check = mysql_fetch_assoc($check);
		
		do { $a[] = $row_check['id']; } while ($row_check = mysql_fetch_assoc($check));
		
		foreach ($a as $id) {
			$deleteEntries = sprintf("DELETE FROM %s WHERE id='%s'", $prefix."brewing", $id);
			mysql_select_db($database, $brewing);
			$result = mysql_query($deleteEntries, $brewing) or die(mysql_error()); 
		}
	}
	
	if ($type == "special") {
		$query_check = sprintf("SELECT id,brewInfo FROM %s WHERE (
					(brewCategorySort = '06' AND brewSubCategory = 'D') OR 
					(brewCategorySort = '16' AND brewSubCategory = 'E') OR 
					(brewCategorySort = '17' AND brewSubCategory = 'F') OR 
					(brewCategorySort = '20' AND brewSubCategory = 'A') OR 
					(brewCategorySort = '21' AND brewSubCategory = 'A') OR 
					(brewCategorySort = '21' AND brewSubCategory = 'B') OR 
					(brewCategorySort = '22' AND brewSubCategory = 'C') OR 
					(brewCategorySort = '23' AND brewSubCategory = 'A') OR 
					(brewCategorySort = '25' AND brewSubCategory = 'C') OR 
					(brewCategorySort = '26' AND brewSubCategory = 'A') OR 
					(brewCategorySort = '26' AND brewSubCategory = 'C') OR 
					(brewCategorySort = '27' AND brewSubCategory = 'E') OR 
					(brewCategorySort = '28' AND brewSubCategory = 'B') OR
					(brewCategorySort = '28' AND brewSubCategory = 'C') OR 
					(brewCategorySort = '28' AND brewSubCategory = 'D') OR 
					brewCategorySort >  '28')", 
					$prefix."brewing");
		if ($interval > 0) $query_check .=" AND brewUpdated < DATE_SUB( NOW(), INTERVAL 1 DAY)";
		
		$check = mysql_query($query_check, $brewing) or die(mysql_error());
		$row_check = mysql_fetch_assoc($check);
		
		do { 
			if ($row_check['brewInfo'] == "") {
				$deleteEntries = sprintf("DELETE FROM %s WHERE id='%s'", $prefix."brewing", $id);
				mysql_select_db($database, $brewing);
				$result = mysql_query($deleteEntries, $brewing) or die(mysql_error()); 
			}
		} while ($row_check = mysql_fetch_assoc($check));
	}
}

function generate_judging_numbers($brewing_db_table) {
	
	require(CONFIG.'config.php');	
	mysql_select_db($database, $brewing);
	
	/*
	$query_judging_numbers = "SELECT id FROM $brewing_db_table ORDER BY id ASC";
	$judging_numbers = mysql_query($query_judging_numbers, $brewing) or die(mysql_error());
	$row_judging_numbers = mysql_fetch_assoc($judging_numbers);

	do { 
		$updateSQL = sprintf("UPDATE $brewing_db_table SET brewJudgingNumber=%s WHERE id='%s'", "NULL", $row_judging_numbers['id']);
		mysql_select_db($database, $brewing);
		$Result = mysql_query($updateSQL, $brewing) or die(mysql_error());
	
	} while ($row_judging_numbers = mysql_fetch_assoc($judging_numbers));
	*/
	
	// Clear out all current judging numbers
	$updateSQL = sprintf("UPDATE %s SET brewJudgingNumber=NULL", $brewing_db_table);
	mysql_select_db($database, $brewing);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
	
	$query_judging_numbers = sprintf("SELECT id,brewCategory,brewName FROM %s", $brewing_db_table);
	$judging_numbers = mysql_query($query_judging_numbers, $brewing) or die(mysql_error());
	$row_judging_numbers = mysql_fetch_assoc($judging_numbers);
	
	// Generate and insert new judging numbers
	do { 	
		$updateSQL = sprintf("UPDATE %s SET brewJudgingNumber=%s WHERE id=%s",
					$brewing_db_table,
				   	GetSQLValueString(generate_judging_num($row_judging_numbers['brewCategory']), "text"),
				   	GetSQLValueString($row_judging_numbers['id'], "text"));	
		mysql_select_db($database, $brewing);
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
	
	} while ($row_judging_numbers = mysql_fetch_assoc($judging_numbers));
}

 ?>