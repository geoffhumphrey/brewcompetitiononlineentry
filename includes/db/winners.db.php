<?php
if (NHC) {
	// Place NHC SQL calls below
	
	
}
// end if (NHC)

else {

	if (table_exists($special_best_info_db_table)) { 
		$query_sbi = "SELECT * FROM $special_best_info_db_table";
		if ($action == "edit") $query_sbi .= " WHERE id='$id'"; else $query_sbi .= " ORDER BY sbi_rank ASC";
		$sbi = mysql_query($query_sbi, $brewing) or die(mysql_error());
		$row_sbi = mysql_fetch_assoc($sbi);
		$totalRows_sbi = mysql_num_rows($sbi);
	}
	
	if (table_exists($judging_tables_db_table)) { 
		$query_tables = "SELECT * FROM $judging_tables_db_table ORDER BY tableNumber ASC";
		$tables = mysql_query($query_tables, $brewing) or die(mysql_error());
		$row_tables = mysql_fetch_assoc($tables);
		$totalRows_tables = mysql_num_rows($tables);
	}
	
	if (table_exists($style_types_db_table)) {
		$query_style_types = "SELECT * FROM $style_types_db_table";
		$style_types = mysql_query($query_style_types, $brewing) or die(mysql_error());
		$row_style_types = mysql_fetch_assoc($style_types);
	}
	
	if (table_exists($judging_scores_db_table)) {
		$query_scores = "SELECT COUNT(*) as 'count' FROM $judging_scores_db_table WHERE (scorePlace='1' OR scorePlace='2' OR scorePlace='3' OR scorePlace='4' OR scorePlace='5')";
		$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
		$row_scores = mysql_fetch_assoc($scores);
	}
	
	if (table_exists($judging_scores_bos_db_table)) {
		$query_bos_scores = "SELECT COUNT(*) as 'count' FROM $judging_scores_bos_db_table WHERE (scorePlace='1' OR scorePlace='2' OR scorePlace='3' OR scorePlace='4' OR scorePlace='5')";
		$bos_scores = mysql_query($query_bos_scores, $brewing) or die(mysql_error());
		$row_bos_scores = mysql_fetch_assoc($bos_scores);
	}
	
} // end else
?>