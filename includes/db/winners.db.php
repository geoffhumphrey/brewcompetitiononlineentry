<?php
if (table_exists($special_best_info_db_table)) { 
	$query_sbi = "SELECT * FROM $special_best_info_db_table";
	if ($action == "edit") $query_sbi .= " WHERE id='$id'"; else $query_sbi .= " ORDER BY sbi_rank ASC";
	$sbi = mysqli_query($connection,$query_sbi) or die (mysqli_error($connection));
	$row_sbi = mysqli_fetch_assoc($sbi);
	$totalRows_sbi = mysqli_num_rows($sbi);
}

if (table_exists($judging_tables_db_table)) { 
	$query_tables = "SELECT * FROM $judging_tables_db_table ORDER BY tableNumber ASC";
	$tables = mysqli_query($connection,$query_tables) or die (mysqli_error($connection));
	$row_tables = mysqli_fetch_assoc($tables);
	$totalRows_tables = mysqli_num_rows($tables);
}

if (table_exists($style_types_db_table)) {
	$query_style_types = "SELECT * FROM $style_types_db_table";
	$style_types = mysqli_query($connection,$query_style_types) or die (mysqli_error($connection));
	$row_style_types = mysqli_fetch_assoc($style_types);
}

if (table_exists($judging_scores_db_table)) {
	$query_scores = "SELECT COUNT(*) as 'count' FROM $judging_scores_db_table WHERE (scorePlace='1' OR scorePlace='2' OR scorePlace='3' OR scorePlace='4' OR scorePlace='5')";
	$scores = mysqli_query($connection,$query_scores) or die (mysqli_error($connection));
	$row_scores = mysqli_fetch_assoc($scores);
}

if (table_exists($judging_scores_bos_db_table)) {
	$query_bos_scores = "SELECT COUNT(*) as 'count' FROM $judging_scores_bos_db_table WHERE (scorePlace='1' OR scorePlace='2' OR scorePlace='3' OR scorePlace='4' OR scorePlace='5')";
	$bos_scores = mysqli_query($connection,$query_bos_scores) or die (mysqli_error($connection));
	$row_bos_scores = mysqli_fetch_assoc($bos_scores);
}
?>