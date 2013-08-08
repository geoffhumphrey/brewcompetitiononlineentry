<?php 
$output .= "<h2>Version ".$current_version."...</h2>";

// THE INCLUDES MUST BE IN THIS ORDER! DO NOT CHANGE!!
	
include ('current/create_tables.php');
// Lots of data updates, breaking them up cuts down the script execution time
include ('current/alter_tables1.php');
include ('current/alter_tables2.php');
include ('current/alter_tables3.php');
include ('current/alter_tables4.php');
include ('current/alter_tables5.php');
include ('current/data_updates.php');
	
?>