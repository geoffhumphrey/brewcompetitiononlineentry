<?php 
/**
 * Module:      db_tables.inc.php
 * Description: This module houses the conversion scripting for db tables
 *              that are archived.
 */

if ($dbTable == "default") {
	$tables_db_table = "judging_tables";
	$flights_db_table = "judging_flights";
	$scores_db_table = "judging_scores";
	$scores_bos_db_table = "judging_scores_bos";
	$style_type_db_table = "style_types";
	$brewing_db_table = "brewing";
	$brewer_db_table = "brewer";
	$users_db_table = "users";
}
else {
	$suffix = get_suffix($dbTable);
	$tables_db_table = "judging_tables_".$suffix;
	$flights_db_table = "judging_flights_".$suffix;
	$scores_db_table = "judging_scores_".$suffix;
	$style_type_db_table = "style_types_".$suffix;
	$scores_bos_db_table = "judging_scores_bos_".$suffix;
	$brewing_db_table = "brewing_".$suffix;
	$brewer_db_table = "brewer_".$suffix;
	$users_db_table = "users_".$suffix;
}
?>