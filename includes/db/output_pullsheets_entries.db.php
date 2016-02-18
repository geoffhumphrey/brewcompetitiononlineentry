<?php
$query_styles = sprintf("SELECT brewStyleGroup,brewStyleNum FROM %s WHERE id='%s'", $styles_db_table, $value);
$styles = mysqli_query($connection,$query_styles) or die (mysqli_error($connection));
$row_styles = mysqli_fetch_assoc($styles);

if ($view == "default") $order = "brewJudgingNumber";
else $order = "id";

$query_entries = sprintf("SELECT id,brewStyle,brewCategory,brewCategorySort,brewSubCategory,brewInfo,brewMead1,brewMead2,brewMead3,brewJudgingNumber,brewBoxNum,brewComments FROM %s WHERE brewCategorySort='%s' AND brewSubCategory='%s' AND brewReceived='1' ORDER BY %s ASC", $prefix."brewing", $row_styles['brewStyleGroup'],$row_styles['brewStyleNum'],$order);
$entries = mysqli_query($connection,$query_entries) or die (mysqli_error($connection));
$row_entries = mysqli_fetch_assoc($entries);

?>