<?php
$query_contest_info = "SELECT * FROM $contest_info_db_table WHERE id=1";
$row_contest_info = mysql_query($query_contest_info, $brewing) or die(mysql_error());
$contest_info = mysql_fetch_assoc($row_contest_info);

$query_brewing = sprintf("SELECT * FROM $brewing_db_table WHERE id = '%s'", $id);
$log = mysql_query($query_brewing, $brewing) or die(mysql_error());
$brewing_info = mysql_fetch_assoc($log);

$query_brewer_user = sprintf("SELECT * FROM $users_db_table WHERE id = '%s'", $bid);
$user = mysql_query($query_brewer_user, $brewing) or die(mysql_error());
$row_brewer_user_info = mysql_fetch_assoc($user);

$query_brewer_organizer = "SELECT a.brewerFirstName,a.brewerLastName FROM $brewer_db_table a, $staff_db_table b WHERE a.uid = b.uid AND staff_organizer='1'";
$brewer_organizer = mysql_query($query_brewer_organizer, $brewing) or die(mysql_error());
$row_brewer_organizer = mysql_fetch_assoc($brewer_organizer); 

$query_logged_in = sprintf("SELECT * FROM $users_db_table WHERE user_name = '%s'", $_SESSION['loginUsername']);
$logged_in_user = mysql_query($query_logged_in, $brewing) or die(mysql_error());
$row_logged_in_user = mysql_fetch_assoc($logged_in_user);

$query_brewer = sprintf("SELECT * FROM $brewer_db_table WHERE uid = '%s'", $bid);
$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
$brewer_info = mysql_fetch_assoc($brewer);
?>