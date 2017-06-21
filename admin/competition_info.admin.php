<?php if ($section == "step4") {

    $currency = explode("^",currency_info($row_prefs['prefsCurrency'],1));
    $currency_symbol = $currency[0];
    $currency_code = $currency[1];

    $query_brewer = sprintf("SELECT brewerFirstName,brewerLastName,brewerEmail FROM %s ORDER BY id ASC LIMIT 1",$prefix."brewer");
    $brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
    $row_brewer = mysqli_fetch_assoc($brewer);

    $query_comp_info = sprintf("SELECT COUNT(*) as 'count' FROM %s ORDER BY id ASC LIMIT 1",$prefix."contest_info");
    $comp_info = mysqli_query($connection,$query_comp_info) or die (mysqli_error($connection));
    $row_comp_info = mysqli_fetch_assoc($comp_info);

    if ($row_comp_info['count'] >= 1) {
        $action = "edit";
    }
    else {
        $action = "add";
    }
}

if ($section == "admin") { ?>
<p class="lead"><?php echo $_SESSION['contestName'].": Update Competition Information"; ?></p>
<?php } ?>
<form data-toggle="validator" role="form" class="form-horizontal" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php if ($section == "step4") echo "setup"; else echo $section; ?>&amp;action=<?php echo $action; ?>&amp;dbTable=<?php echo $prefix; ?>contest_info&amp;id=1" name="form1">

<?php if ($section == "step4") { ?>
<h3>Competition Coordinator</h3>
<div class="form-group"><!-- Form Group REQUIRED Text Input -->
    <label for="contactFirstName" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">First Name</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group has-warning">
            <span class="input-group-addon" id="contactFirstName-addon1"><span class="fa fa-user"></span></span>
            <!-- Input Here -->
            <input class="form-control" id="contactFirstName" name="contactFirstName" type="text" value="<?php echo $row_brewer['brewerFirstName']; ?>" placeholder="" autofocus required>
            <span class="input-group-addon" id="contactFirstName-addon2"><span class="fa fa-star"></span></span>
        </div>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group REQUIRED Text Input -->
    <label for="contactLastName" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Last Name</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group has-warning">
            <span class="input-group-addon" id="contactLastName-addon1"><span class="fa fa-user"></span></span>
            <!-- Input Here -->
            <input class="form-control" id="contactLastName" name="contactLastName" type="text" value="<?php echo $row_brewer['brewerLastName']; ?>" placeholder="" required>
            <span class="input-group-addon" id="contactLastName-addon2"><span class="fa fa-star"></span></span>
        </div>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group REQUIRED Text Input -->
    <label for="contactEmail" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Email</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group has-warning">
            <span class="input-group-addon" id="contactEmail-addon1"><span class="fa fa-envelope"></span></span>
            <!-- Input Here -->
            <input class="form-control" id="contactEmail" name="contactEmail" type="email" value="<?php echo $row_brewer['brewerEmail']; ?>" placeholder="" aria-describedby="helpBlock" required>
            <span class="input-group-addon" id="contactEmail-addon2"><span class="fa fa-star"></span></span>
        </div>
        <span id="helpBlock" class="help-block">You will be able to enter more contact names after setup.</span>
    </div>
</div><!-- ./Form Group -->
<input type="hidden" name="contactPosition" value="Competition Coordinator" />
<?php } // end if ($section == "step4")  ?>

<h3>General</h3>

<div class="form-group"><!-- Form Group REQUIRED Text Input -->
    <label for="contestName" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Competition Name</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group has-warning">
            <!-- Input Here -->
            <input class="form-control" id="contestName" name="contestName" type="text" maxlength="255" value="<?php if ($section != "step4") echo $row_contest_info['contestName']; ?>" placeholder="" autofocus required>
            <span class="input-group-addon" id="contestName-addon2"><span class="fa fa-star"></span></span>
        </div>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="contestID" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">BJCP Competition ID</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <input class="form-control" id="contestID" name="contestID" type="text" value="<?php if ($section != "step4") echo $row_contest_info['contestID']; ?>" placeholder="">

    	<span id="helpBlock" class="help-block">
        <div class="btn-group" role="group" aria-label="BJCPCompIDModal">
			<div class="btn-group" role="group">
				<button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#BJCPCompIDModal">
				  BJCP Competition ID Info
				</button>
			</div>
		</div>
		</span>
    </div>
