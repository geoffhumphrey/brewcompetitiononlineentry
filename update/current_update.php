<?php 
// -----------------------------------------------------------
// If version is below 2.1.5.0, the following will run.
// 2.1.0.0 was the last version with updates.
// -----------------------------------------------------------

$output .= "<h4>Version ".$current_version_display."...</h4>";
$output .= "<p class=\"lead\"><small><strong>Please note!</strong> This update contains a conversion script that affects each table in your database. Therefore, it may take a while to run. Please be patient!</small></p>";
$output .= "<ul>";

// THE INCLUDES MUST BE IN THIS ORDER! DO NOT CHANGE!!

// -----------------------------------------------------------
// Create Needed Tables
// -----------------------------------------------------------

// Not needed for 2.1.5.0
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