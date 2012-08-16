<?php
/**
 * Module:      functions.inc.php
 * Description: This module houses all site-wide function definitions. If a function
 *              or variable is called from multiple modules, it is housed here.
 *
 */

$pg = "default";
if (isset($_GET['pg'])) {
  $pg = (get_magic_quotes_gpc()) ? $_GET['pg'] : addslashes($_GET['pg']);
}

function display_array_content($arrayname,$method) {
 	$a = "";
 	while(list($key, $value) = each($arrayname)) {
  		if (is_array($value)) {
   		$a .= display_array_content($value,'');
		
   		}
  	else $a .= "$value"; 
	if ($method == "2") $a .= ", ";
	if ($method == "1") $a .= "";
	if ($method == "3") $a .= ",";
  	}
	$b = rtrim($a, ",&nbsp;");
 	return $b;
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
		$query_check = sprintf("
							   SELECT id,brewInfo FROM %s WHERE (brewInfo IS NULL OR brewInfo='') 
							   AND (
									(brewCategorySort = '16' AND brewSubCategory = 'E') OR 
									(brewCategorySort = '17' AND brewSubCategory = 'F') OR 
									(brewCategorySort = '20' AND brewSubCategory = 'A') OR 
									(brewCategorySort = '21' AND brewSubCategory = 'A') OR 
									(brewCategorySort = '22' AND brewSubCategory = 'B') OR 
									(brewCategorySort = '23' AND brewSubCategory = 'A') OR 
									(brewCategorySort = '25' AND brewSubCategory = 'C') OR 
									(brewCategorySort = '26' AND brewSubCategory = 'A') OR 
									(brewCategorySort = '27' AND brewSubCategory = 'E') OR 
									(brewCategorySort = '28' AND brewSubCategory = 'B') OR 
									(brewCategorySort = '28' AND brewSubCategory = 'C') OR 
									(brewCategorySort = '28' AND brewSubCategory = 'D') OR
									brewCategorySort >  '28'
									)", 
							   $prefix."brewing");
		if ($interval > 0) $query_check .=" AND brewUpdated < DATE_SUB( NOW(), INTERVAL 1 DAY)";
		
		$check = mysql_query($query_check, $brewing) or die(mysql_error());
		$row_check = mysql_fetch_assoc($check);
		
		do { 
			$a[] = $row_check['id']; 
		} while ($row_check = mysql_fetch_assoc($check));
		
		foreach ($a as $id) {
			$deleteEntries = sprintf("DELETE FROM %s WHERE id='%s'", $prefix."brewing", $id);
			mysql_select_db($database, $brewing);
			$result = mysql_query($deleteEntries, $brewing) or die(mysql_error()); 
		}
	}
}

