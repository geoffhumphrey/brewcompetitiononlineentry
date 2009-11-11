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

$query_styles = "SELECT * FROM styles";
$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
$row_styles = mysql_fetch_assoc($styles);
$totalRows_styles = mysql_num_rows($styles);

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

$query_log_winners = "SELECT * FROM brewing WHERE brewWinner='Y' ORDER BY brewWinnerCat, brewWinnerSubCat, brewWinnerPlace ASC";
$log_winners = mysql_query($query_log_winners, $brewing) or die(mysql_error());
$row_log_winners = mysql_fetch_assoc($log_winners);
$totalRows_log_winners = mysql_num_rows($log_winners);

$query_bos = "SELECT * FROM brewing WHERE brewBOSRound='Y' ORDER BY brewBOSPlace ASC";
$bos = mysql_query($query_bos, $brewing) or die(mysql_error());
$row_bos = mysql_fetch_assoc($bos);
$totalRows_bos = mysql_num_rows($bos);

$query_bos_winner = "SELECT * FROM brewing WHERE brewBOSRound='Y' AND brewBOSPlace='1'";
$bos_winner = mysql_query($query_bos_winner, $brewing) or die(mysql_error());
$row_bos_winner = mysql_fetch_assoc($bos_winner);
$totalRows_bos_winner = mysql_num_rows($bos_winner);

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

if (($section == "list") || ($section == "pay")) { $query_log = sprintf("SELECT * FROM brewing WHERE brewBrewerID = '%s' ORDER BY brewCategorySort, brewSubCategory, brewName ASC", $row_user['id']); $query_log_paid = "SELECT * FROM brewing WHERE brewPaid='Y'"; }
elseif (($section == "brew") && ($action == "edit")) { $query_log = "SELECT * FROM brewing WHERE id = '$id'"; $query_log_paid = "SELECT * FROM brewing WHERE brewPaid='Y'"; }
elseif (($section == "admin") && ($go == "entries") && ($filter == "default") && ($dbTable == "default")) { $query_log = "SELECT * FROM brewing ORDER BY brewCategorySort, brewSubCategory, brewName ASC"; $query_log_paid = "SELECT * FROM brewing WHERE brewPaid='Y'"; }
elseif (($section == "admin") && ($go == "entries") && ($filter != "default") && ($dbTable == "default")) { $query_log = "SELECT * FROM brewing WHERE brewCategorySort='$filter' ORDER BY brewSubCategory, brewName ASC"; $query_log_paid = "SELECT * FROM brewing WHERE brewCategorySort='$filter' AND brewPaid='Y'"; }
elseif (($section == "admin") && ($go == "entries") && ($filter == "default") && ($dbTable != "default")) { $query_log = "SELECT * FROM $dbTable ORDER BY brewCategorySort, brewSubCategory, brewName ASC"; $query_log_paid = "SELECT * FROM $dbTable WHERE brewPaid='Y'"; }
elseif (($section == "admin") && ($go == "entries") && ($filter != "default") && ($dbTable != "default")) { $query_log = "SELECT * FROM $dbTable WHERE brewCategorySort='$filter' ORDER BY brewSubCategory, brewName ASC"; $query_log_paid = "SELECT * FROM $dbTable WHERE brewCategorySort='$filter' AND brewPaid='Y'"; }
else { $query_log = "SELECT * FROM brewing ORDER BY brewSubCategory, brewName, brewBrewerLastName ASC"; $query_log_paid = "SELECT * FROM brewing WHERE brewPaid='Y'"; }
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
elseif (($section == "admin") && ($go == "participants") && ($filter == "default")  && ($dbTable != "default")) $query_brewer = "SELECT * FROM $dbTable ORDER BY brewerLastName";
elseif (($section == "admin") && ($go == "make_admin")) $query_brewer = "SELECT * FROM brewer WHERE brewerEmail='$username'";
else $query_brewer = sprintf("SELECT * FROM brewer WHERE brewerEmail = '%s'", $_SESSION["loginUsername"]);
$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
$row_brewer = mysql_fetch_assoc($brewer);
$totalRows_brewer = mysql_num_rows($brewer);

if (($section == "admin") && ($go == "make_admin")) {
$query_user_level = sprintf("SELECT * FROM users WHERE user_name = '%s'", $username);
$user_level = mysql_query($query_user_level, $brewing) or die(mysql_error());
$row_user_level = mysql_fetch_assoc($user_level);
$totalRows_user_level = mysql_num_rows($user_level);
}

}

?>