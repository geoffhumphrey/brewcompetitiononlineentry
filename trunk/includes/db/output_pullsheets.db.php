<?php
$query_tables = "SELECT * FROM $judging_tables_db_table";
if ($go == "judging_locations") $query_tables .= sprintf(" WHERE tableLocation = '%s'", $location);
if ($id != "default") $query_tables .= sprintf(" WHERE id='%s'",$id);
else $query_tables .= " ORDER BY tableNumber";
$tables = mysql_query($query_tables, $brewing) or die(mysql_error());
$row_tables = mysql_fetch_assoc($tables);
$totalRows_tables = mysql_num_rows($tables);
?>