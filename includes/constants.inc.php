<?php


// -------------------------- Theme file names and display name array --------------------------
// The first item is the the CSS file name (without .css)
// The second item is the display name for use in Site Preferences
// The file name will be stored in the preferences DB table row called prefsTheme and called by all pages

$theme_name = array("default|BCOE&amp;M Default (Gray)","bruxellensis|Bruxellensis (Blue-Gray)", "claussenii|Claussenii (Green)");

// -------------------------- Countries List ---------------------------------------------------
// Array of countries to utilize when users sign up and for competition info
// Replaces countries DB table for better performance
$countries = array("United States","Australia","Canada","Ireland","United Kingdom","Afghanistan","Albania","Algeria","American Samoa","Andorra","Angola","Anguilla","Antarctica","Antigua and Barbuda","Argentina","Armenia","Aruba","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bermuda","Bhutan","Bolivia","Bosnia and Herzegovina","Botswana","Bouvet Island","Brazil","British Indian Ocean Territory","Brunei Darussalam","Bulgaria","Burkina Faso","Burundi","Cambodia","Cameroon","Cape Verde","Cayman Islands","Central African Republic","Chad","Chile","China","Christmas Island","Cocos (Keeling) Islands","Colombia","Comoros","Congo","Congo, The Democratic Republic of The","Cook Islands","Costa Rica","Cote D'ivoire","Croatia","Cuba","Cyprus","Czech Republic","Denmark","Djibouti","Dominica","Dominican Republic","Easter Island","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Ethiopia","Falkland Islands (Malvinas)","Faroe Islands","Fiji","Finland","France","French Guiana","French Polynesia","French Southern Territories","Gabon","Gambia","Georgia","Germany","Ghana","Gibraltar","Greece","Greenland","Grenada","Guadeloupe","Guam","Guatemala","Guinea","Guinea-bissau","Guyana","Haiti","Heard Island and Mcdonald Islands","Honduras","Hong Kong","Hungary","Iceland","India","Indonesia","Iran","Iraq","Israel","Italy","Jamaica","Japan","Jordan","Kazakhstan","Kenya","Kiribati","Korea, North","Korea, South","Kosovo","Kuwait","Kyrgyzstan","Laos","Latvia","Lebanon","Lesotho","Liberia","Libyan Arab Jamahiriya","Liechtenstein","Lithuania","Luxembourg","Macau","Macedonia","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Martinique","Mauritania","Mauritius","Mayotte","Mexico","Micronesia, Federated States of","Moldova, Republic of","Monaco","Mongolia","Montenegro","Montserrat","Morocco","Mozambique","Myanmar","Namibia","Nauru","Nepal","Netherlands","Netherlands Antilles","New Caledonia","New Zealand","Nicaragua","Niger","Nigeria","Niue","Norfolk Island","Northern Mariana Islands","Norway","Oman","Pakistan","Palau","Palestinian Territory","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Pitcairn","Poland","Portugal","Puerto Rico","Qatar","Reunion","Romania","Russia","Russia","Rwanda","Saint Helena","Saint Kitts and Nevis","Saint Lucia","Saint Pierre and Miquelon","Saint Vincent and The Grenadines","Samoa","San Marino","Sao Tome and Principe","Saudi Arabia","Senegal","Serbia and Montenegro","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","South Georgia/South Sandwich Islands","Spain","Sri Lanka","Sudan","Suriname","Svalbard and Jan Mayen","Swaziland","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania, United Republic of","Thailand","Timor-leste","Togo","Tokelau","Tonga","Trinidad and Tobago","Tunisia","Turkey","Turkmenistan","Turks and Caicos Islands","Tuvalu","Uganda","Ukraine","United Arab Emirates","United States Minor Outlying Islands","Uruguay","Uzbekistan","Vanuatu","Vatican City","Venezuela","Vietnam","Virgin Islands, British","Virgin Islands, U.S.","Wallis and Futuna","Western Sahara","Yemen","Zambia","Zimbabwe","Other");

