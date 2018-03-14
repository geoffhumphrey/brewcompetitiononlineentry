<?php

/*
Checked Single
2016-06-06
*/

/**
 * Module:      db_tables.inc.php
 * Description: This module houses the conversion scripting for db tables
 *              that are archived.
 */

// echo get_suffix($dbTable);

if (isset($dbTable)) $dbTable = $dbTable;
else $dbTable = "default";

if ($dbTable == "default") {
	$archive_db_table = $prefix."archive";
	$brewer_db_table = $prefix."brewer";
	$brewing_db_table = $prefix."brewing";
	$contacts_db_table = $prefix."contacts";
	$contest_info_db_table = $prefix."contest_info";
	$drop_off_db_table = $prefix."drop_off";
	$judging_assignments_db_table = $prefix."judging_assignments";
	$judging_flights_db_table = $prefix."judging_flights";
	$judging_locations_db_table = $prefix."judging_locations";
	$judging_preferences_db_table = $prefix."judging_preferences";
	$judging_scores_db_table = $prefix."judging_scores";
	$judging_scores_bos_db_table = $prefix."judging_scores_bos";
	$judging_tables_db_table = $prefix."judging_tables";
	$mods_db_table = $prefix."mods";
	$payments_db_table = $prefix."payments";
	$preferences_db_table = $prefix."preferences";
	$special_best_data_db_table = $prefix."special_best_data";
	$special_best_info_db_table = $prefix."special_best_info";
	$sponsors_db_table = $prefix."sponsors";
	$staff_db_table = $prefix."staff";
	$styles_db_table = $prefix."styles";
	$style_types_db_table = $prefix."style_types";
	$system_db_table = $prefix."system";
	$users_db_table = $prefix."users";
	}
else {
	$suffix = rtrim(get_suffix($dbTable), "_"); // HACK - could not isolate code where there's an extra "_"
	$suffix = "_".$suffix;
	$archive_db_table = $prefix."archive";
	$brewer_db_table = $prefix."brewer".$suffix;
	$brewing_db_table = $prefix."brewing".$suffix;
	$contacts_db_table = $prefix."contacts";
	$contest_info_db_table = $prefix."contest_info";
	$drop_off_db_table = $prefix."drop_off";
	$judging_assignments_db_table = $prefix."judging_assignments".$suffix;
	$judging_flights_db_table = $prefix."judging_flights".$suffix;
	$judging_locations_db_table = $prefix."judging_locations";
	$judging_preferences_db_table = $prefix."judging_preferences";
	$judging_scores_db_table = $prefix."judging_scores".$suffix;
	$judging_scores_bos_db_table = $prefix."judging_scores_bos".$suffix;
	$judging_tables_db_table = $prefix."judging_tables".$suffix;
	$mods_db_table = $prefix."mods";
	$payments_db_table = $prefix."payments";
	$preferences_db_table = $prefix."preferences";
	$special_best_data_db_table = $prefix."special_best_data".$suffix;
	$special_best_info_db_table = $prefix."special_best_info".$suffix;
	$sponsors_db_table = $prefix."sponsors";
	$staff_db_table = $prefix."staff";
	$styles_db_table = $prefix."styles";
	$style_types_db_table = $prefix."style_types".$suffix;
	$system_db_table = $prefix."system";
	$users_db_table = $prefix."users".$suffix;
}

$db_table_array = array(
$archive_db_table,
$brewer_db_table,
$brewing_db_table,
$contacts_db_table,
$contest_info_db_table,
$drop_off_db_table,
$judging_assignments_db_table,
$judging_flights_db_table,
$judging_locations_db_table,
$judging_preferences_db_table,
$judging_scores_db_table,
$judging_scores_bos_db_table,
$judging_tables_db_table,
$mods_db_table,
$preferences_db_table,
$special_best_data_db_table,
$special_best_info_db_table,
$sponsors_db_table,
$staff_db_table,
$styles_db_table,
$style_types_db_table,
$system_db_table,
$users_db_table
);
?>