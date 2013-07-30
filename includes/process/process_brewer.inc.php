<?php 
/*
 * Module:      process_brewer_add.inc.php
 * Description: This module does all the heavy lifting for adding participant information to the 
 *              "brewer" table.
 */

if ((isset($_SESSION['loginUsername'])) && (isset($_SESSION['userLevel']))) { 
 
	require(DB.'common.db.php');
	require(DB.'brewer.db.php');
	require(DB.'judging_locations.db.php');
	
	// Empty the user_info session variable
	// Will trigger the session to reset the variables in common.db.php upon reload after redirect
	session_start();
	unset($_SESSION['user_info'.$prefix_session]);
	
	if ($action == "update") {
	
		foreach($_POST['uid'] as $uid) {
		
		$query_staff = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE uid='%s'",$prefix."staff",$uid);
		$staff = mysql_query($query_staff, $brewing) or die(mysql_error());
		$row_staff = mysql_fetch_assoc($staff);	
		
		//echo $row_staff['count'];
		
			if ($filter == "judges") {
				
				if ($_POST['staff_judge'.$uid] == "1") {
					if ($row_staff['count'] == 0) $updateSQL1 = sprintf("INSERT INTO %s (uid,staff_judge) VALUES (%s,1)",$prefix."staff",$uid);
					else $updateSQL1 = sprintf("UPDATE %s SET staff_judge=1 WHERE uid=%s",$prefix."staff",$uid);
					mysql_real_escape_string($updateSQL1);
					$result1 = mysql_query($updateSQL1, $brewing) or die(mysql_error());
					//echo $updateSQL1."<br>";
				}
				
				if ($_POST['staff_judge'.$uid] == "") {
				
					if ($row_staff['count'] == 0) $updateSQL2 == "";
					else { 
						$updateSQL2 = sprintf("UPDATE %s SET staff_judge=0 WHERE uid=%s",$prefix."staff",$uid);
						mysql_real_escape_string($updateSQL2);
						$result2 = mysql_query($updateSQL2, $brewing) or die(mysql_error());
					}
					//echo $updateSQL2."<br>";
					
					
					// Check to see if the participant is assigned to be a judge or steward in the judging_assignments table
					$query_brewer = sprintf("SELECT id FROM $brewer_db_table WHERE uid='%s'",$uid);
					$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
					$row_brewer = mysql_fetch_assoc($brewer);
					//echo $query_brewer."<br>";
					
					$query_assign = sprintf("SELECT id FROM $judging_assignments_db_table WHERE bid='%s' AND assignment='J'",$row_brewer['id']);
					$assign = mysql_query($query_assign, $brewing) or die(mysql_error());
					$row_assign = mysql_fetch_assoc($assign);
					$totalRows_assign = mysql_num_rows($assign);
					//echo $query_assign."<br>";
					
					// If so, delete all instances
					if ($totalRows_assign > 0) {
						do {
							$deleteSQL1 = sprintf("DELETE FROM $judging_assignments_db_table WHERE id='%s'", $row_assign['id']);
							mysql_select_db($database, $brewing);
							$result3 = mysql_query($deleteSQL1, $brewing) or die(mysql_error());
							//echo $deleteSQL1."<br>";
						} while ($row_assign = mysql_fetch_assoc($assign)); 
					}
					
				}
				
			} // end if ($filter == "judges")
			
			if ($filter == "stewards") {
				
				if ($_POST['staff_steward'.$uid] == "1") {
					if ($row_staff['count'] == 0) $updateSQL1 = sprintf("INSERT INTO %s (uid,staff_steward) VALUES (%s,1)",$prefix."staff",$uid);
					else $updateSQL1 = sprintf("UPDATE %s SET staff_steward=1 WHERE uid=%s",$prefix."staff",$uid);
					mysql_real_escape_string($updateSQL1);
					$result1 = mysql_query($updateSQL1, $brewing) or die(mysql_error());
					//echo $updateSQL1."<br>";
				}
				
				if ($_POST['staff_steward'.$uid] == "") {
					
					if ($row_staff['count'] == 0) $updateSQL2 == "";
					else {  
					$updateSQL2 = sprintf("UPDATE %s SET staff_steward=0 WHERE uid=%s",$prefix."staff",$uid);
					mysql_real_escape_string($updateSQL2);
					$result2 = mysql_query($updateSQL2, $brewing) or die(mysql_error());
					}
					//echo $updateSQL2."<br>";
					
					// Check to see if the participant is assigned to be a steward in the judging_assignments table
					$query_brewer = sprintf("SELECT id FROM $brewer_db_table WHERE uid='%s'",$uid);
					$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
					$row_brewer = mysql_fetch_assoc($brewer);
					
					$query_assign = sprintf("SELECT id FROM $judging_assignments_db_table WHERE bid='%s' AND assignment='S'",$row_brewer['id']);
					$assign = mysql_query($query_assign, $brewing) or die(mysql_error());
					$row_assign = mysql_fetch_assoc($assign);
					$totalRows_assign = mysql_num_rows($assign);
					
					// If so, delete all instances
					if ($totalRows_assign > 0) {
						do {
							$deleteSQL1 = sprintf("DELETE FROM $judging_assignments_db_table WHERE id='%s'", $row_assign['id']);
							mysql_select_db($database, $brewing);
							//echo $deleteSQL1."<br>";
							$result3 = mysql_query($deleteSQL1, $brewing) or die(mysql_error());
						} while ($row_assign = mysql_fetch_assoc($assign)); 
					}	
				}
			} // if ($filter == "stewards")
			
			if ($filter == "staff") {
				
				if ($_POST['staff_staff'.$uid] == "1") {
					if ($row_staff['count'] == 0) $updateSQL1 = sprintf("INSERT INTO %s (uid,staff_staff) VALUES (%s,1)",$prefix."staff",$uid);
					else $updateSQL1 = sprintf("UPDATE %s SET staff_staff=1 WHERE uid=%s",$prefix."staff",$uid);
					mysql_real_escape_string($updateSQL1);
					$result1 = mysql_query($updateSQL1, $brewing) or die(mysql_error());
					//echo $updateSQL1."<br>";
				}
				
				if ($_POST['staff_staff'.$uid] == "") {
					if ($row_staff['count'] == 0) $updateSQL2 == "";
					else { 
						$updateSQL2 = sprintf("UPDATE %s SET staff_staff=0 WHERE uid=%s",$prefix."staff",$uid);
						mysql_real_escape_string($updateSQL2);
						$result2 = mysql_query($updateSQL2, $brewing) or die(mysql_error());
					}
					//echo $updateSQL2."<br>";
				}
				
			} // end if ($filter == "staff")
			
			if ($filter == "bos") {
				
				if ($_POST['staff_judge_bos'.$uid] == "1") {
					if ($row_staff['count'] == 0) $updateSQL1 = sprintf("INSERT INTO %s (uid,staff_judge_bos) VALUES (%s,1)",$prefix."staff",$uid);
					else $updateSQL1 = sprintf("UPDATE %s SET staff_judge_bos=1 WHERE uid=%s",$prefix."staff",$uid);
					mysql_real_escape_string($updateSQL1);
					$result1 = mysql_query($updateSQL1, $brewing) or die(mysql_error());
					//echo $updateSQL."<br>";
				}
				
				if ($_POST['staff_judge_bos'.$uid] == "") {
					if ($row_staff['count'] == 0) $updateSQL2 == "";
					else { 
					$updateSQL2 = sprintf("UPDATE %s SET staff_judge_bos=0 WHERE uid=%s",$prefix."staff",$uid);
					mysql_real_escape_string($updateSQL2);
					$result1 = mysql_query($updateSQL2, $brewing) or die(mysql_error());
					}
					//echo $updateSQL."<br>";
				}
				
			} // end if ($filter == "bos")
			
		} // end foreach($_POST['uid'] as $uid)
		
		if ($filter == "staff") {
			
			if ($_POST['Organizer'] != "") {
					
				$query_org = sprintf("SELECT uid FROM %s WHERE staff_organizer='1'",$prefix."staff");
				$org = mysql_query($query_org, $brewing) or die(mysql_error());
				$row_org = mysql_fetch_assoc($org);
				//echo $_POST['Organizer']."<br>";
				//echo $row_org['uid']."<br>";
				
				if ($_POST['Organizer'] != $row_org['uid']) {
					
					$updateSQL3 = sprintf("UPDATE %s SET staff_organizer='0' WHERE uid='%s'", $prefix."staff",$row_org['uid']);
					mysql_real_escape_string($updateSQL3);
					$result3 = mysql_query($updateSQL3, $brewing) or die(mysql_error());
					//echo $updateSQL3."<br>";
					
					$query_staff_org = sprintf("SELECT uid FROM %s WHERE uid='%s'",$prefix."staff",$_POST['Organizer']);
					$staff_org = mysql_query($query_staff_org, $brewing) or die(mysql_error());
					$row_staff_org = mysql_fetch_assoc($staff_org);
					$totalRows_staff_org = mysql_num_rows($staff_org);
					
					if ($totalRows_staff_org > 0) $updateSQL4 = sprintf("UPDATE %s SET staff_organizer='1', staff_staff='0', staff_judge='0', staff_judge_bos='0' WHERE uid='%s'", $prefix."staff", $_POST['Organizer']);
					else $updateSQL4 = sprintf("INSERT INTO %s (uid,staff_organizer) VALUES (%s,1)",$prefix."staff",$_POST['Organizer']);
					mysql_real_escape_string($updateSQL4);
					$result4 = mysql_query($updateSQL4, $brewing) or die(mysql_error());
					//echo $updateSQL4."<br>";
				}
				
				if ($_POST['Organizer'] == $row_org['uid']) {
					$updateSQL5 = sprintf("UPDATE %s SET staff_organizer='1' WHERE uid='%s'", $prefix."staff", $_POST['Organizer']);
					mysql_real_escape_string($updateSQL5);
					$result5 = mysql_query($updateSQL5, $brewing) or die(mysql_error());
					//echo $updateSQL5."<br>";
				}
				
			}	
			
		}
		
		$pattern = array('\'', '"');
		$massUpdateGoTo = $base_url."index.php?section=admin&action=assign&go=judging&filter=".$filter."&msg=9";
		$massUpdateGoTo = str_replace($pattern, "", $massUpdateGoTo); 
		//echo $massUpdateGoTo;
		header(sprintf("Location: %s", stripslashes($massUpdateGoTo))); 
		
	} // end if ($action == "update")
	
	// --------------------------------------- Adding a Participant ----------------------------------------
	
	if ($action == "add") {
		if ($_POST['brewerJudge'] == "Y") {
			if (($_POST['brewerJudgeLocation'] != "") && (is_array($_POST['brewerJudgeLocation']))) $location_pref1 = implode(",",$_POST['brewerJudgeLocation']);
			elseif (($_POST['brewerJudgeLocation'] != "") && (!is_array($_POST['brewerJudgeLocation']))) $location_pref1 = $_POST['brewerJudgeLocation'];
			
		}
		else $location_pref1 = "";
		
		if ($_POST['brewerSteward'] == "Y") {
			if (($_POST['brewerStewardLocation'] != "") && (is_array($_POST['brewerStewardLocation']))) $location_pref2 = implode(",",$_POST['brewerStewardLocation']);
			elseif (($_POST['brewerJudgeLocation'] != "") && (!is_array($_POST['brewerStewardLocation']))) $location_pref2 = $_POST['brewerStewardLocation'];
		}
		else $location_pref2 = "";
		
		if ($_POST['brewerJudgeRank'] != "") $rank = implode(",",$_POST['brewerJudgeRank']); else $rank = "";
		
		mysql_select_db($database, $brewing);
		$query_user = sprintf("SELECT id FROM $users_db_table WHERE id = '%s'", $_POST['uid']);
		$user = mysql_query($query_user, $brewing) or die(mysql_error());
		$row_user = mysql_fetch_assoc($user);
		$totalRows_user = mysql_num_rows($user);
		
		if ($totalRows_user == 0) { 
			//header(sprintf("Location: %s", $base_url."index.php?section=brewer&go=".$go."&msg=2"));
			$updateSQL = sprintf("UPDATE $users_db_table SET user_name='%s' WHERE id='%s'", 
						   GetSQLValueString($_POST['brewerEmail'], "text"),
						   GetSQLValueString($_POST['uid'], "text"));
						   
			mysql_select_db($database, $brewing);
			mysql_real_escape_string($updateSQL);
			$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		} 		
		
		else {
	
		  
			// Numbers 999999994 through 999999999 are reserved for NHC applications.
			if (($_POST['brewerAHA'] < "999999994") || ($_POST['brewerAHA'] == "")) {
				
				$insertSQL = sprintf("INSERT INTO $brewer_db_table (
				  uid,
				  brewerFirstName, 
				  brewerLastName, 
				  brewerAddress, 
				  brewerCity, 
				  brewerState, 
				  
				  brewerZip,
				  brewerCountry,
				  brewerPhone1, 
				  brewerPhone2, 
				  brewerClubs, 
				  brewerEmail, 
				  
				  brewerSteward, 
				  brewerJudge,
				  brewerJudgeID,
				  brewerJudgeMead,
				  brewerJudgeRank,
				  brewerJudgeLocation,
				  brewerStewardLocation,
				  brewerAHA,
				  brewerDropOff
				) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
							   GetSQLValueString($_POST['uid'], "int"),
							   GetSQLValueString(capitalize($_POST['brewerFirstName']), "text"),
							   GetSQLValueString(capitalize($_POST['brewerLastName']), "text"),
							   GetSQLValueString(capitalize($_POST['brewerAddress']), "text"),
							   GetSQLValueString(capitalize($_POST['brewerCity']), "text"),
							   GetSQLValueString($_POST['brewerState'], "text"),
							   GetSQLValueString($_POST['brewerZip'], "text"),
							   GetSQLValueString($_POST['brewerCountry'], "text"),
							   GetSQLValueString($_POST['brewerPhone1'], "text"),
							   GetSQLValueString($_POST['brewerPhone2'], "text"),
							   GetSQLValueString($_POST['brewerClubs'], "text"),
							   GetSQLValueString($_POST['brewerEmail'], "text"),
							   GetSQLValueString($_POST['brewerSteward'], "text"),
							   GetSQLValueString($_POST['brewerJudge'], "text"),
							   GetSQLValueString($_POST['brewerJudgeID'], "text"),
							   GetSQLValueString($_POST['brewerJudgeMead'], "text"),
							   GetSQLValueString($_POST['brewerJudgeRank'], "text"),
							   GetSQLValueString($location_pref1, "text"),
							   GetSQLValueString($location_pref2, "text"),
							   GetSQLValueString($_POST['brewerAHA'], "int"),
							   GetSQLValueString($_POST['brewerDropOff'], "int")
				);
				
				// only if added by an admin.
				if((NHC) && ($section == "admin")) {
				$updateSQL =  sprintf("INSERT INTO nhcentrant (
				uid, 
				firstName, 
				lastName, 
				email,
				AHAnumber,
				regionPrefix
				) 
				VALUES 
				(%s, %s, %s, %s, %s, %s)",
								   GetSQLValueString($_POST['uid'], "int"),
								   GetSQLValueString(capitalize($_POST['brewerFirstName']), "text"),
								   GetSQLValueString(capitalize($_POST['brewerLastName']), "text"),
								   GetSQLValueString($_POST['brewerEmail'], "text"),
								   GetSQLValueString($_POST['brewerAHA'], "text"),
								   GetSQLValueString($prefix, "text"));
				mysql_real_escape_string($updateSQL);
				$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
				}
			}
			
			else {
				$insertSQL = sprintf("INSERT INTO $brewer_db_table (
				  uid,
				  brewerFirstName, 
				  brewerLastName, 
				  brewerAddress, 
				  brewerCity, 
				  brewerState, 
				  
				  brewerZip,
				  brewerCountry,
				  brewerPhone1, 
				  brewerPhone2, 
				  brewerClubs, 
				  brewerEmail, 
				  
				  brewerSteward, 
				  brewerJudge,
				  brewerJudgeID,
				  brewerJudgeMead,
				  brewerJudgeRank,
				  brewerJudgeLocation,
				  brewerStewardLocation,
				  brewerDropOff
				) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
							   GetSQLValueString($_POST['uid'], "int"),
							   GetSQLValueString(capitalize($_POST['brewerFirstName']), "text"),
							   GetSQLValueString(capitalize($_POST['brewerLastName']), "text"),
							   GetSQLValueString(capitalize($_POST['brewerAddress']), "text"),
							   GetSQLValueString(capitalize($_POST['brewerCity']), "text"),
							   GetSQLValueString($_POST['brewerState'], "text"),
							   GetSQLValueString($_POST['brewerZip'], "text"),
							   GetSQLValueString($_POST['brewerCountry'], "text"),
							   GetSQLValueString($_POST['brewerPhone1'], "text"),
							   GetSQLValueString($_POST['brewerPhone2'], "text"),
							   GetSQLValueString($_POST['brewerClubs'], "text"),
							   GetSQLValueString($_POST['brewerEmail'], "text"),
							   GetSQLValueString($_POST['brewerSteward'], "text"),
							   GetSQLValueString($_POST['brewerJudge'], "text"),
							   GetSQLValueString($_POST['brewerJudgeID'], "text"),
							   GetSQLValueString($_POST['brewerJudgeMead'], "text"),
							   GetSQLValueString($rank, "text"),
							   GetSQLValueString($location_pref1, "text"),
							   GetSQLValueString($location_pref2, "text"),
							   GetSQLValueString($_POST['brewerDropOff'], "int")
							   );
			}
	
			mysql_select_db($database, $brewing);
			mysql_real_escape_string($insertSQL);
			$result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());
			//echo $insertSQL;
			if ($section == "setup") $insertGoTo = "../setup.php?section=step3";
			elseif (($_POST['brewerJudge'] == "Y") || ($_POST['brewerSteward'] == "Y")) $insertGoTo = $base_url."index.php?section=judge&go=judge";
			elseif ($section == "admin") $insertGoTo = $base_url."index.php?section=admin&go=participants&msg=1&username=".$username;
			elseif (($go == "judge") && ($filter == "default")) $insertGoTo = $base_url."index.php?section=list&go=".$go."&filter=default&msg=7";
			elseif (($go == "judge") && ($filter != "default")) $insertGoTo = $base_url."index.php?section=admin&go=participants&msg=1";
			else $insertGoTo = $insertGoTo; 
		
		$pattern = array('\'', '"');
		$insertGoTo = str_replace($pattern, "", $insertGoTo); 
		header(sprintf("Location: %s", stripslashes($insertGoTo)));
		}
	} // end if ($action == "add")
	
	// --------------------------------------- Editing a Participant ----------------------------------------
	if ($action == "edit") {
		if ($_POST['brewerJudge'] == "Y") {
			if (($_POST['brewerJudgeLocation'] != "") && (is_array($_POST['brewerJudgeLocation']))) $location_pref1 = implode(",",$_POST['brewerJudgeLocation']);
			elseif (($_POST['brewerJudgeLocation'] != "") && (!is_array($_POST['brewerJudgeLocation']))) $location_pref1 = $_POST['brewerJudgeLocation'];
			
		}
		else $location_pref1 = "";
		
		if ($_POST['brewerSteward'] == "Y") {
			if (($_POST['brewerStewardLocation'] != "") && (is_array($_POST['brewerStewardLocation']))) $location_pref2 = implode(",",$_POST['brewerStewardLocation']);
			elseif (($_POST['brewerJudgeLocation'] != "") && (!is_array($_POST['brewerStewardLocation']))) $location_pref2 = $_POST['brewerStewardLocation'];
		}
		else $location_pref2 = "";
	
		if ($_POST['brewerJudgeLikes'] != "") $likes = implode(",",$_POST['brewerJudgeLikes']); else $likes = "";
		if ($_POST['brewerJudgeDislikes'] != "") $dislikes = implode(",",$_POST['brewerJudgeDislikes']); else $dislikes = "";
		
		if ($_POST['brewerJudgeRank'] != "") $rank = implode(",",$_POST['brewerJudgeRank']); else $rank = "";
		
		$updateSQL = sprintf("UPDATE $brewer_db_table SET 
			uid=%s,
			brewerFirstName=%s, 
			brewerLastName=%s, 
			brewerAddress=%s, 
			brewerCity=%s, 
			brewerState=%s, 
			
			brewerZip=%s, 
			brewerCountry=%s, 
			brewerPhone1=%s, 
			brewerPhone2=%s, 
			brewerClubs=%s, 
			brewerEmail=%s, 
			
			brewerSteward=%s, 
			brewerJudge=%s, 
			brewerJudgeID=%s, 
			brewerJudgeMead=%s, 
			brewerJudgeRank=%s, 
			brewerJudgeLikes=%s, 
			brewerJudgeDislikes=%s, 
			brewerJudgeLocation=%s, 
			brewerStewardLocation=%s,
			brewerDropOff=%s
			",
							   GetSQLValueString($_POST['uid'], "int"),
							   GetSQLValueString(capitalize($_POST['brewerFirstName']), "text"),
							   GetSQLValueString(capitalize($_POST['brewerLastName']), "text"),
							   GetSQLValueString(capitalize($_POST['brewerAddress']), "text"),
							   GetSQLValueString(capitalize($_POST['brewerCity']), "text"),
							   GetSQLValueString($_POST['brewerState'], "text"),
							   GetSQLValueString($_POST['brewerZip'], "text"),
							   GetSQLValueString($_POST['brewerCountry'], "text"),
							   GetSQLValueString($_POST['brewerPhone1'], "text"),
							   GetSQLValueString($_POST['brewerPhone2'], "text"),
							   GetSQLValueString($_POST['brewerClubs'], "text"),
							   GetSQLValueString($_POST['brewerEmail'], "text"),
							   GetSQLValueString($_POST['brewerSteward'], "text"),
							   GetSQLValueString($_POST['brewerJudge'], "text"),
							   GetSQLValueString($_POST['brewerJudgeID'], "text"),
							   GetSQLValueString($_POST['brewerJudgeMead'], "text"),
							   GetSQLValueString($rank, "text"),
							   GetSQLValueString($likes, "text"),
							   GetSQLValueString($dislikes, "text"),
							   GetSQLValueString($location_pref1, "text"),
							   GetSQLValueString($location_pref2, "text"),
							   GetSQLValueString($_POST['brewerDropOff'], "int")
							   //GetSQLValueString($_POST['brewerAssignment'], "text"),
							   //GetSQLValueString($_POST['brewerAssignmentStaff'], "text"),
							   );
		// Numbers 999999994 through 999999999 are reserved for NHC applications.
		if (($_POST['brewerAHA'] < "999999994") || ($_POST['brewerAHA'] == "")) {
		$updateSQL .= sprintf(", brewerAHA=%s",GetSQLValueString($_POST['brewerAHA'], "text"));
		}
		
		$updateSQL .= sprintf(" WHERE id=%s",GetSQLValueString($id, "int"));
		  
		if ($_POST['brewerAssignment'] == "J") $updateSQL2 = "UPDATE $brewer_db_table SET brewerNickname='judge' WHERE id='".$id."'"; 
		elseif ($_POST['brewerAssignment'] == "S") $updateSQL2 = "UPDATE $brewer_db_table SET brewerNickname='steward' WHERE id='".$id."'"; 
		else $updateSQL2 = "UPDATE $brewer_db_table SET brewerNickname=NULL WHERE id='".$id."'"; 
		
		////echo $updateSQL."<br>";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		mysql_real_escape_string($updateSQL2);
		$result2 = mysql_query($updateSQL2, $brewing) or die(mysql_error());
		
		if ($go == "register") $updateGoTo = $base_url."index.php?section=brew&msg=2";	
		elseif (($go == "judge") && ($filter == "default")) $updateGoTo = $base_url."index.php?section=list&go=".$go."&filter=default&msg=7";
		elseif (($go == "judge") && ($filter != "default")) $updateGoTo = $base_url."index.php?section=admin&go=participants&msg=2";
		elseif ($go == "default") $updateGoTo = $base_url."index.php?section=list&go=".$go."&filter=default&msg=2";
		else $updateGoTo = $updateGoTo;
		
		$pattern = array('\'', '"');
		$updateGoTo = str_replace($pattern, "", $updateGoTo); 
		//echo $updateGoTo;
		header(sprintf("Location: %s", stripslashes($updateGoTo)));
	}
	
} else echo "<p>Not available.</p>";

?>