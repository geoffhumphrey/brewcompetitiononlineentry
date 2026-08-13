<?php
if ($id == "default") $id_table = $row_tables['id'];
else $id_table = $id;
$db_conn->where('assignTable', $id_table);
if ($round2 != "default") $db_conn->where('assignRound', $round2);
$db_conn->orderBy('assignRound', 'ASC');
$db_conn->orderBy('assignFlight', 'ASC');
$rows_assignments = $db_conn->get($prefix."judging_assignments");
$row_assignments = ($rows_assignments && count($rows_assignments) > 0) ? $rows_assignments[0] : null;
$totalRows_assignments = $db_conn->count;
?>