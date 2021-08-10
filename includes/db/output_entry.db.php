<?php
$query_contest_info = sprintf("SELECT * FROM %s WHERE id=1", $prefix."contest_info");
$row_contest_info = mysqli_query($connection,$query_contest_info) or die (mysqli_error($connection));
$contest_info = mysqli_fetch_assoc($row_contest_info);

$query_brewing = sprintf("SELECT * FROM %s WHERE id = '%s'", $prefix."brewing", $id);
$log = mysqli_query($connection,$query_brewing) or die (mysqli_error($connection));
$brewing_info = mysqli_fetch_assoc($log);

$query_brewer_user = sprintf("SELECT * FROM %s WHERE id = '%s'", $prefix."users", $bid);
$user = mysqli_query($connection,$query_brewer_user) or die (mysqli_error($connection));
$row_brewer_user_info = mysqli_fetch_assoc($user);

$query_brewer_organizer = sprintf("SELECT a.brewerFirstName,a.brewerLastName FROM %s a, %s b WHERE a.uid = b.uid AND staff_organizer='1'", $prefix."brewer", $prefix."staff");
$brewer_organizer = mysqli_query($connection,$query_brewer_organizer) or die (mysqli_error($connection));
$row_brewer_organizer = mysqli_fetch_assoc($brewer_organizer); 

$query_logged_in = sprintf("SELECT * FROM %s WHERE user_name = '%s'", $prefix."users", $_SESSION['loginUsername']);
$logged_in_user = mysqli_query($connection,$query_logged_in) or die (mysqli_error($connection));
$row_logged_in_user = mysqli_fetch_assoc($logged_in_user);

$query_brewer = sprintf("SELECT * FROM %s WHERE uid = '%s'", $prefix."brewer", $bid);
$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
$brewer_info = mysqli_fetch_assoc($brewer);
?>