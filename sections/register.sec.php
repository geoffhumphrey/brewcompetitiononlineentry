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

if (($registration_open == 2) && (!$logged_in) || (($logged_in) && ($_SESSION['user_level'] == 2))) {
	
	$page_info1 .= "<p class=\"lead\">Account registration has closed. <span class=\"small\">Thank you for your interest.</p>";
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
	
	foreach ($countries as $country) { 
		$country_select .= "<option value='".$country."' ";
		if (($msg > 0) && ($_COOKIE['brewerCountry'] == $country)) $country_select .= "SELECTED";
		$country_select .= ">";
		$country_select .= $country."</option>";
     }
	 
	 $random_country = array_rand($countries);
	 $random_country = $countries[$random_country];
	
	include(DB.'dropoff.db.php');
	
	if ($totalRows_dropoff > 0) {
		$dropoff_select = "";
		do { 
    		$dropoff_select .= "<option value='".$row_dropoff['id']."' ";
			if (($action == "edit") && ($row_brewer['brewerDropOff'] == $row_dropoff['id'])) $dropoff_select .= "SELECTED";
			$dropoff_select .= ">";
			$dropoff_select .= $row_dropoff['dropLocationName']."</option>";
   		} while ($row_dropoff = mysql_fetch_assoc($dropoff));
	} 
}

$warning1 .= "<p class=\"lead\">The information you provide beyond your first name, last name, and club is strictly for record-keeping and contact purposes. <small>A condition of entry into the competition is providing this information. Your name and club may be displayed should one of your entries place, but no other information will be made public.</small></p>";
$warning2 .= "<div class=\"alert alert-warning\"><strong>Reminder:</strong> You are only allowed to enter one region and once you have registered at a location, you will NOT be able to change it.</div>";
$header1_1 .= "<p class=\"lead\">";
if ($view == "quick") $header1_1 .= "Quick ";
$header1_1 .= "Register ";
if ($section == "admin") { 
	if ($go == "judge") $header1_1 .= "a Judge/Steward"; 
	else $header1_1 .= "a Participant"; 
	}
