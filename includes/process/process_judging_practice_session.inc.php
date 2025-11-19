<?php

/*
ob_start();
require('../paths.php');
require(CONFIG.'bootstrap.php');
ini_set('display_errors', 1); // Change to 0 for prod; change to 1 for testing.
ini_set('display_startup_errors', 1); // Change to 0 for prod; change to 1 for testing.
error_reporting(E_ALL); // Change to error_reporting(0) for prod; change to E_ALL for testing.
*/

/**
 * Instantiate a practice session. If using electronic 
 * scoresheets, add a practice session "location" to 
 * the judging_locations DB table. 
 * 
 * Add the new location to all judges' availabilites.
 * 
 * Create a new custom style for each of the selected style
 * types (beer, cider, and/or mead).
 * 
 * Insert into the entries DB table an entry for each style
 * type.
 * 
 * Create a table with all the newly added entries.
 * 
 * Add all assigned judges to that table.
 * 
 */

$errors = array();
$error_count = 0;
$location_added = FALSE;
$judges_to_update = FALSE;
$styles_added_count = 0;
$styles_added = FALSE;
$added_styles = array();
$judges_list = array();
$judges_not_assigned = array();
$practice_table_added = FALSE;

$testing = TRUE;

// Get selected style types from the form
if (isset($_POST['selected_style_types'])) $selected_style_types = $_POST['selected_style_types'];
else $selected_style_types = array("1","2","3"); // default to beer, cider, and mead

// Establish the new location's judging date start from the form.
if (isset($_POST['judging_session_start'])) {
	if ($_POST['judging_session_start'] == "0") $judging_date_start = time(); // immediately
	else $judging_date_start = sterilize($_POST['judging_session_start']); // specified in the form
}

else $judging_date_start = time(); // default to immediately

if (time() > $last_judging_date) $last_judging_date = time() + 604800; // 7 days from today

$update_table = $prefix."judging_locations";
$data = array(
	'judgingLocType' => 1, // Practice location
	'judgingDate' => $judging_date_start,
	'judgingDateEnd' => $last_judging_date,
	'judgingLocName' => $label_practice_session,
	'judgingLocation' => ucfirst(strtolower($label_practice_session)),
	'judgingRounds' => 1
);

if ($testing) {
	print_r($data);
	echo "<br>";
	$result = TRUE;
}

else $result = $db_conn->insert ($update_table, $data);

