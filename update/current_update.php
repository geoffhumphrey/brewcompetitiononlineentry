<?php 
// -----------------------------------------------------------
// If version is below 2.0.0, the following will run.
// 2.0.0 was the last version with table/data updates.
// -----------------------------------------------------------

$output .= "<h4>Version ".$current_version."...</h4>";
$output .= "<ul>";

// THE INCLUDES MUST BE IN THIS ORDER! DO NOT CHANGE!!

// -----------------------------------------------------------
// Create Needed Tables
// -----------------------------------------------------------

// Not needed for 2.0.0
// include ('current/create_tables.php'); 

// -----------------------------------------------------------
// Alter Existing Tables
// -----------------------------------------------------------

include ('current/alter_tables.php');

// -----------------------------------------------------------
// Data Updates
// -----------------------------------------------------------

include ('current/data_updates.php');

$output .= "</ul>";
?>