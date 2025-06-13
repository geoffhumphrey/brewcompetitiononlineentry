<?php
/** -------------------------- Languages Available ----------------------------------------------
 * Array of languages available for translation
 * Associative array of available translation languages.
 * First in the pair is the label name (should be in native language)
 * Second in the pair is the official WWW3 language tag, exactly as it appears there
 * This WWW3 language tag should be the beginining of the language file (e.g., en-US.lang.php)
 * See https://www.loc.gov/standards/iso639-2/php/code_list.php
 * located in its parent language subfolder in the lang folder (e.g., /lang/en/)
 * More at https://www.w3.org/International/articles/language-tags/
 *
 */

$languages = array(
    "pt-BR" => "Brazilian Portuguese",
    "cs-CZ" => "Czech",
    "en-US" => "English (US)",
    "fr-FR" => "French",
    "es-419" => "Spanish (Latin America)"
);

/** -------------------------- Theme File names and  Display Name -------------------------------
 * The first item is the the CSS file name (without .css)
 * The second item is the display name for use in Site Preferences
 * The file name will be stored in the preferences DB table row called prefsTheme and called by all pages
 */

$theme_name = array(
    "default" => "BCOE&amp;M Default (Gray)",
    "bruxellensis" => "Bruxellensis (Blue-Gray)",
    // "claussenii" => "Claussenii (Green)",
    // "naardenensis" => "Naardenensis (Teal)"
);

// Failsafe fallback if prefsTheme session var value is a deprecated theme.
if (($_SESSION['prefsTheme'] == "claussenii") || ($_SESSION['prefsTheme'] == "naardenensis")) $_SESSION['prefsTheme'] == "default";

// -------------------------- Countries List ----------------------------------------------------
// Array of countries to utilize when users sign up and for competition info
// Replaces countries DB table for better performance
$countries = array("United States","Australia","Canada","Ireland","United Kingdom","Afghanistan","Albania","Algeria","American Samoa","Andorra","Angola","Anguilla","Antarctica","Antigua and Barbuda","Argentina","Armenia","Aruba","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bermuda","Bhutan","Bolivia","Bosnia and Herzegovina","Botswana","Bouvet Island","Brazil","British Indian Ocean Territory","Brunei Darussalam","Bulgaria","Burkina Faso","Burundi","Cambodia","Cameroon","Cape Verde","Cayman Islands","Central African Republic","Chad","Chile","China","Christmas Island","Cocos (Keeling) Islands","Colombia","Comoros","Congo","Congo, The Democratic Republic of The","Cook Islands","Costa Rica","Cote D'ivoire","Croatia","Cuba","Cyprus","Czech Republic","Denmark","Djibouti","Dominica","Dominican Republic","Easter Island","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Ethiopia","Falkland Islands (Malvinas)","Faroe Islands","Fiji","Finland","France","French Guiana","French Polynesia","French Southern Territories","Gabon","Gambia","Georgia","Germany","Ghana","Gibraltar","Greece","Greenland","Grenada","Guadeloupe","Guam","Guatemala","Guinea","Guinea-bissau","Guyana","Haiti","Heard Island and Mcdonald Islands","Honduras","Hong Kong","Hungary","Iceland","India","Indonesia","Iran","Iraq","Israel","Italy","Jamaica","Japan","Jordan","Kazakhstan","Kenya","Kiribati","Korea, North","Korea, South","Kosovo","Kuwait","Kyrgyzstan","Laos","Latvia","Lebanon","Lesotho","Liberia","Libyan Arab Jamahiriya","Liechtenstein","Lithuania","Luxembourg","Macau","Macedonia","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Martinique","Mauritania","Mauritius","Mayotte","Mexico","Micronesia, Federated States of","Moldova, Republic of","Monaco","Mongolia","Montenegro","Montserrat","Morocco","Mozambique","Myanmar","Namibia","Nauru","Nepal","Netherlands","Netherlands Antilles","New Caledonia","New Zealand","Nicaragua","Niger","Nigeria","Niue","Norfolk Island","Northern Mariana Islands","Norway","Oman","Pakistan","Palau","Palestinian Territory","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Pitcairn","Poland","Portugal","Puerto Rico","Qatar","Reunion","Romania","Russia","Rwanda","Saint Helena","Saint Kitts and Nevis","Saint Lucia","Saint Pierre and Miquelon","Saint Vincent and The Grenadines","Samoa","San Marino","Sao Tome and Principe","Saudi Arabia","Senegal","Serbia and Montenegro","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","South Georgia/South Sandwich Islands","Spain","Sri Lanka","Sudan","Suriname","Svalbard and Jan Mayen","Swaziland","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania, United Republic of","Thailand","Timor-leste","Togo","Tokelau","Tonga","Trinidad and Tobago","Tunisia","Turkey","Turkmenistan","Turks and Caicos Islands","Tuvalu","Uganda","Ukraine","United Arab Emirates","United States Minor Outlying Islands","Uruguay","Uzbekistan","Vanuatu","Vatican City","Venezuela","Vietnam","Virgin Islands, British","Virgin Islands, U.S.","Wallis and Futuna","Western Sahara","Yemen","Zambia","Zimbabwe","Other");

