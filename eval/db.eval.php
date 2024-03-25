<?php

// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && (!isset($base_url)))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

/*
if (HOSTED) $styles_db_table = "bcoem_shared_styles";
else
*/

$styles_db_table = $prefix."styles";

$query_eval = sprintf("SELECT * FROM %s WHERE id=%s", $dbTable, $id);
$eval = mysqli_query($connection,$query_eval) or die (mysqli_error($connection));
$row_eval = mysqli_fetch_assoc($eval);

$query_judge = sprintf("SELECT * FROM %s WHERE uid=%s", $prefix."brewer".$archive_suffix, $row_eval['evalJudgeInfo']);
$judge = mysqli_query($connection,$query_judge) or die (mysqli_error($connection));
$row_judge = mysqli_fetch_assoc($judge);

/*
if (HOSTED) $query_style = sprintf("SELECT brewStyle,brewStyleGroup,brewStyleNum,brewStyleType FROM %s WHERE id='%s' UNION ALL SELECT brewStyle,brewStyleGroup,brewStyleNum,brewStyleType FROM %s WHERE id='%s'", $styles_db_table, $row_eval['evalStyle'], $prefix."styles", $row_eval['evalStyle']);
else 
*/
$query_style = sprintf("SELECT brewStyle,brewStyleGroup,brewStyleNum,brewStyleType FROM %s WHERE id=%s", $styles_db_table, $row_eval['evalStyle']);
$style = mysqli_query($connection,$query_style) or die (mysqli_error($connection));
$row_style = mysqli_fetch_assoc($style);

$query_entry_info = sprintf("SELECT * FROM %s WHERE id=%s",$prefix."brewing".$archive_suffix, $row_eval['eid']);
$entry_info = mysqli_query($connection,$query_entry_info) or die (mysqli_error($connection));
$row_entry_info = mysqli_fetch_assoc($entry_info);
?>