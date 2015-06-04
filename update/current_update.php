<?php 

// -----------------------------------------------------------
// Version 1.3.1.0
// This version only includes one DB update, albiet a pretty
// major one. The Styles table will be altered and updated with
// the BJCP 2015 styles.
// -----------------------------------------------------------

$output .= "<h2>Version ".$current_version."...</h2>";

// THE INCLUDES MUST BE IN THIS ORDER! DO NOT CHANGE!!

// -----------------------------------------------------------
// Create Needed Tables
// -----------------------------------------------------------

// include ('current/create_tables.php'); // Not needed for 1.3.1.0

// -----------------------------------------------------------
// Alter Existing Tables
// -----------------------------------------------------------

include ('current/alter_tables.php');

// -----------------------------------------------------------
// Data Updates
// Where there are lots of data updates, breaking them up cuts
// down the script execution time
// -----------------------------------------------------------

// include ('current/alter_tables1.php'); // Not needed for 1.3.1.0
// include ('current/alter_tables2.php'); // Not needed for 1.3.1.0
// include ('current/alter_tables3.php'); // Not needed for 1.3.1.0
// include ('current/alter_tables4.php'); // Not needed for 1.3.1.0
// include ('current/alter_tables5.php'); // Not needed for 1.3.1.0
include ('current/data_updates.php');
	
?>