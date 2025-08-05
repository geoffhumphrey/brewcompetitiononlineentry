<?php
// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && (strpos($section, "step") === FALSE) && ($_SESSION['userLevel'] > 0))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

$style_set_dropdown = "";
$style_set_description = "";
$entry_limit_by_style = "";
$current_entry_limits_by_style = "";
$all_exceptions = "";
$all_exceptions_js = "";
$all_hide_js = "";
$custom_exceptions_USCLEx = "";
$prefsUSCLEx = "";
$js_edit_show_hide_style_set_div = "";
$incremental = FALSE;

include (DB.'styles.db.php');

// Applies to both ($section vars - step3 and admin)
if (($action == "default") || ($action == "entries")) {

    /**
     * Three possible states of the prefsStyleLimits column:
     *  1) Empty (disabled)
     *  2) JSON data (by style enabled)
     *  3) Single integer numerical value only (by table enabled)
     */

    $limits_by_style = FALSE;
    $limits_by_table = FALSE;
    $style_limits_json = json_decode($row_prefs['prefsStyleLimits'],true);
    if ((strlen($row_prefs['prefsStyleLimits']) > 1) && (json_last_error() === JSON_ERROR_NONE)) $limits_by_style = TRUE;
    if ((strlen($row_prefs['prefsStyleLimits']) == 1) && (is_numeric($row_prefs['prefsStyleLimits']))) $limits_by_table = TRUE;

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
        $custom_exceptions_USCLEx = "";
        $hide_other_js = "";
        $row_styles_all = "";
        
        // Build style set drop-down
        if ((isset($_SESSION['prefsStyleSet'])) && ($style_set['style_set_name'] == $_SESSION['prefsStyleSet']))  $style_set_selected = "SELECTED";
        if (($section == "step3") && ($style_set['style_set_name'] == "BJCP2025")) $style_set_selected = "SELECTED";
        $style_set_dropdown .= sprintf("<option value=\"%s\" %s>%s (%s)</option>",$style_set['style_set_name'],$style_set_selected,$style_set['style_set_long_name'],$style_set['style_set_short_name']);

        // Generate exception list for each of the style sets in the 
        // array and show/hide the list as each are selected via jQuery.
        $styles_db_table = $prefix."styles";

        if ($style_set['style_set_name'] == "BJCP2025") $query_styles_all = sprintf("SELECT id,brewStyleGroup,brewStyleNum,brewStyle,brewStyleVersion,brewStyleOwn FROM %s WHERE (brewStyleVersion='BJCP2025' AND brewStyleType='2') OR (brewStyleVersion='BJCP2021' AND brewStyleType !='2') AND brewStyleOwn != 'custom'",$styles_db_table,$style_set['style_set_name']);
        elseif ($style_set['style_set_name'] == "AABC2025") $query_styles_all = sprintf("SELECT id,brewStyleGroup,brewStyleNum,brewStyle,brewStyleVersion,brewStyleOwn FROM %s WHERE (brewStyleVersion='AABC2025' AND brewStyleType='2') OR (brewStyleVersion='AABC2022' AND brewStyleType !='2') AND brewStyleOwn != 'custom'",$styles_db_table,$style_set['style_set_name']);
        else $query_styles_all = sprintf("SELECT id,brewStyleGroup,brewStyleNum,brewStyle,brewStyleVersion,brewStyleOwn FROM %s WHERE brewStyleVersion='%s' AND brewStyleOwn != 'custom'",$styles_db_table,$style_set['style_set_name']);
        
        if ($style_set['style_set_name'] == "BA") $query_styles_all .= " ORDER BY brewStyleType,brewStyleGroup,brewStyle ASC";
        elseif (strpos($style_set['style_set_name'],"AABC") !== false) $query_styles_all .= " ORDER BY brewStyleGroup,brewStyleNum,brewStyle ASC";
        else $query_styles_all .= " ORDER BY brewStyleType,brewStyleGroup,brewStyleNum,brewStyle ASC";
        
        $styles_all = mysqli_query($connection,$query_styles_all) or die (mysqli_error($connection));
        $row_styles_all = mysqli_fetch_assoc($styles_all);

        if ($style_set['style_set_name'] == "BA") $method = 2;
        else $method = 0;

        if ($row_styles_all) {

            do {
                
                $all_exceptions_USCLEx .= "<div class=\"checkbox\"><label><input name=\"prefsUSCLEx[]\" type=\"checkbox\" class=\"chkbox\" value=\"".$row_styles_all['id']."\">";
                if ($style_set['style_set_name'] != "BA") $all_exceptions_USCLEx .= style_number_const($row_styles_all['brewStyleGroup'],$row_styles_all['brewStyleNum'],$style_set['style_set_display_separator'],$method);
                if ($style_set['style_set_name'] == "BA") $all_exceptions_USCLEx .= $style_set['style_set_categories'][$row_styles_all['brewStyleGroup']]." - ".$row_styles_all['brewStyle']."</label></div>\n";
                else $all_exceptions_USCLEx .= " ".$row_styles_all['brewStyle']."</label></div>\n";
                
            } while($row_styles_all = mysqli_fetch_assoc($styles_all));
        
        }

        $all_exceptions .= "<div class=\"form-group\" id=\"".$style_set['id']."-".$style_set['style_set_name']."\">\n";
        $all_exceptions .= "<label for=\"prefsUSCLEx\" class=\"col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label\">Exceptions to Entry Limit per ".$style_set['style_set_name']." Sub-Style</label>\n";
        $all_exceptions .= "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-12\">\n";
        $all_exceptions .= "<div class=\"input-group\">\n";
        $all_exceptions .= $all_exceptions_USCLEx;
        $all_exceptions .= $custom_exceptions_USCLEx;
        $all_exceptions .= "</div>";
        $all_exceptions .= "</div>\n";
        $all_exceptions .= "</div>\n\n";

        if ($_SESSION['prefsStyleSet'] == $style_set['style_set_name']) {

            $style_limits = json_decode($row_prefs['prefsStyleLimits'],true);

            $current_entry_limits_by_style .= "<div id=\"styleLimitsEdit\" class=\"form-group\" id=\"".$style_set['id']."-".$style_set['style_set_name']."-entry-limit"."\">\n";
            $current_entry_limits_by_style .= "<label for=\"styleLimitsEdit\" class=\"col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label\">Entry Limits per ".$style_set['style_set_name']." Style</label>\n";
            $current_entry_limits_by_style .= "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-12\">\n";

            foreach ($style_set['style_set_categories'] as $key => $value) {

                $limit_value = "";
                if ((isset($style_limits[$key])) && (!empty($style_limits[$key]))) $limit_value = $style_limits[$key];

                $current_entry_limits_by_style .= "
                    <div class=\"form-group small\">
                        <div for=\"".$style_set['style_set_name']."-".$key."\" class=\"col-sm-3 col-md-2\">".$key." - ".$value."</div>
                        <div class=\"col-sm-9 col-md-5\">
                        <input type=\"number\" min=\"0\" pattern=\" 0+\.[0-9]*[1-9][0-9]*$\" onkeypress=\"return event.charCode >= 48 && event.charCode <= 57\" oninput=\"validity.valid||(value='');\" name=\"styleEntryLimitCurrent-".$style_set['style_set_name']."-".$key."\" class=\"form-control input-sm current-style-limit\" id=\"".$style_set['style_set_name']."-".$key."\" value=\"".$limit_value."\" placeholder=\"\">
                        </div>
                    </div>
                ";

            }

            $current_entry_limits_by_style .= "</div>\n";
            $current_entry_limits_by_style .= "</div>\n\n";

        }

        $entry_limit_by_style .= "<div class=\"form-group\" id=\"".$style_set['id']."-".$style_set['style_set_name']."-entry-limit"."\">\n";
        $entry_limit_by_style .= "<label for=\"styleLimitsEdit\" class=\"col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label\">Entry Limits per ".$style_set['style_set_name']." Style</label>\n";
        $entry_limit_by_style .= "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-12\">\n";

        foreach ($style_set['style_set_categories'] as $key => $value) {

            $entry_limit_by_style .= "
                <div class=\"form-group small\">
                    <div for=\"".$style_set['style_set_name']."-".$key."\" class=\"col-sm-3 col-md-2\">".$key." - ".$value."</div>
                    <div class=\"col-sm-9 col-md-5\">
                    <input type=\"number\" min=\"0\" pattern=\" 0+\.[0-9]*[1-9][0-9]*$\" onkeypress=\"return event.charCode >= 48 && event.charCode <= 57\" oninput=\"validity.valid||(value='');\" name=\"styleEntryLimit-".$style_set['style_set_name']."-".$key."\" class=\"form-control input-sm current-style-limit\" id=\"".$style_set['style_set_name']."-".$key."\" placeholder=\"\">
                    </div>
                </div>
            ";

        }

        $entry_limit_by_style .= "</div>\n";
        $entry_limit_by_style .= "</div>\n\n";

        // Generate js to hide other style set exception lists if not chosen in the drop-down
        foreach ($style_sets as $hide_style_set) {
            if ($hide_style_set['id'] != $style_set['id']) {
                $hide_other_js .= "\t\t\t$(\"#".$hide_style_set['id']."-".$hide_style_set['style_set_name']."\").hide(\"fast\");\n";
                $hide_other_js .= "\t\t\t$(\"#helpBlock".$hide_style_set['id']."-".$hide_style_set['style_set_name']."\").hide(\"fast\");\n";
                $hide_other_js .= "\t\t\t$(\"#".$hide_style_set['id']."-".$hide_style_set['style_set_name']."-entry-limit"."\").hide(\"fast\");\n";
            }
        }

        /**
         * Generate jQuery for hide/show
         * Unused as of now, but keeping just in case
         */
        /*
        if ((isset($row_limits['prefsStyleSet'])) && ($row_limits['prefsStyleSet'] == $style_set['style_set_name'])) $js_edit_show_hide_style_set_div .= "$(\"#".$style_set['id']."-".$style_set['style_set_name']."\").show(\"fast\");";
        else $js_edit_show_hide_style_set_div .= "$(\"#".$style_set['id']."-".$style_set['style_set_name']."\").hide(\"fast\");";
        */

        $all_exceptions_js .= "\t\telse if ($(\"#prefsStyleSet\").val() == \"".$style_set['style_set_name']."\") {\n";
        $all_exceptions_js .= "\t\t\t$(\"#subStyleExeptionsEdit\").hide(\"fast\");\n"; // Hide default upon entry
        $all_exceptions_js .= "\t\t\t$(\"#styleLimitsEdit\").hide(\"fast\");\n"; // Hide default upon entry
        $all_exceptions_js .= $hide_other_js; // hide all others
        $all_exceptions_js .= "\t\t\t$(\"#".$style_set['id']."-".$style_set['style_set_name']."\").show(\"fast\");\n"; // Show this 
        $all_exceptions_js .= "\t\t\t$(\"#".$style_set['id']."-".$style_set['style_set_name']."-entry-limit"."\").show(\"fast\");\n"; // Show this 
        $all_exceptions_js .= "\t\t\t$(\"#helpBlock".$style_set['id']."-".$style_set['style_set_name']."\").show(\"fast\");\n";
        $all_exceptions_js .= "\t\t\t$(\"input[name='prefsUSCLEx[]']\").prop(\"checked\", false);\n";
        $all_exceptions_js .= "\t\t\t$(\"#prefsHideSpecific\").show(\"fast\");\n";
        $all_exceptions_js .= "\t\t}\n\n";

        // Add to the list of divs to hide upon page load
        $all_hide_js .= "\t$(\"#".$style_set['id']."-".$style_set['style_set_name']."\").hide();\n";
        $all_hide_js .= "\t$(\"#helpBlock".$style_set['id']."-".$style_set['style_set_name']."\").hide();\n";
        $all_hide_js .= "\t$(\"#".$style_set['id']."-".$style_set['style_set_name']."-entry-limit"."\").hide();\n";

    }


} // end if (($action == "default") || ($action == "entries"))


if ($section == "step3") {
    $query_prefs = sprintf("SHOW COLUMNS FROM %s", $prefix."preferences");
    $prefs = mysqli_query($connection,$query_prefs) or die (mysqli_error($connection));
    while($row_prefs_setup = mysqli_fetch_array($prefs)){
        $row_prefs[$row_prefs_setup['Field']] = "";
    }
}

