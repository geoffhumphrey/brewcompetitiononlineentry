<?php
/**
 * Module:      index.php
 * Description: This module is the delivery vehicle for all modules.
 *
 */

// ---------------------------- Load Config Scripts ------------------------------

require_once ('paths.php');
require_once (CONFIG.'bootstrap.php');
require_once (DB.'mods.db.php');

$account_pages = array("list","pay","brewer","user","brew","pay","evaluation");

if ((!$logged_in) && (in_array($section,$account_pages))) {
    $redirect = $base_url."index.php?section=login&msg=99";
    $redirect = prep_redirect_link($redirect);
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

if (MAINT) {

    if ((!$logged_in) || (($logged_in) && ($_SESSION['userLevel'] > 0))) {
        $redirect = $base_url."maintenance.php";
        $redirect = prep_redirect_link($redirect);
        $redirect_go_to = sprintf("Location: %s", $redirect);
        header($redirect_go_to);
        exit();
    }

}

// ---------------------------- Admin Only Functions -------------------------------

if ($section == "admin") {

    // Redirect if non-admins try to access admin functions
    if (!$logged_in) {

        $redirect = $base_url."index.php?section=login&msg=0";
        $redirect = prep_redirect_link($redirect);
        $redirect_go_to = sprintf("Location: %s", $redirect);
        header($redirect_go_to);
        exit();

    }

    if (($logged_in) && ($_SESSION['userLevel'] > 1)) {
        
        $redirect = $base_url."index.php?msg=4";
        $redirect = prep_redirect_link($redirect);
        $redirect_go_to = sprintf("Location: %s", $redirect);
        header($redirect_go_to);
        exit();

    }

    require_once (LIB.'admin.lib.php');
    require_once (DB.'admin_common.db.php');
    require_once (DB.'judging_locations.db.php');
    require_once (DB.'stewarding.db.php');
    require_once (DB.'dropoff.db.php');
    require_once (DB.'contacts.db.php');
}

// ---------------------------- Other Top-Level Constants -------------------------------

require_once (INCLUDES.'constants_post_lang.inc.php');

// Hosted installations only
if (HOSTED) require_once (LIB.'hosted.lib.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $_SESSION['contestName']; ?> - Brew Competition Online Entry &amp; Management</title>
<?php
    if (CDN) include (INCLUDES.'load_cdn_libraries.inc.php');
    else include (INCLUDES.'load_local_libraries.inc.php');
?>
    <!-- Load BCOE&M Custom CSS - Contains Bootstrap overrides and custom classes common to all BCOE&M themes -->
    <link rel="stylesheet" type="text/css" href="<?php echo $css_common_url; ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo $theme; ?>" />

    <script type="text/javascript">
        var username_url = "<?php echo $ajax_url; ?>username.ajax.php";
        var email_url="<?php echo $ajax_url; ?>valid_email.ajax.php";
        var user_agent_msg = "<?php echo $alert_text_086; ?>";
        var setup = 0;
    </script>
    
    <!-- Open Graph Implementation -->
<?php if (!empty($_SESSION['contestName'])) { ?>
    <meta property="og:title" content="<?php echo $_SESSION['contestName']?>" />
<?php } ?>
<?php if (!empty($_SESSION['contestLogo'])) { ?>
    <meta property="og:image" content="<?php echo $base_url."user_images/".$_SESSION['contestLogo']; ?>" />
<?php } ?>
    <meta property="og:url" content="<?php echo "http" . ((!empty($_SERVER['HTTPS'])) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" />
</head>
<body>

<a name="top"></a>

<!-- LOADER -->
<div id="loader-submit">
    <div class="center">
        <span class="fa fa-cog fa-spin fa-5x fa-fw"></span>
        <p><strong><?php echo $label_working; ?>.<br><?php echo $output_text_030." ".$output_text_031; ?></strong></p>
    </div>
</div>
<!-- ./LOADER -->

<!-- MAIN NAV -->
<div class="<?php echo $container_main; ?> hidden-print">
    <?php include (SECTIONS.'nav.sec.php'); ?>
</div><!-- container -->
<!-- ./MAIN NAV -->

<!-- ALERTS -->
<div class="<?php echo $container_main; ?> bcoem-warning-container">
    
<?php

$errors_display = "";

if ((!empty($_SESSION['error_output'])) || (!empty($error_output))) {
    
    $errors_display .= "<div class=\"bcoem-admin-element\">";
    $errors_display .= "<div class=\"alert alert-danger alert-dismissible hidden-print fade in\">";
    $errors_display .= "<p><span class=\"fa fa-lg fa-exclamation-circle\"></span> <strong>Error(s)</strong></p>";
    $errors_display .= "<p>The following errors were logged on the last MySQL server call:</p>";
    $errors_display .= "<ul>";
    
    if (!empty($error_output)) {
        foreach ($error_output as $key => $value) {
            $errors_display .= "<li>".$value."</li>";
        }
    }

    if (!empty($_SESSION['error_output'])) {
        foreach ($_SESSION['error_output'] as $key => $value) {
            $errors_display .= "<li>".$value."</li>";
        }
    }
        
    $errors_display .= "</ul>";
    $errors_display .= "</div>";
    $errors_display .= "</div>";
    
}

if (!empty($errors_display)) echo $errors_display;

include (SECTIONS.'alerts.sec.php'); 

?>

</div><!-- ./container -->
<!-- ./ALERTS -->

<!-- Top line JS -->
<script>
    $("#no-js-alert").hide();
    try {
        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");
        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
            alert(user_agent_msg)
        }
    } catch (error) {
        console.error('Error checking user agent.', error)
    }
</script>

<!-- DEBUG -->
<div class="<?php echo $container_main; ?> hidden-print">
<?php if (DEBUG_SESSION_VARS) include (DEBUGGING.'session_vars.debug.php'); ?>
</div>
<!-- ./DEBUG -->

<?php if ($_SESSION['prefsUseMods'] == "Y") { ?>
<!-- MODS TOP -->
<div class="<?php echo $container_main; ?> hidden-print">
<?php include (INCLUDES.'mods_top.inc.php'); ?>
</div>
<!-- ./MODS TOP -->
<?php } ?>
<?php if (($section == "admin") && (($logged_in) && ($_SESSION['userLevel'] <= 1))) { ?>
<!-- Admin Pages (Fluid Layout) -->
<div class="container-fluid">
    <?php if ($go == "default") { ?>
    <!-- Admin Dashboard - Has sidebar -->
    <div class="row">
        <div class="col col-lg-9 col-md-8 col-sm-12 col-xs-12">
        <div class="page-header">
            <h1><?php echo $header_output; ?></h1>
        </div>
        <?php include (ADMIN.'default.admin.php'); ?>
        </div><!-- ./left column -->
        <div class="sidebar col col-lg-3 col-md-4 col-sm-12 col-xs-12">
            <?php include (ADMIN.'sidebar.admin.php'); ?>
        </div><!-- ./sidebar -->
    </div><!-- ./row -->
    <?php } else { ?>
    <!-- Admin Page - full width of viewport -->
        <div class="page-header">
            <h1><?php echo $header_output; ?></h1>
        </div>
        <?php

            if ($go == "judging") include (ADMIN.'judging_locations.admin.php');
            if ($go == "non-judging") include (ADMIN.'non-judging_locations.admin.php');
            if ($go == "judging_preferences") include (ADMIN.'judging_preferences.admin.php');
            if ($go == "judging_tables") include (ADMIN.'judging_tables.admin.php');
            if ($go == "judging_flights") include (ADMIN.'judging_flights.admin.php');
            if ($go == "judging_scores") include (ADMIN.'judging_scores.admin.php');
            if ($go == "judging_scores_bos") include (ADMIN.'judging_scores_bos.admin.php');
            if ($go == "participants") include (ADMIN.'participants.admin.php');
            if ($go == "entries") include (ADMIN.'entries.admin.php');
            if ($go == "contacts") include (ADMIN.'contacts.admin.php');
            if ($go == "dropoff") include (ADMIN.'dropoff.admin.php');
            if ($go == "checkin") include (ADMIN.'barcode_check-in.admin.php');
            if ($go == "count_by_style") include (ADMIN.'entries_by_style.admin.php');
            if ($go == "count_by_substyle") include (ADMIN.'entries_by_substyle.admin.php');
            if ($action == "register") include (SECTIONS.'register.sec.php');
            if ($go == "upload_scoresheets") include (ADMIN.'upload_scoresheets.admin.php');
            if ($go == "payments") include (ADMIN.'payments.admin.php');
            if (($_SESSION['prefsEval'] == 1) && ($go == "eval")) include (EVALS.'admin.eval.php');

            if ($_SESSION['userLevel'] == "0") {

                if ($go == "styles") include (ADMIN.'styles.admin.php');
                if ($go == "archive") include (ADMIN.'archive.admin.php');
                if ($go == "make_admin") include (ADMIN.'make_admin.admin.php');
                if ($go == "contest_info") include (ADMIN.'competition_info.admin.php');
                if ($go == "preferences") include (ADMIN.'site_preferences.admin.php');
                if ($go == "sponsors") include (ADMIN.'sponsors.admin.php');
                if ($go == "style_types") include (ADMIN.'style_types.admin.php');
                if ($go == "special_best") include (ADMIN.'special_best.admin.php');
                if ($go == "special_best_data") include (ADMIN.'special_best_data.admin.php');
                if ($go == "mods") include (ADMIN.'mods.admin.php');
                if ($go == "upload") include (ADMIN.'upload.admin.php');
                if ($go == "change_user_password") include (ADMIN.'change_user_password.admin.php');
                if ($go == "dates") include (ADMIN.'all_dates.admin.php');

            }

        } ?>
</div><!-- ./container-fluid -->
<!-- ./Admin Pages -->
<?php } elseif (($_SESSION['prefsEval'] == 1) && ($section == "evaluation") && ($logged_in)) { 
    if (($view == "admin") && ($filter == "default")) $container_eval = "container-fluid";
    else $container_eval = "container";
?>
<!-- Electronic Scoresheets Container -->
<div class="<?php echo $container_eval; ?>">
    <div class="page-header">
            <h1><?php echo $header_output; ?></h1>
        </div>
    <?php 
        if ($go == "default") include (EVALS.'default.eval.php');
        if ($go == "scoresheet") include (EVALS.'scoresheet.eval.php');
    ?>
</div><!-- ./container-fluid -->
<?php } else { ?>
<!-- Public Pages (Fixed Layout with Sidebar) -->
<div id="main-content" class="container">
    <div class="row">
        <div class="col col-lg-9 col-md-8 col-sm-12 col-xs-12">
        <?php if ($section != "competition") { ?>
        <div class="page-header">
            <h1><?php echo $header_output; ?></h1>
        </div>
        <?php }

            if (ENABLE_MARKDOWN) {
                include (CLASSES.'parsedown/Parsedown.php');
                $Parsedown = new Parsedown();
            }

            if (SINGLE) include (SSO.'sections/default.sec.php');

            else {

                if (($section == "default") || ($section == "past-winners")) include (SECTIONS.'default.sec.php');
                if ($section == "entry") include (SECTIONS.'entry_info.sec.php');
                if ($section == "contact") include (SECTIONS.'contact.sec.php');
                if ($section == "volunteers") include (SECTIONS.'volunteers.sec.php');
                if ($section == "sponsors") include (SECTIONS.'sponsors.sec.php');
                if ($section == "register") include (SECTIONS.'register.sec.php');
                if ($section == "login") include (SECTIONS.'login.sec.php');
                // if ($section == "past_winners") include (SECTIONS.'past_winners.sec.php');
                if ($section == "competition") include (SECTIONS.'custom_competition_info.sec.php');

                if ($logged_in) {
                    if ($section == "brewer") include (SECTIONS.'brewer.sec.php');
                    if ($section == "list") include (SECTIONS.'list.sec.php');
                    if ($section == "brew") include (SECTIONS.'brew.sec.php');
                    if ($section == "pay") include (SECTIONS.'pay.sec.php');
                    if ($section == "user") include (SECTIONS.'user.sec.php');
                }

            }

        ?>
        </div><!-- ./left column -->
        <div class="sidebar col col-lg-3 col-md-4 col-sm-12 col-xs-12">
            <?php include (SECTIONS.'sidebar.sec.php'); ?>
        </div><!-- ./sidebar -->
    </div><!-- ./row -->
    <!-- ./Public Pages -->
</div><!-- ./container -->
<!-- ./Public Pages -->
<?php } ?>

<?php if (DEBUG) { ?>
<div class="<?php echo $container_main; ?> hidden-print">
<?php
include(DEBUGGING.'query_count_end.debug.php');
echo $output_query_count;
?>
</div>
<?php } ?>

<?php if ($_SESSION['prefsUseMods'] == "Y") { ?>
<!-- Mods Bottom -->
<div class="<?php echo $container_main; ?> hidden-print">
<?php include (INCLUDES.'mods_bottom.inc.php'); ?>
</div>
<!-- ./Mods Bottom -->
<?php } ?>
<!-- Footer -->
<footer class="footer hidden-xs">
    <div class="navbar <?php echo $nav_container; ?> navbar-fixed-bottom">
        <div class="<?php echo $container_main; ?> text-center">
            <p class="navbar-text col-md-12 col-sm-12 col-xs-12 text-muted small bcoem-footer"><?php include (SECTIONS.'footer.sec.php'); ?></p>
        </div>
    </div>
</footer><!-- ./footer -->
<!-- ./ Footer -->
<?php 
session_write_close(); 
if ($logged_in) {
$session_end_seconds = (time() + ($session_expire_after * 60));
$session_end = date('Y-m-d H:i:s',$session_end_seconds);
if (!empty($error_output)) $_SESSION['error_output'] = $error_output;
?>
<!-- Session Expiring Modal: 2 Minute Warning -->
<div class="modal fade" id="session-expire-warning" tabindex="-1" role="dialog" aria-labelledby="session-expire-warning-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="session-expire-warning-label"><?php echo $label_session_expire; ?></h4>
      </div>
      <div class="modal-body">
        <p><?php echo $alert_text_090; ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $label_stay_here; ?></button>
        <button type="button" class="btn btn-success" data-dismiss="modal" onclick="window.location.reload()"><?php echo $label_refresh; ?></button>
        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="window.location.replace('<?php echo $base_url; ?>includes/process.inc.php?section=logout&action=logout')"><?php echo $label_log_out; ?></button>
      </div>
    </div>
  </div>
</div>
<!-- Session Expiring Modal: 30 Second Warning -->
<div class="modal fade" id="session-expire-warning-30" tabindex="-1" role="dialog" aria-labelledby="session-expire-warning-30-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="session-expire-warning-30-label"><?php echo $label_session_expire; ?></h4>
      </div>
      <div class="modal-body">
        <p><?php echo $alert_text_091; ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal" onclick="window.location.reload()"><?php echo $label_refresh; ?></button>
        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="window.location.replace('<?php echo $base_url; ?>includes/process.inc.php?section=logout&action=logout')"><?php echo $label_log_out; ?></button>
      </div>
    </div>
  </div>
</div>
<!-- Session Timer Displays and Auto Logout -->
<?php if ((!in_array($go,$datetime_load)) || ($go == "default")) { ?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.32/moment-timezone-with-data.min.js"></script>
<script>
var session_end = moment.tz("<?php echo $session_end; ?>","<?php echo get_timezone($_SESSION['prefsTimeZone']); ?>");
var session_end_min = "<?php echo $session_expire_after; ?>";
var session_end_seconds = "<?php echo $session_end_seconds; ?>";
var session_end_redirect = "<?php echo $base_url; ?>includes/process.inc.php?section=logout&action=logout";
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.countdown/2.2.0/jquery.countdown.min.js"></script>
<script type="text/javascript" src="<?php echo $js_url; ?>autologout.min.js"></script>
<?php } ?>
<?php if (($_SESSION['prefsEval'] == 1) && ($section == "evaluation")) include (EVALS.'warnings.eval.php'); ?>
<?php } // end if ($logged_in) ?>

<script type="text/javascript">
    var section = "<?php echo $section; ?>";
    var action = "<?php echo $action; ?>";
    var go = "<?php echo $go; ?>";
    var edit_style = "<?php echo $action; ?>";
    var user_level = "<?php if ((isset($_SESSION['userLevel'])) && ($bid != "default")) echo $_SESSION['userLevel']; else echo "2"; ?>";
</script>

<?php if (($section == "admin") && ($go == "styles") && ($action != "default")) { ?>
<script>
    var specialty_ipa_subs = <?php echo json_encode($specialty_ipa_subs); ?>;
    var historical_subs = <?php echo json_encode($historical_subs); ?>;
    if (edit_style == "edit") {
        var req_special = "<?php echo $row_styles['brewStyleReqSpec']; ?>";
        var style_type = "<?php echo $row_styles['brewStyleType']; ?>";
    } else { 
        var req_special = "0";
        var style_type = "1";
    }
</script>
<?php } ?>

<?php if ($section == "brew") { ?>   
<script type="text/javascript">
    var style_set = "<?php echo $_SESSION['prefsStyleSet']; ?>";
    var req_pouring = <?php echo json_encode($req_pouring); ?>;
    var req_special_ing_styles = <?php echo json_encode($req_special_ing_styles); ?>;
    var req_strength_styles = <?php echo json_encode($req_strength_styles); ?>;
    var req_sweetness_styles = <?php echo json_encode($req_sweetness_styles); ?>;
    var req_carb_styles = <?php echo json_encode($req_carb_styles); ?>;
    var cider_sweetness_custom_styles = <?php echo json_encode($cider_sweetness_custom_styles); ?>;
    var mead_sweetness_custom_styles = <?php echo json_encode($mead_sweetness_custom_styles); ?>;
    var optional_info_styles = <?php echo json_encode($optional_info_styles); ?>;
    var edit_style = "<?php echo ltrim($view,"0"); ?>";
    var label_this_style = "<?php echo $label_this_style; ?>";
    var req_special_ing_style_info = <?php echo json_encode($styles_entry_text, JSON_UNESCAPED_SLASHES); ?>;
    <?php if (($action == "edit") && (!empty($row_log['brewPossAllergens']))) { ?>
    var possible_allergens = "<?php echo $row_log['brewPossAllergens']; ?>";
    <?php } else { ?>
    possible_allergens = null;      
    <?php } ?>
</script>
<?php } // end if ($section == "brew") ?>

<?php if ($section == "brewer") {

    $brewery_ttb = "false";
    $brewery_prod = "false";

    if (isset($row_brewer['brewerBreweryInfo'])) { 
        $brewery_info = json_decode($row_brewer['brewerBreweryInfo'],true); 
        if (isset($brewery_info['TTB'])) $brewery_ttb = "true";
        if (isset($brewery_info['Production'])) $brewery_prod = "true";
    }

?> 
<script type='text/javascript'>
var club_other = <?php if ($club_other) echo "true"; else echo "false"; ?>;
var brewer_brewery_ttb = <?php echo $brewery_ttb; ?>;
var brewer_brewery_prod = <?php echo $brewery_prod; ?>;
var user_question_answer = "<?php if (isset($_SESSION['userQuestionAnswer'])) echo $_SESSION['userQuestionAnswer']; ?>"

var brewer_country = "<?php if (isset($row_brewer)) echo $row_brewer['brewerCountry']; ?>";
var brewer_judge = "<?php if (isset($row_brewer)) echo $row_brewer['brewerJudge']; ?>";
var brewer_steward = "<?php if (isset($row_brewer)) echo $row_brewer['brewerSteward']; ?>";
var brewer_staff = "<?php if (isset($row_brewer)) echo $row_brewer['brewerStaff']; ?>";

</script>
<?php } ?>

<?php if (($_SESSION['prefsEval'] == 1) && ($section == "evaluation")) include (EVALS.'warnings.eval.php'); ?>

<script src="<?php echo $js_app_url; ?>"></script>
</body>
</html>