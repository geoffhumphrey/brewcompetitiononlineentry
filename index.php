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

$account_pages = array("list","pay","brewer","user","brew","beerxml","pay","evaluation");
if ((!$logged_in) && (in_array($section,$account_pages))) {
    header(sprintf("Location: %s", $base_url."index.php?section=login&msg=99"));
    exit;
}

// ---------------------------------------------------------------------------------

// ---------------------------- Admin Only Functions -------------------------------

if ($section == "admin") {

    // Redirect if non-admins try to access admin functions
    if (!$logged_in) {
        header(sprintf("Location: %s", $base_url."index.php?section=login&msg=0"));
        exit;
    }

    if (($logged_in) && ($_SESSION['userLevel'] > 1)) {
        header(sprintf("Location: %s", $base_url."index.php?msg=4"));
        exit;
    }

    require_once (LIB.'admin.lib.php');
    require_once (DB.'admin_common.db.php');
    require_once (DB.'judging_locations.db.php');
    require_once (DB.'stewarding.db.php');
    require_once (DB.'dropoff.db.php');
    require_once (DB.'contacts.db.php');
}

// ---------------------------------------------------------------------------------

// ---------------------------- Various Functions ----------------------------------

// Testing
if ((TESTING) || (DEBUG)) {
    $mtime = microtime();
    $mtime = explode(" ",$mtime);
    $mtime = $mtime[1] + $mtime[0];
    $starttime = $mtime;
}

if (DEBUG) include (DEBUGGING.'query_count_begin.debug.php');

// Hosted installations
if (HOSTED) check_hosted_gh();

// Perform version check if NOT going into setup
if (strpos($section, 'step') === FALSE)  {
    version_check($version,$current_version,$current_version_date_display);
}

// Bootstrap layout containers
if (($section == "admin") || ($view == "admin")) {
    $container_main = "container-fluid";
    $nav_container = "navbar-inverse";
}

else {
    $container_main = "container";
    $nav_container = "navbar-default";
}

/* 
 * Load libraries only when needed - for performance
 * Moved to constants.inc.php - 2.1.19
 */

$security_question = array($label_secret_01, $label_secret_05, $label_secret_06, $label_secret_07, $label_secret_08, $label_secret_09, $label_secret_10, $label_secret_11, $label_secret_12, $label_secret_13, $label_secret_14, $label_secret_15, $label_secret_16, $label_secret_17, $label_secret_18, $label_secret_19, $label_secret_20, $label_secret_21, $label_secret_22, $label_secret_23, $label_secret_25, $label_secret_26, $label_secret_27);

// ---------------------------------------------------------------------------------
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
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url."css/common.min.css"; ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo $theme; ?>" />
    <!-- Load BCOE&M Custom JS -->
    <script src="<?php echo $base_url; ?>js_includes/bcoem_custom.min.js"></script>
    <!-- Open Graph Implementation -->
    <?php if (!empty($_SESSION['contestName'])) { ?>
    <meta property="og:title" content="<?php echo $_SESSION['contestName']?>" />
    <?php } ?>
    <?php if (!empty($_SESSION['contestLogo'])) { ?>
    <meta property="og:image" content="<?php echo $base_url."user_images/".$_SESSION['contestLogo']?>" />
    <?php } ?>
    <meta property="og:url" content="<?php echo "http" . ((!empty($_SERVER['HTTPS'])) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" />
</head>
<body>
<script>
try {
    var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE ");
    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))  {
        alert('<?php echo $alert_text_086; ?>');
    }
} catch (error) {
    console.error('Error checking user agent.', error);
}

$(document).ready(function(){
    $("#no-js-alert").hide();
});

</script>
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
        <?php include (SECTIONS.'alerts.sec.php'); ?>
        <?php if ($go == "default") { ?>
        <div id="no-js-alert" class="alert alert-danger lead">
            <i class="fa fa-lg fa-exclamation-circle"></i> <strong><?php echo $alert_text_087; ?></strong>
        </div>
        <?php } ?>
    </div><!-- ./container -->
    <!-- ./ALERTS -->

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
                if ((EVALUATION) && ($go == "eval")) include (EVALS.'admin.eval.php');

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

                }

            } ?>
    </div><!-- ./container-fluid -->
    <!-- ./Admin Pages -->
    
    <?php } elseif ((EVALUATION) && ($section == "evaluation") && ($logged_in)) { 

        if (($view == "admin") && ($filter == "default")) $container_eval = "container-fluid";
        else $container_eval = "container";
    
    ?>
    
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

                    if ($section == "default") include (SECTIONS.'default.sec.php');
                    if ($section == "entry") include (SECTIONS.'entry_info.sec.php');
                    if ($section == "contact") include (SECTIONS.'contact.sec.php');
                    if ($section == "volunteers") include (SECTIONS.'volunteers.sec.php');
                    if ($section == "sponsors") include (SECTIONS.'sponsors.sec.php');
                    if ($section == "register") include (SECTIONS.'register.sec.php');
                    if ($section == "login") include (SECTIONS.'login.sec.php');
                    if ($section == "past_winners") include (SECTIONS.'past_winners.sec.php');
                    if ($section == "competition") include (SECTIONS.'custom_competition_info.sec.php');

                    if ($logged_in) {
                        if ($section == "brewer") include (SECTIONS.'brewer.sec.php');
                        if ($section == "list") include (SECTIONS.'list.sec.php');
                        if ($section == "brew") include (SECTIONS.'brew.sec.php');
                        if ($section == "pay") include (SECTIONS.'pay.sec.php');
                        if ($section == "user") include (SECTIONS.'user.sec.php');
                        if ($section == "beerxml") include (SECTIONS.'beerxml.sec.php');
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
    <footer class="footer hidden-xs hidden-sm hidden-md">
        <div class="navbar <?php echo $nav_container; ?> navbar-fixed-bottom">
            <div class="<?php echo $container_main; ?> text-center">
                <p class="navbar-text col-md-12 col-sm-12 col-xs-12 text-muted small bcoem-footer"><?php include (SECTIONS.'footer.sec.php'); ?></p>
            </div>
        </div>
    </footer><!-- ./footer -->
    <!-- ./ Footer -->
    <?php session_write_close(); ?>
</body>
</html>