</div><!-- ./Form Group -->


<!-- Modal -->
<div class="modal fade" id="BJCPCompIDModal" tabindex="-1" role="dialog" aria-labelledby="contactFormModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="BJCPCompIDModalLabel">BJCP Competition ID Info</h4>
            </div>
            <div class="modal-body">
                <p>Enter the Competition ID you received from the BJCP if you <a href="http://bjcp.org/apps/comp_reg/comp_reg.php" target="_blank">registered your competition</a>. The BJCP will <em>not</em> accept an XML competition report without a Competition ID.</p>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->

<div class="form-group"><!-- Form Group REQUIRED Text Input -->
    <label for="contestHost" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Host</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group has-warning">
            <!-- Input Here -->
            <input class="form-control" id="contestHost" name="contestHost" type="text" maxlength="255" value="<?php if ($section != "step4") echo $row_contest_info['contestHost']; ?>" placeholder="" required>
            <span class="input-group-addon" id="contestHost-addon2"><span class="fa fa-star"></span></span>
        </div>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="contestHostLocation" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Host Location</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <input class="form-control" id="contestHostLocation" name="contestHostLocation" type="text" maxlength="255" value="<?php if ($section != "step4") echo $row_contest_info['contestHostLocation']; ?>" placeholder="">
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="contestHostWebsite" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Host Website Address</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <input class="form-control" id="contestHostWebsite" name="contestHostWebsite" type="text" maxlength="255" value="<?php if ($section != "step4") echo $row_contest_info['contestHostWebsite']; ?>" placeholder="http://www.yoursite.com">
    </div>
</div><!-- ./Form Group -->

<?php if ($section != "step4") { ?>
<div class="form-group"><!-- Form Group NOT REQUIRED Select -->
    <label for="contestLogo" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Logo File Name</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
    <!-- Input Here -->
    <select class="selectpicker" name="contestLogo" id="contestLogo"  data-live-search="true" data-size="10" data-width="auto">
       <?php $directory = (USER_IMAGES); echo directory_contents_dropdown($directory,$row_contest_info['contestLogo']); ?>
    </select>
    <span id="helpBlock" class="help-block">Choose the image file. If the file is not on the list, use the &ldquo;Upload Logo Image&rdquo; button below.</span>
    <a class="btn btn-sm btn-primary" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=upload"><span class="fa fa-upload"></span> Upload Logo Image</a>
    </div>
</div><!-- ./Form Group -->
<?php } ?>
<?php if ($section == "step4") { ?>
<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="contestCheckInPassword" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">QR Code Log On Password</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <input class="form-control" id="contestCheckInPassword" name="contestCheckInPassword" type="text" value="" placeholder="">
    </div>
</div><!-- ./Form Group -->
<?php } else { ?>
<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="contestCheckInPassword" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">QR Code Log On Password</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <a  href="#" class="btn btn-info" data-toggle="modal" data-target="#QRModal">Add, Update, or Change QR Code Log On Password</a>
        <span id="helpBlock" class="help-block">For use with the <a href="<?php echo $base_url; ?>qr.php">QR Code Entry Check-In</a> function.</span>
    </div>
</div><!-- ./Form Group -->
<?php } ?>

<h3>Entry Window</h3>
<div class="form-group"><!-- Form Group REQUIRED Text Input -->
    <label for="contestEntryOpen" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Open Date</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <div class="input-group has-warning">
        	<input class="form-control" id="contestEntryOpen" name="contestEntryOpen" type="text" value="<?php if ($section != "step4") echo
	getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestEntryOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system"); ?>" placeholder="<?php echo $current_date; ?>" required>
        	<span class="input-group-addon" id="contestHost-addon2"><span class="fa fa-star"></span></span>
        </div>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group REQUIRED Text Input -->
    <label for="contestEntryDeadline" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Close Date</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <div class="input-group has-warning">
        	<input class="form-control" id="contestEntryDeadline" name="contestEntryDeadline" type="text" size="20" value="<?php if ($section != "step4") echo
	getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestEntryDeadline'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system"); ?>" placeholder="<?php echo $current_date; ?>" required>
        	<span class="input-group-addon" id="contestHost-addon2"><span class="fa fa-star"></span></span>
        </div>
    </div>
</div><!-- ./Form Group -->

