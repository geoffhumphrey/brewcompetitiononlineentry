<?php 
//error_reporting(0);	// comment out to debug
error_reporting(E_ALL); // uncomment to debug 

if (NHC) $base_url = "../";
else $base_url = $base_url;

require(INCLUDES.'functions.inc.php');
require(INCLUDES.'authentication_nav.inc.php');  session_start(); 
require(INCLUDES.'url_variables.inc.php');
require(INCLUDES.'db_tables.inc.php');
require(DB.'common.db.php');
require(DB.'brewer.db.php');
require(DB.'entries.db.php');
require(DB.'admin_common.db.php');
require(INCLUDES.'headers.inc.php');
require(INCLUDES.'constants.inc.php');
include(INCLUDES.'scrubber.inc.php');

?>