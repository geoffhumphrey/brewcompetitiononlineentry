<?php
ob_start();
session_unset();
session_destroy();
session_write_close();
setcookie(session_name($prefix_session),'',0,'/');

$logout_location = $base_url."index.php?msg=5";
header(sprintf("Location: %s",$logout_location));
?>