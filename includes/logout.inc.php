<?php

/*
Checked Single
2016-06-06
*/

ob_start();
require('../paths.php');

if (NHC) $logout_location = "http://www.brewingcompetition.com/index.php?msg=5";
else $logout_location = $base_url."index.php?msg=5";

session_unset();
session_destroy();
session_write_close();
setcookie(session_name($prefix_session),'',0,'/');
session_regenerate_id(true);

header(sprintf("Location: %s",$logout_location));
?>