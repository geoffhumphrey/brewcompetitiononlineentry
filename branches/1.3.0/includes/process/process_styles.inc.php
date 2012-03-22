<?php 
/*
 * Module:      process_styles.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "styles" table
 */
include(DB.'common.db.php');
if ($action == "update") {
foreach($_POST['id'] as $id)	{ 

		if ($filter == "default") {
		 	if ($_POST["style_active".$id] == "1") $updateSQL = "UPDATE $styles_active SET style_active='".$_POST["style_active".$id]."' WHERE id='".$id."'";
			else $updateSQL = "UPDATE $styles_active SET style_active='0' WHERE id='".$id."'";//mysql_select_db($database, $brewing);
		 	$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());	
		 	//echo $updateSQL."<br>"; 
		 }
		 
		if (($filter == "judging") && ($bid == $_POST["style_location".$id])) { 
		 	$updateSQL = "UPDATE $styles_active SET style_location='".$_POST["style_location".$id]."' WHERE id='".$id."';";
		 	$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		 	//echo $updateSQL."<br>"; 
		 
		 	// Also need to find all records in the "brewing" table (entries) that are null or have either old judging location associated with the style and update them with the new judging location.		 
			 mysql_select_db($database, $brewing);
			 $query_style_name = "SELECT *FROM $styles_active WHERE id='".$id."'";
			 $style_name = mysql_query($query_style_name, $brewing) or die(mysql_error());
			 $row_style_name = mysql_fetch_assoc($style_name);
			 
			 $query_loc = sprintf("SELECT * FROM brewing WHERE brewCategorySort='%s' AND brewSubCategory='%s'", $row_style_name['style_cat'], $row_style_name['style_subcat']);
			 $loc = mysql_query($query_loc, $brewing) or die(mysql_error());
			 $row_loc = mysql_fetch_assoc($loc);
			 $totalRows_loc = mysql_num_rows($loc);
			 //echo $query_loc."<br>";
		 		if ($totalRows_loc > 0) {
					do { 
					if ($row_loc['brewJudgingLocation'] != $_POST["brewStyleJudgingLoc".$id]) {
					$updateSQL2 = sprintf("UPDATE brewing SET brewJudgingLocation='%s' WHERE id='%s';", $_POST["style_location".$id], $row_loc['id']);
					$result2 = mysql_query($updateSQL2, $brewing) or die(mysql_error());
					//echo $updateSQL2."<br>"; 
						}
					}
				 
					while($row_loc = mysql_fetch_assoc($loc));
		 	}
		 }
	}

foreach($_POST['id_custom'] as $id)	{ 

		if ($filter == "default") {
			if ($_POST["style_active_custom".$id] == "1") $updateSQL = "UPDATE styles_custom SET style_active='".$_POST["style_active_custom".$id]."' WHERE id='".$id."'";
			else $updateSQL = "UPDATE styles_custom SET style_active='0' WHERE id='".$id."'";
			mysql_select_db($database, $brewing);
			$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
			//echo $updateSQL."<br>"; 
		 }
		 
		if (($filter == "judging") && ($bid == $_POST["style_location_custom".$id])) { 
		 $updateSQL = "UPDATE styles_custom SET style_location='".$_POST["style_location".$id]."' WHERE id='".$id."';";
		 $result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		 //echo $updateSQL."<br>"; 
		 
		 // Also need to find all records in the "brewing" table (entries) that are null or have either old judging location associated with the style and update them with the new judging location.		 
		 mysql_select_db($database, $brewing);
		 $query_style_name = "SELECT *FROM styles_custom WHERE id='".$id."'";
		 $style_name = mysql_query($query_style_name, $brewing) or die(mysql_error());
		 $row_style_name = mysql_fetch_assoc($style_name);
		 
		 $query_loc = sprintf("SELECT * FROM brewing WHERE brewCategorySort='%s' AND brewSubCategory='%s'", $row_style_name['style_cat'], $row_style_name['style_subcat']);
		 $loc = mysql_query($query_loc, $brewing) or die(mysql_error());
		 $row_loc = mysql_fetch_assoc($loc);
		 $totalRows_loc = mysql_num_rows($loc);
		 //echo $query_loc."<br>";
		 	if ($totalRows_loc > 0) {
		 		do { 
				if ($row_loc['brewJudgingLocation'] != $_POST["brewStyleJudgingLoc".$id]) {
				$updateSQL2 = sprintf("UPDATE brewing SET brewJudgingLocation='%s' WHERE id='%s';", $_POST["style_location".$id], $row_loc['id']); 
				$result2 = mysql_query($updateSQL2, $brewing) or die(mysql_error());
				//echo $updateSQL2."<br>"; 
				}
				}
				 
				while($row_loc = mysql_fetch_assoc($loc));
		 	}
		 }
	}
		 
if($result1){ 
	if (($section == "step7") && ($row_prefs['prefsCompOrg'] == "N")) header("location:../index.php?msg=success");
	elseif (($section == "step7") && ($row_prefs['prefsCompOrg'] == "Y")) header("location:../setup.php?section=step8");
	else header(sprintf("Location: %s", $massUpdateGoTo));
	}
}