// function to generate random number
function random_generator($digits,$method){
	srand ((double) microtime() * 10000000);

	//Array of alphabet
	if ($method == "1") $input = array ("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
	if ($method == "2") $input = array ("1","2","3","4","5","6","7","8","9");

	$random_generator = "";// Initialize the string to store random numbers
	for ($i=1;$i<$digits+1;$i++) { // Loop the number of times of required digits
		if(rand(1,2) == 1){// to decide the digit should be numeric or alphabet
		// Add one random alphabet 
		$rand_index = array_rand($input);
		$random_generator .=$input[$rand_index]; // One char is added
		}
		else {
		// Add one numeric digit between 1 and 10
		$random_generator .=rand(1,10); // one number is added
		} // end of if else
	} // end of for loop 

	return $random_generator;
} // end of function

function relocate($referer,$page,$msg,$id) {
	
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
	if ($page != "default") { 
		$pattern = array("/[0-9]/", "/&pg=/");
		$referer = str_replace($pattern,"",$referer);
		$referer .= "&pg=".$page; 
	}
	
	$pattern = array('\'', '"');
	$referer = str_replace($pattern,"",$referer);
	$referer = stripslashes($referer);	
	
	// Reconstruct the URL
	$reconstruct = "http://".$_SERVER['SERVER_NAME'].$_SERVER['PATH_INFO']."/index.php?".$referer;
	return $reconstruct;
	
}

function check_judging_numbers() {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	$query_check = sprintf("SELECT COUNT(*) as count FROM %s WHERE brewJudgingNumber IS NULL", $prefix."brewing");
	$check = mysql_query($query_check, $brewing) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	if ($row_check['count'] == 0) return true; else return false;
}

function judging_date_return() {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	$query_check = sprintf("SELECT judgingDate FROM %s", $prefix."judging_locations");
	$check = mysql_query($query_check, $brewing) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	
	$today = strtotime("now");
	do {
 		if ($row_check['judgingDate'] > $today) $newDate[] = 1; 
 		else $newDate[] = 0;
	} while ($row_check = mysql_fetch_assoc($check));
	$r = array_sum($newDate);
	return $r;
}

function greaterDate($start_date,$end_date)
{
  $start = strtotime($start_date);
  $end = strtotime($end_date);
  if ($start > $end)
   return TRUE;
  else
   return FALSE;
}

$color = "#eeeeee";
$color1 = "#e0e0e0";
$color2 = "#eeeeee";


// ---------------------------- Temperature, Weight, and Volume Conversion ----------------------------------

function temp_convert($temp,$t) { // $t = desired output, defined at function call
if ($t == "F") { // Celsius to F if source is C
	$tcon = (($temp - 32) / 1.8); 
    return round ($tcon, 1);
	}
	
if ($t == "C") { // F to Celsius
	$tcon = (($temp - 32) * (5/9)); 
    return round ($tcon, 1);
	}
}

function weight_convert($weight,$w) { // $w = desired output, defined at function call
if ($w == "pounds") { // kilograms to pounds
	$wcon = ($weight * 2.2046);
	return round ($wcon, 2);
	}
	
if ($w == "ounces") { // grams to ounces
	$wcon = ($weight * 0.03527);
	return round ($wcon, 2);
	}	
	
if ($w == "grams") { // ounces to grams
	$wcon = ($weight * 28.3495);
	return round ($wcon, 2);
	}
	
if ($w == "kilograms") { // pounds to kilograms
	$wcon = ($weight * 0.4535);
	return round ($wcon, 2);
	}
}

function volume_convert($volume,$v) {  // $v = desired output, defined at function call
if ($v == "gallons") { // liters to gallons
	$vcon = ($volume * 0.2641);
	return round ($vcon, 2);
	}
	
if ($v == "ounces") { // milliliters to ounces
	$vcon = ($volume * 29.573);
	return round ($vcon, 2);
	}	

if ($v == "liters") { // gallons to liters
	$vcon = ($volume * 3.7854);
	return round ($vcon, 2);
	}
	
if ($v == "milliliters") { // fluid ounces to milliliters
	$vcon = ($volume * 29.5735) ;
	return round ($vcon, 2);
	}	
	
}

// ---------------------------- Date Conversion -----------------------------------------

function getTimeZoneDateTime($GMT, $timestamp, $date_format, $time_format, $display_format, $return_format) { 
    $timezones = array( 
        '-12'=>'Pacific/Kwajalein', 
        '-11'=>'Pacific/Midway', 
        '-10'=>'Pacific/Honolulu', 
        '-9'=>'America/Anchorage', 
        '-8'=>'America/Los_Angeles', 
        '-7'=>'America/Denver', 
        '-6'=>'America/Mexico_City', 
        '-5'=>'America/New_York', 
        '-4'=>'America/Caracas', 
        '-3.5'=>'America/St_Johns', 
        '-3'=>'America/Argentina/Buenos_Aires', 
        '-2'=>'Atlantic/South_Georgia',
        '-1'=>'Atlantic/Azores', 
        '0'=>'Europe/London', 
        '1'=>'Europe/Paris', 
        '2'=>'Europe/Helsinki', 
        '3'=>'Europe/Moscow', 
        '3.5'=>'Asia/Tehran', 
        '4'=>'Asia/Baku', 
        '4.5'=>'Asia/Kabul', 
        '5'=>'Asia/Karachi', 
        '5.5'=>'Asia/Calcutta', 
        '6'=>'Asia/Colombo', 
        '7'=>'Asia/Bangkok', 
        '8'=>'Asia/Singapore', 
        '9'=>'Asia/Tokyo', 
        '9.5'=>'Australia/Darwin', 
        '10'=>'Pacific/Guam', 
        '11'=>'Asia/Magadan', 
        '12'=>'Asia/Kamchatka',
		'13'=>'Pacific/Tongatapu',
    );
	
    date_default_timezone_set($timezones[$GMT]);
	switch($display_format) {
		case "long": // Long Format
			if ($date_format == "1") $date = date('l, F j, Y', $timestamp);
			else $date = date('l j F, Y', $timestamp);
		break;
		case "short": // Short Format
			if ($date_format == "1") $date = date('m/d/Y', $timestamp);
			elseif ($date_format = "2") $date = date('d/m/Y',$timestamp);
			else $date = date('Y/m/d', $timestamp);
		break;
		
		case "system": // MySQL Format
			$date = date('Y-m-d', $timestamp);
		break;
	}
	
	if ($time_format == "1") $time = date('H:i',$timestamp);
	else $time = date('g:i A',$timestamp);
	
	switch($return_format) {
		case "date-time":
			$return = $date." at ".$time.", ".timezone_name($GMT);
		break;
		case "date-time-no-gmt":
			$return = $date." at ".$time;
		break;
		case "date-no-gmt":
			$return = $date;
		break;		
		case "time-gmt":
			$return = $time.", ".timezone_name($GMT);
		break;
		case "time":
			$return = $time;
		break;
		default: $return = $date;
	}
	return $return;
}




// http://www.phpbuilder.com/annotate/message.php3?id=1031006
function date_convert($date,$func,$format) {
if ($func == 1)	{ //insert conversion
	list($day, $month, $year) = split('[/.-]', $date); 
	$date = "$year-$month-$day"; 
	}
if ($func == 2)	{ //output conversion
	list($year, $month, $day) = explode("-", $date);
	if ($month == "01" ) { $month = "January "; }
	if ($month == "02" ) { $month = "February "; }
	if ($month == "03" ) { $month = "March "; }
	if ($month == "04" ) { $month = "April "; }
	if ($month == "05" ) { $month = "May "; }
	if ($month == "06" ) { $month = "June "; }
	if ($month == "07" ) { $month = "July "; }
	if ($month == "08" ) { $month = "August "; }
	if ($month == "09" ) { $month = "September "; }
	if ($month == "10" ) { $month = "October "; }
	if ($month == "11" ) { $month = "November "; }
	if ($month == "12" ) { $month = "December "; }
	$day = ltrim($day, "0");
	if ($format == 1) $date = "$month $day, $year";
	if ($format == 2) $date = "$day $month $year";
	if ($format == 3) $date = "$year $month $day";
	}
	
if ($func == 3)	{ //output conversion
	list($year, $month, $day) = explode("-", $date);
	if ($month == "01" ) { $month = "Jan"; }
	if ($month == "02" ) { $month = "Feb"; }
	if ($month == "03" ) { $month = "Mar"; }
	if ($month == "04" ) { $month = "Apr"; }
	if ($month == "05" ) { $month = "May"; }
	if ($month == "06" ) { $month = "Jun"; }
	if ($month == "07" ) { $month = "Jul"; }
	if ($month == "08" ) { $month = "Aug"; }
	if ($month == "09" ) { $month = "Sep"; }
	if ($month == "10" ) { $month = "Oct"; }
	if ($month == "11" ) { $month = "Nov"; }
	if ($month == "12" ) { $month = "Dec"; }
	$day = ltrim($day, "0");
	if ($format == 1) $date = "$month $day, $year";
	if ($format == 2) $date = "$day $month $year";
	if ($format == 3) $date = "$year $month $day";
	}	
return $date;
}

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

# Pagination

function paginate($display, $pg, $total) {
  /* make sure pagination doesn't interfere with other query string variables */
  if(isset($_SERVER['QUERY_STRING']) && trim(
    $_SERVER['QUERY_STRING']) != '') {
    	if(stristr($_SERVER['QUERY_STRING'], 'pg='))
      	$query_str = '?'.preg_replace('/pg=\d+/', 'pg=', 
        $_SERVER['QUERY_STRING']);
    	else
      	$query_str = '?'.$_SERVER['QUERY_STRING'].'&pg=';
  		} 	
	else
    $query_str = '?pg=';
    
  /* find out how many pages we have */
  $pages = ($total <= $display) ? 1 : ceil($total / $display);
    
  /* create the links */
  $first = '<span id="sortable_first" class="first paginate_button"><a href="'.$_SERVER['PHP_SELF'].$query_str.'1">First</a></span>';
  $prev =  '<span id="sortable_previous" class="previous paginate_button"><a href="'.$_SERVER['PHP_SELF'].$query_str.($pg - 1).'">Previous</a></span>';
  $next =  '<span id="sortable_next" class="next paginate_button"><a href="'.$_SERVER['PHP_SELF'].$query_str.($pg + 1).'">Next</a></span>';
  $last =  '<span id="sortable_last" class="last paginate_button"><a href="'.$_SERVER['PHP_SELF'].$query_str.$pages.'">Last</a></span>';
   
  /* display opening navigation */
  echo '<div id="sortable_paginate" class="dataTables_paginate paging_full_numbers">';
  echo ($pg > 1) ? "$first$prev" : '<span id="sortable_first" class="first paginate_button">First</span><span id="sortable_previous" class="previous paginate_button">Previous</span>';
  
  // limit the number of page links displayed  
  $begin = $pg - 8;
  while($begin < 1)
    $begin++;
  $end = $pg + 8;
  while($end > $pages)
    $end--;
  for($i=$begin; $i<=$end; $i++)
    echo ($i == $pg) ? ' <span class="paginate_active">'.$i.'</span> ' : '<span class="paginate_button"><a href="'.
      $_SERVER['PHP_SELF'].$query_str.$i.'">'.$i.'</a></span>';
    
  /* display ending navigation */
  echo ($pg < $pages) ? "$next$last" : '<span id="sortable_next" class="next paginate_button">Next</span><span id="sortable_last" class="last paginate_button">Last</span>';
  echo '</div>';
}
	
function total_fees($entry_fee, $entry_fee_discount, $entry_discount, $entry_discount_number, $cap_no, $special_discount_number, $bid, $filter) {
	require(CONFIG.'config.php');
	
	// ----------------------------------------------------------------------
	if (($bid == "default") && ($filter == "default")) { 
		mysql_select_db($database, $brewing);
		$query_users = sprintf("SELECT id,user_name FROM %s",$prefix."users");
		$users = mysql_query($query_users, $brewing) or die(mysql_error());
		$row_users = mysql_fetch_assoc($users);
		$totalRows_users = mysql_num_rows($users);

		do { $user_id_1[] = $row_users['id']; } while ($row_users = mysql_fetch_assoc($users));
		sort($user_id_1);
			
		foreach ($user_id_1 as $id_1) {
			// Get each entrant's number of entries
			mysql_select_db($database, $brewing);
			$query_entries = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s' AND brewConfirmed='1'",$prefix."brewing", $id_1);
			$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
			$row_entries = mysql_fetch_array($entries);
			$totalRows_entries = $row_entries['count'];
			
			$query_brewer = sprintf("SELECT brewerDiscount FROM %s WHERE uid='%s'",$prefix."brewer", $id_1);
			$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
			$row_brewer = mysql_fetch_array($brewer);
			
			if ($totalRows_entries > 0) {
				if (($row_brewer['brewerDiscount'] == "Y") && ($special_discount_number != "")) {
					if ($entry_discount == "Y") { 
						$a = $entry_discount_number * $special_discount_number;
						if ($entry_fee_discount > $special_discount_number) $b = ($totalRows_entries - $entry_discount_number) * $special_discount_number;
						else $b = ($totalRows_entries - $entry_discount_number) * $entry_fee_discount;
						$c = $a + $b;
				 		$d = $totalRows_entries * $special_discount_number;
				 		if ($totalRows_entries <= $entry_discount_number) $total = $d;
				 		if ($totalRows_entries > $entry_discount_number) $total = $c;	
					} // end if ($entry_discount == "Y")
					else $total = $totalRows_entries * $special_discount_number;
				} // end if ($row_brewer['brewerDiscount'] == "Y")
				if (($row_brewer['brewerDiscount'] != "Y") || ((($row_brewer['brewerDiscount'] == "Y")) && ($special_discount_number == ""))) {
					if ($entry_discount == "Y") {
				 		$a = $entry_discount_number * $entry_fee;
				 		$b = ($totalRows_entries - $entry_discount_number) * $entry_fee_discount;
						$c = $a + $b;
				 		$d = $totalRows_entries * $entry_fee;
				 		if ($totalRows_entries <= $entry_discount_number) $total = $d;
				 		if ($totalRows_entries > $entry_discount_number) $total = $c;
				 	}
					else $total = $totalRows_entries * $entry_fee;
				} // end if ($row_brewer['brewerDiscount'] != "Y")
				if ($cap_no > 0) {
					if ($total < $cap_no) $total_calc = $total;
					if ($total >= $cap_no) $total_calc = $cap_no;
					}
				else $total_calc = $total;
			} // endif ($totalRows_entries > 0)
			else $total_calc = 0;
			$total_array[] = $total_calc;
			mysql_free_result($entries);
			mysql_free_result($brewer);
		} // end foreach
	$total_fees = array_sum($total_array);
   	return $total_fees;
	} // end if (($bid == "default") && ($filter == "default"))
	// ----------------------------------------------------------------------
	
	// ----------------------------------------------------------------------
	if (($bid != "default") && ($filter == "default")) { 
	// Get each entrant's number of entries
	mysql_select_db($database, $brewing);
		$query_entries = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s' AND brewConfirmed='1'", $prefix."brewing", $bid);
		$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
		$row_entries = mysql_fetch_array($entries);
		$totalRows_entries = $row_entries['count'];
		//echo $totalRows_entries."<br>";
		
		$query_brewer = sprintf("SELECT brewerDiscount FROM %s WHERE uid='%s'", $prefix."brewer", $bid);
		$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
		$row_brewer = mysql_fetch_array($brewer);
			
		if ($totalRows_entries > 0) {
				if (($row_brewer['brewerDiscount'] == "Y") && ($special_discount_number != "")) {
					if ($entry_discount == "Y") { 
						$a = $entry_discount_number * $special_discount_number;
						if ($entry_fee_discount > $special_discount_number) $b = ($totalRows_entries - $entry_discount_number) * $special_discount_number;
						else $b = ($totalRows_entries - $entry_discount_number) * $entry_fee_discount;
						$c = $a + $b;
				 		$d = $totalRows_entries * $special_discount_number;
				 		if ($totalRows_entries <= $entry_discount_number) $total = $d;
				 		if ($totalRows_entries > $entry_discount_number) $total = $c;	
					} // end if ($entry_discount == "Y")
					else $total = $totalRows_entries * $special_discount_number;
					//echo $total."<br>";
				} // end if ($row_brewer['brewerDiscount'] == "Y")
				if (($row_brewer['brewerDiscount'] != "Y") || ((($row_brewer['brewerDiscount'] == "Y")) && ($special_discount_number == ""))) {
					if ($entry_discount == "Y") {
				 		$a = $entry_discount_number * $entry_fee;
				 		$b = ($totalRows_entries - $entry_discount_number) * $entry_fee_discount;
						$c = $a + $b;
				 		$d = $totalRows_entries * $entry_fee;
				 		if ($totalRows_entries <= $entry_discount_number) $total = $d;
				 		if ($totalRows_entries > $entry_discount_number) $total = $c;
				 	}
					else $total = $totalRows_entries * $entry_fee;
				} // end if ($row_brewer['brewerDiscount'] != "Y")
				if ($cap_no > 0) {
					if ($total < $cap_no) $total_calc = $total;
					if ($total >= $cap_no) $total_calc = $cap_no;
					}
				else $total_calc = $total;
			} // endif ($totalRows_entries > 0)
			
			else $total_calc = 0;
			//echo $total_calc."<br>";
			$total_array[] = $total_calc;
			mysql_free_result($entries);
			mysql_free_result($brewer);
	$total_fees = array_sum($total_array);
   	return $total_fees;
	} // end if (($bid != "default") && ($filter == "default")) 
	// ----------------------------------------------------------------------
	
	// ----------------------------------------------------------------------
	if (($bid == "default") && ($filter != "default")) { 
	
	mysql_select_db($database, $brewing);
		$query_users = sprintf("SELECT id,user_name FROM %s",$prefix."users");
		$users = mysql_query($query_users, $brewing) or die(mysql_error());
		$row_users = mysql_fetch_assoc($users);
		$totalRows_users = mysql_num_rows($users);

		do { $user_id_1[] = $row_users['id']; } while ($row_users = mysql_fetch_assoc($users));
		sort($user_id_1);
			
		foreach ($user_id_1 as $id_1) {
			// Get each entrant's number of entries
			mysql_select_db($database, $brewing);
			$query_entries = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s' AND brewCategorySort='%s' AND brewConfirmed='1'",$prefix."brewing",$id_1,$filter);
			$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
			$row_entries = mysql_fetch_array($entries);
			$totalRows_entries = $row_entries['count'];
			
			$query_brewer = sprintf("SELECT brewerDiscount FROM %s WHERE uid='%s'",$prefix."brewer", $id_1);
			$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
			$row_brewer = mysql_fetch_array($brewer);
			
			if ($totalRows_entries > 0) {
				if (($row_brewer['brewerDiscount'] == "Y") && ($special_discount_number != "")) {
					if ($entry_discount == "Y") { 
						$a = $entry_discount_number * $special_discount_number;
						if ($entry_fee_discount > $special_discount_number) $b = ($totalRows_entries - $entry_discount_number) * $special_discount_number;
						else $b = ($totalRows_entries - $entry_discount_number) * $entry_fee_discount;
						$c = $a + $b;
				 		$d = $totalRows_entries * $special_discount_number;
				 		if ($totalRows_entries <= $entry_discount_number) $total = $d;
				 		if ($totalRows_entries > $entry_discount_number) $total = $c;	
					} // end if ($entry_discount == "Y")
					else $total = $totalRows_entries * $special_discount_number;
				} // end if ($row_brewer['brewerDiscount'] == "Y")
				if (($row_brewer['brewerDiscount'] != "Y") || ((($row_brewer['brewerDiscount'] == "Y")) && ($special_discount_number == ""))) {
					if ($entry_discount == "Y") {
				 		$a = $entry_discount_number * $entry_fee;
				 		$b = ($totalRows_entries - $entry_discount_number) * $entry_fee_discount;
						$c = $a + $b;
				 		$d = $totalRows_entries * $entry_fee;
				 		if ($totalRows_entries <= $entry_discount_number) $total = $d;
				 		if ($totalRows_entries > $entry_discount_number) $total = $c;
				 	}
					else $total = $totalRows_entries * $entry_fee;
				} // end if ($row_brewer['brewerDiscount'] != "Y")
				if ($cap_no > 0) {
					if ($total < $cap_no) $total_calc = $total;
					if ($total >= $cap_no) $total_calc = $cap_no;
					}
				else $total_calc = $total;
			} // endif ($totalRows_entries > 0)
			else $total_calc = 0;
			$total_array[] = $total_calc;
			mysql_free_result($entries);
			mysql_free_result($brewer);
		} // end foreach
	$total_fees = array_sum($total_array);
   	return $total_fees;
	
	
	
	} // end if (($bid != "default") && ($filter == "default")) 
	
}

function total_fees_paid($entry_fee, $entry_fee_discount, $entry_discount, $entry_discount_number, $cap_no, $special_discount_number, $bid, $filter) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
 	// echo "<br>entry_fee:".$entry_fee."<br>entry_fee_discount:".$entry_fee_discount."<br>entry_discount:".$entry_discount."<br>entry_discount_number:".$entry_discount_number."<br>cap_no:".$cap_no."<br>special_discount_amount:".$special_discount_number."<br>bid:".$bid."<br>filter:".$filter."<br>";
	// ----------------------------------------------------------------------
	if (($bid == "default") && ($filter == "default")) { 
		$query_users = sprintf("SELECT id,user_name FROM %s", $prefix."users");
		$users = mysql_query($query_users, $brewing) or die(mysql_error());
		$row_users = mysql_fetch_assoc($users);
		$totalRows_users = mysql_num_rows($users);
	
		do { $user_id_2[] = $row_users['id']; } while ($row_users = mysql_fetch_assoc($users));
		sort($user_id_2);
		
		foreach ($user_id_2 as $id_2) {
			// Get each entrant's number of entries
			mysql_select_db($database, $brewing);
			$query_entries = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s' AND brewConfirmed='1'", $prefix."brewing", $id_2);
			$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
			$row_entries = mysql_fetch_array($entries);
			$totalRows_entries = $row_entries['count'];
			
			$query_paid = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s' AND brewPaid='1' AND brewConfirmed='1'",$prefix."brewing", $id_2);
			$paid = mysql_query($query_paid, $brewing) or die(mysql_error());
			$row_paid = mysql_fetch_array($paid);
			$totalRows_paid = $row_paid['count'];
			
			$totalRows_not_paid = ($row_entries['count'] - $row_paid['count']);
			
			$query_brewer = sprintf("SELECT brewerDiscount FROM %s WHERE uid='%s'",$prefix."brewer", $id_2);
			$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
			$row_brewer = mysql_fetch_array($brewer);

			if ($totalRows_entries > 0) {
				
				if (($row_brewer['brewerDiscount'] == "Y") && ($special_discount_number != "")) {
					if ($entry_discount == "Y") { 
						// Determine if the amount paid is equal or less than the discount amount
						// If so, total paid is a simple calculation
						if ($totalRows_paid <= $entry_discount_number) $total_paid = $totalRows_paid * $special_discount_number;
						// If not...
						if (($totalRows_paid > $entry_discount_number) && ($totalRows_paid < $totalRows_entries)) {
							// First, calculate all at the "regular" price
							$total_paid_regular = $entry_discount_number * $special_discount_number;
							// Next, determine which discounted figure is more: the "member" discount or the "volume";
							if ($special_discount_number >= $entry_fee_discount) $discount_amount = $entry_fee_discount; else $discount_amount = $special_discount_number;
							// Determine how many paid entires are eligible for a discount
							$total_paid_discount = (($totalRows_paid - $entry_discount_number) * $discount_amount);
							$total_paid = $total_paid_regular + $total_paid_discount;
						}
						if (($totalRows_paid > $entry_discount_number) && ($totalRows_paid == $totalRows_entries)) {
							// First, calculate all at the "regular" price
							$total_paid_regular = $entry_discount_number * $special_discount_number;
							// Next, determine which discounted figure is more: the "member" discount or the "volume";
							if ($special_discount_number >= $entry_fee_discount) $discount_amount = $entry_fee_discount; else $discount_amount = $special_discount_number;
							// Calculate amount of discounted entries
							$total_paid_discount = (($totalRows_entries - $entry_discount_number) * $discount_amount);
							$total_paid = $total_paid_regular + $total_paid_discount;
						}
					} // end if ($entry_discount == "Y")
					else {
						$total_paid = $totalRows_paid * $special_discount_number;
					}
			} // end if ($row_brewer['brewerDiscount'] == "Y")
				
				
				if (($row_brewer['brewerDiscount'] != "Y") || ((($row_brewer['brewerDiscount'] == "Y")) && ($special_discount_number == ""))) {
				if ($entry_discount == "Y") {
				 		// Determine if the amount paid is equal or less than the discount amount
						// If so, total paid is a simple calculation
						if ($totalRows_paid <= $entry_discount_number) $total_paid = $totalRows_paid * $entry_fee;
						// If not...
						if (($totalRows_paid > $entry_discount_number) && ($totalRows_paid < $totalRows_entries)) {
							// First, calculate all at the "regular" price
							$total_paid_regular = $entry_discount_number * $entry_fee;
							// Determine how many paid entires are eligible for a discount
							$total_paid_discount = (($totalRows_paid - $entry_discount_number) * $entry_fee_discount);
							$total_paid = $total_paid_regular + $total_paid_discount;
							}
						if (($totalRows_paid > $entry_discount_number) && ($totalRows_paid == $totalRows_entries)) {
							// First, calculate all at the "regular" price
							$total_paid_regular = $entry_discount_number * $entry_fee;
							// Calculate amount of discounted entries
							$total_paid_discount = (($totalRows_entries - $entry_discount_number) * $entry_fee_discount);
							$total_paid = $total_paid_regular + $total_paid_discount;
							} 					
				 		} // end if ($entry_discount == "Y")
					else 
						$total_paid = $totalRows_paid * $entry_fee;
						//echo $total_paid;
			} // end if ($row_brewer['brewerDiscount'] != "Y")
				
				if (($cap_no > 0) && ($cap_no != "")) {
					if ($total_paid < $cap_no) $total_calc_paid = $total_paid;
					if ($total_paid >= $cap_no) $total_calc_paid = $cap_no;
					}
				else $total_calc_paid = $total_paid;
			} // end if ($totalRows_entries > 0)
			else $total_calc_paid = 0;
			$total_array_paid[] = $total_calc_paid;
			mysql_free_result($entries);
			mysql_free_result($paid);
			mysql_free_result($brewer);
		} // end foreach
		$total_fees_paid = array_sum($total_array_paid);
   		return $total_fees_paid;
	} // end if (($bid == "default") && ($filter == "default"))
	// ----------------------------------------------------------------------
	
	// ----------------------------------------------------------------------
	if (($bid != "default") && ($filter == "default")) { 
	
	// Get the entrant's number of entries
	mysql_select_db($database, $brewing);
	$query_entries = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s' AND brewConfirmed='1'", $prefix."brewing", $bid);
	$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
	$row_entries = mysql_fetch_array($entries);
	$totalRows_entries = $row_entries['count'];
			
	$query_paid = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s' AND brewPaid='1' AND brewConfirmed='1'", $prefix."brewing", $bid);
	$paid = mysql_query($query_paid, $brewing) or die(mysql_error());
	$row_paid = mysql_fetch_array($paid);
	$totalRows_paid = $row_paid['count'];
	
	$totalRows_not_paid = ($totalRows_entries - $totalRows_paid);
	//echo "Entries: ".$totalRows_entries."<br>";
	//echo "Paid: ".$totalRows_paid."<br>";
	//echo "Not Paid: ".$totalRows_not_paid."<br>";	
	
	$query_brewer = sprintf("SELECT brewerDiscount FROM %s WHERE uid='%s'", $prefix."brewer", $bid);
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_array($brewer);
	//echo "Discount? ".$row_brewer['brewerDiscount']."<br>";
		if ($totalRows_entries > 0) {
			if (($row_brewer['brewerDiscount'] == "Y") && ($special_discount_number != "")) {
					if ($entry_discount == "Y") { 
						// Determine if the amount paid is equal or less than the discount amount
						// If so, total paid is a simple calculation
						if ($totalRows_paid <= $entry_discount_number) $total_paid = $totalRows_paid * $special_discount_number;
						// If not...
						if (($totalRows_paid > $entry_discount_number) && ($totalRows_paid < $totalRows_entries)) {
							// First, calculate all at the "regular" price
							$total_paid_regular = $entry_discount_number * $special_discount_number;
							// Next, determine which discounted figure is more: the "member" discount or the "volume";
							if ($special_discount_number >= $entry_fee_discount) $discount_amount = $entry_fee_discount; else $discount_amount = $special_discount_number;
							// Determine how many paid entires are eligible for a discount
							$total_paid_discount = (($totalRows_paid - $entry_discount_number) * $discount_amount);
							$total_paid = $total_paid_regular + $total_paid_discount;
						}
						if (($totalRows_paid > $entry_discount_number) && ($totalRows_paid == $totalRows_entries)) {
							// First, calculate all at the "regular" price
							$total_paid_regular = $entry_discount_number * $special_discount_number;
							// Next, determine which discounted figure is more: the "member" discount or the "volume";
							if ($special_discount_number >= $entry_fee_discount) $discount_amount = $entry_fee_discount; else $discount_amount = $special_discount_number;
							// Calculate amount of discounted entries
							$total_paid_discount = (($totalRows_entries - $entry_discount_number) * $discount_amount);
							$total_paid = $total_paid_regular + $total_paid_discount;
						}
					} // end if ($entry_discount == "Y")
					else {
						$total_paid = $totalRows_paid * $special_discount_number;
					}
			} // end if ($row_brewer['brewerDiscount'] == "Y")
				
			if (($row_brewer['brewerDiscount'] != "Y") || ((($row_brewer['brewerDiscount'] == "Y")) && ($special_discount_number == ""))) {
				if ($entry_discount == "Y") {
				 		// Determine if the amount paid is equal or less than the discount amount
						// If so, total paid is a simple calculation
						if ($totalRows_paid <= $entry_discount_number) $total_paid = $totalRows_paid * $entry_fee;
						// If not...
						if (($totalRows_paid > $entry_discount_number) && ($totalRows_paid < $totalRows_entries)) {
							// First, calculate all at the "regular" price
							$total_paid_regular = $entry_discount_number * $entry_fee;
							// Determine how many paid entires are eligible for a discount
							$total_paid_discount = (($totalRows_paid - $entry_discount_number) * $entry_fee_discount);
							$total_paid = $total_paid_regular + $total_paid_discount;
							}
						if (($totalRows_paid > $entry_discount_number) && ($totalRows_paid == $totalRows_entries)) {
							// First, calculate all at the "regular" price
							$total_paid_regular = $entry_discount_number * $entry_fee;
							// Calculate amount of discounted entries
							$total_paid_discount = (($totalRows_entries - $entry_discount_number) * $entry_fee_discount);
							$total_paid = $total_paid_regular + $total_paid_discount;
							} 					
				 		} // end if ($entry_discount == "Y")
					else 
						$total_paid = $totalRows_paid * $entry_fee;
						//echo $total_paid;
			} // end if ($row_brewer['brewerDiscount'] != "Y")
			
			if ($cap_no > 0) {
				if ($total_paid < $cap_no) $total_calc_paid = $total_paid;
				if ($total_paid >= $cap_no) $total_calc_paid = $cap_no;
				}
			else $total_calc_paid = $total_paid;
		} // end if ($totalRows_entries > 0)
		else $total_calc_paid = 0;
		$total_array_paid[] = $total_calc_paid;
		mysql_free_result($entries);
		mysql_free_result($paid);
		mysql_free_result($brewer);
		//echo "Total Paid: ".$total_calc_paid."<br>";	
		$total_fees_paid = array_sum($total_array_paid);
   		return $total_fees_paid;
	
	} // end if (($bid != "default") && ($filter == "default"))
	// ----------------------------------------------------------------------
	
	if (($bid == "default") && ($filter != "default")) { 
	
		$query_users = sprintf("SELECT id,user_name FROM %s", $prefix."users");
		$users = mysql_query($query_users, $brewing) or die(mysql_error());
		$row_users = mysql_fetch_assoc($users);
		$totalRows_users = mysql_num_rows($users);
	
		do { $user_id_2[] = $row_users['id']; } while ($row_users = mysql_fetch_assoc($users));
		sort($user_id_2);
		
		foreach ($user_id_2 as $id_2) {
			// Get each entrant's number of entries
			mysql_select_db($database, $brewing);
			$query_entries = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s' AND brewCategorySort='%s' AND brewConfirmed='1'",$prefix."brewing", $id_2, $filter);
			$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
			$row_entries = mysql_fetch_array($entries);
			$totalRows_entries = $row_entries['count'];
			
			$query_paid = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s' AND brewPaid='1' AND brewCategorySort='%s' AND brewConfirmed='1'",$prefix."brewing", $id_2, $filter);
			$paid = mysql_query($query_paid, $brewing) or die(mysql_error());
			$row_paid = mysql_fetch_array($paid);
			$totalRows_paid = $row_paid['count'];
			
			$totalRows_not_paid = ($row_entries['count'] - $row_paid['count']);
			
			$query_brewer = sprintf("SELECT brewerDiscount FROM %s WHERE uid='%s'", $prefix."brewer", $id_2);
			$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
			$row_brewer = mysql_fetch_array($brewer);

			if ($totalRows_entries > 0) {
				if (($row_brewer['brewerDiscount'] == "Y") && ($special_discount_number != "")) {
					if ($entry_discount == "Y") { 
						// Determine if the amount paid is equal or less than the discount amount
						// If so, total paid is a simple calculation
						if ($totalRows_paid <= $entry_discount_number) $total_paid = $totalRows_paid * $special_discount_number;
						// If not...
						if (($totalRows_paid > $entry_discount_number) && ($totalRows_paid < $totalRows_entries)) {
							// First, calculate all at the "regular" price
							$total_paid_regular = $entry_discount_number * $special_discount_number;
							// Next, determine which discounted figure is more: the "member" discount or the "volume";
							if ($special_discount_number >= $entry_fee_discount) $discount_amount = $entry_fee_discount; else $discount_amount = $special_discount_number;
							// Determine how many paid entires are eligible for a discount
							$total_paid_discount = (($totalRows_paid - $entry_discount_number) * $discount_amount);
							$total_paid = $total_paid_regular + $total_paid_discount;
						}
						if (($totalRows_paid > $entry_discount_number) && ($totalRows_paid == $totalRows_entries)) {
							// First, calculate all at the "regular" price
							$total_paid_regular = $entry_discount_number * $special_discount_number;
							// Next, determine which discounted figure is more: the "member" discount or the "volume";
							if ($special_discount_number >= $entry_fee_discount) $discount_amount = $entry_fee_discount; else $discount_amount = $special_discount_number;
							// Calculate amount of discounted entries
							$total_paid_discount = (($totalRows_entries - $entry_discount_number) * $discount_amount);
							$total_paid = $total_paid_regular + $total_paid_discount;
						}
					} // end if ($entry_discount == "Y")
					else {
						$total_paid = $totalRows_paid * $special_discount_number;
					}
			} // end if ($row_brewer['brewerDiscount'] == "Y")
				
			if (($row_brewer['brewerDiscount'] != "Y") || ((($row_brewer['brewerDiscount'] == "Y")) && ($special_discount_number == ""))) {
				if ($entry_discount == "Y") {
				 		// Determine if the amount paid is equal or less than the discount amount
						// If so, total paid is a simple calculation
						if ($totalRows_paid <= $entry_discount_number) $total_paid = $totalRows_paid * $entry_fee;
						// If not...
						if (($totalRows_paid > $entry_discount_number) && ($totalRows_paid < $totalRows_entries)) {
							// First, calculate all at the "regular" price
							$total_paid_regular = $entry_discount_number * $entry_fee;
							// Determine how many paid entires are eligible for a discount
							$total_paid_discount = (($totalRows_paid - $entry_discount_number) * $entry_fee_discount);
							$total_paid = $total_paid_regular + $total_paid_discount;
							}
						if (($totalRows_paid > $entry_discount_number) && ($totalRows_paid == $totalRows_entries)) {
							// First, calculate all at the "regular" price
							$total_paid_regular = $entry_discount_number * $entry_fee;
							// Calculate amount of discounted entries
							$total_paid_discount = (($totalRows_entries - $entry_discount_number) * $entry_fee_discount);
							$total_paid = $total_paid_regular + $total_paid_discount;
							} 					
				 		} // end if ($entry_discount == "Y")
					else 
						$total_paid = $totalRows_paid * $entry_fee;
						//echo $total_paid;
			} // end if ($row_brewer['brewerDiscount'] != "Y")
				
			if ($cap_no > 0) {
				if ($total_paid < $cap_no) $total_calc_paid = $total_paid;
				if ($total_paid >= $cap_no) $total_calc_paid = $cap_no;
				}
			else $total_calc_paid = $total_paid;
				
					
			} // end if ($totalRows_entries > 0)
			else $total_calc_paid = 0;
			$total_array_paid[] = $total_calc_paid;
			mysql_free_result($entries);
			mysql_free_result($paid);
			mysql_free_result($brewer);
		} // end foreach
		$total_fees_paid = array_sum($total_array_paid);
   		return $total_fees_paid;
	
	} // end if (($bid == "default") && ($filter != "default"))
	// ----------------------------------------------------------------------	
}

