<?php
$query_count_end = "SHOW SESSION STATUS LIKE 'Questions'";
$count_end = mysqli_query($connection,$query_count_end) or die (mysqli_error($connection));
$row_count_end = mysqli_fetch_array($count_end, MYSQLI_ASSOC);
$stop_queries = $row_count_end['Value'];
$queries_total_page = $stop_queries - $start_queries - 1;
if (basename($_SERVER['SCRIPT_FILENAME'], '.php') == "process.inc") $_SESSION['queries_last'] = $queries_total_page;
$_SESSION['queries_total'] = $_SESSION['queries_total'] + $queries_total_page;

$output_query_count = "";
$output_query_count .= "<div class=\"alert alert-warning\">";
$output_query_count .= "<p>Number of database queries for this page load: <strong>".$queries_total_page."</strong></p>";
$output_query_count .= "<p>Number of database queries in last /includes/process.inc.php call: <strong>".$_SESSION['queries_last']."</strong></p>";
$output_query_count .= "<p>Number of database queries since ".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['queries_started'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time")." (session start): <strong>".$_SESSION['queries_total']."</strong></p>";
$output_query_count .= "</div>";

?>