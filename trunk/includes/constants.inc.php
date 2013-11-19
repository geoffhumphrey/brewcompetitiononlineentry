<?php
$registration_open = open_or_closed(strtotime("now"),$row_contest_dates['contestRegistrationOpen'],$row_contest_dates['contestRegistrationDeadline']);
$entry_window_open = open_or_closed(strtotime("now"),$row_contest_dates['contestEntryOpen'],$row_contest_dates['contestEntryDeadline']);
$judge_window_open = open_or_closed(strtotime("now"),$row_contest_dates['contestJudgeOpen'],$row_contest_dates['contestJudgeDeadline']);
 
$reg_open = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestRegistrationOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time");
$reg_closed = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestRegistrationDeadline'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time");;

$entry_open = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestEntryOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time"); ;
$entry_closed = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestEntryDeadline'], $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], "long", "date-time");

$judge_open = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestJudgeOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time"); ;
$judge_closed = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestJudgeDeadline'], $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], "long", "date-time");

$currency = explode("^",currency_info($_SESSION['prefsCurrency'],1));
$currency_symbol = $currency[0];
$currency_code = $currency[1];

// DataTables Default Values

$output_datatables_bPaginate = "true";
$output_datatables_sPaginationType = "full_numbers";
$output_datatables_bLengthChange = "true";
$output_datatables_iDisplayLength = round($_SESSION['prefsRecordPaging']);
if ($action == "print") $output_datatables_sDom = "it";
else $output_datatables_sDom = "irftip";
$output_datatables_bStateSave = "false";
$output_datatables_bProcessing = "false";

$color = "#eeeeee";
$color1 = "#e0e0e0";
$color2 = "#eeeeee";
?>