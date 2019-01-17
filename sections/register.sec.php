<script type="text/javascript">
function checkAvailability() {
	jQuery.ajax({
		url: "<?php echo $base_url; ?>includes/ajax_functions.inc.php?action=username",
		data:'user_name='+$("#user_name").val(),
		type: "POST",
		success:function(data) {
			$("#status").html(data);
		},
		error:function (){}
	});
}

function AjaxFunction(email) {
	var httpxml;
		try 	{
		// Firefox, Opera 8.0+, Safari
		httpxml=new XMLHttpRequest();
		}
	catch (e) {
		// Internet Explorer
		try	{
			httpxml=new ActiveXObject("Msxml2.XMLHTTP");
		}
	catch (e) {
		try {
		httpxml=new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch (e) {
		//alert("Your browser does not support AJAX!");
		return false;
		}
	}
}

function stateck() {
	if(httpxml.readyState==4) {
		document.getElementById("msg_email").innerHTML=httpxml.responseText;
	}
}

var url="<?php echo $base_url; ?>includes/ajax_functions.inc.php?action=email";
url=url+"&email="+email;
url=url+"&sid="+Math.random();
httpxml.onreadystatechange=stateck;
httpxml.open("GET",url,true);
httpxml.send(null);
}

$(document).ready(function(){

	$("#brewerClubsOther").hide("fast");

	$("#brewerClubs").change(function() {

		if ($("#brewerClubs").val() == "Other") {
			$("#brewerClubsOther").show("slow");
		}

		else  {
			$("#brewerClubsOther").hide("fast");
		}

	});

	<?php if (($action == "edit") && ($row_brewer['brewerSteward'] == "Y")) { ?>
	$("#brewerStewardFields").show("slow");
	<?php } else { ?>
	$("#brewerStewardFields").hide("fast");
	<?php } ?>

	$('input[type="radio"]').click(function() {
       if($(this).attr('id') == 'brewerSteward_0') {
            $("#brewerStewardFields").show("slow");
       }

       else {
         	$("#brewerStewardFields").hide("fast");
       }
   	});

});
//-->
</script>
<?php
$warning0 = "";
$warning1 = "";
$warning2 = "";
$primary_page_info = "";
$header1_1 = "";
$page_info1 = "";
$header1_2 = "";
$page_info2 = "";

if (!empty($_SESSION['prefsGoogleAccount'])) {
    $recaptcha_key = explode("|", $_SESSION['prefsGoogleAccount']);
    $public_captcha_key = $recaptcha_key[0];
}

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

else { // THIS ELSE ENDS at the end of the script

	include (DB.'judging_locations.db.php');
	include (DB.'stewarding.db.php');
	include (DB.'styles.db.php');
	include (DB.'brewer.db.php');
	if (NHC) $totalRows_log = $totalRows_entry_count;
	else $totalRows_log = $totalRows_log;
	if ($go != "default") {
		$country_select = "";
		foreach ($countries as $country) {
			$country_select .= "<option value=\"".$country."\" ";
			if (($msg > 0) && (isset($_COOKIE['brewerCountry'])) && ($_COOKIE['brewerCountry'] == $country)) $country_select .= "SELECTED";
			$country_select .= ">";
			$country_select .= $country."</option>\n";
     	}

	$random_country = array_rand($countries);
	$random_country = $countries[$random_country];

	include (DB.'dropoff.db.php');

	if ($totalRows_dropoff > 0) {
		$dropoff_select = "";
		do {
    		$dropoff_select .= "<option value=\"".$row_dropoff['id']."\" ";
			if (($action == "edit") && ($row_brewer['brewerDropOff'] == $row_dropoff['id'])) $dropoff_select .= "SELECTED";
			if (($msg > 0) && (isset($_COOKIE['brewerDropOff'])) && ($_COOKIE['brewerDropOff'] == $row_dropoff['id'])) $dropoff_select .= "SELECTED";
			$dropoff_select .= ">";
			$dropoff_select .= $row_dropoff['dropLocationName']."</option>\n";
   		} while ($row_dropoff = mysqli_fetch_assoc($dropoff));
	}
}

if (($comp_paid_entry_limit) && ($go == "entrant")) $warning0 .= sprintf("<div class=\"alert alert-danger\"><strong>%s:</strong> %s %s</div>",$label_please_note,$alert_text_053);

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
if ((($registration_open < 2) || ($judge_window_open < 2)) && ($go == "default") && ($section != "admin") && ((!$comp_entry_limit) || (!$comp_paid_entry_limit))) {
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
}

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
		$club_selected = "";
		$club_option = explode ("|",$club);
		$club_options .= "<option value=\"".$club_option[0]."\"".$club_selected.">".$club_option[1]."</option>\n";
	}

}

