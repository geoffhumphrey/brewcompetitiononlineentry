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
//-->
</script>
<?php 
/**
 * Module:      register.sec.php 
 * Description: This module houses the functionality for new users to set
 *              up their account. 
 * 
 
  <img id="captcha" src="<?php echo $base_url; ?>captcha/securimage_show.php" alt="CAPTCHA Image" style="border: 1px solid #000000;" />
	<p>
    <object type="application/x-shockwave-flash" data="<?php echo $base_url; ?>captcha/securimage_play.swf?audio_file=<?php echo $base_url; ?>captcha/securimage_play.php&amp;bgColor1=#fff&amp;bgColor2=#fff&amp;iconColor=#000&amp;borderWidth=1&amp;borderColor=#000" width="19" height="19">
	<param name="movie" value="<?php echo $base_url; ?>captcha/securimage_play.swf?audio_file=<?php echo $base_url; ?>captcha/securimage_play.php&amp;bgColor1=#fff&amp;bgColor2=#fff&amp;iconColor=#000&amp;borderWidth=1&amp;borderColor=#000" />
	</object>
    &nbsp;Play audio
    </p>
	<p><input type="text" name="captcha_code" size="10" maxlength="6" /><br />Enter the characters above exactly as displayed.</p>
    <p>Can't read the characters?<br /><a href="#" onclick="document.getElementById('captcha').src = '<?php echo $base_url; ?>captcha/securimage_show.php?' + Math.random(); return false">Reload the Captcha Image</a>.</p>
 
 */
/* ---------------- PUBLIC Pages Rebuild Info ---------------------
Beginning with the 1.3.0 release, an effort was begun to separate the programming
layer from the presentation layer for all scripts with this header.
All Public pages have certain variables in common that build the page:
	$warningX = any warnings
  
	$primary_page_info = any information related to the page
	
	$header1_X = an <h2> header on the page
	$header2_X = an <h3> subheader on the page
	
	$page_infoX = the bulk of the information on the page.
	$help_page_link = link to the appropriate page on help.brewcompetition.com
	$print_page_link = the "Print This Page" link
	$competition_logo = display of the competition's logo
	
	$labelX = the various labels in a table or on a form
	$messageX = various messages to display
	
	$print_page_link = "<p><span class='icon'><img src='".$base_url."images/printer.png' border='0' alt='Print' title='Print' /></span><a id='modal_window_link' class='data' href='".$base_url."output/print.php?section=".$section."&amp;action=print' title='Print'>Print This Page</a></p>";
	$competition_logo = "<img src='".$base_url."user_images/".$_SESSION['contestLogo']."' width='".$_SESSION['prefsCompLogoSize']."' style='float:right; padding: 5px 0 5px 5px' alt='Competition Logo' title='Competition Logo' />";
	
Declare all variables empty at the top of the script. Add on later...
	$warning1 = "";
	$primary_page_info = "";
	$header1_1 = "";
	$page_info1 = "";
	$header1_2 = "";
	$page_info2 = "";
	
	etc., etc., etc.
 * ---------------- END Rebuild Info --------------------- */

$warning1 = "";
$warning2 = "";
$primary_page_info = "";
$header1_1 = "";
$page_info1 = "";
$header1_2 = "";
$page_info2 = "";



if ($section == "admin") {
	$you_volunteer = $register_text_000;
}

else {
	$you_volunteer = $register_text_001;
}

if (($registration_open == 2) && ($judge_window_open == 2) && (!$logged_in) || (($logged_in) && ($_SESSION['userLevel'] == 2))) {
	
	$page_info1 .= sprintf("<p class=\"lead\">%s <small>%s</small></p>",$register_text_002,$register_text_003);
	echo $page_info1;
}

