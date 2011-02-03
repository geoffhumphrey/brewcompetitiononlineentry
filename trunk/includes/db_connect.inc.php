<?php
// General connections

# Connect to the DB
mysql_select_db($database, $brewing);

if (($section == "pay") && ($msg == "1")) {

	$a = explode('-', $view);

	foreach (array_unique($a) as $value) {
		$updateSQL = "UPDATE brewing SET brewPaid='Y' WHERE id='".$value."';";
		mysql_select_db($database, $brewing);
		$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
	}

}

# Perform general queries
$query_contest_info = "SELECT * FROM contest_info WHERE id=1";
$contest_info = mysql_query($query_contest_info, $brewing) or die(mysql_error());
$row_contest_info = mysql_fetch_assoc($contest_info);
$totalRows_contest_info = mysql_num_rows($contest_info);

if (($section == "admin") && ($action == "edit")) $query_sponsors = "SELECT * FROM sponsors WHERE id='$id'"; else $query_sponsors = "SELECT * FROM sponsors ORDER BY sponsorLevel,sponsorName";
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


// This doesn't seem to be in use - each page uses its own db query.
//$query_contact = "SELECT * FROM contacts";
//if (($section == "admin") && ($action == "edit"))  $query_contact .= " WHERE id='$id'"; else $query_contact .= " ORDER BY contactLastName,contactPosition"; 
//$contact = mysql_query($query_contact, $brewing) or die(mysql_error());
//$row_contact = mysql_fetch_assoc($contact);
//$totalRows_contact = mysql_num_rows($contact);

if (($section == "default") || ($section == "past_winners")) { 
	if ($section == "past_winners")	$dbTable = $dbTable; else $dbTable = "brewing";
	if ($section == "past_winners") {
		$user_table = "users_".ltrim($dbTable, "brewing_");
		$brewer_table = "brewer_".ltrim($dbTable, "brewing_");
	}
	else {
		$user_table = "users";
		$brewer_table = "brewer";
	}

	$query_log_winners = "SELECT * FROM $dbTable WHERE brewWinner='Y' ORDER BY brewWinnerCat, brewWinnerSubCat, brewWinnerPlace ASC";
	$log_winners = mysql_query($query_log_winners, $brewing) or die(mysql_error());
	$row_log_winners = mysql_fetch_assoc($log_winners);
	$totalRows_log_winners = mysql_num_rows($log_winners);

	$query_bos = "(SELECT * FROM $dbTable WHERE brewCategory >= '1' AND brewCategory <= '23' AND brewBOSRound='Y') UNION (SELECT * FROM $dbTable WHERE brewCategory >= '29' AND brewBOSRound='Y') ORDER BY brewBOSPlace ASC";
	$bos = mysql_query($query_bos, $brewing) or die(mysql_error());
	$row_bos = mysql_fetch_assoc($bos);
	$totalRows_bos = mysql_num_rows($bos);

	$query_bos_winner = "(SELECT * FROM $dbTable WHERE brewCategory >= '1' AND brewCategory <= '23' AND brewBOSRound='Y' AND brewBOSPlace='1') UNION (SELECT * FROM $dbTable WHERE brewCategory >= '29' AND brewBOSRound='Y' AND brewBOSPlace='1')";
	$bos_winner = mysql_query($query_bos_winner, $brewing) or die(mysql_error());
	$row_bos_winner = mysql_fetch_assoc($bos_winner);
	$totalRows_bos_winner = mysql_num_rows($bos_winner);

	$query_bos2 = "SELECT * FROM $dbTable WHERE (brewCategory >= '27' AND brewCategory <= '28') AND brewBOSRound='Y' ORDER BY brewBOSPlace ASC";
	$bos2 = mysql_query($query_bos2, $brewing) or die(mysql_error());
	$row_bos2 = mysql_fetch_assoc($bos2);
	$totalRows_bos2 = mysql_num_rows($bos2);

	$query_bos_winner2 = "SELECT * FROM $dbTable WHERE (brewCategory >= '27' AND brewCategory <= '28') AND brewBOSRound='Y' AND brewBOSPlace='1'";
	$bos_winner2 = mysql_query($query_bos_winner2, $brewing) or die(mysql_error());
	$row_bos_winner2 = mysql_fetch_assoc($bos_winner2);
	$totalRows_bos_winner2 = mysql_num_rows($bos_winner2);

	$query_bos3 = "SELECT * FROM $dbTable WHERE (brewCategory >= '24' AND brewCategory <= '26') AND  brewBOSRound='Y' ORDER BY brewBOSPlace ASC";
	$bos3 = mysql_query($query_bos3, $brewing) or die(mysql_error());
	$row_bos3 = mysql_fetch_assoc($bos3);
	$totalRows_bos3 = mysql_num_rows($bos3);

	$query_bos_winner3 = "SELECT * FROM $dbTable WHERE (brewCategory >= '24' AND brewCategory <= '26') AND brewBOSRound='Y' AND brewBOSPlace='1'";
	$bos_winner3 = mysql_query($query_bos_winner3, $brewing) or die(mysql_error());
	$row_bos_winner3 = mysql_fetch_assoc($bos_winner3);
	$totalRows_bos_winner3 = mysql_num_rows($bos_winner3);
	/*
	// DEBUG
	echo $query_bos."<br>";
	echo $query_bos_winner."<br>";
	echo $query_bos2."<br>";
	echo $query_bos_winner2."<br>";
	echo $query_bos3."<br>";
	echo $query_bos_winner3."<br>";
	*/
}

