<?php 
/**
 * Module:      list.sec.php 
 * Description: This module displays user-related data including personal information,
 *              judging/stewarding information, and the participant's associated entries. 
 * 
 */
function pay_to_print($prefs_pay,$entry_paid) { 
	if (($prefs_pay == "Y") && ($entry_paid == "1")) return TRUE;
	elseif (($prefs_pay == "Y") && ($entry_paid == "0")) return FALSE;
	elseif ($prefs_pay == "N") return TRUE;
}
 
if (NHC) {
	function certificate_type($score) {
		if (($score >= 25) && ($score <= 29.9)) $return = "Bronze Certificate";
		elseif (($score >= 30) && ($score <= 37.9))	$return = "Silver Certificate";
		elseif (($score >= 38) && ($score <= 50))	$return = "Gold Certificate";
		return $return;
	}	
}
$judging_date = judging_date_return();
$delay = $_SESSION['prefsWinnerDelay'] * 3600;
$total_not_paid = total_not_paid_brewer($_SESSION['user_id']);

// Display messages if conditions are right
if (($action != "print") && ($msg != "default")) echo $msg_output;

// Add the top of the page MODS scripts 

// Add the help link for the page
if ($action != "print") echo "<p><span class=\"icon\"><img src=\"".$base_url."images/help.png\"  /></span><a id=\"modal_window_link\" href=\"http://help.brewcompetition.com/files/my_info.html\" title=\"BCOE&amp;M Help: My Info and Entries\">My Info and Entries Help</a></p>";

// Call the brewer's info information
include (SECTIONS.'brewer_info.sec.php');

// Call the brewer's entry information
include (SECTIONS.'brewer_entries.sec.php');  
?>