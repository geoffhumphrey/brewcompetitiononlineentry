<?php

function dropoff_loc($id) {
	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$db_conn->where('brewerDropOff', $id);
	$row_dropoffs_user = $db_conn->getOne($prefix."brewer", "uid");
	$totalRows_dropoffs_user = $db_conn->count;

	$return = $totalRows_dropoffs_user."^".$row_dropoffs_user['uid'];

	return $return;
}

function location_count($location_id) {

	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$location_count = 0;
	$uid = array();

	$db_conn->where('brewerDropOff', $location_id);
	$rows_dropoff = $db_conn->get($prefix."brewer", null, "uid");

	if (!empty($rows_dropoff)) {

		foreach ($rows_dropoff as $row_dropoff) { $uid[] = $row_dropoff['uid']; }

		foreach ($uid as $brewBrewerID) {

			$db_conn->where('brewBrewerID', $brewBrewerID);
			$row_dropoffs = $db_conn->getOne($prefix."brewing", "COUNT(*) as 'count'");

			$location_count += $row_dropoffs['count'];

		}

	}

	return $location_count;
}

function dropoff_location_info($location_id) {

	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$db_conn->where('id', $location_id);
	$row_location_info = $db_conn->getOne($prefix."drop_off", "id,dropLocation,dropLocationName");

	$return =
	$row_location_info['id']."^".
	$row_location_info['dropLocation']."^".
	$row_location_info['dropLocationName'];

	return $return;

}

function entries_by_dropoff_loc($id) {

	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$db_conn->where('brewerDropOff', $id);
	$rows_dropoffs = $db_conn->get($prefix."brewer", null, "uid");
	$totalRows_dropoffs = $db_conn->count;

	$build_rows = "";

	if ($totalRows_dropoffs > 0) {

		foreach ($rows_dropoffs as $row_dropoffs) {

			$db_conn->where('brewBrewerID', $row_dropoffs['uid']);
			$rows_dropoff_count = $db_conn->get($prefix."brewing");
			$totalRows_dropoff_count = $db_conn->count;

			if ($totalRows_dropoff_count > 0) {
				foreach ($rows_dropoff_count as $row_dropoff_count) {
					$entry_name = html_entity_decode($row_dropoff_count['brewName'],ENT_QUOTES|ENT_XML1,"UTF-8");
					$entry_name = htmlentities($entry_name,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5,"UTF-8");
					$build_rows .= "
						<tr>
							<td>".sprintf("%06s",$row_dropoff_count['id'])."</td>
							<td>".$entry_name."</td>
							<td>".h($row_dropoff_count['brewBrewerLastName']).", ".h($row_dropoff_count['brewBrewerFirstName'])."</td>
							<td><p class=\"box_small\"></p></td>
						</tr>
				";

				}

			} // end if ($totalRows_dropoff_count > 0)

		}

	} // end if ($totalRows_dropoffs > 0)

	return $build_rows;
}

// --------------------------------------------------------
// The following apply to:	/output/email_export.php
//							/output/entries_export.php
// --------------------------------------------------------

function parseCSVComments($comments) {

	// First, escape all " and make them ""
	$comments = str_replace('"', '""', $comments);
	$comments = preg_replace("/[\n\r]/","",$comments);

	// Check if any commas or new lines
	if((strpos($comments, ",") !== false) or (strpos($comments, "\n") !== false) or (strpos($comments, "\t") !== false) or (strpos($comments, "\r") !== false) or (strpos($comments, "\v") !== false)) {

		// If new lines or commas and escape them
		return '"'.$comments.'"';

	}

	// If no new lines or commas just return the value
	else return $comments;
}

function filename($input) {

	if ($input == "default") $return = "";
	else {
		$return = str_replace('_', ' ',$input);
		$return = ucwords($return);
		$return = "_".str_replace(' ','_',$return);
	}
	return $return;
}

function pay_to_print($prefs_pay,$entry_paid) {
	if (($prefs_pay == "Y") && ($entry_paid == "1")) return TRUE;
	elseif (($prefs_pay == "Y") && ($entry_paid == "0")) return FALSE;
	elseif ($prefs_pay == "N") return TRUE;
}

// --------------------------------------------------------
// The following applies to labels.output.php
// --------------------------------------------------------