function unpaid_fees($total_not_paid, $discount_amt, $entry_fee, $entry_fee_disc, $cap, $secret, $secret_num) {
	switch($discount) {
		case "N": 
			if (($secret == "Y") && ($secret_num != "")) $entry_total =  $total_not_paid * $secret_num; 
			else $entry_total = $total_not_paid * $entry_fee;
		break;
		case "Y":
			if ($total_not_paid > $discount_amt) {
				if (($secret == "Y") && ($secret_num != "")) $reg_fee = $discount_amt * $secret_num;
				else $reg_fee = $discount_amt * $entry_fee;
				$disc_fee = ($total_not_paid - $discount_amt) * $entry_fee_disc;
				$entry_subtotal = $reg_fee + $disc_fee;
				}
			if ($total_not_paid <= $discount_amt) {
				if (($total_not_paid > 0) && ($secret != "Y")) $entry_total = $total_not_paid * $entry_fee;
				elseif (($total_not_paid > 0) && ($secret == "Y") && ($secret_num != "")) $entry_total = $total_not_paid * $secret_num;
				else $entry_subtotal = "0";
				}
		break;			
		} // end switch
		
		if ($cap == "0") $entry_total = $entry_subtotal;
		else { 
			if ($entry_subtotal > $cap) $entry_total = $cap;
			else $entry_total = $entry_subtotal;
		}
		return $entry_total;
	
} // end function

