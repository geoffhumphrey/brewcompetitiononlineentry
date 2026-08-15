<?php
// Get the winner method
$db_conn->where('id', 1);
$row_prefs = $db_conn->getOne($prefix."preferences", "prefsWinnerMethod");
?>