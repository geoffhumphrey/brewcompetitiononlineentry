<?php

// Redirect if directly accessed without authenticated session
if ((session_status() == PHP_SESSION_NONE) || ((isset($_SESSION['loginUsername'])) && (!function_exists('sterilize')))) {
    $redirect = "../../index.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

ob_start();
session_unset();
session_destroy();
session_write_close();
setcookie(session_name($prefix_session),'',0,'/');

$logout_location = $base_url."index.php?msg=5";
header(sprintf("Location: %s",$logout_location));
?>