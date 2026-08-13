<?php
declare(strict_types=1);

if ($filter == "default") {
	$winner_style_set = $_SESSION['prefsStyleSet'];
}

else {
	$winner_style_set = $row_disp_archive_winners['archiveStyleSet'];
	$filter_clean = preg_replace("/[^a-zA-Z0-9]+/", "", $filter);
	$special_best_info_db_table = $prefix."special_best_info_".$filter_clean;
	$judging_tables_db_table = $prefix."judging_tables_".$filter_clean;
	$style_types_db_table = $prefix."style_types_".$filter_clean;
	$judging_scores_db_table = $prefix."judging_scores_".$filter_clean;
	$judging_scores_bos_db_table = $prefix."judging_scores_bos_".$filter_clean;
}

if (table_exists($special_best_info_db_table)) {
	if ($action == "edit") {
		$db_conn->where("id", $id);
		$row_sbi = $db_conn->getOne($special_best_info_db_table);
		$rows_sbi = $row_sbi ? array($row_sbi) : array();
	} else {
		$db_conn->orderBy("sbi_rank", "ASC");
		$rows_sbi = $db_conn->get($special_best_info_db_table);
		$row_sbi = ($rows_sbi && count($rows_sbi) > 0) ? $rows_sbi[0] : null;
	}
	$totalRows_sbi = $db_conn->count;
}

if (table_exists($judging_tables_db_table)) {
	$db_conn->orderBy("tableNumber", "ASC");
	$rows_tables = $db_conn->get($judging_tables_db_table);
	$row_tables = ($rows_tables && count($rows_tables) > 0) ? $rows_tables[0] : null;
	$totalRows_tables = $db_conn->count;
}

if (table_exists($style_types_db_table)) {
	$rows_style_types = $db_conn->get($style_types_db_table);
	$row_style_types = ($rows_style_types && count($rows_style_types) > 0) ? $rows_style_types[0] : null;
}

if (table_exists($judging_scores_db_table)) {
	$db_conn->where("(scorePlace='1' OR scorePlace='2' OR scorePlace='3' OR scorePlace='4' OR scorePlace='5')");
	$row_scores = $db_conn->getOne($judging_scores_db_table, "COUNT(*) as 'count'");
}

if (table_exists($judging_scores_bos_db_table)) {
	$db_conn->where("(scorePlace='1' OR scorePlace='2' OR scorePlace='3' OR scorePlace='4' OR scorePlace='5')");
	$row_bos_scores = $db_conn->getOne($judging_scores_bos_db_table, "COUNT(*) as 'count'");
}
?>