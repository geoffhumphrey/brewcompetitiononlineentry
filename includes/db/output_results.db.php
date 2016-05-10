<?php
// Get the winner method
$query_prefs = sprintf("SELECT prefsWinnerMethod FROM %s WHERE id=1", $prefix."preferences");
$prefs = mysqli_query($connection,$query_prefs) or die (mysqli_error($connection));
$row_prefs = mysqli_fetch_assoc($prefs);
?>