$query_entry_count = "SELECT COUNT(*) as 'count' FROM brewing";
$result = mysql_query($query_entry_count, $brewing) or die(mysql_error());
$row = mysql_fetch_array($result);
$totalRows_entry_count = $row["count"];
mysql_free_result($result);

# Set global pagination variables 
//if (($totalRows_entry_count > $row_prefs['prefsRecordLimit']) || ($totalRows_participant_count > $row_prefs['prefsRecordLimit'])) $display = $row_prefs['prefsRecordPaging']; else $display = $limit; 
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

	if ($section == "list") { 
		$query_log = sprintf("SELECT * FROM brewing WHERE brewBrewerID = '%s' ORDER BY brewCategorySort, brewSubCategory, brewName $dir", $row_name['uid']); 
		$query_log_paid = "SELECT * FROM brewing WHERE brewBrewerID = '%s' AND NOT brewPaid='Y'"; 
		}
		
	elseif ($section == "pay") { 
		$query_log = sprintf("SELECT * FROM brewing WHERE brewBrewerID = '%s' AND NOT brewPaid='Y' ORDER BY brewCategorySort, brewSubCategory, brewName $dir", $row_name['uid']); 
		$query_log_paid = "SELECT * FROM brewing WHERE brewPaid='Y'"; 
		}
		
	elseif (($section == "brew") && ($action == "edit")) { 
		$query_log = "SELECT * FROM brewing WHERE id = '$id'"; 
		$query_log_paid = "SELECT * FROM brewing WHERE brewPaid='Y'"; 
		}
	elseif (($section == "admin") && ($go == "entries") && ($filter == "default") && ($dbTable == "default") && ($bid == "default")) { 
		$query_log = "SELECT * FROM brewing ORDER BY $sort $dir";
		if (($totalRows_entry_count > $row_prefs['prefsRecordLimit']) && ($view == "default")) $query_log .= " LIMIT $start, $display";
		$query_log_paid = "SELECT * FROM brewing WHERE brewPaid='Y'"; 
		}
	elseif (($section == "admin") && ($go == "entries") && ($filter != "default") && ($dbTable == "default") && ($bid == "default")) { 
		$query_log = "SELECT * FROM brewing WHERE brewCategorySort='$filter' ORDER BY $sort $dir";
		if (($totalRows_entry_count > $row_prefs['prefsRecordLimit']) && ($view == "default")) $query_log .= " LIMIT $start, $display";
		$query_log_paid = "SELECT * FROM brewing WHERE brewCategorySort='$filter' AND brewPaid='Y'"; 
		}
	elseif (($section == "admin") && ($go == "entries") && ($filter == "default") && ($dbTable != "default") && ($bid == "default")) { 
		$query_log = "SELECT * FROM $dbTable ORDER BY $sort $dir";
		//if ($view == "default") $query_log .= " LIMIT $start, $display";
		$query_log_paid = "SELECT * FROM $dbTable WHERE brewPaid='Y'"; 
		}
	elseif (($section == "admin") && ($go == "entries") && ($filter != "default") && ($dbTable != "default") && ($bid == "default")) { 
		$query_log = "SELECT * FROM $dbTable WHERE brewCategorySort='$filter' ORDER BY $sort $dir"; 
		//if ($view == "default") $query_log .= " LIMIT $start, $display";
		$query_log_paid = "SELECT * FROM $dbTable WHERE brewCategorySort='$filter' AND brewPaid='Y'"; 
		}
	elseif (($section == "admin") && ($go == "entries") && ($filter == "default") && ($bid != "default")) { 
		$query_log = "SELECT * FROM brewing WHERE brewBrewerID='$bid' ORDER BY $sort $dir";
		if (($totalRows_entry_count > $row_prefs['prefsRecordLimit']) && ($view == "default")) $query_log .= " LIMIT $start, $display";
		$query_log_paid = "SELECT * FROM brewing WHERE brewBrewerID='$bid' AND brewPaid='Y'"; 
		}
	else { 
		$query_log = "SELECT * FROM brewing ORDER BY $sort $dir";
		if (($totalRows_entry_count > $row_prefs['prefsRecordLimit']) && ($view == "default")) $query_log .= " LIMIT $start, $display";
		$query_log_paid = "SELECT * FROM brewing WHERE brewPaid='Y'"; 
		}
	$log = mysql_query($query_log, $brewing) or die(mysql_error());
	$row_log = mysql_fetch_assoc($log);
	$totalRows_log = mysql_num_rows($log);

	$log_paid = mysql_query($query_log_paid, $brewing) or die(mysql_error());
	$row_log_paid = mysql_fetch_assoc($log_paid);
	$totalRows_log_paid = mysql_num_rows($log_paid);

	if (($section == "brewer") && ($action == "edit")) $query_brewer = "SELECT * FROM brewer WHERE id = '$id'";
	elseif (($section == "admin") && ($go == "participants") && ($filter == "default")  && ($dbTable == "default")) {
		$query_brewer = "SELECT * FROM brewer ORDER BY brewerLastName";
		if (($totalRows_participant_count > $row_prefs['prefsRecordLimit']) && ($view == "default")) $query_brewer .= " LIMIT $start, $display";
		}
	elseif (($section == "admin") && ($go == "participants") && ($filter == "judges")   && ($dbTable == "default")) {
		$query_brewer = "SELECT * FROM brewer WHERE brewerJudge='Y' ORDER BY brewerLastName";
		if (($totalRows_participant_count > $row_prefs['prefsRecordLimit']) && ($view == "default"))  $query_brewer .= " LIMIT $start, $display";
		}
	elseif (($section == "admin") && ($go == "participants") && ($filter == "stewards") && ($dbTable == "default")) {
		$query_brewer = "SELECT * FROM brewer WHERE brewerSteward='Y' ORDER BY brewerLastName";
		if (($totalRows_participant_count > $row_prefs['prefsRecordLimit']) && ($view == "default"))  $query_brewer .= " LIMIT $start, $display";
		}
	elseif (($section == "admin") && ($go == "participants") && ($filter == "assignJudges") && ($dbTable == "default")) { 
		$query_brewer = "SELECT * FROM brewer WHERE brewerAssignment='J' ORDER BY brewerLastName";
		if (($totalRows_participant_count > $row_prefs['prefsRecordLimit']) && ($view == "default"))  $query_brewer .= " LIMIT $start, $display";
		}
	elseif (($section == "admin") && ($go == "participants") && ($filter == "assignStewards") && ($dbTable == "default")) {
		$query_brewer = "SELECT * FROM brewer WHERE brewerAssignment='S' ORDER BY brewerLastName";
		if (($totalRows_participant_count > $row_prefs['prefsRecordLimit']) && ($view == "default"))  $query_brewer .= " LIMIT $start, $display";
		}
	elseif (($section == "admin") && ($go == "participants") && ($filter == "default")  && ($dbTable != "default")) {
		$query_brewer = "SELECT * FROM $dbTable ORDER BY brewerLastName";
		//if (($totalRows_participant_count > $row_prefs['prefsRecordLimit']) && ($view == "default")) $query_brewer .= " LIMIT $start, $display";
		}
	elseif (($section == "admin") && ($go == "judging") && ($filter == "judges")  && ($dbTable == "default") && ($action == "update")) {
		$query_brewer = "SELECT * FROM brewer WHERE brewerAssignment='J' ORDER BY brewerLastName";
		if (($totalRows_participant_count > $row_prefs['prefsRecordLimit']) && ($view == "default")) $query_brewer .= " LIMIT $start, $display";
		}
	elseif (($section == "admin") && ($go == "judging") && ($filter == "stewards")  && ($dbTable == "default") && ($action == "update")) {
		$query_brewer = "SELECT * FROM brewer WHERE brewerAssignment='S' ORDER BY brewerLastName";
		if (($totalRows_participant_count > $row_prefs['prefsRecordLimit']) && ($view == "default")) $query_brewer .= " LIMIT $start, $display";
		}
	elseif (($section == "admin") && ($go == "judging") && ($filter == "judges")  && ($dbTable == "default") && ($action == "assign")) { 
		$query_brewer = "SELECT * FROM brewer WHERE brewerJudge='Y' ORDER BY brewerLastName";
		if (($totalRows_participant_count > $row_prefs['prefsRecordLimit']) && ($view == "default")) $query_brewer .= " LIMIT $start, $display";
		}
	elseif (($section == "admin") && ($go == "judging") && ($filter == "stewards")  && ($dbTable == "default") && ($action == "assign")) {
		$query_brewer = "SELECT * FROM brewer WHERE brewerSteward='Y' ORDER BY brewerLastName";
		if (($totalRows_participant_count > $row_prefs['prefsRecordLimit']) && ($view == "default")) $query_brewer .= " LIMIT $start, $display";
		}
	elseif (($section == "admin") && ($go == "make_admin")) {
		$query_brewer = "SELECT * FROM brewer WHERE brewerEmail='$username'";
		}
	else $query_brewer = $query_name;
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	$totalRows_brewer = mysql_num_rows($brewer);

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