if (($section == "admin") && ($go == "preferences")) {

    if ($action == "default") {

        // General: reCAPTCHA
        $recaptcha_key = "";
        if (isset($row_prefs['prefsGoogleAccount'])) $recaptcha_key = explode("|", $row_prefs['prefsGoogleAccount']);
        if ($_SESSION['prefsStyleSet'] == "BA") include (INCLUDES.'ba_constants.inc.php');

    } // end if ($action == "default")

    if ($action == "entries") {

        // Entries: Styles

        $styles_selected = array();
        $styles_selected = json_decode($_SESSION['prefsSelectedStyles'],true);

        if ($row_styles) {

            // Generate the default sub-style exception list (current settings)
            do {

                if (array_key_exists($row_styles['id'], $styles_selected)) {

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

                }

            } while ($row_styles = mysqli_fetch_assoc($styles));

        }

        if (!empty($row_limits['prefsUserEntryLimitDates'])) {
            $incremental = TRUE;
            $incremental_limits = json_decode($row_limits['prefsUserEntryLimitDates'],true);
        }

    } // end if ($action == "entries")

}
?>

<?php if ($section == "admin") { ?>
<p class="lead"><?php echo $_SESSION['contestName'].": Set Preferences"; ?></p>
<div class="bcoem-admin-element hidden-print">
        <a class="btn btn-<?php if ($action == "default") echo "primary disabled"; else echo "primary"; ?>" style="margin: 5px 5px 5px 0" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=preferences"><span class="fa fa-cog"></span> General Preferences</a>
        <a class="btn btn-<?php if ($action == "entries") echo "primary disabled"; else echo "primary"; ?>" style="margin: 5px 5px 5px 0" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=preferences&amp;action=entries"><span class="fa fa-beer"></span> Entry Preferences</a>
        <a class="btn btn-<?php if ($action == "email") echo "primary disabled"; else echo "primary"; ?>" style="margin: 5px 5px 5px 0" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=preferences&amp;action=email"><span class="fa fa-envelope"></span> Email Sending Preferences</a>
        <a class="btn btn-<?php if ($action == "payment") echo "primary disabled"; else echo "primary"; ?>" style="margin: 5px 5px 5px 0" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=preferences&amp;action=payment"><span class="fa fa-money"></span> Currency and Payment Preferences</a>
        <a class="btn btn-<?php if ($action == "best") echo "primary disabled"; else echo "primary"; ?>" style="margin: 5px 5px 5px 0" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=preferences&amp;action=best"><span class="fa fa-trophy"></span> Best Brewer and/or Club Preferences</a>
        <a class="btn btn-primary" style="margin: 5px 5px 5px 0" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_preferences"><span class="fa fa-gavel"></span> Judging/Competition Organization Preferences</a>
</div>
<?php } ?>

<?php 

