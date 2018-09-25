<?php

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
    mysqli_select_db($connection,$database);
    mysqli_real_escape_string($connection,$updateSQL);
    $result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
}

if (!check_update("prefsDropOff", $prefix."preferences")) {
    $updateSQL = sprintf("ALTER TABLE `%s` ADD `prefsDropOff` TINYINT(1) NULL DEFAULT NULL;",$prefix."preferences");
    mysqli_select_db($connection,$database);
    mysqli_real_escape_string($connection,$updateSQL);
    $result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
}

$output .=  "<li>Preferences table updated.</li>";

// -----------------------------------------------------------
// Data Update: preferences
// -----------------------------------------------------------

$updateSQL = sprintf("UPDATE %s SET prefsShipping = '1';",$prefix."preferences");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$updateSQL = sprintf("UPDATE %s SET prefsDropOff = '1';",$prefix."preferences");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$output .= "<li>Preferences data updated.</li>";


// -----------------------------------------------------------
// Data Update: Update Version in System Table
// -----------------------------------------------------------

$updateSQL = sprintf("UPDATE %s SET version='%s', version_date='%s' WHERE id='1'",$prefix."system","2.1.8.0","2016-09-10");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$output .= "<li>Version updated in system table.</li>";

$output .= "</ul>";

?>