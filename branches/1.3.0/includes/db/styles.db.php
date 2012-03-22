<?php
//include(DB.'common.db.php');

$query_styles = "SELECT * FROM $styles_active";
if ((($section == "entry") || ($section == "brew") || ($action == "word") || ($action == "html")) || ((($section == "admin") && ($filter == "judging")) && ($bid != "default"))) $query_styles .= " WHERE style_active='1' ORDER BY style_cat,style_subcat";
//elseif (($section == "admin") && ($action == "edit") && (($go != "judging_tables") || ($go != "styles_custom")) $query_styles .= " WHERE id='$id'";
elseif ((($section == "judge") && ($go == "judge")) || ($action == "add") || ($action == "edit")) $query_styles .= " WHERE style_active='1' ORDER BY style_cat,style_subcat";
elseif (($section == "beerxml") && ($msg != "default")) $query_styles .= " WHERE style_active='1' AND style_owner='bcoe'";
else $query_styles .= "";
//echo $query_styles;
$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
$row_styles = mysql_fetch_assoc($styles);
$totalRows_styles = mysql_num_rows($styles);

//echo $query_styles;

$query_styles2 = "SELECT * FROM $styles_active";
if ((($section == "judge") && ($go == "judge")) || ($action == "edit")) $query_styles2 .= " WHERE style_active='1' AND style_cat ORDER BY style_cat,style_subcat";
else $query_styles2 .= " WHERE style_active='1' ORDER BY style_cat,style_subcat";
$styles2 = mysql_query($query_styles2, $brewing) or die(mysql_error());
$row_styles2 = mysql_fetch_assoc($styles2);
$totalRows_styles2 = mysql_num_rows($styles2);

$query_styles_custom = "SELECT * FROM styles_custom";
if (($section == "admin") && ($action == "edit") && ($go == "styles")) $query_styles_custom .= " WHERE id='$id'";
$styles_custom = mysql_query($query_styles_custom, $brewing) or die(mysql_error());
$row_styles_custom = mysql_fetch_assoc($styles_custom);
$totalRows_styles_custom = mysql_num_rows($styles_custom);
?>