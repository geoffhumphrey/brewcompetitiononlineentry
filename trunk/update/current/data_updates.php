<?php

// -----------------------------------------------------------
// Data Updates
// -----------------------------------------------------------

// Update all current admins to Uber Admins

$query_admin_users = "SELECT * FROM $users_db_table WHERE userLevel='1'";
$admin_users = mysql_query($query_admin_users, $brewing) or die(mysql_error());
$row_admin_users = mysql_fetch_assoc($admin_users);

do { 
	
	$updateSQL = sprintf("UPDATE $users_db_table SET 
						 userLevel='%s'
						 WHERE id='%s'", 
						 "0",
						 $row_admin_users['id']);
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
	
}  
while ($row_admin_users = mysql_fetch_assoc($admin_users));

$output .=  "<li>Updates to user's table completed.</li>";

$updateSQL = sprintf("UPDATE %s SET version='%s', version_date='%s' WHERE id='%s'",$prefix."system","1.3.0.0","2013-08-01","1");
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 

$output .= "<li>Updates to system table completed.</li>";

?>