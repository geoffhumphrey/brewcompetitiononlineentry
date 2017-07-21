<?php if ($go == "preferences") {
include (DB.'styles.db.php');
$prefsUSCLEx = "";
	
if (strpos($styleSet,"BABDB") === false) {
	
	//$prefsUSCLEx = $query_styles;

	do {
	$checked = "";
	if ($go == "preferences") {
		$a = explode(",", $row_limits['prefsUSCLEx']);
		$b = $row_styles['id'];
		foreach ($a as $value) {
			if ($value == $b) $checked = "CHECKED";
		}
	}

	if ($row_styles['id'] != "") $prefsUSCLEx .= "<div class=\"checkbox\"><label><input name=\"prefsUSCLEx[]\" type=\"checkbox\" value=\"".$row_styles['id']."\" ".$checked."> ".ltrim($row_styles['brewStyleGroup'], "0").$row_styles['brewStyleNum'].": ".$row_styles['brewStyle']."</label></div>";

	} while ($row_styles = mysqli_fetch_assoc($styles));

} else {

	include (INCLUDES.'ba_constants.inc.php');

	$ba_exceptions = "";

	$exceptions = explode(",",$row_limits['prefsUSCLEx']);

	foreach ($_SESSION['styles'] as $ba_styles => $stylesData) {

		if (is_array($stylesData) || is_object($stylesData)) {

			foreach ($stylesData as $key => $ba_style) {

				// Likes
				$ba_exceptions_selected = "";
				if (in_array($ba_style['id'],$exceptions)) $ba_exceptions_selected = "CHECKED";

				$ba_exceptions .= "<div class=\"checkbox\">";
				$ba_exceptions .= "<label>";
				$ba_exceptions .= "<input name=\"prefsUSCLEx[]\" type=\"checkbox\" value=\"".$ba_style['id']."\" ".$ba_exceptions_selected.">";
				$ba_exceptions .= $ba_style['name'];
				$ba_exceptions .= "</label>";
				$ba_exceptions .= "</div>";

			} // end foreach ($stylesData as $data => $ba_style)

		} // end if (is_array($stylesData) || is_object($stylesData))

	} // end foreach ($_SESSION['styles'] as $styles => $stylesData)

	$prefsUSCLEx = $ba_exceptions;
}
} ?>
<?php if ($section == "admin") { ?>
<p class="lead"><?php echo $_SESSION['contestName'].": Set Website Preferences"; ?></p>
<div class="bcoem-admin-element hidden-print">
	<div class="btn-group" role="group" aria-label="...">
		<a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_preferences"><span class="fa fa-cog"></span> Competition Organization Preferences</a>
	</div><!-- ./button group -->
</div>
<?php } ?>
<script type='text/javascript'>//<![CDATA[
$(document).ready(function(){

    <?php
    $styleKey = "";
    if ((isset($row_limits['prefsStyleSet'])) && (strpos($row_limits['prefsStyleSet'],"BABDB")) !== false) {

    // Get API Key if set
    $styleKey = explode("|",$row_limits['prefsStyleSet']);

    ?>
    $("#styleSetAPIKey").show("fast");
    $("#helpBlockBAAPI").show("fast");
    $("#prefsHideSpecific").hide("fast");
    <?php } else { ?>
    // show/hide divs on load if no value
    $("#styleSetAPIKey").hide("fast");
    $("#helpBlockBAAPI").hide("fast");
    $("#prefsHideSpecific").show("fast");
    <?php } ?>

    <?php if ((isset($row_limits['prefsStyleSet'])) && ($row_limits['prefsStyleSet'] == "BJCP2008")) { ?>
    $("#helpBlockBJCP2008").show("fast");
    <?php } else { ?>
    $("#helpBlockBJCP2008").hide("fast");
    <?php } ?>

    $("#prefsStyleSet").change(function() {

        if ($("#prefsStyleSet").val() == "BABDB") {
            $("#styleSetAPIKey").show("fast");
            $("#helpBlockBAAPI").show("fast");
            $("#helpBlockBJCP2008").hide("fast");
            $("#prefsHideSpecific").hide("fast");
        }

        else if ($("#prefsStyleSet").val() == "BJCP2008") {
            $("#styleSetAPIKey").hide("fast");
            $("#helpBlockBAAPI").hide("fast");
            $("#helpBlockBJCP2008").show("fast");
            $("#prefsHideSpecific").show("fast");
        }

        else  {
            $("#styleSetAPIKey").hide("fast");
            $("#helpBlockBAAPI").hide("fast");
            $("#helpBlockBJCP2008").hide("fast");
            $("#prefsHideSpecific").show("fast");
        }

    }); // end $("#prefsStyleSet").change(function()

    <?php if ($row_limits['prefsUserSubCatLimit'] > 0) { ?>
    $("#subStyleExeptions").show("fast");
    <?php } else { ?>
    $("#subStyleExeptions").hide("fast");
    <?php } ?>

    $("#prefsUserSubCatLimit").change(function() {

        if (
            $("#prefsUserSubCatLimit").val() == ""){
            $("#subStyleExeptions").hide("fast");
        }

        <?php for ($i=1; $i <= 25; $i++) { ?>
        else if (
            $("#prefsUserSubCatLimit").val() == "<?php echo $i; ?>"){
            $("#subStyleExeptions").show("fast");
        }
        <?php } ?>

    }); // end $("#prefsUserSubCatLimit").change(function()

}); // end $(document).ready(function(){
</script>
<form class="form-horizontal" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php if ($section == "step3") echo "setup"; else echo $section; ?>&amp;action=<?php if ($section == "step3") echo "add"; else echo "edit"; ?>&amp;dbTable=<?php echo $preferences_db_table; ?>&amp;id=1" name="form1">
<input type="hidden" name="prefsRecordLimit" value="9999" />
<h3>General</h3>

<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsProEdition" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Competition Type</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsProEdition" value="0" id="prefsProEdition_0"  <?php if (($section != "step3") && ($_SESSION['prefsProEdition'] == "0")) echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?> /> Amateur
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsProEdition" value="1" id="prefsProEdition_1" <?php if (($section != "step3") && ($_SESSION['prefsProEdition'] == "1")) echo "CHECKED"; ?>/> Professional
            </label>
        </div>
        <span id="helpBlock" class="help-block">Indicate whether the participants in the competition will be individual amateur brewers or licensed breweries with designated points of contact.</span>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsDisplayWinners" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Winner Display</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsDisplayWinners" value="Y" id="prefsDisplayWinners_0"  <?php if (($section != "step3") && ($_SESSION['prefsDisplayWinners'] == "Y")) echo "CHECKED"; ?> /> Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsDisplayWinners" value="N" id="prefsDisplayWinners_1" <?php if (($section != "step3") && ($_SESSION['prefsDisplayWinners'] == "N")) echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?>/> Disable
            </label>
        </div>
        <span id="helpBlock" class="help-block">Indicate if the winners of the competition for each category and Best of Show Style Type will be displayed.</span>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="prefsWinnerDelay" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Winner Display Delay</label>
    <div class="col-lg-6 col-md-4 col-sm-8 col-xs-12">
    	<!-- Input Here -->
        	<input class="form-control" id="prefsWinnerDelay" name="prefsWinnerDelay" type="text" value="<?php if ($section == "step3") echo "24"; else echo $_SESSION['prefsWinnerDelay']; ?>" placeholder="">
        <span id="helpBlock" class="help-block">Hours to delay displaying winners after the <em>start</em> time of the final judging session.</span>
    </div>
