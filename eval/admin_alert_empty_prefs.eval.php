<?php

// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && (!isset($base_url)))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

if ((empty($row_judging_prefs['jPrefsJudgingOpen'])) || (empty($row_judging_prefs['jPrefsJudgingClosed'])) || (empty($row_judging_prefs['jPrefsScoresheet']))) {

	$alert_empty_prefs = "<div class=\"alert alert-danger\">";
	$alert_empty_prefs .= "<div>";
	$alert_empty_prefs .= sprintf("<p><strong><i class=\"fa fa-exclamation-circle\"></i> %s</strong> Judging/Competition Organization Preferences <strong>have not been set</strong> for the following:</p>",$label_attention);
	$alert_empty_prefs .= "<ul>";
	if (empty($row_judging_prefs['jPrefsScoresheet'])) $alert_empty_prefs .= "<li>Entry Evaluation Scoresheet (default: Full Scoresheet)</li>";
	if (empty($row_judging_prefs['jPrefsJudgingOpen'])) $alert_empty_prefs .= "<li>Judging Open Date and Time (default: the earliest judging session's start date/time).</li>";
	if (empty($row_judging_prefs['jPrefsJudgingClosed'])) $alert_empty_prefs .= "<li>Judging Close Date and Time (default: the last judging session's start date/time + 8 hours).</li>";
	$alert_empty_prefs .= "</ul>";
	$alert_empty_prefs .= "</div>";
	$alert_empty_prefs .= "<div>";
	$alert_empty_prefs .= sprintf("<p>Access the <a href=\"%s\">Judging/Competition Organization Preferences</a> to customize these settings. Otherwise, the default settings will be used.</p>",$base_url."index.php?section=admin&amp;go=judging_preferences");
	$alert_empty_prefs .= "</div>";
	$alert_empty_prefs .= "</div>";

	echo $alert_empty_prefs;
	
}
?>