else {

include(DB.'judging_locations.db.php');
include(DB.'stewarding.db.php'); 
include(DB.'styles.db.php'); 
include(DB.'brewer.db.php');
require_once(INCLUDES.'recaptchalib.inc.php');
if (NHC) $totalRows_log = $totalRows_entry_count;
else $totalRows_log = $totalRows_log;
if ($go != "default") {
	$country_select = "";
	foreach ($countries as $country) { 
		$country_select .= "<option value=\"".$country."\" ";
		if (($msg > 0) && ($_COOKIE['brewerCountry'] == $country)) $country_select .= "SELECTED";
		$country_select .= ">";
		$country_select .= $country."</option>\n";
     }
	 
	 $random_country = array_rand($countries);
	 $random_country = $countries[$random_country];
	
	include(DB.'dropoff.db.php');
	
	if ($totalRows_dropoff > 0) {
		$dropoff_select = "";
		do { 
    		$dropoff_select .= "<option value=\"".$row_dropoff['id']."\" ";
			if (($action == "edit") && ($row_brewer['brewerDropOff'] == $row_dropoff['id'])) $dropoff_select .= "SELECTED";
			$dropoff_select .= ">";
			$dropoff_select .= $row_dropoff['dropLocationName']."</option>\n";
   		} while ($row_dropoff = mysqli_fetch_assoc($dropoff));
	} 
}

$warning1 .= sprintf("<p class=\"lead\">%s <small>%s</small></p>",$register_text_004,$register_text_005);
$warning2 .= sprintf("<div class=\"alert alert-warning\"><span class=\"fa fa-exclamation-triangle\"> <strong>%s</strong></div>",$register_text_006);

if ($section == "admin") { 
	$header1_1 .= "<p class=\"lead\">";
	if ($view == "quick") $header1_1 .= sprintf("%s ",$register_text_007);
	$header1_1 .= sprintf("%s ",$register_text_008);
	if ($go == "judge") $header1_1 .= sprintf("%s",$register_text_009); 
	else $header1_1 .= sprintf("%s",$register_text_010);
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
// --------------------------------------------------------------
// Display
// --------------------------------------------------------------
if (($section != "admin") && ($action != "print")) echo $warning1;
if (NHC) echo $warning2;
echo $header1_1;
echo $page_info1;
if ($go == "default") {  ?>
<form class="form-horizontal" name="judgeChoice" id="judgeChoice">
	<div class="form-group">
		<label for="judge_steward" class="col-lg-5 col-md-6 col-sm-6 col-xs-12 control-label"><?php echo $label_register_judge; ?></label>
		<div class="col-lg-7 col-md-6 col-sm-6 col-xs-12">
			<div class="input-group">
				<select class="selectpicker" name="judge_steward" id="judge_steward" onchange="jumpMenu('self',this,0)" data-width="auto">
					<option value=""></option>
					<option value="<?php echo build_public_url("register","judge","default","default",$sef,$base_url); ?>"><?php echo $label_yes; ?></option>
					<option value="<?php echo build_public_url("register","entrant","default","default",$sef,$base_url); ?>"><?php echo $label_no; ?></option>
				</select>
			</div>
		</div>
	</div><!-- Form Group -->	
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=list","default",$msg,$id); ?>">
<?php } ?>
</form>
<?php } else { ?> 
<?php if ($section == "admin") { ?>
<div class="bcoem-admin-element hidden-print">
    <!-- All Participants Button -->
    <div class="btn-group" role="group" aria-label="...">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants"><span class="fa fa-arrow-circle-left"></span> <?php echo $label_all_participants; ?></a>
        
    </div><!-- ./button group -->
    <!-- All Participants Button -->
    <div class="btn-group" role="group" aria-label="...">
        <?php if ($view == "quick") { ?>
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judge&amp;action=register"><span class="fa fa-plus-circle"></span> <?php echo $label_register_judge_standard; ?></a>
        <?php } ?>
        <?php if ($view == "default") { ?>
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judge&amp;action=register&amp;view=quick"><span class="fa fa-plus-circle"></span> <?php echo $label_register_judge_quick; ?></a>
        <?php } ?>
    </div><!-- ./button group -->
</div>
<?php } ?>
<form data-toggle="validator" role="form" class="form-horizontal" action="<?php echo $base_url; ?>includes/process.inc.php?action=add&amp;dbTable=<?php echo $users_db_table; ?>&amp;section=register&amp;go=<?php echo $go; if ($section == "admin") echo "&amp;filter=admin"; echo "&amp;view=".$view; ?>" method="POST" name="form1" id="form1">
<?php if ($section == "admin") { ?>
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
    <?php } // end if ($view == "quick")?>
<?php } // end if ($section == "admin") ?>
    <input type="hidden" name="userLevel" value="2" />
    <?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
    <input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
    <?php } else { ?>
    <input type="hidden" name="relocate" value="<?php echo relocate($base_url,"default",$msg,$id); ?>">
   	<?php } ?>
    <input type="hidden" name="IP" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>" />
