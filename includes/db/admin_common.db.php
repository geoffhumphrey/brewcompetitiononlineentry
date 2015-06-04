<?php
if (NHC) {
	// Place NHC SQL calls below
	
	
}
// end if (NHC)
else {
	if (table_exists($style_types_db_table)) {
		
		if (($go == "default") || ($go == "style_types") || ($go == "styles") || ($go == "judging_scores")  || ($go == "judging_scores_bos") || ($go == "judging_tables") || ($go == "judging")) {
			$query_style_type = "SELECT * FROM $style_types_db_table"; 
			if (($action == "edit") && ($filter != "default")) $query_style_type .= " WHERE id='$filter'";
			if (($action == "enter") && ($filter != "default")) $query_style_type .= " WHERE id='$filter'";
			if (($go != "styles") && ($id !="default")) $query_style_type .= " WHERE id='$id'";
			if ((($go == "judging_tables") || ($go == "judging_scores_bos")) && ($action == "default") && ($id == "default")) $query_style_type .= " WHERE styleTypeBOS='Y'";
			$style_type = mysql_query($query_style_type, $brewing) or die(mysql_error());
			$row_style_type = mysql_fetch_assoc($style_type);
			$totalRows_style_type = mysql_num_rows($style_type); 
			//echo $totalRows_style_type;
		}
	}
	
	if (table_exists($judging_tables_db_table)) {
		if (($go == "default") || ($go == "participants") || ($go == "judging_scores") || ($go == "judging_tables") || ($go == "judging_flights") || ($go == "judging_tables") || ($go == "judging_locations")) {
			
			$query_tables = "SELECT * FROM $judging_tables_db_table";
			if (($go == "judging_scores") || (($section == "table_cards") && ($go == "judging_tables"))) $query_tables .= " ORDER BY tableNumber ASC";
			if (($section = "table_cards") && ($go == "judging_locations")) $query_tables = sprintf("SELECT a.*, b.assignRound FROM $judging_tables_db_table a, $judging_assignments_db_table b WHERE a.id = b.assignTable AND a.tableLocation = '%s' AND b.assignRound = '%s' GROUP BY b.assignTable ORDER BY tableNumber", $location, $round);
			
			$tables = mysql_query($query_tables, $brewing) or die(mysql_error());
			$row_tables = mysql_fetch_assoc($tables);
			$totalRows_tables = mysql_num_rows($tables); 
			
			//echo $query_tables."<br>";
			
			$query_tables_edit = "SELECT * FROM $judging_tables_db_table";
			if ($id != "default") $query_tables_edit .= " WHERE id='$id'";
			if (($id == "default") || ($go == "judging_scores") || ($go == "judging_scores_bos") || ($go == "judging_flights"))  $query_tables_edit .= " ORDER BY tableNumber ASC";
			$tables_edit = mysql_query($query_tables_edit, $brewing) or die(mysql_error());
			$row_tables_edit = mysql_fetch_assoc($tables_edit);
			$tables_edit_2 = mysql_query($query_tables_edit, $brewing) or die(mysql_error());
			$row_tables_edit_2 = mysql_fetch_assoc($tables_edit_2);
		}
	
	}
	
	if ($go == "judging_scores") {
		$query_scores = "SELECT * FROM $judging_scores_db_table ORDER BY eid ASC";
		$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
		$row_scores = mysql_fetch_assoc($scores);
		$totalRows_scores = mysql_num_rows($scores);
	}
	
	if (($go == "judging_scores_bos") || ($go == "judging_tables") || ($go == "output")) {
		
		$query_style_types = "SELECT * FROM $style_types_db_table";
		$style_types = mysql_query($query_style_types, $brewing) or die(mysql_error());
		$row_style_types = mysql_fetch_assoc($style_types);
		
		if ($action == "default") {
			
			$query_style_types_2 = "SELECT * FROM $style_types_db_table";
			$style_types_2 = mysql_query($query_style_types_2, $brewing) or die(mysql_error());
			$row_style_types_2 = mysql_fetch_assoc($style_types_2);
			
			} // end if ($action == "default);
		
		}
		
	$total_fees = total_fees($_SESSION['contestEntryFee'], $_SESSION['contestEntryFee2'], $_SESSION['contestEntryFeeDiscount'], $_SESSION['contestEntryFeeDiscountNum'], $_SESSION['contestEntryCap'], $_SESSION['contestEntryFeePasswordNum'], $bid, $filter);
	$total_fees_paid = total_fees_paid($_SESSION['contestEntryFee'], $_SESSION['contestEntryFee2'], $_SESSION['contestEntryFeeDiscount'], $_SESSION['contestEntryFeeDiscountNum'], $_SESSION['contestEntryCap'], $_SESSION['contestEntryFeePasswordNum'], $bid, $filter);
	$total_fees_unpaid = ($total_fees - $total_fees_paid);
	$total_nopay_received = total_nopay_received($go,"default");
	
	if ($go == "default") {
		$query_with_entries = sprintf("SELECT COUNT(DISTINCT brewBrewerId) as 'count' FROM %s",$prefix."brewing");
		$with_entries = mysql_query($query_with_entries, $brewing) or die(mysql_error());
		$row_with_entries = mysql_fetch_assoc($with_entries);
	}
	
	if (($go == "special_best_data") || ($go == "special_best")) {
		
		$query_sbi = "SELECT * FROM $special_best_info_db_table";
		if (($action == "add") || ($action == "edit")) $query_sbi .= " WHERE id='$id'"; 
		$sbi = mysql_query($query_sbi, $brewing) or die(mysql_error());
		$row_sbi = mysql_fetch_assoc($sbi);
		$totalRows_sbi = mysql_num_rows($sbi);
		
		if ($action == "add") $query_sbd = "SELECT * FROM $special_best_data_db_table WHERE id='$id'";
		elseif ($action == "edit") $query_sbd = "SELECT * FROM $special_best_data_db_table WHERE sid='$id' ORDER BY sbd_place ASC";
		else $query_sbd = "SELECT * FROM $special_best_data_db_table ORDER BY sid,sbd_place ASC";
		$sbd = mysql_query($query_sbd, $brewing) or die(mysql_error());
		$row_sbd = mysql_fetch_assoc($sbd);
		$totalRows_sbd = mysql_num_rows($sbd);
	}
	
}
?>