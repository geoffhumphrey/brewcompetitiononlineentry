<?php
$query_participant_count = "SELECT COUNT(*) as 'count' FROM ".$prefix."brewer";
$params_participant_count = [];
if (SINGLE) { $query_participant_count .= " WHERE FIND_IN_SET(?,brewerCompParticipant) > 0"; $params_participant_count[] = $_SESSION['comp_id']; }
$row_participant_count = ($params_participant_count !== []) ? $db_conn->rawQueryOne($query_participant_count, $params_participant_count) : $db_conn->rawQueryOne($query_participant_count);
?>