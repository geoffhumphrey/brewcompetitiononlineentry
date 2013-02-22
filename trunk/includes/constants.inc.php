<?php

$user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
if (preg_match('/MSIE/i', $user_agent)) $ua = "IE"; else $ua = "non-IE";

function open_or_closed($now,$date1,$date2) {
		if ($now < $date1) $output = "0";
		elseif (($now >= $date1) && ($now <= $date2)) $output = "1";
		else $output = "2";
		return $output;
}


function open_limit($total_entries,$limit,$registration_open) {
	if ($limit != "") {
		if (($total_entries >= $limit) && ($registration_open == "1")) return true;
		else return false;
	}
	else return false;
}

$registration_open = open_or_closed(strtotime("now"),$row_contest_info['contestRegistrationOpen'],$row_contest_info['contestRegistrationDeadline']);
$entry_window_open = open_or_closed(strtotime("now"),$row_contest_info['contestEntryOpen'],$row_contest_info['contestEntryDeadline']);
$judge_window_open = open_or_closed(strtotime("now"),$row_contest_info['contestJudgeOpen'],$row_contest_info['contestJudgeDeadline']);
 
$reg_open = getTimeZoneDateTime($row_prefs['prefsTimeZone'], $row_contest_info['contestRegistrationOpen'], $row_prefs['prefsDateFormat'],  $row_prefs['prefsTimeFormat'], "long", "date-time");
$reg_closed = getTimeZoneDateTime($row_prefs['prefsTimeZone'], $row_contest_info['contestRegistrationDeadline'], $row_prefs['prefsDateFormat'],  $row_prefs['prefsTimeFormat'], "long", "date-time");;

$entry_open = getTimeZoneDateTime($row_prefs['prefsTimeZone'], $row_contest_info['contestEntryOpen'], $row_prefs['prefsDateFormat'],  $row_prefs['prefsTimeFormat'], "long", "date-time"); ;
$entry_closed = getTimeZoneDateTime($row_prefs['prefsTimeZone'], $row_contest_info['contestEntryDeadline'], $row_prefs['prefsDateFormat'],$row_prefs['prefsTimeFormat'], "long", "date-time");

$judge_open = getTimeZoneDateTime($row_prefs['prefsTimeZone'], $row_contest_info['contestJudgeOpen'], $row_prefs['prefsDateFormat'],  $row_prefs['prefsTimeFormat'], "long", "date-time"); ;
$judge_closed = getTimeZoneDateTime($row_prefs['prefsTimeZone'], $row_contest_info['contestJudgeDeadline'], $row_prefs['prefsDateFormat'],$row_prefs['prefsTimeFormat'], "long", "date-time");

?>