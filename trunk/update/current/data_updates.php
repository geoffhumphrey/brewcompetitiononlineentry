<?php

// -----------------------------------------------------------
// Data Updates
// -----------------------------------------------------------

// Updating and increasing password security by implementing phppass
// http://www.openwall.com/phpass/
// http://www.openwall.com/articles/PHP-Users-Passwords

require(CLASSES.'phpass/PasswordHash.php');
$hasher = new PasswordHash(8, false);

$query_admin_users = "SELECT * FROM $users_db_table";
$admin_users = mysql_query($query_admin_users, $brewing);
$row_admin_users = mysql_fetch_assoc($admin_users);
$totalRows_admin_users = mysql_num_rows($admin_users);

if ($totalRows_admin_users > 0) {
	do { 
	
		// Update all current passwords to be much more secure
		// This involves taking the existing string that already has been hashed by md5 and hashing it with phpass
		// Theres no way to "decode" a string that has been hashed by md5, so we'll "hash a hash" here 
		
		// Get password from DB
		$password = $row_admin_users['password'];
		
		// Create hash with phpass
		$hash = $hasher->HashPassword($password);
		
		$updateSQL = sprintf("UPDATE $users_db_table SET password='%s' WHERE id='%s';", $hash, $row_admin_users['id']);
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing); 
		
		// Update all current admins to Uber Admins
		if ((!NHC) && ($row_admin_users['userLevel'] == 1)) {
			$updateSQL = sprintf("UPDATE $users_db_table SET 
								 userLevel='%s'
								 WHERE id='%s';", 
								 "0",
								 $row_admin_users['id']);
			mysql_select_db($database, $brewing);
			mysql_real_escape_string($updateSQL);
			$result = mysql_query($updateSQL, $brewing); 
			//echo $updateSQL."<br>";
		}
	}  
	while ($row_admin_users = mysql_fetch_assoc($admin_users));
	
	$output .=  "<li>Updates to user's table completed.</li>";
}




