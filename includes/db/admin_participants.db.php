<?php
$query_participant_count = sprintf("SELECT COUNT(*) as 'count' FROM %s", $prefix."brewer");
$result_participant_count = mysqli_query($connection,$query_participant_count) or die (mysqli_error($connection));
$row_participant_count = mysqli_fetch_assoc($result_participant_count);
?>