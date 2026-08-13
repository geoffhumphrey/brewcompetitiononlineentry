<?php 

if (!function_exists('check_update')) {
	$redirect = "../../403.php";
	$redirect_go_to = sprintf("Location: %s", $redirect);
	header($redirect_go_to);
	exit();
}

if (!check_update("contestRegistrationOpen", $prefix."contests_info")) {
	
	$output .= "<ul>";
	
	$updateSQL = "ALTER TABLE `".$prefix."contest_info` ADD `contestRegistrationOpen` DATE NULL AFTER `contestHostLocation`, ADD `contestEntryOpen` DATE NULL AFTER `contestRegistrationDeadline`;";
	$result = $db_conn->rawQuery($updateSQL);
	$output .= "<li>Updates to competition info table completed.</li>";

	$updateSQL = "ALTER TABLE `".$prefix."preferences` ADD `prefsBOSMead` CHAR( 1 ) NULL DEFAULT 'N', ADD `prefsBOSCider` CHAR( 1 ) NULL DEFAULT 'N';";
	$result = $db_conn->rawQuery($updateSQL);
	$output .= "<li>Updates to preferences info table completed.</li>";
	
	$output .= "</ul>";

}

else $output .= "<p>None</p>";
?>