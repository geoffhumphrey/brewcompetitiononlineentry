<?php

ob_start();
require('../paths.php');
require(CONFIG.'bootstrap.php');
include (LIB.'admin.lib.php');
ini_set('display_errors', 0); // Change to 0 for prod; change to 1 for testing.
ini_set('display_startup_errors', 0); // Change to 0 for prod; change to 1 for testing.
error_reporting(0); // Change to error_reporting(0) for prod; change to E_ALL for testing.

// CSRF: require a same-origin Referer for this session-authenticated write action.
$referrer_ok = (isset($_SERVER['HTTP_REFERER'])) && (parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) === $_SERVER['SERVER_NAME']);

if ((isset($_SESSION['session_set_'.$prefix_session])) && (isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1) && ($referrer_ok)) {

	$score_count = 0;
	$not_imported_count = 0;
	$scored_places_discrepency_count = 0;
	$flagged_count = 0;

	$return_json = array();
	$eval_arr = array();
	$evalPlace = array();

	// First, query DB to see if any evaluations have been recorded

	$row_eval = $db_conn->get($prefix."evaluation");
	$totalRows_eval = $db_conn->count;

	// If no records
	if ($totalRows_eval == 0) {
		$return_json = array(
			"status" => "0",
			"scores_imported" => "0"
		);
	}

	// If there are records, update the scores DB table with scores recorded by judges
	else {

		$db_conn->returnType = 'array';
		$row_scored = $db_conn->get($prefix."judging_scores");
		$totalRows_scored = $db_conn->count;

		$flagged = array();
		$eval_arr = array();
		$scored_arr = array();
		$scored_places = array();
		$scored_places_discrepency = array();
		$singles = array();
		$evalPlace = array();
		$status = 1;

		foreach ($row_scored as $row_scored) {
			$scored_arr[] = $row_scored['eid'];
			$scored_places[] = array(
				"scored_id" => $row_scored['eid'],
				"scored_place" => $row_scored['scorePlace'],
				"scored_minibos" => $row_scored['scoreMiniBOS']
			);
		} 

		foreach ($row_eval as $row_eval) {
			$eval_arr[] = $row_eval['eid'];
		}

		$eval_arr = array_unique($eval_arr);

		/*
		print_r($eval_arr);
		echo "<br>";
		print_r($scored_places);
		echo "<br>";
		*/

		foreach ($eval_arr as $value) {

			$update_place = FALSE;
			$update_miniBOS = FALSE;

			// Query the evaluation table and return the evaluations associated with the entry
			$db_conn->returnType = 'array';
			$db_conn->where ('eid', $value);
			$row_evals = $db_conn->get($prefix."evaluation");
			$totalRows_evals = $db_conn->count;

			// First, check if the score has been recorded already.
			// If it has, check that any places OR mini-BOS has been recorded or changed
			if (in_array($value,$scored_arr)) {

				$evalMiniBOS = array();
				$evalPlace = array();

				// If there is a record, update the record in the scores DB
				// Loop through and compare each final score.
				foreach ($row_evals as $row_evals) {

					$evalPlace[] = $row_evals['evalPlace'];
					$evalMiniBOS[] = $row_evals['evalMiniBOS'];

				}

				if ((is_array($evalMiniBOS)) && (!empty($evalMiniBOS))) $evalMiniBOS = max($evalMiniBOS);
				if ((is_numeric($evalMiniBOS)) && ($evalMiniBOS > 0)) $evalMiniBOS = 1;
				else $evalMiniBOS = 0;

				if ((is_array($evalPlace)) && (!empty($evalPlace))) $evalPlace = max($evalPlace);
				if ((is_numeric($evalPlace)) && ($evalPlace > 0)) $evalPlace = $evalPlace;
				else $evalPlace = "";

				// An entry's beverage type (beer/mead/cider) is a fixed property of the
				// entry, not something that can vary per judge - look it up directly from
				// the entry's own brewing row (already resolved once, reliably, at
				// entry-submission time by process_brewing.inc.php) instead of re-deriving
				// it per judge via evaluation.evalStyle -> styles.brewStyleType, which can
				// point at a stale styles row if the style set changed after a judge's
				// scoresheet was originally submitted, and was being trusted with no check
				// that the looked-up value was even non-empty (GitHub issue #1770).
				$db_conn->where ('id', $value);
				$row_brewing_type = $db_conn->getOne ($brewing_db_table, "brewStyleType");
				$evalStyleType = (!empty($row_brewing_type['brewStyleType'])) ? $row_brewing_type['brewStyleType'] : "";

				// If the entry has been scored and a place has been recorded
				// Grab the place and build discrepency array
				if (!empty($scored_places)) {
					foreach ($scored_places as $k => $v) {
						if ($v['scored_id'] == $value) {

							// Check if miniBOS has changed
							if ($v['scored_minibos'] != $evalMiniBOS) $update_miniBOS = TRUE;

							// Check if the place has changed
							if ($v['scored_place'] != $evalPlace) {								
								if (empty($evalPlace)) $evalPlace = "None";
								if (empty($v['scored_place'])) {
									$v['scored_place'] = "None";
									$update_place = TRUE;
								}
								else {
									if ($_SESSION['prefsDisplaySpecial'] == "J") {
										$info = explode("^", entry_info($value));
										$scored_places_discrepency[] = sprintf("%06s",$info[6])."|".$v['scored_place']."|".$evalPlace;
									}							
									else $scored_places_discrepency[] = sprintf("%06s",$value)."|".$v['scored_place']."|".$evalPlace;								
									$scored_places_discrepency_count += 1;
								}
							}
						}
					}
				}

				if (($update_place) || ($update_miniBOS)) {

					$update_table = $prefix."judging_scores";
					$data = array();

					if ($update_place) {
						$data['scorePlace'] = $evalPlace;
						// Don't overwrite a previously-good scoreType with a blank lookup
						// result - leave the existing value in place rather than clobber it.
						if (!empty($evalStyleType)) $data['scoreType'] = $evalStyleType;
					}
					if ($update_miniBOS) $data['scoreMiniBOS'] = $evalMiniBOS;

					$score_count++;

					$db_conn->where ('eid', $value);
					$result = $db_conn->update ($update_table, $data);
					if (!$result) $status = 2;

				}

			}
		
			// If no record, import
			else {

				$eval_scores = array();
				$eid = array();
				$uid = array();
				$evalTable = array();
				$evalMiniBOS = array();
				$evalPlace = array();

				// echo $query_evals."<br>";

				// If only a single evaluation, add eid to the singles array
				if ($totalRows_evals == 1) {
					$singles[] = $row_evals[0]['eid'];
					$not_imported_count ++;
				}

				if ($totalRows_evals > 1) {

					// Loop through and compare each final score.
					foreach ($row_evals as $row_evals)  {

						$eval_scores[] = $row_evals['evalFinalScore'];
						$eid[] = $row_evals['eid'];
						$uid[] = $row_evals['uid'];
						$evalTable[] = $row_evals['evalTable'];
						$evalMiniBOS[] = $row_evals['evalMiniBOS'];
						$evalPlace[] = $row_evals['evalPlace'];

					}

					// print_r($evalTable);
					// echo "<br>";

					// Get the highest score of all that were recorded
					$eid = max($eid);
					$uid = max($uid);
					$evalTable = max($evalTable);
					$evalMiniBOS = max($evalMiniBOS);
					$final_score = max($eval_scores);

					if ((is_array($evalPlace)) && (!empty($evalPlace))) $evalPlace = max($evalPlace);
					if ((is_numeric($evalPlace)) && ($evalPlace > 0)) $evalPlace = $evalPlace;
					else $evalPlace = "";

					// See matching comment on the "already scored" branch above - an
					// entry's beverage type doesn't vary per judge, so look it up directly
					// from its own brewing row rather than per-judge via evaluation.evalStyle
					// -> styles.brewStyleType (GitHub issue #1770). NULL (rather than a
					// guessed value) when the entry's own brewStyleType is itself blank, so
					// a genuinely bad source value stays visibly NULL instead of silently
					// becoming "0".
					$db_conn->where ('id', $value);
					$row_brewing_type = $db_conn->getOne ($brewing_db_table, "brewStyleType");
					$evalStyleType = (!empty($row_brewing_type['brewStyleType'])) ? $row_brewing_type['brewStyleType'] : null;

					$db_conn->where ('eid', $value);
					$db_conn->where ('evalFinalScore', $final_score);
					$row_eval_max = $db_conn->getOne ($prefix."evaluation", "sum(id), count(*) as count");
				
					// If all consensus scores match, add the sql insert statement
					if ($row_eval_max['count'] == $totalRows_evals) {

						$score_count++;

						$update_table = $prefix."judging_scores";
						$data = array(
							'eid' => $eid,
							'bid' => $uid, 
							'scoreTable' => $evalTable, 
							'scoreEntry' => $final_score, 
							'scoreMiniBOS' => $evalMiniBOS, 
							'scorePlace' => $evalPlace, 
							'scoreType' => $evalStyleType
						);
						$result = $db_conn->insert ($update_table, $data);
						if (!$result) $status = 2;
					
					} 

					// If scores differ, add the eid to the flagged array
					else { 
						$flagged[] = array(
							"eid" => $value,
							"uid" => $uid
						); 
						$flagged_count++;
					}		
					
				} 

			}
			
		}

		// Finally, clear out all zeroes in scorePlace columns. Just in case.
		$update_table = $prefix."judging_scores";
		$data = array('scorePlace' => NULL);
		$db_conn->where ('scorePlace', 0);
		$result = $db_conn->update ($update_table, $data);

		if (empty($scored_places_discrepency)) $scored_places_discrepency = "";
		else $scored_places_discrepency = implode(",",$scored_places_discrepency);

		$return_json = array(
			"status" => "$status",
			"scores_imported_count" => "$score_count",
			"scores_not_imported_count" => "$not_imported_count",
			"flagged_count" => "$flagged_count",
			"scored_places_discrepency" => "$scored_places_discrepency",
			"scored_places_discrepency_count" => "$scored_places_discrepency_count"
		);
	
	}

	echo json_encode($return_json);
	// https://www.dyn-web.com/tutorials/php-js/json/multidim-arrays.php

} else {
	header("Location: https://pbs.twimg.com/media/CGx6dsDVIAAV0am.png");
	exit();
}
?>