<?php 
/*
 * Module:      process_brewer_add.inc.php
 * Description: This module does all the heavy lifting for adding participant information to the 
 *              "brewer" table.
 */
require(DB.'common.db.php');
require(DB.'brewer.db.php');
require(DB.'judging_locations.db.php');

if (($action == "update") && ($row_prefs['prefsCompOrg'] == "N")) {

	foreach($_POST['id'] as $id){ 
		mysql_select_db($database, $brewing);		
		if (($bid == "default") && ($_POST["brewerAssignment".$id] != "") && ($filter != "bos")) {
		$updateSQL = "UPDATE brewer SET brewerAssignment='";
		$updateSQL .= $_POST["brewerAssignment".$id];
		$updateSQL .= "' WHERE id='".$id.";'";
		}
		elseif (($bid == "default") && ($_POST["brewerAssignment".$id] != "") && ($filter == "bos")) {
		$updateSQL = "UPDATE brewer SET brewerJudgeBOS='";
		$updateSQL .= $_POST["brewerAssignment".$id];
		$updateSQL .= "' WHERE id='".$id.";'";
		}
		else $updateSQL = "SELECT id FROM brewer WHERE id='$id'";
		
		if ($bid != "default") { 
			$query_update = "SELECT brewerStewardAssignedLocation, brewerJudgeAssignedLocation FROM brewer WHERE id='$id'";
			$update = mysql_query($query_update, $brewing) or die(mysql_error());
			$row_update = mysql_fetch_assoc($update);
		
			if ($row_update['brewerStewardAssignedLocation'] != "") { 
			$trimmed = rtrim($row_update['brewerStewardAssignedLocation'], ",");
					if  (!strstr($trimmed, $bid)) $sal = $trimmed.", "; 
					elseif (strstr($trimmed, $bid)) { 
						$sal = str_replace($bid,"",$trimmed).", "; 
						}
					else $sal = ""; 
					}
			if ($row_update['brewerJudgeAssignedLocation'] != "") { 
			$trimmed = rtrim($row_update['brewerJudgeAssignedLocation'], ",");
					if  (!strstr($trimmed, $bid)) $jal = $trimmed.", "; 
					elseif (strstr($trimmed, $bid)) { 
						$jal = str_replace($bid,"",$trimmed).", "; 
						}
					else $jal = ""; 
					}
																																				  
			if ($filter == "stewards") {  
			$sal = str_replace(" ,","",$sal); // clean up
			if (substr($sal, 0, 2) == ", ") $sal = substr_replace($sal, "", 0, 2); else $sal = $sal;
			$updateSQL = "UPDATE brewer SET brewerStewardAssignedLocation='";
			$updateSQL .= $sal.$_POST["brewerStewardAssignedLocation".$id];
			$updateSQL .= "' WHERE id='".$id."';"; 
			}
			elseif ($filter == "judges") {
			$jal = str_replace(" ,","",$jal); // clean up
			if (substr($jal, 0, 2) == ", ") $jal = substr_replace($jal, "", 0, 2); else $jal = $jal;
			$updateSQL = "UPDATE brewer SET brewerJudgeAssignedLocation='";
			$updateSQL .= $jal.$_POST["brewerJudgeAssignedLocation".$id];
			$updateSQL .= "' WHERE id='".$id."';";
			}
			else $updateSQL = "SELECT id FROM brewer WHERE id='$id'";
		}
		
		if ($_POST["brewerAssignment".$id] == "J") $updateSQL2 = "UPDATE brewer SET brewerNickname='judge' WHERE id='".$id."'"; 
  		elseif ($_POST["brewerAssignment".$id] == "S") $updateSQL2 = "UPDATE brewer SET brewerNickname='steward' WHERE id='".$id."'"; 
  		else $updateSQL2 = "SELECT id FROM brewer WHERE id='$id'";
		
		if ($filter == "stewards") $field = "brewerStewardAssignedLocation";
		elseif ($filter == "judges") $field = "brewerJudgeAssignedLocation";
		else $field = "brewerJudgeAssignedLocation";
		
		$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		$result2 = mysql_query($updateSQL2, $brewing) or die(mysql_error());
		
		if (($filter == "judges") || ($filter == "stewards")) {
			$query_clean = "SELECT $field FROM brewer WHERE id = '$id'";
			$clean = mysql_query($query_clean, $brewing) or die(mysql_error());
			$row_clean = mysql_fetch_assoc($clean);
		
			if ($filter == "stewards") { 
				if (substr($row_clean['brewerStewardAssignedLocation'], 0, 2) == ", ")  $cleaned = substr_replace($row_clean['brewerStewardAssignedLocation'], "", 0, 2); else $cleaned = $row_clean['brewerStewardAssignedLocation']; 
				} 
			if ($filter == "judges") { 
				if  (substr($row_clean['brewerJudgeAssignedLocation'], 0, 2) == ", ") $cleaned = substr_replace($row_clean['brewerJudgeAssignedLocation'], "", 0, 2); else $cleaned = $row_clean['brewerJudgeAssignedLocation']; 
			}
			$cleaned = rtrim($cleaned, " ");
			$cleaned = rtrim($cleaned, ",");
			$updateSQL3 = "UPDATE brewer SET ";
			$updateSQL3 .= $field."=";
			$updateSQL3 .= "'".$cleaned."' ";
			$updateSQL3 .= " WHERE id='".$id."';";
		$result3 = mysql_query($updateSQL3, $brewing) or die(mysql_error());	
		}
		// Debug
		//echo "<p>".$updateSQL."<br>";
		//echo $updateSQL2."<br>";
		//echo $updateSQL3."</p>";
		if ($filter == "staff") {
		$updateSQL = sprintf("UPDATE brewer SET brewerAssignment='O' WHERE uid='%s'", $_POST['Organizer']);
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
		}
	} 
if($result1){ header(sprintf("Location: %s", $massUpdateGoTo));  }
}

