<?php
ini_set('display_errors', 0); // Change to 0 for prod; change to 1 for testing.
ini_set('display_startup_errors', 0); // Change to 0 for prod; change to 1 for testing.
error_reporting(0); // Change to error_reporting(0) for prod; change to E_ALL for testing.

// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] > 1))) {
    
    $authorized = FALSE;

    // Do not redirect if its the HTML or PDF download of results
    // Available publicly on the results page
    if ($section == "export-results") {
        if ((($go == "judging_scores_bos") || ($go == "judging_scores")) && (($view == "html") || ($view == "pdf"))) $authorized = TRUE;
    }

    if (!$authorized) {
        $redirect = "../../403.php";
        $redirect_go_to = sprintf("Location: %s", $redirect);
        header($redirect_go_to);
        exit();
    }

}

/**
 * Module: export.output.php
 * Revision History:
 * - fixed point output errors for judges and BOS judges
 * - programming now accounts for multiple roles (e.g., judge/staff, steward/staff, 
 *   bos judge/staff, etc.)
 * - XML output is fully compliant with the BJCP Database Interface Specifications
 *   @see http://www.bjcp.org/it/docs/BJCP%20Database%20XML%20Interface%20Spec%202.1.pdf
 * 
 * 09.18.2018 - judge_points() function was returning incorrect calculations. 
 *              Could not figure out why. All calcs were moved inline below.
 * 09.01.2020 - fixed unauthorized access issues reported to GitHub 
 *              @see https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues/1163
 * 07.18.2021 - fixed judge_points() and steward_points() calculations to 
 *              better handle multiple day and multiple session day calculations
 * 11.15.2021 - BJCP is now rejecting reports with "duplicate entries" - most 
 *              likely resulting from participants serving as both staff and
 *              either a judge or steward. Fixed to combine points into a single 
 *              entry per person (Staff + Judge, Staff + Steward, etc.).
 * 02.24.2022 - Due to incompatabilities with PHP 8, the HTML2PDF and htmlTable 
 *              FPDF extension classes have been removed in favor of FPDF EasyTables -
 *              @see https://github.com/fpdf-easytable/fpdf-easytable
 */

// Queries for current data
if ($filter == "default") {
    $winner_method = $_SESSION['prefsWinnerMethod'];
    $style_set = $_SESSION['prefsStyleSet'];
    $pro_edition = $_SESSION['prefsProEdition'];
}

// Or, for archived data
else {

    // Query the archive table for preferences
    $query_archive_prefs = sprintf("SELECT * FROM %s WHERE archiveSuffix='%s'",$prefix."archive", $filter);
    $archive_prefs = mysqli_query($connection,$query_archive_prefs) or die (mysqli_error($connection));
    $row_archive_prefs = mysqli_fetch_assoc($archive_prefs);
    $totalRows_archive_prefs = mysqli_num_rows($archive_prefs);

    if ($totalRows_archive_prefs > 0) {

        $winner_method = $row_archive_prefs['archiveWinnerMethod'];
        $style_set = $row_archive_prefs['archiveStyleSet'];
        $pro_edition = $row_archive_prefs['archiveProEdition'];
        $judging_scores_db_table = $prefix."judging_scores_".$filter;
        $brewing_db_table = $prefix."brewing_".$filter;
        $brewer_db_table = $prefix."brewer_".$filter;

    }

}

$admin_role = FALSE;
if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) $admin_role = TRUE;

$header = "";
$table_width = "";

// Establish standard widths
// Total of 760 px for Portrait
// Total of 1100 px for landscape
$td_width_place = 35;
$td_width_name = 150;
$td_width_entry = 200;
$td_width_style = 200;
$td_width_club = 175;
if ($view == "pdf") $table_width = 760;
if ($view == "html") $table_width = "100%";

$BOM = "\xEF\xBB\xBF"; // UTF-8 byte order mark (BOM)

function convert_to_entities($input) {
    $output = preg_replace_callback("/(&#[0-9]+;)/", function($m) { 
        return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES"); 
    }, $input);
    $output = html_entity_decode($output);
    return $output;
}

if (!function_exists('fputcsv')) {
    
    function fputcsv(&$handle, $fields = array(), $delimiter = ',', $enclosure = '"') {

        // Sanity Check
        if (!is_resource($handle)) {
            trigger_error('fputcsv() expects parameter 1 to be resource, ' .
                gettype($handle) . ' given', E_USER_WARNING);
            return false;
        }

        if ($delimiter!=NULL) {
            if( strlen($delimiter) < 1 ) {
                trigger_error('delimiter must be a character', E_USER_WARNING);
                return false;
            }elseif( strlen($delimiter) > 1 ) {
                trigger_error('delimiter must be a single character', E_USER_NOTICE);
            }

            /* use first character from string */
            $delimiter = $delimiter[0];
        }

        if( $enclosure!=NULL ) {
             if( strlen($enclosure) < 1 ) {
                trigger_error('enclosure must be a character', E_USER_WARNING);
                return false;
            }elseif( strlen($enclosure) > 1 ) {
                trigger_error('enclosure must be a single character', E_USER_NOTICE);
            }

            /* use first character from string */
            $enclosure = $enclosure[0];
       }

        $i = 0;
        $csvline = '';
        $escape_char = '\\';
        $field_cnt = count($fields);
        $enc_is_quote = in_array($enclosure, array('"',"'"));
        reset($fields);

        foreach( $fields AS $field ) {

            /* enclose a field that contains a delimiter, an enclosure character, or a newline */
            if( is_string($field) && (
                strpos($field, $delimiter)!==false ||
                strpos($field, $enclosure)!==false ||
                strpos($field, $escape_char)!==false ||
                strpos($field, "\n")!==false ||
                strpos($field, "\r")!==false ||
                strpos($field, "\t")!==false ||
                strpos($field, ' ')!==false ) ) {

                $field_len = strlen($field);
                $escaped = 0;

                $csvline .= $enclosure;
                for( $ch = 0; $ch < $field_len; $ch++ )    {
                    if( $field[$ch] == $escape_char && $field[$ch+1] == $enclosure && $enc_is_quote ) {
                        continue;
                    }elseif( $field[$ch] == $escape_char ) {
                        $escaped = 1;
                    }elseif( !$escaped && $field[$ch] == $enclosure ) {
                        $csvline .= $enclosure;
                    }else{
                        $escaped = 0;
                    }
                    $csvline .= $field[$ch];
                }
                $csvline .= $enclosure;
            } else {
                $csvline .= $field;
            }

            if( $i++ != $field_cnt ) {
                $csvline .= $delimiter;
            }
        }

        $csvline .= "\n";

        return fwrite($handle, $csvline);
    }
}

