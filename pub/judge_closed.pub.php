<?php 
/**
 * Module:      judge_closed.sec.php 
 * Description: This module houses the information that will be displayded
 *              once judging dates have passed. 
 * 
 */

/*
// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && (!isset($base_url)))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}
*/

$header_jc_1 = "";
$page_info_jc_1 = "";

if (!empty($archive_alert_display)) {

    // Full button block on smaller viewports
    $archive_alert_button = "<div class=\"d-grid mb-3\">";
    $archive_alert_button .= "<button class=\"btn btn-dark btn-lg d-block d-sm-block d-md-none\" type=\"button\" data-bs-toggle=\"offcanvas\" data-bs-target=\"#archive-list\" aria-controls=\"archive-list\">";
    $archive_alert_button .= "<i class=\"fa fa-trophy me-2 text-gold\"></i>";
    $archive_alert_button .= ucwords(rtrim($past_winners_text_000, ":"));
    $archive_alert_button .= "</button>";
    $archive_alert_button .= "</div>";

    // Smaller button flush right on larger view ports    
    $archive_alert_button .= "<button class=\"btn btn-dark btn-lg float-end ms-4 d-none d-sm-none d-md-block\" type=\"button\" data-bs-toggle=\"offcanvas\" data-bs-target=\"#archive-list\" aria-controls=\"archive-list\">";
    $archive_alert_button .= "<i class=\"fa fa-trophy me-2 text-gold\"></i>";
    $archive_alert_button .= ucwords(rtrim($past_winners_text_000, ":"));
    $archive_alert_button .= "</button>";
    
    $header_jc_1 .= "<div class=\"d-print-none\">";
    $header_jc_1 .= $archive_alert_button;
    $header_jc_1 .= sprintf("<p class=\"lead mt-3\">%s %s.</p>",$judge_closed_000, $_SESSION['contestName']);
    $header_jc_1 .= $archive_alert_display;
    $header_jc_1 .= "</div>";

}

else $header_jc_1 .= sprintf("<p class=\"lead mt-3\">%s %s.</p>",$judge_closed_000, $_SESSION['contestName']);

if ($_SESSION['prefsProEdition'] == 1) $page_info_jc_1 .= sprintf("<p class=\"lead\"><small>%s <strong class=\"text-success\">%s</strong> %s</small></p>",$judge_closed_001,get_participant_count('default',$filter),$judge_closed_003);
else $page_info_jc_1 .= sprintf("<p class=\"lead\"><small>%s <strong class=\"text-success\">%s</strong> %s <strong class=\"text-success\">%s</strong> %s</small></p>",$judge_closed_001,get_entry_count('received',$filter),$judge_closed_002,get_participant_count('default',$filter),$judge_closed_003);

echo $header_jc_1;
echo $page_info_jc_1;
?>