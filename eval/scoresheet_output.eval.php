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

foreach ($scoresheet_display as $id) {
	
	include (EVALS.'db.eval.php');
    include (EVALS.'scoresheet_head.eval.php');

    // Display scoresheet based upon type declared in the record
    if ($row_eval['evalScoresheet'] == 1) include (EVALS.'full_output.eval.php');
	if ($row_eval['evalScoresheet'] == 2) include (EVALS.'checklist_output.eval.php');
	if ($row_eval['evalScoresheet'] == 3) include (EVALS.'structured_output.eval.php');
    if ($row_eval['evalScoresheet'] == 4) include (EVALS.'structured_output.eval.php');

} // end foreach ($scoresheet_display as $id)


?>