// General judging connection
$query_judging = "SELECT * FROM judging";
if (($go == "styles") && ($bid != "default")) $query_judging .= " WHERE id='$bid'";
elseif (($go == "judging") && ($action == "update") && ($bid != "default")) $query_judging .= " WHERE id='$bid'";
elseif (($go == "judging") && (($action == "add") || ($action == "edit")))  $query_judging .= " WHERE id='$id'";
else $query_judging .= " ORDER BY judgingDate,judgingLocName";
$judging = mysql_query($query_judging, $brewing) or die(mysql_error());
$row_judging = mysql_fetch_assoc($judging);
$totalRows_judging = mysql_num_rows($judging);

$query_judging_prefs = "SELECT * FROM judging_preferences";
//if (($go == "styles") && ($bid != "default")) $query_judging .= " WHERE id='$bid'";
//elseif (($go == "judging_preferences") && ($action == "update") && ($bid != "default")) $query_judging .= " WHERE id='$bid'";
//elseif (($go == "judging_preferences") && ($action == "edit"))  $query_judging .= " WHERE id='1'";
//else $query_judging_prefs .= " ORDER BY judgingDate,judgingLocName";
$query_judging .= " WHERE id='1'";
$judging_prefs = mysql_query($query_judging_prefs, $brewing) or die(mysql_error());
$row_judging_prefs = mysql_fetch_assoc($judging_prefs);
$totalRows_judging_prefs = mysql_num_rows($judging_prefs);

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
if ((($section == "entry") || ($section == "brew") || ($action == "word") || ($action == "html")) || ((($section == "admin") && ($filter == "judging")) && ($bid != "default"))) $query_styles .= " WHERE brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum";
elseif (($section == "admin") && ($action == "edit") && ($go != "judging_tables")) $query_styles .= " WHERE id='$id'";
elseif ((($section == "judge") && ($go == "judge")) || ($action == "edit")) $query_styles .= " WHERE brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum";
elseif (($section == "beerxml") && ($msg != "default")) $query_styles .= " WHERE brewStyleActive='Y' AND brewStyleOwn='bcoe'";
else $query_styles .= "";
$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
$row_styles = mysql_fetch_assoc($styles);
$totalRows_styles = mysql_num_rows($styles);

