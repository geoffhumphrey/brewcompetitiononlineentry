<?php
$logout_location = $base_url."/index.php?section=login&action=logout&msg=3";

session_start();
$requested_logout = true;
if ($requested_logout) {
session_restart();
}

// Now the session_id will be different every browser refresh
print(session_id());
function session_restart()
{
if (session_name()=='') {
// Session not started yet
session_start();
}
else {

// Session was started, so destroy
session_destroy();
}
}
header("Location: $logout_location");

?>