if (($action == "update") && ($row_prefs['prefsCompOrg'] == "Y")) {

	foreach($_POST['id'] as $id){ 
		$query_assignment = "SELECT id,brewerAssignment FROM brewer WHERE id='".$id."'";
		$assignment = mysql_query($query_assignment, $brewing) or die(mysql_error());
		$row_assignment = mysql_fetch_assoc($assignment);
		
		if (($_POST["brewerAssignment".$id] != "") && ($filter != "bos")) {
			$updateSQL = "UPDATE brewer SET brewerAssignment='";
			$updateSQL .= $_POST["brewerAssignment".$id];
			$updateSQL .= "' WHERE id='".$id."';";
		}
		elseif (($_POST["brewerAssignment".$id] != "") && ($filter == "bos")) {
			$updateSQL = "UPDATE brewer SET brewerJudgeBOS='";
			$updateSQL .= $_POST["brewerAssignment".$id];
			$updateSQL .= "' WHERE id='".$id."';";
		}
		
		elseif (($_POST["brewerAssignment".$id] == "") && ($row_assignment['brewerAssignment'] == "")) {
			$updateSQL = "UPDATE brewer SET brewerAssignment='";
			$updateSQL .= $_POST["brewerAssignment".$id];
			$updateSQL .= "' WHERE id='".$id."';";
		}
		
		elseif (($_POST["brewerAssignment".$id] == "") && ($filter == "judges") && ($row_assignment['brewerAssignment'] == "J")) {
			$updateSQL = "UPDATE brewer SET brewerAssignment='";
			$updateSQL .= $_POST["brewerAssignment".$id];
			$updateSQL .= "' WHERE id='".$id."';";
		}
		
		elseif (($_POST["brewerAssignment".$id] == "") && ($filter == "stewards") && ($row_assignment['brewerAssignment'] == "S")) {
			$updateSQL = "UPDATE brewer SET brewerAssignment='";
			$updateSQL .= $_POST["brewerAssignment".$id];
			$updateSQL .= "' WHERE id='".$id."';";
		}
		
		elseif (($_POST["brewerAssignment".$id] == "") && ($filter == "staff") && ($row_assignment['brewerAssignment'] == "X")) {
			$updateSQL = "UPDATE brewer SET brewerAssignment='";
			$updateSQL .= $_POST["brewerAssignment".$id];
			$updateSQL .= "' WHERE id='".$id."';";
		}
		
		elseif (($_POST["brewerAssignment".$id] == "") && ($row_assignment['brewerAssignment'] == "O")) {
			$updateSQL = "UPDATE brewer SET brewerAssignment='O' WHERE id='".$id."';";
		}
		
		else $updateSQL = "SELECT id from brewer WHERE id='".$id."';";
		$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
	} 
	
	if (($_POST['Organizer'] != "") && ($filter == "staff")) {
		$query_org = "SELECT uid,brewerAssignment FROM brewer WHERE brewerAssignment='O'";
		$org = mysql_query($query_org, $brewing) or die(mysql_error());
		$row_org = mysql_fetch_assoc($org);
		
		if ($_POST['Organizer'] != $row_org['uid']) {
			
			$updateSQL = sprintf("UPDATE brewer SET brewerAssignment='' WHERE uid='%s'", $row_org['uid']);
			$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
			
			$updateSQL = sprintf("UPDATE brewer SET brewerAssignment='O' WHERE uid='%s'", $_POST['Organizer']);
			$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
		}
		
		if ($_POST['Organizer'] == $row_assignment['uid']) {
			$updateSQL = sprintf("UPDATE brewer SET brewerAssignment='O' WHERE uid='%s'", $_POST['Organizer']);
			$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
		}
	}
	
	if($result1){ header(sprintf("Location: %s", $massUpdateGoTo));  }
}

