<?php
/**
 * Module:      brewer.sec.php
 * Description: This module houses the functionality for users to add/edit their personal
 *              information - references the "brewer" database table.
 *
 */

/*
// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && (!isset($base_url)))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}
*/

if ($section != "step2") {
    include_once (DB.'judging_locations.db.php');
    include_once (DB.'stewarding.db.php');
    include_once (DB.'styles.db.php');
}

include_once (DB.'brewer.db.php');
include_once (DB.'dropoff.db.php');

if ($section != "step2") {

    if (($row_brewer['brewerCountry'] == "United States")) $us_phone = TRUE; 
    else $us_phone = FALSE;

    $phone1 = $row_brewer['brewerPhone1'];
    $phone2 = $row_brewer['brewerPhone2'];

    if ($us_phone) {
        $phone1 = format_phone_us($phone1);
        $phone2 = format_phone_us($phone2);
    }

}

$show_judge_steward_fields = TRUE;
$entrant_type_brewery = FALSE;
$pro_entrant = FALSE;
$club_other = FALSE;
$affiliated_other = FALSE;

if ($section == "step2") {
    $_SESSION['prefsProEdition'] = 0;
    $show_judge_steward_fields = FALSE;
    $entrant_type_brewery = FALSE;
    $table_assign_judge = "";
    $table_assign_steward = "";
    $club_alert = "";
}

