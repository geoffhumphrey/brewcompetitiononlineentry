<?php 

if (strtotime("now") < strtotime($row_contest_info['contestRegistrationOpen'])) $registration_open = "0"; // registration has not opened yet
if ((strtotime("now") >= strtotime($row_contest_info['contestRegistrationOpen'])) && (strtotime("now") <= strtotime($row_contest_info['contestRegistrationDeadline']))) $registration_open = "1"; // registration is currently open
if (strtotime("now") > strtotime($row_contest_info['contestRegistrationDeadline'])) $registration_open = "2"; // registration has closed

if (strtotime("now") < strtotime($row_contest_info['contestEntryOpen'])) $entry_window_open = "0"; // entry window has not opened yet
if ((strtotime("now") >= strtotime($row_contest_info['contestEntryOpen'])) && (strtotime("now") <= strtotime($row_contest_info['contestEntryDeadline']))) $entry_window_open = "1"; // entry window is currently open
if (strtotime("now") > strtotime($row_contest_info['contestEntryDeadline'])) $entry_window_open = "2"; // entry window  has closed

$reg_open = getTimeZoneDateTime($row_prefs['prefsTimeZone'], strtotime($row_contest_info['contestRegistrationOpen']), $row_prefs['prefsDateFormat'],  $row_prefs['prefsTimeFormat'], "long", "date-time");
$reg_closed = getTimeZoneDateTime($row_prefs['prefsTimeZone'], strtotime($row_contest_info['contestRegistrationDeadline']), $row_prefs['prefsDateFormat'],  $row_prefs['prefsTimeFormat'], "long", "date-time");;
$entry_open = getTimeZoneDateTime($row_prefs['prefsTimeZone'], strtotime($row_contest_info['contestEntryOpen']), $row_prefs['prefsDateFormat'],  $row_prefs['prefsTimeFormat'], "long", "date-time"); ;
$entry_closed = getTimeZoneDateTime($row_prefs['prefsTimeZone'], strtotime($row_contest_info['contestEntryDeadline']), $row_prefs['prefsDateFormat'],$row_prefs['prefsTimeFormat'], "long", "date-time");

function open_limit($total_entries,$limit,$registration_open) {
	if ($limit != "") {
		if (($total_entries >= $limit) && ($registration_open == "1")) return true;
		else return false;
	}
	else return false;
}
?>