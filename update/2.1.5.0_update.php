<?php

if (!function_exists('check_update')) {
	$redirect = "../../403.php";
	$redirect_go_to = sprintf("Location: %s", $redirect);
	header($redirect_go_to);
	exit();
}
 
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
	$result = $db_conn->rawQuery($updateSQL);
	if ($db_conn->getLastErrno() !== 0) $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>";
}

if (!check_update("prefsSpecific", $prefix."preferences")) {
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `prefsSpecific` TINYINT(1) NULL;",$prefix."preferences");
	$result = $db_conn->rawQuery($updateSQL);
	if ($db_conn->getLastErrno() !== 0) $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>";
}

// Sanity Check - see if 2.1.0 column names are present - if not, add
if (!check_update("prefsEntryLimitPaid", $prefix."preferences")) {
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `prefsEntryLimitPaid` INT(4) NULL DEFAULT NULL;",$prefix."preferences");
	$result = $db_conn->rawQuery($updateSQL);
	if ($db_conn->getLastErrno() !== 0) $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>";
}

if (!check_update("prefsEmailRegConfirm", $prefix."preferences")) {
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `prefsEmailRegConfirm` TINYINT(1) NULL DEFAULT NULL;",$prefix."preferences");
	$result = $db_conn->rawQuery($updateSQL);
	if ($db_conn->getLastErrno() !== 0) $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>";
}

// -----------------------------------------------------------
// Alter Table: judging_preferences
// -----------------------------------------------------------

if (!check_update("jPrefsCapJudges", $prefix."judging_preferences")) {
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `jPrefsCapJudges` INT(3) NULL DEFAULT NULL;", $prefix."judging_preferences");
	$result = $db_conn->rawQuery($updateSQL);
	if ($db_conn->getLastErrno() !== 0) $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>";
}

if (!check_update("jPrefsCapStewards", $prefix."judging_preferences")) {
	$updateSQL = sprintf(" ALTER TABLE `%s` ADD `jPrefsCapStewards` INT(3) NULL DEFAULT NULL;",	$prefix."judging_preferences");
	$result = $db_conn->rawQuery($updateSQL);
	if ($db_conn->getLastErrno() !== 0) $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>";
}

if (!check_update("jPrefsBottleNum", $prefix."judging_preferences")) {
	$updateSQL = sprintf(" ALTER TABLE `%s` ADD `jPrefsBottleNum` INT(3) NULL DEFAULT NULL;",$prefix."judging_preferences");
	$result = $db_conn->rawQuery($updateSQL);
	if ($db_conn->getLastErrno() !== 0) $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>";
}

// -----------------------------------------------------------
// Alter Table: contest_info
// Sanity Check - see if 2.1.0 column names are present - if not, add
// -----------------------------------------------------------

if (!check_update("contestCheckInPassword", $prefix."contest_info")) {
	$updateSQL= sprintf("ALTER TABLE  `%s` ADD `contestCheckInPassword` VARCHAR(255) NULL CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;",$prefix."contest_info");
	$result = $db_conn->rawQuery($updateSQL);
	if ($db_conn->getLastErrno() !== 0) $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>";
}

// -----------------------------------------------------------
// Alter Table: styles
// Sanity Check - see if 2.1.0 column names are present - if not, add
// -----------------------------------------------------------

if (!check_update("brewStyleEntry", $prefix."styles")) {
	$updateSQL= sprintf("ALTER TABLE  `%s` ADD `brewStyleEntry` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;",$prefix."styles");
	$result = $db_conn->rawQuery($updateSQL);
	if ($db_conn->getLastErrno() !== 0) $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>";
}