function discount_display($total_not_paid, $discount_amt, $entry_fee, $entry_fee_disc, $cap) { 
	if ($total_not_paid > $discount_amt) {
		$disc_fee = (($total_not_paid - $discount_amt) * $entry_fee_disc);
		$reg_fee = ($discount_amt * $entry_fee);
		$total = $disc_fee + $reg_fee;
		$array["a"] = $total_not_paid - $discount_amt;
		$array["b"] = $reg_fee;
		$array["c"] = $disc_fee;
		if (($cap != "0") && ($total <= $cap)) {
			$array["d"] = $total;
			}
		elseif (($cap != "0") && ($total > $cap)) { 
			$array["d"] = $cap;
			}
		else {
			$array["d"] = $total;
			}
		
		}
	if ($total_not_paid <= $discount_amt) {
		if ($total_not_paid > 0) $array = $total_not_paid * $entry_fee;
		else $array = "0";
		}
	return $array;
} // end funtion

function total_not_paid_brewer($bid) { 
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);

	$query_all = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s' AND brewConfirmed='1'", $prefix."brewing", $bid);
	$all = mysql_query($query_all, $brewing) or die(mysql_error());
	$row_all = mysql_fetch_assoc($all);
	$totalRows_all = $row_all['count'];

	$query_paid = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s' AND brewPaid='1' AND brewConfirmed='1'", $prefix."brewing", $bid);
	$paid = mysql_query($query_paid, $brewing) or die(mysql_error());
	$row_paid = mysql_fetch_assoc($paid);
	$totalRows_paid = $row_paid['count'];

	$total_not_paid = ($totalRows_all - $totalRows_paid);
	return $total_not_paid;
}

function total_paid_received($go,$id) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	
	$query_entry_count =  sprintf("SELECT COUNT(*) as 'count' FROM %s", $prefix."brewing");
	if ($go == "judging_scores") $query_entry_count .= " WHERE brewPaid='1' AND brewReceived='1' AND brewConfirmed='1'";
	//if (($go == "entries") && ($id != "default")) $query_entry_count .= " WHERE brewCategorySort='$id'"; 
	if ($id != "default") $query_entry_count .= " WHERE brewBrewerID='$id' AND brewPaid='1' AND brewReceived='1' AND brewConfirmed='1'";
	$result = mysql_query($query_entry_count, $brewing) or die(mysql_error());
	$row = mysql_fetch_array($result);
	return $row['count'];
}

