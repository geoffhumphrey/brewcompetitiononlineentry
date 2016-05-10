<?php
$query_tables = "SELECT * FROM $judging_tables_db_table";
if ($go == "judging_locations") $query_tables .= sprintf(" WHERE tableLocation = '%s'", $location);
if ($id != "default") $query_tables .= sprintf(" WHERE id='%s'",$id);
else $query_tables .= " ORDER BY tableNumber";
$tables = mysqli_query($connection,$query_tables) or die (mysqli_error($connection));
$row_tables = mysqli_fetch_assoc($tables);
$totalRows_tables = mysqli_num_rows($tables);
?>