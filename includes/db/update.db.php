<?php
mysqli_select_db($connection,$database);

$query_contest_info = sprintf("SELECT * FROM %s WHERE id=1", $prefix."contest_info");
$contest_info = mysqli_query($connection,$query_contest_info) or die (mysqli_error($connection));
$row_contest_info = mysqli_fetch_assoc($contest_info);
 
$query_prefs = sprintf("SELECT * FROM %s WHERE id=1", $prefix."preferences");
$prefs = mysqli_query($connection,$query_prefs) or die (mysqli_error($connection));
$row_prefs = mysqli_fetch_assoc($prefs);

$query_log = sprintf("SELECT * FROM %s", $brewing_db_table);
$log = mysqli_query($connection,$query_log) or die (mysqli_error($connection));
$row_log = mysqli_fetch_assoc($log);
$totalRows_log = mysqli_num_rows($log);

// if "bcoem_sys" db table is present, get installed version from it
if (check_setup($prefix."bcoem_sys",$database)) { 
	$query_version = sprintf("SELECT version,version_date FROM %s",$system_db_table);
	$version = mysqli_query($connection,$query_version) or die (mysqli_error($connection));
	$row_version = mysqli_fetch_assoc($version);	
	$version = $row_version['version'];
	$version_date = strtotime($row_version['version_date']); 
}

// if user session present, get info from db
if (isset($_SESSION['loginUsername'])) {	
	$query_user_level = sprintf("SELECT userLevel FROM %s WHERE user_name='%s'",$users_db_table,$_SESSION['loginUsername']);
	$user_level = mysqli_query($connection,$query_user_level) or die (mysqli_error($connection));
	$row_user_level = mysqli_fetch_assoc($user_level);
	$totalRows_user_level = mysqli_num_rows($user_level);
}

// maintain master hosted user account
if (HOSTED) {
	if ($action == "default") require(INCLUDES.'url_variables.inc.php');
}
	
?>