<h3>Drop-Off Window</h3>
<div class="form-group"><!-- Form Group REQUIRED Text Input -->
    <label for="contestDropoffOpen" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Open Date</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        	<input class="form-control" id="contestDropoffOpen" name="contestDropoffOpen" type="text" value="<?php if ($section != "step4") echo
	getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestDropoffOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system"); ?>" placeholder="<?php echo $current_date; ?>">
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group REQUIRED Text Input -->
    <label for="contestDropoffDeadline" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Close Date</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        	<input class="form-control" id="contestDropoffDeadline" name="contestDropoffDeadline" type="text" value="<?php if ($section != "step4") echo
	getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestDropoffDeadline'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system"); ?>" placeholder="<?php echo $current_date; ?>">
    </div>
</div><!-- ./Form Group -->

<h3>Shipping Location</h3>
<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="contestShippingName" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Name</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
    	<!-- Input Here -->
        	<input class="form-control" id="contestShippingName" name="contestShippingName" type="text" value="<?php if ($section != "step4") echo $row_contest_info['contestShippingName']; ?>" placeholder="">
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="contestShippingAddress" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Address</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
    	<!-- Input Here -->
        	<input class="form-control" id="contestShippingAddress" name="contestShippingAddress" type="text" value="<?php if ($section != "step4") echo $row_contest_info['contestShippingAddress']; ?>" placeholder="">
    </div>
</div><!-- ./Form Group -->

<h3>Shipping Window</h3>
<div class="form-group"><!-- Form Group REQUIRED Text Input -->
    <label for="contestShippingOpen" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Open Date</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        	<input class="form-control" id="contestShippingOpen" name="contestShippingOpen" type="text" value="<?php if ($section != "step4") echo
	getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestShippingOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system"); ?>" placeholder="<?php echo $current_date; ?>">
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group REQUIRED Text Input -->
    <label for="contestShippingDeadline" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Close Date</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <input class="form-control" id="contestShippingDeadline" name="contestShippingDeadline" type="text" value="<?php if ($section != "step4") echo
	getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestShippingDeadline'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system"); ?>" placeholder="<?php echo $current_date; ?>" >
     <span id="helpBlock" class="help-block">This window only applies to the Shipping Location above.</span>
    </div>
</div><!-- ./Form Group -->

<h3>Account Registration</h3>
<div class="form-group"><!-- Form Group REQUIRED Text Input -->
    <label for="contestRegistrationOpen" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Open Date</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <div class="input-group has-warning">
        	<input class="form-control" id="contestRegistrationOpen" name="contestRegistrationOpen" type="text" value="<?php if ($section != "step4") echo
	getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestRegistrationOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system"); ?>" placeholder="<?php echo $current_date; ?>" required>
        	<span class="input-group-addon" id="contestHost-addon2"><span class="fa fa-star"></span></span>
        </div>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group REQUIRED Text Input -->
    <label for="contestRegistrationDeadline" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Close Date</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <div class="input-group has-warning">
        	<input class="form-control" id="contestRegistrationDeadline" name="contestRegistrationDeadline" type="text" size="20" value="<?php if ($section != "step4") echo
	getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestRegistrationDeadline'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system"); ?>" placeholder="<?php echo $current_date; ?>" required>
        	<span class="input-group-addon" id="contestHost-addon2"><span class="fa fa-star"></span></span>
        </div>
    </div>
</div><!-- ./Form Group -->

<h3>Judge or Steward Account Registration</h3>
<div class="form-group"><!-- Form Group REQUIRED Text Input -->
    <label for="contestJudgeOpen" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Open Date</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <div class="input-group has-warning">
        	<input class="form-control" id="contestJudgeOpen" name="contestJudgeOpen" type="text" value="<?php if ($section != "step4") echo
	getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestJudgeOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system"); ?>" placeholder="<?php echo $current_date; ?>" required>
        	<span class="input-group-addon" id="contestHost-addon2"><span class="fa fa-star"></span></span>
        </div>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="contestJudgeDeadline" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Close Date</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <div class="input-group has-warning">
        	<input class="form-control" id="contestJudgeDeadline" name="contestJudgeDeadline" type="text" size="20" value="<?php if ($section != "step4") echo
	getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestJudgeDeadline'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system"); ?>" placeholder="<?php echo $current_date; ?>" required>
        	<span class="input-group-addon" id="contestHost-addon2"><span class="fa fa-star"></span></span>
        </div>
    </div>