$us_state_abbrevs_names = array(
    'AL' => 'Alabama',
    'AK' => 'Alaska',
    'AS' => 'American Samoa',
    'AZ' => 'Arizona',
    'AR' => 'Arkansas',
    'CA' => 'California',
    'CO' => 'Colorado',
    'CT' => 'Connecticut',
    'DE' => 'Delaware',
    'DC' => 'District of Columbia',
    'FL' => 'Florida',
    'FM' => 'Federated States of Micronesia',
    'GA' => 'Georgia',
    'GU' => 'Guam',
    'HI' => 'Hawaii',
    'ID' => 'Idaho',
    'IL' => 'Illinois',
    'IN' => 'Indiana',
    'IA' => 'Iowa',
    'KS' => 'Kansas',
    'KY' => 'Kentucky',
    'LA' => 'Louisiana',
    'ME' => 'Maine',
    'MD' => 'Maryland',
    'MA' => 'Massachusetts',
    'MH' => 'Marshall Islands',
    'MI' => 'Michigan',
    'MN' => 'Minnesota',
    'MP' => 'Northern Mariana Islands',
    'MS' => 'Mississippi',
    'MO' => 'Missouri',
    'MT' => 'Montana',
    'NE' => 'Nebraska',
    'NV' => 'Nevada',
    'NH' => 'New Hampshire',
    'NJ' => 'New Jersey',
    'NM' => 'New Mexico',
    'NY' => 'New York',
    'NC' => 'North Carolina',
    'ND' => 'North Dakota',
    'OH' => 'Ohio',
    'OK' => 'Oklahoma',
    'OR' => 'Oregon',
    'PA' => 'Pennsylvania',
    'PR' => 'Puerto Rico',
    'PW' => 'Palau',
    'RI' => 'Rhode Island',
    'SC' => 'South Carolina',
    'SD' => 'South Dakota',
    'TN' => 'Tennessee',
    'TX' => 'Texas',
    'UT' => 'Utah',
    'VT' => 'Vermont',
    'VI' => 'Virgin Islands',
    'VA' => 'Virginia',
    'WA' => 'Washington',
    'WV' => 'West Virginia',
    'WI' => 'Wisconsin',
    'WY' => 'Wyoming'
);

$ca_state_abbrevs_names = array(
    'AB' => 'Alberta', 
    'BC' => 'British Columbia', 
    'MB' => 'Manitoba', 
    'NB' => 'New Brunswick', 
    'NL' => 'Newfoundland and Labrador', 
    'NT' => 'Northwest Territories', 
    'NS' => 'Nova Scotia', 
    'NU' => 'Nunavut',
    'ON' => 'Ontario', 
    'PE' => 'Prince Edward Island', 
    'QC' => 'Quebec', 
    'SK' => 'Saskatchewan', 
    'YT' => 'Yukon Territory'
);

$aus_state_abbrevs_names = array(
    'ACT' => 'Australian Capital Territory',
    'NSW' => 'New South Wales',
    'NT' => 'Northern Territory',
    'QLD' => 'Queensland',
    'SA' => 'South Australia',
    'TAS' => 'Tasmania',
    'VIC' => 'Victoria',
    'WA' => 'Western Australia'
);

// -------------------------- Tie break rules ------------------------
// List of existing rules for the tie break for ordering the best brewers.
// The order of the rules will be chosen during setup
$tie_break_rules = array(
    "",
    "TBTotalPlaces",
    "TBTotalExtendedPlaces",
    "TBFirstPlaces",
    "TBNumEntries",
    "TBMinScore",
    "TBMaxScore",
    "TBAvgScore"
    //,"TBRandom"
);

/**
 * -------------------------- Clubs List ---------------------------
 *
 */

$club_json = file_get_contents('https://admin.brewingcompetitions.com/lib/clubs.php');
$club_array = json_decode($club_json,true);
if ((isset($_SESSION['contestClubs'])) && (!empty($_SESSION['contestClubs']))) {
    $club_additions = json_decode($_SESSION['contestClubs'],true);
    $club_array = array_merge($club_array,$club_additions);
}
asort($club_array);

$sidebar_date_format = "short";
$suggested_open_date = time();
$suggested_close_date = time() + 604800;
$judging_past = 0;
$judging_started = FALSE;
$judging_ended = FALSE;
$comp_paid_entry_limit = FALSE;
$comp_entry_limit = FALSE;

