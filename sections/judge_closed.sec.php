<?php 
/**
 * Module:      judge_closed.sec.php 
 * Description: This module houses the information that will be displayded
 *              once judging dates have passed. 
 * 
 */

// Redirect if directly accessed
if ((!isset($_SESSION['prefs'.$prefix_session])) || ((isset($_SESSION['prefs'.$prefix_session])) && (!isset($base_url)))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

$header_jc_1 = "";
$page_info_jc_1 = "";

$header_jc_1 .= sprintf("<p class=\"lead\">%s %s.</p>",$judge_closed_000, $_SESSION['contestName']);
if ($_SESSION['prefsProEdition'] == 1) $page_info_jc_1 .= sprintf("<p class=\"lead\"><small>%s <strong class=\"text-success\">%s</strong> %s</small></p>",$judge_closed_001,get_participant_count('default',$filter),$judge_closed_003);

else $page_info_jc_1 .= sprintf("<p class=\"lead\"><small>%s <strong class=\"text-success\">%s</strong> %s <strong class=\"text-success\">%s</strong> %s</small></p>",$judge_closed_001,get_entry_count('received',$filter),$judge_closed_002,get_participant_count('default',$filter),$judge_closed_003);

echo $header_jc_1;
echo $page_info_jc_1;
?>