if (($admin_role) || ((($judging_past == 0) && ($registration_open == 2) && ($entry_window_open == 2)))) {

    $archive_suffix = "";
    if ($filter != "default") $archive_suffix = "_".$filter;

    /**
     * -------------- ENTRY Exports -------------- 
     */

	if ($section == "export-entries") {

        $a = array();

        if ($admin_role) {

            $type = "entries";
            if ($tb != "default") $filter_filename = $tb;
            else $filter_filename = $filter;

            if ($go == "csv") { $separator = ","; $extension = ".csv"; }
            if ($go == "tab") { $separator = chr(9); $extension = ".tab"; }
            $contest = str_replace(' ', '_', $_SESSION['contestName']);
            if ($section == "export-loc") $loc = "_".str_replace(' ', '_', $row_judging['judgingLocName']);
            else $loc = "";
            $date = date("m-d-Y");
            if ($sort != "default") $date = $sort;
            $filename = ltrim(filename($contest)."_Entries".filename($filter_filename).filename($action).filename($view).filename($date).$loc.$extension,"_");

            include (DB.'output_entries_export.db.php');

            function mysqli_field_name($result, $field_offset)  {
                $properties = mysqli_fetch_field_direct($result, $field_offset);
                return is_object($properties) ? $properties->name : null;
            }

            if (($go == "csv") && ($action == "all") && ($tb == "all")) {

                $headers = array();
                $headers[] = $label_first_name;
                $headers[] = $label_last_name;
                if ($_SESSION['prefsProEdition'] == 1) {
                    $headers[] = $label_organization;
                    $headers[] = $label_ttb;
                }
                $headers[] = $label_email;
                $headers[] = $label_address;
                $headers[] = $label_city;
                $headers[] = $label_state_province;
                $headers[] = $label_zip;
                $headers[] = $label_country;
                if ($_SESSION['prefsProEdition'] != 1) $headers[] = $label_club;

                for ($i = 0; $i < $num_fields; $i++) {
                    $header_name = mysqli_fetch_field_direct($sql, $i)->name;
                    $header_name = ltrim($header_name,"brew");
                    if ($header_name == "id") $headers[] = $label_entry_number;
                    elseif ($header_name == "Name") $headers[] = $label_entry_name;
                    elseif ($header_name == "Info") $headers[] = $label_required_info;
                    elseif ($header_name == "InfoOptional") $headers[] = $label_optional_info;
                    elseif ($header_name == "Mead1") $headers[] = $label_carbonation;
                    elseif ($header_name == "Mead2") $headers[] = $label_sweetness;
                    elseif ($header_name == "Mead3") $headers[] = $label_strength;
                    elseif ($header_name == "Comments") $headers[] = $label_brewer_specifics;
                    elseif ($header_name == "Updated") $headers[] = $label_updated;
                    else $headers[] = preg_replace(array('/(?<=[^A-Z])([A-Z])/', '/(?<=[^0-9])([0-9])/'), ' $0', $header_name);
                 }

                $headers[] = $label_table;
                $headers[] = $label_flight;
                $headers[] = $label_round;
                $headers[] = $label_score;
                $headers[] = $label_place;
                $headers[] = $label_bos." ".$label_place;
                $headers[] = $label_style." ".$label_type;
                $headers[] = $label_location;

                header("Content-Type: text/csv; charset=utf-8");
                header('Content-Disposition: attachment;filename="'.$filename.'"');
                header('Pragma: no-cache');
                header('Expires: 0');

                $fp = fopen('php://output', 'w');
                fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));

                if ($fp && $sql) {

                    fputcsv($fp, $headers);

                    if ($totalRows_sql > 0) {

                        do {

                            $brewerFirstName = "";
                            $brewerLastName = "";
                            $brewer_club = "";
                            $brewer_info = array();

                            $fields1 = array();
                            $fields2 = array();
                            $fields = array();

                            include (DB.'output_entries_export_extend.db.php');
                            if (isset($row_sql['brewBrewerID'])) $brewer_info = explode("^", brewer_info($row_sql['brewBrewerID']));
                            if (isset($row_sql['brewBrewerFirstName'])) $brewerFirstName = convert_to_entities($row_sql['brewBrewerFirstName']);
                            if (isset($row_sql['brewBrewerLastName'])) $brewerLastName = convert_to_entities($row_sql['brewBrewerLastName']);

                            if ($_SESSION['prefsProEdition'] == 1) {
                                if (!empty($brewer_info)) $fields0 = array(
                                    $brewerFirstName,
                                    $brewerLastName,
                                    convert_to_entities($brewer_info[15]),
                                    convert_to_entities($brewer_info[17]),
                                    convert_to_entities($brewer_info[6]),
                                    convert_to_entities($brewer_info[10]),
                                    convert_to_entities($brewer_info[11]),
                                    convert_to_entities($brewer_info[12]),
                                    convert_to_entities($brewer_info[13]),
                                    convert_to_entities($brewer_info[14])
                                );
                                else $fields0 = array($brewerFirstName,$brewerLastName,"","","","","","","","");
                            }

                            else {
                                if ((isset($brewer_info[8])) && ($brewer_info[8] != "&nbsp;")) $brewer_club =  convert_to_entities($brewer_info[8]);
                                if (!empty($brewer_info)) $fields0 = array(
                                    $brewerFirstName,
                                    $brewerLastName,
                                    convert_to_entities($brewer_info[6]),
                                    convert_to_entities($brewer_info[10]),
                                    convert_to_entities($brewer_info[11]),
                                    convert_to_entities($brewer_info[12]),
                                    convert_to_entities($brewer_info[13]),
                                    convert_to_entities($brewer_info[14]),
                                    $brewer_club
                                );
                                else $fields0 = array($brewerFirstName,$brewerLastName,"","","","","","","");
                            }

                            foreach ($row_sql as $key => $value) {

                                $is_rules_json = json_decode($value);
                                if (json_last_error() === JSON_ERROR_NONE) {
                                    $json_array = json_decode($value,true);
                                    $string = "";
                                    foreach ($json_array as $k => $v) {
                                        $o = convert_to_entities($v);
                                        $k = str_replace('_', ' ', $k);
                                        $string .= ucwords($k).": ";
                                        $string .= $o."; ";
                                    }

                                    $output = rtrim($string, "; ");
                                }

                                else {
                                    $output = convert_to_entities($value);
                                }

                                
                                $fields1[] = $output;
                            
                            }
                            
                            if (($row_flight) && ($row_scores)) $fields2 = array(
                                convert_to_entities($table_name),
                                convert_to_entities($row_flight['flightNumber']),
                                convert_to_entities($row_flight['flightRound']),
                                sprintf("%02s",convert_to_entities($row_scores['scoreEntry'])),
                                convert_to_entities($row_scores['scorePlace']),
                                convert_to_entities($bos_place),
                                convert_to_entities($style_type_entry),
                                convert_to_entities($location[2])
                            );
                            
                            $fields = array_merge($fields0,$fields1,$fields2);
                            fputcsv($fp, $fields);

                        }

                        while ($row_sql = mysqli_fetch_assoc($sql));
                    }

                die;

                }
            
            }

            /**
             * -------------- MHP Organizer Export --------------
             * By request from the Master Homebrewer Program
             * admins. They were concerned that the participants
             * could alter the personal CSV data download. Asked
             * for a report of all MHP member results from the 
             * organizers.
             */

            elseif (($go == "csv") && ($tb == "circuit") && ($filter == "mhp")) {

                $query_mhp_brewers = sprintf("SELECT id, uid, brewerFirstName, brewerLastName, brewerMHP FROM %s WHERE brewerMHP IS NOT NULL ORDER BY brewerLastName, brewerFirstName ASC",$prefix."brewer");
                $mhp_brewers = mysqli_query($connection,$query_mhp_brewers);
                $row_mhp_brewers = mysqli_fetch_assoc($mhp_brewers);
                $totalRows_mhp_brewers = mysqli_num_rows($mhp_brewers);

                $a = array();
                $results = array();

                // Results data headers
                $results[] = array("Email to masterhomebrewerprogram@gmail.com");
                $results[] = array("Remove these first three rows before sending to MHP or applying any sort functions.");
                $results[] = array("");
                $results[] = array("MHP#","Last Name","First Name","Category", "Category Name", "Required Info", "Official Score", "Highest Score", "Place");

                if ($totalRows_mhp_brewers > 0) {

                    do {

                        $query_brewer = sprintf("SELECT DISTINCT a.brewCategory, a.brewSubCategory, a.id AS eid, a.brewStyle, a.brewInfo, a.brewInfoOptional, a.brewComments, b.scoreEntry, b.scorePlace FROM %s a, %s b WHERE a.brewBrewerID = '%s' AND b.bid = '%s' AND a.id = b.eid AND a.brewStyleType <= '3'", $prefix."brewing", $prefix."judging_scores", $row_mhp_brewers['id'], $row_mhp_brewers['id']);
                        $brewer = mysqli_query($connection,$query_brewer);
                        $row_brewer = mysqli_fetch_assoc($brewer);
                        $totalRows_brewer = mysqli_num_rows($brewer);

                        $results_count = 0;
                        $first_name = "";
                        $last_name = "";
                        $club = "";
                        $email = "";
                        $mhp = "";

                        if ($row_brewer) {

                            do {

                                $results_count++;
                                
                                $highest_score = array();
                                $consensus_score = array();
                                $eval_score = 0;
                                $highest_entry_score = "";
                                $entry_consensus_score = "";
                                $first_name = "";
                                $last_name = "";
                                $MHP = "";
                                $req_info = "";
                                
                                $category = $row_brewer['brewCategory'].$row_brewer['brewSubCategory'];
                                
                                if (isset($row_mhp_brewers['brewerMHP'])) $MHP = convert_to_entities($row_mhp_brewers['brewerMHP']);
                                if (isset($row_mhp_brewers['brewerFirstName'])) $first_name = convert_to_entities($row_mhp_brewers['brewerFirstName']);
                                if (isset($row_mhp_brewers['brewerLastName'])) $last_name = convert_to_entities($row_mhp_brewers['brewerLastName']);
                                if (!empty($row_brewer['brewInfo'])) $req_info .= convert_to_entities($row_brewer['brewInfo'])." ";
                                if (!empty($row_brewer['brewInfoOptional'])) $req_info .= convert_to_entities($row_brewer['brewInfoOptional'])." ";
                                if (!empty($row_brewer['brewComments'])) $req_info .= convert_to_entities($row_brewer['brewComments']);
                                if (!empty($req_info)) $req_info = str_replace("^"," ",$req_info);

                                // Check Eval table for the highest score recorded for this entry
                                $query_eid_eval = sprintf("SELECT * FROM %s WHERE eid=%s", $prefix."evaluation", $row_brewer['eid']);
                                $eid_eval = mysqli_query($connection,$query_eid_eval);
                                $row_eid_eval = mysqli_fetch_assoc($eid_eval);
                                $totalRows_eid_eval = mysqli_num_rows($eid_eval);

                                if ($totalRows_eid_eval > 0) {
                                    
                                    do {

                                        $score = 
                                        $row_eid_eval['evalAromaScore'] + 
                                        $row_eid_eval['evalAppearanceScore'] + 
                                        $row_eid_eval['evalFlavorScore'] + 
                                        $row_eid_eval['evalMouthfeelScore'] + 
                                        $row_eid_eval['evalOverallScore']
                                        ;

                                        $highest_score[] = $score;
                                        $consensus_score[] = $row_eid_eval['evalFinalScore'];

                                    } while($row_eid_eval = mysqli_fetch_assoc($eid_eval));

                                }

                                if (empty($row_brewer['scoreEntry'])) $entry_consensus_score = max($consensus_score);
                                else {
                                    $highest_entry_score = $row_brewer['scoreEntry'];
                                    $entry_consensus_score = $row_brewer['scoreEntry'];
                                }

                                if (!empty($highest_score)) {
                                    $eval_score = max($highest_score);
                                    if ($eval_score > $row_brewer['scoreEntry']) $highest_entry_score = $eval_score;
                                }

                                // Results data
                                $results[] = array(
                                    $MHP,
                                    $last_name,
                                    $first_name,
                                    $category, 
                                    convert_to_entities($row_brewer['brewStyle']),
                                    $req_info, 
                                    $entry_consensus_score, 
                                    $highest_entry_score, 
                                    $row_brewer['scorePlace']
                                );

                            } while($row_brewer = mysqli_fetch_assoc($brewer));

                        } // end if ($row_brewer)

                    } while ($row_mhp_brewers = mysqli_fetch_assoc($mhp_brewers));

                }

                else {
                    $results[] = array("No score data was found.");
                    $a = array_merge($results);
                }

                $separator = ","; 
                $extension = ".csv";
                $date = date("m-d-Y");
                $filename = "MHP_Results_".$_SESSION['contestName']."_";
                $filename .= $date.$extension;
                $filename = filename($filename);
                $filename = ltrim($filename,"_");

                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment;filename="'.$filename.'"');
                header('Pragma: no-cache');
                header('Expires: 0');

                $fp = fopen('php://output', 'w');
                fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));

                foreach ($results as $fields) {
                    fputcsv($fp,$fields,$separator);
                }

                fclose($fp);
                
                exit();
                
            } // end elseif (($go == "csv") && ($tb == "circuit") && ($filter == "mhp"))

            else {

                if (($go == "csv") && ($action == "hccp") && ($filter != "winners") && ($tb != "winners")) {

                    if ($_SESSION['prefsProEdition'] == 1) $a[] = array($label_first_name,$label_last_name,$label_organization,$label_ttb,$label_entry_number,$label_category,$label_style,$label_name,$label_entry_number,$label_judging_number,$label_name,$label_required_info,$label_sweetness,$label_carbonation,$label_strength);

                    else $a[] = array($label_first_name,$label_last_name,$label_entry_number,$label_category,$label_style,$label_name,$label_entry_number,$label_judging_number,$label_name,$label_required_info,$label_sweetness,$label_carbonation,$label_strength);
                }

                if (($go == "csv") && (($action == "default") || ($action == "email")) && ($filter != "winners") && (($tb == "default") || ($tb == "paid") || ($tb == "nopay")  || ($tb == "brewer_contact_info"))) {

                    if ($_SESSION['prefsProEdition'] == 1) $a[] = array($label_first_name,$label_last_name,$label_organization,$label_ttb,$label_email,$label_address,$label_city,$label_state_province,$label_zip,$label_country,$label_entry_number,$label_judging_number,$label_category,$label_subcategory,$label_name,$label_entry_name,$label_required_info,$label_brewer_specifics,$label_sweetness,$label_carbonation,$label_strength,$label_table,$label_location,$label_flight,$label_round,$label_score,$label_place,$label_bos);

                    else $a[] = array($label_first_name,$label_last_name,$label_email,$label_address,$label_city,$label_state_province,$label_zip,$label_country,$label_club,$label_entry_number,$label_judging_number,$label_category,$label_subcategory,$label_style,$label_entry_name,$label_required_info,$label_brewer_specifics,$label_sweetness,$label_carbonation,$label_strength,$label_table,$label_location,$label_flight,$label_round,$label_score,$label_place,$label_bos);
                }

                if (($go == "csv") && ($action == "default") && ($filter == "default") && ($tb == "winners")) {

                    if ($_SESSION['prefsProEdition'] == 1) $a[] = array($label_table,$label_name,$label_category,$label_style,$label_name,$label_place,$label_last_name,$label_first_name,$label_organization,$label_ttb,$label_email,$label_address,$label_city,$label_state_province,$label_zip,$label_country,$label_phone,$label_entry_name,$label_club,$label_cobrewer);

                    else $a[] = array($label_table,$label_name,$label_category,$label_subcategory,$label_style,$label_place,$label_last_name,$label_first_name,$label_email,$label_address,$label_city,$label_state_province,$label_zip,$label_country,$label_phone,$label_entry_name,$label_club,$label_cobrewer);
                }

                if (($go == "csv") && ($action == "default") && ($tb == "circuit")) {

                    // Only for amateur comps

                    $a[] = array($label_table,$label_name,$label_judging_number,$label_category,$label_subcategory,$label_style,$label_place,$label_last_name,$label_first_name,$label_email,$label_address,$label_city,$label_state_province,$label_zip,$label_country,$label_phone,$label_entry_name,$label_club,$label_cobrewer,$label_bos,$label_pro_am,$label_medal_count,$label_best_brewer_place);

                }

                // Required and optional info only headers
                if (($go == "csv") && ($action == "required") && ($tb == "required")) {
                    $a[] = array(
                        $label_entry_number,
                        $label_judging_number,
                        $label_category,
                        $label_subcategory,
                        $label_style,
                        $label_required_info,
                        $label_optional_info,
                        $label_brewer_specifics,
                        $label_sweetness,
                        $label_carbonation,
                        $label_strength
                    );
                }

                if ($totalRows_sql > 0) {

                    // Make various queries for circuit export
                    if ($tb == "circuit") {

                        if ($filter != "default") {

                            $query_disp_archive_winners = sprintf("SELECT * FROM %s WHERE archiveSuffix='%s'",$prefix."archive",$filter);
                            $disp_archive_winners = mysqli_query($connection,$query_disp_archive_winners);
                            $row_disp_archive_winners = mysqli_fetch_assoc($disp_archive_winners);
                            $totalRows_disp_archive_winners = mysqli_num_rows($disp_archive_winners);

                        }
                        
                        $bos_for_entry = 0;
                        $pro_am_for_entry = 0;
                        
                        $query_bos_scores = sprintf("SELECT eid, scorePlace, scoreType FROM %s WHERE scorePlace IS NOT NULL", $prefix."judging_scores_bos".$archive_suffix);
                        $bos_scores = mysqli_query($connection,$query_bos_scores) or die (mysqli_error($connection));
                        $row_bos_scores = mysqli_fetch_assoc($bos_scores);
                        $totalRows_bos_scores = mysqli_num_rows($bos_scores);

                        $bos_score_arr = array();
                        if ($totalRows_bos_scores > 0) {
                            do {
                                $bos_score_arr[$row_bos_scores['eid']] = $row_bos_scores['scorePlace'];
                            }
                            while ($row_bos_scores = mysqli_fetch_assoc($bos_scores));
                        }

                        $query_pro_ams = sprintf("SELECT a.id, a.sbi_name, b.eid  FROM %s a, %s b WHERE a.id = b.sid AND b.sbd_place = '1'", $prefix."special_best_info", $prefix."special_best_data".$archive_suffix);
                        $pro_ams = mysqli_query($connection,$query_pro_ams) or die (mysqli_error($connection));
                        $row_pro_ams = mysqli_fetch_assoc($pro_ams);
                        $totalRows_pro_ams = mysqli_num_rows($pro_ams);

                        $pro_am_arr = array();
                        if ($totalRows_pro_ams > 0) {
                            do {
                                $pro_am_arr[$row_pro_ams['eid']] = $row_pro_ams['sbi_name'];
                            }
                            while ($row_pro_ams = mysqli_fetch_assoc($pro_ams));
                        }

                        $style_arr = array();
                        $query_style_type = sprintf("SELECT id FROM %s",$style_types_db_table.$archive_suffix);
                        $style_type = mysqli_query($connection,$query_style_type) or die (mysqli_error($connection));
                        $row_style_type = mysqli_fetch_assoc($style_type);
                        $totalRows_style_type = mysqli_num_rows($style_type);
                        do { $style_arr[] = $row_style_type['id']; } while ($row_style_type = mysqli_fetch_assoc($style_type));
                        sort($style_arr);

                        foreach ($style_arr as $type) {

                            include (DB.'output_results_download_bos.db.php');

                            if ($totalRows_bos > 0) {
                                if ($_SESSION['prefsProEdition'] == 1) $output .= "\t\t".$label_bos.": ".convert_to_entities($row_bos['brewerBreweryName'])."\n";
                                else $output .= "\t\t".$label_bos.": ".convert_to_entities($row_bos['brewerFirstName'])." ".convert_to_entities($row_bos['brewerLastName'])."\n";
                                $output .= "\t\t".$label_style.": ".convert_to_entities($row_bos['brewStyle'])."\n";
                                $output .= "\t\t".$label_city.": ".convert_to_entities($row_bos['brewerCity'])."\n";
                                $output .= "\t\t".$label_state_province.": ".convert_to_entities($row_bos['brewerState'])."\n";
                                $output .= "\t\t".$label_country.": ".convert_to_entities($row_bos['brewerCountry'])."\n";
                            }
                        }

                        include (SECTIONS.'bestbrewer.sec.php');

                    }

                    do {
                        
                        $brewerFirstName = "";
                        $brewerLastName = "";
                        $brewName = "";
                        $brewInfo = "";
                        $brewSpecifics = "";
                        $brewInfoOptional = "";
                        $entryNo = sprintf("%06s",$row_sql['id']);
                        $judgingNo = "";
                        $brewer_info = array();
                        $brewer_club = "";

                        if (isset($row_sql['brewBrewerID'])) $brewer_info = explode("^", brewer_info($row_sql['brewBrewerID']));
                        if (isset($row_sql['brewBrewerFirstName'])) $brewerFirstName = convert_to_entities($row_sql['brewBrewerFirstName']);
                        if (isset($row_sql['brewBrewerLastName'])) $brewerLastName = convert_to_entities($row_sql['brewBrewerLastName']);
                        if (isset($row_sql['brewName'])) $brewName = convert_to_entities($row_sql['brewName']);
                        if (isset($row_sql['brewInfo'])) {
                            $brewInfo = str_replace("^"," | ",$row_sql['brewInfo']);
                            $brewInfo = convert_to_entities($brewInfo);
                        }
                        if (isset($row_sql['brewComments'])) $brewSpecifics = convert_to_entities($row_sql['brewComments']);
                        if (isset($row_sql['brewInfoOptional'])) $brewInfoOptional = convert_to_entities($row_sql['brewInfoOptional']);
                        if (isset($row_sql['brewJudgingNumber'])) $judgingNo = sprintf("%06s",$row_sql['brewJudgingNumber']);
                        if (isset($row_sql['brewBrewerID'])) $brewer_info = explode("^", brewer_info($row_sql['brewBrewerID']));
                        if ((isset($brewer_info[8])) && ($brewer_info[8] != "&nbsp;")) {
                            $brewer_club = convert_to_entities($brewer_info[8]);
                        }
                        if (($action == "default") && (($tb == "winners") || ($tb == "circuit")) && ($winner_method >= 0)) {
                            include (DB.'output_entries_export_winner.db.php');
                        }
                        
                        // No participant email addresses
                        if (($action == "hccp") && (($filter != "winners") && ($tb != "winners"))) {

                            if ($_SESSION['prefsProEdition'] == 1) $a[] = array(
                                $brewerFirstName,
                                $brewerLastName,
                                convert_to_entities($brewer_info[15]),
                                convert_to_entities($brewer_info[17]),
                                convert_to_entities($row_sql['brewCategory']),
                                convert_to_entities($row_sql['brewSubCategory']),
                                convert_to_entities($row_sql['brewStyle']),
                                $entryNo,
                                $brewName,
                                $brewInfo,
                                convert_to_entities($row_sql['brewMead2']),
                                convert_to_entities($row_sql['brewMead1']),
                                convert_to_entities($row_sql['brewMead3'])
                            );
                            
                            else $a[] = array(
                                $brewerFirstName,
                                $brewerLastName,
                                convert_to_entities($row_sql['brewCategory']),
                                convert_to_entities($row_sql['brewSubCategory']),
                                convert_to_entities($row_sql['brewStyle']),
                                $entryNo,
                                $brewName,
                                $brewInfo,
                                convert_to_entities($row_sql['brewMead2']),
                                convert_to_entities($row_sql['brewMead1']),
                                convert_to_entities($row_sql['brewMead3'])
                            );

                        }

                        // With email addresses of participants.
                        if ((($action == "default") || ($action == "email")) && ($go == "csv") && (($filter != "winners") && ($tb != "winners"))) {

                            include (DB.'output_entries_export_extend.db.php');

                            if (!empty($brewer_info)) {

                                $scoreEntry = "";
                                $scorePlace = "";
                                $flightNumber = "";
                                $flightRound = "";

                                if ($row_scores) {
                                    $scoreEntry = sprintf("%02s",$row_scores['scoreEntry']);
                                    $scorePlace = $row_scores['scorePlace'];
                                }

                                if ($row_flight) {
                                    $flightNumber = $row_flight['flightNumber'];
                                    $flightRound = $row_flight['flightRound'];
                                }

                                if ($_SESSION['prefsProEdition'] == 1) $a[] = array(
                                    $brewerFirstName,
                                    $brewerLastName,
                                    convert_to_entities($brewer_info[15]),
                                    convert_to_entities($brewer_info[17]),
                                    convert_to_entities($brewer_info[6]),
                                    convert_to_entities($brewer_info[10]),
                                    convert_to_entities($brewer_info[11]),
                                    convert_to_entities($brewer_info[12]),
                                    convert_to_entities($brewer_info[13]),
                                    convert_to_entities($brewer_info[14]),
                                    $entryNo,
                                    $judgingNo,
                                    convert_to_entities($row_sql['brewCategory']),
                                    convert_to_entities($row_sql['brewSubCategory']),
                                    convert_to_entities($row_sql['brewStyle']),
                                    $brewName,
                                    $brewInfo,
                                    $brewSpecifics,
                                    convert_to_entities($row_sql['brewMead1']),
                                    convert_to_entities($row_sql['brewMead2']),
                                    convert_to_entities($row_sql['brewMead3']),
                                    convert_to_entities($table_name),
                                    convert_to_entities($location[2]),
                                    $flightNumber,
                                    $flightRound,
                                    $scoreEntry,
                                    $scorePlace,
                                    $bos_place
                                );

                                else $a[] = array(
                                    $brewerFirstName,
                                    $brewerLastName,
                                    convert_to_entities($brewer_info[6]),
                                    convert_to_entities($brewer_info[10]),
                                    convert_to_entities($brewer_info[11]),
                                    convert_to_entities($brewer_info[12]),
                                    convert_to_entities($brewer_info[13]),
                                    convert_to_entities($brewer_info[14]),
                                    $brewer_club,
                                    $entryNo,
                                    $judgingNo,
                                    convert_to_entities($row_sql['brewCategory']),
                                    convert_to_entities($row_sql['brewSubCategory']),
                                    convert_to_entities($row_sql['brewStyle']),
                                    $brewName,
                                    $brewInfo,
                                    $brewSpecifics,
                                    convert_to_entities($row_sql['brewMead1']),
                                    convert_to_entities($row_sql['brewMead2']),
                                    convert_to_entities($row_sql['brewMead3']),
                                    convert_to_entities($table_name),
                                    convert_to_entities($location[2]),
                                    $flightNumber,
                                    $flightRound,
                                    $scoreEntry,
                                    $scorePlace,
                                    $bos_place
                                );
                            }

                        }

                        if (($go == "csv") && ($action == "required") && ($tb == "required")) {

                            $a[] = array($entryNo,
                                $judgingNo,
                                convert_to_entities($row_sql['brewCategory']),
                                convert_to_entities($row_sql['brewSubCategory']),
                                convert_to_entities($row_sql['brewStyle']),
                                $brewName,
                                $brewInfo,
                                $brewSpecifics,
                                convert_to_entities($row_sql['brewMead1']),
                                convert_to_entities($row_sql['brewMead2']),
                                convert_to_entities($row_sql['brewMead3'])
                            );

                        }

                    } while ($row_sql = mysqli_fetch_assoc($sql));

                    if ($tb == "circuit") {

                        $total_entries = total_paid_received("judging_scores",0,$filter);

                        if ($filter == "default") {

                            include (DB.'judging_locations.db.php');
                            
                            $dates = array();
                            $final_date = "";
                            
                            if ($row_judging) { 
                                
                                do { 
                                    $dates[] = $row_judging['judgingDate']; 
                                } while ($row_judging = mysqli_fetch_assoc($judging)); 
                                
                                $final_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], max($dates), $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "system", "date-no-gmt");
                            
                            }

                            $a[] = array(); // One empty row as a separator
                            $a[] = array($label_final_judging_date,$label_entries_judged);
                            $a[] = array($final_date,$total_entries);

                        }

                        else {
                            $a[] = array(); // One empty row as a separator
                            $a[] = array($label_entries_judged);
                            $a[] = array($total_entries);
                        }
                        

                    }

                }

                header("Content-Type: text/csv; charset=utf-8");
                header('Content-Disposition: attachment;filename="'.$filename.'"');
                header('Pragma: no-cache');
                header('Expires: 0');

                $fp = fopen('php://output', 'w');
                fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));

                if ((isset($a)) && (is_array($a)) && (!empty($a))) {
                    foreach ($a as $fields) {
                        fputcsv($fp,$fields,$separator);
                    }
                }

                fclose($fp);

            }

        } // END if $admin_role

        else echo "Not Allowed";
        exit();

	} // END if ($section == "export-entries")

    /** 
     * -------------- EMAIL Exports -------------- 
     */

	if ($section == "export-emails") {

        if ($admin_role) {

            include (DB.'output_email_export.db.php');

            $separator = ",";
            $extension = ".csv";
            $contest = str_replace(' ', '_', $_SESSION['contestName']);
            $contest = ltrim(filename($contest),"_");
            if ($filter == "default") $type = "All_Participants";
            else $type = $filter;
            if ($section == "export-loc") {
                 $loc = str_replace(' ', '_', $row_judging['judgingLocName']);
                 $loc = filename($loc);
            }
            else $loc = "";
            $date = date("Y-m-d");

            // Appropriately name the CSV file for each type of download
            if ($filter == "judges")                $filename = $contest."_Assigned_Judge_Emails_".$date.$loc.$extension;
            elseif ($filter == "stewards")          $filename = $contest."_Assigned_Steward_Email_Addresses_".$date.$loc.$extension;
            elseif ($filter == "staff")             $filename = $contest."_Available_and_Assigned_Staff_Emails_".$date.$loc.$extension;
            elseif ($filter == "avail_judges")      $filename = $contest."_Available_Judge_Emails_".$date.$loc.$extension;
            elseif ($filter == "avail_stewards")    $filename = $contest."_Available_Steward_Emails_".$date.$loc.$extension;
            else                                    $filename = $contest."_All_Participant_Emails_".$date.$loc.$extension;

            // Set the header row of the CSV for each type of download
            if (($filter == "judges") || ($filter == "avail_judges")) $a [] = array(
                $label_first_name,
                $label_last_name,
                $label_email,
                $label_bjcp_rank,
                $label_bjcp_mead."?",
                $label_bjcp_cider."?",
                $label_bjcp_id,
                $label_avail,
                $label_judge_preferred,
                $label_judge_non_preferred,
                $label_entries
            );

            elseif (($filter == "stewards") || ($filter == "avail_stewards")) $a [] = array($label_first_name,$label_last_name,$label_email,$label_avail,$label_entries);

            elseif ($filter == "staff") $a [] = array($label_first_name,$label_last_name,$label_email,$label_avail,$label_assignment,$label_entries);

            else {
                if ($_SESSION['prefsProEdition'] == 1) $a [] = array($label_first_name,$label_last_name,$label_organization,$label_ttb,$label_email,$label_address,$label_city,$label_state_province,$label_zip,$label_country,$label_phone,$label_club,$label_entries);
                else $a [] = array($label_first_name,$label_last_name,$label_email,$label_address,$label_city,$label_state_province,$label_zip,$label_country,$label_phone,$label_club,$label_entries);
            }

            if ($totalRows_sql > 0) {

                do {
                    $brewerAddress = "";
                    $brewerCity = "";
                    $phone = "";
                    $brewerFirstName = "";
                    $brewerLastName = "";
                    $brewerAddress = "";
                    $brewerCity = "";
                    $phone = "";
                    $brewerJudgeMead = "";
                    $brewerJudgeCider = "";
                    $judge_avail = "";
                    $steward_avail = "";
                    $brewerEmail = "";
                    
                    if (isset($row_sql['brewerEmail'])) $brewerEmail = $row_sql['brewerEmail'];
                    if (isset($row_sql['brewerFirstName'])) $brewerFirstName = convert_to_entities($row_sql['brewerFirstName']);
                    if (isset($row_sql['brewerLastName'])) $brewerLastName = convert_to_entities($row_sql['brewerLastName']);
                    if ($filter == "default") {
                        if (isset($row_sql['brewerAddress'])) $brewerAddress = convert_to_entities($row_sql['brewerAddress']);
                        if (isset($row_sql['brewerCity'])) $brewerCity = convert_to_entities($row_sql['brewerCity']);
                        if (isset($row_sql['brewerPhone1'])) {
                            if ((isset($row_sql['brewerCountry'])) && ($row_sql['brewerCountry'] == "United States")) $phone = format_phone_us($row_sql['brewerPhone1']); 
                            else $phone = $row_sql['brewerPhone1'];
                        }
                    }

                    if (($filter == "judges") || ($filter == "avail_judges")) {
                        
                        $judge_entries = "";
                        if (isset($row_sql['uid'])) $judge_entries = judge_entries($row_sql['uid'],0);
                        if (isset($row_sql['brewerJudgeLocation'])) $judge_avail = judge_steward_availability($row_sql['brewerJudgeLocation'],2,$prefix);
                        if ((!empty($row_sql['brewerJudgeMead'])) && ($row_sql['brewerJudgeMead'] == "Y")) $brewerJudgeMead = $label_bjcp_mead;
                        if ((!empty($row_sql['brewerJudgeCider'])) && ($row_sql['brewerJudgeCider'] == "Y")) $brewerJudgeCider =
                            $label_bjcp_cider;
                        
                        $a [] = array(
                            $brewerFirstName,
                            $brewerLastName,
                            $brewerEmail,
                            str_replace(",",", ",$row_sql['brewerJudgeRank']),
                            $brewerJudgeMead,
                            $brewerJudgeCider,
                            strtoupper(strtr($row_sql['brewerJudgeID'],$bjcp_num_replace)),
                            $judge_avail,
                            style_convert($row_sql['brewerJudgeLikes'],'6',$base_url),
                            style_convert($row_sql['brewerJudgeDislikes'],'6',$base_url),
                            $judge_entries
                        );

                    }

                    elseif (($filter == "stewards") || ($filter == "avail_stewards")) {
                        $judge_entries = "";
                        if (isset($row_sql['uid'])) $judge_entries = judge_entries($row_sql['uid'],0);
                        if (isset($row_sql['brewerJudgeLocation'])) $judge_avail = judge_steward_availability($row_sql['brewerJudgeLocation'],2,$prefix);
                        $a [] = array(
                            $brewerFirstName,
                            $brewerLastName,
                            $brewerEmail,
                            $judge_avail,
                            $judge_entries
                        );
                    }

                    elseif ($filter == "staff") {
                        $judge_entries = "";
                        if (isset($row_sql['uid'])) $judge_entries = judge_entries($row_sql['uid'],0);
                        $assignment = $label_no;
                        if ($row_sql['staff_staff'] == 1) $assignment = $label_yes;
                        if (isset($row_sql['brewerJudgeLocation'])) $judge_avail = judge_steward_availability($row_sql['brewerJudgeLocation'],3,$prefix);
                        $a [] = array(
                            $brewerFirstName,
                            $brewerLastName,
                            $brewerEmail,
                            $judge_avail,
                            $assignment,
                            $judge_entries
                        );
                    }

                    else {
                        if ($_SESSION['prefsProEdition'] == 1) $a [] = array(
                            $brewerFirstName,
                            $brewerLastName,
                            convert_to_entities($row_sql['brewerBreweryName']),
                            convert_to_entities($row_sql['brewerBreweryTTB']),
                            $brewerEmail,
                            $brewerAddress,
                            $brewerCity,
                            convert_to_entities($row_sql['brewerState']),
                            convert_to_entities($row_sql['brewerZip']),
                            convert_to_entities($row_sql['brewerCountry']),
                            $phone,
                            convert_to_entities($row_sql['brewerClubs']),
                            judge_entries($row_sql['uid'],0)
                        );
                        
                        else $a [] = array(
                            $brewerFirstName,
                            $brewerLastName,
                            $brewerEmail,
                            $brewerAddress,
                            $brewerCity,
                            convert_to_entities($row_sql['brewerState']),
                            convert_to_entities($row_sql['brewerZip']),
                            convert_to_entities($row_sql['brewerCountry']),
                            $phone,
                            convert_to_entities($row_sql['brewerClubs']),
                            judge_entries($row_sql['uid'],0)
                        );
                    }

                } while ($row_sql = mysqli_fetch_assoc($sql));

            }

            header("Content-Type: text/csv; charset=utf-8");
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Pragma: no-cache');
            header('Expires: 0');

            $fp = fopen('php://output', 'w');
            fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));

            foreach ($a as $fields) {
                fputcsv($fp,$fields,$separator);
            }

            fclose($fp);

        } // END if $admin_role

        else echo "Not Allowed";
        exit();

	} // END if ($section == "export-emails")

    /** 
     * -------------- PARTICIPANT Exports -------------- 
     */

	if ($section == "export-participants") {

        if ($admin_role) {

            include (DB.'output_participants_export.db.php');

            if ($go == "csv") { 
                $separator = ","; 
                $extension = ".csv"; 
            }
            
            if ($go == "tab") { 
                $separator = "\t"; 
                $extension = ".tab"; 
            }

            $contest = str_replace(' ', '_', $_SESSION['contestName']);
            if ($section == "export-loc") $loc = "_".str_replace(' ', '_', $row_judging['judgingLocName']);
            else $loc = "";
            $date = date("m-d-Y");

            if ($_SESSION['prefsProEdition'] == 1) $a[] = array($label_first_name,$label_last_name,$label_organization,$label_ttb,$label_address,$label_city,$label_state_province,$label_zip,$label_country,$label_phone,$label_email,$label_club,$label_entries,$label_assignment,$label_bjcp_id,$label_bjcp_rank,$label_bjcp_mead."?",$label_bjcp_cider."?",$label_judge_preferred,$label_judge_non_preferred);
            
            else $a[] = array($label_first_name,$label_last_name,$label_address,$label_city,$label_state_province,$label_zip,$label_country,$label_phone,$label_email,$label_club,$label_entries,$label_assignment,$label_bjcp_id,$label_bjcp_rank,$label_bjcp_mead."?",$label_bjcp_cider."?",$label_judge_preferred,$label_judge_non_preferred);

            //echo $query_sql;

            do {

                $brewerFirstName = convert_to_entities($row_sql['brewerFirstName']);
                $brewerLastName = convert_to_entities($row_sql['brewerLastName']);
                $brewerAddress = convert_to_entities($row_sql['brewerAddress']);
                $brewerCity = convert_to_entities($row_sql['brewerCity']);
                $brewerState = convert_to_entities($row_sql['brewerState']);
                $brewerZip = convert_to_entities($row_sql['brewerZip']);
                $brewerCountry = convert_to_entities($row_sql['brewerCountry']);
                $brewerClubs = convert_to_entities($row_sql['brewerClubs']);
                $brewerJudgeMead = "";
                $brewerJudgeCider = "";
                if ((!empty($row_sql['brewerJudgeMead'])) && ($row_sql['brewerJudgeMead'] == "Y")) $brewerJudgeMead = $label_bjcp_mead;
                if ((!empty($row_sql['brewerJudgeCider'])) && ($row_sql['brewerJudgeCider'] == "Y")) $brewerJudgeCider =
                    $label_bjcp_cider;
                $assignment = brewer_assignment($row_sql['uid'],"1","blah","default","default");
                $assignment = strtolower($assignment);
                $assignment = str_replace(", ", ",", $assignment);
                $assignment = explode(",", $assignment);
                $assign = array();

                foreach ($assignment as $value) {
                    if (strpos($value,"judge") !== false) $assign[] = "Judge";
                    if (strpos($value,"bos") !== false) $assign[] = "BOS";
                    if (strpos($value,"steward") !== false) $assign[] = "Steward";
                }

                $assignment = implode(", ", $assign);

                if ($row_sql['brewerCountry'] == "United States") $phone = format_phone_us($row_sql['brewerPhone1']); 
                else $phone = $row_sql['brewerPhone1'];

                if ($_SESSION['prefsProEdition'] == 1) $a[] = array(
                    $brewerFirstName,
                    $brewerLastName,
                    convert_to_entities($row_sql['brewerBreweryName']),
                    convert_to_entities($row_sql['brewerBreweryTTB']),
                    $brewerAddress,
                    $brewerCity,
                    $brewerState,
                    $brewerZip,
                    $brewerCountry,
                    convert_to_entities($phone),
                    convert_to_entities($row_sql['brewerEmail']),
                    $brewerClubs,
                    judge_entries($row_sql['uid'],0),
                    $assignment,
                    $row_sql['brewerJudgeID'],
                    str_replace(",",", ",$row_sql['brewerJudgeRank']),
                    style_convert($row_sql['brewerJudgeLikes'],'6',$base_url),
                    style_convert($row_sql['brewerJudgeDislikes'],'6',$base_url)
                );

                else $a[] = array(
                    $brewerFirstName,
                    $brewerLastName,
                    $brewerAddress,
                    $brewerCity,
                    $brewerState,
                    $brewerZip,
                    $brewerCountry,
                    convert_to_entities($phone),
                    convert_to_entities($row_sql['brewerEmail']),
                    $brewerClubs,
                    judge_entries($row_sql['uid'],0),
                    $assignment,
                    $row_sql['brewerJudgeID'],
                    str_replace(",",", ",$row_sql['brewerJudgeRank']),
                    $brewerJudgeMead,
                    $brewerJudgeCider,
                    style_convert($row_sql['brewerJudgeLikes'],'6',$base_url),
                    style_convert($row_sql['brewerJudgeDislikes'],'6',$base_url)
                );

            } while ($row_sql = mysqli_fetch_assoc($sql));

            $filename = ltrim(filename($contest)."_Participants".filename($date).$loc.$extension,"_");

            header("Content-Type: text/csv; charset=utf-8");
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Pragma: no-cache');
            header('Expires: 0');

            $fp = fopen('php://output', 'w');
            fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));

            foreach ($a as $fields) {
                fputcsv($fp,$fields,$separator);
            }

            fclose($fp);

        } // END if $admin_role

        else echo "Not Allowed";
        exit();

	}

    /**
     * -------------- PROMO Exports --------------
     * Deprecated as of 2.7.0 
     */

	if ($section == "export-promo") {

		include (DB.'dropoff.db.php');
		include (DB.'sponsors.db.php');
		include (DB.'contacts.db.php');
		include (DB.'styles.db.php');
		include (DB.'judging_locations.db.php');
		include (DB.'entry_info.db.php');

		if ($_SESSION['contestHostWebsite'] != "") $website = $_SESSION['contestHostWebsite'];
		else $website = $_SERVER['SERVER_NAME'];

		if ($action == "word") {
			$filename = str_replace(" ", "_", $_SESSION['contestName'])."_Promo.doc";
			header('Content-Type: application/msword;');
			header('Content-Disposition: attachment; filename="'.$filename.'"');
		}

		if ($action == "html") {
			$filename = str_replace(" ", "_", $_SESSION['contestName'])."_Promo.html";
			header('Content-Type: text/plain;');
			header('Content-Disposition: attachment; filename="'.$filename.'"');
		}

		if ($action == "bbcode") {
			$filename= str_replace(" ", "_", $_SESSION['contestName'])."_Promo.txt";
			header('Content-Type: text/plain;');
			header('Content-Disposition: attachment; filename="'.$filename.'"');
		}

		$output = "";
		if ($action != "bbcode") {
		$output .= "<html>\n";
		$output .= "<body>\n";
		}

		$output .= "<h1>".$_SESSION['contestName']."</h1>\n";
		$output .= "<p>".$_SESSION['contestHost']." announces the ".$_SESSION['contestName'].".</p>\n";
		$output .= "<h2>Entries</h2>\n";
		$output .= "<p>To enter, please go to ".$website." and proceed through the registration process.</p>\n";
		$output .= "<h3>Entry Deadline</h3>\n";
		$output .= "<p>All entries must be received by our shipping location or at a drop-off location by "; $output .=  getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestEntryDeadline'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time").". Entries will not be accepted beyond this date.</p>\n";
		$output .= "<h3>Registration Deadline</h3>\n";
		$output .= "<p>Registration will close on "; $output .= getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestRegistrationDeadline'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time").". Please note: registered users will <em>not</em> be able to add, view, edit, or delete entries on the registration website after  this date.</p>\n";
		$output .= "<h2>Call for Judges and Stewards</h2>\n";
		$output .= "<p>If you are willing to be a judge or steward, please go to ".$website.", register, and fill out the appropriate information.</p>\n";
		$output .= "<h2>Competition Officials</h2>\n";

		$output .= "<ul>\n";
		do {
		$output .= "\t<li>".$row_contact['contactFirstName']." ".$row_contact['contactLastName']." &mdash; ".$row_contact['contactPosition']." (".$row_contact['contactEmail'].")</li>\n";
		} while ($row_contact = mysqli_fetch_assoc($contact));
		$output .= "</ul>\n";
		if ($_SESSION['prefsSponsors'] == "Y") {
		if ($totalRows_sponsors > 0) {
		$output .= "<h2>Sponsors</h2>\n";
		$output .= "<ul>\n";
			do { $output .= "\t<li>".$row_sponsors['sponsorName']."</li>\n"; } while ($row_sponsors = mysqli_fetch_assoc($sponsors));
		$output .= "</ul>\n";
			}
		} // end if prefs dictate display sponsors

		$output .= "<h2>Competition Rules</h2>\n";
		$output .= $row_contest_info['contestRules']."\n";
		$output .= "<h3>Entry Fee</h3>\n";
		$output .= "<p>".$currency_symbol.$_SESSION['contestEntryFee']." per entry."; if ($_SESSION['contestEntryFeeDiscount'] == "Y") $output .= $currency_symbol.number_format($_SESSION['contestEntryFee2'], 2)." per entry after ".$_SESSION['contestEntryFeeDiscountNum']." entries. "; if ($_SESSION['contestEntryCap'] != "") $output .= $currency_symbol.number_format($_SESSION['contestEntryCap'], 2)." for unlimited entries.";
		$output .= "</p>\n";
		$output .= "<h3>Payment</h3>\n";
		$output .= "<p>After registering and inputting entries, all participants must pay their entry fee(s). Accepted payment methods include:</p>\n";
		$output .= "<ul>\n";
			if ($_SESSION['prefsCash'] == "Y") $output .= "\t<li>".$entry_info_text_032."</li>\n";
			if ($_SESSION['prefsCheck'] == "Y") $output .= "\t<li>".$entry_info_text_033." <em>".$_SESSION['prefsCheckPayee']."</em></li>\n";
			if ($_SESSION['prefsPaypal'] == "Y") $output .= "\t<li>".$entry_info_text_034."</li>\n";
		$output .= "</ul>\n";

		$output .= "<h3>Competition Date"; if ($totalRows_judging > 1) $output .= "s"; $output .= "</h3>\n";
		if ($totalRows_judging == 0) $output .= "<p>".$entry_info_text_035."</p>\n"; else {
		  do {
		  $output .= "<p>";
		  $output .= "<strong>".$row_judging['judgingLocName']."</strong><br />";
			if ($row_judging['judgingDate'] != "") $output .= getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time");
			if ($row_judging['judgingLocation'] != "") $output .= "<br />".$row_judging['judgingLocation'];
		  $output .= "</p>\n";
			} while ($row_judging = mysqli_fetch_assoc($judging));
		}

		$output .= "<h3>Styles Accepted</h3>\n";
		$output .= "<ul>\n";
		do {
            if (array_key_exists($row_styles['id'], $styles_selected)) {
                $output .= "\t<li>";
                if ($_SESSION['prefsStyleSet'] != "BA") $output .= ltrim($row_styles['brewStyleGroup'], "0").$row_styles['brewStyleNum']." ";
                $output .= $row_styles['brewStyle'];
                if ($row_styles['brewStyleOwn'] == "custom") $output .= " (Special style: ".$_SESSION['contestName'].")";
                $output .= "</li>\n";
            }
		} while ($row_styles = mysqli_fetch_assoc($styles));
		$output .= "</ul>\n";

		if ($row_contest_info['contestBottles'] != "") {
			$output .= "<h3>Entry Acceptance Rules</h3>\n";
			$output .= $row_contest_info['contestBottles']."\n";
		}

		if ($_SESSION['contestShippingAddress'] != "") {
			$output .= "<h3>Shipping Address</h3>\n";
			$output .= "<p>".$_SESSION['contestShippingAddress']."</p>\n";
			$output .= "<h4>Packing &amp; Shipping Hints</h4>\n";
			$output .= "<ol>\n";
			$output .= "\t<li>".$entry_info_text_038."</li>\n";
            $output .= "\t<li>".$entry_info_text_039."</li>\n";
            $output .= "\t<li>".$entry_info_text_040."</li>\n";
			$output .= "\t<li>".$entry_info_text_041."</li>\n";
			$output .= "\t<li>".$entry_info_text_042."</li>\n";
			$output .= "</ol>\n";
		}

		if ($totalRows_dropoff > 0)  {
			$output .= "<h3>Drop-Off Locations</h3>\n";
			do {
				$output .= "<p><strong>".$row_dropoff['dropLocationName']."</strong><br />".$row_dropoff['dropLocation']."<br />".$row_dropoff['dropLocationPhone']."</p>\n";
			} while($row_dropoff = mysqli_fetch_assoc($dropoff));
		}

		if ($row_contest_info['contestBOSAward'] != "")
			$output .= "<h2>Best of Show</h2>\n";
			$output .= "<p>".$row_contest_info['contestBOSAward']."</p>\n";

		if ($row_contest_info['contestAwards'] != "") {
			$output .= "<h2>Awards</h2>\n";
			$output .= "<p>".$row_contest_info['contestAwards']."</p>\n";
		 }

		if ($row_contest_info['contestAwardsLocName'] != "") {
			$output .= "<h3>Award Ceremony</h3>\n";
			$output .= "<p>";
				if ($row_contest_info['contestAwardsLocDate'] != "")
				$output .= "<strong>".$row_contest_info['contestAwardsLocName']."</strong><br />";
				$output .= getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_info['contestAwardsLocTime'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time");
				if ($_SESSION['contestAwardsLocation'] != "") $output .= "<br />".$_SESSION['contestAwardsLocation'];
			$output .= "</p>\n";
		 }

		if ($action != "bbcode") {
			$output .= "</body>\n";
			$output .= "</html>\n";
		}

		if ($action == "bbcode") {
			$html   = array("<p>","</p>","<strong>","</strong>","<ul>","</ul>","<ol>","</ol>","<li>","</li>","<h1>","</h1>","<h2>","</h2>","<h3>","</h3>","<h4>","</h4>","<body>","</body>","<html>","</html>","<br />","<em>","</em>","&amp;","&mdash;");
			$bbcode = array("[left]","[/left]\r\n\r","[b]","[/b]","[list type=disc]\r","[/list]\r\n\r","[list type=upper-roman]\r","[/list]\r\n\r","[li]","[/li]\r","[size=xx-large]","[/size]\r\n\r","[size=x-large]","[/size]\r\n\r","[size=large]","[/size]\r\n\r","[b]","[/b]","","","","","\n","[i]","[/i]","&","-");
			$output = str_replace($html,$bbcode,$output);
		}
		echo $output;

	} // END if ($section == "export-promo")

    /**
     * -------------- RESULTS Exports -------------- 
     */

	if ($section == "export-results") {

        $results_download = FALSE;
        
        if (($_SESSION['prefsDisplayWinners'] == "Y") && (judging_winner_display($_SESSION['prefsWinnerDelay']))) $results_download = TRUE; 
        if ((isset($_SESSION['userLevel'])) && ($_SESSION['userLevel'] < 2)) $results_download = TRUE;

        if ($results_download) {

            if ($_SESSION['prefsProEdition'] == 1) {
                $td_width_place = 100;
                $td_width_name = 333;
                $td_width_entry = 333;
                $td_width_style = 334;
                $td_width_club = 0;
            }

            else {
                $td_width_place = 50;
                $td_width_name = 250;
                $td_width_entry = 300;
                $td_width_style = 300;
                $td_width_club = 200;
            }

            require(DB.'winners.db.php');
            require(DB.'output_results.db.php');

            if ($_SESSION['prefsProEdition'] == 1) $label_brewer = $label_organization; else $label_brewer = $label_brewer;

            /**
             * -------------- PDF OUTPUT -------------- 
             */

            if ($view == "pdf") {
               
                include (CLASSES.'fpdf/fpdf.php');
                include (CLASSES.'fpdf/exfpdf.php');
                include (CLASSES.'fpdf/easyTable.php');
                
                $pdf=new exFPDF();
                $pdf->AddPage();
                $pdf->SetFont('arial','',10);

                if ($go == "judging_scores") {

                    $filename = str_replace(" ","_",$_SESSION['contestName']).'_Winners.'.$view;

                    $string = sprintf("%s - %s",$label_winners,html_entity_decode($_SESSION['contestName']));
                    $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));                  
                    $title_table = new easyTable($pdf,1);
                    $title_table->easyCell($string, 'font-size:22; font-style:B; font-color:#000000;');
                    $title_table->printRow();
                    $title_table->endTable();

                    /**
                     * Winners by table/medal category
                     */

                    if ($winner_method == 0) {

                        do {
                            
                            $entry_count = get_table_info(1,"count_total",$row_tables['id'],$dbTable,"default");
                            if ($entry_count > 1) $entries = strtolower($label_entries);
                            else $entries = strtolower($label_entry);

                            if ($entry_count > 0) {

                                include (DB.'scores.db.php');

                                $title = sprintf("%s %s: %s (%s %s)",$label_table,$row_tables['tableNumber'],$row_tables['tableName'],$entry_count,$entries);
                                $title_table = new easyTable($pdf,1);
                                $title_table->easyCell($title, 'font-size:16; font-style:B; font-color:#000000;');
                                $title_table->printRow();
                                $title_table->endTable();

                                if ($totalRows_scores > 0) {

                                    if ($_SESSION['prefsProEdition'] == 0) $table = new easyTable($pdf, '{15, 45, 45, 40, 45}','align:L;border:1; border-color:#000000;');
                                    else $table = new easyTable($pdf, '{20, 55, 55, 50}','align:L;border:1; border-color:#000000;');
                                    $table->rowStyle('align:C;valign:M;bgcolor:#000000; font-color:#ffffff; font-style:B;');
                                    $table->easyCell($label_place);
                                    $table->easyCell($label_brewer);
                                    $table->easyCell($label_entry);
                                    $table->easyCell($label_style);
                                    if ($_SESSION['prefsProEdition'] == 0) $table->easyCell($label_club);
                                    $table->printRow(true);

                                    if (!empty($row_scores)) {
                                        
                                        do {

                                            $string = display_place($row_scores['scorePlace'],1);
                                            $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                                            $table->easyCell($string);
                                            
                                            if ($_SESSION['prefsProEdition'] == 1) $string = html_entity_decode($row_scores['brewerBreweryName']);
                                            else $string = html_entity_decode($row_scores['brewerFirstName']).' '.html_entity_decode($row_scores['brewerLastName']);
                                            $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                                            $table->easyCell($string);
                                            
                                            $string = html_entity_decode($row_scores['brewName']);
                                            $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                                            $table->easyCell($string);
                                            
                                            $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $row_scores['brewStyle'])));
                                            $table->easyCell($string);
                                            if ($_SESSION['prefsProEdition'] == 0) {
                                                $string = html_entity_decode($row_scores['brewerClubs']);
                                                $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                                                $table->easyCell($string);
                                            }
                                            $table->printRow();

                                        } while ($row_scores = mysqli_fetch_assoc($scores));
                                    
                                    } // end if (!empty($row_scores))

                                    $table->endTable();

                                } // end if ($totalRows_scores > 0)

                                else {
                                    $no_places_table = new easyTable($pdf,1);
                                    $no_places_table->easyCell($winners_text_007);
                                    $no_places_table->printRow();
                                    $no_places_table->endTable();
                                }
                            
                            } // end if ($entry_count > 0)

                        } while ($row_tables = mysqli_fetch_assoc($tables));

                    } // end if ($winner_method == 0)

                    /**
                     * Winners by style category
                     */

                    if ($winner_method == 1) {

                        $a = styles_active(0);

                        foreach (array_unique($a) as $style) {

                            if ($style > 0) {

                                include (DB.'winners_category.db.php');
                                if ($row_entry_count['count'] > 1) $entries = strtolower($label_entries); 
                                else $entries = strtolower($label_entry);
                                
                                if ($row_score_count['count'] > 0) {

                                    include (DB.'scores.db.php');

                                    if (!empty($row_scores)) {

                                        if ($_SESSION['prefsStyleSet'] == "BA") {
                                            include (INCLUDES.'ba_constants.inc.php');
                                            $title = sprintf("%s (%s %s)",$ba_category_names[$style],$row_entry_count['count'],$entries);
                                        }

                                        else $title = sprintf("%s: %s (%s %s)",$style,style_convert($style,"1",$base_url),$row_entry_count['count'],$entries);
                                        $title_table = new easyTable($pdf,1);
                                        $title_table->easyCell($title, 'font-size:16; font-style:B; font-color:#000000;');
                                        $title_table->printRow();
                                        $title_table->endTable();

                                        if ($totalRows_scores > 0) {

                                            if ($_SESSION['prefsProEdition'] == 0) $table = new easyTable($pdf, '{15, 45, 45, 40, 45}','align:L;border:1; border-color:#000000;');
                                            else $table = new easyTable($pdf, '{20, 55, 55, 50}','align:L;border:1; border-color:#000000;');
                                            $table->rowStyle('align:C;valign:M;bgcolor:#000000; font-color:#ffffff; font-style:B;');
                                            $table->easyCell($label_place);
                                            $table->easyCell($label_brewer);
                                            $table->easyCell($label_entry);
                                            $table->easyCell($label_style);
                                            if ($_SESSION['prefsProEdition'] == 0) $table->easyCell($label_club);
                                            $table->printRow(true);

                                            do {

                                                $string = display_place($row_scores['scorePlace'],1);
                                                $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                                                $table->easyCell($string);
                                                
                                                if ($_SESSION['prefsProEdition'] == 1) $string = html_entity_decode($row_scores['brewerBreweryName']);
                                                else $string = html_entity_decode($row_scores['brewerFirstName']).' '.html_entity_decode($row_scores['brewerLastName']); 
                                                $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                                                $table->easyCell($string);
                                                
                                                $string = html_entity_decode($row_scores['brewName']);
                                                $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                                                $table->easyCell($string);
                                                
                                                $string = truncate_string($row_scores['brewStyle'],30," ");
                                                $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                                                $table->easyCell($string);
                                                
                                                if ($_SESSION['prefsProEdition'] == 0) {
                                                    $string = html_entity_decode($row_scores['brewerClubs']);
                                                    $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                                                    $table->easyCell($string);
                                                }

                                                $table->printRow();

                                            } while ($row_scores = mysqli_fetch_assoc($scores));

                                            $table->endTable();

                                        } // end if ($totalRows_scores > 0)

                                        else {
                                            $no_places_table = new easyTable($pdf,1);
                                            $no_places_table->easyCell($winners_text_001);
                                            $no_places_table->printRow();
                                            $no_places_table->endTable();
                                        }                                          

                                    } // end if (!empty($row_scores))

                                } // end if ($row_score_count['count'] > 0)

                            } // end if ($style > 0)

                        } // end foreach

                    } // end if ($winner_method == 1)

                    /**
                     * Winners by style sub-category
                     */

                    if ($winner_method == 2) {

                        $styles = styles_active(2);
                        $styles = array_unique($styles);

                        foreach ($styles as $style) {

                            $style = explode("^",$style);
                            
                            include (DB.'winners_subcategory.db.php');

                            if (($row_entry_count['count'] > 0) && ($row_score_count['count'] > 0)) {

                                if ($row_entry_count['count'] > 1) $entries = strtolower($label_entries);
                                else $entries = strtolower($label_entry);

                                include (DB.'scores.db.php');

                                if ($row_scores) {

                                    if ($_SESSION['prefsStyleSet'] == "BA") $title = sprintf("%s (%s %s)",$style[2],$row_entry_count['count'],$entries);
                                    else $title = sprintf("%s%s: %s (%s %s)",ltrim($style[0],"0"),$style[1],$style[2],$row_entry_count['count'],$entries);

                                    $title_table = new easyTable($pdf,1);
                                    $title_table->easyCell($title, 'font-size:16; font-style:B; font-color:#000000;');
                                    $title_table->printRow();
                                    $title_table->endTable();

                                    if ($totalRows_scores > 0) {

                                        if ($_SESSION['prefsProEdition'] == 0) $table = new easyTable($pdf, '{15, 45, 45, 40, 45}','align:L;border:1; border-color:#000000;');
                                        else $table = new easyTable($pdf, '{20, 55, 55, 50}','align:L;border:1; border-color:#000000;');
                                        $table->rowStyle('align:C;valign:M;bgcolor:#000000; font-color:#ffffff; font-style:B;');
                                        $table->easyCell($label_place);
                                        $table->easyCell($label_brewer);
                                        $table->easyCell($label_entry);
                                        $table->easyCell($label_style);
                                        if ($_SESSION['prefsProEdition'] == 0) $table->easyCell($label_club);
                                        $table->printRow(true);

                                        do {

                                            $style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];

                                            $string = display_place($row_scores['scorePlace'],1);
                                            $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                                            $table->easyCell($string);
                                            
                                            if ($_SESSION['prefsProEdition'] == 1) $string = html_entity_decode($row_scores['brewerBreweryName']);
                                            else $string = html_entity_decode($row_scores['brewerFirstName']).' '.html_entity_decode($row_scores['brewerLastName']); 
                                            $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                                            $table->easyCell($string);
                                            
                                            $string = html_entity_decode($row_scores['brewName']);
                                            $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                                            $table->easyCell($string);
                                            
                                            $string = truncate_string($row_scores['brewStyle'],30," ");
                                            $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                                            $table->easyCell($string);
                                            
                                            if ($_SESSION['prefsProEdition'] == 0) {
                                                $string = html_entity_decode($row_scores['brewerClubs']);
                                                $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                                                $table->easyCell($string);
                                            }

                                            $table->printRow();

                                        } while ($row_scores = mysqli_fetch_assoc($scores));

                                        $table->endTable();

                                    } // end if ($totalRows_scores > 0)

                                    else {
                                        $no_places_table = new easyTable($pdf,1);
                                        $no_places_table->easyCell($winners_text_001);
                                        $no_places_table->printRow();
                                        $no_places_table->endTable();
                                    }

                                    

                                } // end if (!empty($row_scores))

                            } // end if (($row_entry_count['count'] > 0) && ($row_score_count['count'] > 0))

                        } // end foreach

                    } // end if ($winner_method == 2)

                } // end if ($go == "judging_scores")

                if ($go == "judging_scores_bos") {

                    if ($_SESSION['prefsProEdition'] == 1) $label_brewer = $label_organization; 
                    else $label_brewer = $label_brewer;
                    $filename = str_replace(" ","_",$_SESSION['contestName']).'_BOS_Results.'.$view;

                    $a = array();
                    do { 
                        $a[] = $row_style_types['id']; 
                    } while ($row_style_types = mysqli_fetch_assoc($style_types));
                    sort($a);

                    $string = sprintf("%s - %s",$label_bos,html_entity_decode($_SESSION['contestName']));
                    $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));                  
                    $title_table = new easyTable($pdf,1);
                    $title_table->easyCell($string, 'font-size:22; font-style:B; font-color:#000000;');
                    $title_table->printRow();
                    $title_table->endTable();

                    foreach (array_unique($a) as $type) {

                        include (DB.'output_results_download_bos.db.php');

                        if ($totalRows_bos > 0) {

                            $title_table = new easyTable($pdf,1);
                            $title_table->easyCell($row_style_type_1['styleTypeName'], 'font-size:16; font-style:B; font-color:#000000;');
                            $title_table->printRow();
                            $title_table->endTable();

                            if ($_SESSION['prefsProEdition'] == 0) $table = new easyTable($pdf, '{15, 45, 45, 40, 45}','align:L;border:1; border-color:#000000;');
                            else $table = new easyTable($pdf, '{20, 55, 55, 50}','align:L;border:1; border-color:#000000;');
                            $table->rowStyle('align:C;valign:M;bgcolor:#000000; font-color:#ffffff; font-style:B;');
                            $table->easyCell($label_place);
                            $table->easyCell($label_brewer);
                            $table->easyCell($label_entry);
                            $table->easyCell($label_style);
                            if ($_SESSION['prefsProEdition'] == 0) $table->easyCell($label_club);
                            $table->printRow(true);

                            do {

                                $style = $row_bos['brewCategory'].$row_bos['brewSubCategory'];

                                $string = display_place($row_bos['scorePlace'],1);
                                $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                                $table->easyCell($string);
                                
                                if ($_SESSION['prefsProEdition'] == 1) $string = html_entity_decode($row_bos['brewerBreweryName']);
                                else $string = html_entity_decode($row_bos['brewerFirstName']).' '.html_entity_decode($row_bos['brewerLastName']);
                                $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                                $table->easyCell($string);
                                
                                $string = html_entity_decode($row_bos['brewName']);
                                $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                                $table->easyCell($string);
                                
                                $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $row_bos['brewStyle'])));
                                $table->easyCell($string);
                                if ($_SESSION['prefsProEdition'] == 0) {
                                    $string = html_entity_decode($row_bos['brewerClubs']);
                                    $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                                    $table->easyCell($string);
                                }
                                $table->printRow();

                            } while ($row_bos = mysqli_fetch_assoc($bos));

                            $table->endTable();

                        } // end if ($totalRows_bos > 0)

                    } // end foreach

                    if ($totalRows_sbi > 0) {

                        do {

                            include (DB.'output_results_download_sbd.db.php');

                            if ($totalRows_sbd > 0) {
                                
                                $title_table = new easyTable($pdf,1);
                                $title_table->easyCell(html_entity_decode($row_sbi['sbi_name']), 'font-size:16; font-style:B; font-color:#000000;');
                                $title_table->printRow();
                                if (!empty($row_sbi['sbi_description'])) {
                                    $string = html_entity_decode($row_sbi['sbi_description']);
                                    $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                                    $title_table->easyCell($string);
                                }
                                $title_table->endTable();

                                if ($_SESSION['prefsProEdition'] == 0) $table = new easyTable($pdf, '{15, 45, 45, 40, 45}','align:L;border:1; border-color:#000000;');
                                else $table = new easyTable($pdf, '{20, 55, 55, 50}','align:L;border:1; border-color:#000000;');
                                $table->rowStyle('align:C;valign:M;bgcolor:#000000; font-color:#ffffff; font-style:B;');
                                $table->easyCell($label_place);
                                $table->easyCell($label_brewer);
                                $table->easyCell($label_entry);
                                $table->easyCell($label_style);
                                if ($_SESSION['prefsProEdition'] == 0) $table->easyCell($label_club);
                                $table->printRow(true);

                                do {

                                    $brewer_info = explode("^",brewer_info($row_sbd['bid']));
                                    $entry_info = explode("^",entry_info($row_sbd['eid']));
                                    $style = $entry_info['5'].$entry_info['2'];

                                    $string = "";
                                    if ($row_sbi['sbi_display_places'] == "1") $string = display_place($row_sbd['sbd_place'],4);
                                    $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                                    $table->easyCell($string);
                                    
                                    if ($_SESSION['prefsProEdition'] == 1) $string = html_entity_decode($brewer_info['15']);
                                    else $string = html_entity_decode($brewer_info['0'])." ".html_entity_decode($brewer_info['1']);
                                    $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                                    $table->easyCell($string);
                                    
                                    $string = html_entity_decode($entry_info['0']);
                                    $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                                    $table->easyCell($string);
                                    
                                    $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $entry_info['3'])));
                                    $table->easyCell($string);
                                    if ($_SESSION['prefsProEdition'] == 0) {
                                        $string = html_entity_decode($brewer_info['8']);
                                        $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                                        $table->easyCell($string);
                                    }
                                    $table->printRow();

                                } while ($row_sbd = mysqli_fetch_assoc($sbd));

                                $table->endTable();
                            
                            } // end if ($totalRows_sbd > 0)

                        } while ($row_sbi = mysqli_fetch_assoc($sbi));

                    } // end  if ($totalRows_sbi > 0)

                } // end if ($go == "judging_scores_bos")

                $pdf->Output($filename,'D');

            } // end if ($view == "pdf")

            /**
             * -------------- HTML OUTPUT -------------- 
             */

            if ($view == "html") {
                
                $header .= '<!DOCTYPE html>';
                $header .= '<head>';
                $header .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
                $header .= '<title>Results - '.$_SESSION['contestName'].'</title>';
                $header .= '<style>';
                $header .= 'body {font-family: Arial,sans-serif; font-size: 12px; }';
                $header .= '</style>';
                $header .= '</head>';
                $header .= '<body>';

                if ($go == "judging_scores") {
                    
                    $html = '';                
                    $html .= '<h1>Winners - '.$_SESSION['contestName'].'</h1>';
                    $filename = str_replace(" ","_",$_SESSION['contestName']).'_Results.'.$view;

                    /**
                     * Winners by table/medal group
                     */

                    if ($winner_method == 0) {

                        do {
                            
                            $entry_count = get_table_info(1,"count_total",$row_tables['id'],$dbTable,"default");

                            if ($entry_count > 1) $entries = strtolower($label_entries);
                            else $entries = strtolower($label_entry);

                            if ($entry_count > 0) {

                                include (DB.'scores.db.php');

                                $html .= '<h2>'.$label_table.' '.$row_tables['tableNumber'].': '.$row_tables['tableName'].' ('.$entry_count.' '.$entries.')</h2>';
                                $html .= '<table border="1" cellpadding="5" cellspacing="0" width="'.$table_width.'">';
                                $html .= '<tr>';
                                $html .= '<td width="'.$td_width_place.'" align="center"  bgcolor="#cccccc" nowrap="nowrap"><strong>'.$label_place.'</strong></td>';
                                $html .= '<td width="'.$td_width_name.'" align="center" bgcolor="#cccccc"><strong>'.$label_brewer.'</strong></td>';
                                $html .= '<td width="'.$td_width_entry.'" align="center" bgcolor="#cccccc"><strong>'.$label_entry.' '.$label_name.'</strong></td>';
                                $html .= '<td width="'.$td_width_entry.'" align="center" bgcolor="#cccccc"><strong>'.$label_style.'</strong></td>';
                                if ($_SESSION['prefsProEdition'] == 0) $html .= '<td width="175" align="center" bgcolor="#cccccc"><strong>'.$label_club.'</strong></td>';
                                $html .= '</tr>';

                                do {
                                    
                                    $style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];
                                    $html .= '<tr>';
                                    
                                    $html .= '<td width="'.$td_width_place.'">'.display_place($row_scores['scorePlace'],1).'</td>';
                                    if ($_SESSION['prefsProEdition'] == 1) $html .= '<td width="'.$td_width_name.'">'.html_entity_decode($row_scores['brewerBreweryName']).'</td>';
                                    else $html .= '<td width="'.$td_width_name.'">'.html_entity_decode($row_scores['brewerFirstName']).' '.html_entity_decode($row_scores['brewerLastName']).'</td>';
                                    
                                    $html .= '<td width="'.$td_width_entry.'">';
                                    if ($row_scores['brewName'] != '') $html .= html_entity_decode($row_scores['brewName']); 
                                    else $html .= '&nbsp;';
                                    $html .= '</td>';
                                    
                                    $html .= '<td width="'.$td_width_style.'">';
                                    if ($row_scores['brewStyle'] != '') $html .= html_entity_decode($row_scores['brewStyle']); else $html .= "&nbsp;";
                                    $html .= '</td>';
                                    
                                    if ($_SESSION['prefsProEdition'] == 0) {
                                        $html .= '<td width="175">';
                                        if ($row_scores['brewerClubs'] != "") $html .= html_entity_decode($row_scores['brewerClubs']);
                                        else $html .= "&nbsp;";
                                        $html .= '</td>';
                                    }

                                    $html .= '</tr>';

                                } while ($row_scores = mysqli_fetch_assoc($scores));

                                $html .= '</table>';

                            } // end if ($entry_count > 0)

                        } while ($row_tables = mysqli_fetch_assoc($tables));

                    } // end if ($winner_method == 0)

                    /**
                     * Winners by style category
                     */

                    if ($winner_method == 1) {

                        $a = styles_active(0);

                        foreach (array_unique($a) as $style) {

                            if ($style > 0) {

                                include (DB.'winners_category.db.php');

                                // Display all winners
                                if ($row_entry_count['count'] > 1) $entries = strtolower($label_entries); 
                                else $entries = strtolower($label_entry);
                                
                                if ($row_score_count['count'] > 0) {

                                    $style_trimmed = ltrim($style,"0");

                                    if ($_SESSION['prefsStyleSet'] == "BA") {
                                        include (INCLUDES.'ba_constants.inc.php');
                                        $html .= '<h2>'.$ba_category_names[$style].' ('.$row_entry_count['count'].' '.$entries.')</h2>';
                                    }

                                    else $html .= '<h2>Style '.$style_trimmed.': '.style_convert($style,"1",$base_url).' ('.$row_entry_count['count'].' '.$entries.')</h2>';

                                    $html .= '<table border="1" cellpadding="5" cellspacing="0" width="'.$table_width.'">';
                                    $html .= '<tr>';
                                    $html .= '<td width="'.$td_width_place.'" align="center"  bgcolor="#cccccc" nowrap="nowrap"><strong>'.$label_place.'</strong></td>';
                                    $html .= '<td width="'.$td_width_name.'" align="center" bgcolor="#cccccc"><strong>'.$label_brewer.'</strong></td>';
                                    $html .= '<td width="'.$td_width_entry.'" align="center" bgcolor="#cccccc"><strong>'.$label_entry.' '.$label_name.'</strong></td>';
                                    $html .= '<td width="'.$td_width_style.'" align="center" bgcolor="#cccccc"><strong>'.$label_style.'</strong></td>';
                                    if ($_SESSION['prefsProEdition'] == 0) $html .= '<td width="175" align="center" bgcolor="#cccccc"><strong>'.$label_club.'</strong></td>';
                                    $html .= '</tr>';

                                    include (DB.'scores.db.php');

                                    do {
                                        $style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];
                                        $html .= '<tr>';
                                        $html .= '<td width="'.$td_width_place.'">'.display_place($row_scores['scorePlace'],1).'</td>';
                                        if ($_SESSION['prefsProEdition'] == 1) $html .= '<td width="'.$td_width_name.'">'.html_entity_decode($row_scores['brewerBreweryName']).'</td>';
                                        else $html .= '<td width="'.$td_width_name.'">'.html_entity_decode($row_scores['brewerFirstName']).' '.html_entity_decode($row_scores['brewerLastName']).'</td>';
                                        $html .= '<td width="'.$td_width_entry.'">';
                                        if ($row_scores['brewName'] != '') $html .= html_entity_decode($row_scores['brewName']); 
                                        else $html .= '&nbsp;';
                                        $html .= '</td>';
                                        $html .= '<td width="'.$td_width_style.'">';
                                        if ($row_scores['brewStyle'] != '') $html .= html_entity_decode($row_scores['brewStyle']); else $html .= "&nbsp;";
                                        $html .= '</td>';
                                        if ($_SESSION['prefsProEdition'] == 0) {
                                            $html .= '<td width="175">';
                                            if ($row_scores['brewerClubs'] != "") $html .= html_entity_decode($row_scores['brewerClubs']);
                                            else $html .= "&nbsp;";
                                            $html .= '</td>';
                                        }
                                        $html .= '</tr>';
                                    } while ($row_scores = mysqli_fetch_assoc($scores));

                                    $html .= '</table>';

                                } // end if ($row_score_count['count'] > 0)

                            } // end if ($style > 0)

                        } // end foreach

                    } // end if ($winner_method == 1)

                    /**
                     * Winners by style sub-category
                     */

                    if ($winner_method == 2) {

                        $styles = styles_active(2);

                        foreach (array_unique($styles) as $style) {

                            $style = explode("^",$style);
                            include (DB.'winners_subcategory.db.php');

                            if (($row_entry_count['count'] > 0) && ($row_score_count['count'] > 0)) {

                                if ($row_entry_count['count'] > 1) $entries = strtolower($label_entries);
                                else $entries = strtolower($label_entry);


                                if ($_SESSION['prefsStyleSet'] == "BA") $html .= '<h2>'.$style[2].' ('.$row_entry_count['count'].' '.$entries.')</h2>';
                                else $html .= '<h2>Style '.ltrim($style[0],"0").$style[1].': '.$style[2].' ('.$row_entry_count['count'].' '.$entries.')</h2>';

                                $html .= '<table border="1" cellpadding="5" cellspacing="0" width="'.$table_width.'">';
                                $html .= '<tr>';
                                $html .= '<td width="'.$td_width_place.'" align="center"  bgcolor="#cccccc" nowrap="nowrap"><strong>'.$label_place.'</strong></td>';
                                $html .= '<td width="'.$td_width_name.'" align="center" bgcolor="#cccccc"><strong>'.$label_brewer.'</strong></td>';
                                $html .= '<td width="'.$td_width_entry.'" align="center" bgcolor="#cccccc"><strong>'.$label_entry.' '.$label_name.'</strong></td>';
                                $html .= '<td width="'.$td_width_style.'" align="center" bgcolor="#cccccc"><strong>'.$label_style.'</strong></td>';
                                if ($_SESSION['prefsProEdition'] == 0) $html .= '<td width="175" align="center" bgcolor="#cccccc"><strong>'.$label_club.'</strong></td>';
                                $html .= '</tr>';

                                include (DB.'scores.db.php');

                                do {
                                    
                                    $style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];
                                    $html .= '<tr>';
                                    
                                    $html .= '<td width="'.$td_width_place.'">'.display_place($row_scores['scorePlace'],1).'</td>';
                                    if ($_SESSION['prefsProEdition'] == 1) $html .= '<td width="'.$td_width_name.'">'.html_entity_decode($row_scores['brewerBreweryName']).'</td>';
                                    else $html .= '<td width="'.$td_width_name.'">'.html_entity_decode($row_scores['brewerFirstName']).' '.html_entity_decode($row_scores['brewerLastName']).'</td>';

                                    $html .= '<td width="'.$td_width_entry.'">';
                                    if ($row_scores['brewName'] != '') $html .= html_entity_decode($row_scores['brewName']); else $html .= '&nbsp;';
                                    $html .= '</td>';

                                    $html .= '<td width="'.$td_width_style.'">';
                                    if ($row_scores['brewStyle'] != '') $html .= html_entity_decode($row_scores['brewStyle']); 
                                    else $html .= "&nbsp;";
                                    $html .= '</td>';

                                    if ($_SESSION['prefsProEdition'] == 0) {
                                        $html .= '<td width="175">';
                                        if ($row_scores['brewerClubs'] != "") $html .= html_entity_decode($row_scores['brewerClubs']);
                                        else $html .= "&nbsp;";
                                        $html .= '</td>';
                                    }
                                    
                                    $html .= '</tr>';

                                } while ($row_scores = mysqli_fetch_assoc($scores));

                                $html .= '</table>';

                            } // end if (($row_entry_count['count'] > 0) && ($row_score_count['count'] > 0))

                        } // end foreach

                    } // end if ($winner_method == 2)

                } // end if ($go == "judging_scores")
            
                if ($go == "judging_scores_bos") {

                    if ($_SESSION['prefsProEdition'] == 1) $label_brewer = $label_organization; else $label_brewer = $label_brewer;

                    $html = '';

                    /*
                    if ($view == "pdf") {
                        $pdf->SetFont('Arial','B',16);
                        $pdf->Write(5,'Best of Show Results - '.$_SESSION['contestName']);
                        $pdf->SetFont('Arial','',7);
                    }
                    */
                    $filename = str_replace(" ","_",$_SESSION['contestName']).'_BOS_Results.'.$view;

                    $a = array();

                    do { $a[] = $row_style_types['id']; } while ($row_style_types = mysqli_fetch_assoc($style_types));

                    if ($view == "html") $html .= '<h1>BOS - '.html_entity_decode($_SESSION['contestName']).'</h1>';

                    sort($a);

                    foreach (array_unique($a) as $type) {

                        include (DB.'output_results_download_bos.db.php');

                        if ($totalRows_bos > 0) {

                            //if ($view == "pdf") $html .= '<br><br><strong>'.$row_style_type_1['styleTypeName'].'</strong><br>';
                            //else
                            $html .= '<h2>'.$row_style_type_1['styleTypeName'].'</h2>';
                            $html .= '<table border="1" cellpadding="5" cellspacing="0" width="'.$table_width.'">';
                            $html .= '<tr>';
                            $html .= '<td width="'.$td_width_place.'" align="center"  bgcolor="#cccccc" nowrap="nowrap"><strong>'.$label_place.'</strong></td>';
                            $html .= '<td width="'.$td_width_name.'" align="center" bgcolor="#cccccc"><strong>'.$label_brewer.'</strong></td>';
                            $html .= '<td width="'.$td_width_entry.'" align="center" bgcolor="#cccccc"><strong>'.$label_entry.' '.$label_name.'</strong></td>';
                            $html .= '<td width="'.$td_width_style.'" align="center" bgcolor="#cccccc"><strong>'.$label_style.'</strong></td>';
                            if ($_SESSION['prefsProEdition'] == 0) $html .= '<td width="175" align="center" bgcolor="#cccccc"><strong>'.$label_club.'</strong></td>';
                            $html .= '</tr>';

                            do {

                                $style = $row_bos['brewCategory'].$row_bos['brewSubCategory'];

                                $html .= '<tr>';
                                
                                // Place
                                $html .= '<td width="'.$td_width_place.'" nowrap="nowrap">'.display_place($row_bos['scorePlace'],1).'</td>';
                                
                                // Brewer
                                if ($_SESSION['prefsProEdition'] == 1) {
                                    $html .= '<td width="'.$td_width_name.'">'.html_entity_decode($row_bos['brewerBreweryName']);
                                    $html .= '</td>';
                                }
                                else {
                                    $html .= '<td width="'.$td_width_name.'">'.html_entity_decode($row_bos['brewerFirstName']).' '.html_entity_decode($row_bos['brewerLastName']);
                                    if (($_SESSION['prefsProEdition'] == 0) && ($row_bos['brewCoBrewer'] != "")) $html .=', '.html_entity_decode(truncate_string($row_bos['brewCoBrewer'],20," "));
                                    $html .= '</td>';
                                }

                                $html .= '<td width="'.$td_width_entry.'">'.html_entity_decode($row_bos['brewName']).'</td>';
                                $html .= '<td width="'.$td_width_style.'">'.html_entity_decode($row_bos['brewStyle']).'</td>';

                                if ($_SESSION['prefsProEdition'] == 0) {
                                    $html .= '<td width="175">';
                                    if ($row_bos['brewerClubs'] != "") $html .= html_entity_decode($row_bos['brewerClubs']);
                                    else $html .= "&nbsp;";
                                    $html .= '</td>';
                                }

                                $html .= '</tr>';


                            } while ($row_bos = mysqli_fetch_assoc($bos));

                            $html .= '</table>';

                        } // end if ($totalRows_bos > 0)

                    } // end foreach (array_unique($a) as $type)

                    if ($totalRows_sbi > 0) {

                        do {

                            include (DB.'output_results_download_sbd.db.php');

                            if ($totalRows_sbd > 0) {
                            //if ($view == "pdf") $html .= '<br><br><strong>'.html_entity_decode($row_sbi['sbi_name']).'</strong>';
                            //else 
                            $html .= '<h2>'.html_entity_decode($row_sbi['sbi_name']).'</h2>';
                            if (!empty($row_sbi['sbi_description'])) {
                                $html .= '<br>'.html_entity_decode($row_sbi['sbi_description']).'<br>';
                            }
                            else {
                                // if ($view == "pdf") $html .= "<br>";
                            }
                            $html .= '<table border="1" cellpadding="5" cellspacing="0" width="'.$table_width.'">';
                            $html .= '<tr>';
                            if ($row_sbi['sbi_display_places'] == "1") $html .= '<td width="'.$td_width_place.'" align="center"  bgcolor="#cccccc" nowrap="nowrap"><strong>'.$label_place.'</strong></td>';
                            $html .= '<td width="'.$td_width_name.'" align="center" bgcolor="#cccccc"><strong>'.$label_brewer.'</strong></td>';
                            $html .= '<td width="'.$td_width_entry.'" align="center" bgcolor="#cccccc"><strong>'.$label_entry.' '.$label_name.'</strong></td>';
                            $html .= '<td width="'.$td_width_style.'" align="center" bgcolor="#cccccc"><strong>'.$label_style.'</strong></td>';
                            if ($_SESSION['prefsProEdition'] == 0) $html .= '<td width="175" align="center" bgcolor="#cccccc"><strong>'.$label_club.'</strong></td>';
                            $html .= '</tr>';

                            do {
                                $brewer_info = explode("^",brewer_info($row_sbd['bid']));
                                $entry_info = explode("^",entry_info($row_sbd['eid']));
                                $style = $entry_info['5'].$entry_info['2'];
                                $html .= '<tr>';
                                if ($row_sbi['sbi_display_places'] == "1") { $html .= '<td width="'.$td_width_place.'" nowrap="nowrap">'.display_place($row_sbd['sbd_place'],4).'</td>'; }
                                $html .= '<td width="'.$td_width_name.'">'.html_entity_decode($brewer_info['0'])." ".html_entity_decode($brewer_info['1']);
                                    if (($row_sbd['brewCoBrewer'] != "") && ($view == "html")) $html .= "<br />Co-Brewer: ".html_entity_decode($entry_info['4']);
                                $html .= '</td>';
                                $html .= '<td width="'.$td_width_entry.'">'.html_entity_decode($entry_info['0']).'</td>';
                                $html .= '<td width="'.$td_width_style.'">'.$entry_info['3'].'</td>';
                                $html .= '<td width="175">';
                                if (($_SESSION['prefsProEdition'] == 0) && ($brewer_info['8'] != "")) $html .=html_entity_decode($brewer_info['8']);
                                else $html .= "&nbsp;";
                                $html .= '</td>';
                                $html .= '</tr>';
                                if ($row_sbd['sbd_comments'] != "") {
                                    $html .= '<tr>';
                                    if ($row_sbi['sbi_display_places'] == "1") $html .= '<td width="760" colspan="5"><em>'.$row_sbd['sbd_comments'].'</em></td>';
                                    else $html .= '<td width="725" colspan="4"><em>'.$row_sbd['sbd_comments'].'</em></td>';
                                    $html .= '</tr>';
                                }
                            } while ($row_sbd = mysqli_fetch_assoc($sbd));

                            $html .= '</table>';

                            }

                        } while ($row_sbi = mysqli_fetch_assoc($sbi));

                    } // end if ($totalRows_sbi > 0)

                } // end if ($go == "judging_scores_bos")

                $footer = '</body>';
                $footer .= '</html>';
                header("Content-Type: application/force-download");
                header('Content-Disposition: attachment;filename="'.$filename.'"');
                header("Pragma: no-cache");
                header("Expires: 0");
                echo $header.$html.$footer;
                exit();

            } // end  if ($view == "html")

        } // end if results display enabled

        else echo "Not allowed. Results have not been published yet.";
        exit(); 		

	} // END if ($section == "export-results")

