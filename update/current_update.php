<?php 

// -----------------------------------------------------------
// Version 1.3.2.0
// This version does not have a DB update.

// If version is below 1.3.1.0, the following will run

// Version 1.3.1.0 did have one DB update, albiet a pretty
// major one. The Styles table will be altered and updated with
// the BJCP 2015 styles.
// -----------------------------------------------------------

$output .= "<h4>Version ".$current_version."...</h4>";
$output .= "<ul>";
// THE INCLUDES MUST BE IN THIS ORDER! DO NOT CHANGE!!

// -----------------------------------------------------------
// Create Needed Tables
// -----------------------------------------------------------

// include ('current/create_tables.php'); // Not needed for 1.3.2.0

// -----------------------------------------------------------
// Alter Existing Tables
// -----------------------------------------------------------

include ('current/alter_tables.php');

// -----------------------------------------------------------
// Data Updates
// Where there are lots of data updates, breaking them up cuts
// down the script execution time
// -----------------------------------------------------------

include ('current/data_updates.php'); // Only needed for versions below 1.3.2.0
$output .= "</ul>";
?>