function truncate($string, $your_desired_width, $append="", $max_word_length=20) {
  $parts = preg_split('/([\s\n\r]+)/', $string, null, PREG_SPLIT_DELIM_CAPTURE);
  $parts_count = count($parts);

  // Single word: truncate by character count
  if ($parts_count === 1 && mb_strlen($string, 'UTF-8') > $your_desired_width) {
    $append_len = mb_strlen($append, 'UTF-8');
    return mb_substr($string, 0, $your_desired_width - $append_len, 'UTF-8') . $append;
  }

  $length = 0;
  $last_part = 0;
  for (; $last_part < $parts_count; ++$last_part) {
    $length += mb_strlen($parts[$last_part], 'UTF-8');
    if ($length > $your_desired_width) {
      $part = $parts[$last_part];
      if (!preg_match('/[\s\n\r]/', $part) && mb_strlen($part, 'UTF-8') >= $max_word_length) {
        $append_len = mb_strlen($append, 'UTF-8');
        $remaining = $your_desired_width - ($length - mb_strlen($part, 'UTF-8')) - $append_len;
        if ($remaining >= 1) {
          $r = implode(array_slice($parts, 0, $last_part));
          $r .= mb_substr($part, 0, $remaining, 'UTF-8') . $append;
          return $r;
        }
      }
      break;
    }
  }

  $r = implode(array_slice($parts, 0, $last_part));

  if (mb_strlen($string, 'UTF-8') > $your_desired_width) {
    $r = rtrim($r);
    $r .= $append;
  }
  return $r;
}

function user_entry_count($uid,$view) {

	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$judging_numbers = array();
	$entry_numbers = array();
	$user_entry_numbers = "";
	$user_judging_numbers = "";

	if ($view == "entry") $sort = "id";
	else $sort = "brewJudgingNumber";

	$query_with_entries_count = "SELECT DISTINCT id,brewJudgingNumber FROM ".$prefix."brewing"." WHERE brewBrewerID=? AND brewReceived='1' ORDER BY ".$sort." ASC";
	$rows_with_entries_count = $db_conn->rawQuery($query_with_entries_count, array($uid));
	$totalRows_with_entries_count = $db_conn->count;

	if ($totalRows_with_entries_count > 0) {
		foreach ($rows_with_entries_count as $row_with_entries_count) {
			// %06s, not %06d - brewJudgingNumber is a formatted string like
			// "26-020", not a plain integer. %06d numerically casts it, and PHP
			// truncates at the first non-digit character, so every entry in the
			// same category (e.g. "26-020", "26-001", "26-011") collapses to the
			// same "000026" - array_unique() below then discards all but one of
			// them as an apparent duplicate, silently dropping real entries.
			$judging_numbers[] = sprintf("%06s",$row_with_entries_count['brewJudgingNumber']);
			$entry_numbers[] = sprintf("%06d",$row_with_entries_count['id']);
		}

		$user_judging_numbers = implode(", ",array_unique($judging_numbers));
		$user_entry_numbers = implode(", ",array_unique($entry_numbers));
	}

	return $totalRows_with_entries_count."^".$user_entry_numbers."^".$user_judging_numbers;

}

// --------------------------------------------------------
// The following applies to /output/staff_points.php
// --------------------------------------------------------

function round_down_to_hundred($number) {
    if (strlen($number)<3) { $number = $number;	}
	else { $number = substr($number, 0, strlen($number)-2) . "00";	}
    return $number;
}