function style_convert($number,$type) {
	
	$styles_db_table = $prefix."styles";
	switch ($type) {
		
		case "1": 
		switch ($number) {
			case "01": $style_convert = "Light Lager"; break;
			case "02": $style_convert = "Pilsner"; break;
			case "03": $style_convert = "European Amber Lager"; break;
			case "04": $style_convert = "Dark Lager"; break;
			case "05": $style_convert = "Bock"; break;
			case "06": $style_convert = "Light Hybrid Beer"; break;
			case "07": $style_convert = "Amber Hybrid Beer"; break;
			case "08": $style_convert = "English Pale Ale"; break;
			case "09": $style_convert = "Scottish and Irish Ale"; break;
			case "10": $style_convert = "American Ale"; break;
			case "11": $style_convert = "English Brown Ale"; break;
			case "12": $style_convert = "Porter"; break;
			case "13": $style_convert = "Stout"; break;
			case "14": $style_convert = "India Pale Ale (IPA)"; break;
			case "15": $style_convert = "German Wheat and Rye Beer"; break;
			case "16": $style_convert = "Belgian and French Ale"; break;
			case "17": $style_convert = "Sour Ale"; break;
			case "18": $style_convert = "Belgian Strong Ale"; break;
			case "19": $style_convert = "Strong Ale"; break;
			case "20": $style_convert = "Fruit Beer"; break;
			case "21": $style_convert = "Spice/Herb/Vegatable Beer"; break;
			case "22": $style_convert = "Smoke-Flavored and Wood-Aged Beer"; break;
			case "23": $style_convert = "Specialty Beer"; break;
			case "24": $style_convert = "Traditional Mead"; break;
			case "25": $style_convert = "Melomel (Fruit Mead)"; break;
			case "26": $style_convert = "Other Mead"; break;
			case "27": $style_convert = "Standard Cider and Perry"; break;
			case "28": $style_convert = "Specialty Cider and Perry"; break;
			default: $style_convert = "Custom Style"; break;
		}
		break;
		
		case "2":
		switch ($number) {
			case "01": $style_convert = "1A,1B,1C,1D,1E"; break;
			case "02": $style_convert = "2A,2B,2C"; break;
			case "03": $style_convert = "3A,3B"; break;
			case "04": $style_convert = "4A,4B,4C"; break;
			case "05": $style_convert = "5A,5B,5C,5D"; break;
			case "06": $style_convert = "6A,6B,6C,6D"; break;
			case "07": $style_convert = "7A,7B,7C"; break;
			case "08": $style_convert = "8A,8B,8C"; break;
			case "09": $style_convert = "9A,9B,9C,9D,9E"; break;
			case "10": $style_convert = "10A,10B,10C"; break;
			case "11": $style_convert = "11A,11B,11C"; break;
			case "12": $style_convert = "12A,12B,12C"; break;
			case "13": $style_convert = "13A,13B,13C,13D,13E,13F"; break;
			case "14": $style_convert = "14A,14B,14C,"; break;
			case "15": $style_convert = "15A,15B,15C,15D,"; break;
			case "16": $style_convert = "16A,16B,16C,16D,16E,"; break;
			case "17": $style_convert = "17A,17B,17C,17D,17E,17F"; break;
			case "18": $style_convert = "18A,18B,18C,18D,18E,"; break;
			case "19": $style_convert = "19A,19B,19C,"; break;
			case "20": $style_convert = "20"; break;
			case "21": $style_convert = "21A,21B"; break;
			case "22": $style_convert = "22A,22B,22C"; break;
			case "23": $style_convert = "23"; break;
			case "24": $style_convert = "24A,24B,24C"; break;
			case "25": $style_convert = "25A,25B,25C"; break;
			case "26": $style_convert = "25A,25B,26C"; break;
			case "27": $style_convert = "27A,27B,27C,27D,27E"; break;
			case "28": $style_convert = "28A,28B,28C,28D"; break;
			default: $style_convert = "Custom Style"; break;
		}
		break;
		
		case "3":
		$n = ereg_replace('[^0-9]+', '', $number);
		if ($n >= 29) $style_convert = TRUE;
		else {
		switch ($number) {
			case "06D": $style_convert = TRUE; break;
			case "16E": $style_convert = TRUE; break;
			case "17F": $style_convert = TRUE; break;
			case "20A": $style_convert = TRUE; break;
			case "21A": $style_convert = TRUE; break;
			case "21B": $style_convert = TRUE; break;
			case "22B": $style_convert = TRUE; break;
			case "22C": $style_convert = TRUE; break;
			case "23A": $style_convert = TRUE; break;
			case "25C": $style_convert = TRUE; break;
			case "26A": $style_convert = TRUE; break;
			case "26C": $style_convert = TRUE; break;
			case "27E": $style_convert = TRUE; break;
			case "28B": $style_convert = TRUE; break;
			case "28C": $style_convert = TRUE; break;
			case "28D": $style_convert = TRUE; break;
			default: $style_convert = FALSE; break;
		    }
		}
		break;
		
		case "4":
		$a = explode(",",$number);
		require(CONFIG.'config.php');
	    mysql_select_db($database, $brewing);
		foreach ($a as $value) {
			$styles_db_table = $prefix."styles";
			$query_style = "SELECT brewStyleGroup,brewStyleNum FROM $styles_db_table WHERE id='$value'"; 
			$style = mysql_query($query_style, $brewing) or die(mysql_error());
			$row_style = mysql_fetch_assoc($style);
			$style_convert[] = ltrim($row_style['brewStyleGroup'],"0").$row_style['brewStyleNum'];
		}
		break;
		
		case "5":
		$n = ereg_replace('[^0-9]+', '', $number);
		if ($n >= 24) $style_convert = TRUE;
		break;
		
		case "6":
		$a = explode(",",$number);
		require(CONFIG.'config.php');
	    mysql_select_db($database, $brewing);
		foreach ($a as $value) {
			$styles_db_table = $prefix."styles";
			$query_style = "SELECT brewStyleGroup,brewStyleNum FROM $styles_db_table WHERE id='$value'"; 
			$style = mysql_query($query_style, $brewing) or die(mysql_error());
			$row_style = mysql_fetch_assoc($style);
			$style_convert1[] = ltrim($row_style['brewStyleGroup'],"0").$row_style['brewStyleNum'];
		}
		$style_convert = rtrim(display_array_content($style_convert1,'3'),",");
		break;
	}
	return $style_convert;
}

function get_table_info($input,$method,$id,$dbTable,$param) {	
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	
	if ($dbTable == "default") {
		$judging_tables_db_table = $prefix."judging_tables";
		$judging_locations_db_table = $prefix."judging_locations";
		$judging_scores_db_table = $prefix."judging_scores";
		$judging_scores_bos_db_table = $prefix."judging_scores_bos";
		$styles_db_table = $prefix."styles";
		$brewing_db_table = $prefix."brewing";
	}
	
	else {
		$suffix = ltrim(get_suffix($dbTable), "_");
		$suffix = "_".$suffix;
		$judging_tables_db_table = $prefix."judging_tables".$suffix;
		$judging_locations_db_table = $prefix."judging_locations";
		$judging_scores_db_table = $prefix."judging_scores";
		$judging_scores_bos_db_table = $prefix."judging_scores_bos";
		$styles_db_table = $prefix."styles";
		$brewing_db_table = $prefix."brewing";

		
	}
	
	$query_table = "SELECT * FROM $judging_tables_db_table";
	if ($id != "default") $query_table .= " WHERE id='$id'";
	if ($param != "default") $query_table .= " WHERE tableLocation='$param'";
	$table = mysql_query($query_table, $brewing) or die(mysql_error());
	$row_table = mysql_fetch_assoc($table);
	
	if ($method == "basic") {
		$return = $row_table['tableNumber']."^".$row_table['tableName']."^".$row_table['tableLocation'];
		return $return;
	}
	
	if ($method == "location") { // used in output/assignments.php and output/pullsheets.php
		$query_judging_location = sprintf("SELECT * FROM $judging_locations_db_table WHERE id='%s'", $input);
		$judging_location = mysql_query($query_judging_location, $brewing) or die(mysql_error());
		$row_judging_location = mysql_fetch_assoc($judging_location);
		
		$return = $row_judging_location['judgingDate']."^".$row_judging_location['judgingTime']."^".$row_judging_location['judgingLocName'];
		return $return;
	}
	
	if ($method == "unassigned") {
		$return = "";
		$query_styles = "SELECT id,brewStyle FROM $styles_db_table";
		$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
		$row_styles = mysql_fetch_assoc($styles);
		
		do { $a[] = $row_styles['id']; } while ($row_styles = mysql_fetch_assoc($styles));
		sort($a);
		//echo "<p>"; print_r($a); echo "</p>";
		foreach ($a as $value) { 
			//echo $input."<br>";
			$b = array(explode(",",$input));
			//echo "<p>".print_r($b)."</p>";
			//echo "<p>-".$value."-</p>";
			if (in_array($value,$b)) { 
				echo "Yes. The style ID is $value.<br>";
				//$query_styles1 = "SELECT brewStyle FROM $styles_db_table WHERE id='$value'";
				//$styles1 = mysql_query($query_styles1, $brewing) or die(mysql_error());
				//$row_styles1 = mysql_fetch_assoc($styles1);
				//echo "<p>".$row_styles1['brewStyle']."</p>";
				}
			
				//else echo "No.<br>";
		}
	return $return;
	}
	
	if ($method == "styles") {
		do { 
			$a = explode(",", $row_table['tableStyles']);
			$b = $input;
			foreach ($a as $value) {
				if ($value == $input) return TRUE;
			}
		} while ($row_table = mysql_fetch_assoc($table));
	}
	
	if ($method == "assigned") {
		do { 
			$a = explode(",", $row_table['tableStyles']);
			$b = $input;
			foreach ($a as $value) {
				if ($value == $input) $c = "<br><em>Assigned to Table #".$row_table['tableNumber'].": <a href='index.php?section=admin&go=judging_tables&action=edit&id=".$row_table['id']."'>".$row_table['tableName']."</a></em>";
			}
		} while ($row_table = mysql_fetch_assoc($table));
	return $c;
  	}
	
	if ($method == "list") {
		$a = explode(",", $row_table['tableStyles']);
			foreach ($a as $value) {
				require(CONFIG.'config.php');
				mysql_select_db($database, $brewing);
				$query_styles = "SELECT * FROM $styles_db_table WHERE id='$value'";
				$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
				$row_styles = mysql_fetch_assoc($styles);
				
				$c[] = ltrim($row_styles['brewStyleGroup'].$row_styles['brewStyleNum'],"0").",&nbsp;";
			}
	$d = array($c);
	return $d;
  	}
	
	if (($method == "count_total") && ($param == "default")) {
		
		$a = explode(",", $row_table['tableStyles']);
			foreach ($a as $value) {
				require(CONFIG.'config.php');
				mysql_select_db($database, $brewing);
				$query_styles = "SELECT brewStyle FROM $styles_db_table WHERE id='$value'";
				$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
				$row_styles = mysql_fetch_assoc($styles);
				
				$query_style_count = sprintf("SELECT COUNT(*) as count FROM $brewing_db_table WHERE brewStyle='%s' AND brewReceived='1'", $row_styles['brewStyle']);
				$style_count = mysql_query($query_style_count, $brewing) or die(mysql_error());
				$row_style_count = mysql_fetch_assoc($style_count);
				$totalRows_style_count = $row_style_count['count'];
				$c[] = $totalRows_style_count ;
			}
	$d = array_sum($c);
	return $d; 
  	}
	
	if (($method == "count_total") && ($param != "default")) {
	
	do {	
		$a = explode(",", $row_table['tableStyles']);
			foreach ($a as $value) {
				require(CONFIG.'config.php');
				mysql_select_db($database, $brewing);
				$query_styles = "SELECT brewStyle FROM $styles_db_table WHERE id='$value'";
				$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
				$row_styles = mysql_fetch_assoc($styles);
				
				$query_style_count = sprintf("SELECT COUNT(*) as count FROM $brewing_db_table WHERE brewStyle='%s' AND brewReceived='1'", $row_styles['brewStyle']);
				$style_count = mysql_query($query_style_count, $brewing) or die(mysql_error());
				$row_style_count = mysql_fetch_assoc($style_count);
				$totalRows_style_count = $row_style_count['count'];
				$c[] = $totalRows_style_count ;
			}
	} while ($row_table = mysql_fetch_assoc($table));
	$d = array_sum($c);
	return $d; 
  	}
	
	
	if ($method == "count") {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	$query_style = "SELECT brewStyle FROM $styles_db_table WHERE brewStyle='$input'";
	$style = mysql_query($query_style, $brewing) or die(mysql_error());
	$row_style = mysql_fetch_assoc($style);
	//echo $query_style."<br>";
	
	$query = sprintf("SELECT COUNT(*) as 'count' FROM $brewing_db_table WHERE brewStyle='%s' AND brewReceived='1'",$row_style['brewStyle']);
	$result = mysql_query($query, $brewing) or die(mysql_error());
	$num_rows = mysql_fetch_array($result);
	// echo $query;
	//$num_rows = mysql_num_rows($result);
	return $num_rows['count'];
	}
	
	if ($method == "count_scores") {
		$a = explode(",", $row_table['tableStyles']);
			foreach ($a as $value) {
				require(CONFIG.'config.php');
				mysql_select_db($database, $brewing);
				$query_styles = "SELECT brewStyle FROM $styles_db_table WHERE id='$value'";
				$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
				$row_styles = mysql_fetch_assoc($styles);
				
				$query_style_count = sprintf("SELECT COUNT(*) as 'count' FROM $brewing_db_table WHERE brewStyle='%s' AND brewReceived='1'", $row_styles['brewStyle']);
				$style_count = mysql_query($query_style_count, $brewing) or die(mysql_error());
				$row_style_count = mysql_fetch_assoc($style_count);
				$totalRows_style_count = $row_style_count['count'];
									
				$c[] = $totalRows_style_count;
				
			}
	$query_score_count = sprintf("SELECT COUNT(*) as 'count' FROM $judging_scores_db_table WHERE scoreTable='%s'", $input);
	$score_count = mysql_query($query_score_count, $brewing) or die(mysql_error());
	$row_score_count = mysql_fetch_assoc($score_count);
	$totalRows_score_count = $row_score_count['count'];
	$e = array_sum($c);
	if ($e == $totalRows_score_count) return true;
  	}
}



