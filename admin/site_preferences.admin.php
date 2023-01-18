<?php
$style_set_dropdown = "";
$all_exceptions = "";
$all_exceptions_js = "";
$all_hide_js = "";
$custom_exceptions_USCLEx = "";
$prefsUSCLEx = "";
$js_edit_show_hide_style_set_div = "";

include (DB.'styles.db.php');

if (($custom_styles_arr) && (!empty($custom_styles_arr))) {
    foreach ($custom_styles_arr as $value) {
        if ((is_array($value)) && ($value) && (!empty($value))) {
            $custom_exceptions_USCLEx .= "<div class=\"checkbox\"><label><input name=\"prefsUSCLEx[]\" type=\"checkbox\" class=\"chkbox\" value=\"".$value['id']."\">";
            $custom_exceptions_USCLEx .= "Custom Style: ";
            $custom_exceptions_USCLEx .= $value['brewStyle']."</label></div>\n";
        }
    }
}

foreach ($style_sets as $style_set) {
    
    // Reset vars
    $style_set_selected = "";
    $all_exceptions_USCLEx = "";
    $hide_other_js = "";
    
    // Build style set drop-down
    if ((isset($_SESSION['prefsStyleSet'])) && ($style_set['style_set_name'] == $_SESSION['prefsStyleSet']))  $style_set_selected = "SELECTED";
    if (($section == "step3") && ($style_set['style_set_name'] == "BJCP2021")) $style_set_selected = "SELECTED";
    $style_set_dropdown .= sprintf("<option value=\"%s\" %s>%s (%s)</option>",$style_set['style_set_name'],$style_set_selected,$style_set['style_set_long_name'],$style_set['style_set_short_name']);

    // Generate exception list for each of the style sets in the 
    // array and show/hide the list as each are selected via jQuery.
    $query_styles_all = sprintf("SELECT id,brewStyleGroup,brewStyleNum,brewStyle,brewStyleVersion,brewStyleOwn FROM %s WHERE brewStyleVersion='%s' AND brewStyleOwn != 'custom'",$prefix."styles",$style_set['style_set_name']);
    if ($style_set['style_set_name'] == "BA") $query_styles_all .= " ORDER BY brewStyleVersion,brewStyleGroup,brewStyle ASC";
    else $query_styles_all .= " ORDER BY brewStyleVersion,brewStyleGroup,brewStyleNum,brewStyle ASC";
    $styles_all = mysqli_query($connection,$query_styles_all) or die (mysqli_error($connection));
    $row_styles_all = mysqli_fetch_assoc($styles_all);

    if ($style_set['style_set_name'] == "BA") $method = 2;
    else $method = 0;

    do {
        
        $all_exceptions_USCLEx .= "<div class=\"checkbox\"><label><input name=\"prefsUSCLEx[]\" type=\"checkbox\" class=\"chkbox\" value=\"".$row_styles_all['id']."\">";
        if ($style_set['style_set_name'] != "BA") $all_exceptions_USCLEx .= style_number_const($row_styles_all['brewStyleGroup'],$row_styles_all['brewStyleNum'],$style_set['style_set_display_separator'],$method);
        if ($style_set['style_set_name'] == "BA") $all_exceptions_USCLEx .= $style_set['style_set_categories'][$row_styles_all['brewStyleGroup']]." - ".$row_styles_all['brewStyle']."</label></div>\n";
        else $all_exceptions_USCLEx .= " ".$row_styles_all['brewStyle']."</label></div>\n";
        
    } while($row_styles_all = mysqli_fetch_assoc($styles_all));

    $all_exceptions .= "<div class=\"form-group\" id=\"".$style_set['id']."-".$style_set['style_set_name']."\">\n";
    $all_exceptions .= "<label for=\"prefsUSCLEx\" class=\"col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label\">Exceptions to Entry Limit per ".$style_set['style_set_name']." Sub-Style</label>\n";
    $all_exceptions .= "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-12\">\n";
    $all_exceptions .= "<div class=\"input-group\">\n";
    $all_exceptions .= $all_exceptions_USCLEx;
    $all_exceptions .= $custom_exceptions_USCLEx;
    $all_exceptions .= "</div>";
    $all_exceptions .= "</div>\n";
    $all_exceptions .= "</div>\n\n";

    // Generate js to hide other style set exception lists if not chosen in the drop-down
    foreach ($style_sets as $hide_style_set) {
        if ($hide_style_set['id'] != $style_set['id']) {
            $hide_other_js .= "\t\t\t$(\"#".$hide_style_set['id']."-".$hide_style_set['style_set_name']."\").hide(\"fast\");\n";
            $hide_other_js .= "\t\t\t$(\"#helpBlock".$hide_style_set['id']."-".$hide_style_set['style_set_name']."\").hide(\"fast\");\n";
        }
    }

    // Generate jQuery for hide/show
    // Unused as of now, but keeping just in case
    // if ((isset($row_limits['prefsStyleSet'])) && ($row_limits['prefsStyleSet'] == $style_set['style_set_name'])) $js_edit_show_hide_style_set_div .= "$(\"#".$style_set['id']."-".$style_set['style_set_name']."\").show(\"fast\");";
    // else $js_edit_show_hide_style_set_div .= "$(\"#".$style_set['id']."-".$style_set['style_set_name']."\").hide(\"fast\");";

    $all_exceptions_js .= "\t\telse if ($(\"#prefsStyleSet\").val() == \"".$style_set['style_set_name']."\") {\n";
    $all_exceptions_js .= "\t\t\t$(\"#subStyleExeptionsEdit\").hide(\"fast\");\n"; // Hide default upon entry
    $all_exceptions_js .= $hide_other_js; // hide all others
    $all_exceptions_js .= "\t\t\t$(\"#".$style_set['id']."-".$style_set['style_set_name']."\").show(\"fast\");\n"; // Show this 
    $all_exceptions_js .= "\t\t\t$(\"#helpBlock".$style_set['id']."-".$style_set['style_set_name']."\").show(\"fast\");\n";
    $all_exceptions_js .= "\t\t\t$(\"input[name='prefsUSCLEx[]']\").prop(\"checked\", false);\n";
    $all_exceptions_js .= "\t\t\t$(\"#prefsHideSpecific\").show(\"fast\");\n";
    $all_exceptions_js .= "\t\t}\n\n";

    // Add to the list of divs to hide upon page load
    $all_hide_js .= "\t$(\"#".$style_set['id']."-".$style_set['style_set_name']."\").hide();\n";
    $all_hide_js .= "\t$(\"#helpBlock".$style_set['id']."-".$style_set['style_set_name']."\").hide();\n";

}

if ($section == "step3") {
    $query_prefs = sprintf("SHOW COLUMNS FROM %s", $prefix."preferences");
    $prefs = mysqli_query($connection,$query_prefs) or die (mysqli_error($connection));
    while($row_prefs_setup = mysqli_fetch_array($prefs)){
        $row_prefs[$row_prefs_setup['Field']] = "";
    }
}

if (($section == "admin") && ($go == "preferences")) {

    $recaptcha_key = "";
    if (isset($row_prefs['prefsGoogleAccount'])) $recaptcha_key = explode("|", $row_prefs['prefsGoogleAccount']);
    if ($_SESSION['prefsStyleSet'] == "BA") include (INCLUDES.'ba_constants.inc.php');

    // Generate the default sub-style exception list (current settings)
    do {

        $checked = "";

        if ($go == "preferences") {
            $a = explode(",", $row_limits['prefsUSCLEx']);
            $b = $row_styles['id'];
            foreach ($a as $value) {
                if ($value == $b) $checked = "CHECKED";
            }
        }

        if ($row_styles['id'] != "") {
            $style_number = style_number_const($row_styles['brewStyleGroup'],$row_styles['brewStyleNum'],$_SESSION['style_set_display_separator'],0);
            $prefsUSCLEx .= "<div class=\"checkbox\"><label><input name=\"prefsUSCLEx[]\" type=\"checkbox\" value=\"".$row_styles['id']."\" ".$checked.">".$style_number." ".$row_styles['brewStyle']."</label></div>\n";
        }

    } while ($row_styles = mysqli_fetch_assoc($styles));

}

if ($section == "admin") { ?>
<p class="lead"><?php echo $_SESSION['contestName'].": Set Website Preferences"; ?></p>
<div class="bcoem-admin-element hidden-print">
    <div class="btn-group" role="group" aria-label="...">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_preferences"><span class="fa fa-cog"></span> Judging/Competition Organization Preferences</a>
    </div><!-- ./button group -->
</div>
<?php } ?>
<script type='text/javascript'>

