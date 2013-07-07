<?php
// Determine if current or archive
//include(INCLUDES.'db_tables.inc.php');
if (table_exists($style_types_db_table)) {
	
	if (($go == "default") || ($go == "style_types") || ($go == "styles") || ($go == "judging_scores")  || ($go == "judging_scores_bos") || ($go == "judging_tables") || ($go == "judging")) {
		$query_style_type = "SELECT * FROM $style_types_db_table"; 
		if (($action == "edit") && ($filter != "default")) $query_style_type .= " WHERE id='$filter'";
		if (($action == "enter") && ($filter != "default")) $query_style_type .= " WHERE id='$filter'";
		if (($go != "styles") && ($id !="default")) $query_style_type .= " WHERE id='$id'";
		if (($go == "judging_tables") && ($action == "default") && ($id == "default")) $query_style_type .= " WHERE styleTypeBOS='Y'";
		$style_type = mysql_query($query_style_type, $brewing) or die(mysql_error());
		$row_style_type = mysql_fetch_assoc($style_type);
	}
}

if (table_exists($judging_tables_db_table)) {
	if (($go == "default") || ($go == "judging_scores") || ($go == "judging_tables") || ($go == "judging_flights")) {
		
		$query_tables = "SELECT * FROM $judging_tables_db_table";
		if ($go == "judging_scores") $query_tables .= " ORDER BY tableNumber ASC";
		//if ($id != "default") $query_tables .= " WHERE id='$id'";
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

if ($go == "judging_scores_bos") {
	if ($action == "default") { 
		$query_style_types = "SELECT * FROM $style_types_db_table";
		$style_types = mysql_query($query_style_types, $brewing) or die(mysql_error());
		$row_style_types = mysql_fetch_assoc($style_types);
	
		$query_style_types_2 = "SELECT * FROM $style_types_db_table";
		$style_types_2 = mysql_query($query_style_types_2, $brewing) or die(mysql_error());
		$row_style_types_2 = mysql_fetch_assoc($style_types_2);
		} // end if ($action == "default);
	
	if ($action != "default") {
		$query_style_types = "SELECT * FROM $style_types_db_table";
		$style_types = mysql_query($query_style_types, $brewing) or die(mysql_error());
		$row_style_types = mysql_fetch_assoc($style_types);
		
		$query_enter_bos = "SELECT * FROM $judging_scores_db_table";
		if ($row_style_type['styleTypeBOSMethod'] == "1") $query_enter_bos .= " WHERE scoreType='$filter' AND scorePlace='1'";
		if ($row_style_type['styleTypeBOSMethod'] == "2") $query_enter_bos .= " WHERE scoreType='$filter' AND (scorePlace='1' OR scorePlace='2')";
		if ($row_style_type['styleTypeBOSMethod'] == "3") $query_enter_bos .= " WHERE (scoreType='$filter' AND scorePlace='1') OR (scoreType='$filter' AND scorePlace='2') OR (scoreType='$filter' AND scorePlace='3')";
		//if ($_SESSION['jPrefsBOSMethodBeer'] == "4") $query_enter_bos .= " WHERE scoreType='B' AND scorePlace='1'";
		$query_enter_bos .= " ORDER BY scoreTable ASC";
		$enter_bos = mysql_query($query_enter_bos, $brewing) or die(mysql_error());
		$row_enter_bos = mysql_fetch_assoc($enter_bos);
		$totalRows_enter_bos = mysql_num_rows($enter_bos);
		//echo $query_enter_bos;
		}
  	}
	
$total_fees = total_fees($_SESSION['contestEntryFee'], $_SESSION['contestEntryFee2'], $_SESSION['contestEntryFeeDiscount'], $_SESSION['contestEntryFeeDiscountNum'], $_SESSION['contestEntryCap'], $_SESSION['contestEntryFeePasswordNum'], $bid, $filter);
$total_fees_paid = total_fees_paid($_SESSION['contestEntryFee'], $_SESSION['contestEntryFee2'], $_SESSION['contestEntryFeeDiscount'], $_SESSION['contestEntryFeeDiscountNum'], $_SESSION['contestEntryCap'], $_SESSION['contestEntryFeePasswordNum'], $bid, $filter);
$total_fees_unpaid = ($total_fees - $total_fees_paid);
?>