/* -------------- STAFF Exports -------------- */

	if ($section == "export-staff") {

        if ($admin_role) {

            include (DB.'judging_locations.db.php');
            include (DB.'styles.db.php');
            include (DB.'admin_common.db.php');
            include (DB.'output_staff_points.db.php');

            // Get total amount of paid and received entries
            $total_entries = total_paid_received("judging_scores",0);
            //$total_entries = 750;

            $st_running_total = array();

            // Figure out whether BOS Judge Points are awarded or not
            // "BOS points may only be awarded if a competition has at least 30 entries in at least five beer and/or three mead/cider categories."
            $beer_styles = array();
            $mead_styles = array();
            $cider_styles = array();

            $beer_styles[] = array();
            $mead_styles[] = array();
            $cider_styles[] = array();

            do {

                if (($row_styles2['brewStyleType'] == "Cider") || ($row_styles2['brewStyleType'] == "2")) {
                    $beer_styles[] = 0;
                    $mead_styles[] = 0;
                    $cider_styles[] = 1;
                }

                elseif (($row_styles2['brewStyleType'] == "Mead") || ($row_styles2['brewStyleType'] == "3")) {
                    $beer_styles[] = 0;
                    $mead_styles[] = 1;
                    $cider_styles[] = 0;
                }

                else  {
                    $beer_styles[] = 1;
                    $mead_styles[] = 0;
                    $cider_styles[] = 0;
                }

            } while ($row_styles2 = mysqli_fetch_assoc($styles2));

            $beer_styles_total = array_sum($beer_styles);
            $mead_styles_total = array_sum($mead_styles);
            $cider_styles_total = array_sum($cider_styles);

            $mead_cider_total = $mead_styles_total + $cider_styles_total;
            $all_styles_total = $beer_styles_total + $mead_styles_total + $cider_styles_total;

            if (($total_entries >= 30) && (($beer_styles_total >= 5) || ($mead_cider_total >= 3))) $bos_judge_points = 0.5;
            else $bos_judge_points = 0.0;

            // Get the amount of days the competition took place
            $days = number_format(total_days(),1);

            // Get the number of judging sessions
            $sessions = number_format(total_sessions(),1);

            $a = array();
            $j = array();
            $s = array();
            $st = array();
            $o = array();
            $dates = array();
            if ($totalRows_organizer > 0) $organ_bjcp_id = strtoupper(strtr($row_org['brewerJudgeID'],$bjcp_num_replace));
            else $organ_bjcp_id = "999999999999";
            if ($row_judges) { do { $j[] = $row_judges['uid']; } while ($row_judges = mysqli_fetch_assoc($judges)); }
            if ($row_stewards) { do { $s[] = $row_stewards['uid']; } while ($row_stewards = mysqli_fetch_assoc($stewards)); }
            if ($row_staff) { do { $st[] = $row_staff['uid']; } while ($row_staff = mysqli_fetch_assoc($staff)); }
            if ($row_organizer) { do { $o[] = $row_organizer['uid']; } while ($row_organizer = mysqli_fetch_assoc($organizer)); }
            if ($row_judging) { do { $a[] = $row_judging['id']; $dates[] = $row_judging['judgingDate']; } while ($row_judging = mysqli_fetch_assoc($judging)); }

            /**
             * DEBUG
             * 
            $days = number_format(total_days(),1);
            $sessions = number_format(total_sessions(),1);

            echo "BOS Points: ".$bos_judge_points."<br>";
            echo "Organizer Max Points: ".$organ_max_points."<br>";
            echo "Staff Max Points: ".$staff_max_points."<br>";
            echo "Judge Max Points: ".$judge_max_points."<br>";
            echo "Days: ".$days."<br>";
            echo "Sessions: " .$sessions."<br>";

            $uid = 87;

            foreach (array_unique($a) as $location) {

                $query_assignments = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE bid='%s' AND assignLocation='%s' AND assignment='J'", $prefix."judging_assignments", $uid, $location);
                $assignments = mysqli_query($connection,$query_assignments) or die (mysqli_error($connection));
                $row_assignments = mysqli_fetch_assoc($assignments);

                // 0.5 points per session
                $number = $row_assignments['count'] * 0.5;
                if ($number > 0.5) $b[] = 0.5;
                else $b[] = $number;

            }

            print_r($b);
            echo "<br>";

            $points = array_sum($b);

            echo "Points After Assignment Count: ".$points."<br>";

            // Minimum of 0.5 points per session
            // "Judges earn points at a rate of 0.5 Judge Points per session."
            $max_comp_points = ($sessions * 0.5);

            echo "Max for all Sessions: ".$max_comp_points."<br>";

            // Cannot exceed more than 1.5 points per *day*
            // "Judges earn a maximum of 1.5 Judge Points per day."
            if ($points > ($days * 1.5)) $points = ($days * 1.5);
            else $points = $points;

            echo "Points After Day Calc: ".$points."<br>";

            // Cannot exceed the maximum amount of points possible for the entire competition
            if ($points > $max_comp_points) $points = $max_comp_points;
            else $points = $points;

            echo "Points After Max Points Calc: ".$points."<br>";

            if ($points > $judge_max_points) $points = $judge_max_points;
            else $points = $points;

            echo "Points After Max JUDGE Points Calc: ".$points."<br>";

            // If points are below the minimum, award minimum
            if ($points < 1) $points = 1;
            else $points = $points;

            echo "Points After Check Below 0.5: ".$points."<br>";

            // echo judge_points("16","N",$judge_max_points);

            exit();
            */

            if ($view == "pdf") {

                $filename = str_replace(" ","_",$_SESSION['contestName']).'_BJCP_Points_Report.'.$view;
                include(CLASSES.'fpdf/fpdf.php');
                include(CLASSES.'fpdf/exfpdf.php');
                include(CLASSES.'fpdf/easyTable.php');

                $pdf = new exFPDF();
                $pdf->AddPage();
                $pdf->SetFont('arial','',10);

                $title = sprintf("%s %s",html_entity_decode($_SESSION['contestName']),$output_text_024);
                $title = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $title)));
                
                $title_table = new easyTable($pdf,1);
                $title_table->easyCell($title, 'font-size:22; font-style:B; font-color:#000000;');
                $title_table->printRow();
                
                $string = sprintf("%s: %s",$label_comp_id,$_SESSION['contestID']);
                $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                $title_table->easyCell($string);
                $title_table->printRow();

                $string = sprintf("%s: %s",$label_entries,$total_entries);
                $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                $title_table->easyCell($string);
                $title_table->printRow();

                $string = sprintf("%s: %s",$label_days,total_days());
                $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                $title_table->easyCell($string);
                $title_table->printRow();

                $string = sprintf("%s: %s",$label_sessions,total_sessions());
                $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                $title_table->easyCell($string);
                $title_table->printRow();

                $string = sprintf("%s: %s (%s)",$label_flights,total_flights(),$output_text_023);
                $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                $title_table->easyCell($string);
                $title_table->printRow();
                $title_table->endTable();

                if ($totalRows_organizer > 0) {

                    $organizer_title = $label_organizer;
                    $organizer_title = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $organizer_title)));
                    
                    $organizer_title_table = new easyTable($pdf,1);
                    $organizer_title_table->easyCell($organizer_title, 'font-size:16; font-style:B; font-color:#000000;');
                    $organizer_title_table->printRow();
                    $organizer_title_table->endTable();

                    $organizer_table = new easyTable($pdf, '{85, 55, 50}','align:L;border:1; border-color:#000000;');
                    $organizer_table->rowStyle('align:C;valign:M;bgcolor:#000000; font-color:#ffffff; font-style:B;');
                    $organizer_table->easyCell($label_name);
                    $organizer_table->easyCell($label_bjcp_id);
                    $organizer_table->easyCell($label_points);
                    $organizer_table->printRow();

                    $string = html_entity_decode($row_org['brewerLastName']).", ".html_entity_decode($row_org['brewerFirstName']);
                    $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                    $organizer_table->easyCell($string);

                    $string = "";
                    if ($row_org['brewerJudgeID'] != "") $string .= $organ_bjcp_id; 
                    $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                    $organizer_table->easyCell($string);

                    $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $organ_max_points)));
                    $organizer_table->easyCell($string);

                    $organizer_table->printRow();
                    $organizer_table->endTable();

                }

                if ($totalRows_judges > 0) {

                    $judges_title = $label_judges;
                    $judges_title = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $judges_title)));
                    
                    $judges_title_table = new easyTable($pdf,1);
                    $judges_title_table->easyCell($judges_title, 'font-size:16; font-style:B; font-color:#000000;');
                    $judges_title_table->printRow();
                    $judges_title_table->endTable();

                    $judges_table = new easyTable($pdf, '{85, 55, 50}','align:L;border:1; border-color:#000000;');
                    $judges_table->rowStyle('align:C;valign:M;bgcolor:#000000; font-color:#ffffff; font-style:B;');
                    $judges_table->easyCell($label_name);
                    $judges_table->easyCell($label_bjcp_id);
                    $judges_table->easyCell($label_points);
                    $judges_table->printRow();

                    foreach (array_unique($j) as $uid) {

                        $judge_info = explode("^",brewer_info($uid));

                        unset($b);
                        $judge_points = judge_points($uid,$judge_max_points);
                        $bos_judge = bos_points($uid);
                        $judge_bjcp_id = "";
                        if (validate_bjcp_id($judge_info['4'])) $judge_bjcp_id = strtoupper(strtr($judge_info['4'],$bjcp_num_replace));

                        if ($judge_points > 0) {

                            $string = html_entity_decode($judge_info['1']).", ".html_entity_decode($judge_info['0']);
                            $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                            $judges_table->easyCell($string);

                            $string = "";
                            if (!empty($judge_bjcp_id)) $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $judge_bjcp_id)));
                            $judges_table->easyCell($string);

                            if ($judge_bjcp_id == $organ_bjcp_id) $string = sprintf("0.0 (%s)",$label_organizer);
                            else {
                                if ($bos_judge) {
                                    $string = $judge_points + $bos_judge_points;
                                    $string .= " (BOS)";
                                }
                                else $string = $judge_points;
                            }
                            $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                            $judges_table->easyCell($string);

                            $judges_table->printRow();

                        } // end if ($judge_points > 0)

                    } // end foreach

                    foreach (array_unique($bos_judge_no_assignment) as $uid) {

                        if (($total_entries >= 30) && (($beer_styles >= 5) || ($mead_cider >= 3))) {

                            $judge_info = explode("^",brewer_info($uid));
                            $judge_bjcp_id = "";
                            if (validate_bjcp_id($judge_info['4'])) $judge_bjcp_id = strtoupper(strtr($judge_info['4'],$bjcp_num_replace));

                            if (!empty($uid)) {

                                $string = html_entity_decode($judge_info['1']).", ".html_entity_decode($judge_info['0']);
                                $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                                $judges_table->easyCell($string);

                                $string = "";
                                if (!empty($judge_bjcp_id)) $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $judge_bjcp_id)));
                                $judges_table->easyCell($string);

                                if ($judge_bjcp_id == $organ_bjcp_id) $string = sprintf("0.0 (%s)",$label_organizer);
                                else {
                                    if ($bos_judge) {
                                        $string = $judge_points + $bos_judge_points;
                                        $string .= " (BOS)";
                                    }
                                    else $string = $judge_points;
                                }
                                $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                                $judges_table->easyCell($string);

                                $judges_table->printRow();

                            } // end if (!empty($uid))

                        } // end if (($total_entries >= 30) && (($beer_styles >= 5) || ($mead_cider >= 3)))

                    } // end foreach

                    $judges_table->endTable();
                        
                } // end if ($totalRows_judges > 0)

                if ($totalRows_stewards > 0) {

                    $stewards_assigned_tables = 0;
                    
                    $stewards_title = $label_stewards;
                    $stewards_title = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $stewards_title)));
                    
                    $stewards_title_table = new easyTable($pdf,1);
                    $stewards_title_table->easyCell($stewards_title, 'font-size:16; font-style:B; font-color:#000000;');
                    $stewards_title_table->printRow();
                    $stewards_title_table->endTable();

                    $stewards_table = new easyTable($pdf, '{85, 55, 50}','align:L;border:1; border-color:#000000;');
                    $stewards_table->rowStyle('align:C;valign:M;bgcolor:#000000; font-color:#ffffff; font-style:B;');
                    $stewards_table->easyCell($label_name);
                    $stewards_table->easyCell($label_bjcp_id);
                    $stewards_table->easyCell($label_points);
                    $stewards_table->printRow();
                        
                    foreach (array_unique($s) as $uid) {
                        
                        $steward_info = explode("^",brewer_info($uid));
                        $steward_points = steward_points($uid);
                        $steward_bjcp_id = "";
                        if (validate_bjcp_id($steward_info['4'])) $steward_bjcp_id = strtoupper(strtr($steward_info['4'],$bjcp_num_replace));

                        if ($steward_points > 0) {

                            $stewards_assigned_tables += 1;

                            $string = html_entity_decode($steward_info['1']).", ".html_entity_decode($steward_info['0']);
                            $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                            $stewards_table->easyCell($string);

                            $string = "";
                            if (!empty($steward_bjcp_id)) $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $steward_bjcp_id)));
                            $stewards_table->easyCell($string);

                            $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $steward_points)));
                            $stewards_table->easyCell($string);

                            $stewards_table->printRow();

                        } // end if ($steward_points > 0)

                    } // end foreach

                    if ($stewards_assigned_tables == 0) {
                        $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $output_text_011)));
                        $stewards_table->easyCell($string,'colspan:3');
                        $stewards_table->printRow();
                    }

                    $stewards_table->endTable();

                }        

                if ($totalRows_staff > 0) {

                    $st_running_total = 0;

                    $staff_title = $label_staff;
                    $staff_title = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $staff_title)));
                    
                    $staff_title_table = new easyTable($pdf,1);
                    $staff_title_table->easyCell($staff_title, 'font-size:16; font-style:B; font-color:#000000;');
                    $staff_title_table->printRow();

                    $string = "* ".$output_text_033;
                    $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                    $staff_title_table->easyCell($string, 'font-size:10; font-style:I; font-color:#000000;');
                    $staff_title_table->printRow();
                    
                    $staff_title_table->endTable();

                    $staff_table = new easyTable($pdf, '{85, 55, 50}','align:L;border:1; border-color:#000000;');
                    $staff_table->rowStyle('align:C;valign:M;bgcolor:#000000; font-color:#ffffff; font-style:B;');
                    $staff_table->easyCell($label_name);
                    $staff_table->easyCell($label_bjcp_id);
                    $staff_table->easyCell($label_points);
                    $staff_table->printRow();

                    foreach (array_unique($st) as $uid) {
                        
                        if ($st_running_total <= $staff_max_points) {
                            
                            $staff_info = explode("^",brewer_info($uid));
                            $staff_bjcp_id = "";
                            if (validate_bjcp_id($staff_info['4'])) $staff_bjcp_id = strtoupper(strtr($staff_info['4'],$bjcp_num_replace));
                           
                            if (($st_running_total <= $staff_max_points) && ($staff_points < $organ_max_points)) $staff_points = $staff_points;
                            else $staff_points = $organ_max_points;

                            $string = html_entity_decode($staff_info['1']).", ".html_entity_decode($staff_info['0']);
                            $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $string)));
                            $staff_table->easyCell($string);

                            $string = "";
                            if (!empty($staff_bjcp_id)) $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $staff_bjcp_id)));
                            $staff_table->easyCell($string);

                            $string = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $staff_points)));
                            $staff_table->easyCell($string);

                            $staff_table->printRow();

                            $st_running_total += $staff_points;
                            
                        } // end if (array_sum($st_running_total) < $staff_max_points)


                    } // end foreach
                    
                    $staff_table->endTable();
                
                } // end if ($totalRows_staff > 0)

                //$pdf->Output();
                $pdf->Output($filename,'D');
            
            } // end if ($view == "pdf")

            /** 
             * ------------------------------------------------------------------------------------------------------
             * BJCP XML Output
             * BJCP XML Specification: http://www.bjcp.org/it/docs/BJCP%20Database%20XML%20Interface%20Spec%202.1.pdf
             * ------------------------------------------------------------------------------------------------------
             */

            if ($view == "xml") {

                $filename = "";
                if (!empty($_SESSION['contestID'])) $filename .= $_SESSION['contestID']."_";
                $filename .= str_replace(" ","_",$_SESSION['contestName'])."_BJCP_Points_Report.xml";

                $all_rules_applied = TRUE;
                $rule_org = FALSE;
                $rule_sessions = FALSE;
                $rule_comp_id = FALSE;

                $total_days = total_days();

                // Total sessions cannot exceed three per day
                $total_sessions = total_sessions();
                $max_sessions = ($total_days * 3);
                if ($total_sessions > $max_sessions) {
                    $all_rules_applied = FALSE;
                    $rule_sessions = TRUE;
                }

                // Organizer must be declared
                if ($totalRows_organizer == 0) {
                    $all_rules_applied = FALSE;
                    $rule_org = TRUE;
                }

                // Must have a competition ID
                if (empty($_SESSION['contestID'])) {
                    $all_rules_applied = FALSE;
                    $rule_comp_id = TRUE;
                }

                /**
                 * DEBUG
                 * $all_rules_applied = FALSE;
                 * $rule_comp_id = TRUE;
                 * $rule_org = TRUE;
                 * $rule_sessions = TRUE;
                 */

                if ($all_rules_applied) {

                    $st_running_total = 0;

                    $output = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
                    $output .= "<OrgReport>\n";
                    $output .= "\t<CompData>\n";
                    $output .= "\t\t<CompID>".$_SESSION['contestID']."</CompID>\n";
                    $output .= "\t\t<CompName>".html_entity_decode($_SESSION['contestName'])."</CompName>\n";
                    $output .= "\t\t<CompDate>".getTimeZoneDateTime($_SESSION['prefsTimeZone'], max($dates), $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "system", "date-no-gmt")."</CompDate>\n";
                    $output .= "\t\t<CompEntries>".$total_entries."</CompEntries>\n";
                    $output .= "\t\t<CompDays>".$total_days."</CompDays>\n";
                    $output .= "\t\t<CompSessions>".total_sessions()."</CompSessions>\n";
                    $output .= "\t\t<CompFlights>".total_flights()."</CompFlights>\n";
                    $output .= "\t</CompData>\n";
                    $output .= "\t<BJCPpoints>\n";

                    // Organizer with a properly formatted BJCP ID in the system
                    foreach (array_unique($o) as $uid) {
                        $organizer_info = explode("^",brewer_info($uid));
                        if (($organizer_info['0'] != "") && ($organizer_info['1'] != "") && (validate_bjcp_id($organizer_info['4']))) {
                            $organ_bjcp_id = strtoupper(strtr($organizer_info['4'],$bjcp_num_replace));
                            $output .= "\t\t<JudgeData>\n";
                            $output .= "\t\t\t<JudgeName>".html_entity_decode($organizer_info['0'])." ".html_entity_decode($organizer_info['1'])."</JudgeName>\n";
                            $output .= "\t\t\t<JudgeID>".$organ_bjcp_id."</JudgeID>\n";
                            $output .= "\t\t\t<JudgeRole>Organizer</JudgeRole>\n";
                            $output .= "\t\t\t<JudgePts>0.0</JudgePts>\n";
                            $output .= "\t\t\t<NonJudgePts>".number_format(($organ_max_points),1)."</NonJudgePts>\n";
                            $output .= "\t\t</JudgeData>\n";
                        }
                    }

                    // Judges with a properly formatted BJCP IDs in the system
                    foreach (array_unique($j) as $uid) {
                        $judge_info = explode("^",brewer_info($uid));
                        $judge_points = judge_points($uid,$judge_max_points);
                        $judge_bjcp_id = strtoupper(strtr($judge_info['4'],$bjcp_num_replace));

                        if (($judge_points > 0) && ($judge_bjcp_id != $organ_bjcp_id) && (!in_array($uid,$st))) {
                            $bos_judge = bos_points($uid);
                            if (($bos_judge) && (!in_array($uid,$bos_judge_no_assignment))) $assignment = "Judge + BOS";
                            else $assignment = "Judge";
                            if (($judge_info['0'] != "") && ($judge_info['1'] != "") && (validate_bjcp_id($judge_info['4']))) {
                                $judge_name = html_entity_decode($judge_info['0'])." ".html_entity_decode($judge_info['1']);
                                $output .= "\t\t<JudgeData>\n";
                                $output .= "\t\t\t<JudgeName>".$judge_name."</JudgeName>\n";
                                $output .= "\t\t\t<JudgeID>".$judge_bjcp_id."</JudgeID>\n";
                                $output .= "\t\t\t<JudgeRole>".$assignment."</JudgeRole>\n";
                                if ($bos_judge) $output .= "\t\t\t<JudgePts>".number_format(($judge_points+$bos_judge_points),1)."</JudgePts>\n";
                                else $output .= "\t\t\t<JudgePts>".number_format(($judge_points),1)."</JudgePts>\n";
                                $output .= "\t\t\t<NonJudgePts>0.0</NonJudgePts>\n";
                                $output .= "\t\t</JudgeData>\n";
                            }
                        }
                    }

                    // Loner BOS Judges (no assignment to any table)
                    foreach (array_unique($bos_judge_no_assignment) as $uid) {
                        if (($total_entries >= 30) && (($beer_styles >= 5) || ($mead_cider >= 3))) {
                            $judge_info = explode("^",brewer_info($uid));
                            if (($judge_info['0'] != "") && ($judge_info['1'] != "") && (validate_bjcp_id($judge_info['4'])) && (!in_array($uid,$st))) {
                                $judge_bjcp_id = strtoupper(strtr($judge_info['4'],$bjcp_num_replace));
                                if (!empty($uid) && ($judge_bjcp_id != $organ_bjcp_id) &&  (!in_array($uid,$st))) {
                                    $judge_name = html_entity_decode($judge_info['0'])." ".html_entity_decode($judge_info['1']);
                                    $output .= "\t\t<JudgeData>\n";
                                    $output .= "\t\t\t<JudgeName>".$judge_name."</JudgeName>\n";
                                    $output .= "\t\t\t<JudgeID>".$judge_bjcp_id."</JudgeID>\n";
                                    $output .= "\t\t\t<JudgeRole>BOS Judge</JudgeRole>\n";
                                    $output .= "\t\t\t<JudgePts>1.0</JudgePts>\n";
                                    $output .= "\t\t\t<NonJudgePts>0.0</NonJudgePts>\n";
                                    $output .= "\t\t</JudgeData>\n";
                                }
                            }
                        }
                    }

                    // Stewards with a properly formatted BJCP IDs in the system
                    foreach (array_unique($s) as $uid) {
                        $steward_points = steward_points($uid);
                        if ($steward_points > 0) {
                            $steward_info = explode("^",brewer_info($uid));
                            $steward_bjcp_id = strtoupper(strtr($steward_info['4'],$bjcp_num_replace));
                            $steward_name = html_entity_decode($steward_info['0'])." ".html_entity_decode($steward_info['1']);
                            if (($steward_info['0'] != "") && ($steward_info['1'] != "") && (validate_bjcp_id($steward_info['4']))) {
                                if (($steward_bjcp_id != $organ_bjcp_id) && (!in_array($uid,$st))) {
                                    $output .= "\t\t<JudgeData>\n";
                                    $output .= "\t\t\t<JudgeName>".$steward_name."</JudgeName>\n";
                                    $output .= "\t\t\t<JudgeID>".$steward_bjcp_id."</JudgeID>\n";
                                    $output .= "\t\t\t<JudgeRole>Steward</JudgeRole>\n";
                                    $output .= "\t\t\t<JudgePts>0.0</JudgePts>\n";
                                    $output .= "\t\t\t<NonJudgePts>".number_format(($steward_points),1)."</NonJudgePts>\n";
                                    $output .= "\t\t</JudgeData>\n";
                                }
                            }
                        }
                    }

                    //Staff Members with a properly formatted BJCP IDs in the system
                    foreach (array_unique($st) as $uid) {
                        $staff_info = explode("^",brewer_info($uid));
                        if (($staff_info['0'] != "") && ($staff_info['1'] != "") && (validate_bjcp_id($staff_info['4']))) {
                            $staff_bjcp_id = strtoupper(strtr($staff_info['4'],$bjcp_num_replace));
                            if ($staff_bjcp_id != $organ_bjcp_id) {
                                
                                $staff_name = html_entity_decode($staff_info['0'])." ".html_entity_decode($staff_info['1']);
                                $st_running_total += $staff_points;
                                
                                if ((!in_array($uid,$j)) && (!in_array($uid,$s))) {
                                    
                                    $output .= "\t\t<JudgeData>\n";
                                    $output .= "\t\t\t<JudgeName>".$staff_name."</JudgeName>\n";
                                    $output .= "\t\t\t<JudgeID>".$staff_bjcp_id."</JudgeID>\n";
                                    $output .= "\t\t\t<JudgeRole>Staff</JudgeRole>\n";
                                    $output .= "\t\t\t<JudgePts>0.0</JudgePts>\n";
                                    if ($st_running_total <= $staff_max_points) $output .= "\t\t\t<NonJudgePts>".number_format(($staff_points),1)."</NonJudgePts>\n";
                                    else $output .= "\t\t\t<NonJudgePts>0.0</NonJudgePts>\n";
                                    $output .= "\t\t</JudgeData>\n";
                                
                                } else {
                                    
                                    $output .= "\t\t<JudgeData>\n";
                                    $output .= "\t\t\t<JudgeName>".$staff_name."</JudgeName>\n";
                                    $output .= "\t\t\t<JudgeID>".$staff_bjcp_id."</JudgeID>\n"; 

                                    if (in_array($uid,$j)) {
                                        
                                        $judge_points = judge_points($uid,$judge_max_points);
                                        $bos_judge = bos_points($uid);
                                        
                                        if ($st_running_total <= $staff_max_points) {
                                            if (($bos_judge) && (!in_array($uid,$bos_judge_no_assignment))) $assignment = "Staff + Judge + BOS";
                                            elseif (($bos_judge) && (in_array($uid,$bos_judge_no_assignment))) $assignment = "Staff + BOS Judge";
                                            else $assignment = "Staff + Judge";
                                        }

                                        else {
                                            if (($bos_judge) && (!in_array($uid,$bos_judge_no_assignment))) $assignment = "Judge + BOS";
                                            elseif (($bos_judge) && (in_array($uid,$bos_judge_no_assignment))) $assignment = "BOS Judge";
                                            else $assignment = "Judge";
                                        }

                                        $output .= "\t\t\t<JudgeRole>".$assignment."</JudgeRole>\n";
                                        if ($bos_judge) $output .= "\t\t\t<JudgePts>".number_format(($judge_points+$bos_judge_points),1)."</JudgePts>\n";
                                        else $output .= "\t\t\t<JudgePts>".number_format(($judge_points),1)."</JudgePts>\n";
                                        if ($st_running_total <= $staff_max_points) $output .= "\t\t\t<NonJudgePts>".number_format(($staff_points),1)."</NonJudgePts>\n";
                                        else $output .= "\t\t\t<NonJudgePts>0.0</NonJudgePts>\n";
                                    }
                                    
                                    if (in_array($uid,$s)) {
                                        $steward_points = steward_points($uid);
                                        if ($st_running_total <= $staff_max_points) $output .= "\t\t\t<JudgeRole>Staff + Steward</JudgeRole>\n";
                                        else  $output .= "\t\t\t<JudgeRole>Steward</JudgeRole>\n";
                                        $output .= "\t\t\t<JudgePts>0.0</JudgePts>\n";
                                        if ($st_running_total <= $staff_max_points) $output .= "\t\t\t<NonJudgePts>".number_format(($steward_points+$staff_points),1)."</NonJudgePts>\n";
                                        else $output .= "\t\t\t<NonJudgePts>".number_format($steward_points,1)."</NonJudgePts>\n";
                                    }
                                    
                                    $output .= "\t\t</JudgeData>\n";
                                }
                            }
                        }
                    }

                    $output .= "\t</BJCPpoints>\n";
                    $output .= "\t<NonBJCP>\n";

                    // Judges WITHOUT a properly formatted BJCP IDs in the system
                    foreach (array_unique($j) as $uid) {

                        $judge_info = explode("^",brewer_info($uid));
                        // $judge_points = judge_points($uid,$judge_max_points,$days,$sessions);

                        unset($b);

                        foreach (array_unique($a) as $location) {

                            $query_assignments = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE bid='%s' AND assignLocation='%s' AND assignment='J'", $prefix."judging_assignments", $uid, $location);
                            $assignments = mysqli_query($connection,$query_assignments) or die (mysqli_error($connection));
                            $row_assignments = mysqli_fetch_assoc($assignments);

                            // 0.5 points per session
                            $number = $row_assignments['count'] * 0.5;
                            if ($number > 0.5) $b[] = 0.5;
                            else $b[] = $number;

                        }

                        $points = array_sum($b);

                        // Minimum of 0.5 points per session
                        // "Judges earn points at a rate of 0.5 Judge Points per session."
                        $max_comp_points = ($sessions * 0.5);

                        // Cannot exceed more than 1.5 points per *day*
                        // "Judges earn a maximum of 1.5 Judge Points per day."
                        if ($points > ($days * 1.5)) $points = ($days * 1.5);
                        else $points = $points;

                        // Cannot exceed the maximum amount of points possible for the entire competition
                        if ($points > $max_comp_points) $points = $max_comp_points;
                        else $points = $points;

                        // Cannot exceed the maximum allowable points for judges for the competition
                        if ($points > $judge_max_points) $points = $judge_max_points;
                        else $points = $points;

                        // If points are below the 1.0 minimum, award minimum
                        if ($points < 1) $points = 1;
                        else $points = $points;

                        $judge_points = number_format($points,1);

                        if ($judge_points > 0) {
                            $bos_judge = bos_points($uid);
                            if ($bos_judge) $assignment = "Judge + BOS";
                            else $assignment = "Judge";
                            if (($judge_info['0'] != "") && ($judge_info['1'] != "") && (!validate_bjcp_id($judge_info['4'])) && (!in_array($uid,$st))) {
                                $judge_name = html_entity_decode($judge_info['0'])." ".html_entity_decode($judge_info['1']);
                                $output .= "\t\t<JudgeData>\n";
                                $output .= "\t\t\t<JudgeName>".$judge_name."</JudgeName>\n";
                                $output .= "\t\t\t<JudgeRole>".$assignment."</JudgeRole>\n";
                                if ($bos_judge) $output .= "\t\t\t<JudgePts>".number_format(($judge_points+$bos_judge_points),1)."</JudgePts>\n";
                                else $output .= "\t\t\t<JudgePts>".$judge_points."</JudgePts>\n";
                                $output .= "\t\t\t<NonJudgePts>0.0</NonJudgePts>\n";
                                $output .= "\t\t</JudgeData>\n";
                            }
                        }
                    }

                    // Loner BOS Judges (no assignment to any table)
                    foreach (array_unique($bos_judge_no_assignment) as $uid) {
                        $judge_info = explode("^",brewer_info($uid));
                        if (($judge_info['0'] != "") && ($judge_info['1'] != "") && (!validate_bjcp_id($judge_info['4'])) && (!in_array($uid,$st))) {
                            if (!empty($uid)) {
                                $judge_name = html_entity_decode($judge_info['0'])." ".html_entity_decode($judge_info['1']);
                                $output .= "\t\t<JudgeData>\n";
                                $output .= "\t\t\t<JudgeName>".$judge_name."</JudgeName>\n";
                                $output .= "\t\t\t<JudgeID>".strtoupper(strtr($judge_info['4'],$bjcp_num_replace))."</JudgeID>\n";
                                $output .= "\t\t\t<JudgeRole>BOS Judge</JudgeRole>\n";
                                $output .= "\t\t\t<JudgePts>1.0</JudgePts>\n";
                                $output .= "\t\t\t<NonJudgePts>0.0</NonJudgePts>\n";
                                $output .= "\t\t</JudgeData>\n";
                            }
                        }
                    }

                    // Stewards without a properly formatted BJCP IDs in the system
                    foreach (array_unique($s) as $uid) {
                    $steward_points = steward_points($uid);
                    if ($steward_points > 0) {
                        $steward_info = explode("^",brewer_info($uid));
                            if (($steward_info['0'] != "") && ($steward_info['1'] != "") && (!validate_bjcp_id($steward_info['4'])) && (!in_array($uid,$st))) {
                                    $steward_name = html_entity_decode($steward_info['0'])." ".html_entity_decode($steward_info['1']);
                                    $output .= "\t\t<JudgeData>\n";
                                    $output .= "\t\t\t<JudgeName>".$steward_name."</JudgeName>\n";
                                    $output .= "\t\t\t<JudgeRole>Steward</JudgeRole>\n";
                                    $output .= "\t\t\t<JudgePts>0.0</JudgePts>\n";
                                    $output .= "\t\t\t<NonJudgePts>".$steward_points."</NonJudgePts>\n";
                                    $output .= "\t\t</JudgeData>\n";
                            }
                        }
                    }

                    // Staff Members without a properly formatted BJCP IDs in the system
                    foreach (array_unique($st) as $uid) {
                        $staff_info = explode("^",brewer_info($uid));
                        if (($staff_info['0'] != "") && ($staff_info['1'] != "") && (!validate_bjcp_id($staff_info['4']))) {
                            
                            $st_running_total += $staff_points;
                            //echo $st_running_total."<br>";
                            $staff_name = html_entity_decode($staff_info['0'])." ".html_entity_decode($staff_info['1']);

                            if ((!in_array($uid,$j)) && (!in_array($uid,$s))) {
                                
                                $output .= "\t\t<JudgeData>\n";
                                $output .= "\t\t\t<JudgeName>".$staff_name."</JudgeName>\n";
                                $output .= "\t\t\t<JudgeRole>Staff</JudgeRole>\n";
                                $output .= "\t\t\t<JudgePts>0.0</JudgePts>\n";

                                if ($st_running_total <= $staff_max_points) $output .= "\t\t\t<NonJudgePts>".number_format($staff_points,1)."</NonJudgePts>\n";
                                else $output .= "\t\t\t<NonJudgePts>0.0</NonJudgePts>\n";
                                $output .= "\t\t</JudgeData>\n";
                            
                            } else {

                                $output .= "\t\t<JudgeData>\n";
                                $output .= "\t\t\t<JudgeName>".$staff_name."</JudgeName>\n";

                                if (in_array($uid,$j)) {
                                    $judge_points = judge_points($uid,$judge_max_points);
                                    $bos_judge = bos_points($uid);
                                    
                                    if ($st_running_total <= $staff_max_points) {
                                        if (($bos_judge) && (!in_array($uid,$bos_judge_no_assignment))) $assignment = "Staff + Judge + BOS";
                                        elseif (($bos_judge) && (in_array($uid,$bos_judge_no_assignment))) $assignment = "Staff + BOS Judge";
                                        else $assignment = "Staff + Judge";
                                    }

                                    else {
                                        if (($bos_judge) && (!in_array($uid,$bos_judge_no_assignment))) $assignment = "Judge + BOS";
                                        elseif (($bos_judge) && (in_array($uid,$bos_judge_no_assignment))) $assignment = "BOS Judge";
                                        else $assignment = "Judge";
                                    }
                                        
                                    $output .= "\t\t\t<JudgeRole>".$assignment."</JudgeRole>\n";
                                    if ($bos_judge) $output .= "\t\t\t<JudgePts>".number_format(($judge_points+$bos_judge_points),1)."</JudgePts>\n";
                                    else $output .= "\t\t\t<JudgePts>".number_format(($judge_points),1)."</JudgePts>\n";
                                    if ($st_running_total <= $staff_max_points) $output .= "\t\t\t<NonJudgePts>".number_format($staff_points,1)."</NonJudgePts>\n";
                                    else $output .= "\t\t\t<NonJudgePts>0.0</NonJudgePts>\n";
                                }
                                
                                if (in_array($uid,$s)) {
                                    $steward_points = steward_points($uid);
                                    if ($st_running_total <= $staff_max_points) $output .= "\t\t\t<JudgeRole>Staff + Steward</JudgeRole>\n";
                                    else $output .= "\t\t\t<JudgeRole>Steward</JudgeRole>\n";
                                    $output .= "\t\t\t<JudgePts>0.0</JudgePts>\n";
                                    if ($st_running_total <= $staff_max_points) $output .= "\t\t\t<NonJudgePts>".number_format(($staff_points+$steward_points),1)."</NonJudgePts>\n";
                                    else $output .= "\t\t\t<NonJudgePts>0.0</NonJudgePts>\n";
                                }

                                $output .= "\t\t</JudgeData>\n";
                            }
                        }
                    }

                    // Organizer without a properly formatted BJCP ID in the system
                    foreach (array_unique($o) as $uid) {
                    $organizer_info = explode("^",brewer_info($uid));
                        if (($organizer_info['0'] != "") && ($organizer_info['1'] != "") && (!validate_bjcp_id($organizer_info['4']))) {
                            $output .= "\t\t<JudgeData>\n";
                            $output .= "\t\t\t<JudgeName>".html_entity_decode($organizer_info['0'])." ".html_entity_decode($organizer_info['1'])."</JudgeName>\n";
                            $output .= "\t\t\t<JudgeRole>Organizer</JudgeRole>\n";
                            $output .= "\t\t\t<JudgePts>0.0</JudgePts>\n";
                            $output .= "\t\t\t<NonJudgePts>".$organ_max_points."</NonJudgePts>\n";
                            $output .= "\t\t</JudgeData>\n";
                        }
                    }
                    $output .= "\t</NonBJCP>\n";

                    // BOS Reporting
                    $output .= "\t<Comments>\n";
                    $output .= "\t\tGenerated by BCOE&M version ".$current_version_display."\n";
                    $output .= "\t\t".$label_email.": ".$_SESSION['user_name']."\n";

                    $style_arr = array();
                    $query_style_type = sprintf("SELECT id FROM %s",$style_types_db_table);
                    $style_type = mysqli_query($connection,$query_style_type) or die (mysqli_error($connection));
                    $row_style_type = mysqli_fetch_assoc($style_type);
                    $totalRows_style_type = mysqli_num_rows($style_type);
                    do { $style_arr[] = $row_style_type['id']; } while ($row_style_type = mysqli_fetch_assoc($style_type));
                    sort($style_arr);

                    foreach ($style_arr as $type) {

                        include (DB.'output_results_download_bos.db.php');

                        if ($totalRows_bos > 0) {
                            if ($_SESSION['prefsProEdition'] == 1) $output .= "\t\t".$label_bos.": ".html_entity_decode($row_bos['brewerBreweryName'])."\n";
                            else $output .= "\t\t".$label_bos.": ".html_entity_decode($row_bos['brewerFirstName'])." ".html_entity_decode($row_bos['brewerLastName'])."\n";
                            $output .= "\t\t".$label_style.": ".html_entity_decode($row_bos['brewStyle'])."\n";
                            $output .= "\t\t".$label_city.": ".html_entity_decode($row_bos['brewerCity'])."\n";
                            $output .= "\t\t".$label_state_province.": ".html_entity_decode($row_bos['brewerState'])."\n";
                            $output .= "\t\t".$label_country.": ".html_entity_decode($row_bos['brewerCountry'])."\n";
                        }
                    }

                    $output .= "\t</Comments>\n";

                    $output .= "\t<SubmissionDate>".date('l j F Y h:i:s A')."</SubmissionDate>\n";
                    $output .= "</OrgReport>";

                } // end $all_rules_applied

                else {
                    $output .= "The report cannot be generated for the following reasons:";
                    if ($rule_org) $output .= "\n- No organizer has been designated. The BJCP will not accept an XML report without a named Organizer. Designate the Organizer by going to Admin > Entries and Participants > Assign Staff. Choose the Organizer's name from the drop-down list near the top of the page. If the Organizer's name is not present in the drop-down, an account will need to be created for them.";
                    if ($rule_sessions) $output .= "\n- Judging sessions exceed the maximum of three (3) per day.";
                    if ($rule_comp_id) $output .= "\n- The BCJP Competition ID is missing. A Competition ID is required for submittal to the BJCP and can be found in the registration confirmation email sent by the BJCP. Add the Competition ID via Admin > Competition Preparation > Edit Competition Info.";
                }

                header('Content-Type: application/force-download');
                header('Content-Disposition: attachment;filename="'.$filename.'"');
                header('Pragma: no-cache');
                header('Expires: 0');

                echo $output;
                exit();

            }

        } // END if $admin_role

        else echo "Not Allowed";
        exit();

	} // END if ($section == "export-staff")

} // end if (($admin_role) || ((($judging_past == 0) && ($registration_open == 2) && ($entry_window_open == 2))))