var entries_present = <?php if (isset($totalRows_log)) echo $totalRows_log; ?>;
var current_style_set = "<?php if (isset($_SESSION['prefsStyleSet'])) echo $_SESSION['prefsStyleSet']; ?>";

$(document).ready(function(){
    
    $("#prefsHideSpecific").show();
    $("#reCAPTCHA-keys").hide();
    $("#helpBlock-payPalIPN1").hide();
    $("#paypal-payment").hide();
    $("#checks-payment").hide();

    <?php 
    echo $all_hide_js; 
    // echo $js_edit_show_hide_style_set_div;
    ?>

    <?php if (($row_prefs['prefsCAPTCHA'] == "1") || ($section == "step3")) { ?>
     $("#reCAPTCHA-keys").show("fast");
    <?php } ?>

    $("input[name$='prefsCAPTCHA']").click(function() {
        if ($(this).val() == "1") {
            $("#reCAPTCHA-keys").show("fast");
        }
        else {
            $("#reCAPTCHA-keys").hide("fast");
        }
    });

    <?php if ($row_prefs['prefsPaypal'] == "Y") { ?>
        $("#paypal-payment").show("fast");
        $("input[name='prefsPaypalAccount']").prop("required", true);
    <?php } ?>

    $("input[name$='prefsPaypal']").click(function() {
        if ($(this).val() == "Y") {
            $("#paypal-payment").show("fast");
            $("input[name='prefsPaypalAccount']").prop("required", true);
        }
        else {
            $("#paypal-payment").hide("fast");
            $("input[name='prefsPaypalAccount']").prop("required", false);
        }
    });

    <?php if ($row_prefs['prefsPaypalIPN'] == "1") { ?>
        $("#helpBlock-payPalIPN1").show("fast");
    <?php } ?>

    $("input[name$='prefsPaypalIPN']").click(function() {
        if ($(this).val() == "1") {
            $("#helpBlock-payPalIPN1").show("fast");
        }
        else {
            $("#helpBlock-payPalIPN1").hide("fast");
        }
    });

    <?php if ($row_prefs['prefsCheck'] == "Y") { ?>
        $("#checks-payment").show("fast");
        $("input[name='prefsCheckPayee']").prop("required", true);
    <?php } ?>

    $("input[name$='prefsCheck']").click(function() {
        if ($(this).val() == "Y") {
            $("#checks-payment").show("fast");
            $("input[name='prefsCheckPayee']").prop("required", true);
        }
        else {
            $("#checks-payment").hide("fast");
            $("input[name='prefsCheckPayee']").prop("required", false);
        }
    });

    $("#prefsStyleSet").change(function() {

        if (entries_present > 0) {
           if ((current_style_set == "BJCP2015") && ($("#prefsStyleSet").val() == "BJCP2021")) $('#style-set-change-bjcp-2021').modal('show');
           else {
                if (current_style_set != $("#prefsStyleSet").val()) $('#style-set-change').modal('show');
           } 
        }

        if ($("#prefsStyleSet").val() == "") {
            $("input[name='prefsUSCLEx[]']").prop("checked", false);
        }

<?php echo $all_exceptions_js; ?>

    }); // end $("#prefsStyleSet").change(function()

    <?php if ($row_prefs['prefsProEdition'] == "1") { ?>
    $("#bestClub").hide("fast");
    <?php } ?>

    $('input[type="radio"]').click(function() {

        if($(this).attr('id') == 'prefsProEdition_0') {
            $("#bestClub").show("fast");
        }

        if($(this).attr('id') == 'prefsProEdition_1') {
            $("#bestClub").hide("fast");
        }

    });

    <?php if ($row_prefs['prefsShowBestBrewer'] == 0) { ?>
        $("input[name='prefsBestBrewerTitle']").prop("required", false);
    <?php } else { ?>
        $("input[name='prefsBestBrewerTitle']").prop("required", true);
    <?php } ?>

    <?php if ($row_prefs['prefsShowBestClub'] == 0) { ?>
        $("input[name='prefsBestClubTitle']").prop("required", false);
    <?php } else { ?>
        $("input[name='prefsBestClubTitle']").prop("required", true);
    <?php } ?>

    $("#prefsShowBestBrewer").change(function() {
        if ($("#prefsShowBestBrewer").val() == "0"){
            $("input[name='prefsBestBrewerTitle']").prop("required", false);
        }
        else {
            $("input[name='prefsBestBrewerTitle']").prop("required", true);
        }
    });

    $("#prefsShowBestClub").change(function() {
        if ($("#prefsShowBestClub").val() == "0"){
            $("input[name='prefsBestClubTitle']").prop("required", false);
        }
        else {
            $("input[name='prefsBestClubTitle']").prop("required", true);
        }
    });

}); // end $(document).ready(function(){
</script>
<?php if ($section != "step3") { ?>
<div class="modal fade" id="style-set-change" tabindex="-1" role="dialog" aria-labelledby="style-set-change-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="style-set-change-label">Caution! Entries Present</h4>
      </div>
      <div class="modal-body">
        <p>There are currently entries logged into the database from participants using <?php echo $_SESSION['style_set_short_name']; ?> styles.</p>
        <p><strong class="text-primary">Changing the style set here may result in incorrect style classifications or "unrecognized style" messages for participant entries, necessitating editing of individual entries to align the entered style with a style defined in the your chosen style set.</strong></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">I Understand</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="style-set-change-bjcp-2021" tabindex="-1" role="dialog" aria-labelledby="style-set-change-bjcp-2021-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="style-set-change-bjcp-2021-label">Caution! Entries Present</h4>
      </div>
      <div class="modal-body">
        <p>There are currently entries logged into the database from participants using BJCP 2015 styles.</p>
        <p>Entries currently in the database will be converted from BJCP 2015 to BJCP 2021.</p>
        <p>Additionally, preferred and non-preferred styles will be updated to BJCP 2021 for all judges. All defined table styles will be updated as well.</p>
        <p><strong class="text-primary">This cannot be undone.</strong></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">I Understand</button>
      </div>
    </div>
  </div>
</div>
<?php } ?>
<form data-toggle="validator" role="form" class="form-horizontal" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php if ($section == "step3") echo "setup"; else echo $section; ?>&amp;action=<?php if ($section == "step3") echo "add"; else echo "edit"; ?>&amp;dbTable=<?php echo $preferences_db_table; ?>&amp;id=1" name="form1">
<input type="hidden" name="prefsRecordLimit" value="9999" />
<h3>General</h3>
<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsProEdition" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Competition Type</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsProEdition" value="0" id="prefsProEdition_0"  <?php if (($section != "step3") && ($row_prefs['prefsProEdition'] == "0")) echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?> /> Amateur
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsProEdition" value="1" id="prefsProEdition_1" <?php if (($section != "step3") && ($row_prefs['prefsProEdition'] == "1")) echo "CHECKED"; ?>/> Professional
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
                <input type="radio" name="prefsDisplayWinners" value="Y" id="prefsDisplayWinners_0"  <?php if (($section != "step3") && ($row_prefs['prefsDisplayWinners'] == "Y")) echo "CHECKED"; ?> /> Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsDisplayWinners" value="N" id="prefsDisplayWinners_1" <?php if (($section != "step3") && ($row_prefs['prefsDisplayWinners'] == "N")) echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?>/> Disable
            </label>
        </div>
        <span id="helpBlock" class="help-block">Indicate if the winners of the competition for each category and Best of Show Style Type will be displayed.</span>
    </div>
</div><!-- ./Form Group -->
<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="prefsWinnerDelay" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Winner Display Date/Time</label>
    <div class="col-lg-6 col-md-4 col-sm-8 col-xs-12">
        <!-- Input Here -->
            <input class="form-control" id="prefsWinnerDelay" name="prefsWinnerDelay" type="text" value="<?php if ($section == "step3") { $date = new DateTime(); $date->modify('+2 months'); echo $date->format('Y-m-d H'); } elseif (!empty($row_prefs['prefsWinnerDelay'])) echo getTimeZoneDateTime($row_prefs['prefsTimeZone'], $row_prefs['prefsWinnerDelay'], $row_prefs['prefsDateFormat'],  $row_prefs['prefsTimeFormat'], "system", "date-time-system"); ?>" placeholder="<?php if (strpos($section, "step") === FALSE) echo $current_date." ".$current_time; ?>" required>
        <span id="helpBlock" class="help-block">Date and time when the system will display winners if Winner Display is enabled.</span>
        <div class="help-block with-errors"></div>
    </div>