// --------------------------------------- Adding a Participant ----------------------------------------
if ($action == "add") {
	if ($totalRows_judging < 2) {  
		$location_pref1 = $_POST['brewerJudgeLocation'];
		$location_pref2 = $_POST['brewerStewardLocation'];
	} 
	else { 
	if ($go == "judge") {
			$location_pref1 = implode(",",$_POST['brewerJudgeLocation']);
			$location_pref2 = implode(",",$_POST['brewerStewardLocation']);
		}
	}
	mysql_select_db($database, $brewing);
	$query_user = sprintf("SELECT id FROM users WHERE id = '%s'", $_POST['uid']);
	$user = mysql_query($query_user, $brewing) or die(mysql_error());
	$row_user = mysql_fetch_assoc($user);
	$totalRows_user = mysql_num_rows($user);
	
	if ($totalRows_user == 0) { 
		//header("Location: ../index.php?section=brewer&go=".$go."&msg=2");
		$updateSQL = sprintf("UPDATE users SET user_name='%s' WHERE id='%s'", 
					   GetSQLValueString($_POST['brewerEmail'], "text"),
					   GetSQLValueString($_POST['uid'], "text"));
					   
		mysql_select_db($database, $brewing);
		$Result = mysql_query($updateSQL, $brewing) or die(mysql_error());
	} 		
	
	else {

	  $insertSQL = sprintf("INSERT INTO brewer (
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
	  brewerAHA
	) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
				   GetSQLValueString($_POST['uid'], "int"),
				   GetSQLValueString(capitalize($_POST['brewerFirstName']), "text"),
				   GetSQLValueString(capitalize($_POST['brewerLastName']), "text"),
				   GetSQLValueString(capitalize($_POST['brewerAddress']), "text"),
				   GetSQLValueString(capitalize($_POST['brewerCity']), "text"),
				   GetSQLValueString(capitalize($_POST['brewerState']), "text"),
				   GetSQLValueString($_POST['brewerZip'], "text"),
				   GetSQLValueString($_POST['brewerCountry'], "text"),
				   GetSQLValueString($_POST['brewerPhone1'], "text"),
				   GetSQLValueString($_POST['brewerPhone2'], "text"),
				   GetSQLValueString(capitalize($_POST['brewerClubs']), "text"),
				   GetSQLValueString($_POST['brewerEmail'], "text"),
				   GetSQLValueString($_POST['brewerSteward'], "text"),
				   GetSQLValueString($_POST['brewerJudge'], "text"),
				   GetSQLValueString($_POST['brewerJudgeID'], "text"),
				   GetSQLValueString($_POST['brewerJudgeMead'], "text"),
				   GetSQLValueString($_POST['brewerJudgeRank'], "text"),
				   GetSQLValueString($location_pref1, "text"),
				   GetSQLValueString($location_pref2, "text"),
				   GetSQLValueString($_POST['brewerAHA'], "int")
				   );

		mysql_select_db($database, $brewing);
		$Result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());
		//echo $insertSQL;
		if ($section == "setup") $insertGoTo = "../setup.php?section=step3";
		elseif ($_POST['brewerJudge'] == "Y") $insertGoTo = "../index.php?section=judge&go=judge";
		elseif ($section == "admin") $insertGoTo = "../index.php?section=admin&go=participants&msg=1&username=".$username;
		else $insertGoTo = $insertGoTo; 
		
	header(sprintf("Location: %s", $insertGoTo));
	}
} // end if ($action == "add")

