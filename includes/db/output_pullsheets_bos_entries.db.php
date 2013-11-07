<?php
$query_entries_1 = sprintf("SELECT id,brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewInfo,brewMead1,brewMead2,brewMead3,brewJudgingNumber,brewBoxNum FROM %s WHERE id='%s'", $prefix."brewing", $row_bos['eid']);
$entries_1 = mysql_query($query_entries_1, $brewing) or die(mysql_error());
$row_entries_1 = mysql_fetch_assoc($entries_1);

$query_tables_1 = sprintf("SELECT id,tableName,tableNumber FROM $judging_tables_db_table WHERE id='%s'", $row_bos['scoreTable']);
$tables_1 = mysql_query($query_tables_1, $brewing) or die(mysql_error());
$row_tables_1 = mysql_fetch_assoc($tables_1);
$totalRows_tables = mysql_num_rows($tables_1);
?>