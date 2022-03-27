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
    $brewerBreweryName = standardize_name($purifier->purify($_POST['brewerBreweryName']));
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

/**
 * Address GitHub Issue #1113
 * @see https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues/1113
 * Table assignments not updating if a user or admin indicates they are not
 * available for a particular judging session.
 * Need to search through array of location availablities for any that are "N"
 * and search for the corresponding location/uid combo in the judging_assignments 
 * table and delete the records.
 */

if ($brewerJudge == "Y") {
    
    if (($_POST['brewerJudgeLocation'] != "") && (is_array($_POST['brewerJudgeLocation']))) {
        
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

    elseif (($_POST['brewerJudgeLocation'] != "") && (!is_array($_POST['brewerJudgeLocation']))) {

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

    $location_pref1 = "";

} // end if ($brewerJudge == "N") 

if ($brewerSteward == "Y") {

    if (($_POST['brewerStewardLocation'] != "") && (is_array($_POST['brewerStewardLocation']))) {

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

    elseif (($_POST['brewerStewardLocation'] != "") && (!is_array($_POST['brewerStewardLocation']))) {

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

    $location_pref2 = "";

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
    $first_name = $fname;
    $last_name = $lname;
    $first_name = sterilize($first_name);
    $last_name = sterilize($last_name);
}

$address = standardize_name($purifier->purify($_POST['brewerAddress']));
$address = sterilize($address);
$city = standardize_name($purifier->purify($_POST['brewerCity']));
$city = sterilize($city);
$state = "";

if (isset($_POST['brewerCountry'])) {

    if ($_POST['brewerCountry'] == "United States") {
        if ((isset($_POST['brewerStateUS'])) && ($_POST['brewerStateUS'] != "")) $state = $_POST['brewerStateUS'];
    }

    if ($_POST['brewerCountry'] == "Canada") {
        if ((isset($_POST['brewerStateCA'])) && ($_POST['brewerStateCA'] != "")) $state = $_POST['brewerStateCA'];
    }

    if ($_POST['brewerCountry'] == "Australia") {
        if ((isset($_POST['brewerStateAUS'])) && ($_POST['brewerStateAUS'] != "")) $state = $_POST['brewerStateAUS'];
    }

    else {

        if ((isset($_POST['brewerStateNon'])) && ($_POST['brewerStateNon'] != "")) {
            $state = $purifier->purify($_POST['brewerStateNon']);
            if (strlen($state) > 2) $state = standardize_name($state);
            else $state = strtoupper($state);
        }

    }

}

if (!empty($state)) $state = sterilize($state);

// Set all locations as YES for quick adds
if ($view == "quick") {

    $locations = array();

    $update_table = $prefix."judging_locations";
    $result = $db_conn->get($update_table);
    
    foreach ($result as $key => $value) {
        $locations[] = "Y-".$value;
    }

    $location_pref1 = sterilize(implode(",",$locations));
    $location_pref2 = $location_pref1;

}
?>