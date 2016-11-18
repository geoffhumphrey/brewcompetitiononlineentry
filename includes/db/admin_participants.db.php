<?php
$query_participant_count = sprintf("SELECT COUNT(*) as 'count' FROM %s", $prefix."brewer");
if (SINGLE) $query_participant_count .= sprintf(" WHERE FIND_IN_SET('%s',brewerCompParticipant) > 0)",$_SESSION['comp_id']);
$result_participant_count = mysqli_query($connection,$query_participant_count) or die (mysqli_error($connection));
$row_participant_count = mysqli_fetch_assoc($result_participant_count);
?>