$security_questions_display = (array_rand($security_question, 5));
$security = "";

if (isset($_COOKIE['userQuestion'])) $security .= "<div class=\"radio\"><label><input type=\"radio\" name=\"userQuestion\" value=\"".$_COOKIE['userQuestion']."\" CHECKED> ".$_COOKIE['userQuestion']."</label></div>";

foreach ($security_questions_display as $key => $value) {

	$security_checked = "";
	if (($msg == "default") && ($key == 0)) $security_checked = "CHECKED";

    if (isset($_COOKIE['userQuestion'])) {
        if ($_COOKIE['userQuestion'] == $security_question[$value]) $security .= "";
        else $security .= "<div class=\"radio\"><label><input type=\"radio\" name=\"userQuestion\" value=\"".$security_question[$value]."\" ".$security_checked."> ".$security_question[$value]."</label></div>";

    }
	else $security .= "<div class=\"radio\"><label><input type=\"radio\" name=\"userQuestion\" value=\"".$security_question[$value]."\" ".$security_checked."> ".$security_question[$value]."</label></div>";

}

// --------------------------------------------------------------
// Display
// --------------------------------------------------------------

if (($section != "admin") && ($action != "print")) {
    echo $warning0;
    echo $warning1;
}
if (NHC) echo $warning2;
echo $header1_1;
echo $page_info1;
if ($go == "default") {  ?>
<!-- DEFAULT screen to choose role - Will be deprecated in 2.1.9 -->
<p><strong><?php echo $label_register_judge; ?></strong></p>
<div class="row">
    <div class="col col-sm-4">
        <a class="btn btn-primary btn-block" href="<?php echo build_public_url("register","entrant","default","default",$sef,$base_url); ?>">Entrant</a>
    </div>
    <div class="col col-sm-4">
        <a class="btn btn-warning btn-block" href="<?php echo build_public_url("register","judge","default","default",$sef,$base_url); ?>">Judge</a>
    </div>
    <div class="col col-sm-4">
        <a class="btn btn-info btn-block" href="<?php echo build_public_url("register","steward","default","default",$sef,$base_url); ?>">Steward</a>
    </div>
</div>
<input type="hidden" name="relocate" value="<?php echo relocate($relocate,"default",$msg,$id); ?>">
</form>
<?php } else { // THIS ELSE ENDS at the end of the script ?>
<!-- Begin the Form -->
	<form data-toggle="validator" role="form" class="form-horizontal" action="<?php echo $base_url; ?>includes/process.inc.php?action=add&amp;dbTable=<?php echo $users_db_table; ?>&amp;section=register&amp;go=<?php echo $go; if ($section == "admin") echo "&amp;filter=admin"; echo "&amp;view=".$view; ?>" method="POST" name="form1" id="form1">
	<!-- Hidden Form Elements -->
	<!-- User Level is Always 2 -->
	<input type="hidden" name="userLevel" value="2" />
	<?php if ($judge_hidden) { ?>
	<!-- User does not wish to be a judge -->
	<input type="hidden" name="brewerJudge" value="N" />
	<?php } ?>
	<?php if ($steward_hidden) { ?>
	<!-- User does not wish to be a steward -->
	<input type="hidden" name="brewerSteward" value="N" />
	<?php } ?>
	<?php if ($section == "admin") { ?>
	<!-- Admin Specific Registration -->
    <div class="bcoem-admin-element hidden-print">
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
        <input type="hidden" name="brewerCountry" value="<?php echo $random_country; ?>">
        <input type="hidden" name="brewerPhone1" value="1234567890">
    <?php } // END if ($view == "quick")?>
	<?php } // END if ($section == "admin") ?>
	<?php if (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant")) { ?>
    <div class="form-group"><!-- Form Group REQUIRED Text Input -->
        <label for="brewerBreweryName" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_organization." ".$label_name; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <div class="input-group has-warning">
                <span class="input-group-addon" id="brewerBreweryName-addon1"><span class="fa fa-beer"></span></span>
                <!-- Input Here -->
                <input class="form-control" id="brewerBreweryName" name="brewerBreweryName" type="text" value="<?php if ($msg > 0) echo $_COOKIE['brewerBreweryName']; ?>" data-error="<?php echo $register_text_044; ?>" placeholder="" required autofocus>
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
       		<input class="form-control" id="brewerBreweryTTB" name="brewerBreweryTTB" type="text" value="<?php if (($msg > 0) && (isset($_COOKIE['brewerBreweryTTB']))) echo $_COOKIE['brewerBreweryTTB']; ?>" placeholder="">
            <div class="help-block"><?php echo $register_text_046; ?></div>
        </div>
    </div><!-- ./Form Group -->
	<?php } // END if (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant")) ?>
	<!-- Email -->
    <div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php if (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant")) echo $label_contact." "; echo $label_email; ?></label>
		<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="email-addon1"><span class="fa fa-envelope"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="user_name" id="user_name" type="email" placeholder="" data-error="<?php echo $register_text_019; ?>" onBlur="checkAvailability()" onkeyup="twitter.updateUrl(this.value)" onchange="AjaxFunction(this.value);" value="<?php if (($msg > 0) && (isset($_COOKIE['user_name']))) echo $_COOKIE['user_name']; ?>" required <?php if ($_SESSION['prefsProEdition'] == 0) echo "autofocus"; ?>>
				<span class="input-group-addon" id="email-addon2"><span class="fa fa-star"></span>
			</div>
            <div class="help-block"><?php echo $register_text_021; ?></div>
			<div class="help-block with-errors"></div>
            <div id="msg_email"></div>
			<div id="status"></div>
		</div>
	</div><!-- ./Form Group -->
    <?php if ($view == "default") { // Show if not using quick add judge/steward feature ?>
    <div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_re_enter; if (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant")) echo " ".$label_contact." "; echo " ".$label_email; ?></label>
		<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="re-enter-email-addon1"><span class="fa fa-envelope"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="user_name2" type="email" placeholder="" id="user_name2" data-match="#user_name" data-error="<?php echo $register_text_019; ?>"; data-match-error="<?php echo $register_text_020; ?>" value="<?php if (($msg > 0) && (isset($_COOKIE['user_name2']))) echo $_COOKIE['user_name2']; ?>" required>
				<span class="input-group-addon" id="re-enter-email-addon2"><span class="fa fa-star"></span>
			</div>
            <div class="help-block with-errors"></div>
		</div>
	</div><!-- ./Form Group -->
	<script type="text/javascript">
		$(document).ready(function () {
			"use strict";
			var options = {};
			options.ui = {
				container: "#pwd-container",
				showErrors: true,
				useVerdictCssClass: true,
				showVerdictsInsideProgressBar: true,
				viewports: {
					progress: ".pwd-strength-viewport-progress"
				},
				progressBarExtraCssClasses: "progress-bar-striped active",
				progressBarEmptyPercentage: 2,
				progressBarMinPercentage: 6
			};
			options.common = {
				zxcvbn: true,
				minChar: 8,
				onKeyUp: function (evt, data) {
					$("#length-help-text").text("<?php echo $label_length; ?>: " + $(evt.target).val().length + " - <?php echo $label_score; ?>: " + data.score.toFixed(2));
				},
			};
			$('#password1').pwstrength(options);
		});
	</script>
	<!-- Password -->
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_password; ?></label>
		<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="password-addon1"><span class="fa fa-key"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="password" id="password1" type="password" placeholder="<?php echo $label_password; ?>" value="<?php if (($msg > 0)  && (isset($_COOKIE['password']))) echo $_COOKIE['password']; ?>" data-error="<?php echo $register_text_022; ?>" required>
				<span class="input-group-addon" id="password-addon2"><span class="fa fa-star"></span></span>
			</div>
            <div class="help-block with-errors"></div>
		</div>
	</div><!-- ./Form Group -->
	<div class="form-group" id="pwd-container">
		<label class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_password_strength; ?></label>
		<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
			<div class="pwd-strength-viewport-progress"></div>
			<div id="length-help-text" class="small"></div>
		</div>
	</div>
	<?php } // END if ($view == "default") ?>
    <?php if ($section != "admin") { // Show only when NOT being added by an administrator ?>
	<div class="form-group"><!-- Form Group REQUIRED Radio Group -->
		<label for="" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_security_question; ?></label>
		<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
			<div class="input-group">
                <?php echo $security; ?>
			</div>
            <div class="help-block"><?php echo $register_text_018; ?></div>
		</div>
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_security_answer; ?></label>
		<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="security-question-answer-addon1"><span class="fa fa-bullhorn"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="userQuestionAnswer" id="userQuestionAnswer" type="text" placeholder="" value="<?php if (($msg > 0) && (isset($_COOKIE['userQuestionAnswer']))) echo $_COOKIE['userQuestionAnswer']; ?>" data-error="<?php echo $register_text_023; ?>" required>
				<span class="input-group-addon" id="security-question-answer-addon2"><span class="fa fa-star"></span>
			</div>
            <div class="help-block"><?php echo $register_text_024; ?></div>
            <div class="help-block with-errors"></div>
		</div>
	</div><!-- ./Form Group -->
	<?php } // end if ($section != "admin") ?>
  	<!-- Name -->
    <div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php if (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant")) echo $label_contact." "; echo $label_first_name; ?></label>
		<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="first-name-addon1"><span class="fa fa-user"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="brewerFirstName" id="brewerFirstName" type="text" placeholder="" value="<?php if (($msg > 0) && (isset($_COOKIE['brewerFirstName']))) echo $_COOKIE['brewerFirstName']; ?>" data-error="<?php echo $register_text_025; ?>" required>
				<span class="input-group-addon" id="first-name-addon2"><span class="fa fa-star"></span>
			</div>
            <div class="help-block with-errors"></div>
		</div>
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php if (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant")) echo $label_contact." "; echo $label_last_name; ?></label>
		<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="last-name-addon1"><span class="fa fa-user"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="brewerLastName" id="brewerLastName" type="text" placeholder="" value="<?php if (($msg > 0) && (isset($_COOKIE['brewerFirstName']))) echo $_COOKIE['brewerLastName']; ?>" data-error="<?php echo $register_text_026; ?>" required>
				<span class="input-group-addon" id="last-name-addon2"><span class="fa fa-star"></span>
			</div>
            <?php if ($section != "admin") { ?><div id="helpBlock" class="help-block"><?php echo $brewer_text_000; if ($_SESSION['prefsProEdition'] == 0) echo " ".$brewer_text_022; ?></div><?php } ?>
            <div class="help-block with-errors"></div>
		</div>
	</div><!-- ./Form Group -->
    <?php if ($view == "default") { ?>
    <!-- General Entry Fields: Address, Phone, Dropoff Locations, Club, AHA -->
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php if (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant")) echo $label_organization." "; echo $label_street_address; ?></label>
		<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="street-addon1"><span class="fa fa-home"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="brewerAddress" id="brewerAddress" type="text" placeholder="" value="<?php if (($msg > 0) && (isset($_COOKIE['brewerAddress']))) echo $_COOKIE['brewerAddress']; ?>" data-error="<?php echo $register_text_028; ?>" required>
				<span class="input-group-addon" id="street-addon2"><span class="fa fa-star"></span>
			</div>
            <div class="help-block with-errors"></div>
		</div>
    </div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php if (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant")) echo $label_organization." "; echo $label_city; ?></label>
		<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="city-addon1"><span class="fa fa-home"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="brewerCity" id="brewerCity" type="text" placeholder="" value="<?php if (($msg > 0) && (isset($_COOKIE['brewerCity']))) echo $_COOKIE['brewerCity']; ?>" data-error="<?php echo $register_text_029; ?>" required>
				<span class="input-group-addon" id="city-addon2"><span class="fa fa-star"></span>
			</div>
            <div class="help-block with-errors"></div>
		</div>
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php if (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant")) echo $label_organization." "; echo $label_state_province; ?></label>
		<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="state-addon1"><span class="fa fa-home"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="brewerState" id="brewerState" type="text" placeholder="" value="<?php if (($msg > 0) && (isset($_COOKIE['brewerState']))) echo $_COOKIE['brewerState']; ?>" data-error="<?php echo $register_text_030; ?>" required>
				<span class="input-group-addon" id="state-addon2"><span class="fa fa-star"></span>
			</div>
            <div class="help-block with-errors"></div>
		</div>
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php if (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant")) echo $label_organization." "; echo $label_zip; ?></label>
		<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="zip-addon1"><span class="fa fa-home"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="brewerZip" id="brewerZip" type="text" placeholder="" value="<?php if (($msg > 0) && (isset($_COOKIE['brewerZip']))) echo $_COOKIE['brewerZip']; ?>" data-error="<?php echo $register_text_031; ?>" required>
				<span class="input-group-addon" id="zip-addon2"><span class="fa fa-star"></span>
			</div>
            <div class="help-block with-errors"></div>
		</div>
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group REQUIRED Select -->
		<label for="" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php if (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant")) echo $label_organization." "; echo $label_country; ?></label>
		<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12 has-warning">
		<!-- Input Here -->
		<select class="selectpicker" name="brewerCountry" id="brewerCountry" data-live-search="true" data-size="10" data-width="fit">
    		<?php echo $country_select; ?>
    	</select>
		</div>
	</div><!-- ./Form Group -->
    <div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php if (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant")) echo $label_contact." "; echo $label_phone_primary; ?></label>
		<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="phone1-addon1"><span class="fa fa-phone"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="brewerPhone1" id="brewerPhone1" type="tel" placeholder="" value="<?php if (($msg > 0) && (isset($_COOKIE['brewerPhone1']))) echo $_COOKIE['brewerPhone1']; ?>" data-error="<?php echo $register_text_032; ?>" required>
				<span class="input-group-addon" id="phone1-addon2"><span class="fa fa-star"></span>
			</div>
            <div class="help-block with-errors"></div>
		</div>
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group Text Input -->
		<label for="" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php if (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant")) echo $label_contact." "; echo $label_phone_secondary; ?></label>
		<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
			<div class="input-group">
				<span class="input-group-addon" id="phone2-addon1"><span class="fa fa-phone"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="brewerPhone2" id="brewerPhone2" type="tel" placeholder="" value="<?php if (($msg > 0) && (isset($_COOKIE['brewerPhone2']))) echo $_COOKIE['brewerPhone2']; ?>">
			</div>
		</div>
	</div><!-- ./Form Group -->
    <?php if (($_SESSION['prefsProEdition'] == 0) || (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant"))) { ?>
	<div class="form-group"><!-- Form Group REQUIRED Select -->
		<label for="" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_drop_off; ?></label>
		<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12 has-warning">
			<!-- Input Here -->
			<select class="selectpicker" name="brewerDropOff" id="brewerDropOff" data-size="10" data-width="fit">
				<option value="0"><?php echo $brewer_text_005; ?></option>
				<option disabled="disabled">-------------</option>
				<?php echo $dropoff_select; ?>
			</select>

		</div>
	</div><!-- ./Form Group -->
    <?php } // END if (($_SESSION['prefsProEdition'] == 0) || (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant"))) ?>
    <?php if (($_SESSION['prefsProEdition'] == 0) || (($_SESSION['prefsProEdition'] == 1) && ($go != "entrant"))) { ?>
	<div class="form-group"><!-- Form Group REQUIRED Select -->
        <label for="brewerClubs" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_club; ?></label>
        <div class="col-lg-9 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <select class="selectpicker" name="brewerClubs" id="brewerClubs" data-live-search="true" data-size="10" data-width="fit" data-show-tick="true" data-header="<?php echo $label_select_club; ?>" title="<?php echo $label_select_club; ?>">
        	<option value="">None</option>
            <option value="Other">Other</option>
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
    <?php if ($_SESSION['prefsProEdition'] == 0) { ?>
    <div class="form-group">
        <label for="" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_pro_am; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <p><?php echo $brewer_text_041; ?></p>
            <div class="input-group">
                <label class="radio-inline">
                    <input type="radio" name="brewerProAm" value="1" id="brewerProAm_1"  <?php if (($msg > 0) && (isset($_COOKIE['brewerProAm'])) && ($_COOKIE['brewerProAm'] == "1")) echo "CHECKED";  ?> /> <?php echo $label_yes; ?>
                </label>
                <label class="radio-inline">
                    <input type="radio" name="brewerProAm" value="0" id="brewerProAm_0" <?php if (($msg > 0) && (isset($_COOKIE['brewerProAm'])) && ($_COOKIE['brewerProAm'] == "0")) echo "CHECKED";  if ($msg == "default") echo "CHECKED";  ?> /> <?php echo $label_no; ?>
                </label>
            </div>
            <div class="help-block"><?php echo $brewer_text_042; ?></div>
        </div>
    </div>
    <?php } else { ?>
    <input type="hidden" name="brewerProAm" value="0">
    <?php } ?>
	<div class="form-group"><!-- Form Group Text Input -->
		<label for="" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_aha_number; ?></label>
		<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
			<div class="input-group">
				<span class="input-group-addon" id="aha-addon1"><span class="fa fa-beer"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="brewerAHA" id="brewerAHA" type="number" placeholder="" value="<?php if (($msg > 0) && (isset($_COOKIE['brewerAHA']))) echo $_COOKIE['brewerAHA']; ?>">
			</div>
            <div class="help-block"><?php echo $register_text_033; ?></div>
		</div>
	</div><!-- ./Form Group -->


    <?php } // END if (($_SESSION['prefsProEdition'] == 0) || (($_SESSION['prefsProEdition'] == 1) && ($go != "entrant"))) ?>
    <?php } // END if ($view == "default") ?>
    <?php if (($_SESSION['prefsProEdition'] == 0) || (($_SESSION['prefsProEdition'] == 1) && (($go == "judge") || ($go == "steward")))) {




    ?>
    <!-- Staff preferences -->
    <div class="form-group"><!-- Form Group Radio INLINE -->
        <label for="brewerStaff" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_staff; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
        <p><?php echo $brewer_text_020; ?></p>
            <div class="input-group">
                <!-- Input Here -->
                <label class="radio-inline">
                    <input type="radio" name="brewerStaff" value="Y" id="brewerStaff_0" <?php if (($msg > 0) && (isset($_COOKIE['brewerStaff'])) && ($_COOKIE['brewerStaff'] == "Y")) echo "CHECKED"; ?>> <?php echo $label_yes; ?>
                </label>
                <label class="radio-inline">
                    <input type="radio" name="brewerStaff" value="N" id="brewerStaff_1" <?php if (($msg > 0) && (isset($_COOKIE['brewerStaff'])) && ($_COOKIE['brewerStaff'] == "N")) echo "CHECKED"; if ($msg == "default") echo "CHECKED"; ?>> <?php echo $label_no; ?>
                </label>
            </div>
            <span class="help-block"><?php echo $brewer_text_021; ?></span>
        </div>
    </div><!-- ./Form Group -->
    <?php } // END if (($_SESSION['prefsProEdition'] == 0) || (($_SESSION['prefsProEdition'] == 1) && (($go == "judge") || ($go == "steward"))))?>

    <?php if (!$judge_hidden) {

        $judge_checked_yes = FALSE;
        $judge_checked_no = FALSE;
        $judge_disabled = FALSE;

        if (($msg > 0) && (isset($_COOKIE['brewerJudge']))) {
            if ($_COOKIE['brewerJudge'] == "Y") $judge_checked_yes = TRUE;
            if ($_COOKIE['brewerJudge'] == "N") $judge_checked_no = TRUE;
        }

        if ($msg != "4") $judge_checked_yes = TRUE;
        if ($go == "steward") $judge_checked_no = TRUE;
        if ($go == "judge") $judge_disabled = TRUE;

    ?>
    <!-- Show Judge Fields if Registering as a Judge -->
    <div class="form-group"><!-- Form Group REQUIRED Radio Group -->
        <label for="" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_judging; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <p><?php echo $brewer_text_006; ?></p>
            <div class="input-group">
                <!-- Input Here -->
                <label class="radio-inline">
                    <input type="radio" name="brewerJudge" value="Y" id="brewerJudge_0"  <?php if ($judge_checked_yes) echo "CHECKED"; ?> rel="judge_no" /> <?php echo $label_yes; ?>
                </label>
                <label class="radio-inline">
                    <input type="radio" name="brewerJudge" value="N" id="brewerJudge_1" <?php if ($judge_checked_no) echo "CHECKED"; if ($judge_disabled) echo "DISABLED"; ?> rel="none" /> <?php echo $label_no; ?>
                </label>
            </div>
        </div>
    </div><!-- ./Form Group -->
    <div class="form-group"><!-- Form Group Text Input -->
        <label for="brewerJudgeID" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_bjcp_id; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <!-- Input Here -->
            <input class="form-control" id="brewerJudgeID" name="brewerJudgeID" type="text" value="<?php if (($msg > 0) && (isset($_COOKIE['brewerJudgeID']))) echo $_COOKIE['brewerJudgeID']; ?>" placeholder="">
        </div>
    </div><!-- ./Form Group -->
    <div class="form-group"><!-- Form Group Radio STACKED -->
            <label for="brewerJudgeRank" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php if (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant")) echo $label_contact." "; echo $label_bjcp_rank; ?></label>
            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                <div class="input-group">
                    <!-- Input Here -->
                    <div class="radio">
                        <label>
                            <input type="radio" name="brewerJudgeRank" value="Non-BJCP" checked> Non-BJCP
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="brewerJudgeRank" value="Rank Pending"> Rank Pending
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="brewerJudgeRank" value="Apprentice"> Apprentice
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                             <input type="radio" name="brewerJudgeRank" value="Provisional"> Provisional
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="brewerJudgeRank" value="Recognized"> Recognized
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="brewerJudgeRank" value="Certified"> Certified
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="brewerJudgeRank" value="National"> National
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                             <input type="radio" name="brewerJudgeRank" value="Master"> Master
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="brewerJudgeRank" value="Honorary Master"> Honorary Master
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="brewerJudgeRank" value="Grand Master"> Grand Master
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="brewerJudgeRank" value="Honorary Grand Master" <?php if (($action == "edit") && in_array("Honorary Grand Master",$judge_array)) echo "CHECKED"; ?>>Honorary Grand Master
                        </label>
                    </div>
                </div>

            </div>
        </div><!-- ./Form Group -->
    <?php if ($totalRows_judging > 1) {
	if ($action == "edit") $judging_locations = explode(",",$row_brewer['brewerJudgeLocation']);
	elseif (isset($_COOKIE['brewerJudgeLocation'])) $judging_locations = explode(",",$_COOKIE['brewerJudgeLocation']);
	else $judging_locations = array("","");
	?>
    <div class="form-group"><!-- Form Group REQUIRED Radio Group -->
        <label for="" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_judging_avail; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
        <?php do { ?>
            <div class="well well-sm">
            <p><?php echo $row_judging3['judgingLocName']." ("; echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging3['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time").")"; ?></p>
            <div class="input-group input-group-sm">
                <!-- Input Here -->
                <select class="selectpicker" name="brewerJudgeLocation[]" id="brewerJudgeLocation" data-width="auto">
                    <option value="<?php echo "Y-".$row_judging3['id']; ?>" <?php if (in_array("Y-".$row_judging3['id'],$judging_locations)) echo "SELECTED"; ?>><?php echo $label_yes; ?></option>
                    <option value="<?php echo "N-".$row_judging3['id']; ?>" <?php if (in_array("N-".$row_judging3['id'],$judging_locations)) echo "SELECTED"; ?>><?php echo $label_no; ?></option>

                </select>
            </div>
            </div>
        <?php }  while ($row_judging3 = mysqli_fetch_assoc($judging3)); ?>
        </div>
    </div><!-- ./Form Group -->
    <?php } // END if if ($totalRows_judging > 1)
	else { ?><input name="brewerJudgeLocation" type="hidden" value="<?php echo "Y-".$row_judging3['id']; ?>" /><?php } ?>
    <?php } // END if (!$judge_hidden) ?>
    <?php if (!$steward_hidden) {

        $steward_checked_yes = FALSE;
        $steward_checked_no = FALSE;
        $steward_disabled = FALSE;

        if (($msg > 0) && (isset($_COOKIE['brewerSteward']))) {
            if ($_COOKIE['brewerSteward'] == "Y") $steward_checked_yes = TRUE;
            if ($_COOKIE['brewerSteward'] == "N") $steward_checked_no = TRUE;
        }

        if ($msg != "4") $steward_checked_yes = TRUE;
        if ($go == "judge") $steward_checked_no = TRUE;
        if ($go == "steward") $steward_disabled = TRUE;

    ?>
    <!-- Show Steward Fields if Registering as a Judge -->
    <div class="form-group"><!-- Form Group REQUIRED Radio Group -->
        <label for="" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_stewarding; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <p><?php echo $brewer_text_015; ?></p>
            <div class="input-group">
                <!-- Input Here -->
                <label class="radio-inline">
                    <input type="radio" name="brewerSteward" value="Y" id="brewerSteward_0" <?php if ($steward_checked_yes) echo "CHECKED"; ?> rel="steward_no" /><?php echo $label_yes; ?>
                </label>
                <label class="radio-inline">
                    <input type="radio" name="brewerSteward" value="N" id="brewerSteward_1" <?php if ($steward_checked_no) echo "CHECKED"; if ($steward_disabled) echo "DISABLED"; ?> rel="none" /> <?php echo $label_no; ?>
                </label>
            </div>
        </div>
    </div><!-- ./Form Group -->
    <div class="form-group"><!-- Form Group Text Input -->
        <label for="brewerJudgeID" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_bjcp_id; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <!-- Input Here -->
            <input class="form-control" id="brewerJudgeID" name="brewerJudgeID" type="text" value="<?php if (($msg > 0) && (isset($_COOKIE['brewerJudgeID']))) echo $_COOKIE['brewerJudgeID']; ?>" placeholder="">
        </div>
    </div><!-- ./Form Group -->
	<?php if ($totalRows_judging > 1) {
	if ($action == "edit") $stewarding_locations = explode(",",$row_brewer['brewerStewardLocation']);
	elseif (isset($_COOKIE['brewerStewardLocation'])) $stewarding_locations = explode(",",$_COOKIE['brewerStewardLocation']);
	else $stewarding_locations = array("","");
	?>
    <div class="form-group"><!-- Form Group REQUIRED Radio Group -->
        <label for="" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_stewarding_avail; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
        <?php do { ?>
            <div class="well well-sm">
            <p><?php echo $row_stewarding['judgingLocName']." ("; echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_stewarding['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time").")"; ?></p>
            <div class="input-group input-group-sm">
                <!-- Input Here -->
                <select class="selectpicker" name="brewerStewardLocation[]" id="brewerStewardLocation" data-width="auto">
                    <option value="<?php echo "Y-".$row_stewarding['id']; ?>" <?php if (in_array("Y-".$row_stewarding['id'],$stewarding_locations)) echo "SELECTED"; ?>><?php echo $label_yes; ?></option>
                    <option value="<?php echo "N-".$row_stewarding['id']; ?>" <?php if (in_array("N-".$row_stewarding['id'],$stewarding_locations)) echo "SELECTED"; ?>><?php echo $label_no; ?></option>
                </select>
            </div>
            </div>
        <?php }  while ($row_stewarding = mysqli_fetch_assoc($stewarding));  ?>
        </div>
    </div><!-- ./Form Group -->
	<?php } // END if ($totalRows_judging > 1)
	else { ?>
   	<input name="brewerStewardLocation" type="hidden" value="<?php echo "Y-".$row_judging3['id']; ?>" />
   	<?php } // END else ?>
    <?php } // END if (!$steward_hidden) ?>
    <?php if (((!$judge_hidden) || (!$steward_hidden)) && ($section != "admin")) { ?>
    <!-- Show Waiver -->
    <div class="form-group"><!-- Form Group REQUIRED Radio Group -->
        <label for="brewerJudgeWaiver" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_waiver; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <div class="checkbox">
                <!-- Input Here -->
                <p><?php echo $brewer_text_016; ?></p>
                <label>
                    <input type="checkbox" name="brewerJudgeWaiver" value="Y" id="brewerJudgeWaiver_0" checked data-error="<?php echo $register_text_034; ?>" required /><?php echo $brewer_text_018; ?>
                </label>
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div><!-- ./Form Group -->
    <?php } // END if (((!$judge_hidden) || (!$steward_hidden)) && ($section != "admin")) ?>
    <?php if ($_SESSION['prefsCAPTCHA'] == "1") { ?>
    <!-- CAPTCHA -->
	<div class="form-group">
		<label for="recaptcha" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label">CAPTCHA</label>
		<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
			<div class="input-group">
				<!-- Input Here -->
                <div class="g-recaptcha" data-sitekey="<?php echo $public_captcha_key; ?>"></div>
			</div>
		</div>
	</div><!-- Form Group -->
    <?php } ?>
	<!-- Register Button -->
	<div class="form-group">
		<div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-4">
			<!-- Input Here -->
			<button name="submit" type="submit" class="btn btn-primary" >Register</button>
		</div>
	</div><!-- Form Group -->
</form>
<script type="text/javascript">
  	$( function () {
  		twitter.screenNameKeyUp();
  		$('#user_screen_name').focus();
	});
</script>
<script src="https://www.google.com/recaptcha/api.js"></script>
<?php } // end else ?>
<?php } // end else ?>