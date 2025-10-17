<?php
require('paths.php');
require(CONFIG.'bootstrap.php');
$redirect = $base_url."index.php?section=500";
$redirect = prep_redirect_link($redirect);
$redirect_go_to = sprintf("Location: %s", $redirect);
header($redirect_go_to);
exit();
?>