if ((($section == "admin") && ($go == "preferences") && ($action == "email")) || ($section == "step3")) { 

$email_disabled_all_creds = 0;
$email_previous_no_creds = 0;
if ((!empty($row_prefs['prefsEmailHost'])) && (!empty($row_prefs['prefsEmailFrom'])) && (!empty($row_prefs['prefsEmailUsername'])) && (!empty($row_prefs['prefsEmailPassword'])) && (!empty($row_prefs['prefsEmailPort']))) $email_disabled_all_creds = 1;
if ($row_prefs['prefsEmailSMTP'] == 3) $email_previous_no_creds = 1;
?>
<script type="text/javascript">
var email_sending_enable = "<?php echo $row_prefs['prefsEmailSMTP']; ?>"; 
var email_password_hash = "<?php if (!empty($row_prefs['prefsEmailPassword'])) echo $row_prefs['prefsEmailPassword']; ?>";
var email_disabled_all_creds = "<?php echo $email_disabled_all_creds; ?>";
var email_previous_no_creds = "<?php echo $email_previous_no_creds; ?>";

$(document).ready(function(){

    <?php if ($view == "test-email") { ?>
    $.fancybox.open({
        src  : '<?php echo $base_url; ?>/admin/send_test_email.admin.php',
        type : 'iframe',
        opts : {
            iframe : {
                css: {
                    width : '90%',
                    height: '90%'
                }
            }
        }
    });
    <?php } ?>

    if ((email_password_hash.length === 0) && (email_sending_enable == 0)) $("#change-email-password").show();
    else $("#change-email-password").hide();

    $("#send-test-email-show").hide();
    $("#sending-email-options").hide();
    $('#send-test-email-show-button').hide();
    
    if (email_sending_enable == 1) {
        $("#sending-email-options").show();
        if (email_disabled_all_creds == 1) {
            $('#send-test-email-show-button').show();
        }
        else {
           $('#send-test-email-show').show(); 
        }
        $("#setWebsitePrefs").prop("disabled", false);
    }
    else {
        $("#setWebsitePrefs").prop("disabled", true);
    }
   
    if ((email_previous_no_creds == 1) && (email_sending_enable == 0)) $("#setWebsitePrefs").prop("disabled", false);

    $("input[name='prefsEmailSMTP']").click(function() {
        if ($(this).val() == "1") {
            $("#sending-email-options").show("fast");
            $("#setWebsitePrefs").prop("disabled", false);
            $("input[name='prefsEmailFrom']").prop("required", true);
            $("input[name='prefsEmailHost']").prop("required", true);
            $("input[name='prefsEmailUsername']").prop("required", true);
            $("input[name='prefsEmailPassword']").prop("required", true);
            $("input[name='prefsEmailPort']").prop("required", true);
            if (email_disabled_all_creds == 1) {
                $('#send-test-email-show-button').show("fast");
            }
        }
        else {
            $("#sending-email-options").hide("fast");
            $("#setWebsitePrefs").prop("disabled", false);
            $("input[name='prefsEmailFrom']").prop("required", false);
            $("input[name='prefsEmailHost']").prop("required", false);
            $("input[name='prefsEmailUsername']").prop("required", false);
            $("input[name='prefsEmailEncrypt']").prop("required", false);
            $("input[name='prefsEmailPort']").prop("required", false);
        }
    });

    $("input[name='change-email-password-choice']").click(function() {
        if ($(this).val() == "1") {
            $("#change-email-password").show("fast");
            $("input[name='prefsEmailPassword']").prop("required", true);
            $("input[name='prefsEmailPassword']").val('');
        }
        else {
            $("#change-email-password").hide("fast");
            $("input[name='prefsEmailPassword']").prop("required", false);
            $("input[name='prefsEmailPassword']").val(email_password_hash);
        }
    });

    $('input[type="text"], input[type="number"], input[type="email"]').bind('keyup change',function(){

        $('#send-test-email-show-button').hide("fast");

        // get elements that are empty.
        var empty = $('input[type="text"], input[type="number"], input[type="email"]').map(function(index, el) {
            return !$(el).val().length ? el : null;
        }).get();

        var test_show = $('#send-test-email-show');

        // check if there are any empty elements, if there are none, show numbers, else hide number.
        !empty.length ? test_show.show("fast") : test_show.hide("fast");

    });

});

</script>

<?php } if ((($section == "admin") && ($go == "preferences") && ($action == "default")) || ($section == "step3")) { ?>
<script type="text/javascript">
$(document).ready(function(){

    $("#reCAPTCHA-keys").hide();

    <?php if (($row_prefs['prefsCAPTCHA'] == "1") || ($section == "step3")) { ?>
    $("#reCAPTCHA-keys").show();
    <?php } ?>

    $("input[name$='prefsCAPTCHA']").click(function() {
        if ($(this).val() == "1") {
            $("#reCAPTCHA-keys").show("fast");
        }
        else {
            $("#reCAPTCHA-keys").hide("fast");
        }
    });

    <?php if ($row_prefs['prefsProEdition'] == "1") { ?>
    $("#mhp-display").hide("fast");
    <?php } ?>

    $("input[name$='prefsProEdition']").click(function() {
        if ($(this).val() == "1") {
            $("#mhp-display").hide("fast");
        }
        else {
            $("#mhp-display").show("fast");
        }
    });
    
});
</script>
<?php } if ((($section == "admin") && ($go == "preferences") && ($action == "entries")) || ($section == "step3")) { 
?>
<script type="text/javascript">

var entries_present = "<?php if (isset($totalRows_log)) echo $totalRows_log; ?>";
var current_style_set = "<?php if (isset($_SESSION['prefsStyleSet'])) echo $_SESSION['prefsStyleSet']; ?>";
var limits_by_style = "<?php if ($limits_by_style) echo "1"; ?>";
var limits_by_table = "<?php if ($limits_by_table) echo "1"; ?>";

$(document).ready(function(){

    $("#define-style-entry-limits").hide();
    $("#limit-entries-will-reset").hide();

    if (limits_by_style == 1) {
        $("#define-style-entry-limits").show();
        $("#limit-entries-by-style-help").addClass("alert");
        $("#limit-entries-by-style-help").addClass("alert-success");
    }

    if (limits_by_table == 1) {
        $("#limit-entries-by-table-help").addClass("alert");
        $("#limit-entries-by-table-help").addClass("alert-success");
    }

    $("input[name$='choose-style-entry-limits']").click(function() {

        $("#limit-entries-will-reset").show("fast");
        $("#limit-entries-by-style-help").removeClass("alert");
        $("#limit-entries-by-style-help").removeClass("alert-success");
        $("#limit-entries-by-table-help").removeClass("alert");
        $("#limit-entries-by-table-help").removeClass("alert-success");

        if ($(this).val() == "1") {
            $("#define-style-entry-limits").show("fast");
            $("#limit-entries-by-style-help").addClass("alert");
            $("#limit-entries-by-style-help").addClass("alert-success");
        }

        else if ($(this).val() == "2") {
            $("#define-style-entry-limits").hide("fast");
            $("#limit-entries-by-table-help").addClass("alert");
            $("#limit-entries-by-table-help").addClass("alert-success");
        }

        else {
            $(".current-style-limit").val("");
            $("#define-style-entry-limits").hide("fast");
        }

    });

    $("#prefsHideSpecific").show();
    $("#user-entry-limit-increment-2").hide();
    $("#user-entry-limit-increment-3").hide();
    $("#user-entry-limit-increment-4").hide();
    
    <?php if (isset($incremental_limits[1]['limit-number'])) { ?>
    $("#user-entry-limit-increment-2").show();
    <?php } ?>

    <?php if (isset($incremental_limits[2]['limit-number'])) { ?>
    $("#user-entry-limit-increment-3").show();
    <?php } ?>

    <?php if (isset($incremental_limits[3]['limit-number'])) { ?>
    $("#user-entry-limit-increment-4").show();
    <?php } ?>

    $("#user-entry-limit-number-1").change(function() {
        
        if ($("#user-entry-limit-number-1").val() == ""){
            
            $("#user-entry-limit-increment-2").hide("fast");
            $("#user-entry-limit-increment-3").hide("fast");
            $("#user-entry-limit-increment-4").hide("fast");
            $("input[name='user-entry-limit-number-1']").prop("required", false);
            $("#user-entry-limit-number-2").selectpicker('val', '-1');;
            $("#user-entry-limit-number-3").selectpicker('val', '-1');
            $("#user-entry-limit-number-4").selectpicker('val', '-1');
            $("#user-entry-limit-expire-days-1").selectpicker('val', '-1');
            $("#user-entry-limit-expire-days-2").selectpicker('val', '-1');;
            $("#user-entry-limit-expire-days-3").selectpicker('val', '-1');
            $("#user-entry-limit-expire-days-4").selectpicker('val', '-1');

        }

        else {
            $("input[name='user-entry-limit-number-1']").prop("required", true);
            $("input[name='user-entry-limit-expire-days-1']").prop("required", true);
        }

    }); 

    $("#user-entry-limit-expire-days-1").change(function() {
        
        if ($("#user-entry-limit-expire-days-1").val() == "") {      
            $("#user-entry-limit-increment-2").hide("fast");
            $("#user-entry-limit-increment-3").hide("fast");
            $("#user-entry-limit-increment-4").hide("fast");
            $("input[name='user-entry-limit-number-1']").prop("required", false);
            $("#user-entry-limit-number-2").selectpicker('val', '-1');;
            $("#user-entry-limit-number-3").selectpicker('val', '-1');
            $("#user-entry-limit-number-4").selectpicker('val', '-1');
            $("#user-entry-limit-expire-days-1").selectpicker('val', '-1');
            $("#user-entry-limit-expire-days-2").selectpicker('val', '-1');;
            $("#user-entry-limit-expire-days-3").selectpicker('val', '-1');
            $("#user-entry-limit-expire-days-4").selectpicker('val', '-1');
        }

        else {
            $("input[name='user-entry-limit-number-1']").prop("required", true);
            $("input[name='user-entry-limit-expire-days-1']").prop("required", true);
            $("#user-entry-limit-increment-2").show("fast");  
        }

    });

    $("#user-entry-limit-number-2").change(function() {
        
        if ($("#user-entry-limit-number-2").val() == "") {            
            $("#user-entry-limit-increment-3").hide("fast");
            $("#user-entry-limit-increment-4").hide("fast");
            $("input[name='user-entry-limit-number-2']").prop("required", false);
            $("#user-entry-limit-number-3").selectpicker('val', '-1');
            $("#user-entry-limit-number-4").selectpicker('val', '-1');
            $("#user-entry-limit-expire-days-2").selectpicker('val', '-1');;
            $("#user-entry-limit-expire-days-3").selectpicker('val', '-1');
            $("#user-entry-limit-expire-days-4").selectpicker('val', '-1');
        }

        else {
            $("input[name='user-entry-limit-number-2']").prop("required", true);
            $("input[name='user-entry-limit-expire-days-2']").prop("required", true);
        }

    });

    $("#user-entry-limit-expire-days-2").change(function() {
        
        if ($("#user-entry-limit-expire-days-2").val() == "") {    
            $("#user-entry-limit-increment-3").hide("fast");
            $("#user-entry-limit-increment-4").hide("fast");
            $("input[name='user-entry-limit-number-2']").prop("required", false);
            $("#user-entry-limit-number-3").selectpicker('val', '-1');
            $("#user-entry-limit-number-4").selectpicker('val', '-1');
            $("#user-entry-limit-expire-days-2").selectpicker('val', '-1');;
            $("#user-entry-limit-expire-days-3").selectpicker('val', '-1');
            $("#user-entry-limit-expire-days-4").selectpicker('val', '-1');
        }

        else {
            $("input[name='user-entry-limit-number-2']").prop("required", true);
            $("input[name='user-entry-limit-expire-days-2']").prop("required", true);
            $("#user-entry-limit-increment-3").show("fast");  
        }

    });

    $("#user-entry-limit-number-3").change(function() {
        
        if ($("#user-entry-limit-number-3").val() == "") {
            $("#user-entry-limit-increment-4").hide("fast");  
            $("input[name='user-entry-limit-number-3']").prop("required", false);
            $("#user-entry-limit-number-4").selectpicker('val', '-1');
            $("#user-entry-limit-expire-days-3").selectpicker('val', '-1');
            $("#user-entry-limit-expire-days-4").selectpicker('val', '-1');
        }
        
        else {
            $("input[name='user-entry-limit-number-3']").prop("required", true);
            $("input[name='user-entry-limit-expire-days-3']").prop("required", true);
        }

    });

    $("#user-entry-limit-expire-days-3").change(function() {
        
        if ($("#user-entry-limit-expire-days-3").val() == "") {   
            $("#user-entry-limit-increment-4").hide("fast");  
            $("input[name='user-entry-limit-number-3']").prop("required", false);
            $("#user-entry-limit-number-4").selectpicker('val', '-1');
            $("#user-entry-limit-expire-days-3").selectpicker('val', '-1');
            $("#user-entry-limit-expire-days-4").selectpicker('val', '-1');
        }
        
        else {
            $("input[name='user-entry-limit-number-3']").prop("required", true);
            $("input[name='user-entry-limit-expire-days-3']").prop("required", true);
            $("#user-entry-limit-increment-4").show("fast");  
        }

    });

    $("#user-entry-limit-number-4").change(function() {
        
        if ($("#user-entry-limit-number-4").val() == "") {
            $("input[name='user-entry-limit-number-4']").prop("required", false);
            $("#user-entry-limit-expire-days-4").selectpicker('val', '-1');
        }
        
        else {
            $("input[name='user-entry-limit-number-4']").prop("required", true);
            $("input[name='user-entry-limit-expire-days-4']").prop("required", true);
        }
    });

    $("#user-entry-limit-expire-days-4").change(function() {
        
        if ($("#user-entry-limit-expire-days-4").val() == ""){
            $("input[name='user-entry-limit-number-4']").prop("required", false);
            $("#user-entry-limit-expire-days-4").selectpicker('val', '-1');
        }
        
        else {
            $("input[name='user-entry-limit-number-4']").prop("required", true);
            $("input[name='user-entry-limit-expire-days-4']").prop("required", true);
        }
    });

    <?php echo $all_hide_js; ?>

    $("#prefsStyleSet").change(function() {

        $('.current-style-limit').val("");

        if (entries_present > 0) {
           if ((current_style_set == "BJCP2015") && ($("#prefsStyleSet").val() == "BJCP2021")) $('#style-set-change-bjcp-2021').modal('show');
           else if ((current_style_set == "BJCP2021") && ($("#prefsStyleSet").val() == "BJCP2025")) $('#style-set-change-bjcp-2025').modal('show');
           else {
                if (current_style_set != $("#prefsStyleSet").val()) $('#style-set-change').modal('show');
           } 
        }

        if ($("#prefsStyleSet").val() == "") {
            $("input[name='prefsUSCLEx[]']").prop("checked", false);
        }

    <?php echo $all_exceptions_js; ?>

    }); // end $("#prefsStyleSet").change(function()

});

</script>

<?php } if ((($section == "admin") && ($go == "preferences") && ($action == "payment")) || ($section == "step3")) { ?>

<script type="text/javascript">
    
$(document).ready(function(){

    $("#helpBlock-payPalIPN1").hide();
    $("#paypal-payment").hide();
    $("#checks-payment").hide();

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
    
});

</script>

<?php } if ((($section == "admin") && ($go == "preferences") && ($action == "best")) || ($section == "step3")) { ?>

<script type="text/javascript">
    
$(document).ready(function(){

    <?php if (($section == "step3") || ($row_prefs['prefsShowBestBrewer'] == 0)) { ?>
    $("input[name='prefsBestBrewerTitle']").prop("required", false);
    <?php } else { ?>
    $("input[name='prefsBestBrewerTitle']").prop("required", true);
    <?php } ?>

    <?php if (($section == "step3") || ($row_prefs['prefsShowBestClub'] == 0)) { ?>
    $("input[name='prefsBestClubTitle']").prop("required", false);
    <?php } else { ?>
    $("input[name='prefsBestClubTitle']").prop("required", true);
    <?php } ?>

    <?php if ($row_prefs['prefsProEdition'] == "1") { ?>
    $("#bestClub").hide("fast");
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

    <?php if ($row_prefs['prefsScoringCOA'] == "1") { ?>
     $("#non-COA-scoring").hide();
     $("#bos-in-calcs").hide();
    <?php } ?>

    $("input[name$='prefsScoringCOA']").click(function() {
        if ($(this).val() == "0") {
            $("#non-COA-scoring").show("fast");
            $("#bos-in-calcs").show("fast");
        }
        else {
            $("#non-COA-scoring").hide("fast");
            $("#bos-in-calcs").hide("fast");
        }
    });
    
});

</script>
<?php } ?>

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
        <p>Choosing this option incorporates the 2021 update of beer styles only. Mead and cider remain the same as defined the 2015 update.</p>
        <p>There are currently entries logged into the database from participants using BJCP 2015 styles.</p>
        <p><strong>Beer entries</strong> that are currently in the database will be converted from the 2015 updade to to the 2021 update .</p>
        <p>Additionally, preferred and non-preferred beer styles will be updated to 2021 for all judges. All defined tables incorporating beer styles will be updated as well.</p>
        <p><strong class="text-primary">This cannot be undone.</strong></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">I Understand</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="style-set-change-bjcp-2025" tabindex="-1" role="dialog" aria-labelledby="style-set-change-bjcp-2025-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="style-set-change-bjcp-2025-label">Caution! Entries Present</h4>
      </div>
      <div class="modal-body">
        <p>Choosing this option incorporates the 2025 update of cider styles only. Beer and mead remain the same as defined in the 2021 and 2015 updates, respectively.</p>
        <p>There are currently entries logged into the database from participants using previous BJCP cider styles.</p>
        <p><strong>Cider entries</strong> that are currently in the database will be converted from the 2015 update to the 2025 update.</p>
        <p>Additionally, preferred and non-preferred cider styles will be updated to 2025 for all judges. All defined tables incorporating cider styles will be updated as well.</p>
        <p><strong class="text-primary">This cannot be undone.</strong></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">I Understand</button>
      </div>
    </div>
  </div>
</div>
<?php } ?>
<style>h4 { margin-top: 25px; }</style>
<form data-toggle="validator" role="form" class="form-horizontal" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php if ($section == "step3") echo "setup"; else echo $section; ?>&amp;action=edit&amp;go=<?php if ($section == "step3") echo "setup"; else echo $action; ?>&amp;dbTable=<?php echo $preferences_db_table; ?>&amp;id=1" name="form1">
<input type="hidden" name="token" value ="<?php if (isset($_SESSION['token'])) echo $_SESSION['token']; ?>">
<input type="hidden" name="prefsRecordLimit" value="9999" />

