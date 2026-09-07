<?php
ob_start();
session_unset();
session_destroy();
session_write_close();
setcookie(session_name($prefix_session),'',0,'/');

$logout_location = $base_url."index.php?msg=5";
header(sprintf("Location: %s",$logout_location));
// Without exit(), control returns to includes/process.inc.php's action dispatch chain, which
// falls through to that chain's own tail code and calls header() a second time with an empty,
// never-set $redirect_go_to.
exit();
?>