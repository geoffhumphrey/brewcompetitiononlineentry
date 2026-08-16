<?php
declare(strict_types=1);

function check_setup(string $tablename, string $database): bool {

	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$db_conn->where('table_schema', $database);
	$db_conn->where('table_name', $tablename);
	$row_log = $db_conn->getOne('information_schema.tables', 'COUNT(*) AS count');

	if ($row_log['count'] == 0) return FALSE;
	else return TRUE;

}

function check_update(string $column_name, string $table_name): bool {

	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	// SHOW statements don't support bound placeholders in MySQL/MariaDB's prepared-statement
	// protocol, so the column name is allow-listed to word characters and spliced directly.
	$column_name_clean = preg_replace("/[^a-zA-Z0-9_]+/", "", $column_name);
	$rows_log = $db_conn->rawQuery("SHOW COLUMNS FROM `".$table_name."` LIKE '".$column_name_clean."'");

    if (count($rows_log) > 0) return TRUE;
	else return FALSE;

}

function check_new_style(string $style1, string $style2, string $style3, $mode="none"): bool {

	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	/*
	if (HOSTED) $styles_db_table = "bcoem_shared_styles";
	else
	*/
	$styles_db_table = $prefix."styles";

	/*
	if (HOSTED) {
		if ($mode == "ignore_style_num") $query_new_style = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewStyleGroup='%s' AND brewStyle='%s' UNION ALL SELECT COUNT(*) as 'count' FROM %s WHERE brewStyleGroup='%s' AND brewStyle='%s'", $styles_db_table, $style1, $style3, $prefix."styles", $style1, $style3);
		else $query_new_style = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewStyleGroup='%s' AND brewStyleNum = '%s' AND  brewStyle='%s' UNION ALL SELECT COUNT(*) as 'count' FROM %s WHERE brewStyleGroup='%s' AND brewStyleNum = '%s' AND  brewStyle='%s'", $styles_db_table, $style1, $style2, $style3, $prefix."styles", $style1, $style2, $style3);
	}
	*/

	$db_conn->where('brewStyleGroup', $style1);
	if ($mode != "ignore_style_num") $db_conn->where('brewStyleNum', $style2);
	$db_conn->where('brewStyle', $style3);
	$row_new_style = $db_conn->getOne($styles_db_table, "COUNT(*) as 'count'");

	if ($row_new_style['count'] > 0) return TRUE;
	else return FALSE;

}


function check_mysql_data_type(string $column_name, string $table_name): int {

	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$type = 0;

	// A column type lookup can't bind `column`/`table` as parameters, so both are
	// allow-listed to word characters before being spliced into the identifier position.
	$column_name_clean = preg_replace("/[^a-zA-Z0-9_]+/", "", $column_name);
	$table_name_clean = preg_replace("/[^a-zA-Z0-9_]+/", "", $table_name);

	$sql = sprintf("SELECT `%s` FROM `%s` LIMIT 1", $column_name_clean, $table_name_clean);
	$result = $db_conn->mysqli()->query($sql);

    if ($result) {
        while ($finfo = $result->fetch_field()) {
            $type = $finfo->type;
        }
    }

    return $type;

}

/**
 * Re-base a single competition timestamp to a true UTC epoch.
 *
 * Used by the 3.1.0 update backfill. Before 3.1.0, competition date fields
 * were stored via bare strtotime() (interpreted in the PHP server's default
 * timezone) rather than the admin's prefsTimeZone. This helper re-interprets
 * a stored epoch as wall time in the admin's timezone and returns the correct
 * UTC epoch, mirroring what to_utc_epoch() does on save (3.1.0+).
 *
 * Values that are empty, zero, not a 10-digit Unix epoch, or the
 * prefsWinnerDelay "never" sentinel (2145916800) are returned unchanged.
 *
 * @param string|int|null $value           Stored value from the DB.
 * @param float           $timezone_offset prefsTimeZone offset (e.g. -5.0).
 * @return string|int|false|null           Normalized UTC epoch, or the
 *                                         original value when it should not
 *                                         (or cannot) be re-based.
 */
function normalize_competition_ts($value, float $timezone_offset) {

	if (($value === null) || ($value === '')) return $value;

	$old = (int) $value;

	// Only re-base genuine 10-digit Unix epochs.
	if (($old <= 0) || (strlen((string) $old) !== 10)) return $value;

	// The "no winner date" sentinel is not a real date.
	if ($old == 2145916800) return $value;

	if (!function_exists('to_utc_epoch')) return $value;

	// Read the stored epoch back as wall time in the admin's timezone.
	// to_utc_epoch() leaves the ambient default timezone set to UTC when it
	// returns, so don't rely on whatever is ambient here — set the source
	// timezone ourselves so every field in a multi-field migration pass is
	// interpreted consistently regardless of call order.
	$tz = get_timezone($timezone_offset);
	date_default_timezone_set($tz);
	$local = date('Y-m-d H:i', $old);
	$new = to_utc_epoch($local, $timezone_offset);

	if (($new === false) || ($new === $old)) return $value;

	return $new;

}

?>