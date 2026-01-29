<?php

// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && (strpos($section, "step") === FALSE) && ($_SESSION['userLevel'] > 0))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

if (empty($row_judging_prefs['jPrefsScoresheet'])) $judging_scoresheet = 1;
elseif (!isset($_SESSION['jPrefsScoresheet'])) $judging_scoresheet = 1;
else $judging_scoresheet = $_SESSION['jPrefsScoresheet'];

$suggested_open = FALSE;
$suggested_close = FALSE;
$judging_open_date = "";
$judging_close_date = "";

if (($_SESSION['prefsEval'] == 1) || ($section == "step8")) {

    $judging_dates = array();
    $judging_earliest_date = "";
    $judging_latest_date = "";

    // Check whether any judging sessions have been defined. 
    // If so, loop through and find the earliest and the latest dates.
    $query_judging_locations = sprintf("SELECT id, judgingDate, judgingDateEnd FROM %s WHERE judgingLocType <= '1';", $prefix."judging_locations");
    $judging_locations = mysqli_query($connection,$query_judging_locations) or die (mysqli_error($connection));
    $row_judging_locations = mysqli_fetch_assoc($judging_locations);
    $totalRows_judging_locations = mysqli_num_rows($judging_locations);

    if ($totalRows_judging_locations > 0) {

        do {

            if (!empty($row_judging_locations['judgingDate'])) $judging_dates[] = $row_judging_locations['judgingDate'];
            if (!empty($row_judging_locations['judgingDateEnd'])) $judging_dates[] = $row_judging_locations['judgingDateEnd'];


        } while($row_judging_locations = mysqli_fetch_assoc($judging_locations));

        $judging_earliest_date = min($judging_dates);
        if ((max($judging_dates) > $judging_earliest_date)) $judging_latest_date = max($judging_dates);

    }

    // If no session vars exist for open and/or closed dates, generate

    if (!isset($_SESSION['jPrefsJudgingOpen'])) {

        if (empty($judging_earliest_date)) $judging_open_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], time(), $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system");
        else $judging_open_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $judging_earliest_date, $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system");

    }

    if (!isset($_SESSION['jPrefsJudgingClosed'])) {

        if (empty($judging_latest_date)) $judging_close_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], (time()+86400), $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system"); // Close one day after today
        else $judging_close_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $judging_latest_date, $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system");

    }

    // Get or generate open date

    if ((isset($_SESSION['jPrefsJudgingOpen'])) && (!empty($_SESSION['jPrefsJudgingOpen']))) {

        $j_open_date = $_SESSION['jPrefsJudgingOpen'];
        if ((!empty($judging_earliest_date)) && ($judging_earliest_date < $_SESSION['jPrefsJudgingOpen'])) $j_open_date = $judging_earliest_date;
        $judging_open_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $j_open_date, $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system");
    
    }

    else {

        if (!empty($judging_earliest_date)) $suggested_open_date = $judging_earliest_date;
        else $suggested_open_date = round(time() / (15 * 60)) * (15 * 60);

        $judging_open_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $suggested_open_date, $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system");
        $suggested_open = TRUE;

    }
    

    if ((isset($_SESSION['jPrefsJudgingClosed'])) && (!empty($_SESSION['jPrefsJudgingClosed']))) {
    
        // If the opening date is defined...
        if ((isset($_SESSION['jPrefsJudgingOpen'])) && (!empty($_SESSION['jPrefsJudgingOpen']))) {


            // Make sure the closing date is after the opening date
            if ($_SESSION['jPrefsJudgingClosed'] > $_SESSION['jPrefsJudgingOpen']) {

                $j_closed_date = $_SESSION['jPrefsJudgingClosed'];
                if ((!empty($judging_latest_date)) && ($judging_latest_date > $_SESSION['jPrefsJudgingClosed'])) $j_closed_date = $judging_latest_date;

            }
            
            else {

                if (empty($judging_earliest_date)) $j_closed_date = $_SESSION['jPrefsJudgingOpen'] + 86400; // open plus 1 day

                else {

                    if ($_SESSION['jPrefsJudgingOpen'] >= $judging_earliest_date) $j_closed_date = $_SESSION['jPrefsJudgingOpen'] + 86400;
                    else $j_closed_date = $judging_earliest_date + 86400;

                }

            }

        }
           
        // If not...
        else {

            if (empty($judging_latest_date)) {
                if (empty($judging_earliest_date)) $j_closed_date = $judging_earliest_date + 86400;
                else $j_closed_date = time() + 86400;
            }

            else $j_closed_date = $judging_latest_date;
                
        }

        $judging_close_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $j_closed_date, $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system"); 

    }

    else {

        if (!empty($judging_latest_date)) $suggested_close_date = $judging_latest_date;
        else {
            if ((isset($_SESSION['jPrefsJudgingOpen'])) && (!empty($_SESSION['jPrefsJudgingOpen']))) $suggested_close_date = $_SESSION['jPrefsJudgingOpen'] + 86400;
            elseif (!empty($judging_earliest_date))   $suggested_close_date = $judging_earliest_date + 86400;
            else $suggested_close_date = round((time() + 86400) / (15 * 60)) * (15 * 60);

        }

        $judging_close_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $suggested_close_date, $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system");
        $suggested_close = TRUE;
    
    }

    /*
    echo "Judging Earliest Date: " . getTimeZoneDateTime($_SESSION['prefsTimeZone'], $judging_earliest_date, $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system")."<br>";
    echo "Judging Open Date Session Var: " . getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['jPrefsJudgingOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system")."<br>"; 
    echo "Open Date to Display: ".$judging_open_date."<br>";
    echo "---------<br>";
    if (!empty($judging_latest_date)) echo "Judging Latest Date: " . getTimeZoneDateTime($_SESSION['prefsTimeZone'], $judging_latest_date, $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system")."<br>"; 
    else echo "No Judging Latest Date<br>";
    echo "Judging Closed Date Session Var: " . getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['jPrefsJudgingClosed'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system")."<br>"; 
    echo "Closed Date to Display: ".$judging_close_date."<br>";
    $suggest = round((time() + 86400) / (15 * 60)) * (15 * 60); // round to the nearest quarter hour
    echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], $suggest, $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system")."<br>";
    */
}