if (((strpos($section, "step") === FALSE) && ($section != "setup")) && ($section != "update")) {

    if ((isset($row_contest_dates)) && (!empty($row_contest_dates))) {

        $reg_closed_date = $row_contest_dates['contestRegistrationDeadline'];
        $entry_closed_date = $row_contest_dates['contestEntryDeadline'];

        $registration_open = open_or_closed(time(), $row_contest_dates['contestRegistrationOpen'], $row_contest_dates['contestRegistrationDeadline']);
        $entry_window_open = open_or_closed(time(), $row_contest_dates['contestEntryOpen'], $row_contest_dates['contestEntryDeadline']);
        $judge_window_open = open_or_closed(time(), $row_contest_dates['contestJudgeOpen'], $row_contest_dates['contestJudgeDeadline']);
        if ((!empty($row_contest_dates['contestDropoffOpen'])) && (!empty($row_contest_dates['contestDropoffDeadline']))) $dropoff_window_open = open_or_closed(time(), $row_contest_dates['contestDropoffOpen'], $row_contest_dates['contestDropoffDeadline']);
        else $dropoff_window_open = 1;
        if ((!empty($row_contest_dates['contestShippingOpen'])) && (!empty($row_contest_dates['contestShippingDeadline']))) $shipping_window_open = open_or_closed(time(), $row_contest_dates['contestShippingOpen'], $row_contest_dates['contestShippingDeadline']);
        else $shipping_window_open = 1;
        
        $judging_past = judging_date_return();

        $date_arr = array();
        $later_date_arr = array();
        $first_judging_date = "";
        $last_judging_date = "";

        // Get all deadline dates
        $later_date_arr[] = $_SESSION['contestEntryDeadline'];
        if (isset($_SESSION['contestJudgeDeadline'])) $later_date_arr[] = $_SESSION['contestJudgeDeadline'];
        if (isset($_SESSION['contestAwardsLocDate'])) $later_date_arr[] = $_SESSION['contestAwardsLocDate'];

        // Get all judging session start and end dates
        if ((check_setup($prefix."judging_locations",$database)) && (check_update("judgingDateEnd", $prefix."judging_locations"))) {

            $query_judging_dates = sprintf("SELECT judgingDate,judgingDateEnd FROM %s WHERE judgingLocType < '2'",$judging_locations_db_table);
            $judging_dates = mysqli_query($connection,$query_judging_dates) or die (mysqli_error($connection));
            $row_judging_dates = mysqli_fetch_assoc($judging_dates);
            $totalRows_judging_dates = mysqli_num_rows($judging_dates);

            if ($totalRows_judging_dates > 0) {
                do {
                    
                    if (!empty($row_judging_dates['judgingDate'])) {
                        $date_arr[] = $row_judging_dates['judgingDate'];
                        $later_date_arr[] = $row_judging_dates['judgingDate'];
                    }

                    if (!empty($row_judging_dates['judgingDateEnd'])) {
                        $date_arr[] = $row_judging_dates['judgingDateEnd'];
                        $later_date_arr[] = $row_judging_dates['judgingDateEnd'];
                    }

                } while($row_judging_dates = mysqli_fetch_assoc($judging_dates));
            }

            if (!empty($date_arr)) {
                
                $first_judging_date = min($date_arr);
                $last_judging_date = max($date_arr);
                
                if (time() > $first_judging_date) {
                    $judging_started = TRUE;
                    $reg_closed_date = $first_judging_date;
                    $entry_closed_date = $first_judging_date;
                }

                // Generally safe to assume that judging has ended 24 hours after last posted date.
                if (time() > ($last_judging_date + 86400)) {
                    $judging_ended = TRUE;
                }

            }
            
            $pay_window_open = open_or_closed(time(),$row_contest_dates['contestEntryOpen'],$last_judging_date);

        }

        $later_date = max($later_date_arr);

        // Generally safe to assume that judging has ended 60 days post latest date (if no judging dates are present)
        if (empty($later_date)) $later_date = time() - 5184000;
        else {
            
            if (time() > ($later_date + 5184000)) {
                
                $judging_ended = TRUE;
                $later_date = $later_date + 5184000;

                $contestID = NULL;

                $update_table = $prefix."contest_info";
                $data = array(
                    'contestID' => $contestID
                );
                $db_conn->where ('id', 1);
                $result = $db_conn->update ($update_table, $data);

            }

        }
        
        /**
         * If any judging session has started, close the entry
         * and account registration windows.
         * This ensures that any entries that are being judged 
         * aren't modified or deleted by non-admin users.
         */
        
        if ($judging_started) {
            $entry_window_open = 2;
            $registration_open = 2;
        }

        if (strpos($_SESSION['prefsLanguage'],"en-") !== false) $sidebar_date_format = "long";

        $reg_open = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestRegistrationOpen'], $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], $sidebar_date_format, "date-time");
        $reg_closed = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $reg_closed_date, $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], $sidebar_date_format, "date-time");
        $reg_open_sidebar = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestRegistrationOpen'], $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "short", "date-time");
        $reg_closed_sidebar = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $reg_closed_date, $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time");

        $entry_open = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestEntryOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], $sidebar_date_format, "date-time");
        $entry_closed = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $entry_closed_date, $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], $sidebar_date_format, "date-time");
        $entry_open_sidebar = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestEntryOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time");
        $entry_closed_sidebar = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $entry_closed_date, $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], "short", "date-time"); 

        $dropoff_open = "";
        $dropoff_open_sidebar = "";
        $dropoff_closed = "";
        $dropoff_closed_sidebar = "";
        
        if (!empty($row_contest_dates['contestDropoffOpen'])) {
            $dropoff_open = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestDropoffOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], $sidebar_date_format, "date-time");
            $dropoff_open_sidebar = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestDropoffOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time");
        }

        if (!empty($row_contest_dates['contestDropoffDeadline'])) {
            $dropoff_closed = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestDropoffDeadline'], $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], $sidebar_date_format, "date-time");
            $dropoff_closed_sidebar = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestDropoffDeadline'], $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], "short", "date-time");
        }

        $shipping_open = "";
        $shipping_open_sidebar = "";
        $shipping_closed = "";
        $shipping_closed_sidebar = "";

        if (!empty($row_contest_dates['contestShippingOpen'])) {
            $shipping_open = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestShippingOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], $sidebar_date_format, "date-time");
            $shipping_open_sidebar = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestShippingOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time");
        }

        if (!empty($row_contest_dates['contestShippingDeadline'])) {
            $shipping_closed = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestShippingDeadline'], $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], $sidebar_date_format, "date-time");
            $shipping_closed_sidebar = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestShippingDeadline'], $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], "short", "date-time");
        }

        $judge_open = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestJudgeOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], $sidebar_date_format, "date-time");
        $judge_closed = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestJudgeDeadline'], $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], $sidebar_date_format, "date-time");

        $judge_open_sidebar = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestJudgeOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time");
        $judge_closed_sidebar = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestJudgeDeadline'], $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], "short", "date-time");
    
        if ($_SESSION['prefsEval'] == 1) {

            if ((empty($row_judging_prefs['jPrefsJudgingOpen'])) || (empty($row_judging_prefs['jPrefsJudgingClosed']))) {
                
                if (!empty($date_arr)) {
                    $suggested_open_date = min($date_arr); // Get the start time of the first judging location chronologically
                    $suggested_close_date = (max($date_arr) + 28800); // Add eight hours to the start time at the final judging location
                }
                
                else {
                    $suggested_close_date = (time()  + 28800);
                    $suggested_open_date = time();
                }

                if (empty($row_judging_prefs['jPrefsJudgingOpen'])) $judging_evals_open = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $suggested_open_date, $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], $sidebar_date_format, "date-time");
                else $judging_evals_open = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging_prefs['jPrefsJudgingOpen'], $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], $sidebar_date_format, "date-time");
                if (empty($row_judging_prefs['jPrefsJudgingClosed'])) $judging_evals_closed = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $suggested_close_date, $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], $sidebar_date_format, "date-time");
                else $judging_evals_closed = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging_prefs['jPrefsJudgingClosed'], $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], $sidebar_date_format, "date-time");

            }

            else {
                $judging_evals_open = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging_prefs['jPrefsJudgingOpen'], $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], $sidebar_date_format, "date-time");
                $judging_evals_closed = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging_prefs['jPrefsJudgingClosed'], $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], $sidebar_date_format, "date-time");
            }
            
        }

        $currency = explode("^",currency_info($_SESSION['prefsCurrency'],1));
        $currency_symbol = $currency[0];
        $currency_code = $currency[1];

        $totalRows_entry_count = total_paid_received("",0);
        $total_entries = $totalRows_entry_count;
        $total_paid = get_entry_count("paid");
        $total_entries_received = get_entry_count("received");

        // Get styles types and their associated entry limits
        // If a style type has an entry limit, get an entry count from the db for that style type
        // If that style type's entry limit is equal to the count, disable the fields and flag
        // If the flag is present, message the user
        $style_type_limits = array();
        $style_type_limits_display = array();
        $style_type_limits_alert = array();

        $query_style_type_entry_limits = sprintf("SELECT * FROM %s WHERE styleTypeEntryLimit > 0",$prefix."style_types");
        $style_type_entry_limits = mysqli_query($connection,$query_style_type_entry_limits) or die (mysqli_error($connection));
        $row_style_type_entry_limits = mysqli_fetch_assoc($style_type_entry_limits);
        $totalRows_style_type_entry_limits = mysqli_num_rows($style_type_entry_limits);

        $style_type_entry_count_display = array();
        $style_limit_entry_count_display = array();
        $style_type_running_count = 0;
        $style_type_limit_running_count = 0;

        if ((!empty($_SESSION['prefsStyleLimits'])) && (strlen($_SESSION['prefsStyleLimits']) > 1)) {

            foreach (json_decode($_SESSION['prefsStyleLimits'],true) as $key => $value) {

                $query_style_limit_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewCategorySort='%s'", $prefix."brewing", $key);
                $style_limit_entry_count = mysqli_query($connection,$query_style_limit_entry_count) or die (mysqli_error($connection));
                $row_style_limit_entry_count = mysqli_fetch_assoc($style_limit_entry_count);

                $style_limit_entry_count_display[$key] = $row_style_limit_entry_count['count'];
            
            }

        }

        if ($totalRows_style_type_entry_limits > 0) {

            // Build style type count array
            
            do {

                // Default entry limit flag is 0 (false)
                $style_type_limits[$row_style_type_entry_limits['id']] = 0;

                $style_type_limits_display[$row_style_type_entry_limits['styleTypeName']] = $row_style_type_entry_limits['styleTypeEntryLimit'];

                if ($row_style_type_entry_limits['id'] == 4) $query_style_type_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewStyleType='2' OR brewStyleType='3'",$prefix."brewing",$row_style_type_entry_limits['id']);
                else $query_style_type_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewStyleType='%s'",$prefix."brewing",$row_style_type_entry_limits['id']);
                $style_type_entry_count = mysqli_query($connection,$query_style_type_entry_count) or die (mysqli_error($connection));
                $row_style_type_entry_count = mysqli_fetch_assoc($style_type_entry_count);

                $style_type_entry_count_display[$row_style_type_entry_limits['styleTypeName']] = array($row_style_type_entry_count['count'],$row_style_type_entry_limits['styleTypeEntryLimit']);

                $style_type_running_count += $row_style_type_entry_count['count'];
                
                // Check to see if style type has an entry limit AND if that value is numeric
                // If so, perform various actions
                if ((isset($row_style_type_entry_limits['styleTypeEntryLimit'])) && (is_numeric($row_style_type_entry_limits['styleTypeEntryLimit']))) {

                    $style_type_limit_running_count += $row_style_type_entry_limits['styleTypeEntryLimit'];
                    
                    // If entry limit reached flag with a 1 (true)
                    if ($row_style_type_entry_count['count'] >= $row_style_type_entry_limits['styleTypeEntryLimit']) {

                        if ($row_style_type_entry_limits['id'] == 4) {
                            $style_type_limits[2] = 1;
                            $style_type_limits[3] = 1;
                        }

                        else $style_type_limits[$row_style_type_entry_limits['id']] = 1;
                        
                        if ($row_style_type_entry_limits['id'] <= 9) $style_type_limits_alert[$row_style_type_entry_limits['id']] = $row_style_type_entry_limits['styleTypeEntryLimit'];
                        else $style_type_limits_alert[$row_style_type_entry_limits['styleTypeName']] = $row_style_type_entry_limits['styleTypeEntryLimit'];
                    
                    }

                }
            
            } while ($row_style_type_entry_limits = mysqli_fetch_assoc($style_type_entry_limits));

        }

        if ((!empty($row_limits['prefsEntryLimit'])) && (is_numeric($row_limits['prefsEntryLimit'])) && ($style_type_running_count >= $row_limits['prefsEntryLimit'])) $comp_entry_limit = TRUE;

        if ((!empty($row_limits['prefsEntryLimit'])) && (is_numeric($row_limits['prefsEntryLimit']))) $comp_entry_limit_near = ($row_limits['prefsEntryLimit']*.9); else $comp_entry_limit_near = "";
        if ((!empty($row_limits['prefsEntryLimit'])) && (is_numeric($row_limits['prefsEntryLimit'])) && (($total_entries > $comp_entry_limit_near) && ($total_entries < $row_limits['prefsEntryLimit']))) $comp_entry_limit_near_warning = TRUE; else $comp_entry_limit_near_warning = FALSE;

        $remaining_entries = 0;
        if ((($section == "brew") || ($section == "list") || ($section == "pay") || ($section == "default")) && (!empty($row_limits['prefsUserEntryLimit']))) $remaining_entries = ($row_limits['prefsUserEntryLimit'] - $totalRows_log);
        else $remaining_entries = 1;

        if (isset($totalRows_entry_count)) {
            if ((!empty($row_limits['prefsEntryLimit'])) && ($totalRows_entry_count >= $row_limits['prefsEntryLimit'])) $comp_entry_limit = TRUE;
            if ((!empty($row_limits['prefsEntryLimitPaid'])) && ($total_paid >= $row_limits['prefsEntryLimitPaid'])) $comp_paid_entry_limit = TRUE;
        }

        if (open_limit($row_judge_count['count'],$row_judging_prefs['jPrefsCapJudges'],$judge_window_open)) $judge_limit = TRUE;
        else $judge_limit = FALSE;

        if (open_limit($row_steward_count['count'],$row_judging_prefs['jPrefsCapStewards'],$judge_window_open)) $steward_limit = TRUE;
        else $steward_limit = FALSE;

        if (($judge_limit) && ($steward_limit)) $judge_window_open = 2;
        if (($comp_entry_limit) || ($comp_paid_entry_limit)) $entry_window_open = 2;

        $current_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], time(), $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "system", "date");
        $current_date_display = getTimeZoneDateTime($_SESSION['prefsTimeZone'], time(), $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], $sidebar_date_format, "date");
        $current_date_display_short = getTimeZoneDateTime($_SESSION['prefsTimeZone'], time(), $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'],"short", "date");
        $current_time = getTimeZoneDateTime($_SESSION['prefsTimeZone'], time(), $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "system", "time-gmt");

    } // end if ((isset($row_contest_dates)) && (!empty($row_contest_dates)))

} // end if (((strpos($section, "step") === FALSE) && ($section != "setup")) && ($section != "update"))