<?php if ((($section == "admin") && ($go == "preferences") && ($action == "default")) || ($section == "step3")) { ?>
<h3>General</h3>
<div class="form-group">
    <label for="prefsProEdition" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Competition Type</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">            
            <label class="radio-inline">
                <input type="radio" name="prefsProEdition" value="0" id="prefsProEdition_0"  <?php if (($section != "step3") && ($row_prefs['prefsProEdition'] == "0")) echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?> /> Amateur
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsProEdition" value="1" id="prefsProEdition_1" <?php if (($section != "step3") && ($row_prefs['prefsProEdition'] == "1")) echo "CHECKED"; ?>/> Professional
            </label>
        </div>
        <span class="help-block">Indicate whether the participants in the competition will be individual amateur brewers or licensed breweries with designated points of contact.</span>
    </div>
</div>
<div class="form-group" id="mhp-display">
    <label for="prefsMHPDisplay" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Master Homebrewer Program (MHP) Fields and Display</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">            
            <label class="radio-inline">
                <input type="radio" name="prefsMHPDisplay" value="1" id="prefsMHPDisplay_0"  <?php if ($row_prefs['prefsMHPDisplay'] == "1") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?> /> Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsMHPDisplay" value="0" id="prefsMHPDisplay_1" <?php if ($row_prefs['prefsMHPDisplay'] == "0") echo "CHECKED"; ?>/> Disable
            </label>
        </div>
        <div class="help-block">Enable or disable the ability for entrants to enter their Master Homebrewer Program (MHP) number when adding or editing their account information. When they do so, if this function is enabled, a tag will display beside their name if one or more of their entries place. This will also enable the Winners: Master Homebrewer Program Member Data CSV download available from the Data Exports section of the Administration Dashboard.</div>
    </div>
</div>
<div class="form-group">
    <label for="prefsTheme" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Admin Theme</label>
    <div class="col-lg-6 col-md-5 col-sm-8 col-xs-12">
    
    <select class="selectpicker" name="prefsTheme" id="prefsTheme" data-width="auto">
        <?php 
        $themes = "";
        foreach ($theme_name as $key => $value) {
            $themes .= "<option value=\"".$key."\" ";
            if ($row_prefs['prefsTheme'] == $key) $themes .= " SELECTED";
            $themes .= ">".$value."</option>";
        }
        echo $themes;
        ?>
    </select>
    <div class="help-block">This theme is only for the Administration side of the application. Public themes will be available in future releases.</div>
    </div>
</div>
<?php if (!HOSTED) { ?>
<div class="form-group">
    <label for="prefsSEF" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Search Engine Friendly URLs</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">            
            <label class="radio-inline">
                <input type="radio" name="prefsSEF" value="Y" id="prefsSEF_0"  <?php if ($row_prefs['prefsSEF'] == "Y") echo "CHECKED"; ?> />Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsSEF" value="N" id="prefsSEF_1" <?php if ($row_prefs['prefsSEF'] == "N") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?>/>Disable
            </label>
        </div>
        <div class="help-block">
            <div class="btn-group" role="group" aria-label="SEFModal">
                <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#SEFModal">
                   SEF URLs Info
                </button>
            </div>
        </div>
    </div>
</div>
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
</div>
<div class="form-group">
    <label for="prefsUseMods" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Custom Modules</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">            
            <label class="radio-inline">
                <input type="radio" name="prefsUseMods" value="Y" id="prefsUseMods_0"  <?php if ($row_prefs['prefsUseMods'] == "Y") echo "CHECKED"; ?> /> Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsUseMods" value="N" id="prefsUseMods_1" <?php if ($row_prefs['prefsUseMods'] == "N") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?>/> Disable
            </label>
        </div>
        <div class="help-block"><strong>FOR ADVANCED USERS.</strong> Utilize the ability to add custom modules that extend BCOE&amp;M's core functionality.</div>
    </div>
</div>
<h4>Results</h4>
<div class="form-group">
    <label for="prefsDisplayWinners" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Results Display</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">            
            <label class="radio-inline">
                <input type="radio" name="prefsDisplayWinners" value="Y" id="prefsDisplayWinners_0"  <?php if (($section != "step3") && ($row_prefs['prefsDisplayWinners'] == "Y")) echo "CHECKED"; ?> /> Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsDisplayWinners" value="N" id="prefsDisplayWinners_1" <?php if (($section != "step3") && ($row_prefs['prefsDisplayWinners'] == "N")) echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?>/> Disable
            </label>
        </div>
        <div class="help-block">Indicate if the results of the competition for each category and Best of Show Style Type will be displayed.</div>
    </div>
</div>
<div class="form-group">
    <label for="prefsWinnerDelay" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Results Display Date/Time</label>
    <div class="col-lg-6 col-md-4 col-sm-8 col-xs-12">
            <input class="form-control date-time-picker-system" id="prefsWinnerDelay" name="prefsWinnerDelay" type="text" value="<?php if ($section == "step3") { $date = new DateTime(); $date->modify('+2 months'); echo $date->format('Y-m-d H'); } elseif (!empty($row_prefs['prefsWinnerDelay'])) echo getTimeZoneDateTime($row_prefs['prefsTimeZone'], $row_prefs['prefsWinnerDelay'], $row_prefs['prefsDateFormat'],  $row_prefs['prefsTimeFormat'], "system", "date-time-system"); ?>" placeholder="<?php if (strpos($section, "step") === FALSE) echo $current_date." ".$current_time; ?>" required>
        <div class="help-block">Date and time when the system will display winners if Results Display is enabled.</div>
        <div class="help-block with-errors"></div>
    </div>
</div>
<div class="form-group">
    <label for="prefsWinnerMethod" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Winner Place Distribution Method</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">            
            <?php foreach ($results_method as $key => $value) { ?>
            <label class="radio-inline">
                <input type="radio" name="prefsWinnerMethod" value="<?php echo $key; ?>" id="prefsWinnerMethod_<?php echo $key; ?>" <?php if (($section == "step3") && ($key == "0")) echo "CHECKED"; elseif ($row_prefs['prefsWinnerMethod'] == $key) echo "CHECKED"; ?>> <?php echo $value; ?>
            </label>
            <?php } ?>
        </div>
        <div class="help-block">How the competition will award places for winning entries.</div>
    </div>
</div>
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
</div>
<h4>reCAPTCHA</h4>
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
        <div class="help-block">reCAPTCHA is enabled by default. If you are having trouble with it, disable it here. However, <strong class="text-danger">this is a great security risk</strong> - disabling reCAPTCHA may allow bots to register and/or spam contacts via your site.</div>
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
            <div class="help-block"><strong>To get reCAPTCHA API keys, you will need a Google account.</strong> You can obtain keys on Google's <a class="hide-loader" href="https://www.google.com/recaptcha/admin" target="_blank">reCAPTCHA dashboard</a>. Follow all instructions there or unexpected functionality will occur.</div>
        </div>
    </div>
</div>
<?php } else { ?>
<input type="hidden" name="prefsSEF" value="N" />
<input type="hidden" name="prefsUseMods" value="N" />
<?php } ?>
<h4>Performance and Data Clean-Up</h4>
<div class="form-group">
    <label for="prefsRecordPaging" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Records Displayed</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">        
            <input class="form-control" id="prefsRecordPaging" name="prefsRecordPaging" type="text" value="<?php if ($section == "step3") echo "150"; else echo $row_prefs['prefsRecordPaging']; ?>" placeholder="12" required>
        <div class="help-block">The number of records displayed per page when viewing lists.</div>
        <div class="help-block with-errors"></div>
    </div>
</div>
<div class="form-group">
    <label for="prefsAutoPurge" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Automatically Purge Unconfirmed Entries and Perform Data Clean Up</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">            
            <label class="radio-inline">
                <input type="radio" name="prefsAutoPurge" value="1" id="prefsAutoPurge_0"  <?php if ($row_prefs['prefsAutoPurge'] == "1") echo "CHECKED";  ?> /> Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsAutoPurge" value="0" id="prefsAutoPurge_1" <?php if ($row_prefs['prefsAutoPurge'] == "0") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?>/> Disable
            </label>
        </div>
        <div class="help-block">
            <div class="btn-group" role="group" aria-label="purgeModal">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#purgeModal">
                   Automatically Purge Info
                </button>
            </div>
            </div>
        </div>
    </div>
</div>
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
</div>

<h4>Localization</h4>
<div class="form-group">
    <label for="prefsLanguage" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Language</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">        
        <select class="selectpicker" name="prefsLanguage" id="prefsLanguage" data-live-search="false" data-size="10" data-width="auto">
            <?php foreach ($languages as $lang => $lang_name) { ?>
            <option value="<?php echo $lang; ?>" <?php if ($row_prefs['prefsLanguage'] == $lang) echo "SELECTED"; if (($section == "step3") && ($lang == "en-US")) echo "SELECTED"; ?>><?php echo $lang_name; ?></option>
            <?php } ?>
        </select>
        <div class="help-block">The language to display on all <em>public</em> areas of your installation (e.g., entry information, volunteers, account pages, etc.).</div>
        <div class="help-block with-errors"></div>
    </div>
</div>
<div class="form-group">
    <label for="prefsDateFormat" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Date Format</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">            
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
</div>
<div class="form-group">
    <label for="prefsTimeFormat" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Time Format</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">            
            <label class="radio-inline">
                <input type="radio" name="prefsTimeFormat" value="0" id="prefsTimeFormat_0"  <?php if ($row_prefs['prefsTimeFormat'] == "0") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?> /> 12 Hour
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsTimeFormat" value="1" id="prefsTimeFormat_1" <?php if ($row_prefs['prefsTimeFormat'] == "1") echo "CHECKED"; ?> /> 24 Hour
            </label>
        </div>
    </div>
</div>
<div class="form-group">
    <label for="prefsTimeZone" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Time Zone</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
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
</div>
<h4>Sponsors Display</h4>
<div class="form-group">
    <label for="prefsSponsors" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Sponsor Display</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">            
            <label class="radio-inline">
                <input type="radio" name="prefsSponsors" value="Y" id="prefs0" <?php if (($section != "step3") && ($row_prefs['prefsSponsors'] == "Y")) echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?> /> Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsSponsors" value="N" id="prefs1" <?php if (($section != "step3") && ($row_prefs['prefsSponsors'] == "N")) echo "CHECKED"; ?> /> Disable
            </label>
        </div>
    </div>
</div>
<div class="form-group">
    <label for="prefsSponsorLogos" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Sponsor Logo Display</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">            
            <label class="radio-inline">
                <input type="radio" name="prefsSponsorLogos" value="Y" id="prefsSponsorLogos_0"  <?php if (($section != "step3") && ($row_prefs['prefsSponsorLogos'] == "Y")) echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?> /> Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsSponsorLogos" value="N" id="prefsSponsorLogos_1" <?php if (($section != "step3") && ($row_prefs['prefsSponsorLogos'] == "N")) echo "CHECKED"; ?>/> Disable
            </label>
        </div>
    </div>
</div>
<h4>Drop-Off and Shipping Display</h4>
<div class="form-group">
    <label for="prefsDropOff" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Drop-off Location Display</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">            
            <label class="radio-inline">
                <input type="radio" name="prefsDropOff" value="1" id="prefsDropOff_0"  <?php if (($section != "step3") && ($row_prefs['prefsDropOff'] == "1")) echo "CHECKED"; if ($section == "step3") echo "CHECKED";  ?> /> Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsDropOff" value="0" id="prefsDropOff_1" <?php if (($section != "step3") && ($row_prefs['prefsDropOff'] == "0")) echo "CHECKED";?>/> Disable
            </label>
        </div>
        <div class="help-block">Disable if your competition does not have drop-off locations.</div>
    </div>
</div>
<div class="form-group">
    <label for="prefsShipping" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Shipping Location Display</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">            
            <label class="radio-inline">
                <input type="radio" name="prefsShipping" value="1" id="prefsShipping_0"  <?php if (($section != "step3") && ($row_prefs['prefsShipping'] == "1")) echo "CHECKED"; if ($section == "step3") echo "CHECKED";  ?> /> Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsShipping" value="0" id="prefsShipping_1" <?php if (($section != "step3") && ($row_prefs['prefsShipping'] == "0")) echo "CHECKED"; ?>/> Disable
            </label>
        </div>
        <div class="help-block">Disable if your competition does not have an entry shipping location.</div>
    </div>
</div>
<?php } //end if ($action == "default") ?>


