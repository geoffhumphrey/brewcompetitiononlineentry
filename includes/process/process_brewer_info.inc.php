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
$brewerMHP = "";
$brewerProAm = "";
$brewerClubs = "";
$brewerPhone1 = "";
$brewerPhone2 = "";
$brewerJudgeWaiver = "Y";
$brewerDropOff = 0;
$brewerBreweryName = "";
$brewerBreweryInfo = array();
$brewerBreweryTTB = "";
$brewerBreweryProd = "";
$brewerBreweryProdMeas = "";
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

// Ownership check: the "admin" path (editing another participant's judging/steward assignment)
// requires an actual admin session; otherwise a user may only affect their own assignment records.
//
// GitHub issue #1752: the self-edit branch used to compare $id (the posted "id" query param,
// which sections/brewer.sec.php's form action sets to $row_brewer['id'] - the brewer table's
// own auto-increment PK) against $_SESSION['user_id'] (users.id, i.e. brewer.uid) - two
// different id spaces that only match by numeric coincidence. That left $user_id empty on a
// genuine self-edit almost every time, silently skipping every judging_assignments cleanup
// block below (lines 184-377) - so a judge who removed their own availability for a session,
// or opted out of judging/stewarding entirely, kept a stale table assignment forever, with
// nothing to indicate the cleanup never ran. Use the posted "uid" hidden field instead, which
// sections/brewer.sec.php:334 already populates with the real brewer.uid for a self-edit -
// same id space as $_SESSION['user_id'], so the ownership check (a non-admin may only affect
// their own records) still holds, just correctly this time.
/**
 * GitHub issue #1752 (found during live testing): the app has two different real
 * admin-edit URL shapes in active use - admin/judging_assign.admin.php:394 and
 * admin/judging_locations.admin.php:342 link to section=brewer&go=admin, while the
 * actual "Administration: Participants" pencil-icon edit link (the primary path)
 * goes to section=admin&go=brewer. Checking $go=="admin" alone only ever matched the
 * first, so editing a participant via the pencil-icon link - $go=="brewer",
 * $section=="admin" - fell through to neither branch, left $user_id empty, and
 * silently skipped every judging_assignments cleanup below (confirmed empirically:
 * a real pencil-icon submission logged section='admin' go='brewer', resolved
 * user_id=''). Recognize either shape as an admin edit.
 */
$is_admin_edit_pb = ((($section ?? null) == "admin") || (($go ?? null) == "admin")) && (isset($_SESSION['userLevel'])) && ($_SESSION['userLevel'] <= 1);
if ($is_admin_edit_pb) $user_id = $filter;
elseif ((!$is_admin_edit_pb) && (isset($_POST['uid'])) && (isset($_SESSION['user_id'])) && (sterilize($_POST['uid']) == $_SESSION['user_id'])) $user_id = sterilize($_POST['uid']);

// Gather, convert, and/or sanitize info from the form
if (isset($_POST['brewerJudgeID'])) {
    $brewerJudgeID = $purifier->purify($_POST['brewerJudgeID']);
    $brewerJudgeID = strtoupper($brewerJudgeID);

    // A malformed ID (e.g. a typo'd extra digit) previously saved as-is, silently miscategorizing
    // the person as non-BJCP wherever validate_bjcp_id() is later checked in reports/exports - the
    // form itself only has client-side (pattern-attribute) validation as a first line of defense,
    // so also reject it server-side rather than persist a value known to be invalid.
    if ((!empty($brewerJudgeID)) && (!validate_bjcp_id($brewerJudgeID))) $brewerJudgeID = "";
}