$query_styles2 = "SELECT * FROM styles";
if ((($section == "judge") && ($go == "judge")) || ($action == "edit")) $query_styles2 .= " WHERE brewStyleActive='Y' AND brewStyleGroup ORDER BY brewStyleGroup,brewStyleNum";
else $query_styles2 .= " WHERE brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum";
$styles2 = mysql_query($query_styles2, $brewing) or die(mysql_error());
$row_styles2 = mysql_fetch_assoc($styles2);
$totalRows_styles2 = mysql_num_rows($styles2);

$query_paid = sprintf("SELECT * FROM brewing WHERE brewBrewerID='%s' AND brewPaid='Y'", $row_brewer['uid']);
$paid = mysql_query($query_paid, $brewing) or die(mysql_error());
$row_paid = mysql_fetch_assoc($paid);
$totalRows_paid = mysql_num_rows($paid);

$query_all = sprintf("SELECT * FROM brewing WHERE brewBrewerID='%s'", $row_brewer['uid']);
$all = mysql_query($query_all, $brewing) or die(mysql_error());
$row_all = mysql_fetch_assoc($all);
$totalRows_all = mysql_num_rows($all);

$total_not_paid = ($totalRows_all - $totalRows_paid);

if ($row_contest_info['contestEntryCap'] != "") $cap = $row_contest_info['contestEntryCap']; else $cap = "0";
if ($row_prefs['prefsTransFee'] != "Y") $paypal_fee = "N"; else $paypal_fee = "Y";
if ($row_contest_info['contestEntryFeeDiscount'] != "Y") $discount = "N"; else $discount = "Y";

