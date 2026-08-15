<?php
/**
 * Module:      reg_open.sec.php
 * Description: This module houses information regarding registering for the competition,
 *              judging dates, etc. Shown while the registration window is open.
 *
 */


$page_info_reg_open = "";

// Judge/Steward Registration Info
$page_info_reg_open .= "<div class=\"reveal-element\">";

if (($registration_open == 1) && ($judge_window_open == 1) && (!isset($_SESSION['loginUsername']))) {

	if (($judge_limit) && (!$steward_limit)) {
		$page_info_reg_open .= sprintf("<h2>%s <span class='text-success'>%s</span></h2>",$reg_open_text_016,$reg_open_text_001);
		$page_info_reg_open .= sprintf("<p><strong class=\"text-danger\">%s</strong></p>",$alert_text_072);
		$page_info_reg_open .= sprintf("<p>%s %s.</p>",$reg_open_text_002,$reg_open_text_003);
	}

	if ((!$judge_limit) && ($steward_limit)) {
		$page_info_reg_open .= sprintf("<h2>%s <span class='text-success'>%s</span></h2>",$reg_open_text_015,$reg_open_text_001);
		$page_info_reg_open .= sprintf("<p><strong class=\"text-danger\">%s</strong></p>",$alert_text_076);
		$page_info_reg_open .= sprintf("<p>%s %s.</p>",$reg_open_text_002,$reg_open_text_003);
	}

	if ((!$judge_limit) && (!$steward_limit)) {
		$page_info_reg_open .= sprintf("<h2>%s <span class='text-success'>%s</span></h2>",$reg_open_text_000,$reg_open_text_001) ;
		$page_info_reg_open .= sprintf("<p>%s %s.</p>",$reg_open_text_002,$reg_open_text_003);
	}

	$page_info_reg_open .= sprintf("<p>%s <span class=\"fa fa-lg fa-user\"></span> %s</p>",$reg_open_text_004,$reg_open_text_005);
}

if (($registration_open == 1) && (!isset($_SESSION['brewerBreweryName'])) && (isset($_SESSION['loginUsername']))) $page_info_reg_open .= sprintf("<p>%s <a href=\"%s\">%s</a> %s</p>",$reg_open_text_006,build_public_url("list","default","default","default",$sef,$base_url),$reg_open_text_007,$reg_open_text_008);

if ($registration_open != 1) $page_info_reg_open .= sprintf("<p>%s %s.</p>",$reg_open_text_009,$judge_open);

$page_info_reg_open .= "</div>";

// General Comp Rules
$page_info_reg_open .= "<div class=\"reveal-element\">";

if (($entry_window_open == 1) && ($show_entries)) $page_info_reg_open .= sprintf("<h2>%s <span class=\"text-success\">%s</a></h2>",$reg_open_text_010,$reg_open_text_001);

if ((isset($row_contest_rules['contestRules'])) && (!empty($row_contest_rules['contestRules']))) {
	$contestRulesJSON = json_decode($row_contest_rules['contestRules'],true);
	if ((ENABLE_MARKDOWN) && (!is_html($contestRulesJSON['competition_rules']))) {
		$page_info_reg_open .= Parsedown::instance()
						->setBreaksEnabled(true) # enables automatic line breaks
						->text($contestRulesJSON['competition_rules']); 
	}
	else $page_info_reg_open .= $contestRulesJSON['competition_rules'];
}

$page_info_reg_open .= "</div>";

// Entry Acceptance Rules

if ((isset($_SESSION['contestBottles'])) && (($dropoff_window_open < 2) || ($shipping_window_open < 2))) {
	
	$page_info_reg_open .= "<div class=\"reveal-element\">";
	$page_info_reg_open .= sprintf("<h2>%s</h2>",$label_entry_acceptance_rules);

	if ((ENABLE_MARKDOWN) && (!is_html($_SESSION['contestBottles']))) { 
		$page_info_reg_open .= Parsedown::instance()
					   ->setBreaksEnabled(true) # enables automatic line breaks
					   ->setMarkupEscaped(true) # escapes markup (HTML)
					   ->text($_SESSION['contestBottles']); 
	}
	
	else $page_info_reg_open .= $_SESSION['contestBottles'];
	$page_info_reg_open .= "</div>";

}

// --------------------------------------------------------------
// Display
// --------------------------------------------------------------

echo $page_info_reg_open;

?>