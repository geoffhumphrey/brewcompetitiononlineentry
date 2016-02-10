<?php 
// -----------------------------------------------------------
// Version 2.0.0.0
// This version has a DB update.
// If version is below 2.0.0.0, the following will run.
// -----------------------------------------------------------

$output .= "<h4>Version ".$current_version."...</h4>";
$output .= "<ul>";

// THE INCLUDES MUST BE IN THIS ORDER! DO NOT CHANGE!!

// -----------------------------------------------------------
// Create Needed Tables
// -----------------------------------------------------------

// Not needed for 2.0.0.0
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