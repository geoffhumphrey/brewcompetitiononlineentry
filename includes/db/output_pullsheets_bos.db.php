<?php
$query_bos = sprintf("SELECT * FROM %s",$prefix."judging_scores");
if ($type == "4") $query_bos .= sprintf(" WHERE (scoreType='%s' OR scoreType='%s')", "2", "3");
else $query_bos .= sprintf(" WHERE scoreType='%s'", $type);
if ($style_type_info[1] == "1") $query_bos .= " AND scorePlace='1'";
if ($style_type_info[1] == "2") $query_bos .= " AND (scorePlace='1' OR scorePlace='2')";
if ($style_type_info[1] == "3") $query_bos .= " AND (scorePlace='1' OR scorePlace='2' OR scorePlace='3')";
$query_bos .= " ORDER BY scoreTable ASC";
$bos = mysqli_query($connection,$query_bos) or die (mysqli_error($connection));
$row_bos = mysqli_fetch_assoc($bos);
$totalRows_bos = mysqli_num_rows($bos);
?>