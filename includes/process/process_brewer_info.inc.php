<?php

/**
 This file is shared with two scripts:
 includes/process/process_brewer.inc.php
 includes/process/process_users_registration.inc.php
 */

// Instantiate HTMLPurifier
require (CLASSES.'htmlpurifier/HTMLPurifier.standalone.php');
$config_html_purifier = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config_html_purifier);
$user_id = "";
$brewerJudgeNotes = "";
$brewerJudgeID = "";
$brewerJudgeMead = "";
$brewerJudgeCider = "";
$brewerJudgeRank = "";
$brewerAHA = "";
$brewerProAm = "";
$brewerClubs = "";
$brewerPhone1 = "";
$brewerPhone2 = "";
$brewerJudgeWaiver = "Y";
$brewerDropOff = 0;
$brewerBreweryName = "";
$brewerBreweryTTB = "";
$brewerJudge = "N";
$brewerSteward = "N";
$brewerStaff = "";
$brewerJudgeExp = "";
$likes = "";
$dislikes = "";
$rank = "";
$location_pref1 = "";
$location_pref2 = "";
$brewerAssignment = "";

if ($go == "admin") $user_id = $filter;
elseif ($id != "default") $user_id = $id;

// Gather, convert, and/or sanitize info from the form
if (isset($_POST['brewerJudgeID'])) {
    $brewerJudgeID = $purifier->purify($_POST['brewerJudgeID']);
    $brewerJudgeID = strtoupper($brewerJudgeID);
    $brewerJudgeID = sterilize($brewerJudgeID);
}

if (isset($_POST['brewerJudgeMead'])) $brewerJudgeMead = $_POST['brewerJudgeMead'];
if (isset($_POST['brewerJudgeCider'])) $brewerJudgeCider = $_POST['brewerJudgeCider'];
if (isset($_POST['brewerJudgeRank'])) $brewerJudgeRank = $_POST['brewerJudgeRank'];
if (isset($_POST['brewerAHA'])) $brewerAHA = sterilize($_POST['brewerAHA']);
if (isset($_POST['brewerProAm'])) $brewerProAm = $_POST['brewerProAm'];
if (isset($_POST['brewerClubs'])) {
    include (DB.'entries.db.php');
    include (INCLUDES.'constants.inc.php');
    $brewerClubs = $purifier->purify($_POST['brewerClubs']);
    // $brewerClubsConcat = $brewerClubs."|".$brewerClubs;
    if (!in_array($brewerClubs,$club_array)) {
        if ($_POST['brewerClubs'] == "Other") {
            if (!empty($_POST['brewerClubsOther'])) $brewerClubs = ucwords($purifier->purify($_POST['brewerClubsOther']));
            else $brewerClubs = "Other";
        }
        else $brewerClubs = "";
    }
    else $brewerClubs = $brewerClubs;
}

if (isset($_POST['brewerPhone1'])) $brewerPhone1 = sterilize($_POST['brewerPhone1']);
if (isset($_POST['brewerPhone2'])) $brewerPhone2 = sterilize($_POST['brewerPhone2']);
if (isset($_POST['brewerDropOff'])) $brewerDropOff = sterilize($_POST['brewerDropOff']);

if (isset($_POST['brewerBreweryName'])) {
    $brewerBreweryName = $purifier->purify($_POST['brewerBreweryName']);
    $brewerBreweryName = sterilize($brewerBreweryName);
}

if (isset($_POST['brewerBreweryTTB'])) {
    $brewerBreweryTTB = $purifier->purify($_POST['brewerBreweryTTB']);
    $brewerBreweryTTB = strtoupper($brewerBreweryTTB);
    $brewerBreweryTTB = sterilize($brewerBreweryTTB);
}

if (isset($_POST['brewerJudge'])) $brewerJudge = $_POST['brewerJudge'];
if (isset($_POST['brewerSteward'])) $brewerSteward = $_POST['brewerSteward'];
if (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant")) {
    $brewerJudge = "N";
    $brewerSteward = "N";
}

if (isset($_POST['brewerStaff'])) $brewerStaff = $_POST['brewerStaff'];
if (isset($_POST['brewerJudgeExp'])) $brewerJudgeExp = $_POST['brewerJudgeExp'];
if (isset($_POST['brewerJudgeNotes'])) {
    $brewerJudgeNotes = $purifier->purify($_POST['brewerJudgeNotes']);
    $brewerJudgeNotes = sterilize($brewerJudgeNotes);
}

