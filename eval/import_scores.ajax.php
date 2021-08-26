<?php
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('../paths.php');
require(CONFIG.'bootstrap.php');
include (LIB.'admin.lib.php');

if ((isset($_SESSION['session_set_'.$prefix_session])) && (isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {

	$return_json = array();
	$eval_arr = array();
	$evalPlace = array();

	// First, query DB to see if any evaluations have been recorded
	$query_eval = sprintf("SELECT * FROM %s",$prefix."evaluation");
	$eval = mysqli_query($connection,$query_eval) or die (mysqli_error($connection));
	$row_eval = mysqli_fetch_assoc($eval);
	$totalRows_eval = mysqli_num_rows($eval);

	// If no records
	if ($totalRows_eval == 0) {
		$return_json = array(
			"status" => "0",
			"scores_imported" => "0"
		);
	}

	// If there are records, update the scores DB table with scores recorded by judges
	else {

		$query_scored = sprintf("SELECT * FROM %s",$prefix."judging_scores");
		$scored = mysqli_query($connection,$query_scored) or die (mysqli_error($connection));
		$row_scored = mysqli_fetch_assoc($scored);
		$totalRows_scored = mysqli_num_rows($scored);

		$flagged = array();
		$eval_arr = array();
		$scores_arr = array();
		$scored_places = array();
		$singles = array();
		$evalPlace = array();
		$score_count = 0;
		$not_imported_count = 0;
		$flagged_count = 0;
		$status = 1;

		do {
			$scored_arr[] = $row_scored['eid'];
			$scored_places[] = array(
				"scored_id" => $row_scored['eid'],
				"scored_place" => $row_scored['scorePlace']
			);
		} while($row_scored = mysqli_fetch_assoc($scored));

		do {
			$eval_arr[] = $row_eval['eid'];
		} while($row_eval = mysqli_fetch_assoc($eval));

		$eval_arr = array_unique($eval_arr);

		// print_r($eval_arr);
		// echo "<br>";
		// print_r($scored_places);
		// echo "<br>";

		foreach ($eval_arr as $value) {

			$update_place = FALSE;

			// Query the evaluation table and return the evaluations associated with the entry
			$query_evals = sprintf("SELECT * FROM %s WHERE eid='%s'",$prefix."evaluation",$value);
			$evals = mysqli_query($connection,$query_evals) or die (mysqli_error($connection));
			$row_evals = mysqli_fetch_assoc($evals);
			$totalRows_evals = mysqli_num_rows($evals);

			$query_style_type = sprintf("SELECT brewStyleType FROM %s WHERE id='%s'",$prefix."styles",$row_evals['evalStyle']);
			$style_type = mysqli_query($connection,$query_style_type) or die (mysqli_error($connection));
			$row_style_type = mysqli_fetch_assoc($style_type);

			// First, check if the score has been recorded already.
			// If it has, check that any places have been recoreded or changed
			if (in_array($value,$scored_arr)) {

				$evalPlace = array();
				$evalStyleType = array();

				// If there has been a place recorded, update the record in the scores DB
				// Loop through and compare each final score. 
				do {

					$query_style_type = sprintf("SELECT brewStyleType FROM %s WHERE id='%s'",$prefix."styles",$row_evals['evalStyle']);
					$style_type = mysqli_query($connection,$query_style_type) or die (mysqli_error($connection));
					$row_style_type = mysqli_fetch_assoc($style_type);

					$evalStyleType[] = $row_style_type['brewStyleType'];
					$evalPlace[] = $row_evals['evalPlace'];
				
				} while($row_evals = mysqli_fetch_assoc($evals));

				if ((is_array($evalPlace)) && (!empty($evalPlace))) $evalPlace = max($evalPlace);
				if ((is_numeric($evalPlace)) && ($evalPlace > 0)) $evalPlace = $evalPlace;
				else $evalPlace = "";

				if ((is_array($evalStyleType)) && (!empty($evalStyleType))) $evalStyleType = max($evalStyleType);
				else $evalStyleType = "";

				// If the entry has been scored and a place has been recorded
				// Grab the place.
				if (!empty($scored_places)) {
					foreach ($scored_places as $k => $v) {
						if ($v['scored_id'] == $value) {
							if ($v['scored_place'] != $evalPlace) $update_place = TRUE;
						}
					}
				}

				if ($update_place) {
					
					$updateSQL = sprintf("UPDATE `%s` SET scorePlace=%s, scoreType=%s WHERE eid=%s",
						$prefix."judging_scores",
						GetSQLValueString($evalPlace, "text"),
						GetSQLValueString($evalStyleType, "text"),
						GetSQLValueString($value, "text")
					);

					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
					if (!$result) $status = 2;
					$score_count++;
				
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
				$evalStyleType = array();

				// echo $query_evals."<br>";

				// If only a single evaluation, add eid to the singles array
				if ($totalRows_evals == 1) {
					$singles[] = $row_evals['eid'];
					$not_imported_count ++;
				}
				
				if ($totalRows_evals > 1) {

					// Loop through and compare each final score. 
					do {

						$query_style_type = sprintf("SELECT brewStyleType FROM %s WHERE id='%s'",$prefix."styles",$row_evals['evalStyle']);
						$style_type = mysqli_query($connection,$query_style_type) or die (mysqli_error($connection));
						$row_style_type = mysqli_fetch_assoc($style_type);

						$eval_scores[] = $row_evals['evalFinalScore'];
						$eid[] = $row_evals['eid'];
						$uid[] = $row_evals['uid'];
						$evalTable[] = $row_evals['evalTable'];
						$evalMiniBOS[] = $row_evals['evalMiniBOS'];
						$evalPlace[] = $row_evals['evalPlace'];
						$evalStyleType[] = $row_style_type['brewStyleType'];
						

					} while ($row_evals = mysqli_fetch_assoc($evals));

					// print_r($evalTable);
					// echo "<br>";

					// Get the highest score of all that were recorded
					$eid = max($eid);
					$uid = max($uid);
					$evalTable = max($evalTable);
					$evalMiniBOS = max($evalMiniBOS);
					$final_score = max($eval_scores);
					$evalStyleType = max($evalStyleType);

					if ((is_array($evalPlace)) && (!empty($evalPlace))) $evalPlace = max($evalPlace);
					if ((is_numeric($evalPlace)) && ($evalPlace > 0)) $evalPlace = $evalPlace;
					else $evalPlace = "";

					$query_eval_max = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE eid='%s' AND evalFinalScore='%s'",$prefix."evaluation",$value,$final_score);
					$eval_max = mysqli_query($connection,$query_eval_max) or die (mysqli_error($connection));
					$row_eval_max = mysqli_fetch_assoc($eval_max);
				
					// If all consensus scores match, add the sql insert statement
					if ($row_eval_max['count'] == $totalRows_evals) {
						
						$insertSQL = sprintf("INSERT INTO %s (eid, bid, scoreTable, scoreEntry, scoreMiniBOS, scorePlace, scoreType) VALUES (%s, %s, %s, %s, %s, %s, %s);",
							$prefix."judging_scores",
							GetSQLValueString($eid, "text"),
							GetSQLValueString($uid, "text"),
							GetSQLValueString($evalTable, "text"),
							GetSQLValueString($final_score, "text"),
							GetSQLValueString($evalMiniBOS, "int"),
							GetSQLValueString($evalPlace, "text"),
							GetSQLValueString($evalStyleType, "text")
						);

						mysqli_real_escape_string($connection,$insertSQL);
						$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));
						if (!$result) $status = 2;

						$score_count++;
					
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
		
		$updateSQL = sprintf("UPDATE `%s` SET scorePlace=NULL WHERE scorePlace='0'", $prefix."judging_scores");
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		$return_json = array(
			"status" => "$status",
			"scores_imported_count" => "$score_count",
			"scores_not_imported_count" => "$not_imported_count",
			"flagged_count" => "$flagged_count"
			//"flagged" => $flagged,
			//"singles" => $singles
		);
	}

	echo json_encode($return_json);
	// https://www.dyn-web.com/tutorials/php-js/json/multidim-arrays.php

} else echo "<p>Not allowed.</p>";
?>