else {

    if (($section == "step4") || ($section == "step5") || ($section == "step6")) {

        $query_prefs_tz = sprintf("SELECT prefsTimeZone,prefsDateFormat,prefsTimeFormat FROM %s WHERE id='1'", $prefix."preferences");
        $prefs_tz = mysqli_query($connection,$query_prefs_tz) or die (mysqli_error($connection));
        $row_prefs_tz = mysqli_fetch_assoc($prefs_tz);

        $current_date = getTimeZoneDateTime($row_prefs_tz['prefsTimeZone'], time(), $row_prefs_tz['prefsDateFormat'], $row_prefs_tz['prefsTimeFormat'], "system", "date");
        $current_date_display = getTimeZoneDateTime($row_prefs_tz['prefsTimeZone'], time(), $row_prefs_tz['prefsDateFormat'], $row_prefs_tz['prefsTimeFormat'], $sidebar_date_format, "date");
        $current_date_display_short = getTimeZoneDateTime($row_prefs_tz['prefsTimeZone'], time(), $row_prefs_tz['prefsDateFormat'], $row_prefs_tz['prefsTimeFormat'],"short", "date");
        $current_time = getTimeZoneDateTime($row_prefs_tz['prefsTimeZone'], time(), $row_prefs_tz['prefsDateFormat'], $row_prefs_tz['prefsTimeFormat'], "system", "time-gmt");

    }

}

