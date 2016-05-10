<?php
$query_contest_info = "SELECT * FROM $contest_info_db_table WHERE id=1";
$row_contest_info = mysqli_query($connection,$query_contest_info) or die (mysqli_error($connection));
$contest_info = mysqli_fetch_assoc($row_contest_info);

$query_brewing = sprintf("SELECT * FROM $brewing_db_table WHERE id = '%s'", $id);
$log = mysqli_query($connection,$query_brewing) or die (mysqli_error($connection));
$brewing_info = mysqli_fetch_assoc($log);

$query_brewer_user = sprintf("SELECT * FROM $users_db_table WHERE id = '%s'", $bid);
$user = mysqli_query($connection,$query_brewer_user) or die (mysqli_error($connection));
$row_brewer_user_info = mysqli_fetch_assoc($user);

$query_brewer_organizer = "SELECT a.brewerFirstName,a.brewerLastName FROM $brewer_db_table a, $staff_db_table b WHERE a.uid = b.uid AND staff_organizer='1'";
$brewer_organizer = mysqli_query($connection,$query_brewer_organizer) or die (mysqli_error($connection));
$row_brewer_organizer = mysqli_fetch_assoc($brewer_organizer); 

$query_logged_in = sprintf("SELECT * FROM $users_db_table WHERE user_name = '%s'", $_SESSION['loginUsername']);
$logged_in_user = mysqli_query($connection,$query_logged_in) or die (mysqli_error($connection));
$row_logged_in_user = mysqli_fetch_assoc($logged_in_user);

$query_brewer = sprintf("SELECT * FROM $brewer_db_table WHERE uid = '%s'", $bid);
$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
$brewer_info = mysqli_fetch_assoc($brewer);
?>