</div><!-- ./Form Group -->
<div class="form-group"><!-- Form Group Radio STACKED -->
    <label for="prefsWinnerMethod" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Winner Place Distribution Method</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <?php foreach ($results_method as $key => $value) { ?>
            <label class="radio-inline">
                <input type="radio" name="prefsWinnerMethod" value="<?php echo $key; ?>" id="prefsWinnerMethod_<?php echo $key; ?>" <?php if (($section == "step3") && ($key == "0")) echo "CHECKED"; elseif ($row_prefs['prefsWinnerMethod'] == $key) echo "CHECKED"; ?>> <?php echo $value; ?>
            </label>
            <?php } ?>
        </div>
        <span id="helpBlock" class="help-block">How the competition will award places for winning entries.</span>
    </div>
</div><!-- ./Form Group -->
<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsEval" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Electronic Scoresheets</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsEval" value="1" id="prefsEval_1"  <?php if ($row_prefs['prefsEval'] == "1") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?> /> Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsEval" value="0" id="prefsEval_0" <?php if ($row_prefs['prefsEval'] == "0") echo "CHECKED"; ?>/> Disable
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
</div><!-- ./Form Group -->
<!-- Modal -->
<div class="modal fade" id="prefsEvalModal" tabindex="-1" role="dialog" aria-labelledby="prefsEvalModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="prefsEvalModalLabel">Contact Form Info</h4>
            </div>
            <div class="modal-body">
                <p>Enable or disable the Electronic Scoresheets function. If enabled, Admins have the option to accept judges' entry evaluations via fully electronic, web-based scoresheets built to emulate BJCP official and quasi-official paper-based forms.</p>
                <p>If enabling Electronic Scoresheets and associated functions, Admins should also make sure to set up their installation to take full advantage of them by following the steps outlined in the <a href="https://brewcompetition.com/setup-electronic-scoresheets" target="_blank">Setup BCOE&amp;M Electronic Scoresheets</a> help article. Admins or competition officials should also direct all judges who will be using Electronic Scoresheets to review the <a href="https://brewcompetition.com/judging-with-electronic-scoresheets" target="_blank">Judging with BCOE&amp;M Electronic Scoresheets</a> primer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->
<div class="form-group"><!-- Form Group Radio STACKED -->
    <label for="prefsDisplaySpecial" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Scoresheet Unique Identifier</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsDisplaySpecial" value="J" id="prefsDisplaySpecial_0" <?php if (($section == "step3") || ($row_prefs['prefsDisplaySpecial'] == "J")) echo "CHECKED"; ?>> 6-Character Judging Number
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsDisplaySpecial" value="E" id="prefsDisplaySpecial_1" <?php if ($row_prefs['prefsDisplaySpecial'] == "E") echo "CHECKED"; ?>> 6-Digit Entry Number
            </label>
        </div>
        <div id="helpBlock" class="help-block">
            <p>How entries are identified to judges when evaluating. If uploading scoresheet PDF files, the PDFs for each entry should be named according to the exact 6-character number for use by the system. <span class="text-primary"><strong>Using the random, system-generated judging numbers ensures unique file names for live and archived entry data.</strong></span></p>
            <div class="btn-group" role="group" aria-label="ScoresheetsModal">
                <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#scoresheetModal">
                   Scoresheet Unique Identifier Info/Examples
                </button>
            </div>
        </div>
    </div>
</div><!-- ./Form Group -->
<!-- Modal -->
<div class="modal fade" id="scoresheetModal" tabindex="-1" role="dialog" aria-labelledby="scoresheetModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="scoresheetModalLabel">Scoresheet Upload File Name Info/Examples</h4>
            </div>
            <div class="modal-body">
                <h4>Judging Numbers</h4>
                <p>If using <strong>judging numbers</strong>, the file names would be:</p>
                    <ul>
                        <li>562113.pdf (system-generated random 6-digit number) - this is the best method to ensure a unique number is used for archived and live data</li>
                        <li>000369.pdf (six-digit judging number corresponding to a scanned barcode label)</li>
                        <li>08-024.pdf, 01a-19.pdf, etc. (customized six-character judging id, input manually)</li>
                    </ul>
                <p class="text-danger"><span class="fa fa-exclamation-circle"></span> <strong>Keep in mind</strong> that any judging number or judging id that you use as a judging number <strong>should be unique for both live and archive entry data</strong>. If unique combinations are not used, archived data will display the currently available file.</p>
                <h4>Entry Numbers</h4>
                <p>If using <strong>entry numbers</strong>, the numbers <strong>must use leading zeroes</strong> to form a six-digit number. For example, if the entry number is 0193, the scoresheet PDF file should be named 000193.pdf.</p>
                <p class="text-danger"><span class="fa fa-exclamation-circle"></span> <strong>Caution:</strong> if using Entry Numbers, the numbers will not be unique if you reset, purge, or archive the current set of entries. When the current set of entries is purged or archived, entry numbers begin again at 0001.</p>
                <p>Therefore, it is advised that you use entry numbers only if you plan on purging and/or overwriting the PDF scoresheet files on the server for each competition iteration.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->
<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsContact" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Contact Form</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsContact" value="Y" id="prefsContact_0"  <?php if ($row_prefs['prefsContact'] == "Y") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?> /> Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsContact" value="N" id="prefsContact_1" <?php if ($row_prefs['prefsContact'] == "N") echo "CHECKED"; ?>/> Disable
            </label>
        </div>
        <span id="helpBlock" class="help-block">
        <div class="btn-group" role="group" aria-label="contactFormModal">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#contactFormModal">
                   Contact Form Info
                </button>
            </div>
            <?php if ($section != "step3") { ?>
            <div class="btn-group" role="group">
                <a href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;&amp;go=default&amp;action=email&amp;filter=test-email&amp;id=<?php echo $_SESSION['brewerID']; ?>" role="button" class="btn btn-xs btn-primary">Send Test Email</a>
            </div>
            <?php } ?>
        </div>
        <?php if (ENABLE_MAILER) {?>
        <p>You have phpMailer enabled. Make sure it has been properly configured in the /site/config.mail.php file and then select the &ldquo;Send Test Email&rdquo; button above to send an email to <?php echo $_SESSION['loginUsername']; ?>. Be sure to check your spam folder.</p>
        <?php } else { ?>
        <?php if ($section != "step3") { ?>
        <p>If you are not sure that your server supports sending email via PHP scripts, select the &ldquo;Send Test Email&rdquo; button above to send an email to <?php echo $_SESSION['loginUsername']; ?>. Be sure to check your spam folder.</p>
        <?php } ?>
        <?php } ?>
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
                <p>Enable or disable your installation's contact form. This may be necessary if your site&rsquo;s server does not support PHP&rsquo;s <a class="hide-loader" href="http://php.net/manual/en/function.mail.php" target="_blank">mail()</a> function. Admins should test the form before disabling as the form is the more secure option. <?php if ($section != "step3") { ?>Admins should use the &ldquo;Send Test Email&rdquo; button to test the function.<?php } ?></p>
                <p>If mail() is not an option, Admins have the option to enable phpMailer to send system-generated emails via SMTP. To enable phpMailer, change the MAILER definition in paths.php to TRUE and customize the variables in the /site/config.mail.php folder to your server environment.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->
<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsEmailCC" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Contact Form CC</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsEmailCC" value="0" id="prefsEmailCC_0" <?php if ((empty($row_prefs['prefsEmailCC'])) || ($row_prefs['prefsEmailCC'] == "0")) echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?>/> Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsEmailCC" value="1" id="prefsEmailCC_1"  <?php if ($row_prefs['prefsEmailCC'] == "1") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?> /> Disable
            </label>
            
        </div>
        <span id="helpBlock" class="help-block">
        <p>Enable or disable automatic carbon copying (CC) of emails sent by the system to the "sender" of the email. Since any email address can be entered in the From field of the Contact form, disabling CC will prevent malicious actors from using the competition contact form to spam emails unrelated to the competition.</p>
        </span>
    </div>
