<?php 

$scoresheet_display = array();
$archive_suffix = "";

if ($dbTable == "default") $dbTable = $prefix."evaluation";
else {
    $archive_suffix = "_".get_suffix($dbTable);
}

if ($view == "all") {   

    /*
    $query_eval_all = sprintf("SELECT id FROM %s WHERE eid=%s", $dbTable, $id);
    $eval_all = mysqli_query($connection,$query_eval_all) or die ("A database error occurred.");
    $row_eval_all = mysqli_fetch_assoc($eval_all);
    */

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

