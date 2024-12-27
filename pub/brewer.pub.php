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
    $security .= "
    <div class=\"form-check\">
    <input class=\"form-check-input\" type=\"radio\" name=\"userQuestion\" value=\"".$_SESSION['userQuestion']."\" CHECKED>
    <label class=\"form-check-label\">".$_SESSION['userQuestion']."</label>
    </div>
    ";
    foreach ($security_questions_display as $key => $value) {
        if ($security_question[$value] != $_SESSION['userQuestion']) 
        $security .= "
        <div class=\"form-check\">
        <input class=\"form-check-input\" type=\"radio\" name=\"userQuestion\" value=\"".$security_question[$value]."\" data-error=\"".$brewer_text_033."\" required>
        <label class=\"form-check-label\">".$security_question[$value]."</label>
        </div>
        ";
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

    $judge_staff_locations = explode(",", $row_brewer['brewerJudgeLocation']);
    $steward_locations = explode(",", $row_brewer['brewerStewardLocation']); 
    
    do { 

        $location_yes = "";
        $location_no = "";
        $location_steward_yes = "";
        $location_steward_no = "";
        $judge_avail_info = "";
        $judge_avail_option = "";
        $staff_avail_info = "";
        $staff_avail_option = "";
        $steward_avail_info = "";
        $steward_avail_option = "";

        /*
        $a = "Y-".$row_judging3['id'];
        if (in_array($a,$judge_staff_locations)) $location_yes = " CHECKED";
        else $location_no = " CHECKED";

        $b = "Y-".$row_judging3['id'];
        if (in_array($b,$steward_locations)) $location_steward_yes = " CHECKED";
        else $location_steward_no = " CHECKED";
        */

        $a = "Y-".$row_judging3['id'];
        if (in_array($a,$judge_staff_locations)) $location_yes = " selected";
        else $location_no = " selected";

        $b = "Y-".$row_judging3['id'];
        if (in_array($b,$steward_locations)) $location_steward_yes = " selected";
        else $location_steward_no = " selected";

        if ($row_judging3['judgingLocType'] == 2) {
            
            $staff_avail_info .= sprintf("<p class=\"mb-1\">%s <small class=\"ps-2\">%s</small></p>",$row_judging3['judgingLocName'],getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging3['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time"));

            /*
            $staff_avail_option .= "<div class=\"form-check form-check-inline\">";
            $staff_avail_option .= sprintf("<input class=\"form-check-input\" type=\"radio\" name=\"brewerNonJudgeLocation".$row_judging3['id']."\" value=\"Y-%s\" id=\"brewerNonJudgeLocation_0\" %s>",$row_judging3['id'],$location_yes);
            $staff_avail_option .= "<label class=\"form-check-label\">".$label_yes."</label>";
            $staff_avail_option .= "</div>";
            $staff_avail_option .= "<div class=\"form-check form-check-inline\">";
            $staff_avail_option .= sprintf("<input class=\"form-check-input\" type=\"radio\" name=\"brewerNonJudgeLocation".$row_judging3['id']."\" value=\"N-%s\" id=\"brewerNonJudgeLocation_1\" %s>",$row_judging3['id'],$location_no);
            $staff_avail_option .= "<label class=\"form-check-label\">".$label_no."</label>";
            $staff_avail_option .= "</div>";
            */

            $staff_avail_option .= "<div class=\"row\">";
            $staff_avail_option .= "<div class=\"col-xs-12 col-sm-6 col-md-3\">";
            $staff_avail_option .= "<select class=\"form-select mb-1\" name=\"brewerJudgeLocation[]\" id=\"brewerNonJudgeLocation".$row_judging3['id']."\">";
            $staff_avail_option .= sprintf("<option value=\"N-%s\"%s>%s</option>",$row_judging3['id'],$location_no,$label_no);
            $staff_avail_option .= sprintf("<option value=\"Y-%s\"%s>%s</option>",$row_judging3['id'],$location_yes,$label_yes);
            $staff_avail_option .= "</select>";
            $staff_avail_option .= "</div>";
            $staff_avail_option .= "</div>";
            

            if ((time() < $row_judging3['judgingDate'])  || (($go == "admin") && ($filter != "default"))) {
                $staff_location_avail .= "<section class=\"mb-3\">";
                $staff_location_avail .= $staff_avail_info;
                $staff_location_avail .= $staff_avail_option;
                $staff_location_avail .= "</section>";
            }

        }

        else {

            $judge_avail_info .= sprintf("<p class=\"mb-1\">%s <small class=\"ps-2\">%s</small></p>",$row_judging3['judgingLocName'],getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging3['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time"));

            /*
            $judge_avail_option .= "<div class=\"form-check form-check-inline\">";
            $judge_avail_option .= sprintf("<input class=\"form-check-input\" type=\"radio\" name=\"brewerJudgeLocation".$row_judging3['id']."\" value=\"Y-%s\" id=\"brewerJudgeLocation_0\" %s>",$row_judging3['id'],$location_yes);
            $judge_avail_option .= "<label class=\"form-check-label\">".$label_yes."</label>";
            $judge_avail_option .= "</div>";
            $judge_avail_option .= "<div class=\"form-check form-check-inline\">";
            $judge_avail_option .= sprintf("<input class=\"form-check-input\" type=\"radio\" name=\"brewerJudgeLocation".$row_judging3['id']."\" value=\"N-%s\" id=\"brewerJudgeLocation_1\" %s>",$row_judging3['id'],$location_no);
            $judge_avail_option .= "<label class=\"form-check-label\">".$label_no."</label>";
            $judge_avail_option .= "</div>";
            */

            $judge_avail_option .= "<div class=\"row\">";
            $judge_avail_option .= "<div class=\"col-xs-12 col-sm-6 col-md-3\">";
            $judge_avail_option .= "<select class=\"form-select mb-1\" name=\"brewerJudgeLocation[]\" id=\"brewerJudgeLocation".$row_judging3['id']."\" data-width=\"auto\">";
            $judge_avail_option .= sprintf("<option value=\"N-%s\"%s>%s</option>",$row_judging3['id'],$location_no,$label_no);
            $judge_avail_option .= sprintf("<option value=\"Y-%s\"%s>%s</option>",$row_judging3['id'],$location_yes,$label_yes);
            $judge_avail_option .= "</select>";
            $judge_avail_option .= "</div>";
            $judge_avail_option .= "</div>";
            
            if ((time() < $row_judging3['judgingDate'])  || (($go == "admin") && ($filter != "default"))) {
                $judge_location_avail .= "<section class=\"mb-3\">";
                $judge_location_avail .= $judge_avail_info;
                $judge_location_avail .= $judge_avail_option;
                $judge_location_avail .= "</section>";
            }

            $steward_avail_info .= sprintf("<p class=\"mb-1\">%s <small class=\"ps-2\">%s</small></p>",$row_judging3['judgingLocName'],getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging3['judgingDate'], $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "short", "date-time"));

            /*
            $steward_avail_option .= "<div class=\"form-check form-check-inline\">";
            $steward_avail_option .= sprintf("<input class=\"form-check-input\" type=\"radio\" name=\"brewerStewardLocation".$row_judging3['id']."\" value=\"Y-%s\" id=\"brewerStewardLocation_0\" %s>",$row_judging3['id'],$location_steward_yes);
            $steward_avail_option .= "<label class=\"form-check-label\">".$label_yes."</label>";
            $steward_avail_option .= "</div>";
            $steward_avail_option .= "<div class=\"form-check form-check-inline\">";
            $steward_avail_option .= sprintf("<input class=\"form-check-input\" type=\"radio\" name=\"brewerStewardLocation".$row_judging3['id']."\" value=\"N-%s\" id=\"brewerStewardLocation_1\" %s>",$row_judging3['id'],$location_steward_no);
            $steward_avail_option .= "<label class=\"form-check-label\">".$label_no."</label>";
            $steward_avail_option .= "</div>";
            */
            
            $steward_avail_option .= "<div class=\"row\">";
            $steward_avail_option .= "<div class=\"col-xs-12 col-sm-6 col-md-3\">";
            $steward_avail_option .= "<select class=\"form-select mb-1\" name=\"brewerStewardLocation[]\" id=\"brewerStewardLocation".$row_judging3['id']."\" data-width=\"auto\">";
            $steward_avail_option .= sprintf("<option value=\"N-%s\"%s>%s</option>",$row_judging3['id'],$location_steward_no,$label_no);
            $steward_avail_option .= sprintf("<option value=\"Y-%s\"%s>%s</option>",$row_judging3['id'],$location_steward_yes,$label_yes);
            $steward_avail_option .= "</select>";
            $steward_avail_option .= "</div>";
            $steward_avail_option .= "</div>";
            

            if ((time() < $row_judging3['judgingDate'])  || (($go == "admin") && ($filter != "default"))) {
                $steward_location_avail .= "<section class=\"mb-3\">";
                $steward_location_avail .= $steward_avail_info;
                $steward_location_avail .= $steward_avail_option;
                $steward_location_avail .= "</section>";
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
    $submit_icon = "pencil";
    $submit_text = $label_edit_account;
}

else {
    $submit_icon = "plus";
    $submit_text = $label_add_admin;
}

if ($go != "admin") echo $info_msg;
?>
<form id="submit-form" role="form" class="form-horizontal hide-loader-form-submit needs-validation" action="<?php echo $form_action; ?>" method="POST" name="form1" novalidate>
<input type="hidden" name="token" value ="<?php if (isset($_SESSION['token'])) echo $_SESSION['token']; ?>">

<?php 
include (PUB.'brewer_form_0.pub.php'); // Participant Info
if (!$entrant_type_brewery) include (PUB.'brewer_form_1.pub.php'); // Info for individuals only (not orgs)
if (($go != "entrant") && ($section != "step2")) include (PUB.'brewer_form_2.pub.php'); // Judge/Steward info
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

<div class="bcoem-admin-element hidden-print">
    <div class="mb-3 mt-5 row">
        <div class="col-xs-12 col-sm-3 col-lg-2"></div>
        <div class="col-xs-12 col-sm-9 col-lg-10 d-grid">
            <button name="submit" type="submit" class="btn btn-lg btn-primary <?php if ($disable_fields) echo "disabled"; ?>" ><?php echo $submit_text; ?><i class="ms-2 fa fa-fw fa-<?php echo $submit_icon; ?>"></i></button>
        </div>
    </div>
</div>
</form>
<script src="<?php echo $js_user_url; ?>"></script>
<?php } // end if (($section == "step2") || ($action == "add") || (($action == "edit") && (($_SESSION['loginUsername'] == $row_brewerID['brewerEmail'])) || ($_SESSION['userLevel'] <= "1")))
else echo "<p class='lead'>You can only edit your own profile.</p>";
?>