function total_days() {
	include (CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$db_conn->where('judgingLocType', '2', '<');
	$rows_sessions = $db_conn->get($prefix."judging_locations", null, "judgingDate");

	foreach ($rows_sessions as $row_sessions) {
		$a[] = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_sessions['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-no-gmt");
	}

	$output = array_unique($a);
	$output = count($output);
	return $output;

}

function total_sessions() {
	include (CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$db_conn->where('judgingLocType', '2', '<');
	$row_sessions = $db_conn->getOne($prefix."judging_locations", "COUNT(*) as 'count'");

	/*
	do {
		$a[] = $row_sessions['judgingRounds'];
	} while ($row_sessions = mysqli_fetch_assoc($sessions));
	*/

	return $row_sessions['count'];

}

function total_flights() {
	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);
	$rows_tables = $db_conn->get($prefix."judging_tables", null, "id");

	foreach ($rows_tables as $row_tables) {
		$a[] = $row_tables['id'];
	}

	foreach ($a as $table_id) {
		$db_conn->where('flightTable', $table_id);
		$db_conn->orderBy('flightNumber', 'DESC');
		$row_table_flights = $db_conn->getOne($prefix."judging_flights", "flightNumber");
		$b[] = $row_table_flights['flightNumber'];
	}

	$db_conn->where('styleTypeBOS', 'Y');
	$row_style_types = $db_conn->getOne($prefix."style_types", "COUNT(*) AS 'count'");
	$b[] = $row_style_types['count'];
	$output = array_sum($b);
	return $output;

}

function validate_bjcp_id($input) {
	// BJCP also issues "TEMPDDDD" ids (judges who passed the online exam but not
	// yet the tasting exam) alongside the standard single-letter + 4-digit ids
	if (preg_match('/^TEMP\d{4}$/i', (string) $input)) return TRUE;
	$length = strlen($input);
	if ($length != 5) return FALSE;
	elseif (!preg_match('([a-zA-Z])',$input)) return FALSE;
	else return TRUE;
}

function total_points($total_entries,$method) {

	// Get the maximum allowable points for all roles
	// According to the Maximum Points Earned (Table 1) table - https://bjcp.org/about/reference/experience-point-award-schedule/

	$points = 0;

	switch ($method) {

		case "Organizer":
			if (($total_entries >= 1) && ($total_entries <= 49)) $points = 2.0;
			elseif (($total_entries >= 50) && ($total_entries <= 99)) $points = 2.5;
			elseif (($total_entries >= 100) && ($total_entries <= 149)) $points = 3.0;
			elseif (($total_entries >= 150) && ($total_entries <= 199)) $points = 3.5;
			elseif (($total_entries >= 200) && ($total_entries <= 299)) $points = 4.0;
			elseif (($total_entries >= 300) && ($total_entries <= 399)) $points = 4.5;
			elseif (($total_entries >= 400) && ($total_entries <= 499)) $points = 5.0;
			elseif ($total_entries >= 500) $points = 6.0;
			else $points = 0;
		break;

		case "Staff":
			if (($total_entries >= 1) && ($total_entries <= 49)) $points = 1.0;
			if (($total_entries >= 50) && ($total_entries <= 99)) $points = 2.0;
			if (($total_entries >= 100) && ($total_entries <= 149)) $points = 3.0;
			if (($total_entries >= 150) && ($total_entries <= 199)) $points = 4.0;
			if (($total_entries >= 200) && ($total_entries <= 299)) $points = 5.0;
			if (($total_entries >= 300) && ($total_entries <= 399)) $points = 6.0;
			if (($total_entries >= 400) && ($total_entries <= 499)) $points = 7.0;
			if (($total_entries >= 500) && ($total_entries <= 599)) $points = 8.0;
			if ($total_entries > 599) {
				$total = round_down_to_hundred($total_entries)/100;
				//$points = $total;
				if ($total >= 2) {
					for($i=1; $i<$total+1; $i++) {
						$points = $i+3;
					}
				}
			}
		break;

		case "Judge":
			if (($total_entries >= 1) && ($total_entries <= 49)) $points = 1.5;
			elseif (($total_entries >= 50) && ($total_entries <= 99)) $points = 2.0;
			elseif (($total_entries >= 100) && ($total_entries <= 149)) $points = 2.5;
			elseif (($total_entries >= 150) && ($total_entries <= 199)) $points = 3.0;
			elseif (($total_entries >= 200) && ($total_entries <= 299)) $points = 3.5;
			elseif (($total_entries >= 300) && ($total_entries <= 399)) $points = 4.0;
			elseif (($total_entries >= 400) && ($total_entries <= 499)) $points = 4.5;
			elseif ($total_entries >= 500) $points = 5.5;
			else $points = 0;
		break;

	}

	return number_format($points,1);

}

function judge_points($user_id,$judge_max_points) {

	/**
	 * To figure out judge points, need to assess:
	 *  - Which sessions the judge was assigned to
	 *  - Which day those sessions were on
	 *  - For each day:
	 *    - Determine how many sessions the judge was assigned to and award 0.5 points for each
	 *    - Make sure that number is a minimum of 1.0 and a maximum of 1.5
	 *  - Sum up the daily points
	 *  - Compare that sum to the maximum judge points based upon the table; if more use the max, if less, use the sum
	 */
	
	require (CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);
	require (INCLUDES.'url_variables.inc.php');
	require (INCLUDES.'db_tables.inc.php');
	require (DB.'judging_locations.db.php');

	$days_judged = array();

	$points = 0;

	$rows_judging = $db_conn->get($prefix."judging_locations");
	$totalRows_judging = $db_conn->count;

	foreach ($rows_judging as $row_judging) {

		if ($row_judging['judgingLocType'] < 2) {
			
			// Get date and determine 24 hour window where it falls based upon the time zone
			$timestamp_curr_day_midnight = strtotime(date("Y-m-d", $row_judging['judgingDate']));
			$timestamp_next_day_midnight = $timestamp_curr_day_midnight + (60 * 60 * 24);

			/**
			 * Edited the query below to only take into account Round 1 of the assignment. 
			 * Tables can only be assigned to a single session.
			 * Tables/flights can only be assigned to a single round. 
			 * There will always be a round 1 for all tables/flights.
			 * Reference Issue #1483 on GitHub.
			 * @see https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues/1483
			 */

			$db_conn->where('bid', $user_id);
	        $db_conn->where('assignLocation', $row_judging['id']);
	        $db_conn->where('assignment', 'J');
	        $db_conn->where('assignRound', '1', '<=');
	        $row_assignments = $db_conn->getOne($prefix."judging_assignments", "COUNT(*) as 'count'");

	        if ($row_assignments['count'] > 0) {
				
				$days_judged[] = array (
					"day_midnight" => $timestamp_curr_day_midnight,
					"points" => $row_assignments['count'] * 0.5,
					"distributed" => $row_judging['judgingLocType']
				);

			}

		}

	}

	if (!empty($days_judged)) {

		// Traditional-type sessions are tallied per calendar day (multiple judging_locations
		// rows can share a date - e.g. two rooms running concurrently - so all of a judge's
		// points for that day must be summed before the 1.5/day cap is applied, not capped
		// per row). Distributed sessions aren't tied to a single date - each stands on its
		// own and gets its own 1.5 cap.
		$daily_totals = array();
		$distributed_totals = array();

		foreach ($days_judged as $day) {

			if ($day['distributed'] == 1) {
				$distributed_totals[] = $day['points'];
			}

			else {
				$key = $day['day_midnight'];
				if (!isset($daily_totals[$key])) $daily_totals[$key] = 0;
				$daily_totals[$key] += $day['points'];
			}

		}

		foreach ($daily_totals as $day_total) {
			$points += ($day_total > 1.5) ? 1.5 : $day_total;
		}

		foreach ($distributed_totals as $session_total) {
			$points += ($session_total > 1.5) ? 1.5 : $session_total;
		}

	}

	// Cannot exceed the maximum allowable points for judges for the competition
	if ($points > $judge_max_points) $points = $judge_max_points;
	else $points = $points;

	// If points are below the 1.0 minimum, award minimum
	if ($points < 1) $points = 1;
	else $points = $points;

	return number_format($points,1);

}

function steward_points($user_id) {

	/**
	 * To figure out steward points, need to assess:
	 *  - Which sessions the steward was assigned to
	 *  - Which day those sessions were on
	 *  - For each day:
	 *    - Determine how many sessions the steward was assigned to and award 0.5 points for each
	 *  - Sum up the daily points
	 *  - Maximum of 1.0 points for the entire competition (BJCP states no daily minimum for stewards)
	 */

	require (CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);
	require (INCLUDES.'url_variables.inc.php');
	require (INCLUDES.'db_tables.inc.php');
	require (DB.'judging_locations.db.php');

	$possible_judging_days = array();
	$days_stewarded = array();

	$points = 0;

	$rows_judging = $db_conn->get($prefix."judging_locations");
	$totalRows_judging = $db_conn->count;

	$queries = "";

	foreach ($rows_judging as $row_judging) {

		if ($row_judging['judgingLocType'] < 1) {
			// Get date and determine 24 hour window where it falls based upon the time zone
			$timestamp_curr_day_midnight = strtotime(date("Y-m-d", $row_judging['judgingDate']));
			$timestamp_next_day_midnight = $timestamp_curr_day_midnight + (60 * 60 * 24);
			$possible_judging_days[] = $timestamp_curr_day_midnight;

			/**
			 * Edited the query below to only take into account Round 1 of the assignment. 
			 * Tables can only be assigned to a single session.
			 * Tables/flights can only be assigned to a single round. 
			 * There will always be a round 1 for all tables/flights.
			 * Reference Issue #1483 on GitHub.
			 * @see https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues/1483
			 */
		
			$db_conn->where('bid', $user_id);
	    $db_conn->where('assignLocation', $row_judging['id']);
	    $db_conn->where('assignment', 'S');
	    $db_conn->where('assignRound', '1', '<=');
	    $row_assignments = $db_conn->getOne($prefix."judging_assignments", "COUNT(*) as 'count'");

	    if ($row_assignments['count'] > 0) {
				$days_stewarded[] = $timestamp_curr_day_midnight;
			}
		
		}

	}

	$possible_judging_days = array_unique($possible_judging_days);
	$days_stewarded = array_unique($days_stewarded);

	if (!empty($days_stewarded)) {
		
		foreach ($possible_judging_days as $judging_day) {
			foreach ($days_stewarded as $day) {
				if ($day == $judging_day) {
					$points += 0.5;
				}
			}
		}

		// Cannot exceed more than 1.0 points per competition
		if ($points > 1.0) $points = 1.0; 
		else $points = $points;

	}

	//return $user_id;

	return number_format($points,1);

}

function bos_points($uid) {
	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);
	require(INCLUDES.'db_tables.inc.php');
	$db_conn->where('uid', $uid);
	$row_bos_judges = $db_conn->getOne($prefix."staff", "staff_judge_bos");

	if ($row_bos_judges['staff_judge_bos'] == 1) return TRUE;
	else return FALSE;
}


// --------------------------------------------------------
// The following applies to /output/pullsheets.php
// --------------------------------------------------------

function number_of_flights($table_id) {
    require(CONFIG.'config.php');
    $db_conn = new MysqliDb($connection);

	$db_conn->where('flightTable', $table_id);
	$db_conn->orderBy('flightNumber', 'DESC');
    $row_flights = $db_conn->getOne($prefix."judging_flights", "flightNumber");

	$r = $row_flights['flightNumber'];
	return $r;
}

function check_flight_number($entry_id,$flight,$method) {
	require(CONFIG.'config.php');
  $db_conn = new MysqliDb($connection);

  $r = "";

	$db_conn->where('flightEntryID', $entry_id);
  $row_flights = $db_conn->getOne($prefix."judging_flights", "flightNumber,flightRound");

  if ($row_flights) {
  	if (($method == 0) && ($row_flights['flightNumber'] == $flight)) $r = $row_flights['flightRound'];
  	if ($method == 1) $r = $row_flights['flightNumber'];
  }
	
	return $r;

}

function check_flight_round($flight_round,$round) {

	if ($round == "default") {
		if ($flight_round != "") return TRUE;
		else return FALSE;
	}

	if ($round != "default") {
		if (($flight_round != "") && ($flight_round == $round)) return TRUE;
		else return FALSE;
	}

}

function results_count($style) {
	require(CONFIG.'config.php');
    $db_conn = new MysqliDb($connection);

	$db_conn->where('brewCategorySort', $style);
	$db_conn->where('brewReceived', '1');
	$row_entry_count = $db_conn->getOne($prefix."brewing", "COUNT(*) as 'count'");

	$query_score_count = "SELECT COUNT(*) as 'count' FROM ".$prefix."judging_scores"." a, ".$prefix."brewing"." b, ".$prefix."brewer"." c WHERE b.brewCategorySort=? AND a.eid = b.id AND a.scorePlace IS NOT NULL AND c.uid = b.brewBrewerID";
	$row_score_count = $db_conn->rawQueryOne($query_score_count, array($style));

	return $row_entry_count['count']."^".$row_score_count['count'];

}

function get_flight_info($id) {
	require(CONFIG.'config.php');
    $db_conn = new MysqliDb($connection);

    $db_conn->where('flightEntryID', $id);
    $row_flights = $db_conn->getOne($prefix."judging_flights");
    $totalRows_flights = $db_conn->count;

    if ($totalRows_flights > 0) {
	    $db_conn->where('id', $row_flights['flightTable']);
		$row_tables = $db_conn->getOne($prefix."judging_tables", "id,tableName,tableNumber");

		$return = array(
			"response" => "Assigned",
			"id" => $row_tables['id'],
			"tableName" => $row_tables['tableName'],
			"tableNumber" => $row_tables['tableNumber'],
			"flightNumber" => $row_flights['flightNumber'],
			"flightRound" => $row_flights['flightRound']
		);
	}

	else $return = array("response" => "Not assigned to a table.");

	return $return;
}

?>