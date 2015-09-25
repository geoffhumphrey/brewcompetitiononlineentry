<?php
require('../paths.php');
require(INCLUDES.'url_variables.inc.php');
require(LIB.'common.lib.php');

// For manual purging of unconfirmed and/or entries that require special ingredients that do not have special ingredient data

if ($action == "purge") {
	purge_entries("unconfirmed", 0);
	purge_entries("special", 0); 
	header(sprintf("Location: %s", $base_url."index.php?section=admin&go=entries&purge=purge"));
}

// For manual triggering of data integrity check

if ($action == "cleanup") {
	data_integrity_check();
	header(sprintf("Location: %s", $base_url."index.php?section=admin&purge=cleanup"));
}

?>