</div><!-- ./Form Group -->

<h3>Rules and Other Information</h3>
<div class="form-group"><!-- Form Group NOT-REQUIRED Text Area -->
    <label for="contestRules" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Competition Rules</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <textarea id="contestRules" class="form-control" name="contestRules" rows="15" aria-describedby="helpBlock">
		<?php if ($section != "step4") echo $row_contest_info['contestRules']; else { ?>
        <p>This competition is AHA sanctioned and open to any amateur homebrewer age 21 or older.</p>
		<p>All mailed entries must be <strong>received</strong> at the mailing location by the shipping deadline - please allow for shipping time.</p>
		<p>All entries will be picked up from drop-off locations the day of the drop-off deadline.</p>
		<p>All entries must be handcrafted products, containing ingredients available to the general public, and made using private equipment by hobbyist brewers (i.e., no use of commercial facilities or Brew on Premises operations, supplies, etc.).</p>
		<p>The competition organizers are not responsible for mis-categorized entries, mailed entries that are not received by the entry deadline, or entries that arrived damaged.</p>
		<p>The competition organizers reserve the right to combine styles for judging and to restructure awards as needed depending upon the quantity and quality of entries.</p>
		<p>Qualified judging of all entries is the primary goal of our event. Judges will evaluate and score each entry. The average of the scores will rank each entry in its category. Each flight will have at least one BJCP judge.</p>
		<p>Brewers are not limited to one entry in each category but may only enter each subcategory once.</p>
		<p>The competition committee reserves the right to combine overall style categories based on number of entries. All possible effort will be made to combine similar styles. All brews in combined categories will be judged according to the style they were originally entered in.</p>
		<p>The Best of Show judging will be determined by a Best of Show panel based on a second judging of the top winners.</p>
		<p>Bottles will not be returned to entrants.</p>
       <?php } ?>
        </textarea>
        <span id="helpBlock" class="help-block">Edit the provided general rules text as needed.</span>
     </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT-REQUIRED Text Area -->
    <label for="contestBottles" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Entry Acceptance Rules</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <textarea id="contestBottles" class="form-control" name="contestBottles" rows="15" aria-describedby="helpBlock">
		<?php if ($section != "step4") echo $row_contest_info['contestBottles']; else { ?>
        <p>Each entry will consist of 12 to 22 ounce capped bottles or corked bottles that are void of all identifying information, including labels and embossing. Printed caps are allowed, but must be blacked out completely.</p>
		<p>12oz brown glass bottles are preferred; however, green and clear glass will be accepted. Swing top bottles will likewise be accepted as well as corked bottles.</p>
		<p>Bottles will not be returned to contest entrants.</p>
		<p>All requisite paperwork must be submitted with each entry and can be printed directly from this website. Entry paperwork should be attached to bottles by the method specified on the bottle label.</p>
		<p>Be meticulous about noting any special ingredients that must be specified. Failure to note such ingredients may impact the judges' scoring of your entry.</p>
		<?php } ?>
        </textarea>
        <span id="helpBlock" class="help-block">Indicate the number of bottles, size, color, etc. Edit default text as needed.</span>
     </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT-REQUIRED Text Area -->
    <label for="contestVolunteers" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Volunteer Information</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <textarea id="contestVolunteers" class="form-control" name="contestVolunteers" rows="15">
		<?php if ($section != "step4") echo $row_contest_info['contestVolunteers']; else { ?>
        <p>Volunteer information coming soon!</p>
        <?php } ?>
        </textarea>
     </div>
</div><!-- ./Form Group -->

<h3>Entry Information</h3>

