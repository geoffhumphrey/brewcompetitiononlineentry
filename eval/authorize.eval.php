<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);

require_once ('../paths.php');
require_once (CONFIG.'bootstrap.php');
include (LIB.'admin.lib.php');

if ($logged_in) {
	if (validate($base_url,$_SESSION['prefsEvalLicKey'])) echo "Authorized.";
	else echo "Not Authorized.";
}

else echo "No touchy!";

?>