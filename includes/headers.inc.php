<?php
/**
 * Module:      headers.inc.php
 * Description: This module defines all header text for the application based
 *              upon URL variables.
 *
 */

$header_output = "";
$output = "";
$output_extend = "";
if (strpos($section, "step") === FALSE) {
	if ($_SESSION['jPrefsQueued'] == "N") $assign_to = "Flights"; else $assign_to = "Tables";
}

if ($section == "past-winners") {
	$filter = $go;
}

switch($section) {

	case "default":
	case "past-winners":
		if (isset($_SESSION['contestName'])) $header_output = $_SESSION['contestName'];
		else $header_output = "";

		if (($filter != "default") && ($section == "past-winners")) $header_output .= ": ".$label_past_winners." &ndash; ".$filter;

		if ($msg == "success") {
			$output = "<strong>".$header_text_000."</strong>";
			$output_extend = sprintf("<div class=\"alert alert-info hidden-print\"><span class=\"fa fa-lg fa-info-circle\"></span> %s</p>",$header_text_001);
		}

		if ($msg == "chmod") {
			$output = sprintf("<strong>%s</strong> ",$header_text_000,$header_text_002);
			$output_extend = sprintf("<div class='alert alert-warning hidden-print'><span class=\"fa fa-lg fa-exclamation-triangle\">%s</div>",$header_text_003);
			if (($setup_free_access == TRUE) && ($action != "print")) $output_extend .= sprintf("<div class='alert alert-warning hidden-print'><span class=\"fa fa-lg fa-exclamation-triangle\">%s</div>",$header_text_004);
		}

		if     ($msg == "1") $output = sprintf("<strong>%s</strong>",$header_text_005);
		elseif ($msg == "2") $output = sprintf("<strong>%s</strong>",$header_text_006);
		elseif ($msg == "3") $output = sprintf("<strong>%s</strong> %s",$header_text_007,$header_text_008);
		elseif ($msg == "4") $output = sprintf("<strong>%s</strong>",$header_text_009);
		elseif ($msg == "5") $output = sprintf("<strong>%s</strong> <a href=\#\"  role=\"button\" data-toggle=\"modal\" data-target=\"#loginModal\">%s</a>",$header_text_036,$header_text_037);
		elseif ($msg == "6") { $output = sprintf("<strong>%s</strong> %s",$header_text_034,$header_text_116); $output_extend = ""; }
		elseif ($msg == "7") $output = sprintf("<strong>%s</strong>",$alert_text_088);
		elseif ($msg == "8") $output = sprintf("<strong>%s</strong>",$alert_text_089);
		elseif ($msg == "9") $output = sprintf("<strong>%s</strong>",$header_text_066);
	break;

	case "evaluation":
		if 	   ($msg == "2") $output = sprintf("<strong>%s</strong>",$header_text_006);
		elseif ($msg == "3") $output = sprintf("<strong>%s</strong>",$header_text_005);
		elseif ($msg == "4") $output = sprintf("<strong>%s</strong>",$header_text_006);
		elseif ($msg == "5") $output = sprintf("<strong>%s</strong>",$header_text_044);

		$header_output = $label_judging_dashboard;

		switch($go) {
			case "default":
				if ($view == "admin") $header_output = $label_admin.": ".$label_evaluations;
				else $header_output = $label_judging_dashboard;
			break;
			case "scoresheet":
				if ($view == "admin") $header_output = $label_admin.": ".$label_judging_scoresheet;
				else $header_output = $label_judging_scoresheet;
			break;
		}
	break;

	case "user":
		$header_output = $header_text_010." ";
		if ($action == "username") $header_output .= $header_text_011;
		if ($action == "password") $header_output .= $header_text_012;
		if     ($msg == "1") $output = sprintf("<strong>%s</strong>",$header_text_013);
		elseif ($msg == "2") $output = sprintf("<strong>%s</strong>",$header_text_014);
		elseif ($msg == "3") $output = sprintf("<strong>%s</strong> %s",$header_text_015, $header_text_008);
		else $output = "";
	break;

	case "register":
		$header_output = $_SESSION['contestName'];
		if ($go == "judge") $header_output .= " - ".$label_judge_reg;
		elseif ($go == "steward") $header_output .= " - ".$label_steward_reg;
		else $header_output .= " - ".$label_reg;
		if     ($msg == "1") $output = sprintf("<strong>%s</strong> %s",$header_text_017, $header_text_008);
		elseif ($msg == "2") { $output = sprintf("<strong>%s</strong>",$header_text_018); $output_extend = sprintf("<p>%s <a href=\"index.php?section=login\">%s</a></p>",$header_text_019,$header_text_020); }
		elseif ($msg == "3") $output = sprintf("<strong>%s</strong> %s",$header_text_021, $header_text_022);
		elseif ($msg == "4") $output = sprintf("<strong>%s</strong> %s",$header_text_023, $header_text_008);
		elseif ($msg == "5") $output = sprintf("<strong>%s</strong> %s",$header_text_024, $header_text_008);
		elseif ($msg == "6") $output = sprintf("<strong></strong> %s",$header_text_025, $header_text_008);
		else $output = "";
	break;

	case "pay":
		$header_output = $_SESSION['contestName']." - ".$label_pay;
		if     ($msg == "1") $output = sprintf("<strong>%s</strong>",$header_text_005);
		elseif ($msg == "2") $output = sprintf("<strong>%s</strong>",$header_text_006);
		elseif ($msg == "3") $output = sprintf("<strong>%s</strong> %s",$header_text_007,$header_text_008);
		elseif ($msg == "10") $output = sprintf("<strong>%s</strong> %s",$header_text_026,$header_text_027);
		elseif ($msg == "11") $output = sprintf("<strong>%s</strong>",$header_text_028);
		elseif ($msg == "12") $output = sprintf("<strong>%s</strong>",$header_text_029);
		elseif ($msg == "13") $output = sprintf("<strong>%s</strong>",$header_text_030);
		else $output = "";
	break;

	case "login":
		if ($action == "forgot") $header_output = $_SESSION['contestName']." - ".$label_reset_password;
		elseif ($action == "logout") $header_output = $_SESSION['contestName']." - ".$label_logged_out;
		elseif ($action == "reset-password") $header_output = $_SESSION['contestName']." - ".$label_reset_password." ".$label_with_token;
		else $header_output = $_SESSION['contestName']." - ".$label_log_in;
		if ($msg == "0") $output = sprintf("<strong>%s</strong> ",$header_text_031);
		elseif ($msg == "1") { $output = sprintf("<strong>%s</strong> %s",$header_text_032,$header_text_033); $output_extend = ""; }
	 	elseif ($msg == "2") { $output = sprintf("<strong>%s</strong> %s",$header_text_034,$header_text_116); $output_extend = ""; }
		elseif ($msg == "3") $output = sprintf("<strong>%s</strong> <a href=\#\"  role=\"button\" data-toggle=\"modal\" data-target=\"#loginModal\">%s</a>",$header_text_036,$header_text_037);
		elseif ($msg == "4") $output = sprintf("<strong>%s</strong> %s",$header_text_038,$header_text_008);
		elseif ($msg == "5") $output = sprintf("<strong>%s</strong>",$header_text_039);
		elseif ($msg == "6") $output = sprintf("<strong>%s</strong>",$login_text_023);
		elseif ($msg == "7") $output= sprintf("<strong>%s</strong>",$login_text_022);
		elseif ($msg == "8") $output= sprintf("<strong>%s</strong>",$login_text_027);
		else $output = "";
	break;

	case "entry":
		$header_output = $_SESSION['contestName']." ".$label_info;
		if     ($msg == "1") $output = sprintf("<strong>%s</strong>",$header_text_005);
		elseif ($msg == "2") $output = sprintf("<strong>%s</strong>",$header_text_006);
		elseif ($msg == "3") $output = sprintf("<strong>%s</strong> %s",$header_text_007,$header_text_008);
		else $output = "";
	break;

	case "sponsors":
		$header_output = $_SESSION['contestName']." ".$label_sponsors;
		if     ($msg == "1") $output = sprintf("<strong>%s</strong>",$header_text_005);
		elseif ($msg == "2") $output = sprintf("<strong>%s</strong>",$header_text_006);
		elseif ($msg == "3") $output = sprintf("<strong>%s</strong> %s",$header_text_007,$header_text_008);
		else $output = "";
	break;

	case "rules":
	$header_output = $_SESSION['contestName']." ".$label_rules;
		if     ($msg == "1") $output = sprintf("<strong>%s</strong>",$header_text_005);
		elseif ($msg == "2") $output = sprintf("<strong>%s</strong>",$header_text_006);
		elseif ($msg == "3") $output = sprintf("<strong>%s</strong> %s",$header_text_007,$header_text_008);
		else $output = "";
	break;

	case "volunteers":
		if     ($msg == "1") $output = sprintf("<strong>%s</strong>",$header_text_005);
		elseif ($msg == "2") $output = sprintf("<strong>%s</strong>",$header_text_006);
		elseif ($msg == "3") $output = sprintf("<strong>%s</strong> %s",$header_text_007,$header_text_008);
		else $output = "";
		$header_output = $_SESSION['contestName']." ".$label_volunteer_info;
	break;

	case "past_winners":
		$header_output = $_SESSION['contestName']." - ".$label_past_winners;
		if     ($msg == "1") $output = sprintf("<strong>%s</strong>",$header_text_005);
		elseif ($msg == "2") $output = sprintf("<strong>%s</strong>",$header_text_006);
		elseif ($msg == "3") $output = sprintf("<strong>%s</strong> %s",$header_text_007,$header_text_008);
		else $output = "";
	break;

	case "contact":
		$header_output = $_SESSION['contestName']." - ".$label_contact;
		if ($msg == "1") {

			$query_contact = sprintf("SELECT contactFirstName,contactLastName,contactPosition FROM $contacts_db_table WHERE id='%s'", $id);
			$contact = mysqli_query($connection,$query_contact) or die (mysqli_error($connection));
			$row_contact = mysqli_fetch_assoc($contact);

			$output = sprintf("<strong>%s ".$row_contact['contactFirstName']." ".$row_contact['contactLastName'].", ".$row_contact['contactPosition'].".</strong>",$header_text_040);
		}
		elseif ($msg == "2") $output = sprintf("<strong>%s</strong> %s",$header_text_041,$header_text_008);
		else $output = "";
	break;

	case "brew":
		if ($action == "add") $header_output = $label_add_entry;
		else $header_output = $label_edit_entry;
		
		switch ($msg) {
			case "2": $output = sprintf("<strong>%s</strong><br>",$header_text_006); break;
			case "3": $output = sprintf("<strong>%s</strong> %s<br>",$header_text_007,$header_text_008); break;
			case "4": $output = sprintf("<strong>%s</strong><br>",$header_text_108); break;
			default: $output = sprintf("<strong>%s</strong> %s",$header_text_105,$header_text_106); break;
		}

		if ((strstr($msg,"1-")) && ($_SESSION['prefsAutoPurge'] == 1)) $output .= " Unconfirmed entries may be deleted from the system without warning.";
	break;

	case "brewer":
		if     ($msg == "1") $output = sprintf("<strong>%s</strong>",$header_text_005);
		elseif ($msg == "2") $output = sprintf("<strong>%s</strong>",$header_text_006);
		elseif ($msg == "3") $output = sprintf("<strong>%s</strong> %s",$header_text_007,$header_text_008);
		else $output = "";
		if ($action == "add") $header_output = $label_step." 2: ".$label_account_info;
		elseif ($go == "admin")  $header_output = $label_admin_edit_account;
		else  $header_output = $label_edit_account;
	break;

	case "judge":
		$header_output = $label_judge_info;
		if     ($msg == "1") $output = sprintf("<strong>%s</strong>",$header_text_005);
		elseif ($msg == "2") $output = sprintf("<strong>%s</strong>",$header_text_006);
		elseif ($msg == "3") $output = sprintf("<strong>%s</strong> %s",$header_text_007,$header_text_008);
		else $output = "";
	break;

	case "list":
		$header_output = $label_my_account;
		if     ($msg == "1") $output = sprintf("<strong>%s</strong>",$header_text_005);
		elseif ($msg == "2") $output = sprintf("<strong>%s</strong>",$header_text_006);
		elseif ($msg == "3") $output = sprintf("<strong>%s</strong>",$header_text_042);
		elseif ($msg == "4") $output = sprintf("<strong>%s</strong>",$header_text_043);
		elseif ($msg == "5") $output = sprintf("<strong>%s</strong>",$header_text_044);
		elseif ($msg == "6") $output = sprintf("<strong>%s</strong>",$header_text_045);
		elseif ($msg == "7") $output = sprintf("<strong>%s</strong>",$header_text_046);
		elseif ($msg == "8") $output = sprintf("<strong>%s</strong> %s",$header_text_047,$header_text_048);
		elseif ($msg == "9") $output = sprintf("<strong>%s</strong> %s",$header_text_049,$header_text_048);
		elseif ($msg == "10") $output = sprintf("<strong>%s</strong>",$header_text_109);
		elseif ($msg == "11") $output = sprintf("<strong>%s</strong>",$output_text_004);
		elseif ($msg == "12") $output = sprintf("<strong>%s</strong>",$header_text_113);
		else $output = "";
	break;

	case "step0":
		$header_output = $header_text_050;
		$sidebar_status_0 = "text-danger";
		$sidebar_status_1 = "text-muted";
		$sidebar_status_2 = "text-muted";
		$sidebar_status_3 = "text-muted";
		$sidebar_status_4 = "text-muted";
		$sidebar_status_5 = "text-muted";
		$sidebar_status_6 = "text-muted";
		$sidebar_status_7 = "text-muted";
		$sidebar_status_8 = "text-muted";
		$sidebar_status_icon_0 = "fa fa-lg fa-refresh fa-spin";
		$sidebar_status_icon_1 = "fa fa-lg fa-clock-o";
		$sidebar_status_icon_2 = "fa fa-lg fa-clock-o";
		$sidebar_status_icon_3 = "fa fa-lg fa-clock-o";
		$sidebar_status_icon_4 = "fa fa-lg fa-clock-o";
		$sidebar_status_icon_5 = "fa fa-lg fa-clock-o";
		$sidebar_status_icon_6 = "fa fa-lg fa-clock-o";
		$sidebar_status_icon_7 = "fa fa-lg fa-clock-o";
		$sidebar_status_icon_8 = "fa fa-lg fa-clock-o";
	break;

	case "step1":
		$header_output = $header_text_051;
		if ($msg == "1") $output = sprintf("<strong>%s</strong>",$header_text_016);
		$sidebar_status_0 = "text-success";
		$sidebar_status_1 = "text-danger";
		$sidebar_status_2 = "text-muted";
		$sidebar_status_3 = "text-muted";
		$sidebar_status_4 = "text-muted";
		$sidebar_status_5 = "text-muted";
		$sidebar_status_6 = "text-muted";
		$sidebar_status_7 = "text-muted";
		$sidebar_status_8 = "text-muted";
		$sidebar_status_icon_0 = "fa fa-lg fa-check";
		$sidebar_status_icon_1 = "fa fa-lg fa-refresh fa-spin";
		$sidebar_status_icon_2 = "fa fa-lg fa-clock-o";
		$sidebar_status_icon_3 = "fa fa-lg fa-clock-o";
		$sidebar_status_icon_4 = "fa fa-lg fa-clock-o";
		$sidebar_status_icon_5 = "fa fa-lg fa-clock-o";
		$sidebar_status_icon_6 = "fa fa-lg fa-clock-o";
		$sidebar_status_icon_7 = "fa fa-lg fa-clock-o";
		$sidebar_status_icon_8 = "fa fa-lg fa-clock-o";
	break;

	case "step2":
		$header_output = $header_text_052;
		$sidebar_status_0 = "text-success";
		$sidebar_status_1 = "text-success";
		$sidebar_status_2 = "text-danger";
		$sidebar_status_3 = "text-muted";
		$sidebar_status_4 = "text-muted";
		$sidebar_status_5 = "text-muted";
		$sidebar_status_6 = "text-muted";
		$sidebar_status_7 = "text-muted";
		$sidebar_status_8 = "text-muted";
		$sidebar_status_icon_0 = "fa fa-lg fa-check";
		$sidebar_status_icon_1 = "fa fa-lg fa-check";
		$sidebar_status_icon_2 = "fa fa-lg fa-refresh fa-spin";
		$sidebar_status_icon_3 = "fa fa-lg fa-clock-o";
		$sidebar_status_icon_4 = "fa fa-lg fa-clock-o";
		$sidebar_status_icon_5 = "fa fa-lg fa-clock-o";
		$sidebar_status_icon_6 = "fa fa-lg fa-clock-o";
		$sidebar_status_icon_7 = "fa fa-lg fa-clock-o";
		$sidebar_status_icon_8 = "fa fa-lg fa-clock-o";
	break;

	case "step3":
		$header_output = $header_text_053;
		$sidebar_status_0 = "text-success";
		$sidebar_status_1 = "text-success";
		$sidebar_status_2 = "text-success";
		$sidebar_status_3 = "text-danger";
		$sidebar_status_4 = "text-muted";
		$sidebar_status_5 = "text-muted";
		$sidebar_status_6 = "text-muted";
		$sidebar_status_7 = "text-muted";
		$sidebar_status_8 = "text-muted";
		$sidebar_status_icon_0 = "fa fa-lg fa-check";
		$sidebar_status_icon_1 = "fa fa-lg fa-check";
		$sidebar_status_icon_2 = "fa fa-lg fa-check";
		$sidebar_status_icon_3 = "fa fa-lg fa-refresh fa-spin";
		$sidebar_status_icon_4 = "fa fa-lg fa-clock-o";
		$sidebar_status_icon_5 = "fa fa-lg fa-clock-o";
		$sidebar_status_icon_6 = "fa fa-lg fa-clock-o";
		$sidebar_status_icon_7 = "fa fa-lg fa-clock-o";
		$sidebar_status_icon_8 = "fa fa-lg fa-clock-o";
	break;

	case "step4":
		$header_output = $header_text_054;
		$sidebar_status_0 = "text-success";
		$sidebar_status_1 = "text-success";
		$sidebar_status_2 = "text-success";
		$sidebar_status_3 = "text-success";
		$sidebar_status_4 = "text-danger";
		$sidebar_status_5 = "text-muted";
		$sidebar_status_6 = "text-muted";
		$sidebar_status_7 = "text-muted";
		$sidebar_status_8 = "text-muted";
		$sidebar_status_icon_0 = "fa fa-lg fa-check";
		$sidebar_status_icon_1 = "fa fa-lg fa-check";
		$sidebar_status_icon_2 = "fa fa-lg fa-check";
		$sidebar_status_icon_3 = "fa fa-lg fa-check";
		$sidebar_status_icon_4 = "fa fa-lg fa-refresh fa-spin";
		$sidebar_status_icon_5 = "fa fa-lg fa-clock-o";
		$sidebar_status_icon_6 = "fa fa-lg fa-clock-o";
		$sidebar_status_icon_7 = "fa fa-lg fa-clock-o";
		$sidebar_status_icon_8 = "fa fa-lg fa-clock-o";
	break;

	case "step5":
		$header_output = $header_text_055;
		$sidebar_status_0 = "text-success";
		$sidebar_status_1 = "text-success";
		$sidebar_status_2 = "text-success";
		$sidebar_status_3 = "text-success";
		$sidebar_status_4 = "text-success";
		$sidebar_status_5 = "text-danger";
		$sidebar_status_6 = "text-muted";
		$sidebar_status_7 = "text-muted";
		$sidebar_status_8 = "text-muted";
		$sidebar_status_icon_0 = "fa fa-lg fa-check";
		$sidebar_status_icon_1 = "fa fa-lg fa-check";
		$sidebar_status_icon_2 = "fa fa-lg fa-check";
		$sidebar_status_icon_3 = "fa fa-lg fa-check";
		$sidebar_status_icon_4 = "fa fa-lg fa-check";
		$sidebar_status_icon_5 = "fa fa-lg fa-refresh fa-spin";
		$sidebar_status_icon_6 = "fa fa-lg fa-clock-o";
		$sidebar_status_icon_7 = "fa fa-lg fa-clock-o";
		$sidebar_status_icon_8 = "fa fa-lg fa-clock-o";
	break;

	case "step6":
		$header_output = $header_text_056;
		$sidebar_status_0 = "text-success";
		$sidebar_status_1 = "text-success";
		$sidebar_status_2 = "text-success";
		$sidebar_status_3 = "text-success";
		$sidebar_status_4 = "text-success";
		$sidebar_status_5 = "text-success";
		$sidebar_status_6 = "text-danger";
		$sidebar_status_7 = "text-muted";
		$sidebar_status_8 = "text-muted";
		$sidebar_status_icon_0 = "fa fa-lg fa-check";
		$sidebar_status_icon_1 = "fa fa-lg fa-check";
		$sidebar_status_icon_2 = "fa fa-lg fa-check";
		$sidebar_status_icon_3 = "fa fa-lg fa-check";
		$sidebar_status_icon_4 = "fa fa-lg fa-check";
		$sidebar_status_icon_5 = "fa fa-lg fa-check";
		$sidebar_status_icon_6 = "fa fa-lg fa-refresh fa-spin";
		$sidebar_status_icon_7 = "fa fa-lg fa-clock-o";
		$sidebar_status_icon_8 = "fa fa-lg fa-clock-o";
	break;

	case "step7":
		$header_output = $header_text_057;
		$sidebar_status_0 = "text-success";
		$sidebar_status_1 = "text-success";
		$sidebar_status_2 = "text-success";
		$sidebar_status_3 = "text-success";
		$sidebar_status_4 = "text-success";
		$sidebar_status_5 = "text-success";
		$sidebar_status_6 = "text-success";
		$sidebar_status_7 = "text-danger";
		$sidebar_status_8 = "text-muted";
		$sidebar_status_icon_0 = "fa fa-lg fa-check";
		$sidebar_status_icon_1 = "fa fa-lg fa-check";
		$sidebar_status_icon_2 = "fa fa-lg fa-check";
		$sidebar_status_icon_3 = "fa fa-lg fa-check";
		$sidebar_status_icon_4 = "fa fa-lg fa-check";
		$sidebar_status_icon_5 = "fa fa-lg fa-check";
		$sidebar_status_icon_6 = "fa fa-lg fa-check";
		$sidebar_status_icon_7 = "fa fa-lg fa-refresh fa-spin";
		$sidebar_status_icon_8 = "fa fa-lg fa-clock-o";
	break;

	case "step8":
		$header_output = $header_text_058;
		$sidebar_status_0 = "text-success";
		$sidebar_status_1 = "text-success";
		$sidebar_status_2 = "text-success";
		$sidebar_status_3 = "text-success";
		$sidebar_status_4 = "text-success";
		$sidebar_status_5 = "text-success";
		$sidebar_status_6 = "text-success";
		$sidebar_status_7 = "text-success";
		$sidebar_status_8 = "text-muted";
		$sidebar_status_icon_0 = "fa fa-lg fa-check";
		$sidebar_status_icon_1 = "fa fa-lg fa-check";
		$sidebar_status_icon_2 = "fa fa-lg fa-check";
		$sidebar_status_icon_3 = "fa fa-lg fa-check";
		$sidebar_status_icon_4 = "fa fa-lg fa-check";
		$sidebar_status_icon_5 = "fa fa-lg fa-check";
		$sidebar_status_icon_6 = "fa fa-lg fa-check";
		$sidebar_status_icon_7 = "fa fa-lg fa-check";
		$sidebar_status_icon_8 = "fa fa-lg fa-refresh fa-spin";
	break;

	case "admin":
		if ($action != "print") $header_output = $label_admin;
		else $header_output = $_SESSION['contestName'];

			switch($go) {

				case "default":
				$header_output .= " ".$label_admin_dashboard;
				break;

				case "judging":
				$header_output .= ": Sessions";
				break;

				case "stewards":
				$header_output .= ": ".$label_admin_stewarding;
				break;

				case "participants":
				$header_output .= ": ".$label_admin_participants;
				break;

				case "entrant":
				$header_output .= ": ".$label_admin_participants;
				break;

				case "judge":
				$header_output .= ": ".$label_admin_participants;
				break;

				case "entries":
				$header_output .= ": ".$label_admin_entries;
				break;

				case "contest_info":
				$header_output .= ": ".$label_admin_comp_info;
				break;

				case "preferences":
				$header_output .= ": ".$label_admin_web_prefs;
				break;

				case "judging_preferences":
				$header_output .= ": ".$label_admin_judge_prefs;
				break;

				case "archive":
				$header_output .= ": ".$label_admin_archives;
				break;

				case "styles":
				$header_output .= ": ".$label_admin_styles;
				break;

				case "dropoff":
				$header_output .= ": ".$label_admin_dropoff;
				break;

				case "contacts":
				$header_output .= ": ".$label_admin_contacts;
				break;

				case "judging_tables":
				$header_output .= ": ".$label_admin_tables;
				break;

				case "judging_flights":
				$header_output .= ": ".$assign_to;
				break;

				case "judging_scores":
				$header_output .= ": ".$label_admin_scores;
				break;

				case "judging_scores_bos":
				$header_output .= ": ".$label_admin_bos;
				break;

				case "style_types":
				$header_output .= ": ".$label_admin_style_types;
				break;

				case "special_best":
				$header_output .= ": ".$label_admin_custom_cat;
				break;

				case "special_best_data":
				$header_output .= ": ".$label_admin_custom_cat_data;
				break;

				case "sponsors":
				$header_output .= ": ".$label_admin_sponsors;
				break;

				case "count_by_style":
				$header_output .= ": ".$label_admin_entry_count;
				break;

				case "count_by_substyle":
				$header_output .= ": ".$label_admin_entry_count_sub;
				break;

				case "mods":
				$header_output .= ": ".$label_admin_custom_mods;
				break;

				case "checkin":
				$header_output .= ": ".$label_admin_check_in;
				break;

				case "make_admin":
				$header_output .= ": ".$label_admin_make_admin;
				break;

				case "register":
				$header_output .= ": ".$label_admin_register;
				break;

				case "upload":
				$header_output .= ": ".$label_admin_upload_img;
				break;

				case "upload_scoresheets":
				$header_output .= ": ".$label_admin_upload_doc;
				break;

				case "change_user_password":
				$header_output .= ": ".$label_admin_password;
				break;

				case "payments":
				$header_output .= ": ".$label_admin_payments;
				break;

				case "dates":
				$header_output .= ": Competition-Related Dates";
				break;

				case "non-judging":
				$header_output .= ": Sessions";
				break;

			}

		if     ($msg == "1") $output = sprintf("<strong>%s</strong>",$header_text_005);
		elseif ($msg == "2") $output = sprintf("<strong>%s</strong>",$header_text_006);
		elseif ($msg == "3") $output = sprintf("<strong>%s</strong> %s",$header_text_007,$header_text_008);
		elseif ($msg == "4") $output = sprintf("<strong>%s</strong>",$header_text_065);
		elseif ($msg == "5") $output = sprintf("<strong>%s</strong>",$header_text_044);
		elseif ($msg == "6") $output = sprintf("<strong>%s</strong>",$header_text_067);
		elseif ($msg == "7") {
			if (HOSTED) $output = sprintf("<strong>%s</strong>",$header_text_068);
			else $output = sprintf("<strong>%s</strong> %s",$header_text_069,$header_text_070);
			$output_extend = sprintf("<div class=\"alert alert-info hidden-print\"><span class=\"fa fa-lg fa-info-circle\"></span> <strong>%s</strong></div>",$header_text_071);

		}
		elseif ($msg == "8") $output = sprintf("<strong>%s</strong>",$header_text_072);
		elseif ($msg == "9") $output = sprintf("<strong>%s</strong>",$header_text_073);
		elseif ($msg == "10") $output = sprintf("<strong>%s</strong>",$header_text_074);
		elseif ($msg == "11") {
			$output = sprintf("%s",$header_text_075);
			$output_extend = "<p><a href=\"";
			if ($section == "step4") $output_extend .= "setup.php?section=step4";
			else $output_extend .= "index.php?section=admin&amp;go=dropoff";
			$output_extend .= sprintf("'>%s</a>&nbsp;&nbsp;&nbsp;<a href='",$label_yes);
			if ($section == "step4")	$output_extend .= "setup.php?section=step5";
			else $output_extend .= sprintf("index.php?section=admin\">%s</a>",$label_no);
			}
		elseif ($msg == "12") {
			$output = sprintf("%s",$header_text_076);
			$output_extend = "<p><a href=\"";
			if ($section == "step3") $output_extend .= "setup.php?section=step3";
			else $output_extend .= "index.php?section=admin&amp;go=judging";
			$output_extend .= sprintf("'>%s</a>&nbsp;&nbsp;&nbsp;<a href='",$label_yes);
			if ($section == "step3") $output_extend .= "setup.php?section=step4";
			else $output_extend .= sprintf("index.php?section=admin\">%s</a>",$label_no);
			}
		elseif ($msg == "13") $output = sprintf("<strong>%s</strong>",$header_text_077);
		elseif ($msg == "15") $output = sprintf("<strong>%s</strong> %s",$header_text_078,$header_text_008);
		elseif ($msg == "18") $output = sprintf("<strong>%s</strong> %s",$header_text_079,$header_text_008);
		elseif ($msg == "19") $output = sprintf("<strong>%s</strong> %s",$header_text_080,$header_text_008);
		elseif ($msg == "20") $output = sprintf("<strong>%s</strong>",$header_text_081);
		elseif ($msg == "21") $output = sprintf("<strong>%s</strong>",$header_text_082);
		elseif ($msg == "22") $output = sprintf("<strong>%s</strong>",$header_text_083);
		elseif ($msg == "23") $output = sprintf("<strong>%s</strong>",$header_text_084);
		elseif ($msg == "24") $output = sprintf("<strong>%s</strong> %s",$header_text_085,$header_text_008);
		elseif ($msg == "25") $output = sprintf("<strong>%s</strong>",$header_text_086);
		elseif ($msg == "26") $output = sprintf("<strong>%s</strong>",$header_text_087);
		elseif ($msg == "28") $output = sprintf("<strong>%s</strong>",$header_text_088);
		elseif ($msg == "29") $output = sprintf("<strong>%s</strong>",$header_text_089);
		elseif ($msg == "30") $output = sprintf("<strong>%s</strong> %s",$header_text_090,$header_text_008);
		elseif ($msg == "31") $output = sprintf("<strong>%s</strong>",$header_text_091);
		elseif ($msg == "32") $output = sprintf("<strong>%s</strong>",$header_text_092);
		elseif ($msg == "33") $output = sprintf("<strong>%s</strong>",$header_text_093);
		elseif ($msg == "34") $output = sprintf("<strong>%s</strong>",$header_text_110);
		elseif ($msg == "35") $output = sprintf("<strong>%s</strong>",$header_text_111);
		elseif ($msg == "36") $output = sprintf("<strong>%s</strong>",$header_text_115);
		elseif ($msg == "37") $output = sprintf("<strong>%s</strong> %s","Please Note!", "Since your style set has changed, by default, all styles have been marked as active. Review and update below the styles your competition will and will not accept.");
		elseif ($msg == "755") $output = sprintf("<strong>%s</strong> ",$header_text_094,$header_text_095);
		else $output = "";
	break;
}

if ($msg == "14") $output = sprintf("<strong>%s</strong>",$header_text_096);
if ($msg == "16") {
	$output = sprintf("<strong>%s</strong>",$header_text_097);
	$output_extend = sprintf("<div class=\"alert alert-danger\"><span class=\"fa fa-lg fa-exclamation-circle\"></span> <strong>%s</strong> %s</div><div class=\"alert alert-info\"><span class=\"fa fa-lg fa-info-circle\"></span> <strong>%s</strong>.</div>",$header_text_098,$header_text_099,$header_text_100);
	}
if ($msg == "17") $output = sprintf("<strong>%s</strong>",$header_text_101);
if ($msg == "27") $output = sprintf("<strong>%s</strong> %s",$header_text_102,$header_text_008);
if ($msg == "98") $output = sprintf("<strong>%s</strong>",$header_text_112);
if ($msg == "99") $output = sprintf("<strong>%s</strong>",$header_text_103);
?>