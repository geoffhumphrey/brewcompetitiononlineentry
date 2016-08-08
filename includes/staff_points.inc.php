<?php 

/*
Checked Single
2016-06-06
*/

// Get total amount of paid and received entries
$total_entries = total_paid_received("judging_scores","default");
//$total_entries = 88;
function round_down_to_hundred($number) {
    if (strlen($number)<3) { $number = $number;	} 
	else { $number = substr($number, 0, strlen($number)-2) . "00";	}
    return $number;
}

// Get possible organizer points

function total_points($total_entries,$method) {
	switch ($method) {
		case "Organizer":
			if (($total_entries >= 1) && ($total_entries <= 49)) $points = 2;
			elseif (($total_entries >= 50) && ($total_entries <= 99)) $points = 2.5;
			elseif (($total_entries >= 100) && ($total_entries <= 149)) $points = 3;
			elseif (($total_entries >= 150) && ($total_entries <= 199)) $points = 3.5;
			elseif (($total_entries >= 200) && ($total_entries <= 299)) $points = 4;
			elseif (($total_entries >= 300) && ($total_entries <= 399)) $points = 4.5;
			elseif (($total_entries >= 400) && ($total_entries <= 499)) $points = 5;
			elseif ($total_entries >= 500) $points = 6;
			else $points = 0;
		break;
		
		case "Staff":
			if (($total_entries >= 1) && ($total_entries <= 49)) $points = 1;
			if (($total_entries >= 50) && ($total_entries <= 99)) $points = 2;
			if (($total_entries >= 100) && ($total_entries <= 149)) $points = 3;
			if (($total_entries >= 150) && ($total_entries <= 199)) $points = 4;
			if (($total_entries >= 200) && ($total_entries <= 299)) $points = 5;
			if (($total_entries >= 300) && ($total_entries <= 399)) $points = 6;
			if (($total_entries >= 400) && ($total_entries <= 499)) $points = 7;
			if (($total_entries >= 500) && ($total_entries <= 599)) $points = 8;
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
			elseif (($total_entries >= 50) && ($total_entries <= 99)) $points = 2;
			elseif (($total_entries >= 100) && ($total_entries <= 149)) $points = 2.5;
			elseif (($total_entries >= 150) && ($total_entries <= 199)) $points = 3;
			elseif (($total_entries >= 200) && ($total_entries <= 299)) $points = 3.5;
			elseif (($total_entries >= 300) && ($total_entries <= 399)) $points = 4;
			elseif (($total_entries >= 400) && ($total_entries <= 499)) $points = 4.5;
			elseif ($total_entries >= 500) $points = 5.5;
			else $points = 0;
		break;
	}
	return number_format($points,1);
}

// calculate a Judge's points
function judge_points($bid,$bos) { 
	session_name($prefix_session);
	session_start(); 
	require('../paths.php'); 
	require(DB.'judging_locations.db.php');
	
	// *minimum* of 1.0 points per competition	
	// *maximum* of 1.5 points per day  (includes BOS round, I'm assuming)
	
	do { $a[] = $row_judging['id']; } while ($row_judging = mysqli_fetch_assoc($judging));
	foreach (array_unique($a) as $location) {
		$query_assignments = sprintf("SELECT COUNT(*) as 'count' FROM $judging_assignments_db_table WHERE bid='%s' AND assignLocation='%s' AND assignment='J'", $bid, $location);
		if (SINGLE) $query_assignments .= sprintf(" AND comp_id='%s'",$_SESSION['comp_id']);
		$assignments =  mysqli_query($connection,$query_assignments) or die (mysqli_error($connection));
		$row_assignments = mysqli_fetch_assoc($assignments);
		if ($row_assignments['count'] > 1) $b[] = 1.0; 
		else $b[] = $row_assignments['count'];
	}
	
	$points = array_sum($b);
	if (($bos == "1") && ($points > 0)) $points = $points + 0.5; 
	elseif (($bos == "1") && ($points == 0)) $points = 1.0;
	else $points = $points;
	return number_format($points,1);
	
}
	