</div><!-- ./Form Group -->
<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="EmailRegConfirm" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Confirmation Emails</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsEmailRegConfirm" value="1" id="prefsEmailRegConfirm_1"  <?php if ($row_prefs['prefsEmailRegConfirm'] == "1") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?> /> Enable
          </label>
            <label class="radio-inline">
                <input type="radio" name="prefsEmailRegConfirm" value="0" id="prefsEmailRegConfirm_0" <?php if ($row_prefs['prefsEmailRegConfirm'] == "0") echo "CHECKED"; ?>/> Disable
            </label>
        </div>
        <span id="helpBlock" class="help-block">
        <div class="btn-group" role="group" aria-label="contactFormModal">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#regEmailFormModalLabel">
                   Confirmation Emails Info
                </button>
            </div>
            <?php if ($section != "step3") { ?>
            <div class="btn-group" role="group">
                <a href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;&amp;go=default&amp;action=email&amp;filter=test-email&amp;id=<?php echo $_SESSION['brewerID']; ?>" role="button" class="btn btn-xs btn-primary">Send Test Email</a>
            </div>
            <?php }?>
        </div>
        <?php if (ENABLE_MAILER) {?>
        <p>You have phpMailer enabled. Make sure it has been properly configured in the /site/config.mail.php file and then select the &ldquo;Send Test Email&rdquo; button above to send an email to <?php echo $_SESSION['loginUsername']; ?>. Be sure to check your spam folder.</p>
        <?php } else { ?>
        <?php if ($section != "step3") { ?>
        <p>If you are not sure that your server supports sending email via PHP scripts, select the &ldquo;Send Test Email&rdquo; button above to send an email to <?php echo $_SESSION['loginUsername']; ?>. Be sure to check your spam folder.</p>
        <?php } ?>
        <?php } ?>
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
                <p>Please note that these system-generated emails may not be possible if your site&rsquo;s server does not support PHP&rsquo;s <a class="hide-loader" href="http://php.net/manual/en/function.mail.php" target="_blank">mail()</a> function. <?php if ($section != "step3") { ?>Admins should use the &ldquo;Send Test Email&rdquo; button to test the function.<?php } ?></p>
                <p>If mail() is not an option, Admins have the option to enable phpMailer to send system-generated emails via SMTP. To enable phpMailer, change the MAILER definition in paths.php to TRUE and customize the variables in the /site/config.mail.php folder to your server environment.</p>
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
        <option value="<?php echo $themes['0']; ?>" <?php if ($row_prefs['prefsTheme'] ==  $themes['0']) echo " SELECTED"; ?> /><?php echo  $themes['1']; ?></option>
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
                <input type="radio" name="prefsSEF" value="Y" id="prefsSEF_0"  <?php if ($row_prefs['prefsSEF'] == "Y") echo "CHECKED"; ?> />Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsSEF" value="N" id="prefsSEF_1" <?php if ($row_prefs['prefsSEF'] == "N") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?>/>Disable
            </label>
        </div>
        <span id="helpBlock" class="help-block">
        <div class="btn-group" role="group" aria-label="SEFModal">
            <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#SEFModal">
               SEF URLs Info
            </button>
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
                <p>If you enable this and receive 404 errors, <?php if ($section == "step3") echo "<strong>after setup has been completed</strong>, "; ?>navigate to the login screen at <a class="hide-loader" href="<?php echo $base_url; ?>index.php?section=login" target="_blank"><?php echo $base_url; ?>index.php?section=login</a> to log back in and &ldquo;turn off&rdquo;  this feature.</p>
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
                <input type="radio" name="prefsUseMods" value="Y" id="prefsUseMods_0"  <?php if ($row_prefs['prefsUseMods'] == "Y") echo "CHECKED"; ?> /> Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsUseMods" value="N" id="prefsUseMods_1" <?php if ($row_prefs['prefsUseMods'] == "N") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?>/> Disable
            </label>
        </div>
        <span id="helpBlock" class="help-block"><strong>FOR ADVANCED USERS.</strong> Utilize the ability to add custom modules that extend BCOE&amp;M's core functionality.</span>
    </div>
</div><!-- ./Form Group -->
<div class="form-group">
    <label for="prefsCAPTCHA" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Enable reCAPTCHA</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <label class="radio-inline">
                <input type="radio" name="prefsCAPTCHA" value="1" id="prefsCAPTCHA_1" <?php if (($section != "step3") && ($row_prefs['prefsCAPTCHA'] == "1")) echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?>/> Yes
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsCAPTCHA" value="0" id="prefsCAPTCHA_0"  <?php if (($section != "step3") && ($row_prefs['prefsCAPTCHA'] == "0")) echo "CHECKED";  ?> /> No
            </label>
        </div>
        <span id="helpBlock" class="help-block">reCAPTCHA is enabled by default. If you are having trouble with it, disable it here. However, <strong class="text-danger">this is a great security risk</strong> - disabling reCAPTCHA may allow bots to register and/or spam contacts via your site.</span>
    </div>
</div>
<div id="reCAPTCHA-keys">
    <div class="form-group">
        <label for="prefsGoogleAccount0" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">reCAPTCHA Site Key</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
            <input class="form-control" id="prefsGoogleAccount0" name="prefsGoogleAccount0" type="text" value="<?php if (isset($recaptcha_key[0])) echo $recaptcha_key[0]; ?>" placeholder="**OPTIONAL** reCAPTCHA Site Key (Public)">
        </div>
    </div>
    <div class="form-group">
        <label for="prefsGoogleAccount1" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">reCAPTCHA Secret Key</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
            <input class="form-control" id="prefsGoogleAccount1" name="prefsGoogleAccount1" type="text" value="<?php if (isset($recaptcha_key[1])) echo $recaptcha_key[1]; ?>" placeholder="**OPTIONAL** reCAPTCHA Secret Key (Private)">
            <span id="helpBlock" class="help-block">
            <div class="help-block"><strong>Specifying reCAPTCHA keys is OPTIONAL.</strong> However, if your installation is experiencing issues processing and/or displaying the reCAPTCHA challenge on the contact and registration pages, you can override the default BCOE&amp;M keys with your own. To get reCAPTCHA API keys, you will need a Google account. You can obtain keys on Google's <a class="hide-loader" href="https://www.google.com/recaptcha/admin" target="_blank">reCAPTCHA dashboard</a>. Follow all instructions there or unexpected functionality will occur.</div>
            </span>
        </div>
    </div>
</div>
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
                <input type="radio" name="prefsDropOff" value="1" id="prefsDropOff_0"  <?php if (($section != "step3") && ($row_prefs['prefsDropOff'] == "1")) echo "CHECKED"; if ($section == "step3") echo "CHECKED";  ?> /> Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsDropOff" value="0" id="prefsDropOff_1" <?php if (($section != "step3") && ($row_prefs['prefsDropOff'] == "0")) echo "CHECKED";?>/> Disable
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
                <input type="radio" name="prefsShipping" value="1" id="prefsShipping_0"  <?php if (($section != "step3") && ($row_prefs['prefsShipping'] == "1")) echo "CHECKED"; if ($section == "step3") echo "CHECKED";  ?> /> Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsShipping" value="0" id="prefsShipping_1" <?php if (($section != "step3") && ($row_prefs['prefsShipping'] == "0")) echo "CHECKED"; ?>/> Disable
            </label>
        </div>
        <span id="helpBlock" class="help-block">Disable if your competition does not have an entry shipping location.</span>
    </div>
</div><!-- ./Form Group -->
<h3>Best Brewer and/or Club</h3>
<!-- BEST BREWER / BEST CLUB --->
<div class="form-group"><!-- Form Group NOT REQUIRED Select -->
    <label for="prefsShowBestBrewer" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Best Brewer Display? Up to which Position?</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
    <!-- Input Here -->
    <select class="selectpicker" name="prefsShowBestBrewer" id="prefsShowBestBrewer" data-size="10" data-width="auto">
        <?php for ($i=-1; $i <= 50; $i++) { ?>
        <option value="<?php echo $i; ?>" <?php if ($row_prefs['prefsShowBestBrewer'] == $i) echo "SELECTED"; elseif (($i == 0) && ($section == "step3")) echo "SELECTED"; ?>><?php if ($i == -1) echo "Display all"; elseif ($i == 0) echo "Do not display"; else echo "Up to ".addOrdinalNumberSuffix($i). " position"; ?></option>
        <?php } ?>
    </select>
    <span id="helpBlock" class="help-block">Indicate whether you want to display the list of best brewers according to the points and tie break rules defined below and, if so, up to which position. They will be showed at the same time indicated above for the Winners Display.</span>
    </div>
</div><!-- ./Form Group -->
<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="prefsBestBrewerTitle" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Best Brewer Title</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
            <input class="form-control" id="prefsBestBrewerTitle" name="prefsBestBrewerTitle" type="text" value="<?php if ($section == "step3") echo ""; else echo $row_prefs['prefsBestBrewerTitle']; ?>" data-error="A Best Brewer title is required." placeholder="">
        <div class="help-block with-errors"></div>
        <div id="helpBlock" class="help-block">Enter the title for the Best Brewer award (e.g., Heavy Medal, Ninkasi Award).</div>
    </div>