$logged_in = FALSE;
$admin_user = FALSE;
$disable_pay = FALSE;
$show_scores = FALSE;
$show_scoresheets = FALSE;
$show_presentation = FALSE;

// User constants
if (isset($_SESSION['loginUsername']))  {

    $logged_in = TRUE;
    $logged_in_name = $_SESSION['loginUsername'];

    $total_paid_entry_fees_user = 0;

    if (((strpos($section, "step") === FALSE) && ($section != "setup")) && ($section != "update")) {

        if ($_SESSION['userLevel'] <= "1") {
            if ($section == "admin") $link_admin = "#";
            else $link_admin = "";
            $admin_user = TRUE;
        }

        if ((isset($_SESSION['contestEntryFee'])) && (!empty($_SESSION['contestEntryFee']))) {

            // Get Entry Fees
           $total_entry_fees = total_fees($_SESSION['contestEntryFee'], $_SESSION['contestEntryFee2'], $_SESSION['contestEntryFeeDiscount'], $_SESSION['contestEntryFeeDiscountNum'], $_SESSION['contestEntryCap'], $_SESSION['contestEntryFeePasswordNum'], $_SESSION['user_id'], $filter, $_SESSION['comp_id']);
           if ($bid == "default") $user_id_paid = $_SESSION['user_id'];
           else $user_id_paid = $bid;
           $total_paid_entry_fees = total_fees_paid($_SESSION['contestEntryFee'], $_SESSION['contestEntryFee2'], $_SESSION['contestEntryFeeDiscount'], $_SESSION['contestEntryFeeDiscountNum'], $_SESSION['contestEntryCap'], $_SESSION['contestEntryFeePasswordNum'], $user_id_paid, $filter, $_SESSION['comp_id']);
           $total_paid_entry_fees_user = $total_paid_entry_fees;
           $total_to_pay = $total_entry_fees - $total_paid_entry_fees;
        
        }

        // Disable pay?
        if (($registration_open == 2) && ($shipping_window_open == 2) && ($dropoff_window_open == 2) && ($entry_window_open == 2) && ($pay_window_open == 2)) $disable_pay = TRUE;

    }

}

