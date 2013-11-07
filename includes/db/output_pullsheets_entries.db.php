<?php
$query_styles = sprintf("SELECT brewStyle FROM $styles_db_table WHERE id='%s'", $value);
$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
$row_styles = mysql_fetch_assoc($styles);

$query_entries = sprintf("SELECT id,brewStyle,brewCategory,brewCategorySort,brewSubCategory,brewInfo,brewMead1,brewMead2,brewMead3,brewJudgingNumber,brewBoxNum FROM %s WHERE brewStyle='%s' AND brewReceived='1' ORDER BY id", $prefix."brewing", $row_styles['brewStyle']);
$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
$row_entries = mysql_fetch_assoc($entries);
?>