function bos_place($eid) { 
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	$query_bos_place = sprintf("SELECT scorePlace,scoreEntry FROM %s WHERE eid='$eid'", $prefix."judging_scores_bos");
	$bos_place = mysql_query($query_bos_place, $brewing) or die(mysql_error());
	$row_bos_place = mysql_fetch_assoc($bos_place);
	$value = $row_bos_place['scorePlace']."-".$row_bos_place['scoreEntry'];
	return $value;
}

function style_type($type,$method,$source) { 
	if ($method == "1") { 
		switch($type) { 
			case "Mead": $type = "3";
			break;
			
			case "Cider": $type = "2";
			break;
			
			case "Mixed": $type = "1";
			break;
			
			case "Ale": $type = "1";
			break;
			
			case "Lager": $type = "1";
			break;
			
			default: $type = $type;
			break;
		}
	}
	
	if (($method == "2") && ($source == "bcoe")) { 
		switch($type) {
			case "3": $type = "Mead";
			break;
			
			case "2": $type = "Cider";
			break;
			
			case "1": $type = "Beer";
			break;
			
			case "Lager": $type = "Beer";
			break;
			
			case "Ale": $type = "Beer";
			break;
			
			case "Mixed": $type = "Beer";
			break;
			
			default: $type = $type;
			break;
		}
	}
	
	if (($method == "2") && ($source == "custom")) { 
		require(CONFIG.'config.php');
		mysql_select_db($database, $brewing);
		
		$query_style_type = sprintf("SELECT styleTypeName FROM %s WHERE id='%s'", $prefix."style_types", $type); 
		$style_type = mysql_query($query_style_type, $brewing) or die(mysql_error());
		$row_style_type = mysql_fetch_assoc($style_type);
		$type = $row_style_type['styleTypeName'];
	}
	
	if ($method == "3") { 
		require(CONFIG.'config.php');
		mysql_select_db($database, $brewing);
		
		$query_style_type = sprintf("SELECT styleTypeName FROM %s WHERE id='%s'", $prefix."style_types", $type); 
		$style_type = mysql_query($query_style_type, $brewing) or die(mysql_error());
		$row_style_type = mysql_fetch_assoc($style_type);
		$type = $row_style_type['styleTypeName'];
	}
	return $type;
}

function check_bos_loc($id) { 
	require(CONFIG.'config.php');
	$query_judging = sprintf("SELECT judgingLocName,judgingDate FROM %s WHERE id='$id'", $prefix."judging_locations");
	$judging = mysql_query($query_judging, $brewing) or die(mysql_error());
	$row_judging = mysql_fetch_assoc($judging);
	$totalRows_judging = mysql_num_rows($judging);
	$bos_loc = $row_judging['judgingLocName']." (".date_convert($row_judging['judgingDate'], 3, $row_prefs['prefsDateFormat']).")";
	return $bos_loc;
}

function bos_method($value) {
	switch($value) {
		case "1": $bos_method = "1st place only";
		break;
		case "2": $bos_method = "1st and 2nd places only";
		break;
		case "3": $bos_method = "1st, 2nd, and 3rd places";
		break;
		case "4": $bos_method = "Defined by Admin";
		break;
	}
	return $bos_method;
}

function text_number($n) {
    # Array holding the teen numbers. If the last 2 numbers of $n are in this array, then we'll add 'th' to the end of $n
    $teen_array = array(11, 12, 13, 14, 15, 16, 17, 18, 19);
   
    # Array holding all the single digit numbers. If the last number of $n, or if $n itself, is a key in this array, then we'll add that key's value to the end of $n
    $single_array = array(1 => 'st', 2 => 'nd', 3 => 'rd', 4 => 'th', 5 => 'th', 6 => 'th', 7 => 'th', 8 => 'th', 9 => 'th', 0 => 'th');
   
    # Store the last 2 digits of $n in order to check if it's a teen number.
    $if_teen = substr($n, -2, 2);
   
    # Store the last digit of $n in order to check if it's a teen number. If $n is a single digit, $single will simply equal $n.
    $single = substr($n, -1, 1);
   
    # If $if_teen is in array $teen_array, store $n with 'th' concantenated onto the end of it into $new_n
    if (in_array($if_teen, $teen_array)) {
        $new_n = $n . 'th';
    	}
    # $n is not a teen, so concant the appropriate value of it's $single_array key onto the end of $n and save it into $new_n
    elseif ($single_array[$single])  {
        $new_n = $n . $single_array[$single];   
    	}
		
    # Return new
    return $new_n;
}

function style_choose($section,$go,$action,$filter,$view,$script_name,$method) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	
	if ($method == "thickbox") { $suffix = ''; $class = 'class="menuItem" id="modal_window_link"'; }
	
	if ($method == "none") { $suffix = '';  $class = 'class="menuItem"'; }
	
	$random = random_generator(7,2);
	
	$style_choose = '<div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, \'menu_categories'.$random.'\');">Select Below...</a></div>';
	$style_choose .= '<div id="menu_categories'.$random.'" class="menu" onmouseover="menuMouseover(event)">';
	for($i=1; $i<29; $i++) { 
		if ($i <= 9) $num = "0".$i; else $num = $i;
		$query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewCategory='%s'", $prefix."brewing", $i);
		$result = mysql_query($query_entry_count, $brewing) or die(mysql_error());
		$row = mysql_fetch_array($result);
		//if ($num == $filter) $selected = ' "selected"'; else $selected = '';
		if ($row['count'] > 0) { $style_choose .= '<a '.$class.' style="font-size: 0.9em; padding: 1px;" href="'.$script_name.'?section='.$section.'&go='.$go.'&action='.$action.'&filter='.$num.$suffix.'&view='.$view.'" title="Print '.style_convert($i,"1").'">'.$num.' '.style_convert($i,"1").' ('.$row['count'].' entries)</a>'; }
		mysql_free_result($result);
	}
	
	$query_styles = sprintf("SELECT brewStyle,brewStyleGroup FROM %s WHERE brewStyleGroup >= 29", $prefix."styles");
	$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
	$row_styles = mysql_fetch_assoc($styles);
	$totalRows_styles = mysql_num_rows($styles);
	
	do {  
		$query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewCategorySort='%s'", $prefix."brewing", $row_styles['brewStyleGroup']);
		$result = mysql_query($query_entry_count, $brewing) or die(mysql_error());
		$row = mysql_fetch_array($result);
		//if ($row_styles['brewStyleGroup'] == $filter) $selected = ' "selected"'; else $selected = '';
		if ($row['count'] > 0) { $style_choose .= '<a '.$class.' style="font-size: 0.9em; padding: 1px;" href="'.$script_name.'?section='.$section.'&go='.$go.'&action='.$action.'&filter='.$row_styles['brewStyleGroup'].$suffix.'" title="Print '.$row_styles['brewStyle'].'">'.$row_styles['brewStyleGroup'].' '.$row_styles['brewStyle'].' ('.$row['count'].' entries)</a>'; } 
		mysql_free_result($result);
	} while ($row_styles = mysql_fetch_assoc($styles));
	
	
	$style_choose .= '</div>';
	mysql_free_result($styles);
	return $style_choose;   			
}

function table_location($table_id,$date_format,$time_zone,$time_format) { 
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	$query_table = sprintf("SELECT tableLocation FROM %s WHERE id='%s'", $prefix."judging_tables", $table_id);
	$table = mysql_query($query_table, $brewing) or die(mysql_error());
	$row_table = mysql_fetch_assoc($table);
	
	$query_location = sprintf("SELECT judgingLocName,judgingDate,judgingTime FROM %s WHERE id='%s'", $prefix."judging_locations", $row_table['tableLocation']);
	$location = mysql_query($query_location, $brewing) or die(mysql_error());
	$row_location = mysql_fetch_assoc($location);
	$totalRows_location = mysql_num_rows($location);
	
	if ($totalRows_location == 1) {
    $table_location = $row_location['judgingLocName']." - ".getTimeZoneDateTime($time_zone, $row_location['judgingDate'], $date_format,  $time_format, "long", "date-time-no-gmt");
	}
	else $table_location = ""; 
	mysql_free_result($table);
	mysql_free_result($location);
	return $table_location;
}

function flight_count($table_id,$method) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	$query_flights = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE flightTable='%s'", $prefix."judging_flights", $table_id);
	$flights = mysql_query($query_flights, $brewing) or die(mysql_error());
	$row_flights = mysql_fetch_assoc($flights);
	
	switch($method) {
		case "1": if ($row_flights['count'] > 0) return true; else return false;
		break;
		
		case "2": return $row_flights['count'];
		break;
	}
}

function score_count($table_id,$method) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	$query_scores = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE scoreTable='%s'", $prefix."judging_scores", $table_id);
	$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
	$row_scores = mysql_fetch_assoc($scores);
	
	switch($method) {
		case "1": if ($row_scores['count'] > 0) return true; else return false;
		break;
		
		case "2": return $row_scores['count'];
		break;
	}
	
	mysql_free_result($scores);
	
}


	
function orphan_styles() { 
	require(CONFIG.'config.php');
	$query_styles = sprintf("SELECT id,brewStyle,brewStyleType FROM %s WHERE brewStyleGroup >= 29", $prefix."styles");
	$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
	$row_styles = mysql_fetch_assoc($styles);
	$totalRows_styles = mysql_num_rows($styles);
	
	$query_style_types = sprintf("SELECT id FROM %s WHERE styleTypeOwn = 'custom'", $prefix."style_types");
	$style_types = mysql_query($query_style_types, $brewing) or die(mysql_error());
	$row_style_types = mysql_fetch_assoc($style_types);
	$totalRows_style_types = mysql_num_rows($style_types);
	
	do { $a[] = style_type($row_style_types['id'], "2", "bcoe"); } while ($row_style_types = mysql_fetch_assoc($style_types));

	$return = "";
	if ($totalRows_styles > 0) {
		do {
			if (!in_array($row_styles['brewStyleType'], $a)) { 
				if ($row_styles['brewStyleType'] > 3) $return .= "<p><a href='index.php?section=admin&amp;go=styles&amp;action=edit&amp;id=".$row_styles['id']."'><span class='icon'><img src='images/pencil.png' alt='Edit ".$row_styles['brewStyle']."' title='Edit ".$row_styles['brewStyle']."'></span></a>".$row_styles['brewStyle']."</p>";
			}
		} while ($row_styles = mysql_fetch_assoc($styles));
	}
	if ($return == "") $return .= "<p>All custom styles have a valid style type associated with them.</p>";
	return $return;

}