if ((isset($_POST['brewerAssignment'])) && (!empty($_POST['brewerAssignment']))) {
    $affilliated = array("affilliated" => $_POST['brewerAssignment']);
}

else $affilliated = array();

if ((isset($_POST['brewerAssignmentOther'])) && (!empty($_POST['brewerAssignmentOther']))) {

    $all_orgs = explode(",",$_POST['allOrgs']);   
    $affilliated_other_arr = str_replace(", ",",",$_POST['brewerAssignmentOther']);
    $affilliated_other_arr = str_replace(",",",",$_POST['brewerAssignmentOther']);
    $affilliated_other_arr = str_replace("; ",",",$_POST['brewerAssignmentOther']);
    $affilliated_other_arr = str_replace(";",",",$_POST['brewerAssignmentOther']);
    $affilliated_other_arr = explode(",",$affilliated_other_arr);
    $affilliated_other = array();

    foreach ($affilliated_other_arr as $value) {  
        $value = $purifier->purify($value);
        $value = sterilize($value);
        $value = strtolower($value);
        if (!in_array($value,$all_orgs)) $affilliated_other[] = ucwords($value);
    }

    if (!empty($affilliated_other)) $affilliated_other_arr = array("affilliatedOther" => $affilliated_other);

}

else $affilliated_other_arr = array();

if ((empty($affilliated)) && (empty($affilliated_other_arr))) {
    $brewerAssignment = NULL;
}

else {
    $brewerAssignment = array();
    $brewerAssignment = array_merge($affilliated,$affilliated_other_arr);
    $brewerAssignment = json_encode($brewerAssignment);
}


// print_r($brewerAssignment); exit();

/**
 * Address GitHub Issue #1113
 * @see https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues/1113
 * Table assignments not updating if a user or admin indicates they are not
 * available for a particular judging session.
 * Need to search through array of location availablities for any that are "N"
 * and search for the corresponding location/uid combo in the judging_assignments 
 * table and delete the records.
 */

if (($brewerJudge == "Y") || ($brewerStaff == "Y")) {
    
    if ((isset($_POST['brewerJudgeLocation'])) && (is_array($_POST['brewerJudgeLocation']))) {
        
        foreach ($_POST['brewerJudgeLocation'] as $value) {
            
            $loc = explode("-",$value);
            
            if ($loc[0] == "N") {

                if (!empty($user_id)) {

                    $update_table = $prefix."judging_assignments";
                    $db_conn->where ('bid', $user_id);
                    $db_conn->where ('assignment', 'J');
                    $db_conn->where ('assignLocation', $loc[1]);
                    $result = $db_conn->delete($update_table);
                    if (!$result) {
                        $error_output[] = $db_conn->getLastError();
                        $errors = TRUE;
                    }

                } // if (!empty($user_id))

            } // end if ($loc[0] == "N")
       
        } // end foreach

        $location_pref1 = sterilize(implode(",",$_POST['brewerJudgeLocation']));
    
    } // end if (($_POST['brewerJudgeLocation'] != "") && (is_array($_POST['brewerJudgeLocation'])))

    elseif ((isset($_POST['brewerJudgeLocation'])) && (!is_array($_POST['brewerJudgeLocation']))) {

        $loc = explode("-",$_POST['brewerJudgeLocation']);

        if ($loc[0] == "N") {
            
            if (!empty($user_id)) {

                $update_table = $prefix."judging_assignments";
                $db_conn->where ('bid', $user_id);
                $db_conn->where ('assignment', 'J');
                $db_conn->where ('assignLocation', $loc[1]);
                $result = $db_conn->delete($update_table);
                if (!$result) {
                    $error_output[] = $db_conn->getLastError();
                    $errors = TRUE;
                }

            } // end if (!empty($user_id))

        } // end if ($loc[0] == "N")

        $location_pref1 = sterilize($_POST['brewerJudgeLocation']);

    } // elseif (($_POST['brewerJudgeLocation'] != "") && (!is_array($_POST['brewerJudgeLocation'])))

} // end if ($brewerJudge == "Y")

