<?php
// Record the time all queries started in a session variable
if (!isset($_SESSION['queries_started'])) $_SESSION['queries_started'] = time();
if (!isset($_SESSION['queries_total'])) $_SESSION['queries_total'] = 0;
if (!isset($_SESSION['queries_last'])) $_SESSION['queries_last'] = 0;

$query_count_begin = "SHOW SESSION STATUS LIKE 'Questions'";
$count_begin = mysqli_query($connection,$query_count_begin) or die (mysqli_error($connection));
$row_count_begin = mysqli_fetch_array($count_begin, MYSQLI_ASSOC);
$start_queries = $row_count_begin['Value'];
?>