<?php 
/**
 * Module:      brewer.sec.php 
 * Description: This module houses the functionality for users to add/edit their personal 
 *              information - references the "brewer" database table.
 * 
 */

if ($section != "step2") {
	include(DB.'judging_locations.db.php');
	include(DB.'stewarding.db.php'); 
	include(DB.'styles.db.php');
}

include(DB.'brewer.db.php');
include (DB.'dropoff.db.php');

if (($section != "step2") && ($row_brewer['brewerCountry'] == "United States")) $us_phone = TRUE; else $us_phone = FALSE;

$phone1 = $row_brewer['brewerPhone1'];
$phone2 = $row_brewer['brewerPhone2'];

if ($us_phone) { 
    $phone1 = format_phone_us($phone1);
    $phone2 = format_phone_us($phone2); 
}

// Get table assignments and build flags
$table_assign_judge = table_assignments($_SESSION['user_id'],"J",$_SESSION['prefsTimeZone'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'],0);
$table_assign_steward = table_assignments($_SESSION['user_id'],"S",$_SESSION['prefsTimeZone'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'],0);
if ((!empty($table_assign_judge)) || (!empty($table_assign_steward))) $table_assignment = TRUE;
if ((empty($table_assign_judge)) && (empty($table_assign_steward))) $table_assignment = FALSE;

// Build info message
if (($section == "step2") || ($action == "add") || (($action == "edit") && (($_SESSION['loginUsername'] == $row_brewerID['brewerEmail'])) || ($_SESSION['userLevel'] <= "1")))  { 
$info_msg = "<p class=\"lead\">The information here beyond your first name, last name, and club is strictly for record-keeping and contact purposes. <small>A condition of entry into the competition is providing this information. Your name and club may be displayed should one of your entries place, but no other information will be made public.</small></p>\n";


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
if ($go != "admin") echo $info_msg;
?>
<!-- Checking if correct page -->
<form class="form-horizontal" action="<?php echo $form_action; ?>" method="POST" name="form1" id="form1">
    <div class="form-group"><!-- Form Group REQUIRED Text Input -->
        <label for="brewerFirstName" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">First Name</label>
        <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
            <div class="input-group has-warning">
                <span class="input-group-addon" id="brewerFirstName-addon1"><span class="fa fa-user"></span></span>
                <!-- Input Here -->
                <input class="form-control" id="brewerFirstName" name="brewerFirstName" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerFirstName']; ?>" placeholder="" <?php if ($psort == "default") echo "autofocus"; ?>>
                <span class="input-group-addon" id="brewerFirstName-addon2"><span class="fa fa-star"></span></span>
            </div>
        </div>
    </div><!-- ./Form Group -->
    <div class="form-group"><!-- Form Group REQUIRED Text Input -->
        <label for="brewerLastName" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Last Name</label>
        <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
            <div class="input-group has-warning">
                <span class="input-group-addon" id="brewerLastName-addon1"><span class="fa fa-user"></span></span>
                <!-- Input Here -->
                <input class="form-control" id="brewerLastName" name="brewerLastName" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerLastName']; ?>" placeholder="">
                <span class="input-group-addon" id="brewerLastName-addon2"><span class="fa fa-star"></span></span>
            </div>
            <span class="help-block">Please enter only <em>one</em> person&rsquo;s name. You will be able to identify a co-brewer when adding your entries.</span>
        </div>
    </div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
        <label for="brewerAddress" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Street Address</label>
        <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
            <div class="input-group has-warning">
                <span class="input-group-addon" id="brewerAddress-addon1"><span class="fa fa-home"></span></span>
                <!-- Input Here -->
                <input class="form-control" id="brewerAddress" name="brewerAddress" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerAddress']; ?>" placeholder="">
                <span class="input-group-addon" id="brewerAddress-addon2"><span class="fa fa-star"></span></span>
            </div>
        </div>
    </div><!-- ./Form Group -->
	
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
        <label for="brewerCity" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">City</label>
        <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
            <div class="input-group has-warning">
                <!-- Input Here -->
                <input class="form-control" id="brewerCity" name="brewerCity" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerCity']; ?>" placeholder="">
                <span class="input-group-addon" id="brewerCity-addon2"><span class="fa fa-star"></span></span>
            </div>
        </div>
    </div><!-- ./Form Group -->

	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
        <label for="brewerState" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">State/Province</label>
        <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
            <div class="input-group has-warning">
                <!-- Input Here -->
                <input class="form-control" id="brewerState" name="brewerState" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerState']; ?>" placeholder="">
                <span class="input-group-addon" id="brewerState-addon2"><span class="fa fa-star"></span></span>
            </div>
        </div>
    </div><!-- ./Form Group -->

	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
        <label for="brewerZip" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Zip/Postal Code</label>
        <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
            <div class="input-group has-warning">
                <!-- Input Here -->
                <input class="form-control" id="brewerZip" name="brewerZip" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerZip']; ?>" placeholder="">
                <span class="input-group-addon" id="brewerZip-addon2"><span class="fa fa-star"></span></span>
            </div>
        </div>
    </div><!-- ./Form Group -->

	<div class="form-group"><!-- Form Group REQUIRED Select -->
        <label for="brewerCountry" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Country</label>
        <div class="col-lg-10 col-md-6 col-sm-8 col-xs-12 has-warning">
        <!-- Input Here -->
        <select class="selectpicker" name="brewerCountry" id="brewerCountry" data-live-search="true" data-size="10" data-width="auto">
            <?php foreach ($countries as $country) {  ?>
            <option value="<?php echo $country; ?>" <?php if (($action == "edit") && ($row_brewer['brewerCountry'] == $country)) echo "selected"; ?>><?php echo $country; ?></option>
            <?php } ?>
        </select>
        </div>
    </div><!-- ./Form Group -->
	
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
        <label for="brewerPhone1" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Phone 1</label>
        <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
            <div class="input-group has-warning">
                <span class="input-group-addon" id="brewerPhone1-addon1"><span class="fa fa-phone"></span></span>
                <!-- Input Here -->
                <input class="form-control" id="brewerPhone1" name="brewerPhone1" type="text" value="<?php if ($action == "edit") echo $phone1; ?>" placeholder="">
                <span class="input-group-addon" id="brewerPhone1-addon2"><span class="fa fa-star"></span></span>
            </div>
        </div>
    </div><!-- ./Form Group -->

	<div class="form-group"><!-- Form Group Text Input -->
        <label for="brewerPhone2" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Phone 2</label>
        <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
        	<!-- Input Here -->
       		<input class="form-control" id="brewerPhone2" name="brewerPhone2" type="text" value="<?php if ($action == "edit") echo $phone2; ?>" placeholder="">
        </div>
    </div><!-- ./Form Group -->
    
    <div class="form-group"><!-- Form Group NOT REQUIRED Select -->
        <label for="brewerDropOff" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Drop Off Location</label>
        <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <select class="selectpicker" name="brewerDropOff" id="brewerDropOff" data-live-search="true" data-size="10" data-width="auto">
        <?php do { ?>
            <option value="<?php echo $row_dropoff['id']; ?>" <?php if (($action == "edit") && ($row_brewer['brewerDropOff'] == $row_dropoff['id'])) echo "SELECTED"; ?>><?php echo $row_dropoff['dropLocationName']; ?></option>
        <?php } while ($row_dropoff = mysqli_fetch_assoc($dropoff)); ?>
            <option disabled="disabled">-------------</option>
    		<option value="0" <?php if (($action == "edit") && ($row_brewer['brewerDropOff'] == "0")) echo "SELECTED"; ?>>I'm Shipping My Entries</option>
        </select>
        </div>
    </div><!-- ./Form Group -->

    <div class="form-group"><!-- Form Group Text Input -->
        <label for="brewerClubs" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Club(s)</label>
        <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
        	<!-- Input Here -->
       		<input class="form-control" id="brewerClubs" name="brewerClubs" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerClubs']; ?>" placeholder="">
        </div>
    </div><!-- ./Form Group -->
    
    <div class="form-group"><!-- Form Group Text Input -->
        <label for="brewerAHA" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">AHA Member Number</label>
        <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
        	<!-- Input Here -->
       		<input class="form-control" id="brewerAHA" name="brewerAHA" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerAHA']; ?>" placeholder="">
            <span class="help-block">To be considered for a GABF Pro-Am brewing opportunity you must be an AHA member.</span>
        </div>
    </div><!-- ./Form Group -->
    
    <?php if (($go != "entrant") && ($section != "step2")) { ?>
        
        <div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
            <label for="brewerJudgeNotes" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Notes To Organizer</label>
            <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
                <!-- Input Here -->
                <input class="form-control" name="brewerJudgeNotes" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerJudgeNotes']; ?>" placeholder="">
                <span class="help-block">Provide any information that you believe the competition organizer should know (e.g., allergies, special dietary restrictions, shirt size, etc.).</span>
            </div>
        </div><!-- ./Form Group -->
    <!-- Judging and Stewarding Preferences or Assignments -->
    
		<?php if ($table_assignment) { ?>
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
        <!-- Judging preferences -->
        <div class="form-group"><!-- Form Group Radio INLINE -->
            <label for="brewerJudge" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Judging</label>
            <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
                <div class="input-group">
                    <!-- Input Here -->
                    <label class="radio-inline">
                        <input type="radio" name="brewerJudge" value="Y" id="brewerJudge_0" <?php if (($action == "add") && ($go == "judge")) echo "CHECKED"; if (($action == "edit") && ($row_brewer['brewerJudge'] == "Y")) echo "checked"; ?>> Yes
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="brewerJudge" value="N" id="brewerJudge_1" <?php if (($action == "add") && ($go == "default")) echo "CHECKED"; if (($action == "edit") && ($row_brewer['brewerJudge'] == "N")) echo "checked"; ?>> No
                    </label>
                </div>
                <span class="help-block">Are you willing and qualified to serve as a judge in this competition?</span>
            </div>
        </div><!-- ./Form Group -->
        <?php if ($totalRows_judging > 1) { ?>
        <div class="form-group"><!-- Form Group NOT REQUIRED Select -->
            <label for="brewerJudgeLocation" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Judging Location Availability</label>
            <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
            <!-- Input Here -->
            <?php do { ?>
            <p class="bcoem-form-info"><?php echo $row_judging3['judgingLocName']." ("; echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging3['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time").")"; ?></p>
            <select class="selectpicker" name="brewerJudgeLocation[]" id="brewerJudgeLocation" data-width="auto">
                <option value="<?php echo "N-".$row_judging3['id']; ?>"   <?php $a = explode(",", $row_brewer['brewerJudgeLocation']); $b = "N-".$row_judging3['id']; foreach ($a as $value) { if ($value == $b) { echo "SELECTED"; } } ?>>No</option>
                <option value="<?php echo "Y-".$row_judging3['id']; ?>"   <?php $a = explode(",", $row_brewer['brewerJudgeLocation']); $b = "Y-".$row_judging3['id']; foreach ($a as $value) { if ($value == $b) { echo "SELECTED"; } } ?>>Yes</option>
            </select>
            
            <?php }  while ($row_judging3 = mysqli_fetch_assoc($judging3)); ?> 
            </div>
        </div><!-- ./Form Group -->
        <?php }
		else {	?>        
        <input name="brewerJudgeLocation" type="hidden" value="<?php echo "Y-".$row_judging3['id']; ?>" />
        <input name="brewerStewardLocation" type="hidden" value="<?php echo "Y-".$row_judging3['id']; ?>" />
        <?php } ?>
        <div class="form-group"><!-- Form Group Text Input -->
            <label for="brewerJudgeID" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">BJCP ID</label>
            <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
                <!-- Input Here -->
                <input class="form-control" id="brewerJudgeID" name="brewerJudgeID" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerJudgeID']; ?>" placeholder="" <?php if ($psort == "judge") echo "autofocus"; ?>>
            </div>
        </div><!-- ./Form Group -->
        
        <div class="form-group"><!-- Form Group Radio INLINE -->
            <label for="brewerJudgeMead" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Mead Judge Endorsement</label>
            <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
                <div class="input-group">
                    <!-- Input Here -->
                    <label class="radio-inline">
                        <input type="radio" name="brewerJudgeMead" value="Y" id="brewerJudgeMead_0" <?php if (($action == "edit") && ($row_brewer['brewerJudgeMead'] == "Y")) echo "CHECKED"; ?>> Yes
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="brewerJudgeMead" value="N" id="brewerJudgeMead_1" <?php if (($action == "edit") && (($row_brewer['brewerJudgeMead'] == "N") || ($row_brewer['brewerJudgeMead'] == ""))) echo "CHECKED"; ?>> No
                    </label>
                </div>
                <span class="help-block">Have you passed the BJCP Mead Judge exam?</span>
            </div>
        </div><!-- ./Form Group -->
        <?php $judge_array = explode(",",$row_brewer['brewerJudgeRank']); ?>
        <div class="form-group"><!-- Form Group Radio STACKED -->
            <label for="brewerJudgeRank" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">BJCP Rank</label>
            <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
                <div class="input-group">
                    <!-- Input Here -->
                    <div class="radio">
                        <label>
                            <input type="radio" name="brewerJudgeRank[]" value="Novice" <?php if (($action == "edit") && in_array("Novice",$judge_array)) echo "CHECKED"; else echo "CHECKED" ?>> Novice *
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="brewerJudgeRank[]" value="Rank Pending" <?php if (($action == "edit")  && in_array("Rank Pending",$judge_array)) echo "CHECKED"; ?>> Rank Pending
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="brewerJudgeRank[]" value="Apprentice" <?php if (($action == "edit") && in_array("Apprentice",$judge_array)) echo "CHECKED"; ?>> Apprentice **
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                             <input type="radio" name="brewerJudgeRank[]" value="Provisional" <?php if (($action == "edit") && in_array("Provisional",$judge_array)) echo "CHECKED"; ?>> Provisional ***
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
                	<p>* The <em>Novice</em> rank is for those who haven't taken the BJCP Beer Judge Entrance Exam, and are <em>not</em> a professional brewer.</p>
                    <p>** The <em>Apprentice</em> rank is for those who have taken the BJCP Legacy Beer Exam, but did not pass one or more of the sections. This rank has been phased out.</p>
                    <p>*** The <em>Provisional</em> rank is for those have taken the BJCP Beer Judge Entrance Exam, have passed, but have not yet taken the BJCP Beer Judging Exam.</p>
                    </p>
                </span>
            </div>
        </div><!-- ./Form Group -->
        <div class="form-group"><!-- Form Group Radio STACKED -->
            <label for="brewerJudgeRank" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Designations</label>
            <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
                <div class="input-group">
                    <!-- Input Here -->
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
                            <input type="checkbox" name="brewerJudgeRank[]" value="Master Cicerone" <?php if (($action == "edit") && in_array("Master Cicerone",$judge_array)) echo "CHECKED"; ?>> Master Cicerone
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                             <input type="checkbox" name="brewerJudgeRank[]" value="Judge with Sensory Training" <?php if (($action == "edit") && in_array("Judge with Sensory Training",$judge_array)) echo "CHECKED"; ?>> Judge with Sensory Training
                        </label>
                    </div>
                 </div>
                <span class="help-block">Only the first two checked will appear on your Judge Scoresheet Labels</span>
            </div>
        </div><!-- ./Form Group -->
        <div class="form-group"><!-- Form Group REQUIRED Select -->
            <label for="brewerJudgeExp" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Competitions Judged</label>
            <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
            <!-- Input Here -->
            <select class="selectpicker" name="brewerJudgeExp" id="brewerJudgeExp" data-width="auto" required>
                <option value="0"<?php if (($action == "edit") && ($row_brewer['brewerJudgeExp'] == "0")) echo " SELECTED"; ?>>0</option>
                <option value="1-5"<?php if (($action == "edit") && ($row_brewer['brewerJudgeExp'] == "1-5")) echo " SELECTED"; ?>>1-5</option>
                <option value="6-10"<?php if (($action == "edit") && ($row_brewer['brewerJudgeExp'] == "6-10")) echo " SELECTED"; ?>>6-10</option>
                <option value="10+"<?php if (($action == "edit") && ($row_brewer['brewerJudgeExp'] == "10+")) echo " SELECTED"; ?>>10+</option>
            </select>
            <span class="help-block">How many competitions have you previously served as a <strong>judge</strong>?</span>
            </div>
            
        </div><!-- ./Form Group -->
        <div class="form-group">
        <label for="brewerJudgeLikes" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">&nbsp;</label>
                <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
        			<button class="btn btn-default" type="button" data-toggle="collapse" data-target="#collapsePref" aria-expanded="false" aria-controls="collapsePref">Specify Preferred Styles to Judge</button>
        			<span class="help-block">Click the button to expand the preferred styles to judge list.</span>
                </div>
                
        </div><!-- ./Form Group --> 
        <div class="collapse" id="collapsePref">
          	<div class="form-group"><!-- Form Group Checkbox  -->
                <label for="brewerJudgeLikes" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Preferred Styles</label>
                <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
                <p><strong class="text-danger">For preferences ONLY.</strong> Leaving a style unchecked indicates that you are OK to judge it – there's no need to check all that your available to judge.</p>
                    
                    <?php do { ?>
                        <div class="checkbox">
                            <label>
                                <input name="brewerJudgeLikes[]" type="checkbox" value="<?php echo $row_styles['id']; ?>" <?php if (isset($row_brewer['brewerJudgeLikes'])) { $a = explode(",", $row_brewer['brewerJudgeLikes']); $b = $row_styles['id']; foreach ($a as $value) { if ($value == $b) echo "CHECKED"; } } ?>> <?php echo ltrim($row_styles['brewStyleGroup'], "0").$row_styles['brewStyleNum'].": ".$row_styles['brewStyle']; ?>
                            </label>
                        </div>
                    
                    <?php } while ($row_styles = mysqli_fetch_assoc($styles)); ?>
                    
                </div>
            </div><!-- ./Form Group --> 
        </div>
        <div class="form-group">
        <label for="brewerJudgeLikes" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">&nbsp;</label>
                <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
        			<button class="btn btn-default" type="button" data-toggle="collapse" data-target="#collapseNonPref" aria-expanded="false" aria-controls="collapseNonPref">Specify Non-Preferred Styles to Judge</button>
                    <span class="help-block">Click the button to expand the non-preferred styles to judge list.</span>
                </div>
        </div><!-- ./Form Group --> 
 		<div class="collapse" id="collapseNonPref">
          	<div class="form-group"><!-- Form Group Checkbox  -->
                <label for="brewJudgeDislikes" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Non Preferred Styles</label>
                <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
                    <p><strong class="text-danger">There is no need to mark those styles for which you have entries</strong>; the system will not allow you to be assigned to any table where you have entries.</p>
                    <!-- <div class="row"> -->
                    <?php do { ?>
                    <!-- Input Here -->
                        <div class="checkbox">
                            <label>
                                <input name="brewerJudgeDislikes[]" type="checkbox" value="<?php echo $row_styles2['id']; ?>" <?php if (isset($row_brewer['brewerJudgeDislikes'])) { $a = explode(",", $row_brewer['brewerJudgeDislikes']); $b = $row_styles2['id']; foreach ($a as $value) { if ($value == $b) echo "CHECKED"; } } ?>> <?php echo ltrim($row_styles2['brewStyleGroup'], "0").$row_styles2['brewStyleNum'].": ".$row_styles2['brewStyle']; ?>
                            </label>
                        </div>
                    <?php } while ($row_styles2 = mysqli_fetch_assoc($styles2)); ?>
                    <!-- </div> -->
                </div>
            </div><!-- ./Form Group -->
        </div>
        <!-- Stewarding preferences -->
        <div class="form-group"><!-- Form Group Radio INLINE -->
            <label for="brewerSteward" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Stewarding</label>
            <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
                <div class="input-group">
                    <!-- Input Here -->
                    <label class="radio-inline">
                        <input type="radio" name="brewerSteward" value="Y" id="brewerSteward_0" <?php if (($action == "add") && ($go == "judge")) echo "CHECKED"; if (($action == "edit") && ($row_brewer['brewerSteward'] == "Y")) echo "checked"; ?>> Yes
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="brewerSteward" value="N" id="brewerSteward_1" <?php if (($action == "add") && ($go == "default")) echo "CHECKED"; if (($action == "edit") && ($row_brewer['brewerSteward'] == "N")) echo "checked"; ?>> No
                    </label>
                </div>
                <span class="help-block">Are you willing to serve as a steward in this competition?</span>
            </div>
        </div><!-- ./Form Group -->
        <?php if ($totalRows_judging > 1) { ?>
        <div class="form-group"><!-- Form Group NOT REQUIRED Select -->
            <label for="brewerStewardLocation" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Stewarding Location Availability</label>
            <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
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
        <?php } ?>
        
        <?php } ?>
    <?php } // end if (($go != "entrant") && ($section != "step2")) ?>
   
<?php if ($section == "step2") { ?>
<input name="brewerSteward" type="hidden" value="N" />
<input name="brewerJudge" type="hidden" value="N" />
<input name="brewerEmail" type="hidden" value="<?php echo $go; ?>" />
<input name="uid" type="hidden" value="<?php echo $row_brewerID['id']; ?>" />
<?php } ?>
<?php if ($section != "step2") { ?>
	<input name="brewerEmail" type="hidden" value="<?php if ($filter != "default") echo $row_brewer['brewerEmail']; else echo $_SESSION['user_name']; ?>" />
	<input name="uid" type="hidden" value="<?php if (($action == "edit") && ($row_brewer['uid'] != "")) echo  $row_brewer['uid']; elseif (($action == "edit") && ($_SESSION['userLevel'] <= "1") && (($_SESSION['loginUsername']) != $row_brewer['brewerEmail'])) echo $row_user_level['id']; else echo $_SESSION['user_id']; ?>" />
    <?php if ($go == "entrant") { ?>
	<input name="brewerJudge" type="hidden" value="N" />
	<input name="brewerSteward" type="hidden" value="N" />
	<?php } ?>
<?php } 
if ($action == "add") {
	$submit_icon = "plus";
	$submit_text = "Add Account Info";
}

if ($action == "edit") {
	$submit_icon = "pencil";
	$submit_text = "Edit Account Info";
}

else {
	$submit_icon = "plus";
	$submit_text = "Add Admin User Info";
}

?>
<?php if ($go == "admin") { ?>
	<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id,"yes"); ?>">
<?php } else { ?>
    <input type="hidden" name="relocate" value="<?php echo $base_url; ?>index.php?section=list">
<?php } ?>
<div class="form-group">
    <div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-4">
        <!-- Input Here -->
        <button name="submit" type="submit" class="btn btn-primary" ><?php echo $submit_text; ?></span> </button>
    </div>
</div><!-- Form Group -->
</form>
<?php } // LINE 25 or so... end if (($section == "step2") || ($action == "add") || (($action == "edit") && (($_SESSION['loginUsername'] == $row_brewerID['brewerEmail'])) || ($_SESSION['userLevel'] <= "1"))) 
else echo "<p class='lead'>You can only edit your own profile.</p>";
?> 