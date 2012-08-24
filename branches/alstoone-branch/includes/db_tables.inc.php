<?php 
/**
 * Module:      db_tables.inc.php
 * Description: This module houses the conversion scripting for db tables
 *              that are archived.
 */

// echo get_suffix($dbTable);

if ($dbTable == "default") { 
	$archive_db_table = $prefix."archive";
	$brewer_db_table = $prefix."brewer";
	$brewing_db_table = $prefix."brewing";
	$contacts_db_table = $prefix."contacts";
	$contest_info_db_table = $prefix."contest_info";
	$countries_db_table = $prefix."countries";
	$drop_off_db_table = $prefix."drop_off";
	$judging_assignments_db_table = $prefix."judging_assignments";
	$judging_flights_db_table = $prefix."judging_flights";
	$judging_locations_db_table = $prefix."judging_locations";
	$judging_preferences_db_table = $prefix."judging_preferences";
	$judging_scores_db_table = $prefix."judging_scores";
	$judging_scores_bos_db_table = $prefix."judging_scores_bos";
	$judging_tables_db_table = $prefix."judging_tables";
	$preferences_db_table = $prefix."preferences";
	$special_best_data_db_table = $prefix."special_best_data";
	$special_best_info_db_table = $prefix."special_best_info";
	$sponsors_db_table = $prefix."sponsors";
	$styles_db_table = $prefix."styles";
	$style_types_db_table = $prefix."style_types";
	$system_db_table = $prefix."system";
	$themes_db_table = $prefix."themes";
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
	$countries_db_table = $prefix."countries";
	$drop_off_db_table = $prefix."drop_off";
	$judging_assignments_db_table = $prefix."judging_assignments".$suffix;
	$judging_flights_db_table = $prefix."judging_flights".$suffix;
	$judging_locations_db_table = $prefix."judging_locations";
	$judging_preferences_db_table = $prefix."judging_preferences";
	$judging_scores_db_table = $prefix."judging_scores".$suffix;
	$judging_scores_bos_db_table = $prefix."judging_scores_bos".$suffix;
	$judging_tables_db_table = $prefix."judging_tables".$suffix;
	$preferences_db_table = $prefix."preferences";
	$special_best_data_db_table = $prefix."special_best_data".$suffix;
	$special_best_info_db_table = $prefix."special_best_info".$suffix;
	$sponsors_db_table = $prefix."sponsors";
	$styles_db_table = $prefix."styles";
	$style_types_db_table = $prefix."style_types".$suffix;
	$system_db_table = $prefix."system";
	$themes_db_table = $prefix."themes";
	$users_db_table = $prefix."users".$suffix;
	}
?>