// --------------------------------------- Editing a Participant ----------------------------------------
if ($action == "edit") {
	if ($totalRows_judging > 1) {  
	if (($_POST['brewerJudgeLocation'] != "") && (!strstr($_POST['brewerJudgeLocation'],","))) $location_pref1 = implode(",",$_POST['brewerJudgeLocation']); 
	elseif (($_POST['brewerJudgeLocation'] != "") && (strstr($_POST['brewerJudgeLocation'],","))) $location_pref1 = $_POST['brewerJudgeLocation']; 
	else $location_pref1 = "";
	if (($_POST['brewerStewardLocation'] != "") && (!strstr($_POST['brewerStewardLocation'],","))) $location_pref2 = implode(",",$_POST['brewerStewardLocation']); 
	elseif (($_POST['brewerJudgeLocation'] != "") && (strstr($_POST['brewerStewardLocation'],","))) $location_pref2 = $_POST['brewerStewardLocation'];
	else $location_pref2 = "";
} 
	else { 
		$location_pref1 = $_POST['brewerJudgeLocation'];
		$location_pref2 = $_POST['brewerStewardLocation'];
	}
	if ($_POST['brewerJudgeLikes'] != "") $likes = implode(",",$_POST['brewerJudgeLikes']); else $likes = "";
	if ($_POST['brewerJudgeDislikes'] != "") $dislikes = implode(",",$_POST['brewerJudgeDislikes']); else $dislikes = "";
	
	$updateSQL = sprintf("UPDATE brewer SET 
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
		
		brewerAHA=%s
		WHERE id=%s",
						   GetSQLValueString($_POST['uid'], "int"),
						   GetSQLValueString(capitalize($_POST['brewerFirstName']), "text"),
						   GetSQLValueString(capitalize($_POST['brewerLastName']), "text"),
						   GetSQLValueString(capitalize($_POST['brewerAddress']), "text"),
						   GetSQLValueString(capitalize($_POST['brewerCity']), "text"),
						   GetSQLValueString(capitalize($_POST['brewerState']), "text"),
						   GetSQLValueString($_POST['brewerZip'], "text"),
						   GetSQLValueString($_POST['brewerCountry'], "text"),
						   GetSQLValueString($_POST['brewerPhone1'], "text"),
						   GetSQLValueString($_POST['brewerPhone2'], "text"),
						   GetSQLValueString(capitalize($_POST['brewerClubs']), "text"),
						   GetSQLValueString($_POST['brewerEmail'], "text"),
						   GetSQLValueString($_POST['brewerSteward'], "text"),
						   GetSQLValueString($_POST['brewerJudge'], "text"),
						   GetSQLValueString($_POST['brewerJudgeID'], "text"),
						   GetSQLValueString($_POST['brewerJudgeMead'], "text"),
						   GetSQLValueString($_POST['brewerJudgeRank'], "text"),
						   GetSQLValueString($likes, "text"),
						   GetSQLValueString($dislikes, "text"),
						   GetSQLValueString($location_pref1, "text"),
						   GetSQLValueString($location_pref2, "text"),
						   //GetSQLValueString($_POST['brewerAssignment'], "text"),
						   //GetSQLValueString($_POST['brewerAssignmentStaff'], "text"),
						   GetSQLValueString($_POST['brewerAHA'], "text"),
						   GetSQLValueString($id, "int"));
	  
	if ($_POST['brewerAssignment'] == "J") $updateSQL2 = "UPDATE brewer SET brewerNickname='judge' WHERE id='".$id."'"; 
	elseif ($_POST['brewerAssignment'] == "S") $updateSQL2 = "UPDATE brewer SET brewerNickname='steward' WHERE id='".$id."'"; 
	else $updateSQL2 = "UPDATE brewer SET brewerNickname=NULL WHERE id='".$id."'"; 
	
	//echo $updateSQL."<br>";
	mysql_select_db($database, $brewing);
	$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
	$Result2 = mysql_query($updateSQL2, $brewing) or die(mysql_error());
	
	if ($go == "register") $updateGoTo = "../index.php?section=brew&msg=2";	
	elseif ($go == "judge") $updateGoTo = "../index.php?section=list&go=".$go."&filter=default&msg=7";
	elseif ($go == "default") $updateGoTo = "../index.php?section=list&go=".$go."&filter=default&msg=2";
	else $updateGoTo = $updateGoTo;
	
	header(sprintf("Location: %s", $updateGoTo));
}
?>