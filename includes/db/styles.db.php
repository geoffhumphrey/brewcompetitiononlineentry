<?php
$query_styles = "SELECT * FROM $styles_db_table";
if ((($section == "entry") || ($section == "brew") || ($action == "word") || ($action == "html")) || ((($section == "admin") && ($filter == "judging")) && ($bid != "default"))) $query_styles .= " WHERE brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum";
elseif (($section == "admin") && ($action == "edit") && ($go != "judging_tables")) $query_styles .= " WHERE id='$id'";
elseif (($section == "admin") && ($go == "count_by_style")) $query_styles .= " WHERE brewStyleActive='Y'";
elseif ((($section == "judge") && ($go == "judge")) || ($action == "add") || ($action == "edit")) $query_styles .= " WHERE brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum";
elseif (($section == "beerxml") && ($msg != "default")) $query_styles .= " WHERE brewStyleActive='Y' AND brewStyleOwn='bcoe'";
else $query_styles .= "";
$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
$totalRows_styles = mysql_num_rows($styles);

$query_styles2 = "SELECT * FROM $styles_db_table";
if (($section == "judge") && ($go == "judge")) $query_styles2 .= " WHERE brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum";
elseif ($section == "brew") $query_styles2 .= " WHERE brewStyleActive='Y' AND brewStyleGroup > '28' AND brewStyleReqSpec = '1'";
else $query_styles2 .= " WHERE brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum";
$styles2 = mysql_query($query_styles2, $brewing) or die(mysql_error());
$row_styles2 = mysql_fetch_assoc($styles2);
$totalRows_styles2 = mysql_num_rows($styles2);
?>