if ($result) {

	$location_added = TRUE;
	
	// Get the new location's id
	$query_new_location = sprintf("SELECT id FROM %s ORDER BY id DESC LIMIT 1",$prefix."judging_locations");
	$new_location = mysqli_query($connection,$query_new_location) or die (mysqli_error($connection));
	$row_new_location = mysqli_fetch_assoc($new_location);

	$new_location = ",Y-".$row_new_location['id'];

	// Loop through all judges in the brewer table and update their availabilities
	$query_judges = sprintf("SELECT id,uid,brewerJudgeLocation,brewerFirstName,brewerLastName FROM %s WHERE brewerJudge='Y';",$prefix."brewer");
	$judges = mysqli_query($connection,$query_judges) or die (mysqli_error($connection));
	$row_judges = mysqli_fetch_assoc($judges);
	$totalRows_judges = mysqli_num_rows($judges);

	if ($totalRows_judges > 0) {

		$judges_to_update = TRUE;

		$update_table = $prefix."brewer";
		$new_availability = $row_judges['brewerJudgeLocation'].$new_location;

		do {

			$judges_list[] = array(
				'uid' => $row_judges['uid'],
				'brewerFirstName' => $row_judges['brewerFirstName'],
				'brewerLastName' => $row_judges['brewerLastName']
			);

			$data = array(
				'brewerJudgeLocation' => $new_availability
			);
			$db_conn->where ('id', $row_judges['id']);
			
			if ($testing) {
				print_r($data);
				echo "<br>";
				$result = TRUE;
			}

			else $result = $db_conn->update ($update_table, $data);

		} while($row_judges = mysqli_fetch_assoc($judges));

	}

	// Create a new custom style for each of the selected style
	// types (beer, cider, and/or mead).

	// Get last brewStyleGroup for any custom styles (will be 50+)
	$query_last_custom = sprintf("SELECT brewStyleGroup FROM %s WHERE brewStyleGroup >= '50' AND brewStyleOwn='custom' ORDER BY brewStyleGroup DESC LIMIT 1;",$prefix."styles");
	$last_custom = mysqli_query($connection,$query_last_custom) or die (mysqli_error($connection));
	$row_last_custom = mysqli_fetch_assoc($last_custom);
	$totalRows_last_custom = mysqli_num_rows($last_custom);

	if ($testing) {
		$brewStyleGroup = 50;
	}

	else {

		if (($totalRows_last_custom > 0) && (is_numeric($row_last_custom['brewStyleGroup']))) $brewStyleGroup = $row_last_custom['brewStyleGroup'];
		else $brewStyleGroup = 50;

	}
	
	foreach ($selected_style_types as $value) {

		$brewStyleGroup = $brewStyleGroup + 1;

		if ($value == 1) {
			$brewStyle = $label_practice_beer;
			$brewStyleType = 1;
			$brewStyleStrength = NULL;
			$brewStyleCarb = NULL;
			$brewStyleSweet = NULL;
		}

		if ($value == 2) {
			$brewStyle = $label_practice_cider;
			$brewStyleType = 2;
			$brewStyleStrength = 1;
			$brewStyleCarb = 0;
			$brewStyleSweet = 1;
		}

		if ($value == 3) {
			$brewStyle = $label_practice_mead;
			$brewStyleType = 3;
			$brewStyleStrength = 1;
			$brewStyleCarb = 1;
			$brewStyleSweet = 1;
		}

		$update_table = $prefix."styles";

		$data = array(
			'brewStyle' => sterilize($brewStyle),
			'brewStyleOG' => NULL,
			'brewStyleOGMax' => NULL,
			'brewStyleFG' => NULL,
			'brewStyleFGMax' => NULL,
			'brewStyleABV' => NULL,
			'brewStyleABVMax' => NULL,
			'brewStyleIBU' => NULL,
			'brewStyleIBUMax' => NULL,
			'brewStyleSRM' => NULL,
			'brewStyleSRMMax' => NULL,
			'brewStyleType' => NULL,
			'brewStyleInfo' => NULL,
			'brewStyleLink' => NULL,
			'brewStyleGroup' => $brewStyleGroup,
			'brewStyleNum' => "A",
			'brewStyleActive' => "N",
			'brewStyleOwn' => "custom",
			'brewStyleVersion' => $_SESSION['prefsStyleSet'],
			'brewStyleReqSpec' => NULL,
			'brewStyleStrength' => $brewStyleStrength,
			'brewStyleCarb' => $brewStyleCarb,
			'brewStyleSweet' => $brewStyleSweet,
			'brewStyleEntry' => NULL
		);
		
		if ($testing) {
			print_r($data);
			echo "<br>";
			$result = TRUE;
		}

		else $result = $db_conn->insert ($update_table, $data);

		if ($result) {

			$styles_added_count++;
			
			// Get the id of the newly created style.
			$query_last_added = sprintf("SELECT id FROM %s WHERE brewStyleOwn='custom' ORDER BY id DESC LIMIT 1;",$prefix."styles");
			$last_added = mysqli_query($connection,$query_last_added) or die (mysqli_error($connection));
			$row_last_added = mysqli_fetch_assoc($last_added);
			
			$added_styles[] = $row_last_added['id'];

		}

		// Insert into the entries DB table an entry for each style
 		// type.

	} // end foreach

	if ($styles_added_count > 0) $styles_added = TRUE;

 	// Create a table with all the newly added styles.
	if ((is_array($added_styles)) && (!empty($added_styles))) $added_table_styles = implode(",",$added_styles);

	$update_table = $prefix."judging_tables";
	$data = array(
		'tableName' => $label_scoresheet_practice,
		'tableStyles' => $added_table_styles,
		'tableNumber' => 999,
		'tableLocation' => $row_new_location['id'],
		'tableEntryLimit' => NULL
	);

	if ($testing) {
		print_r($data);
		echo "<br>";
		$result = TRUE;
	}

	else $result = $db_conn->insert ($update_table, $data);

	if ($result) {

		$practice_table_added = TRUE;
		
		$query_last_table_added = sprintf("SELECT id FROM %s ORDER BY id DESC LIMIT 1;",$prefix."judging_tables");
		$last_table_added = mysqli_query($connection,$query_last_table_added) or die (mysqli_error($connection));
		$row_last_table_added = mysqli_fetch_assoc($last_table_added);

		// Add all assigned judges to that table.

		if ((is_array($judges_list)) && (!empty($judges_list))) {

			foreach ($judges_list as $key => $value) {
				
				$update_table = $prefix."judging_assignments";
				$data = array(
					'bid' => $value['uid'],
					'assignment' => "J",
					'assignTable' => $row_last_table_added['id'],
					'assignFlight' => 1,
					'assignRound' => 1,
					'assignLocation' => $row_new_location['id'],
					'assignPlanning' => NULL,
					'assignRoles' => NULL
				);

				if ($testing) {
					print_r($data);
					echo "<br>";
					$result = TRUE;
				}

				else $result = $db_conn->insert ($update_table, $data);

				if (!$result) $judges_not_assigned[] = $value['brewerFirstName']." ".$value['brewerLastName'];

			}

		}

	}

} // end if ($result)

else {
	$error = 1;
}

if ($location_added) $errors['location'] = "Practice session not added.";
if ($styles_added)  $errors['styles'] = "Practice styles not added.";
if ($practice_table_added)  $errors['table'] = "Practice table not added.";
if (!empty($judges_not_assigned)) $errors['judges'] = $judges_not_assigned;

print_r($errors);

/*
	if (!$location_added) $errors['location'] = "Practice session not added.";
	if (!$styles_added)  $errors['styles'] = "Practice styles not added.";
	if (!$practice_table_added)  $errors['table'] = "Practice table not added.";
	if (!empty($judges_not_assigned)) $errors['judges'] = $judges_not_assigned;
*/
?>