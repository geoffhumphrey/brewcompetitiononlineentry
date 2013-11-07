<?php
ob_start();
require('../paths.php');
require(LIB.'common.lib.php');

if (NHC) $logout_location = "http://www.brewingcompetition.com/index.php?msg=3";
else $logout_location = $base_url."index.php?section=login&action=logout&msg=3";

session_start();
$requested_logout = true;
if ($requested_logout) {
	session_unset();
	session_destroy();
	session_write_close();
	setcookie(session_name(),'',0,'/');
	session_regenerate_id(true);
}

// Now the session_id will be different every browser refresh
// print(session_id());
function session_restart() {
	if (session_name()=='') {
		// Session not started yet
		session_start();
	}
	else {
		// Session was started, so destroy
		session_unset();
		session_destroy();
		session_write_close();
		setcookie(session_name(),'',0,'/');
		session_regenerate_id(true);
	}
}
header (sprintf("Location: %s"),$$logout_location);

?>