function bjcp_rank($rank,$method) {
    if ($method == "1") {
		switch($rank) {
			case "Apprentice": $return = "Level 1:";
			break;
			case "Recognized": $return = "Level 2:";
			break;
			case "Certified": $return = "Level 3:";
			break;
			case "National": $return = "Level 4:";
			break;
			case "Master": $return = "Level 5:";
			break;
			case "Grand Master": $return = "Level 6:";
			break;
			case "Honorary Master": $return = "Level 5:";
			break;
			case "Honorary Grand Master": $return = "Level 6:";
			break;
			case "Experienced": $return = "Level 0:";
			break;
			case "Professional Brewer": $return = "Level 2:";
			break;
			default: $return = "";
		}
	if (($rank != "None") && ($rank != "")) $return .= " ".$rank;
	}
	
	if ($method == "2") {
		switch($rank) {
			case "None": $return = "Experienced Judge";
			break;
			case "": $return = "Experienced Judge";
			break;
			case "Professional Brewer": $return = $rank;
			break;
			case "Experienced": $return = $rank." Judge";
			break;
			default: $return = "BJCP ".$rank." Judge";
		}
	}
	
	return $return;
}


function srm_color($srm,$method) {
	if ($method == "ebc") $srm = (1.97 * $srm); else $srm = $srm;
	
    if ($srm >= 01 && $srm < 02) $return = "#f3f993";
	elseif ($srm >= 02 && $srm < 03) $return = "#f5f75c";
	elseif ($srm >= 03 && $srm < 04) $return = "#f6f513";
	elseif ($srm >= 04 && $srm < 05) $return = "#eae615";
	elseif ($srm >= 05 && $srm < 06) $return = "#e0d01b";
	elseif ($srm >= 06 && $srm < 07) $return = "#d5bc26";
	elseif ($srm >= 07 && $srm < 08) $return = "#cdaa37";
	elseif ($srm >= 08 && $srm < 09) $return = "#c1963c";
	elseif ($srm >= 09 && $srm < 10) $return = "#be8c3a";
	elseif ($srm >= 10 && $srm < 11) $return = "#be823a";
	elseif ($srm >= 11 && $srm < 12) $return = "#c17a37";
	elseif ($srm >= 12 && $srm < 13) $return = "#bf7138";
	elseif ($srm >= 13 && $srm < 14) $return = "#bc6733";
	elseif ($srm >= 14 && $srm < 15) $return = "#b26033";
	elseif ($srm >= 15 && $srm < 16) $return = "#a85839";
	elseif ($srm >= 16 && $srm < 17) $return = "#985336";
	elseif ($srm >= 17 && $srm < 18) $return = "#8d4c32";
	elseif ($srm >= 18 && $srm < 19) $return = "#7c452d";
	elseif ($srm >= 19 && $srm < 20) $return = "#6b3a1e";
	elseif ($srm >= 20 && $srm < 21) $return = "#5d341a";
	elseif ($srm >= 21 && $srm < 22) $return = "#4e2a0c";
	elseif ($srm >= 22 && $srm < 23) $return = "#4a2727";
	elseif ($srm >= 23 && $srm < 24) $return = "#361f1b";
	elseif ($srm >= 24 && $srm < 25) $return = "#261716";
	elseif ($srm >= 25 && $srm < 26) $return = "#231716";
	elseif ($srm >= 26 && $srm < 27) $return = "#19100f";
	elseif ($srm >= 27 && $srm < 28) $return = "#16100f";
	elseif ($srm >= 28 && $srm < 29) $return = "#120d0c";
	elseif ($srm >= 29 && $srm < 30) $return = "#100b0a";
	elseif ($srm >= 30 && $srm < 31) $return = "#050b0a";
	elseif ($srm > 31) $return = "#000000";
  	else $return = "#ffffff";
return $return;
}

function get_contact_count() {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	$query_contact_count = sprintf("SELECT COUNT(*) as 'count' FROM %s",$prefix."contacts");
	$result = mysql_query($query_contact_count, $brewing) or die(mysql_error());
	$row = mysql_fetch_assoc($result);
	$contactCount = $row["count"];
	mysql_free_result($result);
	return $contactCount;
}

function brewer_info($bid) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	$query_brewer_info = sprintf("SELECT brewerFirstName,brewerLastName,brewerPhone1,brewerJudgeRank,brewerJudgeID,brewerJudgeBOS,brewerEmail,uid FROM %s WHERE uid='%s'", $prefix."brewer", $bid);
	$brewer_info = mysql_query($query_brewer_info, $brewing) or die(mysql_error());
	$row_brewer_info = mysql_fetch_assoc($brewer_info);
	$r = $row_brewer_info['brewerFirstName']."^".$row_brewer_info['brewerLastName']."^".$row_brewer_info['brewerPhone1']."^".$row_brewer_info['brewerJudgeRank']."^".$row_brewer_info['brewerJudgeID']."^".$row_brewer_info['brewerJudgeBOS']."^".$row_brewer_info['brewerEmail']."^".$row_brewer_info['uid'];
	return $r;
}

function get_entry_count() {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	
	$query_paid = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewReceived='1'",$prefix."brewing");
	$paid = mysql_query($query_paid, $brewing) or die(mysql_error());
	$row_paid = mysql_fetch_assoc($paid);
	$r = $row_paid['count'];
	return $r;
	mysql_free_result($row_paid);
}

function get_participant_count($type) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	if ($type == 'default') $query_participant_count = sprintf("SELECT COUNT(*) as 'count' FROM %s",$prefix."brewer");
	if ($type == 'judge') $query_participant_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewerJudge='Y'",$prefix."brewer");
	if ($type == 'steward') $query_participant_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewerSteward='Y'",$prefix."brewer");
	$participant_count = mysql_query($query_participant_count, $brewing) or die(mysql_error());
	$row_participant_count = mysql_fetch_assoc($participant_count);
	
	return $row_participant_count['count'];
	
	mysql_free_result($participant_count);

}

function display_place($place,$method) {
	if ($method == "1") { 
		switch($place){
			case "1": $place = "1st";
			break;
			case "2": $place = "2nd";
			break;
			case "3": $place = "3rd";
			break;
			case "4": $place = "4th";
			break;
			case "5": $place = "HM";
			break;
		default: $place = "None";
		}
	}
	if ($method == "2") { 
		switch($place){
			case "1": $place = "<span class=\"icon\"><img src=\"images/medal_gold_3.png\"></span>1st";
			break;
			case "2": $place = "<span class=\"icon\"><img src=\"images/medal_silver_3.png\"></span>2nd";
			break;
			case "3": $place = "<span class=\"icon\"><img src=\"images/medal_bronze_3.png\"></span>3rd";
			break;
			case "4": $place = "<span class=\"icon\"><img src=\"images/rosette.png\"></span>4th";
			break;
			case "5": $place = "<span class=\"icon\"><img src=\"images/rosette.png\"></span>HM";
			break;
			default: $place = "N/A";
			}
	}
	
	return $place;
}

function entry_info($id) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	$query_entry_info = sprintf("SELECT brewName,brewCategory,brewCategorySort,brewSubCategory,brewStyle,brewCoBrewer,brewJudgingNumber FROM %s WHERE id='%s'", $prefix."brewing", $id);
	$entry_info = mysql_query($query_entry_info, $brewing) or die(mysql_error());
	$row_entry_info = mysql_fetch_assoc($entry_info);
	$r = $row_entry_info['brewName']."^".$row_entry_info['brewCategorySort']."^".$row_entry_info['brewSubCategory']."^".$row_entry_info['brewStyle']."^".$row_entry_info['brewCoBrewer']."^".$row_entry_info['brewCategory']."^".$row_entry_info['brewJudgingNumber'];
	return $r;
}

function get_suffix($dbTable) {
	/*
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	$query_suffix = sprintf("SELECT archiveSuffix FROM %s WHERE id='%s'", $prefix."archive");
	$suffix = mysql_query($suffix, $brewing) or die(mysql_error());
	$row_suffix = mysql_fetch_assoc($suffix);
	do { $a[] = $row_suffix['archiveSuffix']; } while ($row_suffix = mysql_fetch_assoc($suffix));
	*/
	
	$suffix = strrchr($dbTable,"_");
	$suffix = ltrim($suffix, "_");
	/*
	
	if (strstr($dbTable,"judging_tables")) $suffix = ltrim($dbTable,"judging_tables");
	if (strstr($dbTable,"judging_flights")) $suffix = ltrim($dbTable,"judging_flights");
	if (strstr($dbTable,"judging_scores")) $suffix = ltrim($dbTable,"judging_scores");
	if (strstr($dbTable,"judging_scores_bos")) $suffix = ltrim($dbTable,"judging_scores_bos");
	if (strstr($dbTable,"brewing"))  $suffix = ltrim($dbTable,"brewing");
	if (strstr($dbTable,"brewer"))  $suffix = ltrim($dbTable,"brewer");
	if (strstr($dbTable,"style_types"))  $suffix = ltrim($dbTable,"style_types");
	if (strstr($dbTable,"special_best_data"))  $suffix = ltrim($dbTable,"special_best_data");
	if (strstr($dbTable,"special_best_info"))  $suffix = ltrim($dbTable,"special_best_info");
	*/
	return $suffix;
}

function score_table_choose($dbTable,$judging_tables_db_table,$judging_scores_db_table) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	$query_tables = "SELECT id,tableNumber,tableName FROM $judging_tables_db_table ORDER BY tableNumber ASC";
	$tables = mysql_query($query_tables, $brewing) or die(mysql_error());
	$row_tables = mysql_fetch_assoc($tables);
	$totalRows_tables = mysql_num_rows($tables); 
	//echo $query_tables;
	
	if ($totalRows_tables > 0) {
	$r = "<select name=\"table_choice_1\" id=\"table_choice_1\" onchange=\"jumpMenu('self',this,0)\">";
	$r .= "<option>Choose Below:</option>";
		do { 
		$query_scores = sprintf("SELECT COUNT(*) as 'count' FROM $judging_scores_db_table WHERE scoreTable='%s'", $row_tables['id']);
		$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
		$row_scores = mysql_fetch_assoc($scores);
		if ($row_scores['count'] > 0) $a = "edit"; else $a = "add";
		if (!get_table_info($row_tables['id'],"count_scores",$row_tables['id'],$dbTable,"default")) { 
        	$r .= "<option value=\"index.php?section=admin&amp;&go=judging_scores&amp;action=".$a."&amp;id=".$row_tables['id']."\">Table #".$row_tables['tableNumber'].": ".$row_tables['tableName']."</option>";
		 	} 
		    mysql_free_result($scores);
		} while ($row_tables = mysql_fetch_assoc($tables));
     $r .= "</select>";
	} 
	 else $r = "No tables have been defined.";
	 mysql_free_result($tables);
	 return $r;
}

function score_custom_winning_choose($special_best_info_db_table,$special_best_data_db_table) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	$query_sbi = "SELECT id,sbi_name FROM $special_best_info_db_table ORDER BY sbi_name ASC";
	$sbi = mysql_query($query_sbi, $brewing) or die(mysql_error());
	$row_sbi = mysql_fetch_assoc($sbi);
	$totalRows_sbi = mysql_num_rows($sbi); 
	//echo $query_tables;
	if ($totalRows_sbi > 0) {
	$r = "<select name=\"sbi_choice_1\" id=\"sbi_choice_1\" onchange=\"jumpMenu('self',this,0)\">";
	$r .= "<option>Choose Below:</option>";
		do { 
		$query_scores = sprintf("SELECT COUNT(*) as 'count' FROM $special_best_data_db_table WHERE sid='%s'", $row_sbi['id']);
		$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
		$row_scores = mysql_fetch_assoc($scores);
		if ($row_scores['count'] > 0) $a = "edit"; else $a = "add";
        	$r .= "<option value=\"index.php?section=admin&amp;&go=special_best_data&amp;action=".$a."&amp;id=".$row_sbi['id']."\">".$row_sbi['sbi_name']."</option>";
		    mysql_free_result($scores);
		} while ($row_sbi = mysql_fetch_assoc($sbi));
     $r .= "</select>";
	} 
	else $r = "No custom winning cateories have been defined.";
	 mysql_free_result($sbi);
	 return $r;
}

