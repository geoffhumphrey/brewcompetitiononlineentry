<?php
// General connections

mysql_select_db($database, $brewing);

$query_contest_info = "SELECT * FROM contest_info WHERE id=1";
$contest_info = mysql_query($query_contest_info, $brewing) or die(mysql_error());
$row_contest_info = mysql_fetch_assoc($contest_info);
$totalRows_contest_info = mysql_num_rows($contest_info);

if (($section == "admin") && ($action == "edit")) $query_sponsors = "SELECT * FROM sponsors WHERE id='$id'"; else $query_sponsors = "SELECT * FROM sponsors ORDER BY sponsorName";
$sponsors = mysql_query($query_sponsors, $brewing) or die(mysql_error());
$row_sponsors = mysql_fetch_assoc($sponsors);
$totalRows_sponsors = mysql_num_rows($sponsors);



$query_prefs = "SELECT * FROM preferences WHERE id=1";
$prefs = mysql_query($query_prefs, $brewing) or die(mysql_error());
$row_prefs = mysql_fetch_assoc($prefs);
$totalRows_prefs = mysql_num_rows($prefs);

$query_brewers = "SELECT * FROM brewer ORDER BY brewerLastName";
$brewers = mysql_query($query_brewers, $brewing) or die(mysql_error());
$row_brewers = mysql_fetch_assoc($brewers);
$totalRows_brewers = mysql_num_rows($brewers);

$query_entries = "SELECT id FROM brewing";
$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
$row_entries = mysql_fetch_assoc($entries);
$totalRows_entries = mysql_num_rows($entries);

$query_archive = "SELECT * FROM archive";
$archive = mysql_query($query_archive, $brewing) or die(mysql_error());
$row_archive = mysql_fetch_assoc($archive);
$totalRows_archive = mysql_num_rows($archive);

$query_dropoff = "SELECT * FROM drop_off";
if ($action == "edit") $query_dropoff .= " WHERE id='$id'";
$dropoff = mysql_query($query_dropoff, $brewing) or die(mysql_error());
$row_dropoff = mysql_fetch_assoc($dropoff);
$totalRows_dropoff = mysql_num_rows($dropoff);

if (($section == "default") || ($section == "past_winners")) { 
if ($section == "past_winners") $dbTable = $dbTable; else $dbTable = "brewing";

$query_log_winners = "SELECT * FROM $dbTable WHERE brewWinner='Y' ORDER BY brewWinnerCat, brewWinnerSubCat, brewWinnerPlace ASC";
$log_winners = mysql_query($query_log_winners, $brewing) or die(mysql_error());
$row_log_winners = mysql_fetch_assoc($log_winners);
$totalRows_log_winners = mysql_num_rows($log_winners);

$query_bos = "SELECT * FROM $dbTable WHERE brewBOSRound='Y' ORDER BY brewBOSPlace ASC";
$bos = mysql_query($query_bos, $brewing) or die(mysql_error());
$row_bos = mysql_fetch_assoc($bos);
$totalRows_bos = mysql_num_rows($bos);

$query_bos_winner = "SELECT * FROM $dbTable WHERE brewBOSRound='Y' AND brewBOSPlace='1'";
$bos_winner = mysql_query($query_bos_winner, $brewing) or die(mysql_error());
$row_bos_winner = mysql_fetch_assoc($bos_winner);
$totalRows_bos_winner = mysql_num_rows($bos_winner);
}

