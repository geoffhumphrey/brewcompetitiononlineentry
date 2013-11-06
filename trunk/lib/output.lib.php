<?php
// --------------------------------------------------------
// The following apply to /output/dropoff.php
// --------------------------------------------------------

function dropoff_loc($id) {
	include(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	
	$query_dropoffs_user = sprintf("SELECT uid FROM %s WHERE brewerDropOff='%s'",$prefix."brewer",$id);
	$dropoffs_user = mysql_query($query_dropoffs_user, $brewing) or die(mysql_error());
	$row_dropoffs_user = mysql_fetch_assoc($dropoffs_user);
	$totalRows_dropoffs_user = mysql_num_rows($dropoffs_user);
	
	$return = 
	$totalRows_dropoffs_user."^".
	$row_dropoffs_user['uid'];
	
	return $return;
}

function location_count($location_id) {
	
	include(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	
	$query_dropoff = sprintf("SELECT uid FROM %s WHERE brewerDropOff='%s'",$prefix."brewer",$location_id);
	$dropoff = mysql_query($query_dropoff, $brewing) or die(mysql_error());
	$row_dropoff = mysql_fetch_assoc($dropoff);
	$totalRows_dropoff = mysql_num_rows($dropoff);
	
	do { $uid[] = $row_dropoff['uid']; } while ($row_dropoff = mysql_fetch_assoc($dropoff)); 
	
	foreach ($uid as $brewBrewerID) {
	
		$query_dropoffs = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s'",$prefix."brewing",$brewBrewerID);
		$dropoffs = mysql_query($query_dropoffs, $brewing) or die(mysql_error());
		$row_dropoffs = mysql_fetch_assoc($dropoffs);
		
		$location_count[] = $row_dropoffs['count'];
		
	}
		
	return array_sum($location_count);
}

function dropoff_location_info($location_id) {
	
	include(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	
	$query_location_info = sprintf("SELECT id,dropLocation,dropLocationName FROM %s WHERE id='%s'",$prefix."drop_off",$location_id);
	$location_info = mysql_query($query_location_info, $brewing) or die(mysql_error());
	$row_location_info = mysql_fetch_assoc($location_info);
	
	$return = 
	$row_location_info['id']."^".
	$row_location_info['dropLocation']."^".
	$row_location_info['dropLocationName'];
	
	return $return;
	
}

function entries_by_dropoff_loc($id) {
	
	include(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
		
	$query_dropoffs = sprintf("SELECT uid FROM %s WHERE brewerDropOff='%s'",$prefix."brewer",$id);
	$dropoffs = mysql_query($query_dropoffs, $brewing) or die(mysql_error());
	$row_dropoffs = mysql_fetch_assoc($dropoffs);
	$totalRows_dropoffs = mysql_num_rows($dropoffs);
	
	$build_rows = "";
	
	if ($totalRows_dropoffs > 0) {
	
		do {
			
			$query_dropoff_count = sprintf("SELECT * FROM %s WHERE brewBrewerID='%s'",$prefix."brewing",$row_dropoffs['uid']);
			$dropoff_count = mysql_query($query_dropoff_count, $brewing) or die(mysql_error());
			$row_dropoff_count = mysql_fetch_assoc($dropoff_count);
			$totalRows_dropoff_count = mysql_num_rows($dropoff_count);
			
			if ($totalRows_dropoff_count > 0) {
				
				do {
					$build_rows .= "
						<tr>
							<td class=\"data bdr1B_gray\">".sprintf("%04s",$row_dropoff_count['id'])."</td>
							<td class=\"data bdr1B_gray\">".$row_dropoff_count['brewName']."</td>
							<td class=\"data bdr1B_gray\">".$row_dropoff_count['brewBrewerLastName'].", ".$row_dropoff_count['brewBrewerFirstName']."</td>
							<td class=\"data bdr1B_gray\"><p class=\"box_small\"></p></td>  
						</tr>
				";
				
				} while ($row_dropoff_count = mysql_fetch_assoc($dropoff_count)); 
				
			} // end if ($totalRows_dropoff_count > 0)
			
		} while ($row_dropoffs = mysql_fetch_assoc($dropoffs));
		
	} // end if ($totalRows_dropoffs > 0)
	
	return $build_rows;
}

// --------------------------------------------------------
// The following apply to:	/output/email_export.php
//							/output/eentries_export.php
// --------------------------------------------------------

function parseCSVComments($comments) {
	
	// First, escape all " and make them ""
	$comments = str_replace('"', '""', $comments);
	$comments = preg_replace("/[\n\r]/","",$comments); 
	  
	// Check if any commas or new lines
	if(eregi(",", $comments) or eregi("\n", $comments) or eregi("\t", $comments) or eregi("\r", $comments) or eregi("\v", $comments)) { 
		 
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

// --------------------------------------------------------
// The following applies to /output/entry.php
// --------------------------------------------------------

function pay_to_print($prefs_pay,$entry_paid) { 
	if (($prefs_pay == "Y") && ($entry_paid == "1")) return TRUE;
	elseif (($prefs_pay == "Y") && ($entry_paid == "0")) return FALSE;
	elseif ($prefs_pay == "N") return TRUE;
}



// --------------------------------------------------------
// The following applies to /output/labels.php
// --------------------------------------------------------

function truncate($string, $your_desired_width) {
  $parts = preg_split('/([\s\n\r]+)/', $string, null, PREG_SPLIT_DELIM_CAPTURE);
  $parts_count = count($parts);

  $length = 0;
  $last_part = 0;
  for (; $last_part < $parts_count; ++$last_part) {
    $length += strlen($parts[$last_part]);
    if ($length > $your_desired_width) { break; }
  }

  return implode(array_slice($parts, 0, $last_part));
}

function user_entry_count() {
	
	include(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	
	$query_with_entries_count = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewBrewerID='%s'",$prefix."brewing",$uid);
	$with_entries_count = mysql_query($query_with_entries_count, $brewing) or die(mysql_error());
	$row_with_entries_count = mysql_fetch_assoc($with_entries_count);
	
	return $row_with_entries_count['count'];
	
}

?>