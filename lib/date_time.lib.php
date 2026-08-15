<?php
declare(strict_types=1);

function get_timezone($offset) {
	
	$offset = number_format((float)$offset,3);
	
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
        '-5.001' => 'America/Bogota', // No DST for Colombia, Peru
        '-4.000' => 'America/Virgin', // No DST; matches Caracas, La Paz
        '-4.001' => 'America/Asuncion', // DST observed in Paraguay
        '-4.002' => 'America/Halifax', // DST observed in Atlantic Canada
        '-4.003' => 'America/Santiago', // DST observed in Chile (Southern Hemisphere pattern)
        '-4.004' => 'America/Thule', // DST observed in Greenland (Thule/Pituffik)
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
        '9.001' => 'Asia/Seoul', // South Korea (same offset as Tokyo, no DST, but a distinct region/abbreviation)
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

		// Parse the time string as wall time in the given timezone, then read
		// off the UTC Unix epoch. Uses an explicit DateTimeZone rather than
		// date_default_timezone_set() so this never mutates PHP's global
		// default timezone for whatever else runs later in the request.
		try {
			$dt = new DateTime($time_string, new DateTimeZone($timezone));
		}
		catch (Exception $e) {
			return false;
		}

		return $dt->getTimestamp();

	}

	// Method 2: convert from GMT to selected timezone
	if ($method == 2) {

		// GMT date/time is always stored in DB. Apply the provided offset
		// (in hours) to get the "local" epoch representation. Pure integer
		// arithmetic - no timezone state involved.
		$timestamp = $time_string += ($offset * 3600);

		return $timestamp;

	}

}

/**
 * Convert a datetime string displayed by getTimeZoneDateTime() back to a
 * UTC Unix epoch integer for consistent storage.
 *
 * The form displays a datetime in the admin's current prefsTimeZone.
 * This helper parses it in that timezone, then converts to UTC epoch so
 * the stored value is timezone-independent.
 *
 * @param string  $datetime_string   Raw POST value, e.g. "2025-06-15 14:00"
 * @param float   $timezone_offset  prefsTimeZone float from preferences table, e.g. -5.000
 * @return int|false                UTC Unix epoch, or false on failure
 */
function to_utc_epoch($datetime_string, $timezone_offset) {

	if (empty($datetime_string)) return false;

	// Parse the datetime as wall time in the admin's timezone, then read off
	// the UTC Unix epoch. Uses an explicit DateTimeZone rather than
	// date_default_timezone_set() so this never mutates PHP's global default
	// timezone for whatever else runs later in the request - PHP resolves
	// DST internally either way, so no manual offset arithmetic is needed.
	$tz = get_timezone($timezone_offset);

	try {
		$dt = new DateTime($datetime_string, new DateTimeZone($tz));
	}
	catch (Exception $e) {
		return false;
	}

	return $dt->getTimestamp();

}

function getTimeZoneDateTime($timezone_offset, $timestamp, $date_format, $time_format, $display_format, $return_format) {

	$tz = get_timezone($timezone_offset); // convert offset number to PHP timezone

	// Render via an explicit DateTime/DateTimeZone rather than
	// date_default_timezone_set() + date(), so this never mutates PHP's
	// global default timezone for whatever else runs later in the request.
	// The "@timestamp" form always constructs in UTC regardless of the
	// timezone passed to the constructor, so it's set explicitly after.
	$dt = new DateTime('@'.$timestamp);
	$dt->setTimezone(new DateTimeZone($tz));

	switch($display_format) {

		// Long Format
		case "long":
			if ($date_format == "1") $date = $dt->format('l, F j, Y');
			else $date = $dt->format('l j F, Y');
		break;

		// Short Format
		case "short":
			if ($date_format == 1) $date = $dt->format('m/d/Y');
			elseif ($date_format == 2) $date = $dt->format('d/m/Y');
			elseif ($date_format == 999) $date = $dt->format('Y-m-d H:i:s');
			else $date = $dt->format('Y/m/d');
		break;

		// MySQL Format
		case "system":
			$date = $dt->format('Y-m-d');
		break;

		// XML Report Format
		case "xml":
			$date = $dt->format('l j F Y');
		break;

	}

	if ($time_format == "1") $time = $dt->format('H:i');
	else $time = $dt->format('g:i A');

	switch($return_format) {

		case "date-time":
			$return = $date." ".$time.", ".$dt->format('T');
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
			$return = $time.", ".$dt->format('T');
		break;

		case "time":
			$return = $time;
		break;

		case "year":
			$return = $dt->format('Y');
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
	$db_conn = new MysqliDb($connection);

	$r = 0;
	$today = time();

	$rows_check = $db_conn->get($prefix."judging_locations", null, "judgingDate");
	$totalRows_check = $db_conn->count;

	// Check if the start date/time has passed
	// If so, increase output by 1
	if ($totalRows_check > 0) {

		foreach ($rows_check as $row_check) {

			if (isset($row_check['judgingDate'])) {
				if ($row_check['judgingDate'] >= time()) $r += 1;
			}

		}

	}
	
	return $r;

}

?>