</div><!-- ./Form Group -->
<div id="bestClub">
    <div class="form-group"><!-- Form Group NOT REQUIRED Select -->
        <label for="prefsShowBestClub" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Best Club Display? Up to which Position?</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <select class="selectpicker" name="prefsShowBestClub" id="prefsShowBestClub" data-size="10" data-width="auto">
            <?php for ($i=-1; $i <= 50; $i++) { ?>
            <option value="<?php echo $i; ?>" <?php if ($row_prefs['prefsShowBestClub'] == $i) echo "SELECTED"; elseif (($i == 0) && ($section == "step3")) echo "SELECTED"; ?>><?php if ($i == -1) echo "Display all"; elseif ($i == 0) echo "Do not display"; else echo "Up to ".addOrdinalNumberSuffix($i). " position"; ?></option>
            <?php } ?>
        </select>
        <span id="helpBlock" class="help-block">Indicate whether you want to display the list of best clubs according to the points and tie break rules defined below and, if so, up to which position. They will be showed at the same time indicated above for the Winners Display. Applies ONLY to the amateur edition.</span>
        </div>
    </div><!-- ./Form Group -->
    <div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
        <label for="prefsBestClubTitle" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Best Club Title</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
            <!-- Input Here -->
                <input class="form-control" id="prefsBestClubTitle" name="prefsBestClubTitle" type="text" value="<?php if ($section == "step3") echo ""; else echo $row_prefs['prefsBestClubTitle']; ?>" data-error="A Best Club title is required." placeholder="">
            <div class="help-block with-errors"></div>
            <div id="helpBlock" class="help-block">Enter the title for the Best Club award.</div>

        </div>
    </div><!-- ./Form Group -->
</div>
<div class="form-group"><!-- Form Group Radio  -->
    <label for="prefsBestUseBOS" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Include BOS in Calculations?</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsBestUseBOS" value="1" id="prefsBestUseBOS_1" <?php if ($row_prefs['prefsBestUseBOS'] == 1) echo "CHECKED"; ?>> Yes
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsBestUseBOS" value="0" id="prefsBestUseBOS_0" <?php if (($section == "step3") || ($row_prefs['prefsBestUseBOS'] == 0)) echo "CHECKED"; ?>> No
            </label>
        </div>
        <span id="helpBlock" class="help-block">Indicate whether you wish to include any Best of Show (BOS) places in Best Brewer and Best Club calculations.</span>
    </div>
</div><!-- ./Form Group -->
<div class="form-group"><!-- Form Group NOT REQUIRED Select -->
    <label for="prefsFirstPlacePts" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Points for First Place</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
    <!-- Input Here -->
    <select class="selectpicker" name="prefsFirstPlacePts" id="prefsFirstPlacePts" data-size="10" data-width="auto">
        <?php for ($i=0; $i <= 25; $i++) { ?>
        <option value="<?php echo $i; ?>" <?php if ($row_prefs['prefsFirstPlacePts'] == $i) echo "SELECTED"; elseif (($i == 0) && ($section == "step3")) echo "SELECTED"; ?>><?php echo $i; ?></option>
        <?php } ?>
    </select>
    <span id="helpBlock" class="help-block">Enter the number of points awarded for each first place that an entrant receives.</span>
    </div>
</div><!-- ./Form Group -->
<div class="form-group"><!-- Form Group NOT REQUIRED Select -->
    <label for="prefsSecondPlacePts" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Points for Second Place</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
    <!-- Input Here -->
    <select class="selectpicker" name="prefsSecondPlacePts" id="prefsSecondPlacePts" data-size="10" data-width="auto">
        <?php for ($i=0; $i <= 25; $i++) { ?>
        <option value="<?php echo $i; ?>" <?php if ($row_prefs['prefsSecondPlacePts'] == $i) echo "SELECTED"; elseif (($i == 0) && ($section == "step3")) echo "SELECTED"; ?>><?php echo $i; ?></option>
        <?php } ?>
    </select>
    <span id="helpBlock" class="help-block">Enter the number of points awarded for each second place that an entrant receives.</span>
    </div>
</div><!-- ./Form Group -->
<div class="form-group"><!-- Form Group NOT REQUIRED Select -->
    <label for="prefsThirdPlacePts" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Points for Third Place</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
    <!-- Input Here -->
    <select class="selectpicker" name="prefsThirdPlacePts" id="prefsThirdPlacePts" data-size="10" data-width="auto">
        <?php for ($i=0; $i <= 25; $i++) { ?>
        <option value="<?php echo $i; ?>" <?php if ($row_prefs['prefsThirdPlacePts'] == $i) echo "SELECTED"; elseif (($i == 0) && ($section == "step3")) echo "SELECTED"; ?>><?php echo $i; ?></option>
        <?php } ?>
    </select>
    <span id="helpBlock" class="help-block">Enter the number of points awarded for each third place that an entrant receives.</span>
    </div>
</div><!-- ./Form Group -->
<div class="form-group"><!-- Form Group NOT REQUIRED Select -->
    <label for="prefsFourthPlacePts" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Points for Fourth Place</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
    <!-- Input Here -->
    <select class="selectpicker" name="prefsFourthPlacePts" id="prefsFourthPlacePts" data-size="10" data-width="auto">
        <?php for ($i=0; $i <= 25; $i++) { ?>
        <option value="<?php echo $i; ?>" <?php if ($row_prefs['prefsFourthPlacePts'] == $i) echo "SELECTED"; elseif (($i == 0) && ($section == "step3")) echo "SELECTED"; ?>><?php echo $i; ?></option>
        <?php } ?>
    </select>
    <span id="helpBlock" class="help-block">Enter the number of points awarded for each fourth place that an entrant receives.</span>
    </div>
</div><!-- ./Form Group -->
<div class="form-group"><!-- Form Group NOT REQUIRED Select -->
    <label for="prefsHMPts" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Points for Honorable Mention</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
    <!-- Input Here -->
    <select class="selectpicker" name="prefsHMPts" id="prefsHMPts" data-size="10" data-width="auto">
        <?php for ($i=0; $i <= 25; $i++) { ?>
        <option value="<?php echo $i; ?>" <?php if ($row_prefs['prefsHMPts'] == $i) echo "SELECTED"; elseif (($i == 0) && ($section == "step3")) echo "SELECTED"; ?>><?php echo $i; ?></option>
        <?php } ?>
    </select>
    <span id="helpBlock" class="help-block">Enter the number of points awarded for each Honorable Mention that an entrant receives.</span>
    </div>
</div><!-- ./Form Group -->
<?php for ($i=1; $i<=6; $i++) { ?>
<div class="form-group"><!-- Form Group NOT REQUIRED Select -->
    <label for="<?php echo 'prefsTieBreakRule'.$i;?>" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Tie Break Rule #<?php echo $i; ?></label>
    <div class="col-lg-6 col-md-5 col-sm-8 col-xs-12">
    <!-- Input Here -->
    <select class="selectpicker" name="<?php echo 'prefsTieBreakRule'.$i;?>" id="<?php echo 'prefsTieBreakRule'.$i;?>" data-width="auto">
        <?php foreach ($tie_break_rules as $rule) {
           //$rule_info = explode("|",$rule);
        ?>
        <option value="<?php echo $rule; ?>" <?php if ($row_prefs['prefsTieBreakRule'.$i] ==  $rule) echo "SELECTED"; elseif (($rule == "") && ($section == "step3")) echo " SELECTED"; ?> /><?php echo tiebreak_rule($rule); ?></option>
        <?php } ?>
    </select>
    </div>
</div><!-- ./Form Group -->
<?php } ?>
<h3>Entries</h3>
<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsStyleSet" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Styleset</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <select class="selectpicker" name="prefsStyleSet" id="prefsStyleSet" data-size="12" data-width="auto">
        <?php echo $style_set_dropdown; ?>
        </select>
        <div id="helpBlock2-BA" class="help-block">Please note that every effort is made to keep the BA style data current; however, the latest <a class="hide-loader" href="https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/" target="_blank">BA style set</a> may <strong>not</strong> be available in this application.</div>
        <div id="helpBlock3-AABC" class="help-block">Please note that every effort is made to keep the AABC style data current; however, the latest <a class="hide-loader" href="http://www.aabc.org.au/" target="_blank">AABC style set</a> may <strong>not</strong> be available for use in this application.</div>
    </div>
