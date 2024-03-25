<?php 

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
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	$output .= "<li>Brewing table updated successfully.</li>";
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewer` ADD  `brewerJudgeMead` CHAR( 1 ) NULL AFTER  `brewerJudgeID` ;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewer` ADD  `brewerAssignmentStaff` CHAR( 1 ) NULL AFTER  `brewerAssignment`;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	$output .= "<li>Brewer table updated successfully.</li>";
	
	$updateSQL = "ALTER TABLE  `".$prefix."contest_info` ADD  `contestCircuit` TEXT NULL ;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	$output .= "<li>Competition Info table updated successfully.</li>";
	
	$output .= "</ul>";
}

else $output .= "<p>None</p>";
?>