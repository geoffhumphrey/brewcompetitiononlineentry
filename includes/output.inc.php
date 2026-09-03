<?php
/**
 * Module:      output.inc.php
 * Description: This module does all the heavy lifting for any data downloads,
 *              printing, PDF generation, etc.
 * 
 * Brings all functions through a single file to assist with hosted installation
 * deployment.
 */

ob_start();
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', '1');

require ('../paths.php');
require (CONFIG.'bootstrap.php');
require (INCLUDES.'url_variables.inc.php');

/**
 * Entry labels must be uniform regardless of the requesting user's UI
 * language choice, so labels always render in the site-wide default
 * language (privacy: nobody should be able to identify the entrant's
 * language from the printed label). Swap the language session vars to
 * the default for the duration of this output render, then restore them
 * afterwards. The userLanguage cookie is neutralized too, because
 * language.lang.php re-applies it below this swap point.
 * Scoresheets and other output keep the user's chosen language.
 */
if (($section == "entry-form") || ($section == "entry-form-multi")) {
    include (DB.'default_language.db.php');
    $default_lang_info = get_default_language();
    $saved_prefsLanguage = isset($_SESSION['prefsLanguage']) ? $_SESSION['prefsLanguage'] : "";
    $saved_prefsLanguageFolder = isset($_SESSION['prefsLanguageFolder']) ? $_SESSION['prefsLanguageFolder'] : "";
    $saved_userLanguageCookie = isset($_COOKIE['userLanguage']) ? $_COOKIE['userLanguage'] : "";
    $_SESSION['prefsLanguage'] = $default_lang_info['lang'];
    $_SESSION['prefsLanguageFolder'] = $default_lang_info['folder'];
    // Neutralize the cookie so language.lang.php's per-session override
    // doesn't re-apply the user's language after our swap.
    unset($_COOKIE['userLanguage']);
}

require (LANG.'language.lang.php');
require (INCLUDES.'constants_post_lang.inc.php');

// Restore the user's own language choice after the label output completes
if ((($section == "entry-form") || ($section == "entry-form-multi")) && (isset($saved_prefsLanguage))) {
    if ($saved_prefsLanguage !== "") $_SESSION['prefsLanguage'] = $saved_prefsLanguage; else unset($_SESSION['prefsLanguage']);
    if ($saved_prefsLanguageFolder !== "") $_SESSION['prefsLanguageFolder'] = $saved_prefsLanguageFolder; else unset($_SESSION['prefsLanguageFolder']);
}

function convert_to_entities($input) {
    $output = preg_replace_callback("/(&#[0-9]+;)/", function($m) { 
        return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES"); 
    }, $input);
    $output = html_entity_decode($output);
    return $output;
}

$print_sections = array("admin", "assignments", "bos-mat", "dropoff", "summary", "particpant-entries", "inventory", "pullsheets", "results", "sorting", "staff", "table-cards", "notes", "styles", "shipping-label", "evaluation", "contact");
$export_sections = array("export-entries", "export-loc", "export-emails", "export-participants", "export-promo", "export-results", "export-staff", "export-personal-results");
$label_sections = array("labels-admin","labels-participant","labels-judge");
$entry_sections = array("entry-form","entry-form-multi");
$scoresheet_sections = array("scoresheet");

if (in_array($section,$print_sections)) {
	include (OUTPUT.'print.output.php');
}

if (in_array($section,$export_sections)) {
	include (LIB.'admin.lib.php');
	require (LIB.'output.lib.php');
	require (INCLUDES.'scrubber.inc.php');
	include (OUTPUT.'export.output.php');
}

if (in_array($section,$label_sections)) {
	require (CLASSES.'fpdf/pdf_label.php');
	require (CLASSES.'fpdf/FPDFPlus.php');
	include (DB.'output_labels.db.php');
	include (LIB.'admin.lib.php');
	include (LIB.'output.lib.php');
	include (DB.'styles.db.php');
	include (INCLUDES.'scrubber.inc.php');
	include (OUTPUT.'labels.output.php');
}

if (in_array($section,$entry_sections)) {
	include (LIB.'admin.lib.php');
	require (LIB.'output.lib.php');
	include (DB.'output_entry.db.php');
	if (($section == "entry-form") || ($section == "entry-form-multi")) include (OUTPUT.'bottle_label.output.php');
}

if (in_array($section,$scoresheet_sections)) {
	include (OUTPUT.'scoresheets.output.php');
}

?>