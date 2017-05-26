<?php 
/*
 * Module:      process_styles.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "styles" table
 */
 
if ((isset($_SERVER['HTTP_REFERER'])) && (((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) || ($section == "setup"))) {
	
	if (isset($_POST['brewStyleLink'])) $brew_style_link = $_POST['brewStyleLink'];
	else $brew_style_link = "";
	
	$ba_styles_accepted = "";
	
	if ($action == "update") {
	
		foreach($_POST['id'] as $id) {
			
			if (isset($_POST['brewStyleActive'.$id])) $brewStyleActive = "Y"; 
			else $brewStyleActive = "N";
		
				if ($filter == "default") {
					
					if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false) {
						
						// If the record is from the BreweryDB (not a custom style), add to the list
						if ($_POST['ba_brewerydb'.$id] == 1) {
							
							if ($brewStyleActive == "Y") $ba_styles_accepted .= $id.",";
							
						}
						
						// If the record is a custom style, update the local DB
						if ($_POST['ba_brewerydb'.$id] == 0) {
							
							$updateSQL = "UPDATE $styles_db_table SET brewStyleActive='".$brewStyleActive."' WHERE id='".$id."'";
							mysqli_real_escape_string($connection,$updateSQL);
							$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
							
						}
						
					} // end if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false)
					
					else {
					
						$updateSQL = "UPDATE $styles_db_table SET brewStyleActive='".$brewStyleActive."' WHERE id='".$id."'";
						mysqli_real_escape_string($connection,$updateSQL);
						$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
						// echo $updateSQL."<br>";
					
					}
					
				 } // end if ($filter == "default")
				 
				if (($filter == "judging") && ($bid == $_POST["brewStyleJudgingLoc".$id])) { 
				
					$updateSQL = "UPDATE $styles_db_table SET brewStyleJudgingLoc='".$_POST["brewStyleJudgingLoc".$id]."' WHERE id='".$id."';";
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
				 
					// Also need to find all records in the "brewing" table (entries) that are null or have either old judging location associated with the style and update them with the new judging location.		 
					$query_style_name = "SELECT *FROM $styles_db_table WHERE id='".$id."'";
					$style_name = mysqli_query($connection,$query_style_name) or die (mysqli_error($connection));
					$row_style_name = mysqli_fetch_assoc($style_name);
				 
					$query_loc = sprintf("SELECT * FROM $brewing_db_table WHERE brewCategorySort='%s' AND brewSubCategory='%s'", $row_style_name['brewStyleGroup'], $row_style_name['brewStyleNum']);
					$loc = mysqli_query($connection,$query_loc) or die (mysqli_error($connection));
					$row_loc = mysqli_fetch_assoc($loc);
					$totalRows_loc = mysqli_num_rows($loc);
					
					if ($totalRows_loc > 0) {
						do { 
					
							if ($row_loc['brewJudgingLocation'] != $_POST["brewStyleJudgingLoc".$id]) {
							
							$updateSQL = sprintf("UPDATE $brewing_db_table SET brewJudgingLocation='%s' WHERE id='%s';", $_POST["brewStyleJudgingLoc".$id], $row_loc['id']); 
							mysqli_real_escape_string($connection,$updateSQL);
							$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection)); 
							
							}
						
						} while($row_loc = mysqli_fetch_assoc($loc));
					
					} // end if ($totalRows_loc > 0)
				
				} // end if (($filter == "judging") && ($bid == $_POST["brewStyleJudgingLoc".$id]))
			
			} // end foreach($_POST['id'] as $id)
		
		if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false) {
			
			$explodies = explode("|",$_SESSION['prefsStyleSet']);
			
			// Add new list to broken up parts
			$styles_accepted = $explodies[0]."|".$explodies[1]."|".rtrim($ba_styles_accepted,",");
						
			$updateSQL = sprintf("UPDATE %s SET prefsStyleSet='%s' WHERE id='1'",$preferences_db_table,$styles_accepted);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			
			session_name($prefix_session);
			session_start();
			unset($_SESSION['prefs'.$prefix_session]);
			
		} // end if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false)
		
		if($result) { 
		
			if ($section == "setup") {
				
				$sql = sprintf("UPDATE `%s` SET setup_last_step = '7' WHERE id='1';", $system_db_table);
				mysqli_select_db($connection,$database);
				mysqli_real_escape_string($connection,$sql);
				$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
				
				$massUpdateGoTo = $base_url."setup.php?section=step8";
				$pattern = array('\'', '"');
				$massUpdateGoTo = str_replace($pattern, "", $massUpdateGoTo);
				header(sprintf("Location: %s", stripslashes($massUpdateGoTo)));
				
			}
			
			else {
				$pattern = array('\'', '"');
				$massUpdateGoTo = str_replace($pattern, "", $massUpdateGoTo);
				header(sprintf("Location: %s", stripslashes($massUpdateGoTo)));
			}
			
		} // end if($result)
	
	} // end if ($action == "update");
	
	if ($action == "add") {
	
		if ($_SESSION['prefsStyleSet'] == "BJCP2008") $category_end = 28;		
		else $category_end = 34;	
	
		if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false) {
			$query_style_name = sprintf("SELECT id,brewStyleGroup FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND brewStyleGroup >= %s ORDER BY id DESC LIMIT 1", $styles_db_table, "BJCP2015", $category_end);
		}
		
		else {
			$query_style_name = sprintf("SELECT id,brewStyleGroup FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND brewStyleGroup >= %s ORDER BY id DESC LIMIT 1", $styles_db_table, $_SESSION['prefsStyleSet'], $category_end);
		}
		
		$style_name = mysqli_query($connection,$query_style_name) or die (mysqli_error($connection));
		$row_style_name = mysqli_fetch_assoc($style_name);
		
		// Get the difference between the category end and the last number
		// $style_difference = ($row_style_name['brewStyleGroup'] - $category_end);
		$style_add_one = $row_style_name['brewStyleGroup'] + 1;
		
		if (isset($_POST['brewStyleLink'])) $brew_style_link = $_POST['brewStyleLink']; else $brew_style_link = "";
		
		// Going to start IDs for custom styles at 500
		// Allows for expansion of BA styles and saves conflicts with special requirement styles and subcategory limit exception styles
		if ($row_style_name['id'] < 500) {
			
			$insertSQL = sprintf("INSERT INTO $styles_db_table (
			id,
			brewStyleNum, 
			brewStyle, 
			brewStyleOG, 
			brewStyleOGMax, 
			brewStyleFG, 
			
			brewStyleFGMax, 
			brewStyleABV, 
			brewStyleABVMax, 
			brewStyleIBU, 
			brewStyleIBUMax, 
			
			brewStyleSRM, 
			brewStyleSRMMax, 
			brewStyleType, 
			brewStyleInfo, 
			brewStyleLink, 
			
			brewStyleGroup, 
			brewStyleActive, 
			brewStyleOwn,
			brewStyleVersion,
			brewStyleReqSpec,
			
			brewStyleStrength,
			brewStyleCarb,
			brewStyleSweet,
			brewStyleEntry
			) 
			VALUES (
			%s, %s, %s, %s, %s, 
			%s, %s, %s, %s, %s, 
			%s, %s, %s, %s, %s, 
			%s, %s, %s, %s, %s,
			%s, %s, %s, %s, %s
			)",
							   GetSQLValueString("500", "text"),
							   GetSQLValueString("A", "text"),
							   GetSQLValueString($_POST['brewStyle'], "scrubbed"),
							   GetSQLValueString($_POST['brewStyleOG'], "text"),
							   GetSQLValueString($_POST['brewStyleOGMax'], "text"),
							   GetSQLValueString($_POST['brewStyleFG'], "text"),
							   GetSQLValueString($_POST['brewStyleFGMax'], "text"),
							   GetSQLValueString($_POST['brewStyleABV'], "text"),
							   GetSQLValueString($_POST['brewStyleABVMax'], "text"),
							   GetSQLValueString($_POST['brewStyleIBU'], "text"),
							   GetSQLValueString($_POST['brewStyleIBUMax'], "text"),
							   GetSQLValueString($_POST['brewStyleSRM'], "text"),
							   GetSQLValueString($_POST['brewStyleSRMMax'], "text"),
							   GetSQLValueString($_POST['brewStyleType'], "text"),
							   GetSQLValueString($_POST['brewStyleInfo'], "text"),
							   GetSQLValueString($brew_style_link, "text"),
							   GetSQLValueString($style_add_one, "text"),
							   GetSQLValueString($_POST['brewStyleActive'], "text"),
							   GetSQLValueString($_POST['brewStyleOwn'], "text"),
							   GetSQLValueString($_SESSION['prefsStyleSet'], "text"),
							   GetSQLValueString($_POST['brewStyleReqSpec'], "text"),
							   GetSQLValueString($_POST['brewStyleStrength'], "text"),
							   GetSQLValueString($_POST['brewStyleCarb'], "text"),
							   GetSQLValueString($_POST['brewStyleSweet'], "text"),
							   GetSQLValueString(strtr($_POST['brewStyleEntry'],$quote_convert), "text")
							   );
			
			
		}
		
		else {
	
			$insertSQL = sprintf("INSERT INTO $styles_db_table (
			brewStyleNum, 
			brewStyle, 
			brewStyleOG, 
			brewStyleOGMax, 
			brewStyleFG, 
			
			brewStyleFGMax, 
			brewStyleABV, 
			brewStyleABVMax, 
			brewStyleIBU, 
			brewStyleIBUMax, 
			
			brewStyleSRM, 
			brewStyleSRMMax, 
			brewStyleType, 
			brewStyleInfo, 
			brewStyleLink, 
			
			brewStyleGroup, 
			brewStyleActive, 
			brewStyleOwn,
			brewStyleVersion,
			brewStyleReqSpec,
			
			brewStyleStrength,
			brewStyleCarb,
			brewStyleSweet,
			brewStyleEntry
			) 
			VALUES (
			%s, %s, %s, %s, %s, 
			%s, %s, %s, %s, %s, 
			%s, %s, %s, %s, %s, 
			%s, %s, %s, %s, %s,
			%s, %s, %s, %s
			)",
							   GetSQLValueString("A", "text"),
							   GetSQLValueString($_POST['brewStyle'], "scrubbed"),
							   GetSQLValueString($_POST['brewStyleOG'], "text"),
							   GetSQLValueString($_POST['brewStyleOGMax'], "text"),
							   GetSQLValueString($_POST['brewStyleFG'], "text"),
							   GetSQLValueString($_POST['brewStyleFGMax'], "text"),
							   GetSQLValueString($_POST['brewStyleABV'], "text"),
							   GetSQLValueString($_POST['brewStyleABVMax'], "text"),
							   GetSQLValueString($_POST['brewStyleIBU'], "text"),
							   GetSQLValueString($_POST['brewStyleIBUMax'], "text"),
							   GetSQLValueString($_POST['brewStyleSRM'], "text"),
							   GetSQLValueString($_POST['brewStyleSRMMax'], "text"),
							   GetSQLValueString($_POST['brewStyleType'], "text"),
							   GetSQLValueString($_POST['brewStyleInfo'], "text"),
							   GetSQLValueString($brew_style_link, "text"),
							   GetSQLValueString($style_add_one, "text"),
							   GetSQLValueString($_POST['brewStyleActive'], "text"),
							   GetSQLValueString($_POST['brewStyleOwn'], "text"),
							   GetSQLValueString($_SESSION['prefsStyleSet'], "text"),
							   GetSQLValueString($_POST['brewStyleReqSpec'], "text"),
							   GetSQLValueString($_POST['brewStyleStrength'], "text"),
							   GetSQLValueString($_POST['brewStyleCarb'], "text"),
							   GetSQLValueString($_POST['brewStyleSweet'], "text"),
							   GetSQLValueString(strtr($_POST['brewStyleEntry'],$quote_convert), "text")
							   );		
			
		}
	
		//echo $insertSQL;
		mysqli_real_escape_string($connection,$insertSQL);
		$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));
		
		$pattern = array('\'', '"');
		$insertGoTo = str_replace($pattern, "", $insertGoTo); 
		header(sprintf("Location: %s", stripslashes($insertGoTo)));
	
		
	} // end if ($action == "add");
	
	if ($action == "edit") {
		
		if ($_POST['brewStyleType'] == 2) $styleStrength = 0; else $styleStrength = $_POST['brewStyleStrength'];
		
		if ($id < 500) {
			
			$query_last_id = sprintf("SELECT id FROM %s ORDER BY id DESC LIMIT 1", $styles_db_table);
			$last_id = mysqli_query($connection,$query_last_id) or die (mysqli_error($connection));
			$row_last_id = mysqli_fetch_assoc($last_id);
			
			if ($row_last_id['id'] >= 500) $new_id = $row_last_id['id'] + 1;
			else $new_id = 500;
			
			$updateSQL = sprintf("UPDATE $styles_db_table SET
			id=%s, 
			brewStyleNum=%s, 
			brewStyle=%s, 
			brewStyleOG=%s, 
			brewStyleOGMax=%s, 
			brewStyleFG=%s, 
			
			brewStyleFGMax=%s, 
			brewStyleABV=%s, 
			brewStyleABVMax=%s, 
			brewStyleIBU=%s, 
			brewStyleIBUMax=%s, 
			
			brewStyleSRM=%s, 
			brewStyleSRMMax=%s, 
			brewStyleType=%s, 
			brewStyleInfo=%s, 
			brewStyleLink=%s, 
			
			brewStyleGroup=%s,
			brewStyleActive=%s, 
			brewStyleOwn=%s,
			brewStyleReqSpec=%s,
			brewStyleStrength=%s,
			brewStyleCarb=%s,
			brewStyleSweet=%s,
			brewStyleEntry=%s
			
			WHERE id=%s",
						   GetSQLValueString($new_id, "int"), 
						   GetSQLValueString($_POST['brewStyleNum'], "text"),
						   GetSQLValueString($_POST['brewStyle'], "scrubbed"),
						   GetSQLValueString($_POST['brewStyleOG'], "text"),
						   GetSQLValueString($_POST['brewStyleOGMax'], "text"),
						   GetSQLValueString($_POST['brewStyleFG'], "text"),
						   GetSQLValueString($_POST['brewStyleFGMax'], "text"),
						   GetSQLValueString($_POST['brewStyleABV'], "text"),
						   GetSQLValueString($_POST['brewStyleABVMax'], "text"),
						   GetSQLValueString($_POST['brewStyleIBU'], "text"),
						   GetSQLValueString($_POST['brewStyleIBUMax'], "text"),
						   GetSQLValueString($_POST['brewStyleSRM'], "text"),
						   GetSQLValueString($_POST['brewStyleSRMMax'], "text"),
						   GetSQLValueString($_POST['brewStyleType'], "text"),
						   GetSQLValueString($_POST['brewStyleInfo'], "text"),
						   GetSQLValueString($brew_style_link, "text"),
						   GetSQLValueString($_POST['brewStyleGroup'], "text"),
						   GetSQLValueString($_POST['brewStyleActive'], "text"),
						   GetSQLValueString($_POST['brewStyleOwn'], "text"),
						   GetSQLValueString($_POST['brewStyleReqSpec'], "text"),
						   GetSQLValueString($styleStrength, "text"),
						   GetSQLValueString($_POST['brewStyleCarb'], "text"),
						   GetSQLValueString($_POST['brewStyleSweet'], "text"),
						   GetSQLValueString(strtr($_POST['brewStyleEntry'],$quote_convert), "text"),
						   GetSQLValueString($id, "int"));
		}
		
		else {
		
			$updateSQL = sprintf("UPDATE $styles_db_table SET 
			brewStyleNum=%s, 
			brewStyle=%s, 
			brewStyleOG=%s, 
			brewStyleOGMax=%s, 
			brewStyleFG=%s, 
			
			brewStyleFGMax=%s, 
			brewStyleABV=%s, 
			brewStyleABVMax=%s, 
			brewStyleIBU=%s, 
			brewStyleIBUMax=%s, 
			
			brewStyleSRM=%s, 
			brewStyleSRMMax=%s, 
			brewStyleType=%s, 
			brewStyleInfo=%s, 
			brewStyleLink=%s, 
			
			brewStyleGroup=%s,
			brewStyleActive=%s, 
			brewStyleOwn=%s,
			brewStyleReqSpec=%s,
			brewStyleStrength=%s,
			brewStyleCarb=%s,
			brewStyleSweet=%s,
			brewStyleEntry=%s
			
			WHERE id=%s",
						   GetSQLValueString($_POST['brewStyleNum'], "text"),
						   GetSQLValueString($_POST['brewStyle'], "scrubbed"),
						   GetSQLValueString($_POST['brewStyleOG'], "text"),
						   GetSQLValueString($_POST['brewStyleOGMax'], "text"),
						   GetSQLValueString($_POST['brewStyleFG'], "text"),
						   GetSQLValueString($_POST['brewStyleFGMax'], "text"),
						   GetSQLValueString($_POST['brewStyleABV'], "text"),
						   GetSQLValueString($_POST['brewStyleABVMax'], "text"),
						   GetSQLValueString($_POST['brewStyleIBU'], "text"),
						   GetSQLValueString($_POST['brewStyleIBUMax'], "text"),
						   GetSQLValueString($_POST['brewStyleSRM'], "text"),
						   GetSQLValueString($_POST['brewStyleSRMMax'], "text"),
						   GetSQLValueString($_POST['brewStyleType'], "text"),
						   GetSQLValueString($_POST['brewStyleInfo'], "text"),
						   GetSQLValueString($brew_style_link, "text"),
						   GetSQLValueString($_POST['brewStyleGroup'], "text"),
						   GetSQLValueString($_POST['brewStyleActive'], "text"),
						   GetSQLValueString($_POST['brewStyleOwn'], "text"),
						   GetSQLValueString($_POST['brewStyleReqSpec'], "text"),
						   GetSQLValueString($styleStrength, "text"),
						   GetSQLValueString($_POST['brewStyleCarb'], "text"),
						   GetSQLValueString($_POST['brewStyleSweet'], "text"),
						   GetSQLValueString(strtr($_POST['brewStyleEntry'],$quote_convert), "text"),
						   GetSQLValueString($id, "int"));
						   
		}
	
	  	mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	  
		$query_log = sprintf("SELECT id FROM $brewing_db_table WHERE brewStyle = '%s'",$_POST['brewStyleOld']); 
		$log = mysqli_query($connection,$query_log) or die (mysqli_error($connection));
		$row_log = mysqli_fetch_assoc($log);
		$totalRows_log = mysqli_num_rows($log);
		  
	  do {
			 
			$updateSQL = sprintf("UPDATE $brewing_db_table SET brewStyle='%s' WHERE id='%s'", $_POST['brewStyle'],$row_log['id']);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
		
		} while ($row_log = mysqli_fetch_assoc($log));
	  
		$pattern = array('\'', '"');
		$updateGoTo = str_replace($pattern, "", $updateGoTo); 
		header(sprintf("Location: %s", stripslashes($updateGoTo)));
	}

} else { 
	header(sprintf("Location: %s", $base_url."index.php?msg=98"));
	exit;
}
?>