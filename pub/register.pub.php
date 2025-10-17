<?php
/*
// Redirect if directly accessed
if ((!isset($_SESSION['prefs'.$prefix_session])) || ((isset($_SESSION['prefs'.$prefix_session])) && (!isset($base_url)))) {
    $redirect = "../../index.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}
*/

?>
<script type="text/javascript">
var action = "<?php echo $action; ?>";
</script>
<script src="<?php echo $js_url; ?>registration_checks.min.js<?php if (((DEBUG) || (TESTING)) && (strpos($base_url, 'test.brewingcompetitions.com') !== false)) echo "?t=".time(); ?>"></script>
<?php
$warning0 = "";
$warning1 = "";
$warning2 = "";
$primary_page_info = "";
$header1_1 = "";
$page_info1 = "";
$header1_2 = "";
$page_info2 = "";

if ($section == "admin") $you_volunteer = $register_text_000;
else $you_volunteer = $register_text_001;

if (($registration_open == 2) && ($judge_window_open == 2) && (!$logged_in) || (($logged_in) && ($_SESSION['userLevel'] == 2))) {

	// Show registration closed message if
	// 1) registration window is closed,
	// 2) the judge/steward registration window is closed,
	// 3) the user is not logged in OR the user is logged in and their user level is 2 (non-admin)
	$page_info1 .= sprintf("<p class=\"lead\">%s <small>%s</small></p>",$register_text_002,$register_text_003);
	echo $page_info1;
}

elseif (($registration_open == 0) && ($judge_window_open == 0) && (!$logged_in) || (($logged_in) && ($_SESSION['userLevel'] == 2))) {

	// Show registration closed message if
	// 1) registration window is closed,
	// 2) the judge/steward registration window is closed,
	// 3) the user is not logged in OR the user is logged in and their user level is 2 (non-admin)
	$page_info1 .= sprintf("<p class=\"lead\">%s</p>",$alert_text_033);
	echo $page_info1;
}

elseif (($registration_open == 0) && ($judge_window_open == 1) && ($go == "entrant") && (!$logged_in) || (($logged_in) && ($_SESSION['userLevel'] == 2))) {

	// Show registration closed message if
	// 1) registration window is closed,
	// 2) the judge/steward registration window is OPEN,
	// 3) the user is not logged in OR the user is logged in and their user level is 2 (non-admin)
	$page_info1 .= sprintf("<p class=\"lead\">%s</p>",$alert_text_033);
	echo $page_info1;
}