if ((strpos($section, "step") === FALSE) && ($section != "setup") && ($judging_past == 0)) {
    if (($_SESSION['prefsDisplayWinners'] == "Y") && (judging_winner_display($_SESSION['prefsWinnerDelay']))) {
        $show_presentation = TRUE;
        if ($logged_in) {
            $show_scores = TRUE;
            $show_scoresheets = TRUE;
        }
    }
}

// DataTables Default Values
$output_datatables_bPaginate = "true";
$output_datatables_sPaginationType = "full_numbers";
$output_datatables_bLengthChange = "true";
if ((strpos($section, "step") === FALSE) && ($section != "setup")) $output_datatables_iDisplayLength = round($_SESSION['prefsRecordPaging']);
if ($action == "print") $output_datatables_sDom = "it";
else $output_datatables_sDom = "rftp";
$output_datatables_bStateSave = "false";
$output_datatables_bProcessing = "false";

// Disable stuff on participants, entries, tables, and other screens when looking at archived data
$archive_display = FALSE;
if ($dbTable != "default") $archive_display = TRUE;

$totalRows_mods = "";

// Get unconfirmed entry count
if (((strpos($section, "step") === FALSE) && ($section != "setup")) && ($section != "update")) {
    if (($section == "admin") && (($filter == "default") && ($bid == "default") && ($view == "default"))) $entries_unconfirmed = ($totalRows_entry_count - $totalRows_log_confirmed);
    else $entries_unconfirmed = ($totalRows_log - $totalRows_log_confirmed);
}

$barcode_qrcode_array = array("0","2","N","C","3","4","5","6","1");
$no_entry_form_array = array("0","1","2","E","C");

if ($logged_in) $location_target = "_blank";
else $location_target = "_self";

