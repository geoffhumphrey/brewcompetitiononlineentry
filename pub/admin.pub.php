<!-- Admin Pages -->
<div style="height: 40px;"></div>
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
</div>
<!-- ./Admin Pages -->