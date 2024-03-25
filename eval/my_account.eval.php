<?php 
/**
 * Add judging dashboard text and button to My Account
 */

// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && (!isset($base_url)))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

if (((in_array($label_judge,$assignment_array)) && ($_SESSION['brewerJudge'] == "Y")) && (time() >= $row_judging_prefs['jPrefsJudgingOpen'])) {
	if ((time() > $row_judging_prefs['jPrefsJudgingOpen']) && (time() < $row_judging_prefs['jPrefsJudgingClosed'])) {
		$primary_page_info .= "<div class=\"alert alert-info\">";
		$primary_page_info .= "<p>";
		$primary_page_info .= sprintf("<strong>%s</strong> ",$brewer_info_013);
		$primary_page_info .= sprintf("%s",$brewer_info_014);
		if (time() < $row_judging_prefs['jPrefsJudgingOpen']) $primary_page_info .= sprintf("%s %s %s.",$evaluation_info_020,$judging_evals_open,$evaluation_info_021);
		$primary_page_info .= "</p>";
		$primary_page_info .= "</div>";
	}
	$user_edit_links .= "<div style=\"margin-right: 5px;\" class=\"btn-group hidden-print\" role=\"group\"><a class=\"btn btn-block btn-primary\" href=\"".build_public_url("evaluation","default","default","default",$sef,$base_url)."\"><i class=\"fa fa-gavel\"></i> ".$label_judging_dashboard."</a></div>";
}
?>