// Session specific queries
if (isset($_SESSION["loginUsername"]))  {
$query_user = sprintf("SELECT * FROM users WHERE user_name = '%s'", $_SESSION["loginUsername"]);
$user = mysql_query($query_user, $brewing) or die(mysql_error());
$row_user = mysql_fetch_assoc($user);
$totalRows_user = mysql_num_rows($user);

$query_name = sprintf("SELECT * FROM brewer WHERE brewerEmail='%s'", $row_user['user_name']);
$name = mysql_query($query_name, $brewing) or die(mysql_error());
$row_name = mysql_fetch_assoc($name);
$totalRows_name = mysql_num_rows($name);

if (($section == "list") || ($section == "pay")) { $query_log = sprintf("SELECT * FROM brewing WHERE brewBrewerID = '%s' ORDER BY brewCategorySort, brewSubCategory, brewName $dir", $row_user['id']); $query_log_paid = "SELECT * FROM brewing WHERE brewPaid='Y'"; }
elseif (($section == "brew") && ($action == "edit")) { $query_log = "SELECT * FROM brewing WHERE id = '$id'"; $query_log_paid = "SELECT * FROM brewing WHERE brewPaid='Y'"; }
elseif (($section == "admin") && ($go == "entries") && ($filter == "default") && ($dbTable == "default") && ($bid == "default")) { $query_log = "SELECT * FROM brewing ORDER BY $sort $dir"; $query_log_paid = "SELECT * FROM brewing WHERE brewPaid='Y'"; }
elseif (($section == "admin") && ($go == "entries") && ($filter != "default") && ($dbTable == "default") && ($bid == "default")) { $query_log = "SELECT * FROM brewing WHERE brewCategorySort='$filter' ORDER BY $sort $dir"; $query_log_paid = "SELECT * FROM brewing WHERE brewCategorySort='$filter' AND brewPaid='Y'"; }
elseif (($section == "admin") && ($go == "entries") && ($filter == "default") && ($dbTable != "default") && ($bid == "default")) { $query_log = "SELECT * FROM $dbTable ORDER BY $sort $dir"; $query_log_paid = "SELECT * FROM $dbTable WHERE brewPaid='Y'"; }
elseif (($section == "admin") && ($go == "entries") && ($filter != "default") && ($dbTable != "default") && ($bid == "default")) { $query_log = "SELECT * FROM $dbTable WHERE brewCategorySort='$filter' ORDER BY $sort $dir"; $query_log_paid = "SELECT * FROM $dbTable WHERE brewCategorySort='$filter' AND brewPaid='Y'"; }
elseif (($section == "admin") && ($go == "entries") && ($filter == "default") && ($bid != "default")) { $query_log = "SELECT * FROM brewing WHERE brewBrewerID='$bid' ORDER BY $sort $dir"; $query_log_paid = "SELECT * FROM brewing WHERE brewBrewerID='$bid' AND brewPaid='Y'"; }
else { $query_log = "SELECT * FROM brewing ORDER BY $sort $dir"; $query_log_paid = "SELECT * FROM brewing WHERE brewPaid='Y'"; }
$log = mysql_query($query_log, $brewing) or die(mysql_error());
$row_log = mysql_fetch_assoc($log);
$totalRows_log = mysql_num_rows($log);

$log_paid = mysql_query($query_log_paid, $brewing) or die(mysql_error());
$row_log_paid = mysql_fetch_assoc($log_paid);
$totalRows_log_paid = mysql_num_rows($log_paid);

if (($section == "brewer") && ($action == "edit")) $query_brewer = "SELECT * FROM brewer WHERE id = '$id'";
elseif (($section == "admin") && ($go == "participants") && ($filter == "default")  && ($dbTable == "default")) $query_brewer = "SELECT * FROM brewer ORDER BY brewerLastName";
elseif (($section == "admin") && ($go == "participants") && ($filter == "judges")   && ($dbTable == "default")) $query_brewer = "SELECT * FROM brewer WHERE brewerJudge='Y' ORDER BY brewerLastName";
elseif (($section == "admin") && ($go == "participants") && ($filter == "stewards") && ($dbTable == "default")) $query_brewer = "SELECT * FROM brewer WHERE brewerSteward='Y' ORDER BY brewerLastName";
elseif (($section == "admin") && ($go == "participants") && ($filter == "assignJudges") && ($dbTable == "default")) $query_brewer = "SELECT * FROM brewer WHERE brewerAssignment='J' ORDER BY brewerLastName";
elseif (($section == "admin") && ($go == "participants") && ($filter == "assignStewards") && ($dbTable == "default")) $query_brewer = "SELECT * FROM brewer WHERE brewerAssignment='S' ORDER BY brewerLastName";
elseif (($section == "admin") && ($go == "participants") && ($filter == "default")  && ($dbTable != "default")) $query_brewer = "SELECT * FROM $dbTable ORDER BY brewerLastName";
elseif (($section == "admin") && ($go == "judging") && ($filter == "judges")  && ($dbTable == "default") && ($action == "update")) $query_brewer = "SELECT * FROM brewer WHERE brewerAssignment='J' ORDER BY brewerLastName";
elseif (($section == "admin") && ($go == "judging") && ($filter == "stewards")  && ($dbTable == "default") && ($action == "update")) $query_brewer = "SELECT * FROM brewer WHERE brewerAssignment='S' ORDER BY brewerLastName";
elseif (($section == "admin") && ($go == "judging") && ($filter == "judges")  && ($dbTable == "default") && ($action == "assign")) $query_brewer = "SELECT * FROM brewer WHERE brewerJudge='Y' ORDER BY brewerLastName";
elseif (($section == "admin") && ($go == "judging") && ($filter == "stewards")  && ($dbTable == "default") && ($action == "assign")) $query_brewer = "SELECT * FROM brewer WHERE brewerSteward='Y' ORDER BY brewerLastName";
elseif (($section == "admin") && ($go == "make_admin")) $query_brewer = "SELECT * FROM brewer WHERE brewerEmail='$username'";
else $query_brewer = sprintf("SELECT * FROM brewer WHERE brewerEmail = '%s'", $_SESSION["loginUsername"]);
$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
$row_brewer = mysql_fetch_assoc($brewer);
$totalRows_brewer = mysql_num_rows($brewer);


if ($go == "make_admin") $query_user_level = sprintf("SELECT * FROM users WHERE user_name = '%s'", $username);
elseif (($section == "brewer") && ($action == "edit")) $query_user_level = sprintf("SELECT * FROM users WHERE user_name = '%s'", $row_brewer['brewerEmail']);
else $query_user_level = "SELECT id from users";
$user_level = mysql_query($query_user_level, $brewing) or die(mysql_error());
$row_user_level = mysql_fetch_assoc($user_level);
$totalRows_user_level = mysql_num_rows($user_level);
}

