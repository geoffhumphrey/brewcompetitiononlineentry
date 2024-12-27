<?php
/**
 * Module:      reg_open.sec.php
 * Description: This module houses information regarding registering for the competition,
 *              judging dates, etc. Shown while the registration window is open.
 *
 */

$page_info_reg_open = "";

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

if (($entry_window_open == 1) && ($show_entries)) {
	
	$page_info_reg_open .= sprintf("<h2>%s <span class='text-success'>%s</a></h2>",$reg_open_text_010,$reg_open_text_001);

	if ($_SESSION['prefsProEdition'] == 0)  {
		
		$page_info_reg_open .= "<p>";
		$page_info_reg_open .= sprintf("<strong class=\"text-success\">%s %s</strong> %s %s, %s.", $total_entries, strtolower($label_entries), $sidebar_text_025, $current_time, $current_date_display);
		if ($row_limits['prefsEntryLimit'] > 0) $page_info_reg_open .= sprintf(" %s <strong>%s</strong> %s",$entry_info_text_019,$row_limits['prefsEntryLimit'],$entry_info_text_020);
		$page_info_reg_open .= "</p>";

		$page_info_reg_open .= "<p>";
		$page_info_reg_open .= sprintf("<strong class=\"text-success\">%s %s</strong> %s %s, %s.", $total_paid, strtolower($label_paid_entries), $sidebar_text_026, $current_time, $current_date_display);
		if ($row_limits['prefsEntryLimitPaid'] > 0) $page_info_reg_open .= sprintf(" %s <strong>%s</strong> <em>%s</em> %s",$entry_info_text_019,$row_limits['prefsEntryLimitPaid'],strtolower($label_paid),$entry_info_text_020);
		$page_info_reg_open .= "</p>";


		if (!empty($style_type_entry_count_display)) {
			$page_info_reg_open .= sprintf("<p>%s</p>",$entry_info_text_054);
			$page_info_reg_open .= "<ul>";
			foreach ($style_type_entry_count_display as $key => $value) {
				if (!empty($value[1])) {
					$warning_icon = "";
					$text_warning_color = "";
					if ($value[0] >= ($value[1] - ($value[0] * .10))) {
						$warning_icon = " <span class=\"text-danger\"><i class=\"fa fa-fw fa-exclamation-circle fa-sm\"></i></span>";
						$text_warning_color = "text-danger";
					}
					$page_info_reg_open .= "<li>";
					$page_info_reg_open .= sprintf("<strong>%s</strong> &ndash; <span class='%s'>%s</span> %s %s %s", $key, $text_warning_color, $value[0], strtolower($label_of), $value[1], $warning_icon);
					$page_info_reg_open .= "</li>";
				}
			}
			$page_info_reg_open .= "</ul>";
		}
			

	}

	/*
	$page_info_reg_open .= "<p>";
	$page_info_reg_open .= sprintf("%s, ",$reg_open_text_011);
	if (!isset($_SESSION['loginUsername'])) $page_info_reg_open .= sprintf("%s or %s %s",$reg_open_text_012,strtolower($label_log_in),$reg_open_text_013);
	else $page_info_reg_open .= sprintf("<a href=\"%s\">%s</a>.",build_public_url("brew","entry","add","default",$sef,$base_url),$reg_open_text_014);
	$page_info_reg_open .= "</p>";
	*/
	
}

if ((isset($row_contest_rules['contestRules'])) && (!empty($row_contest_rules['contestRules']))) {
	$contestRulesJSON = json_decode($row_contest_rules['contestRules'],true);
	if ((ENABLE_MARKDOWN) && (!is_html($contestRulesJSON['competition_rules']))) {
		$page_info_reg_open .= Parsedown::instance()
						->setBreaksEnabled(true) # enables automatic line breaks
						->text($contestRulesJSON['competition_rules']); 
	}
	else $page_info_reg_open .= $contestRulesJSON['competition_rules'];
}

if ((isset($_SESSION['contestBottles'])) && (($dropoff_window_open < 2) || ($shipping_window_open < 2))) {
	
	$page_info_reg_open .= sprintf("<h2>%s</h2>",$label_entry_acceptance_rules);

	if ((ENABLE_MARKDOWN) && (!is_html($_SESSION['contestBottles']))) { 
		$page_info_reg_open .= Parsedown::instance()
					   ->setBreaksEnabled(true) # enables automatic line breaks
					   ->setMarkupEscaped(true) # escapes markup (HTML)
					   ->text($_SESSION['contestBottles']); 
	}
	
	else $page_info_reg_open .= $_SESSION['contestBottles'];

}

// --------------------------------------------------------------
// Display
// --------------------------------------------------------------

echo $page_info_reg_open;

?>