if ($brewerJudge == "N") {

    if ($brewerStaff == "N") {

        $location_pref1 = "";

        if ((isset($_POST['brewerJudgeLocation'])) && (!is_array($_POST['brewerJudgeLocation']))) {
            $loc = explode("-",$_POST['brewerJudgeLocation']);
            $location_pref1 .= "N-".$loc[1];
        }

        elseif ((isset($_POST['brewerJudgeLocation'])) && (is_array($_POST['brewerJudgeLocation']))) {

            foreach ($_POST['brewerJudgeLocation'] as $value) {
                $loc = explode("-",$value);
                $location_pref1 .= "N-".$loc[1].",";
            }

        }

        $location_pref1 = sterilize($location_pref1);

    }

    if ($brewerStaff == "Y") {

        $location_pref1 = "";

        if ((isset($_POST['brewerJudgeLocation'])) && (is_array($_POST['brewerJudgeLocation']))) {

            foreach ($_POST['brewerJudgeLocation'] as $value) {
                $loc = explode("-",$value);
                $judging_location_info = judging_location_info($loc[1]);
                $judging_location_info = explode("^",$judging_location_info);
                if ($judging_location_info[5] == "2") $location_pref1 .= $loc[0]."-".$loc[1].",";
                else $location_pref1 .= "N-".$loc[1].",";
            }

        }

        elseif ((isset($_POST['brewerJudgeLocation'])) && (!is_array($_POST['brewerJudgeLocation']))) {
            
            $loc = explode("-",$_POST['brewerJudgeLocation']);
            $judging_location_info = judging_location_info($loc[1]);
            $judging_location_info = explode("^",$judging_location_info);
            if ($judging_location_info[5] == "2") $location_pref1 .= $loc[0]."-".$loc[1];
            else $location_pref1 .= "N-".$loc[1];

        }

        if (!empty($location_pref1)) $location_pref1 = rtrim($location_pref1,",");
        $location_pref1 = sterilize($location_pref1);

    }
    
    if (!empty($user_id)) {

        $update_table = $prefix."judging_assignments";
        $db_conn->where ('bid', $user_id);
        $db_conn->where ('assignment', 'J');
        $result = $db_conn->delete($update_table);
        if (!$result) {
            $error_output[] = $db_conn->getLastError();
            $errors = TRUE;
        }

    }

} // end if ($brewerJudge == "N") 

if ($brewerSteward == "Y") {

    if ((isset($_POST['brewerStewardLocation'])) && (is_array($_POST['brewerStewardLocation']))) {

        foreach ($_POST['brewerStewardLocation'] as $value) {
            
            $loc = explode("-",$value);
            
            if ($loc[0] == "N") {
                
                if (!empty($user_id)) {

                    $update_table = $prefix."judging_assignments";
                    $db_conn->where ('bid', $user_id);
                    $db_conn->where ('assignment', 'S');
                    $db_conn->where ('assignLocation', $loc[1]);
                    $result = $db_conn->delete($update_table);
                    if (!$result) {
                        $error_output[] = $db_conn->getLastError();
                        $errors = TRUE;
                    }

                } // end if (!empty($user_id))

            } // end if ($loc[0] == "N")

        } // end foreach

        $location_pref2 = sterilize(implode(",",$_POST['brewerStewardLocation']));

    } // end if (($_POST['brewerStewardLocation'] != "") && (is_array($_POST['brewerStewardLocation'])))

    elseif ((isset($_POST['brewerStewardLocation'])) && (!is_array($_POST['brewerStewardLocation']))) {

        $loc = explode("-",$_POST['brewerStewardLocation']);

        if ($loc[0] == "N") {
            
            if (!empty($user_id)) {

                $update_table = $prefix."judging_assignments";
                $db_conn->where ('bid', $user_id);
                $db_conn->where ('assignment', 'S');
                $db_conn->where ('assignLocation', $loc[1]);
                $result = $db_conn->delete($update_table);
                if (!$result) {
                    $error_output[] = $db_conn->getLastError();
                    $errors = TRUE;
                }

            } // end if (!empty($user_id))

        } // end if ($loc[0] == "N")

        $location_pref2 = sterilize($_POST['brewerStewardLocation']);

    } // end elseif (($_POST['brewerStewardLocation'] != "") && (!is_array($_POST['brewerStewardLocation'])))

} // end if ($brewerSteward == "Y")