</div><!-- ./Form Group -->

<!--
<div class="form-group">
    <label for="prefsWinnerDelay" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Winner Display Date/Time</label>
    <div class="col-lg-6 col-md-4 col-sm-8 col-xs-12">
        	<input class="form-control" id="prefsWinnerDelay" name="prefsWinnerDelay" type="text" value="<?php if ($section == "step3") echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], time(), $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system"); else echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['prefsWinnerDelay'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system"); ?>" placeholder="<?php if (strpos($section, "step") === FALSE) { echo $current_date." ".$current_time; } ?>">
        <span id="helpBlock" class="help-block">Date and time to display winners on the home page of the site.</span>
    </div>
</div>
-->

<div class="form-group"><!-- Form Group Radio STACKED -->
	<label for="prefsWinnerMethod" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Winner Place Distribution Method</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<div class="input-group">
			<!-- Input Here -->
			<label class="radio-inline">
				<input type="radio" name="prefsWinnerMethod" value="0" id="prefsWinnerMethod_0" <?php if (($section == "step3") || ($_SESSION['prefsWinnerMethod'] == "0")) echo "CHECKED"; ?>> By Table
			</label>
			<label class="radio-inline">
				<input type="radio" name="prefsWinnerMethod" value="1" id="prefsWinnerMethod_1" <?php if ($_SESSION['prefsWinnerMethod'] == "1") echo "CHECKED"; ?>> By Style
			</label>
			<label class="radio-inline">
				<input type="radio" name="prefsWinnerMethod" value="2" id="prefsWinnerMethod_2" <?php if ($_SESSION['prefsWinnerMethod'] == "2") echo "CHECKED"; ?>> By Sub-Style
			</label>
		</div>
		<span id="helpBlock" class="help-block">How the competition will award places for winning entries.</span>
	</div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsContact" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Contact Form</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsContact" value="Y" id="prefsContact_0"  <?php if ($_SESSION['prefsContact'] == "Y") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?> /> Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsContact" value="N" id="prefsContact_1" <?php if ($_SESSION['prefsContact'] == "N") echo "CHECKED"; ?>/> Disable
            </label>
        </div>
        <span id="helpBlock" class="help-block">
		<div class="btn-group" role="group" aria-label="contactFormModal">
			<div class="btn-group" role="group">
				<button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#contactFormModal">
				   Contact Form Info
				</button>
			</div>
            <div class="btn-group" role="group">
                <a href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;&amp;go=default&amp;action=email&amp;filter=test-email&amp;id=<?php echo $_SESSION['brewerID']; ?>" role="button" class="btn btn-xs btn-primary">Send Test Email</a>
			</div>
		</div>
		<p>If you are not sure that your server supports sending email via PHP scripts, click the &ldquo;Send Test Email&rdquo; button above to send an email to <?php echo $_SESSION['loginUsername']; ?>. Be sure to check your spam folder.</p>
		</span>
    </div>
</div><!-- ./Form Group -->


<!-- Modal -->
<div class="modal fade" id="contactFormModal" tabindex="-1" role="dialog" aria-labelledby="contactFormModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="contactFormModalLabel">Contact Form Info</h4>
            </div>
            <div class="modal-body">
                <p>Enable or disable your installation's contact form. This may be necessary if your site&rsquo;s server does not support PHP&rsquo;s <a href="http://php.net/manual/en/function.mail.php" target="_blank">mail()</a> function. Admins should test the form before disabling as the form is the more secure option. Admins should use the &ldquo;Send Test Email&rdquo; button to test the function.</p>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->

<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="EmailRegConfirm" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Confirmation Emails</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsEmailRegConfirm" value="1" id="prefsEmailRegConfirm_1"  <?php if ($_SESSION['prefsEmailRegConfirm'] == "1") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?> /> Enable
          </label>
            <label class="radio-inline">
                <input type="radio" name="prefsEmailRegConfirm" value="0" id="prefsEmailRegConfirm_0" <?php if ($_SESSION['prefsEmailRegConfirm'] == "0") echo "CHECKED"; ?>/> Disable
            </label>
        </div>
        <span id="helpBlock" class="help-block">
        <div class="btn-group" role="group" aria-label="contactFormModal">
			<div class="btn-group" role="group">
				<button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#regEmailFormModalLabel">
				   Confirmation Emails Info
				</button>
			</div>
            <div class="btn-group" role="group">
                <a href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;&amp;go=default&amp;action=email&amp;filter=test-email&amp;id=<?php echo $_SESSION['brewerID']; ?>" role="button" class="btn btn-xs btn-primary">Send Test Email</a>
			</div>
		</div>
		<p>If you are not sure that your server supports sending email via PHP scripts, click the &ldquo;Send Test Email&rdquo; button above to send an email to <?php echo $_SESSION['loginUsername']; ?>. Be sure to check your spam folder.</p>
		</span>
    </div>
</div><!-- ./Form Group -->

<!-- Modal -->
<div class="modal fade" id="regEmailFormModalLabel" tabindex="-1" role="dialog" aria-labelledby="regEmailFormModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="regEmailFormModalLabel">Confirmation Emails</h4>
            </div>
            <div class="modal-body">
            	<p>Do you want a system-generated confirmation email sent to all users upon registering their account information?</p>
            	<!--
                <p>Do you want a system-generated confirmation email sent to:</p>
                <ul>
                	<li>All users upon registering and/or changing their account information?</li>
                    <li>All users who register as a judge and/or steward with information specific to their role?</li>
                    <li>All mid- and top-level admin users when a participant registers as a judge and/or steward?</li>
                </li>
                </ul>
                -->
                <p>Please note that these system-generated emails may not be possible if your site&rsquo;s server does not support PHP&rsquo;s <a href="http://php.net/manual/en/function.mail.php" target="_blank">mail()</a> function. Admins should use the &ldquo;Send Test Email&rdquo; button to test the function.</p>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->

<div class="form-group"><!-- Form Group NOT REQUIRED Select -->
	<label for="prefsTheme" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Site Theme</label>
	<div class="col-lg-6 col-md-5 col-sm-8 col-xs-12">
	<!-- Input Here -->
	<select class="selectpicker" name="prefsTheme" id="prefsTheme" data-width="auto">
		<?php foreach ($theme_name as $theme) {
			$themes = explode("|",$theme);
		?>
    	<option value="<?php echo $themes['0']; ?>" <?php if ($_SESSION['prefsTheme'] ==  $themes['0']) echo " SELECTED"; ?> /><?php echo  $themes['1']; ?></option>
    	<?php } ?>
	</select>
	</div>
</div><!-- ./Form Group -->

<?php if (!HOSTED) { ?>
<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsSEF" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Search Engine Friendly URLs</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsSEF" value="Y" id="prefsSEF_0"  <?php if ($_SESSION['prefsSEF'] == "Y") echo "CHECKED"; ?> />Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsSEF" value="N" id="prefsSEF_1" <?php if ($_SESSION['prefsSEF'] == "N") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?>/>Disable
            </label>
        </div>
		<span id="helpBlock" class="help-block">
		<div class="btn-group" role="group" aria-label="SEFModal">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#SEFModal">
               SEF URLs Info
            </button>
        </div>
		</div>
    </div>
</div><!-- ./Form Group -->
<!-- Modal -->
<div class="modal fade" id="SEFModal" tabindex="-1" role="dialog" aria-labelledby="SEFModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="entryFormModalLabel">SEF URLs Info</h4>
            </div>
            <div class="modal-body">
                <p>Generally, this can be enabled for most installations. However, if your installation is experiencing multiple &ldquo;Page Not Found&rdquo;  errors (404), select &ldquo;Disable&rdquo; to turn off Search Engine Friendly (SEF) URLs.</p>
                <p>If you enable this and receive 404 errors, <?php if ($section == "step3") echo "<strong>after setup has been completed</strong>, "; ?>navigate to the login screen at <a href="<?php echo $base_url; ?>index.php?section=login" target="_blank"><?php echo $base_url; ?>index.php?section=login</a> to log back in and &ldquo;turn off&rdquo;  this feature.</p>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->
<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsUseMods" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Custom Modules</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsUseMods" value="Y" id="prefsUseMods_0"  <?php if ($_SESSION['prefsUseMods'] == "Y") echo "CHECKED"; ?> /> Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsUseMods" value="N" id="prefsUseMods_1" <?php if ($_SESSION['prefsUseMods'] == "N") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?>/> Disable
            </label>
        </div>
        <span id="helpBlock" class="help-block"><strong>FOR ADVANCED USERS.</strong> Utilize the ability to add custom modules that extend BCOE&amp;M's core functionality.</span>
    </div>
</div><!-- ./Form Group -->
<?php } else { ?>
<input type="hidden" name="prefsSEF" value="N" />
<input type="hidden" name="prefsUseMods" value="N" />
<?php } ?>

<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsDropOff" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Drop-off Location Display</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsDropOff" value="1" id="prefsDropOff_0"  <?php if (($section != "step3") && ($_SESSION['prefsDropOff'] == "1")) echo "CHECKED"; if ($section == "step3") echo "CHECKED";  ?> /> Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsDropOff" value="0" id="prefsDropOff_1" <?php if (($section != "step3") && ($_SESSION['prefsDropOff'] == "0")) echo "CHECKED";?>/> Disable
            </label>
        </div>
        <span id="helpBlock" class="help-block">Disable if your competition does not have drop-off locations.</span>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsShipping" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Shipping Location Display</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsShipping" value="1" id="prefsShipping_0"  <?php if (($section != "step3") && ($_SESSION['prefsShipping'] == "1")) echo "CHECKED"; if ($section == "step3") echo "CHECKED";  ?> /> Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsShipping" value="0" id="prefsShipping_1" <?php if (($section != "step3") && ($_SESSION['prefsShipping'] == "0")) echo "CHECKED"; ?>/> Disable
            </label>
        </div>
        <span id="helpBlock" class="help-block">Disable if your competition does not have an entry shipping location.</span>
    </div>
</div><!-- ./Form Group -->

<h3>Entries</h3>
<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsStyleSet" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Styleset</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
    <!-- Input Here -->
	<select class="selectpicker" name="prefsStyleSet" id="prefsStyleSet" data-size="4">
    	<option value="BJCP2015" <?php if ($section == "step3") echo "SELECTED"; elseif ($row_limits['prefsStyleSet'] == "BJCP2015") echo "SELECTED"; ?>>BJCP 2015</option>
        <option value="BJCP2008" <?php if ($row_limits['prefsStyleSet'] == "BJCP2008") echo "SELECTED"; ?>>BJCP 2008</option>
        <option value="BABDB" <?php if (strpos($row_limits['prefsStyleSet'],"BABDB") !== false) echo "SELECTED"; ?>>Brewers Association</option>
	</select>
    <div id="helpBlockBJCP2008" class="help-block">The BJCP 2008 style guidelines have been deprecated and will be completely removed in version 2.2.0. The 2008 guidelines are considered by the BJCP as &quot;obsolete.&quot;</div>
    <div id="helpBlockBAAPI" class="help-block">Brewers Association style data is housed and maintained by the <a href="http://www.brewerydb.com/" target="_blank">Brewery DB</a>. An API key issued by the Brewery DB is <strong>required</strong> for the styles to be utilized by BCOE&amp;M. Please note that the latest <a href="https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/" target="_blank">BA style set</a> may <strong>not</strong> be available.</div>
    </div>
</div><!-- ./Form Group -->

<div id="styleSetAPIKey">
<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="prefsStyleSetAPIKey" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Brewery DB API Key</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
    	<!-- Input Here -->
        <input class="form-control" id="prefsStyleSetAPIKey" name="prefsStyleSetAPIKey" type="text" value="<?php if (isset($styleKey[1])) echo $styleKey[1]; ?>" placeholder="">
		<span id="helpBlock" class="help-block">
		<div class="btn-group" role="group" aria-label="prefsStyleSetAPIKeyModal">
            <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#prefsStyleSetAPIKeyModal">
               Brewery DB API Key Info
            </button>
		</div>
		</span>
    </div>
</div><!-- ./Form Group -->
<!-- Modal -->
<div class="modal fade" id="prefsStyleSetAPIKeyModal" tabindex="-1" role="dialog" aria-labelledby="prefsStyleSetAPIKeyModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="prefsStyleSetAPIKeyModalLabel">Brewery DB API Key Info</h4>
            </div>
            <div class="modal-body">
                <p>The Brewers Association styles are available via the <a href="https://www.brewerydb.com" target="_blank">Brewery DB</a>'s API. To utilize these styles in BCOE&amp;M, you will need an API key issued by Brewery DB.</p>
                <p>To obtain an API key from the Brewery DB project website, you will need to <a href="https://www.brewerydb.com/auth/signup" target="_blank">create an account on the Brewery DB website</a> and register your installation as an app.</p>
                <p>After creating your account, click the &quot;Register New App&quot; button, provide a name for the app (something like &quot;BCOE&amp;M <?php echo $_SESSION['contestName']; ?>&quot;), web address, and platform (choose &quot;Web Application&quot;). Once you have your API key, enter it here in the designated field.</p>
                <p><strong>Please note:</strong> The Brewers Association styles will not populate without a valid API key issued by the Brewery DB.</p>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->
</div>


<div class="form-group"><!-- Form Group NOT REQUIRED Select -->
	<label for="prefsEntryForm" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Printed Entry Form and/or Bottle Labels</label>
	<div class="col-lg-6 col-md-3 col-sm-8 col-xs-12">
	<!-- Input Here -->
	<select class="selectpicker" name="prefsEntryForm" id="prefsEntryForm" data-size="12" data-width="auto">
		<option value="1" <?php if (($section != "step3") && ($_SESSION['prefsEntryForm'] == "1")) echo " SELECTED"; ?> />BCOE&amp;M (Bottle Labels Only)</option>
		<option value="2" <?php if (($section == "step3") || ($_SESSION['prefsEntryForm'] == "2")) echo " SELECTED"; ?> />BCOE&amp;M with Barcode/QR Code (Bottle Labels Only)</option>
        <option value="0" <?php if (($section != "step3") && ($_SESSION['prefsEntryForm'] == "0")) echo " SELECTED"; ?> />BCOE&amp;M Anonymous with Barcode/QR Code (Bottle Labels Only)</option>
        <option value="B" <?php if (($section != "step3") && ($_SESSION['prefsEntryForm'] == "B")) echo " SELECTED"; ?> />BJCP Official</option>
        <option value="E" <?php if (($section != "step3") && ($_SESSION['prefsEntryForm'] == "E")) echo " SELECTED"; ?> />BJCP Official (Bottle Labels Only)</option>
        <option value="N" <?php if (($section != "step3") && ($_SESSION['prefsEntryForm'] == "N")) echo " SELECTED"; ?> />BJCP Official with Barcode/QR Code</option>
        <option value="C" <?php if (($section != "step3") && ($_SESSION['prefsEntryForm'] == "C")) echo " SELECTED"; ?> />BJCP Official with Barcode/QR Code (Bottle Labels Only)</option>
		<option value="M" <?php if (($section != "step3") && ($_SESSION['prefsEntryForm'] == "M")) echo " SELECTED"; ?> />Simple Metric</option>
        <option value="3" <?php if (($section != "step3") && ($_SESSION['prefsEntryForm'] == "3")) echo " SELECTED"; ?> />Simple Metric with Barcode/QR Code</option>
        <option value="U" <?php if (($section != "step3") && ($_SESSION['prefsEntryForm'] == "U")) echo " SELECTED"; ?> />Simple U.S.</option>
        <option value="4" <?php if (($section != "step3") && ($_SESSION['prefsEntryForm'] == "4")) echo " SELECTED"; ?> />Simple U.S. with Barcode/QR Code</option>
	</select>
	<span id="helpBlock" class="help-block">
		<div class="btn-group" role="group" aria-label="entryFormModal">
			<div class="btn-group" role="group">
				<button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#entryFormModal">Printed Entry Form and/or Bottle Labels Info</button>
			</div>
		</div>
	</span>
	</div>
</div><!-- ./Form Group -->
<!-- Modal -->
<div class="modal fade" id="entryFormModal" tabindex="-1" role="dialog" aria-labelledby="entryFormModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="entryFormModalLabel">Printed Entry Form and/or Bottle Labels</h4>
            </div>
            <div class="modal-body">
                <p>The <em>BJCP Official</em> options only display U.S. weights and measures.</p>
                <p>The <em>Anonymous with Barcode/QR Code</em> option provides bottle labels with only an entry number, style, barcode label, and QR code. These labels are intended to be taped to bottles by entrants before submittal, thereby saving the labor and waste of removing rubberbanded labels by competition staff when sorting. This approach is similar to the method used in the National Homebrew Competition final round.</p>
                <p>The Barcode options are intended to be used with a USB barcode scanner and the <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=checkin">barcode entry check-in function</a>.</p>
                <p>The QR code options are intended to be used with a mobile device and <a href="<?php echo $base_url; ?>qr.php" target="_blank">QR code entry check-in function</a> (requires a QR code reading app).</p>
                <div class="well">
                <p>Both the QR code and barcode options are intended to be used with the Judging Number Barcode Labels and the Judging Number Round Labels <a href="http://www.brewcompetition.com/barcode-labels" target="_blank"><strong>available for download at brewcompetition.com</strong></a>. BCOE&amp;M utilizes the&nbsp;<strong><a href="http://en.wikipedia.org/wiki/Code_39" target="_blank">Code 39 specification</a></strong> to generate all barcodes. Please make sure your scanner recognizes this type of barcode <em>before</em> implementing in your competition.</p>
                </div>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->
<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsHideRecipe" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Hide Entry Recipe Section</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsHideRecipe" value="Y" id="prefsHideRecipe_0"  <?php if ($_SESSION['prefsHideRecipe'] == "Y") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?> /> Yes
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsHideRecipe" value="N" id="prefsHideRecipe_1" <?php if ($_SESSION['prefsHideRecipe'] == "N") echo "CHECKED"; ?>/> No
            </label>
        </div>
        <span id="helpBlock" class="help-block">
			<div class="btn-group" role="group" aria-label="hideRecipeModal">
			<div class="btn-group" role="group">
				<button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#hideRecipeModal">
				   Hide Entry Recipe Section Info
				</button>
			</div>
			</div>
		</span>
    </div>
</div><!-- ./Form Group -->

<!-- Modal -->
<div class="modal fade" id="hideRecipeModal" tabindex="-1" role="dialog" aria-labelledby="hideRecipeModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="hideRecipeModalLabel">Hide Entry Recipe Section Info</h4>
            </div>
            <div class="modal-body">
                <p>Indicate if the recipe section (optional information such as malt, yeast, etc.) on the Add Entry or Edit Entry screens will be displayed. If enabled, the BeerXML Import function will not be available.</p>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->
<div id="prefsHideSpecific" class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsHideSpecific" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Hide Brewer&rsquo;s Specifics Field</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsSpecific" value="1" id="prefsSpecific_0"  <?php if ($_SESSION['prefsSpecific'] == "1") echo "CHECKED"; ?> /> Yes
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsSpecific" value="0" id="prefsSpecific_1" <?php if ($_SESSION['prefsSpecific'] == "0") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?>/> No
            </label>
        </div>
        <span id="helpBlock" class="help-block">
			<div class="btn-group" role="group" aria-label="prefsSpecificModal">
			<div class="btn-group" role="group">
				<button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#prefsSpecificModal">
				   Hide Brewer&rsquo;s Specifics Field Info
				</button>
			</div>
			</div>
		</span>
    </div>
</div><!-- ./Form Group -->
<!-- Modal -->
<div class="modal fade" id="prefsSpecificModal" tabindex="-1" role="dialog" aria-labelledby="prefsSpecificModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="prefsSpecificModalLabel">Hide Brewer&rsquo;s Specifics Field</h4>
            </div>
            <div class="modal-body">
                <p>Indicate if the Brewer&rsquo;s Specifics field on the Add Entry or Edit Entry screens will be displayed to users. The field is sometimes confused with the required &ldquo;Special Ingredients&rdquo; field. If enabled, the field will display the following message as a placeholder:</p>
                <small>***NOT REQUIRED*** Provide ONLY if you wish the judges to fully consider what you write here when evaluating and scoring your entry. Use to record specifics that you would like judges to consider when evaluating your entry that you have NOT SPECIFIED in other fields (e.g., mash technique, hop variety, honey variety, grape variety, pear variety, etc.).</small>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->
<div class="form-group"><!-- Form Group NOT REQUIRED Select -->
	<label for="prefsSpecialCharLimit" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Character Limit for Special Ingredients</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
	<!-- Input Here -->
	<select class="selectpicker" name="prefsSpecialCharLimit" id="prefsSpecialCharLimit" data-size="10">
		<?php for ($i=25; $i <= 255; $i+=5) { ?>
    	<option value="<?php echo $i; ?>" <?php if (($section == "step3") && ($i == "150")) echo "SELECTED"; elseif ($row_limits['prefsSpecialCharLimit'] == $i) echo "SELECTED"; ?>><?php echo $i; ?></option>
    <?php } ?>
	</select>
	<span id="helpBlock" class="help-block">
		<div class="btn-group" role="group" aria-label="charLimitModal">
			<div class="btn-group" role="group">
				<button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#charLimitModal">
				   Character Limit Info
				</button>
			</div>
		</div>
	</span>
	</div>
</div><!-- ./Form Group -->
<!-- Modal -->
<div class="modal fade" id="charLimitModal" tabindex="-1" role="dialog" aria-labelledby="charLimitModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="charLimitModalLabel">Character Limit Info</h4>
            </div>
            <div class="modal-body">
                <p>Limit of characters allowed for the Required Info section when adding an entry. 50 characters is the maximum recommended when utilizing the &ldquo;Bottle Labels with Required Info&rdquo; report.</p>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->
<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="prefsEntryLimit" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Total Entry Limit (Paid/Unpaid)</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
    	<!-- Input Here -->
        	<input class="form-control" id="prefsEntryLimit" name="prefsEntryLimit" type="text" value="<?php echo $row_limits['prefsEntryLimit']; ?>" placeholder="">
        <span id="helpBlock" class="help-block">Limit of <strong class="text-danger">total</strong> entries you will accept in the competition. Leave blank if no limit.</span>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="prefsEntryLimit" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Total Entry Limit (Paid)</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
    	<!-- Input Here -->
        	<input class="form-control" id="prefsEntryLimitPaid" name="prefsEntryLimitPaid" type="text" value="<?php echo $row_limits['prefsEntryLimitPaid']; ?>" placeholder="">
            <span id="helpBlock" class="help-block">
		<div class="btn-group" role="group" aria-label="charLimitModal">
			<div class="btn-group" role="group">
				<button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#entryLimitPaidModal">
				   Paid Entry Limit Info
				</button>
			</div>
		</div>
        <p>Limit of <strong class="text-danger">paid</strong> entries you will accept in the competition. Leave blank if no limit.</p>
        </span>
    </div>
</div><!-- ./Form Group -->

<!-- Modal -->
<div class="modal fade" id="entryLimitPaidModal" tabindex="-1" role="dialog" aria-labelledby="entryLimitPaidModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="entryLimitPaidLabel">Paid Entry Limit Info</h4>
            </div>
            <div class="modal-body">
                <p>This option should be used with caution as it depends upon one or more factors for successful implementation:</p>
                <ol>
                	<li>Whether or not the competition is accepting payments via PayPal.
                    	<ol type="a">
                        	<li>Automatic &ldquo;mark as paid&rdquo; functionality is <em>entirely</em> dependent upon the user to click the &ldquo;return to...&rdquo; link on PayPal&rsquo;s payment confirmation screen.</li>
                        	<li>As payments come in via PayPal, an Admin of the site should have access to the email address associated with the PayPal account to monitor and confirm payments.</li>
                        </ol>
                    <li>Whether or not the competition organization facilitates multiple pickups from drop-off sites <em>before</em> the drop-off deadline date (so that Admins can mark entries as paid before sorting day).</li>
                    <li>Whether or not the competition is employing multiple sorting dates to check-in entries and mark them as paid.</li>
                </ol>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->

<div class="form-group"><!-- Form Group NOT REQUIRED Select -->
	<label for="prefsUserEntryLimit" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Entry Limit per Participant</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
	<!-- Input Here -->
	<select class="selectpicker" name="prefsUserEntryLimit" id="prefsUserEntryLimit" data-size="10">
		<option value="" rel="none" <?php ($row_limits['prefsUserEntryLimit'] == ""); echo "SELECTED"; ?>></option>
		<?php for ($i=1; $i <= 25; $i++) { ?>
    	<option value="<?php echo $i; ?>" <?php if ($row_limits['prefsUserEntryLimit'] == $i) echo "SELECTED"; ?>><?php echo $i; ?></option>
		<?php } ?>
	</select>
	<span id="helpBlock" class="help-block">Limit of entries that each participant can enter. Leave blank if no limit.</span>
	</div>
</div><!-- ./Form Group -->
<?php if ($go == "preferences") { ?>
<div class="form-group"><!-- Form Group NOT REQUIRED Select -->
	<label for="prefsUserSubCatLimit" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Entry Limit per Sub-Style</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
	<!-- Input Here -->
	<select class="selectpicker" name="prefsUserSubCatLimit" id="prefsUserSubCatLimit" data-size="10">
		<option value="" <?php ($row_limits['prefsUserSubCatLimit'] == ""); echo "SELECTED"; ?>></option>
		<?php for ($i=1; $i <= 25; $i++) { ?>
    	<option value="<?php echo $i; ?>" <?php if ($row_limits['prefsUserSubCatLimit'] == $i) echo "SELECTED"; ?>><?php echo $i; ?></option>
		<?php } ?>
	</select>
	<span id="helpBlock" class="help-block">Limit of entries that each participant can enter into a single sub-style. Leave blank if no limit.</span>
	</div>
</div><!-- ./Form Group -->
<!-- Insert Collapsable -->
<div id="subStyleExeptions">
	<div class="form-group"><!-- Form Group NOT REQUIRED Select -->
		<label for="prefsUSCLExLimit" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Entry Limit For <em>Excepted</em> Sub-Styles</label>
		<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<!-- Input Here -->
		<select class="selectpicker" name="prefsUSCLExLimit" id="prefsUSCLExLimit" data-size="10" data-width="auto">
			<option value="" rel="none" <?php ($row_limits['prefsUSCLExLimit'] == ""); echo "SELECTED"; ?>></option>
			<?php for ($i=1; $i <= 100; $i++) { ?>
			<option value="<?php echo $i; ?>" <?php if ($row_limits['prefsUSCLExLimit'] == $i) echo "SELECTED"; ?>><?php echo $i; ?></option>
			<?php } ?>
		</select>
		<span id="helpBlock" class="help-block">
			<div class="btn-group" role="group" aria-label="exceptdSubstylesModal">
			<div class="btn-group" role="group">
				<button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#exceptdSubstylesModal">
				   Entry Limit For Excepted Sub-Styles Info
				</button>
			</div>
			</div>
		</span>
		</div>
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group Checkbox Stacked -->
		<label for="prefsUSCLEx" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Exceptions to Entry Limit per Sub-Style</label>
		<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
			<div class="input-group">
				<!-- Input Here -->
				<?php echo $prefsUSCLEx; ?>
			</div>
		</div>
	</div><!-- ./Form Group -->
</div><!-- ./subStyleExeptions -->
<!-- Modal -->
<div class="modal fade" id="exceptdSubstylesModal" tabindex="-1" role="dialog" aria-labelledby="exceptdSubstylesModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exceptdSubstylesModalLabel">Entry Limit For Excepted Sub-Styles Info</h4>
            </div>
            <div class="modal-body">
                <p>Limit of entries that each participant can enter into one of the sub-styles that have been checked. Leave blank if no limit <strong>for the sub-styles that have been checked</strong>.</p>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->
<?php } else { ?>
<input type="hidden" name="prefsUserSubCatLimit" value="">
<input type="hidden" name="prefsUSCLExLimit" value="">
<?php } ?>
<h3>Performance and Data Clean-Up</h3>
<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="prefsRecordPaging" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Records Displayed</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
    	<!-- Input Here -->
        	<input class="form-control" id="prefsRecordPaging" name="prefsRecordPaging" type="text" value="<?php if ($section == "step3") echo "150"; else echo $_SESSION['prefsRecordPaging']; ?>" placeholder="12">
        <span id="helpBlock" class="help-block">The number of records displayed per page when viewing lists.</span>
    </div>
</div><!-- ./Form Group -->
<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsAutoPurge" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Automatically Purge Unconfirmed Entries and Perform Data Clean Up</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsAutoPurge" value="1" id="prefsAutoPurge_0"  <?php if ($_SESSION['prefsAutoPurge'] == "1") echo "CHECKED";  ?> /> Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsAutoPurge" value="0" id="prefsAutoPurge_1" <?php if ($_SESSION['prefsAutoPurge'] == "0") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?>/> Disable
            </label>
        </div>
        <span id="helpBlock" class="help-block">
			<div class="btn-group" role="group" aria-label="purgeModal">
			<div class="btn-group" role="group">
				<button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#purgeModal">
				   Automatically Purge Info
				</button>
			</div>
			</div>
		</span>
    </div>
</div><!-- ./Form Group -->
<!-- Modal -->
<div class="modal fade" id="purgeModal" tabindex="-1" role="dialog" aria-labelledby="purgeModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="purgeModalLabel">Automatically Purge Info</h4>
            </div>
            <div class="modal-body">
                <p>Automatically purge any entries flagged as unconfirmed or that require special ingredients but do not 24 hours after entry as well as any data clean-up functions. If disabled, Admins will have the option to manually purge the entries.</p>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->
<h3>Localization</h3>
<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsDateFormat" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Date Format</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsDateFormat" value="1" id="prefsDateFormat_0"  <?php if ($_SESSION['prefsDateFormat'] == "1") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?> /> MM/DD/YYYY
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsDateFormat" value="2" id="prefsDateFormat_1" <?php if ($_SESSION['prefsDateFormat'] == "2") echo "CHECKED"; ?> /> DD/MM/YYYY
            </label>
			<label class="radio-inline">
                <input type="radio" name="prefsDateFormat" value="3" id="prefsDateFormat_2" <?php if ($_SESSION['prefsDateFormat'] == "3") echo "CHECKED"; ?> /> YYYY/MM/DD
            </label>
        </div>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsTimeFormat" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Time Format</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsTimeFormat" value="0" id="prefsTimeFormat_0"  <?php if ($_SESSION['prefsTimeFormat'] == "0") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?> /> 12 Hour
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsTimeFormat" value="1" id="prefsTimeFormat_1" <?php if ($_SESSION['prefsTimeFormat'] == "1") echo "CHECKED"; ?> /> 24 Hour
            </label>
        </div>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Select -->
	<label for="prefsTimeZone" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Time Zone</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<!-- Input Here -->
		<select class="selectpicker" name="prefsTimeZone" id="prefsTimeZone" data-live-search="true" data-size="10" data-width="auto">
			<option value="-12.000" <?php if ($_SESSION['prefsTimeZone'] == "-12.000") echo "SELECTED"; ?>>(GMT -12:00) International Date Line West, Eniwetok, Kwajalein</option>
			<option value="-11.000" <?php if ($_SESSION['prefsTimeZone'] == "-11.000") echo "SELECTED"; ?>>(GMT -11:00) Midway Island, Samoa</option>
			<option value="-10.000" <?php if ($_SESSION['prefsTimeZone'] == "-10.000") echo "SELECTED"; ?>>(GMT -10:00) Hawaii</option>
			<option value="-9.000" <?php if ($_SESSION['prefsTimeZone'] == "-9.000") echo "SELECTED"; ?>>(GMT -9:00) Alaska</option>
			<option value="-8.000" <?php if ($_SESSION['prefsTimeZone'] == "-8.000") echo "SELECTED"; ?>>(GMT -8:00) Pacific Time (US &amp; Canada), Tiajuana</option>
			<option value="-7.000" <?php if ($_SESSION['prefsTimeZone'] == "-7.000") echo "SELECTED"; ?>>(GMT -7:00) Mountain Time (US &amp; Canada)</option>
			<option value="-7.001" <?php if ($_SESSION['prefsTimeZone'] == "-7.001") echo "SELECTED"; ?>>(GMT -7:00) Arizona (No Daylight Savings)</option>
			<option value="-6.000" <?php if ($_SESSION['prefsTimeZone'] == "-6.000") echo "SELECTED"; ?>>(GMT -6:00) Central Time (US &amp; Canada), Central America, Mexico City</option>
			<option value="-6.001" <?php if ($_SESSION['prefsTimeZone'] == "-6.001") echo "SELECTED"; ?>>(GMT -6:00) Sonora, Mexico (No Daylight Savings)</option>
			<option value="-6.002" <?php if ($_SESSION['prefsTimeZone'] == "-6.002") echo "SELECTED"; ?>>(GMT -6:00) Canada Central Time (No Daylight Savings)</option>
			<option value="-5.000" <?php if ($_SESSION['prefsTimeZone'] == "-5.000") echo "SELECTED"; ?>>(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
			<option value="-4.000" <?php if ($_SESSION['prefsTimeZone'] == "-4.000") echo "SELECTED"; ?>>(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
			<option value="-4.001" <?php if ($_SESSION['prefsTimeZone'] == "-4.001") echo "SELECTED"; ?>>(GMT -4:00) Paraguay</option>
			<option value="-3.500" <?php if ($_SESSION['prefsTimeZone'] == "-3.500") echo "SELECTED"; ?>>(GMT -3:30) Newfoundland</option>
			<option value="-3.000" <?php if ($_SESSION['prefsTimeZone'] == "-3.000") echo "SELECTED"; ?>>(GMT -3:00) Buenos Aires, Georgetown, Greenland</option>
			<option value="-3.001" <?php if ($_SESSION['prefsTimeZone'] == "-3.001") echo "SELECTED"; ?>>(GMT -3:00) Brazil (Brasilia)</option>
			<option value="-2.000" <?php if ($_SESSION['prefsTimeZone'] == "-2.000") echo "SELECTED"; ?>>(GMT -2:00) Mid-Atlantic</option>
			<option value="-1.000" <?php if ($_SESSION['prefsTimeZone'] == "-1.000") echo "SELECTED"; ?>>(GMT -1:00 hour) Azores, Cape Verde Islands</option>
			<option value="0.000" <?php if ($_SESSION['prefsTimeZone'] == "0.000") echo "SELECTED"; ?>>(GMT) Western Europe Time, London, Lisbon, Casablanca, Monrovia</option>
			<option value="1.000" <?php if ($_SESSION['prefsTimeZone'] == "1.000") echo "SELECTED"; ?>>(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris</option>
			<option value="2.000" <?php if ($_SESSION['prefsTimeZone'] == "2.000") echo "SELECTED"; ?>>(GMT +2:00) Kaliningrad, South Africa</option>
			<option value="3.000" <?php if ($_SESSION['prefsTimeZone'] == "3.000") echo "SELECTED"; ?>>(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg, Nairobi</option>
			<option value="3.500" <?php if ($_SESSION['prefsTimeZone'] == "3.500") echo "SELECTED"; ?>>(GMT +3:30) Tehran</option>
			<option value="4.000" <?php if ($_SESSION['prefsTimeZone'] == "4.000") echo "SELECTED"; ?>>(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
			<option value="4.500" <?php if ($_SESSION['prefsTimeZone'] == "4.500") echo "SELECTED"; ?>>(GMT +4:30) Kabul</option>
			<option value="5.000" <?php if ($_SESSION['prefsTimeZone'] == "5.000") echo "SELECTED"; ?>>(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
			<option value="5.000" <?php if ($_SESSION['prefsTimeZone'] == "5.500") echo "SELECTED"; ?>>(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
			<option value="5.750" <?php if ($_SESSION['prefsTimeZone'] == "5.750") echo "SELECTED"; ?>>(GMT +5:45) Kathmandu</option>
			<option value="6.000" <?php if ($_SESSION['prefsTimeZone'] == "6.000") echo "SELECTED"; ?>>(GMT +6:00) Almaty, Dhaka, Colombo, Krasnoyarsk</option>
			<option value="7.000" <?php if ($_SESSION['prefsTimeZone'] == "7.000") echo "SELECTED"; ?>>(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
			<option value="8.000" <?php if ($_SESSION['prefsTimeZone'] == "8.000") echo "SELECTED"; ?>>(GMT +8:00) Beijing, Singapore, Hong Kong</option>
			<option value="8.001" <?php if ($_SESSION['prefsTimeZone'] == "8.001") echo "SELECTED"; ?>>(GMT +8:00) Queensland, Perth, the Northern Territory, Western Australia</option>
			<option value="9.000" <?php if ($_SESSION['prefsTimeZone'] == "9.000") echo "SELECTED"; ?>>(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
			<option value="9.500" <?php if ($_SESSION['prefsTimeZone'] == "9.500") echo "SELECTED"; ?>>(GMT +9:30) Adelaide, Darwin</option>
			<option value="10.000" <?php if ($_SESSION['prefsTimeZone'] == "10.000") echo "SELECTED"; ?>>(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
			<option value="10.001" <?php if ($_SESSION['prefsTimeZone'] == "10.001") echo "SELECTED"; ?>>(GMT +10:00) Brisbane</option>
			<option value="11.000" <?php if ($_SESSION['prefsTimeZone'] == "11.000") echo "SELECTED"; ?>>(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
			<option value="12.000" <?php if ($_SESSION['prefsTimeZone'] == "12.000") echo "SELECTED"; ?>>(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
		</select>
	</div>
</div><!-- ./Form Group -->
<h3>Measurements</h3>

<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsTemp" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Temperature</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsTemp" value="Fahrenheit" id="prefsTemp_0"  <?php if ($_SESSION['prefsTemp'] == "Fahrenheit") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?> /> Fahrenheit
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsTemp" value="Celsius" id="prefsTemp_1" <?php if ($_SESSION['prefsTemp'] == "Celsius") echo "CHECKED"; ?> /> Celsius
            </label>
        </div>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsWeight1" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Weight (Small)</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsWeight1" value="ounces" id="prefsWeight1_0"  <?php if ($_SESSION['prefsWeight1'] == "ounces") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?> /> Ounces (oz)
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsWeight1" value="grams" id="prefsWeight1_1" <?php if ($_SESSION['prefsWeight1'] == "grams") echo "CHECKED"; ?> /> Grams (gr)
            </label>
        </div>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsWeight2" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Weight (Large)</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsWeight2" value="pounds" id="prefsWeight2_0"  <?php if ($_SESSION['prefsWeight2'] == "pounds") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?> /> Pounds (lb)
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsWeight2" value="kilograms" id="prefsWeight2_1" <?php if ($_SESSION['prefsWeight2'] == "kilograms") echo "CHECKED";  ?> /> Kilograms (kg)
            </label>
        </div>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsLiquid1" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Liquid (Small)</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsLiquid1" value="ounces" id="prefsLiquid1_0"  <?php if ($_SESSION['prefsLiquid1'] == "ounces") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?> /> Ounces (oz)
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsLiquid1" value="millilitres" id="prefsLiquid1_1" <?php if ($_SESSION['prefsLiquid1'] == "millilitres") echo "CHECKED";  ?> />
                Milliliters (ml)
            </label>
        </div>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsLiquid2" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Liquid (Large)</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsLiquid2" value="gallons" id="prefsLiquid2_0"  <?php if ($_SESSION['prefsLiquid2'] == "gallons") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?> /> Gallons (gal)
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsLiquid2" value="litres" id="prefsLiquid2_1" <?php if ($_SESSION['prefsLiquid2'] == "litres") echo "CHECKED"; ?> />
                Liters (lt)
            </label>
        </div>
    </div>
</div><!-- ./Form Group -->

<h3>Currency and Payment</h3>
<div class="form-group"><!-- Form Group NOT REQUIRED Select -->
	<label for="prefsCurrency" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Currency</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<!-- Input Here -->
		<select class="selectpicker" name="prefsCurrency" id="prefsCurrency" data-live-search="true" data-size="10" data-width="auto">
			<?php
				$currency = currency_info($_SESSION['prefsCurrency'],2);
				$currency_dropdown = "";

				foreach($currency as $curr) {
					$curr = explode("^",$curr);
					$currency_dropdown .= '<option value="'.$curr[0].'"';
					if ($_SESSION['prefsCurrency'] == $curr[0]) $currency_dropdown .= ' SELECTED';
					$currency_dropdown .= '>';
					$currency_dropdown .= $curr[1]."</option>";
				}

				echo $currency_dropdown;
			?>
		</select>
		<span id="helpBlock" class="help-block">
			<div class="btn-group" role="group" aria-label="currencyModal">
			<div class="btn-group" role="group">
				<button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#currencyModal">
				   Currency Info
				</button>
			</div>
			</div>

		</span>
	</div>
</div><!-- ./Form Group -->
<!-- Modal -->
<div class="modal fade" id="currencyModal" tabindex="-1" role="dialog" aria-labelledby="currencyModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="currencyModalLabel">Currency Info</h4>
            </div>
            <div class="modal-body">
                <p>The currencies available in the list <em>above the dashed line</em> are those that are currently accepted by PayPal.</p>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->
<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsPayToPrint" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Pay to Print Paperwork</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsPayToPrint" value="Y" id="prefsPayToPrint_0"  <?php if ($_SESSION['prefsPayToPrint'] == "Y") echo "CHECKED"; ?> />Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsPayToPrint" value="N" id="prefsPayToPrint_1" <?php if ($_SESSION['prefsPayToPrint'] == "N") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?>/>
                Disable
            </label>
        </div>
		<span id="helpBlock" class="help-block">
		<div class="btn-group" role="group" aria-label="payPrintModal">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#payPrintModal">
               Pay to Print Paperwork Info
            </button>
        </div>
		</div>
		</span>
    </div>
</div><!-- ./Form Group -->
<!-- Modal -->
<div class="modal fade" id="payPrintModal" tabindex="-1" role="dialog" aria-labelledby="payPrintModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="payPrintModalLabel">Pay to Print Paperwork Info</h4>
            </div>
            <div class="modal-body">
                <p>Indicate if the entry must be marked as paid to be able to print associated paperwork.</p>
                <p>The default of &ldquo;Disable&rdquo; is appropriate for most installations; otherwise issues may arise that the BCOE&amp;M programming cannot control (e.g., if the user doesn't click the &ldquo;return to...&rdquo; link in PayPal).</p>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->

<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsCash" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Cash for Payment</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsCash" value="Y" id="prefsCash_0"  <?php if ($_SESSION['prefsCash'] == "Y") echo "CHECKED"; ?> />Enable
            </label>
          <label class="radio-inline">
                <input type="radio" name="prefsCash" value="N" id="prefsCash_1" <?php if ($_SESSION['prefsCash'] == "N") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?>/>
              Disable
          </label>
        </div>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsCheck" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Checks for Payment</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsCheck" value="Y" id="prefsCheck_0"  <?php if ($_SESSION['prefsCheck'] == "Y") echo "CHECKED"; ?> />Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsCheck" value="N" id="prefsCheck_1" <?php if ($_SESSION['prefsCheck'] == "N") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?>/>
                Disable</label>
        </div>
    </div>
</div><!-- ./Form Group -->


<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="prefsCheckPayee" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Checks Payee</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
    	<!-- Input Here -->
        <input class="form-control" id="prefsCheckPayee" name="prefsCheckPayee" type="text" value="<?php echo $_SESSION['prefsCheckPayee']; ?>" placeholder="">
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsPaypal" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">PayPal for Payment</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsPaypal" value="Y" id="prefsPaypal_0"  <?php if ($_SESSION['prefsPaypal'] == "Y") echo "CHECKED"; ?> />Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsPaypal" value="N" id="prefsPaypal_1" <?php if ($_SESSION['prefsPaypal'] == "N") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?>/>
                Disable</label>
        </div>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="prefsPaypalAccount" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">PayPal Account Email</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
    	<!-- Input Here -->
        <input class="form-control" id="prefsPaypalAccount" name="prefsPaypalAccount" type="text" value="<?php echo $_SESSION['prefsPaypalAccount']; ?>" placeholder="">
		<span id="helpBlock" class="help-block">
		<div class="btn-group" role="group" aria-label="payPalPrintModal">
            <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#payPalPrintModal">
               PayPal Account Email Info
            </button>
		</div>
		</span>
    </div>
</div><!-- ./Form Group -->

<!-- Modal -->
<div class="modal fade" id="payPalPrintModal" tabindex="-1" role="dialog" aria-labelledby="payPalPrintModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="payPalPrintModalLabel">PayPal Account Email Info</h4>
            </div>
            <div class="modal-body">
                <p>Indicate the email address associated with your PayPal account.</p>
                <p>Please note that you need to have a verified bank account with PayPal to accept credit cards for payment. More information is contained in the &quot;Merchant Services&quot; area of your PayPal account.</p>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->

<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsPaypal" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><a href="https://developer.paypal.com/docs/classic/products/instant-payment-notification/" target="_blank">PayPal Instant Payment Notification</a> (IPN)</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsPaypalIPN" value="1" id="prefsPaypalIPN_0"  <?php if ($_SESSION['prefsPaypalIPN'] == 1) echo "CHECKED"; ?> />Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsPaypalIPN" value="0" id="prefsPaypalIPN_1" <?php if ($_SESSION['prefsPaypalIPN'] == 0) echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?>/>
                Disable</label>
        </div>
        <span id="helpBlock" class="help-block">
		<div class="btn-group" role="group" aria-label="paypalIPNModal">
            <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#payPalIPNModal">
               PayPal IPN Info and Setup
            </button>
		</div>
		</span>
    </div>
</div><!-- ./Form Group -->

<!-- Modal -->
<div class="modal fade" id="payPalIPNModal" tabindex="-1" role="dialog" aria-labelledby="payPalIPNModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="payPalIPNModalLabel">PayPal IPN Info and Setup</h4>
            </div>
            <div class="modal-body">
            
            	<p>PayPal&rsquo;s Instant Payment Notification (IPN) service is a way for your BCOE&amp;M installation to update entry status to &quot;paid&quot; <strong>instantly</strong> after a user successfully completes their payment on PayPal.</p>
				<p>No more fielding questions from entrants about whether their entries have been marked as paid, or why their entries haven't been.</p>
				<p>Transaction details will be saved to your BCOE&amp;M database and will be available via your PayPal dashboard as well.</p>
				<p class="text-primary"><strong>First, it is suggested that you have a dedicated PayPal account for your competition.</strong></p>
				<p class="text-danger"><strong>Second, to implement PayPal IPN, your PayPal account must be a <u>business</u> account.</strong></p>
				<p><strong>Third, set up your PayPal account to process Instant Payment Notifications. Complete instructions are <a href="http://brewcompetition.com/paypal-ipn" target="_blank">available here</a>.</strong></p>
            	<p>Your notification URL is: <blockquote><strong><?php echo $base_url; ?>ppv.php</strong></blockquote></p>
				<p>Your Auto Return URL is: <blockquote><strong><?php echo $base_url; ?>index.php?section=pay&amp;msg=10</strong></blockquote></p>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->

<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsTransFee" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Checkout Fees Paid by Entrant</label>
<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsTransFee" value="Y" id="prefsTransFee_0"  <?php if ($_SESSION['prefsTransFee'] == "Y") echo "CHECKED"; ?> />Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsTransFee" value="N" id="prefsTransFee_1" <?php if ($_SESSION['prefsTransFee'] == "N") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?>/>
                Disable</label>
        </div>
		<span id="helpBlock" class="help-block">
		<div class="btn-group" role="group" aria-label="payPalFeeModal">
            <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#payPalFeeModal">
               Entrant Pays Checkout Fees Info
            </button>
		</div>

		</span>
	</div>
</div><!-- ./Form Group -->
<!-- Modal -->
<div class="modal fade" id="payPalFeeModal" tabindex="-1" role="dialog" aria-labelledby="payPalFeeModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="payPalFeeModalLabel">Entrant Pays Checkout Fees Info</h4>
            </div>
            <div class="modal-body">
                <p>Do you want participants paying via PayPal also pay the transaction fees?</p>
                <p>PayPal charges 2.9% + $0.30 USD per transaction. Enabling this indicates that the transaction fees will be added to the entrant's total.</p>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->
<h3>Sponsors</h3>
<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsSponsors" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Sponsor Display</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsSponsors" value="Y" id="prefs0" <?php if (($section != "step3") && ($_SESSION['prefsSponsors'] == "Y")) echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?> /> Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsSponsors" value="N" id="prefs1" <?php if (($section != "step3") && ($_SESSION['prefsSponsors'] == "N")) echo "CHECKED"; ?> /> Disable
            </label>
        </div>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsSponsorLogos" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Sponsor Logo Display</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsSponsorLogos" value="Y" id="prefsSponsorLogos_0"  <?php if (($section != "step3") && ($_SESSION['prefsSponsorLogos'] == "Y")) echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?> /> Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsSponsorLogos" value="N" id="prefsSponsorLogos_1" <?php if (($section != "step3") && ($_SESSION['prefsSponsorLogos'] == "N")) echo "CHECKED"; ?>/> Disable
            </label>
        </div>
    </div>
</div><!-- ./Form Group -->
<div class="bcoem-admin-element hidden-print">
	<div class="form-group">
		<div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-4">
			<input type="submit" name="Submit" id="setWebsitePrefs" class="btn btn-primary" aria-describedby="helpBlock" value="Set Website Preferences" />
		</div>
	</div>
</div>
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin","default",$msg,$id); ?>">
<?php } ?>
</form>