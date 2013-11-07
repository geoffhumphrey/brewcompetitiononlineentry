<?php
$query_bos = sprintf("SELECT * FROM %s",$prefix."judging_scores");
if ($style_type_info[1] == "1") $query_bos .= " WHERE scoreType='$type' AND scorePlace='1'";
if ($style_type_info[1] == "2") $query_bos .= " WHERE scoreType='$type' AND (scorePlace='1' OR scorePlace='2')";
if ($style_type_info[1] == "3") $query_bos .= " WHERE (scoreType='$type' AND scorePlace='1') OR (scoreType='$type' AND scorePlace='2') OR (scoreType='$type' AND scorePlace='3')";
$query_bos .= " ORDER BY scoreTable ASC";
$bos = mysql_query($query_bos, $brewing) or die(mysql_error());
$row_bos = mysql_fetch_assoc($bos);
$totalRows_bos = mysql_num_rows($bos);
?>