if ($brewerSteward == "N") { 

    if (!empty($user_id)) {

        $update_table = $prefix."judging_assignments";
        $db_conn->where ('bid', $user_id);
        $db_conn->where ('assignment', 'S');
        $result = $db_conn->delete($update_table);
        if (!$result) {
            $error_output[] = $db_conn->getLastError();
            $errors = TRUE;
        }

    }

} // end if ($brewerSteward == "N")

if (isset($_POST['brewerJudgeLikes'])) {
    if (is_array($_POST['brewerJudgeLikes'])) $likes = implode(",",$_POST['brewerJudgeLikes']);
    else $likes = $_POST['brewerJudgeLikes'];
    $likes = sterilize($likes);
}

if (isset($_POST['brewerJudgeDislikes'])) {
    if (is_array($_POST['brewerJudgeDislikes'])) $dislikes = implode(",",$_POST['brewerJudgeDislikes']);
    else $dislikes = $_POST['brewerJudgeDislikes'];
    $dislikes = sterilize($dislikes);
}

if (isset($brewerJudgeRank)) {
    if (is_array($brewerJudgeRank)) $rank = implode(",",$brewerJudgeRank);
    else $rank = $brewerJudgeRank;
    $rank = sterilize($rank);
}

$fname = $purifier->purify($_POST['brewerFirstName']);
$lname = $purifier->purify($_POST['brewerLastName']);

/**
 * Use PHP Name Parser class if using Latin-based languages in the array in /lib/process.lib.php
 * https://github.com/joshfraser/PHP-Name-Parser
 * Class requires a string with the entire name - concat from form post after purification.
 * Returns an array with the following keys: "salutation", "fname", "initials", "lname", "suffix"
 * So, if the user inputs "Dr JOHN B" in the first name field and "MacKay III" the class will 
 * parse it out and return the individual parts with proper upper-lower case relationships
 * to read "Dr. John B. MacKay III"
 */

if (in_array($_SESSION['prefsLanguageFolder'], $name_check_langs)) {
    
    include (CLASSES.'capitalize_name/parser.php');
    $parser = new FullNameParser();

    $name_to_parse = $fname." ".$lname;
    $parsed_name = $parser->parse_name($name_to_parse);
    
    $first_name = "";
    if (!empty($parsed_name['salutation'])) $first_name .= $parsed_name['salutation']." ";
    $first_name .= $parsed_name['fname'];
    if (!empty($parsed_name['initials'])) $first_name .= " ".$parsed_name['initials'];
    
    $last_name = "";
    if (in_array($_SESSION['prefsLanguageFolder'], $last_name_exception_langs)) $last_name .= standardize_name($parsed_name['lname']);
    else $last_name .= $parsed_name['lname']; 
    if (!empty($parsed_name['suffix'])) $last_name .= " ".$parsed_name['suffix'];

}

else {
    $first_name = sterilize($fname);
    $last_name = sterilize($lname);
}

$address = sterilize($purifier->purify($_POST['brewerAddress']));
$city = sterilize($purifier->purify($_POST['brewerCity']));

if ((isset($_POST['brewerStateUS'])) && (!empty($_POST['brewerStateUS']))) $state = $_POST['brewerStateUS'];
elseif ((isset($_POST['brewerStateCA'])) && (!empty($_POST['brewerStateCA']))) $state = $_POST['brewerStateCA'];
elseif ((isset($_POST['brewerStateAUS'])) && (!empty($_POST['brewerStateAUS']))) $state = $_POST['brewerStateAUS'];
elseif ((isset($_POST['brewerStateNon'])) && (!empty($_POST['brewerStateNon']))) $state = $purifier->purify($_POST['brewerStateNon']);
else $state = "";

if (strlen($state) <= 2) $state = strtoupper($state);
$state = sterilize($state);

// Set all locations as YES for quick adds
if ($view == "quick") {

    $locations = array();

    $query_j_locs = sprintf("SELECT id FROM %s", $prefix."judging_locations");
    $j_locs = mysqli_query($connection,$query_j_locs) or die (mysqli_error($connection));
    $row_j_locs = mysqli_fetch_assoc($j_locs);
    
    do {
        $locations[] = "Y-".$row_j_locs['id'];
    } while ($row_j_locs = mysqli_fetch_assoc($j_locs));

    $location_pref1 = sterilize(implode(",",$locations));
    $location_pref2 = $location_pref1;

}
?>