<?php if ((($section == "admin") && ($go == "preferences") && ($action == "email")) || ($section == "step3")) { ?>
<h3>Email Sending</h3>
<div class="form-group">
    <label for="prefsEmailSMTP" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Allow BCOE&amp;M to Send Emails</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">            
            <label class="radio-inline">
                <input type="radio" name="prefsEmailSMTP" value="1" id="prefsEmailSMTP_1"  <?php if (($section != "step3") && ($row_prefs['prefsEmailSMTP'] == 1)) echo "CHECKED"; if ($section == "step3") echo "CHECKED";  ?> /> Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsEmailSMTP" value="0" id="prefsEmailSMTP_0" <?php if (($section == "step3") || (($section != "step3") && ($row_prefs['prefsEmailSMTP'] == 0))) echo "CHECKED"; ?>/> Disable
            </label>
        </div>
        <?php if ($row_prefs['prefsEmailSMTP'] == 3) { ?>
        <div class="text-danger help-block"><p><strong>As of version 3.0.0, a valid email address and SMTP credentials are required to send emails via the BCOE&amp;M application.</strong> To keep the email sending functionality employed in previous versions, select Enable above, provide the required information in the appropriate fields, and select Set Preferences. If you do not wish to send emails via the application, select Disable and then Set Preferences.</p></div>
        <?php } ?>
        <div class="help-block">
        <?php if (HOSTED) { ?>
            <p>If enabled, emails sent from your installation will originate from the noreply@brewingcompetitions.com address. This address is not monitored and all emails generated will contain a discslaimer stating as such.</p>
        <?php } else { ?>
            <p><strong>If enabled, you will need to provide a valid email address and associated information to send emails via the Simple Mail Transfer Protocol (SMTP).</strong></p>
            <p>See your webhost's or email service's documentation for the necessary settings to set up sending emails using SMTP. If you would like to use a Gmail email address, you'll need to set up Gmail SMTP. <a href="https://mailtrap.io/blog/gmail-smtp/#Step-1-Enabling-SMTP-in-Gmail-settings" target="_blank">This guide</a> will help you get the necessary settings.</p>
        <?php } ?>
        </div>
    </div>
</div>
<section id="sending-email-options">
    <div id="send-test-email-show-button" class="row">
        <label for="prefsEmailSMTP-port" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">SMTP Settings Test</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12"> 
            <div class="input-group">
                <a data-fancybox data-type="iframe" data-fancybox data-type="iframe" class="modal-window-link hide-loader btn btn-primary" href="<?php echo $base_url; ?>/admin/send_test_email.admin.php"><?php if (HOSTED) echo "Send Test Email"; else echo "Test Current Email Sending Settings"; ?></a>  
            </div>
            <?php if (!HOSTED) { ?>
            <div class="help-block">
                <p>Send a test email to <?php echo $_SESSION['user_name']; ?> using the current settings as reflected in the database:</p>
                <ul class="small">
                    <li><strong>Originating Email:</strong> <?php echo $row_prefs['prefsEmailFrom']; ?></li>
                    <li><strong>Username:</strong> <?php echo $row_prefs['prefsEmailUsername']; ?></li>
                    <li><strong>Password:</strong> <em>*not displayed for security reasons*</em></li>
                    <li><strong>Host:</strong> <?php echo $row_prefs['prefsEmailHost']; ?></li>
                    <li><strong>Encryption:</strong> <?php echo $row_prefs['prefsEmailEncrypt']; ?></li>
                    <li><strong>Port:</strong> <?php echo $row_prefs['prefsEmailPort']; ?></li>
                </ul>
            </div>
            <?php } ?>
        </div>
    </div>
<?php if (!HOSTED) { ?>
    <div class="form-group">
        <label for="prefsEmailFrom" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Originating Email Address</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">        
                <input class="form-control" id="prefsEmailFrom" name="prefsEmailFrom" type="email" value="<?php if ($section == "step3") echo "";  if (($section != "step3") && (!empty($row_prefs['prefsEmailFrom']))) echo $row_prefs['prefsEmailFrom'];  ?>" data-error="An email address is required." placeholder="name@yourdomain.com" onChange="AjaxFunction(this.value);">
            <div class="help-block with-errors"></div>
            <div class="help-block">Enter the properly set up, functioning, and accessible email address. Participants will reply to this email if needed.</div>
            <div id="msg_email" class="help-block"></div>
        </div>
    </div>
    <div class="form-group">
        <label for="prefsEmailUsername" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">SMTP Username</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">        
                <input class="form-control" id="prefsEmailUsername" name="prefsEmailUsername" type="text" value="<?php if ($section == "step3") echo ""; if (($section != "step3") && (!empty($row_prefs['prefsEmailUsername']))) echo $row_prefs['prefsEmailUsername']; ?>" data-error="The email address username is required. Typically, it's the email address itself." placeholder="name@yourdomain.com">
            <div class="help-block with-errors"></div>
            <div class="help-block">As outlined in your webhost's or email service's documentation - typically, it's the email address itself, but methods may vary.</div>
        </div>
    </div>
    <?php if (empty($row_prefs['prefsEmailPassword'])) { ?>
        <input type="hidden" name="change-email-password-choice" value="1">
    <?php } else { ?>
    <div class="form-group">
        <label for="change-email-password-choice" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php if (!empty($row_prefs['prefsEmailPassword'])) echo "Update "; ?>Password</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
            <div class="input-group">            
                <label class="radio-inline">
                    <input type="radio" name="change-email-password-choice" value="1" id="change-email-password-choice_1" /> Yes
                </label>
                <label class="radio-inline">
                    <input type="radio" name="change-email-password-choice" value="0" id="change-email-password-choice_0" checked/> No
                </label>
            </div>
            <div class="help-block">
                <p>Do you need to update the password provided previously (i.e., has it changed on your email account)?</p>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="form-group" id="change-email-password">
        <label for="prefsEmailPassword" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">SMTP Password</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">        
                <input class="form-control" id="prefsEmailPassword" name="prefsEmailPassword" type="password" value="" data-error="The password is required." placeholder="">
            <div class="help-block with-errors"></div>
            <div class="help-block">As outlined in your webhost's or email service's documentation. This password will be stored encrypted.</div>
        </div>
    </div>
    <div class="form-group">
        <label for="prefsEmailHost" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">SMTP Host</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">        
                <input class="form-control" id="prefsEmailHost" name="prefsEmailHost" type="text" value="<?php if ($section == "step3") echo ""; if (($section != "step3") && (!empty($row_prefs['prefsEmailHost']))) echo $row_prefs['prefsEmailHost']; ?>" data-error="The SMTP email host name is required." placeholder="mail.yourdomain.com">
            <div class="help-block with-errors"></div>
            <div class="help-block">As outlined in your webhost's or email service's documentation - e.g., mail.yourdomain.com.</div>
        </div>
    </div>
    <div class="form-group">
        <label for="prefsEmailEncrypt" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">SMTP Encryption</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
            <div class="input-group">            
                <label class="radio-inline">
                    <input type="radio" name="prefsEmailEncrypt" value="" id="prefsEmailEncrypt_none"  <?php if (($section != "step3") && (empty($row_prefs['prefsEmailEncrypt']))) echo "CHECKED"; if ($section == "step3") echo "CHECKED";  ?> /> None
                </label>
                <label class="radio-inline">
                    <input type="radio" name="prefsEmailEncrypt" value="ssl" id="prefsEmailEncrypt_ssl" <?php if (($section != "step3") && (!empty($row_prefs['prefsEmailEncrypt'])) && ($row_prefs['prefsEmailEncrypt'] == "ssl")) echo "CHECKED"; ?>/> SSL
                </label>
                <label class="radio-inline">
                    <input type="radio" name="prefsEmailEncrypt" value="tls" id="prefsEmailEncrypt_tls" <?php if (($section != "step3") && (!empty($row_prefs['prefsEmailEncrypt'])) && ($row_prefs['prefsEmailEncrypt'] == "tls")) echo "CHECKED"; ?>/> TLS
                </label>
            </div>
            <div class="help-block">As outlined in your webhost's or email service's documentation.</p>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="prefsEmailPort" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">SMTP Port</label>
        <div class="col-lg-6 col-md-6 col-sm-4 col-xs-12">
                <label class="radio-inline">
                    <input type="radio" name="prefsEmailPort" value="25" id="prefsEmailPort_25" <?php if (($section != "step3") && (!empty($row_prefs['prefsEmailPort'])) && ($row_prefs['prefsEmailPort'] == "25")) echo "CHECKED"; ?> /> 25
                </label>
                <label class="radio-inline">
                    <input type="radio" name="prefsEmailPort" value="465" id="prefsEmailPort_465" <?php if (($section != "step3") && (!empty($row_prefs['prefsEmailPort'])) && ($row_prefs['prefsEmailPort'] == "465")) echo "CHECKED"; ?> /> 465
                </label>
                <label class="radio-inline">
                    <input type="radio" name="prefsEmailPort" value="587" id="prefsEmailPort_587" <?php if (($section != "step3") && (!empty($row_prefs['prefsEmailPort'])) && ($row_prefs['prefsEmailPort'] == "587")) echo "CHECKED"; ?>/> 587
                </label>  
            <div class="help-block with-errors"></div>
            <div class="help-block">As outlined in your webhost's or email service's documentation. Port numbers will vary depending upon the encryption protocol - generally 25, 465, or 587.</div>
        </div>
    </div>
<?php } // End if (!HOSTED); ?>
    <div class="form-group">
        <label for="prefsContact" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Contact Form</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
            <div class="input-group">            
                <label class="radio-inline">
                    <input type="radio" name="prefsContact" value="Y" id="prefsContact_0"  <?php if ($row_prefs['prefsContact'] == "Y") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?> /> Enable
                </label>
                <label class="radio-inline">
                    <input type="radio" name="prefsContact" value="N" id="prefsContact_1" <?php if ($row_prefs['prefsContact'] == "N") echo "CHECKED"; ?>/> Disable
                </label>
            </div>
            <div class="help-block">
                <div class="btn-group" role="group" aria-label="contactFormModal">    
                    <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#contactFormModal">Contact Form Info</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="contactFormModal" tabindex="-1" role="dialog" aria-labelledby="contactFormModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bcoem-admin-modal">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="contactFormModalLabel">Contact Form Info</h4>
                </div>
                <div class="modal-body">
                    <p>Enable or disable your installation's contact form. When users fill out the form, an email is generated from the Originating Email Address as input above to a competition official.</p>
                    <p>If disabled, competition email addresses are obfuscated and users will need to select a link and manually copy/paste email addresses into their email platform. </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <?php if (HOSTED) { ?>
    <input type="hidden" name="prefsEmailCC" value="0">
    <?php } else { ?>
    <div class="form-group">
        <label for="prefsEmailCC" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Contact Form CC</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
            <div class="input-group">            
                <label class="radio-inline">
                    <input type="radio" name="prefsEmailCC" value="1" id="prefsEmailCC_1" <?php if ($row_prefs['prefsEmailCC'] == "1") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?>/> Enable
                </label>
                <label class="radio-inline">
                    <input type="radio" name="prefsEmailCC" value="0" id="prefsEmailCC_0"  <?php if ((empty($row_prefs['prefsEmailCC'])) || ($row_prefs['prefsEmailCC'] == "0")) echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?> /> Disable
                </label>
            </div>
            <div class="help-block">
                <p>Enable or disable automatic carbon copying (CC) of emails sent by the system to the "sender" of the email.</p>
                <p><strong>Disabling this function is STRONGLY recommended</strong> &ndash; since any email address can be entered in the From field of the Contact form, disabling CC will prevent malicious actors from using the competition contact form to spam email addresses unrelated to the competition.</p>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="form-group">
        <label for="prefsEmailRegConfirm" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Confirmation Emails</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
            <div class="input-group">            
                <label class="radio-inline">
                    <input type="radio" name="prefsEmailRegConfirm" value="1" id="prefsEmailRegConfirm_1"  <?php if ($row_prefs['prefsEmailRegConfirm'] == "1") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?> /> Enable
              </label>
                <label class="radio-inline">
                    <input type="radio" name="prefsEmailRegConfirm" value="0" id="prefsEmailRegConfirm_0" <?php if ($row_prefs['prefsEmailRegConfirm'] == "0") echo "CHECKED"; ?>/> Disable
                </label>
            </div>
            <div class="help-block">
                <div class="btn-group" role="group" aria-label="contactFormModal">
                    <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#regEmailFormModalLabel">Confirmation Emails Info</button>
                </div>
            </div>
        </div>
    </div>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <section id="send-test-email-show" class="row">
        <label for="prefsEmailSMTP-port" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">SMTP Settings Test</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12"> 
            <div class="input-group">            
                <label class="radio-inline">
                    <input type="radio" name="send-test-email" value="1" id="send-test-email_1" /> Yes
                </label>
                <label class="radio-inline">
                    <input type="radio" name="send-test-email" value="0" id="send-test-email_0" checked/> No
                </label>
            </div>
            <div class="help-block">After setting preferences, send a test email to <?php echo $_SESSION['user_name']; ?> using the settings input above.</div>
        </div>
    </section>
</section>
<?php } // end if ($action == "email") { ?>

<?php if ((($section == "admin") && ($go == "preferences") && ($action == "best")) || ($section == "step3")) { ?>
<h3>Best Brewer<?php if ($_SESSION['prefsProEdition'] == 0) { ?> and/or Club<?php } ?></h3>
<!-- BEST BREWER / BEST CLUB --->
<div class="form-group">
    <label for="prefsShowBestBrewer" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Best Brewer Display? Up to which Position?</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">    
        <select class="selectpicker" name="prefsShowBestBrewer" id="prefsShowBestBrewer" data-size="10" data-width="auto">
            <?php for ($i=-1; $i <= 50; $i++) { ?>
            <option value="<?php echo $i; ?>" <?php if ($row_prefs['prefsShowBestBrewer'] == $i) echo "SELECTED"; elseif (($i == 0) && ($section == "step3")) echo "SELECTED"; ?>><?php if ($i == -1) echo "Display all"; elseif ($i == 0) echo "Do not display"; else echo "Up to ".addOrdinalNumberSuffix($i). " position"; ?></option>
            <?php } ?>
        </select>
        <div class="help-block">Indicate whether you want to display the list of best brewers according to the points and tie break rules defined below and, if so, up to which position. They will be showed at the same time indicated above for the Winners Display.</div>
    </div>
</div>
<div class="form-group">
    <label for="prefsBestBrewerTitle" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Best Brewer Title</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">        
            <input class="form-control" id="prefsBestBrewerTitle" name="prefsBestBrewerTitle" type="text" value="<?php if ($section == "step3") echo ""; else echo $row_prefs['prefsBestBrewerTitle']; ?>" data-error="A Best Brewer title is required." placeholder="">
        <div class="help-block with-errors"></div>
        <div class="help-block">Enter the title for the Best Brewer award (e.g., Heavy Medal, Ninkasi Award).</div>
    </div>
</div>
<div id="bestClub">
    <div class="form-group">
        <label for="prefsShowBestClub" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Best Club Display? Up to which Position?</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">        
        <select class="selectpicker" name="prefsShowBestClub" id="prefsShowBestClub" data-size="10" data-width="auto">
            <?php for ($i=-1; $i <= 50; $i++) { ?>
            <option value="<?php echo $i; ?>" <?php if ($row_prefs['prefsShowBestClub'] == $i) echo "SELECTED"; elseif (($i == 0) && ($section == "step3")) echo "SELECTED"; ?>><?php if ($i == -1) echo "Display all"; elseif ($i == 0) echo "Do not display"; else echo "Up to ".addOrdinalNumberSuffix($i). " position"; ?></option>
            <?php } ?>
        </select>
            <div class="help-block">Indicate whether you want to display the list of best clubs according to the points and tie break rules defined below and, if so, up to which position. They will be showed at the same time indicated above for the Winners Display. Applies ONLY to the amateur edition.</div>
        </div>
    </div>
    <div class="form-group">
        <label for="prefsBestClubTitle" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Best Club Title</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">         
            <input class="form-control" id="prefsBestClubTitle" name="prefsBestClubTitle" type="text" value="<?php if ($section == "step3") echo ""; else echo $row_prefs['prefsBestClubTitle']; ?>" data-error="A Best Club title is required." placeholder="">
            <div class="help-block with-errors"></div>
            <div class="help-block">Enter the title for the Best Club award.</div>
        </div>
    </div>
</div>
<div id="bos-in-calcs" class="form-group">
    <label for="prefsBestUseBOS" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Include BOS in Calculations?</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">            
            <label class="radio-inline">
                <input type="radio" name="prefsBestUseBOS" value="1" id="prefsBestUseBOS_1" <?php if ($row_prefs['prefsBestUseBOS'] == 1) echo "CHECKED"; ?>> Yes
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsBestUseBOS" value="0" id="prefsBestUseBOS_0" <?php if (($section == "step3") || ($row_prefs['prefsBestUseBOS'] == 0)) echo "CHECKED"; ?>> No
            </label>
        </div>
        <span class="help-block">Indicate whether you wish to include any Best of Show (BOS) places in Best Brewer and Best Club calculations.</span>
    </div>
</div>
<div class="form-group">
    <label for="prefsBestUseBOS" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Use Circuit of America Calculations?</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <label class="radio-inline">
                <input type="radio" name="prefsScoringCOA" value="1" id="prefsScoringCOA_1" <?php if ($row_prefs['prefsScoringCOA'] == 1) echo "CHECKED"; ?>> Yes
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsScoringCOA" value="0" id="prefsScoringCOA_0" <?php if (($section == "step3") || ($row_prefs['prefsScoringCOA'] == 0)) echo "CHECKED"; ?>> No
            </label>
        </div>
            <div class="help-block">Indicate whether you wish use the Master Homebrewer Program's <a href="https://www.masterhomebrewerprogram.com/circuit-of-america" target="_blank">Circuit of America</a> scoring methodolgy for all Best Brewer and Best Club calculations. <strong>Indicating "Yes" here will override all other calculation preferences.</strong>
            </div>
        <div class="btn-group" role="group" aria-label="prefsEvalModal">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#prefsScoringCOAModal">
                   Circuit of America Calculations Info
                </button>
            </div>
        </div>        
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="prefsScoringCOAModal" tabindex="-1" role="dialog" aria-labelledby="prefsScoringCOAModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="prefsScoringCOAModalLabel">Circuit of America Scoring Info</h4>
            </div>
            <div class="modal-body">
                <p>Use the Master Homebrewer Program's Circuit of America scoring methodology to determine Best Brewer and Best Club results. The calculations look like this, depending upon your Winner Place Distribution Method:</p>
                <p><img class="img-responsive" src="https://brewingcompetitions.com/00_images/CoA_Scoring_BCOEM.png" ></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<section id="non-COA-scoring">
    <div class="form-group">
        <label for="prefsFirstPlacePts" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Points for First Place</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
            <select class="selectpicker" name="prefsFirstPlacePts" id="prefsFirstPlacePts" data-size="10" data-width="auto">
                <?php for ($i=0; $i <= 25; $i++) { ?>
                <option value="<?php echo $i; ?>" <?php if ($row_prefs['prefsFirstPlacePts'] == $i) echo "SELECTED"; elseif (($i == 0) && ($section == "step3")) echo "SELECTED"; ?>><?php echo $i; ?></option>
                <?php } ?>
            </select>
            <div class="help-block">Enter the number of points awarded for each first place that an entrant receives.</div>
        </div>
    </div>
    <div class="form-group">
        <label for="prefsSecondPlacePts" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Points for Second Place</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">        
            <select class="selectpicker" name="prefsSecondPlacePts" id="prefsSecondPlacePts" data-size="10" data-width="auto">
                <?php for ($i=0; $i <= 25; $i++) { ?>
                <option value="<?php echo $i; ?>" <?php if ($row_prefs['prefsSecondPlacePts'] == $i) echo "SELECTED"; elseif (($i == 0) && ($section == "step3")) echo "SELECTED"; ?>><?php echo $i; ?></option>
                <?php } ?>
            </select>
            <div class="help-block">Enter the number of points awarded for each second place that an entrant receives.</div>
        </div>
    </div>
    <div class="form-group">
        <label for="prefsThirdPlacePts" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Points for Third Place</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">        
            <select class="selectpicker" name="prefsThirdPlacePts" id="prefsThirdPlacePts" data-size="10" data-width="auto">
                <?php for ($i=0; $i <= 25; $i++) { ?>
                <option value="<?php echo $i; ?>" <?php if ($row_prefs['prefsThirdPlacePts'] == $i) echo "SELECTED"; elseif (($i == 0) && ($section == "step3")) echo "SELECTED"; ?>><?php echo $i; ?></option>
                <?php } ?>
            </select>
            <div class="help-block">Enter the number of points awarded for each third place that an entrant receives.</div>
        </div>
    </div>
    <div class="form-group">
        <label for="prefsFourthPlacePts" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Points for Fourth Place</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">        
            <select class="selectpicker" name="prefsFourthPlacePts" id="prefsFourthPlacePts" data-size="10" data-width="auto">
                <?php for ($i=0; $i <= 25; $i++) { ?>
                <option value="<?php echo $i; ?>" <?php if ($row_prefs['prefsFourthPlacePts'] == $i) echo "SELECTED"; elseif (($i == 0) && ($section == "step3")) echo "SELECTED"; ?>><?php echo $i; ?></option>
                <?php } ?>
            </select>
            <div class="help-block">Enter the number of points awarded for each fourth place that an entrant receives.</div>
        </div>
    </div>
    <div class="form-group">
        <label for="prefsHMPts" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Points for Honorable Mention</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">        
            <select class="selectpicker" name="prefsHMPts" id="prefsHMPts" data-size="10" data-width="auto">
                <?php for ($i=0; $i <= 25; $i++) { ?>
                <option value="<?php echo $i; ?>" <?php if ($row_prefs['prefsHMPts'] == $i) echo "SELECTED"; elseif (($i == 0) && ($section == "step3")) echo "SELECTED"; ?>><?php echo $i; ?></option>
                <?php } ?>
            </select>
            <div class="help-block">Enter the number of points awarded for each Honorable Mention that an entrant receives.</div>
        </div>
    </div>
</section>

<?php for ($i=1; $i<=6; $i++) { ?>
<div class="form-group">
    <label for="<?php echo 'prefsTieBreakRule'.$i;?>" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Tie Break Rule #<?php echo $i; ?></label>
    <div class="col-lg-6 col-md-5 col-sm-8 col-xs-12">    
    <select class="selectpicker" name="<?php echo 'prefsTieBreakRule'.$i;?>" id="<?php echo 'prefsTieBreakRule'.$i;?>" data-width="auto">
        <?php foreach ($tie_break_rules as $rule) {
           //$rule_info = explode("|",$rule);
        ?>
        <option value="<?php echo $rule; ?>" <?php if ($row_prefs['prefsTieBreakRule'.$i] ==  $rule) echo "SELECTED"; elseif (($rule == "") && ($section == "step3")) echo " SELECTED"; ?> /><?php echo tiebreak_rule($rule); ?></option>
        <?php } ?>
    </select>
    </div>
</div>
<?php } ?>

<?php } // end if ($action == "best") ?>

<?php 
if ((($section == "admin") && ($go == "preferences") && ($action == "entries")) || ($section == "step3")) { 
include (DB.'entry_info.db.php');
?>
<h3>Entries</h3>
<div class="form-group">
    <label for="prefsStyleSet" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Style Set</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">        
        <select class="selectpicker" name="prefsStyleSet" id="prefsStyleSet" data-size="12" data-width="auto">
        <?php echo $style_set_dropdown; ?>
        </select>
        <div id="helpBlock3-BA" class="help-block">Please note that every effort is made to keep the BA style data current; however, the latest <a class="hide-loader" href="https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/" target="_blank">BA style set</a> may <strong>not</strong> be available in this application.</div>
        <div id="helpBlock4-AABC" class="help-block">Please note that every effort is made to keep the AABC style data current; however, the latest <a class="hide-loader" href="https://aabc.asn.au" target="_blank">AABC style set</a> may <strong>not</strong> be available for use in this application.</div>
    </div>
</div>
<div class="form-group">
    <label for="prefsEntryForm" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Printed Entry Bottle/Can Labels</label>
    <div class="col-lg-6 col-md-3 col-sm-8 col-xs-12">    
    <select class="selectpicker" name="prefsEntryForm" id="prefsEntryForm" data-size="12" data-width="auto">
        <optgroup label="Print Multiple Entries at a Time">
            <option value="7" <?php if (($section != "step3") && ($row_prefs['prefsEntryForm'] == "7")) echo " SELECTED"; ?> />Standard</option>
            <option value="5" <?php if (($section != "step3") && ($row_prefs['prefsEntryForm'] == "5")) echo " SELECTED"; ?> />Standard with Barcode/QR Code</option>
            <option value="8" <?php if (($section != "step3") && ($row_prefs['prefsEntryForm'] == "8")) echo " SELECTED"; ?> />Anonymous</option>
            <option value="6" <?php if (($section != "step3") && ($row_prefs['prefsEntryForm'] == "6")) echo " SELECTED"; ?> />Anonymous with Barcode/QR Code</option>
        </optgroup>
    </select>
        <div class="help-block">
            <div class="btn-group" role="group" aria-label="entryFormModal">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#entryFormModal">Printed Entry Form and/or Bottle Labels Info</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="entryFormModal" tabindex="-1" role="dialog" aria-labelledby="entryFormModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="entryFormModalLabel">Printed Entry Labels</h4>
            </div>
            <div class="modal-body">
                <p>There are two types of entry labels available: Standard and Anonymous.</p>
                <ul>
                    <li>Standard Entry Labels feature the participant's name and contact info, the name of the entry, and the entry's style/category.</li>
                    <li>Anonymous Entry Labels do NOT list the participant's information. These labels are intended to be taped to bottles by entrants before submittal, thereby saving the labor and waste of removing rubberbanded labels by competition staff when sorting. This approach is similar to the method used in the National Homebrew Competition final round.</li>
                </ul>
                <p>Both label types are available with or without a entry number barcode and QR code.</p>
                <p>The Barcode options are intended to be used with a USB barcode scanner and the <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=checkin">barcode entry check-in function</a>.</p>
                <p>The QR code options are intended to be used with a mobile device and <a class="hide-loader" href="<?php echo $base_url; ?>qr.php" target="_blank">QR code entry check-in function</a> (requires a QR code reading app).</p>
                <div class="well">
                <p>Both the QR code and barcode options are intended to be used with the Judging Number Barcode Labels and the Judging Number Round Labels <a class="hide-loader" href="http://www.brewingcompetitions.com/barcode-labels" target="_blank"><strong>available for download at brewingcompetitions.com</strong></a>. BCOE&amp;M utilizes the&nbsp;<strong><a class="hide-loader" href="http://en.wikipedia.org/wiki/Code_39" target="_blank">Code 39 specification</a></strong> to generate all barcodes. Please make sure your scanner recognizes this type of barcode <em>before</em> implementing in your competition.</p>
                </div>
                <p class="text-primary"><strong>As of version 2.7.1, the label options that printed one page per entry have been removed. General feedback was that they were either unused or unnecessarily wasteful.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div id="prefsHideSpecific" class="form-group">
    <label for="prefsHideSpecific" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Hide Brewer&rsquo;s Specifics Field</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <label class="radio-inline">
                <input type="radio" name="prefsSpecific" value="1" id="prefsSpecific_0"  <?php if ($row_prefs['prefsSpecific'] == "1") echo "CHECKED"; ?> /> Yes
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsSpecific" value="0" id="prefsSpecific_1" <?php if ($row_prefs['prefsSpecific'] == "0") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?>/> No
            </label>
        </div>
        <div class="help-block">
            <div class="btn-group" role="group" aria-label="prefsSpecificModal">
                <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#prefsSpecificModal">
                       Hide Brewer&rsquo;s Specifics Field Info
                </button>
            </div>
        </div>
    </div>
</div>
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
</div>
<div class="form-group">
    <label for="prefsSpecialCharLimit" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Character Limit for Text Entry</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">    
    <select class="selectpicker" name="prefsSpecialCharLimit" id="prefsSpecialCharLimit" data-size="10">
        <?php for ($i=25; $i <= 255; $i+=5) { ?>
        <option value="<?php echo $i; ?>" <?php if (($section == "step3") && ($i == "150")) echo "SELECTED"; elseif ((isset($row_limits['prefsSpecialCharLimit'])) && ($row_limits['prefsSpecialCharLimit'] == $i)) echo "SELECTED"; ?>><?php echo $i; ?></option>
    <?php } ?>
    </select>
    <div class="help-block">
        <p>Indicate the limit of characters users can enter when specifying special ingredients, optional ingredients, and brewer's specifics. A limit of <strong>65 characters or less</strong> is suggested for competitions that attach &ldquo;Bottle Labels with Required Info&rdquo; to entry bottles at sorting.</p>
        <div class="btn-group" role="group" aria-label="charLimitModal">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#charLimitModal">
                   Character Limit Info
                </button>
            </div>
        </div>
    </div>
    </div>
</div>
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
</div>

<h4>Fees and Discounts</h4>
<div class="form-group">
    <label for="contestEntryFee" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Per Entry Fee</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group has-warning">
            <span class="input-group-addon" id="contestEntryFee-addon1"><?php echo $currency_symbol; ?></span>
            
            <input class="form-control" id="contestEntryFee" name="contestEntryFee" type="number" maxlength="5" step=".01" value="<?php if ($section != "step4") echo number_format($row_contest_info['contestEntryFee'],2); ?>" placeholder="" required>
            <div class="input-group-addon" id="contestEntryFee-addon2" data-tooltip="true" title="<?php echo $form_required_fields_02; ?>"><span class="fa fa-star"></span></div>
        </div>
        <div class="help-block">Fee for a single entry. Enter a zero (0) for a free entry fee.</div>
    </div>
</div>

<div class="form-group">
    <label for="contestEntryCap" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Fee Cap</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <span class="input-group-addon" id="contestEntryCap-addon1"><?php echo $currency_symbol; ?></span>
            <input class="form-control" id="contestEntryCap" name="contestEntryCap" type="number" maxlength="5" step=".01" value="<?php if (($section != "step4") && (isset($row_contest_info['contestEntryCap'])) && (!empty($row_contest_info['contestEntryCap']))) echo number_format($row_contest_info['contestEntryCap'],2); ?>" placeholder="">
        </div>
        <div class="help-block">Enter the maximum amount for each entrant. Leave blank if no cap.
            <a tabindex="0"  type="button" role="button" data-toggle="popover" data-trigger="hover" data-placement="auto right" data-container="body"  data-content="Useful for competitions with &ldquo;unlimited&rdquo; entries for a single fee (e.g., <?php if ($section != "step4") echo $currency_symbol; ?>X for the first X number of entries, <?php if ($section != "step4") echo $currency_symbol; ?>X for unlimited entries, etc.). "><span class="fa fa-question-circle"></span></a>
        </div>
    </div>
</div>

<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="contestEntryFeeDiscount" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Discount Multiple Entries</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">      
            <label class="radio-inline">
                <input type="radio" name="contestEntryFeeDiscount" value="Y" id="contestEntryFeeDiscount_0" <?php if (($section != "step4") && ($row_contest_info['contestEntryFeeDiscount'] == "Y")) echo "CHECKED"; ?> /> Yes
            </label>
            <label class="radio-inline">
                <input type="radio" name="contestEntryFeeDiscount" value="N" id="contestEntryFeeDiscount_1" <?php if (($section != "step4") && ($row_contest_info['contestEntryFeeDiscount'] == "N")) echo "CHECKED"; if ($section == "step4") echo "CHECKED"; ?>/> No
            </label>
        </div>
        <div class="help-block">Designate Yes or No if your competition offers a discounted entry fee after a certain number is reached.</div>
    </div>
</div>

<div class="form-group">
    <label for="contestEntryFeeDiscountNum" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Minimum Entries for Discount</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        
            <input class="form-control" id="contestEntryFeeDiscountNum" name="contestEntryFeeDiscountNum" type="text" value="<?php if ($section != "step4") echo $row_contest_info['contestEntryFeeDiscountNum']; ?>" placeholder="">
        <div class="help-block">The entry threshold participants must exceed to take advantage of the per entry fee discount (designated below). If no, discounted fee exists, leave blank.</div>
    </div>
</div>

<div class="form-group">
    <label for="contestEntryFee2" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Discounted Entry Fee</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <span class="input-group-addon" id="contestEntryFee2-addon1"><?php echo $currency_symbol; ?></span>     
            <input class="form-control" id="contestEntryFee2" name="contestEntryFee2" type="number" maxlength="5" step=".01" value="<?php if (($section != "step4") && (isset($row_contest_info['contestEntryFee2'])) && (!empty($row_contest_info['contestEntryFee2']))) echo number_format($row_contest_info['contestEntryFee2'],2); ?>" placeholder="">
        </div>
        <div class="help-block">Fee for a single, discounted entry.</div>
    </div>
</div>
<?php
$secretKey = base64_encode(bin2hex($password));
$nacl = base64_encode(bin2hex($server_root));
$contestEntryFeePassword = "";
if (isset($row_contest_info['contestEntryFeePassword'])) $contestEntryFeePassword = simpleDecrypt($row_contest_info['contestEntryFeePassword'], $secretKey, $nacl);
?>
<div class="form-group">
    <label for="contestEntryFeePassword" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Member Discount Password</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <input class="form-control" id="contestEntryFeePassword" name="contestEntryFeePassword" type="text" value="<?php echo $contestEntryFeePassword; ?>" placeholder="">
        <div class="help-block">Designate a password for participants to enter to receive discounted entry fees. Useful if your competition provides a discount for members of the sponsoring club(s).</div>
    </div>
</div>
<div class="form-group">
    <label for="contestEntryFeePasswordNum" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Member Discount Fee</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <span class="input-group-addon" id="contestEntryFeePasswordNum-addon1"><?php echo $currency_symbol; ?></span>
            
            <input class="form-control" id="contestEntryFeePasswordNum" name="contestEntryFeePasswordNum" type="number" maxlength="5" step=".01" value="<?php if (($section != "step4") && (isset($row_contest_info['contestEntryFeePasswordNum'])) && (!empty($row_contest_info['contestEntryFeePasswordNum']))) echo number_format($row_contest_info['contestEntryFeePasswordNum'],2); ?>" placeholder="">
        </div>
        <div class="help-block">Fee for a single, discounted member entry. If you wish the member discount to be free, enter a zero (0). Leave blank for no discount.</div>
    </div>
</div>

<h4>Limits</h4>
<div class="form-group">
    <label for="prefsEntryLimit" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Total Entry Limit &ndash; Paid/Unpaid</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">        
            <input class="form-control" id="prefsEntryLimit" name="prefsEntryLimit" type="text" value="<?php if (isset($row_limits['prefsEntryLimit'])) echo $row_limits['prefsEntryLimit']; ?>" placeholder="">
        <div class="help-block">Limit of <strong class="text-danger">total</strong> entries you will accept in the competition. Leave blank if no limit.</div>
    </div>
</div>
<div class="form-group">
    <label for="prefsEntryLimit" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Total Entry Limit &ndash; Paid</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">        
        <input class="form-control" id="prefsEntryLimitPaid" name="prefsEntryLimitPaid" type="text" value="<?php if (isset($row_limits['prefsEntryLimitPaid'])) echo $row_limits['prefsEntryLimitPaid']; ?>" placeholder="">
        <div class="help-block">
            <div class="btn-group" role="group" aria-label="charLimitModal">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#entryLimitPaidModal">
                       Paid Entry Limit Info
                    </button>
                </div>
            </div>
            <p>Limit of <strong class="text-danger">paid</strong> entries you will accept in the competition. Leave blank if no limit.</p>
        </div>
    </div>
</div>
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
</div>
<?php
$st_arr = array();
if (strpos($section, "step") === FALSE) {
    $st_count = 0;
    do {
        $st_arr[] = $row_style_type['id'];
        $st_count++;
?>
<div class="form-group">
    <label for="styleTypeEntryLimit-<?php echo $row_style_type['id']; ?>" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Entry Limit &ndash; <?php echo $row_style_type['styleTypeName']; ?></label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <input class="form-control" id="styleTypeEntryLimit-<?php echo $row_style_type['id']; ?>" name="styleTypeEntryLimit-<?php echo $row_style_type['id']; ?>" type="number" min="0" value="<?php echo $row_style_type['styleTypeEntryLimit']; ?>" placeholder="">
        <div class="help-block"><?php if ($st_count == $totalRows_style_type) echo "Individual style type entry limits above are only for those that have BOS enabled. <a href='".$base_url."index.php?section=admin&amp;go=style_types'>Manage your competition style types</a> to specify entry limits for others."; ?></div>
    </div>
</div>
<?php 
    } while ($row_style_type = mysqli_fetch_assoc($style_type)); 
}
?>
<input name="style_type_entry_limits" type="hidden" value="<?php if (!empty($st_arr)) echo implode(",", $st_arr); ?>">
<div class="form-group">
    <label for="choose-style-entry-limits" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Entry Limits by Style or Table/Medal Group</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <label class="radio-inline">
                <input type="radio" name="choose-style-entry-limits" value="0" id="choose-style-entry-limits_0" <?php if (($section != "step3") && (!$limits_by_style) && (!$limits_by_table)) echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?>/> Disable
            </label>
            <label class="radio-inline">
                <input type="radio" name="choose-style-entry-limits" value="2" id="choose-style-entry-limits_2"  <?php if (($section != "step3") && ($limits_by_table)) echo "CHECKED";  ?> /> Enable By Table or Medal Group
            </label>
            <label class="radio-inline">
                <input type="radio" name="choose-style-entry-limits" value="1" id="choose-style-entry-limits_1"  <?php if (($section != "step3") && ($limits_by_style)) echo "CHECKED";  ?> /> Enable By Style
            </label>
        </div>
        <div class="help-block">
            <p id="limit-entries-will-reset" class="alert alert-info"><strong class="text-primary">Please note:</strong> If you choose a different entry limit method than what is currently defined, any limits set previously will be deleted and all styles previously disabled due to limits will be enabled.</p> 
            <p id="limit-entries-by-table-help" class=""><strong>Limiting by table or medal group</strong> requires that your installation be placed into <strong>Tables Planning Mode</strong> and <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables">tables/medal groups defined</a> (limits are set when creating or editing tables/medal groups).</p>
            <p id="limit-entries-by-style-help" class=""><strong>Limiting entries by style</strong> allows you to define a numerical limit on overall styles or style groups. Define your per-style limits below.</p>
        </div>
    </div>
</div>
<section id="define-style-entry-limits">
<?php echo $current_entry_limits_by_style; ?>
<?php echo $entry_limit_by_style; ?>
</section>






<div class="form-group">
    <label for="prefsUserEntryLimit" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Overall Entry Limit per Participant</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">    
    <select class="selectpicker" name="prefsUserEntryLimit" id="prefsUserEntryLimit" data-size="10">
        <option value="" rel="none" <?php if (empty($real_overall_user_entry_limit)); echo "SELECTED"; ?>></option>
        <?php for ($i=1; $i <= 25; $i++) { ?>
        <option value="<?php echo $i; ?>" <?php if ($real_overall_user_entry_limit == $i) echo "SELECTED"; ?>><?php echo $i; ?></option>
        <?php } ?>
    </select>
    <div class="help-block">Overall limit of entries that each participant can enter. Will override any incremental limit defined below IF this number is lower. Leave blank if no OVERALL entry limit.</div>
    </div>
</div>
<?php for ($i=1; $i <= 4; $i++) { ?>
<section id="user-entry-limit-increment-<?php echo $i; ?>">
<?php if ($i == 2) echo "<hr>"; ?>
<div class="form-group">
    <label for="user-entry-limit-number-<?php echo $i; ?>" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">#<?php echo $i; ?> Incremental Entry Limit per Participant</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
    <select class="selectpicker" name="user-entry-limit-number-<?php echo $i; ?>" id="user-entry-limit-number-<?php echo $i; ?>" data-size="10">
        <option value="" rel="none"></option>
        <?php for ($a=1; $a <= 25; $a++) { ?>
        <option value="<?php echo $a; ?>" <?php if ($incremental) { if ((isset($incremental_limits[$i]['limit-number'])) && ($incremental_limits[$i]['limit-number'] == $a)) echo "SELECTED"; } ; ?> ><?php echo $a; ?></option>
        <?php } ?>
    </select>
    <div class="help-block">Numerical limit of entries per paricipant for the specifid number of days AFTER the entry window opening date (<?php echo $entry_open; ?>).</div>
    </div>
</div>
<div class="form-group">
    <label for="user-entry-limit-expire-days-<?php echo $i; ?>" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">#<?php echo $i; ?> Incremental Entry Limit per Participant <span class="text-primary">Days</span></label>
    <div class="col-lg-6 col-md-4 col-sm-8 col-xs-12">
        <select class="selectpicker" id="user-entry-limit-expire-days-<?php echo $i; ?>" name="user-entry-limit-expire-days-<?php echo $i; ?>" data-size="10">
            <option value="" rel="none"></option>
            <?php for ($b=1; $b <= 60; $b++) { ?>
            <option  value="<?php echo $b; ?>" <?php if ($incremental) { if ((isset($incremental_limits[$i]['limit-days'])) && ($incremental_limits[$i]['limit-days'] == $b)) echo "SELECTED"; } ; ?> ><?php echo $b; if (isset($row_contest_dates['contestEntryOpen'])) echo " - ".getTimeZoneDateTime($_SESSION['prefsTimeZone'],($row_contest_dates['contestEntryOpen'] + ($b*86400)), $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time"); ?></option>
            <?php } ?>
        </select>
        <div class="help-block">Number of days AFTER the entry window opening date that the #<?php echo $i; ?> per participant limit will EXPIRE.</div>
        <div class="help-block with-errors"></div>
    </div>
</div>
<?php if ($i > 1) echo "<hr>"; ?>
</section>
<?php } ?>
<?php if ($go == "preferences") { ?>
<div class="form-group">
    <label for="prefsUserSubCatLimit" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Per Participant Sub-Style Entry Limit</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
    <select class="selectpicker" name="prefsUserSubCatLimit" id="prefsUserSubCatLimit" data-size="10">
        <option value="" <?php ($row_limits['prefsUserSubCatLimit'] == ""); echo "SELECTED"; ?>></option>
        <?php for ($i=1; $i <= 25; $i++) { ?>
        <option value="<?php echo $i; ?>" <?php if ($row_limits['prefsUserSubCatLimit'] == $i) echo "SELECTED"; ?>><?php echo $i; ?></option>
        <?php } ?>
    </select>
    <div class="help-block">Limit of entries that each participant can enter into a single sub-style. Leave blank if no limit.</div>
    </div>
</div>
<!-- Insert Collapsable -->
<div id="subStyleExeptions">
    <div class="form-group">
        <label for="prefsUSCLExLimit" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Per Participant Entry Limit For <em>Excepted</em> Sub-Styles</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">        
            <select class="selectpicker" name="prefsUSCLExLimit" id="prefsUSCLExLimit" data-size="10" data-width="auto">
                <option value="" rel="none" <?php ($row_limits['prefsUSCLExLimit'] == ""); echo "SELECTED"; ?>></option>
                <?php for ($i=1; $i <= 100; $i++) { ?>
                <option value="<?php echo $i; ?>" <?php if ($row_limits['prefsUSCLExLimit'] == $i) echo "SELECTED"; ?>><?php echo $i; ?></option>
                <?php } ?>
            </select>
            <div class="help-block">
                <div class="btn-group" role="group" aria-label="exceptdSubstylesModal">
                    <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#exceptdSubstylesModal">
                       Per Participant Entry Limit For <em>Excepted</em> Sub-Styles Info
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group" id="subStyleExeptionsEdit">
        <label for="prefsUSCLEx" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Exceptions to Per Participant Sub-Style Entry Limit</label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <?php if (strpos($section, "step") === FALSE) { ?>
                <div class="btn-group" role="group">
                    <button class="btn btn btn-default" data-toggle="collapse" href="#sub-style-list" aria-expanded="false" aria-controls="sub-style-list">Expand/Collapse the Sub-Style List</button>
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
</div>
<?php } else { ?>
<input type="hidden" name="prefsUserSubCatLimit" value="">
<input type="hidden" name="prefsUSCLExLimit" value="">
<?php } ?>

<?php } // end if ($action == "entries") ?>

<?php if ((($section == "admin") && ($go == "preferences") && ($action == "payment")) || ($section == "step3")) { ?>
<h3>Currency and Payment</h3>
<div class="form-group">
    <label for="prefsCurrency" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Currency</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">        
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
        <div class="help-block">
            <div class="btn-group" role="group" aria-label="currencyModal">
                <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#currencyModal">
                   Currency Info
                </button>
            </div>
        </div>
    </div>
</div>
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
</div>
<div class="form-group">
    <label for="prefsPayToPrint" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Pay to Print Paperwork</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            
            <label class="radio-inline">
                <input type="radio" name="prefsPayToPrint" value="Y" id="prefsPayToPrint_0"  <?php if ($row_prefs['prefsPayToPrint'] == "Y") echo "CHECKED"; ?> />Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsPayToPrint" value="N" id="prefsPayToPrint_1" <?php if ($row_prefs['prefsPayToPrint'] == "N") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?>/>
                Disable
            </label>
        </div>
        <div class="help-block">
            <div class="btn-group" role="group" aria-label="payPrintModal">
                <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#payPrintModal">
                   Pay to Print Paperwork Info
                </button>
            </div>
        </div>
    </div>
</div>
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
</div>
<div class="form-group">
    <label for="prefsCash" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Cash for Payment</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">            
            <label class="radio-inline">
                <input type="radio" name="prefsCash" value="Y" id="prefsCash_0"  <?php if ($row_prefs['prefsCash'] == "Y") echo "CHECKED"; ?> />Enable
            </label>
          <label class="radio-inline">
                <input type="radio" name="prefsCash" value="N" id="prefsCash_1" <?php if ($row_prefs['prefsCash'] == "N") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?>/>
              Disable
          </label>
        </div>
    </div>
</div>
<div class="form-group">
    <label for="prefsCheck" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Checks for Payment</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">            
            <label class="radio-inline">
                <input type="radio" name="prefsCheck" value="Y" id="prefsCheck_0"  <?php if ($row_prefs['prefsCheck'] == "Y") echo "CHECKED"; ?> />Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsCheck" value="N" id="prefsCheck_1" <?php if ($row_prefs['prefsCheck'] == "N") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?>/>
                Disable</label>
        </div>
    </div>
</div>
<div class="form-group" id="checks-payment">
    <label for="prefsCheckPayee" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Checks Payee</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        
        <input class="form-control" id="prefsCheckPayee" name="prefsCheckPayee" type="text" value="<?php echo $row_prefs['prefsCheckPayee']; ?>" data-error="A check payee is required." placeholder="">
    <div class="help-block with-errors"></div>
    </div>
</div>
<div class="form-group">
    <label for="prefsPaypal" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">PayPal for Payment</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">            
            <label class="radio-inline">
                <input type="radio" name="prefsPaypal" value="Y" id="prefsPaypal_0"  <?php if ($row_prefs['prefsPaypal'] == "Y") echo "CHECKED"; ?> />Enable
            </label>
            <label class="radio-inline">
                <input type="radio" name="prefsPaypal" value="N" id="prefsPaypal_1" <?php if ($row_prefs['prefsPaypal'] == "N") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?>/>
                Disable</label>
        </div>
    </div>
</div>
<div id="paypal-payment">
    <div class="form-group">
        <label for="prefsPaypalAccount" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">PayPal Account Email</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">            
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
    </div>
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
    </div>
    <div class="form-group">
        <label for="prefsPaypal" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><a class="hide-loader" href="https://developer.paypal.com/api/nvp-soap/ipn/" target="_blank">PayPal Instant Payment Notification</a> (IPN)</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
            <div class="input-group">                
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
                <p>The IPN Notification URL must be added in your <a href="https://www.paypal.com/merchantnotification/ipn/preference" target="_blank">PayPal IPN Settings</a> with the value: <strong><?php echo $base_url; ?>ppv.php</strong><br>The IPN Auto Return URL must be added in your <a href="https://www.paypal.com/businessmanage/preferences/website" target="_blank">PayPal Website payment preferences</a> with the value: <strong><?php echo $base_url; ?>index.php?section=pay&amp;msg=10</strong></p>
                <p>Be sure to select the <em>PayPal IPN Info and Setup</em> button above for requirements and further info.</p>
            </div>
        </div>
    </div>
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
                    <p class="text-primary"><strong>Second, to implement PayPal IPN, your PayPal account must be a <u>business</u> account.</strong></p>
                    <p class="text-primary"><strong>Third, set up your PayPal account to process Instant Payment Notifications.</strong> Complete instructions are <a class="hide-loader" href="http://brewingcompetitions.com/paypal-ipn" target="_blank">available here</a>.</p>
                    <p>Your IPN Notification URL must be added in your <a href="https://www.paypal.com/merchantnotification/ipn/preference" target="_blank">PayPal IPN Settings</a> with the value: <blockquote><strong><?php echo $base_url; ?>ppv.php</strong></blockquote></p>
                    <p>Your IPN Auto Return URL must be added in your <a href="https://www.paypal.com/businessmanage/preferences/website" target="_blank">PayPal Website payment preferences</a> with the value: <blockquote><strong><?php echo $base_url; ?>index.php?section=pay&amp;msg=10</strong></blockquote></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="prefsTransFee" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Checkout Fees Paid by Entrant</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
            <div class="input-group">                
                <label class="radio-inline">
                    <input type="radio" name="prefsTransFee" value="Y" id="prefsTransFee_0"  <?php if ($row_prefs['prefsTransFee'] == "Y") echo "CHECKED"; ?> />Enable
                </label>
                <label class="radio-inline">
                    <input type="radio" name="prefsTransFee" value="N" id="prefsTransFee_1" <?php if ($row_prefs['prefsTransFee'] == "N") echo "CHECKED"; elseif ($section == "step3") echo "CHECKED"; ?>/>
                    Disable</label>
            </div>
            <div class="help-block">
                <div class="btn-group" role="group" aria-label="payPalFeeModal">
                    <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#payPalFeeModal">
                       Entrant Pays Checkout Fees Info
                    </button>
                </div>
            </div>
        </div>
    </div>
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
    </div>
</div>
<?php } // end if ($section == "payment") ?>
<div style="margin-top: 30px;" class="bcoem-admin-element hidden-print">
    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-12 ">&nbsp;</div>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="form-group">
            <button type="submit" name="submit-prefs" id="setWebsitePrefs" class="btn btn-primary" />Set Preferences</button>
        </div>
    </div>
</div>
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin","default",$msg,$id); ?>">
<?php } ?>
</form>