if ($section == "admin") {
$query_tables = "SELECT * FROM judging_tables";
if ($id == "default") $query_tables_edit .= " ORDER BY tableNumber ASC";
$tables = mysql_query($query_tables, $brewing) or die(mysql_error());
$row_tables = mysql_fetch_assoc($tables);
$totalRows_tables = mysql_num_rows($tables);

}


function getParticipantCount() 
{
	require ('Connections/config.php');
	
	mysql_select_db($database, $brewing);
	
	$query_participant_count = "SELECT COUNT(*) as 'count' FROM brewer";
	$result = mysql_query($query_participant_count, $brewing) or die(mysql_error());
	$row = mysql_fetch_assoc($result);
	$participantCount = $row["count"];
	mysql_free_result($result);
	return $participantCount;

}

function getContactCount() 
{
	require ('Connections/config.php');
	
	mysql_select_db($database, $brewing);
	
	$query_contact_count = "SELECT COUNT(*) as 'count' FROM contacts";
	$result = mysql_query($query_contact_count, $brewing) or die(mysql_error());
	$row = mysql_fetch_assoc($result);
	$contactCount = $row["count"];
	mysql_free_result($result);
	return $contactCount;
}

function getContacts() 
{
	require ('Connections/config.php');
	
	mysql_select_db($database, $brewing);
	
	$query_contacts = "SELECT * FROM contacts ORDER BY contactLastName, contactPosition";
	$contacts = mysql_query($query_contacts, $brewing) or die(mysql_error());
	return $contacts;
}


?>