<?php

/**
 * Module:      headers.inc.php
 * Description: This module defines all header text for the application based
 *              upon URL variables.
 * 
 */

if ($row_judging_prefs['jPrefsQueued'] == "N") $assign_to = "Flights"; else $assign_to = "Tables";

$header_output = "";
$output = "";
$output_extend = "";

switch($section) {
	
	case "default":
	$header_output = $row_contest_info['contestName'];
	if ($msg == "success") { 
		$output = "Setup was successful.";
		$output_extend = "<p class='info'>You are now logged in and ready to further customize your competition's site.</p>"; 
	}
	
	if ($msg == "chmod") { 
		$output = "Setup was successful. However, your config.php permissions file could not be changed.";
		$output_extend = "<div class='error'>It is highly recommended that you change the server permissions (chmod) on the config.php file to 555. To do this, you will need to access the file on your server.</div>"; 
		if (($setup_free_access == TRUE) && ($action != "print")) $output_extend .= "<div class='error'>Additionally, the &#36;setup_free_access variable in config.php is currently set to TRUE. For security reasons, the setting should returned to FALSE. You will need to edit config.php directly and re-upload to your server to do this.</div>"; 
	}
	
	if     ($msg == "1") $output = "Info added successfully."; 
	elseif ($msg == "2") $output = "Info edited successfully.";
	elseif ($msg == "3") $output = "There was an error. Please try again.";
	break;

	case "user":
	$header_output = "Change "; 
	if ($action == "username") $header_output .= "Email Address"; 
	if ($action == "password") $header_output .= "Password";
	if     ($msg == "1") $output = "The email address provided is already in use, please provide another email address.";
	elseif ($msg == "2") $output = "There was a problem with the last request, please try again.";
	elseif ($msg == "3") $output = "Your current password was incorrect. Please try again.";
	elseif ($msg == "4") $output = "Please provide an email address.";
	else $output = "";
	break;
	
	case "register":
	$header_output = "Register";
	if     ($msg == "1") $output = "Sorry, there was a problem with your last login attempt. Please try again.";
	elseif ($msg == "2") { $output = "Sorry, the user name you entered is already in use."; $output_extend = "<p>Perhaps you have already created an account? If so, <a href=\"index.php?section=login\">log in here</a>.</p>"; }
	elseif ($msg == "3") $output = "The user name provided is not a valid email address. Please enter a valid email address.";
	elseif ($msg == "4") $output = "The characters you entered in the CAPTCHA section below were not correct. Please try again.";
	else $output = "";
	break;

	case "pay":
	$header_output = "Pay My Fees";
	if     ($msg == "1") $output = "Info added successfully."; 
	elseif ($msg == "2") $output = "Info edited successfully.";
	elseif ($msg == "3") $output = "There was an error. Please try again.";
	elseif     ($msg == "10") $output = "Your online payment has been received. Please make sure to print the receipt and attach it to one of your entries as proof of payment."; 
	elseif ($msg == "11") $output = "Your online payment has been cancelled.";
	elseif ($msg == "12") $output = "The code has been verified.";
	elseif ($msg == "13") $output = "Sorry, the code you entered was incorrect.";
	else $output = "";
	break;
	
	case "login":
	if ($action == "forgot") $header_output = "Reset Password"; 
	elseif ($action == "logout") $header_output = "Logged Out"; 
	else $header_output = "Log In";
	if     ($msg == "1") { $output = "Sorry, there was a problem with your last login attempt."; $output_extend = "<p>Please make sure your email address and password are correct.</p>"; }
	elseif ($msg == "2") { $output = "Your password has been randomly generated and reset to ".$go."."; $output_extend = "<p>You can now log in using your current username and the new password above.</p>"; }
	elseif ($msg == "3") $output = "You have been logged out. Log in again?"; 
	elseif ($msg == "4") $output = "Your verification question does not match what is in the database. Please try again."; 
	else $output = ""; 
	break;
	
	case "entry":
	if     ($msg == "1") $output = "Info added successfully."; 
	elseif ($msg == "2") $output = "Info edited successfully.";
	elseif ($msg == "3") $output = "There was an error. Please try again.";
	else $output = "";
	$header_output = $row_contest_info['contestName']." Entry Info";
	break;
	
	case "sponsors":
	if     ($msg == "1") $output = "Info added successfully."; 
	elseif ($msg == "2") $output = "Info edited successfully.";
	elseif ($msg == "3") $output = "There was an error. Please try again.";
	else $output = "";
	$header_output = $row_contest_info['contestName']." Sponsors";
	break;
	
	case "rules":
	if     ($msg == "1") $output = "Info added successfully."; 
	elseif ($msg == "2") $output = "Info edited successfully.";
	elseif ($msg == "3") $output = "There was an error. Please try again.";
	else $output = "";
	$header_output = $row_contest_info['contestName']." Rules";
	break;
	
	case "volunteers":
	if     ($msg == "1") $output = "Info added successfully."; 
	elseif ($msg == "2") $output = "Info edited successfully.";
	elseif ($msg == "3") $output = "There was an error. Please try again.";
	else $output = "";
	$header_output = $row_contest_info['contestName']." Volunteer Info";
	break;	
	
	case "past_winners":
	if     ($msg == "1") $output = "Info added successfully."; 
	elseif ($msg == "2") $output = "Info edited successfully.";
	elseif ($msg == "3") $output = "There was an error. Please try again.";
	else $output = "";
	$header_output = "Past Winners";
	break;
	
	case "contact":
	$header_output = "Contact";
	if ($msg == "1") {
	mysql_select_db($database, $brewing);
	$query_contact = sprintf("SELECT contactFirstName,contactLastName,contactPosition FROM $contacts_db_table WHERE id='%s'", $id);
	$contact = mysql_query($query_contact, $brewing) or die(mysql_error());
	$row_contact = mysql_fetch_assoc($contact);
	$output = "Your message has been sent to ".$row_contact['contactFirstName']." ".$row_contact['contactLastName'].", ".$row_contact['contactPosition'].".";
	}
	elseif ($msg == "2") $output = "The characters you entered in the CAPTCHA section below were not correct. Please try again.";
	else $output = "";
	break;
	
	case "brew":
	if ($action == "add") $header_output = "Add an Entry"; 
	else $header_output = "Edit an Entry";
	if ($msg == "1") $output = "This entry's style requires more information. Please specify its special ingredients or classic style. If you do not provide this information, your entry will be deleted from the system after 24 hours.";
	elseif ($msg == "2") $output = "Info edited successfully.";
	elseif ($msg == "3") $output = "There was an error. Please try again.";
	else $output = "";
	break;
	
	case "brewer":
	if     ($msg == "1") $output = "Info added successfully."; 
	elseif ($msg == "2") $output = "Info edited successfully.";
	elseif ($msg == "3") $output = "There was an error. Please try again.";
	else $output = "";
	if ($action == "add") $header_output = "Step 2: Registrant Info"; 
	else $header_output = "Edit Registrant Info";
	break;
	
	case "judge":
	$header_output = "Judge Info"; 
	if ($msg == "1") $output = "Info added successfully."; 
	elseif ($msg == "2") $output = "Info edited successfully.";
	elseif ($msg == "3") $output = "There was an error. Please try again.";
	else $output = "";
	break;

	case "list":
	$header_output = "My Info and Entries";
	if ($msg == "1") $output = "Info added successfully."; 
	elseif ($msg == "2") $output = "Info edited successfully."; 
	elseif ($msg == "3") $output = "Your email address has been updated."; 
	elseif ($msg == "4") $output = "Your password has been updated."; 
	elseif ($msg == "5") $output = "Info deleted successfully."; 
	elseif ($msg == "6") $output = "You should verify all your entries imported using BeerXML."; 
	elseif ($msg == "7") $output = "You have registered as a judge or steward. Thank you."; 
	else $output = "";
	break;
	
	case "step0":
	$header_output = "Set Up: Install Database";
	break;

	case "step1":
	$header_output = "Set Up: Create an Admin User Account";
	if ($msg == "1") $output = "Please provide an email address.";
	break;
	
	case "step2":
	$header_output = "Set Up: Enter the Admin User's Info";
	break;
	
	case "step3":
	$header_output = "Set Up: Define Preferences";
	break;
	
	case "step4":
	$header_output = "Set Up: Enter Your Competition Info"; 
	break;
	
	case "step5":
	$header_output = "Set Up: Enter Judging Location(s) and Date(s)";
	break;

	case "step6":
	$header_output = "Set Up: Enter Drop-Off Locations";
	break;
	
	case "step7":
	$header_output = "Set Up: Designate Accepted Styles";
	break;
	
	case "step8":
	$header_output = "Set Up: Competition Organization Preferences";
	break;

	case "beerxml":
	include(DB.'styles.db.php');
	$header_output = "Import an Entry Using BeerXML";
	if ($msg == "default") { 
	if ($totalRows_styles < 98) $output_extend = "<div class='info'>Our competition accepts ".$totalRows_styles." of the 98 BJCP sub-styles. To make sure each of your entries are entered into one of the accepted categories, you should verify each entry.</div>"; else $output_extend = ""; 
	}
	if ($msg == "1") $output = "Your entry has been recorded.";
	if ($msg == "2") $output = "Your entry has been confirmed.";
	break;

	case "admin":
	if ($action != "print") $header_output = "Administration"; 
	else $header_output = $row_contest_info['contestName'];
	
		switch($go) {
		
			case "judging":
			$header_output .= ": Judging";
			break;
			
			case "stewards":
			$header_output .= ": Stewarding";
			break;
			
			case "participants":
			$header_output .= ": Participants";
			break;
			
			case "entries":
			$header_output .= ": Entries";
			break;
			
			/* placeholder for later
			case "contest_info":
			$header_output .= ""; 
			break;
			*/
			
			case "preferences":
			$header_output .= ": Preferences";
			break;
			
			case "judging_preferences":
			$header_output .= ": Preferences";
			break;
			
			case "archive":
			$header_output .= ": Archives";
			break;
			
			case "styles":
			$header_output .= ": Styles";
			break;
			
			case "dropoff":
			$header_output .= ": Entries";
			break;
			
			case "contacts":
			$header_output .= ": Contacts";
			break;
			
			case "judging_tables":
			$header_output .= ": Tables";
			break;
			
			case "judging_flights":
			$header_output .= ": ".$assign_to;
			break;
			
			case "judging_scores":
			$header_output .= ": Scoring";
			break;
			
			case "judging_scores_bos":
			$header_output .= ": Best of Show";
			break;
			
			case "style_types":
			$header_output .= ": Style Types";
			break;
			
			case "special_best":
			$header_output .= ": Custom Winning Categories";
			break;
			
			case "special_best_data":
			$header_output .= ": Custom Winning Categories";
			break;
		}
	
	if     ($msg == "1") $output = "Info added successfully."; 
	elseif ($msg == "2") $output = "Info edited successfully.";
	elseif ($msg == "3") $output = "There was an error. Please try again.";
	elseif ($msg == "5") $output = "Info deleted successfully.";
	elseif ($msg == "6") $output = "The suffix you entered is already in use, please enter a different one."; 
	elseif ($msg == "7") $output = "Archives created successfully. Click the archive name to view.";
	elseif ($msg == "8") $output = "Archive \"".$filter."\" deleted."; 
	elseif ($msg == "9") $output = "The records have been updated.";
	elseif ($msg == "10") $output = "The username you have entered is already in use.";
	elseif ($msg == "11") { 
		$output = "Add another drop-off location?"; 
		$output_extend = "<p><a href='"; 
		if ($section == "step4") $output_extend .= "setup.php?section=step4"; else $output_extend .= "index.php?section=admin&amp;go=dropoff"; 
		$output_extend .="'>Yes</a>&nbsp;&nbsp;&nbsp;<a href='"; if ($section == "step4")
		$output_extend .= "setup.php?section=step5"; else $output_extend .= "index.php?section=admin'>No</a>"; 
		}
	elseif ($msg == "12") { 
		$output = "Add another judging location, date, or time?"; 
		$output_extend = "<p><a href='"; 
		if ($section == "step3") $output_extend .= "setup.php?section=step3"; else $output_extend .= "index.php?section=admin&amp;go=judging"; 
		$output_extend .="'>Yes</a>&nbsp;&nbsp;&nbsp;<a href='"; 
		if ($section == "step3") $output_extend .= "setup.php?section=step4"; else $output_extend .= "index.php?section=admin'>No</a>"; 
		}
	elseif ($msg == "13") $output = "The table that was just defined does not have any associated styles.";
	elseif ($msg == "15") $output = "One or more pieces of required data are missing - outlined in red below. Please check your data and enter again.";
	else $output = "";
	break;
	
	

}
if ($msg == "14") $output = "Judging Numbers have been regenerated using the method you specified.";
$msg_output = "<div class='error'>".$output."</div>".$output_extend;


?>