if (!NHC) {
	
	// Move assignments in brewer table to newly created staff table
	$query_staff_assign = sprintf("SELECT uid FROM %s WHERE brewerAssignment ='J' OR brewerAssignment ='S' OR brewerAssignment ='X' OR brewerAssignment ='O'", $prefix."brewer");
	$staff_assign = mysql_query($query_staff_assign, $brewing);
	$row_staff_assign = mysql_fetch_assoc($staff_assign);
	$totalRows_staff_assign = mysql_num_rows($staff_assign);
	
	if ($totalRows_staff_assign > 0) {
		
		do { $uid_array[] = $row_staff_assign['uid']; } while ($row_staff_assign = mysql_fetch_assoc($staff_assign));
		
		sort($uid_array);
		//print_r(array_unique($uid_array));
		
			foreach (array_unique($uid_array) as $uid) {
				
				$query_assignment = sprintf("SELECT brewerAssignment FROM %s WHERE uid='%s'", $prefix."brewer",$uid);
				$assignment = mysql_query($query_assignment, $brewing);
				$row_assignment = mysql_fetch_assoc($assignment);
			
				if ($row_assignment['brewerAssignment'] == "J") $updateSQL = sprintf("INSERT INTO `%s` (`uid`, `staff_judge`, `staff_judge_bos`, `staff_steward`, `staff_organizer`, `staff_staff`) VALUES ('%s', '1', '0', '0', '0', '0')", $prefix."staff",$uid);
				elseif ($row_assignment['brewerAssignment'] == "S") $updateSQL = sprintf("INSERT INTO `%s` (`uid`, `staff_judge`, `staff_judge_bos`, `staff_steward`, `staff_organizer`, `staff_staff`) VALUES ('%s', '0', '0', '1', '0', '0')", $prefix."staff",$uid);
				elseif ($row_assignment['brewerAssignment'] == "X") $updateSQL = sprintf("INSERT INTO `%s` (`uid`, `staff_judge`, `staff_judge_bos`, `staff_steward`, `staff_organizer`, `staff_staff`) VALUES ('%s', '0', '0', '0', '0', '1')", $prefix."staff",$uid);
				else $updateSQL = sprintf("INSERT INTO `%s` (`uid`, `staff_judge`, `staff_judge_bos`, `staff_steward`, `staff_organizer`, `staff_staff`) VALUES ('%s', '0', '0', '0', '1', '0')", $prefix."staff",$uid);
				mysql_real_escape_string($updateSQL);
				$result = mysql_query($updateSQL, $brewing);
				//echo $updateSQL."<br>";
			
			}
					
		
	}
	
	$query_judge_bos = sprintf("SELECT id FROM %s WHERE brewerJudgeBOS='Y'", $prefix."brewer");
	$judge_bos = mysql_query($query_judge_bos, $brewing);
	$row_judge_bos = mysql_fetch_assoc($judge_bos);
	$totalRows_judge_bos = mysql_num_rows($judge_bos);
	
	if ($totalRows_judge_bos > 0) {
		
		do { $bos_id[] = $row_judge_bos['id']; } while ($row_judge_bos = mysql_fetch_assoc($judge_bos));
		
		foreach ($bos_id as $uid) {
			
			$query_staff_bos = sprintf("SELECT id FROM %s WHERE uid='%s'", $prefix."staff",$uid);
			$staff_bos = mysql_query($query_staff_bos, $brewing);
			$row_staff_bos = mysql_fetch_assoc($staff_bos);
			$totalRows_staff_bos = mysql_num_rows($staff_bos);
			
			if ($totalRows_staff_bos == 1) {
				$updateSQL = sprintf("UPDATE %s SET staff_judge_bos='1' WHERE uid='%s'", $prefix."staff",$uid);
				mysql_real_escape_string($updateSQL);
				$result = mysql_query($updateSQL, $brewing); 
				//echo $updateSQL."<br>";
			}
			
			else {
				$updateSQL = sprintf("INSERT INTO `%s` (`uid`, `staff_judge`, `staff_judge_bos`, `staff_steward`, `staff_organizer`, `staff_staff`) VALUES ('%s', '0', '1', '0', '0', '0')", $prefix."staff",$uid);
				mysql_real_escape_string($updateSQL);
				$result = mysql_query($updateSQL, $brewing);
				//echo $updateSQL."<br>";
			}
		
		}
		
	}
	
	
	if (($totalRows_staff_assign > 0) || ($totalRows_judge_bos > 0)) $output .= "<li>Staff data transfer completed.</li>";
	
	// Underscores in the archiveSuffix were tripping up the archive functions
	$query_archive_name = sprintf("SELECT id,archiveSuffix FROM %s",$prefix."archive");
	$archive_name = mysql_query($query_archive_name, $brewing);
	$row_archive_name = mysql_fetch_assoc($archive_name);
	
	do { $archive_prefix[] = $row_archive_name['id']."|".$row_archive_name['archiveSuffix']; } while ($row_archive_name = mysql_fetch_assoc($archive_name));
		 
		foreach ($archive_prefix as $archive_name) {
			
			$archive_info = explode("|",$archive_name);
			$archive_name = str_replace("_","",$archive_info[1]);
			
			if ($archive_info[1] != $archive_name) {
				// First, replicate the current archive db tables with the new name
				
				if (HOSTED) $tables_array = array($prefix."users_".$archive_info[1], $prefix."brewer_".$archive_info[1], $prefix."brewing_".$archive_info[1], $prefix."judging_assignments_".$archive_info[1], $prefix."judging_scores_".$archive_info[1],$prefix."judging_tables_".$archive_info[1], $prefix."judging_scores_bos_".$archive_info[1],$prefix."style_types_".$archive_info[1]);
				else $tables_array = array($prefix."users_".$archive_info[1], $prefix."brewer_".$archive_info[1], $prefix."brewing_".$archive_info[1], $prefix."judging_assignments_".$archive_info[1], $prefix."judging_scores_".$archive_info[1],$prefix."judging_tables_".$archive_info[1], $prefix."judging_scores_bos_".$archive_info[1],$prefix."style_types_".$archive_info[1],$prefix."judging_flights_".$archive_info[1], $prefix."special_best_data_".$archive_info[1], $prefix."special_best_info_".$archive_info[1]);
				
				foreach ($tables_array as $table) {
					
					$new_name = str_replace($archive_info[1],"",$table).$archive_name;
					
					if ($table != $new_name) {
						$updateSQL = "RENAME TABLE `".$table."` TO `".$new_name."`;";
						mysql_real_escape_string($updateSQL);
						$result = mysql_query($updateSQL, $brewing);
					}
					
					//echo $updateSQL."<br>";
				}
			
				// Second, update the record in the "archive" table
				
				$updateSQL = sprintf("UPDATE ".$prefix."archive SET 
									 archiveSuffix='%s'
									 WHERE id='%s';", 
									 $archive_name,
									 $archive_info[0]);
				mysql_select_db($database, $brewing);
				mysql_real_escape_string($updateSQL);
				$result = mysql_query($updateSQL, $brewing); 
				//echo $updateSQL."<br>";

			}
		
		}
	
	$output .= "<li>Updates to archive table completed.</li>";

} // end if (!NHC)

$updateSQL = sprintf("UPDATE %s SET version='%s', version_date='%s' WHERE id='%s';",$prefix."system","1.3.0.2","2013-09-13","1");
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing); 
//echo $updateSQL."<br>";
$output .= "<li>Updates to system table completed.</li>";

?>