<?php	
$query_stewarding = "SELECT * FROM $judging_locations_db_table";
if ($section == "list") $query_stewarding .= sprintf(" WHERE id='%s'", $row_brewer['brewerStewardLocation']);
if (($section == "brewer") || ($section == "admin") || ($section == "register")) $query_stewarding .= " ORDER BY judgingDate,judgingLocName ASC";
$stewarding = mysqli_query($connection,$query_stewarding) or die (mysqli_error($connection));
$row_stewarding = mysqli_fetch_assoc($stewarding);
$totalRows_stewarding = mysqli_num_rows($stewarding);

$query_stewarding2 = "SELECT * FROM $judging_locations_db_table";
if ($section == "list") $query_stewarding2 .= sprintf(" WHERE id='%s'", $row_brewer['brewerStewardLocation2']);
if (($section == "brewer") || ($section == "admin") || ($section == "register")) $query_stewarding2 .= " ORDER BY judgingDate,judgingLocName ASC";
$stewarding2 = mysqli_query($connection,$query_stewarding2) or die (mysqli_error($connection));
$row_stewarding2 = mysqli_fetch_assoc($stewarding2);
$totalRows_stewarding2 = mysqli_num_rows($stewarding2);

?>