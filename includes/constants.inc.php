<?php

/*
$user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
if (preg_match('/MSIE/i', $user_agent)) $user_agent = "IE"; else $user_agent = "non-IE";
*/

function open_or_closed($now,$date1,$date2) {
		// First date has not passed yet
		if ($now < $date1) $output = "0";
		
		// First date has passed, but second has not
		elseif (($now >= $date1) && ($now <= $date2)) $output = "1";
		
		// Both dates have passed
		else $output = "2";
		
		return $output;
}
 

function open_limit($total_entries,$limit,$registration_open) {
	
	// Check to see if the limit of entries has been reached
	if ($limit != "") {
		if (($total_entries >= $limit) && ($registration_open == "1")) return TRUE;
		else return FALSE;
	}
	else return FALSE;
}

$registration_open = open_or_closed(strtotime("now"),$_SESSION['contestRegistrationOpen'],$_SESSION['contestRegistrationDeadline']);
$entry_window_open = open_or_closed(strtotime("now"),$_SESSION['contestEntryOpen'],$_SESSION['contestEntryDeadline']);
$judge_window_open = open_or_closed(strtotime("now"),$_SESSION['contestJudgeOpen'],$_SESSION['contestJudgeDeadline']);
 
$reg_open = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['contestRegistrationOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time");
$reg_closed = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['contestRegistrationDeadline'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time");;

$entry_open = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['contestEntryOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time"); ;
$entry_closed = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['contestEntryDeadline'], $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], "long", "date-time");

$judge_open = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['contestJudgeOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time"); ;
$judge_closed = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['contestJudgeDeadline'], $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], "long", "date-time");

// DataTables Default Values

$output_datatables_bPaginate = "true";
$output_datatables_sPaginationType = "full_numbers";
$output_datatables_bLengthChange = "true";
$output_datatables_iDisplayLength = round($_SESSION['prefsRecordPaging']);
$output_datatables_sDom = "irftip";
$output_datatables_bStateSave = "false";
$output_datatables_bProcessing = "false";

?>