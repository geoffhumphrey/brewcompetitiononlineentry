<?php

if (!function_exists('check_update')) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}
 
// -----------------------------------------------------------
// Version 2.1.8.0
// 2.1.6.0 was last version to have an update to DB
// -----------------------------------------------------------

// -----------------------------------------------------------
// Alter Table: preferences
// Add ability for admins to toggle dropoff and shipping location display
// -----------------------------------------------------------

$output .= "<h4>Version 2.1.8</h4>";
$output .= "<ul>";

if (!check_update("prefsShipping", $prefix."preferences")) {
    $updateSQL = sprintf("ALTER TABLE `%s` ADD `prefsShipping` TINYINT(1) NULL DEFAULT NULL;",$prefix."preferences");
    $result = $db_conn->rawQuery($updateSQL);
}

if (!check_update("prefsDropOff", $prefix."preferences")) {
    $updateSQL = sprintf("ALTER TABLE `%s` ADD `prefsDropOff` TINYINT(1) NULL DEFAULT NULL;",$prefix."preferences");
    $result = $db_conn->rawQuery($updateSQL);
}

$output .=  "<li>Preferences table updated.</li>";

// -----------------------------------------------------------
// Data Update: preferences
// -----------------------------------------------------------

$updateSQL = sprintf("UPDATE %s SET prefsShipping = '1';",$prefix."preferences");
$result = $db_conn->rawQuery($updateSQL);

$updateSQL = sprintf("UPDATE %s SET prefsDropOff = '1';",$prefix."preferences");
$result = $db_conn->rawQuery($updateSQL);

$output .= "<li>Preferences data updated.</li>";


// -----------------------------------------------------------
// Data Update: Update Version in System Table
// -----------------------------------------------------------

$updateSQL = sprintf("UPDATE %s SET version='%s', version_date='%s' WHERE id='1'",$system_db_table,"2.1.8.0","2016-09-10");
$result = $db_conn->rawQuery($updateSQL);

$output .= "<li>Version updated in system table.</li>";

$output .= "</ul>";

?>