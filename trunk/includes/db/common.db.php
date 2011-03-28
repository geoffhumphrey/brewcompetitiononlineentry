<?php
// General connections

# Connect to the DB
mysql_select_db($database, $brewing);

$query_contest_info = "SELECT * FROM contest_info WHERE id=1";
$contest_info = mysql_query($query_contest_info, $brewing) or die(mysql_error());
$row_contest_info = mysql_fetch_assoc($contest_info);
$totalRows_contest_info = mysql_num_rows($contest_info);

$query_prefs = "SELECT * FROM preferences WHERE id=1";
$prefs = mysql_query($query_prefs, $brewing) or die(mysql_error());
$row_prefs = mysql_fetch_assoc($prefs);
$totalRows_prefs = mysql_num_rows($prefs);

$query_judging_prefs = "SELECT * FROM judging_preferences WHERE id='1'";
$judging_prefs = mysql_query($query_judging_prefs, $brewing) or die(mysql_error());
$row_judging_prefs = mysql_fetch_assoc($judging_prefs);
$totalRows_judging_prefs = mysql_num_rows($judging_prefs);

# Set global pagination variables 
$display = $row_prefs['prefsRecordPaging']; 
$pg = (isset($_REQUEST['pg']) && ctype_digit($_REQUEST['pg'])) ?  $_REQUEST['pg'] : 1;
$start = $display * $pg - $display;
if ($start == 0) $start_display = "1"; else $start_display = $start;

// Session specific queries
if (isset($_SESSION["loginUsername"]))  {
	$query_user = sprintf("SELECT * FROM users WHERE user_name = '%s'", $_SESSION["loginUsername"]);
	$user = mysql_query($query_user, $brewing) or die(mysql_error());
	$row_user = mysql_fetch_assoc($user);
	$totalRows_user = mysql_num_rows($user);

	$query_name = sprintf("SELECT * FROM brewer WHERE uid='%s'", $row_user['id']);
	$name = mysql_query($query_name, $brewing) or die(mysql_error());
	$row_name = mysql_fetch_assoc($name);
	$totalRows_name = mysql_num_rows($name);

	if (($go == "make_admin") || (($go == "participants") && ($action == "add"))) {
		$query_user_level = sprintf("SELECT * FROM users WHERE user_name = '%s'", $username);
		}
	elseif (($section == "brewer") && ($action == "edit")) { 
		$query_user_level = sprintf("SELECT * FROM users WHERE user_name = '%s'", $row_brewer['brewerEmail']);
		}
	else $query_user_level = "SELECT id from users";
	$user_level = mysql_query($query_user_level, $brewing) or die(mysql_error());
	$row_user_level = mysql_fetch_assoc($user_level);
	$totalRows_user_level = mysql_num_rows($user_level);
	
}

?>