else { // THIS ELSE ENDS at the end of the script

	include_once (DB.'judging_locations.db.php');
	include_once (DB.'stewarding.db.php');
	include_once (DB.'styles.db.php');
	include_once (DB.'brewer.db.php');
	if (NHC) $totalRows_log = $totalRows_entry_count;
	else $totalRows_log = $totalRows_log;
	if ($go != "default") {
		
		asort($countries);
		$country_select = "";
		foreach ($countries as $country) {
			$country_select .= "<option value=\"".$country."\" ";
			if (($msg != "default") && (isset($_COOKIE['brewerCountry'])) && ($_COOKIE['brewerCountry'] == $country)) $country_select .= "SELECTED";
			$country_select .= ">";
			$country_select .= $country."</option>\n";
     	}

     	$us_state_select = "";
		foreach ($us_state_abbrevs_names as $key => $value) {
			$us_state_select .= "<option value=\"".$key."\" ";
			if (($msg != "default") && (isset($_COOKIE['brewerState'])) && ($_COOKIE['brewerState'] == $key)) $us_state_select .= "SELECTED";
			$us_state_select .= ">";
			$us_state_select .= $value." [".$key."]</option>\n";
     	}

     	$ca_state_select = "";
		foreach ($ca_state_abbrevs_names as $key => $value) {
			$ca_state_select .= "<option value=\"".$key."\" ";
			if (($msg != "default") && (isset($_COOKIE['brewerState'])) && ($_COOKIE['brewerState'] == $key)) $ca_state_select .= "SELECTED";
			$ca_state_select .= ">";
			$ca_state_select .= $value." [".$key."]</option>\n";
     	}

     	$aus_state_select = "";
		foreach ($aus_state_abbrevs_names as $key => $value) {
			$aus_state_select .= "<option value=\"".$key."\" ";
			if (($msg != "default") && (isset($_COOKIE['brewerState'])) && ($_COOKIE['brewerState'] == $key)) $aus_state_select .= "SELECTED";
			$aus_state_select .= ">";
			$aus_state_select .= $value." [".$key."]</option>\n";
     	}

	$random_country = array_rand($countries);
	$random_country = $countries[$random_country];

	include (DB.'dropoff.db.php');

	if ($totalRows_dropoff > 0) {
		$dropoff_select = "";
		do {
    		$dropoff_select .= "<option value=\"".$row_dropoff['id']."\" ";
			if (($action == "edit") && ($row_brewer['brewerDropOff'] == $row_dropoff['id'])) $dropoff_select .= "SELECTED";
			if (($msg != "default") && (isset($_COOKIE['brewerDropOff'])) && ($_COOKIE['brewerDropOff'] == $row_dropoff['id'])) $dropoff_select .= "SELECTED";
			$dropoff_select .= ">";
			$dropoff_select .= $row_dropoff['dropLocationName']."</option>\n";
   		} while ($row_dropoff = mysqli_fetch_assoc($dropoff));
	}
}

if (($comp_paid_entry_limit) && ($go == "entrant")) $warning0 .= sprintf("<div class=\"alert alert-danger\"><strong>%s:</strong> %s</div>",$label_please_note,$alert_text_053);

if (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant")) $warning1 .= sprintf("<p class=\"lead\">%s <small>%s</small></p>",$register_text_035,$register_text_036);
elseif (($_SESSION['prefsProEdition'] == 1) && ($go != "entrant")) $warning1 .= sprintf("<p class=\"lead\">%s</p>",$register_text_004);
else $warning1 .= sprintf("<p class=\"lead\">%s <small>%s</small></p>",$register_text_004,$register_text_005);
$warning2 .= sprintf("<div class=\"alert alert-warning\"><span class=\"fa fa-exclamation-triangle\"> <strong>%s</strong></div>",$register_text_006);

if ($section == "admin") {
	$header1_1 .= "<p class=\"lead\">";
	if ($view == "quick") $header1_1 .= sprintf("%s ",$register_text_007);
	$header1_1 .= sprintf("%s ",$register_text_008);
	if ($go == "judge") $header1_1 .= sprintf("%s",$register_text_009);
    if ($go == "steward") $header1_1 .= sprintf("%s",$register_text_027);
	$header1_1 .= "</p>";
}

if (($go != "default") && ($section != "admin")) $page_info1 .= sprintf("<p>%s</p>",$register_text_011);
if ($view == "quick") $page_info1 .= sprintf("<p>%s</p>",$register_text_012);
/*if ((($registration_open < 2) || ($judge_window_open < 2)) && ($go == "default") && ($section != "admin") && ((!$comp_entry_limit) || (!$comp_paid_entry_limit))) {
	$page_info1 .= sprintf("<p>%s</p>",$register_text_013);
	$page_info1 .= "<ul>";
	if (!NHC) {
		$page_info1 .= sprintf("<li>%s</li>",$register_text_014);
	}
	$page_info1 .= sprintf("<li>%s</li>",$register_text_015);
	if ((!NHC) || ((NHC) && ($prefix != "final_"))) {
		$page_info1 .= sprintf("<li>%s</li>",$register_text_016);
		$page_info1 .= sprintf("<li>%s</li>",$register_text_017);
	}
	$page_info1 .= "</ul>";
}*/

if (isset($_SERVER['HTTP_REFERER'])) $relocate = $_SERVER['HTTP_REFERER'];
else $relocate = $base_url."index.php?section=list";

$entrant_hidden = FALSE;
$judge_hidden = FALSE;
$steward_hidden = FALSE;

if ($registration_open != 1) {
	$entrant_hidden = TRUE;
}

if ($go == "entrant") {
	$judge_hidden = TRUE;
	$steward_hidden = TRUE;
}

if ($go == "judge") {
	$steward_hidden = TRUE;
}

if ($go == "steward") {
	$judge_hidden = TRUE;
}

if (($section != "admin") && ($judge_limit)) {
	$judge_hidden = TRUE;
}

if (($section != "admin") && ($steward_limit)) {
	$steward_hidden = TRUE;
}

if (($section == "admin") && ($_SESSION['userLevel'] <= 1)) {
    if ($go == "judge") $judge_hidden = FALSE;
    if ($go == "steward") $steward_hidden = FALSE;
}

if (($_SESSION['prefsProEdition'] == 0) || (($_SESSION['prefsProEdition'] == 1) && ($go != "entrant"))) {

	// Build Clubs dropdown
	$club_options = "";
	$club_other = FALSE;

	foreach ($club_array as $club) {
		$club_options .= "<option value=\"".$club."\">".$club."</option>\n";
	}

}

$security_questions_display = (array_rand($security_question, 10));
$security = "";

if ((isset($_COOKIE['userQuestion'])) && ($_COOKIE['userQuestion'] != "Randomly generated.")) {
	$security .= "<div class=\"form-check\">";
	$security .= "<input class=\"form-check-input\" type=\"radio\" name=\"userQuestion\" value=\"".$_COOKIE['userQuestion']."\" CHECKED>";
	$security .= "<label class=\"form-check-label\">".$_COOKIE['userQuestion']."</label>";
	$security .= "</div>";
}

foreach ($security_questions_display as $key => $value) {

	$security_checked = "";
	
	if (($msg == "default") && ($key == 0) && (!isset($_COOKIE['userQuestion']))) $security_checked = "CHECKED";

    if (isset($_COOKIE['userQuestion'])) {
        
        if ($_COOKIE['userQuestion'] == $security_question[$value]) $security .= "";
        
        else {
        	
        	$security .= "<div class=\"form-check\">";
        	$security .= "<input class=\"form-check-input\" type=\"radio\" name=\"userQuestion\" value=\"".$security_question[$value]."\" ".$security_checked.">";
        	$security .= "<label class=\"form-check-label\">".$security_question[$value]."</label>";
        	$security .= "</div>";
        
        }

    }

	else {
			$security .= "<div class=\"form-check\">";
			$security .= "<input class=\"form-check-input\" type=\"radio\" name=\"userQuestion\" value=\"".$security_question[$value]."\" ".$security_checked.">";
			$security .= "<label class=\"form-check-label\">".$security_question[$value]."</label>";
			$security .= "</div>";
	}

}

$steward_location_avail = "";
$judge_location_avail = "";
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

        if ($row_judging3['judgingLocType'] == 2) {

        	$staff_avail_info .= sprintf("<p class=\"mb-1\">%s <small class=\"ps-2\">%s</small></p>",$row_judging3['judgingLocName'],getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging3['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time"));
            
            $staff_avail_option .= "<div class=\"row\">";
            $staff_avail_option .= "<div class=\"col-xs-12 col-sm-6 col-md-3\">";
            $staff_avail_option .= "<select class=\"form-select mb-1\" name=\"brewerJudgeLocation[]\" id=\"brewerNonJudgeLocation".$row_judging3['id']."\">";
            $staff_avail_option .= sprintf("<option value=\"N-%s\"%s>%s</option>",$row_judging3['id'],$location_no,$label_no);
            $staff_avail_option .= sprintf("<option value=\"Y-%s\"%s>%s</option>",$row_judging3['id'],$location_yes,$label_yes);
            $staff_avail_option .= "</select>";
            $staff_avail_option .= "</div>";
            $staff_avail_option .= "</div>";

            if (((time() < $row_judging3['judgingDate']) || (($go == "admin") && ($filter != "default"))) && (!empty($staff_avail_info) && (!empty($staff_avail_option)))) {
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
            $judge_avail_option .= "<select class=\"form-select bootstrap-select mb-1\" name=\"brewerJudgeLocation[]\" id=\"brewerJudgeLocation".$row_judging3['id']."\" data-width=\"auto\" required>";
            $judge_avail_option .= "<option value=\"\"></option>"; 
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
            $steward_avail_option .= "<select class=\"form-select bootstrap-select mb-1\" name=\"brewerStewardLocation[]\" id=\"brewerStewardLocation".$row_judging3['id']."\" data-width=\"auto\" required>";
            $steward_avail_option .= "<option value=\"\"></option>"; 
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

// --------------------------------------------------------------
// Display
// --------------------------------------------------------------

if (($section != "admin") && ($action != "print")) {
    echo $warning0;
    echo $warning1;
}

echo $header1_1;
echo $page_info1;

if ($go == "default") {  ?>
<p class="lead"><?php echo $register_text_014; ?></p>
<div class="row">
	<?php if ($registration_open == 1) { ?>
    <div class="col col-xs-12 col-sm-12 col-md-4">
        <div class="d-grid mb-2">
        	<a class="btn btn-dark btn-lg" href="<?php echo build_public_url("register","entrant","default","default",$sef,$base_url); ?>"><?php echo $label_entrant_reg; ?></a>
        </div>
    </div>
	<?php } ?>
	<?php if ($judge_window_open == 1) {?>
    <div class="col col-xs-12 col-sm-12 col-md-4">
    	<div class="d-grid mb-2">
	        <a class="btn btn-dark btn-lg" href="<?php echo build_public_url("register","judge","default","default",$sef,$base_url); ?>"><?php echo $label_judge_reg; ?></a>
	    </div>
    </div>
    <div class="col col-xs-12 col-sm-12 col-md-4">
    	<div class="d-grid mb-2">
        	<a class="btn btn-dark btn-lg" href="<?php echo build_public_url("register","steward","default","default",$sef,$base_url); ?>"><?php echo $label_steward_reg; ?></a>
        </div>
    </div>
    <?php } ?>
</div>
<?php } else { // THIS ELSE ENDS at the end of the script ?>

<!-- Begin the Form -->
	<form id="submit-form" role="form" class="form-horizontal needs-validation hide-loader-form-submit" action="<?php echo $base_url; ?>includes/process.inc.php?action=add&amp;dbTable=<?php echo $users_db_table; ?>&amp;section=register&amp;go=<?php echo $go; if ($section == "admin") echo "&amp;filter=admin"; echo "&amp;view=".$view; ?>" method="POST" name="register_form" novalidate>
	<!-- Hidden Form Elements -->
	<input type="hidden" name="user_session_token" value ="<?php if (isset($_SESSION['user_session_token'])) echo $_SESSION['user_session_token']; ?>">
	<input type="hidden" name="userLevel" value="2" />
	<?php if ($judge_hidden) { ?>
	<input type="hidden" name="brewerJudge" value="N" />
	<?php } ?>
	<?php if ($steward_hidden) { ?>
	<input type="hidden" name="brewerSteward" value="N" />
	<?php } ?>
	<?php if ($section == "admin") { ?>
    <div class="bcoem-admin-element d-print-none">
        <!-- All Participants Button -->
        <div class="btn-group" role="group" aria-label="...">
            <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants"><span class="fa fa-arrow-circle-left"></span> <?php echo $label_all_participants; ?></a>
        </div><!-- ./button group -->
        <!-- All Participants Button -->
        <div class="btn-group" role="group" aria-label="...">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                 <span class="fa fa-plus-circle"></span> <?php echo $label_register_judge_standard; ?> <span class="caret"></span>
              </button>
              <ul class="dropdown-menu">
                <li <?php if (($view == "default") && ($go == "judge")) echo "class=\"disabled\""; ?>><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judge&amp;action=register"><?php echo $label_judge; ?></a></li>
                <li <?php if (($view == "default") && ($go == "steward")) echo "class=\"disabled\""; ?>><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=steward&amp;action=register"><?php echo $label_steward; ?></a></li>
              </ul>
        </div>
        <div class="btn-group" role="group" aria-label="...">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                 <span class="fa fa-plus-circle"></span> <?php echo $label_register_judge_quick; ?> <span class="caret"></span>
              </button>
              <ul class="dropdown-menu">
                <li <?php if (($view == "quick") && ($go == "judge")) echo "class=\"disabled\""; ?>><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judge&amp;action=register&amp;view=quick"><?php echo $label_judge; ?></a></li>
                <li <?php if (($view == "quick") && ($go == "steward")) echo "class=\"disabled\""; ?>><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=steward&amp;action=register&amp;view=quick"><?php echo $label_steward; ?></a></li>
              </ul>
        </div><!-- ./button group -->
    </div>
    <input type="hidden" name="password" value="bcoem">
    <input type="hidden" name="userQuestion" value="Randomly generated.">
    <input type="hidden" name="userQuestionAnswer" value="<?php echo random_generator(6,2); ?>">
    <input type="hidden" name="brewerJudgeWaiver" value="Y">
    <?php if ($view == "quick") { ?>
        <input type="hidden" name="brewerAddress" value="1234 Main Street">
        <input type="hidden" name="brewerCity" value="Anytown">
        <input type="hidden" name="brewerState" value="CO">
        <input type="hidden" name="brewerZip" value="80000">
        <input type="hidden" name="brewerCountry" value="United States">
        <input type="hidden" name="brewerPhone1" value="1234567890">
    <?php } // END if ($view == "quick")?>
	<?php } // END if ($section == "admin") ?>

	<?php if (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant")) { ?>
    <div class="mb-3 row">
        <label for="brewerBreweryName" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><i class="fa fa-star me-1"></i><strong><?php echo $label_organization." ".$label_name; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <input class="form-control" id="brewerBreweryName" name="brewerBreweryName" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerBreweryName']; ?>" data-error="<?php echo $register_text_044; ?>" placeholder="" data-error="<?php echo $brewer_text_032; ?>" required autofocus>
            <div class="help-block mb-1 invalid-feedback text-danger"></div>
            <div class="help-block"><?php echo $register_text_045; ?></div>
        </div>
    </div>
	<div class="mb-3 row">
	    <label for="brewerBreweryTTB" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_organization." ".$label_ttb; ?></strong></label>
	    <div class="col-xs-12 col-sm-9 col-lg-10">
	        <input class="form-control" id="brewerBreweryTTB" name="brewerBreweryTTB" type="text" value="<?php if ($action == "edit") echo $brewerBreweryTTB; ?>" placeholder="">
	    </div>
	</div>
    <?php if ($_SESSION['prefsStyleSet'] == "NWCiderCup") { ?>
    <div class="mb-3 row">
        <label for="brewerBreweryProd" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_organization." ".$label_yearly_volume; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="brewerBreweryProd" value="1 - 10,000" id="brewerBreweryProd_5" <?php if (($action == "edit") && (strpos($brewerBreweryProd, "1 - 10,000") !== false)) echo "CHECKED"; if (($action == "add") || (empty($brewerBreweryInfo))) echo "CHECKED"; ?> />
                <label>1 - 10,000 <?php echo $label_gallons; ?></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="brewerBreweryProd" value="10,001 - 250,000" id="brewerBreweryProd_6" <?php if (($action == "edit") && (strpos($brewerBreweryProd, "10,001 - 250,000") !== false)) echo "CHECKED"; ?> />
                <label>10,001 - 250,000 <?php echo $label_gallons; ?></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="brewerBreweryProd" value="250,001+" id="brewerBreweryProd_7" <?php if (($action == "edit") && (strpos($brewerBreweryProd, "250,001+") !== false)) echo "CHECKED"; ?> />
                <label>250,001+ <?php echo $label_gallons; ?></label>
            </div>
            <div>
                <input class="display-d" type="radio" name="brewerBreweryProd" value="" style="display: none;">
                <div class="help-block invalid-feedback text-danger"></div>
            </div>
        </div>
    </div>
    <input type="hidden" name="brewerBreweryProdMeas" value="<?php echo $label_gallons; ?>">
    <?php } else { ?>
    <div class="mb-3 row">
        <label id="brewery-prod-label" for="brewerBreweryProd" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><i id="brewery-prod-label-icon" class="fa fa-star me-1"></i> <strong><?php echo $label_organization." ".$label_yearly_volume; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="brewerBreweryProd" value="1 - 5,000" id="brewerBreweryProd_0" <?php if (($action == "edit") && (strpos($brewerBreweryProd, "1 - 5,000") !== false)) echo "CHECKED"; ?> />
                <label>1 - 5,000</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="brewerBreweryProd" value="5,001 - 15,000" id="brewerBreweryProd_1" <?php if (($action == "edit") && (strpos($brewerBreweryProd, "5,001 - 15,000") !== false)) echo "CHECKED"; ?> />
                <label>5,001 - 15,000</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="brewerBreweryProd" value="15,001 - 60,000" id="brewerBreweryProd_2" <?php if (($action == "edit") && (strpos($brewerBreweryProd, "15,001 - 60,000") !== false)) echo "CHECKED"; ?> />
                <label>15,001 - 60,000</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="brewerBreweryProd" value="60,001 - 599,999" id="brewerBreweryProd_3" <?php if (($action == "edit") && (strpos($brewerBreweryProd, "60,001 - 599,999") !== false)) echo "CHECKED"; ?> />
                <label>60,001 - 599,999</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="brewerBreweryProd" value="6,000,000+" id="brewerBreweryProd_4" <?php if (($action == "edit") && (strpos($brewerBreweryProd, "6,000,000+") !== false)) echo "CHECKED"; ?> />
                <label>6,000,000+</label>
            </div>
            <div>
                <input class="display-d" type="radio" name="brewerBreweryProd" value="" style="display: none;">
                <div class="help-block invalid-feedback text-danger"></div>
            </div>

            <div id="brewerBreweryProdMeas" class="mt-2">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="brewerBreweryProdMeas" value="<?php echo $label_barrels; ?>" id="brewerBreweryProdMeas_0" <?php if (($action == "edit") && (strpos($brewerBreweryProd, $label_barrels) !== false)) echo "CHECKED"; if ($action == "add") echo "CHECKED"; ?> />
                    <label class="form-check-label"><?php echo $label_barrels; ?></label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="brewerBreweryProdMeas" value="<?php echo $label_hectoliters; ?>" id="brewerBreweryProdMeas_1" <?php if (($action == "edit") && (strpos($brewerBreweryProd, $label_hectoliters) !== false)) echo "CHECKED"; ?> />
                    <label class="form-check-label"><?php echo $label_hectoliters; ?></label>
                </div>
            </div>
            <div>
                <input class="display-d" type="radio" name="brewerBreweryProdMeas" value="" style="display: none;">
                <div class="help-block invalid-feedback text-danger"></div>
            </div>

        </div>
    </div>
    <?php } ?>
	<?php } // END if (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant")) ?>
	

	<div class="row mb-3">
        <label for="user_name" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><i class="fa fa-star me-1"></i><strong><?php echo $label_email; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <input class="form-control" id="user_name" name="user_name" type="email" onBlur="checkAvailability()" onchange="AjaxFunction(this.value);" placeholder="" value="<?php if (($msg != "default") && (isset($_COOKIE['user_name']))) echo $_COOKIE['user_name']; ?>" <?php if ($_SESSION['prefsProEdition'] == 0) echo "autofocus"; ?> required>
            <div class="help-block invalid-feedback text-danger"><?php echo $register_text_019; ?></div>
            <div id="msg_email" class="mt-2"></div>
			<div id="username-status" class="mt-2"></div>
        </div>
    </div>
    <?php if ($view == "default") { // Show if not using quick add judge/steward feature ?>
	<div class="row mb-3">
        <label for="password" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><i class="fa fa-star me-1"></i><strong><?php echo $label_password; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <input class="form-control" name="password" id="password-entry" type="password" placeholder="<?php echo $label_password; ?>" value="" data-error="<?php echo $register_text_022; ?>" required>
            <div class="help-block invalid-feedback text-danger"><?php echo $register_text_022; ?></div>
        </div>
    </div>
    <div class="row mb-3" id="pwd-container">
		<label class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_password_strength; ?></strong></label>
		<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
			<div class="pwd-strength-viewport-progress"></div>
			<div id="length-help-text" class="small"></div>
		</div>
	</div>

    <div class="row mb-3">
        <label for="password-confirm" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><strong><i class="fa fa-star me-2"></i><?php echo $label_confirm_password; ?></strong></label>
        <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
            <input class="form-control password-field" name="password-confirm" id="password-confirm" type="password" required>
            <div class="help-block mt-1 invalid-feedback text-danger"><?php echo $login_text_024; ?></div>
            <div id="password-error" class="help-block mt-1 text-danger"><?php echo $login_text_023; ?></div>
        </div>
    </div>
	<?php } // END if ($view == "default") ?>
   
   	<?php if ($section != "admin") { // Show only when NOT being added by an administrator ?>


	<div class="mb-3 row">
	    <label for="security" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><i class="fa fa-star me-1"></i><strong><?php if (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant")) echo $label_contact." "; echo $label_security_question; ?></strong></label>
	    <div class="col-xs-12 col-sm-9 col-lg-10">
	        <?php echo $security; ?>
	        <div class="help-block"><?php echo $register_text_018; ?></div>
	        <div class="help-block mb-1 invalid-feedback text-danger"></div>
	    </div>
	</div>
	<div>
	    <input class="display-d" type="radio" name="userQuestion" value="" style="display: none;">
	    <div class="help-block invalid-feedback text-danger"></div>
	</div>

	<div class="mb-3 row">
	    <label for="userQuestionAnswer" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><i class="fa fa-star me-1"></i><strong><?php if (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant")) echo $label_contact." "; echo $label_security_answer; ?></strong></label>
	    <div class="col-xs-12 col-sm-9 col-lg-10">
	        <input class="form-control" name="userQuestionAnswer" id="userQuestionAnswer" type="text" placeholder="" value="<?php if (($msg != "default") && (isset($_COOKIE['userQuestionAnswer']))) echo $_COOKIE['userQuestionAnswer']; ?>" required>
	        <div class="help-block"><?php echo $register_text_024; ?></div>
	        <div class="help-block mb-1 invalid-feedback text-danger"><?php echo $register_text_023; ?></div>
	    </div>
	</div>

	<?php } // end if ($section != "admin") ?>

  	<!-- Name -->
	<div class="mb-3 row">
	    <label for="brewerFirstName" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><strong><i class="fa fa-star me-1"></i> <?php if (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant")) echo $label_contact." "; echo $label_first_name; ?></strong></label>
	    <div class="col-xs-12 col-sm-9 col-lg-10">
	        <input class="form-control" name="brewerFirstName" id="brewerFirstName" type="text" placeholder="" value="<?php if (($msg != "default") && (isset($_COOKIE['brewerFirstName']))) echo $_COOKIE['brewerFirstName']; ?>" required>
	        <div class="help-block mb-1 invalid-feedback text-danger"><?php echo $register_text_025; ?></div>
	    </div>
	</div>
	<div class="mb-3 row">
	    <label for="brewerLastName" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><strong><i class="fa fa-star me-1"></i> <?php if (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant")) echo $label_contact." "; echo $label_last_name; ?></strong></label>
	    <div class="col-xs-12 col-sm-9 col-lg-10">
	        <input class="form-control" name="brewerLastName" id="brewerLastName" type="text" placeholder="" value="<?php if (($msg != "default") && (isset($_COOKIE['brewerFirstName']))) echo $_COOKIE['brewerLastName']; ?>" data-error="" required>
	        <div class="help-block"><?php if ($_SESSION['prefsProEdition'] == 0) echo $brewer_text_000; ?></div>
	        <div class="help-block mb-1 invalid-feedback text-danger"><?php echo $register_text_026; ?></div>
	    </div>
	</div>

    <?php if ($view == "default") { ?>

	<!-- Country of Residence -->
	<div class="mb-3 row">
	    <label for="brewerCountry" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><i class="fa fa-star me-1"></i><strong><?php if (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant")) echo $label_organization." "; echo $label_country; ?></strong></label>
	    <div class="col-xs-12 col-sm-9 col-lg-10">
	            <select class="form-select mb-1 bootstrap-select" name="brewerCountry" id="brewerCountry" placeholder="<?php echo $label_select_country; ?>" title="<?php echo $label_select_country; ?>" required>
	            <option value=""><?php echo $label_select_country; ?></option>
	            <?php echo $country_select; ?>
	            </select>
	        <div class="help-block mb-1 invalid-feedback text-danger"><?php echo $brewer_text_031; ?></div>
	    </div>
	</div>

	<!-- General Entry Fields: Address, Phone, Dropoff Locations, Club, AHA -->
	<section id="address-fields">

	    <!-- Address -->
	    <div class="mb-3 row">
	        <label for="brewerAddress" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><i class="fa fa-star me-1"></i><strong><?php if (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant")) echo $label_organization." "; echo $label_street_address; ?></strong></label>
	        <div class="col-xs-12 col-sm-9 col-lg-10">
	            <input class="form-control" name="brewerAddress" id="brewerAddress" type="text" placeholder="" value="<?php if (($msg != "default") && (isset($_COOKIE['brewerAddress']))) echo $_COOKIE['brewerAddress']; ?>" required>
	            <div class="help-block mb-1 invalid-feedback text-danger"><?php echo $register_text_028; ?></div>
	        </div>
	    </div>

		<!-- City -->
		<div class="mb-3 row">
		    <label for="brewerCity" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><i class="fa fa-star me-1"></i><strong><?php if (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant")) echo $label_organization." "; echo $label_city; ?></strong></label>
		    <div class="col-xs-12 col-sm-9 col-lg-10">
		        <input class="form-control" name="brewerCity" id="brewerCity" type="text" placeholder="" value="<?php if (($msg != "default") && (isset($_COOKIE['brewerCity']))) echo $_COOKIE['brewerCity']; ?>" required>
		        <div class="help-block mb-1 invalid-feedback text-danger"><?php echo $register_text_029; ?></div>
		    </div>
		</div>

		<div class="mb-3 row">
			<label for="brewerState" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><i class="fa fa-star me-1"></i><strong><?php if (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant")) echo $label_organization." "; echo $label_state_province; ?></strong></label>
			<div class="col-xs-12 col-sm-9 col-lg-10">
				<div id="non-us-state">
				    <input class="form-control" name="brewerStateNon" id="brewerStateNon" type="text" placeholder="" value="<?php if (($msg != "default") && (isset($_COOKIE['brewerState']))) echo $_COOKIE['brewerState']; ?>" title="<?php echo $label_select_state; ?>" required>
				    <div class="help-block mb-1 invalid-feedback text-danger"><?php echo $register_text_030; ?></div>
				</div>
		    	<div id="us-state">
		    	    <select class="form-select mb-1 bootstrap-select" name="brewerStateUS" id="brewerStateUS" placeholder="<?php echo $label_select_state; ?>" title="<?php echo $label_select_state; ?>">
		    	        <option value=""><?php echo $label_select_state; ?></option>
		    	        <?php echo $us_state_select; ?>
		    	    </select>
		    	    <div class="help-block mb-1 invalid-feedback text-danger"><?php echo $register_text_030; ?></div>
		    	</div>
		    	<div id="aus-state">
		    	    <select class="form-select mb-1 bootstrap-select" name="brewerStateAUS" id="brewerStateAUS" placeholder="<?php echo $label_select_state; ?>" title="<?php echo $label_select_state; ?>">
		    	        <option value=""><?php echo $label_select_state; ?></option>
		    	        <?php echo $aus_state_select; ?>
		    	    </select>
		    	    <div class="help-block mb-1 invalid-feedback text-danger"><?php echo $register_text_030; ?></div>
		    	</div>
	            <div id="ca-state">
	                <select class="form-select mb-1 bootstrap-select" name="brewerStateCA" id="brewerStateCA" placeholder="<?php echo $label_select_state; ?>" title="<?php echo $label_select_state; ?>" data-error="<?php echo $register_text_030; ?>">
	                    <option value=""><?php echo $label_select_state; ?></option>
	                    <?php echo $ca_state_select; ?>
	                </select>
	                <div class="help-block mb-1 invalid-feedback text-danger"><?php echo $register_text_030; ?></div>
	            </div> 
			</div>
		</div>

		<!-- Zip/Postal Code -->
		<div class="mb-3 row">
		    <label for="brewerZip" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><i class="fa fa-star me-1"></i><strong><?php if (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant")) echo $label_organization." "; echo $label_zip; ?></strong></label>
		    <div class="col-xs-12 col-sm-9 col-lg-10">
		            <input class="form-control" name="brewerZip" id="brewerZip" type="text" placeholder="" value="<?php if (($msg != "default") && (isset($_COOKIE['brewerZip']))) echo $_COOKIE['brewerZip']; ?>" required>
		        <div class="help-block mb-1 invalid-feedback text-danger"><?php echo $register_text_031; ?></div>
		    </div>
		</div>

	</section>

	<!-- Phone Number -->
	<div class="mb-3 row">
	    <label for="brewerPhone1" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><i class="fa fa-star me-1"></i><strong><?php if (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant")) echo $label_contact." "; echo $label_phone_primary; ?></strong></label>
	    <div class="col-xs-12 col-sm-9 col-lg-10">
	        <input class="form-control" name="brewerPhone1" id="brewerPhone1" type="tel" placeholder="" value="<?php if (($msg != "default") && (isset($_COOKIE['brewerPhone1']))) echo $_COOKIE['brewerPhone1']; ?>" required>
	        <div class="help-block mb-1 invalid-feedback text-danger"><?php echo $register_text_032; ?></div>
	    </div>
	</div>

	<!--
	<div class="mb-3 row">
	    <label for="brewerPhone2" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php if (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant"))  echo $label_contact." "; echo $label_phone_secondary; ?></strong></label>
	    <div class="col-xs-12 col-sm-9 col-lg-10">
	        <input class="form-control" name="brewerPhone2" id="brewerPhone2" type="tel" placeholder="" value="<?php if (($msg != "default") && (isset($_COOKIE['brewerPhone2']))) echo $_COOKIE['brewerPhone2']; ?>">
	    </div>
	</div>
	-->

    <?php if (($_SESSION['prefsProEdition'] == 0) || (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant"))) { ?>
	<div class="mb-3 row">
	    <label for="brewerDropOff" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><i class="fa fa-star me-1"></i><strong><?php echo $label_drop_off; ?></strong></label>
	    <div class="col-xs-12 col-sm-9 col-lg-10">
	        <select class="form-select selectpicker mb-1 bootstrap-select" name="brewerDropOff" id="brewerDropOff" placeholder="<?php echo $label_select_dropoff; ?>" title="<?php echo $label_select_dropoff; ?>" required>
	        <option value=""><?php echo $label_select_dropoff; ?></option>
	        <?php if (!empty($_SESSION['contestShippingAddress'])) { ?>
	            <option value="0" <?php if (($section == "step2") || (($action == "edit") && (($row_brewer['brewerDropOff'] == "0") || (empty($row_brewer['brewerDropOff']))))) echo "SELECTED"; ?>><?php echo $brewer_text_048; ?></option>
	        <?php } ?>
	        <option value="999" <?php if (($section == "step2") || (($action == "edit") && ($row_brewer['brewerDropOff'] == "999"))) echo "SELECTED"; ?>><?php echo $brewer_text_005; ?></option>
	        <?php if (!empty($dropoff_select)) { ?>
	            <optgroup label="<?php echo $label_drop_offs; ?>">
	            <?php echo $dropoff_select; ?>
	            </optgroup>
	        <?php } ?>
	        </select>
	        <?php if (!empty($_SESSION['contestShippingAddress'])) { ?>
	        <div class="help-block"><?php echo $brewer_text_050; ?></div>
	        <?php } ?>
	        <div class="help-block"><?php echo $brewer_text_049; ?></div>
	    </div>
	</div>
    <?php } // END if (($_SESSION['prefsProEdition'] == 0) || (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant"))) ?>
    
    <?php if ($_SESSION['prefsProEdition'] == 0) { ?>


    <div class="mb-3 row">
        <label for="brewerClubs" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_club; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
        
        <select class="form-select mb-1 bootstrap-select" name="brewerClubs" id="brewerClubs" placeholder="<?php echo $label_select_club; ?>" title="<?php echo $label_select_club; ?>">
            <option value="0" <?php if (($action == "edit") && (empty($row_brewer['brewerClubs']))) echo "SELECTED"; ?>>None</option>
            <option value="Other" <?php if ($club_other) echo "SELECTED"; ?>>Other</option>
            <optgroup label="<?php echo $label_select_club; ?>"></optgroup>
            <?php echo $club_options; ?>
        </select>
        <span class="help-block"><?php echo $brewer_text_023; ?></span>
        </div>
    </div>

    <div id="brewerClubsOther" class="mb-3 row">
        <label for="brewerClubsOther" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_club_enter; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <input class="form-control" name="brewerClubsOther" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerClubs']; ?>" placeholder="" pattern="[^%&\x22\x27]+">
            <div class="help-block">
                <p><?php echo $brewer_text_046; ?></p>
            </div>
        </div>
    </div>

	<section id="proAm">
	    <?php if ($_SESSION['prefsProEdition'] == 0) { ?>
	    <div class="mb-3 row">
	        <label for="" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_pro_am; ?></strong></label>
	        <div class="col-xs-12 col-sm-9 col-lg-10">
	            <div class="form-check form-check-inline">
	                <input class="form-check-input" type="radio" name="brewerProAm" value="1" id="brewerProAm_1" <?php if (($msg != "default") && (isset($_COOKIE['brewerProAm'])) && ($_COOKIE['brewerProAm'] == "1")) echo "CHECKED";  ?> />
	                <label class="form-check-label"><?php echo $label_yes; ?></label>
	            </div>    
	            <div class="form-check form-check-inline">
	                <input class="form-check-input" type="radio" name="brewerProAm" value="0" id="brewerProAm_0" <?php if (($msg != "default") && (isset($_COOKIE['brewerProAm'])) && ($_COOKIE['brewerProAm'] == "0")) echo "CHECKED";  if ($msg == "default") echo "CHECKED";  ?> />
	                <label class="form-check-label"><?php echo $label_no; ?></label>
	            </div>
	            <div class="form-check form-check-inline">
	                <input class="form-check-input" type="radio" name="brewerProAm" value="2" id="brewerProAm_2" <?php if (($msg != "default") && (isset($_COOKIE['brewerProAm'])) && ($_COOKIE['brewerProAm'] == "2")) echo "CHECKED"; ?> />
	                <label class="form-check-label"><?php echo $label_opt_out; ?></label>
	            </div>
	            <div class="help-block">
	                <p><?php echo $brewer_text_041; ?></p>
	                <p><?php echo $brewer_text_043; ?></p>
	                <p><?php echo $brewer_text_042; ?></p>
	                <p><?php echo $brewer_text_056; ?></p>
	            </div>
	        </div>
	    </div>
	    <?php } else { ?>
	    <input type="hidden" name="brewerProAm" value="0">
	    <?php } ?>
	</section>

	<section id="aha-number">
	    <div class="mb-3 row">
	        <label for="brewerAHA" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_aha_number; ?></strong></label>
	        <div class="col-xs-12 col-sm-9 col-lg-10">
	            <input class="form-control" name="brewerAHA" id="brewerAHA" type="text" pattern="[A-Za-z0-9]+" placeholder="" data-error="<?php echo $brew_text_019; ?>" value="<?php if (($msg != "default") && (isset($_COOKIE['brewerAHA']))) echo $_COOKIE['brewerAHA']; ?>">
	            <div id="ahaProAmText" class="help-block"><?php echo $register_text_033; ?></div>
	        </div>
	    </div>
	</section>
	<?php if ($_SESSION['prefsMHPDisplay'] == 1) { ?>
	<section id="mhp-number">
	    <div class="mb-3 row">
	        <label for="brewerMHP" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_mhp_number; ?></strong></label>
	        <div class="col-xs-12 col-sm-9 col-lg-10">
	            <input class="form-control" name="brewerMHP" id="brewerMHP" type="text" pattern="\d*" placeholder="" data-error="<?php echo $brew_text_019; ?>" value="<?php if (($msg != "default") && (isset($_COOKIE['brewerMHP']))) echo $_COOKIE['brewerMHP']; ?>" placeholder="">
	            <div class="help-block"><?php echo $brewer_text_053; ?></div>
	        </div>
	    </div>
	</section>
	<?php } ?>
    <?php } // END if (($_SESSION['prefsProEdition'] == 0) ?>
    <?php } // END if ($view == "default") ?>
    <?php if (($_SESSION['prefsProEdition'] == 0) || (($_SESSION['prefsProEdition'] == 1) && (($go == "judge") || ($go == "steward")))) { ?>

    <!-- Staff preferences -->

    <div class="mb-3 row">
        <label for="brewerStaff" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_staff; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="brewerStaff" value="Y" id="brewerStaff_0" <?php if (($msg != "default") && (isset($_COOKIE['brewerStaff'])) && ($_COOKIE['brewerStaff'] == "Y")) echo "CHECKED"; ?>>
                <label class="form-check-label"><?php echo $label_yes; ?></label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="brewerStaff" value="N" id="brewerStaff_1" <?php if (($msg != "default") && (isset($_COOKIE['brewerStaff'])) && ($_COOKIE['brewerStaff'] == "N")) echo "CHECKED"; if ($msg == "default") echo "CHECKED"; ?>>
                <label class="form-check-label"><?php echo $label_no; ?></label>
            </div>
            <div class="help-block"><?php echo "<p>".$brewer_text_021."</p>"; if (!empty($staff_location_avail)) echo "<p id=\"staff-help\" class=\"alert alert-teal fst-normall\">".$brewer_text_047."</p>";  ?></div>
        </div>
    </div>
    <?php if (!empty($staff_location_avail)) { ?>
    <div id="brewerStaffFields">
        <div class="mb-3 row">
            <label for="brewerStaffLocation" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_staff_availability; ?></strong></label>
            <div class="col-xs-12 col-sm-9 col-lg-10">
            <?php echo $staff_location_avail; ?>
            </div>
        </div>
    </div>
    <?php } // end if (!empty($staff_location_avail)) ?>
    <?php } // END if (($_SESSION['prefsProEdition'] == 0) || (($_SESSION['prefsProEdition'] == 1) && (($go == "judge") || ($go == "steward"))))?>


    <?php if (!$judge_hidden) {
        $judge_checked_yes = FALSE;
        $judge_checked_no = FALSE;
        $judge_disabled = FALSE;
        if (($msg != "default") && (isset($_COOKIE['brewerJudge']))) {
            if ($_COOKIE['brewerJudge'] == "Y") $judge_checked_yes = TRUE;
            if ($_COOKIE['brewerJudge'] == "N") $judge_checked_no = TRUE;
        }
        if ($msg != "4") $judge_checked_yes = TRUE;
        if ($go == "steward") $judge_checked_no = TRUE;
        if ($go == "judge") $judge_disabled = TRUE;
    ?>
    
    <!-- Show Judge Fields if Registering as a Judge -->
    
    <div class="mb-3 row">
        <label for="brewerJudge" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><i class="fa fa-star me-1"></i><strong><?php echo $label_judging; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="brewerJudge" value="Y" id="brewerJudge_0" <?php if ($judge_checked_yes) echo "CHECKED"; ?> rel="judge_no"> 
                <label class="form-check-label"><?php echo $label_yes; ?></label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="brewerJudge" value="N" id="brewerJudge_1" <?php if ($judge_checked_no) echo "CHECKED"; if ($judge_disabled) echo "DISABLED"; ?> rel="none" > 
                <label class="form-check-label">
                    <?php echo $label_no; ?>
                </label>
            </div>
            <div class="help-block mt-1"><?php echo $brewer_text_006; ?></div>
        </div>
    </div>

    <?php if ($totalRows_judging > 0) {
	if ($action == "edit") $judging_locations = explode(",",$row_brewer['brewerJudgeLocation']);
	elseif ((isset($_COOKIE['brewerJudgeLocation'])) && ($section != "admin")) $judging_locations = explode(",",$_COOKIE['brewerJudgeLocation']);
	else $judging_locations = array("","");
	if (!empty($judge_location_avail)) { ?>

	<div class="mb-3 row">
	    <?php if (!empty($judge_location_avail)) { ?>
	    <label for="brewerJudgeLocation" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><i class="fa fa-star me-1"></i><strong><?php echo $label_judging_avail; ?></strong></label>
	    <div class="col-xs-12 col-sm-9 col-lg-10">
	    <?php echo $judge_location_avail; ?>
	    <div class="help-block mt-1"><?php echo $register_text_052; ?></div>
	    </div>
	    <?php } ?>
	</div>

	<?php } 
	} // END if if ($totalRows_judging > 1) 
    else { ?>
    <input name="brewerJudgeLocation" type="hidden" value="<?php echo "Y-".$row_judging3['id']; ?>" />
	<?php } ?>

    <div id="bjcp-id" class="mb-3 row">
        <label for="brewerJudgeID" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_bjcp_id; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <input class="form-control" id="brewerJudgeID" name="brewerJudgeID" type="text" value="<?php if (($msg != "default") && (isset($_COOKIE['brewerJudgeID']))) echo $_COOKIE['brewerJudgeID']; ?>" placeholder="">
        </div>
    </div>

    <div class="mb-3 row">
        <label for="brewerJudgeRank" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_bjcp_rank; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="brewerJudgeRank[]" value="Non-BJCP" checked>
                <label class="form-check-label">Non-BJCP *</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="brewerJudgeRank[]" value="Rank Pending">
                <label class="form-check-label">Rank Pending</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="brewerJudgeRank[]" value="Provisional">
                <label class="form-check-label">Provisional **</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="brewerJudgeRank[]" value="Recognized">
                <label class="form-check-label">Recognized</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="brewerJudgeRank[]" value="Certified">
                <label class="form-check-label">Certified</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="brewerJudgeRank[]" value="National">
                <label class="form-check-label">National</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="brewerJudgeRank[]" value="Master">
                <label class="form-check-label">Master</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="brewerJudgeRank[]" value="Honorary Master">
                <label class="form-check-label">Honorary Master</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="brewerJudgeRank[]" value="Grand Master">
                <label class="form-check-label">Grand Master</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="brewerJudgeRank[]" value="Honorary Grand Master">
                <label class="form-check-label">Honorary Grand Master</label>
            </div>
            <div class="help-block mt-1">
                <p class="mt-1">
                    <?php echo $brewer_text_008; ?><br>
                    <?php echo $brewer_text_009; ?>
                </p>
            </div>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="brewerJudgeRank" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_designations; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="brewerJudgeRank[]" value="Judge with Sensory Training">
                <label class="form-check-label">Judge with Sensory Training</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="brewerJudgeRank[]" value="Professional Brewer">
                <label class="form-check-label">Professional Brewer</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="brewerJudgeRank[]" value="Professional Mead Maker">
                <label class="form-check-label">Professional Mead Maker</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="brewerJudgeRank[]" value="Professional Cider Maker">
                <label class="form-check-label">Professional Cider Maker</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="brewerJudgeRank[]" value="Certified Cicerone">
                <label class="form-check-label">Certified Cicerone</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="brewerJudgeRank[]" value="Advanced Cicerone">
                <label class="form-check-label">Advanced Cicerone</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="brewerJudgeRank[]" value="Master Cicerone">
                <label class="form-check-label">Master Cicerone</label>
            </div>
            <div class="help-block mt-1"><?php echo $brewer_text_010; ?></div>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="brewerJudgeMead" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_bjcp_mead; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="brewerJudgeMead" value="Y" id="brewerJudgeMead_0"> 
                <label class="form-check-label"><?php echo $label_yes; ?></label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="brewerJudgeMead" value="N" id="brewerJudgeMead_1" checked> 
                <label class="form-check-label">
                    <?php echo $label_no; ?>
                </label>
            </div>
            <div class="help-block mt-1"><?php echo $brewer_text_007; ?></div> 
        </div>
    </div>

    <div class="mb-3 row">
        <label for="brewerJudgeCider" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_bjcp_cider; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="brewerJudgeCider" value="Y" id="brewerJudgeCider_0" > 
                <label class="form-check-label"><?php echo $label_yes; ?></label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="brewerJudgeCider" value="N" id="brewerJudgeCider_1" checked> 
                <label class="form-check-label">
                    <?php echo $label_no; ?>
                </label>
            </div>
            <div class="help-block mt-1"><?php echo $brewer_text_035; ?></div>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="brewerJudgeExp" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_judge_comps; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
        <select class="form-select bootstrap-select mb-1" name="brewerJudgeExp" id="brewerJudgeExp" required>
            <option value="0">0</option>
            <option value="1-5">1-5</option>
            <option value="6-10">6-10</option>
            <option value="10+">10+</option>
        </select>
        <div class="help-block mt-1"><?php echo $brewer_text_011; ?></div>
        </div>
    </div>


    <div class="mb-3 row">
        <label for="brewerJudgeLikes" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_judge_preferred; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-md-6 d-grid">
            <button class="btn btn-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePref" aria-expanded="false" aria-controls="collapsePref"><?php echo $label_judge_preferred; ?></button>
            <div class="help-block mt-1"><?php echo $brewer_text_017; ?></div>
        </div>
    </div>

    <div class="collapse" id="collapsePref">
        <div class="mb-3 row">
            <label for="brewerJudgeLikes" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"></label>
            <div class="col-xs-12 col-sm-9 col-lg-10">
            <p class="mb-1 small text-danger"><strong><?php echo $brewer_text_012; ?></strong></p>
                <?php do {
                    $style_display = "";
                    if ($_SESSION['prefsStyleSet'] == "BA") {
                        if ($row_styles['brewStyleOwn'] == "bcoe") $style_display .= $row_styles['brewStyleCategory'].": ".$row_styles['brewStyle'];
                        elseif ($row_styles['brewStyleOwn'] == "custom") $style_display .= "Custom: ".$row_styles['brewStyle'];
                        else $style_display .= $row_styles['brewStyle'];
                    }
                    else $style_display .= ltrim($row_styles['brewStyleGroup'], "0").$row_styles['brewStyleNum'].": ".$row_styles['brewStyle'];
                    ?>
                    <div class="form-check small">
                        <input class="form-check-input" name="brewerJudgeLikes[]" type="checkbox" value="<?php echo $row_styles['id']; ?>" <?php if (isset($row_brewer['brewerJudgeLikes'])) { $a = explode(",", $row_brewer['brewerJudgeLikes']); $b = $row_styles['id']; foreach ($a as $value) { if ($value == $b) echo "CHECKED"; } } ?>>
                        <label class="form-check-label"><?php echo $style_display; ?></label>
                    </div>
                <?php } while ($row_styles = mysqli_fetch_assoc($styles)); ?>
            </div>
        </div>
    </div>

    <div class="mb-3 row">
    <label for="brewerJudgeDislikes" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_judge_non_preferred; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-md-6 d-grid">
            <button class="btn btn-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNonPref" aria-expanded="false" aria-controls="collapseNonPref"><?php echo $label_judge_non_preferred; ?></button>
            <span class="help-block mt-1"><?php echo $brewer_text_013; ?></span>
        </div>
    </div>

    <div class="collapse" id="collapseNonPref">
        <div class="mb-3 row">
            <label for="brewJudgeDislikes" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"></label>
            <div class="col-xs-12 col-sm-9 col-lg-10">
                <p class="mb-1 small text-danger"><strong><?php echo $brewer_text_014; ?></strong></p>
                <?php do {
                    $style_display = "";
                    if ($_SESSION['prefsStyleSet'] == "BA") {
                        if ($row_styles2['brewStyleOwn'] == "bcoe") $style_display .= $row_styles2['brewStyleCategory'].": ".$row_styles2['brewStyle'];
                        elseif ($row_styles2['brewStyleOwn'] == "custom") $style_display .= "Custom: ".$row_styles2['brewStyle'];
                        else $style_display .= $row_styles2['brewStyle'];
                    }
                    else $style_display .= ltrim($row_styles2['brewStyleGroup'], "0").$row_styles2['brewStyleNum'].": ".$row_styles2['brewStyle'];
                    ?>
                    <div class="form-check small">
                        <input class="form-check-input" name="brewerJudgeDislikes[]" type="checkbox" value="<?php echo $row_styles2['id']; ?>">
                        <label class="form-check-label"><?php echo $style_display; ?></label>
                    </div>
                <?php } while ($row_styles2 = mysqli_fetch_assoc($styles2)); ?>
                <!-- </div> -->
            </div>
        </div>
    </div>
    
    <?php } // END if (!$judge_hidden) ?>
    

    <?php if (!$steward_hidden) {
        $steward_checked_yes = FALSE;
        $steward_checked_no = FALSE;
        $steward_disabled = FALSE;
        if (($msg != "default") && (isset($_COOKIE['brewerSteward']))) {
            if ($_COOKIE['brewerSteward'] == "Y") $steward_checked_yes = TRUE;
            if ($_COOKIE['brewerSteward'] == "N") $steward_checked_no = TRUE;
        }
        if ($msg != "4") $steward_checked_yes = TRUE;
        if ($go == "judge") $steward_checked_no = TRUE;
        if ($go == "steward") $steward_disabled = TRUE;
    ?>
    
    <!-- Show Steward Fields if Registering as a Steward -->
    <div class="mb-3 row">
        <label for="brewerSteward" class="col-xs-12 col-sm-3 col-lg-2  col-form-label text-teal"><i class="fa fa-star me-1"></i><strong><?php echo $label_stewarding; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="brewerSteward" value="Y" id="brewerSteward_0" <?php if ($steward_checked_yes) echo "CHECKED"; ?> rel="steward_no" > 
                <label class="form-check-label"><?php echo $label_yes; ?></label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="brewerSteward" value="N" id="brewerSteward_1" <?php if ($steward_checked_no) echo "CHECKED"; if ($steward_disabled) echo "DISABLED"; ?> rel="none" > 
                <label class="form-check-label"><?php echo $label_no; ?></label>
            </div>
            <div class="help-block mt-1"><?php echo $brewer_text_015; ?></div>
        </div>
    </div>

	<?php if ($totalRows_judging > 1) {
	if ($action == "edit") $stewarding_locations = explode(",",$row_brewer['brewerStewardLocation']);
	elseif ((isset($_COOKIE['brewerStewardLocation'])) && ($section != "admin")) $stewarding_locations = explode(",",$_COOKIE['brewerStewardLocation']);
	else $stewarding_locations = array("","");
	?>
	<?php if (!empty($steward_location_avail)) { ?>

    <div class="mb-3 row">
        <label for="brewerStewardLocation" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><i class="fa fa-star me-1"></i><strong><?php echo $label_stewarding_avail; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
        <?php echo $steward_location_avail; ?>
        </div>
    </div>

	<?php } ?>
	<?php } // END if ($totalRows_judging > 1)
	else { ?>
   	<input name="brewerStewardLocation" type="hidden" value="<?php echo "Y-".$row_judging3['id']; ?>" />
   	<?php } // END else ?>
    <?php } // END if (!$steward_hidden) ?>

    <?php if (((!$judge_hidden) || (!$steward_hidden)) && ($section != "admin")) {
    include(DB.'organizations.db.php');
    $org_array_lower = array();
    foreach ($org_array as $value) {
        $org_array_lower[] = strtolower($value);
    }
    $org_array = implode(",",$org_array_lower);

    if ($_SESSION['prefsProEdition'] == 1) $participant_orgs_label = $label_industry_affiliations;
    else $participant_orgs_label = $label_brewing_partners;

    ?>    
    <section id="participant-orgs">
        <div class="mb-3 row">
            <label for="brewerAssignment" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $participant_orgs_label; ?></strong></label>
            <div class="col-lg-9 col-md-6 col-sm-8 col-xs-12">  
            <select class="form-select mb-1 bootstrap-select" multiple name="brewerAssignment[]" id="brewerAssignment" placeholder="<?php echo $participant_orgs_label." - ".$label_select_below; ?>" title="<?php echo $participant_orgs_label." - ".$label_select_below; ?>">
                <?php echo $org_options; ?>
            </select>
            <span class="help-block mt-1"><?php if ($_SESSION['prefsProEdition'] == 1) echo $brewer_text_051; else echo $brewer_text_055; ?></span>
            </div>
        </div>
        <input name="allOrgs" type="hidden" value="<?php echo $org_array; ?>">
        <div id="brewerAssignmentOther" class="mb-3 row">
            <label for="brewerAssignmentOther" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $participant_orgs_label." &ndash; ".$label_other; ?></strong></label>
            <div class="col-xs-12 col-sm-9 col-lg-10">
                <input class="form-control" name="brewerAssignmentOther" type="text" value="" placeholder="" pattern="[^%\x22]+">
                <div class="help-block mt-1">
                    <p><?php if ($_SESSION['prefsProEdition'] == 1) echo $brewer_text_052; else echo $brewer_text_054; ?></p>
                </div>
            </div>
        </div>
    </section>
    <!-- Show Waiver -->
    <section id="judge-steward-waiver">
        <div id="judge-waiver" class="mb-3 row">
            <label for="brewerJudgeWaiver" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><i class="fa fa-star me-1"></i><strong><?php echo $label_waiver; ?></strong></label>
            <div class="col-xs-12 col-sm-9 col-lg-10">
                <p><?php echo $brewer_text_016; ?></p>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="brewerJudgeWaiver" value="Y" id="brewerJudgeWaiver_0" checked required />
                    <label class="form-check-label"><?php echo $brewer_text_018; ?></label>
                </div>
                <div class="help-block invalid-feedback text-danger"><?php echo $register_text_034; ?></div>
            </div>
        </div>
    </section>
    <?php } // END if (((!$judge_hidden) || (!$steward_hidden)) && ($section != "admin")) ?>
    
    <?php if ($_SESSION['prefsCAPTCHA'] == "1") { ?>
    <!-- CAPTCHA -->
	<div class="row mb-3">
	    <label for="reg-captcha" class="col-sm-12 col-md-2 col-form-label text-teal"><i class="fa fa-sm fa-star pe-1"></i><strong><?php echo $login_text_007; ?></strong></label>
	    <div class="col-sm-12 col-md-10">
	        <div id="reg-captcha" class="<?php echo $captcha_widget_class; ?> mb-3" data-sitekey="<?php echo $public_captcha_key; ?>"></div>
	    </div>
	</div>
	<script src="<?php echo $captcha_url; ?>"></script>
    <?php } ?>
	<!-- Register Button -->
	<div class="row mb-3">
	    <label for="" class="col-sm-2 col-form-label"></label>
	    <div class="col-sm-12 col-md-10">
	        <div class="d-grid">
	            <button id="submit-button" name="submit" type="submit" class="btn btn-lg btn-primary"><?php echo $label_register; ?></button>
	        </div>
	    </div>
	</div>
</form>
<script type="text/javascript">
	$("#brewerStaffFields").hide();
	$("#staff-help").hide();
	
  	$(function () {
  		$('#user_screen_name').focus();
	});

	$('input[type="radio"]').click(function() {

	    if($(this).attr('id') == 'brewerStaff_0') {
	        $("#brewerStaffFields").show("slow");
	        $("#staff-help").show("slow");
	    }

	    if($(this).attr('id') == 'brewerStaff_1') {
	        $("#brewerStaffFields").hide("slow");
	        $("#staff-help").hide("slow");
	    }
	});
</script>
<?php } // end else ?>
<?php } // end else ?>