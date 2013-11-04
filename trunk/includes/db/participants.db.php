<?php
$query_participant_count = sprintf("SELECT COUNT(*) as 'count' FROM %s", $prefix."brewer");
$result_participant_count = mysql_query($query_participant_count, $brewing) or die(mysql_error());
$row_participant_count = mysql_fetch_assoc($result_participant_count);


?>