$registration_open = open_or_closed(strtotime("now"),$row_contest_dates['contestRegistrationOpen'],$row_contest_dates['contestRegistrationDeadline']);
$entry_window_open = open_or_closed(strtotime("now"),$row_contest_dates['contestEntryOpen'],$row_contest_dates['contestEntryDeadline']);
$judge_window_open = open_or_closed(strtotime("now"),$row_contest_dates['contestJudgeOpen'],$row_contest_dates['contestJudgeDeadline']);
$dropoff_window_open = open_or_closed(strtotime("now"),$row_contest_dates['contestDropoffOpen'],$row_contest_dates['contestDropoffDeadline']);
$shipping_window_open = open_or_closed(strtotime("now"),$row_contest_dates['contestShippingOpen'],$row_contest_dates['contestShippingDeadline']);
 
$reg_open = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestRegistrationOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time");
$reg_closed = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestRegistrationDeadline'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time");
$reg_open_sidebar = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestRegistrationOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time");
$reg_closed_sidebar = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestRegistrationDeadline'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time");

$entry_open = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestEntryOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time"); ;
$entry_closed = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestEntryDeadline'], $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], "long", "date-time");
$entry_open_sidebar = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestEntryOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time"); ;
$entry_closed_sidebar = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestEntryDeadline'], $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], "short", "date-time");

$dropoff_open = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestDropoffOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-no-gmt"); ;
$dropoff_closed = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestDropoffDeadline'], $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], "long", "date-no-gmt");
$dropoff_open_sidebar = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestDropoffOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-no-gmt"); ;
$dropoff_closed_sidebar = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestDropoffDeadline'], $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], "short", "date-no-gmt");

$shipping_open = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestShippingOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-no-gmt"); ;
$shipping_closed = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestShippingDeadline'], $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], "long", "date-no-gmt");
$shipping_open_sidebar = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestShippingOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-no-gmt"); ;
$shipping_closed_sidebar = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestShippingDeadline'], $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], "short", "date-no-gmt");

$judge_open = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestJudgeOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time"); ;
$judge_closed = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestJudgeDeadline'], $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], "long", "date-time");

$judge_open_sidebar = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestJudgeOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time"); ;
$judge_closed_sidebar = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestJudgeDeadline'], $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], "short", "date-time");

$currency = explode("^",currency_info($_SESSION['prefsCurrency'],1));
$currency_symbol = $currency[0];
$currency_code = $currency[1];

$total_entries = $totalRows_entry_count;
if (open_limit($totalRows_entry_count,$row_limits['prefsEntryLimit'],$registration_open)) $comp_entry_limit = TRUE; else $comp_entry_limit = FALSE;

$total_paid = total_paid();
if (open_limit($total_paid,$row_limits['prefsEntryLimitPaid'],$registration_open)) $comp_paid_entry_limit = TRUE; else $comp_paid_entry_limit = FALSE;

if (!empty($row_limits['prefsEntryLimit'])) $comp_entry_limit_near = ($row_limits['prefsEntryLimit']*.9); else $comp_entry_limit_near = "";
if ((!empty($row_limits['prefsEntryLimit'])) && (($total_entries > $comp_entry_limit_near) && ($total_entries < $row_limits['prefsEntryLimit']))) $comp_entry_limit_near_warning = TRUE; else $comp_entry_limit_near_warning = FALSE;

$current_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], time(), $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "system", "date");
$current_time = getTimeZoneDateTime($_SESSION['prefsTimeZone'], time(), $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "system", "time");

// User constants
if (isset($_SESSION['loginUsername']))  {
	
	$logged_in = TRUE;
	$logged_in_name = $_SESSION['loginUsername'];
	
	if ($_SESSION['userLevel'] <= "1") {
		if ($section == "admin") $link_admin = "#"; 
		else $link_admin = "";
		$admin_user = TRUE;
	}
	
	else $admin_user = FALSE;
	
}

else $logged_in = FALSE;

// DataTables Default Values

$output_datatables_bPaginate = "true";
$output_datatables_sPaginationType = "full_numbers";
$output_datatables_bLengthChange = "true";
$output_datatables_iDisplayLength = round($_SESSION['prefsRecordPaging']);
if ($action == "print") $output_datatables_sDom = "it";
else $output_datatables_sDom = "rftp";
$output_datatables_bStateSave = "false";
$output_datatables_bProcessing = "false";

$color = "#eeeeee";
$color1 = "#e0e0e0";
$color2 = "#eeeeee";



?>