?>
<script>

$(document).ready(function() {

    var electronic_scoresheets_show = "<?php echo $_SESSION['prefsEval']; ?>";

    $("#electronic-scoresheets-show").hide();
    if (electronic_scoresheets_show == 1) $("#electronic-scoresheets-show").show();

    $("input[name$='prefsEval']").click(function() {
        if ($(this).val() == "1") {
            $("#electronic-scoresheets-show").show("fast");
        }
        else {
            $("#electronic-scoresheets-show").hide("fast");
        }
    });

});

</script>
<form data-toggle="validator" role="form" class="form-horizontal" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php if ($section == "step8") echo "setup"; else echo $section; ?>&amp;action=edit&amp;dbTable=<?php echo $judging_preferences_db_table; ?>&amp;id=1" name="form1">
<input type="hidden" name="user_session_token" value ="<?php if (isset($_SESSION['user_session_token'])) echo htmlspecialchars($_SESSION['user_session_token'], ENT_QUOTES, 'UTF-8'); ?>">
<?php if ($section != "step8") { ?>
<p class="lead"><?php echo $_SESSION['contestName'].": Set Preferences"; ?></p>
<div class="bcoem-admin-element hidden-print">
    <a class="btn btn-primary" style="margin: 5px 5px 5px 0" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=preferences"><span class="fa fa-cog"></span> General Preferences</a>
    <a class="btn btn-primary" style="margin: 5px 5px 5px 0" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=preferences&amp;action=entries"><span class="fa fa-beer"></span> Entry Preferences</a>
    <a class="btn btn-primary" style="margin: 5px 5px 5px 0" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=preferences&amp;action=email"><span class="fa fa-envelope"></span> Email Sending Preferences</a>
    <a class="btn btn-primary" style="margin: 5px 5px 5px 0" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=preferences&amp;action=payment"><span class="fa fa-money"></span> Currency and Payment Preferences</a>
    <a class="btn btn-primary" style="margin: 5px 5px 5px 0" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=preferences&amp;action=best"><span class="fa fa-trophy"></span> Best Brewer and/or Club Preferences</a>
    <a class="btn btn-primary disabled"  style="margin: 5px 5px 5px 0"  href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_preferences"><span class="fa fa-cog"></span> Judging/Competition Organization Preferences</a>
</div>
<h3>Judging/Competition Organization</h3>
<?php } ?>
<div class="form-group">
    <label for="jPrefsBottleNum" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Number of Bottles Required per Entry</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
            <select class="selectpicker" name="jPrefsBottleNum" id="jPrefsBottleNum" data-size="10" data-width="auto">
            <?php for ($i=1; $i <= 15; $i++) { ?>
            <option value="<?php echo $i; ?>" <?php if ((isset($_SESSION['jPrefsBottleNum'])) && ($_SESSION['jPrefsBottleNum'] == $i)) echo "SELECTED"; else { if ($i == 1) echo "SELECTED"; }?>><?php echo $i; ?></option>
            <?php } ?>
            </select>
            <span id="helpBlock" class="help-block"><p>Most competitions require at least two bottles.</span>
    </div>
