<?php

/**
 * ---------------------------------------------
 * TODO - convert the following for translation:
 * ---------------------------------------------
 * - All Admin screens and functions.
 * - All update screens and functions.
 * - All setup screens and functions.
 * - Conversion scripts for brewStyleEntry field (Entry Requirements).
 *   -- Be sure to also update function in common.lib.php.
 */

// Moved the Use SMTP flag to this file to make
// sure it's able to be used by any translation file
$mail_use_smtp = FALSE;

$carat_url_var = FALSE;
if ((isset($view)) && ($view != "default") && ((strpos($view, "^") !== FALSE) || (strpos($view, "%5E") !== FALSE))) $carat_url_var = TRUE;

if (HOSTED) {
  
  $mail_use_smtp = TRUE;
  
  if (!isset($current_parsed_host)) {
    $current_url_to_parse = 'http://';
    if (is_https()) $current_url_to_parse = 'https://';  
    $current_url_to_parse .= $_SERVER['SERVER_NAME'];
    $current_parsed_url = parse_url($current_url_to_parse);
    $current_parsed_host = explode('.', $current_parsed_url['host']);
  }

  if (!isset($_SESSION['prefsEmailFrom'])) $_SESSION['prefsEmailFrom'] = "noreply@".$current_parsed_host[1].".".$current_parsed_host[2];
  
}

elseif ((!HOSTED) && (isset($_SESSION['prefsEmailSMTP']))) { 
  if (($_SESSION['prefsEmailSMTP'] == 1) && (!empty($_SESSION['prefsEmailHost'])) && (!empty($_SESSION['prefsEmailFrom'])) && (!empty($_SESSION['prefsEmailUsername'])) && (!empty($_SESSION['prefsEmailPassword'])) && (!empty($_SESSION['prefsEmailPort']))) $mail_use_smtp = TRUE;
}

// Default to US English language if prefs not defined.
$prefsLanguage = "en-US";
$prefsLanguageFolder = "en";

/**
 * Per-session language override. 
 *
 * Allows users to switch languages independently of the site-wide default
 * set in Site Preferences. The cookie is set by the language toggle in
 * the navigation bar (pub/nav.pub.php) via the ?lang=XX URL parameter
 * handled in bootstrap.php.
 *
 * To enable this feature, set $enable_language_toggle = TRUE in config.php.
 * By default, this feature is disabled and the site-wide preference is
 * used exclusively (backwards-compatible with existing installations).
 *
 * The site-wide preference (from the DB) remains the default when
 * no cookie is set. This runs on every page load, so the override
 * takes effect immediately and persists across sessions (30-day cookie).
 */
global $enable_language_toggle;
if (isset($enable_language_toggle) && $enable_language_toggle
    && isset($_COOKIE['userLanguage']) && isset($languages) && is_array($languages)) {
  $valid_langs = array_keys($languages);
  if (in_array($_COOKIE['userLanguage'], $valid_langs)) {
    $_SESSION['prefsLanguage'] = $_COOKIE['userLanguage'];
    $lang_folder_parts = explode("-", $_COOKIE['userLanguage']);
    $_SESSION['prefsLanguageFolder'] = strtolower($lang_folder_parts[0]);
  }
}

if ((isset($_SESSION['prefsLanguage'])) && (!empty($_SESSION['prefsLanguage']))) {
  
  if (($_SESSION['prefsLanguage'] == "English") || ($_SESSION['prefsLanguage'] == "english")) {
    $_SESSION['prefsLanguage'] = "en-US";
    $prefsLanguage = "en-US";
  }

  else $prefsLanguage = $_SESSION['prefsLanguage'];

} 

if ((isset($_SESSION['prefsLanguageFolder'])) && (!empty($_SESSION['prefsLanguageFolder']))) {
  // A legacy "English"/"english" prefsLanguage value derives to a "english" folder
  // upstream, which doesn't exist on disk (the actual folder is "en") - catch it here too.
  if (strtolower($_SESSION['prefsLanguageFolder']) == "english") $prefsLanguageFolder = "en";
  else $prefsLanguageFolder = $_SESSION['prefsLanguageFolder'];
}

// Set the language to US English for all admin functions.
if (((isset($section)) && ($section == "admin")) || ((isset($section)) && ($section == "evaluation") && ($view == "admin"))) {
  $prefsLanguage = "en-US";
  $prefsLanguageFolder = "en";
}

// Set language for setup to be US English.
if ((isset($section)) && (strpos($section, "step") === TRUE)) {
  $prefsLanguage = "en-US";
  $prefsLanguageFolder = "en";
}

if ((isset($section)) && ($section == "update")) {
  $prefsLanguage = "en-US";
  $prefsLanguageFolder = "en";
}

// Load public pages language file
include (LANG.$prefsLanguageFolder.DIRECTORY_SEPARATOR.$prefsLanguage.'.lang.php');

// Load admin pages language file
// A future version will have full conversions for Admin, Update, and Setup
include (LANG.$prefsLanguageFolder.DIRECTORY_SEPARATOR.$prefsLanguage.'_admin.lang.php');

?>