else echo "Not allowed."; 

if ((isset($_SESSION['loginUsername'])) && ($section == "export-personal-results") && ($id != "default")) {

    $query_brewer = sprintf("SELECT DISTINCT a.brewCategory, a.brewSubCategory, a.id AS eid, a.brewStyle, a.brewInfo, a.brewInfoOptional, a.brewComments, b.scoreEntry, b.scorePlace, c.brewerFirstName, c.brewerLastName, c.brewerClubs, c.brewerEmail, c.brewerMHP FROM %s a, %s b, %s c WHERE a.brewBrewerID = '%s' AND b.bid = '%s' AND c.uid = '%s' AND a.id = b.eid", $prefix."brewing", $prefix."judging_scores", $prefix."brewer", $id, $id, $id);
    $brewer = mysqli_query($connection,$query_brewer);
    $row_brewer = mysqli_fetch_assoc($brewer);
    $totalRows_brewer = mysqli_num_rows($brewer);
    
    $a = array();
    $personal = array();
    $results = array();

    $results_count = 0;
    $first_name = "";
    $last_name = "";
    $club = "";
    $email = "";
    $mhp = "";

    // Results data headers
    $results[] = array("Category", "Category Name", "Required Info", "Official Score", "Highest Score", "Place");

    if ($row_brewer) {

        do {

            $results_count++;
            
            $highest_score = array();
            $consensus_score = array();
            $eval_score = 0;
            $highest_entry_score = "";
            $entry_consensus_score = "";
            
            $category = $row_brewer['brewCategory'].$row_brewer['brewSubCategory'];
            
            $req_info = "";
            if (!empty($row_brewer['brewInfo'])) $req_info .= convert_to_entities($row_brewer['brewInfo'])." ";
            if (!empty($row_brewer['brewInfoOptional'])) $req_info .= convert_to_entities($row_brewer['brewInfoOptional'])." ";
            if (!empty($row_brewer['brewComments'])) $req_info .= convert_to_entities($row_brewer['brewComments']);
            if (!empty($req_info)) $req_info = str_replace("^"," ",$req_info);

            // Check Eval table for the highest score recorded for this entry
            $query_eid_eval = sprintf("SELECT * FROM %s WHERE eid=%s", $prefix."evaluation", $row_brewer['eid']);
            $eid_eval = mysqli_query($connection,$query_eid_eval);
            $row_eid_eval = mysqli_fetch_assoc($eid_eval);
            $totalRows_eid_eval = mysqli_num_rows($eid_eval);

            if ($totalRows_eid_eval > 0) {
                
                do {

                    $score = 
                    $row_eid_eval['evalAromaScore'] + 
                    $row_eid_eval['evalAppearanceScore'] + 
                    $row_eid_eval['evalFlavorScore'] + 
                    $row_eid_eval['evalMouthfeelScore'] + 
                    $row_eid_eval['evalOverallScore']
                    ;

                    $highest_score[] = $score;
                    $consensus_score[] = $row_eid_eval['evalFinalScore'];

                } while($row_eid_eval = mysqli_fetch_assoc($eid_eval));

            }

            if (empty($row_brewer['scoreEntry'])) $entry_consensus_score = max($consensus_score);
            else {
                $highest_entry_score = $row_brewer['scoreEntry'];
                $entry_consensus_score = $row_brewer['scoreEntry'];
            }

            if (!empty($highest_score)) {
                $eval_score = max($highest_score);
                if ($eval_score > $row_brewer['scoreEntry']) $highest_entry_score = $eval_score;
            }

            // Results data
            $results[] = array(
                $category, 
                convert_to_entities($row_brewer['brewStyle']),
                $req_info, 
                $entry_consensus_score, 
                $highest_entry_score, 
                $row_brewer['scorePlace']
            );

            if ($results_count == $totalRows_brewer) {
                $first_name = convert_to_entities($row_brewer['brewerFirstName']);
                $last_name = convert_to_entities($row_brewer['brewerLastName']);
                $club = convert_to_entities($row_brewer['brewerClubs']);
                $email = convert_to_entities($row_brewer['brewerEmail']);
                if ($filter == "MHP") $mhp = convert_to_entities($row_brewer['brewerMHP']);
            }

        } while($row_brewer = mysqli_fetch_assoc($brewer));

        // Spacer
        $results[] = array("");

        if ($filter == "MHP") {
            $results[] = array("Expand columns or wrap text. Also input your gender in that column if you wish.");
            $results[] = array("Remove these last two lines and email to masterhomebrewerprogram@gmail.com");
        }
        
        // Name and associated info header
        if ($filter == "MHP") {
            $personal[] = array("Last Name", "First Name", "Club", "Email", "Gender", "MHP Number");
            $personal[] = array($last_name,$first_name,$club,$email," ",$mhp);
        }
        
        else {
            $personal[] = array("Last Name", "First Name", "Club", "Email");
            $personal[] = array($last_name,$first_name,$club,$email);
        }        

        // Spacer
        $personal[] = array("");

        $a = array_merge($personal,$results);

    } // end if ($row_brewer)

    else {
        $results[] = array("No score data was found for your profile.");
        $a = array_merge($results);
    }

    $separator = ","; 
    $extension = ".csv";
    $date = date("m-d-Y");
    $filename = $first_name."_".$last_name."_Personal_Results_".$_SESSION['contestName']."_";
    if ($filter == "MHP") $filename .= "MHP_";
    $filename .= $date.$extension;
    $filename = filename($filename);
    $filename = ltrim($filename,"_");

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment;filename="'.$filename.'"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $fp = fopen('php://output', 'w');
    fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));

    foreach ($a as $fields) {
        fputcsv($fp,$fields,$separator);
    }

    fclose($fp);

} 

else echo "Not allowed."; 

exit();
?>