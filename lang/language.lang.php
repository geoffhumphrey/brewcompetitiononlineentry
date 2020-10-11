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

// Default to English language if prefs not defined
$prefsLanguage = "en-US";
$prefsLanguageFolder = "en";
if (isset($_SESSION['prefsLanguage'])) $prefsLanguage = $_SESSION['prefsLanguage'];
if (isset($_SESSION['prefsLanguageFolder'])) $prefsLanguageFolder = $_SESSION['prefsLanguageFolder'];

// Load public pages language file
include (LANG.$prefsLanguageFolder.DIRECTORY_SEPARATOR.$prefsLanguage.'.lang.php');

// Load admin pages language file
// A future version will have full conversions for Admin, Update, and Setup
include (LANG.$prefsLanguageFolder.DIRECTORY_SEPARATOR.$prefsLanguage.'_admin.lang.php');
?>