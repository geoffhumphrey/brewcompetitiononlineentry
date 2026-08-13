<?php
declare(strict_types=1);
$output_query_count = "";
if (isset($_SESSION['userLevel'])) {
	if ($_SESSION['userLevel'] == 0) {
		// Record the time all queries started in a session variable
		if (!isset($_SESSION['queries_started'])) $_SESSION['queries_started'] = time();
		if (!isset($_SESSION['queries_total'])) $_SESSION['queries_total'] = 0;
		if (!isset($_SESSION['queries_last'])) $_SESSION['queries_last'] = 0;

		$row_count_begin = $db_conn->rawQuery("SHOW SESSION STATUS LIKE 'Questions'")[0];
		$start_queries = $row_count_begin['Value'];
	}
}
?>