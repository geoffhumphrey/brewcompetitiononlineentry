<?php 
/**
 * Module:      db_tables.inc.php
 * Description: This module houses the conversion scripting for db tables
 *              that are archived.
 */

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
	$themes_db_table = $prefix."themes";
	$users_db_table = $prefix."users";
	}
else {
	$suffix = get_suffix($dbTable);
	$archive_db_table = $prefix."archive".$suffix;
	$brewer_db_table = $prefix."brewer".$suffix;
	$brewing_db_table = $prefix."brewing".$suffix;
	$contacts_db_table = $prefix."contacts".$suffix;
	$contest_info_db_table = $prefix."contest_info".$suffix;
	$countries_db_table = $prefix."countries".$suffix;
	$drop_off_db_table = $prefix."drop_off".$suffix.$suffix;
	$judging_assignments_db_table = $prefix."judging_assignments".$suffix;
	$judging_flights_db_table = $prefix."judging_flights".$suffix;
	$judging_locations_db_table = $prefix."judging_locations".$suffix;
	$judging_preferences_db_table = $prefix."judging_preferences".$suffix;
	$judging_scores_db_table = $prefix."judging_scores".$suffix;
	$judging_scores_bos_db_table = $prefix."judging_scores_bos".$suffix;
	$judging_tables_db_table = $prefix."judging_tables".$suffix;
	$preferences_db_table = $prefix."preferences".$suffix;
	$special_best_data_db_table = $prefix."special_best_data".$suffix;
	$special_best_info_db_table = $prefix."special_best_info".$suffix;
	$sponsors_db_table = $prefix."sponsors".$suffix;
	$styles_db_table = $prefix."styles".$suffix;
	$style_types_db_table = $prefix."style_types".$suffix;
	$themes_db_table = $prefix."themes".$suffix;
	$users_db_table = $prefix."users".$suffix;
	}
?>