// calculate a Steward's points
function steward_points($bid) {
	
	require('../paths.php'); 
	require(DB.'judging_locations.db.php');
	
	// *minimum* of 0.5 points per day	
	// *maximum* of 1.0 points per competition
	
	do { $a[] = $row_judging['id']; } while ($row_judging = mysqli_fetch_assoc($judging));
	foreach (array_unique($a) as $location) {
		$query_assignments = sprintf("SELECT COUNT(*) as 'count' FROM $judging_assignments_db_table WHERE bid='%s' AND assignLocation='%s' AND assignment='S'", $bid, $location);
		if (SINGLE) $query_assignments .= sprintf(" AND comp_id='%s'",$_SESSION['comp_id']);
		$assignments = mysqli_query($connection,$query_assignments) or die (mysqli_error($connection));
		$row_assignments = mysqli_fetch_assoc($assignments);
		if ($row_assignments['count'] > 1) $b[] = 0.5; 
		else $b[] = $row_assignments['count'] * 0.5;
	}
	
	$points = array_sum($b);
	if ($points >= 1.0) $points = 1.0; else $points = $points;
	return number_format($points,1);
}

function round_to_half($num) {
	$ceil = ceil($num);
	$half = $ceil - 0.5;
 	if($num < $half) return floor($num);
	elseif ($num == $ceil) return $ceil;
 	else return $half;
}

// Get maximum point values based upon number of entries
$organ_points = number_format(total_points($total_entries,"Organizer"), 1);
$staff_points = number_format(total_points($total_entries,"Staff"), 1);
$judge_points = number_format(total_points($total_entries,"Judge"), 1);

// Divide total staff point pool by amount of staff, round down
$query_assignments = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE staff_staff='1'",$staff_db_table);
if (SINGLE) $query_assignments .= sprintf(" AND comp_id='%s'",$_SESSION['comp_id']);
$assignments =  mysqli_query($connection,$query_assignments) or die (mysqli_error($connection));
$row_assignments = mysqli_fetch_assoc($assignments);

$staff_ind_points = $staff_points/$row_assignments['count'];

if ($row_assignments['count'] >= 2) $staff_points =  number_format(round_to_half($staff_ind_points), 1);
elseif ($row_assignments['count'] == 1) $staff_points = number_format($staff_points,1);
else $staff_points = 0;

// Organizer
$query_organizer = sprintf("SELECT uid FROM %s WHERE staff_organizer='1'",$staff_db_table);
if (SINGLE) $query_organizer .= sprintf(" AND comp_id='%s'",$_SESSION['comp_id']);
$organizer =  mysqli_query($connection,$query_organizer) or die (mysqli_error($connection));
$row_organizer = mysqli_fetch_assoc($organizer);
$totalRows_organizer = mysqli_num_rows($organizer);

// Judges
$query_judges = sprintf("SELECT uid FROM %s WHERE staff_judge='1'",$staff_db_table);
if (SINGLE) $query_judges .= sprintf(" AND comp_id='%s'",$_SESSION['comp_id']);
$judges =  mysqli_query($connection,$query_judges) or die (mysqli_error($connection));
$row_judges = mysqli_fetch_assoc($judges);
$totalRows_judges = mysqli_num_rows($judges);

$query_bos_judges = sprintf("SELECT uid FROM %s WHERE staff_judge_bos='1'",$staff_db_table);
if (SINGLE) $query_bos_judges .= sprintf(" AND comp_id='%s'",$_SESSION['comp_id']);
$bos_judges = mysqli_query($connection,$query_bos_judges) or die (mysqli_error($connection));
$row_bos_judges = mysqli_fetch_assoc($bos_judges);
$totalRows_bos_judges = mysqli_num_rows($bos_judges);

// Stewards
$query_stewards = sprintf("SELECT uid FROM %s WHERE staff_steward='1'",$staff_db_table);
if (SINGLE) $query_stewards .= sprintf(" AND comp_id='%s'",$_SESSION['comp_id']);
$stewards = mysqli_query($connection,$query_stewards) or die (mysqli_error($connection));
$row_stewards = mysqli_fetch_assoc($stewards);
$totalRows_stewards = mysqli_num_rows($stewards);

// Staff
$query_staff = sprintf("SELECT uid FROM %s WHERE staff_staff='1'",$staff_db_table);
if (SINGLE) $query_staff .= sprintf(" AND comp_id='%s'",$_SESSION['comp_id']);
$staff = mysqli_query($connection,$query_staff) or die (mysqli_error($connection));
$row_staff = mysqli_fetch_assoc($staff);
$totalRows_staff = mysqli_num_rows($staff);

?>