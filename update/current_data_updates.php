<?php
// -----------------------------------------------------------
// Version 2.1.5
// Data Updates
// -----------------------------------------------------------

/*
// Example:
// -----------------------------------------------------------
// Data Update: XXX
// XXX
// -----------------------------------------------------------

$updateSQL = sprintf("UPDATE %s SET sponsorEnable = '1';",$sponsors_db_table);
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));	

$output .= "<li>XXX.</li>";
*/

// -----------------------------------------------------------
// Data Update: preferences
// -----------------------------------------------------------

$updateSQL = sprintf("UPDATE %s SET prefsSpecific = '1';",$prefix."preferences");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$updateSQL = sprintf("UPDATE %s SET prefsLanguage = '%s';",$prefix."preferences","English");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));	

$output .= "<li>Preferences data updated.</li>";

$updateSQL = sprintf("UPDATE %s SET brewStyle = '%s' WHERE id = %s",$prefix."styles","Czech Premium Pale Lager","107");
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$output .= "<li>Style data updated.</li>";

// -----------------------------------------------------------
// Data Update: Update Version in System Table
// -----------------------------------------------------------

$updateSQL = sprintf("UPDATE %s SET version='%s', version_date='%s' WHERE id='1'",$prefix."system",$current_version,"2016-08-31");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$output .= "<li>Version updated in system table.</li>";



?>