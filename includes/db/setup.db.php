<?php
// Check to see if initial setup has taken place
if (table_exists($prefix."bcoem_sys")) {

	$row_system = $db_conn->getOne($prefix."bcoem_sys", "setup");
	if ($row_system['setup'] == 1) header (sprintf("Location: %s",$base_url."index.php"));

}

if ($section == "step4") {
	$db_conn->where('id', 1);
	$row_prefs = $db_conn->getOne($prefix."preferences");
}
?>