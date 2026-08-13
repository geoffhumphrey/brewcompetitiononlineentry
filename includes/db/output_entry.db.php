<?php
declare(strict_types=1);
$db_conn->where('id', 1);
$contest_info = $db_conn->getOne($prefix."contest_info");

$db_conn->where('id', $id);
$brewing_info = $db_conn->getOne($prefix."brewing");

$db_conn->where('id', $bid);
$row_brewer_user_info = $db_conn->getOne($prefix."users");

$query_brewer_organizer = "SELECT a.brewerFirstName,a.brewerLastName FROM ".$prefix."brewer a, ".$prefix."staff b WHERE a.uid = b.uid AND staff_organizer='1'";
$row_brewer_organizer = $db_conn->rawQueryOne($query_brewer_organizer);

$db_conn->where('user_name', $_SESSION['loginUsername']);
$row_logged_in_user = $db_conn->getOne($prefix."users");

$db_conn->where('uid', $bid);
$brewer_info = $db_conn->getOne($prefix."brewer");
?>