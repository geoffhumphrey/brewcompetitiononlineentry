<?php
declare(strict_types=1);
$db_conn->where('flightTable', $row_tables['id']);
$db_conn->where('flightRound', $round);
$row_table_round = $db_conn->getOne($prefix."judging_flights", "COUNT(*) as count");
?>