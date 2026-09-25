<?php 

$scoresheet_display = array();
$archive_suffix = "";

if ($dbTable == "default") $dbTable = $prefix."evaluation";
else {
    $archive_suffix = "_".get_suffix($dbTable);
}

if ($view == "all") {   

    $db_conn->where ("eid", $id);
    $row_eval_all = $db_conn->get ($dbTable);

    foreach ($row_eval_all as $row_eval_all) {
        $scoresheet_display[] = $row_eval_all['id'];
    }

}

else $scoresheet_display[] = $id;

// $view=="all" loops this block once per judge evaluation on the same entry - if the
// release-pending message fires, it'd otherwise repeat once per judge. Show it once.
$eval_release_pending_shown = FALSE;

foreach ($scoresheet_display as $id) {

	include (EVALS.'db.eval.php');

    if ($eval_access_denied) {

        // Let the entrant know why their own scoresheet didn't come up, reusing the
        // exact release-pending nomenclature already shown on the entrant landing
        // page (pub/default.pub.php / sections/default.sec.php) - see issue #694.
        // Other denial reasons (not this entry's owner, bad/missing token) stay a
        // silent skip, unchanged, same as before this scoresheet-timing gate existed.
        if (($eval_release_pending) && ($_SESSION['userLevel'] == 2) && (!$eval_release_pending_shown)) {
            if ($_SESSION['prefsDisplayScoresheets'] == "Y") {
                echo sprintf("<p>%s %s.</p>",$default_page_text_024,getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['prefsScoresheetDelay'], $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "long", "date-time"));
            }
            else {
                echo sprintf("<p>%s %s.</p>",$default_page_text_005,getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['prefsWinnerDelay'], $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "long", "date-time"));
            }
            $eval_release_pending_shown = TRUE;
        }

        continue;

    }

    include (EVALS.'scoresheet_head.eval.php');

    // Display scoresheet based upon type declared in the record
    if ($row_eval['evalScoresheet'] == 1) include (EVALS.'full_output.eval.php');
	if ($row_eval['evalScoresheet'] == 2) include (EVALS.'checklist_output.eval.php');
	if ($row_eval['evalScoresheet'] == 3) include (EVALS.'structured_output.eval.php');
    if ($row_eval['evalScoresheet'] == 4) include (EVALS.'structured_output.eval.php');

} // end foreach ($scoresheet_display as $id)


?>

