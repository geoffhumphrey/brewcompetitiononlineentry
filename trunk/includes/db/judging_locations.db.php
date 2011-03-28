<?php
$query_judging = "SELECT * FROM judging_locations";
if (($go == "styles") && ($bid != "default")) $query_judging .= " WHERE id='$bid'";
elseif (($go == "judging") && ($action == "update") && ($bid != "default")) $query_judging .= " WHERE id='$bid'";
elseif (($go == "judging") && (($action == "add") || ($action == "edit")))  $query_judging .= " WHERE id='$id'";
else $query_judging .= " ORDER BY judgingDate,judgingLocName";
$judging = mysql_query($query_judging, $brewing) or die(mysql_error());
$row_judging = mysql_fetch_assoc($judging);
$totalRows_judging = mysql_num_rows($judging); 

// Separate connections for selected queries that are housed on the same page.
$query_judging1 = "SELECT * FROM judging_locations ORDER BY judgingDate,judgingLocName";
$judging1 = mysql_query($query_judging1, $brewing) or die(mysql_error());
$row_judging1 = mysql_fetch_assoc($judging1);
$totalRows_judging1 = mysql_num_rows($judging1);

$query_judging2 = "SELECT * FROM judging_locations";
if ($section == "list") $query_judging2 .= sprintf(" WHERE id='%s'", $row_brewer['brewerJudgeLocation2']);
if (($section == "brewer") || ($section == "admin")) $query_judging2 .= " ORDER BY judgingDate,judgingLocName";
$judging2 = mysql_query($query_judging2, $brewing) or die(mysql_error());
$row_judging2 = mysql_fetch_assoc($judging2);
$totalRows_judging2 = mysql_num_rows($judging2);

$query_judging3 = "SELECT * FROM judging_locations";
if ((($section == "brewer") && ($action == "edit")) || ($section == "admin")) $query_judging3 .= " ORDER BY judgingDate,judgingLocName";
$judging3 = mysql_query($query_judging3, $brewing) or die(mysql_error());
$row_judging3 = mysql_fetch_assoc($judging3);
$totalRows_judging3 = mysql_num_rows($judging3);

$query_judging4 = "SELECT * FROM judging_locations";
if (($row_brewer['brewerJudgeAssignedLocation'] != "") && ($row_brewer['brewerStewardAssignedLocation'] == "")) $query_judging4 .= sprintf(" WHERE id='%s'", $row_brewer['brewerJudgeAssignedLocation']);
if (($row_brewer['brewerJudgeAssignedLocation'] == "") && ($row_brewer['brewerStewardAssignedLocation'] != "")) $query_judging4 .= sprintf(" WHERE id='%s'", $row_brewer['brewerStewardAssignedLocation']);
$judging4 = mysql_query($query_judging4, $brewing) or die(mysql_error());
$row_judging4 = mysql_fetch_assoc($judging4);
$totalRows_judging4 = mysql_num_rows($judging4);
?>