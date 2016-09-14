<?php 
// -----------------------------------------------------------
// Version 2.1.7.0
// If version is below 2.1.6.0, the following will run.
// 2.1.6.0 was last version to have an update to DB
// -----------------------------------------------------------

$output .= "<h4>Version ".$current_version_display."</h4>";
$output .= "<ul>";

// THE INCLUDES MUST BE IN THIS ORDER! DO NOT CHANGE!!

// -----------------------------------------------------------
// Create Needed Tables
// -----------------------------------------------------------

// Not needed for 2.1.7.0
// include ('current_create_tables.php'); 

// -----------------------------------------------------------
// Alter Existing Tables
// -----------------------------------------------------------

include ('current_alter_tables.php');

// -----------------------------------------------------------
// Data Updates
// -----------------------------------------------------------

include ('current_data_updates.php');

$output .= "</ul>";
?>