<?php
/**
 * Module:      brewer.sec.php
 * Description: This module houses the functionality for users to add/edit their personal
 *              information - references the "brewer" database table.
 *
 */

if ($section != "step2") {
	include (DB.'judging_locations.db.php');
	include (DB.'stewarding.db.php');
	include (DB.'styles.db.php');
}

include (DB.'brewer.db.php');
include (DB.'dropoff.db.php');

if ($section != "step2") {

    if (($row_brewer['brewerCountry'] == "United States")) $us_phone = TRUE; else $us_phone = FALSE;

    $phone1 = $row_brewer['brewerPhone1'];
    $phone2 = $row_brewer['brewerPhone2'];

    if ($us_phone) {
        $phone1 = format_phone_us($phone1);
        $phone2 = format_phone_us($phone2);
    }

}

// Get table assignments and build flags
$table_assign_judge = table_assignments($_SESSION['user_id'],"J",$_SESSION['prefsTimeZone'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'],0);
$table_assign_steward = table_assignments($_SESSION['user_id'],"S",$_SESSION['prefsTimeZone'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'],0);
if ((!empty($table_assign_judge)) || (!empty($table_assign_steward))) $table_assignment = TRUE;
if ((empty($table_assign_judge)) && (empty($table_assign_steward))) $table_assignment = FALSE;

$show_judge_steward_fields = TRUE;
$entrant_type_brewery = FALSE;

if ($_SESSION['prefsProEdition'] == 1) {

	// If registered as a brewery, will not be a judge
	// Only individuals can be judges, stewards, or staff
	if (($row_brewer['brewerJudge'] != "Y") && ($row_brewer['brewerSteward'] != "Y") && (isset($row_brewer['brewerBreweryName']))) {
		$label_contact = $label_contact;
		$label_organization = $label_organization;
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

// If BA, build likes/dislikes

if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false) {

	include (INCLUDES.'ba_constants.inc.php');

	$brewer_ba_likes = "";
	$brewer_ba_dislikes = "";

	$brewer_likes = explode(",",$row_brewer['brewerJudgeLikes']);
	$brewer_dislikes = explode(",",$row_brewer['brewerJudgeDislikes']);

	foreach ($_SESSION['styles'] as $ba_styles => $stylesData) {

		if (is_array($stylesData) || is_object($stylesData)) {

			foreach ($stylesData as $key => $ba_style) {

				// Likes
				$brewer_ba_likes_selected = "";
				if (in_array($ba_style['id'],$brewer_likes)) $brewer_ba_likes_selected = "CHECKED";

				$brewer_ba_likes .= "<div class=\"checkbox\">";
				$brewer_ba_likes .= "<label>";
				$brewer_ba_likes .= "<input name=\"brewerJudgeLikes[]\" type=\"checkbox\" value=\"".$ba_style['id']."\" ".$brewer_ba_likes_selected.">";
				$brewer_ba_likes .= $ba_style['name'];
				$brewer_ba_likes .= "</label>";
				$brewer_ba_likes .= "</div>";

				// Dislikes
				$brewer_ba_dislikes_selected = "";
				if (in_array($ba_style['id'],$brewer_dislikes)) $brewer_ba_dislikes_selected = "CHECKED";

				$brewer_ba_dislikes .= "<div class=\"checkbox\">";
				$brewer_ba_dislikes .= "<label>";
				$brewer_ba_dislikes .= "<input name=\"brewerJudgeDislikes[]\" type=\"checkbox\" value=\"".$ba_style['id']."\" ".$brewer_ba_dislikes_selected.">";
				$brewer_ba_dislikes .= $ba_style['name'];
				$brewer_ba_dislikes .= "</label>";
				$brewer_ba_dislikes .= "</div>";

			} // end foreach ($stylesData as $data => $ba_style)

		} // end if (is_array($stylesData) || is_object($stylesData))

	} // end foreach ($_SESSION['styles'] as $styles => $stylesData)

}

if (($_SESSION['prefsProEdition'] == 0) || (($_SESSION['prefsProEdition'] == 1) && ($show_judge_steward_fields))) {

	// Build Clubs dropdown
	$club_options = "";
    $club_other = FALSE;

	if ($section != "step2") {
        $club_concat = $row_brewer['brewerClubs']."|".$row_brewer['brewerClubs'];
        if  ((!empty($row_brewer['brewerClubs'])) && (!in_array($club_concat,$club_array))) $club_other = TRUE;
    }

	foreach ($club_array as $club) {
		$club_selected = "";
		$club_option = explode ("|",$club);
        if ($section != "step2") {
            if ($club_option[1] == $row_brewer['brewerClubs']) $club_selected = " SELECTED";
        }
		$club_options .= "<option value=\"".$club_option[0]."\"".$club_selected.">".$club_option[1]."</option>\n";
	}

}

$security_questions_display = (array_rand($security_question, 5));
$security = "";
if ($section != "step2") $security .= "<div class=\"radio\"><label><input type=\"radio\" name=\"userQuestion\" value=\"".$_SESSION['userQuestion']."\" CHECKED> ".$_SESSION['userQuestion']."</label></div>";
foreach ($security_questions_display as $key => $value) {
	if ($security_question[$value] != $_SESSION['userQuestion']) $security .= "<div class=\"radio\"><label><input type=\"radio\" name=\"userQuestion\" value=\"".$security_question[$value]."\"> ".$security_question[$value]."</label></div>";
}

if ($go != "admin") echo $info_msg;
?>

<script type='text/javascript'>//<![CDATA[
$(document).ready(function(){
	
	// hide divs on load if no value
	$("#brewerClubsOther").hide("fast");
	$("#brewerJudgeFields").hide("fast");
	$("#brewerStewardFields").hide("fast");

	<?php if (($action == "edit") && ($club_other)) { ?>
	$("#brewerClubsOther").show("slow");
	<?php } ?>

	$("#brewerClubs").change(function() {

		if ($("#brewerClubs").val() == "Other") {
			$("#brewerClubsOther").show("slow");
		}

		else  {
			$("#brewerClubsOther").hide("fast");
		}

	});

	$("#brewerJudge").change(function() {

		if ($("#brewerJudge").val() == "Y") {
			$("#brewerJudgeFields").show("slow");
		}

	});

	<?php if (($action == "edit") && ($row_brewer['brewerJudge'] == "Y")) { ?>
	$("#brewerJudgeFields").show("slow");
	<?php } ?>

	$('input[type="radio"]').click(function() {
		
		if($(this).attr('id') == 'brewerJudge_0') {
			$("#brewerJudgeFields").show("slow");
		}
		
		if($(this).attr('id') == 'brewerJudge_1') {
            $("#brewerJudgeFields").hide("fast");
       	}

   	});
	
	
	<?php if (($action == "edit") && ($row_brewer['brewerSteward'] == "Y")) { ?>
	$("#brewerStewardFields").show("slow");
	<?php } ?>
	
	$('input[type="radio"]').click(function() {
		
		if($(this).attr('id') == 'brewerSteward_0') {
			$("#brewerStewardFields").show("slow");
		}
		
		if($(this).attr('id') == 'brewerSteward_1') {
            $("#brewerStewardFields").hide("fast");
       	}
		
   	});

});
</script>

<form class="form-horizontal" action="<?php echo $form_action; ?>" method="POST" name="form1" id="form1">

<?php if (($_SESSION['prefsProEdition'] == 1) && (!$show_judge_steward_fields)) { ?>
    <div class="form-group"><!-- Form Group REQUIRED Text Input -->
        <label for="brewerBreweryName" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_organization." ".$label_name; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <div class="input-group has-warning">
                <span class="input-group-addon" id="brewerBreweryName-addon1"><span class="fa fa-beer"></span></span>
                <!-- Input Here -->
                <input class="form-control" id="brewerBreweryName" name="brewerBreweryName" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerBreweryName']; ?>" data-error="<?php echo $register_text_044; ?>" placeholder="" required autofocus>
                <span class="input-group-addon" id="brewerBreweryName-addon2"><span class="fa fa-star"></span></span>
            </div>
            <div class="help-block"><?php echo $register_text_045; ?></div>
            <div class="help-block with-errors"></div>
        </div>
    </div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group Text Input -->
        <label for="brewerBreweryTTB" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_ttb; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
        	<!-- Input Here -->
       		<input class="form-control" id="brewerBreweryTTB" name="brewerBreweryTTB" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerBreweryTTB']; ?>" placeholder="">
        </div>
    </div><!-- ./Form Group -->
<?php } ?>
<?php if ($section == "step2") { ?>
<input type="hidden" name="brewerBreweryName" value="">
<?php } ?>
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
        <label for="brewerFirstName" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php if ($_SESSION['prefsProEdition'] == 1) echo $label_contact." "; echo $label_first_name; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <div class="input-group has-warning">
                <span class="input-group-addon" id="brewerFirstName-addon1"><span class="fa fa-user"></span></span>
                <!-- Input Here -->
                <input class="form-control" id="brewerFirstName" name="brewerFirstName" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerFirstName']; ?>" placeholder="" <?php if (($_SESSION['prefsProEdition'] == 0) && ($psort == "default")) echo "autofocus"; ?>>
                <span class="input-group-addon" id="brewerFirstName-addon2"><span class="fa fa-star"></span></span>
            </div>
        </div>
    </div><!-- ./Form Group -->
    <div class="form-group"><!-- Form Group REQUIRED Text Input -->
        <label for="brewerLastName" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php if ($_SESSION['prefsProEdition'] == 1) echo $label_contact." "; echo $label_last_name; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <div class="input-group has-warning">
                <span class="input-group-addon" id="brewerLastName-addon1"><span class="fa fa-user"></span></span>
                <!-- Input Here -->
                <input class="form-control" id="brewerLastName" name="brewerLastName" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerLastName']; ?>" placeholder="">
                <span class="input-group-addon" id="brewerLastName-addon2"><span class="fa fa-star"></span></span>
            </div>
            <span class="help-block"><?php if ($_SESSION['prefsProEdition'] == 0) echo $brewer_text_000; ?></span>
        </div>
    </div><!-- ./Form Group -->
     <?php if (($go != "admin") && ($section != "step2")) { ?>
    <div class="form-group"><!-- Form Group REQUIRED Radio Group -->
		<label for="" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_security_question; ?></label>
		<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
			<div class="input-group">
            	<?php echo $security; ?>
            </div>
            <span class="help-block"><?php echo $brewer_text_001; ?></span>
		</div>
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_security_answer; ?></label>
		<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="security-question-answer-addon1"><span class="fa fa-bullhorn"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="userQuestionAnswer" id="userQuestionAnswer" type="text" placeholder="" value="<?php if ($action == "edit") echo $_SESSION['userQuestionAnswer']; ?>" required>
				<span class="input-group-addon" id="security-question-answer-addon2"><span class="fa fa-star"></span></span>
			</div>
            <div class="help-block with-errors"></div>
		</div>
	</div><!-- ./Form Group -->
    <?php } 	?>

    <div class="form-group"><!-- Form Group REQUIRED Text Input -->
        <label for="brewerPhone1" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php if ($_SESSION['prefsProEdition'] == 1) echo $label_contact." "; echo $label_phone_primary; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <div class="input-group has-warning">
                <span class="input-group-addon" id="brewerPhone1-addon1"><span class="fa fa-phone"></span></span>
                <!-- Input Here -->
                <input class="form-control" id="brewerPhone1" name="brewerPhone1" type="text" value="<?php if ($action == "edit") echo $phone1; ?>" placeholder="">
                <span class="input-group-addon" id="brewerPhone1-addon2"><span class="fa fa-star"></span></span>
            </div>
        </div>
    </div><!-- ./Form Group -->

	<div class="form-group"><!-- Form Group Text Input -->
        <label for="brewerPhone2" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php if ($_SESSION['prefsProEdition'] == 1) echo $label_contact." "; echo $label_phone_secondary; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
        	<!-- Input Here -->
       		<input class="form-control" id="brewerPhone2" name="brewerPhone2" type="text" value="<?php if ($action == "edit") echo $phone2; ?>" placeholder="">
        </div>
    </div><!-- ./Form Group -->

	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
        <label for="brewerAddress" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php if ($_SESSION['prefsProEdition'] == 1) echo $label_organization." "; echo $label_street_address; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <div class="input-group has-warning">
                <span class="input-group-addon" id="brewerAddress-addon1"><span class="fa fa-home"></span></span>
                <!-- Input Here -->
                <input class="form-control" id="brewerAddress" name="brewerAddress" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerAddress']; ?>" placeholder="">
                <span class="input-group-addon" id="brewerAddress-addon2"><span class="fa fa-star"></span></span>
            </div>
        </div>
    </div><!-- ./Form Group -->

	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
        <label for="brewerCity" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php if ($_SESSION['prefsProEdition'] == 1) echo $label_organization." "; echo $label_city; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <div class="input-group has-warning">
                <!-- Input Here -->
                <input class="form-control" id="brewerCity" name="brewerCity" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerCity']; ?>" placeholder="">
                <span class="input-group-addon" id="brewerCity-addon2"><span class="fa fa-star"></span></span>
            </div>
        </div>
    </div><!-- ./Form Group -->

	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
        <label for="brewerState" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php if ($_SESSION['prefsProEdition'] == 1) echo $label_organization." "; echo $label_state_province; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <div class="input-group has-warning">
                <!-- Input Here -->
                <input class="form-control" id="brewerState" name="brewerState" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerState']; ?>" placeholder="">
                <span class="input-group-addon" id="brewerState-addon2"><span class="fa fa-star"></span></span>
            </div>
        </div>
    </div><!-- ./Form Group -->

	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
        <label for="brewerZip" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php if ($_SESSION['prefsProEdition'] == 1) echo $label_organization." "; echo $label_zip; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <div class="input-group has-warning">
                <!-- Input Here -->
                <input class="form-control" id="brewerZip" name="brewerZip" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerZip']; ?>" placeholder="">
                <span class="input-group-addon" id="brewerZip-addon2"><span class="fa fa-star"></span></span>
            </div>
        </div>
    </div><!-- ./Form Group -->

	<div class="form-group"><!-- Form Group REQUIRED Select -->
        <label for="brewerCountry" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php if ($_SESSION['prefsProEdition'] == 1) echo $label_organization." "; echo $label_country; ?></label>
        <div class="col-lg-9 col-md-6 col-sm-8 col-xs-12 has-warning">
        <!-- Input Here -->
        <select class="selectpicker" name="brewerCountry" id="brewerCountry" data-live-search="true" data-size="10" data-width="auto" data-show-tick="true" data-header="<?php echo $label_select_country; ?>" title="<?php echo $label_select_country; ?>">
            <?php foreach ($countries as $country) {  ?>
            <option value="<?php echo $country; ?>" <?php if (($action == "edit") && ($row_brewer['brewerCountry'] == $country)) echo "selected"; ?>><?php echo $country; ?></option>
            <?php } ?>
        </select>
        </div>
    </div><!-- ./Form Group -->

 	<?php if (($_SESSION['prefsProEdition'] == 0) || (($_SESSION['prefsProEdition'] == 1) && ($entrant_type_brewery))) { ?>
    <div class="form-group"><!-- Form Group NOT REQUIRED Select -->
        <label for="brewerDropOff" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_drop_off; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <select class="selectpicker" name="brewerDropOff" id="brewerDropOff" data-live-search="true" data-size="10" data-width="fit" data-show-tick="true" data-header="<?php echo $label_select_dropoff; ?>" title="<?php echo $label_select_dropoff; ?>">
        <?php if ($section != "step2") {
        do { ?>
            <option value="<?php echo $row_dropoff['id']; ?>" <?php if (($action == "edit") && ($row_brewer['brewerDropOff'] == $row_dropoff['id'])) echo "SELECTED"; ?>><?php echo $row_dropoff['dropLocationName']; ?></option>
        <?php } while ($row_dropoff = mysqli_fetch_assoc($dropoff));
            }
        ?>
            <option disabled="disabled">-------------</option>
    		<option value="0" <?php if (($section == "step2") || (($action == "edit") && ($row_brewer['brewerDropOff'] == "0"))) echo "SELECTED"; ?>><?php echo $brewer_text_005; ?></option>
        </select>
        </div>
    </div><!-- ./Form Group -->
    <?php } ?>

<!--
    <div class="form-group">
        <label for="brewerClubs" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_club; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
       		<input class="form-control" id="brewerClubs" name="brewerClubs" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerClubs']; ?>" placeholder="">
        </div>
    </div>
-->
    <?php if (!$entrant_type_brewery) { ?>
    <div class="form-group"><!-- Form Group REQUIRED Select -->
        <label for="brewerClubs" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_club; ?></label>
        <div class="col-lg-9 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <select class="selectpicker" name="brewerClubs" id="brewerClubs" data-live-search="true" data-size="10" data-width="fit" data-show-tick="true" data-header="<?php echo $label_select_club; ?>" title="<?php echo $label_select_club; ?>">
        	<option value="" <?php if (($action == "edit") && (empty($row_brewer['brewerClubs']))) echo "SELECTED"; ?>>None</option>
            <option value="Other" <?php if ($club_other) echo "SELECTED"; ?>>Other</option>
            <option data-divider="true"></option>
            <?php echo $club_options; ?>
        </select>
        <span class="help-block"><?php echo $brewer_text_023; ?></span>
        </div>
    </div><!-- ./Form Group -->

    <div id="brewerClubsOther" class="form-group">
        <label for="brewerClubsOther" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_club_enter; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
       		<input class="form-control" name="brewerClubsOther" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerClubs']; ?>" placeholder="">
        </div>
    </div>

    <div class="form-group"><!-- Form Group Text Input -->
        <label for="brewerAHA" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_aha_number; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
        	<!-- Input Here -->
       		<input class="form-control" id="brewerAHA" name="brewerAHA" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerAHA']; ?>" placeholder="">
            <span class="help-block"><?php echo $brewer_text_003; ?></span>
        </div>
    </div><!-- ./Form Group -->

    <!-- Staff preferences -->
    <div class="form-group"><!-- Form Group Radio INLINE -->
        <label for="brewerJudge" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_staff; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <div class="input-group">
                <!-- Input Here -->
                <label class="radio-inline">
                    <input type="radio" name="brewerStaff" value="Y" id="brewerStaff_0" <?php if (($action == "edit") && ($row_brewer['brewerStaff'] == "Y")) echo "checked"; ?>> <?php echo $label_yes; ?>
                </label>
                <label class="radio-inline">
                    <input type="radio" name="brewerStaff" value="N" id="brewerStaff_1" <?php if (($action == "edit") && (($row_brewer['brewerStaff'] == "N") || ($row_brewer['brewerStaff'] == ""))) echo "checked";  if ($section == "step2") echo "checked"; ?>> <?php echo $label_no; ?>
                </label>
            </div>
            <span class="help-block"><?php echo $brewer_text_020; ?></span>
        </div>
    </div><!-- ./Form Group -->

	<?php if (($go != "entrant") && ($section != "step2")) { ?>

        <div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
            <label for="brewerJudgeNotes" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_org_notes; ?></label>
            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                <!-- Input Here -->
                <input class="form-control" name="brewerJudgeNotes" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerJudgeNotes']; ?>" placeholder="">
                <span class="help-block"><?php echo $brewer_text_004; ?></span>
            </div>
        </div><!-- ./Form Group -->

    <?php if ($show_judge_steward_fields) ?>
    <!-- Judging and Stewarding Preferences or Assignments -->

		<?php if (($table_assignment) && ($go != "admin")) { ?>
        <!-- Already assigned to a table, can't change preferences -->
        <input name="brewerJudge" type="hidden" value="<?php echo $row_brewer['brewerJudge']; ?>" />
        <input name="brewerJudgeLocation" type="hidden" value="<?php echo $row_brewer['brewerJudgeLocation']; ?>" />
        <input name="brewerJudgeID" type="hidden" value="<?php echo $row_brewer['brewerJudgeID']; ?>" />
        <input name="brewerJudgeMead" type="hidden" value="<?php echo $row_brewer['brewerJudgeMead']; ?>" />
        <input name="brewerJudgeRank" type="hidden" value="<?php echo $row_brewer['brewerJudgeRank']; ?>" />
        <input name="brewerJudgeLikes" type="hidden" value="<?php echo $row_brewer['brewerJudgeLikes']; ?>" />
        <input name="brewerJudgeDislikes" type="hidden" value="<?php echo $row_brewer['brewerJudgeDislikes']; ?>" />
        <input name="brewerSteward" type="hidden" value="<?php echo $row_brewer['brewerSteward']; ?>" />
        <input name="brewerStewardLocation" type="hidden" value="<?php echo $row_brewer['brewerStewardLocation']; ?>" />
        <?php } // end if ($table_assignment)
		else { ?>


        <?php if (!$judge_limit) { ?>
        <!-- Judging preferences -->
        <div class="form-group"><!-- Form Group Radio INLINE -->
            <label for="brewerJudge" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_judging; ?></label>
            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                <div class="input-group">
                    <!-- Input Here -->
                    <label class="radio-inline">
                        <input type="radio" name="brewerJudge" value="Y" id="brewerJudge_0" <?php if (($action == "add") && ($go == "judge")) echo "CHECKED"; if (($action == "edit") && ($row_brewer['brewerJudge'] == "Y")) echo "checked"; ?>> <?php echo $label_yes; ?>
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="brewerJudge" value="N" id="brewerJudge_1" <?php if (($action == "add") && ($go == "default")) echo "CHECKED"; if (($action == "edit") && ($row_brewer['brewerJudge'] == "N")) echo "checked"; ?>> <?php echo $label_no; ?>
                    </label>
                </div>
                <span class="help-block"><?php echo $brewer_text_006; ?></span>
            </div>
        </div><!-- ./Form Group -->
        <div id="brewerJudgeFields">
        <?php if ($totalRows_judging == 1) { ?>
		<input name="brewerJudgeLocation" type="hidden" value="<?php echo "Y-".$row_judging3['id']; ?>" />
        <?php } ?>
        <?php if ($totalRows_judging > 1) { ?>
        <div class="form-group"><!-- Form Group NOT REQUIRED Select -->
            <label for="brewerJudgeLocation" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_judging_avail; ?></label>
            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <!-- Input Here -->
            <?php do { ?>
            <p class="bcoem-form-info"><?php echo $row_judging3['judgingLocName']." ("; echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging3['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time").")"; ?></p>
            <select class="selectpicker" name="brewerJudgeLocation[]" id="brewerJudgeLocation" data-width="auto">
                <option value="<?php echo "N-".$row_judging3['id']; ?>"   <?php $a = explode(",", $row_brewer['brewerJudgeLocation']); $b = "N-".$row_judging3['id']; foreach ($a as $value) { if ($value == $b) { echo "SELECTED"; } } ?>><?php echo $label_no; ?></option>
                <option value="<?php echo "Y-".$row_judging3['id']; ?>"   <?php $a = explode(",", $row_brewer['brewerJudgeLocation']); $b = "Y-".$row_judging3['id']; foreach ($a as $value) { if ($value == $b) { echo "SELECTED"; } } ?>><?php echo $label_yes; ?></option>
            </select>
            <?php }  while ($row_judging3 = mysqli_fetch_assoc($judging3)); ?>
            </div>
        </div><!-- ./Form Group -->
        <?php } ?>
        <div class="form-group"><!-- Form Group Text Input -->
            <label for="brewerJudgeID" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_bjcp_id; ?></label>
            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                <!-- Input Here -->
                <input class="form-control" id="brewerJudgeID" name="brewerJudgeID" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerJudgeID']; ?>" placeholder="" <?php if ($psort == "judge") echo "autofocus"; ?>>
            </div>
        </div><!-- ./Form Group -->

        <div class="form-group"><!-- Form Group Radio INLINE -->
            <label for="brewerJudgeMead" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_bjcp_mead; ?></label>
            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                <div class="input-group">
                    <!-- Input Here -->
                    <label class="radio-inline">
                        <input type="radio" name="brewerJudgeMead" value="Y" id="brewerJudgeMead_0" <?php if (($action == "edit") && ($row_brewer['brewerJudgeMead'] == "Y")) echo "CHECKED"; ?>> <?php echo $label_yes; ?>
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="brewerJudgeMead" value="N" id="brewerJudgeMead_1" <?php if (($action == "edit") && (($row_brewer['brewerJudgeMead'] == "N") || ($row_brewer['brewerJudgeMead'] == ""))) echo "CHECKED"; ?>> <?php echo $label_no; ?>
                    </label>
                </div>
                <span class="help-block"><?php echo $brewer_text_007; ?></span>
            </div>
        </div><!-- ./Form Group -->
        <?php $judge_array = explode(",",$row_brewer['brewerJudgeRank']); ?>
        <div class="form-group"><!-- Form Group Radio STACKED -->
            <label for="brewerJudgeRank" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_bjcp_rank; ?></label>
            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                <div class="input-group">
                    <!-- Input Here -->
                    <div class="radio">
                        <label>
                            <input type="radio" name="brewerJudgeRank[]" value="Non-BJCP" <?php if (($action == "edit") && (in_array("Non-BJCP",$judge_array) || in_array("Novice",$judge_array))) echo "CHECKED"; else echo "CHECKED" ?>> Non-BJCP *
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="brewerJudgeRank[]" value="Rank Pending" <?php if (($action == "edit")  && in_array("Rank Pending",$judge_array)) echo "CHECKED"; ?>> Rank Pending
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                             <input type="radio" name="brewerJudgeRank[]" value="Provisional" <?php if (($action == "edit") && in_array("Provisional",$judge_array)) echo "CHECKED"; ?>> Provisional **
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="brewerJudgeRank[]" value="Recognized" <?php if (($action == "edit") && in_array("Recognized",$judge_array)) echo "CHECKED"; ?>> Recognized
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="brewerJudgeRank[]" value="Certified" <?php if (($action == "edit") && in_array("Certified",$judge_array)) echo "CHECKED"; ?>> Certified
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="brewerJudgeRank[]" value="National" <?php if (($action == "edit") && in_array("National",$judge_array)) echo "CHECKED"; ?>> National
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                             <input type="radio" name="brewerJudgeRank[]" value="Master" <?php if (($action == "edit") && in_array("Master",$judge_array)) echo "CHECKED"; ?>> Master
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="brewerJudgeRank[]" value="Honorary Master" <?php if (($action == "edit") && in_array("Honorary Master",$judge_array)) echo "CHECKED"; ?>> Honorary Master
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="brewerJudgeRank[]" value="Grand Master" <?php if (($action == "edit") && in_array("Grand Master",$judge_array)) echo "CHECKED"; ?>> Grand Master
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="brewerJudgeRank[]" value="Honorary Grand Master" <?php if (($action == "edit") && in_array("Honorary Grand Master",$judge_array)) echo "CHECKED"; ?>>Honorary Grand Master
                        </label>
                    </div>
                </div>
                <span class="help-block">
                	<p><?php echo $brewer_text_008; ?></p>
                    <p><?php echo $brewer_text_009; ?></p>
                    </p>
                </span>
            </div>
        </div><!-- ./Form Group -->
        <div class="form-group"><!-- Form Group Radio STACKED -->
            <label for="brewerJudgeRank" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_designations; ?></label>
            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                <div class="input-group">
                    <!-- Input Here -->
                    <div class="checkbox">
                        <label>
                             <input type="checkbox" name="brewerJudgeRank[]" value="Judge with Sensory Training" <?php if (($action == "edit") && in_array("Judge with Sensory Training",$judge_array)) echo "CHECKED"; ?>> Judge with Sensory Training
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="brewerJudgeRank[]" value="Professional Brewer" <?php if (($action == "edit") && in_array("Professional Brewer",$judge_array)) echo "CHECKED"; ?>> Professional Brewer
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="brewerJudgeRank[]" value="Certified Cicerone" <?php if (($action == "edit") && in_array("Certified Cicerone",$judge_array)) echo "CHECKED"; ?>> Certified Cicerone
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="brewerJudgeRank[]" value="Advanced Cicerone" <?php if (($action == "edit") && in_array("Advanced Cicerone",$judge_array)) echo "CHECKED"; ?>> Advanced Cicerone
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="brewerJudgeRank[]" value="Master Cicerone" <?php if (($action == "edit") && in_array("Master Cicerone",$judge_array)) echo "CHECKED"; ?>> Master Cicerone
                        </label>
                    </div>

                 </div>
                <span class="help-block"><?php echo $brewer_text_010; ?></span>
            </div>
        </div><!-- ./Form Group -->
        <div class="form-group"><!-- Form Group REQUIRED Select -->
            <label for="brewerJudgeExp" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_judge_comps; ?></label>
            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <!-- Input Here -->
            <select class="selectpicker" name="brewerJudgeExp" id="brewerJudgeExp" data-width="auto" required>
                <option value="0"<?php if (($action == "edit") && ($row_brewer['brewerJudgeExp'] == "0")) echo " SELECTED"; ?>>0</option>
                <option value="1-5"<?php if (($action == "edit") && ($row_brewer['brewerJudgeExp'] == "1-5")) echo " SELECTED"; ?>>1-5</option>
                <option value="6-10"<?php if (($action == "edit") && ($row_brewer['brewerJudgeExp'] == "6-10")) echo " SELECTED"; ?>>6-10</option>
                <option value="10+"<?php if (($action == "edit") && ($row_brewer['brewerJudgeExp'] == "10+")) echo " SELECTED"; ?>>10+</option>
            </select>
            <span class="help-block"><?php echo $brewer_text_011; ?></span>
            </div>

        </div><!-- ./Form Group -->
        <div class="form-group">
        <label for="brewerJudgeLikes" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label">&nbsp;</label>
                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
        			<button class="btn btn-info btn-block" type="button" data-toggle="collapse" data-target="#collapsePref" aria-expanded="false" aria-controls="collapsePref"><?php echo $label_judge_preferred; ?></button>
        			<span class="help-block"><?php echo $brewer_text_017; ?></span>
                </div>

        </div><!-- ./Form Group -->
        <div class="collapse" id="collapsePref">
          	<div class="form-group"><!-- Form Group Checkbox  -->
                <label for="brewerJudgeLikes" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_judge_preferred; ?></label>
                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                <p><strong class="text-danger"><?php echo $brewer_text_012; ?></strong></p>
                    <?php if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false) echo $brewer_ba_likes;
					else { ?>
                    <?php do { ?>
                        <div class="checkbox">
                            <label>
                                <input name="brewerJudgeLikes[]" type="checkbox" value="<?php echo $row_styles['id']; ?>" <?php if (isset($row_brewer['brewerJudgeLikes'])) { $a = explode(",", $row_brewer['brewerJudgeLikes']); $b = $row_styles['id']; foreach ($a as $value) { if ($value == $b) echo "CHECKED"; } } ?>> <?php echo ltrim($row_styles['brewStyleGroup'], "0").$row_styles['brewStyleNum'].": ".$row_styles['brewStyle']; ?>
                            </label>
                        </div>
                    <?php } while ($row_styles = mysqli_fetch_assoc($styles)); ?>
                    <?php } ?>
                </div>
            </div><!-- ./Form Group -->
        </div>
        <div class="form-group">
        <label for="brewerJudgeLikes" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label">&nbsp;</label>
                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
        			<button class="btn btn-info btn-block" type="button" data-toggle="collapse" data-target="#collapseNonPref" aria-expanded="false" aria-controls="collapseNonPref"><?php echo $label_judge_non_preferred; ?></button>
                    <span class="help-block"><?php echo $brewer_text_013; ?></span>
                </div>
        </div><!-- ./Form Group -->
 		<div class="collapse" id="collapseNonPref">
          	<div class="form-group"><!-- Form Group Checkbox  -->
                <label for="brewJudgeDislikes" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_judge_non_preferred; ?></label>
                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                    <p><strong class="text-danger"><?php echo $brewer_text_014; ?></strong></p>
                    <!-- <div class="row"> -->
                    <?php if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false) echo $brewer_ba_dislikes;
					else { ?>
					<?php do { ?>
                    <!-- Input Here -->
                        <div class="checkbox">
                            <label>
                                <input name="brewerJudgeDislikes[]" type="checkbox" value="<?php echo $row_styles2['id']; ?>" <?php if (isset($row_brewer['brewerJudgeDislikes'])) { $a = explode(",", $row_brewer['brewerJudgeDislikes']); $b = $row_styles2['id']; foreach ($a as $value) { if ($value == $b) echo "CHECKED"; } } ?>> <?php echo ltrim($row_styles2['brewStyleGroup'], "0").$row_styles2['brewStyleNum'].": ".$row_styles2['brewStyle']; ?>
                            </label>
                        </div>
                    <?php } while ($row_styles2 = mysqli_fetch_assoc($styles2)); ?>
					<?php } ?>
                    <!-- </div> -->
                </div>
            </div><!-- ./Form Group -->
        </div>
        </div><!-- ./ brewerJudgeFields -->
        <?php } // end if (!$judge_limit) ?>
        
        
        
        
        
        <?php if (!$steward_limit) { ?>
        <!-- Stewarding preferences -->
        <div class="form-group"><!-- Form Group Radio INLINE -->
            <label for="brewerSteward" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_stewarding; ?></label>
            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                <div class="input-group">
                    <!-- Input Here -->
                    <label class="radio-inline">
                        <input type="radio" name="brewerSteward" value="Y" id="brewerSteward_0" <?php if (($action == "add") && ($go == "judge")) echo "CHECKED"; if (($action == "edit") && ($row_brewer['brewerSteward'] == "Y")) echo "checked"; ?>> <?php echo $label_yes; ?>
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="brewerSteward" value="N" id="brewerSteward_1" <?php if (($action == "add") && ($go == "default")) echo "CHECKED"; if (($action == "edit") && ($row_brewer['brewerSteward'] == "N")) echo "checked"; ?>> <?php echo $label_no; ?>
                    </label>
                </div>
                <span class="help-block"><?php echo $brewer_text_015; ?></span>
            </div>
        </div><!-- ./Form Group -->
        <?php if ($totalRows_judging == 1) { ?>
		<input name="brewerStewardLocation" type="hidden" value="<?php echo "Y-".$row_judging3['id']; ?>" />
        <?php } ?>
        <?php if ($totalRows_judging > 1) { ?>
        <div id="brewerStewardFields">
			<div class="form-group"><!-- Form Group NOT REQUIRED Select -->
				<label for="brewerStewardLocation" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_stewarding_avail; ?></label>
				<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
				<!-- Input Here -->
				<?php do { ?>
				<p class="bcoem-form-info"><?php echo $row_stewarding['judgingLocName']." ("; echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_stewarding['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time").")"; ?></p>
				<select class="selectpicker" name="brewerStewardLocation[]" id="brewerStewardLocation" data-width="auto">
					<option value="<?php echo "N-".$row_stewarding['id']; ?>"   <?php $a = explode(",", $row_brewer['brewerStewardLocation']); $b = "N-".$row_stewarding['id']; foreach ($a as $value) { if ($value == $b) { echo "SELECTED"; } } ?>>No</option>
					<option value="<?php echo "Y-".$row_stewarding['id']; ?>"   <?php $a = explode(",", $row_brewer['brewerStewardLocation']); $b = "Y-".$row_stewarding['id']; foreach ($a as $value) { if ($value == $b) { echo "SELECTED"; } } ?>>Yes</option>
				</select>
				<?php }  while ($row_stewarding = mysqli_fetch_assoc($stewarding));  ?>
				</div>
			</div><!-- ./Form Group -->
        </div>
        <?php } ?>
        <?php } ?>
        <?php if (($go != "entrant") &&  (($row_brewer['brewerJudge'] == "Y") || ($row_brewer['brewerSteward'] == "Y"))) { ?>
    <div class="form-group"><!-- Form Group REQUIRED Radio Group -->
		<label for="brewerJudgeWaiver" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_waiver; ?></label>
		<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
			<div class="checkbox">
				<!-- Input Here -->
                <p><?php echo $brewer_text_016; ?></p>
				<label>
					<input type="checkbox" name="brewerJudgeWaiver" value="Y" id="brewerJudgeWaiver_0" checked required /><?php echo $brewer_text_018; ?>
				</label>
			</div>
            <div class="help-block with-errors"></div>
		</div>
	</div><!-- ./Form Group -->
    <?php } ?>
        <?php } ?>
    <?php } // end if (($go != "entrant") && ($section != "step2")) ?>
<?php } // end if (!$entrant_type_brewery) ?>
<?php if ($section == "step2") { ?>
<input name="brewerSteward" type="hidden" value="N" />
<input name="brewerJudge" type="hidden" value="N" />
<input name="brewerEmail" type="hidden" value="<?php echo $go; ?>" />
<input name="uid" type="hidden" value="<?php echo $row_brewerID['id']; ?>" />
<?php } ?>
<?php if ($section != "step2") { ?>
	<input name="brewerEmail" type="hidden" value="<?php if ($filter != "default") echo $row_brewer['brewerEmail']; else echo $_SESSION['user_name']; ?>" />
	<input name="uid" type="hidden" value="<?php if (($action == "edit") && ($row_brewer['uid'] != "")) echo  $row_brewer['uid']; elseif (($action == "edit") && ($_SESSION['userLevel'] <= "1") && (($_SESSION['loginUsername']) != $row_brewer['brewerEmail'])) echo $row_user_level['id']; else echo $_SESSION['user_id']; ?>" />
    <?php if (($go == "entrant") || (($_SESSION['prefsProEdition'] == 1) && (isset($row_brewer['brewerBreweryName'])))) { ?>
	<input name="brewerJudge" type="hidden" value="N" />
	<input name="brewerSteward" type="hidden" value="N" />
	<?php }
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

?>
<?php if ($go == "admin") {

?>
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
        <button name="submit" type="submit" class="btn btn-primary" ><?php echo $submit_text; ?> </button>
    </div>
</div><!-- Form Group -->
</form>
<?php } // LINE 25 or so... end if (($section == "step2") || ($action == "add") || (($action == "edit") && (($_SESSION['loginUsername'] == $row_brewerID['brewerEmail'])) || ($_SESSION['userLevel'] <= "1")))
else echo "<p class='lead'>You can only edit your own profile.</p>";
?>