<div class="form-group"><!-- Form Group REQUIRED Text Input -->
    <label for="contestEntryFee" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Per Entry Fee</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group has-warning">
        	<span class="input-group-addon" id="contestEntryFee-addon1"><?php echo $currency_symbol; ?></span>
            <!-- Input Here -->
            <input class="form-control" id="contestEntryFee" name="contestEntryFee" type="number" maxlength="3" value="<?php if ($section != "step4") echo $row_contest_info['contestEntryFee']; ?>" placeholder="" required>
            <span class="input-group-addon" id="contestEntryFee-addon2"><span class="fa fa-star"></span></span>

        </div>
        <span id="helpBlock" class="help-block">Fee for a single entry. Enter a zero (0) for a free entry fee.</span>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="contestEntryCap" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Fee Cap</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
    	<div class="input-group">
        	<span class="input-group-addon" id="contestEntryCap-addon1"><?php echo $currency_symbol; ?></span>
        	<!-- Input Here -->
        	<input class="form-control" id="contestEntryCap" name="contestEntryCap" type="text" value="<?php if ($section != "step4") echo $row_contest_info['contestEntryCap']; ?>" placeholder="">
        </div>
        <span id="helpBlock" class="help-block">Enter the maximum amount for each entrant. Leave blank if no cap.
        <a tabindex="0"  type="button" role="button" data-toggle="popover" data-trigger="hover" data-placement="auto right" data-container="body"  data-content="Useful for competitions with &ldquo;unlimited&rdquo; entries for a single fee (e.g., <?php if ($section != "step4") echo $currency_symbol; ?>X for the first X number of entries, <?php if ($section != "step4") echo $currency_symbol; ?>X for unlimited entries, etc.). "><span class="fa fa-question-circle"></span></a>
        </span>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="contestEntryFeeDiscount" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Discount Multiple Entries</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="contestEntryFeeDiscount" value="Y" id="contestEntryFeeDiscount_0" <?php if (($section != "step4") && ($row_contest_info['contestEntryFeeDiscount'] == "Y")) echo "CHECKED"; ?> /> Yes
            </label>
            <label class="radio-inline">
                <input type="radio" name="contestEntryFeeDiscount" value="N" id="contestEntryFeeDiscount_1" <?php if (($section != "step4") && ($row_contest_info['contestEntryFeeDiscount'] == "N")) echo "CHECKED"; if ($section == "step4") echo "CHECKED"; ?>/> No
            </label>
        </div>
        <span id="helpBlock" class="help-block">Designate Yes or No if your competition offers a discounted entry fee after a certain number is reached.</span>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="contestEntryFeeDiscountNum" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Minimum Entries for Discount</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
    	<!-- Input Here -->
        	<input class="form-control" id="contestEntryFeeDiscountNum" name="contestEntryFeeDiscountNum" type="text" value="<?php if ($section != "step4") echo $row_contest_info['contestEntryFeeDiscountNum']; ?>" placeholder="">
        <span id="helpBlock" class="help-block">The entry threshold participants must exceed to take advantage of the per entry fee discount (designated below). If no, discounted fee exists, leave blank.</span>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="contestEntryFee2" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Discounted Entry Fee</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
    	<div class="input-group">
        	<span class="input-group-addon" id="contestEntryFee2-addon1"><?php echo $currency_symbol; ?></span>
        	<!-- Input Here -->
        	<input class="form-control" id="contestEntryFee2" name="contestEntryFee2" type="text" value="<?php if ($section != "step4") echo $row_contest_info['contestEntryFee2']; ?>" placeholder="">
        </div>
        <span id="helpBlock" class="help-block">Fee for a single, discounted entry.</span>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="contestEntryFeePassword" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Member Discount Password</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
    	<!-- Input Here -->
        	<input class="form-control" id="contestEntryFeePassword" name="contestEntryFeePassword" type="text" value="<?php if ($section != "step4") echo $row_contest_info['contestEntryFeePassword']; ?>" placeholder="">
        <span id="helpBlock" class="help-block">Designate a password for participants to enter to receive discounted entry fees. Useful if your competition provides a discount for members of the sponsoring club(s).</span>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="contestEntryFeePasswordNum" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Member Discount Fee</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
    	<div class="input-group">
        	<span class="input-group-addon" id="contestEntryFeePasswordNum-addon1"><?php echo $currency_symbol; ?></span>
        	<!-- Input Here -->
        	<input class="form-control" id="contestEntryFeePasswordNum" name="contestEntryFeePasswordNum" type="text" value="<?php if ($section != "step4") echo $row_contest_info['contestEntryFeePasswordNum']; ?>" placeholder="">
        </div>
        <span id="helpBlock" class="help-block">Fee for a single, discounted member entry. If you wish the member discount to be free, enter a zero (0). Leave blank for no discount.</span>
    </div>
</div><!-- ./Form Group -->