if ((isset($_SESSION['prefsStyleSet'])) && ($_SESSION['prefsStyleSet'] == "BA")) {
    $optional_info_styles = array();
}
elseif ((isset($_SESSION['prefsStyleSet'])) && ($_SESSION['prefsStyleSet'] == "AABC")) {
    $optional_info_styles = array("12-01","14-08","17-03","18-04","18-05","19-05","19-07","16-01","19-01","19-02","19-03","19-04","19-06","20-02","20-03");
}
elseif ((isset($_SESSION['prefsStyleSet'])) && ($_SESSION['prefsStyleSet'] == "AABC2022")) {
    $optional_info_styles = array("07-03","12-01","14-08","17-03","18-04","18-05","16-01","19-01","19-02","19-03","19-04","19-05","19-06","19-07","19-08","19-09","19-10","19-11","19-12","19-13","20-02","20-03","16-08");
}
elseif ((isset($_SESSION['prefsStyleSet'])) && ($_SESSION['prefsStyleSet'] == "NWCiderCup")) {
    $optional_info_styles = array("C4-A","C4-B","C5-A","C8-A","C8-B","C8-C","C9-A","C9-B","C9-C");
}
else {
    $optional_info_styles = array("21-B","28-A","30-B","33-A","33-B","34-B","M2-C","M2-D","M2-E","M3-A","M3-B","M4-B","M4-C","7-C","M1-A","M1-B","M1-C","M2-A","M2-B","M4-A","C1-A","C1-B","C1-C");
    if ((isset($_SESSION['prefsStyleSet'])) && ($_SESSION['prefsStyleSet'] == "BJCP2021")) $optional_info_styles[] = "25-B";
    if ((isset($_SESSION['prefsStyleSet'])) && ($_SESSION['prefsStyleSet'] == "BJCP2025")) {
        $optional_info_styles[] = "C1-D"; 
        $optional_info_styles[] = "C1-E"; 
        $optional_info_styles[] = "C3-A"; 
        $optional_info_styles[] = "C3-B";
        $optional_info_styles[] = "C3-C";
        $optional_info_styles[] = "C4-D";
    }
}

$results_method = array("0" => "By Table/Medal Group", "1" => "By Style", "2" => "By Sub-Style");

if (HOSTED) $_SESSION['prefsCAPTCHA'] = 1;

// Load libraries only when needed - for performance
$tinymce_load = array("contest_info","default","step4");
$datetime_load = array("contest_info","evaluation","testing","preferences","step4","step5","step6","default","judging","judging_preferences","dates","non-judging");
$datatables_load = array("admin","list","default","step4","evaluation");

$specialty_ipa_subs = array();
$historical_subs = array();

if (isset($_SESSION['prefsStyleSet'])) {
    // Set vars for backwards compatibility
    if (isset($_SESSION['style_set_beer_end'])) $beer_end = $_SESSION['style_set_beer_end'];
    if (isset($_SESSION['style_set_mead'])) $mead_array = $_SESSION['style_set_mead'];
    if (isset($_SESSION['style_set_cider'])) $cider_array = $_SESSION['style_set_cider'];
    if (isset($_SESSION['style_set_category_end'])) $category_end = $_SESSION['style_set_category_end'];
    if (($_SESSION['prefsStyleSet'] == "BJCP2025") || ($_SESSION['prefsStyleSet'] == "BJCP2021")) {
        $specialty_ipa_subs = array("21-B1","21-B2","21-B3","21-B4","21-B5","21-B6","21-B7");
        $historical_subs = array("27-A1","27-A2","27-A3","27-A4","27-A5","27-A6","27-A7","27-A8","27-A9");
    }
}

// Determine if MariaDB is being used instead of MySQL.
$db_version = $connection -> server_info;
$db_maria = FALSE;
if (strpos(strtolower($db_version), "mariadb") !== false) $db_maria = TRUE;

// Generate a unique encryption key on each page load.
if ((!isset($_SESSION['encryption_key'])) || (empty($_SESSION['encryption_key']))) $_SESSION['encryption_key'] = base64_encode(openssl_random_pseudo_bytes(32));

/**
 * Failsafe for selected styles.
 * If the session variable is empty, check the DB table column.
 * If the column is empty, regenerate.
 * If the column has data, check if it JSON. If so, repopulate
 * session variable. If not, regenerate.
 */
 
$regenerate_selected_styles = FALSE;