<?php if ($go == "entrant") { ?>
    <input type="hidden" name="brewerJudge" value="N" />
    <input type="hidden" name="brewerSteward" value="N" />
<?php } ?>
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_email; ?></label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="email-addon1"><span class="fa fa-envelope"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="user_name" id="user_name" type="email" placeholder="<?php echo $register_text_021; ?>" data-error="<?php echo $register_text_019; ?>" onBlur="checkAvailability()" onkeyup="twitter.updateUrl(this.value)" onchange="AjaxFunction(this.value);" value="<?php if ($msg > 0) echo $_COOKIE['user_name']; ?>" required autofocus>
				<span class="input-group-addon" id="email-addon2"><span class="fa fa-star"></span>
			</div>
            <div class="help-block with-errors"></div>
			<div id="msg_email"></div>
			<div id="status"></div>
		</div>
	</div><!-- ./Form Group -->
	<?php if ($view == "default") { // Show if not using quick add judge/steward feature ?>
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_re_enter." ".$label_email; ?></label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="re-enter-email-addon1"><span class="fa fa-envelope"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="user_name2" type="email" placeholder="" id="user_name2" data-match="#user_name" data-error="<?php echo $register_text_019; ?>"; data-match-error="<?php echo $register_text_020; ?>" value="<?php if ($msg > 0) echo $_COOKIE['user_name2']; ?>" required>
				<span class="input-group-addon" id="re-enter-email-addon2"><span class="fa fa-star"></span>
			</div>
            <div class="help-block with-errors"></div>
		</div>
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_password; ?></label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="password-addon1"><span class="fa fa-key"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="password" id="password" type="password" placeholder="Password" value="<?php if ($msg > 0) echo $_COOKIE['password']; ?>" data-error="<?php echo $register_text_022; ?>" required>
				<span class="input-group-addon" id="password-addon2"><span class="fa fa-star"></span></span>
			</div>
            <div class="help-block with-errors"></div>
		</div>
	</div><!-- ./Form Group -->
	<?php } ?>
	<?php if ($section != "admin") { ?>
	<div class="form-group"><!-- Form Group REQUIRED Radio Group -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_security_question; ?></label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<div class="input-group">
				<!-- Input Here -->
				<div class="radio">
					<label>
						<input type="radio" name="userQuestion" id="userQuestion_0" value="What is your favorite all-time beer to drink?" <?php if (($msg > 0) && ($_COOKIE['userQuestion'] == "What is your favorite all-time beer to drink?")) echo "CHECKED"; if ($msg == "default") echo "CHECKED"; ?>>
						<?php echo $label_secret_01; ?>
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="userQuestion" id="userQuestion_1" value="What was the name of your first pet?" <?php if (($msg > 0) && ($_COOKIE['userQuestion'] == "What was the name of your first pet?")) echo "CHECKED"; ?>>
						<?php echo $label_secret_02; ?>
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="userQuestion" id="userQuestion_2" value="What was the name of the street you grew up on?" <?php if (($msg > 0) && ($_COOKIE['userQuestion'] == "What was the name of the street you grew up on?")) echo "CHECKED"; ?>>
						<?php echo $label_secret_03; ?>
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="userQuestion" id="userQuestion_3" value="What was your high school mascot?" <?php if (($msg > 0) && ($_COOKIE['userQuestion'] == "What was your high school mascot?")) echo "CHECKED"; ?>>
						<?php echo $label_secret_04; ?>
					</label>
				</div>
			</div>
            <span class="help-block"><?php echo $register_text_018; ?></span>
		</div>
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_security_answer; ?></label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="security-question-answer-addon1"><span class="fa fa-bullhorn"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="userQuestionAnswer" id="userQuestionAnswer" type="text" placeholder="" value="<?php if ($msg > 0) echo $_COOKIE['userQuestionAnswer']; ?>" data-error="<?php echo $register_text_023; ?>" required>
				<span class="input-group-addon" id="security-question-answer-addon2"><span class="fa fa-star"></span>
			</div>
            <div class="help-block with-errors"><?php echo $register_text_024; ?></div>
		</div>
	</div><!-- ./Form Group -->
	<?php } ?>
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_first_name; ?></label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			
			<div class="input-group has-warning">
				<span class="input-group-addon" id="first-name-addon1"><span class="fa fa-user"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="brewerFirstName" id="brewerFirstName" type="text" placeholder="" value="<?php if ($msg > 0) echo $_COOKIE['brewerFirstName']; ?>" data-error="<?php echo $register_text_025; ?>" required>
				<span class="input-group-addon" id="first-name-addon2"><span class="fa fa-star"></span>
			</div>
            <div class="help-block with-errors"></div>
		</div>
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_last_name; ?></label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="last-name-addon1"><span class="fa fa-user"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="brewerLastName" id="brewerLastName" type="text" placeholder="" value="<?php if ($msg > 0) echo $_COOKIE['brewerLastName']; ?>" data-error="<?php echo $register_text_026; ?>" required>
				<span class="input-group-addon" id="last-name-addon2"><span class="fa fa-star"></span>
			</div>
            <div class="help-block with-errors"></div>
            <?php if ($section != "admin") { ?><span id="helpBlock" class="help-block"><?php echo $brewer_text_000; ?></span><?php } ?>
		</div>
	</div><!-- ./Form Group -->
    
    <?php if ($view == "quick") { ?>
    
    <div class="form-group"><!-- Form Group Text Input -->
        <label for="brewerJudgeID" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_bjcp_id; ?></label>
        <div class="col-lg-10 col-md-6 col-sm-8 col-xs-12">
            <!-- Input Here -->
            <input class="form-control" id="brewerJudgeID" name="brewerJudgeID" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerJudgeID']; ?>" placeholder="">
        </div>
    </div><!-- ./Form Group -->
    
    <div class="form-group"><!-- Form Group Radio STACKED -->
            <label for="brewerJudgeRank" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_bjcp_rank; ?></label>
            <div class="col-lg-10 col-md-6 col-sm-8 col-xs-12">
                <div class="input-group">
                    <!-- Input Here -->
                    <div class="radio">
                        <label>
                            <input type="radio" name="brewerJudgeRank" value="Novice" checked> Novice
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
    
    <?php } ?>
	<?php if ($view == "default") { ?>
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_street_address; ?></label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="street-addon1"><span class="fa fa-home"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="brewerAddress" id="brewerAddress" type="text" placeholder="" value="<?php if ($msg > 0) echo $_COOKIE['brewerAddress']; ?>" data-error="<?php echo $register_text_028; ?>" required>
				<span class="input-group-addon" id="street-addon2"><span class="fa fa-star"></span>
			</div>
            <div class="help-block with-errors"></div>
		</div>
        
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_city; ?></label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="city-addon1"><span class="fa fa-home"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="brewerCity" id="brewerCity" type="text" placeholder="" value="<?php if ($msg > 0) echo $_COOKIE['brewerCity']; ?>" data-error="<?php echo $register_text_029; ?>" required>
				<span class="input-group-addon" id="city-addon2"><span class="fa fa-star"></span>
			</div>
            <div class="help-block with-errors"></div>
		</div>
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_state_province; ?></label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="state-addon1"><span class="fa fa-home"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="brewerState" id="brewerState" type="text" placeholder="" value="<?php if ($msg > 0) echo $_COOKIE['brewerState']; ?>" data-error="<?php echo $register_text_030; ?>" required>
				<span class="input-group-addon" id="state-addon2"><span class="fa fa-star"></span>
			</div>
            <div class="help-block with-errors"></div>
		</div>
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_zip; ?></label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="zip-addon1"><span class="fa fa-home"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="brewerZip" id="brewerZip" type="text" placeholder="" value="<?php if ($msg > 0) echo $_COOKIE['brewerZip']; ?>" data-error="<?php echo $register_text_031; ?>" required>
				<span class="input-group-addon" id="zip-addon2"><span class="fa fa-star"></span>
			</div>
            <div class="help-block with-errors"></div>
		</div>
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group REQUIRED Select -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_country; ?></label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12 has-warning">
		<!-- Input Here -->
		<select class="selectpicker" name="brewerCountry" id="brewerCountry" data-live-search="true" data-size="10" data-width="auto">
    		<?php echo $country_select; ?>
    	</select>
		</div>
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group REQUIRED Select -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_drop_off; ?></label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12 has-warning">
			<!-- Input Here -->
			<select class="selectpicker" name="brewerDropOff" id="brewerDropOff" data-size="10" data-width="auto">
				<option value="0"><?php echo $brewer_text_005; ?></option> 
				<option disabled="disabled">-------------</option>
				<?php echo $dropoff_select; ?>
			</select>
		
		</div>
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_phone_primary; ?></label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="phone1-addon1"><span class="fa fa-phone"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="brewerPhone1" id="brewerPhone1" type="tel" placeholder="" value="<?php if ($msg > 0) echo $_COOKIE['brewerPhone1']; ?>" data-error="<?php echo $register_text_032; ?>" required>
				<span class="input-group-addon" id="phone1-addon2"><span class="fa fa-star"></span>
			</div>
            <div class="help-block with-errors"></div>
		</div>
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group Text Input -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_phone_secondary; ?></label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<div class="input-group">
				<span class="input-group-addon" id="phone2-addon1"><span class="fa fa-phone"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="brewerPhone2" id="brewerPhone2" type="tel" placeholder="" value="<?php if ($msg > 0) echo $_COOKIE['brewerPhone2']; ?>">
			</div>
		</div>
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group Text Input -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_club; ?></label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<div class="input-group">
				<span class="input-group-addon" id="club-addon1"><span class="fa fa-bullhorn"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="brewerClubs" id="brewerClubs" type="text" placeholder="" value="<?php if ($msg > 0) echo $_COOKIE['brewerClubs']; ?>">
			</div>
		</div>
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group Text Input -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_aha_number; ?></label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<div class="input-group">
				<span class="input-group-addon" id="aha-addon1"><span class="fa fa-beer"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="brewerAHA" id="brewerAHA" type="number" placeholder="" value="<?php if ($msg > 0) echo $_COOKIE['brewerAHA']; ?>">
			</div>
            <div class="help-block"><?php echo $register_text_033; ?></div>
		</div>
	</div><!-- ./Form Group -->
    <?php } ?>
	
	<?php if ($go != "entrant") { ?>
	<?php if (!$judge_limit) { ?>
    
	<div class="form-group"><!-- Form Group REQUIRED Radio Group -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_judging; ?></label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<p><?php echo $brewer_text_006; ?></p>
			<div class="input-group">
				<!-- Input Here -->
				<label class="radio-inline">
					<input type="radio" name="brewerJudge" value="Y" id="brewerJudge_0"  <?php if ($msg != "4") echo "CHECKED"; if (($msg > 0) && ($_COOKIE['brewerJudge'] == "Y")) echo "CHECKED"; ?> rel="judge_no" /> <?php echo $label_yes; ?>
				</label>
				<label class="radio-inline">
					<input type="radio" name="brewerJudge" value="N" id="brewerJudge_1" <?php if (($msg > 0) && ($_COOKIE['brewerJudge'] == "N")) echo "CHECKED"; ?> rel="none" /> <?php echo $label_no; ?>
				</label>
			</div>
		</div>
	</div><!-- ./Form Group -->
    
	<?php if ($totalRows_judging > 1) { ?>
    
	<div class="form-group"><!-- Form Group REQUIRED Radio Group -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_judging_avail; ?></label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
		<?php do { ?>
			<div class="well well-sm">
			<p><?php echo $row_judging3['judgingLocName']." ("; echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging3['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time").")"; ?></p>
			<div class="input-group input-group-sm">
				<!-- Input Here -->
				<select class="selectpicker" name="brewerJudgeLocation[]" id="brewerJudgeLocation" data-width="auto">
                    <option value="<?php echo "N-".$row_judging3['id']; ?>"   <?php if ($action == "edit") { $a = explode(",", $row_brewer['brewerJudgeLocation']); $b = "N-".$row_judging3['id']; foreach ($a as $value) { if ($value == $b) { echo "SELECTED"; } } } ?>><?php echo $label_no; ?></option>
                    <option value="<?php echo "Y-".$row_judging3['id']; ?>"   <?php if ($action == "edit") { $a = explode(",", $row_brewer['brewerJudgeLocation']); $b = "Y-".$row_judging3['id']; foreach ($a as $value) { if ($value == $b) { echo "SELECTED"; } } } ?>><?php echo $label_yes; ?></option>
                </select>
			</div>
			</div>
		<?php }  while ($row_judging3 = mysqli_fetch_assoc($judging3)); ?>
		</div>
	</div><!-- ./Form Group -->
    
	<?php } // end if (!$judge_limit) ?>
	<?php } // end if ($go != "entrant") ?>
	<?php if (!$steward_limit) { ?>
	<div class="form-group"><!-- Form Group REQUIRED Radio Group -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_stewarding; ?></label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<p><?php echo $brewer_text_015; ?></p>
			<div class="input-group">
				<!-- Input Here -->
				<label class="radio-inline">
					<input type="radio" name="brewerSteward" value="Y" id="brewerSteward_0" <?php if ($msg != "4") echo "CHECKED"; if (($msg > 0) && ($_COOKIE['brewerSteward'] == "Y")) echo "CHECKED"; ?> rel="steward_no" /><?php echo $label_yes; ?>
				</label>
				<label class="radio-inline">
					<input type="radio" name="brewerSteward" value="N" id="brewerSteward_1" <?php if (($msg > 0) && ($_COOKIE['brewerSteward'] == "N")) echo "CHECKED"; ?> rel="none" /> <?php echo $label_no; ?>
				</label>
			</div>
		</div>
	</div><!-- ./Form Group -->
    
	<?php if ($totalRows_judging > 1) { ?>
	<div class="form-group"><!-- Form Group REQUIRED Radio Group -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_stewarding_avail; ?></label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
		<?php do { ?>
			<div class="well well-sm">
			<p><?php echo $row_stewarding['judgingLocName']." ("; echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_stewarding['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time").")"; ?></p>
			<div class="input-group input-group-sm">
				<!-- Input Here -->
				<select class="selectpicker" name="brewerStewardLocation[]" id="brewerStewardLocation" data-width="auto">
					<option value="<?php echo "N-".$row_stewarding['id']; ?>" <?php if ($action == "edit") { $a = explode(",", $row_brewer['brewerStewardLocation']); $b = "N-".$row_stewarding['id']; foreach ($a as $value) { if ($value == $b) { echo "SELECTED"; } } } ?>><?php echo $label_no; ?></option>
					<option value="<?php echo "Y-".$row_stewarding['id']; ?>" <?php if ($action == "edit") { $a = explode(",", $row_brewer['brewerStewardLocation']); $b = "Y-".$row_stewarding['id']; foreach ($a as $value) { if ($value == $b) { echo "SELECTED"; } } } ?>><?php echo $label_yes; ?></option>
				</select>
			</div>
			</div>
		<?php }  while ($row_stewarding = mysqli_fetch_assoc($stewarding));  ?>
		</div>
	</div><!-- ./Form Group -->
	<?php } // end if ($totalRows_judging > 1) 
		else { ?>
        <input name="brewerJudgeLocation" type="hidden" value="<?php echo "Y-".$row_judging3['id']; ?>" />
        <input name="brewerStewardLocation" type="hidden" value="<?php echo "Y-".$row_judging3['id']; ?>" />
    <?php } ?>
    <?php } // end if (!$steward_limit) ?>
    
    <?php if (($go != "entrant") && ($section != "admin")) { ?>
    <div class="form-group"><!-- Form Group REQUIRED Radio Group -->
		<label for="brewerJudgeWaiver" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_waiver; ?></label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
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
    <?php } ?>
<?php } // end if ($go != "entrant") ?>	
	<?php if ($section != "admin") { ?>
   	<!-- <script src="https://www.google.com/recaptcha/api.js"></script> -->
	<div class="form-group">
		<label for="recaptcha" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">CAPTCHA</label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<div class="input-group">
				<!-- Input Here -->
                <!-- <div class="g-recaptcha" data-sitekey="6LdUsBATAAAAAEJYbnqmygjGK-S6CHCoGcLALg5W"></div> -->
				<?php echo recaptcha_get_html($publickey, null, true); ?>
			</div>
		</div>
	</div><!-- Form Group -->
    <?php } ?>
	<div class="form-group">
		<div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-4">
			<!-- Input Here -->
			<button name="submit" type="submit" class="btn btn-primary" >Register</button>
		</div>
	</div><!-- Form Group -->
<script type="text/javascript">
  	$( function () {
  		twitter.screenNameKeyUp();
  		$('#user_screen_name').focus();
	});
</script>
</form>
<?php } 
}
?>