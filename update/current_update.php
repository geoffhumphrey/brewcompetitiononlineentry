<?php
// -----------------------------------------------------------
// Version 2.1.9.0 through 2.1.12.0
// If version is below 2.1.8.0, the following will run.
// 2.1.8.0 was last version to require an update trigger.
// -----------------------------------------------------------

$output .= "<h4>Version ".$current_version_display."</h4>";
$output .= "<ul>";

// THE INCLUDES MUST BE IN THIS ORDER! DO NOT CHANGE!!

// -----------------------------------------------------------
// Create Needed Tables
// -----------------------------------------------------------

// Not needed for 2.1.9.0
// include ('current_create_tables.php');

// -----------------------------------------------------------
// Alter Existing Tables
// -----------------------------------------------------------

include ('current_alter_tables.php');

// -----------------------------------------------------------
// Data Updates
// -----------------------------------------------------------

include ('current_data_updates.php');

// -----------------------------------------------------------
// Include Off-schedule Updates
// -----------------------------------------------------------

include ('off_schedule_update.php');

$output .= "</ul>";
?>