</div>
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
</div>
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
                <p>If &ldquo;Yes,&rdquo; there is no need for competition organizers to define flights. More information can be downloaded on the <a class="hide-loader" href="https://www.bjcp.org/competitions/supplies-reference-materials/" target="_blank">BJCP's website</a>.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->
<div class="form-group">
    <label for="prefsDisplaySpecial" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Scoresheet Unique Identifier</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">            
            <label class="radio-inline">
                <input type="radio" name="prefsDisplaySpecial" value="J" id="prefsDisplaySpecial_0" <?php if (($section == "step3") || ($_SESSION['prefsDisplaySpecial'] == "J")) echo "CHECKED"; ?>> 6-Character Judging Number
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsDisplaySpecial" value="E" id="prefsDisplaySpecial_1" <?php if ($_SESSION['prefsDisplaySpecial'] == "E") echo "CHECKED"; ?>> 6-Digit Entry Number
            </label>
        </div>
        <div id="helpBlock" class="help-block">
            <p>How entries are identified to judges when evaluating. If uploading scoresheet PDF files, the PDFs for each entry should be named according to the exact 6-character number for use by the system. <span class="text-primary"><strong>Using the random, system-generated <u>Judging Numbers</u> ensures unique file names for live and archived entry data.</strong></span></p>
        </div>
    </div>
</div>
<div class="form-group">
    <label for="prefsEval" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Electronic Scoresheets</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">            
            <label class="radio-inline">
                <input type="radio" name="prefsEval" value="1" id="prefsEval_1"  <?php if ($_SESSION['prefsEval'] == "1") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?> /> Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsEval" value="0" id="prefsEval_0" <?php if ($_SESSION['prefsEval'] == "0") echo "CHECKED"; ?>/> Disable
            </label>
        </div>
        <span id="helpBlock" class="help-block">
        <div class="btn-group" role="group" aria-label="prefsEvalModal">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#prefsEvalModal">
                   Electronic Scoresheets Info
                </button>
            </div>
        </div>
        </span>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="prefsEvalModal" tabindex="-1" role="dialog" aria-labelledby="prefsEvalModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="prefsEvalModalLabel">Electronic Scoresheets Info</h4>
            </div>
            <div class="modal-body">
                <p>Enable or disable the Electronic Scoresheets function. If enabled, Admins have the option to accept judges' entry evaluations via fully electronic, web-based scoresheets built to emulate BJCP official and quasi-official paper-based forms.</p>
                <p>If enabling Electronic Scoresheets and associated functions, Admins should also make sure to set up their installation to take full advantage of them by following the steps outlined in the <a href="https://brewingcompetitions.com/setup-electronic-scoresheets" target="_blank">Setup BCOE&amp;M Electronic Scoresheets</a> help article. Admins or competition officials should also direct all judges who will be using Electronic Scoresheets to review the <a href="https://brewingcompetitions.com/judging-with-electronic-scoresheets" target="_blank">Judging with BCOE&amp;M Electronic Scoresheets</a> primer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<section id="electronic-scoresheets-show">
