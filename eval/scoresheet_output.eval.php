<?php 
$scoresheet_display = array();
$archive_suffix = "";

if ($dbTable == "default") $dbTable = $prefix."evaluation";
else {
    $archive_suffix = "_".get_suffix($dbTable);
}

if ($view == "all") {   

    $query_eval_all = sprintf("SELECT id FROM %s WHERE eid=%s", $dbTable, $id);
    $eval_all = mysqli_query($connection,$query_eval_all) or die (mysqli_error($connection));
    $row_eval_all = mysqli_fetch_assoc($eval_all);

    do {
        $scoresheet_display[] = $row_eval_all['id'];
    } while($row_eval_all = mysqli_fetch_assoc($eval_all));

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