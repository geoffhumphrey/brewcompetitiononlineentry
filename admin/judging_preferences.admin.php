<form data-toggle="validator" role="form" class="form-horizontal" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php if ($section == "step8") echo "setup"; else echo $section; ?>&amp;action=edit&amp;dbTable=<?php echo $judging_preferences_db_table; ?>&amp;id=1" name="form1">
<?php if ($section != "step8") { ?>
<p class="lead"><?php echo $_SESSION['contestName'].": Set Competition Organization Preferences"; ?></p>
<div class="bcoem-admin-element hidden-print">
	<div class="btn-group" role="group" aria-label="...">
		<a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=preferences"><span class="fa fa-cog"></span> Website Preferences</a>
	</div><!-- ./button group -->
</div>
<?php } ?>
<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="jPrefsQueued" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Use Queued Judging</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="jPrefsQueued" value="Y" id="jPrefsQueued_0" rel="none"  <?php if ((isset($_SESSION['jPrefsQueued'])) && ($_SESSION['jPrefsQueued'] == "Y")) echo "CHECKED"; elseif ($section == "step8") echo "CHECKED"; ?> /> Yes
            </label>
            <label class="radio-inline">
                <input type="radio" name="jPrefsQueued" value="N" id="jPrefsQueued_1" rel="queued_no" <?php if ((isset($_SESSION['jPrefsQueued'])) && ($_SESSION['jPrefsQueued'] == "N")) echo "CHECKED"; ?>/> No
            </label>
        </div>
        <span id="helpBlock" class="help-block">
		<div class="btn-group" role="group" aria-label="queuedModal">
			<div class="btn-group" role="group">
				<button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#queuedModal">
				   Queued Judging Info
				</button>
			</div>
		</div>
		</span>
    </div>
</div><!-- ./Form Group -->

<!-- Modal -->
<div class="modal fade" id="queuedModal" tabindex="-1" role="dialog" aria-labelledby="queuedModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="queuedModalLabel">Queued Judging Info</h4>
            </div>
            <div class="modal-body">
                <p>Indicate whether you would like to use the Queued Judging methodology (employed by the American Homebrewers Association for judging the National Hombrewers Competition).</p>
                <p>If &ldquo;Yes,&rdquo; there is no need for competition organizers to define flights. More information can be downloaded on the <a class="hide-loader" href="http://www.bjcp.org/docs/Queued_Judging_organizer.pdf" target="_blank">BJCP's website</a>.</p>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->

<div class="form-group">
    <label for="jPrefsBottleNum" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Number of Bottles Required per Entry</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
            <input class="form-control" id="jPrefsBottleNum" name="jPrefsBottleNum" type="text" value="<?php if (($section == "step8") || (!isset($_SESSION['jPrefsBottleNum']))) echo "2"; else echo $_SESSION['jPrefsBottleNum']; ?>" placeholder="" required>
        <span id="helpBlock" class="help-block"><p>Most competitions require at least two bottles.</span>
    </div>
</div>

<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="jPrefsCapJudges" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Judge Limit</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
            <input class="form-control" id="jPrefsCapJudges" name="jPrefsCapJudges" type="text" value="<?php if (isset($_SESSION['jPrefsCapJudges'])) echo $_SESSION['jPrefsCapJudges']; ?>" placeholder="">

        <span id="helpBlock" class="help-block"><p>Limit to the number of judges that may sign up. Leave blank for no limit.</span>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="jPrefsCapStewards" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Steward Limit</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
            <input class="form-control" id="jPrefsCapStewards" name="jPrefsCapStewards" type="text" value="<?php if (isset($_SESSION['jPrefsCapStewards'])) echo $_SESSION['jPrefsCapStewards']; ?>" placeholder="">

        <span id="helpBlock" class="help-block"><p>Limit to the number of stewards that may sign up. Leave blank for no limit.</span>
    </div>
</div><!-- ./Form Group -->

<div id="queued_no">
	<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
		<label for="jPrefsFlightEntries" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Maximum Entries per Flight</label>
		<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
			<!-- Input Here -->
				<input class="form-control" id="jPrefsFlightEntries" name="jPrefsFlightEntries" type="text" value="<?php if (isset($_SESSION['jPrefsFlightEntries'])) echo $_SESSION['jPrefsFlightEntries']; ?>" placeholder="" required>
		</div>
	</div><!-- ./Form Group -->
</div>
<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
	<label for="jPrefsRounds" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Maximum Rounds per Session</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<!-- Input Here -->
			<input class="form-control" id="jPrefsRounds" name="jPrefsRounds" type="text" value="<?php if (isset($_SESSION['jPrefsRounds'])) echo $_SESSION['jPrefsRounds']; ?>" placeholder="" required>
	</div>
</div><!-- ./Form Group -->
<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
	<label for="jPrefsMaxBOS" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Maximum Places in BOS Round</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<!-- Input Here -->
			<input class="form-control" id="jPrefsMaxBOS" name="jPrefsMaxBOS" type="text" value="<?php if (isset($_SESSION['jPrefsMaxBOS'])) echo $_SESSION['jPrefsMaxBOS']; ?>" placeholder="">
	</div>
</div><!-- ./Form Group -->
<div class="bcoem-admin-element hidden-print">
	<div class="form-group">
		<div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-4">
			<input type="submit" name="Submit" id="setJudgingPrefs" class="btn btn-primary" aria-describedby="helpBlock" value="Set Competition Organization Preferences" />
		</div>
	</div>
</div>
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin","default",$msg,$id); ?>">
<?php } ?>
</form>