<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="jPrefsScoresheet" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Entry Evaluation Scoresheet</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="radio">
            <label>
                <input type="radio" name="jPrefsScoresheet" value="1" id="jPrefsScoresheet_0" rel="" <?php if ($judging_scoresheet == "1") echo "CHECKED"; elseif ($section == "step8") echo "CHECKED"; ?> /> BJCP Classic Scoresheet (All Style Types - Including Custom)
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="jPrefsScoresheet" value="2" id="jPrefsScoresheet_1" rel="" <?php if ($judging_scoresheet == "2") echo "CHECKED"; ?>/> BJCP Checklist Scoresheet (Beer Only)
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="jPrefsScoresheet" value="3" id="jPrefsScoresheet_1" rel="" <?php if ($judging_scoresheet == "3") echo "CHECKED"; ?>/> BJCP Structured Scoresheet (Beer, Mead, and Cider Only)
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="jPrefsScoresheet" value="4" id="jPrefsScoresheet_1" rel="" <?php if ($judging_scoresheet == "4") echo "CHECKED"; ?>/> NW Cider Cup Structured Scoresheet (Cider Only)
            </label>
        </div>
        <span id="helpBlock" class="help-block">
        <div class="btn-group" role="group" aria-label="queuedModal">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#scoresheetModal">
                   Entry Evaluation Scoresheet Info
                </button>
            </div>
        </div>
        </span>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="scoresheetModal" tabindex="-1" role="dialog" aria-labelledby="scoresheetModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="scoresheetModalLabel">Entry Evaluation Scoresheet Info</h4>
            </div>
            <div class="modal-body">
                <p>Indicate which BJCP scoresheet judges will use for entry evaluation and scoring.</p>
                <p>The <strong>BJCP Classic Scoresheet</strong> is the original BJCP-sanctioned competition scoresheet. Long form evaluation responses for aroma, appearance, flavor, mouthfeel, and overall impression for <a href="https://bjcp.org/docs/SCP_BeerScoreSheet.pdf" target="_blank">Beer</a>, <a href="https://bjcp.org/docs/SCP_MeadScoreSheet.pdf" target="_blank">Mead</a>, <a href="https://bjcp.org/docs/SCP_CiderScoreSheet.pdf" target="_blank">Cider</a>, and all other style types.</p>
                <p>The <strong>BJCP Checklist Scoresheet</strong> is "a quick checklist-based beer scoresheet covering perhaps 80% of the sensory information necessary for any beer." This is only for use in <a href="https://bjcp.org/docs/Beer_checklist.pdf" target="_blank">evaluating beer entries</a>; Mead, Cider, and other style types utilize the Classic Scoresheet.</p>
                <p>The <strong>BJCP Structured Scoresheet</strong> is a revised and updated version of the Checklist Scoresheet that features sliding scales (none to high) for aroma, appearance, flavor, and mouthfeel to "reduce the time [judges] spend writing out intensity levels." Considered experimental, these scoresheets were introduced at the National Homebrew Competition in 2018 and used again in 2019 to great success. While not officially adopted by the BJCP, their use has been increasing in local competitions. Available for Beer, Mead, and Cider. All other style types utilize the Full Scoresheet.</p>
                <p>More information about BJCP scoresheets can be found on the <a class="hide-loader" href="https://www.bjcp.org/competition-center/" target="_blank">BJCP's Competition Center</a>.</p>
                <p>The <strong>NW Cider Cup Structured Scoresheet</strong> was developed for use in the NW Cider Cup (formerly Portland International Cider Cup) according the specifications of the <a href="https://www.nwcider.com/" target="_blank">Northwest Cider Association</a>. Cider only. Beer and Mead styles utilize the BJCP Structured Scoresheet; all other style types utilize the Full Scoresheet.</p> 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->
<div class="form-group">
    <label for="jPrefsMinWords" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Minimum Words for Scoresheet Comment/Feedback Fields</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <input class="form-control" id="jPrefsMinWords" name="jPrefsMinWords" type="number" value="<?php if (isset($_SESSION['jPrefsMinWords'])) echo $_SESSION['jPrefsMinWords']; ?>" placeholder="">
        <span id="helpBlock" class="help-block"><p>The minimum number of words judges must use when providing comments. Will be enforced for <strong>all comment fields</strong> on the Classic Scoresheet and Checklist Scoresheet, as well as the <strong>Overall Impression comment/feedback field</strong> on all Structured Scoresheets.</p><p>Leave blank or enter zero for no enforced minimum.</p></span>
    </div>
