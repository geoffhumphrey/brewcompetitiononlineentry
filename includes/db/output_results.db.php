<?php
// Get the winner method
$query_prefs = sprintf("SELECT prefsWinnerMethod FROM %s WHERE id=1", $prefix."preferences");
$prefs = mysql_query($query_prefs, $brewing) or die(mysql_error());
$row_prefs = mysql_fetch_assoc($prefs);
?>