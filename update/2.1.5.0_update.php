<?php
// -----------------------------------------------------------
// Update
// Version 2.1.5.0
// -----------------------------------------------------------

$output .= "<h4>Version 2.1.5</h4>";
$output .= "<ul>";

// -----------------------------------------------------------
// Alter Table: preferences
// Future proofing for translations
// -----------------------------------------------------------

if (!check_update("prefsLanguage", $prefix."preferences")) {
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `prefsLanguage` VARCHAR(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
}

if (!check_update("prefsSpecific", $prefix."preferences")) {
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `prefsSpecific` TINYINT(1) NULL;",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
}

// Sanity Check - see if 2.1.0 column names are present - if not, add
if (!check_update("prefsEntryLimitPaid", $prefix."preferences")) {
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `prefsEntryLimitPaid` INT(4) NULL DEFAULT NULL;",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
}

if (!check_update("prefsEmailRegConfirm", $prefix."preferences")) {
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `prefsEmailRegConfirm` TINYINT(1) NULL DEFAULT NULL;",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
}

// -----------------------------------------------------------
// Alter Table: judging_preferences
// -----------------------------------------------------------

if (!check_update("jPrefsCapJudges", $prefix."judging_preferences")) {
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `jPrefsCapJudges` INT(3) NULL DEFAULT NULL;", $prefix."judging_preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
}

if (!check_update("jPrefsCapStewards", $prefix."judging_preferences")) {
	$updateSQL = sprintf(" ALTER TABLE `%s` ADD `jPrefsCapStewards` INT(3) NULL DEFAULT NULL;",	$prefix."judging_preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
}

if (!check_update("jPrefsBottleNum", $prefix."judging_preferences")) {
	$updateSQL = sprintf(" ALTER TABLE `%s` ADD `jPrefsBottleNum` INT(3) NULL DEFAULT NULL;",$prefix."judging_preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
}

// -----------------------------------------------------------
// Alter Table: contest_info
// Sanity Check - see if 2.1.0 column names are present - if not, add
// -----------------------------------------------------------

if (!check_update("contestCheckInPassword", $prefix."contest_info")) {
	$updateSQL= sprintf("ALTER TABLE  `%s` ADD `contestCheckInPassword` VARCHAR(255) NULL CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;",$prefix."contest_info");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
}

// -----------------------------------------------------------
// Alter Table: styles
// Sanity Check - see if 2.1.0 column names are present - if not, add
// -----------------------------------------------------------

if (!check_update("brewStyleEntry", $prefix."styles")) {
	$updateSQL= sprintf("ALTER TABLE  `%s` ADD `brewStyleEntry` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;",$prefix."styles");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
}

if (!check_update("brewStyleComEx", $prefix."styles")) {
	$updateSQL= sprintf("ALTER TABLE  `%s` ADD `brewStyleComEx` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;",$prefix."styles");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
}


// -----------------------------------------------------------
// Alter Tables: ALL
// Function to convert all tables and text fields to UTF8
// For future internationalization and translations effort
// -----------------------------------------------------------

$target_charset = "utf8";
$target_collate = "utf8_general_ci";

function MysqlError($connection) {
	if (mysqli_errno($connection)) {
		return "<li>MySQL Error: " . mysqli_error($connection) . "</li>";
	}
}

$count = array();
$tabs = array();
$res = mysqli_query($connection,"SHOW TABLES");

$output .= MysqlError($connection);

while (($row = mysqli_fetch_row($res)) != null) {
	if (!empty($prefix)) {
		if (strpos($row[0], $prefix) !== false) $tabs[] = $row[0];
	} else $tabs[] = $row[0];
}

if (!empty($tabs)) {

	// Convert tables

	foreach ($tabs as $tab) {
		$res = mysqli_query($connection,"show index from {$tab}");
		$output .= MysqlError($connection);
		$indicies = array();
		$count = array();

		while (($row = mysqli_fetch_array($res)) != null) {

			if ($row[2] != "PRIMARY") {

				$indicies[] = array("name" => $row[2], "unique" => !($row[1] == "1"), "col" => $row[4]);
				mysqli_query($connection,"ALTER TABLE {$tab} DROP INDEX {$row[2]}");
				$output .= MysqlError($connection);
				$output .= "<li>Dropped index {$row[2]}. Unique: {$row[1]}</li>";
				$count[] = 1;

			}

			else $count[] = 0;

		}


		$res = mysqli_query($connection,"DESCRIBE {$tab}");
		$output .= MysqlError($connection);

		while (($row = mysqli_fetch_array($res)) != null) {

			$name = $row[0];
			$type = $row[1];
			$set = false;

			if (preg_match("/^varchar\((\d+)\)$/i", $type, $mat)) {

				$size = $mat[1];
				mysqli_query($connection,"ALTER TABLE {$tab} MODIFY {$name} VARBINARY({$size})");
				$output .= MysqlError($connection);

				mysqli_query($connection,"ALTER TABLE {$tab} MODIFY {$name} VARCHAR({$size}) CHARACTER SET {$target_charset}");
				$output .= MysqlError($connection);

				$set = TRUE;
				$output .= "<li>Altered field {$name} on {$tab} to type {$type} {$target_collate}.</li>";
				$count[] = 1;

			}

			elseif (preg_match("/^char\((\d+)\)$/i", $type, $mat)) {

				$size = $mat[1];
				mysqli_query($connection,"ALTER TABLE {$tab} MODIFY {$name} CHAR({$size}) CHARACTER SET {$target_charset}");
				$output .= MysqlError($connection);

				$set = TRUE;
				$output .= "<li>Altered field {$name} on {$tab} to type {$type} {$target_collate}.</li>";
				$count[] = 1;

			}

			elseif (!strcasecmp($type, "CHAR")) {

				mysqli_query($connection,"ALTER TABLE {$tab} MODIFY {$name} BINARY(1)");
				$output .= MysqlError($connection);

				mysqli_query($connection,"ALTER TABLE {$tab} MODIFY {$name} VARCHAR(1) CHARACTER SET {$target_charset}");
				$output .= MysqlError($connection);

				mysqli_query($connection,"ALTER TABLE {$tab} MODIFY {$name} CHAR(1) CHARACTER SET {$target_charset}");
				$output .= MysqlError($connection);

				$set = TRUE;
				$output .= "<li>Altered field {$name} on {$tab} to type {$type} {$target_collate}.</li>";
				$count[] = 1;

			}

			elseif (!strcasecmp($type, "TINYTEXT"))	{

				mysqli_query($connection,"ALTER TABLE {$tab} MODIFY {$name} TINYBLOB");
				$output .= MysqlError($connection);

				mysqli_query($connection,"ALTER TABLE {$tab} MODIFY {$name} TINYTEXT CHARACTER SET {$target_charset}");
				$output .= MysqlError($connection);

				$set = TRUE;
				$output .= "<li>Altered field {$name} on {$tab} to type {$type} {$target_collate}.</li>";
				$count[] = 1;

			}

			elseif (!strcasecmp($type, "MEDIUMTEXT")) {

				mysqli_query($connection,"ALTER TABLE {$tab} MODIFY {$name} MEDIUMBLOB");
				$output .= MysqlError($connection);

				mysqli_query($connection,"ALTER TABLE {$tab} MODIFY {$name} MEDIUMTEXT CHARACTER SET {$target_charset}");
				$output .= MysqlError($connection);

				$set = TRUE;
				$output .= "<li>Altered field {$name} on {$tab} to type {$type} {$target_collate}.</li>";
				$count[] = 1;

			}

			elseif (!strcasecmp($type, "LONGTEXT")) {

				mysqli_query($connection,"ALTER TABLE {$tab} MODIFY {$name} LONGBLOB");
				$output .= MysqlError($connection);

				mysqli_query($connection,"ALTER TABLE {$tab} MODIFY {$name} LONGTEXT CHARACTER SET {$target_charset}");
				$output .= MysqlError($connection);

				$set = TRUE;
				$output .= "<li>Altered field {$name} on {$tab} to type {$type} {$target_collate}.</li>";
				$count[] = 1;
			}

			else if (!strcasecmp($type, "TEXT")) {

				mysqli_query($connection,"ALTER TABLE {$tab} MODIFY {$name} BLOB");
				$output .= MysqlError($connection);

				mysqli_query($connection,"ALTER TABLE {$tab} MODIFY {$name} TEXT CHARACTER SET {$target_charset}");
				$output .= MysqlError($connection);

				$set = TRUE;
				$output .= "<li>Altered field {$name} on {$tab} to type {$type} {$target_collate}.</li>";
				$count[] = 1;

			}

			else $count[] = 0;

			if ($set) {

				mysqli_query($connection,"ALTER TABLE {$tab} MODIFY {$name} COLLATE {$target_collate}");
				$count[] = 1;

			}

			else $count[] = 0;
		}

		// Re-build indicies...
		foreach ($indicies as $index) {

			if ($index["unique"]) {

				mysqli_query($connection,"CREATE UNIQUE INDEX {$index["name"]} ON {$tab} ({$index["col"]})");
				$output .= MysqlError($connection);
				$count[] = 1;

			}

			else {

				mysqli_query($connection,"CREATE INDEX {$index["name"]} ON {$tab} ({$index["col"]})");
				$output .= MysqlError($connection);
				$count[] = 1;

			}

			$output .= "<li>Created index {$index["name"]} on {$tab}. Unique: {$index["unique"]}</li>";
			$count[] = 1;
		}

		// set default collate
		mysqli_query($connection,"ALTER TABLE {$tab}  DEFAULT CHARACTER SET {$target_charset} COLLATE {$target_collate}");
		$count[] = 1;
	}

	// set database charset
	mysqli_query($connection,"ALTER DATABASE {$database} DEFAULT CHARACTER SET {$target_charset} COLLATE {$target_collate}");
	$count[] = 1;

}

// -----------------------------------------------------------
// Data Update: preferences
// -----------------------------------------------------------

$updateSQL = sprintf("UPDATE %s SET prefsSpecific = '1';",$prefix."preferences");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$updateSQL = sprintf("UPDATE %s SET prefsLanguage = '%s';",$prefix."preferences","English");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$output .= "<li>Preferences data updated.</li>";

$updateSQL = sprintf("UPDATE %s SET brewStyle = '%s' WHERE id = %s",$prefix."styles","Czech Premium Pale Lager","107");
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$output .= "<li>Style data updated.</li>";

// -----------------------------------------------------------
// Data Update: Update Version in System Table
// -----------------------------------------------------------

$updateSQL = sprintf("UPDATE %s SET version='%s', version_date='%s' WHERE id='1'",$prefix."system","2.1.5.0","2016-08-31");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$output .= "<li>Version updated in system table.</li>";

$output .= "</ul>";
?>