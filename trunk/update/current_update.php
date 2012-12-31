<?php 
echo "<h2>Performing Updates for Version ".$current_version."...</h2>";
	
// THE INCLUDES MUST BE IN THIS ORDER! DO NOT CHANGE!!
	
include ('current/create_tables.php');
include ('current/alter_tables.php');
include ('current/data_updates.php');
	
?>