// General judging connection
$query_judging = "SELECT * FROM judging";
if (($go == "styles") && ($bid != "default")) $query_judging .= " WHERE id='$bid'";
elseif (($go == "judging") && ($action == "update") && ($bid != "default")) $query_judging .= " WHERE id='$bid'";
elseif (($go == "judging") && (($action == "add") || ($action == "edit")))  $query_judging .= " WHERE id='$id'";
else $query_judging .= " ORDER BY judgingDate,judgingLocName";
$judging = mysql_query($query_judging, $brewing) or die(mysql_error());
$row_judging = mysql_fetch_assoc($judging);
$totalRows_judging = mysql_num_rows($judging);

// Separate connections for selected queries that are housed on the same page.
$query_judging1 = "SELECT * FROM judging ORDER BY judgingDate,judgingLocName";
$judging1 = mysql_query($query_judging1, $brewing) or die(mysql_error());
$row_judging1 = mysql_fetch_assoc($judging1);
$totalRows_judging1 = mysql_num_rows($judging1);

$query_judging2 = "SELECT * FROM judging";
if ($section == "list") $query_judging2 .= sprintf(" WHERE id='%s'", $row_brewer['brewerJudgeLocation2']);
if (($section == "brewer") || ($section == "admin")) $query_judging2 .= " ORDER BY judgingDate,judgingLocName";
$judging2 = mysql_query($query_judging2, $brewing) or die(mysql_error());
$row_judging2 = mysql_fetch_assoc($judging2);
$totalRows_judging2 = mysql_num_rows($judging2);

$query_judging3 = "SELECT * FROM judging";
if ((($section == "brewer") && ($action == "edit")) || ($section == "admin")) $query_judging3 .= " ORDER BY judgingDate,judgingLocName";
$judging3 = mysql_query($query_judging3, $brewing) or die(mysql_error());
$row_judging3 = mysql_fetch_assoc($judging3);
$totalRows_judging3 = mysql_num_rows($judging3);

$query_judging4 = "SELECT * FROM judging";
if (($row_brewer['brewerJudgeAssignedLocation'] != "") && ($row_brewer['brewerStewardAssignedLocation'] == "")) $query_judging4 .= sprintf(" WHERE id='%s'", $row_brewer['brewerJudgeAssignedLocation']);
if (($row_brewer['brewerJudgeAssignedLocation'] == "") && ($row_brewer['brewerStewardAssignedLocation'] != "")) $query_judging4 .= sprintf(" WHERE id='%s'", $row_brewer['brewerStewardAssignedLocation']);
$judging4 = mysql_query($query_judging4, $brewing) or die(mysql_error());
$row_judging4 = mysql_fetch_assoc($judging4);
$totalRows_judging4 = mysql_num_rows($judging4);

$query_stewarding = "SELECT * FROM judging";
if ($section == "list") $query_stewarding .= sprintf(" WHERE id='%s'", $row_brewer['brewerStewardLocation']);
if (($section == "brewer") || ($section == "admin")) $query_stewarding .= " ORDER BY judgingDate,judgingLocName";
$stewarding = mysql_query($query_stewarding, $brewing) or die(mysql_error());
$row_stewarding = mysql_fetch_assoc($stewarding);
$totalRows_stewarding = mysql_num_rows($stewarding);

$query_stewarding2 = "SELECT * FROM judging";
if ($section == "list") $query_stewarding2 .= sprintf(" WHERE id='%s'", $row_brewer['brewerStewardLocation2']);
if (($section == "brewer") || ($section == "admin")) $query_stewarding2 .= " ORDER BY judgingDate,judgingLocName";
$stewarding2 = mysql_query($query_stewarding2, $brewing) or die(mysql_error());
$row_stewarding2 = mysql_fetch_assoc($stewarding2);
$totalRows_stewarding2 = mysql_num_rows($stewarding2);

$query_styles = "SELECT * FROM styles";
if ((($section == "entry") || ($section == "brew")) || ((($section == "admin") && ($filter == "judging")) && ($bid != "default"))) $query_styles .= " WHERE brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum";
elseif (($section == "admin") && ($action == "edit")) $query_styles .= " WHERE id='$id'";
elseif ((($section == "judge") && ($go == "judge")) || ($action == "edit")) $query_styles .= " WHERE brewStyleActive='Y' AND brewStyleGroup < 29  ORDER BY brewStyleGroup,brewStyleNum";
else $query_styles .= "";
$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
$row_styles = mysql_fetch_assoc($styles);
$totalRows_styles = mysql_num_rows($styles);

$query_styles2 = "SELECT * FROM styles";
if ((($section == "judge") && ($go == "judge")) || ($action == "edit")) $query_styles2 .= " WHERE brewStyleActive='Y' AND brewStyleGroup < 29  ORDER BY brewStyleGroup,brewStyleNum";
else $query_styles2 .= " WHERE brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum";
$styles2 = mysql_query($query_styles2, $brewing) or die(mysql_error());
$row_styles2 = mysql_fetch_assoc($styles2);
$totalRows_styles2 = mysql_num_rows($styles2);

?>