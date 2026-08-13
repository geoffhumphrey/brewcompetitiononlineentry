<?php
declare(strict_types=1);

if (!function_exists('check_update')) {
	$redirect = "../../403.php";
	$redirect_go_to = sprintf("Location: %s", $redirect);
	header($redirect_go_to);
	exit();
}
 
$output .= "<h4>Version 1.2.0.1, 1.2.0.2, and 1.2.0.3</h4>";

if (!check_update("brewJudgingNumber", $prefix."brewing")) {
	$output .= "<ul>";
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD  `brewJudgingNumber` VARCHAR( 10 ) NULL;";
	$result = $db_conn->rawQuery($updateSQL);

	if ($db_conn->getLastErrno() === 0) $output .= "<li>Brewing table updated successfully.</li>";
	else $output .= "<li class=\"text-danger\">Error: Brewing table NOT updated. ".$db_conn->getLastError()."</li>";

	$block_ok = TRUE;

	$updateSQL = "ALTER TABLE  `".$prefix."brewer` ADD  `brewerJudgeMead` CHAR( 1 ) NULL AFTER  `brewerJudgeID` ;";
	$result = $db_conn->rawQuery($updateSQL);
	if ($db_conn->getLastErrno() !== 0) { $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>"; $block_ok = FALSE; }

	$updateSQL = "ALTER TABLE  `".$prefix."brewer` ADD  `brewerAssignmentStaff` CHAR( 1 ) NULL AFTER  `brewerAssignment`;";
	$result = $db_conn->rawQuery($updateSQL);
	if ($db_conn->getLastErrno() !== 0) { $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>"; $block_ok = FALSE; }

	if ($block_ok) $output .= "<li>Brewer table updated successfully.</li>";

	$updateSQL = "ALTER TABLE  `".$prefix."contest_info` ADD  `contestCircuit` TEXT NULL ;";
	$result = $db_conn->rawQuery($updateSQL);

	if ($db_conn->getLastErrno() === 0) $output .= "<li>Competition Info table updated successfully.</li>";
	else $output .= "<li class=\"text-danger\">Error: Competition Info table NOT updated. ".$db_conn->getLastError()."</li>";
	
	$output .= "</ul>";
}

else $output .= "<p>None</p>";
?>