else $header1_1 .= "Your Account";
$header1_1 .= "</p>";
if (($go != "default") && ($section != "admin")) $page_info1 .= "<p>To register for the competition, create your online account by filling out the form below.</p>";
if ($view == "quick") $page_info1 .= "<p>Quickly add a participant to the competition&rsquo;s judge/steward pool. A dummy address and phone number will be used and a default password of <em>bcoem</em> will be given to each participant added via this screen.</p>";
if ((($registration_open < 2) || ($judge_window_open < 2)) && ($go == "default") && ($section != "admin") && (!$comp_entry_limit)) {
	$page_info1 .= "<p>Entry into this competition is conducted completely online.</p>";
	$page_info1 .= "<ul>";
	if (!NHC) {
		$page_info1 .= "<li>If you have already registered, <a href='".build_public_url("login","default","default","default",$sef,$base_url)."'>log in here</a>.</li>";
		$page_info1 .= "<li>To add your entries and/or indicate that you are willing to judge or steward, you will need to create an account on our system using the fields below.</li>";
	}
	$page_info1 .= "<li>Your email address will be your user name and will be used as a means of information dissemination by the competition staff. Please make sure it is correct. </li>";
	if ((!NHC) || ((NHC) && ($prefix != "final_"))) {
		$page_info1 .= "<li>Once you have registered, you can proceed through the entry process. </li>";
		$page_info1 .= "<li>Each entry you add will automatically be assigned a number by the system.</li>";
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
if ($go == "default") { ?>
<form class="form-horizontal" name="judgeChoice" id="judgeChoice">
	<div class="form-group">
		<label for="judge_steward" class="col-lg-5 col-md-6 col-sm-6 col-xs-12 control-label">Are You Registering as a Judge or Steward?</label>
		<div class="col-lg-7 col-md-6 col-sm-6 col-xs-12">
			<div class="input-group">
				<select class="selectpicker" name="judge_steward" id="judge_steward" onchange="jumpMenu('self',this,0)">
					<option value=""></option>
					<option value="<?php echo build_public_url("register","judge","default","default",$sef,$base_url); ?>">Yes</option>
					<option value="<?php echo build_public_url("register","entrant","default","default",$sef,$base_url); ?>">No</option>
				</select>
			</div>
		</div>
	</div><!-- Form Group -->	
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">

</form>
<?php } else { ?> 
<?php if ($section == "admin") { ?>
<div class="bcoem-admin-element hidden-print">
    <!-- All Participants Button -->
    <div class="btn-group" role="group" aria-label="...">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants"><span class="fa fa-arrow-circle-left"></span> All Participants</a>
        
    </div><!-- ./button group -->
    <!-- All Participants Button -->
    <div class="btn-group" role="group" aria-label="...">
        <?php if ($view == "quick") { ?>
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judge&amp;action=register"><span class="fa fa-plus-circle"></span> Register a Judge or Steward (Standard)</a>
        <?php } ?>
        <?php if ($view == "default") { ?>
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judge&amp;action=register&amp;view=quick"><span class="fa fa-plus-circle"></span> Register a Judge or Steward (Quick)</a>
        <?php } ?>
    </div><!-- ./button group -->
</div>
<?php } ?>
<form data-toggle="validator" role="form" class="form-horizontal" action="<?php echo $base_url; ?>includes/process.inc.php?action=add&amp;dbTable=<?php echo $users_db_table; ?>&amp;section=register&amp;go=<?php echo $go; if ($section == "admin") echo "&amp;filter=admin"; echo "&amp;view=".$view; ?>" method="POST" name="form1" id="form1">
<?php if ($view == "quick") { ?>
    <input type="hidden" name="password" value="bcoem">
    <input type="hidden" name="userQuestion" value="Randomly generated. Use the email function to recover your password.">
    <input type="hidden" name="userQuestionAnswer" value="<?php echo random_generator(7,2); ?>">
    <input type="hidden" name="brewerAddress" value="1234 Main Street">
    <input type="hidden" name="brewerCity" value="Anytown">
    <input type="hidden" name="brewerState" value="CO">
    <input type="hidden" name="brewerZip" value="80000">
    <input type="hidden" name="brewerCountry" value="<?php echo $random_country; ?>">
    <input type="hidden" name="brewerPhone1" value="1234567890">
    <input type="hidden" name="brewerJudge" value="Y">
    <input type="hidden" name="brewerSteward" value="Y">
    <?php if ($totalRows_judging > 1) { ?>
    <?php do { ?>
    <input type="hidden" name="brewerJudgeLocation[]" value="<?php echo "Y-".$row_judging3['id']; ?>">
	<?php } while ($row_judging3 = mysql_fetch_assoc($judging3)); ?>
    <?php do { ?>
    <input type="hidden" name="brewerStewardLocation[]" value="<?php echo "Y-".$row_stewarding['id']; ?>">
    <?php } while ($row_stewarding = mysql_fetch_assoc($stewarding)); ?>
    <?php } // end if ($totalRows_judging > 1) ?>
<?php } ?>
<?php if ($section != "admin") { ?>
	<input type="hidden" name="userQuestion" value="What is your favorite all-time beer to drink?">
	<input type="hidden" name="userQuestionAnswer" value="<?php echo random_generator(7,2); ?>">
<?php } ?>
    <input type="hidden" name="userLevel" value="2" />
    <input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
    <input type="hidden" name="IP" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>" />
<?php if ($go == "entrant") { ?>
    <input type="hidden" name="brewerJudge" value="N" />
    <input type="hidden" name="brewerSteward" value="N" />
<?php } ?>

	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Email Address</label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="email-addon1"><span class="fa fa-envelope"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="user_name" id="user_name" type="email" placeholder="Email addresses serve as user names" onBlur="checkAvailability()" onkeyup="twitter.updateUrl(this.value)" onchange="AjaxFunction(this.value);" value="<?php if ($msg > 0) echo $_COOKIE['user_name']; ?>" required autofocus>
				<span class="input-group-addon" id="email-addon2"><span class="fa fa-star"></span>
			</div>
            <div class="help-block with-errors"></div>
			<div id="msg_email"></div>
			<div id="status"></div>
		</div>
	</div><!-- ./Form Group -->
	<?php if ($view == "default") { // Show if not using quick add judge/steward feature ?>
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Re-Enter Email Address</label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="re-enter-email-addon1"><span class="fa fa-envelope"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="user_name2" type="email" placeholder="" id="user_name2" data-match="#user_name" data-match-error="The email addresses you entered don't match." value="<?php if ($msg > 0) echo $_COOKIE['user_name2']; ?>" required>
				<span class="input-group-addon" id="re-enter-email-addon2"><span class="fa fa-star"></span>
			</div>
            <div class="help-block with-errors"></div>
		</div>
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Password</label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="password-addon1"><span class="fa fa-key"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="password" id="password" type="password" placeholder="Password" value="<?php if ($msg > 0) echo $_COOKIE['password']; ?>" required>
				<span class="input-group-addon" id="password-addon2"><span class="fa fa-star"></span>
			</div>
            <div class="help-block with-errors"></div>
		</div>
	</div><!-- ./Form Group -->
	<?php } ?>
	<?php if ($section != "admin") { ?>
	<div class="form-group"><!-- Form Group REQUIRED Radio Group -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Security Question</label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<div class="input-group">
				<!-- Input Here -->
				<div class="radio">
					<label>
						<input type="radio" name="userQuestion" id="userQuestion_0" value="What is your favorite all-time beer to drink?" <?php if (($msg > 0) && ($_COOKIE['userQuestion'] == "What is your favorite all-time beer to drink?")) echo "CHECKED"; if ($msg == "default") echo "CHECKED"; ?>>
						What is your favorite all-time beer to drink?
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="userQuestion" id="userQuestion_1" value="What was the name of your first pet?" <?php if (($msg > 0) && ($_COOKIE['userQuestion'] == "What was the name of your first pet?")) echo "CHECKED"; ?>>
						What was the name of your first pet?
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="userQuestion" id="userQuestion_2" value="What was the name of the street you grew up on?" <?php if (($msg > 0) && ($_COOKIE['userQuestion'] == "What was the name of the street you grew up on?")) echo "CHECKED"; ?>>
						What was the name of the street you grew up on?
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="userQuestion" id="userQuestion_3" value="What was your high school mascot?" <?php if (($msg > 0) && ($_COOKIE['userQuestion'] == "What was your high school mascot?")) echo "CHECKED"; ?>>
						What was your high school mascot?
					</label>
				</div>
			</div>
            <span class="help-block">Choose one. This question will be used to verify your identity should you forget your password.</span>
		</div>
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Security Question Answer</label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="security-question-answer-addon1"><span class="fa fa-bullhorn"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="userQuestionAnswer" id="userQuestionAnswer" type="text" placeholder="" value="<?php if ($msg > 0) echo $_COOKIE['userQuestionAnswer']; ?>" required>
				<span class="input-group-addon" id="security-question-answer-addon2"><span class="fa fa-star"></span>
			</div>
            <div class="help-block with-errors"></div>
		</div>
	</div><!-- ./Form Group -->
	<?php } ?>
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">First Name</label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			
			<div class="input-group has-warning">
				<span class="input-group-addon" id="first-name-addon1"><span class="fa fa-user"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="brewerFirstName" id="brewerFirstName" type="text" placeholder="" value="<?php if ($msg > 0) echo $_COOKIE['brewerFirstName']; ?>" required>
				<span class="input-group-addon" id="first-name-addon2"><span class="fa fa-star"></span>
			</div>
            <div class="help-block with-errors"></div>
		</div>
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Last Name</label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="last-name-addon1"><span class="fa fa-user"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="brewerLastName" id="brewerLastName" type="text" placeholder="" value="<?php if ($msg > 0) echo $_COOKIE['brewerLastName']; ?>" required>
				<span class="input-group-addon" id="last-name-addon2"><span class="fa fa-star"></span>
			</div>
            <div class="help-block with-errors"></div>
            <?php if ($section != "admin") { ?><span id="helpBlock" class="help-block">Please enter only <em>one</em> person's name. You will be able to identify a co-brewer when adding your entries.</span><?php } ?>
		</div>
	</div><!-- ./Form Group -->
    
    <?php if ($view == "quick") { ?>
    
    <div class="form-group"><!-- Form Group Text Input -->
        <label for="brewerJudgeID" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">BJCP ID</label>
        <div class="col-lg-10 col-md-6 col-sm-8 col-xs-12">
            <!-- Input Here -->
            <input class="form-control" id="brewerJudgeID" name="brewerJudgeID" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerJudgeID']; ?>" placeholder="">
        </div>
    </div><!-- ./Form Group -->
    
    <div class="form-group"><!-- Form Group Radio STACKED -->
            <label for="brewerJudgeRank" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">BJCP Rank</label>
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
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Street Address</label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="street-addon1"><span class="fa fa-home"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="brewerAddress" id="brewerAddress" type="text" placeholder="" value="<?php if ($msg > 0) echo $_COOKIE['brewerAddress']; ?>" required>
				<span class="input-group-addon" id="street-addon2"><span class="fa fa-star"></span>
			</div>
            <div class="help-block with-errors"></div>
		</div>
        
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">City</label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="city-addon1"><span class="fa fa-home"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="brewerCity" id="brewerCity" type="text" placeholder="" value="<?php if ($msg > 0) echo $_COOKIE['brewerCity']; ?>" required>
				<span class="input-group-addon" id="city-addon2"><span class="fa fa-star"></span>
			</div>
            <div class="help-block with-errors"></div>
		</div>
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">State or Province</label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="state-addon1"><span class="fa fa-home"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="brewerState" id="brewerState" type="text" placeholder="" value="<?php if ($msg > 0) echo $_COOKIE['brewerState']; ?>" required>
				<span class="input-group-addon" id="state-addon2"><span class="fa fa-star"></span>
			</div>
            <div class="help-block with-errors"></div>
		</div>
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Zip/Postal Code</label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="zip-addon1"><span class="fa fa-home"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="brewerZip" id="brewerZip" type="number" placeholder="" value="<?php if ($msg > 0) echo $_COOKIE['brewerZip']; ?>" required>
				<span class="input-group-addon" id="zip-addon2"><span class="fa fa-star"></span>
			</div>
            <div class="help-block with-errors"></div>
		</div>
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group REQUIRED Select -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Country</label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12 has-warning">
		<!-- Input Here -->
		<select class="selectpicker" name="brewerCountry" id="brewerCountry">
    		<?php echo $country_select; ?>
    	</select>
		</div>
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group REQUIRED Select -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Drop-off Location</label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12 has-warning">
			<!-- Input Here -->
			<select class="selectpicker" name="brewerDropOff" id="brewerDropOff">
				<option value="0">I'm Shipping My Entries</option> 
				<option disabled="disabled">-------------</option>
				<?php echo $dropoff_select; ?>
			</select>
		
		</div>
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Primary Phone #</label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="phone1-addon1"><span class="fa fa-phone"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="brewerPhone1" id="brewerPhone1" type="tel" placeholder="" value="<?php if ($msg > 0) echo $_COOKIE['brewerPhone1']; ?>" required>
				<span class="input-group-addon" id="phone1-addon2"><span class="fa fa-star"></span>
			</div>
            <div class="help-block with-errors"></div>
		</div>
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group Text Input -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Secondary Phone #</label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<div class="input-group">
				<span class="input-group-addon" id="phone2-addon1"><span class="fa fa-phone"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="brewerPhone2" id="brewerPhone2" type="tel" placeholder="" value="<?php if ($msg > 0) echo $_COOKIE['brewerPhone2']; ?>">
			</div>
		</div>
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group Text Input -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Club</label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<div class="input-group">
				<span class="input-group-addon" id="club-addon1"><span class="fa fa-bullhorn"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="brewerClubs" id="brewerClubs" type="text" placeholder="" value="<?php if ($msg > 0) echo $_COOKIE['brewerClubs']; ?>">
			</div>
		</div>
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group Text Input -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">AHA Member #</label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<div class="input-group">
				<span class="input-group-addon" id="aha-addon1"><span class="fa fa-beer"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="brewerAHA" id="brewerAHA" type="number" placeholder="" value="<?php if ($msg > 0) echo $_COOKIE['brewerAHA']; ?>">
			</div>
		</div>
	</div><!-- ./Form Group -->
    <?php } ?>
	<?php if ($go != "entrant") { ?>
    <?php if ($view == "default") { ?>
	<div class="form-group"><!-- Form Group REQUIRED Radio Group -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Judging</label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<p>Are you willing and qualified to serve as a judge in this competition?</p>
			<div class="input-group">
				<!-- Input Here -->
				<label class="radio-inline">
					<input type="radio" name="brewerJudge" value="Y" id="brewerJudge_0"  <?php if ($msg != "4") echo "CHECKED"; if (($msg > 0) && ($_COOKIE['brewerJudge'] == "Y")) echo "CHECKED"; ?> rel="judge_no" /> Yes
				</label>
				<label class="radio-inline">
					<input type="radio" name="brewerJudge" value="N" id="brewerJudge_1" <?php if (($msg > 0) && ($_COOKIE['brewerJudge'] == "N")) echo "CHECKED"; ?> rel="none" /> No
				</label>
			</div>
		</div>
	</div><!-- ./Form Group -->
    <?php } // end if ($view == "default") ?>
	<?php if ($totalRows_judging > 1) { ?>
    <?php if ($view == "default") { ?>
	<div class="form-group"><!-- Form Group REQUIRED Radio Group -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Judging Availability</label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
		<?php do { ?>
			<div class="well well-sm">
			<p><?php echo $row_judging3['judgingLocName']." ("; echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging3['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time").")"; ?></p>
			<div class="input-group input-group-sm">
				<!-- Input Here -->
				<select class="selectpicker" name="brewerJudgeLocation[]" id="brewerJudgeLocation">
                    <option value="<?php echo "N-".$row_judging3['id']; ?>"   <?php $a = explode(",", $row_brewer['brewerJudgeLocation']); $b = "N-".$row_judging3['id']; foreach ($a as $value) { if ($value == $b) { echo "SELECTED"; } } ?>>No</option>
                    <option value="<?php echo "Y-".$row_judging3['id']; ?>"   <?php $a = explode(",", $row_brewer['brewerJudgeLocation']); $b = "Y-".$row_judging3['id']; foreach ($a as $value) { if ($value == $b) { echo "SELECTED"; } } ?>>Yes</option>
                </select>
			</div>
			</div>
		<?php }  while ($row_judging3 = mysql_fetch_assoc($judging3)); ?>
		</div>
	</div><!-- ./Form Group -->
    <?php } // end if ($view == "default") ?>
	<?php } // end if ($totalRows_judging > 1) ?>
    <?php if ($view == "default") { ?>
	<div class="form-group"><!-- Form Group REQUIRED Radio Group -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Stewarding</label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<p>Are you willing to serve as a steward in this competition?</p>
			<div class="input-group">
				<!-- Input Here -->
				<label class="radio-inline">
					<input type="radio" name="brewerSteward" value="Y" id="brewerSteward_0" <?php if ($msg != "4") echo "CHECKED"; if (($msg > 0) && ($_COOKIE['brewerSteward'] == "Y")) echo "CHECKED"; ?> rel="steward_no" />Yes
				</label>
				<label class="radio-inline">
					<input type="radio" name="brewerSteward" value="N" id="brewerSteward_1" <?php if (($msg > 0) && ($_COOKIE['brewerSteward'] == "N")) echo "CHECKED"; ?> rel="none" /> No
				</label>
			</div>
		</div>
	</div><!-- ./Form Group -->
    <?php } ?>
	<?php if ($totalRows_judging > 1) { ?>
    <?php if ($view == "default") { ?>
	<div class="form-group"><!-- Form Group REQUIRED Radio Group -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Stewarding Availability</label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
		<?php do { ?>
			<div class="well well-sm">
			<p><?php echo $row_stewarding['judgingLocName']." ("; echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_stewarding['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time").")"; ?></p>
			<div class="input-group input-group-sm">
				<!-- Input Here -->
				<select class="selectpicker" name="brewerStewardLocation[]" id="brewerStewardLocation">
					<option value="<?php echo "N-".$row_stewarding['id']; ?>" <?php $a = explode(",", $row_brewer['brewerStewardLocation']); $b = "N-".$row_stewarding['id']; foreach ($a as $value) { if ($value == $b) { echo "SELECTED"; } } ?>>No</option>
					<option value="<?php echo "Y-".$row_stewarding['id']; ?>" <?php $a = explode(",", $row_brewer['brewerStewardLocation']); $b = "Y-".$row_stewarding['id']; foreach ($a as $value) { if ($value == $b) { echo "SELECTED"; } } ?>>Yes</option>
				</select>
			</div>
			</div>
		<?php }  while ($row_stewarding = mysql_fetch_assoc($stewarding));  ?>
		</div>
	</div><!-- ./Form Group -->
	<?php } ?>
	
	
	<?php } // end if ($totalRows_judging > 1) 
	else { ?>
        <input name="brewerJudgeLocation" type="hidden" value="<?php echo "Y-".$row_judging3['id']; ?>" />
        <input name="brewerStewardLocation" type="hidden" value="<?php echo "Y-".$row_judging3['id']; ?>" />
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