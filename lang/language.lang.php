<?php

/*
---------------------------------------------
TODO - convert the following for translation:
---------------------------------------------
- All Admin screens and functions
- All update screens and functions
- All setup screens and functions
- Conversion scripts for brewStyleEntry field (Entry Requirements)
  -- Be sure to also update function in common.lib.php
*/

// Load public pages language file
include (LANG.$_SESSION['prefsLanguageFolder'].DIRECTORY_SEPARATOR.$_SESSION['prefsLanguage'].'.lang.php');
//include (LANG.'en'.DIRECTORY_SEPARATOR.'en-US.lang.php');

// Load admin pages language file
// A future version will have full conversions for Admin, Update, and Setup
include (LANG.$_SESSION['prefsLanguageFolder'].DIRECTORY_SEPARATOR.$_SESSION['prefsLanguage'].'_admin.lang.php');
?>