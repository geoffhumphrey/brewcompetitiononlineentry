<?php
$query_styles = sprintf("SELECT brewStyleGroup,brewStyleNum FROM %s WHERE id='%s'", $styles_db_table, $value);
$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
$row_styles = mysql_fetch_assoc($styles);

if ($view == "default") $order = "brewJudgingNumber";
else $order = "id";

$query_entries = sprintf("SELECT id,brewStyle,brewCategory,brewCategorySort,brewSubCategory,brewInfo,brewMead1,brewMead2,brewMead3,brewJudgingNumber,brewBoxNum,brewComments FROM %s WHERE brewCategorySort='%s' AND brewSubCategory='%s' AND brewReceived='1' ORDER BY %s ASC", $prefix."brewing", $row_styles['brewStyleGroup'],$row_styles['brewStyleNum'],$order);
$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
$row_entries = mysql_fetch_assoc($entries);

?>