</div><!-- ./Form Group -->
<div class="form-group"><!-- Form Group NOT REQUIRED Select -->
    <label for="prefsEntryForm" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Printed Entry Bottle Labels</label>
    <div class="col-lg-6 col-md-3 col-sm-8 col-xs-12">
    <!-- Input Here -->
    <select class="selectpicker" name="prefsEntryForm" id="prefsEntryForm" data-size="12" data-width="auto">
        <optgroup label="Print Multiple Entries at a Time">
            <option value="5" <?php if (($section != "step3") && ($row_prefs['prefsEntryForm'] == "5")) echo " SELECTED"; ?> />Barcode/QR Code</option>
            <option value="6" <?php if (($section != "step3") && ($row_prefs['prefsEntryForm'] == "6")) echo " SELECTED"; ?> />Anonymous with Barcode/QR Code</option>
        </optgroup>
        <optgroup label="Print Single Entries at a Time">
            <option value="1" <?php if (($section != "step3") && ($row_prefs['prefsEntryForm'] == "1")) echo " SELECTED"; ?> />BCOE&amp;M</option>
            <option value="2" <?php if (($section != "step3") && ($row_prefs['prefsEntryForm'] == "2")) echo " SELECTED"; ?> />BCOE&amp;M with Barcode/QR Code</option>
            <option value="0" <?php if (($section != "step3") && ($row_prefs['prefsEntryForm'] == "0")) echo " SELECTED"; ?> />BCOE&amp;M Anonymous with Barcode/QR Code</option>
            <option value="E" <?php if (($section != "step3") && ($row_prefs['prefsEntryForm'] == "E")) echo " SELECTED"; ?> />BJCP Official</option>
            <option value="C" <?php if (($section != "step3") && ($row_prefs['prefsEntryForm'] == "C")) echo " SELECTED"; ?> />BJCP Official with Barcode/QR Code</option>
        </optgroup>
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
                <h4 class="modal-title" id="entryFormModalLabel">Printed Entry Bottle Labels</h4>
            </div>
            <div class="modal-body">
                <p>There are two groups of bottle labels available:</p>
                <ul>
                    <li>The first, under the <em>Print Multiple Entries</em> header in the drop-down, will print all of the entries users choose from their My Account Page in a single document (9 bottle labels per page).</li>
                    <li>The second, under the <em>Print Single Entries</em> header in the drop-down, print one entry per document. Users select the printer icon for each entry they wish to print bottle labels and other paperwork (if required).</li>
                </ul>
                <p>The <em>Anonymous with Barcode/QR Code</em> options provide bottle labels with only an entry number, style, barcode label, and QR code. These labels are intended to be taped to bottles by entrants before submittal, thereby saving the labor and waste of removing rubberbanded labels by competition staff when sorting. This approach is similar to the method used in the National Homebrew Competition final round.</p>
                <p>The Barcode options are intended to be used with a USB barcode scanner and the <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=checkin">barcode entry check-in function</a>.</p>
                <p>The QR code options are intended to be used with a mobile device and <a class="hide-loader" href="<?php echo $base_url; ?>qr.php" target="_blank">QR code entry check-in function</a> (requires a QR code reading app).</p>
                <div class="well">
                <p>Both the QR code and barcode options are intended to be used with the Judging Number Barcode Labels and the Judging Number Round Labels <a class="hide-loader" href="http://www.brewcompetition.com/barcode-labels" target="_blank"><strong>available for download at brewcompetition.com</strong></a>. BCOE&amp;M utilizes the&nbsp;<strong><a class="hide-loader" href="http://en.wikipedia.org/wiki/Code_39" target="_blank">Code 39 specification</a></strong> to generate all barcodes. Please make sure your scanner recognizes this type of barcode <em>before</em> implementing in your competition.</p>
                </div>
                <p class="text-primary"><strong>As of version 2.5.0, due to the deprecation of all recipe-related fields for individual entries, options with entry recipe forms have been removed.</strong></p>
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
                <input type="radio" name="prefsHideRecipe" value="Y" id="prefsHideRecipe_0" checked> Yes
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
                <p>As of version 2.5.0, the entry recipe section has been removed from the add/edit entry functions. This preference will be removed altogether in a future release; until then, it will be "Yes."</p>
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
                <input type="radio" name="prefsSpecific" value="1" id="prefsSpecific_0"  <?php if ($row_prefs['prefsSpecific'] == "1") echo "CHECKED"; ?> /> Yes
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsSpecific" value="0" id="prefsSpecific_1" <?php if ($row_prefs['prefsSpecific'] == "0") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?>/> No
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
    <label for="prefsSpecialCharLimit" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Character Limit for Text Entry</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
    <!-- Input Here -->
    <select class="selectpicker" name="prefsSpecialCharLimit" id="prefsSpecialCharLimit" data-size="10">
        <?php for ($i=25; $i <= 255; $i+=5) { ?>
        <option value="<?php echo $i; ?>" <?php if (($section == "step3") && ($i == "150")) echo "SELECTED"; elseif ((isset($row_limits['prefsSpecialCharLimit'])) && ($row_limits['prefsSpecialCharLimit'] == $i)) echo "SELECTED"; ?>><?php echo $i; ?></option>
    <?php } ?>
    </select>
    <span id="helpBlock" class="help-block">
        <p>Indicate the limit of characters users can enter when specifying special ingredients, optional ingredients, and brewer's specifics. A limit of <strong>65 characters or less</strong> is suggested for competitions that attach &ldquo;Bottle Labels with Required Info&rdquo; to entry bottles at sorting.</p>
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
                <p>Limit of characters allowed for the Required Info section when adding an entry.</p>
                <p><strong>65 characters</strong> is the maximum recommended when utilizing the &ldquo;Bottle Labels with Required Info&rdquo; report. This ensures that the required and optional information added by the entrant will fit on a single address-size label.</p>
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
            <input class="form-control" id="prefsEntryLimit" name="prefsEntryLimit" type="text" value="<?php if (isset($row_limits['prefsEntryLimit'])) echo $row_limits['prefsEntryLimit']; ?>" placeholder="">
        <span id="helpBlock" class="help-block">Limit of <strong class="text-danger">total</strong> entries you will accept in the competition. Leave blank if no limit.</span>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="prefsEntryLimit" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Total Entry Limit (Paid)</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
            <input class="form-control" id="prefsEntryLimitPaid" name="prefsEntryLimitPaid" type="text" value="<?php if (isset($row_limits['prefsEntryLimitPaid'])) echo $row_limits['prefsEntryLimitPaid']; ?>" placeholder="">
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
                            <li>Automatic &ldquo;mark as paid&rdquo; functionality is <em>entirely</em> dependent upon the user to select the &ldquo;return to...&rdquo; link on PayPal&rsquo;s payment confirmation screen.</li>
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
        <option value="" rel="none" <?php if (isset(($row_limits['prefsUserEntryLimit'])) && ($row_limits['prefsUserEntryLimit'] == "")); echo "SELECTED"; ?>></option>
        <?php for ($i=1; $i <= 25; $i++) { ?>
        <option value="<?php echo $i; ?>" <?php if (isset(($row_limits['prefsUserEntryLimit'])) && ($row_limits['prefsUserEntryLimit'] == $i)) echo "SELECTED"; ?>><?php echo $i; ?></option>
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
    <div class="form-group" id="subStyleExeptionsEdit">
        <label for="prefsUSCLEx" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Exceptions to Entry Limit per Sub-Style</label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <?php if (strpos($section, "step") === FALSE) { ?>
                <div class="btn-group" role="group">
                    <button class="btn btn-xs btn-default" data-toggle="collapse" href="#sub-style-list" aria-expanded="false" aria-controls="sub-style-list">Expand/Collapse List</button>
                </div>
                <?php } ?>
            <div class="<?php if (strpos($section, "step") === FALSE) echo "collapse"; ?>" id="sub-style-list">
                <div class="input-group">
                    <?php echo $prefsUSCLEx; ?>
                </div>
            </div>
        </div>
    </div>
    <?php echo $all_exceptions; ?>
</div>
<!-- ./Form Group -->
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
            <input class="form-control" id="prefsRecordPaging" name="prefsRecordPaging" type="text" value="<?php if ($section == "step3") echo "150"; else echo $row_prefs['prefsRecordPaging']; ?>" placeholder="12" required>
        <span id="helpBlock" class="help-block">The number of records displayed per page when viewing lists.</span>
        <div class="help-block with-errors"></div>
    </div>
</div><!-- ./Form Group -->
<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsAutoPurge" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Automatically Purge Unconfirmed Entries and Perform Data Clean Up</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsAutoPurge" value="1" id="prefsAutoPurge_0"  <?php if ($row_prefs['prefsAutoPurge'] == "1") echo "CHECKED";  ?> /> Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsAutoPurge" value="0" id="prefsAutoPurge_1" <?php if ($row_prefs['prefsAutoPurge'] == "0") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?>/> Disable
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
<div class="form-group"><!-- Form Group NOT REQUIRED Select -->
    <label for="prefsLanguage" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Language</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <select class="selectpicker" name="prefsLanguage" id="prefsLanguage" data-live-search="false" data-size="10" data-width="auto">
            <?php foreach ($languages as $lang => $lang_name) { ?>
            <option value="<?php echo $lang; ?>" <?php if ($row_prefs['prefsLanguage'] == $lang) echo "SELECTED"; ?>><?php echo $lang_name; ?></option>
            <?php } ?>
        </select>
        <span id="helpBlock" class="help-block">The language to display on all <em>public</em> areas of your installation (e.g., entry information, volunteers, account pages, etc.).</span>
        <div class="help-block with-errors"></div>
    </div>
</div><!-- ./Form Group -->
<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsDateFormat" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Date Format</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsDateFormat" value="1" id="prefsDateFormat_0"  <?php if ($row_prefs['prefsDateFormat'] == "1") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?> /> MM/DD/YYYY
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsDateFormat" value="2" id="prefsDateFormat_1" <?php if ($row_prefs['prefsDateFormat'] == "2") echo "CHECKED"; ?> /> DD/MM/YYYY
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsDateFormat" value="3" id="prefsDateFormat_2" <?php if ($row_prefs['prefsDateFormat'] == "3") echo "CHECKED"; ?> /> YYYY/MM/DD
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
                <input type="radio" name="prefsTimeFormat" value="0" id="prefsTimeFormat_0"  <?php if ($row_prefs['prefsTimeFormat'] == "0") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?> /> 12 Hour
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsTimeFormat" value="1" id="prefsTimeFormat_1" <?php if ($row_prefs['prefsTimeFormat'] == "1") echo "CHECKED"; ?> /> 24 Hour
            </label>
        </div>
    </div>
</div><!-- ./Form Group -->
<div class="form-group"><!-- Form Group NOT REQUIRED Select -->
    <label for="prefsTimeZone" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Time Zone</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <select class="selectpicker" name="prefsTimeZone" id="prefsTimeZone" data-live-search="true" data-size="10" data-width="auto">
            <option value="-12.000" <?php if ($row_prefs['prefsTimeZone'] == "-12.000") echo "SELECTED"; ?>>(GMT -12:00) International Date Line West, Eniwetok, Kwajalein, Baker Island, Howland Island</option>
            <option value="-11.000" <?php if ($row_prefs['prefsTimeZone'] == "-11.000") echo "SELECTED"; ?>>(GMT -11:00) Midway Island, Samoa, Pago Pago</option>
            <option value="-10.000" <?php if ($row_prefs['prefsTimeZone'] == "-10.000") echo "SELECTED"; ?>>(GMT -10:00) Hawaii</option>
            <option value="-9.000" <?php if ($row_prefs['prefsTimeZone'] == "-9.000") echo "SELECTED"; ?>>(GMT -9:00) Alaska</option>
            <option value="-9.500" <?php if ($row_prefs['prefsTimeZone'] == "-9.000") echo "SELECTED"; ?>>(GMT -9:30) Marquesas</option>
            <option value="-8.000" <?php if ($row_prefs['prefsTimeZone'] == "-8.000") echo "SELECTED"; ?>>(GMT -8:00) Pacific Time (US &amp; Canada), Tiajuana</option>
            <option value="-7.000" <?php if ($row_prefs['prefsTimeZone'] == "-7.000") echo "SELECTED"; ?>>(GMT -7:00) Mountain Time (US &amp; Canada)</option>
            <option value="-7.001" <?php if ($row_prefs['prefsTimeZone'] == "-7.001") echo "SELECTED"; ?>>(GMT -7:00) Mountain Time - Arizona (No Daylight Savings)</option>
            <option value="-6.000" <?php if ($row_prefs['prefsTimeZone'] == "-6.000") echo "SELECTED"; ?>>(GMT -6:00) Central Time (US &amp; Canada), Central America</option>
            <option value="-6.001" <?php if ($row_prefs['prefsTimeZone'] == "-6.001") echo "SELECTED"; ?>>(GMT -6:00) Sonora, Mexico (No Daylight Savings)</option>
            <option value="-6.002" <?php if ($row_prefs['prefsTimeZone'] == "-6.002") echo "SELECTED"; ?>>(GMT -6:00) Canada Central Time (No Daylight Savings)</option>
            <option value="-5.000" <?php if ($row_prefs['prefsTimeZone'] == "-5.000") echo "SELECTED"; ?>>(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
            <option value="-4.000" <?php if ($row_prefs['prefsTimeZone'] == "-4.000") echo "SELECTED"; ?>>(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz, Santiago, Thule</option>
            <option value="-4.001" <?php if ($row_prefs['prefsTimeZone'] == "-4.001") echo "SELECTED"; ?>>(GMT -4:00) Paraguay (No Daylight Savings)</option>
            <option value="-3.500" <?php if ($row_prefs['prefsTimeZone'] == "-3.500") echo "SELECTED"; ?>>(GMT -3:30) Newfoundland</option>
            <option value="-3.000" <?php if ($row_prefs['prefsTimeZone'] == "-3.000") echo "SELECTED"; ?>>(GMT -3:00) Buenos Aires, Georgetown, Greenland</option>
            <option value="-3.001" <?php if ($row_prefs['prefsTimeZone'] == "-3.001") echo "SELECTED"; ?>>(GMT -3:00) Brazil (Brasilia - No Daylight Savings)</option>
            <option value="-2.000" <?php if ($row_prefs['prefsTimeZone'] == "-2.000") echo "SELECTED"; ?>>(GMT -2:00) Mid-Atlantic</option>
            <option value="-1.000" <?php if ($row_prefs['prefsTimeZone'] == "-1.000") echo "SELECTED"; ?>>(GMT -1:00 hour) Azores, Cape Verde Islands, Ittoqqortoormiit</option>
            <option value="0.000" <?php if ($row_prefs['prefsTimeZone'] == "0.000") echo "SELECTED"; ?>>(GMT) Western Europe Time, London, Lisbon, Casablanca, Monrovia</option>
            <option value="1.000" <?php if ($row_prefs['prefsTimeZone'] == "1.000") echo "SELECTED"; ?>>(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris, Lagos</option>
            <option value="2.000" <?php if ($row_prefs['prefsTimeZone'] == "2.000") echo "SELECTED"; ?>>(GMT +2:00) Kaliningrad, Johannesburg, Cairo Helsinki</option>
            <option value="3.000" <?php if ($row_prefs['prefsTimeZone'] == "3.000") echo "SELECTED"; ?>>(GMT +3:00) Istanbul, Baghdad, Riyadh, Moscow, St. Petersburg, Nairobi</option>
            <option value="3.500" <?php if ($row_prefs['prefsTimeZone'] == "3.500") echo "SELECTED"; ?>>(GMT +3:30) Tehran</option>
            <option value="4.000" <?php if ($row_prefs['prefsTimeZone'] == "4.000") echo "SELECTED"; ?>>(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
            <option value="4.500" <?php if ($row_prefs['prefsTimeZone'] == "4.500") echo "SELECTED"; ?>>(GMT +4:30) Kabul</option>
            <option value="5.000" <?php if ($row_prefs['prefsTimeZone'] == "5.000") echo "SELECTED"; ?>>(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
            <option value="5.500" <?php if ($row_prefs['prefsTimeZone'] == "5.500") echo "SELECTED"; ?>>(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
            <option value="5.750" <?php if ($row_prefs['prefsTimeZone'] == "5.750") echo "SELECTED"; ?>>(GMT +5:45) Kathmandu</option>
            <option value="6.000" <?php if ($row_prefs['prefsTimeZone'] == "6.000") echo "SELECTED"; ?>>(GMT +6:00) Almaty, Dhaka, Colombo, Krasnoyarsk</option>
            <option value="7.000" <?php if ($row_prefs['prefsTimeZone'] == "7.000") echo "SELECTED"; ?>>(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
            <option value="8.000" <?php if ($row_prefs['prefsTimeZone'] == "8.000") echo "SELECTED"; ?>>(GMT +8:00) Beijing, Singapore, Hong Kong</option>
            <option value="8.001" <?php if ($row_prefs['prefsTimeZone'] == "8.001") echo "SELECTED"; ?>>(GMT +8:00) Perth, Western Australia (No Daylight Savings)</option>
            <option value="9.000" <?php if ($row_prefs['prefsTimeZone'] == "9.000") echo "SELECTED"; ?>>(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
            <option value="9.500" <?php if ($row_prefs['prefsTimeZone'] == "9.500") echo "SELECTED"; ?>>(GMT +9:30) Adelaide, Darwin, the Northern Territory</option>
            <option value="10.000" <?php if ($row_prefs['prefsTimeZone'] == "10.000") echo "SELECTED"; ?>>(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
            <option value="10.001" <?php if ($row_prefs['prefsTimeZone'] == "10.001") echo "SELECTED"; ?>>(GMT +10:00) Brisbane, Queensland (No Daylight Savings)</option>
            <option value="11.000" <?php if ($row_prefs['prefsTimeZone'] == "11.000") echo "SELECTED"; ?>>(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
            <option value="12.000" <?php if ($row_prefs['prefsTimeZone'] == "12.000") echo "SELECTED"; ?>>(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
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
                <input type="radio" name="prefsTemp" value="Fahrenheit" id="prefsTemp_0"  <?php if ($row_prefs['prefsTemp'] == "Fahrenheit") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?> /> Fahrenheit
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsTemp" value="Celsius" id="prefsTemp_1" <?php if ($row_prefs['prefsTemp'] == "Celsius") echo "CHECKED"; ?> /> Celsius
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
                <input type="radio" name="prefsWeight1" value="ounces" id="prefsWeight1_0"  <?php if ($row_prefs['prefsWeight1'] == "ounces") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?> /> Ounces (oz)
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsWeight1" value="grams" id="prefsWeight1_1" <?php if ($row_prefs['prefsWeight1'] == "grams") echo "CHECKED"; ?> /> Grams (gr)
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
                <input type="radio" name="prefsWeight2" value="pounds" id="prefsWeight2_0"  <?php if ($row_prefs['prefsWeight2'] == "pounds") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?> /> Pounds (lb)
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsWeight2" value="kilograms" id="prefsWeight2_1" <?php if ($row_prefs['prefsWeight2'] == "kilograms") echo "CHECKED";  ?> /> Kilograms (kg)
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
                <input type="radio" name="prefsLiquid1" value="ounces" id="prefsLiquid1_0"  <?php if ($row_prefs['prefsLiquid1'] == "ounces") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?> /> Ounces (oz)
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsLiquid1" value="millilitres" id="prefsLiquid1_1" <?php if ($row_prefs['prefsLiquid1'] == "millilitres") echo "CHECKED";  ?> />
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
                <input type="radio" name="prefsLiquid2" value="gallons" id="prefsLiquid2_0"  <?php if ($row_prefs['prefsLiquid2'] == "gallons") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?> /> Gallons (gal)
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsLiquid2" value="litres" id="prefsLiquid2_1" <?php if ($row_prefs['prefsLiquid2'] == "litres") echo "CHECKED"; ?> />
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
                $currency = currency_info($row_prefs['prefsCurrency'],2);
                $currency_dropdown = "";

                foreach($currency as $curr) {
                    $curr = explode("^",$curr);
                    $currency_dropdown .= '<option value="'.$curr[0].'"';
                    if ($row_prefs['prefsCurrency'] == $curr[0]) $currency_dropdown .= ' SELECTED';
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
                <input type="radio" name="prefsPayToPrint" value="Y" id="prefsPayToPrint_0"  <?php if ($row_prefs['prefsPayToPrint'] == "Y") echo "CHECKED"; ?> />Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsPayToPrint" value="N" id="prefsPayToPrint_1" <?php if ($row_prefs['prefsPayToPrint'] == "N") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?>/>
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
                <p>The default of &ldquo;Disable&rdquo; is appropriate for most installations; otherwise issues may arise that the BCOE&amp;M programming cannot control (e.g., if the user doesn't select the &ldquo;return to...&rdquo; link in PayPal).</p>
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
                <input type="radio" name="prefsCash" value="Y" id="prefsCash_0"  <?php if ($row_prefs['prefsCash'] == "Y") echo "CHECKED"; ?> />Enable
            </label>
          <label class="radio-inline">
                <input type="radio" name="prefsCash" value="N" id="prefsCash_1" <?php if ($row_prefs['prefsCash'] == "N") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?>/>
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
                <input type="radio" name="prefsCheck" value="Y" id="prefsCheck_0"  <?php if ($row_prefs['prefsCheck'] == "Y") echo "CHECKED"; ?> />Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsCheck" value="N" id="prefsCheck_1" <?php if ($row_prefs['prefsCheck'] == "N") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?>/>
                Disable</label>
        </div>
    </div>
</div><!-- ./Form Group -->
<div class="form-group" id="checks-payment"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="prefsCheckPayee" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Checks Payee</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <input class="form-control" id="prefsCheckPayee" name="prefsCheckPayee" type="text" value="<?php echo $row_prefs['prefsCheckPayee']; ?>" data-error="A check payee is required." placeholder="">
    <div class="help-block with-errors"></div>
    </div>
</div><!-- ./Form Group -->
<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsPaypal" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">PayPal for Payment</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsPaypal" value="Y" id="prefsPaypal_0"  <?php if ($row_prefs['prefsPaypal'] == "Y") echo "CHECKED"; ?> />Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsPaypal" value="N" id="prefsPaypal_1" <?php if ($row_prefs['prefsPaypal'] == "N") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?>/>
                Disable</label>
        </div>
    </div>
</div><!-- ./Form Group -->
<div id="paypal-payment">
    <div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
        <label for="prefsPaypalAccount" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">PayPal Account Email</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
            <!-- Input Here -->
            <input class="form-control" id="prefsPaypalAccount" name="prefsPaypalAccount" type="text" value="<?php echo $row_prefs['prefsPaypalAccount']; ?>" data-error="An email associated with a PayPal account is required if using PayPal to collect entry payments." placeholder="">
            <div class="help-block with-errors"></div>
            <div class="help-block">
            <div class="btn-group" role="group" aria-label="payPalPrintModal">
                <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#payPalPrintModal">
                   PayPal Account Email Info
                </button>
            </div>
            </div>
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
        <label for="prefsPaypal" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><a class="hide-loader" href="https://developer.paypal.com/api/nvp-soap/ipn/" target="_blank">PayPal Instant Payment Notification</a> (IPN)</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
            <div class="input-group">
                <!-- Input Here -->
                <label class="radio-inline">
                    <input type="radio" name="prefsPaypalIPN" value="1" id="prefsPaypalIPN_0"  <?php if ($row_prefs['prefsPaypalIPN'] == 1) echo "CHECKED"; ?> />Enable
                </label>
                <label class="radio-inline">
                    <input type="radio" name="prefsPaypalIPN" value="0" id="prefsPaypalIPN_1" <?php if ($row_prefs['prefsPaypalIPN'] == 0) echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?>/>
                    Disable</label>
            </div>

            <div id="helpBlock-payPalIPN2" class="help-block">
                <div class="btn-group" role="group" aria-label="paypalIPNModal">
                    <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#payPalIPNModal">
                       PayPal IPN Info and Setup
                    </button>
                </div>
            </div>
            <div id="helpBlock-payPalIPN1" class="help-block">
                <p>Your PayPal IPN Notification URL is: <strong><?php echo $base_url; ?>ppv.php</strong><br>Your PayPal IPN Auto Return URL is: <strong><?php echo $base_url; ?>index.php?section=pay&amp;msg=10</strong></p>
                <p>Be sure to select the <em>PayPal IPN Info and Setup</em> button above for requirements and further info.</p>
            </div>
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
                    <p><strong>Third, set up your PayPal account to process Instant Payment Notifications. Complete instructions are <a class="hide-loader" href="http://brewcompetition.com/paypal-ipn" target="_blank">available here</a>.</strong></p>
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
                    <input type="radio" name="prefsTransFee" value="Y" id="prefsTransFee_0"  <?php if ($row_prefs['prefsTransFee'] == "Y") echo "CHECKED"; ?> />Enable
                </label>
                <label class="radio-inline">
                    <input type="radio" name="prefsTransFee" value="N" id="prefsTransFee_1" <?php if ($row_prefs['prefsTransFee'] == "N") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?>/>
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
                    <p>PayPal charges 3.49% of the total + $0.49 USD per transaction. Enabling this indicates that these transaction fees will be added to the entrant's total.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div><!-- ./modal -->
</div>
<h3>Sponsors</h3>
<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="prefsSponsors" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Sponsor Display</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="prefsSponsors" value="Y" id="prefs0" <?php if (($section != "step3") && ($row_prefs['prefsSponsors'] == "Y")) echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?> /> Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsSponsors" value="N" id="prefs1" <?php if (($section != "step3") && ($row_prefs['prefsSponsors'] == "N")) echo "CHECKED"; ?> /> Disable
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
                <input type="radio" name="prefsSponsorLogos" value="Y" id="prefsSponsorLogos_0"  <?php if (($section != "step3") && ($row_prefs['prefsSponsorLogos'] == "Y")) echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?> /> Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsSponsorLogos" value="N" id="prefsSponsorLogos_1" <?php if (($section != "step3") && ($row_prefs['prefsSponsorLogos'] == "N")) echo "CHECKED"; ?>/> Disable
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