</div>
<div class="form-group">
    <label for="jPrefsScoreDispMax" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Maximum Difference for Consensus Scores</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <select class="selectpicker" name="jPrefsScoreDispMax" id="jPrefsScoreDispMax" data-size="10" data-width="auto">
        <?php for ($i=1; $i <= 10; $i++) { ?>
        <option value="<?php echo $i; ?>" <?php if ((isset($_SESSION['jPrefsScoreDispMax'])) && ($_SESSION['jPrefsScoreDispMax'] == $i)) echo "SELECTED"; else { if ($i == 1) echo "SELECTED"; }?>><?php echo $i; ?></option>
        <?php } ?>
        </select>
        <div id="helpBlock" class="help-block">
            <p>Provide the maximum difference between judges' scores for any given entry.</p>
            <div class="btn-group" role="group" aria-label="maxDiffModal">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#maxDiffModal">
                       Maximum Difference for Consensus Scores Info
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="maxDiffModal" tabindex="-1" role="dialog" aria-labelledby="maxDiffModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="maxDiffModalLabel">Maximum Difference for Consensus Scores Info</h4>
            </div>
            <div class="modal-body">
                <p>While judges are not required to have exactly the same score for the same entry they are evaluating, the difference between their individual scores should be within an acceptable range.</p>
                <p>For example, suppose the specified maximum difference for consensus scores for a competition is eight (8). If one judge scores an entry at 42 and another scores the same entry at 29, the disparity is too great between their scores and, thus, a discussion should take place to bring the scores into within eight points of one another.</p>
                <p>When scoring entries using BCOE&amp;M, judges will be alerted if their score is beyond the maximum difference.</p>
                <p>If no number is provided in the <em>Maximum Difference for Consensus Scores</em> field, the system will default to seven (7), a commonly accepted range.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->
<div class="form-group">
    <label for="jPrefsJudgingOpen" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Judging Open Date and Time</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <input class="form-control date-time-picker-system" id="jPrefsJudgingOpen" name="jPrefsJudgingOpen" type="text" value="<?php echo $judging_open_date; ?>" placeholder="" required>
        <div id="helpBlock" class="help-block">Indicate when judges will be allowed access to their Judging Dashboard to add entry evaluations.  Typically, the open date begins the day and time the first judging session begins.
        <?php if ($suggested_open) echo "<br><span class=\"text-warning\" style=\"margin-bottom:5px;\">* The date and time above is suggested. It is the the earliest start day/time for any judging session, if present. Otherwise, the current date and time is shown.</span>"; ?>
        </div>
    </div>
</div>
<div class="form-group">
    <label for="jPrefsJudgingClosed" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Judging Close Date and Time</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <input class="form-control date-time-picker-system" id="jPrefsJudgingClosed" name="jPrefsJudgingClosed" type="text" size="20" value="<?php echo $judging_close_date; ?>" placeholder="<?php echo $current_date." ".$current_time; ?>" required>
        <div id="helpBlock" class="help-block">
            <p>The closing date and time is the absolute latest judges will be allowed to enter evaluations and scores.
            <?php if ($suggested_close) echo "<br><span class=\"text-warning\" style=\"margin-bottom:5px;\">* The date and time above is suggested. It is the latest recorded end date/time for a judging session OR the last judging session's start time + 8 hours.</span>"; ?>
            </p>
            <div style="margin-top:5px" class="bcoem-admin-element" role="group" aria-label="judgingWindowModal">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#judgingWindowModal">
                       Judging Open/Close Dates and Times Info
                    </button>
                </div>
            </div> 
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="judgingWindowModal" tabindex="-1" role="dialog" aria-labelledby="judgingWindowModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="judgingWindowModalLabel">Judging Open/Close Dates and Times Info</h4>
            </div>
            <div class="modal-body">
                <p>Indicate when judges will be allowed access to their Judging Dashboard to add entry evaluations. Typically, the open date begins the day and time the first judging session begins. The closing date and time is the absolute latest judges will be allowed to enter evaluations and scores.</p>
                <p>If no dates are input here for either open or close, these defaults will be used by the system:</p>
                <ul>
                    <li><strong>Open</strong> &ndash; the earliest judging session's start date/time.</li>
                    <li><strong>Closed</strong> &ndash; the last judging session's start date/time <span class="text-primary">+ 8 hours</span>.</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->
