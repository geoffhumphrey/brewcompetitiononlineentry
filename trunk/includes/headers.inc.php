<?php
// Output headers and messages based upon the $section URL variable.

switch($section) {
	
	case "default":
	$header_output = $row_contest_info['contestName'];
	if ($msg == "success") $output = "Setup was successful. Use the Admin menu above to further customize your competition site."; 
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
	else $output = "";
	break;

	case "pay":
	$header_output = "Pay My Fees";
	if     ($msg == "1") $output = "Your PayPal payment has been received.<br>Please make sure to print the receipt from PayPal and attach it to one of your entries as proof of payment."; 
	elseif ($msg == "2") $output = "Your PayPal payment has been cancelled.";
	else $output = "";
	break;
	
	case "login":
	if ($action == "forgot") $header_output = "Reset Password"; 
	elseif ($action == "logout") $header_output = "Logged Out"; 
	else $header_output = "Log In";
	if     ($msg == "1") { $output = "Sorry, there was a problem with your last login attempt."; $output_extend = "<p>Please make sure your email address and password are correct.</p>"; }
	elseif ($msg == "2") { $output = "Your password has been randomly generated and reset to ".$go."."; $output_extend = "<p>You can now log in using your current username and the new password above.</p>"; }
	elseif ($msg == "3") $output = "You have been logged out. Log in again?"; 
	elseif ($msg == "4") $output = "Your verification question does not match what's in the database. Please try again."; 
	else $output = ""; 
	break;
	
	case "entry":
	$header_output = $row_contest_info['contestName']." Entry Information";
	break;
	
	case "sponsors":
	$header_output = $row_contest_info['contestName']." Sponsors";
	break;
	
	case "rules":
	$header_output = $row_contest_info['contestName']." Rules";
	break;
	
	case "past_winners":
	$header_output = "Past Winners";
	break;
	
	case "contact":
	$header_output = "Contact";
	if ($msg == "1") {
	mysql_select_db($database, $brewing);
	$query_contact = sprintf("SELECT contactFirstName,contactLastName,contactPosition FROM contacts WHERE id='%s'", $id);
	$contact = mysql_query($query_contact, $brewing) or die(mysql_error());
	$row_contact = mysql_fetch_assoc($contact);
	$output = "Your message has been sent to ".$row_contact['contactFirstName']." ".$row_contact['contactLastName'].", ".$row_contact['contactPosition'].".";
	}
	elseif ($msg == "2") $output = "The characters you entered in the CAPTCHA section below were not correct. Please try again.";
	else $output = "";
	break;
	
	case "brew":
	if ($action == "add") $header_output = "Add an Entry"; 
	else $header_output = "Edit a Entry";
	if ($msg == "1") $output = "This entry's style requires more information. Please specify its special ingredients or classic style.";
	else $output = "";
	break;
	
	case "brewer":
	if ($action == "add") $header_output = "Step 2: Registrant Information"; 
	else $header_output = "Edit Registrant Information";
	if ($msg == "1") $output = "Information added successfully."; 
	break;
	
	case "judge":
	$header_output = "Judge Information"; 
	if ($msg == "1") $output = "Information added successfully."; 
	break;

	case "list":
	$header_output = "My Information and Entries";
	if ($msg == "1") $output = "Information added successfully."; 
	elseif ($msg == "2") $output = "Information edited successfully."; 
	elseif ($msg == "3") $output = "Your email address has been updated."; 
	elseif ($msg == "4") $output = "Your password has been updated."; 
	elseif ($msg == "5") $output = "Information deleted successfully."; 
	elseif ($msg == "6") $output = "You should verify all your entries imported using BeerXML."; 
	elseif ($msg == "7") $output = "You have registered as a judge or steward. Thank you."; 
	else $output = "";
	break;

	case "step1":
	$header_output = "Set Up: Define Preferences";
	break;
	
	case "step2":
	$header_output = "Set Up: Enter Your Competition Information"; 
	break;
	
	case "step3":
	$header_output = "Set Up: Enter Judging Date(s)";
	break;

	case "step4":
	$header_output = "Set Up: Enter Drop-Off Locations";
	break;
	
	case "step5":
	$header_output = "Set Up: Designate Accepted Styles";
	break;
	
	case "step6":
	$header_output = "Set Up: Create an Admin User Account";
	if ($msg == "1") $output = "Please provide an email address.";
	break;
	
	case "step7":
	$header_output = "Set Up: Enter the Admin User's Info";
	break;

	case "beerxml":
	$header_output = "Import an Entry Using BeerXML";
	if ($msg != "default") { 
	if ($totalRows_styles < 98) $output_extend = "<div class='info'>Our competition accepts ".$totalRows_styles." of the 98 BJCP sub-styles. To make sure each of your entries are entered into one of the accepted categories, you should verify each entry.</div>"; else $output_extend = ""; 
	$output = $msg.". You should verify each of your entries for accuracy and to add additional information."; $output_extend .= "<p>To verify your entires, <a href='index.php?section=list&amp;msg=6'>view your list of entries</a> and click edit for each that was imported.";
	}
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
			
			case "preferences":
			$header_output .= "";
			break;
			*/
			
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
		}
	
	if     ($msg == "1") $output = "Information added successfully."; 
	elseif ($msg == "2") $output = "Information edited successfully.";
	elseif ($msg == "3") $output = "There was an error. Please try again.";
	elseif ($msg == "5") $output = "Information deleted successfully.";
	elseif ($msg == "6") $output = "The suffix you entered is already in use, please enter a delseifferent one."; 
	elseif ($msg == "7") $output = "Archives created successfully. Click the archive name to view.";
	elseif ($msg == "8") $output = "Archive \"".$filter."\" deleted."; 
	elseif ($msg == "9") $output = "The records have been updated.";
	elseif ($msg == "10") $output = "The username you have entered is already in use.";
	elseif ($msg == "11") { $output = "Add another drop-off location?"; $output_extend = "<p><a href='"; if ($section == "step4") $output_extend .= "setup.php?section=step4"; else $output_extend .= "index.php?section=admin&amp;go=dropoff"; $output_extend .="'>Yes</a>&nbsp;&nbsp;&nbsp;<a href='"; if ($section == "step4") $output_extend .= "setup.php?section=step5"; else $output_extend .= "index.php?section=admin'>No</a>"; }
	elseif ($msg == "12") { $output = "Add another judging location, date, or time?"; $output_extend = "<p><a href='"; if ($section == "step3") $output_extend .= "setup.php?section=step3"; else $output_extend .= "index.php?section=admin&amp;go=judging"; $output_extend .="'>Yes</a>&nbsp;&nbsp;&nbsp;<a href='"; if ($section == "step3") $output_extend .= "setup.php?section=step4"; else $output_extend .= "index.php?section=admin'>No</a>"; }
	else $output = "";
	break;

}
$msg_output = "<div class='error'>".$output."</div>".$output_extend;
?>