if ((strpos($section, 'step') === FALSE) && (check_setup($prefix."bcoem_sys",$database))) {
    
    if ((check_update("prefsSelectedStyles", $prefix."preferences")) && (empty($_SESSION['prefsSelectedStyles']))) {

        $query_selected_styles = sprintf("SELECT prefsSelectedStyles FROM %s WHERE id='1';",$prefix."preferences");
        $selected_styles = mysqli_query($connection,$query_selected_styles) or die (mysqli_error($connection));
        $row_selected_styles = mysqli_fetch_assoc($selected_styles);

        if (empty($row_selected_styles['prefsSelectedStyles'])) $regenerate_selected_styles = TRUE;
        
        else {
            
            $is_styles_json = json_decode($row_selected_styles['prefsSelectedStyles']);
            if (json_last_error() === JSON_ERROR_NONE) $styles_json_data = TRUE;
            else $styles_json_data = FALSE;

            if ($styles_json_data) $_SESSION['prefsSelectedStyles'] = $row_cted_styles['prefsSelectedStyles'];
            else $regenerate_selected_styles = TRUE;
        
        }

        if ($regenerate_selected_styles) {

            $update_selected_styles = array();
            $prefsStyleSet = $_SESSION['prefsStyleSet'];

            if (HOSTED) {
                
                $query_styles_default = sprintf("SELECT id, brewStyle, brewStyleGroup, brewStyleNum, brewStyleVersion FROM `bcoem_shared_styles` WHERE brewStyleVersion='%s'", $prefsStyleSet);
                $styles_default = mysqli_query($connection,$query_styles_default);
                $row_styles_default = mysqli_fetch_assoc($styles_default);

                if ($row_styles_default) {

                    do {

                        $update_selected_styles[$row_styles_default['id']] = array(
                            'brewStyle' => $row_styles_default['brewStyle'],
                            'brewStyleGroup' => $row_styles_default['brewStyleGroup'],
                            'brewStyleNum' => $row_styles_default['brewStyleNum'],
                            'brewStyleVersion' => $row_styles_default['brewStyleVersion']
                        );

                    } while($row_styles_default = mysqli_fetch_assoc($styles_default));

                        
                }
                
                $query_styles_custom = sprintf("SELECT id, brewStyle, brewStyleGroup, brewStyleNum, brewStyleVersion FROM %s WHERE brewStyleOwn='custom'", $prefix."styles");
                $styles_custom = mysqli_query($connection,$query_styles_custom);
                $row_styles_custom = mysqli_fetch_assoc($styles_custom);

                if ($row_styles_custom) {

                    do {

                        $update_selected_styles[$row_styles_custom['id']] = array(
                            'brewStyle' => sterilize($row_styles_custom['brewStyle']),
                            'brewStyleGroup' => sterilize($row_styles_custom['brewStyleGroup']),
                            'brewStyleNum' => sterilize($row_styles_custom['brewStyleNum']),
                            'brewStyleVersion' => sterilize($row_styles_custom['brewStyleVersion'])
                        );

                    } while($row_styles_custom = mysqli_fetch_assoc($styles_custom));

                    
                }
            
            } // end if (HOSTED)
                
            else {

                $query_styles_default = sprintf("SELECT id, brewStyle, brewStyleGroup, brewStyleNum, brewStyleVersion FROM %s WHERE brewStyleVersion='%s'", $prefix."styles", $prefsStyleSet);
                $styles_default = mysqli_query($connection,$query_styles_default);
                $row_styles_default = mysqli_fetch_assoc($styles_default);

                if ($row_styles_default) {
                    do {
                        $update_selected_styles[$row_styles_default['id']] = array(
                            'brewStyle' => sterilize($row_styles_default['brewStyle']),
                            'brewStyleGroup' => sterilize($row_styles_default['brewStyleGroup']),
                            'brewStyleNum' => sterilize($row_styles_default['brewStyleNum']),
                            'brewStyleVersion' => sterilize($row_styles_default['brewStyleVersion'])
                        );
                    } while($row_styles_default = mysqli_fetch_assoc($styles_default));
                }

            } // end else

            $update_selected_styles = json_encode($update_selected_styles);

            $update_table = $prefix."preferences";
            $data = array(
                'prefsSelectedStyles' => $update_selected_styles
            );
            $db_conn->where ('id', 1);
            $result = $db_conn->update ($update_table, $data);
            if (!$result) {
                $error_output[] = $db_conn->getLastError();
                $errors = TRUE;
            }

            // Empty the prefs session variable
            // Will trigger the session to reset the variables in common.db.php upon reload after redirect
            unset($_SESSION['prefs'.$prefix_session]);

        }

    }

}

$default_to = "prost";
$default_from = "noreply";

$drop_ship_dates = array();
if ($row_contest_dates) {

    // Get drop-off and shipping deadlines, if any.

    $drop_off_deadline = "9999999999";
    $shipping_deadline = "9999999999";

    if (!empty($row_contest_dates['contestDropoffDeadline'])) $drop_off_deadline = $row_contest_dates['contestDropoffDeadline'];
    if (!empty($row_contest_dates['contestShippingDeadline'])) $shipping_deadline = $row_contest_dates['contestShippingDeadline'];

    $drop_ship_dates = array(
        $drop_off_deadline, 
        $shipping_deadline
    );

    // Determine the earliest of the two dates.
    // If no drop-off and shipping deadlines specified, default to entry deadline date since it's required.
    if (!empty($drop_ship_dates)) {

        if ((min($drop_ship_dates)) == "9999999999") $drop_ship_deadline = $row_contest_dates['contestEntryDeadline'];
        else $drop_ship_deadline = min($drop_ship_dates);

    }

    else $drop_ship_deadline = $row_contest_dates['contestEntryDeadline'];

    // Specify the latest date users can edit their entries.
    // If the contestEntryEditDeadline column has a value, and it's value is less than the drop_ship_deadline var value, default to it.
    // Otherwise, use the drop_ship_deadline var value.
    if ((!empty($row_contest_dates['contestEntryEditDeadline'])) && ($row_contest_dates['contestEntryEditDeadline'] < $drop_ship_deadline)) $entry_edit_deadline = $row_contest_dates['contestEntryEditDeadline'];
    else $entry_edit_deadline = $drop_ship_deadline;
    $entry_edit_deadline_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $entry_edit_deadline, $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time");

}

$mail_use_smtp = FALSE;
if (HOSTED) $mail_use_smtp = TRUE;
elseif (isset($_SESSION['prefsEmailSMTP'])) { 
    if (
        ($_SESSION['prefsEmailSMTP'] == 1) && 
        (!empty($_SESSION['prefsEmailHost'])) && 
        (!empty($_SESSION['prefsEmailFrom'])) && 
        (!empty($_SESSION['prefsEmailUsername'])) && 
        (!empty($_SESSION['prefsEmailPassword'])) && 
        (!empty($_SESSION['prefsEmailPort']))
    ) $mail_use_smtp = TRUE;
}
?>
