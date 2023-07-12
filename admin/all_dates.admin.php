<?php
$suggested_open = FALSE;
$suggested_close = FALSE;
$judging_open_date = "";
$judging_close_date = "";
$non_judging_count = 0;
$judging_session_js = ""; 

if ($_SESSION['prefsEval'] == 1) {

    if ((isset($_SESSION['jPrefsJudgingOpen'])) && (!empty($_SESSION['jPrefsJudgingOpen']))) $judging_open_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['jPrefsJudgingOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system"); 
    else {
        $judging_open_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $suggested_open_date, $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system");
        $suggested_open = TRUE;
    }
    if ((isset($_SESSION['jPrefsJudgingClosed'])) && (!empty($_SESSION['jPrefsJudgingClosed']))) $judging_close_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['jPrefsJudgingClosed'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system"); 
    else {
        $judging_close_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $suggested_close_date, $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system");
        $suggested_close = TRUE;
    }

}

?>
<p class="lead"><?php echo $_SESSION['contestName']." Competition-Related Dates"; ?></p>
<p>All competition-related dates for various functions are listed below. Useful when resetting the software for another competition instance after archiving or purging or to adjust any function's date/time for the current competition iteration.</p>
<form data-toggle="validator" role="form" class="form-horizontal" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?action=dates&amp;dbTable=default">
<h3>Entry-Related</h3>
<div class="form-group"><!-- Form Group REQUIRED Text Input -->
    <label for="contestEntryOpen" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Entry Window Open</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <div class="input-group has-warning">
            <input class="form-control" id="contestEntryOpen" name="contestEntryOpen" type="text" value="<?php if ($section != "step4") echo
    getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestEntryOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system"); ?>" placeholder="<?php echo $current_date." ".$current_time; ?>" required>
            <span class="input-group-addon" id="contestHost-addon2"><span class="fa fa-star"></span></span>
        </div>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group REQUIRED Text Input -->
    <label for="contestEntryDeadline" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Entry Window Close</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <div class="input-group has-warning">
            <input class="form-control" id="contestEntryDeadline" name="contestEntryDeadline" type="text" size="20" value="<?php if ($section != "step4") echo
    getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestEntryDeadline'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system"); ?>" placeholder="<?php echo $current_date." ".$current_time; ?>" required>
            <span class="input-group-addon" id="contestHost-addon2"><span class="fa fa-star"></span></span>
        </div>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group REQUIRED Text Input -->
    <label for="contestDropoffOpen" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Drop-Off Window Open</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
            <input class="form-control" id="contestDropoffOpen" name="contestDropoffOpen" type="text" value="<?php if (($section != "step4") && ((isset($row_contest_dates['contestDropoffOpen'])) && ($row_contest_dates['contestDropoffOpen'] > 0))) echo
    getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestDropoffOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system"); ?>" placeholder="<?php echo $current_date." ".$current_time; ?>">
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group REQUIRED Text Input -->
    <label for="contestDropoffDeadline" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Drop-Off Window Close</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
            <input class="form-control" id="contestDropoffDeadline" name="contestDropoffDeadline" type="text" value="<?php if (($section != "step4") && ((isset($row_contest_dates['contestDropoffDeadline'])) && ($row_contest_dates['contestDropoffDeadline'] > 0))) echo
    getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestDropoffDeadline'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system"); ?>" placeholder="<?php echo $current_date." ".$current_time; ?>">
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group REQUIRED Text Input -->
    <label for="contestShippingOpen" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Shipping Window Open</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
            <input class="form-control" id="contestShippingOpen" name="contestShippingOpen" type="text" value="<?php if (($section != "step4") && ((isset($row_contest_dates['contestShippingOpen'])) && ($row_contest_dates['contestShippingOpen'] > 0))) echo
    getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestShippingOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system"); ?>" placeholder="<?php echo $current_date." ".$current_time; ?>">
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group REQUIRED Text Input -->
    <label for="contestShippingDeadline" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Shipping Window Close</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <input class="form-control" id="contestShippingDeadline" name="contestShippingDeadline" type="text" value="<?php if (($section != "step4") && ((isset($row_contest_dates['contestShippingDeadline'])) && ($row_contest_dates['contestShippingDeadline'] > 0))) echo
    getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestShippingDeadline'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system"); ?>" placeholder="<?php echo $current_date." ".$current_time; ?>" >
    </div>
</div><!-- ./Form Group -->

<h3>Account Registration</h3>
<div class="form-group"><!-- Form Group REQUIRED Text Input -->
    <label for="contestRegistrationOpen" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Entrant Open</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <div class="input-group has-warning">
            <input class="form-control" id="contestRegistrationOpen" name="contestRegistrationOpen" type="text" value="<?php if ($section != "step4") echo
    getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestRegistrationOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system"); ?>" placeholder="<?php echo $current_date." ".$current_time; ?>" required>
            <span class="input-group-addon" id="contestHost-addon2"><span class="fa fa-star"></span></span>
        </div>
        <span id="helpBlock" class="help-block with-errors">The date and time when general entrants are able to create an account.</span>
    </div>
</div><!-- ./Form Group -->


<div class="form-group"><!-- Form Group REQUIRED Text Input -->
    <label for="contestRegistrationDeadline" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Entrant Close</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <div class="input-group has-warning">
            <input class="form-control" id="contestRegistrationDeadline" name="contestRegistrationDeadline" type="text" size="20" value="<?php if ($section != "step4") echo
    getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestRegistrationDeadline'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system"); ?>" placeholder="<?php echo $current_date." ".$current_time; ?>" required>
            <span class="input-group-addon" id="contestHost-addon2"><span class="fa fa-star"></span>
        </div>
        <span id="helpBlock" class="help-block with-errors">The deadline for general entrants to create an account.</span>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group REQUIRED Text Input -->
    <label for="contestJudgeOpen" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Judge/Steward Open</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <div class="input-group has-warning">
            <input class="form-control" id="contestJudgeOpen" name="contestJudgeOpen" type="text" value="<?php if ($section != "step4") echo
    getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestJudgeOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system"); ?>" placeholder="<?php echo $current_date." ".$current_time; ?>" required>
            <span class="input-group-addon" id="contestHost-addon2"><span class="fa fa-star"></span></span>
        </div>
        <span id="helpBlock" class="help-block with-errors">The date and time when judges and stewards are able to create an account and indicate their session preferences.</span>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="contestJudgeDeadline" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Judge/Steward Close</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <div class="input-group has-warning">
            <input class="form-control" id="contestJudgeDeadline" name="contestJudgeDeadline" type="text" size="20" value="<?php if ($section != "step4") echo
    getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestJudgeDeadline'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system"); ?>" placeholder="<?php echo $current_date." ".$current_time; ?>" required>
            <span class="input-group-addon" id="contestHost-addon2"><span class="fa fa-star"></span></span>
        </div>
        <span id="helpBlock" class="help-block with-errors">The deadline for judges and stewards to create an account and indicate their session preferences.</span>
    </div>
</div><!-- ./Form Group -->
<?php if ($_SESSION['prefsEval'] == 1) { ?>
<!-- If Evals Enabled -->
<h3>Judging Open and Close</h3>
<div class="form-group"><!-- Form Group REQUIRED Text Input -->
    <label for="jPrefsJudgingOpen" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Open</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <input class="form-control" id="jPrefsJudgingOpen" name="jPrefsJudgingOpen" type="text" value="<?php echo $judging_open_date; ?>" placeholder="<?php echo $current_date." ".$current_time; ?>" required>
        <div id="helpBlock" class="help-block">Indicate when judges will be allowed access to their Judging Dashboard to add entry evaluations.  Typically, the open date begins the day and time the first judging session begins.
            <?php if ($suggested_open) echo "<br><span class='text-warning' style=\"margin-bottom:5px;\">* The date and time above is suggested and is the system default. It is the the earliest judging session's start time.</span>";  ?>
        </div>
    </div>
</div>
<div class="form-group"><!-- Form Group REQUIRED Text Input -->
    <label for="jPrefsJudgingClosed" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Close</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <input class="form-control" id="jPrefsJudgingClosed" name="jPrefsJudgingClosed" type="text" size="20" value="<?php echo $judging_close_date; ?>" placeholder="<?php echo $current_date." ".$current_time; ?>" required>
        <div id="helpBlock" class="help-block">The closing date and time is the absolute latest judges will be allowed to enter or edit their evaluations and scores.
            <?php if ($suggested_close) echo "<br><span class='text-warning' style=\"margin-bottom:5px;\">* The date and time above is suggested and is the system default. It is the <u>last</u> judging session's start time + 8 hours.</span>"; ?>
            <div style="margin-top:5px" class="btn-group bcoem-admin-element" role="group" aria-label="judgingWindowModal">
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
                <p>Indicate when judges will be allowed access to their Judging Dashboard to add entry evaluations. Typically, the open date begins the day and time the first judging session begins. The closing date and time is the absolute latest judges will be allowed to enter or edit their evaluations and scores.</p>
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
<?php } // END if ($_SESSION['prefsEval'] == 1) ?>

<!-- Loop through Judging Sessions and show dates -->
<h3>Judging Sessions</h3>
<?php 
if ($totalRows_judging_locs == 0) echo "<p>No judging sessions have been defined. <a href=\"".$base_url."index.php?section=admin&amp;go=judging&amp;action=add\">Add a judging session</a>?</p>"; 

if ($totalRows_judging_locs > 0) { 
    
    do { 

        $judging_date = "";
        $judging_end_date = "";
        $judging_date .= getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging_locs['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system");
        if (!empty($row_judging_locs['judgingDateEnd'])) $judging_end_date .= getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging_locs['judgingDateEnd'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system");
        if ($row_judging_locs['judgingLocType'] == "1") $session_type = "Distributed";
        else $session_type = "Traditional";

        $judging_session_js .= "$('#judgingDate-".$row_judging_locs['id']."').datetimepicker({format: 'YYYY-MM-DD hh:mm A'}); $('#judgingDateEnd-".$row_judging_locs['id']."').datetimepicker({format: 'YYYY-MM-DD hh:mm A'}); ";

        if ($row_judging_locs['judgingLocType'] < 2) {

?>
<div class="form-group"><!-- Form Group REQUIRED Text Input -->
    <input type="hidden" name="id[]" value="<?php echo $row_judging_locs['id']; ?>">
    <label for="judgingDate-<?php echo $row_judging_locs['id']; ?>" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $row_judging_locs['judgingLocName']; ?> - Session Start</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group date has-warning">
            <!-- Input Here -->
            <input class="form-control" id="judgingDate-<?php echo $row_judging_locs['id']; ?>" name="judgingDate<?php echo $row_judging_locs['id']; ?>" type="text" value="<?php echo $judging_date; ?>" placeholder="" required>
            <span class="input-group-addon"><span class="fa fa-star"></span></span>
        </div>
        <span class="help-block">Provide a start date and time for the session.<br>Session type is set to <strong><?php echo $session_type; ?></strong>. To change the type or other information, <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging&amp;action=edit&amp;id=<?php echo $row_judging_locs['id']; ?>">edit the <?php echo $row_judging_locs['judgingLocName']; ?> session</a>.</span>
    </div>
</div><!-- ./Form Group -->
<?php if ($row_judging_locs['judgingLocType'] == "1") { ?>
<div class="form-group"><!-- Form Group REQUIRED Text Input -->
    <label for="judgingDateEnd-<?php echo $row_judging_locs['id']; ?>" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $row_judging_locs['judgingLocName']; ?> - Session End</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group date has-warning">
            <!-- Input Here -->
            <input class="form-control" id="judgingDateEnd-<?php echo $row_judging_locs['id']; ?>" name="judgingDateEnd<?php echo $row_judging_locs['id']; ?>" type="text" value="<?php echo $judging_end_date; ?>" placeholder="">
            <span class="input-group-addon"><span class="fa fa-star"></span></span>
        </div>
        <span class="help-block">For a distributed session, it is required that you provide an end date and time that will serve as a deadline for judges to submit their evaluations.</span>
    </div>
</div><!-- ./Form Group -->
<?php       } // END if ($row_judging_locs['judgingLocType'] == "1")
        }  // END if ($row_judging_locs['judgingLocType'] < 2)
    } while($row_judging_locs = mysqli_fetch_assoc($judging_locs)); 
} // end if ($totalRows_judging_locs > 0) 
?>

<h3>Non-Judging Sessions</h3>
<?php

if ($totalRows_judging > 0) {
    
    do { 
        
        $judging_date = "";
        $judging_end_date = "";
        $judging_date .= getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system");
        if (!empty($row_judging['judgingDateEnd'])) $judging_end_date .= getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDateEnd'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system");

        $judging_session_js .= "$('#judgingDate-".$row_judging['id']."').datetimepicker({format: 'YYYY-MM-DD hh:mm A'}); $('#judgingDateEnd-".$row_judging['id']."').datetimepicker({format: 'YYYY-MM-DD hh:mm A'}); ";

        if ($row_judging['judgingLocType'] == 2) {
        $non_judging_count += 1;

?>
<div class="form-group"><!-- Form Group REQUIRED Text Input -->
    <input type="hidden" name="id[]" value="<?php echo $row_judging['id']; ?>">
    <label for="judgingDate-<?php echo $row_judging['id']; ?>" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $row_judging['judgingLocName']; ?> - Session Start</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group date has-warning">
            <!-- Input Here -->
            <input class="form-control" id="judgingDate-<?php echo $row_judging['id']; ?>" name="judgingDate<?php echo $row_judging['id']; ?>" type="text" value="<?php echo $judging_date; ?>" placeholder="" required>
            <span class="input-group-addon"><span class="fa fa-star"></span></span>
        </div>
        <span class="help-block">Provide a start date and time for the session.</span>
    </div>
</div><!-- ./Form Group -->
<?php
        } // END if ($row_judging['judgingLocType'] == 2)
    } while($row_judging = mysqli_fetch_assoc($judging)); 
} // end if ($totalRows_judging > 0)
if ($non_judging_count == 0) echo "<p>No non-judging sessions have been defined. <a href=\"".$base_url."index.php?section=admin&amp;go=non-judging&amp;action=add\">Add a non-judging session</a>?</p>"; 
?>

<!-- If Winner Display Enabled -->
<h3>Results</h3>
<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="prefsWinnerDelay" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Display Date and Time</label>
    <div class="col-lg-6 col-md-4 col-sm-8 col-xs-12">
            <input class="form-control" id="prefsWinnerDelay" name="prefsWinnerDelay" type="text" value="<?php if ((isset($_SESSION['prefsWinnerDelay'])) && ($_SESSION['prefsWinnerDelay'] > 0)) echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['prefsWinnerDelay'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system"); ?>" placeholder="<?php echo $current_date." ".$current_time; ?>" >
        <span id="helpBlock" class="help-block">Date and time when the system will display winners. If a date and time are specified, winner display will be enabled. If the date and time are removed or blank, winner display will be disabled.</span>
        <div class="help-block with-errors"></div>
    </div>
</div>

<h3>Awards Ceremony</h3>
<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="contestAwardsLocDate" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Date and Time</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
            <input class="form-control" id="contestAwardsLocDate" name="contestAwardsLocDate" type="text" value="<?php if (($section != "step4") && ((isset($_SESSION['contestAwardsLocTime'])) && ($_SESSION['contestAwardsLocTime'] > 0))) echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['contestAwardsLocTime'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system"); ?>" placeholder="<?php echo $current_date." ".$current_time; ?>" >
        <span id="helpBlock" class="help-block">Provide even if the date of judging is the same.</span>
    </div>
</div><!-- ./Form Group -->

<div class="bcoem-admin-element hidden-print">
    <div class="form-group">
        <div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-4">
            <input name="submit" type="submit" class="btn btn-primary" value="Update Competition Dates">
        </div>
    </div>
</div>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin","default",$msg,$id); ?>">

</form>
<script type="text/javascript">
    $(document).ready(function() {
        <?php echo $judging_session_js; ?>
    });
</script>