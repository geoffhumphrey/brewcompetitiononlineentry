<?php
declare(strict_types=1);

if (!function_exists('check_update')) {
	$redirect = "../../403.php";
	$redirect_go_to = sprintf("Location: %s", $redirect);
	header($redirect_go_to);
	exit();
}
 
$output .= "<h4>Version 1.1.6.0</h4>";

if (!check_update("prefsRecordLimit", $prefix."preferences")) {
	
	$output .= "<ul>";

	$updateSQL = "ALTER TABLE `".$prefix."brewer` CHANGE `brewerJudgeAssignedLocation` `brewerJudgeAssignedLocation` VARCHAR( 255 ) NULL DEFAULT NULL, CHANGE `brewerStewardAssignedLocation` `brewerStewardAssignedLocation` VARCHAR( 255 ) NULL DEFAULT NULL, CHANGE `brewerJudgeLocation` `brewerJudgeLocation` VARCHAR( 255 ) NULL DEFAULT NULL, CHANGE `brewerStewardLocation` `brewerStewardLocation` VARCHAR( 255 ) NULL DEFAULT NULL, ADD `brewerAHA` INT( 11 ) NULL;";
	$result = $db_conn->rawQuery($updateSQL);

	if ($db_conn->getLastErrno() === 0) $output .= "<li>Updates to the brewer table completed.</li>";
	else $output .= "<li class=\"text-danger\">Error: Brewer table NOT updated. ".$db_conn->getLastError()."</li>";

	$block_ok = TRUE;

	$updateSQL = "ALTER TABLE `".$prefix."preferences` ADD `prefsRecordLimit` INT( 11 ) NULL DEFAULT '500' COMMENT 'User defined record limit for using DataTables vs. PHP paging';";
	$result = $db_conn->rawQuery($updateSQL);
	if ($db_conn->getLastErrno() !== 0) { $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>"; $block_ok = FALSE; }

	$updateSQL = "ALTER TABLE `".$prefix."preferences` ADD `prefsRecordPaging` INT( 11 ) NULL DEFAULT '30' COMMENT 'User defined per page record limit'";
	$result = $db_conn->rawQuery($updateSQL);
	if ($db_conn->getLastErrno() !== 0) { $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>"; $block_ok = FALSE; }

	$updateSQL = "UPDATE `".$prefix."preferences` SET `prefsRecordLimit` = '500', `prefsRecordPaging` = '50' WHERE `id` = '1';";
	$result = $db_conn->rawQuery($updateSQL);
	if ($db_conn->getLastErrno() !== 0) { $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>"; $block_ok = FALSE; }

	if ($block_ok) $output .= "<li>Updates to preferences info table completed.</li>";

	$block_ok = TRUE;

	$updateSQL = "ALTER TABLE `".$prefix."brewing` CHANGE `brewPaid` `brewPaid` CHAR( 1 ) NULL DEFAULT 'N' ;";
	$result = $db_conn->rawQuery($updateSQL);
	if ($db_conn->getLastErrno() !== 0) { $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>"; $block_ok = FALSE; }

	$updateSQL = "ALTER TABLE `".$prefix."brewing` ADD `brewCoBrewer` VARCHAR( 255 ) NULL ;";
	$result = $db_conn->rawQuery($updateSQL);
	if ($db_conn->getLastErrno() !== 0) { $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>"; $block_ok = FALSE; }

	if ($block_ok) $output .= "<li>Updates to brewing table completed.</li>";
	$output .= "</ul>";
	
}

else $output .= "<p>None</p>";
?>