if (!check_update("brewStyleComEx", $prefix."styles")) {
	$updateSQL= sprintf("ALTER TABLE  `%s` ADD `brewStyleComEx` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;",$prefix."styles");
	$result = $db_conn->rawQuery($updateSQL);
	if ($db_conn->getLastErrno() !== 0) $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>";
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
$res = $db_conn->rawQuery("SHOW TABLES");

$output .= MysqlError($connection);

foreach ($res as $res_row) {
	$row = array_values($res_row);
	if (!empty($prefix)) {
		if (strpos($row[0], $prefix) !== false) $tabs[] = $row[0];
	} else $tabs[] = $row[0];
}

if (!empty($tabs)) {

	// Convert tables

	foreach ($tabs as $tab) {
		$res = $db_conn->rawQuery("show index from {$tab}");
		$output .= MysqlError($connection);
		$indicies = array();
		$count = array();

		foreach ($res as $res_row) {
			$row = array_values($res_row);

			if ($row[2] != "PRIMARY") {

				$indicies[] = array("name" => $row[2], "unique" => !($row[1] == "1"), "col" => $row[4]);
				$db_conn->rawQuery("ALTER TABLE {$tab} DROP INDEX {$row[2]}");
				$output .= MysqlError($connection);
				$output .= "<li>Dropped index {$row[2]}. Unique: {$row[1]}</li>";
				$count[] = 1;

			}

			else $count[] = 0;

		}


		$res = $db_conn->rawQuery("DESCRIBE {$tab}");
		$output .= MysqlError($connection);

		foreach ($res as $res_row) {
			$row = array_values($res_row);

			$name = $row[0];
			$type = $row[1];
			$set = false;

			if (preg_match("/^varchar\((\d+)\)$/i", $type, $mat)) {

				$size = $mat[1];
				$db_conn->rawQuery("ALTER TABLE {$tab} MODIFY {$name} VARBINARY({$size})");
				$output .= MysqlError($connection);

				$db_conn->rawQuery("ALTER TABLE {$tab} MODIFY {$name} VARCHAR({$size}) CHARACTER SET {$target_charset}");
				$output .= MysqlError($connection);

				$set = TRUE;
				$output .= "<li>Altered field {$name} on {$tab} to type {$type} {$target_collate}.</li>";
				$count[] = 1;

			}

			elseif (preg_match("/^char\((\d+)\)$/i", $type, $mat)) {

				$size = $mat[1];
				$db_conn->rawQuery("ALTER TABLE {$tab} MODIFY {$name} CHAR({$size}) CHARACTER SET {$target_charset}");
				$output .= MysqlError($connection);

				$set = TRUE;
				$output .= "<li>Altered field {$name} on {$tab} to type {$type} {$target_collate}.</li>";
				$count[] = 1;

			}

			elseif (!strcasecmp($type, "CHAR")) {

				$db_conn->rawQuery("ALTER TABLE {$tab} MODIFY {$name} BINARY(1)");
				$output .= MysqlError($connection);

				$db_conn->rawQuery("ALTER TABLE {$tab} MODIFY {$name} VARCHAR(1) CHARACTER SET {$target_charset}");
				$output .= MysqlError($connection);

				$db_conn->rawQuery("ALTER TABLE {$tab} MODIFY {$name} CHAR(1) CHARACTER SET {$target_charset}");
				$output .= MysqlError($connection);

				$set = TRUE;
				$output .= "<li>Altered field {$name} on {$tab} to type {$type} {$target_collate}.</li>";
				$count[] = 1;

			}

			elseif (!strcasecmp($type, "TINYTEXT"))	{

				$db_conn->rawQuery("ALTER TABLE {$tab} MODIFY {$name} TINYBLOB");
				$output .= MysqlError($connection);

				$db_conn->rawQuery("ALTER TABLE {$tab} MODIFY {$name} TINYTEXT CHARACTER SET {$target_charset}");
				$output .= MysqlError($connection);

				$set = TRUE;
				$output .= "<li>Altered field {$name} on {$tab} to type {$type} {$target_collate}.</li>";
				$count[] = 1;

			}

			elseif (!strcasecmp($type, "MEDIUMTEXT")) {

				$db_conn->rawQuery("ALTER TABLE {$tab} MODIFY {$name} MEDIUMBLOB");
				$output .= MysqlError($connection);

				$db_conn->rawQuery("ALTER TABLE {$tab} MODIFY {$name} MEDIUMTEXT CHARACTER SET {$target_charset}");
				$output .= MysqlError($connection);

				$set = TRUE;
				$output .= "<li>Altered field {$name} on {$tab} to type {$type} {$target_collate}.</li>";
				$count[] = 1;

			}

			elseif (!strcasecmp($type, "LONGTEXT")) {

				$db_conn->rawQuery("ALTER TABLE {$tab} MODIFY {$name} LONGBLOB");
				$output .= MysqlError($connection);

				$db_conn->rawQuery("ALTER TABLE {$tab} MODIFY {$name} LONGTEXT CHARACTER SET {$target_charset}");
				$output .= MysqlError($connection);

				$set = TRUE;
				$output .= "<li>Altered field {$name} on {$tab} to type {$type} {$target_collate}.</li>";
				$count[] = 1;
			}

			else if (!strcasecmp($type, "TEXT")) {

				$db_conn->rawQuery("ALTER TABLE {$tab} MODIFY {$name} BLOB");
				$output .= MysqlError($connection);

				$db_conn->rawQuery("ALTER TABLE {$tab} MODIFY {$name} TEXT CHARACTER SET {$target_charset}");
				$output .= MysqlError($connection);

				$set = TRUE;
				$output .= "<li>Altered field {$name} on {$tab} to type {$type} {$target_collate}.</li>";
				$count[] = 1;

			}

			else $count[] = 0;

			if ($set) {

				$db_conn->rawQuery("ALTER TABLE {$tab} MODIFY {$name} COLLATE {$target_collate}");
				$output .= MysqlError($connection);
				$count[] = 1;

			}

			else $count[] = 0;
		}

		// Re-build indicies...
		foreach ($indicies as $index) {

			if ($index["unique"]) {

				$db_conn->rawQuery("CREATE UNIQUE INDEX {$index["name"]} ON {$tab} ({$index["col"]})");
				$output .= MysqlError($connection);
				$count[] = 1;

			}

			else {

				$db_conn->rawQuery("CREATE INDEX {$index["name"]} ON {$tab} ({$index["col"]})");
				$output .= MysqlError($connection);
				$count[] = 1;

			}

			$output .= "<li>Created index {$index["name"]} on {$tab}. Unique: {$index["unique"]}</li>";
			$count[] = 1;
		}

		// set default collate
		$db_conn->rawQuery("ALTER TABLE {$tab}  DEFAULT CHARACTER SET {$target_charset} COLLATE {$target_collate}");
		$output .= MysqlError($connection);
		$count[] = 1;
	}

	// set database charset
	$db_conn->rawQuery("ALTER DATABASE {$database} DEFAULT CHARACTER SET {$target_charset} COLLATE {$target_collate}");
	$output .= MysqlError($connection);
	$count[] = 1;

}

// -----------------------------------------------------------
// Data Update: preferences
// -----------------------------------------------------------

$block_ok = TRUE;

$updateSQL = sprintf("UPDATE %s SET prefsSpecific = '1';",$prefix."preferences");
$result = $db_conn->rawQuery($updateSQL);
if ($db_conn->getLastErrno() !== 0) { $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>"; $block_ok = FALSE; }

$updateSQL = sprintf("UPDATE %s SET prefsLanguage = '%s';",$prefix."preferences","English");
$result = $db_conn->rawQuery($updateSQL);
if ($db_conn->getLastErrno() !== 0) { $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>"; $block_ok = FALSE; }

if ($block_ok) $output .= "<li>Preferences data updated.</li>";

$updateSQL = sprintf("UPDATE %s SET brewStyle = '%s' WHERE id = %s",$prefix."styles","Czech Premium Pale Lager","107");
$result = $db_conn->rawQuery($updateSQL);

if ($db_conn->getLastErrno() === 0) $output .= "<li>Style data updated.</li>";
else $output .= "<li class=\"text-danger\">Error: Style data NOT updated. ".$db_conn->getLastError()."</li>";

// -----------------------------------------------------------
// Data Update: Update Version in System Table
// -----------------------------------------------------------

$updateSQL = sprintf("UPDATE %s SET version='%s', version_date='%s' WHERE id='1'",$system_db_table,"2.1.5.0","2016-08-31");
$result = $db_conn->rawQuery($updateSQL);

if ($db_conn->getLastErrno() === 0) $output .= "<li>Version updated in system table.</li>";
else $output .= "<li class=\"text-danger\">Error: Version NOT updated in system table. ".$db_conn->getLastError()."</li>";

$output .= "</ul>";
?>