<?php
$query_eval = sprintf("SELECT * FROM %s a, %s b WHERE a.id=%s AND a.eid=b.eid", $dbTable, $prefix."judging_scores".$archive_suffix, $id);
$eval = mysqli_query($connection,$query_eval) or die (mysqli_error($connection));
$row_eval = mysqli_fetch_assoc($eval);

$query_judge = sprintf("SELECT brewerFirstName,brewerLastName,brewerJudgeID,brewerJudgeRank,brewerEmail,brewerJudgeMead,brewerJudgeCider FROM %s WHERE uid=%s", $prefix."brewer".$archive_suffix, $row_eval['evalJudgeInfo']);
$judge = mysqli_query($connection,$query_judge) or die (mysqli_error($connection));
$row_judge = mysqli_fetch_assoc($judge);

$query_style = sprintf("SELECT brewStyle,brewStyleGroup,brewStyleNum,brewStyleType FROM %s WHERE id=%s",$prefix."styles", $row_eval['evalStyle']);
$style = mysqli_query($connection,$query_style) or die (mysqli_error($connection));
$row_style = mysqli_fetch_assoc($style);

$query_entry_info = sprintf("SELECT * FROM %s WHERE id=%s",$prefix."brewing".$archive_suffix, $row_eval['eid']);
$entry_info = mysqli_query($connection,$query_entry_info) or die (mysqli_error($connection));
$row_entry_info = mysqli_fetch_assoc($entry_info);
?>