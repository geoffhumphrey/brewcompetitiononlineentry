<?php
// ---------------------------------------------------------
// Entry Data Labels
// ---------------------------------------------------------

if ($go == "entries") {

	if (($action == "bottle-entry") && ($view != "special")) {
			
		$query_log = "SELECT * FROM $brewing_db_table";
		if ($filter != "default") $query_log .= sprintf(" WHERE brewCategorySort='%s'",$filter);
		$query_log .= " ORDER BY brewCategorySort,brewSubCategory,id ASC";
		
	}
	
	if (($action == "bottle-entry") && ($view == "special")) {
		
		$query_log = sprintf("SELECT * FROM %s",$prefix."brewing");
		if ($filter != "default") $query_log .= sprintf(" WHERE brewCategorySort='%s' AND brewReceived='1'",$filter);
		$query_log .= " ORDER BY brewCategorySort,brewSubCategory,brewJudgingNumber ASC";
		
	}
	
	if (($action == "bottle-judging") && ($view == "default")) {
		
		$query_log = sprintf("SELECT * FROM %s",$prefix."brewing");
		if ($filter != "default") $query_log .= sprintf(" WHERE brewCategorySort='%s'",$filter);
		$query_log .= " ORDER BY brewCategorySort,brewSubCategory,brewJudgingNumber ASC";
		
	}
	
	if (($action == "bottle-judging-round") && ($view == "default")) {
		$query_log = sprintf("SELECT * FROM %s",$prefix."brewing");
		//if ($filter != "default") $query_log .= sprintf(" WHERE brewCategorySort='%s'",$filter);
		$query_log .= " ORDER BY brewCategorySort,brewSubCategory,brewJudgingNumber ASC";
		
	}
	
	if (($action == "bottle-entry-round") && ($view == "default")) {
		
		$query_log = sprintf("SELECT * FROM %s",$prefix."brewing");
		//if ($filter != "default") $query_log .= sprintf(" WHERE brewCategorySort='%s'",$filter);
		$query_log .= " ORDER BY brewCategorySort,brewSubCategory,id ASC";
		
	}
	
	if (($action == "bottle-category-round") && ($view == "default")) {
				
		$query_log = sprintf("SELECT brewCategorySort,brewSubCategory FROM %s ORDER BY brewCategorySort,brewSubCategory ASC",$prefix."brewing");
		if ($filter != "default") $query_log .= sprintf(" WHERE brewCategorySort='%s'",$filter);
		
	}
	
	if (($action == "bottle-entry-round") && ($view == "OL5275WR")) {
		
		$query_log = sprintf("SELECT * FROM %s",$prefix."brewing");
		if ($filter != "default") $query_log .= sprintf(" WHERE brewCategorySort='%s'",$filter);
		$query_log .= " ORDER BY brewCategorySort,brewSubCategory,id ASC";
				
	}
	
	if (($go == "entries") && ($action == "bottle-judging") && ($view == "special")) {
		
		$query_log = sprintf("SELECT * FROM %s",$prefix."brewing");
		if ($filter != "default") $query_log .= sprintf(" WHERE brewCategorySort='%s' AND brewReceived='1'",$filter);
		$query_log .= " ORDER BY brewCategorySort,brewSubCategory,brewJudgingNumber ASC";
		
	}

	// Execute query
	$log = mysql_query($query_log, $brewing) or die(mysql_error());
	$row_log = mysql_fetch_assoc($log);
	$totalRows_log = mysql_num_rows($log);
	
}

// ---------------------------------------------------------
// Participant Data Labels
// ---------------------------------------------------------

if ($go == "participants") {
	
	if ($action == "judging_nametags") {
		
		$query_brewer = "SELECT a.id,a.brewerFirstName,a.brewerLastName,a.brewerCity,a.brewerState,b.uid,b.staff_judge,b.staff_steward,b.staff_staff,b.staff_organizer FROM $brewer_db_table a, $staff_db_table b WHERE a.uid = b.uid ORDER BY a.brewerLastName ASC";	
	
	}
	
	if (($action == "judging_labels") && ($id == "default")) {
		
		$query_brewer = "SELECT a.id,a.brewerFirstName,a.brewerLastName,a.brewerJudgeID,a.brewerEmail,a.brewerJudgeRank,b.uid,b.staff_judge FROM $brewer_db_table a, $staff_db_table b WHERE a.uid = b.uid AND b.staff_judge='1' ORDER BY a.brewerLastName ASC";
	
	}
	
	if ($action == "address_labels") {
		
		$query_brewer = sprintf("SELECT * FROM %s ORDER BY brewerLastName ASC",$brewer_db_table);
		
		if ($filter == "with_entries") { 
			$query_with_entries = sprintf("SELECT brewBrewerID FROM %s WHERE brewReceived='1'",$brewing_db_table);
			$with_entries = mysql_query($query_with_entries, $brewing) or die(mysql_error());
			$row_with_entries = mysql_fetch_assoc($with_entries);
		}
	}
	
	if (($action == "judging_labels") && ($id != "default")) {
		
		$query_brewer = sprintf("SELECT id,brewerFirstName,brewerLastName,brewerJudgeID,brewerEmail,brewerJudgeRank,uid FROM $brewer_db_table WHERE id = %s",$id);

	}
	
	// Execute the query
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	$totalRows_brewer = mysql_num_rows($brewer);
}

// ---------------------------------------------------------
// Score and Result Data Labels
// ---------------------------------------------------------
?>