<h3>Awards Ceremony</h3>
<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="contestAwardsLocDate" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Date</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
    	<!-- Input Here -->
        	<input class="form-control" id="contestAwardsLocDate" name="contestAwardsLocDate" type="text" value="<?php if ($section != "step4") echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_info['contestAwardsLocTime'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system"); ?>" placeholder="<?php echo $current_date; ?>">
        <span id="helpBlock" class="help-block">Provide even if the date of judging is the same.</span>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="contestAwardsLocName" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Location Name</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
    	<!-- Input Here -->
        	<input class="form-control" id="contestAwardsLocName" name="contestAwardsLocName" type="text" value="<?php if ($section != "step4") echo $row_contest_info['contestAwardsLocName']; ?>" placeholder="">
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="contestAwardsLocation" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Location Address</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
    	<!-- Input Here -->
        	<input class="form-control" id="contestAwardsLocation" name="contestAwardsLocation" type="text" value="<?php if ($section != "step4") echo $row_contest_info['contestAwardsLocation']; ?>" placeholder="">
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT-REQUIRED Text Area -->
    <label for="contestAwards" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Awards Structure</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <textarea id="contestAwards" class="form-control" name="contestAwards" rows="15" aria-describedby="helpBlock">
        <?php if ($section != "step4") echo $row_contest_info['contestAwards']; else { ?>
        <p>The awards ceremony will take place once judging is completed.</p>
		<p>Places will be awarded to 1st, 2nd, and 3rd place in each category/table.</p>
		<p>The 1st place entry in each category will advance to the Best of Show (BOS) round with a single, overall Best of Show beer selected.</p>
		<p>Additional prizes may be awarded to those winners present at the awards ceremony at the discretion of the competition organizers.</p>
        <p>Both score sheets and awards will be available for pick up that night after the ceremony concludes.  Awards and score sheets not picked up will be mailed back to participants.  Results will be posted to the competition web site after the ceremony concludes.</p>
        <?php } ?>
        </textarea>
        <span id="helpBlock" class="help-block">Indicate places for each category, BOS procedure, qualifying criteria, etc. Edit default text as needed.</span>
     </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT-REQUIRED Text Area -->
    <label for="contestBOSAward" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Best of Show</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <textarea id="contestBOSAward" class="form-control" name="contestBOSAward" rows="15" aria-describedby="helpBlock">
        <?php if ($section != "step4") echo $row_contest_info['contestBOSAward']; ?>
        </textarea>
        <span id="helpBlock" class="help-block">Indicate whether the Best of Show winner will receive a special award (e.g., a pro-am brew with a sponsoring brewery, etc.).</span>
     </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT-REQUIRED Text Area -->
    <label for="contestCircuit" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Circuit Qualifying Events</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <textarea id="contestCircuit" class="form-control" name="contestCircuit" rows="15" aria-describedby="helpBlock">
        <?php if ($section != "step4") echo $row_contest_info['contestCircuit']; ?>
        </textarea>
        <span id="helpBlock" class="help-block">Indicate whether your competition is a qualifier for any national or regional competitions.</span>
     </div>
</div><!-- ./Form Group -->

<div class="bcoem-admin-element hidden-print">
	<div class="form-group">
		<div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-4">
			<input name="submit" type="submit" class="btn btn-primary" value="Update Competition Info">
		</div>
	</div>
</div>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin","default",$msg,$id); ?>">
</form>
<!-- Update QR Password Modal -->
<div class="modal fade" id="QRModal" tabindex="-1" role="dialog" aria-labelledby="QRModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="QRModalLabel">Add, Update, or Change QR Code Log On Password</h4>
      </div>
      <div class="modal-body">
        <form data-toggle="validator" role="form" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php echo $section; ?>&amp;action=edit&amp;go=qr&amp;dbTable=<?php echo $prefix; ?>contest_info&amp;id=1" name="form2">
        <div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
            <label for="contestCheckInPassword">QR Code Log On Password</label>
            <input class="form-control" id="contestCheckInPassword" name="contestCheckInPassword" type="password" value="" placeholder="" data-error="Please provide a password for QR Code entry check-in" required>
            <div class="help-block with-errors"></div>
        </div><!-- ./Form Group -->
        <input name="submit" type="submit" class="btn btn-primary" value="Update Password">
        <input type="hidden" name="relocate" value="<?php echo $base_url."index.php?section=admin&amp;go=contest_info"; ?>">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

