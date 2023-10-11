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
require (LANG.'language.lang.php');

$print_sections = array("admin", "assignments", "bos-mat", "dropoff", "summary", "particpant-entries", "inventory", "pullsheets", "results", "sorting", "staff", "table-cards", "notes", "styles", "shipping-label", "evaluation");
$export_sections = array("export-entries", "export-loc", "export-emails", "export-participants", "export-promo", "export-results", "export-staff", "export-personal-results");
$label_sections = array("labels-admin","labels-participant","labels-judge");
$entry_sections = array("entry-form","entry-form-multi");
$scoresheet_sections = array("scoresheet");

if (in_array($section,$print_sections)) {
	include (OUTPUT.'print.output.php');
}

if (in_array($section,$export_sections)) {
	require (LIB.'output.lib.php');
	require (INCLUDES.'scrubber.inc.php');
	include (OUTPUT.'export.output.php');
}

if (in_array($section,$label_sections)) {
	require (CLASSES.'fpdf/pdf_label.php');
	require (CLASSES.'fpdf/FPDFPlus.php');
	include (DB.'output_labels.db.php');
	include (LIB.'output.lib.php');
	include (DB.'styles.db.php');
	include (INCLUDES.'scrubber.inc.php');
	include (OUTPUT.'labels.output.php');
}

if (in_array($section,$entry_sections)) {
	require (LIB.'output.lib.php');
	include (CLASSES.'tiny_but_strong/tbs_class.php');
	include (DB.'output_entry.db.php');
	if ($section == "entry-form") include (OUTPUT.'entry.output.php');
	if ($section == "entry-form-multi") include (OUTPUT.'bottle_label.output.php');
}

if (in_array($section,$scoresheet_sections)) {
	include (OUTPUT.'scoresheets.output.php');
}

?>