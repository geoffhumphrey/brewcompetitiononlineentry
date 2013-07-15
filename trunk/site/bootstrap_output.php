<?php 
//error_reporting(0);	// comment out to debug
error_reporting(E_ALL); // uncomment to debug 

if (NHC) $base_url = "../";
else $base_url = $base_url;

require(INCLUDES.'functions.inc.php');
require(INCLUDES.'url_variables.inc.php');
require(INCLUDES.'db_tables.inc.php');
require(DB.'common.db.php');
require(DB.'admin_common.db.php');
include(INCLUDES.'version.inc.php');
include(INCLUDES.'headers.inc.php');
include(INCLUDES.'scrubber.inc.php');
include(INCLUDES.'constants.inc.php');

?>