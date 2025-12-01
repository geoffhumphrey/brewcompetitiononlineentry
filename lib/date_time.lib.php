<?php
function get_timezone($offset) {
	
	$offset = number_format($offset,3);
	
	$timezones = array(
        '-12.000' => 'Pacific/Kwajalein',
        '-11.000' => 'Pacific/Midway',
        '-10.000' => 'Pacific/Honolulu',
        '-9.500' => 'Pacific/Marquesas',
        '-9.000' => 'America/Anchorage',
        '-8.000' => 'America/Los_Angeles',
		'-7.000' => 'America/Denver',
        '-7.001' => 'America/Phoenix', // No DST for Arizona
        '-6.000' => 'America/Chicago',
		'-6.001' => 'America/Hermosillo', // No DST in this area of Mexico
		'-6.002' => 'America/Regina', // No DST in this area of Canada
        '-5.000' => 'America/New_York',
        '-4.000' => 'America/Virgin',
        '-4.001' => 'America/Asuncion', // DST observed in Paraguay
        '-3.500' => 'America/St_Johns',
        '-3.000' => 'America/Argentina/Buenos_Aires',
		'-3.001' => 'America/Sao_Paulo', // No DST for region of Brazil
        '-2.000' => 'Atlantic/South_Georgia',
        '-1.000' => 'Atlantic/Azores',
        '0.000' => 'Europe/London',
        '1.000' => 'Europe/Paris',
        '2.000' => 'Europe/Helsinki',
        '3.000' => 'Europe/Moscow',
        '3.500' => 'Asia/Tehran',
        '4.000' => 'Asia/Baku',
        '4.500' => 'Asia/Kabul',
        '5.000' => 'Asia/Karachi',
        '5.500' => 'Asia/Calcutta',
		'5.750' => 'Asia/Kathmandu',
        '6.000' => 'Asia/Colombo',
        '7.000' => 'Asia/Bangkok',
        '8.000' => 'Asia/Singapore',
		'8.001' => 'Australia/Perth', // No DST for this part of Australia
        '9.000' => 'Asia/Tokyo',
        '9.500' => 'Australia/Darwin',
        '10.000' => 'Pacific/Guam',
		'10.001' => 'Australia/Brisbane', // No DST for this part of Australia
		'10.002' => 'Australia/Melbourne', // DST observed in this part of Australia
        '11.000' => 'Asia/Magadan',
        '12.000' => 'Asia/Kamchatka',
		'13.000' => 'Pacific/Tongatapu',
    );

	$timezone = $timezones[$offset];
	
	return $timezone;

}

function convert_timestamp($time_string, $timezone, $offset, $method) {

	$timezone = get_timezone($timezone);

	// Method 1: convert to GMT for storage in DB
	if ($method == 1) {

		// 1. convert the time string specified in the current timezone to UTC (GMT) using built in PHP functions
		date_default_timezone_set($timezone);
		$timestamp = strtotime($time_string);

		// 2. return the value
		return $timestamp;

	}

	// Method 2: convert from GMT to selected timezone
	if ($method == 2) {
		
		// GMT date/time is always stored in DB
		// 1. make sure the timezone is UTC (GMT)
		date_default_timezone_set('UTC');

		// 2. convert the GMT timestamp to the desired timezone using the provided offset
		$timestamp = $time_string += ($offset * 3600);

		// 3. return the value
		return $timestamp;

	}

}

function getTimeZoneDateTime($timezone_offset, $timestamp, $date_format, $time_format, $display_format, $return_format) {

	$tz = get_timezone($timezone_offset); // convert offset number to PHP timezone
    date_default_timezone_set($tz);

	switch($display_format) {
		
		// Long Format
		case "long":
			if ($date_format == "1") $date = date('l, F j, Y', $timestamp);
			else $date = date('l j F, Y', $timestamp);
		break;

		// Short Format
		case "short":
			if ($date_format == 1) $date = date('m/d/Y', $timestamp);
			elseif ($date_format == 2) $date = date('d/m/Y',$timestamp);
			elseif ($date_format == 999) $date = date('Y-m-d H:i:s',$timestamp);
			else $date = date('Y/m/d', $timestamp);
		break;

		// MySQL Format
		case "system":
			$date = date('Y-m-d', $timestamp);
		break;

		// XML Report Format
		case "xml":
			$date = date('l j F Y', $timestamp);
		break;
	}

	if ($time_format == "1") $time = date('H:i',$timestamp);
	else $time = date('g:i A',$timestamp);

	switch($return_format) {
		
		case "date-time":
			$return = $date." ".$time.", ".date('T',$timestamp);
		break;
		
		case "date-time-no-gmt":
			$return = $date." ".$time;
		break;
		
		case "date-time-system":
			$return = $date." ".$time;
		break;
		
		case "date-no-gmt":
			$return = $date;
		break;
		
		case "time-gmt":
			$return = $time.", ".date('T',$timestamp);
		break;
		
		case "time":
			$return = $time;
		break;

		case "year":
			$return = date('Y', $timestamp);
		break;
		
		default: $return = $date;
	
	}

	return $return;
}

function greaterDate($start_date, $end_date) {
  
  $start = strtotime($start_date);
  $end = strtotime($end_date);
  
  if ($start > $end) return TRUE;
  else return FALSE;

}

function judging_date_return() {
	
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	$r = 0;
	$today = time();

	$query_check = sprintf("SELECT judgingDate FROM %s", $prefix."judging_locations");
	$check = mysqli_query($connection,$query_check) or die (mysqli_error($connection));
	$row_check = mysqli_fetch_assoc($check);
	$totalRows_check = mysqli_num_rows($check);

	// Check if the start date/time has passed
	// If so, increase output by 1
	if ($totalRows_check > 0) {
		
		do {
			
			if (isset($row_check['judgingDate'])) {
				if ($row_check['judgingDate'] >= time()) $r += 1;
			}

		} while ($row_check = mysqli_fetch_assoc($check));
		
	}
	
	return $r;

}

?>