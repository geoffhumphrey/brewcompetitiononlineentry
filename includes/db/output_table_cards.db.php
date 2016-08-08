<?php
if ($id == "default") $id_table = $row_tables['id'];
else $id_table = $id;
$query_assignments = sprintf("SELECT * FROM $judging_assignments_db_table WHERE assignTable='%s'",$id_table);
if ($round2 != "default") $query_assignments .= sprintf(" AND assignRound='%s'", $round2);                        
$query_assignments .= " ORDER BY assignRound,assignFlight ASC";
$assignments = mysqli_query($connection,$query_assignments) or die (mysqli_error($connection));
$row_assignments = mysqli_fetch_assoc($assignments);
$totalRows_assignments = mysqli_num_rows($assignments);
?>