else {
    // Get table assignments and build flags
    $table_assign_judge = table_assignments($_SESSION['user_id'],"J",$_SESSION['prefsTimeZone'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'],0);
    $table_assign_steward = table_assignments($_SESSION['user_id'],"S",$_SESSION['prefsTimeZone'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'],0);
}

if ((!empty($table_assign_judge)) || (!empty($table_assign_steward))) $table_assignment = TRUE;
if ((empty($table_assign_judge)) && (empty($table_assign_steward))) $table_assignment = FALSE;

if ($_SESSION['prefsProEdition'] == 1) {

    // If registered as a brewery, will not be a judge
    // Only individuals can be judges, stewards, or staff
    if (($row_brewer['brewerJudge'] != "Y") && ($row_brewer['brewerSteward'] != "Y") && (isset($row_brewer['brewerBreweryName']))) {
        $show_judge_steward_fields = FALSE;
        $entrant_type_brewery = TRUE;
    }

    else {
        $label_contact = "";
        $label_organization = "";
        $entrant_type_brewery = FALSE;
    }

}

// Build info message
if (($section == "step2") || ($action == "add") || (($action == "edit") && (($_SESSION['loginUsername'] == $row_brewerID['brewerEmail'])) || ($_SESSION['userLevel'] <= "1")))  {

$info_msg = "";
if (($_SESSION['prefsProEdition'] == 1) && (!$show_judge_steward_fields)) $info_msg .= sprintf("<p class=\"lead\">%s <small>%s</small></p>",$register_text_035,$register_text_036);
elseif (($_SESSION['prefsProEdition'] == 1) && ($show_judge_steward_fields)) $info_msg .= sprintf("<p class=\"lead\">%s</p>",$register_text_004);
else $info_msg .= sprintf("<p class=\"lead\">%s <small>%s</small></p>",$register_text_004,$register_text_005);

// Build form action link
if ($section == "step2") $form_action = $base_url."includes/process.inc.php?section=setup&amp;action=add&amp;dbTable=".$brewer_db_table;
else {
    $form_action = $base_url."includes/process.inc.php?section=";
    if ($section == "brewer") $form_action .= "list";
    else $form_action .= "admin";
    $form_action .= "&amp;go=".$go."&amp;filter=".$filter."&amp;action=".$action."&amp;dbTable=".$brewer_db_table;
    // if ($table_assignment) $form_action .= "&amp;view=assigned";
    if ($action == "edit") $form_action .= "&amp;id=".$row_brewer['id'];
}

if (($_SESSION['prefsProEdition'] == 0) || (($_SESSION['prefsProEdition'] == 1) && ($show_judge_steward_fields))) {

    // Build Clubs dropdown
    $club_options = "";
    $club_alert = "";

    if ($section != "step2") {
        if ((!empty($row_brewer['brewerClubs'])) && (!in_array($row_brewer['brewerClubs'],$club_array))) {
            $club_other = TRUE;
            $club_alert .= sprintf("<div id=\"clubOther\" class=\"alert alert-warning\"><span class=\"fa fa-exclamation-circle\"></span> <strong>%s</strong> %s %s</div>",$brewer_text_036,$brewer_text_037,$brewer_text_038);
        }
        // Fail safe from previous versions
        if ((!$club_other) && (!in_array($row_brewer['brewerClubs'],$club_array)) && (!empty($row_brewer['brewerClubs']))) $club_alert .= sprintf("<div id=\"clubOther-warning\" class=\"alert alert-warning\"><span class=\"fa fa-exclamation-circle\"></span> <strong>%s</strong> %s %s</div>",$brewer_text_039,$brewer_text_040,$brewer_text_038);
    }

    foreach ($club_array as $club) {
        $club_selected = "";
        if ($section != "step2") {
            if ($club == $row_brewer['brewerClubs']) $club_selected = " SELECTED";
        }
        $club_options .= "<option value=\"".$club."\"".$club_selected.">".$club."</option>\n";
    }

}

$organization_options = "";

if ($show_judge_steward_fields) include(DB.'organizations.db.php');

$security_questions_display = (array_rand($security_question, 5));
$security = "";
if ($section != "step2") {
    $security .= "<div class=\"radio\"><label><input type=\"radio\" name=\"userQuestion\" value=\"".$_SESSION['userQuestion']."\" CHECKED> ".$_SESSION['userQuestion']."</label></div>";
    foreach ($security_questions_display as $key => $value) {
        if ($security_question[$value] != $_SESSION['userQuestion']) $security .= "<div class=\"radio\"><label><input type=\"radio\" name=\"userQuestion\" value=\"".$security_question[$value]."\" data-error=\"".$brewer_text_033."\" required> ".$security_question[$value]."</label></div>";
    }
}

asort($countries);

$country_select = "";
foreach ($countries as $country) {
    $country_select .= "<option value=\"".$country."\" ";
    if ((isset($row_brewer['brewerCountry'])) && ($row_brewer['brewerCountry'] == $country)) $country_select .= "SELECTED";
    $country_select .= ">";
    $country_select .= $country."</option>\n";
}

$us_state_select = "";
foreach ($us_state_abbrevs_names as $key => $value) {
    $us_state_select .= "<option value=\"".$key."\" ";
    if (isset($row_brewer['brewerState'])) { 
        $us_state = strtolower($value);
        $us_state_abb = strtolower($key);
        $us_state_user = strtolower($row_brewer['brewerState']);
        if (($row_brewer['brewerState'] == $key) || ($us_state == $us_state_user) || ($us_state_abb == $us_state_user)) $us_state_select .= "SELECTED";
    }
    $us_state_select .= ">";
    $us_state_select .= $value." [".$key."]</option>\n";
}

$ca_state_select = "";
foreach ($ca_state_abbrevs_names as $key => $value) {
    $ca_state_select .= "<option value=\"".$key."\" ";
    if (isset($row_brewer['brewerState'])) { 
        $ca_state = strtolower($value);
        $ca_state_abb = strtolower($key);
        $ca_state_user = strtolower($row_brewer['brewerState']);
        if (($row_brewer['brewerState'] == $key) || ($ca_state == $ca_state_user) || ($ca_state_abb == $ca_state_user)) $ca_state_select .= "SELECTED";
    }
    $ca_state_select .= ">";
    $ca_state_select .= $value." [".$key."]</option>\n";
}

$aus_state_select = "";
foreach ($aus_state_abbrevs_names as $key => $value) {
    $aus_state_select .= "<option value=\"".$key."\" ";
    if (isset($row_brewer['brewerState'])) { 
        $aus_state = strtolower($value);
        $aus_state_abb = strtolower($key);
        $aus_state_user = strtolower($row_brewer['brewerState']);
        if (($row_brewer['brewerState'] == $key) || ($aus_state == $aus_state_user) || ($aus_state_abb == $aus_state_user)) $aus_state_select .= "SELECTED";
    }
    $aus_state_select .= ">";
    $aus_state_select .= $value." [".$key."]</option>\n";
}

$judge_location_avail = "";
$steward_location_avail = "";
$staff_location_avail = "";

if ((isset($row_judging3)) && (!empty($row_judging3))) {
    
    do { 

        $location_yes = "";
        $location_no = "";
        $judge_avail_info = "";
        $judge_avail_option = "";
        $staff_avail_info = "";
        $staff_avail_option = "";

        $location_steward_no = "";
        $location_steward_yes = "";
        $steward_avail_info = "";
        $steward_avail_option = "";

        $a = explode(",", $row_brewer['brewerJudgeLocation']); 
        $b = "N-".$row_judging3['id']; 
        foreach ($a as $value) { 
            if ($value == $b) $location_no = " SELECTED"; 
        }

        $c = explode(",", $row_brewer['brewerJudgeLocation']); 
        $d = "Y-".$row_judging3['id']; 
        foreach ($c as $value) { 
            if ($value == $d) $location_yes = " SELECTED";
        }

        $e = explode(",", $row_brewer['brewerStewardLocation']); 
        $f = "N-".$row_judging3['id']; 
        foreach ($e as $value) { 
            if ($value == $f) $location_steward_no = " SELECTED";
        }

        $g = explode(",", $row_brewer['brewerStewardLocation']); 
        $h = "Y-".$row_judging3['id']; 
        foreach ($g as $value) { 
            if ($value == $h) $location_steward_yes = " SELECTED";
        }

        if ($row_judging3['judgingLocType'] == 2) {
            
            $staff_avail_info .= sprintf("<p class=\"bcoem-form-info\">%s (%s)</p>",$row_judging3['judgingLocName'],getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging3['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time"));
           
            $staff_avail_option .= "<select class=\"selectpicker\" name=\"brewerJudgeLocation[]\" id=\"brewerNonJudgeLocation".$row_judging3['id']."\" data-width=\"auto\">";
            $staff_avail_option .= sprintf("<option value=\"N-%s\"%s>%s</option>",$row_judging3['id'],$location_no,$label_no);
            $staff_avail_option .= sprintf("<option value=\"Y-%s\"%s>%s</option>",$row_judging3['id'],$location_yes,$label_yes);
            $staff_avail_option .= "</select>";

            if ((time() < $row_judging3['judgingDate'])  || (($go == "admin") && ($filter != "default"))) {
                $staff_location_avail .= $staff_avail_info;
                $staff_location_avail .= $staff_avail_option;
            }

        }

        else {

            $judge_avail_info .= sprintf("<p class=\"bcoem-form-info\">%s (%s)</p>",$row_judging3['judgingLocName'],getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging3['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time"));

            $judge_avail_option .= "<select class=\"selectpicker\" name=\"brewerJudgeLocation[]\" id=\"brewerJudgeLocation".$row_judging3['id']."\" data-width=\"auto\">";
            $judge_avail_option .= sprintf("<option value=\"N-%s\"%s>%s</option>",$row_judging3['id'],$location_no,$label_no);
            $judge_avail_option .= sprintf("<option value=\"Y-%s\"%s>%s</option>",$row_judging3['id'],$location_yes,$label_yes);
            $judge_avail_option .= "</select>";
            
            if ((time() < $row_judging3['judgingDate'])  || (($go == "admin") && ($filter != "default"))) {
                $judge_location_avail .= $judge_avail_info;
                $judge_location_avail .= $judge_avail_option;
            }

            $steward_avail_info .= sprintf("<p class=\"bcoem-form-info\">%s (%s)</p>",$row_judging3['judgingLocName'],getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging3['judgingDate'], $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "short", "date-time"));

            $steward_avail_option .= "<select class=\"selectpicker\" name=\"brewerStewardLocation[]\" id=\"brewerStewardLocation".$row_judging3['id']."\" data-width=\"auto\">";
            $steward_avail_option .= sprintf("<option value=\"N-%s\"%s>%s</option>",$row_judging3['id'],$location_steward_no,$label_no);
            $steward_avail_option .= sprintf("<option value=\"Y-%s\"%s>%s</option>",$row_judging3['id'],$location_steward_yes,$label_yes);
            $steward_avail_option .= "</select>";

            if ((time() < $row_judging3['judgingDate'])  || (($go == "admin") && ($filter != "default"))) {
                $steward_location_avail .= $steward_avail_info;
                $steward_location_avail .= $steward_avail_option;
            }

        }

    }  while ($row_judging3 = mysqli_fetch_assoc($judging3)); 

}

if (($_SESSION['prefsProEdition'] == 1) && ((!$show_judge_steward_fields) || ($go == "admin"))) $pro_entrant = TRUE;

// Build drop-off select element
$dropoff_select = "";
if (($section != "step2") && ($row_brewer) && ($row_dropoff)) {
    do {
        if (($action == "edit") && ($row_brewer['brewerDropOff'] == $row_dropoff['id'])) $selected = "SELECTED";
        else $selected = "";
        $dropoff_select .= sprintf("<option value=\"%s\" %s>%s</option>",$row_dropoff['id'],$selected,$row_dropoff['dropLocationName']);
     } while ($row_dropoff = mysqli_fetch_assoc($dropoff));
}

if ($action == "add") {
    $submit_icon = "plus";
    $submit_text = "Add Account Info";
}

if ($action == "edit") {
    $submit_icon = $label_add_admin;
    $submit_text = $label_edit_account;
}

else {
    $submit_icon = "plus";
    $submit_text = $label_add_admin;
}

if ($go != "admin") echo $info_msg;
?>
<form id="submit-form" class="form-horizontal hide-loader-form-submit" data-toggle="validator" action="<?php echo $form_action; ?>" method="POST" name="form1">
<input type="hidden" name="token" value ="<?php if (isset($_SESSION['token'])) echo $_SESSION['token']; ?>">
<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-4 col-xs-12"></label>
    <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
        <p class="bcoem-form-info text-warning"><i class="fa fa-star"></i> <strong>= <?php echo $label_required_info; ?></strong></p>
    </div>
</div>
<?php 
include (SECTIONS.'brewer_form_0.sec.php'); // Participant Info
if (!$entrant_type_brewery) include (SECTIONS.'brewer_form_1.sec.php'); // Info for individuals only (not orgs)
if (($go != "entrant") && ($section != "step2")) include (SECTIONS.'brewer_form_2.sec.php'); // Judge/Steward info
?>

<?php if ($section == "step2") { ?>
    <input name="brewerSteward" type="hidden" value="N" />
    <input name="brewerJudge" type="hidden" value="N" />
    <input name="brewerEmail" type="hidden" value="<?php echo $go; ?>" />
    <input name="uid" type="hidden" value="<?php echo $row_brewerID['id']; ?>" />
<?php } else { ?>
    <input name="brewerEmail" type="hidden" value="<?php if ($filter != "default") echo $row_brewer['brewerEmail']; else echo $_SESSION['user_name']; ?>" />
    <input name="uid" type="hidden" value="<?php if (($action == "edit") && ($row_brewer['uid'] != "")) echo  $row_brewer['uid']; elseif (($action == "edit") && ($_SESSION['userLevel'] <= "1") && (($_SESSION['loginUsername']) != $row_brewer['brewerEmail'])) echo $row_user_level['id']; else echo $_SESSION['user_id']; ?>" />
    <?php if (($go == "entrant") || (($_SESSION['prefsProEdition'] == 1) && (isset($row_brewer['brewerBreweryName'])))) { ?>
    <input name="brewerJudge" type="hidden" value="N" />
    <input name="brewerSteward" type="hidden" value="N" />
    <?php }
} ?>

<?php if ($go == "admin") { ?>
    <?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
    <input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
    <?php } else { ?>
    <input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin&go=participants","default",$msg,$id); ?>">
    <?php } ?>
<?php } else { ?>
    <input type="hidden" name="relocate" value="<?php echo $base_url; ?>index.php?section=list">
<?php } ?>
<div class="form-group">
    <div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-4">
        <!-- Input Here -->
        <button id="form-submit-button" name="submit" type="submit" class="btn btn-primary" ><?php echo $submit_text; ?> </button>
    </div>
</div><!-- Form Group -->
<div class="alert alert-warning" style="margin-top: 10px;" id="form-submit-button-disabled-msg-required">
    <?php echo sprintf("<p><i class=\"fa fa-exclamation-triangle\"></i> <strong>%s</strong> %s</p>",$form_required_fields_00,$form_required_fields_01); ?>
</div>
</form>
<?php } // WAY up top... end if (($section == "step2") || ($action == "add") || (($action == "edit") && (($_SESSION['loginUsername'] == $row_brewerID['brewerEmail'])) || ($_SESSION['userLevel'] <= "1")))
else echo "<p class='lead'>You can only edit your own profile.</p>";
?>