function score_check($id,$judging_scores_db_table) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	$query_scores = sprintf("SELECT scoreEntry FROM %s WHERE eid='%s'",$judging_scores_db_table,$id);
	$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
	$row_scores = mysql_fetch_assoc($scores);
	
	$r = $row_scores['scoreEntry']; 
	return $r;
}

function winner_check($id,$judging_scores_db_table,$judging_tables_db_table,$method) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	
	$query_scores = sprintf("SELECT scorePlace,scoreTable FROM %s WHERE eid='%s'",$judging_scores_db_table,$id);
	$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
	$row_scores = mysql_fetch_assoc($scores);
	
	if ($row_scores['scorePlace'] >= "1") {
		$query_table = sprintf("SELECT tableName FROM $judging_tables_db_table WHERE id='%s'", $row_scores['scoreTable']);
		$table = mysql_query($query_table, $brewing) or die(mysql_error());
		$row_table = mysql_fetch_assoc($table);
		$r = display_place($row_scores['scorePlace'],$method).": ".$row_table['tableName'];
	} 
	else $r = "-";
	
	//$r = "<td class=\"dataList\">".$query_scores."<br>".$query_table."</td>";
	return $r;
}

function timezone_name($GMT) {
	switch($GMT) {
		case "-12": $timezone_name = "Niue Time";
		break;
		case "-11": $timezone_name = "Somoa Time";
		break;
        case "-10": $timezone_name = "Hawaii-Aleutian Time";
		break;
        case "-9": $timezone_name = "Alaska Time";
		break; 
        case "-8": $timezone_name = "Pacific Time";
		break; 
        case "-7": $timezone_name = "Mountain Time";
		break; 
        case "-6": $timezone_name = "Central Time";
		break; 
        case "-5": $timezone_name = "Eastern Time";
		break;
        case "-4": $timezone_name = "Atlantic Time";
		break; 
       	case "-3.5": $timezone_name = "Newfoundland Time";
		break; 
        case "-3": $timezone_name = "Western Greenland Time";
		break; 
        case "-2": $timezone_name = "South Sandwich Islands Time";
		break;
        case "-1": $timezone_name = "Somoa Standard Time";
		break; 
        case "0": $timezone_name = "Western European Time";
		break;
        case "1": $timezone_name = "Central European Time";
		break;
        case "2": $timezone_name = "Eastern European Time";
		break;
        case "3": $timezone_name = "East Africa Time";
		break; 
        case "3.5": $timezone_name = "Iran Standard Time";
		break; 
        case "4": $timezone_name = "Gulf Standard Time";
		break;
        case "4.5": $timezone_name = "Afghanistan Time";
		break; 
        case "5": $timezone_name = "Pakistan Standard Time";
		break; 
        case "5.5": $timezone_name = "Indian Standard Time";
		break; 
        case "6": $timezone_name = "British Indian Ocean Time";
		break; 
        case "7": $timezone_name = "Indochina Time";
		break; 
        case "8": $timezone_name = "ASEAN Common Time";
		break;
        case "9": $timezone_name = "Australian Western Standard Time";
		break;
        case "9.5": $timezone_name = "Australian Central Standard Time";
		break;
        case "10": $timezone_name = "Australian Eastern Standard Time";
		break;
        case "11": $timezone_name = "Solomon Islands Time";
		break;
        case "12": $timezone_name = "New Zeland Standard Time";
		break;
		case "13": $timezone_name = "Phoenix Island Time";
		break;
	}
	return $timezone_name;
}



function brewer_assignment($a,$method){ 
	switch($method) {
	case "1": // 
		if ($a == "J") $r = "Judge"; 
		elseif ($a == "S") $r = "Steward"; 
		elseif ($a == "X") $r = "Staff";
		elseif ($a == "O") $r = "Organizer"; 
		else $r = "";
	break;
	case "2": // for $filter URL variable
		if ($a == "judges") $r = "J"; 
		elseif ($a == "stewards") $r = "S"; 
		elseif ($a == "staff") $r = "X";
		elseif ($a == "bos") $r = "Y";
		else $r = "";
	break;
	case "3": // for $filter URL variable
		if ($a == "judges") $r = "Judges"; 
		elseif ($a == "stewards") $r = "Stewards"; 
		elseif ($a == "staff") $r = "Staff";
		elseif ($a == "bos") $r = "BOS Judges";
		else $r = "";
	break;
	}
return $r;
}

function entries_unconfirmed($user_id) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);	
	$query_entry_check = sprintf("SELECT id FROM %s WHERE brewBrewerID='%s' AND brewConfirmed='0'", $prefix."brewing", $user_id);
	$entry_check = mysql_query($query_entry_check, $brewing) or die(mysql_error());
	$row_entry_check = mysql_fetch_assoc($entry_check);
	$totalRows_entry_check = mysql_num_rows($entry_check); 
	
	if ($totalRows_entry_check > 0)	return $totalRows_entry_check; else return 0;
}

function entries_no_special($user_id) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);	
	$query_entry_check = sprintf("SELECT id FROM %s WHERE brewBrewerID='%s' AND brewInfo IS NULL 
							   AND (
									(brewCategorySort = '16' AND brewSubCategory = 'E') OR 
									(brewCategorySort = '17' AND brewSubCategory = 'F') OR 
									(brewCategorySort = '20' AND brewSubCategory = 'A') OR 
									(brewCategorySort = '21' AND brewSubCategory = 'A') OR 
									(brewCategorySort = '22' AND brewSubCategory = 'B') OR 
									(brewCategorySort = '23' AND brewSubCategory = 'A') OR 
									(brewCategorySort = '25' AND brewSubCategory = 'C') OR 
									(brewCategorySort = '26' AND brewSubCategory = 'A') OR 
									(brewCategorySort = '27' AND brewSubCategory = 'E') OR 
									(brewCategorySort = '28' AND brewSubCategory = 'B') OR 
									(brewCategorySort = '28' AND brewSubCategory = 'C') OR 
									(brewCategorySort = '28' AND brewSubCategory = 'D') OR
									brewCategorySort >  '28'
									)
							   ", $prefix."brewing", $user_id);
	$entry_check = mysql_query($query_entry_check, $brewing) or die(mysql_error());
	$row_entry_check = mysql_fetch_assoc($entry_check);
	$totalRows_entry_check = mysql_num_rows($entry_check); 
	
	if ($totalRows_entry_check > 0)	return $totalRows_entry_check; else return 0;
}


function data_integrity_check() {
	// Match user emails against the record in the brewer table,
	// Compare user's id against uid,
	// If no match, replace uid with user's id
	// This prevents "lost" entries in the system
	
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	
	$query_user_check = sprintf("SELECT id,user_name FROM %s", $prefix."users");
	$user_check = mysql_query($query_user_check, $brewing) or die(mysql_error());
	$row_user_check = mysql_fetch_assoc($user_check);
	
	do { 
	
		// Get Brewer Info
		$query_brewer = sprintf("SELECT id,uid,brewerEmail,brewerFirstName,brewerLastname FROM %s WHERE brewerEmail='%s'",$prefix."brewer",$row_user_check['user_name']);
		$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
		$row_brewer = mysql_fetch_assoc($brewer);
		$totalRows_brewer = mysql_num_rows($brewer);
		
		// Check to see if info is matching up. If not...
		if (($row_brewer['brewerEmail'] == $row_user_check['user_name']) && ($row_brewer['uid'] != $row_user_check['id']) && ($totalRows_brewer == 1)) {
			// ...Update to the correct uid
			$updateSQL = sprintf("UPDATE %s SET uid='%s' WHERE id='%s'", $prefix."brewer", $row_user_check['id'], $row_brewer['id']);
			$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
			
			// Change all associated entries to the correct uid (brewBrewerID row) in the "brewing" table
			$query_brewer_entries = sprintf("SELECT id FROM %s WHERE brewBrewerLastName='%s' AND brewBrewerFirstName='%s'",$prefix."brewing",$row_brewer['brewerLastName'],$row_brewer['brewerLastName']);
			$brewer_entries = mysql_query($query_brewer_entries, $brewing) or die(mysql_error());
			$row_brewer_entries = mysql_fetch_assoc($brewer_entries);
			$totalRows_brewer_entries = mysql_num_rows($brewer_entries);
			
			if ($totalRows_brewer_entries > 0) {
				do {
					$updateSQL = sprintf("UPDATE %s SET brewBrewerID='%s' WHERE id='%s'", $prefix."brewing", $row_brewer_entries['id'], $row_brewer['id']);
					$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
				} while ($row_brewer_entries = mysql_fetch_assoc($brewer_entries));
			}
			
		} // end if (($row_brewer['brewerEmail'] == $row_user_check['user_name']) && ($row_brewer['uid'] != $row_user_check['id']) && ($totalRows_brewer == 1))
		
		
		// Delete user record if no record of the user's extended information is found in the "brewer" table
		if ($totalRows_brewer == 0) {	
			$deleteSQL = sprintf("DELETE FROM %s WHERE id='%s'", $prefix."users", $row_user_check['id']);
  			$result = mysql_query($deleteSQL, $brewing) or die(mysql_error());		
			
			// Check to see if there are entries under that uid. If so, delete.
			$query_brewer_entries = sprintf("SELECT id FROM %s WHERE brewBrewerID='%s'",$prefix."brewing",$row_user_check['id']);
			$brewer_entries = mysql_query($query_brewer_entries, $brewing) or die(mysql_error());
			$row_brewer_entries = mysql_fetch_assoc($brewer_entries);
			$totalRows_brewer_entries = mysql_num_rows($brewer_entries);
			
			if ($totalRows_brewer_entries > 0) {
				do {
					$deleteSQL = sprintf("DELETE FROM %s WHERE id='%s'", $prefix."brewing", $row_brewer_entries['id']);
					$result = mysql_query($deleteSQL, $brewing) or die(mysql_error());		
				} while ($row_brewer_entries = mysql_fetch_assoc($brewer_entries));
			}

		} // end if ($totalRows_brewer == 0)
		
	} while ($row_user_check = mysql_fetch_assoc($user_check));
	
	
	// Check if there are "blank" entries. If so, delete.
	$query_blank = sprintf("SELECT id FROM %s WHERE 
							 (brewStyle IS NULL OR brewStyle = '')
							 AND 
							 (brewCategory IS NULL OR brewCategory = '')
							 AND 
							 (brewCategorySort IS NULL OR brewCategorySort = '')
							 AND 
							 (brewBrewerID IS NULL OR brewBrewerID = '')
							 ",$prefix."brewing");
	$blank = mysql_query($query_blank, $brewing) or die(mysql_error());
	$row_blank = mysql_fetch_assoc($blank);
	$totalRows_blank = mysql_num_rows($blank);
	
	if ($totalRows_blank > 0) {
		do {
			$deleteSQL = sprintf("DELETE FROM %s WHERE id='%s'", $prefix."brewing", $row_blank['id']);
			$result = mysql_query($deleteSQL, $brewing) or die(mysql_error());
		} while ($row_blank = mysql_fetch_assoc($blank));	
	}
	
	// Last update the "system" table with the date/time the function ended
	$updateSQL = sprintf("UPDATE %s SET data_check=%s WHERE id='1'", $prefix."system", "NOW( )");
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
	
} // END function


?>