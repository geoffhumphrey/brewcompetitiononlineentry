<?php
// General connections

# Connect to the DB

mysql_select_db($database, $brewing);

$url = parse_url($_SERVER['PHP_SELF']);
if (strstr($url['path'],"index.php")) { // only for version 1.2.1.0; REMOVE for subsequent version
	$query_version = sprintf("SELECT version FROM %s WHERE id=1", $prefix."system");
	$version = mysql_query($query_version, $brewing) or die(mysql_error());
	$row_version = mysql_fetch_assoc($version);
	$version = $row_version['version'];
}

$query_contest_info = sprintf("SELECT * FROM %s WHERE id=1", $prefix."contest_info");
$contest_info = mysql_query($query_contest_info, $brewing) or die(mysql_error());
$row_contest_info = mysql_fetch_assoc($contest_info);
$totalRows_contest_info = mysql_num_rows($contest_info); 

$query_prefs = sprintf("SELECT * FROM %s WHERE id=1", $prefix."preferences");
$prefs = mysql_query($query_prefs, $brewing) or die(mysql_error());
$row_prefs = mysql_fetch_assoc($prefs);
$totalRows_prefs = mysql_num_rows($prefs);

$query_judging_prefs = sprintf("SELECT * FROM %s WHERE id='1'", $prefix."judging_preferences");
$judging_prefs = mysql_query($query_judging_prefs, $brewing) or die(mysql_error());
$row_judging_prefs = mysql_fetch_assoc($judging_prefs);
$totalRows_judging_prefs = mysql_num_rows($judging_prefs);

$query_participant_count = sprintf("SELECT COUNT(*) as 'count' FROM %s", $prefix."brewer");
$result = mysql_query($query_participant_count, $brewing) or die(mysql_error());
$row = mysql_fetch_assoc($result);
$totalRows_participant_count = $row["count"];
mysql_free_result($result);

# Set global pagination variables 
$display = $row_prefs['prefsRecordPaging']; 
$pg = (isset($_REQUEST['pg']) && ctype_digit($_REQUEST['pg'])) ?  $_REQUEST['pg'] : 1;
$start = $display * $pg - $display;
if ($start == 0) $start_display = "1"; else $start_display = $start;

// Session specific queries
if (isset($_SESSION["loginUsername"]))  {
	$query_user = sprintf("SELECT * FROM %s WHERE user_name = '%s'", $prefix."users", $_SESSION["loginUsername"]);
	$user = mysql_query($query_user, $brewing) or die(mysql_error());
	$row_user = mysql_fetch_assoc($user);
	$totalRows_user = mysql_num_rows($user);

	$query_name = sprintf("SELECT * FROM %s WHERE uid='%s'", $prefix."brewer", $row_user['id']);
	$name = mysql_query($query_name, $brewing) or die(mysql_error());
	$row_name = mysql_fetch_assoc($name);
	$totalRows_name = mysql_num_rows($name);

	if (($go == "make_admin") || (($go == "participants") && ($action == "add"))) {
		$query_user_level = sprintf("SELECT * FROM %s WHERE user_name = '%s'", $prefix."users", $username);
		}
	elseif (($section == "brewer") && ($action == "edit")) { 
		$query_user_level = sprintf("SELECT * FROM %s WHERE user_name = '%s'", $prefix."users", $row_brewer['brewerEmail']);
		}
	else $query_user_level = sprintf("SELECT id from %s",$prefix."users");
	$user_level = mysql_query($query_user_level, $brewing) or die(mysql_error());
	$row_user_level = mysql_fetch_assoc($user_level);
	$totalRows_user_level = mysql_num_rows($user_level);
	
}



?>