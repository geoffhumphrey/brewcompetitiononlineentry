<?php
function check_setup($tablename, $database) {
	
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	
	$query_log = sprintf("SELECT COUNT(*) AS count FROM information_schema.tables WHERE table_schema = '%s' AND table_name = '%s'", $database, $tablename);
	$log = mysqli_query($connection,$query_log) or die (mysqli_error($connection));
	$row_log = mysqli_fetch_assoc($log);

	if ($row_log['count'] == 0) return FALSE;
	else return TRUE;

}

function check_update($column_name, $table_name) {
	
	require(CONFIG.'config.php');	
	mysqli_select_db($connection,$database);
	
	$query_log = sprintf("SHOW COLUMNS FROM `%s` LIKE '%s'",$table_name,$column_name);
	$log = mysqli_query($connection,$query_log) or die (mysqli_error($connection));
	$row_log_exists = mysqli_num_rows($log);
	
    if ($row_log_exists) return TRUE;
	else return FALSE;
	
}

function check_new_style($style1, $style2, $style3, $mode="none") {

	require(CONFIG.'config.php');

	if ($mode == "ignore_style_num") $query_new_style = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewStyleGroup='%s' AND  brewStyle='%s'", $prefix."styles", $style1, $style3);
	else $query_new_style = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewStyleGroup='%s' AND brewStyleNum = '%s' AND  brewStyle='%s'", $prefix."styles", $style1, $style2, $style3);
	$new_style = mysqli_query($connection,$query_new_style) or die (mysqli_error($connection));
	$row_new_style = mysqli_fetch_assoc($new_style);

	if ($row_new_style['count'] > 0) return TRUE;
	else return FALSE;

}


function check_mysql_data_type($column_name, $table_name) {
	
	require(CONFIG.'config.php');	
	mysqli_select_db($connection,$database);

	$type = 0;
	
	$sql = sprintf("SELECT `%s` FROM `%s` LIMIT 1",$column_name,$table_name);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection, $sql);
	
    if ($result) {
        while ($finfo = mysqli_fetch_field($result)) {
            $type = $finfo->type;
        }
    }

    return $type;
	
}

?>