// -----------------------------------------------------------
// Adding a Custom Style
// -----------------------------------------------------------

if ($action == "add") {
	
	// Adding custom styles in addition to the competitoin's selected style group
		
	mysql_select_db($database, $brewing);
	/*
	$query_style_name = "SELECT style_cat FROM styles_custom ORDER BY id DESC LIMIT 1";
	$style_name = mysql_query($query_style_name, $brewing) or die(mysql_error());
	$row_style_name = mysql_fetch_assoc($style_name);
	$style_add_one = $row_style_name['style_cat'] + 1;
	*/
	  $insertSQL = sprintf("INSERT INTO styles_custom (
	  style_cat,
	  style_subcat, 
	  style_name, 
	  style_og_min, 
	  style_og_max, 
	  
	  style_fg_min, 
	  style_fg_max, 
	  style_abv_min, 
	  style_abv_max, 
	  style_ibu_min, 
	  
	  style_ibu_max, 
	  style_srm_min, 
	  style_srm_max, 
	  style_type, 
	  style_info, 
	  
	  style_link, 
	  style_active,
	  style_spec_ingred
	  ) 
	  VALUES (
	  %s, %s, %s, %s, %s, 
	  %s, %s, %s, %s, %s, 
	  %s, %s, %s, %s, %s, 
	  %s, %s, %s)",
                       GetSQLValueString($_POST['style_cat'], "text"),
					   GetSQLValueString($_POST['style_subcat'], "text"),
                       GetSQLValueString(capitalize($_POST['style_name']), "scrubbed"),
                       GetSQLValueString($_POST['style_og_min'], "text"),
                       GetSQLValueString($_POST['style_og_max'], "text"),
                       GetSQLValueString($_POST['style_fg_min'], "text"),
                       GetSQLValueString($_POST['style_fg_max'], "text"),
                       GetSQLValueString($_POST['style_abv_min'], "text"),
                       GetSQLValueString($_POST['style_abv_max'], "text"),
                       GetSQLValueString($_POST['style_ibu_min'], "text"),
                       GetSQLValueString($_POST['style_ibu_max'], "text"),
                       GetSQLValueString($_POST['style_srm_min'], "text"),
                       GetSQLValueString($_POST['style_srm_max'], "text"),
                       GetSQLValueString($_POST['style_type'], "text"),
                       GetSQLValueString($_POST['style_info'], "text"),
                       GetSQLValueString($_POST['style_link'], "text"),
					   GetSQLValueString("1", "int"),
					   GetSQLValueString($_POST['style_spec_ingred'], "int")
					   );


  	mysql_select_db($database_brewing, $brewing);
  	$Result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());
  	header(sprintf("Location: %s", $insertGoTo));
	
}

// -----------------------------------------------------------
// Editing a Custom Style
// -----------------------------------------------------------

if ($action == "edit") {
	$updateSQL = sprintf("UPDATE styles_custom SET 
	  style_cat=%s,
	  style_subcat=%s, 
	  style_name=%s, 
	  style_og_min=%s, 
	  style_og_max=%s, 
	  style_fg_min=%s, 
	  
	  style_fg_max=%s, 
	  style_abv_min=%s, 
	  style_abv_max=%s, 
	  style_ibu_min=%s, 
	  style_ibu_max=%s, 
	  
	  style_srm_min=%s, 
	  style_srm_max=%s, 
	  style_type=%s, 
	  style_info=%s, 
	  style_link=%s, 
	  
	  style_active=%s,
	  style_spec_ingred=%s
	  
	  WHERE id=%s",
                       GetSQLValueString($_POST['style_cat'], "text"),
					   GetSQLValueString($_POST['style_subcat'], "text"),
                       GetSQLValueString(capitalize($_POST['style_name']), "scrubbed"),
                       GetSQLValueString($_POST['style_og_min'], "text"),
                       GetSQLValueString($_POST['style_og_max'], "text"),
                       GetSQLValueString($_POST['style_fg_min'], "text"),
                       GetSQLValueString($_POST['style_fg_max'], "text"),
                       GetSQLValueString($_POST['style_abv_min'], "text"),
                       GetSQLValueString($_POST['style_abv_max'], "text"),
                       GetSQLValueString($_POST['style_ibu_min'], "text"),
                       GetSQLValueString($_POST['style_ibu_max'], "text"),
                       GetSQLValueString($_POST['style_srm_min'], "text"),
                       GetSQLValueString($_POST['style_srm_max'], "text"),
                       GetSQLValueString($_POST['style_type'], "text"),
                       GetSQLValueString($_POST['style_info'], "text"),
                       GetSQLValueString($_POST['style_link'], "text"),
					   GetSQLValueString($_POST['style_active'], "int"),
					   GetSQLValueString($_POST['style_spec_ingred'], "int"),
                       GetSQLValueString($id, "int"));

  	mysql_select_db($database_brewing, $brewing);
  	$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
	//echo $updateSQL;  
  	header(sprintf("Location: %s", $updateGoTo));
}
?>