if (isset($_POST['brewerJudgeMead'])) $brewerJudgeMead = sterilize($_POST['brewerJudgeMead']);
if (isset($_POST['brewerJudgeCider'])) $brewerJudgeCider = sterilize($_POST['brewerJudgeCider']);
if (isset($_POST['brewerJudgeRank'])) $brewerJudgeRank = $_POST['brewerJudgeRank'];
if (isset($_POST['brewerAHA'])) $brewerAHA = sterilize($_POST['brewerAHA']);
if (isset($_POST['brewerMHP'])) $brewerMHP = sterilize($_POST['brewerMHP']);
if (isset($_POST['brewerProAm'])) $brewerProAm = sterilize($_POST['brewerProAm']);
if (isset($_POST['brewerClubs'])) {
    include (DB.'entries.db.php');
    include (INCLUDES.'constants.inc.php');
    $brewerClubs = $purifier->purify($_POST['brewerClubs']);
    // $brewerClubsConcat = $brewerClubs."|".$brewerClubs;
    if (!in_array(html_entity_decode($brewerClubs),$_SESSION['club_array'])) {
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
}

if (isset($_POST['brewerBreweryTTB'])) {
    $brewerBreweryTTB = $purifier->purify($_POST['brewerBreweryTTB']);
    $brewerBreweryTTB = strtoupper($brewerBreweryTTB);
    $brewerBreweryInfo['TTB'] = $brewerBreweryTTB;
}

if (isset($_POST['brewerBreweryProd'])) {
    $brewerBreweryInfo['Production'] = sterilize($_POST['brewerBreweryProd'])." ".sterilize($_POST['brewerBreweryProdMeas']);
}

if (isset($_POST['brewerJudge'])) $brewerJudge = sterilize($_POST['brewerJudge']);
if (isset($_POST['brewerSteward'])) $brewerSteward = sterilize($_POST['brewerSteward']);
if (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant")) {
    $brewerJudge = "N";
    $brewerSteward = "N";
}

if (isset($_POST['brewerStaff'])) $brewerStaff = sterilize($_POST['brewerStaff']);
if (isset($_POST['brewerJudgeExp'])) $brewerJudgeExp = sterilize($_POST['brewerJudgeExp']);
if (isset($_POST['brewerJudgeNotes'])) {
    $brewerJudgeNotes = $purifier->purify($_POST['brewerJudgeNotes']);
}

if ((isset($_POST['brewerAssignment'])) && (!empty($_POST['brewerAssignment']))) {

    $aff = $_POST['brewerAssignment'];
    $affiliated_cleaned = array();
    foreach ($aff as $value) {
        $value = $purifier->purify(sterilize($value));
        $affiliated_cleaned[] = $value;
    }

    $affilliated = array("affilliated" => $affiliated_cleaned);

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
        $value = $purifier->purify(sterilize($value));
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

if (empty($brewerBreweryInfo)) $brewerBreweryInfo = "";
else $brewerBreweryInfo = json_encode($brewerBreweryInfo);

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

/**
 * GitHub issue #1752: a judge/steward who removes their own availability (or opts out
 * of judging/stewarding entirely) while still assigned to a table left that assignment
 * dangling with no warning - the assignment cleanup above/below still runs, but nothing
 * told the person (or a coordinator) that doing so would pull them off a table they'd
 * committed to, and by the time anyone noticed, the UI hid them from the fix (see the
 * companion fix to admin/judging_assign.admin.php). For a SELF-edit only (an admin
 * already has full visibility there and is presumed to be acting deliberately), block
 * the disabling change and surface a warning instead, unless the request explicitly
 * confirms it wants to proceed anyway.
 */
// $is_admin_edit_pb is computed above (GitHub issue #1752) - recognizes both real
// admin-edit URL shapes this app uses, not just $go=="admin".
$is_self_edit_pb = !$is_admin_edit_pb;

$assignment_conflict_check_pb = function($assignment_type, $location = null) use ($db_conn, $prefix, $user_id) {
    if (empty($user_id)) return null;
    $db_conn->where('bid', $user_id);
    $db_conn->where('assignment', $assignment_type);
    if ($location !== null) $db_conn->where('assignLocation', $location);
    $db_conn->orderBy('id', 'ASC');
    $row = $db_conn->getOne($prefix."judging_assignments", "id,assignTable,assignLocation");
    return $row ? $row : null;
};

$describe_assignment_conflict_pb = function($row_conflict) use ($db_conn, $prefix) {
    $db_conn->where('id', $row_conflict['assignTable']);
    $row_table = $db_conn->getOne($prefix."judging_tables", "tableNumber,tableName");
    $db_conn->where('id', $row_conflict['assignLocation']);
    $row_location = $db_conn->getOne($prefix."judging_locations", "judgingLocName");
    $table_desc = ($row_table) ? ("Table ".$row_table['tableNumber'].": ".$row_table['tableName']) : "a table";
    $location_desc = ($row_location) ? $row_location['judgingLocName'] : "that session";
    return $table_desc." at ".$location_desc;
};

// Full opt-out (brewerJudge/brewerSteward -> "N") is blocked here, before the location-
// preference blocks below run, so a block can cleanly preserve the person's EXISTING
// location list untouched instead of trying to reconcile it with whatever the form
// happened to post for a now-hidden/irrelevant location checklist.
$judge_location_already_handled_pb = false;
$steward_location_already_handled_pb = false;

if (($brewerJudge == "N") && ($is_self_edit_pb)) {

    $row_conflict = $assignment_conflict_check_pb('J');

    if (($row_conflict) && (empty($_POST['confirmDeregisterJudgeAll']))) {

        $brewerJudge = "Y";
        $db_conn->where('uid', $user_id);
        $row_current_brewer_pb = $db_conn->getOne($prefix."brewer", "brewerJudgeLocation");
        $location_pref1 = $row_current_brewer_pb['brewerJudgeLocation'] ?? "";
        $judge_location_already_handled_pb = true;
        $error_output[] = "You're still assigned to judge ".$describe_assignment_conflict_pb($row_conflict).". Check the confirmation box if you want to opt out of judging entirely anyway, or contact a competition coordinator to be unassigned first.";
        $errors = TRUE;

    }

}

if (($brewerSteward == "N") && ($is_self_edit_pb)) {

    $row_conflict = $assignment_conflict_check_pb('S');

    if (($row_conflict) && (empty($_POST['confirmDeregisterStewardAll']))) {

        $brewerSteward = "Y";
        $db_conn->where('uid', $user_id);
        $row_current_brewer_pb = $db_conn->getOne($prefix."brewer", "brewerStewardLocation");
        $location_pref2 = $row_current_brewer_pb['brewerStewardLocation'] ?? "";
        $steward_location_already_handled_pb = true;
        $error_output[] = "You're still assigned to steward ".$describe_assignment_conflict_pb($row_conflict).". Check the confirmation box if you want to opt out of stewarding entirely anyway, or contact a competition coordinator to be unassigned first.";
        $errors = TRUE;

    }

}

if ((($brewerJudge == "Y") || ($brewerStaff == "Y")) && (!$judge_location_already_handled_pb)) {

    $posted_judge_locations_pb = array();
    if (isset($_POST['brewerJudgeLocation'])) {
        $posted_judge_locations_pb = is_array($_POST['brewerJudgeLocation']) ? $_POST['brewerJudgeLocation'] : array($_POST['brewerJudgeLocation']);
    }

    $rebuilt_judge_locations_pb = array();

    foreach ($posted_judge_locations_pb as $value) {

        $loc = explode("-",$value);

        if ($loc[0] == "N") {

            $row_conflict = $assignment_conflict_check_pb('J', $loc[1]);
            $confirmed_locations_pb = (array) ($_POST['confirmDeregisterAssigned'] ?? array());

            if (($row_conflict) && ($is_self_edit_pb) && (!in_array($loc[1], $confirmed_locations_pb))) {

                // Blocked: keep them available here instead of silently reverting.
                $rebuilt_judge_locations_pb[] = "Y-".$loc[1];
                $error_output[] = "You're still assigned to judge ".$describe_assignment_conflict_pb($row_conflict).". Check the confirmation box for that session if you want to remove your availability anyway, or contact a competition coordinator to be unassigned first.";
                $errors = TRUE;

            }

            else {

                $rebuilt_judge_locations_pb[] = $value;

                if ((!empty($user_id)) && ($row_conflict)) {
                    $update_table = $prefix."judging_assignments";
                    $db_conn->where ('bid', $user_id);
                    $db_conn->where ('assignment', 'J');
                    $db_conn->where ('assignLocation', $loc[1]);
                    $result = $db_conn->delete($update_table);
                    if (!$result) {
                        $error_output[] = $db_conn->getLastError();
                        $errors = TRUE;
                    }
                }

            }

        } // end if ($loc[0] == "N")

        else $rebuilt_judge_locations_pb[] = $value;

    } // end foreach

    $location_pref1 = sterilize(implode(",",$rebuilt_judge_locations_pb));

} // end if ((($brewerJudge == "Y") || ($brewerStaff == "Y")) && (!$judge_location_already_handled_pb))

if (($brewerJudge == "N") && (!$judge_location_already_handled_pb)) {

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
                if ($judging_location_info[5] == "2") $location_pref1 .= $loc[0]."-".$loc[1].",";
                else $location_pref1 .= "N-".$loc[1].",";
            }

        }

        elseif ((isset($_POST['brewerJudgeLocation'])) && (!is_array($_POST['brewerJudgeLocation']))) {
            
            $loc = explode("-",$_POST['brewerJudgeLocation']);
            $judging_location_info = judging_location_info($loc[1]);
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

if (($brewerSteward == "Y") && (!$steward_location_already_handled_pb)) {

    $posted_steward_locations_pb = array();
    if (isset($_POST['brewerStewardLocation'])) {
        $posted_steward_locations_pb = is_array($_POST['brewerStewardLocation']) ? $_POST['brewerStewardLocation'] : array($_POST['brewerStewardLocation']);
    }

    $rebuilt_steward_locations_pb = array();

    foreach ($posted_steward_locations_pb as $value) {

        $loc = explode("-",$value);

        if ($loc[0] == "N") {

            $row_conflict = $assignment_conflict_check_pb('S', $loc[1]);
            $confirmed_locations_pb = (array) ($_POST['confirmDeregisterAssigned'] ?? array());

            if (($row_conflict) && ($is_self_edit_pb) && (!in_array($loc[1], $confirmed_locations_pb))) {

                // Blocked: keep them available here instead of silently reverting.
                $rebuilt_steward_locations_pb[] = "Y-".$loc[1];
                $error_output[] = "You're still assigned to steward ".$describe_assignment_conflict_pb($row_conflict).". Check the confirmation box for that session if you want to remove your availability anyway, or contact a competition coordinator to be unassigned first.";
                $errors = TRUE;

            }

            else {

                $rebuilt_steward_locations_pb[] = $value;

                if ((!empty($user_id)) && ($row_conflict)) {
                    $update_table = $prefix."judging_assignments";
                    $db_conn->where ('bid', $user_id);
                    $db_conn->where ('assignment', 'S');
                    $db_conn->where ('assignLocation', $loc[1]);
                    $result = $db_conn->delete($update_table);
                    if (!$result) {
                        $error_output[] = $db_conn->getLastError();
                        $errors = TRUE;
                    }
                }

            }

        } // end if ($loc[0] == "N")

        else $rebuilt_steward_locations_pb[] = $value;

    } // end foreach

    $location_pref2 = sterilize(implode(",",$rebuilt_steward_locations_pb));

} // end if (($brewerSteward == "Y") && (!$steward_location_already_handled_pb))

if (($brewerSteward == "N") && (!$steward_location_already_handled_pb)) {

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

} // end if (($brewerSteward == "N") && (!$steward_location_already_handled_pb))

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
    // Rebuild the last name from the compound particle(s) (preserved as entered,
    // e.g. "van der", "de") plus the fix-cased base surname, rather than the
    // fully fix-cased "lname" which would capitalize the particle(s).
    if (!empty($parsed_name['lname_compound'])) $last_name .= $parsed_name['lname_compound']." ";
    if (in_array($_SESSION['prefsLanguageFolder'], $last_name_exception_langs)) $last_name .= standardize_name($parsed_name['lname_base']);
    else $last_name .= $parsed_name['lname_base'];
    if (!empty($parsed_name['suffix'])) $last_name .= " ".$parsed_name['suffix'];

}

else {
    $first_name = $fname;
    $last_name = $lname;
}

$address = $purifier->purify($_POST['brewerAddress']);
$city = $purifier->purify($_POST['brewerCity']);

if ((isset($_POST['brewerStateUS'])) && (!empty($_POST['brewerStateUS']))) $state_province = $_POST['brewerStateUS'];
elseif ((isset($_POST['brewerStateCA'])) && (!empty($_POST['brewerStateCA']))) $state_province = $_POST['brewerStateCA'];
elseif ((isset($_POST['brewerStateAUS'])) && (!empty($_POST['brewerStateAUS']))) $state_province = $_POST['brewerStateAUS'];
elseif ((isset($_POST['brewerStateNon'])) && (!empty($_POST['brewerStateNon']))) $state_province = $_POST['brewerStateNon'];
else $state_province = "";

if (strlen($state_province) <= 2) $state_province = strtoupper($state_province);
$state_province = sterilize($state_province);

// Set all locations as YES for quick adds
if ($view == "quick") {

    $locations = array();

    $rows_j_locs = $db_conn->get($prefix."judging_locations", null, "id");

    foreach ($rows_j_locs as $row_j_locs) {
        $locations[] = "Y-".$row_j_locs['id'];
    }

    $location_pref1 = sterilize(implode(",",$locations));
    $location_pref2 = $location_pref1;

}
?>