</section>
<div class="form-group">
    <label for="jPrefsCapJudges" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Judge Limit</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <input class="form-control" id="jPrefsCapJudges" name="jPrefsCapJudges" type="number" value="<?php if (isset($_SESSION['jPrefsCapJudges'])) echo $_SESSION['jPrefsCapJudges']; ?>" placeholder="">
        <span id="helpBlock" class="help-block"><p>Limit to the number of judges that may sign up. Leave blank for no limit.</span>
    </div>
</div>
<div class="form-group">
    <label for="jPrefsCapStewards" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Steward Limit</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
            <input class="form-control" id="jPrefsCapStewards" name="jPrefsCapStewards" type="number" value="<?php if (isset($_SESSION['jPrefsCapStewards'])) echo $_SESSION['jPrefsCapStewards']; ?>" placeholder="">

        <span id="helpBlock" class="help-block"><p>Limit to the number of stewards that may sign up. Leave blank for no limit.</span>
    </div>
</div>
<div id="queued_no">
	<div class="form-group">
		<label for="jPrefsFlightEntries" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Maximum Entries per Flight</label>
		<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
                <select class="selectpicker" name="jPrefsFlightEntries" id="jPrefsFlightEntries" data-size="10" data-width="auto">
                <?php for ($i=1; $i <= 50; $i++) { ?>
                <option value="<?php echo $i; ?>" <?php if ((isset($_SESSION['jPrefsFlightEntries'])) && ($_SESSION['jPrefsFlightEntries'] == $i)) echo "SELECTED"; else { if ($i == 1) echo "SELECTED"; }?>><?php echo $i; ?></option>
                <?php } ?>
                </select>
            <span id="helpBlock" class="help-block"><p>The maximum number of entries a judge pair will be assigned to evaluate in the system per flight. This generally applies to the traditional (non-queued) judging methodology.</span>
		</div>
	</div>
</div>
<div class="form-group">
	<label for="jPrefsRounds" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Maximum Rounds per Session</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <select class="selectpicker" name="jPrefsRounds" id="jPrefsRounds" data-size="10" data-width="auto">
        <?php for ($i=1; $i <= 5; $i++) { ?>
        <option value="<?php echo $i; ?>" <?php if ((isset($_SESSION['jPrefsRounds'])) && ($_SESSION['jPrefsRounds'] == $i)) echo "SELECTED"; else { if ($i == 1) echo "SELECTED"; }?>><?php echo $i; ?></option>
        <?php } ?>
        </select>
        <span id="helpBlock" class="help-block"><p>The maximum number of judging rounds for each <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging">defined judging session</a>.</span>
	</div>
</div>
<div class="form-group">
	<label for="jPrefsMaxBOS" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Maximum Places in BOS Round</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <select class="selectpicker" name="jPrefsMaxBOS" id="jPrefsMaxBOS" data-size="10" data-width="auto">
            <?php for ($i=1; $i <= 4; $i++) { ?>
            <option value="<?php echo $i; ?>" <?php if ((isset($_SESSION['jPrefsMaxBOS'])) && ($_SESSION['jPrefsMaxBOS'] == $i)) echo "SELECTED"; else { if (!isset($_SESSION['jPrefsMaxBOS']) && $i == 3) echo "SELECTED"; }?>><?php echo $i; ?></option>
            <?php } ?>
        </select>
        <span id="helpBlock" class="help-block"><p>The maximum number of places available to award and display for each style type. Number does not include Honorable Mention. Of course, all places do not need to be awarded by BOS judges.</p><p>This is <strong>NOT</strong> the number of entries for staff to pull for the BOS round. That methodology is determined for each <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=style_types">style type</a> individually.</span>
	</div>
</div>
<div class="bcoem-admin-element hidden-print">
	<div class="form-group">
		<div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-4">
			<input type="submit" name="Submit" id="setJudgingPrefs" class="btn btn-primary" aria-describedby="helpBlock" value="Set Preferences" />
		</div>
	</div>
</div>
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin","default",$msg,$id); ?>">
<?php } ?>
</form>